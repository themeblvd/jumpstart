<?php
/**
 * Display custom layout within template_builder.php 
 * page template.
 *
 * When each element is displayed, it is done so with 
 * an external function. This will allow some elements 
 * to be used for other things such as shortcodes. 
 * However, even elements that shouldn't have an external 
 * function do to allow those elements to be indidivually 
 * edited from a child theme.
 *
 * @since 2.0.0
 *
 * @param string $layout Post slug for layout
 * @param string $location Location of elements, featured or primary
 */
 
if( ! function_exists( 'themeblvd_elements' ) ) {
	function themeblvd_elements( $layout, $location ) {
		
		// Setup
		$counter = 0;
		$primary_query = false;
		$layout_id = themeblvd_post_id_by_name( $layout, 'tb_layout' );
		if( ! $layout_id ) {
			// This should rarely happen. A common scenario might 
			// be the user setup a page with a layout, but then 
			// deleted the layout after page was already published.
			echo themeblvd_get_local( 'invalid_layout' );
			return;
		}
		// Gather elements and only move forward if we have elements to show.
		$elements = get_post_meta( $layout_id, 'elements', true );
		if( ! empty( $elements ) && ! empty( $elements[$location] ) ) {
			$elements = $elements[$location];
			$num_elements = count($elements);
		} else {
			// If there are no elements in this location, 
			// get us out of here!
			return;
		}

		// Loop through elements
		foreach( $elements as $id => $element ) {
			
			// Skip element if its type isn't registered
			if( ! themeblvd_is_element( $element['type'] ) )
				continue;
			
			// Increase counter
			$counter++;
			
			// CSS classes for element
			$classes = 'element '.$location.'-element-'.$counter.' element-'.$element['type'];
			if( $counter == 1 )
				$classes .= ' first-element';
			if( $num_elements == $counter )
				$classes .= ' last-element';
			if( $element['type'] == 'slider' ) {
				if( isset( $element['options']['slider_id'] ) ) {
					$slider_id = themeblvd_post_id_by_name( $element['options']['slider_id'], 'tb_slider' );
					$type = get_post_meta( $slider_id, 'type', true );
					$classes .= ' element-slider-'.$type;
				}
			}
			if( $element['type'] == 'paginated_post_lst' || $element['type'] == 'paginated_post_grid' )
				$classes .= $element['type'];
			if( isset( $element['options']['visibility'] ) )
				$classes .= themeblvd_responsive_visibility_class( $element['options']['visibility'], true );
			$classes .= themeblvd_get_classes( 'element_'.$element['type'], true, false, $element['type'], $element['options'] );
			
			// Start ouput
			do_action( 'themeblvd_element_'.$element['type'].'_before', $id, $element['options'], $location ); // Before element: themeblvd_element_{type}_before
			do_action( 'themeblvd_element_open', $element['type'], $location, $classes );
			do_action( 'themeblvd_element_'.$element['type'].'_top', $id, $element['options'], $location ); // Top of element: themeblvd_element_{type}_top
			echo '<div class="grid-protection">';
			
			switch( $element['type'] ) {
				
				/*------------------------------------------------------*/
				/* Columns
				/*------------------------------------------------------*/
				
				case 'columns' :
					$i = 1;
					$columns = array();
					$num = $element['options']['setup']['num'];
					while( $i <= $num ) {
						$columns[] = $element['options']['col_'.$i];
						$i++;
					}
					themeblvd_columns( $num, $element['options']['setup']['width'][$num], $columns );
					break;
				
				/*------------------------------------------------------*/
				/* Content
				/*------------------------------------------------------*/
				
				case 'content' :
					echo themeblvd_content( $element['options'] );
					break;
				
				/*------------------------------------------------------*/
				/* Divider
				/*------------------------------------------------------*/
				
				case 'divider' :
					echo themeblvd_divider( $element['options']['type'] );
					break;
					
				/*------------------------------------------------------*/
				/* Headline
				/*------------------------------------------------------*/
				
				case 'headline' :
					echo themeblvd_headline( $element['options'] );
					break;
					
				/*------------------------------------------------------*/
				/* Post Grid
				/*------------------------------------------------------*/
				
				case 'post_grid' :
					themeblvd_posts( $element['options'], 'grid', $location, 'secondary' );
					break;
					
				/*------------------------------------------------------*/
				/* Post Grid (paginated)
				/*------------------------------------------------------*/
				
				case 'post_grid_paginated' :
					if( ! $primary_query ) {
						themeblvd_posts_paginated( $element['options'], 'grid', $location );
						$primary_query = true;
					}
					break;
					
				/*------------------------------------------------------*/
				/* Post Grid Slider
				/*------------------------------------------------------*/
				
				case 'post_grid_slider' :
					themeblvd_post_slider( $id, $element['options'], 'grid', $location );
					break;
					
				/*------------------------------------------------------*/
				/* Post List
				/*------------------------------------------------------*/
				
				case 'post_list' :
					themeblvd_posts( $element['options'], 'list', $location, 'secondary' );
					break;
					
				/*------------------------------------------------------*/
				/* Post List (paginated)
				/*------------------------------------------------------*/
				
				case 'post_list_paginated' :
					if( ! $primary_query ) {
						themeblvd_posts_paginated( $element['options'], 'list', $location );
						$primary_query = true;
					}
					break;
					
				/*------------------------------------------------------*/
				/* Post List Slider
				/*------------------------------------------------------*/
				
				case 'post_list_slider' :
					themeblvd_post_slider( $id, $element['options'], 'list', $location );
					break;
					
				/*------------------------------------------------------*/
				/* Slider
				/*------------------------------------------------------*/
				
				case 'slider' :
					themeblvd_slider( $element['options']['slider_id'] );
					break;
					
				/*------------------------------------------------------*/
				/* Slogan
				/*------------------------------------------------------*/
				
				case 'slogan' :
					echo themeblvd_slogan( $element['options'] );
					break;
					
				/*------------------------------------------------------*/
				/* Tabs
				/*------------------------------------------------------*/
				
				case 'tabs' :
					echo themeblvd_tabs( $id, $element['options'] );
					break;
					
				/*------------------------------------------------------*/
				/* Recent Tweet
				/*------------------------------------------------------*/
				
				case 'tweet' :
					echo themeblvd_tweet( $element['options'] );
					break;
				
			} // End switch
			
			// Allow to add on custom element that's
			// not in the framework
			do_action( 'themeblvd_'.$element['type'], $id, $element['options'], $location );
			
			// End output
			echo '<div class="clear"></div>';
			echo '</div><!-- .grid-protection (end) -->';
			do_action( 'themeblvd_element_'.$element['type'].'_bottom', $id, $element['options'], $location ); // Bottom of element: themeblvd_element_{type}_bottom
			do_action( 'themeblvd_element_close', $element['type'], $location, $classes );
			do_action( 'themeblvd_element_'.$element['type'].'_after', $id, $element['options'], $location ); // Below element: themeblvd_element_{type}_bottom
			
		} // End foreach
				
	}
}