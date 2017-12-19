<?php
/**
 * Helpers: General
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */

/**
 * Print a warning on the screen, for deprecated
 * functions.
 *
 * @since @@name-framework 2.0.0
 *
 * @param string $function    Name of deprectated function.
 * @param string $version     Framework version function was deprecated.
 * @param string $replacement Name of suggested replacement function.
 * @param string $message     Message to display instead of auto-generated replacement statement.
 */
function themeblvd_deprecated_function( $function, $version, $replacement = null, $message = null ) {

	/**
	 * Filters whether to trigger a errors for
	 * deprecated functions.
	 *
	 * Note: This a filter that's part of WordPress
	 * core, which we're implementing here.
	 *
	 * @since WordPress 2.5.0
	 *
	 * @param bool $trigger Whether to trigger the error for deprecated functions. Default true.
	 */
	if ( WP_DEBUG && apply_filters( 'deprecated_function_trigger_error', true ) ) {

		if ( $message ) {

			trigger_error( sprintf(
				// translators: 1. deprecated function name, 2. version number, 3. additional message
				esc_html__( '%1$s is deprecated since version %2$s of the Theme Blvd framework! %3$s', '@@text-domain' ),
				$function,
				$version,
				esc_html( $message )
			));

		} elseif ( $replacement ) {

			trigger_error( sprintf(
				// translators: 1. deprecated function name, 2. version number, 3. replacement function name
				esc_html__( '%1$s is deprecated since version %2$s of the Theme Blvd framework! Use %3$s instead.', '@@text-domain' ),
				$function,
				$version,
				$replacement
			));

		} else {

			trigger_error( sprintf(
				// translators: 1. deprecated function name, 2. version number
				esc_html__( '%1$s is deprecated since version %2$s of the Theme Blvd framework with no alternative available.', '@@text-domain' ),
				$function,
				$version
			));

		}
	}
}

/**
 * Remove trailing space or character from
 * a string.
 *
 * @since @@name-framework 2.0.0
 *
 * @param  string $string Current string to check.
 * @param  string $char   Character to remove from end of string.
 * @return string $string String with that trailing character remove, if it existed.
 */
function themeblvd_remove_trailing_char( $string, $char = ' ' ) {

	if ( ! $string ) {

		return null;

	}

	$offset = strlen( $string ) - 1;

	$trailing_char = strpos( $string, $char, $offset );

	if ( $trailing_char ) {

		$string = substr( $string, 0, -1 );

	}

	return $string;

}

/**
 * Check whether a URL returns an http 200 status.
 *
 * We use this primarily to determine if the URL to
 * some file we're trying to display is accessible,
 * like a video URL, for example.
 *
 * @since @@name-framework 2.6.0
 *
 * @param  string $url URL to a file.
 * @return bool        Whether the https status is 200.
 */
function themeblvd_is_200( $url ) {

	$code = 0;

	$response = wp_remote_head( $url, array(
		'timeout' => 5,
	));

	if ( ! is_wp_error( $response ) && isset( $response['response']['code'] ) ) {

		$code = $response['response']['code'];

	}

	return 200 === $code;

}

/**
 * Check if we're using a certain version of IE.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array $versions Version of IE to check for.
 * @return bool            Whether or not this is the specified version of IE.
 */
function themeblvd_is_ie( $versions = array( '8' ) ) {

	if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {

		foreach ( $versions as $ver ) {

			if ( preg_match( '/MSIE ' . $ver . '.0/', $_SERVER['HTTP_USER_AGENT'] ) ) {

				return true;

			}
		}
	}

	return false;

}

/**
 * Process FontAwesome icons passed into a content
 * block.
 *
 * This function find all instances of %{icon_name}%
 * and transforms them into FontAwesome icon HTML.
 *
 * This function is filtered onto:
 * 1. `themeblvd_the_content` - 10
 * 2. `widget_text` - 10
 * 3. `themeblvd_header_text` - 10
 *
 * @since @@name-framework 2.5.0
 *
 * @param  string $str Content string.
 * @return string $str Content string with icons added.
 */
function themeblvd_do_fa( $str ) {

	preg_match_all( '/\%(.*?)\%/', $str, $icons );

	if ( ! empty( $icons[0] ) ) {

		$list = true;

		if ( substr_count( trim( $str ), "\n" ) ) {

			$list = false; // If text has more than one line, we won't make into an inline list.

		}

		$total = count( $icons[0] );

		if ( $list ) {

			$str = sprintf( "<ul class=\"list-inline\">\n<li>%s</li>\n</ul>", $str );

		}

		foreach ( $icons[0] as $key => $val ) {

			/**
			 * Filters the HTML used to add FontAwesome
			 * icons within themeblvd_do_fa().
			 *
			 * @since @@name-framework 2.5.0
			 *
			 * @param string      FontAwesome icon HTML, with `%s` representing the icon name.,
			 * @param string $str Original Content string.
			 */
			$html = apply_filters( 'themeblvd_do_fa_html', '<i class="%s"></i>', $str );

			if ( $list && $key > 0 ) {

				$html = "<li>\n" . $html;

			}

			$str = str_replace(
				$val,
				sprintf(
					$html,
					esc_attr( themeblvd_get_icon_class( $icons[1][ $key ], array( 'fa-fw' ) ) )
				),
				$str
			);

		}
	}

	return $str;

}

/**
 * Compress a chunk of code to output.
 *
 * @since @@name-framework 2.0.0
 *
 * @param  string $buffer Text to compress.
 * @return array  $buffer Compressed text.
 */
function themeblvd_compress( $buffer ) {

	// Remove comments.
	$buffer = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer );

	// Remove tabs, spaces, newlines, etc.
	$buffer = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $buffer );

	return $buffer;

}

/**
 * Return FALSE.
 *
 * This is a replacement for WordPress's __return_false().
 *
 * When used it makes it easier for us to keep track
 * of where its hooked so it can be removed, if needed.
 *
 * @since @@name-framework 2.6.0
 */
function themeblvd_return_false() {

	return false;

}

/**
 * Return TRUE.
 *
 * This is a replacement for WordPress's __return_true().
 *
 * When used it makes it easier for us to keep track
 * of where its hooked so it can be removed, if needed.
 *
 * @since @@name-framework 2.6.0
 */
function themeblvd_return_true() {

	return true;

}

/**
 * Get site's home url.
 *
 * @since @@name-framework 2.5.0
 *
 * @return string Home URL.
 */
function themeblvd_get_home_url() {

	if ( function_exists( 'icl_get_home_url' ) ) {

		$url = trailingslashit( icl_get_home_url() );

	} else {

		$url = home_url( '/' );

	}

	return apply_filters( 'themeblvd_home_url', $url );

}

/**
 * Display site's home url.
 *
 * @since @@name-framework 2.5.2
 */
function themeblvd_home_url() {

	echo esc_url( themeblvd_get_home_url() );

}

/**
 * Merge theme allowed tags with WordPress's.
 *
 * This sete allowed tags for saving HTML through
 * theme's sanitization functions and displaying
 * custom HTML from theme elements.
 *
 * @since @@name-framework 2.0.0
 *
 * @return array $themeblvd_tags Allowed tags.
 */
function themeblvd_allowed_tags() {

	$tags = wp_kses_allowed_html( 'post' );

	$tags['a']['data-bg'] = true;
	$tags['a']['data-bg-hover'] = true;
	$tags['a']['data-text'] = true;
	$tags['a']['data-text-hover'] = true;

	$tags['img']['srcset'] = true;
	$tags['img']['sizes'] = true;

	$tags['iframe'] = array(
		'style'                 => true,
		'width'                 => true,
		'height'                => true,
		'src'                   => true,
		'frameborder'           => true,
		'allowfullscreen'       => true,
		'webkitAllowFullScreen' => true,
		'mozallowfullscreen'    => true,
	);

	$tags['script'] = array(
		'type' => true,
		'src'  => true,
	);

	$tags['time'] = array(
		'class'    => true,
		'datetime' => true,
	);

	/**
	 * Filters the theme's allowed HTML tags, when
	 * HTML is passed through theme elements.
	 *
	 * @since @@name-framework 2.0.0
	 *
	 * @param array $themeblvd_tags Allowed tags.
	 */
	return apply_filters( 'themeblvd_allowed_tags', $tags );

}

/**
 * Sanitize user-inputted HTML.
 *
 * @see themeblvd_allowed_tags()
 *
 * @since @@name-framework 2.5.2
 *
 * @param  array  $input Content to sanitize.
 * @return string        Content that's been sanitized.
 */
function themeblvd_kses( $input ) {

	/**
	 * Filters whether user-inputted HTML is
	 * sanitized.
	 *
	 * @since @@name-framework 2.5.2
	 *
	 * @param bool Whether user-inputted HTML is sanitized.
	 */
	if ( apply_filters( 'themeblvd_disable_kses', false ) ) {

		return $input;

	}

	return wp_kses( $input, themeblvd_allowed_tags() );

}
