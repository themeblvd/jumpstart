<?php
/**
 * The template used to add sorting to a display of
 * products in WooCommerce. We also use this template
 * to add a toggle between the theme's added product views.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     Jump_Start
 */

$compat = Theme_Blvd_Compat_WooCommerce::get_instance();

$default = themeblvd_get_option('woo_shop_view');

$current = $compat->loop_view();

$views = apply_filters( 'themeblvd_woo_views', array(
	'grid' 		=> themeblvd_get_icon( themeblvd_get_icon_class( 'th' ) ),
	'list' 		=> themeblvd_get_icon( themeblvd_get_icon_class( 'th-list' ) ),
	'catalog' 	=> themeblvd_get_icon( themeblvd_get_icon_class( 'list' ) ),
) );
?>
<div class="tb-woo-ordering">

	<div class="btn-group ordering clearfix">

		<?php if ( ! empty($catalog_orderby_options[$orderby]) ) : ?>
			<button type="button" class="btn btn-sm btn-default"><?php echo $catalog_orderby_options[$orderby]; ?></button>
			<button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<span class="caret"></span>
			</button>
			<?php unset($catalog_orderby_options[$orderby]); ?>
		<?php endif; ?>

		<ul class="dropdown-menu" role="menu">
			<?php foreach ( $catalog_orderby_options as $key => $name ) : ?>
				<li><a href="<?php echo esc_url( add_query_arg( array('orderby' => $key) ) ); ?>"><?php echo $name; ?></a></li>
			<?php endforeach; ?>
		</ul>

	</div>

	<div class="btn-group view clearfix">

		<?php if ( themeblvd_get_option('woo_view_toggle') == 'yes' ) : ?>

			<?php foreach( $views as $key => $view ) : ?>
				<?php if ( $key == $default ) : ?>
					<a href="<?php echo esc_url( add_query_arg( array('view' => $key) ) ); ?>" class="btn btn-sm btn-default<?php if ( $current == $key ) echo ' active'; ?>"><?php echo $view; ?></a>
					<?php unset($views[$key]); ?>
				<?php endif; ?>
			<?php endforeach; ?>

			<?php foreach( $views as $key => $view ) : ?>
				<a href="<?php echo esc_url( add_query_arg( array('view' => $key) ) ); ?>" class="btn btn-sm btn-default<?php if ( $current == $key ) echo ' active'; ?>"><?php echo $view; ?></a>
			<?php endforeach; ?>

		<?php endif; ?>

	</div>

</div>
