<?php
// Define theme constants
define( 'TB_THEME_ID', 'jumpstart' );
define( 'TB_THEME_NAME', 'JumpStart' );

// Modify framework's theme options
require_once( get_template_directory() . '/includes/options.php' );

/**
 * TB Jump Start Setup 
 * 
 * You can override this function from a child 
 * theme if any basic setup things need to be changed.
 */

if( ! function_exists( 'themeblvd_jumpstart_setup' ) ) {
	function themeblvd_jumpstart_setup() {
		
		// Custom background support
		// add_theme_support( 'custom-background' ); // Not supported in this theme.
		
		// Post Formats
		// add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );
		
		// Localization
		load_theme_textdomain( TB_GETTEXT_DOMAIN, get_template_directory() . '/lang' );
		load_theme_textdomain( TB_GETTEXT_DOMAIN_FRONT, get_template_directory() . '/lang' );
	}
}
add_action( 'after_setup_theme', 'themeblvd_jumpstart_setup' );

/**
 * TB Jump Start CSS Files
 *
 * To add styles or remove unwanted styles that you 
 * know you won't need to maybe save some frontend load 
 * time, this function can easily be re-done from a 
 * child theme.
 */

if( ! function_exists( 'themeblvd_jumpstart_css' ) ) {
	function themeblvd_jumpstart_css() {
		wp_register_style( 'themeblvd_theme', get_template_directory_uri() . '/assets/css/theme.css', false, '1.0' ); // ... change to min
		wp_register_style( 'themeblvd_responsive', get_template_directory_uri() . '/assets/css/responsive.min.css', false, '1.0' );
		wp_register_style( 'themeblvd_ie', get_template_directory_uri() . '/assets/css/ie.css', false, '1.0' );
		wp_enqueue_style( 'themeblvd_theme' );
		$GLOBALS['wp_styles']->add_data( 'themeblvd_ie', 'conditional', 'lt IE 9' ); // Add IE conditional
		wp_enqueue_style( 'themeblvd_ie' );
		if( themeblvd_get_option( 'responsive_css' ) != 'false' ) wp_enqueue_style( 'themeblvd_responsive' );
		// Level 3 user styles
		themeblvd_user_stylesheets( 3 );
	}
}
add_action( 'wp_print_styles', 'themeblvd_jumpstart_css' );

/**
 * Jump Start Body Classes 
 * 
 * Here we filter WordPress's default body_class()
 * function to include necessary classes for Main 
 * Styles selected in Theme Options panel.
 */

if( ! function_exists( 'jumpstart_body_class' ) ) {
	function jumpstart_body_class( $classes ) {
		$classes[] = themeblvd_get_option( 'mobile_nav' );
		return $classes;
	}
}
add_filter( 'body_class', 'jumpstart_body_class' );

/**
 * Jump Start Styles 
 * 
 * This is where the theme's configured styles 
 * from the Theme Options page get put into place 
 * by inserting CSS in the <head> of the site. It's 
 * shown here as clearly as possible to be edited, 
 * however it gets compressed when actually inserted 
 * into the front end of the site.
 */

if( ! function_exists( 'jumpstart_styles' ) ) {
	function jumpstart_styles() {
		$custom_styles = themeblvd_get_option( 'custom_styles' );
		$body_font = themeblvd_get_option( 'typography_body' );
		$header_font = themeblvd_get_option( 'typography_header' );
		$special_font = themeblvd_get_option( 'typography_special' );
		themeblvd_include_google_fonts( $body_font, $header_font, $special_font );
		echo '<style>'."\n";
		ob_start();
		?>
		/* Dynamic Styles */
		<?php
		// Ouptput compressed CSS
		echo themeblvd_compress( ob_get_clean() );
		// Add in user's custom CSS
		if( $custom_styles ) echo $custom_styles;
		echo "\n</style>\n";
	}
}
// add_action( 'wp_head', 'jumpstart_styles' ); // Not used in Jump Start

/*-----------------------------------------------------------------------------------*/
/* Theme Filters
/*
/* Here we can take advantage of modifying anything in the framework that is 
/* filterable. 
/*-----------------------------------------------------------------------------------*/

// No filters.

/*-----------------------------------------------------------------------------------*/
/* Theme Functions
/*
/* The following functions either add elements to unsed hooks in the framework, 
/* or replace default functions. These functions can be overridden from a child 
/* theme.
/*-----------------------------------------------------------------------------------*/

// No theme functions.

/*-----------------------------------------------------------------------------------*/
/* Hook Adjustments on framework
/*-----------------------------------------------------------------------------------*/

// Remove Hooks
// No hooks removed.

// Add Hooks
// No hooks added.