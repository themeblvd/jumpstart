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
		'404'						=> __('Apologies, but the page you\'re looking for can\'t be found.', 'jumpstart'),
		'404_title'					=> __('404 Error', 'jumpstart'),
		'all'						=> __('All', 'jumpstart'),
		'archive_no_posts'			=> __('Apologies, but there are no posts to display.', 'jumpstart'),
		'archive'					=> __('Archive', 'jumpstart'),
		'aside'						=> __('Aside', 'jumpstart'),
		'audio'						=> __('Audio', 'jumpstart'),
		'by'						=> __('by', 'jumpstart'),
		'cancel_reply_link'			=> __('Cancel reply', 'jumpstart'),
		'cart'						=> __('Shopping Cart', 'jumpstart'),
		'categories'				=> __('Categories', 'jumpstart'),
		'category'					=> __('Category', 'jumpstart'),
		'chat'						=> __('Chat', 'jumpstart'),
		'close'						=> __('Close', 'jumpstart'),
		'comment_navigation'		=> __('Comment navigation', 'jumpstart'),
		'comment'					=> __('Comment', 'jumpstart'),
		'comments'					=> __('Comments', 'jumpstart'),
		'comments_closed'			=> __('Comments are closed.', 'jumpstart'),
		'comments_newer'			=> __('Newer Comments &rarr;', 'jumpstart'),
		'comments_no_password'		=> __('This post is password protected. Enter the password to view any comments.', 'jumpstart'),
		'comments_notes_after'		=> __('You may use these HTML tags and attributes: %s', 'jumpstart'),
		'comments_older'			=> __('&larr; Older Comments', 'jumpstart'),
		'comments_title_single'		=> __('One comment on &ldquo;%2$s&rdquo;', 'jumpstart'),
		'comments_title_multiple'	=> __('%1$s comments on &ldquo;%2$s&rdquo;', 'jumpstart'),
		'contact_facebook'			=> __('Facebook', 'jumpstart'),
		'contact_gplus'				=> __('Google+', 'jumpstart'),
		'contact_linkedin'			=> __('LinkedIn', 'jumpstart'),
		'contact_pinterest'			=> __('Pinterest', 'jumpstart'),
		'contact_twitter'			=> __('Twitter', 'jumpstart'),
		'contact_us'				=> __('Contact Us', 'jumpstart'),
		'crumb_404'					=> __('Error 404', 'jumpstart'),
		'crumb_author'				=> __('Articles posted by', 'jumpstart'),
		'crumb_search'				=> __('Search results for', 'jumpstart'),
		'crumb_tag'					=> __('Posts tagged', 'jumpstart'),
		'crumb_tag_products'		=> __('Products tagged', 'jumpstart'),
		'edit_attachment'			=> __('Edit Attachment', 'jumpstart'),
		'edit_page'					=> __('Edit Page', 'jumpstart'),
		'edit_post'					=> __('Edit Post', 'jumpstart'),
		'edit_profile'				=> __('Edit Profile', 'jumpstart'),
		'email'						=> __('Email', 'jumpstart'),
		'enlarge'					=> __('Enlarge Image', 'jumpstart'),
		'gallery'					=> __('Gallery', 'jumpstart'),
		'go_to_link'				=> __('Visit Website', 'jumpstart'),
		'home'						=> __('Home', 'jumpstart'),
		'image'						=> __('Image', 'jumpstart'),
		'in'						=> __('in', 'jumpstart'),
		'invalid_layout'			=> __('Invalid Layout ID: The layout ID currently assigned to this page no longer exists.', 'jumpstart'),
		'label_submit'				=> __('Post Comment', 'jumpstart'),
		'language'					=> __('Set Language', 'jumpstart'),
		'last_30'					=> __('The Last 30 Posts', 'jumpstart'),
		'leave_comment'				=> __('Leave a Comment', 'jumpstart'),
		'lightbox_counter'			=> __('%curr% of %total%', 'jumpstart'),
		'lightbox_error'			=> __('The lightbox media could not be loaded.', 'jumpstart'),
		'link'						=> __('Link', 'jumpstart'),
		'link_to_lightbox'			=> __('Link to lightbox', 'jumpstart'),
		'loading'					=> __('Loading...', 'jumpstart'),
		'login_text'				=> __('Log in to Reply', 'jumpstart'),
		'monthly_archives'			=> __('Monthly Archives', 'jumpstart'),
		'my_account'				=> __('My Account', 'jumpstart'),
		'name'						=> __('Name', 'jumpstart'),
		'no_builder_plugin'			=> __('In order for your custom layout to be displayed, you must be have the %s plugin installed.', 'jumpstart'),
		'page'						=> __('Page', 'jumpstart'),
		'pages'						=> __('Pages', 'jumpstart'),
		'page_num'					=> __('Page %s', 'jumpstart'),
		'play'						=> __('Play Movie', 'jumpstart'),
		'posted_on'					=> __('Posted on', 'jumpstart'),
		'posted_in'					=> __('Posted in', 'jumpstart'),
		'posts_per_category'		=> __('Posts per category', 'jumpstart'),
		'navigation' 				=> __('Navigation', 'jumpstart'),
		'next'						=> __('Next', 'jumpstart'),
		'no_comments'				=> __('No Comments', 'jumpstart'),
		'no_slider' 				=> __('Slider does not exist.', 'jumpstart'),
		'no_slider_selected' 		=> __('Oops! You have not selected a slider in your layout.', 'jumpstart'),
		'no_video'					=> __('The video url could not retrieve a video.', 'jumpstart'),
		'previous'					=> __('Previous', 'jumpstart'),
		'quote'						=> __('Quote', 'jumpstart'),
		'read_more'					=> __('Read More', 'jumpstart'),
		'related_posts'				=> __('Related Posts', 'jumpstart'),
		'reply'						=> __('Reply', 'jumpstart'),
		'search'					=> __('Search the site...', 'jumpstart'),
		'search_2'					=> __('Search', 'jumpstart'),
		'search_no_results'			=> __('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'jumpstart'),
		'search_results'			=> __('%s total search results found for: %s', 'jumpstart'),
		'status'					=> __('Status', 'jumpstart'),
		'tag'						=> __('Tag', 'jumpstart'),
		'tags'						=> __('Tags', 'jumpstart'),
		'title_reply'				=> __('Leave a Reply', 'jumpstart'),
		'title_reply_to'			=> __('Leave a Reply to %s', 'jumpstart'),
		'top'						=> __('Top', 'jumpstart'),
		'via'						=> __('via', 'jumpstart'),
		'video'						=> __('Video', 'jumpstart'),
		'view_post'					=> __('View Post', 'jumpstart'),
		'view_item'					=> __('View Item Details', 'jumpstart'),
		'view_posts_by'				=> __('View all posts by %s', 'jumpstart'),
		'website'					=> __('Website', 'jumpstart')
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

	$locals = array (
		'scroll_to_top'		=> 'true',
		'custom_buttons'	=> 'true'
	);

	// Is this mobile? -- body class isn't always reliable.
	if ( wp_is_mobile() ) {
		$locals['mobile'] = 'true';
	} else {
		$locals['mobile'] = 'false';
	}

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

		$locals['mobile_panel'] = 'true';
		$locals['mobile_panel_class'] = implode( ' ', apply_filters( 'themeblvd_mobile_panel_class', array() ) );
		$locals['mobile_menu_viewport_max'] = '992';

		$locals['mobile_menu_location'] = 'right';

		if ( is_rtl() ) {
			$locals['mobile_menu_location'] = 'left';
		}
	}

	// Side Panel
	if ( themeblvd_do_side_panel() ) {
		$locals['side_panel'] = 'true';
	}

	// Sticky header
	$locals['sticky'] = 'false';

	if ( themeblvd_config('sticky') && ! wp_is_mobile() && ! themeblvd_is_ie( array('7', '8', '9') ) ) {
		$locals['sticky'] = 'true';
		$locals['sticky_class'] = implode( ' ', apply_filters( 'themeblvd_sticky_class', array() ) );
		$locals['sticky_offset'] = apply_filters('themeblvd_sticky_offset', '48');
		$locals['sticky_logo'] = apply_filters('themeblvd_sticky_logo_uri', ''); // Optional override, so theme's header logo isn't used
	}

	// Custom scroll-to-section offset
	if ( $offset = apply_filters('themeblvd_scroll_to_section_offset', '') ) {
		$locals['scroll_to_section_offset'] = $offset;
	}

	// Tab deep linking -- i.e. link to a tab on a page and set it open
	if ( apply_filters( 'themeblvd_tabs_deep_linking', false ) ) {
		$locals['tabs_deep_linking'] = 'true';
	}

	return apply_filters( 'themeblvd_js_locals', $locals );
}
