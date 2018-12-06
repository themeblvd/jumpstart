<?php
/**
 * The template used for displaying all search
 * results in search.php.
 *
 * See content-search-result.php for individual
 * posts in a search results list.
 *
 * @link https://dev.themeblvd.com/tutorial/template-parts-framework-2-5
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */

$post_types = themeblvd_get_search_types();

?>
<article class="search-page">

	<section class="search-page-header clearfix">

		<?php if ( count( $post_types ) >= 2 ) : ?>

			<div class="search-refine">

				<?php themeblvd_refine_search_menu( $post_types ); ?>

			</div><!-- .search-refine -->

		<?php endif; ?>

		<div class="search-again">

			<?php get_search_form(); ?>

		</div><!-- .search-again -->

	</section><!-- .tb-search-header -->

	<section class="tb-search-results">

		<?php themeblvd_the_loop(); ?>

	</section><!-- .search-results -->

</article>
