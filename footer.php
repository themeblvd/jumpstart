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
			themeblvd_main_bottom();
			themeblvd_main_end();
		}

		// Featured area (below)
		if ( themeblvd_config( 'featured_below' ) ) {
			themeblvd_featured_below_start();
			themeblvd_featured_below();
			themeblvd_featured_below_end();
		}

		themeblvd_footer_before();
		?>

		<!-- FOOTER (start) -->

		<?php if ( themeblvd_config( 'bottom' ) ) : ?>

			<?php if ( themeblvd_config( 'bottom_builder_post_id' ) ) : ?>

				<div id="custom-bottom" class="clearfix" role="contentinfo">
					<?php do_action( 'themeblvd_builder_content', 'footer' ); ?>
				</div><!-- #custom-bottom (end) -->

			<?php else : ?>

				<div id="bottom">
					<footer id="colophon" class="site-footer" role="contentinfo">
						<div class="wrap clearfix">
							<?php
							/**
							 * Display footer elements.
							 */
							themeblvd_footer_above();
							themeblvd_footer_content();
							themeblvd_footer_sub_content();
							themeblvd_footer_below();
							?>
						</div><!-- .wrap (end) -->
					</footer><!-- #colophon (end) -->
				</div><!-- #bottom (end) -->

			<?php endif; ?>

		<?php endif; ?>

		<!-- FOOTER (end) -->

		<?php themeblvd_footer_after(); ?>

	</div><!-- #container (end) -->
</div><!-- #wrapper (end) -->
<?php themeblvd_after(); ?>
<?php wp_footer(); ?>
</body>
</html>