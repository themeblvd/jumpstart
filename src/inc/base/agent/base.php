<?php
/**
 * Agent theme base functions.
 *
 * @author		Jason Bobich
 * @copyright	2009-2017 Theme Blvd
 * @link		http://themeblvd.com
 * @package 	@@name-package
 */

/**
 * Theme Options Mods
 */
include_once( themeblvd_get_base_path( 'agent' ) . '/options.php' );

/**
 * Theme Option Presets
 */
include_once( themeblvd_get_base_path( 'agent' ) . '/presets.php' );

/**
 * Filter global config
 *
 * @since 2.1.0
 */
function jumpstart_ag_global_config( $setup ) {

	// Dark content style
	if ( themeblvd_get_option('style') == 'dark' ) {
		$setup['display']['dark'] = true;
	}

	return $setup;
}
add_filter('themeblvd_global_config', 'jumpstart_ag_global_config');

/**
 * Body class
 *
 * @since 2.1.0
 */
function jumpstart_ag_body_class($class) {

	if ( themeblvd_get_option('sticky') == 'show' ) {
		$class[] = 'has-sticky';
	}

	if ( themeblvd_get_option('widget_bg') ) {
		$class[] = 'tb-widget-bg';
		$class[] = themeblvd_get_option('widget_bg_color_brightness') . '-widgets';

	}

	return $class;

}
add_filter('body_class', 'jumpstart_ag_body_class');

/**
 * Include Google fonts, if needed
 *
 * @since 2.1.0
 */
function jumpstart_ag_include_fonts() {
	themeblvd_include_fonts(
		themeblvd_get_option('font_body'),
		themeblvd_get_option('font_header'),
		themeblvd_get_option('font_header_sm'),
		themeblvd_get_option('font_quote'),
		themeblvd_get_option('font_meta'),
		themeblvd_get_option('font_epic'),
		themeblvd_get_option('font_menu')
	);
}
add_action('wp_head', 'jumpstart_ag_include_fonts', 5);

/**
 * Enqueue any CSS
 *
 * @since 2.1.0
 */
function jumpstart_ag_css() {

	$print = '';

	/*------------------------------------------------------------*/
	/* General
	/*------------------------------------------------------------*/

	$print .= "/* Primary Buttons */\n";
	$print .= ".btn-default,\n";
	$print .= "input[type=\"submit\"],\n";
	$print .= "input[type=\"reset\"],\n";
	$print .= "input[type=\"button\"],\n";
	$print .= ".button,\n";
	$print .= "button,\n";
	$print .= ".primary,\n";
	$print .= ".bg-primary,\n";
	$print .= ".btn-primary,\n";
	$print .= "a.alt,\n";
	$print .= "button.alt,\n";
	$print .= "input.alt,\n";
	$print .= ".label-primary,\n";
	$print .= ".panel-primary > .panel-heading {\n";
	$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_option('btn_color') );
	$print .= "}\n";

	// Highlight Color
	$highlight = themeblvd_get_option('highlight');

	$print .= "\n/* Highlight */\n";

	$print .= ".highlight,\n";

	if ( themeblvd_supports('plugins', 'wpml') && themeblvd_installed('wpml') ) {
		$print .= ".tb-lang-popup a:hover,\n";
		$print .= ".tb-lang-popup a:focus,\n";
	}

	if ( themeblvd_supports('plugins', 'woocommerce') && themeblvd_installed('woocommerce') ) {
		$print .= ".woocommerce-tabs .tabs > li > a:hover,\n";
		$print .= ".woocommerce-tabs .tabs > li > a:focus,\n";
		$print .= ".woocommerce-tabs .tabs > li.active > a,\n";
	}

	$print .= ".tb-thumb-link:before,\n";
	$print .= ".tb-tags a:hover,\n";
	$print .= ".tb-tags a:focus,\n";
	$print .= ".btn-share:hover,\n";
	$print .= ".btn-share:focus,\n";
	$print .= ".featured-quote > a:hover,\n";
	$print .= ".featured-quote > a:focus,\n";
	$print .= ".tb-thumb-link:after,\n";
	$print .= ".post_showcase .showcase-item.has-title .featured-item.showcase .tb-thumb-link:after,\n";
	$print .= ".post_showcase .showcase-item.has-title .featured-item.showcase.tb-thumb-link:after,\n";
	$print .= ".tb-tag-cloud .tagcloud a:hover,\n";
	$print .= ".btn-default:hover,\n";
	$print .= ".btn-default:focus,\n";
	$print .= ".btn-default:active,\n";
	$print .= ".btn-default.active:hover,\n";
	$print .= "input[type=\"submit\"]:hover,\n";
	$print .= "input[type=\"submit\"]:focus,\n";
	$print .= "input[type=\"submit\"]:active,\n";
	$print .= "input[type=\"reset\"]:hover,\n";
	$print .= "input[type=\"reset\"]:focus,\n";
	$print .= "input[type=\"reset\"]:active,\n";
	$print .= "input[type=\"button\"]:hover,\n";
	$print .= "input[type=\"button\"]:focus,\n";
	$print .= "input[type=\"button\"]:active,\n";
	$print .= ".button:hover,\n";
	$print .= ".button:focus,\n";
	$print .= ".button:active,\n";
	$print .= "button:hover,\n";
	$print .= "button:focus,\n";
	$print .= "button:active,\n";
	$print .= ".pagination .btn-group .btn:hover,\n";
	$print .= ".primary:hover,\n";
	$print .= ".primary:focus,\n";
	$print .= "a.bg-primary:hover,\n";
	$print .= ".btn-primary:hover,\n";
	$print .= ".btn-primary:focus,\n";
	$print .= ".btn-primary:active,\n";
	$print .= ".btn-primary.active,\n";
	$print .= "a.alt:hover,\n";
	$print .= "a.alt:focus,\n";
	$print .= "button.alt:hover,\n";
	$print .= "button.alt:focus,\n";
	$print .= "input.alt:hover,\n";
	$print .= "input.alt:focus {\n";
	$print .= sprintf( "\tbackground-color: %s;\n", $highlight );
	$print .= "}\n";

	$print .= ".tooltip-inner {\n";
	$print .= sprintf( "background-color: %s;\n", $highlight );
	$print .= "}\n";

	$print .= ".tb-floating-search .tb-search .search-input:focus {\n";
	$print .= sprintf( "\tborder-color: %s;\n", $highlight );
	$print .= "}\n";

	$print .= ".tooltip.bottom .tooltip-arrow {\n";
	$print .= sprintf( "\tborder-bottom-color: %s;\n", $highlight );
	$print .= "}\n";

	$print .= ".tooltip.top .tooltip-arrow {\n";
	$print .= sprintf( "\tborder-top-color: %s;\n", $highlight );
	$print .= "}\n";

	$print .= "::selection {\n";
	$print .= sprintf( "\tbackground-color: %s;\n", $highlight );
	$print .= "}\n";
	$print .= "::-moz-selection {\n";
	$print .= sprintf( "\tbackground-color: %s;\n", $highlight );
	$print .= "}\n";

	/*------------------------------------------------------------*/
	/* Typography
	/*------------------------------------------------------------*/

	$print .= "\n/* Typography */\n";

	// Primary Font
	$font = themeblvd_get_option('font_body');

	if ( $font ) {
		$print .= "html,\n";
		$print .= "body {\n";
		$print .= sprintf("\tfont-family: %s;\n", themeblvd_get_font_face($font) );
		$print .= sprintf("\tfont-size: %s;\n", themeblvd_get_font_size($font) );
		$print .= sprintf("\tfont-style: %s;\n", themeblvd_get_font_style($font) );
		$print .= sprintf("\tfont-weight: %s;\n", themeblvd_get_font_weight($font) );
		$print .= sprintf("\ttext-transform: %s;\n", themeblvd_get_text_transform($font) );
		$print .= "}\n";
	}

	// Header Font
	$font = themeblvd_get_option('font_header');

	if ( $font ) {

		if ( themeblvd_installed('gravityforms') && themeblvd_supports('plugins', 'gravityforms') ) {
			$print .= ".tb-gforms-compat .gform_wrapper .gsection .gfield_label,\n";
			$print .= ".tb-gforms-compat .gform_wrapper h2.gsection_title,\n";
			$print .= ".tb-gforms-compat .gform_wrapper h3.gform_title,\n";
		}

		$print .= "h1,\n";
		$print .= "h2,\n";
		$print .= "h3,\n";
		$print .= "h4,\n";
		$print .= "label,\n";
		$print .= ".sf-menu .mega-section-header,\n";
		$print .= "#comments .comment-author {\n";
		$print .= sprintf("\tfont-family: %s;\n", themeblvd_get_font_face($font) );
		$print .= sprintf("\tfont-style: %s;\n", themeblvd_get_font_style($font) );
		$print .= sprintf("\tfont-weight: %s;\n", themeblvd_get_font_weight($font) );
		$print .= sprintf("\ttext-transform: %s;\n", themeblvd_get_text_transform($font) );
		$print .= "}\n";
	}

	// Small Header Font
	$font = themeblvd_get_option('font_header_sm');

	if ( $font ) {

		$print .= "h5,\n";
		$print .= "h6,\n";
		$print .= ".widget-title,\n";
		$print .= ".related-posts-title,\n";
		$print .= ".tb-info-box .info-box-title,\n";
		$print .= "#comments-title,\n";
		$print .= "#respond .comment-reply-title,\n";
		$print .= ".tb-author-box .info-box-title,\n";
		$print .= ".woocommerce-tabs .panel h2,\n";
		$print .= ".products.related > h2,\n";
		$print .= ".products.upsells > h2,\n";
		$print .= "#bbpress-forums fieldset.bbp-form legend,\n";
		$print .= ".modal-title,\n";
		$print .= ".sf-menu .mega-section-header,\n";
		$print .= ".tb-pricing-table .title,\n";
		$print .= ".tb-icon-box .icon-box-title {\n";

		$print .= sprintf("\tfont-family: %s;\n", themeblvd_get_font_face($font) );
		$print .= sprintf("\tfont-style: %s;\n", themeblvd_get_font_style($font) );
		$print .= sprintf("\tfont-weight: %s;\n", themeblvd_get_font_weight($font) );
		$print .= sprintf("\tletter-spacing: %s;\n", themeblvd_get_option('font_header_sm_sp') );
		$print .= sprintf("\ttext-transform: %s;\n", themeblvd_get_text_transform($font) );

		$print .= "}\n";
	}

	// Quote Font
	$font = themeblvd_get_option('font_quote');

	if ( $font ) {

		$print .= "blockquote,\n";
		$print .= ".epic-thumb blockquote,\n";
		$print .= ".entry-content blockquote,\n";
		$print .= ".testimonial-text.entry-content {\n";

		$print .= sprintf("\tfont-family: %s;\n", themeblvd_get_font_face($font) );
		$print .= sprintf("\tfont-style: %s;\n", themeblvd_get_font_style($font) );
		$print .= sprintf("\tfont-weight: %s;\n", themeblvd_get_font_weight($font) );
		$print .= sprintf("\tletter-spacing: %s;\n", themeblvd_get_option('font_quote_sp') );
		$print .= sprintf("\ttext-transform: %s;\n", themeblvd_get_text_transform($font) );

		$print .= "}\n";

	}

	// Meta Font
	$font = themeblvd_get_option('font_meta');

	if ( $font ) {

		$print .= '.post-date,';
		$print .= ".entry-header .entry-meta,\n";
		$print .= ".tb-mini-post-list .entry-meta,\n";
		$print .= ".post_grid .entry-meta,\n";
		$print .= ".tb-post-slider .entry-meta,\n";
		$print .= ".tweeple-feed .tweet-meta,\n";
		$print .= "#comments .comment-body .comment-metadata,\n";
		$print .= "blockquote cite {\n";

		$print .= sprintf("\tfont-family: %s;\n", themeblvd_get_font_face($font) );
		$print .= sprintf("\tfont-style: %s;\n", themeblvd_get_font_style($font) );
		$print .= sprintf("\tfont-weight: %s;\n", themeblvd_get_font_weight($font) );
		$print .= sprintf("\tletter-spacing: %s;\n", themeblvd_get_option('font_meta_sp') );
		$print .= sprintf("\ttext-transform: %s;\n", themeblvd_get_text_transform($font) );

		$print .= "}\n";

	}

	// Featured Image Title Font
	$font = themeblvd_get_option('font_epic');

	if ( $font ) {

		$print .= ".epic-thumb .entry-title,\n";
		$print .= ".tb-jumbotron .text-large {\n";

		$print .= sprintf("\tfont-family: %s;\n", themeblvd_get_font_face($font) );
		$print .= sprintf("\tfont-style: %s;\n", themeblvd_get_font_style($font) );
		$print .= sprintf("\tfont-weight: %s;\n", themeblvd_get_font_weight($font) );
		$print .= sprintf("\tletter-spacing: %s;\n", themeblvd_get_option('font_epic_sp') );

		$print .= "}\n";

		$print .= ".epic-thumb .entry-title {\n";
		$print .= sprintf("\ttext-transform: %s;\n", themeblvd_get_text_transform($font) );
		$print .= "}\n";

		$print .= "@media (min-width: 992px) {\n";
		$print .= "\t.epic-thumb .epic-thumb-header .entry-title {\n";
		$print .= sprintf("\t\tfont-size: %s;\n", themeblvd_get_font_size($font) );
		$print .= "\t}\n";
		$print .= "}\n";

	}

	// Menu Font
	$font = themeblvd_get_option('font_menu');

	if ( $font ) {

		$print .= ".header-nav .tb-primary-menu > li > .menu-btn,\n";
		$print .= ".tb-sticky-menu .tb-primary-menu > li > .menu-btn,\n";
		$print .= ".tb-side-panel .menu > li > .menu-btn,\n";
		$print .= ".tb-mobile-menu-wrapper .tb-mobile-menu > li > .menu-btn {\n";
		$print .= sprintf("\tfont-family: %s;\n", themeblvd_get_font_face($font) );
		$print .= sprintf("\tfont-style: %s;\n", themeblvd_get_font_style($font) );
		$print .= sprintf("\tfont-weight: %s;\n", themeblvd_get_font_weight($font) );
		$print .= sprintf("\tletter-spacing: %s;\n", themeblvd_get_option('font_menu_sp') );
		$print .= sprintf("\ttext-transform: %s;\n", themeblvd_get_text_transform($font) );
		$print .= "}\n";

		$print .= ".header-nav .tb-primary-menu > li > .menu-btn {\n";
		$print .= sprintf("\tfont-size: %s;\n", themeblvd_get_font_size($font) );
		$print .= "}\n";

	}

	// Links
	$print .= "a {\n";
	$print .= sprintf("\tcolor: %s;\n", themeblvd_get_option('link_color'));
	$print .= "}\n";

	$print .= "a:hover {\n";
	$print .= sprintf("\tcolor: %s;\n", themeblvd_get_option('link_hover_color'));
	$print .= "}\n";

	$print .= ".site-footer a {\n";
	$print .= sprintf("\tcolor: %s;\n", themeblvd_get_option('footer_link_color'));
	$print .= "}\n";

	$print .= ".site-footer a:hover {\n";
	$print .= sprintf("\tcolor: %s;\n", themeblvd_get_option('footer_link_hover_color'));
	$print .= "}\n";

	/*------------------------------------------------------------*/
	/* Header
	/*------------------------------------------------------------*/

	$print .= "\n/* Header */\n";

	$bg = themeblvd_get_option('header_bg_color');

	// Background color
	$print .= "@media (min-width: 768px) {\n";

	if ( themeblvd_config('suck_up') ) {

		$print .= "\t.site-header.transparent {\n";
		$print .= "\t\tbackground-color: transparent;\n";

		if ( $opacity = themeblvd_get_option('header_trans_bg_color_opacity') ) {
			$print .= sprintf( "\t\tbackground-color: %s;\n", themeblvd_get_rgb( themeblvd_get_option('header_trans_bg_color'), $opacity ) );
		}

		$print .= "\t}\n";

	} else {

		$print .= "\t.site-header {\n";
		$print .= sprintf("\t\tbackground-color: %s;\n", $bg);
		$print .= "\t}\n";

	}

	$print .= "\t.tb-sticky-menu {\n";
	$print .= sprintf("\t\tbackground-color: %s;\n", $bg);
	$print .= sprintf("\t\tbackground-color: %s;\n", themeblvd_get_rgb($bg, '0.9'));
	$print .= "\t}\n";

	$print .= "}\n";

	// Mobile Background
	$print .= "@media (max-width: 767px) {\n";
	$print .= "\t.site-header {\n";
	$print .= sprintf("\t\tbackground-color: %s;\n", themeblvd_get_option('header_mobile_bg_color'));
	$print .= "\t}\n";
	$print .= "}\n";

	// Get logo attributes
	if ( themeblvd_config('suck_up') ) {

		$logo = themeblvd_get_option('trans_logo');

		if ( ! $logo || ( ! empty($logo['type']) && $logo['type'] == 'default' ) ) {
			$logo = themeblvd_get_option('logo');
		}

	} else {

		$logo = themeblvd_get_option('logo');

	}

	// Height
	if ( $logo['type'] == 'image' ) {
		$h = 50 + intval($logo['image_height']);
	} else {
		$h = 76;
	}

	$print .= "@media (min-width: 768px) {\n";

	if ( themeblvd_get_option('header_stretch') ) {
		$print .= "\t.site-header.stretch .header-toolbar:before,\n";
	}

	$print .= "\t.header-content > .wrap,\n";
	$print .= "\t.has-sticky #top {\n";
	$print .= sprintf("\t\theight: %spx;\n", $h);
	$print .= "\t}\n";

	if ( themeblvd_get_option('header_stretch') ) {
		$print .= "\t.site-header.stretch .header-toolbar:before {\n";
		$print .= sprintf("\t\ttop: -%spx;\n", round( ($h - 58) / 2 ) );
		$print .= "\t}\n";
	}

	$print .= "}\n";

	// Logo width
	if ( $logo['type'] == 'image' ) {
		$w = $logo['image_width'];
	} else {
		$w = '200';
	}

	$print .= "@media (min-width: 768px) {\n";
	$print .= "\t.site-header .header-logo {\n";
	$print .= sprintf("\t\tmax-width: %spx;\n", $w);
	$print .= "\t}\n";
	$print .= "}\n";

	// Main Menu Dropdowns
	$print .= ".tb-primary-menu ul.non-mega-sub-menu,\n";
	$print .= ".tb-primary-menu .sf-mega {\n";
	$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_option('menu_drop_bg_color') );
	$print .= "}\n";

	/*------------------------------------------------------------*/
	/* Footer
	/*------------------------------------------------------------*/

	$print .= "\n/* Footer */\n";

	$print .= ".site-footer {\n";
	$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_option('footer_bg_color') );
	$print .= "}\n";

	$print .= "body,";
	$print .= ".site-footer .footer-sub-content {\n";
	$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_option('copyright_bg_color') );
	$print .= "}\n";

	/*------------------------------------------------------------*/
	/* Side Panels
	/*------------------------------------------------------------*/

	$print .= "\n/* Side Panels */\n";

	if ( themeblvd_do_side_panel() ) {
		$print .= ".tb-side-panel,\n";
	}

	$print .= ".tb-mobile-menu-wrapper {\n";
	$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_option('side_bg_color'));
	$print .= "}\n";

	/*------------------------------------------------------------*/
	/* Widgets
	/*------------------------------------------------------------*/

	if ( themeblvd_get_option('widget_bg') ) {

		$print .= "\n/* Widgets */\n";

		$print .= ".tb-widget-bg .fixed-sidebar .widget {\n";
		$print .= sprintf("\tbackground-color: %s;\n", themeblvd_get_option('widget_bg_color'));
		$print .= "}\n";
	}

	/*------------------------------------------------------------*/
	/* Custom CSS
	/*------------------------------------------------------------*/

	if ( $custom = themeblvd_get_option('custom_styles') ) {
		$print .= "\n/* =Custom CSS\n";
		$print .= "-----------------------------------------------*/\n\n";
		$print .= $custom;
	}

	// Sanitize
	$print = wp_kses( $print, array() );
	$print = htmlspecialchars_decode( $print );

	// Final output
	if ( $print ) {
		wp_add_inline_style( 'jumpstart-base', apply_filters('jumpstart_ag_css_output', $print) );
	}

}
add_action('wp_enqueue_scripts', 'jumpstart_ag_css', 25);

/**
 * Add CSS classes and parralax data() to header
 *
 * @since 2.1.0
 */
function jumpstart_ag_header_class( $class ) {

	// Remove sticky class, because we're adding it to the body
	if ( ( $key = array_search('has-sticky', $class) ) !== false ) {
		unset( $class[$key] );
	}

	// Background brightness
	if ( themeblvd_config('suck_up') ) {

		$class[] = 'trans-' . themeblvd_get_option('header_trans_bg_color_brightness');

		if ( themeblvd_get_option('header_trans_hide_border') ) {
			$class[] = 'no-border';
		} else {
			$class[] = 'has-border';
		}

	} else {

		$class[] = themeblvd_get_option('header_bg_color_brightness');

	}

	$class[] = 'mobile-' . themeblvd_get_option('header_mobile_bg_color_brightness');

	// Dropdown brightness
	$class[] = 'drop-' . themeblvd_get_option('menu_drop_bg_color_brightness');

	// Location of menu
	$menu_args = themeblvd_get_wp_nav_menu_args('primary');

	if ( has_nav_menu( $menu_args['theme_location'] ) ) {
		$class[] = 'menu-' . themeblvd_get_option('menu_placement');
	}

	// Whether to stretch the header full width
	if ( themeblvd_get_option('header_stretch') ) {
		$class[] = 'stretch';
	}

	return $class;
}
add_filter('themeblvd_header_class', 'jumpstart_ag_header_class', 10, 2);

/**
 * Remove header top
 */
remove_action('themeblvd_header_top', 'themeblvd_header_top_default');

/**
 * Remove default menu callback
 */
remove_action('themeblvd_header_menu', 'themeblvd_header_menu_default');

/**
 * Custom menu callback with header toolbar added
 *
 * @since 2.1.0
 */
function jumpstart_ag_header_menu() {

	/**
	 * @hooked null
	 */
	do_action('themeblvd_header_menu_before');

	?>

	<nav id="access" class="header-nav">
		<div class="wrap clearfix">

			<?php if ( themeblvd_get_option('searchform') == 'show' || themeblvd_do_cart() || themeblvd_do_lang_selector() || themeblvd_do_side_panel() ) : ?>
				<ul class="header-toolbar list-unstyled">

					<?php if ( themeblvd_do_cart() ) : ?>
						<li class="top-cart"><?php themeblvd_cart_popup_trigger(); ?></li>
					<?php endif; ?>

					<?php if ( themeblvd_do_lang_selector() ) : ?>
						<li class="top-lang">
							<a href="#" class="tb-lang-trigger" title="<?php echo themeblvd_get_local('language'); ?>" data-toggle="modal" data-target="#floating-lang-switcher">
								<i class="fa fa-globe"></i>
							</a>
						</li>
					<?php endif; ?>

					<?php if ( themeblvd_get_option('searchform') == 'show' ) : ?>
						<li class="top-search"><?php themeblvd_floating_search_trigger(); ?></li>
					<?php endif; ?>

					<?php if ( themeblvd_do_side_panel() ) : ?>
						<li class="top-side-panel"><?php themeblvd_side_trigger(); ?></li>
					<?php endif; ?>

				</ul>
			<?php endif; ?>

			<?php wp_nav_menu( themeblvd_get_wp_nav_menu_args('primary') ); ?>

			<?php
			/**
			 * @hooked null
			 */
			do_action('themeblvd_header_menu_addon');
			?>

		</div><!-- .wrap (end) -->
	</nav><!-- #access (end) -->

	<?php
	/**
	 * @hooked null
	 */
	do_action('themeblvd_header_menu_after');

}
add_action('themeblvd_header_addon', 'jumpstart_ag_header_menu');

/**
 * Add CSS classes to side panel and mobile menu
 *
 * @since 2.1.0
 */
function jumpstart_ag_side_panel_class( $class ) {
	return array_merge( $class, array( themeblvd_get_option('side_bg_color_brightness') ) );
}
add_filter('themeblvd_side_panel_class', 'jumpstart_ag_side_panel_class');
add_filter('themeblvd_mobile_panel_class', 'jumpstart_ag_side_panel_class');

/**
 * Add social icons to side panel.
 *
 * @since 2.1.0
 */
function jumpstart_ag_side_panel_contact() {

	if ( themeblvd_get_option('social_panel') ) {

		$color = 'light';

		if ( themeblvd_get_option('side_text_color') == 'dark' ) {
			$color = 'grey';
		}

		echo themeblvd_get_contact_bar(null, array(
			'tooltip'	=> false,
			'style'		=> $color,
			'class'		=> 'to-mobile'
		));

	}

}
add_action('themeblvd_side_panel', 'jumpstart_ag_side_panel_contact', 30);

/**
 * Add CSS class to sticky header panel for color brightness.
 *
 * @since 2.1.0
 */
function jumpstart_ag_sticky_class( $class ) {

	$class[] = themeblvd_get_option('header_bg_color_brightness');
	$class[] = 'drop-' . themeblvd_get_option('menu_drop_bg_color_brightness');

	if ( themeblvd_get_option('header_stretch') ) {
		$class[] = 'stretch';
	}

	return $class;
}
add_filter('themeblvd_sticky_class', 'jumpstart_ag_sticky_class');

/**
 * Add CSS classes to footer
 *
 * @since 2.1.0
 */
function jumpstart_ag_footer_class( $class ) {

	$class[] = themeblvd_get_option('footer_bg_color_brightness');
	$class[] = 'copyright-' . themeblvd_get_option('copyright_bg_color_brightness');

	return $class;
}
add_filter('themeblvd_footer_class', 'jumpstart_ag_footer_class');

/**
 * Height of the header, not including the logo.
 * Used with "suck up" feature.
 *
 * @since 2.1.0
 */
function jumpstart_ag_top_height_addend( $addend, $context ) {
	return 50; /* Default logo 25px + 50px = 75px */
}
add_filter('themeblvd_top_height_addend', 'jumpstart_ag_top_height_addend', 10, 2);

/**
 * Change main menu sub icon from fa-caret-* to
 * fa-angle-*.
 *
 * @since 2.1.0
 */
function jumpstart_ag_menu_sub_indicator( $html ) {
	return str_replace('caret', 'angle', $html);
}
add_filter('themeblvd_menu_sub_indicator', 'jumpstart_ag_menu_sub_indicator');

/**
 * Remove framework default footer copyright area.
 *
 * @since 2.1.0
 */
remove_action('themeblvd_footer_sub_content', 'themeblvd_footer_sub_content_default');

/**
 * Add custom footer copyright area.
 *
 * @since 2.1.0
 */
function jumpstart_ag_footer_sub_content() {

	$bg = themeblvd_get_option('copyright_bg_color_brightness');

	echo '<div class="footer-sub-content '.$bg.'">';
	echo '<div class="wrap clearfix">';

	if ( themeblvd_get_option('social_footer') ) {

		$style = 'light'; // "light" means it's meant to display over dark BG

		if ( $bg == 'light' ) {
			$style = 'dark';
		}

		echo themeblvd_get_contact_bar(null, array('tooltip' => 'top', 'style' => $style));

	}

	echo '<div class="copyright">' . apply_filters( 'themeblvd_footer_copyright', themeblvd_get_content( themeblvd_get_option('footer_copyright') ) ) . '</div>';

	$menu_args = themeblvd_get_wp_nav_menu_args('footer');

	if ( has_nav_menu( $menu_args['theme_location'] ) ) {
		echo '<div class="footer-nav">';
		wp_nav_menu( $menu_args );
		echo '</div>';
	}

	echo '</div><!-- .wrap (end) -->';
	echo '</div><!-- .footer-sub-content (end) -->';
}
add_action('themeblvd_footer_sub_content', 'jumpstart_ag_footer_sub_content');

/**
 * More narrow sidebars
 *
 * @since 2.1.0
 */
function jumpstart_ag_sidebar_layouts( $layouts, $stack ) {

	// More narrow sidebars
	$layouts['sidebar_right']['columns']['content'] = "col-{$stack}-9";
	$layouts['sidebar_right']['columns']['right'] = "col-{$stack}-3";

	$layouts['sidebar_left']['columns']['content'] = "col-{$stack}-9";
	$layouts['sidebar_left']['columns']['left'] = "col-{$stack}-3";

	return $layouts;
}
add_filter('themeblvd_sidebar_layouts', 'jumpstart_ag_sidebar_layouts', 9, 2);

/**
 * Get modal window for WPML language switcher.
 *
 * @since 1.0.0
 */
function jumpstart_ag_get_lang_popup() {

	$output  = '<div id="floating-lang-switcher" class="tb-lang-popup modal fade">';
	$output .= '<div class="modal-dialog modal-sm">';
	$output .= '<div class="modal-content">';
	$output .= '<div class="modal-header">';
	$output .= '<button type="button" class="close" data-dismiss="modal" aria-label="'.themeblvd_get_local('close').'"><span aria-hidden="true">&times;</span></button>';
	$output .= '<h4 class="modal-title">'.themeblvd_get_local('language').'</h4>';
	$output .= '</div>';
	$output .= '<div class="modal-body clearfix">';

	if ( function_exists('icl_get_languages') ) {

		$langs = icl_get_languages('skip_missing=1');

		if ( $langs ) {

			$output .= '<ul class="tb-lang-selector list-unstyled">';

			foreach ( $langs as $lang ) {

				$class = 'lang-'.$lang['language_code'];

				if ( $lang['active'] ) {
					$class .= ' active';
				}

				$output .= '<li class="'.$class.'">';

				if ( $lang['active'] ) {
					$output .= sprintf('<span title="%1$s">%1$s</span>', $lang['translated_name'] );
				} else {
					$output .= sprintf('<a href="%1$s" title="%2$s">%2$s</a>', $lang['url'], $lang['translated_name'] );
				}

				$output .= '</li>';

			}

			$output .= '</ul>';
		}
	}

	$output .= '</div><!-- .modal-body (end) -->';
	$output .= '</div><!-- .modal-content (end) -->';
	$output .= '</div><!-- .modal-dialog (end) -->';
	$output .= '</div><!-- .tb-wpml-switcher (end) -->';

	return apply_filters('jumpstart_ag_lang_selector', $output);
}

/**
 * Output modal window for WPML language switcher.
 *
 * @since 2.1.0
 */
function jumpstart_ag_lang_popup() {
	if ( themeblvd_do_lang_selector() ) {
		echo jumpstart_ag_get_lang_popup();
	}
}
add_action( 'themeblvd_after', 'jumpstart_ag_lang_popup' );

/**
 * Allow main menu to be empty.
 *
 * @since 2.1.0
 */
function jumpstart_ag_primary_menu_args( $args ) {
	$args['fallback_cb'] = false;
	return $args;
}
add_filter('themeblvd_primary_menu_args', 'jumpstart_ag_primary_menu_args');

/**
 * Filter args that get filtered in when sidebars
 * are registered.
 *
 * @since 2.1.0
 */
function themeblvd_agent_sidebar_args( $args, $sidebar, $location ) {

	if ( in_array( $location, array('sidebar_left', 'sidebar_right') ) && themeblvd_get_option('widget_bg_color_brightness') == 'dark' ) {
		$args['before_widget'] = str_replace('class="widget ', 'class="widget text-light ', $args['before_widget']);
	}

	return $args;

}
add_filter('themeblvd_default_sidebar_args', 'themeblvd_agent_sidebar_args', 10, 3);
add_filter('themeblvd_custom_sidebar_args', 'themeblvd_agent_sidebar_args', 10, 3);
