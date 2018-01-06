<?php
/**
 * Frontend Blocks: Media
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.5.0
 */

/**
 * Get the featured image of a post, taking into account
 * framework featured image linking.
 *
 * @since Theme_Blvd 2.0.0 (re-written in 2.5.0)
 *
 * @param  string $size Optional. Image crop size.
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type int         $attachment_id Attachment post ID of featured image.
 *     @type string      $location      Location of this post loop.
 *     @type bool        $placeholder   Whether to show placeholder when featured image isn't set.
 *     @type string|bool $link          How image links, `link`, `post`, or `thumbnail`; set to FALSE to force no link.
 *     @type string      $img_before    Any HTML to output before the image.
 *     @type string      $img_after     Any HTML to output after the image.
 *     @type bool        $srcset        Whether to include srcset data on <img>.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_post_thumbnail( $size = '', $args = array() ) {

	global $post;

	if ( ! $size ) {

		$size = themeblvd_get_att( 'crop' );

	}

	if ( ! $size ) {

		/**
		 * Filters the fallback image size for featured images,
		 * when none is set.
		 *
		 * Ideally, if no image size was set, we default to
		 * `tb_x_large`, and establish that as the biggest a
		 * featured image can be.
		 *
		 * This is an attempt to avoid ever pulling `full` in
		 * case user uploaded rediculously large image
		 *
		 * @since Theme_Blvd 2.7.0
		 *
		 * @param string Image crop size.
		 */
		$size = apply_filters( 'themeblvd_post_thumbnail_fallback_size', 'tb_x_large' );

	}

	/**
	 * Filters the default arguments for a featured
	 * image.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param array Default arguments used for themeblvd_get_post_thumbnail().
	 */
	$defaults = apply_filters( 'themeblvd_post_thumbnail_args', array(
		'attachment_id' => get_post_thumbnail_id( $post->ID ),
		'location'      => themeblvd_get_att( 'location' ),
		'placeholder'   => false,
		'link'          => null,
		'img_before'    => null,
		'img_after'     => null,
		'srcset'        => true,
	) );

	$args = wp_parse_args( $args, $defaults );

	if ( ! $args['attachment_id'] ) {

		if ( $args['placeholder'] ) {

			if ( true === $args['link'] ) {

				$output = themeblvd_get_media_placeholder( array(
					'link'  => get_permalink(),
					'title' => get_the_title(),
				) );

			} else {

				$output = themeblvd_get_media_placeholder();

			}
		} else {

			$output = '';

		}

		if ( $output ) {

			if ( $args['img_before'] ) {

				$output = $args['img_before'] . $output;

			}

			if ( $args['img_after'] ) {

				$output .= $args['img_after'];

			}
		}

		/** This filter is documented below, at the end of the function. */
		return apply_filters( 'themeblvd_post_thumbnail', $output, $args, $size );

	}

	// Determine link
	if ( false === $args['link'] ) {

		$link = false;

	} else {

		if ( 'single' === $args['location'] ) {

			$link = get_post_meta( $post->ID, '_tb_thumb_link_single', true );

			if ( 'yes' === $link ) {

				$link = get_post_meta( $post->ID, '_tb_thumb_link', true );

			}
		} else {

			$link = get_post_meta( $post->ID, '_tb_thumb_link', true );

		}

		if ( 'inactive' === $link || 'no' === $link || ! $link ) {

			$link = false;

		}
	}

	if ( ! $link && ( 'post' === $args['link'] || 'thumbnail' === $args['link'] ) ) {

		$link = $args['link'];

	}

	// Setup initial image without link.
	$class = '';

	if ( ! $link ) {

		$class = 'featured-image';

	}

	/**
	 * Filters the class attached to the <img> tag of a
	 * featured image.
	 *
	 * @since Theme_Blvd 2.4.0
	 *
	 * @param string $class Image class.
	 * @param int           Post ID.
	 * @param int           Attachment ID.
	 */
	$class = apply_filters( 'themeblvd_post_thumbnail_img_class', $class, $post->ID, $args['attachment_id'] );

	// Disable WordPress's srcset output on <img>.
	if ( ! $args['srcset'] ) {

		add_filter( 'wp_calculate_image_sizes', 'themeblvd_return_false' );

		add_filter( 'wp_calculate_image_srcset', 'themeblvd_return_false' );

	}

	/*
	 * Get the post thumbnail markup.
	 *
	 * Even though we're using a custom function to
	 * display the featured image, we're making sure
	 * to still use get_the_post_thumbnail() here for
	 * better compatibility with third-party plugins.
	 */
	$output = get_the_post_thumbnail( $post->ID, $size, array(
		'class' => $class,
	) );

	/*
	 * Manually pull image, based on inputted attachment
	 * ID.
	 *
	 * Above, we first tried get_the_post_thumbnail() to
	 * stick to WP standards, but in this event no featured
	 * image is assigned to the post, we'll just manually
	 * retrieve the image from the inputted attachment ID.
	 */
	if ( ! $output && $args['attachment_id'] ) {

		$output = wp_get_attachment_image(
			$args['attachment_id'],
			$size,
			false,
			array(
				'class' => $class,
			)
		);

	}

	// Restore general srcset output on images.
	if ( ! $args['srcset'] ) {

		remove_filter( 'wp_calculate_image_sizes', 'themeblvd_return_false' );

		remove_filter( 'wp_calculate_image_srcset', 'themeblvd_return_false' );

	}

	// Apply content before & after image, if necessary.
	if ( $output ) {

		if ( $args['img_before'] ) {

			$output = $args['img_before'] . $output;

		}

		if ( $args['img_after'] ) {

			$output .= $args['img_after'];

		}
	}

	// Wrap image in link, if necessary.
	if ( $link ) {

		$output .= themeblvd_get_thumbnail_link_icon( $link );

		$link = themeblvd_get_post_thumbnail_link(
			$post->ID,
			$args['attachment_id'],
			$link
		);

		if ( 'lightbox' === $link['target'] ) {

			/**
			 * Filters the arguments used to wrap a featured
			 * image in a link, which leads to a lightbox.
			 *
			 * @since Theme_Blvd 2.3.0
			 *
			 * @param array Arguments passed to themeblvd_get_link_to_lightbox().
			 * @param int   Post ID.
			 * @param int   Attachment ID.
			 *
			 */
			$lightbox = apply_filters(
				'themeblvd_featured_image_lightbox_args',
				array(
					'item'  => $output,
					'link'  => $link['href'],
					'class' => $link['class'],
					'title' => $link['title'],
				),
				$post->ID,
				$args['attachment_id']
			);

			$output = themeblvd_get_link_to_lightbox( $lightbox );

		} else {

			$output = sprintf(
				'<a href="%s" title="%s" class="%s" target="%s">%s</a>',
				esc_url( $link['href'] ),
				esc_attr( $link['title'] ),
				esc_attr( $link['class'] ),
				esc_attr( $link['target'] ),
				$output
			);

		}
	}

	/**
	 * Filters the final output for a featured image
	 * generated from themeblvd_get_post_thumbnail().
	 *
	 * Using the framework featured image wrapper
	 * function allows for additional features like
	 * adding links, placeholders, and disabling srcset.
	 *
	 * @since Theme_Blvd 2.0.0
	 *
	 * @param string $output Final HTML output for thumbnail.
	 * @param array  $args   Additional arguments for thumbnail.
	 * @param string $size   Crop size for post thumbnail.
	 */
	return apply_filters( 'themeblvd_post_thumbnail', $output, $args, $size );

}

/**
 * Get a gallery slider block.
 *
 * This function takes an inputted gallery shortcode
 * instance and sets up the info so it can be passed
 * to themeblvd_get_simple_slider().
 *
 * When no $gallery is passed directly into the function,
 * it will attempt to pull the first gallery instance
 * found within the content of the post.
 *
 * @since Theme_Blvd 2.3.0
 *
 * @param  string $gallery Gallery shortcode instance, like `[gallery ids="1,2,3"]`.
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string      $size         Crop size for all images in slider.
 *     @type string|bool $carousel     Whether to use variable-width carousel.
 *     @type string|int  $interval     Seconds between auto-rotation transitions; use `false` for no auto-rotation to be printed for JavaScript.
 *     @type string      $pause        Whether to pause slider on hover, use string `true` or `false` to be printed for JavaScript.
 *     @type string      $wrap         Whether slider should cycle continuously in a loop, use string `true` or `false` to be printed for JavaScript.
 *     @type bool        $nav_standard Whether to show standard slider navigation dots.
 *     @type bool        $nav_arrows   Whether to show slider navigation arrows.
 *     @type string      $arrows       If $nav_arrows is true, style of arrows, `standard` or `mini`.
 *     @type bool        $nav_thumbs   Whether to show slider thumbnail navigation.
 *     @type string|int  $thumb_size   If $nav_thumbs is true, thumb size, `small`, `smaller`, `smallest` or custom integer.
 *     @type bool        $title        Display title of image attachments on slides.
 *     @type bool        $caption      Display captions of image attachments on slides.
 *     @type bool        $dark_text    Whether to use dark text for title, descriptions and navigation; use when images are light.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_gallery_slider( $gallery = '', $args = array() ) {

	/**
	 * Filters the default arguments for a gallery
	 * slider.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param array Default argument passed to themeblvd_get_simple_slider().
	 */
	$defaults = apply_filters( 'themeblvd_gallery_slider_args', array(
		'size'         => '',
		'carousel'     => themeblvd_get_option( 'gallery_carousel' ),
		'interval'     => 'false',
		'pause'        => 'true',
		'wrap'         => 'true',
		'nav_standard' => false,
		'nav_arrows'   => true,
		'arrows'       => 'standard',
		'nav_thumbs'   => false,
		'thumb_size'   => 'smallest',
		'title'        => false,
		'caption'      => false,
		'dark_text'    => false,
	) );

	$args = wp_parse_args( $args, $defaults );

	$post_id = get_the_ID();

	// Did user pass in a gallery shortcode?
	if ( $gallery ) {

		$content = $gallery;

	} else {

		$content = get_the_content(); // Would need to be within the loop.

	}

	/*
	 * Did user insert a gallery like `[gallery ids="1,2,3,4"]`
	 * via $gallery param or anywhere in post?
	 */
	$attachments = array();

	$pattern = get_shortcode_regex();

	if ( preg_match( "/$pattern/s", $content, $match ) && 'gallery' == $match[2] ) {

		$atts = shortcode_parse_atts( $match[3] );

		if ( ! empty( $atts['ids'] ) ) {

			$query = array(
				'post_type'     => 'attachment',
				'post__in'      => explode( ',', $atts['ids'] ),
				'orderby'       => 'post__in',
				'numberposts'   => -1,
			);

			$attachments = get_posts( $query );

		}
	}

	// Slider requires at least 2 images.
	if ( count( $attachments ) <= 1 ) {

		if ( is_user_logged_in() ) {

			return sprintf(
				'<div class="alert alert-warning"><p>%s</p></div>',
				esc_html__( 'Oops! Couldn\'t find a gallery with one or more image attachments. Make sure to insert a gallery into the body of the post. Example: [gallery ids="1,2,3"]', 'jumpstart' )
			);

		} else {

			return;

		}
	}

	$images = array();

	if ( $args['size'] ) {

		$crop = $args['size'];

	} else {

		$crop = 'slider-x-large';

		if ( $args['carousel'] ) {

			$crop = 'full';

		}

		/**
		 * Filters the default crop size for gallery slider
		 * images, when none is passed.
		 *
		 * @since Theme_Blvd 2.5.0
		 *
		 * @param string $crop Crop size name, like `thumbnail` or `slider-x-large`.
		 */
		$crop = apply_filters( 'themeblvd_gallery_slider_default_crop', $crop );

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
			'crop'   => $crop,
			'alt'    => $attachment->post_title,
			'src'    => $img[0],
			'width'  => $img[1],
			'height' => $img[2],
			'thumb'  => $thumb[0],
			'title'  => $title,
			'desc'   => $caption,
		);

	}

	$options = $args;

	$options['crop'] = $crop;

	unset( $options['size'], $options['title'], $options['caption'] );

	// Get slider output.
	$output = themeblvd_get_simple_slider( $images, $options );

	/**
	 * Filters the final HTML output for a gallery
	 * slider block.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param string $output Final HTML output.
	 * @param int    $post_id ID of current post.
	 * @param array  $attachments Attachment posts (i.e. images) used in gallery.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string      $size         Crop size for all images in slider.
	 *     @type string|bool $carousel     Whether to use variable-width carousel.
	 *     @type string|int  $interval     Seconds between auto-rotation transitions; use `false` for no auto-rotation to be printed for JavaScript.
	 *     @type string      $pause        Whether to pause slider on hover, use string `true` or `false` to be printed for JavaScript.
	 *     @type string      $wrap         Whether slider should cycle continuously in a loop, use string `true` or `false` to be printed for JavaScript.
	 *     @type bool        $nav_standard Whether to show standard slider navigation dots.
	 *     @type bool        $nav_arrows   Whether to show slider navigation arrows.
	 *     @type string      $arrows       If $nav_arrows is true, style of arrows, `standard` or `mini`.
	 *     @type bool        $nav_thumbs   Whether to show slider thumbnail navigation.
	 *     @type string|int  $thumb_size   If $nav_thumbs is true, thumb size, `small`, `smaller`, `smallest` or custom integer.
	 *     @type bool        $title        Display title of image attachments on slides.
	 *     @type bool        $caption      Display captions of image attachments on slides.
	 *     @type bool        $dark_text    Whether to use dark text for title, descriptions and navigation; use when images are light.
	 * }
	 */
	return apply_filters( 'themeblvd_gallery_slider', $output, $post_id, $attachments, $args );

}

/**
 * Display a gallery slider block.
 *
 * @since Theme_Blvd 2.3.0
 *
 * @param string $gallery Gallery shortcode instance, like `[gallery ids="1,2,3"]`.
 * @param array  $args    Block arguments, see themeblvd_get_gallery_slider() docs.
 */
function themeblvd_gallery_slider( $gallery = '', $args = array() ) {

	echo themeblvd_get_gallery_slider( $gallery, $args );

}

/**
 * Get a mini gallery slider block.
 *
 * This is basically just a wrapper for themeblvd_get_gallery_slider(),
 * which passes some preset arguments to condense the
 * look of the slider for smaller situations.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param string $gallery Gallery shortcode instance, like `[gallery ids="1,2,3"]`.
 * @param array  $args    Slider arguments; see docs for themeblvd_get_gallery_slider().
 */
function themeblvd_get_mini_gallery_slider( $gallery = '', $args = array() ) {

	/**
	 * Filters the default arguments used for a mini
	 * gallery slider.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param array Arguments passed to themeblvd_get_gallery_slider().
	 */
	$defaults = apply_filters( 'themeblvd_mini_gallery_slider_args', array(
		'class'      => 'mini',
		'carousel'   => false,
		'arrows'     => 'mini',
		'nav_thumbs' => false,
	) );

	$args = wp_parse_args( $args, $defaults );

	return themeblvd_get_gallery_slider( $gallery, $args );

}

/**
 * Display a mini gallery slider block.
 *
 * @since Theme_Blvd 2.3.0
 *
 * @param string $gallery Gallery shortcode instance, like `[gallery ids="1,2,3"]`.
 * @param array  $args    Slider arguments; see docs for themeblvd_get_gallery_slider().
 */
function themeblvd_mini_gallery_slider( $gallery = '', $args = array() ) {

	echo themeblvd_get_mini_gallery_slider( $gallery, $args );

}

/**
 * Get a simple slider block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $images Images for slider.
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string      $id                 Unique ID for current slider.
 *     @type string      $crop               Crop size for all images in slider.
 *     @type string|bool $carousel           Whether to use variable-width carousel.
 *     @type string|int  $interval           Seconds between auto-rotation transitions; use `false` for no auto-rotation to be printed for JavaScript.
 *     @type string      $pause              Whether to pause slider on hover, use string `true` or `false` to be printed for JavaScript.
 *     @type string      $wrap               Whether slider should cycle continuously in a loop, use string `true` or `false` to be printed for JavaScript.
 *     @type string|bool $nav_standard       Whether to show standard slider navigation dots.
 *     @type string|bool $nav_arrows         Whether to show slider navigation arrows.
 *     @type string      $arrows             If $nav_arrows is true, style of arrows, `standard` or `mini`.
 *     @type string|bool $nav_thumbs         Whether to show slider thumbnail navigation.
 *     @type string|int  $thumb_size         If $nav_thumbs is true, thumb size, `small`, `smaller`, `smallest` or custom integer.
 *     @type string|bool $shade              Whether entire image is shaded, to allow overlaid text display more clearly.
 *     @type string|bool $thumb_link         Whether linked slides have animated hover overlay effect.
 *     @type string|bool $dark_text          Whether to use dark text for title, descriptions and navigation; use when images are light.
 *     @type string|bool $caption_bg         Whether to add background color to caption.
 *     @type string      $caption_bg_color   If $caption_bg is true, background color for caption, like `#000`.
 *     @type string      $caption_bg_opacity If $caption_bg is true, background color opacity, like `0.5`, `1`, etc.
 *     @type string      $class              Any additional CSS classes to add, like `foo bar baz`.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_simple_slider( $images, $args = array() ) {

	$output = '';

	$defaults = array(
		'id'                 => uniqid( 'slider_' . rand() ),
		'crop'               => 'slider-large',
		'carousel'           => '0',
		'interval'           => '5',
		'pause'              => '1',
		'wrap'               => '1',
		'nav_standard'       => '1',
		'nav_arrows'         => '1',
		'arrows'             => 'standard',
		'nav_thumbs'         => '0',
		'thumb_size'         => 'smaller',
		'shade'              => '0',
		'thumb_link'         => '1',
		'dark_text'          => '0',
		'caption_bg'         => '0',
		'caption_bg_color'   => '#000000',
		'caption_bg_opacity' => '0.5',
		'class'              => '',
	);

	/**
	 * Filters arguments used for all simple sliders.
	 *
	 * Note: This filter is applied AFTER default arguments
	 * have been merged with wp_parse_args().
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param array Arguments used to build simple slider.
	 */
	$args = apply_filters( 'themeblvd_simple_slider_args', wp_parse_args( $args, $defaults ) );

	// Make sure $images array is setup properly.
	foreach ( $images as $img_id => $img ) {

		$images[ $img_id ] = wp_parse_args( $img, array(
			'crop'         => $args['crop'],
			'id'           => 0,
			'alt'          => '',
			'src'          => '',
			'width'        => 0,
			'height'       => 0,
			'thumb'        => '',
			'title'        => '',
			'desc'         => '',
			'desc_wpautop' => '1',
			'link'         => '',
			'link_url'     => '',
			'addon'        => '',
		) );
	}

	$interval = $args['interval'];

	if ( $interval && 'false' !== $interval && intval( $interval ) < 100 ) {

		$interval .= '000'; // User has inputted seconds; so we convert to milliseconds.

	}

	$pause = '';

	if ( $args['pause'] ) {

		$pause = 'hover';

	}

	$class = 'tb-simple-slider carousel slide';

	if ( $args['carousel'] ) {

		$class .= ' tb-gallery-carousel';

	}

	if ( $args['class'] ) {

		$class .= ' ' . $args['class'];

	}

	if ( $args['dark_text'] ) {

		$class .= ' dark-text';

	}

	if ( $args['caption_bg'] ) {

		$class .= ' has-caption-bg';

	}

	if ( $args['nav_arrows'] ) {

		$class .= ' nav-arrows-' . $args['arrows'];

	}

	if ( $args['nav_standard'] ) {

		$class .= ' has-nav-dots';

	}

	if ( $args['nav_thumbs'] ) {

		$class .= ' has-nav-thumbs';

	}

	$output .= sprintf(
		'<div id="%s" class="%s" data-ride="carousel" data-interval="%s" data-pause="%s" data-wrap="%s">',
		esc_attr( $args['id'] ),
		esc_attr( $class ),
		esc_attr( $interval ),
		esc_attr( $pause ),
		esc_attr( $args['wrap'] )
	);

	if ( $args['carousel'] ) {

		$output = str_replace( ' data-ride="carousel"', '', $output ); // A hack to disable bootstrap carousel, because we're using owl carousel.

	}

	$output .= '<div class="carousel-control-wrap">';

	// Standard nav indicators.
	if ( $args['nav_standard'] ) {

		$output .= '<ol class="carousel-indicators">';

		$counter = 0;

		foreach ( $images as $img ) {

			$class = '';

			if ( 0 === $counter ) {
				$class = 'active';
			}

			$output .= sprintf(
				'<li data-target="#%s" data-slide-to="%s" class="%s"></li>',
				esc_attr( $args['id'] ),
				$counter,
				$class
			);

			$counter++;
		}

		$output .= '</ol>';

	}

	// Build slides.
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

				if ( 'image' === $img['link'] || 'video' === $img['link'] ) {

					$class .= ' ' . $img['link'];

				} elseif ( '_self' === $img['link'] ) {

					$class .= ' post';

				} elseif ( '_blank' === $img['link'] ) {

					$class .= ' external';

				}
			}

			if ( 0 === $counter ) {

				$class .= ' active';

			}

			$output .= sprintf( '<div class="%s">', $class );

			if ( $img['link'] ) {

				// Add icon needed for hover effect?
				if ( $args['thumb_link'] ) {

					$output .= themeblvd_get_thumbnail_link_icon( $img['link'] );

				}

				if ( 'image' === $img['link'] || 'video' === $img['link'] ) {

					$output .= themeblvd_get_link_to_lightbox( array(
						'item'  => '',
						'link'  => $img['link_url'],
						'title' => $img['alt'],
						'class' => 'slide-link',
					) );

				} else {

					$output .= sprintf(
						'<a href="%s" title="%s" class="slide-link" target="%s"></a>',
						esc_url( $img['link_url'] ),
						esc_attr( $img['alt'] ),
						esc_attr( $img['link'] )
					);

				}
			}

			if ( $args['shade'] ) {

				$output .= '<div class="bg-shade"></div>';

			}

			$img_src = $img['src'];

			if ( is_ssl() ) {

				$img_src = str_replace( 'http://', 'https://', $img_src );

			}

			$output .= sprintf( '<img src="%s" alt="%s"', esc_url( $img_src ), esc_attr( $img['alt'] ) );

			if ( $img['width'] ) {

				$output .= sprintf( ' width="%s"', $img['width'] );

			}

			if ( $img['height'] ) {

				$output .= sprintf( ' height="%s"', $img['height'] );

			}

			$output .= ' />';

			if ( $img['title'] || $img['desc'] ) {

				$caption_style = '';

				if ( $args['caption_bg'] ) {

					$caption_style = sprintf(
						'background-color: %s; background-color: %s;',
						$args['caption_bg_color'],
						themeblvd_get_rgb( $args['caption_bg_color'], $args['caption_bg_opacity'] )
					);

				}

				$output .= '<div class="carousel-caption" style="' . esc_attr( $caption_style ) . '">';

				if ( $img['title'] ) {

					$output .= sprintf( '<h3>%s</h3>', themeblvd_kses( $img['title'] ) );

				}

				if ( $img['desc'] ) {

					if ( $img['desc_wpautop'] ) {

						$output .= wpautop( themeblvd_kses( $img['desc'] ) );

					} else {

						$output .= themeblvd_kses( $img['desc'] );

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

	// Add navigation arrows.
	if ( $args['nav_arrows'] ) {

		$output .= themeblvd_get_slider_controls( array(
			'carousel' => $args['id'],
			'color'    => 'trans',
		) );

	}

	$output .= '</div><!-- .carousel-control-wrap (end) -->';

	// Add thumbnail navigation.
	if ( $args['nav_thumbs'] ) {

		$size = $args['thumb_size'];

		switch ( $size ) {
			case 'small':
				$size = '130';
				break;

			case 'smaller':
				$size = '70';
				break;

			case 'smallest':
				$size = '45';

		}

		$output .= '<ol class="carousel-thumb-nav list-unstyled clearfix">';

		$counter = 0;

		foreach ( $images as $img ) {

			$class = '';

			if ( 0 === $counter ) {
				$class = 'active';
			}

			$output .= sprintf(
				'<li data-target="#%s" data-slide-to="%s" class="%s">',
				esc_attr( $args['id'] ),
				$counter,
				$class
			);

			$output .= sprintf(
				'<img src="%s" alt="%s" width="%s" />',
				esc_url( $img['thumb'] ),
				esc_attr( $img['alt'] ),
				$size
			);

			$output .= '</li>';

			$counter++;

		}

		$output .= "</ol>\n";
	}

	$output .= '</div><!-- .carousel (end) -->';

	/**
	 * Filters the final HTML output for a simple slider
	 * block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string      $id                 Unique ID for current slider.
	 *     @type string      $crop               Crop size for all images in slider.
	 *     @type string|bool $carousel           Whether to use variable-width carousel.
	 *     @type string|int  $interval           Seconds between auto-rotation transitions; use `false` for no auto-rotation to be printed for JavaScript.
	 *     @type string      $pause              Whether to pause slider on hover, use string `true` or `false` to be printed for JavaScript.
	 *     @type string      $wrap               Whether slider should cycle continuously in a loop, use string `true` or `false` to be printed for JavaScript.
	 *     @type string|bool $nav_standard       Whether to show standard slider navigation dots.
	 *     @type string|bool $nav_arrows         Whether to show slider navigation arrows.
	 *     @type string      $arrows             If $nav_arrows is true, style of arrows, `standard` or `mini`.
	 *     @type string|bool $nav_thumbs         Whether to show slider thumbnail navigation.
	 *     @type string|int  $thumb_size         If $nav_thumbs is true, thumb size, `small`, `smaller`, `smallest` or custom integer.
	 *     @type string|bool $shade              Whether entire image is shaded, to allow overlaid text display more clearly.
	 *     @type string|bool $link               Whether linked slides have animated hover overlay effect.
	 *     @type string|bool $dark_text          Whether to use dark text for title, descriptions and navigation; use when images are light.
	 *     @type string|bool $caption_bg         Whether to add background color to caption.
	 *     @type string      $caption_bg_color   If $caption_bg is true, background color for caption, like `#000`.
	 *     @type string      $caption_bg_opacity If $caption_bg is true, background color opacity, like `0.5`, `1`, etc.
	 *     @type string      $class              Any additional CSS classes to add, like `foo bar baz`.
	 * }
	 * @param array  $images Images for slider.
	 */
	return apply_filters( 'themeblvd_simple_slider', $output, $args, $images );

}

/**
 * Display a simple slider block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $images Images for slider.
 * @param array $args   Block arguments, see themeblvd_get_simple_slider() docs.
 */
function themeblvd_simple_slider( $images, $args = array() ) {

	echo themeblvd_get_simple_slider( $images, $args );

}

/**
 * Get an image block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $img_atts {
 *     Image attributes.
 *
 *     @type int    $id    Attachment ID of image.
 *     @type string $src   Source URL of image.
 *     @type string $full  URL to enlarged version of image.
 *     @type string $title Title of image.
 *     @type string $crop  Crop size for image.
 * }
 * @param  array  $args {
 *     Additional block arguments.
 *
 *     @type string $link     How to link image, `none`, `image`, `full` or `video`.
 *     @type string $link_url If $link != `none`, the URL where image links.
 *     @type string $align    Alignment for image block, `left`, `right` or `center`.
 *     @type string $title    Title of image; this will take precedence over $img_atts['title'].
 *     @type string $width    Maximum display width of image block, like `100`, `100px`, `50%`, etc; if unitless, will get "px" appended.
 *     @type string $class    CSS classes to add, like `foo bar baz`.
 * }
 * @return string $output Final image HTML to output
 */
function themeblvd_get_image( $img_atts, $args = array() ) {

	$img_atts = wp_parse_args( $img_atts, array(
		'id'    => 0,
		'src'   => '',
		'full'  => '',
		'title' => '',
		'crop'  => '',
	) );

	$args = wp_parse_args( $args, array(
		'link'     => 'none',
		'link_url' => '',
		'align'    => 'none',
		'title'    => '',
		'width'    => '',
		'class'    => '',
	) );

	// Is this image going to be linked?
	$has_link = false;

	if ( 'none' !== $args['link'] ) {

		$has_link = true;

	}

	// Does this image have a display width?
	$width = esc_attr( $args['width'] );

	if ( $width && false === strpos( $args['width'], '%' ) && false === strpos( $args['width'], 'px' ) ) {

		$width .= 'px';

	}

	$img_class = 'wp-image-' . $img_atts['id'];

	if ( ! $has_link ) {

		if ( in_array( $args['align'], array( 'left', 'center', 'right' ) ) ) {

			$img_class .= ' align' . $args['align'];

		} else {

			$img_class .= ' alignnone';

		}
	}

	if ( ! $has_link && ! empty( $args['class'] ) ) {

		$img_class .= ' ' . $args['class'];

	}

	$img_src = $img_atts['src'];

	if ( is_ssl() ) {

		$img_src = str_replace( 'http://', 'https://', $img_src );

	}

	if ( $args['title'] ) {

		$title = $args['title'];

	} else {

		$title = $img_atts['title'];

	}

	$img = sprintf(
		'<img src="%s" alt="%s" class="%s" ',
		esc_url( $img_src ),
		esc_attr( $title ),
		esc_attr( $img_class )
	);

	if ( $width ) {

		$img .= sprintf( 'style="width:%s;" ', $width );

	}

	$img .= '/>';

	// Start output
	$output = $img;

	// Wrap image in link
	if ( $has_link ) {

		$output .= themeblvd_get_thumbnail_link_icon( $args['link'] );

		$anchor_classes = 'tb-thumb-link';

		if ( in_array( $args['align'], array( 'left', 'center', 'right' ) ) ) {

			$anchor_classes .= ' align' . $args['align'];

		}

		if ( ! empty( $args['class'] ) ) {

			$anchor_classes .= ' ' . $args['class'];

		}

		if ( 'image' === $args['link'] || 'full' === $args['link'] || 'video' === $args['link'] ) {

			if ( 'image' === $args['link'] || 'full' === $args['link'] ) {

				$anchor_classes .= ' image';

			} else {

				$anchor_classes .= ' video';

			}

			if ( 'full' === $args['link'] ) {

				$link = $img_atts['full'];

			} else {

				$link = $args['link_url'];

			}

			$props = array();

			$output = themeblvd_get_link_to_lightbox( array(
				'item'      => $output,
				'link'      => $link,
				'title'     => $title,
				'props'     => $props,
				'class'     => $anchor_classes,
			) );

		} else {

			if ( '_self' === $args['link'] ) {

				$anchor_classes .= ' post';

			} elseif ( '_blank' === $args['link'] ) {

				$anchor_classes .= ' external';

			}

			$style = '';

			$output = sprintf(
				'<a href="%s" class="%s" title="%s" target="%s" style="%s">%s</a>',
				esc_url( $args['link_url'] ),
				esc_attr( $anchor_classes ),
				esc_attr( $title ),
				esc_attr( $args['link'] ),
				esc_attr( $style ),
				$output
			);

		}
	}

	// Wrap image in max-width div?
	if ( $width ) {

		$output = sprintf(
			'<div class="display-width %s" style="max-width:%s;">%s</div>',
			esc_attr( $args['align'] ),
			$width,
			$output
		);

	}

	/**
	 * Filters the final HTML output for an image
	 * block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output   Final HTML output.
	 * @param string $img      HTML for the <img> before being wrapped in a link.
	 * @param array  $img_atts {
	 *     Image attributes.
	 *
	 *     @type int    $id    Attachment ID of image.
	 *     @type string $src   Source URL of image.
	 *     @type string $full  URL to enlarged version of image.
	 *     @type string $title Title of image.
	 *     @type string $crop  Crop size for image.
	 * }
	 * @param array  $args {
	 *     Additional block arguments.
	 *
	 *     @type string $link     How to link image, `none`, `image`, `full` or `video`.
	 *     @type string $link_url If $link != `none`, the URL where image links.
	 *     @type string $align    Alignment for image block, `left`, `right` or `center`.
	 *     @type string $title    Title of image; this will take precedence over $img_atts['title'].
	 *     @type string $width    Maximum display width of image block, like `100`, `100px`, `50%`, etc; if unitless, will get "px" appended.
	 *     @type string $class    CSS classes to add, like `foo bar baz`.
	 * }
	 */
	return apply_filters( 'themeblvd_image', $output, $img, $img_atts, $args );

}

/**
 * Display an image block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $img_atts Image attributes, see themeblvd_get_image() docs.
 * @param array $args     Additional block arguments, see themeblvd_get_image() docs.
 */
function themeblvd_image( $img_atts, $args = array() ) {

	echo themeblvd_get_image( $img_atts, $args );

}

/**
 * Get a video block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $video_url URL to video, compatible with WordPress's oEmbed.
 * @param  array  $args      Block arguments, currently not used.
 * @return string $output    Final HTML output for block.
 */
function themeblvd_get_video( $video_url, $args = array() ) {

	/*
	 * Use WordPress's oEmbed to embed video.
	 *
	 * By default, the framework has oEmbed hooked to the
	 * `themeblvd_the_content` filter, which gets applied
	 * through themeblvd_get_content().
	 *
	 * So as long as that remains in place, the following
	 * method should work for embedding a video, as long
	 * as an oEmbed-compatible URL has been passed.
	 */
	$output = themeblvd_get_content( $video_url );

	/**
	 * Filters the final HTML output for a video block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output    Final HTML output.
	 * @param string $video_url URL to video, compatible with WordPress's oEmbed.
	 * @param array  $args      Block arguments, currently not used.
	 */
	return apply_filters( 'themeblvd_video', $output, $video_url, $args );

}

/**
 * Display a video block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param string $video_url URL to video, compatible with WordPress's oEmbed.
 * @param array  $args      Block arguments, currently not used.
 */
function themeblvd_video( $video_url, $args = array() ) {

	echo themeblvd_get_video( $video_url, $args );

}
