<?php
/**
 * API helper functions.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     Jump_Start
 */

/**
 * Setup framework handler and API's.
 *
 * @since Theme_Blvd 2.1.0
 */
function themeblvd_api_init() {

	/*
	 * Setup Theme Options API and establish theme settings.
	 * From this point client can use themeblvd_get_option().
	 */
	Theme_Blvd_Options_API::get_instance();

	/*
	 * Setup framework stylesheets and handler for frontend to
	 * modify these stylesheets.
	 */
	Theme_Blvd_Stylesheet_Handler::get_instance();

	/*
	 * Setup widget areas handler. This registers all default
	 * sidebars and provides methods to modify them and
	 * display them.
	 */
	Theme_Blvd_Sidebar_Handler::get_instance();

}

/*------------------------------------------------------------*/
/* (1) Theme Options API Helpers
/*------------------------------------------------------------*/

/**
 * Get raw options.
 *
 * This helper function is more for backwards compatibility.
 * Realistically, it doesn't have much use unless an old
 * plugin is still using it.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @return array All raw API options before they've been formatted into theme options.
 */
function themeblvd_get_core_options() {

	$api = Theme_Blvd_Options_API::get_instance();

	return $api->get_raw_options();

}

/**
 * Get formatted options. Note that options will not be
 * formatted until after WP's after_setup_theme hook.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @return array All formatted theme options.
 */
function themeblvd_get_formatted_options() {

	$api = Theme_Blvd_Options_API::get_instance();

	return $api->get_formatted_options();

}

/**
 * Get theme setting value.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param  string       $primary   The primary ID of the option.
 * @param  string       $secondary Optional secondary ID to traverse deeper into arrays.
 * @param  string|array $default   Optional default value to be applied if value is empty.
 * @return string|array $setting   Saved setting from theme options.
 */
function themeblvd_get_option( $primary, $secondary = null, $default = null ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$setting = $api->get_setting( $primary, $secondary, $default );

	/**
	 * Filters an individual theme setting returned from
	 * saved theme options.
	 *
	 * @param string|array $setting   Setting value.
	 * @param string       $primary   Primary ID of option.
	 * @param string       $secondary Optional secondary ID to traverse deeper into arrays.
	 * @param string|array $default   Default value to be used if empty.
	 */
	return apply_filters( 'themeblvd_get_option', $setting, $primary, $secondary, $default );

}

/**
 * Check if an option is registered.
 *
 * This can be helpful when theme options have been changed
 * in a theme, but maybe the settings haven't been re-saved
 * by the user.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $option_id ID of the option to check for.
 * @return bool              Whether option exists.
 */
function themeblvd_has_option( $option_id ) {

	$options = themeblvd_get_formatted_options();

	if ( isset( $options[ $option_id ] ) ) {
		return true;
	}

	return false;

}

/**
 * Add theme options tab.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $tab_id   ID of tab to add.
 * @param string $tab_name Name of the tab to add.
 * @param bool   $top      Whether the tab should be added to the start or not.
 */
function themeblvd_add_option_tab( $tab_id, $tab_name, $top = false ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$api->add_tab( $tab_id, $tab_name, $top );

}

/**
 * Remove theme options tab.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $tab_id ID of tab to add.
 */
function themeblvd_remove_option_tab( $tab_id ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$api->remove_tab( $tab_id );

}

/**
 * Add theme options section.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $tab_id       ID of tab section will be located in
 * @param string $section_id   ID of new section
 * @param string $section_name Name of new section
 * @param string $section_desc Description of new section
 * @param array  $options      Options array formatted for Options Framework
 * @param bool   $top          Whether the option should be added to the top or not
 */
function themeblvd_add_option_section( $tab_id, $section_id, $section_name, $section_desc = null, $options = null, $top = false ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$api->add_section(
		$tab_id,
		$section_id,
		$section_name,
		$section_desc,
		$options,
		$top
	);

}

/**
 * Remove theme options section.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $tab_id     ID of tab that section to remove belongs to
 * @param string $section_id ID of section to remove
 */
function themeblvd_remove_option_section( $tab_id, $section_id ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$api->remove_section( $tab_id, $section_id );

}

/**
 * Add theme option.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $tab_id     ID of tab to add option to.
 * @param string $section_id ID of section to add to.
 * @param array  $option {
 *     Attributes for option.
 *
 *     @type string $id   Unique ID for option.
 *     @type string $name Title for option.
 *     @type string $desc Description for option.
 *     @type string $std  Default value.
 *     @type string $type Type of option.
 *     ... More attributes, depending on type of option.
 * }
 * @param string $option_id  ID of of your option, note that this id must also be present in $option array.
 */
function themeblvd_add_option( $tab_id, $section_id, $option_id, $option ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$api->add_option( $tab_id, $section_id, $option_id, $option );

}

/**
 * Remove theme option.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $tab_id     ID of tab to add option to.
 * @param string $section_id ID of section to add to.
 * @param string $option_id  ID of of your option.
 */
function themeblvd_remove_option( $tab_id, $section_id, $option_id ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$api->remove_option( $tab_id, $section_id, $option_id );

}

/**
 * Remove theme option.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $tab_id     ID of tab to add option to
 * @param string $section_id ID of section to add to
 * @param string $option_id  ID of of your option
 * @param string $att        Attribute of option to change
 * @param string $value      New value for attribute
 */
function themeblvd_edit_option( $tab_id, $section_id, $option_id, $att, $value ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$api->edit_option( $tab_id, $section_id, $option_id, $att, $value );

}

/**
 * Get the setting name all theme options are saved to.
 *
 * Usage: $settings = get_option( themeblvd_get_option_name() );
 *
 * For each theme, we use a unique identifier to store
 * the theme's options in the database based on the current
 * name of the theme.
 *
 * This is can be filtered with "themeblvd_option_id". See
 * the get_option_id() method of the Theme_Blvd_Options_API class.
 *
 * @since Theme_Blvd 2.1.0
 */
function themeblvd_get_option_name() {

	$api = Theme_Blvd_Options_API::get_instance();

	return $api->get_option_id();

}

/**
 * Get default values for set of options.
 *
 * Note: if you're using this function on the frontend, there
 * will be no filters by default and so no results will get
 * returned. You must call themeblvd_add_saniziation() before
 * calling this function on the frontend.
 *
 * @since Theme_Blvd 2.2.0
 *
 * @param  array $options  Options formatted for internal options framework.
 * @return array $defaults Default values from options.
 */
function themeblvd_get_option_defaults( $options ) {

	if ( ! $options || ! is_array( $options ) ) {
		return array();
	}

	$defaults = array();

	foreach ( $options as $option ) {

		if ( ! isset( $option['id'] ) || ! isset( $option['std'] ) || ! isset( $option['type'] ) ) {
			continue;
		}

		if ( has_filter( 'themeblvd_sanitize_' . $option['type'] ) ) {

			/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
			$defaults[ $option['id'] ] = apply_filters( 'themeblvd_sanitize_' . $option['type'], $option['std'], $option );

		}
	}

	return $defaults;

}

/**
 * Add option presets.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args {
 *     @type string $id      ID of presets section.
 *     @type string $tab     ID of tab preset section will be added to the top of.
 *     @type string $section ID given to section added for presets.
 *     @type array  $sets    Multiple arrays of option values, organized by preset ID.
 * }
 */
function themeblvd_add_option_presets( $args ) {

	$api = Theme_Blvd_Options_API::get_instance();

	$api->add_presets( $args );

}

/*------------------------------------------------------------*/
/* (2) Builder API Helpers
/*------------------------------------------------------------*/

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

/*------------------------------------------------------------*/
/* (3) Sliders API Helpers
/*------------------------------------------------------------*/

/**
 * Add slider type.
 *
 * @since Theme_Blvd 2.1.0
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
 * @since Theme_Blvd 2.3.0
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

/*------------------------------------------------------------*/
/* (4) Widget Areas API Helpers
/*------------------------------------------------------------*/

/**
 * Add sidebar location.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $id   ID of location.
 * @param string $name Name of location.
 * @param string $type Type of location, `fixed` or `collapsible`.
 * @param string $desc Description or widget area.
 */
function themeblvd_add_sidebar_location( $id, $name, $type, $desc = '' ) {

	$handler = Theme_Blvd_Sidebar_Handler::get_instance();

	$handler->add_location( $id, $name, $type, $desc );

}

/**
 * Remove sidebar location.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $id ID of location.
 */
function themeblvd_remove_sidebar_location( $id ) {

	$handler = Theme_Blvd_Sidebar_Handler::get_instance();

	$handler->remove_location( $id );

}

/**
 * Get finalized sidebar locations.
 *
 * @since Theme_Blvd 2.0.0
 */
function themeblvd_get_sidebar_locations() {

	$handler = Theme_Blvd_Sidebar_Handler::get_instance();

	return $handler->get_locations();

}

/**
 * Register default sidebars (i.e. the "locations").
 *
 * @since Theme_Blvd 2.0.0
 */
function themeblvd_register_sidebars() {

	themeblvd_deprecated_function(
		__FUNCTION__,
		'2.3.0',
		null,
		__( 'Default sidebars are now registered within the register() method of the Theme_Blvd_Sidebar_Handler class.', 'jumpstart' )
	);

}

/**
 * Get the user-friendly name for a sidebar location.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param  string $location ID of sidebar location.
 * @return string $name     Name of sidebar location.
 */
function themeblvd_get_sidebar_location_name( $location ) {

	$handler = Theme_Blvd_Sidebar_Handler::get_instance();

	$sidebar = $handler->get_locations( $location );

	if ( isset( $sidebar['location']['name'] ) ) {
		return esc_html( $sidebar['location']['name'] );
	}

	return esc_html__( 'Floating Widget Area', 'jumpstart' );

}

/**
 * Display sidebar of widgets.
 *
 * Whether we're in a traditional fixed sidebar or a
 * collapsible widget area like ad space, for example,
 * this function will output the widgets for that
 * widget area using WordPress's dynamic_sidebar function.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param string $location the location for the sidebar.
 */
function themeblvd_display_sidebar( $location ) {

	$handler = Theme_Blvd_Sidebar_Handler::get_instance();

	$handler->display( $location );

}

/*------------------------------------------------------------*/
/* (5) Stylesheet Handler
/*------------------------------------------------------------*/

/**
 * Add custom stylesheet
 *
 * @since Theme_Blvd 2.1.0
 * @deprecated Theme_Blvd 2.2.0
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
 * @since Theme_Blvd 2.1.0
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
 * @since Theme_Blvd 2.1.0
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
 * @since Theme_Blvd 2.1.0
 * @deprecated Theme_Blvd 2.3.0
 *
 * @param int $level Level to apply stylesheet - 1, 2, 3, 4
 */
function themeblvd_user_stylesheets( $level ) {
	themeblvd_deprecated_function( __FUNCTION__, '2.2.0', 'themeblvd_print_styles' );
	themeblvd_print_styles( $level );
}
