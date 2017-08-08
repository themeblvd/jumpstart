<?php
/**
 * In-dashboard update functions, specific to the theme.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     @@name-package
 */

/**
 * Enable auto updates and license management.
 *
 * @since 1.0.0
 */
function jumpstart_updates_init() {

	if ( is_admin() ) {
		add_action( 'init', 'jumpstart_updates' );
	}

}
add_action( 'after_setup_theme', 'jumpstart_updates_init' );

/**
 * Setup auto updates.
 *
 * @since 1.0.0
 */
function jumpstart_updates() {

	global $_tb_jumpstart_edd_updater;
	global $_tb_jumpstart_license_admin;

	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/updates/class-tb-license-admin.php' );

	$theme = get_template();
	$theme_data = wp_get_theme( $theme );

	$args = array(
		'remote_api_url' => 'http://wpjumpstart.com', // Store URL.
		'item_name'      => $theme_data->get( 'Name' ), // Name of the theme.
	);

	$_tb_jumpstart_license_admin = new Theme_Blvd_License_Admin( $args );

	$license_key = get_option( 'themeblvd_license_key' );
	$license_key_status = get_option( 'themeblvd_license_key_status' );

	if ( ! $license_key || ! $license_key_status ) {
		return;
	}

	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/updates/class-edd-sl-theme-updater.php' );

	$args['license'] = $license_key;
	$args['author'] = 'Theme Blvd';

	/**
	 * Filter changelog URL.
	 *
	 * @param string URL to changelog.
	 * @param string Template slug retrieved from get_template().
	 */
	$args['changelog_url'] = apply_filters( 'themeblvd_changelog_link', 'http://themeblvd.com/changelog/?theme=' . $theme, $theme );

	/*
	 * Run Updater.
	 */
	$_tb_jumpstart_edd_updater = new EDD_SL_Theme_Updater( $args );

}

/**
 * For auto updates, disable sslverify, which will
 * allow for older hosts to download update.
 *
 * @since 2.1.3
 *
 * @param array $args An array of HTTP request arguments.
 * @param string $url The request URL.
 * @return bool $args Modified sample layouts.
 */
function jumpstart_updates_ssl_verify( $args, $url ) {

	if ( false !== strpos( $url, 'wpjumpstart.com' ) ) {
		$args['sslverify'] = false;
	}

	return $args;
}
add_filter( 'http_request_args', 'jumpstart_updates_ssl_verify', 10, 2 );
