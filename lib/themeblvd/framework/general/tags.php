<?php
/**
 * Template Tags
 *
 * Frontend template tags, which serve primarily
 * as action hook wrappers.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */

/**
 * Display at the top of the content of most
 * template files.
 *
 * @since @@name-framework 2.0.0
 */
function themeblvd_content_top() {

	/**
	 * Fires at the top of the content of most
	 * template files.
	 *
	 * @hooked themeblvd_archive_info - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	do_action( 'themeblvd_content_top' );

}

/**
 * Display at the bottom of the content of most
 * template files.
 *
 * @since @@name-framework 2.3.0
 */
function themeblvd_content_bottom() {

	/**
	 * Fires at the bottom of the content of most
	 * template files.
	 *
	 * @since @@name-framework 2.3.0
	 */
	do_action( 'themeblvd_content_bottom' );

}

/**
 * Display meta info for a post, in the `blog`
 * post display.
 *
 * @since @@name-framework 2.0.0
 */
function themeblvd_blog_meta() {

	/**
	 * Fires where the meta info for a post should be
	 * inserted, in the `blog` post display.
	 *
	 * The meta info that generally displays below
	 * the post title with information like the post
	 * format, publish data, author, etc.
	 *
	 * @since @@name-framework 2.0.0
	 */
	do_action( 'themeblvd_blog_meta' );

}

/**
 * Display the sub meta below the post content
 * in the `blog` post display.
 *
 * @since @@name-framework 2.5.0
 */
function themeblvd_blog_sub_meta() {

	/**
	 * Fires where the sub meta info for a post
	 * should be inserted, in the `blog` post
	 * display.
	 *
	 * The sub meta info generally displays below
	 * the post's content with things like the links
	 * to share a post and the post tags.
	 *
	 * @hooked themeblvd_blog_sub_meta_default - 10
	 *
	 * @since @@name-framework 2.5.0
	 */
	do_action( 'themeblvd_blog_sub_meta' );

}

/**
 * Display meta info for a post, in the `grid`
 * post display.
 *
 * @since @@name-framework 2.5.0
 */
function themeblvd_grid_meta() {

	/**
	 * Fires where the meta info for a post should be
	 * inserted, in the `grid` post display.
	 *
	 * The meta info that generally displays below
	 * the post title with information like the post
	 * format, publish data, author, etc.
	 *
	 * @hooked themeblvd_grid_meta_default - 10
	 *
	 * @since @@name-framework 2.5.0
	 */
	do_action( 'themeblvd_grid_meta' );

}

/**
 * Display meta info for a post, in a list
 * of search results.
 *
 * @since @@name-framework 2.5.0
 */
function themeblvd_search_meta() {

	/**
	 * Fires where the meta info for a post, in a
	 * list of search results.
	 *
	 * The meta info that generally displays below
	 * the post title with information like the post
	 * format, publish data, author, etc.
	 *
	 * @hooked themeblvd_search_meta_default - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	do_action( 'themeblvd_search_meta' );

}

/**
 * Display the featured image, accounting for framework's
 * Image Link settings.
 *
 * @see themeblvd_get_post_thumbnail()
 *
 * @since @@name-framework 2.0.0
 *
 * @param string $size Optional. Image crop size.
 * @param array  $args Optional. See themeblvd_get_post_thumbnail().
 */
function themeblvd_the_post_thumbnail( $size = '', $args = array() ) {

	/*
	 * Handle backwards compatibility.
	 *
	 * The themeblvd_the_post_thumbnail() function used to
	 * take different parameters like:
	 * themeblvd_the_post_thumbnail( $location, $size )
	 */
	$new_args = array();

	if ( 'primary' === $size || 'single' === $size ) {

		$new_args['location'] = $size;

	}

	if ( $args && ! is_array($args) ) {

		$size = $args;

		$args = array();

	}

	$args = wp_parse_args( $args, $new_args );

	/**
	 * Fires in most scenarios where the featured
	 * image should be displayed.
	 *
	 * @hooked themeblvd_the_post_thumbnail_default - 10
	 *
	 * @since @@name-framework 2.0.0
	 *
	 * @param string $size Optional. Image crop size.
	 * @param array  $args Optional. See themeblvd_get_post_thumbnail().
	 */
	do_action( 'themeblvd_the_post_thumbnail', $size, $args );

}

/**
 * Display the content for a post, within the
 * `blog` post display type.
 *
 * @since @@name-framework 2.0.0
 *
 * @param string $type Type of content, `content` or `excerpt`.
 */
function themeblvd_blog_content( $type ) {

	/**
	 * Fires when the content is displayed for a
	 * post, within the `blog` post display type.
	 *
	 * @hooked themeblvd_blog_content_default - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	do_action( 'themeblvd_blog_content', $type );

}

/**
 * Display immediately following the content
 * of a single post, before the comments
 * display.
 *
 * @since @@name-framework 2.0.0
 */
function themeblvd_single_footer() {

	/**
	 * Fires below the content of a single post,
	 * but before the comments.
	 *
	 * @hooked themeblvd_single_footer_default - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	do_action( 'themeblvd_single_footer' );
}

/**
 * Display immediately following the content
 * of a page, before the comments display.
 *
 * @since @@name-framework 2.0.0
 */
function themeblvd_page_footer() {

	/**
	 * Fires below the content of a page, but
	 * before the comments.
	 *
	 * @since @@name-framework 2.0.0
	 */
	do_action( 'themeblvd_page_footer' );

}

/**
 * Display HTML class for the main website
 * top bar.
 *
 * @since @@name-framework 2.6.0
 */
function themeblvd_header_top_class() {

	$class = array( 'header-top' );

	/**
	 * Filters the CSS classes used with the main
	 * website top bar.
	 *
	 * @since @@name-framework 2.6.0
	 *
	 * @param array $class CSS classes.
	 */
	$class = apply_filters( 'themeblvd_header_top_class', $class );

	if ( $class ) {

		$output = sprintf( 'class="%s"', esc_attr( implode( ' ', $class ) ) );

	}

	/**
	 * Filters the full HTML output of the CSS classes
	 * for the main website top bar.
	 *
	 * @since @@name-framework 2.6.0
	 *
	 * @param string $output HTML output CSS classes.
	 * @param array  $class  CSS classes.
	 */
	echo apply_filters( 'themeblvd_header_top_class_output', $output, $class );

}

/**
 * Display HTML class for the main website
 * header.
 *
 * @since @@name-framework 2.5.0
 */
function themeblvd_header_class() {

	$class = array( 'site-header' );

	if ( themeblvd_config( 'suck_up' ) ) {

		$class[] = 'transparent';

	} else {

		$class[] = 'standard';

	}

	/**
	 * Filters the CSS classes used with the main
	 * website header.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array $class CSS classes.
	 */
	$class = apply_filters( 'themeblvd_header_class', $class );

	if ( $class ) {

		$output = sprintf( 'class="%s"', esc_attr( implode( ' ', $class ) ) );

	}

	/**
	 * Filters the full HTML output of the CSS classes
	 * for the main website header.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output HTML output CSS classes.
	 * @param array  $class  CSS classes.
	 */
	echo apply_filters( 'themeblvd_header_class_output', $output, $class );

}

/**
 * Display HTML class for the mobile website
 * header.
 *
 * @since @@name-framework 2.7.0
 */
function themeblvd_mobile_header_class() {

	$class = array( 'tb-mobile-header' );

	/**
	 * Filters the CSS classes used with the mobile
	 * website header.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param array $class CSS classes.
	 */
	$class = apply_filters( 'themeblvd_mobile_header_class', $class );

	if ( $class ) {

		$output = sprintf( 'class="%s"', esc_attr( implode( ' ', $class ) ) );

	}

	/**
	 * Filters the full HTML output of the CSS classes
	 * for the mobile website header.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string $output HTML output CSS classes.
	 * @param array  $class  CSS classes.
	 */
	echo apply_filters( 'themeblvd_mobile_header_class_output', $output, $class );

}

/**
 * Display HTML class for the sticky website
 * header.
 *
 * @since @@name-framework 2.7.0
 */
function themeblvd_sticky_class() {

	$class = array( 'tb-sticky-header' );

	/**
	 * Filters the CSS classes used with the sticky
	 * website header.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param array $class CSS classes.
	 */
	$class = apply_filters( 'themeblvd_sticky_class', $class );

	if ( $class ) {

		$output = sprintf( 'class="%s"', esc_attr( implode( ' ', $class ) ) );

	}

	/**
	 * Filters the full HTML output of the CSS classes
	 * for the sticky website header.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string $output HTML output CSS classes.
	 * @param array  $class  CSS classes.
	 */
	echo apply_filters( 'themeblvd_sticky_class_output', $output, $class );

}

/**
 * Display HTML class for the main website
 * content wrapper.
 *
 * @since @@name-framework 2.5.1
 */
function themeblvd_main_class() {

	$config = Theme_Blvd_Frontend_Init::get_instance();

	$class = array();

	$class[] = 'site-main';

	if ( ! is_page_template( 'template_builder.php' ) || post_password_required() ) {

		$class[] = 'site-inner';

		$class[] = $config->get_config( 'sidebar_layout' );

	}

	if ( themeblvd_get_att( 'epic_thumb' ) ) {

		$class[] = 'has-epic-thumb-above';

	}

	$class[] = 'clearfix';

	/**
	 * Filters the CSS classes used with the main
	 * website content wrapper.
	 *
	 * @since @@name-framework 2.5.1
	 *
	 * @param array $class CSS classes.
	 */
	$class = apply_filters( 'themeblvd_main_class', $class );

	if ( $class ) {

		$output = sprintf( 'class="%s"', esc_attr( implode( ' ', $class ) ) );

	}

	/**
	 * Filters the full HTML output of the CSS classes
	 * for the main website content wrapper.
	 *
	 * @since @@name-framework 2.5.1
	 *
	 * @param string $output HTML output CSS classes.
	 * @param array  $class  CSS classes.
	 */
	echo apply_filters( 'themeblvd_main_class_output', $output, $class );

}

/**
 * Display HTML class for main website
 * footer.
 *
 * @since @@name-framework 2.5.0
 */
function themeblvd_footer_class() {

	$class = array( 'site-footer' );

	if ( themeblvd_get_option( 'footer_setup' ) ) {

		$class[] = 'has-columns';
	}

	/**
	 * Filters the CSS classes used with the main
	 * website footer.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array $class CSS classes.
	 */
	$class = apply_filters( 'themeblvd_footer_class', $class );

	if ( $class ) {

		$output = sprintf( 'class="%s"', esc_attr( implode( ' ', $class ) ) );

	}

	/**
	 * Filters the full HTML output of the CSS classes
	 * for the main website footer.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output HTML output CSS classes.
	 * @param array  $class  CSS classes.
	 */
	echo apply_filters( 'themeblvd_footer_class_output', $output, $class );

}

/**
 * Display HTML class for main website
 * footer.
 *
 * @since @@name-framework 2.7.0
 */
function themeblvd_copyright_class() {

	$class = array(
		'site-copyright',
		'footer-sub-content', // Backwards compatibility.
	);

	$menu = themeblvd_get_wp_nav_menu_args( 'footer' );

	if ( has_nav_menu( $menu['theme_location'] ) ) {

		$class[] = 'has-nav';

	}

	/**
	 * Filters the CSS classes used with the website
	 * copyright at the bottom of the footer.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param array $class CSS classes.
	 */
	$class = apply_filters( 'themeblvd_copyright_class', $class );

	if ( $class ) {

		$output = sprintf( 'class="%s"', esc_attr( implode( ' ', $class ) ) );

	}

	/**
	 * Filters the full HTML output of the CSS classes
	 * for website copyright at the bottom of the footer.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string $output HTML output CSS classes.
	 * @param array  $class  CSS classes.
	 */
	echo apply_filters( 'themeblvd_copyright_class_output', $output, $class );

}

/**
 * Display HTML class for the hidden
 * side panel.
 *
 * @since @@name-framework 2.7.0
 */
function themeblvd_side_panel_class() {

	$class = array( 'tb-side-panel' );

	/**
	 * Filters the CSS classes used with the
	 * hidden side panel.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param array $class CSS classes.
	 */
	$class = apply_filters( 'themeblvd_side_panel_class', $class );

	if ( $class ) {

		$output = sprintf( 'class="%s"', esc_attr( implode( ' ', $class ) ) );

	}

	/**
	 * Filters the full HTML output of the CSS classes
	 * for the hidden side panel.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string $output HTML output CSS classes.
	 * @param array  $class  CSS classes.
	 */
	echo apply_filters( 'themeblvd_side_panel_class_output', $output, $class );

}

/**
 * Display HTML class for the hidden
 * side panel.
 *
 * @since @@name-framework 2.7.0
 */
function themeblvd_mobile_panel_class() {

	$class = array(
		'tb-mobile-panel',
		'tb-mobile-menu-wrapper', // Backwards compatibility.
	);

	$location = 'right';

	if ( is_rtl() ) {

		$location = 'left';

	}

	/**
	 * Filters the location that the mobile menu
	 * slides out from.
	 *
	 * This can currently only be left or right,
	 * but may be extended in the future.
	 * @TODO Issue #185.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string $location Location of mobile menu, `left` or `right`.
	 */
	$location = apply_filters( 'themeblvd_mobile_panel_location', $location );

	$class[] = $location;

	/**
	 * Filters the CSS classes used with the
	 * hidden mobile panel.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array $class CSS classes.
	 */
	$class = apply_filters( 'themeblvd_mobile_panel_class', $class );

	if ( $class ) {

		$output = sprintf( 'class="%s"', esc_attr( implode( ' ', $class ) ) );

	}

	/**
	 * Filters the full HTML output of the CSS classes
	 * for the hidden mobile panel.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string $output HTML output CSS classes.
	 * @param array  $class  CSS classes.
	 */
	echo apply_filters( 'themeblvd_mobile_panel_class_output', $output, $class );

}

/**
 * Display HTML class for the searchform
 * from get_searchform()
 *
 * @since @@name-framework 2.7.0
 */
function themeblvd_searchform_class() {

	$class = array( 'tb-search' );

	$add = themeblvd_get_att( 'search_class' );

	if ( $add ) {

		$add = explode( ' ', $add );

		$class = array_merge( $class, $add );

	}

	/**
	 * Filters the CSS classes used with the
	 * searchform from get_searchform().
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param array $class CSS classes.
	 */
	$class = apply_filters( 'themeblvd_searchform_class', $class );

	if ( $class ) {

		$output = sprintf( 'class="%s"', esc_attr( implode( ' ', $class ) ) );

	}

	/**
	 * Filters the full HTML output of the CSS classes
	 * for searchform from get_searchform().
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string $output HTML output CSS classes.
	 * @param array  $class  CSS classes.
	 */
	echo apply_filters( 'themeblvd_searchform_class_output', $output, $class );

}
