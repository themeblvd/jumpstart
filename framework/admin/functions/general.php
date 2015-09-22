<?php
/**
 * Initialize anything needed for admin panel to run.
 *
 * @since 2.0.0
 */
function themeblvd_admin_init() {

	// Allow theme options page to run if framework filters
	// have don't have it hidden it and user is capable.
	if ( themeblvd_supports('admin', 'options') && current_user_can( themeblvd_admin_module_cap( 'options' ) ) ) {

		// Access Options API, instance should already be created.
		$api = Theme_Blvd_Options_API::get_instance();

		// Option ID the theme options are registered and
		// saved to. -- i.e. get_option( $option_name )
		$option_id = $api->get_option_id();

		// All options constructed from framework and
		// potentially added to by client API.
		$options = $api->get_formatted_options();

		// Arguments for theme options admin page.
		// Filterable with "themeblvd_theme_options_args"
		$args = $api->get_args();

		// Theme Options Page
		$options_page = new Theme_Blvd_Options_Page( $option_id, $options, $args );

	}

	// User profile options
	if ( themeblvd_supports('admin', 'user') ) {
		$user_options = Theme_Blvd_User_Options::get_instance();
	}

	// Category/Tag options
	if ( themeblvd_supports('admin', 'tax') ) {
		$tax_options = Theme_Blvd_Tax_Options::get_instance();
	}

	// Menu options
	if ( themeblvd_supports('admin', 'menus') ) {
		$menu_options = Theme_Blvd_Menu_Options::get_instance();
	}

}

/**
 * Save current version of framework to DB.
 *
 * @since 2.3.0
 */
function themeblvd_update_version() {
	update_option( 'themeblvd_framework_version', TB_FRAMEWORK_VERSION );
}

if ( !function_exists( 'themeblvd_non_modular_assets' ) ) :
/**
 * Non-modular Admin Assets
 *
 * @since 2.0.0
 */
function themeblvd_non_modular_assets() {

	global $pagenow;

	// Assets for editing posts
	if ( $pagenow == 'post-new.php' || $pagenow == 'post.php' ) {
		wp_enqueue_style( 'themeblvd_admin', esc_url( TB_FRAMEWORK_URI . '/admin/assets/css/admin-style.min.css' ), null, TB_FRAMEWORK_VERSION );
		wp_enqueue_style( 'themeblvd_options', esc_url( TB_FRAMEWORK_URI . '/admin/options/css/admin-style.min.css' ), null, TB_FRAMEWORK_VERSION );
		wp_enqueue_script( 'themeblvd_modal', esc_url( TB_FRAMEWORK_URI . '/admin/assets/js/modal.min.js' ), array('jquery'), TB_FRAMEWORK_VERSION );
		wp_enqueue_script( 'themeblvd_admin', esc_url( TB_FRAMEWORK_URI . '/admin/assets/js/shared.min.js' ), array('jquery'), TB_FRAMEWORK_VERSION );
		wp_enqueue_script( 'tb_meta_box-scripts', esc_url( TB_FRAMEWORK_URI . '/admin/assets/js/meta-box.min.js' ), array('jquery'), TB_FRAMEWORK_VERSION );
		wp_localize_script( 'tb_meta_box-scripts', 'themeblvd', themeblvd_get_admin_locals('js') );
	}

	// Includes Theme Blvd admin icon font
	wp_enqueue_style( 'tb_admin_global', esc_url( TB_FRAMEWORK_URI . '/admin/assets/css/admin-global.min.css' ), null, TB_FRAMEWORK_VERSION );

}
endif;

/**
 * Gather all assignments for posts into a single
 * array organized by post ID.
 *
 * @since 2.0.0
 *
 * @param $posts array all posts from WP's get_posts()
 * @return $assignments array assignments from all posts organized by ID
 */
function themeblvd_get_assignment_conflicts( $posts ) {

	// Setup $conflicts/$non_conflicts arrays
	$non_conflicts = array();
	$conflicts = array();
	$locations = themeblvd_get_sidebar_locations();
	foreach ( $locations as $location ) {
		$conflicts[$location['location']['id']] = array();
		$non_conflicts[$location['location']['id']] = array();
	}

	// Loop through sidebar posts to construct two arrays side-by-side.
	// As we build the $non_conflicts arrays, we will be able to build
	// the $conflicts arrays off to the side by checking if items already
	// exist in the $non_conflicts.
	foreach ( $posts as $post ) {

		// Determine location sidebar is assigned to.
		$location = get_post_meta( $post->ID, 'location', true );

		// Only run check if a location exists and this
		// is not a floating widget area.
		if ( $location && $location != 'floating' ) {

			$assignments = get_post_meta( $post->ID, 'assignments', true );

			if ( is_array( $assignments ) && ! empty( $assignments ) ) {
				foreach ( $assignments as $key => $assignment ) {

					if ( $key != 'custom' && in_array( $key, $non_conflicts[$location] ) ) {
						if ( ! in_array( $key, $conflicts[$location] ) ) {
							$conflicts[$location][] = $key;
						}
					} else {
						$non_conflicts[$location][] = $key;
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
 * @since 2.0.0
 */
function themeblvd_hijack_page_atts() {
	if ( themeblvd_supports( 'meta', 'hijack_atts' ) ) {
		remove_meta_box( 'pageparentdiv', 'page', 'side' );
		add_meta_box( 'themeblvd_pageparentdiv', __('Page Attributes', 'themeblvd'), 'themeblvd_page_attributes_meta_box', 'page', 'side', 'core' );
	}
}

/**
 * Saved data from Hi-jacked "Page Attributes"
 * meta box.
 *
 * @since 2.0.0
 */
function themeblvd_save_page_atts( $post_id ) {
	if ( themeblvd_supports( 'meta', 'hijack_atts' ) ) {

		// Save sidebar layout
		if ( isset( $_POST['_tb_sidebar_layout'] ) ) {
			update_post_meta( $post_id, '_tb_sidebar_layout', $_POST['_tb_sidebar_layout'] );
		}

		// Save custom layout
		if ( isset( $_POST['_tb_custom_layout'] ) ) { // backwards compat
			update_post_meta( $post_id, '_tb_custom_layout', $_POST['_tb_custom_layout'] );
		}

	}
}

/**
 * Determine if current admin page is an admin
 * module page.
 *
 * @since 2.2.0
 */
function themeblvd_is_admin_module() {

	global $pagenow;
	global $_GET;

	// Current page
	$current_page = $pagenow;
	if ( isset( $_GET['page'] ) ) {
		$current_page .= sprintf( '?page=%s', $_GET['page'] );
	}

	// Get admin modules
	$modules = themeblvd_get_admin_modules();

	return in_array( $current_page, $modules );
}

/**
 * Clear set of options. Hooked to "admin_init".
 *
 * @since 2.3.0
 */
function themeblvd_clear_options() {
	if ( isset( $_POST['themeblvd_clear_options'] ) ) {
		check_admin_referer( $_POST['option_page'].'-options' );
		delete_option( $_POST['themeblvd_clear_options'] );
		add_settings_error( $_POST['themeblvd_clear_options'] , 'clear_defaults', __('Options cleared from database.', 'themeblvd'), 'themeblvd-error error' );
	}
}

if ( !function_exists( 'themeblvd_admin_content_width' ) ) :
/**
 * Adjust frontend content width for admin panel.
 *
 * This is a little ironic, as $content_width is only for
 * the frontend of the site. This was originally implemented
 * so videos can be displayed at a reasonable size with WP 3.6+
 * in the admin panel when editing a video format post.
 *
 * @since 2.2.1
 */
function themeblvd_admin_content_width() {
	global $content_width;
	$content_width = 600;
}
endif;

/**
 * Integrate 3.8+ admin styling.
 *
 * This function will help the transition period as we go
 * from MP6 to these new admin styles being incorporated
 * into WP core.
 *
 * @todo Remove sometime after WP 3.8 has been released. No rush.
 *
 * @since 2.4.0
 */
function themeblvd_admin_body_class( $classes ) {

	global $wp_version;

	// If WordPress 3.8+, add themeblvd-ui class
	if ( version_compare( floatval( $wp_version ), '3.8', '>=' ) ) {

		$classes = explode( " ", $classes );

	    if ( ! in_array( 'themeblvd-ui', $classes ) ) {
	        $classes[] = 'themeblvd-ui';
	    }

	    $classes = implode( " ", $classes );
	}

	return $classes;
}

/**
 * Get array of framework icons
 *
 * @since 2.3.0
 *
 * @param string $type Type of icons to retrieve - currently only "vector"
 * @return array $icons Array of icons
 */
function themeblvd_get_icons( $type = 'vector' ) {

	$icons = get_transient( 'themeblvd_'.get_template().'_'.$type.'_icons' );

	if ( ! $icons ) {

		$icons = array();
		$fetch_icons = array();
		$file = wp_remote_fopen( esc_url( TB_FRAMEWORK_URI . '/assets/plugins/fontawesome/css/font-awesome.css' ) );

		if ( $file ) {

			// Run through each line of font-awesome.css, and
			// look for anything that could resemble a font ID.
			$lines = explode("\n", $file);

			foreach ( $lines as $line ) {
				if ( strpos( $line, '.fa-' ) !== false && strpos( $line, ':before' ) !== false ) {
					$icon = str_replace( '.fa-', '', $line );
					$icon = str_replace( ':before {', '', $icon );
					$icon = str_replace( ':before,', '', $icon );
					$fetch_icons[] = trim( $icon );
				}
			}

			// Sort icons alphebetically
			sort( $fetch_icons );

			// Format array for use in options framework
			foreach ( $fetch_icons as $icon ) {
				$icons[$icon] = $icon;
			}

		}

		// Cache result
		set_transient( 'themeblvd_'.get_template().'_'.$type.'_icons', $icons, '86400' ); // 1 day

	}

	return apply_filters( 'themeblvd_'.$type.'_icons', $icons );
}

/**
 * Get background selection types
 *
 * @since 2.5.0
 *
 * @param string $context Context in which BG is being applied
 * @return array $types Background types to select from
 */
function themeblvd_get_bg_types( $context = 'section' ) {

	$types = array(
		'none'		=> __('No background', 'themeblvd'),
		'color'		=> __('Custom color', 'themeblvd'),
		'texture'	=> __('Custom color + texture', 'themeblvd'),
		'image'		=> __('Custom color + image', 'themeblvd'),
		'slideshow'	=> __('Custom image slideshow', 'themeblvd'),
		'video'		=> __('Custom video', 'themeblvd')
	);

	if ( $context != 'section' ) {
		unset($types['slideshow']);
	}

	if ( $context == 'section' || $context == 'banner' || $context == 'jumbotron' ) {

		if ( themeblvd_supports( 'featured', 'style' ) ) {
			$types['featured'] = __('Theme\'s preset "Featured" area background', 'themeblvd');
		}

		if ( themeblvd_supports( 'featured_below', 'style' ) ) {
			$types['featured_below'] = __('Theme\'s preset "Featured Below" area background', 'themeblvd');
		}

	}

	if ( $context == 'banner' ) {
		$types['none'] = __('No banner', 'themeblvd');
	}

	return apply_filters( "themeblvd_{$context}_bg_types", $types );
}
