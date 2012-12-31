<?php
/**
 * Setup user-read text strings. This function allows
 * to have all of the framework's common localized text 
 * strings in once place.
 *
 * The filter "themeblvd_frontend_locals"
 * can be used to add/remove strings.
 *
 * @since 2.1.0
 */

function themeblvd_get_all_locals() {
	$locals = array ( 
		'404'						=> __( 'Apologies, but the page you\'re looking for can\'t be found.', 'themeblvd_frontend' ),
		'404_title'					=> __( '404 Error', 'themeblvd_frontend' ),
		'archive_no_posts'			=> __( 'Apologies, but there are no posts to display.', 'themeblvd_frontend' ),
		'archive'					=> __( 'Archive', 'themeblvd_frontend' ),
		'by'						=> __( 'by', 'themeblvd_frontend' ),
		'cancel_reply_link'			=> __( 'Cancel reply', 'themeblvd_frontend' ),
		'categories'				=> __( 'Categories', 'themeblvd_frontend' ),
		'category'					=> __( 'Category', 'themeblvd_frontend' ),
		'close'						=> __( 'Close', 'themeblvd_frontend' ),
		'comment_navigation'		=> __( 'Comment navigation', 'themeblvd_frontend' ),
		'comment'					=> __( 'Comment', 'themeblvd_frontend' ),
		'comments'					=> __( 'Comments', 'themeblvd_frontend' ),
		'comments_closed'			=> __( 'Comments are closed.', 'themeblvd_frontend' ),
		'comments_newer'			=> __( 'Newer Comments &rarr;', 'themeblvd_frontend' ),
		'comments_no_password'		=> __( 'This post is password protected. Enter the password to view any comments.', 'themeblvd_frontend' ),
		'comments_older'			=> __( '&larr; Older Comments', 'themeblvd_frontend' ),
		'comments_title_single'		=> __( 'One comment on &ldquo;%2$s&rdquo;', 'themeblvd_frontend' ),
		'comments_title_multiple'	=> __( '%1$s comments on &ldquo;%2$s&rdquo;', 'themeblvd_frontend' ),
		'contact_us'				=> __( 'Contact Us', 'themeblvd_frontend' ),
		'crumb_404'					=> __( 'Error 404', 'themeblvd_frontend' ),
		'crumb_author'				=> __( 'Articles posted by', 'themeblvd_frontend' ),
		'crumb_search'				=> __( 'Search results for', 'themeblvd_frontend' ),
		'crumb_tag'					=> __( 'Posts tagged', 'themeblvd_frontend' ),
		'edit_page'					=> __( 'Edit Page', 'themeblvd_frontend' ),
		'email'						=> __( 'Email', 'themeblvd_frontend' ),
		'home'						=> __( 'Home', 'themeblvd_frontend' ),
		'in'						=> __( 'in', 'themeblvd_frontend' ),
		'invalid_layout'			=> __( 'Invalid Layout ID: The layout ID currently assigned to this page no longer exists.', 'themeblvd_frontend' ),
		'label_submit'				=> __( 'Post Comment', 'themeblvd_frontend' ),
		'last_30'					=> __( 'The Last 30 Posts', 'themeblvd_frontend' ),
		'login_text'				=> __( 'Log in to Reply', 'themeblvd_frontend' ),
		'monthly_archives'			=> __( 'Monthly Archives', 'themeblvd_frontend' ),
		'name'						=> __( 'Name', 'themeblvd_frontend' ),
		'no_builder_plugin'			=> sprintf( __( 'In order for your custom layout to be displayed, you must be have the %s plugin installed.', 'themeblvd_frontend' ), '<a href="http://wordpress.org/extend/plugins/theme-blvd-layout-builder" target="_blank">Theme Blvd Layout Builder</a>' ),
		'page'						=> __( 'Page', 'themeblvd_frontend' ),
		'pages'						=> __( 'Pages', 'themeblvd_frontend' ),
		'page_num'					=> __( 'Page %s', 'themeblvd_frontend' ),
		'posted_on'					=> __( 'Posted on', 'themeblvd_frontend' ),
		'posts_per_category'		=> __( 'Posts per category', 'themeblvd_frontend' ),
		'navigation' 				=> __( 'Navigation', 'themeblvd_frontend' ),
		'no_comments'				=> __( 'No Comments', 'themeblvd_frontend' ),
		'no_slider' 				=> __( 'Slider does not exist.', 'themeblvd_frontend' ),
		'no_slider_selected' 		=> __( 'Oops! You have not selected a slider in your layout.', 'themeblvd_frontend' ),
		'no_video'					=> __( 'The video url could not retrieve a video.', 'themeblvd_frontend' ),
		'read_more'					=> __( 'Read More', 'themeblvd_frontend' ),
		'reply'						=> __( 'Reply', 'themeblvd_frontend' ),
		'search'					=> __( 'Search the site...', 'themeblvd_frontend' ),
		'search_no_results'			=> __( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'themeblvd_frontend' ),
		'tag'						=> __( 'Tag', 'themeblvd_frontend' ),
		'title_reply'				=> __( 'Leave a Reply', 'themeblvd_frontend' ),
		'title_reply_to'			=> __( 'Leave a Reply to %s', 'themeblvd_frontend' ),
		'via'						=> __( 'via', 'themeblvd_frontend' ),
		'website'					=> __( 'Website', 'themeblvd_frontend' )
	);
	// Return with framework's filter applied
	return apply_filters( 'themeblvd_frontend_locals', $locals );
}

/**
 * Return user read text string.
 *
 * @since 2.0.0
 *
 * @param string $id Key for $locals array
 * @return string $text Localized and filtered text string
 */

if( ! function_exists( 'themeblvd_get_local' ) ) {
	function themeblvd_get_local( $id ) {
		$text = null;
		$locals = themeblvd_get_all_locals();
		// Set text string
		if( isset( $locals[$id] ) )
			$text = $locals[$id];
		return $text;
	}
}

/**
 * Setup JavaScript localized strings for 
 * themeblvd.js
 *
 * The filter "themeblvd_js_locals"
 * can be used to add/remove strings or other 
 * variables we want to pass through to our primary 
 * "themeblvd" script.
 *
 * @since 2.2.0
 */

function themeblvd_get_js_locals() {
	
	// Start $locals array with any miscellaneous stuff
	$locals = array ( 
		'prettyphoto_theme' 	=> 'pp_default',
		'thumb_animations'		=> 'true',
		'featured_animations'	=> 'true',
		'retina_logo'			=> 'true'
	);
	
	// Extend $locals to accomodate scripts being included 
	// through our "themeblvd_global_config" filter. 
	// This allows people to remove jQuery plugin files 
	// w/out having to also remove functions from themeblvd.js.
	if( themeblvd_supports( 'scripts', 'bootstrap' ) )
		$locals['bootstrap'] = 'true';
	if( themeblvd_supports( 'scripts', 'prettyphoto' ) )
		$locals['prettyphoto'] = 'true';
	if( themeblvd_supports( 'scripts', 'superfish' ) )
		$locals['superfish'] = 'true';
	
	// Return with framework's filter applied
	return apply_filters( 'themeblvd_js_locals', $locals );
}