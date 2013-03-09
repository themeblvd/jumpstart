<?php
/** 
 * Contact button bar 
 *
 * @since 2.0.0
 *
 * @param array $buttons icons to use - array( 'twitter' => 'http://twitter.com/whatever', 'facebook' => 'http://facebook.com/whatever' )
 * @param string $style Style of buttons - dark, grey, light, color
 */

if( ! function_exists( 'themeblvd_contact_bar' ) ) {
	function themeblvd_contact_bar( $buttons = array(), $style = null ) {
		// Set up buttons
		if( ! $buttons )
			$buttons = themeblvd_get_option( 'social_media' );
		// If buttons haven't been sanitized return nothing
		if( is_array( $buttons ) && isset( $buttons['includes'] ) )
			return null;
		// Set up style
		if( ! $style )
			$style = themeblvd_get_option( 'social_media_style', null, 'grey' );
		// Start output
		$output = null;
		if( is_array( $buttons ) && ! empty ( $buttons ) ) {
			$output = '<div class="themeblvd-contact-bar">';
			$output .= '<ul class="social-media-'.$style.'">';
			foreach( $buttons as $id => $url ) {
				$target = strpos( $url, 'mailto:' ) !== false ? '_self' : '_blank'; // Change target if URL has 'mailto:'
				$output .= '<li><a href="'.$url.'" title="'.ucfirst( $id ).'" class="'.$id.'" target="'.$target.'">'.ucfirst( $id ).'</a></li>';
			}
			$output .= '</ul>';
			$output .= '<div class="clear"></div>';
			$output .= '</div><!-- .themeblvd-contact-bar (end) -->';
		}
		return $output;
	}
}

/** 
 * Button
 *
 * As of framework v2.2, the button markup matches 
 * the Bootstrap standard "btn" structure.
 *
 * @since 2.0.0
 *
 * @param string $text Text to show in button
 * @param string $color Color class of button
 * @param string $url URL where the button points to
 * @param string $target Anchor tag's target, _self, _blank, or lightbox
 * @param string $size Size of button - small, medium, or large
 * @param string $classes CSS classes to attach onto button
 * @param string $title Title for anchor tag
 * @param string $icon_before Optional fontawesome icon before text
 * @param string $icon_after Optional fontawesome icon after text
 * @param string $addon Anything to add onto the anchor tag
 * @return $output string HTML to output for button
 */

if( ! function_exists( 'themeblvd_button' ) ) {
	function themeblvd_button( $text, $url, $color = 'default', $target = '_self', $size = null, $classes = null, $title = null, $icon_before = null, $icon_after = null, $addon = null ) {
		
		// Classes for button
		$final_classes = 'btn';
		if( ! $color )
			$color = 'default';
		if( in_array( $color, apply_filters( 'themeblvd_bootstrap_btn_colors', array( 'default', 'primary', 'info', 'success', 'warning', 'danger', 'inverse' ) ) ) )
			$final_classes .= ' btn-'.$color;
		else
			$final_classes .= ' '.$color;
		if( in_array( $size, apply_filters( 'themeblvd_bootstrap_btn_sizes', array( 'mini', 'small', 'large' ) ) ) )
			$final_classes .= ' btn-'.$size;
		if( $classes )
			$final_classes .= ' '.$classes;
		
		// Target
		$final_target = '';
		if( $target ) {
			if( $target == 'lightbox' )
				$final_target = ' rel="themeblvd_lightbox"';
			else
				$final_target = ' target="'.$target.'"';
		}
		
		// Title param
		if( ! $title )
			$title = strip_tags( $text );
		
		// Add icon before text?
		if( $icon_before )
			$text = '<i class="icon-'.$icon_before.'"></i> '.$text;
		
		// Add icon after text?
		if( $icon_after )
			$text .= ' <i class="icon-'.$icon_after.'"></i>';
		
		// Optional addon to anchor
		if( $addon )
			$addon = ' '.$addon;
		
		// Return final button
		return '<a href="'.$url.'" title="'.$title.'" class="'.$final_classes.'"'.$final_target.$addon.'>'.$text.'</a>';
	}
}

/** 
 * Display title for archive pages
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_archive_title' ) ) {
	function themeblvd_archive_title() {
		global $post;
		global $posts;
	    if( $posts ) $post = $posts[0]; // Hack. Set $post so that the_date() works.
	    if ( is_search() ) {
			// Search Results
			echo themeblvd_get_local('crumb_search').' "'.get_search_query().'"';
	    } else if ( is_category() ) {
	    	// If this is a category archive 
	    	// echo themeblvd_get_local( 'category' ).': ';
	    	single_cat_title();
	    } else if( is_tag() ) {
	    	// If this is a tag archive 
	    	echo themeblvd_get_local('crumb_tag').' "'.single_tag_title('', false).'"';
	    } else if ( is_day() ) {
	    	// If this is a daily archive 
	    	echo themeblvd_get_local( 'archive' ).': ';
	    	the_time('F jS, Y');
	    } else if ( is_month()) {
	    	// If this is a monthly archive 
	    	echo themeblvd_get_local( 'archive' ).': ';
	    	the_time('F, Y');
	    } else if ( is_year()) {
	    	// If this is a yearly archive 
	    	echo themeblvd_get_local( 'archive' ).': ';
	    	the_time('Y');
	    } else if ( is_author()) {
	    	// If this is an author archive 
	    	global $author;
			$userdata = get_userdata($author);
			echo themeblvd_get_local('crumb_author').' '.$userdata->display_name;
	    }
	}
}

/**
 * Pagination
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_pagination' ) ) {
	function themeblvd_pagination( $pages = '', $range = 2 ) {  
		global $paged;
		global $_themeblvd_paged;
		$showitems = ($range * 2)+1;  	     
		if( $_themeblvd_paged ) $paged = $_themeblvd_paged; // Static frontpage compatibility
		if( empty( $paged ) ) $paged = 1;
		if( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if( !$pages ) {
				$pages = 1;
			}
		}
		if( 1 != $pages ) {
			echo '<div class="pagination-wrap"><div class="pagination"><div class="btn-group clearfix">';
			if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo '<a class="btn btn-default" href="'.get_pagenum_link(1).'">&laquo;</a>';
			if($paged > 1 && $showitems < $pages) echo '<a class="btn btn-default" href="'.get_pagenum_link($paged - 1).'">&lsaquo;</a>';
			for ( $i = 1; $i <= $pages; $i++ ) {
				if (1 != $pages &&( ! ( $i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
					echo ( $paged == $i ) ? '<a class="btn btn-default active" href="'.get_pagenum_link($i).'">'.$i.'</a>' : '<a class="btn btn-default" href="'.get_pagenum_link($i).'">'.$i.'</a>';
				}
			}
			if ($paged < $pages && $showitems < $pages) echo '<a class="btn btn-default" href="'.get_pagenum_link($paged + 1).'">&rsaquo;</a>';  
			if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo '<a class="btn btn-default" href="'.get_pagenum_link($pages).'">&raquo;</a>';
			echo "</div></div></div>\n";
		}
	}
}

/** 
 * Get breadcrumb trail formatted for being displayed.
 *
 * @since 2.2.1
 *
 * @param string $var Description
 * @return string $var Description
 */

if( ! function_exists( 'themeblvd_get_breadcrumbs_trail' ) ) {
	function themeblvd_get_breadcrumbs_trail(){
		
		// Filterable attributes
		$atts = array(
			'delimiter'		=> ' <span class="divider">/</span> ',
			'home' 			=> themeblvd_get_local('home'),
			'home_link' 	=> home_url(),
			'before' 		=> '<span class="current">',
			'after' 		=> '</span>'
		);
		$atts = apply_filters( 'themeblvd_breadcrumb_atts', $atts );
		
		// Get filtered breadcrumb parts as an array so we 
		// can use it to construct the display.
		$parts = themeblvd_get_breadcrumb_parts( $atts );
		
		// Use breadcrumb parts to construct display of trail
		$trail = '';
		$count = 1;
		$total = count($parts);
		if( $parts ) {
			foreach( $parts as $part ) {
				$crumb = $part['text'];
				if( ! empty( $part['link'] ) )
					$crumb = '<a href="'.$part['link'].'" class="'.$part['type'].'-link" title="'.$crumb.'">'.$crumb.'</a>';
				if( $total == $count )
					$crumb = $atts['before'].$crumb.$atts['after'];
				else
					$crumb = $crumb.$atts['delimiter'];
				$trail .= $crumb;
				$count++;
			}
		}
		return apply_filters( 'themeblvd_breadcrumbs_trail', $trail, $atts, $parts );
	}
}

/**
 * Get Recent Tweets
 *
 * @since 2.0.0
 *
 * @param string $count Number of tweets to display
 * @param string $username Twitter username to pull tweets from
 * @param string $time Display time of tweets, yes or no
 * @param string $exclude_replies Exclude replies, yes or no
 * @return string $output Final list of tweets
 */

if( ! function_exists( 'themeblvd_get_twitter' ) ) {
	function themeblvd_get_twitter( $count, $username, $time = 'yes', $exclude_replies = 'yes' ) {		

		$filtered_message = null;
		$output = null;
		$iterations = 0;
		$tweets = array();
		
		// Use WordPress's SimplePie integration to retrieve Tweets
		$rss = fetch_feed( themeblvd_get_twitter_rss_url( $username ) );
		
		// Proceed if we could retrieve the RSS feed
		if ( ! is_wp_error( $rss ) ) {
		
			// Setup items from fetched feed
			$maxitems = $rss->get_item_quantity();
			$rss_items = $rss->get_items(0, $maxitems);
			
			// Build Tweets array for display
			if( $rss_items ) {
				foreach ( $rss_items as $item ) {
					// Only continue if we haven't reached the max number of tweets
					if( $iterations == $count ) break;
					// Set text of tweet
					$text = (string) $item->get_title();
					$text = str_ireplace( $username.': ', '', $text );
					// Take "Exclude @ replies" option into account before adding 
					// tweet and increasing current number of tweets.
					if( $exclude_replies == 'no' || ( $exclude_replies == 'yes' && $text[0] != "@") ) {
					    $iterations++;
					    $tweets[] = array(
					    	'link' => $item->get_permalink(),
					    	'text' => apply_filters( 'themeblvd_tweet_filter', $text, $username ),
					    	'date' => $item->get_date( get_option('date_format') )
					    );
					}
				}
			}
			
			// Start output of tweets
			if( $tweets ) {	
				foreach( $tweets as $tweet) {	
					$output .= '<li class="tweet">';
					$output .= '<div class="tweet-wrap">';
					$output .= '<div class="tweet-text">'.$tweet['text'].'</div>';
					if( $time == 'yes' ) $output .= '<div class="tweet-time"><a href="'.$tweet['link'].'" target="_blank">'.$tweet['date'].'</a></div>';
					$output .= '</div><!-- .tweet-wrap (end) -->';
					$output .= '</li>';
				}
			}
			
			// Finish up output
			if( $output )
				$output = '<ul class="tweets">'.$output.'</ul>';
			else
				$output = '<ul class="tweets"><li>'.__( 'No public Tweets found', 'themeblvd' ).'</li></ul>';
			
		} else {
			// Received error with fetch_feed()
			$output = '<ul class="tweets"><li>'.__( 'Could not fetch Twitter RSS feed.', 'themeblvd' ).'</li></ul>';
		}
		return $output;
	}
}

/**
 * Display Recent Tweets
 *
 * @since 2.1.0
 *
 * @param string $count Number of tweets to display
 * @param string $username Twitter username to pull tweets from
 * @param string $time Display time of tweets, yes or no
 * @param string $exclude_replies Exclude replies, yes or no
 * @return string $filtered_tweet Final list of tweets
 */

if( ! function_exists( 'themeblvd_twitter' ) ) {
	function themeblvd_twitter( $count, $username, $time = 'yes', $exclude_replies = 'yes' ) {	
		echo themeblvd_get_twitter( $count, $username, $time, $exclude_replies );
	}
}

/**
 * Create new walker for WP's wp_nav_menu function. 
 * Each menu item is an <option> with the $depth being 
 * taken into account in its display.
 * 
 * We're using this with themeblvd_nav_menu_select 
 * function.
 * 
 * @since 2.2.1
 */

class ThemeBlvd_Select_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * Start level
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		// do nothing ...
	}

	/**
	 * End level
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		// do nothing ...
	}

	/**
	 * Start nav element
	 */
	function start_el( &$output, $item, $depth, $args ) {
		$indent = '';
		for( $i = 0; $i < $depth; $i++ )
			$indent .= '-';
		if( $indent )
			$indent .= ' ';
		$output .= '<option value="'.$item->url.'">'.$indent.$item->title;
	}

	/**
	 * End nav element
	 */
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= "</option>\n";
	}

}

/** 
 * Responsive wp nav menu 
 *
 * @since 2.0.0
 *
 * @param string $location Location of wp nav menu to grab
 * @return string $select_menu Select menu version of wp nav menu
 */

if( ! function_exists( 'themeblvd_nav_menu_select' ) ) {
	function themeblvd_nav_menu_select( $location ) {
		$select_menu = wp_nav_menu( apply_filters( 'themeblvd_nav_menu_select_args', array(
			'theme_location'	=> $location, 
			'container'			=> false,
			'items_wrap'		=> '<form class="responsive-nav"><select class="tb-jump-menu"><option value="">'.themeblvd_get_local('navigation').'</option>%3$s</select></form>',
			'echo' 				=> false,
			'walker' 			=> new ThemeBlvd_Select_Menu_Walker 
		)));
		return $select_menu;
	}
}

/** 
 * Get Simple Contact module (primary meant for simple contact widget)
 *
 * @since 2.0.3
 *
 * @param array $args Arguments to be used for the elements
 * @return $module HTML to output
 */

if( ! function_exists( 'themeblvd_get_simple_contact' ) ) {
	function themeblvd_get_simple_contact( $args ) {
		// Setup icon links
		$icons = array();
		for( $i = 1; $i <= 6; $i++ )
			if( ! empty( $args['link_'.$i.'_url'] ) )
				$icons[$args['link_'.$i.'_icon']] = $args['link_'.$i.'_url'];
		// Start Output
		$module = '<ul class="simple-contact">';
		// Phone #1
		if( ! empty( $args['phone_1'] ) )
			$module .= '<li class="phone">'.$args['phone_1'].'</li>';
		// Phone #2
		if( ! empty( $args['phone_2'] ) )
			$module .= '<li class="phone">'.$args['phone_2'].'</li>';
		// Email #1
		if( ! empty( $args['email_1'] ) )
			$module .= '<li class="email"><a href="mailto:'.$args['email_1'].'">'.$args['email_1'].'</a></li>';
		// Email #2
		if( ! empty( $args['email_2'] ) )
			$module .= '<li class="email"><a href="mailto:'.$args['email_2'].'">'.$args['email_2'].'</a></li>';
		// Contact Page
		if( ! empty( $args['contact'] ) )
			$module .= '<li class="contact"><a href="'.$args['contact'].'">'.themeblvd_get_local( 'contact_us' ).'</a></li>';
		// Skype
		if( ! empty( $args['skype'] ) )
			$module .= '<li class="skype">'.$args['skype'].'</li>';
		// Social Icons
		if( ! empty( $icons ) ) {
			$module .= '<li class="link"><ul class="icons">';
			foreach( $icons as $icon => $url ) {
				$module .= '<li class="'.$icon.'"><a href="'.$url.'" target="_blank" title="'.ucfirst($icon).'">'.ucfirst($icon).'</a></li>';
			}
			$module .= '</ul></li>';
		}
		$module .= '</ul>';
		return $module;
	}
}

/** 
 * Display Simple Contact module
 *
 * @since 2.1.0
 *
 * @param array $args Arguments to be used for the elements
 */

if( ! function_exists( 'themeblvd_simple_contact' ) ) {
	function themeblvd_simple_contact( $args ) {
		echo themeblvd_get_simple_contact( $args );
	}
}

/** 
 * Get Mini Post List 
 *
 * @since 2.1.0
 *
 * @param string $query Options for many post list
 * @param string $thumb Thumbnail sizes - small, smaller, or smallest
 * @param boolean $meta Show date posted or not
 * @return string $output HTML to output
 */

if( ! function_exists( 'themeblvd_get_mini_post_list' ) ) {
	function themeblvd_get_mini_post_list( $query = '', $thumb = 'smaller', $meta = true ) {
		global $post;
		$output = '';
		// CSS classes
		$classes = '';
		if( ! $thumb )
			$classes .= 'hide-thumbs';
		else
			$classes .= $thumb.'-thumbs';
		if( ! $meta )
			$classes .= ' hide-meta';
		// Get posts
		$posts = get_posts( html_entity_decode( $query ) );
		// Start output
		if( $posts ) {
			$output  = '<div class="themeblvd-mini-post-list">';
			$output .= '<ul class="'.$classes.'">';
			foreach( $posts as $post ) {
				setup_postdata( $post );
				$image = '';
				// Setup post thumbnail if user wants them to show
				if( $thumb ) {
					$image = themeblvd_get_post_thumbnail( 'primary', 'square_'.$thumb );
					// If post thumbnail isn't set, pull default thumbnail 
					// based on post format. If theme doesn't support post 
					// formats, format will always be "standard".
					if( ! $image ) {
						$default_img_directory = apply_filters( 'themeblvd_thumbnail_directory', get_template_directory_uri() . '/framework/frontend/assets/images/thumbs/' );
						$post_format = get_post_format();
						if ( ! $post_format ) 
							$post_format = 'standard';
						$image .= '<div class="featured-image-wrapper '.$classes.'">';
						$image .= '<div class="featured-image">';
						$image .= '<div class="featured-image-inner">';
						$image .= '<img src="'.$default_img_directory.$thumb.'_'.$post_format.'.png" class="wp-post-image thumbnail" />';
						$image .= '</div><!-- .featured-image-inner (end) -->';
						$image .= '</div><!-- .featured-image (end) -->';
						$image .= '</div><!-- .featured-image-wrapper (end) -->';
					}
				}
				$output .= '<li>';
				if( $image ) $output .= $image;
				$output .= '<div class="mini-post-list-content">';
				$output .= '<h4><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h4>';
				if( $meta ) $output .= '<span class="mini-meta">'.get_the_time(get_option('date_format')).'</span>';
				$output .= '</div>';
				$output .= '</li>';
			}
			wp_reset_postdata();
			$output .= '</ul>';
			$output .= '</div><!-- .themeblvd-mini-post-list (end) -->';
		} else {
			$output = themeblvd_get_local( 'archive_no_posts' );
		}
		return $output;
	}
}

/** 
 * Display Mini Post List 
 *
 * @since 2.1.0
 *
 * @param array $options Options for many post list
 */

if( ! function_exists( 'themeblvd_mini_post_list' ) ) {
	function themeblvd_mini_post_list( $options ) {
		echo themeblvd_get_mini_post_list( $options );
	}
}

/** 
 * Get Mini Post Grid 
 *
 * @since 2.1.0
 *
 * @param array $options Options for many post grid
 * @return string $output HTML to output
 */

if( ! function_exists( 'themeblvd_get_mini_post_grid' ) ) {
	function themeblvd_get_mini_post_grid( $query = '', $align = 'left', $thumb = 'smaller', $gallery = '' ) {
		global $post;
		$output = '';
		// CSS classes
		$classes = $thumb.'-thumbs';
		$classes .= ' grid-align-'.$align;
		if( $gallery )
			$classes .= ' gallery-override';
		// Check for gallery override
		if( $gallery )
			$query = 'post_type=attachment&post_parent='.$gallery.'&numberposts=-1';
		// Get posts
		$posts = get_posts( html_entity_decode( $query ) );	
		// Start output
		if( $posts ) {
			$output  = '<div class="themeblvd-mini-post-grid">';
			$output .= '<ul class="'.$classes.'">';
			foreach( $posts as $post ) {
				setup_postdata( $post );
				$output .= '<li>';
				if( $gallery ) {
					// Gallery image output to simulate featured images
					$thumbnail = wp_get_attachment_image_src( $post->ID, 'square_'.$thumb );
					$image = wp_get_attachment_image_src( $post->ID, 'full' );
					$output .= '<div class="featured-image-wrapper">';
					$output .= '<div class="featured-image">';
					$output .= '<div class="featured-image-inner">';
					$output .= '<a href="'.$image[0].'" title="" class="image thumbnail" rel="themeblvd_lightbox[gallery_'.$gallery.']">';
					$output .= '<img src="'.$thumbnail[0].'" alt="'.$post->post_title.'" />';
					$output .= apply_filters( 'themeblvd_image_overlay', '<span class="image-overlay"><span class="image-overlay-bg"></span><span class="image-overlay-icon"></span></span>' );
					$output .= '</a>';
					$output .= '</div><!-- .featured-image-inner (end) -->';
					$output .= '</div><!-- .featured-image (end) -->';
					$output .= '</div><!-- .featured-image-wrapper (end) -->';
				} else {
					// Standard featured image output
					$image = '';
					$image = themeblvd_get_post_thumbnail( 'primary', 'square_'.$thumb );
					// If post thumbnail isn't set, pull default thumbnail 
					// based on post format. If theme doesn't support post 
					// formats, format will always be "standard".
					if( ! $image ) {
						$default_img_directory = apply_filters( 'themeblvd_thumbnail_directory', get_template_directory_uri() . '/framework/frontend/assets/images/thumbs/' );
						$post_format = get_post_format();
						if ( ! $post_format ) 
							$post_format = 'standard';
						$image .= '<div class="featured-image-wrapper '.$classes.'">';
						$image .= '<div class="featured-image">';
						$image .= '<div class="featured-image-inner">';
						$image .= '<img src="'.$default_img_directory.$thumb.'_'.$post_format.'.png" class="wp-post-image thumbnail" />';
						$image .= '</div><!-- .featured-image-inner (end) -->';
						$image .= '</div><!-- .featured-image (end) -->';
						$image .= '</div><!-- .featured-image-wrapper (end) -->';
					}
					$output .= $image;	
				}		
				$output .= '</li>';
			}
			wp_reset_postdata();
			$output .= '</ul>';
			$output .= '<div class="clear"></div>';
			$output .= '</div><!-- .themeblvd-mini-post-list (end) -->';
		} else {
			$output = themeblvd_get_local( 'archive_no_posts' );
		}
		return $output;
	}
}

/** 
 * Display Mini Post Grid 
 *
 * @since 2.1.0
 *
 * @param array $options Options for many post grid
 */

if( ! function_exists( 'themeblvd_mini_post_grid' ) ) {
	function themeblvd_mini_post_grid( $query = '', $align = 'left', $thumb = 'smaller', $gallery = '' ) {
		echo themeblvd_get_mini_post_grid( $query, $align, $thumb, $gallery );
	}
}