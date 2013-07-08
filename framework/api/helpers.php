<?php
/**
 * Setup framework API's.
 *
 * @global array $_themeblvd_customizer_sections
 * @since 2.1.0
 */
function themeblvd_api_init() {

	// Setup Theme Options API and establish theme settings.
	// From this point client can use themeblvd_get_option()
	Theme_Blvd_Options_API::get_instance();

	// Setup framework stylesheets and API for frontend to
	// modify these stylesheets.
	Theme_Blvd_Stylesheets_API::get_instance();

	// Setup Widget Areas API. This registers all default
	// sidebars and provides methods to modify them and
	// display them.
	Theme_Blvd_Sidebars_API::get_instance();

	// Customizer API
	$GLOBALS['_themeblvd_customizer_sections'] = array();

}

/*------------------------------------------------------------*/
/* (1) Theme Options API Helpers
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_get_core_options' ) ) :
/**
 * Get raw options. This helper function is more
 * for backwards compatibility. Realistically, it
 * doesn't have much use unless an old plugin is
 * still using it.
 *
 * @since 2.1.0
 */
function themeblvd_get_core_options() {
	$api = Theme_Blvd_Options_API::get_instance();
	return $api->get_raw_options();
}
endif;

if ( !function_exists( 'themeblvd_get_formatted_options' ) ) :
/**
 * Get formatted options. Note that options will not be
 * formatted until after WP's after_setup_theme hook.
 *
 * @since 2.1.0
 */
function themeblvd_get_formatted_options() {
	$api = Theme_Blvd_Options_API::get_instance();
	return $api->get_formatted_options();
}
endif;

if ( !function_exists( 'themeblvd_get_option' ) ) :
/**
 * Get theme option
 *
 * @since 2.0.0
 *
 * @param string $primary The primary ID of the option
 * @param string $secondary Optional $secondary ID to traverse deeper into arrays
 */
function themeblvd_get_option( $primary, $seconday = null ) {
	$api = Theme_Blvd_Options_API::get_instance();
	return $api->get_setting( $primary, $seconday );
}
endif;

if ( !function_exists( 'themeblvd_add_option_tab' ) ) :
/**
 * Add theme option tab.
 *
 * @since 2.1.0
 *
 * @param string $tab_id ID of tab to add
 * @param string $tab_name Name of the tab to add
 * @param bool $top Whether the tab should be added to the start or not
 */
function themeblvd_add_option_tab( $tab_id, $tab_name, $top = false ) {
	$api = Theme_Blvd_Options_API::get_instance();
	$api->add_tab( $tab_id, $tab_name, $top );
}
endif;

if ( !function_exists( 'themeblvd_remove_option_tab' ) ) :
/**
 * Remove theme option tab.
 *
 * @since 2.1.0
 *
 * @param string $tab_id ID of tab to add
 */
function themeblvd_remove_option_tab( $tab_id ) {
	$api = Theme_Blvd_Options_API::get_instance();
	$api->remove_tab( $tab_id );
}
endif;

if ( !function_exists( 'themeblvd_add_option_section' ) ) :
/**
 * Add theme option section.
 *
 * @since 2.1.0
 *
 * @param string $tab_id ID of tab section will be located in
 * @param string $section_id ID of new section
 * @param string $section_name Name of new section
 * @param string $section_desc Description of new section
 * @param array $options Options array formatted for Options Framework
 * @param bool $top Whether the option should be added to the top or not
 */
function themeblvd_add_option_section( $tab_id, $section_id, $section_name, $section_desc = null, $options = null, $top = false ) {
	$api = Theme_Blvd_Options_API::get_instance();
	$api->add_section( $tab_id, $section_id, $section_name, $section_desc, $options, $top );
}
endif;

if ( !function_exists( 'themeblvd_remove_option_section' ) ) :
/**
 * Remove theme option section.
 *
 * @since 2.1.0
 *
 * @param string $tab_id ID of tab that section to remove belongs to
 * @param string $section_id ID of section to remove
 */
function themeblvd_remove_option_section( $tab_id, $section_id ) {
	$api = Theme_Blvd_Options_API::get_instance();
	$api->remove_section( $tab_id, $section_id );
}
endif;

if ( !function_exists( 'themeblvd_add_option' ) ) :
/**
 * Add theme option.
 *
 * @since 2.1.0
 *
 * @param string $tab_id ID of tab to add option to
 * @param string $section_id ID of section to add to
 * @param array $option attributes for option, formatted for Options Framework
 * @param string $option_id ID of of your option, note that this id must also be present in $option array
 */
function themeblvd_add_option( $tab_id, $section_id, $option_id, $option ) {
	$api = Theme_Blvd_Options_API::get_instance();
	$api->add_option( $tab_id, $section_id, $option_id, $option );
}
endif;

if ( !function_exists( 'themeblvd_remove_option' ) ) :
/**
 * Remove theme option.
 *
 * @since 2.1.0
 *
 * @param string $tab_id ID of tab to add option to
 * @param string $section_id ID of section to add to
 * @param string $option_id ID of of your option
 */
function themeblvd_remove_option( $tab_id, $section_id, $option_id ) {
	$api = Theme_Blvd_Options_API::get_instance();
	$api->remove_option( $tab_id, $section_id, $option_id );
}
endif;

if ( !function_exists( 'themeblvd_edit_option' ) ) :
/**
 * Remove theme option.
 *
 * @since 2.1.0
 *
 * @param string $tab_id ID of tab to add option to
 * @param string $section_id ID of section to add to
 * @param string $option_id ID of of your option
 * @param string $att Attribute of option to change
 * @param string $value New value for attribute
 */
function themeblvd_edit_option( $tab_id, $section_id, $option_id, $att, $value ) {
	$api = Theme_Blvd_Options_API::get_instance();
	$api->edit_option( $tab_id, $section_id, $option_id, $att, $value );
}
endif;

if ( !function_exists( 'themeblvd_get_option_name' ) ) :
/**
 * For each theme, we use a unique identifier to store
 * the theme's options in the database based on the current
 * name of the theme. This is can be filtered with
 * "themeblvd_option_id".
 *
 * @since 2.1.0
 */
function themeblvd_get_option_name() {
	$api = Theme_Blvd_Options_API::get_instance();
	return $api->get_option_id();
}
endif;

if ( !function_exists( 'themeblvd_get_option_defaults' ) ) :
/**
 * Get default values for set of options.
 *
 * Note: if you're using this function on the frontend, there
 * will be no filters by default and so no results will get
 * returned. You must call themeblvd_add_saniziation() before
 * calling this function on the frontend.
 *
 * @since 2.2.0
 *
 * @param array $options Options formatted for internal options framework
 * @return array $defaults Default values from options
 */
function themeblvd_get_option_defaults( $options ) {

	if ( ! $options || ! is_array( $options ) )
		return array();

	$defaults = array();

	foreach ( $options as $option ) {

		// Skip if any vital items are not set.
		if ( ! isset( $option['id'] ) )
			continue;
		if ( ! isset( $option['std'] ) )
			continue;
		if ( ! isset( $option['type'] ) )
			continue;

		// Continue with adding the option in.
		if ( has_filter( 'themeblvd_sanitize_' . $option['type'] ) )
			$defaults[$option['id']] = apply_filters( 'themeblvd_sanitize_' . $option['type'], $option['std'], $option );
	}

	return $defaults;
}
endif;

/*------------------------------------------------------------*/
/* (2) Builder API Helpers
/* Requires Theme Blvd Layout Builder plugin v1.1.1+
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_get_core_elements' ) ) :
/**
 * Get all core elements of Layout Builder.
 *
 * @since 2.1.0
 */
function themeblvd_get_core_elements() {

	// Get out if Builder API doesn't exist.
	if ( ! class_exists('Theme_Blvd_Builder_API') )
		return;

	// Get registered elements
	$api = Theme_Blvd_Builder_API::get_instance();
	return $api->get_core_elements();
}
endif;

if ( !function_exists( 'themeblvd_get_registered_elements' ) ) :
/**
 * Setup all core theme options of framework, which can
 * then be altered at the theme level.
 *
 * @since 2.2.1
 */
function themeblvd_get_registered_elements() {

	// Get out if Builder API doesn't exist.
	if ( ! class_exists('Theme_Blvd_Builder_API') )
		return;

	// Get registered elements
	$api = Theme_Blvd_Builder_API::get_instance();
	return $api->get_registered_elements();

}
endif;

if ( !function_exists( 'themeblvd_get_elements' ) ) :
/**
 * Get layout builder's elements after new elements
 * have been given a chance to be added at the theme-level.
 *
 * @since 2.1.0
 */
function themeblvd_get_elements() {

	// Get out if Builder API doesn't exist.
	if ( ! class_exists('Theme_Blvd_Builder_API') )
		return;

	// Get elements
	$api = Theme_Blvd_Builder_API::get_instance();
	return $api->get_elements();

}
endif;

if ( !function_exists( 'themeblvd_is_element' ) ) :
/**
 * Check if element is currently registered.
 *
 * @since 2.1.0
 *
 * @param string $element_id ID of element type to check for
 * @return bool If element exists or not
 */
function themeblvd_is_element( $element_id ) {

	// Get out if Builder API doesn't exist.
	if ( ! class_exists('Theme_Blvd_Builder_API') )
		return true; // Keep old Builder plugin from blowing up.

	// Is this a registered element?
	$api = Theme_Blvd_Builder_API::get_instance();
	return $api->is_element( $element_id );

}
endif;

if ( !function_exists( 'themeblvd_add_builder_element' ) ) :
/**
 * Add element to layout builder.
 *
 * @since 2.1.0
 *
 * @param string $element_id ID of element to add
 * @param string $element_name Name of element to add
 * @param string $query_type Type of query if any - none, secondary, or primary
 * @param array $options Options formatted for Options Framework
 * @param string $callback Function to display element on frontend
 */
function themeblvd_add_builder_element( $element_id, $element_name, $query_type, $options, $callback ) {

	// Get out if Builder API doesn't exist.
	if ( ! class_exists('Theme_Blvd_Builder_API') )
		return;

	// Add element
	$api = Theme_Blvd_Builder_API::get_instance();
	$api->add_element( $element_id, $element_name, $query_type, $options, $callback );

}
endif;

if ( !function_exists( 'themeblvd_remove_builder_element' ) ) :
/**
 * Remove element from layout builder.
 *
 * @since 2.1.0
 *
 * @param string $element_id ID of element to remove
 */
function themeblvd_remove_builder_element( $element_id ) {

	// Get out if Builder API doesn't exist.
	if ( ! class_exists('Theme_Blvd_Builder_API') )
		return;

	// Remove element
	$api = Theme_Blvd_Builder_API::get_instance();
	$api->remove_element( $element_id );

}
endif;

if ( !function_exists( 'themeblvd_add_sample_layout' ) ) :
/**
 * Add sample layout to layout builder.
 *
 * @since 2.1.0
 *
 * @param string $element_id ID of element to add
 * @param string $element_name Name of element to add
 * @param string $query_type Type of query if any - none, secondary, or primary
 * @param array $options Options formatted for Options Framework
 * @param string $function_to_display Function to display element on frontend
 */
function themeblvd_add_sample_layout( $layout_id, $layout_name, $preview, $sidebar_layout, $elements ) {

	// Get out on front end or if Builder API doesn't exist.
	if ( ! is_admin() || ! class_exists('Theme_Blvd_Builder_API') )
		return;

	// Add sample layout
	$api = Theme_Blvd_Builder_API::get_instance();
	$api->add_layout( $layout_id, $layout_name, $preview, $sidebar_layout, $elements );

}
endif;

if ( !function_exists( 'themeblvd_remove_sample_layout' ) ) :
/**
 * Remove sample layout from layout builder.
 *
 * @since 2.1.0
 *
 * @param string $element_id ID of element to remove
 */
function themeblvd_remove_sample_layout( $layout_id ) {

	// Get out this on front end or if Builder API doesn't exist.
	if ( ! is_admin() || ! class_exists('Theme_Blvd_Builder_API') )
		return;

	// Remove sample layout
	$api = Theme_Blvd_Builder_API::get_instance();
	$api->remove_layout( $layout_id );

}
endif;

/*------------------------------------------------------------*/
/* (3) Sliders API Helpers
/* Requires Theme Blvd Sliders plugin v1.1+
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_add_slider' ) ) :
/**
 * Add slider type.
 *
 * @since 2.1.0
 *
 * @param string $slider_id ID for new slider type
 * @param string $slider_name Name for new slider type
 * @param array $slide_types Slides types - image, video, custom
 * @param array $media_positions Positions for media - full, align-left, align-right
 * @param array $slide_elements Elements to include in slides - image_link, headline, description, button
 * @param array $options Options formatted for Options Framework
 * @param string $callback Function to display slider on frontend
 */
function themeblvd_add_slider( $slider_id, $slider_name, $slide_types, $media_positions, $slide_elements, $options, $callback ) {

	// Get out if Sliders API doesn't exist.
	if ( ! class_exists('Theme_Blvd_Sliders_API') )
		return;

	$api = Theme_Blvd_Sliders_API::get_instance();
	$api->add( $slider_id, $slider_name, $slide_types, $media_positions, $slide_elements, $options, $callback );

}
endif;

if ( !function_exists( 'themeblvd_remove_slider' ) ) :
/**
 * Remove slider type.
 *
 * @since 2.3.0
 *
 * @param string $slider_id ID for new slider type
 */
function themeblvd_remove_slider( $slider_id ) {

	// Get out if Sliders API doesn't exist.
	if ( ! class_exists('Theme_Blvd_Sliders_API') )
		return;

	$api = Theme_Blvd_Sliders_API::get_instance();
	$api->remove( $slider_id );

}
endif;

/*------------------------------------------------------------*/
/* (4) Widget Areas API Helpers
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_add_sidebar_location' ) ) :
/**
 * Add sidebar location.
 *
 * @since 2.1.0
 *
 * @param string $id ID of location
 * @param string $name Name of location
 * @param string $type Type of location - fixed or collapsible
 * @param string $desc Description or widget area
 */
function themeblvd_add_sidebar_location( $id, $name, $type, $desc = '' ) {
	$api = Theme_Blvd_Sidebars_API::get_instance();
	$api->add_location( $id, $name, $type, $desc );
}
endif;

if ( !function_exists( 'themeblvd_remove_sidebar_location' ) ) :
/**
 * Remove sidebar location.
 *
 * @since 2.1.0
 *
 * @param string $id ID of location
 */
function themeblvd_remove_sidebar_location( $id ) {
	$api = Theme_Blvd_Sidebars_API::get_instance();
	$api->remove_location( $id );
}
endif;

if ( !function_exists( 'themeblvd_get_sidebar_locations' ) ) :
/**
 * Get finalized sidebar locations.
 *
 * @since 2.0.0
 */
function themeblvd_get_sidebar_locations() {
	$api = Theme_Blvd_Sidebars_API::get_instance();
	return $api->get_locations();
}
endif;

if ( !function_exists( 'themeblvd_register_sidebars' ) ) :
/**
 * Register default sidebars (i.e. the "locations").
 *
 * @since 2.0.0
 */
function themeblvd_register_sidebars() {
	themeblvd_deprecated_function( __FUNCTION__, '2.3.0', null, __( 'Default sidebars are now registered within the register() method of the Theme_Blvd_Sidebars_API class.', 'themeblvd' ) );
	$api = Theme_Blvd_Sidebars_API::get_instance();
	$api->register();
}
endif;

if ( !function_exists( 'themeblvd_get_sidebar_location_name' ) ) :
/**
 * Get the user friendly name for a sidebar location.
 *
 * @since 2.0.0
 *
 * @param string $location ID of sidebar location
 * @return string $name name of sidebar location
 */
function themeblvd_get_sidebar_location_name( $location ) {

	$api = Theme_Blvd_Sidebars_API::get_instance();
	$sidebar = $api->get_locations( $location );

	if ( isset( $sidebar['location']['name'] ) )
		return $sidebar['location']['name'];

	return __( 'Floating Widget Area', 'themeblvd' );
}
endif;

if ( !function_exists( 'themeblvd_display_sidebar' ) ) :
/**
 * Display sidebar of widgets.
 *
 * Whether we're in a traditional fixed sidebar or a
 * collapsible widget area like ad space, for example,
 * this function will output the widgets for that
 * widget area using WordPress's dynamic_sidebar function.
 *
 * @since 2.0.0
 *
 * @param string $location the location for the sidebar
 */
function themeblvd_display_sidebar( $location ) {
	$api = Theme_Blvd_Sidebars_API::get_instance();
	$api->display( $location );
}
endif;

/*------------------------------------------------------------*/
/* (5) Stylesheets API
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_add_stylesheet' ) ) :
/**
 * Add custom stylesheet
 *
 * @since 2.1.0
 * @deprecated 2.2.0
 *
 * @param string $handle ID for this stylesheet
 * @param string $src URL to stylesheet
 * @param int $level Level determines where stylesheet gets placed - 1, 2, 3, 4
 * @param string $ver Version of stylesheet
 * @param string $media Type of media target for stylesheet
 */
function themeblvd_add_stylesheet( $handle, $src, $level = 4, $ver = null, $media = 'all' ) {
	$api = Theme_Blvd_Stylesheets_API::get_instance();
	$api->add( $handle, $src, $level, $ver, $media );
}
endif;

if ( !function_exists( 'themeblvd_remove_stylesheet' ) ) :
/**
 * Remove custom stylesheet
 *
 * @since 2.1.0
 *
 * @param string $handle ID for this stylesheet
 */
function themeblvd_remove_stylesheet( $handle ) {
	$api = Theme_Blvd_Stylesheets_API::get_instance();
	$api->remove( $handle );
}
endif;

if ( !function_exists( 'themeblvd_print_styles' ) ) :
/**
 * Print out styles.
 *
 * @since 2.1.0
 *
 * @param int $level Level to apply stylesheets - 1, 2, 3, 4
 */
function themeblvd_print_styles( $level ) {
	$api = Theme_Blvd_Stylesheets_API::get_instance();
	$api->print_styles( $level );
}
endif;

if ( !function_exists( 'themeblvd_user_stylesheets' ) ) :
/**
 * Print out styles.
 *
 * @since 2.1.0
 * @deprecated 2.3.0
 *
 * @param int $level Level to apply stylesheet - 1, 2, 3, 4
 */
function themeblvd_user_stylesheets( $level ) {
	themeblvd_deprecated_function( __FUNCTION__, '2.2.0', 'themeblvd_print_styles' );
	themeblvd_print_styles( $level );
}
endif;