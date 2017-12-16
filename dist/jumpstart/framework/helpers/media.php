<?php
/**
 * Helpers: Media
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */

/**
 * Show theme's image thumb sizes when inserting
 * an image in a post or page.
 *
 * This function is filtered onto:
 * 1. `image_size_names_choose` - 10
 *
 * @since Theme_Blvd 2.1.0
 *
 * @return array Framework's image sizes.
 */
function themeblvd_image_size_names_choose( $sizes ) {

	// Get image sizes for framework that were registered.
	$tb_raw_sizes = themeblvd_get_image_sizes();

	// Format sizes
	$tb_sizes = array();

	foreach ( $tb_raw_sizes as $id => $atts ) {

		$tb_sizes[ $id ] = esc_html( $atts['name'] );

	}

	/**
	 * Filters the crop sizes being added by the
	 * theme to options in the WordPress admin
	 * allowing user to select a crop size.
	 *
	 * @since Theme_Blvd 2.1.0
	 *
	 * @param array Framework image sizes.
	 */
	$tb_sizes = apply_filters( 'themeblvd_choose_sizes', $tb_sizes );

	return array_merge( $sizes, $tb_sizes );

}

/**
 * Determine if a URL is a valid lightbox URL.
 *
 * If this is not a valid lightbox URL, return
 * FALSE, but if not return an icon type that
 * can be associated with it.
 *
 * @since Theme_Blvd 2.3.0
 *
 * @param  string $url  Link URL.
 * @return string $icon Type of URL, `image` or `video`, or FALSE if URL is not lightbox compatible.
 */
function themeblvd_is_lightbox_url( $url ) {

	$icon = false;

	if ( $url ) {

		if ( false !== strpos( $url, 'vimeo.com' ) ) { // Vimeo video?

			$icon = 'video';

		} elseif ( false !== strpos( $url, 'youtube.com' ) ) { // YouTube video?

			$icon = 'video';

		} elseif ( false !== strpos( $url, 'maps.google.com' ) ) { // Google map?

			$icon = 'image';

		} elseif ( 0 === strpos( $url, '#' ) ) { // Inline content?

			$icon = 'image';

		} else { // Image?

			$parsed_url = parse_url( $url );

			if ( ! empty( $parsed_url['path'] ) ) {

				$type = wp_check_filetype( $parsed_url['path'] );

				if ( 'image' === substr( $type['type'], 0, 5 ) ) {

					$icon = 'image';

				}
			}
		}
	}

	/**
	 * Filters whether a URL is a valid lightbox URL.
	 *
	 * If it is a valid lightbox URL, a type will be
	 * returned.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param string $icon Type of URL, `image` or `video`, or FALSE if URL is not lightbox compatible.
	 * @param string $url  Link URL that was originally checked.
	 */
	return apply_filters( 'themeblvd_is_lightbox_url', $icon, $url );

}

/**
 * Find all instances of image URL strings within
 * a text block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $str    Text string to search for images within.
 * @return array  $images All found image URLs in text block.
 */
function themeblvd_get_img_urls( $str ) {

	$protocols = array( 'http://', 'https://' );

	$extentions = array( '.gif', '.jpeg', '.jpg', '.png' );

	$images = array();

	foreach ( $protocols as $protocol ) {

		foreach ( $extentions as $ext ) {

			$pattern = sprintf(
				'/%s(.*?)%s/',
				preg_quote( $protocol, '/' ),
				preg_quote( $ext, '/' )
			);

			preg_match_all( $pattern, $str, $img );

			$images = array_merge( $images, $img[0] );

		}
	}

	return $images;

}

/**
 * Get all image sizes to register with WordPress.
 *
 * By having this in a separate function, hopefully
 * it can be extended upon better. If any plugin or
 * other feature of the framework requires these
 * image sizes, they can grab 'em.
 *
 * @since Theme_Blvd 2.2.0
 */
function themeblvd_get_image_sizes( $size = '' ) {

	/**
	 * Filters the maximum content width values
	 * the theme sets to $GLOBALS['content_width'].
	 *
	 * @since Theme_Blvd 2.0.0
	 *
	 * @param int Width value. Default 1200.
	 */
	$max = apply_filters( 'themeblvd_content_width', 1200 );

	$GLOBALS['content_width'] = $max;

	$sizes = array(
		'tb_x_large' => array(
			'name'   => __( 'Theme Blvd XL', 'jumpstart' ),
			'width'  => $max,
			'height' => 9999,
			'crop'   => false,
		),
		'tb_large' => array(
			'name'   => __( 'Theme Blvd L', 'jumpstart' ),
			'width'  => 800,
			'height' => 9999,
			'crop'   => false,
		),
		'tb_medium' => array(
			'name'   => __( 'Theme Blvd M', 'jumpstart' ),
			'width'  => 500,
			'height' => 9999,
			'crop'   => false,
		),
		'tb_thumb'  => array(
			'name'   => __( 'Theme Blvd Thumbnail', 'jumpstart' ),
			'width'  => 200,
			'height' => 200,
			'crop'   => true,
		),
		'tb_grid' => array(
			'name'   => __( 'Theme Blvd 16:9', 'jumpstart' ), // 16:9
			'width'  => 640,
			'height' => 360,
			'crop'   => true,
		),
		'tb_square_x_large' => array(
			'name'   => __( 'Theme Blvd XL Square', 'jumpstart' ),
			'width'  => 1200,
			'height' => 1200,
			'crop'   => true,
		),
		'tb_square_large' => array(
			'name'   => __( 'Theme Blvd L Square', 'jumpstart' ),
			'width'  => 960,
			'height' => 960,
			'crop'   => true,
		),
		'tb_square_medium' => array(
			'name'   => __( 'Theme Blvd M Square', 'jumpstart' ),
			'width'  => 800,
			'height' => 800,
			'crop'   => true,
		),
		'tb_square_small' => array(
			'name'   => __( 'Theme Blvd S Square', 'jumpstart' ),
			'width'  => 500,
			'height' => 500,
			'crop'   => true,
		),
		'slider-x-large' => array(
			'name'   => __( 'Slider Extra Large', 'jumpstart' ),
			'width'  => 1400,
			'height' => 525,
			'crop'   => true,
		),
		'slider-large' => array(
			'name'   => __( 'Slider Large', 'jumpstart' ),
			'width'  => 960,
			'height' => 360,
			'crop'   => true,
		),
		'slider-medium' => array(
			'name'   => __( 'Slider Medium', 'jumpstart' ),
			'width'  => 800,
			'height' => 300,
			'crop'   => true,
		),
		'slider-staged' => array(
			'name'   => __( 'Slider Staged', 'jumpstart' ),
			'width'  => 690,
			'height' => 415,
			'crop'   => true,
		),
	);

	/**
	 * Filters the image sizes the theme registers
	 * with WordPress.
	 *
	 * @since Theme_Blvd 2.0.0
	 *
	 * @param array $sizes Image sizes.
	 */
	$sizes = apply_filters( 'themeblvd_image_sizes', $sizes );

	if ( $size ) {

		if ( isset( $sizes[ $size ] ) ) {

			return $sizes[ $size ];

		} else {

			return false;

		}
	} else {

		return $sizes;

	}

}

/**
 * Register image sizes.
 *
 * This function is hooked to:
 * 1. `after_setup_theme` - 10
 *
 * @since Theme_Blvd 2.1.0
 */
function themeblvd_add_image_sizes() {

	$sizes = themeblvd_get_image_sizes();

	foreach ( $sizes as $size => $atts ) {

		add_image_size( $size, $atts['width'], $atts['height'], $atts['crop'] );

	}

}

/**
 * Remove constraints on image URLs generated in
 * the admin.
 *
 * This function isn't by defualt filtered on, but it
 * can be applied and removed as needed onto WordPress's
 * `editor_max_image_size`.
 *
 * @since Theme_Blvd 2.5.0
 */
function themeblvd_editor_max_image_size() {

	return array( 1200, 1200 );

}

/**
 * Find the next smallest image size.
 *
 * If we're retrieving an crop size that is in one of our
 * stacks, and it doesn't exist, the default WP action would
 * be to just return the full-size image. Instead, we can
 * use this function to try and find the next crop size in
 * the stack.
 *
 * This process helps to downsize the quality of an image,
 * but still maintain the same aspect ratio, as each stack shares
 * a common downsize pattern.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array  $attachment Attachment from original call to get_attachment_image_src().
 * @param string $id         Original attachment ID.
 * @param string $crop       Crop size to check for, and then downsize from.
 * @param string $scaled     Scaled down image URL.
 */
function themeblvd_image_downsize( $attachment, $attachmen_id, $crop = 'tb_x_large' ) {

	if ( ! empty( $attachment[3] ) ) {

		return $attachment;

	}

	$crop_atts = themeblvd_get_image_sizes( $crop );

	// Catches if original image uploaded matches crop size exactly.
	if ( $attachment[1] == $crop_atts['width'] ) {

		if ( $crop_atts['height'] == $attachment[2] || 9999 == $crop_atts['height'] ) {

			return $attachment;

		}
	}

	$stacks = array(
		array( 'tb_x_large', 'tb_large', 'tb_medium' ),
		array( 'tb_square_x_large', 'tb_square_large', 'tb_square_medium' ),
		array( 'slider-x-large', 'slider-large', 'slider-medium' ),
	);

	$scaled = $attachment;

	foreach ( $stacks as $stack ) {

		if ( in_array( $crop, $stack ) ) {

			$key = array_search( $crop, $stack );

			if ( $key > 0 ) {

				for ( $i = 0; $i <= $key; $i++ ) {

					unset( $stack[ $i ] );

				}
			}

			if ( $stack ) {

				foreach ( $stack as $size ) {

					$scaled = wp_get_attachment_image_src( $attachmen_id, $size );

					if ( $scaled[3] ) {

						break;

					}

					$crop_atts = themeblvd_get_image_sizes( $size );

					if ( $crop_atts['width'] == $scaled[1] ) {

						if ( $crop_atts['height'] == $scaled[2] || 9999 == $crop_atts['height'] ) {

							break;

						}
					}
				}
			}

			if ( empty( $scaled[3] ) ) {

				$scaled = $attachment;

			}

			break;

		}
	}

	return $scaled;

}

/**
 * Modify output for WordPress's `[caption]`
 * shortcode.
 *
 * The main purpose of this is to add lightbox
 * functionality when WordPress's `[caption]`
 * shortcode is used.
 *
 * This function is filtered onto:
 * 1. `img_caption_shortcode` - 10
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param  string $output  The caption output. Default empty.
 * @param  array  $attr    Attributes of the caption shortcode.
 * @param  string $content The image element, possibly wrapped in a hyperlink.
 * @return string $output  Modified HTML output for `[caption]` shortcode.
 */
function themeblvd_img_caption_shortcode( $output, $attr, $content ) {

	$atts = shortcode_atts( array(
		'id'      => '',
		'align'   => 'alignnone',
		'width'   => '',
		'caption' => '',
		'class'   => '',
	), $attr, 'caption' );

	$atts['width'] = (int) $atts['width'];

	if ( $atts['width'] < 1 || empty( $atts['caption'] ) ) {

		return $content;

	}

	if ( ! empty( $atts['id'] ) ) {

		$atts['id'] = 'id="' . esc_attr( $atts['id'] ) . '" ';

	}

	$class = sprintf( 'wp-caption %s %s', $atts['align'], trim( $atts['class'] ) );

	$url = themeblvd_get_content_url( $content );

	if ( ! empty( $url[1] ) ) { // Image is linked.

		$lightbox = themeblvd_is_lightbox_url( $url[1] );

		if ( $lightbox ) {

			// Strip link.
			$content = str_replace( array( '<a href="' . $url[1] . '">', '</a>' ), '', $content );

			// Re-wrap image with link.
			$content = themeblvd_get_link_to_lightbox( array(
				'item'  => $content,
				'link'  => $url[1],
				'class' => 'tb-thumb-link ' . $lightbox,
			));

		} else {

			if ( strpos( $url[1], get_site_url() ) !== false ) {

				$content = str_replace( '<a', '<a class="tb-thumb-link post"', $content );

			} else {

				$content = str_replace( '<a', '<a class="tb-thumb-link external" target="_blank"', $content );

			}
		}
	}

	$output  = sprintf(
		'<figure %s style="width: %spx;" class="%s">',
		esc_attr( $atts['id'] ),
		esc_attr( $atts['width'] ),
		esc_attr( $class )
	);

	$output .= do_shortcode( $content );

	$output .= sprintf(
		'<figcaption class="wp-caption-text">%s</figcaption>',
		$atts['caption']
	);

	$output .= '</figure>';

	return $output;

}

/**
 * Make embedded videos response.
 *
 * This function is filtered onto:
 * 1. `oembed_result` - 10
 * 2. `embed_oembed_html` - 10
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param string  $input  Constructed HTML for embed.
 * @param string  $url    Original embed URL.
 * @return string $output Modified $input.
 */
function themeblvd_oembed_result( $input, $url ) {

	// If this is a tweet, keep on movin' fella.
	if ( false !== strpos( $url, 'twitter.com' ) ) {

		return $input;

	}

	/*
	 * If this is a link to external WP post
	 * (introduced in WP 4.4), abort.
	 */
	if ( false !== strpos( $input, 'wp-embedded-content' ) ) {

		return $input;

	}

	/*
	 * Check for duplicate usage.
	 *
	 * Since the framework applies this filter in two
	 * spots, we must first check if the filter has
	 * been applied or not.
	 *
	 * The reason for this is because WP has issues
	 * with caching the oembed result, and oembed_result
	 * doesn't always get applied when it's supposed to.
	 */
	if ( false !== strpos( $input, 'themeblvd-video-wrapper' ) ) {

		return $input;

	}

	// Apply YouTube wmode fix.
	if ( false !== strpos( $url, 'youtube' ) || false !== strpos( $url, 'youtu.be' ) ) {

		if ( false === strpos( $input, 'wmode=opaque' ) ) {

			$input = str_replace( 'feature=oembed', 'feature=oembed&wmode=opaque', $input );

		}
	}

	// Builder final HTML output.

	$output = "<div class=\"themeblvd-video-wrapper\">\n";

	$output .= "\t<div class=\"video-inner\">\n";

	$output .= $input;

	$output .= "\t</div><!-- .video-inner -->\n";

	$output .= "</div><!-- .themeblvd-video-wrapper -->\n";

	return $output;

}

/**
 * Add 100% width to <audio> tag of WP's built-in
 * audio player to make it responsive.
 *
 * This function is filtered onto:
 * 1. `wp_audio_shortcode` - 10
 *
 * @since Theme_Blvd 2.2.1
 *
 * @param  string $input <audio> tag HTML output.
 * @return string        Modified $input.
 */
function themeblvd_audio_shortcode( $input ) {

	return str_replace( '<audio', '<audio width="100%"', $input );

}

/**
 * Whether to apply the parallax effect to a
 * background.
 *
 * This function looks at a standard set of
 * display settings from a custom layout to
 * determine if a parallax effect is going to
 * be applied.
 *
 * @since Theme_Blvd 2.5.1
 *
 * @param  array $display Display settings from custom layout.
 * @return bool           Whether to display with parallax effect.
 */
function themeblvd_do_parallax( $display ) {

	if ( empty( $display['bg_type'] ) ) {

		return false;

	}

	if ( 'image' === $display['bg_type'] ) {

		if ( isset( $display['bg_image']['attachment'] ) && 'parallax' === $display['bg_image']['attachment'] ) {

			if ( ! empty( $display['bg_image']['image'] ) ) {

				return true;

			}
		}
	} elseif ( 'texture' === $display['bg_type'] ) {

		if ( ! empty( $display['apply_bg_texture_parallax'] ) ) {

			return true;

		}
	}

	return false;

}

/**
 * Whether to apply the background shade effect to
 * a background.
 *
 * This function looks at a standard set of
 * display settings from a custom layout to
 * determine if a background shade is going
 * to be applied.
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param  array $display Display settings from custom layout.
 * @return bool           Whether to display with background shade.
 */
function themeblvd_do_bg_shade( $display ) {

	if ( empty( $display['bg_type'] ) ) {

		return false;

	}

	if ( ! in_array( $display['bg_type'], array( 'image', 'slideshow', 'video' ) ) ) {

		return false;

	}

	if ( empty( $display['apply_bg_shade'] ) ) {

		return false;

	}

	return true;

}

/**
 * Get the source of a background video.
 *
 * This function provides a quick way for to
 * determine if a background video is one of
 * our supported video types, self-hosted HTML5,
 * Vimeo, or YouTube.
 *
 * @since Theme_Blvd 2.6.0
 *
 * @param array $video {
 *     Video arguments.
 *
 *     @type string $id       Video ID, should be unique.
 *     @type string $mp4      Video URL, can be .mp4, .ogg, .webm, Vimeo web URL, or YouTube web URL.
 *     @type string $ratio    Aspect ratior, like `16:9`.
 *     @type string $fallback Fallback image URL, like `http://mysite.com/image.jpg`.
 * }
 * @return string $source Video source, `html5`, `youtube` or `vimeo`.
 */
function themeblvd_get_video_source( $video ) {

	$source = false;

	$filetype = wp_check_filetype( $video );

	if ( ! empty( $filetype['ext'] ) ) {

		$source = 'html5';

	} elseif ( false !== strpos( $video, 'youtube.com/watch' ) || false !== strpos( $video, 'youtu.be/' ) ) {

		$source = 'youtube';

	} elseif ( false !== strpos( $video, 'vimeo.com' ) ) {

		$source = 'vimeo';

	}

	return $source;

}

/**
 * Get array of framework icons.
 *
 * @since Theme_Blvd 2.3.0
 *
 * @param  string $type  Type of icons to retrieve, `solid` or `brands`.
 * @return array  $icons Array of icons.
 */
function themeblvd_get_icons( $type = 'solid' ) {

	$icons = get_transient( 'themeblvd_' . get_template() . '_icons_' . $type );

	if ( ! $icons ) {

		$icons = array();

		/**
		 * Filters the URL to the data file used
		 * determine which icons are included
		 * from FontAwesome.
		 *
		 * @since Theme_Blvd 2.7.0
		 *
		 * @param string File URL, like `http://my-site.com/file.json`.
		 */
		$file = apply_filters( 'themeblvd_icon_data_file_url', TB_FRAMEWORK_DIRECTORY . '/admin/assets/data/icons.json' );

		if ( file_exists( $file ) ) {

			$data = file_get_contents( $file );

			if ( $data ) {

				$data = json_decode( $data );

				foreach ( $data as $key => $value ) {

					if ( in_array( $type, $value->styles ) ) {

						$icons[ $key ] = array(
							'name'  => $key,
							'label' => $value->label,
							'terms' => $value->search->terms,
						);

					}
				}
			}

			/*
			 * We'll store our result in a 1-day transient.
			 */
			set_transient( 'themeblvd_' . get_template() . '_icons_' . $type, $icons, '86400' );

		}
	}

	/**
	 * Filters the array of icons that the user can
	 * select from in the icon browser.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param array $icons All icons found from fontawesome.css.
	 */
	return apply_filters( 'themeblvd_icons', $icons, $type );

}

/**
 * Get icon types.
 *
 * @since Theme_Blvd 2.7.0
 *
 * @return array $types Icon types.
 */
function themeblvd_get_icon_types() {

	$types = array(
		'fas' => 'solid',
		'fab' => 'brands',
	);

	/**
	 * Filters the array of icons that the user can
	 * select from in the icon browser.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param array $types Icon types.
	 */
	return apply_filters( 'themeblvd_icon_types', $types );

}
