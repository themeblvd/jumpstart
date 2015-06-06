<?php
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
