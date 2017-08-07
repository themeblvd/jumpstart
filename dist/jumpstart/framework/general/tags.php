<?php
/**
 * Frontend template tags, which serve primarily
 * as action hook wrappers.
 *
 * @author		Jason Bobich
 * @copyright	2009-2017 Theme Blvd
 * @link		http://themeblvd.com
 * @package 	Jump_Start
 */

/**
 * Within the main content column, just before
 * the_content().
 *
 * Default hooked function includes (1) handling
 * titles on archive pages and (2) outputting page
 * title and content at the top of Post List and
 * Post Grid page templates.
 *
 * @since 2.0.0
 * @see themeblvd_content_top_default()
 */
function themeblvd_content_top() {
	do_action( 'themeblvd_content_top' );
}

/**
 * At the end of the main content column, after
 * comments template.
 *
 * No default hooked function.
 *
 * @since 2.3.0
 */
function themeblvd_content_bottom() {
	do_action( 'themeblvd_content_bottom' );
}

/**
 * The meta info that generally displays with a blog
 * post below the title.
 *
 * @since 2.0.0
 * @see themeblvd_blog_meta_default()
 */
function themeblvd_blog_meta() {
	do_action( 'themeblvd_blog_meta' );
}

/**
 * The meta info that generally displays with a blog
 * post after the post content.
 *
 * @since 2.5.0
 * @see themeblvd_blog_sub_meta_default()
 */
function themeblvd_blog_sub_meta() {
	do_action( 'themeblvd_blog_sub_meta' );
}

/**
 * The meta info that generally displays with a blog
 * post below the title.
 *
 * @since 2.5.0
 * @see themeblvd_grid_meta_default()
 */
function themeblvd_grid_meta() {
	do_action( 'themeblvd_grid_meta' );
}

/**
 * The meta info that generally displays with a post
 * in search results.
 *
 * @since 2.5.0
 * @see themeblvd_search_meta_default()
 */
function themeblvd_search_meta() {
	do_action( 'themeblvd_search_meta' );
}

/**
 * Display the featured image, accounting for framework's
 * Image Link settings.
 *
 * @since 2.0.0
 * @see themeblvd_the_post_thumbnail_default()
 *
 * @param string $location Where the thumbnail is being used -- primary, featured, single -- sort of a wild card to build on in the future as conflicts arise.
 * @param string $size For the image crop size of the thumbnail
 * @param bool $link Set to false to force a thumbnail to ignore post's Image Link options
 * @param bool $allow_filters Whether to allow general filters on the thumbnail or not
 */
function themeblvd_the_post_thumbnail( $size = '', $args = array() ) {

	// Deal with backwards compat issues
	// Deprecated declaration: themeblvd_the_post_thumbnail( $location = 'primary', $size = '' )
	$new_args = array();

	if ( $size == 'primary' || $size == 'single' ) {
		$new_args['location'] = $size;
	}
	if ( $args && ! is_array($args) ) {
		$size = $args;
		$args = array();
	}

	$args = wp_parse_args( $args, $new_args );

	do_action( 'themeblvd_the_post_thumbnail', $size, $args );
}

/**
 * Display either (1) the_content() or (2) the_excerpt()
 * followed by a button to the permalink.
 *
 * @since 2.0.0
 * @see themeblvd_blog_content_default()
 *
 * @param string $type Type of content -- content or excerpt
 */
function themeblvd_blog_content( $type ) {
	do_action( 'themeblvd_blog_content', $type );
}

/**
 * Below the content within single.php, before
 * comments template.
 *
 * No default hooked function.
 *
 * @since 2.0.0
 */
function themeblvd_single_footer() {
	do_action( 'themeblvd_single_footer' );
}

/**
 * Below the content within page.php, before
 * comments template.
 *
 * No default hooked function.
 *
 * @since 2.0.0
 */
function themeblvd_page_footer() {
	do_action( 'themeblvd_page_footer' );
}
