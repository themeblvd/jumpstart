<?php
/**
 * Frontend Blocks: Archives and Search
 * Results
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.7.0
 */

/**
 * Get an archive info box block.
 *
 * This info box displays the title and description
 * for the first page of taxonomy term archives.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_tax_info() {

	global $wp_query;

	$output = '';

	if ( ! is_category() && ! is_tag() && ! is_tax() ) {

		return $output;

	}

	/*
	 * Only display the info box, if we're on the first
	 * page of the archive.
	 */
	if ( get_query_var( 'paged' ) >= 2 ) {

		return $output;

	}

	if ( is_tax() ) {

		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

	} else {

		$term = $wp_query->get_queried_object();

	}

	if ( ! $term ) {

		return $output;

	}

	$name = '';

	$desc = '';

	/**
	 * Filters the title of a taxonomy term archive
	 * info box block.
	 *
	 * Taxonomy info boxes get printed at the top
	 * of the first page of taxonomy archives.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string       Name of taxonomy term of archive.
	 * @param object $term Taxonomy term data.
	 */
	$name = apply_filters( 'themeblvd_tax_info_name', esc_html( $term->name ), $term );

	if ( $name ) {

		$name = sprintf( '<h1 class="info-box-title archive-title">%s</h1>', $name );

	}

	if ( $term->description ) {

		/**
		 * Filters the description of a taxonomy term
		 * archive info box block.
		 *
		 * Taxonomy info boxes get printed at the top
		 * of the first page of taxonomy archives.
		 *
		 * @since Theme_Blvd 2.5.0
		 *
		 * @param string       Name of taxonomy term of archive.
		 * @param object $term Taxonomy term data.
		 */
		$desc = apply_filters( 'themeblvd_tax_info_desc', themeblvd_get_content( $term->description ), $term );

	}

	/**
	 * Filters the CSS class added to a taxonomy term
	 * archive info box block.
	 *
	 * This filter is helpful for the framework to filter
	 * on the `bg-content` class to this and similarly
	 * styled info boxes.
	 *
	 * Taxonomy info boxes get printed at the top
	 * of the first page of taxonomy archives.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string       CSS classes for info box like `foo bar baz`.
	 * @param object $term Taxonomy term data.
	 */
	$class = apply_filters( 'themeblvd_tax_info_class', 'tb-info-box tb-tax-info', $term ); // Filtering to allow "content-bg" to be added

	if ( $name || $desc ) {

		$output = sprintf( '<section class="%s"><div class="inner">%s</div></section>', $class, $name . $desc );

	}

	/**
	 * Filters the final HTML output for an archive
	 * info box block.
	 *
	 * Taxonomy info boxes get printed at the top
	 * of the first page of taxonomy archives.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param object $term   Taxonomy term data.
	 */
	return apply_filters( 'themeblvd_tax_info', $output, $term );

}

/**
 * Display an archive info box block.
 *
 * This info box displays the title and description
 * for the first page of taxonomy term archives.
 *
 * @since Theme_Blvd 2.5.0
 */
function themeblvd_tax_info() {

	echo themeblvd_get_tax_info();

}

/**
 * Get an author archive info box block.
 *
 * This info box displays the gravatar, name, bio
 * and contact icons for the author of an author
 * posts archive or a single post.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  object $user    Author user data.
 * @param  string $context Where the author box is displaying, `single` or `archive`.
 * @return string $output  Final HTML output for block.
 */
function themeblvd_get_author_info( $user, $context = 'single' ) {

	/**
	 * Filters the author gravatar image size
	 * for an author archive info box block.
	 *
	 * Author info boxes get printed at the top of the
	 * first page of author archives and the bottom
	 * of single posts (if enabled).
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param int             Size of gravatar for get_avatar(), like `70`.
	 * @param object $user    Author user data.
	 * @param string $context Where the author box is displaying, `single` or `archive`.
	 */
	$gravatar_size = apply_filters( 'themeblvd_author_box_gravatar_size', 70, $user, $context );

	$gravatar = get_avatar( $user->user_email, $gravatar_size );

	$desc = get_user_meta( $user->ID, 'description', true );

	/**
	 * Filters the author gravatar image size for an
	 * author archive info box block.
	 *
	 * This filter is helpful for the framework to filter
	 * on the `bg-content` class to this and similarly
	 * styled info boxes.
	 *
	 * Author info boxes get printed at the top of the
	 * first page of author archives and the bottom
	 * of single posts (if enabled).
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string          CSS classes for info box like `foo bar baz`.
	 * @param object $user    Author user data.
	 * @param string $context Where the author box is displaying, `single` or `archive`.
	 */
	$class = apply_filters( 'themeblvd_tax_info_class', 'tb-info-box tb-author-box ' . $context, $user, $context );

	$output = sprintf( '<section class="%s">', $class );

	/**
	 * Filters the author name (i.e. title) for an author
	 * archive info box block.
	 *
	 * This filter is helpful for the framework to filter
	 * on the `bg-content` class to this and similarly
	 * styled info boxes.
	 *
	 * Author info boxes get printed at the top of the
	 * first page of author archives and the bottom
	 * of single posts (if enabled).
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string          Post author name.
	 * @param object $user    Author user data.
	 * @param string $context Where the author box is displaying, `single` or `archive`.
	 */
	$title = apply_filters( 'themeblvd_author_info_title', $user->display_name, $user, $context );

	if ( 'archive' === $context ) {

		$output .= sprintf(
			'<h1 class="info-box-title archive-title">%s</h1>',
			esc_html( $title )
		);

	} else {

		$output .= sprintf(
			'<h3 class="info-box-title">%s</h3>',
			esc_html( $title )
		);

	}

	$output .= '<div class="inner">';

	if ( $gravatar ) {

		/**
		 * Filters the CSS class added to a gravatar for an
		 * author archive info box block.
		 *
		 * Author info boxes get printed at the top of the
		 * first page of author archives and the bottom
		 * of single posts (if enabled).
		 *
		 * @since Theme_Blvd 2.5.0
		 *
		 * @param string          CSS classes for info box like `foo bar baz`.
		 * @param object $user    Author user data.
		 * @param string $context Where the author box is displaying, `single` or `archive`.
		 */
		$class = apply_filters( 'themeblvd_author_box_avatar_class', 'avatar-wrap', $user, $context );

		$output .= sprintf( '<div class="%s">%s</div>', $class, $gravatar );

	}

	if ( 'single' === $context && '1' === get_user_meta( $user->ID, '_tb_box_archive_link', true ) ) {

		$text = sprintf( themeblvd_get_local( 'view_posts_by' ), esc_html( $user->display_name ) );

		$desc .= "\n\n";

		$desc .= sprintf( '<a href="%s" class="view-posts-link">%s</a>', esc_url( get_author_posts_url( $user->ID ) ), $text );

	}

	if ( $desc ) {

		$output .= themeblvd_get_content( $desc );

	}

	$style = get_user_meta( $user->ID, '_tb_box_icons', true );

	if ( ! $style ) {

		if ( themeblvd_supports( 'display', 'dark' ) ) {

			$style = 'light';

		} else {

			$style = 'grey';

		}
	}

	$icons = Theme_Blvd_User_Options::get_icons();

	$display = array();

	if ( '1' === get_user_meta( $user->ID, '_tb_box_email', true ) ) {

		$display[] = array(
			'icon'   => 'email',
			'url'    => 'mailto:' . $user->user_email,
			'label'  => themeblvd_get_local( 'email' ),
			'target' => '_self',
		);

	}

	foreach ( $icons as $icon_id => $info ) {

		$url = get_user_meta( $user->ID, $info['key'], true );

		if ( 'website' === $icon_id ) {

			$icon_id = 'anchor';

			$url = $user->user_url;

		}

		if ( $url ) {

			$display[ $icon_id ] = array(
				'icon'   => $icon_id,
				'url'    => $url,
				'target' => '_blank',
			);

			if ( 'anchor' === $icon_id ) {

				$display[ $icon_id ]['label'] = $icons['website']['label'];

			} else {

				$display[ $icon_id ]['label'] = $icons[ $icon_id ]['label'];

			}
		}
	}

	if ( $display ) {

		$output .= themeblvd_get_contact_bar( $display, array(
			'style'      => $style,
			'tooltip'    => 'top',
			'class'      => 'author-box',
			'authorship' => true,
		) );

	}

	$output .= '</div><!-- .inner (end) -->';

	$output .= '</section><!-- .tb-author-box (end) -->';

	/**
	 * Filters the CSS class added to a gravatar for an
	 * author archive info box block.
	 *
	 * Author info boxes get printed at the top of the
	 * first page of author archives and the bottom
	 * of single posts (if enabled).
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output  Final HTML output.
	 * @param object $user    Author user data.
	 * @param string $context Where the author box is displaying, `single` or `archive`.
	 */
	return apply_filters( 'themeblvd_author_info', $output, $user, $context );

}

/**
 * Display an archive info box block.
 *
 * This info box displays the title and description
 * for the first page of taxonomy term archives.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param object $user    Author user data.
 * @param string $context Where the author box is displaying, `single` or `archive`.
 */
function themeblvd_author_info( $user, $context = 'single' ) {

	echo themeblvd_get_author_info( $user, $context );

}

/**
 * Get a post type navigation block, used to
 * refine search results.
 *
 * @since Theme_Blvd 2.5.0
 */
function themeblvd_get_refine_search_menu() {

	$output = '';

	$types = themeblvd_get_search_types();

	if ( $types ) {

		$active = 'all';

		if ( ! empty( $_GET['s_type'] ) ) {

			$active = $_GET['s_type'];

		}

		$url = add_query_arg(
			array(
				's' => str_replace( ' ', '+', get_search_query() ),
			),
			themeblvd_get_home_url()
		);

		$output .= '<div class="tb-inline-menu">';

		$output .= '<ul class="list-inline search-refine-menu">';

		if ( 'all' === $active ) {

			$output .= sprintf(
				'<li><span class="active">%s</span></li>',
				themeblvd_get_local( 'all' )
			);

		} else {

			$output .= sprintf(
				'<li><a href="%s">%s</a></li>',
				esc_url( $url ),
				themeblvd_get_local( 'all' )
			);

		}

		foreach ( $types as $type => $name ) {

			if ( $active == $type ) {

				$output .= sprintf(
					'<li><span class="active">%s</span></li>',
					esc_html( $name )
				);

			} else {

				$url = add_query_arg(
					array(
						's_type' => esc_attr( $type ),
					),
					$url
				);

				$output .= sprintf(
					'<li><a href="%s">%s</a></li>',
					esc_url( $url ),
					esc_html( $name )
				);

			}
		}

		$output .= '</ul>';

		$output .= '</div><!-- .tb-inline-mini (end) -->';

	}

	/**
	 * Filters a post type navigation block, used to
	 * refine search results.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $types  Post types present in search results.
	 */
	return apply_filters( 'themeblvd_refine_search_menu', $output, $types );

}

/**
 * Display a post type navigation block, used to
 * refine search results.
 *
 * @since Theme_Blvd 2.5.0
 */
function themeblvd_refine_search_menu() {

	echo themeblvd_get_refine_search_menu();

}
