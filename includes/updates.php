<?php
/**
 * Enable auto updates and license management.
 */

function jumpstart_updates_init(){		
	if( is_admin() ) {
		add_action( 'init', 'jumpstart_license_admin' );
		add_action( 'init', 'jumpstart_updates' );
	}
}
add_action( 'after_setup_theme', 'jumpstart_updates_init' );

/**
 * Setup auto updates. 
 */

function jumpstart_updates(){

	global $_tb_jumpstart_edd_updater;
	
	// License Key
	$license_options = get_option('jumpstart_license');
	$license_key = '';
	if( ! empty( $license_options['license_key'] ) )
		$license_key = trim( $license_options['license_key'] );
	
	// No license key? Let's blow this joint.
	if( ! $license_key )
		return;
	
	// Include EDD_SL_Theme_Updater class to check and administer updates.
	include_once( get_template_directory() . '/includes/EDD_SL_Theme_Updater.php' );
	
	// Theme Data
	$theme_data = wp_get_theme( get_template() ); // Will ignore Child theme

	// Args
	$args = array( 
		'remote_api_url' 	=> 'http://wpjumpstart.com',	// Store URL
		'license' 			=> $license_key, 				// License key
		'item_name' 		=> $theme_data->get('Name'),	// Name of the theme
		'author'			=> 'Theme Blvd'					// Author's Name
	);
	
	// Run it.
	$_tb_jumpstart_edd_updater = new EDD_SL_Theme_Updater( $args );

}

/**
 * Add admin page for storing license key 
 */

function jumpstart_license_admin(){	

	global $_tb_jumpstart_license_admin;
	
	// Setup options
	$options = array(
		array(
			'name'		=> __( 'Automatic Updates', 'themeblvd' ),
			'desc'		=> __( 'After you\'ve activated your license key from <a href="http://support.themeblvd.com" target="_blank">support.themeblvd.com</a> you can use use it here to receive automatic updates for Jump Start.', 'themeblvd' ),
			'type' 		=> 'section_start'
		),
		array(
			'id' 		=> 'license_key',
			'name'		=> __( 'License Key', 'themeblvd' ),
			'desc'		=> __( '<strong>How to get your license key:</strong><br /><br />1) Login to <a href="http://support.themeblvd.com" target="_blank">support.themeblvd.com</a> with the account you purchased Jump Start.<br /><br />2) Go to <em>My Account > Licenses</em>.<br /><br />3) Activate and copy your license key for your Jump Start purchase.', 'themeblvd' ),
			'type'		=> 'textarea'

		),
		array(
			'type' 		=> 'section_end'
		)
	);

	// Setup arguments for options page
	$args = array(
		'page_title' 	=> __( 'Theme License', 'themeblvd' ),
		'menu_title' 	=> __( 'Theme License', 'themeblvd' ),
		'cap'			=> apply_filters( 'jumpstart_license_admin_cap', 'edit_theme_options' ),
		'closer'		=> false
	);

	// Add options page
	$_tb_jumpstart_license_admin = new Theme_Blvd_Options_Page( 'jumpstart_license', $options, $args );

}