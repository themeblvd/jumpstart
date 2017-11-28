<?php
/**
 * The template used for displaying "epic"
 * thumbnail above main site wrapper for
 * WooCommerce shop.
 *
 * @link http://dev.themeblvd.com/tutorial/template-parts-framework-2-5
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */

// Set where a full-screen scroll-to-section button goes.
$to = 'main';

if ( themeblvd_show_breadcrumbs() ) {

	$to = 'breadcrumbs';

}

?>
<div class="<?php echo esc_attr( themeblvd_get_att( 'epic_class' ) ); ?>">

	<header class="entry-header epic-thumb-header epic-thumb-content">

		<?php if ( is_shop() ) : ?>

			<h1 class="entry-title">
				<?php echo get_the_title( get_option( 'woocommerce_shop_page_id' ) ); ?>
			</h1>

		<?php elseif ( is_product_category() ) : ?>

			@TODO

		<?php endif; ?>

	</header>

	<figure class="epic-thumb-img">

		<?php if ( function_exists( 'is_shop' ) && is_shop() ) : ?>

			<?php echo get_the_post_thumbnail( get_option( 'woocommerce_shop_page_id' ), 'full' ); ?>

		<?php elseif ( is_product_category() ) : ?>

			@TODO

		<?php endif; ?>

	</figure>

	<?php if ( 'fs' === themeblvd_get_att( 'thumbs' ) ) : ?>

		<?php
		themeblvd_to_section( array(
			'to' => $to,
		));
		?>

	<?php endif; ?>

</div><!-- .epic-thumbnail -->
