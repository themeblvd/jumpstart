<?php
/**
 * Remove trailing space from string.
 *
 * @since 2.0.0
 *
 * @param string $string Current string to check
 * @param string $char Character to remove from end of string if exists
 * @return string $string String w/out trailing space, if it had one
 */
function themeblvd_remove_trailing_char( $string, $char = ' ' ) {

	if ( ! $string ) {
		return null;
	}

	$offset = strlen( $string ) - 1;
	$trailing_char = strpos( $string, $char, $offset );

	if ( $trailing_char ) {
		$string = substr( $string, 0, -1 );
	}

	return $string;
}

/**
 * Get the name for a font face to be used within the CSS.
 *
 * @since 2.0.0
 *
 * @param array $option Current option set by user for the font
 * @return string $stack CSS value for font-name property
 */
function themeblvd_get_font_face( $option ) {

	$stack = '';
	$stacks = themeblvd_font_stacks();

	if ( $option['face'] == 'google'  ) {

		// Grab font face, making sure they didn't do the
		// super, sneaky trick of including font weight or type.
		$name = explode( ':', $option['google'] );

		// And also check for accidental space at end
		$name = themeblvd_remove_trailing_char( $name[0] );

		// Add the deafult font stack to the end of the
		// google font.
		$stack = $name.', '.$stacks['default'];

	} else {
		$stack = $stacks[$option['face']];
	}

	return apply_filters( 'themeblvd_font_face', $stack, $option, $stacks );
}

/**
 * Get font size
 *
 * @since 2.3.0
 *
 * @param array $option Current option set by user for the font
 * @return string $size CSS value for font-size property
 */
function themeblvd_get_font_size( $option ) {

	$size = '13px';

	if ( isset( $option['size'] ) ) {
		$size = $option['size'];
	}

	return apply_filters( 'themeblvd_font_size', $size, $option );
}

/**
 * Get font style
 *
 * @since 2.3.0
 *
 * @param array $option Current option set by user for the font
 * @return string $style CSS value for font-style property
 */
function themeblvd_get_font_style( $option ) {

	$style = 'normal';

	if ( isset( $option['style'] ) && ( $option['style'] == 'italic' || $option['style'] == 'bold-italic' ) ) {
		$style = 'italic';
	}

	return apply_filters( 'themeblvd_font_style', $style, $option );
}

/**
 * Get font weight
 *
 * @since 2.3.0
 *
 * @param array $option Current option set by user for the font
 * @return string $weight CSS value for font-weight property
 */
function themeblvd_get_font_weight( $option ) {

	$weight = 'normal';

	if ( isset( $option['style'] ) ) {
		if ( $option['style'] == 'bold' || $option['style'] == 'bold-italic' ) {
			$weight = 'bold';
		} else if ( $option['style'] == 'thin' ) {
			$weight = '300';
		}
	}

	return apply_filters( 'themeblvd_font_weight', $weight, $option );
}

/**
 * Setup arguments to pass into get_posts()
 *
 * @since 2.0.0
 *
 * @param array $options All options for query string
 * @param string $type Type of posts setup, grid or list
 * @return array $args Arguments to get passed into get_posts()
 */
function themeblvd_get_posts_args( $options, $type = 'list' ) {

	// Is there a query source? (i.e. category, tag, query)
	$source = '';

	if ( ! empty( $options['source'] ) ) {
		$source = $options['source'];
	}

	// How are we displaying?
	$display = $type;

	if ( ! empty( $options['display'] ) ) {
		$display = $options['display'];
	}

	// Custom query
	if ( ( $source == 'query' && isset( $options['query'] ) ) || ( ! $source && ! empty( $options['query'] ) ) ) {

		$query = $options['query'];

		/**
		 * If user is passing some sort of identfier key that they can
		 * catch with a custom filter, let's just send it through, or
		 * else we can continue to process the custom query.
		 * If the custom query has no equal sign "=", then we can assume
		 * they're not intending it to be an actual query string, and thus
		 * just sent it through.
		 */
		if ( is_array($query) || strpos($query, '=') !== false ) {

			// Pull custom field to determine query, use custom_field=my_query
			// for element's query string
			if ( is_string( $query ) && strpos($query, 'custom_field=') === 0 ) {
				$query = get_post_meta( themeblvd_config('id'), str_replace('custom_field=', '', $query), true );
			}

			// Convert string to query array
			if ( ! is_array( $query ) ) {
				$query = wp_parse_args( htmlspecialchars_decode($query) );
			}

			// Force posts per page on grids
			if ( ( $display == 'grid' || $display == 'showcase' ) && apply_filters( 'themeblvd_force_grid_posts_per_page', true ) ) {
				if ( ! empty( $options['rows'] ) && ! empty( $options['columns'] ) ) {
					$query['posts_per_page'] = $options['rows']*$options['columns'];
				}
			}

		}

	}

	// List of pages
	if ( ! isset( $query ) && $source == 'pages' && ! empty( $options['pages'] ) ) {

		$options['pages'] = str_replace( ' ', '', $options['pages'] );
		$options['pages'] = explode( ',', $options['pages'] );

		$query = array(
			'post_type' => 'page',
			'post__in' 	=> array(),
			'orderby'	=> 'post__in'
		);

		if ( $options['pages'] ) {
			foreach ( $options['pages'] as $pagename ) {
				$query['post__in'][] = themeblvd_post_id_by_name( $pagename, 'page' );
			}
		}
	}

	// If no custom query, let's build it.
	if ( ! isset( $query ) ) {

		// Start $query
		$query = array( 'suppress_filters' => false );

		// Categories
		if ( $source == 'category' || ! $source ) {

			if ( ! empty( $options['cat'] ) ) {

				// Category override option #1 -- cat
				$query['cat'] = $options['cat'];

			} else if ( ! empty( $options['category_name'] ) ) {

				// Category override option #2 -- category_name
				$query['category_name'] = $options['category_name'];

			} else if ( ! empty( $options['categories'] ) && ! $options['categories']['all'] ) {

				unset( $options['categories']['all'] );
				$categories = '';

				foreach ( $options['categories'] as $category => $include ) {
					if ( $include ) {
						$current_category = get_term_by( 'slug', $category, 'category' );
						$categories .= $current_category->term_id.',';
					}
				}

				if ( $categories ) {
					$categories = themeblvd_remove_trailing_char( $categories, ',' );
					$query['cat'] = $categories;
				}
			}

		}

		// Tags
		if ( $source == 'tag' || ! $source ) {
			if ( ! empty( $options['tag'] ) ) {
				$query['tag'] = $options['tag'];
			}
		}

		// If post slider (NOT grid slider), we only want
		// images with featured images set.
		if ( $type == 'slider' ) {
			$query['meta_key'] = '_thumbnail_id';
		}

		// Additional args
		if ( ! empty( $options['orderby'] ) ) {
			$query['orderby'] = $options['orderby'];
		}

		if ( ! empty( $options['order'] ) ) {
			$query['order'] = $options['order'];
		}

		if ( ! empty( $options['offset'] ) ) {
			$query['offset'] = intval( $options['offset'] );
		}

		if ( ! empty( $options['meta_key'] ) ) {
			$query['meta_key'] = $options['meta_key'];
		}

		if ( ! empty( $options['meta_value'] ) ) {
			$query['meta_value'] = $options['meta_value'];
		}

	}

	// Posts per page
	if ( is_array( $query ) && empty( $query['posts_per_page'] ) ) {

		// Number of posts
		if ( $type == 'grid' || $type == 'showcase' ) {

			if ( ! empty( $options['columns'] ) ) {

				if ( $display == 'slider' && ! empty( $options['slides'] ) ) {
					$query['posts_per_page'] = intval($options['slides'])*intval($options['columns']);
				} else if ( $display == 'masonry' && ! empty( $options['posts_per_page'] ) ) {
					$query['posts_per_page'] = $options['posts_per_page'];
				} else if ( ( $display == 'filter' || $display == 'masonry_filter' ) && ! empty( $options['filter_max'] ) ) {
					$query['posts_per_page'] = $options['filter_max'];
				} else if ( ! empty( $options['rows'] ) && ! empty( $options['columns'] ) ) {
					$query['posts_per_page'] = intval($options['rows'])*intval($options['columns']);
				}

			}

		} else {

			if ( ! empty( $options['posts_per_page'] ) ) {
				$query['posts_per_page'] = intval( $options['posts_per_page'] );
			}

		}

		if ( empty( $query['posts_per_page'] ) ) {
			$query['posts_per_page'] = -1;
		}

	}

	return apply_filters( 'themeblvd_get_posts_args', $query, $options, $type );
}

/**
 * Get posts per page for grid of posts.
 *
 * @since 2.0.0
 * @deprecated 2.3.0
 *
 * @param string $type Type of grid, template or builder
 * @param string $columns Number of columns to use
 * @param string $columns Number of rows to use
 * @return int $posts_per_page The number of posts per page for a grid.
 */
function themeblvd_posts_page_page( $type, $columns = null, $rows = null ) {

	if ( $type == 'template' ) {
		if ( ! $columns || ! $rows ) {

			global $post;

			$possible_column_nums = array( 1, 2, 3, 4, 5 );
			$posts_per_page = null;

			// Columns
			$columns = get_post_meta( $post->ID, 'columns', true );
			if ( ! in_array( intval($columns), $possible_column_nums ) ) {
				$columns = apply_filters( 'themeblvd_default_grid_columns', 3 );
			}

			// Rows
			$rows = get_post_meta( $post->ID, 'rows', true );
			if ( ! $rows ) {
				$rows = apply_filters( 'themeblvd_default_grid_columns', 4 );
			}

		}
	}

	// Posts per page
	$posts_per_page = $columns * $rows;

	return apply_filters('themeblvd_posts_page_page', $posts_per_page, $type, $columns, $rows );
}

/**
 * Determine color of text depending on background color.
 *
 * Huge thank you to Oscar for providing this:
 * http://stackoverflow.com/questions/3015116/hex-code-brightness-php
 *
 * @since 2.0.0
 *
 * @param string $bg_color Background color to determine text color for, ex: #ffffff
 * @return string $text_color Text color to show on inputed background color
 */
function themeblvd_text_color( $bg_color ) {

	// Pop off '#' from start.
	$bg_color = explode( '#', $bg_color );
	$bg_color = $bg_color[1];

	// Break up the color in its RGB components
	$r = hexdec( substr( $bg_color,0,2 ) );
	$g = hexdec( substr( $bg_color,2,2 ) );
	$b = hexdec( substr( $bg_color,4,2 ) );

	// Simple weighted average
	if ( $r + $g + $b > 382 ) {
		// bright color, use dark font
	    $text_color = apply_filters( 'themeblvd_dark_font', '#333333' );
	} else {
		// dark color, use bright font
	    $text_color = apply_filters( 'themeblvd_light_font', '#ffffff' );
	}

	return apply_filters( 'themeblvd_text_color', $text_color, $bg_color );
}

/**
 * Darken or Lighten a hex color
 *
 * Huge thank you to Jonas John for providing this:
 * http://www.jonasjohn.de/snippets/php/darker-color.htm
 *
 * @since 2.0.5
 *
 * @param string $color Hex color to adjust
 * @param string $difference Amount to adjust color
 * @param string $direction 'lighten' or 'darken'
 * @return string $new_color Adjusted color
 */
function themeblvd_adjust_color( $color, $difference = 20, $direction = 'darken' ) {

	// Pop off '#' from start.
	$color = explode( '#', $color );
	$color = $color[1];

	// Send back in black if it's not a properly
	// formatted 6-digit hex
	if ( strlen( $color ) != 6 ) {
		return '#000000';
	}

	// Build new color
	$new_color = '';
	for ( $x = 0; $x < 3; $x++ ) {
	    if( $direction == 'lighten' )
	    	$c = hexdec( substr( $color, ( 2*$x ), 2 ) ) + $difference;
	    else
			$c = hexdec( substr( $color, ( 2*$x ), 2 ) ) - $difference;
	    $c = ( $c < 0 ) ? 0 : dechex( $c );
	    $new_color .= ( strlen( $c ) < 2 ) ? '0'.$c : $c;
	}

	return apply_filters( 'themeblvd_adjust_color', '#'.$new_color, $color, $difference, $direction );
}

/**
 * Get an rgb or rgba value based on color hex value.
 *
 * @since 2.5.0
 *
 * @param string $hex Color hex - ex: #000 or 000
 * @param string $opacity Opacity value to determine rgb vs rgba - ex: 0.5
 * @return array $classes Classes for element.
 */
function themeblvd_get_rgb( $color, $opacity = '' ) {

	$default = 'rgb(0,0,0)';

	if ( ! $color ) {
		return $default;
	}

	// Sanitize $color if "#" is provided
	$color = str_replace('#', '', $color);

    // Check if color has 6 or 3 characters and get values
    if ( strlen($color) == 6 ) {
		$hex = array( $color[0].$color[1], $color[2].$color[3], $color[4].$color[5] );
    } elseif ( strlen($color) == 3 ) {
		$hex = array( $color[0].$color[0], $color[1].$color[1], $color[2].$color[2] );
    } else {
		return $default;
    }

    // Convert hexadec to rgb
    $rgb =  array_map( 'hexdec', $hex );

    // Check if opacity is set(rgba or rgb)
    if ( $opacity ) {

		if( abs($opacity) > 1 ) {
    		$opacity = '1.0';
    	}

    	$output = 'rgba('.implode(',', $rgb).','.$opacity.')';

    } else {
    	$output = 'rgb('.implode(',', $rgb).')';
    }

    return $output;
}

/**
 * Get the class to be used for resposive visibility.
 *
 * hide_on_standard
 * hide_on_standard_and_tablet
 * hide_on_standard_and_tablet_and_mobile
 * hide_on_standard_and_mobile
 * hide_on_tablet
 * hide_on_tablet_and_mobile
 * hide_on_mobile
 *
 * @since 2.1.0
 *
 * @param array $devices Devices to be hidden on
 * @param boolean $start_space Whether there should be a space at start
 * @param boolean $end_space Whether there should be a space at end
 * @return var $class CSS class to use
 */
function themeblvd_responsive_visibility_class( $devices, $start_space = false, $end_space = false ) {

	// Build class
	$class = '';
	$exists = false;

	if ( is_array( $devices ) && ! empty( $devices ) ) {
		foreach ( $devices as $device ) {
			if ( $device ) {
				$exists = true;
			}
		}
	}

	// Only start buld if there's a class to build
	if ( $exists ) {

		$class = 'hide_on_';

		if ( $devices['hide_on_standard'] ) {

			// Standard Devices
			$class .= 'standard';
			if ( $devices['hide_on_tablet'] ) {
				$class .= '_and_tablet';
			}

			if ( $devices['hide_on_mobile'] ) {
				$class .= '_and_mobile';
			}

		} else if ( $devices['hide_on_tablet'] ) {

			// Tablets
			$class .= 'tablet';
			if ( $devices['hide_on_mobile'] ) {
				$class .= '_and_mobile';
			}

		} else if ( $devices['hide_on_mobile'] ) {

			// Mobile
			$class .= 'mobile';

		}
	}

	// Apply filter
	$class = apply_filters( 'themeblvd_responsive_visibility_class', $class, $devices );

	// Start/End spaces
	if ( $class ) {

		if ( $start_space ) {
			$class = ' '.$class;
		}

		if ( $end_space ) {
			$class .= ' ';
		}

	}

	// Return class
	return $class;
}

/**
 * Output <title> if using WordPress 4.0-.
 * WordPress 4.1+ uses native `title-tag`
 * theme feature.
 *
 * @since 2.5.0
 */
function themeblvd_wp_title_compat() {

	// If WP 4.1+, do nothing
	if ( function_exists( '_wp_render_title_tag' ) ) {
		return;
	}

	add_filter( 'wp_title', 'themeblvd_wp_title' );

	printf( "<title>%s</title>\n", wp_title( '|', false, 'right' ) );

}

/**
 * Display <title>
 * This is added to wp_title filter.
 *
 * @since 2.2.0
 * @deprecated 2.5.0
 */
function themeblvd_wp_title( $title ) {

	global $page, $paged;

	// Make sure this function is used with WP 4.1+
	if ( function_exists( '_wp_render_title_tag' ) ) {
		return $title;
	}

	// Don't screw with RSS feed titles
	if ( is_feed() ) {
		return $title;
	}

	// Add the blog name.
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " | $site_description";
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$title .= ' | ' . sprintf( themeblvd_get_local( 'page_num' ), max( $paged, $page ) );
	}

	return apply_filters( 'themeblvd_wp_title', $title );
}

/**
 * Output modernizer JS so HTML5 will work in IE8
 *
 * @since 2.5.0
 */
function themeblvd_html5_compat() {
	echo "<!--[if lt IE 9]>\n";
	printf('<script src="%s/framework/assets/js/html5.js" type="text/javascript"></script>', get_template_directory_uri() );
	echo "\n<![endif]-->\n";
}

if ( !function_exists( 'themeblvd_standard_slider_js' ) ) :
/**
 * Print out the JS for setting up a standard slider.
 *
 * @since 2.0.0
 */
function themeblvd_standard_slider_js( $id, $options ) {
	?>
	<script>
	jQuery(document).ready(function($) {
		$(window).load(function() {

			// Initiate flexslider for this slider.
			$('#tb-slider-<?php echo $id; ?> .flexslider').flexslider({
				useCSS: false, // Avoid CSS3 glitches
				video: true, // Avoid CSS3 glitches
				<?php if ( ! empty( $options['smoothheight'] ) && $options['smoothheight'] == 'true' ) : ?>
				smoothHeight: true,
				<?php endif; ?>
				prevText: '<i class="fa fa-chevron-left"></i>',
				nextText: '<i class="fa fa-chevron-right"></i>',
				animation: "<?php echo $options['fx']; ?>",
				// pauseOnHover: true - This was replaced with a custom solution to work with other controls, see below with "pause_on_hover" option.
				<?php if ( $options['timeout'] ) : ?>
				slideshowSpeed: <?php echo $options['timeout']; ?>000,
				<?php else : ?>
				slideshow: false,
				<?php endif; ?>
				<?php if ( ! $options['nav_arrows'] ) echo 'directionNav: false,'; ?>
				<?php if ( ! $options['nav_standard'] ) echo 'controlNav: false,'; ?>
				controlsContainer: ".slides-wrapper-<?php echo $id; ?>",
				<?php do_action( 'themeblvd_flexslider_properties', $id, $options ); ?>
				start: function(slider) {
    				<?php if ( $options['pause_play'] && $options['timeout'] != '0' ) : ?>
	    				$('#tb-slider-<?php echo $id; ?> .flex-direction-nav li:first-child').after('<li><a class="flex-pause" href="#"><i class="icon-pause"></i></a></li><li><a class="flex-play" href="#" style="display:none"><i class="icon-play"></i></a></li>');
	    				$('#tb-slider-<?php echo $id; ?> .flex-pause').click(function(){
							slider.pause();
							$(this).hide();
							$('#tb-slider-<?php echo $id; ?> .flex-play').show();
							return false;
						});
						$('#tb-slider-<?php echo $id; ?> .flex-play').click(function(){
							// slider.resume(); currently has a bug with FlexSlider 2.0, so will do the next line instead.
							$('#tb-slider-<?php echo $id; ?> .flexslider').flexslider('play');
							$(this).hide();
							$('#tb-slider-<?php echo $id; ?> .flex-pause').show();
							return false;
						});
						$('#tb-slider-<?php echo $id; ?> .flex-control-nav li, #tb-slider-<?php echo $id; ?> .flex-direction-nav li').click(function(){
							$('#tb-slider-<?php echo $id; ?> .flex-pause').hide();
							$('#tb-slider-<?php echo $id; ?> .flex-play').show();
						});
					<?php endif; ?>
    				$('#tb-slider-<?php echo $id; ?> .slide-thumbnail-link').click(function() {
    					$('#tb-slider-<?php echo $id; ?> .flex-pause').hide();
    					$('#tb-slider-<?php echo $id; ?> .flex-play').show();
    					slider.pause();
    				});
    			}
			}).parent().find('.tb-loader').fadeOut();

			<?php if ( isset( $options['pause_on_hover'] ) ) : ?>
				<?php if ( $options['pause_on_hover'] == 'pause_on' || $options['pause_on_hover'] == 'pause_on_off' ) : ?>
				// Custom pause on hover funtionality
				$('#tb-slider-<?php echo $id; ?>').hover(
					function() {
						$('#tb-slider-<?php echo $id; ?> .flex-pause').hide();
						$('#tb-slider-<?php echo $id; ?> .flex-play').show();
						$('#tb-slider-<?php echo $id; ?> .flexslider').flexslider('pause');
					},
					function() {
						<?php if ( $options['pause_on_hover'] == 'pause_on_off' ) : ?>
						$('#tb-slider-<?php echo $id; ?> .flex-play').hide();
						$('#tb-slider-<?php echo $id; ?> .flex-pause').show();
						$('#tb-slider-<?php echo $id; ?> .flexslider').flexslider('play');
						<?php endif; ?>
					}
				);
				<?php endif; ?>
			<?php endif; ?>

		});
	});
	</script>
	<?php
}
endif;

/**
 * Get Comment List arguments for comments.php
 *
 * @since 2.2.0
 *
 * @return array $args Arguments to be passed into wp_list_comments()
 */
function themeblvd_get_comment_list_args() {
	$args = array(
		'avatar_size' 		=> 48,
		'style' 			=> 'ul',
		'type' 				=> 'all',
		'reply_text' 		=> themeblvd_get_local( 'reply' ),
		'login_text' 		=> themeblvd_get_local( 'login_text' ),
		'callback' 			=> null,
		'reverse_top_level' => null,
		'reverse_children' 	=> false
	);
	return apply_filters( 'themeblvd_comment_list', $args );
}

/**
 * Get Comment Form arguments for comments.php
 *
 * @since 2.2.0
 *
 * @return array $args Arguments to be passed into comment_form()
 */
function themeblvd_get_comment_form_args() {
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$args = array(
		'comment_field'			=> '<p class="comment-form-comment"><label for="comment">'.themeblvd_get_local('comment').'</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		'title_reply'			=> themeblvd_get_local( 'title_reply' ),
		'title_reply_to'		=> themeblvd_get_local( 'title_reply_to' ),
		'cancel_reply_link'		=> themeblvd_get_local( 'cancel_reply_link' ),
		'label_submit'			=> themeblvd_get_local( 'label_submit' ),
		'comment_notes_after'	=> '<p class="form-allowed-tags">' . sprintf( themeblvd_get_local('comments_notes_after'), '</p><code class="block">' . allowed_tags() . '</code>' ) . '</p>'
	);
	return apply_filters( 'themeblvd_comment_form', $args, $commenter, $req, $aria_req );
}

/**
 * If default language is English, this will swap in our
 * filtered framework text strings. This helps us to keep
 * control of all text strings being outputted on the frontend
 * of the theme.
 *
 * Filtered onto WP's "comment_form_default_fields"
 *
 * @param array $fields Current fields from WP's comment form
 * @return array $fields Modified fields
 */
function themeblvd_comment_form_fields( $fields ) {
	$fields['author'] = str_replace( 'Name', themeblvd_get_local('name'), $fields['author'] );
	$fields['email'] = str_replace( 'Email', themeblvd_get_local('email'), $fields['email'] );
	$fields['url'] = str_replace( 'Website ', themeblvd_get_local('website'), $fields['url'] );
	return $fields;
}

/**
 * Determine whether comments section should show on
 * a single post.
 *
 * At first glance, you're probably wondering why this
 * would exist when you the WP user could just close
 * the comments. When the user closes the comments,
 * comments will still be present and in place of the
 * comment form, it will say that the comments are closed.
 *
 * However, in addition to that, this framework allows
 * the user to completely hide the comments section all
 * together in various ways. So, this extends further
 * up than simply haivng the comments for a post closed.
 *
 * @since 2.2.0
 *
 * @return boolean $show Arguments to be passed into wp_list_comments()
 */
function themeblvd_show_comments() {

	global $post;
	$show = true; // default

	if ( is_single() ) {

		if ( themeblvd_get_option( 'single_comments', null, 'show' ) == 'hide' ) {
			$show = false;
		}

		if ( get_post_meta( $post->ID, '_tb_comments', true ) == 'hide' ) {
			$show = false;
		} else if ( get_post_meta( $post->ID, '_tb_comments', true ) == 'show' ) {
			$show = true;
		}

	}
	return apply_filters( 'themeblvd_show_comments', $show );
}

/**
 * Forward password-protected pages using
 * page templates to page.php
 *
 * @since 2.2.1
 *
 * @param string $template Current template file
 * @return string $template Current theme location of page.php
 */
function themeblvd_private_page( $template ) {

	// Only for password protected pages.
	if ( ! post_password_required() ) {
		return $template;
	}

	// Custom Layouts
	if ( themeblvd_config( 'builder' ) ) {
		$template = locate_template( 'page.php' );
	}

	// Page Templates
	$page_templates = apply_filters( 'themeblvd_private_page_support', array( 'template_grid.php', 'template_list.php', 'template_archives.php', 'template_sitemap.php' ) );
	foreach ( $page_templates as $page_template ) {
		if ( is_page_template( $page_template ) ) {
			$template = locate_template( 'page.php' );
		}
	}

	// Removed hooked the_content on Post Grid/List templates
	if ( is_page_template( 'template_list.php' ) || is_page_template( 'template_grid.php' ) ) {
		remove_action( 'themeblvd_content_top', 'themeblvd_content_top_default' );
	}

	return $template;
}

/**
 * When using wp_link_pages(), match surrounding markup
 * to themeblvd_pagination() and integration of Bootstrap.
 *
 * This function is attached to the filter wp_link_pages_args,
 * but won't do anything unless WP version is 3.6+.
 *
 * @since 2.2.1
 *
 * @param array $args Default arguments of wp_link_pages() to filter
 * @return array $args Args for wp_link_pages() after we've altered them
 */
function themeblvd_link_pages_args( $args ) {

	global $wp_version;

	// Before WP 3.6, this filter can't be applied because the
	// wp_link_pages_link filter did not exist yet. Our changes
	// need to come together.
	if ( version_compare( $wp_version, '3.6-alpha', '<' ) ) {
		return $args;
	}

	// Add TB Framework/Bootstrap surrounding markup
	$args['before'] = '<div class="pagination-wrap"><div class="pagination"><div class="btn-group clearfix">';
	$args['after'] = "</div></div></div>\n";

	return $args;
}

/**
 * When using wp_link_pages(), match individual button markup
 * to themeblvd_pagination() and integration of Bootstrap.
 *
 * This function is attached to the wp_link_pages_link filter,
 * which only exists in WP 3.6+.
 *
 * @since 2.2.1
 *
 * @param string $link Markup of individual link to be filtered
 * @param int $i Page number of link being filtered
 * @return string $link Markup for individual link after being filtered
 */
function themeblvd_link_pages_link( $link, $i ) {

	global $page;

	$color = apply_filters( 'themeblvd_link_pages_button_color', 'default' );
	$size = apply_filters( 'themeblvd_link_pages_button_size', 'default' );
	$class = themeblvd_get_button_class( $color, $size );

	// If is current page
	if ( $page == $i ) {
		$class .= ' active';
		$link = sprintf( '<a href="%s" class="%s">%s</a>', get_pagenum_link($i), $class, $i );
	} else {
		$link = str_replace( '<a', '<a class="'.$class.'"', $link ); // Getting actual URL to rebuild link is a bitch
	}

	return $link;
}

/**
 * Add CSS classes to reply link to they are styled as
 * a Bootstrap button.
 *
 * @since 2.4.0
 *
 * @param string $formatted_link Current HTML to modify for button
 * @return string|bool|null Link to show comment form, if successful. False, if comments are closed.
 */
function themeblvd_comment_reply_link( $formatted_link ) {
	$color = apply_filters( 'themeblvd_comment_reply_button_color', 'default' );
	$size = apply_filters( 'themeblvd_comment_reply_button_size', 'small' );
	$class = themeblvd_get_button_class( $color, $size );
	return str_replace( 'comment-reply-link', sprintf( 'comment-reply-link %s', $class ), $formatted_link );
}

/**
 * Construct parts of a breadcrumbs trail as an array
 * to be used when displaying breadcrumbs.
 *
 * @since 2.2.1
 *
 * @param string $atts Filtered attributes for breadcrumbs
 * @return array $breadcrumbs Breadcrumbs parts to display trail
 */
function themeblvd_get_breadcrumb_parts( $atts ) {

	global $post;
	global $wp_query;
	$breadcrumbs = array();
	$parts = array();
	wp_reset_query();

	// Home
	$breadcrumbs[] = array(
		'link' 	=> $atts['home_link'],
		'text' 	=> $atts['home'],
		'type'	=> 'home'
	);

	// Build parts
	if ( is_category() ) {

		/* Category Archives */

		$cat_obj = $wp_query->get_queried_object();
		$current_cat = $cat_obj->term_id;
		$current_cat = get_category( $current_cat );

		if ( $current_cat->parent && ( $current_cat->parent != $current_cat->term_id ) ) {
			$parents = themeblvd_get_category_parents( $current_cat->parent );
			if ( is_array( $parents ) ) {
				$parts = array_merge( $parts, $parents );
			}
		}

		// Add current category
		$parts[] = array(
			'link' 	=> '',
			'text' 	=> $current_cat->name,
			'type'	=> 'category'
		);

	} elseif ( is_day() ) {

		/* Day Archives */

		// Year
		$parts[] = array(
			'link' 	=> get_year_link( get_the_time('Y') ),
			'text' 	=> get_the_time('Y'),
			'type'	=> 'year'
		);

		// Month
		$parts[] = array(
			'link' 	=> get_month_link( get_the_time('Y'), get_the_time('m') ),
			'text' 	=> get_the_time('F'),
			'type'	=> 'month'
		);

		// Day
		$parts[] = array(
			'link' 	=> '',
			'text' 	=> get_the_time('d'),
			'type'	=> 'day'
		);

	} elseif ( is_month() ) {

		/* Month Archives */

		// Year
		$parts[] = array(
			'link' 	=> get_year_link( get_the_time('Y') ),
			'text' 	=> get_the_time('Y'),
			'type'	=> 'year'
		);

		// Month
		$parts[] = array(
			'link' 	=> '',
			'text' 	=> get_the_time('F'),
			'type'	=> 'month'
		);

	} elseif ( is_year() ) {

		/* Year Archives */

		// Year
		$parts[] = array(
			'link' 	=> '',
			'text' 	=> get_the_time('Y'),
			'type'	=> 'year'
		);

	} elseif ( is_tag() ) {

		/* Tag Archives */

		$parts[] = array(
			'link' 	=> '',
			'text' 	=> themeblvd_get_local('crumb_tag').' "'.single_tag_title('', false).'"',
			'type'	=> 'tag'
		);

	} elseif ( is_author() ) {

		/* Author Archives */

		global $author;
		$userdata = get_userdata( $author );

		$parts[] = array(
			'link' 	=> '',
			'text' 	=> themeblvd_get_local('crumb_author').' '.$userdata->display_name,
			'type'	=> 'author'
		);

	} elseif ( is_attachment() ) {

		/* Attachment */

		$parent = get_post( $post->post_parent );
		if ( ! empty( $parent ) ) {

			$category = get_the_category( $parent->ID );

			if ( ! empty( $category ) ) {
				$category = $category[0];
				$parents = themeblvd_get_category_parents( $category->term_id );
				if ( is_array( $parents ) ) {
					$parts = array_merge( $parts, $parents );
				}
			}

			$parts[] = array(
				'link' 	=> get_permalink( $parent->ID ),
				'text' 	=> $parent->post_title,
				'type'	=> 'single'
			);

		}

		$parts[] = array(
			'link' 	=> '',
			'text' 	=> get_the_title(),
			'type'	=> 'attachment'
		);

	} elseif ( is_single() ) {

		/* Single Posts */

		if ( get_post_type() == 'post' ) {
			// Categories (only if standard post type)
			$category = get_the_category();
			$category = $category[0];
			$parents = themeblvd_get_category_parents( $category->term_id );
			if ( is_array( $parents ) ) {
				$parts = array_merge( $parts, $parents );
			}
		}

		$parts[] = array(
			'link' 	=> '',
			'text' 	=> get_the_title(),
			'type'	=> 'single'
		);

	} elseif ( is_search() ) {

		/* Search Results */

		$parts[] = array(
			'link' 	=> '',
			'text' 	=> themeblvd_get_local('crumb_search').' "'.get_search_query().'"',
			'type'	=> 'search'
		);

	} elseif ( is_page() ) {

		/* Pages */

		if ( $post->post_parent ) {

			// Parent pages
			$parent_id  = $post->post_parent;
			$parents = array();

			while ( $parent_id ) {
				$page = get_page( $parent_id );
				$parents[] = array(
					'link' 	=> get_permalink( $page->ID ),
					'text' 	=> get_the_title( $page->ID ),
					'type'	=> 'page'
				);
				$parent_id = $page->post_parent;
			}

			$parents = array_reverse( $parents );
			$parts = array_merge( $parts, $parents );

		}
		$parts[] = array(
			'link' 	=> '',
			'text' 	=> get_the_title(),
			'type'	=> 'page'
		);

	} elseif ( is_404() ) {

		/* 404 */

		$parts[] = array(
			'link' 	=> '',
			'text' 	=> themeblvd_get_local('crumb_404'),
			'type'	=> '404'
		);

	}

	// Filter the trail before the Home link is
	// added to the start, or the page num is
	// added to the end.
	$parts = apply_filters( 'themeblvd_pre_breadcrumb_parts', $parts, $atts );

	// Add page number if is paged
	if ( get_query_var('paged') ) {
		$last = count($parts) - 1;
		$parts[$last]['text'] .= ' ('.themeblvd_get_local('page').' '.get_query_var('paged').')';
	}

	return apply_filters( 'themeblvd_breadcrumb_parts', array_merge($breadcrumbs, $parts), $atts );
}

/**
 * Determine if breadcrumbs should show or not.
 *
 * @since 2.2.1
 *
 * @return bool $show Whether breadcrumbs should show or not
 */
function themeblvd_show_breadcrumbs() {

	global $post;
	$display = '';

	// Pages and Posts
	if ( is_page() || is_single() ) {
		$display = get_post_meta( $post->ID, '_tb_breadcrumbs', true );
	}

	// Standard site-wide option
	if ( ! $display || $display == 'default' ) {
		$display = themeblvd_get_option( 'breadcrumbs', null, 'show' );
	}

	// Disable on posts homepage
	if ( is_home() ) {
		$display = 'hide';
	}

	// Disable on custom layouts (can be added in layout from Builder)
	if ( is_page_template('template_builder.php') ) {
		$display = 'hide';
	}

	// Convert to boolean
	$show = false;

	if ( $display == 'show' ) {
		$show = true;
	}

	return apply_filters( 'themeblvd_show_breadcrumbs', $show, $display );
}

/**
 * Get parent category attributes
 *
 * @since 2.2.1
 *
 * @param int $id ID of closest category parent
 * @param array $used Any categories in our chain that we've already used
 * @return array $var Description
 */
function themeblvd_get_category_parents( $id, $used = array() ) {
	return themeblvd_get_term_parents( $id, 'category', $used );
}

/**
 * Get parent term attributes
 *
 * @since 2.3.0
 *
 * @param int $id ID of closest category parent
 * @param array $used Any categories in our chain that we've already used
 * @return array $var Description
 */
function themeblvd_get_term_parents( $id, $taxonomy = 'category', $used = array() ) {

	$chain = array();
	$parent = get_term( intval( $id ), $taxonomy );

	// Get out of here if there's an error
	if ( is_wp_error( $parent ) ) {
		return $parent;
	}

	// Parent of the parent
	if ( $parent->parent && ( $parent->parent != $parent->term_id ) && ! in_array( $parent->parent, $used ) ) {
		$used[] = $parent->parent;
		$grand_parent = themeblvd_get_term_parents( $parent->parent, $taxonomy, $used );
		$chain = array_merge( $grand_parent, $chain );
	}

	// Final part of chain
	$chain[] = array(
		'link' 	=> esc_url( get_term_link( intval( $id ), $taxonomy ) ),
		'text' 	=> $parent->name,
		'type'	=> $taxonomy
	);

	return $chain;
}

/**
 * Get pagination parts.
 *
 * @since 2.3.0
 *
 * @param int $pages Optional number of pages
 * @param int $range Optional range for paginated buttons, helpful for many pages
 * @return array $parts Parts to construct pagination
 */
function themeblvd_get_pagination_parts( $pages = 0, $range = 2 ) {

	global $paged;
	global $wp_query;

	$parts = array();
	$showitems = ($range * 2)+1;

	if ( empty( $paged ) ) {
		$paged = 1;
	}

	if ( ! $pages ) {
		$pages = $wp_query->max_num_pages;
		if ( ! $pages ) {
			$pages = 1;
		}
	}

	if ( 1 != $pages ) {

		if ( $paged > 2 && $paged > $range+1 && $showitems < $pages ) {
			$parts[] = array(
				'href'		=> get_pagenum_link(1),
				'text'		=> '&laquo;',
				'active' 	=> false
			);
		}

		if ( $paged > 1 && $showitems < $pages ) {
			$parts[] = array(
				'href'		=> get_pagenum_link($paged-1),
				'text'		=> '&lsaquo;',
				'active' 	=> false
			);
		}

		for( $i = 1; $i <= $pages; $i++ ) {
			if ( ! ( $i >= $paged+$range+1 || $i <= $paged-$range-1 ) || $pages <= $showitems ) {
				$active = ( $paged == $i ) ? true : false;
				$parts[] = array(
					'href'		=> get_pagenum_link($i),
					'text'		=> $i,
					'active' 	=> $active
				);
			}
		}

		if ( $paged < $pages && $showitems < $pages ) {
			$parts[] = array(
				'href'		=> get_pagenum_link($paged + 1),
				'text'		=> '&rsaquo;',
				'active' 	=> false
			);
		}

		if ( $paged < $pages-1 && $paged+$range-1 < $pages && $showitems < $pages ) {
			$parts[] = array(
				'href'		=> get_pagenum_link($pages),
				'text'		=> '&raquo;',
				'active' 	=> false
			);
		}

	}
	return apply_filters( 'themeblvd_pagination_parts', $parts );
}

/**
 * Get class for buttons.
 *
 * @since 2.4.0
 *
 * @param string $color Color of button
 * @param string $size Size of button
 * @param bool $block Whether the button displays as block (true) or inline (false)
 * @return string $class HTML Class to be outputted into button <a> markup
 */
function themeblvd_get_button_class( $color = '', $size = '', $block = false ) {

	$class = 'btn';

	// Color
	if ( ! $color ) {
		$color = 'default';
	}

	if ( in_array( $color, apply_filters( 'themeblvd_bootstrap_btn_colors', array( 'default', 'primary', 'info', 'success', 'warning', 'danger' ) ) ) ) {
		$class .= sprintf( ' btn-%s', $color );
	} else if ( $color == 'custom' ) {
		$class .= ' tb-custom-button';
	} else {
		$class .= sprintf( ' %s', $color );
	}

	// Size
	switch ( $size ) {
		case 'mini' :
			$size = 'xs';
			break;
		case 'small' :
			$size = 'sm';
			break;
		case 'large' :
			$size = 'lg';
			break;
		case 'x-large' :
			$size = 'xl';
			break;
	}

	if ( in_array( $size, apply_filters( 'themeblvd_bootstrap_btn_sizes', array( 'xs', 'sm', 'lg', 'xl' ) ) ) ) {
		$class .= sprintf( ' btn-%s', $size );
	}

	// Block
	if ( $block ) {
		$class .= ' btn-block';
	}

    return apply_filters( 'themeblvd_get_button_class', $class, $color, $size );
}

/**
 * Filter applied on copyright text to allow
 * dynamic variables and shortcodes.
 *
 * @since 2.5.0
 *
 * @return string Text to filter
 */
function themeblvd_footer_copyright_helpers( $text ) {
	$text = str_replace( '%year%', date('Y'), $text );
	$text = str_replace( '%site_title%', get_bloginfo('site_title'), $text );
	return $text;
}

/**
 * Add to the post_class() function of WordPress.
 *
 * @since 2.5.0
 *
 * @param array $class Current classes
 * @return array $class Modified classes
 */
function themeblvd_post_class( $class ) {

	if ( ! themeblvd_get_att('doing_second_loop') && is_single( themeblvd_config('id') ) ) {
		$class[] = 'single';
	}

	return $class;
}

/**
 * Get the the length of time since a post was published
 * @props bbPress
 *
 * @since 2.5.0
 *
 * @param int $post_id ID of post
 * @return string $output Final time ago string
 */
function themeblvd_get_time_ago( $post_id = 0 ) {

	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$date = get_post_time('G', true, $post_id);

	$locals = apply_filters('themeblvd_time_ago_locals', array(
		'year' 		=> __('year', 'themeblvd_front'),
		'years' 	=> __('years', 'themeblvd_front'),
		'month' 	=> __('month', 'themeblvd_front'),
		'months' 	=> __('months', 'themeblvd_front'),
		'week' 		=> __('week', 'themeblvd_front'),
		'weeks' 	=> __('weeks', 'themeblvd_front'),
		'day' 		=> __('day', 'themeblvd_front'),
		'days' 		=> __('days', 'themeblvd_front'),
		'hour' 		=> __('hour', 'themeblvd_front'),
		'hours' 	=> __('hours', 'themeblvd_front'),
		'minute' 	=> __('minute', 'themeblvd_front'),
		'minutes' 	=> __('minutes', 'themeblvd_front'),
		'second' 	=> __('second', 'themeblvd_front'),
		'seconds' 	=> __('seconds', 'themeblvd_front'),
		'ago'		=> __('ago', 'themeblvd_front'),
		'error' 	=> __('sometime', 'themeblvd_front')
	));

	// Array of time period chunks
	$chunks = array(
		array( 60 * 60 * 24 * 365 , $locals['year'], $locals['years'] ),
		array( 60 * 60 * 24 * 30 , $locals['month'], $locals['months'] ),
		array( 60 * 60 * 24 * 7, $locals['week'], $locals['weeks'] ),
		array( 60 * 60 * 24 , $locals['day'], $locals['days'] ),
		array( 60 * 60 , $locals['hour'], $locals['hours'] ),
		array( 60 , $locals['minute'], $locals['minutes'] ),
		array( 1, $locals['second'], $locals['seconds'] )
	);

	if ( !is_numeric( $date ) ) {
		$time_chunks = explode( ':', str_replace( ' ', ':', $date ) );
		$date_chunks = explode( '-', str_replace( ' ', '-', $date ) );
		$date = gmmktime( (int)$time_chunks[1], (int)$time_chunks[2], (int)$time_chunks[3], (int)$date_chunks[1], (int)$date_chunks[2], (int)$date_chunks[0] );
	}

	$current_time = current_time( 'mysql', $gmt = 0 );
	$newer_date = strtotime( $current_time );

	// Difference in seconds
	$since = $newer_date - $date;

	// Something went wrong with date calculation and we ended up with a negative date.
	if ( 0 > $since ) {
		return $locals['error'];
	}

	// Step one: the first chunk
	for ( $i = 0, $j = count($chunks); $i < $j; $i++ ) {

		$seconds = $chunks[$i][0];

		// Finding the biggest chunk (if the chunk fits, break)
		if ( ( $count = floor($since / $seconds) ) != 0 ) {
			break;
		}
	}

	// Set output var
	$output = ( 1 == $count ) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2];

	if ( !(int)trim($output) ){
		$output = '0 ' . $locals['seconds'];
	}

	$output .= ' '.$locals['ago'];

	return $output;
}

/**
 * Get site's home url
 *
 * @since 2.5.0
 */
function themeblvd_get_home_url() {

	if ( function_exists('icl_get_home_url') ) {
        $url = icl_get_home_url();
    } else {
    	$url = get_home_url();
    }

    return apply_filters( 'themeblvd_home_url', trailingslashit($url) );
}

/**
 * Get search result post types
 *
 * @since 2.5.0
 */
function themeblvd_get_search_types() {

	// Because we need all the results, and not just
	// the current page, we have to get the search
	// results again.
	$results = new WP_Query('s='.get_search_query().'&posts_per_page=-1');

	// Build list of custom post types from results
	$types = array();

	if ( $results->have_posts() ) {
		while ( $results->have_posts() ) {

			$results->the_post();
			$type = get_post_type();

			if ( ! isset( $types[$type] ) ) {
				$post_type = get_post_type_object($type);
				$types[$type] = $post_type->labels->name;
			}
		}
	}

	wp_reset_postdata();

	return $types;
}

/**
 * Get the value for data-filter of an item in
 * filterable post display.
 *
 * @since 2.5.0
 *
 * @param string $tax Taxonomy we're sorting the showcase by
 * @param int $post_id ID of current post
 * @return string $value Value to get used in HTML, i.e. data-sort="$value"
 */
function themeblvd_get_filter_val( $tax = 'category', $post_id = 0 ) {

	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$value = '';
	$terms = get_the_terms($post_id, $tax);

	if ( $terms ) {
		foreach ( $terms as $term ) {
			$value .= sprintf('filter-%s ', $term->slug);
		}
	}

	return apply_filters( 'themeblvd_filter_val', trim($value), $tax, $post_id );
}

/**
 * Process any FontAwesome icons passed in as %icon%.
 *
 * @since 2.5.0
 *
 * @param string $str String to search
 * @return string $str Filtered original string
 */
function themeblvd_do_fa( $str ) {

	preg_match_all( '/\%(.*?)\%/', $str, $icons );

	if ( ! empty( $icons[0] ) ) {

		$total = count($icons[0]);
		$str = sprintf("<ul class=\"list-inline\">\n<li>%s</li>\n</ul>", $str);

		foreach ( $icons[0] as $key => $val ) {

			$html = apply_filters('themeblvd_do_fa_html', '<i class="fa fa-%s"></i>', $str);

			if ( $key > 0 ) {
				$html = "<li>\n".$html;
			}

			$str = str_replace($val, sprintf($html, $icons[1][$key]), $str);
		}
	}

	return $str;
}

/**
 * Determine if header displays any content.
 *
 * @since 2.5.0
 *
 * @param array $inc Elements to check for
 * @return bool Whether we've got any info to show in header
 */
function themeblvd_has_header_info( $inc = array('header_text', 'searchform', 'social_media', 'wpml', 'cart') ) {

	$return = false;

	if ( in_array('header_text', $inc) && themeblvd_get_option('header_text') ) {
		$return = true;
	}

	if ( in_array('searchform', $inc) && themeblvd_get_option('searchform') == 'show' ) {
		$return = true;
	}

	if ( in_array('social_media', $inc) && themeblvd_get_option('social_media') ) {
		$return = true;
	}

	if ( in_array('wpml', $inc) && themeblvd_installed('wpml') && themeblvd_supports('plugins', 'wpml') && get_option('tb_wpml_show_lang_switcher', '1') ) {
		$return = true;
	}

	if ( in_array('cart', $inc) && themeblvd_do_cart() ) {
		$return = true;
	}

	return apply_filters('themeblvd_has_header_info', $return, $inc);
}

/**
 * Whether to display theme shopping cart.
 *
 * By default this function will return FALSE;
 * however, if the theme supports WooCommerce and
 * WooCommerce is installed, this will be filtered
 * to TRUE.
 *
 * @since 2.5.0
 */
function themeblvd_do_cart() {
	return apply_filters('themeblvd_do_cart', false);
}

/**
 * Get the full URL of the current page
 *
 * @since 2.5.0
 */
function themeblvd_get_current_uri() {

	global $_SERVER;

	return apply_filters( 'themeblvd_get_current_uri', esc_url( home_url($_SERVER['REQUEST_URI']) ) );
}
