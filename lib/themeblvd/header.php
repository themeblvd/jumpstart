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

	<?php themeblvd_get_template_part( 'head' ); ?>

</head>

<body <?php body_class(); ?>>

	<?php
	/**
	 * Fires just inside the <body> tag, before
	 * any HTML markup.
	 *
	 * @hooked themeblvd_mobile_header - 10
	 * @hooked themeblvd_widgets_above_header - 20
	 *
	 * @since @@name-framework 2.0.0
	 */
	do_action( 'themeblvd_before' );
	?>

	<div id="wrapper">

		<div id="container">

			<?php
			/**
			 * Fires where the website header goes.
			 *
			 * @hooked themeblvd_header_default - 10
			 *
			 * @since @@name-framework 2.7.0
			 */
			do_action( 'themeblvd_header' );

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
