<?php
/**
 * Plugin Compatibility: General Handlers
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.5.0
 */

/**
 * Include compatibility integration for select
 * third-party plugins.
 *
 * This function is hooked to:
 * 1. `after_setup_theme` - 10
 *
 * @since Theme_Blvd 2.5.0
 */
function themeblvd_plugin_compat() {

	// Add compat for bbPress by Automattic.
	if ( themeblvd_installed( 'bbpress' ) && themeblvd_supports( 'plugins', 'bbpress' ) ) {

		include_once( TB_FRAMEWORK_DIRECTORY . '/compat/class-theme-blvd-compat-bbpress.php' );

		$bbpress = Theme_Blvd_Compat_BBPress::get_instance();

	}

	// Add compat for WPML by OnTheGoSystems.
	if ( themeblvd_installed( 'wpml' ) && themeblvd_supports( 'plugins', 'wpml' ) ) {

		include_once( TB_FRAMEWORK_DIRECTORY . '/compat/class-theme-blvd-compat-wpml.php' );

		$wpml = Theme_Blvd_Compat_WPML::get_instance();

	}

	// Add compat for WooCommerce by WooThemes.
	if ( themeblvd_installed( 'woocommerce' ) && themeblvd_supports( 'plugins', 'woocommerce' ) ) {

		include_once( TB_FRAMEWORK_DIRECTORY . '/compat/class-theme-blvd-compat-woocommerce.php' );

		$woocommerce = Theme_Blvd_Compat_WooCommerce::get_instance();

	}

	// Add compat for Gravity Forms by Rocket Genius.
	if ( themeblvd_installed( 'gravityforms' ) && themeblvd_supports( 'plugins', 'gravityforms' ) ) {

		include_once( TB_FRAMEWORK_DIRECTORY . '/compat/class-theme-blvd-compat-gravity-forms.php' );

		$gravityforms = Theme_Blvd_Compat_Gravity_Forms::get_instance();

	}

	// Add compat for Subtitles by Philip Moore.
	if ( themeblvd_installed( 'subtitles' ) && themeblvd_supports( 'plugins', 'subtitles' ) ) {

		include_once( TB_FRAMEWORK_DIRECTORY . '/compat/class-theme-blvd-compat-subtitles.php' );

		$subtitles = Theme_Blvd_Compat_Subtitles::get_instance();

	}

}

/**
 * Get all plugins that the framework provides
 * compatibility for.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  bool  $options Whether to pull only plugins utilizing theme options.
 * @return array $plugins Plugin slugs we're providing compatiblity for.
 */
function themeblvd_get_compat( $options = false ) {

	$plugins[] = 'bbpress';

	$plugins[] = 'gravityforms';

	$plugins[] = 'woocommerce';

	$plugins[] = 'wpml';


	if ( ! $options ) {

		$plugins[] = 'portfolios';

		$plugins[] = 'subtitles';

	}

	/**
	 * Filters the array of plugin slugs for compatible
	 * third-party plugins.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param array $plugins Plugin slugs we're providing compatiblity for.
	 * @param bool  $options Whether to pull only plugins utilizing theme options.
	 */
	return apply_filters( 'themeblvd_plugin_compat', $plugins, $options );

}

/**
 * Check if a compatible plugin is installed and
 * active.
 *
 * In this function, we've setup a switch statement
 * to check a piece we've identified from each
 * third-party plugin, in order to see if it's
 * currently active.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param string $plugin Plugin slug to check.
 * @param bool           Whether plugin is currently active.
 */
function themeblvd_installed( $plugin = '' ) {

	if ( $plugin ) {

		switch ( $plugin ) {

			case 'bbpress':
				if ( class_exists( 'bbPress' ) ) {

					return true;

				}

				break;

			case 'wpml':
				if ( class_exists( 'SitePress' ) ) {

					return true;

				}

				break;

			case 'woocommerce':
				if ( class_exists( 'WooCommerce' ) ) {

					return true;

				}

				break;

			case 'portfolios':
				if ( class_exists( 'Theme_Blvd_Portfolios' ) ) {

					return true;

				}

				break;

			case 'gravityforms':
				if ( class_exists( 'GFForms' ) ) {

					return true;

				}

				break;

			case 'subtitles':
				if ( class_exists( 'Subtitles' ) ) {

					return true;

				}

		}
	}

	return false;

}
