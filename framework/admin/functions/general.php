<?php
/**
 * Initialize anything needed for admin panel to run.
 *
 * @since 2.0.0
 */
 
function themeblvd_admin_init() {

	global $_themeblvd_theme_options_page;
	
	// Allow theme options page to run if framework filters
	// have don't have it hidden it and user is capable.
	if( themeblvd_supports( 'admin', 'options' ) && current_user_can( themeblvd_admin_module_cap( 'options' ) ) ) {
		
		// Option ID the theme options are registered and 
		// saved to. -- i.e. get_option( $option_name )
		$option_name = themeblvd_get_option_name();
		
		// All options constructed from framework and 
		// potentially added to by API
		$options = themeblvd_get_formatted_options();
		
		// Theme Options object
		$_themeblvd_theme_options_page = new Theme_Blvd_Options_Page( $option_name, $options );
		
	}
	
}

/**
 * Non-modular Admin Assets
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_non_modular_assets' ) ) {
	function themeblvd_non_modular_assets() {
		global $pagenow;
		
		// Assets for editing posts
		if( $pagenow == 'post-new.php' || $pagenow == 'post.php' ) {
			wp_enqueue_style( 'tb_meta_box-styles', TB_FRAMEWORK_URI . '/admin/assets/css/meta-box.css', false, false, 'screen' );
			wp_enqueue_script( 'tb_meta_box-scripts', TB_FRAMEWORK_URI . '/admin/assets/js/meta-box.js', array('jquery'), TB_FRAMEWORK_VERSION );
		}
		
		// Styles for all of WP admin
		wp_enqueue_style( 'tb_admin_global', TB_FRAMEWORK_URI . '/admin/assets/css/admin-global.css', null, TB_FRAMEWORK_VERSION );
		
	}
}

/**
 * On activation of the theme, redirect user to the theme options
 * panel.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_theme_activation' ) ) {
	function themeblvd_theme_activation() {
		global $pagenow;
		if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ){
			header( 'Location: '.admin_url( 'themes.php?page='.themeblvd_get_option_name() ) );
			exit;
		}
	}
}

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
	foreach( $locations as $location ) {
		$conflicts[$location['location']['id']] = array();
		$non_conflicts[$location['location']['id']] = array();
	}

	// Loop through sidebar posts to construct two arrays side-by-side.
	// As we build the $non_conflicts arrays, we will be able to build
	// the $conflicts arrays off to the side by checking if items already
	// exist in the $non_conflicts.
	foreach( $posts as $post ) {
		
		// Determine location sidebar is assigned to.
		$location = get_post_meta( $post->ID, 'location', true );
		
		// Only run check if a location exists and this 
		// is not a floating widget area.
		if( $location && $location != 'floating' ) {
			$assignments = get_post_meta( $post->ID, 'assignments', true );
			if( is_array( $assignments ) && ! empty( $assignments ) ) {
				foreach( $assignments as $key => $assignment ) {
					if( $key != 'custom' && in_array( $key, $non_conflicts[$location] ) ) {
						if( ! in_array( $key, $conflicts[$location] ) ) {
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
	if( themeblvd_supports( 'meta', 'hijack_atts' ) ) {
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
	if( themeblvd_supports( 'meta', 'hijack_atts' ) ) {
		// Save sidebar layout
		if( isset( $_POST['_tb_sidebar_layout'] ) )
			update_post_meta( $post_id, '_tb_sidebar_layout', $_POST['_tb_sidebar_layout'] );
		// Save custom layout
		if( isset( $_POST['_tb_custom_layout'] ) )
			update_post_meta( $post_id, '_tb_custom_layout', $_POST['_tb_custom_layout'] );
	}
}