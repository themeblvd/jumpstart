<?php
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
	if ( ! has_post_format( 'gallery' ) ) {
		return $content;
	}

	$pattern = get_shortcode_regex();

	if ( preg_match( "/$pattern/s", $content, $match ) && 'gallery' == $match[2] ) {
		$content = str_replace( $match[0], '', $content );
	}

	return $content;
}
// add_filter( 'the_content', 'themeblvd_content_format_gallery', 7 );

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
// add_filter( 'the_content', 'themeblvd_content_format_link', 7 );

/**
 * Extract a URL from passed content, if possible
 * Checks for a URL on the first line of the content or
 * the first encountered href attribute.
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
 * Get the FontAwesome icon ID to represent a post format
 *
 * @since 2.5.0
 *
 * @param string $format Post format
 * @return string FontAwesime icon ID
 */
function themeblvd_get_format_icon( $format ) {

	$icons = apply_filters('themeblvd_format_icons', array(
		'standard'	=> '',
		'aside'		=> 'thumb-tack',
		'chat'		=> 'comments',
		'gallery'	=> 'picture-o',
		'image'		=> 'camera',
		'link'		=> 'link',
		'quote'		=> 'quote-left',
		'status'	=> 'clock',
		'video'		=> 'film'
	));

	if ( ! empty( $icons[$format] ) ) {
		return $icons[$format];
	}

	return '';
}