<?php
/**
 * Theme option API helper functions.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.7.0
 */

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
	 * @since Theme_Blvd 2.2.0
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
