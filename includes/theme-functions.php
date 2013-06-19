<?php
// Define theme constants
define( 'TB_THEME_ID', 'jumpstart' );
define( 'TB_THEME_NAME', 'JumpStart' );

// Modify framework's theme options
include_once( get_template_directory() . '/includes/options.php' );

// Automatic updates
include_once( get_template_directory() . '/includes/updates.php' );

/**
 * Jump Start CSS Files
 *
 * To add styles or remove unwanted styles that you
 * know you won't need to maybe save some frontend load
 * time, this function can easily be re-done from a
 * child theme.
 */

if( ! function_exists( 'jumpstart_css' ) ) {
	function jumpstart_css() {

		// Get stylesheet API
		$api = Theme_Blvd_Stylesheets_API::get_instance();

		// Primary style.css after all framework stylesheets
		wp_enqueue_style( 'themeblvd_theme', get_stylesheet_uri(), $api->get_framework_deps() );

		// IE Stylesheet
		wp_enqueue_style( 'themeblvd_ie', get_template_directory_uri() . '/assets/css/ie.css', array( 'themeblvd_theme' ) );
		$GLOBALS['wp_styles']->add_data( 'themeblvd_ie', 'conditional', 'lt IE 9' ); // Add IE conditional

		// Level 3 client API-added styles
		$api->print_styles(3);

	}
}
add_action( 'wp_enqueue_scripts', 'jumpstart_css' );

/**
 * Jump Start Scripts
 */

if( ! function_exists( 'jumpstart_scripts' ) ) {
	function jumpstart_scripts() {

		global $themeblvd_framework_scripts;

		// Theme-specific script
		wp_enqueue_script( 'themeblvd_theme', get_template_directory_uri() . '/assets/js/theme.js', $themeblvd_framework_scripts, null, true );

	}
}
add_action( 'wp_enqueue_scripts', 'jumpstart_scripts' );