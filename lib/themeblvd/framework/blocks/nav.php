<?php
/**
 * Frontend Blocks: Navigation
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.5.0
 */
/**
 * Get a button block.
 *
 * @since @@name-framework 2.0.0
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
			'<i class="%s before"></i>%s',
			esc_attr( themeblvd_get_icon_class( $icon_before ) ),
			$text
		);

	}

	if ( $icon_after ) {

		$text .= sprintf(
			'<i class="%s after"></i>',
			esc_attr( themeblvd_get_icon_class( $icon_after ) )
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
	 * @since @@name-framework 2.5.0
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
 * @since @@name-framework 2.5.0
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
	) );

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
	 * @since @@name-framework 2.5.0
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
 * @since @@name-framework 2.5.0
 *
 * @param array $buttons Buttons to include in group.
 * @param array $args Block arguments, see themeblvd_get_buttons() docs.
 */
function themeblvd_buttons( $buttons, $args = array() ) {

	echo themeblvd_get_buttons( $buttons, $args );

}

/**
 * Get a contact button bar block.
 *
 * @since @@name-framework 2.5.0
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
	 * @since @@name-framework 2.5.0
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
		'container'  => 'ul',
	) );

	$args = wp_parse_args( $args, $defaults );

	$output = '';

	if ( $buttons && is_array( $buttons ) ) {

		if ( $args['container'] ) {

			$class = 'themeblvd-contact-bar tb-social-icons ' . $args['style'];

			if ( $args['class'] ) {

				$class .= ' ' . $args['class'];

			}

			$class .= ' clearfix';

			$output .= sprintf(
				'<%s class="%s">',
				$args['container'],
				esc_attr( $class )
			);

		}

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
				 * Most icon ID's already match Font Awesome by default,
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
						$icon = 'pencil-alt';
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

				/**
				 * Filters the icon name used for icons used
				 * in the contact bar.
				 *
				 * @since @@name-framework 2.7.4
				 *
				 * @param string $icon Icon name.
				 * @param string $type Original icon name before any alterations.
				 */
				$icon = apply_filters( 'themeblvd_contact_bar_icon', $icon, $button['icon'] );

				$content = sprintf(
					'<i class="%s"></i>',
					themeblvd_get_icon_class( $icon, array( 'fa-fw' ) )
				);

			}

			$output .= sprintf(
				'<li class="contact-bar-item li-%s"><a href="%s" title="%s" class="%s" target="%s" data-toggle="tooltip" data-placement="%s">%s</a></li>',
				esc_attr( $button['icon'] ),
				esc_url( $button['url'] ),
				esc_attr( $title ),
				esc_attr( $class ),
				esc_attr( $button['target'] ),
				esc_attr( $args['tooltip'] ),
				themeblvd_kses( $content )
			);

		}

		if ( $args['container'] ) {

			$output .= '</ul><!-- .themeblvd-contact-bar -->';

		}

	}

	/**
	 * Filters the final HTML output for a contact
	 * bar block.
	 *
	 * @since @@name-framework 2.5.0
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
 * @since @@name-framework 2.0.0
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
 * Get a link to display the floating searchform
 * block.
 *
 * @since @@name-framework 2.5.0
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
	 * Filters the icon used to open the floating
	 * search modal.
	 *
	 * @since @@name-framework 2.7.4
	 *
	 * @param string Icon name.
	 */
	$icon = apply_filters( 'themeblvd_search_trigger_icon', 'search' );

	/**
	 * Filters the default arguments for a link to
	 * open a floating searchform.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array {
	 *     Default link arguments.
	 *
	 *     @type string $open  FontAwesome icon name used for link.
	 *     @type string $class CSS classes to add to link, like `foo bar baz`.
	 * }
	 */
	$defaults = apply_filters( 'themeblvd_floating_search_trigger_defaults', array(
		'open'  => $icon,
		'class' => '',
	) );

	$args = wp_parse_args( $args, $defaults );

	$class = 'tb-search-trigger';

	if ( $args['class'] ) {

		$class .= ' ' . $args['class'];

	}

	$output = sprintf(
		'<a href="#" class="%s"><i class="%s"></i></a>',
		esc_attr( $class ),
		esc_attr( themeblvd_get_icon_class( $args['open'] ) )
	);

	/**
	 * Filters the final HTML output for a link to display
	 * the floating searchform block.
	 *
	 * @since @@name-framework 2.5.0
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
 * @since @@name-framework 2.5.0
 *
 * @param array $args Link arguments, see themeblvd_get_floating_search_trigger() docs.
 */
function themeblvd_floating_search_trigger( $args = array() ) {

	echo themeblvd_get_floating_search_trigger( $args );

}

/**
 * Get a link to display the mobile panel.
 *
 * @since @@name-framework 2.7.0
 *
 * @param  array  $args {
 *     Link arguments.
 *
 *     @type string $class CSS classes to add to link, like `foo bar baz`.
 * }
 * @return string $output Final HTML output for link.
 */
function themeblvd_get_mobile_panel_trigger( $args = array() ) {

	/**
	 * Filters the default arguments for a link to
	 * open the mobile panel.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param array {
	 *     Default link arguments.
	 *
	 *     @type string $class CSS classes to add to link, like `foo bar baz`.
	 * }
	 */
	$defaults = apply_filters( 'themeblvd_mobile_panel_trigger_defaults', array(
		'class' => '',
	) );

	$args = wp_parse_args( $args, $defaults );

	$class = 'tb-nav-trigger btn-navbar';

	if ( $args['class'] ) {

		$class .= ' ' . $args['class'];

	}

	$output = themeblvd_get_menu_toggle( $class );

	/**
	 * Filters the final HTML output for a link to
	 * display the side panel
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string $output Final HTML output of link.
	 * @param array  $args {
	 *     Link arguments.
	 *
	 *     @type string $class CSS classes to add to link, like `foo bar baz`.
	 * }
	 */
	return apply_filters( 'themeblvd_mobile_panel_trigger', $output, $args );

}

/**
 * Display a link to open the mobile panel.
 *
 * @since @@name-framework 2.7.0
 *
 * @param array $args Link arguments, see themeblvd_get_mobile_panel_trigger() docs.
 */
function themeblvd_mobile_panel_trigger( $args = array() ) {

	echo themeblvd_get_mobile_panel_trigger( $args );

}

/**
 * Get a link to display the side panel.
 *
 * @since @@name-framework 2.6.0
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
	 * @since @@name-framework 2.6.0
	 *
	 * @param array {
	 *     Default link arguments.
	 *
	 *     @type string $class CSS classes to add to link, like `foo bar baz`.
	 * }
	 */
	$defaults = apply_filters( 'themeblvd_side_trigger_defaults', array(
		'class' => '',
	) );

	$args = wp_parse_args( $args, $defaults );

	$class = 'tb-side-trigger';

	if ( $args['class'] ) {

		$class .= ' ' . $args['class'];

	}

	$output = themeblvd_get_menu_toggle( $class );

	/**
	 * Filters the final HTML output for a link to
	 * display the side panel
	 *
	 * @since @@name-framework 2.6.0
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
 * @since @@name-framework 2.6.0
 *
 * @param array $args Link arguments, see themeblvd_get_side_trigger() docs.
 */
function themeblvd_side_trigger( $args = array() ) {

	echo themeblvd_get_side_trigger( $args );

}

/**
 * Get a link to display the floating shopping
 * cart block.
 *
 * @since @@name-framework 2.5.0
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
	 * @since @@name-framework 2.5.0
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
		'icon'   => themeblvd_get_shopping_cart_icon(),
		'class'  => '',
		'target' => 'floating-shopping-cart',
		'url'    => '#',
		'count'  => '',
	) );

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
	 * @since @@name-framework 2.5.0
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
	 * @since @@name-framework 2.5.0
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

	$output .= '<i class="' . esc_attr( themeblvd_get_icon_class( $args['icon'] ) ) . '"></i>';

	if ( $count ) {

		$output .= sprintf( '<span class="trigger-label">%s</span>', $count );

	}

	$output .= '</a>';

	/**
	 * Filters a link to display the floating shopping
	 * cart block.
	 *
	 * @since @@name-framework 2.5.0
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
 * @since @@name-framework 2.5.0
 *
 * @param array $args See themeblvd_get_cart_popup_trigger() docs.
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
 * @since @@name-framework 2.5.0
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

	$icon = sprintf(
		'<i class="%s"></i>',
		esc_attr( themeblvd_get_icon_class( themeblvd_get_shopping_cart_icon() ) )
	);

	$output = sprintf(
		'<a href="%s" id="mobile-to-cart" class="btn-navbar cart">%s%s</a>',
		/**
		 * Filters the url to the shopping cart page.
		 *
		 * @since @@name-framework 2.5.0
		 *
		 * @param string $cart_url Shopping cart URL like `https://mysite.com/cart`.
		 */
		esc_url( apply_filters( 'themeblvd_cart_url', $cart_url ) ),
		/**
		 * Filters the HTML for shopping cart icon in the
		 * mobile header.
		 *
		 * @since @@name-framework 2.5.0
		 *
		 * @param string Basic HTML For cart icon.
		 */
		themeblvd_kses( apply_filters( 'themeblvd_btn_navbar_cart_text', $icon ) ),
		themeblvd_kses( $cart_label )
	);

	/**
	 * Filters link to shopping cart page, intended for
	 * mobile only.
	 *
	 * @since @@name-framework 2.5.0
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
 * @since @@name-framework 2.5.0
 */
function themeblvd_mobile_cart_link() {

	echo themeblvd_get_mobile_cart_link();

}

/**
 * Get a link to display the floating language
 * switcher block.
 *
 * @since @@name-framework 2.7.0
 *
 * @param  array  $args {
 *     Link arguments.
 *
 *     @type string $icon  FontAwesome icon name used for link.
 *     @type string $class CSS classes to add to link, like `foo bar baz`.
 * }
 * @return string $output Final HTML output.
 */
function themeblvd_get_lang_popup_trigger( $args = array() ) {

	/**
	 * Filters the default arguments for a link to
	 * open a floating language switcher.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param array {
	 *     Default link arguments.
	 *
	 *     @type string $iconn FontAwesome icon name used for link.
	 *     @type string $class CSS classes to add to link, like `foo bar baz`.
	 * }
	 */
	$defaults = apply_filters( 'themeblvd_lang_popup_trigger_defaults', array(
		'icon'  => 'globe',
		'class' => '',
	) );

	$args = wp_parse_args( $args, $defaults );

	$class = 'tb-lang-trigger';

	if ( $args['class'] ) {

		$class .= ' ' . $args['class'];

	}

	$output = sprintf(
		'<a href="#" class="%s" title="%s" data-toggle="modal" data-target="#floating-lang-switcher">',
		esc_attr( $class ),
		esc_attr( themeblvd_get_local( 'language' ) )
	);

	$output .= sprintf( '<i class="%s"></i>', esc_attr( themeblvd_get_icon_class( $args['icon'] ) ) );

	$output .= '</a>';

	/**
	 * Filters a link to display the floating language
	 * switcher block.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Default link arguments.
	 *
	 *     @type string $icon  FontAwesome icon name used for link.
	 *     @type string $class CSS classes to add to link, like `foo bar baz`.
	 * }
	 */
	return apply_filters( 'themeblvd_lang_popup_trigger', $output, $args );

}

/**
 * Display a link to display the floating language
 * switcher block.
 *
 * @since @@name-framework 2.7.0
 *
 * @param array $args Link arguments, see themeblvd_get_floating_search_trigger() docs.
 */
function themeblvd_lang_popup_trigger( $args = array() ) {

	echo themeblvd_get_lang_popup_trigger( $args );

}

/**
 * Get a pagination block.
 *
 * @since @@name-framework 2.3.0
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

	$wrap  = "\n<div class=\"pagination-wrap\">\n";

	$wrap .= "\t<div class=\"pagination\">\n";

	$wrap .= "\t\t<div class=\"btn-group paginate_links clearfix\">\n";

	$wrap .= "\t\t\t%s\n";

	$wrap .= "\t\t</div>\n";

	$wrap .= "\t</div>\n";

	$wrap .= "</div>\n";

	$output = sprintf( $wrap, $output );

	/**
	 * Filters the final HTML output for a pagination
	 * block.
	 *
	 * Note: If you're looking to make structural changes
	 * to a pagination block, see themeblvd_get_pagination_parts()
	 * and its filter.
	 *
	 * @since @@name-framework 2.5.0
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
 * @since @@name-framework 2.0.0
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
 * @since @@name-framework 2.2.1
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
	 * @since @@name-framework 2.2.1
	 *
	 * @param array Default arguments for breadcrumb blocks.
	 */
	$defaults = apply_filters( 'themeblvd_breadcrumb_atts', array(
		'delimiter' => '',
		'home'      => themeblvd_get_local( 'home' ),
		'home_link' => themeblvd_get_home_url(),
		'before'    => '<span class="current">',
		'after'     => '</span>',
	) );

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
	 * @since @@name-framework 2.2.1
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
 * @since @@name-framework 2.5.0
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
 * @since @@name-framework 2.0.3
 *
 * @param  array  $args   Options setup from widget.
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_simple_contact( $args ) {

	// Start output.
	$output = '<ul class="simple-contact">';

	if ( ! empty( $args['phone_1'] ) ) {

		$output .= sprintf(
			'<li class="faphone"><i class="%s"></i>%s</li>',
			esc_attr( themeblvd_get_icon_class( 'phone', array( 'fa-fw' ) ) ),
			themeblvd_kses( $args['phone_1'] )
		);

	}

	if ( ! empty( $args['phone_2'] ) ) {

		$output .= sprintf(
			'<li class="phone"><i class="%s"></i>%s</li>',
			esc_attr( themeblvd_get_icon_class( 'phone', array( 'fa-fw' ) ) ),
			themeblvd_kses( $args['phone_2'] )
		);

	}

	if ( ! empty( $args['email_1'] ) ) {

		$output .= sprintf(
			'<li class="email"><i class="%s"></i><a href="mailto:%s">%s</a></li>',
			esc_attr( themeblvd_get_icon_class( 'envelope', array( 'fa-fw' ) ) ),
			esc_attr( $args['email_1'] ),
			esc_html( $args['email_1'] )
		);

	}

	if ( ! empty( $args['email_2'] ) ) {

		$output .= sprintf(
			'<li class="email"><i class="%s"></i><a href="mailto:%s">%s</a></li>',
			esc_attr( themeblvd_get_icon_class( 'envelope', array( 'fa-fw' ) ) ),
			esc_attr( $args['email_2'] ),
			esc_html( $args['email_2'] )
		);

	}

	if ( ! empty( $args['contact'] ) ) {

		$output .= sprintf(
			'<li class="contact"><i class="%s"></i><a href="%s">%s</a></li>',
			esc_attr( themeblvd_get_icon_class( 'pencil-alt', array( 'fa-fw' ) ) ),
			esc_url( $args['contact'] ),
			themeblvd_get_local( 'contact_us' )
		);

	}

	if ( ! empty( $args['skype'] ) ) {

		$output .= sprintf(
			'<li class="skype"><i class="%s"></i>%s</li>',
			esc_attr( themeblvd_get_icon_class( 'skype', array( 'fa-fw' ) ) ),
			themeblvd_kses( $args['skype'] )
		);

	}

	// Setup icons that can be sent to themeblvd_get_contact_bar().
	$icons = array();

	$sources = themeblvd_get_social_media_sources();

	for ( $i = 1; $i <= 6; $i++ ) {

		if ( ! empty( $args[ 'link_' . $i . '_url' ] ) ) {

			$icon = $args[ 'link_' . $i . '_icon' ];

			$icons[ $icon ] = array();

			$icons[ $icon ]['icon'] = $icon;

			$icons[ $icon ]['url'] = $args[ 'link_' . $i . '_url' ];

			$icons[ $icon ]['target'] = '_blank';

			if ( isset( $sources[ $icon ] ) ) {

				$icons[ $icon ]['label'] = $sources[ $icon ];

			}
		}
	}

	if ( $icons ) {

		$output .= sprintf(
			'<li class="link"><i class="%s"></i>',
			esc_attr( themeblvd_get_icon_class( 'link', array( 'fa-fw' ) ) )
		);

		$output .= themeblvd_get_contact_bar(
			$icons,
			array(
				'style'   => 'color',
				'tooltip' => 'top',
			)
		);

		$output .= '</li>';

	}

	$output .= '</ul>';

	/**
	 * Filters the final HTML output for a simple
	 * contact block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args   Options setup from widget.
	 */
	return apply_filters( 'themeblvd_simple_contact', $output, $args );

}

/**
 * Display a simple contact block.
 *
 * @since @@name-framework 2.1.0
 *
 * @param array $args Options setup from widget.
 */
function themeblvd_simple_contact( $args ) {

	echo themeblvd_get_simple_contact( $args );

}

/**
 * Get slider directional navigation arrows.
 *
 * @since @@name-framework 2.5.0
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
	) );

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
	 * @since @@name-framework 2.5.0
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
 * @since @@name-framework 2.5.0
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
 * @since @@name-framework 2.5.0
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
	) );

	$class = 'tb-scroll-to-top';

	if ( $args['class'] ) {

		$class .= ' ' . $args['class'];

	}

	$output = sprintf(
		'<a href="#" class="%s">%s</a>',
		$class,
		themeblvd_get_local( 'top' )
	);

	$output = "\n" . $output . "\n";

	/**
	 * Filters the final HTML output for a button
	 * that scrolls to the top of the page.
	 *
	 * @since @@name-framework 2.5.0
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
 * @since @@name-framework 2.5.0
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
 * @since @@name-framework 2.5.0
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
	 * @since @@name-framework 2.5.0
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
 * @since @@name-framework 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_to_section() docs.
 */
function themeblvd_to_section( $args = array() ) {

	echo themeblvd_get_to_section( $args );

}

/**
 * Get a post filtering navigation block.
 *
 * @since @@name-framework 2.5.0
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
	 * @since @@name-framework 2.5.0
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
	 * @since @@name-framework 2.5.0
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
 * @since @@name-framework 2.5.0
 *
 * @param array  $posts Array of WP_Post objects for current loop.
 * @param string $tax   Taxonomy to filter posts by.
 * @param array  $args  Block arguments, see themeblvd_get_filter_nav() docs.
 */
function themeblvd_filter_nav( $posts, $tax = 'category', $args = array() ) {

	echo themeblvd_get_filter_nav( $posts, $tax, $args );

}
