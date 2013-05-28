<?php
/**
 * Template Name: Archives
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
						<?php get_template_part( 'content', 'template_archives' ); ?>
						<?php if( themeblvd_supports( 'comments', 'pages' ) ) comments_template( '', true ); ?>
						<?php themeblvd_content_bottom(); ?>
					</div><!-- .inner (end) -->
				</div><!-- #content (end) -->
					
				<!-- CONTENT (end) -->	
				
				<?php get_sidebar( 'right' ); ?>
			
			</div><!-- .grid-protection (end) -->
		</div><!-- .sidebar_layout-inner (end) -->
	</div><!-- #sidebar_layout (end) -->
	
<?php get_footer(); ?>