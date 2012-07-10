<?php
/**
 * The default template for displaying content in blogroll.
 */
global $location;
global $size;
global $content;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title entry-title-with-meta"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
		<?php themeblvd_blog_meta(); ?>
	</header><!-- .entry-header (end) -->
	<div class="entry-content">
		<?php themeblvd_the_post_thumbnail( $location, $size ); ?>
		<?php themeblvd_blog_content( $content ); ?>
		<?php themeblvd_blog_tags(); ?>
		<div class="clear"></div>
	</div><!-- .entry-content (end) -->
</article><!-- #post-<?php the_ID(); ?> -->
