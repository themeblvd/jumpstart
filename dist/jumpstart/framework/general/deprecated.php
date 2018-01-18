<?php
/**
 * Deprecated Functions
 *
 * This is just an archive of deprecated
 * functions that are here for backwards
 * compatibility.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.7.1
 */

if ( ! function_exists( 'themeblvd_responsive_menu_toggle' ) ) {

	/**
	 * Displays the hamburger menu button for
	 * the mobile navigation.
	 *
	 * @deprecated 2.7.0
	 *
	 * @since 2.0.0
	 */
	function themeblvd_responsive_menu_toggle() {

		themeblvd_deprecated_function(
			__FUNCTION__,
			'2.7.0',
			'themeblvd_get_mobile_panel_trigger'
		);

		echo themeblvd_get_mobile_panel_trigger();

	}
}
