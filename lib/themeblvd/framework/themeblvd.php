<?php
/**
 * Theme Blvd WordPress Framework
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     @@name-package
 */

/**
 * Set famework constants.
 */
define( 'TB_FRAMEWORK_VERSION', '2.7.0' );
define( 'TB_FRAMEWORK_DIRECTORY', get_template_directory() . '/framework' );
define( 'TB_FRAMEWORK_URI', get_template_directory_uri() . '/framework' );

/*------------------------------------------------------*/
/* General Files and Hooks
/*------------------------------------------------------*/

/**
 * Include option sanitization funtions.
 *
 * Note: This file is included generally because
 * on the frontend it's needed when options aren't
 * saved properly.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/options-sanitize.php' );

/**
 * Include options for user profiles.
 *
 * Note: This file is included generally because
 * on the frontend to grab the user contsact icons.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/admin/meta/class-tb-user-options.php' );

/**
 * Include options for edit taxonomy pages.
 *
 * Note: This file is included generally because
 * it's also used to retrieve the stored taxonomy
 * settings on the frontend.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/admin/meta/class-tb-tax-options.php' );

/**
 * Include all default framework options to build
 * the theme options page.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/api/class-tb-options-api.php' );

/**
 * Include all default framework widget area
 * handling and registration.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/api/class-tb-sidebar-handler.php' );

/**
 * Include CSS stylesheet API system.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/api/class-tb-stylesheets-handler.php' );

/**
 * Include API helper functions. These are mostly
 * wrapper functions for the various API objects.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/api/helpers.php' );

/**
 * Include third-party plugin compatibility
 * functionality.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/compat/compat.php' );

/**
 * Include general functions, which need to be
 * available on the frontend and backend.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/general/general.php' );

/**
 * Include helper functions for using theme bases,
 * which must be enabled at the theme level.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/general/base.php' );

/**
 * Include grid functions, which help to calculate
 * and lay out columns throughout the framework.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/general/grid.php' );

/**
 * Include all frontend text strings and and
 * JavaScript localization, coming from the
 * framework.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/general/locals.php' );

/**
 * Include media-related helper functions.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/general/media.php' );

/*
 * Hook general filters.
 */
add_filter( 'image_size_names_choose', 'themeblvd_image_size_names_choose' );

/*
 * Hook general actions.
 */
add_action( 'themeblvd_localize', 'themeblvd_load_theme_textdomain' );
add_action( 'themeblvd_api', 'themeblvd_api_init' );
add_action( 'after_setup_theme', 'themeblvd_add_image_sizes' );
add_action( 'wp_before_admin_bar_render', 'themeblvd_admin_menu_bar' );
add_action( 'after_setup_theme', 'themeblvd_plugin_compat' );
add_action( 'after_setup_theme', 'themeblvd_add_theme_support' );
add_action( 'after_setup_theme', 'themeblvd_register_navs' );

/*------------------------------------------------------*/
/* Admin Files and Hooks
/*------------------------------------------------------*/

if ( is_admin() ) {

	/**
	 * Include admin setup functions.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/functions/setup.php' );

	/**
	 * Include admin helper functions.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/functions/helpers.php' );

	/**
	 * Include partial display functions for various admin
	 * components.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/functions/display.php' );

	/**
	 * Include user-facing text strings for the WordPress
	 * admin panel, mostly incorporated through JavaScript.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/functions/locals.php' );

	/**
	 * Include re-usable class used to set up meta boxes
	 * which can be used when editing posts and pages.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/meta/class-tb-meta-box.php' );

	/**
	 * Include setup for all framework meta boxes.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/meta/meta.php' );

	/**
	 * Include display function for options interface.
	 *
	 * The themeblvd_option_fields() function serves the
	 * output for all option panels generated on admin
	 * settings pages and in the layout builder.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/options-interface.php' );

	/**
	 * Include media upload handler for admin options.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/media-uploader.php' );

	/**
	 * Include singleton class for adding framework options
	 * to WordPress's menu builder.
	 *
	 * Note: This object is also responsible for including
	 * class-tb-nav-menu-edit.php, which actually extends
	 * Walker_Nav_Menu_Edit to add the specific options for
	 * enabling framework Mega Menu and menu item styling.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/class-tb-menu-options.php' );

	/**
	 * Include re-usable class for creating option pages
	 * in the WordPress admin.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/class-tb-options-page.php' );

	/**
	 * Include singleton class for setting advanced option
	 * types, which are not directly setup within
	 * themeblvd_option_fields() in options-interface.php.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/class-tb-advanced-options.php' );

	/**
	 * Include re-usable abstract class for setting up
	 * sortable options, along with child classes for
	 * each type of sortable option.
	 *
	 * Note: All sortable option types are registered
	 * configured from class-tb-advanced-options.php
	 * (just above).
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/class-tb-sortable-option.php' );

	/**
	 * Include system for suggesting plugins to the
	 * end-user. Utilizes TGM class.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/plugins/plugins.php' );

	/**
	 * Include singleton class which sets up the interface
	 * for the end-user to select a theme base (if enabled
	 * at the theme level).
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/tools/class-tb-bases.php' );

	/**
	 * Include singleton class which gives first-time users
	 * a welcome message, pointing them to info on getting
	 * started.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/tools/class-tb-welcome.php' );

	/*
	 * Hook admin actions.
	 */
	add_action( 'admin_enqueue_scripts', 'themeblvd_non_modular_assets' );
	add_action( 'admin_init', 'themeblvd_add_sanitization' );
	add_action( 'admin_init', 'themeblvd_clear_options' );
	add_action( 'themeblvd_options_footer_text', 'themeblvd_options_footer_text_default' );
	add_action( 'admin_init', 'themeblvd_update_version' );
	add_action( 'admin_menu', 'themeblvd_hijack_page_atts' );
	add_action( 'save_post', 'themeblvd_save_page_atts' );
	add_action( 'after_setup_theme', 'themeblvd_plugins' );
	add_action( 'admin_init', 'themeblvd_add_meta_boxes' );

	/*
	 * Apply other hooks after theme has had a chance
	 * to add filters.
	 *
	 * Note: Options API/Settings finalized at
	 * after_setup_theme, priority 1000.
	 */
	add_action( 'after_setup_theme', 'themeblvd_admin_init', 1001 );

}

/*------------------------------------------------------*/
/* Frontend Files and Hooks
/*------------------------------------------------------*/

if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

	/**
	 * Include singleton class which sets up any secondary
	 * queries and hooks in any modifications to the main
	 * WP Query.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/general/class-tb-query.php' );

	/**
	 * Include singleton class which initializes the website
	 * frontend.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/general/class-tb-frontend-init.php' );

	/**
	 * Include frontend display functions, which serve as default
	 * callback functions for primary framework actions.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/general/display.php' );

	/**
	 * Include frontend setup functions.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/general/frontend.php' );

	/**
	 * Include frontend helper functions.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/general/helpers.php' );

	/**
	 * Include frontend functions to modify WordPress menus.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/general/menu.php' );

	/**
	 * Include frontend post format functions and filter
	 * callbacks used for modifying content, in regards
	 * to post formats.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/general/post-formats.php' );

	/**
	 * Include frontend template tag action wrappers.
	 *
	 * While most action hooks are generally left exposed in the
	 * wild, some are easier to understand as a function call.
	 * So these functions primarily serve to wrap some of the
	 * inner do_action() calls used in top-level template files.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/general/tags.php' );

	/**
	 * Include frontend layout functions.
	 *
	 * These functions help to serve any custom layouts built
	 * from the Theme Blvd Layout Builder plugin, which consist
	 * of blocks (see next section).
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/general/layout.php' );

	/**
	 * Include frontend blocks.
	 *
	 * Frontend blocks consist of smaller pieces, ready to
	 * be displayed throoughout the website. All blocks have a
	 * themeblvd_get_{block}() function that reutnrs a filterable
	 * output, along with a themeblvd_{block}() display function.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/blocks/content.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/blocks/components.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/blocks/loop.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/blocks/media.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/blocks/parts.php' );
	include_once( TB_FRAMEWORK_DIRECTORY . '/blocks/stats.php' );

	/*
	 * Hook frontend filters.
	 */
	add_filter( 'body_class','themeblvd_body_class' );
	add_filter( 'post_class', 'themeblvd_post_class' );
	add_filter( 'oembed_result', 'themeblvd_oembed_result', 10, 2 );
	add_filter( 'embed_oembed_html', 'themeblvd_oembed_result', 10, 2 );
	add_filter( 'wp_audio_shortcode', 'themeblvd_audio_shortcode' );
	add_filter( 'img_caption_shortcode', 'themeblvd_img_caption_shortcode', 10, 3 );
	add_filter( 'themeblvd_the_content', array( $GLOBALS['wp_embed'], 'run_shortcode' ), 8 );
	add_filter( 'themeblvd_the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );
	add_filter( 'themeblvd_the_content', 'themeblvd_footer_copyright_helpers' );
	add_filter( 'themeblvd_the_content', 'themeblvd_do_fa' );
	add_filter( 'themeblvd_the_content', 'wptexturize' );
	add_filter( 'themeblvd_the_content', 'wpautop' );
	add_filter( 'themeblvd_the_content', 'shortcode_unautop' );
	add_filter( 'themeblvd_the_content', 'do_shortcode' );
	add_filter( 'widget_text', 'themeblvd_footer_copyright_helpers' );
	add_filter( 'widget_text', 'themeblvd_do_fa' );
	add_filter( 'themeblvd_sidebar_layout', 'themeblvd_wpmultisite_signup_sidebar_layout' );
	add_filter( 'the_content_more_link', 'themeblvd_read_more_link', 10, 2 );
	add_filter( 'use_default_gallery_style', '__return_false' );
	add_filter( 'template_include', 'themeblvd_private_page' );
	add_filter( 'wp_link_pages_args', 'themeblvd_link_pages_args' );
	add_filter( 'wp_link_pages_link', 'themeblvd_link_pages_link', 10, 2 );
	add_filter( 'comment_form_default_fields', 'themeblvd_comment_form_fields' );
	add_filter( 'themeblvd_column_class', 'themeblvd_column_class_legacy' );
	add_filter( 'walker_nav_menu_start_el', 'themeblvd_nav_menu_start_el', 10, 4 );
	add_filter( 'nav_menu_css_class', 'themeblvd_nav_menu_css_class', 10, 4 );
	add_filter( 'themeblvd_builder_section_start_count', 'themeblvd_builder_section_start_count' );

	/*
	 * Hook post-format-specific filters.
	 */
	add_filter( 'the_content', 'themeblvd_content_format_audio', 7 );
	add_filter( 'the_content', 'themeblvd_content_format_gallery', 7 );
	add_filter( 'the_content', 'themeblvd_content_format_link', 7 );
	add_filter( 'the_content', 'themeblvd_content_format_quote', 7 );
	add_filter( 'the_content', 'themeblvd_content_format_video', 7 );

	/*
	 * Apply other hooks after theme has had a chance
	 * to add filters.
	 *
	 * Note: Options API/Settings finalized at
	 * after_setup_theme, priority 1000.
	 */
	add_action( 'after_setup_theme', 'themeblvd_frontend_init', 1001 );

	/*
	 * Hook frontend actions for the document <head>.
	 */
	add_action( 'wp_enqueue_scripts', 'themeblvd_include_scripts' );
	add_action( 'wp_print_scripts', 'themeblvd_html5_compat' ); // For IE8
	add_action( 'wp_head', 'themeblvd_viewport_default', 2 );
	add_filter( 'wp_head', 'themeblvd_wp_title_compat', 5 ); // Only used with WP 4.0-

	/*
	 * Hook frontend actions for the website header.
	 */
	add_action( 'themeblvd_header_before', 'themeblvd_header_before_default' );
	add_action( 'themeblvd_header_top', 'themeblvd_header_top_default' );
	add_action( 'themeblvd_header_content', 'themeblvd_header_content_default' );
	add_action( 'themeblvd_header_addon', 'themeblvd_responsive_menu_toggle' );
	add_action( 'themeblvd_header_logo', 'themeblvd_header_logo_default' );
	add_action( 'themeblvd_header_logo', 'themeblvd_header_logo_mobile', 20 );
	add_action( 'themeblvd_header_menu', 'themeblvd_header_menu_default' );
	add_action( 'themeblvd_header_after', 'themeblvd_epic_thumb' );
	add_filter( 'themeblvd_header_text', 'themeblvd_do_fa' );

	/*
	 * Hook frontend actions for the website sidebars.
	 */
	add_action( 'themeblvd_fixed_sidebar_before', 'themeblvd_fixed_sidebar_before_default' );
	add_action( 'themeblvd_fixed_sidebar_after', 'themeblvd_fixed_sidebar_after_default' );
	add_action( 'themeblvd_sidebars', 'themeblvd_fixed_sidebars' );

	/*
	 * Hook frontend actions for the website
	 * featured area. @deprecated @TODO Remove.
	 */
	add_action( 'themeblvd_featured', 'themeblvd_featured_start_default', 5 );
	add_action( 'themeblvd_featured', 'themeblvd_featured_end_default', 20 );
	add_action( 'themeblvd_featured_below', 'themeblvd_featured_below_start_default', 5 );
	add_action( 'themeblvd_featured_below', 'themeblvd_featured_below_end_default', 20 );

	/*
	 * Hook frontend actions for the website main
	 * content area.
	 */
	add_action( 'themeblvd_main_start', 'themeblvd_main_start_default' );
	add_action( 'themeblvd_main_top', 'themeblvd_main_top_default' );
	add_action( 'themeblvd_main_bottom', 'themeblvd_main_bottom_default' );
	add_action( 'themeblvd_main_end', 'themeblvd_main_end_default' );
	add_action( 'themeblvd_breadcrumbs', 'themeblvd_breadcrumbs_default' );

	/*
	 * Hook frontend actions for the website footer.
	 */
	add_action( 'themeblvd_footer_content', 'themeblvd_footer_content_default' );
	add_action( 'themeblvd_footer_sub_content', 'themeblvd_footer_sub_content_default' );
	add_action( 'themeblvd_footer_below', 'themeblvd_footer_below_default' );
	add_action( 'themeblvd_after', 'themeblvd_floating_search' );
	add_action( 'themeblvd_after', 'themeblvd_to_top' );

	/*
	 * Hook frontend actions for the website side panel.
	 */
	add_action( 'themeblvd_after', 'themeblvd_side_panel' );
	add_action( 'themeblvd_side_panel', 'themeblvd_side_panel_menu' );
	add_action( 'themeblvd_side_panel', 'themeblvd_side_panel_sub_menu', 20 );

	/*
	 * Hook frontend actions for the website inner
	 * content area.
	 */
	add_action( 'themeblvd_content_top', 'themeblvd_archive_info' );
	add_action( 'themeblvd_single_footer', 'themeblvd_single_footer_default' );
	add_action( 'themeblvd_blog_meta', 'themeblvd_blog_meta_default' );
	add_action( 'themeblvd_blog_sub_meta', 'themeblvd_blog_sub_meta_default' );
	add_action( 'themeblvd_grid_meta', 'themeblvd_grid_meta_default' );
	add_action( 'themeblvd_search_meta', 'themeblvd_search_meta_default' );
	add_action( 'themeblvd_the_post_thumbnail', 'themeblvd_the_post_thumbnail_default', 9, 2 );
	add_action( 'themeblvd_blog_content', 'themeblvd_blog_content_default' );

	/*
	 * Hook frontend actions for structuring the
	 * WordPress multisite signup page to fit with
	 * the theme.
	 */
	add_action( 'before_signup_form', 'themeblvd_before_signup_form' );
	add_action( 'after_signup_form', 'themeblvd_after_signup_form' );

}

/**
 * Action hook for intervening between framework
 * running and API being established.
 *
 * @since @@framework-name 2.3.0
 * @hooked null
 */
do_action( 'themeblvd_intervene' );

/**
 * Fires when text-domains should be registered
 * for localization.
 *
 * @since @@framework-name 2.3.0
 * @hooked themeblvd_load_theme_textdomain - 10
 */
do_action( 'themeblvd_localize' );

/**
 * Fires when API objects should be established
 * in framework.
 *
 * @since @@framework-name 2.3.0
 * @hooked themeblvd_api_init - 10
 */
do_action( 'themeblvd_api' );

/**
 * Include theme-specific functions.
 *
 * Any theme-specific functions to extend the framework
 * should be held within the top-level `/inc/` directory.
 * This should all stem from /inc/theme-functions.php.
 */
include_once( get_template_directory() . '/inc/theme-functions.php' );
