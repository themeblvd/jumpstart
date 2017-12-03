<?php
/**
 * Frontend Blocks: Smaller Parts
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.5.0
 */

/**
 * Get a contact button bar block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $buttons {
 *     All links in contact bar.
 *
 *     @type array {
 *          Structure for each individual link.
 *
 *          @type string $icon   Icon name, like `twitter`.
 *          @type string $url    Link URL, like `http://twitter.com/username`.
 *          @type string $label  Link label, like `Twitter` or `Follow us on Twitter`.
 *          @type string $target Link target, like `_self` or `_blank`.
 *     }
 * }
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string      $style      Style of contact bar, `color`, `grey`, `light`, `dark`, `flat`.
 *     @type string|bool $tooltip    Tooltip location, like `top` or `bottom`; use string 'disable' or boolean FALSE to disable.
 *     @type string      $class      CSS classes to add, like `foo bar baz`.
 *     @type bool        $authorship Whether to add ?rel=author to Google+.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_contact_bar( $buttons = array(), $args = array() ) {

	if ( ! $buttons ) {

		$buttons = themeblvd_get_option( 'social_media' );

	}

	/**
	 * Filters the default arguments for a contact bar.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param array $args {
	 *     Default arguments for a contact bar.
	 *
	 *     @type string      $style      Style of contact bar, `color`, `grey`, `light`, `dark`, `flat`.
	 *     @type string|bool $tooltip    Tooltip location, like `top` or `bottom`; use string 'disable' or boolean FALSE to disable.
	 *     @type string      $class      CSS classes to add, like `foo bar baz`.
	 *     @type bool        $authorship Whether to add ?rel=author to Google+.
	 * }
	 */
	$defaults = apply_filters( 'themeblvd_contact_bar_defaults', array(
		'style'      => themeblvd_get_option( 'social_media_style', null, 'flat' ),
		'tooltip'    => 'bottom',
		'class'      => '',
		'authorship' => false,
	));

	$args = wp_parse_args( $args, $defaults );

	$output = '';

	if ( $buttons && is_array( $buttons ) ) {

		$class = 'themeblvd-contact-bar tb-social-icons ' . $args['style'];

		if ( $args['class'] ) {

			$class .= ' ' . $args['class'];

		}

		$class .= ' clearfix';

		$output .= '<ul class="' . esc_attr( $class ) . '">';

		foreach ( $buttons as $button ) {

			$class = 'tb-icon ' . $button['icon'];

			if ( $args['tooltip'] && 'disable' !== $args['tooltip'] ) {

				$class .= ' tb-tooltip';

			}

			$title = '';

			if ( ! empty( $button['label'] ) ) {

				$title = $button['label'];

			}

			$title = str_replace( '[url]', $button['url'], $title );

			$title = str_replace( array( 'http://', 'https://' ), '', $title );

			// Add Google+ authorship.
			if ( $args['authorship'] && 'google' === $button['icon'] ) {

				if ( false === strpos( $button['url'], '?rel=author' ) ) {

					$button['url'] .= '?rel=author';

				}
			}

			if ( 'color' === $args['style'] ) {

				$content = $title;

			} else {

				/*
				 * Convert icon names to FontAwesome icon names.
				 *
				 * Most icon ID's already match FontAwesome by default,
				 * but below we'll convert the ones that need to be
				 * converted.
				 */
				$icon = $button['icon'];

				switch ( $button['icon'] ) {

					case 'chat':
						$icon = 'comments';
						break;

					case 'email':
						$icon = 'envelope';
						break;

					case 'anchor':
						$icon = 'link';
						break;

					case 'movie':
						$icon = 'film';
						break;

					case 'portfolio':
						$icon = 'briefcase';
						break;

					case 'store':
						$icon = 'shopping-basket';
						break;

					case 'write':
						$icon = 'pencil';
						break;

					case 'github':
						$icon = 'github-alt';
						break;

					case 'google':
						$icon = 'google-plus';
						break;

					case 'pinterest':
						$icon = 'pinterest-p';
						break;

					case 'vimeo':
						$icon = 'vimeo-square';

				}

				$content = sprintf( '<i class="fa fa-fw fa-%s"></i>', $icon );

			}

			$output .= sprintf(
				'<li class="li-%s"><a href="%s" title="%s" class="%s" target="%s" data-toggle="tooltip" data-placement="%s">%s</a></li>',
				esc_attr( $button['icon'] ),
				esc_url( $button['url'] ),
				esc_attr( $title ),
				esc_attr( $class ),
				esc_attr( $button['target'] ),
				esc_attr( $args['tooltip'] ),
				themeblvd_kses( $content )
			);

		}

		$output .= '</ul><!-- .themeblvd-contact-bar -->';

	}

	/**
	 * Filters the final HTML output for a contact
	 * bar block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $buttons {
	 *     All links in contact bar.
	 *
	 *     @type array {
	 *          Structure for each individual link.
	 *
	 *          @type string $icon   Icon name, like `twitter`.
	 *          @type string $url    Link URL, like `http://twitter.com/username`.
	 *          @type string $label  Link label, like `Twitter` or `Follow us on Twitter`.
	 *          @type string $target Link target, like `_self` or `_blank`.
	 *     }
	 * }
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string      $style      Style of contact bar, `color`, `grey`, `light`, `dark`, `flat`.
	 *     @type string|bool $tooltip    Tooltip location, like `top` or `bottom`; use string 'disable' or boolean FALSE to disable.
	 *     @type string      $class      CSS classes to add, like `foo bar baz`.
	 *     @type bool        $authorship Whether to add ?rel=author to Google+.
	 * }
	 */
	return apply_filters( 'themeblvd_contact_bar', $output, $buttons, $args );

}

/**
 * Display a contact bar block.
 *
 * Note: In most cases, the $trans parameter should be
 * set to TRUE if this contact bar is being displayed
 * in the header of the website.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param array $buttons All links in contact bar, see themeblvd_get_contact_bar() docs.
 * @param array $args    Block arguments, see themeblvd_get_contact_bar() docs.
 * @param bool  $trans   Whether to include transparent-compatible version when transparent header is being used.
 */
function themeblvd_contact_bar( $buttons = array(), $args = array(), $trans = true ) {

	echo themeblvd_get_contact_bar( $buttons, $args );

	if ( $trans && themeblvd_config( 'suck_up' ) ) {

		$args = wp_parse_args( array(
			'style' => themeblvd_get_option( 'trans_social_media_style', null, 'flat' ),
			'class' => 'social-trans',
		), $args );

		echo themeblvd_get_contact_bar( themeblvd_get_option( 'trans_social_media' ), $args );

	}
}

/**
 * Get a floating searchform block.
 *
 * Note: While you can have multiple triggers, it'll work
 * best if there is only one floating search bar on the site.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $args   Block arguments, but not currently used.
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_floating_search( $args = array() ) {

	$output  = '<div class="tb-floating-search">';

	$output .= '<div class="wrap">';

	$output .= '<a href="#" title="' . themeblvd_get_local( 'close' ) . '" class="close-search">x</a>';

	$output .= get_search_form( false );

	$output .= '</div><!-- .wrap (end) -->';

	$output .= '</div><!-- .tb-floating-search (end) -->';

	/**
	 * Filters the final HTML output for a floating
	 * searchform block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args   Block arguments, but not currently used.
	 */
	return apply_filters( 'themeblvd_floating_search', $output, $args );

}

/**
 * Display a floating searchform block.
 *
 * This function is only meant to be called once for a
 * website. And by default, it's hooked to
 * `themeblvd_after` at priority 10.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args Block arguments, but not currently used.
 */
function themeblvd_floating_search( $args = array() ) {

	if ( themeblvd_do_floating_search() ) {

		echo themeblvd_get_floating_search( $args );

	}

}

/**
 * Get a link to display the floating searchform
 * block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $args {
 *     Link arguments.
 *
 *     @type string $open  FontAwesome icon name used for link.
 *     @type string $class CSS classes to add to link, like `foo bar baz`.
 * }
 * @return string $output Final HTML output for link.
 */
function themeblvd_get_floating_search_trigger( $args = array() ) {

	/**
	 * Filters the default arguments for a link to
	 * open a floating searchform.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param array {
	 *     Default link arguments.
	 *
	 *     @type string $open  FontAwesome icon name used for link.
	 *     @type string $class CSS classes to add to link, like `foo bar baz`.
	 * }
	 */
	$defaults = apply_filters( 'themeblvd_floating_search_trigger_defaults', array(
		'open'  => 'search',
		'class' => '',
	));

	$args = wp_parse_args( $args, $defaults );

	$class = 'tb-search-trigger';

	if ( $args['class'] ) {

		$class .= ' ' . $args['class'];

	}

	$output = sprintf( '<a href="#" class="%s"><i class="fa fa-%s"></i></a>', esc_attr( $class ), esc_attr( $args['open'] ) );

	/**
	 * Filters the final HTML output for a link to display
	 * the floating searchform block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output of link.
	 * @param array  $args {
	 *     Link arguments.
	 *
	 *     @type string $open  FontAwesome icon name used for link.
	 *     @type string $class CSS classes to add to link, like `foo bar baz`.
	 * }
	 */
	return apply_filters( 'themeblvd_floating_search_trigger', $output, $args );

}

/**
 * Display a link to display the floating searchform
 * block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args Link arguments, see themeblvd_get_floating_search_trigger() docs.
 */
function themeblvd_floating_search_trigger( $args = array() ) {

	echo themeblvd_get_floating_search_trigger( $args );

}

/**
 * Get a link to display the side panel.
 *
 * @since Theme_Blvd 2.6.0
 *
 * @param  array  $args {
 *     Link arguments.
 *
 *     @type string $class CSS classes to add to link, like `foo bar baz`.
 * }
 * @return string $output Final HTML output for link.
 */
function themeblvd_get_side_trigger( $args = array() ) {

	/**
	 * Filters the default arguments for a link to
	 * open the side panel.
	 *
	 * @since Theme_Blvd 2.6.0
	 *
	 * @param array {
	 *     Default link arguments.
	 *
	 *     @type string $class CSS classes to add to link, like `foo bar baz`.
	 * }
	 */
	$defaults = apply_filters( 'themeblvd_side_trigger_defaults', array(
		'class' => '',
	));

	$args = wp_parse_args( $args, $defaults );

	$class = 'tb-side-trigger';

	if ( $args['class'] ) {

		$class .= ' ' . $args['class'];

	}

	$output  = sprintf( "<a href=\"#\" class=\"%s\">\n", $class );

	$output .= "\t<span class=\"hamburger\">\n";

	$output .= "\t\t<span class=\"top\"></span>\n";

	$output .= "\t\t<span class=\"middle\"></span>\n";

	$output .= "\t\t<span class=\"bottom\"></span>\n";

	$output .= "\t</span>\n";

	$output .= "</a>\n";

	/**
	 * Filters the final HTML output for a link to
	 * display the side panel
	 *
	 * @since Theme_Blvd 2.6.0
	 *
	 * @param string $output Final HTML output of link.
	 * @param array  $args {
	 *     Link arguments.
	 *
	 *     @type string $class CSS classes to add to link, like `foo bar baz`.
	 * }
	 */
	return apply_filters( 'themeblvd_side_trigger', $output, $args );

}

/**
 * Display a link to open the side panel.
 *
 * @since Theme_Blvd 2.6.0
 *
 * @param array $args Link arguments, see themeblvd_get_side_trigger() docs.
 */
function themeblvd_side_trigger( $args = array() ) {

	echo themeblvd_get_side_trigger( $args );

}

/**
 * Get the floating shopping cart block, which
 * shows in a modal.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type bool   $trigger Whether to include trigger link in returned output.
 *     @type string $size    Size of floating search block, `sm`, `md` or `lg`.
 *     @type string $id      HTML ID of search modal block.
 *     @type string $class   CSS classes to add, like `foo bar baz`.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_cart_popup( $args = array() ) {

	/**
	 * Filters the default arguments for the floating
	 * shopping cart block, which shows in a modal.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param  array  $args {
	 *     Default block arguments.
	 *
	 *     @type bool   $trigger Whether to include trigger link in returned output.
	 *     @type string $size    Size of floating search block, `sm`, `md` or `lg`.
	 *     @type string $id      HTML ID of search modal block.
	 *     @type string $class   CSS classes to add, like `foo bar baz`.
	 * }
	 */
	$defaults = apply_filters( 'themeblvd_cart_popup_defaults', array(
		'trigger' => false,
		'size'    => 'sm',
		'id'      => 'floating-shopping-cart',
		'class'   => '',
	));

	$args = wp_parse_args( $args, $defaults );

	$output = '';

	if ( $args['trigger'] ) {

		$output .= themeblvd_get_cart_popup_trigger( array(
			'target' => $args['id'],
		));

	}

	$class = 'tb-cart-popup modal fade';

	if ( $args['class'] ) {

		$class .= ' ' . $args['class'];

	}

	$output .= '<div id="' . $args['id'] . '" class="' . $class . '">';

	$output .= '<div class="modal-dialog modal-' . $args['size'] . '">';

	$output .= '<div class="modal-content">';

	$output .= '<div class="modal-header">';

	$output .= '<button type="button" class="close" data-dismiss="modal" aria-label="' . themeblvd_get_local( 'close' ) . '"><span aria-hidden="true">&times;</span></button>';

	$output .= '<h4 class="modal-title">' . themeblvd_get_local( 'cart' ) . '</h4>';

	$output .= '</div>';

	$output .= '<div class="modal-body clearfix">';

	ob_start();

	/**
	 * Fires within the modal meant to display the floating
	 * shopping cart.
	 *
	 * @hooked Theme_Blvd_Compat_WooCommerce::cart - 10 - (If WooCommerce is activated)
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param array $args {
	 *     Block arguments.
	 *
	 *     @type bool   $trigger Whether to include trigger link in returned output.
	 *     @type string $size    Size of floating search block, `sm`, `md` or `lg`.
	 *     @type string $id      HTML ID of search modal block.
	 *     @type string $class   CSS classes to add, like `foo bar baz`.
	 * }
	 */
	do_action( 'themeblvd_floating_cart', $args );

	$output .= ob_get_clean();

	$output .= '</div><!-- .modal-body (end) -->';

	$output .= '</div><!-- .modal-content (end) -->';

	$output .= '</div><!-- .modal-dialog (end) -->';

	$output .= '</div><!-- .tb-cart-popup (end) -->';

	/**
	 * Filters the final HTML output for the floating
	 * shopping cart block, which shows in a modal.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type bool   $trigger Whether to include trigger link in returned output.
	 *     @type string $size    Size of floating search block, `sm`, `md` or `lg`.
	 *     @type string $id      HTML ID of search modal block.
	 *     @type string $class   CSS classes to add, like `foo bar baz`.
	 * }
	 */
	return apply_filters( 'themeblvd_cart_popup', $output, $args );

}

/**
 * Display the floating shopping cart block,
 * which shows in a modal.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_cart_popup() docs.
 */
function themeblvd_cart_popup( $args = array() ) {

	echo themeblvd_get_cart_popup( $args );

}

/**
 * Get a link to display the floating shopping
 * cart block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $args {
 *     Link arguments.
 *
 *     @type string     $id     Optional. An HTML ID to be used on the link.
 *     @type string     $icon   FontAwesome icon name used for link display, like `shopping-basket`.
 *     @type string     $class  CSS classes to add, like `foo bar baz`.
 *     @type string     $target A unique ID for shopping cart modal being linked to, if using more than one.
 *     @type string     $url    Fallback URL of link, if JavaScript isn't working to bring up floating cart.
 *     @type string|int $count  The number of items currently in the shopping cart.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_cart_popup_trigger( $args = array() ) {

	// Is WooCommerce plugin active and are we supporting it?
	$woocommerce = false;

	if ( themeblvd_installed( 'woocommerce' ) && themeblvd_supports( 'plugins', 'woocommerce' ) ) {

		$woocommerce = true;

	}

	/**
	 * Filters the default arguments used for the link
	 * to the floating shopping cart modal.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param array $args {
	 *     Default link arguments.
	 *
	 *     @type string     $id     Optional. An HTML ID to be used on the link.
	 *     @type string     $icon   FontAwesome icon name used for link display, like `shopping-basket`.
	 *     @type string     $class  CSS classes to add, like `foo bar baz`.
	 *     @type string     $target A unique ID for shopping cart modal being linked to, if using more than one.
	 *     @type string     $url    Fallback URL of link, if JavaScript isn't working to bring up floating cart.
	 *     @type string|int $count  The number of items currently in the shopping cart.
	 * }
	 */
	$defaults = apply_filters( 'themeblvd_cart_popup_trigger_defaults', array(
		'id'     => '',
		'icon'   => 'shopping-basket',           // FontAwesome icon to open.
		'class'  => '',                          // Optional CSS classes to add.
		'target' => 'floating-shopping-cart',    // HTML ID of floating shopping cart linking to.
		'url'    => '#',                         // Cart URL.
		'count'  => '',                          // Cart item count.
	));

	$args = wp_parse_args( $args, $defaults );

	if ( ! empty( $args['open'] ) ) {

		$args['icon'] = $args['open']; // Backwards compat.

	}

	/*
	 * Disable floating cart on WooCommerce cart and checkout
	 * pages.
	 *
	 * This is for general usability and to combat WooCommerce
	 * not outputting cart contents correctly in these
	 * scenarios.
	 */
	if ( $woocommerce ) {

		if ( is_cart() || is_checkout() ) {

			$args['target'] = null;

		}
	}

	$url = $args['url'];

	/*
	 * For when cart is disabled, we use wc_get_cart_url()
	 * to get the full URL to the shopping cart page.
	 */
	if ( $woocommerce ) {

		if ( ! $args['target'] ) {

			$url = wc_get_cart_url();

		}
	}

	/**
	 * Filters the URL used in the link to the floating
	 * shopping cart.
	 *
	 * This is the URL you want linked to, in the event
	 * that the floating shopping cart is disabled or
	 * isn't working.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $url URL to the shopping cart page.
	 */
	$url = apply_filters( 'themeblvd_cart_url', $url );

	$count = null;

	if ( $woocommerce ) {

		$count = WC()->cart->get_cart_contents_count();

	}

	/**
	 * Filters the count of the number of items currently
	 * in the shopping cart.
	 *
	 * This number will get displayed with the link.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param int $count Total number of items currently in the cart.
	 */
	$count = apply_filters( 'themeblvd_cart_count', $count );

	$class = 'tb-cart-trigger menu-btn';

	if ( $count ) {

		$class .= ' has-label char-' . strlen( strval( $count ) );

	}

	if ( $woocommerce ) {

		if ( $args['target'] ) {

			$class .= ' tb-woocommerce-cart-popup-link';

		} else {

			$class .= ' tb-woocommerce-cart-page-link';

		}
	}

	if ( $args['target'] ) {

		$class .= ' has-popup';

	} else {

		$class .= ' no-popup';

	}

	if ( $args['class'] ) {

		$class .= ' ' . $args['class'];

	}

	$output = '<a href="' . esc_url( $url ) . '" class="' . esc_attr( $class ) . '"';

	if ( $args['id'] ) {

		$output .= ' id="' . $id . '"';

	}

	if ( $args['target'] ) {

		$output .= ' data-toggle="modal" data-target="#' . esc_attr( $args['target'] ) . '"';

	}

	$output .= '>';

	$output .= '<i class="fa fa-' . esc_attr( $args['icon'] ) . '"></i>';

	if ( $count ) {

		$output .= sprintf( '<span class="trigger-label">%s</span>', $count );

	}

	$output .= '</a>';

	/**
	 * Filters a link to display the floating shopping
	 * cart block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Link arguments.
	 *
	 *     @type string     $id     Optional. An HTML ID to be used on the link.
	 *     @type string     $icon   FontAwesome icon name used for link display, like `shopping-basket`.
	 *     @type string     $class  CSS classes to add, like `foo bar baz`.
	 *     @type string     $target A unique ID for shopping cart modal being linked to, if using more than one.
	 *     @type string     $url    Fallback URL of link, if JavaScript isn't working to bring up floating cart.
	 *     @type string|int $count  The number of items currently in the shopping cart.
	 * }
	 */
	return apply_filters( 'themeblvd_cart_popup_trigger', $output, $args );

}

/**
 * Display a link to display the floating shopping
 * cart block.
 *
 * @since Theme_Blvd 2.5.0
 */
function themeblvd_cart_popup_trigger( $args = array() ) {

	echo themeblvd_get_cart_popup_trigger( $args );

}

/**
 * Get link to shopping cart page, intended for
 * mobile only.
 *
 * When we're at higher viewports we can rely on
 * JavaScript to display our nice, little floating
 * shopping cart. But on mobile, we avoid this by
 * using this function to display a button that just
 * always links to the shopping cart page.
 *
 * @since Theme_Blvd 2.5.0
 */
function themeblvd_get_mobile_cart_link() {

	global $woocommerce;

	$cart_url = '';

	$cart_label = '';

	if ( themeblvd_installed( 'woocommerce' ) ) {

		$cart_url = wc_get_cart_url();

		$count = WC()->cart->get_cart_contents_count();

		if ( $count ) {

			$cart_label = sprintf( '<span class="cart-count">%s</span>', $count );

		}
	}

	$output = sprintf( '<a href="%s" id="mobile-to-cart" class="btn-navbar cart">%s%s</a>', esc_url( apply_filters( 'themeblvd_cart_url', $cart_url ) ), themeblvd_kses( apply_filters( 'themeblvd_btn_navbar_cart_text', '<i class="fa fa-shopping-basket"></i>' ) ), themeblvd_kses( $cart_label ) );

	/**
	 * Filters link to shopping cart page, intended for
	 * mobile only.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output     Final HTML output.
	 * @param string $cart_url   URL to shopping cart page, like `http://yoursite.com/cart`.
	 * @param string $cart_label Additional HTML to append to cart link.
	 */
	return apply_filters( 'themeblvd_mobile_cart_link', $output, $cart_url, $cart_label );
}

/**
 * Display link to shopping cart page, intended for
 * mobile only.
 *
 * @since Theme_Blvd 2.5.0
 */
function themeblvd_mobile_cart_link() {

	echo themeblvd_get_mobile_cart_link();

}

/**
 * Get header text.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $text   Text to display; when left blank, pulls from `header_text` theme option.
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_header_text( $text = '' ) {

	$output = '';

	if ( ! $text ) {

		$text = themeblvd_get_option( 'header_text' );

	}

	/**
	 * Filters the raw header text.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $text Raw header text.
	 */
	$text = apply_filters( 'themeblvd_header_text', $text );

	if ( $text ) {

		$output = sprintf( '<div class="header-text to-mobile">%s</div>', themeblvd_kses( $text ) );

	}

	/**
	 * Filters the final header text output, with
	 * wrapping markup.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param string $text   Raw header text.
	 */
	return apply_filters( 'themeblvd_header_text_output', $output, $text );

}

/**
 * Display header text.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param string $text Text to display; when left blank, pulls from `header_text` theme option.
 */
function themeblvd_header_text( $text = '' ) {

	echo themeblvd_get_header_text( $text );

}

/**
 * Get a button block.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param  string $text        Text to show in button.
 * @param  string $color       Color class of button.
 * @param  string $url         URL where the button links to.
 * @param  string $target      Anchor tag's target, `_self`, `_blank` or `lightbox`.
 * @param  string $size        Size of button, `small`, `medium`, `default`, `large`, `x-large`, `xx-large` or `xxx-large`.
 * @param  string $classes     CSS classes to add, like `foo bar baz`.
 * @param  string $title       Title for anchor tag, will default to $text if blank.
 * @param  string $icon_before FontAwesome icon name before text, like `pencil`.
 * @param  string $icon_after  FontAwesome icon name after text, like `pencil`.
 * @param  string $addon       Anything to add into the <a> tag, like `data-foo="bar"`.
 * @param  bool   $block       Whether the button displays as block-level.
 * @return string $output      Final HTML output for button.
 */
function themeblvd_button( $text, $url, $color = 'default', $target = '_self', $size = null, $classes = null, $title = null, $icon_before = null, $icon_after = null, $addon = null, $block = false ) {

	$final_classes = 'btn';

	if ( ! $color ) {

		$color = 'default';

	}

	$final_classes = themeblvd_get_button_class( $color, $size, $block );

	if ( $classes ) {

		$final_classes .= ' ' . $classes;

	}

	if ( ! $title ) {

		$title = $text;

	}

	if ( ! $target ) {

		$target = '_self';

	}

	if ( $icon_before ) {

		$text = sprintf(
			'<i class="fa fa-%s before"></i>%s',
			$icon_before,
			$text
		);

	}

	if ( $icon_after ) {

		$text .= sprintf(
			'<i class="fa fa-%s after"></i>',
			$icon_after
		);

	}

	if ( $addon ) {

		$addon = ' ' . $addon;

	}

	if ( 'lightbox' === $target ) {

		$args = array(
			'item'  => $text,
			'link'  => $url,
			'title' => $title,
			'class' => $final_classes,
			'addon' => $addon,
		);

		$button = themeblvd_get_link_to_lightbox( $args );

	} else {

		$button = sprintf(
			'<a href="%s" title="%s" class="%s" target="%s"%s>%s</a>',
			esc_url( $url ),
			esc_attr( $title ),
			esc_attr( $final_classes ),
			esc_attr( $target ),
			wp_kses( $addon, array() ),
			themeblvd_kses( $text )
		);

	}

	/**
	 * Filters the final HTML output for a contextual
	 * alert block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output      Final HTML output.
	 * @param string $text        Text to show in button.
	 * @param string $color       Color class of button.
	 * @param string $url         URL where the button links to.
	 * @param string $target      Anchor tag's target, `_self`, `_blank` or `lightbox`.
	 * @param string $size        Size of button - `small`, `medium`, `default`, `large`, `x-large`, `xx-large` or `xxx-large`.
	 * @param string $classes     CSS classes to add, like `foo bar baz`.
	 * @param string $title       Title for anchor tag, will default to $text if blank.
	 * @param string $icon_before FontAwesome icon name before text, like `pencil`.
	 * @param string $icon_after  FontAwesome icon name after text, like `pencil`.
	 * @param string $addon       Anything to add into the <a> tag, like `data-foo="bar"`.
	 * @param bool   $block       Whether the button displays as block-level.
	 */
	return apply_filters( 'themeblvd_button', $button, $text, $url, $color, $target, $size, $classes, $title, $icon_before, $icon_after, $addon, $block );

}

/**
 * Get a group of buttons.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $buttons Buttons to include in group.
 * @param  array  $args {
 *     Button group arguments.
 *
 *     @type bool $stack Whether to vertically stack buttons.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_buttons( $buttons, $args = array() ) {

	$args = wp_parse_args( $args, array(
		'stack' => false,
	));

	// Set default button attributes.
	$btn_std = array(
		'color'       => 'default',
		'custom'      => array(), // Structure matches $btn_custom_std.
		'text'        => '',
		'size'        => 'default',
		'url'         => '',
		'target'      => '_self',
		'icon_before' => '',
		'icon_after'  => '',
		'block'       => false,
	);

	// Set default custom button attributes.
	$btn_custom_std = array(
		'bg'             => '',
		'bg_hover'       => '',
		'border'         => '',
		'text'           => '',
		'text_hover'     => '',
		'include_border' => '1',
		'include_bg'     => '1',
	);

	$output = '';

	$total = count( $buttons );

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

			if ( 'custom' === $btn['color'] && $btn['custom'] ) {

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

				$addon = sprintf(
					'style="background-color: %1$s; border-color: %2$s; color: %3$s;" data-bg="%1$s" data-bg-hover="%4$s" data-text="%3$s" data-text-hover="%5$s"',
					esc_attr( $bg ),
					esc_attr( $border ),
					themeblvd_kses( $custom['text'] ),
					esc_attr( $custom['bg_hover'] ),
					esc_attr( $custom['text_hover'] )
				);

			}

			$output .= themeblvd_button(
				$btn['text'],
				$btn['url'],
				$btn['color'],
				$btn['target'],
				$btn['size'],
				null,
				null,
				$btn['icon_before'],
				$btn['icon_after'],
				$addon,
				$btn['block']
			);

			if ( $args['stack'] ) {

				$output .= '</p>';

			} elseif ( $i < $total ) {

				$output .= ' ';

			}

			$i++;

		}
	}

	/**
	 * Filters the final HTML output for a group of
	 * buttons.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $buttons Buttons to include in group.
	 * @param array  $args {
	 *     Button group arguments.
	 *
	 *     @type bool $stack Whether to vertically stack buttons.
	 * }
	 */
	return apply_filters( 'themeblvd_buttons', $output, $buttons, $args );

}

/**
 * Display a group of buttons.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $buttons Buttons to include in group.
 * @param array $args Block arguments, see themeblvd_get_buttons() docs.
 */
function themeblvd_buttons( $buttons, $args = array() ) {

	echo themeblvd_get_buttons( $buttons, $args );

}

/**
 * Get a group of text blocks.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $blocks Text blocks to display in group.
 * @param  array  $args {
 *     Group arguments. Not currently used yet.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_text_blocks( $blocks, $args = array() ) {

	// $args = wp_parse_args( $args, array() );

	// Set default structure for individual text block.
	$block_std = array(
		'text'           => '',
		'size'           => '200%',
		'color'          => '',
		'apply_bg_color' => '0',
		'bg_color'       => '#ffffff',
		'bg_opacity'     => '1',
		'bold'           => '0',
		'italic'         => '0',
		'caps'           => '0',
		'suck_down'      => '0',
		'wpautop'        => '1',
	);

	$output = '<div class="tb-text-blocks">';

	if ( $blocks && is_array( $blocks ) ) {

		$i = 1;

		foreach ( $blocks as $block ) {

			$block = wp_parse_args( $block, $block_std );

			$class = 'tb-text-block text-block-' . $i;

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

			$size_style = '';

			$size_class = '';

			if ( $block['size'] ) {

				$size = $block['size'];

				if ( false !== strpos( $size, '%' ) ) {

					$size  = intval( $size ) / 100;

					if ( $size >= 3 ) {

						$size_class = 'text-large';

					} elseif ( $size >= 2 ) {

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

			$style = '';

			if ( $block['apply_bg_color'] ) {

				$style .= sprintf( 'background-color:%s;', esc_attr( $block['bg_color'] ) ); // Fallback for older browsers

				$style .= sprintf( 'background-color:%s;', esc_attr( themeblvd_get_rgb( $block['bg_color'], $block['bg_opacity'] ) ) );

				$class .= ' has-bg';

			}

			if ( $block['color'] ) {

				$style .= sprintf( 'color:%s;', esc_attr( $block['color'] ) );

			}

			if ( $block['wpautop'] && ! $block['bold'] ) {

				$content = themeblvd_get_content( $block['text'] );

			} else {

				$content = do_shortcode( themeblvd_kses( $block['text'] ) );

			}

			// Build final output.
			$output .= sprintf(
				"\n\t<div class=\"tb-text-block-wrap %s\" style=\"%s\">",
				$size_class,
				$size_style
			);

			if ( $block['bold'] ) {

				$output .= sprintf(
					"\n\t\t<%1\$s class=\"%2\$s\" style=\"%3\$s\">%4\$s</%1\$s><!-- .tb-text-block (end) -->",
					apply_filters( 'themeblvd_text_block_header_tag', 'h1' ),
					$class,
					$style,
					$content
				);

			} else {

				$output .= sprintf(
					"\n\t\t<div class=\"%s\" style=\"%s\">\n\t\t\t%s\t\t</div><!-- .tb-text-block (end) -->",
					$class,
					$style,
					$content
				);

			}

			$output .= "\n\t</div><!-- .tb-text-block-wrap (end) -->";

			$i++;
		}
	}

	$output .= '</div><!-- .tb-text-blocks -->';

	/**
	 * Filters the final HTML output for a group
	 * of text blocks.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $blocks Text blocks to display in group.
	 * @param array  $args {
	 *     Group arguments. Not currently used yet.
	 * }
	 */
	return apply_filters( 'themeblvd_text_blocks', $output, $blocks, $args );

}

/**
 * Display a group of text blocks.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $blocks Text blocks to display in group.
 * @param array $args   Text block group arguments, see themeblvd_get_text_blocks() docs.
 */
function themeblvd_text_blocks( $blocks, $args = array() ) {

	echo themeblvd_get_text_blocks( $blocks, $args );

}

/**
 * Get a pagination block.
 *
 * @since Theme_Blvd 2.3.0
 *
 * @param  int    $pages  Number of pages.
 * @param  int    $range  Range for paginated buttons, helpful for many pages.
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_pagination( $pages = 0, $range = 2 ) {

	$parts = themeblvd_get_pagination_parts( $pages, $range );

	if ( ! $parts ) {

		return '';

	}

	$output = '';

	if ( $parts ) {

		foreach ( $parts as $part ) {

			$class = 'btn btn-default';

			if ( $part['active'] ) {
				$class .= ' active';
			}

			$output .= sprintf(
				'<a class="%s" href="%s">%s</a>',
				$class,
				$part['href'],
				$part['text']
			);

		}
	}

	$wrap  = '<div class="pagination-wrap">';

	$wrap .= '	<div class="pagination">';

	$wrap .= '		<div class="btn-group paginate_links clearfix">';

	$wrap .= '			%s';

	$wrap .= '		</div>';

	$wrap .= '	</div>';

	$wrap .= '</div>';

	$output = sprintf( $wrap, $output );

	/**
	 * Filters the final HTML output for a pagination
	 * block.
	 *
	 * Note: If you're looking to make structural changes
	 * to a pagination block, see themeblvd_get_pagination_parts()
	 * and its filter.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $parts  Pagination parts retrieved from themeblvd_get_pagination_parts().
	 * @param int    $pages  Number of pages.
	 * @param int    $range  Range for paginated buttons, helpful for many pages.
	 */
	return apply_filters( 'themeblvd_pagination', $output, $parts, $pages, $range );

}

/**
 * Display a pagination block.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param int $pages Number of pages.
 * @param int $range Range for paginated buttons, helpful for many pages.
 */
function themeblvd_pagination( $pages = 0, $range = 2 ) {

	echo themeblvd_get_pagination( $pages, $range );

}

/**
 * Get a breadcrumbs block.
 *
 * @since Theme_Blvd 2.2.1
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string $delimiter Any content to output between crumb items; this is blank by default and is inserted via CSS content.
 *     @type string $home      Text for home link, like `Home`.
 *     @type string $home_link URL for home link, like `http://mysite.com`.
 *     @type string $before    Markup before current crumb item.
 *     @type string $after     Markup after current crumb item.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_breadcrumbs_trail( $args = array() ) {

	/**
	 * Filters the default arguments for a breadcrumbs
	 * block.
	 *
	 * @since Theme_Blvd 2.2.1
	 *
	 * @param array Default arguments for breadcrumb blocks.
	 */
	$defaults = apply_filters( 'themeblvd_breadcrumb_atts', array(
		'delimiter' => '',
		'home'      => themeblvd_get_local( 'home' ),
		'home_link' => themeblvd_get_home_url(),
		'before'    => '<span class="current">',
		'after'     => '</span>',
	));

	$args = wp_parse_args( $args, $defaults );

	$parts = themeblvd_get_breadcrumb_parts( $args );

	$output = '';

	$count = 1;

	$total = count( $parts );

	if ( $parts ) {

		$output .= '<ul class="breadcrumb">';

		foreach ( $parts as $part ) {

			$crumb = $part['text'];

			if ( ! empty( $part['link'] ) ) {

				$crumb = sprintf(
					'<a href="%1$s" class="%2$s-link" title="%3$s">%3$s</a>',
					esc_url( $part['link'] ),
					esc_attr( $part['type'] ),
					esc_attr( $crumb )
				);

			}

			if ( $total == $count ) {

				$crumb = '<li class="active">' . $args['before'] . $crumb . $args['after'] . '</li>';

			} else {

				$crumb = '<li>' . $crumb . $args['delimiter'] . '</li>';

			}

			$output .= $crumb;

			$count++;

		}

		$output .= '</ul><!-- .breadcrumb (end) -->';

	}

	/**
	 * Filters the final HTML output for a breadcrumbs
	 * block.
	 *
	 * Note: If you're looking to make structural changes
	 * to a breadcrumbs block, see themeblvd_get_breadcrumb_parts()
	 * and its filter.
	 *
	 * @since Theme_Blvd 2.2.1
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args
	 * @param array  $parts  Breadcrumb parts retrieved from themeblvd_get_breadcrumb_parts().
	 */
	return apply_filters( 'themeblvd_breadcrumbs_trail', $output, $args, $parts );

}

/**
 * Display the site breadcrumbs.
 *
 * This is meant to be called only once on a page.
 *
 * If you're looking for a custom way to wrap a breadcrumbs
 * output create your own function and use themeblvd_get_breadcrumbs_trail()
 * to get just the trail.
 *
 * But how to unhook default breadcrumbs from running at all?
 * `remove_action( 'themeblvd_breadcrumbs', 'themeblvd_breadcrumbs_default' );`
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args Breadcrumb arguments, see themeblvd_get_breadcrumbs_trail() docs.
 */
function themeblvd_the_breadcrumbs( $args = array() ) {

	echo '<div id="breadcrumbs" class="site-breadcrumbs">';

	echo '<div class="wrap">';

	echo themeblvd_get_breadcrumbs_trail( $args );

	echo '</div><!-- .wrap (end) -->';

	echo '</div><!-- #breadcrumbs (end) -->';

}

/**
 * Get a simple contact block.
 *
 * This function is currently used only for the "Simple
 * Contact" widget of the Theme Blvd Widget Pack plugin.
 *
 * @since Theme_Blvd 2.0.3
 *
 * @param  array  $args   Options setup from widget.
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_simple_contact( $args ) {

	// Setup icon links.
	$icons = array();

	for ( $i = 1; $i <= 6; $i++ ) {

		if ( ! empty( $args[ 'link_' . $i . '_url' ] ) ) {

			$icons[ $args[ 'link_' . $i . '_icon' ] ] = $args[ 'link_' . $i . '_url' ];

		}
	}

	// Start output.
	$output = '<ul class="simple-contact">';

	if ( ! empty( $args['phone_1'] ) ) {

		$output .= sprintf(
			'<li class="phone">%s</li>',
			themeblvd_kses( $args['phone_1'] )
		);

	}

	if ( ! empty( $args['phone_2'] ) ) {

		$output .= sprintf(
			'<li class="phone">%s</li>',
			themeblvd_kses( $args['phone_2'] )
		);

	}

	if ( ! empty( $args['email_1'] ) ) {

		$output .= sprintf(
			'<li class="email"><a href="mailto:%s">%s</a></li>',
			esc_attr( $args['email_1'] ),
			esc_html( $args['email_1'] )
		);

	}

	if ( ! empty( $args['email_2'] ) ) {

		$output .= sprintf(
			'<li class="email"><a href="mailto:%s">%s</a></li>',
			esc_attr( $args['email_2'] ),
			esc_html( $args['email_2'] )
		);

	}

	if ( ! empty( $args['contact'] ) ) {

		$output .= sprintf(
			'<li class="contact"><a href="%s">%s</a></li>',
			esc_url( $args['contact'] ),
			themeblvd_get_local( 'contact_us' )
		);

	}

	if ( ! empty( $args['skype'] ) ) {

		$output .= sprintf(
			'<li class="skype">%s</li>',
			themeblvd_kses( $args['skype'] )
		);

	}

	if ( ! empty( $icons ) ) {

		$sources = themeblvd_get_social_media_sources();

		$output .= '<li class="link"><ul class="icons">';

		foreach ( $icons as $icon => $url ) {

			$title = '';

			if ( isset( $sources[ $icon ] ) ) {

				$title = $sources[ $icon ];

			}

			$output .= sprintf(
				'<li class="%s"><a href="%s" target="_blank" title="%s">%s</a></li>',
				esc_attr( $icon ),
				esc_url( $url ),
				esc_attr( $title ),
				esc_html( $title )
			);

		}

		$output .= '</ul></li>';

	}

	$output .= '</ul>';

	/**
	 * Filters the final HTML output for a simple
	 * contact block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args   Options setup from widget.
	 */
	return apply_filters( 'themeblvd_simple_contact', $output, $args );

}

/**
 * Display a simple contact block.
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param array $args Options setup from widget.
 */
function themeblvd_simple_contact( $args ) {

	echo themeblvd_get_simple_contact( $args );

}

/**
 * Get slider directional navigation arrows.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type bool   $carousel  HTML ID of slider being controlled, not required for all types of sliders.
 *     @type string $color     Color of buttons; use `trans` for transparent buttons.
 *     @type string $direction Direction of navigation, `horz` or `vert`.
 *     @type string $prev      Text for previous button, like `Previous`.
 *     @type string $next      Text for next button, like `Next`.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_slider_controls( $args = array() ) {

	$args = wp_parse_args( $args, array(
		'carousel'  => false,
		'color'     => 'primary',
		'direction' => 'horz',
		'prev'      => themeblvd_get_local( 'previous' ),
		'next'      => themeblvd_get_local( 'next' ),
	));

	if ( 'horz' === $args['direction'] ) {

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

		$output = str_replace( '#', '#' . $args['carousel'], $output );

		$output = str_replace( 'prev">', 'prev" data-slide="prev">', $output );

		$output = str_replace( 'next">', 'next" data-slide="next">', $output );

	}

	/**
	 * Filters the final HTML output for slider
	 * directional navigation arrows
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type bool   $carousel  HTML ID of slider being controlled, not required for all types of sliders.
	 *     @type string $color     Color of buttons; use `trans` for transparent buttons.
	 *     @type string $direction Direction of navigation, `horz` or `vert`.
	 *     @type string $prev      Text for previous button, like `Previous`.
	 *     @type string $next      Text for next button, like `Next`.
	 * }
	 */
	return apply_filters( 'themeblvd_slider_controls', $output, $args );

}

/**
 * Display slider directional navigation arrows.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_slider_controls() docs.
 */
function themeblvd_slider_controls( $args = array() ) {

	echo themeblvd_get_slider_controls( $args );

}

/**
 * Get a button that scrolls to the top of
 * the page.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string $class CSS classes to add, like `foo bar baz`.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_to_top( $args = array() ) {

	$args = wp_parse_args( $args, array(
		'class' => '',
	));

	$class = 'tb-scroll-to-top';

	if ( $args['class'] ) {

		$class .= ' ' . $args['class'];

	}

	$output = sprintf(
		'<a href="#" class="%s">%s</a>',
		$class,
		themeblvd_get_local( 'top' )
	);

	/**
	 * Filters the final HTML output for a button
	 * that scrolls to the top of the page.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string $class CSS classes to add, like `foo bar baz`.
	 * }
	 */
	return apply_filters( 'themeblvd_to_top', $output, $args );

}

/**
 * Display a button that scrolls to the top of
 * the page.
 *
 * This display function takes into account
 * whether THE scroll-to-top button has been
 * enabled from the theme options page or not.
 *
 * So if you're manually outputting the item,
 * use `echo themeblvd_get_to_top()` instead.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_to_top() docs.
 */
function themeblvd_to_top( $args = array() ) {

	if ( 'show' === themeblvd_get_option( 'scroll_to_top' ) ) {

		echo themeblvd_get_to_top( $args );

	}

}

/**
 * Get a button that scrolls to a specific
 * section on the page.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string $to    HTML ID being scrolled to, like `foo`; if blank, it will go to next .element-section.
 *     @type string $class CSS classes to add, like `foo bar baz`.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_to_section( $args = array() ) {

	$defaults = array(
		'to'    => '', // ID of HTML element to jump to; if blank, go to next section
		'class' => '',
	);
	$args = wp_parse_args( $args, $defaults );

	$class = 'tb-scroll-to-section tb-scroll-to';

	if ( $args['class'] ) {

		$class .= ' ' . esc_attr( $args['class'] );

	}

	$output = sprintf(
		'<a href="#%s" class="%s">%s</a>',
		esc_attr( $args['to'] ),
		$class,
		themeblvd_get_local( 'next' )
	);

	/**
	 * Filters the final HTML output for a button
	 * that scrolls to a specific section on
	 * the page.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string $to    HTML ID being scrolled to, like `foo`; if blank, it will go to next .element-section.
	 *     @type string $class CSS classes to add, like `foo bar baz`.
	 * }
	 */
	return apply_filters( 'themeblvd_to_section', $output, $args );

}

/**
 * Display a button that scrolls to a specific
 * section on the page.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_to_section() docs.
 */
function themeblvd_to_section( $args = array() ) {

	echo themeblvd_get_to_section( $args );

}

/**
 * Get an animated loader block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_loader() {

	$output = "<div class=\"tb-loader\">\n";

	$output .= "\t<span class=\"icon-1\"></span>\n";

	$output .= "\t<span class=\"icon-2\"></span>\n";

	$output .= "\t<span class=\"icon-3\"></span>\n";

	$output .= "</div><!-- .tb-loader (end) -->\n";

	/**
	 * Filters the final HTML output for an animated
	 * loader block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 */
	return apply_filters( 'themeblvd_loader', $output );

}

/**
 * Display an animated loader block.
 *
 * @since Theme_Blvd 2.5.0
 */
function themeblvd_loader() {

	echo themeblvd_get_loader();

}

/**
 * Get a post filtering navigation block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $posts Array of WP_Post objects for current loop.
 * @param  string $tax   Taxonomy to filter posts by.
 * @param  array  $args {
 *     Block arguments.
 *
 *     None yet.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_filter_nav( $posts, $tax = 'category', $args = array() ) {

	$output = '';

	$terms = array();

	if ( ! is_a( $posts, 'WP_Query' ) ) {
		return $output;
	}

	/**
	 * Filters default arguments for a posts filtering
	 * navigation block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     None yet.
	 * }
	 */
	$defaults = apply_filters( 'themeblvd_filter_nav_args', array() );

	$args = wp_parse_args( $args, $defaults );

	if ( $posts->have_posts() ) {

		while ( $posts->have_posts() ) {

			$posts->the_post();

			$current = get_the_terms( get_the_ID(), $tax );

			if ( $current ) {

				foreach ( $current as $term ) {

					$terms[ $term->slug ] = $term->name;

				}
			}
		}
	}

	if ( $terms ) {

		asort( $terms );

		$terms = apply_filters( 'themeblvd_filter_nav_terms', $terms, $posts, $tax, $args );

		$output .= '<div class="tb-inline-menu tb-filter-nav">';

		$output .= '<ul class="list-inline filter-menu">';

		$output .= sprintf( '<li class="active"><a href="#" data-filter=".iso-item" title="%1$s">%1$s</a></li>', themeblvd_get_local( 'all' ) );

		foreach ( $terms as $key => $value ) {

			$key = esc_attr( preg_replace( '/[^a-zA-Z0-9._\-]/', '', $key ) ); // Allow non-latin characters, and still work with jQuery.

			$output .= sprintf( '<li><a href="#" data-filter=".filter-%1$s" title="%2$s">%2$s</a></li>', $key, esc_html( $value ) );

		}

		$output .= '</ul>';

		$output .= '</div><!-- .tb-inline-mini (end) -->';

	}

	wp_reset_postdata();

	/**
	 * Filters the final HTML output for a post
	 * filtering navigation block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param array  $posts Array of WP_Post objects for current loop.
	 * @param string $tax   Taxonomy to filter posts by.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     None yet.
	 * }
	 */
	return apply_filters( 'themeblvd_filter_nav', $output, $posts, $tax, $args );

}

/**
 * Display a post filtering navigation block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array  $posts Array of WP_Post objects for current loop.
 * @param string $tax   Taxonomy to filter posts by.
 * @param array  $args  Block arguments, see themeblvd_get_filter_nav() docs.
 */
function themeblvd_filter_nav( $posts, $tax = 'category', $args = array() ) {

	echo themeblvd_get_filter_nav( $posts, $tax, $args );

}
