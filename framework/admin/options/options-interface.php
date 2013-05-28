<?php
/**
 * Generates the options fields that are used in forms for 
 * internal options framework.
 *
 * Total props to Devin Price for originally creating this function 
 * for his "Options Framework" -- This function has since been adapted  
 * over time to be utilized throughout many parts of the Theme Blvd
 * theme framework.
 * Devin Price's website: http://wptheming.com
 * 
 * @since 2.2.0
 * 
 * @param string $option_name Prefix for all field name attributes
 * @param array $options All options to show in form
 * @param array $settings Any current settings for all form fields
 * @param boolean $close Whether to add closing </div>
 * @return array $form Final options form
 */

function themeblvd_option_fields( $option_name, $options, $settings, $close = true ) {

    $counter = 0;
	$menu = '';
	$output = '';
	
	foreach( $options as $value ) {
	   	
		$counter++;
		$val = '';
		$select_value = '';
		$checked = '';
		$class = '';
		
		// Sub Groups --
		// This allows for a wrapping div around groups of elements. 
		// The primary reason for this is to help link certain options 
		// together in order to apply custom javascript for certain 
		// common groups.
	   	if( $value['type'] == 'subgroup_start' ) {
	   		if( isset( $value['class'] ) ) $class = ' '.$value['class'];
	   		$output .= '<div class="subgroup'.$class.'">';
	   		continue;
	   	}
	   	if( $value['type'] == 'subgroup_end' ) {
	   		$output .= '</div><!-- .subgroup (end) -->';
	   		continue;
	   	}
	   	
	   	// Name Grouping --
	   	// This allows certain options to be grouped together in the 
	   	// final saved options array by adding a common prefix to their
	   	// name form attributes.
	   	if( isset( $value['group'] ) )
	   		$option_name .= '['.$value['group'].']';
	   		
	   	// Sections --
		// This allows for a wrapping div around certain sections. This 
		// is meant to create visual dividing styles between sections, 
		// opposed to sub groups, which are used to section off the code 
		// for hidden purposes.
	   	if( $value['type'] == 'section_start' ) {
	   		$name = ! empty( $value['name'] ) ? esc_html( $value['name'] ) : '';
	   		if( isset( $value['class'] ) )
	   			$class = ' '.$value['class'];
	   		if( ! $name ) 
	   			$class .= ' no-name';
	   		$output .= '<div class="postbox inner-section'.$class.'">';
	   		if( $name )
	   			$output .= '<h3>'.$name.'</h3>';
	   		if( ! empty($value['desc']) ) $output .= '<div class="section-description">'.$value['desc'].'</div>';
	   		continue;
	   	}
	   	if( $value['type'] == 'section_end' ) {
	   		$output .= '</div><!-- .inner-section (end) -->';
	   		continue;
	   	}
		
		// Wrap all options
		if( $value['type'] != 'heading' && $value['type'] != 'info' ) {

			// Keep all ids lowercase with no spaces
			$value['id'] = preg_replace('/\W/', '', strtolower($value['id']) );
			
			// Determine CSS classes
			$id = 'section-' . $value['id'];
			$class = 'section ';
			if( isset( $value['type'] ) ) {
				$class .= ' section-' . $value['type'];
				if( $value['type'] == 'logo' || $value['type'] == 'background' )
					$class .= ' section-upload';
			}
			if( ! empty( $value['class'] ) )
				$class .= ' ' . $value['class'];
			
			// Start Output
			$output .= '<div id="' . esc_attr( $id ) .'" class="' . esc_attr( $class ) . '">'."\n";
			if( ! empty( $value['name'] ) ) // Name not required any more
				$output .= '<h4 class="heading">' . esc_html( $value['name'] ) . '</h4>' . "\n";
			$output .= '<div class="option">' . "\n" . '<div class="controls">' . "\n";			
		 }
		
		// Set default value to $val
		if( isset( $value['std'] ) )
			$val = $value['std'];
		
		// If the option is already saved, override $val
		if( $value['type'] != 'heading' && $value['type'] != 'info' ) {
			if( isset( $value['group'] ) ) {
				// Set grouped value
				if( isset( $settings[($value['group'])][($value['id'])] ) ) {
					$val = $settings[($value['group'])][($value['id'])];
					// Striping slashes of non-array options
					if( ! is_array( $val ) )
						$val = stripslashes( $val );
				}
			} else {
				// Set non-grouped value
				if( isset($settings[($value['id'])]) ) {
					$val = $settings[($value['id'])];
					// Striping slashes of non-array options
					if( ! is_array( $val ) )
						$val = stripslashes( $val );
				}
			}
		}
        
        // Add each option to output based on type.                
		switch( $value['type'] ) {
			
			/*---------------------------------------*/
			/* Basic Text Input
			/*---------------------------------------*/
			
			case 'text':
				$place_holder = ! empty( $value['pholder'] ) ? ' placeholder="'.$value['pholder'].'"' : null;
				$output .= '<input id="'.esc_attr( $value['id'] ).'" class="of-input" name="'.esc_attr($option_name.'['.$value['id'].']').'" type="text" value="'.stripslashes(esc_attr($val)).'"'.$place_holder.' />';
				break;
			
			/*---------------------------------------*/
			/* Text Area
			/*---------------------------------------*/
			
			case 'textarea' :
				$place_holder = ! empty( $value['pholder'] ) ? ' placeholder="'.$value['pholder'].'"' : null;
				$cols = '8';
				$ta_value = '';
				if( isset( $value['options'] ) ) {
					$ta_options = $value['options'];
					if( isset( $ta_options['cols']) )
						$cols = $ta_options['cols'];
					else
						$cols = '8';
				}
				$output .= '<textarea id="' . esc_attr( $value['id'] ) . '" class="of-input" name="' . stripslashes(esc_attr($option_name . '[' . $value['id'] . ']')) . '" cols="'. esc_attr( $cols ) . '" rows="8"'.$place_holder.'>' . esc_textarea( $val ) . '</textarea>';
				break;
			
			/*---------------------------------------*/
			/* Select
			/*---------------------------------------*/
			
			case 'select' :
				$output .= '<div class="tb-fancy-select">';
				$output .= '<select class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '">';
				foreach ($value['options'] as $key => $option ) {
					$selected = '';
					 if( $val != '' ) {
						 if( $val == $key) { $selected = ' selected="selected"';} 
				     }
					 $output .= '<option'. $selected .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
				 }
				 $output .= '</select>';
				 $output .= '<span class="trigger"></span>';
				 $output .= '<span class="textbox"></span>';
				 $output .= '</div><!-- .tb-fancy-select (end) -->';
				 // If this is a builder sample select, show preview images
				 if( isset( $value['class'] ) && $value['class'] == 'builder_samples' ) {
				 	if( function_exists( 'themeblvd_builder_sample_previews' ) ) {
				 		$output .= themeblvd_builder_sample_previews();	
				 	}
				 }
				 break;
	
			
			/*---------------------------------------*/
			/* Radio
			/*---------------------------------------*/
			
			case 'radio' :
				$name = $option_name .'['. $value['id'] .']';
				foreach ($value['options'] as $key => $option) {
					$id = $option_name . '-' . $value['id'] .'-'. $key;
					$output .= '<input class="of-input of-radio" type="radio" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="'. esc_attr( $key ) . '" '. checked( $val, $key, false) .' /><label for="' . esc_attr( $id ) . '">' . esc_html( $option ) . '</label>';
				}
				break;
			
			/*---------------------------------------*/
			/* Image Selectors
			/*---------------------------------------*/
			
			case 'images':
				$name = $option_name .'['. $value['id'] .']';
				foreach ( $value['options'] as $key => $option ) {
					$selected = '';
					$checked = '';
					if( $val != '' ) {
						if( $val == $key ) {
							$selected = ' of-radio-img-selected';
							$checked = ' checked="checked"';
						}
					}
					$output .= '<input type="radio" id="' . esc_attr( $value['id'] .'_'. $key) . '" class="of-radio-img-radio" value="' . esc_attr( $key ) . '" name="' . esc_attr( $name ) . '" '. $checked .' />';
					$output .= '<div class="of-radio-img-label">' . esc_html( $key ) . '</div>';
					$output .= '<img src="' . esc_url( $option ) . '" alt="' . $option .'" class="of-radio-img-img' . $selected .'" onclick="document.getElementById(\''. esc_attr($value['id'] .'_'. $key) .'\').checked=true;" />';
				}
				break;
			
			/*---------------------------------------*/
			/* Checkbox
			/*---------------------------------------*/
			
			case 'checkbox' :
				$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" '. checked( $val, 1, false) .' />';
				break;
			
			/*---------------------------------------*/
			/* Multicheck
			/*---------------------------------------*/
			
			case 'multicheck' :
				foreach ($value['options'] as $key => $option) {
					$checked = '';
					$label = $option;
					$option = preg_replace('/\W/', '', strtolower($key));
					$id = $option_name . '-' . $value['id'] . '-'. $option;
					$name = $option_name . '[' . $value['id'] . '][' . $key .']';
				    if( isset($val[$key]) )
						$checked = checked($val[$key], 1, false);
					$output .= '<input id="' . esc_attr( $id ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $name ) . '" ' . $checked . ' /><label for="' . esc_attr( $id ) . '">' . $label . '</label>';
				}
				break;
			
			/*---------------------------------------*/
			/* Color picker
			/*---------------------------------------*/
			
			case 'color' :
				$output .= '<div id="' . esc_attr( $value['id'] . '_picker' ) . '" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $val ) . '"></div></div>';
				$output .= '<input class="of-color" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '" type="text" value="' . esc_attr( $val ) . '" />';
				break; 
			
			/*---------------------------------------*/
			/* Uploader
			/*---------------------------------------*/
			
			case 'upload' :
				if( function_exists('wp_enqueue_media') ) {
					// Media uploader WP 3.5+
					$args = array( 'option_name' => $option_name, 'type' => 'standard', 'id' => $value['id'], 'value' => $val );
					$output .= themeblvd_media_uploader( $args );
				} else {
					// Legacy media uploader
					$val = array( 'url' => $val, 'id' => '' );
					$output .= optionsframework_medialibrary_uploader( $option_name, 'standard', $value['id'], $val ); // @deprecated
				}

				break;
			
			/*---------------------------------------*/
			/* Typography
			/*---------------------------------------*/
			
			case 'typography' :	
			
				$typography_stored = $val;
				
				// Font Size
				if( in_array( 'size', $value['atts'] ) ) {
					
					$output .= '<div class="tb-fancy-select">';
					$output .= '<select class="of-typography of-typography-size" name="' . esc_attr( $option_name . '[' . $value['id'] . '][size]' ) . '" id="' . esc_attr( $value['id'] . '_size' ) . '">';
					
					$sizes = themeblvd_recognized_font_sizes();
					foreach( $sizes as $i ) {
						$size = $i . 'px';
						$output .= '<option value="' . esc_attr( $size ) . '" ' . selected( $typography_stored['size'], $size, false ) . '>' . esc_html( $size ) . '</option>';
					}
					
					$output .= '</select>';
					$output .= '<span class="trigger"></span>';
					$output .= '<span class="textbox"></span>';
					$output .= '</div><!-- .tb-fancy-select (end) -->';
				}

				// Font Style
				if( in_array( 'style', $value['atts'] ) ) {
					
					$output .= '<div class="tb-fancy-select">';
					$output .= '<select class="of-typography of-typography-style" name="' . esc_attr( $option_name . '[' . $value['id'] . '][style]' ) . '" id="' . esc_attr( $value['id'] . '_style' ) . '">';
					
					$styles = themeblvd_recognized_font_styles();
					foreach( $styles as $key => $style ) {
						$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['style'], $key, false ) . '>' . esc_html( $style ) . '</option>';
					}

					$output .= '</select>';
					$output .= '<span class="trigger"></span>';
					$output .= '<span class="textbox"></span>';
					$output .= '</div><!-- .tb-fancy-select (end) -->';
				}
			
				// Font Face
				if( in_array( 'face', $value['atts'] ) ) {
					
					$output .= '<div class="tb-fancy-select">';
					$output .= '<select class="of-typography of-typography-face" name="' . esc_attr( $option_name . '[' . $value['id'] . '][face]' ) . '" id="' . esc_attr( $value['id'] . '_face' ) . '">';
					
					$faces = themeblvd_recognized_font_faces();
					foreach( $faces as $key => $face ) {
						$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['face'], $key, false ) . '>' . esc_html( $face ) . '</option>';
					}			
					
					$output .= '</select>';
					$output .= '<span class="trigger"></span>';
					$output .= '<span class="textbox"></span>';
					$output .= '</div><!-- .tb-fancy-select (end) -->';
				}
					
				// Font Color
				if( in_array( 'color', $value['atts'] ) ) {		
					$output .= '<div id="' . esc_attr( $value['id'] ) . '_color_picker" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $typography_stored['color'] ) . '"></div></div>';
					$output .= '<input class="of-color of-typography of-typography-color" name="' . esc_attr( $option_name . '[' . $value['id'] . '][color]' ) . '" id="' . esc_attr( $value['id'] . '_color' ) . '" type="text" value="' . esc_attr( $typography_stored['color'] ) . '" />';
				}
				
				$output .= '<div class="clear"></div>';
				
				// Google Font support
				if( in_array( 'face', $value['atts'] ) ) {
					$output .= '<div class="google-font hide">';
					$output .= '<h5>'.__( 'Enter the name of a font from the <a href="http://www.google.com/webfonts" target="_blank">Google Font Directory</a>.', 'themeblvd' ).'</h5>';
					$output .= '<input type="text" name="' . esc_attr( $option_name . '[' . $value['id'] . '][google]' ) . '" value="' . esc_attr( $typography_stored['google'] ) . '" />';
					$output .= '<p class="note">Example Font Name: "Hammersmith One"</p>';
					$output .= '</div>';
				}
				
				break;
			
			/*---------------------------------------*/
			/* Background
			/*---------------------------------------*/
			
			case 'background':
				
				$background = $val;
				
				// Background Color		
				$current_color = !empty($background['color']) ? $background['color'] : '';
				$output .= '<div id="' . esc_attr( $value['id'] ) . '_color_picker" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $current_color ) . '"></div></div>';
				$output .= '<input class="of-color of-background of-background-color" name="' . esc_attr( $option_name . '[' . $value['id'] . '][color]' ) . '" id="' . esc_attr( $value['id'] . '_color' ) . '" type="text" value="' . esc_attr( $current_color ) . '" />';
				
				// Background Image - New AJAX Uploader using Media Library
				if( ! isset( $background['image'] ) )
					$background['image'] = '';
				
				// Currrent BG formatted correctly
				$current_bg_url = !empty($background['image']) ? $background['image'] : '';
				$current_bg_image = array( 'url' => $current_bg_url, 'id' => '' );
				
				// Start output
				
				// Uploader
				if( function_exists('wp_enqueue_media') )
					$output .= themeblvd_media_uploader( array( 'option_name' => $option_name, 'type' => 'background', 'id' => $value['id'], 'value' => $current_bg_url, 'name' => 'image' ) );
				else
					$output .= optionsframework_medialibrary_uploader( $option_name, 'standard', $value['id'], $current_bg_image, null, '', 0, 'image' ); // @deprecated
				
				$class = 'of-background-properties';
				if( empty($background['image']) )
					$class .= ' hide';
				$output .= '<div class="' . esc_attr( $class ) . '">';
				
				// Background Repeat
				$current_repeat = !empty($background['repeat']) ? $background['repeat'] : '';
				$output .= '<select class="of-background of-background-repeat" name="' . esc_attr( $option_name . '[' . $value['id'] . '][repeat]'  ) . '" id="' . esc_attr( $value['id'] . '_repeat' ) . '">';
				$repeats = themeblvd_recognized_background_repeat();
				foreach( $repeats as $key => $repeat )
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $current_repeat, $key, false ) . '>'. esc_html( $repeat ) . '</option>';
				$output .= '</select>';
				$output .= '<span class="trigger"></span>';
				$output .= '<span class="textbox"></span>';
				
				// Background Position
				$current_position = !empty($background['position']) ? $background['position'] : '';
				$output .= '<select class="of-background of-background-position" name="' . esc_attr( $option_name . '[' . $value['id'] . '][position]' ) . '" id="' . esc_attr( $value['id'] . '_position' ) . '">';
				$positions = themeblvd_recognized_background_position();
				foreach( $positions as $key => $position )
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $current_position, $key, false ) . '>'. esc_html( $position ) . '</option>';
				$output .= '</select>';
				$output .= '<span class="trigger"></span>';
				$output .= '<span class="textbox"></span>';
				
				// Background Attachment
				$current_attachment = !empty($background['attachment']) ? $background['attachment'] : '';
				$output .= '<select class="of-background of-background-attachment" name="' . esc_attr( $option_name . '[' . $value['id'] . '][attachment]' ) . '" id="' . esc_attr( $value['id'] . '_attachment' ) . '">';
				$attachments = themeblvd_recognized_background_attachment();
				foreach( $attachments as $key => $attachment )
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $current_attachment, $key, false ) . '>' . esc_html( $attachment ) . '</option>';
				$output .= '</select>';
				$output .= '<span class="trigger"></span>';
				$output .= '<span class="textbox"></span>';
				$output .= '</div>';
			
				break;  
			
			/*---------------------------------------*/
			/* Info
			/*---------------------------------------*/
			
			case 'info' :
			
				// Classes
				$class = 'section';
				if( isset( $value['type'] ) )
					$class .= ' section-' . $value['type'];
				if( isset( $value['class'] ) )
					$class .= ' ' . $value['class'];
				
				// Start output
				$output .= '<div class="' . esc_attr( $class ) . '">' . "\n";
				if( isset($value['name']) )
					$output .= '<h4 class="heading">' . esc_html( $value['name'] ) . '</h4>' . "\n";
				if( isset( $value['desc'] ) )
					$output .= $value['desc']."\n";
				$output .= '<div class="clear"></div></div>'."\n";
				break;
			
			/*---------------------------------------*/
			/* Columns Setup
			/*---------------------------------------*/
			
			case 'columns' :
				$output .= themeblvd_columns_option( $value['options'], $value['id'], $option_name, $val );
				break;
			
			/*---------------------------------------*/
			/* Tabs Setup
			/*---------------------------------------*/
			
			case 'tabs' :
				$output .= themeblvd_tabs_option( $value['id'], $option_name, $val );
				break;
			
			/*---------------------------------------*/
			/* Content --
			/* Originally designed to work in conjunction 
			/* with setting up columns and tabs.
			/*---------------------------------------*/

			case 'content' :
				$output .= themeblvd_content_option( $value['id'], $option_name, $val, $value['options'] );
				break;
			
			/*---------------------------------------*/
			/* Conditionals --
			/* Originally designed to allow users to 
			/* assign custom sidebars to certain pages.
			/*---------------------------------------*/

			case 'conditionals' :
				$output .= themeblvd_conditionals_option( $value['id'], $option_name, $val );
				break; 
			
			/*---------------------------------------*/
			/* Logo
			/*---------------------------------------*/
			
			case 'logo' :
				$output .= themeblvd_logo_option( $value['id'], $option_name, $val );
				break; 
			
			/*---------------------------------------*/
			/* Social Media
			/*---------------------------------------*/
			
			case 'social_media' :
				$output .= themeblvd_social_media_option( $value['id'], $option_name, $val );
				break;                       
			
			/*---------------------------------------*/
			/* Editor
			/*---------------------------------------*/

			case 'editor':
				
				// Settings
				$editor_settings = array(
					'wpautop' 			=> true,
					'textarea_name' 	=> esc_attr( $option_name.'['.$value['id'].']' ),
					'media_buttons'		=> true,
					'tinymce' 			=> array( 'plugins' => 'wordpress' ),
					'height'			=> 'small' // small, medium, large (Not part of WP's TinyMCE settings
				);
				
				// @todo -- Add TB shortcode generator button. 
				// This will work however currently there is a quirk that won't allow for 
				// more than one editor on a page. Shortcodes will get inserted in whichever
				// the last editor the cursor was in.
				/*
				if( defined('TB_SHORTCODES_PLUGIN_VERSION') && get_option('themeblvd_shortcode_generator') != 'no' )
					$editor_settings['tinymce']['plugins'] .= ',ThemeBlvdShortcodes';
				*/

				if( ! empty( $value['settings'] ) )
					$editor_settings = wp_parse_args( $value['settings'], $editor_settings );

				// Setup description
				if( ! empty( $value['desc_location'] ) && $value['desc_location'] == 'before' )
					$desc_location = 'before';
				else
					$desc_location = 'after';

				$explain_value = '';
				$has_description = '';
				if( ! empty( $value['desc'] ) ) {
					$explain_value = $value['desc'];
					$has_description = ' has-desc';
				}

				// Output description and editor
				$output .= '<div class="tb-wp-editor desc-'.$desc_location.$has_description.' height-'.$editor_settings['height'].'">';

				if( $desc_location == 'before' )
					$output .= '<div class="explain">' . wp_kses( $explain_value, $allowedtags) . '</div>'."\n";								
				
				ob_start();
				wp_editor( $val, uniqid( $value['id'].'_'.rand() ), $editor_settings );
				$output .= ob_get_clean();

				if( $desc_location == 'after' )
					$output .= '<div class="explain">' . wp_kses( $explain_value, $allowedtags) . '</div>'."\n";
				
				$output .= '</div><!-- .tb-wp-editor (end) -->';

				break;

			/*---------------------------------------*/
			/* Heading for Navigation
			/*---------------------------------------*/
			
			case 'heading' :
				if( $counter >= 2 )
				   $output .= '</div>'."\n";
				$id = ! empty( $value['id'] ) ? $value['id'] : $value['name'];
				$jquery_click_hook = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($id) );
				$jquery_click_hook = "of-option-" . $jquery_click_hook;
				$menu .= '<a id="'.  esc_attr( $jquery_click_hook ) . '-tab" class="nav-tab" title="' . esc_attr( $value['name'] ) . '" href="' . esc_attr( '#'.  $jquery_click_hook ) . '">' . esc_html( $value['name'] ) . '</a>';
				$output .= '<div class="group" id="' . esc_attr( $jquery_click_hook ) . '">';
				// $output .= '<h3>' . esc_html( $value['name'] ) . '</h3>' . "\n";
				break;
				
		} // end switch( $value['type'] )

		// Here's your chance to add in your own custom 
		// option type while we're looping through each 
		// option. If you come up with a unique $type, 
		// you can intercept things here and append  
		// to the $output.
		$output = apply_filters( 'themeblvd_option_type', $output, $value, $option_name, $val );
		
		// Finish off standard options and add description
		if( $value['type'] != 'heading' && $value['type'] != 'info' ) {
			if( $value['type'] != 'checkbox' )
				$output .= '<br/>';
			$explain_value = '';
			if( ! empty( $value['desc'] ) )
				$explain_value = $value['desc'];
			$allowedtags = themeblvd_allowed_tags();
			$output .= '</div>';
			if( $value['type'] != 'editor' ) // Editor displays description above it
				$output .= '<div class="explain">'.wp_kses($explain_value, $allowedtags).'</div>'."\n";
			$output .= '<div class="clear"></div></div></div>'."\n";
		}
	}
	
	// Optional closing div
    if( $close )
    	$output .= '</div>';
    
    // Construct final return
    $form = array(
    	$output,	// The actual options form
    	$menu		// Navigation, will not always be needed
    );
    
    return $form;
}