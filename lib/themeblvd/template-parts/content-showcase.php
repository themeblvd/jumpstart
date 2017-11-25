<?php
/**
 * The template used for displaying posts in a showcase.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */
$link = get_post_meta( get_the_ID(), '_tb_thumb_link', true );
?>
<div <?php post_class(themeblvd_get_att('class')); ?>>

	<div class="featured-item showcase <?php if ( ! $link || $link == 'inactive' ) echo 'tb-thumb-link'; ?>">

		<?php if ( themeblvd_get_att('titles') ) : ?>
			<?php themeblvd_the_post_thumbnail( themeblvd_get_att('crop'), array('placeholder' => true, 'img_before' => themeblvd_get_item_info()) ); ?>
		<?php else : ?>
			<?php themeblvd_the_post_thumbnail( themeblvd_get_att('crop'), array('placeholder' => true) ); ?>
		<?php endif; ?>

	</div><!-- .featured-item (end) -->

	<?php if ( themeblvd_get_att('excerpt') ) : ?>
		<div class="entry-content">
			<?php the_excerpt(); ?>
		</div><!-- .entry-content -->
	<?php endif; ?>

</div><!-- .showcase-item (end) -->