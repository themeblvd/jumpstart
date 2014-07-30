<?php
/**
 * The archive template file.
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

				<div id="content" class="<?php echo themeblvd_get_column_class('content'); ?> clearfix" role="main">
					<div class="inner">
						<?php themeblvd_content_top(); ?>

						<?php if ( themeblvd_is_grid_mode() ) : ?>

							<!-- ARCHIVE POST GRID (start) -->

							<div class="post_grid_paginated post_grid archive">
								<?php themeblvd_post_grid( array('primary' => true) ); ?>
							</div><!-- .primary-post-grid (end) -->

							</div><!-- .post_grid (end) -->

							<!-- ARCHIVE POST GRID (end) -->

						<?php else : ?>

							<!-- ARCHIVE POST LIST (start) -->

							<div class="post_list_paginated post_list archive">
								<?php themeblvd_post_list( array('primary' => true) ); ?>
							</div><!-- .primary-post-list (end) -->

							<!-- ARCHIVE POST LIST (end) -->

						<?php endif; ?>

						<?php themeblvd_content_bottom(); ?>
					</div><!-- .inner (end) -->
				</div><!-- #content (end) -->

				<!-- CONTENT (end) -->

				<!-- SIDEBARS (start) -->

				<?php get_sidebar( 'left' ); ?>

				<?php get_sidebar( 'right' ); ?>

				<!-- SIDEBARS (end) -->

			</div><!-- .grid-protection (end) -->
		</div><!-- .sidebar_layout-inner (end) -->
	</div><!-- #sidebar_layout (end) -->

<?php get_footer(); ?>