<?php
/**
 * The default template for displaying content in blogroll.
 */
global $post;
global $location;
global $size;
global $content;
global $show_meta;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title<?php if( $show_meta ) echo ' entry-title-with-meta'; ?>"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
		<?php if( $show_meta ) : ?>
			<?php themeblvd_blog_meta(); ?>
		<?php endif; ?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php themeblvd_the_post_thumbnail( $location, $size ); ?>
		<?php the_content(); ?>
		<?php themeblvd_blog_tags(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . themeblvd_get_local('pages').': ', 'after' => '</div>' ) ); ?>
		<div class="clear"></div>
		<?php edit_post_link( __( '<p>Edit</p>', 'themeblvd' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
