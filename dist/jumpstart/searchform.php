<?php
/**
 * Template file for displaying searchform.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     Jump_Start
 */
?>
<div class="tb-search">
    <form method="get" action="<?php themeblvd_home_url(); ?>">
		<div class="search-wrap">
			<input type="search" class="search-input" name="s" placeholder="<?php echo themeblvd_get_local('search'); ?>" />
			<button class="search-submit btn-primary" type="submit">
				<i class="fa fa-search"></i>
			</button>
		</div>
	</form>
</div><!-- .tb-search (end) -->
