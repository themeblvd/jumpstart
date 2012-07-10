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
		'404'						=> __( 'Apologies, but the page you\'re looking for can\'t be found.', TB_GETTEXT_DOMAIN_FRONT ),
		'404_title'					=> __( '404 Error', TB_GETTEXT_DOMAIN_FRONT ),
		'archive_no_posts'			=> __( 'Apologies, but there are no posts to display.', TB_GETTEXT_DOMAIN_FRONT ),
		'archive'					=> __( 'Archive', TB_GETTEXT_DOMAIN_FRONT ),
		'by'						=> __( 'by', TB_GETTEXT_DOMAIN_FRONT ),
		'cancel_reply_link'			=> __( 'Cancel reply', TB_GETTEXT_DOMAIN_FRONT ),
		'categories'				=> __( 'Categories', TB_GETTEXT_DOMAIN_FRONT ),
		'category'					=> __( 'Category', TB_GETTEXT_DOMAIN_FRONT ),
		'comment_navigation'		=> __( 'Comment navigation', TB_GETTEXT_DOMAIN_FRONT ),
		'comment'					=> __( 'Comment', TB_GETTEXT_DOMAIN_FRONT ),
		'comments'					=> __( 'Comments', TB_GETTEXT_DOMAIN_FRONT ),
		'comments_closed'			=> __( 'Comments are closed.', TB_GETTEXT_DOMAIN_FRONT ),
		'comments_newer'			=> __( 'Newer Comments &rarr;', TB_GETTEXT_DOMAIN_FRONT ),
		'comments_no_password'		=> __( 'This post is password protected. Enter the password to view any comments.', TB_GETTEXT_DOMAIN_FRONT ),
		'comments_older'			=> __( '&larr; Older Comments', TB_GETTEXT_DOMAIN_FRONT ),
		'comments_title_single'		=> __( 'One comment on &ldquo;%2$s&rdquo;', TB_GETTEXT_DOMAIN_FRONT ),
		'comments_title_multiple'	=> __( '%1$s comments on &ldquo;%2$s&rdquo;', TB_GETTEXT_DOMAIN_FRONT ),
		'contact_us'				=> __( 'Contact Us', TB_GETTEXT_DOMAIN_FRONT ),
		'crumb_404'					=> __( 'Error 404', TB_GETTEXT_DOMAIN_FRONT ),
		'crumb_author'				=> __( 'Articles posted by', TB_GETTEXT_DOMAIN_FRONT ),
		'crumb_search'				=> __( 'Search results for', TB_GETTEXT_DOMAIN_FRONT ),
		'crumb_tag'					=> __( 'Posts tagged', TB_GETTEXT_DOMAIN_FRONT ),
		'edit_page'					=> __( 'Edit Page', TB_GETTEXT_DOMAIN_FRONT ),
		'email'						=> __( 'Email', TB_GETTEXT_DOMAIN_FRONT ),
		'home'						=> __( 'Home', TB_GETTEXT_DOMAIN_FRONT ),
		'in'						=> __( 'in', TB_GETTEXT_DOMAIN_FRONT ),
		'invalid_layout'			=> __( 'Invalid Layout ID', TB_GETTEXT_DOMAIN_FRONT ),
		'label_submit'				=> __( 'Post Comment', TB_GETTEXT_DOMAIN_FRONT ),
		'last_30'					=> __( 'The Last 30 Posts', TB_GETTEXT_DOMAIN_FRONT ),
		'login_text'				=> __( 'Log in to Reply', TB_GETTEXT_DOMAIN_FRONT ),
		'monthly_archives'			=> __( 'Monthly Archives', TB_GETTEXT_DOMAIN_FRONT ),
		'name'						=> __( 'Name', TB_GETTEXT_DOMAIN_FRONT ),
		'page'						=> __( 'Page', TB_GETTEXT_DOMAIN_FRONT ),
		'pages'						=> __( 'Pages', TB_GETTEXT_DOMAIN_FRONT ),
		'page_num'					=> __( 'Page %s', TB_GETTEXT_DOMAIN_FRONT ),
		'posted_on'					=> __( 'Posted on', TB_GETTEXT_DOMAIN_FRONT ),
		'posts_per_category'		=> __( 'Posts per category', TB_GETTEXT_DOMAIN_FRONT ),
		'navigation' 				=> __( 'Navigation', TB_GETTEXT_DOMAIN_FRONT ),
		'no_comments'				=> __( 'No Comments', TB_GETTEXT_DOMAIN_FRONT ),
		'no_slider' 				=> __( 'Slider does not exist.', TB_GETTEXT_DOMAIN_FRONT ),
		'no_slider_selected' 		=> __( 'Oops! You have not selected a slider in your layout.', TB_GETTEXT_DOMAIN_FRONT ),
		'no_video'					=> __( 'The video url could not retrieve a video.', TB_GETTEXT_DOMAIN_FRONT ),
		'read_more'					=> __( 'Read More', TB_GETTEXT_DOMAIN_FRONT ),
		'reply'						=> __( 'Reply', TB_GETTEXT_DOMAIN_FRONT ),
		'search'					=> __( 'Search the site...', TB_GETTEXT_DOMAIN_FRONT ),
		'search_no_results'			=> __( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', TB_GETTEXT_DOMAIN_FRONT ),
		'tag'						=> __( 'Tag', TB_GETTEXT_DOMAIN_FRONT ),
		'title_reply'				=> __( 'Leave a Reply', TB_GETTEXT_DOMAIN_FRONT ),
		'title_reply_to'			=> __( 'Leave a Reply to %s', TB_GETTEXT_DOMAIN_FRONT ),
		'website'					=> __( 'Website', TB_GETTEXT_DOMAIN_FRONT )
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