<?php
/**
 * Admin Setup Functions
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     @@name-package
 */

/**
 * Initialize anything needed for admin panel
 * to run.
 *
 * @since @@name-framework 2.0.0
 */
function themeblvd_admin_init() {

	/*
	 * Add framework welcome message.
	 */
	$welcome = Theme_Blvd_Welcome::get_instance();

	/*
	 * Allow theme options page to run if framework filters
	 * have don't have it hidden it and user is capable.
	 */
	if ( themeblvd_supports( 'admin', 'options' ) && current_user_can( themeblvd_admin_module_cap( 'options' ) ) ) {

		/*
		 * Access Options API, instance should already be created.
		 */
		$api = Theme_Blvd_Options_API::get_instance();

		/*
		 * Option ID the theme options are registered and
		 * saved to. -- i.e. get_option( $option_name )
		 */
		$option_id = $api->get_option_id();

		/*
		 * All options constructed from framework and
		 * potentially added to by client API.
		 */
		$options = $api->get_formatted_options();

		/*
		 * Arguments for theme options admin page.
		 * Filterable with "themeblvd_theme_options_args"
		 */
		$args = $api->get_args();

		/*
		 * Setup theme options page.
		 */
		$options_page = new Theme_Blvd_Options_Page( $option_id, $options, $args );

	}

	/*
	 * Add options to WordPress's User Profile Edit pages,
	 * to allow for framework's author box on frontend.
	 */
	if ( themeblvd_supports( 'admin', 'user' ) ) {

		$user_options = Theme_Blvd_User_Options::get_instance();

	}

	/*
	 * Add framework post display options to editing
	 * category and tag pages.
	 */
	if ( themeblvd_supports( 'admin', 'tax' ) ) {

		$tax_options = Theme_Blvd_Tax_Options::get_instance();

	}

	/*
	 * Add menu styling options, mostly for mega menu
	 * functionaity to WordPress menu builder.
	 */
	if ( themeblvd_supports( 'admin', 'menus' ) ) {

		$menu_options = Theme_Blvd_Menu_Options::get_instance();

	}

}

/**
 * Save current version of framework to database.
 *
 * The framework hooks this to "admin_init" action
 * from /framework/themeblvd.php
 *
 * @since @@name-framework 2.3.0
 */
function themeblvd_update_version() {

	update_option( 'themeblvd_framework_version', TB_FRAMEWORK_VERSION );

}

/**
 * Setup non-modular admin assets.
 *
 * @since @@name-framework 2.0.0
 */
function themeblvd_non_modular_assets() {

	global $pagenow;

	/*
	 * Enqueue assets required for editing
	 * posts and pages.
	 */
	if ( 'post-new.php' === $pagenow || 'post.php' === $pagenow ) {

		wp_enqueue_style(
			'themeblvd_admin',
			esc_url( TB_FRAMEWORK_URI . '/admin/assets/css/admin-style.min.css' ),
			null,
			TB_FRAMEWORK_VERSION
		);

		wp_enqueue_style(
			'themeblvd_options',
			esc_url( TB_FRAMEWORK_URI . '/admin/options/css/admin-style.min.css' ),
			null,
			TB_FRAMEWORK_VERSION
		);

		wp_enqueue_script(
			'themeblvd_modal',
			esc_url( TB_FRAMEWORK_URI . '/admin/assets/js/modal.min.js' ),
			array( 'jquery' ),
			TB_FRAMEWORK_VERSION
		);

		wp_enqueue_script(
			'themeblvd_admin',
			esc_url( TB_FRAMEWORK_URI . '/admin/assets/js/shared.min.js' ),
			array( 'jquery' ),
			TB_FRAMEWORK_VERSION
		);

		wp_enqueue_script(
			'tb_meta_box-scripts',
			esc_url( TB_FRAMEWORK_URI . '/admin/assets/js/meta-box.min.js' ),
			array( 'jquery' ),
			TB_FRAMEWORK_VERSION
		);

		wp_localize_script(
			'tb_meta_box-scripts',
			'themeblvd',
			themeblvd_get_admin_locals( 'js' )
		);

	}

	/*
	 * Enqueue stylesheet needed for styling custom icons
	 * in primary admin sidebar, needed for entire WordPress
	 * admin panel.
	 *
	 * We're very careful here to only include CSS absolutely
	 * needed throughout the entire WordPress admin!
	 */
	wp_enqueue_style(
		'tb_admin_global',
		esc_url( TB_FRAMEWORK_URI . '/admin/assets/css/admin-menu.min.css' ),
		null,
		TB_FRAMEWORK_VERSION
	);

}
