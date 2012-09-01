<?php
/**
 * Initiate Front-end
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_frontend_init' ) ) {
	function themeblvd_frontend_init() {

		global $_themeblvd_theme_settings;
		global $_themeblvd_user_stylesheets;
		global $_themeblvd_config;
		global $post;
		
		// Initiate vars
		$builder = false;
		$sidebar_layout = '';
		
		// Setup global theme settings
		$option_name = themeblvd_get_option_name();
		$_themeblvd_theme_settings = get_option( $option_name );
		if( ! $_themeblvd_theme_settings ) {
			// Theme Options have never been saved.
			themeblvd_add_sanitization();
			$options = themeblvd_get_formatted_options();
			$_themeblvd_theme_settings = themeblvd_get_option_defaults( $options );
		}
		
		/*------------------------------------------------------*/
		/* Primary Post ID
		/*------------------------------------------------------*/
		
		// Obviously at any time you can access the global $post object, 
		// however we want to store here in the config, so it can accessed 
		// from anywhere including in or after the loop.
		
		global $post;
		$primary = null;
		if( is_object( $post ) )
			$primary = $post->ID;
		
		/*------------------------------------------------------*/
		/* Fake Conditional
		/*------------------------------------------------------*/
		
		// This can be used to replace any conditional statements that 
		// come after any usages of query_posts
		
		$fake_conditional = themeblvd_get_fake_conditional();
		
		/*------------------------------------------------------*/
		/* Builder (ID of custom layout or false)
		/*------------------------------------------------------*/
		
		// If the user has slected the page template for a custom layout, 
		// here we'll set the ID for the chosen custom layout into the 
		// the main config. If the user hasn't selected this page template 
		// the $builder will be set to false. By setting this $builder var, 
		// we can make appropriate edits anywhere to content outside of the 
		// template_builder.php file if we need to.
		
		if( defined( 'TB_BUILDER_PLUGIN_VERSION' ) ) {
		
			// Custom Layout on static page
			if( is_page_template( 'template_builder.php' ) ) {
				$layout_id = get_post_meta( $post->ID, '_tb_custom_layout', true );
				if( $layout_id ) {
					$builder = $layout_id;
					$layout_post_id = themeblvd_post_id_by_name( $layout_id, 'tb_layout' );
					$layout_settings = get_post_meta( $layout_post_id, 'settings', true );
					$sidebar_layout = $layout_settings['sidebar_layout'];
				} else {
					$builder = 'error';
				}
			}
			
			// Custom Layout on homepage
			if( is_home() ) {
				$homepage_content = themeblvd_get_option( 'homepage_content', null, 'posts' );
				if( $homepage_content == 'custom_layout' ) {
					$layout_id = themeblvd_get_option( 'homepage_custom_layout' );
					if( $layout_id ) {
						$builder = $layout_id;
						$layout_post_id = themeblvd_post_id_by_name( $layout_id, 'tb_layout' );
						$layout_settings = get_post_meta( $layout_post_id, 'settings', true );
						$sidebar_layout = $layout_settings['sidebar_layout'];
					} else {
						$builder = 'error';
					}
				}
			}
		
		}
		
		/*------------------------------------------------------*/
		/* Featured Area
		/*------------------------------------------------------*/
		
		// In this framework, there will always be a featured area above 
		// the primary content and sidebar layout, whether it's styled or 
		// not. And since generally this featured area will have some sort 
		// of styling, padding, etc, with this set to true or false, we 
		// know whether to completely skip over the featured area's HTML 
		// markup or not when rendering a page from any template file.
		
		$featured = array();
		$featured_below = array();
		if( $builder ) {
			$layout_post_id = themeblvd_post_id_by_name( $builder, 'tb_layout' );
			$elements = get_post_meta( $layout_post_id, 'elements', true );
			$featured = themeblvd_featured_builder_classes( $elements, 'featured' );
			$featured_below = themeblvd_featured_builder_classes( $elements, 'featured_below' );	
		}
		if( is_home() ) {
			$homepage_content = themeblvd_get_option( 'homepage_content', null, 'posts' );
			if( $homepage_content != 'custom_layout' ) {
				if( themeblvd_get_option( 'blog_featured' ) || themeblvd_supports( 'featured', 'blog' ) )
					$featured[] = 'has_blog_featured';
				if( themeblvd_supports( 'featured_below', 'blog' ) )
					$featured_below[] = 'has_blog_featured_below';
			}
		}
		if( is_page_template( 'template_list.php' ) ) {
			if( themeblvd_get_option( 'blog_featured' ) || themeblvd_supports( 'featured', 'blog' ) )
				$featured[] = 'has_blog_featured';
			if( themeblvd_supports( 'featured_below', 'blog' ) )
				$featured_below[] = 'has_blog_featured_below';
		}
		if( is_page_template( 'template_grid.php' ) ) {
			if( themeblvd_supports( 'featured', 'grid' ) )
				$featured[] = 'has_grid_featured';
			if( themeblvd_supports( 'featured_below', 'grid' ) )
				$featured_below[] = 'has_grid_featured_below';
		}
		if( is_archive() || is_search() ) {
			if( themeblvd_supports( 'featured', 'archive' ) )
				$featured[] = 'has_archive_featured';
			if( themeblvd_supports( 'featured_below', 'archive' ) )
				$featured_below[] = 'has_archive_featured_below';
		}
		if( is_page() && ! is_page_template( 'template_builder.php' ) ) {
			if( themeblvd_supports( 'featured', 'page' ) )
				$featured[] = 'has_page_featured';
			if( themeblvd_supports( 'featured_below', 'page' ) )
				$featured_below[] = 'has_page_featured_below';
		}
		if( is_single() ) {
			if( themeblvd_supports( 'featured', 'single' ) )
				$featured[] = 'has_single_featured';
			if( themeblvd_supports( 'featured_below', 'single' ) )
				$featured_below[] = 'has_single_featured_below';
		}

		/*------------------------------------------------------*/
		/* Sidebar Layout (ID of sidebar layout)
		/*------------------------------------------------------*/
		
		// The sidebar layout depends on several scenarios the user could 
		// have theoretically setup form the admin panel. So, in all template 
		// files, we need to know the current sidebar layout at the start of 
		// rendering the content in order to know where to display the left 
		// and right sidebars within the overall HTML markup. Unfortunately, 
		// because the framework gives the user so many choices, the sidebars 
		// cannot be placed purely with CSS, and that's why this is necessary.
		
		if( ! $sidebar_layout ) {
			if( is_page() || is_single() )
				$sidebar_layout = get_post_meta( $post->ID, '_tb_sidebar_layout', true );
		}
		if( ! $sidebar_layout || 'default' == $sidebar_layout ) {
			$sidebar_layout = themeblvd_get_option( 'sidebar_layout', null, apply_filters( 'themeblvd_default_sidebar_layout', 'sidebar_right' ) );
		}
		$sidebar_layout = apply_filters( 'themeblvd_sidebar_layout', $sidebar_layout );
		
		/*------------------------------------------------------*/
		/* Sidebar ID's
		/*------------------------------------------------------*/
		
		// Moving past the sidebar layout, there are many potential sidebar 
		// locations the theme could be utilizing. Also, the user could 
		// have theoretically assigned different custom sidebars to different 
		// sidebar locations depending on what page we're on. So, here we 
		// need to determine all of the proper sidebar IDs for our current 
		// template situation. 
		// NOTE: Custom sidebar ID's have to be filtered onto
		// "themeblvd_custom_sidebar_id" -- The Widget Areas plugin does this.

		$sidebars = array();
		$sidebar_locations = themeblvd_get_sidebar_locations();
		foreach( $sidebar_locations as $location_id => $default_sidebar ) {
    		
    		// By default, the sidebar ID will match the ID of the
    		// current location.
    		$sidebar_id = apply_filters( 'themeblvd_custom_sidebar_id', $location_id );
    		
    		// Set current sidebar ID
    		$sidebars[$location_id]['id'] = $sidebar_id;
    		$sidebars[$location_id]['error'] = false;
    		
    		// Determine if there's an error with the sidebar. 
    		// In this case, there can only be a potential 
    		// error if the sidebar has no widgets.
    		if( ! is_active_sidebar( $sidebar_id ) ) {
				if( $default_sidebar['type'] == 'collapsible' ) {
					// If user set a custom sidebar, but left it empty, 
					// we need to tell 'em, but if this is a default 
					// collapsible sidebar, then the error can stay false.
					if( $sidebar_id != $location_id )
						$sidebars[$location_id]['error'] = true;
	    		} else {
	    			// No matter if it's custom or not, we need to tell the 
	    			// user if a fixed sidebar is empty.
	    			$sidebars[$location_id]['error'] = true;
				}
			}
				
    	}	

		/*------------------------------------------------------*/
		/* Finalize Frontend Configuration
		/*------------------------------------------------------*/
		
    	$config = array(
    		'id'				=> $primary,			// global $post->ID that can be accessed anywhere
    		'fake_conditional'	=> $fake_conditional,	// Fake conditional tag
    		'sidebar_layout' 	=> $sidebar_layout, 	// Sidebar layout
    		'builder'			=> $builder,			// ID of current custom layout if not false
    		'featured'			=> $featured,			// Classes for featured area, if empty area won't show
    		'featured_below'	=> $featured_below,		// Classes for featured below area, if empty area won't show
    		'sidebars'			=> $sidebars			// Array of sidbar ID's for all corresponding locations
    	);
    	$_themeblvd_config = apply_filters( 'themeblvd_frontend_config', $config );
	}
}

/**
 * Get classes for featured areas depending on what elements 
 * exist in a custom layout.
 *
 * @since 2.1.0
 *
 * @param array $elements All elements for current custom layout
 * @param array $area Area to use, featured or featured_below
 * @return array $classes Classes to use
 */

if( ! function_exists( 'themeblvd_featured_builder_classes' ) ) {
	function themeblvd_featured_builder_classes( $elements, $area ) {
		$classes = array();
		if( ! empty( $elements[$area] ) ) {
			$classes[] = 'has_builder';
			foreach( $elements[$area] as $element ) {
				switch( $element['type'] ) {
					case 'slider' :
						$classes[] = 'has_slider';
						break;
					case 'post_grid_slider' :
						$classes[] = 'has_slider';
						$classes[] = 'has_grid';
						$classes[] = 'has_post_grid_slider';
						break;
					case 'post_list_slider' :
						$classes[] = 'has_slider';
						$classes[] = 'has_post_list_slider';
						break;
					case 'post_grid' :
						$classes[] = 'has_grid';
						break;
				}
			}
			// First element classes
			$first_element = array_values( $elements[$area] );
			$first_element = array_shift( $first_element );
			$first_element = $first_element['type'];
			if( $first_element == 'slider' || $first_element == 'post_grid_slider' || $first_element == 'post_list_slider'  )
				$classes[] = 'slider_is_first';
			if( $first_element == 'post_grid' || $first_element == 'post_grid_slider' )
				$classes[] = 'grid_is_first';
			if( $first_element == 'slogan'  )
				$classes[] = 'slogan_is_first';
			// Last element classes
			$last_element = end( $elements[$area] );
			$last_element = $last_element['type'];
			if( $last_element == 'slider' || $last_element == 'post_grid_slider' || $last_element == 'post_list_slider'  )
				$classes[] = 'slider_is_last';
			if( $last_element == 'post_grid' || $last_element == 'post_grid_slider'  )
				$classes[] = 'grid_is_last';
			if( $last_element == 'slogan'  )
				$classes[] = 'slogan_is_last';
		}
		return $classes;
	}
}

/**
 * Add framework css classes to body_class() 
 *
 * @since 2.0.2
 *
 * @param array $classes Current WordPress body classes
 * @return array $classes Modified body classes
 */

if( ! function_exists( 'themeblvd_body_class' ) ) {
	function themeblvd_body_class( $classes ) {
		
		// Featured Area
		if( themeblvd_config( 'featured' ) )
			$classes[] = 'show-featured-area';
		else
			$classes[] = 'hide-featured-area';
			
		// Featured Area (below)
		if( themeblvd_config( 'featured_below' ) )
			$classes[] = 'show-featured-area-below';
		else
			$classes[] = 'hide-featured-area-above';

		// Custom Layout
		$custom_layout = themeblvd_config( 'builder' );
		if( $custom_layout ) {
			$classes[] = 'custom-layout-'.$custom_layout;
			$classes[] = 'has_custom_layout';
		}
			
		// Sidebar Layout
		$classes[] = 'sidebar-layout-'.themeblvd_config( 'sidebar_layout' );
		
		return $classes;
	}
}

/**
 * Set fake conditional.
 *
 * Because query_posts alters the current global $wp_query 
 * conditional, this function is called before query_posts 
 * and assigns a variable to act as a fake conditional if 
 * needed after query_posts.
 *
 * @since 2.0.0
 *
 * @return string $fake_condtional HTML to output thumbnail
 */

if( ! function_exists( 'themeblvd_get_fake_conditional' ) ) {
	function themeblvd_get_fake_conditional() {
		$fake_conditional = '';
		if( is_home() )
			$fake_conditional = 'home';
		else if( is_page_template( 'template_builder.php' ) )
			$fake_conditional = 'template_builder.php';
		else if( is_page_template( 'template_list.php' ) )
			$fake_conditional = 'template_list.php';
		else if( is_page_template( 'template_grid.php' ) )
			$fake_conditional = 'template_grid.php';
		else if( is_single() )
			$fake_conditional = 'single';
		else if( is_search() )
			$fake_conditional = 'search';
		else if ( is_archive() )
			$fake_conditional = 'archive';
		return $fake_conditional;
	}	
}

/**
 * This function is used from within the theme's template 
 * files to return the values setup in the previous init function.
 *
 * @since 2.0.0
 * 
 * @param $key string $key to retrieve from $_themeblvd_config
 * @return $value mixed value from $_themeblvd_config
 */

function themeblvd_config( $key, $seconday = null ) {
	global $_themeblvd_config;
	$value = null;
	if( $seconday ) {
		if( isset( $_themeblvd_config[$key][$seconday] ) )
			$value = $_themeblvd_config[$key][$seconday];	
	} else {	
		if( isset( $_themeblvd_config[$key] ) )
			$value = $_themeblvd_config[$key];
	}
	return $value;
}

/**
 * Load framework's JS scripts 
 *
 * To add scripts or remove unwanted scripts that you 
 * know you won't need to maybe save some frontend load 
 * time, this function can easily be re-done from a 
 * child theme.
 * 
 * (1) jQuery - Already registered by WP, and enqueued for most our scripts.
 * (2) Twitter Bootstrap - All Bootstrap JS plugins combiled.
 * (3) prettyPhoto - Modified version by Jason to include responsive features.
 * (4) Super Fish - Used for primary navigation.
 * (5) FlexSlider - Responsive slider, controls framework's "standard" slider type.
 * (6) Roundabout - Carousel-style slider, controls framwork's "3D Carousel" slider type.
 * (7) Theme Blvd scripts - Anything used by the framework to set other items into motion.
 * (8) iOS Orientation Fix - Allows zooming to be enabled on iOS devices while still 
 * allowing auto adjustment when switching between landscape and portrait.
 * (9) Already registered by WP, enable commentform to show when visitor clicks "Reply" on comment.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_include_scripts' ) ) {
	function themeblvd_include_scripts() {
		// Register scripts
		// wp_register_script( 'prettyPhoto', TB_FRAMEWORK_URI . '/frontend/assets/plugins/prettyphoto/js/jquery.prettyPhoto.js', array('jquery'), '3.1.3', true ); // Original un-modified prettyPhoto
		wp_register_script( 'bootstrap', TB_FRAMEWORK_URI . '/frontend/assets/plugins/bootstrap/js/bootstrap.min.js', array('jquery'), '2.1.0', true );
		wp_register_script( 'prettyPhoto', TB_FRAMEWORK_URI . '/frontend/assets/js/prettyphoto.js', array('jquery'), '3.1.3', true ); // Modified version of prettyPhoto by Jason Bobich
		wp_register_script( 'superfish', TB_FRAMEWORK_URI . '/frontend/assets/js/superfish.js', array('jquery'), '1.4.8', true );
		// wp_register_script( 'flexslider', TB_FRAMEWORK_URI . '/frontend/assets/js/flexslider.js', array('jquery'), '1.8', true );
		wp_register_script( 'flexslider', TB_FRAMEWORK_URI . '/frontend/assets/js/flexslider-2.js', array('jquery'), '2.0', true  );
		wp_register_script( 'roundabout', TB_FRAMEWORK_URI . '/frontend/assets/js/roundabout.js', array('jquery'), '1.1', true );
		wp_register_script( 'themeblvd', TB_FRAMEWORK_URI . '/frontend/assets/js/themeblvd.js', array('jquery'), TB_FRAMEWORK_VERSION, true ); // ... change back from dev
		wp_register_script( 'ios-orientationchange-fix', TB_FRAMEWORK_URI . '/frontend/assets/js/ios-orientationchange-fix.js', true );
		// Enqueue 'em
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'bootstrap' );
		wp_enqueue_script( 'prettyPhoto' );
		wp_enqueue_script( 'superfish' );
		// wp_enqueue_script( 'flexslider' ); // Enque'd within the content if warranted, will keep an eye on how this goes.
		// wp_enqueue_script( 'roundabout' ); // Enque'd within the content if warranted, will keep an eye on how this goes.
		wp_enqueue_script( 'themeblvd' );
		if( themeblvd_supports( 'display', 'responsive' ) )
			wp_enqueue_script( 'ios-orientationchange-fix' );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
	}
}

/**
 * Load framework's CSS files 
 *
 * To add styles or remove unwanted styles that you 
 * know you won't need to maybe save some frontend load 
 * time, this function can easily be re-done from a 
 * child theme.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_include_styles' ) ) {
	function themeblvd_include_styles() {
		// Level 1 user styles
		themeblvd_user_stylesheets( 1 );
		// Register framework styles
		wp_register_style( 'bootstrap', TB_FRAMEWORK_URI . '/frontend/assets/plugins/bootstrap/css/bootstrap.min.css', array(), '2.1.0' );
		wp_register_style( 'fontawesome', TB_FRAMEWORK_URI . '/frontend/assets/plugins/fontawesome/css/font-awesomeness.min.css', array(), '2.0' );
		wp_register_style( 'prettyPhoto', TB_FRAMEWORK_URI . '/frontend/assets/plugins/prettyphoto/css/prettyPhoto.css', array(), '3.1.3' );
		wp_register_style( 'themeblvd', TB_FRAMEWORK_URI . '/frontend/assets/css/themeblvd.css', array(), TB_FRAMEWORK_VERSION );
		// Enqueue framework styles
		wp_enqueue_style( 'bootstrap' );
		wp_enqueue_style( 'fontawesome' );
		wp_enqueue_style( 'prettyPhoto' );
		wp_enqueue_style( 'themeblvd' );
		// Level 2 user styles
		themeblvd_user_stylesheets( 2 );
	}
}

/**
 * Include font from google. Accepts unlimited 
 * amount of font arguments.
 *
 * @since 2.0.0
 *
 * @return string $stacks All current font stacks
 */

if( ! function_exists( 'themeblvd_include_google_fonts' ) ) {
	function themeblvd_include_google_fonts() {
		$fonts = func_get_args();
		if( ! empty( $fonts ) ) {
			// Before including files, determine if SSL is being 
			// used because if we include an external file without https 
			// on a secure server, they'll get an error.
			if( isset( $_SERVER['HTTPS'] ) )
				$protocol = 'https://'; // Google does support https
			else
				$protocol = 'http://';
			// Include each font file from google.
			foreach( $fonts as $font ) {
				if( $font['face'] == 'google' && $font['google'] ) {
					$name = themeblvd_remove_trailing_char( $font['google'] ); 
					$name = str_replace( ' ', '+', $name );
					echo '<link href="'.$protocol.'fonts.googleapis.com/css?family='.$name.'" rel="stylesheet" type="text/css">'."\n";
				}
			}
		}
	}
}

/**
 * Get all current font stacks
 *
 * @since 2.0.0
 *
 * @return string $stacks All current font stacks
 */

if( ! function_exists( 'themeblvd_font_stacks' ) ) {
	function themeblvd_font_stacks() {
		$stacks = array(
			'default'		=> 'Arial, sans-serif', // Used to chain onto end of google font
			'arial'     	=> 'Arial, "Helvetica Neue", Helvetica, sans-serif',
			'baskerville'	=> 'Baskerville, "Times New Roman", Times, serif',
			'georgia'   	=> 'Georgia, Times, "Times New Roman", serif',
			'helvetica' 	=> '"Helvetica Neue", Helvetica, Arial,sans-serif',
			'lucida'  		=> '"Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif',
			'palatino'  	=> 'Palatino, "Palatino Linotype", Georgia, Times, "Times New Roman", serif',
			'tahoma'    	=> 'Tahoma, Geneva, Verdana, sans-serif',
			'times'     	=> 'Times New Roman',
			'trebuchet' 	=> '"Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", Arial, sans-serif',
			'verdana'   	=> 'Verdana, Geneva, Tahoma, sans-serif',
			'google'		=> 'Google Font'
		);
		$stacks = apply_filters( 'themeblvd_font_stacks', $stacks );
		return $stacks;
	}
}

/**
 * Display CSS class for current sidebar layout. 
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_sidebar_layout_class' ) ) {
	function themeblvd_sidebar_layout_class() {
		global $_themeblvd_config;
		echo $_themeblvd_config['sidebar_layout'];
	}
}

/**
 * Get content extension for uses of get_template_part
 * End Usage: get_template_part( 'content', {$part} )
 * File name structure: content-{$part}.php
 *
 * @since 2.0.0
 * 
 * @param string $type Type of template part to get
 * @return string $part Extension to use for get_template_part
 */

if( ! function_exists( 'themeblvd_get_part' ) ) {
	function themeblvd_get_part( $type ) {
		$part = null;
		// Post format example (this is what you'd do if you were applying via a filter)
		// $post_format = null;
		// if( !temp is_404() && ! is_search() ) $post_format = get_post_format();
		// Parts
		$parts = array(
			'404'				=> '404',
			'archive'			=> 'archive',		// Note: If working with magazine theme, can change to 'archive_grid' or 'grid' to be compatible with archive.php
			'grid' 				=> 'grid',
			'grid_paginated' 	=> 'grid',
			'grid_slider' 		=> 'grid',
			'index' 			=> 'list',			// Note: If working with magazine theme, can change to 'index_grid' or 'grid' to be compatible with index.php
			'list'				=> 'list',
			'list_paginated'	=> 'list',
			'list_slider'		=> 'list',
			'page' 				=> 'page',			
			'search'			=> 'search',		// Note: This is for displaying content when no search results were found.
			'search_results'	=> 'archive',
			'single'			=> ''				// Note: For blog style theme, this could be changed to match "list"
		);
		$parts = apply_filters( 'themeblvd_template_parts', $parts );
		// Set and part if exists
		if( isset( $parts[$type] ) )
			$part = $parts[$type];
		// Return part	
		return $part;
	}
}

/**
 * Generate styles to be inserted after everything.
 *
 * @since 2.1.0
 *
 * @param int $level Level to apply stylesheet - 1, 2, 3, 4
 */

if( ! function_exists( 'themeblvd_user_stylesheets' ) ) {
	function themeblvd_user_stylesheets( $level ) {
		global $_themeblvd_user_stylesheets;
		// Add styles
		if( $level == 4 ) {
			// Manually insert level 4 stylesheet
			if( $_themeblvd_user_stylesheets[4] )
				foreach( $_themeblvd_user_stylesheets[4] as $stylesheet )
					echo "<link rel='stylesheet' id='".$stylesheet['handle']."' href='".$stylesheet['src']."' type='text/css' media='".$stylesheet['media']."' />";
		} else {
			// Use WordPress's enqueue system
			if( $_themeblvd_user_stylesheets[$level] )
				foreach( $_themeblvd_user_stylesheets[$level] as $stylesheet )
					wp_enqueue_style( $stylesheet['handle'], $stylesheet['src'], array(), $stylesheet['ver'], $stylesheet['media'] );
		}
	}
}


/**
 * Generate styles to be inserted after everything.
 *
 * @since 2.1.0
 */

if( ! function_exists( 'themeblvd_closing_styles' ) ) {
	function themeblvd_closing_styles() {
		// Show user stylesheets after 
		// ALL styles if any exist.
		themeblvd_user_stylesheets( 4 );
	}
}

/**
 * Adjust sidebar layout to always be full_width if we're 
 * on the WordPress Multisite signup page. This function is
 * added as a filter to themeblvd_sidebar_layout, which gets 
 * applied in themeblvd_frontend_init.
 *
 * @since 2.1.0
 */

if( ! function_exists( 'themeblvd_wpmultisite_signup_sidebar_layout' ) ) {
	function themeblvd_wpmultisite_signup_sidebar_layout( $sidebar_layout ) {
		global $pagenow;
		if( $pagenow == 'wp-signup.php' )
			$sidebar_layout = 'full_width';
		return $sidebar_layout;
	}
}

/**
 * Homepage posts_per_page bug fix.
 *
 * This is an interesting issue that we're trying to fix 
 * here. It only happens to about 1% of users.
 *
 * Basically, if you have a paginated element in a layout 
 * built with the framework's Layout Builder, SOMETIMES 
 * WordPress will not honor the "posts_per_page" of query_posts 
 * and the posts per page will not match up to the posts being 
 * displayed, resulting in not the correct posts showing on 
 * each page. 
 *
 * The only workaround before this function for this select few 
 * users was to make sure "Settings > Reading > Blog posts to show at most"
 * matched whatever the amount of posts per page the custom 
 * homepage layout has. This of course then effects all other places
 * posts are displayed throughout the WP site.
 *
 * This function is hooked to "pre_get_posts" and makes it so 
 * the true posts_per_page is adjusted in the query if this is 
 * a custom layout on the homepage.
 *
 * @since 2.2.0
 */

if( ! function_exists( 'themeblvd_homepage_posts_per_page' ) ) {
	function themeblvd_homepage_posts_per_page( $query ) {

	    // This is only for custom layouts and the homepage
		if( defined( 'TB_BUILDER_PLUGIN_VERSION' ) && is_home() ) {

			// The framework has not run at this point, so 
			// we manually need to check for a homepage layout.
			$new_posts_per_page = '';
			$builder = '';
			$option_name = themeblvd_get_option_name();
			$theme_options = get_option( $option_name );
			if( isset( $theme_options['homepage_content'] ) && $theme_options['homepage_content'] == 'custom_layout' ) {
				if( ! empty( $theme_options['homepage_custom_layout'] ) ) {
					// Determine custom layout info
					$builder = $theme_options['homepage_custom_layout'];
					$layout_post_id = themeblvd_post_id_by_name( $builder, 'tb_layout' );
					$elements = get_post_meta( $layout_post_id, 'elements', true );
					// Loop through elements searching for one with a primary query element
					foreach( $elements as $area ) {
						foreach( $area as $element ) {
							switch( $element['type'] ) {
								case 'post_grid_paginated' :
									if( $element['options']['rows'] && $element['options']['columns'] )
										$new_posts_per_page = $element['options']['rows'] * $element['options']['columns'];
									break;
								case 'post_list_paginated';
									if( $element['options']['posts_per_page'] )
										$new_posts_per_page = $element['options']['posts_per_page'];
									break;
							}	
						}
					}
				}
			}
			
			// And after ALL that, if we end up with a new post per 
			// page item, let's add it in!
			if( $new_posts_per_page )
				$query->set( 'posts_per_page', $new_posts_per_page );
				
	    }
	}
}

/**
 * Get class used to determine width of column in primary layout.
 *
 * @since 2.2.0
 * 
 * @param string $column Which column to retrieve class for
 * @return string $column_class The class to be used in grid system
 */

if( ! function_exists( 'themeblvd_get_column_class' ) ) {
	function themeblvd_get_column_class( $column ) {
		$column_class = '';
		$sidebar_layouts = themeblvd_sidebar_layouts();
		$current_sidebar_layout = themeblvd_config( 'sidebar_layout' );
		if( isset( $sidebar_layouts[$current_sidebar_layout]['columns'][$column] ) )
			$column_class = $sidebar_layouts[$current_sidebar_layout]['columns'][$column];
		return $column_class;
	}
}

/**
 * Add "btn" class to read more links.
 *
 * When a WP user uses the more tag <!--more-->, this filter 
 * will add the class "btn" to that link. This will allow 
 * Bootstrap to style the link as one of its buttons. 
 * 
 * This function is used with WP filter "the_content_more_link"
 *
 * @since 2.2.0
 */

if( ! function_exists( 'themeblvd_read_more_link' ) ) {
	function themeblvd_read_more_link( $read_more ) {
		// Add standard "btn" bootstrap class
		return str_replace( 'class="more-link"', 'class="more-link btn btn-default"', $read_more );
	}
}

/**
 * Add in [raw] shortcode functionality.
 *
 * This allows the user to wrap content when editing posts 
 * and pages with the [raw] shortcode so WP auto formatting 
 * doesn't get applied. 
 *
 * To remove this from a Child theme, you'd do: 
 * remove_action( 'after_setup_theme', 'themeblvd_raw_shortcode' );
 *
 * @since 2.2.0
 *
 * @return array $args Arguments to be passed into comment_form()
 */
 
if( ! function_exists( 'themeblvd_raw_shortcode' ) ) {
	function themeblvd_raw_shortcode() {
		remove_filter( 'the_content', 'wpautop' );
		remove_filter( 'the_content', 'wptexturize' );
		remove_filter( 'the_content', 'shortcode_unautop' );
		add_filter( 'the_content', 'themeblvd_content_formatter', 9 );	
	}
}