<?php
/**
 * Frontend setup functions.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */

/**
 * Initiate Front-end
 *
 * @since 2.0.0
 */
function themeblvd_frontend_init() {

	if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

		/*
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

		/*
		 * Setup any secondary queries or hook any modifications
		 * to the primary query.
		 */
		Theme_Blvd_Query::get_instance();

	}

}

/**
 * Wrapper for WP's get_template_part().
 *
 * This wrapper function helps us to create a unified system
 * where the template parts are consistently filtered.
 *
 * @since 2.2.0
 *
 * @param string $type Type of template part to get.
 */
function themeblvd_get_template_part( $type ) {

	$config = Theme_Blvd_Frontend_Init::get_instance();

	/**
	 * Filter the first $slug paramter passed to get_template_part().
	 *
	 * @since 2.7.0
	 *
	 * @param string Template part slug.
	 * @param string $type Type of template part.
	 */
	$slug = apply_filters( 'themeblvd_template_part_slug', 'template-parts/content', $type );

	/**
	 * Filter the first $slug paramter passed to get_template_part().
	 *
	 * @since 2.0.0
	 *
	 * @param string Name of specialised template.
	 * @param string $type Type of template part.
	 */
	$name = apply_filters( 'themeblvd_template_part', $config->get_template_parts( $type ), $type );

	/*
	 * Include template file.
	 */
	get_template_part( $slug, $name );

}

/**
 * Get second $name parameter for uses of get_template_part().
 *
 * @since 2.0.0
 * @deprecated 2.7.0
 *
 * @param string $type Type of template part to get.
 */
function themeblvd_get_part( $type ) {

	themeblvd_deprecated_function(
		__FUNCTION__,
		'2.7.0',
		'themeblvd_get_template_part',
		__( 'Instead of using themeblvd_get_part(), you should now use themeblvd_get_template_part() wrapper function to completely replace get_template_part() instance.' , 'jumpstart')
	);

}

/**
 * Determine if the current post display mode of the framework is "grid"
 *
 * @since 2.3.0
 *
 * @return bool
 */
function themeblvd_is_grid_mode() {

	if ( themeblvd_config('mode') == 'grid' ) {
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
 * All default body classes puts on framework, including
 * determining current web browser and generate a CSS class for
 * it. This function gets filtered onto WP's body_class.
 *
 * @since 2.2.0
 *
 * @param array $classes Current body classes
 * @return array $classes Body classes with browser classes added
 */
function themeblvd_body_class( $class ) {

	if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
		return $class;
	}

	// Get current user agent
	$browser = $_SERVER['HTTP_USER_AGENT'];

	// OS class
	if ( preg_match( "/Mac/", $browser ) ) {
		$class[] = 'mac';
	} else if ( preg_match( "/Windows/", $browser ) ) {
		$class[] = 'windows';
	} else if ( preg_match( "/Linux/", $browser ) ) {
		$class[] = 'linux';
	} else {
		$class[] = 'unknown-os';
	}

	// Browser class
	if ( preg_match( "/Chrome/", $browser ) ) {
		$class[] = 'chrome';
	} else if ( preg_match( "/Safari/", $browser ) ) {
		$class[] = 'safari';
	} else if ( preg_match( "/Opera/", $browser ) ) {
		$class[] = 'opera';
	} else if ( preg_match( "/MSIE/", $browser ) ) {

		// Internet Explorer... ugh, kill me now.
		$class[] = 'msie';

		if ( preg_match( "/MSIE 6.0/", $browser ) ) {
			$class[] = 'ie6';
		} else if ( preg_match( "/MSIE 7.0/", $browser ) ) {
			$class[] = 'ie7';
		} else if ( preg_match( "/MSIE 8.0/", $browser ) ) {
			$class[] = 'ie8';
		} else if ( preg_match( "/MSIE 9.0/", $browser ) ) {
			$class[] = 'ie9';
		} else if ( preg_match( "/MSIE 10.0/", $browser ) ) {
			$class[] = 'ie10';
		} else if ( preg_match( "/MSIE 11.0/", $browser ) ) {
			$class[] = 'ie11';
		}

	} else if ( preg_match( "/Firefox/", $browser ) && preg_match( "/Gecko/", $browser ) ) {
		$class[] = 'firefox';
	} else {
		$class[] = 'unknown-browser';
	}

	// Add "mobile" class if this actually a mobile device,
	// and not the curious user screwing around with their
	// browser window.
	if ( wp_is_mobile() ) {
		$class[] = 'mobile';
	} else {
		$class[] = 'desktop';
	}

	// Scroll effects
	if ( themeblvd_supports('display', 'scroll_effects') ) {
		$class[] = 'tb-scroll-effects';
	}

	// Suck up custom layout into header
	if ( themeblvd_config('suck_up') ) {
		$class[] = 'tb-suck-up';
	}

	// Epic thumbnail
	if ( ( is_single() || is_page() ) && themeblvd_get_att('epic_thumb') ) {

		$class[] = 'has-epic-thumb';

		if ( themeblvd_get_att('thumbs') == 'fs' ) {
			$class[] = 'has-fs-epic-thumb';
		}
	}

	// Breadcrumbs
	if ( themeblvd_show_breadcrumbs() ) {
		$class[] = 'has-breadcrumbs';
	}

	// Side Panel
	if ( themeblvd_do_side_panel() ) {
		$class[] = 'has-side-panel';
	}

	// Sticky Header
	if ( themeblvd_config('sticky') ) {
		$class[] = 'has-sticky';
	}

	// Narrow full-width content
	if ( themeblvd_do_fw_narrow() ) {
		$class[] = 'tb-fw-narrow';
	}

	// Image popouts
	if ( themeblvd_do_img_popout() ) {
		$class[] = 'tb-img-popout';
	}

	// Dark/Light content
	if ( themeblvd_supports('display', 'dark') ) {
		$class[] = 'content_dark';
	} else {
		$class[] = 'content_light';
	}

	// Tag Cloud styling
	if ( themeblvd_supports('assets', 'tag_cloud') ) {
		$class[] = 'tb-tag-cloud';
	}

	// Blank page template
	if ( is_page_template('template_blank.php') ) {
		$class[] = 'tb-blank-page';
	}

	// Print styles
	if ( themeblvd_supports('display', 'print') ) {
		$class[] = 'tb-print-styles';
	}

	return apply_filters( 'themeblvd_browser_classes', $class, $browser );
}

/**
 * Display HTML class for site header.
 *
 * @since 2.5.0
 */
function themeblvd_header_class() {

	$class = array('site-header');

	if ( themeblvd_config('suck_up') ) {
		$class[] = 'transparent';
	} else {
		$class[] = 'standard';
	}

	if ( $class = apply_filters('themeblvd_header_class', $class ) ) {
		$output = sprintf( 'class="%s"', esc_attr( implode(' ', $class) ) );
	}

	echo apply_filters('themeblvd_header_class_output', $output, $class);

}

/**
 * Display HTML class header top bar.
 *
 * @since 2.6.0
 */
function themeblvd_header_top_class() {

	$class = array('header-top');

	if ( $class = apply_filters('themeblvd_header_top_class', $class ) ) {
		$output = sprintf( 'class="%s"', esc_attr( implode(' ', $class) ) );
	}

	echo apply_filters('themeblvd_header_top_class_output', $output, $class);

}

/**
 * Display HTML class for site main wrapper.
 *
 * @since 2.5.1
 */
function themeblvd_main_class() {

	$config = Theme_Blvd_Frontend_Init::get_instance();

	$class = array('site-inner', $config->get_config('sidebar_layout'));

	if ( themeblvd_get_att('epic_thumb') ) {
		$class[] = 'has-epic-thumb-above';
	}

	if ( $class = apply_filters('themeblvd_main_class', $class ) ) {
		$output = sprintf( 'class="%s"', esc_attr( implode(' ', $class) ) );
	}

	echo apply_filters('themeblvd_main_class_output', $output, $class);

}

/**
 * Display HTML class for site footer.
 *
 * @since 2.5.0
 */
function themeblvd_footer_class() {

	$class = array('site-footer');

	if ( $class = apply_filters('themeblvd_footer_class', $class ) ) {
		$output = sprintf( 'class="%s"', esc_attr( implode(' ', $class) ) );
	}

	echo apply_filters('themeblvd_footer_class_output', $output, $class);

}

/**
 * Display HTML class for side panel.
 *
 * @since 2.6.0
 */
function themeblvd_side_panel_class() {

	$class = array('tb-side-panel');

	if ( $class = apply_filters('themeblvd_side_panel_class', $class ) ) {
		$output = sprintf( 'class="%s"', esc_attr( implode(' ', $class) ) );
	}

	echo apply_filters('themeblvd_side_panel_class_output', $output, $class);

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
 * @since 2.0.0
 */
function themeblvd_include_scripts() {

	global $themeblvd_framework_scripts;

	// Whether to include scripts in footer
	$in_footer = themeblvd_supports('assets', 'in_footer'); // true, by default

	// Start framework scripts. This can be used declare the
	// $deps of any enque'd JS files intended to come after
	// the framework.
	$scripts = array( 'jquery' );

	// Register scripts that get enqueued, as needed
	if ( themeblvd_supports( 'assets', 'gmap' ) ) {

		$gmaps = 'https://maps.googleapis.com/maps/api/js';

		if ( $gmap_key = themeblvd_get_option('gmap_api_key') ) {
			$gmaps = add_query_arg('key', $gmap_key, $gmaps);
		}

		wp_register_script( 'google-maps-api', esc_url($gmaps), array(), null, $in_footer );

	}

	if ( themeblvd_supports( 'assets', 'charts' ) ) {
		wp_register_script( 'charts', esc_url( TB_FRAMEWORK_URI . '/assets/js/chart.min.js' ), array(), null, $in_footer );
	}

	// Enque Scripts
	wp_enqueue_script( 'jquery' );

	if ( wp_is_mobile() ) {
		wp_enqueue_script( 'jquery-mobile-touch', esc_url( TB_FRAMEWORK_URI . '/assets/js/jquery.mobile.touch.min.js' ), array('jquery'), '1.4.5', $in_footer );
	}

	if ( themeblvd_supports( 'assets', 'flexslider' ) ) {
		$scripts[] = 'flexslider';
		wp_enqueue_script( 'flexslider', esc_url( TB_FRAMEWORK_URI . '/assets/js/flexslider.min.js' ), array('jquery'), '2.6.0', $in_footer );
	}

	if ( themeblvd_supports( 'assets', 'owl_carousel' ) && themeblvd_get_option('gallery_carousel') ) {
		$scripts[] = 'owl-carousel';
		wp_enqueue_script( 'owl-carousel', esc_url( TB_FRAMEWORK_URI . '/assets/plugins/owl-carousel/owl.carousel.min.js' ), array('jquery'), '2.2.1', $in_footer );
	}

	if ( themeblvd_supports( 'assets', 'bootstrap' ) ) {
		$scripts[] = 'bootstrap';
		wp_enqueue_script( 'bootstrap', esc_url( TB_FRAMEWORK_URI . '/assets/plugins/bootstrap/js/bootstrap.min.js' ), array('jquery'), '3.3.5', $in_footer );
	}

	if ( themeblvd_supports( 'assets', 'magnific_popup' ) ) {
		$scripts[] = 'magnific-popup';
		wp_enqueue_script( 'magnific-popup', esc_url( TB_FRAMEWORK_URI . '/assets/js/magnificpopup.min.js' ), array('jquery'), '0.9.3', $in_footer );
	}

	if ( themeblvd_supports( 'assets', 'superfish' ) ) {
		$scripts[] = 'superfish';
		wp_enqueue_script( 'hoverintent', esc_url( TB_FRAMEWORK_URI . '/assets/js/hoverintent.min.js' ), array('jquery'), 'r7', $in_footer );
		wp_enqueue_script( 'superfish', esc_url( TB_FRAMEWORK_URI . '/assets/js/superfish.min.js' ), array('jquery'), '1.7.4', $in_footer );
	}

	if ( themeblvd_supports( 'assets', 'easypiechart' ) ) {
		$scripts[] = 'easypiechart';
		wp_enqueue_script( 'easypiechart', esc_url( TB_FRAMEWORK_URI . '/assets/js/easypiechart.min.js' ), array('jquery'), '2.1.5', $in_footer );
	}

	if ( themeblvd_supports( 'assets', 'isotope' ) ) {
		$scripts[] = 'isotope';
		wp_enqueue_script( 'isotope', esc_url( TB_FRAMEWORK_URI . '/assets/js/isotope.min.js' ), array('jquery'), '2.0.1', $in_footer );
	}

	if ( themeblvd_supports( 'assets', 'primary_js' ) ) {
		$scripts[] = 'themeblvd';
		wp_enqueue_script( 'themeblvd', esc_url( TB_FRAMEWORK_URI . '/assets/js/themeblvd.js' ), array('jquery'), TB_FRAMEWORK_VERSION, $in_footer );
		// Localize primary themeblvd.js script. This allows us to pass any filterable
		// parameters through to our primary script.
		wp_localize_script( 'themeblvd', 'themeblvd', themeblvd_get_js_locals() );
	}

	// Final filter on framework script.
	$themeblvd_framework_scripts = apply_filters( 'themeblvd_framework_scripts', $scripts );

	// Comments reply
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
endif;
