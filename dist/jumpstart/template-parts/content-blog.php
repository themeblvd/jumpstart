<?php
/**
 * The template used for displaying each post
 * within a blog post display.
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

	<header class="<?php echo $header_class; ?>">

		<?php if ( ! has_post_format( 'aside' ) && ! has_post_format( 'quote' ) ) : ?>

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

	<?php if ( has_post_format( 'gallery' ) ) : ?>

		<div class="featured-item featured-gallery popout">

			<?php themeblvd_gallery_slider(); ?>

		</div><!-- .featured-gallery -->

	<?php elseif ( has_post_format( 'video' ) ) : ?>

		<div class="featured-item featured-video popout">

			<?php themeblvd_content_video(); ?>

		</div><!-- .featured-video -->

	<?php elseif ( has_post_format( 'audio' ) ) : ?>

		<div class="featured-item featured-audio popout">

			<?php themeblvd_content_audio(); ?>

		</div><!-- .featured-audio -->

	<?php elseif ( has_post_format( 'quote' ) ) : ?>

		<div class="featured-item featured-quote bg-primary">

			<?php themeblvd_content_quote(); ?>

		</div><!-- .featured-quote -->

	<?php elseif ( has_post_thumbnail() && themeblvd_get_att( 'thumbs' ) ) : ?>

		<div class="featured-item featured-image standard popout">

			<?php themeblvd_the_post_thumbnail(); ?>

		</div><!-- .featured-item -->

	<?php endif; ?>

	<div class="entry-content clearfix">

		<?php
		/*
		 * By using themeblvd_blog_content(), we can
		 * automatically detect whether to use the_content()
		 * or the_excerpt(), depending on framework settings.
		 */
		themeblvd_blog_content( themeblvd_get_att( 'content' ) );
		?>

	</div><!-- .entry-content -->

	<?php if ( is_single() ) : ?>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-link">' . themeblvd_get_local( 'pages' ) . ': ',
			'after'  => '</div>',
		));
		?>

	<?php endif; ?>

	<?php
	edit_post_link(
		themeblvd_get_local( 'edit_post' ),
		'<div class="edit-link"><i class="fa fa-edit"></i> ',
		'</div>'
	);
	?>

</article><!-- #post-<?php the_ID(); ?> -->
