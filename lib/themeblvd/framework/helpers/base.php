<?php
/**
 * Theme Base Helpers
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.7.0
 */

/**
 * Get filepath to theme base.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $base Theme base identifier.
 * @return string $path Filepath of base directory.
 */
function themeblvd_get_base_path( $base ) {

	$path = sprintf( '%s/inc/base/%s', get_template_directory(), $base );

	/**
	 * Filters the filepath to where a theme base
	 * is located.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $path Filepath of base directory.
	 * @param string $base Theme base identifier.
	 */
	return apply_filters( 'themeblvd_base_path', $path, $base );

}

/**
 * Get url to theme base.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $base Theme base identifier.
 * @return string URI of base directory.
 */
function themeblvd_get_base_uri( $base ) {

	$url = sprintf( '%s/inc/base/%s', get_template_directory_uri(), $base );

	/**
	 * Filters the URL to where a theme base is
	 * located.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $url  URL of base directory.
	 * @param string $base Theme base identifier.
	 */
	$url = apply_filters( 'themeblvd_base_uri', $url, $base );

	return esc_url( $url );

}

/**
 * Get default theme base.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @return string Theme base identifier.
 */
function themeblvd_get_default_base() {

	/**
	 * Filteres the default theme base identifier.
	 *
	 * This gets used for the theme base, when
	 * none has been set.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string Theme base identifer.
	 */
	return apply_filters( 'themeblvd_default_base', 'dev' );

}

/**
 * Get current theme base.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @return string Theme base identifier.
 */
function themeblvd_get_base() {

	$base = get_option(
		get_template() . '_base',
		themeblvd_get_default_base()
	);

	/**
	 * Filters the current theme base.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string Theme base identifier.
	 */
	return apply_filters( 'themeblvd_base', $base );

}
