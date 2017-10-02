<?php
/**
 * Admin Helper Functions
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */

/**
 * Gather all assignments for posts into a single
 * array organized by post ID.
 *
 * @since @@name-framework 2.0.0
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
 * @since @@name-framework 2.0.0
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
 * Determine if current admin page is an admin
 * module page in our framework.
 *
 * @since @@name-framework 2.2.0
 *
 * @return bool Whether the current admin page is on of our admin module.
 */
function themeblvd_is_admin_module() {

	global $pagenow;
	global $_GET;

	$current = $pagenow;

	if ( isset( $_GET['page'] ) ) {
		$current .= sprintf( '?page=%s', $_GET['page'] );
	}

	return in_array( $current, themeblvd_get_admin_modules() );

}

/**
 * Clear set of options. Hooked to "admin_init".
 *
 * @since @@name-framework 2.3.0
 */
function themeblvd_clear_options() {

	if ( isset( $_POST['themeblvd_clear_options'] ) ) {

		check_admin_referer( $_POST['option_page'] . '-options' );

		delete_option( $_POST['themeblvd_clear_options'] );

		add_settings_error(
			$_POST['themeblvd_clear_options'] ,
			'clear_defaults',
			__( 'Options cleared from database.', '@@text-domain' ),
			'themeblvd-error error'
		);

	}

}

/**
 * Get array of framework icons.
 *
 * @since @@name-framework 2.3.0
 *
 * @param  string $type  Type of icons to retrieve, currently only "vector".
 * @return array  $icons Array of icons.
 */
function themeblvd_get_icons( $type = 'vector' ) {

	$icons = get_transient( 'themeblvd_' . get_template() . '_' . $type . '_icons' );

	if ( ! $icons ) {

		$icons = array();

		$fetch_icons = array();

		$file = wp_remote_fopen( esc_url( TB_FRAMEWORK_URI . '/assets/plugins/fontawesome/css/font-awesome.css' ) );

		if ( $file ) {

			/*
			 * Run through each line of font-awesome.css, and
			 * look for anything that could resemble a font ID.
			 */
			$lines = explode( "\n", $file );

			foreach ( $lines as $line ) {

				if ( false !== strpos( $line, '.fa-' ) && false !== strpos( $line, ':before' ) ) {

					$icon = str_replace( '.fa-', '', $line );

					$icon = str_replace( ':before {', '', $icon );

					$icon = str_replace( ':before,', '', $icon );

					$fetch_icons[] = trim( $icon );

				}
			}

			sort( $fetch_icons ); // Sort alphabetically.

			foreach ( $fetch_icons as $icon ) {
				$icons[ $icon ] = $icon;
			}
		}

		/*
		 * We'll store our result in a 1-day transient.
		 */
		set_transient( 'themeblvd_' . get_template() . '_' . $type . '_icons', $icons, '86400' );

	}

	/**
	 * Filters the array of icons that the user can
	 * select from in the icon browser.
	 *
	 * @since @@name-framework 2.3.0
	 *
	 * @param array $icons All icons found from fontawesome.css.
	 */
	return apply_filters( 'themeblvd_' . $type . '_icons', $icons );

}

/**
 * Get background selection types.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  string $context Context in which BG is being applied.
 * @return array  $types   Background types to select from.
 */
function themeblvd_get_bg_types( $context = 'section' ) {

	$types = array(
		'none'      => __( 'No background', '@@text-domain' ),
		'color'     => __( 'Custom color', '@@text-domain' ),
		'texture'   => __( 'Custom color + texture', '@@text-domain' ),
		'image'     => __( 'Custom color + image', '@@text-domain' ),
		'slideshow' => __( 'Custom image slideshow', '@@text-domain' ),
		'video'     => __( 'Custom video', '@@text-domain' ),
	);

	if ( 'section' !== $context ) {
		unset( $types['slideshow'] );
	}

	if ( 'section' === $context || 'banner' === $context || 'jumbotron' === $context ) {

		if ( themeblvd_supports( 'featured', 'style' ) ) {
			$types['featured'] = __( 'Theme\'s preset "Featured" area background', '@@text-domain' );
		}

		if ( themeblvd_supports( 'featured_below', 'style' ) ) {
			$types['featured_below'] = __( 'Theme\'s preset "Featured Below" area background', '@@text-domain' );
		}
	}

	if ( 'banner' === $context ) {
		$types['none'] = __( 'No banner', '@@text-domain' );
	}

	/**
	 * Filters the types of backgrounds that can be
	 * selected from when setting up display options
	 * in different contexts.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array $types Types of backgrounds.
	 */
	return apply_filters( "themeblvd_{$context}_bg_types", $types );

}
