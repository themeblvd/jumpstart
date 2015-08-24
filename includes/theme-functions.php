<?php
/**
 * Define theme constants
 */
define( 'TB_THEME_ID', 'jumpstart' );
define( 'TB_THEME_NAME', 'JumpStart' );

/**
 * Automatic updates
 */
include_once( get_template_directory() . '/includes/updates.php' );

/**
 * Global configuration, enable theme bases
 *
 * @since 2.0.0
 */
function jumpstart_global_config( $config ) {
	$config['admin']['base'] = true;
	return $config;
}
add_filter('themeblvd_global_config', 'jumpstart_global_config');

/**
 * Setup theme bases admin
 *
 * @since 2.0.0
 */
function jumpstart_bases() {

	if ( is_admin() && themeblvd_supports('admin', 'base') ) {

		$bases = apply_filters('themeblvd_bases', array(
			'dev' => array(
				'name'		=> __('Developer', 'themeblvd'),
				'desc'		=> __('If you\'re a developer looking to create a custom-designed child theme, this is the base for you.', 'themeblvd')
			),
			'superuser' => array(
				'name'		=> __('Super User', 'themeblvd'),
				'desc'		=> __('For the super user, this base builds on the default theme to give you more visual, user options.', 'themeblvd')
			),
			'entrepreneur' => array(
				'name'		=> __('Entrepreneur', 'themeblvd'),
				'desc'		=> __('For the modern entrepreneur, this base embodies the current design trends of the web industry.', 'themeblvd')
			),
			'executive' => array(
				'name'		=> __('Executive', 'themeblvd'),
				'desc'		=> __('For the confident, experienced executive, this base gives you a design you\'re familiar with, for success.', 'themeblvd')
			)
		));

		$admin = new Theme_Blvd_Bases( $bases, themeblvd_get_default_base() ); // class included with is_admin()

	}

}
add_action('after_setup_theme', 'jumpstart_bases');

/**
 * Include theme base
 */
if ( $base = themeblvd_get_base() ) {
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

	// Theme version_compare
	$theme = wp_get_theme( get_template() );
	$ver = $theme->get('Version');

	// Get stylesheet API
	$handler = Theme_Blvd_Stylesheet_Handler::get_instance();

	// Main theme styles
	if ( is_rtl() ) {
		wp_enqueue_style( 'jumpstart', get_template_directory_uri().'/assets/css/theme-rtl.min.css', $handler->get_framework_deps(), $ver );
	} else {
		wp_enqueue_style( 'jumpstart', get_template_directory_uri().'/assets/css/theme.min.css', $handler->get_framework_deps(), $ver );
	}

	// Dark styles
	if ( themeblvd_supports('display', 'dark') ) {
		wp_enqueue_style( 'jumpstart-dark', get_template_directory_uri().'/assets/css/dark.min.css', $handler->get_framework_deps(), $ver );
	}

	// Theme base styles
	$base = themeblvd_get_base();

	if ( $base && $base != 'dev' ) {
		wp_enqueue_style( 'jumpstart-base', themeblvd_get_base_uri($base).'/base.css', $handler->get_framework_deps(), $ver );
	}

	// IE Stylesheet
	wp_enqueue_style( 'themeblvd-ie', get_template_directory_uri() . '/assets/css/ie.css', array(), $ver );
	$GLOBALS['wp_styles']->add_data( 'themeblvd-ie', 'conditional', 'IE' ); // Add IE conditional

	// Primary style.css (mainly for child theme devs)
	wp_enqueue_style( 'themeblvd-theme', get_stylesheet_uri(), $handler->get_framework_deps(), $ver );

	// Level 3 client API-added styles
	$handler->print_styles(3);

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
 * Jump Start base check. If WP user is logged in,
 * output message on frontend to tell them their saved
 * theme options don't match the theme base they've
 * selected.
 *
 * @since 2.0.0
 */
function jumpstart_base_check() {

	if ( is_user_logged_in() && themeblvd_supports('admin', 'base') && themeblvd_get_option('theme_base') != themeblvd_get_base() ) {
		themeblvd_alert( array('style' => 'warning', 'class' => 'full'), __( 'Warning: Your saved theme options do not currently match the theme base you\'ve selected. Please configure and save your theme options page.', 'themeblvd' ) );
	}

}
add_action('themeblvd_before', 'jumpstart_base_check');

/**
 * Add Jump Start Homepage to sample layouts of
 * Layout Builder plugin.
 *
 * @since 2.0.0
 */
function jumpstart_sample_layouts( $layouts ) {

	$layouts['jump-start-homepage'] = array(
		'name'		=> __('Jump Start Homepage', 'themeblvd'),
		'id'		=> 'jump-start-homepage',
		'dir'		=> get_template_directory() . '/includes/layout/',
		'uri'		=> get_template_directory_uri() . '/includes/layout/',
		'assets'	=> get_template_directory_uri() . '/includes/layout/img/'
	);

	return $layouts;
}
add_filter('themeblvd_sample_layouts', 'jumpstart_sample_layouts');
