<?php
/**
 * Template Name: Post Grid
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
 * @package 	Theme Blvd WordPress Framework
 */

get_header();
?>

	<div id="sidebar_layout" class="clearfix">
		<div class="sidebar_layout-inner">
			<div class="row-fluid grid-protection">

				<?php get_sidebar( 'left' ); ?>

				<!-- CONTENT (start) -->

				<div id="content" class="<?php echo themeblvd_get_column_class('content'); ?> clearfix" role="main">
					<div class="inner">
						<?php themeblvd_content_top(); ?>

						<div class="primary-post-grid post_grid_paginated post_grid<?php echo themeblvd_get_classes( 'element_post_grid_paginated', true ); ?>">
							<div class="grid-protection">
								<?php
								// Query the post grid
								$post_grid = new WP_Query( themeblvd_get_second_query() );
								$counter = themeblvd_set_att( 'counter', 1 );
								$columns = themeblvd_get_att( 'columns' );

								// Start the loop
								if ( $post_grid->have_posts() ) {

									while ( $post_grid->have_posts() ) {

										$post_grid->the_post();

										global $more;
										$more = 0;

										// If this is the very first post, open the first row
										if ( $counter == 1 )
											themeblvd_open_row();

										// Get template part, framework default is content-grid.php
										get_template_part( 'content', themeblvd_get_part( 'grid_paginated' ) );

										// If last post in a row, close the row
										if ( $counter % $columns == 0 )
											themeblvd_close_row();

										// If first post in a row, open the row
										if ( $counter % $columns == 0 && themeblvd_get_att( 'posts_per_page' ) != $counter )
											themeblvd_open_row();

										// Increment the counter with global template attribute accounted for
										$counter = themeblvd_set_att( 'counter', $counter+1 );
									}

									if ( ($counter-1) != themeblvd_get_att( 'posts_per_page' ) )
										themeblvd_close_row();

								} else {

									// No posts to display
									printf( '<p>%s</p>', themeblvd_get_local( 'archive_no_posts' ) );

								}
								?>
							</div><!-- .grid-protection (end) -->
							<?php themeblvd_pagination( $post_grid->max_num_pages ); ?>
							<?php wp_reset_postdata(); ?>
						</div><!-- .post_grid (end) -->

						<?php themeblvd_content_bottom(); ?>
					</div><!-- .inner (end) -->
				</div><!-- #content (end) -->

				<!-- CONTENT (end) -->

				<?php get_sidebar( 'right' ); ?>

			</div><!-- .grid-protection (end) -->
		</div><!-- .sidebar_layout-inner (end) -->
	</div><!-- #sidebar_layout (end) -->

<?php get_footer(); ?>