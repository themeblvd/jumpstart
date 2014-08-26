<?php
/**
 * The template used for displaying no search results in search.php
 */
global $wp_query;
$post_types = themeblvd_get_search_types();
?>
<article class="search-page">

	<section class="search-page-header clearfix">

		<?php if ( count($post_types) >= 2 ) : ?>
			<div class="search-refine">
				<?php themeblvd_refine_search_menu($post_types); ?>
			</div><!-- .search-refine (end) -->
		<?php endif; ?>

		<div class="search-again">
			<?php get_search_form(); ?>
		</div><!-- .search-again (end) -->

	</section><!-- .tb-search-header (end) -->

	<section class="tb-search-results">
		<?php themeblvd_the_loop(); // See: content-search-result.php ?>
	</section><!-- .search-results (end) -->

</article>