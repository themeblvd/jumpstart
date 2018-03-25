<?php
/**
 * Admin Setup Functions
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */

/**
 * Initialize everything needed for admin panel
 * to run.
 *
 * This function is hooked to:
 * 1. `after_setup_theme` - 1001
 *
 * @since @@name-framework 2.0.0
 */
function themeblvd_admin_init() {

	/**
	 * Filters whether framework message displays.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param bool Whether framework welcome message displays.
	 */
	if ( apply_filters( 'themeblvd_do_welcome_message', true ) ) {

		/*
		 * Add framework welcome message.
		 */
		$welcome = Theme_Blvd_Welcome::get_instance();

	}

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
 * In version 2.7.0 this function was majorly
 * altered to use "My Plugin Manager" instead
 * of the TGMPA script.
 *
 * This function is hooked to:
 * 1. `after_setup_theme` - 10
 *
 * @since @@name-framework 2.2.0
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
			'version' => '2.2+',
		),
		'sidebars' => array(
			'name'    => 'Theme Blvd Widget Areas',
			'slug'    => 'theme-blvd-widget-areas',
			'version' => '1.3+',
		),
		'widgets' => array(
			'name'    => 'Theme Blvd Widget Pack',
			'slug'    => 'theme-blvd-widget-pack',
			'version' => '1.0.6+',
		),
		'shortcodes' => array(
			'name'    => 'Theme Blvd Shortcodes',
			'slug'    => 'theme-blvd-shortcodes',
			'version' => '1.6.5+',
		),
		'importer' => array(
			'name'    => 'Theme Blvd Importer',
			'slug'    => 'theme-blvd-importer',
			'version' => '1.0.4+',
		),
		'portfolios' => array(
			'name'    => 'Portfolios',
			'slug'    => 'portfolios',
			'version' => '1.1.4+',
		),
		'tweeple' => array(
			'name'    => 'Tweeple',
			'slug'    => 'tweeple',
			'version' => '0.9.5+',
		),
		'analytics' => array(
			'name'    => 'Simple Analytics',
			'slug'    => 'simple-analytics',
			'version' => '1.1+',
		),
	) );

	/*
	 * Setup arguments for plugin manager interface.
	 */
	$args = array(
		'page_title' => __( 'Suggested Plugins', '@@text-domain' ),
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
 * This function is hooked to:
 * 1. `admin_init` - 10
 *
 * @since @@name-framework 2.3.0
 */
function themeblvd_update_version() {

	update_option( 'themeblvd_framework_version', TB_FRAMEWORK_VERSION );

}

/**
 * Enqueue admin scripts and stylesheets.
 *
 * Note: This function was previously named
 * themeblvd_non_modular_assets() prior to framework
 * 2.7.0.
 *
 * This function is hooked to:
 * 1. `admin_enqueue_scripts` - 10
 *
 * @since @@name-framework 2.0.0
 */
function themeblvd_admin_enqueue() {

	global $pagenow;

	$suffix = themeblvd_script_debug() ? '' : '.min';

	$icon_file = themeblvd_get_icon_js_file();

	if ( is_array( $icon_file['url'] ) ) {

		$i = 1;

		foreach ( $icon_file['url'] as $url ) {

			$handle =  $i > 1 ? $icon_file['handle'] . '-' . $i : $icon_file['handle']; // foo, foo-2, foo-3, etc.

			wp_register_script(
				$handle,
				esc_url( $url ),
				array(),
				esc_attr( $icon_file['version'] )
			);

			$i++;

		}
	} else {

		wp_register_script(
			$icon_file['handle'],
			esc_url( $icon_file['url'] ),
			array(),
			esc_attr( $icon_file['version'] )
		);

	}

	wp_localize_script(
		$icon_file['handle'],
		'themeblvdIconSearchData',
		themeblvd_get_icon_search_data()
	);

	wp_register_script(
		'themeblvd-admin-utils',
		esc_url( TB_FRAMEWORK_URI . "/admin/assets/js/utils{$suffix}.js" ),
		array(
			'jquery',
			'jquery-ui-core',
			'jquery-ui-sortable',
			'jquery-ui-slider',
			'wp-color-picker',
			'media-editor',
			'editor',
		),
		TB_FRAMEWORK_VERSION
	);

	wp_localize_script(
		'themeblvd-admin-utils',
		'themeblvdL10n',
		themeblvd_get_admin_locals( 'js' )
	);

	wp_register_script(
		'themeblvd-admin-options',
		esc_url( TB_FRAMEWORK_URI . "/admin/assets/js/options{$suffix}.js" ),
		array( 'themeblvd-admin-utils' ),
		TB_FRAMEWORK_VERSION
	);

	/*
	 * Enqueue assets required for editing posts and
	 * pages.
	 */
	if ( 'post-new.php' === $pagenow || 'post.php' === $pagenow ) {

		themeblvd_admin_assets();

		wp_enqueue_script(
			'themeblvd-meta-box',
			esc_url( TB_FRAMEWORK_URI . "/admin/assets/js/meta-box{$suffix}.js" ),
			array( 'jquery' ),
			TB_FRAMEWORK_VERSION
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
		'themeblvd-admin-global',
		esc_url( TB_FRAMEWORK_URI . "/admin/assets/css/global{$suffix}.css" ),
		null,
		TB_FRAMEWORK_VERSION
	);

}

/**
 * Include all standard admin stylesheets and scripts,
 * meant to be called from other functions hooked to
 * admin_enqueue_scripts.
 *
 * @since @@name-framework 2.7.0
 *
 * @param string $type Type of assets to load, `styles` or 'scripts'.
 */
function themeblvd_admin_assets( $type = '' ) {

	$suffix = themeblvd_script_debug() ? '' : '.min';

	// Enqueue stylesheets.
	if ( ! $type || 'styles' === $type ) {

		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_style(
			'themeblvd-admin-utils',
			esc_url( TB_FRAMEWORK_URI . "/admin/assets/css/utils{$suffix}.css" ),
			null,
			TB_FRAMEWORK_VERSION
		);

		wp_enqueue_style(
			'themeblvd-admin-options',
			esc_url( TB_FRAMEWORK_URI . "/admin/assets/css/options{$suffix}.css" ),
			null,
			TB_FRAMEWORK_VERSION
		);

	}

	// Enqueue scripts.
	if ( ! $type || 'scripts' === $type ) {

		$required = array(
			'jquery',
			'jquery-ui-core',
			'jquery-ui-sortable',
			'jquery-ui-slider',
			'wp-color-picker',
			'media-editor',
			'editor',
		);

		foreach ( $required as $handle ) {

			if ( 'media-editor' === $handle ) {

				wp_enqueue_media();

			} elseif ( 'editor' === $handle ) {

				if ( function_exists( 'wp_enqueue_editor' ) ) { // WordPress 4.8+

					wp_enqueue_editor();

				}

			} else {

				wp_enqueue_script( $handle );

			}
		}

		wp_enqueue_script( 'themeblvd-admin-utils' );

		wp_enqueue_script( 'themeblvd-admin-options' );

	}

}
