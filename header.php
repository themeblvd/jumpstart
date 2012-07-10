<?php
/**
 * The Header for our theme.
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
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php themeblvd_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php themeblvd_before(); ?>
<div id="wrapper">
	<div id="container">
		
		<?php themeblvd_header_before(); ?>
		
		<!-- HEADER (start) -->
		
		<div id="top">
			<header id="branding" role="banner">
				<div class="content">
					<?php
					/**
					 * Display header elements.
					 */
					themeblvd_header_top();
					themeblvd_header_above();
					themeblvd_header_content();
					themeblvd_header_menu();
					?>
				</div><!-- .content (end) -->
			</header><!-- #branding (end) -->
		</div><!-- #top (end) -->
		
		<!-- HEADER (end) -->
		
		<?php themeblvd_header_after(); ?>