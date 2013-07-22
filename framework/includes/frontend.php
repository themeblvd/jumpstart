<?php
/**
 * Initiate Front-end
 *
 * @since 2.0.0
 */
function themeblvd_frontend_init() {

	/**
	 * Setup frontend
	 * (1) Template Parts
	 * (2) Template Attributes
	 * (3) Theme Mode (list or grid)
	 * (4) Main Configuration
	 *	- a. Original $post object ID
	 * 	- b. Builder name and post ID (if custom layout)
	 * 	- c. Featured areas classes (if present)
	 * 	- d. Sidebar layout
	 * 	- e. Sidebar ID's
	 */
	Theme_Blvd_Frontend_Init::get_instance();

	/**
	 * Setup any secondary queries or hook any modifications
	 * to the primary query.
	 */
	Theme_Blvd_Query::get_instance();

}

/**
 * Get content extension for uses of get_template_part
 * End Usage: get_template_part( 'content', {$part} )
 * File name structure: content-{$part}.php
 *
 * @since 2.0.0
 *
 * @param string $type Type of template part to get
 * @return string $part Extension to use for get_template_part
 */
function themeblvd_get_part( $type ) {
	$config = Theme_Blvd_Frontend_Init::get_instance();
	$part = $config->get_template_parts( $type );
	return apply_filters( 'themeblvd_template_part', $part, $type );
}

/**
 * Get the current mode of the framework, list or grid.
 * This determines if archives and the posts page display
 * as a grid.
 *
 * Grid mode is triggered when the template parts for "index"
 * and "archive" are filtered to be either "grid", "archive_grid"
 * or "index_grid".
 *
 * @since 2.3.0
 *
 * @return bool
 */
function themeblvd_is_grid_mode() {

	$config = Theme_Blvd_Frontend_Init::get_instance();

	if ( $config->get_mode() == 'grid' ) {
		return true;
	}

	return false;
}

/**
 * This function is used from within the theme's template
 * files to return the values setup in the frontend init.
 *
 * @since 2.0.0
 *
 * @param mixed $key string $key to retrieve from frontend config object
 * @param mixed $secondary string Optional array key to traverse one level deeper
 * @return mixed Value from frontend config object
 */
function themeblvd_config( $key = '', $secondary = '' ) {
	$config = Theme_Blvd_Frontend_Init::get_instance();
	return $config->get_config( $key, $secondary );
}

/**
 * Display CSS class for current sidebar layout.
 *
 * @since 2.0.0
 */
function themeblvd_sidebar_layout_class() {
	$config = Theme_Blvd_Frontend_Init::get_instance();
	echo $config->get_config('sidebar_layout');
}

/**
 * At any time, this function can be called to effect
 * the global template attributes array which can
 * be utilized within template files.
 *
 * This system provides a way for attributes to be set
 * and retreived with themeblvd_get_att() from files
 * included with WP's get_template_part.
 *
 * @since 2.2.0
 *
 * @param array $atts Attributes to be merged with global attributes
 * @param bool $flush Whether or not to flush previous attributes before merging
 */
function themeblvd_set_atts( $atts, $flush = false ) {
	$config = Theme_Blvd_Frontend_Init::get_instance();
	return $config->set_atts( $atts, $flush );
}

/**
 * Working with the system established in the
 * previous function, this function allows you
 * to set an individual att along with creating
 * a new variable.
 *
 * @since 2.2.0
 *
 * @param string $key Key in $atts array to modify
 * @param mixed $value New value
 * @return mixed New value
 */
function themeblvd_set_att( $key, $value ) {
	$config = Theme_Blvd_Frontend_Init::get_instance();
	return $config->set_att( $key, $value );
}

/**
 * Retrieve a single attribute set with
 * themeblvd_set_atts()
 *
 * @since 2.2.0
 *
 * @param string $key Key in $atts array to retrieve
 * @return mixed Value of attribute
 */
function themeblvd_get_att( $key ) {
	$config = Theme_Blvd_Frontend_Init::get_instance();
	return $config->get_att( $key );
}

/**
 * Get the secondary query.
 *
 * @since 2.3.0
 *
 * @param array|string $args Arguments to parse into query
 * @param string $type Type of secondary query, list or grid
 * @return array $second_query Newly stored second query attribute
 */
function themeblvd_get_second_query() {
	$query = Theme_Blvd_Query::get_instance();;
	return $query->get_second_query();
}

/**
 * Set the secondary query.
 *
 * @since 2.3.0
 *
 * @return array The secondary query
 */
function themeblvd_set_second_query( $args, $type ) {
	$query = Theme_Blvd_Query::get_instance();;
	return $query->set_second_query( $args, $type );
}

/**
 * Verify the state of the original query.
 *
 * @since 2.3.0
 *
 * @param string $type The primary type of WP page being checked for
 * @param string $helper A secondary param if allowed with $type
 * @return bool
 */
function themeblvd_was( $type, $helper = '' ) {
	$query = Theme_Blvd_Query::get_instance();
	return $query->was( $type, $helper );
}

/**
 * Determine current web browser and generate a CSS class for
 * it. This function gets filtered onto WP's body_class.
 *
 * @since 2.2.0
 *
 * @param array $classes Current body classes
 * @return array $classes Body classes with browser classes added
 */
function themeblvd_browser_class( $classes ) {

	// Get current user agent
	$browser = $_SERVER[ 'HTTP_USER_AGENT' ];

	// OS class
	if ( preg_match( "/Mac/", $browser ) ) {
		$classes[] = 'mac';
	} elseif ( preg_match( "/Windows/", $browser ) ) {
		$classes[] = 'windows';
	} elseif ( preg_match( "/Linux/", $browser ) ) {
		$classes[] = 'linux';
	} else {
		$classes[] = 'unknown-os';
	}

	// Browser class
	if ( preg_match( "/Chrome/", $browser ) ) {
		$classes[] = 'chrome';
	} elseif ( preg_match( "/Safari/", $browser ) ) {
		$classes[] = 'safari';
	} elseif ( preg_match( "/Opera/", $browser ) ) {
		$classes[] = 'opera';
	} elseif ( preg_match( "/MSIE/", $browser ) ) {

		// Internet Explorer... ugh, kill me now.
		$classes[] = 'msie';
		if ( preg_match( "/MSIE 6.0/", $browser ) ) {
			$classes[] = 'ie6';
		} elseif ( preg_match( "/MSIE 7.0/", $browser ) ) {
			$classes[] = 'ie7';
		} elseif ( preg_match( "/MSIE 8.0/", $browser ) ) {
			$classes[] = 'ie8';
		} elseif ( preg_match( "/MSIE 9.0/", $browser ) ) {
			$classes[] = 'ie9';
		} elseif ( preg_match( "/MSIE 10.0/", $browser ) ) {
			$classes[] = 'ie10';
		}

	} elseif ( preg_match( "/Firefox/", $browser ) && preg_match( "/Gecko/", $browser ) ) {
		$classes[] = 'firefox';
	} else {
		$classes[] = 'unknown-browser';
	}

	return apply_filters( 'themeblvd_browser_classes', $classes, $browser );
}

if ( !function_exists( 'themeblvd_include_scripts' ) ) :
/**
 * Load framework's JS scripts
 *
 * To add scripts or remove unwanted scripts that you
 * know you won't need to maybe save some frontend load
 * time, this function can easily be re-done from a
 * child theme.
 *
 * (1) jQuery - Already registered by WP, and enqueued for most our scripts.
 * (2) Twitter Bootstrap - All Bootstrap JS plugins combiled.
 * (3) Magnific Popup - Handles all default lightbox functionality.
 * (4) Super Fish/Hover Intent - Used for primary navigation.
 * (5) FlexSlider - Responsive slider, controls framework's "standard" slider type.
 * (6) Roundabout - Carousel-style slider, controls framwork's "3D Carousel" slider type.
 * (7) Theme Blvd scripts - Anything used by the framework to set other items into motion.
 * (8) iOS Orientation Fix - Allows zooming to be enabled on [older] iOS devices while still
 * allowing auto adjustment when switching between landscape and portrait.
 * (9) Already registered by WP, enable commentform to show when visitor clicks "Reply" on comment.
 *
 * @since 2.0.0
 */
function themeblvd_include_scripts() {

	global $themeblvd_framework_scripts;

	// Start framework scripts. This can be used declare the
	// $deps of any enque'd JS files intended to come after
	// the framework.
	$scripts = array( 'jquery' );

	// Register scripts -- These scripts are only enque'd as needed.
	wp_register_script( 'flexslider', TB_FRAMEWORK_URI . '/assets/js/flexslider.min.js', array('jquery'), '2.1', true  );
	wp_register_script( 'roundabout', TB_FRAMEWORK_URI . '/assets/js/roundabout.min.js', array('jquery'), '1.1', true );
	wp_register_script( 'nivo', TB_FRAMEWORK_URI . '/assets/js/nivo.min.js', array('jquery'), '3.2', true );

	// Enque Scripts
	wp_enqueue_script( 'jquery' );

	if ( themeblvd_supports( 'assets', 'bootstrap' ) ) {
		$scripts[] = 'bootstrap';
		wp_enqueue_script( 'bootstrap', TB_FRAMEWORK_URI . '/assets/plugins/bootstrap/js/bootstrap.min.js', array('jquery'), '2.3.2', true );
	}

	if ( themeblvd_supports( 'assets', 'magnific_popup' ) ) {
		$scripts[] = 'magnific_popup';
		wp_enqueue_script( 'magnific_popup', TB_FRAMEWORK_URI . '/assets/js/magnificpopup.min.js', array('jquery'), '0.9.3', true );
	}

	if ( themeblvd_supports( 'assets', 'superfish' ) ) {
		$scripts[] = 'superfish';
		wp_enqueue_script( 'hoverintent', TB_FRAMEWORK_URI . '/assets/js/hoverintent.min.js', array('jquery'), 'r7', true );
		wp_enqueue_script( 'superfish', TB_FRAMEWORK_URI . '/assets/js/superfish.min.js', array('jquery'), '1.7.4', true );
	}

	if ( themeblvd_supports( 'assets', 'primary_js' ) ) {
		$scripts[] = 'themeblvd';
		wp_enqueue_script( 'themeblvd', TB_FRAMEWORK_URI . '/assets/js/themeblvd.min.js', array('jquery'), TB_FRAMEWORK_VERSION, true );
		// Localize primary themeblvd.js script. This allows us to pass any filterable
		// parameters through to our primary script.
		wp_localize_script( 'themeblvd', 'themeblvd', themeblvd_get_js_locals() );
	}

	// Final filter on framework script.
	$themeblvd_framework_scripts = apply_filters( 'themeblvd_framework_scripts', $scripts );

	// iOS Orientation (for older iOS devices, not supported by default)
	if ( themeblvd_supports( 'display', 'responsive' ) && themeblvd_supports( 'assets', 'ios_orientation' ) ) {
		wp_enqueue_script( 'ios-orientationchange-fix', TB_FRAMEWORK_URI . '/assets/js/ios-orientationchange-fix.js', true );
	}

	// Comments reply
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
endif;