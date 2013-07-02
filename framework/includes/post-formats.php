<?php
/**
 * Filter out the first URL or HTML link of the
 * content in a "Link" format post.
 *
 * @since 2.3.0
 *
 * @param string $content Content of post
 * @return string $content Filtered content of post
 */
if ( ! function_exists( 'themeblvd_content_format_link' ) ) {
	function themeblvd_content_format_link( $content ){

		// Only continue if this is a "link" format post.
		if ( ! has_post_format( 'link' ) )
			return $content;

		// Removes link from content, but not actually
		// using returned $url here.
		$url = get_content_url( $content, true );

		return $content;

	}
}
// add_filter( 'the_content', 'themeblvd_content_format_link', 7 );

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
if ( ! function_exists( 'themeblvd_content_format_gallery' ) ) {
	function themeblvd_content_format_gallery( $content ){

		// Only continue if this is a "gallery" format post.
		if ( ! has_post_format( 'gallery' ) )
			return $content;

		$pattern = get_shortcode_regex();

		if ( preg_match( "/$pattern/s", $content, $match ) && 'gallery' == $match[2] )
			$content = str_replace( $match[0], '', $content );

		return $content;
	}
}
// add_filter( 'the_content', 'themeblvd_content_format_gallery', 7 );

/**
 * Copied from WP 3.6 core for backwards compat. This
 * function only gets implemented if using WP version
 * prior to 3.6.
 *
 * @since 2.3.0
 *
 * =======
 * Extract a URL from passed content, if possible
 * Checks for a URL on the first line of the content or
 * the first encountered href attribute.
 *
 * @since 3.6.0
 *
 * @param string $content A string which might contain a URL, passed by reference.
 * @param boolean $remove Whether to remove the found URL from the passed content.
 * @return string The found URL.
 */
if ( ! function_exists( 'get_content_url' ) ) {
	function get_content_url( &$content, $remove = false ) {
		if ( empty( $content ) )
			return '';

		// the content is a URL
		$trimmed = trim( $content );
		if ( 0 === stripos( $trimmed, 'http' ) && ! preg_match( '#\s#', $trimmed ) ) {
			if ( $remove )
				$content = '';

			return $trimmed;
		// the content is HTML so we grab the first href
		} elseif ( preg_match( '/<a\s[^>]*?href=([\'"])(.+?)\1/is', $content, $matches ) ) {
			return esc_url_raw( $matches[2] );
		}

		$lines = explode( "\n", $trimmed );
		$line = trim( array_shift( $lines ) );

		// the content is a URL followed by content
		if ( 0 === stripos( $line, 'http' ) ) {
			if ( $remove )
				$content = trim( join( "\n", $lines ) );

			return esc_url_raw( $line );
		}

		return '';
	}
}