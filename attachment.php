<?php
/**
 * The template file for attachments.
 *
 * WARNING: This template file is a core part of the
 * Theme Blvd WordPress Framework. It is advised
 * that any edits to the way this file displays its
 * content be done with via hooks, filters, and
 * template parts.
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package		Theme Blvd WordPress Framework
 */

get_header();
?>

	<div id="sidebar_layout" class="clearfix">
		<div class="sidebar_layout-inner">
			<div class="row grid-protection">

				<!-- CONTENT (start) -->

				<div id="content" class="<?php echo esc_attr( themeblvd_get_column_class('content') ); ?> clearfix" role="main">
					<div class="inner">
						<?php themeblvd_content_top(); ?>

						<?php while ( have_posts() ) : the_post(); ?>

							<?php get_template_part( 'content', themeblvd_get_part('attachment') ); ?>

							<?php if ( themeblvd_supports( 'comments', 'attachments' ) ) : ?>
								<?php comments_template( '', true ); ?>
							<?php endif; ?>

						<?php endwhile; ?>

						<?php themeblvd_content_bottom(); ?>
					</div><!-- .inner (end) -->
				</div><!-- #content (end) -->

				<!-- CONTENT (end) -->

				<!-- SIDEBARS (start) -->

				<?php get_sidebar('left'); ?>

				<?php get_sidebar('right'); ?>

				<!-- SIDEBARS (end) -->

			</div><!-- .grid-protection (end) -->
		</div><!-- .sidebar_layout-inner (end) -->
	</div><!-- #sidebar_layout (end) -->

<?php get_footer(); ?>
