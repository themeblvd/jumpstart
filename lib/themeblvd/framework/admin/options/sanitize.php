<?php
/**
 * Option Sanitization
 *
 * This file contains the setup for all option
 * sanitization and the hooked sanitization
 * functions for all basic option types.
 *
 * For the rest of the hooked sanitization functions
 * you see in themeblvd_add_sanitization(), see:
 *
 * /framework/admin/sanitize-specialized.php
 * /framework/admin/sanitize-advanced.php
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.2.0
 */

/**
 * Add sanitization filters.
 *
 * This function gets hooked to `admin_init` when the
 * framework runs on the admin side.
 *
 * Furthermore, on the frontend of the site, if the
 * options have never been saved we can call this function
 * to allow default option values to be generated.
 *
 * This will almost never happen, so this is our way of
 * including them, but only if needed.
 *
 * This function is hooked to:
 * 1. `admin_init` - 10
 *
 * @since @@name-framework 2.2.0
 */
function themeblvd_add_sanitization() {

	add_filter( 'themeblvd_sanitize_hidden',           'themeblvd_sanitize_hidden',                10, 2 );
	add_filter( 'themeblvd_sanitize_text',             'themeblvd_sanitize_text',                  10, 1 );
	add_filter( 'themeblvd_sanitize_textarea',         'themeblvd_sanitize_textarea',              10, 1 );
	add_filter( 'themeblvd_sanitize_editor',           'themeblvd_sanitize_textarea',              10, 1 );
	add_filter( 'themeblvd_sanitize_select',           'themeblvd_sanitize_text',                  10, 2 );
	add_filter( 'themeblvd_sanitize_radio',            'themeblvd_sanitize_text',                  10, 2 );
	add_filter( 'themeblvd_sanitize_images',           'themeblvd_sanitize_text',                  10, 2 );
	add_filter( 'themeblvd_sanitize_checkbox',         'themeblvd_sanitize_checkbox',              10, 1 );
	add_filter( 'themeblvd_sanitize_multicheck',       'themeblvd_sanitize_multicheck',            10, 2 );
	add_filter( 'themeblvd_sanitize_color',            'themeblvd_sanitize_color',                 10, 1 );
	add_filter( 'themeblvd_sanitize_gradient',         'themeblvd_sanitize_gradient',              10, 1 );
	add_filter( 'themeblvd_sanitize_button',           'themeblvd_sanitize_button',                10, 1 );
	add_filter( 'themeblvd_sanitize_upload',           'themeblvd_sanitize_upload',                10, 1 );
	add_filter( 'themeblvd_sanitize_background',       'themeblvd_sanitize_background',            10, 1 );
	add_filter( 'themeblvd_background_repeat',         'themeblvd_sanitize_background_repeat',     10, 1 );
	add_filter( 'themeblvd_background_position',       'themeblvd_sanitize_background_position',   10, 1 );
	add_filter( 'themeblvd_background_attachment',     'themeblvd_sanitize_background_attachment', 10, 1 );
	add_filter( 'themeblvd_background_size',           'themeblvd_sanitize_background_size',       10, 1 );
	add_filter( 'themeblvd_sanitize_background_video', 'themeblvd_sanitize_background_video',      10, 1 );
	add_filter( 'themeblvd_sanitize_typography',       'themeblvd_sanitize_typography',            10, 1 );
	add_filter( 'themeblvd_font_face',                 'themeblvd_sanitize_font_face',             10, 1 );
	add_filter( 'themeblvd_font_style',                'themeblvd_sanitize_font_style',            10, 1 );
	add_filter( 'themeblvd_font_weight',               'themeblvd_sanitize_font_weight',           10, 1 );
	add_filter( 'themeblvd_sanitize_columns',          'themeblvd_sanitize_columns',               10, 1 );
	add_filter( 'themeblvd_sanitize_tabs',             'themeblvd_sanitize_tabs',                  10, 1 );
	add_filter( 'themeblvd_sanitize_testimonials',     'themeblvd_sanitize_testimonials',          10, 1 );
	add_filter( 'themeblvd_sanitize_text_blocks',      'themeblvd_sanitize_text_blocks',           10, 1 );
	add_filter( 'themeblvd_sanitize_toggles',          'themeblvd_sanitize_toggles',               10, 1 );
	add_filter( 'themeblvd_sanitize_content',          'themeblvd_sanitize_content',               10, 1 );
	add_filter( 'themeblvd_sanitize_logo',             'themeblvd_sanitize_logo',                  10, 1 );
	add_filter( 'themeblvd_sanitize_social_media',     'themeblvd_sanitize_social_media',          10, 1 );
	add_filter( 'themeblvd_sanitize_share',            'themeblvd_sanitize_share',                 10, 1 );
	add_filter( 'themeblvd_sanitize_slide',            'themeblvd_sanitize_slide',                 10, 1 );
	add_filter( 'themeblvd_sanitize_slider',           'themeblvd_sanitize_slider',                10, 1 );
	add_filter( 'themeblvd_sanitize_logos',            'themeblvd_sanitize_logos',                 10, 1 );
	add_filter( 'themeblvd_sanitize_price_cols',       'themeblvd_sanitize_price_cols',            10, 1 );
	add_filter( 'themeblvd_sanitize_conditionals',     'themeblvd_sanitize_conditionals',          10, 3 );
	add_filter( 'themeblvd_sanitize_editor',           'themeblvd_sanitize_editor',                10, 1 );
	add_filter( 'themeblvd_sanitize_editor_modal',     'themeblvd_sanitize_editor',                10, 1 );
	add_filter( 'themeblvd_sanitize_code',             'themeblvd_sanitize_editor',                10, 1 );
	add_filter( 'themeblvd_sanitize_locations',        'themeblvd_sanitize_locations',             10, 1 );
	add_filter( 'themeblvd_sanitize_geo',              'themeblvd_sanitize_geo',                   10, 1 );
	add_filter( 'themeblvd_sanitize_sectors',          'themeblvd_sanitize_sectors',               10, 1 );
	add_filter( 'themeblvd_sanitize_datasets',         'themeblvd_sanitize_datasets',              10, 1 );
	add_filter( 'themeblvd_sanitize_bars',             'themeblvd_sanitize_bars',                  10, 1 );
	add_filter( 'themeblvd_sanitize_buttons',          'themeblvd_sanitize_buttons',               10, 1 );

}

/**
 * Sanitize option type, `hidden`.
 *
 * Hidden option types can be used to pass a
 * specific piece data through to a saved array
 * of settings.
 *
 * In this case, we'll just run standard wp_kses(),
 * with all accepted HTML tags for publishing posts
 * which is done via themeblvd_kses().
 *
 * With the `hidden` option, if the option ID is
 * specified in a certain way, it will trigger
 * one of our preset hidden types.
 *
 * `framework_version` Current version of framework.
 * `theme_version`     Current version of theme.
 * `theme_base`        Current active theme base.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  string $input Original value.
 * @param  array  $option {
 *     Original option setup arguments.
 *
 *     @type string $id   Unique ID for option.
 *     @type string $name Title of option.
 *     @type string $type Type of option.
 *     @type string $desc Optional. Description.
 *     @type string $std  Optional. Default value.
 * }
 * @return string $output Sanitized value.
 */
function themeblvd_sanitize_hidden( $input, $option ) {

	if ( 'framework_version' === $option['id'] ) {

		$output = TB_FRAMEWORK_VERSION;

	} elseif ( 'theme_version' === $option['id'] ) {

		$theme = wp_get_theme( get_template() );

		$output = $theme->get( 'Version' );

	} elseif ( 'theme_base' === $option['id'] ) {

		$output = themeblvd_get_base();

	} else {

		$output = themeblvd_kses( $input );

		$output = htmlspecialchars_decode( $output );

		$output = str_replace( "\r\n", "\n", $output );

	}

	return $output;

}

/**
 * Sanitize option type, `text`.
 *
 * The `text` option type is generally just a
 * standard <input type="text" /> option.
 *
 * This sanitization function is also hooked to
 * the option types, `select`, `radio` and `images`.
 *
 * In some cases, you'll see this sanitization
 * function getting applied to smaller pieces
 * of larger sanitization functions.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  string $input  Original value.
 * @return string $output Sanitized value.
 */
function themeblvd_sanitize_text( $input ) {

	if ( current_user_can( 'unfiltered_html' ) ) {

		$output = $input;

	} else {

		$output = themeblvd_kses( $input );

		$output = htmlspecialchars_decode( $output );

	}

	$output = str_replace( "\r\n", "\n", $output );

	return $output;

}

/**
 * Sanitize option type, `textarea`.
 *
 * The `textarea` option tpe is generally just
 * a standard <textarea> option.
 *
 * In some cases, you'll see this sanitization
 * function getting applied to smaller pieces
 * of larger sanitization functions.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  string $input  Original value.
 * @return string $output Sanitized value.
 */
function themeblvd_sanitize_textarea( $input ) {

	if ( current_user_can( 'unfiltered_html' ) ) {

		$output = $input;

	} else {

		$output = themeblvd_kses( $input );

		$output = htmlspecialchars_decode( $output );

	}

	$output = str_replace( "\r\n", "\n", $output );

	return $output;

}

/**
 * Sanitize option type, `checkbox`.
 *
 * When a checkbox is passed through a standard
 * HTML form, it will be passed a string `1` if
 * it has been checked by the user, and if not,
 * the value will not exist.
 *
 * For our purposes, we want to make sure the value
 * is still saved to the database, even if the
 * user didn't check the option. So if unchecked,
 * we'll pass back a string `0`.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  string $input  Original value.
 * @return string $output Sanitized value.
 */
function themeblvd_sanitize_checkbox( $input ) {

	if ( $input && '0' !== $input ) {

		$output = '1';
	} else {

		$output = '0';

	}

	return $output;

}

/**
 * Sanitize option type, `multicheck`.
 *
 * Similarly to our standard `checkbox` option
 * type, in an returning an array of a checkbox
 * group, we want all values, checked and unchecked
 * to exist. So every value will be either a
 * `1` or `0` string.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  array $input  Original value.
 * @param  array $option {
 *     Original option setup arguments.
 *
 *     @type string $id      Unique ID for option.
 *     @type string $name    Title of option.
 *     @type string $type    Type of option.
 *     @type string $desc    Optional. Description.
 *     @type string $std     Optional. Default value.
 *     @type array  $options All available values to check.
 * }
 * @return array $output Sanitized value.
 */
function themeblvd_sanitize_multicheck( $input, $option ) {

	$output = array();

	if ( is_array( $input ) ) {

		foreach ( $option['options'] as $key => $value ) {

			$output[ $key ] = '0';

		}

		if ( $input ) {

			foreach ( $input as $key => $value ) {

				if ( array_key_exists( $key, $option['options'] ) && $value ) {

					$output[ $key ] = '1';

				}
			}
		}
	}

	return $output;

}

/**
 * Sanitize option type, `slide`.
 *
 * This will be a value sent from the jQuery UI
 * slide element. Not to be confused with anythig
 * related to image sliders.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  string $input  Original value.
 * @return string $output Sanitized value.
 */
function themeblvd_sanitize_slide( $input ) {

	return wp_kses( $input, array() );

}

/**
 * Validate whether a hex color is formatted
 * properly or not.
 *
 * This is used within themeblvd_sanitize_color().
 *
 * @since @@name-framework 2.2.0
 *
 * @param  string $hex Color hex value to check.
 * @return bool        Whether $hex is a valid hex color value.
 */
function themeblvd_validate_hex( $hex ) {

	$hex = trim( $hex );

	// Strip recognized prefixes.
	if ( 0 === strpos( $hex, '#' ) ) {

		$hex = substr( $hex, 1 );

	} elseif ( 0 === strpos( $hex, '%23' ) ) {

		$hex = substr( $hex, 3 );

	}

	if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {

		return false;

	} else {

		return true;
	}

}

/**
 * Sanitize option type, `color`.
 *
 * Note: This function was origially implemented in
 * framework v2.2.0 as themeblvd_sanitize_hex(),
 * but was later changed to themeblvd_sanitize_color().
 *
 * @since @@name-framework 2.7.0
 *
 * @param  string $input  Original value.
 * @return string $output Sanitized value.
 */
function themeblvd_sanitize_color( $input ) {

	/**
	 * Filters the default value returned when a
	 * color hex was not formatted properly.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string Color hex value like `#000000`.
	 */
	$output = apply_filters( 'themeblvd_default_color', '#000000' );

	if ( themeblvd_validate_hex( $input ) ) {

		$output = $input;

	}

	return $output;

}
