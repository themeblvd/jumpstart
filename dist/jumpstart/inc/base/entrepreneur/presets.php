<?php
/**
 * Entrepreneur theme base preset style functions.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     Jump_Start
 */

/**
 * Get option presets.
 *
 * @since Jump_Start 2.1.1
 *
 * @param  string $preset Optional preset style to retrieve.
 * @return array  $args   All preset styles or specific preset if $preset specified.
 */
function jumpstart_ent_get_presets( $preset = '' ) {

	$args = array(
		'id'      => 'style_presets',
		'tab'     => 'styles',
		'section' => 'presets',
		'sets'    => array(),
	);

	/**
	 * Filters predefined hex color values
	 * used in Entrepreneur theme base.
	 *
	 * @since Jump_Start 2.1.1
	 *
	 * @param $hex array Color values.
	 */
	$colors = apply_filters( 'jumpstart_ent_preset_colors', array(
		'default'       => array( __( 'Default', 'jumpstart' ), '#ffffff', '#ffffff' ),
		'green'         => array( __( 'Green', 'jumpstart' ), '#4fba6f', '#4ca165' ),
		'sea-green'     => array( __( 'Sea Green', 'jumpstart' ), '#18a185', '#268f77' ),
		'blue'          => array( __( 'Blue', 'jumpstart' ), '#2880ba', '#3174a2' ),
		'midnight-blue' => array( __( 'Midnight Blue', 'jumpstart' ), '#2c3e50', '#1a2836' ),
		'purple'        => array( __( 'Purple', 'jumpstart' ), '#894b9d', '#7b478b' ),
		'orange'        => array( __( 'Orange', 'jumpstart' ), '#d15627', '#b85128' ),
		'red'           => array( __( 'Red', 'jumpstart' ), '#c03b2b', '#a93b2a' ),
		'grey'          => array( __( 'Grey', 'jumpstart' ), '#7f8c8d', '#727e7e' ),
	));

	// Predefined attributes.
	$atts = array(
		'icon'        => trailingslashit( themeblvd_get_base_uri( 'entrepreneur' ) ) . 'img/',
		'icon_width'  => '70',
		'icon_height' => '40',
		'icon_style'  => 'width: 70px; height: 40px',
		'settings'    => array(),
	);

	$styles = array(
		'style-1' => __( 'Style 1', 'jumpstart' ),
		'style-2' => __( 'Style 2', 'jumpstart' ),
		'style-3' => __( 'Style 3', 'jumpstart' ),
		'style-4' => __( 'Style 4', 'jumpstart' ),
	);

	// Do we only want return one preset?
	if ( $preset ) {

		$parts = explode( '-', $preset );
		$color = $parts[2];

		if ( isset( $parts[3] ) ) {
			$color .= '-' . $parts[3];
		}

		foreach ( $colors as $key => $val ) {
			if ( $key != $color ) {
				unset( $colors[ $key ] );
			}
		}

		foreach ( $styles as $key => $val ) {
			if ( $key != $parts[0] . '-' . $parts[1] ) {
				unset( $styles[ $key ] );
			}
		}
	}

	// Build presets.
	foreach ( $styles as $style => $name ) {
		foreach ( $colors as $key => $val ) {

			$settings = array(
				'layout_style'                      => 'stretch',
				'style'                             => 'light',
				'header_bg_type'                    => 'color',
				'header_text_color'                 => 'light',
				'header_bg_color'                   => $val[1],
				'header_bg_color_opacity'           => '1',
				'header_apply_border_top'           => '0',
				'header_mobile_bg_color'            => $val[1],
				'header_mobile_bg_color_brightness' => 'dark',
				'menu_highlight'                    => '#ffffff',
				'menu_text_shadow'                  => '0',
				'menu_sub_bg_color'                 => $val[2],
				'menu_sub_bg_color_brightness'      => 'dark',
				'menu_mobile_bg_color'              => $val[2],
				'menu_mobile_bg_color_brightness'   => 'dark',
				'side_bg_color'                     => $val[2],
				'side_bg_color_brightness'          => 'dark',
				'footer_bg_type'                    => 'color',
				'footer_bg_color'                   => $val[2],
				'footer_bg_color_brightness'        => 'dark',
				'footer_bg_color_opacity'           => '1',
				'footer_apply_border_top'           => '0',
				'footer_apply_border_bottom'        => '0',
				'searchform'                        => 'show',
				'social_media_style'                => 'light',
				'highlight'                         => $val[1],
				'link_color'                        => $val[1],
				'link_hover_color'                  => $val[2],
				'footer_link_color'                 => '#cccccc',
				'footer_link_hover_color'           => '#ffffff',
				'custom_styles'                     => '',
			);

			switch ( $style ) {

				case 'style-1':
					$settings = wp_parse_args( array(
						'header_height'              => '70px',
						'header_apply_border_bottom' => '0',
						'header_text'                => '',
						'font_menu_sp'               => '1px',
						'font_menu'                  => array(
							'color'       => '#ffffff',
							'size'        => '11px',
							'face'        => 'google',
							'style'       => 'uppercase',
							'weight'      => '700',
							'google'      => 'Lato:700',
							'typekit'     => '',
							'typekit_kit' => '',
						),
					), $settings);

					if ( 'default' === $key ) {
						$settings['header_apply_border_bottom'] = '1';
						$settings['header_border_bottom_width'] = '1px';
						$settings['header_border_bottom_color'] = '#f2f2f2';
						$settings['footer_apply_border_top'] = '1';
						$settings['footer_border_top_width'] = '1px';
						$settings['footer_border_top_color'] = '#f2f2f2';
						$settings['header_text_color'] = 'dark';
						$settings['header_mobile_bg_color'] = '#333333';
						$settings['menu_highlight'] = '#333333';
						$settings['menu_sub_bg_color_brightness'] = 'light';
						$settings['menu_mobile_bg_color'] = '#222222';
						$settings['side_bg_color'] = '#222222';
						$settings['footer_bg_color_brightness'] = 'light';
						$settings['highlight'] = '#fec527';
						$settings['link_color'] = '#f9d718';
						$settings['link_hover_color'] = '#f9bc18';
						$settings['footer_link_color'] = '#f9d718';
						$settings['footer_link_hover_color'] = '#f9bc18';
						$settings['social_media_style'] = 'dark';
						$settings['font_menu']['color'] = '#333333';
					}

					break;

				case 'style-2':
					$settings = wp_parse_args( array(
						'top_bg_color'               => $val[1],
						'top_bg_color_opacity'       => '1',
						'top_text_color'             => 'light',
						'top_apply_border_bottom'    => '1',
						'top_border_bottom_color'    => $val[2],
						'top_border_bottom_width'    => '1px',
						'header_height'              => '70px',
						'header_apply_border_bottom' => '1',
						'header_border_bottom_width' => '1px',
						'header_border_bottom_color' => $val[2],
						'header_text'                => '%phone% 1-800-555-5555 %envelope% admin@yoursite.com',
						'font_menu'                  => array(
							'color'       => '#ffffff',
							'size'        => '13px',
							'face'        => 'google',
							'style'       => 'normal',
							'weight'      => '300',
							'google'      => 'Raleway:300',
							'typekit'     => '',
							'typekit_kit' => '',
						),
					), $settings);

					if ( 'default' === $key ) {
						$settings['top_text_color'] = 'dark';
						$settings['top_border_bottom_color'] = '#f2f2f2';
						$settings['header_border_bottom_color'] = '#f2f2f2';
						$settings['footer_apply_border_top'] = '1';
						$settings['footer_border_top_width'] = '1px';
						$settings['footer_border_top_color'] = '#f2f2f2';
						$settings['header_text_color'] = 'dark';
						$settings['header_mobile_bg_color'] = '#333333';
						$settings['menu_highlight'] = '#333333';
						$settings['menu_sub_bg_color_brightness'] = 'light';
						$settings['menu_mobile_bg_color'] = '#222222';
						$settings['side_bg_color'] = '#222222';
						$settings['footer_bg_color_brightness'] = 'light';
						$settings['highlight'] = '#fec527';
						$settings['link_color'] = '#f9d718';
						$settings['link_hover_color'] = '#f9bc18';
						$settings['footer_link_color'] = '#f9d718';
						$settings['footer_link_hover_color'] = '#f9bc18';
						$settings['social_media_style'] = 'dark';
						$settings['font_menu']['color'] = '#333333';
					}

					break;

				case 'style-3':
					$settings = wp_parse_args( array(
						'top_bg_color'               => $val[2],
						'top_bg_color_opacity'       => '1',
						'top_text_color'             => 'light',
						'top_apply_border_bottom'    => '0',
						'header_height'              => '46px',
						'header_apply_border_bottom' => '0',
						'header_text'                => '%phone% 1-800-555-5555 %envelope% admin@yoursite.com',
						'font_menu'                  => array(
							'color'       => '#ffffff',
							'size'        => '13px',
							'face'        => 'google',
							'style'       => 'normal',
							'weight'      => '600',
							'google'      => 'Raleway:600',
							'typekit'     => '',
							'typekit_kit' => '',
						),
					), $settings);

					if ( 'default' === $key ) {
						$settings['top_bg_color'] = '#1b1b1b';
						$settings['header_bg_color'] = '#252525';
						$settings['highlight'] = '#fec527';
						$settings['menu_sub_bg_color'] = '#1b1b1b';
						$settings['menu_sub_bg_color'] = '#1b1b1b';
						$settings['menu_mobile_bg_color'] = '#1b1b1b';
						$settings['side_bg_color'] = '#1b1b1b';
						$settings['footer_bg_color'] = '#1b1b1b';
						$settings['link_color'] = '#f9d718';
						$settings['link_hover_color'] = '#f9bc18';
						$settings['footer_link_color'] = '#f9d718';
						$settings['footer_link_hover_color'] = '#f9bc18';
					}

					break;

				case 'style-4':
					$settings = wp_parse_args( array(
						'top_bg_color'               => '#ffffff',
						'top_bg_color_opacity'       => '1',
						'top_text_color'             => 'dark',
						'top_apply_border_bottom'    => '0',
						'header_height'              => '70px',
						'header_apply_border_bottom' => '0',
						'header_text'                => '%phone% 1-800-555-5555 %envelope% admin@yoursite.com',
						'social_media_style'         => 'dark',
						'font_menu'                  => array(
							'color'       => '#ffffff',
							'size'        => '11px',
							'face'        => 'google',
							'style'       => 'uppercase',
							'weight'      => '300',
							'google'      => 'Lato:300',
							'typekit'     => '',
							'typekit_kit' => '',
						),
					), $settings);

					if ( 'default' === $key ) {
						$settings['header_bg_color'] = '#252525';
						$settings['highlight'] = '#fec527';
						$settings['menu_sub_bg_color'] = '#1b1b1b';
						$settings['menu_sub_bg_color'] = '#1b1b1b';
						$settings['menu_mobile_bg_color'] = '#1b1b1b';
						$settings['side_bg_color'] = '#1b1b1b';
						$settings['footer_bg_color'] = '#1b1b1b';
						$settings['link_color'] = '#f9d718';
						$settings['link_hover_color'] = '#f9bc18';
						$settings['footer_link_color'] = '#f9d718';
						$settings['footer_link_hover_color'] = '#f9bc18';
					}
			}

			$args['sets'][ $style . '-' . $key ] = wp_parse_args( array(
				'id'       => $style . '-' . $key,
				'name'     => $name . ' - ' . $val[0],
				'settings' => $settings,
			), $atts);

			if ( 'default' === $key ) {
				$args['sets'][ $style . '-' . $key ]['icon_width'] = '100%';
				$args['sets'][ $style . '-' . $key ]['icon_height'] = '';
				$args['sets'][ $style . '-' . $key ]['icon_style'] = '';
			}

			$args['sets'][ $style . '-' . $key ]['icon'] .= 'preset-' . $style . '-' . $key . '.jpg';

		}
	}

	/**
	 * Filters the preset styles added by the
	 * Entrepreneur base.
	 *
	 * @since Jump_Start 2.1.1
	 *
	 * @param array $args All presets added.
	 * @param array $colors Color names and hex values.
	 */
	$args = apply_filters( 'jumpstart_ent_style_presets', $args, $colors );

	if ( $preset && isset( $args['sets'][ $preset ]['settings'] ) ) {

		$args = $args['sets'][ $preset ]['settings'];

	}

	return $args;

}
