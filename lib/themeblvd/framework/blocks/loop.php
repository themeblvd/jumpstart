<?php
/**
 * Frontend Blocks: Post Loops
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.5.0
 */

/**
 * Display secondary post loops.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $args {
 *     Post loop arguments.
 *
 *     @type string       $title          Optional. Title to display before post loop.
 *     @type string       $context        Post display method, `blog`, `list`, `grid`, `showcase` or `small-grid`.
 *     @type string       $display        How to display, `grid`, `list`, `showcase`, `filter`, `masonry`, `masonry_filter`, `paginated` or `masonry_paginated`.
 *     @type string       $source         Source of post query, `category`, `tag` or `query`.
 *     @type array        $categories     Category ID's to include posts from.
 *     @type string       $category_name  Force `category_name` parameter of WP_query.
 *     @type string       $cat            Force `cat` parameter of WP_query.
 *     @type string       $tag            Force `tag` parameter of WP_query.
 *     @type string|int   $posts_per_page Total number of posts to query.
 *     @type string       $orderby        Used for `orderby` parameter of WP_Query.
 *     @type string       $order          Used for `order` parameter of WP_Query.
 *     @type string|int   $offset         Used for `offset` parameter of WP_Query.
 *     @type string|int   $columns        If $context == `grid`, number of columns.
 *     @type string|int   $rows           If $context == `grid`, number of rows.
 *     @type string|int   $slides         If $context == `grid` && $display == `slider`, number of slides.
 *     @type string       $pages          Comma-separated list of page slugs to query.
 *     @type string|array $query          Custom query, overrides all else.
 *     @type string       $thumbs         Size of featured images, `default`, `small`, `full`, `hide`; `small` isn't always supported.
 *     @type string       $content        If $context == `blog`, show full content or excerpt for each post.
 *     @type string|bool  $titles         If $context == `showcase`, whether to show post titles.
 *     @type string|bool  $excerpt        If $context == `grid`, whether to show excerpts.
 *     @type string|bool  $meta           If $context == `blog`, `list` or `grid`, whether to show meta.
 *     @type string|bool  $sub_meta       If $context == `blog`, whether to show sub meta below posts.
 *     @type string       $more           If $context == `list`, how to show read-more, `text`, `button` or `none`.
 *     @type string       $more_text      If $context == `list` and $more == `text` or `button`, the link text to show.
 *     @type string|int   $timeout        If $context == `grid` && $display == `slider`, seconds between transitions.
 *     @type string|bool  $nav            If $context == `grid` && $display == `slider`, whether to show slider controls.
 *     @type string       $filter         If $display == `filter` or `masonry_filter`, end-user will filter posts by this.
 *     @type string       $filter_max     If $display == `filter` or `masonry_filter`, use this as $posts_per_page in query.
 *     @type string       $crop           If $context == `grid` or `showcase`, custom featured image crop size.
 *     @type string       $gutters        If $context == `showcase`, whether to have spacing between thumbnails.
 *     @type string       $part           Custom templat part override; by default will be determined by $context.
 *     @type bool         $shortcode      Whether this loop originated from a shortcode.
 *     @type bool         $element        Whether this loop originated from a layout builder element.
 *     @type string       $class          CSS classes to add.
 *     @type int          $max_width      Parent container max width; this should be set if $lement is true.
 *     @type bool         $wp_query       Whether to pull from primary WordPress query; used as an override for using NOT as a secondary query.
 *     @type string       $msg_no_posts   Message to display when no posts are queried.
 * }
 */
function themeblvd_loop( $args = array() ) {

	global $wp_query;

	global $post;

	global $more;

	$args = wp_parse_args( $args, array(
		'title'          => '',
		'display'        => 'paginated',
		'context'        => '',
		'source'         => '',
		'categories'     => array( 'all' => 1 ),
		'category_name'  => '',
		'cat'            => '',
		'tag'            => '',
		'posts_per_page' => '6',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'offset'         => '0',
		'columns'        => '',
		'rows'           => '',
		'slides'         => '3',
		'pages'          => '',
		'query'          => '',
		'thumbs'         => '',
		'content'        => '',
		'titles'         => '',
		'excerpt'        => '',
		'meta'           => '',
		'sub_meta'       => '',
		'more'           => '',
		'more_text'      => '',
		'timeout'        => '3',
		'nav'            => '1',
		'filter'         => 'category',
		'filter_max'     => '-1',
		'crop'           => '',
		'gutters'        => '',
		'part'           => '',
		'shortcode'      => false,
		'element'        => false,
		'class'          => '',
		'max_width'      => 0,
		'wp_query'       => false,
		'msg_no_posts'   => themeblvd_get_local( 'archive_no_posts' ),
	) );

	// Is this a paginated post list?
	$paginated = false;

	if ( $args['display'] == 'paginated' || $args['display'] == 'masonry_paginated' ) {

		$paginated = true;

	}

	// Are we using isotope?
	$isotope = false;

	if ( in_array( $args['display'], array( 'filter', 'masonry', 'masonry_filter', 'masonry_paginated' ) ) ) {

		$isotope = true;

	}

	/*
	 * In what context are these posts being displayed?
	 *
	 * If the post list is in secondary context, like
	 * a shortcode, widget, etc, and you don't want the
	 * $context to be overridden with generic, top-level
	 * conditionals, you need to pass in a $context here.
	 */
	$context = $args['context'];

	if ( ! $context ) {

		$context = themeblvd_config( 'mode' );

	}

	if ( ! $context ) {

		$context = 'list'; // Default.

	}

	/*
	 * What template part should we include?
	 *
	 * Note: $part will get passed into themeblvd_get_template_part().
	 * So, if you want to change the actual file name, you'd
	 * filter onto themeblvd_template_part.
	 */
	if ( $args['part'] ) {

		$part = $args['part'];

	} else {

		$part = $context;

		if ( $paginated && 'search_results' !== $context ) {

			$part .= '_paginated';

		}
	}

	$post_class = '';

	/*
	 * Set attributes that we can access from template
	 * part files.
	 *
	 * This works by setting global attributes with
	 * themeblvd_set_att(), which are then retrieved
	 * from template part files with themeblvd_get_att().
	 */
	switch ( $context ) {

		/*
		 * Post Display: Blog
		 */
		case 'blog':
			// Setup featured images.
			if ( ! $args['thumbs'] || 'default' === $args['thumbs'] ) {

				$thumbs = themeblvd_get_option( 'blog_thumbs', null, 'full' );

			} else {

				$thumbs = $args['thumbs'];

			}

			if ( 'hide' === $thumbs ) {

				themeblvd_set_att( 'thumbs', false );

			} else {

				themeblvd_set_att( 'thumbs', $thumbs );

				$post_class .= ' has-thumbnail';

			}

			// Setup meta info.
			if ( ! $args['meta'] || 'default' === $args['meta'] ) {

				$meta = themeblvd_get_option( 'blog_meta', null, 'show' );

			} else {

				$meta = $args['meta'];

			}

			if ( 'show' === $meta ) {

				themeblvd_set_att( 'show_meta', true );

				$post_class .= ' has-meta';

			} else {

				themeblvd_set_att( 'show_meta', false );

			}

			// Setup sub/secondary meta info.
			if ( ! $args['sub_meta'] || 'default' === $args['sub_meta'] ) {

				$sub_meta = themeblvd_get_option( 'blog_sub_meta', null, 'show' );

			} else {

				$sub_meta = $args['sub_meta'];

			}

			if ( 'show' === $sub_meta ) {

				themeblvd_set_att( 'show_sub_meta', true );

				$post_class .= ' has-sub-meta';

			} else {

				themeblvd_set_att( 'show_sub_meta', false );

			}

			// Determine whether to show content or excerpt.
			if ( ! $args['content'] || 'default' === $args['content'] ) {

				themeblvd_set_att( 'content', themeblvd_get_option( 'blog_content', null, 'excerpt' ) );

			} else {

				themeblvd_set_att( 'content', $args['content'] );

			}

			break;

		/*
		 * Post Display: List
		 */
		case 'list':
			// Setup featured images.
			if ( ! $args['thumbs'] || 'default' === $args['thumbs'] ) {

				$thumbs = themeblvd_get_option( 'list_thumbs', null, 'full' );

			} else {

				$thumbs = $args['thumbs'];

			}

			if ( 'hide' === $thumbs ) {

				themeblvd_set_att( 'thumbs', false );

			} else {

				themeblvd_set_att( 'thumbs', $thumbs );

				if ( 'date' === $thumbs ) {

					$post_class .= ' has-date';

				} else {

					$post_class .= ' has-thumbnail';

				}
			}

			// Setup meta info.
			if ( ! $args['meta'] || 'default' === $args['meta'] ) {

				$meta = themeblvd_get_option( 'list_meta', null, 'show' );

			} else {

				$meta = $args['meta'];

			}

			if ( 'show' === $meta ) {

				themeblvd_set_att( 'show_meta', true );

				$post_class .= ' has-meta';

			} else {

				themeblvd_set_att( 'show_meta', false );

			}

			// Setup read-more link or button.
			if ( ! $args['more'] || 'default' === $args['more'] ) {

				$more = themeblvd_get_option( 'list_more', null, 'text' );

				$text = themeblvd_get_option( 'list_more_text', null, themeblvd_get_local( 'read_more' ) );

			} else {

				$more = $args['more'];

				$text = $args['more_text'];

				if ( ! $text ) {

					$text = themeblvd_get_option( 'list_more_text', null, themeblvd_get_local( 'read_more' ) );

				}
			}

			if ( 'hide' === $more ) {

				themeblvd_set_att( 'more', false );

			} else {

				themeblvd_set_att( 'more', $more );

				themeblvd_set_att( 'more_text', $text );

			}

			break;

		/*
		 * Post Display: Grid
		 */
		case 'grid':
			// Setup featured images.
			if ( ! $args['thumbs'] || 'default' === $args['thumbs'] ) {

				$thumbs = themeblvd_get_option( $context . '_thumbs', null, 'full' );

			} else {

				$thumbs = $args['thumbs'];

			}

			if ( 'hide' === $thumbs ) {

				themeblvd_set_att( 'thumbs', false );

			} else {

				themeblvd_set_att( 'thumbs', $thumbs );

			}

			// Setup meta info.
			if ( ! $args['meta'] || 'default' === $args['meta'] ) {

				$meta = themeblvd_get_option( 'grid_meta', null, 'show' );

			} else {

				$meta = $args['meta'];

			}

			if ( 'show' === $meta ) {

				themeblvd_set_att( 'show_meta', true );

			} else {

				themeblvd_set_att( 'show_meta', false );

			}

			// Determine whether to show excerpts.
			if ( ! $args['excerpt'] || 'default' === $args['excerpt'] ) {

				$excerpt = themeblvd_get_option( 'grid_excerpt', null, 'show' );

			} else {

				$excerpt = $args['excerpt'];

			}

			if ( 'show' === $excerpt ) {

				themeblvd_set_att( 'excerpt', true );

			} else {

				themeblvd_set_att( 'excerpt', false );

			}

			// Setup read-more link or button, and text to use.
			if ( ! $args['more'] || 'default' === $args['more'] ) {

				themeblvd_set_att( 'more', themeblvd_get_option( 'grid_more', null, 'text' ) );

				themeblvd_set_att( 'more_text', themeblvd_get_option( 'grid_more_text', null, themeblvd_get_local( 'read_more' ) ) );

			} else {

				themeblvd_set_att( 'more', $args['more'] );

				$text = $args['more_text'];

				if ( ! $text ) {

					$text = themeblvd_get_option( 'list_more_text', null, themeblvd_get_local( 'read_more' ) );

				}

				themeblvd_set_att( 'more_text', $text );

			}

			// Setup number of columns (i.e. posts per row).
			if ( $args['columns'] ) {

				$columns = $args['columns'];

			} else {

				$columns = themeblvd_get_option( 'grid_columns', null, '3' );

			}

			$columns = themeblvd_set_att( 'columns', intval( $columns ) );

			// Setup grid class, used for each post.
			$post_class = sprintf( 'col %s', themeblvd_grid_class( intval( $columns ) ) );

			themeblvd_set_att( 'size', $post_class ); // Backwards compatibility.

			if ( themeblvd_get_att( 'thumbs' ) ) {

				$post_class .= ' has-thumb';

			}

			if ( themeblvd_get_att( 'show_meta' ) ) {

				$post_class .= ' has-meta';

			}

			if ( themeblvd_get_att( 'excerpt' ) ) {

				$post_class .= ' has-excerpt';

			}

			if ( 'button' === themeblvd_get_att( 'more' ) || 'text' === themeblvd_get_att( 'more' ) ) {

				$post_class .= ' has-more';

			}

			if ( $args['crop'] ) {

				$crop = $args['crop'];

			} else {

				$crop = themeblvd_get_option( 'grid_crop', null, 'tb_grid' );

			}

			themeblvd_set_att( 'crop', $crop );

			// Setup atts for image placeholders, when no image exists.
			$crop = themeblvd_get_image_sizes( $crop );

			themeblvd_set_att( 'crop_w', $crop['width'] );

			themeblvd_set_att( 'crop_h', $crop['height'] );

			break;

		/*
		 * Post Display: Showcase
		 */
		case 'showcase':
			// Determine whether post titles show.
			if ( ! $args['titles'] || 'default' === $args['titles'] ) {

				$titles = themeblvd_get_option( 'showcase_titles', null, 'show' );

			} else {

				$titles = $args['titles'];

			}

			if ( 'show' === $titles ) {

				themeblvd_set_att( 'titles', true );

			} else {

				themeblvd_set_att( 'titles', false );

			}

			// Determine whether excerpts show.
			if ( ! $args['excerpt'] || 'default' === $args['excerpt'] ) {

				$excerpt = themeblvd_get_option( 'showcase_excerpt', null, 'hide' );

			} else {

				$excerpt = $args['excerpt'];

			}

			if ( 'show' === $excerpt ) {

				themeblvd_set_att( 'excerpt', true );

			} else {

				themeblvd_set_att( 'excerpt', false );

			}

			// Determine whether gutters show between posts.
			if ( ! $args['gutters'] || 'default' === $args['gutters'] ) {

				$gutters = themeblvd_get_option( 'showcase_gutters', null, 'show' );

			} else {

				$gutters = $args['gutters'];

			}

			// Determine featured image crop size.
			if ( $args['crop'] ) {

				$crop = $args['crop'];

			} else {

				$crop = themeblvd_get_option( 'showcase_crop', null, 'tb_large' );

			}

			themeblvd_set_att( 'crop', $crop );

			// Setup atts for image placeholders, when no image exists.
			themeblvd_set_att( 'crop_w', '640' );

			themeblvd_set_att( 'crop_h', '360' );

			// Setup number of columns (i.e. posts per row).
			if ( $args['columns'] ) {

				$columns = $args['columns'];

			} else {

				$columns = themeblvd_get_option( 'showcase_columns', null, '3' );

			}

			$columns = themeblvd_set_att( 'columns', intval( $columns ) );

			// Setup grid class for each post.
			$post_class = sprintf( 'col %s', themeblvd_grid_class( intval( $columns ) ) );

			$post_class .= ' showcase-item';

			if ( themeblvd_get_att( 'titles' ) ) {

				$post_class .= ' has-title';

			}

			if ( themeblvd_get_att( 'excerpt' ) ) {

				$post_class .= ' has-excerpt';

			}

			break;

		/*
		 * Post Display: Small Post Grid
		 */
		case 'small-grid':
			// Setup crop size for featured images.
			$crop = themeblvd_get_image_sizes( $args['crop'] );

			themeblvd_set_att( 'crop_w', $crop['width'] );

			themeblvd_set_att( 'crop_h', $crop['height'] );

			// Setup number of columns (i.e. posts per row).
			$columns = '3';

			if ( $args['columns'] ) {

				$columns = $args['columns'];

			}

			$columns = themeblvd_set_att( 'columns', intval( $columns ) );

			// Post class to determine column width.
			$post_class = sprintf( 'col %s', themeblvd_grid_class( intval( $columns ) ) );


	}

	/*
	 * Make sure that themeblvd_get_featured_image never
	 * thinks this is for the single post.
	 */
	themeblvd_set_att( 'location', 'primary' );

	/**
	 * Fires after all global attributes have been set
	 * with themeblvd_set_att().
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array $args All post loop arguments, see docs for themeblvd_loop().
	 */
	do_action( "themeblvd_loop_set_{$context}_atts", $args );

	/*
	 * Determine post query
	 *
	 * If we're pointing to this function from themeblvd_the_loop(),
	 * we'll pull from the primary WordPress query, which is already
	 * established.
	 *
	 * If we're dealing with a paginted query, we can use the
	 * Theme_Blvd_Query class. If this is one of the page templates
	 * which display posts, then our "second query" will already exist.
	 * If the "second query" doesn't exist, when we can generate it
	 * with this class.
	 *
	 * If there's no pagination, we can go ahead use themeblvd_get_posts_args()
	 * to produce our query.
	 */
	if ( $args['wp_query'] ) {

		// Pull from primary query.
		$posts = $wp_query;

	} else {

		if ( $paginated ) {

			/*
			 * There can only be one "second query"; so if one
			 * already exists, that's our boy.
			 */
			$query_args = themeblvd_get_second_query();

			if ( $query_args ) {

				$doing_the_second_loop = true;

			} else {

				if ( in_array( $context, array( 'grid', 'small-grid', 'showcase' ) ) ) {

					$posts_per_page = '-1';

					if ( 'masonry_paginated' === $args['display'] && $args['posts_per_page'] ) {

						$posts_per_page = $args['posts_per_page'];

					} elseif ( 'masonry_filter' === $args['display'] && $args['filter_max'] ) {

						$posts_per_page = $args['filter_max'];

					} elseif ( 'paginated' === $args['display'] && $args['rows'] ) {

						$posts_per_page = intval( $args['rows'] ) * $columns;

					}

					$args['posts_per_page'] = $posts_per_page;

				}

				/*
				 * Set the second query in global scopt, which we only
				 * do for paginated queries.
				 */
				$query_args = themeblvd_set_second_query( $args, $context );

			}
		} else {

			// Standard query for non-paginated posts.
			$query_args = themeblvd_get_posts_args( $args, $context );

		}

		/**
		 * Filters the query arguments for secondary post loops,
		 * which are passed to WP_Query.
		 *
		 * @since @@name-framework 2.0.0
		 *
		 * @param array  $query_args Query arguments passed to WP_Query.
		 * @param array  $args       Original arguments for post loop; see docs for themeblvd_loop().
		 * @param string $context    Post display method, `blog`, `list`, `grid`, `showcase` or `small-grid`.
		 */
		$query_args = apply_filters( 'themeblvd_posts_args', $query_args, $args, $context );

		// If it's a post grid slider, pass it on with the query.
		if ( 'grid' === $context && 'slider' === $args['display'] ) {

			$args['query'] = $query_args;

			themeblvd_set_att( 'class', $post_class );

			themeblvd_grid_slider( $args );

			return;

		}

		$posts = new WP_Query( $query_args );

	}

	/*
	 * Setup wrapping CSS classes.
	 *
	 * These CSS classes get used on the wrapping markup of
	 * the entire post loop.
	 */
	$class = array();

	if ( 'blog' === $context ) {

		$class[] = 'blog';

		if ( $paginated ) {

			$class[] = 'paginated';

		}
	} elseif ( 'list' === $context || 'grid' === $context || 'showcase' === $context ) {

		$class[] = 'post_' . $context;

		if ( 'grid' === $context || 'showcase' === $context ) {

			$class[] = 'themeblvd-gallery';

		}

		if ( $paginated && 'showcase' !== $context ) {

			$class[] = 'post_' . $context . '_paginated';

		}

		if ( 'showcase' === $context && ! empty( $gutters ) ) {

			if ( 'show' === $gutters ) {

				$class[] = 'has-gutters';

			} else {

				$class[] = 'no-gutters';

			}
		}
	}

	if ( ! empty( $columns ) ) {

		$class[] = 'columns-' . $columns;

	}

	if ( $isotope ) {

		$class[] = 'tb-isotope';

	}

	if ( 'filter' === $args['display'] || 'masonry_filter' === $args['display'] ) {

		$class[] = 'tb-filter';

	}

	if ( 'masonry' === $args['display'] || 'masonry_paginated' === $args['display'] || 'masonry_filter' === $args['display'] ) {

		$class[] = 'tb-masonry';

	}

	if ( $args['class'] ) {

		$class[] = $args['class'];

	}

	if ( $args['shortcode'] ) {

		$class[] = 'loop-shortcode';

	}

	if ( $args['element'] ) {

		$class[] = 'loop-element';

	}

	/**
	 * Filters the wrapping CSS classes for a secondary
	 * post loop.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param array  $class   CSS classes to add to wrapping markup.
	 * @param array  $args    Original arguments for post loop; see docs for themeblvd_loop().
	 * @param string $context Post display method, `blog`, `list`, `grid`, `showcase` or `small-grid`.
	 */
	$class = apply_filters( 'themeblvd_posts_class', $class, $args, $context );

	$class = implode( ' ', $class );

	// Start output.
	echo '<div class="' . esc_attr( $class ) . '">';

	/*
	 * Output title and content of current page, if this is a page
	 * template displaying secondary loop.
	 *
	 * This gets used with page templates that are meant to display
	 * posts, in order to display page information before the
	 * secondary post loop making up the page template.
	 */
	if ( ! empty( $doing_the_second_loop ) ) {

		/**
		 * Filters the secondary loop page templates, which show
		 * page information at the top, before the posts loop
		 * displays.
		 *
		 * @since @@name-framework 2.7.0
		 *
		 * @param array       Page templates to show page info before post loop.
		 * @param array $args Original arguments for post loop; see docs for themeblvd_loop().
		 */
		$templates = apply_filters( 'themeblvd_posts_page_info_templates', array( 'template_blog.php', 'template_list.php', 'template_grid.php', 'template_showcase.php' ), $args );

		if ( is_page_template( $templates ) ) {

			themeblvd_page_info();

		}
	}

	/**
	 * Fires at the point when page info can be printed out
	 * before the secondary posts loop.
	 *
	 * Use this if you need to add content at the particular
	 * place where default secondary post loop page templates
	 * display their page info, for custom scenarios.
	 *
	 * @param array $args Original arguments for post loop; see docs for themeblvd_loop().
	 */
	do_action( 'themeblvd_posts_page_info', $args );

	// Optional title passed in from shortcodes and builder elements.
	if ( $args['title'] ) {

		printf( '<h3 class="title">%s</h3>', themeblvd_kses( $args['title'] ) );

	}

	// Add filtering navigation.
	if ( 'filter' === $args['display'] || 'masonry_filter' === $args['display'] ) {

		themeblvd_filter_nav( $posts, $args['filter'] );

	}

	if ( $posts->have_posts() ) {

		if ( ! $args['wp_query'] ) {

			themeblvd_set_att( 'doing_second_loop', true );

		}

		/**
		 * Fires before the loop starts and before the HTML
		 * has been opened.
		 *
		 * @since @@name-framework 2.0.0
		 *
		 * @param array $args Original arguments for post loop; see docs for themeblvd_loop().
		 */
		do_action( "themeblvd_post_{$context}_before_loop", $args );

		printf( '<div class="post-wrap %s-wrap">', $context );

		$counter = themeblvd_set_att( 'counter', 1 );

		$total = $posts->post_count;

		// Setup posts per column.
		$ppc = 0;

		if ( 'grid' !== $context && 'small-grid' !== $context && 'showcase' !== $context && intval( $args['columns'] ) >= 2 ) {

			$ppc = ceil( $total / intval( $args['columns'] ) );

		}

		$ppc_class = themeblvd_grid_class( intval( $args['columns'] ) );

		// Add loading icon, when isotope script has to load posts.
		if ( $isotope ) {

			themeblvd_loader();

		}

		// When displaying any grid, open the first row.
		if ( 'grid' === $context || 'small-grid' === $context || 'showcase' === $context || 'list' === $context || $ppc ) {

			themeblvd_open_row();

		}

		// If posts are listed in columns, open first column.
		if ( $ppc ) {

			printf( '<div class="%s">', $ppc_class );

		}

		/**
		 * Fires before the loop has started, but after HTML has
		 * been opened.
		 *
		 * @since @@name-framework 2.0.0
		 *
		 * @param array $args Original arguments for post loop; see docs for themeblvd_loop().
		 */
		do_action( "themeblvd_post_{$context}_start_loop", $args );

		// Start the loop.
		while ( $posts->have_posts() ) {

			$posts->the_post();

			$more = 0;

			// Setup CSS classes for individual post.
			$current_post_class = $post_class;

			if ( $isotope ) {

				$current_post_class .= ' iso-item';

				if ( 'filter' === $args['display'] || 'masonry_filter' === $args['display'] ) {

					$current_post_class .= ' ' . themeblvd_get_filter_val( $args['filter'] );

				}
			}

			themeblvd_set_att( 'class', trim( $current_post_class ) );

			/*
			 * Display post.
			 *
			 * In order to display the individual post, we'll include
			 * the proper template part file.
			 */
			themeblvd_get_template_part( $part );

			/*
			 * For displaying any grid, if this is the last post in
			 * a row, but not the very last post, close the current
			 * row and open a new one.
			 *
			 * Note: If this were the very last post in the loop, we
			 * don't need to do anything because the final row will
			 * get closed AFTER the loop has ended.
			 *
			 * Note: When using isotope script, we do not separate
			 * posts into rows.
			 */
			if ( ! $isotope && ( 'grid' == $context || 'small-grid' == $context || 'showcase' == $context ) && $counter % $columns == 0 && $total != $counter ) {

				// Close current row.
				themeblvd_close_row();

				// Open the next row.
				themeblvd_open_row();

			}

			// For posts devided into columns.
			if ( $ppc && $counter % $ppc == 0 && $total != $counter ) {

				// Close current column.
				printf( '</div><!-- .%s (end) -->', $ppc_class );

				// Open new column.
				printf( '<div class="%s">', $ppc_class );

			}

			// Increment the counter with global template attribute accounted for.
			$counter = themeblvd_set_att( 'counter', $counter + 1 );

		}

		// If posts are listed in columns, close last column.
		if ( $ppc ) {

			printf( '</div><!-- .%s (end) -->', $ppc_class );

		}

		// For grid displays, close the final row.
		if ( 'grid' == $context || 'small-grid' == $context || 'showcase' == $context || 'list' == $context || $ppc ) {

			themeblvd_close_row();

		}

		/**
		 * Fires after the loop has ended, but before HTML has
		 * been closed.
		 *
		 * @since @@name-framework 2.0.0
		 *
		 * @param array $args Original arguments for post loop; see docs for themeblvd_loop().
		 */
		do_action( "themeblvd_post_{$context}_end_loop", $args );

		echo "</div><!-- .{$context}-wrap (end) -->";

		/**
		 * Fires after the loop has ended and HTML has been closed.
		 *
		 * @since @@name-framework 2.0.0
		 *
		 * @param array $args Original arguments for post loop; see docs for themeblvd_loop().
		 */
		do_action( "themeblvd_post_{$context}_after_loop", $args );

		if ( ! $args['wp_query'] ) {

			themeblvd_set_att( 'doing_second_loop', false );

		}
	} else {

		// No posts to display.
		printf( '<p>%s</p>', $args['msg_no_posts'] );

	}

	// Display pagination.
	if ( $paginated && $posts->max_num_pages >= 2 ) {

		themeblvd_pagination( $posts->max_num_pages );

	}

	wp_reset_postdata();

	echo '</div><!-- .*-loop (end) -->';

}

/**
 * Display primary post loop.
 *
 * This is a wrapper function for themeblvd_loop(),
 * which modifies it from displaying a secondary
 * loop, to displaying the primary WordPress loop.
 *
 * This function is called in the default top-level
 * WordPress template files.
 *
 * @since @@name-framework 2.5.0
 */
function themeblvd_the_loop() {

	$mode = themeblvd_config( 'mode' );

	$class = '';

	if ( is_home() ) {

		$class = 'home-loop';

	} elseif ( is_archive() ) {

		$class = 'archive-loop';

		if ( is_category() ) {

			$class .= ' category-loop';

		} elseif ( is_tag() ) {

			$class .= ' tag-loop';

		} elseif ( is_author() ) {

			$class .= ' author-loop';

		} elseif ( is_date() ) {

			$class .= ' date-loop';

		} elseif ( is_tax( 'portfolio' ) || is_tax( 'portfolio_tag' ) ) {

			$class .= ' portfolio-loop';

		}
	}

	$display = '';

	if ( 'grid' === $mode || 'showcase' === $mode ) {

		$display = themeblvd_get_option( $mode . '_display' );

	}

	if ( ! $display ) {

		$display = 'paginated';

	}

	$args = array(
		'display'  => $display,
		'context'  => $mode,
		'class'    => $class,
		'wp_query' => true,
	);

	if ( is_search() ) {

		$args['context'] = 'search_results';

		$args['class'] = 'search-loop';

		$args['msg_no_posts'] = themeblvd_get_local( 'search_no_results' );

	}

	/**
	 * Filters the arguments passed to themeblvd_loop()
	 * from themeblvd_the_loop().
	 *
	 * All displays of the primary WordPress loop from
	 * all template files use themeblvd_loop(), and this
	 * filters the arguments passed to it. See docs for
	 * themeblvd_loop() for more on how to format this
	 * $args array.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array $args Arguments passed to themeblvd_loop().
	 */
	$args = apply_filters( 'themeblvd_the_loop_args', $args );

	/*
	 * Run the loop.
	 */
	themeblvd_loop( $args );

}

/**
 * Get arguments to pass into themeblvd_loop() when
 * using a page template that displays the secondary
 * posts loop.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  string $template Type of page template, `blog`, `list`, `grid`, or `showcase`.
 * @return array  $args     Arguments passed to themeblvd_loop().
 */
function themeblvd_get_template_loop_args( $template ) {

	$page_id = themeblvd_config( 'id' );

	$args = array();

	if ( 'list' === $template || 'blog' === $template ) {

		$args['display'] = 'paginated';

	} elseif ( 'grid' === $template || 'showcase' === $template ) {

		if ( $display = get_post_meta( $page_id, 'tb_display', true ) ) {

			$args['display'] = $display;

		} elseif ( $template == 'grid' ) {

			$args['display'] = themeblvd_get_option( 'grid_display', null, 'paginated' );

		} elseif ( $template == 'showcase' ) {

			$args['display'] = themeblvd_get_option( 'showcase_display', null, 'masonry_paginated' );

		}

		$args['columns'] = get_post_meta( $page_id, 'columns', true );

		$args['rows'] = get_post_meta( $page_id, 'rows', true );

	}

	/**
	 * Filters the arguments passed to themeblvd_loop()
	 * for secondary loop page templates.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array $args    Arguments passed to themeblvd_loop().
	 * @param int   $page_ID ID of current top-level page with template being displayed.
	 */
	return apply_filters( "themeblvd_{$template}_template_loop_args", $args, $page_id );

}

/**
 * Get a post grid slider block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string      $query   Query for posts passed to WP_Query.
 *     @type string      $title   Optional. Title for the block.
 *     @type string      $columns Number of posts per slide.
 *     @type string|bool $nav     Whether to display slider controls.
 *     @type string|int  $timeout Seconds in between auto-rotation; use `0` for not auto-rotation.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_grid_slider( $args ) {

	global $post;

	global $more;

	$more = 0;

	$args = wp_parse_args( $args, array(
		'query'   => null,
		'title'   => '',
		'columns' => '3',
		'nav'     => '1',
		'timeout' => '3',
	) );

	if ( ! $args['query'] ) {

		return esc_html__( 'Error: No query supplied.', '@@text-domain' );

	}

	$class = 'tb-grid-slider tb-block-slider post_grid themeblvd-gallery';

	if ( $args['title'] ) {

		$class .= ' has-title';

	}

	if ( $args['nav'] ) {

		$class .= ' has-nav';

	}

	$output = sprintf(
		'<div class="%s" data-timeout="%s" data-nav="%s">',
		$class,
		$args['timeout'],
		$args['nav']
	);

	if ( $args['title'] ) {

		$output .= sprintf( '<h3 class="title">%s</h3>', $args['title'] );

	}

	$output .= '<div class="tb-grid-slider-inner tb-block-slider-inner slider-inner flexslider">';

	$output .= themeblvd_get_loader();

	/**
	 * Filters arguments supplied to WP_Query when getting
	 * posts for a post slider.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array  $query Query arguments for WP_Query.
	 * @param array  $args  Original arguments passed to themeblvd_get_grid_slider().
	 * @param string        Context of post slider, which is `grid`.
	 */
	$query_args = apply_filters( 'themeblvd_post_slider_args', $args['query'], $args, 'grid' );

	$posts = new WP_Query( $query_args );

	if ( $posts->have_posts() ) {

		$num_per = intval( $args['columns'] );

		$grid_class = themeblvd_grid_class( $num_per );

		$i = themeblvd_set_att( 'counter', 1 );

		$total = $posts->post_count;

		if ( $args['nav'] && $total > $num_per ) {

			$output .= themeblvd_get_slider_controls();

		}

		/**
		 * Fires before the post loop of a post grid slider.
		 *
		 * @since @@name-framework 2.5.0
		 *
		 * @param array $args Original arguments passed to themeblvd_get_grid_slider().
		 */
		do_action( 'themeblvd_grid_slider_before_loop', $args );

		$output .= '<ul class="slides">';

		$output .= '<li class="slide">';

		$output .= themeblvd_get_open_row();

		while ( $posts->have_posts() ) {

			$posts->the_post();

			$more = 0;

			ob_start();

			themeblvd_get_template_part( 'grid_slider' );

			$output .= ob_get_clean();

			if ( $i % $num_per == 0 && $i < $total ) {

				$output .= themeblvd_get_close_row();

				$output .= '</li>';

				$output .= '<li class="slide">';

				$output .= themeblvd_get_open_row();

			}

			$i++;

		}

		$output .= themeblvd_get_close_row();

		$output .= '</li>';

		$output .= '</ul>';

		wp_reset_postdata();

		/**
		 * Fires after the post loop of a post grid slider.
		 *
		 * @since @@name-framework 2.5.0
		 *
		 * @param array $args Original arguments passed to themeblvd_get_grid_slider().
		 */
		do_action( 'themeblvd_grid_slider_after_loop', $args );

	}

	$output .= '</div><!-- .tb-grid-slider-inner (end) -->';

	$output .= '</div><!-- .tb-grid-slider (end) -->';

	/**
	 * Filters the final HTML output for a post grid
	 * slider block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string      $query   Query for posts passed to WP_Query.
	 *     @type string      $title   Optional. Title for the block.
	 *     @type string      $columns Number of posts per slide.
	 *     @type string|bool $nav     Whether to display slider controls.
	 *     @type string|int  $timeout Seconds in between auto-rotation; use `0` for not auto-rotation.
	 * }
	 */
	return apply_filters( 'themeblvd_grid_slider', $output, $args );

}

/**
 * Display a grid slider block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_grid_slider() docs.
 */
function themeblvd_grid_slider( $args ) {

	echo themeblvd_get_grid_slider( $args );

}

/**
 * Get a post slider block.
 *
 * This function is really just a complex wrapper
 * for themeblvd_get_simple_slider().
 *
 * We're querying posts and converting that data to
 * array data that will be understand by the simple
 * slider block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string       $source         Source of post query, `category`, `tag` or `query`..
 *     @type array        $categories     Category ID's to include posts from..
 *     @type string       $category_name  Force `category_name` parameter of WP_query..
 *     @type string       $cat            Force `cat` parameter of WP_query..
 *     @type string       $tag            Force `tag` parameter of WP_query..
 *     @type string|int   $posts_per_page Total number of posts to query..
 *     @type string       $orderby        Used for `orderby` parameter of WP_Query..
 *     @type string       $order          Used for `order` parameter of WP_Query..
 *     @type string|int   $offset         Used for `offset` parameter of WP_Query..
 *     @type string|array $query          Custom query, overrides all else..
 *     @type string       $crop           Crop size used for images of slider.
 *     @type string       $style          Preset styles for post slider, `style-1`, `style-2` or `style-3`.
 *     @type string       $slide_link     How to handle links from slides, `none`, `image_post`, `image_link`, or `button`.
 *     @type string       $button_color   If $slide_link == `button`, color of button.
 *     @type array        $button_custom  If $button_color == `custom`, button attributes for themeblvd_button().
 *     @type string       $button_text    If $slide_link == `button`, text of button.
 *     @type string       $button_size    If $slide_link == `button`, size of button, `mini`, `small`, `default`, `large`, or `x-large`.
 *     @type string|int   $interval       Seconds between auto-rotation of slider; use `0` for no auto-rotation.
 *     @type string|bool  $pause          Whether to pause slider on hover.
 *     @type string|bool  $wrap           Whether slider continuously cyles after first pass.
 *     @type string|bool  $nav_standard   Whether to show standard navigation dots.
 *     @type string|bool  $nav_arrows     Whether to show navigation arrows.
 *     @type string|bool  $nav_thumbs     Whether to show navigation image thumbnails.
 *     @type string|bool  $shade          Whether to shade entire image for text readability.
 *     @type string|bool  $link           Whether linked slides have animated hover overlay effect.
 *     @type string|bool  $title          Whether to include post title on each slide.
 *     @type string|bool  $meta           Whether to include post meta on each slide.
 *     @type string|bool  $excerpt        Whether to include post excerpt on each slide.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_post_slider( $args ) {

	global $post;

	$images = array();

	$args = wp_parse_args( $args, array(
		'source'         => '',
		'categories'     => array( 'all' => 1 ),
		'category_name'  => '',
		'cat'            => '',
		'tag'            => '',
		'posts_per_page' => '6',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'offset'         => '0',
		'query'          => '',
		'crop'           => 'slider-large',
		'style'          => 'style-1',
		'slide_link'     => 'button',
		'button_color'   => 'custom',
		'button_custom'  => array(),
		'button_text'    => 'View Post',
		'button_size'    => 'default',
		'interval'       => '5',
		'pause'          => '1',
		'wrap'           => '1',
		'nav_standard'   => '1',
		'nav_arrows'     => '1',
		'nav_thumbs'     => '0',
		'shade'          => '0',
		'link'           => '1',
		'title'          => '1',
		'meta'           => '1',
		'excerpt'        => '0',
	) );

	/*
	 * Pass a class onto the slider so we know
	 * it's the post slider for styling.
	 */
	$args['class'] = sprintf( 'tb-post-slider %s', $args['style'] );

	// Do we need mini controls?
	if ( $args['style'] == 'style-2' || $args['style'] == 'style-3' ) {

		$args['arrows'] = 'mini';

	}

	// Setup buttons, if included.
	if ( 'button' === $args['slide_link'] ) {

		$button = array(
			'text'   => $args['button_text'],
			'color'  => $args['button_color'],
			'target' => '_self',
			'size'   => $args['button_size'],
			'addon'  => '',
		);

		$custom = wp_parse_args( $args['button_custom'], array(
			'bg'             => '',
			'bg_hover'       => '#ffffff',
			'border'         => '#ffffff',
			'text'           => '#ffffff',
			'text_hover'     => '#333333',
			'include_bg'     => '0',
			'include_border' => '1',
		) );

		if ( 'custom' === $args['button_color'] ) {

			if ( $custom['include_bg'] ) {

				$bg = $custom['bg'];

			} else {

				$bg = 'transparent';

			}

			if ( $custom['include_border'] ) {

				$border = $custom['border'];

			} else {

				$border = 'transparent';

			}

			$button['addon'] = sprintf(
				'style="background-color: %1$s; border-color: %2$s; color: %3$s;" data-bg="%1$s" data-bg-hover="%4$s" data-text="%3$s" data-text-hover="%5$s"',
				$bg,
				$border,
				$custom['text'],
				$custom['bg_hover'],
				$custom['text_hover']
			);

		}
	}

	// Setup the query.
	$query_args = themeblvd_get_posts_args( $args, 'slider' );

	/**
	 * Filters the query arguments passed to WP_Query for
	 * a post slider.
	 *
	 * This filter exists for backwards compatibility only
	 * and is now deprecated. Use `themeblvd_posts_args`
	 * filter instead.
	 *
	 * @since @@name-framework 2.0.0
	 * @deprecated @@name-framework 2.5.0
	 *
	 * @param array $query_args Query arguments passed to WP_Query.
	 * @param array $args       Original arguments for post slider; see docs for themeblvd_get_post_slider().
	 */
	$query_args = apply_filters( 'themeblvd_slider_auto_args', $query_args, $args ); // Backward compatibility.

	/**
	 * Filters the query arguments for secondary post loops,
	 * which are passed to WP_Query.
	 *
	 * @since @@name-framework 2.0.0
	 *
	 * @param array  $query_args Query arguments passed to WP_Query.
	 * @param array  $args       Original arguments for post slider; see docs for themeblvd_get_post_slider().
	 * @param string $context    Post display method, which will be `slider` in this case.
	 */
	$query_args = apply_filters( 'themeblvd_posts_args', $query_args, $args, 'slider' );

	// Get posts.
	$posts = new WP_Query( $query_args );

	// Build out images for slider.
	if ( $posts->have_posts() ) {

		while ( $posts->have_posts() ) {

			$posts->the_post();

			$featured_image_id = get_post_thumbnail_id( $post->ID );

			$featured_image = wp_get_attachment_image_src( $featured_image_id, $args['crop'] );

			// If the post doesn't have a featured image set, move on.
			if ( ! $featured_image ) {

				continue;

			}

			$image = array(
				'crop'         => $args['crop'],
				'id'           => 0,
				'alt'          => get_the_title( $featured_image_id ),
				'src'          => $featured_image[0],
				'width'        => $featured_image[1],
				'height'       => $featured_image[2],
				'thumb'        => '',
				'title'        => '',
				'desc'         => '',
				'desc_wpautop' => false,
				'link'         => '',
				'link_url'     => '',
				'addon'        => '',
			);

			// Add slide image from featured image.
			if ( $args['nav_thumbs'] ) {

				$thumb = wp_get_attachment_image_src( $featured_image_id, apply_filters( 'themeblvd_simple_slider_thumb_crop', 'tb_thumb' ) );

				$image['thumb'] = $thumb[0];

			}

			// Add slide title, from post title.s
			if ( $args['title'] ) {

				$image['title'] = get_the_title();

			}

			// Add slide description, which will be meta post info.
			if ( $args['meta'] ) {

				switch ( $args['style'] ) {

					case 'style-1':
						$image['desc'] = themeblvd_get_meta(
							/**
							 * Filters the arguments used for the post meta info
							 * for post slider style #1.
							 *
							 * @since @@name-framework 2.5.0
							 *
							 * @param array Arguments passed to themeblvd_get_meta().
							 */
							apply_filters(
								'themeblvd_post_slider_style_1_args', array(
									'include' => array( 'format', 'time', 'author', 'comments' ),
								)
							)
						);
						break;

					case 'style-2':
						$image['desc'] = themeblvd_get_meta(
							/**
							 * Filters the arguments used for the post meta info
							 * for post slider style #2.
							 *
							 * @since @@name-framework 2.5.0
							 *
							 * @param array Arguments passed to themeblvd_get_meta().
							 */
							apply_filters(
								'themeblvd_post_slider_style_2_args', array(
									'include'  => array( 'time', 'author', 'comments' ),
									'time'     => 'ago',
									'comments' => 'mini',
								)
							)
						);
						break;

					case 'style-3':
						if ( has_category() ) {

							$image['addon'] .= sprintf( '<div class="category-label bg-primary">%s</div>', get_the_category_list( ', ' ) );

						}

						$image['addon'] .= themeblvd_get_meta(
							/**
							 * Filters the arguments used for the post meta info
							 * for post slider style #3.
							 *
							 * @since @@name-framework 2.5.0
							 *
							 * @param array Arguments passed to themeblvd_get_meta().
							 */
							apply_filters(
								'themeblvd_post_slider_style_3_args', array(
									'include'  => array( 'time', 'comments' ),
									'comments' => 'mini',
								)
							)
						);

				}
			}

			// Add excerpts to slide descriptions.
			if ( $args['excerpts'] ) {

				$image['desc'] .= '<p class="carousel-excerpt">' . get_the_excerpt() . '</p>';

			}

			// Add slide links.
			if ( 'image_post' === $args['slide_link'] ) {

				// Link full image slide to the post.

				$image['link'] = '_self';

				$image['link_url'] = get_the_permalink();

			} elseif ( 'image_link' === $args['slide_link'] ) {

				/*
				 * Link the full image slide to whatever the user
				 * has setup as the featured image link.
				 */
				$type = get_post_meta( $post->ID, '_tb_thumb_link', true );

				if ( $type && 'inactive' !== $type ) {

					switch ( $type ) {
						case 'post':
							$image['link'] = '_self';

							$image['link_url'] = get_the_permalink();

							break;

						case 'thumbnail':
							$image['link'] = 'image';

							$full = wp_get_attachment_image_src( $featured_image_id, 'tb_x_large' );

							$image['link_url'] = $full[0];

							break;

						case 'image':
							$image['link'] = 'image';

							$image['link_url'] = get_post_meta( $post->ID, '_tb_image_link', true );

							break;

						case 'video':
							$image['link'] = 'video';

							$image['link_url'] = get_post_meta( $post->ID, '_tb_video_link', true );

							break;

						case 'external':
							$image['link'] = '_blank';

							$image['link_url'] = get_post_meta( $post->ID, '_tb_external_link', true );

					}
				}
			} elseif ( $button ) {

				// Add button that links to the post.

				$btn = themeblvd_button(
					$button['text'],
					get_the_permalink(),
					$button['color'],
					$button['target'],
					$button['size'],
					null,
					get_the_title(),
					null,
					null,
					$button['addon']
				);

				$image['desc'] .= '<p class="carousel-button">' . $btn . '</p>';

			}

			// And finally, attach slide to the stack.
			$images[] = $image;

		}

		wp_reset_postdata();

	}

	$output = themeblvd_get_simple_slider( $images, $args );

	/**
	 * Filters the final HTML output for a post
	 * slider block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args   Block arguments; see docs for themeblvd_get_post_slider().
	 */
	return apply_filters( 'themeblvd_post_slider', $output, $args );

}

/**
 * Display a post slider block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_post_slider() docs.
 */
function themeblvd_post_slider( $args ) {

	echo themeblvd_get_post_slider( $args );

}

/**
 * Get a mini post list block.
 *
 * @since @@name-framework 2.1.0
 *
 * @param  string|array $query  Query parameters and other arguments to sneak in.
 * @param  string|bool  $thumb  Thumbnail display size (not image crop), `small`, `smaller`, `smallest`; use FALSE to display no thumbnails.
 * @param  bool         $meta   Whether to show meta info.
 * @return string       $output Final HTML output for block.
 */
function themeblvd_get_mini_post_list( $query = '', $thumb = 'smaller', $meta = true ) {

	$class = 'tb-mini-post-list';

	if ( $thumb && $thumb !== 'hide' ) {

		$class .= ' thumb-' . $thumb;

		themeblvd_set_att( 'thumbs', $thumb );

	} else {

		$class .= ' thumb-hide';

		themeblvd_set_att( 'thumbs', false );

	}

	if ( $meta && 'hide' !== $meta ) {

		themeblvd_set_att( 'show_meta', true );

	} else {

		themeblvd_set_att( 'show_meta', false );

	}

	$element = false;

	if ( ! empty( $query['element'] ) ) {

		$element = true;

	}

	$shortcode = false;

	if ( ! empty( $query['shortcode'] ) ) {

		$shortcode = true;

	}

	$args = array(
		'display'   => 'mini-list',
		'context'   => 'mini-list',
		'part'      => 'list_mini', // By default uses content-mini-list.php.
		'element'   => $element,
		'shortcode' => $shortcode,
		'class'     => $class,
	);

	if ( is_string( $query ) ) {

		$args['query'] = str_replace( 'numberposts', 'posts_per_page', $query );

	} else {

		$args = array_merge( $args, $query );

	}

	ob_start();

	themeblvd_loop( $args );

	/**
	 * Filters the final HTML output for a mini post
	 * list block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string       $output Final HTML output.
	 * @param string|array $query  Query parameters and other arguments to sneak in.
	 * @param string|bool  $thumb  Thumbnail display size (not image crop), `small`, `smaller`, `smallest`; use FALSE to display no thumbnails.
	 * @param bool         $meta   Whether to show meta info.
	 */
	return apply_filters( 'themeblvd_mini_post_list', ob_get_clean(), $query, $thumb, $meta );

}

/**
 * Display a mini post list block.
 *
 * @since @@name-framework 2.1.0
 *
 * @param string|array $query Query parameters and other arguments to sneak in.
 * @param string|bool  $thumb Thumbnail display size (not image crop), `small`, `smaller`, `smallest`; use FALSE to display no thumbnails.
 * @param bool         $meta  Whether to show meta info.
 */
function themeblvd_mini_post_list( $query = '', $thumb = 'smaller', $meta = true ) {

	echo themeblvd_get_mini_post_list( $query, $thumb, $meta );

}

/**
 * Get a mini post grid block.
 *
 * @since @@name-framework 2.1.0
 *
 * @param  string|array $query   Query parameters and other arguments to sneak in.
 * @param  string       $align   Alignment of images, `left`, `right` or `center`.
 * @param  string|bool  $thumb   Thumbnail display size (not image crop), `small`, `smaller`, `smallest`; use FALSE to display no thumbnails.
 * @param  string       $gallery Gallery shortcode instance to generate from, like `[gallery ids="1,2,3"]`.
 * @return string       $output  Final HTML output for block.
 */
function themeblvd_get_mini_post_grid( $query = '', $align = 'left', $thumb = 'smaller', $gallery = '' ) {

	if ( $gallery ) {

		themeblvd_set_att( 'gallery', true );

		$query = array();

		$pattern = get_shortcode_regex();

		if ( preg_match( "/$pattern/s", $gallery, $match ) && 'gallery' == $match[2] ) {

			$atts = shortcode_parse_atts( $match[3] );

			if ( ! empty( $atts['ids'] ) ) {

				$query = array(
					'post_type'      => 'attachment',
					'post_status'    => 'inherit',
					'post__in'       => explode( ',', $atts['ids'] ),
					'orderby'        => 'post__in',
					'posts_per_page' => -1,
				);

			} else {

				return sprintf(
					'<div class="alert alert-warning">%s<br /><code>[gallery ids="1,2,3"]</code></div>',
					esc_html__( 'Oops! There aren\'t any ID\'s in your gallery shortcode. It should be formatted like:', '@@text-domain' )
				);

			}
		} else {

			return sprintf(
				'<div class="alert alert-warning">%s</div>',
				esc_html__( 'Oops! You used the gallery override for this mini post grid, but didn\'t use the [gallery] shortcode.', '@@text-domain' )
			);

		}
	} else {

		themeblvd_set_att( 'gallery', false );

		if ( is_string( $query ) ) {

			$query = str_replace( 'numberposts', 'posts_per_page', $query ); // Backwards compatibility.

			$query .= '&meta_key=_thumbnail_id'; // Only query posts with featured image.

		} else {

			$query['meta_key'] = '_thumbnail_id';

		}
	}

	$element = false;

	if ( ! empty( $query['element'] ) ) {

		$element = true;

	}

	$shortcode = false;

	if ( ! empty( $query['shortcode'] ) ) {

		$shortcode = true;

	}

	$args = array(
		'display'   => 'mini-grid',
		'context'   => 'mini-grid',
		'part'      => 'grid_mini', // By default uses content-mini-list.php.
		'element'   => $element,
		'shortcode' => $shortcode,
		'class'     => sprintf( 'tb-mini-post-grid clearfix themeblvd-gallery thumb-%s thumb-align-%s', $thumb, $align ),
	);

	if ( $gallery || is_string( $query ) ) {

		$args['query'] = $query;

	} else {

		$args = array_merge( $args, $query );

	}

	ob_start();

	themeblvd_loop( $args );

	/**
	 * Filters the final HTML output for a mini post
	 * grid block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string      $output Final HTML output.
	 * @param string      $align   Alignment of images, `left`, `right` or `center`.
	 * @param string|bool $thumb   Thumbnail display size (not image crop), `small`, `smaller`, `smallest`; use FALSE to display no thumbnails.
	 * @param string      $gallery Gallery shortcode instance to generate from, like `[gallery ids="1,2,3"]`.
	 */
	return apply_filters( 'themeblvd_mini_post_grid', ob_get_clean(), $align, $thumb, $gallery );

}

/**
 * Display a mini post grid block.
 *
 * @since @@name-framework 2.1.0
 *
 * @param string|array $query   Query parameters and other arguments to sneak in.
 * @param string       $align   Alignment of images, `left`, `right` or `center`.
 * @param string|bool  $thumb   Thumbnail display size (not image crop), `small`, `smaller`, `smallest`; use FALSE to display no thumbnails.
 * @param string       $gallery Gallery shortcode instance to generate from, like `[gallery ids="1,2,3"]`.
 */
function themeblvd_mini_post_grid( $query = '', $align = 'left', $thumb = 'smaller', $gallery = '' ) {

	echo themeblvd_get_mini_post_grid( $query, $align, $thumb, $gallery );

}

/**
 * Get a small post list block.
 *
 * @since @TODO This feature hasn't been built yet.
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string $foo Desc.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_small_post_list( $args = array() ) {

	$args = wp_parse_args( $args, array(
		// ...
	) );

	//  @TODO

	/**
	 * Filters the final HTML output for a small post
	 * list block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args   Block arguments; see docs for themeblvd_get_small_post_list().
	 */
	return apply_filters( 'themeblvd_small_post_list', '', $args );

}

/**
 * Display a small post list block.
 *
 * @since @TODO This feature hasn't been built yet.
 *
 * @param array $args Block arguments, see themeblvd_get_small_post_list() docs.
 */
function themeblvd_small_post_list( $args = array() ) {

	echo themeblvd_get_small_post_list( $args );

}

/**
 * Get a small post grid block.
 *
 * @since @@name-framework 2.6.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string $crop    Crop size for featured images.
 *     @type int    $columns Number of columns in grid.
 *     @type int    $rows    Number of rows in grid.
 *     @type bool   $titles  Whether to show titles for posts.
 *     @type bool   $meta    Whether to show meta info for posts.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_small_post_grid( $args = array() ) {

	$args = wp_parse_args( $args, array(
		'crop'    => 'tb_grid',
		'columns' => 3,
		'rows'    => 1,
		'titles'  => 1,
		'meta'    => 1,
	) );

	$class = 'tb-small-post-grid';

	themeblvd_set_att( 'crop', $args['crop'] );

	if ( $args['titles'] ) {

		$class .= ' has-titles';

		themeblvd_set_att( 'show_title', true );

	} else {

		themeblvd_set_att( 'show_title', false );

	}

	if ( $args['meta'] ) {

		$class .= ' has-meta';

		themeblvd_set_att( 'show_meta', true );

	} else {

		themeblvd_set_att( 'show_meta', false );

	}

	$element = false;

	if ( ! empty( $args['element'] ) ) {

		$element = true;

	}

	$shortcode = false;

	if ( ! empty( $query['shortcode'] ) ) {

		$shortcode = true;

	}

	$args = array_merge( $args, array(
		'display'   => 'small-grid',
		'context'   => 'small-grid',
		'part'      => 'grid_small', // By default uses content-small-grid.php.
		'element'   => $element,
		'shortcode' => $shortcode,
		'class'     => $class,
	) );

	ob_start();

	themeblvd_loop( $args );

	/**
	 * Filters the final HTML output for a small post
	 * grid block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args   Block arguments; see docs for themeblvd_get_small_post_grid().
	 */
	return apply_filters( 'themeblvd_small_post_grid', ob_get_clean(), $args );

}

/**
 * Display a small post grid block.
 *
 * @since @@name-framework 2.6.0
 *
 * @param array $args Block arguments, see themeblvd_get_small_post_grid() docs.
 */
function themeblvd_small_post_grid( $args = array() ) {

	echo themeblvd_get_small_post_grid( $args );

}
