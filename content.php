<?php
/**
 * The default template for displaying content in single.php.
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if( is_single() ) : ?>
			<h1 class="entry-title<?php if( themeblvd_get_att( 'show_meta' ) ) echo ' entry-title-with-meta'; ?>">
				<?php the_title(); ?>
			</h1>
		<?php else : ?>
			<h1 class="entry-title<?php if( themeblvd_get_att( 'show_meta' ) ) echo ' entry-title-with-meta'; ?>">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
			</h1>
		<?php endif; ?>
		<?php if( themeblvd_get_att( 'show_meta' ) ) : ?>
			<?php themeblvd_blog_meta(); ?>
		<?php endif; ?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php themeblvd_the_post_thumbnail( 'single', themeblvd_get_att( 'size' ) ); ?>
		<?php the_content(); ?>
		<div class="clear"></div>
		<?php themeblvd_blog_tags(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . themeblvd_get_local('pages').': ', 'after' => '</div>' ) ); ?>
		<?php edit_post_link( themeblvd_get_local( 'edit_page' ), '<p class="edit-link clearfix">', '</p>' ); ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
