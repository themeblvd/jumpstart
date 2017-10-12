<?php
/**
 * Option Pages
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.2.0
 */

/**
 * This is a re-usable class used to create options
 * pages within the framework.
 *
 * By default, it's used to create the theme options
 * page. But it's also used by other Theme Blvd plugins
 * to create their options pages.
 *
 * This class incorporates the options interface system,
 * which is actually separate. See themeblvd_option_fields().
 *
 * @since Theme_Blvd 2.2.0
 *
 * @param string $id      A unique ID for this admin page
 * @param array  $options Options for admin page
 * @param array  $args    Arguments to setup various elements of the admin page
 */
class Theme_Blvd_Options_Page {

	/**
	 * ID that the options panel is going to be saved under.
	 *
	 * @since Theme_Blvd 2.2.0
	 * @var array
	 */
	public $id;

	/**
	 * Options for the panel.
	 *
	 * @since Theme_Blvd 2.2.0
	 * @var array
	 */
	public $options;

	/**
	 * Arguments to pass into admin page function.
	 *
	 * @since Theme_Blvd 2.2.0
	 * @var array
	 */
	public $args;

	/**
	 * If sanitization has run yet or not when saving
	 * options.
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var bool
	 */
	private $sanitized = false;

	/**
	 * Whether options page has editor modal.
	 *
	 * @since Theme_Blvd 2.5.0
	 * @var bool
	 */
	public $editor = false;

	/**
	 * Whether options page has code editor modal.
	 *
	 * @since Theme_Blvd 2.5.0
	 * @var bool
	 */
	public $code_editor = false;

	/**
	 * Whether options page has vector icon browser
	 *
	 * @since Theme_Blvd 2.5.0
	 * @var bool
	 */
	public $icons_vector = false;

	/**
	 * Whether options page has image icon browser
	 *
	 * @since Theme_Blvd 2.5.0
	 * @var bool
	 */
	public $icons_image = false;

	/**
	 * Whether options page has post ID browser
	 *
	 * @since Theme_Blvd 2.5.0
	 * @var bool
	 */
	public $find_post_id = false;

	/**
	 * Whether options page has texture browser
	 *
	 * @since Theme_Blvd 2.5.0
	 * @var bool
	 */
	public $textures = false;

	/**
	 * Whether options page needs google maps
	 *
	 * @since Theme_Blvd 2.5.0
	 * @var bool
	 */
	public $gmap = false;

	/**
	 * URL to importer, if enabled
	 *
	 * @since Theme_Blvd 2.5.0
	 * @var string
	 */
	public $importer_url = '';

	/**
	 * Class constructor.
	 *
	 * @since Theme_Blvd 2.2.0
	 *
	 * @param string $id      A unique ID for this admin page.
	 * @param array  $options Options for admin page.
	 * @param array  $args    Arguments to setup various elements of the admin page.
	 */
	public function __construct( $id, $options, $args = null ) {

		$this->args = wp_parse_args( $args, array(
			'page_title'  => __( 'Theme Options', 'jumpstart' ),
			'menu_title'  => __( 'Theme Options', 'jumpstart' ),
			'cap'         => themeblvd_admin_module_cap( 'options' ),
			'menu_slug'   => '',
			'icon'        => '',
			'form_action' => 'options.php',
			'export'      => false,
			'import'      => false,
		));

		if ( ! $this->args['menu_slug'] ) {
			$this->args['menu_slug'] = $id;
		}

		$this->id = $id;

		$this->options = $options;

		add_action( 'admin_menu', array( $this, 'add_page' ) );

		add_action( 'admin_init', array( $this, 'register' ) );

		/*
		 * Here, we'l figure out if any options included in
		 * this options page require hidden modal windows
		 * from the framework.
		 */
		foreach ( $this->options as $option ) {

			// Text option, looking for icon browsers.
			if ( 'text' === $option['type'] ) {

				if ( isset( $option['icon'] ) ) {

					if ( 'vector' === $option['icon'] ) {
						$this->icons_vector = true;
					}

					if ( 'image' === $option['icon'] ) {
						$this->icons_image = true;
					}

					if ( 'post_id' === $option['icon'] ) {
						$this->find_post_id = true;
					}
				}
			}

			// Textarea option, looking for visual or code editor.
			if ( 'textarea' === $option['type'] ) {

				if ( isset( $option['editor'] ) && $option['editor'] ) {
					$this->editor = true;
				}

				if ( isset( $option['code'] ) && $option['code'] ) {
					$this->code_editor = true;
				}
			}

			// Directly embedded code editor.
			if ( 'code' === $option['type'] ) {
				$this->code_editor = true;
			}

			// Selects, looking for texture browser.
			if ( 'select' === $option['type'] ) {
				if ( isset( $option['select'] ) && 'textures' === $option['select'] ) {
					$this->textures = true;
				}
			}

			// Look for "geo" option type.
			if ( 'geo' === $option['type'] ) {
				$this->gmap = true;
			}

			// We can just end the loop, if using all the hidden modals.
			if ( $this->editor && $this->code_editor && $this->icons_vector && $this->icons_image && $this->find_post_id && $this->textures && $this->gmap ) {
				break;
			}
		}

		// Add icon browsers.
		if ( $this->icons_vector || $this->icons_image ) {
			add_action( 'current_screen', array( $this, 'add_icon_browser' ) );
		}

		// Add Post ID browser.
		if ( $this->find_post_id ) {
			add_action( 'current_screen', array( $this, 'add_post_browser' ) );
		}

		// Add texture browsers.
		if ( $this->textures ) {
			add_action( 'current_screen', array( $this, 'add_texture_browser' ) );
		}

		/*
		 * Add Editor into footer, which any textarea type
		 * options with "editor" set to true can utilize.
		 */
		if ( $this->editor ) {

			add_action( 'current_screen', array( $this, 'add_editor' ) );

			// Shortcode generator for Editor modal.
			if ( defined( 'TB_SHORTCODES_PLUGIN_VERSION' ) && version_compare( TB_SHORTCODES_PLUGIN_VERSION, '1.4.0', '>=' ) ) {
				if ( isset( $GLOBALS['_themeblvd_shortcode_generator'] ) ) {

					add_action(
						'admin_footer-appearance_page_' . $this->id,
						array( $GLOBALS['_themeblvd_shortcode_generator'], 'add_modal' )
					);

				}
			}
		}

		/*
		 * Create any objects needed for certain types of options.
		 * Our create() method will ensure no duplicates are created,
		 * and only objects are created on neccessary option types.
		 */
		$advanced = Theme_Blvd_Advanced_Options::get_instance();

		foreach ( $this->options as $option ) {

			$advanced->create( $option['type'] );

		}

		// Allow for exporting.
		if ( $this->args['export'] && class_exists( 'Theme_Blvd_Export_Options' ) ) { // requires Theme Blvd Importer plugin

			$args = array(
				'base_url' => add_query_arg(
					array(
						'page' => $this->id,
					),
					admin_url( 'themes.php' )
				),
				'cancel'   => __( 'Nothing to export. Theme Options have never been saved.', 'jumpstart' ),
			);

			$export = new Theme_Blvd_Export_Options( $this->id, $args );

		} else {

			$this->args['export'] = false;

		}

		// Allow for importing.
		if ( $this->args['import'] && class_exists( 'Theme_Blvd_Import_Options' ) ) { // Requires Theme Blvd Importer plugin.

			$args = array(
				'redirect' => add_query_arg(
					array(
						'page' => $this->id,
					),
					admin_url( 'themes.php' )
				), // Current options page URL
			);

			$import = new Theme_Blvd_Import_Options( $this->id, $args );

			$this->importer_url = $import->get_url(); // URL of page where importer is.

		} else {

			$this->args['import'] = false;

		}

	}

	/**
	 * Register Setting.
	 *
	 * This set of options will all be registered under one
	 * option in a single multi-dimensional array.
	 *
	 * @since Theme_Blvd 2.2.0
	 */
	public function register() {

		register_setting(
			$this->id,
			$this->id,
			array( $this, 'validate' )
		);

	}

	/**
	 * Add the menu page.
	 *
	 * @since Theme_Blvd 2.2.0
	 */
	public function add_page() {

		$admin_page = add_theme_page(
			$this->args['page_title'],
			$this->args['menu_title'],
			$this->args['cap'],
			$this->args['menu_slug'],
			array( $this, 'admin_page' )
		);

		add_action(
			'admin_print_styles-' . $admin_page,
			array( $this, 'load_styles' )
		);

		add_action(
			'admin_print_scripts-' . $admin_page,
			array( $this, 'load_scripts' )
		);

	}

	/**
	 * Enqueue CSS for options page.
	 *
	 * @since Theme_Blvd 2.2.0
	 */
	public function load_styles() {

		$suffix = SCRIPT_DEBUG ? '' : '.min';

		themeblvd_admin_assets( 'styles' );

		wp_enqueue_style(
			'themeblvd-admin-options-page',
			esc_url( TB_FRAMEWORK_URI . "/admin/assets/css/options-page{$suffix}.css" ),
			null,
			TB_FRAMEWORK_VERSION
		);

		// FontAwesome is required for icon browser and shortcode generator.
		if ( $this->icons_vector || $this->editor ) {

			wp_enqueue_style(
				'fontawesome',
				esc_url( TB_FRAMEWORK_URI . "/assets/plugins/fontawesome/css/font-awesome{$suffix}.css" ),
				null,
				TB_FRAMEWORK_VERSION
			);

		}

		/// Shortcode generator for Theme Blvd shortcodes plugin.
		if ( $this->editor ) {

			if ( defined( 'TB_SHORTCODES_PLUGIN_VERSION' ) && version_compare( TB_SHORTCODES_PLUGIN_VERSION, '1.4.0', '>=' ) ) {

				wp_enqueue_style(
					'themeblvd-shortcode-generator',
					esc_url( TB_SHORTCODES_PLUGIN_URI . "/includes/admin/generator/assets/css/generator{$suffix}.css" ),
					false,
					TB_SHORTCODES_PLUGIN_VERSION
				);

			}
		}

		// Code mirror is required for code editor.
		if ( $this->code_editor ) {

			wp_enqueue_style(
				'codemirror',
				esc_url( TB_FRAMEWORK_URI . '/admin/assets/plugins/codemirror/codemirror.min.css' ), // No unminified file.
				null, '4.0'
			);

			wp_enqueue_style(
				'codemirror-theme',
				esc_url( TB_FRAMEWORK_URI . "/admin/assets/plugins/codemirror/themeblvd{$suffix}.css" ),
				null,
				'4.0'
			);

		}
	}

	/**
	 * Enqueue JavaScript for options page.
	 *
	 * @since Theme_Blvd 2.2.0
	 */
	public function load_scripts() {

		$suffix = SCRIPT_DEBUG ? '' : '.min';

		if ( $this->gmap ) {

			$gmaps = 'https://maps.googleapis.com/maps/api/js';

			$gmap_key = themeblvd_get_option( 'gmap_api_key' );

			if ( $gmap_key ) {
				$gmaps = add_query_arg( 'key', $gmap_key, $gmaps );
			}

			wp_enqueue_script( 'themeblvd-gmap', esc_url( $gmaps ), array(), null );

		}

		themeblvd_admin_assets( 'scripts' );

		wp_enqueue_script(
			'themeblvd-admin-options-page',
			esc_url( TB_FRAMEWORK_URI . "/admin/assets/js/options-page{$suffix}.js" ),
			array( 'jquery' ),
			TB_FRAMEWORK_VERSION
		);

		// Shortcode generator for Theme Blvd shortcodes plugin.
		if ( $this->editor ) {

			if ( defined( 'TB_SHORTCODES_PLUGIN_VERSION' ) && version_compare( TB_SHORTCODES_PLUGIN_VERSION, '1.4.0', '>=' ) ) {

				wp_enqueue_script(
					'themeblvd-shortcode-generator',
					esc_url( TB_SHORTCODES_PLUGIN_URI . "/includes/admin/generator/assets/js/generator{$suffix}.js" ),
					false,
					TB_SHORTCODES_PLUGIN_VERSION
				);

			}
		}

		// Code mirror is required for code editor.
		if ( $this->code_editor ) {

			wp_enqueue_script(
				'codemirror',
				esc_url( TB_FRAMEWORK_URI . '/admin/assets/plugins/codemirror/codemirror.min.js' ),
				null,
				'4.0'
			);

			wp_enqueue_script(
				'codemirror-modes',
				esc_url( TB_FRAMEWORK_URI . '/admin/assets/plugins/codemirror/modes.min.js' ),
				null,
				'4.0'
			);

		}

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
	 * @since Theme_Blvd 2.2.0
	 */
	public function admin_page() {

		/**
		 * Filters current settings from the database.
		 *
		 * The filter name isn't all too logical in this circumstance,
		 * but has been around since the beginning; so it must remain!
		 *
		 * @since Theme_Blvd 2.0.0
		 *
		 * @param array Settings from database for entire options page.
		 */
		$settings = apply_filters( 'themeblvd_frontend_options', get_option( $this->id ) );

		/*
		 * Add warning to user if the currently saved theme base doesn't
		 * match up to saved options.
		 *
		 * If the this is the theme options page and theme bases are
		 * being used, this message will basically show any time the
		 * theme base has been changed by the user at Appearance > Theme Base,
		 * but the theme options page hasn't been saved again.
		 *
		 * Because theme bases alter the available options, it's required
		 * that the end-user configure and re-save theme options page when
		 * switching between theme bases.
		 */
		if ( themeblvd_supports( 'admin', 'base' ) && isset( $this->options['theme_base'] ) ) {

			$base = themeblvd_get_base();

			if ( $base && ( empty( $settings['theme_base'] ) || $settings['theme_base'] != $base ) ) {

				add_settings_error(
					$this->id,
					'theme_base_error',
					__( 'Your saved options do not currently match the theme base you\'ve selected. Please configure and save your theme options page.', 'jumpstart' ),
					'themeblvd-error error'
				);

			}
		}

		$fields = themeblvd_option_fields( $this->id, $this->options, $settings );

		settings_errors( $this->id );

		$class = 'wrap tb-options-page';

		if ( themeblvd_get_option_name() == $this->id ) {

			$class .= ' tb-theme-options-wrap';

			foreach ( themeblvd_get_compat( true ) as $plugin ) {

				if ( 'portfolios' !== $plugin && themeblvd_installed( $plugin ) ) {

					$class .= sprintf( ' %s-installed', $plugin );

				}
			}

			if ( strpos( $class, 'installed' ) !== false ) {
				$class .= ' plugins-installed';
			}

			/**
			 * Filter the CSS class that gets added to the wrapping DIV
			 * of an options page.
			 *
			 * If we want to add options for another plugin, you
			 * can ensure the "Plugins" tab is shown by making sure
			 * "plugins-installed" class is filtered on here.
			 *
			 * @since Theme_Blvd 2.5.0
			 *
			 * @param string $class CSS class used on wrapping DIV.
			 * @param string $id    Unique ID for this options page.
			 */
			$class = apply_filters( 'themeblvd_theme_options_wrap_class', $class, $this->id );

		}
		?>
		<div class="<?php echo esc_attr( $class ); ?>">

			<div class="admin-module-header">
				<?php do_action( 'themeblvd_admin_module_header', 'options' ); ?>
			</div>

			<?php if ( $fields[1] ) : ?>
				<h2 class="nav-tab-wrapper"><?php echo $fields[1]; ?></h2>
			<?php else : ?>
				<h2><?php echo esc_html( $this->args['page_title'] ); ?></h2>
			<?php endif; ?>

			<div class="metabox-holder">

				<div id="optionsframework" class="tb-options-js">

					<form id="tb-options-page-form" action="<?php echo $this->args['form_action']; ?>" method="post">

						<?php settings_fields( $this->id ); ?>

						<?php echo $fields[0]; /* Settings */ ?>

						<div id="optionsframework-submit" class="options-page-footer">

							<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( 'Save Options', 'jumpstart' ); ?>" />

							<input type="submit" class="clear-button button-secondary tb-tooltip-link" data-tooltip-text="<?php esc_attr_e( 'Delete options from the database.', 'jumpstart' ); ?>" value="<?php esc_attr_e( 'Clear Options', 'jumpstart' ); ?>" />

							<?php if ( $this->args['export'] ) : ?>

								<?php
								$url = add_query_arg(
									array(
										'page'                          => $this->id,
										'themeblvd_export_' . $this->id => true,
										'security'                      => wp_create_nonce( 'themeblvd_export_' . $this->id ),
									),
									admin_url( 'themes.php' )
								);
								?>

								<a href="<?php echo esc_url( $url ); ?>" class="export-button button-secondary tb-tooltip-link" data-tooltip-text="<?php esc_attr_e( 'Export options to XML file.', 'jumpstart' ); ?>">
									<?php esc_attr_e( 'Export Options', 'jumpstart' ); ?>
								</a>

							<?php endif; ?>

							<?php if ( $this->args['import'] ) : ?>

								<a href="<?php echo esc_url( $this->importer_url ); ?>" class="export-button button-secondary tb-tooltip-link" data-tooltip-text="<?php esc_attr_e( 'Import options from XML file.', 'jumpstart' ); ?>">
									<?php esc_attr_e( 'Import Options', 'jumpstart' ); ?>
								</a>

							<?php endif; ?>

							<div class="clear"></div>

						</div><!-- .options-page-footer (end) -->

					</form><!-- #tb-options-page-form (end) -->

					<div class="tb-footer-text">

						<?php
						/**
						 * Fires at the base of the theme options page.
						 *
						 * @hooked themeblvd_options_footer_text_default - 10
						 *
						 * @since Theme_Blvd 2.2.0
						 */
						do_action( 'themeblvd_options_footer_text' );
						?>

					</div><!-- .tb-footer-text (end) -->

				</div><!-- #optionsframework (end) -->

				<div class="admin-module-footer">

					<?php
					/**
					 * Fires at below everything on the page for
					 * a framework admin page.
					 *
					 * @hooked null
					 *
					 * @since Theme_Blvd 2.2.0
					 *
					 * @param string Type of framework admin page.
					 */
					do_action( 'themeblvd_admin_module_footer', 'options' );
					?>

				</div><!-- .admin-module-footer (end) -->

			</div><!-- .metabox-holder (end) -->

		</div><!-- .wrap (end) -->
		<?php
	}

	/**
	 * Sanitize options.
	 *
	 * This runs after the submit/reset button has been clicked and
	 * validates the inputted data.
	 *
	 * @since Theme_Blvd 2.2.0
	 *
	 * @param  array $input Input from submitted form.
	 * @return array $clean Sanitized options from submitted form.
	 */
	public function validate( $input ) {

		/*
		 * Restore Defaults.
		 *
		 * In the event that the user clicked the "Restore Defaults"
		 * button, the options defined in the theme's options.php
		 * file will be added to the option for the active theme.
		 */
		if ( isset( $_POST['reset'] ) ) {

			add_settings_error(
				$this->id,
				'restore_defaults',
				__( 'Default options restored.', 'jumpstart' ),
				'themeblvd-error error'
			);

			return themeblvd_get_option_defaults( $this->options );

		}

		/*
		 * Import options from XML.
		 *
		 * Importing options from Tools > Import through custom demo
		 * importer system.
		 *
		 * @todo Can we improve this? There are some potential
		 * security holes here.
		 */
		if ( isset( $_POST['themeblvd_import_theme_settings'] ) ) {

			return $input;

		}

		/*
		 * Update Settings.
		 *
		 * Basically, we're just looping through the current options
		 * registered in this set and sanitizing each value from the
		 * $input before sending back the final $clean array.
		 *
		 * So if a value is passed through $input with a key that
		 * doesn't exist in the original $options array, it'll get
		 * left out.
		 */
		$clean = array();

		foreach ( $this->options as $option ) {

			/*
			 * If an $option array doesn't have both an `id` and
			 * `type` key, then skip it; we can't use it.
			 */
			if ( ! isset( $option['id'] ) || ! isset( $option['type'] ) ) {

				continue;

			}

			/*
			 * Before we atually add the final setting to the $clean
			 * array, let's just make sure that ID from the original
			 * options array is formatted properly for saving.
			 */
			$id = preg_replace( '/\W/', '', strtolower( $option['id'] ) );

			/*
			 * When dealing with checkbox type options, they won't exist
			 * in the data passed if they were unchecked; so in this case
			 * we still want them to exist in the final $clean array,
			 * always as a string `1` or `0`, for consistency.
			 */
			if ( 'checkbox' === $option['type'] && ! isset( $input[ $id ] ) ) {

				if ( ! empty( $option['inactive'] ) && 'true' === $option['inactive'] ) {

					$input[ $id ] = '1';

				} else {

					$input[ $id ] = '0';

				}
			}

			/*
			 * Make sure at least an empty array gets sent to sanitization
			 * if no items were checked. Sanitization will result in a `0`
			 * value set for each unchecked box.
			 */
			if ( 'multicheck' === $option['type'] && ! isset( $input[ $id ] ) ) {

				$input[ $id ] = array();

			}

			/*
			 * If the submitted form data doesn't match up with the original
			 * $options array, we want to catch these values and make sure
			 * they still get saved to the final $clean data. In these cases,
			 * the default value will get assigned (if it exists).
			 */
			$excluded_types = array(
				'subgroup_start',
				'subgroup_end',
				'section_start',
				'section_end',
				'heading',
				'info',
			);

			if ( ! isset( $input[ $id ] ) && ! in_array( $option['type'], $excluded_types, true ) ) {

				if ( isset( $option['std'] ) ) {

					$clean[ $id ] = $option['std'];

				} else {

					$clean[ $id ] = '';

				}

				continue;

			}

			/*
			 * For the `slider` option type, if the option set has crop
			 * setting attached, we can apply that for saving the
			 * slider option.
			 */
			if ( 'slider' === $option['type'] ) {

				$crop = 'full';

				if ( ! empty( $input[ $id . '_crop' ] ) ) {

					$crop = wp_kses( $input[ $id . '_crop' ], array() );

				}

				if ( is_array( $input[ $id ] ) ) {

					$input[ $id ]['crop'] = $crop;

				}

			}

			/*
			 * For `button` option type, set checkboxes to `0`` if they
			 * weren't selected by the user.
			 */
			if ( 'button' === $option['type'] ) {

				if ( is_array( $input[ $id ] ) ) {

					if ( ! isset( $input[ $id ]['include_bg'] ) ) {

						$input[ $id ]['include_bg'] = '0';

					}

					if ( ! isset( $input[ $id ]['include_border'] ) ) {

						$input[ $id ]['include_border'] = '0';

					}
				}
			}

			/*
			 * For options that have made it this far, their settings
			 * can only get added to the final $clean array, if a
			 * sanitization function is hooked for their type.
			 *
			 * @see themeblvd_add_sanitization()
			 */
			if ( has_filter( 'themeblvd_sanitize_' . $option['type'] ) ) {

				/*
				 * Filters each setting in the data passed through
				 * $_POST, which matches up with the supplied options
				 * array.
				 *
				 * These filter names vary by the type of option, and
				 * the framework hooks all sanitization here by default.
				 *
				 * @since Theme_Blvd 2.0.0
				 *
				 * @see themeblvd_add_sanitization()
				 *
				 * @param mixed $setting The setting value veing sanitized.
				 * @param array $option  The original option array, for reference.
				 */
				$clean[ $id ] = apply_filters( 'themeblvd_sanitize_' . $option['type'], $input[ $id ], $option );

			}
		}

		/*
		 * Merge option presets.
		 *
		 * If the current options page is utilizing the presets feature,
		 * the gist of what's happenning is that we've passed all
		 * user-submitted settings to the final $clean array already,
		 * but now we're going to override any settings with options
		 * that are part of the preset.
		 */
		if ( ! empty( $_POST['_tb_set_preset'] ) ) {

			$presets = array();

			foreach ( $this->options as $option ) {

				if ( ! empty( $option['preset']['sets'][ $_POST['_tb_set_preset'] ]['settings'] ) ) {

					$presets = $option['preset']['sets'][ $_POST['_tb_set_preset'] ]['settings'];

					break;

				}
			}

			if ( $presets ) {

				$clean = array_merge( $clean, $presets );

			}
		}

		/*
		 * After options have been saved, add a success message upon the
		 * page refreshing.
		 */
		if ( ! $this->sanitized ) { // Avoid success message getting printed more than once.

			add_settings_error(
				$this->id,
				'save_options',
				__( 'Options saved.', 'jumpstart' ),
				'themeblvd-updated updated'
			);

		}

		/*
		 * This is just a simple fail-safe for the odd circumstances where
		 * the WordPress Settings API will fire santiziation more than
		 * once.
		 *
		 * Basically, by setting this to true, it will stop from displaying
		 * the "Options Saved" success message to the user more than once.
		 */
		$this->sanitized = true;

		/**
		 * Filters all data one last time before it's actually passed
		 * to the database.
		 *
		 * @since Theme_Blvd 2.3.0
		 *
		 * @param array $clean Settings being passed to the database.
		 * @param array $input The original data submitted from the form.
		 */
		return apply_filters( 'themeblvd_options_sanitize_' . $this->id, $clean, $input );

	}

	/**
	 * Hook in hidden editor modal.
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	public function add_editor() {

		$page = get_current_screen();

		if ( 'appearance_page_' . $this->id === $page->base ) {

			add_action( 'in_admin_header', 'themeblvd_editor' );

		}

	}

	/**
	 * Hook in hidden icon browser modal.
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	public function add_icon_browser() {

		$page = get_current_screen();

		if ( 'appearance_page_' . $this->id === $page->base ) {

			add_action( 'in_admin_header', array( $this, 'display_icon_browser' ) );

		}

	}

	/**
	 * Display the actual icon browser, hooked to
	 * `in_admin_header` from the previous method.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @see themeblvd_icon_browser()
	 */
	public function display_icon_browser() {

		if ( $this->icons_vector ) {

			themeblvd_icon_browser( array(
				'type' => 'vector',
			));

		}

	}

	/**
	 * Hook in hidden post browser modal.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @see themeblvd_post_browser()
	 */
	public function add_post_browser() {

		$page = get_current_screen();

		if ( 'appearance_page_' . $this->id === $page->base ) {

			add_action( 'in_admin_header', 'themeblvd_post_browser' );

		}

	}

	/**
	 * Hook in hidden texture browser modal.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @see themeblvd_texture_browser()
	 */
	public function add_texture_browser() {

		$page = get_current_screen();

		if ( 'appearance_page_' . $this->id === $page->base ) {

			add_action( 'in_admin_header', 'themeblvd_texture_browser' );

		}

	}

}
