<?php
/**
 * The template used to display an individual
 * WooCommerce product in the "list" view.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     @@name-package
 */
?>
<li <?php post_class(); ?>>

	<div class="tb-product">

		<div class="product-thumb-wrap">
			<a href="<?php the_permalink(); ?>" class="thumbnail-link">
				<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
			</a>
		</div><!-- .thumb-wrapper (end) -->

		<div class="product-content-wrap">

			<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

			<?php woocommerce_template_loop_price(); ?>

			<?php woocommerce_template_single_excerpt(); ?>

			<?php woocommerce_template_loop_add_to_cart(); ?>

		</div><!-- .product-content (end) -->

	</div><!-- .tb-product (end) -->
</li>
