<?php
/**
 * The template used for displaying posts in a grid.
 */
?>
<div class="grid-item column <?php echo themeblvd_get_att( 'size' ); ?><?php if( themeblvd_get_att( 'counter' ) % themeblvd_get_att( 'columns' ) == 0 ) echo ' last'; ?>">
	<div class="article-wrap">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-content">
				<?php themeblvd_the_post_thumbnail( themeblvd_get_att( 'location' ), themeblvd_get_att( 'crop' ) ); ?>
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<?php the_excerpt(); ?>
				<?php echo themeblvd_button( themeblvd_get_local( 'read_more' ), get_permalink( get_the_ID() ), 'default', '_self', 'small', 'read-more', get_the_title( get_the_ID() )  ); ?>
			</div><!-- .entry-content -->
		</article><!-- #post-<?php the_ID(); ?> -->
	</div><!-- .article-wrap (end) -->
</div><!-- .grid-item (end) -->