<?php
/**
 * Template for displaying a search form.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */
?>
<div class="tb-search">

	<form method="get" action="<?php themeblvd_home_url(); ?>">

		<div class="search-wrap">

			<input type="search" class="search-input" name="s" placeholder="<?php echo themeblvd_get_local( 'search' ); ?>" />

			<button class="search-submit btn-primary" type="submit">
				<i class="fa fa-search"></i>
			</button>

		</div>

	</form>

</div><!-- .tb-search -->
