<?php
if ( !function_exists( 'themeblvd_contact_bar' ) ) : // pluggable for backwards compat
/**
 * Contact button bar
 *
 * @since 2.0.0
 *
 * @param array $buttons icons to use - array( 'twitter' => 'http://twitter.com/whatever', 'facebook' => 'http://facebook.com/whatever' )
 * @param string $style Style of buttons - dark, grey, light, color
 */
function themeblvd_contact_bar( $buttons = array(), $style = null ) {

	// Set up buttons
	if ( ! $buttons ) {
		$buttons = themeblvd_get_option( 'social_media' );
	}

	// If buttons haven't been sanitized return nothing
	if ( is_array( $buttons ) && isset( $buttons['includes'] ) ) {
		return null;
	}

	// Set up style
	if ( ! $style ) {
		$style = themeblvd_get_option( 'social_media_style', null, 'grey' );
	}

	// Social media sources
	$sources = themeblvd_get_social_media_sources();

	// Start output
	$output = null;
	if ( is_array( $buttons ) && ! empty ( $buttons ) ) {

		$output = '<div class="themeblvd-contact-bar">';
		$output .= '<ul class="social-media-'.$style.'">';

		foreach ( $buttons as $id => $url ) {

			// Link target
			$target = '_blank';
			if ( strpos( $url, 'mailto:' ) !== false ) {
				$target = '_self';
			}

			// Link Title
			$title = '';
			if ( isset( $sources[$id] ) ) {
				$title = $sources[$id];
			}

			$output .= sprintf( '<li><a href="%s" title="%s" class="%s" target="%s">%s</a></li>', $url, $title, $id, $target, $title );
		}

		$output .= '</ul>';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!-- .themeblvd-contact-bar (end) -->';
	}
	return apply_filters( 'themeblvd_contact_bar', $output );
}
endif;

if ( ! function_exists( 'themeblvd_button' ) ) : // pluggable for backwards compat
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
 * @param string $size Size of button - small, medium, default, or large
 * @param string $classes CSS classes to attach onto button
 * @param string $title Title for anchor tag
 * @param string $icon_before Optional fontawesome icon before text
 * @param string $icon_after Optional fontawesome icon after text
 * @param string $addon Anything to add onto the anchor tag
 * @param bool $block Whether the button displays as block (true) or inline (false)
 * @return $output string HTML to output for button
 */
function themeblvd_button( $text, $url, $color = 'default', $target = '_self', $size = null, $classes = null, $title = null, $icon_before = null, $icon_after = null, $addon = null, $block = false ) {

	// Classes for button
	$final_classes = 'btn';

	if ( ! $color ) {
		$color = 'default';
	}

	$final_classes = themeblvd_get_button_class( $color, $size, $block );

	if ( $classes ) {
		$final_classes .= ' '.$classes;
	}

	// Title param
	if ( ! $title ) {
		$title = strip_tags( $text );
	}

	// Add icon before text?
	if ( $icon_before ) {
		$text = '<i class="fa fa-'.$icon_before.'"></i> '.$text;
	}

	// Add icon after text?
	if ( $icon_after ) {
		$text .= ' <i class="fa fa-'.$icon_after.'"></i>';
	}

	// Optional addon to anchor
	if ( $addon ) {
		$addon = ' '.$addon;
	}

	// Finalize button
	if ( $target == 'lightbox' ) {

		// Button linking to lightbox
		$args = array(
			'item' 	=> $text,
			'link' 	=> $url,
			'title' => $title,
			'class' => $final_classes,
			'addon'	=> $addon
		);

		$button = themeblvd_get_link_to_lightbox( $args );


	} else {

		// Standard button
		$button = sprintf( '<a href="%s" title="%s" class="%s" target="%s"%s>%s</a>', $url, $title, $final_classes, $target, $addon, $text );

	}

	// Return final button
	return apply_filters( 'themeblvd_button', $button, $text, $url, $color, $target, $size, $classes, $title, $icon_before, $icon_after, $addon, $block );
}
endif;

if ( !function_exists( 'themeblvd_archive_title' ) ) :
/**
 * Display title for archive pages
 *
 * @since 2.0.0
 */
function themeblvd_archive_title() {

	global $post;
	global $posts;

    if ( $posts ) {
    	$post = $posts[0]; // Hack. Set $post so that the_date() works.
    }

    if ( is_search() ) {

		// Search Results
		echo themeblvd_get_local('crumb_search').' "'.get_search_query().'"';

    } else if ( is_category() ) {

    	// If this is a category archive
    	// echo themeblvd_get_local( 'category' ).': ';
    	single_cat_title();

    } else if ( is_tag() ) {

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
endif;

/**
 * Get pagination
 *
 * @since 2.3.0
 *
 * @param int $pages Optional number of pages
 * @param int $range Optional range for paginated buttons, helpful for many pages
 * @return string $output Final HTML markup for pagination
 */
function themeblvd_get_pagination( $pages = 0, $range = 2 ) {

	$pass = paginate_links(); // Sub

	// Get pagination parts
	$parts = themeblvd_get_pagination_parts( $pages, $range );

	// Pagination markup
	$output = '';
	if ( $parts ) {
		foreach ( $parts as $part ) {
			$class = 'btn btn-default';
			if ( $part['active'] ) {
				$class .= ' active';
			}
			$output .= sprintf('<a class="%s" href="%s">%s</a>', $class, $part['href'], $part['text'] );
		}
	}

	// Wrapping markup
	$wrap  = '<div class="pagination-wrap">';
	$wrap .= '	<div class="pagination">';
	$wrap .= '		<div class="btn-group clearfix">';
	$wrap .= '			%s';
	$wrap .= '		</div>';
	$wrap .= '	</div>';
	$wrap .= '</div>';

	$output = sprintf( $wrap, $output );

	return apply_filters( 'themeblvd_pagination', $output, $parts );
}

/**
 * Pagination
 *
 * @since 2.0.0
 *
 * @param int $pages Optional number of pages
 * @param int $range Optional range for paginated buttons, helpful for many pages
 */
function themeblvd_pagination( $pages = 0, $range = 2 ) {
	echo themeblvd_get_pagination( $pages, $range );
}

/**
 * Get breadcrumb trail formatted for being displayed.
 *
 * @since 2.2.1
 */
function themeblvd_get_breadcrumbs_trail() {

	// Filterable attributes
	$atts = array(
		'delimiter'		=> '', // Previously <span class="divider">/</span> w/Bootstrap 2.x, now inserted w/CSS.
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
	if ( $parts ) {

        $trail .= '<ul class="breadcrumb">';

        foreach ( $parts as $part ) {

			$crumb = $part['text'];

			if ( ! empty( $part['link'] ) ) {
				$crumb = '<a href="'.$part['link'].'" class="'.$part['type'].'-link" title="'.$crumb.'">'.$crumb.'</a>';
			}

			if ( $total == $count ) {
				$crumb = '<li class="active">'.$atts['before'].$crumb.$atts['after'].'</li>';
			} else {
				$crumb = '<li>'.$crumb.$atts['delimiter'].'</li>';
			}

			$trail .= $crumb;
			$count++;
		}

        $trail .= '</ul><!-- .breadcrumb (end) -->';

	}
	return apply_filters( 'themeblvd_breadcrumbs_trail', $trail, $atts, $parts );
}

/**
 * Get default meta for blogroll.
 *
 * @since 2.3.0
 */
function themeblvd_get_meta( $sep = '' ) {

	// Separator
	if ( ! $sep ) {
		$sep = apply_filters( 'themeblvd_meta_separator', '<span class="sep"> / </span>' );
	}

	// Start output
	$output  = '<div class="entry-meta">';

	// Time
	$time = sprintf('<time class="entry-date updated" datetime="%s"><i class="icon-calendar"></i> %s</time>', get_the_time('c'), get_the_time( get_option( 'date_format' ) ) );
	$output .= $time;

	// Author
	$author_url = get_author_posts_url( get_the_author_meta('ID') );
	$author_title = sprintf( __( 'View all posts by %s', 'themeblvd_frontend' ), get_the_author() );
	$author = sprintf( '<span class="author vcard"><i class="icon-user"></i> <a class="url fn n" href="%s" title="%s" rel="author">%s</a></span>', $author_url, $author_title, get_the_author() );
	$output .= $sep;
	$output .= $author;

	// Category
	$category = '';
	if ( has_category() ) {
		$category = sprintf( '<span class="category"><i class="icon-reorder"></i> %s</span>', get_the_category_list(', ') );
		$output .= $sep;
		$output .= $category;
	}

	// Comments
	$comments = '';

	if( comments_open() ) {

		$output .= $sep;
		$comments .= '<span class="comments-link">';

		ob_start();
		comments_popup_link( '<span class="leave-reply">'.themeblvd_get_local( 'no_comments' ).'</span>', '1 '.themeblvd_get_local( 'comment' ), '% '.themeblvd_get_local( 'comments' ) );
		$comment_link = ob_get_clean();

		$comments .= sprintf( '<i class="icon-comment"></i> %s', $comment_link, $sep );
		$comments .= '</span>';

		$output .= $comments;

	}

	$output .= '</div><!-- .entry-meta -->';

	return apply_filters( 'themeblvd_meta', $output, $time, $author, $category, $comments, $sep );
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
function themeblvd_get_twitter( $count, $username, $time = 'yes', $exclude_replies = 'yes' ) {
	themeblvd_deprecated_function( __FUNCTION__, '2.3.0', null, __( 'Twitter functionality is no longer built into the Theme Blvd framework. Use Theme Blvd "Tweeple" plugin found in the WordPress plugin repository.', 'themeblvd' ) );
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
function themeblvd_twitter( $count, $username, $time = 'yes', $exclude_replies = 'yes' ) {
	themeblvd_deprecated_function( __FUNCTION__, '2.3.0', null, __( 'Twitter functionality is no longer built into the Theme Blvd framework. Use Theme Blvd "Tweeple" plugin found in the WordPress plugin repository.', 'themeblvd' ) );
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
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = '';

		for( $i = 0; $i < $depth; $i++ ) {
			$indent .= '-';
		}

		if ( $indent ) {
			$indent .= ' ';
		}

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
function themeblvd_nav_menu_select( $location ) {
	$select_menu = wp_nav_menu( apply_filters( 'themeblvd_nav_menu_select_args', array(
		'theme_location'	=> $location,
		'container'			=> false,
		'items_wrap'		=> '<form class="responsive-nav"><select class="tb-jump-menu"><option value="">'.themeblvd_get_local('navigation').'</option>%3$s</select></form>',
		'echo' 				=> false,
		'walker' 			=> new ThemeBlvd_Select_Menu_Walker
	)));
	return apply_filters('themeblvd_nav_menu_select', $select_menu, $location );
}

/**
 * Get Simple Contact module (primary meant for simple contact widget)
 *
 * @since 2.0.3
 *
 * @param array $args Arguments to be used for the elements
 * @return $module HTML to output
 */
function themeblvd_get_simple_contact( $args ) {

	// Setup icon links
	$icons = array();
	for ( $i = 1; $i <= 6; $i++ ) {
		if ( ! empty( $args['link_'.$i.'_url'] ) ) {
			$icons[$args['link_'.$i.'_icon']] = $args['link_'.$i.'_url'];
		}
	}

	// Start Output
	$module = '<ul class="simple-contact">';

	// Phone #1
	if ( ! empty( $args['phone_1'] ) ) {
		$module .= sprintf( '<li class="phone">%s</li>', $args['phone_1'] );
	}

	// Phone #2
	if ( ! empty( $args['phone_2'] ) ) {
		$module .= sprintf( '<li class="phone">%s</li>', $args['phone_2'] );
	}

	// Email #1
	if ( ! empty( $args['email_1'] ) ) {
		$module .= sprintf( '<li class="email"><a href="mailto:%s">%s</a></li>', $args['email_1'], $args['email_1'] );
	}

	// Email #2
	if ( ! empty( $args['email_2'] ) ) {
		$module .= sprintf( '<li class="email"><a href="mailto:%s">%s</a></li>', $args['email_2'], $args['email_2'] );
	}

	// Contact Page
	if ( ! empty( $args['contact'] ) ) {
		$module .= sprintf( '<li class="contact"><a href="%s">%s</a></li>', $args['contact'], themeblvd_get_local( 'contact_us' ) );
	}

	// Skype
	if ( ! empty( $args['skype'] ) ) {
		$module .= sprintf( '<li class="skype">%s</li>', $args['skype'] );
	}

	// Social Icons
	if ( ! empty( $icons ) ) {

		// Social media sources
		$sources = themeblvd_get_social_media_sources();

		$module .= '<li class="link"><ul class="icons">';

		foreach ( $icons as $icon => $url ) {

			// Link title
			$title = '';
			if ( isset( $sources[$icon] ) ) {
				$title = $sources[$icon];
			}

			$module .= sprintf( '<li class="%s"><a href="%s" target="_blank" title="%s">%s</a></li>', $icon, $url, $title, $title );
		}

		$module .= '</ul></li>';
	}
	$module .= '</ul>';

	return apply_filters( 'themeblvd_simple_contact', $module, $args );
}

/**
 * Display Simple Contact module
 *
 * @since 2.1.0
 *
 * @param array $args Arguments to be used for the elements
 */
function themeblvd_simple_contact( $args ) {
	echo themeblvd_get_simple_contact( $args );
}

if ( !function_exists( 'themeblvd_get_mini_post_list' ) ) : // pluggable for backwards compat
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
function themeblvd_get_mini_post_list( $query = '', $thumb = 'smaller', $meta = true ) {

	global $post;
	global $_wp_additional_image_sizes;

	$output = '';

	// CSS classes
	$classes = '';
	if ( ! $thumb ) {
		$classes .= 'hide-thumbs';
	} else {
		$classes .= $thumb.'-thumbs';
	}

	if ( ! $meta ) {
		$classes .= ' hide-meta';
	}

	// Thumb size
	$thumb_size = apply_filters( 'themeblvd_mini_post_list_thumb_size', 'square_'.$thumb, $thumb, $query, $meta );
	$thumb_width = $_wp_additional_image_sizes[$thumb_size]['width'];

	// Get posts
	$posts = get_posts( html_entity_decode( $query ) );

	// Start output
	if ( $posts ) {

		$output  = '<div class="themeblvd-mini-post-list">';
		$output .= '<ul class="'.$classes.'">';

		foreach ( $posts as $post ) {

			setup_postdata( $post );
			$image = '';

			// Setup post thumbnail if user wants them to show
			if ( $thumb ) {

				$image = themeblvd_get_post_thumbnail( 'primary', $thumb_size );

				// If post thumbnail isn't set, pull default thumbnail
				// based on post format. If theme doesn't support post
				// formats, format will always be "standard".
				if ( ! $image ) {

					$default_img_directory = apply_filters( 'themeblvd_thumbnail_directory', get_template_directory_uri() . '/framework/assets/images/thumbs/2x/' );

					$post_format = get_post_format();
					if ( ! $post_format ) {
						$post_format = 'standard';
					}

					$image .= '<div class="featured-image-wrapper attachment-'.$thumb_size.' thumbnail">';
					$image .= '<div class="featured-image">';
					$image .= '<div class="featured-image-inner">';
					$image .= sprintf( '<img src="%s.png" width="%s" class="wp-post-image" />', $default_img_directory.$thumb.'_'.$post_format, $thumb_width );
					$image .= '</div><!-- .featured-image-inner (end) -->';
					$image .= '</div><!-- .featured-image (end) -->';
					$image .= '</div><!-- .featured-image-wrapper (end) -->';
				}
			}

			$output .= '<li>';

			if ( $image ) {
				$output .= $image;
			}

			$output .= '<div class="mini-post-list-content">';
			$output .= sprintf( '<h4>%s</h4>', themeblvd_get_the_title( $post->ID, true ) );

			if ( $meta ) {
				$output .= sprintf('<span class="mini-meta">%s</span>', get_the_time( get_option('date_format') ) );
			}

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
endif;

if ( !function_exists( 'themeblvd_mini_post_list' ) ) :
/**
 * Display Mini Post List
 *
 * @since 2.1.0
 *
 * @param array $options Options for many post list
 */
function themeblvd_mini_post_list( $options ) {
	echo themeblvd_get_mini_post_list( $options );
}
endif;

if ( !function_exists( 'themeblvd_get_mini_post_grid' ) ) : // pluggable for backwards compat
/**
 * Get Mini Post Grid
 *
 * @since 2.1.0
 *
 * @param array $options Options for many post grid
 * @return string $output HTML to output
 */
function themeblvd_get_mini_post_grid( $query = '', $align = 'left', $thumb = 'smaller', $gallery = '' ) {

	global $post;
	global $_wp_additional_image_sizes;

	$output = '';

	// CSS classes
	$classes = $thumb.'-thumbs';
	$classes .= ' grid-align-'.$align;
	if ( $gallery ) {
		$classes .= ' gallery-override themeblvd-gallery';
	}

	// Thumb size
	$thumb_size = apply_filters( 'themeblvd_mini_post_grid_thumb_size', 'square_'.$thumb, $thumb, $query );
	$thumb_width = $_wp_additional_image_sizes[$thumb_size]['width'];

	// Check for gallery override
	$gallery_link = '';

	if ( $gallery ) {

		$pattern = get_shortcode_regex();

		if ( preg_match( "/$pattern/s", $gallery, $match ) && 'gallery' == $match[2] ) {

			$atts = shortcode_parse_atts( $match[3] );

			if( isset( $atts['link'] ) && 'file' == $atts['link'] ) {
				$gallery_link = 'file';
			}

			if ( ! empty( $atts['ids'] ) ) {
				$query = array(
					'post_type'			=> 'attachment',
					'post__in' 			=> explode( ',', $atts['ids'] ),
					'posts_per_page' 	=> -1
				);
			}
		}
	}

	// Format query
	if ( ! is_array( $query ) ) {
		$query = html_entity_decode( $query );
	}

	// Get posts
	$posts = get_posts( $query );

	// Start output
	if ( $posts ) {

		$output  = '<div class="themeblvd-mini-post-grid">';
		$output .= '<ul class="'.$classes.'">';

		foreach ( $posts as $post ) {

			setup_postdata( $post );

			$output .= '<li>';

			if ( $gallery ) {

				// Gallery image output to simulate featured images
				$thumbnail = wp_get_attachment_image_src( $post->ID, apply_filters( 'themeblvd_mini_post_grid_thumb_size', 'square_'.$thumb, $thumb, $query, $align, $gallery ) );
				$output .= '<div class="featured-image-wrapper">';
				$output .= '<div class="featured-image">';
				$output .= '<div class="featured-image-inner">';

				if ( 'file' == $gallery_link ) {

					$image = wp_get_attachment_image_src( $post->ID, 'full' );
					$item = sprintf( '<img src="%s" alt="%s" />', $thumbnail[0], $post->post_title );

					$args = array(
						'item'		=> $item.themeblvd_get_image_overlay(),
						'link'		=> $image[0],
						'title'		=> $post->post_title,
						'class'		=> 'image thumbnail',
						'gallery' 	=> true
					);

					$output .= themeblvd_get_link_to_lightbox( $args );

				} else {

					$output .= sprintf( '<a href="%s" title="%s" class="image thumbnail">', get_permalink($post->ID), $post->post_title, $gallery );
					$output .= sprintf( '<img src="%s" alt="%s" />', $thumbnail[0], $post->post_title );
					$output .= themeblvd_get_image_overlay();
					$output .= '</a>';

				}

				$output .= '</div><!-- .featured-image-inner (end) -->';
				$output .= '</div><!-- .featured-image (end) -->';
				$output .= '</div><!-- .featured-image-wrapper (end) -->';

			} else {

				// Standard featured image output
				$image = themeblvd_get_post_thumbnail( 'primary', $thumb_size );

				// If post thumbnail isn't set, pull default thumbnail
				// based on post format. If theme doesn't support post
				// formats, format will always be "standard".
				if ( ! $image ) {

					$default_img_directory = apply_filters( 'themeblvd_thumbnail_directory', get_template_directory_uri() . '/framework/assets/images/thumbs/2x/' );

					$post_format = get_post_format();
					if ( ! $post_format ) {
						$post_format = 'standard';
					}

					$image .= '<div class="featured-image-wrapper attachment-'.$thumb_size.' thumbnail">';
					$image .= '<div class="featured-image">';
					$image .= '<div class="featured-image-inner">';
					$image .= sprintf( '<a href="%s" title="%s">', get_permalink(), get_the_title() );
					$image .= sprintf( '<img src="%s.png" width="%s" class="wp-post-image" />', $default_img_directory.$thumb.'_'.$post_format, $thumb_width );
					$image .= '</a>';
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
endif;

if ( !function_exists( 'themeblvd_mini_post_grid' ) ) :
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
endif;

/**
 * Get the_title() taking into account if it should
 * wrapped in a link.
 *
 * @since 2.3.0
 *
 * @param int $post_id Can feed in a post ID if outside the loop.
 * @param bool $foce_link Whether to force the title to link.
 * @return string $title The title of the post
 */
function themeblvd_get_the_title( $post_id = 0, $force_link = false ) {

	$url = '';
	$title = get_the_title( $post_id );

	// If "link" post format, get URL from start of content.
	if ( has_post_format( 'link', $post_id ) ) {
		$url = get_content_url( get_the_content( $post_id ) );
	}

	// If not a single post or page, get permalink for URL.
	if ( ! $url && ( ! themeblvd_was( 'single' ) || $force_link ) ) {
		$url = get_permalink( $post_id );
	}

	// Wrap title in link if there's a URL.
	if ( $url ) {
		$title = sprintf('<a href="%s" title="%s">%s</a>', esc_url( $url ), esc_attr( the_title_attribute('echo=0') ), $title );
	}

	return apply_filters( 'themeblvd_the_title', $title, $url );
}

if ( !function_exists( 'themeblvd_the_title' ) ) :
/**
 * Display the_title() taking into account if it should
 * wrapped in a link.
 *
 * @since 2.3.0
 *
 * @param int $post_id Can feed in a post ID if outside the loop.
 * @param bool $foce_link Whether to force the title to link.
 */
function themeblvd_the_title( $post_id = 0, $force_link = false ) {
	echo themeblvd_get_the_title( $post_id, $force_link );
}
endif;