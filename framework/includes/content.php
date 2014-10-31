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
        'format'		=> '1',			// Whether to apply wpautop
        'style'			=> '',			// Custom styling class
		'text_color'	=> 'dark',		// Color of text, dark or light
        'bg_color'		=> '#cccccc',	// Background color, if wrap is true
        'bg_opacity'	=> '1'			// Background color opacity, if wrap is true
    );
    $args = wp_parse_args( $args, $defaults );

	// CSS class
	$class = 'tb-content-block entry-content';

	if ( $args['style'] == 'custom' ) {
		$class .= ' has-bg text-'.$args['text_color'];
	} else if ( $args['style'] && $args['style'] != 'none' ) {
		$class .= ' '.$args['style'];
	}

	// Inline styles
	$style = '';

	if ( $args['style'] == 'custom' ) {
		$style = sprintf( 'background-color: %s;', $args['bg_color'] ); // Fallback for older browsers
		$style = sprintf( 'background-color: %s;', themeblvd_get_rgb( $args['bg_color'], $args['bg_opacity'] ) );
	}

	// Setup content
	if ( $args['format'] ) {
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
	$output .= '<div class="tb-headline text-'.$args['align'].'">';
	$output .= sprintf( '<%1$s>%2$s</%1$s>', $args['tag'], stripslashes($text) );

	if ( $args['tagline'] ) {
		$output .= sprintf( '<p>%s</p>', stripslashes($args['tagline']) );
	}

	$output .= '</div><!-- .tb-headline (end) -->';

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

	return sprintf( '<div class="entry-content">%s</div>', $content );
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
 * Get the_title() taking into account if it should
 * wrapped in a link.
 *
 * @since 2.3.0
 *
 * @param int $post_id Can feed in a post ID if outside the loop.
 * @param bool $foce_link Whether to force the title to link.
 * @return string $title The title of the post
 */
function themeblvd_get_the_title( $post_id = 0, $force_link = false ) {

	$url = '';
	$target = '_self';
	$title = get_the_title( $post_id );

	// If "link" post format, get URL from start of content.
	if ( has_post_format( 'link', $post_id ) ) {

		$find = themeblvd_get_content_url( get_the_content( $post_id ) );

		if ( $find ) {
			$target = '_blank';
			$url = $find[1];
			$title = $title.' <i class="fa fa-external-link"></i>';
		}
	}

	// If not a single post or page, get permalink for URL.
	if ( ! $url ) {
		if ( $force_link || ! is_single() || ( is_single() && themeblvd_get_att('doing_second_loop') ) ) {
			$url = get_permalink( $post_id );
		}
	}

	// Wrap title in link if there's a URL.
	if ( $url ) {
		$title = sprintf('<a href="%s" title="%s" target="%s">%s</a>', esc_url( $url ), esc_attr( the_title_attribute('echo=0') ), $target, $title );
	}

	return apply_filters( 'themeblvd_the_title', $title, $url );
}

/**
 * Display the_title() taking into account if it should
 * wrapped in a link.
 *
 * @since 2.3.0
 *
 * @param int $post_id Can feed in a post ID if outside the loop.
 * @param bool $foce_link Whether to force the title to link.
 */
function themeblvd_the_title( $post_id = 0, $force_link = false ) {
	echo themeblvd_get_the_title( $post_id, $force_link );
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

/**
 * Get title and content for a page, intended to be used with
 * page templates that list out posts so that the title and
 * content can optionally be displayed above the posts.
 *
 * @since 2.5.0
 *
 * @param array $content Content to of page
 * @return string Content with post display attached
 */
function themeblvd_get_page_info() {

	if ( get_query_var('paged') >= 2 ) {
		return;
	}

	$post_id = themeblvd_config('id');
	$output = $title = $content = '';

	if ( get_post_meta( $post_id , '_tb_title', true ) != 'hide' ) {
		$title = sprintf( '<h1 class="info-box-title archive-title">%s</h1>', get_the_title($post_id) );
	}

	$content = get_the_content($post_id);
	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );

	if ( $content && $edit = get_edit_post_link($post_id) ) {
		$content .= sprintf( '<div class="edit-link"><i class="fa fa-edit"></i> <a href="%s">%s</a></div>', $edit, themeblvd_get_local('edit_page') );
	}

	$class = apply_filters('themeblvd_tax_info_class', 'tb-info-box tb-page-info'); // Filtering to allow "content-bg" to be added

	if ( $title || $content ) {
		$output = sprintf( '<section class="%s"><div class="inner">%s</div></section>', $class, $title.$content );
	}

	echo apply_filters( 'themeblvd_page_info', $output );
}

/**
 * Display title and content for a page.
 *
 * @since 2.5.0
 *
 * @param array $content Content to of page
 * @return string Content with post display attached
 */
function themeblvd_page_info() {
	echo themeblvd_get_page_info();
}