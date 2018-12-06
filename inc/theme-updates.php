<?php
/**
 * In-Dashboad Updates
 *
 * This file include the in-dashboard update functions,
 * which are specific to the theme.
 *
 * @author    Jason Bobich <info@themeblvd.com>
 * @copyright 2009-2017 Theme Blvd
 * @package   Jump_Start
 * @since     Jump_Start 1.0.0
 */

/**
 * Enable auto updates and license management.
 *
 * @since Jump_Start 1.0.0
 */
function jumpstart_updates_init() {

	if ( is_admin() ) {

		include_once( get_template_directory() . '/inc/admin/class-jump-start-extension.php' );

		include_once( get_template_directory() . '/inc/admin/class-jump-start-license-admin.php' );

		include_once( get_template_directory() . '/inc/admin/class-jump-start-updater.php' );

		add_action( 'init', 'jumpstart_updates' );

	}

}
add_action( 'after_setup_theme', 'jumpstart_updates_init' );

/**
 * Setup auto updates.
 *
 * @since Jump_Start 1.0.0
 */
function jumpstart_updates() {

	$template = get_template();

	$license_admin = new Jump_Start_License_Admin();

	$settings_id = $license_admin->get_settings_id();

	$item_name = $license_admin->get_item_name();

	$item_shortname = $license_admin->get_item_shortname();

	$items = get_option( $settings_id );

	/*
	 * Add backwards compatibility to license admin.
	 *
	 * If the user is updating from prior to Jump Start 2.2.2
	 * their license data will be saved to these old settings;
	 * so let's move the data and delete the old settings.
	 */
	if ( ! $items && get_option( 'themeblvd_license_key' ) ) {

		$items = array();

		$item = $license_admin->check_license( array(
			'item_name' => $item_name,
			'key'       => get_option( 'themeblvd_license_key' ),
		) );

		$items[ $item_shortname ] = $item;

		update_option( $settings_id, $items );

		delete_option( 'themeblvd_license_key' );

		delete_option( 'themeblvd_license_key_status' );

	}

	if ( ! $items ) {

		return;

	}

	$args = array(
		'item_name'      => $item_name,
		'item_shortname' => $item_shortname,
	);

	/**
	 * Filter changelog URL.
	 *
	 * @since Theme_Blvd 2.0.0
	 *
	 * @param string           Website URL to changelog.
	 * @param string $template Template slug retrieved from get_template().
	 */
	$args['changelog_url'] = apply_filters( 'themeblvd_changelog_link', 'https://themeblvd.com/changelog/?theme=' . $template, $template );

	/*
	 * Run Updater.
	 */
	$updater = new Jump_Start_Updater( $items, $args );

}

/**
 * For auto updates, disable sslverify, which will
 * allow for older hosts to download update.
 *
 * @since Jump_Start 2.1.3
 *
 * @param  array  $args An array of HTTP request arguments.
 * @param  string $url  The request URL.
 * @return bool   $args Modified sample layouts.
 */
function jumpstart_updates_ssl_verify( $args, $url ) {

	if ( false !== strpos( $url, 'wpjumpstart.com' ) ) {

		$args['sslverify'] = false;

	}

	return $args;

}
add_filter( 'http_request_args', 'jumpstart_updates_ssl_verify', 10, 2 );
