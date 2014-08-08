<?php
/*------------------------------------------------------------*/
/* (1) <head>
/*------------------------------------------------------------*/

/**
 * <head>
 *
 * @since 2.0.0
 * @deprecated 2.3.0
 */
function themeblvd_head() {
	themeblvd_deprecated_function( __FUNCTION__, '2.3.0', null, __('Hook your custom head functionality to wp_head.', 'themeblvd') );
	do_action( 'themeblvd_head' );
}

/*------------------------------------------------------------*/
/* (2) Before and after site
/*------------------------------------------------------------*/

/**
 * Before main wrapper and all HTML markup of the theme.
 *
 * No default hooked function.
 *
 * @since 2.0.0
 */
function themeblvd_before() {
	do_action( 'themeblvd_before' );
}

/**
 * Before main wrapper and all HTML markup of the theme.
 *
 * No default hooked function.
 *
 * @since 2.0.0
 */
function themeblvd_after() {
	do_action( 'themeblvd_after' );
}

/*------------------------------------------------------------*/
/* (3) Header
/*------------------------------------------------------------*/

/**
 * Before any HTML markup for the header, just inside
 * the site's main wrappers.
 *
 * No default hooked function.
 *
 * @since 2.0.0
 */
function themeblvd_header_before() {
	do_action( 'themeblvd_header_before' );
}

/**
 * The highest point inside the header's wrapping
 * HTML markup.
 *
 * No default hooked function.
 *
 * @since 2.0.0
 */
function themeblvd_header_top() {
	do_action( 'themeblvd_header_top' );
}

/**
 * Above the content area of the header. By default,
 * holds the Above Header widget area location.
 *
 * @since 2.0.0
 * @see themeblvd_header_above_default()
 */
function themeblvd_header_above() {
	do_action( 'themeblvd_header_above' );
}

/**
 * The content area of the header. By default
 * includes the logo and the header addon hook.
 *
 * @since 2.0.0
 * @see themeblvd_header_content_default()
 */
function themeblvd_header_content() {
	do_action( 'themeblvd_header_content' );
}

/**
 * The logo of the the site. By default includes function
 * that incorporates logo from Appearance > Theme Options
 * > Layout > Header.
 *
 * @since 2.0.0
 * @see themeblvd_header_logo_default()
 */
function themeblvd_header_logo() {
	do_action( 'themeblvd_header_logo' );
}

/**
 * Just after the logo's HTML markup. This is commonly
 * styled to be floated or positioned to the right of
 * the header.
 *
 * No default hooked function.
 *
 * @since 2.0.0
 */
function themeblvd_header_addon() {
	do_action( 'themeblvd_header_addon' );
}

/**
 * The main menu. By default has "primary" theme navigation
 * location hooked to it.
 *
 * @since 2.0.0
 * @see themeblvd_header_menu_default()
 */
function themeblvd_header_menu() {
	do_action( 'themeblvd_header_menu' );
}

/**
 * Just after the menu's HTML markup. This is commonly
 * styled to be floated or positioned to the right of
 * the main menu.
 *
 * No default hooked function.
 *
 * @since 2.0.0
 */
function themeblvd_header_menu_addon() {
	do_action( 'themeblvd_header_menu_addon' );
}

/**
 * After all HTML markup for the header.
 *
 * No default hooked function.
 *
 * @since 2.0.0
 */
function themeblvd_header_after() {
	do_action( 'themeblvd_header_after' );
}

/*------------------------------------------------------------*/
/* (4) Featured Area (above)
/*------------------------------------------------------------*/

/**
 * HTML markup to start the wrapping HTML markup for the
 * featured area above the main content, when in use.
 *
 * @since 2.0.0
 * @see themeblvd_featured_start_default()
 */
function themeblvd_featured_start() {
	do_action( 'themeblvd_featured_start' );
}

/**
 * Display featured content above the main content area.
 * This hook only runs if featured area has been set to
 * active for the current page.
 *
 * No default hooked function.
 *
 * @since 2.0.0
 */
function themeblvd_featured() {
	do_action( 'themeblvd_featured' );
}

/**
 * HTML markup to end the wrapping HTML markup for the
 * featured area above the main content, when in use.
 *
 * @since 2.0.0
 * @see themeblvd_featured_end_default()
 */
function themeblvd_featured_end() {
	do_action( 'themeblvd_featured_end' );
}

/*------------------------------------------------------------*/
/* (5) Featured Area (below)
/*------------------------------------------------------------*/

/**
 * HTML markup to start the wrapping HTML markup for the
 * featured area below the main content, when in use.
 *
 * @since 2.1.0
 * @see themeblvd_featured_below_start_default()
 */
function themeblvd_featured_below_start() {
	do_action( 'themeblvd_featured_below_start' );
}

/**
 * Display featured content below the main content area.
 * This hook only runs if featured area has been set to
 * active for the current page.
 *
 * No default hooked function.
 *
 * @since 2.1.0
 */
function themeblvd_featured_below() {
	do_action( 'themeblvd_featured_below' );
}

/**
 * HTML markup to end the wrapping HTML markup for the
 * featured area below the main content, when in use.
 *
 * @since 2.1.0
 * @see themeblvd_featured_below_end_default()
 */
function themeblvd_featured_below_end() {
	do_action( 'themeblvd_featured_below_end' );
}

/*------------------------------------------------------------*/
/* (6) Main content area
/*------------------------------------------------------------*/

/**
 * Start the main wrapper around the sidebar layout.
 *
 * @since 2.0.0
 * @see themeblvd_main_start_default()
 */
function themeblvd_main_start() {
	do_action( 'themeblvd_main_start' );
}

/**
 * Just inside the starting HTML markup for the
 * main sidebar layout area.
 *
 * @since 2.0.0
 * @see themeblvd_main_top_default()
 */
function themeblvd_main_top() {
	do_action( 'themeblvd_main_top' );
}

/**
 * Location for the breadcrumbs above tge sidebar layout,
 * but inside the main sidebar layout area's wrapper.
 *
 * @since 2.0.0
 * @see themeblvd_breadcrumbs_default()
 */
function themeblvd_breadcrumbs() {
	do_action( 'themeblvd_breadcrumbs' );
}

/**
 * Before the sidebar layout starts, after the breadcrumbs.
 *
 * No default hooked function.
 *
 * @since 2.0.0
 * @see themeblvd_before_layout_default()
 */
function themeblvd_before_layout() {
	do_action( 'themeblvd_before_layout' );
}

/**
 * Just before the ending HTML markup for the
 * main sidebar layout area.
 *
 * @since 2.0.0
 * @see themeblvd_main_bottom_default()
 */
function themeblvd_main_bottom() {
	do_action( 'themeblvd_main_bottom' );
}

/**
 * End the main wrapper around the sidebar layout.
 *
 * @since 2.0.0
 * @see themeblvd_main_end_default()
 */
function themeblvd_main_end() {
	do_action( 'themeblvd_main_end' );
}

/*------------------------------------------------------------*/
/* (7) Sidebars
/*------------------------------------------------------------*/

/**
 * Display the sidebar(s) to the left or right of
 * the content.
 *
 * @since 2.0.0
 * @see themeblvd_fixed_sidebars()
 *
 * @param string $position The position of the sidebar(s) on the page, left or right
 */
function themeblvd_sidebars( $position ) {
	do_action( 'themeblvd_sidebars', $position );
}

/*------------------------------------------------------------*/
/* (8) Footer
/*------------------------------------------------------------*/

/**
 * Before any HTML markup wrapping the footer
 * has started.
 *
 * No default hooked function.
 *
 * @since 2.0.0
 */
function themeblvd_footer_before() {
	do_action( 'themeblvd_footer_before' );
}

/**
 * Above the content of the footer.
 *
 * No default hooked function.
 *
 * @since 2.0.0
 */
function themeblvd_footer_above() {
	do_action( 'themeblvd_footer_above' );
}

/**
 * The main content of the footer. By default will
 * include the configured columns from Appearance >
 * Theme Options > Layout > Footer.
 *
 * @since 2.0.0
 * @see themeblvd_footer_content_default()
 */
function themeblvd_footer_content() {
	do_action( 'themeblvd_footer_content' );
}

/**
 * Below the main content of the footer. By default
 * includes copyright info configured from Appearance
 * > Theme Options > Layout > Footer.
 *
 * @since 2.0.0
 * @see themeblvd_footer_sub_content_default()
 */
function themeblvd_footer_sub_content() {
	do_action( 'themeblvd_footer_sub_content' );
}

/**
 * Below the footer. By default, holds the Below Footer
 * widget area location.
 *
 * @since 2.0.0
 * @see themeblvd_footer_below_default()
 */
function themeblvd_footer_below() {
	do_action( 'themeblvd_footer_below' );
}

/**
 * After all HTML markup wrapping the footer
 * has ended.
 *
 * No default hooked function.
 *
 * @since 2.0.0
 */
function themeblvd_footer_after() {
	do_action( 'themeblvd_footer_after' );
}

/*------------------------------------------------------------*/
/* (9) Content
/*------------------------------------------------------------*/

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
function themeblvd_the_post_thumbnail( $location = 'primary', $size = '', $link = true, $allow_filters = true ) {
	do_action( 'themeblvd_the_post_thumbnail', $location, $size, $link, $allow_filters );
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