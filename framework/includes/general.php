<?php
if ( !function_exists( 'themeblvd_include_google_fonts' ) ) :
/**
 * Include font from google. Accepts unlimited
 * amount of font arguments.
 *
 * @since 2.0.0
 */
function themeblvd_include_google_fonts() {

	$fonts = func_get_args();
	$used = array();

	if ( ! empty( $fonts ) ) {

		// Before including files, determine if SSL is being
		// used because if we include an external file without https
		// on a secure server, they'll get an error.
		$protocol = is_ssl() ? 'https://' : 'http://';

		// Include each font file from google.
		foreach ( $fonts as $font ) {
			if ( $font['face'] == 'google' && $font['google'] ) {

				if ( in_array( $font['google'], $used ) ) {
					// Skip duplicate
					continue;
				}

				$used[] = $font['google'];
				$name = themeblvd_remove_trailing_char( $font['google'] );
				$name = str_replace( ' ', '+', $name );
				printf( '<link href="%sfonts.googleapis.com/css?family=%s" rel="stylesheet" type="text/css">'."\n", $protocol, $name );

			}
		}

	}
}
endif;

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
			'sliders' 			=> true,			// Sliders page
			'builder'			=> true,			// Layouts page
			'sidebars'			=> true,			// Sidebars page
			'updates'			=> true				// Updates (if theme supports)
		),
		'meta' => array(
			'hijack_atts'		=> true,			// Hijack and modify "Page Attributes"
			'page_options'		=> true,			// Meta box for basic page options
			'post_options'		=> true				// Meta box for basic post options
		),
		'featured' => array(
			'archive'			=> false,			// Featured area on/off by default
			'blog'				=> false,			// Featured area on/off by default
			'grid'				=> false,			// Featured area on/off by default
			'page'				=> false,			// Featured area on/off by default
			'single'			=> false			// Featured area on/off by default
		),
		'featured_below' => array(
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
			'responsive' 		=> true				// Responsive elements
		),
		'assets' => array(
			'primary_js'		=> true,			// Primary "themeblvd" script
			'primary_css'		=> true,			// Primary "themeblvd" stylesheet
			'flexslider'		=> true,			// Flexslider script by WooThemes
			'roundabout'		=> true,			// Roundabout script by FredHQ
			'nivo'				=> true,			// Nivo script by Dev7studios
			'bootstrap'			=> true,			// "bootstrap" script/stylesheet
			'magnific_popup'	=> true,			// "magnific_popup" script/stylesheet
			'superfish'			=> true,			// "superfish" script
			'ios_orientation'	=> false			// "ios-orientationchange-fix" script
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
		if ( ! is_null( $message ) ) {

			trigger_error( sprintf( __( '%1$s is <strong>deprecated</strong> since version %2$s of the Theme Blvd framework! %3$s', 'themeblvd' ), $function, $version, $message ) );

		} elseif ( ! is_null( $replacement ) ) {

			trigger_error( sprintf( __( '%1$s is <strong>deprecated</strong> since version %2$s of the Theme Blvd framework! Use %3$s instead.', 'themeblvd' ), $function, $version, $replacement ) );

		} else {

			trigger_error( sprintf( __( '%1$s is <strong>deprecated</strong> since version %2$s of the Theme Blvd framework with no alternative available.', 'themeblvd' ), $function, $version ) );

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
		'sliders' 	=> 'edit_theme_options',		// Role: Administrator
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

if ( !function_exists( 'themeblvd_post_id_by_name' ) ) :
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
endif;

if ( !function_exists( 'themeblvd_register_navs' ) ) :
/**
 * Register theme's nav menus.
 *
 * @since 2.0.0
 */
function themeblvd_register_navs() {

	// Setup nav menus
	$menus = array(
		'primary' => __( 'Primary Navigation', 'themeblvd' ),
		'footer' => __( 'Footer Navigation', 'themeblvd' )
	);
	$menus = apply_filters( 'themeblvd_nav_menus', $menus );

	// Register nav menus with WP
	register_nav_menus( $menus );

}
endif;

if ( !function_exists( 'themeblvd_add_theme_support' ) ) :
/**
 * Any occurances of WordPress's add_theme_support() happen here.
 * Can override function from Child Theme.
 *
 * @since 2.0.0
 */
function themeblvd_add_theme_support() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
}
endif;

/**
 * Get current page identifiers and keys for what we consider
 * admin modules. By default, this includeds:
 * 1) Theme Options
 * 2) Layout Builder 	(plugin)
 * 3) Widget Areas 		(plugin)
 * 4) Sliders 			(plugin)
 *
 * @since 2.3.0
 */
function themeblvd_get_admin_modules() {

	// Options page
	$api = Theme_Blvd_Options_API::get_instance();
	$args = $api->get_args();
	$options_page = sprintf( '%s?page=%s', $args['parent'], $args['menu_slug'] );

	// Admin modules
	$modules = array(
		'options'	=> $options_page,
		'builder'	=> 'admin.php?page=themeblvd_builder',
		'sidebars'	=> 'themes.php?page=themeblvd_widget_areas',
		'sliders'	=> 'admin.php?page=themeblvd_sliders',
	);

	return apply_filters( 'themeblvd_admin_modules', $modules );
}

if ( !function_exists( 'themeblvd_admin_menu_bar' ) ) :
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
				'title'			=> __( 'Theme Options', 'themeblvd' ),
				'parent'		=> 'site-name',
				'href'			=> admin_url( $modules['options'] )
			)
		);
	}

	// Sliders (if sliders plugin is installed)
	if ( defined( 'TB_SLIDERS_PLUGIN_VERSION' ) && isset( $modules['sliders'] ) ) {
		if ( themeblvd_supports( 'admin', 'sliders' ) && current_user_can( themeblvd_admin_module_cap( 'sliders' ) ) ) {
			$wp_admin_bar->add_node(
				array(
					'id'		=> 'tb_sliders',
					'title'		=> __( 'Sliders', 'themeblvd' ),
					'parent'	=> 'site-name',
					'href'		=> admin_url( $modules['sliders'] )
				)
			);
		}
	}

	// Builder (if layout builder plugin is installed)
	if ( defined( 'TB_BUILDER_PLUGIN_VERSION' ) && isset( $modules['builder'] ) ) {
		if ( themeblvd_supports( 'admin', 'builder' ) && current_user_can( themeblvd_admin_module_cap( 'builder' ) ) ) {
			$wp_admin_bar->add_node(
				array(
					'id'		=> 'tb_builder',
					'title'		=> __( 'Layout Builder', 'themeblvd' ),
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
					'title'		=> __( 'Widget Areas', 'themeblvd' ),
					'parent'	=> 'site-name',
					'href' 		=> admin_url( $modules['sidebars'] )
				)
			);
		}
	}
}
endif;

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
	// is before stacking the columns. By using "sm"
	// we are having columns drop responsively at the
	// 767px or less (i.e. mobile viewports).
	$size = 'sm';

	// ... And then because old versions of IE are horrible,
	// they do not accurately know the viewport size.

	// So, is this IE8?
	if ( preg_match( "/MSIE 8.0/", $_SERVER[ 'HTTP_USER_AGENT' ] ) ) {

		// If this is IE8, change the size
		// to "xs" as a fail-safe. This is okay because
		// responsive behavior here is irrelvant, anyway.
		$size = 'xs';

	}

	$layouts = array(
		'full_width' => array(
			'name' 		=> 'Full Width',
			'id'		=> 'full_width',
			'columns'	=> array(
				'content' 	=> "col-{$size}-12",
				'left' 		=> '',
				'right' 	=> ''
			)
		),
		'sidebar_right' => array(
			'name' 		=> 'Sidebar Right',
			'id'		=> 'sidebar_right',
			'columns'	=> array(
				'content' 	=> "col-{$size}-8",
				'left' 		=> '',
				'right' 	=> "col-{$size}-4"
			)
		),
		'sidebar_left' => array(
			'name' 		=> 'Sidebar Left',
			'id'		=> 'sidebar_left',
			'columns'	=> array(
				'content' 	=> "col-{$size}-8",
				'left' 		=> "col-{$size}-4",
				'right' 	=> ''
			)
		),
		'double_sidebar' => array(
			'name' 		=> 'Double Sidebar',
			'id'		=> 'double_sidebar',
			'columns'	=> array(
				'content' 	=> "col-{$size}-6",
				'left' 		=> "col-{$size}-3",
				'right' 	=> "col-{$size}-3"
			)
		),
		'double_sidebar_left' => array(
			'name' 		=> 'Double Left Sidebars',
			'id'		=> 'double_sidebar_left',
			'columns'	=> array(
				'content' 	=> "col-{$size}-6",
				'left' 		=> "col-{$size}-3",
				'right' 	=> "col-{$size}-3"
			)
		),
		'double_sidebar_right' => array(
			'name' 		=> 'Double Right Sidebars',
			'id'		=> 'double_sidebar_right',
			'columns'	=> array(
				'content' 	=> "col-{$size}-6",
				'left' 		=> "col-{$size}-3",
				'right' 	=> "col-{$size}-3"
			)
		)
	);
	return apply_filters( 'themeblvd_sidebar_layouts', $layouts );
}

/**
 * Get class used to determine width of column in primary layout.
 *
 * @since 2.2.0
 *
 * @param string $column Which column to retrieve class for
 * @return string $column_class The class to be used in grid system
 */
function themeblvd_get_column_class( $column ) {

	$column_class = '';
	$sidebar_layouts = themeblvd_sidebar_layouts();
	$current_sidebar_layout = themeblvd_config( 'sidebar_layout' );

	if ( isset( $sidebar_layouts[$current_sidebar_layout]['columns'][$column] ) ) {
		$column_class = $sidebar_layouts[$current_sidebar_layout]['columns'][$column];
	}

	return apply_filters( 'themeblvd_column_class', $column_class );
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
	$imagepath = apply_filters( 'themeblvd_textures_img_path', get_template_directory_uri().'/framework/assets/images/textures/' );
	$textures = array(
		'boxy' => array(
			'name' 		=> __( 'Boxy', 'themeblvd' ),
			'url' 		=> $imagepath.'boxy.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'carbon_classic' => array(
			'name' 		=> __( 'Carbon Classic', 'themeblvd' ),
			'url' 		=> $imagepath.'carbon_classic.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'carbon_diagonal' => array(
			'name' 		=> __( 'Carbon Diagonal', 'themeblvd' ),
			'url' 		=> $imagepath.'carbon_diagonal.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'carbon_weave' => array(
			'name' 		=> __( 'Carbon Weave', 'themeblvd' ),
			'url' 		=> $imagepath.'carbon_weave.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'chex' => array(
			'name' 		=> __( 'Chex', 'themeblvd' ),
			'url' 		=> $imagepath.'chex.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'concrete' => array(
			'name' 		=> __( 'Concrete', 'themeblvd' ),
			'url' 		=> $imagepath.'concrete.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'cross' => array(
			'name' 		=> __( 'Crosses', 'themeblvd' ),
			'url' 		=> $imagepath.'cross.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'denim' => array(
			'name' 		=> __( 'Denim', 'themeblvd' ),
			'url' 		=> $imagepath.'denim.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'diagnol_thin' => array(
			'name' 		=> __( 'Diagonal (thin)', 'themeblvd' ),
			'url' 		=> $imagepath.'diagnol_thin.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'diagnol_thick' => array(
			'name' 		=> __( 'Diagonal (thick)', 'themeblvd' ),
			'url' 		=> $imagepath.'diagnol_thick.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'diamonds' => array(
			'name' 		=> __( 'Diamonds', 'themeblvd' ),
			'url' 		=> $imagepath.'diamonds.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'grid' => array(
			'name' 		=> __( 'Grid', 'themeblvd' ),
			'url' 		=> $imagepath.'grid.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'grunge' => array(
			'name' 		=> __( 'Grunge', 'themeblvd' ),
			'url' 		=> $imagepath.'grunge.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'honey_comb' => array(
			'name' 		=> __( 'Honey Comb', 'themeblvd' ),
			'url' 		=> $imagepath.'honey_comb.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'leather' => array(
			'name' 		=> __( 'Leather', 'themeblvd' ),
			'url' 		=> $imagepath.'leather.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'metal' => array(
			'name' 		=> __( 'Metal', 'themeblvd' ),
			'url' 		=> $imagepath.'metal.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'mosaic' => array(
			'name' 		=> __( 'Mosaic', 'themeblvd' ),
			'url' 		=> $imagepath.'mosaic.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'noise' => array(
			'name' 		=> __( 'Noise', 'themeblvd' ),
			'url' 		=> $imagepath.'noise.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'paper' => array(
			'name' 		=> __( 'Paper', 'themeblvd' ),
			'url' 		=> $imagepath.'paper.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'plaid' => array(
			'name' 		=> __( 'Plaid', 'themeblvd' ),
			'url' 		=> $imagepath.'plaid.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'rubber' => array(
			'name' 		=> __( 'Rubber', 'themeblvd' ),
			'url' 		=> $imagepath.'rubber.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'squares' => array(
			'name' 		=> __( 'Squares', 'themeblvd' ),
			'url' 		=> $imagepath.'squares.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'textile' => array(
			'name' 		=> __( 'Textile', 'themeblvd' ),
			'url' 		=> $imagepath.'textile.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'vertical_fabric' => array(
			'name' 		=> __( 'Vertical Fabric', 'themeblvd' ),
			'url' 		=> $imagepath.'vertical_fabric.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'vintage' => array(
			'name' 		=> __( 'Vintage', 'themeblvd' ),
			'url' 		=> $imagepath.'vintage.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'wood' => array(
			'name' 		=> __( 'Wood', 'themeblvd' ),
			'url' 		=> $imagepath.'wood.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'wood_planks' => array(
			'name' 		=> __( 'Wood Planks', 'themeblvd' ),
			'url' 		=> $imagepath.'wood_planks.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'divider' => array(
			'name' 		=> __( '---------------', 'themeblvd' ),
			'url' 		=> null,
			'position' 	=> null,
			'repeat' 	=> null,
		),
		'boxy_light' => array(
			'name' 		=> __( 'Light Boxy', 'themeblvd' ),
			'url' 		=> $imagepath.'boxy_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'carbon_classic_light' => array(
			'name' 		=> __( 'Light Carbon Classic', 'themeblvd' ),
			'url' 		=> $imagepath.'carbon_classic_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'carbon_diagonal_light' => array(
			'name' 		=> __( 'Light Carbon Diagonal', 'themeblvd' ),
			'url' 		=> $imagepath.'carbon_diagonal_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'carbon_weave_light' => array(
			'name' 		=> __( 'Light Carbon Weave', 'themeblvd' ),
			'url' 		=> $imagepath.'carbon_weave_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'chex_light' => array(
			'name' 		=> __( 'Light Chex', 'themeblvd' ),
			'url' 		=> $imagepath.'chex_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'concrete_light' => array(
			'name' 		=> __( 'Light Concrete', 'themeblvd' ),
			'url' 		=> $imagepath.'concrete_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'cross_light' => array(
			'name' 		=> __( 'Light Crosses', 'themeblvd' ),
			'url' 		=> $imagepath.'cross_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'denim_light' => array(
			'name' 		=> __( 'Light Denim', 'themeblvd' ),
			'url' 		=> $imagepath.'denim_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'diagnol_thin_light' => array(
			'name' 		=> __( 'Light Diagonal (thin)', 'themeblvd' ),
			'url' 		=> $imagepath.'diagnol_thin_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'diagnol_thick_light' => array(
			'name' 		=> __( 'Light Diagonal (thick)', 'themeblvd' ),
			'url' 		=> $imagepath.'diagnol_thick_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'diamonds_light' => array(
			'name' 		=> __( 'Light Diamonds', 'themeblvd' ),
			'url' 		=> $imagepath.'diamonds_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'grid_light' => array(
			'name' 		=> __( 'Light Grid', 'themeblvd' ),
			'url' 		=> $imagepath.'grid_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'grunge_light' => array(
			'name' 		=> __( 'Light Grunge', 'themeblvd' ),
			'url' 		=> $imagepath.'grunge_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'honey_comb_light' => array(
			'name' 		=> __( 'Light Honey Comb', 'themeblvd' ),
			'url' 		=> $imagepath.'honey_comb_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'leather_light' => array(
			'name' 		=> __( 'Light Leather', 'themeblvd' ),
			'url' 		=> $imagepath.'leather_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'metal_light' => array(
			'name' 		=> __( 'Light Metal', 'themeblvd' ),
			'url' 		=> $imagepath.'metal_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'mosaic_light' => array(
			'name' 		=> __( 'Light Mosaic', 'themeblvd' ),
			'url' 		=> $imagepath.'mosaic_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'noise_light' => array(
			'name' 		=> __( 'Light Noise', 'themeblvd' ),
			'url' 		=> $imagepath.'noise_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'paper_light' => array(
			'name' 		=> __( 'Light Paper', 'themeblvd' ),
			'url' 		=> $imagepath.'paper_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'plaid_light' => array(
			'name' 		=> __( 'Light Plaid', 'themeblvd' ),
			'url' 		=> $imagepath.'plaid_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'rubber_light' => array(
			'name' 		=> __( 'Light Rubber', 'themeblvd' ),
			'url' 		=> $imagepath.'rubber_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'squares_light' => array(
			'name' 		=> __( 'Light Squares', 'themeblvd' ),
			'url' 		=> $imagepath.'squares_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'textile_light' => array(
			'name' 		=> __( 'Light Textile', 'themeblvd' ),
			'url' 		=> $imagepath.'textile_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'vertical_fabric_light' => array(
			'name' 		=> __( 'Light Vertical Fabric', 'themeblvd' ),
			'url' 		=> $imagepath.'vertical_fabric_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'vintage_light' => array(
			'name' 		=> __( 'Light Vintage', 'themeblvd' ),
			'url' 		=> $imagepath.'vintage_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'wood_light' => array(
			'name' 		=> __( 'Light Wood', 'themeblvd' ),
			'url' 		=> $imagepath.'wood_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'wood_planks_light' => array(
			'name' 		=> __( 'Light Wood Planks', 'themeblvd' ),
			'url' 		=> $imagepath.'wood_planks_light.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		)

	);
	return apply_filters( 'themeblvd_textures', $textures );
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
function themeblvd_get_select( $type ) {

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
			$pages_select = array();
			$pages = get_pages();

			if ( ! empty( $pages ) ) {
				foreach ( $pages as $page ) {
					$select[$page->post_name] = $page->post_title;
				}
			} else {
				$select['null'] = __( 'No pages exist.', 'themeblvd' );
			}
			break;

		// Categories
		case 'categories' :
			$select['all'] = __( '<strong>All Categories</strong>', 'themeblvd' );

			$categories = get_categories( array( 'hide_empty' => false ) );

			foreach ( $categories as $category ) {
				$select[$category->slug] = $category->name;
			}
			break;

		// Sliders
		case 'sliders' :
			$sliders = get_posts( 'post_type=tb_slider&numberposts=-1' );
			if ( ! empty( $sliders ) ) {
				foreach ( $sliders as $slider ) {
					$select[$slider->post_name] = $slider->post_title;
				}
			} else {
				$select['null'] = __( 'You haven\'t created any custom sliders yet.', 'themeblvd' );
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

	}

	// Put WPML filters back
	if ( isset( $GLOBALS['sitepress'] ) ) {
		add_filter( 'get_pages', array( $GLOBALS['sitepress'], 'exclude_other_language_pages2' ) );
		add_filter( 'get_terms_args', array( $GLOBALS['sitepress'], 'get_terms_args_filter' ) );
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
function themeblvd_colors( $bootstrap = true ) {

	// Setup colors separated out to begin with.
	$colors = array(
		'default'		=> __( 'Default Color', 'themeblvd' )
	);
	$boostrap_colors = array(
		'primary' 		=> __( 'Bootstrap: Primary', 'themeblvd' ),
		'info' 			=> __( 'Bootstrap: Info', 'themeblvd' ),
		'success' 		=> __( 'Bootstrap: Success', 'themeblvd' ),
		'warning' 		=> __( 'Bootstrap: Warning', 'themeblvd' ),
		'danger' 		=> __( 'Bootstrap: Danger', 'themeblvd' )
	);
	$themeblvd_colors = array(
		'black' 		=> __( 'Black', 'themeblvd' ),
		'blue' 			=> __( 'Blue', 'themeblvd' ),
		'brown' 		=> __( 'Brown', 'themeblvd' ),
		'dark_blue'		=> __( 'Dark Blue', 'themeblvd' ),
		'dark_brown' 	=> __( 'Dark Brown', 'themeblvd' ),
		'dark_green' 	=> __( 'Dark Green', 'themeblvd' ),
		'green' 		=> __( 'Green', 'themeblvd' ),
		'mauve' 		=> __( 'Mauve', 'themeblvd' ),
		'orange'		=> __( 'Orange', 'themeblvd' ),
		'pearl'			=> __( 'Pearl', 'themeblvd' ),
		'pink'			=> __( 'Pink', 'themeblvd' ),
		'purple'		=> __( 'Purple', 'themeblvd' ),
		'red'			=> __( 'Red', 'themeblvd' ),
		'slate_grey'	=> __( 'Slate Grey', 'themeblvd' ),
		'silver'		=> __( 'Silver', 'themeblvd' ),
		'steel_blue'	=> __( 'Steel Blue', 'themeblvd' ),
		'teal'			=> __( 'Teal', 'themeblvd' ),
		'yellow'		=> __( 'Yellow', 'themeblvd' ),
		'wheat'			=> __( 'Wheat', 'themeblvd' ),
		'white'			=> __( 'White', 'themeblvd' )
	);

	// Merge colors
	if ( $bootstrap ) {
		$colors = array_merge( $colors, $boostrap_colors, $themeblvd_colors );
	} else {
		$colors = array_merge( $colors, $themeblvd_colors );
	}

	return apply_filters( 'themeblvd_colors', $colors, $bootstrap );
}

/**
 * Stats
 *
 * @since 2.1.0
 */
function themeblvd_stats() {

	// API Key
	$api_key = 'y0zr2c64abc1qvebamzpnk4m3izccxpxxlfh';

	// Start of Metrics
	global $wpdb;
	$data = get_transient( 'presstrends_data' );

	if ( ! $data || $data == '' ) {

		$api_base = 'http://api.presstrends.io/index.php/api/sites/update/api/';
		$url = $api_base . $api_key . '/';

		// Theme Data (by Jason)
		$data = array();
		$theme_data = wp_get_theme( get_template() );
		$data['theme_name'] = str_replace( ' ', '', $theme_data->get('Name') ); // remove spaces to fix presstrend's bug
		$data['theme_version'] = str_replace( ' ', '', $theme_data->get('Version') ); // remove spaces to fix presstrend's bug

		// Continue on ...
		$count_posts = wp_count_posts();
		$count_pages = wp_count_posts('page');
		$comments_count = wp_count_comments();
		//$theme_data = get_theme_data(get_stylesheet_directory() . '/style.css');
		$plugin_count = count(get_option('active_plugins'));
		$all_plugins = get_plugins();
		$plugin_name = ''; // (added by Jason)

		foreach ( $all_plugins as $plugin_file => $plugin_data ) {
			$plugin_name .= $plugin_data['Name'];
			$plugin_name .= '&';
		}

		$posts_with_comments = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}posts WHERE post_type='post' AND comment_count > 0");

		// fix by Jason
		$comments_to_posts = 0;
		if ( $count_posts->publish > 0  ) {
			$comments_to_posts = number_format(($posts_with_comments / $count_posts->publish) * 100, 0, '.', '');
		}

		$pingback_result = $wpdb->get_var('SELECT COUNT(comment_ID) FROM '.$wpdb->comments.' WHERE comment_type = "pingback"');
		$data['url'] = stripslashes(str_replace(array('http://', '/', ':' ), '', site_url()));
		$data['posts'] = $count_posts->publish;
		$data['pages'] = $count_pages->publish;
		$data['comments'] = $comments_count->total_comments;
		$data['approved'] = $comments_count->approved;
		$data['spam'] = $comments_count->spam;
		$data['pingbacks'] = $pingback_result;
		$data['post_conversion'] = $comments_to_posts;
		//$data['theme_version'] = $theme_data['Version'];
		//$data['theme_name'] = $theme_data['Name'];
		$data['site_name'] = str_replace( ' ', '', get_bloginfo( 'name' ));
		$data['plugins'] = $plugin_count;
		$data['plugin'] = urlencode($plugin_name);
		$data['wpversion'] = get_bloginfo('version');

		foreach ( $data as $k => $v ) {
			$url .= $k . '/' . $v . '/';
		}

		// Manually set theme name to avoid confusion with feed
		if ( defined( 'TB_THEME_NAME' ) ) {
			$data['theme_name'] = TB_THEME_NAME;
		}

		// Send response and set transient
		$response = wp_remote_get( $url );
		set_transient('presstrends_data', $data, 60*60*24); // 1 day transient

	}
}

if ( !function_exists( 'themeblvd_load_theme_textdomain' ) ) :
/**
 * Load theme text domains
 *
 * @since 2.2.0
 */
function themeblvd_load_theme_textdomain() {
	load_theme_textdomain( 'themeblvd', get_template_directory() . '/lang' );
	load_theme_textdomain( 'themeblvd_frontend', get_template_directory() . '/lang' );
}
endif;

/**
 * Get Image Sizes
 *
 * By having this in a separate function, hopefully
 * it can be extended upon better. If any plugin or
 * other feature of the framework requires these
 * image sizes, they can grab 'em.
 *
 * @since 2.2.0
 */
function themeblvd_get_image_sizes() {

	global $content_width;

	// Content Width
	$content_width = apply_filters( 'themeblvd_content_width', 940 ); // Default width of primary content area

	// Crop sizes
	$sizes = array(
		'tb_large' => array(
			'name' 		=> __( 'TB Large', 'themeblvd' ),
			'width' 	=> $content_width,
			'height' 	=> 9999,
			'crop' 		=> false
		),
		'tb_medium' => array(
			'name' 		=> __( 'TB Medium', 'themeblvd' ),
			'width' 	=> 620,
			'height'	=> 9999,
			'crop' 		=> false
		),
		'tb_small' => array(
			'name' 		=> __( 'TB Small', 'themeblvd' ),
			'width' 	=> 195,
			'height' 	=> 195,
			'crop' 		=> false
		),
		'square_small' => array(
			'name' 		=> __( 'Small Square', 'themeblvd' ),
			'width' 	=> 130,
			'height' 	=> 130,
			'crop' 		=> true
		),
		'square_smaller' => array(
			'name' 		=> __( 'Smaller Square', 'themeblvd' ),
			'width' 	=> 70,
			'height' 	=> 70,
			'crop' 		=> true
		),
		'square_smallest' => array(
			'name' 		=> __( 'Smallest Square', 'themeblvd' ),
			'width' 	=> 45,
			'height' 	=> 45,
			'crop' 		=> true
		),
		'slider-large' => array(
			'name' 		=> __( 'Slider Full Width', 'themeblvd' ),
			'width' 	=> 940,
			'height' 	=> 350,
			'crop' 		=> true
		),
		'slider-staged' => array(
			'name' 		=> __( 'Slider Staged', 'themeblvd' ),
			'width' 	=> 550,
			'height' 	=> 340,
			'crop' 		=> true
		),
		'grid_fifth_1' => array(
			'name' 		=> __( '1/5 Column of Grid', 'themeblvd' ),
			'width' 	=> 200,
			'height' 	=> 125,
			'crop' 		=> true
		),
		'grid_3' => array(
			'name' 		=> __( '1/4 Column of Grid', 'themeblvd' ),
			'width' 	=> 240,
			'height' 	=> 150,
			'crop' 		=> true
		),
		'grid_4' => array(
			'name' 		=> __( '1/3 Column of Grid', 'themeblvd' ),
			'width' 	=> 320,
			'height' 	=> 200,
			'crop' 		=> true
		),
		'grid_6' => array(
			'name' 		=> __( '1/2 Column of Grid', 'themeblvd' ),
			'width' 	=> 472,
			'height' 	=> 295,
			'crop' 		=> true
		)
	);

	return apply_filters( 'themeblvd_image_sizes', $sizes );
}

if ( !function_exists( 'themeblvd_add_image_sizes' ) ) :
/**
 * Register Image Sizes
 *
 * @since 2.1.0
 */
function themeblvd_add_image_sizes() {

	// Get image sizes
	$sizes = themeblvd_get_image_sizes();

	// Add image sizes
	foreach ( $sizes as $size => $atts ) {
		add_image_size( $size, $atts['width'], $atts['height'], $atts['crop'] );
	}

}
endif;

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
		$tb_sizes[$id] = $atts['name'];
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

	global $allowedposttags;

	// Match Theme Blvd tags with global HTML
	// allowed for standard Posts/Pages.
	$themeblvd_tags = $allowedposttags;

	// And make any adjustments
	$themeblvd_tags['iframe'] = array(
		'style' 				=> true,
		'width' 				=> true,
		'height' 				=> true,
		'src' 					=> true,
		'frameborder'			=> true,
		'allowfullscreen' 		=> true,
		'webkitAllowFullScreen'	=> true,
		'mozallowfullscreen' 	=> true
	);
	$themeblvd_tags['script'] = array(
		'type'					=> true,
		'src' 					=> true
	);

	return apply_filters( 'themeblvd_allowed_tags', $themeblvd_tags );
}

/**
 * Generates default column widths for column element.
 *
 * @since 2.0.0
 *
 * @return array $widths All column options with cooresponding widths.
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
			'name'	=> __( 'Pages', 'themeblvd' ),
			'empty'	=> __( 'No pages to display.', 'themeblvd' )
		),
		'posts' => array(
			'id'	=> 'posts',
			'name'	=> __( 'Posts', 'themeblvd' ),
			'empty'	=> __( 'No posts to display.', 'themeblvd' )
		),
		'posts_in_category' => array(
			'id'	=> 'posts_in_category',
			'name'	=> __( 'Posts in Category', 'themeblvd' ),
			'empty'	=> __( 'No categories to display.', 'themeblvd' )
		),
		'categories' => array(
			'id'	=> 'categories',
			'name'	=> __( 'Category Archives', 'themeblvd' ),
			'empty'	=> __( 'No categories to display.', 'themeblvd' )
		),
		'tags' => array(
			'id'	=> 'tags',
			'name'	=> __( 'Tag Archives', 'themeblvd' ),
			'empty'	=> __( 'No tags to display.', 'themeblvd' )
		),
		'top' => array(
			'id'	=> 'top',
			'name'	=> __( 'Hierarchy', 'themeblvd' ),
			'items'	=> array(
				'home' 			=> __( 'Homepage', 'themeblvd' ),
				'posts' 		=> __( 'All Posts', 'themeblvd' ),
				'pages' 		=> __( 'All Pages', 'themeblvd' ),
				'archives' 		=> __( 'All Archives', 'themeblvd' ),
				'categories'	=> __( 'All Category Archives', 'themeblvd' ),
				'tags' 			=> __( 'All Tag Archives', 'themeblvd' ),
				'authors' 		=> __( 'All Author Archives', 'themeblvd' ),
				'search' 		=> __( 'Search Results', 'themeblvd' ),
				'404' 			=> __( '404 Page', 'themeblvd' )
			)
		),
		'custom' => array(
			'id'	=> 'custom',
			'name'	=> __( 'Custom', 'themeblvd' )
		)
	);
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

		// Link to Vimeo page?
		if ( strpos( $url, 'vimeo.com' ) !== false ) {
			$icon = 'video';
		}

		// Link to YouTube page?
		if ( strpos( $url, 'youtube.com' ) !== false ) {
			$icon = 'video';
		}

		// Link to Google map?
		if ( strpos( $url, 'maps.google.com' ) !== false ) {
			$icon = 'image'; // represents more of an "enlarge" icon
		}

		// Link to inline popup?
		if ( strpos( $url, '#' ) === 0 ) {
			$icon = 'image'; // represents more of an "enlarge" icon
		}

		if ( ! $icon ) {

			$parsed_url = parse_url( $url );
			$type = wp_check_filetype( $parsed_url['path'] );

			// Link to image file?
			if ( substr( $type['type'], 0, 5 ) == 'image' ) {
				$icon = 'image';
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
		'amazon' 		=> 'Amazon',
		'delicious' 	=> 'del.icio.us',
		'deviantart' 	=> 'Deviant Art',
		'digg' 			=> 'Digg',
		'dribbble' 		=> 'Dribbble',
		'ebay' 			=> 'Ebay',
		'email' 		=> 'Email',
		'facebook' 		=> 'Facebook',
		'feedburner' 	=> 'Feedburner',
		'flickr' 		=> 'Flickr',
		'forrst' 		=> 'Forrst',
		'foursquare' 	=> 'Foursquare',
		'github' 		=> 'Github',
		'google' 		=> 'Google+',
		'instagram' 	=> 'Instagram',
		'linkedin' 		=> 'Linkedin',
		'myspace' 		=> 'MySpace',
		'paypal' 		=> 'PayPal',
		'picasa' 		=> 'Picasa',
		'pinterest' 	=> 'Pinterest',
		'reddit' 		=> 'Reddit',
		'scribd' 		=> 'Sribd',
		'squidoo' 		=> 'Squidoo',
		'technorati' 	=> 'Technorati',
		'tumblr' 		=> 'Tumblr',
		'twitter' 		=> 'Twitter',
		'vimeo' 		=> 'Vimeo',
		'xbox' 			=> 'Xbox',
		'yahoo' 		=> 'Yahoo',
		'youtube' 		=> 'YouTube',
		'rss' 			=> 'RSS'
	);

 	// Backwards compat filter
 	$sources = apply_filters( 'themeblvd_social_media_buttons', $sources );

	return apply_filters( 'themeblvd_social_media_sources', $sources );
}