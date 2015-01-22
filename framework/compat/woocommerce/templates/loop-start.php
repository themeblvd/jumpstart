<?php
if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
	$compat = Theme_Blvd_Compat_WooCommerce::get_instance();
	printf('<div class="tb-product-loop-wrap shop-columns-%s %s-view bg-content">', $compat->loop_columns(), themeblvd_get_att('woo_product_view'));
}

if ( themeblvd_get_att('woo_product_view') == 'catalog' ) {
	echo '<table class="shop_table">';
	echo '<tbody>';
} else {
	echo '<ul class="products">';
}