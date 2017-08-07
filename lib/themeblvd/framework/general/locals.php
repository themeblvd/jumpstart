<?php
/**
 * Frontend text strings, requiring localization.
 *
 * @author		Jason Bobich
 * @copyright	2009-2017 Theme Blvd
 * @link		http://themeblvd.com
 * @package 	@@name-package
 */

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
		'404'						=> __('Apologies, but the page you\'re looking for can\'t be found.', '@@text-domain'),
		'404_title'					=> __('404 Error', '@@text-domain'),
		'all'						=> __('All', '@@text-domain'),
		'archive_no_posts'			=> __('Apologies, but there are no posts to display.', '@@text-domain'),
		'archive'					=> __('Archive', '@@text-domain'),
		'aside'						=> __('Aside', '@@text-domain'),
		'audio'						=> __('Audio', '@@text-domain'),
		'by'						=> __('by', '@@text-domain'),
		'cancel_reply_link'			=> __('Cancel reply', '@@text-domain'),
		'cart'						=> __('Shopping Cart', '@@text-domain'),
		'categories'				=> __('Categories', '@@text-domain'),
		'category'					=> __('Category', '@@text-domain'),
		'chat'						=> __('Chat', '@@text-domain'),
		'close'						=> __('Close', '@@text-domain'),
		'comment_navigation'		=> __('Comment navigation', '@@text-domain'),
		'comment'					=> __('Comment', '@@text-domain'),
		'comments'					=> __('Comments', '@@text-domain'),
		'comments_closed'			=> __('Comments are closed.', '@@text-domain'),
		'comments_newer'			=> __('Newer Comments &rarr;', '@@text-domain'),
		'comments_no_password'		=> __('This post is password protected. Enter the password to view any comments.', '@@text-domain'),
		'comments_notes_after'		=> __('You may use these HTML tags and attributes: %s', '@@text-domain'),
		'comments_older'			=> __('&larr; Older Comments', '@@text-domain'),
		'comments_title_single'		=> __('One comment on &ldquo;%2$s&rdquo;', '@@text-domain'),
		'comments_title_multiple'	=> __('%1$s comments on &ldquo;%2$s&rdquo;', '@@text-domain'),
		'contact_facebook'			=> __('Facebook', '@@text-domain'),
		'contact_gplus'				=> __('Google+', '@@text-domain'),
		'contact_linkedin'			=> __('LinkedIn', '@@text-domain'),
		'contact_pinterest'			=> __('Pinterest', '@@text-domain'),
		'contact_twitter'			=> __('Twitter', '@@text-domain'),
		'contact_us'				=> __('Contact Us', '@@text-domain'),
		'crumb_404'					=> __('Error 404', '@@text-domain'),
		'crumb_author'				=> __('Articles posted by', '@@text-domain'),
		'crumb_search'				=> __('Search results for', '@@text-domain'),
		'crumb_tag'					=> __('Posts tagged', '@@text-domain'),
		'crumb_tag_products'		=> __('Products tagged', '@@text-domain'),
		'edit_attachment'			=> __('Edit Attachment', '@@text-domain'),
		'edit_page'					=> __('Edit Page', '@@text-domain'),
		'edit_post'					=> __('Edit Post', '@@text-domain'),
		'edit_profile'				=> __('Edit Profile', '@@text-domain'),
		'email'						=> __('Email', '@@text-domain'),
		'enlarge'					=> __('Enlarge Image', '@@text-domain'),
		'gallery'					=> __('Gallery', '@@text-domain'),
		'go_to_link'				=> __('Visit Website', '@@text-domain'),
		'home'						=> __('Home', '@@text-domain'),
		'image'						=> __('Image', '@@text-domain'),
		'in'						=> __('in', '@@text-domain'),
		'invalid_layout'			=> __('Invalid Layout ID: The layout ID currently assigned to this page no longer exists.', '@@text-domain'),
		'label_submit'				=> __('Post Comment', '@@text-domain'),
		'language'					=> __('Set Language', '@@text-domain'),
		'last_30'					=> __('The Last 30 Posts', '@@text-domain'),
		'leave_comment'				=> __('Leave a Comment', '@@text-domain'),
		'lightbox_counter'			=> __('%curr% of %total%', '@@text-domain'),
		'lightbox_error'			=> __('The lightbox media could not be loaded.', '@@text-domain'),
		'link'						=> __('Link', '@@text-domain'),
		'link_to_lightbox'			=> __('Link to lightbox', '@@text-domain'),
		'loading'					=> __('Loading...', '@@text-domain'),
		'login_text'				=> __('Log in to Reply', '@@text-domain'),
		'monthly_archives'			=> __('Monthly Archives', '@@text-domain'),
		'my_account'				=> __('My Account', '@@text-domain'),
		'name'						=> __('Name', '@@text-domain'),
		'no_builder_plugin'			=> __('In order for your custom layout to be displayed, you must be have the %s plugin installed.', '@@text-domain'),
		'page'						=> __('Page', '@@text-domain'),
		'pages'						=> __('Pages', '@@text-domain'),
		'page_num'					=> __('Page %s', '@@text-domain'),
		'play'						=> __('Play Movie', '@@text-domain'),
		'posted_on'					=> __('Posted on', '@@text-domain'),
		'posted_in'					=> __('Posted in', '@@text-domain'),
		'posts_per_category'		=> __('Posts per category', '@@text-domain'),
		'navigation' 				=> __('Navigation', '@@text-domain'),
		'next'						=> __('Next', '@@text-domain'),
		'no_comments'				=> __('No Comments', '@@text-domain'),
		'no_slider' 				=> __('Slider does not exist.', '@@text-domain'),
		'no_slider_selected' 		=> __('Oops! You have not selected a slider in your layout.', '@@text-domain'),
		'no_video'					=> __('The video url could not retrieve a video.', '@@text-domain'),
		'previous'					=> __('Previous', '@@text-domain'),
		'quote'						=> __('Quote', '@@text-domain'),
		'read_more'					=> __('Read More', '@@text-domain'),
		'related_posts'				=> __('Related Posts', '@@text-domain'),
		'reply'						=> __('Reply', '@@text-domain'),
		'search'					=> __('Search the site...', '@@text-domain'),
		'search_2'					=> __('Search', '@@text-domain'),
		'search_no_results'			=> __('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', '@@text-domain'),
		'search_results'			=> __('%s total search results found for: %s', '@@text-domain'),
		'status'					=> __('Status', '@@text-domain'),
		'tag'						=> __('Tag', '@@text-domain'),
		'tags'						=> __('Tags', '@@text-domain'),
		'title_reply'				=> __('Leave a Reply', '@@text-domain'),
		'title_reply_to'			=> __('Leave a Reply to %s', '@@text-domain'),
		'top'						=> __('Top', '@@text-domain'),
		'via'						=> __('via', '@@text-domain'),
		'video'						=> __('Video', '@@text-domain'),
		'view_post'					=> __('View Post', '@@text-domain'),
		'view_item'					=> __('View Item Details', '@@text-domain'),
		'view_posts_by'				=> __('View all posts by %s', '@@text-domain'),
		'website'					=> __('Website', '@@text-domain')
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
