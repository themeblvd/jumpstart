<?php
/**
 * Frontend Blocks: Content
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.5.0
 */

/**
 * Get a blockquote block.
 *
 * @since Theme_Blvd 2.4.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string      $quote       Quote text.
 *     @type string      $source      Source of quote, like `John Smith`.
 *     @type string      $source_link URL to quote source, like `http://johnsmith.com`.
 *     @type string      $align       How to align outer block, `left` or `right` (leave blank for standard, center alignment).
 *     @type string      $max_width   Maximum width, like `300px`, `50%`, etc.
 *     @type string|bool $reverse     Whether to align inner text right.
 *     @type string      $class       Optional. CSS class to add.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_blockquote( $args ) {

	$args = wp_parse_args( $args, array(
		'quote'       => '',
		'source'      => '',
		'source_link' => '',
		'align'       => '',
		'max_width'   => '',
		'reverse'     => 'false',
		'class'       => '',
	) );

	$class = 'tb-blockquote';

	if ( 'true' == $args['reverse'] || '1' == $args['reverse'] ) {

		$class .= ' blockquote-reverse';

	}

	if ( $args['align'] ) {

		if ( 'left' === $args['align'] ) {

			$class .= ' pull-left';

		} elseif ( 'right' == $args['align'] ) {

			$class .= ' pull-right';

		}
	}

	if ( $args['class'] ) {

		$class .= ' ' . $args['class'];

	}

	$style = '';

	if ( $args['max_width'] ) {

		if ( false === strpos( $args['max_width'], 'px' ) && false === strpos( $args['max_width'], '%' ) ) {

			$args['max_width'] = $args['max_width'] . 'px';

		}

		$style = sprintf( 'max-width: %s;', $args['max_width'] );

	}

	$quote = $args['quote'];

	if ( false === strpos( $quote, '<p>' ) ) {

		$quote = wpautop( $quote );

	}

	$source = '';

	if ( $args['source'] ) {

		$source = $args['source'];

		if ( $args['source_link'] ) {

			$source = sprintf(
				'<a href="%1$s" title="%2$s" target="_blank">%2$s</a>',
				$args['source_link'],
				$source
			);

		}

		$source = sprintf( '<small><cite>%s</cite></small>', $source );

		$quote .= $source;

	}

	$output = sprintf(
		'<blockquote class="%s" style="%s">%s</blockquote>',
		esc_attr( $class ),
		esc_attr( $style ),
		themeblvd_kses( $quote )
	);

	/**
	 * Filters the final HTML output for a blockquote
	 * block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string      $quote       Quote text.
	 *     @type string      $source      Source of quote, like `John Smith`.
	 *     @type string      $source_link URL to quote source, like `http://johnsmith.com`.
	 *     @type string      $align       How to align outer block, `left` or `right` (leave blank for standard, center alignment).
	 *     @type string      $max_width   Maximum width, like `300px`, `50%`, etc.
	 *     @type string|bool $reverse     Whether to align inner text right.
	 *     @type string      $class       Optional. CSS class to add.
	 * }
	 */
	return apply_filters( 'themeblvd_blockquote', $output, $args, $quote, $source, $class, $style );

}

/**
 * Display a blockquote block.
 *
 * @since Theme_Blvd 2.4.0
 *
 * @param array $args Block arguments, see themeblvd_get_blockquote() docs.
 */
function themeblvd_blockquote( $args ) {

	echo themeblvd_get_blockquote( $args );

}

/**
 * Get formatted content.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $content Content to display.
 * @return string          Formatted content.
 */
function themeblvd_get_content( $content ) {

	/**
	 * Filters formatted content.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @hooked array( $GLOBALS['wp_embed'], 'run_shortcode' ) - 8
	 * @hooked array( $GLOBALS['wp_embed'], 'autoembed' ) - 8
	 * @hooked themeblvd_footer_copyright_helpers - 10
	 * @hooked themeblvd_do_fa - 10
	 * @hooked wptexturize - 10
	 * @hooked wpautop - 10
	 * @hooked shortcode_unautop - 10
	 * @hooked do_shortcode - 10
	 *
	 * @param string $content Content to format.
	 */
	return apply_filters( 'themeblvd_the_content', $content );

}

/**
 * Display formatted content.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param string $content Content to display.
 */
function themeblvd_content( $content ) {

	echo themeblvd_get_content( $content );

}

/**
 * Get a content block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string      $content    Content to display within block.
 *     @type string|bool $format     Whether to apply automatic formatting.
 *     @type string|bool $center     Whether to center the text within.
 *     @type string      $max        Maximum with of outer block, like `500px`, `50%`, etc.
 *     @type string      $style      Custom styling class.
 *     @type string      $text_color If $style == `custom`, color of text, like `#000`.
 *     @type string      $bg_color   If $style == `custom`, color of background, like `#000`.
 *     @type string      $bg_opacity If $style == `custom`, opacity of background color, like `0.5`, `1`, etc.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_content_block( $args ) {

	$args = wp_parse_args( $args, array(
		'content'    => '',
		'format'     => '1',
		'center'     => '0',
		'max'        => '',
		'style'      => '',
		'text_color' => 'none',
		'bg_color'   => '#cccccc',
		'bg_opacity' => '1',
	) );

	$class = 'tb-content-block entry-content';

	if ( 'custom' === $args['style'] ) {

		$class .= ' has-bg';

		if ( 'none' !== $args['text_color'] ) {

			$class .= ' text-' . $args['text_color'];

		}
	} elseif ( $args['style'] && 'none' !== $args['style'] ) {

		$class .= ' ' . $args['style'];

	}

	if ( $args['center'] ) {

		$class .= ' text-center';

	}

	$style = '';

	if ( 'custom' === $args['style'] ) {

		$style .= sprintf(
			'background-color: %s;',
			themeblvd_get_rgb( $args['bg_color'], $args['bg_opacity'] )
		);

	}

	if ( $args['max'] ) {

		$style .= sprintf( 'max-width: %s;', $args['max'] );

	}

	if ( $args['format'] ) {

		$content = themeblvd_get_content( $args['content'] );

	} else {

		$content = do_shortcode( themeblvd_kses( $args['content'] ) );

	}

	$output = sprintf(
		'<div class="%s" style="%s">%s</div>',
		esc_attr( $class ),
		esc_attr( $style ),
		$content
	);

	/**
	 * Filters the final HTML output for a content
	 * block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string      $content    Content to display within block.
	 *     @type string|bool $format     Whether to apply automatic formatting.
	 *     @type string|bool $center     Whether to center the text within.
	 *     @type string      $max        Maximum with of outer block, like `500px`, `50%`, etc.
	 *     @type string      $style      Custom styling class.
	 *     @type string      $text_color If $style == `custom`, color of text, like `#000`.
	 *     @type string      $bg_color   If $style == `custom`, color of background, like `#000`.
	 *     @type string      $bg_opacity If $style == `custom`, opacity of background color, like `0.5`, `1`, etc.
	 * }
	 */
	return apply_filters( 'themeblvd_content_block', $output, $args, $content );

}

/**
 * Display a content block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_content_block() docs.
 */
function themeblvd_content_block( $args ) {

	echo themeblvd_get_content_block( $args );

}

/**
 * Get a headline block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string $text    Headline text.
 *     @type string $tagline Optional. Tagline below headline text.
 *     @type string $tag     Header HTML tag to use, like `h1`, `h2`, `h3`, etc.
 *     @type string $align   How to align text, `left`, `center` or `right`.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_headline( $args ) {

	$defaults = array(
		'text'    => '',      // Hadline text
		'tagline' => '',      // Tagline below headline
		'tag'     => 'h1',    // Header wrapping headline - h1, h2, h3, etc
		'align'   => 'left',   // How to align the header - left, center, right
	);
	$args = wp_parse_args( $args, $defaults );

	// Swap in current page's title for %page_title%.
	$text = str_replace(
		'%page_title%',
		get_the_title( themeblvd_config( 'id' ) ),
		$args['text']
	);

	$output = '<div class="tb-headline text-' . esc_attr( $args['align'] ) . '">';

	$output .= sprintf(
		'<%1$s>%2$s</%1$s>',
		esc_attr( $args['tag'] ),
		themeblvd_kses( $text )
	);

	if ( $args['tagline'] ) {

		$output .= sprintf(
			'<p>%s</p>',
			themeblvd_kses( $args['tagline'] )
		);

	}

	$output .= '</div><!-- .tb-headline (end) -->';

	/**
	 * Filters the final HTML output for a headline
	 * block.
	 *
	 * @since Theme_Blvd 2.0.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string $text    Headline text.
	 *     @type string $tagline Optional. Tagline below headline text.
	 *     @type string $tag     Header HTML tag to use, like `h1`, `h2`, `h3`, etc.
	 *     @type string $align   How to align text, `left`, `center` or `right`.
	 * }
	 */
	return apply_filters( 'themeblvd_headline', $output, $args );

}

/**
 * Display a headline block.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param array $args Block arguments, see themeblvd_get_headline() docs.
 */
function themeblvd_headline( $args ) {

	echo themeblvd_get_headline( $args );

}

/**
 * Get a dynamic content block.
 *
 * The content from this can come from current
 * post, or inputted post slug or ID.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  int|string $post_id   Post ID or slug to pull content from.
 * @param  string     $post_type Optional. Post type of post to pull content from, which makes database retrieval more efficient.
 * @return string                Final content to output.
 */
function themeblvd_get_post_content( $post_id = 0, $post_type = '' ) {

	$content = '';

	if ( ! $post_id ) {

		wp_reset_query();

		$content = apply_filters( 'the_content', get_the_content() );

	} else {

		if ( is_string( $post_id ) ) {

			$post_id = themeblvd_post_id_by_name( $post_id, $post_type );

		}

		$post = get_post( $post_id );

		if ( $post ) {

			$content = themeblvd_get_content( $post->post_content );

		}
	}

	return sprintf( '<div class="entry-content">%s</div>', $content );

}

/**
 * Display a dynamic content block.
 *
 * The content from this can come from current
 * post, or inputted post slug or ID.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  int|string $post_id   Post ID or slug to pull content from.
 * @param  string     $post_type Optional. Post type of post to pull content from, which makes database retrieval more efficient.
 */
function themeblvd_post_content( $post = 0, $post_type = '' ) {

	echo themeblvd_get_post_content( $post, $post_type );

}

/**
 * Gets the title of a post.
 *
 * This serves as a wrapper for WordPress's get_the_title(),
 * to extend it and determine if the post title should
 * be wrapped in a link or not.
 *
 * @since Theme_Blvd 2.3.0
 *
 * @param  int    $post_id   Can feed in a post ID if outside the loop.
 * @param  bool   $foce_link Whether to force the title to link.
 * @return string $title     The title of the post, wrapped in a link if necessary.
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

			/**
			 * Filters the icon name used in the title of a
			 * "link" format post.
			 *
			 * @since Theme_Blvd 2.7.4
			 *
			 * @param string Icon name.
			 */
			$icon = apply_filters( 'themeblvd_external_link_title_icon', 'external-link-square-alt' );

			$title = $title . ' <i class="' . esc_attr( themeblvd_get_icon_class( $icon ) ) . '"></i>';

		}
	}

	// If not a single post or page, get permalink for URL.
	if ( ! $url ) {

		if ( $force_link || ! is_single() || ( is_single() && themeblvd_get_att( 'doing_second_loop' ) ) ) {

			$url = get_permalink( $post_id );

		}
	}

	// Wrap title in link if there's a URL.
	if ( $url ) {

		$title = sprintf(
			'<a href="%s" title="%s" target="%s">%s</a>',
			esc_url( $url ),
			esc_attr( the_title_attribute( 'echo=0' ) ),
			$target,
			themeblvd_kses( $title )
		);

	}

	/**
	 * Filters the get_the_title() wrapper, which accounts
	 * for wrapping the title in a link or not.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $title The title of the post, wrapped in a link if necessary.
	 * @param string $url   If a link, the URL to link to.
	 */
	return apply_filters( 'themeblvd_the_title', $title, $url );

}

/**
 * Displays the title of a post.
 *
 * This serves as a wrapper for WordPress's the_title(),
 * to extend it and determine if the post title should
 * be wrapped in a link or not.
 *
 * @since Theme_Blvd 2.3.0
 *
 * @param  int    $post_id   Can feed in a post ID if outside the loop.
 * @param  bool   $foce_link Whether to force the title to link.
 */
function themeblvd_the_title( $post_id = 0, $force_link = false ) {

	echo themeblvd_kses( themeblvd_get_the_title( $post_id, $force_link ) );

}

/**
 * Get the archive title.
 *
 * @since Theme_Blvd 2.7.0
 *
 * @return string $title Archive title.
 */
function themeblvd_get_the_archive_title() {

	global $wp_query;

	/**
	 * Filters the archive title used for archive
	 * banners and archive info boxes.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @param string Archive title.
	 */
	$title = apply_filters( 'themeblvd_archive_title', '' );

	if ( ! $title ) {

		if ( is_category() || is_tag() || is_tax() ) {

			if ( is_tax() ) {

				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

			} else {

				$term = $wp_query->get_queried_object();

			}

			if ( $term ) {

				$title = $term->name;

			}

			if ( is_tag() ) {

				$title = sprintf( themeblvd_get_local( 'crumb_tag' ), $title );

			}

		} elseif ( is_date() ) {

			if ( is_day() ) {

				$title = get_the_time( get_option( 'date_format' ) );

			} elseif ( is_month() ) {

				$title = get_the_time( 'F Y' );

			} elseif ( is_year() ) {

				$title = get_the_time( 'Y' );

			}

		} elseif ( is_author() ) {

			if ( get_query_var( 'author_name' ) ) {

				$user = get_user_by( 'slug', get_query_var( 'author_name' ) );

			} elseif ( get_query_var( 'author' ) ) {

				$user = get_user_by( 'id', get_query_var( 'author' ) );

			}

			if ( ! empty( $user ) ) {

				$title = themeblvd_get_local( 'crumb_author' ) . ' ' . $user->display_name;

			}

		} elseif ( is_search() ) {

			$title = sprintf( themeblvd_get_local( 'crumb_search' ), get_search_query() );

		}
	}

	return $title;

}

/**
 * Display the archive title.
 *
 * @since Theme_Blvd 2.7.0
 */
function themeblvd_the_archive_title() {

	echo themeblvd_kses( themeblvd_get_the_archive_title() );

}

/**
 * Get the archive banner image.
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param  array $image {
 *     Archive banner image arguments.
 *
 *     @type int    $id    Attachment ID.
 *     @type string $src   Source URL of cropped image.
 *     @type string $alt   Image alternative text.
 *     @type string $full  Source URL of full-size image.
 *     @type string $title Image title.
 *     @type string $crop  Image crop size.
 * }
 * @return string $output Final HTML output.
 */
function themeblvd_get_the_archive_banner_image( $image = array() ) {

	$output = '';

	if ( ! $image ) {

		$image = themeblvd_get_option( 'archive_banner' );

	}

	$image = wp_parse_args( $image, array(
		'id'   => 0,
		'src'  => '',
		'alt'  => '',
		'crop' => '',
	) );

	/**
	 * Filters the archive banner image arguments.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @param array $image {
	 *     Archive banner image arguments.
	 *
	 *     @type int    $id    Attachment ID.
	 *     @type string $src   Source URL of cropped image.
	 *     @type string $full  Source URL of full-size image.
	 *     @type string $title Image title.
	 *     @type string $crop  Image crop size.
	 * }
	 */
	$image = apply_filters( 'themeblvd_archive_banner_image_args', $image );

	if ( 'fs' === themeblvd_get_att( 'thumbs' ) ) {

		$output .= themeblvd_get_bg_parallax( array(
			'src' => $image['src'],
			'alt' => $image['alt'],
		) );

		$to = 'main';

		if ( themeblvd_show_breadcrumbs() ) {

			$to = 'breadcrumbs';

		}

		$output .= themeblvd_get_to_section( array(
			'to' => $to,
		) );

	} else {

		$output .= wp_get_attachment_image( $image['id'], $image['crop'] );

	}

	/**
	 * Filters the archive banner image.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $image {
	 *     Archive banner image arguments.
	 *
	 *     @type int    $id    Attachment ID.
	 *     @type string $src   Source URL of cropped image.
	 *     @type string $full  Source URL of full-size image.
	 *     @type string $title Image title.
	 *     @type string $crop  Image crop size.
	 * }
	 */
	return apply_filters( 'themeblvd_archive_banner_image', $output, $image );

}

/**
 * Display the archive banner image.
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param  array {
 *     Archive banner image arguments.
 *
 *     @type int    $id    Attachment ID.
 *     @type string $src   Source URL of cropped image.
 *     @type string $full  Source URL of full-size image.
 *     @type string $title Image title.
 *     @type string $crop  Image crop size.
 * }
 */
function themeblvd_the_archive_banner_image( $image = array() ) {

	echo themeblvd_get_the_archive_banner_image( $image );

}

/**
 * Get a widget area block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $sidebar Sidebar ID to pull widgets from.
 * @param  string $context Context of how widget area is used, `element` or `block`.
 * @return string $output  Final HTML output for block.
 */
function themeblvd_get_widgets( $sidebar, $context = 'element' ) {

	$class = 'widget-area ' . $sidebar;

	if ( in_array( $context, array( 'block', 'column', 'sidebar' ) ) ) {

		$class .= ' fixed-sidebar';

	}

	ob_start();

	dynamic_sidebar( $sidebar );

	$widgets = ob_get_clean();

	$output = sprintf( '<div class="%s">%s</div><!-- .widget-area (end) -->', $class, $widgets );

	/**
	 * Filters the final HTML output for a contextual
	 * alert block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output  Final HTML output.
	 * @param string $widgets Widget area output from dynamic_sidebar().
	 * @param string $sidebar Sidebar ID to pull widgets from.
	 * @param string $context Context of how widget area is used, `element` or `block`.
	 */
	return apply_filters( 'themeblvd_widgets', $output, $widgets, $sidebar, $context );

}

/**
 * Display a widget area block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param string $sidebar Sidebar ID to pull widgets from.
 * @param string $context Context of how widget area is used, `element` or `block`.
 */
function themeblvd_widgets( $sidebar, $context = 'element' ) {

	echo themeblvd_get_widgets( $sidebar, $context );

}

/**
 * Get a page info block.
 *
 * The page information will consist of the title
 * and content.
 *
 * This is intended to be used with page templates
 * that list out posts so that the title and content
 * can optionally be displayed above the secondary
 * posts loop.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_page_info() {

	if ( get_query_var( 'paged' ) >= 2 ) {

		return;

	}

	$post_id = themeblvd_config( 'id' );

	$output = '';

	$title = '';

	$content = '';

	if ( 'hide' !== get_post_meta( $post_id , '_tb_title', true ) && ! themeblvd_get_att( 'epic_thumb' ) ) {

		$title = sprintf(
			'<h1 class="info-box-title archive-title">%s</h1>',
			get_the_title( $post_id )
		);

	}

	$content = get_the_content( $post_id );

	$content = apply_filters( 'the_content', $content );

	$content = str_replace( ']]>', ']]&gt;', $content );

	$edit = get_edit_post_link( $post_id );

	if ( $content && $edit ) {

		$content .= sprintf(
			'<div class="edit-link"><i class="%s"></i> <a href="%s">%s</a></div>',
			esc_attr( themeblvd_get_icon_class( 'fa-edit' ) ),
			esc_url( $edit ),
			esc_html( themeblvd_get_local( 'edit_page' ) )
		);

	}

	/**
	 * Filters the class used for the info box.
	 *
	 * This is a shared filter that gets across similar
	 * info boxes. It was originally used for adding
	 * information to taxonomy pages, thus the name.
	 *
	 * This filter allows for an easy way to add a
	 * unified styling class (like `content-bg`) across
	 * all similar info box elements.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string CSS classes, separated by spaces.
	 */
	$class = apply_filters( 'themeblvd_tax_info_class', 'tb-info-box tb-page-info' );

	if ( $title || $content ) {

		$output = sprintf(
			'<section class="%s"><div class="inner">%s</div></section>',
			$class,
			$title . $content
		);

	}

	/**
	 * Filters a page info block.
	 *
	 * The page information will consist of the title
	 * and content.
	 *
	 * This is intended to be used with page templates
	 * that list out posts so that the title and content
	 * can optionally be displayed above the secondary
	 * posts loop.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $outuput Final HTML output.
	 */
	return apply_filters( 'themeblvd_page_info', $output );

}

/**
 * Display a page info block.
 *
 * The page information will consist of the title
 * and content.
 *
 * This is intended to be used with page templates
 * that list out posts so that the title and content
 * can optionally be displayed above the secondary
 * posts loop.
 *
 * @since Theme_Blvd 2.5.0
 */
function themeblvd_page_info() {

	echo themeblvd_get_page_info();

}
