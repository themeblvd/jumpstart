<?php
/**
 * The post thumbnail (must be within the loop)
 *
 * @since 2.0.0
 *
 * @param string $location Where the thumbnail is being used -- primary, featured, single -- sort of a wild card to build on in the future as conflicts arise.
 * @param string $size For the image crop size of the thumbnail
 * @param bool $link Set to false to force a thumbnail to ignore post's Image Link options
 * @param bool $allow_filters Whether to allow general filters on the thumbnail or not
 * @param string $gallery If thumb is linking to gallery, specify the prettyPhoto extension rel="themeblvd_lightbox[gallery]" -- i.e. the "gallery" part
 * @return string $output HTML to output thumbnail
 */
if ( ! function_exists( 'themeblvd_get_post_thumbnail' ) ) {
	function themeblvd_get_post_thumbnail( $location = 'primary', $size = '', $link = true, $allow_filters = true, $gallery = 'gallery' ) {

		global $post;

		$attachment_id = get_post_thumbnail_id( $post->ID );
		$sidebar_layout = themeblvd_config( 'sidebar_layout' );
		$link_target = '';
		$link_url = '';
		$end_link = '';
		$output = '';
		$classes = '';
		$image = '';
		$title = '';

		// If no thumbnail, we can skip everything. However,
		// we still want plugins to be able to filter in here
		// however they want. This same filter is applied below
		// on the final output.
		if ( ! has_post_thumbnail( $post->ID ) && $allow_filters )
			return apply_filters( 'themeblvd_post_thumbnail', '', $location, $size, $link );

		// Determine correct thumbnail size string, or if wasn't
		// passed in, get a fallback based on framework options.
		$size = themeblvd_get_thumbnail_size( $size, $location, $sidebar_layout );

		// If $size was set to null, it means the post
		// thumb should be hidden. So, return nothing.
		if ( $size === null )
			return $output;

		// Can we just skip the featured image?
		$thumb_link_meta = get_post_meta( $post->ID, '_tb_thumb_link', true ); // used below in determining featured image link
		if ( $thumb_link_meta == 'inactive' )
			$link = false;

		// How about skipping featured image link on the single post?
		if ( $link && $location == 'single' && get_post_meta( $post->ID, '_tb_thumb_link_single', true ) == 'no' )
			$link = false;

		// Determine link for featured image
		if ( $link ) {
			$possible_link_options = array( 'post', 'thumbnail', 'image', 'video', 'external' );
			if ( in_array( $thumb_link_meta, $possible_link_options ) ) {
				switch( $thumb_link_meta ) {

					case 'post' :
						$link_url = get_permalink( $post->ID );
						break;

					case 'thumbnail' :
						$link_url = wp_get_attachment_url( $attachment_id );
						$link_target = ' rel="featured_themeblvd_lightbox"';
						if ( $gallery )
							$link_target = str_replace( 'featured_themeblvd_lightbox', 'featured_themeblvd_lightbox['.$gallery.']', $link_target );
						break;

					case 'image' :
						$link_url = get_post_meta( $post->ID, '_tb_image_link', true );
						$link_target = ' rel="featured_themeblvd_lightbox"';
						if ( $gallery )
							$link_target = str_replace( 'featured_themeblvd_lightbox', 'featured_themeblvd_lightbox['.$gallery.']', $link_target );
						break;

					case 'video' :
						$link_url = get_post_meta( $post->ID, '_tb_video_link', true );
						$link_target = ' rel="featured_themeblvd_lightbox"';
						if ( $gallery )
							$link_target = str_replace( 'featured_themeblvd_lightbox', 'featured_themeblvd_lightbox['.$gallery.']', $link_target );
						// WP oEmbed for non YouTube and Vimeo videos
						if ( ! themeblvd_prettyphoto_supported_link( $link_url ) ) {
							$id = uniqid('inline-video-');
							$output .= sprintf( '<div id="%s" class="hide">%s</div>', $id, wp_oembed_get($link_url) );
							$link_url = "#{$id}";
						}
						break;

					case 'external' :
						$link_url = get_post_meta( $post->ID, '_tb_external_link', true );
						$target = get_post_meta( $post->ID, '_tb_external_link_target', true );
						if ( ! $target )
							$target = '_blank';
						$link_target = ' target="'.$target.'"';
						break;
				}
			} else {
				$link = false;
			}
		}

		// Attributes
		$size_class = $size;

		if ( $size_class == 'tb_small' )
			$size_class = 'small';

		$classes = 'attachment-'.$size_class.' wp-post-image';

		if ( ! $link ) {
			$classes .= ' thumbnail';
		} else {
			if ( is_single() )
				$title = ' title="'.get_the_title($post->ID).'"';
			$anchor_class = 'thumbnail';
			if ( $thumb_link_meta != 'thumbnail' )
				$anchor_class .= ' '.$thumb_link_meta;
		}

		// Image with link
		$image = get_the_post_thumbnail( $post->ID, $size, array( 'class' => '' ) );
		if ( $link )
			$image = sprintf('<a href="%s"%s class="%s"%s>%s%s</a>', $link_url, $link_target, $anchor_class, $title, $image, themeblvd_get_image_overlay() );

		// Final HTML output
		$output .= '<div class="featured-image-wrapper '.$classes.'">';
		$output .= '<div class="featured-image">';
		$output .= '<div class="featured-image-inner">';
		$output .= $image;
		$output .= '</div><!-- .featured-image-inner (end) -->';
		$output .= '</div><!-- .featured-image (end) -->';
		$output .= '</div><!-- .featured-image-wrapper (end) -->';

		// Apply filters if allowed
		if ( $allow_filters )
			$output = apply_filters( 'themeblvd_post_thumbnail', $output, $location, $size, $link );

		// Return final output
		return $output;
	}
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
if ( ! function_exists( 'themeblvd_get_thumbnail_size' ) ) {
	function themeblvd_get_thumbnail_size( $size = '', $location = 'primary', $sidebar_layout = 'full_width' ) {

		// If no $size was passed in, we'll use the framework's options
		// to determine one for different scenarios.
		if ( ! $size ) {
			if ( themeblvd_was('home') || themeblvd_was('page_template', 'template_list.php') ) {

				// "Primary Posts Display" (i.e. homepage or post list template)
				$size = themeblvd_get_option( 'blog_thumbs' );

			} else if ( themeblvd_was('search') || themeblvd_was('archive') ) {

				// Search results and archives
				$size = themeblvd_get_option( 'archive_thumbs' );

			} else if ( themeblvd_was('single') ) {

				// Single posts. First check for overrding meta value, then
				// move to default option from theme options page.
				$size_meta = get_post_meta( get_the_ID(), '_tb_thumb', true );
				if ( $size_meta == 'full' || $size_meta == 'small' || $size_meta == 'hide' )
					$size = $size_meta;
				else
					$size = themeblvd_get_option( 'single_thumbs' );

			}
		}

		if ( $size == 'hide' )
			$size = null;

		if ( $size == 'full' )
			$location == 'featured' || $sidebar_layout == 'full_width' ? $size = 'tb_large' : $size = 'tb_medium';

		if ( $size == 'small' )
			$size = 'tb_small';

		return apply_filters( 'themeblvd_get_thumbnail_size', $size, $location, $sidebar_layout );
	}
}

/**
 * Add wrapper around embedded videos to allow for respnsive videos.
 *
 * @since 2.0.0
 */
if ( ! function_exists( 'themeblvd_oembed_result' ) ) {
	function themeblvd_oembed_result( $input, $url ) {

		// If this is a tweet, keep on movin' fella. @todo Create filterable list of items to skip other than twitter.com
		if ( strpos( $url, 'twitter.com' ) )
			return $input;

		// Since the framework applies this filter in two
		// spots, we must first check if the filter has
		// been applied or not. The reason for this is
		// because WP has issues with caching the oembed
		// result, and oembed_result doesn't always get
		// applied when it's supposed to.
		if ( strpos( $input, 'themeblvd-video-wrapper' ) )
			return $input;

		// Apply YouTube wmode fix
		if ( strpos( $url, 'youtube' ) || strpos( $url, 'youtu.be' ) ) {
			if ( ! strpos( $input, 'wmode=transparent' ) )
				$input = str_replace('feature=oembed', 'feature=oembed&wmode=transparent', $input);
		}

		// Wrap output
		$output  = '<div class="themeblvd-video-wrapper">';
		$output .= '<div class="video-inner">';
		$output .= $input;
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}

/**
 * Add 100% width to <audio> tag of WP's built-in
 * audio player to make it responsive.
 *
 * @since 2.2.1
 *
 * @param string $html HTML for output to be filtered
 */
if ( ! function_exists( 'themeblvd_audio_shortcode' ) ) {
	function themeblvd_audio_shortcode( $html ){
		return str_replace( '<audio', '<audio width="100%"', $html );
	}
}

/**
 * Gallery slider
 *
 * @since 2.3.0
 *
 * @param string $gallery Optional gallery shortcode usage like [gallery ids="1,2,3,4"]
 * @param string $type Type of slider, supports nivo or standard
 * @param string $size Image crop size for attachment images
 */
if ( ! function_exists( 'themeblvd_gallery_slider' ) ) {
	function themeblvd_gallery_slider( $gallery = '', $type = 'standard', $size = 'full' ) {
		echo themeblvd_get_gallery_slider( $gallery, $type, $size );
	}
}

/**
 * Get gallery slider
 *
 * @since 2.3.0
 *
 * @param string $gallery Optional gallery shortcode usage like [gallery ids="1,2,3,4"]
 * @param string $type Type of slider, supports nivo or standard
 * @param string $size Image crop size for attachment images
 * @return string $output Final HTML to output
 */
if ( ! function_exists( 'themeblvd_get_gallery_slider' ) ) {
	function themeblvd_get_gallery_slider( $gallery = '', $type = 'standard', $size = 'full' ) {

		$post_id = get_the_ID();
		$type = apply_filters( 'themeblvd_gallery_slider_type', $type, $post_id );
		$size = apply_filters( 'themeblvd_gallery_slider_size', $size, $post_id );

		// Did user pass in a gallery shortcode?
		if ( $gallery )
			$content = $gallery;
		else
			$content = get_the_content();

		// Did user insert a gallery like [gallery ids="1,2,3,4"]
		// via $gallery param or anywhere in post?
		$attachments = array();
		$pattern = get_shortcode_regex();

		if ( preg_match( "/$pattern/s", $content, $match ) && 'gallery' == $match[2] ) {

			$atts = shortcode_parse_atts( $match[3] );

			if ( ! empty( $atts['ids'] ) ) {
				$query = array(
					'post_type'	=> 'attachment',
					'post__in' 	=> explode( ',', $atts['ids'] )
				);
				$attachments = get_posts($query);
			}
		}

		// If no gallery present, pull from attachments of posts
		// (old school way before WP 3.5, less common)
		if ( ! $attachments ) {
			$args = array(
				'post_parent'		=> $post_id,
				'post_status'		=> 'inherit',
				'post_type'			=> 'attachment',
				'post_mime_type'	=> 'image'
			);
			$attachments = get_children( $args );
		}

		// Slider needs 2 or more attachments.
		if ( count( $attachments ) <= 1 ) {
			if ( is_user_logged_in() )
				return sprintf( '<div class="alert warning"><p>%s</p></div>', __( 'Oops! Couldn\'t find a gallery with one or more image attachments. Make sure to insert a gallery into the body of the post or attach some images to the post.', 'themeblvd' ) );
			else
				return;
		}

		// Build javascript properties
		$props = array();

		if ( $type == 'nivo' ) {

			$props = array(
				'effect'			=> 'random',
				'slices'			=> '15',
				'directionNav'		=> 'true',
				'controlNav'		=> 'true',
				'pauseOnHover'		=> 'true',
				'pauseTime'			=> '5000',
				'manualAdvance'		=> 'false'
			);

		} elseif ( $type == 'standard' ) {

			$props = array(
				'animation'			=> 'slide',
				'smoothHeight'		=> 'true',
				'slideshow' 		=> 'false',
				'controlNav' 		=> 'false',
				'slideshowSpeed'	=> '5000',
				'slideshow'			=> 'true',
				'controlsContainer'	=> ".slides-wrapper-{$post_id}",
				'directionNav'		=> 'true',
				'controlNav'		=> 'true'
			);

		}
		$props = apply_filters( 'themeblvd_gallery_slider_'.$type.'_props', $props, $post_id, $attachments );

		$i = 1;
		$count = count( $props );
		$props_output = '';

		foreach( $props as $key => $value ) {

			$fmt = '%s: ';
			if ( $value == 'true' || $value == 'false' || intval($value) )
				$fmt .= '%s'; // for bool or int, don't wrap in quotes
			else
				$fmt .= '"%s"';

			$props_output .= sprintf($fmt, $key, $value);

			if ( $i < $count )
				$props_output .= ",\n";

			$i++;
		}

		// CSS Classes
		$wrap_class = "{$type}-slider-wrapper";
		if ( $type != 'standard' )
			$wrap_class = "tb-{$wrap_class}";

		$class = apply_filters( 'themeblvd_gallery_slider_class', array('show-nav_standard', 'show-nav_arrows') );
		$class = implode( ' ', $class );

		// Slider Wrap
		$slider_wrap  = "<div id=\"gallery-slider-{$post_id}\" class=\"slider-wrapper {$wrap_class} gallery-slider\">\n";
		$slider_wrap .= "	<div class=\"slides-wrapper slides-wrapper-{$type}\">\n";
		$slider_wrap .= "		<div class=\"slides-inner {$class}\">\n";
		$slider_wrap .= "			<div class=\"tb-loader\"></div>\n";
		$slider_wrap .= "				%s\n";
		$slider_wrap .= "		</div><!-- .slides-inner (end) -->\n";
		$slider_wrap .= "	</div><!-- .slides-wrapper (end) -->\n";
		$slider_wrap .= "</div><!-- .gallery-slider (end) -->\n";

		// Start Output
		$output = '';

		if ( $type == 'nivo' ) {

			/*--------------------------------------------*/
			/* Nivo Slider
			/*--------------------------------------------*/

			wp_enqueue_script( 'nivo' ); // add to wp_footer()

			$js  = "<script>\n";
			$js .= "jQuery(document).ready(function($) {\n";
			$js .= "	$(window).load(function() {\n";
			$js .= "		$('#gallery-slider-{$post_id} .nivoSlider').nivoSlider({\n";
			$js .= "			%s\n";
			$js .= "		}).parent().find('.tb-loader').fadeOut();\n";
			$js .= "	});\n";
			$js .= "});\n";
			$js .= "</script>\n\n";

			$output .= sprintf( $js, $props_output );

			$slider  = "<div class=\"slider nivoSlider\">\n";

			foreach( $attachments as $attachment ) {
				$image = wp_get_attachment_image_src( $attachment->ID, $size );
				$slider .= sprintf("<img src=\"%s\" alt=\"%s\" />\n", $image[0], $attachment->post_title);
			}

			$slider .= "</div><!-- .nivoSlider (end) -->\n";

			$output .= sprintf( $slider_wrap, $slider );

		} elseif ( $type == 'standard' ) {

			/*--------------------------------------------*/
			/* Standard Slider
			/*--------------------------------------------*/

			wp_enqueue_script( 'flexslider' ); // add to wp_footer()

			$js  = "<script>\n";
			$js .= "jQuery(document).ready(function($) {\n";
			$js .= "	$(window).load(function() {\n";
			$js .= "		$('#gallery-slider-{$post_id} .flexslider').flexslider({\n";
			$js .= "			%s\n";
			$js .= "		}).parent().find('.tb-loader').fadeOut();\n";
			$js .= "	});\n";
			$js .= "});\n";
			$js .= "</script>\n\n";

			$output .= sprintf( $js, $props_output );

			$slider  = "<div class=\"slider standard-slider flexslider\">\n";
			$slider .= "	<ul class=\"slides\">\n";

			foreach( $attachments as $attachment ) {
				$image = wp_get_attachment_image_src( $attachment->ID, $size );
				$slider .= sprintf("<li><img src=\"%s\" alt=\"%s\" /></li>\n", $image[0], $attachment->post_title);
			}

			$slider .= "	</ul><!-- .slides (end) -->\n";
			$slider .= "</div><!-- .flexslider (end) -->\n";

			$output .= sprintf( $slider_wrap, $slider );

		}

		return apply_filters( 'themeblvd_gallery_slider', $output, $post_id, $type, $attachments );
	}
}