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

	/**
	 * Filters the options added by the Agent base
	 * to the theme options page.
	 *
	 * @since Jump_Start 2.1.0
	 *
	 * @param array Options added.
	 */
	$options = apply_filters( 'jumpstart_ag_options', array(
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
		'header_mobile' => array(
			'header_mobile_info' => array(
				'id'      => 'header_mobile_info',
				'desc'    => __( 'These styles are applied to your header across most mobile devices and portrait tablets.', 'jumpstart' ),
				'type'    => 'info',
			),
			'header_mobile_bg_color' => array(
				'id'       => 'header_mobile_bg_color',
				'name'     => __( 'Background Color', 'jumpstart' ),
				'desc'     => __( 'Select a background color for the mobile header.', 'jumpstart' ),
				'std'      => '#101010',
				'type'     => 'color',
			),
			'header_mobile_bg_color_brightness' => array(
				'id' 		=> 'header_mobile_bg_color_brightness',
				'name' 		=> __( 'Background Color Brightness', 'jumpstart' ),
				'desc' 		=> __( 'In the previous option, did you go dark or light?', 'jumpstart' ),
				'std' 		=> 'dark',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __( 'I chose a light color in the previous option.', 'jumpstart' ),
					'dark' 	=> __( 'I chose a dark color in the previous option.', 'jumpstart' )
				),
			),
			'header_mobile_icon_color' => array(
				'id'       => 'header_mobile_icon_color',
				'name'     => __( 'Icon Color', 'jumpstart' ),
				'desc'     => __( 'Select a color for the navigational icons in the mobile header.', 'jumpstart' ),
				'std'      => '#ffffff',
				'type'     => 'color',
			),
			'header_mobile_height' => array(
				'id'       => 'header_mobile_height',
				'name'     => __( 'Height', 'jumpstart' ),
				'desc'     => __( 'Set the height of your mobile header in pixels. This number should be higher than the height of your mobile logo image at <em>Layout > Mobile Header</em>.', 'jumpstart' ),
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
		'footer' => array(
			'footer_info' => array(
				'id'       => 'footer_info',
				// translators: 1: link to WP Menus admin page
				'desc'     => sprintf( __( 'These options apply to the footer of your website. Note that for the footer menu to show in the copyright area, you must assign a menu to the "Footer Navigation" location at %s.', 'jumpstart' ), '<a href="nav-menus.php" target="_blank">' . __( 'Appearance > Menus', 'jumpstart' ) . '</a>' ),
				'type'     => 'info',
			),
			'footer_bg_color' => array(
				'id'       => 'footer_bg_color',
				'name'     => __( 'Footer Background Color', 'jumpstart' ),
				'desc'     => __( 'Select a background color for the footer columns.', 'jumpstart' ),
				'std'      => '#222222',
				'type'     => 'color',
			),
			'footer_bg_color_brightness' => array(
				'id'       => 'footer_bg_color_brightness',
				'name'     => __( 'Footer Background Color Brightness', 'jumpstart' ),
				'desc'     => __( 'In the previous option, did you go dark or light?', 'jumpstart' ),
				'std'      => 'dark',
				'type'     => 'radio',
				'options'  => array(
					'light' => __( 'I chose a light color in the previous option.', 'jumpstart' ),
					'dark'  => __( 'I chose a dark color in the previous option.', 'jumpstart' ),
				),
			),
			'copyright_bg_color' => array(
				'id'       => 'copyright_bg_color',
				'name'     => __( 'Copyright Background Color', 'jumpstart' ),
				'desc'     => __( 'Select a background color for the copyright area below the footer columns.', 'jumpstart' ),
				'std'      => '#1b1b1b',
				'type'     => 'color',
			),
			'copyright_bg_color_brightness' => array(
				'id'       => 'copyright_bg_color_brightness',
				'name'     => __( 'Copyright Background Color Brightness', 'jumpstart' ),
				'desc'     => __( 'If you\'re using a dark background color, select to show light text, and vice versa.', 'jumpstart' ),
				'std'      => 'dark',
				'type'     => 'radio',
				'options'  => array(
					'light' => __( 'I chose a light color in the previous option.', 'jumpstart' ),
					'dark'  => __( 'I chose a dark color in the previous option.', 'jumpstart' ),
				),
			),
		),
		'side' => array(
			'side_info' => array(
				'id'       => 'side_info',
				// translators: 1: link to WP Menus admin page
				'desc'     => sprintf( __( 'These options apply to your side panel that slides out from the side of your webste on desktop browsers, and the compiled menu that slides out on mobile devices. Note that for the side panel to show in desktop browsers, you must assign a menu to the "Primary Side Navigation" location at %s.', 'jumpstart' ), '<a href="nav-menus.php" target="_blank">' . __( 'Appearance > Menus', 'jumpstart' ) . '</a>' ),
				'type'     => 'info',
			),
			'side_bg_color' => array(
				'id'       => 'side_bg_color',
				'name'     => __( 'Background Color', 'jumpstart' ),
				'desc'     => __( 'Select a background color for the side panel.', 'jumpstart' ),
				'std'      => '#1b1b1b',
				'type'     => 'color',
			),
			'side_bg_color_brightness' => array(
				'id'       => 'side_bg_color_brightness',
				'name'     => __( 'Background Color Brightness', 'jumpstart' ),
				'desc'     => __( 'In the previous option, did you go dark or light?', 'jumpstart' ),
				'std'      => 'dark',
				'type'     => 'radio',
				'options'  => array(
					'light' => __( 'I chose a light color in the previous option.', 'jumpstart' ),
					'dark'  => __( 'I chose a dark color in the previous option.', 'jumpstart' ),
				),
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
		'typo' => array(
			'font_body' => array(
				'id'       => 'font_body',
				'name'     => __( 'Primary Font', 'jumpstart' ),
				'desc'     => __( 'This applies to most of the text on your site.', 'jumpstart' ),
				'std'      => array(
					'size'   => '16px',
					'face'   => 'google',
					'weight' => '300',
					'color'  => '',
					'google' => 'Lato:300',
					'style'  => 'normal',
				),
				'atts'     => array( 'size', 'face', 'style', 'weight' ),
				'type'     => 'typography',
			),
			'font_header' => array(
				'id'       => 'font_header',
				'name'     => __( 'Header Font', 'jumpstart' ),
				'desc'     => __( 'This applies to all of the primary headers throughout your site (h1, h2, h3, h4, h5, h6). This would include header tags used in redundant areas like widgets and the content of posts and pages.', 'jumpstart' ),
				'std'      => array(
					'size'   => '',
					'face'   => 'google',
					'weight' => '600',
					'color'  => '',
					'google' => 'Hind:600',
					'style'  => 'normal',
				),
				'atts'     => array( 'face', 'style', 'weight' ),
				'type'     => 'typography',
			),
			'font_header_sm' => array(
				'id'       => 'font_header_sm',
				'name'     => __( 'Small Header Font', 'denali' ),
				'desc'     => __( 'This applies to smaller sub headers throughout your website, like widget titles, for example.', 'denali' ),
				'std'      => array(
					'size'   => '',
					'face'   => 'google',
					'weight' => '700',
					'color'  => '',
					'google' => 'Lato:700',
					'style'  => 'uppercase',
				),
				'atts'     => array( 'face', 'style', 'weight' ),
				'type'     => 'typography',
			),
			'font_header_sm_sp' => array(
				'id'       => 'font_header_sm_sp',
				'name'     => __( 'Small Header Letter Spacing', 'denali' ),
				'desc'     => __( 'Adjust the spacing between letters.', 'denali' ),
				'std'      => '1px',
				'type'     => 'slide',
				'options'  => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '5',
					'step'  => '1',
				),
			),
			'font_quote' => array(
				'id'       => 'font_quote',
				'name'     => __( 'Quote Font', 'jumpstart' ),
				'desc'     => __( 'This applies to quoted text in blockquote tags.', 'jumpstart' ),
				'std'      => array(
					'size'   => '',
					'face'   => 'google',
					'weight' => '400',
					'color'  => '',
					'google' => 'Libre Baskerville:400italic',
					'style'  => 'italic',
				),
				'atts'     => array( 'face', 'style', 'weight' ),
				'type'     => 'typography',
			),
			'font_quote_sp' => array(
				'id'       => 'font_quote_sp',
				'name'     => __( 'Quote Letter Spacing', 'jumpstart' ),
				'desc'     => __( 'Adjust the spacing between letters.', 'jumpstart' ),
				'std'      => '0px',
				'type'     => 'slide',
				'options'  => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '5',
					'step'  => '1',
				),
			),
			'font_meta' => array(
				'id'       => 'font_meta',
				'name'     => __( 'Meta Info Font', 'jumpstart' ),
				'desc'     => __( 'This applies to meta info like the "Posted" date below a post title, for example.', 'jumpstart' ),
				'std'      => array(
					'size'   => '',
					'face'   => 'google',
					'weight' => '700',
					'color'  => '',
					'google' => 'Lato:700',
					'style'  => 'uppercase',
				),
				'atts'     => array( 'face', 'style', 'weight' ),
				'type'     => 'typography',
			),
			'font_meta_sp' => array(
				'id'       => 'font_meta_sp',
				'name'     => __( 'Meta Info Letter Spacing', 'jumpstart' ),
				'desc'     => __( 'Adjust the spacing between letters.', 'jumpstart' ),
				'std'      => '0px',
				'type'     => 'slide',
				'options'  => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '5',
					'step'  => '1',
				),
			),
			'font_epic' => array(
				'id'       => 'font_epic',
				'name'     => __( 'Featured Image Title Font', 'jumpstart' ),
				'desc'     => __( 'This applies when displaying a title on top of featured images.', 'jumpstart' ),
				'std'      => array(
					'size'   => '50px',
					'face'   => 'google',
					'weight' => '700',
					'color'  => '',
					'google' => 'Montserrat:700',
					'style'  => 'uppercase',
				),
				'atts'     => array( 'face', 'style', 'weight', 'size' ),
				'sizes'    => array( '25', '26', '150' ),
				'type'     => 'typography',
			),
			'font_epic_sp' => array(
				'id'       => 'font_epic_sp',
				'name'     => __( 'Featured Image Title Letter Spacing', 'jumpstart' ),
				'desc'     => __( 'Adjust the spacing between letters.', 'jumpstart' ),
				'std'      => '3px',
				'type'     => 'slide',
				'options'  => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '5',
					'step'  => '1',
				),
			),
			'font_menu' => array(
				'id'       => 'font_menu',
				'name'     => __( 'Main Menu Font', 'jumpstart' ),
				'desc'     => __( 'This font applies to the top level items of the main menu.', 'jumpstart' ),
				'std'      => array(
					'size'   => '11px',
					'face'   => 'google',
					'weight' => '700',
					'color'  => '#ffffff',
					'google' => 'Lato:700',
					'style'  => 'uppercase',
				),
				'atts'     => array( 'size', 'face', 'style', 'weight', 'color' ),
				'type'     => 'typography',
				'sizes'    => array( '10', '11', '12', '13', '14', '15', '16', '17', '18' ),
			),
			'font_menu_sp' => array(
				'id'       => 'font_menu_sp',
				'name'     => __( 'Main Menu Letter Spacing', 'jumpstart' ),
				'desc'     => __( 'Adjust the spacing between letters.', 'jumpstart' ),
				'std'      => '1px',
				'type'     => 'slide',
				'options'  => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '5',
					'step'  => '1',
				),
			),
			'link_color' => array(
				'id'       => 'link_color',
				'name'     => __( 'Link Color', 'jumpstart' ),
				'desc'     => __( 'Choose the color you\'d like applied to links.', 'jumpstart' ),
				'std'      => '#2ea3f2',
				'type'     => 'color',
			),
			'link_hover_color' => array(
				'id'       => 'link_hover_color',
				'name'     => __( 'Link Hover Color', 'jumpstart' ),
				'desc'     => __( 'Choose the color you\'d like applied to links when they are hovered over.', 'jumpstart' ),
				'std'      => '#337ab7',
				'type'     => 'color',
			),
			'footer_link_color' => array(
				'id'       => 'footer_link_color',
				'name'     => __( 'Footer Link Color', 'jumpstart' ),
				'desc'     => __( 'Choose the color you\'d like applied to links in the footer.', 'jumpstart' ),
				'std'      => '#2ea3f2',
				'type'     => 'color',
			),
			'footer_link_hover_color' => array(
				'id'       => 'footer_link_hover_color',
				'name'     => __( 'Footer Link Hover Color', 'jumpstart' ),
				'desc'     => __( 'Choose the color you\'d like applied to links in the footer when they are hovered over.', 'jumpstart' ),
				'std'      => '#337ab7',
				'type'     => 'color',
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

	themeblvd_remove_option( 'layout', 'header', 'social_media' );

	themeblvd_remove_option( 'layout', 'header', 'social_media_style' );

	themeblvd_remove_option( 'layout', 'header_trans', 'trans_social_media_style' );

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

	// Add Layout > Contact.
	themeblvd_add_option_section( 'layout', 'contact', __( 'Contact', 'jumpstart' ), null, array(
		'social_media' => array(
			'id'       => 'social_media',
			'name'     => __( 'Contact Buttons', 'jumpstart' ),
			'desc'     => __( 'Configure the contact and social media buttons you\'d like to use.', 'jumpstart' ),
			'type'     => 'social_media',
			'std'      => array(
				'item_1' => array(
					'icon'   => 'facebook',
					'url'    => 'http://facebook.com/jasonbobich',
					'label'  => 'Facebook',
					'target' => '_blank',
				),
				'item_2' => array(
					'icon'   => 'google',
					'url'    => 'https://plus.google.com/116531311472104544767/posts',
					'label'  => 'Google+',
					'target' => '_blank',
				),
				'item_3' => array(
					'icon'   => 'twitter',
					'url'    => 'http://twitter.com/jasonbobich',
					'label'  => 'Twitter',
					'target' => '_blank',
				),
				'item_4' => array(
					'icon'   => 'rss',
					'url'    => get_feed_link(),
					'label'  => 'RSS Feed',
					'target' => '_blank',
				),
			),
		),
		'social_panel' => array(
			'id'       => 'social_panel',
			'name'     => null,
			'desc'     => __( 'Display contact buttons in side menu panel.', 'jumpstart' ),
			'std'      => '1',
			'type'     => 'checkbox',
		),
		'social_footer' => array(
			'id'       => 'social_footer',
			'name'     => null,
			'desc'     => __( 'Display contact buttons in footer.', 'jumpstart' ),
			'std'      => '1',
			'type'     => 'checkbox',
		),
	));

	// Change default sidebar layout to full width for single posts and pages.
	themeblvd_edit_option( 'layout', 'sidebar_layouts', 'single_sidebar_layout', 'std', 'full_width' );

	themeblvd_edit_option( 'layout', 'sidebar_layouts', 'page_sidebar_layout', 'std', 'full_width' );

}
add_action( 'after_setup_theme', 'jumpstart_ag_options' );
