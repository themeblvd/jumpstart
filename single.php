<?php
/**
 * The template file for single posts.
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

// Determine if meta info should show
$show_meta = true;
if( themeblvd_get_option( 'single_meta', null, 'show' ) == 'hide' )
	$show_meta = false;
if( get_post_meta( $post->ID, '_tb_meta', true ) == 'hide' )
	$show_meta = false;
else if( get_post_meta( $post->ID, '_tb_meta', true ) == 'show' )
	$show_meta = true;

// Header
get_header(); 
	
	// Featured area
	if( themeblvd_config( 'featured' ) ) {
		themeblvd_featured_start();
		themeblvd_featured( 'single' );
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
						<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'content', themeblvd_get_part( 'single' ) ); ?>
							<?php themeblvd_single_footer(); ?>
							<?php if( themeblvd_supports( 'comments', 'posts' ) ) comments_template( '', true ); ?>
						<?php endwhile; ?>			
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
	
	// Featured area
	if( themeblvd_config( 'featured_below' ) ) {
		themeblvd_featured_below_start();
		themeblvd_featured_below( 'single' );
		themeblvd_featured_below_end();
	}
	
// Footer
get_footer();