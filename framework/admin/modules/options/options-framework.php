<?php
/**
 * Run Theme Options
 * 
 * We check the user-role before actually adding the admin page the 
 * user sees, however we run the rest of the options framework in 
 * the background just in case its needed for other admin modules.
 */

function optionsframework_rolescheck () {
	global $pagenow;
	if ( themeblvd_supports( 'admin', 'options' ) && current_user_can( themeblvd_admin_module_cap( 'options' ) ) ) {
		add_action( 'admin_menu', 'optionsframework_add_page');
	}
	add_action( 'admin_init', 'optionsframework_init' );
	add_action( 'admin_init', 'optionsframework_mlu_init' );
}
add_action( 'init', 'optionsframework_rolescheck' );

/**
 * Here we're basically just registering the settings for 
 * WordPress with register_setting. We are storing all of 
 * our theme's options in a single array stored in the WP 
 * options table based on the name of the current theme.
 * 
 * This is concept is based on Otto's article on the 
 * Settings API.
 * 
 * http://ottopress.com/2009/wordpress-settings-api-tutorial/
 *
 * Note: You can alter the option name used for storing 
 * the theme options by using the filter "themeblvd_option_id".
 */

if( ! function_exists( 'optionsframework_init' ) ) {
	function optionsframework_init() {
		
		// Get unique identifier for this theme's options.
		$option_name = themeblvd_get_option_name();
		
		// Registers the settings fields and callback
		register_setting( $option_name, $option_name, 'optionsframework_validate' );
		
	}
}

/** 
 * Add a subpage called "Theme Options" to the appearance menu. 
 */

if ( ! function_exists( 'optionsframework_add_page' ) ) {
	function optionsframework_add_page() {
	
		$title = __( 'Theme Options', TB_GETTEXT_DOMAIN );
		$options_page = add_theme_page( $title, $title, themeblvd_admin_module_cap( 'options' ), 'options-framework', 'optionsframework_page' );
		
		// Adds actions to hook in the required css and javascript
		add_action( 'admin_print_styles-'.$options_page,'optionsframework_load_styles');
		add_action( 'admin_print_scripts-'.$options_page, 'optionsframework_load_scripts');
		
	}
}

/** 
 * Loads the CSS 
 */

if( ! function_exists( 'optionsframework_load_styles' ) ) {
	function optionsframework_load_styles() {
		// Enqueued styles
		wp_enqueue_style('admin-style', OPTIONS_FRAMEWORK_DIRECTORY.'css/admin-style.css');
		wp_enqueue_style('sharedframework-style', THEMEBLVD_ADMIN_ASSETS_DIRECTORY . 'css/admin-style.css');
		wp_enqueue_style('color-picker', OPTIONS_FRAMEWORK_DIRECTORY.'css/colorpicker.css');
	}
}

/**
 * Loads the javascript
 */

if( ! function_exists( 'optionsframework_load_scripts' ) ) {
	function optionsframework_load_scripts() {		
		// Enqueued scripts
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('sharedframework-scripts', THEMEBLVD_ADMIN_ASSETS_DIRECTORY . 'js/shared.min.js', array('jquery'));
		wp_localize_script('sharedframework-scripts', 'themeblvd', themeblvd_get_admin_locals( 'js' ) );
		wp_enqueue_script('color-picker', OPTIONS_FRAMEWORK_DIRECTORY.'js/colorpicker.js', array('jquery'));
		wp_enqueue_script('options-custom', OPTIONS_FRAMEWORK_DIRECTORY.'js/options-custom.js', array('jquery'));
	}
}

/* 
 * Builds out the options panel.
 *
 * If we were using the Settings API as it was likely intended 
 * we would use do_settings_sections here.  But as we don't want 
 * the settings wrapped in a table, we'll call our own custom 
 * optionsframework_fields.  See options-interface.php for 
 * specifics on how each individual field is generated.
 *
 * Nonces are provided using the settings_fields()
 */

if ( ! function_exists( 'optionsframework_page' ) ) {
	function optionsframework_page() {
		
		// Get unique identifier for this theme's options.
		$option_name = themeblvd_get_option_name();
		
		// Get any current settings from the database.
		$settings = get_option( $option_name );
	    
	    // Get options.
	    $options = themeblvd_get_formatted_options();
		$return = optionsframework_fields( $option_name, $options, $settings  );
		
		// Display any errors or update messages.
		settings_errors();
		?>
		<div class="wrap">
			<div class="admin-module-header">
				<?php do_action( 'themeblvd_admin_module_header', 'options' ); ?>
			</div>
		    <?php screen_icon( 'themes' ); ?>
		    <h2 class="nav-tab-wrapper">
		        <?php echo $return[1]; ?>
		    </h2>
		    <div class="metabox-holder">
			    <div id="optionsframework">
					<form id="themeblvd_theme_options" action="options.php" method="post">
						<?php settings_fields( $option_name ); ?>
						<?php echo $return[0]; /* Settings */ ?>
				        <div id="optionsframework-submit">
							<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( 'Save Options', TB_GETTEXT_DOMAIN ); ?>" />
							<input type="submit" class="reset-button button-secondary" value="<?php esc_attr_e( 'Restore Defaults', TB_GETTEXT_DOMAIN ); ?>" />
							<input type="submit" class="clear-button button-secondary" value="<?php esc_attr_e( 'Clear Options', TB_GETTEXT_DOMAIN ); ?>" />
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
}

/** 
 * Options footer text
 */

if ( ! function_exists( 'optionsframework_footer_text' ) ) {
	function optionsframework_footer_text() {
		// Theme info and text
		if( function_exists( 'wp_get_theme' ) ) {
			// Use wp_get_theme for WP 3.4+
			$theme_data = wp_get_theme( get_template() );
			$theme_title = $theme_data->get('Name');
			$theme_version = $theme_data->get('Version');
		} else {
			// Deprecated theme data retrieval
			$theme_data = get_theme_data( get_template_directory() . '/style.css' );
			$theme_title = $theme_data['Title'];
			$theme_version = $theme_data['Version'];
		}
		// Changelog
		$changelog = null;
		if ( defined( 'TB_THEME_ID' ) ) {
			$changelog .= ' ( <a href="'.apply_filters( 'themeblvd_changelog_link', 'http://themeblvd.com/changelog/?theme='.TB_THEME_ID.'&TB_iframe=1', TB_THEME_ID ).'" class="thickbox tb-update-log" onclick="return false;">';
			$changelog .= __( 'Changelog', TB_GETTEXT_DOMAIN );
			$changelog .= '</a> )';
		}
		// Output
		echo $theme_title.' <strong>'.$theme_version.'</strong> with Theme Blvd Framework <strong>'.TB_FRAMEWORK_VERSION.'</strong>';
		echo $changelog;
	}
}

/** 
 * Validate Options.
 *
 * This runs after the submit/reset button has been clicked and
 * validates the inputs.
 *
 * @uses $_POST['reset']
 * @uses $_POST['update']
 */

if ( ! function_exists( 'optionsframework_validate' ) ) {
	function optionsframework_validate( $input ) {
		
		// Get unique identifier for this theme's options.
		$option_name = themeblvd_get_option_name();
		
		// Restore Defaults.
		//
		// In the event that the user clicked the "Restore Defaults"
		// button, the options defined in the theme's options.php
		// file will be added to the option for the active theme.
		
		if ( isset( $_POST['reset'] ) ) {
			add_settings_error( $option_name, 'restore_defaults', __( 'Default options restored.', TB_GETTEXT_DOMAIN ), 'error fade' );
			return of_get_default_values();
		}
		
		// Clear options.
		//
		// This gives the user a chance to clear the options from 
		// the database.
		 
		if ( isset( $_POST['clear'] ) ) {
			add_settings_error( $option_name, 'restore_defaults', __( 'Options cleared from database.', TB_GETTEXT_DOMAIN ), 'error fade' );
			return null;
		}
		 
		// Udpdate Settings.
		// 
		// This runs through all registered options and sanitizes them. 
		// However, the catch here that is a bit different than the 
		// original options framework, is that we first check if each 
		// option was present in the $input before adding it our sanitized 
		// options to return.
		//
		// By doing this, when we save from the customizer, if it doesn't 
		// include ALL registered options, it will not effect those options 
		// upon saving that weren't included with the customizer.
		 
		$clean = array();
		$options = themeblvd_get_formatted_options();
		foreach( $options as $option ){

			// Skip if we don't have an ID or type.
			if ( ! isset( $option['id'] ) || ! isset( $option['type'] ) )
				continue;
			
			// Make sure ID is formatted right.
			$id = preg_replace( '/\W/', '', strtolower( $option['id'] ) );

			// Skip if this is the customizer and current option wasn't 
			// sent in the input. This current method means we can't have 
			// any checkboxes or multichecks in the customizer.
			// (something to fix later hopefully)
			if( isset( $_POST['customized'] ) && ! isset( $input[$id] ) )
				continue;

			// Set checkbox to false if it wasn't sent in the $_POST
			if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) )
				$input[$id] = '0';

			// Set each item in the multicheck to false if it wasn't sent in the $_POST
			if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) )
				foreach ( $option['options'] as $key => $value )
					$input[$id][$key] = '0';

			// For a value to be submitted to database it must pass through a sanitization filter
			if ( has_filter( 'of_sanitize_' . $option['type'] ) )
				$clean[$id] = apply_filters( 'of_sanitize_' . $option['type'], $input[$id], $option );
				
		}
		
		// Add update message for page re-fresh
		add_settings_error( 'options-framework', 'save_options', __( 'Options saved.', TB_GETTEXT_DOMAIN ), 'updated fade' );
		
		// Return sanitized options
		return $clean;
	}
}

/**
 * Format Configuration Array.
 *
 * Get an array of all default values as set in
 * options.php. The 'id','std' and 'type' keys need
 * to be defined in the configuration array. In the
 * event that these keys are not present the option
 * will not be included in this function's output.
 *
 * @return array Rey-keyed options configuration array.
 *
 * @access private
 */

if ( ! function_exists( 'of_get_default_values' ) ) {
	function of_get_default_values() {
		$output = array();
		$config = themeblvd_get_formatted_options();
		foreach ( (array) $config as $option ) {
			
			// Skip if any vital items are not set.
			if ( ! isset( $option['id'] ) )
				continue;
			if ( ! isset( $option['std'] ) )
				continue;
			if ( ! isset( $option['type'] ) )
				continue;
			
			// Continue with adding the option in.
			if ( has_filter( 'of_sanitize_' . $option['type'] ) )
				$output[$option['id']] = apply_filters( 'of_sanitize_' . $option['type'], $option['std'], $option );
		}
		return $output;
	}
}