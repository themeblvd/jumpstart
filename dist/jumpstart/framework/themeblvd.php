<?php
/**
 * Theme Blvd WordPress Framework
 *
 * This file sets up the framework, by defining framework
 * constants, including all files, and hooking in all
 * intial actions and filters to start everything.
 *
 * It has been split into four sections:
 *
 *     1. Define Constants
 *     2. General Files and Hooks
 *     3. Admin Files and Hooks
 *     4. Frontend Files and Hooks
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */

/*------------------------------------------------------*/
/* Define Constants
/*------------------------------------------------------*/

/**
 * Current framework version.
 *
 * @since Theme_Blvd 2.0.0
 */
define( 'TB_FRAMEWORK_VERSION', '2.7.5' );

/**
 * Absolute file path to framework directory.
 *
 * @since Theme_Blvd 2.0.0
 */
define( 'TB_FRAMEWORK_DIRECTORY', get_template_directory() . '/framework' );

/**
 * URI to framework directory.
 *
 * @since Theme_Blvd 2.0.0
 */
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
include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/sanitize.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/sanitize-specialized.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/sanitize-advanced.php' );

/**
 * Include options for user profiles, mainly for
 * configuring author box settings and author
 * archive post display.
 *
 * Note: This file is included generally because
 * it's used on the frontend to get user contact icons.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/admin/meta/class-theme-blvd-user-options.php' );

/**
 * Include options for edit taxonomy pages.
 *
 * Note: This file is included generally because
 * it's also used to retrieve the stored taxonomy
 * settings on the frontend.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/admin/meta/class-theme-blvd-tax-options.php' );

/**
 * Include all default framework options to build
 * the theme options page.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/api/class-theme-blvd-options-api.php' );

/**
 * Include all default framework widget area
 * handling and registration.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/api/class-theme-blvd-sidebar-handler.php' );

/**
 * Include API helper functions. These are mostly
 * wrapper functions for the various API objects.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/api/helpers-deprecated.php' ); // For backwards compat only.
include_once( TB_FRAMEWORK_DIRECTORY . '/api/helpers-builder.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/api/helpers-options.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/api/helpers-sidebar.php' );

/**
 * Include third-party plugin compatibility
 * functionality.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/compat/compat.php' );

/**
 * Include general setup functions.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/general/setup.php' );

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
 * Include media-related functions.
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
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/meta/class-theme-blvd-meta-box.php' );

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
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/class-theme-blvd-menu-options.php' );

	/**
	 * Include re-usable class for creating option pages
	 * in the WordPress admin.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/class-theme-blvd-options-page.php' );

	/**
	 * Include singleton class for setting advanced option
	 * types, which are not directly setup within
	 * themeblvd_option_fields() in options-interface.php.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/class-theme-blvd-advanced-options.php' );

	/**
	 * Include re-usable abstract class for setting up
	 * sortable options, along with child classes for
	 * each type of sortable option.
	 *
	 * Note: All sortable option types are registered
	 * configured from class-tb-advanced-options.php
	 * (just above).
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/class-theme-blvd-sortable-option.php' );

	/**
	 * Include singleton class which sets up the interface
	 * for the end-user to select a theme base (if enabled
	 * at the theme level).
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/tools/class-theme-blvd-bases.php' );

	/**
	 * Include singleton class which gives first-time users
	 * a welcome message, pointing them to info on getting
	 * started.
	 */
	include_once( TB_FRAMEWORK_DIRECTORY . '/admin/tools/class-theme-blvd-welcome.php' );

	/*
	 * Hook admin actions.
	 */
	add_action( 'admin_enqueue_scripts', 'themeblvd_admin_enqueue' );
	add_action( 'admin_init', 'themeblvd_add_sanitization' );
	add_action( 'admin_init', 'themeblvd_clear_options' );
	add_action( 'themeblvd_options_footer_text', 'themeblvd_options_footer_text_default' );
	add_action( 'admin_init', 'themeblvd_update_version' );
	add_action( 'page_attributes_meta_box_template', 'themeblvd_sidebar_layout_dropdown', 10, 2 );
	add_action( 'save_post_page', 'themeblvd_save_page_atts' );
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

/**
 * Include singleton class which sets up any secondary
 * queries and hooks in any modifications to the main
 * WP Query.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/general/class-theme-blvd-query.php' );

/**
 * Include singleton class which initializes the website
 * frontend.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/general/class-theme-blvd-frontend-init.php' );

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
 * Include deprecated functions, no longer being used.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/general/deprecated.php' );

/**
 * Include general helper functions.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/helpers/base.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/helpers/comments.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/helpers/general.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/helpers/icons.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/helpers/layout.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/helpers/media.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/helpers/nav.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/helpers/posts.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/helpers/style.php' );

/**
 * Include frontend blocks.
 *
 * Frontend blocks consist of smaller pieces, ready to
 * be displayed throoughout the website.
 *
 * Most blocks have a themeblvd_get_{block}() function
 * that returns a filterable output, along with a
 * themeblvd_{block}() display function.
 */
include_once( TB_FRAMEWORK_DIRECTORY . '/blocks/archive.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/blocks/content.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/blocks/components.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/blocks/loop.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/blocks/media.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/blocks/nav.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/blocks/parts.php' );
include_once( TB_FRAMEWORK_DIRECTORY . '/blocks/post.php' );
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
add_filter( 'the_password_form', 'themeblvd_password_form' );
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
add_action( 'wp_enqueue_scripts', 'themeblvd_include_styles' );

/*
 * Hook frontend actions for the website header.
 */
add_action( 'themeblvd_before', 'themeblvd_widgets_above_header', 20 );
add_action( 'themeblvd_header', 'themeblvd_header_default' );
add_action( 'themeblvd_header_top', 'themeblvd_header_top_default' );
add_action( 'themeblvd_header_content', 'themeblvd_header_content_default' );
add_action( 'themeblvd_header_logo', 'themeblvd_header_logo_default' );
add_action( 'themeblvd_header_menu', 'themeblvd_header_menu_default' );
add_action( 'themeblvd_header_after', 'themeblvd_epic_thumb' );
add_filter( 'themeblvd_header_text', 'themeblvd_do_fa' );

/*
 * Hook frontend actions for the website mobile
 * header.
 */
add_action( 'themeblvd_before', 'themeblvd_mobile_header' );
add_action( 'themeblvd_mobile_header', 'themeblvd_mobile_header_logo', 10 );
add_action( 'themeblvd_mobile_header', 'themeblvd_mobile_header_menu', 20 );

/*
 * Hook frontend actions for the website sticky
 * header.
 */
add_action( 'themeblvd_after', 'themeblvd_sticky_header' );
add_action( 'themeblvd_sticky_header', 'themeblvd_sticky_header_logo', 10 );
add_action( 'themeblvd_sticky_header', 'themeblvd_sticky_header_menu', 20 );

/*
 * Hook frontend actions for the website sidebars.
 */
add_action( 'themeblvd_fixed_sidebar_before', 'themeblvd_fixed_sidebar_before_default' );
add_action( 'themeblvd_fixed_sidebar_after', 'themeblvd_fixed_sidebar_after_default' );
add_action( 'themeblvd_sidebars', 'themeblvd_fixed_sidebars' );

/*
 * Hook frontend actions for the website main
 * content area.
 */
add_action( 'themeblvd_main_start', 'themeblvd_main_start_default' );
add_action( 'themeblvd_main_top', 'themeblvd_widgets_above_content' );
add_action( 'themeblvd_main_bottom', 'themeblvd_widgets_below_content' );
add_action( 'themeblvd_main_end', 'themeblvd_main_end_default' );
add_action( 'themeblvd_breadcrumbs', 'themeblvd_breadcrumbs_default' );

/*
 * Hook frontend actions for the website footer.
 */
add_action( 'themeblvd_footer', 'themeblvd_footer_default' );
add_action( 'themeblvd_footer_content', 'themeblvd_footer_content_default' );
add_action( 'themeblvd_footer_sub_content', 'themeblvd_footer_sub_content_default' );
add_action( 'themeblvd_after', 'themeblvd_widgets_below_footer', 5 );
add_action( 'themeblvd_after', 'themeblvd_floating_search' );
add_action( 'themeblvd_after', 'themeblvd_to_top' );

/*
 * Hook frontend actions for the website side panel.
 */
add_action( 'themeblvd_after', 'themeblvd_side_panel' );
add_action( 'themeblvd_side_panel', 'themeblvd_side_panel_menu' );
add_action( 'themeblvd_side_panel', 'themeblvd_side_panel_sub_menu', 20 );
add_action( 'themeblvd_side_panel', 'themeblvd_side_panel_contact', 30 );

/*
 * Hook frontend actions for the website mobile panel.
 */
add_action( 'themeblvd_after', 'themeblvd_mobile_panel' );
add_action( 'themeblvd_mobile_panel', 'themeblvd_mobile_panel_search' );
add_action( 'themeblvd_mobile_panel', 'themeblvd_mobile_panel_menu', 20 );
add_action( 'themeblvd_mobile_panel', 'themeblvd_mobile_panel_sub_menu', 30 );
add_action( 'themeblvd_mobile_panel', 'themeblvd_mobile_panel_contact', 40 );

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

/*------------------------------------------------------*/
/* Finalize and Load Theme Functions
/*------------------------------------------------------*/

/**
 * Fires for intervening between framework
 * running and API being established.
 *
 * @since Theme_Blvd 2.3.0
 */
do_action( 'themeblvd_intervene' );

/**
 * Fires when text-domains should be registered
 * for localization.
 *
 * @hooked themeblvd_load_theme_textdomain - 10
 *
 * @since Theme_Blvd 2.3.0
 */
do_action( 'themeblvd_localize' );

/**
 * Fires when API objects should be established
 * in framework.
 *
 * @hooked themeblvd_api_init - 10
 *
 * @since Theme_Blvd 2.3.0
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
