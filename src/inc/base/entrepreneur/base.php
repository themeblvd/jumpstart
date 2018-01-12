<?php
/**
 * Theme Base: Entrepreneur, Functions
 *
 * @author    Jason Bobich <info@themeblvd.com>
 * @copyright 2009-2017 Theme Blvd
 * @package   @@name-package
 * @since     @@name-package 2.0.0
 */

/**
 * Include modifications to Theme Options page.
 */
include_once( themeblvd_get_base_path( 'entrepreneur' ) . '/options.php' );

/**
 * Include preset styles for Theme Options.
 */
include_once( themeblvd_get_base_path( 'entrepreneur' ) . '/presets.php' );

/**
 * Height of the header. Used with "suck up"
 * feature.
 *
 * @since @@name-package 2.0.0
 *
 * @return int $height Height of header area.
 */
function jumpstart_ent_top_height() {

	// User-configured header height.
	$height = intval( themeblvd_get_option( 'header_height' ) );

	// Add 1px for trans header design's bottom border.
	$height += 1;

	// Top bar height.
	if ( themeblvd_ent_has_header_info() ) {

		if ( themeblvd_get_option( 'top_mini' ) ) {

			$height += 36; // mini

		} else {

			$height += 48; // standard

		}

		// Add 1px for trans header top bar design's border.
		$height += 1; // standard 48px + 1px bottom border.

	}

	return $height;

}
add_filter( 'themeblvd_top_height', 'jumpstart_ent_top_height' );

/**
 * Include Google fonts, if needed.
 *
 * @since @@name-package 2.0.0
 */
function jumpstart_ent_include_fonts() {

	themeblvd_include_fonts(
		themeblvd_get_option( 'font_body' ),
		themeblvd_get_option( 'font_header' ),
		themeblvd_get_option( 'font_quote' ),
		themeblvd_get_option( 'font_meta' ),
		themeblvd_get_option( 'font_epic' ),
		themeblvd_get_option( 'font_menu' )
	);

}
add_action( 'wp_head', 'jumpstart_ent_include_fonts', 5 );

/**
 * Add custom styles for theme options.
 *
 * This generate output string to pass to
 * wp_add_inline_style().
 *
 * @since @@name-package 2.0.0
 */
function jumpstart_ent_css() {

	$print = '';

	$header_bg_type = themeblvd_get_option( 'header_bg_type' );

	$header_bg_color = themeblvd_get_option( 'header_bg_color' );

	// Primary Font
	$font = themeblvd_get_option( 'font_body' );

	if ( $font ) {

		$print .= "html,\n";
		$print .= "body {\n";
		$print .= sprintf( "\tfont-family: %s;\n", themeblvd_get_font_face( $font ) );
		$print .= sprintf( "\tfont-size: %s;\n", themeblvd_get_font_size( $font ) );
		$print .= sprintf( "\tfont-style: %s;\n", themeblvd_get_font_style( $font ) );
		$print .= sprintf( "\tfont-weight: %s;\n", themeblvd_get_font_weight( $font ) );
		$print .= sprintf( "\ttext-transform: %s;\n", themeblvd_get_text_transform( $font ) );
		$print .= "}\n";

	}

	// Header Font
	$font = themeblvd_get_option( 'font_header' );

	if ( $font ) {

		if ( themeblvd_installed( 'gravityforms' ) && themeblvd_supports( 'plugins', 'gravityforms' ) ) {

			$print .= ".tb-gforms-compat .gform_wrapper .gsection .gfield_label,\n";
			$print .= ".tb-gforms-compat .gform_wrapper h2.gsection_title,\n";
			$print .= ".tb-gforms-compat .gform_wrapper h3.gform_title,\n";

		}

		$print .= ".sf-menu .mega-section-header,\n";
		$print .= "h1, h2, h3, h4, h5, h6 {\n";
		$print .= sprintf( "\tfont-family: %s;\n", themeblvd_get_font_face( $font ) );
		$print .= sprintf( "\tfont-style: %s;\n", themeblvd_get_font_style( $font ) );
		$print .= sprintf( "\tfont-weight: %s;\n", themeblvd_get_font_weight( $font ) );
		$print .= sprintf( "\ttext-transform: %s;\n", themeblvd_get_text_transform( $font ) );
		$print .= "}\n";

	}

	// Small Header Font
	$font = themeblvd_get_option( 'font_header_sm' );

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

		$print .= sprintf( "\tfont-family: %s;\n", themeblvd_get_font_face( $font ) );
		$print .= sprintf( "\tfont-style: %s;\n", themeblvd_get_font_style( $font ) );
		$print .= sprintf( "\tfont-weight: %s;\n", themeblvd_get_font_weight( $font ) );
		$print .= sprintf( "\tletter-spacing: %s;\n", themeblvd_get_option( 'font_header_sm_sp' ) );
		$print .= sprintf( "\ttext-transform: %s;\n", themeblvd_get_text_transform( $font ) );

		$print .= "}\n";

	}

	// Quote Font
	$font = themeblvd_get_option( 'font_quote' );

	if ( $font ) {

		$print .= "blockquote,\n";
		$print .= ".epic-thumb blockquote,\n";
		$print .= ".entry-content blockquote,\n";
		$print .= ".testimonial-text.entry-content {\n";

		$print .= sprintf( "\tfont-family: %s;\n", themeblvd_get_font_face( $font ) );
		$print .= sprintf( "\tfont-style: %s;\n", themeblvd_get_font_style( $font ) );
		$print .= sprintf( "\tfont-weight: %s;\n", themeblvd_get_font_weight( $font ) );
		$print .= sprintf( "\tletter-spacing: %s;\n", themeblvd_get_option( 'font_quote_sp' ) );
		$print .= sprintf( "\ttext-transform: %s;\n", themeblvd_get_text_transform( $font ) );

		$print .= "}\n";

	}

	// Meta Font
	$font = themeblvd_get_option( 'font_meta' );

	if ( $font ) {

		$print .= '.post-date,';
		$print .= ".entry-header .entry-meta,\n";
		$print .= ".post_grid .entry-meta,\n";
		$print .= ".tb-post-slider .entry-meta,\n";
		$print .= ".tweeple-feed .tweet-meta,\n";
		$print .= "#comments .comment-body .comment-metadata,\n";
		$print .= "blockquote cite {\n";

		$print .= sprintf( "\tfont-family: %s;\n", themeblvd_get_font_face( $font ) );
		$print .= sprintf( "\tfont-style: %s;\n", themeblvd_get_font_style( $font ) );
		$print .= sprintf( "\tfont-weight: %s;\n", themeblvd_get_font_weight( $font ) );
		$print .= sprintf( "\tletter-spacing: %s;\n", themeblvd_get_option( 'font_meta_sp' ) );
		$print .= sprintf( "\ttext-transform: %s;\n", themeblvd_get_text_transform( $font ) );

		$print .= "}\n";

	}

	// Featured Image Title Font
	$font = themeblvd_get_option( 'font_epic' );

	if ( $font ) {

		$print .= ".epic-thumb .entry-title,\n";
		$print .= ".tb-jumbotron .text-large {\n";

		$print .= sprintf( "\tfont-family: %s;\n", themeblvd_get_font_face( $font ) );
		$print .= sprintf( "\tfont-style: %s;\n", themeblvd_get_font_style( $font ) );
		$print .= sprintf( "\tfont-weight: %s;\n", themeblvd_get_font_weight( $font ) );
		$print .= sprintf( "\tletter-spacing: %s;\n", themeblvd_get_option( 'font_epic_sp' ) );

		$print .= "}\n";

		$print .= ".epic-thumb .entry-title {\n";
		$print .= sprintf( "\ttext-transform: %s;\n", themeblvd_get_text_transform( $font ) );
		$print .= "}\n";

		$print .= "@media (min-width: 992px) {\n";
		$print .= "\t.epic-thumb .epic-thumb-header .entry-title {\n";
		$print .= sprintf( "\t\tfont-size: %s;\n", themeblvd_get_font_size( $font ) );
		$print .= "\t}\n";
		$print .= "}\n";

	}

	// Links
	$print .= "a {\n";
	$print .= sprintf( "\tcolor: %s;\n", themeblvd_get_option( 'link_color' ) );
	$print .= "}\n";

	$print .= "a:hover {\n";
	$print .= sprintf( "\tcolor: %s;\n", themeblvd_get_option( 'link_hover_color' ) );
	$print .= "}\n";

	$print .= ".site-footer a {\n";
	$print .= sprintf( "\tcolor: %s;\n", themeblvd_get_option( 'footer_link_color' ) );
	$print .= "}\n";

	$print .= ".site-footer a:hover {\n";
	$print .= sprintf( "\tcolor: %s;\n", themeblvd_get_option( 'footer_link_hover_color' ) );
	$print .= "}\n";

	// Buttons
	$border = themeblvd_get_option( 'btn_border' );

	if ( '0px' === $border ) {

		$print .= ".btn,\n";
		$print .= "input[type=\"submit\"],\n";
		$print .= "input[type=\"reset\"],\n";
		$print .= "input[type=\"button\"],\n";
		$print .= ".button,\n";
		$print .= "button {\n";
		$print .= sprintf( "\tborder-radius: %s;\n", themeblvd_get_option( 'btn_corners' ) );
		$print .= "}\n";

		$print .= ".btn:not(.tb-custom-button),\n";
		$print .= "input[type=\"submit\"],\n";
		$print .= "input[type=\"reset\"],\n";
		$print .= "input[type=\"button\"],\n";
		$print .= ".button,\n";
		$print .= "button {\n";
		$print .= "\tborder: none;\n";
		$print .= "}\n";

	} else {

		$print .= ".btn,\n";
		$print .= "input[type=\"submit\"],\n";
		$print .= "input[type=\"reset\"],\n";
		$print .= "input[type=\"button\"],\n";
		$print .= ".button,\n";
		$print .= "button {\n";
		$print .= sprintf( "\tborder-radius: %s;\n", themeblvd_get_option( 'btn_corners' ) );
		$print .= sprintf( "\tborder-width: %s;\n", themeblvd_get_option( 'btn_border' ) );
		$print .= "}\n";

	}

	$btn = themeblvd_get_option( 'btn_default' );

	if ( $btn ) {

		$print .= ".btn-default,\n";
		$print .= "input[type=\"submit\"],\n";
		$print .= "input[type=\"reset\"],\n";
		$print .= "input[type=\"button\"],\n";
		$print .= ".button,\n";
		$print .= "button {\n";

		if ( $btn['include_bg'] ) {

			$print .= sprintf( "\tbackground-color: %s;\n", $btn['bg'] );

		} else {

			$print .= "\tbackground-color: transparent;\n";

		}

		if ( $btn['include_border'] ) {

			$print .= sprintf( "\tborder-color: %s;\n", $btn['border'] );

		} else {

			$print .= "\tborder: none;\n";

		}

		$print .= "\t-webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.2);\n";
		$print .= "\tbox-shadow: inset 0 1px 0 rgba(255,255,255,.2);\n";

		$print .= sprintf( "\tcolor: %s;\n", $btn['text'] );

		$print .= "}\n";

		$print .= ".btn-default:hover,\n";
		$print .= ".btn-default:focus,\n";
		$print .= ".btn-default:active,\n";
		$print .= ".btn-default.active,\n";
		$print .= ".btn-default:active:hover,\n";
		$print .= ".btn-default.active:hover,\n";
		$print .= ".btn-default.active:focus,\n";
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
		$print .= "button:active {\n";

		$print .= sprintf( "\tbackground-color: %s;\n", $btn['bg_hover'] );

		if ( $btn['include_border'] ) {

			$print .= sprintf( "\tborder-color: %s;\n", $btn['border'] );

		}

		$print .= "\t-webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.1);\n";
		$print .= "\tbox-shadow: inset 0 1px 0 rgba(255,255,255,.1);\n";

		$print .= sprintf( "\tcolor: %s;\n", $btn['text_hover'] );
		$print .= "}\n";

	}

	$btn = themeblvd_get_option( 'btn_primary' );

	if ( $btn ) {

		$print .= ".primary,\n";
		$print .= ".bg-primary,\n";
		$print .= ".btn-primary,\n";
		$print .= "a.alt,\n";
		$print .= "button.alt,\n";
		$print .= "input.alt,\n";
		$print .= ".label-primary,\n";
		$print .= ".panel-primary > .panel-heading {\n";

		if ( $btn['include_bg'] ) {

			$print .= sprintf( "\tbackground-color: %s;\n", $btn['bg'] );

		} else {

			$print .= "\tbackground-color: transparent;\n";

		}

		if ( $btn['include_border'] ) {

			$print .= sprintf( "\tborder-color: %s;\n", $btn['border'] );

		} else {

			$print .= "\tborder: none;\n";

		}

		$print .= sprintf( "\tcolor: %s;\n", $btn['text'] );

		$print .= "}\n";

		$print .= ".panel-primary > .panel-heading > .panel-title {\n";
		$print .= sprintf( "\tcolor: %s;\n", $btn['text'] );
		$print .= "}\n";

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

		$print .= sprintf( "\tbackground-color: %s;\n", $btn['bg_hover'] );

		if ( $btn['include_border'] ) {

			$print .= sprintf( "\tborder-color: %s;\n", $btn['border'] );

		}

		$print .= sprintf( "\tcolor: %s;\n", $btn['text_hover'] );

		$print .= "}\n";

		$print .= ".panel-primary {\n";

		if ( $btn['include_border'] ) {

			$print .= sprintf( "\tborder-color: %s;\n", $btn['border'] );

		} else {

			$print .= "\tborder: none;\n";

		}

		$print .= "}\n";
	}

	// Highlight Color
	$highlight = themeblvd_get_option( 'highlight' );

	if ( themeblvd_supports( 'plugins', 'wpml' ) && themeblvd_installed( 'wpml' ) ) {

		$print .= ".tb-lang-popup a:hover,\n";
		$print .= ".tb-lang-popup a:focus,\n";

	}

	$print .= ".tb-tag-cloud .tagcloud a:hover,\n";
	$print .= ".tb-tags a:hover,\n";
	$print .= ".tb-tags a:focus,\n";
	$print .= ".btn-share:hover,\n";
	$print .= ".btn-share:focus {\n";
	$print .= sprintf( "\tbackground-color: %s\n", $highlight );
	$print .= "}\n";

	$print .= ".tb-thumb-link .thumb-link-icon,\n";
	$print .= ".post_showcase .showcase-item.has-title .featured-item.showcase .tb-thumb-link:after,\n";
	$print .= ".post_showcase .showcase-item.has-title .featured-item.showcase.tb-thumb-link:after {\n";
	$print .= sprintf( "\tbackground-color: %s;\n", themeblvd_get_rgb( $highlight, '0.8' ) );
	$print .= "}\n";

	$print .= ".tb-floating-search .tb-search .search-input:focus,\n";
	$print .= ".tooltip-inner {\n";
	$print .= sprintf( "\tborder-color: %s;\n", $highlight );
	$print .= "}\n";
	$print .= ".tooltip.top .tooltip-arrow,\n";
	$print .= ".tb-contact-popover.bottom {\n";
	$print .= sprintf( "\tborder-top-color: %s;\n", $highlight );
	$print .= "}\n";
	$print .= ".tooltip.bottom .tooltip-arrow,\n";
	$print .= ".tb-contact-popover.bottom > .arrow:after {\n";
	$print .= sprintf( "\tborder-bottom-color: %s;\n", $highlight );
	$print .= "}\n";

	// Box Titles & Widgets
	$widget_style = themeblvd_get_option( 'widget_style' );

	if ( themeblvd_get_option( 'box_titles' ) ) {

		$print .= ".box-title,\n";

		if ( 'standard' === $widget_style ) {
			$print .= ".fixed-sidebar .widget-title,\n";
		}

		if ( themeblvd_installed( 'woocommerce' ) && themeblvd_supports( 'plugins', 'woocommerce' ) ) {
			$print .= ".products > h2,\n";
		}

		$print .= "#comments-title,\n";
		$print .= ".tb-info-box .info-box-title,\n";
		$print .= ".tb-related-posts .related-posts-title {\n";
		$print .= "\tborder-bottom: 2px solid #f2f2f2;\n";
		$print .= "\tborder-color: rgba(220,220,220,.4);\n";
		$print .= "\tpadding-bottom: .83em;\n";
		$print .= "\tposition: relative;\n";
		$print .= "}\n";

		$print .= ".box-title:before,\n";
		$print .= ".box-title:after,\n";

		if ( 'standard' === $widget_style ) {

			$print .= ".fixed-sidebar .widget-title:before,\n";

		}

		if ( themeblvd_installed( 'woocommerce' ) && themeblvd_supports( 'plugins', 'woocommerce' ) ) {

			$print .= ".products > h2:before,\n";

		}

		$print .= "#comments-title:before,\n";
		$print .= ".info-box-title:before,\n";
		$print .= ".tb-related-posts .related-posts-title:before {\n";
		$print .= "\tcontent: \"\";\n";
		$print .= sprintf( "\tbackground: %s; /* highlight */\n", $highlight );
		$print .= "\tposition: absolute;\n";
		$print .= "\tbottom: -2px;\n";

		if ( is_rtl() ) {

			$print .= "\tright: 0;\n";

		} else {

			$print .= "\tleft: 0;\n";

		}

		$print .= "\twidth: 75px;\n";
		$print .= "\theight: 2px;\n";
		$print .= "\tz-index: 2;\n";
		$print .= "}\n";

		$print .= ".box-title {\n";
		$print .= "\tborder-bottom-color: transparent;\n";
		$print .= "}\n";
		$print .= ".box-title:before {\n";
		$print .= "\twidth: 50px;\n";
		$print .= "}\n";
		$print .= ".box-title:after {\n";
		$print .= "\tbackground: #f2f2f2;\n";
		$print .= "\tbackground: rgba(220,220,220,.4);\n";
		$print .= "\twidth: 150px;\n";
		$print .= "\tz-index: 1;\n";
		$print .= "}\n";

		if ( 'standard' === $widget_style && 'dark' === themeblvd_get_option( 'widget_bg_brightness' ) ) {

			$print .= ".fixed-sidebar .widget-title {\n";
			$print .= "\tborder-color: rgba(0,0,0,.9)";
			$print .= "}\n";

		}
	}

	$print .= sprintf( ".fixed-sidebar .widget.%s {\n", $widget_style );

	$print .= sprintf( "\tbackground-color: %s;\n", themeblvd_get_option( 'widget_bg_color' ) );
	$print .= sprintf( "\tbackground-color: %s;\n", themeblvd_get_rgb( themeblvd_get_option( 'widget_bg_color' ), themeblvd_get_option( 'widget_bg_color_opacity' ) ) );

	if ( 'standard' === $widget_style && themeblvd_get_option( 'widget_apply_border' ) ) {

		$print .= sprintf( "\tborder: %s solid %s;\n", themeblvd_get_option( 'widget_border_width' ), themeblvd_get_option( 'widget_border_color' ) );

	} elseif ( 'panel' === $widget_style && 'custom' === themeblvd_get_option( 'widget_panel_style' ) ) {

		$print .= sprintf( "\tborder-color: %s;\n", themeblvd_get_option( 'widget_panel_border_color' ) );

	}

	$print .= "}\n";

	if ( 'panel' === $widget_style && 'custom' === themeblvd_get_option( 'widget_panel_style' ) ) {

		$color = themeblvd_get_option( 'widget_panel_title_bg_color' );

		$print .= sprintf( ".fixed-sidebar .widget.%s .panel-heading {\n", $widget_style );

		if ( $color['start'] == $color['end'] ) {

			$print .= sprintf( "\tbackground-color: %s;\n", $color['end'] );

		} else {

			$print .= sprintf( "\tbackground-color: %s;\n", $color['end'] );
			$print .= sprintf( "\tbackground-image: -webkit-gradient(linear, left top, left bottom, from(%s), to(%s) );\n", $color['start'], $color['end'] );
			$print .= sprintf( "\tbackground-image: -webkit-linear-gradient(top, %s, %s);\n", $color['start'], $color['end'] );
			$print .= sprintf( "\tbackground-image: -moz-linear-gradient(top, %s, %s);\n", $color['start'], $color['end'] );
			$print .= sprintf( "\tbackground-image: -o-linear-gradient(top, %s, %s);\n", $color['start'], $color['end'] );
			$print .= sprintf( "\tbackground-image: -ms-linear-gradient(top, %s, %s);\n", $color['start'], $color['end'] );
			$print .= sprintf( "\tbackground-image: linear-gradient(top, %s, %s);\n", $color['start'], $color['end'] );
			$print .= sprintf( "\tfilter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='%s', EndColorStr='%s' );\n", $color['start'], $color['end'] );

		}

		$print .= sprintf( "\tborder-color: %s;\n", themeblvd_get_option( 'widget_panel_border_color' ) );

		$print .= "}\n";
	}

	$print .= ".fixed-sidebar .widget-title {\n";
	$print .= sprintf( "\tcolor: %s;\n", themeblvd_get_option( 'widget_title_color' ) );
	$print .= sprintf( "\tfont-size: %s;\n", themeblvd_get_option( 'widget_title_size' ) );

	if ( 'panel' === $widget_style ) {

		$print .= "\tmargin-bottom: 0;\n";

	}

	if ( themeblvd_get_option( 'widget_title_shadow' ) ) {

		$print .= "\ttext-shadow: 1px 1px 1px rgba(0,0,0,.8);\n";
	} else {

		$print .= "\ttext-shadow: none;\n";

	}

	$print .= "}\n";

	if ( themeblvd_get_option( 'layout_style' ) == 'boxed' ) {

		// Boxed Layout

		$print .= "@media (min-width: 481px) {\n";
		$print .= "\t.tb-boxed #container {\n";
		$print .= sprintf( "\t\tbox-shadow: 0 0 %s %s;\n", themeblvd_get_option( 'layout_shadow_size' ), themeblvd_get_rgb( '#000000', themeblvd_get_option( 'layout_shadow_opacity' ) ) );
		$print .= sprintf( "\tborder-right: %s solid %s;\n", themeblvd_get_option( 'layout_border_width' ), themeblvd_get_option( 'layout_border_color' ) );
		$print .= sprintf( "\tborder-left: %s solid %s;\n", themeblvd_get_option( 'layout_border_width' ), themeblvd_get_option( 'layout_border_color' ) );
		$print .= "\t}\n";
		$print .= "}\n";

		$border = intval( themeblvd_get_option( 'layout_border_width' ) );

		if ( $border > 0 ) {

			$print .= ".tb-boxed .tb-sticky-header {\n";

			$width = 1170 - ( 2 * $border );

			$print .= sprintf( "\tmargin-left: -%spx;\n", $width / 2 );
			$print .= sprintf( "\tmax-width: %spx;\n", $width );

			$print .= "}\n";

			$print .= "@media (max-width: 1199px) {\n";

			$print .= "\t.tb-boxed .tb-sticky-header {\n";

			$width = 960 - ( 2 * $border );

			$print .= sprintf( "\t\tmargin-left: -%spx;\n", $width / 2 );
			$print .= sprintf( "\t\tmax-width: %spx;\n", $width );

			$print .= "\t}\n";
			$print .= "}\n";

		}
	} else {

		// Stretch Layout

		// Content border
		if ( themeblvd_get_option( 'apply_content_border' ) ) {

			$print .= ".bg-content,\n";

			$print .= ".blog-wrap > article,\n";
			$print .= "article.single,\n";
			$print .= "article.page,\n";

			$print .= "#comments,\n";
			$print .= ".tb-related-posts,\n";

			if ( themeblvd_installed( 'woocommerce' ) && themeblvd_supports( 'plugins', 'woocommerce' ) ) {
				$print .= ".products.upsells,\n";
				$print .= ".products.related,\n";
			}

			if ( themeblvd_installed( 'bbpress' ) && themeblvd_supports( 'plugins', 'bbpress' ) ) {
				$print .= ".tb-naked-page .bbp-topic-form,\n";
				$print .= ".tb-naked-page .bbp-reply-form,\n";
			}

			$print .= ".tb-info-box,\n";
			$print .= ".search-page,\n";
			$print .= ".post_list.archive-loop .post-wrap,\n";
			$print .= ".post_grid.archive-loop .post-wrap,\n";
			$print .= ".post_showcase.archive-loop .post-wrap,\n";

			$print .= ".list-template-wrap .list-wrap,\n";
			$print .= ".grid-template-wrap .grid-wrap,\n";
			$print .= ".showcase-template-wrap .showcase-wrap,\n";

			$print .= ".element-section > .element.bg-content,\n";
			$print .= ".element-columns .element.bg-content {\n";

			$print .= sprintf( "\tborder: %s solid %s;\n", themeblvd_get_option( 'content_border_width' ), themeblvd_get_option( 'content_border_color' ) );

			$print .= "}\n";

			if ( themeblvd_installed( 'woocommerce' ) && themeblvd_supports( 'plugins', 'woocommerce' ) ) {

				$print .= ".woocommerce-tabs .panel,\n";
				$print .= ".woocommerce-tabs .tabs > li.active {\n";
				$print .= sprintf( "\tborder-color: %s;\n", themeblvd_get_option( 'content_border_color' ) );
				$print .= "}\n";

			}
		}
	}

	// Header background
	if ( ! themeblvd_config( 'suck_up' ) ) {

		$options = array();

		$options['bg_type'] = $header_bg_type;
		$options['bg_color'] = $header_bg_color;
		$options['bg_color_opacity'] = themeblvd_get_option( 'header_bg_color_opacity' );
		$options['bg_texture'] = themeblvd_get_option( 'header_bg_texture' );
		$options['bg_image'] = themeblvd_get_option( 'header_bg_image' );

		$options['apply_border_top'] = themeblvd_get_option( 'header_apply_border_top' );
		$options['border_top_color'] = themeblvd_get_option( 'header_border_top_color' );
		$options['border_top_width'] = themeblvd_get_option( 'header_border_top_width' );

		$options['apply_border_bottom'] = themeblvd_get_option( 'header_apply_border_bottom' );
		$options['border_bottom_color'] = themeblvd_get_option( 'header_border_bottom_color' );
		$options['border_bottom_width'] = themeblvd_get_option( 'header_border_bottom_width' );

		$styles = themeblvd_get_display_inline_style( $options, 'external' );

		if ( ! empty( $styles['general'] ) ) {

			$print .= ".site-header {\n";

			foreach ( $styles['general'] as $prop => $value ) {

				$prop = str_replace( '-2', '', $prop );

				$print .= sprintf( "\t%s: %s;\n", $prop, $value );

			}

			$print .= "}\n";

		}

		// Header top bar
		if ( themeblvd_ent_has_header_info() ) { // Top bar only shows if there's header text.

			$options = array();

			$options['bg_color'] = themeblvd_get_option( 'top_bg_color' );
			$options['bg_type'] = $options['bg_color'] ? 'color' : 'none';
			$options['apply_border_bottom'] = themeblvd_get_option( 'top_apply_border_bottom' );
			$options['border_bottom_color'] = themeblvd_get_option( 'top_border_bottom_color' );
			$options['border_bottom_width'] = themeblvd_get_option( 'top_border_bottom_width' );
			$options['bg_color_opacity'] = themeblvd_get_option( 'top_bg_color_opacity' );

			$styles = themeblvd_get_display_inline_style( $options, 'external' );

			if ( ! empty( $styles['general'] ) ) {

				$print .= ".header-top {\n";

				foreach ( $styles['general'] as $prop => $value ) {

					$prop = str_replace( '-2', '', $prop );

					$print .= sprintf( "\t%s: %s;\n", $prop, $value );

				}

				$print .= "}\n";

			}
		}
	}

	// Header Height and Main Menu
	$height = intval( themeblvd_get_option( 'header_height' ) );

	$print .= ".header-content {\n";
	$print .= sprintf( "\theight: %spx;\n", $height );
	$print .= "}\n";
	$print .= ".header-content .site-logo img {\n";
	$print .= sprintf( "\theight: %spx;\n", $height - 20 );
	$print .= "}\n";

	$top = round( ($height - 20) / 2 ); // 20px line-height for font

	$bottom = ($height - 20) - $top; // 20px line-height for font

	$print .= ".header-nav .tb-primary-menu > li > .menu-btn {\n";
	$print .= sprintf( "\tpadding-top: %spx;\n", $top );
	$print .= sprintf( "\tpadding-bottom: %spx;\n", $bottom );
	$print .= "}\n";

	$print .= ".site-header .tb-primary-menu > li.highlight {\n";
	$print .= sprintf( "\tpadding-top: %spx;\n", max( $top - 10, 0 ) );
	$print .= sprintf( "\tpadding-bottom: %spx;\n", max( $bottom - 10, 0 ) );
	$print .= "}\n";

	$header_text = themeblvd_get_option( 'header_text_color' );
	$menu_font = themeblvd_get_option( 'font_menu' );

	$print .= ".header-nav .tb-primary-menu > li > .menu-btn,\n";
	$print .= ".tb-sticky-header .tb-primary-menu > li > .menu-btn,\n";
	$print .= ".tb-side-panel .menu > li > .menu-btn,\n";
	$print .= ".tb-mobile-panel .tb-mobile-menu > li > .menu-btn {\n";
	$print .= sprintf( "\tfont-family: %s;\n", themeblvd_get_font_face( $menu_font ) );
	$print .= sprintf( "\tfont-style: %s;\n", themeblvd_get_font_style( $menu_font ) );
	$print .= sprintf( "\tfont-weight: %s;\n", themeblvd_get_font_weight( $menu_font ) );
	$print .= sprintf( "\tletter-spacing: %s;\n", themeblvd_get_option( 'font_menu_sp' ) );
	$print .= sprintf( "\ttext-transform: %s;\n", themeblvd_get_text_transform( $menu_font ) );
	$print .= "}\n";

	$print .= ".header-nav .tb-primary-menu > li > .menu-btn {\n";
	$print .= sprintf( "\tfont-size: %s;\n", themeblvd_get_font_size( $menu_font ) );

	if ( themeblvd_get_option( 'menu_text_shadow' ) ) {

		$print .= "\ttext-shadow: 1px 1px 1px rgba(0,0,0,.8);\n";

	}

	$print .= "}\n";

	// Main Menu highlight
	$highlight = themeblvd_get_option( 'menu_highlight' );

	if ( themeblvd_config( 'suck_up' ) && themeblvd_get_option( 'menu_highlight_trans' ) ) {

		$print .= ".site-header.transparent .header-nav .tb-primary-menu > li > .menu-btn:before {\n";

	} else {

		$print .= ".header-nav .tb-primary-menu > li > .menu-btn:before {\n";

	}

	$print .= sprintf( "\tbackground-color: %s;\n", $highlight );
	$print .= "}\n";

	$print .= ".tb-primary-menu > li > ul.non-mega-sub-menu,\n";
	$print .= ".tb-primary-menu .sf-mega {\n";
	$print .= sprintf( "\tborder-color: %s;\n", $highlight );
	$print .= "}\n";

	// Main Menu sub menus
	$print .= ".tb-primary-menu ul.non-mega-sub-menu,\n";
	$print .= ".tb-primary-menu .sf-mega {\n";
	$print .= sprintf( "\tbackground-color: %s;\n", themeblvd_get_option( 'menu_sub_bg_color' ) );
	$print .= "}\n";

	// Sticky Header
	$print .= themeblvd_get_shared_style( 'sticky-header' );

	// Mobile Header
	$print .= themeblvd_get_shared_style( 'mobile-header' );

	// Header sticky menu
	if ( in_array( $header_bg_type, array( 'color', 'texture', 'image' ) ) ) {

		$print .= ".tb-sticky-header {\n";
		$print .= sprintf( "\tbackground-color: %s;\n", $header_bg_color );
		$print .= sprintf( "\tbackground-color: %s;\n", themeblvd_get_rgb( $header_bg_color, '0.9' ) );
		$print .= "}\n";

	}

	$font = themeblvd_get_option( 'font_menu' );

	if ( $font ) {

		$print .= ".tb-sticky-header .tb-primary-menu > li > .menu-btn {\n";
		$print .= sprintf( "\tfont-family: %s;\n", themeblvd_get_font_face( $font ) );
		$print .= sprintf( "\tletter-spacing: %s;\n", themeblvd_get_option( 'font_menu_sp' ) );
		$print .= sprintf( "\ttext-transform: %s;\n", themeblvd_get_text_transform( $font ) );
		$print .= "}\n";

	}

	// Side Panel and Mobile Menu
	$print .= themeblvd_get_shared_style( 'side-panel' );

	// Footer
	$print .= themeblvd_get_shared_style( 'footer' );

	/*------------------------------------------------------------*/
	/* Custom CSS
	/*------------------------------------------------------------*/

	$custom = themeblvd_get_option( 'custom_styles' );

	if ( $custom ) {

		$print .= "\n/* =Custom CSS\n";

		$print .= "-----------------------------------------------*/\n\n";

		$print .= $custom;

	}

	$print = wp_kses( $print, array() );

	$print = htmlspecialchars_decode( $print );

	/**
	 * Filters final printed inline CSS output
	 * for Entrepreneur theme base.
	 *
	 * @since @@name-package 2.0.0
	 *
	 * @param string $print CSS output.
	 */
	$print = apply_filters( 'jumpstart_ent_css_output', $print );

	if ( $print ) {

		wp_add_inline_style( 'jumpstart-base', $print );

	}

}
add_action( 'wp_enqueue_scripts', 'jumpstart_ent_css', 25 );

/**
 * Only trigger default header info top bar,
 * if there's header text saved.
 *
 * @since @@name-package 2.0.0
 */
function themeblvd_ent_has_header_info() {

	if ( themeblvd_get_option( 'header_text' ) ) {

		$return = true;

	} else {

		$return = false;

	}

	/**
	 * Filters whether top bar of header should
	 * show in Entrepreneur theme base.
	 *
	 * @since @@name-package 2.0.0
	 *
	 * @param bool $return Whether to show top bar or not.
	 */
	return apply_filters( 'themeblvd_ent_has_header_info', $return );

}
add_filter( 'themeblvd_has_header_info', 'themeblvd_ent_has_header_info' );

/*
 * Move header menu to the right of the logo
 */
remove_action( 'themeblvd_header_menu', 'themeblvd_header_menu_default' );
add_action( 'themeblvd_header_addon', 'themeblvd_header_menu_default' );

/**
 * Header menu addons.
 *
 * @since @@name-package 2.0.0
 *
 * @param  string $items Current output for WordPress menu.
 * @param  array  $args  Arguments for WordPress menu.
 * @return string $items Modifed output for WordPress menu.
 */
function jumpstart_ent_menu_addon( $items, $args ) {

	if ( themeblvd_ent_has_header_info() ) {

		return $items; // Header top bar is showing.

	}

	if ( 'primary' !== $args->theme_location ) {

		return $items; // This isn't the main menu.

	}

	$icons = themeblvd_get_option( 'social_media' );

	if ( $icons && themeblvd_get_option( 'social_header' ) ) {

		$contact = themeblvd_get_contact_bar( $icons, array(
			'tooltip'   => false,
			'container' => false,
			'style'     => 'flat',
		) );

		$contact = str_replace(
			'contact-bar-item',
			'contact-bar-item menu-item',
			$contact
		);

		$contact = str_replace(
			'class="tb-icon',
			'class="tb-icon menu-btn',
			$contact
		);

		$items .= $contact;

	}

	if ( themeblvd_do_cart() ) {

		$items .= sprintf(
			'<li class="menu-item level-1 menu-cart">%s</li>',
			themeblvd_get_cart_popup_trigger()
		);

	}

	if ( themeblvd_do_lang_selector() ) {

		$items .= sprintf(
			'<li class="menu-item level-1 menu-lang">%s</li>',
			themeblvd_get_lang_popup_trigger( array(
				'class' => 'menu-btn',
			) )
		);

	}

	if ( 'show' === themeblvd_get_option( 'searchform' ) ) {

		$items .= sprintf(
			'<li class="menu-item level-1 menu-search">%s</li>',
			themeblvd_get_floating_search_trigger( array(
				'class' => 'menu-btn',
			) )
		);

	}

	if ( themeblvd_do_side_panel() ) {

		$items .= sprintf(
			'<li class="menu-item level-1 menu-side-panel no-sticky">%s</li>',
			themeblvd_get_side_trigger( array(
				'class' => 'menu-btn',
			) )
		);

	}

	return $items;

}
add_filter( 'wp_nav_menu_items', 'jumpstart_ent_menu_addon', 10, 2 );

/**
 * Add CSS classes to <body>.
 *
 * @since @@name-package 2.0.0
 *
 * @param  array $class WordPress classes to add to body.
 * @return array $class Modified WordPress classes to add to body.
 */
function jumpstart_ent_body_class( $class ) {

	if ( 'boxed' === themeblvd_get_option( 'layout_style' ) ) {

		$class[] = 'tb-boxed';

		$class[] = 'js-boxed'; // Backwards compatibility.

	}

	return $class;

}
add_filter( 'body_class', 'jumpstart_ent_body_class' );

/**
 * Add CSS classes to sticky header panel for
 * color brightness.
 *
 * @since @@name-package 2.1.0
 *
 * @param  array $class Classes to add to sticky header.
 * @return array $class Modified classes to add to sticky header.
 */
function jumpstart_ent_sticky_class( $class ) {

	if ( 'light' === themeblvd_get_option( 'header_text_color' ) ) {

		$class[] = 'dark';

	} else {

		$class[] = 'light';

	}

	$class[] = 'drop-' . themeblvd_get_option( 'menu_sub_bg_color_brightness' );

	return $class;

}
add_filter( 'themeblvd_sticky_class', 'jumpstart_ent_sticky_class' );

/**
 * Add CSS classes to header.
 *
 * @since @@name-package 2.0.0
 *
 * @param  array $class Classes to add to header.
 * @return array $class Modified classes to add to header.
 */
function jumpstart_ent_header_class( $class ) {

	$options = array(
		'bg_type'  => themeblvd_get_option( 'header_bg_type' ),
		'bg_image' => themeblvd_get_option( 'header_bg_image' ),
	);

	if ( themeblvd_config( 'suck_up' ) ) {

		unset( $options['bg_type'] );

	}

	$class = array_merge( $class, themeblvd_get_display_class( $options ) );

	if ( themeblvd_get_option( 'top_mini', null, '1' ) ) {

		$class[] = 'header-top-mini';

	}

	if ( themeblvd_config( 'suck_up' ) || 'light' === themeblvd_get_option( 'header_text_color' ) ) {

		$class[] = 'dark';

	} else {

		$class[] = 'light';

	}

	$class[] = 'drop-' . themeblvd_get_option( 'menu_sub_bg_color_brightness' );

	return $class;

}
add_filter( 'themeblvd_header_class', 'jumpstart_ent_header_class' );

/*
 * Add CSS classes to mobile header.
 */
add_filter( 'themeblvd_mobile_header_class', 'jumpstart_mobile_header_class' );

/*
 * Modify mobile header breakpoint with user option.
 */
add_filter( 'themeblvd_mobile_header_breakpoint', 'jumpstart_mobile_header_breakpoint' );

/**
 * Add CSS classes to header top bar.
 *
 * @since @@name-package 2.1.0
 *
 * @param  array $class Classes to add to top bar.
 * @return array $class Modified classes to add to top bar.
 */
function jumpstart_ent_header_top_class( $class ) {

	if ( themeblvd_config( 'suck_up' ) || 'light' === themeblvd_get_option( 'top_text_color' ) ) {

		$class[] = 'dark';

	} else {

		$class[] = 'light';

	}

	return $class;

}
add_filter( 'themeblvd_header_top_class', 'jumpstart_ent_header_top_class' );

/*
 * Add CSS classes to side panel and mobile menu.
 */
add_filter( 'themeblvd_side_panel_class', 'jumpstart_side_panel_class' );
add_filter( 'themeblvd_mobile_panel_class', 'jumpstart_side_panel_class' );

/*
 * Adjust the style of the side panel contact bar.
 */
add_filter( 'themeblvd_panel_contact_bar_args', 'jumpstart_panel_contact_bar_args' );
add_filter( 'themeblvd_mobile_panel_contact_bar_args', 'jumpstart_panel_contact_bar_args' );

/*
 * Add CSS classes to footer.
 */
add_filter( 'themeblvd_footer_class', 'jumpstart_footer_class' );

/*
 * Add CSS classes to copyright.
 */
add_filter( 'themeblvd_copyright_class', 'jumpstart_copyright_class' );

/*
 * Adjust the style of the copyright contact bar.
 */
add_filter( 'themeblvd_copyright_contact_bar_args', 'jumpstart_copyright_contact_bar_args' );

/**
 * Filter args that get filtered in when all
 * sidebars are registered.
 *
 * @since @@name-package 2.0.0
 *
 * @param  array  $args     Arguments passed to register_sidebar().
 * @param  array  $sidebar  Sidebar information from framework.
 * @param  string $location ID of widget area being registered.
 * @return array            Modified arguments passed to register_sidebar().
 */
function themeblvd_ent_sidebar_args( $args, $sidebar, $location ) {

	if ( in_array( $location, array( 'sidebar_left', 'sidebar_right' ) ) ) {

		$text = 'text-dark';

		if ( themeblvd_get_option( 'widget_bg_brightness' ) == 'dark' ) {

			$text = 'text-light';

		}

		if ( themeblvd_get_option( 'widget_style' ) == 'panel' ) {

			$class = sprintf(
				'panel panel-%s %s',
				themeblvd_get_option( 'widget_panel_style' ),
				$text
			);

			$args['before_widget'] = '<aside id="%1$s" class="widget ' . $class . ' %2$s">';

			$args['after_widget'] = '</aside>';

			$args['before_title'] = '<div class="panel-heading"><h3 class="widget-title panel-title">';

			$args['after_title'] = '</h3></div>';

		} else {

			$args['before_widget'] = str_replace(
				'class="widget ',
				'class="widget standard ' . $text . ' ',
				$args['before_widget']
			);

		}
	}

	return $args;

}
add_filter( 'themeblvd_default_sidebar_args', 'themeblvd_ent_sidebar_args', 10, 3 );
add_filter( 'themeblvd_custom_sidebar_args', 'themeblvd_ent_sidebar_args', 10, 3 );
