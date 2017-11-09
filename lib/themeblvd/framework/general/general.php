<?php
/**
 * General-use functions, included on frontend
 * and admin.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */

/**
 * Setup framework handler and APIs.
 *
 * This function is hooked to the action `themeblvd_api`
 * which fires in /framework/themeblvd.php.
 *
 * @since @@name-framework 2.1.0
 */
function themeblvd_api_init() {

	/*
	 * Setup Theme Options API and establish theme settings.
	 * From this point client can use themeblvd_get_option().
	 */
	Theme_Blvd_Options_API::get_instance();

	/*
	 * Setup framework stylesheets and handler for frontend to
	 * modify these stylesheets.
	 */
	Theme_Blvd_Stylesheet_Handler::get_instance();

	/*
	 * Setup widget areas handler. This registers all default
	 * sidebars and provides methods to modify them and
	 * display them.
	 */
	Theme_Blvd_Sidebar_Handler::get_instance();

}

/**
 * Setup the config array for which features the
 * framework supports. This can easily be filtered, so the
 * theme author has a chance to disable the framework's
 * various features. The filter is this:
 *
 * themeblvd_global_config
 *
 * @since 2.0.0
 *
 * @return array $setup Features framework support
 */
function themeblvd_setup() {
	$setup = array(
		'admin' => array(
			'options'			=> true,			// Theme Options
			'builder'			=> true,			// Layouts page
			'sidebars'			=> true,			// Sidebars page
			'updates'			=> true,			// Updates (if theme supports)
			'user'				=> true,			// User profile options
			'tax'				=> true,			// Taxonomy options
			'menus'				=> true, 			// Options added to WP menu builder
			'base'				=> false,			// Theme base
			'plugins'           => true,			// Plugin manager interface.
		),
		'meta' => array(
			'page_options'		=> true,			// Meta box for basic page options
			'post_options'		=> true,			// Meta box for basic post options
			'pto'				=> true,			// Meta box for "Post Grid/List" page template
			'layout'			=> true				// Meta box for theme layout adjustments
		),
		'featured' => array(
			'style'				=> false,			// Whether current theme has special styling for featured area
			'archive'			=> false,			// Featured area on/off by default
			'blog'				=> false,			// Featured area on/off by default
			'grid'				=> false,			// Featured area on/off by default
			'page'				=> false,			// Featured area on/off by default
			'single'			=> false			// Featured area on/off by default
		),
		'featured_below' => array(
			'style'				=> false,			// Whether current theme has special styling for featured below area
			'archive'			=> false,			// Featured area on/off by default
			'blog'				=> false,			// Featured area on/off by default
			'grid'				=> false,			// Featured area on/off by default
			'page'				=> false,			// Featured area on/off by default
			'single'			=> false			// Featured area on/off by default
		),
		'comments' => array(
			'posts'				=> true,			// Comments on posts
			'pages'				=> false,			// Comments on pages
			'attachments'		=> false			// Comments on attachments
		),
		'display' => array(
			'responsive' 		=> true,			// Responsive elements
			'dark'				=> false,			// Whether to display as dark theme
			'sticky'			=> true,			// Sticky header as user scrolls past header
			'mobile_side_menu'	=> true,			// Responsive menu position fixed to the side of the screen on mobile
			'side_panel'		=> true,			// Optional side panel navigation
			'scroll_effects'	=> true, 			// Effects as user scrolls down page
			'hide_top'			=> true,			// Whether theme supports hiding the #top
			'hide_bottom'		=> true, 			// Whether theme supports hiding the #bottom
			'footer_sync'		=> true,			// Whether theme suppors syncing footer with template
			'suck_up'			=> true,			// Whether theme supports sucking custom layout content up into header
			'gallery'			=> true,			// Integration of thumbnail classes and lightbox to WP [gallery]
			'print'				=> true				// Whether to apply basic styles for printing
		),
		'assets' => array(
			'primary_js'		=> true,			// Primary "themeblvd" script
			'primary_css'		=> true,			// Primary "themeblvd" stylesheet
			'primary_dark_css'	=> true,			// Primary "themeblvd_dark" stylesheet (if supports display=>dark)
			'flexslider'		=> true,			// Flexslider script by WooThemes
			'bootstrap'			=> true,			// "bootstrap" script/stylesheet
			'magnific_popup'	=> true,			// "magnific_popup" script/stylesheet
			'superfish'			=> true,			// "superfish" script
			'easypiechart'		=> true,			// "EasyPieChart" scrip
			'gmap'				=> true,			// Google Maps API v3
			'charts'			=> true,			// Charts.js
			'vimeo'				=> true,			// Latest Vimeo API.
			'youtube'			=> true,			// Latest YouTube Iframe API.
			'isotope'			=> true,			// Isotope script for sorting
			'tag_cloud'			=> true, 			// Framework tag cloud styling
			'owl_carousel'		=> true, 			// Owl Carousel for gallery sliders
			'in_footer'			=> true				// Whether theme scripts are enqueued in footer
		),
		'plugins' => array(
			'bbpress'			=> true,			// bbPress by Automattic
			'gravityforms'		=> true,			// Gravity Forms by Rocket Genius
			'subtitles'			=> true,			// Subtitles by Philip Moore
			'woocommerce'		=> true,			// WooCommerce by WooThemes
			'wpml'				=> true				// WPML by On The Go Systems
		)
	);

	return apply_filters( 'themeblvd_global_config', $setup );
}

/**
 * Test whether an feature is currently supported.
 *
 * @since 2.0.0
 *
 * @param string $group Admin or frontend
 * @param string $feature Feature key to check
 * @return boolean $supports Whether feature is supported or not
 */
function themeblvd_supports( $group, $feature ) {

	$setup = themeblvd_setup();
	$supports = false;

	if ( ! empty( $setup ) && ! empty( $setup[$group][$feature] ) ) {
		$supports = true;
	}

	return $supports;
}

/**
 * Get post display modes
 *
 * @since 2.5.0
 *
 * @param string $var Description
 * @return string $var Description
 */
function themeblvd_get_modes() {
	return apply_filters('themeblvd_modes', array(
		'blog' 		=> __('Blog', '@@text-domain'),
		'list' 		=> __('List', '@@text-domain'),
		'grid' 		=> __('Grid', '@@text-domain'),
		'showcase' 	=> __('Showcase', '@@text-domain')
	));
}

/**
 * Include font from google and typekit. Accepts unlimited
 * amount of font arguments.
 *
 * @since 2.6.0
 */
function themeblvd_include_fonts() {

	$input = func_get_args();
	$used = array();

	if ( ! empty( $input ) ) {

		// Before including files, determine if SSL is being
		// used because if we include an external file without https
		// on a secure server, they'll get an error.
		$protocol = is_ssl() ? 'https://' : 'http://';

		// Build fonts to include
		$g_fonts = array();
		$t_fonts = array();

		foreach ( $input as $font ) {
			if ( $font['face'] == 'google' && ! empty($font['google']) ) {

				$font = explode(':', $font['google']);
				$name = trim(str_replace(' ', '+', $font[0]));

				if ( ! isset($g_fonts[$name]) ) {
					$g_fonts[$name] = array(
						'style'		=> array(),
						'subset'	=> array()
					);
				}

				if ( isset( $font[1] ) ) {

					$parts = explode('&', $font[1]);

					foreach ( $parts as $part ) {
						if ( strpos($part, 'subset') === 0 ) {
							$part = str_replace('subset=', '', $part);
							$part = explode(',', $part);
							$part = array_merge($g_fonts[$name]['subset'], $part);
							$g_fonts[$name]['subset'] = array_unique($part);
						} else {
							$part = explode(',', $part);
							$part = array_merge($g_fonts[$name]['style'], $part);
							$g_fonts[$name]['style'] = array_unique($part);
						}
					}

				}

			} else if ( $font['face'] == 'typekit' && ! empty($font['typekit_kit']) ) {

				if ( ! in_array( $font['typekit_kit'], $t_fonts ) ) {
					$t_fonts[] = $font['typekit_kit'];
				}

			}
		}

		// Include each font file from google
		foreach ( $g_fonts as $font => $atts ) {

			if ( ! empty($atts['style']) ) {
				$font .= sprintf( ':%s', implode(',', $atts['style']) );
			}

			if ( ! empty($atts['subset']) ) {
				$font .= sprintf( '&subset=%s', implode(',', $atts['subset']) );
			}

			printf( '<link href="%sfonts.googleapis.com/css?family=%s" rel="stylesheet" type="text/css">'."\n", $protocol, esc_attr($font) );

		}

		// Include each font from Typekit
		foreach ( $t_fonts as $font ) {
			echo themeblvd_kses($font) . "\n";
		}

	}
}

/**
 * Include font from google. Accepts unlimited
 * amount of font arguments.
 *
 * @since 2.0.0
 * @deprecated 2.6.0
 */
function themeblvd_include_google_fonts() {
	themeblvd_deprecated_function( __FUNCTION__, '2.6.0', 'themeblvd_include_fonts' );
	$args = func_get_args();
    call_user_func_array('themeblvd_include_fonts', $args);
}

/**
 * Get all current font stacks
 *
 * @since 2.0.0
 *
 * @return string $stacks All current font stacks
 */
function themeblvd_font_stacks() {
	$stacks = array(
		'default'		=> 'Arial, sans-serif', // Used to chain onto end of google font
		'arial'     	=> 'Arial, "Helvetica Neue", Helvetica, sans-serif',
		'baskerville'	=> 'Baskerville, "Times New Roman", Times, serif',
		'georgia'   	=> 'Georgia, Times, "Times New Roman", serif',
		'helvetica' 	=> '"Helvetica Neue", Helvetica, Arial,sans-serif',
		'lucida'  		=> '"Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif',
		'palatino'  	=> 'Palatino, "Palatino Linotype", Georgia, Times, "Times New Roman", serif',
		'tahoma'    	=> 'Tahoma, Geneva, Verdana, sans-serif',
		'times'     	=> 'Times New Roman',
		'trebuchet' 	=> '"Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", Arial, sans-serif',
		'verdana'   	=> 'Verdana, Geneva, Tahoma, sans-serif',
		'google'		=> 'Google Font'
	);
	return apply_filters( 'themeblvd_font_stacks', $stacks );
}

/**
 * Adjust sidebar layout to always be full_width if we're
 * on the WordPress Multisite signup page. This function is
 * added as a filter to themeblvd_sidebar_layout, which gets
 * applied in themeblvd_frontend_init.
 *
 * @since 2.1.0
 */
function themeblvd_wpmultisite_signup_sidebar_layout( $sidebar_layout ) {

	global $pagenow;

	if ( $pagenow == 'wp-signup.php' ) {
		$sidebar_layout = 'full_width';
	}

	return apply_filters( 'themeblvd_wpmultisite_signup_sidebar_layout', $sidebar_layout );
}

/**
 * Use themeblvd_button() function for read more links.
 *
 * When a WP user uses the more tag <!--more-->, this filter
 * will add the class "btn" to that link. This will allow
 * Bootstrap to style the link as one of its buttons.
 *
 * This function is used with WP filter "the_content_more_link"
 *
 * @since 2.2.0
 */
function themeblvd_read_more_link( $read_more, $more_link_text ) {

	$args = apply_filters( 'themeblvd_the_content_more_args', array(
		'text'			=> $more_link_text,
		'url'			=> get_permalink().'#more-'.get_the_ID(),
		'color'			=> 'default',
		'target' 		=> null,
		'size'			=> null,
		'classes'		=> null,
		'title'			=> null,
		'icon_before'	=> null,
		'icon_after'	=> null,
		'addon'			=> null
	));

	// Construct button based on filterable $args above
	$button = themeblvd_button( $args['text'], $args['url'], $args['color'], $args['target'], $args['size'], $args['classes'], $args['title'], $args['icon_before'], $args['icon_after'], $args['addon'] );

	return apply_filters( 'themeblvd_read_more_link', $button );
}

/**
 * Run warning if Theme Blvd function is deprecated
 * and WP_DEBUG is on.
 *
 * @since 2.0.0
 *
 * @param string $function Name of deprectated function
 * @param string $version Framework version function was deprecated
 * @param string $replacement Name of suggested replacement function
 * @param string $message Message to display instead of auto-generated replacement statement.
 */
function themeblvd_deprecated_function( $function, $version, $replacement = null, $message = null ) {
	if ( WP_DEBUG && apply_filters( 'deprecated_function_trigger_error', true ) ) {
		if ( $message ) {
			trigger_error( sprintf( esc_html__('%1$s is deprecated since version %2$s of the Theme Blvd framework! %3$s', '@@text-domain'), $function, $version, esc_html($message) ) );
		} else if ( $replacement ) {
			trigger_error( sprintf( esc_html__('%1$s is deprecated since version %2$s of the Theme Blvd framework! Use %3$s instead.', '@@text-domain'), $function, $version, $replacement ) );
		} else {
			trigger_error( sprintf( esc_html__('%1$s is deprecated since version %2$s of the Theme Blvd framework with no alternative available.', '@@text-domain'), $function, $version ) );
		}
	}
}

/**
 * Get capability for admin module so WordPress
 * can test this against current user-role.
 *
 * @since 2.1.0
 *
 * @param string $module Module ID to check
 * @return string $cap WP capability for current admin module
 */
function themeblvd_admin_module_cap( $module ) {

	// Setup default capabilities
	$module_caps = array(
		'builder' 	=> 'edit_theme_options', 		// Role: Administrator
		'options' 	=> 'edit_theme_options',		// Role: Administrator
		'sidebars' 	=> 'edit_theme_options',		// Role: Administrator
		'updates' 	=> 'edit_theme_options'			// Role: Administrator
	);
	$module_caps = apply_filters( 'themeblvd_admin_module_caps', $module_caps );

	// Setup capability
	$cap = '';
	if ( isset( $module_caps[$module] ) ) {
		$cap = $module_caps[$module];
	}

	return $cap;
}

/**
 * Compress a chunk of code to output.
 *
 * @since 2.0.0
 *
 * @param string $buffer Text to compress
 * @param string $buffer Buffered text
 * @return array $buffer Compressed text
 */
function themeblvd_compress( $buffer ) {

	// Remove comments
	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

	// Remove tabs, spaces, newlines, etc.
	$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

	return $buffer;
}

/**
 * Retrieves a post id given a post's slug and post type.
 *
 * @since 2.0.0
 * @uses $wpdb
 *
 * @param string $slug slug of post
 * @param string $post_type post type for post.
 * @return string $id ID of post.
 */
function themeblvd_post_id_by_name( $slug, $post_type = null ) {

	global $wpdb;
	$null = null;
	$slug = sanitize_title( $slug );

	// Grab posts from DB (hopefully there's only one!)
	if ( $post_type ) {
		// More efficiant with post type
		$posts = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND (post_type = %s)", $slug, $post_type ));
	} else {
		$posts = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s", $slug ));
	}

	// If no results, return null
	if ( empty( $posts ) ) {
		return $null;
	}

	// Run through our results and return the ID of the first.
	// Hopefully there was only one result, but if there was
	// more than one, we'll just return a single ID.
	foreach ( $posts as $post ) {
		if ( $post->ID ) {
			return $post->ID;
		}
	}

	// If for some odd reason, there was no ID in the returned
	// post ID's, return nothing.
	return $null;
}

/**
 * Register theme's nav menus.
 *
 * @since 2.0.0
 */
function themeblvd_register_navs() {

	// Setup nav menus
	$menus = array(
		'primary' => __('Primary Navigation', '@@text-domain'),
		'footer' => __('Footer Navigation', '@@text-domain')
	);

	if ( themeblvd_supports('display', 'side_panel') ) {
		$menus['side'] = __('Primary Side Navigation', '@@text-domain');
		$menus['side_sub'] = __('Secondary Side Navigation', '@@text-domain');
	}

	$menus = apply_filters( 'themeblvd_nav_menus', $menus );

	// Register nav menus with WP
	register_nav_menus( $menus );

}

/**
 * Any occurances of WordPress's add_theme_support() happen here.
 * Can override function from Child Theme.
 *
 * @since 2.0.0
 */
function themeblvd_add_theme_support() {

	// Auto Feed Links
	add_theme_support( 'automatic-feed-links' );

	// Post Thumbnails
	add_theme_support( 'post-thumbnails' );

	// HTML5
	add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list', 'caption') );

	// Post Formats
	add_theme_support( 'post-formats', array('aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery', 'status', 'chat') );

	// Title tags auto added to wp_head
	add_theme_support( 'title-tag' );

	// WooCommerce native support by theme
	add_theme_support( 'woocommerce' );

	// Editor style
	add_editor_style( 'style-editor.css' );

}

/**
 * Get current page identifiers and keys for what we consider
 * admin modules. By default, this includeds:
 * 1) Theme Options
 * 2) Layout Builder 	(plugin)
 * 3) Widget Areas 		(plugin)
 *
 * @since 2.3.0
 */
function themeblvd_get_admin_modules() {

	// Options page
	$api = Theme_Blvd_Options_API::get_instance();
	$args = $api->get_args();
	$options_page = sprintf( 'themes.php?page=%s', $args['menu_slug'] );

	// Admin modules
	$modules = array(
		'options'	=> $options_page,
		'builder'	=> 'admin.php?page=themeblvd_builder',
		'sidebars'	=> 'themes.php?page=themeblvd_widget_areas',
	);

	return apply_filters( 'themeblvd_admin_modules', $modules );
}

/**
 * Add items to admin menu bar. This needs to be here in general
 * functions because admin bar appears on frontend as well.
 *
 * @since 2.0.0
 */
function themeblvd_admin_menu_bar() {

	global $wp_admin_bar;

	if ( is_admin() || ! method_exists( $wp_admin_bar, 'add_node' ) ) {
		return;
	}

	// Get all admin modules
	$modules = themeblvd_get_admin_modules();

	if ( ! $modules ) {
		return;
	}

	// Theme Options
	if ( isset( $modules['options'] ) && themeblvd_supports( 'admin', 'options' ) && current_user_can( themeblvd_admin_module_cap( 'options' ) ) ) {
		$wp_admin_bar->add_node(
			array(
				'id'			=> 'tb_theme_options',
				'title'			=> __('Theme Options', '@@text-domain'),
				'parent'		=> 'site-name',
				'href'			=> admin_url( $modules['options'] )
			)
		);
	}

	// Builder (if layout builder plugin is installed)
	if ( defined( 'TB_BUILDER_PLUGIN_VERSION' ) && isset( $modules['builder'] ) ) {
		if ( themeblvd_supports( 'admin', 'builder' ) && current_user_can( themeblvd_admin_module_cap( 'builder' ) ) ) {
			$wp_admin_bar->add_node(
				array(
					'id'		=> 'tb_builder',
					'title'		=> __('Templates', '@@text-domain'),
					'parent'	=> 'site-name',
					'href'		=> admin_url( $modules['builder'] )
				)
			);
		}
	}

	// Sidebars (if sidebar plugin is installed)
	if ( defined( 'TB_SIDEBARS_PLUGIN_VERSION' ) && isset( $modules['sidebars'] ) ) {
		if ( themeblvd_supports( 'admin', 'sidebars' ) && current_user_can( themeblvd_admin_module_cap( 'sidebars' ) ) ) {
			$wp_admin_bar->add_node(
				array(
					'id'		=> 'tb_sidebars',
					'title'		=> __('Widget Areas', '@@text-domain'),
					'parent'	=> 'site-name',
					'href' 		=> admin_url( $modules['sidebars'] )
				)
			);
		}
	}
}

/**
 * Get all sidebar layouts.
 *
 * @since 2.0.0
 *
 * @return array $layouts All current sidebar layouts
 */
function themeblvd_sidebar_layouts() {

	// Bootstrap column size -- In Bootstrap 3+,
	// this is used to determine how small the viewport
	// is before stacking the columns. By using "md"
	// we are having columns drop responsively at the
	// 992px or less (i.e. tablet viewport).
	$stack = apply_filters( 'themeblvd_sidebar_layout_stack', 'md' );

	// ... And then because old versions of IE are horrible,
	// they do not accurately know the viewport size.

	// So, is this IE8?
	if ( themeblvd_is_ie( array('8') ) ) {

		// If this is IE8, change the size
		// to "xs" as a fail-safe. This is okay because
		// responsive behavior here is irrelvant, anyway.
		$stack = 'xs';

	}

	$layouts = array(
		'full_width' => array(
			'name' 		=> __('Full Width', '@@text-domain'),
			'id'		=> 'full_width',
			'columns'	=> array(
				'content' 	=> "col-{$stack}-12",
				'left' 		=> '',
				'right' 	=> ''
			)
		),
		'sidebar_right' => array(
			'name' 		=> __('Sidebar Right', '@@text-domain'),
			'id'		=> 'sidebar_right',
			'columns'	=> array(
				'content' 	=> "col-{$stack}-8",
				'left' 		=> '',
				'right' 	=> "col-{$stack}-4"
			)
		),
		'sidebar_left' => array(
			'name' 		=> __('Sidebar Left', '@@text-domain'),
			'id'		=> 'sidebar_left',
			'columns'	=> array(
				'content' 	=> "col-{$stack}-8",
				'left' 		=> "col-{$stack}-4",
				'right' 	=> ''
			)
		),
		'double_sidebar' => array(
			'name' 		=> __('Double Sidebar', '@@text-domain'),
			'id'		=> 'double_sidebar',
			'columns'	=> array(
				'content' 	=> "col-{$stack}-6",
				'left' 		=> "col-{$stack}-3",
				'right' 	=> "col-{$stack}-3"
			)
		),
		'double_sidebar_left' => array(
			'name' 		=> __('Double Left Sidebars', '@@text-domain'),
			'id'		=> 'double_sidebar_left',
			'columns'	=> array(
				'content' 	=> "col-{$stack}-6",
				'left' 		=> "col-{$stack}-3",
				'right' 	=> "col-{$stack}-3"
			)
		),
		'double_sidebar_right' => array(
			'name' 		=> __('Double Right Sidebars', '@@text-domain'),
			'id'		=> 'double_sidebar_right',
			'columns'	=> array(
				'content' 	=> "col-{$stack}-6",
				'left' 		=> "col-{$stack}-3",
				'right' 	=> "col-{$stack}-3"
			)
		)
	);
	return apply_filters( 'themeblvd_sidebar_layouts', $layouts, $stack );
}

/**
 * Get class used to determine width of column in primary layout.
 *
 * @since 2.2.0
 *
 * @param string $column Which column to retrieve class for - left, right, or content
 * @return string $column_class The class to be used in grid system
 */
function themeblvd_get_column_class( $column ) {

	$class = '';

	// Make sure valid $column
	if ( ! in_array( $column, array( 'content', 'left', 'right' ) ) ) {
		return $class;
	}

	$layouts = themeblvd_sidebar_layouts();
	$layout = themeblvd_config('sidebar_layout');

	if ( isset( $layouts[$layout]['columns'][$column] ) ) {

		// Get intial class
		$class = $layouts[$layout]['columns'][$column];

		// If this layout has a left sidebar, it'll require some push/pull
		if ( in_array( $layout, array('sidebar_left', 'double_sidebar', 'double_sidebar_left' )) ) {

			// What is the current stack?
			$stack = 'xs';

			if ( strpos($class, 'sm') !== false ) {
				$stack = 'sm';
			} else if( strpos($class, 'md') !== false ) {
				$stack = 'md';
			} else if( strpos($class, 'lg') !== false ) {
				$stack = 'lg';
			}

			// Push/pull columns based on the layout
			if ( $layout == 'sidebar_left' || $layout == 'double_sidebar' ) {

				if ( $column == 'content' ) {

					// Content push = left sidebar width
					$class .= ' '.str_replace( "col-{$stack}-", "col-{$stack}-push-", $layouts[$layout]['columns']['left'] );

				} else if ( $column == 'left' ) {

					// Left sidebar pull = content width
					$class .= ' '.str_replace( "col-{$stack}-", "col-{$stack}-pull-", $layouts[$layout]['columns']['content'] );

				}

			} else if ( 'double_sidebar_left' ) {

				if ( $column == 'content' ) {

					// Content push = left sidebar width + right right sidebar width.
					$push_1 = str_replace( "col-{$stack}-", '', $layouts['double_sidebar_left']['columns']['left'] );
					$push_2 = str_replace( "col-{$stack}-", '', $layouts['double_sidebar_left']['columns']['right'] );

					$push = trim( intval($push_1) + intval($push_2) );

					if ( strpos($push_1, '0') === 0 && strpos($push_2, '0') === 0 ) {
						$push = '0'.$push;
					}

					$push = "col-{$stack}-push-{$push}";
					$class .= ' '.$push;

				} else {

					// Left/Right sidebar pull = content width
					$class .= ' '.str_replace( "col-{$stack}-", "col-{$stack}-pull-", $layouts['double_sidebar_left']['columns']['content'] );

				}

			}

		}

	}

	return apply_filters( 'themeblvd_column_class', $class, $column, $layout );
}

/**
 * Convert scaffolding CSS classes from Boostrap 1 and 2
 * to default classes used in Bootstrap 3. This is a
 * fallback for anyone who was filtering "themeblvd_sidebar_layouts"
 * prior to framework 2.4 and Bootstrap 3.
 *
 * @since 2.4.0
 *
 * @param string $class Current sidebar layout column class
 */
function themeblvd_column_class_legacy( $class ) {
	return str_replace( 'span', 'col-sm-', $class );
}

/**
 * Get transparent textures
 *
 * @since 2.0.5
 *
 * @return array $textures All current textures
 */
function themeblvd_get_textures() {

	$imagepath = apply_filters( 'themeblvd_textures_img_path', get_template_directory_uri().'/framework/assets/img/textures/' );

	$textures = array(
		'arches' => array(
			'name' 		=> __('Arches', '@@text-domain'),
			'url' 		=> $imagepath.'arches.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '103px 23px'
		),
		'boxy' => array(
			'name' 		=> __('Boxy', '@@text-domain'),
			'url' 		=> $imagepath.'boxy.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'brick_wall' => array(
			'name' 		=> __('Brick Wall', '@@text-domain'),
			'url' 		=> $imagepath.'brick_wall.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'carbon_classic' => array(
			'name' 		=> __('Carbon Classic', '@@text-domain'),
			'url' 		=> $imagepath.'carbon_classic.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'carbon_diagonal' => array(
			'name' 		=> __('Carbon Diagonal', '@@text-domain'),
			'url' 		=> $imagepath.'carbon_diagonal.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'carbon_weave' => array(
			'name' 		=> __('Carbon Weave', '@@text-domain'),
			'url' 		=> $imagepath.'carbon_weave.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'chex' => array(
			'name' 		=> __('Chex', '@@text-domain'),
			'url' 		=> $imagepath.'chex.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'circles' => array(
			'name' 		=> __('Circles', '@@text-domain'),
			'url' 		=> $imagepath.'circles.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'climpek' => array(
			'name' 		=> __('Climpek', '@@text-domain'),
			'url' 		=> $imagepath.'climpek.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'concrete' => array(
			'name' 		=> __('Concrete', '@@text-domain'),
			'url' 		=> $imagepath.'concrete.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'cross' => array(
			'name' 		=> __('Crosses', '@@text-domain'),
			'url' 		=> $imagepath.'cross.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'crystal' => array(
			'name' 		=> __('Crystal', '@@text-domain'),
			'url' 		=> $imagepath.'crystal.png',
			'position' 	=> '50% 50%',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'cube_stack' => array(
			'name' 		=> __('Cube Stack', '@@text-domain'),
			'url' 		=> $imagepath.'cube_stack.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'denim' => array(
			'name' 		=> __('Denim', '@@text-domain'),
			'url' 		=> $imagepath.'denim.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'diagnol_thin' => array(
			'name' 		=> __('Diagonal (thin)', '@@text-domain'),
			'url' 		=> $imagepath.'diagnol.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '6px 6px'
		),
		'diagnol_thick' => array(
			'name' 		=> __('Diagonal (thick)', '@@text-domain'),
			'url' 		=> $imagepath.'diagnol.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '20px 20px'
		),
		'diamonds' => array(
			'name' 		=> __('Diamonds', '@@text-domain'),
			'url' 		=> $imagepath.'diamonds.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'escheresque' => array(
			'name' 		=> __('Escheresque', '@@text-domain'),
			'url' 		=> $imagepath.'escheresque.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '46px 29px',
			'size'		=> 'auto'
		),
		'grid' => array(
			'name' 		=> __('Grid', '@@text-domain'),
			'url' 		=> $imagepath.'grid.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'grunge' => array(
			'name' 		=> __('Grunge', '@@text-domain'),
			'url' 		=> $imagepath.'grunge.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'feather' => array(
			'name' 		=> __('Feather', '@@text-domain'),
			'url' 		=> $imagepath.'feather.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '500px 333px'
		),
		'hexagons' => array(
			'name' 		=> __('Hexagons', '@@text-domain'),
			'url' 		=> $imagepath.'hexagons.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'honey_comb' => array(
			'name' 		=> __('Honey Comb', '@@text-domain'),
			'url' 		=> $imagepath.'honey_comb.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'leather' => array(
			'name' 		=> __('Leather', '@@text-domain'),
			'url' 		=> $imagepath.'leather.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'metal' => array(
			'name' 		=> __('Metal', '@@text-domain'),
			'url' 		=> $imagepath.'metal.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'mosaic' => array(
			'name' 		=> __('Mosaic', '@@text-domain'),
			'url' 		=> $imagepath.'mosaic.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'mosaic_triangles' => array(
			'name' 		=> __('Mosaic Triangles', '@@text-domain'),
			'url' 		=> $imagepath.'mosaic_triangles.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'noise' => array(
			'name' 		=> __('Noise', '@@text-domain'),
			'url' 		=> $imagepath.'noise.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'paper' => array(
			'name' 		=> __('Paper', '@@text-domain'),
			'url' 		=> $imagepath.'paper.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'pixel_weave' => array(
			'name' 		=> __('Pixel Weave', '@@text-domain'),
			'url' 		=> $imagepath.'pixel_weave.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '64px 64px'
		),
		'plaid' => array(
			'name' 		=> __('Plaid', '@@text-domain'),
			'url' 		=> $imagepath.'plaid.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'pyramids' => array(
			'name' 		=> __('Pyramids', '@@text-domain'),
			'url' 		=> $imagepath.'pyramids.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'rubber' => array(
			'name' 		=> __('Rubber', '@@text-domain'),
			'url' 		=> $imagepath.'rubber.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'skulls' => array(
			'name' 		=> __('Skulls', '@@text-domain'),
			'url' 		=> $imagepath.'skulls.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'squares' => array(
			'name' 		=> __('Squares', '@@text-domain'),
			'url' 		=> $imagepath.'squares.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'stacked_circles' => array(
			'name' 		=> __('Stacked Circles', '@@text-domain'),
			'url' 		=> $imagepath.'stacked_circles.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '9px 9px'
		),
		'swirl' => array(
			'name' 		=> __('Swirl', '@@text-domain'),
			'url' 		=> $imagepath.'swirl.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '100px 100px'
		),
		'textile' => array(
			'name' 		=> __('Textile', '@@text-domain'),
			'url' 		=> $imagepath.'textile.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'tiles' => array(
			'name' 		=> __('Tiles', '@@text-domain'),
			'url' 		=> $imagepath.'tiles.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '100px 99px'
		),
		'vertical_fabric' => array(
			'name' 		=> __('Vertical Fabric', '@@text-domain'),
			'url' 		=> $imagepath.'vertical_fabric.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'vintage' => array(
			'name' 		=> __('Vintage', '@@text-domain'),
			'url' 		=> $imagepath.'vintage.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'waves' => array(
			'name' 		=> __('Waves', '@@text-domain'),
			'url' 		=> $imagepath.'waves.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'wood' => array(
			'name' 		=> __('Wood', '@@text-domain'),
			'url' 		=> $imagepath.'wood.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'wood_planks' => array(
			'name' 		=> __('Wood Planks', '@@text-domain'),
			'url' 		=> $imagepath.'wood_planks.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'divider' => array(
			'name' 		=> __('---------------', '@@text-domain'),
			'url' 		=> null,
			'position' 	=> null,
			'repeat' 	=> null,
		),
		'arches_light' => array(
			'name' 		=> __('Light Arches', '@@text-domain'),
			'url' 		=> $imagepath.'arches_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '103px 23px'
		),
		'boxy_light' => array(
			'name' 		=> __('Light Boxy', '@@text-domain'),
			'url' 		=> $imagepath.'boxy_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'brick_wall_light' => array(
			'name' 		=> __('Light Brick Wall', '@@text-domain'),
			'url' 		=> $imagepath.'brick_wall_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'carbon_classic_light' => array(
			'name' 		=> __('Light Carbon Classic', '@@text-domain'),
			'url' 		=> $imagepath.'carbon_classic_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'carbon_diagonal_light' => array(
			'name' 		=> __('Light Carbon Diagonal', '@@text-domain'),
			'url' 		=> $imagepath.'carbon_diagonal_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'carbon_weave_light' => array(
			'name' 		=> __('Light Carbon Weave', '@@text-domain'),
			'url' 		=> $imagepath.'carbon_weave_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'chex_light' => array(
			'name' 		=> __('Light Chex', '@@text-domain'),
			'url' 		=> $imagepath.'chex_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'circles_light' => array(
			'name' 		=> __('Light Circles', '@@text-domain'),
			'url' 		=> $imagepath.'circles_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'climpek_light' => array(
			'name' 		=> __('Light Climpek', '@@text-domain'),
			'url' 		=> $imagepath.'climpek_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'concrete_light' => array(
			'name' 		=> __('Light Concrete', '@@text-domain'),
			'url' 		=> $imagepath.'concrete_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'cross_light' => array(
			'name' 		=> __('Light Crosses', '@@text-domain'),
			'url' 		=> $imagepath.'cross_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'cube_stack_light' => array(
			'name' 		=> __('Light Cube Stack', '@@text-domain'),
			'url' 		=> $imagepath.'cube_stack_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'denim_light' => array(
			'name' 		=> __('Light Denim', '@@text-domain'),
			'url' 		=> $imagepath.'denim_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'diagnol_thin_light' => array(
			'name' 		=> __('Light Diagonal (thin)', '@@text-domain'),
			'url' 		=> $imagepath.'diagnol_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '6px 6px'
		),
		'diagnol_thick_light' => array(
			'name' 		=> __('Light Diagonal (thick)', '@@text-domain'),
			'url' 		=> $imagepath.'diagnol_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '20px 20px'
		),
		'diamonds_light' => array(
			'name' 		=> __('Light Diamonds', '@@text-domain'),
			'url' 		=> $imagepath.'diamonds_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'escheresque_light' => array(
			'name' 		=> __('Light Escheresque', '@@text-domain'),
			'url' 		=> $imagepath.'escheresque_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '46px 29px'
		),
		'grid_light' => array(
			'name' 		=> __('Light Grid', '@@text-domain'),
			'url' 		=> $imagepath.'grid_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'grunge_light' => array(
			'name' 		=> __('Light Grunge', '@@text-domain'),
			'url' 		=> $imagepath.'grunge_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'feather_light' => array(
			'name' 		=> __('Light Feather', '@@text-domain'),
			'url' 		=> $imagepath.'feather_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '500px 333px'
		),
		'hexagons_light' => array(
			'name' 		=> __('Light Hexagons', '@@text-domain'),
			'url' 		=> $imagepath.'hexagons_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'honey_comb_light' => array(
			'name' 		=> __('Light Honey Comb', '@@text-domain'),
			'url' 		=> $imagepath.'honey_comb_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'leather_light' => array(
			'name' 		=> __('Light Leather', '@@text-domain'),
			'url' 		=> $imagepath.'leather_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'metal_light' => array(
			'name' 		=> __('Light Metal', '@@text-domain'),
			'url' 		=> $imagepath.'metal_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'mosaic_light' => array(
			'name' 		=> __('Light Mosaic', '@@text-domain'),
			'url' 		=> $imagepath.'mosaic_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'mosaic_triangles_light' => array(
			'name' 		=> __('Light Mosaic Triangles', '@@text-domain'),
			'url' 		=> $imagepath.'mosaic_triangles_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'noise_light' => array(
			'name' 		=> __('Light Noise', '@@text-domain'),
			'url' 		=> $imagepath.'noise_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'paper_light' => array(
			'name' 		=> __('Light Paper', '@@text-domain'),
			'url' 		=> $imagepath.'paper_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'pixel_weave_light' => array(
			'name' 		=> __('Light Pixel Weave', '@@text-domain'),
			'url' 		=> $imagepath.'pixel_weave_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '64px 64px'
		),
		'plaid_light' => array(
			'name' 		=> __('Light Plaid', '@@text-domain'),
			'url' 		=> $imagepath.'plaid_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'pyramids_light' => array(
			'name' 		=> __('Light Pyramids', '@@text-domain'),
			'url' 		=> $imagepath.'pyramids_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'rubber_light' => array(
			'name' 		=> __('Light Rubber', '@@text-domain'),
			'url' 		=> $imagepath.'rubber_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'skulls_light' => array(
			'name' 		=> __('Light Skulls', '@@text-domain'),
			'url' 		=> $imagepath.'skulls_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'squares_light' => array(
			'name' 		=> __('Light Squares', '@@text-domain'),
			'url' 		=> $imagepath.'squares_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'stacked_circles_light' => array(
			'name' 		=> __('Light Stacked Circles', '@@text-domain'),
			'url' 		=> $imagepath.'stacked_circles_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '9px 9px'
		),
		'swirl_light' => array(
			'name' 		=> __('Light Swirl', '@@text-domain'),
			'url' 		=> $imagepath.'swirl_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '100px 100px'
		),
		'textile_light' => array(
			'name' 		=> __('Light Textile', '@@text-domain'),
			'url' 		=> $imagepath.'textile_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'tiles_light' => array(
			'name' 		=> __('Light Tiles', '@@text-domain'),
			'url' 		=> $imagepath.'tiles_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> '100px 99px'
		),
		'vertical_fabric_light' => array(
			'name' 		=> __('Light Vertical Fabric', '@@text-domain'),
			'url' 		=> $imagepath.'vertical_fabric_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'vintage_light' => array(
			'name' 		=> __('Light Vintage', '@@text-domain'),
			'url' 		=> $imagepath.'vintage_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'waves_light' => array(
			'name' 		=> __('Light Waves', '@@text-domain'),
			'url' 		=> $imagepath.'waves_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'wood_light' => array(
			'name' 		=> __('Light Wood', '@@text-domain'),
			'url' 		=> $imagepath.'wood_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		),
		'wood_planks_light' => array(
			'name' 		=> __('Light Wood Planks', '@@text-domain'),
			'url' 		=> $imagepath.'wood_planks_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
			'size'		=> 'auto'
		)

	);

	return apply_filters( 'themeblvd_textures', $textures );
}

/**
 * Get single transparent texture
 *
 * @since 2.5.0
 *
 * @param string $texture Texture ID to pull
 * @return array texture Attributes
 */
function themeblvd_get_texture( $texture ) {

	$textures = themeblvd_get_textures();

	if ( ! empty( $textures[$texture] ) ) {
		return $textures[$texture];
	}

	return null;
}

/**
 * Generates array to be used in a select option
 * type of the options framework.
 *
 * @since 2.0.0
 *
 * @param $type string type of select to return
 * @return $select array items for select
 */
function themeblvd_get_select( $type, $force_single = false ) {

	// WPML compat
	if ( isset( $GLOBALS['sitepress'] ) ) {
		remove_filter( 'get_pages', array( $GLOBALS['sitepress'], 'exclude_other_language_pages2' ) );
		remove_filter( 'get_terms_args', array( $GLOBALS['sitepress'], 'get_terms_args_filter' ) );
		remove_filter( 'terms_clauses', array( $GLOBALS['sitepress'], 'terms_clauses' ) );
	}

	$select = array();

	switch ( $type ) {

		// Pages
		case 'pages' :
			$pages = get_pages();

			if ( ! empty( $pages ) ) {
				foreach ( $pages as $page ) {
					$select[$page->post_name] = $page->post_title;
				}
			} else {
				$select['null'] = __('No pages exist.', '@@text-domain');
			}
			break;

		// Categories
		case 'categories' :
			$select['all'] = __('<strong>All Categories</strong>', '@@text-domain');

			$categories = get_categories( array( 'hide_empty' => false ) );

			foreach ( $categories as $category ) {
				$select[$category->slug] = $category->name;
			}
			break;

		// Floating Sidebars
		case 'sidebars' :
			$sidebars = get_posts('post_type=tb_sidebar&numberposts=-1');
			if ( ! empty( $sidebars ) ) {
				foreach ( $sidebars as $sidebar ) {
					$location = get_post_meta( $sidebar->ID, 'location', true );
					if ( $location == 'floating' ) {
						$select[$sidebar->post_name] = $sidebar->post_title;
					}
				}
			} // Handle error message for no sidebars outside of this function
			break;

		// All registered widget areas
		case 'sidebars_all' :
			global $wp_registered_sidebars;
			if ( ! empty( $wp_registered_sidebars ) ) {
				foreach ( $wp_registered_sidebars as $sidebar ) {

					// Exclude collapsible widget area locations. It
					// could work to include these, but it's just sort
					// of confusing, conceptually.
					if ( in_array( $sidebar['id'], array( 'ad_above_header', 'ad_above_content', 'ad_below_content', 'ad_below_footer' ) ) ) {
						continue;
					}

					// Remove the word "Location" to avoid confusion,
					// as the concept of locations doesn't really apply
					// in this instance.
					$name = str_replace( __('Location:', '@@text-domain'), __('Default', '@@text-domain'), $sidebar['name'] );

					$select[$sidebar['id']] = $name;
				}
			}
			break;

		// All registered crop sizes
		case 'crop' :

			$registered = get_intermediate_image_sizes();
			$atts = $GLOBALS['_wp_additional_image_sizes'];

			$select['full'] = __('Full Size','@@text-domain');

			foreach ( $registered as $size ) {

				// Skip some sizes
				// if ( in_array( $size, array('thumbnail', 'tb_thumb' ) ) ) {
				// 	continue;
				// }

				// Determine width, height, and crop mode
				if ( isset( $atts[$size]['width'] ) ) {
					$width = intval( $atts[$size]['width'] );
				} else {
					$width = get_option( "{$size}_size_w" );
				}

				if ( isset( $atts[$size]['height'] ) ) {
					$height = intval( $atts[$size]['height'] );
				} else {
					$height = get_option( "{$size}_size_h" );
				}

				if ( isset( $atts[$size]['crop'] ) ) {
					$crop = intval( $atts[$size]['crop'] );
				} else {
					$crop = get_option( "{$size}_crop" );
				}

				// Crop mode message
				if ( $crop ) {
					$crop_desc = __('hard crop', '@@text-domain');
				} else if ( $height == 9999 ) {
					$crop_desc = __('no height crop', '@@text-domain');
				} else {
					$crop_desc = __('soft crop', '@@text-domain');
				}

				// Piece it all together
				$select[$size] = sprintf( '%s (%d x %d, %s)', $size, $width, $height, $crop_desc );

			}
			break;

		// Framework textures
		case 'textures' :

			$textures = themeblvd_get_textures();

			if ( $force_single ) {

				foreach ( $textures as $texture_id => $texture ) {
					$select[$texture_id] = $texture['name'];
				}

			} else {

				$select = array(
					'dark' => array(
						'label' 	=> __('For Darker Background Color', '@@text-domain'),
						'options'	=> array()
					),
					'light' => array(
						'label' 	=> __('For Lighter Background Color', '@@text-domain'),
						'options'	=> array()
					)
				);

				$optgroup = 'dark';

				foreach ( $textures as $texture_id => $texture ) {

					if ( $texture_id == 'divider' ) {
						$optgroup = 'light';
						continue;
					}

					$select[$optgroup]['options'][$texture_id] = $texture['name'];
				}

			}
			break;

		case 'templates' :

			$templates = get_posts('post_type=tb_layout&orderby=title&order=ASC&numberposts=-1');

			if ( $templates ) {
				foreach ( $templates as $template ) {
					$select[$template->post_name] = $template->post_title;
				}
			}
			break;

		case 'authors' :

			$users = get_users();

			if ( $users ) {
				foreach ( $users as $user ) {
					$select[$user->user_login] = sprintf('%s (%s)', $user->display_name, $user->user_login);
				}
			}

	}

	// Put WPML filters back
	if ( isset( $GLOBALS['sitepress'] ) ) {
		add_filter( 'get_pages', array( $GLOBALS['sitepress'], 'exclude_other_language_pages2' ), 10, 2 );
		add_filter( 'get_terms_args', array( $GLOBALS['sitepress'], 'get_terms_args_filter' ), 10, 2 );
		add_filter( 'terms_clauses', array( $GLOBALS['sitepress'], 'terms_clauses' ), 10, 4 );
	}

	return $select;
}

/**
 * All color classes.
 *
 * @since 2.0.0
 *
 * @param boolean $bootstrap Whether to include Bootstrap colors or not
 * @return array $colors All colors in framework filtered
 */
function themeblvd_colors( $bootstrap = true, $custom = true, $default = true ) {

	// Setup colors separated out to begin with.
	$colors = array(
		'default'		=> __('Default Color', '@@text-domain'),
		'custom'		=> __('Custom Color', '@@text-domain'),
	);
	$boostrap_colors = array(
		'primary' 		=> __('Primary', '@@text-domain'),
		'info' 			=> __('Info', '@@text-domain'),
		'success' 		=> __('Success', '@@text-domain'),
		'warning' 		=> __('Warning', '@@text-domain'),
		'danger' 		=> __('Danger', '@@text-domain')
	);
	$themeblvd_colors = array(
		'black' 		=> __('Black', '@@text-domain'),
		'blue' 			=> __('Blue', '@@text-domain'),
		'dark_blue'		=> __('Blue (Dark)', '@@text-domain'),
		'royal'			=> __('Blue (Royal)', '@@text-domain'),
		'steel_blue'	=> __('Blue (Steel)', '@@text-domain'),
		'brown' 		=> __('Brown', '@@text-domain'),
		'dark_brown' 	=> __('Brown (Dark)', '@@text-domain'),
		'slate_grey'	=> __('Grey (Slate)', '@@text-domain'),
		'green' 		=> __('Green', '@@text-domain'),
		'dark_green' 	=> __('Green (Dark)', '@@text-domain'),
		'mauve' 		=> __('Mauve', '@@text-domain'),
		'orange'		=> __('Orange', '@@text-domain'),
		'pearl'			=> __('Pearl', '@@text-domain'),
		'pink'			=> __('Pink', '@@text-domain'),
		'purple'		=> __('Purple', '@@text-domain'),
		'red'			=> __('Red', '@@text-domain'),
		'silver'		=> __('Silver', '@@text-domain'),
		'teal'			=> __('Teal', '@@text-domain'),
		'yellow'		=> __('Yellow', '@@text-domain'),
		'wheat'			=> __('Wheat', '@@text-domain'),
		'white'			=> __('White', '@@text-domain')
	);

	// Merge colors
	if ( $bootstrap ) {
		$colors = array_merge( $colors, $boostrap_colors, $themeblvd_colors );
	} else {
		$colors = array_merge( $colors, $themeblvd_colors );
	}

	if ( ! $custom ) {
		unset($colors['custom']);
	}

	if ( ! $default ) {
		unset($colors['default']);
	}

	return apply_filters( 'themeblvd_colors', $colors, $bootstrap, $custom, $default );
}

/**
 * Load theme text domains.
 *
 * @since 2.2.0
 */
function themeblvd_load_theme_textdomain() {
	load_theme_textdomain( '@@text-domain', get_template_directory() . '/languages' );
}

/**
 * Show theme's image thumb sizes when inserting
 * an image in a post or page.
 *
 * This function gets added as a filter to WP's
 * image_size_names_choose
 *
 * @since 2.1.0
 *
 * @return array Framework's image sizes
 */
function themeblvd_image_size_names_choose( $sizes ) {

	// Get image sizes for framework that were registered.
	$tb_raw_sizes = themeblvd_get_image_sizes();

	// Format sizes
	$tb_sizes = array();
	foreach ( $tb_raw_sizes as $id => $atts ) {
		$tb_sizes[$id] = esc_html($atts['name']);
	}

	// Apply filter - Filter in filter... I know, I know.
	$tb_sizes = apply_filters( 'themeblvd_choose_sizes', $tb_sizes );

	// Return merged with original WP sizes
	return array_merge( $sizes, $tb_sizes );
}

/**
 * Set allowed tags in the admin panel. This works by
 * adding the framework's allowed admin tags to WP's
 * global $allowedtags.
 *
 * @since 2.0.0
 * @uses $allowedposttags
 *
 * @return array $themeblvd_tags Allowed HTML tags for Theme Blvd options sanitation
 */
function themeblvd_allowed_tags() {

	$tags = wp_kses_allowed_html( 'post' );

	$tags['a']['data-bg'] = true;
	$tags['a']['data-bg-hover'] = true;
	$tags['a']['data-text'] = true;
	$tags['a']['data-text-hover'] = true;

	$tags['img']['srcset'] = true;
	$tags['img']['sizes'] = true;

	$tags['iframe'] = array(
		'style' 				=> true,
		'width' 				=> true,
		'height' 				=> true,
		'src' 					=> true,
		'frameborder'			=> true,
		'allowfullscreen' 		=> true,
		'webkitAllowFullScreen'	=> true,
		'mozallowfullscreen' 	=> true
	);

	$tags['script'] = array(
		'type'					=> true,
		'src' 					=> true
	);

	$tags['time'] = array(
		'class'                 => true,
		'datetime'              => true,
	);

	return apply_filters('themeblvd_allowed_tags', $tags);

}

/**
 * Apply wp_kses() to content with framework allowed tags.
 *
 * @since 2.5.2
 *
 * @param array $input Content to sanitize
 * @return string Content that's been sanitized
 */
function themeblvd_kses( $input ) {

	if ( apply_filters('themeblvd_disable_kses', false) ) { // I'd suggest not fucking with this
		return $input;
	}

	return wp_kses( $input, themeblvd_allowed_tags() );
}

/**
 * Generates default column widths for column element.
 *
 * @since 2.0.0
 *
 * @return array $widths All column options with corresponding widths.
 */
function themeblvd_column_widths() {
	$widths = array (
		'1-col' => array (					// User selects 1 columns
			array(
				'name' 	=> '100%',
				'value' => 'grid_12',
			)
		),
		'2-col' => array (					// User selects 2 columns
			array(
				'name' 	=> '20% | 80%',
				'value' => 'grid_fifth_1-grid_fifth_4',
			),
			array(
				'name' 	=> '25% | 75%',
				'value' => 'grid_3-grid_9',
			),
			array(
				'name' 	=> '30% | 70%',
				'value' => 'grid_tenth_3-grid_tenth_7',
			),
			array(
				'name' 	=> '33% | 66%',
				'value' => 'grid_4-grid_8',
			),
			array(
				'name' 	=> '50% | 50%',
				'value' => 'grid_6-grid_6',
			),
			array(
				'name' 	=> '66% | 33%',
				'value' => 'grid_8-grid_4',
			),
			array(
				'name' 	=> '70% | 30%',
				'value' => 'grid_tenth_7-grid_tenth_3',
			),
			array(
				'name' 	=> '75% | 25%',
				'value' => 'grid_9-grid_3',
			),
			array(
				'name' 	=> '80% | 20%',
				'value' => 'grid_fifth_4-grid_fifth_1',
			)
		),
		'3-col' => array (					// User selects 3 columns
			array(
				'name' 	=> '33% | 33% | 33%',
				'value' => 'grid_4-grid_4-grid_4',
			),
			array(
				'name' 	=> '25% | 25% | 50%',
				'value' => 'grid_3-grid_3-grid_6',
			),
			array(
				'name' 	=> '25% | 50% | 25%',
				'value' => 'grid_3-grid_6-grid_3',
			),
			array(
				'name' 	=> '50% | 25% | 25% ',
				'value' => 'grid_6-grid_3-grid_3',
			),
			array(
				'name' 	=> '20% | 20% | 60%',
				'value' => 'grid_fifth_1-grid_fifth_1-grid_fifth_3',
			),
			array(
				'name' 	=> '20% | 60% | 20%',
				'value' => 'grid_fifth_1-grid_fifth_3-grid_fifth_1',
			),
			array(
				'name' 	=> '60% | 20% | 20%',
				'value' => 'grid_fifth_3-grid_fifth_1-grid_fifth_1',
			),
			array(
				'name' 	=> '40% | 40% | 20%',
				'value' => 'grid_fifth_2-grid_fifth_2-grid_fifth_1',
			),
			array(
				'name' 	=> '40% | 20% | 40%',
				'value' => 'grid_fifth_2-grid_fifth_1-grid_fifth_2',
			),
			array(
				'name' 	=> '20% | 40% | 40%',
				'value' => 'grid_fifth_1-grid_fifth_2-grid_fifth_2',
			),
			array(
				'name' 	=> '30% | 30% | 40%',
				'value' => 'grid_tenth_3-grid_tenth_3-grid_fifth_2',
			),
			array(
				'name' 	=> '30% | 40% | 30%',
				'value' => 'grid_tenth_3-grid_fifth_2-grid_tenth_3',
			),
			array(
				'name' 	=> '40% | 30% | 30%',
				'value' => 'grid_fifth_2-grid_tenth_3-grid_tenth_3',
			)
		),
		'4-col' => array (					// User selects 4 columns
			array(
				'name' 	=> '25% | 25% | 25% | 25%',
				'value' => 'grid_3-grid_3-grid_3-grid_3',
			),
			array(
				'name' 	=> '20% | 20% | 20% | 40%',
				'value' => 'grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_2',
			),
			array(
				'name' 	=> '20% | 20% | 40% | 20%',
				'value' => 'grid_fifth_1-grid_fifth_1-grid_fifth_2-grid_fifth_1',
			),
			array(
				'name' 	=> '20% | 40% | 20% | 20%',
				'value' => 'grid_fifth_1-grid_fifth_2-grid_fifth_1-grid_fifth_1',
			),
			array(
				'name' 	=> '40% | 20% | 20% | 20%',
				'value' => 'grid_fifth_2-grid_fifth_1-grid_fifth_1-grid_fifth_1',
			)
		),
		'5-col' => array (						// User selects 5 columns
			array(
				'name' 	=> '20% | 20% | 20% | 20% | 20%',
				'value' => 'grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1',
			)
		)
	);
	return apply_filters( 'themeblvd_column_widths', $widths );
}

/**
 * Setup all possible assignments (i.e. WordPress conditionals)
 * that could be assigned to an item. An example where this is
 * currently used is to assign custom sidebars to various WP
 * conditionals.
 *
 * @since 2.0.0
 *
 * @return array $conditionals Configuration of conditionals to choose from
 */
function themeblvd_conditionals_config() {

	$conditionals = array(
		'pages' => array(
			'id'	=> 'pages',
			'name'	=> __('Pages', '@@text-domain'),
			'empty'	=> __('No pages to display.', '@@text-domain'),
			'field'	=> 'page'
		),
		'posts' => array(
			'id'	=> 'posts',
			'name'	=> __('Posts', '@@text-domain'),
			'empty'	=> __('No posts to display.', '@@text-domain'),
			'field'	=> 'post'
		),
		'posts_in_category' => array(
			'id'	=> 'posts_in_category',
			'name'	=> __('Posts in Category', '@@text-domain'),
			'empty'	=> __('No categories to display.', '@@text-domain'),
			'field'	=> 'posts_in_category'
		),
		'categories' => array(
			'id'	=> 'categories',
			'name'	=> __('Category Archives', '@@text-domain'),
			'empty'	=> __('No categories to display.', '@@text-domain'),
			'field'	=> 'category'
		),
		'tags' => array(
			'id'	=> 'tags',
			'name'	=> __('Tag Archives', '@@text-domain'),
			'empty'	=> __('No tags to display.', '@@text-domain'),
			'field'	=> 'tag'
		),
		'portfolio_items' => array(
			'id'	=> 'portfolio_items',
			'name'	=> __('Single Portfolio Items', '@@text-domain'),
			'empty'	=> __('No portfolio items to display.', '@@text-domain'),
			'field'	=> 'portfolio_item'
		),
		'portfolio_items_in_portfolio' => array(
			'id'	=> 'portfolio_items_in_portfolio',
			'name'	=> __('Items in Portfolio', '@@text-domain'),
			'empty'	=> __('No categories to display.', '@@text-domain'),
			'field'	=> 'portfolio_items_in_portfolio'
		),
		'portfolios' => array(
			'id'	=> 'portfolios',
			'name'	=> __('Portfolios', '@@text-domain'),
			'empty'	=> __('No portfolios to display.', '@@text-domain'),
			'field'	=> 'portfolio'
		),
		'portfolio_tags' => array(
			'id'	=> 'portfolio_tags',
			'name'	=> __('Portfolio Tag Archives', '@@text-domain'),
			'empty'	=> __('No portfolio tags to display.', '@@text-domain'),
			'field'	=> 'portfolio_tag'
		),
		'portfolio_top' => array(
			'id'	=> 'portfolio_top',
			'name'	=> __('Portfolio Hierarchy', '@@text-domain'),
			'empty'	=> null,
			'field'	=> 'portfolio_top',
			'items'	=> array(
				'portfolio_items' 	=> __('All single portfolio items', '@@text-domain'),
				'portfolios' 		=> __('Items displayed by portfolio', '@@text-domain'),
				'portfolio_tags' 	=> __('Items displayed by portfolio tag', '@@text-domain')
			)
		),
		'product_cat' => array(
			'id'	=> 'product_cat',
			'name'	=> __('Product Category Archives', '@@text-domain'),
			'empty'	=> __('No categories to display.', '@@text-domain'),
			'field'	=> 'product_cat'
		),
		'product_tags' => array(
			'id'	=> 'product_tags',
			'name'	=> __('Product Tag Archives', '@@text-domain'),
			'empty'	=> __('No tags to display.', '@@text-domain'),
			'field'	=> 'product_tag'
		),
		'products_in_cat' => array(
			'id'	=> 'products_in_cat',
			'name'	=> __('Products in Category', '@@text-domain'),
			'empty'	=> __('No categories to display.', '@@text-domain'),
			'field'	=> 'products_in_cat'
		),
		'product_top' => array(
			'id'	=> 'product_top',
			'name'	=> __('Product Hierarchy', '@@text-domain'),
			'empty'	=> null,
			'field'	=> 'product_top',
			'items'	=> array(
				'woocommerce' 		=> __('All WooCommerce pages', '@@text-domain'),
				'products' 			=> __('All single products', '@@text-domain'),
				'product_cat' 		=> __('Products displayed by category', '@@text-domain'),
				'product_tag'		=> __('Products displayed by tag', '@@text-domain'),
				'product_search'	=> __('WooCommerce search results', '@@text-domain'),
			)
		),
		'forums' => array(
			'id'	=> 'forums',
			'name'	=> __('Forums', '@@text-domain'),
			'empty'	=> null,
			'field'	=> 'forum'
		),
		'forum_top' => array(
			'id'	=> 'forum_top',
			'name'	=> __('Forum Hierarchy', '@@text-domain'),
			'empty'	=> null,
			'field'	=> 'forum_top',
			'items'	=> array(
				'bbpress' 			=> __('All bbPress pages', '@@text-domain'),
				'topic' 			=> __('All single topics', '@@text-domain'),
				'forum' 			=> __('All single forums', '@@text-domain'),
				'topic_tag' 		=> __('Viewing topics by tag', '@@text-domain'),
				'forum_user'		=> __('Public user profiles', '@@text-domain'),
				'forum_user_home'	=> __('Logged-in user home', '@@text-domain')
			)
		),
		'top' => array(
			'id'	=> 'top',
			'name'	=> __('Hierarchy', '@@text-domain'),
			'empty'	=> null,
			'field'	=> 'top',
			'items'	=> array(
				'home' 				=> __('Homepage', '@@text-domain'),
				'posts' 			=> __('All posts (any post type)', '@@text-domain'),
				'blog_posts' 		=> __('All blog posts', '@@text-domain'),
				'pages' 			=> __('All pages', '@@text-domain'),
				'archives' 			=> __('All archives', '@@text-domain'),
				'categories'		=> __('All category archives', '@@text-domain'),
				'tags' 				=> __('All tag archives', '@@text-domain'),
				'authors' 			=> __('All author archives', '@@text-domain'),
				'search' 			=> __('Search Results', '@@text-domain'),
				'404' 				=> __('404 Page', '@@text-domain')
			)
		),
		'custom' => array(
			'id'	=> 'custom',
			'name'	=> __('Custom', '@@text-domain'),
			'empty'	=> null,
			'field'	=> 'custom'
		)
	);

	if ( ! themeblvd_installed('portfolios') ) {
		unset( $conditionals['portfolio_items'], $conditionals['portfolio_items_in_portfolio'], $conditionals['portfolios'], $conditionals['portfolio_tags'], $conditionals['portfolio_top'] );
	}

	if ( ! themeblvd_installed('woocommerce') ) {
		unset( $conditionals['product_cat'], $conditionals['product_tags'], $conditionals['products_in_cat'], $conditionals['product_top'] );
	}

	if ( ! themeblvd_installed('bbpress') ) {
		unset( $conditionals['forums'], $conditionals['forum_top'] );
	}

	return apply_filters( 'themeblvd_conditionals_config', $conditionals );
}

/**
 * Determine if valid lightbox URL. If this is not a valid
 * lightbox URL, return false. If it is valid return an icon
 * type that can be associated with it.
 *
 * @since 2.3.0
 *
 * @param string $url URL string to check
 * @return string $icon Type of URL (video or image) or blank if URL not supported
 */
function themeblvd_is_lightbox_url( $url ) {

	$icon = false;

	if ( $url ) {

		if ( strpos( $url, 'vimeo.com' ) !== false ) { // Vimeo video?

			$icon = 'video';

		} else if ( strpos( $url, 'youtube.com' ) !== false ) { // YouTube video?

			$icon = 'video';

		} else if ( strpos( $url, 'maps.google.com' ) !== false ) { // Google map?

			$icon = 'image';

		} else if ( strpos( $url, '#' ) === 0 ) { // Inline content?

			$icon = 'image';

		} else { // Image?

			$parsed_url = parse_url( $url );

			if ( ! empty( $parsed_url['path'] ) ) {

				$type = wp_check_filetype( $parsed_url['path'] );

				if ( substr( $type['type'], 0, 5 ) == 'image' ) {

					$icon = 'image';

				}
			}
		}

	}

	return apply_filters( 'themeblvd_is_lightbox_url', $icon, $url );
}

/**
 * Get social media sources and their respective names.
 *
 * @since 2.3.0
 *
 * @return array $sources All social media buttons
 */
function themeblvd_get_social_media_sources() {

 	$sources = array(
		'chat' 				=> __('General: Chat', '@@text-domain'), // fa: comments
		'cloud' 			=> __('General: Cloud', '@@text-domain'),
		'anchor' 			=> __('General: Link', '@@text-domain'), // fa: link
		'email' 			=> __('General: Mail', '@@text-domain'), // fa: envelope
		'movie' 			=> __('General: Movie', '@@text-domain'), // fa: film
		'music' 			=> __('General: Music', '@@text-domain'),
		'portfolio' 		=> __('General: Portfolio', '@@text-domain'), // fa: briefcase
 		'rss' 				=> __('General: RSS', '@@text-domain'),
 		'store' 			=> __('General: Store', '@@text-domain'), // fa: shopping-cart
		'write' 			=> __('General: Write', '@@text-domain'), // fa: pencil
 		'android' 			=> __('Android', '@@text-domain'),
 		'apple' 			=> __('Apple', '@@text-domain'),
 		'behance' 			=> __('Behance', '@@text-domain'),
 		'codepen' 			=> __('CodePen', '@@text-domain'),
 		'delicious' 		=> __('Delicious', '@@text-domain'),
 		'deviantart'		=> __('DeviantArt', '@@text-domain'),
 		'digg' 				=> __('Digg', '@@text-domain'),
 		'dribbble' 			=> __('Dribbble', '@@text-domain'),
 		'dropbox' 			=> __('Dropbox', '@@text-domain'),
 		'facebook' 			=> __('Facebook', '@@text-domain'),
 		'flickr' 			=> __('Flickr', '@@text-domain'),
 		'foursquare' 		=> __('Foursquare', '@@text-domain'),
 		'github' 			=> __('GitHub', '@@text-domain'), // fa: github-alt
 		'google' 			=> __('Google+', '@@text-domain'), // fa: google-plus
 		'instagram'			=> __('Instagram', '@@text-domain'),
 		'linkedin'			=> __('LinkedIn', '@@text-domain'),
 		'paypal'			=> __('PayPal', '@@text-domain'),
 		'pinterest'			=> __('Pinterest', '@@text-domain'), // fa: pinterest-p
 		'reddit'			=> __('Reddit', '@@text-domain'),
 		'slideshare'		=> __('SlideShare', '@@text-domain'),
 		'soundcloud'		=> __('SoundCloud', '@@text-domain'),
 		'stumbleupon' 		=> __('StumbleUpon', '@@text-domain'),
 		'tumblr' 			=> __('Tumblr', '@@text-domain'),
 		'twitter' 			=> __('Twitter', '@@text-domain'),
 		'vimeo' 			=> __('Vimeo', '@@text-domain'), // fa: vimeo-square
 		'vine' 				=> __('Vine', '@@text-domain'),
 		'windows' 			=> __('Windows', '@@text-domain'),
 		'wordpress' 		=> __('WordPress', '@@text-domain'),
 		'xing' 				=> __('XING', '@@text-domain'),
 		'yahoo' 			=> __('Yahoo', '@@text-domain'),
 		'youtube'			=> __('YouTube', '@@text-domain')
	);

 	// Backwards compat filter
 	$sources = apply_filters( 'themeblvd_social_media_buttons', $sources );

	return apply_filters( 'themeblvd_social_media_sources', $sources );
}

/**
 * Get social media share sources.
 *
 * @since 2.5.0
 *
 * @return array $sources All social media buttons
 */
function themeblvd_get_share_sources() {

	$sources = array(
		'digg' 			=> __('Digg', '@@text-domain'),
		'email' 		=> __('Email', '@@text-domain'),
		'facebook' 		=> __('Facebook', '@@text-domain'),
		'google' 		=> __('Google+', '@@text-domain'),
		'linkedin' 		=> __('Linkedin', '@@text-domain'),
		// 'wordpress' 	=> __('Press This', '@@text-domain'),
		'pinterest' 	=> __('Pinterest', '@@text-domain'),
		'reddit' 		=> __('Reddit', '@@text-domain'),
		'tumblr' 		=> __('Tumblr', '@@text-domain'),
		'twitter' 		=> __('Twitter', '@@text-domain')
	);

	return apply_filters( 'themeblvd_share_sources', $sources );
}

/**
 * Get social media share patterns.
 *
 * @since 2.5.0
 *
 * @return array $sources All social media buttons
 */
function themeblvd_get_share_patterns() {

	$patterns = array(
		'digg' => array(
			'pattern'		=> 'http://digg.com/submit?url=[permalink]&title=[title]',
			'encode'		=> true,
			'encode_urls' 	=> false,
			'icon'			=> 'digg'
		),
		'email' => array(
			'pattern'		=> 'mailto:?subject=[title]&amp;body=[permalink]',
			'encode'		=> true,
			'encode_urls' 	=> false,
			'icon'			=> 'envelope-o'
		),
		'facebook' => array(
			'pattern'		=> 'http://www.facebook.com/sharer.php?u=[permalink]&amp;t=[title]',
			'encode'		=> true,
			'encode_urls' 	=> false,
			'icon'			=> 'facebook'
		),
		'google' => array(
			'pattern'		=> 'https://plus.google.com/share?url=[permalink]',
			'encode'		=> true,
			'encode_urls' 	=> false,
			'icon'			=> 'google-plus'
		),
		'linkedin' => array(
			'pattern'		=> 'http://linkedin.com/shareArticle?mini=true&amp;url=[permalink]&amp;title=[title]',
			'encode'		=> true,
			'encode_urls' 	=> false,
			'icon'			=> 'linkedin'
		),
		'pinterest' => array(
			'pattern'		=> 'http://pinterest.com/pin/create/button/?url=[permalink]&amp;description=[title]&amp;media=[thumbnail]',
			'encode'		=> true,
			'encode_urls' 	=> true,
			'icon'			=> 'pinterest-p'
		),
		'reddit' => array(
			'pattern'		=> 'http://reddit.com/submit?url=[permalink]&amp;title=[title]',
			'encode'		=> true,
			'encode_urls' 	=> false,
			'icon'			=> 'reddit'
		),
		'tumblr' => array(
			'pattern'		=> 'http://www.tumblr.com/share/link?url=[permalink]&amp;name=[title]&amp;description=[excerpt]',
			'encode'		=> true,
			'encode_urls' 	=> true,
			'icon'			=> 'tumblr'
		),
		'twitter' => array(
			'pattern'		=> 'http://twitter.com/home?status=[title] [shortlink]',
			'encode'		=> true,
			'encode_urls' 	=> false,
			'icon'			=> 'twitter'
		)
		/*
		'wordpress' => array(
			'pattern'		=> '[site_url]/wp-admin/press-this.php?u=[permalink]&amp;t=[title]&amp;v=4',
			'encode'		=> true,
			'encode_urls' 	=> true,
			'icon'			=> 'wordpress'
		)
		*/
	);

	return apply_filters( 'themeblvd_share_patterns', $patterns );
}

/**
 * Find all instances of image URL strings within
 * 	a text block.
 *
 * @since 2.5.0
 *
 * @param string $str Text string to search for images within
 * @return array $images Array of Image URL's
 */
function themeblvd_get_img_urls( $str ) {

	$protocols = array('http://', 'https://');
	$extentions = array('.gif', '.jpeg', '.jpg', '.png');
	$images = array();

	foreach ( $protocols as $protocol ) {
		foreach ( $extentions as $ext ) {
			$pattern = sprintf( '/%s(.*?)%s/', preg_quote( $protocol, '/'), preg_quote( $ext, '/') );
			preg_match_all( $pattern, $str, $img );
			$images = array_merge( $images, $img[0] );
		}
	}

	return $images;
}

/**
 * Check if we're using a certain version of IE
 *
 * @since 2.5.0
 *
 * @param array $ver Version ofs IE to check for
 * @return bool Whether or not this is IE
 */
function themeblvd_is_ie( $versions = array('8') ) {

	if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {

		foreach ( $versions as $ver ) {
			if ( preg_match( "/MSIE ".$ver.".0/", $_SERVER['HTTP_USER_AGENT'] ) ) {
				return true;
			}
		}
	}

	return false;
}