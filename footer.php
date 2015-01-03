<?php
/**
 * The template for displaying the footer.
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

		// End main area (if not a custom layout)
		if ( ! themeblvd_config( 'builder_post_id' ) ) {

			/**
			 * @hooked themeblvd_main_end_default - 10
			 */
			do_action('themeblvd_main_end');

			/**
			 * @hooked themeblvd_main_bottom_default - 10
			 */
			do_action('themeblvd_main_bottom');

		}

		// Featured area (below)
		if ( themeblvd_config( 'featured_below' ) ) {

			/**
			 * @hooked themeblvd_featured_below_start_default - 5
			 * @hooked themeblvd_featured_below_end_default - 20
			 */
			do_action('themeblvd_featured_below');

		}

		do_action('themeblvd_footer_before');
		?>

		<!-- FOOTER (start) -->

		<?php if ( themeblvd_config( 'bottom' ) ) : ?>

			<?php if ( themeblvd_config( 'bottom_builder_post_id' ) ) : ?>

				<div id="custom-bottom" class="clearfix" role="contentinfo">
					<?php do_action( 'themeblvd_builder_content', 'footer' ); ?>
				</div><!-- #custom-bottom (end) -->

			<?php else : ?>

				<div id="bottom">
					<footer id="colophon" <?php themeblvd_footer_class(); ?> role="contentinfo">
						<div class="wrap clearfix">
							<?php
							/**
							 * @hooked themeblvd_footer_content_default - 10
							 * @hooked themeblvd_footer_copyright_default - 20
							 * @hooked themeblvd_footer_sub_content_default - 30
							 * @hooked themeblvd_footer_below_default - 40
							 */
							do_action('themeblvd_footer');
							?>
						</div><!-- .wrap (end) -->
					</footer><!-- #colophon (end) -->
				</div><!-- #bottom (end) -->

			<?php endif; ?>

		<?php endif; ?>

		<!-- FOOTER (end) -->

		<?php do_action('themeblvd_footer_after'); ?>

	</div><!-- #container (end) -->
</div><!-- #wrapper (end) -->
<?php do_action('themeblvd_after'); ?>
<?php wp_footer(); ?>
</body>
</html>