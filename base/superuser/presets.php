<?php
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
		'icon_width'	=> '240',
		'options'		=> array(),
		'sets'			=> array()
	);

	// Default

	$args['options']['default'] = get_template_directory_uri() . '/base/superuser/images/preset-default.jpg';

	$args['sets']['default'] = array(
		'layout_style'						=> 'stretch',
		'apply_content_border'				=> 0,
		'style'								=> 'light',
		'header_info'						=> 'header_top',
		'top_bg_color'						=> '#ffffff',
		'top_bg_color_opacity'				=> 1,
		'top_text_color'					=> 'dark',
		'top_apply_border_bottom'			=> 0,
		'top_mini'							=> 0,
		'header_bg_type'					=> 'color',
		'header_text_color'					=> 'dark',
		'header_bg_color' 					=> '#f8f8f8',
		'header_bg_color_opacity' 			=> 1,
		'header_apply_border_top' 			=> 0,
		'header_apply_border_bottom'		=> 0,
		'header_apply_padding' 				=> 0,
		'logo_center' 						=> 0,
		'header_mobile_bg_color' 			=> '#333333',
		'header_mobile_bg_color_brightness'	=> 'dark',
		'menu_bg_type' 						=> 'color',
		'menu_bg_color' 					=> '#333333',
		'menu_bg_color_opacity' 			=> 1,
		'menu_bg_color_brightness' 			=> 'dark',
		'menu_hover_bg_color' 				=> '#000000',
		'menu_hover_bg_color_opacity'		=> '0.3',
		'menu_hover_bg_color_brightness'	=> 'dark',
		'menu_sub_bg_color' 				=> '#ffffff',
		'menu_sub_bg_color_brightness'		=> 'light',
		'menu_apply_border_top'				=> 0,
		'menu_apply_border_bottom'			=> 0,
		'menu_text_shadow'					=> 0,
		'menu_divider'						=> 0,
		'menu_center' 						=> 0,
		'menu_search' 						=> 0,
		'menu_mobile_bg_color' 				=> '#222222',
		'menu_mobile_bg_color_brightness' 	=> 'dark',
		'side_bg_color' 					=> '#222222',
		'side_bg_color_brightness' 			=> 'dark',
		'footer_bg_type' 					=> 'color',
		'footer_bg_color' 					=> '#ffffff',
		'footer_bg_color_brightness' 		=> 'light',
		'footer_bg_color_opacity' 			=> 1,
		'footer_apply_border_top' 			=> 0,
		'footer_apply_border_bottom' 		=> 0,
        'header_text'                       => '%phone% 1-800-555-5555 %envelope% admin@yoursite.com',
        'searchform'                        => 'show',
        'social_media_style'                => 'grey',
        'custom_styles'                     => ''
	);

	$args['sets']['default']['font_menu'] = array(
        'color'         => '#ffffff',
		'size'			=> '13px',
		'face'			=> 'google',
		'style' 		=> 'normal',
		'weight' 		=> '300',
		'google' 		=> 'Raleway:300',
		'typekit'		=> '',
		'typekit_kit'	=> ''
	);

	$args['sets']['default']['font_menu_sp'] = '0px';

	// Preset 1

    $args['options']['preset-1'] = get_template_directory_uri() . '/base/superuser/images/preset-1.jpg';

    $val = get_option('jumpstart');

    $args['sets']['preset-1'] = array(
        'layout_style'						=> 'stretch',
		'apply_content_border'				=> 0,
		'style'								=> 'light',
		'header_info'						=> 'header_addon',
		'header_bg_type'					=> 'color',
		'header_text_color'					=> 'light',
		'header_bg_color' 					=> '#23282d',
		'header_bg_color_opacity' 			=> 1,
		'header_apply_border_top' 			=> 0,
		'header_apply_border_bottom'		=> 1,
        'header_border_bottom_color'        => '#0f74a8',
        'header_border_bottom_width'        => '5px',
		'header_apply_padding' 				=> 0,
		'logo_center' 						=> 0,
		'header_mobile_bg_color' 			=> '#23282d',
		'header_mobile_bg_color_brightness'	=> 'dark',
		'menu_bg_type' 						=> 'color',
		'menu_bg_color' 					=> '#1c2125',
		'menu_bg_color_opacity' 			=> 1,
		'menu_bg_color_brightness' 			=> 'dark',
		'menu_hover_bg_color' 				=> '#191b1f',
		'menu_hover_bg_color_opacity'		=> '1',
		'menu_hover_bg_color_brightness'	=> 'dark',
		'menu_sub_bg_color' 				=> '#191b1f',
		'menu_sub_bg_color_brightness'		=> 'dark',
		'menu_apply_border_top'				=> 0,
		'menu_apply_border_bottom'			=> 0,
		'menu_text_shadow'					=> 0,
		'menu_divider'						=> 0,
		'menu_center' 						=> 0,
		'menu_search' 						=> 0,
		'menu_mobile_bg_color' 				=> '#23282d',
		'menu_mobile_bg_color_brightness' 	=> 'dark',
		'side_bg_color' 					=> '#23282d',
		'side_bg_color_brightness' 			=> 'dark',
		'footer_bg_type' 					=> 'none',
		'footer_apply_border_top' 			=> 0,
		'footer_apply_border_bottom' 		=> 0,
        'header_text'                       => '%phone% 1-800-555-5555 %envelope% admin@yoursite.com',
        'searchform'                        => 'show',
        'social_media_style'                => 'light',
        'custom_styles'                     => "/**\r\n * Custom CSS Example: Add highlight color\r\n * to active top-level main menu item.\r\n */\r\n.tb-primary-menu > li.current-menu-item > .menu-btn {\r\n\tbackground-color: #0f74a8;\r\n}"
    );

    $args['sets']['preset-1']['font_menu'] = array(
        'color'         => '#ffffff',
		'size'			=> '13px',
		'face'			=> 'google',
		'style' 		=> 'normal',
		'weight' 		=> '300',
		'google' 		=> 'Raleway:300',
		'typekit'		=> '',
		'typekit_kit'	=> ''
	);

	$args['sets']['preset-1']['font_menu_sp'] = '0px';

	// Preset 2

    $args['options']['preset-2'] = get_template_directory_uri() . '/base/superuser/images/preset-2.jpg';

    $args['sets']['preset-2'] = array(
		'layout_style'						=> 'stretch',
		'apply_content_border'				=> 0,
		'style'								=> 'light',
        'header_info'                       => 'header_addon',
		'header_bg_type'					=> 'color',
		'header_text_color'					=> 'dark',
		'header_bg_color' 					=> '#ffffff',
		'header_bg_color_opacity' 			=> 1,
		'header_apply_border_top' 			=> 0,
		'header_apply_border_bottom'		=> 0,
		'header_apply_padding' 				=> 0,
		'logo_center' 						=> 1,
		'header_mobile_bg_color' 			=> '#ffffff',
		'header_mobile_bg_color_brightness'	=> 'light',
        'menu_bg_type'                      => 'color',
        'menu_bg_color'                     => '#ffffff',
        'menu_bg_color_opacity'             => '1',
        'menu_bg_color_brightness'          => 'light',
        'menu_hover_bg_color'               => '#f2f2f2',
        'menu_hover_bg_color_opacity'       => '1',
        'menu_hover_bg_color_brightness'    => 'light',
        'menu_sub_bg_color'                 => '#ffffff',
        'menu_sub_bg_color_brightness'      => 'light',
        'menu_apply_border_top'             => 1,
        'menu_border_top_color'             => '#dddddd',
        'menu_border_top_width'             => '1px',
        'menu_apply_border_bottom'          => 1,
        'menu_border_bottom_color'          => '#dddddd',
        'menu_border_bottom_width'          => '1px',
        'menu_text_shadow'                  => 0,
        'menu_divider'                      => 1,
        'menu_divider_color'                => '#dddddd',
        'menu_center'                       => 1,
        'menu_search'                       => 1,
		'menu_mobile_bg_color' 				=> '#f85f5b',
		'menu_mobile_bg_color_brightness' 	=> 'dark',
		'side_bg_color' 					=> '#f85f5b',
		'side_bg_color_brightness' 			=> 'dark',
		'footer_bg_type' 					=> 'color',
		'footer_bg_color' 					=> '#ffffff',
		'footer_bg_color_brightness' 		=> 'light',
		'footer_bg_color_opacity' 			=> 1,
		'footer_apply_border_top' 			=> 1,
        'footer_border_top_color'           => '#dddddd',
        'footer_border_top_width'           => '1px',
		'footer_apply_border_bottom' 		=> 0,
        'header_text'                       => '',
        'searchform'                        => 'hide',
        'social_media_style'                => 'grey',
        'custom_styles'                     => ''
	);

	$args['sets']['preset-2']['font_menu'] = array(
        'color'         => '#666666',
		'size'			=> '14px',
		'face'			=> 'google',
		'style' 		=> 'normal',
		'weight' 		=> '300',
		'google' 		=> 'Raleway:300',
		'typekit'		=> '',
		'typekit_kit'	=> ''
	);

	$args['sets']['preset-2']['font_menu_sp'] = '0px';

	return apply_filters('jumpstart_su_style_presets', $args);
}
