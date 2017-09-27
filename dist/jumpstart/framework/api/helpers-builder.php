<?php
/**
 * Layout Builer API helper functions.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     Jump_Start
 */

/**
 * Get all core elements of Layout Builder.
 *
 * @since Theme_Blvd 2.1.0
 */
function themeblvd_get_core_elements() {

	if ( ! class_exists( 'Theme_Blvd_Builder_API' ) ) {
		return;
	}

	$api = Theme_Blvd_Builder_API::get_instance();

	return $api->get_core_elements();

}

/**
 * Setup all core theme options of framework, which can
 * then be altered at the theme level.
 *
 * @since Theme_Blvd 2.2.1
 */
function themeblvd_get_registered_elements() {

	if ( ! class_exists( 'Theme_Blvd_Builder_API' ) ) {
		return;
	}

	$api = Theme_Blvd_Builder_API::get_instance();

	return $api->get_registered_elements();

}

/**
 * Get layout builder's elements after new elements
 * have been given a chance to be added at the theme-level.
 *
 * @since Theme_Blvd 2.1.0
 */
function themeblvd_get_elements() {

	if ( ! class_exists( 'Theme_Blvd_Builder_API' ) ) {
		return;
	}

	$api = Theme_Blvd_Builder_API::get_instance();

	return $api->get_elements();

}

/**
 * Check if element is currently registered.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param  string $element_id ID of element type to check for.
 * @return bool               If element exists or not.
 */
function themeblvd_is_element( $element_id ) {

	if ( ! class_exists( 'Theme_Blvd_Builder_API' ) ) {
		return true; // Keep old Builder plugin from blowing up.
	}

	$api = Theme_Blvd_Builder_API::get_instance();

	return $api->is_element( $element_id );

}

/**
 * Add element to layout builder.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param array $args {
 *     All arguments to pass through.
 *
 *     @type string $id       ID of new element.
 *     @type string $name     Name of new element.
 *     @type array  $options  Array of user options for element.
 *     @type string $callback Callback function name to display element on frontend.
 * }
 *
 * Note: The following are the old parameters, which will still work
 * if passed in (for backwards compatibility).
 *
 * @param @deprecated string $element_id   ID of new element.
 * @param @deprecated string $element_name Name of new element.
 * @param @deprecated string $query_type   Type of query if any - none, secondary, or primary (not used at all any more).
 * @param @deprecated array  $options      Array of user options for element.
 * @param @deprecated string $callback     Callback function name to display element on frontend.
 */
function themeblvd_add_builder_element( $args, $element_name = null, $query_type = null, $options = null, $callback = null ) {

	if ( ! class_exists( 'Theme_Blvd_Builder_API' ) ) {
		return;
	}

	/*
	 * Backwards compatibility. If using old API function
	 * parameters, let's convert to the arguments array.
	 */
	if ( ! is_array( $args ) ) {
		$args = array(
			'id'       => $args, // They'll be using $args as the element ID.
			'name'     => $element_name,
			'options'  => $options,
			'callback' => $callback,
		);
	}

	$api = Theme_Blvd_Builder_API::get_instance();

	$api->add_element( $args );

}

/**
 * Remove element from layout builder.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $element_id ID of element to remove.
 */
function themeblvd_remove_builder_element( $element_id ) {

	if ( ! class_exists( 'Theme_Blvd_Builder_API' ) ) {
		return;
	}

	$api = Theme_Blvd_Builder_API::get_instance();

	$api->remove_element( $element_id );

}

/**
 * Add sample layout to layout builder.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $layout_id      Unique ID of sample layout
 * @param string $layout_name    Name of the sample layout
 * @param string $preview        URL to preview image of layout
 * @param string $sidebar_layout Default sidebar layout for layout
 * @param string $import         Absolute path on server to XML file containing layout elements
 */
function themeblvd_add_sample_layout( $layout_id, $layout_name, $preview, $sidebar_layout, $import ) {

	if ( ! is_admin() || ! class_exists( 'Theme_Blvd_Builder_API' ) ) {
		return;
	}

	$api = Theme_Blvd_Builder_API::get_instance();

	$api->add_layout( $layout_id, $layout_name, $preview, $sidebar_layout, $import );

}

/**
 * Remove sample layout from layout builder.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $element_id ID of element to remove
 */
function themeblvd_remove_sample_layout( $layout_id ) {

	if ( ! is_admin() || ! class_exists( 'Theme_Blvd_Builder_API' ) ) {
		return;
	}

	$api = Theme_Blvd_Builder_API::get_instance();

	$api->remove_layout( $layout_id );

}
