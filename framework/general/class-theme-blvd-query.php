<?php
/**
 * Query Handling
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.3.0
 */

/**
 * Setup any secondary queries or hook any modifications
 * to the primary query.
 *
 * Throughout this class you will see the concept of a
 * "secondary loop" referenced. This concept refers to
 * loop of posts within the context of a post or page.
 *
 * In these cases, the top-level page or post would be
 * from WordPress's primary query, while the secondary
 * would be contained somewhere within, separately.
 *
 * @since Theme_Blvd 2.3.0
 */
class Theme_Blvd_Query {

	/**
	 * A single instance of this class.
	 *
	 * @since Theme_Blvd 2.3.0
	 */
	private static $instance = null;

	/**
	 * Secondary query args
	 *
	 * @since Theme_Blvd 2.3.0
	 * @access private
	 * @see set_second_query()
	 * @var array
	 */
	private $second_query = null;

	/**
	 * Conditionals for original post object.
	 *
	 * @since Theme_Blvd 2.3.0
	 */
	private $was = array();

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @return Theme_Blvd_Query A single instance of this class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {

			self::$instance = new self;

		}

		return self::$instance;

	}

	/**
	 * Hook everything in.
	 *
	 * @since Theme_Blvd 2.3.0
	 */
	private function __construct() {

		// Set private properties.

		add_action( 'wp', array( $this, 'set_was' ), 5 );

		add_action( 'wp', array( $this, 'templates_init' ) );

		// Add primary query modifications.

		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

	}

	/**
	 * Set a secondary query for paginated elements.
	 *
	 * This is used for page templates that display a
	 * secondary loop of posts.
	 *
	 * It's also used for custom layouts that contain
	 * a paginated secondary post loop.
	 *
	 * This method can best be utilized with the wrapper
	 * function, `themeblvd_set_second_query()`.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param array|string $args         Arguments to parse into query.
	 * @param string       $type         Type of secondary query, `blog`, `list`, `grid` or `showcase`.
	 * @return array       $second_query Newly stored second query, formatted for WP_Query.
	 */
	public function set_second_query( $args, $type = '' ) {

		/*
		 * If no $type is passed in, it means the arguments
		 * should already be formatted.
		 *
		 * In this case, we're just storing it in our object
		 * and then sending it back for confirmation.
		 */
		if ( ! $type ) {

			$this->second_query = wp_parse_args( $args );

			return $this->second_query;

		}

		// Is there a query source, like category, tag, query, etc?
		$source = '';

		if ( isset( $args['source'] ) ) {

			$source = $args['source'];

		}

		// Custom query
		if ( ( 'query' === $source && isset( $args['query'] ) ) || ( ! $source && ! empty( $args['query'] ) ) ) {

			/*
			 * Don't do anything further, if the query string doesn't
			 * contain any equal sign.
			 *
			 * If user is passing some sort of identfier key that they can
			 * catch with a custom filter, let's just send it through, or
			 * else we can continue to process the custom query.
			 *
			 * If the custom query has no equal sign "=", then we can assume
			 * they're not intending it to be an actual query string, and thus
			 * just sent it through.
			 */
			if ( false === strpos( $args['query'], '=' ) ) {

				$query = $args['query'];

			} else {

				/*
				 * Pull custom field to determine query, use custom_field=my_query
				 * for element's query string.
				 */
				if ( 0 === strpos( $args['query'], 'custom_field=' ) ) {

					$args['query'] = get_post_meta(
						themeblvd_config( 'id' ),
						str_replace( 'custom_field=', '', $args['query'] ),
						true
					);

				}

				// Convert string to query array.
				$query = wp_parse_args( htmlspecialchars_decode( $args['query'] ) );

				/*
				 * If they didn't set a posts_per_page on their
				 * custom query, let's do it for them.
				 */
				if ( ! isset( $query['posts_per_page'] ) ) {

					$query['posts_per_page'] = get_option( 'posts_per_page' );

				}

				// Force posts per page on grids.
				if ( 'grid' === $type || 'showcase' === $type ) {

					/**
					 * Filters whether to force a maximum posts per
					 * page on grid post displays.
					 *
					 * @since Theme_Blvd 2.3.0
					 *
					 * @param bool Whether to force posts per page on grids.
					 */
					if ( apply_filters( 'themeblvd_force_grid_posts_per_page', true ) ) {

						if ( ! empty( $args['posts_per_page'] ) ) {

							$query['posts_per_page'] = $args['posts_per_page'];

						}
					}
				}
			}
		}

		/*
		 * Process query from passed list of pages, like
		 * `page-1, page-2, page-3`.
		 */
		if ( ! isset( $query ) && 'pages' === $source && ! empty( $args['pages'] ) ) {

			$pages = str_replace( ' ', '', $args['pages'] );

			$pages = explode( ',', $pages );

			$query = array(
				'post_type'           => 'page',
				'post__in'            => array(),
				'orderby'             => 'post__in',
				'ignore_sticky_posts' => true,
			);

			if ( $pages ) {

				foreach ( $pages as $page_name ) {

					$query['post__in'][] = themeblvd_post_id_by_name( $page_name, 'page' );

				}
			}

			if ( ! empty( $args['posts_per_page'] ) ) {

				$query['posts_per_page'] = $args['posts_per_page'];

			}

			if ( ! empty( $args['orderby'] ) ) {

				$query['orderby'] = $args['orderby'];

			}

			if ( ! empty( $args['order'] ) ) {

				$query['order'] = $args['order'];

			}
		}

		// If no custom query, let's build it.
		if ( ! isset( $query ) ) {

			$query = array(
				'cat' => null,
			);

			if ( 'category' == $source || ! $source ) {

				if ( isset( $args['categories']['all'] ) && ! $args['categories']['all'] ) {

					unset( $args['categories']['all'] );

					$category_name = '';

					if ( $args['categories'] ) {

						foreach ( $args['categories'] as $category => $include ) {

							if ( $include ) {

								$category_name .= $category . ',';

							}
						}
					}

					if ( $category_name ) {

						$query['category_name'] = themeblvd_remove_trailing_char( $category_name, ',' );

					}
				}
			}

			if ( 'tag' == $source && ! empty( $args['tag'] ) ) {

				$query['tag'] = $args['tag'];

			}

			if ( ! empty( $args['posts_per_page'] ) ) {

				$query['posts_per_page'] = $args['posts_per_page'];

			}

			if ( ! empty( $args['orderby'] ) ) {

				$query['orderby'] = $args['orderby'];

			}

			if ( ! empty( $args['order'] ) ) {

				$query['order'] = $args['order'];

			}
		}

		// Add pagination.
		if ( is_array( $query ) && empty( $query['paged'] ) ) {

			if ( get_query_var( 'paged' ) ) {

				$query['paged'] = get_query_var( 'paged' );

			} elseif ( get_query_var( 'page' ) ) {

				$query['paged'] = get_query_var( 'page' );

			} else {

				$query['paged'] = 1;

			}
		}

		// Store final secondary query with object.
		$this->second_query = $query;

		return $this->second_query;

	}

	/**
	 * Get stored second query.
	 *
	 * This method can best be utilized with the wrapper
	 * function, `themeblvd_get_second_query()`.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @return array $second_query Query arguments to feed to WP_Query
	 */
	public function get_second_query() {

		return $this->second_query;

	}

	/**
	 * Set conditionals that can be used with the was()
	 * method to help determine the state of the original
	 * query.
	 *
	 * This system is helpful for replacing basic WordPress
	 * conditional checks, from within a secondary loop, to
	 * reference back to the original main query.
	 *
	 * This method is hooked to:
	 * 1. `wp` - 5
	 *
	 * @since Theme_Blvd 2.3.0
	 */
	public function set_was() {

		global $post;

		$this->was = array(
			'id'            => 0,
			'name'          => '',
			'single'        => 0,
			'page'          => 0,
			'singular'      => 0,
			'page_template' => 0,
			'home'          => 0,
			'front_page'    => 0,
			'archive'       => 0,
			'search'        => 0,
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

	/**
	 * Get stored "was" conditional parameters for
	 * original main WordPress query.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @return array $was All conditions; see set_was().
	 */
	public function get_was() {

		return $this->was;

	}

	/**
	 * Verify a condition of the original query.
	 *
	 * This method can best be utilized with the wrapper
	 * function, `themeblvd_was()`.
	 *
	 * @see themeblvd_was()
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param  string $type   The primary type of WP page being checked for.
	 * @param  string $helper A secondary param if allowed with $type.
	 * @return bool           Whether the checked condtion was met.
	 */
	public function was( $type, $helper = '' ) {

		switch ( $type ) {

			case 'single':
			case 'page':
				if ( $this->was[ $type ] ) {
					if ( ! $helper || $helper == $this->was['id'] || $helper == $this->was['name'] ) {
						return true;
					}
				}
				break;

			case 'singular':
				if ( $this->was['singular'] ) {
					return true;
				}
				break;

			case 'page_template':
				if ( $this->was['page_template'] ) {
					if ( ! $helper || $helper == $this->was['page_template'] ) {
						return true;
					}
				}
				break;

			case 'home':
				if ( $this->was['home'] ) {
					return true;
				}
				break;

			case 'front_page':
				if ( $this->was['front_page'] ) {
					return true;
				}
				break;

			case 'archive':
				if ( $this->was['archive'] ) {
					return true;
				}
				break;

			case 'search':
				if ( $this->was['search'] ) {
					return true;
				}
				break;
		}

		return false;

	}

	/**
	 * Setup secondary query for pages templates,
	 * which contain a secondary posts loop.
	 *
	 * This method is hooked to:
	 * 1. `wp` - 10
	 *
	 * @since Theme_Blvd 2.3.0
	 */
	public function templates_init() {

		global $post;

		// Set page templates, which use a secondary query.
		$templates = array(
			'template_list.php',
			'template_grid.php',
			'template_blog.php',
			'template_showcase.php',
		);

		/*
		 * Only proceed if this is a page templates, which
		 * uses a secondary query.
		 */
		if ( ! is_page_template( $templates ) ) {

			return;

		}

		// What type of template are we dealing with?
		$type = '';

		if ( is_page_template( 'template_list.php' ) ) {

			$type = 'list';

		} elseif ( is_page_template( 'template_grid.php' ) ) {

			$type = 'grid';

		} elseif ( is_page_template( 'template_showcase.php' ) ) {

			$type = 'showcase';

		} elseif ( is_page_template( 'template_blog.php' ) ) {

			$type = 'blog';

		}

		/*
		 * Build the query, from possible parameters passed through
		 * the "Post Template Options" meta box.
		 */
		$query = array(
			'cat' => null,
		);

		// Maybe use category slugs.
		$category_name = get_post_meta( $post->ID, 'category_name', true );

		if ( $category_name ) {

			$query['category_name'] = str_replace( ' ', '', $category_name );

		}

		// Maybe use category IDs.
		$cat = get_post_meta( $post->ID, 'cat', true );

		if ( ! $cat ) {

			$cat = get_post_meta( $post->ID, 'categories', true ); // @deprecated `categories` custom field.

		}

		if ( $cat ) {

			$query['cat'] = str_replace( ' ', '', $cat );

		}

		// Maybe use tags.
		$tag = get_post_meta( $post->ID, 'tag', true );

		if ( $tag ) {

			$query['tag'] = str_replace( ' ', '', $tag );

		}

		// Set the posts per page.
		$posts_per_page = '';

		if ( 'list' === $type || 'blog' === $type ) {

			$posts_per_page = get_post_meta( $post->ID, 'posts_per_page', true );

			if ( ! $posts_per_page && 'list' === $type ) {

				$posts_per_page = themeblvd_get_option( 'list_posts_per_page' );

			}
		} elseif ( 'grid' === $type || 'showcase' === $type ) {

			$meta_display = get_post_meta( $post->ID, 'tb_display', true );

			if ( $meta_display ) {

				$display = $meta_display;

			} elseif ( 'grid' === $type ) {

				$display = themeblvd_get_option( 'grid_display', null, 'paginated' );

			} elseif ( 'showcase' === $type ) {

				$display = themeblvd_get_option( 'showcase_display', null, 'masonry_paginated' );

			}

			if ( 'paginated' === $display ) {

				$columns = get_post_meta( $post->ID, 'columns', true );

				if ( ! $columns ) {

					$columns = intval( themeblvd_get_option( $type . '_columns', null, '3' ) );

				}

				$rows = get_post_meta( $post->ID, 'rows', true );

				if ( ! $rows ) {

					$rows = intval( themeblvd_get_option( $type . '_rows', null, '3' ) );

				}

				$posts_per_page = $columns * $rows;

			} else { // paginated_masonry

				$posts_per_page = get_post_meta( $post->ID, 'posts_per_page', true );

				if ( ! $posts_per_page ) {

					$posts_per_page = themeblvd_get_option( $type . '_posts_per_page' );

				}
			}
		}

		if ( $posts_per_page ) {

			$query['posts_per_page'] = $posts_per_page;

		}

		// Maybe add orderby parameter.
		$orderby = get_post_meta( $post->ID, 'orderby', true );

		if ( $orderby ) {

			$query['orderby'] = $orderby;

		}

		// Maybe add order parameter.
		$order = get_post_meta( $post->ID, 'order', true ); // `ACS` or `DESC`

		if ( $order ) {

			$query['order'] = $order;

		}

		// Maybe add offset parameter.
		$offset = get_post_meta( $post->ID, 'offset', true );

		if ( $offset ) {

			$query['offset'] = $offset;

		}

		// Maybe use custom query string.
		$custom = get_post_meta( $post->ID, 'query', true );

		/*
		 * Don't do anything further, if the query string doesn't
		 * contain any equal sign.
		 *
		 * If user is passing some sort of identfier key that they can
		 * catch with a custom filter, let's just send it through, or
		 * else we can continue to process the custom query.
		 *
		 * If the custom query has no equal sign "=", then we can assume
		 * they're not intending it to be an actual query string, and thus
		 * just sent it through.
		 */
		if ( $custom && false === strpos( $custom, '=' ) ) {

			$query = $custom;

		} else {

			$query = wp_parse_args( htmlspecialchars_decode( $custom ), $query );

		}

		// Add pagination.
		if ( is_array( $query ) && empty( $query['paged'] ) ) {

			if ( get_query_var( 'paged' ) ) {

				$query['paged'] = get_query_var( 'paged' );

			} elseif ( get_query_var( 'page' ) ) {

				$query['paged'] = get_query_var( 'page' );

			} else {

				$query['paged'] = 1;

			}
		}

		/**
		 * Filters the secondary query generated for page
		 * templates, which display secondary loops of
		 * posts.
		 *
		 * The filter name will vary by the type of template,
		 * which will be, `blog`, `list`, `grid` or `showcase`.
		 *
		 * @since Theme_Blvd 2.3.0
		 *
		 * @param array|string $query  The generated query.
		 * @param string       $custom Original custom query string meta value.
		 * @param int          $id     Top-level page ID, which template is applied to.
		 */
		$query = apply_filters( "themeblvd_template_{$type}_query", $query, $custom, $post->ID );

		// Store secondary query in our object.
		$this->set_second_query( $query );

	}

	/**
	 * Modifications to the primary query.
	 *
	 * This method is hooked to:
	 * 1. `pre_get_posts` - 10
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param WP_Query $q The WP_Query instance (passed by reference).
	 */
	public function pre_get_posts( $q ) {

		if ( ! $q->is_main_query() ) {

			return;

		}

		if ( ! $q->is_home() && ! $q->is_page() && ! $q->is_archive() && ! $q->is_search() ) {

			return;

		}

		/*
		 * Modify query for static front page.
		 *
		 * This helps with complications that come in getting
		 * to the second page of a set of posts from a secondary
		 * loop, within a page template.
		 *
		 * This has been a long-standing oddity in WordPress
		 * when trying to display a secondary loop of posts
		 * within a page that has been set at the static front
		 * page.
		 */
		if ( $q->is_page() && 'page' === get_option( 'show_on_front' ) && get_option( 'page_on_front' ) == $q->get( 'page_id' ) ) {

			/**
			 * Filters the page temlates that use pagination
			 * for secondary loop of posts.
			 *
			 * @param Theme_Blvd 2.3.0
			 *
			 * @param array Page templates.
			 */
			$templates = apply_filters( 'themeblvd_paginated_templates', array(
				'template_blog.php',
				'template_list.php',
				'template_grid.php',
				'template_builder.php',
			) );

			$template = get_post_meta( $q->get( 'page_id' ), '_wp_page_template', true );

			if ( in_array( $template, $templates ) && $q->get( 'page' ) >= 2 ) {

				$q->set( 'paged', $q->query['paged'] );

			}
		}

		// Adjust posts_per_page for different templates.
		if ( $q->is_archive() && ! $q->get( 'posts_per_page' ) ) {

			$config = Theme_Blvd_Frontend_Init::get_instance();

			$mode = $config->get_mode();

			if ( 'grid' === $mode || 'showcase' === $mode ) {

				if ( 'masonry_paginated' === themeblvd_get_option( $mode . '_display' ) ) {

					$num = intval( themeblvd_get_option( $mode . '_posts_per_page' ) );

				} else {

					$cols = intval( themeblvd_get_option( $mode . '_columns', null, '3' ) );

					$rows = intval( themeblvd_get_option( $mode . '_rows', null, '3' ) );

					$num = $cols * $rows;

				}

				$q->set( 'posts_per_page', $num );

			} elseif ( 'list' === $mode ) {

				$q->set( 'posts_per_page', intval( themeblvd_get_option( 'list_posts_per_page', null, get_option( 'posts_per_page' ) ) ) );

			}
		}

		// Exclude any categories from posts page.
		if ( $q->is_home() ) {

			$cat = '';

			$exclude = themeblvd_get_option( 'blog_categories' );

			if ( $exclude ) {

				foreach ( $exclude as $key => $value ) {

					if ( $value ) {

						$cat .= sprintf( '-%s,', $key );

					}
				}
			}

			if ( $cat ) {

				$cat = themeblvd_remove_trailing_char( $cat, ',' );

				$q->set( 'cat', $cat );

			}
		}

		// Allow limiting search results by post type.
		if ( $q->is_search() && ! empty( $_GET['s_type'] ) ) {

			$q->set( 'post_type', esc_attr( $_GET['s_type'] ) );

		}

		/**
		 * Fires at the end of the theme's modifications
		 * with pre_get_posts.
		 *
		 * @since Theme_Blvd 2.3.0
		 *
		 * @param WP_Query         $q    The WP_Query instance (passed by reference).
		 * @param Theme_Blvd_Query $this Instance of theme's query object (passed by reference).
		 */
		do_action( 'themeblvd_pre_get_posts', $q, $this );

	}

}
