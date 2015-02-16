<?php
/**
 * Theme Blvd Options Page. Create an options panel
 * using internal options framework.
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_Options_Page {

	/**
	 * ID that the options panel is going to be saved under.
	 *
	 * @since 2.2.0
	 * @var array
	 */
	public $id;

	/**
	 * Options for the panel.
	 *
	 * @since 2.2.0
	 * @var array
	 */
	public $options;

	/**
	 * Arguments to pass into admin page function.
	 *
	 * @since 2.2.0
	 * @var array
	 */
	public $args;

	/**
	 * If sanitization has run yet or not when saving
	 * options.
	 *
	 * @since 2.3.0
	 * @var bool
	 */
	private $sanitized = false;

	/**
	 * Whether options page has editor modal.
	 *
	 * @since 2.5.0
	 * @var bool
	 */
	public $editor = false;

	/**
	 * Whether options page has code editor modal.
	 *
	 * @since 2.5.0
	 * @var bool
	 */
	public $code_editor = false;

	/**
	 * Whether options page has vector icon browser
	 *
	 * @since 2.5.0
	 * @var bool
	 */
	public $icons_vector = false;

	/**
	 * Whether options page has image icon browser
	 *
	 * @since 2.5.0
	 * @var bool
	 */
	public $icons_image = false;

	/**
	 * Whether options page has post ID browser
	 *
	 * @since 2.5.0
	 * @var bool
	 */
	public $find_post_id = false;

	/**
	 * Whether options page has texture browser
	 *
	 * @since 2.5.0
	 * @var bool
	 */
	public $textures = false;

	/**
	 * Whether options page needs google maps
	 *
	 * @since 2.5.0
	 * @var bool
	 */
	public $gmap = false;

	/**
	 * URL to importer, if enabled
	 *
	 * @since 2.5.0
	 * @var string
	 */
	public $importer_url = '';

	/**
	 * Constructor.
	 *
	 * @since 2.2.0
	 *
	 * @param string $id A unique ID for this admin page
	 * @param array $options Options for admin page
	 * @param array $args Arguments to setup various elements of the admin page
	 */
	public function __construct( $id, $options, $args = null ) {

		// Arguments
		$defaults = array(
			'parent'		=> 'themes.php',
			'page_title' 	=> __( 'Theme Options', 'themeblvd' ),
			'menu_title' 	=> __( 'Theme Options', 'themeblvd' ),
			'cap'			=> themeblvd_admin_module_cap( 'options' ),
			'menu_slug'		=> '',
			'icon'			=> '',
			'form_action'	=> 'options.php',
			'export'		=> false,
			'import'		=> false
		);
		$this->args = wp_parse_args( $args, $defaults );

		// Options page menu slug
		if ( ! $this->args['menu_slug'] ) {
			$this->args['menu_slug'] = $id;
		}

		// Option ID -- i.e. get_option( $id )
		$this->id = $id;

		// Form options
		$this->options = $options;

		// Add admin page and register settings
		add_action( 'admin_menu', array( $this, 'add_page' ) );
		add_action( 'admin_init', array( $this, 'register' ) );

		// Whether options page has hidden modals
		foreach ( $this->options as $option ) {

			// Text option, looking for icon browsers
			if ( $option['type'] == 'text' ) {

				if ( isset( $option['icon'] ) ) {

					if ( $option['icon'] == 'vector' ) {
						$this->icons_vector = true;
					}

					if ( $option['icon'] == 'image' ) {
						$this->icons_image = true;
					}

					if ( $option['icon'] == 'post_id' ) {
						$this->find_post_id = true;
					}
				}
			}

			// Textareas, looking for visual or code editor
			if ( $option['type'] == 'textarea' ) {

				if ( isset( $option['editor'] ) && $option['editor'] ) {
					$this->editor = true;
				}

				if ( isset( $option['code'] ) && $option['code'] ) {
					$this->code_editor = true;
				}
			}

			// Directly embedded code editor
			if ( $option['type'] == 'code' ) {
				$this->code_editor = true;
			}

			// Selects, looking for texture browser
			if ( $option['type'] == 'select' ) {

				if ( isset( $option['select'] ) && $option['select'] == 'textures' ) {
					$this->textures = true;
				}

			}

			// Look for "geo" option type
			if ( $option['type'] == 'geo' ) {
				$this->gmap = true;
			}

			if ( $this->editor && $this->code_editor && $this->icons_vector && $this->icons_image && $this->find_post_id && $this->textures && $this->gmap ) {
				break;
			}
		}

		// Add icon browsers
		if ( $this->icons_vector || $this->icons_image ) {
			add_action( 'current_screen', array( $this, 'add_icon_browser' ) );
		}

		// Add Post ID browser
		if ( $this->find_post_id ) {
			add_action( 'current_screen', array( $this, 'add_post_browser' ) );
		}

		// Add texture browsers
		if ( $this->textures ) {
			add_action( 'current_screen', array( $this, 'add_texture_browser' ) );
		}

		// Add Editor into footer, which any textarea type
		// options with "editor" set to true can utilize.
		if ( $this->editor ) {

			add_action( 'current_screen', array( $this, 'add_editor' ) );

			// Shortcode generator for Editor modal
			if ( defined('TB_SHORTCODES_PLUGIN_VERSION') && version_compare(TB_SHORTCODES_PLUGIN_VERSION, '1.4.0', '>=') ) {
				if ( isset( $GLOBALS['_themeblvd_shortcode_generator'] ) ) {
					add_action( 'admin_footer-appearance_page_'.$this->id, array( $GLOBALS['_themeblvd_shortcode_generator'], 'add_modal' ) );
				}

			}
		}

		// Create any objects needed for certain types of
		// options. Our create() method will ensure no
		// duplicates are created, and only objects are created
		// on neccessary option types.
		$advanced = Theme_Blvd_Advanced_Options::get_instance();

		foreach ( $this->options as $option ) {
			$advanced->create( $option['type'] );
		}

		// Allow for exporting
		if ( $this->args['export'] ) {
			$args = array(
				'base_url'	=> admin_url($this->args['parent'].'?page='.$this->id),
				'cancel'	=> __('Nothing to export. Theme Options have never been saved.', 'themeblvd')
			);
			$export = new Theme_Blvd_Export_Options( $this->id, $args );
		}

		// Allow for importing
		if ( $this->args['import'] ) {
			$args = array(
				'redirect' => admin_url($this->args['parent'].'?page='.$this->id) // Current options page URL
			);
			$import = new Theme_Blvd_Import_Options( $this->id, $args );
			$this->importer_url = $import->get_url(); // URL of page where importer is
		}

	}

	/**
	 * Register Setting.
	 *
	 * This set of options will all be registered under one
	 * option in a single multi-dimensional array.
	 *
	 * @since 2.2.0
	 */
	public function register() {
		// Registers the settings fields and callback
		register_setting( $this->id, $this->id, array( $this, 'validate' ) );
	}

	/**
	 * Add the menu page.
	 *
	 * @since 2.2.0
	 */
	public function add_page() {
		$admin_page = add_submenu_page( $this->args['parent'], $this->args['page_title'], $this->args['menu_title'], $this->args['cap'], $this->args['menu_slug'], array( $this, 'admin_page' ) );
		add_action( 'admin_print_styles-'.$admin_page, array( $this, 'load_styles' ) );
		add_action( 'admin_print_scripts-'.$admin_page, array( $this, 'load_scripts' ) );
		if ( ! function_exists('wp_enqueue_media') ) {
			// Legacy uploader
			add_action( 'admin_print_styles-'.$admin_page, 'optionsframework_mlu_css', 0 );
			add_action( 'admin_print_scripts-'.$admin_page, 'optionsframework_mlu_js', 0 );
		}
	}

	/**
	 * Load CSS.
	 *
	 * @since 2.2.0
	 */
	public function load_styles() {

		// WP Built-in styles
		wp_enqueue_style( 'wp-color-picker' );

		// Framework
		wp_enqueue_style( 'themeblvd_admin', TB_FRAMEWORK_URI . '/admin/assets/css/admin-style.min.css', null, TB_FRAMEWORK_VERSION );
		wp_enqueue_style( 'themeblvd_options', TB_FRAMEWORK_URI . '/admin/options/css/admin-style.min.css', null, TB_FRAMEWORK_VERSION );

		// Shortcode Generator
		if ( $this->editor && defined('TB_SHORTCODES_PLUGIN_VERSION') && version_compare(TB_SHORTCODES_PLUGIN_VERSION, '1.4.0', '>=') ) {
			wp_enqueue_style( 'fontawesome', TB_FRAMEWORK_URI . '/assets/plugins/fontawesome/css/font-awesome.min.css', null, TB_FRAMEWORK_VERSION );
			wp_enqueue_style( 'tb_shortcode_generator', TB_SHORTCODES_PLUGIN_URI . '/includes/admin/generator/assets/css/generator.min.css', false, TB_SHORTCODES_PLUGIN_VERSION );
		}

		// Icon Browser (vector)
		if ( $this->icons_vector ) {
			wp_enqueue_style( 'fontawesome', TB_FRAMEWORK_URI . '/assets/plugins/fontawesome/css/font-awesome.min.css', null, TB_FRAMEWORK_VERSION );
		}

		// Code Editor
		if ( $this->code_editor ) {
			wp_enqueue_style( 'codemirror', TB_FRAMEWORK_URI . '/admin/assets/plugins/codemirror/codemirror.min.css', null, '4.0' );
			wp_enqueue_style( 'codemirror-theme', TB_FRAMEWORK_URI . '/admin/assets/plugins/codemirror/themeblvd.min.css', null, '4.0' );
		}
	}

	/**
	 * Load scripts.
	 *
	 * @since 2.2.0
	 */
	public function load_scripts() {

		// WP Built-in scripts
		wp_enqueue_script( 'jquery-ui-core');
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'wp-color-picker' );

		// WP Built-in Media Modal
		if ( function_exists( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}

		// Google maps
		if ( $this->gmap ) {
			wp_enqueue_script( 'themeblvd_gmap', 'https://maps.googleapis.com/maps/api/js', array(), null );
		}

		// Framework
		wp_enqueue_script( 'populate', TB_FRAMEWORK_URI . '/admin/options/js/populate.min.js', array('jquery'), TB_FRAMEWORK_VERSION );
		wp_enqueue_script( 'themeblvd_admin', TB_FRAMEWORK_URI . '/admin/assets/js/shared.min.js', array('jquery'), TB_FRAMEWORK_VERSION );
		wp_enqueue_script( 'themeblvd_modal', TB_FRAMEWORK_URI . '/admin/assets/js/modal.min.js', array('jquery'), TB_FRAMEWORK_VERSION );
		wp_localize_script( 'themeblvd_admin', 'themeblvd', themeblvd_get_admin_locals( 'js' ) );
		wp_enqueue_script( 'themeblvd_options', TB_FRAMEWORK_URI . '/admin/options/js/options.min.js', array('jquery'), TB_FRAMEWORK_VERSION );

		// Shortcode Generator
		if ( $this->editor && defined('TB_SHORTCODES_PLUGIN_VERSION') && version_compare(TB_SHORTCODES_PLUGIN_VERSION, '1.4.0', '>=') ) {
			wp_enqueue_script( 'tb_shortcode_generator', TB_SHORTCODES_PLUGIN_URI . '/includes/admin/generator/assets/js/generator.min.js', false, TB_SHORTCODES_PLUGIN_VERSION );
		}

		// Code Editor
		if ( $this->code_editor ) {
			wp_enqueue_script( 'codemirror', TB_FRAMEWORK_URI . '/admin/assets/plugins/codemirror/codemirror.min.js', null, '4.0' );
			wp_enqueue_script( 'codemirror-modes', TB_FRAMEWORK_URI . '/admin/assets/plugins/codemirror/modes.min.js', null, '4.0' );
		}

		echo "\n<script type=\"text/javascript\">\n";
		echo "/* <![CDATA[ */\n";
		echo "var themeblvd_presets = {};\n";
		echo "/* ]]> */\n";
		echo "</script>\n";
	}

	/**
	 * Builds out the options panel.
	 *
	 * If we were using the Settings API as it was likely intended
	 * we would use do_settings_sections here.  But as we don't want
	 * the settings wrapped in a table, we'll call our own custom
	 * themeblvd_option_fields.  See options-interface.php for
	 * specifics on how each individual field is generated.
	 *
	 * Nonces are provided using the settings_fields()
	 *
	 * @since 2.2.0
	 */
	public function admin_page() {

		// Get any current settings from the database.
		$settings = apply_filters( 'themeblvd_frontend_options', get_option( $this->id ) ); // Name of filter isn't very logical, but has been around through many versions, so must remain

		if ( themeblvd_supports('admin', 'base') && isset($this->options['theme_base']) ) {

			$base = get_option( get_template().'_base' );

			if ( $base && ( empty($settings['theme_base']) || $settings['theme_base'] != $base ) ) {
				add_settings_error( $this->id, 'theme_base_error', __( 'Your saved options do not currently match the theme base you\'ve selected. Please configure and save your theme options page.', 'themeblvd' ), 'themeblvd-error error' );
			}
		}

	    // Setup options form
		$return = themeblvd_option_fields( $this->id, $this->options, $settings );

		// Display any errors or update messages.
		settings_errors( $this->id );

		// Wrap classes
		$class = 'wrap';

		if ( $this->id == themeblvd_get_option_name() ) {

			$class .= ' tb-theme-options-wrap';

			foreach ( themeblvd_get_compat() as $plugin ) {
				if ( $plugin != 'portfolios' && themeblvd_installed($plugin) ) {
					$class .= sprintf(' %s-installed', $plugin);
				}
			}

			if ( strpos($class, 'installed') !== false ) {
				$class .= ' plugins-installed';
			}

			// If we want to add options for another plugin, you
			// can ensure the "Plugins" tab is shown by making sure
			// "plugins-installed" class is filtered on here.
			$class = apply_filters('themeblvd_theme_options_wrap_class', $class, $this->id);

		}
		?>
		<div class="<?php echo $class; ?>">
			<div class="admin-module-header">
				<?php do_action( 'themeblvd_admin_module_header', 'options' ); ?>
			</div>
		    <h2<?php if ( $return[1] ) echo ' class="nav-tab-wrapper"' ?>>
		        <?php if ( $return[1] ) : ?>
		        	<?php echo $return[1]; ?>
		        <?php else : ?>
		        	<?php echo $this->args['page_title']; ?>
		        <?php endif; ?>
		    </h2>
		    <div class="metabox-holder">
			    <div id="optionsframework" class="tb-options-js">
					<form id="themeblvd_options_page" action="<?php echo $this->args['form_action']; ?>" method="post">
						<?php settings_fields( $this->id ); ?>
						<?php echo $return[0]; /* Settings */ ?>
				        <div id="optionsframework-submit" class="options-page-footer">
							<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( 'Save Options', 'themeblvd' ); ?>" />
							<input type="submit" class="clear-button button-secondary tb-tooltip-link" data-tooltip-text="<?php _e('Delete options from the database.', 'themeblvd'); ?>" value="<?php esc_attr_e( 'Clear Options', 'themeblvd' ); ?>" />
							<?php if ( $this->args['export'] ) : ?>
								<a href="<?php echo admin_url($this->args['parent'].'?page='.$this->id.'&themeblvd_export_'.$this->id.'=true&security='.wp_create_nonce( 'themeblvd_export_'.$this->id )); ?>" class="export-button button-secondary tb-tooltip-link" data-tooltip-text="<?php _e('Export options to XML file.', 'themeblvd'); ?>"><?php _e( 'Export Options', 'themeblvd' ); ?></a>
				           	<?php endif; ?>
				           	<?php if ( $this->args['import'] ) : ?>
								<a href="<?php echo $this->importer_url; ?>" class="export-button button-secondary tb-tooltip-link" data-tooltip-text="<?php _e('Import options from XML file.', 'themeblvd'); ?>"><?php _e( 'Import Options', 'themeblvd' ); ?></a>
				           	<?php endif; ?>
				           	<div class="clear"></div>
						</div>
					</form>
					<div class="tb-footer-text">
						<?php do_action( 'themeblvd_options_footer_text' ); ?>
					</div><!-- .tb-footer-text (end) -->
				</div><!-- #optionsframework (end) -->
				<div class="admin-module-footer">
					<?php do_action( 'themeblvd_admin_module_footer', 'options' ); ?>
				</div><!-- .admin-module-footer (end) -->
			</div><!-- .metabox-holder (end) -->
		</div><!-- .wrap (end) -->
		<?php
	}

	/**
	 * Validate Options.
	 *
	 * This runs after the submit/reset button has been clicked and
	 * validates the inputs.
	 *
	 * @since 2.2.0
	 *
	 * @param array $input Input from submitted form
	 * @return array $clean Sanitized options from submitted form
	 */
	public function validate( $input ) {

		// Restore Defaults --
		// In the event that the user clicked the "Restore Defaults"
		// button, the options defined in the theme's options.php
		// file will be added to the option for the active theme.

		if ( isset( $_POST['reset'] ) ) {
			add_settings_error( $this->id, 'restore_defaults', __( 'Default options restored.', 'themeblvd' ), 'themeblvd-error error fade' );
			return themeblvd_get_option_defaults( $this->options );
		}

		// Update Settings --
		// Basically, we're just looping through the current options
		// registered in this set and sanitizing each value from the
		// $input before sending back the final $clean array.

		$clean = array();
		foreach ( $this->options as $option ) {

			// Skip if we don't have an ID or type.
			if ( ! isset( $option['id'] ) || ! isset( $option['type'] ) ) {
				continue;
			}

			// Make sure ID is formatted right.
			$id = preg_replace( '/\W/', '', strtolower( $option['id'] ) );

			// Set checkbox to false if it wasn't sent in the $_POST
			if ( $option['type'] == 'checkbox' && ! isset( $input[$id] ) ) {
				if ( ! empty( $option['inactive'] ) && $option['inactive'] === 'true' ) {
					$input[$id] = '1';
				} else {
					$input[$id] = '0';
				}
			}

			// Set each item in the multicheck to false if it wasn't sent in the $_POST
			if ( $option['type'] == 'multicheck' && ! isset( $input[$id] ) && ! empty( $option['options'] ) ) {
				foreach ( $option['options'] as $key => $value ) {
					$input[$id][$key] = '0';
				}
			}

			// If option wasn't sent through, set it the default
			if ( ! isset( $input[$id] ) ) {
				if ( isset( $option['std'] ) ) {
					$clean[$id] = $option['std'];
				} else {
					$clean[$id] = '';
				}
				continue;
			}

			// For slider option type, if the option set has crop setting attached,
			// we can apply that for saving the slider option.
			if ( $option['type'] == 'slider' ) {

				$crop = 'full';

				if ( ! empty( $input[$id.'_crop'] ) ) {
					$crop = wp_kses( $input[$id.'_crop'], array() );
				}

				$input[$id]['crop'] = $crop;

			}

			// For button option type, set checkbox to false if it wasn't
			// sent in the $_POST
			if ( $option['type'] == 'button' ) {
				if ( ! isset( $input[$id]['include_bg'] ) ) {
					$input[$id]['include_bg'] = '0';
				}
				if ( ! isset( $input[$id]['include_border'] ) ) {
					$input[$id]['include_border'] = '0';
				}
			}

			// For a value to be submitted to database it must pass through a sanitization filter
			if ( has_filter( 'themeblvd_sanitize_' . $option['type'] ) ) {
				$clean[$id] = apply_filters( 'themeblvd_sanitize_' . $option['type'], $input[$id], $option );
			}

		}

		// Extend
		$clean = apply_filters( 'themeblvd_options_sanitize_'.$this->id, $clean, $input );

		// Add update message for page re-fresh
		if ( ! $this->sanitized ) {
			// Avoid duplicates
			add_settings_error( $this->id, 'save_options', __( 'Options saved.', 'themeblvd' ), 'themeblvd-updated updated fade' );
		}

		// We know sanitization has happenned at least
		// once at this point; so set to true.
		$this->sanitized = true;

		// Return sanitized options
		return $clean;
	}

	/**
	 * Hook in hidden editor modal.
	 *
	 * @since 2.5.0
	 */
	public function add_editor() {

		$page = get_current_screen();

		if ( $page->base == 'appearance_page_'.$this->id ) {
			add_action( 'in_admin_header', 'themeblvd_editor' );
		}
	}

	/**
	 * Hook in hidden icon browser modal(s).
	 *
	 * @since 2.5.0
	 */
	public function add_icon_browser() {

		$page = get_current_screen();

		if ( $page->base == 'appearance_page_'.$this->id ) {
			add_action( 'in_admin_header', array( $this, 'display_icon_browser' ) );
		}
	}
	public function display_icon_browser() {

		if ( $this->icons_vector ) {
			themeblvd_icon_browser( array( 'type' => 'vector' ) );
		}

		if ( $this->icons_image ) {
			themeblvd_icon_browser( array( 'type' => 'image' ) );
		}
	}

	/**
	 * Hook in hidden post browser modal.
	 *
	 * @since 2.5.0
	 */
	public function add_post_browser() {

		$page = get_current_screen();

		if ( $page->base == 'appearance_page_'.$this->id ) {
			add_action( 'in_admin_header', 'themeblvd_post_browser' );
		}
	}

	/**
	 * Hook in hidden texture browser modal.
	 *
	 * @since 2.5.0
	 */
	public function add_texture_browser() {

		$page = get_current_screen();

		if ( $page->base == 'appearance_page_'.$this->id ) {
			add_action( 'in_admin_header', 'themeblvd_texture_browser' );
		}
	}

}
