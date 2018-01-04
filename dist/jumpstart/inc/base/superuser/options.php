<?php
/**
 * Theme Base: Super User, Options
 *
 * @author    Jason Bobich <info@themeblvd.com>
 * @copyright 2009-2017 Theme Blvd
 * @package   Jump_Start
 * @since     Jump_Start 2.0.0
 */

/**
 * Add theme options to framework.
 *
 * @since Jump_Start 2.0.0
 */
function jumpstart_su_options() {

	// Background support
	add_theme_support( 'custom-background', array(
		'default-color' => 'f8f8f8',
		'default-image' => '',
	) );

	$bg_types = array();

	if ( function_exists( 'themeblvd_get_bg_types' ) ) {

		$bg_types = themeblvd_get_bg_types( 'basic' );

	}

	$options = array(
		'general' => array(
			'sub_group_start_1' => array(
				'id'        => 'sub_group_start_1',
				'type'      => 'subgroup_start',
				'class'     => 'show-hide-toggle',
			),
			'layout_style' => array(
				'name'      => __( 'Site Layout Style', 'jumpstart' ),
				'desc'      => __( 'Select whether you\'d like the layout of the theme to be boxed or not.', 'jumpstart' ),
				'id'        => 'layout_style',
				'std'       => 'stretch',
				'type'      => 'select',
				'options'   => array(
					'stretch'   => __( 'Stretch', 'jumpstart' ),
					'boxed'     => __( 'Boxed', 'jumpstart' ),
				),
				'class'     => 'trigger',
			),
			'layout_shadow_size' => array(
				'id'        => 'layout_shadow_size',
				'name'      => __( 'Layout Shadow Size', 'jumpstart' ),
				'desc'      => __( 'Select the size of the shadow around the boxed layout. Set to 0px for no shadow.', 'jumpstart' ),
				'std'       => '5px',
				'type'      => 'slide',
				'options'   => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '20',
				),
				'class'     => 'receiver receiver-boxed',
			),
			'layout_shadow_opacity' => array(
				'id'        => 'layout_shadow_opacity',
				'name'      => __( 'Layout Shadow Strength', 'jumpstart' ),
				// translators: 1: link to Backgrounds admin screen
				'desc'      => sprintf( __( 'Select the opacity of the shadow for the boxed layout. The darker %s, the closer to 100%% you want to go.', 'jumpstart' ), '<a href="' . esc_url( admin_url( 'customize.php?autofocus[control]=background_image' ) ) . '" target="_blank">' . __( 'your background', 'jumpstart' ) . '</a>' ),
				'std'       => '0.3',
				'type'      => 'select',
				'options'   => array(
					'0.05'  => '5%',
					'0.1'   => '10%',
					'0.15'  => '15%',
					'0.2'   => '20%',
					'0.25'  => '25%',
					'0.3'   => '30%',
					'0.35'  => '35%',
					'0.4'   => '40%',
					'0.45'  => '45%',
					'0.5'   => '50%',
					'0.55'  => '55%',
					'0.6'   => '60%',
					'0.65'  => '65%',
					'0.7'   => '70%',
					'0.75'  => '75%',
					'0.8'   => '80%',
					'0.85'  => '85%',
					'0.9'   => '90%',
					'0.95'  => '95%',
					'1'     => '100%',
				),
				'class'     => 'receiver  receiver-boxed',
			),
			'layout_border_width' => array(
				'id'        => 'layout_border_width',
				'name'      => __( 'Layout Border Width', 'jumpstart' ),
				'desc'      => __( 'Select a width in pixels for the boxed layout. Set to 0px for no border.', 'jumpstart' ),
				'std'       => '0px',
				'type'      => 'slide',
				'options'   => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '20',
				),
				'class'     => 'receiver receiver-boxed',
			),
			'layout_border_color' => array(
				'id'        => 'layout_border_color',
				'name'      => __( 'Layout Border Color', 'jumpstart' ),
				'desc'      => __( 'Select a color for the border around the boxed layout.', 'jumpstart' ),
				'std'       => '#cccccc',
				'type'      => 'color',
				'class'     => 'receiver receiver-boxed',
			),
			'sub_group_start_2' => array(
				'id'        => 'sub_group_start_2',
				'type'      => 'subgroup_start',
				'class'     => 'show-hide receiver receiver-stretch',
			),
			'apply_content_border' => array(
				'id'        => 'apply_content_border',
				'name'      => null,
				'desc'      => '<strong>' . __( 'Content Border', 'jumpstart' ) . '</strong>: ' . __( 'Apply border around content areas.', 'jumpstart' ),
				'std'       => 0,
				'type'      => 'checkbox',
				'class'     => 'trigger',
			),
			'content_border_color' => array(
				'id'        => 'content_border_color',
				'name'      => __( 'Content Border Color', 'jumpstart' ),
				'desc'      => __( 'Select a color for the border around content areas.', 'jumpstart' ),
				'std'       => '#f2f2f2',
				'type'      => 'color',
				'class'     => 'hide receiver',
			),
			'content_border_width' => array(
				'id'        => 'content_border_width',
				'name'      => __( 'Bottom Border Width', 'jumpstart' ),
				'desc'      => __( 'Select a width in pixels for the border around content areas.', 'jumpstart' ),
				'std'       => '1px',
				'type'      => 'slide',
				'options'   => array(
					'units' => 'px',
					'min'   => '1',
					'max'   => '10',
				),
				'class'     => 'hide receiver',
			),
			'sub_group_end_2' => array(
				'id'        => 'sub_group_end_2',
				'type'      => 'subgroup_end',
			),
			'sub_group_end_1' => array(
				'id'        => 'sub_group_end_1',
				'type'      => 'subgroup_end',
			),
		),
		'header_info' => array(
			'sub_group_start_3' => array(
				'id'        => 'sub_group_start_3',
				'type'      => 'subgroup_start',
				'class'     => 'show-hide-toggle',
			),
			'header_info' => array(
				'name'      => __( 'Header Info Display', 'jumpstart' ),
				// translators: 1: location of where to setup Header features on Theme Options screen
				'desc'      => sprintf( __( 'Select where you\'d like the header info to display, configured at %s.', 'jumpstart' ), '<em>' . __( 'Layout > Header', 'jumpstart' ) . '</em>' ),
				'id'        => 'header_info',
				'std'       => 'header_top',
				'type'      => 'select',
				'options'   => array(
					'header_top'    => __( 'Top bar above header', 'jumpstart' ),
					'header_addon'  => __( 'Within header', 'jumpstart' ),
				),
				'class'     => 'trigger',
			),
			'top_bg_color' => array(
				'id'        => 'top_bg_color',
				'name'      => __( 'Top Bar Background Color', 'jumpstart' ),
				'desc'      => __( 'Select a background color for the bar that runs across the top of the header.', 'jumpstart' ),
				'std'       => '#ffffff',
				'type'      => 'color',
				'class'     => 'receiver receiver-header_top',
			),
			'top_bg_color_opacity' => array(
				'id'        => 'top_bg_color_opacity',
				'name'      => __( 'Top Bar Background Color Opacity', 'jumpstart' ),
				'desc'      => __( 'Select the opacity of the above background color. Selecting "100%" means that the background color is not transparent, at all.', 'jumpstart' ),
				'std'       => '1',
				'type'      => 'select',
				'options'   => array(
					'0.05'  => '5%',
					'0.1'   => '10%',
					'0.15'  => '15%',
					'0.2'   => '20%',
					'0.25'  => '25%',
					'0.3'   => '30%',
					'0.35'  => '35%',
					'0.4'   => '40%',
					'0.45'  => '45%',
					'0.5'   => '50%',
					'0.55'  => '55%',
					'0.6'   => '60%',
					'0.65'  => '65%',
					'0.7'   => '70%',
					'0.75'  => '75%',
					'0.8'   => '80%',
					'0.85'  => '85%',
					'0.9'   => '90%',
					'0.95'  => '95%',
					'1'     => '100%',
				),
				'class'     => 'receiver receiver-header_top',
			),
			'top_text_color' => array(
				'id'        => 'top_text_color',
				'name'      => __( 'Top Bar Text Color', 'jumpstart' ),
				'desc'      => __( 'If you\'re using a dark background color, select to show light text, and vice versa.', 'jumpstart' ),
				'std'       => 'dark',
				'type'      => 'select',
				'options'   => array(
					'dark'  => __( 'Dark Text', 'jumpstart' ),
					'light' => __( 'Light Text', 'jumpstart' ),
				),
				'class'     => 'receiver receiver-header_top',
			),
			'sub_group_start_4' => array(
				'id'        => 'sub_group_start_4',
				'type'      => 'subgroup_start',
				'class'     => 'show-hide receiver receiver-header_top',
			),
			'top_apply_border_bottom' => array(
				'id'        => 'top_apply_border_bottom',
				'name'      => null,
				'desc'      => '<strong>' . __( 'Bottom Border', 'jumpstart' ) . '</strong>: ' . __( 'Apply bottom border to the top bar of the header.', 'jumpstart' ),
				'std'       => 0,
				'type'      => 'checkbox',
				'class'     => 'trigger',
			),
			'top_border_bottom_color' => array(
				'id'        => 'top_border_bottom_color',
				'name'      => __( 'Top Bar Bottom Border Color', 'jumpstart' ),
				'desc'      => __( 'Select a color for the bottom border.', 'jumpstart' ),
				'std'       => '#f2f2f2',
				'type'      => 'color',
				'class'     => 'hide receiver',
			),
			'top_border_bottom_width' => array(
				'id'        => 'top_border_bottom_width',
				'name'      => __( 'Bottom Border Width', 'jumpstart' ),
				'desc'      => __( 'Select a width in pixels for the bottom border.', 'jumpstart' ),
				'std'       => '1px',
				'type'      => 'slide',
				'options'   => array(
					'units' => 'px',
					'min'   => '1',
					'max'   => '10',
				),
				'class'     => 'hide receiver',
			),
			'sub_group_end_4' => array(
				'id'        => 'sub_group_end_4',
				'type'      => 'subgroup_end',
			),
			'top_mini' => array(
				'id'        => 'top_mini',
				'name'      => null,
				'desc'      => '<strong>' . __( 'Mini Display', 'jumpstart' ) . '</strong>: ' . __( 'Display top bar a bit smaller and more condensed.', 'jumpstart' ),
				'std'       => 0,
				'type'      => 'checkbox',
				'class'     => 'receiver receiver-header_top',
			),
			'sub_group_end_3' => array(
				'id'        => 'sub_group_end_3',
				'type'      => 'subgroup_end',
			),
		),
		'header' => array(
			'sub_group_start_5' => array(
				'id'        => 'sub_group_start_5',
				'type'      => 'subgroup_start',
				'class'     => 'show-hide-toggle',
			),
			'header_bg_type' => array(
				'id'        => 'header_bg_type',
				'name'      => __( 'Apply Header Background', 'jumpstart' ),
				'desc'      => __( 'Select the type of background you\'d like applied to your website header.', 'jumpstart' ),
				'std'       => 'none',
				'type'      => 'select',
				'options'   => $bg_types,
				'class'     => 'trigger',
			),
			'header_text_color' => array(
				'id'        => 'header_text_color',
				'name'      => __( 'Text Color', 'jumpstart' ),
				'desc'      => __( 'If you\'re using a dark background, select to show light text, and vice versa.', 'jumpstart' ),
				'std'       => 'dark',
				'type'      => 'select',
				'options'   => array(
					'dark'  => __( 'Dark Text', 'jumpstart' ),
					'light' => __( 'Light Text', 'jumpstart' ),
				),
			),
			'header_bg_color' => array(
				'id'        => 'header_bg_color',
				'name'      => __( 'Background Color', 'jumpstart' ),
				'desc'      => __( 'Select a background color.', 'jumpstart' ),
				'std'       => '#f8f8f8',
				'type'      => 'color',
				'class'     => 'hide receiver receiver-color receiver-texture receiver-image',
			),
			'header_bg_color_opacity' => array(
				'id'        => 'header_bg_color_opacity',
				'name'      => __( 'Background Color Opacity', 'jumpstart' ),
				'desc'      => __( 'Select the opacity of the background color. Selecting "100%" means that the background color is not transparent, at all.', 'jumpstart' ),
				'std'       => '1',
				'type'      => 'select',
				'options'   => array(
					'0.05'  => '5%',
					'0.1'   => '10%',
					'0.15'  => '15%',
					'0.2'   => '20%',
					'0.25'  => '25%',
					'0.3'   => '30%',
					'0.35'  => '35%',
					'0.4'   => '40%',
					'0.45'  => '45%',
					'0.5'   => '50%',
					'0.55'  => '55%',
					'0.6'   => '60%',
					'0.65'  => '65%',
					'0.7'   => '70%',
					'0.75'  => '75%',
					'0.8'   => '80%',
					'0.85'  => '85%',
					'0.9'   => '90%',
					'0.95'  => '95%',
					'1'     => '100%',
				),
				'class'     => 'hide receiver receiver-color receiver-texture',
			),
			'header_bg_texture' => array(
				'id'        => 'header_bg_texture',
				'name'      => __( 'Background Texture', 'jumpstart' ),
				'desc'      => __( 'Select a background texture.', 'jumpstart' ),
				'type'      => 'select',
				'select'    => 'textures',
				'class'     => 'hide receiver receiver-texture',
			),
			'header_bg_image' => array(
				'id'        => 'header_bg_image',
				'name'      => __( 'Background Image', 'jumpstart' ),
				'desc'      => __( 'Select a background image.', 'jumpstart' ),
				'type'      => 'background',
				'class'     => 'hide receiver receiver-image',
			),
			'sub_group_end_5' => array(
				'id'        => 'sub_group_end_5',
				'type'      => 'subgroup_end',
			),
			'sub_group_start_9' => array(
				'id'        => 'sub_group_start_9',
				'type'      => 'subgroup_start',
				'class'     => 'show-hide',
			),
			'header_apply_border_top' => array(
				'id'        => 'header_apply_border_top',
				'name'      => null,
				'desc'      => '<strong>' . __( 'Top Border', 'jumpstart' ) . '</strong>: ' . __( 'Apply top border to header.', 'jumpstart' ),
				'std'       => 0,
				'type'      => 'checkbox',
				'class'     => 'trigger',
			),
			'header_border_top_color' => array(
				'id'        => 'header_border_top_color',
				'name'      => __( 'Top Border Color', 'jumpstart' ),
				'desc'      => __( 'Select a color for the top border.', 'jumpstart' ),
				'std'       => '#f2f2f2',
				'type'      => 'color',
				'class'     => 'hide receiver',
			),
			'header_border_top_width' => array(
				'id'        => 'header_border_top_width',
				'name'      => __( 'Top Border Width', 'jumpstart' ),
				'desc'      => __( 'Select a width in pixels for the top border.', 'jumpstart' ),
				'std'       => '5px',
				'type'      => 'slide',
				'options'   => array(
					'units' => 'px',
					'min'   => '1',
					'max'   => '10',
				),
				'class'     => 'hide receiver',
			),
			'sub_group_end_9' => array(
				'id'        => 'sub_group_end_9',
				'type'      => 'subgroup_end',
			),
			'sub_group_start_10' => array(
				'id'        => 'sub_group_start_10',
				'type'      => 'subgroup_start',
				'class'     => 'show-hide',
			),
			'header_apply_border_bottom' => array(
				'id'        => 'header_apply_border_bottom',
				'name'      => null,
				'desc'      => '<strong>' . __( 'Bottom Border', 'jumpstart' ) . '</strong>: ' . __( 'Apply bottom border to header.', 'jumpstart' ),
				'std'       => 0,
				'type'      => 'checkbox',
				'class'     => 'trigger',
			),
			'header_border_bottom_color' => array(
				'id'        => 'header_border_bottom_color',
				'name'      => __( 'Bottom Border Color', 'jumpstart' ),
				'desc'      => __( 'Select a color for the bottom border.', 'jumpstart' ),
				'std'       => '#f2f2f2',
				'type'      => 'color',
				'class'     => 'hide receiver',
			),
			'header_border_bottom_width' => array(
				'id'        => 'header_border_bottom_width',
				'name'      => __( 'Bottom Border Width', 'jumpstart' ),
				'desc'      => __( 'Select a width in pixels for the bottom border.', 'jumpstart' ),
				'std'       => '5px',
				'type'      => 'slide',
				'options'   => array(
					'units' => 'px',
					'min'   => '1',
					'max'   => '10',
				),
				'class'     => 'hide receiver',
			),
			'sub_group_end_10' => array(
				'id'        => 'sub_group_end_10',
				'type'      => 'subgroup_end',
			),
			'sub_group_start_11' => array(
				'id'        => 'sub_group_start_11',
				'type'      => 'subgroup_start',
				'class'     => 'show-hide',
			),
			'header_apply_padding' => array(
				'id'        => 'header_apply_padding',
				'name'      => null,
				'desc'      => '<strong>' . __( 'Padding', 'jumpstart' ) . ':</strong> ' . __( 'Apply custom padding around header content.', 'jumpstart' ),
				'std'       => 0,
				'type'      => 'checkbox',
				'class'     => 'trigger',
			),
			'header_padding_top' => array(
				'id'        => 'header_padding_top',
				'name'      => __( 'Top Padding', 'jumpstart' ),
				'desc'      => __( 'Set the padding on the top of the header.', 'jumpstart' ),
				'std'       => '20px',
				'type'      => 'slide',
				'options'   => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '600',
				),
				'class'     => 'hide receiver',
			),
			'header_padding_right' => array(
				'id'        => 'header_padding_right',
				'name'      => __( 'Right Padding', 'jumpstart' ),
				'desc'      => __( 'Set the padding on the right of the header.', 'jumpstart' ),
				'std'       => '20px',
				'type'      => 'slide',
				'options'   => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '600',
				),
				'class'     => 'hide receiver',
			),
			'header_padding_bottom' => array(
				'id'        => 'header_padding_bottom',
				'name'      => __( 'Bottom Padding', 'jumpstart' ),
				'desc'      => __( 'Set the padding on the bottom of the header.', 'jumpstart' ),
				'std'       => '20px',
				'type'      => 'slide',
				'options'   => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '600',
				),
				'class'     => 'hide receiver',
			),
			'header_padding_left' => array(
				'id'        => 'header_padding_left',
				'name'      => __( 'Left Padding', 'jumpstart' ),
				'desc'      => __( 'Set the padding on the left of the header.', 'jumpstart' ),
				'std'       => '20px',
				'type'      => 'slide',
				'options'   => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '600',
				),
				'class'     => 'hide receiver',
			),
			'sub_group_end_11' => array(
				'id'        => 'sub_group_end_11',
				'type'      => 'subgroup_end',
			),
			'logo_center' => array(
				'id'        => 'logo_center',
				'name'      => null,
				'desc'      => '<strong>' . __( 'Center Logo', 'jumpstart' ) . '</strong>: ' . __( 'Center align the logo within the header.', 'jumpstart' ),
				'std'       => 0,
				'type'      => 'checkbox',
			),
		),
		'menu' => array(
			'sub_group_start_12' => array(
				'id'        => 'sub_group_start_12',
				'type'      => 'subgroup_start',
				'class'     => 'show-hide-toggle',
			),
			'menu_bg_type' => array(
				'id'        => 'menu_bg_type',
				'name'      => __( 'Main Menu Background', 'jumpstart' ),
				'desc'      => __( 'Select if you\'d like to apply a custom background and how you want to control it.', 'jumpstart' ),
				'std'       => 'color',
				'type'      => 'select',
				'options'   => array(
					'color'             => __( 'Custom color', 'jumpstart' ),
					'glassy'            => __( 'Custom color + glassy overlay', 'jumpstart' ),
					'textured'          => __( 'Custom color + noisy texture', 'jumpstart' ),
					'gradient'          => __( 'Custom gradient', 'jumpstart' ),
				),
				'class'     => 'trigger',
			),
			'menu_bg_color' => array(
				'id'        => 'menu_bg_color',
				'name'      => __( 'Background Color', 'jumpstart' ),
				'desc'      => __( 'Select a background color for the main menu.', 'jumpstart' ),
				'std'       => '#333333',
				'type'      => 'color',
				'class'     => 'hide receiver receiver-color receiver-glassy receiver-textured',
			),
			'menu_bg_gradient' => array(
				'id'        => 'menu_bg_gradient',
				'name'      => __( 'Background Gradient', 'jumpstart' ),
				'desc'      => __( 'Select two colors to create a gradient with for the main menu.', 'jumpstart' ),
				'std'       => array(
					'start' => '#3c3c3c',
					'end' => '#2b2b2b',
				),
				'type'      => 'gradient',
				'class'     => 'hide receiver receiver-gradient receiver-gradient_glassy',
			),
			'menu_bg_color_opacity' => array(
				'id'        => 'menu_bg_color_opacity',
				'name'      => __( 'Background Color Opacity', 'jumpstart' ),
				'desc'      => __( 'Select the opacity of the background color(s). Selecting "100%" means that the background color is not transparent, at all.', 'jumpstart' ),
				'std'       => '1',
				'type'      => 'select',
				'options'   => array(
					'0.05'  => '5%',
					'0.1'   => '10%',
					'0.15'  => '15%',
					'0.2'   => '20%',
					'0.25'  => '25%',
					'0.3'   => '30%',
					'0.35'  => '35%',
					'0.4'   => '40%',
					'0.45'  => '45%',
					'0.5'   => '50%',
					'0.55'  => '55%',
					'0.6'   => '60%',
					'0.65'  => '65%',
					'0.7'   => '70%',
					'0.75'  => '75%',
					'0.8'   => '80%',
					'0.85'  => '85%',
					'0.9'   => '90%',
					'0.95'  => '95%',
					'1'     => '100%',
				),
				'class'     => 'hide receiver receiver-color receiver-glassy receiver-textured receiver-gradient',
			),
			'menu_bg_color_brightness' => array(
				'id'        => 'menu_bg_color_brightness',
				'name'      => __( 'Background Color Brightness', 'jumpstart' ),
				'desc'      => __( 'In the previous option, did you go dark or light?', 'jumpstart' ),
				'std'       => 'dark',
				'type'      => 'radio',
				'options'   => array(
					'light' => __( 'I chose a light color in the previous option.', 'jumpstart' ),
					'dark'  => __( 'I chose a dark color in the previous option.', 'jumpstart' ),
				),
				'class'     => 'hide receiver receiver-color receiver-glassy receiver-textured receiver-gradient receiver-gradient_glassy',
			),
			'sub_group_end_12' => array(
				'id'        => 'sub_group_end_12',
				'type'      => 'subgroup_end',
			),
			'menu_hover_bg_color' => array(
				'id'        => 'menu_hover_bg_color',
				'name'      => __( 'Button Hover Background Color', 'jumpstart' ),
				'desc'      => __( 'Select a background color for when buttons of the main are hovered on.', 'jumpstart' ),
				'std'       => '#000000',
				'type'      => 'color',
			),
			'menu_hover_bg_color_opacity' => array(
				'id'        => 'menu_hover_bg_color_opacity',
				'name'      => __( 'Button Hover Background Color Opacity', 'jumpstart' ),
				'desc'      => __( 'Select the opacity of the color you selected in the previous option.', 'jumpstart' ),
				'std'       => '0.3',
				'type'      => 'select',
				'options'   => array(
					'0.05'  => '5%',
					'0.1'   => '10%',
					'0.15'  => '15%',
					'0.2'   => '20%',
					'0.25'  => '25%',
					'0.3'   => '30%',
					'0.35'  => '35%',
					'0.4'   => '40%',
					'0.45'  => '45%',
					'0.5'   => '50%',
					'0.55'  => '55%',
					'0.6'   => '60%',
					'0.65'  => '65%',
					'0.7'   => '70%',
					'0.75'  => '75%',
					'0.8'   => '80%',
					'0.85'  => '85%',
					'0.9'   => '90%',
					'0.95'  => '95%',
					'1'     => '100%',
				),
			),
			'menu_hover_bg_color_brightness' => array(
				'id'        => 'menu_hover_bg_color_brightness',
				'name'      => __( 'Button Hover Background Color Brightness', 'jumpstart' ),
				'desc'      => __( 'In the previous option, did you go dark or light?', 'jumpstart' ),
				'std'       => 'dark',
				'type'      => 'radio',
				'options'   => array(
					'light' => __( 'I chose a light color in the previous option.', 'jumpstart' ),
					'dark'  => __( 'I chose a dark color in the previous option.', 'jumpstart' ),
				),
			),
			'menu_sub_bg_color' => array(
				'id'        => 'menu_sub_bg_color',
				'name'      => __( 'Dropdown Background Color', 'jumpstart' ),
				'desc'      => __( 'Select a background color for the main menu\'s drop down menus.', 'jumpstart' ),
				'std'       => '#ffffff',
				'type'      => 'color',
			),
			'menu_sub_bg_color_brightness' => array(
				'id'        => 'menu_sub_bg_color_brightness',
				'name'      => __( 'Dropdown Background Color Brightness', 'jumpstart' ),
				'desc'      => __( 'In the previous option, did you go dark or light?', 'jumpstart' ),
				'std'       => 'light',
				'type'      => 'radio',
				'options'   => array(
					'light' => __( 'I chose a light color in the previous option.', 'jumpstart' ),
					'dark'  => __( 'I chose a dark color in the previous option.', 'jumpstart' ),
				),
			),
			'sub_group_start_13' => array(
				'id'        => 'sub_group_start_13',
				'type'      => 'subgroup_start',
				'class'     => 'show-hide',
			),
			'menu_apply_border_top' => array(
				'id'        => 'menu_apply_border_top',
				'name'      => null,
				'desc'      => '<strong>' . __( 'Top Border', 'jumpstart' ) . '</strong>: ' . __( 'Apply top border to menu.', 'jumpstart' ),
				'std'       => 0,
				'type'      => 'checkbox',
				'class'     => 'trigger',
			),
			'menu_border_top_color' => array(
				'id'        => 'menu_border_top_color',
				'name'      => __( 'Top Border Color', 'jumpstart' ),
				'desc'      => __( 'Select a color for the top border.', 'jumpstart' ),
				'std'       => '#181818',
				'type'      => 'color',
				'class'     => 'hide receiver',
			),
			'menu_border_top_width' => array(
				'id'        => 'menu_border_top_width',
				'name'      => __( 'Top Border Width', 'jumpstart' ),
				'desc'      => __( 'Select a width in pixels for the top border.', 'jumpstart' ),
				'std'       => '1px',
				'type'      => 'slide',
				'options'   => array(
					'units' => 'px',
					'min'   => '1',
					'max'   => '10',
				),
				'class'     => 'hide receiver',
			),
			'sub_group_end_13' => array(
				'id'        => 'sub_group_end_13',
				'type'      => 'subgroup_end',
			),
			'sub_group_start_14' => array(
				'id'        => 'sub_group_start_14',
				'type'      => 'subgroup_start',
				'class'     => 'show-hide',
			),
			'menu_apply_border_bottom' => array(
				'id'        => 'menu_apply_border_bottom',
				'name'      => null,
				'desc'      => '<strong>' . __( 'Bottom Border', 'jumpstart' ) . '</strong>: ' . __( 'Apply bottom border to menu.', 'jumpstart' ),
				'std'       => 0,
				'type'      => 'checkbox',
				'class'     => 'trigger',
			),
			'menu_border_bottom_color' => array(
				'id'        => 'menu_border_bottom_color',
				'name'      => __( 'Bottom Border Color', 'jumpstart' ),
				'desc'      => __( 'Select a color for the bottom border.', 'jumpstart' ),
				'std'       => '#181818',
				'type'      => 'color',
				'class'     => 'hide receiver',
			),
			'menu_border_bottom_width' => array(
				'id'        => 'menu_border_bottom_width',
				'name'      => __( 'Bottom Border Width', 'jumpstart' ),
				'desc'      => __( 'Select a width in pixels for the bottom border.', 'jumpstart' ),
				'std'       => '1px',
				'type'      => 'slide',
				'options'   => array(
					'units' => 'px',
					'min'   => '1',
					'max'   => '10',
				),
				'class'     => 'hide receiver',
			),
			'sub_group_end_14' => array(
				'id'        => 'sub_group_end_14',
				'type'      => 'subgroup_end',
			),
			'menu_text_shadow' => array(
				'id'        => 'menu_text_shadow',
				'name'      => null,
				'desc'      => '<strong>' . __( 'Text Shadow', 'jumpstart' ) . '</strong>: ' . __( 'Apply shadow to the text of the main menu.', 'jumpstart' ),
				'std'       => 0,
				'type'      => 'checkbox',
			),
			'sub_group_start_15' => array(
				'id'        => 'sub_group_start_15',
				'type'      => 'subgroup_start',
				'class'     => 'show-hide',
			),
			'menu_divider' => array(
				'id'        => 'menu_divider',
				'name'      => null,
				'desc'      => '<strong>' . __( 'Dividers', 'jumpstart' ) . '</strong>: ' . __( 'Add dividers between buttons of main menu.', 'jumpstart' ),
				'std'       => 0,
				'type'      => 'checkbox',
				'class'     => 'trigger',
			),
			'menu_divider_color' => array(
				'id'        => 'menu_divider_color',
				'name'      => __( 'Divider Color', 'jumpstart' ),
				'desc'      => __( 'Select a color for the menu dividers.', 'jumpstart' ),
				'std'       => '#000000',
				'type'      => 'color',
				'class'     => 'hide receiver',
			),
			'sub_group_end_15' => array(
				'id'        => 'sub_group_end_15',
				'type'      => 'subgroup_end',
			),
			'menu_center' => array(
				'id'        => 'menu_center',
				'name'      => null,
				'desc'      => '<strong>' . __( 'Center', 'jumpstart' ) . '</strong>: ' . __( 'Center align the buttons of the main menu.', 'jumpstart' ),
				'std'       => 0,
				'type'      => 'checkbox',
			),
			'menu_search' => array(
				'id'        => 'menu_search',
				'name'      => null,
				'desc'      => '<strong>' . __( 'Search Bar', 'jumpstart' ) . '</strong>: ' . __( 'Add popup with search bar to main menu.', 'jumpstart' ),
				'std'       => 0,
				'type'      => 'checkbox',
			),
		),
		'buttons' => array(
			'btn_default' => array(
				'id'        => 'btn_default',
				'name'      => __( 'Default Buttons', 'jumpstart' ),
				'desc'      => __( 'Configure what a default button looks like.', 'jumpstart' ),
				'std'       => array(
					'bg'             => '#333333',
					'bg_hover'       => '#222222',
					'border'         => '#000000',
					'text'           => '#ffffff',
					'text_hover'     => '#ffffff',
					'include_bg'     => 1,
					'include_border' => 0,
				),
				'type'      => 'button',
			),
			'btn_primary' => array(
				'id'        => 'btn_primary',
				'name'      => __( 'Primary Buttons', 'jumpstart' ),
				'desc'      => __( 'Configure what a primary button looks like.', 'jumpstart' ),
				'std'       => array(
					'bg'             => '#333333',
					'bg_hover'       => '#222222',
					'border'         => '#000000',
					'text'           => '#ffffff',
					'text_hover'     => '#ffffff',
					'include_bg'     => 1,
					'include_border' => 0,
				),
				'type'      => 'button',
			),
			'btn_border' => array(
				'id'        => 'btn_border',
				'name'      => __( 'General Button Border Width', 'jumpstart' ),
				'desc'      => __( 'If your buttons are set to include a border, select a width in pixels for those borders.', 'jumpstart' ),
				'std'       => '0px',
				'type'      => 'slide',
				'options'   => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '5',
				),
			),
			'btn_corners' => array(
				'id'        => 'btn_corners',
				'name'      => __( 'General Button Corners', 'jumpstart' ),
				'desc'      => __( 'Set the border radius of button corners. Setting to 0px will mean buttons corners are square.', 'jumpstart' ),
				'std'       => '0px',
				'type'      => 'slide',
				'options'   => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '50',
				),
			),
		),
		'widgets' => array(
			'sub_group_start_19' => array(
				'id'        => 'sub_group_start_19',
				'type'      => 'subgroup_start',
				'class'     => 'show-hide-toggle',
			),
			'widget_style' => array(
				'id'        => 'widget_style',
				'name'      => __( 'Widget Style', 'jumpstart' ),
				'desc'      => __( 'Select how you want to style your widgets.', 'jumpstart' ) . ' <a href="http://getbootstrap.com/components/#panels" target="_blank">' . __( 'What\'s a Bootstrap panel?', 'jumpstart' ) . '</a>',
				'std'       => 'standard',
				'type'      => 'select',
				'options'   => array(
					'standard' => __( 'Standard', 'jumpstart' ),
					'panel'    => __( 'Bootstrap Panel', 'jumpstart' ),
				),
				'class'     => 'trigger',
			),
			'sub_group_start_20' => array(
				'id'        => 'sub_group_start_20',
				'type'      => 'subgroup_start',
				'class'     => 'show-hide-toggle hide receiver receiver-panel',
			),
			'widget_panel_style' => array(
				'name'      => __( 'Panel Style', 'jumpstart' ),
				'desc'      => __( 'Select a style for the Bootstrap panel. You can use a preset style, or setup custom colors.', 'jumpstart' ),
				'id'        => 'widget_panel_style',
				'std'       => 'default',
				'type'      => 'select',
				'options'   => array(
					'custom'    => __( 'Custom Style', 'jumpstart' ),
					'default'   => __( 'Bootstrap: Default', 'jumpstart' ),
					'primary'   => __( 'Bootstrap: Primary', 'jumpstart' ),
					'info'      => __( 'Bootstrap: Info (blue)', 'jumpstart' ),
					'warning'   => __( 'Bootstrap: Warning (yellow)', 'jumpstart' ),
					'danger'    => __( 'Bootstrap: Danger (red)', 'jumpstart' ),

				),
				'class'     => 'trigger',
			),
			'widget_panel_title_bg_color' => array(
				'id'        => 'widget_panel_title_bg_color',
				'name'      => __( 'Panel Title Background', 'jumpstart' ),
				'desc'      => __( 'Select two colors to create a background gradient for widget titles. For a solid color, simply select the same color twice.', 'jumpstart' ),
				'std'       => array(
					'start' => '#f5f5f5',
					'end'   => '#e8e8e8',
				),
				'type'      => 'gradient',
				'class'     => 'hide receiver receiver-custom',
			),
			'widget_panel_border_color' => array(
				'id'        => 'widget_panel_border_color',
				'name'      => __( 'Panel Border Color', 'jumpstart' ),
				'desc'      => __( 'Select a color for the border.', 'jumpstart' ),
				'std'       => '#f2f2f2',
				'type'      => 'color',
				'class'     => 'hide receiver receiver-custom',
			),
			'sub_group_end_20' => array(
				'id'        => 'sub_group_end_20',
				'type'      => 'subgroup_end',
			),
			'widget_bg_color' => array(
				'id'        => 'widget_bg_color',
				'name'      => __( 'Widget Background Color', 'jumpstart' ),
				'desc'      => __( 'Select a background color for widgets.', 'jumpstart' ),
				'std'       => '#ffffff',
				'type'      => 'color',
				'class'     => 'hide receiver receiver-standard receiver-panel',
			),
			'widget_bg_brightness' => array(
				'name'      => __( 'Widget Background Color Brightness', 'jumpstart' ),
				'desc'      => __( 'In the previous option, did you go dark or light?', 'jumpstart' ),
				'id'        => 'widget_bg_brightness',
				'std'       => 'light',
				'type'      => 'radio',
				'options'   => array(
					'light' => __( 'I chose a light color in the previous option.', 'jumpstart' ),
					'dark'  => __( 'I chose a dark color in the previous option.', 'jumpstart' ),
				),
				'class'     => 'hide receiver receiver-standard receiver-panel',
			),
			'widget_bg_color_opacity' => array(
				'id'        => 'widget_bg_color_opacity',
				'name'      => __( 'Widget Background Color Opacity', 'jumpstart' ),
				'desc'      => __( 'Select the opacity of the background color you chose.', 'jumpstart' ),
				'std'       => '1',
				'type'      => 'select',
				'options'   => array(
					'0.05'  => '5%',
					'0.1'   => '10%',
					'0.15'  => '15%',
					'0.2'   => '20%',
					'0.25'  => '25%',
					'0.3'   => '30%',
					'0.35'  => '35%',
					'0.4'   => '40%',
					'0.45'  => '45%',
					'0.5'   => '50%',
					'0.55'  => '55%',
					'0.6'   => '60%',
					'0.65'  => '65%',
					'0.7'   => '70%',
					'0.75'  => '75%',
					'0.8'   => '80%',
					'0.85'  => '85%',
					'0.9'   => '90%',
					'0.95'  => '95%',
					'1'     => '100%',
				),
				'class'     => 'hide receiver receiver-standard receiver-panel',
			),
			'widget_title_color' => array(
				'id'        => 'widget_title_color',
				'name'      => __( 'Widget Title Text Color', 'jumpstart' ),
				'desc'      => __( 'Select the text color for titles of widgets.', 'jumpstart' ),
				'std'       => '#333333',
				'type'      => 'color',
				'class'     => 'hide receiver receiver-standard receiver-panel',
			),
			'widget_title_size' => array(
				'id'        => 'widget_title_size',
				'name'      => __( 'Widget Title Text Size', 'jumpstart' ),
				'desc'      => __( 'Select the text size for titles of widgets.', 'jumpstart' ),
				'std'       => '18px',
				'type'      => 'slide',
				'options'   => array(
					'units' => 'px',
					'min'   => '10',
					'max'   => '30',
				),
				'class'     => 'hide receiver receiver-standard receiver-panel',
			),
			'widget_title_shadow' => array(
				'id'        => 'widget_title_shadow',
				'name'      => null,
				'desc'      => '<strong>' . __( 'Widget Title Text Shadow', 'jumpstart' ) . '</strong>: ' . __( 'Apply shadow to widget title text.', 'jumpstart' ),
				'std'       => 0,
				'type'      => 'checkbox',
				'class'     => 'hide receiver receiver-standard receiver-panel',
			),
			'sub_group_start_21' => array(
				'id'        => 'sub_group_start_21',
				'type'      => 'subgroup_start',
				'class'     => 'show-hide hide receiver receiver-standard',
			),
			'widget_apply_border' => array(
				'id'        => 'widget_apply_border',
				'name'      => null,
				'desc'      => '<strong>' . __( 'Widget Border', 'jumpstart' ) . '</strong>: ' . __( 'Apply border around widgets.', 'jumpstart' ),
				'std'       => 0,
				'type'      => 'checkbox',
				'class'     => 'trigger',
			),
			'widget_border_color' => array(
				'id'        => 'widget_border_color',
				'name'      => __( 'Border Color', 'jumpstart' ),
				'desc'      => __( 'Select a color for the border.', 'jumpstart' ),
				'std'       => '#f2f2f2',
				'type'      => 'color',
				'class'     => 'hide receiver',
			),
			'widget_border_width' => array(
				'id'        => 'widget_border_width',
				'name'      => __( 'Border Width', 'jumpstart' ),
				'desc'      => __( 'Select a width in pixels for the border.', 'jumpstart' ),
				'std'       => '1px',
				'type'      => 'slide',
				'options'   => array(
					'units' => 'px',
					'min'   => '1',
					'max'   => '10',
				),
				'class'     => 'hide receiver',
			),
			'sub_group_end_21' => array(
				'id'        => 'sub_group_end_21',
				'type'      => 'subgroup_end',
			),
			'sub_group_end_17' => array(
				'id'        => 'sub_group_end_17',
				'type'      => 'subgroup_end',
			),
		),
		'extras' => array(
			'highlight' => array(
				'id'        => 'highlight',
				'name'      => __( 'Highlight Color', 'jumpstart' ),
				'desc'      => __( 'Select a Highlight color to be used in a few little areas throughout your site.', 'jumpstart' ),
				'std'       => '#fec527',
				'type'      => 'color',
			),
			'box_titles' => array(
				'id'        => 'box_titles',
				'name'      => null,
				'desc'      => __( 'Display special styling to titles of info boxes and standard widgets.', 'jumpstart' ),
				'std'       => '1',
				'type'      => 'checkbox',
			),
			'thumbnail_circles' => array(
				'id'        => 'thumbnail_circles',
				'name'      => null,
				'desc'      => __( 'Display avatars and small featured images as circles', 'jumpstart' ),
				'std'       => '1',
				'type'      => 'checkbox',
			),
		),
		'css' => array(
			'custom_styles' => array(
				'id'        => 'custom_styles',
				'name'      => null,
				'desc'      => null,
				'std'       => '',
				'type'      => 'code',
				'lang'      => 'css',
			),
		),
	);

	/*
	 * Add standard mobile header options, shared across
	 * all theme bases.
	 */
	$options['header_mobile'] = jumpstart_get_shared_options( 'mobile-header' );

	/*
	 * Add standard side panel options, shared across
	 * all theme bases.
	 */
	$options['side'] = jumpstart_get_shared_options( 'side-panel', array(
		'side_bg_color'            => '#222222',
		'side_bg_color_brightness' => 'dark',
	) );

	/*
	 * Add standard footer options, shared across all
	 * theme bases.
	 */
	$options['footer'] = jumpstart_get_shared_options( 'footer', array(
		'footer_apply_border_top'    => '0',
		'footer_bg_type'             => 'color',
		'footer_bg_color_brightness' => 'light',
		'footer_bg_color'            => '#ffffff',
		'footer_bg_color_opacity'    => '1',
		'copyright_apply_bg'         => '0',
	) );

	/*
	 * Add standard typography options, with custom default
	 * values for this theme base.
	 */
	$font_defaults = array();

	$font_defaults['font_body'] = array(
		'size'   => '16px',
		'face'   => 'google',
		'weight' => '300',
		'color'  => '',
		'google' => 'Raleway:300',
		'style'  => 'normal',
	);

	$font_defaults['font_header'] = array(
		'size'   => '',
		'face'   => 'google',
		'weight' => '400',
		'color'  => '',
		'google' => 'Montserrat:400',
		'style'  => 'normal',
	);

	$font_defaults['font_header_sm'] = array(
		'size'   => '',
		'face'   => 'google',
		'weight' => '400',
		'color'  => '',
		'google' => 'Montserrat:400',
		'style'  => 'normal',
	);

	$font_defaults['font_header_sm_sp'] = '0px';

	$font_defaults['font_meta'] = array(
		'size'   => '',
		'face'   => 'google',
		'weight' => '400',
		'color'  => '',
		'google' => 'Montserrat:400',
		'style'  => 'uppercase',
	);

	$font_defaults['font_meta_sp'] = '0px';

	$font_defaults['font_menu'] = array(
		'size'   => '13px',
		'face'   => 'google',
		'weight' => '300',
		'color'  => '',
		'google' => 'Raleway:300',
		'style'  => 'normal',
	);

	$font_defaults['font_menu_sp'] = '0px';

	$options['typo'] = jumpstart_get_shared_options( 'typography', $font_defaults );

	/**
	 * Filters the options added by the Super User base
	 * to the theme options page.
	 *
	 * @since Jump_Start 2.0.0
	 *
	 * @param array Options added.
	 */
	$options = apply_filters( 'jumpstart_su_options', $options );

	/*
	 * Add all options set up above from the $options
	 * array to a new section called "Styles."
	 */
	themeblvd_add_option_tab( 'styles', __( 'Styles', 'jumpstart' ), true );

	// translators: 1: link to Theme Base admin page
	themeblvd_add_option_section( 'styles', 'presets', __( 'Preset Styles', 'jumpstart' ), __( 'For a quick starting point, click any image below to merge its preset settings into your current option selections. Then, you can continue editing individual options.', 'jumpstart' ) . ' &mdash; ' . sprintf( __( 'Looking for more theme style variations? Try a different %s.', 'jumpstart' ), '<a href="themes.php?page=jumpstart-base" target="_blank">Theme Base</a>' ), array() );

	if ( is_admin() ) {

		themeblvd_add_option_presets( jumpstart_su_get_presets() );

	}

	themeblvd_add_option_section( 'styles', 'su_general',       __( 'General', 'jumpstart' ),         null, $options['general'] );

	themeblvd_add_option_section( 'styles', 'su_header_info',   __( 'Header Info', 'jumpstart' ),     null, $options['header_info'] );

	themeblvd_add_option_section( 'styles', 'su_header',        __( 'Header', 'jumpstart' ),          null, $options['header'] );

	themeblvd_add_option_section( 'styles', 'su_header_mobile', __( 'Mobile Header', 'jumpstart' ),   null, $options['header_mobile'] );

	themeblvd_add_option_section( 'styles', 'su_menu',          __( 'Main Menu', 'jumpstart' ),       null, $options['menu'] );

	themeblvd_add_option_section( 'styles', 'su_side_panel',    __( 'Side Panel', 'jumpstart' ),      null, $options['side'] );

	themeblvd_add_option_section( 'styles', 'su_footer',        __( 'Footer', 'jumpstart' ),          null, $options['footer'] );

	themeblvd_add_option_section( 'styles', 'su_typo',          __( 'Typography', 'jumpstart' ),      null, $options['typo'] );

	themeblvd_add_option_section( 'styles', 'su_buttons',       __( 'Buttons', 'jumpstart' ),         null, $options['buttons'] );

	themeblvd_add_option_section( 'styles', 'su_widgets',       __( 'Sidebar Widgets', 'jumpstart' ), null, $options['widgets'] );

	themeblvd_add_option_section( 'styles', 'su_extras',        __( 'Extras', 'jumpstart' ),          null, $options['extras'] );

	themeblvd_add_option_section( 'styles', 'su_css',           __( 'Custom CSS', 'jumpstart' ),      null, $options['css'] );

}
add_action( 'after_setup_theme', 'jumpstart_su_options' );
