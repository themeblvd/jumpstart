<?php
/**
 * Deprecated API helper functions.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.7.0
 */

/**
 * Add slider type.
 *
 * This function is deprecated and will no longer
 * actually do anything.
 *
 * @since @@name-framework 2.1.0
 * @deprecated @@name-framework 2.7.0
 *
 * @param string $slider_id       ID for new slider type.
 * @param string $slider_name     Name for new slider type.
 * @param array  $slide_types     Slides types, `image`, `video` or `custom`.
 * @param array  $media_positions Positions for media, `full`, `align-left` or `align-right`.
 * @param array  $slide_elements  Elements to include in slides, `image_link`, `headline`, `description` or `button`.
 * @param array  $options         Options formatted for Options Framework.
 * @param string $callback        Function to display slider on frontend.
 */
function themeblvd_add_slider( $slider_id, $slider_name, $slide_types, $media_positions, $slide_elements, $options, $callback ) {

	themeblvd_deprecated_function(
		__FUNCTION__,
		'2.7.0',
		null,
		__( 'The Theme Blvd Sliders plugin is no longer supported.' , '@@text-domain' )
	);

}

/**
 * Remove slider type.
 *
 * This function is deprecated and will no longer
 * actually do anything.
 *
 * @since @@name-framework 2.3.0
 * @deprecated @@name-framework 2.7.0
 *
 * @param string $slider_id ID for slider type to remove.
 */
function themeblvd_remove_slider( $slider_id ) {

	themeblvd_deprecated_function(
		__FUNCTION__,
		'2.7.0',
		null,
		__( 'The Theme Blvd Sliders plugin is no longer supported.' , '@@text-domain' )
	);

}

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

	themeblvd_deprecated_function(
		__FUNCTION__,
		'2.2.0',
		null,
		__( 'Custom stylesheets should be enqueued via the WordPress wp_enqueu_scripts action hook. Alternatively, if you\'re using an old custom.css file for child theme CSS customization, you can also just move all that CSS code to your child theme\'s style.css.' , '@@text-domain' )
	);

}

/**
 * Remove custom stylesheet
 *
 * @since @@name-framework 2.1.0
 * @deprecated @@name-framework 2.2.0
 *
 * @param string $handle ID for this stylesheet
 */
function themeblvd_remove_stylesheet( $handle ) {

	themeblvd_deprecated_function(
		__FUNCTION__,
		'2.2.0'
	);

}

/**
 * Print out styles.
 *
 * @since @@name-framework 2.1.0
 * @deprecated @@name-framework 2.2.0
 *
 * @param int $level Level to apply stylesheets - 1, 2, 3, 4
 */
function themeblvd_print_styles( $level ) {

	themeblvd_deprecated_function(
		__FUNCTION__,
		'2.2.0'
	);

}
