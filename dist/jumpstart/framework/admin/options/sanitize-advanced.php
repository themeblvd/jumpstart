<?php
/**
 * Advanced Option Sanitization
 *
 * This file contains sanitization functions for
 * all advanced options setup with the
 * Theme_Blvd_Advanced_Options class, which
 * currently consist of primarily sortable options.
 *
 * For more complete documenation on sortable options,
 * which are a subset of advanced options, see:
 *
 * framework/admin/options/class-theme-blvd-sortable-options.php
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     Jump_Start
 */

/**
 * Sanitize option type, `bars`.
 *
 * This option is used to create a group of
 * progress bars.
 *
 * Sortable option. For more documentation, see:
 * framework/admin/options/class-theme-blvd-sortable-options.php
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array $input  Original value.
 * @return array $output Sanitized value.
 */
function themeblvd_sanitize_bars( $input ) {

	$output = array();

	if ( $input && is_array( $input ) ) {

		foreach ( $input as $item_id => $item ) {

			$output[ $item_id ] = array(
				'label'       => '',
				'label_value' => '',
				'value'       => '',
				'total'       => '',
				'color'       => '',
			);

			if ( isset( $item['label'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['label'] = apply_filters( 'themeblvd_sanitize_text', $item['label'] );
			}

			if ( isset( $item['label_value'] ) ) {
				$output[ $item_id ]['label_value'] = wp_kses( $item['label_value'], array() );
			}

			if ( isset( $item['value'] ) ) {
				$output[ $item_id ]['value'] = wp_kses( $item['value'], array() );
			}

			if ( isset( $item['total'] ) ) {
				$output[ $item_id ]['total'] = wp_kses( $item['total'], array() );
			}

			if ( isset( $item['color'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['color'] = apply_filters( 'themeblvd_sanitize_color', $item['color'] );
			}
		}
	}

	return $output;

}

/**
 * Sanitize option type, `buttons`.
 *
 * Sortable option. For more documentation, see:
 * framework/admin/options/class-theme-blvd-sortable-options.php
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array $input  Original value.
 * @return array $output Sanitized value.
 */
function themeblvd_sanitize_buttons( $input ) {

	$output = array();

	if ( $input && is_array( $input ) ) {
		foreach ( $input as $item_id => $item ) {

			$output[ $item_id ] = array(
				'color'       => '',
				'custom'      => array(),
				'text'        => '',
				'size'        => '',
				'url'         => '',
				'target'      => '',
				'icon_before' => '',
				'icon_after'  => '',
			);

			if ( isset( $item['color'] ) ) {
				$output[ $item_id ]['color'] = wp_kses( $item['color'], array() );
			}

			if ( isset( $item['custom'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['custom'] = apply_filters( 'themeblvd_sanitize_button', $item['custom'] );
			}

			if ( isset( $item['text'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['text'] = apply_filters( 'themeblvd_sanitize_text', $item['text'] );
			}

			if ( isset( $item['size'] ) ) {
				$output[ $item_id ]['size'] = wp_kses( $item['size'], array() );
			}

			if ( isset( $item['url'] ) ) {
				$output[ $item_id ]['url'] = esc_url( $item['url'] );
			}

			if ( isset( $item['target'] ) ) {
				$output[ $item_id ]['target'] = wp_kses( $item['target'], array() );
			}

			if ( isset( $item['icon_before'] ) ) {
				$output[ $item_id ]['icon_before'] = wp_kses( $item['icon_before'], array() );
			}

			if ( isset( $item['icon_after'] ) ) {
				$output[ $item_id ]['icon_after'] = wp_kses( $item['icon_after'], array() );
			}
		}
	}

	return $output;

}

/**
 * Sanitize option type, `datasets`.
 *
 * This option type sets up values for a bar
 * or line graph.
 *
 * Sortable option. For more documentation, see:
 * framework/admin/options/class-theme-blvd-sortable-options.php
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array $input  Original value.
 * @return array $output Sanitized value.
 */
function themeblvd_sanitize_datasets( $input ) {

	$output = array();

	if ( $input && is_array( $input ) ) {

		foreach ( $input as $item_id => $item ) {

			$output[ $item_id ] = array(
				'label'  => '',
				'values' => '',
				'color'  => '',
			);

			if ( isset( $item['label'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['label'] = apply_filters( 'themeblvd_sanitize_text', $item['label'] );
			}

			if ( isset( $item['values'] ) ) {
				$output[ $item_id ]['values'] = wp_kses( $item['values'], array() );
			}

			if ( isset( $item['color'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['color'] = apply_filters( 'themeblvd_sanitize_color', $item['color'] );
			}
		}
	}

	return $output;

}

/**
 * Sanitize option type, `locations`.
 *
 * This option type sets of localtion markers
 * of a Google Map.
 *
 * Sortable option. For more documentation, see:
 * framework/admin/options/class-theme-blvd-sortable-options.php
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array $input  Original value.
 * @return array $output Sanitized value.
 */
function themeblvd_sanitize_locations( $input ) {

	$output = array();

	if ( $input && is_array( $input ) ) {

		foreach ( $input as $item_id => $item ) {

			$output[ $item_id ] = array(
				'name'   => '',
				'geo'    => array(),
				'info'   => '',
				'image'  => '',
				'width'  => '',
				'height' => '',
			);

			if ( isset( $item['name'] ) ) {
				$output[ $item_id ]['name'] = wp_kses( $item['name'], array() );
			}

			if ( isset( $item['geo'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['geo'] = apply_filters( 'themeblvd_sanitize_geo', $item['geo'] );
			}

			if ( isset( $item['info'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['info'] = apply_filters( 'themeblvd_sanitize_textarea', $item['info'] );
			}

			if ( isset( $item['image'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['image'] = apply_filters( 'themeblvd_sanitize_upload', $item['image'] );
			}

			if ( isset( $item['width'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['width'] = apply_filters( 'themeblvd_sanitize_text', $item['width'] );
			}

			if ( isset( $item['height'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['height'] = apply_filters( 'themeblvd_sanitize_text', $item['height'] );
			}
		}
	}

	return $output;

}

/**
 * Sanitize option type, `price_cols`.
 *
 * This option is used for setting up columns
 * of a pricing table block.
 *
 * Sortable option. For more documentation, see:
 * framework/admin/options/class-theme-blvd-sortable-options.php
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array $input  Original value.
 * @return array $output Sanitized value.
 */
function themeblvd_sanitize_price_cols( $input ) {

	$output = array();

	if ( $input && is_array( $input ) ) {

		foreach ( $input as $item_id => $item ) {

			$output[ $item_id ] = array(
				'highlight'          => '',
				'title'              => '',
				'title_subline'      => '',
				'price'              => '',
				'price_subline'      => '',
				'features'           => '',
				'button_color'       => '',
				'button_custom'      => array(),
				'button_text'        => '',
				'button_url'         => '',
				'button_size'        => '',
				'button_icon_before' => '',
				'button_icon_after'  => '',
				'popout'             => '0',
				'button'             => '0',
			);

			if ( isset( $item['highlight'] ) ) {
				$output[ $item_id ]['highlight'] = wp_kses( $item['highlight'], array() );
			}

			if ( isset( $item['title'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['title'] = apply_filters( 'themeblvd_sanitize_text', $item['title'] );
			}

			if ( isset( $item['title_subline'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['title_subline'] = apply_filters( 'themeblvd_sanitize_text', $item['title_subline'] );
			}

			if ( isset( $item['price'] ) ) {
				$output[ $item_id ]['price'] = wp_kses( $item['price'], array() );
			}

			if ( isset( $item['price_subline'] ) ) {
				$output[ $item_id ]['price_subline'] = wp_kses( $item['price_subline'], array() );
			}

			if ( isset( $item['features'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['features'] = apply_filters( 'themeblvd_sanitize_textarea', $item['features'] );
			}

			if ( isset( $item['button_color'] ) ) {
				$output[ $item_id ]['button_color'] = wp_kses( $item['button_color'], array() );
			}

			if ( isset( $item['button_custom'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['button_custom'] = apply_filters( 'themeblvd_sanitize_button', $item['button_custom'] );
			}

			if ( isset( $item['button_text'] ) ) {
				$output[ $item_id ]['button_text'] = wp_kses( $item['button_text'], array() );
			}

			if ( isset( $item['button_url'] ) ) {
				$output[ $item_id ]['button_url'] = esc_url( $item['button_url'] );
			}

			if ( isset( $item['button_size'] ) ) {
				$output[ $item_id ]['button_size'] = wp_kses( $item['button_size'], array() );
			}

			if ( isset( $item['button_icon_before'] ) ) {
				$output[ $item_id ]['button_icon_before'] = wp_kses( $item['button_icon_before'], array() );
			}

			if ( isset( $item['button_icon_after'] ) ) {
				$output[ $item_id ]['button_icon_after'] = wp_kses( $item['button_icon_after'], array() );
			}

			if ( ! empty( $item['popout'] ) ) {
				$output[ $item_id ]['popout'] = '1';
			}

			if ( ! empty( $item['button'] ) ) {
				$output[ $item_id ]['button'] = '1';
			}
		}
	}

	return $output;

}

/**
 * Sanitize option type, `testimonials`.
 *
 * Sortable option. For more documentation, see:
 * framework/admin/options/class-theme-blvd-sortable-options.php
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $input  Original value.
 * @return string $output Sanitized value.
 */
function themeblvd_sanitize_sectors( $input ) {

	$output = array();

	if ( $input && is_array( $input ) ) {

		foreach ( $input as $item_id => $item ) {

			$output[ $item_id ] = array(
				'label' => '',
				'value' => '',
				'color' => '',
			);

			if ( isset( $item['label'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['label'] = apply_filters( 'themeblvd_sanitize_text', $item['label'] );
			}

			if ( isset( $item['value'] ) ) {
				$output[ $item_id ]['value'] = wp_kses( $item['value'], array() );
			}

			if ( isset( $item['color'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['color'] = apply_filters( 'themeblvd_sanitize_color', $item['color'] );
			}
		}
	}

	return $output;

}

/**
 * Sanitize option type, `social_media`.
 *
 * This option is generally used to setup a custom
 * group of icons that represent a user's social
 * media accounts.
 *
 * Sortable option. For more documentation, see:
 * framework/admin/options/class-theme-blvd-sortable-options.php
 *
 * @since Theme_Blvd 2.5.0
 *
 * @see themeblvd_get_social_media_sources()
 *
 * @param  array $input  Original value.
 * @return array $output Sanitized value.
 */
function themeblvd_sanitize_social_media( $input ) {

	$output = array();

	if ( $input && is_array( $input ) ) {

		foreach ( $input as $item_id => $item ) {

			$output[ $item_id ] = array(
				'icon'   => '',
				'url'    => '',
				'label'  => '',
				'target' => '',
			);

			if ( isset( $item['icon'] ) ) {
				$output[ $item_id ]['icon'] = wp_kses( $item['icon'], array() );
			}

			if ( isset( $item['url'] ) ) {
				$output[ $item_id ]['url'] = esc_url( $item['url'] );
			}

			if ( isset( $item['label'] ) ) {
				$output[ $item_id ]['label'] = wp_kses( $item['label'], array() );
			}

			if ( isset( $item['target'] ) ) {
				$output[ $item_id ]['target'] = wp_kses( $item['target'], array() );
			}
		}
	}

	return $output;

}

/**
 * Sanitize option type, `share`.
 *
 * This option is used to denote which of the available
 * sources should be used for the sharing buttons on
 * a WordPress post.
 *
 * Sortable option. For more documentation, see:
 * framework/admin/options/class-theme-blvd-sortable-options.php
 *
 * @since Theme_Blvd 2.5.0
 *
 * @see themeblvd_get_share_sources()
 *
 * @param  array $input  Original value.
 * @return array $output Sanitized value.
 */
function themeblvd_sanitize_share( $input ) {

	$output = array();

	if ( $input && is_array( $input ) ) {

		foreach ( $input as $item_id => $item ) {

			$output[ $item_id ] = array(
				'icon'  => '',
				'label' => '',
			);

			if ( isset( $item['icon'] ) ) {
				$output[ $item_id ]['icon'] = wp_kses( $item['icon'], array() );
			}

			if ( isset( $item['label'] ) ) {
				$output[ $item_id ]['label'] = wp_kses( $item['label'], array() );
			}
		}
	}

	return $output;

}

/**
 * Sanitize option type, `slider`.
 *
 * This option type was built specifically for
 * the Simple Slider block.
 *
 * Sortable option. For more documentation, see:
 * framework/admin/options/class-theme-blvd-sortable-options.php
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array $input  Original value.
 * @return array $output Sanitized value.
 */
function themeblvd_sanitize_slider( $input ) {

	/*
	 * For more documentation on why we're doing
	 * this, see themeblvd_sanitize_upload() above
	 * for a similar use-case with explanation.
	 */
	add_filter( 'editor_max_image_size', 'themeblvd_editor_max_image_size' );

	$output = array();

	if ( $input && is_array( $input ) ) {

		// Setup crop size.

		$crop = 'full';

		if ( ! empty( $input['crop'] ) ) {
			$crop = wp_kses( $input['crop'], array() );
		}

		if ( isset( $input['crop'] ) ) {
			unset( $input['crop'] );
		}

		// Begin loop through slides.

		foreach ( $input as $item_id => $item ) {

			$output[ $item_id ] = array(
				'crop'     => '',
				'id'       => '',
				'alt'      => '',
				'src'      => '',
				'thumb'    => '',
				'title'    => '',
				'desc'     => '',
				'link'     => '',
				'link_url' => '',
			);

			$output[ $item_id ]['crop'] = $crop;

			$image_id = 0;

			if ( isset( $item['id'] ) ) {

				$image_id = intval( $item['id'] );

				$output[ $item_id ]['id'] = $image_id;

				$output[ $item_id ]['alt'] = get_the_title( $image_id );

			}

			if ( $image_id ) {

				$attachment = wp_get_attachment_image_src( $image_id, $crop );

				if ( $attachment ) {

					$downsize = themeblvd_image_downsize( $attachment, $image_id, $crop );

					if ( isset( $downsize[0] ) ) {
						/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
						$output[ $item_id ]['src'] = apply_filters( 'themeblvd_sanitize_upload', $downsize[0] );
					}
				}

				$thumb = wp_get_attachment_image_src( $image_id, 'tb_thumb' );

				if ( isset( $thumb[0] ) ) {
					/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
					$output[ $item_id ]['thumb'] = apply_filters( 'themeblvd_sanitize_upload', $thumb[0] );
				}
			} else {

				if ( isset( $item['src'] ) ) {
					$output[ $item_id ]['src'] = apply_filters( 'themeblvd_sanitize_upload', $item['src'] );
				} else {
					$output[ $item_id ]['src'] = '';
				}

				if ( isset( $item['thumb'] ) ) {
					$output[ $item_id ]['thumb'] = eapply_filters( 'themeblvd_sanitize_upload', $item['thumb'] );
				} else {
					$output[ $item_id ]['thumb'] = '';
				}
			}

			if ( isset( $item['title'] ) ) {
				$output[ $item_id ]['title'] = wp_kses( $item['title'], array() );
			}

			if ( isset( $item['desc'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['desc'] = apply_filters( 'themeblvd_sanitize_textarea', $item['desc'] );
			}

			if ( isset( $item['link'] ) ) {
				$output[ $item_id ]['link'] = wp_kses( $item['link'], array() );
			}

			if ( 'none' === $output[ $item_id ]['link'] ) {
				$output[ $item_id ]['link'] = '';
			}

			if ( isset( $item['link_url'] ) ) {
				$output[ $item_id ]['link_url'] = esc_url( $item['link_url'] );
			}
		}
	}

	/*
	 * For more documentation on why we're doing
	 * this, see themeblvd_sanitize_upload() above
	 * for a similar use-case with explanation.
	 */
	remove_filter( 'editor_max_image_size', 'themeblvd_editor_max_image_size' );

	return $output;

}

/**
 * Sanitize option type, `logos`.
 *
 * Sortable option. For more documentation, see:
 * framework/admin/options/class-theme-blvd-sortable-options.php
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array $input  Original value.
 * @return array $output Sanitized value.
 */
function themeblvd_sanitize_logos( $input ) {

	/*
	 * For more documentation on why we're doing
	 * this, see themeblvd_sanitize_upload() above
	 * for a similar use-case with explanation.
	 */
	add_filter( 'editor_max_image_size', 'themeblvd_editor_max_image_size' );

	$output = array();

	if ( $input && is_array( $input ) ) {

		foreach ( $input as $item_id => $item ) {

			$output[ $item_id ] = array(
				'id'    => '',
				'alt'   => '',
				'src'   => '',
				'thumb' => '',
				'name'  => '',
				'link'  => '',
			);

			$image_id = 0;

			if ( isset( $item['id'] ) ) {

				$image_id = intval( $item['id'] );

				$output[ $item_id ]['id'] = $image_id;

				$output[ $item_id ]['alt'] = get_the_title( $image_id );

			}

			if ( $image_id ) {

				$attachment = wp_get_attachment_image_src( $image_id, 'full' );

				if ( isset( $attachment[0] ) ) {
					/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
					$output[ $item_id ]['src'] = apply_filters( 'themeblvd_sanitize_upload', $attachment[0] );
				}

				$thumb = wp_get_attachment_image_src( $image_id, 'tb_thumb' );

				if ( isset( $thumb[0] ) ) {
					/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
					$output[ $item_id ]['thumb'] = apply_filters( 'themeblvd_sanitize_upload', $thumb[0] );
				}
			} else {

				if ( isset( $item['src'] ) ) {

					/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
					$output[ $item_id ]['src'] = apply_filters( 'themeblvd_sanitize_upload', $item['src'] );

				} else {

					$output[ $item_id ]['src'] = '';

				}

				if ( isset( $item['thumb'] ) ) {

					/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
					$output[ $item_id ]['thumb'] = apply_filters( 'themeblvd_sanitize_upload', $item['thumb'] );

				} else {

					$output[ $item_id ]['thumb'] = '';

				}
			}

			if ( isset( $item['name'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['name'] = apply_filters( 'themeblvd_sanitize_text', $item['name'] );
			}

			if ( isset( $item['link'] ) ) {
				$output[ $item_id ]['link'] = esc_url( $item['link'] );
			}
		}
	}

	/*
	 * For more documentation on why we're doing
	 * this, see themeblvd_sanitize_upload() above
	 * for a similar use-case with explanation.
	 */
	remove_filter( 'editor_max_image_size', 'themeblvd_editor_max_image_size' );

	return $output;

}

/**
 * Sanitize option type, `tabs`.
 *
 * Sortable option. For more documentation, see:
 * framework/admin/options/class-theme-blvd-sortable-options.php
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array $input  Original value.
 * @return array $output Sanitized value.
 */
function themeblvd_sanitize_tabs( $input ) {

	$output = array();

	if ( $input && is_array( $input ) ) {

		foreach ( $input as $item_id => $item ) {

			$output[ $item_id ] = array(
				'title'   => '',
				'content' => '',
			);

			if ( isset( $item['title'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['title'] = apply_filters( 'themeblvd_sanitize_text', $item['title'] );
			}

			if ( isset( $item['content'] ) ) {

				/**
				 * Filters dynamic content selection.
				 *
				 * @see themeblvd_sanitize_content()
				 *
				 * @since Theme_Blvd 2.2.0
				 *
				 * @param array Arguments for display content.
				 */
				$output[ $item_id ]['content'] = apply_filters( 'themeblvd_sanitize_content', $item['content'] );

			}
		}
	}

	return $output;

}

/**
 * Sanitize option type, `testimonials`.
 *
 * Sortable option. For more documentation, see:
 * framework/admin/options/class-theme-blvd-sortable-options.php
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array $input  Original value.
 * @return array $output Sanitized value.
 */
function themeblvd_sanitize_testimonials( $input ) {

	$output = array();

	if ( $input && is_array( $input ) ) {

		foreach ( $input as $item_id => $item ) {

			$output[ $item_id ] = array(
				'text'        => '',
				'name'        => '',
				'tagline'     => '',
				'company'     => '',
				'company_url' => '',
				'image'       => '',
			);

			if ( isset( $item['text'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['text'] = apply_filters( 'themeblvd_sanitize_textarea', $item['text'] );
			}

			if ( isset( $item['name'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['name'] = apply_filters( 'themeblvd_sanitize_text', $item['name'] );
			}

			if ( isset( $item['tagline'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['tagline'] = apply_filters( 'themeblvd_sanitize_text', $item['tagline'] );
			}

			if ( isset( $item['company'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['company'] = apply_filters( 'themeblvd_sanitize_text', $item['company'] );
			}

			if ( isset( $item['company_url'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['company_url'] = apply_filters( 'themeblvd_sanitize_text', $item['company_url'] );
			}

			if ( isset( $item['image'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['image'] = apply_filters( 'themeblvd_sanitize_upload', $item['image'] );
			}
		}
	}

	return $output;

}

/**
 * Sanitize option type, `text_blocks`.
 *
 * This option type was primiarly created to help
 * the hero unit block. It allows the user to create
 * lines of text, which can then be further configured
 * for their color, size, etc.
 *
 * Sortable option. For more documentation, see:
 * framework/admin/options/class-theme-blvd-sortable-options.php
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array $input  Original value.
 * @return array $output Sanitized value.
 */
function themeblvd_sanitize_text_blocks( $input ) {

	$output = array();

	if ( $input && is_array( $input ) ) {

		foreach ( $input as $item_id => $item ) {

			$output[ $item_id ] = array(
				'text'           => '',
				'size'           => '',
				'color'          => '',
				'apply_bg_color' => '0',
				'bg_color'       => '',
				'bg_opacity'     => '',
				'bold'           => '0',
				'italic'         => '0',
				'caps'           => '0',
				'suck_down'      => '0',
				'wpautop'        => '0',
			);

			if ( isset( $item['text'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['text'] = apply_filters( 'themeblvd_sanitize_textarea', $item['text'] );
			}

			if ( isset( $item['size'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['size'] = apply_filters( 'themeblvd_sanitize_text', $item['size'] );
			}

			if ( isset( $item['color'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['color'] = apply_filters( 'themeblvd_sanitize_color', $item['color'] );
			}

			if ( ! empty( $item['apply_bg_color'] ) ) {
				$output[ $item_id ]['apply_bg_color'] = '1';
			}

			if ( isset( $item['bg_color'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['bg_color'] = apply_filters( 'themeblvd_sanitize_color', $item['bg_color'] );
			}

			if ( isset( $item['bg_opacity'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['bg_opacity'] = apply_filters( 'themeblvd_sanitize_text', $item['bg_opacity'] );
			}

			if ( ! empty( $item['bold'] ) ) {
				$output[ $item_id ]['bold'] = '1';
			}

			if ( ! empty( $item['italic'] ) ) {
				$output[ $item_id ]['italic'] = '1';
			}

			if ( ! empty( $item['caps'] ) ) {
				$output[ $item_id ]['caps'] = '1';
			}

			if ( ! empty( $item['suck_down'] ) ) {
				$output[ $item_id ]['suck_down'] = '1';
			}

			if ( ! empty( $item['wpautop'] ) ) {
				$output[ $item_id ]['wpautop'] = '1';
			}
		}
	}

	return $output;

}

/**
 * Sanitize option type, `toggles`.
 *
 * Sortable option. For more documentation, see:
 * framework/admin/options/class-theme-blvd-sortable-options.php
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array $input  Original value.
 * @return array $output Sanitized value.
 */
function themeblvd_sanitize_toggles( $input ) {

	$output = array();

	if ( $input && is_array( $input ) ) {

		foreach ( $input as $item_id => $item ) {

			$output[ $item_id ] = array(
				'title'   => '',
				'content' => '',
				'wpautop' => '0',
				'open'    => '0',
			);

			if ( isset( $item['title'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['title'] = apply_filters( 'themeblvd_sanitize_text', $item['title'] );
			}

			if ( isset( $item['content'] ) ) {
				/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
				$output[ $item_id ]['content'] = apply_filters( 'themeblvd_sanitize_textarea', $item['content'] );
			}

			if ( ! empty( $item['wpautop'] ) ) {
				$output[ $item_id ]['wpautop'] = '1';
			}

			if ( ! empty( $item['open'] ) ) {
				$output[ $item_id ]['open'] = '1';
			}
		}
	}

	return $output;

}
