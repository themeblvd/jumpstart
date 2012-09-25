<?php
/**
 * Theme Blvd WordPress Framework
 * 
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package		Theme Blvd WordPress Framework
 */

// Constants
define( 'TB_FRAMEWORK_VERSION', '2.2.0' );
define( 'TB_FRAMEWORK_DIRECTORY', get_template_directory().'/framework' );
define( 'TB_FRAMEWORK_URI', get_template_directory_uri().'/framework' );

// Run framework
if( is_admin() ) {
	
	/*------------------------------------------------------*/
	/* Admin Hooks, Filters, and Files
	/*------------------------------------------------------*/
	
	// Include files
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/functions/display.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/functions/locals.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/functions/meta.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/options-interface.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/options-medialibrary-uploader.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/options-sanitize.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/class-tb-options-page.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/api/builder.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/api/customizer.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/api/helpers.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/api/locals.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/api/options.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/api/sidebars.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/api/sliders.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/functions/general.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/plugins/plugins.php' );
	
	// Filters
	add_filter( 'image_size_names_choose', 'themeblvd_image_size_names_choose' );
	
	// Apply initial hooks
	add_action( 'themeblvd_localize', 'themeblvd_load_theme_textdomain' );
	add_action( 'themeblvd_api', 'themeblvd_api_init' );
	add_action( 'admin_enqueue_scripts', 'themeblvd_non_modular_assets' );
	add_action( 'admin_init','themeblvd_theme_activation' );
	add_action( 'admin_init', 'themeblvd_add_sanitization' );
	add_action( 'after_setup_theme', 'themeblvd_register_posts', 5 );
	add_action( 'after_setup_theme', 'themeblvd_add_image_sizes' );
	add_action( 'wp_before_admin_bar_render', 'themeblvd_admin_menu_bar' );
	add_action( 'themeblvd_options_footer_text', 'themeblvd_options_footer_text_default' );
	add_action( 'admin_init', 'themeblvd_stats' );
	add_action( 'admin_menu', 'themeblvd_hijack_page_atts' );
	add_action( 'add_meta_boxes', 'themeblvd_add_meta_boxes' );
	add_action( 'save_post', 'themeblvd_save_page_atts' );
	add_action( 'save_post', 'themeblvd_save_meta_boxes' );
	add_action( 'customize_register', 'themeblvd_customizer_init' );
	add_action( 'customize_controls_print_styles', 'themeblvd_customizer_styles' );
	add_action( 'customize_controls_print_scripts', 'themeblvd_customizer_scripts' );
	add_action( 'after_setup_theme', 'themeblvd_plugins' );
	
	// Apply other hooks after theme has had a chance to add filters
	add_action( 'after_setup_theme', 'themeblvd_format_options', 1000 );
	add_action( 'after_setup_theme', 'themeblvd_admin_init', 1000 );
	add_action( 'after_setup_theme', 'themeblvd_add_theme_support', 1000 );
	add_action( 'after_setup_theme', 'themeblvd_register_navs', 1000 );
	add_action( 'after_setup_theme', 'themeblvd_register_sidebars', 1000 );

} else {
	
	/*------------------------------------------------------*/
	/* Front-end Hooks, Filters, and Files
	/*------------------------------------------------------*/

	// Include files
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/options-sanitize.php' ); // Needed if options haven't been saved
	include_once( TB_FRAMEWORK_DIRECTORY . '/api/builder.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/api/customizer.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/api/helpers.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/api/locals.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/api/options.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/api/sidebars.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/api/sliders.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/frontend/functions/elements.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/frontend/functions/parts.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/frontend/functions/actions.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/frontend/functions/helpers.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/frontend/functions/display.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/frontend/functions/general.php' );

	// Filters
	add_filter( 'body_class', 'themeblvd_body_class' );
	add_filter( 'oembed_result', 'themeblvd_oembed_result', 10, 2 );
	add_filter( 'embed_oembed_html', 'themeblvd_oembed_result', 10, 2 );
	add_filter( 'wp_feed_cache_transient_lifetime' , 'themeblvd_feed_transient' );
	add_filter( 'themeblvd_the_content', 'do_shortcode' );
	add_filter( 'image_size_names_choose', 'themeblvd_image_size_names_choose' );
	add_filter( 'themeblvd_tweet_filter', 'themeblvd_tweet_filter_default', 10, 2 );
	add_filter( 'themeblvd_sidebar_layout', 'themeblvd_wpmultisite_signup_sidebar_layout' );
	add_filter( 'the_content_more_link', 'themeblvd_read_more_link' );
	add_filter( 'use_default_gallery_style', '__return_false' );
	add_filter( 'wp_title', 'themeblvd_wp_title' );
	
	// Apply initial hooks
	add_action( 'themeblvd_localize', 'themeblvd_load_theme_textdomain' );
	add_action( 'themeblvd_api', 'themeblvd_api_init' );
	add_action( 'pre_get_posts', 'themeblvd_homepage_posts_per_page' );
	add_action( 'after_setup_theme', 'themeblvd_register_posts', 5 );
	add_action( 'after_setup_theme', 'themeblvd_add_theme_support' );
	add_action( 'after_setup_theme', 'themeblvd_add_image_sizes' );
	add_action( 'wp_enqueue_scripts', 'themeblvd_include_scripts' );
	add_action( 'wp_enqueue_scripts', 'themeblvd_include_styles', 5 );
	add_action( 'wp_before_admin_bar_render', 'themeblvd_admin_menu_bar' );
	add_action( 'customize_register', 'themeblvd_customizer_init' );

	// Apply other hooks after theme has had a chance to add filters
	add_action( 'after_setup_theme', 'themeblvd_format_options', 1000 );
	add_action( 'after_setup_theme', 'themeblvd_register_navs', 1000 );
	add_action( 'after_setup_theme', 'themeblvd_register_sidebars', 1000 );
	add_action( 'wp', 'themeblvd_frontend_init', 5 ); // This needs to run before any plugins hook into it
	add_action( 'wp_print_styles', 'themeblvd_deregister_stylesheets', 1000 );
	
	// <head> hooks
	add_action( 'wp_head', 'themeblvd_viewport_default' );
	add_action( 'wp_head', 'themeblvd_closing_styles', 11 );
	
	// Header hooks
	add_action( 'themeblvd_header_above', 'themeblvd_header_above_default' );
	add_action( 'themeblvd_header_content', 'themeblvd_header_content_default' );
	add_action( 'themeblvd_header_logo', 'themeblvd_header_logo_default' );
	add_action( 'themeblvd_header_menu', 'themeblvd_header_menu_default' );
	
	// Sidebars
	add_action( 'themeblvd_fixed_sidebar_before', 'themeblvd_fixed_sidebar_before_default' );
	add_action( 'themeblvd_fixed_sidebar_after', 'themeblvd_fixed_sidebar_after_default' );
	add_action( 'themeblvd_sidebars', 'themeblvd_fixed_sidebars' );
	
	// Featured area hooks
	add_action( 'themeblvd_featured_start', 'themeblvd_featured_start_default' );
	add_action( 'themeblvd_featured_end', 'themeblvd_featured_end_default' );
	add_action( 'themeblvd_featured_below_start', 'themeblvd_featured_below_start_default' );
	add_action( 'themeblvd_featured_below_end', 'themeblvd_featured_below_end_default' );
	
	// Main content area hooks
	add_action( 'themeblvd_main_start', 'themeblvd_main_start_default' );
	add_action( 'themeblvd_main_top', 'themeblvd_main_top_default' );
	add_action( 'themeblvd_main_bottom', 'themeblvd_main_bottom_default' );
	add_action( 'themeblvd_main_end', 'themeblvd_main_end_default' );
	add_action( 'themeblvd_breadcrumbs', 'themeblvd_breadcrumbs_default' );
	
	// Footer
	add_action( 'themeblvd_footer_content', 'themeblvd_footer_content_default' );
	add_action( 'themeblvd_footer_sub_content', 'themeblvd_footer_sub_content_default' );
	add_action( 'themeblvd_footer_below', 'themeblvd_footer_below_default' );
	add_action( 'themeblvd_footer_below', 'themeblvd_footer_below_default' );
	add_action( 'wp_footer', 'themeblvd_analytics', 999 );

	// Content
	add_action( 'themeblvd_content_top', 'themeblvd_content_top_default' );
	add_action( 'themeblvd_blog_meta', 'themeblvd_blog_meta_default' );
	add_action( 'themeblvd_blog_tags', 'themeblvd_blog_tags_default' );
	add_action( 'themeblvd_the_post_thumbnail', 'themeblvd_the_post_thumbnail_default', 9, 5 );
	add_action( 'themeblvd_blog_content', 'themeblvd_blog_content_default' );
	
	// Elements
	add_action( 'themeblvd_element_open', 'themeblvd_element_open_default', 9, 3 );
	add_action( 'themeblvd_element_close', 'themeblvd_element_close_default', 9, 3 );
	
	// WordPress Multisite Signup
	add_action( 'before_signup_form', 'themeblvd_before_signup_form' );
	add_action( 'after_signup_form', 'themeblvd_after_signup_form' );
}

// Optional Intervene (nothing hooked by default)
do_action( 'themeblvd_intervene' );

// Register text domains
do_action( 'themeblvd_localize' );

// Initiate API
do_action( 'themeblvd_api' );

// Run theme functions
include_once ( get_template_directory() . '/includes/theme-functions.php' );