<?php
/**
 * The template used to end a WooCommerce loop.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     @@name-package
 */

$compat = Theme_Blvd_Compat_WooCommerce::get_instance();

if ( $compat->loop_view() == 'catalog' ) {
	echo '</tbody>';
	echo '</table>';
	echo '</div><!-- .table-responsive -->';
} else {
	echo '</ul><!-- .products (end) -->';
}

if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
	echo '</div><!-- .tb-product-loop-wrap (end) -->';
}

// Reset view, if necessary
$compat->shortcode_reset_view();
