<?php
/*------------------------------------------------------------*/
/* Elements
/*
/* - columns: 1-5 columns of content blocks (see /farmework/includes/content.php)
/* - divider: Basic divider line to break up content
/* - headline: Simple heading and optional slogan
/* - image: An image with optional link
/* - jumbotron: Bootstrap jumbotron
/* - milestones: Milestone numbers with taglines
/* - post_slider: Slider of posts, list or grid
/* - posts: Non-paginated display of posts, list or grid
/* - posts_paginated: Paginated display of posts, list or grid
/* - simple_slider: Simple slider utilizing Bootstrap carousel
/* - slider_auto: Slider of posts modeled after custom, standard slider
/* - slider: Custom built-slider from Theme Blvd Sliders plugin
/* - slogan: Slogan text plus optional call-to-action button
/* - tabs: Set of tabs utilizing Bootstrap tabs
/* - toggles: Set of toggles utilizing Bootstrap panels
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
function themeblvd_divider( $args = array() ) {

	// Setup and extract $args
	$defaults = array(
		'type' 		=> 'shadow',	// Style of divider - dashed, shadow, solid, double-solid, double-dashed
		'width' 	=> '',			// A width for the divider in pixels
		'placement'	=> 'equal'		// Where the divider sits between the content - equal, above (closer to content above), below (closer to content below)
	);
	$args = wp_parse_args( $args, $defaults );

	$class = sprintf( 'tb-divider %s', $args['type'] );

	if ( $args['placement'] == 'up' || $args['placement'] == 'down' ) {
		$class .= ' suck-'.$args['placement'];
	}

	$style = '';

	if ( $args['width'] ) {
		$style .= sprintf( 'max-width: %spx;', $args['width'] );
	}

	$output = sprintf( '<div class="%s" style="%s"></div>', $class, $style );

	return apply_filters( 'themeblvd_divider', $output, $args['type'] );
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

if ( !function_exists( 'themeblvd_milestones' ) ) :
/**
 * Display milestones.
 *
 * @since 2.5.0
 *
 * @param array $args Options for from "Milestones" element
 */
function themeblvd_milestones( $options ) {

	$grid_class = '';

	$num = count($options['milestones']);

	if ( $num  >= 1 ) {
		$grid_class = themeblvd_grid_class( $num, apply_filters('themeblvd_milestones_stack', 'sm') );
	}

	$output = '<div class="tb-milestones row">';

	foreach ( $options['milestones'] as $milestone ) {
		$output .= sprintf('<div class="milestone-wrap %s">', $grid_class);
		$output .= sprintf('<span class="milestone" style="color: %s; font-size: %s;">%s<span class="num">%s</span>%s</span>', $milestone['color'], themeblvd_get_font_size($options['milestone_size']), $milestone['before'], $milestone['milestone'], $milestone['after']);
		$output .= sprintf('<span class="text" style="font-size: %s;">%s</span>', themeblvd_get_font_size($options['text_size']), $milestone['text']);
		$output .= '</div><!-- .milestone-wrap (end) -->';
	}

	$output .= '</div><!-- .tb-milestones (end) -->';

	return apply_filters( 'themeblvd_milestones', $output );
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
		'button' 				=> 0,
		'button_color' 			=> 'default',
		'button_custom'			=> array(
			'bg' 				=> '#ffffff',
			'bg_hover'			=> '#ebebeb',
			'border' 			=> '#cccccc',
			'text'				=> '#333333',
			'text_hover'		=> '#333333',
			'include_bg'		=> 1,
			'include_border'	=> 1
		),
		'button_text' 			=> 'Get Started Today!',
		'button_size'			=> 'large',
		'button_url' 			=> 'http://www.your-site.com/your-landing-page',
		'button_target' 		=> '_self',
		'button_icon_before'	=> '',
		'button_icon_after'		=> '',
	);
	$args = wp_parse_args( $args, $defaults );

	// Setup content
	$content = '';

	if ( ! empty( $args['content'] ) ) {
		$content = $args['content'];
	}

	// Add buttont to content?
	if ( $args['button'] ) {

		// Custom button styling
		$addon = '';

		if ( $args['button_color'] == 'custom' ) {

			if ( $args['button_custom']['include_bg'] ) {
				$bg = $args['button_custom']['bg'];
			} else {
				$bg = 'transparent';
			}

			if ( $args['button_custom']['include_border'] ) {
				$border = $args['button_custom']['border'];
			} else {
				$border = 'transparent';
			}

			$addon = sprintf( 'style="background-color: %1$s; border-color: %2$s; color: %3$s;" data-bg="%1$s" data-bg-hover="%4$s" data-text="%3$s" data-text-hover="%5$s"', $bg, $border, $args['button_custom']['text'], $args['button_custom']['bg_hover'], $args['button_custom']['text_hover'] );

		}

		$content .= "\n\n".themeblvd_button( stripslashes($args['button_text']), $args['button_url'], $args['button_color'], $args['button_target'], $args['button_size'], null, null, $args['button_icon_before'], $args['button_icon_after'], $addon );
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
		$class = themeblvd_set_att( 'class', themeblvd_grid_class( intval($columns) ) );
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
		'crop'			=> ''					// Custom image crop size (grid only)
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_OVERWRITE );

	// Config before query
	if ( $type == 'grid' ) {
		$columns = themeblvd_set_att( 'columns', $columns );
		$class = themeblvd_set_att( 'class', themeblvd_grid_class( intval($columns) ) );
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
		$class = themeblvd_set_att( 'class', themeblvd_grid_class( intval($columns) ) );
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
		'slogan'				=> '',						// Text for slogan
		'text_size'				=> 'large',					// Size of text - small, normal, medium, large
		'button'				=> 1,						// Show button - true, false
		'button_color' 			=> 'default',				// Color of button - Use themeblvd_colors() to generate list
		'button_custom'			=> array(
			'bg' 				=> '#ffffff',
			'bg_hover'			=> '#ebebeb',
			'border' 			=> '#cccccc',
			'text'				=> '#333333',
			'text_hover'		=> '#333333',
			'include_bg'		=> 1,
			'include_border'	=> 1
		),
		'button_text'			=> 'Get Started Today!',	// Text for button
		'button_size'			=> 'large',					// Size of button - mini, small, default, large
		'button_url'			=> '',						// URL button goes to
		'button_target'			=> '_self',					// Button target - _self, _blank
		'button_icon_before'	=> '',						// FontAwesome Icon before button text
		'button_icon_after'		=> ''						// FontAwesome Icon afters button text
	);
	$args = wp_parse_args( $args, $defaults );

	// Wrapping class
	$class  = $args['button'] ? 'has_button' : 'text_only';
	$text_class = 'text_'.$args['text_size'];

	// Output
	$output = '<div class="tb-slogan '.$class.'">';

	// Button
	if ( $args['button'] ) {

		// Custom button styling
		$addon = '';

		if ( $args['button_color'] == 'custom' ) {

			if ( $args['button_custom']['include_bg'] ) {
				$bg = $args['button_custom']['bg'];
			} else {
				$bg = 'transparent';
			}

			if ( $args['button_custom']['include_border'] ) {
				$border = $args['button_custom']['border'];
			} else {
				$border = 'transparent';
			}

			$addon = sprintf( 'style="background-color: %1$s; border-color: %2$s; color: %3$s;" data-bg="%1$s" data-bg-hover="%4$s" data-text="%3$s" data-text-hover="%5$s"', $bg, $border, $args['button_custom']['text'], $args['button_custom']['bg_hover'], $args['button_custom']['text_hover'] );

		}

		$output .= themeblvd_button( stripslashes($args['button_text']), $args['button_url'], $args['button_color'], $args['button_target'], $args['button_size'], null, null, $args['button_icon_before'], $args['button_icon_after'], $addon );
	}
	$output .= '<span class="slogan-text '.$text_class.'">'.stripslashes( themeblvd_get_content( $args['slogan'] ) ).'</span>';
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
	$nav_type = $options['nav'];

    if ( $nav_type != 'tabs' && $nav_type != 'pills' ) {
    	$nav_type = 'tabs';
    }

    // Style
    $style = $options['style'];

    if ( $style != 'framed' && $style != 'open' ) {
    	$style = 'framed';
    }

	// Container classes
	$classes = 'tabbable';

	if ( ! empty( $options['height'] ) ) {
		$classes .= ' fixed-height';
	}

	$classes .= ' tb-tabs-'.$style;

	if ( $nav_type == 'pills' ) {
		$classes .= ' tb-tabs-pills';
	}

	// Allow deep linking directly to individual tabs?
	$deep = apply_filters( 'themeblvd_tabs_deep_linking', false );

	// Navigation
	$i = 1;
	$navigation .= '<ul class="nav nav-'.$nav_type.'">';
	if ( $options['tabs'] && is_array($options['tabs']) ) {
		foreach ( $options['tabs'] as $tab_id => $tab ) {

			$class = '';

			if ( $i == 1 ) {
				$class = 'active';
			}

			$name = $tab['title'];

			if ( $deep ) {
				$tab_id = str_replace( ' ', '_', $name );
				$tab_id = preg_replace('/\W/', '', strtolower($tab_id) );
			} else {
				$tab_id = $id.'-'.$tab_id;
			}

			$navigation .= '<li class="'.$class.'"><a href="#'.$tab_id.'" data-toggle="'.str_replace('s', '', $nav_type).'" title="'.stripslashes($name).'">'.stripslashes($name).'</a></li>';

			$i++;
		}
	}
	$navigation .= '</ul>';

	// Tab content
	$i = 1;
	$content_class = 'tab-content';

	if ( $style == 'framed' ) {
		$content_class .= ' '.apply_filters( 'themeblvd_toggle_body_text', 'dark' );
	}

	$content = '<div class="'.$content_class.'">';

	if ( $options['tabs'] && is_array($options['tabs']) ) {
		foreach ( $options['tabs'] as $tab_id => $tab ) {

			$class = '';
			if ( $i == 1 ) {
				$class = ' active';
			}

			if ( $deep ) {
				$tab_id = str_replace( ' ', '_', $name );
				$tab_id = preg_replace('/\W/', '', strtolower($tab_id) );
			} else {
				$tab_id = $id.'-'.$tab_id;
			}

			$content .= '<div id="'.$tab_id.'" class="tab-pane fade'.$class.' in clearfix">';

			ob_start();
			themeblvd_content_block( $tab_id, $tab['content']['type'], $tab['content'] );
			$content .= ob_get_clean();

			$content .= '</div><!-- #'.$tab_id.' (end) -->';
			$i++;
		}
	}
	$content .= '</div><!-- .tab-content (end) -->';

	// Construct final output
	$output = '<div class="'.$classes.'">';
	$output .= $navigation;
	$output .= $content;
	$output .= '</div><!-- .tabbable (end) -->';

	return apply_filters('themeblvd_tabs', $output);
}
endif;

if ( !function_exists( 'themeblvd_toggles' ) ) :
/**
 * Display set of toggles.
 *
 * @since 2.5.0
 *
 * @param array $id unique ID for toggle set
 * @param array $options all options for tabs
 * @return string $output HTML output for tabs
 */
function themeblvd_toggles( $id, $options ) {

	$accordion = false;

	if ( isset($options['accordion']) ) {
		if( $options['accordion'] == 'true' || $options['accordion'] == '1' || $options['accordion'] === 1 ) {
			$accordion = true;
		}
	}

	$counter = 1;
	$total = count($options['toggles']);
	$output = '';

	if ( $options['toggles'] && is_array($options['toggles']) ) {
		foreach ( $options['toggles'] as $toggle ) {

			if ( ! $accordion && $counter == $total ) {
				$toggle['last'] = true;
			}

			$output .= themeblvd_get_toggle( $toggle );

			$counter++;
		}
	}

	if ( $accordion ) {
		$output = sprintf( '<div id="%s" class="tb-accordion panel-group">%s</div>', $id, $output );
	}

	return apply_filters('themeblvd_toggles', $output);
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