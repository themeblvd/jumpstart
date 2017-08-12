<?php
/**
 * General-Use Admin Functions
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     @@name-package
 */

/**
 * Initialize anything needed for admin panel
 * to run.
 *
 * @since @@name-framework 2.0.0
 */
function themeblvd_admin_init() {

	/*
	 * Add framework welcome message.
	 */
	$welcome = Theme_Blvd_Welcome::get_instance();

	/*
	 * Allow theme options page to run if framework filters
	 * have don't have it hidden it and user is capable.
	 */
	if ( themeblvd_supports( 'admin', 'options' ) && current_user_can( themeblvd_admin_module_cap( 'options' ) ) ) {

		/*
		 * Access Options API, instance should already be created.
		 */
		$api = Theme_Blvd_Options_API::get_instance();

		/*
		 * Option ID the theme options are registered and
		 * saved to. -- i.e. get_option( $option_name )
		 */
		$option_id = $api->get_option_id();

		/*
		 * All options constructed from framework and
		 * potentially added to by client API.
		 */
		$options = $api->get_formatted_options();

		/*
		 * Arguments for theme options admin page.
		 * Filterable with "themeblvd_theme_options_args"
		 */
		$args = $api->get_args();

		/*
		 * Setup theme options page.
		 */
		$options_page = new Theme_Blvd_Options_Page( $option_id, $options, $args );

	}

	/*
	 * Add options to WordPress's User Profile Edit pages,
	 * to allow for framework's author box on frontend.
	 */
	if ( themeblvd_supports( 'admin', 'user' ) ) {

		$user_options = Theme_Blvd_User_Options::get_instance();

	}

	/*
	 * Add framework post display options to editing
	 * category and tag pages.
	 */
	if ( themeblvd_supports( 'admin', 'tax' ) ) {

		$tax_options = Theme_Blvd_Tax_Options::get_instance();

	}

	/*
	 * Add menu styling options, mostly for mega menu
	 * functionaity to WordPress menu builder.
	 */
	if ( themeblvd_supports( 'admin', 'menus' ) ) {

		$menu_options = Theme_Blvd_Menu_Options::get_instance();

	}

}

/**
 * Save current version of framework to database.
 *
 * The framework hooks this to "admin_init" action
 * from /framework/themeblvd.php
 *
 * @since @@name-framework 2.3.0
 */
function themeblvd_update_version() {

	update_option( 'themeblvd_framework_version', TB_FRAMEWORK_VERSION );

}

/**
 * Setup non-modular admin assets.
 *
 * @since @@name-framework 2.0.0
 */
function themeblvd_non_modular_assets() {

	global $pagenow;

	/*
	 * Enqueue assets required for editing
	 * posts and pages.
	 */
	if ( 'post-new.php' === $pagenow || 'post.php' === $pagenow ) {

		wp_enqueue_style(
			'themeblvd_admin',
			esc_url( TB_FRAMEWORK_URI . '/admin/assets/css/admin-style.min.css' ),
			null,
			TB_FRAMEWORK_VERSION
		);

		wp_enqueue_style(
			'themeblvd_options',
			esc_url( TB_FRAMEWORK_URI . '/admin/options/css/admin-style.min.css' ),
			null,
			TB_FRAMEWORK_VERSION
		);

		wp_enqueue_script(
			'themeblvd_modal',
			esc_url( TB_FRAMEWORK_URI . '/admin/assets/js/modal.min.js' ),
			array( 'jquery' ),
			TB_FRAMEWORK_VERSION
		);

		wp_enqueue_script(
			'themeblvd_admin',
			esc_url( TB_FRAMEWORK_URI . '/admin/assets/js/shared.min.js' ),
			array( 'jquery' ),
			TB_FRAMEWORK_VERSION
		);

		wp_enqueue_script(
			'tb_meta_box-scripts',
			esc_url( TB_FRAMEWORK_URI . '/admin/assets/js/meta-box.min.js' ),
			array( 'jquery' ),
			TB_FRAMEWORK_VERSION
		);

		wp_localize_script(
			'tb_meta_box-scripts',
			'themeblvd',
			themeblvd_get_admin_locals( 'js' )
		);

	}

	/*
	 * Enqueue stylesheet needed for styling custom icons
	 * in primary admin sidebar, needed for entire WordPress
	 * admin panel.
	 *
	 * We're very careful here to only include CSS absolutely
	 * needed throughout the entire WordPress admin!
	 */
	wp_enqueue_style(
		'tb_admin_global',
		esc_url( TB_FRAMEWORK_URI . '/admin/assets/css/admin-menu.min.css' ),
		null,
		TB_FRAMEWORK_VERSION
	);

}

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
 * Hijack and modify default WP's "Page Attributes"
 * meta box.
 *
 * @since @@name-framework 2.0.0
 */
function themeblvd_hijack_page_atts() {

	if ( themeblvd_supports( 'meta', 'hijack_atts' ) ) {

		remove_meta_box( 'pageparentdiv', 'page', 'side' );
		add_meta_box( 'themeblvd_pageparentdiv', __( 'Page Attributes', '@@text-domain' ), 'themeblvd_page_attributes_meta_box', 'page', 'side', 'core' );

	}

}

/**
 * Saved data from Hi-jacked "Page Attributes"
 * meta box.
 *
 * @since @@name-framework 2.0.0
 */
function themeblvd_save_page_atts( $post_id ) {

	if ( themeblvd_supports( 'meta', 'hijack_atts' ) ) {

		if ( isset( $_POST['_tb_sidebar_layout'] ) ) {

			// Save sidebar layout.
			update_post_meta(
				$post_id,
				'_tb_sidebar_layout',
				$_POST['_tb_sidebar_layout']
			);

		}

		if ( isset( $_POST['_tb_custom_layout'] ) ) { // Backwards compat.

			// Save custom layout.
			update_post_meta(
				$post_id,
				'_tb_custom_layout',
				$_POST['_tb_custom_layout']
			);

		}
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
	 * @param array $types Types of backgrounds.
	 */
	return apply_filters( "themeblvd_{$context}_bg_types", $types );

}
