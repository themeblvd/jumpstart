<?php
/**
 * The template used to start a WooCommerce loop.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        https://themeblvd.com
 * @package     Jump_Start
 */

if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {

	$compat = Theme_Blvd_Compat_WooCommerce::get_instance();

	printf(
		'<div class="tb-product-loop-wrap shop-columns-%s %s-view bg-content clearfix">',
		$compat->loop_columns(),
		$compat->loop_view()
	);

}

if ( themeblvd_get_att('woo_product_view') == 'catalog' ) {

	echo '<div class="table-responsive">';

	echo '<table class="shop_table table">';

	echo '<tbody>';

} else {

	echo '<ul class="products">';

}
