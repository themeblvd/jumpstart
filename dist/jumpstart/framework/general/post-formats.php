<?php
/**
 * Frontend post format functions.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     Jump_Start
 */

/**
 * Get the FontAwesome icon ID to represent a post format
 *
 * @since 2.5.0
 *
 * @param string $format Post format
 * @param book $force Force icon return, if no post format
 * @return string FontAwesime icon ID
 */
function themeblvd_get_format_icon( $format = '', $force = false ) {

	$icon = '';
	$post_type = get_post_type();

	if ( ! $format && $force ) {

		switch ( $post_type ) {
			case 'page' :
				$format = 'page';		// Standard Pages
				break;
			case 'attachment' :
				$format = 'attachment';	// Media Library
				break;
			case 'portfolio_item' :		// TB Portfolios
			case 'portfolio' :			// General, other portfolio plugins
				$format = 'portfolio';
				break;
			case 'product' : 			// WooCommerce
			case 'download' : 			// EDD
				$format = 'product';
				break;
			default :					// Standard Post
				$format = 'standard';
		}
	}

	$icons = apply_filters('themeblvd_format_icons', array(
		'standard'	=> 'pencil',		// Alt: thumb-tack
		'audio'		=> 'volume-up',		// Alt: music
		'aside'		=> 'thumb-tack', 	// Alt: file-text
		'attachment'=> 'picture-o',
		'chat'		=> 'comments',
		'gallery'	=> 'picture-o',
		'image'		=> 'camera',
		'link'		=> 'link',
		'page'		=> 'file-o',
		'portfolio'	=> 'briefcase',
		'product'	=> 'shopping-basket',
		'quote'		=> 'quote-left',
		'status'	=> 'clock-o',
		'video'		=> 'film'
	));

	if ( ! empty( $icons[$format] ) ) {
		$icon = $icons[$format];
	}

	return apply_filters( 'themeblvd_format_icon', $icon, $format, $force, $post_type );
}

/**
 * Remove the first instance of a [gallery]
 * shortcode from a block of content.
 *
 * This is intended to be a helper function that
 * can be filtered onto the_content() for use in
 * the loop with the "gallery" post format.
 *
 * @since 2.3.0
 *
 * @param string $content Content of post
 * @return string $content Filtered content of post
 */
function themeblvd_content_format_gallery( $content ) {

	// Only continue if this is a "gallery" format post.
	if ( ! has_post_format('gallery') ) {
		return $content;
	}

	$pattern = get_shortcode_regex();

	if ( preg_match( "/$pattern/s", $content, $match ) && 'gallery' == $match[2] ) {
		$content = str_replace( $match[0], '', $content );
	}

	return $content;
}
// USAGE: add_filter( 'the_content', 'themeblvd_content_format_gallery', 7 );

/**
 * Filter out the first URL or HTML link of the
 * content in a "Link" format post.
 *
 * @since 2.3.0
 *
 * @param string $content Content of post
 * @return string $content Filtered content of post
 */
function themeblvd_content_format_link( $content ) {

	// Only continue if this is a "link" format post.
	if ( ! has_post_format('link') ) {
		return $content;
	}

	// Get the URL from the content.
	$url = themeblvd_get_content_url( $content );

	// Remove that URL from the start of content,
	// if that's where it was.
	if ( $url ) {
		$content = str_replace( $url[0], '', $content ); // $url[0] is first line of content
	}

	return $content;

}
// USAGE: add_filter( 'the_content', 'themeblvd_content_format_link', 7 );

/**
 * Extract a URL from first line of passed content, if
 * possible. Checks for a URL on the first line of the
 * content or and <a> tag.
 *
 * @since 2.4.0
 *
 * @param string $content A string which might contain a URL, passed by reference.
 * @return string The found URL.
 */
function themeblvd_get_content_url( $content ) {

	if ( empty( $content ) ) {
		return '';
	}

	$trimmed = trim($content);
	$lines = explode( "\n", $trimmed );
	$line = trim( array_shift($lines) );

	$find_link = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";

  	if ( preg_match("/$find_link/siU", $line, $matches) ) {

  		// First line of content is HTML link
  		return array( $line, $matches[2] );

  	} else if ( stripos( $line, 'http' ) === 0 ) {

  		// First line of content is URL
  		return array( $line, esc_url_raw($line) );

  	}

	return '';
}

/**
 * Filter out the first quote from the
 * content in a "Quote" format post.
 * (Framework does not implement by default)
 *
 * @since 2.5.0
 *
 * @param string $content Content of post
 * @return string $content Filtered content of post
 */
function themeblvd_content_format_quote( $content ) {

	// Only continue if this is a "quote" format post.
	if ( ! has_post_format('quote') ) {
		return $content;
	}

	// Get the URL from the content.
	$quote = themeblvd_get_content_quote( $content, false );

	// Remove that URL from the start of content,
	// if that's where it was.
	if ( $quote ) {
		$content = str_replace( $quote, '', $content );
	}

	return $content;

}
// USAGE: add_filter( 'the_content', 'themeblvd_content_format_quote', 7 );

/**
 * Extract a quote from first line of passed content, if
 * possible. Checks for a <blockquote> on the first line of the
 * content or [blockquote] shortcode.
 *
 * @since 2.5.0
 *
 * @param string $content A string which might contain a URL, passed by reference.
 * @return string The found URL.
 */
function themeblvd_get_content_quote( $content, $run = true ) {

	if ( empty( $content ) ) {
		return '';
	}

	$trimmed = trim($content);
	$lines = explode( "\n", $trimmed );
	$line = trim( array_shift($lines) );

	// [blockquote]
	if ( strpos($line, '[blockquote' ) === 0) {
		if ( $run ) {
			return do_shortcode($line);
		} else {
			return $line;
		}
	}

	// <blockquote>
	if ( strpos($trimmed, '<blockquote') === 0 ) {
		$end = strpos($trimmed, '</blockquote>') + 13;
		return substr( $trimmed, 0, $end );
	}

	return '';
}

/**
 * Display first quote from current post's content in the loop.
 *
 * @since 2.5.0
 *
 * @param string $content A string which might contain a URL, passed by reference.
 * @return string The found URL.
 */
function themeblvd_content_quote() {

	// Only continue if this is a "quote" format post.
	if ( ! has_post_format('quote') ) {
		return;
	}

	echo themeblvd_get_content_quote( get_the_content() );
}

/**
 * Filter out the first video from the
 * content in a "Video" format post.
 *
 * @since 2.5.0
 *
 * @param string $content Content of post
 * @return string $content Filtered content of post
 */
function themeblvd_content_format_video( $content ) {

	// Only continue if this is a "link" format post.
	if ( ! has_post_format('video') ) {
		return $content;
	}

	// Get the URL from the content.
	$video = themeblvd_get_content_video( $content, false );

	// Remove that URL from the start of content,
	// if that's where it was.
	if ( $video ) {
		$content = str_replace( $video, '', $content );
	}

	return $content;

}
// USAGE: add_filter( 'the_content', 'themeblvd_content_format_video', 7 );

/**
 * Extract a video from first line of passed content, if
 * possible. Checks for a <blockquote> on the first line of the
 * content or the first encountered href attribute.
 *
 * @since 2.5.0
 *
 * @param string $content A string which might contain a URL, passed by reference.
 * @return string The found URL.
 */
function themeblvd_get_content_video( $content, $run = true ) {

	if ( empty( $content ) ) {
		return '';
	}

	$trimmed = trim($content);
	$lines = explode( "\n", $trimmed );
	$line = trim( array_shift($lines) );

	// Video oembed get
	if ( strpos($line, 'http' ) === 0) {
		if ( $run ) {
			return wp_oembed_get($line);
		} else {
			return $line;
		}
	}

	// [video]
	if ( strpos($trimmed, '[video') === 0 ) {

		$end = strpos($trimmed, '[/video]') + 8;
		$video = substr( $trimmed, 0, $end );

		if ( $run ) {
			$video = do_shortcode($video);
		}

		return $video;
	}

	return '';
}

/**
 * Display first video from current post's content in the loop.
 *
 * @since 2.5.0
 *
 * @param string $content A string which might contain a URL, passed by reference.
 * @return string The found URL.
 */
function themeblvd_content_video( $placeholder = false ) {

	// Only continue if this is a "video" format post.
	if ( ! has_post_format('video') ) {
		return;
	}

	$video = themeblvd_get_content_video( get_the_content() );

	if ( $video && apply_filters('themeblvd_featured_thumb_frame', false) ) {
		$video = sprintf('<div class="thumbnail">%s</div>', $video);
	} else if ( ! $video && $placeholder ) {
		$video = themeblvd_get_media_placeholder();
	}

	echo $video;
}

/**
 * Filter out the first audio from the
 * content in a "Audio" format post.
 *
 * @since 2.5.0
 *
 * @param string $content Content of post
 * @return string $content Filtered content of post
 */
function themeblvd_content_format_audio( $content ) {

	// Only continue if this is a "link" format post.
	if ( ! has_post_format('audio') ) {
		return $content;
	}

	// Get the URL from the content.
	$audio = themeblvd_get_content_audio( $content, false );

	// Remove that URL from the start of content,
	// if that's where it was.
	if ( $audio ) {
		$content = str_replace( $audio, '', $content );
	}

	return $content;

}
// USAGE: add_filter( 'the_content', 'themeblvd_content_format_audio', 7 );

/**
 * Extract a audio from first line of passed content, if
 * possible. Checks for a <blockquote> on the first line of the
 * content or the first encountered href attribute.
 *
 * @since 2.5.0
 *
 * @param string $content A string which might contain a URL, passed by reference.
 * @return string The found URL.
 */
function themeblvd_get_content_audio( $content, $run = true ) {

	if ( empty( $content ) ) {
		return '';
	}

	$trimmed = trim($content);
	$lines = explode( "\n", $trimmed );
	$line = trim( array_shift($lines) );

	// Audio oembed get
	if ( strpos($line, 'http' ) === 0) {
		if ( $run ) {
			return wp_oembed_get($line);
		} else {
			return $line;
		}
	}

	// [audio]
	if ( strpos($trimmed, '[audio') === 0 ) {

		$end = strpos($trimmed, '[/audio]') + 8;
		$audio = substr( $trimmed, 0, $end );

		if ( $run ) {
			$audio = do_shortcode($audio);
		}

		return $audio;
	}

	return '';
}

/**
 * Display first audio from current post's content in the loop.
 *
 * @since 2.5.0
 *
 * @param string $content A string which might contain a URL, passed by reference.
 * @return string The found URL.
 */
function themeblvd_content_audio( $placeholder = false ) {

	// Only continue if this is a "audio" format post.
	if ( ! has_post_format('audio') ) {
		return;
	}

	$audio = themeblvd_get_content_audio( get_the_content(), false );
	$img = themeblvd_get_post_thumbnail( themeblvd_get_att('crop'), array('link' => false, 'placeholder' => false) );

	if ( ! $img && $placeholder && strpos($audio, '[audio' ) !== false ) {
		$img = themeblvd_get_media_placeholder();
	}

	if ( strpos($audio, 'http' ) === 0) {

		$audio = wp_oembed_get($audio);

		if ( apply_filters('themeblvd_featured_thumb_frame', false) ) {
			$audio = sprintf('<div class="thumbnail">%s</div>', $audio);
		}

	} else {
		$audio = do_shortcode($audio);
	}

	if ( $img ) {
		printf('<div class="tb-audio-image">%s<div class="audio-wrap">%s</div></div>', $img, $audio );
	}else {
		echo $audio;
	}
}
