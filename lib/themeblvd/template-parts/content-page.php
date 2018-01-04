<?php
/**
 * The template used for displaying page
 * content in page.php
 *
 * @link http://dev.themeblvd.com/tutorial/template-parts-framework-2-5
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */
$class = array();

$class[] = 'top'; // Indicates top-level page.

if ( 'hide' !== get_post_meta( $post->ID, '_tb_title', true ) ) {

	$class[] = 'has-title';

} else {

	$class[] = 'no-title';

}

if ( get_the_content() ) {

	$class[] = 'has-content';

} else {

	$class[] = 'no-content';

}

$class = implode( ' ', $class );

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>

	<?php if ( ! themeblvd_get_att( 'epic_thumb' ) ) : ?>

		<?php if ( 'hide' !== get_post_meta( get_the_ID(), '_tb_title', true ) ) : ?>

			<header class="entry-header">

				<h1 class="entry-title"><?php the_title(); ?></h1>

			</header><!-- .entry-header -->

		<?php endif; ?>

	<?php endif; ?>

	<?php if ( has_post_thumbnail() && themeblvd_get_att( 'thumbs' ) ) : ?>

		<?php if ( ! themeblvd_get_att( 'epic_thumb' ) ) : ?>

			<div class="featured-item featured-image standard popout">

				<?php themeblvd_the_post_thumbnail(); ?>

			</div><!-- .featured-item -->

		<?php endif; ?>

	<?php endif; ?>

	<div class="entry-content clearfix">

		<?php the_content(); ?>

	</div><!-- .entry-content -->

	<?php
	wp_link_pages( array(
		'before' => '<div class="page-link">' . themeblvd_get_local( 'pages' ) . ': ',
		'after'  => '</div>',
	) );
	?>

	<?php
	edit_post_link(
		themeblvd_get_local( 'edit_page' ),
		'<div class="edit-link"><i class="' . themeblvd_get_icon_class( 'edit' ) . '"></i> ',
		'</div>'
	);
	?>

</article><!-- #post-<?php the_ID(); ?> -->
