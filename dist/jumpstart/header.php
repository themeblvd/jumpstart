<?php
/**
 * The Header for our theme.
 *
 * WARNING: This template file is a core part of the
 * Theme Blvd WordPress Framework. It is advised
 * that any edits to the way this file displays its
 * content be done with via actions, filters, and
 * template parts.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     Jump_Start
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
/**
 * @hooked null
 */
do_action('themeblvd_before');
?>

<div id="wrapper">
	<div id="container">

		<?php
		/**
		 * @hooked themeblvd_header_before_default - 10
		 */
		do_action('themeblvd_header_before');
		?>

		<!-- HEADER (start) -->

		<?php if ( themeblvd_config('top') ) : ?>

			<div id="top">
				<header id="branding" <?php themeblvd_header_class(); ?>>
					<div class="wrap clearfix">
						<?php
						/**
						 * @hooked themeblvd_header_top_default - 10
						 */
						do_action('themeblvd_header_top');

						/**
						 * @hooked null -- @deprecated
						 */
						do_action('themeblvd_header_above');

						/**
						 * @hooked themeblvd_header_content_default - 10
						 */
						do_action('themeblvd_header_content');

						/**
						 * @hooked themeblvd_header_menu_default - 10
						 */
						do_action('themeblvd_header_menu');
						?>
					</div><!-- .wrap (end) -->
				</header><!-- #branding (end) -->
			</div><!-- #top (end) -->

		<?php endif; ?>

		<!-- HEADER (end) -->

		<?php
		/**
		 * @hooked themeblvd_epic_thumb - 10
		 */
		do_action('themeblvd_header_after');

		// Featured area (above)
		if ( themeblvd_config( 'featured' ) ) {

			/**
			 * @hooked themeblvd_featured_start_default - 5
			 * @hooked themeblvd_featured_end_default - 20
			 */
			do_action('themeblvd_featured');

		}

		/**
		 * @hooked themeblvd_breadcrumbs_default - 10
		 */
		do_action('themeblvd_breadcrumbs');

		// Start main area (if not a custom layout)
		if ( ! themeblvd_config( 'builder_post_id' ) ) {

			/**
			 * @hooked themeblvd_main_start_default - 10
			 */
			do_action('themeblvd_main_start');

			/**
			 * @hooked themeblvd_main_top_default - 10
			 */
			do_action('themeblvd_main_top');

		}

		/**
		 * @hooked null
		 */
		do_action('themeblvd_before_layout');
