<?php
/**
 * The template used for displaying the
 * header content within header.php.
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
<div id="top">

	<header id="branding" <?php themeblvd_header_class(); ?>>

		<div class="wrap clearfix">

			<?php
			/**
			 * Fires at the top of the header.
			 *
			 * By default, this is where the optional
			 * header top bar gets displayed.
			 *
			 * @hooked themeblvd_header_top_default - 10
			 *
			 * @since @@name-framework 2.0.0
			 */
			do_action( 'themeblvd_header_top' );

			/**
			 * Fires where the content of the header
			 * goes.
			 *
			 * @hooked themeblvd_header_content_default - 10
			 *
			 * @since @@name-framework 2.0.0
			 */
			do_action( 'themeblvd_header_content' );

			/**
			 * Fires where the main menu goes.
			 *
			 * @hooked themeblvd_header_menu_default - 10
			 *
			 * @since @@name-framework 2.0.0
			 */
			do_action( 'themeblvd_header_menu' );
			?>

		</div><!-- .wrap -->

	</header><!-- #branding -->

</div><!-- #top -->
