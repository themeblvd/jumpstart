<?php
/**
 * The header for the theme.
 *
 * WARNING: This template file is a core part of the
 * Theme Blvd WordPress Framework. It is advised
 * that any edits to the way this file displays its
 * content be done with via actions, filters, and
 * template parts.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */
?><!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>" />

	<link rel="profile" href="http://gmpg.org/xfn/11" />

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

	<?php
	/**
	 * Fires just inside the <body> tag, before
	 * any HTML markup.
	 *
	 * @since @@name-framework 2.0.0
	 */
	do_action( 'themeblvd_before' );
	?>

	<div id="wrapper">

		<div id="container">

			<?php
			/**
			 * Fires just inside the main container, before
			 * the header has started.
			 *
			 * @hooked themeblvd_header_before_default - 10
			 *
			 * @since @@name-framework 2.0.0
			 */
			do_action( 'themeblvd_header_before' );
			?>

			<!-- HEADER (start) -->

			<?php if ( themeblvd_config( 'top' ) ) : ?>

				<div id="top">

					<header id="branding" <?php themeblvd_header_class(); ?>>

						<div class="wrap clearfix">

							<?php
							/**
							 * Fires at the top of the header.
							 *
							 * By default, this is where the optional
							 * header top bar gets displayed.
							 *
							 * @hooked themeblvd_header_top_default - 10
							 *
							 * @since @@name-framework 2.0.0
							 */
							do_action( 'themeblvd_header_top' );

							/**
							 * Fires where the content of the header
							 * goes.
							 *
							 * @hooked themeblvd_header_content_default - 10
							 *
							 * @since @@name-framework 2.0.0
							 */
							do_action( 'themeblvd_header_content' );

							/**
							 * Fires where the main menu goes.
							 *
							 * @hooked themeblvd_header_menu_default - 10
							 *
							 * @since @@name-framework 2.0.0
							 */
							do_action( 'themeblvd_header_menu' );
							?>

						</div><!-- .wrap -->

					</header><!-- #branding -->

				</div><!-- #top -->

			<?php endif; ?>

			<!-- HEADER (end) -->

			<?php
			/**
			 * Fires after the header.
			 *
			 * By default, this is where full-width and
			 * full-screen featured images display.
			 *
			 * @hooked themeblvd_epic_thumb - 10
			 *
			 * @since @@name-framework 2.0.0
			 */
			do_action( 'themeblvd_header_after' );

			/**
			 * Fires where the breadcrumbs go.
			 *
			 * @hooked themeblvd_breadcrumbs_default - 10
			 *
			 * @since @@name-framework 2.0.0
			 */
			do_action( 'themeblvd_breadcrumbs' );

			/*
			 * Start the main content.
			 *
			 * Note: When displaying a custom layout, this does
			 * not get outputted; see `template_builder.php`.
			 */
			if ( ! themeblvd_config( 'builder_post_id' ) ) {

				/**
				 * Fires to start the wrapper for the
				 * main content.
				 *
				 * @hooked themeblvd_main_start_default - 10
				 *
				 * @since @@name-framework 2.0.0
				 */
				do_action( 'themeblvd_main_start' );

				/**
				 * Fires just inside the main content
				 * wrapper.
				 *
				 * @hooked themeblvd_main_top_default - 10
				 *
				 * @since @@name-framework 2.0.0
				 */
				do_action( 'themeblvd_main_top' );

			}

			/**
			 * Fires just before the sidebar layout
			 * is started.
			 *
			 * @since @@name-framework 2.0.0
			 */
			do_action( 'themeblvd_before_layout' );
