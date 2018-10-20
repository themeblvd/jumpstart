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

	$output  = "\n<div class=\"tb-floating-search\">\n";

	$output .= "\t<div class=\"wrap\">\n\n";

	$output .= '<a href="#" title="' . themeblvd_get_local( 'close' ) . '" class="close-search">x</a>';

	$output .= get_search_form( false );

	$output .= "\n\t</div><!-- .wrap (end) -->\n";

	$output .= "</div><!-- .tb-floating-search (end) -->\n";

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
	) );

	$args = wp_parse_args( $args, $defaults );

	$output = '';

	if ( $args['trigger'] ) {

		$output .= themeblvd_get_cart_popup_trigger( array(
			'target' => $args['id'],
		) );

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
 * Get header text.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $text   Text to display; when left blank, pulls from `header_text` theme option.
 * @param  array  $args {
 *     Optional Arguments
 *
 *     @type array $class CSS classes to add to output.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_header_text( $text = '', $args = array() ) {

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

		$class = 'header-text';

		if ( ! empty( $args['class'] ) ) {

			if ( is_array( $args['class'] ) ) {

				$class .= ' ' . implode( ' ' , $args['class'] );

			} else {

				$class .= ' ' . $args['class'];

			}
		}

		$output = sprintf( '<div class="%s">%s</div>', $class, themeblvd_kses( $text ) );

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
function themeblvd_header_text( $text = '', $args = array() ) {

	echo themeblvd_get_header_text( $text, $args );

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
