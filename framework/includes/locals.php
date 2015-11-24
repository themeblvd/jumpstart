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
		'404'						=> __('Apologies, but the page you\'re looking for can\'t be found.', 'themeblvd'),
		'404_title'					=> __('404 Error', 'themeblvd'),
		'all'						=> __('All', 'themeblvd'),
		'archive_no_posts'			=> __('Apologies, but there are no posts to display.', 'themeblvd'),
		'archive'					=> __('Archive', 'themeblvd'),
		'aside'						=> __('Aside', 'themeblvd'),
		'audio'						=> __('Audio', 'themeblvd'),
		'by'						=> __('by', 'themeblvd'),
		'cancel_reply_link'			=> __('Cancel reply', 'themeblvd'),
		'cart'						=> __('Shopping Cart', 'themeblvd'),
		'categories'				=> __('Categories', 'themeblvd'),
		'category'					=> __('Category', 'themeblvd'),
		'chat'						=> __('Chat', 'themeblvd'),
		'close'						=> __('Close', 'themeblvd'),
		'comment_navigation'		=> __('Comment navigation', 'themeblvd'),
		'comment'					=> __('Comment', 'themeblvd'),
		'comments'					=> __('Comments', 'themeblvd'),
		'comments_closed'			=> __('Comments are closed.', 'themeblvd'),
		'comments_newer'			=> __('Newer Comments &rarr;', 'themeblvd'),
		'comments_no_password'		=> __('This post is password protected. Enter the password to view any comments.', 'themeblvd'),
		'comments_notes_after'		=> __('You may use these HTML tags and attributes: %s', 'themeblvd'),
		'comments_older'			=> __('&larr; Older Comments', 'themeblvd'),
		'comments_title_single'		=> __('One comment on &ldquo;%2$s&rdquo;', 'themeblvd'),
		'comments_title_multiple'	=> __('%1$s comments on &ldquo;%2$s&rdquo;', 'themeblvd'),
		'contact_facebook'			=> __('Facebook', 'themeblvd'),
		'contact_gplus'				=> __('Google+', 'themeblvd'),
		'contact_linkedin'			=> __('LinkedIn', 'themeblvd'),
		'contact_pinterest'			=> __('Pinterest', 'themeblvd'),
		'contact_twitter'			=> __('Twitter', 'themeblvd'),
		'contact_us'				=> __('Contact Us', 'themeblvd'),
		'crumb_404'					=> __('Error 404', 'themeblvd'),
		'crumb_author'				=> __('Articles posted by', 'themeblvd'),
		'crumb_search'				=> __('Search results for', 'themeblvd'),
		'crumb_tag'					=> __('Posts tagged', 'themeblvd'),
		'crumb_tag_products'		=> __('Products tagged', 'themeblvd'),
		'edit_attachment'			=> __('Edit Attachment', 'themeblvd'),
		'edit_page'					=> __('Edit Page', 'themeblvd'),
		'edit_post'					=> __('Edit Post', 'themeblvd'),
		'edit_profile'				=> __('Edit Profile', 'themeblvd'),
		'email'						=> __('Email', 'themeblvd'),
		'enlarge'					=> __('Enlarge Image', 'themeblvd'),
		'gallery'					=> __('Gallery', 'themeblvd'),
		'go_to_link'				=> __('Visit Website', 'themeblvd'),
		'home'						=> __('Home', 'themeblvd'),
		'image'						=> __('Image', 'themeblvd'),
		'in'						=> __('in', 'themeblvd'),
		'invalid_layout'			=> __('Invalid Layout ID: The layout ID currently assigned to this page no longer exists.', 'themeblvd'),
		'label_submit'				=> __('Post Comment', 'themeblvd'),
		'language'					=> __('Set Language', 'themeblvd'),
		'last_30'					=> __('The Last 30 Posts', 'themeblvd'),
		'leave_comment'				=> __('Leave a Comment', 'themeblvd'),
		'lightbox_counter'			=> __('%curr% of %total%', 'themeblvd'),
		'lightbox_error'			=> __('The lightbox media could not be loaded.', 'themeblvd'),
		'link'						=> __('Link', 'themeblvd'),
		'link_to_lightbox'			=> __('Link to lightbox', 'themeblvd'),
		'loading'					=> __('Loading...', 'themeblvd'),
		'login_text'				=> __('Log in to Reply', 'themeblvd'),
		'monthly_archives'			=> __('Monthly Archives', 'themeblvd'),
		'my_account'				=> __('My Account', 'themeblvd'),
		'name'						=> __('Name', 'themeblvd'),
		'no_builder_plugin'			=> __('In order for your custom layout to be displayed, you must be have the %s plugin installed.', 'themeblvd'),
		'page'						=> __('Page', 'themeblvd'),
		'pages'						=> __('Pages', 'themeblvd'),
		'page_num'					=> __('Page %s', 'themeblvd'),
		'play'						=> __('Play Movie', 'themeblvd'),
		'posted_on'					=> __('Posted on', 'themeblvd'),
		'posted_in'					=> __('Posted in', 'themeblvd'),
		'posts_per_category'		=> __('Posts per category', 'themeblvd'),
		'navigation' 				=> __('Navigation', 'themeblvd'),
		'next'						=> __('Next', 'themeblvd'),
		'no_comments'				=> __('No Comments', 'themeblvd'),
		'no_slider' 				=> __('Slider does not exist.', 'themeblvd'),
		'no_slider_selected' 		=> __('Oops! You have not selected a slider in your layout.', 'themeblvd'),
		'no_video'					=> __('The video url could not retrieve a video.', 'themeblvd'),
		'previous'					=> __('Previous', 'themeblvd'),
		'quote'						=> __('Quote', 'themeblvd'),
		'read_more'					=> __('Read More', 'themeblvd'),
		'related_posts'				=> __('Related Posts', 'themeblvd'),
		'reply'						=> __('Reply', 'themeblvd'),
		'search'					=> __('Search the site...', 'themeblvd'),
		'search_2'					=> __('Search', 'themeblvd'),
		'search_no_results'			=> __('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'themeblvd'),
		'search_results'			=> __('%s total search results found for: %s', 'themeblvd'),
		'status'					=> __('Status', 'themeblvd'),
		'tag'						=> __('Tag', 'themeblvd'),
		'tags'						=> __('Tags', 'themeblvd'),
		'title_reply'				=> __('Leave a Reply', 'themeblvd'),
		'title_reply_to'			=> __('Leave a Reply to %s', 'themeblvd'),
		'top'						=> __('Top', 'themeblvd'),
		'via'						=> __('via', 'themeblvd'),
		'video'						=> __('Video', 'themeblvd'),
		'view_post'					=> __('View Post', 'themeblvd'),
		'view_item'					=> __('View Item Details', 'themeblvd'),
		'view_posts_by'				=> __('View all posts by %s', 'themeblvd'),
		'website'					=> __('Website', 'themeblvd')
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

	return esc_html($text);
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
		'custom_buttons'			=> 'true'
	);

	// Mobile menu location

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

	// Gallery integration
	if ( themeblvd_supports( 'display', 'gallery' ) ) {

		$locals['gallery'] = 'true';

		if ( apply_filters('themeblvd_gallery_thumb_frame', false) ) {
			$locals['gallery_thumb_frame'] = 'true';
		} else {
			$locals['gallery_thumb_frame'] = 'false';
		}
	}

	// Superfish for drop down menus
	if ( themeblvd_supports('assets', 'superfish') ) {
		$locals['superfish'] = 'true';
	}

	// Responsive nav menu fixed to the side on mobile
	if ( themeblvd_supports('display', 'responsive') && themeblvd_supports('display', 'mobile_side_menu') ) {

		$locals['mobile_side_menu'] = 'true';
		$locals['mobile_side_menu_icon_color'] = apply_filters('themeblvd_mobile_side_menu_social_media_color', 'light');
		$locals['mobile_menu_viewport_max'] = '992';

		$locals['mobile_menu_location'] = 'right';

		if ( is_rtl() ) {
			$locals['mobile_menu_location'] = 'left';
		}
	}

	// Sticky header
	$locals['sticky'] = 'false';

	if ( themeblvd_config('sticky') ) {
		$locals['sticky'] = apply_filters('themeblvd_sticky_selector', '#branding');
		$locals['sticky_offset'] = apply_filters('themeblvd_sticky_offset', '40');
		$locals['sticky_logo'] = apply_filters('themeblvd_sticky_logo_uri', ''); // Optional override, so theme's header logo isn't used
	}

	// Tab deep linking -- i.e. link to a tab on a page and set it open
	if ( apply_filters( 'themeblvd_tabs_deep_linking', false ) ) {
		$locals['tabs_deep_linking'] = 'true';
	}

	return apply_filters( 'themeblvd_js_locals', $locals );
}
