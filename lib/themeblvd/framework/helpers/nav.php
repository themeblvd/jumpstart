<?php
/**
 * Helpers: Navigation
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */

/**
 * Adds wrapping HTML around output from
 * wp_link_pages(), to help with theme styling.
 *
 * Note: This is meant to help get wp_link_pages()
 * looking more like themeblvd_pagination() output.
 *
 * This function is filtered onto:
 * 1. `wp_link_pages_args` - 10
 *
 * @since @@name-framework 2.2.1
 *
 * @param  array $args Default arguments from wp_link_pages().
 * @return array $args Modified arguments for wp_link_pages().
 */
function themeblvd_link_pages_args( $args ) {

	// Add HTML before pagination links.
	$args['before']  = "<div class=\"pagination-wrap\">\n";

	$args['before'] .= "\t<div class=\"pagination\">\n";

	$args['before'] .= "\t\t<div class=\"btn-group clearfix\">\n";

	// Add HTML after pagination links.
	$args['after'] = "\t\t</div><!-- .btn-group -->\n";

	$args['after'] = "\t</div><!-- .pagination -->\n";

	$args['after'] = "</div><!-- .pagination-wrap -->\n";

	return $args;

}

/**
 * Adjust output HTML for wp_link_pages(), to
 * help with theme styling.
 *
 * Note: This is meant to help get wp_link_pages()
 * looking more like themeblvd_pagination() output.
 *
 * This function is filtered onto:
 * 1. `wp_link_pages_link` - 10
 *
 * @since @@name-framework 2.2.1
 *
 * @param  string $link Markup of individual link to be filtered.
 * @param  int    $num  Page number of link being filtered.
 * @return string $link HTML for individual link after being filtered.
 */
function themeblvd_link_pages_link( $link, $num ) {

	global $page;

	/**
	 * Filters the frontend framework button color
	 * for the pagination buttons of wp_link_pages().
	 *
	 * @since @@name-framework 2.2.1
	 *
	 * @param string       Button color.
	 * @param string $link Markup of individual link to be filtered.
	 * @param int    $num  Page number of link being filtered.
	 */
	$color = apply_filters( 'themeblvd_link_pages_button_color', 'default', $link, $num );

	/**
	 * Filters the frontend framework button size
	 * for the pagination buttons of wp_link_pages().
	 *
	 * @since @@name-framework 2.2.1
	 *
	 * @param string       Button size.
	 * @param string $link Markup of individual link to be filtered.
	 * @param int    $num  Page number of link being filtered.
	 */
	$size = apply_filters( 'themeblvd_link_pages_button_size', 'default', $link, $num );

	$class = themeblvd_get_button_class( $color, $size );

	if ( $page == $num ) {

		$class .= ' active';

		$link = sprintf(
			'<a href="%s" class="%s">%s</a>',
			get_pagenum_link( $num ),
			$class,
			$num
		);

	} else {

		$link = str_replace(
			'<a',
			'<a class="' . $class . '"',
			$link
		);

	}

	return $link;

}

/**
 * Build pieces to be used in breadcrumbs.
 *
 * @see themeblvd_get_breadcrumbs_trail()
 *
 * @since @@name-framework 2.2.1
 *
 * @param  array $args {
 *     Breadcrumb arguments.
 *
 *     @type string $delimiter HTML between breadcrumb pieces.
 *     @type string $home      Home link text.
 *     @type string $home_link Home link URL.
 *     @type string $before    HTML before current breadcrumb item.
 *     @type string $after     HTML after current breadcrumb item.
 * }
 * @return array $breadcrumbs Breadcrumbs parts to display trail.
 */
function themeblvd_get_breadcrumb_parts( $args ) {

	global $post;

	global $wp_query;

	$breadcrumbs = array();

	$parts = array();

	wp_reset_query();

	// Add home link.
	$breadcrumbs[] = array(
		'link' => $args['home_link'],
		'text' => $args['home'],
		'type' => 'home',
	);

	if ( is_category() ) {

		/* Category Archives */

		$cat_obj = $wp_query->get_queried_object();

		$current_cat = $cat_obj->term_id;

		$current_cat = get_category( $current_cat );

		if ( $current_cat->parent && ( $current_cat->parent != $current_cat->term_id ) ) {

			$parents = themeblvd_get_category_parents( $current_cat->parent );

			if ( is_array( $parents ) ) {

				$parts = array_merge( $parts, $parents );

			}
		}

		// Add current category.
		$parts[] = array(
			'link' => '',
			'text' => $current_cat->name,
			'type' => 'category',
		);

	} elseif ( is_day() ) {

		/* Day Archives */

		// Add year.
		$parts[] = array(
			'link' => get_year_link( get_the_time( 'Y' ) ),
			'text' => get_the_time( 'Y' ),
			'type' => 'year',
		);

		// Add month.
		$parts[] = array(
			'link' => get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ),
			'text' => get_the_time( 'F' ),
			'type' => 'month',
		);

		// Add day.
		$parts[] = array(
			'link' => '',
			'text' => get_the_time( 'd' ),
			'type' => 'day',
		);

	} elseif ( is_month() ) {

		/* Month Archives */

		// Add year.
		$parts[] = array(
			'link' => get_year_link( get_the_time( 'Y' ) ),
			'text' => get_the_time( 'Y' ),
			'type' => 'year',
		);

		// Add month.
		$parts[] = array(
			'link' => '',
			'text' => get_the_time( 'F' ),
			'type' => 'month',
		);

	} elseif ( is_year() ) {

		/* Year Archives */

		// Add year.
		$parts[] = array(
			'link' => '',
			'text' => get_the_time( 'Y' ),
			'type' => 'year',
		);

	} elseif ( is_tag() ) {

		/* Tag Archives */

		$parts[] = array(
			'link' => '',
			'text' => sprintf( themeblvd_get_local( 'crumb_tag' ), single_tag_title( '', false ) ),
			'type' => 'tag',
		);

	} elseif ( is_author() ) {

		/* Author Archives */

		global $author;

		$userdata = get_userdata( $author );

		$parts[] = array(
			'link' => '',
			'text' => themeblvd_get_local( 'crumb_author' ) . ' ' . $userdata->display_name,
			'type' => 'author',
		);

	} elseif ( is_attachment() ) {

		/* Attachment */

		$parent = get_post( $post->post_parent );

		if ( ! empty( $parent ) ) {

			$category = get_the_category( $parent->ID );

			if ( ! empty( $category ) ) {

				$category = $category[0];

				$parents = themeblvd_get_category_parents( $category->term_id );

				if ( is_array( $parents ) ) {

					$parts = array_merge( $parts, $parents );

				}
			}

			$parts[] = array(
				'link' => get_permalink( $parent->ID ),
				'text' => $parent->post_title,
				'type' => 'single',
			);

		}

		$parts[] = array(
			'link' => '',
			'text' => get_the_title(),
			'type' => 'attachment',
		);

	} elseif ( is_single() ) {

		/* Single Posts */

		if ( get_post_type() == 'post' ) {

			// Add categories, IF standard post type only.
			$category = get_the_category();

			$category = $category[0];

			$parents = themeblvd_get_category_parents( $category->term_id );

			if ( is_array( $parents ) ) {

				$parts = array_merge( $parts, $parents );

			}
		}

		$parts[] = array(
			'link' => '',
			'text' => get_the_title(),
			'type' => 'single',
		);

	} elseif ( is_search() ) {

		/* Search Results */

		$parts[] = array(
			'link' => '',
			'text' => sprintf( themeblvd_get_local( 'crumb_search' ), get_search_query() ),
			'type' => 'search',
		);

	} elseif ( is_page() ) {

		/* Pages */

		if ( $post->post_parent ) {

			// Parent pages
			$parent_id  = $post->post_parent;

			$parents = array();

			while ( $parent_id ) {

				$page = get_page( $parent_id );

				$parents[] = array(
					'link'  => get_permalink( $page->ID ),
					'text'  => get_the_title( $page->ID ),
					'type'  => 'page',
				);

				$parent_id = $page->post_parent;

			}

			$parents = array_reverse( $parents );

			$parts = array_merge( $parts, $parents );

		}
		$parts[] = array(
			'link' => '',
			'text' => get_the_title(),
			'type' => 'page',
		);

	} elseif ( is_404() ) {

		/* 404 */

		$parts[] = array(
			'link' => '',
			'text' => themeblvd_get_local( 'crumb_404' ),
			'type' => '404',
		);

	}

	/**
	 * Filters the breadcrumbs trail before the home
	 * link is added to the start or the page number
	 * is added to the end.
	 *
	 * @since @@name-framework 2.2.1
	 *
	 * @param array $parts Breadcrumbs parts.
	 * @param array $args {
	 *     Breadcrumb arguments.
	 *
	 *     @type string $delimiter HTML between breadcrumb pieces.
	 *     @type string $home      Home link text.
	 *     @type string $home_link Home link URL.
	 *     @type string $before    HTML before current breadcrumb item.
	 *     @type string $after     HTML after current breadcrumb item.
	 * }
	 */
	$parts = apply_filters( 'themeblvd_pre_breadcrumb_parts', $parts, $args );

	// Add page number, if is paged.
	if ( get_query_var( 'paged' ) ) {

		$last = count( $parts ) - 1;

		$parts[ $last ]['text'] .= ' (' . themeblvd_get_local( 'page' ) . ' ' . get_query_var( 'paged' ) . ')';

	}

	/**
	 * Filters the breadcrumbs trail after the home
	 * link is added to the start and the page number
	 * has been added to the end.
	 *
	 * @since @@name-framework 2.2.1
	 *
	 * @param array       Breadcrumbs parts.
	 * @param array $args {
	 *     Breadcrumb arguments.
	 *
	 *     @type string $delimiter HTML between breadcrumb pieces.
	 *     @type string $home      Home link text.
	 *     @type string $home_link Home link URL.
	 *     @type string $before    HTML before current breadcrumb item.
	 *     @type string $after     HTML after current breadcrumb item.
	 * }
	 */
	return apply_filters( 'themeblvd_breadcrumb_parts', array_merge( $breadcrumbs, $parts ), $args );

}

/**
 * Whether breadcrumbs should display.
 *
 * @since @@name-framework 2.2.1
 *
 * @return bool $show Whether breadcrumbs should display.
 */
function themeblvd_show_breadcrumbs() {

	global $post;

	$display = '';

	// Pages and Posts.
	if ( is_page() || is_single() ) {

		$display = get_post_meta( $post->ID, '_tb_breadcrumbs', true );

	}

	// Standard site-wide option.
	if ( ! $display || 'default' === $display ) {

		$display = themeblvd_get_option( 'breadcrumbs', null, 'show' );

	}

	// Disable on posts homepage.
	if ( is_home() ) {

		$display = 'hide';

	}

	// Disable on custom layouts (can be added in layout from Builder).
	if ( is_page_template( 'template_builder.php' ) && ! is_search() && ! is_archive() ) { // ! is_search() and ! is_archive() added to fix is_page_template() bug noticed in WordPress 4.7.

		$display = 'hide';

	}

	// Convert to boolean.
	$show = false;

	if ( 'show' === $display ) {

		$show = true;

	}

	/**
	 * Filters whether breadcrumbs should display.
	 *
	 * @since @@name-framework 2.2.1
	 *
	 * @param bool   $show    Whether breadcrumbs should display.
	 * @param string $display String represention of display before converted to boolean.
	 */
	return apply_filters( 'themeblvd_show_breadcrumbs', $show, $display );

}

/**
 * Get parent category attributes. Used in
 * building breadcrumbs.
 *
 * @since @@name-framework 2.2.1
 *
 * @param  int   $id   ID of closest category parent.
 * @param  array $used Any categories in our chain that we've already used.
 * @return array       Breadcrumb links from themeblvd_get_term_parents().
 */
function themeblvd_get_category_parents( $id, $used = array() ) {

	return themeblvd_get_term_parents( $id, 'category', $used );

}

/**
 * Get parent term attributes. Used with
 * building breadcrumbs.
 *
 * @since @@name-framework 2.3.0
 *
 * @param  int   $id    ID of closest category parent.
 * @param  array $used  Any categories in our chain that we've already used.
 * @return array $chain Breadcrumb links.
 */
function themeblvd_get_term_parents( $id, $taxonomy = 'category', $used = array() ) {

	$chain = array();

	$parent = get_term( intval( $id ), $taxonomy );

	if ( is_wp_error( $parent ) ) {

		return $parent;

	}

	// Parent of the parent.
	if ( $parent->parent && ( $parent->parent != $parent->term_id ) && ! in_array( $parent->parent, $used ) ) {

		$used[] = $parent->parent;

		$grand_parent = themeblvd_get_term_parents( $parent->parent, $taxonomy, $used );

		$chain = array_merge( $grand_parent, $chain );

	}

	$chain[] = array(
		'link' => esc_url( get_term_link( intval( $id ), $taxonomy ) ),
		'text' => $parent->name,
		'type' => $taxonomy,
	);

	return $chain;

}

/**
 * Get pagination parts.
 *
 * @see themeblvd_get_pagination()
 *
 * @since @@name-framework 2.3.0
 *
 * @param  int   $pages Number of pages.
 * @param  int   $range Range for paginated buttons, helpful for many pages.
 * @return array $parts Parts to construct pagination.
 */
function themeblvd_get_pagination_parts( $pages = 0, $range = 2 ) {

	global $paged;

	global $wp_query;

	$parts = array();

	$showitems = ( $range * 2 ) + 1;

	if ( empty( $paged ) ) {

		$paged = 1;

	}

	if ( ! $pages ) {

		$pages = $wp_query->max_num_pages;

		if ( ! $pages ) {

			$pages = 1;

		}
	}

	if ( 1 != $pages ) {

		if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {

			$parts[] = array(
				'href'   => get_pagenum_link( 1 ),
				'text'   => '&laquo;',
				'active' => false,
			);

		}

		if ( $paged > 1 && $showitems < $pages ) {

			$parts[] = array(
				'href'   => get_pagenum_link( $paged - 1 ),
				'text'   => '&lsaquo;',
				'active' => false,
			);

		}

		for ( $i = 1; $i <= $pages; $i++ ) {

			if ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) {

				$active = ( $paged == $i ) ? true : false;

				$parts[] = array(
					'href'   => get_pagenum_link( $i ),
					'text'   => $i,
					'active' => $active,
				);

			}
		}

		if ( $paged < $pages && $showitems < $pages ) {

			$parts[] = array(
				'href'   => get_pagenum_link( $paged + 1 ),
				'text'   => '&rsaquo;',
				'active' => false,
			);

		}

		if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {

			$parts[] = array(
				'href'   => get_pagenum_link( $pages ),
				'text'   => '&raquo;',
				'active' => false,
			);

		}
	}

	/**
	 * Filters pagination parts.
	 *
	 * @see themeblvd_get_pagination()
	 *
	 * @since @@name-framework 2.3.0
	 *
	 * @param array $parts Parts to construct pagination.
	 * @param int   $pages Number of pages.
	 * @param int   $range Range for paginated buttons, helpful for many pages.
	 */
	return apply_filters( 'themeblvd_pagination_parts', $parts, $pages, $range );

}

/**
 * Get search result post types.
 *
 * This function serves as a helper for generating
 * the menu that shows on search results pages,
 * which lets the website visitor narrow the
 * search by post type.
 *
 * @see themeblvd_get_refine_search_menu()
 *
 * @since @@name-framework 2.5.0
 */
function themeblvd_get_search_types() {

	/*
	 * Because we need all the results, and not just
	 * the current page, we have to get the search
	 * results again.
	 */
	$results = new WP_Query( array(
		's'              => get_search_query(),
		'posts_per_page' => -1,
	) );

	$types = array();

	if ( $results->have_posts() ) {

		while ( $results->have_posts() ) {

			$results->the_post();

			$type = get_post_type();

			if ( ! isset( $types[ $type ] ) ) {

				$post_type = get_post_type_object( $type );

				$types[ $type ] = $post_type->labels->name;

			}
		}
	}

	wp_reset_postdata();

	return $types;

}

/**
 * Get CSS classes to be used with JavaScript
 * filtering navigation on posts.
 *
 * This function gets a value to output with a
 * JavaScript filtered post loop, to be triggered
 * from the menu outputted with themeblvd_filter_nav().
 *
 * @since @@name-framework 2.5.0
 *
 * @param  string $tax     Taxonomy we're sorting the showcase by.
 * @param  int    $post_id ID of current post.
 * @return string $value   Value to get used in HTML, like `filter-{$value}"`.
 */
function themeblvd_get_filter_val( $tax = 'category', $post_id = 0 ) {

	if ( ! $post_id ) {

		$post_id = get_the_ID();

	}

	$value = '';

	$terms = get_the_terms( $post_id, $tax );

	if ( $terms ) {

		foreach ( $terms as $term ) {

			$slug = esc_attr( preg_replace( '/[^a-zA-Z0-9._\-]/', '', $term->slug ) ); // Allow non-latin characters, and still work with jQuery.

			$value .= sprintf( 'filter-%s ', $slug );

		}

		$value = trim( $value );

	}

	/**
	 * Filters CSS classes to be used with JavaScript
	 * filtering navigation on posts.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $value   Value to get used in HTML, like `filter-{$value}"`.
	 * @param string $tax     Taxonomy we're sorting the showcase by.
	 * @param int    $post_id ID of current post.
	 */
	return apply_filters( 'themeblvd_filter_val', $value, $tax, $post_id );

}
