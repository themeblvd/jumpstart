<?php
/**
 * Admin Helper Functions
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */

/**
 * Gather all assignments for posts into a single
 * array organized by post ID.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param  array $posts       All posts from WP's get_posts().
 * @return array $assignments Assignments from all posts organized by ID.
 */
function themeblvd_get_assignment_conflicts( $posts ) {

	$non_conflicts = array();

	$conflicts = array();

	$locations = themeblvd_get_sidebar_locations();

	foreach ( $locations as $location ) {

		$conflicts[ $location['location']['id'] ] = array();

		$non_conflicts[ $location['location']['id'] ] = array();

	}

	/*
	 * Loop through sidebar posts to construct two arrays side-by-side.
	 * As we build the $non_conflicts arrays, we will be able to build
	 * the $conflicts arrays off to the side by checking if items already
	 * exist in the $non_conflicts.
	 */
	foreach ( $posts as $post ) {

		// Determine location sidebar is assigned to.
		$location = get_post_meta( $post->ID, 'location', true );

		/*
		 * Only run check if a location exists and this
		 * is not a floating widget area.
		 */
		if ( $location && 'floating' !== $location ) {

			$assignments = get_post_meta( $post->ID, 'assignments', true );

			if ( is_array( $assignments ) && ! empty( $assignments ) ) {

				foreach ( $assignments as $key => $assignment ) {

					if ( 'custom' !== $key && in_array( $key, $non_conflicts[ $location ] ) ) {

						if ( ! in_array( $key, $conflicts[ $location ] ) ) {

							$conflicts[ $location ][] = $key;

						}
					} else {

						$non_conflicts[ $location ][] = $key;

					}
				}
			}
		}
	}

	return $conflicts;

}

/**
 * Saved any data we've added to the Page Attributes
 * meta box of WordPress.
 *
 * Currently this just includes an option for a sidebar
 * layout selection, when editing pages.
 *
 * This function is hooked to:
 * 1. `save_post_page` - 10
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param int $post_id Post ID for post being saved.
 */
function themeblvd_save_page_atts( $post_id ) {

	if ( ! isset( $_POST['_tb_sidebar_layout'] ) ) {

		return;

	}

	check_admin_referer(
		'themeblvd-save-page-atts_' . $post_id,
		'themeblvd-save-page-atts_' . $post_id
	);

	$layouts = array_merge(
		array(
			'default' => null,
		),
		themeblvd_sidebar_layouts()
	);

	if ( array_key_exists( $_POST['_tb_sidebar_layout'], $layouts ) ) {

		update_post_meta(
			$post_id,
			'_tb_sidebar_layout',
			$_POST['_tb_sidebar_layout']
		);

	}

}

/**
 * Clear set of options.
 *
 * This function is hooked to:
 * 1. `admin_init` - 10
 *
 * @since Theme_Blvd 2.3.0
 */
function themeblvd_clear_options() {

	if ( ! isset( $_POST['themeblvd_clear_options'] ) ) {

		return;

	}

	if ( ! current_user_can( 'edit_theme_options' ) ) {

		return;

	}

	check_admin_referer( $_POST['option_page'] . '-options' );

	delete_option( $_POST['themeblvd_clear_options'] );

	add_settings_error(
		$_POST['themeblvd_clear_options'] ,
		'clear_defaults',
		__( 'Options cleared from database.', 'jumpstart' ),
		'themeblvd-error error'
	);

}

/**
 * Get background selection types.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $context Context in which BG is being applied.
 * @return array  $types   Background types to select from.
 */
function themeblvd_get_bg_types( $context = 'section' ) {

	$types = array(
		'none'      => __( 'No background', 'jumpstart' ),
		'color'     => __( 'Custom color', 'jumpstart' ),
		'texture'   => __( 'Custom color + texture', 'jumpstart' ),
		'image'     => __( 'Custom color + image', 'jumpstart' ),
		'slideshow' => __( 'Custom image slideshow', 'jumpstart' ),
		'video'     => __( 'Custom video', 'jumpstart' ),
	);

	if ( 'section' !== $context ) {

		unset( $types['slideshow'] );

	}

	if ( 'basic' === $context ) {

		unset( $types['slideshow'] );

		unset( $types['video'] );

	}

	/**
	 * Filters the types of backgrounds that can be
	 * selected from when setting up display options
	 * in different contexts.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param array $types Types of backgrounds.
	 */
	return apply_filters( "themeblvd_{$context}_bg_types", $types );

}

/**
 * Generates array to be used in a select option
 * type of the options framework.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param  string $type   Type of <select> to prepare.
 * @return array  $select Items prepared for <select>.
 */
function themeblvd_get_select( $type, $force_single = false ) {

	// Add WPML compatibility.
	if ( isset( $GLOBALS['sitepress'] ) ) {

		remove_filter( 'get_pages', array( $GLOBALS['sitepress'], 'exclude_other_language_pages2' ) );

		remove_filter( 'get_terms_args', array( $GLOBALS['sitepress'], 'get_terms_args_filter' ) );

		remove_filter( 'terms_clauses', array( $GLOBALS['sitepress'], 'terms_clauses' ) );

	}

	$select = array();

	switch ( $type ) {

		case 'pages':
			$pages = get_pages();

			if ( ! empty( $pages ) ) {

				foreach ( $pages as $page ) {

					$select[ $page->post_name ] = $page->post_title;

				}
			} else {

				$select['null'] = __( 'No pages exist.', 'jumpstart' );

			}

			break;

		case 'categories':
			$select['all'] = __( '<strong>All Categories</strong>', 'jumpstart' );

			$categories = get_categories( array(
				'hide_empty' => false,
			) );

			foreach ( $categories as $category ) {

				$select[ $category->slug ] = $category->name;

			}

			break;

		case 'sidebars':
			$sidebars = get_posts( 'post_type=tb_sidebar&numberposts=-1' );

			if ( ! empty( $sidebars ) ) {

				foreach ( $sidebars as $sidebar ) {

					$location = get_post_meta( $sidebar->ID, 'location', true );

					if ( 'floating' === $location ) {

						$select[ $sidebar->post_name ] = $sidebar->post_title;

					}
				}
			} // Handle error message for no sidebars outside of this function.

			break;

		case 'sidebars_all':
			global $wp_registered_sidebars;

			if ( ! empty( $wp_registered_sidebars ) ) {

				foreach ( $wp_registered_sidebars as $sidebar ) {

					/*
					 * Exclude collapsible widget area locations,
					 * which already show in a custom layout.
					 *
					 * Because this select menu is intended to be
					 * used with custom layouts, `ad_above_header`
					 * and `ad_below_footer` display already and so
					 * it wouldn't really make sense (in most cases)
					 * to include them in a custom layout.
					 */
					if ( in_array( $sidebar['id'], array( 'ad_above_header', 'ad_below_footer' ) ) ) {

						continue;

					}

					/*
					 * Remove the word "Location" to avoid confusion,
					 * as the concept of locations doesn't really apply
					 * in this instance.
					 */
					$name = str_replace(
						__( 'Location:', 'jumpstart' ),
						__( 'Default', 'jumpstart' ),
						$sidebar['name']
					);

					$select[ $sidebar['id'] ] = $name;

				}
			}

			break;

		// All registered crop sizes
		case 'crop':
			$registered = get_intermediate_image_sizes();

			$atts = $GLOBALS['_wp_additional_image_sizes'];

			$select['full'] = __( 'Full Size','jumpstart' );

			foreach ( $registered as $size ) {

				// Skip some sizes
				// if ( in_array( $size, array('thumbnail', 'tb_thumb' ) ) ) {
				// 	continue;
				// }

				// Determine width, height, and crop mode
				if ( isset( $atts[ $size ]['width'] ) ) {

					$width = intval( $atts[ $size ]['width'] );

				} else {

					$width = get_option( "{$size}_size_w" );

				}

				if ( isset( $atts[ $size ]['height'] ) ) {

					$height = intval( $atts[ $size ]['height'] );

				} else {

					$height = get_option( "{$size}_size_h" );

				}

				if ( isset( $atts[ $size ]['crop'] ) ) {

					$crop = intval( $atts[ $size ]['crop'] );

				} else {

					$crop = get_option( "{$size}_crop" );

				}

				// Crop mode message.
				if ( $crop ) {

					$crop_desc = __( 'hard crop', 'jumpstart' );

				} elseif ( 9999 == $height ) {

					$crop_desc = __( 'no height crop', 'jumpstart' );

				} else {

					$crop_desc = __( 'soft crop', 'jumpstart' );

				}

				// Piece it all together.
				$select[ $size ] = sprintf( '%s (%d x %d, %s)', $size, $width, $height, $crop_desc );

			}

			break;

		case 'textures':
			$textures = themeblvd_get_textures();

			if ( $force_single ) {

				foreach ( $textures as $texture_id => $texture ) {

					$select[ $texture_id ] = $texture['name'];

				}
			} else {

				$select = array(
					'dark' => array(
						'label'   => __( 'For Darker Background Color', 'jumpstart' ),
						'options' => array(),
					),
					'light' => array(
						'label'   => __( 'For Lighter Background Color', 'jumpstart' ),
						'options' => array(),
					),
				);

				$optgroup = 'dark';

				foreach ( $textures as $texture_id => $texture ) {

					if ( 'divider' === $texture_id ) {

						$optgroup = 'light';

						continue;

					}

					$select[ $optgroup ]['options'][ $texture_id ] = $texture['name'];

				}
			}

			break;

		case 'templates':
			$templates = get_posts( 'post_type=tb_layout&orderby=title&order=ASC&numberposts=-1' );

			if ( $templates ) {

				foreach ( $templates as $template ) {

					$select[ $template->post_name ] = $template->post_title;

				}
			}

			break;

		case 'authors':
			$users = get_users();

			if ( $users ) {

				foreach ( $users as $user ) {

					$select[ $user->user_login ] = sprintf( '%s (%s)', $user->display_name, $user->user_login );

				}
			}
	}

	// Put WPML filters back.
	if ( isset( $GLOBALS['sitepress'] ) ) {

		add_filter( 'get_pages', array( $GLOBALS['sitepress'], 'exclude_other_language_pages2' ), 10, 2 );

		add_filter( 'get_terms_args', array( $GLOBALS['sitepress'], 'get_terms_args_filter' ), 10, 2 );

		add_filter( 'terms_clauses', array( $GLOBALS['sitepress'], 'terms_clauses' ), 10, 4 );

	}

	return $select;

}
