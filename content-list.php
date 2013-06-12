<?php
/**
 * The default template for displaying content in post list.
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title entry-title-with-meta"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
		<?php themeblvd_blog_meta(); ?>
	</header><!-- .entry-header (end) -->
	<div class="entry-content">
		<?php themeblvd_the_post_thumbnail( themeblvd_get_att( 'location' ), themeblvd_get_att( 'size' ) ); ?>
		<?php themeblvd_blog_content( themeblvd_get_att( 'content' ) ); ?>
		<?php themeblvd_blog_tags(); ?>
		<div class="clear"></div>
	</div><!-- .entry-content (end) -->
</article><!-- #post-<?php the_ID(); ?> -->
