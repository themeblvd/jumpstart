<?php
global $product;
?>
<tr class="tb-product">

	<td class="product-thumbnail">
		<?php echo $product->get_image(); ?>
	</td>

	<td class="product-name">
		<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
		<?php echo $product->get_categories(', '); ?>
	</td>

	<td class="product-rating">
		<?php echo $product->get_rating_html(); ?>
	</td>

	<td class="product-price">
		<?php woocommerce_template_loop_price(); ?>
	</td>

	<td class="product-action">
		<?php woocommerce_template_loop_add_to_cart(); ?>
	</td>

</tr>