<?php
/**
 * The default template for displaying content in an archive and search results.
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title entry-title-with-meta">
			<?php themeblvd_the_title(); ?>
		</h1>
		<?php if ( 'page' != get_post_type() ) : ?>
			<div class="meta-wrapper">
				<?php themeblvd_blog_meta(); ?>
			</div><!-- .meta-wrapper (end) -->
		<?php endif; ?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php themeblvd_the_post_thumbnail( themeblvd_get_att( 'location' ) ); ?>
		<?php themeblvd_blog_content( themeblvd_get_att( 'content' ) ); ?>
		<?php themeblvd_blog_tags(); ?>
		<div class="clear"></div>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->