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
		'all'						=> __( 'All', 'themeblvd_frontend' ),
		'archive_no_posts'			=> __( 'Apologies, but there are no posts to display.', 'themeblvd_frontend' ),
		'archive'					=> __( 'Archive', 'themeblvd_frontend' ),
		'aside'						=> __( 'Aside', 'themeblvd_frontend' ),
		'audio'						=> __( 'Audio', 'themeblvd_frontend' ),
		'by'						=> __( 'by', 'themeblvd_frontend' ),
		'cancel_reply_link'			=> __( 'Cancel reply', 'themeblvd_frontend' ),
		'categories'				=> __( 'Categories', 'themeblvd_frontend' ),
		'category'					=> __( 'Category', 'themeblvd_frontend' ),
		'chat'						=> __( 'Chat', 'themeblvd_frontend' ),
		'close'						=> __( 'Close', 'themeblvd_frontend' ),
		'comment_navigation'		=> __( 'Comment navigation', 'themeblvd_frontend' ),
		'comment'					=> __( 'Comment', 'themeblvd_frontend' ),
		'comments'					=> __( 'Comments', 'themeblvd_frontend' ),
		'comments_closed'			=> __( 'Comments are closed.', 'themeblvd_frontend' ),
		'comments_newer'			=> __( 'Newer Comments &rarr;', 'themeblvd_frontend' ),
		'comments_no_password'		=> __( 'This post is password protected. Enter the password to view any comments.', 'themeblvd_frontend' ),
		'comments_notes_after'		=> __( 'You may use these HTML tags and attributes: %s', 'themeblvd_frontend' ),
		'comments_older'			=> __( '&larr; Older Comments', 'themeblvd_frontend' ),
		'comments_title_single'		=> __( 'One comment on &ldquo;%2$s&rdquo;', 'themeblvd_frontend' ),
		'comments_title_multiple'	=> __( '%1$s comments on &ldquo;%2$s&rdquo;', 'themeblvd_frontend' ),
		'contact_facebook'			=> __( 'Facebook', 'themeblvd_frontend' ),
		'contact_gplus'				=> __( 'Google+', 'themeblvd_frontend' ),
		'contact_linkedin'			=> __( 'LinkedIn', 'themeblvd_frontend' ),
		'contact_pinterest'			=> __( 'Pinterest', 'themeblvd_frontend' ),
		'contact_twitter'			=> __( 'Twitter', 'themeblvd_frontend' ),
		'contact_us'				=> __( 'Contact Us', 'themeblvd_frontend' ),
		'crumb_404'					=> __( 'Error 404', 'themeblvd_frontend' ),
		'crumb_author'				=> __( 'Articles posted by', 'themeblvd_frontend' ),
		'crumb_search'				=> __( 'Search results for', 'themeblvd_frontend' ),
		'crumb_tag'					=> __( 'Posts tagged', 'themeblvd_frontend' ),
		'edit_page'					=> __( 'Edit Page', 'themeblvd_frontend' ),
		'edit_post'					=> __( 'Edit Post', 'themeblvd_frontend' ),
		'edit_profile'				=> __( 'Edit Profile', 'themeblvd_frontend' ),
		'email'						=> __( 'Email', 'themeblvd_frontend' ),
		'enlarge'					=> __( 'Enlarge Image', 'themeblvd_frontend' ),
		'gallery'					=> __( 'Gallery', 'themeblvd_frontend' ),
		'go_to_link'				=> __( 'Visit Website', 'themeblvd_frontend' ),
		'home'						=> __( 'Home', 'themeblvd_frontend' ),
		'image'						=> __( 'Image', 'themeblvd_frontend' ),
		'in'						=> __( 'in', 'themeblvd_frontend' ),
		'invalid_layout'			=> __( 'Invalid Layout ID: The layout ID currently assigned to this page no longer exists.', 'themeblvd_frontend' ),
		'label_submit'				=> __( 'Post Comment', 'themeblvd_frontend' ),
		'last_30'					=> __( 'The Last 30 Posts', 'themeblvd_frontend' ),
		'leave_comment'				=> __( 'Leave a Comment', 'themeblvd_frontend' ),
		'lightbox_counter'			=> __( '%curr% of %total%', 'themeblvd_frontend' ),
		'lightbox_error'			=> __( 'The lightbox media could not be loaded.', 'themeblvd_frontend' ),
		'link'						=> __( 'Link', 'themeblvd_frontend' ),
		'link_to_lightbox'			=> __( 'Link to lightbox', 'themeblvd_frontend' ),
		'loading'					=> __( 'Loading...', 'themeblvd_frontend' ),
		'login_text'				=> __( 'Log in to Reply', 'themeblvd_frontend' ),
		'monthly_archives'			=> __( 'Monthly Archives', 'themeblvd_frontend' ),
		'my_account'				=> __( 'My Account', 'themeblvd_frontend' ),
		'name'						=> __( 'Name', 'themeblvd_frontend' ),
		'no_builder_plugin'			=> __( 'In order for your custom layout to be displayed, you must be have the %s plugin installed.', 'themeblvd_frontend' ),
		'page'						=> __( 'Page', 'themeblvd_frontend' ),
		'pages'						=> __( 'Pages', 'themeblvd_frontend' ),
		'page_num'					=> __( 'Page %s', 'themeblvd_frontend' ),
		'play'						=> __( 'Play Movie', 'themeblvd_frontend' ),
		'posted_on'					=> __( 'Posted on', 'themeblvd_frontend' ),
		'posted_in'					=> __( 'Posted in', 'themeblvd_frontend' ),
		'posts_per_category'		=> __( 'Posts per category', 'themeblvd_frontend' ),
		'navigation' 				=> __( 'Navigation', 'themeblvd_frontend' ),
		'next'						=> __( 'Next', 'themeblvd_frontend' ),
		'no_comments'				=> __( 'No Comments', 'themeblvd_frontend' ),
		'no_slider' 				=> __( 'Slider does not exist.', 'themeblvd_frontend' ),
		'no_slider_selected' 		=> __( 'Oops! You have not selected a slider in your layout.', 'themeblvd_frontend' ),
		'no_video'					=> __( 'The video url could not retrieve a video.', 'themeblvd_frontend' ),
		'previous'					=> __( 'Previous', 'themeblvd_frontend' ),
		'quote'						=> __( 'Quote', 'themeblvd_frontend' ),
		'read_more'					=> __( 'Read More', 'themeblvd_frontend' ),
		'related_posts'				=> __( 'Related Posts', 'themeblvd_frontend' ),
		'reply'						=> __( 'Reply', 'themeblvd_frontend' ),
		'search'					=> __( 'Search the site...', 'themeblvd_frontend' ),
		'search_no_results'			=> __( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'themeblvd_frontend' ),
		'search_results'			=> __( '%s total search results found for: %s', 'themeblvd_frontend' ),
		'status'					=> __( 'Status', 'themeblvd_frontend' ),
		'tag'						=> __( 'Tag', 'themeblvd_frontend' ),
		'tags'						=> __( 'Tags', 'themeblvd_frontend' ),
		'title_reply'				=> __( 'Leave a Reply', 'themeblvd_frontend' ),
		'title_reply_to'			=> __( 'Leave a Reply to %s', 'themeblvd_frontend' ),
		'via'						=> __( 'via', 'themeblvd_frontend' ),
		'video'						=> __( 'Video', 'themeblvd_frontend' ),
		'view_post'					=> __( 'View Post', 'themeblvd_frontend' ),
		'view_item'					=> __( 'View Item Details', 'themeblvd_frontend' ),
		'view_posts_by'				=> __( 'View all posts by %s', 'themeblvd_frontend' ),
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
function themeblvd_get_local( $id ) {

	$text = null;
	$locals = themeblvd_get_all_locals();

	// Set text string
	if ( isset( $locals[$id] ) ) {
		$text = $locals[$id];
	}

	return $text;
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
		'scroll_to_top'				=> 'true',
		'thumb_animations'			=> 'true',
		'retina_logo'				=> 'true',
		'custom_buttons'			=> 'true',
		'mobile_menu_viewport_max'	=> '767'
	);

	// Extend $locals to accomodate scripts being included
	// through our "themeblvd_global_config" filter.
	// This allows people to remove jQuery plugin files
	// w/out having to also remove functions from themeblvd.js.
	if ( themeblvd_supports( 'assets', 'bootstrap' ) ) {
		$locals['bootstrap'] = 'true';
	}

	// Magnific Popup Lightbox integration
	if ( themeblvd_supports( 'assets', 'magnific_popup' ) ) {

		$locals['magnific_popup'] = 'true';

		// Magnific Popup animation
		$locals['lightbox_animation'] = themeblvd_get_option( 'lightbox_animation' );

		// Disable standard lightbox on mobile?
		if ( 'yes' == themeblvd_get_option( 'lightbox_mobile' ) ) {
			$locals['lightbox_mobile'] = '768';
		} else {
			$locals['lightbox_mobile'] = '0';
		}

		// Disable iframe lightboxes (i.e. video, google maps) on mobile?
		if ( 'yes' == themeblvd_get_option( 'lightbox_mobile_iframe' ) ) {
			$locals['lightbox_mobile_iframe'] = '768';
		} else {
			$locals['lightbox_mobile_iframe'] = '0';
		}

		// Disable gallery lightboxes on mobile?
		if ( 'yes' == themeblvd_get_option( 'lightbox_mobile_gallery' ) ) {
			$locals['lightbox_mobile_gallery'] = '768';
		} else {
			$locals['lightbox_mobile_gallery'] = '0';
		}

		// Text strings
		$locals['lightbox_error'] = themeblvd_get_local('lightbox_error');
		$locals['lightbox_close'] = themeblvd_get_local('close');
		$locals['lightbox_loading'] = themeblvd_get_local('loading');
		$locals['lightbox_counter'] = themeblvd_get_local('lightbox_counter');
		$locals['lightbox_next'] = themeblvd_get_local('next');
		$locals['lightbox_previous'] = themeblvd_get_local('previous');

	}

	// Superfish for drop down menus
	if ( themeblvd_supports('assets', 'superfish') ) {
		$locals['superfish'] = 'true';
	}

	// Responsive nav menu fixed to the side on mobile
	if ( themeblvd_supports('display', 'responsive') && themeblvd_supports('display', 'mobile_side_menu') ) {
		$locals['mobile_side_menu'] = 'true';
		$locals['mobile_side_menu_icon_color'] = apply_filters('themeblvd_mobile_side_menu_social_media_color', 'light');
	}

	// Sticky header
	$locals['sticky'] = 'false';

	if ( themeblvd_config('sticky') ) {
		$locals['sticky'] = apply_filters('themeblvd_sticky_selector', '#branding');
		$locals['sticky_offset'] = apply_filters('themeblvd_sticky_offset', '40');
	}

	// Tab deep linking -- i.e. link to a tab on a page and set it open
	if ( apply_filters( 'themeblvd_tabs_deep_linking', false ) ) {
		$locals['tabs_deep_linking'] = 'true';
	}

	return apply_filters( 'themeblvd_js_locals', $locals );
}