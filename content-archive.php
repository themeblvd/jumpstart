<?php
/**
 * The default template for displaying content in an archive.
 */
global $tb_location;
global $tb_content;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title  entry-title-with-meta"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
	</header><!-- .entry-header -->
	<div class="meta-wrapper">
		<?php themeblvd_blog_meta(); ?>
	</div><!-- .meta-wrapper (end) -->
	<div class="entry-content">
		<?php themeblvd_the_post_thumbnail( $tb_location ); ?>
		<?php themeblvd_blog_content( $tb_content ); ?>
		<?php themeblvd_blog_tags(); ?>
		<div class="clear"></div>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->