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

	if ( $option['face'] == 'google' || $option['face'] == 'typekit' ) {

		if ( $option['face'] == 'typekit' ) {
			$name = strtolower( str_replace(' ', '-', $option['typekit'] ) );
		} else {
			$name = $option['google'];
		}

		// Grab font face, making sure they didn't do the
		// super, sneaky trick of including font weight or type.
		$name = explode( ':', $name );

		// And also check for accidental space at end
		$name = esc_attr( trim( $name[0] ) );

		// Add the deafult font stack to the end of the
		// google font.
		$stack = '"'.$name.'", '.$stacks['default'];

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

	return apply_filters( 'themeblvd_font_size', esc_attr($size), $option );
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

	if ( ! empty( $option['style'] ) && in_array( $option['style'], array('italic', 'uppercase-italic', 'bold-italic' ) ) ) { // "bold-italic" only used if options are saved prior to framework 2.5
		$style = 'italic';
	}

	return apply_filters( 'themeblvd_font_style', esc_attr($style), $option );
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

	$weight = '';

	// @deprecated -- If option was saved after updating to
	// framework 2.5+, 'style' won't have one of these values.
	if ( ! empty( $option['style'] ) ) {
		if ( $option['style'] == 'bold' || $option['style'] == 'bold-italic' ) {
			$weight = 'bold';
		} else if ( $option['style'] == 'thin' ) {
			$weight = '300';
		}
	}

	if ( ! empty( $option['weight'] ) ){
		$weight = $option['weight'];
	}

	if ( ! $weight ) {
		$weight = '400';
	}

	return apply_filters( 'themeblvd_font_weight', esc_attr($weight), $option );
}

/**
 * Get font text-transform
 *
 * @since 2.5.0
 *
 * @param array $option Current option set by user for the font
 * @return string $transform CSS value for text-transform property
 */
function themeblvd_get_text_transform( $option ) {

	$tranform = 'none';

	if ( ! empty( $option['style'] ) && in_array( $option['style'], array('uppercase', 'uppercase-italic') ) ) {
		$tranform = 'uppercase';
	}

	return apply_filters( 'themeblvd_text_transform', $tranform, $option );
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

	// List of pages (but will allow standard posts and portfolio items also)
	if ( ! isset( $query ) && $source == 'pages' && ! empty( $options['pages'] ) ) {

		$options['pages'] = str_replace( ' ', '', $options['pages'] );
		$options['pages'] = explode( ',', $options['pages'] );

		$query = array(
			'post_type'	=> apply_filters('themeblvd_page_list_post_types', array('page', 'post', 'portfolio_item')),
			'post__in' 	=> array(),
			'orderby'	=> 'post__in'
		);

		if ( $options['pages'] ) {
			foreach ( $options['pages'] as $pagename ) {
				$query['post__in'][] = themeblvd_post_id_by_name( $pagename );
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

			} else if ( ! empty( $options['categories'] ) && empty( $options['categories']['all'] ) ) {

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
	if ( is_array( $query ) && empty( $query['posts_per_page'] ) && ! isset( $query['post__in'] ) ) {

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
 * Get the Bootstrap classes to be used for resposive
 * visibility. - xs, sm, md, lg
 *
 * @since 2.1.0
 *
 * @param array $hide Devices to be hidden on
 * @return var $class CSS class to use
 */
function themeblvd_responsive_visibility_class( $hide ) {

	if ( ! $hide || ! is_array($hide) ) {
		return false; // option wasn't passed in correctly
	}

	$class = '';

	foreach ( $hide as $key => $val ) {
		if ( $val && in_array($key, array('xs', 'sm', 'md', 'lg') ) ) {
			$class .= " hidden-{$key}";
		}
	}

	return apply_filters( 'themeblvd_responsive_visibility_class', trim($class), $hide );
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

	// Weirdness in calling wp_title() here and opening/closing
	// PHP is to get around silly quirck in theme-check plugin.
	?>
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<?php

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
	printf( '<script src="%s" type="text/javascript"></script>', esc_url( get_template_directory_uri() . '/framework/assets/js/html5.js' ) );
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
				animation: "<?php echo esc_attr($options['fx']); ?>",
				// pauseOnHover: true - This was replaced with a custom solution to work with other controls, see below with "pause_on_hover" option.
				<?php if ( $options['timeout'] ) : ?>
				slideshowSpeed: <?php echo esc_attr($options['timeout']); ?>000,
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
	return apply_filters( 'themeblvd_comment_list', array(
		'avatar_size' 		=> 60,
		'style' 			=> 'ul',
		'type' 				=> 'all',
		'reply_text' 		=> themeblvd_get_local('reply'),
		'login_text' 		=> themeblvd_get_local('login_text'),
		'callback' 			=> null,
		'reverse_top_level' => null,
		'reverse_children' 	=> false
	));
}

/**
 * Get Comment Form arguments for comments.php
 *
 * @since 2.2.0
 *
 * @return array $args Arguments to be passed into comment_form()
 */
function themeblvd_get_comment_form_args() {
	return apply_filters( 'themeblvd_comment_form', array(
		'comment_field'			=> '<p class="comment-form-comment"><label for="comment">'.themeblvd_get_local('comment').'</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		'title_reply'			=> themeblvd_get_local('title_reply'),
		'title_reply_to'		=> themeblvd_get_local('title_reply_to'),
		'cancel_reply_link'		=> themeblvd_get_local('cancel_reply_link'),
		'label_submit'			=> themeblvd_get_local('label_submit')
	));
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
 * Determine whether comments should have any presence
 * for current post. Must be within the loop.
 *
 * At first glance, you're probably wondering why this
 * would exist when you the WP user could just close
 * the comments. When the user closes the comments,
 * comments will still be present and in place of the
 * comment form, it will say that the comments are closed.
 *
 * However, in addition to that, this framework allows
 * the user to completely hide the comments presence.
 * So, this extends further up than simply having the
 * comments for a post closed.
 *
 * @since 2.2.0
 *
 * @return boolean $show Arguments to be passed into wp_list_comments()
 */
function themeblvd_show_comments() {

	global $post;

	$show = true;

	// If comments presence has been hidden for all single posts,
	// this inevitably extends to the comments presence for all posts.
	// This will extend to pages, as well, if comments are enabled.
	if ( themeblvd_get_option( 'single_comments', null, 'show' ) == 'hide' ) {
		$show = false;
	}

	// If comments presence has been hidden for this single post,
	// this extends to the comments presence everywhere for this post.
	if ( get_post_meta( $post->ID, '_tb_comments', true ) == 'hide' ) {
		$show = false;
	} else if ( get_post_meta( $post->ID, '_tb_comments', true ) == 'show' ) {
		$show = true;
	}

	// If the current post's type doesn't support comments, comments
	// presence should be hidden.
	if ( $show && ! post_type_supports( get_post_type(), 'comments' ) ) {
		$show = false;
	}

	// If comments are closed AND no comments exist, then it doesn't
	// make sense to have any comments presence.
	if ( $show && ! comments_open() && ! have_comments() ) {
		$show = false;
	}

	return apply_filters( 'themeblvd_show_comments', $show );
}

/**
 * Get comments title for comments.php -- In order to
 * maintain our filterable localization strings, this
 * function has to avoid using _n(), which probably
 * looks seemingly weird, at first glance.
 *
 * @since 2.5.2
 */
function themeblvd_get_comments_title() {

	$output = '';
	$num = get_comments_number();

	if ( $num == 0 ) {
		$output = themeblvd_get_local('no_comments');
	} else if ( $num == 1 ) {
		$output = sprintf( themeblvd_get_local('comments_title_single'), number_format_i18n( get_comments_number() ), '<span>'.esc_html( get_the_title() ).'</span>' );
	} else if ( $num >= 2 ) {
		$output = sprintf( themeblvd_get_local('comments_title_multiple'), number_format_i18n( get_comments_number() ), '<span>'.esc_html( get_the_title() ).'</span>' );
	}

	return apply_filters( 'themeblvd_comments_title', $output, $num );
}

/**
 * Display comments title
 *
 * @since 2.5.2
 */
function themeblvd_comments_title() {
	echo themeblvd_get_comments_title();
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
	$page_templates = apply_filters('themeblvd_private_page_support', array(
		'template_blog.php',
		'template_grid.php',
		'template_list.php',
		'template_showcase.php',
		'template_archives.php',
		'template_sitemap.php'
	));

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
		case 'xx-large' :
			$size = 'xxl';
			break;
		case 'xxx-large' :
			$size = 'xxxl';
	}

	if ( in_array( $size, apply_filters( 'themeblvd_bootstrap_btn_sizes', array( 'xs', 'sm', 'lg', 'xl', 'xxl', 'xxxl' ) ) ) ) {
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
	$text = str_replace( '%year%', esc_attr( date('Y') ), $text );
	$text = str_replace( '%site_title%', esc_html( get_bloginfo('site_title') ), $text );
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

	if ( is_page_template('template_naked.php') ) {
		$class[] = 'tb-naked-page';
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
		'year' 		=> __('year', 'jumpstart'),
		'years' 	=> __('years', 'jumpstart'),
		'month' 	=> __('month', 'jumpstart'),
		'months' 	=> __('months', 'jumpstart'),
		'week' 		=> __('week', 'jumpstart'),
		'weeks' 	=> __('weeks', 'jumpstart'),
		'day' 		=> __('day', 'jumpstart'),
		'days' 		=> __('days', 'jumpstart'),
		'hour' 		=> __('hour', 'jumpstart'),
		'hours' 	=> __('hours', 'jumpstart'),
		'minute' 	=> __('minute', 'jumpstart'),
		'minutes' 	=> __('minutes', 'jumpstart'),
		'second' 	=> __('second', 'jumpstart'),
		'seconds' 	=> __('seconds', 'jumpstart'),
		'ago'		=> __('ago', 'jumpstart'),
		'error' 	=> __('sometime', 'jumpstart')
	));

	// Array of time period chunks
	$chunks = array(
		array( 60 * 60 * 24 * 365, esc_html($locals['year']), esc_html($locals['years']) ),
		array( 60 * 60 * 24 * 30, esc_html($locals['month']), esc_html($locals['months']) ),
		array( 60 * 60 * 24 * 7, esc_html($locals['week']), esc_html($locals['weeks']) ),
		array( 60 * 60 * 24, esc_html($locals['day']), esc_html($locals['days']) ),
		array( 60 * 60, esc_html($locals['hour']), esc_html($locals['hours']) ),
		array( 60, esc_html($locals['minute']), esc_html($locals['minutes']) ),
		array( 1, esc_html($locals['second']), esc_html($locals['seconds']) )
	);

	if ( !is_numeric( $date ) ) {
		$time_chunks = explode( ':', str_replace( ' ', ':', $date ) );
		$date_chunks = explode( '-', str_replace( ' ', '-', $date ) );
		$date = gmmktime( (int)$time_chunks[1], (int)$time_chunks[2], (int)$time_chunks[3], (int)$date_chunks[1], (int)$date_chunks[2], (int)$date_chunks[0] );
	}

	$current_time = current_time( 'mysql', 1 );
	$newer_date = strtotime( $current_time );

	// Difference in seconds
	$since = $newer_date - $date;

	// Something went wrong with date calculation and we ended up with a negative date.
	if ( 0 > $since ) {
		return esc_html($locals['error']);
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
		$output = '0 ' . esc_html($locals['seconds']);
	}

	$output .= ' '.esc_html($locals['ago']);

	return $output;
}

/**
 * Get site's home url
 *
 * @since 2.5.0
 */
function themeblvd_get_home_url() {

	if ( function_exists('icl_get_home_url') ) {
        $url = trailingslashit( icl_get_home_url() );
    } else {
    	$url = home_url('/');
    }

    return apply_filters( 'themeblvd_home_url', $url );
}

/**
 * Display site's home url
 *
 * @since 2.5.2
 */
function themeblvd_home_url() {
	echo esc_url( themeblvd_get_home_url() );
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
			$slug = esc_attr( preg_replace('/[^a-zA-Z0-9._\-]/', '', $term->slug ) ); // Allow non-latin characters, and still work with jQuery
			$value .= sprintf('filter-%s ', $slug);
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

		$list = true;

		if ( substr_count(trim($str), "\n") ) {
			$list = false; // If text has more than one line, we won't make into an inline list
		}

		$total = count($icons[0]);

		if ( $list ) {
			$str = sprintf("<ul class=\"list-inline\">\n<li>%s</li>\n</ul>", $str);
		}

		foreach ( $icons[0] as $key => $val ) {

			$html = apply_filters('themeblvd_do_fa_html', '<i class="fa fa-fw fa-%s"></i>', $str);

			if ( $list && $key > 0 ) {
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
function themeblvd_has_header_info( $inc = array('header_text', 'searchform', 'social_media', 'wpml', 'cart', 'side_panel') ) {

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

	if ( in_array('side_panel', $inc) && themeblvd_do_side_panel() ) {
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
 * Whether to display theme language selector.
 *
 * By default this function will return FALSE;
 * however, if the theme supports WPML and WPML
 * is installed, this will be filtered to TRUE,
 * if necessary.
 *
 * @since 2.5.1
 */
function themeblvd_do_lang_selector() {
	return apply_filters('themeblvd_do_lang_selector', false);
}

/**
 * Whether to display floating search bar.
 *
 * @since 2.5.0
 */
function themeblvd_do_floating_search() {

	$do = false;

	if ( themeblvd_get_option('searchform') == 'show' ) {
		$do = true;
	}

	return apply_filters('themeblvd_do_floating_search', $do);
}

/**
 * Whether to display side panel.
 *
 * @since 2.6.0
 */
function themeblvd_do_side_panel() {

	$do = false;

	if ( themeblvd_supports('display', 'side_panel') ) {

		$primary = themeblvd_get_wp_nav_menu_args('side');
		$secondary = themeblvd_get_wp_nav_menu_args('side_sub');

		if ( has_nav_menu( $primary['theme_location'] ) || has_nav_menu( $secondary['theme_location'] ) ) {
			$do = true;
		}
	}

	return apply_filters('themeblvd_do_side_panel', $do);
}

/**
 * Whether to condense full-width content.
 *
 * @since 2.6.0
 */
function themeblvd_do_fw_narrow() {

	$do = false;

	if ( themeblvd_get_option('fw_narrow') && themeblvd_config('sidebar_layout') == 'full_width' ) {
		$do = true;
	}

	if ( themeblvd_get_option('fw_narrow') && is_page_template('template_builder.php') ) {
		$do = true;
	}

	if ( is_page_template('template_blank.php') || is_page_template('template_grid.php') || is_page_template('template_showcase.php') ) {
		$do = false;
	}

	if ( is_archive() && ( themeblvd_config('mode') == 'grid' || themeblvd_config('mode') == 'showcase' ) ) {
		$do = false;
	}

	if ( themeblvd_installed('woocommerce') && themeblvd_supports('plugins', 'woocommerce') ) {
		if ( is_shop() || is_cart() || is_checkout() ) {
			$do = false;
		}
	}

	return apply_filters('themeblvd_do_fw_narrow', $do);
}

/**
 * Whether to condense full-width content.
 *
 * @since 2.6.0
 */
function themeblvd_do_img_popout() {

	$do = false;

	if ( themeblvd_do_fw_narrow() && themeblvd_get_option('img_popout') ) {
		$do = true;
	}

	return apply_filters('themeblvd_do_img_popout', $do);
}

/**
 * Whether the URL returns an http 200 status.
 *
 * We use this primarily to determine if the URL to
 * some file we're trying to display is accessible,
 * like a video URL, for example.
 *
 * @since 2.6.0
 *
 * @var string $url The URL to some file, local or external
 * @return bool Whether the https status was 200
 */
function themeblvd_is_200( $url ) {

	$code = 0;
	$response = wp_remote_head( $url, array('timeout' => 5) );

	if ( ! is_wp_error( $response ) && isset( $response['response']['code'] ) ) {
		$code = $response['response']['code'];
	}

	return $code === 200;
}

/**
 * Return false
 *
 * @since 2.6.0
 */
function themeblvd_return_false() {
	return false;
}

/**
 * Return true
 *
 * @since 2.6.0
 */
function themeblvd_return_true() {
	return true;
}
