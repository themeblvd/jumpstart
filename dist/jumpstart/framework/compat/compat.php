<?php
/**
 * Add third-party plugin compatibility.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     Jump_Start
 */

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
	if ( themeblvd_installed('woocommerce') && themeblvd_supports('plugins', 'woocommerce') ) {
		include_once( TB_FRAMEWORK_DIRECTORY . '/compat/woocommerce/class-tb-compat-woocommerce.php' );
		$woocommerce = Theme_Blvd_Compat_WooCommerce::get_instance();
	}

	// Gravity Forms by Rocket Genius
	if ( themeblvd_installed('gravityforms') && themeblvd_supports('plugins', 'gravityforms') ) {
		include_once( TB_FRAMEWORK_DIRECTORY . '/compat/gravityforms/class-tb-compat-gravity-forms.php' );
		$gravityforms = Theme_Blvd_Compat_Gravity_Forms::get_instance();
	}

	// Subtitles by Philip Moore
	if ( themeblvd_installed('subtitles') && themeblvd_supports('plugins', 'subtitles') ) {
		include_once( TB_FRAMEWORK_DIRECTORY . '/compat/subtitles/class-tb-compat-subtitles.php' );
		$gravityforms = Theme_Blvd_Compat_Subtitles::get_instance();
	}

}

/**
 * Get all plugins that the framework provides
 * compatibility for.
 *
 * @since 2.5.0
 *
 * @param bool $options Whether to pull only plugins utilizing theme options
 */
function themeblvd_get_compat( $options = false ) {

	$plugins[] = 'bbpress';
	$plugins[] = 'woocommerce';
	$plugins[] = 'wpml';

	if ( ! $options ) {
		$plugins[] = 'gravityforms';
		$plugins[] = 'portfolios';
		$plugins[] = 'subtitles';
	}

	return apply_filters('themeblvd_plugin_compat', $plugins, $options);
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
				break;

			case 'woocommerce' :

				if ( class_exists('WooCommerce') ) {
					return true;
				}
				break;

			case 'portfolios' :

				if ( class_exists('Theme_Blvd_Portfolios') ) {
					return true;
				}
				break;

			case 'gravityforms' :

				if ( class_exists('GFForms') ) {
					return true;
				}
				break;

			case 'subtitles' :

				if ( class_exists('Subtitles') ) {
					return true;
				}

		}
	}

	return false;
}
