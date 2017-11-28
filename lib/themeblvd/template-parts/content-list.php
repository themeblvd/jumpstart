<?php
/**
 * The default template for displaying content
 * in a post list.
 *
 * @link http://dev.themeblvd.com/tutorial/template-parts-framework-2-5
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */
?>
<article <?php post_class( themeblvd_get_att( 'class' ) ); ?>>

	<?php if ( themeblvd_get_att( 'thumbs' ) ) : ?>

		<div class="thumb-wrapper">

			<?php if ( 'date' === themeblvd_get_att( 'thumbs' ) ) : ?>

				<?php themeblvd_date_block(); ?>

			<?php else : ?>

				<?php
				themeblvd_the_post_thumbnail( 'tb_thumb', array(
					'placeholder' => true,
				));
				?>

			<?php endif; ?>

		</div><!-- .thumb-wrapper -->

	<?php endif; ?>

	<header class="entry-header">

		<?php if ( ! has_post_format( 'quote' ) && ! has_post_format( 'aside' ) ) : ?>

			<h2 class="entry-title">
				<?php themeblvd_the_title(); ?>
			</h2>

		<?php endif; ?>

		<?php if ( themeblvd_get_att( 'show_meta' ) ) : ?>

			<div class="meta-wrapper">

				<?php themeblvd_blog_meta(); ?>

			</div><!-- .meta-wrapper -->

		<?php endif; ?>

	</header><!-- .entry-header -->

	<div class="entry-content">

		<?php if ( has_post_format( 'gallery' ) ) : ?>

			<div class="featured-item featured-gallery">

				<?php themeblvd_mini_gallery_slider(); ?>

			</div><!-- .featured-gallery -->

		<?php elseif ( has_post_format( 'video' ) ) : ?>

			<div class="featured-item featured-video">

				<?php themeblvd_content_video(); ?>

			</div><!-- .featured-video -->

		<?php elseif ( has_post_format( 'audio' ) ) : ?>

			<div class="featured-item featured-audio">

				<?php themeblvd_content_audio( false ); ?>

			</div><!-- .featured-audio -->

		<?php endif; ?>

		<?php if ( has_post_format( 'quote' ) ) : ?>

			<?php themeblvd_content_quote(); ?>

		<?php else : ?>

			<?php the_excerpt(); ?>

		<?php endif; ?>

		<?php if ( 'button' === themeblvd_get_att( 'more' ) ) : ?>

			<?php
			echo themeblvd_button(
				themeblvd_get_att( 'more_text' ),
				get_permalink( get_the_ID() ),
				'default',
				'_self',
				'small',
				'read-more',
				get_the_title( get_the_ID() )
			);
			?>

		<?php elseif ( 'text' === themeblvd_get_att( 'more' ) ) : ?>

			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
				<?php echo themeblvd_get_att( 'more_text' ); ?>
			</a>

		<?php endif; ?>

	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
