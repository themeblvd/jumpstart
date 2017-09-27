<?php
/**
 * Slider API helper functions.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     @@name-package
 */

/**
 * Add slider type.
 *
 * @since @@name-framework 2.1.0
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

	if ( ! class_exists( 'Theme_Blvd_Sliders_API' ) ) {
		return;
	}

	$api = Theme_Blvd_Sliders_API::get_instance();

	$api->add( $slider_id, $slider_name, $slide_types, $media_positions, $slide_elements, $options, $callback );

}

/**
 * Remove slider type.
 *
 * @since @@name-framework 2.3.0
 *
 * @param string $slider_id ID for new slider type.
 */
function themeblvd_remove_slider( $slider_id ) {

	if ( ! class_exists( 'Theme_Blvd_Sliders_API' ) ) {
		return;
	}

	$api = Theme_Blvd_Sliders_API::get_instance();

	$api->remove( $slider_id );

}
