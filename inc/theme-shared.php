<?php
/**
 * Shared Theme Base Functions
 *
 * Several of the theme bases use similar
 * option sets and styling for different
 * sections of the website.
 *
 * This file holds some of the re-useable
 * pieces used across multiple theme bases.
 *
 * @author    Jason Bobich <info@themeblvd.com>
 * @copyright 2009-2017 Theme Blvd
 * @package   Jump_Start
 * @since     Jump_Start 2.7.0
 */

/**
 * Get re-useable option sets for theme bases.
 *
 * As theme bases have grown, many options have become
 * fairly consistent across multiple theme bases. So
 * this function can help to eliminate a lot of duplicate
 * code that has crept up.
 *
 * Note: For the `typography` set options, the theme base
 * must pass in the $defaults.
 *
 * @since Jump_Start 2.7.0
 *
 * @param  string $set      Type of option set, like `typography`, `buttons`, `widgets`, etc.
 * @param  array  $defaults Any custom default values.
 * @return array  $options  Options set.
 */
function jumpstart_get_shared_options( $set, $defaults = array() ) {

	$options = array();

	switch ( $set ) {

		/*
		 * Set up sticky header options.
		 */
		case 'sticky-header':
			$options['sticky_info'] = array(
				'id'   => 'sticky_info',
				'desc' => __( 'These styles are applied to your sticky header, which displays when the standard website header is scrolled out of view.', 'jumpstart' ),
				'type' => 'info',
			);

			$options['sticky_height'] = array(
				'id'      => 'sticky_height',
				'name'    => __( 'Height', 'jumpstart' ),
				'desc'    => __( 'Set the height of your sticky header.', 'jumpstart' ),
				'std'     => '50px',
				'type'    => 'slide',
				'options' => array(
					'min'   => '40',
					'max'   => '100',
					'step'  => '1',
					'units' => 'px',
				),
			);

			break;

		/*
		 * Set up mobile header options.
		 */
		case 'mobile-header':
			$options['header_mobile_info'] = array(
				'id'   => 'header_mobile_info',
				'desc' => __( 'These styles are applied to your header across smaller devices.', 'jumpstart' ),
				'type' => 'info',
			);

			$options['header_mobile_bg_color'] = array(
				'id'   => 'header_mobile_bg_color',
				'name' => __( 'Background Color', 'jumpstart' ),
				'desc' => __( 'Select a background color for the mobile header.', 'jumpstart' ),
				'std'  => '#101010',
				'type' => 'color',
			);

			$options['header_mobile_bg_color_brightness'] = array(
				'id'      => 'header_mobile_bg_color_brightness',
				'name'    => __( 'Background Color Brightness', 'jumpstart' ),
				'desc'    => __( 'In the previous option, did you go dark or light?', 'jumpstart' ),
				'std'     => 'dark',
				'type'    => 'select',
				'options' => array(
					'light' => __( 'I chose a light color in the previous option.', 'jumpstart' ),
					'dark'  => __( 'I chose a dark color in the previous option.', 'jumpstart' ),
				),
			);

			$options['header_mobile_icon_color'] = array(
				'id'   => 'header_mobile_icon_color',
				'name' => __( 'Icon Color', 'jumpstart' ),
				'desc' => __( 'Select a color for the navigational icons in the mobile header.', 'jumpstart' ),
				'std'  => '#ffffff',
				'type' => 'color',
			);

			$options['header_mobile_height'] = array(
				'id'      => 'header_mobile_height',
				'name'    => __( 'Height', 'jumpstart' ),
				'desc'    => __( 'Set the height of your mobile header.', 'jumpstart' ),
				'std'     => '64px',
				'type'    => 'slide',
				'options' => array(
					'min'   => '40',
					'max'   => '150',
					'step'  => '1',
					'units' => 'px',
				),
			);

			$options['header_mobile_breakpoint'] = array(
				'id'      => 'header_mobile_breakpoint',
				'name'    => __( 'Breakpoint', 'jumpstart' ),
				'desc'    => __( 'Set the viewport size breakpoint where the desktop header should be hidden, and the mobile header displayed.', 'jumpstart' ),
				'std'     => '991px',
				'type'    => 'slide',
				'options' => array(
					'min'   => '0',
					'max'   => '1400',
					'step'  => '1',
					'units' => 'px',
				),
			);

			break;

		/*
		 * Set up typography options.
		 *
		 * Note: Theme bases should pass in $defaults for typography
		 * options which include values for the following:
		 *
		 * 1. font_body
		 * 2. font_header
		 * 3. font_header_sm
		 * 4. font_header_sm_sp
		 * 5. font_meta
		 * 6. font_meta_sp
		 * 7. font_menu
		 * 8. font_menu_sp
		 */
		case 'typography':
			$options['font_body'] = array(
				'id'   => 'font_body',
				'name' => __( 'Primary Font', 'jumpstart' ),
				'desc' => __( 'This applies to most of the text on your site.', 'jumpstart' ),
				'std'  => array(),
				'atts' => array( 'size', 'face', 'style', 'weight' ),
				'type' => 'typography',
			);

			$options['font_header'] = array(
				'id'   => 'font_header',
				'name' => __( 'Header Font', 'jumpstart' ),
				'desc' => __( 'This applies to all of the primary headers throughout your site (h1, h2, h3, h4, h5, h6). This would include header tags used in redundant areas like widgets and the content of posts and pages.', 'jumpstart' ),
				'std'  => array(),
				'atts' => array( 'face', 'style', 'weight' ),
				'type' => 'typography',
			);

			$options['font_header_sm'] = array(
				'id'   => 'font_header_sm',
				'name' => __( 'Small Header Font', 'jumpstart' ),
				'desc' => __( 'This applies to smaller sub headers throughout your website, like widget titles, for example.', 'jumpstart' ),
				'std'  => array(),
				'atts' => array( 'face', 'style', 'weight' ),
				'type' => 'typography',
			);

			$options['font_header_sm_sp'] = array(
				'id'      => 'font_header_sm_sp',
				'name'    => __( 'Small Header Letter Spacing', 'jumpstart' ),
				'desc'    => __( 'Adjust the spacing between letters.', 'jumpstart' ),
				'std'     => '0px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '5',
					'step'  => '1',
				),
			);

			$options['font_quote'] = array(
				'id'   => 'font_quote',
				'name' => __( 'Quote Font', 'jumpstart' ),
				'desc' => __( 'This applies to quoted text in blockquote tags.', 'jumpstart' ),
				'std'  => array(
					'size'   => '',
					'face'   => 'google',
					'weight' => '400',
					'color'  => '',
					'google' => 'Libre Baskerville:400italic',
					'style'  => 'italic',
				),
				'atts' => array( 'face', 'style', 'weight' ),
				'type' => 'typography',
			);

			$options['font_quote_sp'] = array(
				'id'      => 'font_quote_sp',
				'name'    => __( 'Quote Letter Spacing', 'jumpstart' ),
				'desc'    => __( 'Adjust the spacing between letters.', 'jumpstart' ),
				'std'     => '0px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '5',
					'step'  => '1',
				),
			);

			$options['font_meta'] = array(
				'id'   => 'font_meta',
				'name' => __( 'Meta Info Font', 'jumpstart' ),
				'desc' => __( 'This applies to meta info like the "Posted" date below a post title, for example.', 'jumpstart' ),
				'std'  => array(),
				'atts' => array( 'face', 'style', 'weight' ),
				'type' => 'typography',
			);

			$options['font_meta_sp'] = array(
				'id'      => 'font_meta_sp',
				'name'    => __( 'Meta Info Letter Spacing', 'jumpstart' ),
				'desc'    => __( 'Adjust the spacing between letters.', 'jumpstart' ),
				'std'     => '0px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '5',
					'step'  => '1',
				),
			);

			$options['font_epic'] = array(
				'id'   => 'font_epic',
				'name' => __( 'Featured Image Title Font', 'jumpstart' ),
				'desc' => __( 'This applies when displaying a title on top of featured images.', 'jumpstart' ),
				'std'  => array(
					'size'   => '50px',
					'face'   => 'google',
					'weight' => '700',
					'color'  => '',
					'google' => 'Montserrat:700',
					'style'  => 'uppercase',
				),
				'atts'  => array( 'face', 'style', 'weight', 'size' ),
				'sizes' => array( '25', '26', '150' ),
				'type'  => 'typography',
			);

			$options['font_epic_sp'] = array(
				'id'      => 'font_epic_sp',
				'name'    => __( 'Featured Image Title Letter Spacing', 'jumpstart' ),
				'desc'    => __( 'Adjust the spacing between letters.', 'jumpstart' ),
				'std'     => '3px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '5',
					'step'  => '1',
				),
			);

			$options['font_menu'] = array(
				'id'    => 'font_menu',
				'name'  => __( 'Main Menu Font', 'jumpstart' ),
				'desc'  => __( 'This font applies to the top level items of the main menu.', 'jumpstart' ),
				'std'   => array(),
				'atts'  => array( 'size', 'face', 'style', 'weight' ),
				'type'  => 'typography',
				'sizes' => array( '10', '11', '12', '13', '14', '15', '16', '17', '18' ),
			);

			$options['font_menu_sp'] = array(
				'id'      => 'font_menu_sp',
				'name'    => __( 'Main Menu Letter Spacing', 'jumpstart' ),
				'desc'    => __( 'Adjust the spacing between letters.', 'jumpstart' ),
				'std'     => '0px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '0',
					'max'   => '5',
					'step'  => '1',
				),
			);

			$options['link_color'] = array(
				'id'   => 'link_color',
				'name' => __( 'Link Color', 'jumpstart' ),
				'desc' => __( 'Choose the color you\'d like applied to links.', 'jumpstart' ),
				'std'  => '#2ea3f2',
				'type' => 'color',
			);

			$options['link_hover_color'] = array(
				'id'   => 'link_hover_color',
				'name' => __( 'Link Hover Color', 'jumpstart' ),
				'desc' => __( 'Choose the color you\'d like applied to links when they are hovered over.', 'jumpstart' ),
				'std'  => '#337ab7',
				'type' => 'color',
			);

			$options['footer_link_color'] = array(
				'id'   => 'footer_link_color',
				'name' => __( 'Footer Link Color', 'jumpstart' ),
				'desc' => __( 'Choose the color you\'d like applied to links in the footer.', 'jumpstart' ),
				'std'  => '#2ea3f2',
				'type' => 'color',
			);

			$options['footer_link_hover_color'] = array(
				'id'   => 'footer_link_hover_color',
				'name' => __( 'Footer Link Hover Color', 'jumpstart' ),
				'desc' => __( 'Choose the color you\'d like applied to links in the footer when they are hovered over.', 'jumpstart' ),
				'std'  => '#337ab7',
				'type' => 'color',
			);

			break;

		/*
		 * Set up options for buttons.
		 */
		case 'buttons':
			// ...
			break;

		/*
		 * Set up options for sidebar widgets.
		 */
		case 'widgets':
			// @TODO Issue #265
			// @link https://github.com/themeblvd/jumpstart/issues/265
			break;

		/*
		 * Set up options for the site footer.
		 */
		case 'footer':
			$footer_bg_types = array();

			if ( function_exists( 'themeblvd_get_bg_types' ) ) {

				$footer_bg_types = themeblvd_get_bg_types( 'basic' );

			}

			$options['footer_info'] = array(
				'id'   => 'footer_info',
				// translators: 1: link to WP Menus admin page
				'desc' => sprintf( __( 'These options apply to the footer of your website. Note that for the footer menu to show in the copyright area, you must assign a menu to the "Footer Navigation" location at %s.', 'jumpstart' ), '<a href="nav-menus.php" target="_blank">' . __( 'Appearance > Menus', 'jumpstart' ) . '</a>' ),
				'type' => 'info',
			);

			$options['footer_border_top_start'] = array(
				'id'    => 'footer_border_top_start',
				'type'  => 'subgroup_start',
				'class' => 'show-hide',
			);

			$options['footer_apply_border_top'] = array(
				'id'    => 'footer_apply_border_top',
				'name'  => null,
				'desc'  => '<strong>' . __( 'Top Border', 'jumpstart' ) . '</strong>: ' . __( 'Apply top border to footer.', 'jumpstart' ),
				'std'   => 0,
				'type'  => 'checkbox',
				'class' => 'trigger',
			);

			$options['footer_border_top_color'] = array(
				'id'    => 'footer_border_top_color',
				'name'  => __( 'Top Border Color', 'jumpstart' ),
				'desc'  => __( 'Select a color for the top border.', 'jumpstart' ),
				'std'   => '#000000',
				'type'  => 'color',
				'class' => 'hide receiver',
			);

			$options['footer_border_top_width'] = array(
				'id'      => 'footer_border_top_width',
				'name'    => __( 'Top Border Width', 'jumpstart' ),
				'desc'    => __( 'Select a width in pixels for the top border.', 'jumpstart' ),
				'std'     => '1px',
				'type'    => 'slide',
				'options' => array(
					'units' => 'px',
					'min'   => '1',
					'max'   => '10',
				),
				'class'   => 'hide receiver',
			);

			$options['footer_border_top_end'] = array(
				'id'   => 'footer_border_top_end',
				'type' => 'subgroup_end',
			);

			$options['footer_bg_start'] = array(
				'id'    => 'footer_bg_start',
				'type'  => 'subgroup_start',
				'class' => 'show-hide-toggle',
			);

			$options['footer_bg_type'] = array(
				'id'      => 'footer_bg_type',
				'name'    => __( 'Background', 'jumpstart' ),
				// translators: 1: location of where to setup Footer features on Theme Options screen
				'desc'    => __( 'Select if you\'d like to apply a custom background color to the footer.', 'jumpstart' ) . '<br><br>' . sprintf( __( 'Note: To setup a more complex designed footer, go to %s and use the "Template Sync" feature.', 'jumpstart' ), '<em>' . __( 'Layout > Footer', 'jumpstart' ) . '</em>' ),
				'std'     => 'none',
				'type'    => 'select',
				'options' => $footer_bg_types,
				'class'   => 'trigger',
			);

			$options['footer_bg_color_brightness'] = array(
				'id'      => 'footer_bg_color_brightness',
				'name'    => __( 'Background Brightness', 'jumpstart' ),
				'desc'    => __( 'Would you say the background you\'re setting up is light or dark? And if you\'ve set the previous option to "No Background," is your website\'s body background light or dark?', 'jumpstart' ),
				'std'     => 'dark',
				'type'    => 'radio',
				'options' => array(
					'light' => __( 'I\'m using a light background.', 'jumpstart' ),
					'dark'  => __( 'I\'m using a dark background.', 'jumpstart' ),
				),
			);

			$options['footer_bg_color'] = array(
				'id'    => 'footer_bg_color',
				'name'  => __( 'Background Color', 'jumpstart' ),
				'desc'  => __( 'Select a background color for the footer columns.', 'jumpstart' ),
				'std'   => '#000000',
				'type'  => 'color',
				'class' => 'hide receiver receiver-color receiver-image receiver-texture',
			);

			$options['footer_bg_color_opacity'] = array(
				'id'        => 'footer_bg_color_opacity',
				'name'      => __( 'Background Color Opacity', 'jumpstart' ),
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
				'class'     => 'hide receiver receiver-color receiver-image receiver-texture',
			);

			$options['footer_bg_texture'] = array(
				'id'     => 'footer_bg_texture',
				'name'   => __( 'Background Texture', 'jumpstart' ),
				'desc'   => __( 'Select a background texture.', 'jumpstart' ),
				'type'   => 'select',
				'select' => 'textures',
				'class'  => 'hide receiver receiver-texture',
			);

			$options['footer_bg_image'] = array(
				'id'    => 'footer_bg_image',
				'name'  => __( 'Background Image', 'jumpstart' ),
				'desc'  => __( 'Select a background image.', 'jumpstart' ),
				'type'  => 'background',
				'class' => 'hide receiver receiver-image',
			);

			$options['footer_bg_end'] = array(
				'id'   => 'footer_bg_end',
				'type' => 'subgroup_end',
			);

			$options['copyright_bg_start'] = array(
				'id'    => 'copyright_bg_start',
				'type'  => 'subgroup_start',
				'class' => 'show-hide',
			);

			$options['copyright_apply_bg'] = array(
				'id'        => 'copyright_apply_bg',
				'name'      => null,
				'desc'      => '<strong>' . __( 'Copyright Background Color', 'jumpstart' ) . '</strong>: ' . __( 'Apply background color to copyright area below footer columns.', 'jumpstart' ),
				'std'       => 0,
				'type'      => 'checkbox',
				'class'     => 'trigger',
			);

			$options['copyright_bg_color'] = array(
				'id'       => 'copyright_bg_color',
				'name'     => __( 'Copyright Background Color', 'jumpstart' ),
				'desc'     => __( 'Select a background color for the copyright area below the footer columns.', 'jumpstart' ),
				'std'      => '#000000',
				'type'     => 'color',
				'class'    => 'hide receiver',
			);

			$options['copyright_bg_color_brightness'] = array(
				'id'       => 'copyright_bg_color_brightness',
				'name'     => __( 'Copyright Background Color Brightness', 'jumpstart' ),
				'desc'     => __( 'If you\'re using a dark background color, select to show light text, and vice versa.', 'jumpstart' ),
				'std'      => 'dark',
				'type'     => 'radio',
				'options'  => array(
					'light' => __( 'I chose a light color in the previous option.', 'jumpstart' ),
					'dark'  => __( 'I chose a dark color in the previous option.', 'jumpstart' ),
				),
				'class'    => 'hide receiver',
			);

			$options['copyright_bg_end'] = array(
				'id'   => 'copyright_bg_end',
				'type' => 'subgroup_end',
			);

			break;

		/*
		 * Set up options for side panel and mobile
		 * menu styling.
		 */
		case 'side-panel':
			$options['side_info'] = array(
				'id'   => 'side_info',
				// translators: 1: link to WP Menus admin page
				'desc' => sprintf( __( 'These options apply to your side panel that slides out from the side of your webste on desktop browsers, and the compiled menu that slides out on mobile devices. Note that for the side panel to show in desktop browsers, you must assign a menu to the "Primary Side Navigation" location at %s.', 'jumpstart' ), '<a href="nav-menus.php" target="_blank">' . __( 'Appearance > Menus', 'jumpstart' ) . '</a>' ),
				'type' => 'info',
			);

			$options['side_bg_color'] = array(
				'id'   => 'side_bg_color',
				'name' => __( 'Background Color', 'jumpstart' ),
				'desc' => __( 'Select a background color for the side panel.', 'jumpstart' ),
				'std'  => '#000000',
				'type' => 'color',
			);

			$options['side_bg_color_brightness'] = array(
				'id'      => 'side_bg_color_brightness',
				'name'    => __( 'Background Color Brightness', 'jumpstart' ),
				'desc'    => __( 'In the previous option, did you go dark or light?', 'jumpstart' ),
				'std'     => 'dark',
				'type'    => 'radio',
				'options' => array(
					'light' => __( 'I chose a light color in the previous option.', 'jumpstart' ),
					'dark'  => __( 'I chose a dark color in the previous option.', 'jumpstart' ),
				),
			);

			break;

		/*
		 * Set up options for Front Street branding
		 * colors.
		 */
		case 'branding':
			// @TODO Issue #264
			// @link https://github.com/themeblvd/jumpstart/issues/264

	}

	if ( $defaults && $options ) {

		foreach ( $options as $option_id => $option ) {

			if ( isset( $defaults[ $option_id ] ) ) {

				$options[ $option_id ]['std'] = $defaults[ $option_id ];

			}
		}
	}

	return $options;

}

/**
 * Get styles output for shared sections used in
 * different theme bases.
 *
 * @since Jump_Start 2.7.0
 *
 * @param  string $set    Type of option set to style, like `footer`.
 * @return string $output CSS output used with wp_add_inline_style().
 */
function themeblvd_get_shared_style( $set ) {

	$output = '';

	switch ( $set ) {

		/*
		 * Style the sticky header.
		 */
		case 'sticky-header':
			$height = intval( themeblvd_get_option( 'sticky_height' ) );

			if ( $height ) {

				$output .= ".tb-sticky-header > .wrap,\n";

				$output .= ".tb-sticky-header .tb-primary-menu > li {\n";

				$output .= sprintf( "\theight: %spx;\n", $height );

				$output .= "}\n";

				$output .= ".tb-sticky-header .logo-sticky img {\n";

				$output .= sprintf( "\tmax-height: %spx; /* {height} - 10px */\n", $height - 10 );

				$output .= "}\n";

				$output .= ".tb-sticky-header .tb-primary-menu > li > .menu-btn {\n";

				$output .= sprintf( "\tline-height: %spx;\n", $height );

				$output .= "}\n";

			}

			break;

		/*
		 * Style the mobile header.
		 */
		case 'mobile-header':
			$output .= ".tb-mobile-header {\n";

			$output .= sprintf(
				"\tbackground-color: %s;\n",
				esc_attr( themeblvd_get_option( 'header_mobile_bg_color' ) )
			);

			$output .= "}\n";

			$output .= ".tb-mobile-header > .wrap {\n";

			$output .= sprintf(
				"\theight: %s;\n",
				esc_attr( themeblvd_get_option( 'header_mobile_height' ) )
			);

			$output .= "}\n";

			$output .= ".tb-mobile-header .site-logo img {\n";

			$height = themeblvd_get_option( 'header_mobile_height' );

			$height = intval( $height ) - 10;

			$height = $height . 'px';

			$output .= sprintf(
				"\tmax-height: %s;\n",
				$height
			);

			$output .= "}\n";

			$icon_color = themeblvd_get_option( 'header_mobile_icon_color' );

			$output .= ".mobile-nav > li > a,\n";

			$output .= ".mobile-nav > li > a:hover {\n";

			$output .= sprintf( "\tcolor: %s;\n", esc_attr( $icon_color ) );

			$output .= "}\n";

			$output .= ".tb-nav-trigger .hamburger span,\n";

			$output .= ".tb-nav-trigger:hover .hamburger span {\n";

			$output .= sprintf( "\t\tbackground-color: %s;\n", esc_attr( $icon_color ) );

			$output .= "}\n";

			$breakpoint = intval( themeblvd_get_option( 'header_mobile_breakpoint', null, '991px' ) );

			$output .= "#top,\n";

			$output .= ".tb-mobile-header {\n";

			$output .= "\tdisplay: none;\n";

			$output .= "}\n";

			$output .= sprintf( "@media (min-width: %spx) {\n", $breakpoint + 1 );

			$output .= "\t#top {\n";

			$output .= "\t\tdisplay: block;\n";

			$output .= "\t}\n";

			$output .= "}\n";

			$output .= sprintf( "@media (max-width: %spx) {\n", $breakpoint );

			$output .= "\t.tb-mobile-header {\n";

			$output .= "\t\tdisplay: block;\n";

			$output .= "\t}\n";

			$output .= "}\n";

			break;

		/*
		 * Style the side panel and mobile menu.
		 */
		case 'side-panel':
			if ( themeblvd_do_side_panel() ) {

				$output .= ".tb-side-panel,\n";

			}

			$output .= ".tb-mobile-panel {\n";

			$output .= sprintf(
				"\tbackground-color: %s;\n",
				esc_attr( themeblvd_get_option( 'side_bg_color' ) )
			);

			$output .= "}\n";

			break;

		/*
		 * Style the website footer.
		 */
		case 'footer':
			$args = array(
				'bg_type'          => themeblvd_get_option( 'footer_bg_type' ),
				'bg_color'         => themeblvd_get_option( 'footer_bg_color' ),
				'bg_color_opacity' => themeblvd_get_option( 'footer_bg_color_opacity' ),
				'bg_texture'       => themeblvd_get_option( 'footer_bg_texture' ),
				'bg_image'         => themeblvd_get_option( 'footer_bg_image' ),
				'apply_border_top' => themeblvd_get_option( 'footer_apply_border_top' ),
				'border_top_color' => themeblvd_get_option( 'footer_border_top_color' ),
				'border_top_width' => themeblvd_get_option( 'footer_border_top_width' ),
			);

			$styles = themeblvd_get_display_inline_style( $args, 'external' );

			if ( ! empty( $styles['general'] ) ) {

				$output .= ".site-footer {\n";

				foreach ( $styles['general'] as $prop => $value ) {

					$prop = str_replace( '-2', '', $prop );

					$output .= sprintf( "\t%s: %s;\n", esc_attr( $prop ), esc_attr( $value ) );

				}

				$output .= "}\n";

			}

			if ( themeblvd_get_option( 'copyright_apply_bg' ) ) {

				$output .= ".site-copyright {\n";

				$output .= sprintf(
					"\tbackground-color: %s;\n",
					esc_attr( themeblvd_get_option( 'copyright_bg_color' ) )
				);

				$output .= "}\n";

			}
	}

	return $output;

}

/**
 * Add CSS classes to mobile header.
 *
 * @since Jump_Start 2.7.0
 *
 * @param  array $class CSS classes being added to mobile header.
 * @return array $class Modified CSS classes being added to mobile header.
 */
function jumpstart_mobile_header_class( $class ) {

	$class[] = themeblvd_get_option( 'header_mobile_bg_color_brightness' );

	return $class;

}

/**
 * Add CSS classes to side panel and mobile menu.
 *
 * @since Jump_Start 2.7.0
 *
 * @param  array $class CSS classes being added.
 * @return array $class Modified CSS classes being added.
 */
function jumpstart_side_panel_class( $class ) {

	$class[] = themeblvd_get_option( 'side_bg_color_brightness' );

	return $class;

}

/**
 * Add CSS classes to footer.
 *
 * @since Jump_Start 2.7.0
 *
 * @param  array $class CSS classes being added.
 * @return array $class Modified CSS classes being added.
 */
function jumpstart_footer_class( $class ) {

	$bg_type = themeblvd_get_option( 'footer_bg_type' );

	if ( $bg_type && 'none' !== $bg_type ) {

		$class[] = themeblvd_get_option( 'footer_bg_color_brightness' );

		if ( themeblvd_get_option( 'copyright_apply_bg' ) ) {

			$class[] = 'copyright-' . themeblvd_get_option( 'copyright_bg_color_brightness' );

		} else {

			$class[] = 'copyright-' . themeblvd_get_option( 'footer_bg_color_brightness' );

		}

		$class[] = 'has-bg';

	}

	return $class;

}

/**
 * Add CSS classes to site copyright at the
 * bottom of the footer.
 *
 * @since Jump_Start 2.7.0
 *
 * @param  array $class CSS classes being added.
 * @return array $class Modified CSS classes being added.
 */
function jumpstart_copyright_class( $class ) {

	if ( themeblvd_get_option( 'copyright_apply_bg' ) ) {

		$class[] = 'has-bg';

	}

	return $class;

}

/**
 * Adjust the style of the side panel contact
 * bar.
 *
 * To use, filter onto:
 * `themeblvd_panel_contact_bar_args`
 *
 * @since Jump_Start 2.7.0
 *
 * @param  array $args Arguments to pass to themeblvd_contact_bar().
 * @return array $args Modified arguments to pass to themeblvd_contact_bar().
 */
function jumpstart_panel_contact_bar_args( $args ) {

	if ( 'light' === themeblvd_get_option( 'side_bg_color_brightness' ) ) {

		$args['style'] = 'dark';

	}

	return $args;

}

/**
 * Adjust the style of the copyright contact bar.
 *
 * To use, filter onto:
 * `themeblvd_copyright_contact_bar_args`
 *
 * @since Jump_Start 2.7.0
 *
 * @param  array $args Arguments to pass to themeblvd_contact_bar().
 * @return array $args Modified arguments to pass to themeblvd_contact_bar().
 */
function jumpstart_copyright_contact_bar_args( $args ) {

	if ( themeblvd_get_option( 'copyright_apply_bg' ) ) {

		if ( 'dark' === themeblvd_get_option( 'copyright_bg_color_brightness' ) ) {

			$args['style'] = 'light';

		}
	} elseif ( 'dark' === themeblvd_get_option( 'footer_bg_color_brightness' ) ) {

		$args['style'] = 'light';

	}

	return $args;

}

/**
 * Adjust the mobile header breakpoint.
 *
 * This is the viewport size where the desktop
 * is hidden and the mobile header is displayed.
 *
 * To use, filter onto:
 * `themeblvd_mobile_header_breakpoint`
 *
 * @since Jump_Start 2.7.0
 *
 * @param  int $breakpoint Breakpoint, like `991`.
 * @return int $breakpoint Modified breakpoint, like `991`.
 */
function jumpstart_mobile_header_breakpoint( $breakpoint ) {

	$new = themeblvd_get_option( 'header_mobile_breakpoint' );

	if ( $new ) {

		$breakpoint = intval( $new );

	}

	return $breakpoint;

}
