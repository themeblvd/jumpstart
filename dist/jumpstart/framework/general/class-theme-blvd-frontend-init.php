<?php
/**
 * Frontend Initialization
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.5.0
 */

/**
 * Initializes the theme's frontend.
 *
 * This class follows the singleton pattern,
 * meaning it can only be instantiated in
 * one instance.
 *
 * @since Theme_Blvd 2.5.0
 */
class Theme_Blvd_Frontend_Init {

	/**
	 * A single instance of this class.
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var Theme_Blvd_Frontend_Init
	 */
	private static $instance = null;

	/**
	 * Template parts for get_template_part().
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var array
	 */
	private $template_parts = array();

	/**
	 * Mode of framework, `list` or `grid`.
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var string
	 */
	private $mode = 'blog';

	/**
	 * Configuration array for page being loaded.
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var array
	 */
	private $config = array();

	/**
	 * Store template attributes.
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var array
	 */
	private $atts = array();

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @return Theme_Blvd_Frontend_Init A single instance of this class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {

			self::$instance = new self;

		}

		return self::$instance;

	}

	/**
	 * Constructor. Hook everything in.
	 *
	 * @since Theme_Blvd 2.3.0
	 */
	public function __construct() {

		add_action( 'pre_get_posts', array( $this, 'set_template_parts' ), 5 );

		add_action( 'pre_get_posts', array( $this, 'set_mode' ), 5 );

		add_action( 'wp', array( $this, 'set_config' ), 5 );

		add_action( 'wp', array( $this, 'atts_init' ) );

		add_action( 'wp_head', array( $this, 'debug' ) );

		add_filter( 'body_class', array( $this, 'body_class' ) );

	}

	/**
	 * Set template parts for framework when using
	 * get_template_part() in theme's post loops.
	 *
	 * i.e. get_template_part( 'content', {$part} );
	 *
	 * This method is hooked to:
	 * 1. `pre_get_posts` - 5
	 *
	 * @since Theme_Blvd 2.3.0
	 */
	public function set_template_parts() {

		/**
		 * Filters the template parts used for different
		 * scenarios, outside of the loop.
		 *
		 * This filter fires when the website initially
		 * loads on the frontend, before anything is queried.
		 *
		 * So if you're looking to change template parts based
		 * on someth happenning within the loop, like by post
		 * type, post format, etc, use the filter
		 * `themeblvd_template_part` instead.
		 *
		 * Array Structure: This array is made up of key => value
		 * pairs, where the key is the theme scenario and the
		 * value cooresponds to the template file from the theme,
		 * which is used.
		 *
		 * @since Theme_Blvd 2.2.0
		 *
		 * @param array Template part slugs.
		 */
		$this->template_parts = apply_filters( 'themeblvd_template_parts', array(

			// Set header template parts.
			'head'               => 'head',          // content-head.php
			'header'             => 'header',        // content-header.php

			// Set footer template parts.
			'footer'             => 'footer',        // content-footer.php

			// Set blog template parts.
			'blog'               => 'blog',          // content-blog.php
			'blog_paginated'     => 'blog',          // content-blog.php
			'single'             => 'single',        // content-single.php (doesn't exist by default)
			'featured'           => 'featured',      // content-featured.php
			'featured-wc'        => 'featured-wc',   // content-featured-wc.php

			// Set post list template parts.
			'list'               => 'list',          // content-list.php
			'list_paginated'     => 'list',          // content-list.php
			'list_small'         => 'small-list',    // content-mini-list.php
			'list_mini'          => 'mini-list',     // content-mini-list.php

			// Set post grid template parts.
			'grid'               => 'grid',          // content-grid.php
			'grid_paginated'     => 'grid',          // content-grid.php
			'grid_slider'        => 'grid',          // content-grid.php
			'grid_small'         => 'small-grid',    // content-small-grid.php
			'grid_mini'          => 'mini-grid',     // content-mini-grid.php

			// Set post showcase template parts.
			'showcase'           => 'showcase',      // content-showcase.php
			'showcase_paginated' => 'showcase',      // content-showcase.php

			// Set page template parts.
			'page'               => 'page',          // content-page.php
			'naked_page'         => 'page',          // content-page.php
			'404'                => '404',           // content-404.php

			// Set search result template parts.
			'search'             => 'page-search',   // content-page-search.php
			'search_results'     => 'search-result', // content-search-result.php

			// Set single attachment template parts.
			'attachment'         => 'attachment',    // content-attachment.php

			// Set side and mobile panel template parts.
			'panel'              => 'panel',         // content-panel.php

		));

	}

	/**
	 * Set mode to either grid or list.
	 *
	 * This method is hooked to:
	 * 1. `pre_get_posts` - 5
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param WP_Query $q The WP_Query instance (passed by reference).
	 */
	public function set_mode( $q ) {

		if ( ! $q->is_main_query() ) {

			return;

		}

		$this->mode = '';

		if ( $q->is_home() ) {

			$this->mode = themeblvd_get_option( 'home_mode', null, 'blog' );

			/**
			 * Filters the post display mode of the posts
			 * homepage.
			 *
			 * Note: This filter is mainly for backwards
			 * compatibility. It existed before the end-user
			 * theme option did.
			 *
			 * @since Theme_Blvd 2.3.0
			 *
			 * @param string $mode Post display mode, `blog`, `list`, `grid` or `showcase`.
			 */
			$this->mode = apply_filters( 'themeblvd_posts_page_mode', $this->mode );

		} elseif ( $q->is_archive() ) {

			if ( $q->is_author() ) {

				if ( get_query_var( 'author_name' ) ) {

					$user = get_user_by( 'slug', get_query_var( 'author_name' ) );

					if ( $user ) {

						$user_id = $user->ID;

					}
				} elseif ( get_query_var( 'author' ) ) {

					$user_id = get_query_var( 'author' );

				}

				if ( ! empty( $user_id ) ) {

					$this->mode = get_user_meta( $user_id, '_tb_archive_mode', true );

				}
			} elseif ( $q->is_category() ) {

				$this->mode = themeblvd_get_tax_meta( 'category', get_query_var( 'category_name' ), 'mode', 'default' );

			} elseif ( $q->is_tag() ) {

				$this->mode = themeblvd_get_tax_meta( 'post_tag', get_query_var( 'tag' ), 'mode', 'default' );

			}

			if ( ! $this->mode || 'default' === $this->mode ) {

				$this->mode = themeblvd_get_option( 'archive_mode', null, 'blog' );

			}
		} elseif ( $q->is_page() ) {

			$page_id = 0;

			if ( $q->get( 'page_id' ) ) { // Most likely static frontpage.

				$page_id = $q->get( 'page_id' );

			} elseif ( isset( $q->queried_object_id ) ) { // Page in standard context.

				$page_id = $q->queried_object_id;

			}

			$template = get_post_meta( $page_id, '_wp_page_template', true );

			switch ( $template ) {

				case 'template_blog.php':
					$this->mode = 'blog';
					break;

				case 'template_list.php':
					$this->mode = 'list';
					break;

				case 'template_grid.php':
					$this->mode = 'grid';
					break;

				case 'template_showcase.php':
					$this->mode = 'showcase';

			}
		}

		/**
		 * Filters the post display mode of for all
		 * scenarios.
		 *
		 * @since Theme_Blvd 2.3.0
		 *
		 * @param string   $mode Post display mode, `blog`, `list`, `grid` or `showcase`.
		 * @param WP_Query $q    The WP_Query instance (passed by reference).
		 */
		$this->mode = apply_filters( 'themeblvd_theme_mode_override', $this->mode, $q );

	}

	/**
	 * Set primary frontend configuration.
	 *
	 * This method is hooked to:
	 * 1. `wp` - 5
	 *
	 * @since Theme_Blvd 2.3.0
	 */
	public function set_config() {

		global $post;

		// Set initial structure of array.
		$this->config = array(
			'id'                     => 0,
			'mode'                   => $this->mode,
			'builder'                => false,
			'builder_post_id'        => 0,
			'bottom_builder'         => false,
			'bottom_builder_post_id' => 0,
			'sidebar_layout'         => '',
			'sidebars'               => array(),
			'sticky'                 => false,
			'suck_up'                => false,
			'logo_height'            => 0,
			'top_height'             => 0,
			'top_height_tablet'      => 0,
			'top'                    => true,
			'bottom'                 => true,
		);

		/*
		 * Set the primary post ID.
		 *
		 * Store the ID of the original $post object in case
 		 * we modify the main query or need to ever access it,
		 * from any random scenarios.
		 */
		$this->config['id'] = 0;

		if ( is_singular() && is_a( $post, 'WP_Post' ) ) {
			$this->config['id'] = $post->ID;
		}

		/**
		 * Filters the theme's stored primary post ID.
		 *
		 * @since Theme_Blvd 2.2.0
		 *
		 * @param int $id Current primary post ID.
		 */
		$this->config['id'] = apply_filters( 'themeblvd_frontend_config_post_id', $this->config['id'] );

		/*
		 * Set the custom layout slug and ID.
		 *
		 * If using the layout builder plugin and this is
		 * a page implementing a custom layout, this will
		 * store the post ID and slug that has the layout
		 * data stored.
		 *
		 * If the custom layout is built directly into a,
		 * page the data will exist with that page. However,
		 * if the page is synced with a template, the layout
		 * data will exist with that tb_layout post
		 * representing the template.
		 */
		if ( defined( 'TB_BUILDER_PLUGIN_VERSION' ) ) {

			$layout = '';

			$sync_id = 0;

			/*
			 * Is this a page with the "Custom Layout" page
			 * template applied?
			 *
			 * Currently this is the only default way to attach
			 * a custom layout. The other way would be to manually
			 * filter `themeblvd_global_config`.
			 *
			 * Note: The checks to verify that is_search() and
			 * is_archive() are FALSE fixes a bug with
			 * is_page_template() we noticed in WordPress 4.7.
			 */
			if ( is_page_template( 'template_builder.php' ) && ! is_search() && ! is_archive() ) {

				if ( post_password_required() || ( 'private' == get_post_status() && ! current_user_can( 'edit_posts' ) ) ) {

					/*
					 * Page is password protected.
					 *
					 * Because a password is required to view the
					 * content of the page, we will not show the
					 * custom layout.
					 */
					$layout = 'wp-private';

				} else {

					$layout = get_post_meta( $this->config['id'], '_tb_custom_layout', true );

					if ( $layout ) {

						/*
						 * Use template sync.
						 *
						 * The user has sycned the page with a template;
						 * so we'll pulling the data from the associated
						 * tb_layout post.
						 */
						$sync_id = themeblvd_post_id_by_name( $layout, 'tb_layout' );

						if ( ! $sync_id ) {

							$layout = 'error';

						}
					} else {

						/*
						 * Use page data.
						 *
						 * The user has built the layout directly into the
						 * page; so we'll be pulling the data from current
						 * page in the loop.
						 */
						$layout = $post->post_name;

						if ( ! $layout ) {

							$layout = 'current';

						}
					}
				}
			}

			// We have the slug; now store the actual page or post ID.
			if ( $layout ) {

				$this->config['builder'] = $layout;

				if ( $sync_id ) {

					$this->config['builder_post_id'] = $sync_id;

				} else {

					$this->config['builder_post_id'] = $post->ID;

				}
			}

			/*
			 * Sync footer with layout builder template.
			 *
			 * This is an optional eature the user can set up from
			 * Appearance > Theme Options > Layout > Footer.
			 */
			if ( themeblvd_supports( 'display', 'footer_sync' ) ) {

				$footer_sync = themeblvd_get_option( 'footer_sync' );

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

		/*
		 * Determine the sidebar layout.
		 *
		 * The sidebar layout is how the left and right sidebar
 		 * will be displayed on the current page.
		 *
		 * Note: The checks to verify that is_search() and
		 * is_archive() are FALSE fixes a bug with
		 * is_page_template() we noticed in WordPress 4.7.
		 */

		/*
		 * If using a custom layout, set sidebar layout to
		 * `full_width`, as a formality. No sidebar layout
		 * used here.
		 */
		if ( is_page_template( 'template_builder.php' ) && ! is_search() && ! is_archive() ) {

			$this->config['sidebar_layout'] = 'full_width';

		}

		// Determine sidebar layout, if this is an archive.
		if ( ! $this->config['sidebar_layout'] && is_archive() ) {

			if ( is_category() ) {

				$this->config['sidebar_layout'] = themeblvd_get_tax_meta(
					'category',
					get_query_var( 'category_name' ),
					'sidebar_layout',
					'default'
				);

			} elseif ( is_tag() ) {

				$this->config['sidebar_layout'] = themeblvd_get_tax_meta(
					'post_tag',
					get_query_var( 'tag' ),
					'sidebar_layout',
					'default'
				);

			} elseif ( is_author() ) {

				if ( get_query_var( 'author_name' ) ) {

					$user = get_user_by( 'slug', get_query_var( 'author_name' ) );

					$user_id = $user->ID;

				} elseif ( get_query_var( 'author' ) ) {

					$user_id = get_query_var( 'author' );

				}

				if ( ! empty( $user_id ) ) {

					$this->config['sidebar_layout'] = get_user_meta(
						$user_id,
						'_tb_sidebar_layout',
						true
					);

				}
			}

			if ( ! $this->config['sidebar_layout'] || 'default' === $this->config['sidebar_layout'] ) {

				$this->config['sidebar_layout'] = themeblvd_get_option( 'archive_sidebar_layout', null, 'default' );

			}
		}

		// Determine sidebar layout, if this is a page or post.
		if ( ! $this->config['sidebar_layout'] && ( is_page() || is_single() ) ) {

			$this->config['sidebar_layout'] = get_post_meta( $this->config['id'], '_tb_sidebar_layout', true );

			if ( ! $this->config['sidebar_layout'] || 'default' === $this->config['sidebar_layout'] ) {

				if ( is_page() ) {

					$this->config['sidebar_layout'] = themeblvd_get_option( 'page_sidebar_layout' );

				} else {

					$this->config['sidebar_layout'] = themeblvd_get_option( 'single_sidebar_layout' );

				}
			}
		}

		if ( ! $this->config['sidebar_layout'] || 'default' === $this->config['sidebar_layout'] ) {

			$this->config['sidebar_layout'] = themeblvd_get_option( 'sidebar_layout' );

		}

		if ( ! $this->config['sidebar_layout'] ) {

			/**
			 * Filters the default sidebar layout.
			 *
			 * This only gets used if a sidebar layout wasn't
			 * set yet by the theme.
			 *
			 * Note: This filter is a little redundant with the
			 * following `themeblvd_sidebar_layout` filter, but
			 * it was created before that one existed.
			 *
			 * @since Theme_Blvd 2.0.0
			 *
			 * @param string $sidebar_layout Sidebar layout, like `sidebar_right`.
			 */
			$this->config['sidebar_layout'] = apply_filters(
				'themeblvd_default_sidebar_layout',
				'sidebar_right',
				$this->config['sidebar_layout']
			);

		}

		/**
		 * Filters the sidebar layout for any given
		 * WordPress-generated page.
		 *
		 * Possible sidebar layouts:
		 *
		 * 1. `full_width`
		 * 2. `sidebar_left`
		 * 3. `sidebar_right`
		 * 4. `double_sidebar`
		 * 5. `double_sidebar_left`
		 * 6. `double_sidebar_right`
		 *
		 * @since Theme_Blvd 2.3.0
		 *
		 * @param string $sidebar_layout Sidebar layout, like `sidebar_right`.
		 */
		$this->config['sidebar_layout'] = apply_filters(
			'themeblvd_sidebar_layout',
			$this->config['sidebar_layout']
		);

		/*
		 * Determine which sidebars will display in each
		 * location.
		 *
		 * For each location, this will be the framework's
		 * corresponding default sidebar, unless something
		 * else intervenes like the Theme Blvd Widget Areas
		 * plugin, for example.
		 */
		$locations = themeblvd_get_sidebar_locations();

		$custom_sidebars = null;

		$sidebar_overrides = null;

		if ( defined( 'TB_SIDEBARS_PLUGIN_VERSION' ) ) {

			$custom_sidebars = get_posts( array(
				'post_type'   => 'tb_sidebar',
				'numberposts' => -1,
			));

			$sidebar_overrides = get_post_meta(
				$this->config['id'],
				'_tb_sidebars',
				true
			);

		}

		foreach ( $locations as $location_id => $default_sidebar ) {

			/*
			 * By default, the sidebar ID will match the ID of the
			 * current location.
			 */
			$sidebar_id = $location_id;

			/**
			 * Filters the actual sidebar ID, to display in
			 * the current location.
			 *
			 * Note: This filter is currently what's used by the
			 * Theme Blvd Widget Areas plugin to apply custom
			 * sidebars.
			 *
			 * @since Theme_Blvd 2.0.0
			 *
			 * @param string $sidebar_id        Sidebar ID, like `my-sidebar`.
			 * @param array  $custom_sidebars   Array of WP_Post objects for tb_sidebar posts.
			 * @param array  $sidebar_overrides Sidebar overrides for a specific page or post.
			 * @param int    $id                Current post ID, if this is a single page or post.
			 */
			$sidebar_id = apply_filters( 'themeblvd_custom_sidebar_id', $sidebar_id, $custom_sidebars, $sidebar_overrides, $this->config['id'] );

			$this->config['sidebars'][ $location_id ]['id'] = $sidebar_id;

			$this->config['sidebars'][ $location_id ]['error'] = false;

			// Check if the sidebar contains widgets.
			if ( ! is_active_sidebar( $sidebar_id ) ) {

				if ( 'collapsible' === $default_sidebar['type'] ) {

					/*
					 * For "collapsible" widget area, we only show an
					 * error to the user if a custom sidebar was
					 * specifically applied. Otherwise it just, well...
					 * collapses.
					 */
					if ( $sidebar_id != $location_id ) {

						$this->config['sidebars'][ $location_id ]['error'] = true;

					}
				} else {

					/*
					 * For "fixed" sidebars (i.e. left or right sidebars), we
					 * need to tell the user if they have no widgets.
					 */
					$this->config['sidebars'][ $location_id ]['error'] = true;

				}
			}
		}

		/*
		 * Determine whether to display the sticky header.
		 *
		 * Note: The sticky header is independent from the
		 * standard header.
		 */
		$this->config['sticky'] = themeblvd_do_sticky();

		/*
		 * Determine "Theme Layout" pieces of the
		 * configuration.
		 *
		 * This mostly refers to the "Theme Layout" meta
		 * box when editing posts and pages.
		 *
		 * We need to determine if the page will display
		 * a header and footer, if the transparent header
		 * will be applied, and any supporting factors like
		 * header and logo heights.
		 */
		$header = get_post_meta( $this->config['id'], '_tb_layout_header', true );

		/*
		 * And aside from standard pages, the transparent
		 * header can also be triggered on archives, if an
		 * archive banner is applied from the theme options.
		 */
		if ( themeblvd_get_option( 'apply_archive_banner' ) ) {

			if ( is_category() || is_tag() || is_author() || is_date() ) {

				if (  themeblvd_get_option( 'apply_archive_trans_header' ) ) {

					$header = 'suck_up';

				}
			}
		}

		/**
		 * Filters how the header is displayed.
		 *
		 * This filter is primarily useful to apply the
		 * transparent header (i.e. `suck_up`) in custom
		 * circumstances, where no `_tb_layout_header` meta
		 * exists, like a WordPress archive, for example.
		 *
		 * @since Theme_Blvd 2.5.0
		 *
		 * @param string $header Display style for header, `default`, `suck_up` or `hide`.
		 * @param int    $id     Current post ID, if this is a single page or post.
		 */
		$header = apply_filters( 'themeblvd_frontend_config_header', $header, $this->config['id'] );

		// Set the logo height.
		$logo = themeblvd_get_option( 'trans_logo' );

		$logo_height = 65;

		if ( $logo && 'image' === $logo['type'] && ! empty( $logo['image_height'] ) ) {

			$logo_height = intval( $logo['image_height'] );

		}

		$this->config['logo_height'] = $logo_height;

		if ( 'suck_up' === $header && themeblvd_supports( 'display', 'suck_up' ) ) {

			$this->config['suck_up'] = true;

			// Set the desktop header height.

			/*
			 * The theme's base height for the header before the
			 * user's logo height is dynamically added.
			 *
			 * 20px (above logo) + 20px (below logo) + 50px (menu)
			 */
			$addend = 90;

			// And plus 48px if, the header top bar exists.
			if ( themeblvd_has_header_info() ) {

				$addend += 48;

			}

			/**
			 * Filters the height of the header (for desktops)
			 * before taking into account the height of the
			 * logo inputted by the end-user, which is used to
			 * aid in displaying the transparent header.
			 *
			 * @since Theme_Blvd 2.5.0
			 *
			 * @param int    $addend   Height of header excluding logo.
			 * @param string $viewport Viewport range this applies to.
			 */
			$addend = apply_filters( 'themeblvd_top_height_addend', $addend, 'desktop' );

			/**
			 * Filters the total header height after the logo has
			 * been added to the $addend, which is used to aid in
			 * displaying the transparent header.
			 *
			 * @since Theme_Blvd 2.5.0
			 *
			 * @param int    Height of the header.
			 * @param string Display context, `desktop` or `tablet`.
			 */
			$this->config['top_height'] = apply_filters( 'themeblvd_top_height', $addend + $logo_height, 'desktop' );

			// Set the tablet header height.

			/*
			 * The theme's base height for the header before the
			 * user's logo height is dynamically added (on tablets).
			 *
			 * 20px (above logo) + 20px (below logo)
			 */
			$addend = 40;

			// And plus 48px if, the header top bar exists.
			if ( themeblvd_has_header_info() ) {
				$addend += 48;
			}

			/** This filter is documented above. */
			$addend = apply_filters( 'themeblvd_top_height_addend', $addend, 'tablet' );

			/** This filter is documented above. */
			$this->config['top_height_tablet'] = apply_filters( 'themeblvd_top_height', $addend + $logo_height, 'tablet' );

		} elseif ( 'hide' === $header && themeblvd_supports( 'display', 'hide_top' ) ) {

			$this->config['top'] = false;

		}

		// Hide footer, if necessary.
		if ( themeblvd_supports( 'display', 'hide_bottom' ) ) {

			if ( 'hide' === get_post_meta( $this->config['id'], '_tb_layout_footer', true ) ) {

				$this->config['bottom'] = false;

			}
		}

		/**
		 * Filters the primary frontend configuration.
		 *
		 * @since Theme_Blvd 2.2.0
		 *
		 * @param array $config {
		 *     Configuration arguments.
		 *
		 *     @type int         $id                     Store initial global post ID.
		 *     @type string      $mode                   Post display mode, `blog`, `list`, `grid` or `showcase`.
		 *     @type string|bool $builder                Slug of current custom layout, like `my-homepage`, or FALSE.
		 *     @type int         $builder_post_id        Post ID of post holding data from $builder slug.
		 *     @type string|bool $bottom_builder         Slug of current footer custom layout, like `my-homepage`, or FALSE.
		 *     @type int         $bottom_builder_post_id Post ID of post holding data from $bottom_builder slug.
		 *     @type string      $sidebar_layout         How content and sidebars are laid out.
		 *     @type array       $sidebars               Array of sidbar ID's for all corresponding locations.
		 *     @type bool        $sticky                 Whether to include sticky header.
		 *     @type bool        $suck_up                Whether to suck content up beneath transparent header.
		 *     @type int         $logo_height            Height of website logo.
		 *     @type int         $top_height             If $suck_up == TRUE, height of the header on desktops.
		 *     @type int         $top_height_tablet      If $suck_up == TRUE, height of the header on tablets.
		 *     @type bool        $top                    Whether to display website header.
		 *     @type bool        $bottom                 Whether to display website footer.
		 * }
		 */
		$this->config = apply_filters( 'themeblvd_frontend_config', $this->config );

		// DEBUG:
		// echo '<pre>'; print_r($this->config); echo '</pre>';

	}

	/**
	 * Get template part(s).
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param  string       $part Optional. Specific part to pull from $template_parts.
	 * @return string|array       All template parts or specific template part.
	 */
	public function get_template_parts( $part = '' ) {

		if ( ! $part ) {

			return $this->template_parts;

		}

		if ( ! isset( $this->template_parts[ $part ] ) ) {

			return $part;

		}

		return $this->template_parts[ $part ];

	}

	/**
	 * Get current post display mode.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @return string $mode Current theme mode, `blog`, `list`, `grid` or `showcase`.
	 */
	public function get_mode() {

		return $this->mode;

	}

	/**
	 * Get an item from the main frontend configuration.
	 *
	 * To use this method, it's easiest to just use the
	 * `themeblvd_config()` wrapper function.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param  string $key       Optinal. Key to retrieve from frontend configuration array.
	 * @param  string $secondary Optional. Key to retrieve from array, requiring to traverse one level deeper.
	 * @return string            Value from frontend configuration.
	 */
	public function get_config( $key = '', $secondary = '' ) {

		if ( ! $key ) {

			return $this->config;

		}

		if ( $secondary && isset( $this->config[ $key ][ $secondary ] ) ) {

			return $this->config[ $key ][ $secondary ];

		}

		if ( isset( $this->config[ $key ] ) ) {

			return $this->config[ $key ];

		}

		return null;

	}

	/**
	 * Get template attributes.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @return array $atts Template attributes.
	 */
	public function get_atts() {

		return $this->atts;

	}

	/**
	 * Output theme debugging information in website
	 * source code.
	 *
	 * This method is hooked to:
	 * 1. `wp_head` - 10
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	public function debug() {

		/**
		 * Filters whether debug info shows in source
		 * code.
		 *
		 * @since Theme_Blvd 2.7.0
		 *
		 * @param bool Whether debug info shows.
		 */
		if ( ! apply_filters( 'themeblvd_do_debug_info', true ) ) {

			return;

		}

		$child = false;

		$template = get_template();

		$stylesheet = get_stylesheet();

		$parent = wp_get_theme( $template );

		echo "\n<!--\n";

		echo "Debug Info\n\n";

		if ( $template != $stylesheet ) {

			$child = wp_get_theme( $stylesheet );

			printf( "Child Theme: %s\n", $child->get( 'Name' ) );

			printf( "Child Directory: %s\n", $stylesheet );

		} else {

			echo "Child Theme: No\n";

		}

		printf( "Parent Theme: %s %s\n", $parent->get( 'Name' ), $parent->get( 'Version' ) );

		printf( "Parent Theme Directory: %s\n", $template );

		if ( themeblvd_supports( 'admin', 'base' ) ) {

			printf( "Theme Base: %s\n", themeblvd_get_base() );

		}

		printf( "Theme Blvd Framework: %s\n", TB_FRAMEWORK_VERSION );

		if ( defined( 'TB_BUILDER_PLUGIN_VERSION' ) ) {

			printf( "Theme Blvd Builder: %s\n", TB_BUILDER_PLUGIN_VERSION );

		}

		if ( defined( 'TB_SHORTCODES_PLUGIN_VERSION' ) ) {

			printf( "Theme Blvd Shortcodes: %s\n", TB_SHORTCODES_PLUGIN_VERSION );

		}

		if ( defined( 'TB_SIDEBARS_PLUGIN_VERSION' ) ) {

			printf( "Theme Blvd Widget Areas: %s\n", TB_SIDEBARS_PLUGIN_VERSION );

		}

		if ( defined( 'TB_WIDGET_PACK_PLUGIN_VERSION' ) ) {

			printf( "Theme Blvd Widget Pack: %s\n", TB_WIDGET_PACK_PLUGIN_VERSION );

		}

		if ( defined( 'TB_PORTFOLIOS_PLUGIN_VERSION' ) ) {

			printf( "Theme Blvd Portfolios: %s\n", TB_PORTFOLIOS_PLUGIN_VERSION );

		}

		if ( function_exists( 'bbp_get_version' ) ) {

			printf( "bbPress: %s\n", bbp_get_version() );

		}

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {

			printf( "WPML: %s\n", ICL_SITEPRESS_VERSION );

		}

		if ( class_exists( 'GFForms' ) ) {

			printf( "Gravity Forms: %s\n", GFForms::$version );

		}

		if ( isset( $GLOBALS['woocommerce'] ) ) {

			printf( "WooCommerce: %s\n", $GLOBALS['woocommerce']->version );

		}

		/**
		 * Fires toward the end of the theme's debug
		 * output.
		 *
		 * This action hook is useful for adding custom
		 * debug info.
		 *
		 * @since Theme_Blvd 2.6.4
		 */
		do_action( 'themeblvd_debug_info' );

		if ( isset( $GLOBALS['wp_version'] ) ) {

			printf( "WordPress: %s\n", $GLOBALS['wp_version'] );

		}

		echo "-->\n";

	}

	/**
	 * Add framework css classes to body_class() based
	 * on our main configuration.
	 *
	 * This method is filtered onto:
	 * 1. `body_class` - 10
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param  array $classes Current body classes.
	 * @return array $classes Modified body classes.
	 */
	public function body_class( $class ) {

		/*
		 * Add classes to designate page has a custom
		 * layout or template.
		 */
		if ( $this->config['builder'] ) {

			$class[] = 'custom-layout-' . $this->config['builder'];

			$class[] = 'has_custom_layout';

		}

		// Add class to specify the sidebar Layout.
		$class[] = 'sidebar-layout-' . $this->config['sidebar_layout'];

		return $class;

	}

	/**
	 * Set template attributes.
	 *
	 * This is the framework's system for passing
	 * global attributes through template files.
	 * Instead of adding multiple global variables,
	 * they're all stored here in this object.
	 *
	 * These are just initial values set, but the
	 * following functions can be used to set or
	 * get template attributes at any point:
	 *
	 * 1. `themeblvd_set_atts()`
	 * 2. `themeblvd_set_att()`
	 * 3. `themeblvd_get_att()`
	 *
	 * This method is hooked to:
	 * 1. `wp` - 10
	 *
	 * @since Theme_Blvd 2.3.0
	 */
	public function atts_init() {

		global $post;

		if ( is_single() ) {

			// Featured images.
			$thumbs = true;

			if ( 'hide' === themeblvd_get_option( 'single_thumbs', null, 'full' ) ) {

				$thumbs = false;

			}

			if ( 'hide' === get_post_meta( $this->config['id'], '_tb_thumb', true ) ) {

				$thumbs = false;

			} elseif ( 'full' === get_post_meta( $this->config['id'], '_tb_thumb', true ) ) {

				$thumbs = true;

			}

			// Meta information (i.e. date posted, author).
			$show_meta = true;

			if ( 'hide' === themeblvd_get_option( 'single_meta', null, 'show' ) ) {

				$show_meta = false;

			}

			if ( 'hide' === get_post_meta( $this->config['id'], '_tb_meta', true ) ) {

				$show_meta = false;

			} elseif ( 'show' === get_post_meta( $this->config['id'], '_tb_meta', true ) ) {

				$show_meta = true;

			}

			// Sub meta information (i.e. tags, categories).
			$show_sub_meta = true;

			if ( 'hide' === themeblvd_get_option( 'single_sub_meta', null, 'show' ) ) {

				$show_sub_meta = false;

			}

			if ( 'hide' === get_post_meta( $this->config['id'], '_tb_sub_meta', true ) ) {

				$show_sub_meta = false;

			} elseif ( 'show' === get_post_meta( $this->config['id'], '_tb_sub_meta', true ) ) {

				$show_meta = true;

			}

			/**
			 * Filters the initial, global template attributes
			 * set for a single post.
			 *
			 * @since Theme_Blvd 2.3.0
			 *
			 * @param array Template attributes.
			 */
			$this->atts = apply_filters( 'themeblvd_single_atts', array(
				'location'      => 'single',
				'content'       => 'content', // We don't want excerpts to show on a single post!
				'thumbs'        => $thumbs,
				'show_meta'     => $show_meta,
				'show_sub_meta' => $show_sub_meta,
			));

		}

		/**
		 * Filters the post types where the featured image
		 * displaying above the content (i.e. "epic thumbnails")
		 * is supported.
		 *
		 * @since Theme_Blvd 2.5.0
		 *
		 * @param array Post types supporting epic thumbnails.
		 */
		$epic_types = apply_filters( 'themeblvd_epic_thumb_types', array( 'post', 'page', 'portfolio_item' ) );

		/*
		 * Set template attributes used for featured images,
		 * above the content (i.e. "epic thumbnails").
		 */
		if ( is_singular( $epic_types ) ) {

			if ( is_page() ) {

				$thumbs = themeblvd_get_option( 'page_thumbs', null, 'fw' );

			} else {

				$thumbs = themeblvd_get_option( 'single_thumbs', null, 'fw' );

			}

			$meta = get_post_meta( $post->ID, '_tb_thumb', true );

			if ( $meta && 'default' !== $meta ) {

				$thumbs = $meta;

			}

			if ( 'hide' === $thumbs ) {

				$thumbs = false;

			}

			if ( ! has_post_thumbnail( $post->ID ) && ! has_post_format( 'gallery', $post->ID ) ) {

				$thumbs = false;

			}

			if ( 'fs' === $thumbs && has_post_format( 'gallery', $post->ID ) && ! themeblvd_get_option( 'gallery_carousel' ) ) {

				$thumbs = 'fw';

			}

			$epic = false;

			if ( 'fw' === $thumbs || 'fs' === $thumbs ) {

				if ( has_post_format( 'gallery', $post->ID ) ) {

					if ( ! empty( $post->post_content ) ) {

						$pattern = get_shortcode_regex();

						if ( preg_match( "/$pattern/s", $post->post_content, $match ) && 'gallery' === $match[2] ) {

							$epic = true;

						}
					}
				} elseif ( has_post_thumbnail( $post->ID ) ) {

					$epic = true;

				}
			}

			$this->atts['thumbs'] = $thumbs;

			$this->atts['epic_thumb'] = $epic;

		}

		/*
		 * Set template attributes used for archive banner,
		 * above the content (i.e. "epic banner").
		 */
		if ( themeblvd_get_option( 'apply_archive_banner' ) ) {

			if ( is_category() || is_tag() || is_author() || is_date() ) {

				$this->atts['thumbs'] = themeblvd_get_option( 'archive_banner_display' );

				$this->atts['epic_banner'] = true;

			}
		}

	}

	/**
	 * Set template attributes, in bulk.
	 *
	 * To easily utilize this method, you can use the
	 * wrapper function `themeblvd_set_atts()`.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param  array $atts  Attributes to be merged with global attributes.
	 * @param  bool  $flush Whether or not to flush previous attributes before merging.
	 * @return array $atts  Updated attributes array.
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
	 * Set individual template attribute.
	 *
	 * To easily utilize this method, you can use the
	 * wrapper function `themeblvd_set_att()`.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param  string $key   Template attributes key.
	 * @param  mixed  $value New value to store.
	 * @return mixed         Newly stored value.
	 */
	public function set_att( $key, $value ) {

		$this->atts[ $key ] = $value;

		return $this->atts[ $key ];

	}

	/**
	 * Remove individual template attribute.
	 *
	 * To easily utilize this method, you can use the
	 * wrapper function `themeblvd_remove_att()`.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param string $key Template attributes key.
	 */
	public function remove_att( $key ) {

		unset( $this->atts[ $key ] );

	}

	/**
	 * Get individual template attribute.
	 *
	 * To easily utilize this method, you can use the
	 * wrapper function `themeblvd_get_att()`.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param  string $key Template attributes key.
	 * @return mixed       Template attribute value.
	 */
	public function get_att( $key ) {

		if ( isset( $this->atts[ $key ] ) ) {

			return $this->atts[ $key ];

		}

		return null;

	}

}
