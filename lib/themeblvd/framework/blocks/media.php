<?php
/**
 * Frontend media blocks.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     @@name-package
 */

/**
 * Display the featured image of a post, taking into
 * account framework image linking.
 *
 * @since 2.0.0 (re-written in 2.5.0)
 *
 * @param string $size Crop size of the thumbnail (optional)
 * @return string $args Any additional arguments (optional)
 */
function themeblvd_get_post_thumbnail( $size = '', $args = array() ) {

	global $post;

	if ( ! $size ) {
		$size = themeblvd_get_att('crop');
	}

	if ( ! $size ) {
		$size = 'tb_x_large'; // Try to avoid ever pulling "full" in case user uploaded rediculously large image
	}

	$defaults = apply_filters('themeblvd_post_thumbnail_args', array(
		'attachment_id'	=> get_post_thumbnail_id($post->ID),
		'location'		=> themeblvd_get_att('location'),
		'placeholder'	=> false,
		'frame'			=> apply_filters('themeblvd_featured_thumb_frame', false),
		'link'			=> null, // FALSE to force no link, post, or thumbnail
		'img_before'	=> null,
		'img_after'		=> null,
		'srcset'		=> true
	));
	$args = wp_parse_args( $args, $defaults );

	if ( ! $args['attachment_id'] ) {

		if ( $args['placeholder'] ) {
			if ( $args['link'] === true ) {
				$output = themeblvd_get_media_placeholder( array( 'link' => get_permalink(), 'title' => get_the_title() ) );
			} else {
				$output = themeblvd_get_media_placeholder();
			}
		} else {
			$output = '';
		}

		if ( $output ) {

			if ( $args['img_before'] ) {
				$output = $args['img_before'].$output;
			}

			if ( $args['img_after'] ) {
				$output .= $args['img_after'];
			}
		}

		return apply_filters( 'themeblvd_post_thumbnail', $output, $args, $size );
	}

	// Determine link
	if ( $args['link'] === false ) {

		$link = false;

	} else {

		if ( $args['location'] == 'single' ) {

			$link = get_post_meta( $post->ID, '_tb_thumb_link_single', true );

			if ( $link == 'yes' ) {
				$link = get_post_meta( $post->ID, '_tb_thumb_link', true );
			}

		} else {
			$link = get_post_meta( $post->ID, '_tb_thumb_link', true );
		}

		if ( $link == 'inactive' || $link == 'no' || ! $link ) {
			$link = false;
		}

	}

	if ( ! $link && ( $args['link'] == 'post' || $args['link'] == 'thumbnail' ) ) {
		$link = $args['link'];
	}

	// Initial image without link
	$class = '';

	if ( ! $link ) {

		$class = 'featured-image';

		if ( $args['frame'] ) {
			$class .= ' thumbnail';
		}
	}

	$class = apply_filters('themeblvd_post_thumbnail_img_class', $class, $post->ID, $args['attachment_id']);

	if ( ! $args['srcset'] ) {
		add_filter('wp_calculate_image_sizes', 'themeblvd_return_false');
		add_filter('wp_calculate_image_srcset', 'themeblvd_return_false');
	}

	// First attempt to get the actual post thumbnail for the
	// post to make sure proper filtering is present for other
	// plugins.
	$output = get_the_post_thumbnail( $post->ID, $size, array('class' => $class) );

	// If no actual post thumbnail, we can work off a manually feed in attachment ID
	if ( ! $output && $args['attachment_id'] ) {
		$output = wp_get_attachment_image( $args['attachment_id'], $size, false, array('class' => $class) );
	}

	if ( ! $args['srcset'] ) {
		remove_filter('wp_calculate_image_sizes', 'themeblvd_return_false');
		remove_filter('wp_calculate_image_srcset', 'themeblvd_return_false');
	}

	// Apply content before/after image, if necessary
	if ( $output ) {

		if ( $args['img_before'] ) {
			$output = $args['img_before'].$output;
		}

		if ( $args['img_after'] ) {
			$output .= $args['img_after'];
		}

	}

	// Wrap image in link, if necessary
	if ( $link ) {

		$link = themeblvd_get_post_thumbnail_link( $post->ID, $args['attachment_id'], $link );

		if ( $args['frame'] ) {
			$link['class'] .= ' thumbnail';
		}

		if ( $link['target'] == 'lightbox' ) {

			$lightbox = apply_filters( 'themeblvd_featured_image_lightbox_args', array(
				'item'	=> $output,
				'link'	=> $link['href'],
				'class'	=> $link['class'],
				'title'	=> $link['title']
			), $post->ID, $args['attachment_id'] );

			$output = themeblvd_get_link_to_lightbox($lightbox);

		} else {
			$output = sprintf( '<a href="%s" title="%s" class="%s" target="%s">%s</a>', esc_url($link['href']), esc_attr($link['title']), esc_attr($link['class']), esc_attr($link['target']), $output );
		}

	}

	return apply_filters( 'themeblvd_post_thumbnail', $output, $args, $size );
}

/**
 * Get gallery slider
 *
 * @since 2.3.0
 *
 * @param string $gallery Optional gallery shortcode usage like [gallery ids="1,2,3,4"]
 * @param array $args Options for slider
 * @return string $output Final HTML to output
 */
function themeblvd_get_gallery_slider( $gallery = '', $args = array() ) {

	$defaults = apply_filters( 'themeblvd_gallery_slider_args', array(
		'size'			=> '',											// Crop size for images
		'carousel'		=> themeblvd_get_option('gallery_carousel'),	// Whether to use variable width carousel or default bootstrap carousel
		'thumb_size'	=> 'smallest', 									// Size of nav thumbnail images - small, smaller, smallest or custom int
		'interval'		=> 'false',										// Milliseconds between transitions, false for no auto rotation (PHP string to output as JS boolean)
		'pause'			=> 'true',										// Whether to pause on hover (PHP string to output as JS boolean)
		'wrap'			=> 'true',										// Whether sliders continues auto rotate after first pass (PHP string to output as JS boolean)
		'nav_standard'	=> false,										// Whether to show standard nav indicator dots
		'nav_arrows'	=> true,										// Whether to show standard nav arrows
		'arrows'		=> 'standard',									// Nav arrow style - standard, mini
		'nav_thumbs'	=> false,										// Whether to show nav thumbnails (added by Theme Blvd framework)
		'title'			=> false,										// Display title of attachments on slides
		'caption'		=> false, 										// Display captions of attachments on slides
		'dark_text'		=> false,										// Whether to use dark text for title/descriptions/standard nav, use when images are light
		'frame'			=> apply_filters('themeblvd_featured_thumb_frame', false)
	));
	$args = wp_parse_args( $args, $defaults );

	$post_id = get_the_ID();

	// Did user pass in a gallery shortcode?
	if ( $gallery ) {
		$content = $gallery;
	} else {
		$content = get_the_content(); // Would need to be within the loop
	}

	// Did user insert a gallery like [gallery ids="1,2,3,4"]
	// via $gallery param or anywhere in post?
	$attachments = array();
	$pattern = get_shortcode_regex();

	if ( preg_match( "/$pattern/s", $content, $match ) && 'gallery' == $match[2] ) {

		$atts = shortcode_parse_atts( $match[3] );

		if ( ! empty( $atts['ids'] ) ) {
			$query = array(
				'post_type'		=> 'attachment',
				'post__in' 		=> explode( ',', $atts['ids'] ),
				'orderby'   	=> 'post__in',
				'numberposts'	=> -1
			);
			$attachments = get_posts($query);
		}
	}

	// Slider needs 2 or more attachments.
	if ( count( $attachments ) <= 1 ) {
		if ( is_user_logged_in() ) {
			return sprintf( '<div class="alert alert-warning"><p>%s</p></div>', esc_html__('Oops! Couldn\'t find a gallery with one or more image attachments. Make sure to insert a gallery into the body of the post. Example: [gallery ids="1,2,3"]', '@@text-domain') );
		} else {
			return;
		}
	}

	// Prepare images
	$images = array();

	if ( $args['size'] ) {

		$crop = $args['size'];

	} else {

		$crop = 'slider-x-large';

		if ( $args['carousel'] ) {
			$crop = 'full';
		}

		$crop = apply_filters('themeblvd_gallery_slider_default_crop', $crop);
	}

	foreach ( $attachments as $attachment ) {

		$title = '';

		if ( $args['title'] ) {
			$title = $attachment->post_title;
		}

		$caption = '';

		if ( $args['caption'] ) {
			$caption = $attachment->post_excerpt;
		}

		$img = wp_get_attachment_image_src( $attachment->ID, $crop );
		$img = themeblvd_image_downsize( $img, $attachment->ID, $crop );

		$thumb = wp_get_attachment_image_src( $attachment->ID, 'tb_thumb' );

		$images[] = array(
			'crop'		=> $crop,
			'alt'		=> $attachment->post_title,
			'src'		=> $img[0],
			'width'		=> $img[1],
			'height'	=> $img[2],
			'thumb'		=> $thumb[0],
			'title'		=> $title,
			'desc'		=> $caption,
		);

	}

	if ( $args['frame'] ) {
		$args['class'] = 'thumbnail';
	}

	// Prepare slider options
	$options = $args;
	$options['crop'] = $crop;
	unset( $options['size'], $options['title'], $options['caption'] );

	// Get our slider
	$output = themeblvd_get_simple_slider( $images, $options );

	return apply_filters( 'themeblvd_gallery_slider', $output, $post_id, $attachments, $args );
}

/**
 * Gallery slider
 *
 * @since 2.3.0
 *
 * @param string $gallery Optional gallery shortcode usage like [gallery ids="1,2,3,4"]
 * @param array $args Options for slider
 * @param string $size Image crop size for attachment images
 */
function themeblvd_gallery_slider( $gallery = '', $args = array() ) {
	echo themeblvd_get_gallery_slider( $gallery, $args );
}

/**
 * Gallery slider with some preset arguments for
 * smaller situations.
 *
 * @since 2.3.0
 *
 * @param string $gallery Optional gallery shortcode usage like [gallery ids="1,2,3,4"]
 * @param array $args Options for slider
 * @param string $size Image crop size for attachment images
 */
function themeblvd_mini_gallery_slider( $gallery = '', $args = array() ) {

	$defaults = apply_filters( 'themeblvd_mini_gallery_slider_args', array(
		'class'			=> 'mini',
		'carousel'		=> false,
		'arrows'		=> 'mini',
		'nav_thumbs'	=> false
	));
	$args = wp_parse_args( $args, $defaults );

	echo themeblvd_get_gallery_slider( $gallery, $args );
}

/**
 * Get bootstrap carousel slider from passed
 * in image URL's, no DB queries involved.
 *
 * @since 2.5.0
 *
 * @param array $images Images for simple slider
 * @param array $args Options for simple slider
 * @return string $output Final slider HTML to output
 */
function themeblvd_get_simple_slider( $images, $args = array() ) {

	$output = '';

	$defaults = array(
		'id'					=> uniqid( 'slider_' . rand() ),	// Unique ID for the slider
		'crop'					=> 'slider-large',					// Crop size for images
		'carousel'				=> '0',								// Whether to use variable width carousel or default bootstrap carousel
		'interval'				=> '5',								// How fast to auto rotate betweens slides
		'pause'					=> '1',								// Whether to pause slider on hover
		'wrap'					=> '1',								// When slider auto-rotates, whether it continuously cycles
		'nav_standard'			=> '1',								// Whether to show standard navigation dots
		'nav_arrows'			=> '1',								// Whether to show navigation arrows
		'arrows'				=> 'standard',						// Nav arrow style - standard, mini
		'nav_thumbs'			=> '0',								// Whether to show navigation image thumbnails
		'shade'					=> '0',								// Whether entire image is shaded
		'link'					=> '1',								// Whether linked slides have animated hover overlay effect
		'thumb_size'			=> 'smaller',						// Size of thumbnails - small, smaller, smallest or custom int
		'dark_text'				=> '0',								// Whether to use dark text for title/descriptions/standard nav, use when images are light
		'caption_bg'			=> '0',								// Whether to add BG color to caption
		'caption_bg_color'		=> '#000000',						// Caption BG color
		'caption_bg_opacity'	=> '0.5',							// Caption BG color opacity
		'cover'					=> '0',								// popout: Whether images horizontal space 100%
		'position'				=> 'middle center',					// popout: If cover is true, how slider images are positioned (i.e. with background-position)
		'height_desktop'		=> '400',							// popout: If cover is true, slider height for desktop viewport
		'height_tablet'			=> '300',							// popout: If cover is true, slider height for tablet viewport
		'height_mobile'			=> '200',							// popout: If cover is true, slider height for mobile viewport
		'class'					=> ''								// Any CSS classes to add
	);

	$args = apply_filters( 'themeblvd_simple_slider_args', wp_parse_args( $args, $defaults ) );

	// Make sure $images array is setup properly
	foreach ( $images as $img_id => $img ) {
		$images[$img_id] = wp_parse_args( $img, array(
			'crop'			=> $args['crop'],
			'id'			=> 0,
			'alt'			=> '',
			'src'			=> '',
			'width'			=> 0,
			'height'		=> 0,
			'thumb'			=> '',
			'title'			=> '',
			'desc'			=> '',
			'desc_wpautop'	=> '1',
			'link'			=> '',
			'link_url'		=> '',
			'addon'			=> ''
		));
	}

	// Slider auto rotate speed
	$interval = $args['interval'];

	if ( $interval && $interval !== 'false' && intval( $interval ) < 100 ) {
		$interval .= '000'; // User has inputted seconds, so we convert to milliseconds
	}

	// Whether to pause on hover
	$pause = '';

	if ( $args['pause'] ) {
		$pause = 'hover';
	}

	$class = 'tb-simple-slider carousel slide';

	if ( $args['carousel'] ) {
		$class .= ' tb-gallery-carousel';
	}

	if ( $args['class'] ) {
		$class .= ' '.$args['class'];
	}

	if ( $args['dark_text'] ) {
		$class .= ' dark-text';
	}

	if ( $args['caption_bg'] ) {
		$class .= ' has-caption-bg';
	}

	if ( $args['cover'] ) {
		$class .= ' cover';
	}

	if ( $args['nav_arrows'] ) {
		$class .= ' nav-arrows-'.$args['arrows'];
	}

	if ( $args['nav_standard'] ) {
		$class .= ' has-nav-dots';
	}

	if ( $args['nav_thumbs'] ) {
		$class .= ' has-nav-thumbs';
	}

	// Inline styles for popout slider with background images that cover full width
	if ( $args['cover'] ) {

		$style = "\n<style>\n";

		$style .= sprintf( "#%s .img {\n", esc_attr($args['id']) );
		$style .= sprintf( "\tbackground-position: %s;\n", esc_attr($args['position']) );
		$style .= sprintf( "\theight: %spx;\n", esc_attr($args['height_desktop']) );
		$style .= "}\n";

		$style .= "@media (max-width: 991px) {\n";
		$style .= sprintf( "\t#%s .img {\n", esc_attr($args['id']) );
		$style .= sprintf( "\t\theight: %spx;\n", esc_attr($args['height_tablet']) );
		$style .= "\t}\n";
		$style .= "}\n";

		$style .= "@media (max-width: 767px) {\n";
		$style .= sprintf( "\t#%s .img {\n", esc_attr($args['id']) );
		$style .= sprintf( "\t\theight: %spx;\n", esc_attr($args['height_mobile']) );
		$style .= "\t}\n";
		$style .= "}\n";

		// Pre-load BG images
		if ( count( $images ) > 1 ) {

			$style .= "body:after {\n";
			$style .= "\tdisplay: none;\n";

			$load = '';

			foreach ( $images as $img ) {

				$img_src = $img['src'];

				if ( is_ssl() ) {
					$img_src = str_replace('http://', 'https://', $img_src);
				}

				$load .= sprintf( "url(%s) ", esc_url($img_src) );

			}

			$style .= sprintf("\tcontent: %s;\n", trim($load));
			$style .= "}\n";
		}

		$style .= "</style>\n";

		$output .= apply_filters( 'themeblvd_simple_slider_cover_style', $style, $args, $images );

	}

	$output .= sprintf( '<div id="%s" class="%s" data-ride="carousel" data-interval="%s" data-pause="%s" data-wrap="%s">', esc_attr($args['id']), esc_attr($class), esc_attr($interval), esc_attr($pause), esc_attr($args['wrap']) );

	if ( $args['carousel'] ) {
		$output = str_replace(' data-ride="carousel"', '', $output); // disable bootstrap carousel, because we're using owl carousel
	}

	$output .= '<div class="carousel-control-wrap">';

	// Standard nav indicators
	if ( $args['nav_standard'] ) {

		$output .= '<ol class="carousel-indicators">';

		$counter = 0;

		foreach ( $images as $img ) {

			$class = '';

			if ( $counter == 0 ) {
				$class = 'active';
			}

			$output .= sprintf( '<li data-target="#%s" data-slide-to="%s" class="%s"></li>', esc_attr($args['id']), $counter, $class );

			$counter++;
		}

		$output .= '</ol>';
	}

	// Slides
	if ( $args['carousel'] ) {
		$output .= themeblvd_get_loader();
		$output .= '<div class="carousel-inner owl-carousel">';
	} else {
		$output .= '<div class="carousel-inner">';
	}

	$counter = 0;

	if ( count( $images ) > 1 ) {
		foreach ( $images as $img ) {

			$class = 'item';

			if ( $img['link'] && $args['thumb_link'] ) {

				$class .= ' tb-thumb-link';

				if ( $img['link'] == 'image' || $img['link'] == 'video' ) {
					$class .= ' '.$img['link'];
				} else if ( $img['link'] == '_self' ) {
					$class .= ' post';
				} else if ( $img['link'] == '_blank' ) {
					$class .= ' external';
				}
			}

			if ( $counter == 0 ) {
				$class .= ' active';
			}

			$output .= sprintf( '<div class="%s">', $class );

			if ( $img['link'] ) {

				if ( $img['link'] == 'image' || $img['link'] == 'video' ) {

					$output .= themeblvd_get_link_to_lightbox(array(
						'item' 		=> '',
						'link' 		=> $img['link_url'],
						'title' 	=> $img['alt'],
						'class'		=> 'slide-link'
					));

				} else {

					$output .= sprintf( '<a href="%s" title="%s" class="slide-link" target="%s"></a>', esc_url($img['link_url']), esc_attr($img['alt']), esc_attr($img['link']));

				}

			}

			if ( $args['shade'] ) {
				$output .= '<div class="bg-shade"></div>';
			}

			$img_src = esc_url($img['src']);

			if ( is_ssl() ) {
				$img_src = str_replace('http://', 'https://', $img_src);
			}

			if ( $args['cover'] ) {

				$output .= sprintf( '<div class="img" style="background-image: url(%s);"></div>', $img_src );

			} else {

				$output .= sprintf( '<img src="%s" alt="%s"', $img_src, esc_attr($img['alt']) );

				if ( $img['width'] ) {

					$output .= sprintf( ' width="%s"', $img['width'] );

				}

				if ( $img['height'] ) {

					$output .= sprintf( ' height="%s"', $img['height'] );

				}

				$output .= ' />';

			}

			if ( $img['title'] || $img['desc'] ) {

				$caption_style = '';

				if ( $args['caption_bg'] ) {
					$caption_style = sprintf( 'background-color: %s; background-color: %s;', $args['caption_bg_color'], themeblvd_get_rgb( $args['caption_bg_color'], $args['caption_bg_opacity'] ) );
				}

				$output .= '<div class="carousel-caption" style="'.esc_attr($caption_style).'">';

				if ( $img['title'] ) {
					$output .= sprintf( '<h3>%s</h3>', themeblvd_kses($img['title']) );
				}

				if ( $img['desc'] ) {
					if ( $img['desc_wpautop'] ) {
						$output .= wpautop( themeblvd_kses($img['desc']) );
					} else {
						$output .= themeblvd_kses($img['desc']);
					}
				}

				$output .= '</div><!-- .carousel-caption (end) -->';

			}

			if ( $img['addon'] ) {
				$output .= $img['addon'];
			}

			$output .= '</div><!-- .item (end) -->';

			$counter++;
		}
	}

	$output .= '</div><!-- .carousel-inner (end) -->';

	// Nav arrows
	if ( $args['nav_arrows'] ) {
		$output .= themeblvd_get_slider_controls( array( 'carousel' => $args['id'], 'color' => 'trans' ) );
	}

	$output .= '</div><!-- .carousel-control-wrap (end) -->';

	// Thumbnail navigation
	if ( $args['nav_thumbs'] ) {

		$size = $args['thumb_size'];

		switch ( $size ) {
			case 'small' :
				$size = '130';
				break;
			case 'smaller' :
				$size = '70';
				break;
			case 'smallest' :
				$size = '45';
		}

		$output .= '<ol class="carousel-thumb-nav list-unstyled clearfix">';

		$counter = 0;

		foreach ( $images as $img ) {

			$class = '';

			if ( $counter == 0 ) {
				$class = 'active';
			}

			$output .= sprintf( '<li data-target="#%s" data-slide-to="%s" class="%s">', esc_attr($args['id']), $counter, $class );
			$output .= sprintf( '<img src="%s" alt="%s" width="%s" />', esc_url($img['thumb']), esc_attr($img['alt']), $size );
			$output .= '</li>';

			$counter++;
		}

		$output .= "</ol>\n";
	}

	$output .= '</div><!-- .carousel (end) -->';

	return apply_filters( 'themeblvd_simple_slider', $output, $args );
}

/**
 * Display bootstrap coursel slider.
 *
 * @since 2.5.0
 *
 * @param array $args All options for simple slider
 */
function themeblvd_simple_slider( $images, $args = array() ) {
	echo themeblvd_get_simple_slider( $images, $args );
}

/**
 * Get image display from a set of options
 *
 * @since 2.5.0
 *
 * @param array $img_atts Attributes for image file
 * @param array $args Additional options for image
 * @return string $output Final image HTML to output
 */
function themeblvd_get_image( $img_atts, $args = array() ) {

	// Attributes for the image
	$defaults = array(
		'id'		=> 0,
		'src'		=> '',
		'full'		=> '',
		'title'		=> '',
		'crop'		=> ''
	);
	$img_atts = wp_parse_args( $img_atts, $defaults );

	// Additional options for image display
	$defaults = array(
		'link' 		=> 'none',
    	'link_url'	=> '',
    	'frame' 	=> '0',
    	'align'		=> 'none',
    	'title'		=> '',
    	'width'		=> '',
    	'class'		=> ''
	);
	$args = wp_parse_args( $args, $defaults );

	// Is this image going to be linked?
	$has_link = false;

	if ( $args['link'] != 'none' ) {
		$has_link = true;
	}

	// Does this image have a display width?
	$width = esc_attr($args['width']);

	if ( $width && strpos($args['width'], '%') === false && strpos($args['width'], 'px') === false ) {
		$width .= 'px';
	}

	// Image class
	$img_class = 'wp-image-'.$img_atts['id'];

	if ( ! $has_link ) {
		if ( in_array( $args['align'], array('left', 'center', 'right') ) ) {
			$img_class .= ' align'.$args['align'];
		} else {
			$img_class .= ' alignnone';
		}
	}

	if ( $args['frame'] && ! $has_link ) {
		$img_class .= ' thumbnail';
	}

	if ( ! $has_link && ! empty( $args['class'] ) ) {
		$img_class .= ' '.$args['class'];
	}

	$img_src = $img_atts['src'];

	if ( is_ssl() ) {
		$img_src = str_replace('http://', 'https://', $img_src);
	}

	if ( $args['title'] ) {
		$title = $args['title'];
	} else {
		$title = $img_atts['title'];
	}

	// Setup intial image
	$img = sprintf( '<img src="%s" alt="%s" class="%s" ', esc_url($img_src), esc_attr($title), esc_attr($img_class) );

	if ( $width ) {
		$img .= sprintf( 'style="width:%s;" ', $width );
	}

	$img .= '/>';

	// Start output
	$output = $img;

	// Wrap image in link
	if ( $has_link ) {

		$anchor_classes = 'tb-thumb-link';

		if ( $args['frame'] ) {
			$anchor_classes .= ' thumbnail';
		}

		if ( in_array( $args['align'], array('left', 'center', 'right') ) ) {
			$anchor_classes .= ' align'.$args['align'];
		}

		if ( ! empty( $args['class'] ) ) {
			$anchor_classes .= ' '.$args['class'];
		}

		if ( $args['link'] == 'image' || $args['link'] == 'full' || $args['link'] == 'video' ) {

			if ( $args['link'] == 'image' || $args['link'] == 'full' ) {
				$anchor_classes .= ' image';
			} else {
				$anchor_classes .= ' video';
			}

			if ( $args['link'] == 'full' ) {
				$link = $img_atts['full'];
			} else {
				$link = $args['link_url'];
			}

			$props = array();

			$output = themeblvd_get_link_to_lightbox(array(
				'item' 		=> $output,
				'link' 		=> $link,
				'title' 	=> $title,
				'props'		=> $props,
				'class' 	=> $anchor_classes
			));

		} else {

			if ( $args['link'] == '_self' ) {
				$anchor_classes .= ' post';
			} else if ( $args['link'] == '_blank' ) {
				$anchor_classes .= ' external';
			}

			$style = '';

			$output = sprintf( '<a href="%s" class="%s" title="%s" target="%s" style="%s">%s</a>', esc_url($args['link_url']), esc_attr($anchor_classes), esc_attr($title), esc_attr($args['link']), esc_attr($style), $output );

		}
	}

	// Wrap image in max-width div?
	if ( $width ) {
		$output = sprintf('<div class="display-width %s" style="max-width:%s;">%s</div>', esc_attr($args['align']), $width, $output);
	}

	return apply_filters( 'themeblvd_image', $output, $img, $img_atts, $args );
}

/**
 * Display image.
 *
 * @since 2.5.0
 *
 * @param array $args Options for from "Image" element
 */
function themeblvd_image( $img_atts, $args = array() ) {
	echo themeblvd_get_image( $img_atts, $args );
}

/**
 * Get video
 *
 * @since 2.5.0
 *
 * @param string $video_url URL to video, file URL or oEmbed compatible link
 * @param array $args Any extra arguments, currently not being used
 * @return string $output Final image HTML to output
 */
function themeblvd_get_video( $video_url, $args = array() ) {
	return apply_filters( 'themeblvd_video', themeblvd_get_content( $video_url ), $video_url, $args );
}

/**
 * Display video
 *
 * @since 2.5.0
 *
 * @param string $video_url URL to video, file URL or oEmbed compatible link
 * @param array $args Any extra arguments, currently not being used
 * @return string $output Final image HTML to output
 */
function themeblvd_video( $video_url, $args = array() ) {
	echo themeblvd_get_video( $video_url, $args );
}
