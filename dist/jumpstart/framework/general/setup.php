<?php
/**
 * Setup Functions
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */

/**
 * Initialize API.
 *
 * This function is hooked to:
 * 1. `themeblvd_api` - 10
 *
 * @since Theme_Blvd 2.1.0
 */
function themeblvd_api_init() {

	/*
	 * Setup Theme Options API and establish theme settings.
	 * From this point client can use themeblvd_get_option().
	 */
	Theme_Blvd_Options_API::get_instance();

	/*
	 * Setup framework stylesheets and handler for frontend to
	 * modify these stylesheets.
	 */
	Theme_Blvd_Stylesheet_Handler::get_instance();

	/*
	 * Setup widget areas handler. This registers all default
	 * sidebars and provides methods to modify them and
	 * display them.
	 */
	Theme_Blvd_Sidebar_Handler::get_instance();

}

/**
 * Setup global configuration.
 *
 * The global configuration determines which
 * features are supported throughout the theme.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @return array $setup Framework features.
 */
function themeblvd_setup() {

	$setup = array();

	$setup['admin'] = array(
		'options'          => true,  // Display of "Theme Options" page.
		'builder'          => true,  // Display of "Templates" admin page.
		'sidebars'         => true,  // Display of "Widget Areas" admin page.
		'updates'          => true,  // Display of "Theme Updates" admin page.
		'user'             => true,  // Display of added user profile options.
		'tax'              => true,  // Display of added taxonomy options.
		'menus'            => true,  // Display of added menu builder options.
		'base'             => false, // Support of theme bases.
		'plugins'          => true,  // Support of recommended plugin manager interface.
	);

	$setup['meta'] = array(
		'page_options'     => true,  // Display meta box for page options.
		'post_options'     => true,  // Display meta box for post options.
		'pto'              => true,  // Display meta box for post displaying page template.
		'layout'           => true,  // Display meta box for theme layout options.
	);

	$setup['comments'] = array(
		'posts'            => true,  // Comments on posts.
		'pages'            => false, // Comments on pages.
		'attachments'      => false, // Comments on attachments.
	);

	$setup['display'] = array(
		'responsive'       => true,  // Responsive elements.
		'dark'             => false, // Whether to display as dark theme.
		'sticky'           => true,  // Sticky header as user scrolls past header.
		'mobile_side_menu' => true,  // Responsive menu position fixed to the side of the screen on mobile.
		'side_panel'       => true,  // Optional side panel navigation.
		'scroll_effects'   => true,  // Effects as user scrolls down page.
		'hide_top'         => true,  // Whether theme supports hiding the #top.
		'hide_bottom'      => true,  // Whether theme supports hiding the #bottom.
		'footer_sync'      => true,  // Whether theme suppors syncing footer with template.
		'suck_up'          => true,  // Whether theme supports sucking custom layout content up into header.
		'gallery'          => true,  // Integration of thumbnail classes and lightbox to WP [gallery].
		'print'            => true,  // Whether to apply basic styles for printing.
	);

	$setup['assets'] = array(
		'primary_js'        => true, // Primary "themeblvd" script.
		'primary_css'       => true, // Primary "themeblvd" stylesheet
		'flexslider'        => true, // Flexslider script by WooThemes
		'bootstrap'         => true, // "bootstrap" script/stylesheet
		'magnific_popup'    => true, // "magnific_popup" script/stylesheet
		'superfish'         => true, // "superfish" script
		'easypiechart'      => true, // "EasyPieChart" script
		'gmap'              => true, // Google Maps API v3
		'charts'            => true, // Charts.js
		'vimeo'             => true, // Latest Vimeo API
		'youtube'           => true, // Latest YouTube Iframe API
		'isotope'           => true, // Isotope script for sorting
		'tag_cloud'         => true, // Framework tag cloud styling
		'owl_carousel'      => true, // Owl Carousel for gallery sliders
		'in_footer'         => true, // Whether theme scripts are enqueued in footer
	);

	$setup['plugins'] = array(
		'bbpress'           => true, // bbPress by Automattic
		'gravityforms'      => true, // Gravity Forms by Rocket Genius
		'subtitles'         => true, // Subtitles by Philip Moore
		'woocommerce'       => true, // WooCommerce by WooThemes
		'wpml'              => true, // WPML by On The Go Systems
	);

	/**
	 * Filters the global configuration array.
	 *
	 * The global configuration determines which
	 * features are supported throughout the theme.
	 *
	 * @link http://dev.themeblvd.com/tutorial/managing-framework-features/
	 *
	 * @since Theme_Blvd 2.0.0
	 *
	 * @param array $setup Framework features.
	 */
	return apply_filters( 'themeblvd_global_config', $setup );

}

/**
 * Check whether a framework feature is
 * supported.
 *
 * @see themeblvd_setup()
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param  string $group   Top-level group key in configuration array.
 * @param  string $feature Second-level feature key in confiration array.
 * @return bool   $support Whether feature is supported or not.
 */
function themeblvd_supports( $group, $feature ) {

	$setup = themeblvd_setup();

	$support = false;

	if ( ! empty( $setup ) && ! empty( $setup[ $group ][ $feature ] ) ) {

		$support = true;

	}

	return $support;

}

/**
 * Sets sidebar layout for multisite signup
 * page.
 *
 * This function is filteres onto:
 * 1. `themeblvd_sidebar_layout` - 10
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param string $sidebar_layout Sidebar layout identifier, like `full_width`.
 */
function themeblvd_wpmultisite_signup_sidebar_layout( $sidebar_layout ) {

	global $pagenow;

	if ( 'wp-signup.php' === $pagenow ) {

		/**
		 * Filters the sidebar layout used with the
		 * multisite signup page.
		 *
		 * @since Theme_Blvd 2.1.0
		 *
		 * @param string Sidebar layout identifier, like `full_width`.
		 */
		$sidebar_layout = apply_filters( 'themeblvd_wpmultisite_signup_sidebar_layout', 'full_width' );

	}

	return $sidebar_layout;

}

/**
 * Register theme's nav menus.
 *
 * This function is hooked to:
 * 1. `after_setup_theme` - 10
 *
 * @since Theme_Blvd 2.0.0
 */
function themeblvd_register_navs() {

	// Setup nav menus
	$menus = array(
		'primary' => __( 'Primary Navigation', 'jumpstart' ),
		'footer'  => __( 'Footer Navigation', 'jumpstart' ),
	);

	if ( themeblvd_supports( 'display', 'side_panel' ) ) {

		$menus['side'] = __( 'Primary Side Navigation', 'jumpstart' );

		$menus['side_sub'] = __( 'Secondary Side Navigation', 'jumpstart' );

	}

	/**
	 * Filters the navigation menu locations
	 * registered by the theme.
	 *
	 * @since Theme_Blvd 2.0.0
	 *
	 * @param array Navigation menu locations.
	 */
	$menus = apply_filters( 'themeblvd_nav_menus', $menus );

	register_nav_menus( $menus );

}

/**
 * Add all theme framework's instances of
 * add_theme_support().
 *
 * This function is hooked to:
 * 1. `after_setup_theme` - 10
 *
 * @since Theme_Blvd 2.0.0
 */
function themeblvd_add_theme_support() {

	// Add support for auto feed links.
	add_theme_support( 'automatic-feed-links' );

	// Add post thumbnail support.
	add_theme_support( 'post-thumbnails' );

	// Add HTML5 support.
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'caption' ) );

	// Add post format support.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery', 'status', 'chat' ) );

	// Add automated <title> tag support.
	add_theme_support( 'title-tag' );

	// WooCommerce native support by theme.
	add_theme_support( 'woocommerce' );

	// Add editor style.
	add_editor_style( 'style-editor.css' );

}

/**
 * Add items to admin menu bar.
 *
 * This function is hooked to:
 * 1. `wp_before_admin_bar_render` - 10
 *
 * @since Theme_Blvd 2.0.0
 */
function themeblvd_admin_menu_bar() {

	global $wp_admin_bar;

	if ( is_admin() || ! method_exists( $wp_admin_bar, 'add_node' ) ) {

		return;

	}

	// Determine options page URL.
	$api = Theme_Blvd_Options_API::get_instance();

	$args = $api->get_args();

	$options_page = sprintf( 'themes.php?page=%s', $args['menu_slug'] );

	/**
	 * Filters the URLs to framework admin pages,
	 * used in the admin bar.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param array Admin page URL's.
	 */
	$modules = apply_filters( 'themeblvd_admin_modules', array(
		'options'  => $options_page,
		'builder'  => 'admin.php?page=themeblvd_builder',
		'sidebars' => 'themes.php?page=themeblvd_widget_areas',
	));

	if ( ! $modules ) {

		return;

	}

	if ( isset( $modules['options'] ) ) {

		if ( themeblvd_supports( 'admin', 'options' ) && current_user_can( themeblvd_admin_module_cap( 'options' ) ) ) {

			$wp_admin_bar->add_node( array(
				'id'     => 'tb_theme_options',
				'title'  => __( 'Theme Options', 'jumpstart' ),
				'parent' => 'site-name',
				'href'   => admin_url( $modules['options'] ),
			));
		}
	}

	if ( defined( 'TB_BUILDER_PLUGIN_VERSION' ) && isset( $modules['builder'] ) ) {

		if ( themeblvd_supports( 'admin', 'builder' ) && current_user_can( themeblvd_admin_module_cap( 'builder' ) ) ) {

			$wp_admin_bar->add_node( array(
				'id'     => 'tb_builder',
				'title'  => __( 'Templates', 'jumpstart' ),
				'parent' => 'site-name',
				'href'   => admin_url( $modules['builder'] ),
			));

		}
	}

	if ( defined( 'TB_SIDEBARS_PLUGIN_VERSION' ) && isset( $modules['sidebars'] ) ) {

		if ( themeblvd_supports( 'admin', 'sidebars' ) && current_user_can( themeblvd_admin_module_cap( 'sidebars' ) ) ) {

			$wp_admin_bar->add_node( array(
				'id'     => 'tb_sidebars',
				'title'  => __( 'Widget Areas', 'jumpstart' ),
				'parent' => 'site-name',
				'href'   => admin_url( $modules['sidebars'] ),
			));

		}
	}
}

/**
 * Get all sidebar layouts.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @return array $layouts Sidebar layouts.
 */
function themeblvd_sidebar_layouts() {

	/**
	 * Filters the responsive stacking point for
	 * columns of the sidebar layout.
	 *
	 * Stacking points: `xs`, `sm`, `md`, `lg` or `xl`
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param string Stacking point, `xs`, `sm`, `md`, `lg` or `xl`.
	 */
	$stack = apply_filters( 'themeblvd_sidebar_layout_stack', 'md' );

	$layouts = array(
		'full_width' => array(
			'name'    => __( 'Full Width', 'jumpstart' ),
			'id'      => 'full_width',
			'columns' => array(
				'content' => "col-{$stack}-12",
				'left'    => '',
				'right'   => '',
			),
		),
		'sidebar_right' => array(
			'name'    => __( 'Sidebar Right', 'jumpstart' ),
			'id'      => 'sidebar_right',
			'columns' => array(
				'content' => "col-{$stack}-8",
				'left'    => '',
				'right'   => "col-{$stack}-4",
			),
		),
		'sidebar_left' => array(
			'name'    => __( 'Sidebar Left', 'jumpstart' ),
			'id'      => 'sidebar_left',
			'columns' => array(
				'content' => "col-{$stack}-8",
				'left'    => "col-{$stack}-4",
				'right'   => '',
			),
		),
		'double_sidebar' => array(
			'name'    => __( 'Double Sidebar', 'jumpstart' ),
			'id'      => 'double_sidebar',
			'columns' => array(
				'content' => "col-{$stack}-6",
				'left'    => "col-{$stack}-3",
				'right'   => "col-{$stack}-3",
			),
		),
		'double_sidebar_left' => array(
			'name'    => __( 'Double Left Sidebars', 'jumpstart' ),
			'id'      => 'double_sidebar_left',
			'columns' => array(
				'content' => "col-{$stack}-6",
				'left'    => "col-{$stack}-3",
				'right'   => "col-{$stack}-3",
			),
		),
		'double_sidebar_right' => array(
			'name'    => __( 'Double Right Sidebars', 'jumpstart' ),
			'id'      => 'double_sidebar_right',
			'columns' => array(
				'content' => "col-{$stack}-6",
				'left'    => "col-{$stack}-3",
				'right'   => "col-{$stack}-3",
			),
		),
	);

	/**
	 * Filters the sidebar layouts setup.
	 *
	 * @since Theme_Blvd 2.0.0
	 *
	 * @param array  Sidebar layouts.
	 * @param string Stacking point, `xs`, `sm`, `md`, `lg` or `xl`.
	 */
	return apply_filters( 'themeblvd_sidebar_layouts', $layouts, $stack );

}

/**
 * Load theme text domains.
 *
 * This function is hooked to:
 * 1. `themeblvd_localize` - 10
 *
 * @since Theme_Blvd 2.2.0
 */
function themeblvd_load_theme_textdomain() {

	load_theme_textdomain(
		'jumpstart',
		get_template_directory() . '/languages'
	);

}
