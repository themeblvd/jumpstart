<?php
/**
 * Template Name: Post List
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
						<div class="primary-post-list element-post_list_paginated post_list<?php echo themeblvd_get_classes( 'element_post_list_paginated', true ); ?>">
							<?php
							$post_list = new WP_Query( themeblvd_get_second_query() );
							if( $post_list->have_posts() ) {
								while( $post_list->have_posts() ) {
									$post_list->the_post();
									global $more; $more = 0;
									get_template_part( 'content', themeblvd_get_part( 'list_paginated' ) );
								}
							} else {
								printf( '<p>%s</p>', themeblvd_get_local( 'archive_no_posts' ) );
							}
							themeblvd_pagination( $post_list->max_num_pages );
							wp_reset_postdata();
							?>
						</div><!-- .post_list (end) -->
					</div><!-- .inner (end) -->
				</div><!-- #content (end) -->
					
				<!-- CONTENT (end) -->
				
				<?php get_sidebar( 'right' ); ?>
			
			</div><!-- .grid-protection (end) -->
		</div><!-- .sidebar_layout-inner (end) -->
	</div><!-- #sidebar_layout (end) -->
		
<?php get_footer(); ?>