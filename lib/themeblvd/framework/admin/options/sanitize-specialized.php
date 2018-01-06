<?php
/**
 * Specialized Option Sanitization
 *
 * Specialized options help to setup specific
 * website elements, and their UI generally
 * combine multiple basic options.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.7.0
 */

/**
 * Sanitize option type, `upload`.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  array $input {
 *     Original value.
 *
 *     @type string $id     Attachment ID.
 *     @type string $src    Attachment URL.
 *     @type string $title  Attachment title.
 *     @type string $crop   Attachment crop size.
 *     @type string $width  Attachment width.
 *     @type string $height Attachment height.
 * }
 * @return array $output {
 *     Sanitized value.
 *
 *     @type string $id      Attachment ID.
 *     @type string $src     Attachment URL.
 *     @type string $title   Attachment title.
 *     @type string $alt     Attachment alternative text.
 *     @type string $caption Attachment caption.
 *     @type string $crop    Attachment crop size.
 *     @type string $width   Attachment width.
 *     @type string $height  Attachment height.
 *     @type string $full    Full-size attachment URL, limited to 1200x1200.
 * }
 */
function themeblvd_sanitize_upload( $input ) {

	if ( is_array( $input ) ) {

		/*
		 * When working with retrieving image attachments
		 * in the WordPress admin, we can get weird results,
		 * as WordPress is limiting the size of images it
		 * thinks are getting inserted into the Editor.
		 *
		 * So here we'll filtering on a function to increase
		 * maximum size of images for the editor, but it's
		 * just for our sanitization purposes here.
		 *
		 * @see themeblvd_editor_max_image_size()
		 */
		add_filter( 'editor_max_image_size', 'themeblvd_editor_max_image_size' );

		$output = array(
			'id'      => 0,
			'src'     => '',
			'full'    => '',
			'title'   => '',
			'alt'     => '',
			'caption' => '',
			'crop'    => '',
			'width'   => 0,
			'height'  => 0,
		);

		if ( isset( $input['id'] ) ) {

			$output['id'] = intval( $input['id'] );

			$full = wp_get_attachment_image_src( $output['id'], 'tb_x_large' );

			if ( isset( $full[0] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output['full'] = apply_filters( 'themeblvd_sanitize_upload', $full[0] );
			}
		}

		if ( isset( $input['src'] ) ) {
			/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
			$output['src'] = apply_filters( 'themeblvd_sanitize_upload', $input['src'] );
		}

		if ( isset( $input['id'] ) ) {
			$output['id'] = intval( $input['id'] );
		}

		if ( isset( $input['title'] ) ) {
			$output['title'] = wp_kses( $input['title'], array() );
		}

		if ( isset( $input['alt'] ) ) {
			$output['alt'] = wp_kses( $input['alt'], array() );
		}

		if ( isset( $input['caption'] ) ) {
			$output['caption'] = wp_kses( $input['caption'], array() );
		}

		if ( isset( $input['crop'] ) ) {
			$output['crop'] = esc_attr( $input['crop'] );
		}

		/*
		 * And now that we're done, we remove our hooked
		 * filter callback and allow the WordPress editor
		 * and images to work as normal.
		 */
		remove_filter( 'editor_max_image_size', 'themeblvd_editor_max_image_size' );

	} else {

		$output = esc_url( $input );

	}

	return $output;

}

/**
 * Sanitize option type, `background`.
 *
 * This option lets the end-user to configure the
 * background image for an element.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  array $input {
 *     Original value.
 *
 *     @type string $color       Background color.
 *     @type string $image       Background image URL.
 *     @type string $image_id    Background image attachment ID.
 *     @type string $image_title Background image attachment title.
 *     @type string $image_alt   Background image alternative text.
 *     @type string $repeat      CSS background-repeat property.
 *     @type string $position    CSS background-position property.
 *     @type string $attachment  CSS background-attachment property, or `parallax`.
 *     @type string $size        CSS background-size property.
 * }
 * @return array $output {
 *     Sanitized value. Structured same as $input.
 * }
 */
function themeblvd_sanitize_background( $input ) {

	$output = array(
		'color'       => '',
		'image'       => '',
		'image_id'    => '',
		'image_title' => '',
		'image_alt'   => '',
		'repeat'      => 'repeat',
		'position'    => 'top center',
		'attachment'  => 'scroll',
		'size'        => 'auto',
	);

	if ( isset( $input['color'] ) ) {
		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['color'] = apply_filters( 'themeblvd_sanitize_color', $input['color'] );
	}

	if ( isset( $input['image'] ) ) {
		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['image'] = apply_filters( 'themeblvd_sanitize_upload', $input['image'] );
	}

	if ( isset( $input['image_id'] ) ) {
		$output['image_id'] = esc_attr( $input['image_id'] );
	}

	if ( isset( $input['image_title'] ) ) {
		$output['image_title'] = wp_kses( $input['image_title'], array() );
	}

	if ( isset( $input['image_alt'] ) ) {
		$output['image_alt'] = wp_kses( $input['image_alt'], array() );
	}

	if ( isset( $input['repeat'] ) ) {

		/**
		 * Filters the background-repeat CSS property for
		 * within a `background` option type.
		 *
		 * @since @@name-framework 2.2.0
		 *
		 * @param string CSS background-repeat property.
		 */
		$output['repeat'] = apply_filters( 'themeblvd_background_repeat', $input['repeat'] );

	}

	if ( isset( $input['position'] ) ) {

		/**
		 * Filters the background-position CSS property for
		 * within a `background` option type.
		 *
		 * @since @@name-framework 2.2.0
		 *
		 * @param string CSS background-position property.
		 */
		$output['position'] = apply_filters( 'themeblvd_background_position', $input['position'] );

	}

	if ( isset( $input['attachment'] ) ) {

		/**
		 * Filters the background-attachment CSS property for
		 * within a `background` option type.
		 *
		 * @since @@name-framework 2.2.0
		 *
		 * @param string CSS background-attachment property.
		 */
		$output['attachment'] = apply_filters( 'themeblvd_background_attachment', $input['attachment'] );

	}

	if ( isset( $input['size'] ) ) {

		/**
		 * Filters the background-size CSS property for
		 * within a `background` option type.
		 *
		 * @since @@name-framework 2.5.0
		 *
		 * @param string CSS background-size property.
		 */
		$output['size'] = apply_filters( 'themeblvd_background_size', $input['size'] );

	}

	return $output;

}

/**
 * Sanitize option type, `background_video`.
 *
 * This option lets the end-user to configure the
 * background video for an element.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  array $input {
 *     Original value.
 *
 *     @type string $mp4      Any mp4, webm, ogv video file URL, YouTube URL, or Vimeo URL.
 *     @type string $ratio    Aspect ratio of video.
 *     @type string $fallback Fallback image URL.
 * }
 * @return array $output {
 *     Sanitized value. Structured same as $input.
 * }
 */
function themeblvd_sanitize_background_video( $input ) {

	$output = array(
		'mp4'      => '',
		'ratio'    => '16:9',
		'fallback' => '',
	);

	if ( isset( $input['mp4'] ) ) {
		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['mp4'] = apply_filters( 'themeblvd_sanitize_upload', $input['mp4'] );
	}

	if ( isset( $input['fallback'] ) ) {
		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['fallback'] = apply_filters( 'themeblvd_sanitize_upload', $input['fallback'] );
	}

	if ( isset( $input['ratio'] ) && false !== strpos( $input['ratio'], ':' ) ) {
		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['ratio'] = apply_filters( 'themeblvd_sanitize_text', $input['ratio'] );
	}

	return $output;

}

/**
 * Get accepted CSS background-repeat properties.
 *
 * @since @@name-framework 2.2.0
 *
 * @see themeblvd_sanitize_background_repeat()
 *
 * @return array CSS background-repeat properties.
 */
function themeblvd_recognized_background_repeat() {

	/**
	 * Filters what can be used for the background-repeat
	 * property of a `background` option type.
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @return array CSS background-repeat properties.
	 */
	return apply_filters( 'themeblvd_recognized_background_repeat', array(
		'no-repeat' => __( 'Background Repeat: No Repeat', '@@text-domain' ),
		'repeat-x'  => __( 'Background Repeat: Repeat Horizontally', '@@text-domain' ),
		'repeat-y'  => __( 'Background Repeat: Repeat Vertically', '@@text-domain' ),
		'repeat'    => __( 'Background Repeat: Repeat All', '@@text-domain' ),
	) );

}

/**
 * Sanitize background-repeat property of option
 * type, `background`.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  string $input  Original value.
 * @return string $output Sanitized value.
 */
function themeblvd_sanitize_background_repeat( $value ) {

	$recognized = themeblvd_recognized_background_repeat();

	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}

	/**
	 * Filters the default value for the CSS
	 * background-repeat property for a background
	 * image, when none is specified.
	 *
	 * By default, this value will be the first
	 * item from the array returned from
	 * themeblvd_recognized_background_repeat().
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @param string Value for CSS background-repeat property.
	 */
	return apply_filters( 'themeblvd_default_background_repeat', current( $recognized ) );

}

/**
 * Get accepted CSS background-position properties.
 *
 * @since @@name-framework 2.2.0
 *
 * @see themeblvd_sanitize_background_position()
 *
 * @return array CSS background-position properties.
 */
function themeblvd_recognized_background_position() {

	/**
	 * Filters what can be used for the background-position
	 * property of a `background` option type.
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @return array CSS background-position properties.
	 */
	return apply_filters( 'themeblvd_recognized_background_position', array(
		'center top'    => __( 'Background Position: Top Center', '@@text-domain' ),
		'left top'      => __( 'Background Position: Top Left', '@@text-domain' ),
		'right top'     => __( 'Background Position: Top Right', '@@text-domain' ),
		'center center' => __( 'Background Position: Middle Center', '@@text-domain' ),
		'left center'   => __( 'Background Position: Middle Left', '@@text-domain' ),
		'right center'  => __( 'Background Position: Middle Right', '@@text-domain' ),
		'center bottom' => __( 'Background Position: Bottom Center', '@@text-domain' ),
		'left bottom'   => __( 'Background Position: Bottom Left', '@@text-domain' ),
		'right bottom'  => __( 'Background Position: Bottom Right', '@@text-domain' ),
	) );

}

/**
 * Sanitize background-position property of option
 * type, `background`.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  string $input  Original value.
 * @return string $output Sanitized value.
 */
function themeblvd_sanitize_background_position( $value ) {

	$recognized = themeblvd_recognized_background_position();

	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}

	/**
	 * Filters the default value for the CSS
	 * background-position property for a
	 * background image, when none is specified.
	 *
	 * By default, this value will be the first
	 * item from the array returned from
	 * themeblvd_recognized_background_position().
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @param string Value for CSS background-position property.
	 */
	return apply_filters( 'themeblvd_default_background_position', current( $recognized ) );

}

/**
 * Get accepted CSS background-attachment properties.
 *
 * @since @@name-framework 2.2.0
 *
 * @see themeblvd_sanitize_background_attachment()
 *
 * @return array CSS background-attachment properties.
 */
function themeblvd_recognized_background_attachment() {

	/**
	 * Filters what can be used for the background-attachment
	 * property of a `background` option type.
	 *
	 * When `parallax` is selected it will trigger that on
	 * the frontend, which obviously isn't actually done with
	 * the CSS background-attachment property.
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @return array CSS background-attachment properties.
	 */
	return apply_filters( 'themeblvd_recognized_background_attachment', array(
		'scroll'   => __( 'Background Scrolling: Normal', '@@text-domain' ),
		'parallax' => __( 'Background Scrolling: Parallax Effect', '@@text-domain' ),
		'fixed'    => __( 'Background Scrolling: Fixed in Place', '@@text-domain' ),
	) );

}

/**
 * Sanitize background-attachment property of option
 * type, `background`.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  string $input  Original value.
 * @return string $output Sanitized value.
 */
function themeblvd_sanitize_background_attachment( $value ) {

	$recognized = themeblvd_recognized_background_attachment();

	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}

	/**
	 * Filters the default value for the CSS
	 * background-attachment property for a
	 * background image, when none is specified.
	 *
	 * By default, this value will be the first
	 * item from the array returned from
	 * themeblvd_recognized_background_attachment().
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @param string Value for CSS background-attachment property.
	 */
	return apply_filters( 'themeblvd_default_background_attachment', current( $recognized ) );

}

/**
 * Get accepted CSS background-size properties.
 *
 * @since @@name-framework 2.5.0
 *
 * @see themeblvd_sanitize_background_size()
 *
 * @return array CSS background-size properties.
 */
function themeblvd_recognized_background_size() {

	/**
	 * Filters what can be used for the background-size
	 * property of a `background` option type.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @return array CSS background-size properties.
	 */
	return apply_filters( 'themeblvd_recognized_background_size',array(
		'auto'      => __( 'Background Size: Auto', '@@text-domain' ),
		'cover'     => __( 'Background Size: Cover', '@@text-domain' ),
		'contain'   => __( 'Background Size: Contain', '@@text-domain' ),
		'100% 100%' => __( 'Background Size: 100% x 100%', '@@text-domain' ),
		'100% auto' => __( 'Background Size: Fit Horizontally', '@@text-domain' ),
		'auto 100%' => __( 'Background Size: Fit Vertically', '@@text-domain' ),
	) );

}

/**
 * Sanitize background-size property of option
 * type, `background`.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  string $input  Original value.
 * @return string $output Sanitized value.
 */
function themeblvd_sanitize_background_size( $value ) {

	$recognized = themeblvd_recognized_background_size();

	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}

	/**
	 * Filters the default value for the CSS
	 * background-size property for a background
	 * image, when none is specified.
	 *
	 * By default, this value will be the first
	 * item from the array returned from
	 * themeblvd_recognized_background_size().
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string Value for CSS background-size property.
	 */
	return apply_filters( 'themeblvd_default_background_size', current( $recognized ) );

}

/**
 * Sanitize option type, `typography`.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  array $input {
 *     Original value.
 *
 *     @type string $size        Unitless CSS font-size property.
 *     @type string $face        Web-safe font name, `google` or `typeface`.
 *     @type string $style       CSS font-style property.
 *     @type string $weight      CSS font-weight property..
 *     @type string $color       CSS color property.
 *     @type string $google      Name of Google Font.
 *     @type string $typekit     Name of Typekit Font.
 *     @type string $typekit_kit Typekit embed code.
 * }
 * @return array $output {
 *     Sanitized value. Structured same as $input.
 * }
 */
function themeblvd_sanitize_typography( $input ) {

	$output = array(
		'size'        => '',
		'face'        => '',
		'style'       => '',
		'weight'      => '',
		'color'       => '',
		'google'      => '',
		'typekit'     => '',
		'typekit_kit' => '',
	);

	if ( isset( $input['size'] ) ) {

		/**
		 * Filters the font-size CSS property for within
		 * a `typography` option type.
		 *
		 * @since @@name-framework 2.2.0
		 *
		 * @see themeblvd_sanitize_font_size()
		 *
		 * @param string Unitless CSS font-size property.
		 */
		$output['size'] = apply_filters( 'themeblvd_font_size', $input['size'] );

	}

	if ( isset( $input['face'] ) ) {

		/**
		 * Filters the font-face CSS property for within
		 * a `typography` option type.
		 *
		 * @since @@name-framework 2.2.0
		 *
		 * @see themeblvd_sanitize_font_face()
		 *
		 * @param string CSS font-face property.
		 */
		$output['face'] = apply_filters( 'themeblvd_font_face', $input['face'] );

	}

	if ( isset( $input['style'] ) ) {

		/**
		 * Filters the font-style CSS property for within
		 * a `typography` option type.
		 *
		 * @since @@name-framework 2.2.0
		 *
		 * @see themeblvd_sanitize_font_style()
		 *
		 * @param string CSS font-style property.
		 */
		$output['style'] = apply_filters( 'themeblvd_font_style', $input['style'] );

	}

	if ( isset( $input['weight'] ) ) {

		/**
		 * Filters the font-weight CSS property for within
		 * a `typography` option type.
		 *
		 * @since @@name-framework 2.2.0
		 *
		 * @see themeblvd_sanitize_font_weight()
		 *
		 * @param string CSS font-weight property.
		 */
		$output['weight'] = apply_filters( 'themeblvd_font_weight', $input['weight'] );

	}

	if ( isset( $input['color'] ) ) {
		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['color'] = apply_filters( 'themeblvd_sanitize_color', $input['color'] );
	}

	if ( isset( $input['google'] ) ) {

		$output['google'] = str_replace( '"', '', $output['google'] );

		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['google'] = apply_filters( 'themeblvd_sanitize_text', $input['google'] );

	}

	if ( isset( $input['typekit'] ) ) {

		$output['typekit'] = str_replace( '"', '', $output['typekit'] );

		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['typekit'] = apply_filters( 'themeblvd_sanitize_text', $input['typekit'] );

	}

	if ( isset( $input['typekit_kit'] ) ) {
		$output['typekit_kit'] = themeblvd_kses( $input['typekit_kit'] );
	}

	if ( isset( $input['custom'] ) ) {
		$output['custom'] = themeblvd_kses( $input['custom'] );
	}

	return $output;

}

/**
 * Get accepted CSS font-size properties.
 *
 * @since @@name-framework 2.2.0
 *
 * @see themeblvd_sanitize_font_size()
 *
 * @return array Unitless CSS font-size properties.
 */
function themeblvd_recognized_font_sizes() {

	$sizes = range( 9, 100 );

	/**
	 * Filters the array of accepted font sizes for
	 * the `typography` option type.
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @param array $sizes Simple array of accepted integers.
	 */
	$sizes = apply_filters( 'themeblvd_recognized_font_sizes', $sizes );

	$sizes = array_map( 'absint', $sizes );

	return $sizes;

}

/**
 * Sanitize font-size property of option
 * type, `typography`.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  int|string $input  Original value.
 * @return int        $output Sanitized value.
 */
function themeblvd_sanitize_font_size( $input ) {

	$recognized = themeblvd_recognized_font_sizes();

	$output = preg_replace( '/px/', '', $input );

	if ( in_array( (int) $output, $recognized ) ) {
		return (int) $output;
	}

	/**
	 * Filters the default value for the CSS
	 * font-size property for a `typography`
	 * type option.
	 *
	 * The value should be unitless, meaning
	 * like `16`, instead of `16px`, `16em`, etc.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string Unitless value for CSS font-size property.
	 */
	return (int) apply_filters( 'themeblvd_default_font_size', 16 );

}

/**
 * Get accepted CSS font-style properties.
 *
 * @since @@name-framework 2.2.0
 *
 * @see themeblvd_sanitize_font_style()
 *
 * @return array CSS font-style properties.
 */
function themeblvd_recognized_font_styles() {

	/**
	 * Filters the array of accepted font styles for
	 * the `typography` option type.
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @param array Font styles.
	 */
	return apply_filters( 'themeblvd_recognized_font_styles', array(
		'normal'           => __( 'Normal', '@@text-domain' ),
		'uppercase'        => __( 'Uppercase', '@@text-domain' ),
		'italic'           => __( 'Italic', '@@text-domain' ),
		'uppercase-italic' => __( 'Uppercase Italic', '@@text-domain' ),
	) );

}

/**
 * Sanitize font-style property of option
 * type, `typography`.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  string $input  Original value.
 * @return string $output Sanitized value.
 */
function themeblvd_sanitize_font_style( $value ) {

	$recognized = themeblvd_recognized_font_styles();

	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}

	/**
	 * Filters the default value for the CSS
	 * font-style property for a `typography`
	 * type option.
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @param string Value for CSS font-style property.
	 */
	return apply_filters( 'themeblvd_default_font_style', 'normal' );

}

/**
 * Get accepted CSS font-weight properties.
 *
 * @since @@name-framework 2.5.0
 *
 * @see themeblvd_sanitize_font_weight()
 *
 * @return array CSS font-weight properties.
 */
function themeblvd_recognized_font_weights() {

	/**
	 * Filters the array of accepted font weights for
	 * the `typography` option type.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array Font weights.
	 */
	return apply_filters( 'themeblvd_recognized_font_styles', array(
		'100' => __( '100', '@@text-domain' ),
		'200' => __( '200', '@@text-domain' ),
		'300' => __( '300', '@@text-domain' ),
		'400' => __( '400 (normal)', '@@text-domain' ),
		'500' => __( '500', '@@text-domain' ),
		'600' => __( '600', '@@text-domain' ),
		'700' => __( '700 (bold)', '@@text-domain' ),
		'800' => __( '800', '@@text-domain' ),
		'900' => __( '900', '@@text-domain' ),
	) );

}

/**
 * Sanitize font-weight property of option
 * type, `typography`.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  string $input  Original value.
 * @return string $output Sanitized value.
 */
function themeblvd_sanitize_font_weight( $value ) {

	$recognized = themeblvd_recognized_font_weights();

	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}

	/**
	 * Filters the default value for the CSS
	 * font-weight property for a `typography`
	 * type option.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string Value for CSS font-weight property.
	 */
	return apply_filters( 'themeblvd_default_font_weight', '400' );

}

/**
 * Get accepted CSS font-family properties.
 *
 * @since @@name-framework 2.2.0
 *
 * @see themeblvd_sanitize_font_face()
 *
 * @return array CSS font-face properties.
 */
function themeblvd_recognized_font_faces() {

	/**
	 * Filters the array of accepted font names for
	 * the `typography` option type.
	 *
	 * When `google` or `typekit` are selected, the
	 * frontend will handle the CSS font-family properly
	 * differently.
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @param array Font names.
	 */
	return apply_filters( 'themeblvd_recognized_font_faces', array(
		'arial'       => 'Arial',
		'baskerville' => 'Baskerville',
		'georgia'     => 'Georgia',
		'helvetica'   => 'Helvetica*',
		'lucida'      => 'Lucida Sans',
		'palatino'    => 'Palatino',
		'tahoma'      => 'Tahoma, Geneva',
		'times'       => 'Times New Roman',
		'trebuchet'   => 'Trebuchet',
		'verdana'     => 'Verdana, Geneva',
		'google'      => 'Google Font',
		'typekit'     => 'Typekit Font',
		'custom'      => 'Custom Font',
	) );

}

/**
 * Sanitize font-weight property of option
 * type, `typography`.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  string $input  Original value.
 * @return string $output Sanitized value.
 */
function themeblvd_sanitize_font_face( $value ) {

	$recognized = themeblvd_recognized_font_faces();

	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}

	/**
	 * Filters the default value for the CSS
	 * font-family property for a `typography`
	 * type option.
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @param string Value for CSS font-weight property.
	 */
	return apply_filters( 'themeblvd_default_font_face', current( $recognized ) );

}

/**
 * Sanitize option type, `columns`.
 *
 * The value passed here will end up being a
 * string formatted like `1/3-1/3-1/3`; this
 * example would denote three colums, 1/3
 * width each.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  string $input  Original value.
 * @return string         Sanitized value.
 */
function themeblvd_sanitize_columns( $input ) {

	return wp_kses( $input, array() );

}

/**
 * Sanitize option type, `logo`.
 *
 * This option type is primarily used to setup the
 * site branding logo on the theme options page.
 *
 * @since @@name-framework 2.2.0
 *
 ** @param  array $input {
 *     Original value.
 *
 *     @type string $type           Type of logo to display on website.
 *     @type string $custom         Custom display text.
 *     @type string $custom_tagline Custom display tagline.
 *     @type string $image          Logo image URL.
 *     @type string $image_2x       Optional. 2x Logo image URL.
 *     @type string $image_width    Image display width.
 *     @type string $image_height   Image display height.
 * }
 * @return array $output {
 *     Sanitized value. Structured same as $input.
 * }
 */
function themeblvd_sanitize_logo( $input ) {

	$output = array();

	if ( is_array( $input ) && isset( $input['type'] ) ) {
		$output['type'] = $input['type'];
	}

	if ( isset( $input['custom'] ) ) {
		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['custom'] = apply_filters( 'themeblvd_sanitize_text', $input['custom'] );
	}

	if ( isset( $input['custom_tagline'] ) ) {
		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['custom_tagline'] = apply_filters( 'themeblvd_sanitize_text', $input['custom_tagline'] );
	}

	if ( isset( $input['image'] ) ) {

		$filetype = wp_check_filetype( $input['image'] );

		if ( $filetype['ext'] ) {

			/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
			$output['image'] = apply_filters( 'themeblvd_sanitize_upload', $input['image'] );

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

	if ( isset( $input['image_2x'] ) ) {

		$filetype = wp_check_filetype( $input['image_2x'] );

		if ( $filetype['ext'] ) {

			/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
			$output['image_2x'] = apply_filters( 'themeblvd_sanitize_upload', $input['image_2x'] );

		} else {

			$output['image_2x'] = null;

		}
	}

	return $output;

}

/**
 * Sanitize option type, `content`.
 *
 * When this type of option is passed through, the user
 * was given a choice to populate some sort of content
 * area with either a widget area, content from a post,
 * content from the current post, or raw input.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  array $input {
 *     Original value.
 *
 *     @type string $type       Content source - widget, page, raw or current.
 *     @type string $sidebar    If $type == widget, the sidebar ID to pull widgets from.
 *     @type string $page       If $type == page, the slug of the page to pull content from.
 *     @type string $raw        If $type == raw, the content.
 *     @type string $raw_format If $type == raw, whether to apply WP formatting to raw content - `0` or `1`.
 * }
 * @return array $output {
 *     Sanitized value. Structure varies depending on the
 *     $type. Only relevent values from original $input,
 *     that coorespond to the $type will be passed.
 * }
 */
function themeblvd_sanitize_content( $input ) {

	$output = array();

	if ( in_array( $input['type'], array( 'widget', 'current', 'page', 'raw' ) ) ) {

		$output['type'] = $input['type'];

	} else {

		$output['type'] = null;

	}

	switch ( $output['type'] ) {

		case 'widget':
			if ( isset( $input['sidebar'] ) ) {
				$output['sidebar'] = $input['sidebar'];
			} else {
				$output['sidebar'] = null;
			}

			break;

		case 'page':
			if ( isset( $input['page'] ) ) {
				$output['page'] = $input['page'];
			} else {
				$output['page'] = null;
			}

			break;

		case 'raw':
			/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
			if ( isset( $input['raw'] ) ) {
				$output['raw'] = apply_filters( 'themeblvd_sanitize_textarea', $input['raw'] );
			}

			$output['raw_format'] = '0';

			if ( ! empty( $input['raw_format'] ) ) {
				$output['raw_format'] = '1';
			}

			break;

	}

	return $output;

}

/**
 * Sanitize option type, `conditionals`.
 *
 * This option type was built to work with the
 * Theme Blvd Widget Areas plugin, for selecting
 * where a custom widget area should be applied.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  array  $input        Original value.
 * @param  string $sidebar_slug Current sidebar slug.
 * @param  string $sidebar_id   Current sidebar ID.
 * @return string $output       Sanitized value.
 */
function themeblvd_sanitize_conditionals( $input, $sidebar_slug = null, $sidebar_id = null ) {

	$conditionals = themeblvd_conditionals_config();

	$output = array();

	/*
	 * Prepare items that weren't straight-up arrays
	 * gifted on a platter for us.
	 */
	$types = array(
		'post',
		'page',
		'tag',
		'portfolio_item',
		'portfolio_tag',
		'product_tag',
	);

	foreach ( $types as $type ) {

		if ( ! empty( $input[ $type ] ) ) {

			$input[ $type ] = str_replace( ' ', '', $input[ $type ] );

			$input[ $type ] = explode( ',', $input[ $type ] );

		}
	}

	/*
	 * Now loop through each group, and then, through
	 * each item, attempting to create a name for each
	 * item. If an item has a $name, it gets added to
	 * the $output.
	 */
	foreach ( $input as $type => $group ) {

		if ( is_array( $group ) && ! empty( $group ) ) {

			foreach ( $group as $item_id ) {

				$name = '';

				switch ( $type ) {

					case 'page':
					case 'post':
					case 'portfolio_item':
					case 'forum':
						$post_id = themeblvd_post_id_by_name( $item_id, $type );

						$post = get_post( $post_id );

						if ( $post ) {
							$name = $post->post_title;
						}

						break;

					case 'category':
					case 'posts_in_category':
					case 'tag':
					case 'portfolio':
					case 'portfolio_items_in_portfolio':
					case 'portfolio_tag':
					case 'products_in_cat':
					case 'product_cat':
					case 'product_tag':
						if ( 'category' === $type || 'posts_in_category' === $type ) {

							$tax = 'category';

						} elseif ( 'tag' === $type ) {

							$tax = 'post_tag';

						} elseif ( 'portfolio' === $type || 'portfolio_items_in_portfolio' === $type ) {

							$tax = 'portfolio';

						} elseif ( 'portfolio_tag' === $type ) {

							$tax = 'portfolio_tag';

						} elseif ( 'product_cat' === $type || 'products_in_cat' === $type ) {

							$tax = 'product_cat';

						} elseif ( 'product_tag' === $type ) {

							$tax = 'product_tag';

						}

						if ( ! empty( $tax ) ) {

							$term = get_term_by( 'slug', $item_id, $tax );

							if ( $term ) {
								$name = $term->name;
							}
						}

						break;

					case 'portfolio_top':
					case 'product_top':
					case 'forum_top':
					case 'top':
						$name = $conditionals[ $type ]['items'][ $item_id ];

						break;

				}

				if ( $name ) {

					$output[ $type . '_' . $item_id ] = array(
						'type'      => $type,
						'id'        => $item_id,
						'name'      => $name,
						'post_slug' => $sidebar_slug,
						'post_id'   => $sidebar_id,
					);

				}
			}
		}
	}

	// Add in custom conditional.
	if ( ! empty( $input['custom'] ) ) {

		$output['custom'] = array(
			'type'      => 'custom',
			'id'        => apply_filters( 'themeblvd_sanitize_text', $input['custom'] ),
			'name'      => apply_filters( 'themeblvd_sanitize_text', $input['custom'] ),
			'post_slug' => $sidebar_slug,
			'post_id'   => $sidebar_id,
		);

	}

	return $output;

}

/**
 * Sanitize option type, `editor`.
 *
 * These are options that use wp_editor() to give
 * a content editor to the user.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  string $input  Original value.
 * @return string $output Sanitized value.
 */
function themeblvd_sanitize_editor( $input ) {

	if ( current_user_can( 'unfiltered_html' ) ) {
		$output = $input;
	} else {
		$output = themeblvd_kses( $input );
	}

	return $output;

}

/**
 * Sanitize option type, `gradient`.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  string $input  Original value.
 * @return string $output Sanitized value.
 */
function themeblvd_sanitize_gradient( $input ) {

	$output = array(
		'start' => '',
		'end'   => '',
	);

	if ( isset( $input['start'] ) ) {
		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['start'] = apply_filters( 'themeblvd_sanitize_color', $input['start'] );
	}

	if ( isset( $input['end'] ) ) {
		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['end'] = apply_filters( 'themeblvd_sanitize_color', $input['end'] );
	}

	return $output;

}

/**
 * Sanitize option type, `button`.
 *
 * This option is used to create a specifically
 * a custom-colored button block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array $input {
 *     Original value.
 *
 *     @type string $bg             Background color.
 *     @type string $bg_hover       Background color of hover state.
 *     @type string $border         Border color.
 *     @type string $text           Text color.
 *     @type string $text_hover     Text color of hover state.
 *     @type string $include_bg     Whether to include button background.
 *     @type string $include_border Whether to include button border.
 * }
 * @return array $output {
 *     Sanitized value. Structured same as $input.
 * }
 */
function themeblvd_sanitize_button( $input ) {

	$output = array(
		'bg'             => '',
		'bg_hover'       => '',
		'border'         => '',
		'text'           => '',
		'text_hover'     => '',
		'include_bg'     => '0',
		'include_border' => '0',
	);

	if ( isset( $input['bg'] ) ) {
		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['bg'] = apply_filters( 'themeblvd_sanitize_color', $input['bg'] );
	}

	if ( isset( $input['bg_hover'] ) ) {
		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['bg_hover'] = apply_filters( 'themeblvd_sanitize_color', $input['bg_hover'] );
	}

	if ( isset( $input['border'] ) ) {
		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['border'] = apply_filters( 'themeblvd_sanitize_color', $input['border'] );
	}

	if ( isset( $input['text'] ) ) {
		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['text'] = apply_filters( 'themeblvd_sanitize_color', $input['text'] );
	}

	if ( isset( $input['text_hover'] ) ) {
		/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
		$output['text_hover'] = apply_filters( 'themeblvd_sanitize_color', $input['text_hover'] );
	}

	if ( ! empty( $input['include_bg'] ) ) {
		$output['include_bg'] = '1';
	}

	if ( ! empty( $input['include_border'] ) ) {
		$output['include_border'] = '1';
	}

	return $output;

}

/**
 * Sanitize option type, `geo`.
 *
 * This option type is used to set the coordinates,
 * latitude and longitude for a Google Map marker.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array $input {
 *     Original value.
 *
 *     @type string $lat  Latitude of coordinates.
 *     @type string $long Longitude of coordinates.
 * }
 * @return array $output {
 *     Sanitized value.
 *
 *     @type int    $lat  Latitude of coordinates.
 *     @type int    $long Longitude of coordinates.
 * }
 */
function themeblvd_sanitize_geo( $input ) {

	$output = array(
		'lat'  => 0,
		'long' => 0,
	);

	if ( ! empty( $input['lat'] ) ) {

		$lat = floatval( $input['lat'] );

		if ( $lat > -90 && $lat < 90 ) {
			$output['lat'] = $lat;
		}
	}

	if ( ! empty( $input['long'] ) ) {

		$long = floatval( $input['long'] );

		if ( $long > -180 && $long < 180 ) {
			$output['long'] = $long;
		}
	}

	return $output;

}
