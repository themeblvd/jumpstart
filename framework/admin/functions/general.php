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
 * Run the theme demo importer.
 *
 * @since 2.5.0
 */
function themeblvd_import(){

	// Check if WordPress's importer plugin
	// is setup and running.
	if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
		return;
	}

	// Run importer
	$importer = Theme_Blvd_Import::get_instance();

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
		wp_enqueue_style( 'themeblvd_admin', TB_FRAMEWORK_URI . '/admin/assets/css/admin-style.min.css', null, TB_FRAMEWORK_VERSION );
		wp_enqueue_style( 'themeblvd_options', TB_FRAMEWORK_URI . '/admin/options/css/admin-style.min.css', null, TB_FRAMEWORK_VERSION );
		wp_enqueue_script( 'themeblvd_modal', TB_FRAMEWORK_URI . '/admin/assets/js/modal.min.js', array('jquery'), TB_FRAMEWORK_VERSION );
		wp_enqueue_script( 'themeblvd_admin', TB_FRAMEWORK_URI . '/admin/assets/js/shared.min.js', array('jquery'), TB_FRAMEWORK_VERSION );
		wp_enqueue_script( 'tb_meta_box-scripts', TB_FRAMEWORK_URI . '/admin/assets/js/meta-box.min.js', array('jquery'), TB_FRAMEWORK_VERSION );
		wp_localize_script( 'tb_meta_box-scripts', 'themeblvd', themeblvd_get_admin_locals('js') );
	}

	// Includes Theme Blvd admin icon font
	wp_enqueue_style( 'tb_admin_global', TB_FRAMEWORK_URI . '/admin/assets/css/admin-global.min.css', null, TB_FRAMEWORK_VERSION );

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
		add_meta_box( 'themeblvd_pageparentdiv', __( 'Page Attributes', 'themeblvd' ), 'themeblvd_page_attributes_meta_box', 'page', 'side', 'core' );
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
 * Dismiss an admin notice.
 *
 * Plugins can use this to dismiss any admin notices
 * by providing a link similar to the following, which
 * will store meta data for the current user.
 *
 * An admin notice could be setup something like this:
 *
 * function my_admin_notice() {
 *		global $current_user;
 * 		if ( ! get_user_meta( $current_user->ID, 'example_message' ) ) {
 * 			echo '<div class="updated">';
 *			echo '<p>Some message to the user.</p>';
 * 			echo '<p><a href="?tb_nag_ignore=example_message">Dismiss this notice</a></p>';
 *			echo '</div>';
 * 		}
 * }
 * add_action( 'admin_notices', 'my_admin_notice' );
 *
 * @since 2.2.1
 */
function themeblvd_disable_nag() {

	global $current_user;

    if ( isset( $_GET['tb_nag_ignore'] ) ) {
		add_user_meta( $current_user->ID, $_GET['tb_nag_ignore'], 'true', true );
	}
}

/**
 * Clear set of options. Hooked to "admin_init".
 *
 * @since 2.3.0
 */
function themeblvd_clear_options() {
	if ( isset( $_POST['themeblvd_clear_options'] ) ) {
		$option_id = $_POST['themeblvd_clear_options'];
		delete_option( $option_id );
		add_settings_error( $option_id , 'clear_defaults', __( 'Options cleared from database.', 'themeblvd' ), 'themeblvd-error error fade' );
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
 * @param string $type Type of icons to retrieve - vector, image
 * @return array $icons Array of icons
 */
function themeblvd_get_icons( $type ) {

	$icons = get_transient( 'themeblvd_'.get_template().'_'.$type.'_icons' );

	if ( ! $icons ) {

		$icons = array();

		switch ( $type ) {

			case 'vector' :

				$fetch_icons = array();
				$file_location = TB_FRAMEWORK_DIRECTORY.'/assets/plugins/fontawesome/css/font-awesome.css';

				if ( file_exists( $file_location ) ) {

					$file = fopen( $file_location, "r" );

					// Run through each line of font-awesome.css, and
					// look for anything that could resemble a font ID.
					while ( !feof( $file ) ) {

						$line = fgets( $file );

						if ( strpos( $line, '.fa-' ) !== false && strpos( $line, ':before' ) !== false ) {
							$icon = str_replace( '.fa-', '', $line );
							$icon = str_replace( ':before {', '', $icon );
							$icon = str_replace( ':before,', '', $icon );
							$fetch_icons[] = trim( $icon );
						}

					}

					// Close file
					fclose( $file );

					// Sort icons alphebetically
					sort( $fetch_icons );

					// Format array for use in options framework
					foreach ( $fetch_icons as $icon ) {
						$icons[$icon] = $icon;
					}

				}
				break;

			case 'image' :

				// Icons from the parent theme
				$parent_icons = array();
				$icons_url = TB_FRAMEWORK_URI.'/assets/images/shortcodes/icons';
				$icons_dir = TB_FRAMEWORK_DIRECTORY.'/assets/images/shortcodes/icons';

				if ( file_exists( $icons_dir ) ) {
					$parent_icons = scandir( $icons_dir );
				}

				// Display icons
				if ( count( $parent_icons ) > 0 ) {
					foreach ( $parent_icons as $icon ) {
						if ( strpos( $icon, '.png' ) !== false ) {
							$id = str_replace( '.png', '', $icon );
							$icons[$id] = sprintf( '%s/%s.png', $icons_url, $id );
						}
					}
				}

				// Check for icons in the child theme
				$child_icons = array();
				$child_icons_url = get_stylesheet_directory_uri().'/icons';
				$child_icons_dir = get_stylesheet_directory().'/assets/images/shortcodes/icons';

				if ( file_exists( $child_icons_dir ) ) {
					$child_icons = scandir( $child_icons_dir );
				}

				// Display icons
				if ( count( $child_icons ) > 0 ) {
					foreach ( $child_icons as $icon ) {
						if ( strpos( $icon, '.png' ) !== false ) {
							$id = str_replace( '.png', '', $icon );
							$icons[$id] = sprintf( '%s/%s.png', $child_icons_url, $id );
						}
					}
				}

				break;

		} // end switch $type

		// Cache result
		set_transient( 'themeblvd_'.$type.'_icons', $icons, '86400' ); // 1 day

	} // end if $icons

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
		'image'		=> __('Custom color + image', 'themeblvd')
	);

	if ( $context == 'section' || $context == 'banner' ) {

		if ( themeblvd_supports( 'featured', 'style' ) ) {
			$types['featured'] = __('Theme\'s preset "Featured" area background', 'themeblvd_builder');
		}

		if ( themeblvd_supports( 'featured_below', 'style' ) ) {
			$types['featured_below'] = __('Theme\'s preset "Featured Below" area background', 'themeblvd_builder');
		}

	}

	if ( $context == 'banner' ) {
		$types['none'] = __('No banner', 'themeblvd');
	}

	return apply_filters( "themeblvd_{$context}_bg_types", $types );
}