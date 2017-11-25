<?php
/**
 * Frontend Setup
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */

/**
 * Initiate frontend.
 *
 * This function is hooked to:
 * 1. `after_setup_theme` - 1001
 *
 * @since @@name-framework 2.0.0
 */
function themeblvd_frontend_init() {

	if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

		/*
		 * Set up the frontend, when the website is
		 * loaded.
		 */
		Theme_Blvd_Frontend_Init::get_instance();

		/*
		 * Set up any secondary queries or hook any
		 * modifications to the primary query.
		 */
		Theme_Blvd_Query::get_instance();

	}

}

/**
 * Wrapper for WordPress's get_template_part().
 *
 * This wrapper function helps us to create a
 * unified system where the template parts are
 * consistently filtered.
 *
 * @since @@name-framework 2.2.0
 *
 * @param string $type Type of template part to get.
 */
function themeblvd_get_template_part( $type ) {

	$config = Theme_Blvd_Frontend_Init::get_instance();

	/**
	 * Filters the first $slug paramter passed to
	 * get_template_part().
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string       Template part slug.
	 * @param string $type Type of template part.
	 */
	$slug = apply_filters( 'themeblvd_template_part_slug', 'template-parts/content', $type );

	/**
	 * Filters the first $slug paramter passed to
	 * get_template_part().
	 *
	 * @since @@name-framework 2.0.0
	 *
	 * @param string       Name of specialised template.
	 * @param string $type Type of template part.
	 */
	$name = apply_filters( 'themeblvd_template_part', $config->get_template_parts( $type ), $type );

	/*
	 * Include template file.
	 */
	get_template_part( $slug, $name );

}

/**
 * Get second $name parameter for uses of
 * get_template_part().
 *
 * @since @@name-framework 2.0.0
 * @deprecated @@name-framework 2.7.0
 *
 * @param string $type Type of template part to get.
 */
function themeblvd_get_part( $type ) {

	themeblvd_deprecated_function(
		__FUNCTION__,
		'2.7.0',
		'themeblvd_get_template_part',
		__( 'Instead of using themeblvd_get_part(), you should now use themeblvd_get_template_part() wrapper function to completely replace get_template_part() instance.' , '@@text-domain' )
	);

}

/**
 * Get a frontend configuration value.
 *
 * This function is used from within the theme's
 * template files to return the values setup in the
 * frontend initialization.
 *
 * @since @@name-framework 2.0.0
 *
 * @param  string $key       Optinal. Key to retrieve from frontend configuration array.
 * @param  string $secondary Optional. Key to retrieve from array, requiring to traverse one level deeper.
 * @return mixed             Value from frontend configuration object.
 */
function themeblvd_config( $key = '', $secondary = '' ) {

	$config = Theme_Blvd_Frontend_Init::get_instance();

	return $config->get_config( $key, $secondary );

}

/**
 * Display CSS class for current sidebar layout.
 *
 * @since @@name-framework 2.0.0
 * @deprecated @@name-framework 2.7.0
 */
function themeblvd_sidebar_layout_class() {

	themeblvd_deprecated_function(
		__FUNCTION__,
		'2.7.0',
		'themeblvd_main_class'
	);

	$config = Theme_Blvd_Frontend_Init::get_instance();

	echo $config->get_config( 'sidebar_layout' );

}

/**
 * Set multiple global template attributes.
 *
 * At any time, this function can be called to effect
 * the global template attributes array which can
 * be utilized within template files.
 *
 * This system provides a way for attributes to be set
 * and retreived with themeblvd_get_att() from files
 * included with WP's get_template_part().
 *
 * @since @@name-framework 2.2.0
 *
 * @param  array $atts  Attributes to be merged with global attributes.
 * @param  bool  $flush Whether or not to flush previous attributes before merging.
 * @return array $atts  Updated attributes array.
 */
function themeblvd_set_atts( $atts, $flush = false ) {

	$config = Theme_Blvd_Frontend_Init::get_instance();

	return $config->set_atts( $atts, $flush );

}

/**
 * Set a single global template attributes.
 *
 * Working with the system established in the
 * previous function, this function allows you
 * to set an individual attribute along with
 * creating a new variable.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  string $key   Template attributes key.
 * @param  mixed  $value New value to store.
 * @return mixed         Newly stored value.
 */
function themeblvd_set_att( $key, $value ) {

	$config = Theme_Blvd_Frontend_Init::get_instance();

	return $config->set_att( $key, $value );

}

/**
 * Get a single global template attribute.
 *
 * Retrieves a single attribute set with
 * themeblvd_set_atts() or themeblvd_set_att().
 *
 * @since @@name-framework 2.2.0
 *
 * @param  string $key Template attributes key.
 * @return mixed       Template attribute value.
 */
function themeblvd_get_att( $key ) {

	$config = Theme_Blvd_Frontend_Init::get_instance();

	return $config->get_att( $key );

}

/**
 * Get the secondary query.
 *
 * Generally, the secondary query is used for
 * displaying secondary post loops within pages
 * and posts.
 *
 * @since @@name-framework 2.3.0
 *
 * @return array Parameters formatted for WP_Query.
 */
function themeblvd_get_second_query() {

	$query = Theme_Blvd_Query::get_instance();

	return $query->get_second_query();

}

/**
 * Set the secondary query.
 *
 * Generally, the secondary query is used for
 * displaying secondary post loops within pages
 * and posts.
 *
 * @since @@name-framework 2.3.0
 *
 * @param  array|string $args Arguments to parse into query.
 * @param  string       $type Type of secondary query, `blog`, `list`, `grid` or `showcase`.
 * @return array              Newly stored second query, formatted for WP_Query.
 */
function themeblvd_set_second_query( $args, $type ) {

	$query = Theme_Blvd_Query::get_instance();

	return $query->set_second_query( $args, $type );

}

/**
 * Setup arguments to pass to WP_Query.
 *
 * @since @@name-framework 2.0.0
 *
 * @param  array  $options Settings for generating query.
 * @param  string $type    Type of posts being displayed.
 * @return array  $args    Parameters for WP_Query.
 */
function themeblvd_get_posts_args( $options, $type = 'list' ) {

	// Is there a query source? (i.e. category, tag, query)
	$source = '';

	if ( ! empty( $options['source'] ) ) {

		$source = $options['source'];

	}

	// How are we displaying?
	$display = $type;

	if ( ! empty( $options['display'] ) ) {

		$display = $options['display'];

	}

	// Custom query
	if ( ( 'query' === $source && isset( $options['query'] ) ) || ( ! $source && ! empty( $options['query'] ) ) ) {

		$query = $options['query'];

		/**
		 * If user is passing some sort of identfier key that they can
		 * catch with a custom filter, let's just send it through, or
		 * else we can continue to process the custom query.
		 *
		 * If the custom query has no equal sign "=", then we can assume
		 * they're not intending it to be an actual query string, and thus
		 * just sent it through.
		 */
		if ( is_array( $query ) || strpos( $query, '=' ) !== false ) {

			/*
			 * Pull custom field to determine query, use custom_field=my_query
			 * for element's query string.
			 */
			if ( is_string( $query ) && 0 === strpos( $query, 'custom_field=' ) ) {

				$query = get_post_meta(
					themeblvd_config( 'id' ),
					str_replace( 'custom_field=', '', $query ),
					true
				);

			}

			// Convert string to query array.
			if ( ! is_array( $query ) ) {

				$query = wp_parse_args( htmlspecialchars_decode( $query ) );

			}

			// Force posts per page on grids.
			if ( ( 'grid' === $display || 'showcase' === $display ) ) {

				/**
				 * Filters whether to force a maximum posts per
				 * page on grid post displays.
				 *
				 * @since @@name-framework 2.3.0
				 *
				 * @param bool Whether to force posts per page on grids.
				 */
				if ( apply_filters( 'themeblvd_force_grid_posts_per_page', true ) ) {

					if ( ! empty( $options['rows'] ) && ! empty( $options['columns'] ) ) {

						$query['posts_per_page'] = $options['rows'] * $options['columns'];

					}
				}
			}
		}
	}

	/**
	 * Generate query from list of post slugs.
	 *
	 * Note: This is presented to the end user as
	 * input asking for a comma-separated list of pages.
	 */
	if ( ! isset( $query ) && 'pages' === $source && ! empty( $options['pages'] ) ) {

		$options['pages'] = str_replace( ' ', '', $options['pages'] );

		$options['pages'] = explode( ',', $options['pages'] );

		$query = array(
			/**
			 * Filters the post types allowed when passing
			 * a list of post slugs to themeblvd_get_posts_args().
			 *
			 * Note: This is presented to the end user as
			 * input asking for a comma-separated list of pages.
			 *
			 * @since @@name-framework 2.0.0
			 *
			 * @param array Post types.
			 */
			'post_type' => apply_filters( 'themeblvd_page_list_post_types', array( 'page', 'post', 'portfolio_item' ) ),
			'post__in'  => array(),
			'orderby'   => 'post__in',
		);

		if ( $options['pages'] ) {

			foreach ( $options['pages'] as $pagename ) {

				$query['post__in'][] = themeblvd_post_id_by_name( $pagename );

			}
		}
	}

	if ( ! isset( $query ) ) {

		$query = array(
			'suppress_filters' => false,
		);

		if ( 'category' === $source || ! $source ) {

			if ( ! empty( $options['cat'] ) ) {

				$query['cat'] = $options['cat'];

			} elseif ( ! empty( $options['category_name'] ) ) {

				$query['category_name'] = $options['category_name'];

			} elseif ( ! empty( $options['categories'] ) && empty( $options['categories']['all'] ) ) {

				$categories = '';

				foreach ( $options['categories'] as $category => $include ) {

					if ( $include ) {

						$current_category = get_term_by( 'slug', $category, 'category' );

						$categories .= $current_category->term_id . ',';

					}
				}

				if ( $categories ) {

					$categories = themeblvd_remove_trailing_char( $categories, ',' );

					$query['cat'] = $categories;

				}
			}
		}

		if ( 'tag' === $source || ! $source ) {

			if ( ! empty( $options['tag'] ) ) {

				$query['tag'] = $options['tag'];

			}
		}

		/*
		 * If post slider (NOT grid slider), we only want
		 * images with featured images set.
		 */
		if ( 'slider' === $type ) {

			$query['meta_key'] = '_thumbnail_id';

		}

		if ( ! empty( $options['orderby'] ) ) {

			$query['orderby'] = $options['orderby'];

		}

		if ( ! empty( $options['order'] ) ) {

			$query['order'] = $options['order'];

		}

		if ( ! empty( $options['offset'] ) ) {

			$query['offset'] = intval( $options['offset'] );

		}

		if ( ! empty( $options['meta_key'] ) ) {

			$query['meta_key'] = $options['meta_key'];

		}

		if ( ! empty( $options['meta_value'] ) ) {

			$query['meta_value'] = $options['meta_value'];

		}
	}

	if ( is_array( $query ) && empty( $query['posts_per_page'] ) && ! isset( $query['post__in'] ) ) {

		if ( 'grid' === $type || 'showcase' === $type ) {

			if ( ! empty( $options['columns'] ) ) {

				if ( 'slider' === $display && ! empty( $options['slides'] ) ) {

					$query['posts_per_page'] = intval( $options['slides'] ) * intval( $options['columns'] );

				} elseif ( 'masonry' === $display && ! empty( $options['posts_per_page'] ) ) {

					$query['posts_per_page'] = $options['posts_per_page'];

				} elseif ( ( 'filter' === $display || 'masonry_filter' === $display ) && ! empty( $options['filter_max'] ) ) {

					$query['posts_per_page'] = $options['filter_max'];

				} elseif ( ! empty( $options['rows'] ) && ! empty( $options['columns'] ) ) {

					$query['posts_per_page'] = intval( $options['rows'] ) * intval( $options['columns'] );

				}
			}
		} else {

			if ( ! empty( $options['posts_per_page'] ) ) {

				$query['posts_per_page'] = intval( $options['posts_per_page'] );

			}
		}

		if ( empty( $query['posts_per_page'] ) ) {

			$query['posts_per_page'] = -1;

		}
	}

	/**
	 * Filters the generated post arguments for
	 * many elements.
	 *
	 * @since @@name-framework 2.0.0
	 *
	 * @param array  $args    Parameters for WP_Query.
	 * @param array  $options Settings for generating query.
	 * @param string $type    Type of posts being displayed.
	 */
	return apply_filters( 'themeblvd_get_posts_args', $query, $options, $type );

}

/**
 * Conditional check against the original query.
 *
 * This system is helpful for replacing basic
 * WordPress conditional checks, from within a
 * secondary loop, to reference back to the original
 * main query.
 *
 * Accepted for $type:
 * 1. `single`        Like calling is_single() on original query.
 * 2. `page`          Like calling is_page() on original query.
 * 3. `singular`      Like calling is_singular() on original query.
 * 4. `page_template` Like calling is_page_template() on original query.
 * 5. `home`          Like calling is_home() on original query.
 * 6. `front_page`    Like calling is_front_page() on original query.
 * 7. `archive`       Like calling is_archive() on original query.
 * 8. `search`        Like calling is_search() on original query.
 *
 * For example, if you were within a secondary post
 * loop, and you wanted to check if the top-level
 * page was a certain page template, instead of:
 *
 * `is_page_template( 'template_foo.php' )`
 *
 * You'd use:
 *
 * `themeblvd_was( 'page_template', 'template_foo.php' )`
 *
 * @since @@name-framework 2.3.0
 *
 * @param  string $type   The primary type of WP page being checked for.
 * @param  string $helper A secondary param if allowed with $type.
 * @return bool           Whether the checked condtion was met.
 */
function themeblvd_was( $type, $helper = '' ) {

	$query = Theme_Blvd_Query::get_instance();

	return $query->was( $type, $helper );

}

/**
 * Add <body> classes.
 *
 * This function is filtered onto:
 * 1. `body_class` - 10
 *
 * @since @@name-framework 2.2.0
 *
 * @param  array $class Current body classes.
 * @return array $class Modified body classes.
 */
function themeblvd_body_class( $class ) {

	/*
	 * Add classes to represent current OS and
	 * web browser.
	 */
	if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {

		$browser = $_SERVER['HTTP_USER_AGENT'];

		// Add class to represent general operating system.
		if ( preg_match( '/Mac/', $browser ) ) {

			$class[] = 'mac';

		} elseif ( preg_match( '/Windows/', $browser ) ) {

			$class[] = 'windows';

		} elseif ( preg_match( '/Linux/', $browser ) ) {

			$class[] = 'linux';

		} else {

			$class[] = 'unknown-os';

		}

		// Add class to represent specific web browser.
		if ( preg_match( '/Chrome/', $browser ) ) {

			$class[] = 'chrome';

		} elseif ( preg_match( '/Safari/', $browser ) ) {

			$class[] = 'safari';

		} elseif ( preg_match( '/Opera/', $browser ) ) {

			$class[] = 'opera';

		} elseif ( preg_match( '/MSIE/', $browser ) ) {

			$class[] = 'msie';

			if ( preg_match( '/MSIE 6.0/', $browser ) ) {

				$class[] = 'ie6';

			} elseif ( preg_match( '/MSIE 7.0/', $browser ) ) {

				$class[] = 'ie7';

			} elseif ( preg_match( '/MSIE 8.0/', $browser ) ) {

				$class[] = 'ie8';

			} elseif ( preg_match( '/MSIE 9.0/', $browser ) ) {

				$class[] = 'ie9';

			} elseif ( preg_match( '/MSIE 10.0/', $browser ) ) {

				$class[] = 'ie10';

			} elseif ( preg_match( '/MSIE 11.0/', $browser ) ) {

				$class[] = 'ie11';

			}
		} elseif ( preg_match( '/Edge/', $browser ) ) {

			$class[] = 'edge';

		} elseif ( preg_match( '/Firefox/', $browser ) && preg_match( '/Gecko/', $browser ) ) {

			$class[] = 'firefox';

		} else {

			$class[] = 'unknown-browser';

		}
	}

	/*
	 * Add "mobile" or "desktop" classes, based on
	 * the actual device, and not the viewport size.
	 */
	if ( wp_is_mobile() ) {

		$class[] = 'mobile';

	} else {

		$class[] = 'desktop';

	}

	// Add support for scroll effects.
	if ( themeblvd_supports( 'display', 'scroll_effects' ) ) {

		$class[] = 'tb-scroll-effects';

	}

	// Apply transparent header.
	if ( themeblvd_config( 'suck_up' ) ) {

		$class[] = 'tb-suck-up';

	}

	// Add supporting CSS class for epic thumbnail.
	if ( ( is_single() || is_page() ) && themeblvd_get_att( 'epic_thumb' ) ) {

		$class[] = 'has-epic-thumb';

		if ( 'fs' === themeblvd_get_att( 'thumbs' ) ) {

			$class[] = 'has-fs-epic-thumb';

		}
	}

	// Add supporting class for breadcrumbs.
	if ( themeblvd_show_breadcrumbs() ) {

		$class[] = 'has-breadcrumbs';

	}

	// Add supporting class for the hidden side panel.
	if ( themeblvd_do_side_panel() ) {

		$class[] = 'has-side-panel';

	}

	// Add supporting class for sticky header.
	if ( themeblvd_config( 'sticky' ) ) {

		$class[] = 'has-sticky';

	}

	// Add supporting class for displaying narrow full-width content.
	if ( themeblvd_do_fw_narrow() ) {

		$class[] = 'tb-fw-narrow';

		if ( themeblvd_do_img_popout() ) {

			$class[] = 'tb-img-popout';

		}
	}

	// Add custom styling for WP's tag cloud widget.
	if ( themeblvd_supports( 'assets', 'tag_cloud' ) ) {

		$class[] = 'tb-tag-cloud';

	}

	// Add supporting class for the "Blank Page" page template.
	if ( is_page_template( 'template_blank.php' ) ) {

		$class[] = 'tb-blank-page';

	}

	// Add supporting class for theme's default print styles.
	if ( themeblvd_supports( 'display', 'print' ) ) {

		$class[] = 'tb-print-styles';

	}

	/**
	 * Filters the <body> classes, after the theme
	 * has added to them.
	 *
	 * @since @@name-framework 2.0.0
	 *
	 * @param array  $class   CSS classes.
	 * @param string $browser Current user agent.
	 */
	return apply_filters( 'themeblvd_browser_classes', $class, $browser );

}

if ( ! function_exists( 'themeblvd_include_scripts' ) ) {

	/**
	 * Load framework's frontend scripts.
	 *
	 * This functin is hooked to:
	 * 1. `wp_enqueue_scripts` - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	function themeblvd_include_scripts() {

		$suffix = SCRIPT_DEBUG ? '' : '.min';

		$in_footer = themeblvd_supports( 'assets', 'in_footer' ); // Will be TRUE, by default.

		$deps = array( 'jquery' );

		if ( themeblvd_supports( 'assets', 'gmap' ) ) {

			$gmaps = 'https://maps.googleapis.com/maps/api/js';

			$gmap_key = themeblvd_get_option( 'gmap_api_key' );

			if ( $gmap_key ) {

				$gmaps = add_query_arg( 'key', $gmap_key, $gmaps );

			}

			wp_register_script(
				'google-maps-api',
				esc_url( $gmaps ),
				array(),
				null,
				$in_footer
			);

		}

		if ( themeblvd_supports( 'assets', 'charts' ) ) {

			wp_register_script(
				'charts',
				esc_url( TB_FRAMEWORK_URI . "/assets/js/chart{$suffix}.js" ),
				array(),
				'2.7.1',
				$in_footer
			);

		}

		wp_enqueue_script( 'jquery' );

		if ( themeblvd_supports( 'assets', 'flexslider' ) ) {

			wp_enqueue_script( // @TODO Can we remove with Front Street integration?
				'flexslider',
				esc_url( TB_FRAMEWORK_URI . '/assets/js/flexslider.min.js' ),
				array( 'jquery' ),
				'2.6.0',
				$in_footer
			);

		}

		if ( themeblvd_supports( 'assets', 'owl_carousel' ) && themeblvd_get_option( 'gallery_carousel' ) ) {

			wp_enqueue_script( // @TODO Will be bundled with Front Street.
				'owl-carousel',
				esc_url( TB_FRAMEWORK_URI . '/assets/plugins/owl-carousel/owl.carousel.min.js' ),
				array( 'jquery' ),
				'2.2.1',
				$in_footer
			);

		}

		if ( themeblvd_supports( 'assets', 'bootstrap' ) ) {

			wp_enqueue_script( // @TODO Will be removed with the implementation of Front Street.
				'bootstrap',
				esc_url( TB_FRAMEWORK_URI . '/assets/plugins/bootstrap/js/bootstrap.min.js' ),
				array( 'jquery' ),
				'3.3.5',
				$in_footer
			);

		}

		if ( themeblvd_supports( 'assets', 'magnific_popup' ) ) {

			wp_enqueue_script( // @TODO Will be bundled with Front Street.
				'magnific-popup',
				esc_url( TB_FRAMEWORK_URI . '/assets/js/magnificpopup.min.js' ),
				array( 'jquery' ),
				'0.9.3',
				$in_footer
			);

		}

		if ( themeblvd_supports( 'assets', 'superfish' ) ) {

			wp_enqueue_script( // @TODO Will be removed with the implementation of Front Street.
				'hoverintent',
				esc_url( TB_FRAMEWORK_URI . '/assets/js/hoverintent.min.js' ),
				array( 'jquery' ),
				'r7',
				$in_footer
			);

			wp_enqueue_script( // @TODO Will be removed with the implementation of Front Street.
				'superfish',
				esc_url( TB_FRAMEWORK_URI . '/assets/js/superfish.min.js' ),
				array( 'jquery' ),
				'1.7.4',
				$in_footer
			);

		}

		if ( themeblvd_supports( 'assets', 'easypiechart' ) ) {

			wp_enqueue_script(
				'easypiechart',
				esc_url( TB_FRAMEWORK_URI . "/assets/js/easypiechart{$suffix}.js" ),
				array( 'jquery' ),
				'2.1.6',
				$in_footer
			);

		}

		if ( themeblvd_supports( 'assets', 'isotope' ) ) {

			wp_enqueue_script(
				'isotope',
				esc_url( TB_FRAMEWORK_URI . "/assets/js/isotope{$suffix}.js" ),
				array( 'jquery' ),
				'3.0.4',
				$in_footer
			);

		}

		if ( themeblvd_supports( 'assets', 'primary_js' ) ) {

			wp_enqueue_script(
				'themeblvd',
				esc_url( TB_FRAMEWORK_URI . "/assets/js/themeblvd{$suffix}.js" ),
				array( 'jquery' ),
				TB_FRAMEWORK_VERSION,
				$in_footer
			);

			wp_localize_script( 'themeblvd', 'themeblvd', themeblvd_get_js_locals() );

		}

		// Comments reply
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {

			wp_enqueue_script( 'comment-reply' );

		}
	}
}

/**
 * Use themeblvd_button() function for read
 * more links, added by the end-user from
 * `<!--more-->` tag.
 *
 * This function is filtered onto:
 * 1. `the_content_more_link` - 10
 *
 * @since @@name-framework 2.2.0
 *
 * @param  string $more_link_element Read More link element.
 * @param  string $more_link_text    Read More text.
 * @return string                    Modified read More link element.
 */
function themeblvd_read_more_link( $read_more, $more_link_text ) {

	/**
	 * Filters the arguments used to build the
	 * read more link, which are passed to
	 * themeblvd_button().
	 *
	 * @see themeblvd_button()
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @param array Arguments to split into parameters to themeblvd_button().
	 */
	$args = apply_filters( 'themeblvd_the_content_more_args', array(
		'text'        => $more_link_text,
		'url'         => get_permalink() . '#more-' . get_the_ID(),
		'color'       => 'default',
		'target'      => null,
		'size'        => null,
		'classes'     => null,
		'title'       => null,
		'icon_before' => null,
		'icon_after'  => null,
		'addon'       => null,
	));

	$button = themeblvd_button(
		$args['text'],
		$args['url'],
		$args['color'],
		$args['target'],
		$args['size'],
		$args['classes'],
		$args['title'],
		$args['icon_before'],
		$args['icon_after'],
		$args['addon']
	);

	/**
	 * Filters the theme's modified version of
	 * the read more link, generated from the
	 * `<!--more-->` tag.
	 *
	 * @param string $button Final HTML for read more link.
	 */
	return apply_filters( 'themeblvd_read_more_link', $button );

}
