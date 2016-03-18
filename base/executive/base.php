<?php
/**
 * Add theme options to framework.
 *
 * @since 2.0.0
 */
function jumpstart_ex_options() {

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
	$options = apply_filters('jumpstart_ex_options', array(
		'general' => array(
			'sub_group_start_1' => array(
				'id'		=> 'sub_group_start_1',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'layout_style' => array(
				'name' 		=> __('Site Layout Style', 'jumpstart'),
				'desc' 		=> __('Select whether you\'d like the layout of the theme to be boxed or not.', 'jumpstart'),
				'id' 		=> 'layout_style',
				'std' 		=> 'stretch',
				'type' 		=> 'select',
				'options'	=> array(
					'stretch' 	=> __('Stretch', 'jumpstart'),
					'boxed' 	=> __('Boxed', 'jumpstart')
				),
				'class'		=> 'trigger'
			),
			'layout_shadow_size' => array(
				'id'		=> 'layout_shadow_size',
				'name'		=> __('Layout Shadow Size', 'jumpstart'),
				'desc'		=> __('Select the size of the shadow around the boxed layout. Set to 0px for no shadow.', 'jumpstart'),
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
				'name'		=> __('Layout Shadow Strength', 'jumpstart'),
				'desc'		=> sprintf(__('Select the opacity of the shadow for the boxed layout. The darker %s, the closer to 100%% you want to go.', 'jumpstart'), '<a href="'.esc_url(admin_url('customize.php?autofocus[control]=background_image')).'" target="_blank">'.__('your background', 'jumpstart').'</a>'),
				'std'		=> '0.3',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%',
					'1'		=> '100%'
				),
				'class'		=> 'receiver  receiver-boxed'
			),
			'layout_border_width' => array(
				'id'		=> 'layout_border_width',
				'name'		=> __('Layout Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the boxed layout. Set to 0px for no border.', 'jumpstart'),
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
				'name'		=> __('Layout Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the border around the boxed layout.', 'jumpstart'),
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
				'desc'		=> '<strong>'.__('Content Border', 'jumpstart').'</strong>: '.__('Apply border around content areas.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'content_border_color' => array(
				'id'		=> 'content_border_color',
				'name'		=> __('Content Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the border around content areas.', 'jumpstart'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'content_border_width' => array(
				'id'		=> 'content_border_width',
				'name'		=> __('Bottom Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the border around content areas.', 'jumpstart'),
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
				'name' 		=> __('Content Style', 'jumpstart'),
				'desc'		=> __('Select the content style of the site.', 'jumpstart'),
				'std'		=> 'light',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __('Light', 'jumpstart'),
					'dark' 	=> __('Dark', 'jumpstart')
				)
			)
		),
		'header_info' => array(
			'sub_group_start_3' => array(
				'id'		=> 'sub_group_start_3',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'header_info' => array(
				'name' 		=> __('Header Info Display', 'jumpstart'),
				'desc' 		=> sprintf(__('Select where you\'d like the header info to display, configured at %s.', 'jumpstart'), '<em>'.__('Theme Options > Layout > Header', 'jumpstart').'</em>'),
				'id' 		=> 'header_info',
				'std' 		=> 'header_addon',
				'type' 		=> 'select',
				'options'	=> array(
					'header_top'	=> __('Top bar above header', 'jumpstart'),
					'header_addon'	=> __('Within header', 'jumpstart')
				),
				'class'		=> 'trigger'
			),
			'top_bg_color' => array(
				'id'		=> 'top_bg_color',
				'name'		=> __('Top Bar Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color for the bar that runs across the top of the header.', 'jumpstart'),
				'std'		=> '#ffffff',
				'type'		=> 'color',
				'class'		=> 'receiver receiver-header_top'
			),
			'top_bg_color_opacity' => array(
				'id'		=> 'top_bg_color_opacity',
				'name'		=> __('Top Bar Background Color Opacity', 'jumpstart'),
				'desc'		=> __('Select the opacity of the above background color. Selecting "100%" means that the background color is not transparent, at all.', 'jumpstart'),
				'std'		=> '1',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%',
					'1'		=> '100%'
				),
				'class'		=> 'receiver receiver-header_top'
			),
			'top_text_color' => array(
			    'id'		=> 'top_text_color',
			    'name'		=> __('Top Bar Text Color', 'jumpstart'),
			    'desc'		=> __('If you\'re using a dark background color, select to show light text, and vice versa.', 'jumpstart'),
			    'std'		=> 'dark',
			    'type'		=> 'select',
			    'options'	=> array(
			        'dark'	=> __('Dark Text', 'jumpstart'),
			        'light'	=> __('Light Text', 'jumpstart')
			    ),
				'class'		=> 'receiver receiver-header_top'
			),
			'sub_group_start_4' => array(
				'id'		=> 'sub_group_start_4',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide receiver receiver-header_top'
			),
			'top_apply_border_bottom' => array(
				'id'		=> 'top_apply_border_bottom',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Top Bar Bottom Border', 'jumpstart').'</strong>: '.__('Apply bottom border to the top bar of the header.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'top_border_bottom_color' => array(
				'id'		=> 'top_border_bottom_color',
				'name'		=> __('Bottom Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the bottom border.', 'jumpstart'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'top_border_bottom_width' => array(
				'id'		=> 'top_border_bottom_width',
				'name'		=> __('Bottom Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the bottom border.', 'jumpstart'),
				'std'		=> '1px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_4' => array(
				'id'		=> 'sub_group_end_4',
				'type' 		=> 'subgroup_end'
			),
			'top_mini' => array(
				'id'		=> 'top_mini',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Mini Display', 'jumpstart').'</strong>: '.__('Display top bar a bit smaller and more condensed.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'receiver receiver-header_top'
			),
			'sub_group_end_3' => array(
				'id'		=> 'sub_group_end_3',
				'type' 		=> 'subgroup_end'
			)
		),
		'header' => array(
			'sub_group_start_5' => array(
				'id'		=> 'sub_group_start_5',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'header_bg_type' => array(
				'id'		=> 'header_bg_type',
				'name'		=> __('Apply Header Background', 'jumpstart'),
				'desc'		=> __('Select if you\'d like to apply a custom background and how you want to control it.', 'jumpstart'),
				'std'		=> 'color',
				'type'		=> 'select',
				'options'	=> $bg_types,
				'class'		=> 'trigger'
			),
			'header_text_color' => array(
				'id'		=> 'header_text_color',
				'name'		=> __('Text Color', 'jumpstart'),
				'desc'		=> __('If you\'re using a dark background color, select to show light text, and vice versa.', 'jumpstart'),
				'std'		=> 'dark',
				'type'		=> 'select',
				'options'	=> array(
					'dark'	=> __('Dark Text', 'jumpstart'),
					'light'	=> __('Light Text', 'jumpstart')
				)
			),
			'header_bg_color' => array(
				'id'		=> 'header_bg_color',
				'name'		=> __('Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color.', 'jumpstart'),
				'std'		=> '#ffffff',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-color receiver-texture receiver-image'
			),
			'header_bg_color_opacity' => array(
				'id'		=> 'header_bg_color_opacity',
				'name'		=> __('Background Color Opacity', 'jumpstart'),
				'desc'		=> __('Select the opacity of the background color. Selecting "100%" means that the background color is not transparent, at all.', 'jumpstart'),
				'std'		=> '1',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%',
					'1'		=> '100%'
				),
				'class'		=> 'hide receiver receiver-color receiver-texture'
			),
			'header_bg_texture' => array(
				'id'		=> 'header_bg_texture',
				'name'		=> __('Background Texture', 'jumpstart'),
				'desc'		=> __('Select a background texture.', 'jumpstart'),
				'type'		=> 'select',
				'select'	=> 'textures',
				'class'		=> 'hide receiver receiver-texture'
			),
			'header_apply_bg_texture_parallax' => array(
				'id'		=> 'header_apply_bg_texture_parallax',
				'name'		=> null,
				'desc'		=> __('Apply parallax scroll effect to background texture.', 'jumpstart'),
				'type'		=> 'checkbox',
				'class'		=> 'hide receiver receiver-texture'
			),
			'sub_group_start_6' => array(
				'id'		=> 'sub_group_start_6',
				'type'		=> 'subgroup_start',
				'class'		=> 'select-parallax hide receiver receiver-image'
			),
			'header_bg_image' => array(
				'id'		=> 'header_bg_image',
				'name'		=> __('Background Image', 'jumpstart'),
				'desc'		=> __('Select a background image.', 'jumpstart'),
				'type'		=> 'background',
				'color'		=> false,
				'parallax'	=> true
			),
			'sub_group_end_6' => array(
				'id'		=> 'sub_group_end_6',
				'type' 		=> 'subgroup_end'
			),
			'header_bg_video' => array(
				'id'		=> 'header_bg_video',
				'name'		=> __('Background Video', 'jumpstart'),
				'desc'		=> __('You can upload a web-video file (mp4, webm, ogv), or input a URL to a video page on YouTube or Vimeo. Your fallback image will display on mobile devices.', 'jumpstart').'<br><br>'.__('Examples:', 'jumpstart').'<br>https://vimeo.com/79048048<br>http://www.youtube.com/watch?v=5guMumPFBag',
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
				'desc'		=> __('Shade background with transparent color.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'header_bg_shade_color' => array(
				'id'		=> 'header_bg_shade_color',
				'name'		=> __('Shade Color', 'jumpstart'),
				'desc'		=> __('Select the color you want overlaid on your background.', 'jumpstart'),
				'std'		=> '#000000',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'header_bg_shade_opacity' => array(
				'id'		=> 'header_bg_shade_opacity',
				'name'		=> __('Shade Opacity', 'jumpstart'),
				'desc'		=> __('Select the opacity of the shade color overlaid on your background.', 'jumpstart'),
				'std'		=> '0.5',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%'
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
				'name'		=> __('Slideshow Images', 'jumpstart'),
				'desc'		=> null,
				'type'		=> 'slider'
			),
			'header_bg_slideshow_crop' => array(
				'name' 		=> __('Slideshow Crop Size', 'jumpstart'),
				'desc' 		=> __('Select the crop size to be used for the background slideshow images. Remember that the background images will be stretched to cover the area.', 'jumpstart'),
				'id' 		=> 'header_bg_slideshow_crop',
				'std' 		=> 'full',
				'type' 		=> 'select',
				'select'	=> 'crop'
			),
			'header_apply_bg_slideshow_parallax' => array(
				'id'		=> 'header_apply_bg_slideshow_parallax',
				'name'		=> null,
				'desc'		=> __('Apply parallax scroll effect to background slideshow.', 'jumpstart'),
				'type'		=> 'checkbox',
			),
			'sub_group_end_8' => array(
				'id'		=> 'sub_group_end_8',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_end_5' => array(
				'id'		=> 'sub_group_end_5',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_start_9' => array(
				'id'		=> 'sub_group_start_9',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'header_apply_border_top' => array(
				'id'		=> 'header_apply_border_top',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Top Border', 'jumpstart').'</strong>: '.__('Apply top border to header.', 'jumpstart'),
				'std'		=> 1,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'header_border_top_color' => array(
				'id'		=> 'header_border_top_color',
				'name'		=> __('Top Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the top border.', 'jumpstart'),
				'std'		=> '#1e73be',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'header_border_top_width' => array(
				'id'		=> 'header_border_top_width',
				'name'		=> __('Top Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the top border.', 'jumpstart'),
				'std'		=> '7px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_9' => array(
				'id'		=> 'sub_group_end_9',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_start_10' => array(
				'id'		=> 'sub_group_start_10',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'header_apply_border_bottom' => array(
				'id'		=> 'header_apply_border_bottom',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Bottom Border', 'jumpstart').'</strong>: '.__('Apply bottom border to header.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'header_border_bottom_color' => array(
				'id'		=> 'header_border_bottom_color',
				'name'		=> __('Bottom Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the bottom border.', 'jumpstart'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'header_border_bottom_width' => array(
				'id'		=> 'header_border_bottom_width',
				'name'		=> __('Bottom Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the bottom border.', 'jumpstart'),
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
			'header_apply_padding_top' => array(
			    'id'		=> 'header_apply_padding_top',
			    'name'		=> null,
			    'desc'		=> '<strong>'.__('Top Padding', 'jumpstart').':</strong> '.__('Apply custom padding top the top of the header.', 'jumpstart'),
			    'std'		=> 0,
			    'type'		=> 'checkbox',
			    'class'		=> 'trigger'
			),
			'header_padding_top' => array(
			    'id'		=> 'header_padding_top',
			    'name'		=> __('Top Padding', 'jumpstart'),
			    'desc'		=> __('Set the padding on the top of the header.', 'jumpstart'),
			    'std'		=> '20px',
			    'type'		=> 'slide',
			    'options'	=> array(
			        'units'		=> 'px',
			        'min'		=> '0',
			        'max'		=> '600'
			    ),
			    'class'		=> 'hide receiver'
			),
			'sub_group_end_11' => array(
			    'id'		=> 'sub_group_end_11',
			    'type' 		=> 'subgroup_end'
			),
			'sub_group_start_12' => array(
			    'id'		=> 'sub_group_start_12',
			    'type' 		=> 'subgroup_start',
			    'class'		=> 'show-hide'
			),
			'header_apply_padding_bottom' => array(
			    'id'		=> 'header_apply_padding_bottom',
			    'name'		=> null,
			    'desc'		=> '<strong>'.__('Bottom Padding', 'jumpstart').':</strong> '.__('Apply custom padding bottom the bottom of the header.', 'jumpstart'),
			    'std'		=> 0,
			    'type'		=> 'checkbox',
			    'class'		=> 'trigger'
			),
			'header_padding_bottom' => array(
			    'id'		=> 'header_padding_bottom',
			    'name'		=> __('Bottom Padding', 'jumpstart'),
			    'desc'		=> __('Set the padding on the bottom of the header.', 'jumpstart'),
			    'std'		=> '20px',
			    'type'		=> 'slide',
			    'options'	=> array(
			        'units'		=> 'px',
			        'min'		=> '0',
			        'max'		=> '600'
			    ),
			    'class'		=> 'hide receiver'
			),
			'sub_group_end_12' => array(
			    'id'		=> 'sub_group_end_12',
			    'type' 		=> 'subgroup_end'
			),
			'logo_center' => array(
				'id'		=> 'logo_center',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Center Logo', 'jumpstart').'</strong>: '.__('Center align the logo within the header.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox'
			)
		),
		'menu' => array(
			'sub_group_start_13' => array(
				'id'		=> 'sub_group_start_13',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'menu_bg_type' => array(
				'id'		=> 'menu_bg_type',
				'name'		=> __('Main Menu Background', 'jumpstart'),
				'desc'		=> __('Select if you\'d like to apply a custom background and how you want to control it.', 'jumpstart'),
				'std'		=> 'color',
				'type'		=> 'select',
				'options'	=> array(
					'color'				=> __('Custom color', 'jumpstart'),
					'glassy'			=> __('Custom color + glassy overlay', 'jumpstart'),
					'textured'			=> __('Custom color + noisy texture', 'jumpstart'),
					'gradient'			=> __('Custom gradient', 'jumpstart')
				),
				'class'		=> 'trigger'
			),
			'menu_bg_color' => array(
				'id'		=> 'menu_bg_color',
				'name'		=> __('Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color for the main menu.', 'jumpstart'),
				'std'		=> '#333333',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-color receiver-glassy receiver-textured'
			),
			'menu_bg_gradient' => array(
				'id'		=> 'menu_bg_gradient',
				'name'		=> __('Background Gradient', 'jumpstart'),
				'desc'		=> __('Select two colors to create a gradient with for the main menu.', 'jumpstart'),
				'std'		=> array('start' => '#3c3c3c', 'end' => '#2b2b2b'),
				'type'		=> 'gradient',
				'class'		=> 'hide receiver receiver-gradient receiver-gradient_glassy'
			),
			'menu_bg_color_opacity' => array(
				'id'		=> 'menu_bg_color_opacity',
				'name'		=> __('Background Color Opacity', 'jumpstart'),
				'desc'		=> __('Select the opacity of the background color(s). Selecting "100%" means that the background color is not transparent, at all.', 'jumpstart'),
				'std'		=> '1',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%',
					'1'		=> '100%'
				),
				'class'		=> 'hide receiver receiver-color receiver-glassy receiver-textured receiver-gradient'
			),
			'menu_bg_color_brightness' => array(
				'id' 		=> 'menu_bg_color_brightness',
				'name' 		=> __('Background Color Brightness', 'jumpstart'),
				'desc' 		=> __('In the previous option, did you go dark or light?', 'jumpstart'),
				'std' 		=> 'dark',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __('I chose a light color in the previous option.', 'jumpstart'),
					'dark' 	=> __('I chose a dark color in the previous option.', 'jumpstart')
				),
				'class'		=> 'hide receiver receiver-color receiver-glassy receiver-textured receiver-gradient receiver-gradient_glassy'
			),
			'sub_group_end_13' => array(
				'id'		=> 'sub_group_end_13',
				'type' 		=> 'subgroup_end'
			),
			'menu_hover_bg_color' => array(
				'id'		=> 'menu_hover_bg_color',
				'name'		=> __('Button Hover Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color for when buttons of the main are hovered on.', 'jumpstart'),
				'std'		=> '#000000',
				'type'		=> 'color'
			),
			'menu_hover_bg_color_opacity' => array(
				'id'		=> 'menu_hover_bg_color_opacity',
				'name'		=> __('Button Hover Background Color Opacity', 'jumpstart'),
				'desc'		=> __('Select the opacity of the color you selected in the previous option.', 'jumpstart'),
				'std'		=> '0.3',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%',
					'1'		=> '100%'
				)
			),
			'menu_hover_bg_color_brightness' => array(
				'id' 		=> 'menu_hover_bg_color_brightness',
				'name' 		=> __('Button Hover Background Color Brightness', 'jumpstart'),
				'desc' 		=> __('In the previous option, did you go dark or light?', 'jumpstart'),
				'std' 		=> 'dark',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __('I chose a light color in the previous option.', 'jumpstart'),
					'dark' 	=> __('I chose a dark color in the previous option.', 'jumpstart')
				)
			),
			'menu_sub_bg_color' => array(
				'id'		=> 'menu_sub_bg_color',
				'name'		=> __('Dropdown Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color for the main menu\'s drop down menus.', 'jumpstart'),
				'std'		=> '#ffffff',
				'type'		=> 'color'
			),
			'menu_sub_bg_color_brightness' => array(
				'id' 		=> 'menu_sub_bg_color_brightness',
				'name' 		=> __('Dropdown Background Color Brightness', 'jumpstart'),
				'desc' 		=> __('In the previous option, did you go dark or light?', 'jumpstart'),
				'std' 		=> 'light',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __('I chose a light color in the previous option.', 'jumpstart'),
					'dark' 	=> __('I chose a dark color in the previous option.', 'jumpstart')
				)
			),
			'menu_corners' => array(
				'id'		=> 'menu_corners',
				'name'		=> __('Menu Corners', 'jumpstart'),
				'desc'		=> __('Set the border radius of menu corners. Setting to 0px will mean the menu corners are square.', 'jumpstart'),
				'std'		=> '0px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '0',
					'max'		=> '50'
				)
			),
			'sub_group_start_14' => array(
				'id'		=> 'sub_group_start_14',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'menu_apply_border' => array(
				'id'		=> 'menu_apply_border',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Border', 'jumpstart').'</strong>: '.__('Apply border around menu.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'menu_border_color' => array(
				'id'		=> 'menu_border_color',
				'name'		=> __('Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the border.', 'jumpstart'),
				'std'		=> '#181818',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'menu_border_width' => array(
				'id'		=> 'menu_border_width',
				'name'		=> __('Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the border.', 'jumpstart'),
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
			'menu_text_shadow' => array(
				'id'		=> 'menu_text_shadow',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Text Shadow', 'jumpstart').'</strong>: '.__('Apply shadow to the text of the main menu.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox'
			),
			'sub_group_start_15' => array(
			    'id'		=> 'sub_group_start_15',
			    'type' 		=> 'subgroup_start',
			    'class'		=> 'show-hide'
			),
			'menu_divider' => array(
			    'id'		=> 'menu_divider',
			    'name'		=> null,
			    'desc'		=> '<strong>'.__('Dividers', 'jumpstart').'</strong>: '.__('Add dividers between buttons of main menu.', 'jumpstart'),
			    'std'		=> 0,
			    'type'		=> 'checkbox',
			    'class'		=> 'trigger'
			),
			'menu_divider_color' => array(
			    'id'		=> 'menu_divider_color',
			    'name'		=> __('Divider Color', 'jumpstart'),
			    'desc'		=> __('Select a color for the menu dividers.', 'jumpstart'),
			    'std'		=> '#000000',
			    'type'		=> 'color',
			    'class'		=> 'hide receiver'
			),
			'sub_group_end_15' => array(
			    'id'		=> 'sub_group_end_15',
			    'type' 		=> 'subgroup_end'
			),
			'menu_center' => array(
				'id'		=> 'menu_center',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Center', 'jumpstart').'</strong>: '.__('Center the main menu.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox'
			),
			'menu_search' => array(
				'id'		=> 'menu_search',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Search Bar', 'jumpstart').'</strong>: '.__('Add popup with search bar to main menu.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox'
			)
		),
		'menu_mobile' => array(
			'menu_mobile_bg_color' => array(
				'id'		=> 'menu_mobile_bg_color',
				'name'		=> __('Mobile Menu Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color for the main menu\'s drop down menus.', 'jumpstart'),
				'std'		=> '#333333',
				'type'		=> 'color'
			),
			'menu_mobile_bg_color_brightness' => array(
				'id' 		=> 'menu_mobile_bg_color_brightness',
				'name' 		=> __('Mobile Menu Background Color Brightness', 'jumpstart'),
				'desc' 		=> __('In the previous option, did you go dark or light?', 'jumpstart'),
				'std' 		=> 'dark',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __('I chose a light color in the previous option.', 'jumpstart'),
					'dark' 	=> __('I chose a dark color in the previous option.', 'jumpstart')
				)
			),
			'menu_mobile_social_media_style' => array(
				'name' 		=> __('Social Media Style', 'jumpstart'),
				'desc'		=> __('Select the color you\'d like applied to the social icons in the mobile menu.', 'jumpstart'),
				'id'		=> 'menu_mobile_social_media_style',
				'std'		=> 'light',
				'type' 		=> 'select',
				'options'	=> array(
					'flat'			=> __('Flat Color', 'jumpstart'),
					'dark' 			=> __('Flat Dark', 'jumpstart'),
					'grey' 			=> __('Flat Grey', 'jumpstart'),
					'light' 		=> __('Flat Light', 'jumpstart'),
					'color'			=> __('Color', 'jumpstart')
				)
			)
		),
		'footer' => array(
			'sub_group_start_16' => array(
				'id'		=> 'sub_group_start_16',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'footer_bg_type' => array(
				'id'		=> 'footer_bg_type',
				'name'		=> __('Apply Footer Background', 'jumpstart'),
				'desc'		=> __('Select if you\'d like to apply a custom background color to the footer.', 'jumpstart').'<br><br>'.sprintf(__('Note: To setup a more complex designed footer, go to %s and use the "Template Sync" feature.', 'jumpstart'), '<em>'.__('Layout > Footer', 'jumpstart').'</em>'),
				'std'		=> 'none',
				'type'		=> 'select',
				'options'	=> array(
					'none'		=> __('None', 'jumpstart'),
					'color'		=> __('Custom color', 'jumpstart'),
					'texture'	=> __('Custom color + texture', 'jumpstart')
				),
				'class'		=> 'trigger'
			),
			'footer_bg_texture' => array(
				'id'		=> 'footer_bg_texture',
				'name'		=> __('Background Texture', 'jumpstart'),
				'desc'		=> __('Select a background texture.', 'jumpstart'),
				'type'		=> 'select',
				'select'	=> 'textures',
				'class'		=> 'hide receiver receiver-texture'
			),
			'footer_bg_color' => array(
				'id'		=> 'footer_bg_color',
				'name'		=> __('Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color for the footer.', 'jumpstart'),
				'std'		=> '#333333',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-color receiver-texture'
			),
			'footer_bg_color_brightness' => array(
				'id' 		=> 'footer_bg_color_brightness',
				'name' 		=> __('Background Color Brightness', 'jumpstart'),
				'desc' 		=> __('In the previous option, did you go dark or light?', 'jumpstart'),
				'std' 		=> 'dark',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __('I chose a light color in the previous option.', 'jumpstart'),
					'dark' 	=> __('I chose a dark color in the previous option.', 'jumpstart')
				),
				'class'		=> 'hide receiver receiver-color receiver-texture'
			),
			'footer_bg_color_opacity' => array(
				'id'		=> 'footer_bg_color_opacity',
				'name'		=> __('Background Color Opacity', 'jumpstart'),
				'desc'		=> __('Select the opacity of the background color you chose.', 'jumpstart'),
				'std'		=> '1',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%',
					'1'		=> '100%'
				),
				'class'		=> 'hide receiver receiver-color receiver-texture'
			),
			'sub_group_end_16' => array(
				'id'		=> 'sub_group_end_16',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_start_17' => array(
				'id'		=> 'sub_group_start_17',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'footer_apply_border_top' => array(
				'id'		=> 'footer_apply_border_top',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Top Border', 'jumpstart').'</strong>: '.__('Apply top border to footer.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'footer_border_top_color' => array(
				'id'		=> 'footer_border_top_color',
				'name'		=> __('Top Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the top border.', 'jumpstart'),
				'std'		=> '#181818',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'footer_border_top_width' => array(
				'id'		=> 'footer_border_top_width',
				'name'		=> __('Top Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the top border.', 'jumpstart'),
				'std'		=> '1px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_17' => array(
				'id'		=> 'sub_group_end_17',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_start_19' => array(
				'id'		=> 'sub_group_start_19',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'footer_apply_border_bottom' => array(
				'id'		=> 'footer_apply_border_bottom',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Bottom Border', 'jumpstart').'</strong>: '.__('Apply bottom border to footer.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'footer_border_bottom_color' => array(
				'id'		=> 'footer_border_bottom_color',
				'name'		=> __('Bottom Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the bottom border.', 'jumpstart'),
				'std'		=> '#181818',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'footer_border_bottom_width' => array(
				'id'		=> 'footer_border_bottom_width',
				'name'		=> __('Bottom Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the bottom border.', 'jumpstart'),
				'std'		=> '1px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_19' => array(
				'id'		=> 'sub_group_end_19',
				'type' 		=> 'subgroup_end'
			)
		),
		'typo' => array(
			'font_body' => array(
				'id' 		=> 'font_body',
				'name' 		=> __('Primary Font', 'jumpstart'),
				'desc' 		=> __('This applies to most of the text on your site.', 'jumpstart'),
				'std' 		=> array('size' => '16px', 'face' => 'google', 'weight' => '300', 'color' => '', 'google' => 'Raleway:300', 'style' => 'normal'),
				'atts'		=> array('size', 'face', 'style', 'weight'),
				'type' 		=> 'typography'
			),
			'font_header' => array(
				'id' 		=> 'font_header',
				'name' 		=> __('Header Font', 'jumpstart'),
				'desc' 		=> __('This applies to all of the primary headers throughout your site (h1, h2, h3, h4, h5, h6). This would include header tags used in redundant areas like widgets and the content of posts and pages.', 'jumpstart'),
				'std' 		=> array('size' => '', 'face' => 'google', 'weight' => '400', 'color' => '', 'google' => 'Raleway:400', 'style' => 'normal'),
				'atts'		=> array('face', 'style', 'weight'),
				'type' 		=> 'typography'
			),
			// ...
			'font_menu' => array(
				'id' 		=> 'font_menu',
				'name' 		=> __('Main Menu Font', 'jumpstart'),
				'desc' 		=> __('This font applies to the top level items of the main menu.', 'jumpstart'),
				'std' 		=> array('size' => '13px', 'face' => 'google', 'weight' => '300', 'color' => '#ffffff', 'google' => 'Raleway:300', 'style' => 'normal'),
				'atts'		=> array('size', 'face', 'style', 'weight', 'color'),
				'type' 		=> 'typography',
				'sizes'		=> array('10', '11', '12', '13', '14', '15', '16', '17', '18')
			),
			'font_menu_sp' => array(
				'id' 		=> 'font_menu_sp',
				'name'		=> __('Main Menu Letter Spacing', 'jumpstart'),
				'desc'		=> __('Adjust the spacing between letters.', 'jumpstart'),
				'std'		=> '0px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'	=> 'px',
					'min'	=> '0',
					'max'	=> '5',
					'step'	=> '1'
				)
			),
			'link_color' => array(
				'id' 		=> 'link_color',
				'name' 		=> __('Link Color', 'jumpstart'),
				'desc' 		=> __('Choose the color you\'d like applied to links.', 'jumpstart'),
				'std' 		=> '#428bca',
				'type' 		=> 'color'
			),
			'link_hover_color' => array(
				'id' 		=> 'link_hover_color',
				'name' 		=> __('Link Hover Color', 'jumpstart'),
				'desc' 		=> __('Choose the color you\'d like applied to links when they are hovered over.', 'jumpstart'),
				'std' 		=> '#2a6496',
				'type' 		=> 'color'
			),
			'footer_link_color' => array(
				'id' 		=> 'footer_link_color',
				'name' 		=> __('Footer Link Color', 'jumpstart'),
				'desc' 		=> __('Choose the color you\'d like applied to links in the footer.', 'jumpstart'),
				'std' 		=> '#428bca',
				'type' 		=> 'color'
			),
			'footer_link_hover_color' => array(
				'id' 		=> 'footer_link_hover_color',
				'name' 		=> __('Footer Link Hover Color', 'jumpstart'),
				'desc' 		=> __('Choose the color you\'d like applied to links in the footer when they are hovered over.', 'jumpstart'),
				'std' 		=> '#2a6496',
				'type' 		=> 'color'
			)
		),
		'buttons' => array(
			'btn_default' => array(
				'id' 		=> 'btn_default',
				'name'		=> __('Default Buttons', 'jumpstart'),
				'desc'		=> __('Configure what a default button looks like.', 'jumpstart'),
				'std'		=> array(
					'bg' 				=> '#333333',
					'bg_hover'			=> '#222222',
					'border' 			=> '#000000',
					'text'				=> '#ffffff',
					'text_hover'		=> '#ffffff',
					'include_bg'		=> 1,
					'include_border'	=> 0
				),
				'type'		=> 'button'
			),
			'btn_primary' => array(
				'id' 		=> 'btn_primary',
				'name'		=> __('Primary Buttons', 'jumpstart'),
				'desc'		=> __('Configure what a primary button looks like.', 'jumpstart'),
				'std'		=> array(
					'bg' 				=> '#333333',
					'bg_hover'			=> '#222222',
					'border' 			=> '#000000',
					'text'				=> '#ffffff',
					'text_hover'		=> '#ffffff',
					'include_bg'		=> 1,
					'include_border'	=> 0
				),
				'type'		=> 'button'
			),
			'btn_border' => array(
				'id'		=> 'btn_border',
				'name'		=> __('General Button Border Width', 'jumpstart'),
				'desc'		=> __('If your buttons are set to include a border, select a width in pixels for those borders.', 'jumpstart'),
				'std'		=> '0px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '0',
					'max'		=> '5'
				)
			),
			'btn_corners' => array(
				'id'		=> 'btn_corners',
				'name'		=> __('General Button Corners', 'jumpstart'),
				'desc'		=> __('Set the border radius of button corners. Setting to 0px will mean buttons corners are square.', 'jumpstart'),
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
			'sub_group_start_18' => array(
				'id'		=> 'sub_group_start_18',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'widget_style' =>  array(
				'id'		=> 'widget_style',
				'name' 		=> __('Widget Style', 'jumpstart'),
				'desc'		=> __('Select how you want to style your widgets.', 'jumpstart').' <a href="http://getbootstrap.com/components/#panels" target="_blank">'.__('What\'s a Bootstrap panel?', 'jumpstart').'</a>',
				'std'		=> 'standard',
				'type' 		=> 'select',
				'options'	=> array(
					'standard'	=> __('Standard', 'jumpstart'),
					'panel'		=> __('Bootstrap Panel', 'jumpstart')
				),
				'class'		=> 'trigger'
			),
			'sub_group_start_23' => array(
				'id'		=> 'sub_group_start_23',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle hide receiver receiver-panel'
			),
			'widget_panel_style' => array(
				'name' 		=> __('Panel Style', 'jumpstart'),
				'desc' 		=> __('Select a style for the Bootstrap panel. You can use a preset style, or setup custom colors.', 'jumpstart'),
				'id' 		=> 'widget_panel_style',
				'std' 		=> 'default',
				'type' 		=> 'select',
				'options'	=> array(
					'custom'	=> __('Custom Style', 'jumpstart'),
					'default'	=> __('Bootstrap: Default', 'jumpstart'),
					'primary'	=> __('Bootstrap: Primary', 'jumpstart'),
					'info'		=> __('Bootstrap: Info (blue)', 'jumpstart'),
					'warning'	=> __('Bootstrap: Warning (yellow)', 'jumpstart'),
					'danger'	=> __('Bootstrap: Danger (red)', 'jumpstart')

				),
				'class'		=> 'trigger'
			),
			'widget_panel_title_bg_color' => array(
				'id'		=> 'widget_panel_title_bg_color',
				'name'		=> __('Panel Title Background', 'jumpstart'),
				'desc'		=> __('Select two colors to create a background gradient for widget titles. For a solid color, simply select the same color twice.', 'jumpstart'),
				'std'		=> array('start' => '#f5f5f5', 'end' => '#e8e8e8'),
				'type'		=> 'gradient',
				'class'		=> 'hide receiver receiver-custom'
			),
			'widget_panel_border_color' => array(
				'id'		=> 'widget_panel_border_color',
				'name'		=> __('Panel Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the border.', 'jumpstart'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-custom'
			),
			'sub_group_end_23' => array(
				'id'		=> 'sub_group_end_23',
				'type' 		=> 'subgroup_end'
			),
			'widget_bg_color' => array(
				'id'		=> 'widget_bg_color',
				'name'		=> __('Widget Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color for widgets.', 'jumpstart'),
				'std'		=> '#ffffff',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'widget_bg_brightness' => array(
				'name' 		=> __('Widget Background Color Brightness', 'jumpstart'),
				'desc' 		=> __('In the previous option, did you go dark or light?', 'jumpstart'),
				'id' 		=> 'widget_bg_brightness',
				'std' 		=> 'light',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __('I chose a light color in the previous option.', 'jumpstart'),
					'dark' 	=> __('I chose a dark color in the previous option.', 'jumpstart')
				),
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'widget_bg_color_opacity' => array(
				'id'		=> 'widget_bg_color_opacity',
				'name'		=> __('Widget Background Color Opacity', 'jumpstart'),
				'desc'		=> __('Select the opacity of the background color you chose.', 'jumpstart'),
				'std'		=> '1',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%',
					'1'		=> '100%'
				),
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'widget_title_color' => array(
				'id'		=> 'widget_title_color',
				'name'		=> __('Widget Title Text Color', 'jumpstart'),
				'desc'		=> __('Select the text color for titles of widgets.', 'jumpstart'),
				'std'		=> '#333333',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'widget_title_size' => array(
				'id'		=> 'widget_title_size',
				'name'		=> __('Widget Title Text Size', 'jumpstart'),
				'desc'		=> __('Select the text size for titles of widgets.', 'jumpstart'),
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
				'desc'		=> '<strong>'.__('Widget Title Text Shadow', 'jumpstart').'</strong>: '.__('Apply shadow to widget title text.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'sub_group_start_20' => array(
				'id'		=> 'sub_group_start_20',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide hide receiver receiver-standard'
			),
			'widget_apply_border' => array(
				'id'		=> 'widget_apply_border',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Widget Border', 'jumpstart').'</strong>: '.__('Apply border around widgets.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'widget_border_color' => array(
				'id'		=> 'widget_border_color',
				'name'		=> __('Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the border.', 'jumpstart'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'widget_border_width' => array(
				'id'		=> 'widget_border_width',
				'name'		=> __('Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the border.', 'jumpstart'),
				'std'		=> '1px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_20' => array(
				'id'		=> 'sub_group_end_20',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_end_18' => array(
				'id'		=> 'sub_group_end_18',
				'type' 		=> 'subgroup_end'
			)
		),
		'extras' => array(
			'highlight' => array(
				'id'		=> 'highlight',
				'name' 		=> __('Highlight Color', 'jumpstart'),
				'desc'		=> __('Select a Highlight color to be used in a few little areas throughout your site.', 'jumpstart'),
				'std'		=> '#fec527',
				'type' 		=> 'color'
			),
			'box_titles' => array(
				'id'		=> 'box_titles',
				'name' 		=> null,
				'desc'		=> __('Display special styling to titles of info boxes and standard widgets.', 'jumpstart'),
				'std'		=> '1',
				'type' 		=> 'checkbox'
			),
			'thumbnail_circles' => array(
				'id'		=> 'thumbnail_circles',
				'name' 		=> null,
				'desc'		=> __('Display avatars and small featured images as circles', 'jumpstart'),
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

	themeblvd_add_option_tab( 'styles', __('Styles', 'jumpstart'), true );

	themeblvd_add_option_section( 'styles', 'ex_general',		__('General', 'jumpstart'), 		null, $options['general'] );
	themeblvd_add_option_section( 'styles', 'ex_header_info',	__('Header Info', 'jumpstart'), 	null, $options['header_info'] );
	themeblvd_add_option_section( 'styles', 'ex_header',		__('Header', 'jumpstart'),			null, $options['header'] );
	themeblvd_add_option_section( 'styles', 'ex_menu',			__('Main Menu', 'jumpstart'),		null, $options['menu'] );
	themeblvd_add_option_section( 'styles', 'ex_menu_mobile',	__('Mobile Menu', 'jumpstart'),		null, $options['menu_mobile'] );
	themeblvd_add_option_section( 'styles', 'ex_footer',		__('Footer', 'jumpstart'),			null, $options['footer'] );
	themeblvd_add_option_section( 'styles', 'ex_typo',			__('Typography', 'jumpstart'), 		null, $options['typo'] );
	themeblvd_add_option_section( 'styles', 'ex_buttons',		__('Buttons', 'jumpstart'),			null, $options['buttons'] );
	themeblvd_add_option_section( 'styles', 'ex_widgets',		__('Sidebar Widgets', 'jumpstart'),	null, $options['widgets'] );
	themeblvd_add_option_section( 'styles', 'ex_extras',		__('Extras', 'jumpstart'), 			null, $options['extras'] );
	themeblvd_add_option_section( 'styles', 'ex_css',			__('Custom CSS', 'jumpstart'), 		null, $options['css'] );

}
add_action('after_setup_theme', 'jumpstart_ex_options');

/**
 * Filter global config
 *
 * @since 2.0.0
 */
function jumpstart_ex_global_config( $setup ) {

	if ( themeblvd_get_option('style') == 'dark' ) {
		$setup['display']['dark'] = true;
	}

	return $setup;
}
add_filter('themeblvd_global_config', 'jumpstart_ex_global_config');

/**
 * Change the color of social icons on
 * mobile side menu
 *
 * @since 2.0.0
 */
function jumpstart_ex_mobile_side_menu_social_media_color( $color ) {
	return themeblvd_get_option('menu_mobile_social_media_style');
}
add_filter('themeblvd_mobile_side_menu_social_media_color', 'jumpstart_ex_mobile_side_menu_social_media_color');

/**
 * Body class
 *
 * @since 2.0.0
 */
function jumpstart_ex_body_class($class) {

	// Boxed layout
	if ( themeblvd_get_option('layout_style') == 'boxed' ) {
		$class[] = 'js-boxed';
	}

	// Centered header logo
	if ( themeblvd_get_option('logo_center') ) {
		$class[] = 'js-center-logo';
	}

	// Centered main menu
	if ( themeblvd_get_option('menu_center') ) {
		$class[] = 'js-center-menu';
	}

	return $class;

}
add_filter('body_class', 'jumpstart_ex_body_class');

/**
 * Include Google fonts, if needed
 *
 * @since 2.0.0
 */
function jumpstart_ex_include_fonts() {
	themeblvd_include_fonts(
		themeblvd_get_option('font_body'),
		themeblvd_get_option('font_header'),
		themeblvd_get_option('font_menu')
	);
}
add_action('wp_head', 'jumpstart_ex_include_fonts', 5);

/**
 * Enqueue any CSS
 *
 * @since 2.0.0
 */
function jumpstart_ex_css() {

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

	$border = themeblvd_get_option('btn_border');

	if ( $border == '0px' ) {

		$print .= ".btn,\n";
		$print .= "input[type=\"submit\"],\n";
		$print .= "input[type=\"reset\"],\n";
		$print .= "input[type=\"button\"],\n";
		$print .= ".button,\n";
		$print .= "button {\n";
		$print .= sprintf("\tborder-radius: %s;\n", themeblvd_get_option('btn_corners'));
		$print .= "}\n";

		$print .= ".btn:not(.tb-custom-button),\n";
		$print .= "input[type=\"submit\"],\n";
		$print .= "input[type=\"reset\"],\n";
		$print .= "input[type=\"button\"],\n";
		$print .= ".button,\n";
		$print .= "button {\n";
		$print .= "\tborder: none;\n";
		$print .= "}\n";

	} else {

		$print .= ".btn,\n";
		$print .= "input[type=\"submit\"],\n";
		$print .= "input[type=\"reset\"],\n";
		$print .= "input[type=\"button\"],\n";
		$print .= ".button,\n";
		$print .= "button {\n";
		$print .= sprintf("\tborder-radius: %s;\n", themeblvd_get_option('btn_corners'));
		$print .= sprintf("\tborder-width: %s;\n", themeblvd_get_option('btn_border'));
		$print .= "}\n";

	}

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
		$print .= ".btn-default:active:hover,\n";
		$print .= ".btn-default.active:hover,\n";
		$print .= ".btn-default.active:focus,\n";
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

		if ( themeblvd_installed('woocommerce') ) {
			$print .= ".product_list_widget li > a > img,\n";
		}

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
	$print .= ".post_showcase .showcase-item.has-title .featured-item.showcase .tb-thumb-link:after,\n";
	$print .= ".post_showcase .showcase-item.has-title .featured-item.showcase.tb-thumb-link:after {\n";
	$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_rgb($highlight, '0.8'));
	$print .= "}\n";

	$print .= ".tb-floating-search .tb-search .search-input:focus,\n";
	$print .= ".tooltip-inner {\n";
	$print .= sprintf("\tborder-color: %s;\n", $highlight);
	$print .= "}\n";
	$print .= ".tooltip.top .tooltip-arrow,\n";
	$print .= ".tb-contact-popover.bottom {\n";
	$print .= sprintf("\tborder-top-color: %s;\n", $highlight);
	$print .= "}\n";
	$print .= ".tooltip.bottom .tooltip-arrow,\n";
	$print .= ".tb-contact-popover.bottom > .arrow:after {\n";
	$print .= sprintf("\tborder-bottom-color: %s;\n", $highlight);
	$print .= "}\n";

	/* Box Titles & Widgets */
	$widget_style = themeblvd_get_option('widget_style');

	if ( themeblvd_get_option('box_titles') ) {

		$print .= ".box-title,\n";

		if ( $widget_style == 'standard' ) {
			$print .= ".fixed-sidebar .widget-title,\n";
		}

		if ( themeblvd_installed('woocommerce') ) {
			$print .= ".products > h2,\n";
		}

		$print .= "#comments-title,\n";
		$print .= ".tb-info-box .info-box-title,\n";
		$print .= ".tb-related-posts .related-posts-title {\n";
		$print .= "\tborder-bottom: 2px solid #f2f2f2;\n";
		$print .= "\tborder-color: rgba(220,220,220,.4);\n";
		$print .= "\tpadding-bottom: .83em;\n";
		$print .= "\tposition: relative;\n";
		$print .= "}\n";

		$print .= ".box-title:before,\n";
		$print .= ".box-title:after,\n";

		if ( $widget_style == 'standard' ) {
			$print .= ".fixed-sidebar .widget-title:before,\n";
		}

		if ( themeblvd_installed('woocommerce') ) {
			$print .= ".products > h2:before,\n";
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
		$print .= "\tz-index: 2;\n";
		$print .= "}\n";

		$print .= ".box-title {\n";
		$print .= "\tborder-bottom-color: transparent;\n";
		$print .= "}\n";
		$print .= ".box-title:before {\n";
		$print .= "\twidth: 50px;\n";
		$print .= "}\n";
		$print .= ".box-title:after {\n";
		$print .= "\tbackground: #f2f2f2;\n";
		$print .= "\tbackground: rgba(220,220,220,.4);\n";
		$print .= "\twidth: 150px;\n";
		$print .= "\tz-index: 1;\n";
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

	if ( themeblvd_get_option('layout_style') == 'boxed'  ) {

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

			$print .= "#comments,\n";
			$print .= ".tb-related-posts,\n";

			if ( themeblvd_installed('woocommerce') ) {
				$print .= ".products.upsells,\n";
				$print .= ".products.related,\n";
			}

			if ( themeblvd_installed('bbpress') ) {
				$print .= ".tb-naked-page .bbp-topic-form,\n";
				$print .= ".tb-naked-page .bbp-reply-form,\n";
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

	// Header background (entire header, behind top bar and main menu)
	if ( ! themeblvd_config('suck_up') ) {

		$options = array();

		$options['bg_type'] = $header_bg_type;
		$options['bg_color'] = $header_bg_color;
		$options['bg_color_opacity'] = themeblvd_get_option('header_bg_color_opacity');
		$options['bg_texture'] = themeblvd_get_option('header_bg_texture');
		$options['apply_bg_texture_parallax'] = themeblvd_get_option('header_apply_bg_texture_parallax');
		$options['bg_image'] = themeblvd_get_option('header_bg_image');
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

		if ( themeblvd_get_option('header_info') == 'header_addon' && themeblvd_get_option('header_text_color') == 'light' ) {
			$print .= ".header-addon {\n";
			$print .= "\tcolor: #ffffff;\n";
			$print .= "}\n";
			$print .= ".header-top-nav > li {\n";
			$print .= "\tborder-color: rgba(0,0,0,.4);\n";
			$print .= "}\n";
		}

		// Header top bar
		if ( themeblvd_get_option('header_info') == 'header_top' ) {

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

	} else {

		// For transparent header, give header the
		// selected background color for mobile.
		$print .= "@media (max-width: 767px) {\n";
		$print .= "\t.site-header {\n";
		$print .= sprintf("\t\tbackground-color: %s;\n", $header_bg_color);
		$print .= "\t}\n";
		$print .= "}\n";

	}

	// Header padding
	if ( themeblvd_get_option('header_apply_padding_top') || themeblvd_get_option('header_apply_padding_bottom') ) {

		$print .= "@media (min-width: 992px) {\n";

		if ( themeblvd_get_option('header_apply_padding_top') ) {
			$print .= "\t.header-content > .wrap {\n";
			$print .= sprintf("\t\tpadding-top: %s;\n", themeblvd_get_option('header_padding_top'));
			$print .= "\t}\n";
		}

		if ( ! themeblvd_config('suck_up') && themeblvd_get_option('header_apply_padding_bottom') ) { // Bottom padding doesn't get applied on suck_up
			$print .= "\t.site-header > .wrap {\n";
			$print .= sprintf("\t\tpadding-bottom: %s;\n", themeblvd_get_option('header_padding_bottom'));
			$print .= "\t}\n";
		}

		$print .= "}\n";

	}

	// Header sticky menu
	if ( $header_bg_type && $header_bg_type != 'none' && $header_bg_color ) {

		$text_color = themeblvd_get_option('header_text_color');

		$print .= ".tb-sticky-menu {\n";
		$print .= sprintf("\tbackground-color: %s;\n", $header_bg_color);
		$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_rgb($header_bg_color, '0.9'));

		if ( $text_color == 'light' ) {
			$print .= "\tcolor: #ffffff;\n";
		} else {
			$print .= "\tcolor: #333333;\n";
		}

		$print .= "}\n";

		$print .= ".tb-sticky-menu .tb-primary-menu > li > .menu-btn,\n";
		$print .= ".tb-sticky-menu .tb-primary-menu > li > .menu-btn:hover {\n";

		if ( $text_color == 'light' ) {
			$print .= "\tcolor: #ffffff;\n";
		} else {
			$print .= "\tcolor: #333333;\n";
		}

		if ( $font = themeblvd_get_option('font_menu') ) {
			$print .= sprintf("\tfont-family: %s;\n", themeblvd_get_font_face($font) );
			$print .= sprintf("\tletter-spacing: %s;\n", themeblvd_get_option('font_menu_sp') );
			$print .= sprintf("\ttext-transform: %s;\n", themeblvd_get_text_transform($font) );
		}

		$print .= "}\n";

		$print .= ".tb-sticky-menu .tb-primary-menu > li > .menu-btn:hover {\n";

		if ( $text_color == 'light' ) {
			$print .= "\tbackground-color: #000000;\n";
			$print .= "\tbackground-color: rgba(255,255,255,.1);\n";
		} else {
			$print .= "\tbackground-color: #ffffff;\n";
			$print .= "\tbackground-color: rgba(0,0,0,.1);\n";
		}

		$print .= "}\n";

	}

	// Primary navigation
	$options = array();

	$options['corners'] = themeblvd_get_option('menu_corners');
	$options['font'] = themeblvd_get_option('font_menu');
	$options['font_sp'] = themeblvd_get_option('font_menu_sp');
	$options['sub_bg_color'] = themeblvd_get_option('menu_sub_bg_color');
	$options['sub_bg_color_brightness'] = themeblvd_get_option('menu_sub_bg_color_brightness');

	if ( ! themeblvd_config('suck_up') ) {

		$options['bg_type'] = themeblvd_get_option('menu_bg_type');
		$options['bg_color'] = themeblvd_get_option('menu_bg_color');
		$options['bg_gradient'] = themeblvd_get_option('menu_bg_gradient');
		$options['bg_color_brightness'] = themeblvd_get_option('menu_bg_color_brightness');
		$options['bg_color_opacity'] = themeblvd_get_option('menu_bg_color_opacity');

		$options['hover_bg_color'] = themeblvd_get_option('menu_hover_bg_color');
		$options['hover_bg_color_opacity'] = themeblvd_get_option('menu_hover_bg_color_opacity');
		$options['hover_bg_color_brightness'] = themeblvd_get_option('menu_hover_bg_color_brightness');

		$options['text_shadow'] = themeblvd_get_option('menu_text_shadow');

		$options['apply_border'] = themeblvd_get_option('menu_apply_border');
		$options['border_color'] = themeblvd_get_option('menu_border_color');
		$options['border_width'] = themeblvd_get_option('menu_border_width');

		$options['divider'] = themeblvd_get_option('menu_divider');
		$options['divider_color'] = themeblvd_get_option('menu_divider_color');

		$print .= ".header-nav {\n";

		if ( $options['bg_type'] == 'gradient' ) {

			$start = themeblvd_get_rgb( $options['bg_gradient']['start'], $options['bg_color_opacity'] );
			$end = themeblvd_get_rgb( $options['bg_gradient']['end'], $options['bg_color_opacity'] );

			$print .= sprintf("\tbackground-color: %s;\n", $end );
			$print .= sprintf("\tbackground-image: -webkit-gradient(linear, left top, left bottom, from(%s), to(%s));\n", $start, $end );
			$print .= sprintf("\tbackground-image: -webkit-linear-gradient(top, %s, %s);\n", $start, $end );
			$print .= sprintf("\tbackground-image: -moz-linear-gradient(top, %s, %s);\n", $start, $end );
			$print .= sprintf("\tbackground-image: -o-linear-gradient(top, %s, %s);\n", $start, $end );
			$print .= sprintf("\tbackground-image: -ms-linear-gradient(top, %s, %s);\n", $start, $end );
			$print .= sprintf("\tbackground-image: linear-gradient(top, %s, %s);\n", $start, $end );
			$print .= sprintf("\tfilter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='%s', EndColorStr='%s');\n", $start, $end );

		} else {

			$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_rgb( $options['bg_color'], $options['bg_color_opacity'] ));

		}

		if ( $options['bg_type'] == 'glassy' ) {
			$print .= sprintf("\tbackground-image: url(%s);\n", esc_url( themeblvd_get_base_uri('superuser') . '/images/menu-glassy.png' ) );
		} else if ( $options['bg_type'] == 'textured' ) {
			$print .= sprintf("\tbackground-image: url(%s);\n", esc_url( themeblvd_get_base_uri('superuser') . '/images/menu-textured.png' ) );
			$print .= "\tbackground-position: 0 0;\n";
			$print .= "\tbackground-repeat: repeat;\n";
			$print .= "\tbackground-size: 72px 56px;\n";
		}

		if ( $options['apply_border'] ) {

			$print .= sprintf("\tborder: %s solid %s;\n", $options['border_width'], $options['border_color'] );

			if ( themeblvd_get_option('header_apply_padding_bottom') && themeblvd_get_option('header_padding_bottom') == '0px' ) {
				$print .= "\tborder-bottom: none;\n";
			}
		}

		$print .= "}\n";

		if ( $options['apply_border'] ) {
			$print .= ".btn-navbar {\n";
			$print .= sprintf("\tborder: %s solid %s;\n", $options['border_width'], $options['border_color'] );
			$print .= "}\n";
		}

		if ( themeblvd_get_option('menu_search') ) {
			$print .= ".header-nav .tb-primary-menu .menu-search .tb-search-trigger,\n";
		}

		$print .= ".header-nav .tb-primary-menu > li > .menu-btn {\n";

		$print .= sprintf("\tcolor: %s;\n", $options['font']['color'] );
		$print .= sprintf("\tfont-family: %s;\n", themeblvd_get_font_face($options['font']) );
		$print .= sprintf("\tfont-size: %s;\n", themeblvd_get_font_size($options['font']) );
		$print .= sprintf("\tfont-style: %s;\n", themeblvd_get_font_style($options['font']) );
		$print .= sprintf("\tfont-weight: %s;\n", themeblvd_get_font_weight($options['font']) );
		$print .= sprintf("\tletter-spacing: %s;\n", $options['font_sp'] );
		$print .= sprintf("\ttext-transform: %s;\n", themeblvd_get_text_transform($options['font']) );

		if ( $options['text_shadow'] ) {
			$print .= "\ttext-shadow: 1px 1px 1px rgba(0,0,0,.8);\n";
		}

		$print .= "}\n";


		if ( themeblvd_get_option('menu_search') ) {
			$print .= ".header-nav .tb-primary-menu .menu-search .tb-search-trigger:hover,\n";
		}

		$print .= ".tb-primary-menu > li > a:hover {\n";

		$print .= sprintf("\tbackground-color: %s;\n", $options['hover_bg_color'] );
		$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_rgb($options['hover_bg_color'], $options['hover_bg_color_opacity']) );

		if ( $options['hover_bg_color_brightness'] == 'light' ) {
			$print .= "\tcolor: #333333;\n";
		}

		$print .= "}\n";

		// Primary menu mobile toggle
		$print .= ".btn-navbar {\n";

		if ( $options['bg_type'] == 'gradient' ) {
			$print .= sprintf("\tbackground-color: %s;\n", $options['bg_gradient']['end'] );
			$print .= sprintf("\tbackground-image: -webkit-gradient(linear, left top, left bottom, from(%s), to(%s));\n", $options['bg_gradient']['start'], $options['bg_gradient']['end'] );
			$print .= sprintf("\tbackground-image: -webkit-linear-gradient(top, %s, %s);\n", $options['bg_gradient']['start'], $options['bg_gradient']['end'] );
			$print .= sprintf("\tbackground-image: -moz-linear-gradient(top, %s, %s);\n", $options['bg_gradient']['start'], $options['bg_gradient']['end'] );
			$print .= sprintf("\tbackground-image: -o-linear-gradient(top, %s, %s);\n", $options['bg_gradient']['start'], $options['bg_gradient']['end'] );
			$print .= sprintf("\tbackground-image: -ms-linear-gradient(top, %s, %s);\n", $options['bg_gradient']['start'], $options['bg_gradient']['end'] );
			$print .= sprintf("\tbackground-image: linear-gradient(top, %s, %s);\n", $options['bg_gradient']['start'], $options['bg_gradient']['end'] );
			$print .= sprintf("\tfilter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='%s', EndColorStr='%s');\n", $options['bg_gradient']['start'], $options['bg_gradient']['end'] );
		} else {
			$print .= sprintf("\tbackground-color: %s;\n", $options['bg_color']);
		}

		if ( $options['bg_color_brightness'] == 'light' ) {
			$print .= "\tcolor: #333333 !important;\n";
		}

		$print .= "}\n";

		$print .= ".btn-navbar:hover {\n";

		if ( $options['bg_type'] == 'gradient' && $options['bg_gradient'] ) {
			$print .= sprintf("\tbackground-color: %s;\n", themeblvd_adjust_color( $options['bg_gradient']['end'] ) );
		} else if ( $options['bg_color'] ) {
			$print .= sprintf("\tbackground-color: %s;\n", themeblvd_adjust_color( $options['bg_color'] ) );
		}

		$print .= "}\n";

		// Button dividers
		if ( $options['divider'] ) {

			$start = 'left';
			$end = 'right';

			if ( is_rtl() ) {
				$start = 'right';
				$end = 'left';
			}

			$print .= ".header-nav .tb-primary-menu > li {\n";
			$print .= sprintf("\tborder-%s: 1px solid %s;\n", $end, $options['divider_color']);
			$print .= "}\n";

			if ( themeblvd_get_option('menu_center') ) {
				$print .= ".header-nav .tb-primary-menu > li:first-child {\n";
				$print .= sprintf("\tborder-%s: 1px solid %s;\n", $start, $options['divider_color']);
				$print .= "}\n";
			}

			if ( themeblvd_get_option('menu_search') && ! themeblvd_get_option('menu_center') ) {
				$print .= ".header-nav .tb-primary-menu .menu-search {\n";
				$print .= sprintf("\tborder-%s: 1px solid %s;\n", $start, $options['divider_color']);
				$print .= sprintf("\tborder-%s: none;\n", $end);
				$print .= "}\n";
			}

			$print .= ".header-nav .tb-primary-menu > li > ul.non-mega-sub-menu {\n";
			$print .= sprintf("\tmargin-%s: -1px;\n", $start);
			$print .= "}\n";

		}

	} // end IF suck_up

	// Primary nav custom font size/family for suck_up
	if ( themeblvd_config('suck_up') && $options['font'] ) {

		if ( themeblvd_get_option('menu_search') ) {
			$print .= ".header-nav .tb-primary-menu .menu-search .tb-search-trigger,\n";
		}

		$print .= ".header-nav .tb-primary-menu > li > .menu-btn {\n";

		$print .= sprintf("\tfont-family: %s;\n", themeblvd_get_font_face($options['font']) );
		$print .= sprintf("\tfont-size: %s;\n", themeblvd_get_font_size($options['font']) );
		$print .= sprintf("\tfont-style: %s;\n", themeblvd_get_font_style($options['font']) );
		$print .= sprintf("\tfont-weight: %s;\n", themeblvd_get_font_weight($options['font']) );
		$print .= sprintf("\ttext-transform: %s;\n", themeblvd_get_text_transform($options['font']) );

		$print .= "}\n";
	}

	// Primary nav border radius
	if ( $options['corners'] && $options['corners'] != '0px' ) {

		$print .= ".header-nav,\n";
		$print .= ".btn-navbar {\n";
		$print .= sprintf("\tborder-radius: %s;\n", $options['corners']);
		$print .= "}\n";

		if ( ! themeblvd_config('suck_up') && (themeblvd_get_option('header_apply_padding_bottom') && themeblvd_get_option('header_padding_bottom') == '0px') ) {
			$print .= ".header-nav {\n";
			$print .= "\t-webkit-border-bottom-right-radius: 0;\n";
			$print .= "\t-webkit-border-bottom-left-radius: 0;\n";
			$print .= "\tborder-bottom-right-radius: 0;\n";
			$print .= "\tborder-bottom-left-radius: 0;\n";
			$print .= "}\n";
		}

		// Fix for buttons hugging corners
		$fix = intval($options['corners']);

		if ( ! themeblvd_config('suck_up') && $options['apply_border'] ) { // menu border not applied on suck_up

			$border = intval($options['border_width']);

			if ( ($fix - $border) >= 0 ) {
				$fix = $fix - $border;
			}
		}

		$start = 'left';
		$end = 'right';

		if ( is_rtl() ) {
			$start = 'right';
			$end = 'left';
		}

		$print .= ".header-nav .tb-primary-menu > li:first-child > .menu-btn {\n";
		$print .= sprintf("\t-webkit-border-top-%s-radius: %spx;\n", $start, $fix);
		$print .= sprintf("\tborder-top-%s-radius: %spx;\n", $start, $fix);

		if ( themeblvd_config('suck_up') || ! themeblvd_get_option('header_apply_padding_bottom') || ( themeblvd_get_option('header_apply_padding_bottom') && themeblvd_get_option('header_padding_bottom') != '0px') ) {
			$print .= sprintf("\t-webkit-border-bottom-%s-radius: %spx;\n", $start, $fix);
			$print .= sprintf("\tborder-bottom-%s-radius: %spx;\n", $start, $fix);
		}

		$print .= "}\n";

		if ( themeblvd_get_option('menu_search') ) {

			$print .= ".header-nav .tb-primary-menu .menu-search .tb-search-trigger {\n";
			$print .= sprintf("\t-webkit-border-top-%s-radius: %spx;\n", $end, $fix);
			$print .= sprintf("\tborder-top-%s-radius: %spx;\n", $end, $fix);

			if ( themeblvd_config('suck_up') || ! themeblvd_get_option('header_apply_padding_bottom') || ( themeblvd_get_option('header_apply_padding_bottom') && themeblvd_get_option('header_padding_bottom') != '0px') ) {
				$print .= sprintf("\t-webkit-border-bottom-%s-radius: %spx;\n", $end, $fix);
				$print .= sprintf("\tborder-bottom-%s-radius: %spx;\n", $end, $fix);
			}

			$print .= "}\n";
		}

	}

	// Primary nav sub menus
	$print .= ".tb-primary-menu ul.non-mega-sub-menu,\n";
	$print .= ".tb-primary-menu .sf-mega {\n";
	$print .= sprintf("\tbackground-color: %s;\n", $options['sub_bg_color'] );
	$print .= "}\n";

	if ( $options['sub_bg_color_brightness'] == 'dark' ) {

		$print .= ".tb-primary-menu ul.sub-menu .menu-btn,\n";
		$print .= ".tb-primary-menu .mega-section-header {\n";
		$print .= "\tcolor: #ffffff;\n";
		$print .= "}\n";

		$print .= ".tb-primary-menu ul.non-mega-sub-menu,\n";
		$print .= ".tb-primary-menu .sf-mega {\n";
		$print .= "\tborder-color: rgba(0,0,0,.2);\n";
		$print .= "}";

		$print .= "\t.tb-primary-menu ul.sub-menu a:hover {";
		$print .= "background-color: rgba(255,255,255,.1)\n";
		$print .= "}\n";

	}

	// Primary mobile menu
	$print .= ".tb-mobile-menu-wrapper,\n";
	$print .= ".tb-mobile-menu-wrapper .tb-side-menu,\n";
	$print .= ".tb-mobile-menu-wrapper .tb-side-menu .sub-menu li.non-mega-sub-menu:last-child {\n";
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

	// Sanitize
	$print = wp_kses( $print, array() );
	$print = htmlspecialchars_decode( $print );

	// Final output
	if ( $print ) {
		wp_add_inline_style( 'jumpstart-base', apply_filters('jumpstart_ex_css_output', $print) );
	}

}
add_action('wp_enqueue_scripts', 'jumpstart_ex_css', 25);

/**
 * Add CSS classes and parralax data() to header
 *
 * @since 2.0.0
 */
function jumpstart_ex_header_class( $class ) {

	$options = array(
		'bg_type'						=> themeblvd_get_option('header_bg_type'),
		'apply_bg_shade'				=> themeblvd_get_option('header_apply_bg_shade'),
		'apply_bg_texture_parallax'		=> themeblvd_get_option('header_apply_bg_texture_parallax'),
		'bg_image'						=> themeblvd_get_option('header_bg_image'),
		'bg_slideshow'					=> themeblvd_get_option('header_bg_slideshow'),
		'bg_video'						=> themeblvd_get_option('header_bg_video')
	);

	if ( themeblvd_config('suck_up') ) {
		unset($options['bg_type']);
	}

	$class = array_merge( $class, themeblvd_get_display_class($options) );

	if ( themeblvd_get_option('top_mini', null, '0') ) {
		$class[] = 'header-top-mini';
	}

	return $class;
}
add_filter('themeblvd_header_class', 'jumpstart_ex_header_class', 10, 2);

/**
 * Add CSS classes to footer
 *
 * @since 2.0.0
 */
function jumpstart_ex_footer_class( $class ) {

	$bg_type = themeblvd_get_option('footer_bg_type');

	if ( $bg_type == 'color' || $bg_type == 'texture' ) {

		if ( themeblvd_get_option('footer_bg_color_brightness') == 'dark' ) {
			$class[] = 'text-light';
		}

		$class[] = 'has-bg';

	}

	return $class;
}
add_filter('themeblvd_footer_class', 'jumpstart_ex_footer_class');

/**
 * Height of the header, not including the logo.
 * Used with "suck up" feature.
 *
 * @since 2.0.0
 */
function jumpstart_ex_top_height_addend( $addend, $context ) {

	$addend = 0;

	// Header top bar
	if ( themeblvd_get_option('header_info') == 'header_top' && themeblvd_has_header_info() ) {
		$addend += 48;
	}

	// Header top padding
	if ( themeblvd_get_option('header_apply_padding_top') ) {
		$addend += intval(themeblvd_get_option('header_padding_top'));
	} else {
		$addend += 20;
	}

	// Space between logo and menu
	$addend += 20;

	if ( $context == 'desktop' ) {

		// Main menu default top padding
		$addend += 18;

		// Main menu font size
		if ( $font = themeblvd_get_option('font_menu') ) {
			$addend += intval($font['size']);
		} else {
			$addend += 14; // Default menu font size
		}

		// Main menu default bottom padding
		$addend += 18;

	}

	// Header's bottom padding fixed at 0 for suck_up
	// $addend += 0;

	return $addend;

}
add_filter('themeblvd_top_height_addend', 'jumpstart_ex_top_height_addend', 10, 2);

/**
 * Add any outputted HTML needed for header styling
 * options to work.
 *
 * @since 2.0.0
 */
function jumpstart_ex_header_top() {

	if ( themeblvd_config('suck_up') ) {
		return;
	}

	$display = array(
		'bg_type' 						=> themeblvd_get_option('header_bg_type'),
		'bg_color' 						=> themeblvd_get_option('header_bg_color'),
		'bg_texture'					=> themeblvd_get_option('header_bg_texture'),
		'apply_bg_texture_parallax'		=> themeblvd_get_option('header_apply_bg_texture_parallax'),
		'bg_image' 						=> themeblvd_get_option('header_bg_image'),
		'apply_bg_shade'				=> themeblvd_get_option('header_apply_bg_shade'),
		'bg_shade_color'				=> themeblvd_get_option('header_bg_shade_color'),
		'bg_shade_opacity'				=> themeblvd_get_option('header_bg_shade_opacity'),
		'bg_slideshow'					=> themeblvd_get_option('header_bg_slideshow'),
		'apply_bg_slideshow_parallax'	=> themeblvd_get_option('header_apply_bg_slideshow_parallax'),
		'bg_video'						=> themeblvd_get_option('header_bg_video')
	);

	if ( ( $display['bg_type'] == 'image' || $display['bg_type'] == 'slideshow' ) && ! empty($display['apply_bg_shade']) ) {
		printf( '<div class="bg-shade" style="background-color: %s;"></div>', esc_attr( themeblvd_get_rgb( $display['bg_shade_color'], $display['bg_shade_opacity'] ) ) );
	}

	if ( themeblvd_do_parallax($display) ) {
		themeblvd_bg_parallax($display);
	}

	if ( $display['bg_type'] == 'video' && ! empty($display['bg_video']) ) {
		echo '<div class="header-video">';
		themeblvd_bg_video( $display['bg_video'] );
		echo '</div><!-- .header-video (end) -->';
	}

	if ( $display['bg_type'] == 'slideshow' && ! empty($display['bg_slideshow']) ) {

		$parallax = false;

		if ( ! empty($display['apply_bg_slideshow_parallax']) ) {
			$parallax = true;
		}

		themeblvd_bg_slideshow( 'header', $display['bg_slideshow'], $parallax );
	}

}
add_action('themeblvd_header_top', 'jumpstart_ex_header_top', 5);

/**
 * If user has selected to have the header info
 * within the content of the header, let's remove
 * it from the themeblvd_header action, and move
 * to the themeblvd_header_addon action.
 *
 * @since 2.0.0
 */
function jumpstart_ex_header_info() {

	if ( themeblvd_get_option('header_info') == 'header_addon' ) {
		remove_action('themeblvd_header_top', 'themeblvd_header_top_default');
		add_action('themeblvd_header_addon', 'jumpstart_ex_header_addon');
	}
}
add_action('wp', 'jumpstart_ex_header_info');

/**
 * Add header text, search, and social icons to header content area.
 *
 * @since 2.0.0
 */
function jumpstart_ex_header_addon() {

	if ( ! themeblvd_has_header_info() ) {
		return;
	}

	$header_text = themeblvd_get_option('header_text');
	$icons = themeblvd_get_option('social_media');

	$class = 'header-addon';

	if ( $header_text ) {
		$class .= ' header-addon-with-text';
	}

	printf('<div class="%s">', $class);

	if ( themeblvd_get_option('searchform') == 'show' || $icons || themeblvd_do_cart() || ( themeblvd_installed('wpml') && themeblvd_supports('plugins', 'wpml') && get_option('tb_wpml_show_lang_switcher', '1') ) ) {

		echo '<ul class="header-top-nav list-unstyled clearfix">';

		// Floating search trigger
		if ( themeblvd_get_option('searchform') == 'show' ) {
			printf('<li class="top-search">%s</li>', themeblvd_get_floating_search_trigger());
		}

		// Floating shopping cart
		if ( themeblvd_do_cart() ) {
			printf('<li class="top-cart">%s</li>', themeblvd_get_cart_popup_trigger());
		}

		// Contact icons. Note: We're not using themeblvd_get_contact_bar()
		// to account for the "suck up" header and outputting extra
		// contact icon set.
		if ( $icons ) {
			echo '<li class="top-icons">';
			themeblvd_contact_bar( $icons, array('class' => 'to-mobile') );
			echo '</li>';
		}

		// WPML switcher
		if ( themeblvd_installed('wpml') && themeblvd_supports('plugins', 'wpml') && get_option('tb_wpml_show_lang_switcher', '1') ) {
			echo '<li class="top-wpml">';
			do_action('icl_language_selector');
			echo '</li>';
		}

		echo '</ul>';

	}

	// Header text
	themeblvd_header_text();

	echo '</div><!-- .header-addon (end) -->';

}

/**
 * Add floating search to main menu.
 *
 * @since 2.0.0
 */
function jumpstart_ex_nav_search( $items, $args ) {

	if ( $args->theme_location == 'primary' && themeblvd_get_option('menu_search') ) {
		$items .= sprintf('<li class="menu-search">%s</li>', themeblvd_get_floating_search_trigger());
	}

	return $items;
}
add_filter('wp_nav_menu_items', 'jumpstart_ex_nav_search', 10, 2);

/**
 * Make sure floating search outputs in header if user
 * selected it for main menu.
 *
 * @since 2.0.0
 */
function jumpstart_ex_do_floating_search( $do ) {

	if ( themeblvd_get_option('menu_search') ) {
		$do = true;
	}

	return $do;
}
add_filter('themeblvd_do_floating_search', 'jumpstart_ex_do_floating_search');

/**
 * Filter args that get filtered in when
 * all sidebars are registered.
 *
 * @since 2.5.0
 */
function themeblvd_ex_sidebar_args( $args, $sidebar, $location ) {

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
add_filter('themeblvd_default_sidebar_args', 'themeblvd_ex_sidebar_args', 10, 3);
add_filter('themeblvd_custom_sidebar_args', 'themeblvd_ex_sidebar_args', 10, 3);
