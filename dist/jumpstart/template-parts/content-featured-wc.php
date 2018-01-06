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
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */

// Set where a full-screen scroll-to-section button goes.
$to = 'main';

if ( themeblvd_show_breadcrumbs() ) {

	$to = 'breadcrumbs';

}

?>
<div class="<?php echo esc_attr( themeblvd_get_att( 'epic_class' ) ); ?>">

	<header class="entry-header epic-thumb-header epic-thumb-content">

		<h1 class="entry-title">
			<?php echo get_the_title( get_option( 'woocommerce_shop_page_id' ) ); ?>
		</h1>

	</header>

	<?php
	$attachment_id = get_post_thumbnail_id( get_option( 'woocommerce_shop_page_id' ) );

	themeblvd_the_archive_banner_image( array(
		'id'   => $attachment_id,
		'src'  => wp_get_attachment_image_url( $attachment_id ),
		'alt'  => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true )
		'crop' => 'full'
	) );
	?>

</div><!-- .epic-banner (end) -->
