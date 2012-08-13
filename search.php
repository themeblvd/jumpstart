<?php
/**
 * The search results template file.
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
 * @package 	Theme Blvd WordPress Framework
 */

$tb_content = themeblvd_get_option( 'archive_content', null, 'excerpt' );

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
						<div class="post_list post_list_paginated archive search-results">
							<?php if ( have_posts() ) : ?>
								<?php while ( have_posts() ) : the_post(); ?>
									<?php get_template_part( 'content', themeblvd_get_part( 'search_results' ) ); ?>
								<?php endwhile; ?>
							<?php else : ?>
								<?php get_template_part( 'content', themeblvd_get_part( 'search' ) ); ?>
							<?php endif; ?>
							<?php themeblvd_pagination(); ?>
						</div><!-- .post_list (end) -->
					</div><!-- .inner (end) -->
				</div><!-- #content (end) -->
			
				<!-- CONTENT (end) -->	
				
				<?php get_sidebar( 'right' ); ?>
			
			</div><!-- .grid-protection (end) -->
		</div><!-- .sidebar_layout-inner (end) -->
	</div><!-- #sidebar_layout (end) -->
	
<?php get_footer(); ?>