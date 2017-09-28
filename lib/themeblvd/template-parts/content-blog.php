<?php
/**
 * The template used for displaying posts in a blog.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(themeblvd_get_att('class')); ?>>

	<header class="entry-header<?php if ( themeblvd_get_att('show_meta') ) echo ' has-meta'; ?>">

		<?php if ( ! has_post_format('aside') && ! has_post_format('quote') ) : ?>
			<h1 class="entry-title">
				<?php themeblvd_the_title(); ?>
			</h1>
		<?php endif; ?>

		<?php if ( themeblvd_get_att('show_meta') ) : ?>
			<div class="meta-wrapper">
				<?php themeblvd_blog_meta(); ?>
			</div><!-- .meta-wrapper (end) -->
		<?php endif; ?>

	</header><!-- .entry-header -->

	<?php if ( has_post_format('gallery') ) : ?>

		<div class="featured-item featured-gallery popout">
			<?php themeblvd_gallery_slider(); ?>
		</div><!-- .featured-gallery (end) -->

	<?php elseif ( has_post_format('video') ) : ?>

		<div class="featured-item featured-video popout">
			<?php themeblvd_content_video(); ?>
		</div><!-- .featured-video (end) -->

	<?php elseif ( has_post_format('audio') ) : ?>

		<div class="featured-item featured-audio popout">
			<?php themeblvd_content_audio(); ?>
		</div><!-- .featured-audio (end) -->

	<?php elseif ( has_post_format('quote') ) : ?>

		<div class="featured-item featured-quote bg-primary">
			<?php themeblvd_content_quote(); ?>
		</div><!-- .featured-quote (end) -->

	<?php elseif ( has_post_thumbnail() && themeblvd_get_att('thumbs') ) : ?>

		<div class="featured-item featured-image standard popout">
			<?php themeblvd_the_post_thumbnail(); ?>
		</div><!-- .featured-item (end) -->

	<?php endif; ?>

	<div class="entry-content clearfix">
		<?php themeblvd_blog_content( themeblvd_get_att('content') ); // Abide by relevant excerpt vs content theme option ?>
	</div><!-- .entry-content -->

	<?php if ( is_single() ) : ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . themeblvd_get_local('pages').': ', 'after' => '</div>' ) ); ?>
	<?php endif; ?>

	<?php edit_post_link( themeblvd_get_local( 'edit_post' ), '<div class="edit-link"><i class="fa fa-edit"></i> ', '</div>' ); ?>

</article><!-- #post-<?php the_ID(); ?> -->
