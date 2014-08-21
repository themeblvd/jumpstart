<?php
/**
 * Setup any secondary queries or hook any modifications
 * to the primary query.
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_Query {

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
	 * Secondary query args
	 *
	 * @since 2.3.0
	 * @access private
	 * @see set_second_query()
	 * @var array
	 */
	private $second_query = null;

	/**
	 * Conditionals for original post object.
	 *
	 * @since 2.3.0
	 */
	private $was = array();

	/*--------------------------------------------*/
	/* Constructor
	/*--------------------------------------------*/

	/**
     * Creates or returns an instance of this class.
     *
     * @since 2.3.0
     *
     * @return Theme_Blvd_Query A single instance of this class.
     */
	public static function get_instance() {

		if ( self::$instance == null ) {
            self::$instance = new self;
        }

        return self::$instance;
	}

	/**
	 * Hook everything in.
	 *
	 * @since 2.3.0
	 */
	private function __construct() {

		// Set private properties
		add_action( 'wp', array( $this, 'set_was' ), 5 );
		add_action( 'wp', array( $this, 'templates_init' ) );

		// Query modifications
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

	}

	/*--------------------------------------------*/
	/* Methods, mutators
	/*--------------------------------------------*/

	/**
	 * Set a secondary query for paginated elements. This is used
	 * for Post List and Post Grid page templates. It's also used
	 * for custom layouts that contain a pagianted post list or
	 * post grid.
	 *
	 * @since 2.3.0
	 *
	 * @param array|string $args Arguments to parse into query
	 * @param string $type Type of secondary query, list or grid
	 * @return array $second_query Newly stored second query attribute
	 */
	public function set_second_query( $args, $type = '' ) {

		// If no $type is passed in, it means the $args
		// should already be formatted.
		if ( ! $type ) {
			$this->second_query = wp_parse_args( $args );
			return $this->second_query;
		}

		// Is there a query source? (i.e. category, tag, query)
		$source = '';
		if ( isset( $args['source'] ) ) {
			$source = $args['source'];
		}

		// Custom query
		if ( ( $source == 'query' && isset( $args['query'] ) ) || ( ! $source && ! empty( $args['query'] ) ) ) {

			// Convert string to query array
			$query = wp_parse_args( htmlspecialchars_decode( $args['query'] ) );

			// If they didn't set a posts_per_page on their
			// custom query, let's do it for them.
			if( ! isset( $query['posts_per_page'] ) ) {
				$query['posts_per_page'] = get_option('posts_per_page');
			}

			// Force posts per page on grids
			if( $type == 'grid' && apply_filters( 'themeblvd_force_grid_posts_per_page', true ) ) {
				if( ! empty( $args['posts_per_page'] ) ) {
					$query['posts_per_page'] = $args['posts_per_page'];
				}
			}

		}

		// List of pages
		if ( ! isset( $query ) && $source == 'pages' && ! empty( $args['pages'] ) ) {

			$pages = str_replace( ' ', '', $args['pages'] );
			$pages = explode( ',', $pages );

			$query = array(
				'post_type' => 'page',
				'post__in' 	=> array(),
				'orderby'	=> 'post__in'
			);

			if ( $pages ) {
				foreach ( $pages as $pagename ) {
					$query['post__in'][] = themeblvd_post_id_by_name( $pagename, 'page' );
				}
			}
		}

		// If no custom query, let's build it.
		if ( ! isset( $query ) ) {

			// Start building query args
			$query = array(
				'cat' => null
			);

			// Categories
			if ( 'category' == $source || ! $source ) {
				if ( isset( $args['categories']['all'] ) && ! $args['categories']['all'] ) {

					unset( $args['categories']['all'] );

					$category_name = '';
					if ( $args['categories'] ) {
						foreach ( $args['categories'] as $category => $include ) {
							if ( $include ) {
								$category_name .= $category.',';
							}
						}
					}

					if ( $category_name ) {
						$query['category_name'] = themeblvd_remove_trailing_char( $category_name, ',' );
					}

				}
			}

			// Tags
			if ( 'tag' == $source && ! empty( $args['tag'] ) ) {
				$query['tag'] = $args['tag'];
			}

			// Posts per page
			if ( ! empty( $args['posts_per_page'] ) ) {
				$query['posts_per_page'] = $args['posts_per_page'];
			}

			// Order by (date, title, rand, etc)
			if ( $args['orderby'] ) {
				$query['orderby'] = $args['orderby'];
			}

			// Order (ASC or DESC)
			if ( $args['order'] ) {
				$query['order'] = $args['order'];
			}

		}

		// Pagination
		if ( empty( $query['paged'] ) ) {
			if ( get_query_var('paged') ) {
				$query['paged'] = get_query_var('paged');
			} else if ( get_query_var('page') ) {
				$query['paged'] = get_query_var('page');
			} else {
				$query['paged'] = 1;
			}
		}

		// Set final query
		$this->second_query = $query;

		return $this->second_query;
	}

	/**
	 * Set conditionals that can be used with the was()
	 * method to help determine the state of the original
	 * query.
	 *
	 * @since 2.3.0
	 */
	public function set_was() {

		global $post;

		$this->was = array(
			'id'			=> 0,
			'name'			=> '',
			'single'		=> 0,
			'page'			=> 0,
			'singular'		=> 0,
			'page_template'	=> 0,
			'home'			=> 0,
			'front_page'	=> 0,
			'archive'		=> 0,
			'search'		=> 0,
		);

		if ( is_page() || is_single() ) {

			$this->was['id'] = $post->ID;
			$this->was['name'] = $post->post_name;

			if ( is_single() ) {
				$this->was['single'] = 1;
			}

			if ( is_page() ) {
				$this->was['page'] = 1;
			}

		}

		if ( is_singular() ) {
			$this->was['singular'] = 1;
		}

		if ( is_page_template() ) {
			$this->was['page_template'] = basename( get_page_template() );
		}

		if ( is_home() ) {
			$this->was['home'] = 1;
		}

		if ( is_front_page() ) {
			$this->was['front_page'] = 1;
		}

		if ( is_archive() ) {
			$this->was['archive'] = 1;
		}

		if ( is_search() ) {
			$this->was['search'] = 1;
		}

	}

	/*--------------------------------------------*/
	/* Methods, accessors
	/*--------------------------------------------*/

	/**
	 * Get stored second query.
	 *
	 * @since 2.3.0
	 *
	 * @return array $second_query Query arguments to feed to WP_Query
	 */
	public function get_second_query() {
		return $this->second_query;
	}

	/**
	 * Get stored "was" conditional parameters for
	 * original $post object.
	 *
	 * @since 2.3.0
	 *
	 * @return array $was Conditions for original $post object
	 */
	public function get_was() {
		return $this->was;
	}

	/*--------------------------------------------*/
	/* Methods, helpers
	/*--------------------------------------------*/

	/**
	 * Setup args for secondary query in the Post List/Grid
	 * page templates. Hooked to "wp".
	 */
	public function templates_init() {

		global $post;

		// If this isn't the Post List or Post Grid page
		// template, get out of here.
		if ( ! is_page_template('template_list.php') && ! is_page_template('template_grid.php') && ! is_page_template('template_blog.php') ) {
			return;
		}

		// What type of template are we dealing with?
		$type = '';
		if ( is_page_template('template_list.php') ) {
			$type = 'list';
		} else if ( is_page_template('template_grid.php') ) {
			$type = 'grid';
		} else if ( is_page_template('template_blog.php') ) {
			$type = 'blog';
		}

		// Start building query args
		$query = array(
			'cat' => null
		);

		// Category Slugs
		$category_name = get_post_meta( $post->ID, 'category_name', true );
		if ( $category_name ) {
			$query['category_name'] = str_replace( ' ', '', $category_name );
		}

		// Category IDs
		$cat = get_post_meta( $post->ID, 'cat', true );

		if ( ! $cat ) {
			$cat = get_post_meta( $post->ID, 'categories', true ); // @deprecated "categories" custom field
		}

		if ( $cat ) {
			$query['cat'] = str_replace(' ', '', $cat );
		}

		// Tags
		$tag = get_post_meta( $post->ID, 'tag', true );

		if ( $tag ) {
			$query['tag'] = str_replace(' ', '', $tag );
		}

		// Posts per page
		$posts_per_page = '';

		if ( $type == 'list' || $type == 'blog' ) {

			$posts_per_page = get_post_meta( $post->ID, 'posts_per_page', true );

			if ( ! $posts_per_page && $type == 'list' ) {
				$posts_per_page = themeblvd_get_option('list_posts_per_page');
			}

		} else if ( $type == 'grid' ) {

			$columns = get_post_meta( $post->ID, 'columns', true );

			if ( ! $columns ) {
				$columns = intval(themeblvd_get_option('grid_columns', null, '3'));
			}

			$rows = get_post_meta( $post->ID, 'rows', true );

			if ( ! $rows ) {
				$rows = intval(themeblvd_get_option('rows_columns', null, '3'));
			}

			$posts_per_page = $columns*$rows;
		}

		if ( $posts_per_page ) {
			$query['posts_per_page'] = $posts_per_page;
		}

		// Orderby
		$orderby = get_post_meta( $post->ID, 'orderby', true );

		if ( $orderby ) {
			$query['orderby'] = $orderby;
		}

		// Order
		$order = get_post_meta( $post->ID, 'order', true ); // ACS or DESC

		if ( $order ) {
			$query['order'] = $order;
		}

		// Offset
		$offset = get_post_meta( $post->ID, 'offset', true );

		if ( $offset ) {
			$query['offset'] = $offset;
		}

		// Custom query string
		$custom = get_post_meta( $post->ID, 'query', true );

		if ( $custom ) {
			$query = wp_parse_args( htmlspecialchars_decode( $custom ), $query );
		}

		// Pagination
		if ( empty( $query['paged'] ) ) {
			if ( get_query_var('paged') ) {
				$query['paged'] = get_query_var('paged');
			} else if ( get_query_var('page') ) {
				$query['paged'] = get_query_var('page');
			} else {
				$query['paged'] = 1;
			}
		}

		// Extend and finalize
		$this->set_second_query( apply_filters( "themeblvd_template_{$type}_query", $query, $custom, $post->ID ) );
	}

	/*--------------------------------------------*/
	/* Methods, main query modifications
	/*--------------------------------------------*/

	/**
	 * Make any needed modifications to the main query
	 * via pre_get_posts for the homepage or frontpage
	 *
	 * @since 2.3.0
	 *
	 * @param WP_Query $q Current WP_Query object at the time of pre_get_posts
	 */
	public function pre_get_posts( $q ) {

		if ( ! $q->is_main_query() || ( ! $q->is_home() && ! $q->is_page() && ! $q->is_archive() ) ) {
			return;
		}

		// Static frontpage
		if ( $q->is_page() && get_option('show_on_front') == 'page' && get_option('page_on_front') == $q->get('page_id') ) {

			$templates = apply_filters('themeblvd_paginated_templates', array('template_blog.php', 'template_list.php', 'template_grid.php', 'template_builder.php'));
			$template = get_post_meta( $q->get('page_id'), '_wp_page_template', true );

			if ( in_array( $template, $templates ) && $q->get('page') >= 2 ) {
				$q->set('paged', $q->query['paged'] );
			}

		}

		// Adjust posts_per_page
		if ( $q->is_archive() ) {

			$config = Theme_Blvd_Frontend_Init::get_instance();
			$mode = $config->get_mode();

			if ( $mode == 'grid' ) {

				$columns = intval(themeblvd_get_option('grid_columns', null, '3'));
				$rows = intval(themeblvd_get_option('grid_rows', null, '3'));

				$q->set('posts_per_page', $columns*$rows);

			} else if ( $mode == 'list' ) {

				$default = get_option('posts_per_page');
				$q->set( 'posts_per_page', intval(themeblvd_get_option('list_posts_per_page', null, $default)) );

			}
		}

		// Exclude any categories from posts page
		if ( $q->is_home() ) {

			$cat = '';
			$exclude = themeblvd_get_option( 'blog_categories' );

			if ( $exclude ) {
				foreach ( $exclude as $key => $value ) {
					if ( $value ) {
						$cat .= sprintf('-%s,', $key);
					}
				}
			}

			if ( $cat ) {
				$cat = themeblvd_remove_trailing_char($cat, ',');
				$q->set('cat', $cat);
			}
		}

		do_action( 'themeblvd_pre_get_posts', $q, $this );

	} // end pre_get_posts()

	/*--------------------------------------------*/
	/* Methods, conditionals
	/*--------------------------------------------*/

	/**
	 * Verify the state of the original query.
	 *
	 * @since 2.3.0
	 *
	 * @param string $type The primary type of WP page being checked for
	 * @param string $helper A secondary param if allowed with $type
	 * @return bool
	 */
	public function was( $type, $helper = '' ) {

		switch ( $type ) {

			case 'single' :
			case 'page' :
				if ( $this->was[$type] ) {
					if ( ! $helper || $helper == $this->was['id'] || $helper == $this->was['name'] ) {
						return true;
					}
				}
				break;

			case 'singular' :
				if ( $this->was['singular'] ) {
					return true;
				}
				break;

			case 'page_template' :
				if ( $this->was['page_template'] ) {
					if ( ! $helper || $helper == $this->was['page_template'] ) {
						return true;
					}
				}
				break;

			case 'home' :
				if ( $this->was['home'] ) {
					return true;
				}
				break;

			case 'front_page' :
				if ( $this->was['front_page'] ) {
					return true;
				}
				break;

			case 'archive' :
				if ( $this->was['archive'] ) {
					return true;
				}
				break;

			case 'search' :
				if ( $this->was['search'] ) {
					return true;
				}
				break;
		}

		return false;
	}

}