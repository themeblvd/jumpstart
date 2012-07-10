<?php
/**
 * Template Name: Post Grid
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

// Setup
$columns = get_post_meta( $post->ID, 'columns', true );
if( ! $columns ) $columns = apply_filters( 'themeblvd_default_grid_columns', 3 );
$posts_per_page = themeblvd_posts_page_page( 'template' );
$size = themeblvd_grid_class( $columns );

// Setup query string
$query_string = '';
$custom_query_string = get_post_meta( $post->ID, 'query', true );
if( $custom_query_string ) {
	// Custom query string
	$query_string = htmlspecialchars_decode($custom_query_string).'&';
	$query_string .= 'posts_per_page='.$posts_per_page.'&'; // User can't use posts_per_page in custom query
	if ( get_query_var('paged') )
        $paged = get_query_var('paged');
    else if ( get_query_var('page') )
        $paged = get_query_var('page');
	else
        $paged = 1;
	$query_string .= 'paged='.$paged;

} else {
	// Generated query string
	$query_string = themeblvd_query_string( $posts_per_page );
}

// Header
get_header(); 

	// Featured area
	if( themeblvd_config( 'featured' ) ) {
		themeblvd_featured_start();
		themeblvd_featured( 'page' );
		themeblvd_featured( 'grid' );
		themeblvd_featured_end();
	}
	
	// Start main area
	themeblvd_main_start();
	themeblvd_main_top();
	
	// Breadcrumbs
	themeblvd_breadcrumbs();
	
	// Before sidebar+content layout
	themeblvd_before_layout();
	?>
	
	<div id="sidebar_layout">
		<div class="sidebar_layout-inner">
			<div class="grid-protection">

				<?php themeblvd_sidebars( 'left' ); ?>
				
				<!-- CONTENT (start) -->
	
				<div id="content" role="main">
					<div class="inner">
						<?php themeblvd_content_top(); ?>
						<div class="primary-post-grid post_grid_paginated post_grid<?php echo themeblvd_get_classes( 'element_post_grid_paginated', true ); ?>">
							<div class="grid-protection">
								<?php
								query_posts( $query_string );
								global $more; $more = 0;
								$counter = 1;
								if ( have_posts() ) {
									while ( have_posts() ) {
										the_post();
										if( $counter == 1 ) themeblvd_open_row();
										get_template_part( 'content', themeblvd_get_part( 'grid_paginated' ) );
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
					</div><!-- .inner (end) -->
				</div><!-- #content (end) -->
					
				<!-- CONTENT (end) -->		
				
				<?php themeblvd_sidebars( 'right' ); ?>
			
			</div><!-- .grid-protection (end) -->
		</div><!-- .sidebar_layout-inner (end) -->
	</div><!-- .sidebar-layout-wrapper (end) -->
	
	<?php
	// End main area
	themeblvd_main_bottom();
	themeblvd_main_end();
	
	// Featured area (below)
	if( themeblvd_config( 'featured_below' ) ) {
		themeblvd_featured_below_start();
		themeblvd_featured_below( 'page' );
		themeblvd_featured_below( 'grid' );
		themeblvd_featured_below_end();
	}
	
// Footer
get_footer();