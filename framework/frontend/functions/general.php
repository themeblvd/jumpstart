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
		$builder_post_id = false;
		$sidebar_layout = '';
		
		// Setup global theme settings
		$option_name = themeblvd_get_option_name();
		$_themeblvd_theme_settings = apply_filters( 'themeblvd_frontend_options', get_option( $option_name ) );
		if( ! $_themeblvd_theme_settings ) {
			// Theme Options have never been saved.
			themeblvd_add_sanitization();
			$options = themeblvd_get_formatted_options();
			$_themeblvd_theme_settings = apply_filters( 'themeblvd_frontend_options', themeblvd_get_option_defaults( $options ) );
		}
		
		/*------------------------------------------------------*/
		/* Primary Post ID
		/*------------------------------------------------------*/
		
		// Obviously at any time you can access the global $post object, 
		// however we want to store here in the config, so it can accessed 
		// from anywhere including in or after the loop.
		
		global $post;
		$primary_id = null;
		if( is_object( $post ) )
			$primary_id = $post->ID;
		
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
				if( post_password_required() ) {
					// Password is currently required 
					// and so the layout is irrelevant.
					$builder = 'wp-private';
				} else {
					// Determine ID of custom layout
					$layout_id = get_post_meta( $primary_id, '_tb_custom_layout', true );
					if( $layout_id ) {
						$builder = $layout_id;
						$builder_post_id = themeblvd_post_id_by_name( $layout_id, 'tb_layout' );
						$layout_settings = get_post_meta( $builder_post_id, 'settings', true );
						$sidebar_layout = $layout_settings['sidebar_layout'];
					} else {
						$builder = 'error';
					}
				}
			}
			
			// Custom Layout on homepage
			if( is_home() && ! get_option( 'page_for_posts' ) ) {
				$homepage_content = themeblvd_get_option( 'homepage_content', null, 'posts' );
				if( $homepage_content == 'custom_layout' ) {
					$layout_id = themeblvd_get_option( 'homepage_custom_layout' );
					if( $layout_id ) {
						$builder = $layout_id;
						$builder_post_id = themeblvd_post_id_by_name( $layout_id, 'tb_layout' );
						$layout_settings = get_post_meta( $builder_post_id, 'settings', true );
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
		if( $builder && $builder != 'wp-private' ) {
			if( ! $builder_post_id  ) 
				$builder_post_id = themeblvd_post_id_by_name( $builder, 'tb_layout' );
			$elements = get_post_meta( $builder_post_id, 'elements', true );
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
				$sidebar_layout = get_post_meta( $primary_id, '_tb_sidebar_layout', true );
		}
		if( ! $sidebar_layout || 'default' == $sidebar_layout ) {
			$sidebar_layout = themeblvd_get_option( 'sidebar_layout' );
		}
		if( ! $sidebar_layout ){
			$sidebar_layout = apply_filters( 'themeblvd_default_sidebar_layout', 'sidebar_right', $sidebar_layout ); // Keeping for backwards compatibility, although is redundant with next filter.
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
		$custom_sidebars = defined('TB_SIDEBARS_PLUGIN_VERSION') ? get_posts('post_type=tb_sidebar&numberposts=-1') : null;
		$sidebar_overrides = defined('TB_SIDEBARS_PLUGIN_VERSION') ? get_post_meta( $primary_id, '_tb_sidebars', true ) : null;
		foreach( $sidebar_locations as $location_id => $default_sidebar ) {
    		
    		// By default, the sidebar ID will match the ID of the
    		// current location.
    		$sidebar_id = apply_filters( 'themeblvd_custom_sidebar_id', $location_id, $custom_sidebars, $sidebar_overrides, $primary_id );

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
			'id'				=> $primary_id,			// global $post->ID that can be accessed anywhere
			'fake_conditional'	=> $fake_conditional,	// Fake conditional tag
			'sidebar_layout'	=> $sidebar_layout,		// Sidebar layout
			'builder'			=> $builder,			// ID of current custom layout if not false
			'builder_post_id'	=> $builder_post_id,	// Numerical Post ID of tb_layout custom post
			'featured'			=> $featured,			// Classes for featured area, if empty area won't show
			'featured_below'	=> $featured_below,		// Classes for featured below area, if empty area won't show
			'sidebars'			=> $sidebars 			// Array of sidbar ID's for all corresponding locations
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
 * Determine current web browser and generate a CSS class for 
 * it. This function gets filtered onto WP's body_class.
 *
 * @since 2.2.0
 *
 * @param array $classes Current body classes
 * @return array $classes Body classes with browser classes added
 */
 
if( ! function_exists( 'themeblvd_browser_class' ) ) {
	function themeblvd_browser_class( $classes ) {
	 
		// Get current user agent
		$browser = $_SERVER[ 'HTTP_USER_AGENT' ];
	 
		// OS class
		if( preg_match( "/Mac/", $browser ) )
			$classes[] = 'mac';
		elseif( preg_match( "/Windows/", $browser ) )
			$classes[] = 'windows';
		elseif( preg_match( "/Linux/", $browser ) )
			$classes[] = 'linux';
		else
			$classes[] = 'unknown-os';
	 
		// Browser class
		if( preg_match( "/Chrome/", $browser ) ) {
			$classes[] = 'chrome';
		} elseif( preg_match( "/Safari/", $browser ) ) {
			$classes[] = 'safari';
		} elseif( preg_match( "/Opera/", $browser ) ) {
			$classes[] = 'opera';
		} elseif( preg_match( "/MSIE/", $browser ) ) {
			
			// Internet Explorer... ugh, kill me now.
			$classes[] = 'msie';
			if( preg_match( "/MSIE 6.0/", $browser ) )
				$classes[] = 'ie6';
			elseif ( preg_match( "/MSIE 7.0/", $browser ) )
				$classes[] = 'ie7';
			elseif ( preg_match( "/MSIE 8.0/", $browser ) )
				$classes[] = 'ie8';
			elseif ( preg_match( "/MSIE 9.0/", $browser ) )
				$classes[] = 'ie9';
			elseif ( preg_match( "/MSIE 10.0/", $browser ) )
				$classes[] = 'ie10';
	
		} elseif( preg_match( "/Firefox/", $browser ) && preg_match( "/Gecko/", $browser ) ) {
			$classes[] = 'firefox';
		} else {
			$classes[] = 'unknown-browser';
		}
		
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
 * At any time, this function can be called to effect 
 * the global template attributes array which can 
 * be utilized within template files.
 *
 * This system provides a way for attributes to be set 
 * and retreived with themeblvd_get_att() from files 
 * included with WP's get_template_part.
 *
 * @uses global $_themeblvd_template_atts
 * @since 2.2.0
 *
 * @param array $atts Attributes to be merged with global attributes 
 */

if( ! function_exists( 'themeblvd_set_atts' ) ) {
	function themeblvd_set_atts( $atts ) {
		
		global $_themeblvd_template_atts;
		
		// If no atts have been added, make
		// sure it exists as an array.
		if( ! $_themeblvd_template_atts )
			$_themeblvd_template_atts = array();
		
		// Merge inputted $atts 
		$_themeblvd_template_atts = array_merge( $_themeblvd_template_atts, $atts );
		
	}
}

/**
 * Working with the system established in the 
 * previous function, this function allows you
 * to set an individual att along with creating 
 * a new variable.
 *
 * @uses global $_themeblvd_template_atts
 * @since 2.2.0
 *
 * @param array $atts Attributes to be merged with global attributes 
 * @return string $value
 */

if( ! function_exists( 'themeblvd_set_att' ) ) {
	function themeblvd_set_att( $key, $value ) {
		
		global $_themeblvd_template_atts;
		
		// If no atts have been added, make
		// sure it exists as an array.
		if( ! $_themeblvd_template_atts )
			$_themeblvd_template_atts = array();
		
		// Add inputted attribute
		$_themeblvd_template_atts[$key] = $value;
		
		// Return value just in case this needs to be stored.
		// Ex: $value = themeblvd_set_att( 'key', 'value' );
		return $value;
		
	}
}

/**
 * Set template attributes for a page containing a 
 * grid in the main query.
 *
 * @uses themeblvd_set_atts()
 * @since 2.2.0
 */

if( ! function_exists( 'themeblvd_set_grid_atts' ) ) {
	function themeblvd_set_grid_atts() {
		
		global $post;
		
		// Columns and rows
		$columns = '';
		$rows = '';
		if( is_home() ){
			$columns = themeblvd_get_option( 'index_grid_columns' );
			$rows = themeblvd_get_option( 'index_grid_rows' );
		} elseif( is_archive() ) {
			$columns = themeblvd_get_option( 'archive_grid_columns' );
			$rows = themeblvd_get_option( 'archive_grid_rows' );
		} elseif( is_page_template( 'template_grid.php' ) ){
			$possible_column_nums = array( 1, 2, 3, 4, 5 );
			$custom_columns = get_post_meta( $post->ID, 'columns', true );
			if( in_array( intval( $custom_columns ), $possible_column_nums ) )
				$columns = $custom_columns;
			$rows = get_post_meta( $post->ID, 'rows', true );
		}
		if( ! $columns )
			$columns = apply_filters( 'themeblvd_default_grid_columns', 3 );
		if( ! $rows )
			$rows = apply_filters( 'themeblvd_default_grid_columns', 4 );
		
		// Posts per page, used for the grid display and not 
		// the actual main query of posts.
		$posts_per_page = $columns*$rows;
		
		// Thumbnail size
		$size = themeblvd_grid_class( $columns );
		$crop = '';
		if( is_home() ){
			$crop = apply_filters( 'themeblvd_index_grid_crop_size', $size );
		} elseif( is_archive() ) {
			$crop = apply_filters( 'themeblvd_archive_grid_crop_size', $size );
		} elseif( is_page_template( 'template_grid.php' ) ) {
			$crop = get_post_meta( $post->ID, 'crop', true );
			if( ! $crop )
				$crop = apply_filters( 'themeblvd_template_grid_crop_size', $size );
		}
		if( ! $crop )
			$crop = $size;
		
		// Query String
		$query_string = '';
		if( is_home() ){
			
			// Note: This is a special case where the theme has added a
			// custom option "index_grid_categories" for a theme that 
			// uses a grid on the homepage. This is NOT the case in the 
			// framework by default.

			// Categories
			$exclude = themeblvd_get_option( 'index_grid_categories' );
			if( $exclude ) {
				$categories = 'cat=';
				foreach( $exclude as $key => $value )
					if( $value )
						$categories .= '-'.$key.',';
				$categories = themeblvd_remove_trailing_char( $categories, ',' );
			}
			if( isset( $categories ) )
				$query_string .= $categories;
			// Posts per page
			$query_string .= 'posts_per_page='.$posts_per_page.'&';
			// Pagination
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$query_string .= $paged;
		
		} elseif( is_page_template( 'template_grid.php' ) ) {
			
			$custom_query_string = get_post_meta( $post->ID, 'query', true );
			if( $custom_query_string ) {
				
				// Custom query string
				$query_string = htmlspecialchars_decode($custom_query_string).'&';
				$query_string .= 'posts_per_page='.$posts_per_page.'&'; // User can't use posts_per_page in custom query
				if ( get_query_var('paged') )
			        $paged = get_query_var('paged');
			    else if ( get_query_var('page') )
			        $paged = get_query_var('page');
				else
			        $paged = 1;
				$query_string .= 'paged='.$paged;
			
			} else {
				
				// Generated query string
				$query_string = themeblvd_query_string( $posts_per_page );
			
			}
			$query_string = apply_filters( 'themeblvd_template_grid_query', $query_string, $custom_query_string, $post->ID );
		}
		
		// Set attributes
		$atts = array(
			'query_string' 		=> $query_string, 	// Only used for index.php and template_list.php
			'columns' 			=> $columns,
			'rows' 				=> $rows,
			'posts_per_page' 	=> $posts_per_page,
			'counter'			=> 0,
			'size'				=> $size,
			'crop'				=> $crop 			// Will be equal to "size" if not overridden
		);
		themeblvd_set_atts( apply_filters( 'themeblvd_grid_atts', $atts ) ); // Any atts you want available with themeblvd_get_att() in a template part can filtered on here.
				
	}
}

/**
 * Set template attributes for a page containing a 
 * list in the main query.
 *
 * @uses themeblvd_set_atts()
 * @since 2.2.0
 */

if( ! function_exists( 'themeblvd_set_list_atts' ) ) {
	function themeblvd_set_list_atts() {
		
		global $post;
		
		// Content
		$content = 'content'; // Can be "content" or "excerpt"
		if( is_home() )
			$content = themeblvd_get_option( 'blog_content', null, apply_filters( 'themeblvd_blog_content_default', 'content' ) );
		elseif( is_archive() )
			$content = themeblvd_get_option( 'archive_content', null, apply_filters( 'themeblvd_archive_content_default', 'content' ) );
		elseif( is_page_template( 'template_list.php' ) )
			$content = themeblvd_get_option( 'blog_content', null, apply_filters( 'themeblvd_list_template_content_default', 'content' ) );
		
		// Query string
		$query_string = '';
		if( is_home() ) {
			
			// Generated default query string
			$query_string = themeblvd_query_string();
		
		} elseif( is_page_template( 'template_list.php' ) ) {
			
			// If user has put in a custom query string to the page 
			// template, we'll work with, but if not, we'll use 
			// the default generated one. @uses "query" custom field.
			$custom_query_string = get_post_meta( $post->ID, 'query', true );
			if( $custom_query_string ) {
				// Custom query string
				$query_string = htmlspecialchars_decode($custom_query_string).'&';
				if ( get_query_var('paged') )
			        $paged = get_query_var('paged');
			    else if ( get_query_var('page') )
			        $paged = get_query_var('page');
				else
			        $paged = 1;
				$query_string .= 'paged='.$paged;
			
			} else {
				// Generated query string
				$query_string = themeblvd_query_string();
			}
			$query_string = apply_filters( 'themeblvd_template_list_query', $query_string, $custom_query_string, $post->ID );
		}
			
		// Set attributes
		$atts = array(
			'query_string' 	=> $query_string, // Only used for index.php and template_list.php
			'content' 		=> $content
		);
		themeblvd_set_atts( apply_filters( 'themeblvd_list_atts', $atts ) ); // Any atts you want available with themeblvd_get_att() in a template part can filtered on here.
	}
}

/**
 * Retrieve a single attribute set with 
 * themeblvd_set_atts()
 *
 * @uses global $_themeblvd_template_atts
 * @since 2.2.0
 *
 * @param string $key Array key to pull value from on $_themeblvd_template_atts
 * @return string $value Value pulled from $_themeblvd_template_atts based on inputted $key
 */

if( ! function_exists( 'themeblvd_get_att' ) ) {
	function themeblvd_get_att( $key ) {
		
		global $_themeblvd_template_atts;
		$value = '';
		
		// Set value if the array key exists
		if( isset( $_themeblvd_template_atts[$key] ) )
			$value = $_themeblvd_template_atts[$key];
		
		return $value;
	}
}

/**
 * This function is hooked to 'wp' in the loading 
 * process to setup any template attributes required 
 * for specific theme files.
 *
 * @since 2.2.0
 */

if( ! function_exists( 'themeblvd_atts_init' ) ) {
	function themeblvd_atts_init() {
		
		global $post;
		
		/*---------------------------------*/
		/* Index/Archive
		/*---------------------------------*/
		
		if( is_home() || is_archive() ) {
			
			// Possible template part ID's that will trigger the 
			// grid layout on main post loops
			$archive_grid_parts = apply_filters( 'archive_grid_parts', array( 'grid', 'index_grid' ) );
			
			// Template part the check against
			$template_part = is_archive() ? themeblvd_get_part( 'archive' ) : themeblvd_get_part( 'index' );
			
			// Set global attributes for the primary query based on 
			// whether this is a grid or list display.
			if( in_array( $template_part, $archive_grid_parts ) )
				themeblvd_set_grid_atts();
			else
				themeblvd_set_list_atts();
			
		}
		
		/*---------------------------------*/
		/* Single Posts
		/*---------------------------------*/
		
		if( is_single() ) {

			$show_meta = true;
			if( themeblvd_get_option( 'single_meta', null, 'show' ) == 'hide' )
				$show_meta = false;
			if( get_post_meta( $post->ID, '_tb_meta', true ) == 'hide' )
				$show_meta = false;
			else if( get_post_meta( $post->ID, '_tb_meta', true ) == 'show' )
				$show_meta = true;

			themeblvd_set_atts( apply_filters( 'themeblvd_single_atts', array( 'show_meta' => $show_meta ) ) ); // Any atts you want available with themeblvd_get_att() in a template part can filtered on here.
			
		}
		
		/*---------------------------------*/
		/* Post List Page Template
		/*---------------------------------*/
		
		if( is_page_template( 'template_list.php' ) )
			themeblvd_set_list_atts();
		
		/*---------------------------------*/
		/* Post Grid Page Template
		/*---------------------------------*/
		
		if( is_page_template( 'template_grid.php' ) )
			themeblvd_set_grid_atts();
		
	}
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
		
		global $themeblvd_framework_scripts;
		
		// Start framework scripts. This can be used declare the 
		// $deps of any enque'd JS files intended to come after 
		// the framework.
		$scripts = array( 'jquery' );

		// Register scripts -- These scripts are only enque'd as needed.
		wp_register_script( 'flexslider', TB_FRAMEWORK_URI . '/frontend/assets/js/flexslider.min.js', array('jquery'), '2.1', true  );
		wp_register_script( 'roundabout', TB_FRAMEWORK_URI . '/frontend/assets/js/roundabout.js', array('jquery'), '1.1', true );
		
		// Enque Scripts
		wp_enqueue_script( 'jquery' );
		if( themeblvd_supports( 'assets', 'bootstrap' ) ) {
			$scripts[] = 'bootstrap';
			wp_enqueue_script( 'bootstrap', TB_FRAMEWORK_URI . '/frontend/assets/plugins/bootstrap/js/bootstrap.min.js', array('jquery'), '2.2.1', true );
		}
		if( themeblvd_supports( 'assets', 'prettyphoto' ) ) {
			$scripts[] = 'prettyphoto';
			wp_enqueue_script( 'prettyphoto', TB_FRAMEWORK_URI . '/frontend/assets/js/prettyphoto.min.js', array('jquery'), '3.1.5', true ); // Modified version of prettyPhoto by Jason Bobich
			// wp_enqueue_script( 'prettyphoto', TB_FRAMEWORK_URI . '/frontend/assets/plugins/prettyphoto/js/jQuery.prettyPhoto.js', array('jquery'), '3.1.4', true ); // Unmodified version
		}
		if( themeblvd_supports( 'assets', 'superfish' ) ) {
			$scripts[] = 'superfish';
			wp_enqueue_script( 'superfish', TB_FRAMEWORK_URI . '/frontend/assets/js/superfish.min.js', array('jquery'), '1.4.8', true ); // Modified version of Superfish by Jason Bobich
		}
		if( themeblvd_supports( 'assets', 'primary_js' ) ) {
			$scripts[] = 'themeblvd';
			wp_enqueue_script( 'themeblvd', TB_FRAMEWORK_URI . '/frontend/assets/js/themeblvd.min.js', array('jquery'), TB_FRAMEWORK_VERSION, true );
			// Localize primary themeblvd.js script. This allows us to pass any filterable 
			// parameters through to our primary script. 
			wp_localize_script( 'themeblvd', 'themeblvd', themeblvd_get_js_locals() );
		}
		
		// Final filter on framework script.
		$themeblvd_framework_scripts = apply_filters( 'themeblvd_framework_scripts', $scripts );
		
		// Enque ios orientation and comment reply scripts.
		if( themeblvd_supports( 'display', 'responsive' ) && themeblvd_supports( 'assets', 'ios_orientation' ) )
			wp_enqueue_script( 'ios-orientationchange-fix', TB_FRAMEWORK_URI . '/frontend/assets/js/ios-orientationchange-fix.js', true );
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
		
		global $themeblvd_framework_stylesheets;
		
		// Start framework stylesheets. This can be used declare the 
		// $deps of any enque'd CSS files intended to come after 
		// the framework.
		$styles = array();
		
		// Level 1 user styles
		themeblvd_user_stylesheets( 1 ); // @deprecated
		
		// Register framework styles
		if( themeblvd_supports( 'assets', 'bootstrap' ) ) {
			$stylesheets[] = 'bootstrap';
			wp_enqueue_style( 'bootstrap', TB_FRAMEWORK_URI . '/frontend/assets/plugins/bootstrap/css/bootstrap.min.css', array(), '2.2.1' );
			$stylesheets[] = 'fontawesome';
			wp_enqueue_style( 'fontawesome', TB_FRAMEWORK_URI . '/frontend/assets/plugins/fontawesome/css/font-awesome.min.css', array(), '3.0.2' );
		}
		if( themeblvd_supports( 'assets', 'prettyphoto' ) ) {
			$stylesheets[] = 'prettyphoto';
			wp_enqueue_style( 'prettyphoto', TB_FRAMEWORK_URI . '/frontend/assets/plugins/prettyphoto/css/prettyPhoto.css', array(), '3.1.3' );
		}
		if( themeblvd_supports( 'assets', 'primary_css' ) ) {
			$stylesheets[] = 'themeblvd';
			wp_enqueue_style( 'themeblvd', TB_FRAMEWORK_URI . '/frontend/assets/css/themeblvd.min.css', array(), TB_FRAMEWORK_VERSION );
		}
		
		// Final filter on framework stylesheets.
		$themeblvd_framework_stylesheets = apply_filters( 'themeblvd_framework_stylesheets', $stylesheets );
		
		// Level 2 user styles
		themeblvd_user_stylesheets( 2 ); // @deprecated
		
	}
}

/**
 * Include font from google. Accepts unlimited 
 * amount of font arguments.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_include_google_fonts' ) ) {
	function themeblvd_include_google_fonts() {
		$fonts = func_get_args();
		$used = array();
		if( ! empty( $fonts ) ) {
			
			// Before including files, determine if SSL is being 
			// used because if we include an external file without https 
			// on a secure server, they'll get an error.
			$protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
			
			// Include each font file from google.
			foreach( $fonts as $font ) {
				if( $font['face'] == 'google' && $font['google'] ) {
					
					if( in_array( $font['google'], $used ) )
						continue; // Skip duplicate

					$used[] = $font['google'];
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
	return apply_filters( 'themeblvd_font_stacks', $stacks );
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

if( ! function_exists( 'themeblvd_posts_per_page' ) ) {
	function themeblvd_posts_per_page( $query ) {

		$new_posts_per_page = '';

	    /*---------------------------------*/
	    /* Homepage Custom Layouts
	    /*---------------------------------*/
	    
		if( defined( 'TB_BUILDER_PLUGIN_VERSION' ) && is_home() && !get_option('page_for_posts') && $query->is_main_query() ) {

			// The framework has not run at this point, so 
			// we manually need to check for a homepage layout.
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
					if( ! empty( $elements ) ) {
						foreach( $elements as $area ) {
							if( ! empty( $area ) ) {
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
				}
			}	
				
	    }
	    
	    /*---------------------------------*/
		/* Archive/Index Grids
		/*---------------------------------*/
		
		if( ! $new_posts_per_page ) {
			if( is_archive() || is_home() && $query->is_main_query() ) {
				
				// Possible template part ID's that will trigger the 
				// grid layout on main post loops
				$archive_grid_parts = apply_filters( 'archive_grid_parts', array( 'grid', 'index_grid' ) );
				
				// Template part the check against
				$template_part = is_archive() ? themeblvd_get_part( 'archive' ) : themeblvd_get_part( 'index' );
				
				// Only move forward if internal archive system should display in grid format.
				if( in_array( $template_part, $archive_grid_parts ) ) {
					// Columns
					$columns = themeblvd_get_option( 'archive_grid_columns' );
					if( ! $columns ) $columns = apply_filters( 'themeblvd_default_grid_columns', 3 );
					// Rows
					$rows = themeblvd_get_option( 'archive_grid_rows' );
					if( ! $rows ) $rows = apply_filters( 'themeblvd_default_grid_columns', 4 );
					// Posts per page = $columns x $rows
					$new_posts_per_page = $columns * $rows;
				}
				
			}
		}
		
		/*---------------------------------*/
		/* The Grand Finale
		/*---------------------------------*/
		
		// And after ALL that, if we end up with a new post per 
		// page item, let's add it in!
		if( $new_posts_per_page )
			$query->set( 'posts_per_page', $new_posts_per_page );
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
 * Use themeblvd_button() function for read more links.
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
	function themeblvd_read_more_link( $read_more, $more_link_text ) {
		
		$args = apply_filters( 'themeblvd_the_content_more_args', array(
			'text'			=> $more_link_text,
			'url'			=> get_permalink().'#more-'.get_the_ID(),
			'color'			=> 'default',
			'target' 		=> null,
			'size'			=> null,
			'classes'		=> null,
			'title'			=> null,
			'icon_before'	=> null, 
			'icon_after'	=> null, 
			'addon'			=> null
		));
		
		// Construct button based on filterable $args above
		$button = themeblvd_button( $args['text'], $args['url'], $args['color'], $args['target'], $args['size'], $args['classes'], $args['title'], $args['icon_before'], $args['icon_after'], $args['addon'] );

		return $button;
	}
}