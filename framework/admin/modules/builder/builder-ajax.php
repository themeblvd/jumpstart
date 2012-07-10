<?php
// Hooks
add_action( 'wp_ajax_builder_blvd_add_layout', 'builder_blvd_ajax_add_layout' );
add_action( 'wp_ajax_builder_blvd_save_layout', 'builder_blvd_ajax_save_layout' );
add_action( 'wp_ajax_builder_blvd_add_element', 'builder_blvd_ajax_add_element' );
add_action( 'wp_ajax_builder_blvd_update_table', 'builder_blvd_ajax_update_table' );
add_action( 'wp_ajax_builder_blvd_delete_layout', 'builder_blvd_ajax_delete_layout' );
add_action( 'wp_ajax_builder_blvd_edit_layout', 'builder_blvd_ajax_edit_layout' );

/**
 * Add new layout
 *
 * @since 2.0.0 
 */

if( ! function_exists( 'builder_blvd_ajax_add_layout' ) ) {
	function builder_blvd_ajax_add_layout() {
		
		// Make sure Satan isn't lurking
		check_ajax_referer( 'optionsframework_new_builder', 'security' );
		
		// Handle form data
		parse_str( $_POST['data'], $config );
	
		// Setup arguments for new 'layout' post
		$args = array(	
			'post_type'			=> 'tb_layout', 
			'post_title'		=> $config['options']['layout_name'],
			'post_status' 		=> 'publish',
			'comment_status'	=> 'closed', 
			'ping_status'		=> 'closed'
		);
		
		// Create new post
		$post_id = wp_insert_post( $args );
		
		// Setup meta
		if( $config['options']['layout_start'] ) {
			// Configure meta for sample layout
			$samples = builder_blvd_samples();
			$current_sample = $samples[$config['options']['layout_start']];
			$elements = array(
				'featured' => $current_sample['featured'],
				'primary' => $current_sample['primary'],
				'featured_below' => $current_sample['featured_below']
			);
			$settings = array( 'sidebar_layout' => $current_sample['sidebar_layout'] );
		} else {
			// Configure meta for blank layout
			$elements = array();
			$settings = array( 'sidebar_layout' => $config['options']['layout_sidebar'] );
		}
	
		// Update even if they're empty
		update_post_meta( $post_id, 'elements', $elements );
		update_post_meta( $post_id, 'settings', $settings );
		
		// Respond with edit page for the new layout and ID
		echo $post_id.'[(=>)]';
		builder_blvd_edit( $post_id );
		
		die();
	}
}

/**
 * Save layout
 *
 * @since 2.0.0
 */

if( ! function_exists( 'builder_blvd_ajax_save_layout' ) ) {
	function builder_blvd_ajax_save_layout () {
		
		// Make sure Satan isn't lurking
		check_ajax_referer( 'optionsframework_save_builder', 'security' );
		
		// Handle form data
		parse_str( $_POST['data'], $data );
		
		// Layout ID
		$layout_id = $data['layout_id'];
		
		// Setup elements
		$location = 'featured';
		$elements = array();
		if( isset( $data['elements'] ) ) {
	
			// Get default element options
			$default_element_options = themeblvd_get_elements();
			
			// Loop through setting items in 'featured' location 
			// until we arrive at the divider line, then 
			// continue putting them into the 'primary' area, 
			// and then when we hit divider_2, set location to 
			// 'featured_below'.
			foreach ( $data['elements'] as $id => $element ) {
				if( $id == 'divider' ) {
					// Now the primary area
					$location = 'primary';
				} else if( $id == 'divider_2' ) {
					// And now the featured below area
					$location = 'featured_below';
				} else {
					
					// Sanitize element's options
					$clean = array();
					foreach( $default_element_options[$element['type']]['options'] as $option ){
						
						if ( ! isset( $option['id'] ) )
							continue;
	
						if ( ! isset( $option['type'] ) )
							continue;
						
						$option_id = $option['id'];
							
						// Set checkbox to false if it wasn't sent in the $_POST
						if ( 'checkbox' == $option['type'] ) {
							if( isset( $element['options'][$option_id] ) )
								$element['options'][$option_id] = '1';
							else
								$element['options'][$option_id] = '0';
						}
			
						// Set each item in the multicheck to false if it wasn't sent in the $_POST
						if ( 'multicheck' == $option['type'] ){
							if( ! isset( $element['options'][$option_id] ) ) {
								$element['options'][$option_id] = array();
							}
						}
						
						// For a value to be submitted to database it must pass through a sanitization filter
						if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
							$clean[$option_id] = apply_filters( 'of_sanitize_' . $option['type'], $element['options'][$option_id], $option );
						}
						
					}
					$element['options'] = $clean;
					$elements[$location][$id] = $element;
				}
			}
		}
		
		// Setup options	
		if( isset( $data['options'] ) )
			$options = $data['options'];
		else
			$options = null;
		
		// Update even if they're empty
		update_post_meta( $layout_id, 'elements', $elements );
		update_post_meta( $layout_id, 'settings', $options );
		
		// Display update message
		echo '<div id="setting-error-save_options" class="updated fade settings-error ajax-update">';
		echo '	<p><strong>'.__( 'Layout saved.', TB_GETTEXT_DOMAIN ).'</strong></p>';
		echo '</div>';
		die();
	}
}

/**
 * Add new element 
 *
 * @since 2.0.0
 */

if( ! function_exists( 'builder_blvd_ajax_add_element' ) ) {
	function builder_blvd_ajax_add_element() {	
		$element_type = $_POST['data'];
		$element_id = uniqid( 'element_'.rand() );
		builder_blvd_edit_element( $element_type, $element_id );
		die();
	}
}

/**
 * Update layout manager table 
 *
 * @since 2.0.0
 */

if( ! function_exists( 'builder_blvd_ajax_update_table' ) ) {
	function builder_blvd_ajax_update_table() {
		builder_blvd_manage();
		die();
	}
}

/**
 * Delete layout
 *
 * @since 2.0.0
 */

if( ! function_exists( 'builder_blvd_ajax_delete_layout' ) ) {
	function builder_blvd_ajax_delete_layout() {
		
		// Make sure Satan isn't lurking
		check_ajax_referer( 'optionsframework_manage_builder', 'security' );
		
		// Handle data
		parse_str( $_POST['data'], $data );
		
		// Only run if user selected some layouts to delete
		if( isset( $data['posts'] ) ) {
	
			// Delete slider posts
			foreach( $data['posts'] as $id ) {
				
				// Can still be recovered from trash 
				// if post type's admin UI is turned on.
				wp_delete_post( $id );
			
			}
			
			// Send back number of sliders
			$posts = get_posts( array( 'post_type' => 'tb_layout', 'numberposts' => -1 ) );
			echo sprintf( _n( '1 Layout', '%s Layouts', count($posts) ), number_format_i18n( count($posts) ) ).'[(=>)]';
			
			// Display update message
			echo '<div id="setting-error-delete_layout" class="updated fade settings-error ajax-update">';
			echo '	<p><strong>'.__( 'Layout(s) deleted.', TB_GETTEXT_DOMAIN ).'</strong></p>';
			echo '</div>';
		
		}
		
		die();
	}
}

/**
 * Edit a layout
 *
 * @since 2.0.0 
 */

if( ! function_exists( 'builder_blvd_ajax_edit_layout' ) ) {
	function builder_blvd_ajax_edit_layout() {
		$layout_id = $_POST['data'];
		echo $layout_id.'[(=>)]';
		builder_blvd_edit( $layout_id );
		die();
	}
}