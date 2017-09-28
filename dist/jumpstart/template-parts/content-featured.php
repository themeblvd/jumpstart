<?php
/**
 * The template used for displaying "epic" thumbnail
 * above main site wrapper.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */
$to = themeblvd_show_breadcrumbs() ? 'breadcrumbs' : 'main'; // where full screen scroll-to-section goes
$to = is_page_template('template_builder.php') ? 'custom-main' : $to;
?>
<div class="<?php echo themeblvd_get_att('epic_class'); ?>">

    <?php if ( has_post_format('quote') ) : ?>

        <div class="featured-quote epic-thumb-quote epic-thumb-content">
            <?php themeblvd_content_quote(); ?>
        </div>

    <?php else : ?>

        <?php if ( ( ! is_page() || get_post_meta( get_the_ID(), '_tb_title', true ) != 'hide' ) || themeblvd_get_att('show_meta') || has_post_format('audio') ) : ?>

            <header class="entry-header epic-thumb-header epic-thumb-content">

                <?php if ( ! is_page() || get_post_meta( get_the_ID(), '_tb_title', true ) != 'hide' ) : ?>
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                <?php endif; ?>

                <?php if ( themeblvd_get_att('show_meta') ) : ?>
                    <div class="meta-wrapper above">
                        <?php themeblvd_blog_meta(); ?>
                    </div><!-- .meta-wrapper (end) -->
                <?php endif; ?>

                <?php if ( has_post_format('audio') ) : ?>
                    <?php themeblvd_content_audio(); ?>
                <?php endif; ?>

            </header>

        <?php endif; ?>

    <?php endif; ?>

    <?php if ( has_post_format('gallery') ) : ?>

		<?php themeblvd_gallery_slider(); ?>

    <?php else : ?>

        <?php if ( themeblvd_get_att('thumbs') == 'fs' ) : ?>
            <?php themeblvd_bg_parallax( array( 'src' => wp_get_attachment_image_url( get_post_thumbnail_id(), 'tb_x_large' ) ) ); ?>
        <?php else : ?>
            <?php the_post_thumbnail('full'); ?>
        <?php endif; ?>

    <?php endif; ?>

    <?php if ( themeblvd_get_att('thumbs') == 'fs' ) : ?>
        <?php themeblvd_to_section(array('to' => $to)); ?>
    <?php endif; ?>

</div><!-- .epic-thumbnail (end) -->
