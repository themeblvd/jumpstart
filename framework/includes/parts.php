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
		'style'			=> themeblvd_get_option( 'social_media_style', null, 'flat' ),	// color, grey, light, dark, flat
		'tooltip'		=> 'bottom',
		'class'			=> '',															// top, right, left, bottom, false
		'authorship'	=> false
	));
	$args = wp_parse_args( $args, $defaults );

	// Start output
	$output = '';

	if ( $buttons && is_array($buttons) ) {

		$class = 'themeblvd-contact-bar tb-social-icons '.$args['style'];

		if ( $args['class'] ) {
			$class .= ' '.$args['class'];
		}

		$class .= ' clearfix';

		$output .= '<ul class="'.esc_attr($class).'">';

		foreach ( $buttons as $button ) {

			// Link class
			$class = 'tb-icon '.$button['icon'];

			if ( $args['tooltip'] && $args['tooltip'] != 'disable' ) {
				$class .= ' tb-tooltip';
			}

			// Link Title
			$title = '';

			if ( ! empty( $button['label']) ) {
				$title = $button['label'];
			}

			$title = str_replace('[url]', $button['url'], $title);
			$title = str_replace(array('http://', 'https://'), '', $title);

			// Google+ authorship
			if ( $args['authorship'] && $button['icon'] == 'google' ) {
				if ( strpos($button['url'], '?rel=author') === false ) {
					$button['url'] .= '?rel=author';
				}
			}

			// Content
			if ( $args['style'] == 'color' ) {

				$content = $title;

			} else {

				// FontAwesome icon ID conversions
				$icon = $button['icon']; // most icon ID's already match FA icon ID

				switch ( $button['icon'] ) {

					case 'chat' :
						$icon = 'comments';
						break;

					case 'email' :
						$icon = 'envelope';
						break;

					case 'anchor' :
						$icon = 'link';
						break;

					case 'movie' :
						$icon = 'film';
						break;

					case 'portfolio' :
						$icon = 'briefcase';
						break;

					case 'store' :
						$icon = 'shopping-basket';
						break;

					case 'write' :
						$icon = 'pencil';
						break;

					case 'github' :
						$icon = 'github-alt';
						break;

					case 'google' :
						$icon = 'google-plus';
						break;

					case 'pinterest' :
						$icon = 'pinterest-p';
						break;

					case 'vimeo' :
						$icon = 'vimeo-square';

				}

				$content = sprintf('<i class="fa fa-fw fa-%s"></i>', $icon);
			}

			$output .= sprintf( '<li><a href="%s" title="%s" class="%s" target="%s" data-toggle="tooltip" data-placement="%s">%s</a></li>', esc_url($button['url']), esc_attr($title), esc_attr($class), esc_attr($button['target']), esc_attr($args['tooltip']), themeblvd_kses($content) );
		}

		$output .= '</ul><!-- .themeblvd-contact-bar (end) -->';

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
function themeblvd_contact_bar( $buttons = array(), $args = array(), $trans = true ) {

	echo themeblvd_get_contact_bar( $buttons, $args );

	if ( $trans && themeblvd_config('suck_up') ) {

		$args = wp_parse_args(array(
			'style' => themeblvd_get_option( 'trans_social_media_style', null, 'flat' ),
			'class'	=> 'social-trans'
		), $args);

		echo themeblvd_get_contact_bar( themeblvd_get_option('trans_social_media'), $args );
	}
}

/**
 * Get floating searchform, uses searchform.php for actual
 * search form portion
 *
 * Note: While you can have multiple triggers, it'll work
 * best if there is only one floating search bar on the site.
 *
 * @since 2.5.0
 *
 * @param array $args Optional argments to override default behavior
 * @return string $output HTML to output for searchform
 */
function themeblvd_get_floating_search( $args = array() ) {

	// Setup arguments
	// $defaults = apply_filters('themeblvd_floating_search_defaults', array());
	// $args = wp_parse_args( $args, $defaults );

	$output  = '<div class="tb-floating-search">';
	$output .= '<div class="wrap">';
	$output .= '<a href="#" title="'.themeblvd_get_local('close').'" class="close-search">x</a>';
	$output .= get_search_form(false);
	$output .= '</div><!-- .wrap (end) -->';
	$output .= '</div><!-- .tb-floating-search (end) -->';

	return apply_filters( 'themeblvd_floating_search', $output, $args );
}

/**
 * Floating searchform, uses searchform.php for actual
 * search form portion
 *
 * @since 2.5.0
 *
 * @param array $args Optional argments to override default behavior
 */
function themeblvd_floating_search( $args = array() ) {
	if ( themeblvd_do_floating_search() ) {
		echo themeblvd_get_floating_search( $args );
	}
}

/**
 * Get floating searchform trigger link
 *
 * @since 2.5.0
 *
 * @param array $args Optional argments to override default behavior
 * @return string $output HTML to output for link
 */
function themeblvd_get_floating_search_trigger( $args = array() ) {

	// Setup arguments
	$defaults = apply_filters('themeblvd_floating_search_trigger_defaults', array(
		'open'		=> 'search',	// FontAwesome icon to open
		'class'		=> ''			// Optional css classes to add to <a>
	));
	$args = wp_parse_args( $args, $defaults );

	$class = 'tb-search-trigger';

	if ( $args['class'] ) {
		$class .= ' '.$args['class'];
	}

	$output = sprintf( '<a href="#" class="%s"><i class="fa fa-%s"></i></a>', esc_attr($class), esc_attr($args['open']) );

	return apply_filters( 'themeblvd_floating_search_trigger', $output, $args );
}

/**
 * Floating searchform trigger link
 *
 * @since 2.5.0
 *
 * @param array $args Optional argments to override default behavior
 */
function themeblvd_floating_search_trigger( $args = array() ) {
	echo themeblvd_get_floating_search_trigger( $args );
}

/**
 * Get side panel trigger link.
 *
 * @since 2.6.0
 *
 * @param array $args Optional argments to override default behavior
 * @return string $output HTML to output for link
 */
function themeblvd_get_side_trigger( $args = array() ) {

	// Setup arguments
	$defaults = apply_filters('themeblvd_side_trigger_defaults', array(
		'class'	=> ''
	));
	$args = wp_parse_args( $args, $defaults );

	$class = 'tb-side-trigger';

	if ( $args['class'] ) {
		$class .= ' ' . $args['class'];
	}

	$output  = sprintf("<a href=\"#\" class=\"%s\">\n", $class);
	$output .= "\t<span class=\"hamburger\">\n";
	$output .= "\t\t<span class=\"top\"></span>\n";
	$output .= "\t\t<span class=\"middle\"></span>\n";
	$output .= "\t\t<span class=\"bottom\"></span>\n";
	$output .= "\t</span>\n";
	$output .= "</a>\n";

	return apply_filters( 'themeblvd_side_trigger', $output, $args );
}

/**
 * Display side panel trigger link.
 *
 * @since 2.6.0
 *
 * @param array $args Optional argments to override default behavior
 */
function themeblvd_side_trigger( $args = array() ) {
	echo themeblvd_get_side_trigger( $args );
}

/**
 * Get floating shopping cart popup
 *
 * @since 2.5.0
 *
 * @param array $args Optional argments to override default behavior
 * @return string $output HTML to output for searchform
 */
function themeblvd_get_cart_popup( $args = array() ) {

	// Setup arguments
	$defaults = apply_filters('themeblvd_cart_popup_defaults', array(
		'trigger'	=> false,						// Include trigger in return
		'size'		=> 'sm', 						// sm, md, lg
		'id'		=> 'floating-shopping-cart',	// HTML ID of modal
		'class'		=> '' 							// Optional CSS class to add
	));
	$args = wp_parse_args( $args, $defaults );

	$output = '';

	if ( $args['trigger'] ) {
		$output .= themeblvd_get_cart_popup_trigger( array('target' => $args['id']) );
	}

	$class = 'tb-cart-popup modal fade';

	if ( $args['class'] ) {
		$class .= ' '.$args['class'];
	}

	// Cart popup
	$output .= '<div id="'.$args['id'].'" class="'.$class.'">';
	$output .= '<div class="modal-dialog modal-'.$args['size'].'">';
	$output .= '<div class="modal-content">';
	$output .= '<div class="modal-header">';
	$output .= '<button type="button" class="close" data-dismiss="modal" aria-label="'.themeblvd_get_local('close').'"><span aria-hidden="true">&times;</span></button>';
	$output .= '<h4 class="modal-title">'.themeblvd_get_local('cart').'</h4>';
	$output .= '</div>';
	$output .= '<div class="modal-body clearfix">';

	ob_start();

	/**
	 * @hooked Theme_Blvd_Compat_WooCommerce::cart - 10 - (If WooCommerce is activated)
	 */
	do_action('themeblvd_floating_cart');

	$output .= ob_get_clean();
	$output .= '</div><!-- .modal-body (end) -->';
	$output .= '</div><!-- .modal-content (end) -->';
	$output .= '</div><!-- .modal-dialog (end) -->';
	$output .= '</div><!-- .tb-cart-popup (end) -->';

	return apply_filters( 'themeblvd_cart_popup', $output, $args );
}

/**
 * Floating shopping cart popup
 *
 * @since 2.5.0
 *
 * @param array $args Optional argments to override default behavior
 */
function themeblvd_cart_popup( $args = array() ) {
	echo themeblvd_get_cart_popup( $args );
}

/**
 * Get shopping cart popup trigger link
 *
 * @since 2.5.0
 *
 * @param array $args Optional argments to override default behavior
 */
function themeblvd_get_cart_popup_trigger( $args = array() ) {

	// Setup arguments
	$defaults = apply_filters('themeblvd_cart_popup_trigger_defaults', array( // filtering matches themeblvd_get_cart_popup() args
		'icon'			=> 'shopping-basket',		// FontAwesome icon to open
		'class'			=> '',						// Optional CSS classes to add
		'target'		=> 'floating-shopping-cart'	// HTML ID of floating shopping cart linking to
	));
	$args = wp_parse_args( $args, $defaults );

	if ( ! empty($args['open']) ) {
		$args['icon'] = $args['open']; // backwards compat
	}

	$class = 'tb-cart-trigger menu-btn';

	if ( $args['class'] ) {
		$class .= ' '.$args['class'];
	}

	$output = sprintf( '<a href="#" class="%s" data-toggle="modal" data-target="#%s"><i class="fa fa-%3$s"></i></a>', esc_attr($class), esc_attr($args['target']), esc_attr($args['icon']) );

	if ( themeblvd_installed('woocommerce') && themeblvd_supports('plugins', 'woocommerce') ) {
		if ( $count = WC()->cart->get_cart_contents_count() ) {
			$output =  str_replace('tb-cart-trigger', 'tb-cart-trigger has-label char-'.strlen(strval($count)), $output);
			$label = sprintf( '<span class="trigger-label">%s</span>', $count );
			$output =  str_replace('</a>', themeblvd_kses($label).'</a>', $output);
		}
	}

	return apply_filters('themeblvd_cart_popup_trigger', $output, $args);
}

/**
 * Shopping cart popup trigger link
 *
 * @since 2.5.0
 */
function themeblvd_cart_popup_trigger( $args = array() ) {
	echo themeblvd_get_cart_popup_trigger( $args );
}

/**
 * Get shopping cart button used in mobile display.
 *
 * @since 2.5.0
 */
function themeblvd_get_mobile_cart_link() {

	global $woocommerce;

	$cart_url = '';
	$cart_label = '';

	if ( themeblvd_installed('woocommerce') ) {

		$cart_url = WC()->cart->get_cart_url();

		$count = WC()->cart->get_cart_contents_count();

		if ( $count ) {
			$cart_label = sprintf( '<span class="cart-count">%s</span>', $count );
		}
	}

	$output = sprintf( '<a href="%s" id="mobile-to-cart" class="btn-navbar cart">%s%s</a>', esc_url( apply_filters('themeblvd_cart_url', $cart_url) ), themeblvd_kses( apply_filters('themeblvd_btn_navbar_cart_text', '<i class="fa fa-shopping-basket"></i>') ), themeblvd_kses($cart_label) );

	return apply_filters('themeblvd_mobile_cart_link', $output, $cart_url, $cart_label);
}

/**
 * Shopping cart button used in mobile display.
 *
 * @since 2.5.0
 */
function themeblvd_mobile_cart_link() {
	echo themeblvd_get_mobile_cart_link();
}

/**
 * Get header text.
 *
 * @since 2.5.0
 */
function themeblvd_get_header_text() {

	$output = '';

	if ( $text = themeblvd_get_option('header_text') ) {
		$text = apply_filters( 'themeblvd_header_text', $text);
		$output = sprintf( '<div class="header-text to-mobile">%s</div>', themeblvd_kses($text) );
	}

	return apply_filters( 'themeblvd_header_text_output', $output );
}

/**
 * Display header text.
 *
 * @since 2.5.0
 */
function themeblvd_header_text() {
	echo themeblvd_get_header_text();
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
 * @param string $size Size of button - small, medium, default, large, x-large, xx-large, xxx-large
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
		$title = $text;
	}

	// Add icon before text?
	if ( $icon_before ) {
		$text = sprintf('<i class="fa fa-%s before"></i>%s', $icon_before, $text);
	}

	// Make sure there's a default target
	if ( ! $target ) {
		$target = '_self';
	}

	// Add icon after text?
	if ( $icon_after ) {
		$text .= sprintf('<i class="fa fa-%s after"></i>', $icon_after);
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
		$button = sprintf( '<a href="%s" title="%s" class="%s" target="%s"%s>%s</a>', esc_url($url), esc_attr($title), esc_attr($final_classes), esc_attr($target), wp_kses($addon, array()), themeblvd_kses($text) );

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

	            $addon = sprintf( 'style="background-color: %1$s; border-color: %2$s; color: %3$s;" data-bg="%1$s" data-bg-hover="%4$s" data-text="%3$s" data-text-hover="%5$s"', esc_attr($bg), esc_attr($border), themeblvd_kses($custom['text']), esc_attr($custom['bg_hover']), esc_attr($custom['text_hover']) );

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

	return apply_filters( 'themeblvd_buttons', $output, $buttons, $args );

}

/**
 * Display group of buttons
 *
 * @since 2.5.0
 */
function themeblvd_buttons( $buttons ) {
	echo themeblvd_get_buttons( $buttons );
}

/**
 * Get group of text blocks
 *
 * @since 2.5.0
 */
function themeblvd_get_text_blocks( $blocks, $args = array() ) {

	/*
	$defaults = array(
		// Currently no $args, maybe have some in the future
	);
	$args = wp_parse_args( $args, $defaults );
	*/

	$block_std = array(
		'text'				=> '',
	    'size'				=> '200%',
	    'color'				=> '',
	    'apply_bg_color'	=> '0',
	    'bg_color'			=> '#ffffff',
	    'bg_opacity'		=> '1',
	    'bold'				=> '0',
	    'italic'			=> '0',
	    'caps'				=> '0',
		'suck_down'			=> '0',
	    'wpautop'			=> '1',
	);

	$output = '<div class="tb-text-blocks">';

	if ( $blocks && is_array($blocks) ) {

		$i = 1;

		foreach ( $blocks as $block ) {

			$block = wp_parse_args( $block, $block_std );

			// CSS class
			$class = sprintf('tb-text-block text-block-%s', $i);

			if ( $block['italic'] ) {
				$class .= ' italic';
			} else {
				$class .= ' no-italic';
			}

			if ( $block['caps'] ) {
				$class .= ' caps';
			} else {
				$class .= ' no-caps';
			}

			// Font Size (outer style)
			$size_style = '';
			$size_class = '';

			if ( $block['size'] ) {

				$size = $block['size'];

				if ( strpos($size, '%') !== false ) {

					$size  = intval($size) / 100;

					if ( $size >= 3 ) {
						$size_class = 'text-large';
					} else if ( $size >= 2 ) {
						$size_class = 'text-medium';
					} else {
						$size_class = 'text-small';
					}

					$size .= 'em';
				}

				$size_style = sprintf( 'font-size:%s;', $size );
			}

			if ( $block['suck_down'] ) {
				$size_class .= ' suck-down';
			}

			// CSS style (inner style)
			$style = '';

			if ( $block['apply_bg_color'] ) {
				$style .= sprintf( 'background-color:%s;', esc_attr($block['bg_color']) ); // Fallback for older browsers
	            $style .= sprintf( 'background-color:%s;', esc_attr( themeblvd_get_rgb( $block['bg_color'], $block['bg_opacity'] ) ) );
				$class .= ' has-bg';
			}

			if ( $block['color'] ) {
				$style .= sprintf( 'color:%s;', esc_attr($block['color']) );
			}

			// Content
			if ( $block['wpautop'] && ! $block['bold'] ) {
				$content = themeblvd_get_content($block['text']);
			} else {
				$content = do_shortcode( themeblvd_kses($block['text']) );
			}

			// Output
			$output .= sprintf("\n\t<div class=\"tb-text-block-wrap %s\" style=\"%s\">", $size_class, $size_style);

			if ( $block['bold'] ) {
				$output .= sprintf("\n\t\t<%1\$s class=\"%2\$s\" style=\"%3\$s\">%4\$s</%1\$s><!-- .tb-text-block (end) -->", apply_filters('themeblvd_text_block_header_tag', 'h1'), $class, $style, $content);
			} else {
				$output .= sprintf("\n\t\t<div class=\"%s\" style=\"%s\">\n\t\t\t%s\t\t</div><!-- .tb-text-block (end) -->", $class, $style, $content);
			}

			$output .= "\n\t</div><!-- .tb-text-block-wrap (end) -->";

			$i++;
		}
	}

	$output .= '</div><!-- .tb-text-blocks -->';

	return apply_filters( 'themeblvd_text_blocks', $output, $blocks, $args );
}

/**
 * Display group of text blocks
 *
 * @since 2.5.0
 */
function themeblvd_text_blocks( $blocks ) {
	echo themeblvd_get_text_blocks( $blocks );
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
		echo themeblvd_get_local('crumb_author').' '.esc_html($userdata->display_name);

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

	// Get pagination parts
	$parts = themeblvd_get_pagination_parts( $pages, $range );

	if ( ! $parts ) {
		return '';
	}

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
	$wrap .= '		<div class="btn-group paginate_links clearfix">';
	$wrap .= '			%s';
	$wrap .= '		</div>';
	$wrap .= '	</div>';
	$wrap .= '</div>';

	$output = sprintf( $wrap, $output );

	return apply_filters( 'themeblvd_pagination', $output, $parts, $pages, $range );
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
		'home_link' 	=> themeblvd_get_home_url(),
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
				$crumb = '<a href="'.esc_url($part['link']).'" class="'.$part['type'].'-link" title="'.esc_attr($crumb).'">'.esc_html($crumb).'</a>';
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
	echo '<div id="breadcrumbs" class="site-breadcrumbs">';
	echo '<div class="wrap">';
	echo themeblvd_get_breadcrumbs_trail();
	echo '</div><!-- .wrap (end) -->';
	echo '</div><!-- #breadcrumbs (end) -->';
}

/**
 * Get meta display for a post
 *
 * @since 2.3.0
 */
function themeblvd_get_meta( $args = array() ) {

	$defaults = array(
		'sep' 		=> apply_filters( 'themeblvd_meta_separator', '<span class="sep"> / </span>' ),
		'include'	=> array('format', 'time', 'author', 'comments'), // possible: author, category, comments, format, portfolio, time
		'icons'		=> array('format', 'time', 'author', 'comments', 'category', 'portfolio'), // all includes to display icons
		'comments'	=> 'standard',	// can be string "mini"
		'time'		=> 'standard',	// can be string "ago"
		'class'		=> ''	// Additional CSS classes for wrapper
	);
	$args = wp_parse_args( $args, $defaults );

	// Setup FA icons
	$icons = apply_filters('themeblvd_meta_icons', array(
		'time'		=> 'clock-o',
		'author'	=> 'user',
		'comments'	=> 'comment',
		'category'	=> 'folder',
		'portfolio'	=> 'briefcase',
	));

	// Go forth with comments?
	if ( in_array('comments', $args['include']) && ! themeblvd_show_comments() ) {
		$key = array_search('comments', $args['include']);
		unset( $args['include'][$key] );
	}

	// Prep for category
	if ( in_array('category', $args['include']) ) {

		$tax = 'category';
		$tax_icon = $icons['category'];

		if ( get_post_type() == 'portfolio_item' ) { // requires Portfolios plugin
			$tax = 'portfolio';
			$tax_icon = $icons['portfolio'];
		}

		$category = get_the_term_list(get_the_ID(), $tax, '', ', ');

		if ( ! $category ) {
			$key = array_search('category', $args['include']);
			unset( $args['include'][$key] );
		}

	}

	// We can now check if there's even anything to include before
	// generating any output.
	$output = '';

	if ( $count = count( $args['include'] ) ) {

		// Separator
		$sep = $args['sep'];

		// CSS class
		$class = 'entry-meta';

		if ( $args['class'] ) {
			$class .= ' '.$args['class'];
		}

		$class .= ' clearfix';

		// Start output
		$output .= sprintf('<div class="%s">', $class);

		foreach ( $args['include'] as $key => $item ) {

			$item_output = '';

			switch ( $item ) {

				case 'author' :

					$author = esc_html( get_the_author() );
					$author_url = esc_url( get_author_posts_url( get_the_author_meta('ID') ) );
					$author_title = sprintf( esc_html__('View all posts by %s', 'jumpstart'), $author );
					$author_icon = in_array($item, $args['icons']) ? '<i class="fa fa-'.$icons['author'].'"></i>' : '';
					$item_output = sprintf( '<span class="byline author vcard">%s<a class="url fn n" href="%s" title="%s" rel="author">%s</a></span>', $author_icon, $author_url, $author_title, $author );
					break;

				case 'category' :

					$category_icon = in_array($item, $args['icons']) ? '<i class="fa fa-'.$tax_icon.'"></i>' : '';
					$item_output = sprintf( '<span class="category">%s%s</span>', $category_icon, $category );
					break;

				case 'comments' :

					$item_output = '<span class="comments-link">';

					ob_start();
					if ( $args['comments'] === 'mini' ) {
						comments_popup_link( '0', '1', '%' );
					} else {
						comments_popup_link( '<span class="leave-reply">'.themeblvd_get_local('no_comments').'</span>', '1 '.themeblvd_get_local('comment'), '% '.themeblvd_get_local('comments') );
					}
					$comment_link = ob_get_clean();

					if ( in_array( $item, $args['icons'] ) ) {
						$item_output .= '<i class="fa fa-'.$icons['comments'].'"></i>';
					}

					$item_output .= $comment_link;
					$item_output .= '</span>';
					break;

				case 'format' :

					if ( $format = get_post_format() ) {

						$format_icon = '';

						if ( in_array( $item, $args['icons'] ) ) {

							$format_icon = themeblvd_get_format_icon($format);

							if ( $format_icon ) {
								$format_icon = sprintf('<i class="fa fa-%s"></i>', $format_icon);
							}
						}

						$item_output .= sprintf( '<span class="post-format">%s%s</span>', $format_icon, themeblvd_get_local($format) );
					}
					break;

				case 'time' :

					$time_icon = in_array($item, $args['icons']) ? '<i class="fa fa-'.$icons['time'].'"></i>' : '';

					if ( $args['time'] === 'ago' ) {

						$item_output = sprintf(
							'<time class="entry-date updated" datetime="%s">%s%s</time>',
							esc_attr( get_the_time('c') ),
							$time_icon,
							esc_html( themeblvd_get_time_ago( get_the_ID() ) )
						);

					} else {

						$item_output = sprintf(
							'<time class="entry-date updated" datetime="%s">%s%s</time>',
							esc_attr( get_the_time('c') ),
							$time_icon,
							esc_html( get_the_time( get_option('date_format') ) )
						);

					}
					break;
			}

			if ( $item_output ) {

				$output .= $item_output;

				if ( $key+1 < $count ) {
					$output .= $sep;
				}
			}
		}

		$output .= '</div><!-- .entry-meta -->';

	}

	return apply_filters( 'themeblvd_meta', $output, $args );
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

	ob_start();
	the_tags('', '');
	$tags = ob_get_clean();

	if ( $tags ) {
		$output .= sprintf('<div class="tb-tags tags">%s</div><!-- .tb-tags (end) -->', $tags);
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

		$patterns = themeblvd_get_share_patterns();
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'tb_thumb' );
		$thumb = esc_url($thumb[0]);
		$permalink = get_permalink();
		$shortlink = wp_get_shortlink();
		$title = get_the_title();
		$excerpt = get_the_excerpt();

		$output .= '<ul class="tb-share clearfix">';

		foreach ( $buttons as $button ) {

			$network = $button['icon'];

			// Link class
			$class = 'btn-share tb-tooltip shutter-out-vertical '.$network;

			if ( $network != 'email' ) {
				$class .= ' popup';
			}

			// Link URL
			$link = '';

			if ( isset( $patterns[$network] ) ) {

				$link = $patterns[$network]['pattern'];

				if ( $patterns[$network]['encode_urls'] ) {
					$link = str_replace( '[permalink]', rawurlencode($permalink), $link );
					$link = str_replace( '[shortlink]', rawurlencode($shortlink), $link );
					$link = str_replace( '[thumbnail]', rawurlencode($thumb), $link );
				} else {
					$link = str_replace( '[permalink]', $permalink, $link );
					$link = str_replace( '[shortlink]', $shortlink, $link );
					$link = str_replace( '[thumbnail]', $thumb, $link );
				}

				if ( $patterns[$network]['encode'] ) {
					$link = str_replace( '[title]', rawurlencode($title), $link );
					$link = str_replace( '[excerpt]', rawurlencode($excerpt), $link );
				} else {
					$link = str_replace( '[title]', $title, $link );
					$link = str_replace( '[excerpt]', $excerpt, $link );
				}
			}

			$output .= sprintf( '<li><a href="%s" title="%s" class="%s" data-toggle="tooltip" data-placement="top"><i class="fa fa-fw fa-%s"></i></a></li>', $link, $button['label'], $class, $patterns[$network]['icon'] );
		}

		$output .= '</ul><!-- .tb-share (end) -->';

	}

	$output = apply_filters( 'themeblvd_blog_share', $output, get_the_ID(), $buttons );

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
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
		$module .= sprintf( '<li class="phone">%s</li>', themeblvd_kses($args['phone_1']) );
	}

	// Phone #2
	if ( ! empty( $args['phone_2'] ) ) {
		$module .= sprintf( '<li class="phone">%s</li>', themeblvd_kses($args['phone_2']) );
	}

	// Email #1
	if ( ! empty( $args['email_1'] ) ) {
		$module .= sprintf( '<li class="email"><a href="mailto:%s">%s</a></li>', esc_attr($args['email_1']), esc_html($args['email_1']) );
	}

	// Email #2
	if ( ! empty( $args['email_2'] ) ) {
		$module .= sprintf( '<li class="email"><a href="mailto:%s">%s</a></li>', esc_attr($args['email_2']), esc_html($args['email_2']) );
	}

	// Contact Page
	if ( ! empty( $args['contact'] ) ) {
		$module .= sprintf( '<li class="contact"><a href="%s">%s</a></li>', esc_url($args['contact']), themeblvd_get_local('contact_us') );
	}

	// Skype
	if ( ! empty( $args['skype'] ) ) {
		$module .= sprintf( '<li class="skype">%s</li>', themeblvd_kses($args['skype']) );
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

			$module .= sprintf( '<li class="%s"><a href="%s" target="_blank" title="%s">%s</a></li>', esc_attr($icon), esc_url($url), esc_attr($title), esc_html($title) );
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
 * Get moveable slider controls
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for slider controls
 * @return string $output Final content to output
 */
function themeblvd_get_slider_controls( $args = array() ) {

	$defaults = array(
		'carousel'		=> false,							// If using Bootstrap carousel, the ID
		'color'			=> 'primary',						// Color of buttons (use "trans" for transparent style)
		'direction' 	=> 'horz', 							// horz or vert
		'prev' 			=> themeblvd_get_local('previous'),	// Text for previous button
		'next' 			=> themeblvd_get_local('next'),		// Text for next button
    );
    $args = wp_parse_args( $args, $defaults );

    if ( $args['direction'] == 'horz' ) {

		if ( is_rtl() ) {
			$prev = 'right';
	    	$next = 'left';
		} else {
			$prev = 'left';
	    	$next = 'right';
		}

	} else {
		$prev = 'up';
		$next = 'down';
	}

    $output  = '<ul class="tb-slider-arrows">';
	$output .= sprintf( '<li><a href="#" title="%1$s" class="%2$s %3$s prev">%1$s</a></li>', $args['prev'], $prev, $args['color'] );
	$output .= sprintf( '<li><a href="#" title="%1$s" class="%2$s %3$s next">%1$s</a></li>', $args['next'], $next, $args['color'] );
	$output .= '</ul>';

	if ( $args['carousel'] ) {
		$output = str_replace( '#', '#'.$args['carousel'], $output );
		$output = str_replace( 'prev">', 'prev" data-slide="prev">', $output );
		$output = str_replace( 'next">', 'next" data-slide="next">', $output );
	}

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

	$class = 'tb-scroll-to-top';

	if ( $args['class'] ) {
		$class .= ' '.$args['class'];
	}

	$output = sprintf('<a href="#" class="%s">%s</a>', $class, themeblvd_get_local('top'));

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

/**
 * Get scroll to section button
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for button
 */
function themeblvd_get_to_section( $args = array() ) {

	$defaults = array(
		'to'	=> '', // ID of HTML element to jump to; if blank, go to next section
		'class'	=> ''
	);
	$args = wp_parse_args( $args, $defaults );

	$class = 'tb-scroll-to-section tb-scroll-to';

	if ( $args['class'] ) {
		$class .= ' '.esc_attr($args['class']);
	}

	$output = sprintf('<a href="#%s" class="%s">%s</a>', esc_attr($args['to']), $class, themeblvd_get_local('next'));

	return apply_filters( 'themeblvd_to_section', $output, $args );
}

/**
 * Display scroll to section button
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for button
 */
function themeblvd_to_section( $args = array() ) {
	echo themeblvd_get_to_section( $args );
}

/**
 * Get loader
 *
 * @since 2.5.0
 *
 * @return string $output Final content to output
 */
function themeblvd_get_loader() {
    return apply_filters( 'themeblvd_loader', '<div class="tb-loader"><span class="icon-1"></span><span class="icon-2"></span><span class="icon-3"></span></div>' );
}

/**
 * Display loader
 *
 * @since 2.5.0
 */
function themeblvd_loader() {
	echo themeblvd_get_loader();
}

/**
 * Get archive info box
 *
 * @since 2.5.0
 *
 * @param string $tax Slug of taxonomy
 * @param string $term Slug of term
 * @return string $output Final content to output
 */
function themeblvd_get_tax_info() {

	global $wp_query;

	if ( ! is_category() && ! is_tag() && ! is_tax() ) {
		return;
	}

	if ( get_query_var('paged') >= 2 ) {
		return;
	}

	if ( is_tax() ) {
		$term = get_term_by( 'slug', get_query_var('term'), get_query_var('taxonomy') );
	} else {
		$term = $wp_query->get_queried_object();
	}

	if ( ! $term ) {
		return;
	}

	$output = $name = $desc = '';

	$name = apply_filters('themeblvd_tax_info_name', esc_html($term->name), $term);

	if ( $name ) {
		$name = sprintf( '<h1 class="info-box-title archive-title">%s</h1>', $name );
	}

	if ( $term->description ) {
		$desc = apply_filters('themeblvd_tax_info_desc', themeblvd_get_content($term->description));
	}

	$class = apply_filters('themeblvd_tax_info_class', 'tb-info-box tb-tax-info'); // Filtering to allow "content-bg" to be added

	if ( $name || $desc ) {
		$output = sprintf( '<section class="%s"><div class="inner">%s</div></section>', $class, $name.$desc );
	}

	return apply_filters( 'themeblvd_tax_info', $output, $term );
}

/**
 * Display archive info box
 *
 * @since 2.5.0
 */
function themeblvd_tax_info() {
	echo themeblvd_get_tax_info();
}

/**
 * Get author box
 *
 * @since 2.5.0
 *
 * @return string $output Final content to output
 */
function themeblvd_get_author_info( $user = null, $context = 'single' ) {

	$gravatar_size = apply_filters('themeblvd_author_box_gravatar_size', 70);
	$gravatar = get_avatar( $user->user_email, $gravatar_size );
	$desc = get_user_meta( $user->ID, 'description', true );

	$class = apply_filters('themeblvd_tax_info_class', 'tb-info-box tb-author-box '.$context); // Filtering to allow "content-bg" to be added

	$output = sprintf('<section class="%s">', $class);

	// Title
	$title = apply_filters('themeblvd_author_info_title', esc_html($user->display_name), $user);

	if ( $context == 'archive' ) {
		$output .= sprintf('<h1 class="info-box-title archive-title">%s</h1>', $title);
	} else {
		$output .= sprintf('<h3 class="info-box-title">%s</h3>', $title);
	}

	$output .= '<div class="inner">';

	// User info
	if ( $gravatar ) {
		$class = apply_filters('themeblvd_author_box_avatar_class', 'avatar-wrap');
		$output .= sprintf('<div class="%s">%s</div>', $class, $gravatar);
	}

	// Link to archive of user posts
	if ( $context == 'single' && get_user_meta( $user->ID, '_tb_box_archive_link', true ) === '1' ) {
		$text = sprintf(themeblvd_get_local('view_posts_by'), esc_html($user->display_name));
		$desc .= "\n\n";
		$desc .= sprintf('<a href="%s" class="view-posts-link">%s</a>', esc_url( get_author_posts_url($user->ID) ), $text);
	}

	if ( $desc ) {
		$output .= themeblvd_get_content($desc);
	}

	// Contact icons
	$style = get_user_meta( $user->ID, '_tb_box_icons', true );

	if ( ! $style ) {
		if ( themeblvd_supports('display', 'dark') ) {
			$style = 'light';
		} else {
			$style = 'grey';
		}
	}

	$icons = Theme_Blvd_User_Options::get_icons();
	$display = array();

	if ( get_user_meta( $user->ID, '_tb_box_email', true ) === '1' ) {
		$display[] = array(
			'icon' 		=> 'email',
			'url' 		=> 'mailto:'.$user->user_email,
			'label' 	=> themeblvd_get_local('email'),
			'target' 	=> '_self'
		);
	}

	foreach ( $icons as $icon_id => $info ) {

		$url = get_user_meta( $user->ID, $info['key'], true );

		if ( $icon_id == 'website' ) {
			$icon_id = 'anchor';
			$url = $user->user_url;
		}

		if ( $url ) {

			$display[$icon_id] = array(
				'icon' 		=> $icon_id,
				'url' 		=> $url,
				'target' 	=> '_blank'
			);

			if ( $icon_id == 'anchor' ) {
				$display[$icon_id]['label'] = $icons['website']['label'];
			} else {
				$display[$icon_id]['label'] = $icons[$icon_id]['label'];
			}
		}

	}

	if ( $display ) {
		$output .= themeblvd_get_contact_bar( $display, array('style' => $style, 'tooltip' => 'top', 'class' => 'author-box', 'authorship' => true) );
	}

	$output .= '</div><!-- .inner (end) -->';
	$output .= '</section><!-- .tb-author-box (end) -->';

	return apply_filters( 'themeblvd_author_info', $output, $user, $context );
}

/**
 * Display author info box
 *
 * @since 2.5.0
 */
function themeblvd_author_info( $user = null, $context = 'single' ) {
	echo themeblvd_get_author_info($user, $context);
}

/**
 * Get related posts
 *
 * @since 2.5.0
 */
function themeblvd_get_related_posts( $args = array() ) {

	$defaults = apply_filters('themeblvd_related_posts_args', array(
		'post_id'		=> themeblvd_config('id'),
		'post_type'		=> 'post',
		'columns' 		=> 2,
		'grid_columns'	=> 3,
		'grid_rows'		=> 2,
		'total'			=> 6,
		'related_by'	=> themeblvd_get_option('single_related_posts', null, 'tag'),
		'style'			=> themeblvd_get_option('single_related_posts_style', null, 'list'),
		'thumbs'		=> 'smaller',
		'meta'			=> true,
		'query'			=> ''
	));
	$args = wp_parse_args( $args, $defaults );

	if ( get_post_type() != $args['post_type'] ) {
		return '';
	}

	if ( ! $args['query'] ) {

		$args['query'] = array(
            'post_type' 			=> $args['post_type'],
            'post__not_in'			=> array($args['post_id']),
            'orderby'				=> 'rand',
            'ignore_sticky_posts'	=> 1,
            'posts_per_page'		=> $args['total']
        );

		if ( $args['related_by'] == 'tag' ) {
			$args['related_by'] = 'post_tag';
		}

		$terms = array();
		$term_obj = get_the_terms( $args['post_id'], $args['related_by'] );

		if ( $term_obj && ! is_wp_error($term_obj) ) {
			foreach ( $term_obj as $term ) {
				$terms[] = $term->term_id;
			}
		}

		if ( $terms ) {
			$args['query']['tax_query'] = array(
				array(
					'taxonomy'	=> $args['related_by'],
					'field'		=> 'term_id',
					'terms'		=> $terms
				)
			);
		}

		$args['query'] = apply_filters( 'themeblvd_related_posts_query', $args['query'], $args );

	}

	$output = '<section class="tb-related-posts">';
	$output .= sprintf('<h2 class="related-posts-title">%s</h2>', themeblvd_get_local('related_posts'));
	$output .= '<div class="inner">';

	if ( $args['style'] == 'list' ) {

		$output .= themeblvd_get_mini_post_list( $args, $args['thumbs'], $args['meta'] );

	} else {

		$args['columns'] = $args['grid_columns'];
		$args['rows'] = $args['grid_rows'];

		$output .= themeblvd_get_small_post_grid( $args );

	}

	$output .= '</div><!-- .inner (end) -->';
	$output .= '</section><!-- .tb-related-posts (end) -->';

	return apply_filters( 'themeblvd_related_posts', $output, $args );
}

/**
 * Display related posts
 *
 * @since 2.5.0
 */
function themeblvd_related_posts( $args = array() ) {
	echo themeblvd_get_related_posts($args);
}

/**
 * Get menu of post types to refine search results
 *
 * @since 2.5.0
 */
function themeblvd_get_refine_search_menu() {

	$output = '';
	$types = themeblvd_get_search_types();

	if ( $types ) {

		$active = 'all';

		if ( ! empty($_GET['s_type']) ) {
			$active = $_GET['s_type'];
		}

		$url = add_query_arg( array( 's' => str_replace( ' ', '+', get_search_query() ) ), themeblvd_get_home_url() );

		$output .= '<div class="tb-inline-menu">';
		$output .= '<ul class="list-inline search-refine-menu">';

		if ( $active == 'all' ) {
			$output .= sprintf( '<li><span class="active">%s</span></li>', themeblvd_get_local('all') );
		} else {
			$output .= sprintf( '<li><a href="%s">%s</a></li>', esc_url($url), themeblvd_get_local('all') );
		}

		foreach ( $types as $type => $name ) {
			if ( $active == $type ) {
				$output .= sprintf( '<li><span class="active">%s</span></li>', esc_html($name) );
			} else {
				$url = add_query_arg( array( 's_type' => esc_attr($type) ), $url );
				$output .= sprintf( '<li><a href="%s">%s</a></li>', esc_url($url), esc_html($name) );
			}
		}

		$output .= '</ul>';
		$output .= '</div><!-- .tb-inline-mini (end) -->';

	}

	return apply_filters( 'themeblvd_refine_search_menu', $output );
}

/**
 * Display menu of post types to refine search results
 *
 * @since 2.5.0
 */
function themeblvd_refine_search_menu() {
	echo themeblvd_get_refine_search_menu();
}

/**
 * Get navigation to filter post results
 *
 * @since 2.5.0
 */
function themeblvd_get_filter_nav( $posts, $tax = 'category', $args = array() ) {

	$output = '';
	$terms = array();

	if ( ! is_a( $posts, 'WP_Query' ) ) {
		return $output;
	}

	$defaults = apply_filters('themeblvd_filter_nav_args', array(
		// ...
	));
	$args = wp_parse_args( $args, $defaults );

	if ( $posts->have_posts() ) {
		while ( $posts->have_posts() ) {

			$posts->the_post();
			$current = get_the_terms( get_the_ID(), $tax );

			if ( $current ) {
				foreach ( $current as $term ) {
					$terms[$term->slug] = $term->name;
				}
			}
		}

	}

	if ( $terms ) {

		asort($terms);

		$terms = apply_filters( 'themeblvd_filter_nav_terms', $terms, $posts, $tax, $args );

		$output .= '<div class="tb-inline-menu tb-filter-nav">';
		$output .= '<ul class="list-inline filter-menu">';
		$output .= sprintf('<li class="active"><a href="#" data-filter=".iso-item" title="%1$s">%1$s</a></li>', themeblvd_get_local('all'));

		foreach ( $terms as $key => $value ) {
			$key = esc_attr( preg_replace('/[^a-zA-Z0-9._\-]/', '', $key ) ); // Allow non-latin characters, and still work with jQuery
			$output .= sprintf('<li><a href="#" data-filter=".filter-%1$s" title="%2$s">%2$s</a></li>', $key, esc_html($value));
		}

		$output .= '</ul>';
		$output .= '</div><!-- .tb-inline-mini (end) -->';

	}

	wp_reset_postdata();

	return apply_filters( 'themeblvd_filter_nav', $output, $posts, $tax, $args );
}

/**
 * Display navigation to filter showcase results
 *
 * @since 2.5.0
 */
function themeblvd_filter_nav( $posts, $tax = 'category', $args = array() ) {
	echo themeblvd_get_filter_nav($posts, $tax, $args);
}

/**
 * Get date block to replace of a featured image.
 *
 * @since 2.5.0
 */
function themeblvd_get_date_block( $post_id = 0 ) {

	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$output  = '<div class="tb-date-block">';
	$output .= sprintf( '<span class="bg-primary day">%s</span>', esc_html( get_the_date( 'd', $post_id ) ) );
	$output .= sprintf( '<span class="month">%s</span>', esc_html( get_the_date( 'M', $post_id ) ) );
	$output .= '</div><!-- .tb-date-block (end) -->';

	return apply_filters( 'themeblvd_date_block', $output, $post_id );

}

/**
 * Display date block in place of a featured image.
 *
 * @since 2.5.0
 */
function themeblvd_date_block( $post_id = 0 ) {
	echo themeblvd_get_date_block( $post_id );
}

/**
 * Get title and category/portfolio for post showcase hover.
 *
 * @since 2.6.0
 */
function themeblvd_get_item_info( $post_id = 0, $tax = '' ) {

	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$post_type = get_post_type($post_id);

	$output = '<span class="item-title">';
	$output .= sprintf( '<span class="title">%s</span>', esc_html( get_the_title($post_id) ) );

	if ( ! $tax ) {

		$tax = 'category';

		if ( $post_type == 'portfolio_item' ) {
			$tax = 'portfolio';
		}
	}

	$tax = apply_filters( 'themeblvd_item_info_tax', $tax, $post_type, $post_id );

	$terms_obj = get_the_terms( $post_id, $tax );

	if ( $terms_obj ) {

		$terms = array();

		foreach ( $terms_obj as $term ) {
			$terms[] = $term->name;
		}

		$output .= sprintf( '<span class="cat">%s</span>', esc_html( implode($terms, ', ') ) );

	}

	$output .= '</span>';

	return apply_filters('themeblvd_item_info', $output);
}

/**
 * Display title and category/portfolio for post showcase hover.
 * Must be within the loop.
 *
 * @since 2.6.0
 */
function themeblvd_item_info( $post_id = 0, $tax = '' ) {
	echo themeblvd_get_item_info( $post_id, $tax );
}
