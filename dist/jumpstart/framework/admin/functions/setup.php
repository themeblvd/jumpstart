<?php
/**
 * Admin Setup Functions
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */

/**
 * Initialize anything needed for admin panel
 * to run.
 *
 * @since Theme_Blvd 2.0.0
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
 * Setup suggested plugin manager.
 *
 * In version 2.7.0 this function was majorly altered to
 * use "My Plugin Manager" instead of the TGMPA script.
 *
 * @since Theme_Blvd 2.2.0
 */
function themeblvd_plugins() {

	if ( ! themeblvd_supports( 'admin', 'plugins' ) ) {
		return;
	}

	/**
	 * Include plugin manager class.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/plugin-manager/class-theme-blvd-plugin-manager.php' );

	/*
	 * Setup suggested plugins.
	 */
	$plugins = apply_filters( 'themeblvd_plugins', array(
		'builder' => array(
			'name'    => 'Theme Blvd Layout Builder',
			'slug'    => 'theme-blvd-layout-builder',
			'version' => '2.1.5+',
		),
		'sidebars' => array(
			'name'    => 'Theme Blvd Widget Areas',
			'slug'    => 'theme-blvd-widget-areas',
			'version' => '1.2+',
		),
		'widgets' => array(
			'name'    => 'Theme Blvd Widget Pack',
			'slug'    => 'theme-blvd-widget-pack',
			'version' => '1.0.5+',
		),
		'shortcodes' => array(
			'name'    => 'Theme Blvd Shortcodes',
			'slug'    => 'theme-blvd-shortcodes',
			'version' => '1.6+',
		),
		'importer' => array(
			'name'    => 'Theme Blvd Importer',
			'slug'    => 'theme-blvd-importer',
			'version' => '1.0.3+',
		),
		'portfolios' => array(
			'name'    => 'Portfolios',
			'slug'    => 'portfolios',
			'version' => '1.1.4+',
		),
		'tweeple' => array(
			'name'    => 'Tweeple',
			'slug'    => 'tweeple',
			'version' => '0.9.4+',
		),
		'analytics' => array(
			'name'    => 'Simple Analytics',
			'slug'    => 'simple-analytics',
			'version' => '1.1+',
		),
	));

	/*
	 * Setup arguments for plugin manager interface.
	 */
	$args = array(
		'page_title' => __( 'Suggested Plugins', 'jumpstart' ),
		'menu_slug'  => 'themeblvd-suggested-plugins',
	);

	/*
	 * Create plugin manager object, passing in the suggested
	 * plugins and optional arguments.
	 */
	$manager = new Theme_Blvd_Plugin_Manager( $plugins, $args );

}

/**
 * Save current version of framework to database.
 *
 * The framework hooks this to "admin_init" action
 * from /framework/themeblvd.php
 *
 * @since Theme_Blvd 2.3.0
 */
function themeblvd_update_version() {

	update_option( 'themeblvd_framework_version', TB_FRAMEWORK_VERSION );

}

/**
 * Setup non-modular admin assets.
 *
 * @since Theme_Blvd 2.0.0
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
