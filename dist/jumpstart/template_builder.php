<?php
/**
 * Template Name: Custom Layout
 *
 * WARNING: This template file is a core part of the
 * Theme Blvd WordPress Framework. It is advised
 * that any edits to the way this file displays its
 * content be done with via actions, filters, and
 * template parts.
 *
 * @author		Jason Bobich
 * @copyright	2009-2017 Theme Blvd
 * @link		http://themeblvd.com
 * @package 	Jump_Start
 */

get_header();
?>

	<div id="custom-main" class="clearfix" role="main">
		<?php themeblvd_content_top(); ?>
		<?php if ( has_action( 'themeblvd_builder_content' ) ) : ?>
			<?php do_action( 'themeblvd_builder_content', 'main' ); ?>
		<?php else : ?>
			<div class="alert alert-warning"><p><?php printf( themeblvd_get_local('no_builder_plugin'), '<a href="http://wordpress.org/extend/plugins/theme-blvd-layout-builder" target="_blank">Theme Blvd Layout Builder</a>' ); ?></p></div>
		<?php endif; ?>
		<?php themeblvd_content_bottom(); ?>
	</div><!-- #elements (end) -->

<?php get_footer(); ?>
