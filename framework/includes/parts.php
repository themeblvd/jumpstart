<?php
if ( !function_exists( 'themeblvd_contact_bar' ) ) :
/**
 * Contact button bar
 *
 * @since 2.0.0
 *
 * @param array $buttons icons to use
 * @param array $args Any options for contact bar
 */
function themeblvd_contact_bar( $buttons = array(), $args = array() ) {
	echo themeblvd_get_contact_bar( $buttons, $args );
}
endif;

/**
 * Get contact button bar
 *
 * $buttons array should be formatted like this:
 * array(
 *		array(
 * 			'icon' 		=> 'facebook',
 * 			'url' 		=> 'http://facebook.com/example',
 * 			'label' 	=> 'Facebook',
 * 			'target' 	=> '_blank'
 *		),
 *		array(
 * 			'icon' 		=> 'twitter',
 * 			'url' 		=> 'http://twitter.com/example',
 * 			'label' 	=> 'Twitter',
 * 			'target' 	=> '_blank'
 *		)
 * )
 *
 * @since 2.5.0
 *
 * @param array $buttons icons to use
 * @param array $args Any options for contact bar
 * @return string Output for contact bar
 */
function themeblvd_get_contact_bar( $buttons = array(), $args = array() ) {

	// Set up buttons
	if ( ! $buttons ) {
		$buttons = themeblvd_get_option( 'social_media' );
	}

	// Setup arguments
	$defaults = apply_filters('themeblvd_contact_bar_defaults', array(
		'style'		=> themeblvd_get_option( 'social_media_style', null, 'grey' ),	// color, grey, light, dark
		'tooltip'	=> 'top',
		'class'		=> ''													// top, right, left, bottom, false
	));
	$args = wp_parse_args( $args, $defaults );

	// Start output
	$output = '';

	if ( $buttons && is_array($buttons) ) {

		$class = 'themeblvd-contact-bar '.$args['style'];

		if ( $args['class'] ) {
			$class .= ' '.$args['class'];
		}

		$output .= '<div class="'.$class.'">';
		$output .= '<ul class="social-media">';

		foreach ( $buttons as $button ) {

			// Link class
			$class = $button['icon'];
			if ( $args['style'] != 'color' ) { // Note: "color" means to use colored image icons; otherwise, we use icon font.
				$class .= ' tb-icon tb-icon-'.$class;
			}
			if ( $args['tooltip'] && $args['tooltip'] != 'disable' ) {
				$class .= ' tb-tooltip';
			}

			// Link Title
			$title = '';
			if ( ! empty( $button['label']) ) {
				$title = $button['label'];
			}

			$output .= sprintf( '<li><a href="%s" title="%s" class="%s" target="%s" data-toggle="tooltip" data-placement="%s"></a></li>', $button['url'], $title, $class, $button['target'], $args['tooltip'] );
		}

		$output .= '</ul>';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!-- .themeblvd-contact-bar (end) -->';
	}
	return apply_filters( 'themeblvd_contact_bar', $output, $buttons, $args );
}

if ( ! function_exists( 'themeblvd_button' ) ) : // pluggable for backwards compat, use themeblvd_button filter instead
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
	$time = sprintf('<time class="entry-date updated" datetime="%s"><i class="fa fa-calendar"></i> %s</time>', get_the_time('c'), get_the_time( get_option( 'date_format' ) ) );
	$output .= $time;

	// Author
	$author_url = get_author_posts_url( get_the_author_meta('ID') );
	$author_title = sprintf( __( 'View all posts by %s', 'themeblvd_frontend' ), get_the_author() );
	$author = sprintf( '<span class="author vcard"><i class="fa fa-user"></i> <a class="url fn n" href="%s" title="%s" rel="author">%s</a></span>', $author_url, $author_title, get_the_author() );
	$output .= $sep;
	$output .= $author;

	// Category
	$category = '';
	if ( has_category() ) {
		$category = sprintf( '<span class="category"><i class="fa fa-bars"></i> %s</span>', get_the_category_list(', ') );
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

		$comments .= sprintf( '<i class="fa fa-comment"></i> %s', $comment_link, $sep );
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
		'items_wrap'		=> '<form class="responsive-nav"><select class="tb-jump-menu form-control"><option value="">'.themeblvd_get_local('navigation').'</option>%3$s</select></form>',
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

					$image .= '<div class="featured-image-wrapper attachment-'.$thumb_size.'">';
					$image .= '<div class="featured-image">';
					$image .= '<div class="featured-image-inner">';
					$image .= '<div class="thumbnail">';
					$image .= sprintf( '<img src="%s.png" width="%s" class="wp-post-image" />', $default_img_directory.$thumb.'_'.$post_format, $thumb_width );
					$image .= '</div><!-- .thumbnail (end) -->';
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
					'orderby'           => 'post__in',
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

					$image .= '<div class="featured-image-wrapper attachment-'.$thumb_size.'">';
					$image .= '<div class="featured-image">';
					$image .= '<div class="featured-image-inner">';
					$image .= sprintf( '<a href="%s" title="%s" class="thumbnail">', get_permalink(), get_the_title() );
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
		$url = themeblvd_get_content_url( get_the_content( $post_id ) );
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

/**
 * Get blockquote formatted correctly for Bootstrap
 *
 * @since 2.4.0
 *
 * @param array $args Arguments for blockquote.
 */
function themeblvd_get_blockquote( $args ) {

	$defaults = array(
		'quote'			=> '',
		'source' 		=> '',		// Source of quote
		'source_link'	=> '',		// URL to link source to
		'align'			=> '',		// How to align blockquote - left, right
		'max_width'		=> '',		// Meant to be used with align left/right - 300px, 50%, etc
		'reverse'		=> 'false',	// Whether to add "blockquote-reverse" Bootstrap class, which will align text to right; this is different than pull-right, which will float.
		'class'			=> '' 		// Any additional CSS classes
	);
	$args = wp_parse_args( $args, $defaults );

	// CSS classes
	$class = 'tb-blockquote';

	if ( $args['reverse'] == 'true' ) {
		$class .= ' blockquote-reverse';
	}

	if ( $args['align'] ) {
		if ( 'left' == $args['align'] ) {
			$class .= ' pull-left';
		} else if ( 'right' == $args['align'] ) {
			$class .= ' pull-right';
		}
	}

	if ( $args['class'] ) {
		$class .= ' '.$args['class'];
	}

	// Max width
	$style = '';

	if ( $args['max_width'] ) {

		if ( false === strpos( $args['max_width'], 'px' ) && false === strpos( $args['max_width'], '%' ) ) {
			$args['max_width'] = $args['max_width'].'px';
		}

		$style = sprintf('max-width: %s;', $args['max_width'] );
	}

	// Quote
	$quote = $args['quote'];

	if ( false === strpos( $quote, '<p>' ) ) {
		$quote = wpautop( $quote );
	}

	// Source
	$source = '';

	if ( $args['source'] ) {

		$source = $args['source'];

		if ( $args['source_link'] ) {
			$source = sprintf( '<a href="%s" title="%s" target="_blank">%s</a>', $args['source_link'], $source, $source );
		}

		$source = sprintf( '<small><cite>%s</cite></small>', $source );

		$quote .= $source;

	}

	// Output
	$output = sprintf( '<blockquote class="%s" style="%s">%s</blockquote>', $class, $style, $quote );

	return apply_filters( 'themeblvd_blockquote', $output, $args, $quote, $source, $class, $style );
}

/**
 * Display blockquote formatted correctly for Bootstrap
 *
 * @since 2.4.0
 *
 * @param array $args Arguments for blockquote.
 */
function themeblvd_blockquote( $args ) {
	echo themeblvd_get_blockquote( $args );
}

/**
 * Get panel formatted correctly for Bootstrap
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for panel
 * @param string $content content for panel, optional
 * @return string $output Output for panel
 */
function themeblvd_get_panel( $args, $content = '' ) {

	$defaults = array(
        'style'         => 'default',   // Style of panel - primary, success, info, warning, danger
        'title'         => '',          // Header for panel
        'footer'        => '',          // Footer for panel
        'text_align'    => 'left',      // How to align text - left, right, center
        'align'         => '',          // How to align panel - left, right
        'max_width'     => '',          // Meant to be used with align left/right - 300px, 50%, etc
        'class'         => '',          // Any additional CSS classes
        'wpautop'       => 'true'       // Whether to apply wpautop on content
    );
    $args = wp_parse_args( $args, $defaults );

    // CSS classes
    $class = sprintf( 'panel panel-%s text-%s', $args['style'], $args['text_align'] );

    if ( $args['class'] ) {
        $class .= ' '.$args['class'];
    }

    // How are we getting the content?
    if ( ! $content && ! empty( $args['content'] ) ) {
    	$content = $args['content'];
    }

    // WP auto?
    if ( $args['wpautop'] == 'true' || $args['wpautop'] == '1' ) {
        $content = themeblvd_get_content( $content );
    } else {
    	$content = do_shortcode( $content );
    }

    // Construct intial panel
    $output = sprintf( '<div class="%s">', $class );

    if ( $args['title'] ) {
        $output .= sprintf( '<div class="panel-heading"><h3 class="panel-title">%s</h3></div>', $args['title'] );
    }

    $output .= sprintf( '<div class="panel-body text-%s">%s</div>', apply_filters( 'themeblvd_panel_text', 'dark' ), do_shortcode($content) );

    if ( $args['footer'] ) {
        $output .= sprintf( '<div class="panel-footer">%s</div>', $args['footer'] );
    }

    $output .= '</div><!-- .panel (end) -->';

    return apply_filters( 'themeblvd_panel', $output );
}

/**
 * Display panel formatted correctly for Bootstrap
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for panel
 * @param string $content content for panel, optional
 */
function themeblvd_panel( $args, $content = '' ) {
	echo themeblvd_get_panel( $args, $content );
}

/**
 * Get toggle formatted correctly for Bootstrap,
 * using the panel.
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for alert
 * @param string $content content for alert, optional
 * @return string $output Output for alert
 */
function themeblvd_get_toggle( $args ) {

	$defaults = array(
        'title'       	=> '',   	// Title of toggle
        'content'       => '',   	// Hidden content of toggle
        'wpautop'       => 'true',  // Whether to apply wpautop on content
        'open'       	=> 'false', // Whether toggle is initially open
        'class'         => '',		// Any additional CSS classes
    	'last'			=> false	// Whether this is the last toggle of a group; this only applies if it's not part of an accordion
    );
    $args = wp_parse_args( $args, $defaults );

    // Bootstrap color
	$color = apply_filters( 'themeblvd_toggle_color', 'default' );

    // CSS classes
    $class = sprintf( 'tb-panel panel panel-%s', $color );

    if ( $args['class'] ) {
        $class .= ' '.$args['class'];
    }

    if ( $args['last'] ) {
        $class .= '  panel-last';
    }

    // WP auto?
    if ( $args['wpautop'] == 'true' || $args['wpautop'] == '1' ) {
        $content = themeblvd_get_content( $args['content'] );
    } else {
    	$content = do_shortcode( $args['content'] );
    }

    // Is toggle open?
    $state = 'panel-collapse collapse';
    $icon = 'plus-circle';
    if( $args['open'] == 'true' || $args['open'] == '1' ) {
        $state .= ' in';
        $icon = 'minus-circle';
    }

    // Content text color
	$text = apply_filters( 'themeblvd_toggle_body_text', 'dark' );

	// Individual toggle ID (NOT the Accordion ID)
	$toggle_id = uniqid( 'toggle_'.rand() );

    // Bootstrap 3 output
    $output = '
        <div class="'.$class.'">
            <div class="panel-heading">
                <a class="panel-title" data-toggle="collapse" data-parent="" href="#'.$toggle_id.'">
                    <i class="fa fa-'.$icon.' switch-me"></i> '.$args['title'].'
                </a>
            </div><!-- .panel-heading (end) -->
            <div id="'.$toggle_id.'" class="'.$state.'">
                <div class="panel-body text-'.$text.'">
                    '.$content.'
                </div><!-- .panel-body (end) -->
            </div><!-- .panel-collapse (end) -->
        </div><!-- .panel (end) -->';

    return apply_filters( 'themeblvd_toggle', $output );
}

/**
 * Display toggle formatted correctly for Bootstrap,
 * using the panel.
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for panel
 * @param string $content content for panel, optional
 */
function themeblvd_toggle( $args, $content = '' ) {
	echo themeblvd_get_toggle( $args, $content );
}

/**
 * Display alert formatted correctly for Bootstrap
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for alert
 * @param string $content content for alert, optional
 * @return string $output Output for alert
 */
function themeblvd_get_alert( $args, $content = '' ) {

	$defaults = array(
        'style'         => 'default',   // Style of panel - primary, success, info, warning, danger
        'class'         => '',          // Any additional CSS classes
        'wpautop'       => 'true'       // Whether to apply wpautop on content
    );
    $args = wp_parse_args( $args, $defaults );

    // CSS classes
    $class = sprintf( 'alert alert-%s', $args['style'] );

    if ( $args['class'] ) {
        $class .= ' '.$args['class'];
    }

    // How are we getting the content?
    if ( ! $content && ! empty( $args['content'] ) ) {
    	$content = $args['content'];
    }

    // WP auto?
    if ( $args['wpautop'] == 'true' || $args['wpautop'] == '1' ) {
        $content = themeblvd_get_content( $content );
    } else {
    	$content = do_shortcode( $content );
    }

    // Construct alert
    $output = sprintf( '<div class="%s">%s</div><!-- .panel (end) -->', $class, do_shortcode( $content ) );

    return apply_filters( 'themeblvd_alert', $output, $args, $content );
}

/**
 * Display alert formatted correctly for Bootstrap
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for panel
 * @param string $content content for panel, optional
 */
function themeblvd_alert( $args, $content = '' ) {
	echo themeblvd_get_alert( $args, $content );
}

/**
 * Get icon box.
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for alert
 * @return string $output Output for alert
 */
function themeblvd_get_icon_box( $args ) {

	$defaults = array(
        'icon'			=> '',			// FontAwesome icon ID
        'size'			=> '20px',		// Font size of font icon
        'location'		=> 'above',		// Location of icon
        'color'			=> '#666666',	// Color of the icon
        'circle'		=> '0',			// Whether to circle the icon
        'circle_color'	=> '#cccccc',	// BG color of the circle
        'title'			=> '',			// Title of the block
        'text'			=> ''			// Content of the block
    );
    $args = wp_parse_args( $args, $defaults );

    // Class for icon box
    $class = sprintf( 'tb-icon-box icon-%s', $args['location'] );

    if ( $args['circle'] ) {
    	$class .= ' icon-circled';
    }

    // Icon
    $icon_style = sprintf( 'color: %s; font-size: %s;', $args['color'], $args['size'] );

    if ( $args['circle'] ) {
    	$icon_style .= sprintf( ' background-color: %s;', $args['circle_color'] );
    }

    $icon = sprintf( '<div class="icon" style="%s"><i class="fa fa-%s" style="width:%s;"></i></div>', $icon_style, $args['icon'], $args['size'] );

    // Content style
    $content_style = '';

    if ( $args['location'] == 'side' ) {

    	$padding = intval( str_replace( 'px', '', $args['size'] ) );

    	if ( $args['circle'] ) {
    		$padding = $padding + 30; // Account for 15px of padding both sides of circled icon
    	}

    	$padding = $padding + 10;

    	if ( is_rtl() ) {
    		$content_style = sprintf( 'padding-right: %spx;', $padding );
    	} else {
    		$content_style = sprintf( 'padding-left: %spx;', $padding );
    	}
    }

    // Final output
	$output  = '<div class="'.$class.'">';

	$output .= $icon;

	if ( $args['title'] || $args['text'] ) {
		$output .= '<div class="content" style="'.$content_style.'">';
		$output .= '<h3>'.$args['title'].'</h3>';
		$output .= themeblvd_get_content( $args['text'] );
		$output .= '</div><!-- .content (end) -->';
	}

	$output .= '</div><!-- .tb-icon-box (end) -->';

	return apply_filters( 'themeblvd_icon_box', $output, $args, $icon );
}

/**
 * Display icon box.
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for panel
 */
function themeblvd_icon_box( $args ) {
	echo themeblvd_get_icon_box( $args );
}

/**
 * Get content block
 *
 * @since 2.5.0
 *
 * @param string $args Options for content block
 * @return string $output Content output
 */
function themeblvd_get_content_block( $args ){

	$defaults = array(
        'content'		=> '',			// Content to display
        'style'			=> '',			// Custom styling class
		'text_color'	=> 'dark',		// Color of text, dark or light
        'bg_color'		=> '#cccccc',	// Background color, if wrap is true
        'bg_opacity'	=> '1'			// Background color opacity, if wrap is true
    );
    $args = wp_parse_args( $args, $defaults );

	// CSS class
	$class = 'tb-content-block';

	if ( $args['style'] == 'custom' ) {
		$class .= ' has-bg text-'.$args['text_color'];
	}

	if ( $args['style'] && $args['style'] != 'custom' && $args['style'] != 'none'  ) {
		$class .= ' '.$args['style'];
	}

	// Inline styles
	$style = '';

	if ( $args['style'] == 'custom' ) {
		$style = sprintf( 'background-color: %s;', $args['bg_color'] ); // Fallback for older browsers
		$style = sprintf( 'background-color: %s;', themeblvd_get_rgb( $args['bg_color'], $args['bg_opacity'] ) );
	}

	// Final output
	$output = sprintf( '<div class="%s" style="%s">%s</div>', $class, $style, themeblvd_get_content( $args['content'] ) );

	return apply_filters( 'themeblvd_content_block', $output, $args );
}

/**
 * Display content block
 *
 * @since 2.5.0
 *
 * @param string $args Options for content block
 * @return string $output Content output
 */
function themeblvd_content_block( $args ){
	echo themeblvd_get_content_block( $args );
}

/**
 * Get team member.
 *
 * @since 2.5.0
 *
 * @param string $args Options for content block
 * @return string $output Content output
 */
function themeblvd_get_team_member( $args ){

	$defaults = array(
        'image'			=> array(),	// Image of person
        'name'			=> '',		// Name of person
        'tagline'		=> '',		// Tagline for person, Ex: Founder and CEO
        'icons'			=> array(),	// Social icons for themeblvd_contact_bar()
        'icons_style'	=> 'grey',	// Style of social icons - grey, light, dark, or color
        'text'			=> ''		// Description for person
    );
    $args = wp_parse_args( $args, $defaults );

    $output = '<div class="tb-team-member">';

    if ( ! empty( $args['image']['src'] ) ) {
    	$output .= sprintf( '<div class="member-image"><img src="%s" alt="%s" class="img-thumbnail" /></div>', $args['image']['src'], $args['image']['title'] );
    }

    $output .= '<div class="member-info clearfix">';

    $output .= '<div class="member-identity">';

    if ( $args['name'] ) {
    	$output .= sprintf( '<span class="member-name">%s</span>', $args['name'] );
    }

    if ( $args['tagline'] ) {
    	$output .= sprintf( '<span class="member-tagline">%s</span>', $args['tagline'] );
    }

    $output .= '</div><!-- .member-identity (end) -->';

    if ( $args['icons'] ) {
    	$icon_args = array(
    		'style' => $args['icons_style'],
    		'class'	=> 'member-contact'
    	);
		$output .= themeblvd_get_contact_bar( $args['icons'], $icon_args );
    }

    $output .= '</div><!-- .member-info (end) -->';

    if ( $args['text'] ) {
	    $output .= sprintf('<div class="member-text">%s</div><!-- .member-text (end) -->', themeblvd_get_content($args['text']) );
    }

    $output .= '</div><!-- .tb-team-member (end) -->';

    return apply_filters( 'themeblvd_team_member', $output, $args );
}

/**
 * Display team member.
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for panel
 */
function themeblvd_team_member( $args ) {
	echo themeblvd_get_team_member( $args );
}

/**
 * Get testimonial.
 *
 * @since 2.5.0
 *
 * @param string $args Options for content block
 * @return string $output Content output
 */
function themeblvd_get_testimonial( $args ){

	$defaults = array(
        'text'			=> '',		// Text for testimonial
        'name'			=> '', 		// Name of person giving testimonial
        'tagline'		=> '',		// Tagline or position of person giving testimonial
        'company'		=> '',		// Company of person giving testimonial
        'company_url'	=> '',		// Company URL of person giving testimonial
        'image'			=> array()	// Image of person giving testimonial
    );
    $args = wp_parse_args( $args, $defaults );

    $class = 'tb-testimonial';

	if ( ! empty( $args['image']['src'] ) ) {
		$class .= ' has-image';
	}

	if ( $args['name'] && ( $args['tagline'] || $args['company'] ) ) {
		$class .= ' tag-two-line';
	} else if ( $args['name'] || $args['tagline'] || $args['company'] ) {
		$class .= ' tag-one-line';
	}

	$output = '<div class="'.$class.'">';

	$output .= sprintf( '<div class="testimonial-text"><span class="arrow"></span>%s</div>', themeblvd_get_content($args['text']) );

	if ( $args['name'] ) {

		$output .= '<div class="author">';

		if ( ! empty( $args['image']['src'] ) ) {
			$output .= sprintf( '<span class="author-image"><img src="%s" alt="%s" /></span>', $args['image']['src'], $args['image']['title'] );
		}

		$output .= sprintf( '<span class="author-name">%s</span>', $args['name'] );

		if ( $args['tagline'] || $args['company'] ) {

			$tagline = '';

			if ( $args['tagline'] ) {
				$tagline .= $args['tagline'];
			}

			if ( $args['company'] ) {

				$company = $args['company'];

				if ( $args['company_url'] ) {
					$company = sprintf( '<a href="%1$s" title="%2$s" target="_blank">%2$s</a>', $args['company_url'], $company );
				}

				if ( $tagline ) {
					$tagline .= ', '.$company;
				} else {
					$tagline .= $company;
				}

			}

			$output .= sprintf( '<span class="author-tagline">%s</span>', $tagline );

		}

		$output .= '</div><!-- .author (end) -->';
	}

    $output .= '</div><!-- .tb-testimonial (end) -->';

    return apply_filters( 'themeblvd_testiomnial', $output, $args );
}


/**
 * Display testimonial.
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for panel
 */
function themeblvd_testimonial( $args ) {
	echo themeblvd_get_testimonial( $args );
}

/**
 * Get testimonial slider.
 *
 * @since 2.5.0
 *
 * @param string $args Options for content block
 * @return string $output Content output
 */
function themeblvd_get_testimonial_slider( $args ){

	$defaults = array(
        'testimonials'	=> array(),		// The testimonials, each formatted for themeblvd_get_testimonial
        'timeout'		=> '3',			// Secods in between transitions, can be 0 for no auto rotation
        'nav_standard'	=> true			// Whether to show slider navigation below
    );
    $args = wp_parse_args( $args, $defaults );

    $class = 'tb-testimonial-slider flexslider';

    if ( $args['nav_standard'] ) {
    	$class .= ' has-nav';
    }

    $output = sprintf('<div class="%s" data-timeout="%s" data-nav="%s">', $class, $args['timeout'], $args['nav_standard'] );

    if ( $args['testimonials'] ) {

    	$output .= '<ul class="slides">';

    	foreach ( $args['testimonials'] as $testimonial ) {
    		$output .= sprintf( '<li class="slide">%s</li>', themeblvd_get_testimonial($testimonial) );
    	}

    	$output .= '</ul>';

    }

    $output .= '</div><!-- .tb-testimonial-slider (end) -->';

    return apply_filters( 'themeblvd_testiomnial_slider', $output, $args );
}

/**
 * Display testimonial slider.
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for panel
 */
function themeblvd_testimonial_slider( $args ) {
	echo themeblvd_get_testimonial_slider( $args );
}

/**
 * Get Google Map
 *
 * @since 2.4.2
 *
 * @param array $args Arguments for map
 * @return string $output Final content to output
 */
function themeblvd_get_map( $args ) {

	wp_enqueue_script( 'google_maps' );

	$defaults = array(
		'id'			=> uniqid('map_'),	// Unique ID for map
		'markers'		=> array(),			// Location markers for map
		'height'		=> '400',			// CSS height of map
		'center_type'	=> 'default',		// If default, will be first location - default or custom
		'center'		=> array(),			// If above is custom, this will be the center point of map
        'zoom'			=> '15',			// Zoom level of initial map- [1, 20]
		'lightness'		=> '0',				// Map brightness - [-100, 100]
		'saturation'	=> '0',				// Map color saturation - [-100, 100]
		'has_hue'		=> '0',				// Whether map has overlay color
		'hue'			=> '',				// Overlay color for map (i.e. hue)
		'zoom_control'	=> '1',				// Whether user has zoom control
		'pan_control'	=> '1',				// Whether user has pan control
		'draggable'		=> '1'				// Whether user can drag map around
    );
    $args = wp_parse_args( $args, $defaults );

    $hue = '0';
    if ( $args['has_hue'] && $args['hue'] ) {
    	$hue = $args['hue'];
    }

    // Start map with config options
    $output = sprintf( '<div class="tb-map" data-zoom="%s" data-lightness="%s" data-saturation="%s" data-hue="%s" data-zoom_control="%s" data-pan_control="%s" data-draggable="%s">', $args['zoom'], $args['lightness'], $args['saturation'], $hue, $args['zoom_control'], $args['pan_control'], $args['draggable'] );

    // Map gets inserted into this DIV
    $output .= sprintf( '<div id="%s" class="map-canvas" style="height: %spx;"></div>', $args['id'], $args['height'] );

    // Map center point
    $center_lat = '0';
    $center_long = '0';

    if ( $args['center_type'] == 'custom' ) {

		// Custom center point
		if ( isset( $args['center']['lat'] ) ) {
			$center_lat = $args['center']['lat'];
		}
		if ( isset( $args['center']['long'] ) ) {
			$center_long = $args['center']['long'];
		}

    } else {

		// Default: Use first marker as center point
		if ( $args['markers'] ) {
			foreach ( $args['markers'] as $marker ) {
				if ( isset( $marker['geo']['lat'] ) ) {
					$center_lat = $marker['geo']['lat'];
				}
				if ( isset( $marker['geo']['long'] ) ) {
					$center_long = $marker['geo']['long'];
				}
				break;
			}
		}

    }

    $output .= sprintf('<div class="map-center" data-lat="%s" data-long="%s"></div>', $center_lat, $center_long );

    // Map markers
    if ( $args['markers'] ) {

		$output .= '<div class="map-markers hide">';

		foreach ( $args['markers'] as $marker ) {

			$name = '';
			if ( ! empty( $marker['name'] ) ) {
				$name = $marker['name'];
			}

			$lat = '0';
			if ( ! empty( $marker['geo']['lat'] ) ) {
				$lat = $marker['geo']['lat'];
			}

			$long = '0';
			if ( ! empty( $marker['geo']['long'] ) ) {
				$long = $marker['geo']['long'];
			}

			$info = '';
			if ( ! empty( $marker['info'] ) ) {
				$info = $marker['info'];
			}

			$image = '';
			if ( ! empty( $marker['image']['src'] ) ) {
				$image = $marker['image']['src'];
			}

			$output .= sprintf('<div class="map-marker" data-name="%s" data-lat="%s" data-long="%s" data-image="%s">', $name, $lat, $long, $image );
			$output .= sprintf( '<div class="map-marker-info">%s</div>', themeblvd_get_content($info) );
			$output .= '</div><!-- .map-marker (end) -->';
		}

		$output .= '</div><!-- .map-markers (end) -->';
	}

    $output .= '</div><!-- .tb-map (end) -->';

	return apply_filters( 'themeblvd_map', $output, $args );
}

/**
 * Display Google map
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for Google Map.
 */
function themeblvd_map( $args ) {
	echo themeblvd_get_map( $args );
}

/**
 * Get Bootstrap Jumbotron
 *
 * @since 2.4.2
 *
 * @param array $args Arguments for jumbotron
 * @param string $args Content within jumbotron
 * @return string $output Final content to output
 */
function themeblvd_get_jumbotron( $args, $content ) {

	$output = '';

	$defaults = array(
        'title'        	=> '',      // Title of unit
        'style'			=> 'none',	// Custom styling class
        'bg_color'		=> '',		// Background color - Ex: #000000
        'bg_opacity'	=> '1',		// BG color opacity for rgba()
        'text_color'	=> '',		// Text color - Ex: #000000
        'text_align'   	=> 'left',  // How to align text - left, right, center
        'align'        	=> '',      // How to align jumbotron - left, right, center, blank for no alignment
        'max_width'    	=> '',      // Meant to be used with align left/right/center - 300px, 50%, etc
        'class'        	=> '',      // Any additional CSS classes
        'wpautop'		=> true 	// Whether to apply wpautop on content
    );
    $args = wp_parse_args( $args, $defaults );

    // WP auto?
    if ( $args['wpautop'] ) {
    	$content = wpautop( $content );
    }

    // CSS classes
    $class = sprintf( 'jumbotron text-%s', $args['text_align'] );

    // Setup inline styles
    $style = '';

    if ( $args['style'] == 'custom' ) {

	    if ( $args['bg_color'] ) {
	    	$style .= sprintf( 'background-color:%s;', $args['bg_color'] ); // Fallback for older browsers
	    	$style .= sprintf( 'background-color:%s;', themeblvd_get_rgb( $args['bg_color'], $args['bg_opacity'] ) );
	    	$class .= ' has-bg';
	    }

	    if ( $args['text_color'] ) {
	    	$style .= sprintf( 'color:%s;', $args['text_color'] );
	    }

	}

	// Custom CSS classes
	if ( $args['style'] && $args['style'] != 'custom' && $args['style'] != 'none'  ) {
		$class .= ' '.$args['style'];
	}

    if ( $args['class'] ) {
    	$class .= ' '.$args['class'];
    }

    // Construct initial jumbotron
    if ( $args['title'] ) {
    	$title = sprintf( '<h2>%s</h2>', $args['title'] );
    	$content = $title.$content;
    }

    $jumbotron = sprintf('<div class="%s" style="%s">%s</div>', $class, $style, do_shortcode( $content ) );

    // Wrap the unit
	$wrap_class = 'jumbotron-wrap';

	// Align jumbotron right or left?
	if ( $args['align'] == 'left' ) {
		$wrap_class .= ' pull-left';
	} else if ( $args['align'] == 'right' ) {
		$wrap_class .= ' pull-right';
	}

	// Inline styles
	$style = '';

	// Align jumbotron center?
	if ( $args['align'] == 'center' ) {
		$style .= 'margin-left: auto; margin-right: auto; ';
	}

	// Max width?
	if ( $args['max_width'] ) {
		$style .= sprintf( 'max-width: %s;', $args['max_width'] );
	}

	// Final output
	$output = sprintf( '<div class="%s" style="%s">%s</div>', $wrap_class, $style, $jumbotron );

	return apply_filters( 'themeblvd_jumbotron', $output, $args, $content, $jumbotron );
}