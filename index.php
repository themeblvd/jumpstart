<?php
/**
 * The main template file.
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
// global $_themeblvd_config;
// echo '<pre>'; print_r($_themeblvd_config); echo "</pre>"; // Debug
$homepage_content = themeblvd_get_option( 'homepage_content', null, 'posts' );
$tb_content = themeblvd_get_option( 'blog_content', null, 'content' );

// In displaying the homepage, we need to first figure out if a custom layout
// should show or we're going to list out posts. If the user were to apply a 
// custom layout to a static page, and then set that as the frontpage under 
// Settings > Reading > Frontpage displays, any paginated elements in the 
// layout will not work right. So, to combat this, it's setup that the user 
// can select a custom layout from their Theme Options page and leave their 
// frontpage displays option to "your latest posts."

if( $homepage_content == 'custom_layout' ) {
	
	/*------------------------------------------------------*/
	/* Custom Layout Homepage
	/*------------------------------------------------------*/
	
	get_template_part( 'template_builder' );

} else {

	// Template part
	$template_part = themeblvd_get_part( 'index' );
	
	// Setup
	if( $template_part == 'grid' || $template_part == 'index_grid' ) {
		
		/*------------------------------------------------------*/
		/* Magazine Homepage setup (post grid)
		/*------------------------------------------------------*/
	
		// Columns
		$columns = themeblvd_get_option( 'index_grid_columns' );
		if( ! $columns ) $columns = apply_filters( 'themeblvd_default_grid_columns', 3 );
		// Rows
		$rows = themeblvd_get_option( 'index_grid_rows' );
		if( ! $rows ) $rows = apply_filters( 'themeblvd_default_grid_columns', 4 );
		// Thumbnail size
		$tb_size = themeblvd_grid_class( $columns );
		$tb_crop = apply_filters( 'themeblvd_index_grid_crop_size', $tb_size );
		// Re-Build query string
		$query_string = '';
		// Categories
		$exclude = themeblvd_get_option( 'index_grid_categories' );
		if( $exclude ) {
			$categories = 'cat=';
			foreach( $exclude as $key => $value )
				if( $value )
					$categories .= '-'.$key.',';
			$categories = themeblvd_remove_trailing_char( $categories, ',' );
		}
		if( isset( $categories ) )
			$query_string .= $categories;
		// Posts per page
		$query_string .= 'posts_per_page='.get_option( 'posts_per_page' ).'&';
		// Pagination
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$query_string .= $paged;
	
	} else {
		
		/*------------------------------------------------------*/
		/* Standard Blog Homepage setup (post list)
		/*------------------------------------------------------*/
		
		$query_string = themeblvd_query_string();
		$tb_content = themeblvd_get_option( 'blog_content', null, 'content' );
	
	}
	
	// Header
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
							<?php if( $template_part == 'grid' || $template_part == 'index_grid' ) : ?>
								
								<!-- HOMEPAGE POST GRID (start) -->
								
								<div class="primary-post-grid post_grid_paginated post_grid">
									<div class="grid-protection">
										<?php
										query_posts( $query_string );
										$counter = 1;
										if ( have_posts() ) {
											while ( have_posts() ) {
												the_post();
												if( $counter == 1 ) themeblvd_open_row();
												get_template_part( 'content', $template_part );
												if( $counter % $columns == 0 ) themeblvd_close_row();
												if( $counter % $columns == 0 && $posts_per_page != $counter ) themeblvd_open_row();
												$counter++;
											}
											if( ($counter-1) != $posts_per_page ) themeblvd_close_row();
										} else {
											echo '<p>'.themeblvd_get_local( 'archive_no_posts' ).'</p>';
										}
										?>
									</div><!-- .grid-protection (end) -->
									<?php themeblvd_pagination(); ?>
								</div><!-- .post_grid (end) -->
								
								<!-- HOMEPAGE POST GRID (end) -->
								
							<?php else : ?>
								
								<!-- HOMEPAGE POST LIST (start) -->
								
								<div class="primary-post-list post_list_paginated post_list">
									<?php query_posts( $query_string ); ?>
									<?php if ( have_posts() ) : ?>
										<?php while ( have_posts() ) : the_post(); ?>
											<?php get_template_part( 'content', themeblvd_get_part( 'index' ) ); ?>
										<?php endwhile; ?>
									<?php else : ?>
										<p><?php echo themeblvd_get_local( 'archive_no_posts' ); ?></p>
									<?php endif; ?>
									<?php themeblvd_pagination(); ?>
								</div><!-- .blogroll (end) -->
								
								<!-- HOMEPAGE POST LIST (end) -->
								
							<?php endif; ?>
						</div><!-- .inner (end) -->
					</div><!-- #content (end) -->
						
					<!-- CONTENT (end) -->
					
					<?php get_sidebar( 'right' ); ?>
				
				</div><!-- .grid-protection (end) -->
			</div><!-- .sidebar_layout-inner (end) -->
		</div><!-- #sidebar_layout (end) -->
		<?php
		
	// Footer
	get_footer();
	
}