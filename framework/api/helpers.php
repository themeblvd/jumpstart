<?php
/**
 * Setup all global vars for use with API functions.
 *
 * @global array $_themeblvd_core_options
 * @global array $_themeblvd_core_elements
 * @global array $_themeblvd_customizer_sections
 * @global array $_themeblvd_registered_elements
 * @global array $_themeblvd_user_sample_layouts
 * @global array $_themeblvd_remove_sample_layouts
 * @global array $_themeblvd_user_sidebar_locations
 * @global array $_themeblvd_remove_sidebar_locations
 * @global array $_themeblvd_user_sliders
 * @global array $_themeblvd_user_stylesheets
 * @global array $_themeblvd_remove_stylesheets
 * @since 2.1.0
 */

if( ! function_exists( 'themeblvd_api_init' ) ) { 
	function themeblvd_api_init() {
		
		global $_themeblvd_core_options;
		global $_themeblvd_core_elements;
		global $_themeblvd_customizer_sections;
		global $_themeblvd_registered_elements;
		global $_themeblvd_user_sample_layouts;
		global $_themeblvd_remove_sample_layouts;
		global $_themeblvd_user_sidebar_locations;
		global $_themeblvd_remove_sidebar_locations;
		global $_themeblvd_user_sliders;
		global $_themeblvd_user_stylesheets;
		global $_themeblvd_remove_stylesheets;
		
		// Options
		$_themeblvd_core_options = themeblvd_get_core_options(); // Filters must be applied before framework. 
		$_themeblvd_customizer_sections = array();
		
		// Core elements with options
		$_themeblvd_core_elements = themeblvd_get_core_elements(); // Filters must be applied before framework. 
		
		// Single dimensional array of elements
		$_themeblvd_registered_elements = array();
		foreach( $_themeblvd_core_elements as $element )
			$_themeblvd_registered_elements[] = $element['info']['id'];
		
		// Sample layouts
		$_themeblvd_user_sample_layouts = array();
		$_themeblvd_remove_sample_layouts = array();
		
		// Sidebars
		$_themeblvd_user_sidebar_locations = array();
		$_themeblvd_remove_sidebar_locations = array();
		
		// Sliders
		$_themeblvd_user_sliders = array();
		
		// User Stylesheets
		$_themeblvd_user_stylesheets = array(
			'1' => array(),	// Level 1: Before Framework styles
			'2' => array(),	// Level 2: After Framework styles
			'3' => array(),	// Level 3: After Theme styles
			'4' => array()	// Level 4: After Theme Options-generated styles
		);
		
		// Stylesheets to remove
		$_themeblvd_remove_stylesheets = array();
	}
}

/**
 * Setup the config array for which features the 
 * framework supports. This can easily be filtered, so the 
 * theme author has a chance to disable the framework's 
 * various features. The filter is this:
 *
 * themeblvd_global_config
 *
 * @since 2.0.0
 *
 * @return array $setup Features framework support
 */

if( ! function_exists( 'themeblvd_setup' ) ) { 
	function themeblvd_setup() {
		$setup = array(
			'primary' => array(
				'sliders' 			=> true,			// Sliders
				'sidebars'			=> true,			// Custom sidebars
				'builder'			=> true				// Custom layouts
			),
			'admin' => array(
				'options'			=> true,			// Entire Admin presence
				'sliders' 			=> true,			// Sliders page
				'builder'			=> true,			// Layouts page
				'sidebars'			=> true				// Sidebars page
			),
			'meta' => array(
				'hijack_atts'		=> true,			// Hijack and modify "Page Attributes"
				'page_options'		=> true,			// Meta box for basic page options
				'post_options'		=> true				// Meta box for basic post options
			),
			'featured' => array(
				'archive'			=> false,			// Featured area on/off by default
				'blog'				=> false,			// Featured area on/off by default
				'grid'				=> false,			// Featured area on/off by default
				'page'				=> false,			// Featured area on/off by default
				'single'			=> false			// Featured area on/off by default
			),
			'featured_below' => array(
				'archive'			=> false,			// Featured area on/off by default
				'blog'				=> false,			// Featured area on/off by default
				'grid'				=> false,			// Featured area on/off by default
				'page'				=> false,			// Featured area on/off by default
				'single'			=> false			// Featured area on/off by default
			),
			'comments' => array(
				'pages'				=> false,			// Comments on pages
				'posts'				=> true,			// Commments on posts
			)
		);
		return apply_filters( 'themeblvd_global_config', $setup );
	}
}

/**
 * Test whether an feature is currently supported.
 *
 * @since 2.0.0
 *
 * @param string $group admin or frontend
 * @param string $feature feature key to check
 * @return boolean
 */

if( ! function_exists( 'themeblvd_supports' ) ) {  
	function themeblvd_supports( $group, $feature ) {
		$setup = themeblvd_setup();
		if( isset( $setup[$group][$feature] ) && $setup[$group][$feature] )
			return true;
		else
			return false;
	}
}

/**
 * Get capability for admin module so WordPress 
 * can test this against current user-role.
 *
 * @since 2.1.0
 *
 * @param string $module Module ID to check
 * @return string $cap WP capability for current admin module
 */

if( ! function_exists( 'themeblvd_admin_module_cap' ) ) {  
	function themeblvd_admin_module_cap( $module ) {
		// Setup default capabilities
		$module_caps = array(
			'builder' 	=> 'edit_theme_options', 		// Role: Administrator
			'options' 	=> 'edit_theme_options',		// Role: Administrator
			'sidebars' 	=> 'edit_theme_options',		// Role: Administrator
			'sliders' 	=> 'edit_theme_options'			// Role: Administrator
		);
		$module_caps = apply_filters( 'themeblvd_admin_module_caps', $module_caps );
		
		// Setup capability
		$cap = '';
		if( isset( $module_caps[$module] ) )
			$cap = $module_caps[$module];
		
		return $cap;
	}
}

/**
 * Compress a chunk of code to output.
 *
 * @since 2.0.0
 * 
 * @param string $buffer Text to compress
 * @param string $buffer Buffered text
 * @return array $buffer Compressed text
 */

if( ! function_exists( 'themeblvd_compress' ) ) {  
	function themeblvd_compress( $buffer ) {
		/* remove comments */
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		/* remove tabs, spaces, newlines, etc. */
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
		return $buffer;
	}
}

/**
 * Register any post types used with framework.
 * 
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_register_posts' ) ) {  
	function themeblvd_register_posts() {
		
		// Sliders
		if( themeblvd_supports( 'primary', 'sliders' ) ) {
			$args = array(
				'labels' 			=> array( 'name' => 'Sliders', 'singular_name' => 'Slider' ),
				'public'			=> false,
				//'show_ui' 		=> true,	// Can uncomment for debugging
				'query_var' 		=> true,
				'capability_type' 	=> 'post',
				'hierarchical' 		=> false,
				'rewrite' 			=> false,
				'supports' 			=> array( 'title', 'custom-fields', 'editor' ), // needs to support 'editor' for image to be inserted properly
				'can_export'		=> true
			);
			register_post_type( 'tb_slider', $args );
		}
		
		// Custom Sidebars
		if( themeblvd_supports( 'primary', 'sidebars' ) ) {
			$args = array(
				'labels' 			=> array( 'name' => 'Widget Areas', 'singular_name' => 'Widget Area' ),
				'public'			=> false,
				//'show_ui' 		=> true,	// Can uncomment for debugging
				'query_var' 		=> true,
				'capability_type' 	=> 'post',
				'hierarchical' 		=> false,
				'rewrite' 			=> false,
				'supports' 			=> array( 'title', 'custom-fields' ), 
				'can_export'		=> true
			);
			register_post_type( 'tb_sidebar', $args );
		}
		
		// Custom Layouts
		if( themeblvd_supports( 'primary', 'builder' ) ) {
			$args = array(
				'labels' 			=> array( 'name' => 'Layouts', 'singular_name' => 'Layout' ),
				'public'			=> false,
				//'show_ui' 		=> true,	// Can uncomment for debugging
				'query_var' 		=> true,
				'capability_type' 	=> 'post',
				'hierarchical' 		=> false,
				'rewrite' 			=> false,
				'supports' 			=> array( 'title', 'custom-fields' ), 
				'can_export'		=> true
			);
			register_post_type( 'tb_layout', $args );
		}
	}
}

/**
 * Retrieves a post id given a post's slug and post type.
 *
 * @since 2.0.0
 * @uses $wpdb
 *
 * @param string $slug slug of post
 * @param string $post_type post type for post.
 * @return string $id ID of post.
 */
if( ! function_exists( 'themeblvd_post_id_by_name' ) ) { 
	function themeblvd_post_id_by_name( $slug, $post_type ) {
		global $wpdb;
		$null = null;
		$slug = sanitize_title( $slug );
		
		// Grab posts from DB (hopefully there's only one!)
		$posts = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND (post_type = %s)", $slug, $post_type ));
		
		// If no results, return null
		if ( empty($posts) )
			return $null;
		
		// Run through our results and return the ID of the first. 
		// Hopefully there was only one result, but if there was 
		// more than one, we'll just return a single ID.
		foreach ( $posts as $post )
			if( $post->ID )
				return $post->ID;
		
		// If for some odd reason, there was no ID in the returned 
		// post ID's, return nothing.
		return $null;
	}
}

/**
 * Register theme's nav menus.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_register_navs' ) ) { 
	function themeblvd_register_navs() {
		$menus = array(
			'primary' => __( 'Primary Navigation', 'themeblvd' ),
			'footer' => __( 'Footer Navigation', 'themeblvd' )
		);
		$menus = apply_filters( 'themeblvd_nav_menus', $menus );
		register_nav_menus( $menus );
	}
}

/**
 * Any occurances of WordPress's add_theme_support() happen here.
 * Can override function from Child Theme.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_add_theme_support' ) ) {
	function themeblvd_add_theme_support() {
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
	}
}

/**
 * Add items to admin menu bar. This needs to be here in general 
 * functions because admin bar appears on frontend as well.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_admin_menu_bar' ) ) {
	function themeblvd_admin_menu_bar() {
		global $wp_admin_bar;
		if( ! is_admin() ) {
			if( method_exists( $wp_admin_bar, 'add_menu' ) ) {
				
				// Theme Options
				if( themeblvd_supports( 'admin', 'options' ) ) {
					if( current_user_can( themeblvd_admin_module_cap( 'options' ) ) ) {	
						// Theme Options
						$wp_admin_bar->add_menu( 
							array(
								'id' => 'tb_theme_options',
								'title' => __( 'Theme Options', 'themeblvd' ),
								'parent' => 'site-name',
								'href' => admin_url( 'themes.php?page=options-framework')
							)
						);
					}
				}
				// Sidebars
				if( themeblvd_supports( 'admin', 'sidebars' ) ) {
					if( current_user_can( themeblvd_admin_module_cap( 'sidebars' ) ) ) {	
						$wp_admin_bar->add_menu( 
							array(
								'id' => 'tb_sidebars',
								'title' => __( 'Widget Areas', 'themeblvd' ),
								'parent' => 'site-name',
								'href' => admin_url( 'admin.php?page=sidebar_blvd')
							)
						);
					}
				}
				// Sliders
				if( themeblvd_supports( 'admin', 'sliders' ) ) {
					if( current_user_can( themeblvd_admin_module_cap( 'sliders' ) ) ) {	
						$wp_admin_bar->add_menu( 
							array(
								'id' => 'tb_sliders',
								'title' => __( 'Sliders', 'themeblvd' ),
								'parent' => 'site-name',
								'href' => admin_url( 'admin.php?page=slider_blvd')
							)
						);
					}
				}
				// Builder
				if( themeblvd_supports( 'admin', 'builder' ) ) {
					if( current_user_can( themeblvd_admin_module_cap( 'builder' ) ) ) {
						$wp_admin_bar->add_menu( 
							array(
								'id' => 'tb_builder',
								'title' => __( 'Builder', 'themeblvd' ),
								'parent' => 'site-name',
								'href' => admin_url( 'admin.php?page=builder_blvd')
							)
						);
					}
				}
				
			} // end if method_exists()
		} // end if is_admin()
	}
}

/**
 * Get all sidebar layouts.
 *
 * @since 2.0.0
 *
 * @return array $layouts All current sidebar layouts
 */

if( ! function_exists( 'themeblvd_sidebar_layouts' ) ) { 
	function themeblvd_sidebar_layouts() {
		$layouts = array(
			'full_width' => array(
				'name' 		=> 'Full Width',
				'id'		=> 'full_width',
				'columns'	=> array(
					'content' 	=> 'span12',
					'left' 		=> '',
					'right' 	=> '',
				)
			),
			'sidebar_right' => array(
				'name' 		=> 'Sidebar Right',
				'id'		=> 'sidebar_right',
				'columns'	=> array(
					'content' 	=> 'span8',
					'left' 		=> '',
					'right' 	=> 'span4',
				)
			),
			'sidebar_left' => array(
				'name' 		=> 'Sidebar Left',
				'id'		=> 'sidebar_left',
				'columns'	=> array(
					'content' 	=> 'span8',
					'left' 		=> 'span4',
					'right' 	=> '',
				)
			),
			'double_sidebar' => array(
				'name' 		=> 'Double Sidebar',
				'id'		=> 'double_sidebar',
				'columns'	=> array(
					'content' 	=> 'span6',
					'left' 		=> 'span3',
					'right' 	=> 'span3',
				)
			),
			'double_sidebar_left' => array(
				'name' 		=> 'Double Left Sidebars',
				'id'		=> 'double_sidebar_left',
				'columns'	=> array(
					'content' 	=> 'span6',
					'left' 		=> 'span3',
					'right' 	=> 'span3',
				)
			),
			'double_sidebar_right' => array(
				'name' 		=> 'Double Right Sidebars',
				'id'		=> 'double_sidebar_right',
				'columns'	=> array(
					'content' 	=> 'span6',
					'left' 		=> 'span3',
					'right' 	=> 'span3',
				)
			)
		);
		return apply_filters( 'themeblvd_sidebar_layouts', $layouts );
	}
}

/**
 * Get transparent textures
 *
 * @since 2.0.5
 *
 * @return array $textures All current textures
 */

if( ! function_exists( 'themeblvd_get_textures' ) ) {  
	function themeblvd_get_textures() {
		$imagepath = get_template_directory_uri().'/framework/frontend/assets/images/textures/';
		$textures = array(
			'boxy' => array( 
				'name' 		=> __( 'Boxy', 'themeblvd' ),
				'url' 		=> $imagepath.'boxy.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'carbon_classic' => array( 
				'name' 		=> __( 'Carbon Classic', 'themeblvd' ),
				'url' 		=> $imagepath.'carbon_classic.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'carbon_diagonal' => array( 
				'name' 		=> __( 'Carbon Diagonol', 'themeblvd' ),
				'url' 		=> $imagepath.'carbon_diagonal.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'carbon_weave' => array( 
				'name' 		=> __( 'Carbon Weave', 'themeblvd' ),
				'url' 		=> $imagepath.'carbon_weave.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'chex' => array( 
				'name' 		=> __( 'Chex', 'themeblvd' ),
				'url' 		=> $imagepath.'chex.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'concrete' => array( 
				'name' 		=> __( 'Concrete', 'themeblvd' ),
				'url' 		=> $imagepath.'concrete.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'cross' => array( 
				'name' 		=> __( 'Crosses', 'themeblvd' ),
				'url' 		=> $imagepath.'cross.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'denim' => array( 
				'name' 		=> __( 'Denim', 'themeblvd' ),
				'url' 		=> $imagepath.'denim.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'diagnol_thin' => array( 
				'name' 		=> __( 'Diagonal (thin)', 'themeblvd' ),
				'url' 		=> $imagepath.'diagnol_thin.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'diagnol_thick' => array( 
				'name' 		=> __( 'Diagonal (thick)', 'themeblvd' ),
				'url' 		=> $imagepath.'diagnol_thick.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'diamonds' => array( 
				'name' 		=> __( 'Diamonds', 'themeblvd' ),
				'url' 		=> $imagepath.'diamonds.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'grid' => array( 
				'name' 		=> __( 'Grid', 'themeblvd' ),
				'url' 		=> $imagepath.'grid.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'grunge' => array( 
				'name' 		=> __( 'Grunge', 'themeblvd' ),
				'url' 		=> $imagepath.'grunge.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'honey_comb' => array( 
				'name' 		=> __( 'Honey Comb', 'themeblvd' ),
				'url' 		=> $imagepath.'honey_comb.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'leather' => array( 
				'name' 		=> __( 'Leather', 'themeblvd' ),
				'url' 		=> $imagepath.'leather.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'metal' => array( 
				'name' 		=> __( 'Metal', 'themeblvd' ),
				'url' 		=> $imagepath.'metal.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'mosaic' => array( 
				'name' 		=> __( 'Mosaic', 'themeblvd' ),
				'url' 		=> $imagepath.'mosaic.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'noise' => array( 
				'name' 		=> __( 'Noise', 'themeblvd' ),
				'url' 		=> $imagepath.'noise.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'paper' => array( 
				'name' 		=> __( 'Paper', 'themeblvd' ),
				'url' 		=> $imagepath.'paper.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'plaid' => array( 
				'name' 		=> __( 'Plaid', 'themeblvd' ),
				'url' 		=> $imagepath.'plaid.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'rubber' => array( 
				'name' 		=> __( 'Rubber', 'themeblvd' ),
				'url' 		=> $imagepath.'rubber.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'squares' => array( 
				'name' 		=> __( 'Squares', 'themeblvd' ),
				'url' 		=> $imagepath.'squares.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'textile' => array( 
				'name' 		=> __( 'Textile', 'themeblvd' ),
				'url' 		=> $imagepath.'textile.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'vertical_fabric' => array( 
				'name' 		=> __( 'Vertical Fabric', 'themeblvd' ),
				'url' 		=> $imagepath.'vertical_fabric.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'vintage' => array( 
				'name' 		=> __( 'Vintage', 'themeblvd' ),
				'url' 		=> $imagepath.'vintage.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'wood' => array( 
				'name' 		=> __( 'Wood', 'themeblvd' ),
				'url' 		=> $imagepath.'wood.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'wood_planks' => array( 
				'name' 		=> __( 'Wood Planks', 'themeblvd' ),
				'url' 		=> $imagepath.'wood_planks.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'divider' => array( 
				'name' 		=> __( '---------------', 'themeblvd' ),
				'url' 		=> null,
				'position' 	=> null,
				'repeat' 	=> null,
			),
			'boxy_light' => array( 
				'name' 		=> __( 'Light Boxy', 'themeblvd' ),
				'url' 		=> $imagepath.'boxy_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'carbon_classic_light' => array( 
				'name' 		=> __( 'Light Carbon Classic', 'themeblvd' ),
				'url' 		=> $imagepath.'carbon_classic_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'carbon_diagonal_light' => array( 
				'name' 		=> __( 'Light Carbon Diagonol', 'themeblvd' ),
				'url' 		=> $imagepath.'carbon_diagonal_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'carbon_weave_light' => array( 
				'name' 		=> __( 'Light Carbon Weave', 'themeblvd' ),
				'url' 		=> $imagepath.'carbon_weave_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'chex_light' => array( 
				'name' 		=> __( 'Light Chex', 'themeblvd' ),
				'url' 		=> $imagepath.'chex_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'concrete_light' => array( 
				'name' 		=> __( 'Light Concrete', 'themeblvd' ),
				'url' 		=> $imagepath.'concrete_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'cross_light' => array( 
				'name' 		=> __( 'Light Crosses', 'themeblvd' ),
				'url' 		=> $imagepath.'cross_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'denim_light' => array( 
				'name' 		=> __( 'Light Denim', 'themeblvd' ),
				'url' 		=> $imagepath.'denim_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'diagnol_thin_light' => array( 
				'name' 		=> __( 'Light Diagonal (thin)', 'themeblvd' ),
				'url' 		=> $imagepath.'diagnol_thin_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'diagnol_thick_light' => array( 
				'name' 		=> __( 'Light Diagonal (thick)', 'themeblvd' ),
				'url' 		=> $imagepath.'diagnol_thick_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'diamonds_light' => array( 
				'name' 		=> __( 'Light Diamonds', 'themeblvd' ),
				'url' 		=> $imagepath.'diamonds_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'grid_light' => array( 
				'name' 		=> __( 'Light Grid', 'themeblvd' ),
				'url' 		=> $imagepath.'grid_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'grunge_light' => array( 
				'name' 		=> __( 'Light Grunge', 'themeblvd' ),
				'url' 		=> $imagepath.'grunge_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'honey_comb_light' => array( 
				'name' 		=> __( 'Light Honey Comb', 'themeblvd' ),
				'url' 		=> $imagepath.'honey_comb_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'leather_light' => array( 
				'name' 		=> __( 'Light Leather', 'themeblvd' ),
				'url' 		=> $imagepath.'leather_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'metal_light' => array( 
				'name' 		=> __( 'Light Metal', 'themeblvd' ),
				'url' 		=> $imagepath.'metal_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'mosaic_light' => array( 
				'name' 		=> __( 'Light Mosaic', 'themeblvd' ),
				'url' 		=> $imagepath.'mosaic_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'noise_light' => array( 
				'name' 		=> __( 'Light Noise', 'themeblvd' ),
				'url' 		=> $imagepath.'noise_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'paper_light' => array( 
				'name' 		=> __( 'Light Paper', 'themeblvd' ),
				'url' 		=> $imagepath.'paper_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'plaid_light' => array( 
				'name' 		=> __( 'Light Plaid', 'themeblvd' ),
				'url' 		=> $imagepath.'plaid_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'rubber_light' => array( 
				'name' 		=> __( 'Light Rubber', 'themeblvd' ),
				'url' 		=> $imagepath.'rubber_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'squares_light' => array( 
				'name' 		=> __( 'Light Squares', 'themeblvd' ),
				'url' 		=> $imagepath.'squares_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'textile_light' => array( 
				'name' 		=> __( 'Light Textile', 'themeblvd' ),
				'url' 		=> $imagepath.'textile_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'vertical_fabric_light' => array( 
				'name' 		=> __( 'Light Vertical Fabric', 'themeblvd' ),
				'url' 		=> $imagepath.'vertical_fabric_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'vintage_light' => array( 
				'name' 		=> __( 'Light Vintage', 'themeblvd' ),
				'url' 		=> $imagepath.'vintage_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'wood_light' => array( 
				'name' 		=> __( 'Light Wood', 'themeblvd' ),
				'url' 		=> $imagepath.'wood_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			),
			'wood_planks_light' => array( 
				'name' 		=> __( 'Light Wood Planks', 'themeblvd' ),
				'url' 		=> $imagepath.'wood_planks_light.png',
				'position' 	=> '0 0',
				'repeat' 	=> 'repeat',
			)
	
		);
		return apply_filters( 'themeblvd_textures', $textures );
	}
}

/**
 * Generates array to be used in a select option 
 * type of the options framework.
 *
 * @since 2.0.0
 *
 * @param $type string type of select to return
 * @return $select array items for select
 */
 
function themeblvd_get_select( $type ) {
	$select = array();
	switch( $type ) {
		
		// Pages
		case 'pages' :
			$pages_select = array();
			$pages = get_pages();
			if( ! empty( $pages ) )
				foreach( $pages as $page )
					$select[$page->post_name] = $page->post_title;
			else
				$select['null'] = __( 'No pages exist.', 'themeblvd' );
			break;
		
		// Categories
		case 'categories' :
			$select['all'] = __( '<strong>All Categories</strong>', 'themeblvd' );
			$categories = get_categories();
			foreach( $categories as $category )
				$select[$category->slug] = $category->name;
			break;
		
		// Sliders	
		case 'sliders' : 
			$sliders = get_posts( 'post_type=tb_slider&numberposts=-1' );
			if( ! empty( $sliders ) )
				foreach( $sliders as $slider )
					$select[$slider->post_name] = $slider->post_title;
			else
				$select['null'] = __( 'You haven\'t created any custom sliders yet.', 'themeblvd' );
			break;
			
		// Floating Sidebars
		case 'sidebars' :
			$sidebars = get_posts('post_type=tb_sidebar&numberposts=-1');
			if( ! empty( $sidebars ) ) {
				foreach( $sidebars as $sidebar ) {
					$location = get_post_meta( $sidebar->ID, 'location', true );
					if( $location == 'floating' )
						$select[$sidebar->post_name] = $sidebar->post_title;
				}
			} // Handle error message for no sidebars outside of this function
			break;
		
	}
	return $select;
}

/**
 * All color classes.
 *
 * @since 2.0.0
 *
 * @return $colors array all colors in framework filtered
 */
 
function themeblvd_colors() {
	$colors = array(
		'default'		=> __( 'Default Color', 'themeblvd' ),
		'black' 		=> __( 'Black', 'themeblvd' ),
		'blue' 			=> __( 'Blue', 'themeblvd' ),
		'brown' 		=> __( 'Brown', 'themeblvd' ),
		'dark_blue'		=> __( 'Dark Blue', 'themeblvd' ),
		'dark_brown' 	=> __( 'Dark Brown', 'themeblvd' ),
		'dark_green' 	=> __( 'Dark Green', 'themeblvd' ),
		'green' 		=> __( 'Green', 'themeblvd' ),
		'mauve' 		=> __( 'Mauve', 'themeblvd' ),
		'orange'		=> __( 'Orange', 'themeblvd' ),
		'pearl'			=> __( 'Pearl', 'themeblvd' ),
		'pink'			=> __( 'Pink', 'themeblvd' ),
		'purple'		=> __( 'Purple', 'themeblvd' ),
		'red'			=> __( 'Red', 'themeblvd' ),
		'slate_grey'	=> __( 'Slate Grey', 'themeblvd' ),
		'silver'		=> __( 'Silver', 'themeblvd' ),
		'steel_blue'	=> __( 'Steel Blue', 'themeblvd' ),
		'teal'			=> __( 'Teal', 'themeblvd' ),
		'yellow'		=> __( 'Yellow', 'themeblvd' ),
		'wheat'			=> __( 'Wheat', 'themeblvd' ),
		'white'			=> __( 'White', 'themeblvd' )
	);
	return apply_filters( 'themeblvd_colors', $colors );
}

/**
 * Stats
 *
 * @since 2.1.0
 */

function themeblvd_stats() {
	// API Key
	$api_key = 'y0zr2c64abc1qvebamzpnk4m3izccxpxxlfh';
	// Start of Metrics
	global $wpdb;
	$data = get_transient( 'presstrends_data' );
	if( ! $data || $data == '' ){
		$api_base = 'http://api.presstrends.io/index.php/api/sites/update/api/';
		$url = $api_base . $api_key . '/';
		// Theme Data (by Jason)
		$data = array();
		if( function_exists( 'wp_get_theme' ) ) {
			// Use wp_get_theme for WP 3.4+
			$theme_data = wp_get_theme( get_template() );
			$data['theme_name'] = str_replace( ' ', '', $theme_data->get('Name') ); // remove spaces to fix presstrend's bug
			$data['theme_version'] = str_replace( ' ', '', $theme_data->get('Version') ); // remove spaces to fix presstrend's bug		
		} else {
			// Deprecated theme data retrieval
			$theme_data = get_theme_data( get_template_directory() . '/style.css' );
			$data['theme_version'] = str_replace( ' ', '', $theme_data['Version'] ); // remove spaces to fix presstrend's bug
			$data['theme_name'] = str_replace( ' ', '', $theme_data['Name'] ); // remove spaces to fix presstrend's bug
		} 
		// Continue on ...
		$count_posts = wp_count_posts();
		$count_pages = wp_count_posts('page');
		$comments_count = wp_count_comments();
		//$theme_data = get_theme_data(get_stylesheet_directory() . '/style.css');
		$plugin_count = count(get_option('active_plugins'));
		$all_plugins = get_plugins();
		$plugin_name = ''; // (added by Jason)
		foreach( $all_plugins as $plugin_file => $plugin_data ){
			$plugin_name .= $plugin_data['Name'];
			$plugin_name .= '&';
		}
		$posts_with_comments = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}posts WHERE post_type='post' AND comment_count > 0");
		if( $count_posts->publish > 0  ) // fix by Jason
			$comments_to_posts = number_format(($posts_with_comments / $count_posts->publish) * 100, 0, '.', '');
		$pingback_result = $wpdb->get_var('SELECT COUNT(comment_ID) FROM '.$wpdb->comments.' WHERE comment_type = "pingback"');
		$data['url'] = stripslashes(str_replace(array('http://', '/', ':' ), '', site_url()));
		$data['posts'] = $count_posts->publish;
		$data['pages'] = $count_pages->publish;
		$data['comments'] = $comments_count->total_comments;
		$data['approved'] = $comments_count->approved;
		$data['spam'] = $comments_count->spam;
		$data['pingbacks'] = $pingback_result;
		$data['post_conversion'] = $comments_to_posts;
		//$data['theme_version'] = $theme_data['Version'];
		//$data['theme_name'] = $theme_data['Name'];
		$data['site_name'] = str_replace( ' ', '', get_bloginfo( 'name' ));
		$data['plugins'] = $plugin_count;
		$data['plugin'] = urlencode($plugin_name);
		$data['wpversion'] = get_bloginfo('version');
		foreach ( $data as $k => $v ) {
			$url .= $k . '/' . $v . '/';
		}
		// Manually set theme name to avoid confusion with feed
		if( defined( 'TB_THEME_NAME' ) )
			$data['theme_name'] = TB_THEME_NAME;
		// Send response and set transient
		$response = wp_remote_get( $url );
		set_transient('presstrends_data', $data, 60*60*24); // 1 day transient
	}
}

/**
 * Add custom stylesheet
 *
 * @since 2.1.0
 * 
 * @param string $handle ID for this stylesheet
 * @param string $src URL to stylesheet
 * @param int $level Level determines where stylesheet gets placed - 1, 2, 3, 4
 * @param string $ver Version of stylesheet
 * @param string $media Type of media target for stylesheet
 */

if( ! function_exists( 'themeblvd_add_stylesheet' ) ) {
	function themeblvd_add_stylesheet( $handle, $src, $level = 4, $ver = null, $media = 'all' ) {
		if( ! is_admin() ) {
			global $_themeblvd_user_stylesheets;
			$_themeblvd_user_stylesheets[$level][] = array(
				'handle' 	=> $handle,
				'src' 		=> $src,
				'level' 	=> $level,
				'ver' 		=> $ver,
				'media' 	=> $media
			);
		}		
	}
}

/**
 * Remove custom stylesheet
 *
 * @since 2.1.0
 * 
 * @param string $handle ID for this stylesheet
 */

if( ! function_exists( 'themeblvd_remove_stylesheet' ) ) {
	function themeblvd_remove_stylesheet( $handle ) {
		if( ! is_admin() ) {
			global $_themeblvd_remove_stylesheets;
			$_themeblvd_remove_stylesheets[] = $handle;
		}		
	}
}

/**
 * Remove all stylesheets that have been appplied 
 * to $_themeblvd_remove_stylesheets. This gets 
 * hooked into "wp_print_styles".
 *
 * @since 2.1.0
 * 
 * @param string $handle ID for this stylesheet
 */

if( ! function_exists( 'themeblvd_deregister_stylesheets' ) ) {
	function themeblvd_deregister_stylesheets( $handle ) {
		global $_themeblvd_remove_stylesheets;
		if( $_themeblvd_remove_stylesheets )
			foreach( $_themeblvd_remove_stylesheets as $handle )
				wp_deregister_style( $handle );
	}
}

/**
 * Register Image Sizes
 *
 * @since 2.2.0
 */

if( ! function_exists( 'themeblvd_textdomain' ) ) {
	function themeblvd_textdomain() {
		load_theme_textdomain( 'themeblvd', get_template_directory() . '/lang' );
		load_theme_textdomain( 'themeblvd_frontend', get_template_directory() . '/lang' );
	}
}

/**
 * Register Image Sizes
 *
 * @since 2.1.0
 */

if( ! function_exists( 'themeblvd_add_image_sizes' ) ) {
	function themeblvd_add_image_sizes() {
		
		// Content Width
		$content_width = apply_filters( 'themeblvd_content_width', 940 ); // Default width of primary content area
		
		// Crop sizes
		$sizes = array(
			'tb_large' => array(
				'width' 	=> $content_width,	// 940 => Full width thumb for 1-col page
				'height' 	=> 9999,
				'crop' 		=> false
			),
			'tb_medium' => array(
				'width' 	=> 620, 			// 620 => Full width thumb for 2-col/3-col page
				'height'	=> 9999,
				'crop' 		=> false
			),
			'tb_small' => array(
				'width' 	=> 195,				// Square'ish thumb floated left
				'height' 	=> 195,
				'crop' 		=> false
			),
			'square_small' => array(
				'width' 	=> 130,				// Square small thumbnail
				'height' 	=> 130,
				'crop' 		=> true
			),
			'square_smaller' => array(
				'width' 	=> 70,				// Square smaller thumbnail
				'height' 	=> 70,
				'crop' 		=> true
			),
			'square_smallest' => array(
				'width' 	=> 45,				// Square smallest thumbnail
				'height' 	=> 45,
				'crop' 		=> true
			),
			'slider-large' => array(
				'width' 	=> 940,				// Slider full-size image
				'height' 	=> 350,
				'crop' 		=> true
			),
			'slider-staged' => array(
				'width' 	=> 550,				// Slider staged image
				'height' 	=> 340,
				'crop' 		=> true
			),
			'grid_fifth_1' => array(
				'width' 	=> 200,				// 1/5 Column
				'height' 	=> 125,
				'crop' 		=> true
			),
			'grid_3' => array(
				'width' 	=> 240,				// 1/4 Column
				'height' 	=> 150,
				'crop' 		=> true
			),
			'grid_4' => array(
				'width' 	=> 320,				// 1/3 Column
				'height' 	=> 200,
				'crop' 		=> true
			),
			'grid_6' => array(
				'width' 	=> 472,				// 1/2 Column
				'height' 	=> 295,
				'crop' 		=> true
			)
		);
		$sizes = apply_filters( 'themeblvd_image_sizes', $sizes );
		
		// Add image sizes
		foreach( $sizes as $size => $atts )
			add_image_size( $size, $atts['width'], $atts['height'], $atts['crop'] );
	}
}

/**
 * Show theme's image thumb sizes when inserting 
 * an image in a post or page.
 *
 * This function gets added as a filter to WP's 
 * image_size_names_choose
 *
 * @since 2.1.0
 *
 * @return array Framework's image sizes
 */

if( ! function_exists( 'themeblvd_image_size_names_choose' ) ) {
	function themeblvd_image_size_names_choose( $sizes ) {
		$themeblvd_sizes = array(
			'tb_small' 		=> 'TB Small',
			'tb_medium' 	=> 'TB Medium',
			'tb_large' 		=> 'TB Large',
			'slider-large' 	=> 'Slider Full Width',
			'slider-staged' => 'Slider Staged',
			'grid_fifth_1' 	=> '1/5 Column of Grid',
			'grid_3' 		=> '1/4 Column of Grid',
			'grid_4' 		=> '1/3 Column of Grid',
			'grid_6' 		=> '1/2 Column of Grid'
		);
		$themeblvd_sizes = apply_filters( 'themeblvd_choose_sizes', $themeblvd_sizes );
		return array_merge( $sizes, $themeblvd_sizes );
	}
}

/**
 * Set allowed tags in the admin panel. This works by 
 * adding the framework's allowed admin tags to WP's 
 * global $allowedtags.
 *
 * @since 2.0.0
 * @uses $allowedposttags
 *
 * @return array $themeblvd_tags Allowed HTML tags for Theme Blvd options sanitation
 */

function themeblvd_allowed_tags() {
	
	global $allowedposttags;
	
	// Match Theme Blvd tags with global HTML 
	// allowed for standard Posts/Pages.
	$themeblvd_tags = $allowedposttags;

	// And make any adjustments
	$themeblvd_tags['iframe'] = array(
		'style' 				=> true,
		'width' 				=> true,
		'height' 				=> true,
		'src' 					=> true,
		'frameborder'			=> true,
		'allowfullscreen' 		=> true,
		'webkitAllowFullScreen'	=> true,
		'mozallowfullscreen' 	=> true
	);
	$themeblvd_tags['script'] = array(
		'type'					=> true,
		'src' 					=> true
	);
	
	return apply_filters( 'themeblvd_allowed_tags', $themeblvd_tags );
}

/**
 * Generates default column widths for column element.
 *
 * @since 2.0.0
 *
 * @return array $widths All column options with cooresponding widths.
 */

function themeblvd_column_widths() {
	$widths = array (
		'1-col' => array (					// User selects 1 columns
			array(
				'name' 	=> '100%',
				'value' => 'grid_12',
			)
		),
		'2-col' => array (					// User selects 2 columns
			array(
				'name' 	=> '20% | 80%',
				'value' => 'grid_fifth_1-grid_fifth_4',
			),
			array(
				'name' 	=> '25% | 75%',
				'value' => 'grid_3-grid_9',
			),
			array(
				'name' 	=> '30% | 70%',
				'value' => 'grid_tenth_3-grid_tenth_7',
			),
			array(
				'name' 	=> '33% | 66%',
				'value' => 'grid_4-grid_8',
			),
			array(
				'name' 	=> '50% | 50%',
				'value' => 'grid_6-grid_6',
			),
			array(
				'name' 	=> '66% | 33%',
				'value' => 'grid_8-grid_4',
			),
			array(
				'name' 	=> '70% | 30%',
				'value' => 'grid_tenth_7-grid_tenth_3',
			),
			array(
				'name' 	=> '75% | 25%',
				'value' => 'grid_9-grid_3',
			),
			array(
				'name' 	=> '80% | 20%',
				'value' => 'grid_fifth_4-grid_fifth_1',
			)
		),
		'3-col' => array (					// User selects 3 columns
			array(
				'name' 	=> '33% | 33% | 33%',
				'value' => 'grid_4-grid_4-grid_4',
			),
			array(
				'name' 	=> '25% | 25% | 50%',
				'value' => 'grid_3-grid_3-grid_6',
			),
			array(
				'name' 	=> '25% | 50% | 25%',
				'value' => 'grid_3-grid_6-grid_3',
			),
			array(
				'name' 	=> '50% | 25% | 25% ',
				'value' => 'grid_6-grid_3-grid_3',
			),
			array(
				'name' 	=> '20% | 20% | 60%',
				'value' => 'grid_fifth_1-grid_fifth_1-grid_fifth_3',
			),
			array(
				'name' 	=> '20% | 60% | 20%',
				'value' => 'grid_fifth_1-grid_fifth_3-grid_fifth_1',
			),
			array(
				'name' 	=> '60% | 20% | 20%',
				'value' => 'grid_fifth_3-grid_fifth_1-grid_fifth_1',
			),
			array(
				'name' 	=> '40% | 40% | 20%',
				'value' => 'grid_fifth_2-grid_fifth_2-grid_fifth_1',
			),
			array(
				'name' 	=> '40% | 20% | 40%',
				'value' => 'grid_fifth_2-grid_fifth_1-grid_fifth_2',
			),
			array(
				'name' 	=> '20% | 40% | 40%',
				'value' => 'grid_fifth_1-grid_fifth_2-grid_fifth_2',
			),
			array(
				'name' 	=> '30% | 30% | 40%',
				'value' => 'grid_tenth_3-grid_tenth_3-grid_fifth_2',
			),
			array(
				'name' 	=> '30% | 40% | 30%',
				'value' => 'grid_tenth_3-grid_fifth_2-grid_tenth_3',
			),
			array(
				'name' 	=> '40% | 30% | 30%',
				'value' => 'grid_fifth_2-grid_tenth_3-grid_tenth_3',
			)
		),
		'4-col' => array (					// User selects 4 columns
			array(
				'name' 	=> '25% | 25% | 25% | 25%',
				'value' => 'grid_3-grid_3-grid_3-grid_3',
			),
			array(
				'name' 	=> '20% | 20% | 20% | 40%',
				'value' => 'grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_2',
			),
			array(
				'name' 	=> '20% | 20% | 40% | 20%',
				'value' => 'grid_fifth_1-grid_fifth_1-grid_fifth_2-grid_fifth_1',
			),
			array(
				'name' 	=> '20% | 40% | 20% | 20%',
				'value' => 'grid_fifth_1-grid_fifth_2-grid_fifth_1-grid_fifth_1',
			),
			array(
				'name' 	=> '40% | 20% | 20% | 20%',
				'value' => 'grid_fifth_2-grid_fifth_1-grid_fifth_1-grid_fifth_1',
			)
		),
		'5-col' => array (						// User selects 5 columns
			array(
				'name' 	=> '20% | 20% | 20% | 20% | 20%',
				'value' => 'grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1',
			)
		)
	);
	return apply_filters( 'themeblvd_column_widths', $widths );
}

/**
 * Setup all possible assignments (i.e. WordPress conditionals) 
 * that could be assigned to an item. An example where this is 
 * currently used is to assign custom sidebars to various WP 
 * conditionals.
 *
 * @since 2.0.0
 *
 * @return array $conditionals Configuration of conditionals to choose from
 */
 
function themeblvd_conditionals_config() {
	$conditionals = array(
		'pages' => array(
			'id'	=> 'pages',
			'name'	=> __( 'Pages', 'themeblvd' ),
			'empty'	=> __( 'No pages to display.', 'themeblvd' )
		),
		'posts' => array(
			'id'	=> 'posts',
			'name'	=> __( 'Posts', 'themeblvd' ),
			'empty'	=> __( 'No posts to display.', 'themeblvd' )
		),
		'posts_in_category' => array(
			'id'	=> 'posts_in_category',
			'name'	=> __( 'Posts in Category', 'themeblvd' ),
			'empty'	=> __( 'No categories to display.', 'themeblvd' )
		),
		'categories' => array(
			'id'	=> 'categories',
			'name'	=> __( 'Category Archives', 'themeblvd' ),
			'empty'	=> __( 'No categories to display.', 'themeblvd' )
		),
		'tags' => array(
			'id'	=> 'tags',
			'name'	=> __( 'Tag Archives', 'themeblvd' ),
			'empty'	=> __( 'No tags to display.', 'themeblvd' )
		),
		'top' => array(
			'id'	=> 'top',
			'name'	=> __( 'Hierarchy', 'themeblvd' ),
			'items'	=> array(
				'home' 			=> __( 'Homepage', 'themeblvd' ),
				'posts' 		=> __( 'All Posts', 'themeblvd' ),
				'pages' 		=> __( 'All Pages', 'themeblvd' ),
				'archives' 		=> __( 'All Archives', 'themeblvd' ),
				'categories'	=> __( 'All Category Archives', 'themeblvd' ),
				'tags' 			=> __( 'All Tag Archives', 'themeblvd' ),
				'authors' 		=> __( 'All Author Archives', 'themeblvd' ),
				'search' 		=> __( 'Search Results', 'themeblvd' ),
				'404' 			=> __( '404 Page', 'themeblvd' )
			)
		)
	);
	return apply_filters( 'themeblvd_conditionals_config', $conditionals );
}