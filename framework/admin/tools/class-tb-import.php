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
	 * Theme Demo number to import.
	 *
	 * @since 2.5.0
	 */
	private $num = '0';

	/**
	 * Directory paths to XML files used for import.
	 *
	 * @since 2.5.0
	 */
	private $files = array();

	/**
	 * Stored meta data for all nav menu items, so we
	 * can process for it our main menu afterwards.
	 *
	 * @since 2.5.0
	 */
	private $nav_meta = array();

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

		// Store nav meta for all processed nav menu items,
		// so we can process it at the end.
		add_filter( 'wp_import_post_data_raw', array( $this, 'store_nav_meta' ) );

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

		global $_FILES;
		global $_POST;

		$file = '';

		if ( ! empty($_FILES['import']['name']) ) {
			$file = $_FILES['import']['name']; // Pull filename on step 1
		} else if ( ! empty($_POST['themeblvd_file_name']) ) {
			$file = $_POST['themeblvd_file_name']; // Pull filename on step 2
		}

		$this->files = array(
			'theme-settings'	=> null,
			'site-settings'		=> null,
			'site-widgets'		=> null
		);

		$num = substr($file, strlen(get_template().'-demo-'), 1);

		if ( intval($num) ) {

			$this->num = $num;

			$parent = get_template_directory();
			$active = get_stylesheet_directory();
			$dir = apply_filters('themeblvd_import_dir', '/includes/demos/demo-'.$this->num.'/', $this->num);

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

		global $_FILES;

		if ( $this->doing_import() ) { // step 1 only

			$theme = wp_get_theme();
			$name = $theme->get('Name');

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'themeblvd-import', TB_FRAMEWORK_URI . '/admin/assets/js/import.js', array('jquery'), TB_FRAMEWORK_VERSION );

			$locals = apply_filters('themeblvd_import_locals', array(
				'header' 			=> sprintf(__("Import %s Demo #%s", 'themeblvd'), $name, $this->num),
				'theme_settings'	=> sprintf(__("Import demo's theme settings", 'themeblvd'), $name),
				'site_settings'		=> sprintf(__("Import demo's important site settings", 'themeblvd'), $name),
				'site_widgets'		=> sprintf(__("Import demo's widgets", 'themeblvd'), $name),
				'file_name'			=> $_FILES['import']['name']
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
	 * Store meta data in object for nav menu item
	 * so we can save it at the end.
	 *
	 * @since 2.5.0
	 */
	function store_nav_meta( $post ) {

		if ( ! empty($post['postmeta']) && ! empty($post['post_type']) && $post['post_type'] == 'nav_menu_item' ) {
			$this->nav_meta[$post['menu_order']] = $post['postmeta'];
		}

		return $post; // pass back through, untouched
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

		// Import theme settings
		if ( ! $this->error && ! empty( $_POST['themeblvd_import_theme_settings'] ) ) {
			if ( $this->has_file('theme-settings') ) {
				$this->do_import('theme-settings');
				$did_something = true;
			} else {
				$this->error = __('One or more of the required XML files could not be found.', 'themeblvd');
			}
		}

		// Output message to user whether was successful or not
		if ( $did_something ) {

			$theme = wp_get_theme();

			if ( $this->error ) {
				echo '<p>'.sprintf(esc_html__('There was an error with setting up your site like the %s demo. %s', 'themeblvd'), $theme->get('Name'), esc_html__($this->error)).'</p>';
			} else {
				echo '<p>'.sprintf(esc_html__('%s demo data has been setup successfully.', 'themeblvd'), $theme->get('Name')).'</p>';
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
				$pages_to_ids = apply_filters('themeblvd_import_pages_to_ids', array('page_on_front', 'page_for_posts', 'woocommerce_shop_page_id', 'woocommerce_cart_page_id', 'woocommerce_checkout_page_id'));

				if ( $settings ) {
					foreach ( $settings as $setting ) {

						$id = (string)$setting->id;
						$value = (string)$setting->value;

						// These options require that we convert the slug of the
						// selected page to the current ID it got when it was imported.
						if ( in_array( $id, $pages_to_ids ) ) {
							$value = themeblvd_post_id_by_name($value, 'page');
						}

						update_option( $id, maybe_unserialize($value) );

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

				// Save meta for menu items in the main menu menu,
				// which we've saved in our current object.
				$items = wp_get_nav_menu_items( $assign['primary'] );

				foreach ( $items as $item ) {
					if ( ! empty($this->nav_meta[$item->menu_order]) ) {
						foreach ( $this->nav_meta[$item->menu_order] as $meta ) {
							if ( in_array( $meta['key'], array('_tb_mega_menu', '_tb_mega_menu_hide_headers', '_tb_bold', '_tb_deactivate_link', '_tb_placeholder') ) ) {
								update_post_meta( $item->ID, $meta['key'], $meta['value'] );
							}
						}
					}
				}

				// Find Home button and change URL to current
				// site's address. -- Note this will only work
				// if the user hasn't created another menu with
				// a home button first.
				$home = themeblvd_post_id_by_name( 'home', 'nav_menu_item' );

				if ( $home ) {
					update_post_meta( $home, '_menu_item_url', esc_url( home_url() ) );
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

		if ( strpos($_FILES['import']['name'], apply_filters('themeblvd_theme_demo_xml', get_template().'-demo-')) === false ) { // XML must be named {theme-name}-demo-{#}.xml
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
