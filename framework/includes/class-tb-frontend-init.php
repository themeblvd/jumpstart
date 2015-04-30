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
	private $mode = 'blog';

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

		if ( self::$instance == null ) {
            self::$instance = new self;
        }

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
		add_action( 'wp_head', array( $this, 'debug' ) );
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

			// Blog (default content.php)
			'blog'				=> 'blog', 			// content-blog.php (doesn't exist by default)
			'blog_paginated'	=> 'blog', 			// content-blog.php (doesn't exist by default)
			'single'			=> 'single',		// content-single.php (doesn't exist by default)

			// Post List
			'list'				=> 'list',			// content-list.php
			'list_paginated'	=> 'list',			// content-list.php
			'list_mini'			=> 'mini-list',		// content-mini-list.php

			// Post Grid
			'grid' 				=> 'grid',			// content-grid.php
			'grid_paginated' 	=> 'grid',			// content-grid.php
			'grid_slider' 		=> 'grid',			// content-grid.php
			'grid_mini'			=> 'mini-grid',		// content-mini-grid.php

			// Showcase
			'showcase' 			=> 'showcase',		// content-showcase.php
			'showcase_paginated'=> 'showcase',		// content-showcase.php

			// Pages
			'page' 				=> 'page',			// content-page.php
			'naked_page' 		=> 'page',			// content-page.php
			'404'				=> '404',			// content-404.php

			// Search
			'search'			=> 'page-search',	// content-page-search.php
			'search_results'	=> 'search-result',	// content-search-result.php

			// Single attachment
			'attachment' 		=> 'attachment'		// content-attachment.php

		));
	}

	/**
	 * Set mode to either grid or list.
	 *
	 * @since 2.3.0
	 */
	public function set_mode( $q ) {

		if ( ! $q->is_main_query() ) {
			return;
		}

		$this->mode = '';

		if ( $q->is_home() ) {

			$this->mode = 'blog';

		} else if ( $q->is_archive() ) {

			if ( $q->is_author() ) {

				if ( get_query_var('author_name') ) {
					$user = get_user_by('slug', get_query_var('author_name'));
					$user_id = $user->ID;
				} else if ( get_query_var('author') ) {
					$user_id = get_query_var('author');
				}

				if ( ! empty( $user_id ) ) {
					$this->mode = get_user_meta( $user_id, '_tb_archive_mode', true );
				}

			} else if ( $q->is_category() ) {

				$this->mode = themeblvd_get_tax_meta( 'category', get_query_var('category_name'), 'mode', 'default' );

			} else if ( $q->is_tag() ) {

				$this->mode = themeblvd_get_tax_meta( 'post_tag', get_query_var('tag'), 'mode', 'default' );

			}

			if ( ! $this->mode || $this->mode == 'default' ) {
				$this->mode = themeblvd_get_option( 'archive_mode', null, 'blog' );
			}

		} else if ( $q->is_page() ) {

			$page_id = 0;

			if ( $q->get('page_id') ) { // most likely static frontpage
				$page_id = $q->get('page_id');
			} else if ( isset($q->queried_object_id) ) { // page in standard context
				$page_id = $q->queried_object_id;
			}

			$template = get_post_meta( $page_id, '_wp_page_template', true );

			switch ( $template ) {
				case 'template_blog.php' :
					$this->mode = 'blog';
					break;
				case 'template_list.php' :
					$this->mode = 'list';
					break;
				case 'template_grid.php' :
					$this->mode = 'grid';
					break;
				case 'template_showcase.php' :
					$this->mode = 'showcase';
			}

		}

		// Allow manual override.
		$this->mode = apply_filters( 'themeblvd_theme_mode_override', $this->mode, $q );
	}

	/**
	 * Set primary configuration array.
	 *
	 * @since 2.3.0
	 */
	public function set_config() {

		global $post;

		$this->config = array(
			'id'						=> 0,			// global $post->ID that can be accessed anywhere
			'mode'						=> $this->mode,	// Mode used for displaying posts - blog, list, or grid
			'builder'					=> false,		// ID of current custom layout if not false
			'builder_post_id'			=> 0,			// Numerical Post ID of tb_layout custom post
			'bottom_builder'			=> false,		// ID of current custom layout for footer if not false
			'bottom_builder_post_id'	=> 0,			// Numerical Post ID of tb_layout custom post
			'sidebar_layout'			=> '',			// Sidebar layout
			'featured'					=> array(),		// Classes for featured area, if empty area won't show
			'featured_below'			=> array(),		// Classes for featured below area, if empty area won't show
			'sidebars'					=> array(), 	// Array of sidbar ID's for all corresponding locations
			'sticky'					=> false,		// Whether to include sticky header
			'suck_up'					=> false,		// Whether to suck content up into transparent header
			'logo_height'				=> 0,			// Height of logo
			'top_height'				=> 0,			// If using suck up, figure out the height of the header
			'top_height_tablet'			=> 0,			// If using suck up, figure out the height of the header
			'top'						=> true,		// Whether to show entire #top section (header)
			'bottom'					=> true, 		// Whether to show entire #bottom section (bottom)
			'banner'					=> false		// Whether to show featured banner
		);

		/*------------------------------------------------------*/
		/* Primary Post ID
		/*------------------------------------------------------*/

		// Store the ID of the original $post object in case
		// we modify the main query or need to ever access it.

		$this->config['id'] = 0;

		if ( is_singular() && is_a($post, 'WP_Post') ) {
			$this->config['id'] = $post->ID;
		}

		/*------------------------------------------------------*/
		/* Custom Layout, Builder Name/ID
		/*------------------------------------------------------*/

		if ( defined( 'TB_BUILDER_PLUGIN_VERSION' ) ) {

			$layout = '';
			$sync_id = 0;

			// Custom Layout on static page
			if ( is_page_template( 'template_builder.php' ) ) {
				if ( post_password_required() || ( 'private' == get_post_status() && ! current_user_can( 'edit_posts' ) ) ) {

					// Password is currently required and so
					// the custom layout doesn't get used.
					$layout = 'wp-private';

				} else {

					$layout = get_post_meta( $this->config['id'], '_tb_custom_layout', true );

					if ( $layout ) {

						$sync_id = themeblvd_post_id_by_name( $layout, 'tb_layout' );

						if ( ! $sync_id ) {
							$layout = 'error';
						}

					} else {

						$layout = $post->post_name; // Will pull from current page's meta data

						if ( ! $layout ) {
							$layout = 'current';
						}
					}

				}
			}

			if ( $layout ) {

				$this->config['builder'] = $layout;

				if ( $sync_id ) {
					$this->config['builder_post_id'] = $sync_id;
				} else {
					$this->config['builder_post_id'] = $post->ID;
				}

			}

			// Custom template for footer
			if ( themeblvd_supports( 'display', 'footer_sync' ) ) {

				$footer_sync = themeblvd_get_option('footer_sync');

				if ( $footer_sync ) {

					$bottom_template = themeblvd_get_option( 'footer_template' );

					if ( $bottom_template ) {
						$bottom_template_id = themeblvd_post_id_by_name( $bottom_template, 'tb_layout' );
					}
				}

				if ( ! empty( $bottom_template_id ) ) {
					$this->config['bottom_builder'] = $bottom_template;
					$this->config['bottom_builder_post_id'] = $bottom_template_id;
				}

			}
		}

		/*------------------------------------------------------*/
		/* Featured Area
		/*------------------------------------------------------*/

		// For the featured area above or below the content to be
		// enabled, it must contain at least one CSS class or else
		// it won't be displayed for the current page.

		if ( is_page_template( 'template_list.php' ) ) {

			if ( themeblvd_supports( 'featured', 'blog' ) ) {
				$this->config['featured'][] = 'has_blog_featured';
			}

			if ( themeblvd_supports( 'featured_below', 'blog' ) ) {
				$this->config['featured_below'][] = 'has_blog_featured_below';
			}

		}

		if ( is_page_template( 'template_grid.php' ) ) {

			if ( themeblvd_supports( 'featured', 'grid' ) ) {
				$this->config['featured'][] = 'has_grid_featured';
			}

			if ( themeblvd_supports( 'featured_below', 'grid' ) ) {
				$this->config['featured_below'][] = 'has_grid_featured_below';
			}

		}

		if ( is_archive() || is_search() ) {

			if ( themeblvd_supports( 'featured', 'archive' ) ) {
				$this->config['featured'][] = 'has_archive_featured';
			}

			if ( themeblvd_supports( 'featured_below', 'archive' ) ) {
				$this->config['featured_below'][] = 'has_archive_featured_below';
			}

		}

		if ( is_page() && ! is_page_template( 'template_builder.php' ) ) {

			if ( themeblvd_supports( 'featured', 'page' ) ) {
				$this->config['featured'][] = 'has_page_featured';
			}

			if ( themeblvd_supports( 'featured_below', 'page' ) ) {
				$this->config['featured_below'][] = 'has_page_featured_below';
			}

		}

		if ( is_single() ) {

			if ( themeblvd_supports( 'featured', 'single' ) ) {
				$this->config['featured'][] = 'has_single_featured';
			}

			if ( themeblvd_supports( 'featured_below', 'single' ) ) {
				$this->config['featured_below'][] = 'has_single_featured_below';
			}

		}

		/*------------------------------------------------------*/
		/* Sidebar Layout
		/*------------------------------------------------------*/

		// The sidebar layout is how the left and right sidebar will
		// be displayed on the current page.

		if ( is_page_template('template_builder.php') ) {
			$this->config['sidebar_layout'] = 'full_width';
		}

		if ( ! $this->config['sidebar_layout'] && is_archive() ) {

			if ( is_category() ) {
				$this->config['sidebar_layout'] = themeblvd_get_tax_meta( 'category', get_query_var('category_name'), 'sidebar_layout', 'default' );
			} else if ( is_tag() ) {
				$this->config['sidebar_layout'] = themeblvd_get_tax_meta( 'post_tag', get_query_var('tag'), 'sidebar_layout', 'default' );
			} else if ( is_author() ) {

				if ( get_query_var('author_name') ) {
					$user = get_user_by('slug', get_query_var('author_name'));
					$user_id = $user->ID;
				} else if ( get_query_var('author') ) {
					$user_id = get_query_var('author');
				}

				if ( ! empty( $user_id ) ) {
					$this->config['sidebar_layout']= get_user_meta( $user_id, '_tb_sidebar_layout', true );
				}
			}

			if ( ! $this->config['sidebar_layout'] || $this->config['sidebar_layout'] == 'default' ) {
				$this->config['sidebar_layout'] = themeblvd_get_option('archive_sidebar_layout', null, 'default');
			}

		}

		if ( ! $this->config['sidebar_layout'] && ( is_page() || is_single() ) ) {

			$this->config['sidebar_layout'] = get_post_meta( $this->config['id'], '_tb_sidebar_layout', true );

			if ( ! $this->config['sidebar_layout'] || $this->config['sidebar_layout'] == 'default' ) {
				if ( is_page() ) {
					$this->config['sidebar_layout'] = themeblvd_get_option( 'page_sidebar_layout' );
				} else {
					$this->config['sidebar_layout'] = themeblvd_get_option( 'single_sidebar_layout' );
				}
			}
		}

		if ( ! $this->config['sidebar_layout'] || $this->config['sidebar_layout'] == 'default' ) {
			$this->config['sidebar_layout']= themeblvd_get_option( 'sidebar_layout' );
		}

		if ( ! $this->config['sidebar_layout'] ) {
			$this->config['sidebar_layout']= apply_filters( 'themeblvd_default_sidebar_layout', 'sidebar_right', $this->config['sidebar_layout']); // Keeping for backwards compatibility, although is redundant with next filter.
		}

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
					if ( $sidebar_id != $location_id ) {
						$this->config['sidebars'][$location_id]['error'] = true;
					}

	    		} else {

	    			// Custom or not, we need to tell the user if a
	    			// fixed sidebar is empty.
	    			$this->config['sidebars'][$location_id]['error'] = true;

				}
			}

		}

		/*------------------------------------------------------*/
		/* Sticky Header
		/*------------------------------------------------------*/

		if ( themeblvd_supports('display', 'sticky') && themeblvd_get_option('sticky') != 'hide' ) {
			$this->config['sticky'] = true;
		}

		/*------------------------------------------------------*/
		/* Theme Layout
		/*------------------------------------------------------*/

		$header = get_post_meta( $this->config['id'], '_tb_layout_header', true );

		// Logo height
		$logo = themeblvd_get_option('trans_logo');
		$logo_height = 65;

		if ( $logo && $logo['type'] == 'image' && ! empty( $logo['image_height'] ) ) {
			$logo_height = intval($logo['image_height']);
		}

		$this->config['logo_height'] = $logo_height;

		if ( $header == 'suck_up' && themeblvd_supports('display', 'suck_up') ) {

			$this->config['suck_up'] = true;

			// Desktop

			// The theme's base height for the header before the
			// user's logo height is dynamically added
			$addend = 90; // 20px (above logo) + 20px (below logo) + 50px (menu)

			if ( themeblvd_has_header_info() ) {
				$addend += 48;
			}

			$addend = apply_filters('themeblvd_top_height_addend', $addend, 'desktop');

			$this->config['top_height'] = apply_filters('themeblvd_top_height', $addend+$logo_height, 'desktop');

			// Tablet

			// The theme's base height for the header before the
			// user's logo height is dynamically added
			$addend = 40; // 20px (above logo) + 20px (below logo)

			if ( themeblvd_has_header_info() ) {
				$addend += 48;
			}

			$addend = apply_filters('themeblvd_top_height_addend', $addend, 'tablet');

			$this->config['top_height_tablet'] = apply_filters('themeblvd_top_height', $addend+$logo_height, 'tablet');

		} else if ( $header == 'hide' && themeblvd_supports('display', 'hide_top') ) {
			$this->config['top'] = false;
		}

		if ( themeblvd_supports('display', 'hide_bottom') && get_post_meta( $this->config['id'], '_tb_layout_footer', true ) == 'hide' ) {
			$this->config['bottom'] = false;
		}

		/*------------------------------------------------------*/
		/* Banner
		/*------------------------------------------------------*/

		if ( is_singular() && themeblvd_supports('display', 'banner') ) {

			$banner = get_post_meta( $this->config['id'], '_tb_banner', true );

			if ( $banner && ! empty($banner['bg_type']) && $banner['bg_type'] != 'none' ) {
				$this->config['banner'] = $banner;
			}
		}

		/*------------------------------------------------------*/
		/* Extend
		/*------------------------------------------------------*/

		$this->config = apply_filters( 'themeblvd_frontend_config', $this->config );

		// DEBUG:
		// echo '<pre>'; print_r($this->config); echo '</pre>';

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

		if ( ! $part ) {
			return $this->template_parts;
		}

		if ( ! isset( $this->template_parts[$part] ) ) {
			return null;
		}

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

		if ( ! $key ) {
			return $this->config;
		}

		if ( $secondary && isset( $this->config[$key][$secondary] ) ) {
			return $this->config[$key][$secondary];
		}

		if ( isset( $this->config[$key] ) ) {
			return $this->config[$key];
		}

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
	/* Methods, additional actions & filters
	/*--------------------------------------------*/

	/**
	 * Output theme debug info to wp_head.
	 *
	 * @since 2.5.0
	 */
	public function debug() {

		$child = false;
		$template = get_template();
		$stylesheet = get_stylesheet();
		$parent = wp_get_theme( $template );

		echo "\n<!--\n";
		echo "Debug Info\n\n";

		if ( $template == $stylesheet ) {
			printf("Theme: %s\n", $parent->get('Name'));
			printf("Directory: %s\n", $stylesheet);
		} else {
			$child = wp_get_theme( $stylesheet );
			printf("Child Theme: %s\n", $child->get('Name'));
			printf("Child Directory: %s\n", $stylesheet);
			printf("Parent Theme: %s\n", $parent->get('Name'));
			printf("Parent Directory: %s\n", $template);
		}

		printf("Version: %s\n", $parent->get('Version'));

		printf("TB Framework: %s\n", TB_FRAMEWORK_VERSION);

		if ( defined('TB_BUILDER_PLUGIN_VERSION') ) {
			printf("TB Builder: %s\n", TB_BUILDER_PLUGIN_VERSION);
		}

		if ( defined('TB_SHORTCODES_PLUGIN_VERSION') ) {
			printf("TB Shortcodes: %s\n", TB_SHORTCODES_PLUGIN_VERSION);
		}

		if ( defined('TB_SIDEBARS_PLUGIN_VERSION') ) {
			printf("TB Widget Areas: %s\n", TB_SIDEBARS_PLUGIN_VERSION);
		}

		if ( defined('TB_WIDGET_PACK_PLUGIN_VERSION') ) {
			printf("TB Widget Pack: %s\n", TB_WIDGET_PACK_PLUGIN_VERSION);
		}

		if ( defined('TB_SLIDERS_PLUGIN_VERSION') ) {
			printf("TB Sliders: %s\n", TB_SLIDERS_PLUGIN_VERSION);
		}

		if ( defined('TB_PORTFOLIOS_PLUGIN_VERSION') ) {
			printf("TB Portfolios: %s\n", TB_PORTFOLIOS_PLUGIN_VERSION);
		}

		if ( isset($GLOBALS['wp_version']) ) {
			printf("WordPress: %s\n", $GLOBALS['wp_version']);
		}

		echo "-->\n";

	}

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
		if ( $this->config['featured'] ) {
			$classes[] = 'show-featured-area';
		} else {
			$classes[] = 'hide-featured-area';
		}

		// Featured Area (below)
		if ( $this->config['featured_below'] ) {
			$classes[] = 'show-featured-area-below';
		} else {
			$classes[] = 'hide-featured-area-above';
		}

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

		// Single posts
		if ( is_single() ) {

			// Featured images
			$thumbs = true;

			if ( themeblvd_get_option( 'single_thumbs', null, 'full' ) == 'hide' ) {
				$thumbs = false;
			}

			if ( get_post_meta( $this->config['id'], '_tb_thumb', true ) == 'hide' ) {
				$thumbs = false;
			} else if ( get_post_meta( $this->config['id'], '_tb_thumb', true ) == 'full' ) {
				$thumbs = true;
			}

			// Meta information (i.e. date posted, author)
			$show_meta = true;

			if ( themeblvd_get_option( 'single_meta', null, 'show' ) == 'hide' ) {
				$show_meta = false;
			}

			if ( get_post_meta( $this->config['id'], '_tb_meta', true ) == 'hide' ) {
				$show_meta = false;
			} else if ( get_post_meta( $this->config['id'], '_tb_meta', true ) == 'show' ) {
				$show_meta = true;
			}

			// Sub meta information (i.e. tags, categories)
			$show_sub_meta = true;

			if ( themeblvd_get_option( 'single_sub_meta', null, 'show' ) == 'hide' ) {
				$show_sub_meta = false;
			}

			if ( get_post_meta( $this->config['id'], '_tb_sub_meta', true ) == 'hide' ) {
				$show_sub_meta = false;
			} else if ( get_post_meta( $this->config['id'], '_tb_sub_meta', true ) == 'show' ) {
				$show_meta = true;
			}

			$this->atts = apply_filters( 'themeblvd_single_atts', array(
				'location'		=> 'single',
				'content'		=> 'content', // We don't want excerpts to show on a single post!
				'thumbs'		=> $thumbs,
				'show_meta' 	=> $show_meta,
				'show_sub_meta' => $show_sub_meta
			));

		}

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

		if ( $flush ) {
			$this->atts = $atts;
		} else {
			$this->atts = array_merge( $this->atts, $atts );
		}

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

		if ( isset( $this->atts[$key] ) ) {
			return $this->atts[$key];
		}

		return null;
	}

}
