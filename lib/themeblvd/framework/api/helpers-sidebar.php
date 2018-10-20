<?php
/**
 * Widget area API helper functions.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.7.0
 */

/**
 * Add sidebar location.
 *
 * @since @@name-framework 2.1.0
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
 * @since @@name-framework 2.1.0
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
 * @since @@name-framework 2.0.0
 */
function themeblvd_get_sidebar_locations() {

	$handler = Theme_Blvd_Sidebar_Handler::get_instance();

	return $handler->get_locations();

}

/**
 * Get the user-friendly name for a sidebar location.
 *
 * @since @@name-framework 2.0.0
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
 * @since @@name-framework 2.0.0
 *
 * @param string $location the location for the sidebar.
 */
function themeblvd_display_sidebar( $location ) {

	$handler = Theme_Blvd_Sidebar_Handler::get_instance();

	$handler->display( $location );

}
