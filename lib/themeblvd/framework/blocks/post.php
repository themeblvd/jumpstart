<?php
/**
 * Frontend Blocks: Post Elements
 *
 * Note: The functions included in this file
 * are intended to be used within the loop.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.7.0
 */

/**
 * Get a meta info block for a post.
 *
 * This can include the following components, which
 * get compiled into an output string.
 *
 * 1. `format`    Post format.
 * 2. `time`      Post date.
 * 3. `author`    Post author name.
 * 4. `comments`  Number of post comments, if has comments.
 * 5. `category`  Post's category or portfolio item's portfolio.
 *
 * @since @@name-framework 2.3.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string $sep      Separator HTML between items.
 *     @type array  $include  Array of items to include; see description just above.
 *     @type array  $icons    Which of $include items should display with an icon.
 *     @type string $comments How to display comments, `standard` or `mini`.
 *     @type string $time     How to display post date, `standard` or `ago`.
 *     @type string $class    CSS classes to add, like `foo bar baz`.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_meta( $args = array() ) {

	$args = wp_parse_args( $args, array(
		'sep'      => apply_filters( 'themeblvd_meta_separator', '<span class="sep"> / </span>' ),
		'include'  => array( 'format', 'time', 'author', 'comments' ),
		'icons'    => array( 'format', 'time', 'author', 'comments', 'category', 'portfolio' ),
		'comments' => 'standard',
		'time'     => 'standard',
		'class'    => '',
	) );

	/**
	 * Filters the FontAwesome icons used for each
	 * of the $include items within a post meta block.
	 *
	 * @since @@name-framework 2.3.0
	 *
	 * @param array {
	 *     Icon names used.
	 *
	 *     @type string $time      Icon name used for displaying post's date.
	 *     @type string $author    Icon name used for displaying post's author.
	 *     @type string $comments  Icon name used for displaying post's comment count.
	 *     @type string $category  Icon name used for displaying post's category.
	 *     @type string $portfolio Icon name used for displaying portfolio item's portfolio.
	 * }
	 */
	$icons = apply_filters( 'themeblvd_meta_icons', array(
		'time'      => 'clock',
		'author'    => 'user',
		'comments'  => 'comment',
		'category'  => 'folder',
		'portfolio' => 'briefcase',
	) );

	/*
	 * If comments are supposed to be included in meta
	 * info block, but are generally disabled on the
	 * post, we'll remove `comments` from the items to
	 * include.
	 */
	if ( in_array( 'comments', $args['include'] ) && ! themeblvd_show_comments() ) {

		$key = array_search( 'comments', $args['include'] );

		unset( $args['include'][ $key ] );

	}

	// Setup data for displaying the post category.
	if ( in_array( 'category', $args['include'] ) ) {

		$tax = 'category';

		$tax_icon = $icons['category'];

		if ( 'portfolio_item' === get_post_type() ) { // requires "Portfolios" plugin.

			$tax = 'portfolio';

			$tax_icon = $icons['portfolio'];

		}

		$category = get_the_term_list( get_the_ID(), $tax, '', ', ' );

		if ( ! $category ) {

			$key = array_search( 'category', $args['include'] );

			unset( $args['include'][ $key ] );

		}
	}

	$output = '';

	$count = count( $args['include'] );

	if ( $count ) {

		$sep = $args['sep'];

		$class = 'entry-meta';

		if ( $args['class'] ) {

			$class .= ' ' . $args['class'];

		}

		$class .= ' clearfix';

		// Start output.
		$output .= sprintf( '<div class="%s">', $class );

		foreach ( $args['include'] as $key => $item ) {

			$item_output = '';

			switch ( $item ) {

				case 'author':
					$author = esc_html( get_the_author() );

					$author_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );

					$author_title = sprintf(
						// translators: 1: post author's name
						esc_html__( 'View all posts by %s', '@@text-domain' ),
						$author
					);

					if ( in_array( $item, $args['icons'] ) ) {

						$author_icon = themeblvd_get_icon( themeblvd_get_icon_class( $icons['author'] ) );

					} else {

						$author_icon = '';

					}

					$item_output = sprintf(
						'<span class="byline author vcard">%s<a class="url fn n" href="%s" title="%s" rel="author">%s</a></span>',
						$author_icon,
						$author_url,
						$author_title,
						$author
					);

					break;

				case 'category':
					if ( in_array( $item, $args['icons'] ) ) {

						$category_icon = themeblvd_get_icon( themeblvd_get_icon_class( $tax_icon ) );

					} else {

						$category_icon = '';

					}

					$item_output = sprintf(
						'<span class="category">%s%s</span>',
						$category_icon,
						$category
					);

					break;

				case 'comments':
					$item_output = '<span class="comments-link">';

					ob_start();

					if ( 'mini' === $args['comments'] ) {

						comments_popup_link( '0', '1', '%' );

					} else {

						comments_popup_link(
							'<span class="leave-reply">' . themeblvd_get_local( 'no_comments' ) . '</span>',
							'1 ' . themeblvd_get_local( 'comment' ),
							'% ' . themeblvd_get_local( 'comments' )
						);

					}

					$comment_link = ob_get_clean();

					if ( in_array( $item, $args['icons'] ) ) {

						$item_output .= themeblvd_get_icon( themeblvd_get_icon_class( $icons['comments'] ) );

					}

					$item_output .= $comment_link;

					$item_output .= '</span>';

					break;

				case 'format':
					$format = get_post_format();

					if ( $format ) {

						$format_icon = '';

						if ( in_array( $item, $args['icons'] ) ) {

							$format_icon = themeblvd_get_format_icon( $format );

							if ( $format_icon ) {

								$format_icon = themeblvd_get_icon( themeblvd_get_icon_class( $format_icon ) );

							}
						}

						$item_output .= sprintf(
							'<span class="post-format">%s%s</span>',
							$format_icon,
							themeblvd_get_local( $format )
						);

					}

					break;

				case 'time':
					if ( in_array( $item, $args['icons'] ) ) {

						$time_icon = themeblvd_get_icon( themeblvd_get_icon_class( $icons['time'] ) );

					} else {

						$time_icon = '';

					}

					if ( 'ago' === $args['time'] ) {

						$item_output = sprintf(
							'<time class="entry-date updated" datetime="%s">%s%s</time>',
							esc_attr( get_the_time( 'c' ) ),
							$time_icon,
							esc_html( themeblvd_get_time_ago( get_the_ID() ) )
						);

					} else {

						$item_output = sprintf(
							'<time class="entry-date updated" datetime="%s">%s%s</time>',
							esc_attr( get_the_time( 'c' ) ),
							$time_icon,
							esc_html( get_the_time( get_option( 'date_format' ) ) )
						);

					}

					break;

			}

			if ( $item_output ) {

				$output .= $item_output;

				if ( $key + 1 < $count ) {

					$output .= $sep;

				}
			}
		}

		$output .= '</div><!-- .entry-meta -->';

	}

	/**
	 * Filters the final HTML output for a meta
	 * info block for a post
	 *
	 * @since @@name-framework 2.3.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string $sep      Separator HTML between items.
	 *     @type array  $include  Array of items to include; this can include `format`, `time`, `author`, `comments` and `category`.
	 *     @type array  $icons    Which of $include items should display with an icon.
	 *     @type string $comments How to display comments, `standard` or `mini`.
	 *     @type string $time     How to display post date, `standard` or `ago`.
	 *     @type string $class    CSS classes to add, like `foo bar baz`.
	 * }
	 */
	return apply_filters( 'themeblvd_meta', $output, $args );

}

/**
 * Get (or display) a list of categories for the
 * current post in the loop.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  bool   $echo   Whether to echo $output; if FALSE, will return $output.
 * @return string $output Final HTML output for block.
 */
function themeblvd_blog_cats( $echo = true ) {

	$output = '';

	if ( has_category() ) {

		$output .= '<div class="tb-cats categories">';

		$output .= sprintf( '<span class="title">%s:</span>', themeblvd_get_local( 'posted_in' ) );

		ob_start();

		the_category( ', ' );

		$output .= ob_get_clean();

		$output .= '</div><!-- .tb-cats (end) -->';

	}

	/**
	 * Filters the final HTML output for a post's
	 * categories.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param string         ID of current post in the loop.
	 */
	$output = apply_filters( 'themeblvd_blog_cats', $output, get_the_ID() );

	if ( $echo ) {

		echo $output;

	} else {

		return $output;

	}

}

/**
 * Get (or display) a list of tags for the current
 * post in the loop.
 *
 * @since @@name-framework 2.0.0
 *
 * @param  bool   $echo   Whether to echo $output; if FALSE, will return $output.
 * @return string $output Final HTML output for block.
 */
function themeblvd_blog_tags( $echo = true ) {

	$output = '';

	ob_start();

	the_tags( '', '' );

	$tags = ob_get_clean();

	if ( $tags ) {

		$output .= sprintf(
			'<div class="tb-tags tags">%s</div><!-- .tb-tags (end) -->',
			$tags
		);

	}

	/**
	 * Filters the final HTML output for a post's
	 * tags.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param string         ID of current post in the loop.
	 */
	$output = apply_filters( 'themeblvd_blog_tags', $output, get_the_ID() );

	if ( $echo ) {

		echo $output;

	} else {

		return $output;

	}

}

/**
 * Get (or display) the share buttons for the
 * current post in the loop.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  bool   $echo   Whether to echo $output; if FALSE, will return $output.
 * @return string $output Final HTML output for block.
 */
function themeblvd_blog_share( $echo = true ) {

	$output = '';

	$buttons = themeblvd_get_option( 'share' );

	if ( $buttons && is_array( $buttons ) ) {

		$patterns = themeblvd_get_share_patterns();

		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'tb_thumb' );

		$thumb = esc_url( $thumb[0] );

		$permalink = get_permalink();

		$shortlink = wp_get_shortlink();

		$title = get_the_title();

		$excerpt = get_the_excerpt();

		$output .= '<ul class="tb-share clearfix">';

		foreach ( $buttons as $button ) {

			$network = $button['icon'];

			$class = 'btn-share tb-tooltip shutter-out-vertical ' . $network;

			if ( 'email' !== $network ) {

				$class .= ' popup';

			}

			$link = '';

			if ( isset( $patterns[ $network ] ) ) {

				$link = $patterns[ $network ]['pattern'];

				if ( $patterns[ $network ]['encode_urls'] ) {

					$link = str_replace( '[permalink]', rawurlencode( $permalink ), $link );

					$link = str_replace( '[shortlink]', rawurlencode( $shortlink ), $link );

					$link = str_replace( '[thumbnail]', rawurlencode( $thumb ), $link );

				} else {

					$link = str_replace( '[permalink]', $permalink, $link );

					$link = str_replace( '[shortlink]', $shortlink, $link );

					$link = str_replace( '[thumbnail]', $thumb, $link );

				}

				if ( $patterns[ $network ]['encode'] ) {

					$link = str_replace( '[title]', rawurlencode( $title ), $link );

					$link = str_replace( '[excerpt]', rawurlencode( $excerpt ), $link );

				} else {

					$link = str_replace( '[title]', $title, $link );

					$link = str_replace( '[excerpt]', $excerpt, $link );

				}
			}

			$output .= sprintf(
				'<li class="li-%s"><a href="%s" title="%s" class="%s" data-toggle="tooltip" data-placement="top">%s</a></li>',
				esc_attr( $network ),
				esc_url( $link ),
				esc_html( $button['label'] ),
				esc_attr( $class ),
				themeblvd_get_icon( themeblvd_get_icon_class( $patterns[ $network ]['icon'], array( 'fa-fw' ) ) )
			);

		}

		$output .= '</ul><!-- .tb-share -->';

	}

	/**
	 * Filters the final HTML output for a post's
	 * share buttons.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output  Final HTML output.
	 * @param string          ID of current post in the loop.
	 * @param array  $buttons Share buttons configured from theme options to include.
	 */
	$output = apply_filters( 'themeblvd_blog_share', $output, get_the_ID(), $buttons );

	if ( $echo ) {

		echo $output;

	} else {

		return $output;

	}

}

/**
 * Get a related posts block.
 *
 * Related posts are queried by a shared taxonomy
 * term. The taxonomy used for this is passed
 * in from the $args.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type int          $post_id      ID of current post.
 *     @type string       $post_type    Current post's type, like `post` or `portfolio_item`.
 *     @type string       $style        Display style, `list` or `grid`.
 *     @type int          $columns      If $style == `list`, number of columns to split them into.
 *     @type int          $grid_columns If $style == `grid`, number of columns.
 *     @type int          $grid_rows    If $style == `grid`, number of rows.
 *     @type int          $total        Number of posts to query.
 *     @type string       $related_by   Which taxonomy to look for shared terms, like `category`.
 *     @type string       $thumbs       Crop size of featured images.
 *     @type bool         $meta         Whether to include meta info for each post.
 *     @type string|array $query        Optional. An overriding query.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_related_posts( $args = array() ) {

	/**
	 * Filters the default arguments for related
	 * posts blocks.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array $args {
	 *     Block arguments.
	 *
	 *     @type int          $post_id      ID of current post.
	 *     @type string       $post_type    Current post's type, like `post` or `portfolio_item`.
	 *     @type string       $style        Display style, `list` or `grid`.
	 *     @type int          $columns      If $style == `list`, number of columns to split them into.
	 *     @type int          $grid_columns If $style == `grid`, number of columns.
	 *     @type int          $grid_rows    If $style == `grid`, number of rows.
	 *     @type int          $total        Number of posts to query.
	 *     @type string       $related_by   Which taxonomy to look for shared terms, like `category`.
	 *     @type string       $thumbs       Crop size of featured images.
	 *     @type bool         $meta         Whether to include meta info for each post.
	 *     @type string|array $query        Optional. An overriding query.
	 * }
	 */
	$defaults = apply_filters( 'themeblvd_related_posts_args', array(
		'post_id'      => themeblvd_config( 'id' ),
		'post_type'    => 'post',
		'columns'      => 2,
		'grid_columns' => 3,
		'grid_rows'    => 2,
		'total'        => 6,
		'related_by'   => themeblvd_get_option( 'single_related_posts', null, 'tag' ),
		'style'        => themeblvd_get_option( 'single_related_posts_style', null, 'list' ),
		'thumbs'       => 'smaller',
		'meta'         => true,
		'query'        => '',
	) );

	$args = wp_parse_args( $args, $defaults );

	if ( get_post_type() !== $args['post_type'] ) {

		return '';

	}

	if ( ! $args['query'] ) {

		$args['query'] = array(
			'post_type'           => $args['post_type'],
			'post__not_in'        => array( $args['post_id'] ),
			'orderby'             => 'rand',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $args['total'],
		);

		if ( 'tag' === $args['related_by'] ) {

			$args['related_by'] = 'post_tag';

		}

		$terms = array();

		$term_obj = get_the_terms( $args['post_id'], $args['related_by'] );

		if ( $term_obj && ! is_wp_error( $term_obj ) ) {
			foreach ( $term_obj as $term ) {
				$terms[] = $term->term_id;
			}
		}

		if ( $terms ) {

			$args['query']['tax_query'] = array(
				array(
					'taxonomy'  => $args['related_by'],
					'field'     => 'term_id',
					'terms'     => $terms,
				),
			);

		}

		/**
		 * Filters the generated query for retrieving
		 * related posts from WP_Query.
		 *
		 * @since @@name-framework 2.5.0
		 *
		 * @param array       Query parameters to pass to WP_Query.
		 * @param array $args {
		 *     Block arguments.
		 *
		 *     @type int          $post_id      ID of current post.
		 *     @type string       $post_type    Current post's type, like `post` or `portfolio_item`.
		 *     @type string       $style        Display style, `list` or `grid`.
		 *     @type int          $columns      If $style == `list`, number of columns to split them into.
		 *     @type int          $grid_columns If $style == `grid`, number of columns.
		 *     @type int          $grid_rows    If $style == `grid`, number of rows.
		 *     @type int          $total        Number of posts to query.
		 *     @type string       $related_by   Which taxonomy to look for shared terms, like `category`.
		 *     @type string       $thumbs       Crop size of featured images.
		 *     @type bool         $meta         Whether to include meta info for each post.
		 *     @type string|array $query        Optional. An overriding query.
		 * }
		 */
		$args['query'] = apply_filters( 'themeblvd_related_posts_query', $args['query'], $args );

	}

	$output = '<section class="tb-related-posts">';

	$output .= sprintf(
		'<h2 class="related-posts-title">%s</h2>',
		themeblvd_get_local( 'related_posts' )
	);

	$output .= '<div class="inner">';

	if ( 'list' === $args['style'] ) {

		$output .= themeblvd_get_mini_post_list( $args, $args['thumbs'], $args['meta'] );

	} else {

		$args['columns'] = $args['grid_columns'];

		$args['rows'] = $args['grid_rows'];

		$output .= themeblvd_get_small_post_grid( $args );

	}

	$output .= '</div><!-- .inner (end) -->';

	$output .= '</section><!-- .tb-related-posts (end) -->';

	/**
	 * Filters the final HTML output for a related
	 * posts block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type int          $post_id      ID of current post.
	 *     @type string       $post_type    Current post's type, like `post` or `portfolio_item`.
	 *     @type string       $style        Display style, `list` or `grid`.
	 *     @type int          $columns      If $style == `list`, number of columns to split them into.
	 *     @type int          $grid_columns If $style == `grid`, number of columns.
	 *     @type int          $grid_rows    If $style == `grid`, number of rows.
	 *     @type int          $total        Number of posts to query.
	 *     @type string       $related_by   Which taxonomy to look for shared terms, like `category`.
	 *     @type string       $thumbs       Crop size of featured images.
	 *     @type bool         $meta         Whether to include meta info for each post.
	 *     @type string|array $query        Optional. An overriding query.
	 * }
	 * @param array  $query Query parameters to pass to WP_Query.
	 */
	return apply_filters( 'themeblvd_related_posts', $output, $args, $args['query'] );

}

/**
 * Display related posts
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_related_posts() docs.
 */
function themeblvd_related_posts( $args = array() ) {

	echo themeblvd_get_related_posts( $args );

}

/**
 * Get a date block, which can replace a featured
 * image on posts lists.
 *
 * @since @@name-framework 2.5.0
 *
 * @param int $post_id ID of current post.
 */
function themeblvd_get_date_block( $post_id = 0 ) {

	if ( ! $post_id ) {

		$post_id = get_the_ID();

	}

	$output  = '<div class="tb-date-block">';

	$output .= sprintf(
		'<span class="bg-primary day">%s</span>',
		esc_html( get_the_date( 'd', $post_id ) )
	);

	$output .= sprintf(
		'<span class="month">%s</span>',
		esc_html( get_the_date( 'M', $post_id ) )
	);

	$output .= '</div><!-- .tb-date-block (end) -->';

	/**
	 * Filters a date block, which can replace a featured
	 * image on posts lists.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output  Final HTML output.
	 * @param int    $post_id ID of current post.
	 */
	return apply_filters( 'themeblvd_date_block', $output, $post_id );

}

/**
 * Display a date block, which can replace a featured
 * image on posts lists.
 *
 * @since @@name-framework 2.5.0
 *
 * @param int $post_id ID of current post.
 */
function themeblvd_date_block( $post_id = 0 ) {

	echo themeblvd_get_date_block( $post_id );

}

/**
 * Get the title and taxonomy term of a
 * post.
 *
 * By default, this is meant to be used with the
 * post showcase, to display when a featurd image
 * is hovered on.
 *
 * @since @@name-framework 2.6.0
 *
 * @param int    $post_id ID of current post.
 * @param string $tax     Taxonomy to pull current term from.
 */
function themeblvd_get_item_info( $post_id = 0, $tax = '' ) {

	if ( ! $post_id ) {

		$post_id = get_the_ID();

	}

	$post_type = get_post_type( $post_id );

	$output = '<span class="item-title">';

	$output .= sprintf(
		'<span class="title">%s</span>',
		esc_html( get_the_title( $post_id ) )
	);

	if ( ! $tax ) {

		$tax = 'category';

		if ( 'portfolio_item' === $post_type ) {

			$tax = 'portfolio';

		}
	}

	/**
	 * Filters the taxonomy used to determine the
	 * item info subtitle.
	 *
	 * @since @@name-framework 2.6.0
	 *
	 * @param string $tax       Taxonomy being used to get terms for.
	 * @param string $post_type Post typ of current post.
	 * @param int    $post_id   ID of current post.
	 */
	$tax = apply_filters( 'themeblvd_item_info_tax', $tax, $post_type, $post_id );

	/**
	 * Filters the subtitle used in the item info.
	 *
	 * Note: By default the item info is used for
	 * displaying information about a post when
	 * the featured image is hovered on in a post
	 * showcase.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string            Subtitle text to display below title.
	 * @param int    $post_id   ID of current post.
	 * @param string $post_type Post typ of current post.
	 * @param string $tax       Taxonomy originally used to pull terms from.
	 */
	$subtitle = apply_filters( 'themeblvd_item_info_subtitle', '', $post_id, $post_type, $tax );

	if ( ! $subtitle ) {

		$terms_obj = get_the_terms( $post_id, $tax );

		if ( $terms_obj ) {

			$terms = array();

			foreach ( $terms_obj as $term ) {

				$terms[] = $term->name;

			}

			$subtitle = esc_html( implode( $terms, ', ' ) );

		}
	}

	$output .= sprintf( '<span class="cat">%s</span>', $subtitle );

	$output .= '</span>';

	/**
	 * Filters the final HTML output for an item
	 * info block, which contains the title and
	 * a taxonomy term.
	 *
	 * @since @@name-framework 2.6.0
	 *
	 * @param string $output  Final HTML output.
	 * @param int    $post_id ID of current post.
	 * @param string $tax     Taxonomy to pull current term from.
	 */
	return apply_filters( 'themeblvd_item_info', $output, $post_id, $tax );

}

/**
 * Display the title and taxonomy term of a
 * post.
 *
 * @since @@name-framework 2.6.0
 */
function themeblvd_item_info( $post_id = 0, $tax = '' ) {

	echo themeblvd_get_item_info( $post_id, $tax );

}
