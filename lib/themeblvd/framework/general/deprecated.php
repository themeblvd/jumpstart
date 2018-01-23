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
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.7.1
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

/**
 * Display custom slider from Theme Blvd
 * Sliders plugin.
 *
 * @since @@name-framework 2.0.0
 * @deprecated @@name-framework 2.5.0
 *
 * @param string $slider Slug of custom-built slider to use.
 */
function themeblvd_slider( $slider ) {

	themeblvd_deprecated_function(
		__FUNCTION__,
		'2.5.0',
		null,
		'The Theme Blvd Sliders plugin was deprecated as of Framework 2.5 and support for it was completely removed in Framework 2.7. Use one of the layout builder\'s slider elements or the [galler_slider] shortcode instead.'
	);

}
