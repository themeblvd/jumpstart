<?php
/**
 * The template used to display an individual
 * WooCommerce product in the "catalog" view.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     Jump_Start
 */

global $product;
?>
<tr class="tb-product">

	<td class="product-thumbnail">
		<?php echo $product->get_image(); ?>
	</td>

	<td class="product-name">
		<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
		<?php echo wc_get_product_category_list( $product->get_id() ); ?>
	</td>

	<td class="product-rating">
		<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
	</td>

	<td class="product-price">
		<?php woocommerce_template_loop_price(); ?>
	</td>

	<td class="product-action">
		<?php woocommerce_template_loop_add_to_cart(); ?>
	</td>

</tr>
