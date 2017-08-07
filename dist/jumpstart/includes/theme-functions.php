<?php
/**
 * Define theme constants
 */
define( 'TB_THEME_ID', 'jumpstart' );
define( 'TB_THEME_NAME', 'JumpStart' );

/**
 * Automatic updates
 */
include_once( get_template_directory() . '/includes/theme-updates.php' );

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
				'name'		=> __('Developer', 'jumpstart'),
				'desc'		=> __('If you\'re a developer looking to create a custom-designed child theme, this is the base for you.', 'jumpstart')
			),
			'superuser' => array(
				'name'		=> __('Super User', 'jumpstart'),
				'desc'		=> __('For the super user, this base builds on the default theme to give you more visual, user options.', 'jumpstart')
			),
			'agent' => array(
				'name'		=> __('Agent', 'jumpstart'),
				'desc'		=> __('A modern and open, agency-style design with a bit less user options.', 'jumpstart')
			),
			'entrepreneur' => array(
				'name'		=> __('Entrepreneur', 'jumpstart'),
				'desc'		=> __('A more standard, corporate design with a lot of user options.', 'jumpstart')
			),
			'executive' => array(
				'name'		=> __('Executive', 'jumpstart'),
				'desc'		=> __('A more classic, corporate design with a lot of user options.', 'jumpstart')
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

	// Theme version
	$theme = wp_get_theme( get_template() );
	$ver = $theme->get( 'Version' );
	$stylesheet_ver = $ver;

	if ( get_template() !== get_stylesheet() ) {

		$theme = wp_get_theme( get_stylesheet() );
		$stylesheet_ver = $theme->get( 'Version' );

	}

	// Get stylesheet API
	$handler = Theme_Blvd_Stylesheet_Handler::get_instance();

	// Main theme styles
	if ( is_rtl() ) {
		wp_enqueue_style( 'jumpstart', esc_url( get_template_directory_uri() . '/assets/css/theme-rtl.min.css' ), $handler->get_framework_deps(), $ver );
	} else {
		wp_enqueue_style( 'jumpstart', esc_url( get_template_directory_uri() . '/assets/css/theme.min.css' ), $handler->get_framework_deps(), $ver );
	}

	// Dark styles
	if ( themeblvd_supports('display', 'dark') ) {
		wp_enqueue_style( 'jumpstart-dark', esc_url( get_template_directory_uri() . '/assets/css/dark.min.css' ), $handler->get_framework_deps(), $ver );
	}

	// Theme base styles
	$base = themeblvd_get_base();

	if ( $base && $base != 'dev' ) {
		wp_enqueue_style( 'jumpstart-base', esc_url( themeblvd_get_base_uri($base) . '/base.css' ), $handler->get_framework_deps(), $ver );
	}

	// IE Stylesheet
	wp_enqueue_style( 'themeblvd-ie', esc_url( get_template_directory_uri() . '/assets/css/ie.css' ), array(), $ver );
	$GLOBALS['wp_styles']->add_data( 'themeblvd-ie', 'conditional', 'IE' ); // Add IE conditional

	// Primary style.css (mainly for child theme devs)
	wp_enqueue_style( 'themeblvd-theme', esc_url( get_stylesheet_uri() ), $handler->get_framework_deps(), $stylesheet_ver );

	// Level 3 client API-added styles
	$handler->print_styles(3);

}
add_action('wp_enqueue_scripts', 'jumpstart_css', 20);

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
		themeblvd_alert( array('style' => 'warning', 'class' => 'full'), __('Warning: Your saved theme options do not currently match the theme base you\'ve selected. Please configure and save your theme options page.', 'jumpstart') );
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

	$layouts['jumpstart-home'] = array(
		'name'		=> __('Jump Start: Home', 'jumpstart'),
		'id'		=> 'jumpstart-home',
		'dir'		=> get_template_directory() . '/includes/layouts/home/',
		'uri'		=> get_template_directory_uri() . '/includes/layouts/home/',
		'assets'	=> get_template_directory_uri() . '/includes/layouts/home/img/'
	);

	return $layouts;
}
add_filter('themeblvd_sample_layouts', 'jumpstart_sample_layouts');
