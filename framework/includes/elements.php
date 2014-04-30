<?php
/*------------------------------------------------------------*/
/* Elements
/*
/* - columns: 1-5 columns of content blocks (see /farmework/includes/content.php)
/* - divider: Basic divider line to break up content
/* - headline: Simple heading and optional slogan
/* - image: An image with optional link
/* - post_slider: Slider of posts, list or grid
/* - posts: Non-paginated display of posts, list or grid
/* - posts_paginated: Paginated display of posts, list or grid
/* - simple_slider: Simple slider utilizing Bootstrap carousel
/* - slider_auto: Slider of posts modeled after custom, standard slider
/* - slider: Custom built-slider from Theme Blvd Sliders plugin
/* - slogan: Slogan text plus optional call-to-action button
/* - tabs: Set of tabs utilizing Bootstrap tabs
/* - video: A video displayed using WordPress's embedding
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_divider' ) ) :
/**
 * Display divider.
 *
 * @since 2.0.0
 *
 * @param string $type Style of divider
 * @return string $output HTML output for divider
 */
function themeblvd_divider( $type = 'solid' ) {
	$output = '<div class="divider divider-'.$type.'"></div>';
	return apply_filters( 'themeblvd_divider', $output, $type );
}
endif;

if ( !function_exists( 'themeblvd_headline' ) ) :
/**
 * Display headline.
 *
 * @since 2.0.0
 *
 * @param array $args Options for headline
 * @return string $output HTML output for headline
 */
function themeblvd_headline( $args = array() ) {

	// Setup and extract $args
	$defaults = array(
		'text' 		=> '',		// Hadline text
		'tagline' 	=> '',		// Tagline below headline
		'tag' 		=> 'h1',	// Header wrapping headline - h1, h2, h3, etc
		'align' 	=> 'left'	// How to align the header - left, center, right
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_OVERWRITE );

	// Swap in current page's title for %page_title%
	$text = str_replace( '%page_title%', get_the_title( themeblvd_config( 'id' ) ), $text );

	// Output
	$output = '<'.$tag.' class="text-'.$align.'">';
	$output .= stripslashes( $text );
	$output .= '</'.$tag.'>';

	if ( $tagline ) {
		$output .= '<p class="text-'.$align.'">';
		$output .= stripslashes( $tagline );
		$output .= '</p>';
	}

	return apply_filters( 'themeblvd_headline', $output, $args );
}
endif;

if ( !function_exists( 'themeblvd_image' ) ) :
/**
 * Display image.
 *
 * @since 2.5.0
 *
 * @param array $args Options for from "Image" element
 */
function themeblvd_image( $options ) {
	$image = $options['image'];
	unset( $options['image'] );
	echo themeblvd_get_image( $image, $options );
}
endif;

if ( !function_exists( 'themeblvd_jumbotron' ) ) :
/**
 * Display Bootstrap Jumbotron
 *
 * @since 2.4.2
 *
 * @param array $args Arguments for Jumbotron.
 */
function themeblvd_jumbotron( $args ) {

	$defaults = array(
		// Rest of $args are verified in themeblvd_get_jumbotron() ...
		'button' 		=> 0,
		'button_text' 	=> 'Get Started Today!',
		'button_color' 	=> 'default',
		'button_size'	=> 'large',
		'button_url' 	=> 'http://www.your-site.com/your-landing-page',
		'button_target' => '_self'
	);
	$args = wp_parse_args( $args, $defaults );

	// Setup content
	$content = '';

	if ( ! empty( $args['content'] ) ) {
		$content = $args['content'];
	}

	// Add buttont to content?
	if ( $args['button'] ) {
		$content .= "\n\n".themeblvd_button( $args['button_text'], $args['button_url'], $args['button_color'], $args['button_target'], $args['button_size'], null, $args['button_text'] );
	}

	echo themeblvd_get_jumbotron( $args, $content );

}
endif;

if ( !function_exists( 'themeblvd_post_slider' ) ) :
/**
 * Display post slider.
 * Desinged to work with FlexSlider jQuery plugin
 *
 * @since 2.0.0
 *
 * @param string $id Unique ID for element
 * @param array $args All options for posts
 * @param string $type Type of posts, grid or list
 * @param string $current_location Location of element, primary, featured, or featured_below
 */
function themeblvd_post_slider( $id, $args = array(), $type = 'grid', $current_location = 'primary' ) {

	global $post;

	// Setup and extract $args
	$defaults = array(
		'fx' 				=> 'slide',				// Effect for transitions
		'timeout' 			=> '0',					// Time between auto trasitions in seconds
		'nav_standard' 		=> '1', 				// Show standard nav - true, false
		'nav_arrows' 		=> '1', 				// Show nav arrows - true, false
		'pause_play'		=> '1', 				// Show pause/play buttons - true, false
		'source'			=> '',					// Source of posts - category, tag, query
		'categories'		=> array( 'all' => 1 ),	// Post categories to include
		'category_name'		=> '',					// Force category_name string of query
		'cat'				=> '',					// Force cat string of query
		'tag'				=> '', 					// Force tag string of query
		'columns'			=> '3', 				// Number of columns (grid only)
		'rows'				=> '3', 				// Number of rows (grid only)
		'posts_per_slide'	=> '', 					// Posts per slide (list only)
		'thumbs'			=> 'default', 			// Size of featured iamges (list only) - default, small, full, hide
		'content'			=> 'default', 			// Full content or excerpts (list only) - default, content, excerpt
		'numberposts'		=> '-1',				// Total number of posts to query for slider
		'orderby'			=> 'date', 				// Orderby param for posts query
		'order'				=> 'DESC', 				// Order param for posts query
		'offset'			=> '0', 				// Offset param for posts query
		'query'				=> '',					// Custom query string
		'crop'				=> '' 					// Custom image crop size (grid only)
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_OVERWRITE );

	// Location and query string
	$location = themeblvd_set_att( 'location', $current_location );

	// Configure additional CSS classes
	$classes = '';
	$nav_standard == '1' ? $classes .= ' show-nav_standard' : $classes .= ' hide-nav_standard';
	$nav_arrows == '1' ? $classes .= ' show-nav_arrows' : $classes .= ' hide-nav_arrows';
	$pause_play == '1' ? $classes .= ' show-pause_play' : $classes .= ' hide-pause_play';

	// Config before query string
	if ( $type == 'grid' ) {
		$columns = themeblvd_set_att( 'columns', $columns );
		$posts_per_slide = $columns*$rows;
		$class = themeblvd_set_att( 'class', themeblvd_grid_class( $columns ) );
		$size = themeblvd_set_att( 'size', themeblvd_grid_thumb_class( $columns ) );
		$crop = ! empty( $crop ) ? $crop : $size;
		$crop = themeblvd_set_att( 'crop', $crop );
	} else {
		$content = $content == 'default' ? themeblvd_get_option( 'blog_content', null, 'content' ) : $content;
		$content = themeblvd_set_att( 'content', $content );
		$size = $thumbs == 'default' ? themeblvd_get_option( 'blog_thumbs', null, 'small' ) : $thumbs;
		$size = themeblvd_set_att( 'size', $size );
	}

	// Generated query args
	$query_args = themeblvd_get_posts_args( $args, $type, true );
	$query_args = apply_filters( 'themeblvd_post_slider_args', $query_args, $args, $type, $current_location );

	// Get posts
	$posts = get_posts( $query_args );

	// Manual offset
	if ( 'query' != $source || ( ! $source && ! $query ) ) { // check for custom query
		if ( $numberposts == -1 && $offset > 0 ) {
			for( $i = 0; $i < $offset; $i++ ) {
				unset( $posts[$i] );
			}
		}
	}

	// Slider JS
	themeblvd_standard_slider_js( $id, $args );
	?>
	<div class="tb-post-<?php echo $type; ?>-slider">
		<div id="tb-slider-<?php echo $id; ?>" class="slider-wrapper standard-slider-wrapper">
			<div class="slider-inner<?php echo $classes; ?>">
				<div class="slides-wrapper slides-wrapper-<?php echo $id; ?>">
					<div class="slides-inner">
						<div class="slider standard-slider flexslider">
							<div class="loader"></div>
							<ul class="slides">
								<?php
								if ( ! empty( $posts ) ) {

									do_action( 'themeblvd_post_'.$type.'_slider_before_loop', $args );

									if ( $type == 'grid' ) {

										/*-------------------------------------------*/
										/* Post Grid Loop
										/*-------------------------------------------*/

										$counter = themeblvd_set_att( 'counter', 1 );
										$per_slide_counter = 1;
										$number_of_posts = count( $posts );

										foreach ( $posts as $post ) {

											setup_postdata( $post );

											// Start first slide and first row.
											if ( $counter == 1 ) {
												echo '<li class="slide">';
												echo '<div class="post_'.$type.'">';
												themeblvd_open_row();
											}

											// Include template part, framework default is content-grid.php
											get_template_part( 'content', themeblvd_get_part( 'grid_slider' ) );

											// Add in the complicated stuff to break up slides, rows, and columns
											if ( $per_slide_counter == $posts_per_slide ) {

												// End of a slide and thus end the row
												themeblvd_close_row();
												echo '</div><!-- .post_'.$type.' (end) -->';
												echo '</li>';

												// And if posts aren't done yet, open a
												// new slide and new row.
												if ( $number_of_posts != $counter ) {
													echo '<li class="slide">';
													echo '<div class="post_'.$type.'">';
													themeblvd_open_row();
												}

												// Set the posts per slide counter back to
												// 0 for the next slide.
												$per_slide_counter = 1;

											} elseif ( $per_slide_counter % $columns == 0  ) {

												// End row only
												themeblvd_close_row();

												// Open a new row if there are still post left
												// in this slide.
												if ( $posts_per_slide != $per_slide_counter ) {
													themeblvd_open_row();
												}

												// Increase number of posts per slide cause
												// we're not done yet.
												$per_slide_counter++;

											} else {

												// Increment posts per slide number
												// if nothing else is happenning.
												$per_slide_counter++;

											}

											// Increment the counter with global template attribute accounted for
											$counter = themeblvd_set_att( 'counter', $counter+1 );

										}
										wp_reset_postdata();

									} else {

										/*-------------------------------------------*/
										/* Post List Loop
										/*-------------------------------------------*/

										$counter = themeblvd_set_att( 'counter', 1 );
										$per_slide_counter = 1;
										$number_of_posts = count( $posts );

										foreach ( $posts as $post ) {

											setup_postdata( $post );

											// The first post
											if ( $counter == 1 ) {
												echo '<li class="slide">';
												echo '<div class="post_'.$type.'">';
											}

											// Include template part, framework default is content-list.php
											get_template_part( 'content', themeblvd_get_part( 'list_slider' ) );

											// Add in the complicated stuff
											if ( $per_slide_counter == $posts_per_slide ) {

												// End of a slide and thus end the row
												echo '</div><!-- .post_'.$type.' (end) -->';
												echo '</li>';

												// And if posts aren't done yet, open a
												// new slide
												if ( $number_of_posts != $counter ) {
													echo '<li class="slide">';
													echo '<div class="post_'.$type.'">';
												}

												// Set the posts per slide counter back to
												// 0 for the next slide.
												$per_slide_counter = 1;

											} else {

												// Increment posts per slide number
												// if nothing else is happenning.
												$per_slide_counter++;

											}

											// Increment the counter with global template attribute accounted for
											$counter = themeblvd_set_att( 'counter', $counter+1 );
										}

										wp_reset_postdata();

									}

									do_action( 'themeblvd_post_'.$type.'_slider_after_loop', $args );

								} else {

									// No posts to display
									echo '<p>'.themeblvd_get_local( 'archive_no_posts' ).'</p>';

								}
								?>
							</ul>
						</div><!-- .slider (end) -->

					</div><!-- .slides-inner (end) -->
				</div><!-- .slides-wrapper (end) -->
			</div><!-- .slider-inner (end) -->
			<div class="design-1"></div>
			<div class="design-2"></div>
			<div class="design-3"></div>
			<div class="design-4"></div>
		</div><!-- #<?php echo $id; ?> (end) -->
	</div>
	<?php
}
endif;

if ( !function_exists( 'themeblvd_posts' ) ) :
/**
 * Display post list or post grid
 *
 * @since 2.0.0
 *
 * @param array $args All options for posts
 * @param string $type Type of posts, grid or list
 * @param string $current_location Current location of element, featured or primary
 */
function themeblvd_posts( $args = array(), $type = 'list', $current_location = 'primary' ) {

	global $post;
	global $more;
	$more = 0;

	// Current location if relevant
	$location = themeblvd_set_att( 'location', $current_location );

	// Setup and extract $args
	$defaults = array(
		'source'		=> '',					// Source of posts - category, tag, query
		'categories'	=> array( 'all' => 1 ),	// Post categories to include
		'category_name'	=> '',					// Force category_name string of query
		'cat'			=> '',					// Force cat string of query
		'tag'			=> '', 					// Force tag string of query
		'columns'		=> '3',					// Number of columns (grid only)
		'rows'			=> '3',					// Number of rows (grid only)
		'thumbs'		=> 'default',			// Size of featured iamges (list only) - default, small, full, hide
		'content'		=> 'default',			// Full content or excerpts (list only) - default, content, excerpt
		'numberposts'	=> '-1',				// Total number of posts to query (list only)
		'orderby'		=> 'date',				// Orderby param for posts query
		'order'			=> 'DESC',				// Order param for posts query
		'offset'		=> '0',					// Offset param for posts query
		'query'			=> '',					// Custom query string
		'crop'			=> '',					// Custom image crop size (grid only)
		'link'			=> '0',					// Show link below posts - true, false
		'link_text'		=> '',					// Text of link
		'link_url'		=> '',					// Href att of link
		'link_target'	=> ''					// Target att of anchor - _self, _blank
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_OVERWRITE );

	// Config before query
	if ( $type == 'grid' ) {
		$columns = themeblvd_set_att( 'columns', $columns );
		$class = themeblvd_set_att( 'class', themeblvd_grid_class( $columns ) );
		$size = themeblvd_set_att( 'size', themeblvd_grid_thumb_class( $columns ) );
		$crop = ! empty( $crop ) ? $crop : $size;
		$crop = themeblvd_set_att( 'crop', $crop );
	} else {
		$content = $content == 'default' ? themeblvd_get_option( 'blog_content', null, 'content' ) : $content;
		$content = themeblvd_set_att( 'content', $content );
		$size = $thumbs == 'default' ? themeblvd_get_option( 'blog_thumbs', null, 'small' ) : $thumbs;
		$size = themeblvd_set_att( 'size', $size );
	}

	// Setup query args
	$query_args = themeblvd_get_posts_args( $args, $type );
	$query_args = apply_filters( 'themeblvd_posts_args', $query_args, $args, $type, $current_location );

	// Get posts
	$posts = get_posts( $query_args );

	// Adjust offset if neccesary
	if ( 'query' != $source || ( ! $source && ! $query ) ) { // check for custom query
		if ( $type != 'grid' && intval($numberposts) == -1 && intval($offset) > 0 ) {
			for( $i = 0; $i < intval($offset); $i++ ) {
				unset( $posts[$i] );
			}
		}
	}

	// Start the output
	echo '<div class="post_'.$type.'">';

	if ( ! empty( $posts ) ) {

		do_action( 'themeblvd_post_'.$type.'_before_loop', $args );

		if ( $type == 'grid' ) {

			// Loop for post grid
			$counter = themeblvd_set_att( 'counter', 1 );
			$number_of_posts = count( $posts );

			foreach ( $posts as $post ) {

				setup_postdata( $post );

				// If this is the very first post, open the first row
				if ( $counter == 1 ) {
					themeblvd_open_row();
				}

				// Get template part, framework default is content-grid.php
				get_template_part( 'content', themeblvd_get_part( 'grid' ) );

				// If last post in a row, close the row
				if ( $counter % $columns == 0 ) {
					themeblvd_close_row();
				}

				// If first post in a row, open the row
				if ( $counter % $columns == 0 && $number_of_posts != $counter ) {
					themeblvd_open_row();
				}

				// Increment the counter with global template attribute accounted for
				$counter = themeblvd_set_att( 'counter', $counter+1 );

			}

			// In case the last row wasn't filled, close it now
			if ( $number_of_posts % $columns != 0 ) {
				themeblvd_close_row();
			}

		} else {

			// Loop for post list (i.e. Blog)
			foreach ( $posts as $post ) {

				setup_postdata( $post );

				// Get template part, framework default is content-list.php
				get_template_part( 'content', themeblvd_get_part( 'list' ) );

			}
		}

		do_action( 'themeblvd_post_'.$type.'_after_loop', $args );

	} else {

		// No posts to display
		echo '<p>'.themeblvd_get_local( 'archive_no_posts' ).'</p>';

	}

	wp_reset_postdata();

	echo '</div><!-- .post_'.$type.' (end) -->';

	// Show link
	if ( $link ) {
		printf( '<a href="%s" target="%s" title="%s" class="lead-link">%s</a>', $link_url, $link_target, $link_text, $link_text );
	}

}
endif;

if ( !function_exists( 'themeblvd_posts_paginated' ) ) :
/**
 * Display paginated post list or post grid
 *
 * @since 2.0.0
 *
 * @param array $options all options for posts
 * @param string $type Type of posts, grid or list
 * @param string $current_location Current location of element, featured or primary
 */
function themeblvd_posts_paginated( $args = array(), $type = 'list', $current_location = 'primary' ) {

	global $more;

	// Current location if relevant
	$location = themeblvd_set_att( 'location', $current_location );

	// Setup and extract $args
	$defaults = array(
		'source'			=> '',					// Source of posts - category, tag, query
		'categories' 		=> array( 'all' => 1 ),	// Post categories to include
		'tag'				=> '', 					// Tag to include
		'thumbs'			=> 'default',			// Size of featured iamges (list only) - default, small, full, hide
		'content'			=> 'default',			// Full content or excerpts (list only) - default, content, excerpt
		'columns'			=> '3',					// Number of columns (grid only)
		'rows'				=> '3',					// Number of rows (grid only)
		'posts_per_page'	=> '6',					// Posts per page (list only)
		'orderby'			=> 'date',				// Orderby param for posts query
		'order'				=> 'DESC',				// Order param for posts query
		'offset'			=> '0',					// Offset param for posts query
		'query'				=> '',					// Custom query string
		'crop'				=> ''					// Custom image crop size (grid only)
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_OVERWRITE );

	// Config before query string
	if ( $type == 'grid' ) {
		$columns = themeblvd_set_att( 'columns', $columns );
		$posts_per_page = $rows ? $columns*$rows : '-1';
		$args['posts_per_page'] = $posts_per_page; // Set new value to $args for parsing query
		$class = themeblvd_set_att( 'class', themeblvd_grid_class( $columns ) );
		$size = themeblvd_set_att( 'size', themeblvd_grid_thumb_class( $columns ) );
		$crop = ! empty( $crop ) ? $crop : $size;
		$crop = themeblvd_set_att( 'crop', $crop );
	} else {
		$posts_per_page = themeblvd_set_att( 'posts_per_page', $posts_per_page );
		$content = $content == 'default' ? themeblvd_get_option( 'blog_content', null, 'content' ) : $content;
		$content = themeblvd_set_att( 'content', $content );
		$size = $thumbs == 'default' ? themeblvd_get_option( 'blog_thumbs', null, 'small' ) : $thumbs;
		$size = themeblvd_set_att( 'size', $size );
	}

	/*------------------------------------------------------*/
	/* Query Args
	/*------------------------------------------------------*/

	// Set the second query in global $themeblvd_query.
	// We only do this for paginated queries.
	$query_args = themeblvd_set_second_query( $args, $type ); // Sets global var and gets for local var

	// Apply filters
	$query_args = apply_filters( 'themeblvd_posts_args', $query_args, $args, $type, $current_location );

	/*------------------------------------------------------*/
	/* The Loop
	/*------------------------------------------------------*/

	// Query posts
	$posts = new WP_Query( $query_args );

	// Start output
	echo '<div class="post_'.$type.'">';
	if ( $posts->have_posts() ) {

		do_action( 'themeblvd_post_'.$type.'_paginated_before_loop', $args );

		if ( $type == 'grid' ) {

			// Loop for post grid
			$counter = themeblvd_set_att( 'counter', 1 );
			while( $posts->have_posts() ) {

				$posts->the_post();
				$more = 0;

				// If this is the very first post, open the first row
				if ( $counter == 1 ) {
					themeblvd_open_row();
				}

				// Get template part, framework default is content-grid.php
				get_template_part( 'content', themeblvd_get_part( 'grid_paginated' ) );

				// If last post in a row, close the row
				if ( $counter % $columns == 0 ) {
					themeblvd_close_row();
				}

				// If first post in a row, open the row
				if ( $counter % $columns == 0 && $posts_per_page != $counter ) {
					themeblvd_open_row();
				}

				// Increment the counter with global template attribute accounted for
				$counter = themeblvd_set_att( 'counter', $counter+1 );
			}

			// In case the last row wasn't filled, close it now
			if ( ($counter-1) != $posts_per_page ) {
				themeblvd_close_row();
			}

		} else {

			// Loop for post list
			while( $posts->have_posts() ) {

				$posts->the_post();
				$more = 0;

				// Get template part, framework default is content-list.php
				get_template_part( 'content', themeblvd_get_part( 'list_paginated' ) );

			}

		}

		do_action( 'themeblvd_post_'.$type.'_paginated_after_loop', $args );

	} else {

		// No posts to display
		printf( '<p>%s</p>', themeblvd_get_local( 'archive_no_posts' ) );

	}

	// Pagination
	themeblvd_pagination( $posts->max_num_pages );

	echo '</div><!-- .post_'.$type.' (end) -->';

	// Reset Post Data
	wp_reset_postdata();

}
endif;

if ( !function_exists( 'themeblvd_slider_auto' ) ) :
/**
 * Display post slider.
 *
 * This is different from post list slider or post
 * grid slider. This slider mimics custom slider set
 * up in the slider manager, but provides a way to
 * automatically feed slides to them from posts.
 *
 * @since 2.2.1
 *
 * @param string $slider Slug of custom-built slider to use
 */
function themeblvd_slider_auto( $id, $args = array() ) {

	global $post;

	// Setup $args
	$defaults = array(
		'fx' 				=> 'slide', 		// Effect for transitions
		'smoothheight'		=> 'true',			// smoothHeight property if using "slide" transition
		'timeout' 			=> '3',				// Time between auto trasitions in seconds
		'nav_standard' 		=> '1',				// Show standard nav - true, false
		'nav_arrows'		=> '1',				// Show nav arrows - true, false
		'pause_play'		=> '1',				// Show pause/play buttons - true, false
		'pause_on_hover' 	=> 'disable',		// Pause on hover - pause_on, pause_on_off, disable
		'image' 			=> 'full',			// How to display featured images - full, align-right, align-left
		'image_size'		=> 'slider-large', 	// Crop size for full-size images
		'image_link' 		=> 'permalink',		// Where image link goes - option, permalink, lightbox, none
		'button' 			=> '',				// Text for button to lead to permalink - leave empty to hide
		'source' 			=> '',				// Source for the posts query
		'tag' 				=> '',				// Tag to pull posts from
		'category' 			=> '',				// Category slug to pull posts from
		'category_name'		=> '',				// Force category_name string of query
		'cat'				=> '',				// Force cat string of query
		'numberposts' 		=> '5',				// Number of posts/slides
		'orderby' 			=> 'date',			// Orderby param for posts query
		'order'				=> 'DESC',			// Order param for posts query
		'query' 			=> '',				// Custom query string
		'mobile_fallback' 	=> 'full_list'		// How to display on mobile - full_list, first_slide, display
	);
	$args = wp_parse_args( $args, $defaults );

	// Image Size
	$image_size = $args['image_size'];
	if ( $args['image'] == 'align-right' || $args['image'] == 'align-left' ) {
		$image_size = 'slider-staged';
	}

	// Format settings array so it matches the array
	// pulled if we were getting to this from a
	// custom-built slider.
	$settings = array(
		'fx' 				=> $args['fx'],
		'smoothheight' 		=> $args['smoothheight'],
	    'timeout' 			=> $args['timeout'],
	    'nav_standard' 		=> $args['nav_standard'],
	    'nav_arrows' 		=> $args['nav_arrows'],
	    'pause_play' 		=> $args['pause_play'],
	    'pause_on_hover' 	=> $args['pause_on_hover'],
	    'mobile_fallback' 	=> $args['mobile_fallback']
	);
	$settings = apply_filters( 'themeblvd_slider_auto_settings', $settings, $args );

	// Setup query
	$query_args = themeblvd_get_posts_args( $args, 'auto_slider' );

	// Get posts for slider
	$posts = get_posts( apply_filters( 'themeblvd_slider_auto_args', $query_args, $args ) );

	// Now loop through posts and setup an array of
	// slides that matches what would have been pulled
	// from a custom-built slider.
	$slides = array();
	$counter = 1;

	if ( $posts ) {
		do_action( 'themeblvd_slider_auto_before_loop', $args );
		foreach ( $posts as $post ) {

			// Setup post data for loop
			setup_postdata( $post );

			// Featured image ID
			$featured_image_id = get_post_thumbnail_id( $post->ID );
			$featured_image = wp_get_attachment_image_src( $featured_image_id, $image_size );

			// Image
			$image = array(
				'id' 		=> $featured_image_id,	// Attachment ID of image
				'title'		=> get_the_title(),		// Attachment title
				'mime_type'	=> 'image',				// Post mime type, i.e. image/jpeg, image/png, etc
				'display'	=> $featured_image[0],	// Cropped Image URL for slider display
				'width'		=> $featured_image[1],	// Width of cropped image
				'height'	=> $featured_image[2],	// Height of cropped image
				'size'		=> $image_size,			// Name of crop size, 'full' if not registered or selected by user
				'crop'		=> null,				// Crop mode, true for hard or false for soft
				'cropped'	=> null					// Whether the cropped image actually exists, or WP has returned original
			);

			// Elements to include in slide
			$includes = array( 'headline', 'description' );
			if ( $args['button'] ) {
				$includes[] = 'button';
			}

			// Image Link
			$image_link_type = $args['image_link'];
			$image_link_target = '';
			$image_link_url = '';

			// Use "Featured Image Link" setting from post
			if ( $image_link_type == 'option' ) {
				switch ( get_post_meta( get_the_ID(), '_tb_thumb_link', true ) ) {
					case 'post' :
						$image_link_type = 'permalink'; // Pass to next section
						break;
					case 'thumbnail' :
						$image_link_type = 'lightbox'; // Pass to next section
						break;
					case 'image' :
						$image_link_target = 'lightbox';
						$image_link_url = get_post_meta( get_the_ID(), '_tb_image_link', true );
						break;
					case 'video' :
						$image_link_target = 'lightbox_video';
						$image_link_url = get_post_meta( get_the_ID(), '_tb_video_link', true );
						break;
					case 'external' :
						$image_link_target = '_blank';
						$image_link_url = get_post_meta( get_the_ID(), '_tb_external_link', true );
						break;
				}
			}

			switch ( $image_link_type ) {
				case 'permalink' :
					$image_link_target = '_self';
					$image_link_url = get_permalink();
					break;
				case 'lightbox' :
					$image_link_target = 'lightbox';
					$image_link_url = wp_get_attachment_url( $featured_image_id );
					break;
			}

			if ( $image_link_url ) {
				$includes[] = 'image_link';
			}

			// Elements
			$elements = array(
				'include'		=> $includes,
				'image_link' 	=> array(
					'target' 	=> $image_link_target,
					'url'		=> $image_link_url
				),
				'headline' 		=> get_the_title(),
				'description'	=> get_the_excerpt(),
				'button'		=> array(
					'text'		=> $args['button'],
					'target' 	=> '_self',
					'url' 		=> get_permalink()
				)
			);

			// In Sliders plugin v1.1+, buttons in full width sliders are
			// no longer supported; so this will add a new paragraph with
			// a link to the post, if relevant.
			if ( defined( 'TB_SLIDERS_PLUGIN_VERSION' ) && version_compare( TB_SLIDERS_PLUGIN_VERSION, '1.1.0', '>=' ) ) {
				if ( 'full' == $args['image'] && in_array( 'button', $includes ) ) {
					$elements['description'] .= "\n\n"; // Slider display uses wpautop
					$elements['description'] .= sprintf('<a href="%s" title="%s">%s</a>', $elements['button']['url'], $elements['button']['text'], $elements['button']['text'] );
				}
			}

			// Add slide
			$slides['slide_'.$counter] = array(
				'slide_type' 		=> 'image',
				'position_image' 	=> $args['image'],
				'position' 			=> $args['image'],
				'elements' 			=> $elements,
				'image'				=> $image
			);
			$counter++;
		}
		wp_reset_postdata();
		do_action( 'themeblvd_slider_auto_after_loop', $args );
	}
	$slides = apply_filters( 'themeblvd_slider_auto_slides', $slides, $args, $posts );

	// Display post slider
	do_action( 'themeblvd_slider_auto', $id, $settings, $slides );
}
endif;

if ( !function_exists( 'themeblvd_slider' ) ) :
/**
 * Display slider.
 *
 * @since 2.0.0
 *
 * @param string $slider Slug of custom-built slider to use
 */
function themeblvd_slider( $slider ) {

	// Kill it if there's no slider
	if ( ! $slider ) {
		printf('<div class="alert warning"><p>%s</p></div>', themeblvd_get_local( 'no_slider_selected.' ) );
		return;
	}

	// Get Slider ID
	$slider_id = themeblvd_post_id_by_name( $slider, 'tb_slider' );
	if ( ! $slider_id ) {
		echo themeblvd_get_local( 'no_slider' );
		return;
	}

	// Gather info
	$type = get_post_meta( $slider_id, 'type', true );
	$settings = get_post_meta( $slider_id, 'settings', true );
	$slides = get_post_meta( $slider_id, 'slides', true );

	// Display slider based on its slider type
	do_action( 'themeblvd_'.$type.'_slider', $slider, $settings, $slides );
}
endif;

if ( !function_exists( 'themeblvd_simple_slider' ) ) :
/**
 * Display bootstrap coursel slider.
 *
 * @since 2.5.0
 *
 * @param array $args All options for simple slider
 */
function themeblvd_simple_slider( $options ) {
	$images = $options['images'];
	unset( $options['images'] );
	echo themeblvd_get_simple_slider( $images, $options );
}
endif;

if ( !function_exists( 'themeblvd_slogan' ) ) :
/**
 * Display slogan.
 *
 * @since 2.0.0
 *
 * @param array $args All options for slogan
 * @return string $output HTML output for slogan
 */
function themeblvd_slogan( $args = array() ) {

	// Setup and extract $args
	$defaults = array(
		'slogan'		=> '',						// Text for slogan
		'text_size'		=> 'large',					// Size of text - small, normal, medium, large
		'button'		=> 1,						// Show button - true, false
		'button_text'	=> 'Get Started Today!',	// Text for button
		'button_color'	=> 'default',				// Color of button - Use themeblvd_colors() to generate list
		'button_size'	=> 'large',					// Size of button - mini, small, default, large
		'button_url'	=> '',						// URL button goes to
		'button_target'	=> '_self'					// Button target - _self, _blank
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_OVERWRITE );

	// Wrapping class
	$class  = $button ? 'has_button' : 'text_only';
	$text_class = 'text_'.$text_size;

	// Output
	$output = '<div class="tb-slogan '.$class.'">';
	if ( $button ) {
		$output .= themeblvd_button( stripslashes($button_text), $button_url, $button_color, $button_target, $button_size );
	}
	$output .= '<span class="slogan-text '.$text_class.'">'.stripslashes( do_shortcode( $slogan ) ).'</span>';
	$output .= '</div><!-- .slogan (end) -->';

	return $output;
}
endif;

if ( !function_exists( 'themeblvd_tabs' ) ) :
/**
 * Display set of tabs.
 *
 * @since 2.0.0
 *
 * @param array $id unique ID for tab set
 * @param array $options all options for tabs
 * @return string $output HTML output for tabs
 */
function themeblvd_tabs( $id, $options ) {

	$nav = array( 'tabs', 'above' ); // Backup for someone updating who doesn't have this saved yet.
	$navigation = '';
	$content = '';
	$output = '';

	// Tabs or pills?
	$nav_type = $options['setup']['nav'];

	// For those using old method for tabs
    if ( 'tabs_above' == $nav_type || 'tabs_right' == $nav_type || 'tabs_below' == $nav_type || 'tabs_left' == $nav_type ) {
        $nav_type = 'tabs';
    } else if ( 'pills_above' == $nav_type || 'pills_below' == $nav_type ) {
        $nav_type = 'pills';
    }

    // Backup
    if ( 'tabs' != $nav_type && 'pills' != $nav_type ) {
    	$nav_type = 'tabs';
    }

	// Container classes
	$classes = 'tabbable';

	if ( $options['height'] ) {
		$classes .= ' fixed-height';
	}

	$classes .= ' tb-tabs-'.$options['setup']['style'];

	if ( 'pills' == $nav_type ) {
		$classes .= ' tb-tabs-pills';
	}

	// Allow deep linking directly to individual tabs?
	$deep = apply_filters( 'themeblvd_tabs_deep_linking', false );

	// Navigation
	$i = 0;
	$class = null;
	$navigation .= '<ul class="nav nav-'.$nav_type.'">';
	foreach ( $options['setup']['names'] as $key => $name ) {

		if ( $i == 0 ) {
			$class = 'active';
		}

		if ( $deep ) {
			$tab_id = str_replace( ' ', '_', $name );
			$tab_id = preg_replace('/\W/', '', strtolower($tab_id) );
		} else {
			$tab_id = $id.'-'.$key;
		}

		$navigation .= '<li class="'.$class.'"><a href="#'.$tab_id.'" data-toggle="'.str_replace('s', '', $nav_type).'" title="'.stripslashes($name).'">'.stripslashes($name).'</a></li>';
		$class = null;

		$i++;
	}
	$navigation .= '</ul>';

	// Tab content
	$i = 0;
	$content = '<div class="tab-content">';

	foreach ( $options['setup']['names'] as $key => $name ) {

		$class = '';
		if ( $i == '0' ) {
			$class = ' active';
		}

		if ( $deep ) {
			$tab_id = str_replace( ' ', '_', $name );
			$tab_id = preg_replace('/\W/', '', strtolower($tab_id) );
		} else {
			$tab_id = $id.'-'.$key;
		}

		$content .= '<div id="'.$tab_id.'" class="tab-pane fade'.$class.' in clearfix">';

		switch ( $options[$key]['type'] ) {

			// External Page
			case 'page' :

				// Get WP internal ID for the page
				$page_id = themeblvd_post_id_by_name( $options[$key]['page'], 'page' );

				// Use WP_Query to retrieve external page. We do it
				// this way to allow certain primary query-dependent
				// items such as galleries to work properly.
				$the_query = new WP_Query( 'page_id='.$page_id );

				// Standard WP loop, even though there should only be
				// a single post (i.e. our external page).
				while( $the_query->have_posts() ) {
					$the_query->the_post();
					$content .= apply_filters( 'themeblvd_the_content', get_the_content() );
				}

				// Reset Post Data
				wp_reset_postdata();
				break;

			// Raw content textarea
			case 'raw' :

				// Only negate simulated the_content filter if the option exists AND it's
				// been unchecked. This is for legacy purposes, as this feature
				// was added in v2.1.0
				if ( isset( $options[$key]['raw_format'] ) && ! $options[$key]['raw_format'] ) {
					$content .= do_shortcode( stripslashes( $options[$key]['raw'] ) ); // Shortcodes only
				} else {
					$content .= apply_filters( 'themeblvd_the_content', stripslashes( $options[$key]['raw'] ) );
				}
				break;

			// Floating Widget Area
			case 'widget' :

				if ( ! empty( $options[$key]['sidebar'] ) ) {
					$content .= '<div class="widget-area">';
					ob_start();
					dynamic_sidebar( $options[$key]['sidebar'] );
					$content .= ob_get_clean();
					$content .= '</div><!-- .widget-area (end) -->';
				}
				break;

		}
		$content .= '</div><!-- #'.$id.'-'.$key.' (end) -->';
		$i++;
	}
	$content .= '</div><!-- .tab-content (end) -->';

	// Construct final output
	$output = '<div class="'.$classes.'">';
	$output .= $navigation;
	$output .= $content;
	$output .= '</div><!-- .tabbable (end) -->';

	return $output;
}
endif;


if ( !function_exists( 'themeblvd_video' ) ) :
/**
 * Display video
 *
 * @since 2.5.0
 *
 * @param string $video_url URL to video, file URL or oEmbed compatible link
 * @param array $args Any extra arguments, currently not being used
 * @return string $output Final image HTML to output
 */
function themeblvd_video( $video_url, $args = array() ) {
	echo themeblvd_get_video( $video_url, $args );
}
endif;

/*------------------------------------------------------------*/
/* Deprecated Elements
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_tweet' ) ) :
/**
 * Display recent tweet.
 *
 * @since 2.0.0
 *
 * @deprecated 2.3.0
 * @param array $args all options for tweet
 * @return string $output HTML output for tweet
 */
function themeblvd_tweet( $args = array() ) {
	themeblvd_deprecated_function( __FUNCTION__, '2.3.0', null, __( 'Twitter functionality is no longer built into the Theme Blvd framework. Use Theme Blvd "Tweeple" plugin found in the WordPress plugin repository.', 'themeblvd' ) );
}
endif;