<?php
/**
 * The Header for our theme.
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
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/framework/assets/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php themeblvd_before(); ?>
<div id="wrapper">
	<div id="container">

		<?php themeblvd_header_before(); ?>

		<!-- HEADER (start) -->

		<?php if ( themeblvd_config( 'top' ) ) : ?>

			<div id="top">
				<header id="branding" class="site-header" role="banner">
					<div class="wrap clearfix">
						<?php
						/**
						 * Display header elements.
						 */
						themeblvd_header_top();
						themeblvd_header_above();
						themeblvd_header_content();
						themeblvd_header_menu();
						?>
					</div><!-- .wrap (end) -->
				</header><!-- #branding (end) -->
			</div><!-- #top (end) -->

		<?php endif; ?>

		<!-- HEADER (end) -->

		<?php
		// After header
		themeblvd_header_after();

		// Featured area (above)
		if ( themeblvd_config( 'featured' ) ) {
			themeblvd_featured_start();
			themeblvd_featured();
			themeblvd_featured_end();
		}

		// Start main area (if not a custom layout)
		if ( ! themeblvd_config( 'builder_post_id' ) ) {

			themeblvd_main_start();
			themeblvd_main_top();

			// Breadcrumbs
			themeblvd_breadcrumbs();
		}

		// Before sidebar+content layout
		themeblvd_before_layout();