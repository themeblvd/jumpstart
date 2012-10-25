<?php
/**
 * The default template for displaying a single attachment.
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title<?php if( themeblvd_get_att( 'show_meta' ) ) echo ' entry-title-with-meta'; ?>"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
		<?php if( themeblvd_get_att( 'show_meta' ) ) : ?>
			<?php themeblvd_blog_meta(); ?>
		<?php endif; ?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php if ( wp_attachment_is_image( $post->ID ) ) : $att_image = wp_get_attachment_image_src( $post->ID, 'full' ); ?>
			<p class="attachment">
				<a href="<?php echo wp_get_attachment_url($post->ID); ?>" title="<?php the_title(); ?>" rel="attachment">
					<img src="<?php echo $att_image[0];?>" width="<?php echo $att_image[1];?>" height="<?php echo $att_image[2];?>" alt="<?php $post->post_excerpt; ?>" />
				</a>
			</p>
		<?php endif; ?>
		<?php the_content(); ?>
		<div class="clear"></div>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . themeblvd_get_local('pages').': ', 'after' => '</div>' ) ); ?>
		<?php edit_post_link( themeblvd_get_local( 'edit_page' ), '<p class="edit-link clearfix">', '</p>' ); ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
