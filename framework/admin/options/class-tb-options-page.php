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
	 * Arguments to pass into add_submenu_page() function.
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
			'closer'		=> true // Needs to be false if option page has no tabs
		);
		$this->args = wp_parse_args( $args, $defaults );

		// Options page menu slug
		if ( ! $this->args['menu_slug'] )
			$this->args['menu_slug'] = $id;

		// Option ID -- i.e. get_option( $id )
		$this->id = $id;

		// Form options
		$this->options = $options;

		// Add admin page and register settings
		add_action( 'admin_menu', array( $this, 'add_page' ) );
		add_action( 'admin_init', array( $this, 'register' ) );

		// Legacy media uploader
		if ( ! function_exists('wp_enqueue_media') )
			add_action( 'admin_init', 'optionsframework_mlu_init' );

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
		wp_enqueue_style( 'themeblvd_admin', TB_FRAMEWORK_URI . '/admin/assets/css/admin-style.min.css', null, TB_FRAMEWORK_VERSION );
		wp_enqueue_style( 'themeblvd_options', TB_FRAMEWORK_URI . '/admin/options/css/admin-style.min.css', null, TB_FRAMEWORK_VERSION );
		wp_enqueue_style( 'color-picker', TB_FRAMEWORK_URI . '/admin/options/css/colorpicker.min.css' );
	}

	/**
	 * Load scripts.
	 *
	 * @since 2.2.0
	 */
	public function load_scripts() {
		wp_enqueue_script( 'jquery-ui-core');
		if ( function_exists( 'wp_enqueue_media' ) )
			wp_enqueue_media();
		wp_enqueue_script( 'themeblvd_admin', TB_FRAMEWORK_URI . '/admin/assets/js/shared.min.js', array('jquery'), TB_FRAMEWORK_VERSION );
		wp_localize_script( 'themeblvd_admin', 'themeblvd', themeblvd_get_admin_locals( 'js' ) );
		wp_enqueue_script( 'themeblvd_options', TB_FRAMEWORK_URI . '/admin/options/js/options.min.js', array('jquery'), TB_FRAMEWORK_VERSION );
		wp_enqueue_script( 'color-picker', TB_FRAMEWORK_URI . '/admin/options/js/colorpicker.min.js', array('jquery') );
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
		$settings = get_option( $this->id );

	    // Setup options form
		$return = themeblvd_option_fields( $this->id, $this->options, $settings, $this->args['closer'] );

		// Icon ID
		$icon_id = ! empty( $this->args['icon'] ) ? $this->args['icon'] : str_replace( '.php', '', $this->args['parent']);

		// Display any errors or update messages.
		settings_errors( $this->id );
		?>
		<div class="wrap">
			<div class="admin-module-header">
				<?php do_action( 'themeblvd_admin_module_header', 'options' ); ?>
			</div>
		    <?php screen_icon( $icon_id ); ?>
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
				        <div id="optionsframework-submit">
							<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( 'Save Options', 'themeblvd' ); ?>" />
							<input type="submit" class="reset-button button-secondary" value="<?php esc_attr_e( 'Restore Defaults', 'themeblvd' ); ?>" />
							<input type="submit" class="clear-button button-secondary" value="<?php esc_attr_e( 'Clear Options', 'themeblvd' ); ?>" />
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
			add_settings_error( $this->id, 'restore_defaults', __( 'Default options restored.', 'themeblvd' ), 'error fade' );
			return themeblvd_get_option_defaults( $this->options );
		}

		// Update Settings --
		// Basically, we're just looping through the current options
		// registered in this set and sanitizing each value from the
		// $input before sending back the final $clean array.

		$clean = array();
		foreach ( $this->options as $option ) {

			// Skip if we don't have an ID or type.
			if ( ! isset( $option['id'] ) || ! isset( $option['type'] ) )
				continue;

			// Make sure ID is formatted right.
			$id = preg_replace( '/\W/', '', strtolower( $option['id'] ) );

			// Set checkbox to false if it wasn't sent in the $_POST
			if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) )
				$input[$id] = '0';

			// Set each item in the multicheck to false if it wasn't sent in the $_POST
			if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) && ! empty( $option['options'] ) ) {
				foreach ( $option['options'] as $key => $value ) {
					$input[$id][$key] = '0';
				}
			}

			// For a value to be submitted to database it must pass through a sanitization filter
			if ( has_filter( 'themeblvd_sanitize_' . $option['type'] ) )
				$clean[$id] = apply_filters( 'themeblvd_sanitize_' . $option['type'], $input[$id], $option );

		}

		// Extend
		$clean = apply_filters( 'themeblvd_options_sanitize_'.$this->id, $clean, $input );

		// Add update message for page re-fresh
		if ( ! $this->sanitized ) // Avoid duplicates
			add_settings_error( $this->id, 'save_options', __( 'Options saved.', 'themeblvd' ), 'updated fade' );

		// We know sanitization has happenned at least
		// once at this point; so set to true.
		$this->sanitized = true;

		// Return sanitized options
		return $clean;
	}

}