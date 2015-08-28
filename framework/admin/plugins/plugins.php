<?php
/**
 * Run TGM class to tell users just installing
 * the current theme which plugins they should
 * be using.
 *
 * remove_action( 'after_setup_theme', 'themeblvd_plugins' );
 *
 * @since 2.2.0
 */
function themeblvd_plugins() {

	// Include files
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/plugins/class-tb-plugin-management.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/plugins/class-tgm-plugin-activation.php' );

	// Setup plugin management
	$manage = Theme_Blvd_Plugin_Management::get_instance();

	// Hook in TGM Class registration
	add_action( 'tgmpa_register', 'themeblvd_tgm_register' );

}

/**
 * Register the required/recommnended plugins.
 *
 * This function is hooked into tgmpa_register, which
 * is fired within the TGM_Plugin_Activation class
 * constructor.
 *
 * @since 2.2.0
 */
function themeblvd_tgm_register() {

	// Plugins to require/recommend
	$plugins = array(
		'builder' => array(
			'name'		=> 'Theme Blvd Layout Builder',
			'slug'		=> 'theme-blvd-layout-builder',
			'required'	=> false
		),
		/* Not a default recommended plugin as of v2.5
		'sliders' => array(
			'name'		=> 'Theme Blvd Sliders',
			'slug'		=> 'theme-blvd-sliders',
			'required'	=> false
		),
		*/
		'sidebars' => array(
			'name'		=> 'Theme Blvd Widget Areas',
			'slug'		=> 'theme-blvd-widget-areas',
			'required'	=> false
		),
		'widgets' => array(
			'name'		=> 'Theme Blvd Widget Pack',
			'slug'		=> 'theme-blvd-widget-pack',
			'required'	=> false
		),
		'shortcodes' => array(
			'name'		=> 'Theme Blvd Shortcodes',
			'slug'		=> 'theme-blvd-shortcodes',
			'required'	=> false
		),
		/*
		'bbpress' => array(
			'name'		=> 'bbPress',
			'slug'		=> 'bbpress',
			'required'	=> false
		),
		*/
		'portfolios' => array(
			'name'		=> 'Portfolios',
			'slug'		=> 'portfolios',
			'required'	=> false
		),
		'tweeple' => array(
			'name'		=> 'Tweeple',
			'slug'		=> 'tweeple',
			'required'	=> false
		),
		'analytics' => array(
			'name'		=> 'Simple Analytics',
			'slug'		=> 'simple-analytics',
			'required'	=> false
		)
		/*
		'woocommerce' => array(
			'name'		=> 'WooCommerce',
			'slug'		=> 'woocommerce',
			'required'	=> false
		)
		*/
	);
	$plugins = apply_filters( 'themeblvd_plugins', $plugins );

	// TGM Class config
	$config = array(
		'default_path' 		=> '',                      // Default absolute path to pre-packaged plugins
		'menu'         		=> 'install-plugins',		// Menu slug
		'has_notices'  		=> true,                    // Show admin notices or not.
        'dismissable'  		=> true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  		=> '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' 		=> false,                   // Automatically activate plugins after installation or not.
		'message' 			=> '',						// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Recommended Plugins', 'themeblvd' ),
			'menu_title'                       			=> __( 'Theme Plugins', 'themeblvd' ),
			'installing'                       			=> __( 'Installing Plugin: %s', 'themeblvd' ), // %s = plugin name.
			'oops'                             			=> __( 'Something went wrong with the plugin API.', 'themeblvd' ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'themeblvd' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'themeblvd' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'themeblvd' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'themeblvd' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'themeblvd' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'themeblvd' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'themeblvd' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'themeblvd' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'themeblvd' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'themeblvd' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', 'themeblvd' ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'themeblvd' ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'themeblvd' ) // %s = dashboard link.
		)
	);
	$config = apply_filters( 'themeblvd_tgm_config', $config );

	// Run it.
	tgmpa( $plugins, $config );

}
