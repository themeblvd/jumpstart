<?php
/**
 * Theme-Specific Functions
 *
 * This file is included immediately after framework
 * runs and serves as basis for all theme-specific
 * modifications.
 *
 * All theme-specific functionality should be included
 * in the theme's root `inc` directory. And any files
 * in there besides this one should be included from
 * this file.
 *
 * @author    Jason Bobich <info@themeblvd.com>
 * @copyright 2009-2017 Theme Blvd
 * @package   @@name-package
 * @since     @@name-package 2.0.0
 */

/**
 * Include in-dashboard update system.
 */
include_once( get_template_directory() . '/inc/theme-updates.php' );

/**
 * Global configuration, enable theme bases.
 *
 * @since @@name-package 2.0.0
 *
 * @param  array $config Configuration settings from framework.
 * @return array $config Modified configuration settings.
 */
function jumpstart_global_config( $config ) {

	$config['admin']['base'] = true;

	return $config;

}
add_filter( 'themeblvd_global_config', 'jumpstart_global_config' );

/**
 * Setup theme bases admin.
 *
 * @since @@name-package 2.0.0
 */
function jumpstart_bases() {

	if ( is_admin() && themeblvd_supports( 'admin', 'base' ) ) {

		/**
		 * Filter theme bases added by Jump Start, which
		 * are passed to the object created with
		 * class Theme_Blvd_Bases.
		 *
		 * @since @@name-package 2.0.0
		 *
		 * @param array Theme bases being added.
		 */
		$bases = apply_filters( 'themeblvd_bases', array(
			'dev' => array(
				'name' => __( 'Developer', '@@text-domain' ),
				'desc' => __( 'If you\'re a developer looking to create a custom-designed child theme, this is the base for you.', '@@text-domain' ),
			),
			'superuser' => array(
				'name' => __( 'Super User', '@@text-domain' ),
				'desc' => __( 'For the super user, this base builds on the default theme to give you more visual, user options.', '@@text-domain' ),
			),
			'agent' => array(
				'name' => __( 'Agent', '@@text-domain' ),
				'desc' => __( 'A modern and open, agency-style design with a bit less user options.', '@@text-domain' ),
			),
			'entrepreneur' => array(
				'name' => __( 'Entrepreneur', '@@text-domain' ),
				'desc' => __( 'A more standard, corporate design with a lot of user options.', '@@text-domain' ),
			),
			'executive' => array(
				'name' => __( 'Executive', '@@text-domain' ),
				'desc' => __( 'A more classic, corporate design with a lot of user options.', '@@text-domain' ),
			),
		));

		$admin = new Theme_Blvd_Bases( $bases, themeblvd_get_default_base() ); // Class included with is_admin().

	}

}
add_action( 'after_setup_theme', 'jumpstart_bases' );

/*
 * Include theme base.
 */
$base = themeblvd_get_base();

if ( $base ) {

	include_once( themeblvd_get_base_path( $base ) . '/base.php' );

}

/**
 * Enqueue Jump Start CSS files.
 *
 * @since 1.0.0
 */
function jumpstart_css() {

	$suffix = SCRIPT_DEBUG ? '' : '.min';

	$theme = wp_get_theme( get_template() );

	$ver = $theme->get( 'Version' );

	$stylesheet_ver = $ver;

	if ( get_template() !== get_stylesheet() ) {

		$theme = wp_get_theme( get_stylesheet() );

		$stylesheet_ver = $theme->get( 'Version' );

	}

	/*
	 * Theme Stylesheet
	 */
	if ( is_rtl() ) {

		wp_enqueue_style(
			'jumpstart',
			esc_url( get_template_directory_uri() . "/assets/css/theme-rtl{$suffix}.css" ),
			array( 'themeblvd' ),
			$ver
		);

	} else {

		wp_enqueue_style(
			'jumpstart',
			esc_url( get_template_directory_uri() . "/assets/css/theme{$suffix}.css" ),
			array( 'themeblvd' ),
			$ver
		);

	}

	/*
	 * Theme Base Stylesheet
	 */
	$base = themeblvd_get_base();

	if ( $base && 'dev' !== $base ) {

		wp_enqueue_style(
			'jumpstart-base',
			esc_url( themeblvd_get_base_uri( $base ) . "/base{$suffix}.css" ),
			array( 'themeblvd' ),
			$ver
		);

	}

	/*
	 * IE Stylesheet
	 */
	wp_enqueue_style(
		'themeblvd-ie',
		esc_url( get_template_directory_uri() . '/assets/css/ie.css' ),
		array(),
		$ver
	);

	$GLOBALS['wp_styles']->add_data( 'themeblvd-ie', 'conditional', 'IE' );

	/*
	 * Primary style.css
	 */
	wp_enqueue_style(
		'themeblvd-theme',
		esc_url( get_stylesheet_uri() ),
		array( 'themeblvd' ),
		$stylesheet_ver
	);

}
add_action( 'wp_enqueue_scripts', 'jumpstart_css', 20 );

/**
 * Jump Start base check.
 *
 * If WP user is logged in, output message on frontend
 * to tell them their saved theme options don't match
 * the theme base they've selected.
 *
 * @since @@name-package 2.0.0
 */
function jumpstart_base_check() {

	if ( ! is_user_logged_in() ) {

		return;

	}

	if ( ! themeblvd_supports( 'admin', 'base' ) ) {

		return;

	}

	if ( themeblvd_get_option( 'theme_base' ) == themeblvd_get_base() ) {

		return; // All good!

	}

	themeblvd_alert(
		array(
			'style' => 'warning',
			'class' => 'full',
		),
		__( 'Warning: Your saved theme options do not currently match the theme base you\'ve selected. Please configure and save your theme options page.', '@@text-domain' )
	);

}
add_action( 'themeblvd_before', 'jumpstart_base_check' );

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
 * @since @@name-package 2.2.0
 *
 * @param  string $set      Type of option set, like `typography`, `buttons`, `widgets`, etc.
 * @param  array  $defaults Any custom default values.
 * @return array  $options  Options set.
 */
function jumpstart_get_shared_options( $set, $defaults = array() ) {

	$options = array();

	switch ( $set ) {

		/*
		 * Set up mobile header options.
		 */
		case 'mobile-header':
			$options['header_mobile_info'] = array(
				'id'   => 'header_mobile_info',
				'desc' => __( 'These styles are applied to your header across most mobile devices and portrait tablets.', '@@text-domain' ),
				'type' => 'info',
			);

			$options['header_mobile_bg_color'] = array(
				'id'   => 'header_mobile_bg_color',
				'name' => __( 'Background Color', '@@text-domain' ),
				'desc' => __( 'Select a background color for the mobile header.', '@@text-domain' ),
				'std'  => '#101010',
				'type' => 'color',
			);

			$options['header_mobile_bg_color_brightness'] = array(
				'id'      => 'header_mobile_bg_color_brightness',
				'name'    => __( 'Background Color Brightness', '@@text-domain' ),
				'desc'    => __( 'In the previous option, did you go dark or light?', '@@text-domain' ),
				'std'     => 'dark',
				'type' 	  => 'select',
				'options' => array(
					'light' => __( 'I chose a light color in the previous option.', '@@text-domain' ),
					'dark' 	=> __( 'I chose a dark color in the previous option.', '@@text-domain' )
				),
			);

			$options['header_mobile_icon_color'] = array(
				'id'   => 'header_mobile_icon_color',
				'name' => __( 'Icon Color', '@@text-domain' ),
				'desc' => __( 'Select a color for the navigational icons in the mobile header.', '@@text-domain' ),
				'std'  => '#ffffff',
				'type' => 'color',
			);

			$options['header_mobile_height'] = array(
				'id'      => 'header_mobile_height',
				'name'    => __( 'Height', '@@text-domain' ),
				'desc'    => __( 'Set the height of your mobile header in pixels. This number should be higher than the height of your mobile logo image at <em>Layout > Mobile Header</em>.', '@@text-domain' ),
				'std'     => '64px',
				'type'    => 'slide',
				'options' => array(
					'min'   => '40',
					'max'   => '150',
					'step'  => '1',
					'units' => 'px',
				)
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
				'name' => __( 'Primary Font', '@@text-domain' ),
				'desc' => __( 'This applies to most of the text on your site.', '@@text-domain' ),
				'std'  => array(),
				'atts' => array( 'size', 'face', 'style', 'weight' ),
				'type' => 'typography',
			);

			$options['font_header'] = array(
				'id'   => 'font_header',
				'name' => __( 'Header Font', '@@text-domain' ),
				'desc' => __( 'This applies to all of the primary headers throughout your site (h1, h2, h3, h4, h5, h6). This would include header tags used in redundant areas like widgets and the content of posts and pages.', '@@text-domain' ),
				'std'  => array(),
				'atts' => array( 'face', 'style', 'weight' ),
				'type' => 'typography',
			);

			$options['font_header_sm'] = array(
				'id'   => 'font_header_sm',
				'name' => __( 'Small Header Font', '@@text-domain' ),
				'desc' => __( 'This applies to smaller sub headers throughout your website, like widget titles, for example.', '@@text-domain' ),
				'std'  => array(),
				'atts' => array( 'face', 'style', 'weight' ),
				'type' => 'typography',
			);

			$options['font_header_sm_sp'] = array(
				'id'      => 'font_header_sm_sp',
				'name'    => __( 'Small Header Letter Spacing', '@@text-domain' ),
				'desc'    => __( 'Adjust the spacing between letters.', '@@text-domain' ),
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
				'name' => __( 'Quote Font', '@@text-domain' ),
				'desc' => __( 'This applies to quoted text in blockquote tags.', '@@text-domain' ),
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
			);

			$options['font_meta'] = array(
				'id'   => 'font_meta',
				'name' => __( 'Meta Info Font', '@@text-domain' ),
				'desc' => __( 'This applies to meta info like the "Posted" date below a post title, for example.', '@@text-domain' ),
				'std'  => array(),
				'atts' => array( 'face', 'style', 'weight' ),
				'type' => 'typography',
			);

			$options['font_meta_sp'] = array(
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
			);

			$options['font_epic'] = array(
				'id'   => 'font_epic',
				'name' => __( 'Featured Image Title Font', '@@text-domain' ),
				'desc' => __( 'This applies when displaying a title on top of featured images.', '@@text-domain' ),
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
			);

			$options['font_menu'] = array(
				'id'    => 'font_menu',
				'name'  => __( 'Main Menu Font', '@@text-domain' ),
				'desc'  => __( 'This font applies to the top level items of the main menu.', '@@text-domain' ),
				'std'   => array(),
				'atts'  => array( 'size', 'face', 'style', 'weight' ),
				'type'  => 'typography',
				'sizes' => array( '10', '11', '12', '13', '14', '15', '16', '17', '18' ),
			);

			$options['font_menu_sp'] = array(
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
			);

			$options['link_color'] = array(
				'id'   => 'link_color',
				'name' => __( 'Link Color', '@@text-domain' ),
				'desc' => __( 'Choose the color you\'d like applied to links.', '@@text-domain' ),
				'std'  => '#2ea3f2',
				'type' => 'color',
			);

			$options['link_hover_color'] = array(
				'id'   => 'link_hover_color',
				'name' => __( 'Link Hover Color', '@@text-domain' ),
				'desc' => __( 'Choose the color you\'d like applied to links when they are hovered over.', '@@text-domain' ),
				'std'  => '#337ab7',
				'type' => 'color',
			);

			$options['footer_link_color'] = array(
				'id'   => 'footer_link_color',
				'name' => __( 'Footer Link Color', '@@text-domain' ),
				'desc' => __( 'Choose the color you\'d like applied to links in the footer.', '@@text-domain' ),
				'std'  => '#2ea3f2',
				'type' => 'color',
			);

			$options['footer_link_hover_color'] = array(
				'id'   => 'footer_link_hover_color',
				'name' => __( 'Footer Link Hover Color', '@@text-domain' ),
				'desc' => __( 'Choose the color you\'d like applied to links in the footer when they are hovered over.', '@@text-domain' ),
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
			// @TODO Issue #343
			// @link https://github.com/themeblvd/jumpstart/issues/343
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
 * Add Jump Start Homepage to sample layouts of
 * Layout Builder plugin.
 *
 * @since @@name-package 2.0.0
 *
 * @param  array $layouts All sample layouts.
 * @return array $layouts Modified sample layouts.
 */
function jumpstart_sample_layouts( $layouts ) {

	$layouts['jumpstart-home'] = array(
		'name'   => __( 'Jump Start: Home', '@@text-domain' ),
		'id'     => 'jumpstart-home',
		'dir'    => get_template_directory() . '/inc/layouts/home/',
		'uri'    => get_template_directory_uri() . '/inc/layouts/home/',
		'assets' => get_template_directory_uri() . '/inc/layouts/home/img/',
	);

	return $layouts;

}
add_filter( 'themeblvd_sample_layouts', 'jumpstart_sample_layouts' );
