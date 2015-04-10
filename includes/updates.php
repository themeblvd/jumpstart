<?php
/**
 * Enable Envato auto updates.
 */
function themeblvd_envato_updates_init(){
	if ( is_admin() ) {
		add_action( 'init', 'themeblvd_envato_updates' );
	}
}
add_action( 'after_setup_theme', 'themeblvd_envato_updates_init' );

/**
 * Setup auto updates.
 */
function themeblvd_envato_updates(){

	global $_tb_envato_update_options;
	global $_tb_envato_updates;

	// Include update classes
	if ( ! class_exists( 'Envato_Protected_API' ) ) {
		include_once( TB_FRAMEWORK_DIRECTORY . '/admin/updates/class-envato-protected-api.php' );
	}

	if ( ! class_exists( 'Theme_Blvd_Envato_Updates' ) ) {
		include_once( TB_FRAMEWORK_DIRECTORY . '/admin/updates/class-tb-envato-updates.php' );
	}

	// Option name - get_option( 'option-name' )
	$option_name = apply_filters( 'themeblvd_envato_options_name', 'tb-envato' );

	// Admin page
	if ( class_exists('Theme_Blvd_Options_Page') && themeblvd_supports('admin', 'updates') && current_user_can(themeblvd_admin_module_cap('updates')) ) {

		// Options to display on page
		$options = array(
			'start' => array(
				'name'		=> __( 'Configuration', 'themeblvd' ),
				'type' 		=> 'section_start',
				'desc'		=> __('<strong>Warning:</strong> Although there is a backup option below, we recommend that you still always backup your theme files before running any automatic updates. Additionally, it\'s a good idea to never update any plugin or theme on a live website without first testing its compatibility with your specific WordPress site.', 'themeblvd' )
			),
			'username' => array(
				'name'		=> __( 'Envato Username', 'themeblvd' ),
				'id'		=> 'username',
				'desc'		=> __( 'Enter the username that you have purchased the theme with through ThemeForest.', 'themeblvd' ),
				'type' 		=> 'text'
			),
			'api' => array(
				'name'		=> __( 'Envato API Key', 'themeblvd' ),
				'id'		=> 'api',
				'desc'		=> sprintf( __( 'Enter an %s key associated with your Envato username.', 'themeblvd' ), '<a href="http://extras.envato.com/api/" target="_blank">Envato API</a>' ),
				'type' 		=> 'text'
			),
			'backup' => array(
				'name'		=> __( 'Backups', 'themeblvd' ),
				'id'		=> 'backup',
				'desc'		=> __( 'Select if you\'d like a backup made of the previous theme version on your server before updating to the new version.', 'themeblvd' ),
				'std'		=> 'yes',
				'type' 		=> 'radio',
				'options'	=> array(
					'yes' 	=> __( 'Yes, make theme backups when updating.', 'themeblvd' ),
					'no' 	=> __( 'No, do not make theme backups.', 'themeblvd' )
				)
			),
			'end' => array(
				'type' 		=> 'section_end'
			)
		);
		$options = apply_filters( 'themeblvd_envato_options', $options );

		// Setup argument for options page
		$options_args = array(
			'page_title' 	=> __( 'Theme Blvd Envato Updates', 'themeblvd' ),
			'menu_title' 	=> __( 'Theme Updates', 'themeblvd' ),
			'cap'			=> 'edit_theme_options',
			'closer'		=> false // Needs to be false when options page has no tabs
		);
		$options_args = apply_filters( 'themeblvd_envato_options_args', $options_args );

		// Run admin page
		$_tb_envato_update_options = new Theme_Blvd_Options_Page( $option_name, $options, $options_args );

	}

	// Setup arguments for Theme_Blvd_Envato_Updates class based on
	// user-configured options.
	$args = array();

	if ( $settings = get_option( $option_name ) ) {

		$args = apply_filters('themeblvd_envato_update_args', array(
			'envato_username'	=> $settings['username'],
			'envato_api_key'	=> $settings['api'],
			'backup'			=> $settings['backup']
		));

		$_tb_envato_updates = new Theme_Blvd_Envato_Updates($args);
	}

}

/**
 * Clear WordPress's theme update cache when options
 * page is saved. This allows an update check from
 * Envato servers to run immediately after user puts
 * in their update information.
 *
 * @since 2.0.3
 */
function themeblvd_delete_theme_update_transient( $settings ) {

	// Using filter as simply an action hook to
	//clear our transient.
	set_site_transient( 'update_themes', null );

	// And so now pass $settings back through,
	// untouched.
	return $settings;
}
add_filter( 'themeblvd_options_sanitize_tb-envato', 'themeblvd_delete_theme_update_transient' );
