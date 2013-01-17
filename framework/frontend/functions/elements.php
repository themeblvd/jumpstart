<?php
/**
 * Display set of columns.
 *
 * @since 2.0.0
 *
 * @param array $num Number of columns
 * @param string $widths Width for each column
 * @param array $columns Inidivual columns, number of array items must match $setup's number
 */

if( ! function_exists( 'themeblvd_columns' ) ) {
	function themeblvd_columns( $num, $widths, $columns ) {
		
		// Kill it if number of columns doesn't match the 
		// number of widths exploded from the string.
		$widths = explode( '-', $widths );
		if( $num != count( $widths ) )
			return;
		
		// Kill it if number of columns doesn't match the 
		// number of columns feed into the function.
		if( $num != count( $columns ) )
			return;

		// Last column's key
		$last = $num - 1;
		
		foreach( $columns as $key => $column ) {
			
			// Set CSS classes for column
			$classes = 'column '.$widths[$key];
			if( $last == $key )
				$classes .= ' last';
			
			// Start display
			echo '<div class="'.$classes.'">';
			
			// Column Content
			switch( $column['type'] ) {
				case 'widget' :
					if( ! empty( $column['sidebar'] ) ) {
						echo '<div class="widget-area">';
						dynamic_sidebar( $column['sidebar'] );
						echo '</div><!-- .widget-area (end) -->';
					}
					break;
				case 'current' :
					$current_page_id = themeblvd_config( 'id' );
					$current_page = get_page( $current_page_id );
					echo apply_filters( 'the_content', $current_page->post_content );
					break;
				case 'page' :
					if( ! empty( $column['page'] ) ) {
						// Get WP internal ID for the page
						$page_id = themeblvd_post_id_by_name( $column['page'], 'page' );
						
						// Use WP_Query to retrieve external page. We do it 
						// this way to allow certain primary query-dependent 
						// items such as galleries to work properly.
						$the_query = new WP_Query( 'page_id='.$page_id );
						
						// Standard WP loop, even though there should only be
						// a single post (i.e. our external page).
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							echo apply_filters( 'themeblvd_the_content', get_the_content() );
						}
						
						// Reset Post Data
						wp_reset_postdata();
					}
					break;
				case 'raw' :
					if( isset( $column['raw'] ) ) {
						// Only negate the "simulated" the_content filter if the option exists 
						// AND it's been unchecked. This is for legacy purposes, as this 
						// feature was added in v2.1.0
						if( isset( $column['raw_format'] ) && ! $column['raw_format'] )
							echo do_shortcode( stripslashes( $column['raw'] ) ); // Shortcodes only
						else
							echo apply_filters( 'themeblvd_the_content', stripslashes( $column['raw'] ) );
					}
					break;			
			}
			
			// End display
			echo '</div><!-- .column (end) -->';
		}
	}
}

/**
 * Display content.
 *
 * @since 2.0.0
 *
 * @param array $args Options for content
 * @return string $output HTML output for content
 */

if( ! function_exists( 'themeblvd_content' ) ) {
	function themeblvd_content( $args = array() ) {
	
		// Setup and extract $args
		$defaults = array(
			'source' 		=> 'current',	// Source of content
			'page_id' 		=> '',			// If source is external, this is the slug of that page
			'raw_content' 	=> '',			// If source is raw, this is the content
			'raw_format' 	=> 1,			// If source is raw, true will apply WP auto formatting
			'widget_area' 	=> ''			// If source is widget_area, this is the ID of the widget area
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_OVERWRITE );
		
		// Start output
		$output = '';
		switch( $source ) {
		
			// Content from current page
			case 'current' :
				$current_page_id = themeblvd_config( 'id' );
				$current_page = get_page( $current_page_id );
				$output = apply_filters( 'the_content', $current_page->post_content );
				break;
			
			// Content from external page
			case 'external' :
				// Get WP internal ID for the page
				$page_num_id = themeblvd_post_id_by_name( $page_id, 'page' );
				
				// Use WP_Query to retrieve external page. We do it 
				// this way to allow certain primary query-dependent 
				// items such as galleries to work properly.
				$the_query = new WP_Query( 'page_id='.$page_num_id );
				
				// Standard WP loop, even though there should only be
				// a single post (i.e. our external page).
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$output = apply_filters( 'themeblvd_the_content', get_the_content() );
				}
				
				// Reset Post Data
				wp_reset_postdata();
				break;
				
			// Raw content input
			case 'raw' :
				if( $raw_format )
					$output = apply_filters( 'themeblvd_the_content', stripslashes( $raw_content ) ); // WP auto formatting w/shortcodes
				else
					$output =  do_shortcode( stripslashes( $raw_content ) ); // Shortcodes only
				break;
			
			// Widget area
			case 'widget_area' :
				if( $widget_area ) {
					$output = '<div class="widget-area">';
					ob_start();
					dynamic_sidebar( $widget_area );
					$output .= ob_get_clean();
					$output .= '</div><!-- .widget-area (end) -->';
				}
				break;
			
		}
		return $output;
	}
}

/**
 * Display divider.
 *
 * @since 2.0.0
 *
 * @param string $type Style of divider
 * @return string $output HTML output for divider
 */

if( ! function_exists( 'themeblvd_divider' ) ) {
	function themeblvd_divider( $type = 'solid' ) {
		$output = '<div class="divider divider-'.$type.'"></div>';
		return $output;
	}
}

/**
 * Display headline.
 *
 * @since 2.0.0
 *
 * @param array $args Options for headline
 * @return string $output HTML output for headline
 */

if( ! function_exists( 'themeblvd_headline' ) ) {
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
		if( $tagline ) {
			$output .= '<p class="text-'.$align.'">';
			$output .= stripslashes( $tagline );
			$output .= '</p>';
		}
		
		return $output;
	}
}

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

if( ! function_exists( 'themeblvd_post_slider' ) ) {
	function themeblvd_post_slider( $id, $args = array(), $type = 'grid', $current_location = 'primary' ) {

		global $post;

		// Setup and extract $args
		$defaults = array(
			'fx' 				=> 'slide',				// Effect for transitions
			'timeout' 			=> '0',					// Time between auto trasitions in seconds
			'nav_standard' 		=> '1', 				// Show standard nav - true, false
			'nav_arrows' 		=> '1', 				// Show nav arrows - true, false
			'pause_play'		=> '1', 				// Show pause/play buttons - true, false 
			'categories'		=> array( 'all' => 1 ),	// post categories to include
			'columns'			=> '3', 				// Number of columns (grid only)
			'rows'				=> '3', 				// Number of rows (grid only)
			'posts_per_slide'	=> '', 					// Posts per slide (list only)
			'thumbs'			=> 'default', 			// Size of featured iamges (list only) - default, small, full, hide
			'content'			=> 'default', 			// Full content or excerpts (list only) - default, content, excerpt
			'numberposts'		=> '-1',				// Total number of posts to query for slider
			'orderby'			=> 'date', 				// Orderby param for posts query
			'order'				=> 'DESC', 				// Order param for posts query
			'offset'			=> '0', 				// Offset param for posts query
			'crop'				=> '', 					// Custom image crop size (grid only)	
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_OVERWRITE );
		
		// Location and query string
		$location = themeblvd_set_att( 'location', $current_location );
		$query_string = themeblvd_get_posts_args( $args, $type, true );
		$query_string = apply_filters( 'themeblvd_post_slider_args', $query_string, $args, $type, $current_location );

		// Configure additional CSS classes
		$classes = '';
		$nav_standard == '1' ? $classes .= ' show-nav_standard' : $classes .= ' hide-nav_standard';
		$nav_arrows == '1' ? $classes .= ' show-nav_arrows' : $classes .= ' hide-nav_arrows';
		$pause_play == '1' ? $classes .= ' show-pause_play' : $classes .= ' hide-pause_play';
		
		// Config before query string
		if( $type == 'grid' ) {
			$columns = themeblvd_set_att( 'columns', $columns );
			$posts_per_slide = $columns*$rows;
			$size = themeblvd_set_att( 'size', themeblvd_grid_class( $columns ) );
			$crop = ! empty( $crop ) ? $crop : $size;
			$crop = themeblvd_set_att( 'crop', $crop );
		} else {
			$content = $content == 'default' ? themeblvd_get_option( 'blog_content', null, 'content' ) : $content;
			$content = themeblvd_set_att( 'content', $content );
			$size = $thumbs == 'default' ? themeblvd_get_option( 'blog_thumbs', null, 'small' ) : $thumbs;
			$size = themeblvd_set_att( 'size', $size );
		}
		
		// Get posts
		$posts = get_posts( $query_string );
		
		// Adjust offset if neccesary
		if( $numberposts == -1 && $offset > 0 ) {
			$i = 0;
			while ( $i < $offset ) {
				unset( $posts[$i] );
				$i++;
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
										if( $type == 'grid' ) {
											
											/*-------------------------------------------*/
											/* Post Grid Loop
											/*-------------------------------------------*/
											
											$counter = themeblvd_set_att( 'counter', 1 );
											$per_slide_counter = 1;
											$number_of_posts = count( $posts );

											foreach ( $posts as $post ) {
												setup_postdata( $post );
												// Start first slide and first row.
												if( $counter == 1 ) {
													echo '<li class="slide">';
													echo '<div class="post_'.$type.'">';
													themeblvd_open_row();
												}
												// Include the post
												get_template_part( 'content', themeblvd_get_part( 'grid_slider' ) );
												// Add in the complicated stuff to break up slides, rows, and columns
												if( $per_slide_counter == $posts_per_slide ) {
													// End of a slide and thus end the row
													themeblvd_close_row();
													echo '</div><!-- .post_'.$type.' (end) -->';
													echo '</li>';
													// And if posts aren't done yet, open a 
													// new slide and new row.
													if( $number_of_posts != $counter ) {
														echo '<li class="slide">';
														echo '<div class="post_'.$type.'">';
														themeblvd_open_row();
													}
													// Set the posts per slide counter back to 
													// 0 for the next slide.
													$per_slide_counter = 1;
												} elseif( $per_slide_counter % $columns == 0  ) {
													// End row only
													themeblvd_close_row();
													// Open a new row if there are still post left 
													// in this slide.
													if( $posts_per_slide != $per_slide_counter ) {
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
												if( $counter == 1 ) {
													echo '<li class="slide">';
													echo '<div class="post_'.$type.'">';
												}
												get_template_part( 'content', themeblvd_get_part( 'list_slider' ) );
												// Add in the complicated stuff
												if( $per_slide_counter == $posts_per_slide ) {
													// End of a slide and thus end the row
													echo '</div><!-- .post_'.$type.' (end) -->';
													echo '</li>';
													// And if posts aren't done yet, open a 
													// new slide
													if( $number_of_posts != $counter ) {
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
												$counter = themeblvd_set_att( 'counter', $counter+1 );
											}
											wp_reset_postdata();
										}
									} else {
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
}

/**
 * Display post list or post grid
 *
 * @since 2.0.0
 *
 * @param array $args All options for posts
 * @param string $type Type of posts, grid or list
 * @param string $current_location Current location of element, featured or primary
 */

if( ! function_exists( 'themeblvd_posts' ) ) {
	function themeblvd_posts( $args = array(), $type = 'list', $current_location = 'primary' ) {
		
		global $post;
		global $more;
		$more = 0;
		
		// Current location if relevant
		$location = themeblvd_set_att( 'location', $current_location );
		
		// Setup and extract $args
		$defaults = array(
			'categories'	=> array( 'all' => 1 ),	// Post categories to include
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
		
		// Setup query args
		$custom_query = false;
		if( ! empty( $query ) ) {
			// Custom query string
			$custom_query = true;
			$query_string = html_entity_decode( $query );
		} else {
			// Generated query args
			$query_string = themeblvd_get_posts_args( $args, $type );
		}

		// Config before query string
		if( $type == 'grid' ) {
			$columns = themeblvd_set_att( 'columns', $columns );
			$size = themeblvd_set_att( 'size', themeblvd_grid_class( $columns ) );		
			$crop = ! empty( $crop ) ? $crop : $size;
			$crop = themeblvd_set_att( 'crop', $crop );
		} else {
			$content = $content == 'default' ? themeblvd_get_option( 'blog_content', null, 'content' ) : $content;
			$content = themeblvd_set_att( 'content', $content );
			$size = $thumbs == 'default' ? themeblvd_get_option( 'blog_thumbs', null, 'small' ) : $thumbs;
			$size = themeblvd_set_att( 'size', $size );
		}
		
		// Apply filters
		$query_string = apply_filters( 'themeblvd_posts_args', $query_string, $args, $type, $current_location );
		
		// Get posts
		$posts = get_posts( $query_string );
		
		// Adjust offset if neccesary
		if( ! $custom_query ) {
			if( $numberposts == -1 && $offset > 0 ) {
				$i = 0;
				while ( $i < $offset ) {
					unset( $posts[$i] );
					$i++;
				}
			}
		}

		// Start the loop
		echo '<div class="post_'.$type.'">';
		if ( ! empty( $posts ) ) {
			if( $type == 'grid' ) {
				// Loop for post grid (i.e. Portfolio)
				$counter = themeblvd_set_att( 'counter', 1 );
				$number_of_posts = count( $posts );
				foreach ( $posts as $post ) {
					setup_postdata( $post );
					if( $counter == 1 ) themeblvd_open_row();
					get_template_part( 'content', themeblvd_get_part( 'grid' ) );
					if( $counter % $columns == 0 ) themeblvd_close_row();
					if( $counter % $columns == 0 && $number_of_posts != $counter ) themeblvd_open_row();
					$counter = themeblvd_set_att( 'counter', $counter+1 );
				}
				wp_reset_postdata();
				if( $number_of_posts % $columns != 0 ) themeblvd_close_row();
			} else {
				// Loop for post list (i.e. Blog)
				foreach ( $posts as $post ) { 
					setup_postdata( $post );
					get_template_part( 'content', themeblvd_get_part( 'list' ) );
				}
				wp_reset_postdata();
			}
		} else {
			echo '<p>'.themeblvd_get_local( 'archive_no_posts' ).'</p>';
		}
		echo '</div><!-- .post_'.$type.' (end) -->';
		// Show link
		if( $link )
			echo '<a href="'.$link_url.'" target="'.$link_target.'" title="'.$link_text.'" class="lead-link">'.$link_text.'</a>';
			
	}
}

/**
 * Display paginated post list or post grid
 *
 * @since 2.0.0
 *
 * @param array $options all options for posts
 * @param string $type Type of posts, grid or list
 * @param string $current_location Current location of element, featured or primary
 */

if( ! function_exists( 'themeblvd_posts_paginated' ) ) {
	function themeblvd_posts_paginated( $args = array(), $type = 'list', $current_location = 'primary' ) {
		
		global $wp_query;
		global $_themeblvd_paged;
		global $more;
    	$more = 0;
		$query_string = '';
		
		// Current location if relevant
		$location = themeblvd_set_att( 'location', $current_location );
		
		// Setup and extract $args
		$defaults = array(
			'categories' 		=> array( 'all' => 1 ),	// Post categories to include
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
		if( $type == 'grid' ) {
			$columns = themeblvd_set_att( 'columns', $columns );
			$posts_per_page = $rows ? $columns*$rows : '-1';
			$size = themeblvd_set_att( 'size', themeblvd_grid_class( $columns ) );		
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
		/* Query String (very similar to themeblvd_get_posts_args() 
		/* in helpers.php - May combine functions later )
		/*------------------------------------------------------*/
		
		if( $query ) {
			
			// Custom query string
			$query_string = html_entity_decode( $query );
			$query_string .= '&';
			if( $type == 'grid' )
				$query_string .= 'posts_per_page='.$posts_per_page.'&'; // User can't use "posts_per_page" in custom query for grids
			
		} else {
			
			// Generate query string
			if( isset( $categories['all'] ) && ! $categories['all'] ) {
				unset( $categories['all'] );
				$category_name = '';
				foreach( $categories as $category => $include ) {
					if( $include ) {
						$category_name .= $category.',';
					}
				}
				if( $category_name ) {
					$category_name = themeblvd_remove_trailing_char( $category_name, $char = ',' );
					$query_string .= 'category_name='.$category_name.'&';
				}
			}
			if( $type == 'grid' ) {
				$query_string .= 'posts_per_page='.$posts_per_page.'&';
			} else {
				if( $posts_per_page ) 
					$query_string .= 'posts_per_page='.$posts_per_page.'&';
			}
			if( $orderby )
				$query_string .= 'orderby='.$orderby.'&';
			if( $order )
				$query_string .= 'order='.$order.'&';
			
		}
		
		// Pagination
		if ( get_query_var('paged') )
	        $paged = get_query_var('paged');
	    else if ( get_query_var('page') )
	        $paged = get_query_var('page'); // This provides compatiblity with static frontpage
		else
	        $paged = 1;
	        
		$_themeblvd_paged = $paged; // Set global variable for pagination compatiblity on static frontpage
		$query_string .= 'paged='.$paged;
		
		// Apply filters
		$query_string = apply_filters( 'themeblvd_posts_args', $query_string, $args, $type, $current_location );

		/*------------------------------------------------------*/
		/* The Loop
		/*------------------------------------------------------*/

		// Query posts
		query_posts( $query_string );
		
		// Start the loop
		echo '<div class="post_'.$type.'">';
		if ( have_posts() ) {
			if( $type == 'grid' ) {
				// Loop for post grid (i.e. Portfolio)
				$counter = themeblvd_set_att( 'counter', 1 );
				while ( have_posts() ) { 
					the_post();
					if( $counter == 1 ) themeblvd_open_row();
					get_template_part( 'content', themeblvd_get_part( 'grid_paginated' ) );
					if( $counter % $columns == 0 ) themeblvd_close_row();
					if( $counter % $columns == 0 && $posts_per_page != $counter ) themeblvd_open_row();
					$counter = themeblvd_set_att( 'counter', $counter+1 );
				}
				if( ($counter-1) != $posts_per_page ) themeblvd_close_row();
			} else {
				// Loop for post list (i.e. Blog)
				while ( have_posts() ) { 
					the_post();
					get_template_part( 'content', themeblvd_get_part( 'list_paginated' ) );
				}
			}
		} else {
			echo '<p>'.themeblvd_get_local( 'archive_no_posts' ).'</p>';
		}
		themeblvd_pagination();
		echo '</div><!-- .post_'.$type.' (end) -->';	
		
		// Reset Query
		wp_reset_query();	
	}
}

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

if( ! function_exists( 'themeblvd_slider_auto' ) ) {
	function themeblvd_slider_auto( $id, $args = array() ) {

		global $post;

		// Setup $args
		$defaults = array(
			'fx' 				=> 'slide', 	// Effect for transitions
			'timeout' 			=> '3',			// Time between auto trasitions in seconds
			'nav_standard' 		=> '1',			// Show standard nav - true, false
			'nav_arrows'		=> '1',			// Show nav arrows - true, false
			'pause_play'		=> '1',			// Show pause/play buttons - true, false
			'pause_on_hover' 	=> 'disable',	// Pause on hover - pause_on, pause_on_off, disable
			'image' 			=> 'full',		// How to display featured images - full, align-right, align-left
			'image_link' 		=> 'permalink',	// Where image link goes - permalink, lightbox, none
			'button' 			=> '',			// Text for button to lead to permalink - leave empty to hide
			'source' 			=> 'tag',		// Source for the posts query
			'tag' 				=> '',			// Tag to pull posts from
			'category' 			=> '',			// Category slug to pull posts from
			'numberposts' 		=> '5',			// Number of posts/slides
			'orderby' 			=> 'date',		// Orderby param for posts query
			'order'				=> 'DESC',		// Order param for posts query
			'query' 			=> '',			// Custom query string
			'mobile_fallback' 	=> 'full_list',	// How to display on mobile - full_list, first_slide, display
			
		);
		$args = wp_parse_args( $args, $defaults );		

		// Format settings array so it matches the array 
		// pulled if we were getting to this from a 
		// custom-built slider.
		$settings = array(
			'fx' 				=> $args['fx'],
		    'timeout' 			=> $args['timeout'],
		    'nav_standard' 		=> $args['nav_standard'],
		    'nav_arrows' 		=> $args['nav_arrows'],
		    'pause_play' 		=> $args['pause_play'],
		    'pause_on_hover' 	=> $args['pause_on_hover'],
		    'mobile_fallback' 	=> $args['mobile_fallback']
		);
		$settings = apply_filters( 'themeblvd_slider_auto_settings', $settings, $args );
		
		// Get posts for slider
		$query_string = '';
		switch( $args['source'] ){
			case 'tag' :
				$query_string = 'tag='.$args['tag'].'&orderby='.$args['orderby'].'&order='.$args['order'].'&numberposts='.$args['numberposts'];
				break;
			case 'category' :
				$query_string = 'category_name='.$args['category'].'&orderby='.$args['orderby'].'&order='.$args['order'].'&numberposts='.$args['numberposts'];
				break;
			case 'query' :
				$query_string = $args['query'];
				break;
		}
		$posts = get_posts( apply_filters( 'themeblvd_slider_auto_args', $query_string, $args ) );
		
		// Now loop through posts and setup an array of 
		// slides that matches what would have been pulled 
		// from a custom-built slider.
		$slides = array();
		$counter = 1;
		$includes = array( 'image_link', 'headline', 'description' );
		if( $args['button'] ) $includes[] = 'button';
		$image_link_target = $args['image_link'] == 'permalink' ? '_self' : $args['image_link'];
		if( $posts ) {
			foreach( $posts as $post ) {
				
				// Setup post data for loop
				setup_postdata( $post );
				
				// Featured image ID
				$featured_image_id = get_post_thumbnail_id( $post->ID );
				
				// Image Link
				$image_link_url = '';
				switch( $args['image_link'] ) {
					case 'permalink' :
						$image_link_url = get_permalink();
						break;
					case 'lightbox' :
						$image_link_url = wp_get_attachment_url( $featured_image_id );
						break;
				}
				
				// Elements
				$include = array( 'image_link', 'headline', 'description' );
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
				
				// Add slide
				$slides['slide_'.$counter] = array(
					'slide_type' 		=> 'image',
					'position_image' 	=> $args['image'],
					'position' 			=> $args['image'],
					'elements' 			=> $elements,
					'image'				=> array(
						'id' => $featured_image_id
					)
				);
				$counter++;
			}
			wp_reset_postdata();
		}
		$slides = apply_filters( 'themeblvd_slider_auto_slides', $slides, $args, $posts );
		
		// Display post slider
		do_action( 'themeblvd_slider_auto', $id, $settings, $slides );
	}
}

/**
 * Display slider.
 *
 * @since 2.0.0
 *
 * @param string $slider Slug of custom-built slider to use
 */

if( ! function_exists( 'themeblvd_slider' ) ) {
	function themeblvd_slider( $slider ) {
		
		// Kill it if there's no slider
		if( ! $slider ) {
			echo '<div class="tb-warning">'.themeblvd_get_local( 'no_slider_selected.' ).'</div>';
			return;
		}
		
		// Get Slider ID
		$slider_id = themeblvd_post_id_by_name( $slider, 'tb_slider' );
		if( ! $slider_id ) {
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
}

/**
 * Display slogan.
 *
 * @since 2.0.0
 *
 * @param array $args All options for slogan
 * @return string $output HTML output for slogan
 */

if( ! function_exists( 'themeblvd_slogan' ) ) {
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
		if( $button ) {
			$output .= themeblvd_button( stripslashes($button_text), $button_url, $button_color, $button_target, $button_size );	
		}
		$output .= '<span class="slogan-text '.$text_class.'">'.stripslashes( do_shortcode( $slogan ) ).'</span>';
		$output .= '</div><!-- .slogan (end) -->';
		
		return $output;
	}	
}

/**
 * Display set of tabs.
 *
 * @since 2.0.0
 *
 * @param array $id unique ID for tab set
 * @param array $options all options for tabs
 * @return string $output HTML output for tabs
 */

if( ! function_exists( 'themeblvd_tabs' ) ) {
	function themeblvd_tabs( $id, $options ) {
		$nav = array( 'tabs', 'above' ); // Backup for someone updating who doesn't have this saved yet.
		$navigation = '';
		$content = '';
		$output = '';		
		
		// Fixed Height
		$height = '';
		if( $options['height'] )
			$height = ' style="height:'.$options['height'].'px"';
		
		// Tabs or pills?
		if( ! empty( $options['setup']['nav'] ) )
			$nav = explode( '_', $options['setup']['nav'] );
		$nav_type = $nav[0];
		$nav_location = $nav[1];
		
		// Container classes
		$classes = 'tabbable';
		if( $nav_type == 'tabs' )
			$classes .= ' tabs-'.$nav_location;
		$classes .= ' tb-tabs-'.$options['setup']['style'];
		
		// Navigation
		$i = 0;
		$class = null;
		$navigation .= '<ul class="nav nav-'.$nav_type.'">';
		foreach( $options['setup']['names'] as $key => $name ) {
			if( $i == 0 ) $class = 'active';
			$navigation .= '<li class="'.$class.'"><a href="#'.$id.'-'.$key.'" data-toggle="'.str_replace('s', '', $nav_type).'" title="'.stripslashes($name).'">'.stripslashes($name).'</a></li>';
			$class = null;
			$i++;
		}
		$navigation .= '</ul>';
		
		// Tab content
		$i = 0;
		$content = '<div class="tab-content">';
		foreach( $options['setup']['names'] as $key => $name ) {
			$i == '0' ? $class = ' active' : $class = ''; 
			$content .= '<div id="'.$id.'-'.$key.'" class="tab-pane fade'.$class.' in clearfix"'.$height.'>';
			switch( $options[$key]['type'] ) {
				case 'page' :
					// Get WP internal ID for the page
					$page_id = themeblvd_post_id_by_name( $options[$key]['page'], 'page' );
					
					// Use WP_Query to retrieve external page. We do it 
					// this way to allow certain primary query-dependent 
					// items such as galleries to work properly.
					$the_query = new WP_Query( 'page_id='.$page_id );
					
					// Standard WP loop, even though there should only be
					// a single post (i.e. our external page).
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$content .= apply_filters( 'themeblvd_the_content', get_the_content() );	
					}
					
					// Reset Post Data
					wp_reset_postdata();
					break;
				case 'raw' :
					// Only negate simulated the_content filter if the option exists AND it's 
					// been unchecked. This is for legacy purposes, as this feature 
					// was added in v2.1.0
					if( isset( $options[$key]['raw_format'] ) && ! $options[$key]['raw_format'] )
						$content .= do_shortcode( stripslashes( $options[$key]['raw'] ) ); // Shortcodes only
					else
						$content .= apply_filters( 'themeblvd_the_content', stripslashes( $options[$key]['raw'] ) );
					break;
				case 'widget' :
					if( ! empty( $options[$key]['sidebar'] ) ) {
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
		if( $nav_location != 'below' ) $output .= $navigation;
		$output .= $content;
		if( $nav_location == 'below' ) $output .= $navigation;
		$output .= '</div><!-- .tabbable (end) -->';
		
		return $output;
	}
}
/**
 * Display recent tweet.
 *
 * @since 2.0.0
 *
 * @param array $args all options for tweet
 * @return string $output HTML output for tweet
 */

if( ! function_exists( 'themeblvd_tweet' ) ) {
	function themeblvd_tweet( $args = array() ) {
		
		// Setup and extract $args
		$defaults = array(
			'account'	=> 'themeblvd',	// Twitter account username
			'icon'		=> 'twitter',	// Font awesome icon ID
			'meta'		=> 'show',		// Show meta info below tweet - show, hide
			'replies'	=> 'no',		// Exclude @replies - yes, no
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_OVERWRITE );
		
		// In Framework verstion 2.1.0, this function was changed quite a bit. 
		// It's now setup in a way that is a little cumbersome for only 
		// displaying one tweet, however this will make it easier to add more 
		// options to this Tweet element in the future.
		
		$tweets = array();
		$iterations = 0;
		$count = 1; // Being manually set currently
		$output = '';		
		$exclude_replies = $replies; // Logic is confusing here, but we've got to stick to it at this point.
		
		// Convert older icon option for those updating. 
		switch( $icon ) {
			case 'message' :
				$icon = 'comment';
				break;
			case 'alert' :
				$icon = 'warning';
				break;
		}
		
		// Wrapping CSS class
		$wrap_class = 'tb-tweet-wrapper';
		if( $icon )
			$wrap_class .= ' has-icon';
		
		// Use WordPress's SimplePie integration to retrieve Tweets
		$rss = fetch_feed( themeblvd_get_twitter_rss_url( $account ) );

		// Proceed if we could retrieve the RSS feed
		if ( ! is_wp_error( $rss ) ) {
		
			// Setup items from fetched feed
			$maxitems = $rss->get_item_quantity();
			$rss_items = $rss->get_items(0, $maxitems);
			
			// Build Tweets array for display - (should only be 1 tweet currently)
			if( $rss_items ) {
				foreach ( $rss_items as $item ) {
					// Only continue if we haven't reached the max number of tweets
					if( $iterations == $count ) break;
					// Set text of tweet
					$text = (string) $item->get_title();
					$text = str_ireplace( $account.': ', '', $text );
					// Take "Exclude @ replies" option into account before adding 
					// tweet and increasing current number of tweets.
					if( $exclude_replies == 'no' || ( $exclude_replies == 'yes' && $text[0] != "@") ) {
					    $iterations++;
					    $tweets[] = array(
					    	'link' => $item->get_permalink(),
					    	'text' => apply_filters( 'themeblvd_tweet_filter', $text, $account ),
					    	'date' => $item->get_date( get_option('date_format') )
					    );
					}
				}
			}
			
			// Start output of tweets - (should only be 1 tweet currently)
			if( $tweets ) {	
				foreach( $tweets as $tweet) {	
					$output .= '<div class="'.$wrap_class.'">';
					if( $icon ) 
						$output .= '<div class="tweet-icon"><i class="icon-'.$icon.'"></i></div>';
					$output .= '<div class="tweet-content">'.$tweet['text'].'</div>';
					if( $meta == 'show' ) {
						$output .= '<div class="tweet-meta"><a href="http://twitter.com/'.$account.'" target="_blank">@'.$account.'</a> ';
						$output .= themeblvd_get_local('via').' Twitter, <a href="'.$tweet['link'].'" target="_blank">'.$tweet['date'].'</a></div>';
					}
					$output .= '</div><!-- .tweet-wrapper (end) -->';
				}
			}
			
			// Finish up output
			if( ! $output )
				$output = __( 'No public Tweets found', 'themeblvd' );
			
		} else {
			// Received error with fetch_feed()
			$output = __( 'Could not fetch Twitter RSS feed.', 'themeblvd' );
		}
		
		return $output;
	}	
}