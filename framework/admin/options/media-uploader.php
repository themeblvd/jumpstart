<?php
/**
 * Media Uploader Using the WordPress Media Library in 3.5+.
 * 
 * @param array $args Arguments to setup option, see descriptions at start of function.
 */

if( ! function_exists( 'themeblvd_media_uploader' ) ) {
	function themeblvd_media_uploader( $args ) {

		$defaults = array(
			'option_name' 	=> '',			// Prefix for form name attributes
			'type'			=> 'standard',	// Type of media uploader - standard, logo, logo_2x, background, slider, video
			'id'			=> '', 			// A token to identify this field, extending onto option_name. -- option_name[id]
			'value'			=> '',			// The value of the field, if present.
			'value_id'		=> '',			// Attachment ID used in slider
			'value_width'	=> '',			// Width value used for logo option
			'name'			=> ''			// Option to extend 'id' token -- option_name[id][name]
		);
		$args = wp_parse_args( $args, $defaults );

		$output = '';
		$id = '';
		$class = '';
		$int = '';
		$value = '';
		$name = '';
		
		$id = strip_tags( strtolower( $args['id'] ) );
		$type = $args['type'];

		// If a value is passed and we don't have a stored value, use the value that's passed through.
		$value = '';
		if( $args['value'] )
			$value = $args['value'];
		
		// Set name formfield based on type.
		if( $type == 'slider' )
			$name = $args['option_name'].'[image]';
		else
			$name = $args['option_name'].'['.$id.']';
		
		// If passed name, set it.
		if( $args['name'] ) {
			$name = $name.'['.$args['name'].']';
			$id = $id.'_'.$args['name'];
		}

		if( $value )
			$class = ' has-file';

		// Allow multiple upload options on the same page with 
		// same ID -- This could happen in the Layout Builder, for example.
		$formfield = uniqid( $id.'_' );

		// Data passed to wp.media
		$data = array(
			'title' 	=> __('Select Media', 'themeblvd'),
			'select'	=> __('Select', 'themeblvd'),
			'upload'	=> __( 'Upload', 'themeblvd' ),
			'remove'	=> __( 'Remove', 'themeblvd' ),
			'class'		=> 'tb-modal-hide-settings'
		);

		// Start output
		switch( $type ){
			case 'slider' :
				$data['title'] = __('Slide Image', 'themeblvd');
				$data['select'] = __('Use for Slide', 'themeblvd');
				$data['upload'] = __('Get Image', 'themeblvd');
				$help = __( 'You must use the \'Get Image\' button to insert an image for this slide to ensure that a proper image ID is used. This is what the locked icon represents.', 'themeblvd' );
				$output .= '<span class="locked"><span></span>';
				$output .= '<a href="#" class="help-icon tooltip-link" title="'.$help.'">Help</a>';
				$output .= '<input id="'.$formfield.'_id" class="image-id locked upload'.$class.'" type="text" name="'.$name.'[id]" placeholder="'.__('Image ID', 'themeblvd').'" value="'.$args['value_id'].'" /></span>'."\n";
				$output .= '<input id="'.$formfield.'" class="image-url upload'.$class.'" type="hidden" name="'.$name.'[url]" value="'.$value.'" />'."\n";
				break;

			case 'video' :
				$data['title'] = __('Slide Video', 'themeblvd');
				$data['select'] = __('Use for Slide', 'themeblvd');
				$data['upload'] = __('Get Video', 'themeblvd');
				$output .= '<input id="'.$formfield.'" class="video-url upload'.$class.'" type="text" name="'.$name.'" value="'.$value.'" placeholder="'.__('Video Link', 'themeblvd') .'" />'."\n";
				break;

			case 'quick_slider' :
				$data['title'] = __('Quick Slider', 'themeblvd');
				$data['select'] = __('Use selected images', 'themeblvd');
				$data['class'] = '';
				// @todo -- Future feature
				break;
			
			case 'logo' :
				$data['title'] = __('Logo Image', 'themeblvd'); 
				$data['select'] = __('Use for Logo', 'themeblvd');
				$width_name = str_replace( '[image]', '[image_width]', $name );
				$output .= '<input id="'.$formfield.'" class="image-url upload'.$class.'" type="text" name="'.$name.'" value="'.$value.'" placeholder="'.__('Image URL', 'themeblvd').'" />'."\n";
				$output .= '<input id="'.$formfield.'_width" class="image-width upload'.$class.'" type="text" name="'.$width_name.'" value="'.$args['value_width'].'" placeholder="'.__('Width', 'themeblvd').'" />'."\n";
				break;
			
			case 'logo_2x' :
				$data['title'] = __('Logo HiDPI Image', 'themeblvd');
				$data['select'] = __('Use for Logo', 'themeblvd');
				$output .= '<input id="'.$formfield.'" class="image-url upload'.$class.'" type="text" name="'.$name.'" value="'.$value.'" placeholder="'.__('URL for image twice the size of standard image', 'themeblvd') .'" />'."\n";
				break;
			
			case 'background' :
				$data['title'] = __('Select Background Image', 'themeblvd'); 
				$output .= '<input id="'.$formfield.'" class="image-url upload'.$class.'" type="text" name="'.$name.'" value="'.$value.'" placeholder="'.__('Image URL', 'themeblvd') .'" />'."\n";
				break;

			default :
				$output .= '<input id="'.$formfield.'" class="image-url upload'.$class.'" type="text" name="'.$name.'" value="'.$value.'" placeholder="'.__('No file chosen', 'themeblvd') .'" />'."\n";
		}

		$data = apply_filters('themeblvd_media_uploader_data', $data, $type);
		
		if( ! $value || $type == 'video' )
			$output .= '<input id="upload-'.$formfield.'" class="trigger upload-button button" type="button" data-type="'.$type.'" data-title="'.$data['title'].'" data-select="'.$data['select'].'" data-class="'.$data['class'].'" data-upload="'.$data['upload'].'" data-remove="'.$data['remove'].'" value="'.$data['upload'].'" />'."\n";
		else
			$output .= '<input id="remove-'.$formfield.'" class="trigger remove-file button" type="button" data-type="'.$type.'" data-title="'.$data['title'].'" data-select="'.$data['select'].'" data-class="'.$data['class'].'" data-upload="'.$data['upload'].'" data-remove="'.$data['remove'].'" value="'.$data['remove'].'" />'."\n";

		$output .= '<div class="screenshot" id="' . $formfield . '-image">' . "\n";
		
		if( $value && $type != 'video' ) { 
			$remove = '<a class="remove-image">Remove</a>';
			$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value );
			if( $image ) {
				$output .= '<img src="' . $value . '" alt="" />' . $remove;
			} else {
				$parts = explode( "/", $value );
				for( $i = 0; $i < sizeof( $parts ); ++$i )
					$title = $parts[$i];
			
				// Standard generic output if it's not an image.	
				$title = __( 'View File', 'themeblvd' );
				$output .= '<div class="no-image"><span class="file_link"><a href="' . $value . '" target="_blank" rel="external">'.$title.'</a></span></div>';
			}	
		}
		$output .= '</div>' . "\n";
		return $output;
	}
}