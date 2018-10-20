<?php
/**
 * The default template for displaying content
 * in the side panel.
 *
 * Note: For this side panel to show, there needs
 * to be a menu applied to the Primary Side or
 * Secondary Side menu location from Appearance > Menus.
 * Or, `themeblvd_do_side_panel` can be manually
 * filtered to TRUE.
 *
 * @link http://dev.themeblvd.com/tutorial/template-parts-framework-2-5
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.7.0
 */
?>
<div id="side-panel" <?php themeblvd_side_panel_class(); ?>>

	<div class="wrap">

		<?php
		/**
		 * Fires within the hidden side panel.
		 *
		 * @hooked themeblvd_side_panel_menu - 10
		 * @hooked themeblvd_side_panel_sub_menu - 20
		 * @hooked themeblvd_side_panel_contact - 30
		 *
		 * @since Theme_Blvd 2.6.0
		 */
		do_action( 'themeblvd_side_panel' );
		?>

	</div><!-- .wrap (end) -->

</div><!-- #side-panel (end) -->
