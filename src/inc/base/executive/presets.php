<?php
/**
 * Theme Base: Executive, Style Presets
 *
 * @author    Jason Bobich <info@themeblvd.com>
 * @copyright 2009-2017 Theme Blvd
 * @package   @@name-package
 * @since     @@name-package 2.1.1
 */

/**
 * Get option presets.
 *
 * @since @@name-package 2.1.1
 *
 * @param  string $preset Optional preset style to retrieve.
 * @return array  $args   All preset styles or specific preset if $preset specified.
 */
function jumpstart_ex_get_presets( $preset = '' ) {

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
	 * @since @@name-package 2.1.1
	 *
	 * @param $hex array Color values.
	 */
	$colors = apply_filters( 'jumpstart_ex_preset_colors', array(
		'default'       => array( __( 'Default', 'jumpstart' ), '#ffffff', '#ffffff' ),
		'green'         => array( __( 'Green', 'jumpstart' ), '#4fba6f', '#4ca165' ),
		'sea-green'     => array( __( 'Sea Green', 'jumpstart' ), '#18a185', '#268f77' ),
		'blue'          => array( __( 'Blue', 'jumpstart' ), '#2880ba', '#3174a2' ),
		'midnight-blue' => array( __( 'Midnight Blue', 'jumpstart' ), '#2c3e50', '#1a2836' ),
		'purple'        => array( __( 'Purple', 'jumpstart' ), '#894b9d', '#7b478b' ),
		'orange'        => array( __( 'Orange', 'jumpstart' ), '#d15627', '#b85128' ),
		'red'           => array( __( 'Red', 'jumpstart' ), '#c03b2b', '#a93b2a' ),
		'grey'          => array( __( 'Grey', 'jumpstart' ), '#7f8c8d', '#727e7e' ),
	) );

	// Predefined attributes.
	$atts = array(
		'icon'        => trailingslashit( themeblvd_get_base_uri( 'executive' ) ) . 'img/',
		'icon_width'  => '70',
		'icon_height' => '40',
		'icon_style'  => 'width: 70px; height: 40px',
		'settings'    => array(),
	);

	$styles = array(
		'style-1' => __( 'Style 1', 'jumpstart' ),
		'style-2' => __( 'Style 2', 'jumpstart' ),
		'style-3' => __( 'Style 3', 'jumpstart' ),
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

			if ( 'style-3' === $style && 'default' === $key ) {

				$val[1] = '#333333';

				$val[2] = '#222222';

			}

			$settings = array(
				'layout_style'                      => 'stretch',
				'apply_content_border'              => '1',
				'content_border_color'              => '#dddddd',
				'content_border_width'              => '1px',
				'style'                             => 'light',
				'header_bg_type'                    => 'color',
				'header_text_color'                 => 'dark',
				'header_bg_color'                   => '#ffffff',
				'header_bg_color_opacity'           => '1',
				'header_mobile_bg_color'            => $val[1],
				'header_mobile_bg_color_brightness' => 'dark',
				'header_mobile_icon_color'          => '#ffffff',
				'menu_bg_type'                      => 'color',
				'menu_bg_color'                     => $val[1],
				'menu_bg_color_opacity'             => '1',
				'menu_bg_color_brightness'          => 'dark',
				'menu_hover_bg_color'               => $val[2],
				'menu_hover_bg_color_opacity'       => '1',
				'menu_hover_bg_color_brightness'    => 'dark',
				'menu_sub_bg_color'                 => $val[1],
				'menu_sub_bg_color_brightness'      => 'dark',
				'menu_text_shadow'                  => '0',
				'side_bg_color'                     => $val[2],
				'side_bg_color_brightness'          => 'dark',
				'footer_apply_border_top'           => '0',
				'footer_bg_type'                    => 'color',
				'footer_bg_color'                   => $val[1],
				'footer_bg_color_brightness'        => 'dark',
				'footer_bg_color_opacity'           => '1',
				'copyright_apply_bg'                => '0',
				'highlight'                         => $val[1],
				'link_color'                        => $val[1],
				'link_hover_color'                  => $val[2],
				'footer_link_color'                 => '#cccccc',
				'footer_link_hover_color'           => '#ffffff',
				'widget_style'                      => 'panel',
				'widget_panel_style'                => 'default',
				'widget_bg_color'                   => '#ffffff',
				'widget_bg_brightness'              => 'light',
				'widget_bg_color_opacity'           => '1',
				'widget_title_color'                => '#333333',
				'widget_title_size'                 => '16px',
				'widget_title_shadow'               => '0',
				'header_text'                       => '%phone% 1-800-555-5555 %envelope% admin@yoursite.com',
				'social_media_style'                => 'dark',
				'searchform'                        => 'show',
				'font_menu_sp'                      => '0px',
				'custom_styles'                     => '',
				'trans_logo'                        => array(
					'type'         => 'image',
					'image'        => get_template_directory_uri() . '/assets/img/logo-light.png',
					'image_width'  => '250',
					'image_height' => '75',
					'image_2x'     => get_template_directory_uri() . '/assets/img/logo-light_2x.png',
				),
			);

			switch ( $style ) {

				case 'style-1':
					$settings = wp_parse_args( array(
						'header_info'                       => 'header_addon',
						'header_apply_border_top'           => '1',
						'header_border_top_color'           => $val[1],
						'header_border_top_width'           => '7px',
						'header_apply_border_bottom'        => '0',
						'header_apply_padding_top'          => '0',
						'header_apply_padding_bottom'       => '0',
						'logo_center'                       => '0',
						'menu_hover_bg_color'               => '#000000',
						'menu_hover_bg_color_opacity'       => '0.3',
						'menu_corners'                      => '0px',
						'menu_apply_border'                 => '0',
						'menu_divider'                      => '0',
						'menu_center'                       => '0',
						'menu_search'                       => '0',
						'footer_bg_color'                   => '#ffffff',
						'footer_bg_color_brightness'        => 'light',
						'footer_apply_border_top'           => '1',
						'footer_border_top_color'           => $val[1],
						'footer_border_top_width'           => '7px',
						'footer_link_color'                 => $val[1],
						'footer_link_hover_color'           => $val[2],
						'font_menu'                         => array(
							'size'         => '13px',
							'face'         => 'google',
							'style'        => 'normal',
							'weight'       => '300',
							'google'       => 'Raleway:300',
							'typekit'      => '',
							'typekit_kit'  => '',
						),
						'logo'                              => array(
							'type'         => 'image',
							'image'        => get_template_directory_uri() . '/assets/img/logo.png',
							'image_width'  => '250',
							'image_height' => '75',
							'image_2x'     => get_template_directory_uri() . '/assets/img/logo_2x.png',
						),
					), $settings );

					if ( 'default' === $key ) {

						$settings['header_border_top_color'] = '#333333';

						$settings['header_mobile_bg_color'] = '#333333';

						$settings['menu_bg_color'] = '#333333';

						$settings['menu_sub_bg_color'] = '#333333';

						$settings['side_bg_color'] = '#222222';

						$settings['footer_bg_color'] = '#ffffff';

						$settings['footer_bg_color_brightness'] = 'light';

						$settings['highlight'] = '#fec527';

						$settings['link_color'] = '#f9d718';

						$settings['link_hover_color'] = '#f9bc18';

						$settings['footer_link_color'] = '#f9d718';

						$settings['footer_link_hover_color'] = '#f9bc18';

						$settings['social_media_style'] = 'dark';

					}

					break;

				case 'style-2':
					$settings = wp_parse_args( array(
						'header_info'                       => 'header_top',
						'top_bg_color'                      => $val[1],
						'top_bg_color_opacity'              => '1',
						'top_text_color'                    => 'light',
						'top_apply_border_bottom'           => '1',
						'top_border_bottom_color'           => $val[2],
						'top_border_bottom_width'           => '1px',
						'top_mini'                          => '0',
						'header_text_color'                 => 'light',
						'header_bg_color'                   => $val[1],
						'header_apply_border_top'           => '1',
						'header_border_top_color'           => $val[2],
						'header_border_top_width'           => '7px',
						'header_apply_border_bottom'        => '1',
						'header_border_bottom_color'        => $val[2],
						'header_border_bottom_width'        => '1px',
						'header_apply_padding_top'          => '0',
						'header_apply_padding_bottom'       => '1',
						'header_padding_bottom'             => '0px',
						'logo_center'                       => '0',
						'menu_bg_type'                      => 'gradient',
						'menu_bg_gradient'                  => array(
							'start' => $val[1],
							'end'   => $val[2],
						),
						'menu_corners'                      => '4px',
						'menu_apply_border'                 => '1',
						'menu_border_color'                 => $val[2],
						'menu_border_width'                 => '1px',
						'menu_divider'                      => '1',
						'menu_divider_color'                => $val[2],
						'menu_center'                       => '0',
						'menu_search'                       => '1',
						'footer_apply_border_top'           => '0',
						'social_media_style'                => 'color',
						'custom_styles'                     => "/**\r\n * Custom CSS Example: Move dropdown menus down 1px\r\n */\r\n.tb-primary-menu ul.non-mega-sub-menu,\r\n.tb-primary-menu .sf-mega {\r\n\tmargin-top: 1px;\r\n}",
						'font_menu'                         => array(
							'color'        => '#ffffff',
							'size'         => '14px',
							'face'         => 'google',
							'style'        => 'normal',
							'weight'       => '300',
							'google'       => 'Raleway:300',
							'typekit'      => '',
							'typekit_kit'  => '',
						),
						'logo'                              => array(
							'type'         => 'image',
							'image'        => get_template_directory_uri() . '/assets/img/logo-light.png',
							'image_width'  => '250',
							'image_height' => '75',
							'image_2x'     => get_template_directory_uri() . '/assets/img/logo-light_2x.png',
						),
					), $settings );

					if ( 'default' === $key ) {

						$settings['top_text_color'] = 'dark';

						$settings['top_border_bottom_color'] = '#dddddd';

						$settings['header_text_color'] = 'dark';

						$settings['header_border_top_color'] = '#333333';

						$settings['header_border_bottom_color'] = '#dddddd';

						$settings['header_mobile_bg_color'] = '#333333';

						$settings['menu_bg_gradient'] = array(
							'start' => '#fefefe',
							'end'   => '#f3f3f3',
						);

						$settings['menu_bg_color_brightness'] = 'light';

						$settings['menu_hover_bg_color_brightness'] = 'light';

						$settings['menu_border_color'] = '#dddddd';

						$settings['menu_divider_color'] = '#dddddd';

						$settings['menu_sub_bg_color_brightness'] = 'light';

						$settings['side_bg_color'] = '#333333';

						$settings['highlight'] = '#fec527';

						$settings['link_color'] = '#f9d718';

						$settings['link_hover_color'] = '#f9bc18';

						$settings['footer_link_color'] = '#f9d718';

						$settings['footer_link_hover_color'] = '#f9bc18';

						$settings['logo'] = array(
							'type'         => 'image',
							'image'        => get_template_directory_uri() . '/assets/img/logo.png',
							'image_width'  => '250',
							'image_height' => '75',
							'image_2x'     => get_template_directory_uri() . '/assets/img/logo_2x.png',
						);

					}

					break;

				case 'style-3':
					$settings = wp_parse_args( array(
						'apply_content_border'              => '0',
						'header_info'                       => 'header_top',
						'top_bg_color'                      => $val[1],
						'top_bg_color_opacity'              => '1',
						'top_text_color'                    => 'light',
						'top_apply_border_bottom'           => '0',
						'top_mini'                          => '1',
						'header_apply_border_top'           => '0',
						'header_apply_border_bottom'        => '0',
						'header_apply_padding_top'          => '1',
						'header_padding_top'                => '40px',
						'header_apply_padding_bottom'       => '1',
						'header_padding_bottom'             => '40px',
						'logo_center'                       => '1',
						'menu_bg_color'                     => '#ffffff',
						'menu_bg_color_brightness'          => 'light',
						'menu_hover_bg_color'               => $val[1],
						'menu_hover_bg_color_brightness'    => 'dark',
						'menu_corners'                      => '0px',
						'menu_divider'                      => '0',
						'menu_center'                       => '1',
						'menu_search'                       => '0',
						'menu_apply_border'                 => '1',
						'menu_border_color'                 => $val[1],
						'menu_border_width'                 => '4px',
						'footer_apply_border_top'           => '0',
						'widget_style'                      => 'standard',
						'widget_title_color'                => '#333333',
						'widget_title_size'                 => '16px',
						'social_media_style'                => 'light',
						'font_menu_sp'                      => '1px',
						'custom_styles'                     => ".header-nav {\n\tborder-right: none;\n\tborder-left: none;\n}\n.header-nav .tb-primary-menu .menu-search .tb-search-trigger:hover,\n.header-nav .tb-primary-menu > li > .menu-btn:hover {\n\tcolor: #ffffff;\n}",
						'font_menu'                         => array(
							'color'        => '#333333',
							'size'         => '10px',
							'face'         => 'google',
							'style'        => 'uppercase',
							'weight'       => '700',
							'google'       => 'Lato:700',
							'typekit'      => '',
							'typekit_kit'  => '',
						),
						'logo'                              => array(
							'type'         => 'image',
							'image'        => get_template_directory_uri() . '/assets/img/logo.png',
							'image_width'  => '250',
							'image_height' => '75',
							'image_2x'     => get_template_directory_uri() . '/assets/img/logo_2x.png',
						),
					), $settings );

					if ( 'default' === $key ) {

						$settings['highlight'] = '#fec527';

						$settings['link_color'] = '#f9d718';

						$settings['link_hover_color'] = '#f9bc18';

					}
			}

			$args['sets'][ $style . '-' . $key ] = wp_parse_args( array(
				'id'        => $style . '-' . $key,
				'name'      => $name . ' - ' . $val[0],
				'settings'  => $settings,
			), $atts );

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
	 * Executive base.
	 *
	 * @since @@name-package 2.1.1
	 *
	 * @param array $args   All presets added.
	 * @param array $colors Color names and hex values.
	 */
	$args = apply_filters( 'jumpstart_ex_style_presets', $args, $colors );

	if ( $preset && isset( $args['sets'][ $preset ]['settings'] ) ) {

		$args = $args['sets'][ $preset ]['settings'];

	}

	return $args;

}
