<?php
/**
 * Post List (secondary loops)
 *
 * @since 2.5.0
 *
 * @param array $args All arguments for post list
 * @param string $part Template part to use for loop
 */
function themeblvd_loop( $args = array() ){

	global $wp_query;
	global $post;
	global $more;

	// Setup and extract $args
	$defaults = array(
		'title'				=> '',						// Title for element
		'display'			=> 'paginated',				// How to display - grid, list, showcase, filter, masonry, masonry_filter, paginated, masonry_paginated
		'source'			=> '',						// Source of posts - category, tag, query
		'categories'		=> array('all' => 1),		// Post categories to include
		'category_name'		=> '',						// Force category_name string of query
		'cat'				=> '',						// Force cat string of query
		'tag'				=> '', 						// Force tag string of query
		'posts_per_page'	=> '6',						// Number of posts
		'orderby'			=> 'date',					// Orderby param for posts query
		'order'				=> 'DESC',					// Order param for posts query
		'offset'			=> '0',						// Offset param for posts query
		'columns'			=> '',						// If grid, number of columns
		'rows'				=> '',						// If grid, Number of rows
		'slides'			=> '3',						// If grid slider, Number of slides
		'pages'				=> '',						// List of page slugs
		'query'				=> '',						// Custom query string
		'thumbs'			=> '',						// Size of featured images - default, small, full, hide ("small" not always supported)
		'content'			=> '',						// For blog, full content or excerpts
		'titles'			=> '',						// For showcase, whether to shwo post titles
		'excerpt'			=> '',						// For grid, whether to show excerpts
		'meta'				=> '',						// Whether to show meta (used with blog, list, grid)
		'more'				=> '',						// Read More - text, button, none (supported with "list" $context only)
		'more_text'			=> '',						// If Read More is text or button, text to use (supported with "list" $context only)
		'timeout'			=> '3',						// If grid slider, seconds between trasitinos
		'nav'				=> '1',						// If grid slider, whether to show controls
		'filter'			=> 'category',				// If display == filter or masonry_filter, use this to filter by
		'filter_max'		=> '-1',					// If grid and display == filter or masonry_filter, use this as posts_per_page in query
		'part'				=> '',						// For custom template part for each post
		'context'			=> '',						// Context of this post display
		'shortcode'			=> false,					// Whether this is called by a shortcode
		'element'			=> false,					// Whether this is called by an element in the Builder
		'crop'				=> '',						// If grid or showcase, custom crop size
		'class'				=> '',						// Any additional CSS class to add
		'max_width'			=> 0,						// Container max width - if coming from element, this should be set
		'wp_query'			=> false,					// Whether to pull from primary WP query
		'msg_no_posts'		=> themeblvd_get_local('archive_no_posts')
	);
	$args = wp_parse_args( $args, $defaults );

	// Is this a paginated post list?
	$paginated = false;

	if ( $args['display'] == 'paginated' || $args['display'] == 'masonry_paginated' ) {
		$paginated = true;
	}

	// Are we using isotope?
	$isotope = false;

	if ( in_array( $args['display'], array('filter', 'masonry', 'masonry_filter', 'masonry_paginated' ) ) ) {
		$isotope = true;
	}

	/**
	 * In what context are these posts being displayed?
	 *
	 * If the post list is in secondary context, like
	 * a shortcode, widget, etc, and you don't want the
	 * $context to be overridden with generic, top-level
	 * conditionals, you need to pass in a $context here.
	 */
	$context = $args['context'];

	if ( ! $context ) {
		$context = themeblvd_config('mode');
	}

	if ( ! $context ) {
		$context = 'list';
	}

	/**
	 * What template part should we include?
	 *
	 * Note: $part will get passed into themelvd_get_part().
	 * So, if you want to change the actual file name, you'd
	 * filter onto themeblvd_template_part.
	 */
	if ( $args['part'] ) {

		$part = $args['part'];

	} else {

		$part = $context;

		if ( $paginated && $context != 'search_results' ) {
			$part .= '_paginated';
		}
	}

	// Start class var
	$post_class = '';

	/**
	 * Set attributes that we can access from template
	 * part files.
	 */
	switch ( $context ) {

		/**
		 * Post Display: Blog
		 */
		case 'blog' :

			// Featured images
			if ( ! $args['thumbs'] || $args['thumbs'] == 'default' ) {
				$thumbs = themeblvd_get_option('blog_thumbs', null, 'full');
			} else {
				$thumbs = $args['thumbs'];
			}

			if ( $thumbs == 'hide' ) {
				themeblvd_set_att( 'thumbs', false );
			} else {
				themeblvd_set_att( 'thumbs', $thumbs );
				$post_class .= ' has-thumbnail';
			}

			// Meta
			if ( ! $args['meta'] || $args['meta'] == 'default' ) {
				$meta = themeblvd_get_option('blog_meta', null, 'show');
			} else {
				$meta = $args['meta'];
			}

			if ( $meta == 'show' ) {
				themeblvd_set_att( 'show_meta', true );
				$post_class .= ' has-meta';
			} else {
				themeblvd_set_att( 'show_meta', false );
			}

			// Content or Excerpt
			if ( ! $args['content'] || $args['content'] == 'default' ) {
				themeblvd_set_att( 'content', themeblvd_get_option('blog_thumbs', null, 'excerpt') );
			} else {
				themeblvd_set_att( 'content', $args['content'] );
			}

			break;

		/**
		 * Post Display: List
		 */
		case 'list' :

			// Featured images
			if ( ! $args['thumbs'] || $args['thumbs'] == 'default' ) {
				$thumbs = themeblvd_get_option('list_thumbs', null, 'full');
			} else {
				$thumbs = $args['thumbs'];
			}

			if ( $thumbs == 'hide' ) {

				themeblvd_set_att( 'thumbs', false );

			} else {

				themeblvd_set_att( 'thumbs', $thumbs );

				if ( $thumbs == 'date' ) {
					$post_class .= ' has-date';
				} else {
					$post_class .= ' has-thumbnail';
				}

			}

			// Meta
			if ( ! $args['meta'] || $args['meta'] == 'default' ) {
				$meta = themeblvd_get_option('list_meta', null, 'show');
			} else {
				$meta = $args['meta'];
			}

			if ( $meta == 'show' ) {
				themeblvd_set_att( 'show_meta', true );
				$post_class .= ' has-meta';
			} else {
				themeblvd_set_att( 'show_meta', false );
			}

			// Read More Link or Button
			if ( ! $args['more'] || $args['more'] == 'default' ) {
				$more = themeblvd_get_option('list_more', null, 'text');
			} else {
				$more = $args['more'];
			}

			if ( $more == 'hide' ) {
				themeblvd_set_att( 'more', false );
			} else {
				themeblvd_set_att( 'more', $more );
			}

			// Read More text
			themeblvd_set_att( 'more_text', themeblvd_get_option('list_more_text', null, themeblvd_get_local('read_more')) );

			break;

		/**
		 * Post Display: Grid
		 */
		case 'grid' :

			// Featured images
			if ( ! $args['thumbs'] || $args['thumbs'] == 'default' ) {
				$thumbs = themeblvd_get_option($context.'_thumbs', null, 'full');
			} else {
				$thumbs = $args['thumbs'];
			}

			if ( $thumbs == 'hide' ) {
				themeblvd_set_att( 'thumbs', false );
			} else {
				themeblvd_set_att( 'thumbs', $thumbs );
			}

			// Meta
			if ( ! $args['meta'] || $args['meta'] == 'default' ) {
				$meta = themeblvd_get_option('grid_meta', null, 'show');
			} else {
				$meta = $args['meta'];
			}

			if ( $meta == 'show' ) {
				themeblvd_set_att( 'show_meta', true );
			} else {
				themeblvd_set_att( 'show_meta', false );
			}

			// Excerpts
			if ( ! $args['excerpt'] || $args['excerpt'] == 'default' ) {
				$excerpt = themeblvd_get_option('grid_excerpt', null, 'show');
			} else {
				$excerpt = $args['excerpt'];
			}

			if ( $excerpt == 'show' ) {
				themeblvd_set_att( 'excerpt', true );
			} else {
				themeblvd_set_att( 'excerpt', false );
			}

			// Read More Link or Button
			if ( ! $args['more'] || $args['more'] == 'default' ) {
				themeblvd_set_att( 'more', themeblvd_get_option('grid_more', null, 'text') );
			} else {
				themeblvd_set_att( 'more', $args['more'] );
			}

			// Read More text
			themeblvd_set_att( 'more_text', themeblvd_get_option('grid_more_text', null, themeblvd_get_local('read_more')) );

			// Number of columns (i.e. posts per row)
			if ( $args['columns'] ) {
				$columns = $args['columns'];
			} else {
				$columns = themeblvd_get_option( 'grid_columns', null, '3' );
			}

			$columns = themeblvd_set_att( 'columns', intval($columns) );

			// Grid class
			$post_class = sprintf( 'col %s', themeblvd_grid_class(intval($columns)) );

			themeblvd_set_att('size', $post_class); // backwards compat

			if ( themeblvd_get_att('thumbs') ) {
				$post_class .= ' has-thumb';
			}

			if ( themeblvd_get_att('show_meta') ) {
				$post_class .= ' has-meta';
			}

			if ( themeblvd_get_att('excerpt') ) {
				$post_class .= ' has-excerpt';
			}

			if ( themeblvd_get_att('more') == 'button' || themeblvd_get_att('more') == 'text' ) {
				$post_class .= ' has-more';
			}

			if ( $args['crop'] ) {
				$crop = $args['crop'];
			} else {
				$crop = themeblvd_get_option('grid_crop', null, 'tb_grid');
			}

			themeblvd_set_att( 'crop', $crop );

			// For any placeholders
			themeblvd_set_att( 'crop_w', '640' );
			themeblvd_set_att( 'crop_h', '360' );
			break;

		/**
		 * Post Display: Showcase
		 */
		case 'showcase' :

			// Titles
			if ( ! $args['titles'] || $args['titles'] == 'default' ) {
				$titles = themeblvd_get_option('showcase_titles', null, 'show');
			} else {
				$titles = $args['titles'];
			}

			if ( $titles == 'show' ) {
				themeblvd_set_att( 'titles', true );
			} else {
				themeblvd_set_att( 'titles', false );
			}

			// Excerpts
			if ( ! $args['excerpt'] || $args['excerpt'] == 'default' ) {
				$excerpt = themeblvd_get_option('showcase_excerpt', null, 'hide');
			} else {
				$excerpt = $args['excerpt'];
			}

			if ( $excerpt == 'show' ) {
				themeblvd_set_att( 'excerpt', true );
			} else {
				themeblvd_set_att( 'excerpt', false );
			}

			// Crop
			if ( $args['crop'] ) {
				$crop = $args['crop'];
			} else {
				$crop = themeblvd_get_option('showcase_crop', null, 'tb_large');
			}

			themeblvd_set_att( 'crop', $crop );

			// For any placeholders
			themeblvd_set_att( 'crop_w', '640' );
			themeblvd_set_att( 'crop_h', '360' );

			// Number of columns (i.e. posts per row)
			if ( $args['columns'] ) {
				$columns = $args['columns'];
			} else {
				$columns = themeblvd_get_option( 'showcase_columns', null, '3' );
			}

			$columns = themeblvd_set_att( 'columns', intval($columns) );

			// Grid class
			$post_class = sprintf( 'col %s', themeblvd_grid_class(intval($columns)) );

			$post_class .= ' showcase-item';

			if ( themeblvd_get_att('titles') ) {
				$post_class .= ' has-title';
			}

			if ( themeblvd_get_att('excerpt') ) {
				$post_class .= ' has-excerpt';
			}

	}

	// Make sure that themeblvd_get_featured_image never
	// thinks this is for the single post.
	themeblvd_set_att('location', 'primary');

	// Extend
	do_action("themeblvd_loop_set_{$context}_atts", $args);

	/**
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

		// Pull from primary query
		$posts = $wp_query;

	} else {

		if ( $paginated ) {

			// There can only be one "second query"; so if one
			// already exists, that's our boy.
			$query_args = themeblvd_get_second_query();

			if ( $query_args ) {

				$doing_the_second_loop = true;

			} else {

				if ( $context == 'grid' || $context == 'showcase' ) {

					$posts_per_page = '-1';

					if ( $args['display'] == 'masonry_paginated' && $args['posts_per_page'] ) {
						$posts_per_page = $args['posts_per_page'];
					} else if ( $args['display'] == 'masonry_filter' && $args['filter_max'] ) {
						$posts_per_page = $args['filter_max'];
					} else if ( $args['display'] == 'paginated' && $args['rows'] ) {
						$posts_per_page = intval($args['rows']) * $columns;
					}

					$args['posts_per_page'] = $posts_per_page;
				}

				// Set the second query in global $themeblvd_query.
				// We only do this for paginated queries.
				$query_args = themeblvd_set_second_query( $args, $context ); // Sets global var and gets for local var
			}

		} else {

			// Standard query for non-paginated posts
			$query_args = themeblvd_get_posts_args( $args, $context );

		}

		$query_args = apply_filters( 'themeblvd_posts_args', $query_args, $args, $context );

		// If it's a post grid slider, pass it on with the query.
		if ( $context == 'grid' && $args['display'] == 'slider' ) {
			$args['query'] = $query_args;
			themeblvd_set_att('class', $post_class);
			themeblvd_grid_slider($args);
			return;
		}

		// Get posts
		$posts = new WP_Query( $query_args );

	}

	// CSS classes
	$class = '';

	if ( $context == 'blog' ) {

		$class = 'blog';

		if ( $paginated ) {
			$class .= ' paginated';
		}

	} else if ( $context == 'list' || $context == 'grid' || $context == 'showcase' ) {

		$class = 'post_'.$context;

		if ( $context == 'grid' || $context == 'showcase' ) {
			$class .= ' themeblvd-gallery';
		}

		if ( $paginated && $context != 'showcase' ) {
			$class .= ' post_'.$context.'_paginated';
		}

	}

	if ( ! empty($columns) ) {
		$class .= ' columns-'.$columns;
	}

	if ( $isotope ) {
		$class .= ' tb-isotope';
	}

	if ( $args['display'] == 'filter' || $args['display'] == 'masonry_filter' ) {
		$class .= ' tb-filter';
	}

	if ( $args['display'] == 'masonry' || $args['display'] == 'masonry_paginated' || $args['display'] == 'masonry_filter' ) {
		$class .= ' tb-masonry';
	}

	if ( $args['class'] ) {
		$class .= ' '.$args['class'];
	}

	if ( $args['shortcode'] ) {
		$class .= ' loop-shortcode';
	}

	if ( $args['element'] ) {
		$class .= ' loop-element';
	}

	// Start output
	echo '<div class="'.$class.'">';

	// Output title and content of current page, if this is a
	// page template displaying secondary loop.
	if ( ! empty($doing_the_second_loop) && ( is_page_template('template_blog.php') || is_page_template('template_list.php') || is_page_template('template_grid.php') || is_page_template('template_showcase.php') ) ) {
		themeblvd_page_info();
	}

	// Optional title passed in from shortcodes/elements
	if ( $args['title'] ) {
		printf( '<h3 class="title">%s</h3>', $args['title'] );
	}

	if ( $args['display'] == 'filter' || $args['display'] == 'masonry_filter' ) {
		themeblvd_filter_nav($posts, $args['filter']);
	}

	if ( $posts->have_posts() ) {

		// Let the world know we're doing a secondary loop
		if ( ! $args['wp_query'] ) {
			themeblvd_set_att('doing_second_loop', true);
		}

		// If you need to modify any global attributes for passing
		// to template parts, hook in here!
		do_action( 'themeblvd_post_list_before_loop', $args );

		printf('<div class="post-wrap %s-wrap">', $context);

		$counter = themeblvd_set_att( 'counter', 1 );
		$total = $posts->post_count;

		// Posts per column
		$ppc = 0;

		if ( $context != 'grid' && $context != 'showcase' && intval($args['columns']) >= 2 ) {
			$ppc = ceil( $total / intval($args['columns']) );
		}

		$ppc_class = themeblvd_grid_class( intval($args['columns']) );

		// Loader
		if ( $isotope ) {
			themeblvd_loader();
		}

		// Open row
		if ( $context == 'grid' || $context == 'showcase' || $ppc || $isotope ) {
			themeblvd_open_row();
		}

		// If posts are listed in columns, open first column
		if ( $ppc ) {
			printf('<div class="%s">', $ppc_class);
		}

		while ( $posts->have_posts() ) {

			$posts->the_post();
			$more = 0;

			// Post class
			$current_post_class = $post_class;

			if ( $isotope ) {

				$current_post_class .= ' iso-item';

				if ( $args['display'] == 'filter' || $args['display'] == 'masonry_filter' ) {
					$current_post_class .= ' '.themeblvd_get_filter_val( $args['filter'] );
				}
			}
			themeblvd_set_att('class', trim($current_post_class));


			// Get template part
			get_template_part( 'content', themeblvd_get_part($part) );

			// For grid, if last post in a row, but not the very last post.
			// NOTE: This is only for standard grids not using isotope
			if ( ! $isotope && ( $context == 'grid' || $context == 'showcase' ) && $counter % $columns == 0 && $total != $counter ) {

				// Close current row
				themeblvd_close_row();

				// Open the next row
				themeblvd_open_row();

			}

			// For posts devided into columns
			if ( $ppc && $counter % $ppc == 0 && $total != $counter ) {

				// Close current column
				printf('</div><!-- .%s (end) -->', $ppc_class);

				// Open new column
				printf('<div class="%s">', $ppc_class);

			}

			// Increment the counter with global template attribute accounted for
			$counter = themeblvd_set_att( 'counter', $counter+1 );

		}

		// If posts are listed in columns, close last column
		if ( $ppc ) {
			printf('</div><!-- .%s (end) -->', $ppc_class);
		}

		// Close row
		if ( $context == 'grid' || $context == 'showcase' || $ppc || $context == 'showcase' ) {
			themeblvd_close_row();
		}

		echo "</div><!-- .{$context}-wrap (end) -->";

		do_action( 'themeblvd_post_list_after_loop', $args );

		// Let the world know we've finished our secondary loop
		if ( ! $args['wp_query'] ) {
			themeblvd_set_att('doing_second_loop', false);
		}

	} else {

		// No posts to display
		printf( '<p>%s</p>', $args['msg_no_posts'] );

	}

	// Pagination
	if ( $paginated && $posts->max_num_pages >= 2 ) {
		themeblvd_pagination( $posts->max_num_pages );
	}

	// Reset Post Data
	wp_reset_postdata();

	// End output
	echo '</div><!-- .*-loop (end) -->';

}

/**
 * Post Loop (primary loop)
 *
 * @since 2.5.0
 */
function themeblvd_the_loop() {

	$mode = themeblvd_config('mode');
	$class = '';

	if ( is_home() ) {

		$class = 'home-loop';

	} else if ( is_archive() ) {

		$class = 'archive-loop';

		if ( is_category() ) {
			$class .= ' category-loop';
		} else if ( is_tag() ) {
			$class .= ' tag-loop';
		} else if  ( is_author() ) {
			$class .= ' author-loop';
		} else if ( is_date() ) {
			$class .= ' date-loop';
		} else if ( is_tax('portfolio') || is_tax('portfolio_tag') ) {
			$class .= ' portfolio-loop';
		}

	}

	$display = '';

	if ( $mode == 'grid' || $mode == 'showcase' ) {
		$display = themeblvd_get_option( $mode.'_display' );
	}

	if ( ! $display ) {
		$display = 'paginated';
	}

	$args = array(
		'display'	=> $display,
		'context'	=> $mode,
		'class'		=> $class,
		'wp_query' 	=> true
	);

	if ( is_search() ) {
		$args['context'] = 'search_results';
		$args['class'] = 'search-loop';
		$args['msg_no_posts'] = themeblvd_get_local('search_no_results');
	}

	$args = apply_filters( 'themeblvd_the_loop_args', $args );

	themeblvd_loop( $args );

}

/**
 * Get arguments to pass into themeblvd_loop()
 * when using a page template that displays posts.
 *
 * @since 2.5.0
 *
 * @param string $template Type of template - blog, list, grid, or showcase
 * @return array $args
 */
function themeblvd_get_template_loop_args( $template ){

	$page_id = themeblvd_config('id');
	$args = array();

	if ( $template == 'list' || $template == 'blog' ) {

		$args['display'] = 'paginated';

	} else if ( $template == 'grid' || $template == 'showcase' ) {

		if ( $display = get_post_meta( $page_id, 'tb_display', true ) ) {
			$args['display'] = $display;
		} else if( $template == 'grid' ) {
			$args['display'] = themeblvd_get_option( 'grid_display', null, 'paginated' );
		} else if( $template == 'showcase' ) {
			$args['display'] = themeblvd_get_option( 'showcase_display', null, 'masonry_paginated' );
		}

		$args['columns'] = get_post_meta( $page_id, 'columns', true );
		$args['rows'] = get_post_meta( $page_id, 'rows', true );

	}

	return apply_filters( "themeblvd_{$template}_template_loop_args", $args, $page_id );
}

/**
 * Get post grid slider
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for map
 * @return string $output Final content to output
 */
function themeblvd_get_grid_slider( $args ) {

	global $post;
	global $more;
	$more = 0;

	$defaults = array(
		'query'		=> null,		// Query for posts
		'title'		=> '',			// Title for unit
		'columns'	=> '3',			// Number of logos per slide
		'nav'		=> '1',			// If slider, whether to display nav
		'timeout'	=> '3',			// If slider, seconds in between auto rotation
	);
	$args = wp_parse_args( $args, $defaults );

	if ( ! $args['query'] ) {
		return __('Error: No query supplied.', 'themeblvd');
	}

	$class = 'tb-grid-slider tb-block-slider post_grid themeblvd-gallery';

	if ( $args['title'] ) {
		$class .= ' has-title';
	}

	if ( $args['nav'] ) {
		$class .= ' has-nav';
	}

	$output = sprintf( '<div class="%s" data-timeout="%s" data-nav="%s">', $class, $args['timeout'], $args['nav'] );

	if ( $args['title'] ) {
		$output .= sprintf( '<h3 class="title">%s</h3>', $args['title'] );
	}

	$output .= '<div class="tb-grid-slider-inner tb-block-slider-inner flexslider">';

	$output .= themeblvd_get_loader();

	// Get posts
	$query_args = apply_filters( 'themeblvd_post_slider_args', $args['query'], $args, 'grid' );

	$posts = new WP_Query( $query_args );

    if ( $posts->have_posts() ) {

    	$num_per = intval($args['columns']);
		$grid_class = themeblvd_grid_class($num_per);

		$i = themeblvd_set_att( 'counter', 1 );
		$total = $posts->post_count;

		if ( $args['nav'] && $total > $num_per ) {
			$output .= themeblvd_get_slider_controls();
		}

    	do_action( 'themeblvd_grid_slider_before_loop', $args );

		$output .= '<ul class="slides">';
		$output .= '<li class="slide">';

		$output .= themeblvd_get_open_row();

		while ( $posts->have_posts() ) {

			$posts->the_post();
			$more = 0;

			ob_start();
			get_template_part( 'content', themeblvd_get_part( 'grid_slider' ) );
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

		do_action( 'themeblvd_grid_slider_after_loop', $args );
    }

    $output .= '</div><!-- .tb-grid-slider-inner (end) -->';
    $output .= '</div><!-- .tb-grid-slider (end) -->';

	return apply_filters( 'themeblvd_grid_slider', $output, $args );
}

/**
 * Display grid slider
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for slider
 */
function themeblvd_grid_slider( $args ) {
	echo themeblvd_get_grid_slider( $args );
}

/**
 * Get post post slider
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for slider
 * @return string $output Final content to output
 */
function themeblvd_get_post_slider( $args ) {

	global $post;

	$images = array();

	$defaults = array(
		'source'			=> '',						// Source of posts - category, tag, query
		'categories'		=> array('all' => 1),		// Post categories to include
		'category_name'		=> '',						// Force category_name string of query
		'cat'				=> '',						// Force cat string of query
		'tag'				=> '', 						// Force tag string of query
		'posts_per_page'	=> '6',						// Number of posts
		'orderby'			=> 'date',					// Orderby param for posts query
		'order'				=> 'DESC',					// Order param for posts query
		'offset'			=> '0',						// Offset param for posts query
		'query'				=> '',						// Custom query string
    	'crop'				=> 'slider-large',			// Crop size for slide images
    	'style'				=> 'style-1',				// Preset styles for post slider - style-1, style-2, style-3
    	'slide_link'		=> 'button',				// How to handle links from slides - none, image_post, image_link, or button
    	'button_color'		=> 'custom',				// If slide_link == button, color of button
    	'button_custom'		=> array(),					// Custom button color atts
		'button_text'		=> 'View Post',				// Button text
		'button_size'		=> 'default',				// Side of button - mini, small, default, large, or x-large
		'interval'			=> '5',						// How fast to auto rotate betweens slides
		'pause'				=> '1',						// Whether to pause slider on hover
		'wrap'				=> '1',						// When slider auto-rotates, whether it continuously cycles
		'nav_standard'		=> '1',						// Whether to show standard navigation dots
		'nav_arrows'		=> '1',						// Whether to show navigation arrows
		'nav_thumbs'		=> '0',						// Whether to show navigation image thumbnails
		'link'				=> '1',						// Whether linked slides have animated hover overlay effect
    	'title'				=> '1',						// Whether to include post title on each slide
    	'meta'				=> '1',						// Whether to include post meta on each slide
    	'excerpt'			=> '0',						// Whether to include post excerpt on each slide
    	'cover'				=> '0',						// popout: Whether images horizontal space 100%
		'position'			=> 'middle center',			// popout: If cover is true, how slider images are positioned (i.e. with background-position)
		'height_desktop'	=> '400',					// popout: If cover is true, slider height for desktop viewport
		'height_tablet'		=> '300',					// popout: If cover is true, slider height for tablet viewport
		'height_mobile'		=> '200'					// popout: If cover is true, slider height for mobile viewport
    );
    $args = wp_parse_args( $args, $defaults );

    // Pass a class onto the slider so we know
    // it's the post slider for styling
    $args['class'] = sprintf('tb-post-slider %s', $args['style']);

    // Do we need mini controls?
    if ( $args['style'] == 'style-2' || $args['style'] == 'style-3' ) {
    	$args['arrows'] = 'mini';
    }

    // Setup buttons, if included
    if ( $args['slide_link'] == 'button' ) {

    	$button = array(
    		'text'		=> $args['button_text'],
    		'color'		=> $args['button_color'],
    		'target'	=> '_self',
    		'size'		=> $args['button_size'],
    		'addon'		=> ''
    	);

    	$defaults = array(
			'bg' 				=> '',
			'bg_hover'			=> '#ffffff',
			'border' 			=> '#ffffff',
			'text'				=> '#ffffff',
			'text_hover'		=> '#333333',
			'include_bg'		=> '0',
			'include_border'	=> '1'
		);
		$custom = wp_parse_args( $args['button_custom'], $defaults );

    	if ( $args['button_color'] == 'custom' ) {

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

            $button['addon'] = sprintf( 'style="background-color: %1$s; border-color: %2$s; color: %3$s;" data-bg="%1$s" data-bg-hover="%4$s" data-text="%3$s" data-text-hover="%5$s"', $bg, $border, $custom['text'], $custom['bg_hover'], $custom['text_hover'] );

        }

    }

    // Setup the query
	$query_args = themeblvd_get_posts_args( $args, 'slider' );
	$query_args = apply_filters( 'themeblvd_slider_auto_args', $query_args, $args ); // backward compat
	$query_args = apply_filters( 'themeblvd_posts_args', $query_args, $args, 'slider' );

	// Get posts
	$posts = new WP_Query( $query_args );

    // Build out images for slider
	if ( $posts->have_posts() ) {

		while ( $posts->have_posts() ) {

			$posts->the_post();

			// Featured image
			$featured_image_id = get_post_thumbnail_id( $post->ID );
			$featured_image = wp_get_attachment_image_src( $featured_image_id, $args['crop'] );

			// If the post doesn't have a featured image set, move on.
			if ( ! $featured_image ) {
				continue;
			}

			$image = array(
				'crop'			=> $args['crop'],
				'id'			=> 0,
				'alt'			=> get_the_title($featured_image_id),
				'src'			=> $featured_image[0],
				'width'			=> $featured_image[1],
				'height'		=> $featured_image[2],
				'thumb'			=> '',
				'title'			=> '',
				'desc'			=> '',
				'desc_wpautop'	=> false,
				'link'			=> '',
				'link_url'		=> '',
				'addon'			=> ''
			);

			// Thumbnail
			if ( $args['nav_thumbs'] ) {
				$thumb = wp_get_attachment_image_src( $featured_image_id, apply_filters('themeblvd_simple_slider_thumb_crop', 'tb_thumb') );
				$image['thumb'] = $thumb[0];
			}

			// Title
			if ( $args['title'] ) {
				$image['title'] = get_the_title();
			}

			// Description (meta)
			if ( $args['meta'] ) {
				switch( $args['style'] ) {

					case 'style-1' :
						$image['desc'] = themeblvd_get_meta( apply_filters('themeblvd_post_slider_style_1_args', array('include' => array('format', 'time', 'author', 'comments')) ) );
						break;

					case 'style-2' :
						$image['desc'] = themeblvd_get_meta( apply_filters('themeblvd_post_slider_style_2_args', array('include' => array('time', 'author', 'comments'), 'time' => 'ago', 'comments' => 'mini') ) );
						break;

					case 'style-3' :
						if ( has_category() ) {
							$image['addon'] .= sprintf( '<div class="category-label bg-primary">%s</div>', get_the_category_list(', ') );
						}
						$image['addon'] .= themeblvd_get_meta( apply_filters('themeblvd_post_slider_style_3_args', array('include' => array('time', 'comments'), 'comments' => 'mini') ) );

				}
			}

			// Description (excerpts)
			if ( $args['excerpts'] ) {
				$image['desc'] .= '<p class="carousel-excerpt">'.get_the_excerpt().'</p>';
			}

			// Link / Button
			if ( $args['slide_link'] == 'image_post' ) {

				// Link full image slide to the post
				$image['link'] = '_self';
				$image['link_url'] = get_the_permalink();

			} else if ( $args['slide_link'] == 'image_link' ) {

				// Link the full image slide to whatever the user
				// has setup as the featured image link
				$type = get_post_meta( $post->ID, '_tb_thumb_link', true );

				if ( $type && $type != 'inactive'  ) {
					switch ( $type ) {
						case 'post' :
							$image['link'] = '_self';
							$image['link_url'] = get_the_permalink();
							break;
						case 'thumbnail' :
							$image['link'] = 'image';
							$full = wp_get_attachment_image_src( $featured_image_id, 'tb_x_large' );
							$image['link_url'] = $full[0];
							break;
						case 'image' :
							$image['link'] = 'image';
							$image['link_url'] = get_post_meta( $post->ID, '_tb_image_link', true );
							break;
						case 'video' :
							$image['link'] = 'video';
							$image['link_url'] = get_post_meta( $post->ID, '_tb_video_link', true );
							break;
						case 'external' :
							$image['link'] = '_blank';
							$image['link_url'] = get_post_meta( $post->ID, '_tb_external_link', true );
							break;
					}
				}

			} else if ( $button ) {

				// Add button that links to the post
				$btn = themeblvd_button( $button['text'], get_the_permalink(), $button['color'], $button['target'], $button['size'], null, get_the_title(), null, null, $button['addon'] );
				$image['desc'] .= '<p class="carousel-button">'.$btn.'</p>';

			}

			// Attach slide to the stack
			$images[] = $image;

		}

		// Reset Post Data
		wp_reset_postdata();

	}

	$output = themeblvd_get_simple_slider( $images, $args );

	return apply_filters( 'themeblvd_post_slider', $output, $args );
}

/**
 * Display post slider
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for slider
 */
function themeblvd_post_slider( $args ) {
	echo themeblvd_get_post_slider( $args );
}

/**
 * Get Mini Post List
 *
 * @since 2.1.0
 *
 * @param string $query Query params and any other options
 * @param string|bool $thumb Thumbnail display size (not image crop) - small, smaller, smallest, or FALSE
 * @param boolean $meta Show meta info or not
 * @return string $output HTML to output
 */
function themeblvd_get_mini_post_list( $query = '', $thumb = 'smaller', $meta = true ) {

	$class = 'tb-mini-post-list';

	if ( $thumb && $thumb !== 'hide' ) {
		$class .= ' thumb-'.$thumb;
		themeblvd_set_att('thumbs', $thumb);
	} else {
		$class .= ' thumb-hide';
		themeblvd_set_att('thumbs', false);
	}

	if ( $meta && $meta !== 'hide' ) {
		themeblvd_set_att('show_meta', true);
	} else {
		themeblvd_set_att('show_meta', false);
	}

	$element = false;

	if ( ! empty($query['element']) ) {
		$element = true;
	}

	$shortcode = false;

	if ( ! empty($query['shortcode']) ) {
		$shortcode = true;
	}

	$args = array(
		'display'	=> 'mini-list',
		'context'	=> 'mini-list',
		'part'		=> 'list_mini',	// by default, content-mini-list.php
		'element'	=> $element,
		'shortcode'	=> $shortcode,
		'class'		=> $class
	);

	if ( is_string($query) ) {
		$args['query'] = str_replace('numberposts', 'posts_per_page', $query);
	} else {
		$args = array_merge( $args, $query );
	}

	ob_start();
	themeblvd_loop($args);
	return apply_filters( 'themeblvd_mini_post_list', ob_get_clean(), $query, $thumb, $meta );
}

/**
 * Display Mini Post List
 *
 * @since 2.1.0
 *
 * @param string $query Query params and any other options
 * @param string|bool $thumb Thumbnail display size (not image crop) - small, smaller, smallest, or FALSE
 * @param boolean $meta Show meta info or not
 * @return string $output HTML to output
 */
function themeblvd_mini_post_list( $query = '', $thumb = 'smaller', $meta = true ) {
	echo themeblvd_get_mini_post_list( $query, $thumb, $meta );
}

/**
 * Get Mini Post Grid
 *
 * @since 2.1.0
 *
 * @param
 * @return string $output HTML to output
 */
function themeblvd_get_mini_post_grid( $query = '', $align = 'left', $thumb = 'smaller', $gallery = '' ) {

	if ( $gallery ) {

		themeblvd_set_att('gallery', true);
		$query = array();
		$pattern = get_shortcode_regex();

		if ( preg_match( "/$pattern/s", $gallery, $match ) && 'gallery' == $match[2] ) {

			$atts = shortcode_parse_atts( $match[3] );

			if ( ! empty( $atts['ids'] ) ) {
				$query = array(
					'post_type'			=> 'attachment',
					'post_status'		=> 'inherit',
					'post__in' 			=> explode( ',', $atts['ids'] ),
					'orderby'           => 'post__in',
					'posts_per_page' 	=> -1
				);
			} else {
				return sprintf('<div class="alert alert-warning">%s<br /><code>[gallery ids="1,2,3"]</code></div>', __('Oops! There aren\'t any ID\'s in your gallery shortcode. It should be formatted like:', 'themeblvd_front'));
			}

		} else {
			return sprintf('<div class="alert alert-warning">%s</div>', __('Oops! You used the gallery override for this mini post grid, but didn\'t use the [gallery] shortcode.', 'themeblvd_front'));
		}

	} else {

		themeblvd_set_att('gallery', false);

		if ( is_string($query) ) {
			$query = str_replace('numberposts', 'posts_per_page', $query); // Backwards compat
			$query .= '&meta_key=_thumbnail_id'; // Only query posts with featured image
		} else {
			$query['meta_key'] = '_thumbnail_id';
		}

	}

	$element = false;

	if ( ! empty($query['element']) ) {
		$element = true;
	}

	$shortcode = false;

	if ( ! empty($query['shortcode']) ) {
		$shortcode = true;
	}

	$args = array(
		'display'	=> 'mini-grid',
		'context'	=> 'mini-grid',
		'part'		=> 'grid_mini',	// by default, content-mini-list.php
		'element'	=> $element,
		'shortcode'	=> $shortcode,
		'class'		=> sprintf('tb-mini-post-grid clearfix themeblvd-gallery thumb-%s thumb-align-%s', $thumb, $align)
	);

	if ( $gallery || is_string($query) ) {
		$args['query'] = $query;
	} else {
		$args = array_merge( $args, $query );
	}

	ob_start();
	themeblvd_loop($args);
	return apply_filters( 'themeblvd_mini_post_grid', ob_get_clean(), $align, $thumb, $gallery );
}

/**
 * Display Mini Post Grid
 *
 * @since 2.1.0
 *
 * @param array $options Options for many post grid
 */
function themeblvd_mini_post_grid( $query = '', $align = 'left', $thumb = 'smaller', $gallery = '' ) {
	echo themeblvd_get_mini_post_grid( $query, $align, $thumb, $gallery );
}