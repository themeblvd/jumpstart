<?php
/*-----------------------------------------------------------------------------------*/
/* Slider Blvd Configuration Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Get recognized sliders.
 *
 * Returns an array of all recognized sliders.
 * Sliders included with a particular theme can 
 * be edited by adding a filter through this array.
 *
 * @since 2.0.0
 *
 * @return array
 */
 
if( ! function_exists( 'slider_blvd_recognized_sliders' ) ) {
	function slider_blvd_recognized_sliders() {
		
		global $_themeblvd_user_sliders;
		
		/**
		 * For each slider type, there are then types of 
		 * individual slides it supports.
		 */
		
		$standard_types = array(
			'image' => array(
				'name' => __( 'Image Slide', 'themeblvd' ),
				'main_title' => __( 'Setup Image', 'themeblvd' )
			),
			'video' => array(
				'name' => __( 'Video Slide', 'themeblvd' ),
				'main_title' => __( 'Video Link', 'themeblvd' )
			),
			'custom' => array(
				'name' => __( 'Custom Slide', 'themeblvd' ),
				'main_title' => __( 'Setup Custom Content', 'themeblvd' )
			)
		);
		$carrousel_types = array(
			'image' => array(
				'name' => __( 'Image Slide', 'themeblvd' ),
				'main_title' => __( 'Setup Image', 'themeblvd' )
			)
		);
		
		/**
		 * For each slider type, there are positions its media
		 * can be placed.
		 */
		
		$standard_positions = array(
			'full' 			=> 'slider-large',
			'align-left' 	=> 'slider-staged',
			'align-right' 	=> 'slider-staged'
		);
		$carrousel_positions = array(
			'full' 			=> 'grid_4'
		);
		
		/**
		 * For each slider type, these are the different elements
		 * the user can choose to include in a slide.
		 */
		
		$standard_elements = array( 'image_link', 'headline', 'description', 'button', 'custom_content' );
		$carrousel_elements = array( 'image_link' );
		
		/**
		 * For each slider type, these are settings.
		 * ALL options must have a default value 'std'.
		 */
		
		$standard_options = array(
			array(
				'id'		=> 'fx',
				'name'		=> __( 'How to transition between slides?', 'themeblvd' ),
				'std'		=> 'fade',
				'type'		=> 'select',
				'options'		=> array(
		            'fade' 	=> 'Fade',
					'slide'	=> 'Slide'
				)
			),
			array(
				'id'		=> 'timeout',
				'name' 		=> __( 'Seconds between each transition?', 'themeblvd' ),
				'std'		=> '5',
				'type'		=> 'text'
		    ),
			array(
				'id'		=> 'nav_standard',
				'name'		=> __( 'Show standard slideshow navigation?', 'themeblvd' ),
				'std'		=> '1',
				'type'		=> 'select',
				'options'		=> array(
		            '1'	=> __( 'Yes, show navigation.', 'themeblvd' ),
		            '0'	=> __( 'No, don\'t show it.', 'themeblvd' )
				)
			),
			array(
				'id'		=> 'nav_arrows',
				'name'		=> __( 'Show next/prev arrows?', 'themeblvd' ),
				'std'		=> '1',
				'type'		=> 'select',
				'options'		=> array(
		            '1'	=> __( 'Yes, show arrows.', 'themeblvd' ),
		            '0'	=> __( 'No, don\'t show them.', 'themeblvd' )
				)
			),
			array(
				'id'		=> 'pause_play',
				'name'		=> __( 'Show pause/play button?', 'themeblvd' ),
				'std'		=> '1',
				'type'		=> 'select',
				'options'		=> array(
		            '1'	=> __( 'Yes, show pause/play button.', 'themeblvd' ),
		            '0'	=> __( 'No, don\'t show it.', 'themeblvd' )
				)
			),
			array(
				'id'		=> 'pause_on_hover',
				'name'		=> __( 'Enable pause on hover?', 'themeblvd' ),
				'std'		=> 'pause_on',
				'type'		=> 'select',
				'options'		=> array(
		            'pause_on'		=> __( 'Pause on hover only.', 'themeblvd' ),
		            'pause_on_off'	=> __( 'Pause on hover and resume when hovering off.', 'themeblvd' ),
		            'disable'		=> __( 'No, disable this all together.', 'themeblvd' )
				)
			),
			array(
				'id'		=> 'mobile_fallback',
				'name'		=> __( 'How to display on mobile devices?', 'themeblvd' ),
				'std'		=> 'full_list',
				'type'		=> 'radio',
				'options'		=> array(
		            'full_list'		=> __( 'List out slides for a more user-friendly mobile experience.', 'themeblvd' ),
		            'first_slide'	=> __( 'Show first slide only for a more simple mobile experience.', 'themeblvd' ),
		            'display'		=> __( 'Attempt to show full animated slider on mobile devices.', 'themeblvd' )
				)
			)
		);
		$carrousel_options = array(
			array(
				'id'		=> 'nav_arrows',
				'name'		=> __( 'Show next/prev arrows?', 'themeblvd' ),
				'std'		=> '1',
				'type'		=> 'select',
				'options'		=> array(
		            '1'	=> __( 'Yes, show arrows.', 'themeblvd' ),
		            '0'	=> __( 'No, don\'t show them.', 'themeblvd' )
				)
			),
			array(
				'id'		=> 'mobile_fallback',
				'name'		=> __( 'How to display on mobile devices?', 'themeblvd' ),
				'std'		=> 'full_list',
				'type'		=> 'radio',
				'options'		=> array(
		            'full_list'		=> __( 'List out slides for a more user-friendly mobile experience.', 'themeblvd' ),
		            'first_slide'	=> __( 'Show first slide only for a more simple mobile experience.', 'themeblvd' ),
		            'display'		=> __( 'Attempt to show full animated slider on mobile devices.', 'themeblvd' )
				)
			)
		);
		
		// Final array (which is filterable from outside)
		$sliders = array(
			'standard' => array(
				'name' 		=> 'Standard',
				'id'		=> 'standard',
				'types'		=> $standard_types,
				'positions'	=> $standard_positions,
				'elements'	=> $standard_elements,
				'options'	=> $standard_options
			),
			'carrousel' => array(
				'name' 		=> 'Carrousel 3D',
				'id'		=> 'carrousel',
				'types'		=> $carrousel_types,
				'positions'	=> $carrousel_positions,
				'elements'	=> $carrousel_elements,
				'options'	=> $carrousel_options
			)
		);
		// Add in user-created sliders from API
		$sliders = array_merge( $sliders, $_themeblvd_user_sliders );
		
		// Return filtered
		return apply_filters( 'slider_blvd_recognized_sliders', $sliders );
	}
}