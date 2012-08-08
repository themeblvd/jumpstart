<?php
/*-----------------------------------------------------------------------------------*/
/* <head>
/*-----------------------------------------------------------------------------------*/

/**
 * Display <head>
 * Default display for action: themeblvd_head
 *
 * @since 2.1.0
 */

if( ! function_exists( 'themeblvd_head_default' ) ) {
	function themeblvd_head_default() {
		
		// Charset meta
		echo '<meta charset="'.get_bloginfo( 'charset' ).'" />'."\n";
		
		// Viewport meta
		if( themeblvd_get_option( 'responsive_css', null, 'true' ) != 'false' )
			echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">'."\n";
		
		// <title> tag
		echo '<title>'.wp_title( '|', false, 'right' )."</title>\n";
		
		// Optional meta data for Theme Blvd SEO plugin
		do_action( 'themeblvd_meta' );
		
		// XFN
		echo '<link rel="profile" href="http://gmpg.org/xfn/11" />'."\n";
		
		// Theme style.css
		echo '<link rel="stylesheet" type="text/css" media="all" href="'.get_bloginfo( 'stylesheet_url' ).'" />'."\n";
		
		// Favicon
		$icon_16x16 = themeblvd_get_option('favicon');
		if( $icon_16x16 )
			echo '<link rel="shortcut icon" href="'.$icon_16x16.'" />'."\n";
		
		// Apple touch icons
		$icon_144x144 = themeblvd_get_option('apple_touch_144x144');
		if( $icon_144x144 )
			echo '<link rel="apple-touch-icon-precomposed" sizes="144x144" href="'.$icon_144x144.'" />'."\n";
		
		$icon_114x114 = themeblvd_get_option('apple_touch_114x114');
		if( $icon_114x114 )
			echo '<link rel="apple-touch-icon-precomposed" sizes="114x114" href="'.$icon_114x114.'" />'."\n";
			
		$icon_72x72 = themeblvd_get_option('apple_touch_72x72');
		if( $icon_72x72 )
			echo '<link rel="apple-touch-icon-precomposed" sizes="72x72" href="'.$icon_72x72.'" />'."\n";
			
		$icon_57x57 = themeblvd_get_option('apple_touch_57x57');
		if( $icon_57x57 )
			echo '<link rel="apple-touch-icon-precomposed" href="'.$icon_57x57.'" />'."\n";

		// Pingback
		echo '<link rel="pingback" href="'.get_bloginfo( 'pingback_url' ).'" />'."\n";
		
		// HTML5 for old IE browsers
		echo "<!--[if lt IE 9]>\n";
		echo '<script src="'.get_template_directory_uri().'/framework/frontend/assets/js/html5.js" type="text/javascript"></script>';
		echo "<![endif]-->\n";
		
		// Comment reply JS
		if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
		
		// Standard WP head hook
		wp_head();
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
						$image_2x = '';
						if( ! empty( $option['image_2x'] ) )
							$image_2x = $option['image_2x'];
						echo '<a href="'.home_url().'" title="'.get_bloginfo('name').'" class="tb-image-logo"><img src="'.$option['image'].'" alt="'.get_bloginfo('name').'" data-image-2x="'.$image_2x.'" /></a>';
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
		if( themeblvd_get_option( 'responsive_css', null, 'true' ) != 'false' && themeblvd_get_option( 'mobile_nav' ) == 'mobile_nav_select' )
			echo themeblvd_nav_menu_select( apply_filters( 'themeblvd_responsive_menu_location', 'primary' ) );
		?>
		
		<?php if( themeblvd_get_option( 'mobile_nav' ) == 'mobile_nav_toggle_graphic' ) : ?>
			<a href="#access" class="btn btn-navbar">
				<?php echo apply_filters( 'themeblvd_btn_navbar_text', '<i class="icon-reorder"></i>' ); ?>
			</a>
		<?php endif; ?>
		
		<nav id="access" role="navigation">
			<div class="access-inner">
				<div class="access-content clearfix">
					<?php wp_nav_menu( array( 'menu_id' => 'primary-menu', 'menu_class' => 'sf-menu','container' => '', 'theme_location' => 'primary', 'fallback_cb' => 'themeblvd_primary_menu_fallback' ) ); ?>
					<?php themeblvd_header_menu_addon(); ?>
				</div><!-- .access-content (end) -->
			</div><!-- .access-inner (end) -->
		</nav><!-- #access (end) -->
		<?php
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
							<?php wp_nav_menu( array( 'menu_id' => 'footer-menu', 'container' => '', 'fallback_cb' => '', 'theme_location' => 'footer', 'depth' => 1 ) ); ?>
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

/**
 * Default display for action: themeblvd_featured
 *
 * @since 2.1.0
 */

if( ! function_exists( 'themeblvd_featured_blog_default' ) ) {
	function themeblvd_featured_blog_default() {
		if( is_home() || is_page_template( 'template_list.php' ) ) {
			if( ! themeblvd_config( 'builder' ) && themeblvd_get_option( 'blog_featured', null, false ) ) {
				$slider = themeblvd_get_option( 'blog_slider' );
				$type = get_post_meta( themeblvd_post_id_by_name($slider, 'tb_slider'), 'type', true );
				echo '<div class="element element-slider element-slider-'.$type.'">';
				echo '<div class="element-inner">';
				echo '<div class="element-inner-wrap">';
				themeblvd_slider( $slider );
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
		}
	}
}

/**
 * Default display for action: themeblvd_featured
 *
 * @since 2.2.0
 */
 
if( ! function_exists( 'themeblvd_featured_builder_default' ) ) {
	function themeblvd_featured_builder_default() {
		if( themeblvd_config( 'builder' ) )
			themeblvd_elements( themeblvd_config( 'builder' ), 'featured' );	
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

/**
 * Default display for action: themeblvd_featured_below_end
 *
 * @since 2.2.0
 */
 
if( ! function_exists( 'themeblvd_featured_below_builder_default' ) ) {
	function themeblvd_featured_below_builder_default() {
		if( themeblvd_config( 'builder' ) )
			themeblvd_elements( themeblvd_config( 'builder' ), 'featured_below' );	
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
		wp_reset_query();
		global $post;
		$display = '';
		// Pages and Posts
		if( is_page() || is_single() )
			$display = get_post_meta( $post->ID, '_tb_breadcrumbs', true );
		// Standard site-wide option
		if( ! $display || $display == 'default' )
			$display = themeblvd_get_option( 'breadcrumbs', null, 'show' );
		// Disable on posts homepage
		if( is_home() )
			$display = 'hide';
		// Show breadcrumbs if not hidden
		if( $display == 'show' )
			echo themeblvd_get_breadcrumbs();
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
			<time class="entry-date" datetime="<?php the_time('c'); ?>" pubdate><i class="icon-calendar"></i> <?php the_time( get_option('date_format') ); ?></time>
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
	function themeblvd_the_post_thumbnail_default( $location, $size, $link ) {
		echo themeblvd_get_post_thumbnail( $location, $size, $link );
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
			the_content( themeblvd_get_local('read_more').' &rarr;' );
		} else {
			// Show excerpt and read more button
			the_excerpt();
			echo '<p><a href="'.get_permalink( get_the_ID() ).'" class="btn btn-default">'.themeblvd_get_local( 'read_more' ).' &rarr;</a></p>';
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