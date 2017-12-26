<?php
/**
 * Theme Base: Agent, Options
 *
 * @author    Jason Bobich <info@themeblvd.com>
 * @copyright 2009-2017 Theme Blvd
 * @package   Jump_Start
 * @since     Jump_Start 2.1.0
 */

/**
 * Add theme options to framework.
 *
 * @since Jump_Start 2.1.0
 */
function jumpstart_ag_options() {

	$options = array(
		'general' => array(
			'btn_color' => array(
				'id'      => 'btn_color',
				'name'    => __( 'Primary Buttons', 'jumpstart' ),
				'desc'    => __( 'Select a background color for primary buttons throughour your website.', 'jumpstart' ),
				'std'     => '#1b1b1b',
				'type'    => 'color',
			),
			'highlight' => array(
				'id'      => 'highlight',
				'name'    => __( 'Default Highlight', 'jumpstart' ),
				'desc'    => __( 'Select a "highlight" color to be used throughour your website, like with button and thumbnail hovers, for example.', 'jumpstart' ),
				'std'     => '#2ea3f2',
				'type'    => 'color',
			),
		),
		'header' => array(
			'header_info' => array(
				'id'      => 'header_info',
				// translators: 1: link to WP Menus admin page
				'desc'    => sprintf( __( 'These options apply to the header of your website. Note that for the main menu to show, you must assign a menu to the "Primary Navigation" location, and for there to be a side menu to toggle open on desktops, you need to assign a menu to the "Primary Side Navigation" location. This can all be done at %s.', 'jumpstart' ), '<a href="nav-menus.php" target="_blank">' . __( 'Appearance > Menus', 'jumpstart' ) . '</a>' ),
				'type'    => 'info',
			),
			'header_bg_color' => array(
				'id'      => 'header_bg_color',
				'name'    => __( 'Background Color', 'jumpstart' ),
				'desc'    => __( 'Select a background color for the header.', 'jumpstart' ),
				'std'     => '#101010',
				'type'    => 'color',
			),
			'header_bg_color_brightness' => array(
				'id'      => 'header_bg_color_brightness',
				'name'    => __( 'Background Color Brightness', 'jumpstart' ),
				'desc'    => __( 'In the previous option, did you go dark or light?', 'jumpstart' ),
				'std'     => 'dark',
				'type'    => 'radio',
				'options' => array(
					'light' => __( 'I chose a light color in the previous option.', 'jumpstart' ),
					'dark'  => __( 'I chose a dark color in the previous option.', 'jumpstart' ),
				),
			),
			'menu_placement' => array(
				'id'      => 'menu_placement',
				'name'    => __( 'Menu Placement', 'jumpstart' ),
				'desc'    => __( 'Select where you\'d like the main menu placed within the header.', 'jumpstart' ),
				'std'     => 'center',
				'type'    => 'radio',
				'options' => array(
					'center' => __( 'Menu is centered within the header.', 'jumpstart' ),
					'far'    => __( 'Menu is opposite the logo.', 'jumpstart' ),
				),
			),
			'menu_drop_bg_color' => array(
				'id'      => 'menu_drop_bg_color',
				'name'    => __( 'Main Menu Dropdown Background Color', 'jumpstart' ),
				'desc'    => __( 'Select a background color for dropdown submenus of the main menu.', 'jumpstart' ),
				'std'     => '#202020',
				'type'    => 'color',
			),
			'menu_drop_bg_color_brightness' => array(
				'id'      => 'menu_drop_bg_color_brightness',
				'name'    => __( 'Background Color Brightness', 'jumpstart' ),
				'desc'    => __( 'In the previous option, did you go dark or light?', 'jumpstart' ),
				'std'     => 'dark',
				'type'    => 'radio',
				'options' => array(
					'light' => __( 'I chose a light color in the previous option.', 'jumpstart' ),
					'dark'  => __( 'I chose a dark color in the previous option.', 'jumpstart' ),
				),
			),
			'header_stretch' => array(
				'id'       => 'header_stretch',
				'name'     => null,
				'desc'     => __( 'Stretch header to full width.', 'jumpstart' ),
				'std'      => '1',
				'type'     => 'checkbox',
			),
		),
		'header_trans' => array(
			'header_trans_info' => array(
				'id'       => 'header_trans_info',
				'desc'     => __( 'These options apply to pages where you\'ve setup a transparent header.', 'jumpstart' ),
				'type'     => 'info',
			),
			'header_trans_bg_color' => array(
				'id'       => 'header_trans_bg_color',
				'name'     => __( 'Background Color', 'jumpstart' ),
				'desc'     => __( 'Select a background color for the header.', 'jumpstart' ),
				'std'      => '#000000',
				'type'     => 'color',
			),
			'header_trans_bg_color_opacity' => array(
				'id'       => 'header_trans_bg_color_opacity',
				'name'     => __( 'Background Color Opacity', 'jumpstart' ),
				'desc'     => __( 'Select the opacity of the background color.', 'jumpstart' ),
				'std'      => '0',
				'type'     => 'select',
				'options'  => array(
					'0'    => '0%',
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
			),
			'header_trans_bg_color_brightness' => array(
				'id'       => 'header_trans_bg_color_brightness',
				'name'     => __( 'Background Color Brightness', 'jumpstart' ),
				'desc'     => __( 'In the previous option, did you go dark or light?', 'jumpstart' ),
				'std'      => 'dark',
				'type'     => 'radio',
				'options'  => array(
					'light' => __( 'I chose a light color in the previous option.', 'jumpstart' ),
					'dark'  => __( 'I chose a dark color in the previous option.', 'jumpstart' ),
				),
			),
			'header_trans_hide_border' => array(
				'id'       => 'header_trans_hide_border',
				'name'     => null,
				'desc'     => __( 'Hide bounding borders around transparent header.', 'jumpstart' ),
				'std'      => '0',
				'type'     => 'checkbox',
			),
		),
		'widgets' => array(
			'widget_info' => array(
				'id'       => 'widget_info',
				'desc'     => __( 'These options apply to widgets in your sidebar.', 'jumpstart' ),
				'type'     => 'info',
			),
			'sub_group_start_1' => array(
				'id'       => 'sub_group_start_1',
				'type'     => 'subgroup_start',
				'class'    => 'show-hide',
			),
			'widget_bg' => array(
				'id'       => 'widget_bg',
				'name'     => null,
				'desc'     => __( 'Apply background color to widgets.', 'jumpstart' ),
				'std'      => '1',
				'type'     => 'checkbox',
				'class'    => 'trigger',
			),
			'widget_bg_color' => array(
				'id'       => 'widget_bg_color',
				'name'     => __( 'Background Color', 'jumpstart' ),
				'desc'     => __( 'Select a background color for widgets.', 'jumpstart' ),
				'std'      => '#f8f8f8',
				'type'     => 'color',
				'class'    => 'receiver',
			),
			'widget_bg_color_brightness' => array(
				'id'       => 'widget_bg_color_brightness',
				'name'     => __( 'Background Color Brightness', 'jumpstart' ),
				'desc'     => __( 'In the previous option, did you go dark or light?', 'jumpstart' ),
				'std'      => 'light',
				'type'     => 'radio',
				'options'  => array(
					'light' => __( 'I chose a light color in the previous option.', 'jumpstart' ),
					'dark'  => __( 'I chose a dark color in the previous option.', 'jumpstart' ),
				),
				'class'    => 'receiver',
			),
			'sub_group_end_1' => array(
				'id'       => 'sub_group_end_1',
				'type'     => 'subgroup_end',
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
		'side_bg_color'            => '#1b1b1b',
		'side_bg_color_brightness' => 'dark',
	) );

	/*
	 * Add standard footer options, shared across all
	 * theme bases.
	 */
	$options['footer'] = jumpstart_get_shared_options( 'footer', array(
		'footer_apply_border_top'       => '0',
		'footer_bg_type'                => 'color',
		'footer_bg_color_brightness'    => 'dark',
		'footer_bg_color'               => '#222222',
		'footer_bg_color_opacity'       => '1',
		'copyright_apply_bg'            => '1',
		'copyright_bg_color'            => '#1b1b1b',
		'copyright_bg_color_brightness' => 'dark',
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
		'google' => 'Lato:300',
		'style'  => 'normal',
	);

	$font_defaults['font_header'] = array(
		'size'   => '',
		'face'   => 'google',
		'weight' => '600',
		'color'  => '',
		'google' => 'Hind:600',
		'style'  => 'normal',
	);

	$font_defaults['font_header_sm'] = array(
		'size'   => '',
		'face'   => 'google',
		'weight' => '700',
		'color'  => '',
		'google' => 'Lato:700',
		'style'  => 'uppercase',
	);

	$font_defaults['font_header_sm_sp'] = '1px';

	$font_defaults['font_meta'] = array(
		'size'   => '',
		'face'   => 'google',
		'weight' => '700',
		'color'  => '',
		'google' => 'Lato:700',
		'style'  => 'uppercase',
	);

	$font_defaults['font_meta_sp'] = '0px';

	$font_defaults['font_menu'] = array(
		'size'   => '11px',
		'face'   => 'google',
		'weight' => '700',
		'color'  => '#ffffff',
		'google' => 'Lato:700',
		'style'  => 'uppercase',
	);

	$font_defaults['font_menu_sp'] = '1px';

	$options['typo'] = jumpstart_get_shared_options( 'typography', $font_defaults );

	/*
	 * Add color selection to menu font.
	 *
	 * Agent is currently only base that allows menu
	 * font color selection; so it needs to be added
	 * onto the shared options retrieved.
	 */
	$options['type']['font_menu']['atts'][] = 'color';

	/**
	 * Filters the options added by the Agent base
	 * to the theme options page.
	 *
	 * @since Jump_Start 2.1.0
	 *
	 * @param array Options added.
	 */
	$options = apply_filters( 'jumpstart_ag_options', $options );

	/*
	 * Add all options set up above from the $options
	 * array to a new section called "Styles."
	 */
	themeblvd_add_option_tab( 'styles', __( 'Styles', 'jumpstart' ), true );

	// translators: 1: link to Theme Base admin page
	themeblvd_add_option_section( 'styles', 'presets', __( 'Preset Styles', 'jumpstart' ), __( 'For a quick starting point, click any image below to merge its preset settings into your current option selections. Then, you can continue editing individual options.', 'jumpstart' ) . ' &mdash; ' . sprintf( __( 'Looking for more theme style variations? Try a different %s.', 'jumpstart' ), '<a href="themes.php?page=jumpstart-base" target="_blank">Theme Base</a>' ), array() );

	if ( is_admin() ) {

		themeblvd_add_option_presets( jumpstart_ag_get_presets() );

	}

	themeblvd_add_option_section( 'styles', 'ag_general',       __( 'General', 'jumpstart' ),            null, $options['general'] );

	themeblvd_add_option_section( 'styles', 'ag_header',        __( 'Header', 'jumpstart' ),             null, $options['header'] );

	themeblvd_add_option_section( 'styles', 'ag_header_trans',  __( 'Transparent Header', 'jumpstart' ), null, $options['header_trans'] );

	themeblvd_add_option_section( 'styles', 'ag_header_mobile', __( 'Mobile Header', 'jumpstart' ),      null, $options['header_mobile'] );

	themeblvd_add_option_section( 'styles', 'ag_footer',        __( 'Footer', 'jumpstart' ),             null, $options['footer'] );

	themeblvd_add_option_section( 'styles', 'ag_side',          __( 'Side Panel', 'jumpstart' ),         null, $options['side'] );

	themeblvd_add_option_section( 'styles', 'ag_widgets',       __( 'Widgets', 'jumpstart' ),            null, $options['widgets'] );

	themeblvd_add_option_section( 'styles', 'ag_typo',          __( 'Typography', 'jumpstart' ),         null, $options['typo'] );

	themeblvd_add_option_section( 'styles', 'ag_css',           __( 'Custom CSS', 'jumpstart' ),         null, $options['css'] );

	// Remove options.
	themeblvd_remove_option( 'layout', 'header', 'header_text' );

	themeblvd_remove_option( 'layout', 'header', 'social_media_style' );

	themeblvd_remove_option( 'layout', 'header_trans', 'trans_social_media_style' );

	themeblvd_remove_option( 'layout', 'contact', 'social_header' );

	// Set default logos.
	themeblvd_edit_option( 'layout', 'header', 'logo', 'std', array(
		'type'         => 'image',
		'image'        => get_template_directory_uri() . '/assets/img/logo-smaller-light.png',
		'image_width'  => '85',
		'image_height' => '25',
		'image_2x'     => get_template_directory_uri() . '/assets/img/logo-smaller-light_2x.png',
	));

	themeblvd_edit_option( 'layout', 'header_trans', 'trans_logo', 'std', array(
		'type'         => 'default',
		'image'        => '',
		'image_width'  => '',
		'image_height' => '0',
		'image_2x'     => '',
	));

	// Make narrow full-width content and popout images on by default.
	themeblvd_edit_option( 'content', 'general', 'fw_narrow', 'std', '1' );

	themeblvd_edit_option( 'content', 'general', 'img_popout', 'std', '1' );

	// Change default sidebar layout to full width for single posts and pages.
	themeblvd_edit_option( 'layout', 'sidebar_layouts', 'single_sidebar_layout', 'std', 'full_width' );

	themeblvd_edit_option( 'layout', 'sidebar_layouts', 'page_sidebar_layout', 'std', 'full_width' );

}
add_action( 'after_setup_theme', 'jumpstart_ag_options' );
