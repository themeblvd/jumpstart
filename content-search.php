<?php
/**
 * The template used for displaying no search results in search.php
 */
?>
<div class="article-wrap">
	<article>
		<div class="entry-content no-search-results">
			<p><?php echo themeblvd_get_local( 'search_no_results' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</article>
</div><!-- .article-wrap (end) -->