<?php
/**
 * The template used for displaying "epic" thumbnail
 * above main site wrapper.
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

if ( is_page_template( 'template_builder.php' ) ) {

	$to = 'custom-main';

} elseif ( themeblvd_show_breadcrumbs() ) {

	$to = 'breadcrumbs';

}

?>
<div class="<?php echo esc_attr( themeblvd_get_att( 'epic_class' ) ); ?>">

	<?php if ( has_post_format( 'quote' ) ) : ?>

		<div class="featured-quote epic-thumb-quote epic-thumb-content">

			<?php themeblvd_content_quote(); ?>

		</div>

	<?php else : ?>

		<?php if ( ( ! is_page() || 'hide' !== get_post_meta( get_the_ID(), '_tb_title', true ) ) || themeblvd_get_att( 'show_meta' ) || has_post_format( 'audio' ) ) : ?>

			<header class="entry-header epic-thumb-header epic-thumb-content">

				<?php if ( ! is_page() || 'hide' !== get_post_meta( get_the_ID(), '_tb_title', true ) ) : ?>

					<h1 class="entry-title"><?php the_title(); ?></h1>

				<?php endif; ?>

				<?php if ( themeblvd_get_att( 'show_meta' ) ) : ?>

					<div class="meta-wrapper above">

						<?php themeblvd_blog_meta(); ?>

					</div><!-- .meta-wrapper -->

				<?php endif; ?>

				<?php if ( has_post_format( 'audio' ) ) : ?>

					<?php themeblvd_content_audio(); ?>

				<?php endif; ?>

			</header>

		<?php endif; ?>

	<?php endif; ?>

	<?php if ( has_post_format( 'gallery' ) ) : ?>

		<?php themeblvd_gallery_slider(); ?>

	<?php else : ?>

		<?php if ( 'fs' === themeblvd_get_att( 'thumbs' ) ) : ?>

			<?php
			themeblvd_bg_parallax( array(
				'src' => wp_get_attachment_image_url( get_post_thumbnail_id(), 'tb_x_large' ),
			));
			?>

		<?php else : ?>

			<?php the_post_thumbnail( 'full' ); ?>

		<?php endif; ?>

	<?php endif; ?>

	<?php if ( 'fs' === themeblvd_get_att( 'thumbs' ) ) : ?>

		<?php
		themeblvd_to_section( array(
			'to' => $to,
		));
		?>

	<?php endif; ?>

</div><!-- .epic-thumb (end) -->
