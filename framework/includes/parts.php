<?php
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
		$buttons = themeblvd_get_option('social_media');
	}

	// Setup arguments
	$defaults = apply_filters('themeblvd_contact_bar_defaults', array(
		'style'		=> themeblvd_get_option( 'social_media_style', null, 'flat' ),	// color, grey, light, dark, flat
		'tooltip'	=> 'bottom',
		'class'		=> ''															// top, right, left, bottom, false
	));
	$args = wp_parse_args( $args, $defaults );

	// Start output
	$output = '';

	if ( $buttons && is_array($buttons) ) {

		$class = 'themeblvd-contact-bar tb-social-icons '.$args['style'];

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

/**
 * Searchform popup, uses searchform.php for actual
 * search form portion
 *
 * @since 2.5.0
 *
 * @param array $args Optional argments to override default behavior
 * @return string $output HTML to output for searchform
 */
function themeblvd_get_search_popup( $args = array() ) {

	// Setup arguments
	$defaults = apply_filters('themeblvd_search_popup_defaults', array(
		'open'			=> 'search',	// FontAwesome icon to open
		'close'			=> 'times',		// FontAwesome icon to close
		'placement-x'	=> '', 			// left, right
		'placement-y'	=> 'bottom', 	// top, bottom
		'class'			=> '' 			// Optional CSS class to add
	));
	$args = wp_parse_args( $args, $defaults );

	$x = $args['placement-x'];

	if ( ! $x ) {
		if ( is_rtl() ) {
			$x = 'right';
		} else {
			$x = 'left';
		}
	}

	$class = sprintf( 'tb-floater tb-search-popup %s %s', $x, $args['placement-y'] );

	if ( $args['class'] ) {
		$class .= $args['class'];
	}

	$output = sprintf( '<div class="%s">', $class );

	// Trigger Button
	$output .= sprintf( '<a href="#" class="floater-trigger search-trigger" data-open="%1$s" data-close="%2$s"><i class="fa fa-%1$s"></i></a>', $args['open'], $args['close'] );

	// Search popup
	$output .= '<div class="floater-popup search-popup">';
	$output .= '<span class="arrow"></span>';
	$output .= get_search_form(false);
	$output .= '</div><!-- .search-holder (end) -->';

	$output .= '</div><!-- .tb-search-popup (end) -->';

	return apply_filters( 'themeblvd_search_popup', $output, $args );
}

/**
 * Searchform popup, uses searchform.php for actual
 * search form portion
 *
 * @since 2.5.0
 *
 * @param array $args Optional argments to override default behavior
 */
function themeblvd_search_popup( $args = array() ) {
	echo themeblvd_get_search_popup( $args );
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

/**
 * Get group of buttons
 *
 * @since 2.5.0
 */
function themeblvd_get_buttons( $buttons, $args ) {

	$defaults = array(
		'stack'	=> false
	);
	$args = wp_parse_args( $args, $defaults );

	// Default button atts
	$btn_std = array(
		'color'				=> 'default',
		'custom'			=> array(),
		'text'				=> '',
		'size'				=> 'default',
		'url'				=> '',
		'target'			=> '_self',
		'icon_before'		=> '',
		'icon_after'		=> '',
		'block'				=> false
	);

	// Default custom button atts
	$btn_custom_std = array(
		'bg'				=> '',
		'bg_hover'			=> '',
		'border'			=> '',
		'text'				=> '',
		'text_hover'		=> '',
		'include_border'	=> '1',
		'include_bg'		=> '1'
	);

	$output = '';

	$total = count($buttons);
	$i = 1;

	if ( $buttons ) {
		foreach ( $buttons as $btn ) {

			$btn = wp_parse_args( $btn, $btn_std );

			if ( ! $btn['text'] ) {
				continue;
			}

			if ( $args['stack'] ) {
				$output .= '<p class="has-btn">';
			}

			$addon = '';

			if ( $btn['color'] == 'custom' && $btn['custom'] ) {

				$custom = wp_parse_args( $btn['custom'], $btn_custom_std );

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

	            $addon = sprintf( 'style="background-color: %1$s; border-color: %2$s; color: %3$s;" data-bg="%1$s" data-bg-hover="%4$s" data-text="%3$s" data-text-hover="%5$s"', $bg, $border, $custom['text'], $custom['bg_hover'], $custom['text_hover'] );

	        }

			$output .= themeblvd_button( $btn['text'], $btn['url'], $btn['color'], $btn['target'], $btn['size'], null, null, $btn['icon_before'], $btn['icon_after'], $addon, $btn['block'] );

			if ( $args['stack'] ) {
				$output .= '</p>';
			} else if ( $i < $total ) {
				$output .= ' ';
			}

			$i++;
		}
	}

	return apply_filters( 'themeblvd_buttons', $output, $buttons );

}

/**
 * Display group of buttons
 *
 * @since 2.5.0
 */
function themeblvd_buttons( $buttons ) {
	echo themeblvd_get_buttons( $buttons );
}

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
 * A default full display for breacrumbs with surrounding
 * HTML markup. If you're looking for a custom way to wrap
 * a breadcrumbs output create your own function and use
 * themeblvd_get_breadcrumbs_trail() to get just the trail.
 *
 * @since 2.5.0
 */
function themeblvd_the_breadcrumbs(){
	echo '<div id="breadcrumbs">';
	echo '<div class="wrap">';
	echo themeblvd_get_breadcrumbs_trail();
	echo '</div><!-- .wrap (end) -->';
	echo '</div><!-- #breadcrumbs (end) -->';
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

	// Post Format
	$format = get_post_format();
	$icon = themeblvd_get_format_icon($format);

	if ( $icon ) {
		// Note: URL to post format archive => esc_url( get_post_format_link($format) )
		$output .= sprintf( '<span class="post-format"><i class="fa fa-%s"></i> %s</span>', $icon, themeblvd_get_local($format) );
		$output .= $sep;
	}

	// Time
	$time = sprintf('<time class="entry-date updated" datetime="%s"><i class="fa fa-calendar"></i> %s</time>', get_the_time('c'), get_the_time( get_option('date_format') ) );
	$output .= $time;

	// Author
	$author_url = esc_url( get_author_posts_url( get_the_author_meta('ID') ) );
	$author_title = sprintf( __( 'View all posts by %s', 'themeblvd_frontend' ), get_the_author() );
	$author = sprintf( '<span class="byline author vcard"><i class="fa fa-user"></i> <a class="url fn n" href="%s" title="%s" rel="author">%s</a></span>', $author_url, $author_title, get_the_author() );
	$output .= $sep;
	$output .= $author;

	// Category
	/*
	$category = '';
	if ( has_category() ) {
		$category = sprintf( '<span class="category"><i class="fa fa-bars"></i> %s</span>', get_the_category_list(', ') );
		$output .= $sep;
		$output .= $category;
	}
	*/

	// Comments
	$comments = '';

	if( comments_open() ) {

		$output .= $sep;
		$comments .= '<span class="comments-link">';

		ob_start();
		comments_popup_link( '<span class="leave-reply">'.themeblvd_get_local('leave_comment').'</span>', '1 '.themeblvd_get_local('comment'), '% '.themeblvd_get_local('comments') );
		$comment_link = ob_get_clean();

		$comments .= sprintf( '<i class="fa fa-comment"></i> %s', $comment_link, $sep );
		$comments .= '</span>';

		$output .= $comments;

	}

	$output .= '</div><!-- .entry-meta -->';

	return apply_filters( 'themeblvd_meta', $output, $time, $author, $category, $comments, $sep );
}

/**
 * Show/Get blog categories for a post (in loop)
 *
 * @since 2.5.0
 *
 * @param bool $echo Whether to echo out the categories
 */
function themeblvd_blog_cats( $echo = true ) {

	$output = '';

	if ( has_category() ) {
		$output .= '<div class="tb-cats categories">';
		$output .= sprintf( '<span class="title">%s:</span>', themeblvd_get_local('posted_in') );
		ob_start();
		the_category(', ');
		$output .= ob_get_clean();
		$output .= '</div><!-- .tb-cats (end) -->';
	}

	$output = apply_filters( 'themeblvd_blog_cats', $output, get_the_ID() );

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * Show/Get blog tags for a post (in loop)
 *
 * @since 2.0.0
 *
 * @param bool $echo Whether to echo out the tags
 */
function themeblvd_blog_tags( $echo = true ) {

	$output = '';

	if ( has_tag() ) {
		$output .= '<div class="tb-tags tags">';
		$before = sprintf( '<span class="title">%s:</span>', themeblvd_get_local('tags') );
		ob_start();
		the_tags( $before, ', ' );
		$output .= ob_get_clean();
		$output .= '</div><!-- .tb-tags (end) -->';
	}

	$output = apply_filters( 'themeblvd_blog_tags', $output, get_the_ID() );

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * Show/Get blog sharing buttons (in loop)
 *
 * @since 2.5.0
 *
 * @param bool $echo Whether to echo out the buttons
 */
function themeblvd_blog_share( $echo = true ) {

	$output = '';
	$buttons = themeblvd_get_option('share');

	if ( $buttons && is_array($buttons) ) {

		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail' );
		$patterns = themeblvd_get_share_patterns();
		$style = themeblvd_get_option('share_style');
		$permalink = get_permalink();
		$shortlink = wp_get_shortlink();
		$title = get_the_title();
		$excerpt = get_the_excerpt();

		$output .= sprintf( '<div class="tb-social-icons tb-share %s clearfix">', $style );
		$output .= '<ul class="social-media">';

		foreach ( $buttons as $button ) {

			$network = $button['icon'];

			// Link class
			$class = 'tb-share-button tb-tooltip '.$network;

			if ( $network != 'email' ) {
				$class .= ' popup';
			}

			if ( $style != 'color' ) { // Note: "color" means to use colored image icons; otherwise, we use icon font.
				$class .= ' tb-icon tb-icon-'.$network;
			}

			// Link URL
			$link = '';

			if ( isset( $patterns[$network] ) ) {

				$link = $patterns[$network]['pattern'];

				if ( $patterns[$network]['encode_urls'] ) {
					$link = str_replace( '[permalink]', rawurlencode($permalink), $link );
					$link = str_replace( '[shortlink]', rawurlencode($shortlink), $link );
					$link = str_replace( '[thumbnail]', rawurlencode($thumb[0]), $link );
				} else {
					$link = str_replace( '[permalink]', $permalink, $link );
					$link = str_replace( '[shortlink]', $shortlink, $link );
					$link = str_replace( '[thumbnail]', $thumb[0], $link );
				}

				if ( $patterns[$network]['encode'] ) {
					$link = str_replace( '[title]', rawurlencode($title), $link );
					$link = str_replace( '[excerpt]', rawurlencode($excerpt), $link );
				} else {
					$link = str_replace( '[title]', $title, $link );
					$link = str_replace( '[excerpt]', $excerpt, $link );
				}
			}

			$output .= sprintf( '<li><a href="%s" title="%s" class="%s" data-toggle="tooltip" data-placement="top"></a></li>', $link, $button['label'], $class );
		}

		$output .= '</ul>';
		$output .= '</div><!-- .tb-share (end) -->';

	}

	$output = apply_filters( 'themeblvd_blog_share', $output, get_the_ID(), $buttons, $style );

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
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
	$frame = apply_filters( 'themeblvd_featured_thumb_frame', false );

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

					if ( $frame ) {
						$image .= '<div class="thumbnail">';
					}

					$image .= sprintf( '<img src="%s.png" width="%s" class="wp-post-image" />', $default_img_directory.$thumb.'_'.$post_format, $thumb_width );

					if ( $frame ) {
						$image .= '</div><!-- .thumbnail (end) -->';
					}

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
	$frame = apply_filters( 'themeblvd_featured_thumb_frame', false );

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

					$class = 'tb-thumb-link image';

					if ( $frame ) {
						$class .= ' thumbnail';
					}

					$args = array(
						'item'		=> $item,
						'link'		=> $image[0],
						'title'		=> $post->post_title,
						'class'		=> $class,
						'gallery' 	=> true
					);

					$output .= themeblvd_get_link_to_lightbox( $args );

				} else {

					$class = 'tb-thumb-link image';

					if ( $frame ) {
						$class .= ' thumbnail';
					}

					$output .= sprintf( '<a href="%s" title="%s" class="%s">', get_permalink($post->ID), $post->post_title, $gallery, $class );
					$output .= sprintf( '<img src="%s" alt="%s" />', $thumbnail[0], $post->post_title );
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

					$class = 'tb-thumb-link post';

					if ( $frame ) {
						$class .= ' thumbnail';
					}

					$image .= '<div class="featured-image-wrapper attachment-'.$thumb_size.'">';
					$image .= '<div class="featured-image">';
					$image .= '<div class="featured-image-inner">';
					$image .= sprintf( '<a href="%s" title="%s" class="%s">', get_permalink(), get_the_title(), $class );
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

/**
 * Get moveable slider controls
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for slider controls
 * @return string $output Final content to output
 */
function themeblvd_get_slider_controls( $args = array() ) {

	$defaults = array(
		'direction' 	=> 'horz', 						// horz or vert
		'prev' 			=> themeblvd_get_local('prev'),	// Text for previous button
		'next' 			=> themeblvd_get_local('next'),	// Text for next button
    );
    $args = wp_parse_args( $args, $defaults );

    if ( $args['direction'] == 'horz' ) {
		$icon_prev = 'chevron-left';
	    $icon_next = 'chevron-right';
	} else {
		$icon_prev = 'chevron-up';
		$icon_next = 'chevron-down';
	}

    if ( is_rtl() && $args['direction'] == 'horz' ) {
    	$icon_prev = 'chevron-right';
	    $icon_next = 'chevron-left';
    }

    $output  = '<ul class="tb-slider-arrows">';
	$output .= sprintf( '<li><a href="#" title="%s" class="prev"><i class="fa fa-%s"></i></a></li>', $args['prev'], $icon_prev );
	$output .= sprintf( '<li><a href="#" title="%s" class="next"><i class="fa fa-%s"></i></a></li>', $args['prev'], $icon_next );
	$output .= '</ul>';

    return apply_filters( 'themeblvd_slider_controls', $output, $args );
}

/**
 * Display moveable slider controls
 *
 * @since 2.5.0
 *
 * @param array $args Arguments slider controls
 */
function themeblvd_slider_controls( $args = array() ) {
	echo themeblvd_get_slider_controls( $args );
}

/**
 * Get scroll to top button
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for button
 * @return string $output Final content to output
 */
function themeblvd_get_to_top( $args = array() ) {

	$defaults = array(
		'class'	=> ''
	);
	$args = wp_parse_args( $args, $defaults );

	$output = sprintf('<a href="#" class="tb-scroll-to-top %s"><i class="fa fa-chevron-up"></i></a>', $args['class']);

    return apply_filters( 'themeblvd_to_top', $output, $args );
}

/**
 * Display scroll to top button. Incorporates theme option
 * so it can be easily hooked; so if you're manually outputting
 * the item, use themeblvd_get_to_top() and echo it out.
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for button
 */
function themeblvd_to_top( $args = array() ) {
	if ( themeblvd_get_option('scroll_to_top') == 'show' ) {
		echo themeblvd_get_to_top( $args );
	}
}