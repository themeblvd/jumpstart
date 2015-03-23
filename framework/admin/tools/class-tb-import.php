<?php
/**
 * This class integrates into the WordPress
 * importer plugin to add in additional import
 * options for setting up your site like the
 * current theme's demo website.
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_Import {

	/*--------------------------------------------*/
	/* Properties, private
	/*--------------------------------------------*/

	/**
	 * A single instance of this class.
	 *
	 * @since 2.5.0
	 */
	private static $instance = null;

	/**
	 * Directory paths to XML files used for import.
	 *
	 * @since 2.5.0
	 */
	private $files = array();

	/**
	 * If at any point there's an error, we'll store it here.
	 *
	 * @since 2.5.0
	 */
	private $error = '';

	/*--------------------------------------------*/
	/* Constructor
	/*--------------------------------------------*/

	/**
     * Creates or returns an instance of this class.
     *
     * @since 2.5.0
     *
     * @return Theme_Blvd_Import A single instance of this class.
     */
	public static function get_instance() {

		if ( self::$instance == null ) {
            self::$instance = new self;
        }

        return self::$instance;
	}

	/**
	 * Constructor. Hook everything in.
	 *
	 * @since 2.5.0
	 */
	private function __construct() {

		// Setup paths to XML files used in import. These should
		// be located within the theme.
		$this->set_files();

		// Add any required scripts at Tools > Import
		add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );

		// Run our importer after WordPress has imported
		// the standard sample data file
		add_action( 'import_end', array( $this, 'import' ) );

	}

	/*--------------------------------------------*/
	/* Methods, mutators
	/*--------------------------------------------*/

	/**
	 * Set XML file locations
	 *
	 * @since 2.5.0
	 */
	public function set_files() {

		$this->files = array(
			'theme-settings'	=> null,
			'site-settings'		=> null,
			'site-widgets'		=> null
		);

		$parent = get_template_directory();
		$active = get_stylesheet_directory();
		$dir = apply_filters('themeblvd_import_dir', '/includes/demo/');

		foreach ( $this->files as $key => $value ) {

			$file = '';

			if ( file_exists( $active.$dir.$key.'.xml' ) ) {
				$file = $active.$dir.$key.'.xml';
			} else if ( file_exists( $parent.$dir.$key.'.xml' ) ) {
				$file = $parent.$dir.$key.'.xml';
			}

			if ( $file ) {
				$this->files[$key] = $file;
			}

		}

	}

	/*--------------------------------------------*/
	/* Methods, general
	/*--------------------------------------------*/

	/**
	 * Add javascript to insert HTML markup into form of
	 * the importer. This allows the user to select if they
	 * want to import our demo data with their XML file.
	 *
	 * @since 2.5.0
	 */
	public function add_scripts( $hook ) {

		if ( $this->doing_import() ) {

			$theme = wp_get_theme();
			$name = $theme->get('Name');

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'themeblvd-import', TB_FRAMEWORK_URI . '/admin/assets/js/import.js', array('jquery'), TB_FRAMEWORK_VERSION );

			$locals = apply_filters('themeblvd_import_locals', array(
				'header' 			=> sprintf(__("Import %s Demo", 'themeblvd'), $name),
				'theme_settings'	=> sprintf(__("Import %s demo's theme settings", 'themeblvd'), $name),
				'site_settings'		=> sprintf(__("Import %s demo's important site settings", 'themeblvd'), $name),
				'site_widgets'		=> sprintf(__("Import %s demo's widgets", 'themeblvd'), $name)
			));

			if ( ! $this->has_file('theme-settings') ) {
				$locals['theme_settings'] = '';
			}

			if ( ! $this->has_file('site-settings') ) {
				$locals['site_settings'] = '';
			}

			if ( ! $this->has_file('site-widgets') ) {
				$locals['site_widgets'] = '';
			}

			wp_localize_script( 'themeblvd-import', 'themeblvd_import', $locals );
		}
	}

	/**
	 * Run importer.
	 *
	 * @since 2.5.0
	 */
	public function import() {

		global $_POST;

		if ( ! empty( $_POST['themeblvd_import_theme_settings'] ) || ! empty( $_POST['themeblvd_import_site_settings'] ) || ! empty( $_POST['themeblvd_import_site_widgets'] ) ) {
			if ( ! function_exists( 'simplexml_load_file' ) ) {
				$this->error = __('Simple XML not found in your server\'s PHP configuration.', 'themeblvd');
			}
		}

		$did_something = false;

		// Import theme settings
		if ( ! $this->error && ! empty( $_POST['themeblvd_import_theme_settings'] ) ) {
			if ( $this->has_file('theme-settings') ) {
				$this->do_import('theme-settings');
				$did_something = true;
			} else {
				$this->error = __('One or more of the required XML files could not be found.', 'themeblvd');
			}
		}

		// Import site settings
		if ( ! $this->error && ! empty( $_POST['themeblvd_import_site_settings'] ) ) {
			if ( $this->has_file('site-settings') ) {
				$this->do_import('site-settings');
				$did_something = true;
			} else {
				$this->error = __('One or more of the required XML files could not be found.', 'themeblvd');
			}
		}

		// Import site widgets
		if ( ! $this->error && ! empty( $_POST['themeblvd_import_site_widgets'] ) ) {
			if ( $this->has_file('site-widgets') ) {
				$this->do_import('site-widgets');
				$did_something = true;
			} else {
				$this->error = __('One or more of the required XML files could not be found.', 'themeblvd');
			}
		}

		// Output message to user whether was successful or not
		if ( $did_something ) {

			$theme = wp_get_theme();

			if ( $this->error ) {
				echo '<p>'.sprintf(__('There was an error with setting up your site like the %s demo. %s', 'themeblvd'), $theme->get('Name'), $error).'</p>';
			} else {
				echo '<p>'.sprintf(__('%s demo data has been setup successfully.', 'themeblvd'), $theme->get('Name')).'</p>';
			}
		}


	}

	/**
	 * Import each infividual XML file.
	 *
	 * @since 2.5.0
	 */
	public function do_import( $file ) {

		$import = '';

		$internal_errors = libxml_use_internal_errors(true);
		$import = simplexml_load_file( $this->get_file($file) );

		if ( ! $import ) {
			$this->error = __('One or more of the required XML files could not be read.', 'themeblvd');
			return;
		}

		switch ( $file ) {

			/**
			 * Theme Settings
			 */
			case 'theme-settings' :

				$settings = array();
				$imported = $import->setting;

				if ( $imported ) {
					foreach ( $imported as $setting ) {
						$id = (string)$setting->id;
						$value = (string)$setting->value;
						$settings[$id] = maybe_unserialize(base64_decode($value));
					}
				}

				if ( $settings ) {
					update_option( themeblvd_get_option_name(), $settings );
				}

				break;

			/**
			 * Site Settings
			 */
			case 'site-settings' :

				// WordPress Settings
				$settings = $import->settings->setting;

				if ( $settings ) {
					foreach ( $settings as $setting ) {

						$id = (string)$setting->id;
						$value = (string)$setting->value;

						// These options require that we convert the slug of the
						// selected page to the current ID it got when it was imported.
						if ( $id == 'page_on_front' || $id == 'page_for_posts' ) {
							$value = themeblvd_post_id_by_name($value, 'page');
						}

						update_option( $id, $value );

					}
				}

				// Theme mods
				$theme_mods = $import->thememods->thememod;

				if ( $theme_mods ) {
					foreach ( $theme_mods as $theme_mod ) {

						$id = (string)$theme_mod->id;
						$value = (string)$theme_mod->value;

						set_theme_mod( $id, $value );

					}
				}

				// Assign imported menus
				$menus = $import->menus->menu;
				$assign = array();

				if ( $menus ) {
					foreach ( $menus as $menu ) {

						$location = (string)$menu->location;
						$assignment = (string)$menu->assignment;

						$menu = get_term_by( 'slug', $assignment, 'nav_menu' );
						$assign[$location] = $menu->term_id;

					}
				}

				set_theme_mod( 'nav_menu_locations', $assign );

				// Find Home button and change URL to current
				// site's address. -- Note this will only work
				// if the user hasn't created another menu with
				// a home button first.
				$home = themeblvd_post_id_by_name( 'home', 'nav_menu_item' );

				if ( $home ) {
					update_post_meta( $home, '_menu_item_url', home_url() );
				}

				break;

			/**
			 * Site Widgets
			 */
			case 'site-widgets' :

				// Widget Settings
				$settings = $import->setting;

				if ( $settings ) {
					foreach ( $settings as $setting ) {

						$id = (string)$setting->id;
						$value = (string)$setting->value;

						$value = maybe_unserialize(base64_decode($value));

						// For custom menu widget, we need to attempt
						// to convert menu slugs back to ID's.
						if ( ( $id == 'widget_nav_menu' || $id == 'widget_themeblvd_horz_menu_widget' ) && is_array($value) ) {
							foreach ( $value as $key => $instance ) {
								if ( ! empty( $instance['nav_menu'] ) ) {
									$menu = get_term_by( 'slug', $instance['nav_menu'], 'nav_menu' );
									if ( $menu ) {
										$value[$key]['nav_menu'] = $menu->term_id;
									}
								}
							}
						}

						update_option( $id, $value );
					}
				}

				break;

		}

	}

	/*--------------------------------------------*/
	/* Methods, helpers and accessors
	/*--------------------------------------------*/

	/**
	 * Check if this is step 1 of the WordPress import process.
	 *
	 * @since 2.5.0
	 */
	public function doing_import() {

		global $_GET;
		global $_FILES;

		$screen = get_current_screen();

		if ( $screen->base != 'admin' ) {
			return false;
		}

		if ( ! isset( $_GET['import'] ) || ! isset( $_GET['step'] ) ) {
			return false;
		}

		if ( $_GET['import'] != 'wordpress' ) {
			return false;
		}

		if ( $_GET['step'] != '1' ) {
			return false;
		}

		if ( ! isset( $_FILES['import']['name'] ) ) {
			return false;
		}

		if ( $_FILES['import']['name'] != apply_filters('themeblvd_theme_demo_xml', get_template().'-demo.xml') ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if XML file exists
	 *
	 * @since 2.5.0
	 */
	public function has_file( $file ) {

		if ( $this->files[$file] ) {
			return true;
		}

		return false;
	}

	/**
	 * Return import XML file
	 *
	 * @since 2.5.0
	 */
	public function get_file( $file ) {

		if ( ! isset( $this->files[$file] ) ) {
			return null;
		}

		return $this->files[$file];
	}

}
