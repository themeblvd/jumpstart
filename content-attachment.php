<?php
/**
 * The default template for displaying a single attachment.
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(themeblvd_get_att('class')); ?>>

	<header class="entry-header">
		<h1 class="entry-title">
			<?php themeblvd_the_title(); ?>
		</h1>
	</header><!-- .entry-header -->

	<?php if ( wp_attachment_is_image( get_the_ID() ) ) : ?>
		<div class="featured-item">
			<?php themeblvd_the_post_thumbnail( themeblvd_get_att('crop'), array('attachment_id' => get_the_id(), 'link' => 'thumbnail') ); ?>
		</div><!-- .featured-item(end) -->
	<?php endif; ?>

	<div class="entry-content clearfix">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<?php edit_post_link( themeblvd_get_local( 'edit_post' ), '<div class="edit-link"><i class="fa fa-edit"></i> ', '</div>' ); ?>

</article>
