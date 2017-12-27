<?php
/**
 * The template used for displaying "epic" banner
 * above main site wrapper for archives.
 *
 * @link http://dev.themeblvd.com/tutorial/template-parts-framework-2-5
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.7.0
 */
// Set where a full-screen scroll-to-section button goes.
$to = 'main';

if ( themeblvd_show_breadcrumbs() ) {

	$to = 'breadcrumbs';

}

$image = themeblvd_get_option( 'archive_banner' );

?>
<div class="<?php echo esc_attr( themeblvd_get_att( 'epic_class' ) ); ?>">

	<header class="entry-header epic-thumb-header epic-thumb-content">

		<h1 class="entry-title"><?php themeblvd_the_archive_title(); ?></h1>

	</header>

	<?php if ( 'fs' === themeblvd_get_att( 'thumbs' ) ) : ?>

		<?php
		themeblvd_bg_parallax( array(
			'src' => $image['src'],
		));

		themeblvd_to_section( array(
			'to' => $to,
		));
		?>

	<?php else : ?>

		<?php echo wp_get_attachment_image( $image['id'], $image['crop'] ); ?>

	<?php endif; ?>

</div><!-- .epic-banner (end) -->
