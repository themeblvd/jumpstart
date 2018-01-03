<?php
/**
 * Frontend Blocks: Components
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.5.0
 */

/**
 * Get a contextual alert block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string $style   Style of alert block, `primary`, `success`, `info`, `warning`, or `danger`.
 *     @type string $class   Optional. CSS class to add.
 *     @type string $wpautop Whether to apply wpautop formatting to inner content.
 * }
 * @param  string $content Content for alert.
 * @return string $output  Final HTML output for block.
 */
function themeblvd_get_alert( $args, $content = '' ) {

	$args = wp_parse_args( $args, array(
		'style'   => 'default',
		'class'   => '',
		'wpautop' => 'true',
	));

	$class = sprintf( 'tb-alert alert alert-%s entry-content', $args['style'] );

	if ( $args['class'] ) {

		$class .= ' ' . $args['class'];

	}

	// How are we getting the content?
	if ( ! $content && ! empty( $args['content'] ) ) {

		$content = $args['content'];

	}

	// Apply wpautop?
	if ( 'true' == $args['wpautop'] || '1' == $args['wpautop'] ) {

		$content = themeblvd_get_content( $content );

	} else {

		$content = do_shortcode( themeblvd_kses( $content ) );

	}

	$output = sprintf(
		'<div class="%s">%s</div><!-- .panel (end) -->',
		esc_attr( $class ),
		$content
	);

	/**
	 * Filters the final HTML output for a contextual
	 * alert block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string $style   Style of alert block, `primary`, `success`, `info`, `warning`, or `danger`.
	 *     @type string $class   Optional. CSS class to add.
	 *     @type string $wpautop Whether to apply wpautop formatting to inner content.
	 * }
	 * @param string $content The formatting content placed within the block.
	 */
	return apply_filters( 'themeblvd_alert', $output, $args, $content );

}

/**
 * Display a contextual alert block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array  $args    Block arguments, see themeblvd_get_alert() docs.
 * @param string $content Content for alert.
 */
function themeblvd_alert( $args, $content = '' ) {

	echo themeblvd_get_alert( $args, $content );

}

/**
 * Get a divider block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string $type       Style of divider, `dashed`, `shadow`, `solid`, `thick-solid`, `thick-dashed`, `double-solid`, `double-dashed`.
 *     @type string $color      Color hex for divider, like `#ccc`.
 *     @type string $opacity    Opacity for color, like `0.5`, `0.75`, `1`, etc.
 *     @type string $insert     Whether to insert an icon or text into middle of divider.
 *     @type string $icon       FontAwesome Icon for divider insert.
 *     @type string $text       Text for divider insert.
 *     @type string $bold       Whether text should be bolded.
 *     @type string $text_color Color of icon or text, like `#666`.
 *     @type string $text_size  Size of icon or text in pixels without units, like `15`, `16`, etc.
 *     @type string       A width for the divider in pixels without units like `100`, `200`, etc.
 *     @type string $align      How to horizontally align divider, `center`, `left` or `right`.
 *     @type string $placement  Where the divider sits between the content, `equal`, `up` (closer to content above) or `down` (closer to content below).
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_divider( $args = array() ) {

	$args = wp_parse_args( $args, array(
		'type'       => 'shadow',
		'color'      => '',
		'opacity'    => '1',
		'insert'     => 'none',
		'icon'       => 'bolt',
		'text'       => '',
		'bold'       => '1',
		'text_color' => '#666666',
		'text_size'  => '15',
		'width'      => '',
		'align'      => 'center',
		'placement'  => 'equal',
	));

	$class = sprintf( 'tb-divider %s align-%s', $args['type'], $args['align'] );

	if ( 'up' === $args['placement'] || 'down' === $args['placement'] ) {

		$class .= ' suck-' . $args['placement'];

	}

	if ( ( 'icon' === $args['insert'] || 'text' === $args['insert'] ) && 'shadow' !== $args['type'] ) {

		$class .= ' has-insert';

		if ( 'icon' === $args['insert'] ) {

			$class .= ' has-icon';

		}

		if ( 'text' === $args['insert'] ) {

			$class .= ' has-text';

		}
	}

	$style = '';

	if ( $args['width'] ) {

		$style .= sprintf( 'max-width: %spx;', $args['width'] );

	}

	if ( ( 'icon' === $args['insert'] || 'text' === $args['insert'] ) && 'shadow' !== $args['type'] ) {

		$style .= sprintf( 'color: %s;', $args['text_color'] );

		$style .= sprintf( 'font-size: %spx;', $args['text_size'] );

		if ( $args['bold'] ) {

			$style .= 'font-weight: bold;';

		}
	}

	$output = sprintf(
		'<div class="%s" style="%s">',
		esc_attr( $class ),
		esc_attr( $style )
	);

	if ( 'shadow' !== $args['type'] ) {

		$style = '';

		if ( $args['color'] && 'shadow' !== $args['type'] ) {

			if ( in_array( $args['type'], array( 'solid', 'thick-solid', 'double-solid' ) ) ) {

				$style .= sprintf( 'background-color: %s;', $args['color'] );

				$style .= sprintf( 'background-color: %s;', themeblvd_get_rgb( $args['color'], $args['opacity'] ) );

			} else {

				$style .= sprintf( 'border-color: %s;', $args['color'] );

				$style .= sprintf( 'border-color: %s;', themeblvd_get_rgb( $args['color'], $args['opacity'] ) );

			}
		}

		if ( 'text' === $args['insert'] || 'icon' === $args['insert'] ) {

			if ( 'text' === $args['insert'] ) {

				$divider = sprintf( '<span class="text">%s</span>', themeblvd_kses( $args['text'] ) );

			} elseif ( 'icon' === $args['insert'] ) {

				$divider = sprintf( '<i class="%s"></i>', esc_attr( themeblvd_get_icon_class( $args['icon'] ) ) );

			}

			if ( 'double-solid' === $args['type'] ) {

				$output .= '<span class="left">';

				$output .= sprintf( '<span class="divider top" style="%s"></span>', esc_attr( $style ) );

				$output .= sprintf( '<span class="divider bottom" style="%s"></span>', esc_attr( $style ) );

				$output .= '</span>';

				$output .= $divider;

				$output .= '<span class="right">';

				$output .= sprintf( '<span class="divider top" style="%s"></span>', esc_attr( $style ) );

				$output .= sprintf( '<span class="divider bottom" style="%s"></span>', esc_attr( $style ) );

				$output .= '</span>';

			} else {

				$output .= sprintf( '<span class="divider left" style="%s"></span>', esc_attr( $style ) );

				$output .= $divider;

				$output .= sprintf( '<span class="divider right" style="%s"></span>', esc_attr( $style ) );

			}
		} else {

			if ( 'double-solid' === $args['type'] ) {

				$output .= sprintf( '<span class="divider top" style="%s"></span>', esc_attr( $style ) );

				$output .= sprintf( '<span class="divider bottom" style="%s"></span>', esc_attr( $style ) );

			} else {

				$output .= sprintf( '<span class="divider" style="%s"></span>', esc_attr( $style ) );

			}
		}
	}

	$output .= '</div><!-- .tb-divider (end) -->';

	/**
	 * Filters the final HTML output for a divider
	 * block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param string $type   The type of divider, `dashed`, `shadow`, `solid`, `thick-solid`, `thick-dashed`, `double-solid`, `double-dashed`.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string $type       Style of divider, `dashed`, `shadow`, `solid`, `thick-solid`, `thick-dashed`, `double-solid`, `double-dashed`.
	 *     @type string $color      Color hex for divider, like `#ccc`.
	 *     @type string $opacity    Opacity for color, like `0.5`, `0.75`, `1`, etc.
	 *     @type string $insert     Whether to insert an icon or text into middle of divider.
	 *     @type string $icon       FontAwesome Icon for divider insert.
	 *     @type string $text       Text for divider insert.
	 *     @type string $bold       Whether text should be bolded.
	 *     @type string $text_color Color of icon or text, like `#666`.
	 *     @type string $text_size  Size of icon or text in pixels without units, like `15`, `16`, etc.
	 *     @type string       A width for the divider in pixels without units like `100`, `200`, etc.
	 *     @type string $align      How to horizontally align divider, `center`, `left` or `right`.
	 *     @type string $placement  Where the divider sits between the content, `equal`, `up` (closer to content above) or `down` (closer to content below).
	 * }
	 */
	return apply_filters( 'themeblvd_divider', $output, $args['type'], $args );

}

/**
 * Display a divider block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_divider() docs.
 */
function themeblvd_divider( $args = array() ) {

	echo themeblvd_get_divider( $args );

}

/**
 * Get a Google Map block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array $args {
 *     Block arguments.
 *
 *     @type string      $id           Unique ID for map.
 *     @type string      $mode         Map mode, `roadmap`, `satellite`, `hybrid` or `terrain`.
 *     @type array       $markers      Location markers for map.
 *     @type string      $height       Height of map in pixels without units, like `400`.
 *     @type string      $center_type  If default, will be first location, `default` or `custom`.
 *     @type string      $center       If $center_type is custom, this will be the center point of map.
 *     @type string|int  $zoom         Zoom level of initial map, 1 to 20.
 *     @type string|int  $lightness    Map brightness, -100 to 100.
 *     @type string|int  $saturation   Map color saturation, -100 to 100.
 *     @type string|bool $has_hue      Whether map has overlay color.
 *     @type string      $hue          If $has_hue is true, overlay color for map, like '#000'.
 *     @type string|bool $zoom_control Whether user has zoom control.
 *     @type string|bool $pan_control  Whether user has pan control.
 *     @type string|bool $draggable    Whether user can drag map around.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_map( $args ) {

	wp_enqueue_script( 'google-maps-api' );

	$args = wp_parse_args( $args, array(
		'id'           => uniqid( 'map_' . rand() ),
		'mode'         => 'roadmap',
		'markers'      => array(),
		'height'       => '400',
		'center_type'  => 'default',
		'center'       => array(),
		'zoom'         => '15',
		'lightness'    => '0',
		'saturation'   => '0',
		'has_hue'      => '0',
		'hue'          => '',
		'zoom_control' => '1',
		'pan_control'  => '1',
		'draggable'    => '1',
	));

	$hue = '0';

	if ( $args['has_hue'] && $args['hue'] ) {

		$hue = $args['hue'];

	}

	$output = sprintf(
		'<div class="tb-map" data-zoom="%s" data-lightness="%s" data-saturation="%s" data-hue="%s" data-zoom_control="%s" data-pan_control="%s" data-draggable="%s" data-mode="%s">',
		esc_attr( $args['zoom'] ),
		esc_attr( $args['lightness'] ),
		esc_attr( $args['saturation'] ),
		esc_attr( $hue ),
		esc_attr( $args['zoom_control'] ),
		esc_attr( $args['pan_control'] ),
		esc_attr( $args['draggable'] ),
		esc_attr( $args['mode'] )
	);

	// Map gets inserted into this DIV via JavaScript.
	$output .= sprintf(
		'<div id="%s" class="map-canvas" style="height: %spx;"></div>',
		esc_attr( $args['id'] ),
		esc_attr( $args['height'] )
	);

	// Determine map center point.
	$center_lat = '0';

	$center_long = '0';

	if ( 'custom' === $args['center_type'] ) {

		// Custom center point.
		if ( isset( $args['center']['lat'] ) ) {

			$center_lat = $args['center']['lat'];

		}

		if ( isset( $args['center']['long'] ) ) {

			$center_long = $args['center']['long'];

		}
	} else {

		// If no custom, use first marker as center point.
		if ( $args['markers'] ) {

			foreach ( $args['markers'] as $marker ) {

				if ( isset( $marker['geo']['lat'] ) ) {

					$center_lat = $marker['geo']['lat'];

				}

				if ( isset( $marker['geo']['long'] ) ) {

					$center_long = $marker['geo']['long'];

				}

				break;

			}
		}
	}

	// Add map center point.
	$output .= sprintf(
		'<div class="map-center" data-lat="%s" data-long="%s"></div>',
		esc_attr( $center_lat ),
		esc_attr( $center_long )
	);

	// Add map markers.
	if ( $args['markers'] ) {

		$output .= '<div class="map-markers hide">';

		foreach ( $args['markers'] as $marker ) {

			$name = '';

			if ( ! empty( $marker['name'] ) ) {

				$name = $marker['name'];

			}

			$lat = '0';

			if ( ! empty( $marker['geo']['lat'] ) ) {

				$lat = $marker['geo']['lat'];

			}

			$long = '0';

			if ( ! empty( $marker['geo']['long'] ) ) {

				$long = $marker['geo']['long'];

			}

			$info = '';

			if ( ! empty( $marker['info'] ) ) {

				$info = $marker['info'];

			}

			$image = '';

			if ( ! empty( $marker['image']['src'] ) ) {

				$image = $marker['image']['src'];

			}

			$width = '';

			if ( $image && ! empty( $marker['width'] ) ) {

				$width = $marker['width'];

			}

			$height = '';

			if ( $image && ! empty( $marker['height'] ) ) {

				$height = $marker['height'];

			}

			$output .= sprintf(
				'<div class="map-marker" data-name="%s" data-lat="%s" data-long="%s" data-image="%s" data-image-width="%s" data-image-height="%s">',
				esc_attr( $name ),
				esc_attr( $lat ),
				esc_attr( $long ),
				esc_url( $image ),
				esc_attr( $width ),
				esc_attr( $height )
			);

			$output .= sprintf( '<div class="map-marker-info">%s</div>', themeblvd_get_content( $info ) );

			$output .= '</div><!-- .map-marker (end) -->';

		}

		$output .= '</div><!-- .map-markers (end) -->';

	}

	$output .= '</div><!-- .tb-map (end) -->';

	/**
	 * Filters the final HTML output for a Google Map
	 * block.
	 *
	 * Note: The markup generated here is constructed
	 * mainly for the purpose of theme's JavaScript to
	 * pull information from, in order to communicate
	 * and build a map with the Google Maps API.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string      $id           Unique ID for map.
	 *     @type string      $mode         Map mode, `roadmap`, `satellite`, `hybrid` or `terrain`.
	 *     @type array       $markers      Location markers for map.
	 *     @type string      $height       Height of map in pixels without units, like `400`.
	 *     @type string      $center_type  If default, will be first location, `default` or `custom`.
	 *     @type string      $center       If $center_type is custom, this will be the center point of map.
	 *     @type string|int  $zoom         Zoom level of initial map, 1 to 20.
	 *     @type string|int  $lightness    Map brightness, -100 to 100.
	 *     @type string|int  $saturation   Map color saturation, -100 to 100.
	 *     @type string|bool $has_hue      Whether map has overlay color.
	 *     @type string      $hue          If $has_hue is true, overlay color for map, like '#000'.
	 *     @type string|bool $zoom_control Whether user has zoom control.
	 *     @type string|bool $pan_control  Whether user has pan control.
	 *     @type string|bool $draggable    Whether user can drag map around.
	 * }
	 */
	return apply_filters( 'themeblvd_map', $output, $args );

}

/**
 * Display a Google Map block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_map() docs.
 */
function themeblvd_map( $args ) {

	echo themeblvd_get_map( $args );

}

/**
 * Get an icon box block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array $args {
 *     Block arguments.
 *
 *     @type string      $icon        FontAwesome icon ID, like `clock`.
 *     @type string      $size        Font size of font icon with units, like `20px`.
 *     @type string      $location    Location of icon, `above`, `side` or `side-alt`.
 *     @type string      $color       Color of the icon, like `#000`.
 *     @type string|bool $badge       Whether to wrap icon in a circle.
 *     @type string|bool $badge_trans Whether that badge is transparent.
 *     @type string      $title       Title of the block.
 *     @type string      $text        Content of the block.
 *     @type string      $style       Optional. Custom styling class.
 *     @type string      $text_color  Color of text, `none`, `dark` or `light`.
 *     @type string      $bg_color    If $style is `custom` a background color, like `#000`.
 *     @type string      $bg_opacity  If $style is `custom` a background color opacity, like `0.5`, `1`, etc.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_icon_box( $args ) {

	$args = wp_parse_args( $args, array(
		'icon'        => '',
		'size'        => '20px',
		'location'    => 'above',
		'color'       => '#666666',
		'badge'       => '0',
		'badge_trans' => '0',
		'title'       => '',
		'text'        => '',
		'style'       => '',
		'text_color'  => 'none',
		'bg_color'    => '#cccccc',
		'bg_opacity'  => '1',
	));

	$class = sprintf( 'tb-icon-box icon-%s', $args['location'] );

	if ( 'custom' === $args['style'] ) {

		$class .= ' has-bg';

		if ( 'none' !== $args['text_color'] ) {

			$class .= ' text-' . $args['text_color'];

		}
	} elseif ( $args['style'] && 'none' !== $args['style'] ) {

		$class .= ' ' . $args['style'];

	}

	if ( '#ffffff' === $args['color'] || '#fff' === $args['color'] ) {

		$class .= ' has-white-icon';

	}

	// Build icon HTML.
	if ( $args['badge'] && $args['badge_trans'] ) {

		$size = intval( str_replace( 'px', '', $args['size'] ) );

		$lh = ( 3 * $size ) - 2;

		$lh = $lh . 'px';

		$icon = sprintf(
			'<div class="icon trans-badge" style="border-color: %s; color: %s; font-size: %s;"><i class="%s" style="line-height: %s"></i></div>',
			esc_attr( $args['color'] ),
			esc_attr( $args['color'] ),
			esc_attr( $args['size'] ),
			esc_attr( themeblvd_get_icon_class( $args['icon'] ) ),
			$lh
		);

	} elseif ( $args['badge'] ) {

		$size_3x = intval( $args['size'] );

		$size_3x = $size_3x * 3;

		$size_3x = $size_3x . 'px';

		$icon = sprintf(
			'<div class="icon"><span class="fa-layers fa-fw" style="font-size: %1$s; width: %1$s;"><i class="%2$s" style="color: %3$s;"></i><i class="%4$s" style="font-size: %5$s;"></i></span></div>',
			$size_3x,
			esc_attr( themeblvd_get_icon_class( 'circle', array( 'fas' ) ) ), // Passing `fas` forces to use .fas if the default base icon class were filters to something different.
			esc_attr( $args['color'] ),
			esc_attr( themeblvd_get_icon_class( $args['icon'], array( 'fa-inverse' ) ) ),
			esc_attr( $args['size'] )
		);

	} else {

		$icon = sprintf(
			'<div class="icon" style="color: %s; font-size: %s;"><i class="%s" style="width:%s;"></i></div>',
			esc_attr( $args['color'] ),
			esc_attr( $args['size'] ),
			esc_attr( themeblvd_get_icon_class( $args['icon'] ) ),
			esc_attr( $args['size'] )
		);

	}

	// Determine the content style.
	$content_style = '';

	if ( 'side' === $args['location'] || 'side-alt' === $args['location'] ) {

		$width = intval( $args['size'] );

		if ( $args['badge'] ) {

			$width = $width * 3;

		}

		$padding = $width + 15;

		if ( 'side-alt' === $args['location'] ) {

			$content_style = sprintf( 'padding-right: %spx;', $padding );

		} else {

			$content_style = sprintf( 'padding-left: %spx;', $padding );

		}
	}

	// Set inline style.
	$style = '';

	if ( 'custom' === $args['style'] ) {

		$style = sprintf(
			'background-color: %s;',
			esc_attr( themeblvd_get_rgb( $args['bg_color'], $args['bg_opacity'] ) )
		);

	}

	$output  = sprintf( '<div class="%s" style="%s">', $class, $style );

	$output .= $icon;

	if ( $args['title'] || $args['text'] ) {

		$output .= '<div class="entry-content" style="' . $content_style . '">';

		$output .= '<h3 class="icon-box-title">' . themeblvd_kses( $args['title'] ) . '</h3>';

		$output .= themeblvd_get_content( $args['text'] );

		$output .= '</div><!-- .content (end) -->';

	}

	$output .= '</div><!-- .tb-icon-box (end) -->';

	/**
	 * Filters the final HTML output for an icon box
	 * block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string      $icon        FontAwesome icon ID, like `clock`.
	 *     @type string      $size        Font size of font icon with units, like `20px`.
	 *     @type string      $location    Location of icon, `above`, `side` or `side-alt`.
	 *     @type string      $color       Color of the icon, like `#000`.
	 *     @type string|bool $badge       Whether to wrap icon in a circle.
	 *     @type string|bool $badge_trans Whether that badge is transparent.
	 *     @type string      $title       Title of the block.
	 *     @type string      $text        Content of the block.
	 *     @type string      $style       Optional. Custom styling class.
	 *     @type string      $text_color  Color of text, `none`, `dark` or `light`.
	 *     @type string      $bg_color    If $style is `custom` a background color, like `#000`.
	 *     @type string      $bg_opacity  If $style is `custom` a background color opacity, like `0.5`, `1`, etc.
	 * }
	 * @param string  $icon Full HTML for icon. Useful with str_replace() for a new icon within final HTML.
	 */
	return apply_filters( 'themeblvd_icon_box', $output, $args, $icon );

}

/**
 * Display an icon box block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_icon_box() docs.
 */
function themeblvd_icon_box( $args ) {

	echo themeblvd_get_icon_box( $args );

}

/**
 * Get hero unit block.
 *
 * @since @@name-framework 2.4.2
 *
 * @param  array $args {
 *     Block arguments.
 *
 *     @type array       $blocks                    Text blocks to display within hero unit.
 *     @type string      $bg_type                   Type of background.
 *     @type string      $bg_color                  Background color, like `#000`.
 *     @type string      $bg_color_opacity          Background color opacity, like `0.5`, `1`, etc.
 *     @type string      $bg_texture                Background texture.
 *     @type string|bool $apply_bg_texture_parallax Whether to apply parallax effect to background image.
 *     @type string|int  $bg_texture_parallax       Background txture parallax amount, 1-10.
 *     @type string      $bg_image                  Background image URL.
 *     @type string|int  $bg_image_parallax         Background parallax amount, 1-10.
 *     @type string      $bg_video                  Background video URL.
 *     @type string|bool $apply_bg_shade            Whether to apply background shade color overlay.
 *     @type string      $bg_shade_color            Background shade overlay color.
 *     @type string      $bg_shade_opacity          Background shade overlay color opacity, like `0.5`, `0.75`, etc.
 *     @type string      $text_align                How to align all text, `left`, `right` or `center`.
 *     @type string      $max                       Max width, meant to be used with $align left/right/center, like `300px`, `50%`, etc.
 *     @type string      $align                     How to align inner text blocks - `left`, `right`, `center`, or `blank` for no alignment.
 *     @type string|bool $apply_content_bg          Whether to apply background color just around text blocks.
 *     @type string      $content_bg_color          Background color just around content blocks, like `#000`.
 *     @type string      $content_bg_opacity        Background color opacity just around content blocks, like `0.5`, `1`, etc.
 *     @type string|bool $height_100vh              Whether to match outer height to viewport.
 *     @type string|bool $section_jump              Whether to show button to jump to next section (needs $height_100vh to be true).
 *     @type array       $buttons                   Any buttons to include after text blocks.
 *     @type string|bool $buttons_stack             Whether buttons should stack.
 *     @type string|bool $buttons_block             Whether buttons should display as block-level.
 *     @type string|bool $wpautop                   Whether to apply wpautop formatting (only used with $content var)
 *     @type string      $class                     Optional. CSS class(es) to add.
 * }
 * @param  string $content Inner content, if not passed in as content blocks from $args['blocks'].
 * @return string $output  Final HTML output for block.
 */
function themeblvd_get_jumbotron( $args, $content = '' ) {

	$output = '';

	$args = wp_parse_args( $args, array(
		'blocks'                    => array(),
		'bg_type'                   => '',
		'bg_color'                  => '#333333',
		'bg_color_opacity'          => '1',
		'bg_texture'                => '',
		'apply_bg_texture_parallax' => '0',
		'bg_texture_parallax'       => '5',
		'bg_image'                  => array(),
		'bg_image_parallax'         => '2',
		'bg_video'                  => '',
		'apply_bg_shade'            => '0',
		'bg_shade_color'            => '#000000',
		'bg_shade_opacity'          => '0.5',
		'text_align'                => 'left',
		'max'                       => '',
		'align'                     => 'center',
		'apply_content_bg'          => '',
		'content_bg_color'          => '',
		'content_bg_opacity'        => '1',
		'height_100vh'              => '0',
		'section_jump'              => '0',
		'buttons'                   => array(),
		'buttons_stack'             => '0',
		'buttons_block'             => '0',
		'wpautop'                   => '1',
		'class'                     => '',
	));

	$class = sprintf(
		'tb-jumbotron jumbotron entry-content text-%s',
		$args['text_align']
	);

	$style = '';

	if ( $args['class'] ) {

		$class .= ' ' . $args['class'];

	}

	if ( $args['apply_content_bg'] ) {

		$style .= sprintf(
			'background-color: %s;',
			themeblvd_get_rgb( $args['content_bg_color'], $args['content_bg_opacity'] )
		);

		$class .= ' has-bg';

	}

	// Align hero unit right or left?
	if ( 'left' === $args['align'] ) {

		$class .= ' pull-left';

	} elseif ( 'right' === $args['align'] ) {

		$class .= ' pull-right';

	} else {

		$class .= ' pull-none';

	}

	// Align hero unit center?
	if ( 'center' === $args['align'] ) {

		$style .= 'margin-left: auto; margin-right: auto; ';

	}

	// Maximum width?
	if ( $args['max'] ) {

		$style .= sprintf( 'max-width: %s;', $args['max'] );

	}

	/*
	 * Determine content source and apply formatting.
	 *
	 * If original $content paramater was passed into function,
	 * this will get used with wpautop formatting (if enabled).
	 *
	 * If $args['blocks'] was passed in, this most likely
	 * means the data is coming from configured text blocks
	 * of the layout builder. This is an alternative to using
	 * the $content parameter.
	 */
	if ( ! $content && $args['blocks'] ) {

		$content = themeblvd_get_text_blocks( $args['blocks'] );

	} elseif ( $args['wpautop'] ) {

		$content = themeblvd_get_content( $content );

	}

	// Add buttons to $content.
	if ( $args['buttons'] ) {

		if ( $args['buttons_block'] ) {

			foreach ( $args['buttons'] as $key => $value ) {

				$args['buttons'][ $key ]['block'] = true;

			}
		}

		$content .= "\n\t<div class=\"jumbotron-buttons\">";

		$content .= "\n\t\t" . themeblvd_get_buttons(
			$args['buttons'],
			array(
				'stack' => $args['buttons_stack'],
			)
		);

		$content .= "\n\t</div><!-- .jumbotron-buttons -->\n";

	}

	/*
	 * Start output for hero unit, but keep in separate
	 * variable from outer output.
	 */
	$jumbotron = sprintf(
		'<div class="%s" style="%s">%s</div>',
		esc_attr( $class ),
		esc_attr( $style ),
		$content
	);

	// Start final output.
	$output = sprintf(
		'<div class="jumbotron-wrap clearfix">%s</div>',
		$jumbotron
	);

	// Add button to jump to next section.
	if ( $args['height_100vh'] && $args['section_jump'] ) {

		$output .= themeblvd_get_to_section();

	}

	// Wrap output, if has background.
	if ( $args['bg_type'] && 'none' !== $args['bg_type'] ) {

		$jumbotron = $output;

		$class = 'jumbotron-outer clearfix';

		if ( $args['height_100vh'] ) {
			$class .= ' height-100vh';
		}

		$class .= ' ' . implode( ' ', themeblvd_get_display_class( $args ) );

		$output = sprintf(
			'<div class="%s" style="%s">',
			esc_attr( $class ),
			esc_attr( themeblvd_get_display_inline_style( $args ) )
		);

		if ( in_array( $args['bg_type'], array( 'image', 'slideshow', 'video' ) ) && ! empty( $args['apply_bg_shade'] ) ) {

			$output .= sprintf(
				'<div class="bg-shade" style="background-color: %s;"></div>',
				esc_attr( themeblvd_get_rgb( $args['bg_shade_color'], $args['bg_shade_opacity'] ) )
			);

		}

		if ( themeblvd_do_parallax( $args ) ) {

			$output .= themeblvd_get_bg_parallax( $args );

		}

		if ( 'video' === $args['bg_type'] && ! empty( $args['bg_video'] ) ) {

			$output .= themeblvd_get_bg_video( $args['bg_video'] );

		}

		$output .= $jumbotron;

		$output .= '</div><!-- .jumbotron-outer (end) -->';

	}

	/**
	 * Filters the final HTML output for an icon box
	 * block.
	 *
	 * @since @@name-framework 2.4.2
	 *
	 * @param string $output   Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type array       $blocks                    Text blocks to display within hero unit.
	 *     @type string      $bg_type                   Type of background.
	 *     @type string      $bg_color                  Background color, like `#000`.
	 *     @type string      $bg_color_opacity          Background color opacity, like `0.5`, `1`, etc.
	 *     @type string      $bg_texture                Background texture.
	 *     @type string|bool $apply_bg_texture_parallax Whether to apply parallax effect to background image.
	 *     @type string|int  $bg_texture_parallax       Background txture parallax amount, 1-10.
	 *     @type string      $bg_image                  Background image URL.
	 *     @type string|int  $bg_image_parallax         Background parallax amount, 1-10.
	 *     @type string      $bg_video                  Background video URL.
	 *     @type string|bool $apply_bg_shade            Whether to apply background shade color overlay.
	 *     @type string      $bg_shade_color            Background shade overlay color.
	 *     @type string      $bg_shade_opacity          Background shade overlay color opacity, like `0.5`, `0.75`, etc.
	 *     @type string      $text_align                How to align all text, `left`, `right` or `center`.
	 *     @type string      $max                       Max width, meant to be used with $align left/right/center, like `300px`, `50%`, etc.
	 *     @type string      $align                     How to align inner text blocks - `left`, `right`, `center`, or `blank` for no alignment.
	 *     @type string|bool $apply_content_bg          Whether to apply background color just around text blocks.
	 *     @type string      $content_bg_color          Background color just around content blocks, like `#000`.
	 *     @type string      $content_bg_opacity        Background color opacity just around content blocks, like `0.5`, `1`, etc.
	 *     @type string|bool $height_100vh              Whether to match outer height to viewport.
	 *     @type string|bool $section_jump              Whether to show button to jump to next section (needs $height_100vh to be true).
	 *     @type array       $buttons                   Any buttons to include after text blocks.
	 *     @type string|bool $buttons_stack             Whether buttons should stack.
	 *     @type string|bool $buttons_block             Whether buttons should display as block-level.
	 *     @type string|bool $wpautop                   Whether to apply wpautop formatting (only used with $content var)
	 *     @type string      $class                     Optional. CSS class(es) to add.
	 * }
	 * @param string $content   HTML output for the content within the hero unit.
	 * @param string $jumbotron HTML output for the isolated inner hero unit.
	 */
	return apply_filters( 'themeblvd_jumbotron', $output, $args, $content, $jumbotron );

}

/**
 * Display hero unit block.
 *
 * @since @@name-framework 2.4.2
 *
 * @param array  $args    Block arguments, see themeblvd_get_jumbotron() docs.
 * @param string $content Inner content, if not passed in as content blocks from $args['blocks'].
 */
function themeblvd_jumbotron( $args, $content = '' ) {

	echo themeblvd_get_jumbotron( $args, $content );

}

/**
 * Get a logos block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type array       $logos     Logos to display.
 *     @type string      $title     Title for the block.
 *     @type string      $display   How to display logos, `grid` or `slider`.
 *     @type string      $slide     If $display == `slider`, number of logos per slide.
 *     @type string|bool $nav       If $display == `slider`, whether to display nav.
 *     @type string      $timeout   If $display == `slider`, seconds in between auto-rotation.
 *     @type string      $grid      If $display == `grid`, number of logos per row.
 *     @type string      $height    Unified height across all logos.
 *     @type string|bool $boxed     Whether to dispay box arond logo.
 *     @type string|bool $greyscale Whether to display logos as black and white.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_logos( $args ) {

	$args = wp_parse_args( $args, array(
		'logos'     => array(),
		'title'     => '',
		'display'   => 'slider',
		'slide'     => '4',
		'nav'       => '1',
		'timeout'   => '3',
		'grid'      => '4',
		'height'    => '100',
		'boxed'     => '1',
		'greyscale' => '1',
	));

	$class = 'tb-logos ' . $args['display'];

	if ( $args['boxed'] ) {

		$class .= ' has-boxed';

	}

	if ( $args['title'] ) {

		$class .= ' has-title';

	}

	if ( 'slider' === $args['display'] ) {

		$class .= ' tb-block-slider flexslider';

		if ( $args['nav'] ) {

			$class .= ' has-nav';

		}
	}

	$output = sprintf(
		'<div class="%s" data-timeout="%s" data-nav="%s" data-fx="slide">',
		esc_attr( $class ),
		esc_attr( $args['timeout'] ),
		esc_attr( $args['nav'] )
	);

	if ( $args['title'] ) {

		$output .= sprintf(
			'<h3 class="title">%s</h3>',
			themeblvd_kses( $args['title'] )
		);

	}

	$output .= '<div class="tb-logos-inner tb-block-slider-inner slider-inner">';

	if ( 'slider' === $args['display'] ) {

		$output .= themeblvd_get_loader();

		if ( $args['nav'] ) {

			$output .= themeblvd_get_slider_controls();

		}
	}

	if ( $args['logos'] ) {

		$img_class = '';

		if ( $args['greyscale'] ) {

			$img_class .= 'greyscale';

		}

		$wrap_class = 'tb-logo';

		if ( $args['boxed'] ) {

			$wrap_class .= ' boxed';

		}

		$wrap_style = '';

		if ( $args['height'] ) {

			$wrap_style .= sprintf( 'height: %spx;', $args['height'] );

		}

		if ( 'slider' === $args['display'] ) {

			$num_per = intval( $args['slide'] );

		} else {

			$num_per = intval( $args['grid'] );

		}

		$grid_class = themeblvd_grid_class( $num_per );

		if ( 'slider' === $args['display'] ) {

			$output .= '<ul class="slides">';

			$output .= '<li class="slide">';

		}

		$output .= themeblvd_get_open_row();

		$total = count( $args['logos'] );

		$i = 1;

		foreach ( $args['logos'] as $logo ) {

			$img = sprintf(
				'<img src="%s" alt="%s" class="%s" />',
				esc_url( $logo['src'] ),
				esc_attr( $logo['alt'] ),
				esc_attr( $img_class )
			);

			if ( $logo['link'] ) {

				$img = sprintf( '<a href="%s" title="%s" class="%s" style="%s" target="_blank">%s</a>',
					esc_url( $logo['link'] ),
					esc_attr( $logo['name'] ),
					esc_attr( $wrap_class ),
					esc_attr( $wrap_style ),
					$img
				);

			} else {

				$img = sprintf(
					'<div class="%s" style="%s">%s</div>',
					esc_attr( $wrap_class ),
					esc_attr( $wrap_style ),
					$img
				);

			}

			$output .= sprintf(
				'<div class="col %s">%s</div>',
				$grid_class,
				$img
			);

			if ( 0 === $i % $num_per && $i < $total ) {

				$output .= themeblvd_get_close_row();

				if ( 'slider' === $args['display'] ) {

					$output .= '</li>';

					$output .= '<li class="slide">';

				}

				$output .= themeblvd_get_open_row();

			}

			$i++;

		}

		$output .= themeblvd_get_close_row();

		if ( 'slider' === $args['display'] ) {

			$output .= '</li>';

			$output .= '</ul>';

		}
	}

	$output .= '</div><!-- .tb-logos-inner (end) -->';

	$output .= '</div><!-- .tb-logos (end) -->';

	/**
	 * Filters the final HTML output for a logos
	 * block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type array       $logos     Logos to display.
	 *     @type string      $title     Title for the block.
	 *     @type string      $display   How to display logos, `grid` or `slider`.
	 *     @type string      $slide     If $display == `slider`, number of logos per slide.
	 *     @type string|bool $nav       If $display == `slider`, whether to display nav.
	 *     @type string      $timeout   If $display == `slider`, seconds in between auto-rotation.
	 *     @type string      $grid      If $display == `grid`, number of logos per row.
	 *     @type string      $height    Unified height across all logos.
	 *     @type string|bool $boxed     Whether to dispay box arond logo.
	 *     @type string|bool $greyscale Whether to display logos as black and white.
	 * }
	 */
	return apply_filters( 'themeblvd_logos', $output, $args );

}

/**
 * Display a logos block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_logos() docs.
 */
function themeblvd_logos( $args ) {

	echo themeblvd_get_logos( $args );

}

/**
 * Get a panel block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string      $style      Style of panel, `primary`, `success`, `info`, `warning` or `danger`.
 *     @type string      $title      Title text for panel.
 *     @type string      $footer     Footer text for panel.
 *     @type string      $text_align How to align text, `left`, `right` or `center`.
 *     @type string      $align      How to align outer panel, `left` or `right`.
 *     @type string      $max_width  Maximum width, like `50%`, `300px`, etc.
 *     @type string      $class      Optional. CSS class(es) to add.
 *     @type string|bool $wpautop    Whether to apply wpautop to panel content.
 * }
 * @param  string $content Content for alert.
 * @return string $output  Final HTML output for block.
 */
function themeblvd_get_panel( $args, $content = '' ) {

	$args = wp_parse_args( $args, array(
		'style'      => 'default',
		'title'      => '',
		'footer'     => '',
		'text_align' => '',
		'align'      => '',
		'max_width'  => '',
		'class'      => '',
		'wpautop'    => 'true',
	));

	// CSS classes
	$class = sprintf( 'tb-panel panel panel-%s', $args['style'] );

	if ( $args['text_align'] ) {
		$class .= ' text-' . $args['text_align'];
	}

	if ( $args['class'] ) {
		$class .= ' ' . $args['class'];
	}

	// How are we getting the content?
	if ( ! $content && ! empty( $args['content'] ) ) {
		$content = $args['content'];
	}

	// wpautop formatting?
	if ( 'true' == $args['wpautop'] || '1' == $args['wpautop'] ) {
		$content = themeblvd_get_content( $content );
	} else {
		$content = do_shortcode( themeblvd_kses( $content ) );
	}

	$output = sprintf( '<div class="%s">', esc_attr( $class ) );

	if ( $args['title'] ) {

		$output .= sprintf(
			'<div class="panel-heading"><h3 class="panel-title">%s</h3></div>',
			themeblvd_kses( $args['title'] )
		);

	}

	$output .= sprintf(
		'<div class="panel-body entry-content">%s</div>',
		$content
	);

	if ( $args['footer'] ) {

		$output .= sprintf(
			'<div class="panel-footer">%s</div>',
			themeblvd_kses( $args['footer'] )
		);

	}

	$output .= '</div><!-- .panel (end) -->';

	/**
	 * Filters the final HTML output for a panel
	 * block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string      $style      Style of panel, `primary`, `success`, `info`, `warning` or `danger`.
	 *     @type string      $title      Title text for panel.
	 *     @type string      $footer     Footer text for panel.
	 *     @type string      $text_align How to align text, `left`, `right` or `center`.
	 *     @type string      $align      How to align outer panel, `left` or `right`.
	 *     @type string      $max_width  Maximum width, like `50%`, `300px`, etc.
	 *     @type string      $class      Optional. CSS class(es) to add.
	 *     @type string|bool $wpautop    Whether to apply wpautop to panel content.
	 * }
	 */
	return apply_filters( 'themeblvd_panel', $output, $args );

}

/**
 * Display a panel block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array  $args    Block arguments, see themeblvd_get_panel() docs.
 * @param string $content Content for panel.
 */
function themeblvd_panel( $args, $content = '' ) {

	echo themeblvd_get_panel( $args, $content );

}

/**
 * Get a promo box block, formally known as a
 * slogan block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string      $headline           Headline text for block.
 *     @type string      $desc               Subtext for block.
 *     @type string|bool $wpautop            Whether to apply wpautop formatting to block text.
 *     @type string      $max                Maximum width of block, like `500px`, `50%`, etc.
 *     @type string      $style              Custom styling class, will equal `custom` to trigger custom styling params.
 *     @type string      $bg_color           If $style == `custom`, background color like `#000`.
 *     @type string      $bg_opacity         If $style == `custom`, background color opacity like `0.5`, `1`, etc.
 *     @type string      $text_color         If $style == `custom`, text color like `#000`.
 *     @type string|bool $button             Whether to show call-to-action button.
 *     @type string      $button_color       Button color class.
 *     @type array       $button_custom      If $button_color == `custom`, custom color attributes for button.
 *     @type string      $button_text        Button text.
 *     @type string      $button_size        Button size, `mini`, `small`, `default` or `large`.
 *     @type string      $button_placement   Button placement, `left`, `right` or `below`.
 *     @type string      $button_url         Button URL linked to.
 *     @type string      $button_target      Button target attribute, `_self` or `_blank`.
 *     @type string      $button_icon_before FontAwesome icon before button text.
 *     @type string      $button_icon_after  FontAwesome icon after button text.
 * }
 * @return string $output  Final HTML output for block.
 */
function themeblvd_get_slogan( $args ) {

	$args = wp_parse_args( $args, array(
		'headline'           => '',
		'desc'               => '',
		'wpautop'            => 1,
		'max'                => '',
		'style'              => 'none',
		'bg_color'           => '',
		'bg_opacity'         => '1',
		'text_color'         => '',
		'button'             => 1,
		'button_color'       => 'default',
		'button_custom'      => array(
			'bg'             => '#ffffff',
			'bg_hover'       => '#ebebeb',
			'border'         => '#cccccc',
			'text'           => '#333333',
			'text_hover'     => '#333333',
			'include_bg'     => 1,
			'include_border' => 1,
		),
		'button_text'        => 'Purchase Now',
		'button_size'        => 'large',
		'button_placement'   => 'right',
		'button_url'         => '',
		'button_target'      => '_self',
		'button_icon_before' => '',
		'button_icon_after'  => '',
	));

	// CSS classes.
	$class = sprintf( 'tb-slogan clearfix' );

	if ( $args['button'] ) {

		$class .= ' has-button';

		if ( 'left' === $args['button_placement'] ) {

			$class .= ' button-left';

		} elseif ( 'right' === $args['button_placement'] ) {

			$class .= ' button-right';

		} else {

			$class .= ' button-below';

		}
	} else {

		$class .= ' text-only';

	}

	if ( $args['headline'] ) {

		$class .= ' has-headline';

	}

	if ( $args['desc'] ) {

		$class .= ' has-desc';

	}

	if ( $args['max'] ) {

		$class .= ' has-max-width';

	}

	// Inline styles.
	$style = '';

	if ( 'custom' === $args['style'] ) {

		if ( $args['bg_color'] ) {

			$style .= sprintf( 'background-color:%s;', themeblvd_get_rgb( $args['bg_color'], $args['bg_opacity'] ) );

			$class .= ' has-bg';

		}

		if ( $args['text_color'] ) {

			$style .= sprintf( 'color:%s;', $args['text_color'] );

		}
	}

	// Maximum width?
	if ( $args['max'] ) {

		$style .= sprintf( 'max-width:%s;', $args['max'] );

	}

	// Setup optional button.
	$button = '';

	if ( $args['button'] ) {

		// Custom button styling.
		$addon = '';

		if ( 'custom' === $args['button_color'] ) {

			if ( $args['button_custom']['include_bg'] ) {

				$bg = $args['button_custom']['bg'];

			} else {

				$bg = 'transparent';

			}

			if ( $args['button_custom']['include_border'] ) {

				$border = $args['button_custom']['border'];

			} else {

				$border = 'transparent';

			}

			$addon = sprintf(
				'style="background-color: %1$s; border-color: %2$s; color: %3$s;" data-bg="%1$s" data-bg-hover="%4$s" data-text="%3$s" data-text-hover="%5$s"',
				$bg,
				$border,
				$args['button_custom']['text'],
				$args['button_custom']['bg_hover'],
				$args['button_custom']['text_hover']
			);

		}

		$button = themeblvd_button(
			$args['button_text'],
			$args['button_url'],
			$args['button_color'],
			$args['button_target'],
			$args['button_size'],
			null,
			null,
			$args['button_icon_before'],
			$args['button_icon_after'],
			$addon
		);

	}

	// Custom CSS classes.
	if ( $args['style'] && 'custom' !== $args['style'] && 'none' !== $args['style'] ) {

		$class .= ' ' . $args['style'];

	}

	// Start output.
	$output = sprintf( '<div class="%s" style="%s">', esc_attr( $class ), esc_attr( $style ) );

	$content = '';

	if ( $args['headline'] ) {

		$content .= sprintf(
			'<div class="headline">%s</div>',
			themeblvd_kses( $args['headline'] )
		);

	}

	if ( $args['desc'] ) {

		$content .= sprintf(
			'<div class="desc">%s</div>',
			themeblvd_kses( $args['desc'] )
		);

	}

	// Add wpautop formatting?
	if ( $args['wpautop'] ) {

		$content = wpautop( $content );

	}

	if ( $button ) {

		if ( 'left' === $args['button_placement'] || 'right' === $args['button_placement'] ) {

			$content = $button . $content;

		} else {

			$content .= $button;

		}
	}

	$output .= sprintf(
		'<div class="slogan-text entry-content">%s</div>',
		do_shortcode( $content )
	);

	$output .= '</div><!-- .slogan (end) -->';

	/**
	 * Filters the final HTML output for a promo box
	 * block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string      $headline           Headline text for block.
	 *     @type string      $desc               Subtext for block.
	 *     @type string|bool $wpautop            Whether to apply wpautop formatting to block text.
	 *     @type string      $max                Maximum width of block, like `500px`, `50%`, etc.
	 *     @type string      $style              Custom styling class, will equal `custom` to trigger custom styling params.
	 *     @type string      $bg_color           If $style == `custom`, background color like `#000`.
	 *     @type string      $bg_opacity         If $style == `custom`, background color opacity like `0.5`, `1`, etc.
	 *     @type string      $text_color         If $style == `custom`, text color like `#000`.
	 *     @type string|bool $button             Whether to show call-to-action button.
	 *     @type string      $button_color       Button color class.
	 *     @type array       $button_custom      If $button_color == `custom`, custom color attributes for button.
	 *     @type string      $button_text        Button text.
	 *     @type string      $button_size        Button size, `mini`, `small`, `default` or `large`.
	 *     @type string      $button_placement   Button placement, `left`, `right` or `below`.
	 *     @type string      $button_url         Button URL linked to.
	 *     @type string      $button_target      Button target attribute, `_self` or `_blank`.
	 *     @type string      $button_icon_before FontAwesome icon before button text.
	 *     @type string      $button_icon_after  FontAwesome icon after button text.
	 * }
	 */
	return apply_filters( 'themeblvd_slogan', $output, $args );

}

/**
 * Display a promo box block, formally known as a
 * slogan block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_slogan() docs.
 */
function themeblvd_slogan( $args ) {

	echo themeblvd_get_slogan( $args );

}

/**
 * Get a tabs block.
 *
 * @since @@name-framework 2.0.0
 *
 * @param  string $id Unique ID for this tabs block.
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type array       $tabs   Tabs from sortable option type.
 *     @type string      $nav    Style of navigation, `tabs` or `pills`.
 *     @type string      $style  Style of unit, `framed` or `open`; filter on style choices with `themeblvd_tab_styles`.
 *     @type string|bool $height Whether forced shared height of all columns.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_tabs( $id, $args ) {

	$args = wp_parse_args( $args, array(
		'tabs'   => array(),
		'nav'    => 'tabs',
		'style'  => 'framed',
		'height' => '0',
	));

	$navigation = '';

	$content = '';

	$output = '';

	// Tabs or pills?
	$nav_type = $args['nav'];

	if ( 'tabs' !== $nav_type && 'pills' !== $nav_type ) {

		$nav_type = 'tabs';

	}

	// Container classes.
	$classes = 'tb-tabs tabbable';

	if ( ! empty( $args['height'] ) ) {

		$classes .= ' fixed-height';

	}

	$classes .= ' tb-tabs-' . $args['style'];

	if ( 'pills' === $nav_type ) {
		$classes .= ' tb-tabs-pills';
	}

	/**
	 * Filters whether to allow deep linking for tabs.
	 *
	 * Deep linking for tabs means that the user can
	 * link to #tab_{ID OF TAB} and that tab will
	 * automatically open.
	 *
	 * @since @@name-framework 2.4.4
	 *
	 * @param bool Whether deeplinking is enabled.
	 */
	$deep = apply_filters( 'themeblvd_tabs_deep_linking', false );

	// Setup tabs navigation.
	$i = 1;

	$navigation .= '<ul class="nav nav-' . $nav_type . '">';

	if ( $args['tabs'] && is_array( $args['tabs'] ) ) {

		foreach ( $args['tabs'] as $tab_id => $tab ) {

			$class = '';

			if ( 1 === $i ) {

				$class = 'active';

			}

			$name = $tab['title'];

			if ( $deep ) {

				$tab_id = str_replace( ' ', '_', $name );

				$tab_id = preg_replace( '/\W/', '', strtolower( $tab_id ) );

			} else {

				$tab_id = $id . '-' . $tab_id;

			}

			$navigation .= sprintf(
				'<li class="%s"><a href="#%s" data-toggle="%s" title="%s">%s</a></li>',
				$class,
				$tab_id,
				str_replace( 's', '', $nav_type ),
				esc_attr( $name ),
				themeblvd_kses( $name )
			);

			$i++;

		}
	}

	$navigation .= '</ul>';

	// Setup tab content.
	$i = 1;

	$content = '<div class="tab-content">';

	$content .= "\n";

	if ( $args['tabs'] && is_array( $args['tabs'] ) ) {

		foreach ( $args['tabs'] as $tab_id => $tab ) {

			$class = 'tab-pane entry-content fade in clearfix';

			if ( 1 === $i ) {
				$class .= ' active';
			}

			if ( $deep ) {

				$tab_id = str_replace( ' ', '_', $tab['title'] );

				$tab_id = preg_replace( '/\W/', '', strtolower( $tab_id ) );

			} else {

				$tab_id = $id . '-' . $tab_id;

			}

			$content .= sprintf( '<div id="%s" class="%s">', $tab_id, $class );

			switch ( $tab['content']['type'] ) {

				case 'widget':
					$content .= themeblvd_get_widgets( $tab['content']['sidebar'], 'tabs' );
					break;

				case 'page':
					$content .= themeblvd_get_post_content( $tab['content']['page'], 'page' );
					break;

				case 'raw':
					if ( ! empty( $tab['content']['raw_format'] ) ) {
						$content .= themeblvd_get_content( $tab['content']['raw'] );
					} else {
						$content .= do_shortcode( themeblvd_kses( $tab['content']['raw'] ) );
					}
					break;

			}

			$content .= '</div><!-- .tab-pane (end) -->';

			$i++;

		}
	}

	$content .= '</div><!-- .tab-content (end) -->';

	// Build final output.
	$output  = '<div class="' . $classes . '">';

	$output .= $navigation;

	$output .= $content;

	$output .= '</div><!-- .tb-tabs (end) -->';

	/**
	 * Filters the final HTML output for a tabs
	 * block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type array       $tabs   Tabs from sortable option type.
	 *     @type string      $nav    Style of navigation, `tabs` or `pills`.
	 *     @type string      $style  Style of unit, `framed` or `open`; filter on style choices with `themeblvd_tab_styles`.
	 *     @type string|bool $height Whether forced shared height of all columns.
	 * }
	 * @param string $id Unique ID for this tabs block.
	 */
	return apply_filters( 'themeblvd_tabs', $output, $id );

}

/**
 * Display a tabs block.
 *
 * @since @@name-framework 2.0.0
 *
 * @param string $id   Unique ID for this tabs block.
 * @param array  $args Block arguments, see themeblvd_get_tabs() docs.
 */
function themeblvd_tabs( $id, $args ) {

	echo themeblvd_get_tabs( $id, $args );

}

/**
 * Get a team member block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type array  $image   Image attributes of team member.
 *     @type string $name    Name of team member, like `John Smith`.
 *     @type string $tagline Tagline for team member, like `Founder and CEO`.
 *     @type array  $icons   Social icons formatted for themeblvd_contact_bar().
 *     @type string $text    Description for team member.
 * }
 * @return string $output Content output
 */
function themeblvd_get_team_member( $args ) {

	$args = wp_parse_args( $args, array(
		'image'   => array(),
		'name'    => '',
		'tagline' => '',
		'icons'   => array(),
		'text'    => '',
	));

	$output = '<div class="tb-team-member">';

	if ( ! empty( $args['image']['src'] ) ) {

		$output .= '<div class="member-image">';

		$output .= sprintf(
			'<img src="%s" alt="%s" />',
			esc_url( $args['image']['src'] ),
			esc_attr( $args['image']['title'] )
		);

		$output .= '<div class="member-info clearfix">';

		$output .= '<div class="member-identity">';

		if ( $args['name'] ) {

			$output .= sprintf( '<h5 class="member-name">%s</h5>', themeblvd_kses( $args['name'] ) );

		}

		if ( $args['tagline'] ) {

			$output .= sprintf( '<span class="member-tagline">%s</span>', themeblvd_kses( $args['tagline'] ) );

		}

		$output .= '</div><!-- .member-identity (end) -->';

		if ( $args['icons'] ) {

			$output .= themeblvd_get_contact_bar( $args['icons'], array(
				'style'   => 'light',
				'class'   => 'member-contact',
				'tooltip' => false,
			));

		}

		$output .= '</div><!-- .member-info (end) -->';

		$output .= '</div><!-- .member-image (end) -->';
	}

	if ( $args['text'] ) {

		$output .= sprintf(
			'<div class="member-text entry-content">%s</div><!-- .member-text (end) -->',
			themeblvd_get_content( $args['text'] )
		);

	}

	$output .= '</div><!-- .tb-team-member (end) -->';

	/**
	 * Filters the final HTML output for a team
	 * member block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type array  $image   Image attributes of team member.
	 *     @type string $name    Name of team member, like `John Smith`.
	 *     @type string $tagline Tagline for team member, like `Founder and CEO`.
	 *     @type array  $icons   Social icons formatted for themeblvd_contact_bar().
	 *     @type string $text    Description for team member.
	 * }
	 */
	return apply_filters( 'themeblvd_team_member', $output, $args );

}

/**
 * Display a team member block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_team_member() docs.
 */
function themeblvd_team_member( $args ) {

	echo themeblvd_get_team_member( $args );

}

/**
 * Get a testimonial block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string $text        Text for testimonial.
 *     @type string $name        Name of person giving testimonial, like `John Smith`.
 *     @type string $tagline     Tagline or position of person giving testimonial, like `Founder and CEO`.
 *     @type string $company     Company of person giving testimonial, like `Google`.
 *     @type string $company_url Company URL of person giving testimonial, like `http://google.com`.
 *     @type array  $image       Image attributes for person giving testimonial.
 *     @type string $display     How to display the testimonial, `showcase` or `standard`.
 * }
 * @return string $output  Final HTML output for block.
 */
function themeblvd_get_testimonial( $args ) {

	$args = wp_parse_args( $args, array(
		'text'        => '',
		'name'        => '',
		'tagline'     => '',
		'company'     => '',
		'company_url' => '',
		'image'       => array(),
		'display'     => 'standard',
	));

	$class = sprintf( 'tb-testimonial %s', $args['display'] );

	if ( ! empty( $args['image']['src'] ) ) {

		$class .= ' has-image';

	}

	if ( $args['name'] && ( $args['tagline'] || $args['company'] ) ) {

		$class .= ' tag-two-line';

	} elseif ( $args['name'] || $args['tagline'] || $args['company'] ) {

		$class .= ' tag-one-line';

	}

	$output = '<div class="' . $class . '">';

	$output .= sprintf(
		'<div class="testimonial-text entry-content"><span class="arrow"></span>%s</div>',
		themeblvd_get_content( $args['text'] )
	);

	if ( $args['name'] ) {

		if ( 'showcase' === $args['display'] ) {

			$output .= themeblvd_get_divider();

		}

		$output .= '<div class="author">';

		if ( empty( $args['image']['src'] ) ) {

			$output .= sprintf(
				'<span class="author-image"><i class="%s"></i></span>',
				themeblvd_get_icon_class( 'user' )
			);

		} else {

			$output .= sprintf(
				'<span class="author-image"><img src="%s" alt="%s" /></span>',
				esc_url( $args['image']['src'] ),
				esc_attr( $args['image']['title'] )
			);

		}

		$output .= sprintf(
			'<h5 class="author-name">%s</h5>',
			themeblvd_kses( $args['name'] )
		);

		if ( $args['tagline'] || $args['company'] ) {

			$tagline = '';

			if ( $args['tagline'] ) {

				$tagline .= esc_html( $args['tagline'] );

			}

			if ( $args['company'] ) {

				$company = esc_html( $args['company'] );

				if ( $args['company_url'] ) {

					$company = sprintf(
						'<a href="%1$s" title="%2$s" target="_blank">%2$s</a>',
						esc_url( $args['company_url'] ),
						$company
					);

				}

				if ( $tagline ) {

					$tagline .= ', ' . $company;

				} else {

					$tagline .= $company;

				}
			}

			$output .= sprintf( '<span class="author-tagline">%s</span>', $tagline );

		}

		$output .= '</div><!-- .author (end) -->';

	}

	$output .= '</div><!-- .tb-testimonial (end) -->';

	/**
	 * Filters the final HTML output for a contextual
	 * alert block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string $text        Text for testimonial.
	 *     @type string $name        Name of person giving testimonial, like `John Smith`.
	 *     @type string $tagline     Tagline or position of person giving testimonial, like `Founder and CEO`.
	 *     @type string $company     Company of person giving testimonial, like `Google`.
	 *     @type string $company_url Company URL of person giving testimonial, like `http://google.com`.
	 *     @type array  $image       Image attributes for person giving testimonial.
	 *     @type string $display     How to display the testimonial, `showcase` or `standard`.
	 * }
	 */
	return apply_filters( 'themeblvd_testimonial', $output, $args );

}


/**
 * Display a testimonial block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_testimonial() docs.
 */
function themeblvd_testimonial( $args ) {

	echo themeblvd_get_testimonial( $args );

}

/**
 * Get a testimonial slider block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type array       $testimonials All testimonials, each formatted for themeblvd_get_testimonial().
 *     @type string      $title        Optional. Title for block.
 *     @type string      $fx           Slide transition effect, `slide` or `fade`.
 *     @type string      $timeout      Seconds inbetween slider transitions; use `0` for no auto-rotation.
 *     @type string|bool $nav          Whether to show slider navigation.
 *     @type string      $display      How to display the slider, `showcase` or `standard`.
 * }
 * @return string $output  Final HTML output for block.
 */
function themeblvd_get_testimonial_slider( $args ) {

	$args = wp_parse_args( $args, array(
		'testimonials' => array(),
		'title'        => '',
		'fx'           => '',
		'timeout'      => '3',
		'nav'          => '1',
		'display'      => 'standard',
	));

	if ( ! $args['fx'] ) {

		if ( 'showcase' === $args['display'] ) {

			$args['fx'] = 'slide';

		} else {

			$args['fx'] = 'fade';

		}
	}

	$class = sprintf(
		'tb-testimonial-slider tb-block-slider %s flexslider',
		$args['display']
	);

	if ( $args['nav'] ) {

		$class .= ' has-nav';

	}

	if ( $args['title'] && 'standard' === $args['display'] ) {

		$class .= ' has-title';

	}

	$output = sprintf(
		'<div class="%s" data-timeout="%s" data-nav="%s" data-fx="%s">',
		$class,
		esc_attr( $args['timeout'] ),
		esc_attr( $args['nav'] ),
		esc_attr( $args['fx'] )
	);

	if ( $args['title'] && 'standard' === $args['display'] ) {

		$output .= sprintf( '<h3 class="title">%s</h3>', $args['title'] );

	}

	$output .= '<div class="tb-testimonial-slider-inner tb-block-slider-inner slider-inner">';

	$output .= themeblvd_get_loader();

	if ( $args['nav'] ) {

		$controls = array();

		if ( 'showcase' === $args['display'] ) {

			$controls['color'] = 'trans';

		}

		$output .= themeblvd_get_slider_controls( $controls );
	}

	if ( $args['testimonials'] ) {

		$output .= '<ul class="slides">';

		foreach ( $args['testimonials'] as $testimonial ) {

			$testimonial['display'] = $args['display'];

			$output .= sprintf(
				'<li class="slide">%s</li>',
				themeblvd_get_testimonial( $testimonial )
			);

		}

		$output .= '</ul>';

	}

	$output .= '</div><!-- .tb-testimonial-slider-inner (end) -->';

	$output .= '</div><!-- .tb-testimonial-slider (end) -->';

	/**
	 * Filters the final HTML output for a testimonial
	 * slider block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type array       $testimonials All testimonials, each formatted for themeblvd_get_testimonial().
	 *     @type string      $title        Optional. Title for block.
	 *     @type string      $fx           Slide transition effect, `slide` or `fade`.
	 *     @type string      $timeout      Seconds inbetween slider transitions; use `0` for no auto-rotation.
	 *     @type string|bool $nav          Whether to show slider navigation.
	 *     @type string      $display      How to display the slider, `showcase` or `standard`.
	 * }
	 */
	return apply_filters( 'themeblvd_testimonial_slider', $output, $args );

}

/**
 * Display a testimonial slider block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_testimonial_slider() docs.
 */
function themeblvd_testimonial_slider( $args ) {

	echo themeblvd_get_testimonial_slider( $args );

}


/**
 * Get an individual toggle.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string      $title   Title of toggle.
 *     @type string      $content Hidden content of toggle.
 *     @type string|bool $wpautop Whether to apply wpautop on content.
 *     @type string|bool $open    Whether toggle is initially open.
 *     @type string      $class   Optional. Any additional CSS classes to add.
 *     @type bool        $last    Whether this is the last toggle of a group; this only applies if it's not part of an accordion.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_toggle( $args ) {

	$args = wp_parse_args( $args, array(
		'title'   => '',
		'content' => '',
		'wpautop' => 'true',
		'open'    => 'false',
		'class'   => '',
		'last'    => false,
	));

	/**
	 * Filters the color of the toggle.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string       Color class.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string      $title   Title of toggle.
	 *     @type string      $content Hidden content of toggle.
	 *     @type string|bool $wpautop Whether to apply wpautop on content.
	 *     @type string|bool $open    Whether toggle is initially open.
	 *     @type string      $class   Optional. Any additional CSS classes to add.
	 *     @type bool        $last    Whether this is the last toggle of a group; this only applies if it's not part of an accordion.
	 * }
	 */
	$color = apply_filters( 'themeblvd_toggle_color', 'default', $args );

	$class = sprintf( 'tb-toggle panel panel-%s', $color );

	if ( $args['class'] ) {

		$class .= ' ' . $args['class'];

	}

	if ( $args['last'] ) {

		$class .= ' panel-last';

	}

	// wpautop formatting?
	if ( 'true' == $args['wpautop'] || '1' == $args['wpautop'] ) {

		$content = themeblvd_get_content( $args['content'] );

	} else {

		$content = do_shortcode( themeblvd_kses( $args['content'] ) );

	}

	// Is toggle open?
	$state = 'panel-collapse collapse';

	$icon = 'plus-circle';

	if ( 'true' == $args['open'] || '1' == $args['open'] ) {

		$state .= ' in';

		$icon = 'minus-circle';

	}

	// Individual toggle ID (NOT the Accordion ID)
	$toggle_id = uniqid( 'toggle_' . rand() );

	// Bootstrap 3 output.
	$output = '
        <div class="' . esc_attr( $class ) . '">
            <div class="panel-heading">
                <a class="panel-title" data-toggle="collapse" data-parent="" href="#' . $toggle_id . '">
                    <i class="' . themeblvd_get_icon_class( $icon ) . ' switch-me"></i>' . themeblvd_kses( $args['title'] ) . '
                </a>
            </div><!-- .panel-heading (end) -->
            <div id="' . $toggle_id . '" class="' . $state . '">
                <div class="panel-body entry-content">
                    ' . $content . '
                </div><!-- .panel-body (end) -->
            </div><!-- .panel-collapse (end) -->
        </div><!-- .panel (end) -->';

	/**
	 * Filters the final HTML output for an individual
	 * toggle.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string      $title   Title of toggle.
	 *     @type string      $content Hidden content of toggle.
	 *     @type string|bool $wpautop Whether to apply wpautop on content.
	 *     @type string|bool $open    Whether toggle is initially open.
	 *     @type string      $class   Optional. Any additional CSS classes to add.
	 *     @type bool        $last    Whether this is the last toggle of a group; this only applies if it's not part of an accordion.
	 * }
	 */
	return apply_filters( 'themeblvd_toggle', $output, $args );

}

/**
 * Display an individual toggle.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_toggle() docs.
 */
function themeblvd_toggle( $args ) {

	echo themeblvd_get_toggle( $args );

}

/**
 * Get a toggles block.
 *
 * This consists of a set of a toggles using
 * themeblvd_get_toggle() to return the output
 * for each individual toggle.
 *
 * @since @@name-framework 2.5.0
 *
 * @param string $id Unique ID for toggles block.
 * @param array  $args {
 *     Block arguments.
 *
 *     @type array       $toggles   Array of toggles, each formatted for themeblvd_get_toggle().
 *     @type string|bool $accordion Whether to treat set as an accordion (i.e. only one toggle open at a time).
 * }
 * @return string $output HTML output for toggles
 */
function themeblvd_get_toggles( $id, $args ) {

	$args = wp_parse_args( $args, array(
		'toggles'   => array(),
		'accordion' => 'false',
	));

	$accordion = false;

	if ( 'true' == $args['accordion'] || '1' == $args['accordion'] ) {

		$accordion = true;

	}

	$counter = 1;

	$total = count( $args['toggles'] );

	$output = '';

	if ( $args['toggles'] && is_array( $args['toggles'] ) ) {

		foreach ( $args['toggles'] as $toggle ) {

			if ( ! $accordion && $counter == $total ) {

				$toggle['last'] = true;

			}

			$output .= themeblvd_get_toggle( $toggle );

			$counter++;

		}
	}

	if ( $accordion ) {

		$output = sprintf(
			'<div id="accordion_%s" class="tb-accordion panel-group">%s</div>',
			$id,
			$output
		);

	}

	/**
	 * Filters the final HTML output for a toggles
	 * block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type array       $toggles   Array of toggles, each formatted for themeblvd_get_toggle().
	 *     @type string|bool $accordion Whether to treat set as an accordion (i.e. only one toggle open at a time).
	 * }
	 * @param string $id Unique ID for toggles block.
	 */
	return apply_filters( 'themeblvd_toggles', $output, $args, $id );

}

/**
 * Display set of toggles.
 *
 * @since @@name-framework 2.5.0
 *
 * @param string $id   Unique ID for toggles block.
 * @param array  $args Block arguments, see themeblvd_get_toggles() docs.
 */
function themeblvd_toggles( $id, $args ) {

	echo themeblvd_get_toggles( $id, $args );

}

/**
 * Get a pricing table block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $cols Data for each column of pricing table.
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string $currency           Currency symbol, like `$`.
 *     @type string $currency_placement Placement of currency symbol, relative to dollar amount, `before` or `after`.
 * }
 * @return string $output  Final HTML output for block.
 */
function themeblvd_get_pricing_table( $cols, $args ) {

	$args = wp_parse_args( $args, array(
		'currency'           => '$',
		'currency_placement' => 'before',
	));

	if ( $cols ) {

		$grid_class = themeblvd_grid_class( count( $cols ) );

		$has_popout = false;

		foreach ( $cols as $col ) {

			if ( ! empty( $col['popout'] ) ) {

				$has_popout = true;

				break;

			}
		}

		$class = 'tb-pricing-table row row-inner';

		if ( $has_popout ) {

			$class .= ' has-popout';

		}

		$output = sprintf( '<div class="%s">', $class );

		foreach ( $cols as $col ) {

			$col = wp_parse_args( $col, array(
				'highlight'          => 'default',
				'popout'             => '0',
				'title'              => '',
				'title_subline'      => '',
				'price'              => '',
				'price_subline'      => '',
				'features'           => '',
				'button'             => '0',
				'button_color'       => 'default',
				'button_custom'      => array(),
				'button_text'        => '',
				'button_url'         => '',
				'button_size'        => '',
				'button_icon_before' => '',
				'button_icon_after'  => '',
			));

			$class = sprintf( 'col %s price-column', $grid_class );

			if ( 'none' === $col['highlight'] ) {

				$class .= ' no-highlight';

			} else {

				if ( in_array( $col['highlight'], array( 'primary', 'info', 'success', 'warning', 'danger' ) ) ) {

					$class .= ' bg-' . $col['highlight'];

				} else {

					$class .= ' ' . $col['highlight'];

				}
			}

			if ( $col['popout'] ) {

				$class .= ' popout';

				if ( $col['title_subline'] ) {

					$class .= ' has-title-subline';

				} else {

					$class .= ' no-title-subline';

				}
			}

			if ( $col['price_subline'] ) {

				$class .= ' has-price-subline';

			} else {

				$class .= ' no-price-subline';

			}

			if ( $col['button'] ) {

				$class .= ' has-button';

			}

			$output .= sprintf( '<div class="%s">', $class );

			$output .= '<div class="title-wrap">';

			$output .= sprintf(
				'<span class="title">%s</span>',
				themeblvd_kses( $col['title'] )
			);

			if ( $col['popout'] && $col['title_subline'] ) {

				$output .= sprintf(
					'<span class="title-subline">%s</span>',
					themeblvd_kses( $col['title_subline'] )
				);

			}

			$output .= '</div>';

			$output .= sprintf(
				'<div class="price-wrap currency-%s">',
				esc_attr( $args['currency_placement'] )
			);

			$output .= '<span class="price">';

			if ( $args['currency'] && 'before' === $args['currency_placement'] ) {

				$output .= sprintf(
					'<span class="currency">%s</span>',
					themeblvd_kses( $args['currency'] )
				);

			}

			$output .= themeblvd_kses( $col['price'] );

			if ( $args['currency'] && 'after' === $args['currency_placement'] ) {

				$output .= sprintf(
					'<span class="currency">%s</span>',
					themeblvd_kses( $args['currency'] )
				);

			}

			$output .= '</span>';

			if ( $col['price_subline'] ) {

				$output .= sprintf(
					'<span class="price-subline">%s</span>',
					themeblvd_kses( $col['price_subline'] )
				);

			}

			$output .= '</div>';

			$output .= '<div class="features">';

			if ( $col['features'] ) {

				$features = explode( "\n", str_replace( "\n\n", "\n", $col['features'] ) );

				$output .= '<ul class="list-unstyled feature-list">';

				foreach ( $features as $feature ) {

					$output .= sprintf(
						'<li class="feature">%s</li>',
						do_shortcode( themeblvd_kses( $feature ) )
					);

				}

				$output .= '</ul>';

			}

			$output .= '</div>';

			$button = '';

			if ( $col['button'] ) {

				$addon = '';

				if ( 'custom' === $col['button_color'] ) {

					if ( $col['button_custom']['include_bg'] ) {

						$bg = $col['button_custom']['bg'];

					} else {

						$bg = 'transparent';

					}

					if ( $col['button_custom']['include_border'] ) {

						$border = $col['button_custom']['border'];

					} else {

						$border = 'transparent';

					}

					$addon = sprintf(
						'style="background-color: %1$s; border-color: %2$s; color: %3$s;" data-bg="%1$s" data-bg-hover="%4$s" data-text="%3$s" data-text-hover="%5$s"',
						$bg,
						$border,
						$col['button_custom']['text'],
						$col['button_custom']['bg_hover'],
						$col['button_custom']['text_hover']
					);

				}

				$output .= '<div class="button-wrap">';

				/**
				 * Filters the target attribute of a button in a
				 * pricing table column.
				 *
				 * @since @@name-framework 2.5.0
				 *
				 * @param string Link target attribute like `_self` or `_blank`.
				 */
				$target = apply_filters( 'themeblvd_pricing_table_btn_target', '_self' );

				$output .= themeblvd_button(
					$col['button_text'],
					$col['button_url'],
					$col['button_color'],
					$target,
					$col['button_size'],
					null,
					null,
					$col['button_icon_before'],
					$col['button_icon_after'],
					$addon
				);

				$output .= '</div>';

			}

			$output .= '</div><!-- .col.price-column (end) -->';

		}

		$output .= '</div><!-- .tb-pricing-table (end) -->';

	}

	/**
	 * Filters the final HTML output for a pricing
	 * table block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $cols Data for each column of pricing table.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string $currency           Currency symbol, like `$`.
	 *     @type string $currency_placement Placement of currency symbol, relative to dollar amount, `before` or `after`.
	 * }
	 */
	return apply_filters( 'themeblvd_pricing_table', $output, $cols, $args );

}

/**
 * Display a pricing table block.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $cols Data for each column of pricing table.
 * @param array $args Block arguments, see themeblvd_get_pricing_table() docs.
 */
function themeblvd_pricing_table( $cols, $args ) {

	echo themeblvd_get_pricing_table( $cols, $args );

}
