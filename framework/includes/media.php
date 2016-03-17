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
			'name' 		=> __('Theme Blvd XL', 'jumpstart'),
			'width' 	=> $max,
			'height' 	=> 9999,
			'crop' 		=> false
		),
		'tb_large' => array(
			'name' 		=> __('Theme Blvd L', 'jumpstart'),
			'width' 	=> 800,
			'height'	=> 9999,
			'crop' 		=> false
		),
		'tb_medium' => array(
			'name' 		=> __('Theme Blvd M', 'jumpstart'),
			'width' 	=> 500,
			'height'	=> 9999,
			'crop' 		=> false
		),
		'tb_thumb'	=> array(
			'name' 		=> __('Theme Blvd Thumbnail', 'jumpstart'),
			'width' 	=> 200,
			'height' 	=> 200,
			'crop' 		=> true
		),
		'tb_grid' => array(
			'name' 		=> __('Theme Blvd 16:9', 'jumpstart'), // 16:9
			'width' 	=> 640,
			'height' 	=> 360,
			'crop' 		=> true
		),
		'tb_square_x_large' => array(
			'name' 		=> __('Theme Blvd XL Square', 'jumpstart'),
			'width' 	=> 1200,
			'height' 	=> 1200,
			'crop' 		=> true
		),
		'tb_square_large' => array(
			'name' 		=> __('Theme Blvd L Square', 'jumpstart'),
			'width' 	=> 960,
			'height' 	=> 960,
			'crop' 		=> true
		),
		'tb_square_medium' => array(
			'name' 		=> __('Theme Blvd M Square', 'jumpstart'),
			'width' 	=> 800,
			'height' 	=> 800,
			'crop' 		=> true
		),
		'tb_square_small' => array(
			'name' 		=> __('Theme Blvd S Square', 'jumpstart'),
			'width' 	=> 500,
			'height' 	=> 500,
			'crop' 		=> true
		),
		'slider-x-large' => array(
			'name' 		=> __('Slider Extra Large', 'jumpstart'),
			'width' 	=> 1200,
			'height' 	=> 450,
			'crop' 		=> true
		),
		'slider-large' => array(
			'name' 		=> __('Slider Large', 'jumpstart'),
			'width' 	=> 960,
			'height' 	=> 360,
			'crop' 		=> true
		),
		'slider-medium' => array(
			'name' 		=> __('Slider Medium', 'jumpstart'),
			'width' 	=> 800,
			'height' 	=> 300,
			'crop' 		=> true
		),
		'slider-staged' => array(
			'name' 		=> __('Slider Staged', 'jumpstart'),
			'width' 	=> 690,
			'height' 	=> 415,
			'crop' 		=> true
		)
	);

	$sizes = apply_filters( 'themeblvd_image_sizes', $sizes );

	if ( $size ) {
		if ( isset( $sizes[$size] ) ) {
			return $sizes[$size];
		} else {
			return false;
		}
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
 * Remove constraints on image URL's generated in the
 * admin.
 *
 * This function isn't by defualt filtered on, but it
 * can be applied/removed as needed onto WP's
 * "editor_max_image_size".
 *
 * @since 2.5.0
 */
function themeblvd_editor_max_image_size() {
	return array(1200, 1200);
}

/**
 * If we're retrieving an crop size that is in one of our
 * stacks, and it doesn't exist, the default WP action would
 * be to just return the full-size image. Instead, we can
 * call this function to try and find the next crop size in
 * the stack.
 *
 * This process helps to downsize the quality of an image,
 * but still maintain the same aspect ratio, as each stack shares
 * a common downsize pattern.
 *
 * @since 2.5.0
 *
 * @param array $attachment Attachment from original call to get_attachment_image_src()
 * @param string $id Original attachment ID
 * @param string $crop
 */
function themeblvd_image_downsize( $attachment, $attachmen_id, $crop = 'tb_x_large' ) {

	if ( ! empty( $attachment[3] ) ) {
		return $attachment;
	}

	$crop_atts = themeblvd_get_image_sizes($crop);

	// Catches if original image uploaded matches crop size exactly
	if ( $attachment[1] == $crop_atts['width'] && ( $attachment[2] == $crop_atts['height'] || $crop_atts['height'] == 9999 ) ) {
		return $attachment;
	}

	$stacks = array(
		array( 'tb_x_large', 'tb_large', 'tb_medium' ),
		array( 'tb_square_x_large', 'tb_square_large', 'tb_square_medium' ),
		array( 'slider-x-large', 'slider-large', 'slider-medium' )
	);

	$scaled = $attachment;

	foreach ( $stacks as $stack ) {
		if ( in_array($crop, $stack) ) {

			$key = array_search($crop, $stack);

			if ( $key > 0 ) {
				for ( $i = 0; $i <= $key; $i++ ) {
					unset($stack[$i]);
				}
			}

			if ( $stack ) {
				foreach ( $stack as $size ) {

					$scaled = wp_get_attachment_image_src( $attachmen_id, $size );

					if ( $scaled[3] ) {
						break;
					}

					$crop_atts = themeblvd_get_image_sizes($size);

					if ( $scaled[1] == $crop_atts['width'] && ( $scaled[2] == $crop_atts['height'] || $crop_atts['height'] == 9999 ) ) {
						break;
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
 * Get the image link for a featured image, set
 * from the standard framework featured image
 * link meta options.
 *
 * @since 2.5.0
 *
 * @param int $post_id ID of post to pull meta data from
 * @param int $thumb_id ID of attachment post set as featured image
 * @param string $link Override pulling type directly from _tb_thumb_link
 */
function themeblvd_get_post_thumbnail_link( $post_id = 0, $thumb_id = 0, $link = '' ) {

	if ( $link === false ) {
		return false;
	}

	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$params = array(
		'href' 		=> '',
		'title' 	=> '',
		'target'	=> '',
		'class'		=> 'featured-image tb-thumb-link',
		'icon'		=> ''
	);

	if ( ! $link ) {
		$link = get_post_meta( $post_id, '_tb_thumb_link', true );
	}

	if ( $link == 'inactive' || ! $link ) {
		return false;
	}

	if ( ! $thumb_id  && ( $link == 'thumbnail' || $link == 'video' ) ) {
		$thumb_id = get_post_thumbnail_id($post_id);
	}

	switch ( $link ) {

		// Link to post's permalink
		case 'post' :
			$params['href'] = get_permalink($post_id);
			$params['title'] = get_the_title();
			$params['target'] = '_self';
			$params['class'] .= ' post';
			$params['tooltip'] = themeblvd_get_local('view_item');
			break;

		// Linked to enlarged version of the current featured image in a lightbox
		case 'thumbnail' :
			$enlarge = wp_get_attachment_image_src( $thumb_id, 'tb_x_large' );
			$params['href'] = $enlarge[0];
			$params['title'] = get_the_title($thumb_id);
			$params['target'] = 'lightbox';
			$params['class'] .= ' image';
			$params['tooltip'] = themeblvd_get_local('enlarge');
			break;

		// Link to an inputted image URL in a lightbox
		case 'image' :
			$params['href'] = get_post_meta( $post_id, '_tb_image_link', true );
			$params['title'] = get_the_title();
			$params['target'] = 'lightbox';
			$params['class'] .= ' image';
			$params['tooltip'] = themeblvd_get_local('enlarge');
			break;

		// Link to a Vimeo or YouTube video in a lightbox
		case 'video' :
			$params['href'] = get_post_meta( $post_id, '_tb_video_link', true );
			$params['title'] = get_the_title($thumb_id);
			$params['target'] = 'lightbox';
			$params['class'] .= ' video';
			$params['tooltip'] = themeblvd_get_local('play');
			break;

		// Link to an external URL
		case 'external' :
			$params['href'] = get_post_meta( $post_id, '_tb_external_link', true );
			$params['title'] = get_the_title();
			$params['target'] = get_post_meta( $post_id, '_tb_external_link_target', true );
			$params['class'] .= ' external';
			$params['tooltip'] = themeblvd_get_local('go_to_link');

	}

	$params['class'] = apply_filters('themeblvd_post_thumbnail_a_class', $params['class'], $post_id, $thumb_id); // backwards compat

	return apply_filters( 'themeblvd_post_thumbnail_link', $params, $post_id, $link );
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

	$h = ( 9 / 16 ) * 100; // 16:9

	if ( intval($args['height']) > 0 && intval($args['height']) < 9999 && intval($args['width']) > 0 ) {
		$h = ( intval($args['height']) / intval($args['width']) ) * 100;
	}

	$h = strval($h).'%';

	$icon = sprintf( '<i class="fa fa-%s"></i>',  themeblvd_get_format_icon($args['format'], true) );

	if ( $args['link'] ) {
		$icon = sprintf( '<a href="%s" title="%s">%s</a>', esc_url($args['link']), esc_attr($args['title']), $icon );
	}

	$output = sprintf( '<div class="%s"><div class="placeholder" style="padding-bottom:%s">%s</div></div>', $class, $h, $icon );

	return apply_filters('themeblvd_media_placeholder', $output, $args );
}

/**
 * Filter WP's image caption. Allow for thumbnail class
 * to be added, if enabled. Add lightbox functionality.
 *
 * @since 2.0.0
 */
function themeblvd_img_caption_shortcode( $output, $attr, $content ) {

	$atts = shortcode_atts( array(
		'id'	  => '',
		'align'	  => 'alignnone',
		'width'	  => '',
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

	$class = trim( 'wp-caption ' . $atts['align'] . ' ' . $atts['class'] );

	if ( apply_filters('themeblvd_img_frame', false) ) {
		$class .= ' thumbnail';
	}

	$url = themeblvd_get_content_url($content);

	if ( ! empty($url[1]) ) { // Image is linked

		if ( $lightbox = themeblvd_is_lightbox_url( $url[1] ) ) {

			// Strip link
			$content = str_replace( array('<a href="'.$url[1].'">', '</a>'), '', $content );

			// Re-wrap image with link
			$content = themeblvd_get_link_to_lightbox(array(
				'item'	=> $content,
				'link'	=> $url[1],
				'class'	=> 'tb-thumb-link '.$lightbox
			));

		} else {

			if ( strpos( $url[1], get_site_url() ) !== false ) {
				$content = str_replace('<a', '<a class="tb-thumb-link post"', $content);
			} else {
				$content = str_replace('<a', '<a class="tb-thumb-link external" target="_blank"', $content);
			}

		}

	}

	$output  = sprintf('<figure %s style="width: %spx;" class="%s">', $atts['id'], (int) $atts['width'], esc_attr($class));
	$output .= do_shortcode( $content );
	$output .= sprintf('<figcaption class="wp-caption-text">%s</figcaption>', $atts['caption']);
	$output .= '</figure>';

	return $output;
}

/**
 * Add wrapper around embedded videos to
 * allow for responsive videos.
 *
 * @since 2.0.0
 */
function themeblvd_oembed_result( $html, $url ) {

	// If this is a tweet, keep on movin' fella.
	if ( strpos($url, 'twitter.com') !== false ) {
		return $html;
	}

	// If this is a link to external WP post
	// (introduced in WP 4.4), abort.
	if ( strpos($html, 'wp-embedded-content') !== false ) {
		return $html;
	}

	// Since the framework applies this filter in two
	// spots, we must first check if the filter has
	// been applied or not. The reason for this is
	// because WP has issues with caching the oembed
	// result, and oembed_result doesn't always get
	// applied when it's supposed to.
	if ( strpos($html, 'themeblvd-video-wrapper') !== false ) {
		return $html;
	}

	// Apply YouTube wmode fix
	if ( strpos($url, 'youtube') !== false || strpos($url, 'youtu.be') !== false ) {
		if ( strpos($html, 'wmode=opaque') === false ) {
			$html = str_replace('feature=oembed', 'feature=oembed&wmode=opaque', $html);
		}
	}

	return sprintf('<div class="themeblvd-video-wrapper"><div class="video-inner">%s</div></div>', $html);
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
		'nav_thumbs'	=> false,				// Whether to show nav thumbnails (added by Theme Blvd framework)
		'title'			=> false,				// Display title of attachments on slides
		'caption'		=> false, 				// Display captions of attachments on slides
		'dark_text'		=> false,				// Whether to use dark text for title/descriptions/standard nav, use when images are light
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
			return sprintf( '<div class="alert alert-warning"><p>%s</p></div>', esc_html__('Oops! Couldn\'t find a gallery with one or more image attachments. Make sure to insert a gallery into the body of the post. Example: [gallery ids="1,2,3"]', 'jumpstart') );
		} else {
			return;
		}
	}

	// Prepare images
	$images = array();

	if ( $args['size'] ) {
		$crop = $args['size'];
	} else {
		$crop = apply_filters('themeblvd_gallery_slider_default_crop', 'slider-x-large');
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
		'id'					=> uniqid('slider_'),	// Unique ID for the slider
		'crop'					=> 'slider-large',		// Crop size for images
		'interval'				=> '5',					// How fast to auto rotate betweens slides
		'pause'					=> '1',					// Whether to pause slider on hover
		'wrap'					=> '1',					// When slider auto-rotates, whether it continuously cycles
		'nav_standard'			=> '1',					// Whether to show standard navigation dots
		'nav_arrows'			=> '1',					// Whether to show navigation arrows
		'arrows'				=> 'standard',			// Nav arrow style - standard, mini
		'nav_thumbs'			=> '0',					// Whether to show navigation image thumbnails
		'shade'					=> '0',					// Whether entire image is shaded
		'link'					=> '1',					// Whether linked slides have animated hover overlay effect
		'thumb_size'			=> 'smaller',			// Size of thumbnails - small, smaller, smallest or custom int
		'dark_text'				=> '0',					// Whether to use dark text for title/descriptions/standard nav, use when images are light
		'caption_bg'			=> '0',					// Whether to add BG color to caption
		'caption_bg_color'		=> '#000000',			// Caption BG color
		'caption_bg_opacity'	=> '0.5',				// Caption BG color opacity
		'cover'					=> '0',					// popout: Whether images horizontal space 100%
		'position'				=> 'middle center',		// popout: If cover is true, how slider images are positioned (i.e. with background-position)
		'height_desktop'		=> '400',				// popout: If cover is true, slider height for desktop viewport
		'height_tablet'			=> '300',				// popout: If cover is true, slider height for tablet viewport
		'height_mobile'			=> '200',				// popout: If cover is true, slider height for mobile viewport
		'class'					=> ''					// Any CSS classes to add
	);
	$args = apply_filters( 'themeblvd_simple_slider_args', wp_parse_args( $args, $defaults ) );

	// Make sure $images array is setup properly
	foreach ( $images as $img_id => $img ) {
		$images[$img_id] = wp_parse_args( $img, array(
			'crop'			=> $args['crop'],
			'id'			=> 0,
			'alt'			=> '',
			'src'			=> '',
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
	$output .= '<div class="carousel-inner">';

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
						'title' 	=> $img['alt']
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
				$output .= sprintf( '<img src="%s" alt="%s" />', $img_src, esc_attr($img['alt']) );
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

				$output .= '</div><!-- .carousel-caption-wrap (end) -->';

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
		'href'	=> esc_url($args['link']),
		'title'	=> esc_html($args['title']),
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
	$props['class'] = esc_attr( implode( ' ', $class ) );

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
	$output = trim($output);

	// Manual addon
	if ( $args['addon'] ) {
		$output .= ' '.wp_kses( $args['addon'], array() );
	}

	// Finish link
	$output .= sprintf( '>%s</a>', themeblvd_kses($item) );

	return apply_filters( 'themeblvd_link_to_lightbox', $output, $args, $props, $type, $item, $class );
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
		$width += 'px';
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
		printf('<div class="alert warning"><p>%s</p></div>', themeblvd_get_local('no_slider_selected') );
		return;
	}

	// Get Slider ID
	$slider_id = themeblvd_post_id_by_name( $slider, 'tb_slider' );
	if ( ! $slider_id ) {
		echo themeblvd_get_local('no_slider');
		return;
	}

	// Gather info
	$type = get_post_meta( $slider_id, 'type', true );
	$settings = get_post_meta( $slider_id, 'settings', true );
	$slides = get_post_meta( $slider_id, 'slides', true );

	// Display slider based on its slider type
	do_action( 'themeblvd_'.$type.'_slider', $slider, $settings, $slides );
}

/**
 * Get main site logo
 *
 * @since 2.5.0
 */
function themeblvd_get_logo( $logo = array(), $trans = false ) {

	$output = '';

	$defaults = array(
		'type' 				=> 'image',
	    'custom' 			=> '',
	    'custom_tagline' 	=> '',
	    'image' 			=> '',
	    'image_width' 		=> 0,
	    'image_height' 		=> 0,
	    'image_2x'			=> '',
	    'class'				=> ''
	);

	if ( ! $logo || ( isset($logo['type']) && $logo['type'] == 'image' && empty($logo['image']) ) ) {
		$logo = themeblvd_get_option('logo');
	}

	$logo = wp_parse_args( $logo, $defaults );

	if ( $logo ) {

		$class = 'header-logo header_logo header_logo_'.$logo['type'];

		if ( $logo['type'] == 'custom' || $logo['type'] == 'title' || $logo['type'] == 'title_tagline' ) {
			$class .= ' header-text-logo';
			$class .= ' header_logo_text'; // @deprecated legacy class
		}

		if ( $logo['type'] == 'custom' && ! empty( $logo['custom_tagline'] ) ) {
			$class .= ' header_logo_has_tagline';
		}

		if ( $logo['type'] == 'title_tagline' ) {
			$class .= ' header_logo_has_tagline';
		}

		if ( $logo['class'] ) {
			$class .= ' '.$logo['class'];
		}

		$output .= sprintf( '<div class="%s">', esc_attr($class) );

		if ( ! empty( $logo['type'] ) ) {
			switch ( $logo['type'] ) {

				case 'title' :
					$output .= sprintf( '<h1 class="tb-text-logo"><a href="%1$s" title="%2$s">%2$s</a></h1>', esc_url( themeblvd_get_home_url() ), esc_html( get_bloginfo('name') ) );
					break;

				case 'title_tagline' :
					$output .= sprintf( '<h1 class="tb-text-logo"><a href="%1$s" title="%2$s">%2$s</a></h1>', esc_url( themeblvd_get_home_url() ), esc_html( get_bloginfo('name') ) );
					$output .= sprintf( '<span class="tagline">%s</span>', esc_html( get_bloginfo('description') ) );
					break;

				case 'custom' :

					$output .= sprintf( '<h1 class="tb-text-logo"><a href="%1$s" title="%2$s">%2$s</a></h1>', esc_url( themeblvd_get_home_url() ), esc_attr( $logo['custom'] ) );

					if ( $logo['custom_tagline'] ) {
						$output .= sprintf( '<span class="tagline">%s</span>', themeblvd_kses( $logo['custom_tagline'] ) );
					}
					break;

				case 'image' :

					$output .= sprintf('<a href="%s" title="%s" class="tb-image-logo">', esc_url( themeblvd_get_home_url() ), esc_html( get_bloginfo('name') ) );
					$output .= sprintf( '<img src="%s" alt="%s" ', esc_url( $logo['image'] ), esc_html( get_bloginfo('name') ) );

					if ( ! empty( $logo['image_width'] ) ) {
						$output .= sprintf( 'width="%s" ', esc_attr( $logo['image_width'] ) );
					}

					if ( ! empty( $logo['image_height'] ) ) {
						$output .= sprintf( 'height="%s" ', esc_attr( $logo['image_height'] ) );
					}

					$srcset = '';

					if ( ! empty( $logo['image'] ) && ! empty( $logo['image_2x'] ) ) {
						$srcset .= sprintf( '%s 1x, %s 2x', $logo['image'], $logo['image_2x'] );
					}

					if ( $srcset = apply_filters('themeblvd_logo_srcset', $srcset, $logo) ) {
						$output .= sprintf( 'srcset="%s" ', $srcset );
					}

					$sizes = '';

					if ( $sizes = apply_filters('themeblvd_logo_sizes', $sizes, $logo) ) {
						$output .= sprintf( 'sizes="%s" ', $sizes );
					}

					$output .= '/></a>';

					break;
			}
		}

		$output .= '</div><!-- .header-logo (end) -->';

	}

	return apply_filters( 'themeblvd_logo', $output );
}

/**
 * Get background slideshow.
 *
 * @since 2.5.0
 */
function themeblvd_get_bg_slideshow( $id, $images, $parallax = false ) {

	$output = '';

	if ( ! $images || ! is_array( $images ) ) {
		return $output;
	}

	foreach ( $images as $img_id => $img ) {
		$images[$img_id] = wp_parse_args( $img, array(
			'crop'			=> 'full',
			'id'			=> 0,
			'alt'			=> '',
			'src'			=> ''
		));
	}

	$output .= sprintf( '<div id="bg-slideshow-%s" class="tb-bg-slideshow carousel" data-ride="carousel" data-interval="%s" data-pause="0" data-wrap="1">', $id, apply_filters('themeblvd_bg_slideshow_interval', 5000) );
	$output .= '<div class="carousel-control-wrap">';
	$output .= '<div class="carousel-inner">';

	$counter = 0;

	foreach ( $images as $img_id => $img ) {

		$class = 'item';

		if ( $parallax ) {
			$class .= ' tb-parallax';
		}

		if ( $counter == 0 ) {
			$class .= ' active';
		}

		$img_src = $img['src'];

		if ( is_ssl() ) {
			$img_src = str_replace('http://', 'https://', $img_src);
		}

		if ( $parallax ) {
			$output .= sprintf( '<div class="%s">%s</div><!-- .item (end) -->', $class, themeblvd_get_bg_parallax(array('src' => $img_src)) );
		} else {
			$output .= sprintf( '<div class="%s" style="background-image: url(%s);"></div><!-- .item (end) -->', $class, esc_url($img_src) );
		}

		$counter++;

	}

	$output .= '</div><!-- .carousel-inner (end) -->';
	$output .= '</div><!-- .carousel-control-wrap (end) -->';
	$output .= '</div><!-- .tb-bg-slideshow (end) -->';

	return apply_filters( 'themeblvd_bg_slideshow', $output, $images, $id );
}

/**
 * Display background slideshow.
 *
 * @since 2.5.0
 */
function themeblvd_bg_slideshow( $id, $images, $parallax = false ) {
	echo themeblvd_get_bg_slideshow( $id, $images, $parallax );
}

/**
 * When using a standard set of display options,
 * determine of parallax should be used for standard
 * image and texture backgrounds.
 *
 * @since 2.5.1
 */
function themeblvd_do_parallax( $display ) {

	if ( empty( $display['bg_type'] ) ) {
		return false;
	}

	if ( $display['bg_type'] == 'image' && isset($display['bg_image']['attachment']) && $display['bg_image']['attachment'] == 'parallax' )  {
		if ( ! empty($display['bg_image']['image']) ) {
			return true;
		}
	}

	if ( $display['bg_type'] == 'texture' && ! empty($display['apply_bg_texture_parallax']) ) {
		return true;
	}

	return false;
}

/**
 * Get background parallax image.
 *
 * @since 2.5.1
 */
function themeblvd_get_bg_parallax( $display ) {

	// Type of background, image or texture
	$type = 'image';

	if ( ! empty($display['bg_type']) ) {
		$type = $display['bg_type'];
	}

	// Base bg color behind image
	$color = '#000000';

	if ( ! empty( $display['bg_color'] ) ) {
		$color = $display['bg_color'];
	}

	// Image to display
	$src = '';
	$texture = '';

	if ( $type == 'image' ) {

		if ( ! empty($display['src']) ) { // manually feed in
			$src = $display['src'];
		} else if ( ! empty($display['bg_image']['image']) ) {
			$src = $display['bg_image']['image'];
		}

	} else if ( $type == 'texture' ) {

		if ( ! empty($display['bg_texture']) ) {
			$texture = $display['bg_texture'];
		}

	}

	// CSS class
	$class = 'parallax-figure';

	if ( $texture ) {
		$class .= ' has-texture';
	}

	// Start ouptut
	$output = sprintf( '<div class="%s" style="background-color:%s">', esc_attr($class), esc_attr($color) );

	if ( $type == 'texture' ) {
		$texture = themeblvd_get_texture( $texture );
		$output .= sprintf('<div class="img" style="background-image:url(%s);background-position:%s;background-repeat:%s;background-size:%s;"></div>', esc_url($texture['url']), esc_attr($texture['position']), esc_attr($texture['repeat']), esc_attr($texture['size']));
	} else {
		$output .= sprintf('<img src="%s" alt="" />', esc_url($src));
	}

	$output .= '</div><!-- .parallax-figure (end) -->';

	return apply_filters( 'themeblvd_bg_parallax', $output, $display );
}

/**
 * Display background parallax image.
 *
 * @since 2.5.1
 */
function themeblvd_bg_parallax( $display ) {
	echo themeblvd_get_bg_parallax( $display );
}

/**
 * Get background video.
 *
 * @since 2.5.0
 */
function themeblvd_get_bg_video( $video ) {

	$video = apply_filters('themeblvd_video_args', wp_parse_args( $video, array(
		'id'		=> uniqid( 'video_'.rand() ),
		'mp4'		=> '',		// .mp4, .ogg, .webm, vimeo url, or youtube url
		'ratio'		=> '16:9',
		'fallback'	=> ''
	)));

	$source = themeblvd_get_video_source( $video['mp4'] );

	$output  = sprintf("\n<div class=\"tb-bg-video %s\" data-ratio=\"%s\">", $source, $video['ratio']);
	$output .= "\n\t<div class=\"no-click\"></div>";

	switch ( $source ) {

		case 'html5' :

			wp_enqueue_script('wp-mediaelement');

			preg_match("!^(.+?)(?:\.([^.]+))?$!", $video['mp4'], $path_split);

			$output .= sprintf("\n\t<video id=\"%s\" controls", $video['id']);

			if ( $video['fallback'] && themeblvd_is_200($video['fallback']) ) {
				$output .= sprintf(" poster=\"%s\"", $video['fallback']);
			}

			$output .= ">\n";

			$types = apply_filters('themeblvd_html5_video_types', array(
				'webm'	=> 'type="video/webm"',
				'mp4'	=> 'type="video/mp4"',
				'ogv'	=> 'type="video/ogg"'
			));

			foreach ( $types as $key => $type ) {
				if ( $path_split[2] == $key || themeblvd_is_200($path_split[1].'.'.$key) ) {
					$output .= sprintf("\t\t<source src=\"%s.%s\" %s />\n", $path_split[1], $key, $type);
				}
			}

			$output .= "\t</video>\n";

			break;

		case 'youtube' :

			$explode_at = strpos($video['mp4'], 'youtu.be/') !== false ? "/" : "v=";
			$yt_id = explode($explode_at, trim($video['mp4']));
			$yt_id = end($yt_id);

			if ( $yt_id ) {

				$args = apply_filters('themeblvd_youtube_bg_args', array(
					'vid'				=> $yt_id,
					'autoplay'			=> 0, // will play video through API after it's loaded
					'loop'				=> 1,
					'hd'				=> 1,
					'controls'			=> 0,
					'showinfo'			=> 0,
					'modestbranding'	=> 1,
					'iv_load_policy'	=> 3,
					'rel'				=> 0,
					'version'			=> 3,
					'enablejsapi'		=> 1,
					'wmode'				=> 'opaque',
					'playlist'			=> $yt_id
				));

				$output .= sprintf("\n\t<div id=\"%s\" class=\"video\"", $video['id']);

				foreach ( $args as $key => $val ) {
					$output .= sprintf(' data-%s="%s"', $key, $val);
				}

				$output .= "></div>\n";
			}

			break;

		case 'vimeo' :

			wp_enqueue_script('froogaloop');

			$v_id = explode('/', trim($video['mp4']));
			$v_id = end($v_id);

			$v_url = add_query_arg( apply_filters('themeblvd_vimeo_bg_args',array(
				'portrait' 		=> 0,
				'byline'		=> 0,
				'title'			=> 0,
				'badge'			=> 0,
				'loop'			=> 1,
				'autopause'		=> 0,
				'api'			=> 1,
				'rel'			=> 0,
				'player_id'		=> $video['id'],
				'background'	=> 1
			)), 'https://player.vimeo.com/video/'.$v_id );

			$output .= sprintf("\n\t<iframe id=\"%s\" src=\"%s\" height=\"1600\" width=\"900\" frameborder=\"\" class=\"video\"></iframe>\n", $video['id'], $v_url);

			break;

	}

	$output .= "</div><!-- .tb-bg-video (end) -->\n";

	return $output;

	// ... @TODO

	if ( $video['source'] == 'youtube' ) {

		if ( $video['youtube'] ) {

			$id = '';

			$bits = explode('?', $video['youtube']);
			$bits = explode('&', $bits[1]);

			if ( $bits ) {
				foreach ( $bits as $bit ) {
					if ( strpos($bit, 'v=') === 0 ) {
						$id = str_replace('v=', '', $bit);
						break;
					}
				}
			}

			if ( $id ) {

				$output .= '<div class="tb-bg-video youtube">';
				$output .= '<div class="no-click"></div>';

				$ratio = explode(':', $video['ratio']);
				$ratio = ( intval($ratio[1]) / intval($ratio[0]) ) * 100;

				//echo '<pre>'; print_r($ratio); echo '</pre>';


				$output .= sprintf('<div class="video-inner" style="padding-bottom:%s">', $ratio);

				$output .= sprintf('<iframe frameborder="0" scrolling="no" seamless="seamless" width="100%%" height="100%%" src="http://youtube.com/embed/%1$s?autoplay=1&loop=1&hd=1&controls=0&showinfo=0&modestbranding=1&iv_load_policy=3&rel=0&playlist=%1$s"></iframe>', $id);
				$output .= '</iframe>';
				$output .= '</div><!-- .video-inner (end) -->';
				$output .= '</div><!-- .tb-bg-video (end) -->';
			}

		}

	} else {

		$output .= "\t<video class=\"tb-bg-video\"";

		if ( $video['autoplay'] ) {
			$output .= " autoplay";
		}

		if ( $video['loop'] ) {
			$output .= " loop";
		}

		if ( $video['controls'] ) {
			$output .= " controls";
		}

		if ( $video['width'] ) {
			$output .= sprintf( " width=\"%s\"", esc_attr($video['width']) );
		}

		if ( $video['height'] ) {
			$output .= sprintf( " width=\"%s\"", esc_attr($video['width']) );
		}

		$output .= ">\n";

		if ( $video['webm'] ) {
			$output .= sprintf( "\t\t<source src=\"%s\" type=\"video/webm\">\n", esc_url($video['webm']) );
		}

		if ( $video['mp4'] ) {
			$output .= sprintf( "\t\t<source src=\"%s\" type=\"video/mp4\">\n", esc_url($video['mp4']) );
		}

		$output .= "\t</video><!-- .tb-bg-video (end) -->\n";

	}

	return apply_filters( 'themeblvd_bg_video', $output, $video );
}

/**
 * Display background video.
 *
 * @since 2.5.0
 */
function themeblvd_bg_video( $video ) {
	echo themeblvd_get_bg_video( $video );
}

/**
 * Get source of video.
 *
 * @since 2.6.0
 */
function themeblvd_get_video_source( $video ) {

	$source = false;
	$filetype = wp_check_filetype($video);

	if ( ! empty( $filetype['ext'] ) ) {
		$source = 'html5';
	} else if ( strpos($video, 'youtube.com/watch') !== false || strpos($video, 'youtu.be/') !== false ) {
		$source = 'youtube';
	} else if ( strpos($video, 'vimeo.com') !== false ) {
		$source = 'vimeo';
	}

	return $source;
}
