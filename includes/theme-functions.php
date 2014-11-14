<?php
// Define theme constants
define( 'TB_THEME_ID', 'jumpstart' );
define( 'TB_THEME_NAME', 'JumpStart' );

// Modify framework's theme options
include_once( get_template_directory() . '/includes/options.php' );

// Automatic updates
include_once( get_template_directory() . '/includes/updates.php' );

// Theme Base
if ( $base = get_option( get_template().'_base' ) ) {
	include_once( themeblvd_get_base_path($base) . '/base.php' );
}

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

	// Main theme styles
	if ( is_rtl() ) {
		wp_enqueue_style( 'jumpstart', get_template_directory_uri().'/assets/css/theme-rtl.css', $api->get_framework_deps() );
	} else {
		wp_enqueue_style( 'jumpstart', get_template_directory_uri().'/assets/css/theme.css', $api->get_framework_deps() );
	}

	// Dark styles
	if ( themeblvd_supports('display', 'dark') ) {
		wp_enqueue_style( 'jumpstart-dark', get_template_directory_uri().'/assets/css/dark.css', $api->get_framework_deps() );
	}

	// Theme base styles
	$base = get_option(get_template().'_base');

	if ( $base && $base != 'dev' ) {
		wp_enqueue_style( 'jumpstart-base', themeblvd_get_base_uri($base).'/base.css', $api->get_framework_deps() );
	}

	// IE Stylesheet
	wp_enqueue_style( 'themeblvd-ie', get_template_directory_uri() . '/assets/css/ie.css' );
	$GLOBALS['wp_styles']->add_data( 'themeblvd-ie', 'conditional', 'lt IE 9' ); // Add IE conditional

	// Primary style.css (mainly for child theme devs)
	wp_enqueue_style( 'themeblvd-theme', get_stylesheet_uri(), $api->get_framework_deps() );

	// Level 3 client API-added styles
	$api->print_styles(3);

}
add_action( 'wp_enqueue_scripts', 'jumpstart_css', 20 );

/**
 * Jump Start scripts
 *
 * @since 1.0.0
 */
function jumpstart_scripts() {

	global $themeblvd_framework_scripts;

	// Theme-specific script
	wp_enqueue_script( 'themeblvd_theme', get_template_directory_uri() . '/assets/js/theme.js', $themeblvd_framework_scripts, null, true );

}
add_action( 'wp_enqueue_scripts', 'jumpstart_scripts' );

/**
 * Jump Start theme bases
 *
 * @since 2.0.0
 */
function jumpstart_bases() {

	if ( is_admin() ) {

		$bases = apply_filters('themeblvd_bases', array(
			'dev' => array(
				'name'		=> 'Developer',
				'desc'		=> 'If you\'re a developer looking to create a custom-designed child theme, this is the base for you.'
			),
			'superuser' => array(
				'name'		=> 'Super User',
				'desc'		=> 'For the super user, this base builds on the default theme to give you more visual, user options.'
			)
			/*
			'artist' => array(
				'name'		=> 'Artist',
				'desc'		=> 'This base will give you the perfect starting point for a business-oriented website for you or your clients.'
			),
			'entrepreneur' => array(
				'name'		=> 'Entrepreneur',
				'desc'		=> 'This base will give you the perfect starting point for a business-oriented website for you or your clients.'
			)
			*/
		));

		$admin = new Theme_Blvd_Bases( $bases, 'dev' ); // class included with is_admin()

	}

}
add_action('after_setup_theme', 'jumpstart_bases');