<?php
/**
 * The template used for displaying "epic" thumbnail
 * above main site wrapper for WooCommerce shop.
 */
$to = themeblvd_show_breadcrumbs() ? 'breadcrumbs' : 'main'; // where full screen scroll-to-section goes
?>
<div class="epic-thumb <?php echo themeblvd_get_att('thumbs'); ?>">

    <header class="entry-header epic-thumb-header epic-thumb-content">
        <?php if ( is_shop() ) : ?>
            <h1 class="entry-title"><?php echo get_the_title( get_option('woocommerce_shop_page_id') ); ?></h1>
        <?php elseif ( is_product_category() ) : ?>
            @TODO
        <?php endif; ?>
    </header>

    <figure class="epic-thumb-img">
        <?php if ( function_exists('is_shop') && is_shop() ) : ?>
            <?php echo get_the_post_thumbnail(get_option('woocommerce_shop_page_id'), 'full'); ?>
        <?php elseif ( is_product_category() ) : ?>
            @TODO
        <?php endif; ?>
    </figure>

    <?php if ( themeblvd_get_att('thumbs') == 'fs' ) : ?>
        <?php themeblvd_to_section(array('to' => $to)); ?>
    <?php endif; ?>

</div><!-- .epic-thumbnail (end) -->
