<?php
/**
 * Add slider type.
 *
 * @uses $_themeblvd_user_sliders 
 * @since 2.1.0
 *
 * @param string $slider_id ID for new slider type
 * @param string $slider_name Name for new slider type
 * @param array $slide_types Slides types - image, video, custom 
 * @param array $media_positions Positions for media - full, align-left, align-right
 * @param array $slide_elements Elements to include in slides - image_link, headline, description, button
 * @param array $options Options formatted for Options Framework
 * @param string $function_to_display Function to display slider on frontend
 */

if( ! function_exists( 'themeblvd_add_slider' ) ) {
	function themeblvd_add_slider( $slider_id, $slider_name, $slide_types, $media_positions, $slide_elements, $options, $function_to_display ) {
		global $_themeblvd_user_sliders;
		
		// Format slide types
		// $slide_types should look something like: array( 'image', 'video', 'custom' )
		$formatted_slide_types = array();
		foreach( $slide_types as $type ) {
			switch( $type ) {
				case 'image' :
					$formatted_slide_types['image'] = array(
						'name' 			=> __( 'Image Slide', 'themeblvd' ),
						'main_title' 	=> __( 'Setup Image', 'themeblvd' )
					);
					break;
				case 'video' :
					$formatted_slide_types['video'] = array(
						'name' 			=> __( 'Video Slide', 'themeblvd' ),
						'main_title' 	=> __( 'Video Link', 'themeblvd' )
					);
					break;
				case 'custom' :
					$formatted_slide_types['custom'] = array(
						'name' 			=> __( 'Custom Slide', 'themeblvd' ),
						'main_title' 	=> __( 'Setup Custom Content', 'themeblvd' )
					);
					break;
			}
		}
		
		// Format media positions
		// $media_positions should look something like: array( 'full', 'align-left', 'align-right' )
		$formatted_media_positions = array();
		foreach( $media_positions as $position ) {
			switch( $position ) {
				case 'full' :
					$formatted_media_positions['full'] = __( 'Full-Size', 'themeblvd' );
					break;
				case 'align-left' :
					$formatted_media_positions['align-left'] = __( 'Aligned Left', 'themeblvd' );
					break;
				case 'align-right' :
					$formatted_media_positions['align-right'] = __( 'Aligned Right', 'themeblvd' );
					break;
			}	
		}
		
		// Slide Elements
		// $slide_elements should look something like: array( 'image_link', 'headline', 'description', 'button' )
		
		// Add in slider
		$_themeblvd_user_sliders[$slider_id] = array(
			'name' 		=> $slider_name,
			'id'		=> $slider_id,
			'types'		=> $formatted_slide_types,
			'positions'	=> $formatted_media_positions,
			'elements'	=> $slide_elements,
			'options'	=> $options
		);
		
		// Add frontend display
		add_action( 'themeblvd_'.$slider_id.'_slider', $function_to_display, 10, 3 );
	}
}	