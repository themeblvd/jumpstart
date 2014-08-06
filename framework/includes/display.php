<?php
/*------------------------------------------------------------*/
/* (1) <head>
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
/* (2) Before and after site
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_responsive_side_menu' ) ) :
/**
 * Default display for action: themeblvd_before
 *
 * @since 2.0.0
 */
function themeblvd_responsive_side_menu() {
	if ( themeblvd_supports('display', 'responsive') && themeblvd_supports('display', 'mobile_side_menu') ) {
		echo '<div id="tb-side-menu-wrapper"><div class="wrap"></div></div>';
	}
}
endif;

/*------------------------------------------------------------*/
/* (3) Header
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_header_top_default' ) ) :
/**
 * Default display for action: themeblvd_header_top
 *
 * @since 2.0.0
 */
function themeblvd_header_top_default() {
	?>
	<div class="header-top">
		<div class="wrap clearfix">

			<?php if ( themeblvd_get_option('header_text') ) : ?>
				<div class="header-top-text"><?php echo themeblvd_get_option('header_text'); ?></div>
			<?php endif; ?>

			<ul class="header-top-nav list-unstyled">
				<?php if ( themeblvd_get_option('searchform') == 'show' ) : ?>
					<li><?php themeblvd_search_popup(); ?></li>
				<?php endif; ?>
				<li><?php themeblvd_contact_bar(); ?></li>
			</ul>

		</div><!-- .wrap (end) -->
	</div><!-- .header-above (end) -->
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
	?>
	<div class="header-content" role="banner">
		<div class="wrap clearfix">
			<?php
			themeblvd_header_logo();
			themeblvd_header_addon();
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

	$option = themeblvd_get_option( 'logo' );
	$classes = 'header_logo header_logo_'.$option['type'];

	if ( $option['type'] == 'custom' || $option['type'] == 'title' || $option['type'] == 'title_tagline' ) {
		$classes .= ' header_logo_text';
	}

	if ( $option['type'] == 'custom' && ! empty( $option['custom_tagline'] ) ) {
		$classes .= ' header_logo_has_tagline';
	}

	if ( $option['type'] == 'title_tagline' ) {
		$classes .= ' header_logo_has_tagline';
	}
	?>
	<div class="<?php echo $classes; ?>">
		<?php
		if ( ! empty( $option['type'] ) ) {
			switch ( $option['type'] ) {

				case 'title' :
					echo '<h1 class="tb-text-logo"><a href="'.home_url().'" title="'.get_bloginfo('name').'">'.get_bloginfo('name').'</a></h1>';
					break;

				case 'title_tagline' :
					echo '<h1 class="tb-text-logo"><a href="'.home_url().'" title="'.get_bloginfo('name').'">'.get_bloginfo('name').'</a></h1>';
					echo '<span class="tagline">'.get_bloginfo('description').'</span>';
					break;

				case 'custom' :
					echo '<h1 class="tb-text-logo"><a href="'.home_url().'" title="'.$option['custom'].'">'.$option['custom'].'</a></h1>';
					if ( $option['custom_tagline'] ) {
						echo '<span class="tagline">'.$option['custom_tagline'].'</span>';
					}
					break;

				case 'image' :

					echo '<a href="'.home_url().'" title="'.get_bloginfo('name').'" class="tb-image-logo">';

					echo '<img src="'.$option['image'].'" alt="'.get_bloginfo('name').'" ';

					if ( ! empty( $option['image_width'] ) ) {
						echo 'width="'.$option['image_width'].'" ';
					}

					if ( ! empty( $option['image_2x'] ) ) {
						echo 'data-image-2x="'.$option['image_2x'].'" ';
					}

					echo '/></a>';

					break;
			}
		}
		?>
	</div><!-- .tbc_header_logo (end) -->
	<?php
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
		<a href="#" id="primary-menu-toggle" class="btn-navbar">
			<?php echo apply_filters( 'themeblvd_btn_navbar_text', '<i class="fa fa-bars"></i>' ); ?>
		</a>
		<div class="wrap clearfix">
			<?php wp_nav_menu( apply_filters( 'themeblvd_primary_menu_args', array( 'menu_id' => 'primary-menu', 'menu_class' => 'tb-primary-menu tb-to-side-menu sf-menu', 'container' => '', 'theme_location' => 'primary', 'fallback_cb' => 'themeblvd_primary_menu_fallback' ) ) ); ?>
			<?php themeblvd_header_menu_addon(); ?>
		</div><!-- .wrap (end) -->
	</nav><!-- #access (end) -->
	<?php
	do_action( 'themeblvd_header_menu_after' );
}
endif;

/*------------------------------------------------------------*/
/* (4) Featured Area (above)
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
/* (5) Featured Area (below)
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
/* (6) Main content area
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
/* (7) Sidebars
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
/* (8) Footer
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
					<?php echo apply_filters( 'themeblvd_footer_copyright', themeblvd_get_option( 'footer_copyright' ) ); ?>
				</div>
			</div><!-- .copyright (end) -->
			<div class="footer-nav">
				<div class="footer-nav-inner">
					<?php wp_nav_menu( apply_filters( 'themeblvd_footer_menu_args', array( 'menu_id' => 'footer-menu', 'container' => '', 'fallback_cb' => '', 'theme_location' => 'footer', 'depth' => 1 ) ) ); ?>
				</div>
			</div><!-- .copyright (end) -->
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

if ( !function_exists( 'themeblvd_content_top_default' ) ) :
/**
 * Default display for action: themeblvd_content_top
 *
 * @since 2.1.0
 */
function themeblvd_content_top_default() {
	if ( is_archive() ) {
		if ( themeblvd_get_option( 'archive_title', null, 'false' ) != 'false' ) {
			echo '<div class="element element-headline primary-entry-title">';
			echo '<h1 class="entry-title">';
			themeblvd_archive_title();
			echo '</h1>';
			echo '</div><!-- .element (end) -->';
		}
	}
	if ( is_page_template( 'template_list.php' ) || is_page_template( 'template_grid.php' ) ) {
		global $post;
		if ( 'hide' != get_post_meta( $post->ID, '_tb_title', true ) ) {
			echo '<div class="element element-headline primary-entry-title">';
			echo '<h1 class="entry-title">';
			the_title();
			echo '</h1>';
			echo '</div><!-- .element (end) -->';
		}
		the_content();
	}
}
endif;

// The following must happen within the loop!

if ( !function_exists( 'themeblvd_blog_meta_default' ) ) :
/**
 * Default display for action: themeblvd_meta
 *
 * @since 2.0.0
 */
function themeblvd_blog_meta_default() {
	echo themeblvd_get_meta();
}
endif;

if ( !function_exists( 'themeblvd_blog_tags_default' ) ) :
/**
 * Default display for action: themeblvd_tags
 *
 * @since 2.0.0
 */
function themeblvd_blog_tags_default() {
	the_tags( '<span class="tags"><i class="fa fa-tags"></i> ', ', ', '</span>' );
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
function themeblvd_the_post_thumbnail_default( $location = 'primary', $size = '', $link = true, $allow_filters = true ) {
	echo themeblvd_get_post_thumbnail( $location, $size, $link, $allow_filters );
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
/* (10) Layout Builder Elements
/*------------------------------------------------------------*/

// ...

/*------------------------------------------------------------*/
/* (11) Comment Form
/*------------------------------------------------------------*/

// ...

/*------------------------------------------------------------*/
/* (12) WordPress Multisite
/*------------------------------------------------------------*/

if ( !function_exists( 'themeblvd_before_signup_form' ) ) :
/**
 * Before sign-up form
 *
 * @since 2.1.0
 */
function themeblvd_before_signup_form() {
	echo '<div id="sidebar_layout" class="clearfix">';
	echo '<div class="sidebar_layout-inner">';
	echo '<div class="grid-protection">';
}
endif;

if ( !function_exists( 'themeblvd_after_signup_form' ) ) :
/**
 * After sign-up form
 *
 * @since 2.1.0
 */
function themeblvd_after_signup_form() {
	echo '</div><!-- .grid-protection (end) -->';
	echo '</div><!-- .sidebar_layout-inner (end) -->';
	echo '</div><!-- .sidebar-layout-wrapper (end) -->';
}
endif;
