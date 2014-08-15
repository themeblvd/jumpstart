<?php
/**
 * Get Image Sizes
 *
 * By having this in a separate function, hopefully
 * it can be extended upon better. If any plugin or
 * other feature of the framework requires these
 * image sizes, they can grab 'em.
 *
 * @since 2.2.0
 */
function themeblvd_get_image_sizes( $size = '' ) {

	// Max content Width
	$GLOBALS['content_width'] = $max = apply_filters( 'themeblvd_content_width', 1200 );

	// Crop sizes
	$sizes = array(
		'tb_x_large' => array(
			'name' 		=> __( 'Theme Blvd XL', 'themeblvd' ),
			'width' 	=> $max,
			'height' 	=> 9999,
			'crop' 		=> false
		),
		'tb_large' => array(
			'name' 		=> __( 'Theme Blvd L', 'themeblvd' ),
			'width' 	=> 800,
			'height'	=> 9999,
			'crop' 		=> false
		),
		'tb_medium' => array(
			'name' 		=> __( 'Theme Blvd M', 'themeblvd' ),
			'width' 	=> 500,
			'height'	=> 9999,
			'crop' 		=> false
		),
		'tb_thumb'	=> array(
			'name' 		=> __( 'Theme Blvd Thumbnail', 'themeblvd' ),
			'width' 	=> 200,
			'height' 	=> 200,
			'crop' 		=> true
		),
		'tb_grid' => array(
			'name' 		=> __( 'Theme Blvd Grid', 'themeblvd' ), // 16:9
			'width' 	=> 640,
			'height' 	=> 360,
			'crop' 		=> true
		),
		'slider-x-large' => array(
			'name' 		=> __( 'Slider Extra Large', 'themeblvd' ),
			'width' 	=> 1200,
			'height' 	=> 450,
			'crop' 		=> true
		),
		'slider-large' => array(
			'name' 		=> __( 'Slider Large', 'themeblvd' ),
			'width' 	=> 960,
			'height' 	=> 360,
			'crop' 		=> true
		),
		'slider-medium' => array(
			'name' 		=> __( 'Slider Medium', 'themeblvd' ),
			'width' 	=> 800,
			'height' 	=> 300,
			'crop' 		=> true
		),
		'slider-staged' => array(
			'name' 		=> __( 'Slider Staged', 'themeblvd' ),
			'width' 	=> 690,
			'height' 	=> 415,
			'crop' 		=> true
		)
	);

	$sizes = apply_filters( 'themeblvd_image_sizes', $sizes );

	if ( $size && isset( $sizes[$size] ) ) {
		return $sizes[$size];
	} else {
		return $sizes;
	}
}

/**
 * Register Image Sizes
 *
 * @since 2.1.0
 */
function themeblvd_add_image_sizes() {

	// Get image sizes
	$sizes = themeblvd_get_image_sizes();

	// Add image sizes
	foreach ( $sizes as $size => $atts ) {
		add_image_size( $size, $atts['width'], $atts['height'], $atts['crop'] );
	}

}

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

	$defaults = array(
		'attachment_id'	=> get_post_thumbnail_id($post->ID),
		'location'		=> themeblvd_get_att('location'),
		'placeholder'	=> themeblvd_get_att('placeholder'),
		'frame'			=> apply_filters('themeblvd_featured_thumb_frame', false),
		'link'			=> null // FALSE to force no link, post, or thumbnail
	);
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

		return apply_filters( 'themeblvd_post_thumbnail', $output, $args );
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

		if ( $link == 'inactive' || ! $link ) {
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

	// First attempt to get the actual post thumbnail for the
	// post to make sure proper filtering is present for other
	// plugins.
	$output = get_the_post_thumbnail( $post->ID, $size, array('class' => $class) );

	// If no actual post thumbnail, we can work off a manually feed in attachment ID
	if ( ! $output && $args['attachment_id'] ) {
		$output = wp_get_attachment_image( $args['attachment_id'], $size, false, array('class' => $class) );
	}

	// Shall this image be wrapped in a link?
	if ( $link ) {

		$class = 'featured-image tb-thumb-link';

		if ( $args['frame'] ) {
			$class .= ' thumbnail';
		}

		$parts = array();

		switch ( $link ) {

			case 'post' :
				$parts['href'] = get_permalink($post->ID);
				$parts['title'] = get_the_title();
				$parts['target'] = '_self';
				$class .= ' post';
				break;

			case 'thumbnail' :
				$enlarge = wp_get_attachment_image_src( $args['attachment_id'], 'tb_x_large' );
				$parts['href'] = $enlarge[0];
				$parts['title'] = get_the_title($args['attachment_id']);
				$parts['target'] = 'lightbox';
				$class .= ' image';
				break;

			case 'image' :
				$parts['href'] = get_post_meta( $post->ID, '_tb_image_link', true );
				$parts['title'] = get_the_title();
				$parts['target'] = 'lightbox';
				$class .= ' image';
				break;

			case 'video' :
				$parts['href'] = get_post_meta( $post->ID, '_tb_video_link', true );
				$parts['title'] = get_the_title($args['attachment_id']);
				$parts['target'] = 'lightbox';
				$class .= ' video';
				break;

			case 'external' :
				$parts['href'] = get_post_meta( $post->ID, '_tb_external_link', true );
				$parts['title'] = get_the_title();
				$parts['target'] = get_post_meta( $post->ID, '_tb_external_link_target', true );
				$class .= ' external';
				break;
		}

		$class = apply_filters('themeblvd_post_thumbnail_a_class', $class, $post->ID, $args['attachment_id']);

		if ( $parts['target'] == 'lightbox' ) {

			$lightbox = apply_filters( 'themeblvd_featured_image_lightbox_args', array(
				'item'	=> $output,
				'link'	=> $parts['href'],
				'class'	=> $class,
				'title'	=> $parts['title']
			), $post->ID, $args['attachment_id'] );

			$output = themeblvd_get_link_to_lightbox($lightbox);

		} else {
			$output = sprintf( '<a href="%s" title="%s" class="%s" target="%s">%s</a>', $parts['href'], $parts['title'], $class, $parts['target'], $output );
		}

	}

	return apply_filters( 'themeblvd_post_thumbnail', $output, $args );
}

/**
 * Get thumbnail size based on passed in size and/or
 * framework options.
 *
 * @since 2.3.0
 *
 * @param $size string Optional current size of image
 * @param $location string Optional location for thumbnail
 * @param $sidebar_layout string Optional current sidebar layout
 * @return $size Size after it's been formatted
 */
function themeblvd_get_thumbnail_size( $max, $context = 'full' ) {

	$size = 'full';
	$sizes = themeblvd_get_image_sizes();

	if ( $context == 'full' ) {
		$keys = array('tb_x_large', 'tb_large', 'tb_medium');
	}

	if ( ! empty($keys) ) {
		foreach ( $keys as $key ) {
			if ( $sizes[$key]['width'] <= $max ) {
				$size = $key;
			}
		}
	}

	return $size;

}

/**
 * Get a placeholder for media when not present
 *
 * @since 2.5.0
 */
function themeblvd_get_media_placeholder( $args = array() ) {

	$defaults = array(
		'frame'			=> apply_filters('themeblvd_featured_thumb_frame', false),
		'format'		=> get_post_format(),
		'type'			=> themeblvd_get_att('placeholder'),
		'width'			=> themeblvd_get_att('crop_w'),
		'height'		=> themeblvd_get_att('crop_h'),
		'link'			=> '',		// URL to link icon to
		'title'			=> ''		// If link, title of link
	);
	$args = wp_parse_args( $args, $defaults );

	$class = 'placeholder-wrap featured-image';

	if ( $args['frame'] ) {
		$class .= ' thumbnail';
	}

	$h = 0;

	if ( intval($args['height']) > 0 && intval($args['width']) > 0 ) {
		$h = ( intval($args['height']) / intval($args['width']) ) * 100;
	}

	$h = strval($h).'%';

	$icon = sprintf( '<i class="fa fa-%s"></i>',  themeblvd_get_format_icon($args['format'], true) );

	if ( $args['link'] ) {
		$icon = sprintf( '<a href="%s" title="%s">%s</a>', $args['link'], $args['title'], $icon );
	}

	$output = sprintf( '<div class="%s"><div class="placeholder" style="padding-bottom:%s">%s</div></div>', $class, $h, $icon );

	return apply_filters('themeblvd_media_placeholder', $output, $args );
}

/**
 * Add wrapper around embedded videos to allow for respnsive videos.
 *
 * @since 2.0.0
 */
function themeblvd_oembed_result( $input, $url ) {

	// If this is a tweet, keep on movin' fella. @todo Create filterable list of items to skip other than twitter.com
	if ( strpos( $url, 'twitter.com' ) ) {
		return $input;
	}

	// Since the framework applies this filter in two
	// spots, we must first check if the filter has
	// been applied or not. The reason for this is
	// because WP has issues with caching the oembed
	// result, and oembed_result doesn't always get
	// applied when it's supposed to.
	if ( strpos( $input, 'themeblvd-video-wrapper' ) ) {
		return $input;
	}

	// Apply YouTube wmode fix
	if ( strpos( $url, 'youtube' ) || strpos( $url, 'youtu.be' ) ) {
		if ( ! strpos( $input, 'wmode=transparent' ) ) {
			$input = str_replace('feature=oembed', 'feature=oembed&wmode=transparent', $input);
		}
	}

	// Wrap output
	$output  = '<div class="themeblvd-video-wrapper">';
	$output .= '<div class="video-inner">';
	$output .= $input;
	$output .= '</div>';
	$output .= '</div>';

	return $output;
}

/**
 * Add 100% width to <audio> tag of WP's built-in
 * audio player to make it responsive.
 *
 * @since 2.2.1
 *
 * @param string $html HTML for output to be filtered
 */
function themeblvd_audio_shortcode( $html ) {
	return str_replace( '<audio', '<audio width="100%"', $html );
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
		'size'			=> '',					// Crop size for images
		'thumb_size'	=> 'smallest', 			// Size of nav thumbnail images - small, smaller, smallest or custom int
		'interval'		=> 'false',				// Milliseconds between transitions, false for no auto rotation (PHP string to output as JS boolean)
		'pause'			=> 'true',				// Whether to pause on hover (PHP string to output as JS boolean)
		'wrap'			=> 'true',				// Whether sliders continues auto rotate after first pass (PHP string to output as JS boolean)
		'nav_standard'	=> false,				// Whether to show standard nav indicator dots
		'nav_arrows'	=> true,				// Whether to show standard nav arrows
		'arrows'		=> 'standard',			// Nav arrow style - standard, mini
		'nav_thumbs'	=> true,				// Whether to show nav thumbnails (added by Theme Blvd framework)
		'title'			=> false,				// Display title of attachments on slides
		'caption'		=> false, 				// Display captions of attachments on slides
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
			return sprintf( '<div class="alert warning"><p>%s</p></div>', __( 'Oops! Couldn\'t find a gallery with one or more image attachments. Make sure to insert a gallery into the body of the post. Example: [gallery] ids="1,2,3"]', 'themeblvd' ) );
		} else {
			return;
		}
	}

	// Prepare images
	$images = array();
	$sizes = themeblvd_get_image_sizes();
	$search = array('slider-x-large', 'slider-large', 'slider-medium');

	if ( $args['size'] ) {
		array_unshift( $search, $args['size'] );
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

		// Find image that matches one of our crop sizes exactly
		foreach ( $search  as $size ) {

			$img = wp_get_attachment_image_src( $attachment->ID, $size );

			if ( $img[3] && $img[1] == $sizes[$size]['width'] && $img[2] == $sizes[$size]['height'] ) {
				break;
			}
		}

		$thumb = wp_get_attachment_image_src( $attachment->ID, 'tb_thumb' );

		$images[] = array(
			'crop'		=> $size,
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
	$options['crop'] = $size;
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
		'id'				=> uniqid('slider_'),	// Unique ID for the slider
		'crop'				=> 'slider-large',		// Crop size for images
		'interval'			=> '5',					// How fast to auto rotate betweens slides
		'pause'				=> '1',					// Whether to pause slider on hover
		'wrap'				=> '1',					// When slider auto-rotates, whether it continuously cycles
		'nav_standard'		=> '1',					// Whether to show standard navigation dots
		'nav_arrows'		=> '1',					// Whether to show navigation arrows
		'arrows'			=> 'standard',			// Nav arrow style - standard, mini
		'nav_thumbs'		=> '0',					// Whether to show navigation image thumbnails
		'link'				=> '1',					// Whether linked slides have animated hover overlay effect
		'thumb_size'		=> 'smaller',			// Size of thumbnails - small, smaller, smallest or custom int
		'dark_text'			=> '0',					// Whether to use dark text for title/descriptions/standard nav, use when images are light
		'cover'				=> '0',					// popout: Whether images horizontal space 100%
		'position'			=> 'middle center',		// popout: If cover is true, how slider images are positioned (i.e. with background-position)
		'height_desktop'	=> '400',				// popout: If cover is true, slider height for desktop viewport
		'height_tablet'		=> '300',				// popout: If cover is true, slider height for tablet viewport
		'height_mobile'		=> '200',				// popout: If cover is true, slider height for mobile viewport
		'class'				=> ''					// Any CSS classes to add
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
			'link_url'		=> ''
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

	if ( $args['class'] ) {
		$class .= ' '.$args['class'];
	}

	if ( $args['dark_text'] ) {
		$class .= ' dark-text';
	}

	if ( $args['cover'] ) {
		$class .= ' cover';
	}

	// Inline styles for popout slider with background images that cover full width
	if ( $args['cover'] ) {

		$style = "\n<style>\n";

		$style .= sprintf( "#%s .image {\n", $args['id'] );
		$style .= sprintf( "background-position: %s;\n", $args['position'] );
		$style .= sprintf( "height: %spx;\n", $args['height_desktop'] );
		$style .= "}\n";

		$style .= "@media (max-width: 992px) {\n";
		$style .= sprintf( "#%s .image {\n", $args['id'] );
		$style .= sprintf( "height: %spx;\n", $args['height_tablet'] );
		$style .= "}\n";
		$style .= "}\n";

		$style .= "@media (max-width: 767px) {\n";
		$style .= sprintf( "#%s .image {\n", $args['id'] );
		$style .= sprintf( "height: %spx;\n", $args['height_mobile'] );
		$style .= "}\n";
		$style .= "}\n";

		$style .= "</style>\n";

		$output .= apply_filters( 'themeblvd_simple_slider_cover_style', $style, $args );

	}

	$output .= sprintf( '<div id="%s" class="%s" data-ride="carousel" data-interval="%s" data-pause="%s" data-wrap="%s">', $args['id'], $class, $interval, $pause, $args['wrap'] );
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

			$output .= sprintf( '<li data-target="#%s" data-slide-to="%s" class="%s"></li>', $args['id'], $counter, $class );

			$counter++;
		}

		$output .= '</ol>';
	}

	// Slides
	$output .= '<div class="carousel-inner">';

	$counter = 0;

	if ( count( $images ) > 1 ) {
		foreach ( $images as $img ) {

			$class = 'item';

			if ( $counter == 0 ) {
				$class .= ' active';
			}

			$output .= sprintf( '<div class="%s">', $class );

			$image = '';

			if ( $args['cover'] ) {
				$image = sprintf( '<div class="image" style="background-image: url(%s);"></div>', $img['src'] );
			} else {
				$image = sprintf( '<img src="%s" alt="%s" />', $img['src'], $img['alt'] );
			}

			if ( $img['link'] ) {

				$a_class = 'slide-link';

				if ( $args['thumb_link'] ) {
					$a_class .= ' tb-thumb-link';
				}

				if ( $img['link'] == 'image' || $img['link'] == 'video' ) {

					if ( $args['thumb_link'] ) {
						$a_class .= ' '.$img['link'];
					}

					$lightbox = array(
						'item' 		=> $image,
						'link' 		=> $img['link_url'],
						'title' 	=> $img['alt'],
						'class' 	=> $a_class
					);
					$output .= themeblvd_get_link_to_lightbox( $lightbox );

				} else {

					if ( $args['thumb_link'] ) {
						if ( $img['link'] == '_self' ) {
							$a_class .= ' post';
						} else if ( $img['link'] == '_blank' ) {
							$a_class .= ' external';
						}
					}

					$output .= sprintf( '<a href="%s" title="%s" class="%s" target="%s">%s</a>', $img['link_url'], $img['alt'], $a_class, $img['link'], $image );
				}

			} else {
				$output .= $image;
			}

			if ( $img['title'] || $img['desc'] ) {

				$output .= '<div class="carousel-caption">';

				if ( $img['title'] ) {
					$output .= sprintf( '<h3>%s</h3>', $img['title'] );
				}

				if ( $img['desc'] ) {
					if ( $img['desc_wpautop'] ) {
						$output .= wpautop( $img['desc'] );
					} else {
						$output .= $img['desc'];
					}
				}

				$output .= '</div>';
			}

			$output .= '</div><!-- .item (end) -->';

			$counter++;
		}
	}

	$output .= '</div><!-- .carousel-inner (end) -->';

	// Nav arrows
	if ( $args['nav_arrows'] ) {

		if ( $args['arrows'] == 'mini' ) {

			$output .= themeblvd_get_slider_controls( array( 'carousel' => $args['id'] ) );

		} else {

			$output .= '<a class="left carousel-control" href="#'.$args['id'].'" data-slide="prev">';
			$output .= '<span class="glyphicon glyphicon-chevron-left"></span>';
			$output .= '</a>';

			$output .= '<a class="right carousel-control" href="#'.$args['id'].'" data-slide="next">';
			$output .= '<span class="glyphicon glyphicon-chevron-right"></span>';
			$output .= '</a>';

		}
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

			$output .= sprintf( '<li data-target="#%s" data-slide-to="%s" class="%s">', $args['id'], $counter, $class );
			$output .= sprintf( '<img src="%s" alt="%s" width="%s" />', $img['thumb'], $img['alt'], $size );
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
 * Take a piece of markup and wrap it in a link to a lightbox.
 *
 * @since 2.3.0
 *
 * @param $args array Arguments for lightbox link
 */
function themeblvd_link_to_lightbox( $args ) {
	echo themeblvd_get_link_to_lightbox( $args );
}

/**
 * Take a piece of markup and wrap it in a link to a lightbox.
 *
 * @since 2.3.0
 *
 * @param $args array Arguments for lightbox link
 * @return $output string Final HTML to output
 */
function themeblvd_get_link_to_lightbox( $args ) {

	$defaults = array(
		'item' 		=> themeblvd_get_local('link_to_lightbox'), // HTML Markup to be wrapped in link
		'link' 		=> '',										// Source for media in lightbox
		'title' 	=> '', 										// Title for link
		'type'		=> '',										// Type of lightbox link - image, iframe, ajax, inline - leave blank for auto detection
		'class' 	=> '', 										// Additional CSS classes to add
		'props'		=> array(),									// Additional properties for anchor tag, i.e. array( 'data-something' => 'whatever' )
		'addon'		=> '',										// Optional addon for anchor tag, i.e. data-something="whatever"
		'gallery' 	=> false									// Whether this is part of a gallery
	);
	$args = wp_parse_args( $args, $defaults );

	// Item markup to wrap link around
	$item = $args['item'];

	// Start building link properties
	$props = array(
		'href'	=> $args['link'],
		'title'	=> $args['title'],
		'class'	=> ''
	);

	// Fix for youtu.be links
	if ( strpos( $props['href'], 'http://youtu.be/' ) !== false ) {
		$props['href'] = str_replace( 'http://youtu.be/', 'http://youtube.com/watch?v=', $props['href'] );
	}

	// Lightbox type
	$types = array( 'image', 'iframe', 'inline', 'ajax' );
	$type = $args['type'];

	if ( ! in_array( $type, $types ) ) {

		// Auto lightbox type detection
		if ( strpos( $props['href'], 'youtube.com' ) !== false || strpos( $props['href'], 'vimeo.com' ) !== false || strpos( $props['href'], 'maps.google.com' ) !== false ) {

			$type = 'iframe';

		} else if ( strpos( $props['href'], '#' ) === 0 ) {

			$type = 'inline';

		} else {

			$parsed_url = parse_url( $props['href'] );
			$filetype = wp_check_filetype( $parsed_url['path'] );

			// Link to image file?
			if ( substr( $filetype['type'], 0, 5 ) == 'image' ) {
				$type = 'image';
			}
		}

	}

	// CSS classes
	$class = array( 'themeblvd-lightbox', "mfp-{$type}" );

	if ( 'iframe' == $type ) {
		$class[] = 'lightbox-iframe'; // Enables framework's separate JS for iframe video handling in non-galleries
	}

	$user_class = $args['class'];
	if ( ! is_array( $args['class'] ) ) {
		$user_class = explode(' ', $args['class'] );
	}

	$class = array_merge( $class, $user_class );
	$class = apply_filters( 'themeblvd_lightbox_class', $class, $args, $type, $item ); // Filter while still an array
	$props['class'] = implode( ' ', $class );

	// Add user any additional properties passed in
	if ( is_array( $args['props'] ) ) {
		$props = array_merge( $props, $args['props'] );
	}

	// Extend link properties
	$props = apply_filters( 'themeblvd_lightbox_props', $props, $args, $type, $item, $class );

	// Use properties array to build anchor tag
	$output = '<a ';
	foreach ( $props as $key => $value ) {
		$output .= "{$key}=\"{$value}\" ";
	}
	$output = themeblvd_remove_trailing_char( $output, ' ' );

	// Manual addon
	if ( $args['addon'] ) {
		$output .= ' '.$args['addon'];
	}

	// Finish link
	$output .= sprintf( '>%s</a>', $item );

	return apply_filters( 'themeblvd_link_to_lightbox', $output, $args, $props, $type, $item, $class );
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
		'crop'		=> '',
		'width'		=> 0,
		'height'	=> 0
	);
	$img_atts = wp_parse_args( $img_atts, $defaults );

	// Additional options for image display
	$defaults = array(
		'link' 		=> 'none',
    	'link_url'	=> '',
    	'frame' 	=> '0',
    	'class'		=> ''
	);
	$args = wp_parse_args( $args, $defaults );

	// Is this image going to be linked?
	$has_link = false;

	if ( $args['link'] != 'none' ) {
		$has_link = true;
	}

	// Image class
	$img_class = sprintf( 'size-%s wp-image-%s', $img_atts['crop'], $img_atts['id'] );

	if ( ! $has_link ) {
		$img_class .= ' alignnone';
	}

	if ( $args['frame'] && ! $has_link ) {
		$img_class .= ' thumbnail';
	}

	if ( ! $has_link && ! empty( $args['class'] ) ) {
		$img_class .= ' '.$args['class'];
	}

	// Setup intial image
	$img = sprintf( '<img src="%s" alt="%s" width="%s" height="%s" class="%s" />', $img_atts['src'], $img_atts['title'], $img_atts['width'], $img_atts['height'], $img_class );

	// Start output
	$output = $img;

	// Wrap image in link
	if ( $has_link ) {

		$anchor_classes = 'tb-thumb-link';

		if ( $args['frame'] ) {
			$anchor_classes .= ' thumbnail';
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

			$args = array(
				'item' 		=> $output,
				'link' 		=> $link,
				'title' 	=> $img_atts['title'],
				'class' 	=> $anchor_classes
			);
			$output = themeblvd_get_link_to_lightbox( $args );

		} else {

			if ( $args['frame'] ) {

				if ( $args['link'] == '_self' ) {
					$anchor_classes .= ' post';
				} else if ( $args['link'] == '_blank' ) {
					$anchor_classes .= ' external';
				}
			}

			$output = sprintf( '<a href="%s" class="%s" title="%s" target="%s">%s</a>', $args['link_url'], $anchor_classes, $img_atts['title'], $args['link'], $output );

		}
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

/**
 * Display custom slider from Theme Blvd Sliders plugin.
 *
 * @since 2.0.0
 *
 * @param string $slider Slug of custom-built slider to use
 */
function themeblvd_slider( $slider ) {

	// Kill it if there's no slider
	if ( ! $slider ) {
		printf('<div class="alert warning"><p>%s</p></div>', themeblvd_get_local( 'no_slider_selected.' ) );
		return;
	}

	// Get Slider ID
	$slider_id = themeblvd_post_id_by_name( $slider, 'tb_slider' );
	if ( ! $slider_id ) {
		echo themeblvd_get_local( 'no_slider' );
		return;
	}

	// Gather info
	$type = get_post_meta( $slider_id, 'type', true );
	$settings = get_post_meta( $slider_id, 'settings', true );
	$slides = get_post_meta( $slider_id, 'slides', true );

	// Display slider based on its slider type
	do_action( 'themeblvd_'.$type.'_slider', $slider, $settings, $slides );
}