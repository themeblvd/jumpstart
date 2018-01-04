<?php
/**
 * The default template for displaying content
 * of single posts.
 *
 * @link http://dev.themeblvd.com/tutorial/template-parts-framework-2-5
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */

$header_class = 'entry-header';

if ( themeblvd_get_att( 'show_meta' ) ) {

	$header_class .= ' has-meta';
}

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( themeblvd_get_att( 'class' ) ); ?>>

	<?php if ( ! themeblvd_get_att( 'epic_thumb' ) && ! has_post_format( 'quote' ) ) : ?>

		<header class="<?php echo $header_class; ?>">

			<?php if ( ! has_post_format( 'aside' ) ) : ?>

				<h1 class="entry-title">
					<?php themeblvd_the_title(); ?>
				</h1>

			<?php endif; ?>

			<?php if ( themeblvd_get_att( 'show_meta' ) ) : ?>

				<div class="meta-wrapper">

					<?php themeblvd_blog_meta(); ?>

				</div><!-- .meta-wrapper -->

			<?php endif; ?>

		</header><!-- .entry-header -->

	<?php endif; ?>

	<?php if ( has_post_format( 'gallery' ) && ! themeblvd_get_att( 'epic_thumb' ) ) : ?>

		<div class="featured-item featured-gallery popout">

			<?php themeblvd_gallery_slider(); ?>

		</div><!-- .featured-gallery -->

	<?php elseif ( has_post_format( 'video' ) ) : ?>

		<div class="featured-item featured-video popout">

			<?php themeblvd_content_video(); ?>

		</div><!-- .featured-video -->

	<?php elseif ( has_post_format( 'audio' ) && themeblvd_get_att( 'thumbs' ) && ! themeblvd_get_att( 'epic_thumb' ) ) : ?>

		<div class="featured-item featured-audio popout">

			<?php themeblvd_content_audio(); ?>

		</div><!-- .featured-audio -->

	<?php elseif ( has_post_format( 'quote' ) && ! themeblvd_get_att( 'epic_thumb' ) ) : ?>

		<?php if ( themeblvd_get_att( 'show_meta' ) ) : ?>

			<header class="entry-header quote">

				<div class="meta-wrapper">

					<?php themeblvd_blog_meta(); ?>

				</div><!-- .meta-wrapper -->

			</header><!-- .entry-header -->

		<?php endif; ?>

		<div class="featured-item featured-quote bg-primary">

			<?php themeblvd_content_quote(); ?>

		</div><!-- .featured-quote -->

	<?php elseif ( has_post_thumbnail() && themeblvd_get_att( 'thumbs' ) && ! themeblvd_get_att( 'epic_thumb' ) ) : ?>

		<div class="featured-item featured-image standard popout">

			<?php themeblvd_the_post_thumbnail(); ?>

		</div><!-- .featured-item -->

	<?php endif; ?>

	<div class="entry-content clearfix">

		<?php the_content(); ?>

		<?php
		edit_post_link(
			themeblvd_get_local( 'edit_post' ),
			'<p class="edit-link">',
			'</p>'
		);
		?>

	</div><!-- .entry-content -->

	<?php if ( is_single() ) : ?>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-link">' . themeblvd_get_local( 'pages' ) . ': ',
			'after'  => '</div>',
		) );
		?>

	<?php endif; ?>

	<?php if ( themeblvd_get_att( 'show_sub_meta' ) ) : ?>

		<?php themeblvd_blog_sub_meta(); ?>

	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
