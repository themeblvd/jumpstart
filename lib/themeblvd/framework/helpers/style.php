<?php
/**
 * Helpers: Styling
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */

/**
 * Include fonts.
 *
 * Include external fonts from Google and Typekit.
 * Accepts unlimited amount of font arguments.
 *
 * @since @@name-framework 2.6.0
 */
function themeblvd_include_fonts() {

	$input = func_get_args();

	$used = array();

	if ( ! empty( $input ) ) {

		$g_fonts = array();

		$t_fonts = array();

		foreach ( $input as $font ) {

			if ( 'google' === $font['face'] && ! empty( $font['google'] ) ) {

				$font = explode( ':', $font['google'] );

				$name = trim( str_replace( ' ', '+', $font[0] ) );

				if ( ! isset( $g_fonts[ $name ] ) ) {

					$g_fonts[ $name ] = array(
						'style'  => array(),
						'subset' => array(),
					);

				}

				if ( isset( $font[1] ) ) {

					$parts = explode( '&', $font[1] );

					foreach ( $parts as $part ) {

						if ( 0 === strpos( $part, 'subset' ) ) {

							$part = str_replace( 'subset=', '', $part );

							$part = explode( ',', $part );

							$part = array_merge( $g_fonts[ $name ]['subset'], $part );

							$g_fonts[ $name ]['subset'] = array_unique( $part );

						} else {

							$part = explode( ',', $part );

							$part = array_merge( $g_fonts[ $name ]['style'], $part );

							$g_fonts[ $name ]['style'] = array_unique( $part );

						}
					}
				}
			} elseif ( 'typekit' === $font['face'] && ! empty( $font['typekit_kit'] ) ) {

				if ( ! in_array( $font['typekit_kit'], $t_fonts ) ) {

					$t_fonts[] = $font['typekit_kit'];

				}
			}
		}

		// Include fonts from Google.
		if ( $g_fonts ) {

			$url = array();

			foreach ( $g_fonts as $font => $atts ) {

				if ( ! empty( $atts['style'] ) ) {

					$font .= sprintf( ':%s', implode( ',', $atts['style'] ) );

				}

				if ( ! empty( $atts['subset'] ) ) {

					$font .= sprintf( '&subset=%s', implode( ',', $atts['subset'] ) );

				}

				$url[] = $font;

			}

			$url = sprintf(
				'https://fonts.googleapis.com/css?family=%s',
				implode( '|', $url )
			);

			/**
			 * Filters the URL to include fonts from
			 * Google Font Directory.
			 *
			 * @since @@name-framework 2.7.0
			 *
			 * @param string $url      Full URL, like `https://fonts.googleapis.com/css?family=Foo`.
			 * @param array  $g_fonts  Font data.
			 * @param string $protocol Website protocol, `http://` or `https://`.
			 */
			$url = apply_filters( 'themeblvd_google_font_url', $url, $g_fonts );

			printf(
				'<link href="%s" rel="stylesheet" type="text/css">' . "\n",
				esc_url( $url )
			);

		}

		// Include fonts from Typekit.
		foreach ( $t_fonts as $font ) {

			echo themeblvd_kses( $font ) . "\n";

		}
	}
}

/**
 * Get a CSS font-face value.
 *
 * Gets the prepared value for a CSS font-face
 * property, given the value from a `typography`
 * type framework option.
 *
 * @since @@name-framework 2.0.0
 *
 * @param  array  $option Value from a `typography` type framework option.
 * @return string $stack  Font name, to be used with CSS font-family property.
 */
function themeblvd_get_font_face( $option ) {

	$stack = '';

	$stacks = themeblvd_font_stacks();

	if ( in_array( $option['face'], array( 'google', 'typekit', 'custom' ) ) ) {

		$name = '';

		if ( 'typekit' === $option['face'] ) {

			if ( isset( $option['typekit'] ) ) {

				$name = strtolower( str_replace( ' ', '-', $option['typekit'] ) );

			}
		} elseif ( 'google' === $option['face'] ) {

			if ( isset( $option['google'] ) ) {

				$name = $option['google'];

			}
		} elseif ( 'custom' === $option['face'] ) {

			if ( isset( $option['custom'] ) ) {

				$name = $option['custom'];

			}
		}

		/*
		 * Grab font face, making sure they didn't do the
		 * super, sneaky trick of including font weight
		 * or type.
		 */
		$name = explode( ':', $name );

		// And also check for accidental space at end.
		$name = esc_attr( trim( $name[0] ) );

		/*
		 * Add the deafult font stack to the end of the
		 * google font.
		 */
		$stack = '"' . $name . '", ' . $stacks['default'];

	} else {

		$stack = $stacks[ $option['face'] ];

	}

	/**
	 * Filters the prepared value for a CSS font-face
	 * property, given the value from a `typography`
	 * type framework option.
	 *
	 * @since @@name-framework 2.0.0
	 *
	 * @param string $stack  Font name, to be used with CSS font-family property.
	 * @param array  $option Value from a `typography` type framework option.
	 * @param array  $stacks Framework font stack values from themeblvd_font_stacks().
	 */
	return apply_filters( 'themeblvd_font_face_output', $stack, $option, $stacks );

}

/**
 * Get a CSS font-size value.
 *
 * Gets the prepared value for a CSS font-size
 * property, given the value from a `typography`
 * type framework option.
 *
 * @since @@name-framework 2.3.0
 *
 * @param  array  $option Value from a `typography` type framework option.
 * @return string $size   Font size, to be used with CSS font-size property.
 */
function themeblvd_get_font_size( $option ) {

	$size = '13px';

	if ( isset( $option['size'] ) ) {

		$size = $option['size'];

	}

	/**
	 * Filters the prepared value for a CSS font-size
	 * property, given the value from a `typography`
	 * type framework option.
	 *
	 * @since @@name-framework 2.3.0
	 *
	 * @param string $size   Font size, to be used with CSS font-size property.
	 * @param array  $option Value from a `typography` type framework option.
	 */
	return apply_filters( 'themeblvd_font_size_output', $size, $option );

}

/**
 * Get a CSS font-style value.
 *
 * Gets the prepared value for a CSS font-style
 * property, given the value from a `typography`
 * type framework option.
 *
 * @since @@name-framework 2.3.0
 *
 * @param  array  $option Value from a `typography` type framework option.
 * @return string $style  Font style, to be used with CSS font-style property.
 */
function themeblvd_get_font_style( $option ) {

	$style = 'normal';

	if ( ! empty( $option['style'] ) ) {

		if ( in_array( $option['style'], array( 'italic', 'uppercase-italic', 'bold-italic' ) ) ) {

			$style = 'italic';

		}
	}

	/**
	 * Filters the prepared value for a CSS font-style
	 * property, given the value from a `typography`
	 * type framework option.
	 *
	 * @since @@name-framework 2.3.0
	 *
	 * @param string $style  Font style, to be used with CSS font-style property.
	 * @param array  $option Value from a `typography` type framework option.
	 */
	return apply_filters( 'themeblvd_font_style_output', $style, $option );

}

/**
 * Get a CSS font-weight value.
 *
 * Gets the prepared value for a CSS font-weight
 * property, given the value from a `typography`
 * type framework option.
 *
 * @since @@name-framework 2.3.0
 *
 * @param  array  $option Value from a `typography` type framework option.
 * @return string $weight Font weight, to be used with CSS font-weight property.
 */
function themeblvd_get_font_weight( $option ) {

	$weight = '400';

	/*
	 * Handle backwards comaptibility.
	 *
	 * If option was saved after framework 2.5, $option['style']
	 * won't have one of these values.
	 */
	if ( empty( $option['weight'] ) && ! empty( $option['style'] ) ) {

		if ( 'bold' === $option['style'] || 'bold-italic' === $option['style'] ) {

			$weight = 'bold';

		} elseif ( 'thin' === $option['style'] ) {

			$weight = '300';

		}
	}

	if ( ! empty( $option['weight'] ) ) {

		$weight = $option['weight'];

	}

	/**
	 * Filters the prepared value for a CSS font-weight
	 * property, given the value from a `typography`
	 * type framework option.
	 *
	 * @since @@name-framework 2.3.0
	 *
	 * @param string $weight Font weight, to be used with CSS font-style property.
	 * @param array  $option Value from a `typography` type framework option.
	 */
	return apply_filters( 'themeblvd_font_weight_output', esc_attr( $weight ), $option );

}

/**
 * Get a CSS text-transform value.
 *
 * Gets the prepared value for a CSS text-transform
 * property, given the value from a `typography`
 * type framework option.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $option   Value from a `typography` type framework option.
 * @return string $tranform Text transform value, to be used with CSS text-transform property.
 */
function themeblvd_get_text_transform( $option ) {

	$tranform = 'none';

	if ( ! empty( $option['style'] ) ) {

		if ( in_array( $option['style'], array( 'uppercase', 'uppercase-italic' ) ) ) {

			$tranform = 'uppercase';

		}
	}

	/**
	 * Filters the prepared value for a CSS text-transform
	 * property, given the value from a `typography`
	 * type framework option.
	 *
	 * @since @@name-framework 2.5s.0
	 *
	 * @param string $weight Text transform value, to be used with CSS text-transform property.
	 * @param array  $option Value from a `typography` type framework option.
	 */
	return apply_filters( 'themeblvd_text_transform_output', $tranform, $option );

}

/**
 * Determine color of text depending on background
 * color.
 *
 * Huge thank you to Oscar for providing this:
 * @link http://stackoverflow.com/questions/3015116/hex-code-brightness-php
 *
 * @since @@name-framework 2.0.0
 *
 * @param  string $bg_color   Background color, like `#fff`.
 * @return string $text_color Text color, like `#000`.
 */
function themeblvd_text_color( $bg_color ) {

	// Pop off '#' from start.
	$bg_color = explode( '#', $bg_color );

	$bg_color = $bg_color[1];

	// Break up the color in its RGB components
	$r = hexdec( substr( $bg_color, 0, 2 ) );

	$g = hexdec( substr( $bg_color, 2, 2 ) );

	$b = hexdec( substr( $bg_color, 4, 2 ) );

	// Use simple weighted average.
	if ( $r + $g + $b > 382 ) {

		/**
		 * Filters the deault dark text color when a
		 * background is determined to be light.
		 *
		 * @since @@name-framework 2.0.0
		 *
		 * @param string Color hex value, like `#000`.
		 */
		$text_color = apply_filters( 'themeblvd_dark_font', '#333333' );

	} else {

		/**
		 * Filters the deault light text color when a
		 * background is determined to be dark.
		 *
		 * @since @@name-framework 2.0.0
		 *
		 * @param string Color hex value, like `#fff`.
		 */
		$text_color = apply_filters( 'themeblvd_light_font', '#ffffff' );

	}

	/**
	 * Filters the text color result, determined based
	 * on the background color brightness.
	 *
	 * @since @@name-framework 2.0.0
	 *
	 * @param string $text_color Text color, like `#000`.
	 * @param string $bg_color   Background color, like `#fff`.
	 */
	return apply_filters( 'themeblvd_text_color', $text_color, $bg_color );

}

/**
 * Darken or Lighten a hex color
 *
 * Huge thank you to Jonas John for providing this:
 * @link http://www.jonasjohn.de/snippets/php/darker-color.htm
 *
 * @since @@name-framework 2.0.5
 *
 * @param  string $color      Color hex to adjust, like `#000000`.
 * @param  int    $difference Amount to adjust color, like `20`.
 * @param  string $direction  How to adjust color, `lighten` or `darken`.
 * @return string $new_color  Adjusted color hex, like `#000000`.
 */
function themeblvd_adjust_color( $color, $difference = 20, $direction = 'darken' ) {

	// Pop off '#' from start.
	$color = explode( '#', $color );

	$color = $color[1];

	/*
	 * Send back in black if it's not a properly
	 * formatted 6-digit hex.
	 *
	 * Note: This function will not properly handle
	 * a 3-digit hex.
	 */
	if ( 6 !== strlen( $color ) ) {

		return '#000000';

	}

	$new_color = '';

	for ( $x = 0; $x < 3; $x++ ) {

		if ( 'lighten' === $direction ) {

			$c = hexdec( substr( $color, ( 2 * $x ), 2 ) ) + $difference;

		} else {

			$c = hexdec( substr( $color, ( 2 * $x ), 2 ) ) - $difference;

		}

		$c = ( $c < 0 ) ? 0 : dechex( $c );

		$new_color .= ( strlen( $c ) < 2 ) ? '0' . $c : $c;

	}

	/**
	 * Filters an adjusted color hex to be brighter
	 * or darker.
	 *
	 * @since @@name-framework 2.0.5
	 *
	 * @param string $new_color  Adjusted color hex, like `#000000`.
	 * @param string $color      Color hex to adjust, like `#000000`.
	 * @param int    $difference Amount to adjust color, like `20`.
	 * @param string $direction  How to adjust color, `lighten` or `darken`.
	 */
	return apply_filters( 'themeblvd_adjust_color', '#' . $new_color, $color, $difference, $direction );

}

/**
 * Get an rgb() or rgba() value, given a color hex.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  string $hex     Color hex value to convert, like `#000`.
 * @param  string $opacity Optional. Opacity value, like `0.5`, `1`, etc.
 * @return array  $output  CSS-ready color value output like `rgb(0, 0, 0)` or `rgba(0, 0, 0, 0.5)`.
 */
function themeblvd_get_rgb( $color, $opacity = '' ) {

	$output = 'rgb(0, 0, 0)';

	if ( ! $color ) {

		return $output;

	}

	$color = str_replace( '#', '', $color );

	if ( 6 === strlen( $color ) ) {

		$hex = array(
			$color[0] . $color[1],
			$color[2] . $color[3],
			$color[4] . $color[5],
		);

	} elseif ( 3 === strlen( $color ) ) {

		$hex = array(
			$color[0] . $color[0],
			$color[1] . $color[1],
			$color[2] . $color[2],
		);

	} else {

		return $output;

	}

	$rgb = array_map( 'hexdec', $hex );

	if ( $opacity ) {

		if ( abs( $opacity ) > 1 ) {

			$opacity = '1.0';

		}

		$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';

	} else {

		$output = 'rgb(' . implode( ',', $rgb ) . ')';

	}

	return $output;

}

/**
 * Get class for buttons.
 *
 * @since @@name-framework 2.4.0
 *
 * @param  string $color Color of button.
 * @param  string $size  Size of button.
 * @param  bool   $block Whether the button displays as block (TRUE) or inline (FALSE).
 * @return string $class HTML Class to be outputted into button <a> HTML.
 */
function themeblvd_get_button_class( $color = '', $size = '', $block = false ) {

	$class = 'btn';

	if ( ! $color ) {

		$color = 'default';

	}

	if ( in_array( $color, array( 'default', 'primary', 'info', 'success', 'warning', 'danger' ) ) ) {

		$class .= sprintf( ' btn-%s', $color );

	} elseif ( 'custom' === $color ) {

		$class .= ' tb-custom-button';

	} else {

		$class .= sprintf( ' %s', $color );

	}

	switch ( $size ) {

		case 'mini':
			$size = 'xs';
			break;

		case 'small':
			$size = 'sm';
			break;

		case 'large':
			$size = 'lg';
			break;

		case 'x-large':
			$size = 'xl';
			break;

		case 'xx-large':
			$size = 'xxl';
			break;

		case 'xxx-large':
			$size = 'xxxl';

	}

	if ( in_array( $size, array( 'xs', 'sm', 'lg', 'xl', 'xxl', 'xxxl' ) ) ) {

		$class .= sprintf( ' btn-%s', $size );

	}

	if ( $block ) {

		$class .= ' btn-block';

	}

	/**
	 * Filters CSS classes formmated for a button.
	 *
	 * @see themeblvd_button()
	 *
	 * @since @@name-framework 2.4.0
	 *
	 * @param string $class HTML Class to be outputted into button <a> HTML.
	 * @param string $color Color of button.
	 * @param string $size  Size of button.
	 */
	return apply_filters( 'themeblvd_get_button_class', $class, $color, $size );

}

/**
 * Get all CSS font stacks.
 *
 * @since @@name-framework 2.0.0
 *
 * @return string $stacks CSS font stacks.
 */
function themeblvd_font_stacks() {

	$stacks = array(
		'default'     => 'Arial, sans-serif', // Chained onto Google or Typekit font.
		'arial'       => 'Arial, "Helvetica Neue", Helvetica, sans-serif',
		'baskerville' => 'Baskerville, "Times New Roman", Times, serif',
		'georgia'     => 'Georgia, Times, "Times New Roman", serif',
		'helvetica'   => '"Helvetica Neue", Helvetica, Arial,sans-serif',
		'lucida'      => '"Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif',
		'palatino'    => 'Palatino, "Palatino Linotype", Georgia, Times, "Times New Roman", serif',
		'tahoma'      => 'Tahoma, Geneva, Verdana, sans-serif',
		'times'       => 'Times New Roman',
		'trebuchet'   => '"Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", Arial, sans-serif',
		'verdana'     => 'Verdana, Geneva, Tahoma, sans-serif',
		'google'      => 'Google Font',
	);

	/**
	 * Filters the CSS font stacks.
	 *
	 * @since @@name-framework 2.0.0
	 *
	 * @return array $stacks CSS font stacks.
	 */
	return apply_filters( 'themeblvd_font_stacks', $stacks );

}

/**
 * Get an individual transparent texture.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  string     $texture Texture identifier.
 * @return array|bool          Texture attributes, FALSE if texture doesn't exist.
 */
function themeblvd_get_texture( $texture ) {

	$textures = themeblvd_get_textures();

	if ( ! empty( $textures[ $texture ] ) ) {

		return $textures[ $texture ];

	}

	return false;

}

/**
 * Get the CSS classes for displaying an icon.
 *
 * @since @@name-framework 2.7.0
 *
 * @param  string $icon  Icon name.
 * @param  array  $add   CSS classes to add.
 * @return string $class CSS classes separated by spaces.
 */
function themeblvd_get_icon_class( $icon, $add = array() ) {

	$class = array();

	$add_base_class = true;

	/*
	 * Determine if a base class has been passed
	 * already through $add.
	 */
	if ( $add ) {

		foreach ( array( 'fas', 'far', 'fab' ) as $base ) {

			if ( in_array( $base, $add ) ) {

				$add_base_class = false;

			}
		}
	}

	if ( $add_base_class ) {

		/**
		 * Filters the available icon names that are
		 * identified as brands.
		 *
		 * @since @@name-framework 2.7.0
		 *
		 * @param array Brand icon names.
		 */
		$brands = apply_filters( 'themeblvd_icon_brands', array(
			// ... @TODO Can we get this from admin transient?
		));

		if ( in_array( $icon, $brands ) ) {

			$class[] = 'fab';

		} else {

			/**
			 * Filters the default fallback base class
			 * used for icons.
			 *
			 * @since @@name-framework 2.7.0
			 *
			 * @param string Default base class.
			 */
			$class[] = apply_filters( 'themeblvd_icon_base_class', 'fas' );

		}
	}

	$class[] = 'fa-' . str_replace( 'fa-', '', $icon );

	if ( $add ) {

		$class = array_merge( $class, $add );

	}

	/**
	 * Filters the classes used for displaying a
	 * generic icon.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param array $class CSS classes.
	 */
	$class = apply_filters( 'themeblvd_icon_class', $class );

	return implode( ' ', $class );

}
