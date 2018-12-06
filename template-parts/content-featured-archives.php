<?php
/**
 * The template used for displaying "epic" banner
 * above main site wrapper for archives.
 *
 * @link https://dev.themeblvd.com/tutorial/template-parts-framework-2-5
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.7.0
 */
?>
<div class="<?php echo esc_attr( themeblvd_get_att( 'epic_class' ) ); ?>">

	<header class="entry-header epic-thumb-header epic-thumb-content">

		<h1 class="entry-title"><?php themeblvd_the_archive_title(); ?></h1>

	</header>

	<?php themeblvd_the_archive_banner_image(); ?>

</div><!-- .epic-banner (end) -->
