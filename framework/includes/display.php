<?php
/*-----------------------------------------------------------------------------------*/
/* <head>
/*-----------------------------------------------------------------------------------*/

/**
 * Add viewport meta to wp_head if responsive
 * design is enabled in framework.
 *
 * @since 2.2.0
 */
if( ! function_exists( 'themeblvd_viewport_default' ) ) {
	function themeblvd_viewport_default() {
		if( themeblvd_supports( 'display', 'responsive' ) )
			echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">'."\n";
	}
}

/*-----------------------------------------------------------------------------------*/
/* Header
/*-----------------------------------------------------------------------------------*/

/**
 * Default display for action: themeblvd_header_above
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_header_above_default' ) ) {
	function themeblvd_header_above_default() {
		echo '<div class="header-above">';
		themeblvd_display_sidebar( 'ad_above_header' );
		echo '</div><!-- .header-above (end) -->';
	}
}

/**
 * Default display for action: themeblvd_header_content
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_header_content_default' ) ) {
	function themeblvd_header_content_default() {
		?>
		<div id="header_content">
			<div class="header_content-inner">
				<div class="header_content-content clearfix">
					<?php
					themeblvd_header_logo();
					themeblvd_header_addon();
					?>
				</div><!-- .header_content-content (end) -->
			</div><!-- .header_content-inner (end) -->
		</div><!-- #header_content (end) -->
		<?php
	}
}

/**
 * Default display for action: themeblvd_header_logo
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_header_logo_default' ) ) {
	function themeblvd_header_logo_default() {
		$option = themeblvd_get_option( 'logo' );
		$classes = 'header_logo header_logo_'.$option['type'];
		if( $option['type'] == 'custom' || $option['type'] == 'title' || $option['type'] == 'title_tagline' )
			$classes .= ' header_logo_text';
		if( $option['type'] == 'custom' && ! empty( $option['custom_tagline'] ) )
			$classes .= ' header_logo_has_tagline';
		if( $option['type'] == 'title_tagline' )
			$classes .= ' header_logo_has_tagline';
		?>
		<div class="<?php echo $classes; ?>">
			<?php
			if( ! empty( $option['type'] ) ) {
				switch( $option['type'] ) {
					case 'title' :
						echo '<h1 class="tb-text-logo"><a href="'.home_url().'" title="'.get_bloginfo('name').'">'.get_bloginfo('name').'</a></h1>';
						break;
					case 'title_tagline' :
						echo '<h1 class="tb-text-logo"><a href="'.home_url().'" title="'.get_bloginfo('name').'">'.get_bloginfo('name').'</a></h1>';
						echo '<span class="tagline">'.get_bloginfo('description').'</span>';
						break;
					case 'custom' :
						echo '<h1 class="tb-text-logo"><a href="'.home_url().'" title="'.$option['custom'].'">'.$option['custom'].'</a></h1>';
						if( $option['custom_tagline'] )
							echo '<span class="tagline">'.$option['custom_tagline'].'</span>';
						break;
					case 'image' :
						$image_1x = $option['image'];
						$image_2x = '';
						$image_width = '';
						if( ! empty( $option['image_2x'] ) )
							$image_2x = $option['image_2x'];
						if( ! empty( $option['image_width'] ) )
							$image_width = $option['image_width'];

						echo '<a href="'.home_url().'" title="'.get_bloginfo('name').'" class="tb-image-logo"><img src="'.$image_1x.'" width="'.$image_width.'" alt="'.get_bloginfo('name').'" data-image-2x="'.$image_2x.'" /></a>';
						break;
				}
			}
			?>
		</div><!-- .tbc_header_logo (end) -->
		<?php
	}
}

/**
 * Default display for action: themeblvd_header_main_menu
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_header_menu_default' ) ) {
	function themeblvd_header_menu_default() {
		do_action( 'themeblvd_header_menu_before' );
		?>
		<a href="#access" class="btn-navbar">
			<?php echo apply_filters( 'themeblvd_btn_navbar_text', '<i class="icon-reorder"></i>' ); ?>
		</a>
		<nav id="access" role="navigation">
			<div class="access-inner">
				<div class="access-content clearfix">
					<?php wp_nav_menu( apply_filters( 'themeblvd_primary_menu_args', array( 'menu_id' => 'primary-menu', 'menu_class' => 'sf-menu','container' => '', 'theme_location' => 'primary', 'fallback_cb' => 'themeblvd_primary_menu_fallback' ) ) ); ?>
					<?php themeblvd_header_menu_addon(); ?>
				</div><!-- .access-content (end) -->
			</div><!-- .access-inner (end) -->
		</nav><!-- #access (end) -->
		<?php
		do_action( 'themeblvd_header_menu_after' );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Footer
/*-----------------------------------------------------------------------------------*/

/**
 * Default display for action: themeblvd_footer_content
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_footer_content_default' ) ) {
	function themeblvd_footer_content_default() {
		// Grab the setup
		$footer_setup = themeblvd_get_option( 'footer_setup' );
		// Make sure there's actually a footer option in the theme setup
		if( is_array( $footer_setup ) ) {
			// Only move forward if user has selected for columns to show
			if( $footer_setup['num'] > 0 ) {
				// Build array of columns
				$i = 1;
				$columns = array();
				$num = $footer_setup['num'];
				while( $i <= $num ) {
					$columns[] = themeblvd_get_option( 'footer_col_'.$i );
					$i++;
				}
				?>
				<div id="footer_content" class="clearfix">
					<div class="footer_content-inner">
						<div class="footer_content-content">
							<div class="grid-protection">
								<?php themeblvd_columns( $num, $footer_setup['width'][$num], $columns ); ?>
							</div><!-- .grid-protection (end) -->
						</div><!-- .footer_content-content (end) -->
					</div><!-- .footer_content-inner (end) -->
				</div><!-- .footer_content (end) -->
				<?php
			}
		}
	}
}

/**
 * Default display for action: themeblvd_footer_sub_content
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_footer_sub_content_default' ) ) {
	function themeblvd_footer_sub_content_default() {
		?>
		<div id="footer_sub_content" class="clearfix">
			<div class="footer_sub_content-inner">
				<div class="footer_sub_content-content">
					<div class="copyright">
						<span class="copyright-inner">
							<?php echo apply_filters( 'themeblvd_footer_copyright', themeblvd_get_option( 'footer_copyright' ) ); ?>
						</span>
					</div><!-- .copyright (end) -->
					<div class="footer-nav">
						<span class="footer-inner">
							<?php wp_nav_menu( apply_filters( 'themeblvd_footer_menu_args', array( 'menu_id' => 'footer-menu', 'container' => '', 'fallback_cb' => '', 'theme_location' => 'footer', 'depth' => 1 ) ) ); ?>
						</span>
					</div><!-- .copyright (end) -->
				</div><!-- .footer_sub_content-content (end) -->
			</div><!-- .footer_sub_content-inner (end) -->
		</div><!-- .footer_sub_content (end) -->
		<?php
	}
}

/**
 * Default display for action: themeblvd_footer_below
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_footer_below_default' ) ) {
	function themeblvd_footer_below_default() {
		echo '<div class="footer-below">';
		themeblvd_display_sidebar( 'ad_below_footer' );
		echo '</div><!-- .footer-below (end) -->';
	}
}


/*-----------------------------------------------------------------------------------*/
/* Sidebars/Widget Areas
/*-----------------------------------------------------------------------------------*/

/**
 * Default display for action: themeblvd_fixed_sidebar_before
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_fixed_sidebar_before_default' ) ) {
	function themeblvd_fixed_sidebar_before_default( $side ) {
		echo '<div class="fixed-sidebar '.$side.'-sidebar '.themeblvd_get_column_class( $side ).'">';
		echo '<div class="fixed-sidebar-inner">';
	}
}

/**
 * Default display for action: themeblvd_fixed_sidebar_after
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_fixed_sidebar_after_default' ) ) {
	function themeblvd_fixed_sidebar_after_default() {
		echo '</div><!-- .fixed-sidebar-inner (end) -->';
		echo '</div><!-- .fixed-sidebar (end) -->';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Featured Area (above)
/*-----------------------------------------------------------------------------------*/

/**
 * Default display for action: themeblvd_featured_start
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_featured_start_default' ) ) {
	function themeblvd_featured_start_default() {
		$classes = '';
		$featured = themeblvd_config( 'featured' );
		if( $featured ) {
			foreach( $featured as $class )
				$classes .= " $class";

		}
		?>
		<!-- FEATURED (start) -->

		<div id="featured">
			<div class="featured-inner<?php echo $classes; ?>">
				<div class="featured-content">
		<?php
	}
}

/**
 * Default display for action: themeblvd_featured_end
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_featured_end_default' ) ) {
	function themeblvd_featured_end_default() {
		?>
					<div class="clear"></div>
				</div><!-- .featured-content (end) -->
			</div><!-- .featured-inner (end) -->
		</div><!-- #featured (end) -->

		<!-- FEATURED (end) -->
		<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* Featured Area (below)
/*-----------------------------------------------------------------------------------*/

/**
 * Default display for action: themeblvd_featured_below_start
 *
 * @since 2.1.0
 */
if( ! function_exists( 'themeblvd_featured_below_start_default' ) ) {
	function themeblvd_featured_below_start_default() {
		$classes = '';
		$featured_below = themeblvd_config( 'featured_below' );
		if( $featured_below ) {
			foreach( $featured_below as $class )
				$classes .= " $class";

		}
		?>
		<!-- FEATURED BELOW (start) -->

		<div id="featured_below">
			<div class="featured_below-inner<?php echo $classes; ?>">
				<div class="featured_below-content">
		<?php
	}
}

/**
 * Default display for action: themeblvd_featured_below
 *
 * @since 2.1.0
 */
if( ! function_exists( 'themeblvd_featured_below_end_default' ) ) {
	function themeblvd_featured_below_end_default() {
		?>
					<div class="clear"></div>
				</div><!-- .featured_below-content (end) -->
			</div><!-- .featured_below-inner (end) -->
		</div><!-- #featured_below (end) -->

		<!-- FEATURED BELOW (end) -->
		<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* Primary Content Area
/*-----------------------------------------------------------------------------------*/

/**
 * Default display for action: themeblvd_main_start
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_main_start_default' ) ) {
	function themeblvd_main_start_default() {
		?>
		<!-- MAIN (start) -->

		<div id="main" class="<?php themeblvd_sidebar_layout_class(); ?>">
			<div class="main-inner">
				<div class="main-content">
					<div class="grid-protection">
		<?php
	}
}

/**
 * Default display for action: themeblvd_main_end
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_main_end_default' ) ) {
	function themeblvd_main_end_default() {
		?>
						<div class="clear"></div>
					</div><!-- .grid-protection (end) -->
				</div><!-- .main-content (end) -->
			</div><!-- .main-inner (end) -->
		</div><!-- #main (end) -->

		<!-- MAIN (end) -->
		<?php
	}
}

/**
 * Default display for action: themeblvd_main_top
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_main_top_default' ) ) {
	function themeblvd_main_top_default() {
		echo '<div class="main-top">';
		themeblvd_display_sidebar( 'ad_above_content' );
		echo '</div><!-- .main-top (end) -->';
	}
}

/**
 * Default display for action: themeblvd_main_top
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_main_bottom_default' ) ) {
	function themeblvd_main_bottom_default() {
		echo '<div class="main-bottom">';
		themeblvd_display_sidebar( 'ad_below_content' );
		echo '</div><!-- .main-bottom (end) -->';
	}
}

/**
 * Default display for action: themeblvd_breadcrumbs
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_breadcrumbs_default' ) ) {
	function themeblvd_breadcrumbs_default() {
		if( themeblvd_show_breadcrumbs() ){
			?>
			<div id="breadcrumbs">
				<div class="breadcrumbs-inner">
					<div class="breadcrumbs-content">
						<div class="breadcrumb">
							<?php echo themeblvd_get_breadcrumbs_trail(); ?>
						</div><!-- .breadcrumb (end) -->
					</div><!-- .breadcrumbs-content (end) -->
				</div><!-- .breadcrumbs-inner (end) -->
			</div><!-- #breadcrumbs (end) -->
			<?php
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Content
/*-----------------------------------------------------------------------------------*/

/**
 * Default display for action: themeblvd_content_top
 *
 * @since 2.1.0
 */
if( ! function_exists( 'themeblvd_content_top_default' ) ) {
	function themeblvd_content_top_default() {
		if( is_archive() ) {
			if( themeblvd_get_option( 'archive_title', null, 'false' ) != 'false' ) {
				echo '<div class="element element-headline primary-entry-title">';
				echo '<h1 class="entry-title">';
				themeblvd_archive_title();
				echo '</h1>';
				echo '</div><!-- .element (end) -->';
			}
		}
		if( is_page_template( 'template_list.php' ) || is_page_template( 'template_grid.php' ) ) {
			global $post;
			if( 'hide' != get_post_meta( $post->ID, '_tb_title', true ) ) {
				echo '<div class="element element-headline primary-entry-title">';
				echo '<h1 class="entry-title">';
				the_title();
				echo '</h1>';
				echo '</div><!-- .element (end) -->';
			}
			the_content();
		}
	}
}

// The following must happen within the loop!

/**
 * Default display for action: themeblvd_meta
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_blog_meta_default' ) ) {
	function themeblvd_blog_meta_default() {

		// Setup text strings so their run through the
		// framework's frontend localization filter.
		$text = array(
			'comment' => themeblvd_get_local( 'comment' ),
			'comments' => themeblvd_get_local( 'comments' ),
			'no_comments' => themeblvd_get_local( 'no_comments' ),
		);

		?>
		<div class="entry-meta">
			<time class="entry-date updated" datetime="<?php the_time('c'); ?>"><i class="icon-calendar"></i> <?php the_time( get_option( 'date_format' ) ); ?></time>
			<span class="sep"> / </span>
			<span class="author vcard"><i class="icon-user"></i> <a class="url fn n" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php echo sprintf( esc_attr__( 'View all posts by %s', 'themeblvd' ), get_the_author() ); ?>" rel="author"><?php the_author(); ?></a></span>
			<span class="sep"> / </span>
			<span class="category"><i class="icon-reorder"></i>  <?php the_category(', '); ?></span>
			<?php if ( comments_open() ) : ?>
			<span class="sep"> / </span>
			<span class="comments-link">
				<i class="icon-comment"></i> <?php comments_popup_link( '<span class="leave-reply">'.$text['no_comments'].'</span>', '1 '.$text['comment'], '% '.$text['comments'] ); ?>
			</span>
			<?php endif; ?>
		</div><!-- .entry-meta -->
		<?php
	}
}

/**
 * Default display for action: themeblvd_tags
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_blog_tags_default' ) ) {
	function themeblvd_blog_tags_default() {
		the_tags( '<span class="tags"><i class="icon-tags"></i> ', ', ', '</span>' );
	}
}

/**
 * Default display for action: themeblvd_the_post_thumbnail
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_the_post_thumbnail_default' ) ) {
	function themeblvd_the_post_thumbnail_default( $location = 'primary', $size = '', $link = true, $allow_filters = true, $gallery = 'gallery' ) {
		echo themeblvd_get_post_thumbnail( $location, $size, $link, $allow_filters, $gallery );
	}
}

/**
 * Default display for action: themeblvd_content
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_blog_content_default' ) ) {
	function themeblvd_blog_content_default( $type ) {
		if( $type == 'content' ) {
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
			the_excerpt();
			$button = themeblvd_button( $args['text'], $args['url'], $args['color'], $args['target'], $args['size'], $args['classes'], $args['title'], $args['icon_before'], $args['icon_after'], $args['addon'] );
			if( $args['p'] )
				$button = '<p>'.$button.'</p>';
			echo $button;
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Layout Builder Elements
/*-----------------------------------------------------------------------------------*/

/**
 * Default display for action: themeblvd_element_close
 *
 * @since 2.1.0
 */
if( ! function_exists( 'themeblvd_element_open_default' ) ) {
	function themeblvd_element_open_default( $type, $location, $classes ) {
		echo '<div class="'.$classes.'">';
		echo '<div class="element-inner">';
		echo '<div class="element-inner-wrap">';
	}
}

/**
 * Default display for action: themeblvd_element_close
 *
 * @since 2.1.0
 */
if( ! function_exists( 'themeblvd_element_close_default' ) ) {
	function themeblvd_element_close_default( $type, $location, $classes ) {
		echo '</div><!-- .element-inner-wrap (end) -->';
		echo '</div><!-- .element-inner (end) -->';
		echo '</div><!-- .element (end) -->';
	}
}

/*-----------------------------------------------------------------------------------*/
/* WordPress Multisite
/*-----------------------------------------------------------------------------------*/

/**
 * Before sign-up form
 *
 * @since 2.1.0
 */
if( ! function_exists( 'themeblvd_before_signup_form' ) ) {
	function themeblvd_before_signup_form() {
		themeblvd_main_start();
		themeblvd_main_top();
		// themeblvd_breadcrumbs();
		themeblvd_before_layout();
		echo '<div id="sidebar_layout">';
		echo '<div class="sidebar_layout-inner">';
		echo '<div class="grid-protection">';
	}
}

/**
 * After sign-up form
 *
 * @since 2.1.0
 */
if( ! function_exists( 'themeblvd_after_signup_form' ) ) {
	function themeblvd_after_signup_form() {
		echo '</div><!-- .grid-protection (end) -->';
		echo '</div><!-- .sidebar_layout-inner (end) -->';
		echo '</div><!-- .sidebar-layout-wrapper (end) -->';
		themeblvd_main_bottom();
		themeblvd_main_end();
	}
}