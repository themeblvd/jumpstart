<?php
/**
 * Theme Base: Entrepeneur, Options
 *
 * @author    Jason Bobich <info@themeblvd.com>
 * @copyright 2009-2017 Theme Blvd
 * @package   @@name-package
 * @since     @@name-package 2.0.0
 */

/**
 * Add theme options to framework.
 *
 * @since @@name-package 2.0.0
 */
function jumpstart_ent_options() {

	// Background support
	add_theme_support( 'custom-background', array(
		'default-color' => 'f8f8f8',
		'default-image' => '',
	));

	$bg_types = array();

	if ( function_exists( 'themeblvd_get_bg_types' ) ) {

		$bg_types = themeblvd_get_bg_types( 'section' );

	}

	/**
	 * Filters the options added by the Entrepeneur
	 * base to the theme options page.
	 *
	 * @since @@name-package 2.0.0
	 *
	 * @param array Options added.
	 */
	$options = apply_filters( 'jumpstart_ent_options', array(
		'general' => array(
			'sub_group_start_1' => array(
				'id'      => 'sub_group_start_1',
				'type'    => 'subgroup_start',
				'class'   => 'show-hide-toggle',
			),
			'layout_style' => array(
				'id'      => 'layout_style',
				'name'    => __( 'Site Layout Style', '@@text-domain' ),
				'desc'    => __( 'Select whether you\'d like the layout of the theme to be boxed or not.', '@@text-domain' ),
				'std'     => 'stretch',
				'type'    => 'select',
				'options' => array(
					'stretch' => __( 'Stretch', '@@text-domain' ),
					'boxed'   => __( 'Boxed', '@@text-domain' ),
				),
				'class'   => 'trigger',
			),
			'layout_shadow_size' => array(
				'id'      => 'layout_shadow_size',
				'name'    => __( 'Layout Shadow Size', '@@text-domain' ),
				'desc'    => __( 'Select the size of the shadow around the boxed layout. Set to 0px for no shadow.', '@@text-domain' ),
				'std'     => '5px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '20',
				),
				'class'   => 'receiver receiver-boxed',
			),
			'layout_shadow_opacity' => array(
				'id'      => 'layout_shadow_opacity',
				'name'    => __( 'Layout Shadow Strength', '@@text-domain' ),
				// translators: 1: link to Backgrounds admin screen
				'desc'    => sprintf( __( 'Select the opacity of the shadow for the boxed layout. The darker %s, the closer to 100%% you want to go.', '@@text-domain' ), '<a href="' . esc_url( admin_url( 'customize.php?autofocus[control]=background_image' ) ) . '" target="_blank">' . __( 'your background', '@@text-domain' ) . '</a>' ),
				'std'     => '0.3',
				'type'    => 'select',
				'options' => array(
					'0.05' => '5%',
					'0.1'  => '10%',
					'0.15' => '15%',
					'0.2'  => '20%',
					'0.25' => '25%',
					'0.3'  => '30%',
					'0.35' => '35%',
					'0.4'  => '40%',
					'0.45' => '45%',
					'0.5'  => '50%',
					'0.55' => '55%',
					'0.6'  => '60%',
					'0.65' => '65%',
					'0.7'  => '70%',
					'0.75' => '75%',
					'0.8'  => '80%',
					'0.85' => '85%',
					'0.9'  => '90%',
					'0.95' => '95%',
					'1'    => '100%',
				),
				'class'   => 'receiver  receiver-boxed',
			),
			'layout_border_width' => array(
				'id'      => 'layout_border_width',
				'name'    => __( 'Layout Border Width', '@@text-domain' ),
				'desc'    => __( 'Select a width in pixels for the boxed layout. Set to 0px for no border.', '@@text-domain' ),
				'std'     => '0px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '20',
				),
				'class'   => 'receiver receiver-boxed',
			),
			'layout_border_color' => array(
				'id'      => 'layout_border_color',
				'name'    => __( 'Layout Border Color', '@@text-domain' ),
				'desc'    => __( 'Select a color for the border around the boxed layout.', '@@text-domain' ),
				'std'     => '#cccccc',
				'type'    => 'color',
				'class'   => 'receiver receiver-boxed',
			),
			'sub_group_start_2' => array(
				'id'      => 'sub_group_start_2',
				'type'    => 'subgroup_start',
				'class'   => 'show-hide receiver receiver-stretch',
			),
			'apply_content_border' => array(
				'id'      => 'apply_content_border',
				'name'    => null,
				'desc'    => '<strong>' . __( 'Content Border', '@@text-domain' ) . '</strong>: ' . __( 'Apply border around content areas.', '@@text-domain' ),
				'std'     => 0,
				'type'    => 'checkbox',
				'class'   => 'trigger',
			),
			'content_border_color' => array(
				'id'      => 'content_border_color',
				'name'    => __( 'Content Border Color', '@@text-domain' ),
				'desc'    => __( 'Select a color for the border around content areas.', '@@text-domain' ),
				'std'     => '#f2f2f2',
				'type'    => 'color',
				'class'   => 'hide receiver',
			),
			'content_border_width' => array(
				'id'      => 'content_border_width',
				'name'    => __( 'Bottom Border Width', '@@text-domain' ),
				'desc'    => __( 'Select a width in pixels for the border around content areas.', '@@text-domain' ),
				'std'     => '1px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '1',
					'max'   => '10',
				),
				'class'   => 'hide receiver',
			),
			'sub_group_end_2' => array(
				'id'      => 'sub_group_end_2',
				'type'    => 'subgroup_end',
			),
			'sub_group_end_1' => array(
				'id'      => 'sub_group_end_1',
				'type'    => 'subgroup_end',
			),
		),
		'header_info' => array(
			'header_info' => array(
				'name'    => null,
				'desc'    => __( 'Note: With the Entrepeneur theme base, for the header info top bar to show, you must enter text at Theme Options > Layout > Header > Header Text. Without this, other header info elements like social icons and searchform, will be displayed next to the main menu instead.', '@@text-domain' ),
				'id'      => 'header_info',
				'type'    => 'info',
			),
			'top_bg_color' => array(
				'id'      => 'top_bg_color',
				'name'    => __( 'Top Bar Background Color', '@@text-domain' ),
				'desc'    => __( 'Select a background color for the bar that runs across the top of the header.', '@@text-domain' ),
				'std'     => '#ffffff',
				'type'    => 'color',
			),
			'top_bg_color_opacity' => array(
				'id'      => 'top_bg_color_opacity',
				'name'    => __( 'Top Bar Background Color Opacity', '@@text-domain' ),
				'desc'    => __( 'Select the opacity of the above background color. Selecting "100%" means that the background color is not transparent, at all.', '@@text-domain' ),
				'std'     => '1',
				'type'    => 'select',
				'options' => array(
					'0.05' => '5%',
					'0.1'  => '10%',
					'0.15' => '15%',
					'0.2'  => '20%',
					'0.25' => '25%',
					'0.3'  => '30%',
					'0.35' => '35%',
					'0.4'  => '40%',
					'0.45' => '45%',
					'0.5'  => '50%',
					'0.55' => '55%',
					'0.6'  => '60%',
					'0.65' => '65%',
					'0.7'  => '70%',
					'0.75' => '75%',
					'0.8'  => '80%',
					'0.85' => '85%',
					'0.9'  => '90%',
					'0.95' => '95%',
					'1'    => '100%',
				),
			),
			'top_text_color' => array(
				'id'      => 'top_text_color',
				'name'    => __( 'Top Bar Text Color', '@@text-domain' ),
				'desc'    => __( 'If you\'re using a dark background color, select to show light text, and vice versa.', '@@text-domain' ),
				'std'     => 'dark',
				'type'    => 'select',
				'options' => array(
					'dark'  => __( 'Dark Text', '@@text-domain' ),
					'light' => __( 'Light Text', '@@text-domain' ),
				),
			),
			'sub_group_start_3' => array(
				'id'      => 'sub_group_start_3',
				'type'    => 'subgroup_start',
				'class'   => 'show-hide',
			),
			'top_apply_border_bottom' => array(
				'id'      => 'top_apply_border_bottom',
				'name'    => null,
				'desc'    => '<strong>' . __( 'Bottom Border', '@@text-domain' ) . '</strong>: ' . __( 'Apply bottom border to the top bar of the header.', '@@text-domain' ),
				'std'     => 1,
				'type'    => 'checkbox',
				'class'   => 'trigger',
			),
			'top_border_bottom_color' => array(
				'id'      => 'top_border_bottom_color',
				'name'    => __( 'Bottom Border Color', '@@text-domain' ),
				'desc'    => __( 'Select a color for the bottom border.', '@@text-domain' ),
				'std'     => '#f2f2f2',
				'type'    => 'color',
				'class'   => 'hide receiver',
			),
			'top_border_bottom_width' => array(
				'id'      => 'top_border_bottom_width',
				'name'    => __( 'Bottom Border Width', '@@text-domain' ),
				'desc'    => __( 'Select a width in pixels for the bottom border.', '@@text-domain' ),
				'std'     => '1px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '1',
					'max'   => '10',
				),
				'class'   => 'hide receiver',
			),
			'sub_group_end_3' => array(
				'id'      => 'sub_group_end_3',
				'type'    => 'subgroup_end',
			),
			'top_mini' => array(
				'id'      => 'top_mini',
				'name'    => null,
				'desc'    => '<strong>' . __( 'Mini Display', '@@text-domain' ) . '</strong>: ' . __( 'Display top bar a bit smaller and more condensed.', '@@text-domain' ),
				'std'     => 1,
				'type'    => 'checkbox',
			),
		),
		'header' => array(
			'header_height' => array(
				'id'      => 'header_height',
				'name'    => __( 'Header Height', '@@text-domain' ),
				'desc'    => __( 'Apply a fixed height to the header. Keep in mind that your header logo image will always be displayed to match the height of your header, minus 20px.', '@@text-domain' ),
				'type'    => 'slide',
				'std'     => '70px',
				'options' => array(
					'units' => 'px',
					'min'   => '45',
					'max'   => '150',
					'step'  => '1',
				),
			),
			'sub_group_start_4' => array(
				'id'      => 'sub_group_start_4',
				'type'    => 'subgroup_start',
				'class'   => 'show-hide-toggle',
			),
			'header_bg_type' => array(
				'id'      => 'header_bg_type',
				'name'    => __( 'Apply Header Background', '@@text-domain' ),
				'desc'    => __( 'Select if you\'d like to apply a custom background and how you want to control it.', '@@text-domain' ),
				'std'     => 'color',
				'type'    => 'select',
				'options' => $bg_types,
				'class'   => 'trigger',
			),
			'header_bg_color' => array(
				'id'      => 'header_bg_color',
				'name'    => __( 'Background Color', '@@text-domain' ),
				'desc'    => __( 'Select a background color.', '@@text-domain' ),
				'std'     => '#ffffff',
				'type'    => 'color',
				'class'   => 'hide receiver receiver-color receiver-texture receiver-image receiver-slideshow',
			),
			'header_bg_color_opacity' => array(
				'id'      => 'header_bg_color_opacity',
				'name'    => __( 'Background Color Opacity', '@@text-domain' ),
				'desc'    => __( 'Select the opacity of the background color. Selecting "100%" means that the background color is not transparent, at all.', '@@text-domain' ),
				'std'     => '1',
				'type'    => 'select',
				'options' => array(
					'0.05' => '5%',
					'0.1'  => '10%',
					'0.15' => '15%',
					'0.2'  => '20%',
					'0.25' => '25%',
					'0.3'  => '30%',
					'0.35' => '35%',
					'0.4'  => '40%',
					'0.45' => '45%',
					'0.5'  => '50%',
					'0.55' => '55%',
					'0.6'  => '60%',
					'0.65' => '65%',
					'0.7'  => '70%',
					'0.75' => '75%',
					'0.8'  => '80%',
					'0.85' => '85%',
					'0.9'  => '90%',
					'0.95' => '95%',
					'1'    => '100%',
				),
				'class'   => 'hide receiver receiver-color receiver-texture',
			),
			'header_bg_texture' => array(
				'id'      => 'header_bg_texture',
				'name'    => __( 'Background Texture', '@@text-domain' ),
				'desc'    => __( 'Select a background texture.', '@@text-domain' ),
				'type'    => 'select',
				'select'  => 'textures',
				'class'   => 'hide receiver receiver-texture',
			),
			'header_apply_bg_texture_parallax' => array(
				'id'      => 'header_apply_bg_texture_parallax',
				'name'    => null,
				'desc'    => __( 'Apply parallax scroll effect to background texture.', '@@text-domain' ),
				'type'    => 'checkbox',
				'class'   => 'hide receiver receiver-texture',
			),
			'sub_group_start_5' => array(
				'id'      => 'sub_group_start_5',
				'type'    => 'subgroup_start',
				'class'   => 'select-parallax hide receiver receiver-image',
			),
			'header_bg_image' => array(
				'id'      => 'header_bg_image',
				'name'    => __( 'Background Image', '@@text-domain' ),
				'desc'    => __( 'Select a background image.', '@@text-domain' ),
				'type'    => 'background',
				'color'    => false,
				'parallax' => true,
			),
			'sub_group_end_5' => array(
				'id'      => 'sub_group_end_5',
				'type'    => 'subgroup_end',
			),
			'header_bg_video' => array(
				'id'      => 'header_bg_video',
				'name'    => __( 'Background Video', '@@text-domain' ),
				'desc'    => __( 'You can upload a web-video file (mp4, webm, ogv), or input a URL to a video page on YouTube or Vimeo. Your fallback image will display on mobile devices.', '@@text-domain' ) . '<br><br>' . __( 'Examples:', '@@text-domain' ) . '<br>https://vimeo.com/79048048<br>http://www.youtube.com/watch?v=5guMumPFBag',
				'type'    => 'background_video',
				'class'   => 'hide receiver receiver-video',
			),
			'sub_group_start_6' => array(
				'id'      => 'sub_group_start_6',
				'type'    => 'subgroup_start',
				'class'   => 'show-hide hide receiver receiver-image receiver-slideshow',
			),
			'header_apply_bg_shade' => array(
				'id'      => 'header_apply_bg_shade',
				'name'    => null,
				'desc'    => __( 'Shade background with transparent color.', '@@text-domain' ),
				'std'     => 0,
				'type'    => 'checkbox',
				'class'   => 'trigger',
			),
			'header_bg_shade_color' => array(
				'id'      => 'header_bg_shade_color',
				'name'    => __( 'Shade Color', '@@text-domain' ),
				'desc'    => __( 'Select the color you want overlaid on your background.', '@@text-domain' ),
				'std'     => '#000000',
				'type'    => 'color',
				'class'   => 'hide receiver',
			),
			'header_bg_shade_opacity' => array(
				'id'      => 'header_bg_shade_opacity',
				'name'    => __( 'Shade Opacity', '@@text-domain' ),
				'desc'    => __( 'Select the opacity of the shade color overlaid on your background.', '@@text-domain' ),
				'std'     => '0.5',
				'type'    => 'select',
				'options' => array(
					'0.05' => '5%',
					'0.1'  => '10%',
					'0.15' => '15%',
					'0.2'  => '20%',
					'0.25' => '25%',
					'0.3'  => '30%',
					'0.35' => '35%',
					'0.4'  => '40%',
					'0.45' => '45%',
					'0.5'  => '50%',
					'0.55' => '55%',
					'0.6'  => '60%',
					'0.65' => '65%',
					'0.7'  => '70%',
					'0.75' => '75%',
					'0.8'  => '80%',
					'0.85' => '85%',
					'0.9'  => '90%',
					'0.95' => '95%',
				),
				'class'   => 'hide receiver',
			),
			'sub_group_end_6' => array(
				'id'      => 'sub_group_end_6',
				'type'    => 'subgroup_end',
			),
			'sub_group_start_7' => array(
				'id'      => 'sub_group_start_7',
				'type'    => 'subgroup_start',
				'class'   => 'section-bg-slideshow hide receiver receiver-slideshow',
			),
			'header_bg_slideshow' => array(
				'id'      => 'header_bg_slideshow',
				'name'    => __( 'Slideshow Images', '@@text-domain' ),
				'desc'    => null,
				'type'    => 'slider',
			),
			'header_bg_slideshow_crop' => array(
				'name'    => __( 'Slideshow Crop Size', '@@text-domain' ),
				'desc'    => __( 'Select the crop size to be used for the background slideshow images. Remember that the background images will be stretched to cover the area.', '@@text-domain' ),
				'id'      => 'header_bg_slideshow_crop',
				'std'     => 'full',
				'type'    => 'select',
				'select'  => 'crop',
			),
			'header_apply_bg_slideshow_parallax' => array(
				'id'      => 'header_apply_bg_slideshow_parallax',
				'name'    => null,
				'desc'    => __( 'Apply parallax scroll effect to background slideshow.', '@@text-domain' ),
				'type'    => 'checkbox',
			),
			'sub_group_end_7' => array(
				'id'      => 'sub_group_end_7',
				'type'    => 'subgroup_end',
			),
			'sub_group_end_4' => array(
				'id'      => 'sub_group_end_4',
				'type'    => 'subgroup_end',
			),
			'sub_group_start_8' => array(
				'id'      => 'sub_group_start_8',
				'type'    => 'subgroup_start',
				'class'   => 'show-hide',
			),
			'header_text_color' => array(
				'id'      => 'header_text_color',
				'name'    => __( 'Text Color', '@@text-domain' ),
				'desc'    => __( 'If you\'re using a dark background color, select to show light text, and vice versa.', '@@text-domain' ),
				'std'     => 'dark',
				'type'    => 'select',
				'options' => array(
					'dark'  => __( 'Dark Text', '@@text-domain' ),
					'light' => __( 'Light Text', '@@text-domain' ),
				),
			),
			'header_apply_border_top' => array(
				'id'      => 'header_apply_border_top',
				'name'    => null,
				'desc'    => '<strong>' . __( 'Top Border', '@@text-domain' ) . '</strong>: ' . __( 'Apply top border to header.', '@@text-domain' ),
				'std'     => 0,
				'type'    => 'checkbox',
				'class'   => 'trigger',
			),
			'header_border_top_color' => array(
				'id'      => 'header_border_top_color',
				'name'    => __( 'Top Border Color', '@@text-domain' ),
				'desc'    => __( 'Select a color for the top border.', '@@text-domain' ),
				'std'     => '#f2f2f2',
				'type'    => 'color',
				'class'   => 'hide receiver',
			),
			'header_border_top_width' => array(
				'id'      => 'header_border_top_width',
				'name'    => __( 'Top Border Width', '@@text-domain' ),
				'desc'    => __( 'Select a width in pixels for the top border.', '@@text-domain' ),
				'std'     => '5px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '1',
					'max'   => '10',
				),
				'class'   => 'hide receiver',
			),
			'sub_group_end_8' => array(
				'id'      => 'sub_group_end_8',
				'type'    => 'subgroup_end',
			),
			'sub_group_start_9' => array(
				'id'      => 'sub_group_start_9',
				'type'    => 'subgroup_start',
				'class'   => 'show-hide',
			),
			'header_apply_border_bottom' => array(
				'id'      => 'header_apply_border_bottom',
				'name'    => null,
				'desc'    => '<strong>' . __( 'Bottom Border', '@@text-domain' ) . '</strong>: ' . __( 'Apply bottom border to header.', '@@text-domain' ),
				'std'     => 1,
				'type'    => 'checkbox',
				'class'   => 'trigger',
			),
			'header_border_bottom_color' => array(
				'id'      => 'header_border_bottom_color',
				'name'    => __( 'Bottom Border Color', '@@text-domain' ),
				'desc'    => __( 'Select a color for the bottom border.', '@@text-domain' ),
				'std'     => '#f2f2f2',
				'type'    => 'color',
				'class'   => 'hide receiver',
			),
			'header_border_bottom_width' => array(
				'id'      => 'header_border_bottom_width',
				'name'    => __( 'Bottom Border Width', '@@text-domain' ),
				'desc'    => __( 'Select a width in pixels for the bottom border.', '@@text-domain' ),
				'std'     => '1px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '1',
					'max'   => '10',
				),
				'class'   => 'hide receiver',
			),
			'sub_group_end_9' => array(
				'id'      => 'sub_group_end_9',
				'type'    => 'subgroup_end',
			),
		),
		'header_mobile' => array(
			'header_mobile_info' => array(
				'id'      => 'header_mobile_info',
				'desc'    => __( 'These styles are applied to your header across most mobile devices and portrait tablets.', '@@text-domain' ),
				'type'    => 'info',
			),
			'header_mobile_bg_color' => array(
				'id'      => 'header_mobile_bg_color',
				'name'    => __( 'Background Color', '@@text-domain' ),
				'desc'    => __( 'Select a background color for the mobile header.', '@@text-domain' ),
				'std'     => '#333333',
				'type'    => 'color',
			),
			'header_mobile_bg_color_brightness' => array(
				'id' 		=> 'header_mobile_bg_color_brightness',
				'name' 		=> __( 'Background Color Brightness', '@@text-domain' ),
				'desc' 		=> __( 'In the previous option, did you go dark or light?', '@@text-domain' ),
				'std' 		=> 'dark',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __( 'I chose a light color in the previous option.', '@@text-domain' ),
					'dark' 	=> __( 'I chose a dark color in the previous option.', '@@text-domain' )
				),
			),
			'header_mobile_icon_color' => array(
				'id'       => 'header_mobile_icon_color',
				'name'     => __( 'Icon Color', '@@text-domain' ),
				'desc'     => __( 'Select a color for the navigational icons in the mobile header.', '@@text-domain' ),
				'std'      => '#ffffff',
				'type'     => 'color',
			),
			'header_mobile_height' => array(
				'id'       => 'header_mobile_height',
				'name'     => __( 'Height', '@@text-domain' ),
				'desc'     => __( 'Set the height of your mobile header in pixels. This number should be higher than the height of your mobile logo image at <em>Layout > Mobile Header</em>.', '@@text-domain' ),
				'std'      => '64px',
				'type'     => 'slide',
				'options'  => array(
					'min'   => '40',
					'max'   => '150',
					'step'  => '1',
					'units' => 'px',
				)
 			),
		),
		'menu' => array(
			'menu_text_shadow' => array(
				'id'      => 'menu_text_shadow',
				'name'    => null,
				'desc'    => '<strong>' . __( 'Text Shadow', '@@text-domain' ) . '</strong>: ' . __( 'Apply shadow to the text of the main menu.', '@@text-domain' ) . ' &mdash; <em>' . __( 'Note: This works better with a darker colored header.', '@@text-domain' ) . '</em>',
				'std'     => 0,
				'type'    => 'checkbox',
			),
			'menu_highlight' => array(
				'id'      => 'menu_highlight',
				'name'    => __( 'Highlight Color', '@@text-domain' ),
				'desc'    => __( 'Select a color to be used as a sublte highlight to the main menu.', '@@text-domain' ),
				'std'     => '#fec427',
				'type'    => 'color',
			),
			'menu_highlight_trans' => array(
				'id'      => 'menu_highlight_trans',
				'name'    => null,
				'desc'    => '<strong>' . __( 'Transparent Header', '@@text-domain' ) . ':</strong> ' . __( 'Also apply menu highlight color top-level menu items, when using transparent header.', '@@text-domain' ),
				'std'     => '0',
				'type'    => 'checkbox',
			),
			'menu_sub_bg_color' => array(
				'id'      => 'menu_sub_bg_color',
				'name'    => __( 'Dropdown Background Color', '@@text-domain' ),
				'desc'    => __( 'Select a background color for the main menu\'s drop down menus.', '@@text-domain' ),
				'std'     => '#ffffff',
				'type'    => 'color',
			),
			'menu_sub_bg_color_brightness' => array(
				'id'      => 'menu_sub_bg_color_brightness',
				'name'    => __( 'Dropdown Background Color Brightness', '@@text-domain' ),
				'desc'    => __( 'In the previous option, did you go dark or light?', '@@text-domain' ),
				'std'     => 'light',
				'type'    => 'radio',
				'options' => array(
					'light' => __( 'I chose a light color in the previous option.', '@@text-domain' ),
					'dark'  => __( 'I chose a dark color in the previous option.', '@@text-domain' ),
				),
			),
		),
		'menu_mobile' => array(
			'menu_mobile_bg_color' => array(
				'id'      => 'menu_mobile_bg_color',
				'name'    => __( 'Mobile Menu Background Color', '@@text-domain' ),
				'desc'    => __( 'Select a background color for the mobile menu side panel.', '@@text-domain' ),
				'std'     => '#222222',
				'type'    => 'color',
			),
			'menu_mobile_bg_color_brightness' => array(
				'id'      => 'menu_mobile_bg_color_brightness',
				'name'    => __( 'Mobile Menu Background Color Brightness', '@@text-domain' ),
				'desc'    => __( 'In the previous option, did you go dark or light?', '@@text-domain' ),
				'std'     => 'dark',
				'type'    => 'radio',
				'options' => array(
					'light' => __( 'I chose a light color in the previous option.', '@@text-domain' ),
					'dark'  => __( 'I chose a dark color in the previous option.', '@@text-domain' ),
				),
			),
		),
		'side_panel' => array(
			'side_info' => array(
				'id'      => 'side_info',
				// translators: 1: link to Menus admin screen
				'desc'    => sprintf( __( 'These options apply to the side panel that shows on desktops when you assign a menu to the "Primary Side Navigation" or "Secondary Side Navigation" locations at %s.', '@@text-domain' ), '<a href="nav-menus.php" target="_blank">' . __( 'Appearance > Menus', '@@text-domain' ) . '</a>' ),
				'type'    => 'info',
			),
			'side_bg_color' => array(
				'id'      => 'side_bg_color',
				'name'    => __( 'Side Panel Background Color', '@@text-domain' ),
				'desc'    => __( 'Select a background color for the desktop side panel.', '@@text-domain' ),
				'std'     => '#222222',
				'type'    => 'color',
			),
			'side_bg_color_brightness' => array(
				'id'      => 'side_bg_color_brightness',
				'name'    => __( 'Side Panel Background Color Brightness', '@@text-domain' ),
				'desc'    => __( 'In the previous option, did you go dark or light?', '@@text-domain' ),
				'std'     => 'dark',
				'type'    => 'radio',
				'options' => array(
					'light' => __( 'I chose a light color in the previous option.', '@@text-domain' ),
					'dark'  => __( 'I chose a dark color in the previous option.', '@@text-domain' ),
				),
			),
		),
		'footer' => array(
			'sub_group_start_10' => array(
				'id'      => 'sub_group_start_10',
				'type'    => 'subgroup_start',
				'class'   => 'show-hide-toggle',
			),
			'footer_bg_type' => array(
				'id'      => 'footer_bg_type',
				'name'    => __( 'Footer Background', '@@text-domain' ),
				// translators: 1: location of where to setup Template Sync feature on Theme Options screen
				'desc'    => __( 'Select if you\'d like to apply a custom background color to the footer.', '@@text-domain' ) . '<br><br>' . sprintf( __( 'Note: To setup a more complex designed footer, go to %s and use the "Template Sync" feature.', '@@text-domain' ), '<em>' . __( 'Layout > Footer', '@@text-domain' ) . '</em>' ),
				'std'     => 'color',
				'type'    => 'select',
				'options' => array(
					'none'    => __( 'None', '@@text-domain' ),
					'color'   => __( 'Custom color', '@@text-domain' ),
					'texture' => __( 'Custom color + texture', '@@text-domain' ),
				),
				'class'   => 'trigger',
			),
			'footer_bg_texture' => array(
				'id'      => 'footer_bg_texture',
				'name'    => __( 'Background Texture', '@@text-domain' ),
				'desc'    => __( 'Select a background texture.', '@@text-domain' ),
				'type'    => 'select',
				'select'  => 'textures',
				'class'   => 'hide receiver receiver-texture',
			),
			'footer_bg_color' => array(
				'id'      => 'footer_bg_color',
				'name'    => __( 'Background Color', '@@text-domain' ),
				'desc'    => __( 'Select a background color for the footer.', '@@text-domain' ),
				'std'     => '#ffffff',
				'type'    => 'color',
				'class'   => 'hide receiver receiver-color receiver-texture',
			),
			'footer_bg_color_brightness' => array(
				'id'      => 'footer_bg_color_brightness',
				'name'    => __( 'Background Color Brightness', '@@text-domain' ),
				'desc'    => __( 'In the previous option, did you go dark or light?', '@@text-domain' ),
				'std'     => 'light',
				'type'    => 'radio',
				'options' => array(
					'light' => __( 'I chose a light color in the previous option.', '@@text-domain' ),
					'dark'  => __( 'I chose a dark color in the previous option.', '@@text-domain' ),
				),
				'class'   => 'hide receiver receiver-color receiver-texture',
			),
			'footer_bg_color_opacity' => array(
				'id'      => 'footer_bg_color_opacity',
				'name'    => __( 'Background Color Opacity', '@@text-domain' ),
				'desc'    => __( 'Select the opacity of the background color you chose.', '@@text-domain' ),
				'std'     => '1',
				'type'    => 'select',
				'options' => array(
					'0.05' => '5%',
					'0.1'  => '10%',
					'0.15' => '15%',
					'0.2'  => '20%',
					'0.25' => '25%',
					'0.3'  => '30%',
					'0.35' => '35%',
					'0.4'  => '40%',
					'0.45' => '45%',
					'0.5'  => '50%',
					'0.55' => '55%',
					'0.6'  => '60%',
					'0.65' => '65%',
					'0.7'  => '70%',
					'0.75' => '75%',
					'0.8'  => '80%',
					'0.85' => '85%',
					'0.9'  => '90%',
					'0.95' => '95%',
					'1'    => '100%',
				),
				'class'   => 'hide receiver receiver-color receiver-texture',
			),
			'sub_group_end_10' => array(
				'id'      => 'sub_group_end_10',
				'type'    => 'subgroup_end',
			),
			'sub_group_start_11' => array(
				'id'      => 'sub_group_start_11',
				'type'    => 'subgroup_start',
				'class'   => 'show-hide',
			),
			'footer_apply_border_top' => array(
				'id'      => 'footer_apply_border_top',
				'name'    => null,
				'desc'    => '<strong>' . __( 'Top Border', '@@text-domain' ) . '</strong>: ' . __( 'Apply top border to footer.', '@@text-domain' ),
				'std'     => 1,
				'type'    => 'checkbox',
				'class'   => 'trigger',
			),
			'footer_border_top_color' => array(
				'id'      => 'footer_border_top_color',
				'name'    => __( 'Top Border Color', '@@text-domain' ),
				'desc'    => __( 'Select a color for the top border.', '@@text-domain' ),
				'std'     => '#f2f2f2',
				'type'    => 'color',
				'class'   => 'hide receiver',
			),
			'footer_border_top_width' => array(
				'id'      => 'footer_border_top_width',
				'name'    => __( 'Top Border Width', '@@text-domain' ),
				'desc'    => __( 'Select a width in pixels for the top border.', '@@text-domain' ),
				'std'     => '1px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '1',
					'max'   => '10',
				),
				'class'   => 'hide receiver',
			),
			'sub_group_end_11' => array(
				'id'      => 'sub_group_end_11',
				'type'    => 'subgroup_end',
			),
			'sub_group_start_12' => array(
				'id'      => 'sub_group_start_12',
				'type'    => 'subgroup_start',
				'class'   => 'show-hide',
			),
			'footer_apply_border_bottom' => array(
				'id'      => 'footer_apply_border_bottom',
				'name'    => null,
				'desc'    => '<strong>' . __( 'Bottom Border', '@@text-domain' ) . '</strong>: ' . __( 'Apply bottom border to footer.', '@@text-domain' ),
				'std'     => 0,
				'type'    => 'checkbox',
				'class'   => 'trigger',
			),
			'footer_border_bottom_color' => array(
				'id'      => 'footer_border_bottom_color',
				'name'    => __( 'Bottom Border Color', '@@text-domain' ),
				'desc'    => __( 'Select a color for the bottom border.', '@@text-domain' ),
				'std'     => '#181818',
				'type'    => 'color',
				'class'   => 'hide receiver',
			),
			'footer_border_bottom_width' => array(
				'id'      => 'footer_border_bottom_width',
				'name'    => __( 'Bottom Border Width', '@@text-domain' ),
				'desc'    => __( 'Select a width in pixels for the bottom border.', '@@text-domain' ),
				'std'     => '1px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '1',
					'max'   => '10',
				),
				'class'   => 'hide receiver',
			),
			'sub_group_end_12' => array(
				'id'      => 'sub_group_end_12',
				'type'    => 'subgroup_end',
			),
		),
		'typo' => array(
			'font_body' => array(
				'id'      => 'font_body',
				'name'    => __( 'Primary Font', '@@text-domain' ),
				'desc'    => __( 'This applies to most of the text on your site.', '@@text-domain' ),
				'std'     => array(
					'size'   => '16px',
					'face'   => 'google',
					'weight' => '300',
					'color'  => '',
					'google' => 'Raleway:300',
					'style'  => 'normal',
				),
				'atts'    => array( 'size', 'face', 'style', 'weight' ),
				'type'    => 'typography',
			),
			'font_header' => array(
				'id'      => 'font_header',
				'name'    => __( 'Header Font', '@@text-domain' ),
				'desc'    => __( 'This applies to all of the primary headers throughout your site (h1, h2, h3, h4, h5, h6). This would include header tags used in redundant areas like widgets and the content of posts and pages.', '@@text-domain' ),
				'std'     => array(
					'size'   => '',
					'face'   => 'google',
					'weight' => '400',
					'color'  => '',
					'google' => 'Montserrat:400',
					'style'  => 'normal',
				),
				'atts'    => array( 'face', 'style', 'weight' ),
				'type'    => 'typography',
			),
			'font_quote' => array(
				'id'      => 'font_quote',
				'name'    => __( 'Quote Font', '@@text-domain' ),
				'desc'    => __( 'This applies to quoted text in blockquote tags.', '@@text-domain' ),
				'std'     => array(
					'size'   => '',
					'face'   => 'google',
					'weight' => '400',
					'color'  => '',
					'google' => 'Libre Baskerville:400italic',
					'style'  => 'italic',
				),
				'atts'    => array( 'face', 'style', 'weight' ),
				'type'    => 'typography',
			),
			'font_quote_sp' => array(
				'id'      => 'font_quote_sp',
				'name'    => __( 'Quote Letter Spacing', '@@text-domain' ),
				'desc'    => __( 'Adjust the spacing between letters.', '@@text-domain' ),
				'std'     => '0px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '5',
					'step'  => '1',
				),
			),
			'font_meta' => array(
				'id'      => 'font_meta',
				'name'    => __( 'Meta Info Font', '@@text-domain' ),
				'desc'    => __( 'This applies to meta info like the "Posted" date below a post title, for example.', '@@text-domain' ),
				'std'     => array(
					'size'   => '',
					'face'   => 'google',
					'weight' => '400',
					'color'  => '',
					'google' => 'Montserrat:400',
					'style'  => 'uppercase',
				),
				'atts'    => array( 'face', 'style', 'weight' ),
				'type'    => 'typography',
			),
			'font_meta_sp' => array(
				'id'      => 'font_meta_sp',
				'name'    => __( 'Meta Info Letter Spacing', '@@text-domain' ),
				'desc'    => __( 'Adjust the spacing between letters.', '@@text-domain' ),
				'std'     => '0px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '5',
					'step'  => '1',
				),
			),
			'font_epic' => array(
				'id'      => 'font_epic',
				'name'    => __( 'Featured Image Title Font', '@@text-domain' ),
				'desc'    => __( 'This applies when displaying a title on top of featured images.', '@@text-domain' ),
				'std'     => array(
					'size'   => '50px',
					'face'   => 'google',
					'weight' => '700',
					'color'  => '',
					'google' => 'Montserrat:700',
					'style'  => 'uppercase',
				),
				'atts'    => array( 'face', 'style', 'weight', 'size' ),
				'sizes'   => array( '25', '26', '150' ),
				'type'    => 'typography',
			),
			'font_epic_sp' => array(
				'id'      => 'font_epic_sp',
				'name'    => __( 'Featured Image Title Letter Spacing', '@@text-domain' ),
				'desc'    => __( 'Adjust the spacing between letters.', '@@text-domain' ),
				'std'     => '3px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '5',
					'step'  => '1',
				),
			),
			'font_menu' => array(
				'id'      => 'font_menu',
				'name'    => __( 'Main Menu Font', '@@text-domain' ),
				'desc'    => __( 'This font applies to the top level items of the main menu.', '@@text-domain' ),
				'std'     => array(
					'size'   => '13px',
					'face'   => 'google',
					'weight' => '300',
					'color'  => '#555555',
					'google' => 'Raleway:300',
					'style'  => 'normal',
				),
				'atts'    => array( 'size', 'face', 'style', 'weight' ),
				'sizes'   => array( '10', '11', '12', '13', '14', '15', '16', '17', '18' ),
				'type'    => 'typography',
			),
			'font_menu_sp' => array(
				'id'      => 'font_menu_sp',
				'name'    => __( 'Main Menu Letter Spacing', '@@text-domain' ),
				'desc'    => __( 'Adjust the spacing between letters.', '@@text-domain' ),
				'std'     => '0px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '5',
					'step'  => '1',
				),
			),
			'link_color' => array(
				'id'      => 'link_color',
				'name'    => __( 'Link Color', '@@text-domain' ),
				'desc'    => __( 'Choose the color you\'d like applied to links.', '@@text-domain' ),
				'std'     => '#f9bc18',
				'type'    => 'color',
			),
			'link_hover_color' => array(
				'id'      => 'link_hover_color',
				'name'    => __( 'Link Hover Color', '@@text-domain' ),
				'desc'    => __( 'Choose the color you\'d like applied to links when they are hovered over.', '@@text-domain' ),
				'std'     => '#f9d718',
				'type'    => 'color',
			),
			'footer_link_color' => array(
				'id'      => 'footer_link_color',
				'name'    => __( 'Footer Link Color', '@@text-domain' ),
				'desc'    => __( 'Choose the color you\'d like applied to links in the footer.', '@@text-domain' ),
				'std'     => '#f9bc18',
				'type'    => 'color',
			),
			'footer_link_hover_color' => array(
				'id'      => 'footer_link_hover_color',
				'name'    => __( 'Footer Link Hover Color', '@@text-domain' ),
				'desc'    => __( 'Choose the color you\'d like applied to links in the footer when they are hovered over.', '@@text-domain' ),
				'std'     => '#f9d718',
				'type'    => 'color',
			),
		),
		'buttons' => array(
			'btn_default' => array(
				'id'      => 'btn_default',
				'name'    => __( 'Default Buttons', '@@text-domain' ),
				'desc'    => __( 'Configure what a default button looks like.', '@@text-domain' ),
				'std'     => array(
					'bg'             => '#333333',
					'bg_hover'       => '#222222',
					'border'         => '#000000',
					'text'           => '#ffffff',
					'text_hover'     => '#ffffff',
					'include_bg'     => 1,
					'include_border' => 0,
				),
				'type'    => 'button',
			),
			'btn_primary' => array(
				'id'      => 'btn_primary',
				'name'    => __( 'Primary Buttons', '@@text-domain' ),
				'desc'    => __( 'Configure what a primary button looks like.', '@@text-domain' ),
				'std'     => array(
					'bg'             => '#333333',
					'bg_hover'       => '#222222',
					'border'         => '#000000',
					'text'           => '#ffffff',
					'text_hover'     => '#ffffff',
					'include_bg'     => 1,
					'include_border' => 0,
				),
				'type'    => 'button',
			),
			'btn_border' => array(
				'id'      => 'btn_border',
				'name'    => __( 'General Button Border Width', '@@text-domain' ),
				'desc'    => __( 'If your buttons are set to include a border, select a width in pixels for those borders.', '@@text-domain' ),
				'std'     => '0px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '5',
				),
			),
			'btn_corners' => array(
				'id'      => 'btn_corners',
				'name'    => __( 'General Button Corners', '@@text-domain' ),
				'desc'    => __( 'Select the border radius of button corners.', '@@text-domain' ),
				'std'     => '0px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '50',
				),
			),
		),
		'widgets' => array(
			'sub_group_start_13' => array(
				'id'      => 'sub_group_start_13',
				'type'    => 'subgroup_start',
				'class'   => 'show-hide-toggle',
			),
			'widget_style' => array(
				'id'      => 'widget_style',
				'name'    => __( 'Widget Style', '@@text-domain' ),
				'desc'    => __( 'Select how you want to style your widgets.', '@@text-domain' ) . ' <a href="http://getbootstrap.com/components/#panels" target="_blank">' . __( 'What\'s a Bootstrap panel?', '@@text-domain' ) . '</a>',
				'std'     => 'standard',
				'type'    => 'select',
				'options' => array(
					'standard' => __( 'Standard', '@@text-domain' ),
					'panel'    => __( 'Bootstrap Panel', '@@text-domain' ),
				),
				'class'   => 'trigger',
			),
			'sub_group_start_14' => array(
				'id'      => 'sub_group_start_14',
				'type'    => 'subgroup_start',
				'class'   => 'show-hide-toggle hide receiver receiver-panel',
			),
			'widget_panel_style' => array(
				'name'    => __( 'Panel Style', '@@text-domain' ),
				'desc'    => __( 'Select a style for the Bootstrap panel. You can use a preset style, or setup custom colors.', '@@text-domain' ),
				'id'      => 'widget_panel_style',
				'std'     => 'default',
				'type'    => 'select',
				'options' => array(
					'custom'  => __( 'Custom Style', '@@text-domain' ),
					'default' => __( 'Bootstrap: Default', '@@text-domain' ),
					'primary' => __( 'Bootstrap: Primary', '@@text-domain' ),
					'info'    => __( 'Bootstrap: Info (blue)', '@@text-domain' ),
					'warning' => __( 'Bootstrap: Warning (yellow)', '@@text-domain' ),
					'danger'  => __( 'Bootstrap: Danger (red)', '@@text-domain' ),

				),
				'class'   => 'trigger',
			),
			'widget_panel_title_bg_color' => array(
				'id'      => 'widget_panel_title_bg_color',
				'name'    => __( 'Panel Title Background', '@@text-domain' ),
				'desc'    => __( 'Select two colors to create a background gradient for widget titles. For a solid color, simply select the same color twice.', '@@text-domain' ),
				'std'     => array(
					'start' => '#f5f5f5',
					'end'   => '#e8e8e8',
				),
				'type'    => 'gradient',
				'class'   => 'hide receiver receiver-custom',
			),
			'widget_panel_border_color' => array(
				'id'      => 'widget_panel_border_color',
				'name'    => __( 'Panel Border Color', '@@text-domain' ),
				'desc'    => __( 'Select a color for the border.', '@@text-domain' ),
				'std'     => '#f2f2f2',
				'type'    => 'color',
				'class'   => 'hide receiver receiver-custom',
			),
			'sub_group_end_14' => array(
				'id'      => 'sub_group_end_14',
				'type'    => 'subgroup_end',
			),
			'widget_bg_color' => array(
				'id'      => 'widget_bg_color',
				'name'    => __( 'Widget Background Color', '@@text-domain' ),
				'desc'    => __( 'Select a background color for widgets.', '@@text-domain' ),
				'std'     => '#ffffff',
				'type'    => 'color',
				'class'   => 'hide receiver receiver-standard receiver-panel',
			),
			'widget_bg_brightness' => array(
				'name'    => __( 'Widget Background Color Brightness', '@@text-domain' ),
				'desc'    => __( 'In the previous option, did you go dark or light?', '@@text-domain' ),
				'id'      => 'widget_bg_brightness',
				'std'     => 'light',
				'type'    => 'radio',
				'options' => array(
					'light' => __( 'I chose a light color in the previous option.', '@@text-domain' ),
					'dark'  => __( 'I chose a dark color in the previous option.', '@@text-domain' ),
				),
				'class'   => 'hide receiver receiver-standard receiver-panel',
			),
			'widget_bg_color_opacity' => array(
				'id'      => 'widget_bg_color_opacity',
				'name'    => __( 'Widget Background Color Opacity', '@@text-domain' ),
				'desc'    => __( 'Select the opacity of the background color you chose.', '@@text-domain' ),
				'std'     => '1',
				'type'    => 'select',
				'options' => array(
					'0.05' => '5%',
					'0.1'  => '10%',
					'0.15' => '15%',
					'0.2'  => '20%',
					'0.25' => '25%',
					'0.3'  => '30%',
					'0.35' => '35%',
					'0.4'  => '40%',
					'0.45' => '45%',
					'0.5'  => '50%',
					'0.55' => '55%',
					'0.6'  => '60%',
					'0.65' => '65%',
					'0.7'  => '70%',
					'0.75' => '75%',
					'0.8'  => '80%',
					'0.85' => '85%',
					'0.9'  => '90%',
					'0.95' => '95%',
					'1'    => '100%',
				),
				'class'   => 'hide receiver receiver-standard receiver-panel',
			),
			'widget_title_color' => array(
				'id'      => 'widget_title_color',
				'name'    => __( 'Widget Title Text Color', '@@text-domain' ),
				'desc'    => __( 'Select the text color for titles of widgets.', '@@text-domain' ),
				'std'     => '#333333',
				'type'    => 'color',
				'class'   => 'hide receiver receiver-standard receiver-panel',
			),
			'widget_title_size' => array(
				'id'      => 'widget_title_size',
				'name'    => __( 'Widget Title Text Size', '@@text-domain' ),
				'desc'    => __( 'Select the text size for titles of widgets.', '@@text-domain' ),
				'std'     => '18px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '10',
					'max'   => '30',
				),
				'class'   => 'hide receiver receiver-standard receiver-panel',
			),
			'widget_title_shadow' => array(
				'id'      => 'widget_title_shadow',
				'name'    => null,
				'desc'    => '<strong>' . __( 'Widget Title Text Shadow', '@@text-domain' ) . '</strong>: ' . __( 'Apply shadow to widget title text.', '@@text-domain' ),
				'std'     => 0,
				'type'    => 'checkbox',
				'class'   => 'hide receiver receiver-standard receiver-panel',
			),
			'sub_group_start_15' => array(
				'id'      => 'sub_group_start_15',
				'type'    => 'subgroup_start',
				'class'   => 'show-hide hide receiver receiver-standard',
			),
			'widget_apply_border' => array(
				'id'      => 'widget_apply_border',
				'name'    => null,
				'desc'    => '<strong>' . __( 'Widget Border', '@@text-domain' ) . '</strong>: ' . __( 'Apply border around widgets.', '@@text-domain' ),
				'std'     => 0,
				'type'    => 'checkbox',
				'class'   => 'trigger',
			),
			'widget_border_color' => array(
				'id'      => 'widget_border_color',
				'name'    => __( 'Border Color', '@@text-domain' ),
				'desc'    => __( 'Select a color for the border.', '@@text-domain' ),
				'std'     => '#f2f2f2',
				'type'    => 'color',
				'class'   => 'hide receiver',
			),
			'widget_border_width' => array(
				'id'      => 'widget_border_width',
				'name'    => __( 'Border Width', '@@text-domain' ),
				'desc'    => __( 'Select a width in pixels for the border.', '@@text-domain' ),
				'std'     => '1px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '1',
					'max'   => '10',
				),
				'class'   => 'hide receiver',
			),
			'sub_group_end_15' => array(
				'id'      => 'sub_group_end_15',
				'type'    => 'subgroup_end',
			),
			'sub_group_end_13' => array(
				'id'      => 'sub_group_end_13',
				'type'    => 'subgroup_end',
			),
		),
		'extras' => array(
			'highlight' => array(
				'id'      => 'highlight',
				'name'    => __( 'Highlight Color', '@@text-domain' ),
				'desc'    => __( 'Select a Highlight color to be used in a few little areas throughout your site.', '@@text-domain' ),
				'std'     => '#fec527',
				'type'    => 'color',
			),
			'box_titles' => array(
				'id'      => 'box_titles',
				'name'    => null,
				'desc'    => __( 'Display special styling to titles of info boxes and standard widgets.', '@@text-domain' ),
				'std'     => '1',
				'type'    => 'checkbox',
			),
			'thumbnail_circles' => array(
				'id'      => 'thumbnail_circles',
				'name'    => null,
				'desc'    => __( 'Display avatars and small featured images as circles', '@@text-domain' ),
				'std'     => '1',
				'type'    => 'checkbox',
			),
		),
		'css' => array(
			'custom_styles' => array(
				'id'       => 'custom_styles',
				'name'     => null,
				'desc'     => null,
				'std'      => '',
				'type'     => 'code',
				'lang'     => 'css',
			),
		),
	));

	themeblvd_add_option_tab( 'styles', __( 'Styles', '@@text-domain' ), true );

	// translators: 1: link to Theme Base admin page
	themeblvd_add_option_section( 'styles', 'presets', __( 'Preset Styles', '@@text-domain' ), __( 'For a quick starting point, click any image below to merge its preset settings into your current option selections. Then, you can continue editing individual options.', '@@text-domain' ) . ' &mdash; ' . sprintf( __( 'Looking for more theme style variations? Try a different %s.', '@@text-domain' ), '<a href="themes.php?page=jumpstart-base" target="_blank">Theme Base</a>' ), array() );

	if ( is_admin() ) {

		themeblvd_add_option_presets( jumpstart_ent_get_presets() );

	}

	themeblvd_add_option_section( 'styles', 'ent_general',       __( 'General', '@@text-domain' ),         null, $options['general'] );

	themeblvd_add_option_section( 'styles', 'ent_header_info',   __( 'Header Info', '@@text-domain' ),     null, $options['header_info'] );

	themeblvd_add_option_section( 'styles', 'ent_header',        __( 'Header', '@@text-domain' ),          null, $options['header'] );

	themeblvd_add_option_section( 'styles', 'ent_header_mobile', __( 'Mobile Header', '@@text-domain' ),   null, $options['header_mobile'] );

	themeblvd_add_option_section( 'styles', 'ent_menu',          __( 'Main Menu', '@@text-domain' ),       null, $options['menu'] );

	themeblvd_add_option_section( 'styles', 'ent_menu_mobile',   __( 'Mobile Menu', '@@text-domain' ),     null, $options['menu_mobile'] );

	themeblvd_add_option_section( 'styles', 'ent_side_panel',    __( 'Side Panel', '@@text-domain' ),      null, $options['side_panel'] );

	themeblvd_add_option_section( 'styles', 'ent_footer',        __( 'Footer', '@@text-domain' ),          null, $options['footer'] );

	themeblvd_add_option_section( 'styles', 'ent_typo',          __( 'Typography', '@@text-domain' ),      null, $options['typo'] );

	themeblvd_add_option_section( 'styles', 'ent_buttons',       __( 'Buttons', '@@text-domain' ),         null, $options['buttons'] );

	themeblvd_add_option_section( 'styles', 'ent_widgets',       __( 'Sidebar Widgets', '@@text-domain' ), null, $options['widgets'] );

	themeblvd_add_option_section( 'styles', 'ent_extras',        __( 'Extras', '@@text-domain' ),          null, $options['extras'] );

	themeblvd_add_option_section( 'styles', 'ent_css',           __( 'Custom CSS', '@@text-domain' ),      null, $options['css'] );

	themeblvd_edit_option( 'layout', 'header', 'logo', 'std', array(
		'type'         => 'image',
		'image'        => get_template_directory_uri() . '/assets/img/logo-small.png',
		'image_width'  => '165',
		'image_height' => '50',
		'image_2x'     => get_template_directory_uri() . '/assets/img/logo-small_2x.png',
	));

	themeblvd_edit_option( 'layout', 'header_trans', 'trans_logo', 'std', array(
		'type'         => 'image',
		'image'        => get_template_directory_uri() . '/assets/img/logo-small-light.png',
		'image_width'  => '165',
		'image_height' => '50',
		'image_2x'     => get_template_directory_uri() . '/assets/img/logo-small-light_2x.png',
	));

}
add_action( 'after_setup_theme', 'jumpstart_ent_options' );
