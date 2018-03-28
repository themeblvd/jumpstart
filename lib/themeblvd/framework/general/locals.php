<?php
/**
 * Frontend Text Strings
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.1.0
 */

/**
 * Setup frontend text strings.
 *
 * This function allows us to have all of the
 * framework's common localized text strings
 * in once place.
 *
 * @since @@name-framework 2.1.0
 */
function themeblvd_get_all_locals() {

	$locals = array(
		'404'                       => __( 'Apologies, but the page you\'re looking for can\'t be found.', '@@text-domain' ),
		'404_title'                 => __( '404 Error', '@@text-domain' ),
		'all'                       => __( 'All', '@@text-domain' ),
		'archive_no_posts'          => __( 'Apologies, but there are no posts to display.', '@@text-domain' ),
		'archive'                   => __( 'Archive', '@@text-domain' ),
		'aside'                     => __( 'Aside', '@@text-domain' ),
		'audio'                     => __( 'Audio', '@@text-domain' ),
		'by'                        => __( 'by', '@@text-domain' ),
		'cancel_reply_link'         => __( 'Cancel reply', '@@text-domain' ),
		'cart'                      => __( 'Shopping Cart', '@@text-domain' ),
		'categories'                => __( 'Categories', '@@text-domain' ),
		'category'                  => __( 'Category', '@@text-domain' ),
		'chat'                      => __( 'Chat', '@@text-domain' ),
		'close'                     => __( 'Close', '@@text-domain' ),
		'comment_navigation'        => __( 'Comment navigation', '@@text-domain' ),
		'comment'                   => __( 'Comment', '@@text-domain' ),
		'comments'                  => __( 'Comments', '@@text-domain' ),
		'comments_closed'           => __( 'Comments are closed.', '@@text-domain' ),
		'comments_newer'            => __( 'Newer Comments &rarr;', '@@text-domain' ),
		'comments_no_password'      => __( 'This post is password protected. Enter the password to view any comments.', '@@text-domain' ),
		// translators: 1: HTML tags allowed with comments
		'comments_notes_after'      => __( 'You may use these HTML tags and attributes: %s', '@@text-domain' ),
		'comments_older'            => __( '&larr; Older Comments', '@@text-domain' ),
		// translators: 1: post title
		'comments_title_single'     => __( 'One comment on &ldquo;%2$s&rdquo;', '@@text-domain' ),
		// translators: 1: number of comments allowed, 2: post title
		'comments_title_multiple'   => __( '%1$s comments on &ldquo;%2$s&rdquo;', '@@text-domain' ),
		'contact_facebook'          => __( 'Facebook', '@@text-domain' ),
		'contact_gplus'             => __( 'Google+', '@@text-domain' ),
		'contact_linkedin'          => __( 'LinkedIn', '@@text-domain' ),
		'contact_pinterest'         => __( 'Pinterest', '@@text-domain' ),
		'contact_twitter'           => __( 'Twitter', '@@text-domain' ),
		'contact_us'                => __( 'Contact Us', '@@text-domain' ),
		'crumb_404'                 => __( 'Error 404', '@@text-domain' ),
		'crumb_author'              => __( 'Posts by', '@@text-domain' ),
		// translators: 1. search term
		'crumb_search'              => __( 'Search results for "%s"', '@@text-domain' ),
		// translators: 1. post archive tag
		'crumb_tag'                 => __( 'Posts tagged "%s"', '@@text-domain' ),
		// translators: 1. product archive tag
		'crumb_tag_products'        => __( 'Products tagged "%s"', '@@text-domain' ),
		'edit_attachment'           => __( 'Edit Attachment', '@@text-domain' ),
		'edit_page'                 => __( 'Edit Page', '@@text-domain' ),
		'edit_post'                 => __( 'Edit Post', '@@text-domain' ),
		'edit_profile'              => __( 'Edit Profile', '@@text-domain' ),
		'email'                     => __( 'Email', '@@text-domain' ),
		'enlarge'                   => __( 'Enlarge Image', '@@text-domain' ),
		'enter'                     => __( 'Enter', '@@text-domain' ),
		'gallery'                   => __( 'Gallery', '@@text-domain' ),
		'go_to_link'                => __( 'Visit Website', '@@text-domain' ),
		'home'                      => __( 'Home', '@@text-domain' ),
		'image'                     => __( 'Image', '@@text-domain' ),
		'in'                        => __( 'in', '@@text-domain' ),
		'invalid_layout'            => __( 'Invalid Layout ID: The layout ID currently assigned to this page no longer exists.', '@@text-domain' ),
		'label_submit'              => __( 'Post Comment', '@@text-domain' ),
		'language'                  => __( 'Set Language', '@@text-domain' ),
		'last_30'                   => __( 'The Last 30 Posts', '@@text-domain' ),
		'leave_comment'             => __( 'Leave a Comment', '@@text-domain' ),
		// translators: 1. current item number in ligthbox gallery, 2: total number of items in lightbox gallery
		'lightbox_counter'          => __( '%curr% of %total%', '@@text-domain' ),
		'lightbox_error'            => __( 'The lightbox media could not be loaded.', '@@text-domain' ),
		'link'                      => __( 'Link', '@@text-domain' ),
		'link_to_lightbox'          => __( 'Link to lightbox', '@@text-domain' ),
		'loading'                   => __( 'Loading...', '@@text-domain' ),
		'login_text'                => __( 'Log in to Reply', '@@text-domain' ),
		'monthly_archives'          => __( 'Monthly Archives', '@@text-domain' ),
		'my_account'                => __( 'My Account', '@@text-domain' ),
		'password'                  => __( 'Password', '@@text-domain' ),
		'password_enter'            => __( 'This content is password protected. To view it please enter your password below.', '@@text-domain' ),
		'name'                      => __( 'Name', '@@text-domain' ),
		// translators: 1. name of plugin, Theme Blvd Layout Builder
		'no_builder_plugin'         => __( 'In order for your custom layout to be displayed, you must be have the %s plugin installed.', '@@text-domain' ),
		'page'                      => __( 'Page', '@@text-domain' ),
		'pages'                     => __( 'Pages', '@@text-domain' ),
		// translators: current page number
		'page_num'                  => __( 'Page %s', '@@text-domain' ),
		'play'                      => __( 'Play Movie', '@@text-domain' ),
		'posted_on'                 => __( 'Posted on', '@@text-domain' ),
		'posted_in'                 => __( 'Posted in', '@@text-domain' ),
		'posts_per_category'        => __( 'Posts per category', '@@text-domain' ),
		'navigation'                => __( 'Navigation', '@@text-domain' ),
		'next'                      => __( 'Next', '@@text-domain' ),
		'no_comments'               => __( 'No Comments', '@@text-domain' ),
		'no_slider'                 => __( 'Slider does not exist.', '@@text-domain' ),
		'no_slider_selected'        => __( 'Oops! You have not selected a slider in your layout.', '@@text-domain' ),
		'no_video'                  => __( 'The video url could not retrieve a video.', '@@text-domain' ),
		'previous'                  => __( 'Previous', '@@text-domain' ),
		'quote'                     => __( 'Quote', '@@text-domain' ),
		'read_more'                 => __( 'Read More', '@@text-domain' ),
		'related_posts'             => __( 'Related Posts', '@@text-domain' ),
		'reply'                     => __( 'Reply', '@@text-domain' ),
		'search'                    => __( 'Search the site...', '@@text-domain' ),
		'search_2'                  => __( 'Search', '@@text-domain' ),
		'search_no_results'         => __( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', '@@text-domain' ),
		// translators: 1. number of search results found, 2: term that was searched for
		'search_results'            => __( '%1$s total search results found for: %2$s', '@@text-domain' ),
		'status'                    => __( 'Status', '@@text-domain' ),
		'tag'                       => __( 'Tag', '@@text-domain' ),
		'tags'                      => __( 'Tags', '@@text-domain' ),
		'title_reply'               => __( 'Leave a Reply', '@@text-domain' ),
		// translators: 1: comments author's name
		'title_reply_to'            => __( 'Leave a Reply to %s', '@@text-domain' ),
		'top'                       => __( 'Top', '@@text-domain' ),
		'via'                       => __( 'via', '@@text-domain' ),
		'video'                     => __( 'Video', '@@text-domain' ),
		'view_post'                 => __( 'View Post', '@@text-domain' ),
		'view_item'                 => __( 'View Item Details', '@@text-domain' ),
		// translators: 1: author name
		'view_posts_by'             => __( 'View all posts by %s', '@@text-domain' ),
		'website'                   => __( 'Website', '@@text-domain' ),
	);

	/**
	 * Filters all frontend text strings.
	 *
	 * @since @@name-framework 2.0.0
	 *
	 * @param array Frontend text strings.
	 */
	return apply_filters( 'themeblvd_frontend_locals', $locals );

}

/**
 * Get a frontend text string.
 *
 * @since @@name-framework 2.0.0
 *
 * @param  string $id   Key for $locals array.
 * @return string $text Localized and filtered text string.
 */
function themeblvd_get_local( $id ) {

	$text = null;

	$locals = themeblvd_get_all_locals();

	if ( isset( $locals[ $id ] ) ) {

		$text = $locals[ $id ];

	}

	return esc_html( $text );

}

/**
 * Get JavaScript localized strings for
 * themeblvd.js.
 *
 * @since @@name-framework 2.2.0
 */
function themeblvd_get_js_locals() {

	$locals = array(
		'scroll_to_top'  => 'true',
		'custom_buttons' => 'true',
	);

	// Is this mobile? -- The <body> class isn't always reliable.
	if ( wp_is_mobile() ) {

		$locals['mobile'] = 'true';

	} else {

		$locals['mobile'] = 'false';

	}

	/*
	 * Extend $locals to accomodate scripts being included
	 * through our "themeblvd_global_config" filter.
	 *
	 * This allows people to remove jQuery plugin files
	 * w/out having to also remove functions from themeblvd.js.
	 */
	if ( themeblvd_supports( 'assets', 'bootstrap' ) ) {

		$locals['bootstrap'] = 'true';

	}

	// Add Magnific Popup Lightbox integration.
	if ( themeblvd_supports( 'assets', 'magnific_popup' ) ) {

		$locals['magnific_popup'] = 'true';

		// Add Magnific Popup animation.
		$locals['lightbox_animation'] = themeblvd_get_option( 'lightbox_animation' );

		// Disable standard lightbox on mobile?
		if ( 'yes' === themeblvd_get_option( 'lightbox_mobile' ) ) {

			$locals['lightbox_mobile'] = '768';

		} else {

			$locals['lightbox_mobile'] = '0';

		}

		// Disable iframe lightboxes (i.e. video, google maps) on mobile?
		if ( 'yes' === themeblvd_get_option( 'lightbox_mobile_iframe' ) ) {

			$locals['lightbox_mobile_iframe'] = '768';

		} else {

			$locals['lightbox_mobile_iframe'] = '0';

		}

		// Disable gallery lightboxes on mobile?
		if ( 'yes' === themeblvd_get_option( 'lightbox_mobile_gallery' ) ) {

			$locals['lightbox_mobile_gallery'] = '768';

		} else {

			$locals['lightbox_mobile_gallery'] = '0';

		}

		$locals['lightbox_error'] = themeblvd_get_local( 'lightbox_error' );

		$locals['lightbox_close'] = themeblvd_get_local( 'close' );

		$locals['lightbox_loading'] = themeblvd_get_local( 'loading' );

		$locals['lightbox_counter'] = themeblvd_get_local( 'lightbox_counter' );

		$locals['lightbox_next'] = themeblvd_get_local( 'next' );

		$locals['lightbox_previous'] = themeblvd_get_local( 'previous' );

	}

	// Add gallery integration.
	if ( themeblvd_supports( 'display', 'gallery' ) ) {

		$locals['gallery'] = 'true';

	}

	// Superfish for drop down menus.
	if ( themeblvd_supports( 'assets', 'superfish' ) ) {

		$locals['superfish'] = 'true';

	}

	/**
	 * Filters the mobile header breakpoint.
	 *
	 * This will be the viewport size where the mobile
	 * header is displayed and desktop header is hidden.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string|int Viewport size, like `991`.
	 */
	$locals['mobile_header_breakpoint'] = apply_filters( 'themeblvd_mobile_header_breakpoint', '991' );

	// Set up mobile panel.
	if ( themeblvd_supports( 'display', 'mobile_panel' ) ) {

		$locals['mobile_panel'] = 'true';

		$location = 'right';

		if ( is_rtl() ) {

			$location = 'left';

		}

		/** This filter is documented in framework/general/tags.php */
		$locals['mobile_menu_location'] = apply_filters( 'themeblvd_mobile_panel_location', $location );

	}

	if ( themeblvd_do_sticky() ) {

		$locals['sticky'] = 'true';

		/**
		 * Filters the offset for scroll to JavaScript
		 * functionality.
		 *
		 * @since @@name-framework 2.5.0
		 *
		 * @param int|string Number of pixels, like `50`.
		 */
		$offset = apply_filters( 'themeblvd_sticky_offset', 50 );

		if ( $offset ) {

			$locals['sticky_offset'] = $offset;

		}

	}

	if ( themeblvd_do_side_panel() ) {

		$locals['side_panel'] = 'true';

	}

	/**
	 * Filters the offset for scroll to JavaScript
	 * functionality.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string CSS value, like `20px`.
	 */
	$offset = apply_filters( 'themeblvd_scroll_to_section_offset', '' );

	if ( $offset ) {

		$locals['scroll_to_section_offset'] = $offset;

	}

	/**
	 * Filters whether to allow deep linking for tabs.
	 *
	 * Deep linking for tabs means that the user can
	 * link to #tab_{ID OF TAB} and that tab will
	 * automatically open.
	 *
	 * @since @@name-framework 2.4.4
	 *
	 * @param bool Whether deeplinking is enabled.
	 */
	if ( apply_filters( 'themeblvd_tabs_deep_linking', false ) ) {

		$locals['tabs_deep_linking'] = 'true';

	}

	if ( themeblvd_supports( 'assets', 'youtube' ) ) {

		$locals['youtube_api'] = 'true';

	}

	if ( themeblvd_supports( 'assets', 'vimeo' ) ) {

		$locals['vimeo_api'] = 'true';

	}

	/**
	 * Filters localized text strings that get
	 * printed with themeblvd.js.
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @param array $locals Localization text strings.
	 */
	return apply_filters( 'themeblvd_js_locals', $locals );

}
