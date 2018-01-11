<?php
/**
 * The footer for the theme.
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

			/*
			 * End the main content.
			 *
			 * Note: When displaying a custom layout,
			 * this does not get outputted; see
			 * `template_builder.php`.
			 */
			if ( ! themeblvd_config( 'builder_post_id' ) ) {

				/**
				 * Fires just inside the bottom of the
				 * main content wrapper.
				 *
				 * @hooked themeblvd_main_bottom_default - 10
				 *
				 * @since @@name-framework 2.0.0
				 */
				do_action( 'themeblvd_main_bottom' );

				/**
				 * Fires to end the wrapper for the
				 * main content.
				 *
				 * @hooked themeblvd_main_end_default - 10
				 *
				 * @since @@name-framework 2.0.0
				 */
				do_action( 'themeblvd_main_end' );

			}

			/**
			 * Fires before the header.
			 *
			 * @since @@name-framework 2.0.0
			 */
			do_action( 'themeblvd_footer_before' );
			?>

			<?php if ( themeblvd_config( 'bottom' ) ) : ?>

				<!-- FOOTER (start) -->

				<?php if ( themeblvd_config( 'bottom_builder_post_id' ) ) : ?>

					<div id="custom-bottom" class="clearfix" role="contentinfo">

						<?php
						/** This action is documented in template_builder.php */
						do_action( 'themeblvd_builder_content', 'footer' );
						?>

					</div><!-- #custom-bottom -->

				<?php else : ?>

					<?php themeblvd_get_template_part( 'footer' ); ?>

				<?php endif; ?>

				<!-- FOOTER (end) -->

			<?php endif; ?>

			<?php
			/**
			 * Fires after the footer.
			 *
			 * @since @@name-framework 2.0.0
			 */
			do_action( 'themeblvd_footer_after' );
			?>

		</div><!-- #container -->

	</div><!-- #wrapper -->

	<?php
	/**
	 * Fires after all HTML markup, but before
	 * wp_footer().
	 *
	 * @hooked themeblvd_widgets_below_footer - 5
	 * @hooked themeblvd_floating_search - 10
	 * @hooked themeblvd_to_top - 10
	 * @hooked themeblvd_side_panel - 10
	 * @hooked themeblvd_mobile_panel - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	do_action( 'themeblvd_after' );
	?>

	<?php wp_footer(); ?>

</body>

</html>
