<?php
/**
 * Media Uploader
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.3.0
 */

/**
 * Generate the HTML output to link up with our
 * JavaScript for various media upload option types.
 *
 * @since Theme_Blvd 2.3.0
 *
 * @param  array $args {
 *     Arguments for uploader.
 *
 *     @type string $option_name   Prefix for form name attributes.
 *     @type string $id            A token to identify this field, extending onto $option_name like `$option_name[$id]`.
 *     @type string $name          Option to go deeper in top-level $id token, like `$option_name[$id][$name]`.
 *     @type string $type          Type of media uploader, `standard` (an image), `advanced` (an image w/crop settings), `logo`, `logo_2x`, `background`, `slider`, `video`, `media`.
 *     @type string $send_back     On type `standard` send back image url or attachment ID to text input - url, id.
 *     @type string $value         The value of the field, if present.
 *     @type string $value_id      Attachment ID used w/ `slider`, or stored ID in `advanced` type.
 *     @type string $value_src     Image URL used in `advanced` type.
 *     @type string $value_title   Title of attachment image used for `slider`, `advanced` and `background` types.
 *     @type string $value_alt     Alternative text for `advanced` and `background` types.
 *     @type string $value_caption Caption text for `advanced` and `background` types.
 *     @type string $value_width   Width value used for `logo` and `advanced` types.
 *     @type string $value_height  Height baclue used for `logo` and `advanced` types.
 *     @type string $value_crop    Crop size of image used with `advanced` and `background` types.
 * }
 * @param string $output Final HTML output for media uploader.
 */
function themeblvd_media_uploader( $args ) {

	$args = wp_parse_args( $args, array(
		'option_name'   => '',
		'id'            => '',
		'name'          => '',
		'type'          => 'standard',
		'send_back'     => 'url',
		'value'         => '',
		'value_id'      => '',
		'value_src'     => '',
		'value_title'   => '',
		'value_alt'     => '',
		'value_caption' => '',
		'value_width'   => '',
		'value_height'  => '',
		'value_crop'    => '',
	) );

	$output = '';

	$id = '';

	$id = strip_tags( strtolower( $args['id'] ) );

	$class = '';

	$int = '';

	$value = '';

	$name = '';

	$type = $args['type'];

	/*
	 * If a value is passed and we don't have a stored
	 * value, use the value that's passed through.
	 */
	$value = '';

	if ( $args['value'] ) {

		$value = $args['value'];

	} elseif ( isset( $args['value_src'] ) ) {

		$value = $args['value_src'];

	}

	// Set name formfield based on type.
	if ( 'slider' === $type ) {

		$name = $args['option_name'] . '[image]';

	} else {

		$name = $args['option_name'] . '[' . $id . ']';

	}

	if ( $args['name'] ) {

		$name = $name . '[' . $args['name'] . ']';

		$id = $id . '_' . $args['name'];

	}

	if ( $value ) {

		$class = ' has-file';

	}

	/*
	 * Allow multiple upload options on the same page
	 * with same ID. This could happen in the Layout
	 * Builder, for example.
	 */
	$formfield = uniqid( $id . '_' . rand() );

	/*
	 * Abstract for data sent to wp.media. This will
	 * get modified below, depending the type.
	 */
	$data = array(
		'title'     => __( 'Select Media', 'jumpstart' ),
		'select'    => __( 'Select', 'jumpstart' ),
		'upload'    => __( 'Upload', 'jumpstart' ),
		'remove'    => __( 'Remove', 'jumpstart' ),
		'send_back' => $args['send_back'],
		'class'     => 'tb-modal-hide-settings',
	);

	switch ( $type ) {

		case 'slider':
			$data['title'] = __( 'Slide Image', 'jumpstart' );

			$data['select'] = __( 'Use for Slide', 'jumpstart' );

			$data['upload'] = __( 'Get Image', 'jumpstart' );

			$help = __( 'You must use the \'Get Image\' button to insert an image for this slide to ensure that a proper image ID is used. This is what the locked icon represents.', 'jumpstart' );

			$output .= '<span class="locked"><span></span>';

			$output .= sprintf(
				'<a href="#" class="help-icon tb-icon-help-circled tooltip-link" title="%s"></a>',
				esc_attr( $help )
			);

			$output .= sprintf(
				'<input id="%s_id" class="image-id locked upload%s" type="text" name="%s[id]" placeholder="%s" value="%s" /></span>',
				$formfield,
				$class,
				esc_attr( $name ),
				esc_attr__( 'Image ID', 'jumpstart' ),
				esc_attr( $args['value_id'] )
			);

			$output .= "\n";

			$output .= sprintf(
				'<input id="%s" class="image-url upload%s" type="hidden" name="%s[url]" value="%s" />',
				$formfield,
				$class,
				esc_attr( $name ),
				esc_attr( $value )
			);

			$output .= "\n";

			$output .= sprintf(
				'<input id="%s_title" class="image-title upload%s" type="hidden" name="%s[title]" value="%s" />',
				$formfield,
				$class,
				$name,
				esc_attr( $args['value_title'] )
			);

			$output .= "\n";

			break;

		case 'video':
			$data['title'] = __( 'Select Video', 'jumpstart' );

			$data['select'] = __( 'Use Video', 'jumpstart' );

			$data['upload'] = __( 'Get Video', 'jumpstart' );

			$output .= sprintf(
				'<input id="%s" class="video-url upload%s" type="text" name="%s" value="%s" placeholder="%s" />',
				$formfield,
				$class,
				esc_attr( $name ),
				esc_attr( $value ),
				esc_attr__( 'Video URL', 'jumpstart' )
			);

			$output .= "\n";

			break;

		case 'logo':
			$data['title'] = __( 'Logo Image', 'jumpstart' );

			$data['select'] = __( 'Use for Logo', 'jumpstart' );

			$width_name = str_replace( '[image]', '[image_width]', $name );

			$height_name = str_replace( '[image]', '[image_height]', $name );

			$output .= sprintf(
				'<input id="%s" class="image-url upload%s" type="text" name="%s" value="%s" placeholder="%s" />',
				$formfield,
				$class,
				esc_attr( $name ),
				esc_attr( $value ),
				esc_attr__( 'Image URL', 'jumpstart' )
			);

			$output .= "\n";

			break;

		case 'logo_2x':
			$data['title'] = __( 'Logo HiDPI Image', 'jumpstart' );

			$data['select'] = __( 'Use for Logo', 'jumpstart' );

			$output .= sprintf(
				'<input id="%s" class="image-url upload%s" type="text" name="%s" value="%s" placeholder="%s" />',
				$formfield,
				$class,
				esc_attr( $name ),
				esc_attr( $value ),
				esc_attr__( 'URL for image twice the size of standard image', 'jumpstart' )
			);

			$output .= "\n";

			break;

		case 'background':
			$data['title'] = __( 'Select Background Image', 'jumpstart' );

			$data['upload'] = __( 'Get Image', 'jumpstart' );

			$output .= sprintf(
				'<input id="%s" class="image-url upload%s" type="text" name="%s" value="%s" placeholder="%s" />',
				$formfield,
				$class,
				esc_attr( $name ),
				esc_attr( $value ),
				esc_attr__( 'Image URL', 'jumpstart' )
			);

			$output .= "\n";

			break;

		case 'media':
			$data['select'] = __( 'Insert Media', 'jumpstart' );

			$data['class'] = '';

			$output .= sprintf(
				'<textarea id="%s" class="image-url upload%s" name="%s" value="%s"></textarea>',
				$formfield,
				$class,
				esc_attr( $name ),
				esc_attr( $value )
			);

			$output .= "\n";

			break;

		case 'advanced':
			$data['title'] = __( 'Select Image', 'jumpstart' );

			$data['class'] = str_replace( 'tb-modal-hide-settings', 'tb-modal-advanced-image', $data['class'] );

			$data['select'] = __( 'Use Image', 'jumpstart' );

			$output .= sprintf(
				'<input id="%s" class="image-url upload%s" type="text" name="%s[src]" value="%s" placeholder="%s" />',
				$formfield,
				$class,
				esc_attr( $name ),
				esc_attr( $args['value_src'] ),
				esc_attr__( 'No image chosen', 'jumpstart' )
			);

			$output .= "\n";

			break;

		default:
			$output .= sprintf(
				'<input id="%s" class="image-url upload%s" type="text" name="%s" value="%s" placeholder="%s" />',
				$formfield,
				$class,
				esc_attr( $name ),
				esc_attr( $value ),
				esc_attr__( 'No file chosen', 'jumpstart' )
			);

			$output .= "\n";

	}

	/**
	 * Filters the data-* attributes passed to the
	 * WordPress media uploader.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param array  $data Data attributes.
	 * @param string $type Option type.
	 */
	$data = apply_filters( 'themeblvd_media_uploader_data', $data, $type );

	if ( ! $value || 'video' === $type || 'media' === $type ) {

		$output .= sprintf(
			'<input id="upload-%s" class="trigger upload-button button" type="button" data-type="%s" data-title="%s" data-select="%s" data-class="%s" data-upload="%s" data-remove="%s" data-send-back="%s" value="%s" />',
			$formfield,
			esc_attr( $type ),
			esc_attr( $data['title'] ),
			esc_attr( $data['select'] ),
			esc_attr( $data['class'] ),
			esc_attr( $data['upload'] ),
			esc_attr( $data['remove'] ),
			esc_attr( $data['send_back'] ),
			esc_attr( $data['upload'] )
		);

	} else {

		$output .= sprintf(
			'<input id="remove-%s" class="trigger remove-file button" type="button" data-type="%s" data-title="%s" data-select="%s" data-class="%s" data-upload="%s" data-remove="%s" data-send-back="%s" value="%s" />',
			$formfield,
			esc_attr( $type ),
			esc_attr( $data['title'] ),
			esc_attr( $data['select'] ),
			esc_attr( $data['class'] ),
			esc_attr( $data['upload'] ),
			esc_attr( $data['remove'] ),
			esc_attr( $data['send_back'] ),
			esc_attr( $data['remove'] )
		);

	}

	$output .= "\n";

	// Add extra output for `logo` type option.
	if ( 'logo' === $type ) {

		$output .= '<div class="logo-atts clearfix">' . "\n";

		$output .= '<div class="logo-width">' . "\n";

		$output .= sprintf(
			'<input id="%s_width" class="image-width upload%s" type="text" name="%s" value="%s" />',
			$formfield,
			$class,
			esc_attr( $width_name ),
			esc_attr( $args['value_width'] )
		);

		$output .= "\n";

		$output .= sprintf(
			'<span class="logo-label logo-width-label">%s</span>',
			esc_html__( 'Width', 'jumpstart' )
		);

		$output .= '</div>' . "\n";

		$output .= '<div class="logo-height">' . "\n";

		$output .= sprintf(
			'<input id="%s_height" class="image-height upload%s" type="text" name="%s" value="%s" />',
			$formfield,
			$class,
			esc_attr( $height_name ),
			esc_attr( $args['value_height'] )
		);

		$output .= "\n";

		$output .= sprintf(
			'<span class="logo-label logo-height-label">%s</span>',
			esc_html__( 'Height', 'jumpstart' )
		);

		$output .= "\n";

		$output .= '</div>' . "\n";

		$output .= '</div><!-- .logo-atts (end) -->' . "\n";

	}

	$output .= sprintf(
		'<div class="screenshot" id="%s-image">',
		$formfield
	);

	$output .= "\n";

	// Display saved value for option.
	if ( ( $value || $args['value_src'] ) && 'video' !== $type && 'media' !== $type ) {

		$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value );

		if ( $image ) {

			$output .= sprintf(
				'<img src="%s" alt="" />',
				esc_attr( $value )
			);

			$output .= '<a class="remove-image"></a>';

		} else {

			$parts = explode( '/', $value );

			for ( $i = 0; $i < sizeof( $parts ); ++$i ) {
				$title = $parts[ $i ];
			}

			// Standard generic output if it's not an image.
			$title = esc_attr__( 'View File', 'jumpstart' );

			$output .= '<div class="no-image">' . "\n";

			$output .= '<span class="file_link">' . "\n";

			$output .= sprintf(
				'<a href="%s" target="_blank" rel="external">%s</a>',
				esc_attr( $value ),
				esc_attr( $title )
			);

			$output .= "</span>\n";

			$output .= "</div>\n";

		}
	}

	$output .= '</div>' . "\n";

	if ( 'advanced' === $type || 'background' === $type ) {

		if ( 'background' === $type ) {
			$advanced_name = themeblvd_remove_trailing_char( $name, ']' ) . '_id]';
		} else {
			$advanced_name = $name . '[id]';
		}

		$output .= sprintf(
			'<input id="id-%s" class="image-id" name="%s" type="hidden" value="%s" />',
			$formfield,
			esc_attr( $advanced_name ),
			esc_attr( $args['value_id'] )
		);

		if ( 'background' === $type ) {
			$advanced_name = themeblvd_remove_trailing_char( $name, ']' ) . '_title]';
		} else {
			$advanced_name = $name . '[title]';
		}

		$output .= sprintf(
			'<input id="title-%s" class="image-title" name="%s" type="hidden" value="%s" />',
			$formfield,
			esc_attr( $advanced_name ),
			esc_attr( $args['value_title'] )
		);

		if ( 'background' === $type ) {
			$advanced_name = themeblvd_remove_trailing_char( $name, ']' ) . '_alt]';
		} else {
			$advanced_name = $name . '[alt]';
		}

		$output .= sprintf(
			'<input id="alt-%s" class="image-alt" name="%s" type="hidden" value="%s" />',
			$formfield,
			esc_attr( $advanced_name ),
			esc_attr( $args['value_alt'] )
		);

		if ( 'background' === $type ) {
			$advanced_name = themeblvd_remove_trailing_char( $name, ']' ) . '_caption]';
		} else {
			$advanced_name = $name . '[caption]';
		}

		$output .= sprintf(
			'<input id="caption-%s" class="image-caption" name="%s" type="hidden" value="%s" />',
			$formfield,
			esc_attr( $advanced_name ),
			esc_attr( $args['value_caption'] )
		);

		if ( 'advanced' === $type ) {

			$output .= sprintf(
				'<input id="crop-%s" class="image-crop" name="%s" type="hidden" value="%s" />',
				$formfield,
				esc_attr( $name . '[crop]' ),
				esc_attr( $args['value_crop'] )
			);

		}

	}

	return $output;

}
