<?php
/**
 * The template used for displaying posts in a grid.
 */
?>
<div class="grid-item <?php echo themeblvd_get_att('class'); ?>">
	<article <?php post_class(); ?>>

		<?php if ( has_post_format('gallery') ) : ?>

			<div class="featured-item gallery">
				<?php themeblvd_mini_gallery_slider( get_the_content(), array('size' => themeblvd_get_att('crop'))  ); ?>
			</div><!-- .gallery (end) -->

		<?php elseif ( has_post_format('video') ) : ?>

			<div class="featured-item video">
				<?php themeblvd_content_video(true); ?>
			</div><!-- .video (end) -->

		<?php elseif ( has_post_format('audio') ) : ?>

			<div class="featured-item audio">
				<?php themeblvd_content_audio(true); ?>
			</div><!-- .audio (end) -->

		<?php elseif ( themeblvd_get_att('thumbs') ) : ?>

			<div class="featured-item">
				<?php themeblvd_the_post_thumbnail( themeblvd_get_att('crop'), array('placeholder' => true) ); ?>
			</div><!-- .featured-item (end) -->

		<?php endif; ?>

		<h2 class="entry-title">
			<?php themeblvd_the_title(); ?>
		</h2>

		<?php if ( themeblvd_get_att('show_meta') ) : ?>
			<?php themeblvd_grid_meta(); ?>
		<?php endif; ?>

		<?php if ( themeblvd_get_att('excerpt') ) : ?>
			<div class="entry-content">
				<?php the_excerpt(); ?>
			</div><!-- .entry-content -->
		<?php endif; ?>

		<?php if ( themeblvd_get_att('more') == 'button' ) : ?>
			<?php echo themeblvd_button( themeblvd_get_att('more_text'), get_permalink( get_the_ID() ), 'default', '_self', 'small', 'read-more', get_the_title( get_the_ID() ) ); ?>
		<?php elseif ( themeblvd_get_att('more') == 'text' ) : ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo themeblvd_get_att('more_text'); ?></a>
		<?php endif; ?>

	</article><!-- #post-<?php the_ID(); ?> -->
</div><!-- .grid-item (end) -->