<?php
/**
 * The archive template file.
 * 
 * WARNING: This template file is a core part of the 
 * Theme Blvd WordPress Framework. This framework is 
 * designed around this file NEVER being altered. It 
 * is advised that any edits to the way this file 
 * displays its content be done with via hooks and filters.
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
			<div class="row-fluid grid-protection">

				<?php get_sidebar( 'left' ); ?>
				
				<!-- CONTENT (start) -->

				<div id="content" class="<?php echo themeblvd_get_column_class('content'); ?> clearfix" role="main">
					<div class="inner">
						<?php themeblvd_content_top(); ?>
						<?php if( themeblvd_get_part( 'archive' ) == 'grid' || themeblvd_get_part( 'archive' ) == 'archive_grid' ) : ?>
							
							<!-- ARCHIVE POST GRID (start) -->
							
							<div class="post_grid post_grid_paginated archive">
								<div class="grid-protection">
									<?php
									global $more; $more = 0;
									$counter = themeblvd_set_att( 'counter', 1 );
									$columns = themeblvd_get_att( 'columns' );
									if ( have_posts() ) {
										while ( have_posts() ) {
											the_post();
											if( $counter == 1 ) themeblvd_open_row();
											get_template_part( 'content', themeblvd_get_part( 'archive' ) );
											if( $counter % $columns == 0 ) themeblvd_close_row();
											if( $counter % $columns == 0 && themeblvd_get_att( 'posts_per_page' ) != $counter ) themeblvd_open_row();
											$counter = themeblvd_set_att( 'counter', $counter+1 );
										}
										if( ($counter-1) != themeblvd_get_att( 'posts_per_page' ) ) themeblvd_close_row();
									} else {
										echo '<p>'.themeblvd_get_local( 'archive_no_posts' ).'</p>';
									}
									?>
								</div><!-- .grid-protection (end) -->
								<?php themeblvd_pagination(); ?>
							</div><!-- .post_grid (end) -->
							
							<!-- ARCHIVE POST GRID (end) -->
							
						<?php else : ?>
							
							<!-- ARCHIVE POST LIST (start) -->
							
							<div class="post_list post_list_paginated archive">
								<?php if ( have_posts() ) : ?>
									<?php while ( have_posts() ) : the_post(); ?>
										<?php get_template_part( 'content', themeblvd_get_part( 'archive' ) ); ?>
									<?php endwhile; ?>
								<?php else : ?>
									<p><?php echo themeblvd_get_local( 'archive_no_posts' ); ?></p>
								<?php endif; ?>
								<?php themeblvd_pagination(); ?>
							</div><!-- .blogroll (end) -->
							
							<!-- ARCHIVE POST LIST (end) -->
							
						<?php endif; ?>
			
					</div><!-- .inner (end) -->
				</div><!-- #content (end) -->
			
				<!-- CONTENT (end) -->		
				
				<?php get_sidebar( 'right' ); ?>
			
			</div><!-- .grid-protection (end) -->
		</div><!-- .sidebar_layout-inner (end) -->
	</div><!-- #sidebar_layout (end) -->

<?php get_footer(); ?>