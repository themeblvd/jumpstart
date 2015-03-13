<?php
if ( ! function_exists( 'themeblvd_media_uploader' ) ) :
/**
 * Media Uploader Using the WordPress Media Library in 3.5+.
 *
 * @since 2.3.0
 *
 * @param array $args Arguments to setup option, see descriptions at start of function.
 * @param string $output Final HTML output
 */
function themeblvd_media_uploader( $args ) {

	$defaults = array(
		'option_name' 	=> '',			// Prefix for form name attributes
		'type'			=> 'standard',	// Type of media uploader - standard (an image), advanced (an image w/crop settings) logo, logo_2x, background, slider, video, media
		'send_back'		=> 'url',		// On type "standard" send back image url or attachment ID to text input - url, id
		'id'			=> '', 			// A token to identify this field, extending onto option_name. -- option_name[id]
		'value'			=> '',			// The value of the field, if present.
		'value_id'		=> '',			// Attachment ID used in slider, or stored ID in advanced option
		'value_src'		=> '',			// Image URL used in advanced option
		'value_title'	=> '',			// Title of attachment image (used for slider)
		'value_width'	=> '',			// Width value used for logo option and advanced option
		'value_height'	=> '',			// Height baclue used for advanced option
		'value_title'	=> '',			// Title of image, used with advanced type
		'value_crop'	=> '',			// Crop size of image, used with advanced type
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
	if ( $args['value'] ) {
		$value = $args['value'];
	} else if ( isset( $args['value_src'] ) ) {
		$value = $args['value_src'];
	}

	// Set name formfield based on type.
	if ( $type == 'slider' ) {
		$name = $args['option_name'].'[image]';
	} else {
		$name = $args['option_name'].'['.$id.']';
	}

	// If passed name, set it.
	if ( $args['name'] ) {
		$name = $name.'['.$args['name'].']';
		$id = $id.'_'.$args['name'];
	}

	if ( $value ) {
		$class = ' has-file';
	}

	// Allow multiple upload options on the same page with
	// same ID -- This could happen in the Layout Builder, for example.
	$formfield = uniqid( $id.'_' );

	// Data passed to wp.media
	$data = array(
		'title' 	=> __( 'Select Media', 'themeblvd'),
		'select'	=> __( 'Select', 'themeblvd'),
		'upload'	=> __( 'Upload', 'themeblvd' ),
		'remove'	=> __( 'Remove', 'themeblvd' ),
		'send_back'	=> $args['send_back'],
		'class'		=> 'tb-modal-hide-settings'
	);

	// Start output
	switch ( $type ) {

		case 'slider' :
			$data['title'] = __('Slide Image', 'themeblvd');
			$data['select'] = __('Use for Slide', 'themeblvd');
			$data['upload'] = __('Get Image', 'themeblvd');
			$help = __( 'You must use the \'Get Image\' button to insert an image for this slide to ensure that a proper image ID is used. This is what the locked icon represents.', 'themeblvd' );
			$output .= '<span class="locked"><span></span>';
			$output .= '<a href="#" class="help-icon tb-icon-help-circled tooltip-link" title="'.$help.'"></a>';
			$output .= '<input id="'.$formfield.'_id" class="image-id locked upload'.$class.'" type="text" name="'.$name.'[id]" placeholder="'.__('Image ID', 'themeblvd').'" value="'.$args['value_id'].'" /></span>'."\n";
			$output .= '<input id="'.$formfield.'" class="image-url upload'.$class.'" type="hidden" name="'.$name.'[url]" value="'.$value.'" />'."\n";
			$output .= '<input id="'.$formfield.'_title" class="image-title upload'.$class.'" type="hidden" name="'.$name.'[title]" value="'.$args['value_title'].'" />'."\n";
			break;

		case 'video' :
			$data['title'] = __('Select Video', 'themeblvd');
			$data['select'] = __('Use Video', 'themeblvd');
			$data['upload'] = __('Get Video', 'themeblvd');
			$output .= '<input id="'.$formfield.'" class="video-url upload'.$class.'" type="text" name="'.$name.'" value="'.$value.'" placeholder="'.__('Video URL', 'themeblvd') .'" />'."\n";
			break;

		case 'logo' :
			$data['title'] = __('Logo Image', 'themeblvd');
			$data['select'] = __('Use for Logo', 'themeblvd');
			$width_name = str_replace( '[image]', '[image_width]', $name );
			$height_name = str_replace( '[image]', '[image_height]', $name );
			$output .= '<input id="'.$formfield.'" class="image-url upload'.$class.'" type="text" name="'.$name.'" value="'.$value.'" placeholder="'.__('Image URL', 'themeblvd').'" />'."\n";
			break;

		case 'logo_2x' :
			$data['title'] = __('Logo HiDPI Image', 'themeblvd');
			$data['select'] = __('Use for Logo', 'themeblvd');
			$output .= '<input id="'.$formfield.'" class="image-url upload'.$class.'" type="text" name="'.$name.'" value="'.$value.'" placeholder="'.__('URL for image twice the size of standard image', 'themeblvd') .'" />'."\n";
			break;

		case 'background' :
			$data['title'] = __('Select Background Image', 'themeblvd');
			$data['upload'] = __('Get Image', 'themeblvd');
			$output .= '<input id="'.$formfield.'" class="image-url upload'.$class.'" type="text" name="'.$name.'" value="'.$value.'" placeholder="'.__('Image URL', 'themeblvd') .'" />'."\n";
			break;

		case 'media' :
			$data['select'] = __('Insert Media', 'themeblvd');
			$data['class'] = '';
			$output .= '<textarea id="'.$formfield.'" class="image-url upload'.$class.'" name="'.$name.'" value="'.$value.'"></textarea>'."\n";
			break;

		case 'advanced' :
			$data['title'] = __('Select Image', 'themeblvd');
			$data['class'] = str_replace( 'tb-modal-hide-settings', 'tb-modal-advanced-image', $data['class'] );
			$data['select'] = __('Use Image', 'themeblvd');
			$output .= '<input id="'.$formfield.'" class="image-url upload'.$class.'" type="text" name="'.$name.'[src]" value="'.$args['value_src'].'" placeholder="'.__('No image chosen', 'themeblvd') .'" />'."\n";
			break;

		default :
			$output .= '<input id="'.$formfield.'" class="image-url upload'.$class.'" type="text" name="'.$name.'" value="'.$value.'" placeholder="'.__('No file chosen', 'themeblvd') .'" />'."\n";
	}

	$data = apply_filters('themeblvd_media_uploader_data', $data, $type);

	if ( ! $value || $type == 'video' || $type == 'media' ) {
		$output .= '<input id="upload-'.$formfield.'" class="trigger upload-button button" type="button" data-type="'.$type.'" data-title="'.$data['title'].'" data-select="'.$data['select'].'" data-class="'.$data['class'].'" data-upload="'.$data['upload'].'" data-remove="'.$data['remove'].'" data-send-back="'.$data['send_back'].'" value="'.$data['upload'].'" />'."\n";
	} else {
		$output .= '<input id="remove-'.$formfield.'" class="trigger remove-file button" type="button" data-type="'.$type.'" data-title="'.$data['title'].'" data-select="'.$data['select'].'" data-class="'.$data['class'].'" data-upload="'.$data['upload'].'" data-remove="'.$data['remove'].'" data-send-back="'.$data['send_back'].'" value="'.$data['remove'].'" />'."\n";
	}

	if ( $type == 'logo' ) {

		// $output .= '<span class="logo-label logo-url-label">'.__('Image URL', 'themeblvd').'</span>';

		$output .= '<div class="logo-atts clearfix">';

		$output .= '<div class="logo-width">';
		$output .= '<input id="'.$formfield.'_width" class="image-width upload'.$class.'" type="text" name="'.$width_name.'" value="'.$args['value_width'].'" />'."\n";
		$output .= '<span class="logo-label logo-width-label">'.__('Width', 'themeblvd').'</span>';
		$output .= '</div>';

		$output .= '<div class="logo-height">';
		$output .= '<input id="'.$formfield.'_height" class="image-height upload'.$class.'" type="text" name="'.$height_name.'" value="'.$args['value_height'].'" />'."\n";
		$output .= '<span class="logo-label logo-height-label">'.__('Height', 'themeblvd').'</span>';
		$output .= '</div>';

		$output .= '</div><!-- .logo-atts (end) -->';

	}

	$output .= '<div class="screenshot" id="' . $formfield . '-image">' . "\n";

	if ( ( $value || $args['value_src'] ) && $type != 'video' && $type != 'media' ) {

		$remove = '<a class="remove-image"></a>';

		$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value );

		if ( $image ) {

			$output .= '<img src="' . $value . '" alt="" />' . $remove;

		} else {

			$parts = explode( "/", $value );

			for ( $i = 0; $i < sizeof( $parts ); ++$i ) {
				$title = $parts[$i];
			}

			// Standard generic output if it's not an image.
			$title = __( 'View File', 'themeblvd' );
			$output .= '<div class="no-image"><span class="file_link"><a href="' . $value . '" target="_blank" rel="external">'.$title.'</a></span></div>';
		}
	}

	$output .= '</div>' . "\n";

	if ( $type == 'advanced' ) {
		$output .= '<input id="id-'.$formfield.'" class="image-id" name="'.$name.'[id]" type="hidden" value="'.$args['value_id'].'" />';
		$output .= '<input id="title-'.$formfield.'" class="image-title" name="'.$name.'[title]" type="hidden" value="'.$args['value_title'].'" />';
		$output .= '<input id="crop-'.$formfield.'" class="image-crop" name="'.$name.'[crop]" type="hidden" value="'.$args['value_crop'].'" />';
		// $output .= '<input id="width-'.$formfield.'" class="image-width" name="'.$name.'[width]" type="hidden" value="'.$args['value_width'].'" />';
		// $output .= '<input id="height-'.$formfield.'" class="image-height" name="'.$name.'[height]" type="hidden" value="'.$args['value_height'].'" />';
	}

	return $output;
}
endif;
