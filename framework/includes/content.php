<?php
/**
 * Get blockquote formatted correctly for Bootstrap
 *
 * @since 2.4.0
 *
 * @param array $args Arguments for blockquote.
 */
function themeblvd_get_blockquote( $args ) {

	$defaults = array(
		'quote'			=> '',
		'source' 		=> '',		// Source of quote
		'source_link'	=> '',		// URL to link source to
		'align'			=> '',		// How to align blockquote - left, right
		'max_width'		=> '',		// Meant to be used with align left/right - 300px, 50%, etc
		'reverse'		=> 'false',	// Whether to add "blockquote-reverse" Bootstrap class, which will align text to right; this is different than pull-right, which will float.
		'class'			=> '' 		// Any additional CSS classes
	);
	$args = wp_parse_args( $args, $defaults );

	// CSS classes
	$class = 'tb-blockquote';

	if ( $args['reverse'] == 'true' ) {
		$class .= ' blockquote-reverse';
	}

	if ( $args['align'] ) {
		if ( 'left' == $args['align'] ) {
			$class .= ' pull-left';
		} else if ( 'right' == $args['align'] ) {
			$class .= ' pull-right';
		}
	}

	if ( $args['class'] ) {
		$class .= ' '.$args['class'];
	}

	// Max width
	$style = '';

	if ( $args['max_width'] ) {

		if ( false === strpos( $args['max_width'], 'px' ) && false === strpos( $args['max_width'], '%' ) ) {
			$args['max_width'] = $args['max_width'].'px';
		}

		$style = sprintf('max-width: %s;', $args['max_width'] );
	}

	// Quote
	$quote = $args['quote'];

	if ( false === strpos( $quote, '<p>' ) ) {
		$quote = wpautop( $quote );
	}

	// Source
	$source = '';

	if ( $args['source'] ) {

		$source = $args['source'];

		if ( $args['source_link'] ) {
			$source = sprintf( '<a href="%s" title="%s" target="_blank">%s</a>', $args['source_link'], $source, $source );
		}

		$source = sprintf( '<small><cite>%s</cite></small>', $source );

		$quote .= $source;

	}

	// Output
	$output = sprintf( '<blockquote class="%s" style="%s">%s</blockquote>', $class, $style, $quote );

	return apply_filters( 'themeblvd_blockquote', $output, $args, $quote, $source, $class, $style );
}

/**
 * Display blockquote formatted correctly for Bootstrap
 *
 * @since 2.4.0
 *
 * @param array $args Arguments for blockquote.
 */
function themeblvd_blockquote( $args ) {
	echo themeblvd_get_blockquote( $args );
}

/**
 * Take in some content and return it with formatting.
 *
 * @since 2.5.0
 *
 * @param array $content Content to display
 * @return string Formatted content
 */
function themeblvd_get_content( $content ) {
	return apply_filters( 'themeblvd_the_content', stripslashes( $content ) );
}

/**
 * Take in some content and display it with formatting.
 *
 * @since 2.5.0
 *
 * @param array $content Content to display
 * @return string Formatted content
 */
function themeblvd_content( $content ) {
	echo themeblvd_get_content( $content );
}

/**
 * Get content block
 *
 * @since 2.5.0
 *
 * @param string $args Options for content block
 * @return string $output Content output
 */
function themeblvd_get_content_block( $args ){

	$defaults = array(
        'content'		=> '',			// Content to display
        'raw_format'	=> '1',			// Whether to apply wpautop
        'style'			=> '',			// Custom styling class
		'text_color'	=> 'dark',		// Color of text, dark or light
        'bg_color'		=> '#cccccc',	// Background color, if wrap is true
        'bg_opacity'	=> '1'			// Background color opacity, if wrap is true
    );
    $args = wp_parse_args( $args, $defaults );

	// CSS class
	$class = 'tb-content-block';

	if ( $args['style'] == 'custom' ) {
		$class .= ' has-bg text-'.$args['text_color'];
	}

	if ( $args['style'] && $args['style'] != 'custom' && $args['style'] != 'none'  ) {
		$class .= ' '.$args['style'];
	}

	// Inline styles
	$style = '';

	if ( $args['style'] == 'custom' ) {
		$style = sprintf( 'background-color: %s;', $args['bg_color'] ); // Fallback for older browsers
		$style = sprintf( 'background-color: %s;', themeblvd_get_rgb( $args['bg_color'], $args['bg_opacity'] ) );
	}

	// Setup content
	if ( $args['raw_format'] ) {
		$content = themeblvd_get_content( $args['content'] );
	} else {
		$content = do_shortcode( $args['content'] );
	}

	// Final output
	$output = sprintf( '<div class="%s" style="%s">%s</div>', $class, $style, $content );

	return apply_filters( 'themeblvd_content_block', $output, $args );
}

/**
 * Display content block
 *
 * @since 2.5.0
 *
 * @param string $args Options for content block
 * @return string $output Content output
 */
function themeblvd_content_block( $args ){
	echo themeblvd_get_content_block( $args );
}

/**
 * Display headline.
 *
 * @since 2.5.0
 *
 * @param array $args Options for headline
 * @return string $output HTML output for headline
 */
function themeblvd_get_headline( $args ) {

	// Setup and extract $args
	$defaults = array(
		'text' 		=> '',		// Hadline text
		'tagline' 	=> '',		// Tagline below headline
		'tag' 		=> 'h1',	// Header wrapping headline - h1, h2, h3, etc
		'align' 	=> 'left'	// How to align the header - left, center, right
	);
	$args = wp_parse_args( $args, $defaults );

	// Swap in current page's title for %page_title%
	$text = str_replace( '%page_title%', get_the_title( themeblvd_config( 'id' ) ), $args['text'] );

	// Output
	$output = '<'.$args['tag'].' class="text-'.$args['align'].'">';
	$output .= stripslashes( $text );
	$output .= '</'.$args['tag'].'>';

	if ( $args['tagline'] ) {
		$output .= sprintf( '<p class="text-%s">%s</p>', $args['align'], stripslashes($args['tagline']) );
	}

	return apply_filters( 'themeblvd_headline', $output, $args );
}

/**
 * Display headline
 *
 * @since 2.0.0
 *
 * @param string $args Options for content block
 * @return string $output Content output
 */
function themeblvd_headline( $args ){
	echo themeblvd_get_headline( $args );
}

/**
 * Get content of current post, or inputted
 * post slug or ID.
 *
 * @since 2.5.0
 *
 * @param int|string $page Page ID or slug to pull content from
 */
function themeblvd_get_post_content( $post = 0, $post_type = '' ) {

	$content = '';
	$current = false;

	// If no ID, get current post's ID
	if ( ! $post ) {
		$current = true;
		$post = themeblvd_config('id');
	}

	// Post slug?
	if ( is_string( $post ) ) {
		$post = themeblvd_post_id_by_name( $post, $post_type );
	}

	$get_post = get_post( $post );

	if ( $get_post ) {
		if ( $current ) {
			$content = apply_filters( 'the_content', $get_post->post_content );
		} else {
			$content = themeblvd_get_content( $get_post->post_content );
		}
	}

	return $content;
}

/**
 * Display content of current post, or inputted post slug or ID.
 *
 * @since 2.5.0
 *
 * @param int|string $page Page ID or slug to pull content from
 */
function themeblvd_post_content( $post = 0 ) {
	echo themeblvd_get_post_content( $post );
}

/**
 * Get widgets from widget area
 *
 * @since 2.5.0
 *
 * @param array $sidebar Sidebar ID to pull widgets from
 * @param string $context Context of how widget area is used, element or block
 * @return string Formatted content
 */
function themeblvd_get_widgets( $sidebar, $context = 'element' ) {

	// CSS class
	$class = 'widget-area '.$sidebar;

	if ( in_array( $context, array( 'block', 'column', 'sidebar' ) ) ) {
		$class .= ' fixed-sidebar';
	}

	// Widgets
	ob_start();
	dynamic_sidebar( $sidebar );
	$widgets = ob_get_clean();

	// Wrap widgets for final output
	$output = sprintf( '<div class="%s">%s</div><!-- .widget-area (end) -->', $class, $widgets );

	return apply_filters( 'themeblvd_widgets', $output, $widgets, $sidebar, $context );
}

/**
 * Display widgets from widget area
 *
 * @since 2.5.0
 *
 * @param array $content Content to display
 * @return string Formatted content
 */
function themeblvd_widgets( $sidebar, $context = 'element' ) {
	echo themeblvd_get_widgets( $sidebar, $context );
}