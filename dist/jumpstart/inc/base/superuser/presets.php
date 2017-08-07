<?php
/**
 * Super User theme base preset style functions.
 *
 * @author		Jason Bobich
 * @copyright	2009-2017 Theme Blvd
 * @link		http://themeblvd.com
 * @package 	Jump_Start
 */

/**
 * Get option presets.
 *
 * @since 2.1.1
 */
function jumpstart_su_get_presets( $preset = '' ) {

	$args = array(
		'id'			=> 'style_presets',
		'tab' 			=> 'styles',
		'section'		=> 'presets',
		'sets'			=> array()
	);

	// Predefined colors
    $colors = apply_filters('jumpstart_su_preset_colors', array(
        'default'       => array( __('Default', 'jumpstart'),       '#333333', '#222222' ),
        'green'         => array( __('Green', 'jumpstart'),         '#4fba6f', '#4ca165' ),
        'sea-green'     => array( __('Sea Green', 'jumpstart'),     '#18a185', '#268f77' ),
        'blue'          => array( __('Blue', 'jumpstart'),          '#2880ba', '#3174a2' ),
        'midnight-blue' => array( __('Midnight Blue', 'jumpstart'), '#2c3e50', '#1a2836' ),
        'purple'        => array( __('Purple', 'jumpstart'),        '#894b9d', '#7b478b' ),
        'orange'        => array( __('Orange', 'jumpstart'),        '#d15627', '#b85128' ),
        'red'           => array( __('Red', 'jumpstart'),           '#c03b2b', '#a93b2a' ),
        'grey'          => array( __('Grey', 'jumpstart'),          '#7f8c8d', '#727e7e' )
    ));

    // Predefined attributes
    $atts = array(
		'icon'			=> trailingslashit( themeblvd_get_base_uri( 'superuser' ) ) . 'img/',
        'icon_width'	=> '70',
        'icon_height'	=> '40',
        'icon_style'    => 'width: 70px; height: 40px',
        'settings'      => array()
    );

    $styles = array(
        'style-1' => __('Style 1', 'jumpstart'),
        'style-2' => __('Style 2', 'jumpstart'),
        'style-3' => __('Style 3', 'jumpstart'),
		'style-4' => __('Style 4', 'jumpstart'),
		'style-5' => __('Style 5', 'jumpstart')
    );

	// Do we only want return one preset?
    if ( $preset ) {

        $parts = explode('-', $preset);
        $color = $parts[2];

        if ( isset( $parts[3] ) ) {
            $color .= '-' . $parts[3];
        }

        foreach ( $colors as $key => $val ) {
            if ( $key != $color ) {
                unset($colors[$key]);
            }
        }

        foreach ( $styles as $key => $val ) {
            if ( $key != $parts[0] . '-' . $parts[1] ) {
                unset($styles[$key]);
            }
        }

    }

    // Build presets
	foreach ( $styles as $style => $name ) {
        foreach ( $colors as $key => $val ) {

			$settings = array(
				'layout_style'						=> 'stretch',
				'apply_content_border'				=> '0',
				'style'								=> 'light',
				'top_bg_color_opacity'				=> '1',
				'top_apply_border_bottom'			=> '0',
				'top_mini'							=> '0',
				'header_bg_type'					=> 'color',
				'header_bg_color_opacity' 			=> '1',
				'header_apply_border_top' 			=> '0',
				'header_apply_border_bottom'		=> '0',
				'header_apply_padding' 				=> '0',
				'logo_center' 						=> '0',
				'header_mobile_bg_color' 			=> $val[1],
				'header_mobile_bg_color_brightness'	=> 'dark',
				'menu_bg_type' 						=> 'color',
				'menu_bg_color_opacity' 			=> '1',
				'menu_bg_color_brightness' 			=> 'dark',
				'menu_hover_bg_color_opacity'		=> '1',
				'menu_hover_bg_color_brightness'	=> 'dark',
				'menu_apply_border_top'				=> '0',
				'menu_apply_border_bottom'			=> '0',
				'menu_text_shadow'					=> '0',
				'menu_divider'						=> '0',
				'menu_center' 						=> '0',
				'menu_search' 						=> '0',
				'menu_mobile_bg_color' 				=> $val[2],
				'menu_mobile_bg_color_brightness' 	=> 'dark',
				'side_bg_color' 					=> $val[2],
				'side_bg_color_brightness' 			=> 'dark',
				'footer_bg_type' 					=> 'color',
				'footer_bg_color_opacity' 			=> '1',
				'footer_apply_border_top' 			=> '0',
				'footer_apply_border_bottom' 		=> '0',
		        'header_text'                       => '%phone% 1-800-555-5555 %envelope% admin@yoursite.com',
		        'searchform'                        => 'show',
		        'social_media_style'                => 'light',
		        'custom_styles'                     => '',
				'highlight'                         => $val[1],
				'link_color'                        => $val[1],
                'link_hover_color'                  => $val[2],
                'footer_link_color'                 => '#cccccc',
                'footer_link_hover_color'           => '#ffffff',
				'font_menu_sp'						=> '1px'
			);

			switch ( $style ) {

                case 'style-1' :

					$settings = wp_parse_args(array(
						'top_bg_color'						=> '#ffffff',
						'top_text_color'					=> 'dark',
						'header_bg_color'					=> '#f8f8f8',
						'header_text_color'					=> 'dark',
						'header_info'						=> 'header_top',
						'menu_bg_color' 					=> $val[1],
						'menu_hover_bg_color'				=> $val[2],
						'menu_sub_bg_color' 				=> '#ffffff',
						'menu_sub_bg_color_brightness'		=> 'light',
						'footer_bg_color' 					=> '#ffffff',
						'footer_bg_color_brightness' 		=> 'light',
						'footer_link_color'             	=> $val[1],
		                'footer_link_hover_color'       	=> $val[2],
						'social_media_style'                => 'grey',
						'font_menu'							=> array(
							'size'			=> '13px',
							'face'			=> 'google',
							'style' 		=> 'normal',
							'weight' 		=> '300',
							'google' 		=> 'Raleway:300',
							'typekit'		=> '',
							'typekit_kit'	=> ''
						)
					), $settings);

					if ( $key == 'default' ) {
						$settings['highlight'] = '#f9bc18';
						$settings['link_color'] = '#f9bc18';
						$settings['link_hover_color'] = '#f9d718';
						$settings['footer_link_color'] = '#f9bc18';
						$settings['footer_link_hover_color'] = '#f9d718';
					}

					break;

				case 'style-2' :

					$settings = wp_parse_args(array(
						'top_bg_color'						=> '#1b1b1b',
						'top_text_color'					=> 'light',
						'header_bg_color'					=> '#222222',
						'header_text_color'					=> 'light',
						'header_info'						=> 'header_top',
						'header_apply_border_bottom'        => '1',
                        'header_border_bottom_color'        => '#1b1b1b',
                        'header_border_bottom_width'        => '5px',
						'menu_bg_color' 					=> $val[1],
						'menu_hover_bg_color'				=> $val[2],
						'menu_sub_bg_color' 				=> '#1b1b1b',
						'menu_sub_bg_color_brightness'		=> 'dark',
						'menu_mobile_bg_color' 				=> '#222222',
						'side_bg_color' 					=> '#222222',
						'footer_bg_color' 					=> '#222222',
						'footer_bg_color_brightness' 		=> 'dark',
						'footer_link_color'             	=> $val[1],
		                'footer_link_hover_color'       	=> $val[2],
						'font_menu'							=> array(
							'size'			=> '13px',
							'face'			=> 'google',
							'style' 		=> 'normal',
							'weight' 		=> '300',
							'google' 		=> 'Raleway:300',
							'typekit'		=> '',
							'typekit_kit'	=> ''
						)
					), $settings);

					if ( $key == 'default' ) {
						$settings['menu_bg_color'] = '#ffffff';
						$settings['menu_bg_color_brightness'] = 'light';
						$settings['menu_hover_bg_color'] = '#f8f8f8';
						$settings['menu_hover_bg_color_brightness'] = 'light';
						$settings['highlight'] = '#f9bc18';
						$settings['link_color'] = '#f9bc18';
						$settings['link_hover_color'] = '#f9d718';
						$settings['footer_link_color'] = '#f9bc18';
						$settings['footer_link_hover_color'] = '#f9d718';
					}

					break;

				case 'style-3' :

					$settings = wp_parse_args(array(
						'header_info'						=> 'header_addon',
						'header_bg_color'					=> '#222222',
						'header_text_color'					=> 'light',
						'header_apply_border_bottom'        => '1',
                        'header_border_bottom_color'        => $val[1],
                        'header_border_bottom_width'        => '5px',
						'menu_bg_color'						=> '#1b1b1b',
						'menu_hover_bg_color'				=> '#0f0f0f',
						'menu_sub_bg_color' 				=> '#0f0f0f',
						'menu_sub_bg_color_brightness'		=> 'dark',
						'menu_mobile_bg_color' 				=> '#222222',
						'side_bg_color' 					=> '#222222',
						'footer_bg_color' 					=> '#222222',
						'footer_bg_color_brightness' 		=> 'dark',
						'footer_link_color'             	=> $val[1],
		                'footer_link_hover_color'       	=> $val[2],
						'custom_styles'						=> "/**\r\n * Custom CSS Example: Add highlight color\r\n * to active top-level main menu item.\r\n */\r\n.tb-primary-menu > li.current-menu-item > .menu-btn,\r\n.tb-primary-menu > li.current-menu-item > .menu-btn:hover {\r\n\tbackground-color: " . $val[1] . ";\r\n}\r\n.header-nav .tb-primary-menu ul.non-mega-sub-menu,\r\n.header-nav .tb-primary-menu .sf-mega {\r\n\tmargin-top: 0;\r\n}",
						'font_menu'							=> array(
							'size'			=> '13px',
							'face'			=> 'google',
							'style' 		=> 'normal',
							'weight' 		=> '300',
							'google' 		=> 'Raleway:300',
							'typekit'		=> '',
							'typekit_kit'	=> ''
						)
					), $settings);

					if ( $key == 'default' ) {
						$settings['header_border_bottom_color'] = '#ffffff';
						$settings['highlight'] = '#f9bc18';
						$settings['link_color'] = '#f9bc18';
						$settings['link_hover_color'] = '#f9d718';
						$settings['footer_link_color'] = '#f9bc18';
						$settings['footer_link_hover_color'] = '#f9d718';
						$settings['custom_styles'] = "/**\r\n * Custom CSS Example: Add highlight color\r\n * to active top-level main menu item.\r\n */\r\n.tb-primary-menu > li.current-menu-item > .menu-btn,\r\n.tb-primary-menu > li.current-menu-item > .menu-btn:hover {\r\n\tbackground-color: #ffffff;\r\n\tcolor: #333333;\r\n}\r\n.header-nav .tb-primary-menu ul.non-mega-sub-menu,\r\n.header-nav .tb-primary-menu .sf-mega {\r\n\tmargin-top: 0;\r\n}";
					}

					break;

				case 'style-4' :

					$settings = wp_parse_args(array(
						'header_info'						=> 'header_addon',
						'header_bg_color'					=> $val[1],
						'header_text_color'					=> 'light',
						'header_apply_border_bottom'        => '1',
                        'header_border_bottom_color'        => $val[2],
                        'header_border_bottom_width'        => '1px',
						'logo_center'						=> '1',
						'menu_bg_color'						=> $val[1],
						'menu_hover_bg_color'				=> '#000000',
						'menu_hover_bg_color_opacity'		=> '0.05',
						'menu_sub_bg_color' 				=> $val[1],
						'menu_sub_bg_color_brightness'		=> 'dark',
						'menu_mobile_bg_color' 				=> $val[1],
						'menu_apply_border_top'				=> '1',
						'menu_border_top_color'				=> $val[2],
						'menu_border_top_width'				=> '1px',
						'menu_center'						=> '1',
						'menu_divider'						=> '1',
						'menu_divider_color'				=> $val[2],
						'menu_search'						=> '1',
						'footer_bg_color' 					=> $val[2],
						'footer_bg_color_brightness' 		=> 'dark',
						'header_text'						=> '',
						'searchform'						=> 'hide',
						'font_menu'							=> array(
							'size'			=> '14px',
							'face'			=> 'google',
							'style' 		=> 'normal',
							'weight' 		=> '300',
							'google' 		=> 'Raleway:300',
							'typekit'		=> '',
							'typekit_kit'	=> ''
						)
					), $settings);

					if ( $key == 'default' ) {
						$settings['header_bg_color'] = '#ffffff';
						$settings['header_text_color'] = 'dark';
						$settings['header_border_bottom_color'] = '#dddddd';
						$settings['menu_bg_color'] = '#ffffff';
						$settings['menu_bg_color_brightness'] = 'light';
						$settings['menu_hover_bg_color'] = '#f8f8f8';
						$settings['menu_hover_bg_color_brightness'] = 'light';
						$settings['menu_border_top_color'] = '#dddddd';
						$settings['menu_sub_bg_color'] = '#ffffff';
						$settings['menu_sub_bg_color_brightness'] = 'light';
						$settings['menu_divider_color'] = '#dddddd';
						$settings['footer_bg_color'] = '#ffffff';
						$settings['footer_bg_color_brightness'] = 'light';
						$settings['highlight'] = '#f9bc18';
						$settings['link_color'] = '#f9bc18';
						$settings['link_hover_color'] = '#f9d718';
						$settings['footer_link_color'] = '#f9bc18';
						$settings['footer_link_hover_color'] = '#f9d718';
						$settings['social_media_style'] = 'dark';
					}

					break;

				case 'style-5' :

					$settings = wp_parse_args(array(
						'top_bg_color'						=> $val[2],
						'top_text_color'					=> 'light',
						'top_mini'							=> '1',
						'header_info'						=> 'header_top',
						'header_bg_color'					=> $val[1],
						'header_text_color'					=> 'light',
						'logo_center'						=> '1',
						'menu_bg_color'						=> $val[1],
						'menu_hover_bg_color'				=> $val[1],
						'menu_sub_bg_color' 				=> $val[2],
						'menu_sub_bg_color_brightness'		=> 'dark',
						'menu_mobile_bg_color' 				=> $val[1],
						'menu_center'						=> '1',
						'menu_divider_color'				=> $val[2],
						'footer_bg_color' 					=> $val[2],
						'footer_bg_color_brightness' 		=> 'dark',
						'custom_styles'						=> "/**\r\n * Custom CSS Example: Add 20px of space\r\n * to bottom of header, below menu.\r\n */\r\n@media (min-width: 992px) {\r\n\t.site-header {\r\n\t\tpadding-bottom: 20px;\r\n\t}\r\n\t.header-nav .tb-primary-menu .menu-search .tb-search-trigger,\r\n\t\t.tb-primary-menu > li > a {\r\n\t\topacity: .8;\r\n\t}\r\n\t.header-nav .tb-primary-menu .menu-search .tb-search-trigger:hover,\r\n\t.tb-primary-menu > li > a:hover {\r\n\t\tbackground: transparent;\r\n\t\topacity: 1;\r\n\t}\r\n}",
						'font_menu'							=> array(
							'size'			=> '11px',
							'face'			=> 'google',
							'style' 		=> 'uppercase',
							'weight' 		=> '700',
							'google' 		=> 'Lato:700',
							'typekit'		=> '',
							'typekit_kit'	=> ''
						)
					), $settings);

					if ( $key == 'default' ) {
						$settings['top_bg_color'] = '#222222';
						$settings['header_bg_color'] = '#ffffff';
						$settings['header_text_color'] = 'dark';
						$settings['header_mobile_bg_color'] = '#222222';
						$settings['menu_sub_bg_color'] = '#222222';
						$settings['menu_bg_color'] = '#ffffff';
						$settings['menu_bg_color_brightness'] = 'light';
						$settings['menu_hover_bg_color'] = '#ffffff';
						$settings['menu_hover_bg_color_brightness'] = 'light';
						$settings['side_bg_color'] = '#222222';
						$settings['footer_bg_color'] = '#ffffff';
						$settings['footer_bg_color_brightness'] = 'light';
						$settings['highlight'] = '#f9bc18';
						$settings['link_color'] = '#f9bc18';
						$settings['link_hover_color'] = '#f9d718';
						$settings['footer_link_color'] = '#f9bc18';
						$settings['footer_link_hover_color'] = '#f9d718';
					}

			}

			$args['sets'][$style.'-'.$key] = wp_parse_args(array(
                'id'        => $style.'-'.$key,
                'name'      => $name . ' - ' . $val[0],
                'settings'  => $settings
            ), $atts);

            if ( $key == 'default' ) {
                $args['sets'][$style.'-'.$key]['icon_width'] = '100%';
                $args['sets'][$style.'-'.$key]['icon_height'] = '';
                $args['sets'][$style.'-'.$key]['icon_style'] = '';
            }

            $args['sets'][$style.'-'.$key]['icon'] .= 'preset-' . $style . '-' . $key . '.jpg';

		}
	}

	$args = apply_filters('jumpstart_su_style_presets', $args, $colors);

    if ( $preset && isset( $args['sets'][$preset]['settings'] ) ) {
        $args = $args['sets'][$preset]['settings'];
    }

    return $args;
}
