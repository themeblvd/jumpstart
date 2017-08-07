<?php
/**
 * The template used for displaying content in template_archives.php
 *
 * @author		Jason Bobich
 * @copyright	2009-2017 Theme Blvd
 * @link		http://themeblvd.com
 * @package 	Jump_Start
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
        <?php the_content(); ?>
		<?php edit_post_link( themeblvd_get_local( 'edit_page' ), '<div class="edit-link">', '</div>' ); ?>
        <h2><?php echo themeblvd_get_local( 'last_30' ); ?></h2>
        <ul>
            <?php query_posts('showposts=30'); ?>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php $wp_query->is_home = false; ?>
                <li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> - <?php the_time(get_option('date_format')); ?> - <?php echo $post->comment_count ?> <?php echo themeblvd_get_local('comments'); ?></li>
            <?php endwhile; endif; wp_reset_query(); ?>
        </ul>
        <h2><?php echo themeblvd_get_local( 'categories' ); ?></h2>
        <ul>
            <?php wp_list_categories('title_li=&hierarchical=0&show_count=1') ?>
        </ul>
        <h2><?php echo themeblvd_get_local( 'monthly_archives' ); ?></h2>
        <ul>
            <?php wp_get_archives('type=monthly&show_post_count=1') ?>
        </ul>
	</div><!-- .entry-content (end) -->
</article>
