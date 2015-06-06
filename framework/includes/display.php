<?php
/*------------------------------------------------------------*/
/* <head>
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_viewport_default' ) ) :
/**
 * Add viewport meta to wp_head if responsive
 * design is enabled in framework.
 *
 * @since 2.2.0
 */
function themeblvd_viewport_default() {
	if ( themeblvd_supports( 'display', 'responsive' ) ) {
		echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">'."\n";
	}
}
endif;

/*------------------------------------------------------------*/
/* Header
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_header_top_default' ) ) :
/**
 * Default display for action: themeblvd_header_top
 *
 * @since 2.0.0
 */
function themeblvd_header_top_default() {

	if ( ! themeblvd_has_header_info() ) {
		return;
	}

	$icons = themeblvd_get_option('social_media');
	?>
	<div class="header-top">
		<div class="wrap clearfix">

			<?php themeblvd_header_text(); ?>

			<?php if ( themeblvd_get_option('searchform') == 'show' || themeblvd_do_cart() || $icons || ( themeblvd_installed('wpml') && themeblvd_supports('plugins', 'wpml') && get_option('tb_wpml_show_lang_switcher', '1') ) ) : ?>

				<ul class="header-top-nav list-unstyled">

					<?php if ( themeblvd_get_option('searchform') == 'show' ) : ?>
						<li class="top-search"><?php themeblvd_floating_search_trigger(); ?></li>
					<?php endif; ?>

					<?php if ( themeblvd_do_cart() ) : ?>
						<li class="top-cart"><?php themeblvd_cart_popup_trigger(); ?></li>
					<?php endif; ?>

					<?php if ( $icons ) : ?>
						<li class="top-icons"><?php themeblvd_contact_bar( $icons, array('class' => 'to-mobile') ); ?></li>
					<?php endif; ?>

					<?php if ( themeblvd_installed('wpml') && themeblvd_supports('plugins', 'wpml') && get_option('tb_wpml_show_lang_switcher', '1') ) : ?>
						<li class="top-wpml"><?php do_action('icl_language_selector'); ?></li>
					<?php endif; ?>

				</ul>

			<?php endif; ?>

		</div><!-- .wrap (end) -->
	</div><!-- .header-above (end) -->
	<?php
}
endif;

if ( !function_exists( 'themeblvd_responsive_menu_toggle' ) ) :
/**
 * Default display for action: themeblvd_header_top
 *
 * @since 2.0.0
 */
function themeblvd_responsive_menu_toggle() {
	?>
	<div id="primary-menu-toggle">

		<a href="#" id="primary-menu-open" class="btn-navbar open">
			<?php echo apply_filters( 'themeblvd_btn_navbar_text', '<i class="fa fa-bars"></i>' ); ?>
		</a>

		<a href="#" id="primary-menu-close" class="btn-navbar close">
			<?php echo apply_filters( 'themeblvd_btn_navbar_text_close', '<i class="fa fa-times"></i>' ); ?>
		</a>

		<?php if ( themeblvd_do_cart() ) : ?>
			<?php themeblvd_mobile_cart_link(); ?>
		<?php endif; ?>

	</div>
	<?php
}
endif;

if ( !function_exists( 'themeblvd_header_above_default' ) ) :
/**
 * Default display for action: themeblvd_header_above
 *
 * @since 2.0.0
 */
function themeblvd_header_above_default() {
	?>
	<div class="header-above">
		<div class="wrap clearfix">
			<?php themeblvd_display_sidebar( 'ad_above_header' ); ?>
		</div><!-- .wrap (end) -->
	</div><!-- .header-above (end) -->
	<?php
}
endif;

if ( !function_exists( 'themeblvd_header_content_default' ) ) :
/**
 * Default display for action: themeblvd_header_content
 *
 * @since 2.0.0
 */
function themeblvd_header_content_default() {

	$class = 'header-content';

	if ( themeblvd_do_floating_search() ) {
		$class .= ' has-floating-search';
	}

	if ( themeblvd_do_cart() ) {
		$class .= ' has-cart-button';
	}
	?>
	<div class="<?php echo $class; ?>" role="banner">

		<?php
		/**
		 * Setup floating search bar
		 */
		if ( themeblvd_do_floating_search() ) {
			themeblvd_floating_search();
		}
		?>

		<div class="wrap clearfix">
			<?php
			/**
			 * @hooked themeblvd_header_logo_default - 10
			 */
			do_action('themeblvd_header_logo');

			/**
			 * @hooked themeblvd_responsive_menu_toggle - 10
			 */
			do_action('themeblvd_header_addon');
			?>
		</div><!-- .wrap (end) -->
	</div><!-- .header-content (end) -->
	<?php
}
endif;

if ( !function_exists( 'themeblvd_header_logo_default' ) ) :
/**
 * Default display for action: themeblvd_header_logo
 *
 * @since 2.0.0
 */
function themeblvd_header_logo_default() {

	$logo = themeblvd_get_option('logo');
	$trans = themeblvd_get_option('trans_logo');

	if ( $logo ) {
		if ( themeblvd_config('suck_up') && ! empty($trans['type']) && $trans['type'] != 'default' ) {

			$logo['class'] = 'logo-standard';
			echo themeblvd_get_logo($logo);

			if ( $trans ) {
				$trans['class'] = 'logo-trans';
				echo themeblvd_get_logo($trans);
			}

		} else {

			echo themeblvd_get_logo($logo);

		}
	}

}
endif;

if ( !function_exists( 'themeblvd_header_menu_default' ) ) :
/**
 * Default display for action: themeblvd_header_main_menu
 *
 * @since 2.0.0
 */
function themeblvd_header_menu_default() {
	do_action( 'themeblvd_header_menu_before' );
	?>
	<nav id="access" class="header-nav" role="navigation">
		<div class="wrap clearfix">
			<?php
			wp_nav_menu( themeblvd_get_wp_nav_menu_args('primary') );
			do_action('themeblvd_header_menu_addon');
			?>
		</div><!-- .wrap (end) -->
	</nav><!-- #access (end) -->
	<?php
	do_action( 'themeblvd_header_menu_after' );
}
endif;

/*------------------------------------------------------------*/
/* Featured Area (above)
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_featured_start_default' ) ) :
/**
 * Default display for action: themeblvd_featured_start
 *
 * @since 2.0.0
 */
function themeblvd_featured_start_default() {
	$classes = '';
	$featured = themeblvd_config( 'featured' );
	if ( $featured ) {
		foreach ( $featured as $class ) {
			$classes .= " $class";
		}
	}
	?>
	<!-- FEATURED (start) -->

	<div id="featured">
		<div class="featured-inner<?php echo $classes; ?>">
			<div class="featured-content clearfix">
	<?php
}
endif;

if ( !function_exists( 'themeblvd_featured_end_default' ) ) :
/**
 * Default display for action: themeblvd_featured_end
 *
 * @since 2.0.0
 */
function themeblvd_featured_end_default() {
	?>
			</div><!-- .featured-content (end) -->
		</div><!-- .featured-inner (end) -->
	</div><!-- #featured (end) -->

	<!-- FEATURED (end) -->
	<?php
}
endif;

/*------------------------------------------------------------*/
/* Featured Area (below)
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_featured_below_start_default' ) ) :
/**
 * Default display for action: themeblvd_featured_below_start
 *
 * @since 2.1.0
 */
function themeblvd_featured_below_start_default() {
	$classes = '';
	$featured_below = themeblvd_config( 'featured_below' );
	if ( $featured_below ) {
		foreach ( $featured_below as $class ) {
			$classes .= " $class";
		}
	}
	?>
	<!-- FEATURED BELOW (start) -->

	<div id="featured_below">
		<div class="featured_below-inner<?php echo $classes; ?>">
			<div class="featured_below-content clearfix">
	<?php
}
endif;

if ( !function_exists( 'themeblvd_featured_below_end_default' ) ) :
/**
 * Default display for action: themeblvd_featured_below
 *
 * @since 2.1.0
 */
function themeblvd_featured_below_end_default() {
	?>
			</div><!-- .featured_below-content (end) -->
		</div><!-- .featured_below-inner (end) -->
	</div><!-- #featured_below (end) -->

	<!-- FEATURED BELOW (end) -->
	<?php
}
endif;

/*------------------------------------------------------------*/
/* Main content area
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_main_start_default' ) ) :
/**
 * Default display for action: themeblvd_main_start
 *
 * @since 2.0.0
 */
function themeblvd_main_start_default() {
	?>
	<!-- MAIN (start) -->

	<div id="main" class="site-inner <?php themeblvd_sidebar_layout_class(); ?>">
		<div class="wrap clearfix">
	<?php
}
endif;

if ( !function_exists( 'themeblvd_main_end_default' ) ) :
/**
 * Default display for action: themeblvd_main_end
 *
 * @since 2.0.0
 */
function themeblvd_main_end_default() {
	?>
		</div><!-- .wrap (end) -->
	</div><!-- #main (end) -->

	<!-- MAIN (end) -->
	<?php
}
endif;

if ( !function_exists( 'themeblvd_main_top_default' ) ) :
/**
 * Default display for action: themeblvd_main_top
 *
 * @since 2.0.0
 */
function themeblvd_main_top_default() {
	?>
	<div class="main-top">
		<div class="wrap clearfix">
			<?php themeblvd_display_sidebar( 'ad_above_content' ); ?>
		</div><!-- .wrap (end) -->
	</div><!-- .main-top (end) -->
	<?php
}
endif;

if ( !function_exists( 'themeblvd_main_bottom_default' ) ) :
/**
 * Default display for action: themeblvd_main_top
 *
 * @since 2.0.0
 */
function themeblvd_main_bottom_default() {
	?>
	<div class="main-bottom">
		<div class="wrap clearfix">
			<?php themeblvd_display_sidebar( 'ad_below_content' ); ?>
		</div><!-- .wrap (end) -->
	</div><!-- .main-bottom (end) -->
	<?php
}
endif;

if ( !function_exists( 'themeblvd_breadcrumbs_default' ) ) :
/**
 * Default display for action: themeblvd_breadcrumbs
 *
 * @since 2.0.0
 */
function themeblvd_breadcrumbs_default() {
	if ( themeblvd_show_breadcrumbs() ) {
		themeblvd_the_breadcrumbs();
	}
}
endif;

/*------------------------------------------------------------*/
/* Sidebars
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_fixed_sidebars' ) ) :
/**
 * Display fixed sidebar(s). Default display for action: themeblvd_sidebars
 *
 * @since 2.0.0
 *
 * @param string $position position of sidebar on page, left or right
 */
function themeblvd_fixed_sidebars( $position ) {

	$layout = themeblvd_config( 'sidebar_layout' );

	// Sidebar Left, Sidebar Right, Double Sidebars
	if ( $layout == 'sidebar_'.$position || $layout == 'double_sidebar' ) {

		do_action( 'themeblvd_fixed_sidebar_before', $position );
		themeblvd_display_sidebar( 'sidebar_'.$position );
		do_action( 'themeblvd_fixed_sidebar_after', $position );

	}

	// Double Left Sidebars
	if ( $layout == 'double_sidebar_left' && $position == 'left' ) {

		// Left Sidebar
		do_action( 'themeblvd_fixed_sidebar_before', 'left'  );
		themeblvd_display_sidebar( 'sidebar_left' );
		do_action( 'themeblvd_fixed_sidebar_after', 'left' );

		// Right Sidebar
		do_action( 'themeblvd_fixed_sidebar_before', 'right'  );
		themeblvd_display_sidebar( 'sidebar_right' );
		do_action( 'themeblvd_fixed_sidebar_after', 'right' );

	}

	// Double Right Sidebars
	if ( $layout == 'double_sidebar_right' && $position == 'right' ) {

		// Left Sidebar
		do_action( 'themeblvd_fixed_sidebar_before', 'left'  );
		themeblvd_display_sidebar( 'sidebar_left' );
		do_action( 'themeblvd_fixed_sidebar_after', 'left' );

		// Right Sidebar
		do_action( 'themeblvd_fixed_sidebar_before', 'right'  );
		themeblvd_display_sidebar( 'sidebar_right' );
		do_action( 'themeblvd_fixed_sidebar_after', 'right' );

	}
}
endif;

if ( !function_exists( 'themeblvd_fixed_sidebar_before_default' ) ) :
/**
 * Default display for action: themeblvd_fixed_sidebar_before
 *
 * @since 2.0.0
 */
function themeblvd_fixed_sidebar_before_default( $side ) {
	echo '<div class="fixed-sidebar '.$side.'-sidebar '.themeblvd_get_column_class( $side ).'">';
	echo '<div class="fixed-sidebar-inner">';
}
endif;

if ( !function_exists( 'themeblvd_fixed_sidebar_after_default' ) ) :
/**
 * Default display for action: themeblvd_fixed_sidebar_after
 *
 * @since 2.0.0
 */
function themeblvd_fixed_sidebar_after_default() {
	echo '</div><!-- .fixed-sidebar-inner (end) -->';
	echo '</div><!-- .fixed-sidebar (end) -->';
}
endif;

/*------------------------------------------------------------*/
/* Footer
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_footer_content_default' ) ) :
/**
 * Default display for action: themeblvd_footer_content
 *
 * @since 2.0.0
 */
function themeblvd_footer_content_default() {

	// Grab the setup
	$footer_setup = themeblvd_get_option( 'footer_setup' );

	if ( $footer_setup ) {

		$args = array();
		$args['num'] = count( explode( '-', $footer_setup ) );
		$args['widths'] = $footer_setup;

		// Build array of columns
		$i = 1;
		$columns = array();
		while ( $i <= $args['num'] ) {
			$columns[$i] = themeblvd_get_option( 'footer_col_'.$i );
			$i++;
		}
		?>
		<div class="footer-content">
			<div class="wrap clearfix">
				<?php themeblvd_columns( $args, $columns ); ?>
			</div><!-- .wrap (end) -->
		</div><!-- .footer-content (end) -->
		<?php
	}
}
endif;

if ( !function_exists( 'themeblvd_footer_sub_content_default' ) ) :
/**
 * Default display for action: themeblvd_footer_sub_content
 *
 * @since 2.0.0
 */
function themeblvd_footer_sub_content_default() {
	?>
	<div class="footer-sub-content">
		<div class="wrap clearfix">
			<div class="copyright">
				<div class="copyright-inner">
					<?php echo apply_filters( 'themeblvd_footer_copyright', themeblvd_get_content( themeblvd_get_option('footer_copyright') ) ); ?>
				</div>
			</div><!-- .copyright (end) -->
			<?php if ( has_nav_menu('footer') ) : ?>
				<div class="footer-nav">
					<div class="footer-nav-inner">
						<?php wp_nav_menu( themeblvd_get_wp_nav_menu_args('footer') ); ?>
					</div>
				</div><!-- .footer-nav (end) -->
			<?php endif; ?>
		</div><!-- .wrap (end) -->
	</div><!-- .footer-sub-content (end) -->
	<?php
}
endif;

/**
 * Default display for action: themeblvd_footer_below
 *
 * @since 2.0.0
 */
if ( !function_exists( 'themeblvd_footer_below_default' ) ) {
	function themeblvd_footer_below_default() {
		?>
		<div class="footer-below">
			<div class="wrap clearfix">
				<?php themeblvd_display_sidebar( 'ad_below_footer' ); ?>
			</div><!-- .wrap (end) -->
		</div><!-- .footer-below (end) -->
		<?php
	}
}

/*------------------------------------------------------------*/
/* (9) Content
/*------------------------------------------------------------*/

/**
 * Display info boxes at the top of archives.
 * Hooked to themeblvd_content_top by default.
 *
 * @since 2.5.0
 */
function themeblvd_archive_info() {

	if ( is_archive() ) {

		if ( is_category() || is_tag() ) {

			$setting = '';

			if ( is_category() ) {

				$setting = themeblvd_get_tax_meta( 'category', get_query_var('category_name'), 'info', 'default' );

				if ( ! $setting || $setting == 'default' ) {
					$setting = themeblvd_get_option('category_info', null, 'hide');
				}

				if ( $setting == 'show' ) {
					$tax = 'category';
					$term = get_query_var('category_name');
				}

			} else if ( is_tag() ) {

				$setting = themeblvd_get_tax_meta( 'post_tag', get_query_var('tag'), 'info', 'default' );

				if ( ! $setting || $setting == 'default' ) {
					$setting = themeblvd_get_option('tag_info', null, 'hide');
				}

				if ( $setting == 'show' ) {
					$tax = 'post_tag';
					$term = get_query_var('tag');
				}

			}

			if ( $setting == 'show' ) {
				themeblvd_tax_info();
			}

		} else if ( is_author() ) {

			if ( get_query_var('author_name') ) {
				$user = get_user_by('slug', get_query_var('author_name'));
			} else if ( get_query_var('author') ) {
				$user = get_user_by('id', get_query_var('author'));
			}

			if ( ! empty( $user ) ) {

				$setting = get_user_meta( $user->ID, '_tb_box_archive', true );

				if ( $setting === '1' ) {
					themeblvd_author_info($user, 'archive');
				}
			}

		}

		/**
		 * Hook anything here that you want to display at the
		 * top of archives.
		 *
		 * If you wanted to use themeblvd_tax_info() for a taxonomy
		 * other than category or post_tag, this would be a good
		 * place to do it.
		 */
		do_action('themeblvd_archive_info');

	} // end if is_archive()
}

// The following must happen within the loop!

if ( !function_exists( 'themeblvd_single_footer_default' ) ) :
/**
 * Default display for action: themeblvd_single_footer
 *
 * @since 2.5.0
 */
function themeblvd_single_footer_default() {

	// Author Box
	$user = get_user_by( 'id', get_the_author_meta('ID') );
	$setting = get_post_meta( get_the_ID(), '_tb_author_box', true );

	if ( ! $setting || $setting == 'default' ) {
		$setting = get_user_meta( $user->ID, '_tb_box_single', true );
	}

	if ( $setting === '1' ) {
		themeblvd_author_info($user, 'single');
	}

	// Related Posts
	$setting = get_post_meta( get_the_ID(), '_tb_related_posts', true );

	if ( ! $setting || $setting == 'default' ) {
		$setting = themeblvd_get_option('single_related_posts', null, 'tag');
	}

	if ( $setting != 'hide' ) {
		themeblvd_related_posts(array('related_by' => $setting));
	}

}
endif;

if ( !function_exists( 'themeblvd_blog_meta_default' ) ) :
/**
 * Default display for action: themeblvd_meta
 *
 * @since 2.0.0
 */
function themeblvd_blog_meta_default() {

	$args = apply_filters('themeblvd_blog_meta_args', array(
		'include' => array('format', 'time', 'author', 'comments')
	));

	echo themeblvd_get_meta($args);
}
endif;

if ( !function_exists( 'themeblvd_grid_meta_default' ) ) :
/**
 * Default display for action: themeblvd_grid_meta
 *
 * @since 2.0.0
 */
function themeblvd_grid_meta_default() {

	$args = apply_filters('themeblvd_grid_meta_args', array(
		'include'	=> array('time', 'author', 'comments'),
		'comments'	=> 'mini',
		//'time'	=> 'ago'
	));

	echo themeblvd_get_meta($args);
}
endif;

if ( !function_exists( 'themeblvd_search_meta_default' ) ) :
/**
 * Default display for action: themeblvd_search_meta
 *
 * @since 2.0.0
 */
function themeblvd_search_meta_default() {

	$args = array(
		'include'	=> array('time'),
		'time'		=> 'ago'
	);

	if ( get_post_type() == 'post' ) {
		$args['include'][] = 'author';
		$args['include'][] = 'comments';
	}

	$args = apply_filters( 'themeblvd_search_meta_args', $args );

	echo themeblvd_get_meta($args);
}
endif;

if ( !function_exists( 'themeblvd_blog_sub_meta_default' ) ) :
/**
 * Default display for action: themeblvd_meta
 *
 * @since 2.0.0
 */
function themeblvd_blog_sub_meta_default() {
	echo '<div class="sub-meta">';
	do_action('themeblvd_sub_meta_items');
	echo '</div><!-- .sub-meta (end) -->';
}
endif;

if ( !function_exists( 'themeblvd_the_post_thumbnail_default' ) ) :
/**
 * Default display for action: themeblvd_the_post_thumbnail
 *
 * @since 2.0.0
 *
 * @param string $location Where the thumbnail is being used -- primary, featured, single -- sort of a wild card to build on in the future as conflicts arise.
 * @param string $size For the image crop size of the thumbnail
 * @param bool $link Set to false to force a thumbnail to ignore post's Image Link options
 * @param bool $allow_filters Whether to allow general filters on the thumbnail or not
 */
function themeblvd_the_post_thumbnail_default( $size = '', $args = array() ) {
	echo themeblvd_get_post_thumbnail( $size, $args );
}
endif;

if ( !function_exists( 'themeblvd_blog_content_default' ) ) :
/**
 * Default display for action: themeblvd_content
 *
 * @since 2.0.0
 *
 * @param string $type Type of content -- content or excerpt
 */
function themeblvd_blog_content_default( $type ) {

	if ( $type == 'content' ) {

		// Show full content
		the_content( apply_filters( 'themeblvd_the_content_more_text', themeblvd_get_local('read_more') ) );

	} else {

		// Show excerpt and read more button
		$args = apply_filters( 'themeblvd_the_excerpt_more_args', array(
			'text'			=> themeblvd_get_local('read_more'),
			'url'			=> get_permalink(),
			'color'			=> 'default',
			'target' 		=> '_self',
			'size'			=> null,
			'classes'		=> null,
			'title'			=> null,
			'icon_before' 	=> null,
			'icon_after' 	=> null,
			'addon'			=> null,
			'p'				=> true
		));

		// Output excerpt
		the_excerpt();

		// Output button
		$button = themeblvd_button( $args['text'], $args['url'], $args['color'], $args['target'], $args['size'], $args['classes'], $args['title'], $args['icon_before'], $args['icon_after'], $args['addon'] );

		if ( $args['p'] ) {
			$button = '<p>'.$button.'</p>';
		}

		echo $button;

	}
}
endif;

/*------------------------------------------------------------*/
/* WordPress Multisite
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_before_signup_form' ) ) :
/**
 * Before sign-up form
 *
 * @since 2.1.0
 */
function themeblvd_before_signup_form() {
	?>
	<div id="sidebar_layout" class="clearfix">
		<div class="sidebar_layout-inner">
			<div class="row grid-protection">

				<!-- CONTENT (start) -->

				<div id="content" class="col-md-12 clearfix" role="main">
					<div class="inner">
						<?php themeblvd_content_top();
}
endif;

if ( !function_exists( 'themeblvd_after_signup_form' ) ) :
/**
 * After sign-up form
 *
 * @since 2.1.0
 */
function themeblvd_after_signup_form() {

						themeblvd_content_bottom(); ?>
					</div><!-- .inner (end) -->
				</div><!-- #content (end) -->

				<!-- CONTENT (end) -->

				<!-- SIDEBARS (start) -->

				<?php get_sidebar( 'left' ); ?>

				<?php get_sidebar( 'right' ); ?>

				<!-- SIDEBARS (end) -->

			</div><!-- .grid-protection (end) -->
		</div><!-- .sidebar_layout-inner (end) -->
	</div><!-- .#sidebar_layout (end) -->
	<?php
}
endif;
