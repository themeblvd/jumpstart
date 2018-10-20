<?php
/**
 * The default template for displaying content
 * in the mobile header.
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
<div id="mobile-header" <?php themeblvd_mobile_header_class(); ?>>

	<div class="wrap">

		<?php
		/**
		 * Fires within the mobile header.
		 *
		 * @hooked themeblvd_mobile_header_logo - 10
		 * @hooked themeblvd_mobile_header_menu - 20
		 *
		 * @since Theme_Blvd 2.7.0
		 */
		do_action( 'themeblvd_mobile_header' );
		?>

	</div><!-- .wrap (end) -->

</div><!-- #mobile-header (end) -->
