<?php
/**
 * Initialize frontend
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_Frontend_Init {

	/*--------------------------------------------*/
	/* Properties, private
	/*--------------------------------------------*/

	/**
	 * A single instance of this class.
	 *
	 * @since 2.3.0
	 */
	private static $instance = null;

	/**
	 * Template parts for get_template_part()
	 *
	 * @since 2.3.0
	 */
	private $template_parts = array();

	/**
	 * Mode of framework, list or grid.
	 *
	 * @since 2.3.0
	 */
	private $mode = 'list';

	/**
	 * Configuration array for page being loaded.
	 *
	 * @since 2.3.0
	 */
	private $config = array();

	/**
	 * Template attributes. These help us pass template
	 * attributes around to different theme files,
	 * opposed to using global variables.
	 *
	 * @since 2.3.0
	 */
	private $atts = array();

	/*--------------------------------------------*/
	/* Constructor
	/*--------------------------------------------*/

	/**
     * Creates or returns an instance of this class.
     *
     * @since 2.3.0
     *
     * @return Theme_Blvd_Frontend_Init A single instance of this class.
     */
	public static function get_instance() {

		if ( self::$instance == null )
            self::$instance = new self;

        return self::$instance;
	}

	/**
	 * Constructor. Hook everything in.
	 *
	 * @since 2.3.0
	 */
	public function __construct() {

		add_action( 'pre_get_posts', array( $this, 'set_template_parts' ), 5 );
		add_action( 'pre_get_posts', array( $this, 'set_mode' ), 5 );
		add_action( 'wp', array( $this, 'set_config' ), 5 );
		add_action( 'wp', array( $this, 'atts_init' ) );
		add_filter( 'body_class', array( $this, 'body_class' ) );

	}

	/*--------------------------------------------*/
	/* Methods, mutators
	/*--------------------------------------------*/

	/**
	 * Set template parts for framework when using
	 * get_template_part() in theme's post loops.
	 *
	 * i.e. get_template_part( 'content', {$part} );
	 *
	 * @since 2.3.0
	 */
	public function set_template_parts() {
		$this->template_parts = apply_filters( 'themeblvd_template_parts', array(
			'404'				=> '404',
			'archive'			=> 'archive',	// Note: To set framework to grid mode, can change to 'archive_grid' or 'grid' to be compatible with archive.php
			'grid' 				=> 'grid',
			'grid_paginated' 	=> 'grid',
			'grid_slider' 		=> 'grid',
			'index' 			=> 'list',		// Note: To set framework to grid mode, can change to 'index_grid' or 'grid' to be compatible with index.php
			'list'				=> 'list',
			'list_paginated'	=> 'list',
			'list_slider'		=> 'list',
			'page' 				=> 'page',
			'search'			=> 'search',	// Note: This is for displaying content when no search results were found.
			'search_results'	=> 'archive',
			'single'			=> ''			// Note: For blog style theme, this could be changed to match "list"
		));
	}

	/**
	 * Set mode to either grid or list.
	 *
	 * @since 2.3.0
	 */
	public function set_mode() {

		// Default
		$this->mode = 'list';

		// Possible template part ID's that will trigger the
		// grid layout on main post loops
		$grid_triggers = apply_filters( 'themeblvd_grid_mode_triggers', array( 'grid', 'index_grid', 'archive_grid', 'search_grid' ) );

		// If this is the homepage, and "index" is one of the
		// triggers, set grid mode.
		if ( is_home() && in_array( $this->get_template_parts('index'), $grid_triggers ) )
			$this->mode = 'grid';

		// If this is an archive, and "archive" is one of the
		// triggers, set grid mode.
		if ( is_archive() && in_array( $this->get_template_parts('archive'), $grid_triggers ) )
			$this->mode = 'grid';

		// If this is search results, and "search_results" is one of the
		// triggers, set grid mode.
		if ( is_search() && in_array( $this->get_template_parts('search_results'), $grid_triggers ) )
			$this->mode = 'grid';

		// Allow manual override.
		$this->mode = apply_filters( 'themeblvd_theme_mode_override', $this->mode );
	}

	/**
	 * Set primary configuration array.
	 *
	 * @since 2.3.0
	 */
	public function set_config() {

		global $post;

		$this->config = array(
			'id'				=> 0,			// global $post->ID that can be accessed anywhere
			'builder'			=> false,		// ID of current custom layout if not false
			'builder_post_id'	=> 0,			// Numerical Post ID of tb_layout custom post
			'sidebar_layout'	=> '',			// Sidebar layout
			'featured'			=> array(),		// Classes for featured area, if empty area won't show
			'featured_below'	=> array(),		// Classes for featured below area, if empty area won't show
			'sidebars'			=> array() 		// Array of sidbar ID's for all corresponding locations
		);

		/*------------------------------------------------------*/
		/* Primary Post ID
		/*------------------------------------------------------*/

		// Store the ID of the original $post object in case
		// we modify the main query or need to ever access it.

		if ( is_object( $post ) )
			$this->config['id'] = $post->ID;

		/*------------------------------------------------------*/
		/* Custom Layout, Builder Name/ID
		/*------------------------------------------------------*/

		if ( defined( 'TB_BUILDER_PLUGIN_VERSION' ) ) {

			$layout_name = '';

			// Custom Layout on static page
			if ( is_page_template( 'template_builder.php' ) ) {
				if ( post_password_required() ) {

					// Password is currently required and so
					// the custom layout doesn't get used.
					$layout_name = 'wp-private';

				} else {

					$layout_name = get_post_meta( $this->config['id'], '_tb_custom_layout', true );
					if ( ! $layout_name )
						$layout_name = 'error';

				}
			}

			// Custom Layout over home "posts page"
			if ( is_home() && get_option( 'show_on_front' ) == 'posts' ) {
				if ( 'custom_layout' == themeblvd_get_option( 'homepage_content' ) ) {
					$layout_name = themeblvd_get_option( 'homepage_custom_layout' );
					if ( ! $layout_name )
						$layout_name = 'error';
				}
			}

			// If we have a layout name, setup it's ID and sidebar layout
			if ( $layout_name && $layout_name != 'error' && $layout_name != 'wp-private' ) {

				// Set name and ID
				$this->config['builder'] = $layout_name;
				$this->config['builder_post_id'] = themeblvd_post_id_by_name( $layout_name, 'tb_layout' );

				// Sidebar layout
				$layout_settings = get_post_meta( $this->config['builder_post_id'], 'settings', true );
				$this->config['sidebar_layout'] = $layout_settings['sidebar_layout'];

			}
		}

		/*------------------------------------------------------*/
		/* Featured Area
		/*------------------------------------------------------*/

		// For the featured area above or below the content to be
		// enabled, it must contain at least one CSS class or else
		// it won't be displayed for the current page.

		if ( $this->config['builder'] && $this->config['builder'] != 'error' && $this->config['builder'] != 'wp-private' ) {
			$elements = get_post_meta( $this->config['builder_post_id'], 'elements', true );
			$this->config['featured'] = $this->featured_builder_classes( $elements, 'featured' );
			$this->config['featured_below'] = $this->featured_builder_classes( $elements, 'featured_below' );
		}

		if ( is_home() ) {
			if ( 'custom_layout' != themeblvd_get_option( 'homepage_content' ) ) {
				if ( themeblvd_get_option( 'blog_featured' ) || themeblvd_supports( 'featured', 'blog' ) )
					$this->config['featured'][] = 'has_blog_featured';
				if ( themeblvd_supports( 'featured_below', 'blog' ) )
					$this->config['featured_below'][] = 'has_blog_featured_below';
			}
		}

		if ( is_page_template( 'template_list.php' ) ) {
			if ( themeblvd_get_option( 'blog_featured' ) || themeblvd_supports( 'featured', 'blog' ) )
				$this->config['featured'][] = 'has_blog_featured';
			if ( themeblvd_supports( 'featured_below', 'blog' ) )
				$this->config['featured_below'][] = 'has_blog_featured_below';
		}

		if ( is_page_template( 'template_grid.php' ) ) {
			if ( themeblvd_supports( 'featured', 'grid' ) )
				$this->config['featured'][] = 'has_grid_featured';
			if ( themeblvd_supports( 'featured_below', 'grid' ) )
				$this->config['featured_below'][] = 'has_grid_featured_below';
		}

		if ( is_archive() || is_search() ) {
			if ( themeblvd_supports( 'featured', 'archive' ) )
				$this->config['featured'][] = 'has_archive_featured';
			if ( themeblvd_supports( 'featured_below', 'archive' ) )
				$this->config['featured_below'][] = 'has_archive_featured_below';
		}

		if ( is_page() && ! is_page_template( 'template_builder.php' ) ) {
			if ( themeblvd_supports( 'featured', 'page' ) )
				$this->config['featured'][] = 'has_page_featured';
			if ( themeblvd_supports( 'featured_below', 'page' ) )
				$this->config['featured_below'][] = 'has_page_featured_below';
		}

		if ( is_single() ) {
			if ( themeblvd_supports( 'featured', 'single' ) )
				$this->config['featured'][] = 'has_single_featured';
			if ( themeblvd_supports( 'featured_below', 'single' ) )
				$this->config['featured_below'][] = 'has_single_featured_below';
		}

		/*------------------------------------------------------*/
		/* Sidebar Layout
		/*------------------------------------------------------*/

		// The sidebar layout is how the left and right sidebar will
		// be displayed on the current page.

		if ( ! $this->config['sidebar_layout'] && ( is_page() || is_single() ) )
			$this->config['sidebar_layout']= get_post_meta( $this->config['id'], '_tb_sidebar_layout', true );

		if ( ! $this->config['sidebar_layout']|| 'default' == $this->config['sidebar_layout'] )
			$this->config['sidebar_layout']= themeblvd_get_option( 'sidebar_layout' );

		if ( ! $this->config['sidebar_layout'])
			$this->config['sidebar_layout']= apply_filters( 'themeblvd_default_sidebar_layout', 'sidebar_right', $this->config['sidebar_layout']); // Keeping for backwards compatibility, although is redundant with next filter.

		$this->config['sidebar_layout']= apply_filters( 'themeblvd_sidebar_layout', $this->config['sidebar_layout']);

		/*------------------------------------------------------*/
		/* Sidebar ID's
		/*------------------------------------------------------*/

		// Determine which sidebar ID's belong to each sidebar location
		// on the current page. For each location, this will be the
		// framework's cooresponding default sidebar, unless something
		// else is filtered in. -- Note: The Widget Areas plugin filters
		// into this.

		$locations = themeblvd_get_sidebar_locations();
		$custom_sidebars = defined('TB_SIDEBARS_PLUGIN_VERSION') ? get_posts('post_type=tb_sidebar&numberposts=-1') : null;
		$sidebar_overrides = defined('TB_SIDEBARS_PLUGIN_VERSION') ? get_post_meta( $this->config['id'], '_tb_sidebars', true ) : null;

		foreach ( $locations as $location_id => $default_sidebar ) {

    		// By default, the sidebar ID will match the ID of the
    		// current location.
    		$sidebar_id = apply_filters( 'themeblvd_custom_sidebar_id', $location_id, $custom_sidebars, $sidebar_overrides, $this->config['id'] );

    		// Set current sidebar ID
    		$this->config['sidebars'][$location_id]['id'] = $sidebar_id;
    		$this->config['sidebars'][$location_id]['error'] = false;

    		// Determine sidebar error (i.e. sidebar is empty)
    		if ( ! is_active_sidebar( $sidebar_id ) ) {
				if ( $default_sidebar['type'] == 'collapsible' ) {

					// Only an error if collapsible sidebar is custom.
					if ( $sidebar_id != $location_id )
						$this->config['sidebars'][$location_id]['error'] = true;

	    		} else {

	    			// Custom or not, we need to tell the user if a
	    			// fixed sidebar is empty.
	    			$this->config['sidebars'][$location_id]['error'] = true;

				}
			}

		}

		/*------------------------------------------------------*/
		/* Extend
		/*------------------------------------------------------*/

		$this->config = apply_filters( 'themeblvd_frontend_config', $this->config );
	}

	/*--------------------------------------------*/
	/* Methods, accessors
	/*--------------------------------------------*/

	/**
	 * Get template part(s).
	 *
	 * @since 2.3.0
	 *
	 * @param string $part Optional specific part to pull from $template_parts
	 * @return string|array All template parts or specific template part
	 */
	public function get_template_parts( $part = '' ) {

		if ( ! $part )
			return $this->template_parts;

		if ( ! isset( $this->template_parts[$part] ) )
			return null;

		return $this->template_parts[$part];
	}

	/**
	 * Get theme mode (i.e. grid or list).
	 *
	 * @since 2.3.0
	 *
	 * @return string $mode Current theme mode, grid or list
	 */
	public function get_mode() {
		return $this->mode;
	}

	/**
	 * Get an item from the main frontend $config array.
	 *
	 * @since 2.3.0
	 *
	 * @return string Value from $config array
	 */
	public function get_config( $key = '', $secondary = '' ) {

		if ( ! $key )
			return $this->config;

		if ( $secondary && isset( $this->config[$key][$secondary] ) )
			return $this->config[$key][$secondary];

		if ( isset( $this->config[$key] ) )
			return $this->config[$key];

		return null;
	}

	/**
	 * Get template attributes
	 *
	 * @since 2.3.0
	 *
	 * @return array $atts Template attributes
	 */
	public function get_atts() {
		return $this->atts;
	}

	/*--------------------------------------------*/
	/* Methods, configuration helpers
	/*--------------------------------------------*/

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
	private function featured_builder_classes( $elements, $area ) {

		$classes = array();

		if ( ! empty( $elements[$area] ) ) {

			$classes[] = 'has_builder';
			foreach ( $elements[$area] as $element ) {
				switch ( $element['type'] ) {
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

			$sliders = apply_filters('themeblvd_slider_element_list', array('slider', 'post_slider', 'post_grid_slider', 'post_list_slider'));

			// First element classes
			$first_element = array_values( $elements[$area] );
			$first_element = array_shift( $first_element );
			$first_element = $first_element['type'];

			if ( in_array( $first_element, $sliders ) )
				$classes[] = 'slider_is_first';

			if ( $first_element == 'post_grid' || $first_element == 'post_grid_slider' )
				$classes[] = 'grid_is_first';

			if ( $first_element == 'slogan'  )
				$classes[] = 'slogan_is_first';

			// Last element classes
			$last_element = end( $elements[$area] );
			$last_element = $last_element['type'];

			if ( in_array( $last_element, $sliders ) )
				$classes[] = 'slider_is_last';

			if ( $last_element == 'post_grid' || $last_element == 'post_grid_slider'  )
				$classes[] = 'grid_is_last';

			if ( $last_element == 'slogan'  )
				$classes[] = 'slogan_is_last';
		}

		return apply_filters( 'featured_builder_classes', $classes, $elements, $area );
	}

	/*--------------------------------------------*/
	/* Methods, filters
	/*--------------------------------------------*/

	/**
	 * Add framework css classes to body_class() based
	 * on our main configuration.
	 *
	 * @since 2.3.0
	 *
	 * @param array $classes Current WordPress body classes
	 * @return array $classes Modified body classes
	 */
	public function body_class( $classes ) {

		// Featured Area
		if ( $this->config['featured'] )
			$classes[] = 'show-featured-area';
		else
			$classes[] = 'hide-featured-area';

		// Featured Area (below)
		if ( $this->config['featured_below'] )
			$classes[] = 'show-featured-area-below';
		else
			$classes[] = 'hide-featured-area-above';

		// Custom Layout
		if ( $this->config['builder'] ) {
			$classes[] = 'custom-layout-'.$this->config['builder'];
			$classes[] = 'has_custom_layout';
		}

		// Sidebar Layout
		$classes[] = 'sidebar-layout-'.$this->config['sidebar_layout'];

		return $classes;
	}

	/*--------------------------------------------*/
	/* Methods, template attribute
	/*--------------------------------------------*/

	/**
	 * Set template attributes.
	 *
	 * @since 2.3.0
	 */
	public function atts_init() {

		// Index/Archive
		if ( is_home() || is_archive() || is_search() ) {
			if ( $this->mode == 'grid' )
				$this->atts = $this->get_default_grid_atts();
			else
				$this->atts = $this->get_default_list_atts();
		}

		// Single posts
		if ( is_single() ) {

			$show_meta = true;
			if ( themeblvd_get_option( 'single_meta', null, 'show' ) == 'hide' )
				$show_meta = false;
			if ( get_post_meta( $this->config['id'], '_tb_meta', true ) == 'hide' )
				$show_meta = false;
			else if ( get_post_meta( $this->config['id'], '_tb_meta', true ) == 'show' )
				$show_meta = true;

			$this->atts = apply_filters( 'themeblvd_single_atts', array( 'show_meta' => $show_meta ) );

		}

		// Post List Page Template
		if ( is_page_template( 'template_list.php' ) )
			$this->atts = $this->get_default_list_atts();

		// Post Grid Page Template
		if ( is_page_template( 'template_grid.php' ) )
			$this->atts = $this->get_default_grid_atts();
	}

	/**
	 * Generate default template attributes for post list pages.
	 *
	 * @since 2.3.0
	 *
	 * @return array $atts Default template attributes for post list page
	 */
	private function get_default_list_atts() {

		// Content
		$content = 'content'; // Can be "content" or "excerpt"
		if ( is_home() )
			$content = themeblvd_get_option( 'blog_content', null, apply_filters( 'themeblvd_blog_content_default', 'content' ) );
		elseif ( is_archive() )
			$content = themeblvd_get_option( 'archive_content', null, apply_filters( 'themeblvd_archive_content_default', 'content' ) );
		elseif ( is_page_template( 'template_list.php' ) )
			$content = themeblvd_get_option( 'blog_content', null, apply_filters( 'themeblvd_list_template_content_default', 'content' ) );

		// Note: Thumbnail size could be passed here, but not needed
		// because it gets determined in themeblvd_get_post_thumbnail()
		// when left blank.

		// Set attributes
		$atts = array(
			'content' => $content
		);

		return apply_filters( 'themeblvd_list_atts', $atts );

	}

	/**
	 * Generate default template attributes for post grid pages.
	 *
	 * @since 2.3.0
	 *
	 * @return array $atts Default template attributes for post list page
	 */
	private function get_default_grid_atts() {

		global $post;

		// Columns and rows
		$columns = '';
		$rows = '';

		if ( is_home() ) {

			$columns = themeblvd_get_option( 'index_grid_columns' );
			$rows = themeblvd_get_option( 'index_grid_rows' );

		} elseif ( is_archive() || is_search() ) {

			$columns = themeblvd_get_option( 'archive_grid_columns' );
			$rows = themeblvd_get_option( 'archive_grid_rows' );

		} elseif ( is_page_template( 'template_grid.php' ) ) {

			$possible_column_nums = array( 1, 2, 3, 4, 5 );
			$custom_columns = get_post_meta( $this->config['id'], 'columns', true );

			if ( in_array( intval( $custom_columns ), $possible_column_nums ) )
				$columns = $custom_columns;

			$rows = get_post_meta( $this->config['id'], 'rows', true );
		}

		if ( ! $columns )
			$columns = apply_filters( 'themeblvd_default_grid_columns', 3 );

		if ( ! $rows )
			$rows = apply_filters( 'themeblvd_default_grid_rows', 4 );

		// Posts per page, used for the grid display and not
		// the actual main query of posts.
		$posts_per_page = intval($columns)*intval($rows);

		// Thumbnail size
		$size = themeblvd_grid_class( $columns );

		if ( is_home() ) {

			$crop = apply_filters( 'themeblvd_index_grid_crop_size', $size );

		} elseif ( is_archive() || is_search() ) {

			$crop = apply_filters( 'themeblvd_archive_grid_crop_size', $size );

		} elseif ( is_page_template( 'template_grid.php' ) ) {

			$crop = get_post_meta( $this->config['id'], 'crop', true );

			if ( ! $crop )
				$crop = apply_filters( 'themeblvd_template_grid_crop_size', $size );
		}

		if ( empty( $crop ) )
			$crop = $size;

		// Setup attributes
		$atts = array(
			'columns' 			=> $columns,
			'rows' 				=> $rows,
			'posts_per_page' 	=> $posts_per_page,
			'counter'			=> 0,
			'size'				=> $size,
			'crop'				=> $crop 			// Will be equal to "size" if not overridden
		);

		return apply_filters( 'themeblvd_grid_atts', $atts );

	}

	/**
	 * Set template attributes.
	 *
	 * @since 2.3.0
	 *
	 * @param array $atts Attributes to be merged with global attributes
	 * @param bool $flush Whether or not to flush previous attributes before merging
	 * @return array $atts Updated attributes array
	 */
	public function set_atts( $atts, $flush = false ) {

		if ( $flush )
			$this->atts = $atts;
		else
			$this->atts = array_merge( $this->atts, $atts );

		return $this->atts;
	}

	/**
	 * Set template single attribute.
	 *
	 * @since 2.3.0
	 *
	 * @param string $key Key in $atts array to modify
	 * @param mixed $value New value
	 * @return mixed New value
	 */
	public function set_att( $key, $value ) {
		$this->atts[$key] = $value;
		return $this->atts[$key];
	}

	/**
	 * Remove single attribute.
	 *
	 * @since 2.3.0
	 *
	 * @param string $key Key in $atts array to remove
	 */
	public function remove_att( $key ) {
		unset( $this->atts[$key] );
	}

	/**
	 * Get single attribute.
	 *
	 * @since 2.3.0
	 *
	 * @param string $key Key in $atts array to retrieve
	 * @return mixed Value of attribute
	 */
	public function get_att( $key ) {

		if ( isset( $this->atts[$key] ) )
			return $this->atts[$key];

		return null;
	}

}