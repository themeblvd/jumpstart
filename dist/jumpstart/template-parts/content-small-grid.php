<?php
/**
 * The default template for displaying content in small post grid.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     Jump_Start
 */
$meta = apply_filters('themeblvd_small_grid_meta_args', array('include' => array('time'), 'icons' => array()));
?>
<div class="grid-item <?php echo themeblvd_get_att('class'); ?>">
    <article <?php post_class(); ?>>

        <?php themeblvd_the_post_thumbnail( themeblvd_get_att('crop'), array('placeholder' => true) ); ?>

        <?php if ( themeblvd_get_att('show_title') || themeblvd_get_att('show_meta') ) : ?>
            <div class="content-wrapper">

                <?php if ( themeblvd_get_att('show_title') ) : ?>
                    <h3 class="entry-title"><?php themeblvd_the_title(); ?></h3>
                <?php endif; ?>

                <?php if ( themeblvd_get_att('show_meta') ) : ?>
        			<div class="meta-wrapper">
        				<?php echo themeblvd_kses( themeblvd_get_meta($meta) ); ?>
        			</div><!-- .meta-wrapper (end) -->
        		<?php endif; ?>

            </div><!-- .content-wrapper (end) -->
        <?php endif; ?>

    </article><!-- #post-<?php the_ID(); ?> -->
</div><!-- .grid-item (end) -->
