<?php
/**
 * Post List (secondary loops)
 *
 * @since 2.5.0
 *
 * @param array $args All arguments for post list
 * @param string $part Template part to use for loop
 */
function themeblvd_post_list( $args = array() ){

	global $wp_query;
	global $post;
	global $more;

	// Setup and extract $args
	$defaults = array(
		'title'				=> '',						// Title for element
		'display'			=> 'paginated',				// How to display - list or paginated
		'source'			=> '',						// Source of posts - category, tag, query
		'categories'		=> array('all' => 1),		// Post categories to include
		'category_name'		=> '',						// Force category_name string of query
		'cat'				=> '',						// Force cat string of query
		'tag'				=> '', 						// Force tag string of query
		'posts_per_page'	=> '6',						// Number of posts
		'thumbs'			=> 'default',				// Size of featured images - default, small, full, hide ("small" no always supported)
		'content'			=> 'default',				// Full content or excerpts - default, content, excerpt
		'orderby'			=> 'date',					// Orderby param for posts query
		'order'				=> 'DESC',					// Order param for posts query
		'offset'			=> '0',						// Offset param for posts query
		'query'				=> '',						// Custom query string
		'part'				=> '',						// For custom template part for each post
		'class'				=> 'list-wrap',				// Class for DIV just inside .post_list
		'wp_query'			=> false					// Whether to pull from primary WP query
	);
	$args = wp_parse_args( $args, $defaults );

	// Is this a paginated post list?
	$paginated = false;

	if ( $args['display'] == 'paginated' ) {
		$paginated = true;
	}

	// What template part should we include?
	if ( $args['part'] ) {

		$part = $args['part'];

	} else {

		$part = 'list';

		if ( strpos( $args['class'], 'shortcode-list-wrap' ) !== false ) {
			$part = 'list_shortcode';
		} else if ( $paginated ) {
			$part = 'list_paginated';
		}

	}

	// Content or excerpt (if main wp_query, this will
	// already be globally set)
	if ( ! $args['wp_query'] ) {

		if ( $args['content'] == 'default' ) {
			$content = themeblvd_get_option( 'blog_content', null, 'content' );
		} else {
			$content = $args['content'];
		}

		$content = themeblvd_set_att( 'content', $content );

	}

	// Thumbnails
	if ( ! is_archive() ) {

		if ( $args['thumbs'] == 'default' ) {
			$size = themeblvd_get_option( 'blog_thumbs', null, 'small' );
		} else {
			$size = $args['thumbs'];
		}

		$size = themeblvd_set_att( 'size', $size );
	}

	// Make sure if this was a single post, that location
	// doesn't get passed onto featured images of this loop.
	themeblvd_set_att('location', 'primary');

	// Query
	if ( $args['wp_query'] ) {

		$posts = $wp_query; // Pull from primary query

	} else {

		if ( $paginated ) {

			// There can only be one "second query"; so if one
			// already exists, that's our boy.
			$query_args = themeblvd_get_second_query();

			if ( ! $query_args ) {
				// Set the second query in global $themeblvd_query.
				// We only do this for paginated queries.
				$query_args = themeblvd_set_second_query( $args, 'list' ); // Sets global var and gets for local var
			}

		} else {

			// Standard query for non-paginated posts
			$query_args = themeblvd_get_posts_args( $args, 'list' );

		}

		$query_args = apply_filters( 'themeblvd_posts_args', $query_args, $args, 'list' );

		// Get posts
		$posts = new WP_Query( $query_args );

	}

	// Start output
	$class = 'post_list';

	if ( $paginated ) {
		$class .= ' post_list_paginated';
	}

	echo '<div class="'.$class.'">';

	if ( $args['title'] ) {
		printf( '<h3 class="title">%s</h3>', $args['title'] );
	}

	if ( $posts->have_posts() ) {

		// Let the world know we're doing a secondary loop
		if ( ! $args['wp_query'] ) {
			themeblvd_set_att('doing_second_loop', true);
		}

		// For default framework setup, we want secondary loops to show meta
		themeblvd_set_att('show_meta', true);

		// If you need to modify any global attributes for passing
		// to template parts, hook in here!
		do_action( 'themeblvd_post_list_before_loop', $args );

		echo '<div class="'.$args['class'].'">'; // default is .list-wrap, [post_list] shortcode will override

		while( $posts->have_posts() ) {

			$posts->the_post();
			$more = 0;

			// Get template part, framework default is content-list.php
			get_template_part( 'content', themeblvd_get_part($part) );

		}

		echo '</div><!-- .list-wrap (end) -->';

		do_action( 'themeblvd_post_list_after_loop', $args );

		// Let the world know we've finished our secondary loop
		if ( ! $args['wp_query'] ) {
			themeblvd_set_att('doing_second_loop', false);
		}

	} else {

		// No posts to display
		printf( '<p>%s</p>', themeblvd_get_local( 'archive_no_posts' ) );

	}

	// Pagination
	if ( $paginated ) {
		themeblvd_pagination( $posts->max_num_pages );
	}

	// Reset Post Data
	wp_reset_postdata();

	// End output
	echo '</div><!-- .post_list (end) -->';

}

/**
 * Post List (primary loop)
 *
 * @since 2.5.0
 */
function themeblvd_the_post_list() {

	$args = array(
		'display'	=> 'paginated',
		'wp_query' 	=> true
	);

	if ( is_search() ) {
		$args['part'] = 'search';
	} else if ( is_archive() ) {
		$args['part'] = 'archive';
	} else if ( is_home() ) {
		$args['parts'] = 'index';
	}

	$args = apply_filters( 'themeblvd_the_post_list_args', $args );

	themeblvd_post_list( $args );

}

/**
 * Post Grid (secondary loops)
 *
 * @since 2.5.0
 *
 * @param array $args Description
 * @param string $part Template part to use for loop
 */
function themeblvd_post_grid( $args = array() ){

	global $post;
	global $more;

	// Setup and extract $args
	$defaults = array(
		'title'				=> '',								// Title for element
		'display'			=> 'paginated',						// How to display, grid, paginated, or slider
		'source'			=> '',								// Source of posts - category, tag, query
		'categories'		=> array( 'all' => 1 ),				// Post categories to include
		'category_name'		=> '',								// Force category_name string of query
		'cat'				=> '',								// Force cat string of query
		'tag'				=> '', 								// Force tag string of query
		'columns'			=> themeblvd_get_att( 'columns' ),	// Number of columns
		'rows'				=> '3',								// Number of rows
		'slides'			=> '3',								// Number of slides
		'orderby'			=> 'date',							// Orderby param for posts query
		'order'				=> 'DESC',							// Order param for posts query
		'offset'			=> '0',								// Offset param for posts query
		'pages'				=> '',								// List of page slugs
		'query'				=> '',								// Custom query string
		'timeout'			=> '3',								// If slider, seconds between trasitinos
		'nav'				=> '1',								// If slider, whether to show controls
		'crop'				=> '',								// Custom image crop size
		'part'				=> '',								// For custom template part for each post
		'class'				=> 'grid-wrap',						// Class for DIV just inside .post_grid
		'wp_query'			=> false							// Whether to pull from primary WP query
	);
	$args = wp_parse_args( $args, $defaults );

	// Is this a paginated post grid?
	$paginated = false;

	if ( $args['display'] == 'paginated' ) {
		$paginated = true;
	}

	// What template part should we include?
	if ( $args['part'] ) {

		$part = $args['part'];

	} else {

		$part = 'grid';

		if ( strpos( $args['class'], 'grid-list-wrap' ) !== false ) {
			$part = 'grid_shortcode';
		} else if ( $paginated ) {
			$part = 'grid_paginated';
		}

	}

	// Number of columns (i.e. posts per row)
	$columns = '3';

	if ( $args['columns'] ) {
		$columns = $args['columns'];
	}

	$columns = themeblvd_set_att( 'columns', $columns );

	// Global grid class
	$class = themeblvd_set_att( 'class', sprintf( 'col %s', themeblvd_grid_class(intval($columns)) ) );

	// Crop and size
	$size = themeblvd_set_att( 'size', themeblvd_grid_thumb_class( $columns ) );
	$crop = $size;

	if ( $args['crop'] ) {
		$crop = $args['crop'];
	}

	$crop = themeblvd_set_att( 'crop', $crop );

	if ( $args['wp_query'] ) {

		// Pull from primary query
		$posts = $wp_query;

	} else {

		// Setup query
		if ( $paginated ) {

			// There can only be one "second query"; so if one
			// already exists, that's our boy.
			$query_args = themeblvd_get_second_query();

			if ( ! $query_args ) {

				// Set the second query in global $themeblvd_query.
				// We only do this for paginated queries.
				$args['posts_per_page'] = '-1';

				if ( $args['display'] == 'paginated' && $args['rows'] ) {
					$args['posts_per_page'] = $args['rows']*$columns;
				}

				$query_args = themeblvd_set_second_query( $args, 'grid' ); // Sets global var and gets for local var
			}

		} else {

			// Standard query for non-paginated posts
			$query_args = themeblvd_get_posts_args( $args, 'grid' );

		}

		$query_args = apply_filters( 'themeblvd_posts_args', $query_args, $args, 'grid' );

		// If it's a post grid slider, pass it on with the query.
		if ( $args['display'] == 'slider' ) {
			$args['query'] = $query_args;
			themeblvd_grid_slider($args);
			return;
		}

		// Get posts
		$posts = new WP_Query( $query_args );

	}

	// Start output
	$class = 'post_grid';

	if ( $paginated ) {
		$class .= ' post_grid_paginated';
	}

	echo '<div class="'.$class.'">';

	if ( $args['title'] ) {
		printf( '<h3 class="title">%s</h3>', $args['title'] );
	}

	if ( $posts->have_posts() ) {

		// Let the world know we're doing a secondary loop
		if ( ! $args['wp_query'] ) {
			themeblvd_set_att('doing_second_loop', true);
		}

		do_action( 'themeblvd_post_grid_before_loop', $args );

		echo '<div class="'.$args['class'].'">'; // default is .grid-wrap, [post_grid] shortcode will override

		$counter = themeblvd_set_att( 'counter', 1 );
		$total = $posts->post_count;

		// Open first row
		themeblvd_open_row();

		// Start loop
		while ( $posts->have_posts() ) {

			$posts->the_post();
			$more = 0;

			// Get template part, framework default is content-grid.php
			get_template_part( 'content', themeblvd_get_part($part) );

			// If last post in a row, but not the very last post.
			if ( $counter % $columns == 0 && $total != $counter ) {

				// Close current row
				themeblvd_close_row();

				// Open the next row
				themeblvd_open_row();

			}

			// Increment the counter with global template attribute accounted for
			$counter = themeblvd_set_att( 'counter', $counter+1 );

		}

		// Close last row
		themeblvd_close_row();

		echo '</div><!-- .grid-wrap (end) -->';

		do_action( 'themeblvd_post_grid_after_loop', $args );

		// Let the world know we've finished our secondary loop
		if ( ! $args['wp_query'] ) {
			themeblvd_set_att('doing_second_loop', false);
		}

	} else {

		// No posts to display
		printf( '<p>%s</p>', themeblvd_get_local( 'archive_no_posts' ) );

	}

	// Pagination
	if ( $paginated ) {
		themeblvd_pagination( $posts->max_num_pages );
	}

	// Reset Post Data
	wp_reset_postdata();

	// End output
	echo '</div><!-- .post_grid (end) -->';
}

/**
 * Post Grid (primary loop)
 *
 * @since 2.5.0
 */
function themeblvd_the_post_grid() {

	$args = apply_filters('themeblvd_the_post_grid_args', array(
		'display'	=> 'paginated',
		'wp_query' 	=> true
	));

	themeblvd_post_grid( $args );

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
		'display'	=> 'slider',	// How to display logos, grid or slider
		'columns'	=> '3',			// Number of logos per slide
		'nav'		=> '1',			// If slider, whether to display nav
		'timeout'	=> '3',			// If slider, seconds in between auto rotation
    );
    $args = wp_parse_args( $args, $defaults );

    if ( ! $args['query'] ) {
    	return __('Error: No query supplied.', 'themeblvd');
    }

	$class = 'tb-grid-slider tb-block-slider post_grid flexslider';

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

	$output .= '<div class="tb-grid-slider-inner tb-block-slider-inner">';

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
		'posts_per_page'	=> '6',						// Number of rows (grid only)
		'orderby'			=> 'date',					// Orderby param for posts query
		'order'				=> 'DESC',					// Order param for posts query
		'offset'			=> '0',						// Offset param for posts query
		'query'				=> '',						// Custom query string
    	'crop'				=> 'slider-large',			// Crop size for slide images
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
    	'meta'				=> '1',						// Whether to include post meta on each slide
    	'cover'				=> '0',						// popout: Whether images horizontal space 100%
		'position'			=> 'middle center',			// popout: If cover is true, how slider images are positioned (i.e. with background-position)
		'height_desktop'	=> '400',					// popout: If cover is true, slider height for desktop viewport
		'height_tablet'		=> '300',					// popout: If cover is true, slider height for tablet viewport
		'height_mobile'		=> '200',					// popout: If cover is true, slider height for mobile viewport
    );
    $args = wp_parse_args( $args, $defaults );

    // Pass a class onto the slider so we know
    // it's the post slider for styling
    $args['class'] = 'tb-post-slider';

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
				'title'			=> get_the_title(),
				'desc'			=> '',
				'desc_wpautop'	=> false,
				'link'			=> '',
				'link_url'		=> ''
			);

			// Thumbnail
			if ( $args['nav_thumbs'] ) {
				$thumb = wp_get_attachment_image_src( $featured_image_id, apply_filters('themeblvd_simple_slider_thumb_crop', 'square_small') );
				$image['thumb'] = $thumb[0];
			}

			// Description (meta)
			if ( $args['meta'] ) {
				ob_start();
				themeblvd_blog_meta();
				$image['desc'] = apply_filters( 'themeblvd_post_slider_meta', ob_get_clean() );
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
							$full = wp_get_attachment_image_src( $featured_image_id, 'full' );
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