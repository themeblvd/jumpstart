<?php
/**
 * Include compatibility integration for select plugins.
 *
 * @since 2.5.0
 */
function themeblvd_plugin_compat() {

	// bbPress by Automattic
	if ( themeblvd_installed('bbpress') && themeblvd_supports('plugins', 'bbpress') ) {
		include_once( TB_FRAMEWORK_DIRECTORY . '/compat/bbpress/class-tb-compat-bbpress.php' );
		$bbpress = Theme_Blvd_Compat_bbPress::get_instance();
	}

	// WPML by OnTheGoSystems
	if ( themeblvd_installed('wpml') && themeblvd_supports('plugins', 'wpml') ) {
		include_once( TB_FRAMEWORK_DIRECTORY . '/compat/wpml/class-tb-compat-wpml.php' );
		$wpml = Theme_Blvd_Compat_WPML::get_instance();
	}

	// WooCommerce by WooThemes
	// ... @TODO

}

/**
 * Get all plugins that the framework provideds
 * compatibility for.
 *
 * @since 2.5.0
 */
function themeblvd_get_compat() {
	return apply_filters('themeblvd_plugin_compat', array('bbpress', 'sitepress')); // @TODO woocommerce
}

/**
 * Check if a compatible plugin is installed.
 *
 * @since 2.5.0
 */
function themeblvd_installed( $plugin = '' ) {

	if ( $plugin ) {
		switch ( $plugin ) {

			case 'bbpress' :
				if ( class_exists('bbPress') ) {
					return true;
				}
				break;

			case 'wpml' :
				if ( class_exists('SitePress') ) {
					return true;
				}

			case 'woocommerce' :
				if ( class_exists('WooCommerce') ) {
					return true;
				}
		}
	}

	return false;
}