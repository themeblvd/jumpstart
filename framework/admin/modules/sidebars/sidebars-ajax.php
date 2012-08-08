<?php
// Hooks
add_action( 'wp_ajax_sidebar_blvd_add_sidebar', 'sidebar_blvd_ajax_add_sidebar' );
add_action( 'wp_ajax_sidebar_blvd_save_sidebar', 'sidebar_blvd_ajax_save_sidebar' );
add_action( 'wp_ajax_sidebar_blvd_delete_sidebar', 'sidebar_blvd_ajax_delete_sidebar' );
add_action( 'wp_ajax_sidebar_blvd_edit_sidebar', 'sidebar_blvd_ajax_edit_sidebar' );

/**
 * Add new sidebar
 *
 * @since 2.0.0
 */

if( ! function_exists( 'sidebar_blvd_ajax_add_sidebar' ) ) {
	function sidebar_blvd_ajax_add_sidebar() {
	
		// Make sure Satan isn't lurking
		check_ajax_referer( 'optionsframework_new_sidebar', 'security' );
		
		// Handle form data
		parse_str( $_POST['data'], $config );
	
		// Setup arguments for new 'sidebar' post
		$args = array(	
			'post_type'			=> 'tb_sidebar', 
			'post_title'		=> $config['options']['sidebar_name'],
			'post_status' 		=> 'publish',
			'comment_status'	=> 'closed', 
			'ping_status'		=> 'closed'
		);
		
		// Create new post
		$post_id = wp_insert_post( $args );
		$post = get_post($post_id);
		$post_slug = $post->post_name;
		
		// Setup location
		$location = null;
		if( isset( $config['options']['sidebar_location'] ) ) {
			// Sanitize location
			if( $config['options']['sidebar_location'] == 'floating' ) {
				$location = $config['options']['sidebar_location'];
			} else {
				$exists = false;
				$framework_sidebars = themeblvd_get_sidebar_locations();
				foreach( $framework_sidebars as $framework_sidebar ) {
					if( $framework_sidebar['location']['id'] == $config['options']['sidebar_location'] )
						$exists = true;
				}
				if( $exists ) 
					$location = $config['options']['sidebar_location'];
			}			
		}
			
		// Setup assignments
		$assignments = array();
		$name = null;
		if( isset( $config['options']['sidebar_assignments'] ) && has_filter( 'of_sanitize_conditionals' ) ) {
			$assignments = apply_filters( 'of_sanitize_conditionals', $config['options']['sidebar_assignments'], $post_slug, $post_id );
		}
		
		// Update even if they're empty
		update_post_meta( $post_id, 'location', $location );
		update_post_meta( $post_id, 'assignments', $assignments );
		
		// Respond with mange sidebar page
		sidebar_blvd_manage();
		
		die();
	}
}


/**
 * Save sidebar
 *
 * @since 2.0.0
 */

if( ! function_exists( 'sidebar_blvd_ajax_save_sidebar' ) ) {
	function sidebar_blvd_ajax_save_sidebar() {
		
		// Make sure Satan isn't lurking
		check_ajax_referer( 'optionsframework_save_sidebar', 'security' );
		
		// Handle form data
		parse_str( $_POST['data'], $config );
		
		// Sidebar info
		$post_id = $config['sidebar_id'];
		$sidebar = get_post( $post_id );
		$post_slug = $sidebar->post_name;
		
		// Setup location
		$location = null;
		if( isset( $config['options']['sidebar_location'] ) ) {
			// Sanitize location
			if( $config['options']['sidebar_location'] == 'floating' ) {
				$location = $config['options']['sidebar_location'];
			} else {
				$exists = false;
				$framework_sidebars = themeblvd_get_sidebar_locations();
				foreach( $framework_sidebars as $framework_sidebar ) {
					if( $framework_sidebar['location']['id'] == $config['options']['sidebar_location'] )
						$exists = true;
				}
				if( $exists ) 
					$location = $config['options']['sidebar_location'];
			}
		}
		
		// Setup assignments
		$assignments = array();
		$name = null;
		if( isset( $config['options']['sidebar_assignments'] ) && has_filter( 'of_sanitize_conditionals' ) ) {
			$assignments = apply_filters( 'of_sanitize_conditionals', $config['options']['sidebar_assignments'], $post_slug, $post_id );
		}
		
		// Update even if they're empty
		update_post_meta( $post_id, 'location', $location );
		update_post_meta( $post_id, 'assignments', $assignments );
		
		// Widget Area Information
		if( isset( $config['options']['post_title'] ) && isset( $config['options']['post_name'] ) ){
			
			// Start post data to be updated with the ID
			$post_atts = array(
				'ID' 			=> $post_id,
				'post_title' 	=> $config['options']['post_title'],
				'post_name' 	=> $config['options']['post_name']
			);
			
			// Update Post info
			wp_update_post( $post_atts );
		
		}
		
		// Get most recent layout id after doing the above processes
		$updated_sidebar = get_post($post_id);
		$current_sidebar_id = $updated_sidebar->post_name;
		
		// Send current layout ID back with response
		echo $current_sidebar_id.'[(=>)]';
		
		// Respond with update message and management table
		echo '<div id="setting-error-save_options" class="updated fade settings-error ajax-update">';
		echo '	<p><strong>'.__( 'Widget Area saved.', 'themeblvd' ).'</strong></p>';
		echo '</div>';
		echo '[(=>)]';
		sidebar_blvd_manage();
		die();
	}
}

/**
 * Delete sidebar
 *
 * @since 2.0.0 
 */

if( ! function_exists( 'sidebar_blvd_ajax_delete_sidebar' ) ) {
	function sidebar_blvd_ajax_delete_sidebar() {
		
		// Make sure Satan isn't lurking
		check_ajax_referer( 'optionsframework_manage_sidebars', 'security' );
		
		// Handle data
		parse_str( $_POST['data'], $data );
		
		// Only run if user selected some sidebars to delete
		if( isset( $data['posts'] ) ) {
	
			// Delete slider posts
			foreach( $data['posts'] as $id ) {
				// Can still be recovered from trash 
				// if post type's admin UI is turned on.
				wp_delete_post( $id );
			}
					
			// Respond with update message and management table
			echo '<div id="setting-error-delete_sidebar" class="updated fade settings-error ajax-update">';
			echo '	<p><strong>'.__( 'Sidebar(s) deleted.', 'themeblvd' ).'</strong></p>';
			echo '</div>';
			echo '[(=>)]';
			sidebar_blvd_manage();
		}
		die();
	}
}

/**
 * Edit a sidebar
 *
 * @since 2.0.0
 */

if( ! function_exists( 'sidebar_blvd_ajax_edit_sidebar' ) ) {
	function sidebar_blvd_ajax_edit_sidebar() {
		$sidebar_id = $_POST['data'];
		echo $sidebar_id.'[(=>)]';
		sidebar_blvd_edit( $sidebar_id );
		die();
	}
}