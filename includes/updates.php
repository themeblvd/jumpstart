<?php
/**
 * Enable auto updates and license management.
 */

function jumpstart_updates_init(){		
	if( is_admin() ) {
		add_action( 'init', 'jumpstart_updates' );
	}
}
add_action( 'after_setup_theme', 'jumpstart_updates_init' );

/**
 * Setup auto updates. 
 */

function jumpstart_updates(){

	global $_tb_jumpstart_edd_updater;
	global $_tb_jumpstart_license_admin;
	
	// Include Theme_Blvd_License_Admin class for admin page.
	include_once( get_template_directory() . '/includes/Theme_Blvd_License_Admin.php' );
	
	// Theme Data
	$theme_data = wp_get_theme( get_template() ); // Will ignore Child theme
	
	// Args
	$args = array( 
		'remote_api_url' 	=> 'http://themeblvd.local/network/jumpstart',	// Store URL
		'item_name' 		=> $theme_data->get('Name')		// Name of the theme
	);
	
	// Add admin page.
	$_tb_jumpstart_license_admin = new Theme_Blvd_License_Admin( $args );
	
	// License Key
	$license_key = get_option('themeblvd_license_key');
	
	// No license key? Let's blow this joint.
	if( ! $license_key )
		return;
	
	// Include EDD_SL_Theme_Updater class to check and administer updates.
	include_once( get_template_directory() . '/includes/EDD_SL_Theme_Updater.php' );
	
	// Adjust args for EDD_SL_Theme_Updater class.
	$args['license'] = $license_key;
	$args['author'] = 'Theme Blvd';
	
	// Run Updater.
	$_tb_jumpstart_edd_updater = new EDD_SL_Theme_Updater( $args );

}