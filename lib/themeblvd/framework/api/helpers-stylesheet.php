<?php
/**
 * Stylesheet API helper functions.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     @@name-package
 */

/**
 * Add custom stylesheet
 *
 * @since @@name-framework 2.1.0
 * @deprecated @@name-framework 2.2.0
 *
 * @param string $handle ID for this stylesheet.
 * @param string $src    URL to stylesheet.
 * @param int    $level  Level determines where stylesheet gets placed - 1, 2, 3, 4.
 * @param string $ver    Version of stylesheet.
 * @param string $media  Type of media target for stylesheet.
 */
function themeblvd_add_stylesheet( $handle, $src, $level = 4, $ver = null, $media = 'all' ) {
	$handler = Theme_Blvd_Stylesheet_Handler::get_instance();
	$handler->add( $handle, $src, $level, $ver, $media );
}

/**
 * Remove custom stylesheet
 *
 * @since @@name-framework 2.1.0
 *
 * @param string $handle ID for this stylesheet
 */
function themeblvd_remove_stylesheet( $handle ) {
	$handler = Theme_Blvd_Stylesheet_Handler::get_instance();
	$handler->remove( $handle );
}

/**
 * Print out styles.
 *
 * @since @@name-framework 2.1.0
 *
 * @param int $level Level to apply stylesheets - 1, 2, 3, 4
 */
function themeblvd_print_styles( $level ) {
	$handler = Theme_Blvd_Stylesheet_Handler::get_instance();
	$handler->print_styles( $level );
}

/**
 * Print out styles.
 *
 * @since @@name-framework 2.1.0
 * @deprecated @@name-framework 2.3.0
 *
 * @param int $level Level to apply stylesheet - 1, 2, 3, 4
 */
function themeblvd_user_stylesheets( $level ) {
	themeblvd_deprecated_function( __FUNCTION__, '2.2.0', 'themeblvd_print_styles' );
	themeblvd_print_styles( $level );
}
