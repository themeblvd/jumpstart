<?php
/**
 * Functions for all option sanitization.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     Jump_Start
 */

/**
 * Sanitization Filters
 *
 * This function gets hooked to admin_init when the framework
 * runs on the admin side, but why have this in a function? --
 * Because on the frontend of the site, if the options have
 * never been saved we can call this function to allow default
 * option values to be generated. This will almost never happen,
 * so this is our way of including them, but only if needed;
 * no point in adding these filters every time the frontend loads.
 *
 * @since 2.2.0
 */
function themeblvd_add_sanitization() {
	add_filter( 'themeblvd_sanitize_hidden', 'themeblvd_sanitize_hidden', 10, 2 );
	add_filter( 'themeblvd_sanitize_text', 'themeblvd_sanitize_text' );
	add_filter( 'themeblvd_sanitize_textarea', 'themeblvd_sanitize_textarea' );
	add_filter( 'themeblvd_sanitize_select', 'themeblvd_sanitize_text', 10, 2 );
	add_filter( 'themeblvd_sanitize_radio', 'themeblvd_sanitize_text', 10, 2 );
	add_filter( 'themeblvd_sanitize_images', 'themeblvd_sanitize_text', 10, 2 );
	add_filter( 'themeblvd_sanitize_checkbox', 'themeblvd_sanitize_checkbox' );
	add_filter( 'themeblvd_sanitize_multicheck', 'themeblvd_sanitize_multicheck', 10, 2 );
	add_filter( 'themeblvd_sanitize_color', 'themeblvd_sanitize_hex' );
	add_filter( 'themeblvd_sanitize_gradient', 'themeblvd_sanitize_gradient' );
	add_filter( 'themeblvd_sanitize_button', 'themeblvd_sanitize_button' );
	add_filter( 'themeblvd_sanitize_upload', 'themeblvd_sanitize_upload' );
	add_filter( 'themeblvd_sanitize_background', 'themeblvd_sanitize_background' );
	add_filter( 'themeblvd_background_repeat', 'themeblvd_sanitize_background_repeat' );
	add_filter( 'themeblvd_background_position', 'themeblvd_sanitize_background_position' );
	add_filter( 'themeblvd_background_attachment', 'themeblvd_sanitize_background_attachment' );
	add_filter( 'themeblvd_background_size', 'themeblvd_sanitize_background_size' );
	add_filter( 'themeblvd_sanitize_background_video', 'themeblvd_sanitize_background_video' );
	add_filter( 'themeblvd_sanitize_typography', 'themeblvd_sanitize_typography' );
	add_filter( 'themeblvd_font_face', 'themeblvd_sanitize_font_face' );
	add_filter( 'themeblvd_font_style', 'themeblvd_sanitize_font_style' );
	add_filter( 'themeblvd_font_weight', 'themeblvd_sanitize_font_weight' );
	add_filter( 'themeblvd_font_face', 'themeblvd_sanitize_font_face' );
	add_filter( 'themeblvd_sanitize_columns', 'themeblvd_sanitize_columns' );
	add_filter( 'themeblvd_sanitize_tabs', 'themeblvd_sanitize_tabs' );
	add_filter( 'themeblvd_sanitize_testimonials', 'themeblvd_sanitize_testimonials' );
	add_filter( 'themeblvd_sanitize_text_blocks', 'themeblvd_sanitize_text_blocks' );
	add_filter( 'themeblvd_sanitize_toggles', 'themeblvd_sanitize_toggles' );
	add_filter( 'themeblvd_sanitize_content', 'themeblvd_sanitize_content' );
	add_filter( 'themeblvd_sanitize_logo', 'themeblvd_sanitize_logo' );
	add_filter( 'themeblvd_sanitize_social_media', 'themeblvd_sanitize_social_media' );
	add_filter( 'themeblvd_sanitize_share', 'themeblvd_sanitize_share' );
	add_filter( 'themeblvd_sanitize_slide', 'themeblvd_sanitize_slide' );
	add_filter( 'themeblvd_sanitize_slider', 'themeblvd_sanitize_slider' );
	add_filter( 'themeblvd_sanitize_logos', 'themeblvd_sanitize_logos' );
	add_filter( 'themeblvd_sanitize_price_cols', 'themeblvd_sanitize_price_cols' );
	add_filter( 'themeblvd_sanitize_conditionals', 'themeblvd_sanitize_conditionals', 10, 3 );
	add_filter( 'themeblvd_sanitize_editor', 'themeblvd_sanitize_editor' );
	add_filter( 'themeblvd_sanitize_editor_modal', 'themeblvd_sanitize_editor' );
	add_filter( 'themeblvd_sanitize_code', 'themeblvd_sanitize_editor' );
	add_filter( 'themeblvd_sanitize_locations', 'themeblvd_sanitize_locations' );
	add_filter( 'themeblvd_sanitize_geo', 'themeblvd_sanitize_geo' );
	add_filter( 'themeblvd_sanitize_sectors', 'themeblvd_sanitize_sectors' );
	add_filter( 'themeblvd_sanitize_datasets', 'themeblvd_sanitize_datasets' );
	add_filter( 'themeblvd_sanitize_bars', 'themeblvd_sanitize_bars' );
	add_filter( 'themeblvd_sanitize_buttons', 'themeblvd_sanitize_buttons' );
}

/**
 * Hidden
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_hidden( $input, $option ) {

	if ( $option['id'] == 'framework_version' ) {

		$output = TB_FRAMEWORK_VERSION;

	} else if ( $option['id'] == 'theme_version' ) {

		$theme = wp_get_theme( get_template() );
		$output = $theme->get('Version');

	} else if ( $option['id'] == 'theme_base' ) {

		$output = themeblvd_get_base();

	} else {

		$output = themeblvd_kses( $input );
		$output = htmlspecialchars_decode( $output );
		$output = str_replace( "\r\n", "\n", $output );

	}
	return $output;
}

/**
 * Text
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_text( $input ) {

	if ( current_user_can('unfiltered_html') ) {
		$output = $input;
	} else {
		$output = themeblvd_kses($input);
		$output = htmlspecialchars_decode($output);
	}

	$output = str_replace( "\r\n", "\n", $output );

	return $output;
}

/**
 * Textarea
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_textarea( $input ) {

	if ( current_user_can('unfiltered_html') ) {
		$output = $input;
	} else {
		$output = themeblvd_kses($input);
		$output = htmlspecialchars_decode($output);
	}

	$output = str_replace( "\r\n", "\n", $output );

	return $output;
}

/**
 * Checkbox
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_checkbox( $input ) {
	if ( $input && $input !== '0' ) {
		$output = "1";
	} else {
		$output = "0";
	}
	return $output;
}

/**
 * Multicheck
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_multicheck( $input, $option ) {
	$output = array();
	if ( is_array( $input ) ) {
		foreach ( $option['options'] as $key => $value ) {
			$output[$key] = "0";
		}
		foreach ( $input as $key => $value ) {
			if ( array_key_exists( $key, $option['options'] ) && $value ) {
				$output[$key] = "1";
			}
		}
	}
	return $output;
}

/**
 * Uploader
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_upload( $input ) {

	if ( is_array( $input ) ) {

		// Remove admin attachment restrains
		add_filter( 'editor_max_image_size', 'themeblvd_editor_max_image_size' );

		$output = array(
			'id'		=> 0,
			'src'		=> '',
			'full'		=> '',
			'title'		=> '',
			'crop'		=> '',
			'width'		=> 0,
			'height'	=> 0
		);

		if ( isset( $input['id'] ) ) {
			$output['id'] = intval( $input['id'] );
			$full = wp_get_attachment_image_src( $output['id'], 'tb_x_large' );
			$output['full'] = $full[0];
		}

		if ( isset( $input['src'] ) ) {
			$output['src'] = wp_kses( $input['src'], array() );
		}

		if ( isset( $input['id'] ) ) {
			$output['id'] = intval( $input['id'] );
		}

		if ( isset( $input['title'] ) ) {
			$output['title'] = wp_kses( $input['title'], array() );
		}

		if ( isset( $input['crop'] ) ) {
			$output['crop'] = wp_kses( $input['crop'], array() );
		}

		// Restore admin attachment restraints
		remove_filter( 'editor_max_image_size', 'themeblvd_editor_max_image_size' );

	} else {
		$output = wp_kses( $input, array() );
	}

	return $output;
}

/**
 * Check that the key value sent is valid
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_enum( $input, $option ) {
	$output = '';
	if ( isset( $option['options'] ) && is_array( $option['options'] ) ) {

		// Manual select, with standard options set
		if ( array_key_exists( $input, $option['options'] ) ) {
			$output = $input;
		}

	} else if ( isset( $option['select'] ) ) {

		// Dynamic Select
		$options = themeblvd_get_select( $option['select'], true );

		if ( array_key_exists( $input, $options ) ) {
			$output = $input;
		}
	}
	return $output;
}

/**
 * Background
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_background( $input ) {

	$output = wp_parse_args( $input, array(
		'color' 		=> '',
		'image'  		=> '',
		'repeat'  		=> 'repeat',
		'position' 		=> 'top center',
		'attachment' 	=> 'scroll',
		'size'			=> 'auto'
	) );

	if ( isset( $input['color'] ) ) {
		$output['color'] = apply_filters( 'themeblvd_sanitize_hex', $input['color'] );
	}

	if ( isset( $input['image'] ) ) {
		$output['image'] = apply_filters( 'themeblvd_sanitize_upload', $input['image'] );
	}

	if ( isset( $input['repeat'] ) ) {
		$output['repeat'] = apply_filters( 'themeblvd_background_repeat', $input['repeat'] );
	}

	if ( isset( $input['position'] ) ) {
		$output['position'] = apply_filters( 'themeblvd_background_position', $input['position'] );
	}

	if ( isset( $input['attachment'] ) ) {
		$output['attachment'] = apply_filters( 'themeblvd_background_attachment', $input['attachment'] );
	}

	if ( isset( $input['size'] ) ) {
		$output['size'] = apply_filters( 'themeblvd_background_size', $input['size'] );
	}

	return $output;
}

/**
 * Background Video
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_background_video( $input ) {

	$output = array(
		'mp4'		=> '',
		'ratio'		=> '16:9',
		'fallback'	=> ''
	);

	if ( isset( $input['mp4'] ) ) {
		$output['mp4'] = apply_filters( 'themeblvd_sanitize_upload', $input['mp4'] );
	}

	if ( isset( $input['fallback'] ) ) {
		$output['fallback'] = apply_filters( 'themeblvd_sanitize_upload', $input['fallback'] );
	}

	if ( isset( $input['ratio'] ) && strpos($input['ratio'], ':') !== false ) {
		$output['ratio'] = apply_filters( 'themeblvd_sanitize_text', $input['ratio'] );
	}

	return $output;
}

/**
 * Background - repeat
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_background_repeat( $value ) {
	$recognized = themeblvd_recognized_background_repeat();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'themeblvd_default_background_repeat', current( $recognized ) );
}

/**
 * Background - position
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_background_position( $value ) {
	$recognized = themeblvd_recognized_background_position();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'themeblvd_default_background_position', current( $recognized ) );
}

/**
 * Background - attachment
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_background_attachment( $value ) {
	$recognized = themeblvd_recognized_background_attachment();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'themeblvd_default_background_attachment', current( $recognized ) );
}

/**
 * Background - size
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_background_size( $value ) {
	$recognized = themeblvd_recognized_background_size();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'themeblvd_default_background_size', current( $recognized ) );
}

/**
 * Get recognized background positions
 *
 * @since 2.2.0
 */
function themeblvd_recognized_background_position() {
	$default = array(
		'center top'    => __('Background Position: Top Center', 'jumpstart'),
		'left top'      => __('Background Position: Top Left', 'jumpstart'),
		'right top'     => __('Background Position: Top Right', 'jumpstart'),
		'center center' => __('Background Position: Middle Center', 'jumpstart'),
		'left center'   => __('Background Position: Middle Left', 'jumpstart'),
		'right center'  => __('Background Position: Middle Right', 'jumpstart'),
		'center bottom' => __('Background Position: Bottom Center', 'jumpstart'),
		'left bottom'   => __('Background Position: Bottom Left', 'jumpstart'),
		'right bottom'  => __('Background Position: Bottom Right', 'jumpstart')
	);
	return apply_filters( 'themeblvd_recognized_background_position', $default );
}

/**
 * Get recognized background attachment
 *
 * @since 2.2.0
 */
function themeblvd_recognized_background_attachment() {
	$default = array(
		'scroll' 	=> __('Background Scrolling: Normal', 'jumpstart'),
		'parallax'  => __('Background Scrolling: Parallax Effect', 'jumpstart'),
		'fixed'  	=> __('Background Scrolling: Fixed in Place', 'jumpstart'),
	);
	return apply_filters( 'themeblvd_recognized_background_attachment', $default );
}

/**
 * Get recognized background repeat settings
 *
 * @since 2.2.0
 */
function themeblvd_recognized_background_repeat() {
	$default = array(
		'no-repeat' => __('Background Repeat: No Repeat', 'jumpstart'),
		'repeat-x'  => __('Background Repeat: Repeat Horizontally', 'jumpstart'),
		'repeat-y'  => __('Background Repeat: Repeat Vertically', 'jumpstart'),
		'repeat'    => __('Background Repeat: Repeat All', 'jumpstart')
	);
	return apply_filters( 'themeblvd_recognized_background_repeat', $default );
}

/**
 * Get recognized background positions
 *
 * @since 2.5.0
 */
function themeblvd_recognized_background_size() {
	$default = array(
		'auto'    	=> __('Background Size: Auto', 'jumpstart'),
		'cover'     => __('Background Size: Cover', 'jumpstart'),
		'contain'   => __('Background Size: Contain', 'jumpstart'),
		'100% 100%' => __('Background Size: 100% x 100%', 'jumpstart'),
		'100% auto' => __('Background Size: Fit Horizontally', 'jumpstart'),
		'auto 100%' => __('Background Size: Fit Vertically', 'jumpstart')
	);
	return apply_filters( 'themeblvd_recognized_background_size', $default );
}

/**
 * Typography
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_typography( $input ) {
	$output = wp_parse_args( $input, array(
		'size' 			=> '',
		'face'  		=> '',
		'style' 		=> '',
		'weight'		=> '',
		'color' 		=> '',
		'google' 		=> '',
		'typekit'		=> '',
		'typekit_kit'	=> ''
	) );
	$output['size']  = apply_filters( 'themeblvd_font_size', $output['size'] );
	$output['face']  = apply_filters( 'themeblvd_font_face', $output['face'] );
	$output['style'] = apply_filters( 'themeblvd_font_style', $output['style'] );
	$output['weight'] = apply_filters( 'themeblvd_font_weight', $output['weight'] );
	$output['color'] = apply_filters( 'themeblvd_color', $output['color'] );
	$output['google'] = str_replace('"', '', $output['google'] );
	$output['google'] = apply_filters( 'themeblvd_sanitize_text', $output['google'] );
	$output['typekit'] = str_replace('"', '', $output['typekit'] );
	$output['typekit'] = apply_filters( 'themeblvd_sanitize_text', $output['typekit'] );
	$output['typekit_kit'] = themeblvd_kses($output['typekit_kit']);
	return $output;
}

/**
 * Typography - font size
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_font_size( $value ) {
	$recognized = themeblvd_recognized_font_sizes();
	$value = preg_replace('/px/','', $value);
	if ( in_array( (int) $value, $recognized ) ) {
		return (int) $value;
	}
	return (int) apply_filters( 'themeblvd_default_font_size', $recognized );
}

/**
 * Typography - font style
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_font_style( $value ) {
	$recognized = themeblvd_recognized_font_styles();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'themeblvd_default_font_style', 'normal' );
}

/**
 * Typography - font weight
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_font_weight( $value ) {
	$recognized = themeblvd_recognized_font_weights();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'themeblvd_default_font_weight', '400' );
}

/**
 * Typography - font face
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_font_face( $value ) {
	$recognized = themeblvd_recognized_font_faces();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'themeblvd_default_font_face', current( $recognized ) );
}

/**
 * Columns
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_columns( $input ) {
	return wp_kses( $input, array() );
}

/**
 * Tabs
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_tabs( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id] = array();
			$output[$item_id]['title'] = themeblvd_sanitize_text( $item['title'] );
			$output[$item_id]['content'] = themeblvd_sanitize_content( $item['content'] );
		}
	}

	return $output;
}

/**
 * Testimonials
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_testimonials( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id]['text'] = apply_filters( 'themeblvd_sanitize_textarea', $item['text'] );
			$output[$item_id]['name'] = apply_filters( 'themeblvd_sanitize_text', $item['name'] );
			$output[$item_id]['tagline'] = apply_filters( 'themeblvd_sanitize_text', $item['tagline'] );
			$output[$item_id]['company'] = apply_filters( 'themeblvd_sanitize_text', $item['company'] );
			$output[$item_id]['company_url'] = apply_filters( 'themeblvd_sanitize_text', $item['company_url'] );
			$output[$item_id]['image'] = apply_filters( 'themeblvd_sanitize_upload', $item['image'] );
		}
	}

	return $output;
}

/**
 * Text Blocks
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_text_blocks( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {

			$output[$item_id]['text'] = apply_filters( 'themeblvd_sanitize_textarea', $item['text'] );
			$output[$item_id]['size'] = apply_filters( 'themeblvd_sanitize_text', $item['size'] );
			$output[$item_id]['color'] = apply_filters( 'themeblvd_sanitize_color', $item['color'] );

			$output[$item_id]['apply_bg_color'] = '0';

			if ( ! empty($item['apply_bg_color']) ) {
				$output[$item_id]['apply_bg_color'] = '1';
			}

			if ( ! empty($item['bg_color']) ) {
				$output[$item_id]['bg_color'] = apply_filters( 'themeblvd_sanitize_color', $item['bg_color'] );
			}

			if ( ! empty($item['bg_opacity']) ) {
				$output[$item_id]['bg_opacity'] = apply_filters( 'themeblvd_sanitize_text', $item['bg_opacity'] );
			}

			$output[$item_id]['bold'] = '0';

			if ( ! empty($item['bold']) ) {
				$output[$item_id]['bold'] = '1';
			}

			$output[$item_id]['italic'] = '0';

			if ( ! empty($item['italic']) ) {
				$output[$item_id]['italic'] = '1';
			}

			$output[$item_id]['caps'] = '0';

			if ( ! empty($item['caps']) ) {
				$output[$item_id]['caps'] = '1';
			}

			$output[$item_id]['suck_down'] = '0';

			if ( ! empty($item['suck_down']) ) {
				$output[$item_id]['suck_down'] = '1';
			}

			$output[$item_id]['wpautop'] = '0';

			if ( ! empty($item['wpautop']) ) {
				$output[$item_id]['wpautop'] = '1';
			}

		}
	}

	return $output;
}

/**
 * Toggles
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_toggles( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {

			$output[$item_id] = array();

			$output[$item_id]['title'] = apply_filters( 'themeblvd_sanitize_text', $item['title'] );
			$output[$item_id]['content'] = apply_filters( 'themeblvd_sanitize_textarea', $item['content'] );

			$output[$item_id]['wpautop'] = '0';

			if ( ! empty($item['wpautop']) ) {
				$output[$item_id]['wpautop'] = '1';
			}

			$output[$item_id]['open'] = '0';

			if ( ! empty($item['open']) ) {
				$output[$item_id]['open'] = '1';
			}
		}
	}

	return $output;
}

/**
 * Dynamic Content
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_content( $input ) {

	$allowedtags = themeblvd_allowed_tags();
	$output = array();

	// Verify type
	$types = array( 'widget', 'current', 'page', 'raw' );
	if ( in_array( $input['type'], $types ) ) {
		$output['type'] = $input['type'];
	} else {
		$output['type'] = null;
	}

	// Add type's corresponding option
	switch ( $output['type'] ) {

		case 'widget' :
			if ( isset( $input['sidebar'] ) ) {
				$output['sidebar'] = $input['sidebar'];
			} else {
				$output['sidebar'] = null;
			}
			break;

		case 'page' :
			$output['page'] = $input['page'];
			break;

		case 'raw' :
			$output['raw'] = apply_filters( 'themeblvd_sanitize_textarea', $input['raw'] );
			$output['raw_format'] = '0';
			if ( ! empty( $input['raw_format'] ) ) {
				$output['raw_format'] = '1';
			}
			break;
	}

	return $output;
}

/**
 * Logo
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_logo( $input ) {

	$output = array();

	// Type
	if ( is_array( $input ) && isset( $input['type'] ) ) {
		$output['type'] = $input['type'];
	}

	// Custom
	if ( isset( $input['custom'] ) ) {
		$output['custom'] = sanitize_text_field( $input['custom'] );
	}
	if ( isset( $input['custom_tagline'] ) ) {
		$output['custom_tagline'] = sanitize_text_field( $input['custom_tagline'] );
	}

	// Image (standard)
	if ( isset( $input['image'] ) ) {

		$filetype = wp_check_filetype( $input['image'] );

		if ( $filetype["ext"] ) {

			$output['image'] = $input['image'];

			if ( isset( $input['image_width'] ) ) {
				$output['image_width'] = wp_kses( $input['image_width'], array() );
			}

			if ( isset( $input['image_height'] ) ) {
				$output['image_height'] = wp_kses( $input['image_height'], array() );
			}

		} else {
			$output['image'] = null;
			$output['image_width'] = null;
			$output['image_height'] = null;
		}
	}

	// Image (for retina)
	if ( isset( $input['image_2x'] ) ) {
		$filetype = wp_check_filetype( $input['image_2x'] );
		if ( $filetype["ext"] ) {
			$output['image_2x'] = $input['image_2x'];
		} else {
			$output['image_2x'] = null;
		}
	}

	return $output;
}

/**
 * Social Media Buttons
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_social_media( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id] = array();
			$output[$item_id]['icon'] = wp_kses( $item['icon'], array() );
			$output[$item_id]['url'] = wp_kses( $item['url'], array() );
			$output[$item_id]['label'] = wp_kses( $item['label'], array() );
			$output[$item_id]['target'] = wp_kses( $item['target'], array() );
		}
	}

	return $output;
}

/**
 * Social Media Buttons
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_share( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id] = array();
			$output[$item_id]['icon'] = wp_kses( $item['icon'], array() );
			$output[$item_id]['label'] = wp_kses( $item['label'], array() );
		}
	}

	return $output;
}

/**
 * jQuery UI slider
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_slide( $input ) {
	return wp_kses( $input, array() );
}

/**
 * Simple Slider
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_slider( $input ) {

	// Remove admin attachment restraints.
	add_filter( 'editor_max_image_size', 'themeblvd_editor_max_image_size' );

	$output = array();

	if ( $input && is_array( $input ) ) {

		// Setup crop size.

		$crop = 'full';

		if ( ! empty( $input['crop'] ) ) {
			$crop = wp_kses( $input['crop'], array() );
		}

		if ( isset( $input['crop'] ) ) {
			unset( $input['crop'] );
		}

		// Begin loop through slides.

		foreach ( $input as $item_id => $item ) {

			$output[$item_id] = array();

			// Crop size
			$output[$item_id]['crop'] = $crop;

			// Attachment ID
			$output[$item_id]['id'] = intval( $item['id'] );

			// Attachment title
			$output[$item_id]['alt'] = get_the_title( $output[$item_id]['id'] );

			if ( $output[$item_id]['id'] ) {

				$attachment = wp_get_attachment_image_src( $output[$item_id]['id'], $crop );
				$downsize = themeblvd_image_downsize( $attachment, $output[$item_id]['id'], $crop );
				$output[$item_id]['src'] = apply_filters( 'themeblvd_sanitize_upload', $downsize[0] );

				$thumb = wp_get_attachment_image_src( $output[$item_id]['id'], 'tb_thumb' );
				$output[$item_id]['thumb'] = apply_filters( 'themeblvd_sanitize_upload', $thumb[0] );

			} else {

				if ( isset( $item['src'] ) ) {
					$output[$item_id]['src'] = wp_kses( $item['src'], array() );
				} else {
					$output[$item_id]['src'] = '';
				}

				if ( isset( $item['thumb'] ) ) {
					$output[$item_id]['thumb'] = wp_kses( $item['thumb'], array() );
				} else {
					$output[$item_id]['thumb'] = '';
				}

			}

			// Slide info
			$output[$item_id]['title'] = wp_kses( $item['title'], array() );
			$output[$item_id]['desc'] = apply_filters( 'themeblvd_sanitize_textarea', $item['desc'] );

			// Link
			$output[$item_id]['link'] = wp_kses( $item['link'], array() );

			if ( $output[$item_id]['link'] == 'none' ) {
				$output[$item_id]['link'] = '';
			}

			if ( isset($item['link_url']) ) {
				$output[$item_id]['link_url'] = wp_kses( $item['link_url'], array() );
			}

		}
	}

	// Restore admin attachment restrains
	remove_filter( 'editor_max_image_size', 'themeblvd_editor_max_image_size' );

	return $output;
}

/**
 * Partner Logos
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_logos( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {

			$output[$item_id] = array();

			// Attachment ID
			$output[$item_id]['id'] = intval( $item['id'] );

			// Attachment title
			$output[$item_id]['alt'] = get_the_title( $output[$item_id]['id'] );

			if ( $output[$item_id]['id'] ) {

				$attachment = wp_get_attachment_image_src( $output[$item_id]['id'], 'full' );
				$output[$item_id]['src'] = apply_filters( 'themeblvd_sanitize_upload', $attachment[0] );

				$thumb = wp_get_attachment_image_src( $output[$item_id]['id'], 'tb_thumb' );
				$output[$item_id]['thumb'] = apply_filters( 'themeblvd_sanitize_upload', $thumb[0] );

			} else {

				if ( isset( $item['src'] ) ) {
					$output[$item_id]['src'] = wp_kses( $item['src'], array() );
				} else {
					$output[$item_id]['src'] = '';
				}

				if ( isset( $item['thumb'] ) ) {
					$output[$item_id]['thumb'] = wp_kses( $item['thumb'], array() );
				} else {
					$output[$item_id]['thumb'] = '';
				}

			}

			// Partner Name, description, and Link
			$output[$item_id]['name'] = wp_kses( $item['name'], array() );
			$output[$item_id]['link'] = wp_kses( $item['link'], array() );

		}
	}

	return $output;
}

/**
 * Pricing table columns
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_price_cols( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {

			$output[$item_id] = array();

			$output[$item_id]['highlight'] = wp_kses( $item['highlight'], array() );
			$output[$item_id]['title'] = wp_kses( $item['title'], array() );

			if ( isset( $item['title_subline'] ) ) {
				$output[$item_id]['title_subline'] = wp_kses( $item['title_subline'], array() );
			}

			$output[$item_id]['price'] = wp_kses( $item['price'], array() );
			$output[$item_id]['price_subline'] = wp_kses( $item['price_subline'], array() );
			$output[$item_id]['features'] = apply_filters( 'themeblvd_sanitize_textarea', $item['features'] );

			if ( isset( $item['button_color'] ) ) {
				$output[$item_id]['button_color'] = wp_kses( $item['button_color'], array() );
			}

			if ( isset( $item['button_custom'] ) ) {
				$output[$item_id]['button_custom'] = apply_filters( 'themeblvd_sanitize_button', $item['button_custom'] );
			}

			if ( isset( $item['button_text'] ) ) {
				$output[$item_id]['button_text'] = wp_kses( $item['button_text'], array() );
			}

			if ( isset( $item['button_url'] ) ) {
				$output[$item_id]['button_url'] = wp_kses( $item['button_url'], array() );
			}

			if ( isset( $item['button_size'] ) ) {
				$output[$item_id]['button_size'] = wp_kses( $item['button_size'], array() );
			}

			if ( isset( $item['button_icon_before'] ) ) {
				$output[$item_id]['button_icon_before'] = wp_kses( $item['button_icon_before'], array() );
			}

			if ( isset( $item['button_icon_after'] ) ) {
				$output[$item_id]['button_icon_after'] = wp_kses( $item['button_icon_after'], array() );
			}

			if ( empty( $item['popout'] ) ) {
				$output[$item_id]['popout'] = '0';
			} else {
				$output[$item_id]['popout'] = '1';
			}

			if ( empty( $item['button'] ) ) {
				$output[$item_id]['button'] = '0';
			} else {
				$output[$item_id]['button'] = '1';
			}

		}
	}

	return $output;
}

/**
 * Conditionals
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_conditionals( $input, $sidebar_slug = null, $sidebar_id = null ) {

	$conditionals = themeblvd_conditionals_config();
	$output = array();

	// Prepare items that weren't straight-up arrays
	// gifted on a platter for us.
	foreach ( array('post', 'page', 'tag', 'portfolio_item', 'portfolio_tag', 'product_tag') as $type ) {
		if ( ! empty( $input[$type] ) ) {
			$input[$type] = str_replace(' ', '', $input[$type] );
			$input[$type] = explode( ',', $input[$type] );
		}
	}

	// Now loop through each group and then each item
	foreach ( $input as $type => $group ) {
		if ( is_array( $group ) && ! empty( $group ) ) {
			foreach ( $group as $item_id ) {

				$name = '';

				switch ( $type ) {

					case 'page' :
					case 'post' :
					case 'portfolio_item' :
					case 'forum' :

						$post_id = themeblvd_post_id_by_name( $item_id, $type );
						$post = get_post( $post_id );

						if ( $post ) {
							$name = $post->post_title;
						}
						break;

					case 'category' :
					case 'posts_in_category' :
					case 'tag' :
					case 'portfolio' :
					case 'portfolio_items_in_portfolio' :
					case 'portfolio_tag' :
					case 'products_in_cat' :
					case 'product_cat' :
					case 'product_tag' :

						if ( $type == 'category' || $type == 'posts_in_category' ) {
							$tax = 'category';
						} else if ( $type == 'tag' ) {
							$tax = 'post_tag';
						} else if ( $type == 'portfolio' || $type == 'portfolio_items_in_portfolio' ) {
							$tax = 'portfolio';
						} else if ( $type == 'portfolio_tag' ) {
							$tax = 'portfolio_tag';
						} else if ( $type == 'product_cat' || $type == 'products_in_cat'  ) {
							$tax = 'product_cat';
						} else if ( $type == 'product_tag' ) {
							$tax = 'product_tag';
						}

						if ( ! empty($tax) ) {

							$term = get_term_by( 'slug', $item_id, $tax );

							if ( $term ) {
								$name = $term->name;
							}
						}
						break;

					case 'portfolio_top' :
					case 'product_top' :
					case 'forum_top' :
					case 'top' :
						$name = $conditionals[$type]['items'][$item_id];
						break;
				}

				if ( $name ) {
					$output[$type.'_'.$item_id] = array(
						'type' 		=> $type,
						'id' 		=> $item_id,
						'name' 		=> $name,
						'post_slug' => $sidebar_slug,
						'post_id' 	=> $sidebar_id
					);
				}
			}
		}
	}

	// Add in custom conditional
	if ( ! empty( $input['custom'] ) ) {
		$output['custom'] = array(
			'type' 		=> 'custom',
			'id' 		=> apply_filters( 'themeblvd_sanitize_text', $input['custom'] ),
			'name' 		=> apply_filters( 'themeblvd_sanitize_text', $input['custom'] ),
			'post_slug' => $sidebar_slug,
			'post_id' 	=> $sidebar_id
		);
	}

	return $output;
}

/**
 * Sanitize Editor option type
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_editor( $input ) {
	if ( current_user_can('unfiltered_html') ) {
		$output = $input;
	} else {
		$output = wp_kses( $input, themeblvd_allowed_tags() );
	}
	return $output;
}

/**
 * Sanitize gradient option type
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_gradient( $input ){

	$output = array(
		'start'	=> '',
		'end'	=> ''
	);

	if ( isset( $input['start'] ) ) {
		$output['start'] = apply_filters( 'themeblvd_sanitize_hex', $input['start'] );
	}

	if ( isset( $input['end'] ) ) {
		$output['end'] = apply_filters( 'themeblvd_sanitize_hex', $input['end'] );
	}

	return $output;
}

/**
 * Sanitize button option type
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_button( $input ){

	$output = array(
		'bg' 				=> '',
		'bg_hover'			=> '',
		'border' 			=> '',
		'text'				=> '',
		'text_hover'		=> '',
		'include_bg'		=> 1,
		'include_border'	=> 1
	);

	$output['bg'] = apply_filters( 'themeblvd_sanitize_hex', $input['bg'] );
	$output['bg_hover'] = apply_filters( 'themeblvd_sanitize_hex', $input['bg_hover'] );
	$output['border'] = apply_filters( 'themeblvd_sanitize_hex', $input['border'] );
	$output['text'] = apply_filters( 'themeblvd_sanitize_hex', $input['text'] );
	$output['text_hover'] = apply_filters( 'themeblvd_sanitize_hex', $input['text_hover'] );

	if ( empty( $input['include_bg'] ) ) {
		$input['include_bg'] = '';
	}

	$output['include_bg'] = apply_filters( 'themeblvd_sanitize_checkbox', $input['include_bg'] );

	if ( empty( $input['include_border'] ) ) {
		$input['include_border'] = '';
	}

	$output['include_border'] = apply_filters( 'themeblvd_sanitize_checkbox', $input['include_border'] );

	return $output;
}

/**
 * Sanitize a color represented in hexidecimal notation.
 *
 * @since 2.2.0
 */
function themeblvd_sanitize_hex( $hex, $default = '' ) {
	if ( themeblvd_validate_hex( $hex ) ) {
		return $hex;
	}
	return $default;
}

/**
 * Get recognized font sizes.
 *
 * @since 2.2.0
 */
function themeblvd_recognized_font_sizes() {
	$sizes = range( 9, 70 );
	$sizes = apply_filters( 'themeblvd_recognized_font_sizes', $sizes );
	$sizes = array_map( 'absint', $sizes );
	return $sizes;
}

/**
 * Get recognized font faces.
 *
 * @since 2.2.0
 */
function themeblvd_recognized_font_faces() {
	$default = array(
		'arial'     	=> 'Arial',
		'baskerville'	=> 'Baskerville',
		'georgia'   	=> 'Georgia',
		'helvetica' 	=> 'Helvetica*',
		'lucida'  		=> 'Lucida Sans',
		'palatino'  	=> 'Palatino',
		'tahoma'    	=> 'Tahoma, Geneva',
		'times'     	=> 'Times New Roman',
		'trebuchet' 	=> 'Trebuchet',
		'verdana'   	=> 'Verdana, Geneva',
		'google'		=> 'Google Font',
		'typekit'		=> 'Typekit Font'
	);
	return apply_filters( 'themeblvd_recognized_font_faces', $default );
}

/**
 * Get recognized font styles.
 *
 * @since 2.2.0
 */
function themeblvd_recognized_font_styles() {
	$default = array(
		'normal' 			=> __('Normal', 'jumpstart'),
		'uppercase' 		=> __('Uppercase', 'jumpstart'),
		'italic' 			=> __('Italic', 'jumpstart'),
		'uppercase-italic'	=> __('Uppercase Italic', 'jumpstart')
	);
	return apply_filters( 'themeblvd_recognized_font_styles', $default );
}

/**
 * Get recognized font weights.
 *
 * @since 2.5.0
 */
function themeblvd_recognized_font_weights() {
	$default = array(
		'100' 			=> __('100', 'jumpstart'),
		'200' 			=> __('200', 'jumpstart'),
		'300' 			=> __('300', 'jumpstart'),
		'400' 			=> __('400 (normal)', 'jumpstart'),
		'500' 			=> __('500', 'jumpstart'),
		'600' 			=> __('600', 'jumpstart'),
		'700' 			=> __('700 (bold)', 'jumpstart'),
		'800' 			=> __('800', 'jumpstart'),
		'900' 			=> __('900', 'jumpstart')
	);
	return apply_filters( 'themeblvd_recognized_font_styles', $default );
}

/**
 * Verify formatted in hexidecimal notation
 *
 * @since 2.2.0
 */
function themeblvd_validate_hex( $hex ) {

	$hex = trim( $hex );

	/* Strip recognized prefixes. */
	if ( 0 === strpos( $hex, '#' ) ) {
		$hex = substr( $hex, 1 );
	} else if ( 0 === strpos( $hex, '%23' ) ) {
		$hex = substr( $hex, 3 );
	}

	/* Regex match. */
	if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		return false;
	} else {
		return true;
	}

}

/**
 * Locations
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_locations( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id] = array();
			$output[$item_id]['name'] = wp_kses( $item['name'], array() );
			$output[$item_id]['geo'] = apply_filters( 'themeblvd_sanitize_geo', $item['geo'] );
			$output[$item_id]['info'] = apply_filters( 'themeblvd_sanitize_textarea', $item['info'] );
			$output[$item_id]['image'] = apply_filters( 'themeblvd_sanitize_upload', $item['image'] );
			$output[$item_id]['width'] = apply_filters( 'themeblvd_sanitize_text', $item['width'] );
			$output[$item_id]['height'] = apply_filters( 'themeblvd_sanitize_text', $item['height'] );
		}
	}

	return $output;
}

/**
 * Geo (latitude and longitude)
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_geo( $input ) {

	$output = array(
		'lat' 	=> 0,
		'long'	=> 0
	);

	if ( ! empty( $input['lat'] ) ) {

		$lat = floatval($input['lat']);

		if ( $lat > -90 && $lat < 90  ) {
			$output['lat'] = $lat;
		}
	}

	if ( ! empty( $input['long'] ) ) {

		$long = floatval($input['long']);

		if ( $long > -180 && $long < 180  ) {
			$output['long'] = $long;
		}
	}

	return $output;
}

/**
 * Sectors
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_sectors( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id] = array();
			$output[$item_id]['label'] = wp_kses( $item['label'], array() );
			$output[$item_id]['value'] = wp_kses( $item['value'], array() );
			$output[$item_id]['color'] = apply_filters( 'themeblvd_sanitize_hex', $item['color'] );
		}
	}

	return $output;
}

/**
 * Data Sets
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_datasets( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id] = array();
			$output[$item_id]['label'] = wp_kses( $item['label'], array() );
			$output[$item_id]['values'] = wp_kses( $item['values'], array() );
			$output[$item_id]['color'] = apply_filters( 'themeblvd_sanitize_hex', $item['color'] );
		}
	}

	return $output;
}

/**
 * Progress Bars
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_bars( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id] = array();
			$output[$item_id]['label'] = wp_kses( $item['label'], array() );
			$output[$item_id]['label_value'] = wp_kses( $item['label_value'], array() );
			$output[$item_id]['value'] = wp_kses( $item['value'], array() );
			$output[$item_id]['total'] = wp_kses( $item['total'], array() );
			$output[$item_id]['color'] = apply_filters( 'themeblvd_sanitize_hex', $item['color'] );
		}
	}

	return $output;
}

/**
 * Buttons
 *
 * @since 2.5.0
 */
function themeblvd_sanitize_buttons( $input ) {

	$output = array();

	if ( $input && is_array($input) ) {
		foreach ( $input as $item_id => $item ) {
			$output[$item_id] = array();
			$output[$item_id]['color'] = wp_kses( $item['color'], array() );

			if ( isset( $item['custom'] ) ) {
				$output[$item_id]['custom'] = apply_filters( 'themeblvd_sanitize_button', $item['custom'] );
			}

			$output[$item_id]['text'] = apply_filters( 'themeblvd_sanitize_text', $item['text'] );
			$output[$item_id]['size'] = wp_kses( $item['size'], array() );
			$output[$item_id]['url'] = wp_kses( $item['url'], array() );
			$output[$item_id]['target'] = wp_kses( $item['target'], array() );
			$output[$item_id]['icon_before'] = wp_kses( $item['icon_before'], array() );
			$output[$item_id]['icon_after'] = wp_kses( $item['icon_after'], array() );
		}
	}

	return $output;
}
