<?php
/**
 * Post Formats
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.5.0
 */

/**
 * Get the FontAwesome icon name to represent
 * a post format.
 *
 * When the $force parameter is FALSE, an icon
 * name will only be returned when working with
 * a standard post type with a post format.
 *
 * However, when $force is TRUE, an icon name will
 * also be returned to represent some different
 * post types.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $format Current post format, like `aside`.
 * @param  bool   $force  Whether to return icon, when no post format.
 * @return string         Icon name, like `pencil`.
 */
function themeblvd_get_format_icon( $format = '', $force = false ) {

	$icon = '';

	$post_type = get_post_type();

	/*
	 * Set icon name based on post type.
	 *
	 * If there's no post format and $force is
	 * TRUE, we'll add an icon name to represent
	 * different post types the theme uses.
	 */
	if ( ! $format && $force ) {

		switch ( $post_type ) {

			// Standard Pages
			case 'page':
				$format = 'page';
				break;

			// Media Attachment
			case 'attachment':
				$format = 'attachment';
				break;

			// Portfolio Items
			case 'portfolio_item':
			case 'portfolio':
				$format = 'portfolio';
				break;

			// Products (from WooCommerce or EDD)
			case 'product':
			case 'download':
				$format = 'product';
				break;

			default:
				$format = 'standard';

		}
	}

	$icons = array(
		'standard'   => 'pencil-alt',      // Logical alternate could be `thumbtack`.
		'audio'      => 'volume-up',       // Logical alternate could be `music` or `headphones`.
		'aside'      => 'thumbtack',       // Logical alternate could be `file-alt`.
		'attachment' => 'image',
		'chat'       => 'comments',
		'gallery'    => 'images',
		'image'      => 'camera-retro',    // Logical alternate could be `camera`.
		'link'       => 'link',
		'page'       => 'file',
		'portfolio'  => 'briefcase',
		'product'    => 'shopping-basket', // Logical alternate could be `shopping-cart` or `shopping-bag`.
		'quote'      => 'quote-left',
		'status'     => 'clock',
		'video'      => 'film',
	);

	if ( is_rtl() ) {

		$icons['quote'] = 'quote-right';

	}

	/**
	 * Filters the icons used from FontAwesome to
	 * represent each post type and post format.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param array Icons used for post formats and post types.
	 */
	$icons = apply_filters( 'themeblvd_format_icons', $icons );

	if ( ! empty( $icons[ $format ] ) ) {

		$icon = $icons[ $format ];

	}

	/**
	 * Filters the individual icon used to represent a
	 * post format.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $icon        Icon name.
	 * @param string $format      Post format of current post, or post type if no post format and $force is TRUE.
	 * @param bool   $force       Whether to force an icon to still show when no post format.
	 * @param string $post_type   Post type of current post.
	 */
	return apply_filters( 'themeblvd_format_icon', $icon, $format, $force, $post_type );

}

/**
 * Remove the first instance of a [gallery]
 * shortcode from a block of content for a gallery
 * format post.
 *
 * This is intended to be a helper function that
 * can be filtered onto the_content() for use in
 * the loop with the "gallery" post format.
 *
 * This function is filtered onto:
 * 1. `the_content` - 7
 *
 * @since Theme_Blvd 2.3.0
 *
 * @param  string $content Post content.
 * @return string $content Modified post content.
 */
function themeblvd_content_format_gallery( $content ) {

	if ( ! has_post_format( 'gallery' ) ) {

		return $content;

	}

	$pattern = get_shortcode_regex();

	if ( preg_match( "/$pattern/s", $content, $match ) && 'gallery' == $match[2] ) {

		$content = str_replace( $match[0], '', $content );

	}

	return $content;

}

/**
 * Filter out the first URL or HTML link of the
 * content in a link format post.
 *
 * This function is filtered onto:
 * 1. `the_content` - 7
 *
 * @since Theme_Blvd 2.3.0
 *
 * @param  string $content Post content.
 * @return string $content Modified post content.
 */
function themeblvd_content_format_link( $content ) {

	if ( ! has_post_format( 'link' ) ) {

		return $content;

	}

	// Get the URL from the first line of content.
	$url = themeblvd_get_content_url( $content );

	/*
	 * Remove that URL from the start of content,
	 * if that's where it was.
	 */
	if ( $url ) {

		$content = str_replace( $url[0], '', $content ); // $url[0] will be first line of content.

	}

	return $content;

}

/**
 * Find URL in first line of content.
 *
 * Extracts a URL string from first line of passed
 * content, if possible.
 *
 * Also checks for a URL on the first line of the
 * content or and <a> tag.
 *
 * @since Theme_Blvd 2.4.0
 *
 * @param  string $content A string which might contain a URL, passed by reference.
 * @return string          The found URL.
 */
function themeblvd_get_content_url( $content ) {

	if ( empty( $content ) ) {

		return '';

	}

	$trimmed = trim( $content );

	$lines = explode( "\n", $trimmed );

	$line = trim( array_shift( $lines ) );

	$find_link = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";

	if ( preg_match( "/$find_link/siU", $line, $matches ) ) {

		// First line of content is HTML link.
		return array( $line, $matches[2] );

	} elseif ( stripos( $line, 'http' ) === 0 ) {

		// First line of content is URL.
		return array( $line, esc_url_raw( $line ) );

	}

	return '';

}

/**
 * Filter out the first quote from the content for
 * a quote format post.
 *
 * This will filter out either a <blockquote> or a
 * [blockquote] that exists on the first line of the
 * content.
 *
 * This function is filtered onto:
 * 1. `the_content` - 7
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $content Post content.
 * @return string $content Modified post content.
 */
function themeblvd_content_format_quote( $content ) {

	if ( ! has_post_format( 'quote' ) ) {

		return $content;

	}

	// Get the quote from the content.
	$quote = themeblvd_get_content_quote( $content, false );

	// Remove the quote, if we found one.
	if ( $quote ) {

		$content = str_replace( $quote, '', $content );

	}

	return $content;

}

/**
 * Get a quote from the first line a block of
 * content.
 *
 * Checks for a <blockquote> or [blockquote]
 * shortcode on the first line of the content.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $content A string which might contain a quote, passed by reference.
 * @param  bool   $run     If a [blockquote] shortcode is found, whether to process it into HTML.
 * @return string          The found quote.
 */
function themeblvd_get_content_quote( $content, $run = true ) {

	if ( empty( $content ) ) {

		return '';

	}

	$trimmed = trim( $content );

	$lines = explode( "\n", $trimmed );

	$line = trim( array_shift( $lines ) );

	// Look for `[blockquote]`.
	if ( strpos( $line, '[blockquote' ) === 0 ) {

		if ( $run ) {

			return do_shortcode( $line );

		} else {

			return $line;

		}
	}

	// Look for `<blockquote>`.
	if ( 0 === strpos( $trimmed, '<blockquote' ) ) {

		$end = strpos( $trimmed, '</blockquote>' ) + 13; // `</blockquote>` = 13 characters

		return substr( $trimmed, 0, $end );

	}

	return '';

}

/**
 * Display the extracted quote from a quote
 * format post.
 *
 * @since Theme_Blvd 2.5.0
 */
function themeblvd_content_quote() {

	if ( ! has_post_format( 'quote' ) ) {

		return;

	}

	echo themeblvd_get_content_quote( get_the_content() );

}

/**
 * Filter out the first video from the content for
 * a quote format post.
 *
 * This function is filtered onto:
 * 1. `the_content` - 7
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $content Post content.
 * @return string $content Modified post content.
 */
function themeblvd_content_format_video( $content ) {

	if ( ! has_post_format( 'video' ) ) {

		return $content;

	}

	// Get the video from the content.
	$video = themeblvd_get_content_video( $content, false );

	// Remove that video, if one was found.
	if ( $video ) {

		$content = str_replace( $video, '', $content );

	}

	return $content;

}

/**
 * Get a video from the first line a block of
 * content.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $content A string which might contain a video, passed by reference.
 * @param  bool   $run     Whether to process the HTML for an oembed URL or [video] shortcode.
 * @return string          The found video.
 */
function themeblvd_get_content_video( $content, $run = true ) {

	if ( empty( $content ) ) {

		return '';

	}

	$trimmed = trim( $content );

	$lines = explode( "\n", $trimmed );

	$line = trim( array_shift( $lines ) );

	// Check for URL, intended for oEmbed.
	if ( 0 === strpos( $line, 'http' ) ) {

		if ( $run ) {

			return wp_oembed_get( $line );

		} else {

			return $line;

		}
	}

	// Check for `[video]` shortcode.
	if ( 0 === strpos( $trimmed, '[video' ) ) {

		$end = strpos( $trimmed, '[/video]' ) + 8;

		$video = substr( $trimmed, 0, $end );

		if ( $run ) {

			$video = do_shortcode( $video );

		}

		return $video;

	}

	return '';

}

/**
 * Display the extracted video from the content
 * of a video format post.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param bool $placeholder Whether to display placeholder, when no video exists.
 */
function themeblvd_content_video( $placeholder = false ) {

	if ( ! has_post_format( 'video' ) ) {

		return;

	}

	$video = themeblvd_get_content_video( get_the_content() );

	if ( ! $video && $placeholder ) {

		$video = themeblvd_get_media_placeholder();

	}

	echo $video;

}

/**
 * Filter out the first audio player from the
 * content in an audio format post.
 *
 * This function is filtered onto:
 * 1. `the_content` - 7
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $content Post content.
 * @return string $content Modified post content.
 */
function themeblvd_content_format_audio( $content ) {

	if ( ! has_post_format( 'audio' ) ) {

		return $content;

	}

	// Get the audio player from the content.
	$audio = themeblvd_get_content_audio( $content, false );

	// Remove that audio player, if one was found.
	if ( $audio ) {

		$content = str_replace( $audio, '', $content );

	}

	return $content;

}

/**
 * Get an audio player from the first line a
 * block of content.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $content A string which might contain an audio player, passed by reference.
 * @param  bool   $run     Whether to process the HTML for an oembed URL or [audio] shortcode.
 * @return string          The found audio.
 */
function themeblvd_get_content_audio( $content, $run = true ) {

	if ( empty( $content ) ) {

		return '';

	}

	$trimmed = trim( $content );

	$lines = explode( "\n", $trimmed );

	$line = trim( array_shift( $lines ) );

	// Check for URL, intended for oEmbed.
	if ( strpos( $line, 'http' ) === 0 ) {
		if ( $run ) {
			return wp_oembed_get( $line );
		} else {
			return $line;
		}
	}

	// Check for `[audio]` shortcode.
	if ( strpos( $trimmed, '[audio' ) === 0 ) {

		$end = strpos( $trimmed, '[/audio]' ) + 8;

		$audio = substr( $trimmed, 0, $end );

		if ( $run ) {

			$audio = do_shortcode( $audio );

		}

		return $audio;

	}

	return '';

}

/**
 * Display the extracted audio from the content
 * of an audio format post.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param bool $placeholder Whether to display placeholder, when no audio exists.
 */
function themeblvd_content_audio( $placeholder = false ) {

	if ( ! has_post_format( 'audio' ) ) {

		return;

	}

	$audio = themeblvd_get_content_audio( get_the_content(), false );

	$img = themeblvd_get_post_thumbnail( themeblvd_get_att( 'crop' ), array(
		'link'        => false,
		'placeholder' => false,
	));

	if ( ! $img && $placeholder && false !== strpos( $audio, '[audio' ) ) {

		$img = themeblvd_get_media_placeholder();

	}

	if ( 0 === strpos( $audio, 'http' ) ) {

		$audio = wp_oembed_get( $audio );

	} else {
		$audio = do_shortcode( $audio );
	}

	if ( $img ) {

		printf(
			'<div class="tb-audio-image">%s<div class="audio-wrap">%s</div></div>',
			$img,
			$audio
		);

	} else {

		echo $audio;

	}

}
