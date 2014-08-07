<?php
/**
 * The default template for displaying content of posts.
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<h1 class="entry-title<?php if ( themeblvd_get_att( 'show_meta' ) ) echo ' entry-title-with-meta'; ?>">
			<?php themeblvd_the_title(); ?>
		</h1>
		<?php if ( themeblvd_get_att( 'show_meta' ) ) : ?>
			<div class="meta-wrapper">
				<?php themeblvd_blog_meta(); ?>
			</div><!-- .meta-wrapper (end) -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php themeblvd_the_post_thumbnail( 'single', themeblvd_get_att( 'size' ) ); ?>

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<?php if ( themeblvd_get_att( 'show_sub_meta' ) ) : ?>
		<div class="sub-meta-wrapper">
			<?php themeblvd_blog_sub_meta(); ?>
		</div><!-- .sub-meta-wrapper (end) -->
	<?php endif; ?>

	<?php wp_link_pages( array( 'before' => '<div class="page-link">' . themeblvd_get_local('pages').': ', 'after' => '</div>' ) ); ?>

	<?php edit_post_link( themeblvd_get_local( 'edit_post' ), '<div class="edit-link">', '</div>' ); ?>

</article><!-- #post-<?php the_ID(); ?> -->
