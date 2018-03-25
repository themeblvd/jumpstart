<?php
/**
 * The template used for displaying content
 * in attachment.php.
 *
 * @link http://dev.themeblvd.com/tutorial/template-parts-framework-2-5
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( themeblvd_get_att( 'class' ) ); ?>>

	<header class="entry-header">

		<h1 class="entry-title">
			<?php themeblvd_the_title(); ?>
		</h1>

	</header><!-- .entry-header -->

	<?php if ( wp_attachment_is_image( get_the_ID() ) ) : ?>

		<div class="featured-item featured-image">

			<?php
			themeblvd_the_post_thumbnail(
				themeblvd_get_att( 'crop' ),
				array(
					'attachment_id' => get_the_id(),
					'link'          => 'thumbnail',
				)
			);
			?>

		</div><!-- .featured-item -->

	<?php endif; ?>

	<div class="entry-content clearfix">

		<?php the_content(); ?>

	</div><!-- .entry-content -->

	<?php
	edit_post_link(
		themeblvd_get_local( 'edit_attachment' ),
		'<div class="edit-link">',
		'</div>'
	);
	?>

</article>
