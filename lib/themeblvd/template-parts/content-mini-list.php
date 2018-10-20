<?php
/**
 * The default template for displaying content
 * in a mini post list.
 *
 * @link http://dev.themeblvd.com/tutorial/template-parts-framework-2-5
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */

$class = array();

if ( themeblvd_get_att( 'thumbs' ) ) {

	$class[] = 'has-thumbnail';

}

if ( themeblvd_get_att( 'show_meta' ) ) {

	$class[] = 'has-meta';

}

$class = implode( ' ', $class );

/**
 * Filters the arguments used to build the meta
 * info for each post in a mini post list.
 *
 * @see themeblvd_get_meta()
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array Arguments for themeblvd_get_meta().
 */
$meta = apply_filters( 'themeblvd_mini_list_meta_args', array(
	'include'  => array( 'time', 'comments' ),
	'comments' => 'mini',
	'time'     => 'ago',
) );

?>
<article <?php post_class( $class ); ?>>

	<?php if ( themeblvd_get_att( 'thumbs' ) ) : ?>

		<div class="thumb-wrapper">

			<?php if ( 'date' === themeblvd_get_att( 'thumbs' ) ) : ?>

				<?php themeblvd_date_block(); ?>

			<?php else : ?>

				<?php
				themeblvd_the_post_thumbnail(
					'tb_thumb',
					array(
						'placeholder' => true,
					)
				);
				?>

			<?php endif; ?>

		</div><!-- .thumb-wrapper -->

	<?php endif; ?>

	<div class="content-wrapper">

		<h3 class="entry-title"><?php themeblvd_the_title(); ?></h3>

		<?php if ( themeblvd_get_att( 'show_meta' ) ) : ?>

			<div class="meta-wrapper">

				<?php echo themeblvd_get_meta( $meta ); ?>

			</div><!-- .meta-wrapper -->

		<?php endif; ?>

	</div><!-- .content-wrapper -->

</article><!-- #post-<?php the_ID(); ?> -->
