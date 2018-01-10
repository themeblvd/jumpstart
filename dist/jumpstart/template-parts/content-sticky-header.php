<?php
/**
 * The default template for displaying content
 * in the sticky header.
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
<div id="sticky-header" <?php themeblvd_sticky_class(); ?>>

	<div class="wrap">

		<?php
		/**
		 * Fires within the sticky header.
		 *
		 * @hooked themeblvd_sticky_header_logo - 10
		 * @hooked themeblvd_sticky_header_menu - 20
		 *
		 * @since Theme_Blvd 2.7.0
		 */
		do_action( 'themeblvd_sticky_header' );
		?>

	</div><!-- .wrap (end) -->

</div><!-- #sticky-header (end) -->
