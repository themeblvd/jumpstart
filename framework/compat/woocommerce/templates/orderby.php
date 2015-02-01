<?php
$compat = Theme_Blvd_Compat_WooCommerce::get_instance();
$default = themeblvd_get_option('woo_shop_view');
$current = $compat->loop_view();
$views = apply_filters( 'themeblvd_woo_views', array(
	'grid' 		=> '<i class="fa fa-th"></i>',
	'list' 		=> '<i class="fa fa-list"></i>',
	'catalog' 	=> '<i class="fa fa-list-alt"></i>'
));
?>
<div class="tb-woo-ordering">

	<div class="btn-group ordering">

		<?php if ( ! empty($catalog_orderby_options[$orderby]) ) : ?>
			<button type="button" class="btn btn-sm btn-default"><?php echo $catalog_orderby_options[$orderby]; ?></button>
			<button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<span class="caret"></span>
			</button>
			<?php unset($catalog_orderby_options[$orderby]); ?>
		<?php endif; ?>

		<ul class="dropdown-menu" role="menu">
			<?php foreach ( $catalog_orderby_options as $key => $name ) : ?>
				<li><a href="<?php echo add_query_arg( array('orderby' => $key) ); ?>"><?php echo $name; ?></a></li>
			<?php endforeach; ?>
		</ul>

	</div>

	<div class="btn-group view">

		<?php if ( themeblvd_get_option('woo_view_toggle') == 'yes' ) : ?>

			<?php foreach( $views as $key => $view ) : ?>
				<?php if ( $key == $default ) : ?>
					<a href="<?php echo add_query_arg( array('view' => $key) ); ?>" class="btn btn-sm btn-default<?php if ( $current == $key ) echo ' active'; ?>"><?php echo $view; ?></a>
					<?php unset($views[$key]); ?>
				<?php endif; ?>
			<?php endforeach; ?>

			<?php foreach( $views as $key => $view ) : ?>
				<a href="<?php echo add_query_arg( array('view' => $key) ); ?>" class="btn btn-sm btn-default<?php if ( $current == $key ) echo ' active'; ?>"><?php echo $view; ?></a>
			<?php endforeach; ?>

		<?php endif; ?>

	</div>

</div>
