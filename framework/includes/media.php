<?php
/**
 * The post thumbnail (must be within the loop)
 *
 * @since 2.0.0
 *
 * @param string $location Whether the thumbnail is currently in the featured area or not, not always applicable
 * @param string $size Size of post thumbnail
 * @param string $link Where link will go if it's active
 * @param string $link_url URL where link will go if applicable
 * @param boolean $allow_filters Whether to allow filters to be applied or not
 * @return string $output HTML to output thumbnail
 */

if( ! function_exists( 'themeblvd_get_post_thumbnail' ) ) {
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
		if( ! has_post_thumbnail( $post->ID ) && $allow_filters )
			return apply_filters( 'themeblvd_post_thumbnail', '', $location, $size, $link );

		// Determine correct thumbnail size string, or if wasn't 
		// passed in, get a fallback based on framework options.
		$size = themeblvd_get_thumbnail_size( $size, $location, $sidebar_layout );
		
		// If $size was set to null, it means the post 
		// thumb should be hidden. So, return nothing.
		if( $size === null )
			return $output;

		// Can we just skip the featured image?
		$thumb_link_meta = get_post_meta( $post->ID, '_tb_thumb_link', true ); // used below in determining featured image link
		if( $thumb_link_meta == 'inactive' )
			$link = false;

		// How about skipping featured image link on the single post?
		if( $link && $location == 'single' && get_post_meta( $post->ID, '_tb_thumb_link_single', true ) == 'no' )
			$link = false;

		// Determine link for featured image
		if( $link ) {
			$possible_link_options = array( 'post', 'thumbnail', 'image', 'video', 'external' );
			if( in_array( $thumb_link_meta, $possible_link_options ) ) {
				switch( $thumb_link_meta ) {
					
					case 'post' :
						$link_url = get_permalink( $post->ID );
						break;
					
					case 'thumbnail' :
						$link_url = wp_get_attachment_url( $attachment_id );
						$link_target = ' rel="featured_themeblvd_lightbox"';
						if( $gallery )
							$link_target = str_replace( 'featured_themeblvd_lightbox', 'featured_themeblvd_lightbox['.$gallery.']', $link_target );
						break;
					
					case 'image' :
						$link_url = get_post_meta( $post->ID, '_tb_image_link', true );
						$link_target = ' rel="featured_themeblvd_lightbox"';
						if( $gallery )
							$link_target = str_replace( 'featured_themeblvd_lightbox', 'featured_themeblvd_lightbox['.$gallery.']', $link_target );
						break;
					
					case 'video' :
						$link_url = get_post_meta( $post->ID, '_tb_video_link', true );
						$link_target = ' rel="featured_themeblvd_lightbox"';
						if( $gallery )
							$link_target = str_replace( 'featured_themeblvd_lightbox', 'featured_themeblvd_lightbox['.$gallery.']', $link_target );
						// WP oEmbed for non YouTube and Vimeo videos
						if( ! themeblvd_prettyphoto_supported_link( $link_url ) ) {
							$id = uniqid('inline-video-');
							$output .= sprintf( '<div id="%s" class="hide">%s</div>', $id, wp_oembed_get($link_url) );
							$link_url = "#{$id}";
						}
						break;
					
					case 'external' :
						$link_url = get_post_meta( $post->ID, '_tb_external_link', true );
						$target = get_post_meta( $post->ID, '_tb_external_link_target', true );
						if( ! $target )
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
		
		if( $size_class == 'tb_small' )
			$size_class = 'small';
		
		$classes = 'attachment-'.$size_class.' wp-post-image';
		
		if( ! $link ) {
			$classes .= ' thumbnail';
		} else {
			if( is_single() ) 
				$title = ' title="'.get_the_title($post->ID).'"';
			$anchor_class = 'thumbnail';
			if( $thumb_link_meta != 'thumbnail' )
				$anchor_class .= ' '.$thumb_link_meta;
		}
		
		// Image with link
		$image = get_the_post_thumbnail( $post->ID, $size, array( 'class' => '' ) );
		if( $link )
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
		if( $allow_filters )
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

if( ! function_exists( 'themeblvd_get_thumbnail_size' ) ) {
	function themeblvd_get_thumbnail_size( $size = '', $location = 'primary', $sidebar_layout = 'full_width' ) {

		// If no $size was passed in, we'll use the framework's options 
		// to determine one for different scenarios.
		if( ! $size ) {
			if( themeblvd_was('home') || themeblvd_was('page_template', 'template_list.php') ) {
				
				// "Primary Posts Display" (i.e. homepage or post list template)
				$size = themeblvd_get_option( 'blog_thumbs' );
			
			} else if( themeblvd_was('search') || themeblvd_was('archive') ) {
				
				// Search results and archives
				$size = themeblvd_get_option( 'archive_thumbs' );
			
			} else if( themeblvd_was('single') ) {
				
				// Single posts. First check for overrding meta value, then 
				// move to default option from theme options page.
				$size_meta = get_post_meta( $post->ID, '_tb_thumb', true );
				if( $size_meta == 'full' || $size_meta == 'small' || $size_meta == 'hide' )
					$size = $size_meta;
				else
					$size = themeblvd_get_option( 'single_thumbs' );

			}
		}

		if( $size == 'hide' )
			$size = null;
		
		if( $size == 'full' )
			$location == 'featured' || $sidebar_layout == 'full_width' ? $size = 'tb_large' : $size = 'tb_medium';
		
		if( $size == 'small' )
			$size = 'tb_small';

		return apply_filters( 'themeblvd_get_thumbnail_size', $size, $location, $sidebar_layout );
	}
}

/**
 * Add wrapper around embedded videos to allow for respnsive videos.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_oembed_result' ) ) {
	function themeblvd_oembed_result( $input, $url ) {
		
		// If this is a tweet, keep on movin' fella. @todo Create filterable list of items to skip other than twitter.com
		if( strpos( $url, 'twitter.com' ) )
			return $input;
		
		// Since the framework applies this filter in two 
		// spots, we must first check if the filter has 
		// been applied or not. The reason for this is 
		// because WP has issues with caching the oembed 
		// result, and oembed_result doesn't always get 
		// applied when it's supposed to.
		if( strpos( $input, 'themeblvd-video-wrapper' ) ) 
			return $input;
		
		// Apply YouTube wmode fix
		if( strpos( $url, 'youtube' ) || strpos( $url, 'youtu.be' ) ) {
			if( ! strpos( $input, 'wmode=transparent' ) )
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

if( ! function_exists( 'themeblvd_audio_shortcode' ) ) {
	function themeblvd_audio_shortcode( $html ){
		return str_replace( '<audio', '<audio width="100%"', $html );
	}
}