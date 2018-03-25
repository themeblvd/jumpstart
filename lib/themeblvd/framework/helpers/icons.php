<?php
/**
 * Helpers: Icons
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.7.4
 */

/**
 * Get the CSS classes for displaying an icon.
 *
 * @since @@name-framework 2.7.0
 *
 * @param  string $icon  Icon name like `bolt` or instance like `fas fa-bolt`.
 * @param  array  $add   CSS classes to add.
 * @return string $class CSS classes separated by spaces.
 */
function themeblvd_get_icon_class( $icon, $add = array() ) {

	$icon_name = '';

	$icon = explode( ' ', $icon );

	$bases = array_keys( themeblvd_get_icon_types() );

	$key = 0; // Set location of icon name.

	if ( count( $icon ) > 1 ) {

		$key = 1;

	}

	$icon_name = str_replace( 'fa-', '', $icon[ $key ] );

	$icon[ $key ] = 'fa-' . $icon_name;

	$class = array_unique( array_merge( $icon, $add ) );

	// Do we already have a base class?
	$has_base_class = false;

	foreach ( $bases as $base ) {

		if ( in_array( $base, $icon ) ) {

			$has_base_class = true;

		}
	}

	// If no base class, figure out what to use.
	if ( ! $has_base_class ) {

		$base = '';

		/** This filter is documented in framework/general/frontend.php */
		if ( apply_filters( 'themeblvd_icon_shims', false ) ) {

			$base = 'fa'; // @deprecated v4 icon name.

		} else {

			$brands = themeblvd_get_icons( 'brands' );

			if ( in_array( $icon_name, $brands ) ) {

				$base = 'fab';

			} else {

				/**
				 * Filters the default fallback base class
				 * used for icons.
				 *
				 * @since @@name-framework 2.7.0
				 *
				 * @param string Default base class.
				 */
				$base = apply_filters( 'themeblvd_icon_base_class', 'fas', $icon_name );

			}
		}

		array_unshift( $class, $base );

	}

	/**
	 * Filters the classes used for displaying a
	 * generic icon.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param array  $class     CSS classes.
	 * @param string $icon_name Determined name of icon, like `bolt`.
	 * @param string $icon      Original icon value passed in like `bolt` or like `fas fa-bolt`.
	 * @param array  $add       Any CSS classes to add.
	 */
	$class = apply_filters( 'themeblvd_icon_class', $class, $icon_name, $icon, $add );

	return implode( ' ', $class );

}

/**
 * Get the CSS classes for displaying an icon.
 *
 * @since @@name-framework 2.7.4
 *
 * @param  string $class  CSS classes for icon, like `fas fa-user`.
 * @param  string $atts   HTML attributes to add to icon, like `array( 'style' => 'color: #000' )`.
 * @return string $output Final icon HTML output.
 */
function themeblvd_get_icon( $class, $atts = array() ) {

	$output = '<i class="' . esc_attr( $class ) . '"';

	if ( $atts ) {

		foreach ( $atts as $key => $value ) {

			$output .= sprintf(' %s="%s"', esc_attr( $key ), esc_attr( $value ) );

		}
	}

	$output .= '></i>';

	/**
	 * Filters the HTML output for an icon.
	 *
	 * @since @@name-framework 2.7.4
	 *
	 * @param string $output Final icon HTML output.
	 * @param string $class  CSS classes for icon, like `fas fa-user`.
	 */
	return apply_filters( 'themeblvd_icon', $output, $class, $atts );

}

/**
 * Get the icon name used for the icon linking to
 * the shopping cart.
 *
 * @since @@name-framework 2.7.4
 *
 * @return string Icon name.
 */
function themeblvd_get_shopping_cart_icon_name() {

	/**
	 * Filters the icon name used for the icon linking to
	 * the shopping cart.
	 *
	 * @since @@name-framework 2.7.4
	 *
	 * @param string Icon name.
	 */
	return apply_filters( 'themeblvd_shopping_cart_icon_name', 'shopping-basket' );

}

/**
 * Get the general icon name used for the searchform
 * and linking to it.
 *
 * @since @@name-framework 2.7.4
 *
 * @return string Icon name.
 */
function themeblvd_get_search_icon_name() {

	/**
	 * Filters the general icon name used for the searchform
	 * and linking to it.
	 *
	 * @since @@name-framework 2.7.4
	 *
	 * @param string Icon name.
	 */
	return apply_filters( 'themeblvd_search_icon_name', 'search' );

}

/**
 * Get array of framework icons.
 *
 * @since @@name-framework 2.3.0
 *
 * @param  string $type  Type of icons to retrieve, `solid` or `brands`.
 * @return array  $icons Array of icons.
 */
function themeblvd_get_icons( $type = 'solid' ) {

	$icons = get_transient( 'themeblvd_' . get_template() . '_icons_' . $type );

	if ( ! $icons ) {

		$icons = array();

		$file = themeblvd_get_icon_data_file();

		if ( file_exists( $file ) ) {

			$data = file_get_contents( $file );

			if ( $data ) {

				$data = json_decode( $data );

				foreach ( $data as $key => $value ) {

					if ( in_array( $type, $value->styles ) ) {

						$icons[] = $key;

					}
				}
			}

			// We'll store our result in a 1-day transient.
			set_transient( 'themeblvd_' . get_template() . '_icons_' . $type, $icons, '86400' );

		}
	}

	/**
	 * Filters the array of icons that the user can
	 * select from in the icon browser.
	 *
	 * @since @@name-framework 2.3.0
	 *
	 * @param array $icons All icons found from fontawesome.css.
	 */
	return apply_filters( 'themeblvd_icons', $icons, $type );

}

/**
 * Get icon types.
 *
 * @since @@name-framework 2.7.0
 *
 * @return array $types Icon types.
 */
function themeblvd_get_icon_types() {

	$types = array(
		'fas' => 'solid',
		'fab' => 'brands',
	);

	/**
	 * Filters the array of icons that the user can
	 * select from in the icon browser.
	 *
	 * @since @@name-framework 2.3.0
	 *
	 * @param array $types Icon types.
	 */
	return apply_filters( 'themeblvd_icon_types', $types );

}

/**
 * Get the URL to the data file used determine
 * which icons are included from FontAwesome.
 *
 * @since @@name-framework 2.7.0
 *
 * @return string File URL, like `http://my-site.com/file.json`.
 */
function themeblvd_get_icon_data_file() {

	/**
	 * Filters the URL to the data file used
	 * determine which icons are included
	 * from FontAwesome.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string File URL, like `http://my-site.com/file.json`.
	 */
	return apply_filters( 'themeblvd_icon_data_file_url', TB_FRAMEWORK_DIRECTORY . '/admin/assets/data/icons.json' );

}

/**
 * Get icon search data.
 *
 * This data gets printed to allow the user to
 * filter quickly through available icons.
 *
 * @since @@name-framework 2.7.0
 *
 * @return array $data Searchable icon data.
 */
function themeblvd_get_icon_search_data() {

	$data = get_transient( 'themeblvd_' . get_template() . '_icon_search_data' );

	if ( ! $data ) {

		$data = array();

		$file = themeblvd_get_icon_data_file();

		if ( file_exists( $file ) ) {

			$json = file_get_contents( $file );

			if ( $json ) {

				$raw_data = json_decode( $json );

				foreach ( $raw_data as $key => $value ) {

					$terms = explode( '-', $key );

					$terms = array_merge( $terms, $value->search->terms );

					$data[ $key ] = $terms;

				}
			}

			// We'll store our result in a 1-day transient.
			set_transient( 'themeblvd_' . get_template() . '_icon_search_data', $data, '86400' );

		}

	}

	/**
	 * Filters the searchable icon data.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param array $data Searchable icon data.
	 */
	return apply_filters( 'themeblvd_icon_search_data', $data );

}

/**
 * Get icon JavaScript file.
 *
 * @since @@name-framework 2.7.0
 *
 * @return array $file {
 *     @type string $handle  Icon library handle.
 *     @type string $url     Icon library JavaScript file URL.
 *     @type string $version Icon library version.
 * }
 */
function themeblvd_get_icon_js_file() {

	$suffix = themeblvd_script_debug() ? '' : '.min';

	/**
	 * Filters the URL to include the icon font JavaScript
	 * file.
	 *
	 * Note: If you'd like to filter not only the file URL,
	 * but also the handle and/or version number, you can
	 * use the `themeblvd_icon_js_file` filter below instead.
	 *
	 * Note: If you're wanting to use multiple Font Awesome
	 * JavaScript files, return an array of the file URL strings
	 * instead of just a single string.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param string|array $url JavaScript file URL(s), like `http://mysite.com/file.js`.
	 */
	$url = apply_filters(
		'themeblvd_icon_js_file_url',
		TB_FRAMEWORK_URI . "/assets/js/themeblvd-fontawesome{$suffix}.js"
	);

	$file = array(
		'handle'  => 'fontawesome',
		'url'     => $url,
		'version' => '5.0.8',
	);

	/**
	 * Filters the handle, url and version to include
	 * the icon font JavaScript file.
	 *
	 * The theme currently uses FontAwesome for
	 * this, and so this can be useful if you're
	 * trying to include a custom version of
	 * FontAwesome with more styles or icons.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param array $file {
	 *     @type string $handle  Icon library handle.
	 *     @type string $url     Icon library JavaScript file URL.
	 *     @type string $version Icon library version.
	 * }
	 */
	return apply_filters( 'themeblvd_icon_js_file', $file );

}
