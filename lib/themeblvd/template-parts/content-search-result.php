<?php
/**
 * The template for displaying each post in
 * a search results list.
 *
 * @link http://dev.themeblvd.com/tutorial/template-parts-framework-2-5
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */

$icon = themeblvd_get_format_icon( get_post_format(), true );

?>
<div class="search-result">

	<span class="search-result-icon bg-primary">
		<i class="<?php echo esc_attr( themeblvd_get_icon_class( $icon ) ); ?>"></i>
	</span>

	<h3 class="entry-title">
		<?php themeblvd_the_title(); ?>
	</h3>

	<div class="meta-wrapper">

		<?php echo themeblvd_search_meta(); ?>

	</div><!-- .meta-wrapper -->

	<div class="entry-content">

		<?php the_excerpt(); ?>

	</div>

</div><!-- .search-result -->
