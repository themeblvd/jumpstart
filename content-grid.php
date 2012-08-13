<?php
/**
 * The template used for displaying posts in a grid.
 */
global $columns;
global $tb_size;
global $tb_crop; // Will be equal to $tb_size if not overridden
global $counter;
global $tb_location;
?>
<div class="grid-item column <?php echo $tb_size; ?><?php if( $counter % $columns == 0 ) echo ' last'; ?>">
	<div class="article-wrap">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-content">
				<?php themeblvd_the_post_thumbnail( $tb_location, $tb_crop ); ?>
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<?php the_excerpt(); ?>
				<?php echo themeblvd_button( themeblvd_get_local( 'read_more' ), get_permalink( get_the_ID() ), 'default', '_self', 'small', 'read-more', get_the_title( get_the_ID() )  ); ?>
			</div><!-- .entry-content -->
		</article><!-- #post-<?php the_ID(); ?> -->
	</div><!-- .article-wrap (end) -->
</div><!-- .grid-item (end) -->