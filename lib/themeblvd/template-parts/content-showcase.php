<?php
/**
 * The template used for displaying posts
 * in a post showcase.
 *
 * @link http://dev.themeblvd.com/tutorial/template-parts-framework-2-5
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */

$link = get_post_meta( get_the_ID(), '_tb_thumb_link', true );

$class = 'featured-item showcase';

if ( ! $link || 'inactive' === $link ) {

	$class .= ' tb-thumb-link';

}

?>
<div <?php post_class( themeblvd_get_att( 'class' ) ); ?>>

	<div class="<?php echo $class; ?>">

		<?php if ( themeblvd_get_att( 'titles' ) ) : ?>

			<?php
			themeblvd_the_post_thumbnail(
				themeblvd_get_att( 'crop' ),
				array(
					'placeholder' => true,
					'img_before'  => themeblvd_get_item_info(),
				)
			);
			?>

		<?php else : ?>

			<?php
			themeblvd_the_post_thumbnail(
				themeblvd_get_att( 'crop' ),
				array(
					'placeholder' => true,
				)
			);
			?>

		<?php endif; ?>

	</div><!-- .featured-item -->

	<?php if ( themeblvd_get_att( 'excerpt' ) ) : ?>

		<div class="entry-content">

			<?php the_excerpt(); ?>

		</div><!-- .entry-content -->

	<?php endif; ?>

</div><!-- .showcase-item -->
