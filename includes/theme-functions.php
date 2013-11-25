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
 * @since 1.0.0
 */
function jumpstart_css() {

	// For plugins not inserting their scripts/stylesheets
	// correctly in the admin.
	if ( is_admin() ) {
		return;
	}

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
add_action( 'wp_enqueue_scripts', 'jumpstart_css', 20 );

/**
 * Jump Start Scripts
 *
 * @since 1.0.0
 */
function jumpstart_scripts() {

	global $themeblvd_framework_scripts;

	// Theme-specific script
	wp_enqueue_script( 'themeblvd_theme', get_template_directory_uri() . '/assets/js/theme.js', $themeblvd_framework_scripts, null, true );

}
add_action( 'wp_enqueue_scripts', 'jumpstart_scripts' );