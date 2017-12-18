<?php
/**
 * Media Functions
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.2.0
 */

/**
 * Get a featured image link.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  int    $post_id  ID of post to pull meta data from.
 * @param  int    $thumb_id ID of attachment post set as featured image.
 * @param  string $link     Override pulling type directly from `_tb_thumb_link` meta.
 * @return array  $params {
 *     Link arguments.
 *
 *     @type string $href    Link URL, like `http://google.com`.
 *     @type string $title   Link title.
 *     @type string $target  Link target, like `_self` or `_blank`.
 *     @type string $class   Link CSS class(es) separated by spaces.
 *     @type string $tooltip Link help text, not currently used anywhere.
 * }
 */
function themeblvd_get_post_thumbnail_link( $post_id = 0, $thumb_id = 0, $link = '' ) {

	if ( false === $link ) {

		return false;

	}

	if ( ! $post_id ) {

		$post_id = get_the_ID();

	}

	$params = array(
		'href'    => '',
		'title'   => '',
		'target'  => '',
		'class'   => 'featured-image tb-thumb-link',
		'tooltip' => '',
	);

	if ( ! $link ) {

		$link = get_post_meta( $post_id, '_tb_thumb_link', true );

	}

	if ( 'inactive' === $link || ! $link ) {

		return false;

	}

	if ( ! $thumb_id && ( 'thumbnail' === $link || 'video' === $link ) ) {

		$thumb_id = get_post_thumbnail_id( $post_id );

	}

	switch ( $link ) {

		// Link to post's permalink.
		case 'post':
			$params['href'] = get_permalink( $post_id );

			$params['title'] = get_the_title();

			$params['target'] = '_self';

			$params['class'] .= ' post';

			$params['tooltip'] = themeblvd_get_local( 'view_item' );

			break;

		// Linked to enlarged version of the current featured image in a lightbox.
		case 'thumbnail':
			$enlarge = wp_get_attachment_image_src( $thumb_id, 'tb_x_large' );

			$params['href'] = $enlarge[0];

			$params['title'] = get_the_title( $thumb_id );

			$params['target'] = 'lightbox';

			$params['class'] .= ' image';

			$params['tooltip'] = themeblvd_get_local( 'enlarge' );

			break;

		// Link to an inputted image URL in a lightbox.
		case 'image':
			$params['href'] = get_post_meta( $post_id, '_tb_image_link', true );

			$params['title'] = get_the_title();

			$params['target'] = 'lightbox';

			$params['class'] .= ' image';

			$params['tooltip'] = themeblvd_get_local( 'enlarge' );

			break;

		// Link to a Vimeo or YouTube video in a lightbox.
		case 'video':
			$params['href'] = get_post_meta( $post_id, '_tb_video_link', true );

			$params['title'] = get_the_title( $thumb_id );

			$params['target'] = 'lightbox';

			$params['class'] .= ' video';

			$params['tooltip'] = themeblvd_get_local( 'play' );

			break;

		// Link to an external URL.
		case 'external':
			$params['href'] = get_post_meta( $post_id, '_tb_external_link', true );

			$params['title'] = get_the_title();

			$params['target'] = get_post_meta( $post_id, '_tb_external_link_target', true );

			$params['class'] .= ' external';

			$params['tooltip'] = themeblvd_get_local( 'go_to_link' );

	}

	/**
	 * Filters the classes added to featured
	 * image links.
	 *
	 * Note: This filter is deprecated; use
	 * `themeblvd_post_thumbnail_link` instead.
	 *
	 * @since @@name-framework 2.2.0
	 * @deprecated @@name-framework 2.5.0
	 *
	 * @param string $class    CSS class(es), like `foo bar baz`.
	 * @param int    $post_id  Post ID.
	 * @param int    $thumb_ID Attachment ID.
	 */
	$params['class'] = apply_filters( 'themeblvd_post_thumbnail_a_class', $params['class'], $post_id, $thumb_id );

	/**
	 * Filters the link parameters for a featured
	 * image.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array  $params {
	 *     Link arguments.
	 *
	 *     @type string $href    Link URL, like `http://google.com`.
	 *     @type string $title   Link title.
	 *     @type string $target  Link target, like `_self` or `_blank`.
	 *     @type string $class   Link CSS class(es) separated by spaces.
	 *     @type string $tooltip Link help text, not currently used anywhere.
	 * }
	 * @param int    $post_id  ID of post to pull meta data from.
	 * @param string $link     Link type, from `_tb_thumb_link` meta.
	 */
	return apply_filters( 'themeblvd_post_thumbnail_link', $params, $post_id, $link );

}

/**
 * Get a placeholder for the featured image.
 *
 * When displaying featured images, some post
 * displays require that something show. This
 * function provides an alternative placeholder
 * display when a featured image doesn't exist.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $args {
 *     Placeholder arguments.
 *
 *     @type string     $format Post format.
 *     @type string     $type   Type of placeholder, not currently used.
 *     @type string|int $width  Width of placeholder.
 *     @type string|int $height Height of placeholder.
 *     @type string     $link   Placeholder link URL, like `http://google.com`.
 *     @type string     $title  If $link, title for link.
 * }
 * @return string $output Final HTML output for placeholder.
 */
function themeblvd_get_media_placeholder( $args = array() ) {

	$args = wp_parse_args( $args, array(
		'format' => get_post_format(),
		'type'   => themeblvd_get_att( 'placeholder' ),
		'width'  => themeblvd_get_att( 'crop_w' ),
		'height' => themeblvd_get_att( 'crop_h' ),
		'link'   => '',
		'title'  => '',
	));

	$class = 'placeholder-wrap featured-image';

	$height = ( 9 / 16 ) * 100; // 16:9

	if ( intval( $args['height'] ) > 0 && intval( $args['height'] ) < 9999 && intval( $args['width'] ) > 0 ) {

		$height = ( intval( $args['height'] ) / intval( $args['width'] ) ) * 100;

	}

	$height = strval( $height ) . '%';

	$icon = sprintf(
		'<i class="%s"></i>',
		themeblvd_get_icon_class( themeblvd_get_format_icon( $args['format'], true ) )
	);

	if ( $args['link'] ) {

		$icon = sprintf(
			'<a href="%s" title="%s">%s</a>',
			esc_url( $args['link'] ),
			esc_attr( $args['title'] ),
			$icon
		);

	}

	$output = sprintf(
		'<div class="%s"><div class="placeholder" style="padding-bottom:%s">%s</div></div>',
		$class,
		$height,
		$icon
	);

	/**
	 * Filters the placeholder used for missing
	 * featured images.
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Placeholder arguments.
	 *
	 *     @type string     $format Post format.
	 *     @type string     $type   Type of placeholder, not currently used.
	 *     @type string|int $width  Width of placeholder.
	 *     @type string|int $height Height of placeholder.
	 *     @type string     $link   Placeholder link URL, like `http://google.com`.
	 *     @type string     $title  If $link, title for link.
	 * }
	 */
	return apply_filters( 'themeblvd_media_placeholder', $output, $args );

}

/**
 * Get a lightbox link.
 *
 * This will take some HTML and wrap it in
 * a link (<a>) to a lightbox.
 *
 * @since @@name-framework 2.3.0
 *
 * @param  array  $args {
 *     Lightox link arguments.
 *
 *     @type string $item    HTML that should be wrapped in the lightbox link.
 *     @type string $link    Media source URL for lightbox, like `http://mysite.com/image.jpg`.
 *     @type string $title   Link title, which will get used in lightbox.
 *     @type string $type    Type of lightbox link, `image`, `iframe`, `ajax` or `inline`; leave blank for auto-detection.
 *     @type string $class   CSS classes for link, like `foo bar baz`.
 *     @type array  $props   Additional data properties for link, like `array( 'data-foo' => 'bar' )`.
 *     @type string $addon   HTML to add directly into <a> tag like `data-foo="bar"`.
 *     @type string $gallery Whether this link is part of a gallery.
 * }
 * @return string $output Final HTML output.
 */
function themeblvd_get_link_to_lightbox( $args ) {

	$args = wp_parse_args( $args, array(
		'item'    => themeblvd_get_local( 'link_to_lightbox' ),
		'link'    => '',
		'title'   => '',
		'type'    => '',
		'class'   => '',
		'props'   => array(),
		'addon'   => '',
		'gallery' => false,
	));

	$item = $args['item'];

	$props = array(
		'href'  => esc_url( $args['link'] ),
		'title' => esc_html( $args['title'] ),
		'class' => '',
	);

	// Fix for youtu.be links
	if ( false !== strpos( $props['href'], 'http://youtu.be/' ) ) {

		$props['href'] = str_replace(
			'http://youtu.be/',
			'http://youtube.com/watch?v=',
			$props['href']
		);

	}

	$types = array( 'image', 'iframe', 'inline', 'ajax' );

	$type = $args['type'];

	if ( ! in_array( $type, $types ) ) {

		$type = 'iframe'; // Default, will work for videos, google maps, webpages, etc.

		// Auto lightbox type detection.
		if ( 0 === strpos( $props['href'], '#' ) ) {

			$type = 'inline';

		} else {

			$parsed_url = parse_url( $props['href'] );

			if ( ! empty( $parsed_url['path'] ) ) {

				$filetype = wp_check_filetype( $parsed_url['path'] );

				// Link to image file?
				if ( substr( $filetype['type'], 0, 5 ) == 'image' ) {

					$type = 'image';

				}
			}
		}
	}

	$class = array( 'themeblvd-lightbox', "mfp-{$type}" );

	if ( 'iframe' === $type ) {

		$class[] = 'lightbox-iframe'; // Enables framework's separate JS for iframe video handling in non-galleries.

	}

	$user_class = $args['class'];

	if ( ! is_array( $args['class'] ) ) {

		$user_class = explode( ' ', $args['class'] );

	}

	$class = array_merge( $class, $user_class );

	/**
	 * Filters the CSS classes used for a lightbox
	 * link.
	 *
	 * @since @@name-framework 2.3.0
	 *
	 * @param array  $class CSS classes for lightbox link.
	 * @param array  $args {
	 *     Lightox link arguments.
	 *
	 *     @type string $item    HTML that should be wrapped in the lightbox link.
	 *     @type string $link    Media source URL for lightbox, like `http://mysite.com/image.jpg`.
	 *     @type string $title   Link title, which will get used in lightbox.
	 *     @type string $type    Type of lightbox link, `image`, `iframe`, `ajax` or `inline`; leave blank for auto-detection.
	 *     @type string $class   CSS classes for link, like `foo bar baz`.
	 *     @type array  $props   Additional data properties for link, like `array( 'data-foo' => 'bar' )`.
	 *     @type string $addon   HTML to add directly into <a> tag like `data-foo="bar"`.
	 *     @type string $gallery Whether this link is part of a gallery.
	 * }
	 * @param string $type  Type of lightbox media, `image`, `iframe`, `inline` or `ajax`.
	 * @param string $item  HTML that is getting wrapped in a link.
	 */
	$class = apply_filters( 'themeblvd_lightbox_class', $class, $args, $type, $item );

	$props['class'] = esc_attr( implode( ' ', $class ) );

	// Add user any additional properties passed in.
	if ( is_array( $args['props'] ) ) {

		$props = array_merge( $props, $args['props'] );

	}

	/**
	 * Filters the link properties for a lightbox
	 * link.
	 *
	 * @since @@name-framework 2.3.0
	 *
	 * @param array  $props Properties to get added to lightbox <a> link tag.
	 * @param array  $args {
	 *     Lightox link arguments.
	 *
	 *     @type string $item    HTML that should be wrapped in the lightbox link.
	 *     @type string $link    Media source URL for lightbox, like `http://mysite.com/image.jpg`.
	 *     @type string $title   Link title, which will get used in lightbox.
	 *     @type string $type    Type of lightbox link, `image`, `iframe`, `ajax` or `inline`; leave blank for auto-detection.
	 *     @type string $class   CSS classes for link, like `foo bar baz`.
	 *     @type array  $props   Additional data properties for link, like `array( 'data-foo' => 'bar' )`.
	 *     @type string $addon   HTML to add directly into <a> tag like `data-foo="bar"`.
	 *     @type string $gallery Whether this link is part of a gallery.
	 * }
	 * @param string $type  Type of lightbox media, `image`, `iframe`, `inline` or `ajax`.
	 * @param string $item  HTML that is getting wrapped in a link.
	 * @param array  $class Classes for link, before imploded to string.
	 */
	$props = apply_filters( 'themeblvd_lightbox_props', $props, $args, $type, $item, $class );

	// Build <a> tag and properties.

	$output = '<a ';

	foreach ( $props as $key => $value ) {

		$output .= sprintf( '%s="%s" ', $key, $value );

	}

	$output = trim( $output );

	if ( $args['addon'] ) {

		$output .= ' ' . wp_kses( $args['addon'], array() );

	}

	$output .= sprintf( '>%s</a>', themeblvd_kses( $item ) );

	/**
	 * Filters final HTML output for a lightbox
	 * link.
	 *
	 * @since @@name-framework 2.3.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Lightox link arguments.
	 *
	 *     @type string $item    HTML that should be wrapped in the lightbox link.
	 *     @type string $link    Media source URL for lightbox, like `http://mysite.com/image.jpg`.
	 *     @type string $title   Link title, which will get used in lightbox.
	 *     @type string $type    Type of lightbox link, `image`, `iframe`, `ajax` or `inline`; leave blank for auto-detection.
	 *     @type string $class   CSS classes for link, like `foo bar baz`.
	 *     @type array  $props   Additional data properties for link, like `array( 'data-foo' => 'bar' )`.
	 *     @type string $addon   HTML to add directly into <a> tag like `data-foo="bar"`.
	 *     @type string $gallery Whether this link is part of a gallery.
	 * }
	 * @param array  $props  Properties to get added to lightbox <a> link tag.
	 * @param string $type   Type of lightbox media, `image`, `iframe`, `inline` or `ajax`.
	 * @param string $item   HTML that is getting wrapped in a link.
	 * @param array  $class  Classes for link, before imploded to string.
	 */
	return apply_filters( 'themeblvd_link_to_lightbox', $output, $args, $props, $type, $item, $class );

}

/**
 * Display a lightbox link.
 *
 * @since @@name-framework 2.3.0
 *
 * @param array $args Lightbox link arguments, see themeblvd_get_link_to_lightbox() docs.
 */
function themeblvd_link_to_lightbox( $args ) {

	echo themeblvd_get_link_to_lightbox( $args );

}

/**
 * Get the main site logo.
 *
 * This uses arguments saved from the `logo`
 * option type.
 *
 * Available logo types:
 * 1. `default`       Use website default logo attributes.
 * 2. `title`         Display website title from WordPress settings as logo.
 * 3. `title_tagline` Display website title and tagline from WordPress settings as logo.
 * 4. `custom`        Display custom title (and optional tagline) as logo.
 * 5. `image`         Display image as logo.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $logo {
 *     Logo arguments.
 *
 *     @type string $type           Logo type, `default`, `title`, `title_tagline`, `custom` or `image`.
 *     @type string $custom         If $type == `custom`, title text, like `My Website`.
 *     @type string $custom_tagline If $type == `custom`, tagline text, like `Just Another WordPress Site`.
 *     @type string $image          If $type == `image`, the image URL.
 *     @type string $image_width    If $type == `image`, the image width, like `200`.
 *     @type string $image_height   If $type == `image`, the image height, like `50`.
 *     @type string $image_2x       If $type == `image`, the 2x image URL for HiDPI/Retina.
 *     @type string $class          CSS classes for logo.
 * }
 * @param  bool   $trans  Whether being used for transparent header.
 * @return string $output Final HTML output.
 */
function themeblvd_get_logo( $logo = array(), $trans = false ) {

	$output = '';

	$defaults = array(
		'type'           => 'image',
		'custom'         => '',
		'custom_tagline' => '',
		'image'          => '',
		'image_width'    => 0,
		'image_height'   => 0,
		'image_2x'       => '',
		'class'          => '',
	);

	if ( ! $logo || ( isset( $logo['type'] ) && 'image' === $logo['type'] && empty( $logo['image'] ) ) ) {

		$logo = wp_parse_args( themeblvd_get_option( 'logo' ), $logo );

	}

	$logo = wp_parse_args( $logo, $defaults );

	if ( $logo ) {

		$class = 'header-logo header_logo header_logo_' . $logo['type'];

		if ( 'custom' === $logo['type'] || 'title' === $logo['type'] || 'title_tagline' === $logo['type'] ) {

			$class .= ' header-text-logo';

			$class .= ' header_logo_text'; // @deprecated legacy class.

		}

		if ( 'custom' === $logo['type'] && ! empty( $logo['custom_tagline'] ) ) {

			$class .= ' header_logo_has_tagline';

		}

		if ( 'title_tagline' === $logo['type'] ) {

			$class .= ' header_logo_has_tagline';

		}

		if ( $logo['class'] ) {

			$class .= ' ' . $logo['class'];

		}

		$output .= sprintf( '<div class="%s">', esc_attr( $class ) );

		if ( ! empty( $logo['type'] ) ) {

			switch ( $logo['type'] ) {

				case 'title':
					$output .= sprintf(
						'<h1 class="tb-text-logo"><a href="%1$s" title="%2$s">%2$s</a></h1>',
						esc_url( themeblvd_get_home_url() ),
						esc_html( get_bloginfo( 'name' ) )
					);

					break;

				case 'title_tagline':
					$output .= sprintf(
						'<h1 class="tb-text-logo"><a href="%1$s" title="%2$s">%2$s</a></h1>',
						esc_url( themeblvd_get_home_url() ),
						esc_html( get_bloginfo( 'name' ) )
					);

					$output .= sprintf(
						'<span class="tagline">%s</span>',
						esc_html( get_bloginfo( 'description' ) )
					);

					break;

				case 'custom':
					$output .= sprintf(
						'<h1 class="tb-text-logo"><a href="%1$s" title="%2$s">%2$s</a></h1>',
						esc_url( themeblvd_get_home_url() ),
						esc_attr( $logo['custom'] )
					);

					if ( $logo['custom_tagline'] ) {

						$output .= sprintf(
							'<span class="tagline">%s</span>',
							themeblvd_kses( $logo['custom_tagline'] )
						);

					}

					break;

				case 'image':
					$output .= sprintf(
						'<a href="%s" title="%s" class="tb-image-logo">',
						esc_url( themeblvd_get_home_url() ),
						esc_html( get_bloginfo( 'name' ) )
					);

					$output .= sprintf(
						'<img src="%s" alt="%s" ',
						esc_url( $logo['image'] ),
						esc_html( get_bloginfo( 'name' ) )
					);

					if ( ! empty( $logo['image_width'] ) ) {

						$output .= sprintf(
							'width="%s" ',
							esc_attr( $logo['image_width'] )
						);

					}

					if ( ! empty( $logo['image_height'] ) ) {

						$output .= sprintf(
							'height="%s" ',
							esc_attr( $logo['image_height'] )
						);

					}

					$srcset = '';

					if ( ! empty( $logo['image'] ) && ! empty( $logo['image_2x'] ) ) {

						$srcset .= sprintf(
							'%s 1x, %s 2x',
							esc_attr( $logo['image'] ),
							esc_attr( $logo['image_2x'] )
						);

					}

					/**
					 * Filters the `srcset` attribute used with the
					 * main website logo.
					 *
					 * @since @@name-framework 2.5.0
					 *
					 * @param string $srcset Value formatted for HTML srcset parameter.
					 * @param array  $logo {
					 *     Logo arguments.
					 *
					 *     @type string $type           Logo type, `default`, `title`, `title_tagline`, `custom` or `image`.
					 *     @type string $custom         If $type == `custom`, title text, like `My Website`.
					 *     @type string $custom_tagline If $type == `custom`, tagline text, like `Just Another WordPress Site`.
					 *     @type string $image          If $type == `image`, the image URL.
					 *     @type string $image_width    If $type == `image`, the image width, like `200`.
					 *     @type string $image_height   If $type == `image`, the image height, like `50`.
					 *     @type string $image_2x       If $type == `image`, the 2x image URL for HiDPI/Retina.
					 *     @type string $class          CSS classes for logo.
					 * }
					 * @param bool   $trans  Whether being used for transparent header.
					 */
					$srcset = apply_filters( 'themeblvd_logo_srcset', $srcset, $logo, $trans );

					if ( $srcset ) {

						$output .= sprintf( 'srcset="%s" ', $srcset );

					}

					/**
					 * Filters the `sizes` attribute used with the
					 * main website logo. Blank by default.
					 *
					 * @since @@name-framework 2.5.0
					 *
					 * @param string $srcset Value formatted for HTML srcset parameter.
					 * @param array  $logo {
					 *     Logo arguments.
					 *
					 *     @type string $type           Logo type, `default`, `title`, `title_tagline`, `custom` or `image`.
					 *     @type string $custom         If $type == `custom`, title text, like `My Website`.
					 *     @type string $custom_tagline If $type == `custom`, tagline text, like `Just Another WordPress Site`.
					 *     @type string $image          If $type == `image`, the image URL.
					 *     @type string $image_width    If $type == `image`, the image width, like `200`.
					 *     @type string $image_height   If $type == `image`, the image height, like `50`.
					 *     @type string $image_2x       If $type == `image`, the 2x image URL for HiDPI/Retina.
					 *     @type string $class          CSS classes for logo.
					 * }
					 * @param bool   $trans  Whether being used for transparent header.
					 */
					$sizes = apply_filters( 'themeblvd_logo_sizes', '', $logo );

					if ( $sizes ) {

						$output .= sprintf( 'sizes="%s" ', $sizes );

					}

					$output .= '/></a>';

					break;

			}
		}

		$output .= '</div><!-- .header-logo (end) -->';

	}

	/**
	 * Filters the main site logo output.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $logo {
	 *     Logo arguments.
	 *
	 *     @type string $type           Logo type, `default`, `title`, `title_tagline`, `custom` or `image`.
	 *     @type string $custom         If $type == `custom`, title text, like `My Website`.
	 *     @type string $custom_tagline If $type == `custom`, tagline text, like `Just Another WordPress Site`.
	 *     @type string $image          If $type == `image`, the image URL.
	 *     @type string $image_width    If $type == `image`, the image width, like `200`.
	 *     @type string $image_height   If $type == `image`, the image height, like `50`.
	 *     @type string $image_2x       If $type == `image`, the 2x image URL for HiDPI/Retina.
	 *     @type string $class          CSS classes for logo.
	 * }
	 * @param bool   $trans  Whether being used for transparent header.
	 */
	return apply_filters( 'themeblvd_logo', $output, $logo, $trans );

}

/**
 * Get a background slider.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  string $id       Unique ID for background slider.
 * @param  array  $images   Images for background slider.
 * @param  bool   $parallax Whether to display with parallax effect.
 * @return string $output   Final HTML output.
 */
function themeblvd_get_bg_slideshow( $id, $images, $parallax = false ) {

	$output = '';

	if ( ! $images || ! is_array( $images ) ) {

		return $output;

	}

	foreach ( $images as $img_id => $img ) {

		$images[ $img_id ] = wp_parse_args( $img, array(
			'crop' => 'full',
			'id'   => 0,
			'alt'  => '',
			'src'  => '',
		));

	}

	/**
	 * Filters the milliseconds between transitions
	 * for a background slider.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param int|string Background slider interval in milliseconds, like `5000`.
	 */
	$interval = apply_filters( 'themeblvd_bg_slideshow_interval', 5000 );

	$output .= sprintf(
		'<div id="bg-slideshow-%s" class="tb-bg-slideshow carousel" data-ride="carousel" data-interval="%s" data-pause="0" data-wrap="1">',
		esc_attr( $id ),
		esc_attr( $interval )
	);

	$output .= '<div class="carousel-control-wrap">';

	$output .= '<div class="carousel-inner">';

	$counter = 0;

	foreach ( $images as $img_id => $img ) {

		$class = 'item';

		if ( $parallax ) {

			$class .= ' tb-parallax';

		}

		if ( 0 === $counter ) {

			$class .= ' active';

		}

		$img_src = $img['src'];

		if ( is_ssl() ) {

			$img_src = str_replace( 'http://', 'https://', $img_src );

		}

		if ( $parallax ) {

			$output .= sprintf(
				'<div class="%s">%s</div><!-- .item (end) -->',
				$class,
				themeblvd_get_bg_parallax( array(
					'src' => $img_src,
				))
			);

		} else {

			$output .= sprintf(
				'<div class="%s" style="background-image: url(%s);"></div><!-- .item (end) -->',
				$class,
				esc_url( $img_src )
			);

		}

		$counter++;

	}

	$output .= '</div><!-- .carousel-inner (end) -->';

	$output .= '</div><!-- .carousel-control-wrap (end) -->';

	$output .= '</div><!-- .tb-bg-slideshow (end) -->';

	/**
	 * Filters the HTML output for a background
	 * slider.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output   Final HTML output.
	 * @param string $id       Unique ID for background slider.
	 * @param array  $images   Images for background slider.
	 * @param bool   $parallax Whether to display with parallax effect.
	 */
	return apply_filters( 'themeblvd_bg_slideshow', $output, $images, $id, $parallax );

}

/**
 * Display a background slider.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  string $id       Unique ID for background slider.
 * @param  array  $images   Images for background slider.
 * @param  bool   $parallax Whether to display with parallax effect.
 */
function themeblvd_bg_slideshow( $id, $images, $parallax = false ) {

	echo themeblvd_get_bg_slideshow( $id, $images, $parallax );

}

/**
 * Get a background parallax image.
 *
 * @since @@name-framework 2.5.1
 *
 * @param  array $display Display settings from custom layout.
 */
function themeblvd_get_bg_parallax( $display ) {

	$type = 'image';

	if ( ! empty( $display['bg_type'] ) ) {

		$type = $display['bg_type'];

	}

	$color = '#000000';

	if ( ! empty( $display['bg_color'] ) ) {

		$color = $display['bg_color'];

	}

	$src = '';

	$texture = '';

	if ( 'image' === $type ) {

		if ( ! empty( $display['src'] ) ) { // Manually pass in.

			$src = $display['src'];

		} elseif ( ! empty( $display['bg_image']['image'] ) ) {

			$src = $display['bg_image']['image'];

		}
	} elseif ( 'texture' === $type ) {

		if ( ! empty( $display['bg_texture'] ) ) {

			$texture = $display['bg_texture'];

		}
	}

	$class = 'parallax-figure';

	if ( $texture ) {

		$class .= ' has-texture';

	}

	// Build HTML output.

	$output = sprintf(
		'<div class="%s" style="background-color:%s">',
		esc_attr( $class ),
		esc_attr( $color )
	);

	$output .= themeblvd_get_loader();

	if ( 'texture' === $type ) {

		$texture = themeblvd_get_texture( $texture );

		$output .= sprintf(
			'<div class="img" style="background-image:url(%s);background-position:%s;background-repeat:%s;background-size:%s;"></div>',
			esc_url( $texture['url'] ),
			esc_attr( $texture['position'] ),
			esc_attr( $texture['repeat'] ),
			esc_attr( $texture['size'] )
		);

	} else {

		$output .= sprintf( '<img src="%s" alt="" />', esc_url( $src ) );

	}

	$output .= '</div><!-- .parallax-figure (end) -->';

	/**
	 * Filters a background parallax image.
	 *
	 * @since @@name-framework 2.5.1
	 *
	 * @param string $output  Final HTML output.
	 * @param array  $display Display settings from custom layout.
	 */
	return apply_filters( 'themeblvd_bg_parallax', $output, $display );

}

/**
 * Display a background parallax image.
 *
 * @since @@name-framework 2.5.1
 *
 * @param array $display Display settings from custom layout.
 */
function themeblvd_bg_parallax( $display ) {

	echo themeblvd_get_bg_parallax( $display );

}

/**
 * Get a background shade.
 *
 * @since @@name-framework 2.7.0
 *
 * @param array $display Display settings from custom layout.
 */
function themeblvd_get_bg_shade( $display ) {

	$output = '';

	if ( ! empty( $display['bg_shade_color'] ) && ! empty( $display['bg_shade_opacity'] ) ) {

		$output = sprintf(
			'<div class="bg-shade" style="background-color: %s;"></div>',
			esc_attr( themeblvd_get_rgb( $display['bg_shade_color'], $display['bg_shade_opacity'] ) )
		);

	}

	/**
	 * Filters a background shade.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string $output  Final HTML output.
	 * @param array  $display Display settings from custom layout.
	 */
	return apply_filters( 'themeblvd_bg_shade', $output, $display );

}

/**
 * Display a background shade.
 *
 * @since @@name-framework 2.7.0
 *
 * @param array $display Display settings from custom layout.
 */
function themeblvd_bg_shade( $display ) {

	echo themeblvd_get_bg_shade( $display );

}

/**
 * Get a background video.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $video {
 *     Video arguments.
 *
 *     @type string $id       Video ID, should be unique.
 *     @type string $mp4      Video URL, can be .mp4, .ogg, .webm, Vimeo web URL, or YouTube web URL.
 *     @type string $ratio    Aspect ratior, like `16:9`.
 *     @type string $fallback Fallback image URL, like `http://mysite.com/image.jpg`.
 * }
 * @return string $output Final HTML output.
 */
function themeblvd_get_bg_video( $video ) {

	$video = apply_filters( 'themeblvd_video_args', wp_parse_args( $video, array(
		'id'       => uniqid( 'video_' . rand() ),
		'mp4'      => '',
		'ratio'    => '16:9',
		'fallback' => '',
	)));

	$source = themeblvd_get_video_source( $video['mp4'] );

	$output  = sprintf(
		"\n<div class=\"tb-bg-video %s\" data-ratio=\"%s\">",
		esc_url( $source ),
		esc_attr( $video['ratio'] )
	);

	$output .= "\n\t<div class=\"no-click\"></div>";

	switch ( $source ) {

		case 'html5':
			wp_enqueue_script( 'wp-mediaelement' );

			preg_match( '!^(.+?)(?:\.([^.]+))?$!', $video['mp4'], $path_split );

			$output .= sprintf(
				"\n\t<video id=\"%s\" class=\"video\" controls",
				esc_attr( $video['id'] )
			);

			if ( $video['fallback'] && themeblvd_is_200( $video['fallback'] ) ) {

				$output .= sprintf( ' poster="%s"', esc_url( $video['fallback'] ) );

			}

			$output .= ">\n";

			/**
			 * Filters the types of videos to look for with
			 * self-hosted background video.
			 *
			 * Because the user can only feed in a single video
			 * URL, this allows for multiple video file formats,
			 * based off that original URL.
			 *
			 * For example, if the user inputs:
			 * `http://mysite/dir/foo.mp4`
			 *
			 * Then, we'll also search for:
			 * `http://mysite/dir/foo.webm`
			 * `http://mysite/dir/foo.ogv`
			 *
			 * ... So those can be added to the <video> tag.
			 *
			 * @since @@name-framework 2.5.0
			 *
			 * @param array Video types.
			 */
			$types = apply_filters( 'themeblvd_html5_video_types', array(
				'webm'  => 'type="video/webm"',
				'mp4'   => 'type="video/mp4"',
				'ogv'   => 'type="video/ogg"',
			));

			foreach ( $types as $key => $type ) {

				if ( $path_split[2] == $key || themeblvd_is_200( $path_split[1] . '.' . $key ) ) {

					$output .= sprintf(
						"\t\t<source src=\"%s.%s\" %s />\n",
						$path_split[1],
						$key,
						$type
					);

				}
			}

			$output .= "\t</video>\n";

			break;

		case 'youtube':
			$explode_at = false !== strpos( $video['mp4'], 'youtu.be/' ) ? '/' : 'v=';

			$youtube_id = explode( $explode_at, trim( $video['mp4'] ) );

			$youtube_id = end( $youtube_id );

			if ( $youtube_id ) {

				/**
				 * Filters the arguments used to embed a
				 * background video from YouTube API.
				 *
				 * @since @@name-framework 2.5.0
				 *
				 * @param array        Video arguments for YouTube API.
				 * @param array $video {
				 *     Video arguments.
				 *
				 *     @type string $id       Video ID, should be unique.
				 *     @type string $mp4      Video URL, can be .mp4, .ogg, .webm, Vimeo web URL, or YouTube web URL.
				 *     @type string $ratio    Aspect ratior, like `16:9`.
				 *     @type string $fallback Fallback image URL, like `http://mysite.com/image.jpg`.
				 * }
				 */
				$args = apply_filters( 'themeblvd_youtube_bg_args', array(
					'vid'            => $youtube_id,
					'autoplay'       => 0, // Will play video through API after it's loaded.
					'loop'           => 1,
					'hd'             => 1,
					'controls'       => 0,
					'showinfo'       => 0,
					'modestbranding' => 1,
					'iv_load_policy' => 3,
					'rel'            => 0,
					'version'        => 3,
					'enablejsapi'    => 1,
					'wmode'          => 'opaque',
					'playlist'       => $youtube_id,
				), $video );

				$output .= sprintf(
					"\n\t<div id=\"%s\" class=\"video\"",
					esc_attr( $video['id'] )
				);

				foreach ( $args as $key => $val ) {

					$output .= sprintf(
						' data-%s="%s"',
						esc_attr( $key ),
						esc_attr( $val )
					);

				}

				$output .= "></div>\n";

			}

			break;

		case 'vimeo':
			$vimeo_id = explode( '/', trim( $video['mp4'] ) );

			$vimeo_id = end( $vimeo_id );

			/**
			 * Filters the arguments used to embed a
			 * background video from Vimeo API.
			 *
			 * @since @@name-framework 2.5.0
			 *
			 * @param array        Video arguments for Vimeo API.
			 * @param array $video {
			 *     Video arguments.
			 *
			 *     @type string $id       Video ID, should be unique.
			 *     @type string $mp4      Video URL, can be .mp4, .ogg, .webm, Vimeo web URL, or YouTube web URL.
			 *     @type string $ratio    Aspect ratior, like `16:9`.
			 *     @type string $fallback Fallback image URL, like `http://mysite.com/image.jpg`.
			 * }
			 */
			$args = apply_filters( 'themeblvd_vimeo_bg_args', array(
				'portrait'   => 0,
				'byline'     => 0,
				'title'      => 0,
				'badge'      => 0,
				'loop'       => 1,
				'autoplay'   => 0,
				'autopause'  => 0,
				'api'        => 1,
				'rel'        => 0,
				'player_id'  => $video['id'],
				'background' => 1,
			), $video );

			$vimeo_url = add_query_arg(
				$args,
				'https://player.vimeo.com/video/' . $vimeo_id
			);

			$output .= sprintf(
				"\n\t<iframe id=\"%s\" src=\"%s\" height=\"1600\" width=\"900\" frameborder=\"\" class=\"video\"></iframe>\n",
				esc_attr( $video['id'] ),
				esc_url( $vimeo_url )
			);

			break;

	}

	$output .= "</div><!-- .tb-bg-video (end) -->\n";

	/**
	 * Filters a background video.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string $output  Final HTML output.
	 * @param array  $video {
	 *     Video arguments.
	 *
	 *     @type string $id       Video ID, should be unique.
	 *     @type string $mp4      Video URL, can be .mp4, .ogg, .webm, Vimeo web URL, or YouTube web URL.
	 *     @type string $ratio    Aspect ratior, like `16:9`.
	 *     @type string $fallback Fallback image URL, like `http://mysite.com/image.jpg`.
	 * }
	 */
	return apply_filters( 'themeblvd_bg_video', $output, $video );

}

/**
 * Display a background video.
 *
 * @since @@name-framework 2.5.0
 */
function themeblvd_bg_video( $video ) {

	echo themeblvd_get_bg_video( $video );

}
