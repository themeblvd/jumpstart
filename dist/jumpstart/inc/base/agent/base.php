<?php
/**
 * Theme Base: Agent, Functions
 *
 * @author    Jason Bobich <info@themeblvd.com>
 * @copyright 2009-2017 Theme Blvd
 * @package   Jump_Start
 * @since     Jump_Start 2.1.0
 */

/**
 * Include modifications to Theme Options page.
 */
include_once( themeblvd_get_base_path( 'agent' ) . '/options.php' );

/**
 * Include preset styles for Theme Options.
 */
include_once( themeblvd_get_base_path( 'agent' ) . '/presets.php' );

/**
 * Include Google fonts, if needed.
 *
 * @since Jump_Start 2.1.0
 */
function jumpstart_ag_include_fonts() {

	themeblvd_include_fonts(
		themeblvd_get_option( 'font_body' ),
		themeblvd_get_option( 'font_header' ),
		themeblvd_get_option( 'font_header_sm' ),
		themeblvd_get_option( 'font_quote' ),
		themeblvd_get_option( 'font_meta' ),
		themeblvd_get_option( 'font_epic' ),
		themeblvd_get_option( 'font_menu' )
	);

}
add_action( 'wp_head', 'jumpstart_ag_include_fonts', 5 );

/**
 * Add custom styles for theme options.
 *
 * This generate output string to pass to
 * wp_add_inline_style().
 *
 * @since Jump_Start 2.1.0
 *
 * @return string $print CSS code to print.
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
	$print .= sprintf( "\tbackground-color: %s;\n", themeblvd_get_option( 'btn_color' ) );
	$print .= "}\n";

	// Highlight Color
	$highlight = themeblvd_get_option( 'highlight' );

	$print .= "\n/* Highlight */\n";

	$print .= ".highlight,\n";

	if ( themeblvd_supports( 'plugins', 'wpml' ) && themeblvd_installed( 'wpml' ) ) {

		$print .= ".tb-lang-popup a:hover,\n";
		$print .= ".tb-lang-popup a:focus,\n";

	}

	if ( themeblvd_supports( 'plugins', 'woocommerce' ) && themeblvd_installed( 'woocommerce' ) ) {

		$print .= ".woocommerce-tabs .tabs > li > a:hover,\n";
		$print .= ".woocommerce-tabs .tabs > li > a:focus,\n";
		$print .= ".woocommerce-tabs .tabs > li.active > a,\n";

	}

	$print .= ".tb-thumb-link .thumb-link-icon,\n";
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

		$print .= "h1,\n";
		$print .= "h2,\n";
		$print .= "h3,\n";
		$print .= "h4,\n";
		$print .= "label,\n";
		$print .= ".sf-menu .mega-section-header,\n";
		$print .= "#comments .comment-author {\n";
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
		$print .= ".tb-mini-post-list .entry-meta,\n";
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

	// Menu Font
	$font = themeblvd_get_option( 'font_menu' );

	if ( $font ) {

		$print .= ".header-nav .tb-primary-menu > li > .menu-btn,\n";
		$print .= ".tb-sticky-header .tb-primary-menu > li > .menu-btn,\n";
		$print .= ".tb-side-panel .menu > li > .menu-btn,\n";
		$print .= ".tb-mobile-panel .tb-mobile-menu > li > .menu-btn {\n";
		$print .= sprintf( "\tfont-family: %s;\n", themeblvd_get_font_face( $font ) );
		$print .= sprintf( "\tfont-style: %s;\n", themeblvd_get_font_style( $font ) );
		$print .= sprintf( "\tfont-weight: %s;\n", themeblvd_get_font_weight( $font ) );
		$print .= sprintf( "\tletter-spacing: %s;\n", themeblvd_get_option( 'font_menu_sp' ) );
		$print .= sprintf( "\ttext-transform: %s;\n", themeblvd_get_text_transform( $font ) );
		$print .= "}\n";

		$print .= ".header-nav .tb-primary-menu > li > .menu-btn {\n";
		$print .= sprintf( "\tfont-size: %s;\n", themeblvd_get_font_size( $font ) );
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

	/*------------------------------------------------------------*/
	/* Header
	/*------------------------------------------------------------*/

	$print .= "\n/* Header */\n";

	$bg = themeblvd_get_option( 'header_bg_color' );

	// Background color
	if ( themeblvd_config( 'suck_up' ) ) {

		$print .= ".site-header.transparent {\n";
		$print .= "\tbackground-color: transparent;\n";

		$opacity = themeblvd_get_option( 'header_trans_bg_color_opacity' );

		if ( $opacity ) {

			$print .= sprintf( "\tbackground-color: %s;\n", themeblvd_get_rgb( themeblvd_get_option( 'header_trans_bg_color' ), $opacity ) );

		}

		$print .= "}\n";

	} else {

		$print .= ".site-header {\n";
		$print .= sprintf( "\t\tbackground-color: %s;\n", $bg );
		$print .= "}\n";

	}

	$print .= ".tb-sticky-header {\n";
	$print .= sprintf( "\tbackground-color: %s;\n", $bg );
	$print .= sprintf( "\tbackground-color: %s;\n", themeblvd_get_rgb( $bg, '0.9' ) );
	$print .= "}\n";

	// Sticky Header
	$print .= themeblvd_get_shared_style( 'sticky-header' );

	// Mobile Header
	$print .= themeblvd_get_shared_style( 'mobile-header' );

	// Get logo attributes.
	if ( themeblvd_config( 'suck_up' ) ) {

		$logo = themeblvd_get_option( 'trans_logo' );

		if ( ! $logo || ( ! empty( $logo['type'] ) && 'default' === $logo['type'] ) ) {

			$logo = themeblvd_get_option( 'logo' );

		}
	} else {

		$logo = themeblvd_get_option( 'logo' );

	}

	if ( 'image' === $logo['type'] ) {

		$height = 50 + intval( $logo['image_height'] );

	} else {

		$height = 76;

	}

	if ( themeblvd_get_option( 'header_stretch' ) ) {
		$print .= ".site-header.stretch .header-toolbar:before,\n";
	}

	$print .= ".header-content > .wrap,\n";
	$print .= ".has-sticky #top {\n";
	$print .= sprintf( "\t\theight: %spx;\n", $height );
	$print .= "}\n";

	if ( themeblvd_get_option( 'header_stretch' ) ) {

		$print .= ".site-header.stretch .header-toolbar:before {\n";
		$print .= sprintf( "\ttop: -%spx;\n", round( ($height - 58) / 2 ) );
		$print .= "}\n";

	}

	// Main Menu Dropdowns
	$print .= ".tb-primary-menu ul.non-mega-sub-menu,\n";
	$print .= ".tb-primary-menu .sf-mega {\n";
	$print .= sprintf( "\tbackground-color: %s;\n", themeblvd_get_option( 'menu_drop_bg_color' ) );
	$print .= "}\n";

	/*------------------------------------------------------------*/
	/* Footer
	/*------------------------------------------------------------*/

	$print .= "\n/* Footer */\n";

	$print .= themeblvd_get_shared_style( 'footer' );

	/*------------------------------------------------------------*/
	/* Side Panels
	/*------------------------------------------------------------*/

	$print .= "\n/* Side Panels */\n";

	$print .= themeblvd_get_shared_style( 'side-panel' );

	/*------------------------------------------------------------*/
	/* Widgets
	/*------------------------------------------------------------*/

	if ( themeblvd_get_option( 'widget_bg' ) ) {

		$print .= "\n/* Widgets */\n";

		$print .= ".tb-widget-bg .fixed-sidebar .widget {\n";
		$print .= sprintf( "\tbackground-color: %s;\n", themeblvd_get_option( 'widget_bg_color' ) );
		$print .= "}\n";

	}

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
	 * for Agent theme base.
	 *
	 * @since Jump_Start 2.1.0
	 *
	 * @param string $print CSS output.
	 */
	$print = apply_filters( 'jumpstart_ag_css_output', $print );

	if ( $print ) {

		wp_add_inline_style( 'jumpstart-base', $print );

	}

}
add_action( 'wp_enqueue_scripts', 'jumpstart_ag_css', 25 );

/**
 * Add CSS classes to <body>.
 *
 * @since Jump_Start 2.1.0
 *
 * @param  array $class WordPress classes to add to body.
 * @return array $class Modified WordPress classes to add to body.
 */
function jumpstart_ag_body_class( $class ) {

	if ( themeblvd_get_option( 'widget_bg' ) ) {

		$class[] = 'tb-widget-bg';

		$class[] = themeblvd_get_option( 'widget_bg_color_brightness' ) . '-widgets';

	}

	return $class;

}
add_filter( 'body_class', 'jumpstart_ag_body_class' );

/**
 * Add CSS classes to header.
 *
 * @since Jump_Start 2.1.0
 *
 * @param  array $class CSS classes being added to theme header.
 * @return array $class Modified CSS classes being added to theme header.
 */
function jumpstart_ag_header_class( $class ) {

	// Background brightness.
	if ( themeblvd_config( 'suck_up' ) ) {

		$class[] = 'trans-' . themeblvd_get_option( 'header_trans_bg_color_brightness' );

		if ( themeblvd_get_option( 'header_trans_hide_border' ) ) {

			$class[] = 'no-border';

		} else {

			$class[] = 'has-border';

		}
	} else {

		$class[] = themeblvd_get_option( 'header_bg_color_brightness' );

	}

	// Dropdown brightness.
	$class[] = 'drop-' . themeblvd_get_option( 'menu_drop_bg_color_brightness' );

	// Location of menu.
	$menu_args = themeblvd_get_wp_nav_menu_args( 'primary' );

	if ( has_nav_menu( $menu_args['theme_location'] ) ) {

		$class[] = 'menu-' . themeblvd_get_option( 'menu_placement' );

	}

	// Whether to stretch the header full width.
	if ( themeblvd_get_option( 'header_stretch' ) ) {

		$class[] = 'stretch';

	}

	return $class;

}
add_filter( 'themeblvd_header_class', 'jumpstart_ag_header_class' );

/*
 * Add CSS classes to mobile header.
 */
add_filter( 'themeblvd_mobile_header_class', 'jumpstart_mobile_header_class' );

/*
 * Modify mobile header breakpoint with user option.
 */
add_filter( 'themeblvd_mobile_header_breakpoint', 'jumpstart_mobile_header_breakpoint' );

/*
 * Remove header top.
 */
remove_action( 'themeblvd_header_top', 'themeblvd_header_top_default' );

/*
 * Remove default menu callback.
 */
remove_action( 'themeblvd_header_menu', 'themeblvd_header_menu_default' );

/**
 * Custom menu callback with header toolbar
 * added.
 *
 * @since Jump_Start 2.1.0
 */
function jumpstart_ag_header_menu() {

	/**
	 * Fires before the primary navigation.
	 *
	 * @since Theme_Blvd 2.0.0
	 */
	do_action( 'themeblvd_header_menu_before' );

	$icons = themeblvd_get_option( 'social_media' );

	$do_icons = themeblvd_get_option( 'social_header' );

	?>
	<nav id="access" class="header-nav">

		<div class="wrap clearfix">

			<?php if ( ( $icons && $do_icons ) || 'show' === themeblvd_get_option( 'searchform' ) || themeblvd_do_cart() || themeblvd_do_lang_selector() || themeblvd_do_side_panel() ) : ?>

				<ul class="header-toolbar list-unstyled">

					<?php if ( $icons && $do_icons ) : ?>

						<?php
						echo themeblvd_get_contact_bar( $icons, array(
							'tooltip'   => false,
							'container' => false,
							'style'     => 'flat',
						) );
						?>

					<?php endif; ?>

					<?php if ( themeblvd_do_cart() ) : ?>

						<li class="top-cart"><?php themeblvd_cart_popup_trigger(); ?></li>

					<?php endif; ?>

					<?php if ( themeblvd_do_lang_selector() ) : ?>

						<li class="top-wpml"><?php themeblvd_lang_popup_trigger(); ?></li>

					<?php endif; ?>

					<?php if ( 'show' === themeblvd_get_option( 'searchform' ) ) : ?>

						<li class="top-search"><?php themeblvd_floating_search_trigger(); ?></li>

					<?php endif; ?>

					<?php if ( themeblvd_do_side_panel() ) : ?>

						<li class="top-side-panel"><?php themeblvd_side_trigger(); ?></li>

					<?php endif; ?>

				</ul>

			<?php endif; ?>

			<?php wp_nav_menu( themeblvd_get_wp_nav_menu_args( 'primary' ) ); ?>

			<?php
			/**
			 * Fires just after the main menu, within
			 * the main menu's <nav> wrapper.
			 *
			 * @since Theme_Blvd 2.0.0
			 */
			do_action( 'themeblvd_header_menu_addon' );
			?>

		</div><!-- .wrap (end) -->

	</nav><!-- #access (end) -->

	<?php
	/**
	 * Fires after the primary navigation.
	 *
	 * @since Theme_Blvd 2.0.0
	 */
	do_action( 'themeblvd_header_menu_after' );

}
add_action( 'themeblvd_header_addon', 'jumpstart_ag_header_menu' );

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

/**
 * Add CSS class to sticky header panel for
 * color brightness.
 *
 * @since Jump_Start 2.1.0
 *
 * @param  array $class CSS classes being added.
 * @return array $class Modified CSS classes being added.
 */
function jumpstart_ag_sticky_class( $class ) {

	$class[] = themeblvd_get_option( 'header_bg_color_brightness' );

	$class[] = 'drop-' . themeblvd_get_option( 'menu_drop_bg_color_brightness' );

	if ( themeblvd_get_option( 'header_stretch' ) ) {

		$class[] = 'stretch';

	}

	return $class;

}
add_filter( 'themeblvd_sticky_class', 'jumpstart_ag_sticky_class' );

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
 * Height of the header, not including the logo.
 * Used with "suck up" feature.
 *
 * @since Jump_Start 2.1.0
 *
 * @param int    $addend   Height of header excluding logo.
 * @param string $viewport Viewport range this applies to.
 */
function jumpstart_ag_top_height_addend( $addend, $viewport ) {

	return 50; // Default logo 25px + 50px = 75px

}
add_filter( 'themeblvd_top_height_addend', 'jumpstart_ag_top_height_addend', 10, 2 );

/**
 * More narrow sidebars.
 *
 * @since Jump_Start 2.1.0
 *
 * @param  array  $layouts All layouts and their configurations.
 * @param  string $stack   The stacking point for the main grid system.
 * @return array  $layouts Modified layouts and their configurations.
 */
function jumpstart_ag_sidebar_layouts( $layouts, $stack ) {

	// Make the sidebars more narrow. @TODO Issue #265. Remove this.

	$layouts['sidebar_right']['columns']['content'] = "col-{$stack}-9";

	$layouts['sidebar_right']['columns']['right'] = "col-{$stack}-3";

	$layouts['sidebar_left']['columns']['content'] = "col-{$stack}-9";

	$layouts['sidebar_left']['columns']['left'] = "col-{$stack}-3";

	return $layouts;

}
add_filter( 'themeblvd_sidebar_layouts', 'jumpstart_ag_sidebar_layouts', 9, 2 );

/**
 * Filter args that get filtered in when sidebars
 * are registered.
 *
 * @since Jump_Start 2.1.0
 */
function themeblvd_agent_sidebar_args( $args, $sidebar, $location ) {

	if ( in_array( $location, array( 'sidebar_left', 'sidebar_right' ) ) ) {

		if ( 'dark' === themeblvd_get_option( 'widget_bg_color_brightness' ) ) {

			$args['before_widget'] = str_replace(
				'class="widget ',
				'class="widget text-light ',
				$args['before_widget']
			);

		}
	}

	return $args;

}
add_filter( 'themeblvd_sidebar_args', 'themeblvd_ag_sidebar_args', 10, 3 );
