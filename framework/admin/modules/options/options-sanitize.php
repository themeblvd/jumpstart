<?php

/* Text (Extended by ThemeBlvd) */

function of_sanitize_text($input) {
	$allowedtags = themeblvd_allowed_tags( false );
	$output = wp_kses( $input, $allowedtags);
	$output = str_replace( "\r\n", "\n", $output );
	return $output;
}

add_filter( 'of_sanitize_text', 'of_sanitize_text' );

/* Textarea */

function of_sanitize_textarea($input) {
	$allowedtags = themeblvd_allowed_tags( true );
	$output = wp_kses( $input, $allowedtags);
	$output = str_replace( "\r\n", "\n", $output );
	return $output;
}

add_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );

/* Info */

function of_sanitize_allowedtags($input) {
	$allowedtags = themeblvd_allowed_tags( false );
	$output = wpautop(wp_kses( $input, $allowedtags));
	return $output;
}

add_filter( 'of_sanitize_info', 'of_sanitize_allowedtags' );

/* Select */

add_filter( 'of_sanitize_select', 'of_sanitize_enum', 10, 2);

/* Radio */

add_filter( 'of_sanitize_radio', 'of_sanitize_enum', 10, 2);

/* Images */

add_filter( 'of_sanitize_images', 'of_sanitize_enum', 10, 2);

/* Checkbox */

function of_sanitize_checkbox( $input ) {
	if ( $input ) {
		$output = "1";
	} else {
		$output = "0";
	}
	return $output;
}
add_filter( 'of_sanitize_checkbox', 'of_sanitize_checkbox' );

/* Multicheck */

function of_sanitize_multicheck( $input, $option ) {
	$output = '';
	if ( is_array( $input ) ) {
		foreach( $option['options'] as $key => $value ) {
			$output[$key] = "0";
		}
		foreach( $input as $key => $value ) {
			if ( array_key_exists( $key, $option['options'] ) && $value ) {
				$output[$key] = "1"; 
			}
		}
	}
	return $output;
}
add_filter( 'of_sanitize_multicheck', 'of_sanitize_multicheck', 10, 2 );

/* Color Picker */

add_filter( 'of_sanitize_color', 'of_sanitize_hex' );

/* Uploader */

function of_sanitize_upload( $input ) {
	$output = '';
	$filetype = wp_check_filetype($input);
	if ( $filetype["ext"] ) {
		$output = $input;
	}
	return $output;
}
add_filter( 'of_sanitize_upload', 'of_sanitize_upload' );

/* Check that the key value sent is valid */

function of_sanitize_enum( $input, $option ) {
	$output = '';
	if ( array_key_exists( $input, $option['options'] ) ) {
		$output = $input;
	}
	return $output;
}

/* Background */

function of_sanitize_background( $input ) {
	$output = wp_parse_args( $input, array(
		'color' => '',
		'image'  => '',
		'repeat'  => 'repeat',
		'position' => 'top center',
		'attachment' => 'scroll'
	) );

	$output['color'] = apply_filters( 'of_sanitize_hex', $input['color'] );
	$output['image'] = apply_filters( 'of_sanitize_upload', $input['image'] );
	$output['repeat'] = apply_filters( 'of_background_repeat', $input['repeat'] );
	$output['position'] = apply_filters( 'of_background_position', $input['position'] );
	$output['attachment'] = apply_filters( 'of_background_attachment', $input['attachment'] );

	return $output;
}
add_filter( 'of_sanitize_background', 'of_sanitize_background' );

function of_sanitize_background_repeat( $value ) {
	$recognized = of_recognized_background_repeat();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'of_default_background_repeat', current( $recognized ) );
}
add_filter( 'of_background_repeat', 'of_sanitize_background_repeat' );

function of_sanitize_background_position( $value ) {
	$recognized = of_recognized_background_position();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'of_default_background_position', current( $recognized ) );
}
add_filter( 'of_background_position', 'of_sanitize_background_position' );

function of_sanitize_background_attachment( $value ) {
	$recognized = of_recognized_background_attachment();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'of_default_background_attachment', current( $recognized ) );
}
add_filter( 'of_background_attachment', 'of_sanitize_background_attachment' );


/* Typography */

function of_sanitize_typography( $input ) {
	$output = wp_parse_args( $input, array(
		'size' 		=> '',
		'face'  	=> '',
		'style' 	=> '',
		'color' 	=> '',
		'google' 	=> ''
	) );

	$output['size']  = apply_filters( 'of_font_size', $output['size'] );
	$output['face']  = apply_filters( 'of_font_face', $output['face'] );
	$output['style'] = apply_filters( 'of_font_style', $output['style'] );
	$output['color'] = apply_filters( 'of_color', $output['color'] );
	$output['google'] = apply_filters( 'of_sanitize_text', $output['google'] );

	return $output;
}
add_filter( 'of_sanitize_typography', 'of_sanitize_typography' );


function of_sanitize_font_size( $value ) {
	$recognized = of_recognized_font_sizes();
	$value = preg_replace('/px/','', $value);
	if ( in_array( (int) $value, $recognized ) ) {
		return (int) $value;
	}
	return (int) apply_filters( 'of_default_font_size', $recognized );
}
add_filter( 'of_font_face', 'of_sanitize_font_face' );


function of_sanitize_font_style( $value ) {
	$recognized = of_recognized_font_styles();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'of_default_font_style', current( $recognized ) );
}
add_filter( 'of_font_style', 'of_sanitize_font_style' );


function of_sanitize_font_face( $value ) {
	$recognized = of_recognized_font_faces();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'of_default_font_face', current( $recognized ) );
}
add_filter( 'of_font_face', 'of_sanitize_font_face' );



/*-----------------------------------------------------------------------------------*/
/* Sanitization for custom options added by ThemeBlvd (start)
/*-----------------------------------------------------------------------------------*/

/* Columns */

function of_sanitize_columns( $input ) {

	$width_options = themeblvd_column_widths();
	$output = array();

	// Verify number of columns is an integer
	if( is_numeric( $input['num'] ) )
		$output['num'] = $input['num'];
	else
		$output['num'] = null;
	
	// Verify widths
	foreach( $input['width'] as $key => $width ) {
		$valid = false;
		foreach( $width_options[$key.'-col'] as $width_option )
			if( $width == $width_option['value'] )
				$valid = true;
		if( $valid )
			$output['width'][$key] = $width;
		else
			$output['width'][$key] = null;
	}
	
	return $output;
}

add_filter( 'of_sanitize_columns', 'of_sanitize_columns' );

/* Tabs */

function of_sanitize_tabs( $input ) {
	
	$output = array();
	
	// Verify number of tabs is an integer
	if( is_numeric( $input['num'] ) )
		$output['num'] = $input['num'];
	else
		$output['num'] = null;
	
	// Verify style
	if( $input['style'] == 'open' || $input['style'] == 'framed' )
		$output['style'] = $input['style'];
	
	// Verify name fields and only save the right amount of names
	if( $output['num'] ) {
		$total_num = intval( $output['num'] );
		$i = 1;
		while( $i <= $total_num ) {
			$output['names']['tab_'.$i] = sanitize_text_field( $input['names']['tab_'.$i] );
			$i++;
		}
	}
	return $output;	
}

add_filter( 'of_sanitize_tabs', 'of_sanitize_tabs' );

/* Dynamic Content */

function of_sanitize_content( $input ) {
	
	$allowedtags = themeblvd_allowed_tags( true );
	$output = array();
	
	// Verify type
	$types = array( 'widget', 'page', 'raw' );
	if( in_array( $input['type'], $types ) )
		$output['type'] = $input['type'];
	else
		$output['type'] = null;
	
	// Add type's corresponding option
	switch( $output['type'] ) {
		case 'widget' :
			if( isset( $input['sidebar'] ) )
				$output['sidebar'] = $input['sidebar'];
			else
				$output['sidebar'] = null;
			break;
		case 'page' :
			$output['page'] = $input['page'];
			break;
		case 'raw' :
			$output['raw'] = wp_kses( $input['raw'], $allowedtags );
			$output['raw'] = str_replace( "\r\n", "\n", $output['raw'] );
			isset( $input['raw_format'] ) ? $output['raw_format'] = '1' : $output['raw_format'] = '0';
			break;
	}
	
	return $output;	
}

add_filter( 'of_sanitize_content', 'of_sanitize_content' );

/* Logo */

function of_sanitize_logo( $input ) {
	
	$output = array();
	
	// Type 
	if( is_array( $input ) && isset( $input['type'] ) )
		$output['type'] = $input['type'];
	
	if( isset( $output['type'] ) ) {
		switch( $output['type'] ) {
			case 'custom' :
				if( isset( $input['custom'] ) )
					$output['custom'] = sanitize_text_field( $input['custom'] );
				if( isset( $input['custom_tagline'] ) )
					$output['custom_tagline'] = sanitize_text_field( $input['custom_tagline'] );
				break;
			case 'image' :
				$filetype = wp_check_filetype( $input['image'] );
				if ( $filetype["ext"] )
					$output['image'] = $input['image'];
				else
					$output['image'] = null;
				break;
		}
	}

	return $output;
}

add_filter( 'of_sanitize_logo', 'of_sanitize_logo' );

/* Social Media Buttons */

function of_sanitize_social_media( $input ) {
	$output = array();
	$value = null;
	if( is_array( $input ) ) {
		if( isset( $input['includes'] ) && ! empty( $input['includes'] ) ) {
			foreach( $input['includes'] as $include ) {
				if( isset( $input[$include] ) )	
					$output[$include] = $input[$include];
			}
		}
	}
	return $output;
}

add_filter( 'of_sanitize_social_media', 'of_sanitize_social_media' );

/* Conditionals */

function of_sanitize_conditionals( $input, $sidebar_slug = null, $sidebar_id = null ) {
	$conditionals = themeblvd_conditionals_config();
	$output = array();
	foreach( $input as $type => $group ) {
		foreach( $group as $item_id ) {
			switch( $type ) {
				case 'page' :
					$page_id = themeblvd_post_id_by_name( $item_id, 'page' );
					$page = get_page( $page_id );
					$name = $page->post_title;
					break;
				case 'post' :
					$post_id = themeblvd_post_id_by_name( $item_id, 'post' );
					$post = get_post( $post_id );
					$name = $post->post_title;
					break;
				case 'posts_in_category' :
					$category = get_category_by_slug( $item_id );
					$name = $category->slug;
					break;
				case 'category' :
					$category = get_category_by_slug( $item_id );
					$name = $category->slug;
					break;
				case 'tag' :
					$tag = get_term_by( 'slug', $item_id, 'post_tag' );
					$name = $tag->name;
					break;
				case 'top' :
					$name = $conditionals['top']['items'][$item_id];
					break;
			}
			$output[$type.'_'.$item_id] = array(
				'type' 		=> $type,
				'id' 		=> $item_id,
				'name' 		=> $name,
				'post_slug' => $sidebar_slug,
				'post_id' 	=> $sidebar_id
			);
		}
	}
	return $output;
}

add_filter( 'of_sanitize_conditionals', 'of_sanitize_conditionals', 10, 3 );

/*-----------------------------------------------------------------------------------*/
/* Sanitization for custom options added by ThemeBlvd (end)
/*-----------------------------------------------------------------------------------*/



/**
 * Get recognized background repeat settings
 *
 * @return   array
 *
 */
function of_recognized_background_repeat() {
	$default = array(
		'no-repeat' => 'No Repeat',
		'repeat-x'  => 'Repeat Horizontally',
		'repeat-y'  => 'Repeat Vertically',
		'repeat'    => 'Repeat All',
		);
	return apply_filters( 'of_recognized_background_repeat', $default );
}

/**
 * Get recognized background positions
 *
 * @return   array
 *
 */
function of_recognized_background_position() {
	$default = array(
		'top left'      => 'Top Left',
		'top center'    => 'Top Center',
		'top right'     => 'Top Right',
		'center left'   => 'Middle Left',
		'center center' => 'Middle Center',
		'center right'  => 'Middle Right',
		'bottom left'   => 'Bottom Left',
		'bottom center' => 'Bottom Center',
		'bottom right'  => 'Bottom Right'
		);
	return apply_filters( 'of_recognized_background_position', $default );
}

/**
 * Get recognized background attachment
 *
 * @return   array
 *
 */
function of_recognized_background_attachment() {
	$default = array(
		'scroll' => 'Scroll Normally',
		'fixed'  => 'Fixed in Place'
		);
	return apply_filters( 'of_recognized_background_attachment', $default );
}

/**
 * Sanitize a color represented in hexidecimal notation.
 *
 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @param    string    The value that this function should return if it cannot be recognized as a color.
 * @return   string
 *
 */
 
function of_sanitize_hex( $hex, $default = '' ) {
	if ( of_validate_hex( $hex ) ) {
		return $hex;
	}
	return $default;
}

/**
 * Get recognized font sizes.
 *
 * Returns an indexed array of all recognized font sizes.
 * Values are integers and represent a range of sizes from
 * smallest to largest.
 *
 * @return   array
 */
 
function of_recognized_font_sizes() {
	$sizes = range( 9, 71 );
	$sizes = apply_filters( 'of_recognized_font_sizes', $sizes );
	$sizes = array_map( 'absint', $sizes );
	return $sizes;
}

/**
 * Get recognized font faces.
 *
 * Returns an array of all recognized font faces.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return   array
 *
 */
 
function of_recognized_font_faces() {
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
		'google'		=> 'Google Font'
	);
	return apply_filters( 'of_recognized_font_faces', $default );
}

/**
 * Get recognized font styles.
 *
 * Returns an array of all recognized font styles.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return   array
 *
 */

function of_recognized_font_styles() {
	$default = array(
		'normal'      => 'Normal',
		'italic'      => 'Italic',
		'bold'        => 'Bold',
		'bold italic' => 'Bold Italic'
	);
	return apply_filters( 'of_recognized_font_styles', $default );
}

/**
 * Is a given string a color formatted in hexidecimal notation?
 *
 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @return   bool
 *
 */
 
function of_validate_hex( $hex ) {
	$hex = trim( $hex );
	/* Strip recognized prefixes. */
	if ( 0 === strpos( $hex, '#' ) ) {
		$hex = substr( $hex, 1 );
	}
	else if ( 0 === strpos( $hex, '%23' ) ) {
		$hex = substr( $hex, 3 );
	}
	/* Regex match. */
	if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		return false;
	}
	else {
		return true;
	}
}