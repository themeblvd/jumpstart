<?php
/**
 * The template used for displaying posts in a showcase.
 */
?>
<div <?php post_class(themeblvd_get_att('class')); ?>>

	<div class="featured-item showcase">

		<?php if ( themeblvd_get_att('titles') ) : ?>
			<?php themeblvd_the_post_thumbnail( themeblvd_get_att('crop'), array('placeholder' => true, 'img_before' => '<span class="item-title">'.get_the_title().'</span>') ); ?>
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
