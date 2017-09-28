<?php
/**
 * The template used for displaying page content in page.php
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */
$page_class  = 'top'; // Help to force consistency for plugins like bbPress using in non-page context
$page_class .= get_post_meta( $post->ID, '_tb_title', true ) != 'hide' ? ' has-title' : ' no-title';
$page_class .= get_the_content() ? ' has-content' : ' no-content';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class($page_class); ?>>

	<?php if ( ! themeblvd_get_att('epic_thumb') && get_post_meta( get_the_ID(), '_tb_title', true ) != 'hide' ) : ?>
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header><!-- .entry-header -->
	<?php endif; ?>

	<?php if ( has_post_thumbnail() && themeblvd_get_att('thumbs') && ! themeblvd_get_att('epic_thumb') ) : ?>
		<div class="featured-item featured-image standard popout">
			<?php themeblvd_the_post_thumbnail(); ?>
		</div><!-- .featured-item(end) -->
	<?php endif; ?>

	<div class="entry-content clearfix">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<?php wp_link_pages( array( 'before' => '<div class="page-link">' . themeblvd_get_local('pages').': ', 'after' => '</div>' ) ); ?>
	<?php edit_post_link( themeblvd_get_local( 'edit_page' ), '<div class="edit-link"><i class="fa fa-edit"></i> ', '</div>' ); ?>

</article><!-- #post-<?php the_ID(); ?> -->
