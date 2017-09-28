<?php
/**
 * Theme Base helper functions.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.7.0
 */

/**
 * Get path to theme base.
 *
 * @since 2.5.0
 *
 * @param string $base Theme base identifier.
 * @return string Filepath to theme base's directory.
 */
function themeblvd_get_base_path( $base ) {

	return apply_filters( 'themeblvd_base_path', sprintf( '%s/inc/base/%s', get_template_directory(), $base ), $base );

}

/**
 * Get url to theme base.
 *
 * @since 2.5.0
 *
 * @param string $base Theme base identifier.
 * @return string URI to theme base's directory.
 */
function themeblvd_get_base_uri( $base ) {

	return esc_url( apply_filters( 'themeblvd_base_uri', sprintf( '%s/inc/base/%s', get_template_directory_uri(), $base ), $base ) );

}

/**
 * Get default theme base.
 *
 * @since 2.5.0
 *
 * @return string Theme base identifier.
 */
function themeblvd_get_default_base() {

	return apply_filters( 'themeblvd_default_base', 'dev' );

}

/**
 * Get current theme base.
 *
 * @since 2.5.0
 *
 * @return string Theme base identifier.
 */
function themeblvd_get_base() {

	return apply_filters( 'themeblvd_base', get_option( get_template() . '_base', themeblvd_get_default_base() ) );

}
