<?php
/**
 * The default template for displaying content in
 * a small post grid.
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
<div class="grid-item <?php echo esc_attr( themeblvd_get_att( 'class' ) ); ?>">

	<article <?php post_class(); ?>>

		<?php
		themeblvd_the_post_thumbnail(
			themeblvd_get_att( 'crop' ),
			array(
				'placeholder' => true,
			)
		);
		?>

		<?php if ( themeblvd_get_att( 'show_title' ) || themeblvd_get_att( 'show_meta' ) ) : ?>

			<div class="content-wrapper">

				<?php if ( themeblvd_get_att( 'show_title' ) ) : ?>

					<h3 class="entry-title">
						<?php themeblvd_the_title(); ?>
					</h3>

				<?php endif; ?>

				<?php if ( themeblvd_get_att( 'show_meta' ) ) : ?>

					<div class="meta-wrapper">

						<?php
						/**
						 * Filters the arguments used to build the meta
						 * info for each post in a small post grid.
						 *
						 * @see themeblvd_get_meta()
						 *
						 * @since @@name-framework 2.7.0
						 *
						 * @param array Arguments for themeblvd_get_meta().
						 */
						$meta = apply_filters( 'themeblvd_small_grid_meta_args', array(
							'include' => array( 'time' ),
							'icons'   => array(),
						) );

						echo themeblvd_get_meta( $meta );
						?>

					</div><!-- .meta-wrapper -->

				<?php endif; ?>

			</div><!-- .content-wrapper -->

		<?php endif; ?>

	</article><!-- #post-<?php the_ID(); ?> -->

</div><!-- .grid-item -->
