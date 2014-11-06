<?php
/**
 * The template used for displaying posts in a showcase.
 */
?>
<div <?php post_class(themeblvd_get_att('class')); ?>>

	<div class="featured-item showcase">

		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( themeblvd_get_att('crop') ); ?>
		<?php else : ?>
			<?php echo themeblvd_get_media_placeholder(); ?>
		<?php endif; ?>

		<div class="overlay">
			<div class="wrap">

				<?php if ( themeblvd_get_att('titles') ) : ?>
					<h2 class="entry-title"><?php the_title(); ?></h2>
				<?php endif; ?>

				<div class="badges">
					<?php themeblvd_post_thumbnail_link_badge( get_the_ID(), false ); // Will skip if image links to post ?>
					<?php themeblvd_post_thumbnail_link_badge( get_the_ID(), 'force' ); // Force to link to the post ?>
				</div><!-- .badges (end) -->

			</div><!-- .wrap (end) -->
		</div><!-- .overlay (end) -->

	</div><!-- .featured-item (end) -->

	<?php if ( themeblvd_get_att('excerpt') ) : ?>
		<div class="entry-content">
			<?php the_excerpt(); ?>
		</div><!-- .entry-content -->
	<?php endif; ?>

</div><!-- .showcase-item (end) -->