<?php
/**
 * Add theme options to framework.
 *
 * @since 2.0.0
 */
function jumpstart_ent_options() {

	// Background support
	add_theme_support( 'custom-background', array(
		'default-color'	=> 'f9f9f9',
		'default-image'	=> ''
	));

	$bg_types = array();

	if ( function_exists('themeblvd_get_bg_types') ) {
		$bg_types = themeblvd_get_bg_types('section');
	}

	// Theme Options
	$options = apply_filters('jumpstart_ent_options', array(
		'general' => array(
			'sub_group_start_1' => array(
				'id'		=> 'sub_group_start_1',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'layout_style' => array(
				'name' 		=> __( 'Site Layout Style', 'themeblvd' ),
				'desc' 		=> __( 'Select whether you\'d like the layout of the theme to be boxed or not.', 'themeblvd' ),
				'id' 		=> 'layout_style',
				'std' 		=> 'stretch',
				'type' 		=> 'select',
				'options'	=> array(
					'stretch' 	=> __( 'Stretch', 'themeblvd' ),
					'boxed' 	=> __( 'Boxed', 'themeblvd' )
				),
				'class'		=> 'trigger'
			),
			'layout_shadow_size' => array(
				'id'		=> 'layout_shadow_size',
				'name'		=> __('Layout Shadow Size', 'themeblvd'),
				'desc'		=> __('Select the size of the shadow around the boxed layout. Set to <em>0px</em> for no shadow.', 'themeblvd'),
				'std'		=> '5px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '0',
					'max'		=> '20'
				),
				'class'		=> 'receiver receiver-boxed'
			),
			'layout_shadow_opacity' => array(
				'id'		=> 'layout_shadow_opacity',
				'name'		=> __('Layout Shadow Strength', 'themeblvd'),
				'desc'		=> __('Select the opacity of the shadow for the boxed layout. The darker <a href="themes.php?page=custom-background" target="_blank">your background</a>, the closer to 1.0 you want to go.', 'themeblvd'),
				'std'		=> '0.3',
				'type'		=> 'select',
				'options'	=> array(
					'0.1'	=> '0.1',
					'0.2'	=> '0.2',
					'0.3'	=> '0.3',
					'0.4'	=> '0.4',
					'0.5'	=> '0.5',
					'0.6'	=> '0.6',
					'0.7'	=> '0.7',
					'0.8'	=> '0.8',
					'0.9'	=> '0.9',
					'1'		=> '1.0'
				),
				'class'		=> 'receiver  receiver-boxed'
			),
			'layout_border_width' => array(
				'id'		=> 'layout_border_width',
				'name'		=> __('Layout Border Width', 'themeblvd'),
				'desc'		=> __('Select a width in pixels for the boxed layout. Set to <em>0px</em> for no border.', 'themeblvd'),
				'std'		=> '0px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '0',
					'max'		=> '20'
				),
				'class'		=> 'receiver receiver-boxed'
			),
			'layout_border_color' => array(
				'id'		=> 'layout_border_color',
				'name'		=> __('Layout Border Color', 'themeblvd'),
				'desc'		=> __('Select a color for the border around the boxed layout.', 'themeblvd'),
				'std'		=> '#cccccc',
				'type'		=> 'color',
				'class'		=> 'receiver receiver-boxed'
			),
			'sub_group_start_2' => array(
				'id'		=> 'sub_group_start_2',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide receiver receiver-stretch'
			),
			'apply_content_border' => array(
				'id'		=> 'apply_content_border',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Content Border', 'themeblvd').'</strong>: '.__('Apply border around content areas.', 'themeblvd'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'content_border_color' => array(
				'id'		=> 'content_border_color',
				'name'		=> __('Content Border Color', 'themeblvd'),
				'desc'		=> __('Select a color for the border around content areas.', 'themeblvd'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'content_border_width' => array(
				'id'		=> 'content_border_width',
				'name'		=> __('Bottom Border Width', 'themeblvd'),
				'desc'		=> __('Select a width in pixels for the border around content areas.', 'themeblvd'),
				'std'		=> '1px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_2' => array(
				'id'		=> 'sub_group_end_2',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_end_1' => array(
				'id'		=> 'sub_group_end_1',
				'type' 		=> 'subgroup_end'
			),
			'style' =>  array(
				'id'		=> 'style',
				'name' 		=> __( 'Content Style', 'themeblvd' ),
				'desc'		=> __( 'Select the content style of the site.', 'themeblvd' ),
				'std'		=> 'light',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __( 'Light', 'themeblvd' ),
					'dark' 	=> __( 'Dark', 'themeblvd' )
				)
			)
		),
		'header_info' => array(
			'header_info' => array(
				'name' 		=> null,
				'desc' 		=> __( 'Note: With the Entrepeneur theme base, for the header info top bar to show, you must enter text at Theme Options > Layout > Header > Header Text. Without this, other header info elements like social icons and searchform, will be displayed next to the main menu instead.', 'themeblvd' ),
				'id' 		=> 'header_info',
				'type' 		=> 'info'
			),
			'top_bg_color' => array(
				'id'		=> 'top_bg_color',
				'name'		=> __('Top Bar Background Color', 'themeblvd'),
				'desc'		=> __('Select a background color for the bar that runs across the top of the header.', 'themeblvd'),
				'std'		=> '#ffffff',
				'type'		=> 'color'
			),
			'top_bg_color_opacity' => array(
				'id'		=> 'top_bg_color_opacity',
				'name'		=> __('Top Bar Background Color Opacity', 'themeblvd'),
				'desc'		=> __('Select the opacity of the above background color. Selecting "1.0" means that the background color is not transparent, at all.', 'themeblvd'),
				'std'		=> '1',
				'type'		=> 'select',
				'options'	=> array(
					'0.1'	=> '0.1',
					'0.2'	=> '0.2',
					'0.3'	=> '0.3',
					'0.4'	=> '0.4',
					'0.5'	=> '0.5',
					'0.6'	=> '0.6',
					'0.7'	=> '0.7',
					'0.8'	=> '0.8',
					'0.9'	=> '0.9',
					'1'		=> '1.0'
				)
			),
			'top_text_color' => array(
			    'id'		=> 'top_text_color',
			    'name'		=> __('Top Bar Text Color', 'themeblvd'),
			    'desc'		=> __('If you\'re using a dark background color, select to show light text, and vice versa.', 'theme-blvd-layout-builder'),
			    'std'		=> 'dark',
			    'type'		=> 'select',
			    'options'	=> array(
			        'dark'	=> __('Dark Text', 'theme-blvd-layout-builder'),
			        'light'	=> __('Light Text', 'theme-blvd-layout-builder')
			    )
			),
			'sub_group_start_3' => array(
				'id'		=> 'sub_group_start_3',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'top_apply_border_bottom' => array(
				'id'		=> 'top_apply_border_bottom',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Bottom Border', 'themeblvd').'</strong>: '.__('Apply bottom border to the top bar of the header.', 'themeblvd'),
				'std'		=> 1,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'top_border_bottom_color' => array(
				'id'		=> 'top_border_bottom_color',
				'name'		=> __('Bottom Border Color', 'themeblvd'),
				'desc'		=> __('Select a color for the bottom border.', 'themeblvd'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'top_border_bottom_width' => array(
				'id'		=> 'top_border_bottom_width',
				'name'		=> __('Bottom Border Width', 'themeblvd'),
				'desc'		=> __('Select a width in pixels for the bottom border.', 'themeblvd'),
				'std'		=> '1px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_3' => array(
				'id'		=> 'sub_group_end_3',
				'type' 		=> 'subgroup_end'
			),
			'top_mini' => array(
				'id'		=> 'top_mini',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Mini Display', 'themeblvd').'</strong>: '.__('Display top bar a bit smaller and more condensed.', 'themeblvd'),
				'std'		=> 1,
				'type'		=> 'checkbox'
			)
		),
		'header' => array(
			'header_height' => array(
				'id'		=> 'header_height',
				'name'		=> __('Header Height', 'themeblvd'),
				'desc'		=> __('Apply a fixed height to the header. Keep in mind that your header logo image will always be displayed to match the height of your header, minus 20px.', 'themeblvd'),
				'type'		=> 'slide',
				'std'		=> '70px',
				'options'	=> array(
					'units'	=> 'px',
					'min'	=> '50',
					'max'	=> '150',
					'step'	=> '1'
				)
			),
			'sub_group_start_4' => array(
				'id'		=> 'sub_group_start_4',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'header_bg_type' => array(
				'id'		=> 'header_bg_type',
				'name'		=> __('Apply Header Background', 'themeblvd'),
				'desc'		=> __('Select if you\'d like to apply a custom background and how you want to control it.', 'themeblvd'),
				'std'		=> 'color',
				'type'		=> 'select',
				'options'	=> $bg_types,
				'class'		=> 'trigger'
			),
			'header_bg_color' => array(
				'id'		=> 'header_bg_color',
				'name'		=> __('Background Color', 'themeblvd'),
				'desc'		=> __('Select a background color.', 'themeblvd'),
				'std'		=> '#ffffff',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-color receiver-texture receiver-image receiver-slideshow'
			),
			'header_bg_color_opacity' => array(
				'id'		=> 'header_bg_color_opacity',
				'name'		=> __('Background Color Opacity', 'themeblvd'),
				'desc'		=> __('Select the opacity of the background color. Selecting "1.0" means that the background color is not transparent, at all.', 'themeblvd'),
				'std'		=> '1',
				'type'		=> 'select',
				'options'	=> array(
					'0.1'	=> '0.1',
					'0.2'	=> '0.2',
					'0.3'	=> '0.3',
					'0.4'	=> '0.4',
					'0.5'	=> '0.5',
					'0.6'	=> '0.6',
					'0.7'	=> '0.7',
					'0.8'	=> '0.8',
					'0.9'	=> '0.9',
					'1'		=> '1.0'
				),
				'class'		=> 'hide receiver receiver-color receiver-texture'
			),
			'header_bg_texture' => array(
				'id'		=> 'header_bg_texture',
				'name'		=> __('Background Texture', 'themeblvd'),
				'desc'		=> __('Select a background texture.', 'themeblvd'),
				'type'		=> 'select',
				'select'	=> 'textures',
				'class'		=> 'hide receiver receiver-texture'
			),
			'sub_group_start_5' => array(
				'id'		=> 'sub_group_start_5',
				'type'		=> 'subgroup_start',
				'class'		=> 'show-hide hide receiver receiver-texture'
			),
			'header_apply_bg_texture_parallax' => array(
				'id'		=> 'header_apply_bg_texture_parallax',
				'name'		=> null,
				'desc'		=> __('Apply parallax scroll effect to background texture.', 'themeblvd'),
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'header_bg_texture_parallax' => array(
				'id'		=> 'header_bg_texture_parallax',
				'name'		=> __('Parallax Intensity', 'themeblvd'),
				'desc'		=> __('Select the instensity of the scroll effect. 1 is the least intense, and 10 is the most intense.', 'themeblvd'),
				'type'		=> 'slide',
				'std'		=> '5',
				'options'	=> array(
					'min'	=> '1',
					'max'	=> '10',
					'step'	=> '1'
				),
				'class'		=> 'hide receiver'
			),
			'subgroup_end_5' => array(
				'id'		=> 'subgroup_end_5',
				'type'		=> 'subgroup_end'
			),
			'sub_group_start_6' => array(
				'id'		=> 'sub_group_start_6',
				'type'		=> 'subgroup_start',
				'class'		=> 'select-parallax hide receiver receiver-image'
			),
			'header_bg_image' => array(
				'id'		=> 'header_bg_image',
				'name'		=> __('Background Image', 'themeblvd'),
				'desc'		=> __('Select a background image.', 'themeblvd'),
				'type'		=> 'background',
				'color'		=> false,
				'parallax'	=> true
			),
			'header_bg_image_parallax' => array(
				'id'		=> 'header_bg_image_parallax',
				'name'		=> __('Parallax: Intensity', 'themeblvd'),
				'desc'		=> __('Select the instensity of the scroll effect. 1 is the least intense, and 10 is the most intense.', 'themeblvd'),
				'type'		=> 'slide',
				'std'		=> '2',
				'options'	=> array(
					'min'	=> '1',
					'max'	=> '10',
					'step'	=> '1'
				),
				'class'		=> 'hide parallax'
			),
			'sub_group_end_6' => array(
				'id'		=> 'sub_group_end_6',
				'type' 		=> 'subgroup_end'
			),
			'header_bg_video' => array(
				'id'		=> 'header_bg_video',
				'name'		=> __('Background Video', 'theme-blvd-layout-builder'),
				'desc'		=> __('Setup a background video. For best results, make sure to use all three fields. The <em>.webm</em> file will display in Google Chrome, while the <em>.mp4</em> will display in most other modnern browsers. Your fallback image will display on mobile and in browsers that don\'t support HTML5 video.', 'theme-blvd-layout-builder'),
				'type'		=> 'background_video',
				'class'		=> 'hide receiver receiver-video'
			),
			'sub_group_start_7' => array(
				'id'		=> 'sub_group_start_7',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide hide receiver receiver-image receiver-slideshow'
			),
			'header_apply_bg_shade' => array(
				'id'		=> 'header_apply_bg_shade',
				'name'		=> null,
				'desc'		=> __('Shade background with transparent color.', 'themeblvd'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'header_bg_shade_color' => array(
				'id'		=> 'header_bg_shade_color',
				'name'		=> __('Shade Color', 'themeblvd'),
				'desc'		=> __('Select the color you want overlaid on your background.', 'themeblvd'),
				'std'		=> '#000000',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'header_bg_shade_opacity' => array(
				'id'		=> 'header_bg_shade_opacity',
				'name'		=> __('Shade Opacity', 'themeblvd'),
				'desc'		=> __('Select the opacity of the shade color overlaid on your background.', 'themeblvd'),
				'std'		=> '0.5',
				'type'		=> 'select',
				'options'	=> array(
					'0.1'	=> '0.1',
					'0.2'	=> '0.2',
					'0.3'	=> '0.3',
					'0.4'	=> '0.4',
					'0.5'	=> '0.5',
					'0.6'	=> '0.6',
					'0.7'	=> '0.7',
					'0.8'	=> '0.8',
					'0.9'	=> '0.9'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_7' => array(
				'id'		=> 'sub_group_end_7',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_start_8' => array(
				'id'		=> 'sub_group_start_8',
				'type'		=> 'subgroup_start',
				'class'		=> 'section-bg-slideshow hide receiver receiver-slideshow'
			),
			'header_bg_slideshow' => array(
				'id' 		=> 'header_bg_slideshow',
				'name'		=> __('Slideshow Images', 'themeblvd'),
				'desc'		=> null,
				'type'		=> 'slider'
			),
			'header_bg_slideshow_crop' => array(
				'name' 		=> __( 'Slideshow Crop Size', 'themeblvd' ),
				'desc' 		=> __( 'Select the crop size to be used for the background slideshow images. Remember that the background images will be stretched to cover the area.', 'themeblvd' ),
				'id' 		=> 'header_bg_slideshow_crop',
				'std' 		=> 'full',
				'type' 		=> 'select',
				'select'	=> 'crop'
			),
			'sub_group_start_9' => array(
				'id'		=> 'sub_group_start_9',
				'type'		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'header_apply_bg_slideshow_parallax' => array(
				'id'		=> 'header_apply_bg_slideshow_parallax',
				'name'		=> null,
				'desc'		=> __('Apply parallax scroll effect to background slideshow.', 'themeblvd'),
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'header_bg_slideshow_parallax' => array(
				'id'		=> 'header_bg_slideshow_parallax',
				'name'		=> __('Parallax Intensity', 'themeblvd'),
				'desc'		=> __('Select the instensity of the scroll effect. 1 is the least intense, and 10 is the most intense.', 'themeblvd'),
				'type'		=> 'slide',
				'std'		=> '5',
				'options'	=> array(
					'min'	=> '1',
					'max'	=> '10',
					'step'	=> '1'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_9' => array(
				'id'		=> 'sub_group_end_9',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_end_8' => array(
				'id'		=> 'sub_group_end_8',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_end_4' => array(
				'id'		=> 'sub_group_end_4',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_start_10' => array(
				'id'		=> 'sub_group_start_10',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'header_text_color' => array(
			    'id'		=> 'header_text_color',
			    'name'		=> __('Text Color', 'themeblvd'),
			    'desc'		=> __('If you\'re using a dark background color, select to show light text, and vice versa.', 'theme-blvd-layout-builder'),
			    'std'		=> 'dark',
			    'type'		=> 'select',
			    'options'	=> array(
			        'dark'	=> __('Dark Text', 'theme-blvd-layout-builder'),
			        'light'	=> __('Light Text', 'theme-blvd-layout-builder')
			    )
			),
			'header_apply_border_top' => array(
				'id'		=> 'header_apply_border_top',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Top Border', 'themeblvd').'</strong>: '.__('Apply top border to header.', 'themeblvd'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'header_border_top_color' => array(
				'id'		=> 'header_border_top_color',
				'name'		=> __('Top Border Color', 'themeblvd'),
				'desc'		=> __('Select a color for the top border.', 'themeblvd'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'header_border_top_width' => array(
				'id'		=> 'header_border_top_width',
				'name'		=> __('Top Border Width', 'themeblvd'),
				'desc'		=> __('Select a width in pixels for the top border.', 'themeblvd'),
				'std'		=> '5px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_10' => array(
				'id'		=> 'sub_group_end_10',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_start_11' => array(
				'id'		=> 'sub_group_start_11',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'header_apply_border_bottom' => array(
				'id'		=> 'header_apply_border_bottom',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Bottom Border', 'themeblvd').'</strong>: '.__('Apply bottom border to header.', 'themeblvd'),
				'std'		=> 1,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'header_border_bottom_color' => array(
				'id'		=> 'header_border_bottom_color',
				'name'		=> __('Bottom Border Color', 'themeblvd'),
				'desc'		=> __('Select a color for the bottom border.', 'themeblvd'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'header_border_bottom_width' => array(
				'id'		=> 'header_border_bottom_width',
				'name'		=> __('Bottom Border Width', 'themeblvd'),
				'desc'		=> __('Select a width in pixels for the bottom border.', 'themeblvd'),
				'std'		=> '1px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_11' => array(
				'id'		=> 'sub_group_end_11',
				'type' 		=> 'subgroup_end'
			)
		),
		'menu' => array(
			'menu_text_shadow' => array(
				'id'		=> 'menu_text_shadow',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Text Shadow', 'themeblvd').'</strong>: '.__('Apply shadow to the text of the main menu. &mdash; <em>Note: This works better with a darker colored header.</em>', 'themeblvd'),
				'std'		=> 0,
				'type'		=> 'checkbox'
			),
			'sub_group_start_12' => array(
				'id'		=> 'sub_group_start_12',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'menu_apply_font' => array(
				'id'		=> 'menu_apply_font',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Font', 'themeblvd').'</strong>: '.__('Apply custom font to main menu.', 'themeblvd'),
				'std'		=> 1,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'font_menu' => array(
				'id' 		=> 'font_menu',
				'name' 		=> __( 'Main Menu Font', 'themeblvd' ),
				'desc' 		=> __( 'This font applies to the top level items of the main menu.', 'themeblvd' ),
				'std' 		=> array('size' => '13px', 'face' => 'google', 'weight' => '300', 'color' => '#555555', 'google' => 'Raleway:300', 'style' => 'normal'),
				'atts'		=> array('size', 'face', 'style', 'weight', 'color'),
				'type' 		=> 'typography',
				'sizes'		=> array('10', '11', '12', '13', '14', '15', '16', '17', '18'),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_12' => array(
				'id'		=> 'sub_group_end_12',
				'type' 		=> 'subgroup_end',
			),
			'menu_highlight' => array(
				'id'		=> 'menu_highlight',
				'name'		=> __('Highlight Color', 'themeblvd'),
				'desc'		=> __('Select a color to be used as a sublte highlight to the main menu.', 'themeblvd'),
				'std'		=> '#fec427',
				'type'		=> 'color'
			),
			'menu_highlight_trans' => array(
				'id'		=> 'menu_highlight_trans',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Transparent Header', 'themeblvd').':</strong> '.__('Also apply menu highlight color top-level menu items, when using transparent header.', 'themeblvd'),
				'std'		=> '0',
				'type'		=> 'checkbox'
			),
			'menu_sub_bg_color' => array(
				'id'		=> 'menu_sub_bg_color',
				'name'		=> __('Dropdown Background Color', 'themeblvd'),
				'desc'		=> __('Select a background color for the main menu\'s drop down menus.', 'themeblvd'),
				'std'		=> '#ffffff',
				'type'		=> 'color'
			),
			'menu_sub_bg_color_brightness' => array(
				'id' 		=> 'menu_sub_bg_color_brightness',
				'name' 		=> __( 'Dropdown Background Color Brightness', 'themeblvd' ),
				'desc' 		=> __( 'In the previous option, did you go dark or light?', 'themeblvd' ),
				'std' 		=> 'light',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __( 'I chose a light color in the previous option.', 'themeblvd' ),
					'dark' 	=> __( 'I chose a dark color in the previous option.', 'themeblvd' )
				)
			)
		),
		'menu_mobile' => array(
			'menu_mobile_bg_color' => array(
				'id'		=> 'menu_mobile_bg_color',
				'name'		=> __('Mobile Menu Background Color', 'themeblvd'),
				'desc'		=> __('Select a background color for the main menu\'s drop down menus.', 'themeblvd'),
				'std'		=> '#333333',
				'type'		=> 'color'
			),
			'menu_mobile_bg_color_brightness' => array(
				'id' 		=> 'menu_mobile_bg_color_brightness',
				'name' 		=> __( 'Mobile Menu Background Color Brightness', 'themeblvd' ),
				'desc' 		=> __( 'In the previous option, did you go dark or light?', 'themeblvd' ),
				'std' 		=> 'dark',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __( 'I chose a light color in the previous option.', 'themeblvd' ),
					'dark' 	=> __( 'I chose a dark color in the previous option.', 'themeblvd' )
				)
			),
			'menu_mobile_social_media_style' => array(
				'name' 		=> __( 'Social Media Style', 'themeblvd' ),
				'desc'		=> __( 'Select the color you\'d like applied to the social icons in the mobile menu.', 'themeblvd' ),
				'id'		=> 'menu_mobile_social_media_style',
				'std'		=> 'light',
				'type' 		=> 'select',
				'options'	=> array(
					'flat'			=> __( 'Flat Color', 'themeblvd' ),
					'dark' 			=> __( 'Flat Dark', 'themeblvd' ),
					'grey' 			=> __( 'Flat Grey', 'themeblvd' ),
					'light' 		=> __( 'Flat Light', 'themeblvd' ),
					'color'			=> __( 'Color', 'themeblvd' )
				)
			)
		),
		'footer' => array(
			'sub_group_start_13' => array(
				'id'		=> 'sub_group_start_13',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'footer_bg_type' => array(
				'id'		=> 'footer_bg_type',
				'name'		=> __('Footer Background', 'themeblvd'),
				'desc'		=> __('Select if you\'d like to apply a custom background color to the footer.<br><br>Note: To setup a more complex designed footer, go to <em>Layout > Footer</em> and use the "Template Sync" feature.', 'themeblvd'),
				'std'		=> 'color',
				'type'		=> 'select',
				'options'	=> array(
					'none'		=> __('None', 'themeblvd'),
					'color'		=> __('Custom color', 'themeblvd'),
					'texture'	=> __('Custom color + texture', 'themeblvd')
				),
				'class'		=> 'trigger'
			),
			'footer_bg_texture' => array(
				'id'		=> 'footer_bg_texture',
				'name'		=> __('Background Texture', 'theme-blvd-layout-builder'),
				'desc'		=> __('Select a background texture.', 'theme-blvd-layout-builder'),
				'type'		=> 'select',
				'select'	=> 'textures',
				'class'		=> 'hide receiver receiver-texture'
			),
			'footer_bg_color' => array(
				'id'		=> 'footer_bg_color',
				'name'		=> __('Background Color', 'themeblvd'),
				'desc'		=> __('Select a background color for the footer.', 'themeblvd'),
				'std'		=> '#ffffff',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-color receiver-texture'
			),
			'footer_bg_color_brightness' => array(
				'id' 		=> 'footer_bg_color_brightness',
				'name' 		=> __( 'Background Color Brightness', 'themeblvd' ),
				'desc' 		=> __( 'In the previous option, did you go dark or light?', 'themeblvd' ),
				'std' 		=> 'light',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __( 'I chose a light color in the previous option.', 'themeblvd' ),
					'dark' 	=> __( 'I chose a dark color in the previous option.', 'themeblvd' )
				),
				'class'		=> 'hide receiver receiver-color receiver-texture'
			),
			'footer_bg_color_opacity' => array(
				'id'		=> 'footer_bg_color_opacity',
				'name'		=> __('Background Color Opacity', 'themeblvd'),
				'desc'		=> __('Select the opacity of the background color you chose.', 'themeblvd'),
				'std'		=> '1',
				'type'		=> 'select',
				'options'	=> array(
					'0.1'	=> '0.1',
					'0.2'	=> '0.2',
					'0.3'	=> '0.3',
					'0.4'	=> '0.4',
					'0.5'	=> '0.5',
					'0.6'	=> '0.6',
					'0.7'	=> '0.7',
					'0.8'	=> '0.8',
					'0.9'	=> '0.9',
					'1'		=> '1.0'
				),
				'class'		=> 'hide receiver receiver-color receiver-texture'
			),
			'sub_group_end_13' => array(
				'id'		=> 'sub_group_end_13',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_start_14' => array(
				'id'		=> 'sub_group_start_14',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'footer_apply_border_top' => array(
				'id'		=> 'footer_apply_border_top',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Top Border', 'themeblvd').'</strong>: '.__('Apply top border to footer.', 'themeblvd'),
				'std'		=> 1,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'footer_border_top_color' => array(
				'id'		=> 'footer_border_top_color',
				'name'		=> __('Top Border Color', 'themeblvd'),
				'desc'		=> __('Select a color for the top border.', 'themeblvd'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'footer_border_top_width' => array(
				'id'		=> 'footer_border_top_width',
				'name'		=> __('Top Border Width', 'themeblvd'),
				'desc'		=> __('Select a width in pixels for the top border.', 'themeblvd'),
				'std'		=> '1px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_14' => array(
				'id'		=> 'sub_group_end_14',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_start_15' => array(
				'id'		=> 'sub_group_start_15',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'footer_apply_border_bottom' => array(
				'id'		=> 'footer_apply_border_bottom',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Bottom Border', 'themeblvd').'</strong>: '.__('Apply bottom border to footer.', 'themeblvd'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'footer_border_bottom_color' => array(
				'id'		=> 'footer_border_bottom_color',
				'name'		=> __('Bottom Border Color', 'themeblvd'),
				'desc'		=> __('Select a color for the bottom border.', 'themeblvd'),
				'std'		=> '#181818',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'footer_border_bottom_width' => array(
				'id'		=> 'footer_border_bottom_width',
				'name'		=> __('Bottom Border Width', 'themeblvd'),
				'desc'		=> __('Select a width in pixels for the bottom border.', 'themeblvd'),
				'std'		=> '1px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_15' => array(
				'id'		=> 'sub_group_end_15',
				'type' 		=> 'subgroup_end'
			)
		),
		'typo' => array(
			'font_body' => array(
				'id' 		=> 'font_body',
				'name' 		=> __( 'Primary Font', 'themeblvd' ),
				'desc' 		=> __( 'This applies to most of the text on your site.', 'themeblvd' ),
				'std' 		=> array('size' => '15px', 'face' => 'google', 'weight' => '300', 'color' => '', 'google' => 'Raleway:300', 'style' => 'normal'),
				'atts'		=> array('size', 'face', 'style', 'weight'),
				'type' 		=> 'typography'
			),
			'font_header' => array(
				'id' 		=> 'font_header',
				'name' 		=> __( 'Header Font', 'themeblvd' ),
				'desc' 		=> __( 'This applies to all of the primary headers throughout your site (h1, h2, h3, h4, h5, h6). This would include header tags used in redundant areas like widgets and the content of posts and pages.', 'themeblvd' ),
				'std' 		=> array('size' => '', 'face' => 'google', 'weight' => '400', 'color' => '', 'google' => 'Raleway:400', 'style' => 'normal'),
				'atts'		=> array('face', 'style', 'weight'),
				'type' 		=> 'typography'
			),
			'link_color' => array(
				'id' 		=> 'link_color',
				'name' 		=> __( 'Link Color', 'themeblvd' ),
				'desc' 		=> __( 'Choose the color you\'d like applied to links.', 'themeblvd' ),
				'std' 		=> '#f9bc18',
				'type' 		=> 'color'
			),
			'link_hover_color' => array(
				'id' 		=> 'link_hover_color',
				'name' 		=> __( 'Link Hover Color', 'themeblvd' ),
				'desc' 		=> __( 'Choose the color you\'d like applied to links when they are hovered over.', 'themeblvd' ),
				'std' 		=> '#f9d718',
				'type' 		=> 'color'
			),
			'footer_link_color' => array(
				'id' 		=> 'footer_link_color',
				'name' 		=> __( 'Footer Link Color', 'themeblvd' ),
				'desc' 		=> __( 'Choose the color you\'d like applied to links in the footer.', 'themeblvd' ),
				'std' 		=> '#f9bc18',
				'type' 		=> 'color'
			),
			'footer_link_hover_color' => array(
				'id' 		=> 'footer_link_hover_color',
				'name' 		=> __( 'Footer Link Hover Color', 'themeblvd' ),
				'desc' 		=> __( 'Choose the color you\'d like applied to links in the footer when they are hovered over.', 'themeblvd' ),
				'std' 		=> '#f9d718',
				'type' 		=> 'color'
			)
		),
		'buttons' => array(
			'btn_default' => array(
				'id' 		=> 'btn_default',
				'name'		=> __( 'Default Buttons', 'themeblvd' ),
				'desc'		=> __( 'Configure what a default button looks like.', 'themeblvd' ),
				'std'		=> array(
					'bg' 				=> '#f8f8f8',
					'bg_hover'			=> '#f5f5f5',
					'border' 			=> '#f2f2f2',
					'text'				=> '#666666',
					'text_hover'		=> '#666666',
					'include_bg'		=> 1,
					'include_border'	=> 1
				),
				'type'		=> 'button'
			),
			'btn_primary' => array(
				'id' 		=> 'btn_primary',
				'name'		=> __( 'Primary Buttons', 'themeblvd' ),
				'desc'		=> __( 'Configure what a primary button looks like.', 'themeblvd' ),
				'std'		=> array(
					'bg' 				=> '#333333',
					'bg_hover'			=> '#222222',
					'border' 			=> '#000000',
					'text'				=> '#ffffff',
					'text_hover'		=> '#ffffff',
					'include_bg'		=> 1,
					'include_border'	=> 1
				),
				'type'		=> 'button'
			),
			'btn_border' => array(
				'id'		=> 'btn_border',
				'name'		=> __('General Button Border Width', 'themeblvd'),
				'desc'		=> __('Select a width in pixels for border of buttons.', 'themeblvd'),
				'std'		=> '2px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '0',
					'max'		=> '5'
				)
			),
			'btn_corners' => array(
				'id'		=> 'btn_corners',
				'name'		=> __('General Button Corners', 'themeblvd'),
				'desc'		=> __('Select the border radius of button corners.', 'themeblvd'),
				'std'		=> '0px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '0',
					'max'		=> '50'
				)
			)
		),
		'widgets' => array(
			'sub_group_start_16' => array(
				'id'		=> 'sub_group_start_16',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'widget_style' =>  array(
				'id'		=> 'widget_style',
				'name' 		=> __('Widget Style', 'themeblvd'),
				'desc'		=> __('Select how you want to style your widgets.', 'themeblvd' ).' <a href="http://getbootstrap.com/components/#panels" target="_blank">'.__('What\'s a Bootstrap panel?', 'themeblvd').'</a>',
				'std'		=> 'standard',
				'type' 		=> 'select',
				'options'	=> array(
					'standard'	=> __('Standard', 'themeblvd'),
					'panel'		=> __('Bootstrap Panel', 'themeblvd')
				),
				'class'		=> 'trigger'
			),
			'sub_group_start_17' => array(
				'id'		=> 'sub_group_start_17',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle hide receiver receiver-panel'
			),
			'widget_panel_style' => array(
				'name' 		=> __( 'Panel Style', 'themeblvd' ),
				'desc' 		=> __( 'Select a style for the Bootstrap panel. You can use a preset style, or setup custom colors.', 'themeblvd' ),
				'id' 		=> 'widget_panel_style',
				'std' 		=> 'default',
				'type' 		=> 'select',
				'options'	=> array(
					'custom'	=> __( 'Custom Style', 'themeblvd' ),
					'default'	=> __( 'Bootstrap: Default', 'themeblvd' ),
					'primary'	=> __( 'Bootstrap: Primary', 'themeblvd' ),
					'info'		=> __( 'Bootstrap: Info (blue)', 'themeblvd' ),
					'warning'	=> __( 'Bootstrap: Warning (yellow)', 'themeblvd' ),
					'danger'	=> __( 'Bootstrap: Danger (red)', 'themeblvd' )

				),
				'class'		=> 'trigger'
			),
			'widget_panel_title_bg_color' => array(
				'id'		=> 'widget_panel_title_bg_color',
				'name'		=> __('Panel Title Background', 'themeblvd'),
				'desc'		=> __('Select two colors to create a background gradient for widget titles. For a solid color, simply select the same color twice.', 'themeblvd'),
				'std'		=> array('start' => '#f5f5f5', 'end' => '#e8e8e8'),
				'type'		=> 'gradient',
				'class'		=> 'hide receiver receiver-custom'
			),
			'widget_panel_border_color' => array(
				'id'		=> 'widget_panel_border_color',
				'name'		=> __('Panel Border Color', 'themeblvd'),
				'desc'		=> __('Select a color for the border.', 'themeblvd'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-custom'
			),
			'sub_group_end_17' => array(
				'id'		=> 'sub_group_end_17',
				'type' 		=> 'subgroup_end'
			),
			'widget_bg_color' => array(
				'id'		=> 'widget_bg_color',
				'name'		=> __('Widget Background Color', 'themeblvd'),
				'desc'		=> __('Select a background color for widgets.', 'themeblvd'),
				'std'		=> '#ffffff',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'widget_bg_brightness' => array(
				'name' 		=> __( 'Widget Background Color Brightness', 'themeblvd' ),
				'desc' 		=> __( 'In the previous option, did you go dark or light?', 'themeblvd' ),
				'id' 		=> 'widget_bg_brightness',
				'std' 		=> 'light',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __( 'I chose a light color in the previous option.', 'themeblvd' ),
					'dark' 	=> __( 'I chose a dark color in the previous option.', 'themeblvd' )
				),
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'widget_bg_color_opacity' => array(
				'id'		=> 'widget_bg_color_opacity',
				'name'		=> __('Widget Background Color Opacity', 'themeblvd'),
				'desc'		=> __('Select the opacity of the background color you chose.', 'themeblvd'),
				'std'		=> '1',
				'type'		=> 'select',
				'options'	=> array(
					'0.1'	=> '0.1',
					'0.2'	=> '0.2',
					'0.3'	=> '0.3',
					'0.4'	=> '0.4',
					'0.5'	=> '0.5',
					'0.6'	=> '0.6',
					'0.7'	=> '0.7',
					'0.8'	=> '0.8',
					'0.9'	=> '0.9',
					'1'		=> '1.0'
				),
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'widget_title_color' => array(
				'id'		=> 'widget_title_color',
				'name'		=> __('Widget Title Text Color', 'themeblvd'),
				'desc'		=> __('Select the text color for titles of widgets.', 'themeblvd'),
				'std'		=> '#333333',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'widget_title_size' => array(
				'id'		=> 'widget_title_size',
				'name'		=> __('Widget Title Text Size', 'themeblvd'),
				'desc'		=> __('Select the text size for titles of widgets.', 'themeblvd'),
				'std'		=> '18px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '10',
					'max'		=> '30'
				),
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'widget_title_shadow' => array(
				'id'		=> 'widget_title_shadow',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Widget Title Text Shadow', 'themeblvd').'</strong>: '.__('Apply shadow to widget title text.', 'themeblvd'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'sub_group_start_18' => array(
				'id'		=> 'sub_group_start_18',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide hide receiver receiver-standard'
			),
			'widget_apply_border' => array(
				'id'		=> 'widget_apply_border',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Widget Border', 'themeblvd').'</strong>: '.__('Apply border around widgets.', 'themeblvd'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'widget_border_color' => array(
				'id'		=> 'widget_border_color',
				'name'		=> __('Border Color', 'themeblvd'),
				'desc'		=> __('Select a color for the border.', 'themeblvd'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'widget_border_width' => array(
				'id'		=> 'widget_border_width',
				'name'		=> __('Border Width', 'themeblvd'),
				'desc'		=> __('Select a width in pixels for the border.', 'themeblvd'),
				'std'		=> '1px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_18' => array(
				'id'		=> 'sub_group_end_18',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_end_16' => array(
				'id'		=> 'sub_group_end_16',
				'type' 		=> 'subgroup_end'
			)
		),
		'extras' => array(
			'highlight' => array(
				'id'		=> 'highlight',
				'name' 		=> __('Highlight Color', 'themeblvd'),
				'desc'		=> __('Select a Highlight color to be used in a few little areas throughout your site.', 'themeblvd'),
				'std'		=> '#fec527',
				'type' 		=> 'color'
			),
			'box_titles' => array(
				'id'		=> 'box_titles',
				'name' 		=> null,
				'desc'		=> __('Display special styling to titles of info boxes and standard widgets.', 'themeblvd'),
				'std'		=> '1',
				'type' 		=> 'checkbox'
			),
			'thumbnail_circles' => array(
				'id'		=> 'thumbnail_circles',
				'name' 		=> null,
				'desc'		=> __('Display avatars and small featured images as circles', 'themeblvd'),
				'std'		=> '1',
				'type' 		=> 'checkbox'
			)
		),
		'css' => array(
			'custom_styles' => array(
				'id'		=> 'custom_styles',
				'name' 		=> null,
				'desc'		=> null,
				'std'		=> '',
				'type' 		=> 'code',
				'lang'		=> 'css'
			)
		)
	));

	themeblvd_add_option_tab( 'styles', __('Styles', 'themeblvd'), true );

	themeblvd_add_option_section( 'styles', 'ent_general',		__('General', 'themeblvd'),			null, $options['general'] );
	themeblvd_add_option_section( 'styles', 'ent_header_info',	__('Header Info', 'themeblvd'), 	null, $options['header_info'] );
	themeblvd_add_option_section( 'styles', 'ent_header',		__('Header', 'themeblvd'),			null, $options['header'] );
	themeblvd_add_option_section( 'styles', 'ent_menu',			__('Main Menu', 'themeblvd'),		null, $options['menu'] );
	themeblvd_add_option_section( 'styles', 'ent_menu_mobile',	__('Mobile Menu', 'themeblvd'),		null, $options['menu_mobile'] );
	themeblvd_add_option_section( 'styles', 'ent_footer', 		__('Footer', 'themeblvd'),			null, $options['footer'] );
	themeblvd_add_option_section( 'styles', 'ent_typo',			__('Typography', 'themeblvd'), 		null, $options['typo'] );
	themeblvd_add_option_section( 'styles', 'ent_buttons',		__('Buttons', 'themeblvd'),			null, $options['buttons'] );
	themeblvd_add_option_section( 'styles', 'ent_widgets',		__('Sidebar Widgets', 'themeblvd'),	null, $options['widgets'] );
	themeblvd_add_option_section( 'styles', 'ent_extras',		__('Extras', 'themeblvd'), 			null, $options['extras'] );
	themeblvd_add_option_section( 'styles', 'ent_css',			__('Custom CSS', 'themeblvd'), 		null, $options['css'] );

	themeblvd_edit_option( 'layout', 'header', 'logo', 'std', array( 'type' => 'image', 'image' => get_template_directory_uri().'/assets/images/logo-small.png', 'image_width' => '165', 'image_height' => '50', 'image_2x' => get_template_directory_uri().'/assets/images/logo-small_2x.png' ) );
	themeblvd_edit_option( 'layout', 'header_trans', 'trans_logo', 'std', array( 'type' => 'image', 'image' => get_template_directory_uri().'/assets/images/logo-small-trans.png', 'image_width' => '165', 'image_height' => '50', 'image_2x' => get_template_directory_uri().'/assets/images/logo-small-trans_2x.png' ) );

}
add_action('after_setup_theme', 'jumpstart_ent_options');

/**
 * Filter global config
 *
 * @since 2.0.0
 */
function jumpstart_ent_global_config( $setup ) {

	if ( themeblvd_get_option('style') == 'dark' ) {
		$setup['display']['dark'] = true;
	}

	return $setup;
}
add_filter('themeblvd_global_config', 'jumpstart_ent_global_config');

/**
 * Change the color of social icons on
 * mobile side menu
 *
 * @since 2.0.0
 */
function jumpstart_ent_mobile_side_menu_social_media_color( $color ) {
	return themeblvd_get_option('menu_mobile_social_media_style');
}
add_filter('themeblvd_mobile_side_menu_social_media_color', 'jumpstart_ent_mobile_side_menu_social_media_color');

/**
 * Body class
 *
 * @since 2.0.0
 */
function jumpstart_ent_body_class($class) {

	// Boxed layout
	if ( themeblvd_get_option('layout_style') == 'boxed' ) {
		$class[] = 'js-boxed';
	}

	return $class;

}
add_filter('body_class', 'jumpstart_ent_body_class');

/**
 * Height of the header. Used with "suck up" feature.
 *
 * @since 2.0.0
 */
function jumpstart_ent_top_height() {

	// User-configured header height
	$height = intval(themeblvd_get_option('header_height'));

	// Add 1px for trans header design's bottom border
	$height += 1;

	// Top bar height
	if ( themeblvd_get_option('header_text') ) {

		if ( themeblvd_get_option('top_mini') ) {
			$height += 36; // mini
		} else {
			$height += 48; // standard
		}

		// Add 1px for trans header top bar design's border
		$height += 1; // standard 48px + 1px bottom border
	}

	return $height;
}
add_filter('themeblvd_top_height', 'jumpstart_ent_top_height');

/**
 * Include Google fonts, if needed
 *
 * @since 2.0.0
 */
function jumpstart_ent_include_fonts() {
	themeblvd_include_google_fonts(
		themeblvd_get_option('font_body'),
		themeblvd_get_option('font_header'),
		themeblvd_get_option('font_menu')
	);
}
add_action( 'wp_head', 'jumpstart_ent_include_fonts', 5 );

/**
 * Enqueue any CSS
 *
 * @since 2.0.0
 */
function jumpstart_ent_css() {

	$print = '';

	$header_bg_type = themeblvd_get_option('header_bg_type');
	$header_bg_color = themeblvd_get_option('header_bg_color');

	// Typography and links
	$font = themeblvd_get_option('font_body');

	if ( $font ) {
		$print .= "html,\n";
		$print .= "body {\n";
		$print .= sprintf("\tfont-family: %s;\n", themeblvd_get_font_face($font) );
		$print .= sprintf("\tfont-size: %s;\n", themeblvd_get_font_size($font) );
		$print .= sprintf("\tfont-style: %s;\n", themeblvd_get_font_style($font) );
		$print .= sprintf("\tfont-weight: %s;\n", themeblvd_get_font_weight($font) );
		$print .= sprintf("\ttext-transform: %s;\n", themeblvd_get_text_transform($font) );
		$print .= "}\n";
	}

	$font = themeblvd_get_option('font_header');

	if ( $font ) {

		if ( themeblvd_installed('gravityforms') && themeblvd_supports('plugins', 'gravityforms') ) {
			$print .= ".tb-gforms-compat .gform_wrapper .gsection .gfield_label,\n";
			$print .= ".tb-gforms-compat .gform_wrapper h2.gsection_title,\n";
			$print .= ".tb-gforms-compat .gform_wrapper h3.gform_title,\n";
		}

		$print .= ".sf-menu .mega-section-header,\n";
		$print .= "h1, h2, h3, h4, h5, h6 {\n";
		$print .= sprintf("\tfont-family: %s;\n", themeblvd_get_font_face($font) );
		$print .= sprintf("\tfont-style: %s;\n", themeblvd_get_font_style($font) );
		$print .= sprintf("\tfont-weight: %s;\n", themeblvd_get_font_weight($font) );
		$print .= sprintf("\ttext-transform: %s;\n", themeblvd_get_text_transform($font) );
		$print .= "}\n";
	}

	$print .= "a {\n";
	$print .= sprintf("\tcolor: %s;\n", themeblvd_get_option('link_color'));
	$print .= "}\n";

	$print .= "a:hover {\n";
	$print .= sprintf("\tcolor: %s;\n", themeblvd_get_option('link_hover_color'));
	$print .= "}\n";

	$print .= ".site-footer a {\n";
	$print .= sprintf("\tcolor: %s;\n", themeblvd_get_option('footer_link_color'));
	$print .= "}\n";

	$print .= ".site-footer a:hover {\n";
	$print .= sprintf("\tcolor: %s;\n", themeblvd_get_option('footer_link_hover_color'));
	$print .= "}\n";

	// Buttons
	$print .= ".btn,\n";
	$print .= "input[type=\"submit\"],\n";
	$print .= "input[type=\"reset\"],\n";
	$print .= "input[type=\"button\"],\n";
	$print .= ".button,\n";
	$print .= "button {\n";
	$print .= sprintf("\tborder-width: %s;\n", themeblvd_get_option('btn_border'));
	$print .= sprintf("\t-webkit-border-radius: %s;\n", themeblvd_get_option('btn_corners'));
	$print .= sprintf("\tborder-radius: %s;\n", themeblvd_get_option('btn_corners'));
	$print .= "}\n";

	$btn = themeblvd_get_option('btn_default');

	if ( $btn ) {

		$print .= ".btn-default,\n";
		$print .= "input[type=\"submit\"],\n";
		$print .= "input[type=\"reset\"],\n";
		$print .= "input[type=\"button\"],\n";
		$print .= ".button,\n";
		$print .= "button {\n";

		if ( $btn['include_bg'] ) {
			$print .= sprintf("\tbackground-color: %s;\n", $btn['bg']);
		} else {
			$print .= "\tbackground-color: transparent;\n";
		}

		if ( $btn['include_border'] ) {
			$print .= sprintf("\tborder-color: %s;\n", $btn['border']);
		} else {
			$print .= "\tborder: none;\n";
		}

		$print .= "\t-webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.2);\n";
		$print .= "\tbox-shadow: inset 0 1px 0 rgba(255,255,255,.2);\n";

		$print .= sprintf("\tcolor: %s;\n", $btn['text']);

		$print .= "}\n";

		$print .= ".btn-default:hover,\n";
		$print .= ".btn-default:focus,\n";
		$print .= ".btn-default:active,\n";
		$print .= ".btn-default.active,\n";
		$print .= "input[type=\"submit\"]:hover,\n";
		$print .= "input[type=\"submit\"]:focus,\n";
		$print .= "input[type=\"submit\"]:active,\n";
		$print .= "input[type=\"reset\"]:hover,\n";
		$print .= "input[type=\"reset\"]:focus,\n";
		$print .= "input[type=\"reset\"]:active,\n";
		$print .= "input[type=\"button\"]:hover,\n";
		$print .= "input[type=\"button\"]:focus,\n";
		$print .= "input[type=\"button\"]:active,\n";
		$print .= ".button:hover,\n";
		$print .= ".button:focus,\n";
		$print .= ".button:active,\n";
		$print .= "button:hover,\n";
		$print .= "button:focus,\n";
		$print .= "button:active {\n";

		$print .= sprintf("\tbackground-color: %s;\n", $btn['bg_hover']);

		if ( $btn['include_border'] ) {
			$print .= sprintf("\tborder-color: %s;\n", $btn['border']);
		}

		$print .= "\t-webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.1);\n";
		$print .= "\tbox-shadow: inset 0 1px 0 rgba(255,255,255,.1);\n";

		$print .= sprintf("\tcolor: %s;\n", $btn['text_hover']);
		$print .= "}\n";

	}

	$btn = themeblvd_get_option('btn_primary');

	if ( $btn ) {

		$print .= ".primary,\n";
		$print .= ".bg-primary,\n";
		$print .= ".btn-primary,\n";
		$print .= "a.alt,\n";
		$print .= "button.alt,\n";
		$print .= "input.alt,\n";
		$print .= ".label-primary,\n";
		$print .= ".panel-primary > .panel-heading {\n";

		if ( $btn['include_bg'] ) {
			$print .= sprintf("\tbackground-color: %s;\n", $btn['bg']);
		} else {
			$print .= "\tbackground-color: transparent;\n";
		}

		if ( $btn['include_border'] ) {
			$print .= sprintf("\tborder-color: %s;\n", $btn['border']);
		} else {
			$print .= "\tborder: none;\n";
		}

		$print .= sprintf("\tcolor: %s;\n", $btn['text']);

		$print .= "}\n";

		$print .= ".panel-primary > .panel-heading > .panel-title {\n";
		$print .= sprintf("\tcolor: %s;\n", $btn['text']);
		$print .= "}\n";

		$print .= ".primary:hover,\n";
		$print .= ".primary:focus,\n";
		$print .= "a.bg-primary:hover,\n";
		$print .= ".btn-primary:hover,\n";
		$print .= ".btn-primary:focus,\n";
		$print .= ".btn-primary:active,\n";
		$print .= ".btn-primary.active,\n";
		$print .= "a.alt:hover,\n";
		$print .= "a.alt:focus,\n";
		$print .= "button.alt:hover,\n";
		$print .= "button.alt:focus,\n";
		$print .= "input.alt:hover,\n";
		$print .= "input.alt:focus {\n";

		$print .= sprintf("\tbackground-color: %s;\n", $btn['bg_hover']);

		if ( $btn['include_border'] ) {
			$print .= sprintf("\tborder-color: %s;\n", $btn['border']);
		}

		$print .= sprintf("\tcolor: %s;\n", $btn['text_hover']);

		$print .= "}\n";

		$print .= ".panel-primary {\n";

		if ( $btn['include_border'] ) {
			$print .= sprintf("\tborder-color: %s;\n", $btn['border']);
		} else {
			$print .= "\tborder: none;\n";
		}

		$print .= "}\n";
	}

	// Disable circlular avatars & small thumbs
	if ( ! themeblvd_get_option('thumbnail_circles') ) { // by default, theme styles these as circles with 100% border-radius
		$print .= "#comments .comment-body .avatar,\n";
		$print .= ".tb-author-box .avatar-wrap img,\n";
		$print .= ".tb-mini-post-grid img,\n";
		$print .= ".tb-mini-post-grid .placeholder,\n";
		$print .= ".tb-mini-post-list img,\n";
		$print .= ".tb-mini-post-list .placeholder {\n";
		$print .= "\tborder-radius: 0;\n";
		$print .= "}\n";
	}

	// Highlight Color
	$highlight = themeblvd_get_option('highlight');

	$print .= ".tb-tag-cloud .tagcloud a:hover,\n";
	$print .= ".tb-tags a:hover,\n";
	$print .= ".tb-tags a:focus,\n";
	$print .= ".btn-share:hover,\n";
	$print .= ".btn-share:focus {\n";
	$print .= sprintf("\tbackground-color: %s\n", $highlight);
	$print .= "}\n";

	$print .= ".tb-thumb-link:before,\n";
	$print .= ".post_showcase .showcase-item.has-title .featured-item.showcase .item-title {\n";
	$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_rgb($highlight, '0.8'));
	$print .= "}\n";

	$print .= ".tooltip-inner {\n";
	$print .= sprintf("\tborder-color: %s;\n", $highlight);
	$print .= "}\n";
	$print .= ".tooltip.top .tooltip-arrow {\n";
	$print .= sprintf("\tborder-top-color: %s;\n", $highlight);
	$print .= "}\n";
	$print .= ".tooltip.bottom .tooltip-arrow {\n";
	$print .= sprintf("\tborder-bottom-color: %s;\n", $highlight);
	$print .= "}\n";

	/* Box Titles & Widgets */
	$widget_style = themeblvd_get_option('widget_style');

	if ( themeblvd_get_option('box_titles') ) {

		if ( $widget_style == 'standard' ) {
			$print .= ".fixed-sidebar .widget-title,\n";
		}

		$print .= "#comments-title,\n";
		$print .= ".tb-info-box .info-box-title,\n";
		$print .= ".tb-related-posts .related-posts-title {\n";
		$print .= "\tborder-bottom: 2px solid #f2f2f2;\n";
		$print .= "\tborder-color: rgba(220,220,220,.4);\n";
		$print .= "\tfont-size: 24px;\n";
		$print .= "\tpadding-bottom: .83em;\n";
		$print .= "\tposition: relative;\n";
		$print .= "}\n";

		if ( $widget_style == 'standard' ) {
			$print .= ".fixed-sidebar .widget-title:before,\n";
		}

		$print .= "#comments-title:before,\n";
		$print .= ".info-box-title:before,\n";
		$print .= ".tb-related-posts .related-posts-title:before {\n";
		$print .= "\tcontent: \"\";\n";
		$print .= sprintf("\tbackground: %s; /* highlight */\n", $highlight);
		$print .= "\tposition: absolute;\n";
		$print .= "\tbottom: -2px;\n";

		if ( is_rtl() ) {
			$print .= "\tright: 0;\n";
		} else {
			$print .= "\tleft: 0;\n";
		}

		$print .= "\twidth: 75px;\n";
		$print .= "\theight: 2px;\n";
		$print .= "}\n";

		if ( $widget_style == 'standard' && themeblvd_get_option('widget_bg_brightness') == 'dark' ) {
			$print .= ".fixed-sidebar .widget-title {\n";
			$print .= "\tborder-color: rgba(0,0,0,.9)";
			$print .= "}\n";
		}
	}

	$print .= sprintf(".fixed-sidebar .widget.%s {\n", $widget_style);

	$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_option('widget_bg_color'));
	$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_rgb( themeblvd_get_option('widget_bg_color'), themeblvd_get_option('widget_bg_color_opacity') ) );

	if ( $widget_style == 'standard' && themeblvd_get_option('widget_apply_border') ) {
		$print .= sprintf("\tborder: %s solid %s;\n", themeblvd_get_option('widget_border_width'), themeblvd_get_option('widget_border_color'));
	} else if ( $widget_style == 'panel' && themeblvd_get_option('widget_panel_style') == 'custom' ) {
		$print .= sprintf("\tborder-color: %s;\n", themeblvd_get_option('widget_panel_border_color'));
	}

	$print .= "}\n";

	if ( $widget_style == 'panel' && themeblvd_get_option('widget_panel_style') == 'custom' ) {

		$color = themeblvd_get_option('widget_panel_title_bg_color');

		$print .= sprintf(".fixed-sidebar .widget.%s .panel-heading {\n", $widget_style);

		if ( $color['start'] == $color['end'] ) {
			$print .= sprintf("\tbackground-color: %s;\n", $color['end']);
		} else {
			$print .= sprintf("\tbackground-color: %s;\n", $color['end'] );
			$print .= sprintf("\tbackground-image: -webkit-gradient(linear, left top, left bottom, from(%s), to(%s));\n", $color['start'], $color['end'] );
			$print .= sprintf("\tbackground-image: -webkit-linear-gradient(top, %s, %s);\n", $color['start'], $color['end'] );
			$print .= sprintf("\tbackground-image: -moz-linear-gradient(top, %s, %s);\n", $color['start'], $color['end'] );
			$print .= sprintf("\tbackground-image: -o-linear-gradient(top, %s, %s);\n", $color['start'], $color['end'] );
			$print .= sprintf("\tbackground-image: -ms-linear-gradient(top, %s, %s);\n", $color['start'], $color['end'] );
			$print .= sprintf("\tbackground-image: linear-gradient(top, %s, %s);\n", $color['start'], $color['end'] );
			$print .= sprintf("\tfilter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='%s', EndColorStr='%s');\n", $color['start'], $color['end'] );
		}

		$print .= sprintf("\tborder-color: %s;\n", themeblvd_get_option('widget_panel_border_color'));

		$print .= "}\n";
	}

	$print .= ".fixed-sidebar .widget-title {\n";
	$print .= sprintf("\tcolor: %s;\n", themeblvd_get_option('widget_title_color'));
	$print .= sprintf("\tfont-size: %s;\n", themeblvd_get_option('widget_title_size'));

	if ( $widget_style == 'panel' ) {
		$print .= "\tmargin-bottom: 0;\n";
	}

	if ( themeblvd_get_option('widget_title_shadow') ) {
		$print .= "\ttext-shadow: 1px 1px 1px rgba(0,0,0,.8);\n";
	} else {
		$print .= "\ttext-shadow: none;\n";
	}

	$print .= "}\n";

	if ( themeblvd_get_option('layout_style') == 'boxed' ) {

		// Boxed Layout

		$print .= "@media (min-width: 481px) {\n";
		$print .= "\t.js-boxed #container {\n";
		$print .= sprintf( "\t\tbox-shadow: 0 0 %s %s;\n", themeblvd_get_option('layout_shadow_size'), themeblvd_get_rgb( '#000000', themeblvd_get_option('layout_shadow_opacity') ) );
		$print .= sprintf( "\tborder-right: %s solid %s;\n", themeblvd_get_option('layout_border_width'), themeblvd_get_option('layout_border_color') );
		$print .= sprintf( "\tborder-left: %s solid %s;\n", themeblvd_get_option('layout_border_width'), themeblvd_get_option('layout_border_color') );
		$print .= "\t}\n";
		$print .= "}\n";

		$border = intval(themeblvd_get_option('layout_border_width'));

		if ( $border > 0 ) {

			$print .= ".js-boxed .tb-sticky-menu {\n";

			$width = 1170 - ( 2 * $border );

			$print .= sprintf( "\tmargin-left: -%spx;\n", $width/2);
			$print .= sprintf( "\tmax-width: %spx;\n", $width);

			$print .= "}\n";

			$print .= "@media (max-width: 1199px) {\n";

			$print .= "\t.js-boxed .tb-sticky-menu {\n";

			$width = 960 - ( 2 * $border );

			$print .= sprintf( "\t\tmargin-left: -%spx;\n", $width/2);
			$print .= sprintf( "\t\tmax-width: %spx;\n", $width);

			$print .= "\t}\n";
			$print .= "}\n";

		}

	} else {

		// Stretch Layout

		// Content border
		if ( themeblvd_get_option('apply_content_border') ) {

			$print .= ".bg-content,\n";

			$print .= ".blog-wrap > article,\n";
			$print .= "article.single,\n";
			$print .= "article.page,\n";

			$print .= ".tb-related-posts > .inner,\n";

			$print .= "#comments .comment-body,\n";
			$print .= "#respond #commentform,\n";

			if ( themeblvd_installed('bbpress') ) {
				$print .= ".bbp-topic-form,\n";
				$print .= ".bbp-reply-form,\n";
			}

			$print .= ".tb-info-box,\n";
			$print .= ".search-page,\n";
			$print .= ".post_list.archive-loop .post-wrap,\n";
			$print .= ".post_grid.archive-loop .post-wrap,\n";
			$print .= ".post_showcase.archive-loop .post-wrap,\n";

			$print .= ".list-template-wrap .list-wrap,\n";
			$print .= ".grid-template-wrap .grid-wrap,\n";
			$print .= ".showcase-template-wrap .showcase-wrap,\n";

			$print .= ".element-section > .element.bg-content,\n";
			$print .= ".element-columns .element.bg-content {\n";

			$print .= sprintf("\tborder: %s solid %s;\n", themeblvd_get_option('content_border_width'), themeblvd_get_option('content_border_color'));

			$print .= "}\n";

			if ( themeblvd_installed('woocommerce') ) {
				$print .= ".woocommerce-tabs .panel,\n";
				$print .= ".woocommerce-tabs .tabs > li.active {\n";
				$print .= sprintf("\tborder-color: %s;\n", themeblvd_get_option('content_border_color'));
				$print .= "}\n";
			}

		}

	}

	// Header background
	if ( ! themeblvd_config('suck_up') ) {

		$options = array();

		$options['bg_type'] = $header_bg_type;
		$options['bg_color'] = $header_bg_color;
		$options['bg_color_opacity'] = themeblvd_get_option('header_bg_color_opacity');
		$options['bg_texture'] = themeblvd_get_option('header_bg_texture');
		$options['apply_bg_texture_parallax'] = themeblvd_get_option('header_apply_bg_texture_parallax');
		$options['bg_texture_parallax'] = themeblvd_get_option('header_bg_texture_parallax');
		$options['bg_image'] = themeblvd_get_option('header_bg_image');
		$options['bg_image_parallax'] = themeblvd_get_option('header_bg_image_parallax');
		$options['bg_video'] = themeblvd_get_option('header_bg_video');
		$options['apply_bg_shade'] = themeblvd_get_option('header_apply_bg_shade');
		$options['bg_shade_color'] = themeblvd_get_option('header_bg_shade_color');
		$options['bg_shade_opacity'] = themeblvd_get_option('header_bg_shade_opacity');

		$options['apply_border_top'] = themeblvd_get_option('header_apply_border_top');
		$options['border_top_color'] = themeblvd_get_option('header_border_top_color');
		$options['border_top_width'] = themeblvd_get_option('header_border_top_width');

		$options['apply_border_bottom'] = themeblvd_get_option('header_apply_border_bottom');
		$options['border_bottom_color'] = themeblvd_get_option('header_border_bottom_color');
		$options['border_bottom_width'] = themeblvd_get_option('header_border_bottom_width');

		$styles = themeblvd_get_display_inline_style( $options, 'external' );

		if ( ! empty( $styles['general'] ) ) {

			$print .= ".site-header {\n";

			foreach ( $styles['general'] as $prop => $value ) {
				$prop = str_replace('-2', '', $prop);
				$print .= sprintf("\t%s: %s;\n", $prop, $value);
			}

			$print .= "}\n";

		}

		// Header top bar
		if ( themeblvd_get_option('header_text') ) { // top bar only shows if there's header text

			$options = array();
			$options['bg_color'] = themeblvd_get_option('top_bg_color');
			$options['bg_type'] = $options['bg_color'] ? 'color' : 'none';
			$options['apply_border_bottom'] = themeblvd_get_option('top_apply_border_bottom');
			$options['border_bottom_color'] = themeblvd_get_option('top_border_bottom_color');
			$options['border_bottom_width'] = themeblvd_get_option('top_border_bottom_width');
			$options['bg_color_opacity'] = themeblvd_get_option('top_bg_color_opacity');

			$styles = themeblvd_get_display_inline_style( $options, 'external' );

			if ( ! empty( $styles['general'] ) ) {

				$print .= ".header-top {\n";

				foreach ( $styles['general'] as $prop => $value ) {
					$prop = str_replace('-2', '', $prop);
					$print .= sprintf("\t%s: %s;\n", $prop, $value);
				}

				if ( themeblvd_get_option('top_text_color') == 'light' ) {
					$print .= "\tcolor: #ffffff;\n";
				} else {
					$print .= "\tcolor: #666666;\n";
					$print .= "\tcolor: rgba(26,26,26,.7);\n";
				}

				$print .= "}\n";

				if ( themeblvd_get_option('top_text_color') == 'light' ) {
					$print .= ".header-top-nav > li {\n";
					$print .= "\tborder-color: rgba(0,0,0,0.4);\n";
					$print .= "}\n";
				}

			}
		}

		// Floating search background color, to match header color
		if ( $header_bg_color ) {

			// Floating search background color, to match header color
			$print .= ".tb-floating-search.below {\n";
			$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_rgb($header_bg_color, '0.8'));
			$print .= "}\n";

			if ( themeblvd_get_option('header_text_color') == 'light' ) {
				$print .= ".tb-floating-search .tb-search .search-input,\n";
				$print .= ".tb-floating-search .search-wrap:before {\n";
				$print .= "\tcolor: #ffffff;\n";
				$print .= "\topacity: 1;\n";
				$print .= "}\n";
				$print .= ".tb-floating-search .tb-search .search-input::-moz-placeholder {\n";
				$print .= "\tcolor: rgba(255,255,255,.8);\n";
				$print .= "}\n";
				$print .= ".tb-floating-search .tb-search .search-input:-ms-input-placeholder {\n";
				$print .= "\tcolor: rgba(255,255,255,.8);\n";
				$print .= "}\n";
				$print .= ".tb-floating-search .tb-search .search-input::-webkit-input-placeholder {\n";
				$print .= "\tcolor: rgba(255,255,255,.8);\n";
				$print .= "}\n";
			}

		}

	} else {

		// For transparent header, give header the
		// selected background color for mobile.
		$print .= "@media (max-width: 767px) {\n";
		$print .= "\t.site-header {\n";
		$print .= sprintf("\t\tbackground-color: %s;\n", $header_bg_color);
		$print .= "\t}\n";
		$print .= "}\n";

	}

	// Header Height and Main Menu
	$height = intval(themeblvd_get_option('header_height'));

	$print .= "@media (min-width: 768px) {\n";
	$print .= "\t.header-content {\n";
	$print .= sprintf( "\t\theight: %spx;\n", $height );
	$print .= "\t}\n";
	$print .= "}\n";

	$print .= ".header-content .header-logo img {\n";
	$print .= sprintf( "\theight: %spx;\n", $height-20 );
	$print .= "}\n";

	$top = round( ($height-20) / 2 ); // 20px line-height for font
	$bottom = ($height-20) - $top; // 20px line-height for font

	$print .= ".header-nav .tb-primary-menu > li > .menu-btn {\n";

	$print .= sprintf( "\tpadding-top: %spx;\n", $top );
	$print .= sprintf( "\tpadding-bottom: %spx;\n", $bottom );

	$print .= "}\n";

	$header_text = themeblvd_get_option('header_text_color');
	$menu_font = themeblvd_get_option('font_menu');

	if ( themeblvd_get_option('menu_apply_font') && $menu_font ) {

		$print .= ".header-nav .tb-primary-menu > li > .menu-btn {\n";

		if ( ! themeblvd_config('suck_up') ) {

			$print .= sprintf("\tcolor: %s;\n", $menu_font['color'] );

			if ( $header_text == 'light' ) {
				$print .= sprintf("\tcolor: %s;\n", themeblvd_get_rgb($menu_font['color'], '0.9') );
			}
		}

		$print .= sprintf("\tfont-family: %s;\n", themeblvd_get_font_face($menu_font) );
		$print .= sprintf("\tfont-size: %s;\n", themeblvd_get_font_size($menu_font) );
		$print .= sprintf("\tfont-style: %s;\n", themeblvd_get_font_style($menu_font) );
		$print .= sprintf("\tfont-weight: %s;\n", themeblvd_get_font_weight($menu_font) );
		$print .= sprintf("\ttext-transform: %s;\n", themeblvd_get_text_transform($menu_font) );

		$print .= "}\n";

		if ( ! themeblvd_config('suck_up') ) {

			$print .= ".header-nav .tb-primary-menu > li > .menu-btn:hover {\n";

			if ( $header_text == 'light' ) {
				$print .= sprintf("\tcolor: %s;\n", $menu_font['color'] );
			} else {
				$print .= sprintf("\tcolor: %s;\n", themeblvd_adjust_color($menu_font['color'], 40, 'darken') );
			}

			$print .= "}\n";
		}

	} else if ( $header_text == 'light' ) {

		$print .= ".header-nav .tb-primary-menu > li > .menu-btn {\n";
	    $print .= "\tcolor: #ffffff;\n";
		$print .= "\tcolor: rgba(255,255,255,.9);\n";
	    $print .= "}\n";

		$print .= ".header-nav .tb-primary-menu > li > .menu-btn:hover {\n";
		$print .= "\tcolor: #ffffff;\n";
		$print .= "}\n";

	}

	if ( themeblvd_get_option('menu_text_shadow') ) {
		$print .= ".tb-primary-menu > li > .menu-btn {\n";
		$print .= "\ttext-shadow: 1px 1px 1px rgba(0,0,0,.8);\n";
		$print .= "}\n";
	}

	// Main Menu highlight
	$highlight = themeblvd_get_option('menu_highlight');

	if ( themeblvd_config('suck_up') && themeblvd_get_option('menu_highlight_trans') ) {
		$print .= ".site-header.transparent .header-nav .tb-primary-menu > li > .menu-btn:before {\n";
	} else {
		$print .= ".header-nav .tb-primary-menu > li > .menu-btn:before {\n";
	}

	$print .= sprintf("\tbackground-color: %s;\n", $highlight);
	$print .= "}\n";

	$print .= ".tb-primary-menu > li > ul.non-mega-sub-menu,\n";
	$print .= ".tb-primary-menu .sf-mega {\n";
	$print .= sprintf("\tborder-color: %s;\n", $highlight);
	$print .= "}\n";

	$text = themeblvd_text_color($highlight);

	$print .= ".btn-navbar {\n";
	$print .= sprintf("\tbackground-color: %s;\n", $highlight);
	$print .= sprintf("\tcolor: %s;\n", $text);
	$print .= "}\n";

	$print .= ".btn-navbar:hover {\n";

	if ( $text == '#333333' ) {
		$print .= sprintf("\tbackground-color: %s;\n", themeblvd_adjust_color($highlight, 20, 'darken'));
	} else {
		$print .= sprintf("\tbackground-color: %s;\n", themeblvd_adjust_color($highlight, 20, 'lighten'));
	}

	$print .= "}\n";

	// Main Menu sub menus
	$print .= ".tb-primary-menu ul.non-mega-sub-menu,\n";
	$print .= ".tb-primary-menu .sf-mega {\n";
	$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_option('menu_sub_bg_color'));
	$print .= "}\n";

	if ( themeblvd_get_option('menu_sub_bg_color_brightness') == 'dark' ) {

		$print .= ".tb-primary-menu ul.sub-menu .menu-btn,\n";
		$print .= ".tb-primary-menu .mega-section-header {\n";
		$print .= "\tcolor: #ffffff;\n";
		$print .= "}\n";

		$print .= "\t.tb-primary-menu ul.sub-menu a:hover {";
		$print .= "background-color: rgba(255,255,255,.1)\n";
		$print .= "}\n";

	}

	// Header sticky menu
	if ( $header_bg_type && $header_bg_type != 'none' && $header_bg_color ) {

		$print .= ".tb-sticky-menu {\n";
		$print .= sprintf("\tbackground-color: %s;\n", $header_bg_color);
		$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_rgb($header_bg_color, '0.9'));
		$print .= "}\n";

		$print .= ".tb-sticky-menu .tb-primary-menu > li > .menu-btn,\n";
		$print .= ".tb-sticky-menu .tb-primary-menu > li > .menu-btn:hover {\n";

		if ( themeblvd_get_option('menu_apply_font') && $menu_font ) {

			$print .= sprintf("\tcolor: %s;\n", $menu_font['color'] );
			$print .= sprintf("\tfont-family: %s;\n", themeblvd_get_font_face($menu_font) );
			$print .= sprintf("\ttext-transform: %s;\n", themeblvd_get_text_transform($menu_font) );

		} else {

			if ( $header_text == 'light' ) {
				$print .= "\tcolor: #ffffff;\n";
			} else {
				$print .= "\tcolor: #333333;\n";
			}

		}

		$print .= "}\n";

		$print .= ".tb-sticky-menu .tb-primary-menu > li > .menu-btn:hover {\n";

		if ( $header_text == 'light' ) {
			$print .= "\tbackground-color: #000000;\n";
			$print .= "\tbackground-color: rgba(255,255,255,.1);\n";
		} else {
			$print .= "\tbackground-color: #ffffff;\n";
			$print .= "\tbackground-color: rgba(0,0,0,.1);\n";
		}

		$print .= "}\n";

	}

	// Primary mobile menu
	$print .= ".tb-mobile-menu-wrapper,\n";
	$print .= ".tb-mobile-menu-wrapper .tb-mobile-menu,\n";
	$print .= ".tb-mobile-menu-wrapper .tb-mobile-menu .sub-menu li.non-mega-sub-menu:last-child {\n";
	$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_option('menu_mobile_bg_color'));
	$print .= "}\n";

	if ( themeblvd_get_option('menu_mobile_bg_color_brightness') == 'light' ) {

		$print .= ".tb-mobile-menu-wrapper .tb-mobile-menu a,\n";
		$print .= ".tb-mobile-menu-wrapper .tb-mobile-menu span,\n";
		$print .= ".tb-mobile-menu-wrapper .header-text,\n";
		$print .= ".tb-mobile-menu-wrapper .header-text a,\n";
		$print .= ".tb-mobile-menu-wrapper .tb-search .search-input,";
		$print .= ".tb-search.mini .search-submit {\n";
		$print .= "\tcolor: #666666;\n";
		$print .= "\tcolor: rgba(26,26,26,.7);\n";
		$print .= "}\n";

		$print .= ".tb-mobile-menu-wrapper .tb-search.mini > form,\n";
		$print .= ".tb-mobile-menu-wrapper .tb-mobile-menu > li > a:hover,\n";
		$print .= ".tb-mobile-menu-wrapper .tb-mobile-menu > li > a:active {\n";
		$print .= "\tbackground-color: rgba(0,0,0,.05);\n";
		$print .= "}\n";

		$print .= ".tb-mobile-menu-wrapper .tb-mobile-menu > li,\n";
		$print .= ".tb-mobile-menu-wrapper .tb-search {\n";
		$print .= "\tborder-color: rgba(0,0,0,.05);\n";
		$print .= "}\n";

		$print .= ".tb-mobile-menu-wrapper .tb-search .search-input::-moz-placeholder {\n";
		$print .= "\tcolor: rgba(0,0,0,.3);\n";
		$print .= "}\n";
		$print .= ".tb-mobile-menu-wrapper .tb-search .search-input:-ms-input-placeholder {\n";
		$print .= "\tcolor: rgba(0,0,0,.3);\n";
		$print .= "}\n";
		$print .= ".tb-mobile-menu-wrapper .tb-search .search-input::-webkit-input-placeholder {\n";
		$print .= "\tcolor: rgba(0,0,0,.3);\n";
		$print .= "}\n";

		$print .= ".tb-mobile-menu-wrapper .tb-side-menu .sub-menu {\n";
		$print .= sprintf("\tbackground-image: url(%s/assets/images/parts/side-nav-list-outer-cccccc.png);", TB_FRAMEWORK_URI);
		$print .= "}\n";
		$print .= ".tb-mobile-menu-wrapper .tb-side-menu .sub-menu li {\n";
		$print .= sprintf("\tbackground-image: url(%s/assets/images/parts/side-nav-list-ltr-cccccc.png);", TB_FRAMEWORK_URI);
		$print .= "}\n";

	}

	// Footer
	$options = array();

	$options['bg_type'] = themeblvd_get_option('footer_bg_type');
	$options['bg_texture'] = themeblvd_get_option('footer_bg_texture');
	$options['bg_color'] = themeblvd_get_option('footer_bg_color');
	$options['bg_color_opacity'] = themeblvd_get_option('footer_bg_color_opacity');

	$options['apply_border_top'] = themeblvd_get_option('footer_apply_border_top');
	$options['border_top_color'] = themeblvd_get_option('footer_border_top_color');
	$options['border_top_width'] = themeblvd_get_option('footer_border_top_width');

	$options['apply_border_bottom'] = themeblvd_get_option('footer_apply_border_bottom');
	$options['border_bottom_color'] = themeblvd_get_option('footer_border_bottom_color');
	$options['border_bottom_width'] = themeblvd_get_option('footer_border_bottom_width');

	$styles = themeblvd_get_display_inline_style( $options, 'external' );

	if ( ! empty( $styles['general'] ) ) {

		$print .= ".site-footer {\n";

		foreach ( $styles['general'] as $prop => $value ) {
			$prop = str_replace('-2', '', $prop);
			$print .= sprintf("\t%s: %s;\n", $prop, $value);
		}

		$print .= "}\n";

	}

	// Custom CSS
	if ( $custom = themeblvd_get_option('custom_styles') ) {
		$print .= "\n/* =Custom CSS\n";
		$print .= "-----------------------------------------------*/\n\n";
		$print .= $custom;
	}

	// Final output
	if ( $print ) {
		wp_add_inline_style( 'jumpstart-base', apply_filters('jumpstart_ent_css_output', $print) );
	}

}
add_action( 'wp_enqueue_scripts', 'jumpstart_ent_css', 25 );

/**
 * Only trigger default header info top bar,
 * if there's header text saved.
 *
 * @since 2.0.0
 */
function themeblvd_ent_has_header_info( $return ) {
	if ( themeblvd_get_option('header_text') ) {
		return true;
	} else {
		return false;
	}
}
add_filter('themeblvd_has_header_info', 'themeblvd_ent_has_header_info');

/**
 * Move header menu to the right of the logo
 */
remove_action('themeblvd_header_menu', 'themeblvd_header_menu_default');
add_action('themeblvd_header_addon', 'themeblvd_header_menu_default');

/**
 * Header menu addons
 *
 * @since 2.0.0
 */
function jumpstart_ent_menu_addon( $items, $args ) {

	if ( themeblvd_get_option('header_text') ) {
		return $items; // header top bar is showing
	}

	if ( $args->theme_location != 'primary' ) {
		return $items; // not the main menu
	}

	if ( $icons = themeblvd_get_option('social_media') ) {

		$items .= '<li class="menu-item level-1 menu-contact">';
		$items .= '<a href="#" class="tb-contact-trigger menu-btn" tabindex="0" data-toggle="popover" data-container="body" data-placement="bottom" data-open="envelope-o" data-close="close"><i class="fa fa-envelope-o"></i></a>';

		$color = themeblvd_get_option('social_media_style');

		if ( $color == 'light' ) { // color can't be light cause it's in a white popover
			$color = 'grey';
		}

		$items .= sprintf('<div class="contact-popover-content hide">%s</div>', themeblvd_get_contact_bar($icons, array('tooltip' => false, 'style' => $color, 'class' => 'to-mobile')));
		$items .= '</li>';
	}

	if ( themeblvd_do_cart() ) {
		$items .= sprintf('<li class="menu-item level-1 menu-cart">%s</li>', themeblvd_get_cart_popup_trigger());
	}

	if ( themeblvd_get_option('searchform') == 'show' ) {
		$items .= sprintf('<li class="menu-item level-1 menu-search">%s</li>', themeblvd_get_floating_search_trigger(array('placement' => 'below', 'class' => 'menu-btn')));
	}

	return $items;
}
add_filter('wp_nav_menu_items', 'jumpstart_ent_menu_addon', 10, 2);

/**
 * Add CSS classes and parralax data() to header
 *
 * @since 2.0.0
 */
function jumpstart_ent_header_class( $output, $class ) {

	$options = array(
		'bg_type'						=> themeblvd_get_option('header_bg_type'),
		'apply_bg_shade'				=> themeblvd_get_option('header_apply_bg_shade'),
		'apply_bg_texture_parallax'		=> themeblvd_get_option('header_apply_bg_texture_parallax'),
		'bg_texture_parallax'			=> themeblvd_get_option('header_bg_texture_parallax'),
		'bg_image'						=> themeblvd_get_option('header_bg_image'),
		'bg_image_parallax'				=> themeblvd_get_option('header_bg_image_parallax'),
		'bg_slideshow'					=> themeblvd_get_option('header_bg_slideshow'),
		'bg_video'						=> themeblvd_get_option('header_bg_video')
	);

	$class = array_merge( $class, themeblvd_get_display_class($options) );

	if ( themeblvd_get_option('top_mini', null, '1') ) {
		$class[] = 'header-top-mini';
	}

	return sprintf('class="%s" data-parallax="%s"', implode(' ', $class), themeblvd_get_parallax_intensity($options) );
}
add_filter('themeblvd_header_class_output', 'jumpstart_ent_header_class', 10, 2);

/**
 * Add CSS classes to footer
 *
 * @since 2.0.0
 */
function jumpstart_ent_footer_class( $class ) {

	$bg_type = themeblvd_get_option('footer_bg_type');

	if ( $bg_type == 'color' || $bg_type == 'texture' ) {

		if ( themeblvd_get_option('footer_bg_color_brightness') == 'dark' ) {
			$class[] = 'text-light';
		}

		$class[] = 'has-bg';

	}

	return $class;
}
add_filter('themeblvd_footer_class', 'jumpstart_ent_footer_class');

/**
 * Add any outputted HTML needed for header styling
 * options to work.
 *
 * @since 2.0.0
 */
function jumpstart_ent_header_top() {

	$display = array(
		'bg_type' 						=> themeblvd_get_option('header_bg_type'),
		'apply_bg_shade'				=> themeblvd_get_option('header_apply_bg_shade'),
		'bg_shade_color'				=> themeblvd_get_option('header_bg_shade_color'),
		'bg_shade_opacity'				=> themeblvd_get_option('header_bg_shade_opacity'),
		'bg_slideshow'					=> themeblvd_get_option('header_bg_slideshow'),
		'apply_bg_slideshow_parallax'	=> themeblvd_get_option('header_apply_bg_slideshow_parallax'),
		'bg_slideshow_parallax'			=> themeblvd_get_option('header_bg_slideshow_parallax'),
		'bg_video'						=> themeblvd_get_option('header_bg_video')
	);

	if ( ( $display['bg_type'] == 'image' || $display['bg_type'] == 'slideshow' ) && ! empty($display['apply_bg_shade']) ) {
		printf( '<div class="bg-shade" style="background-color: %s;"></div>', themeblvd_get_rgb( $display['bg_shade_color'], $display['bg_shade_opacity'] ) );
	}

	if ( $display['bg_type'] == 'video' && ! empty($display['bg_video']) ) {
		themeblvd_bg_video( $display['bg_video'] );
	}

	if ( $display['bg_type'] == 'slideshow' && ! empty($display['bg_slideshow']) ) {

		$parallax = 0;

		if ( ! empty($display['apply_bg_slideshow_parallax']) ) {
			$parallax = $display['bg_slideshow_parallax'];
		}

		themeblvd_bg_slideshow( 'header', $display['bg_slideshow'], $parallax );
	}

}
add_action('themeblvd_header_top', 'jumpstart_ent_header_top', 5);

/**
 * If we're showing a header top bar (i.e. there's header text), then
 * we want the floating search to be full-sized over header content
 * below. But if the're no top bar and search icon is in the menu,
 * we need the menu to open below the header.
 *
 * @since 2.0.0
 */
function jumpstart_ent_floating_search_placement( $args ) {

	if ( themeblvd_get_option('header_text') ) {
		$args['placement'] = 'full';
	} else {
		$args['placement'] = 'below';
	}

	return $args;
}
add_filter('themeblvd_floating_search_trigger_defaults', 'jumpstart_ent_floating_search_placement');

/**
 * Filter args that get filtered in when
 * all sidebars are registered.
 *
 * @since 2.5.0
 */
function themeblvd_ent_sidebar_args( $args, $sidebar, $location ) {

	if ( in_array( $location, array('sidebar_left', 'sidebar_right') ) ) {

		$text = 'text-dark';

		if ( themeblvd_get_option('widget_bg_brightness') == 'dark' ) {
			$text = 'text-light';
		}

		if ( themeblvd_get_option('widget_style') == 'panel' ) {

			$class = sprintf('panel panel-%s %s', themeblvd_get_option('widget_panel_style'), $text);

			$args['before_widget'] = '<aside id="%1$s" class="widget '.$class.' %2$s">';
			$args['after_widget'] = '</aside>';
			$args['before_title'] = '<div class="panel-heading"><h3 class="widget-title panel-title">';
			$args['after_title'] = '</h3></div>';

		} else {

			$args['before_widget'] = str_replace('class="widget ', 'class="widget standard '.$text.' ', $args['before_widget']);

		}

	}

	return $args;
}
add_filter('themeblvd_default_sidebar_args', 'themeblvd_ent_sidebar_args', 10, 3);
add_filter('themeblvd_custom_sidebar_args', 'themeblvd_ent_sidebar_args', 10, 3);
