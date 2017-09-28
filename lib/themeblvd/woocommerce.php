<?php
/**
 * The template file for WooCommerce pages.
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
 * @since      @@name-framework 2.5.0
 */

get_header();
?>

	<div id="sidebar_layout" class="clearfix">
		<div class="sidebar_layout-inner">
			<div class="row grid-protection">

				<!-- CONTENT (start) -->

				<div id="content" class="<?php echo esc_attr( themeblvd_get_column_class('content') ); ?> clearfix" role="main">
					<div class="inner">
						<?php
						themeblvd_content_top();
						woocommerce_content();
						themeblvd_content_bottom();
						?>
					</div><!-- .inner (end) -->
				</div><!-- #content (end) -->

				<!-- CONTENT (end) -->

				<!-- SIDEBARS (start) -->

				<?php get_sidebar('left'); ?>

				<?php get_sidebar('right'); ?>

				<!-- SIDEBARS (end) -->

			</div><!-- .grid-protection (end) -->
		</div><!-- .sidebar_layout-inner (end) -->
	</div><!-- .#sidebar_layout (end) -->

<?php get_footer(); ?>
