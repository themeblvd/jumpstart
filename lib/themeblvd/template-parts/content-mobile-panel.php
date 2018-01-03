<?php
/**
 * The default template for displaying content
 * in the mobile menu panel.
 *
 * @link http://dev.themeblvd.com/tutorial/template-parts-framework-2-5
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.7.0
 */
?>
<div id="mobile-panel" <?php themeblvd_mobile_panel_class(); ?>>

	<div class="wrap">

		<?php
		/**
		 * Fires within the hidden mobile panel.
		 *
		 * @hooked themeblvd_mobile_panel_search - 10
		 * @hooked themeblvd_mobile_panel_menu - 20
		 * @hooked themeblvd_mobile_panel_sub_menu - 30
		 * @hooked themeblvd_mobile_panel_contact - 40
		 *
		 * @since @@name-framework 2.7.0
		 */
		do_action( 'themeblvd_mobile_panel' );
		?>

	</div><!-- .wrap (end) -->

</div><!-- #mobile-panel (end) -->
