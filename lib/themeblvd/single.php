<?php
/**
 * The template file for single posts.
 *
 * WARNING: This template file is a core part of the
 * Theme Blvd WordPress Framework. It is advised
 * that any edits to the way this file displays its
 * content be done with via actions, filters, and
 * template parts.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */

get_header();
?>

<div id="sidebar_layout" class="clearfix">

	<div class="sidebar_layout-inner">

		<div class="row grid-protection">

			<!-- CONTENT (start) -->

			<div id="content" class="<?php echo esc_attr( themeblvd_get_column_class( 'content' ) ); ?> clearfix" role="main">

				<div class="inner">

					<?php themeblvd_content_top(); ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php themeblvd_get_template_part( 'single' ); ?>

						<?php themeblvd_single_footer(); ?>

						<?php if ( themeblvd_supports( 'comments', 'posts' ) ) : ?>

							<?php comments_template( '', true ); ?>

						<?php endif; ?>

					<?php endwhile; ?>

					<?php themeblvd_content_bottom(); ?>

				</div><!-- .inner -->

			</div><!-- #content -->

			<!-- CONTENT (end) -->

			<!-- SIDEBARS (start) -->

			<?php get_sidebar( 'left' ); ?>

			<?php get_sidebar( 'right' ); ?>

			<!-- SIDEBARS (end) -->

		</div><!-- .grid-protection -->

	</div><!-- .sidebar_layout-inner -->

</div><!-- #sidebar_layout -->

<?php get_footer(); ?>
