<?php
/**
 * Grid Layout Functions
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */

/**
 * Get class for a column based on our framework
 * grid keys.
 *
 * @link http://dev.themeblvd.com/tutorial/grid-system/
 *
 * Accepted inputs for $key:
 * 1. A fraction (as string) equivalent to 1/12 - 11/12
 * 2. A fraction (as string) equivalent to a percent divisable by 10
 * 3. A classic grid key for 12-col - grid_{1-12}
 * 4. A classic grid key for fifths - grid_fifth_{1-5}
 * 5. A classic grid key for tenths - grid_tenth_{1-10}
 * 6. An integer from 1-6, representing the number of equal-width columns
 *
 * @since @@name-framework 2.0.0
 *
 * @param string|int $key   Grid key, see above.
 * @return string    $class Column class.
 */
function themeblvd_grid_class( $key, $stack = 'sm' ) {

	/*
	 * Set accepted keys.
	 *
	 * By the end, we want the key to match one
	 * of these.
	 */
	$keys = array( '1', '010', '2', '020', '3', '030', '4', '040', '5', '6', '7', '060', '8', '070', '9', '080', '10', '090', '11', '12' );

	// Accepted $stack values.
	$stacks = array( 'xs', 'sm', 'md', 'lg' );

	if ( ! in_array( $key, $keys, true ) ) {

		if ( is_int( $key ) && ( ( $key >= 2 && $key <= 6 ) || 10 == $key || 12 == $key ) ) {

			switch ( $key ) {

				case 1:
					$key = '12';
					break;

				case 2:
					$key = '6';
					break;

				case 3:
					$key = '4';
					break;

				case 4:
					$key = '3';
					break;

				case 5:
					$key = '020';
					break;

				case 6:
					$key = '2';
					break;

				case 10:
					$key = '010';
					break;

				case 12:
					$key = '1';
			}
		} elseif ( false !== strpos( $key, '/' ) ) {

			$fraction = explode( '/', $key );

			$numerator = intval( $fraction[0] );

			$denominator = intval( $fraction[1] );

			// Is this a fraction of 12?
			$x = ( $numerator * 12 ) / $denominator;

			if ( floor( $x ) == $x ) {

				$key = strval( $x );

			} else {

				// Or is this a supported fifth or tenth?
				$percent = ( $numerator / $denominator ) * 100;

				if ( floor( $percent / 10 ) == ( $percent / 10 ) ) {

					$key = strval( $percent );

					$key = "0{$key}";

				}
			}
		} elseif ( false !== strpos( $key, 'grid_' ) ) {

			$key = str_replace( 'grid_', '', $key );

			if ( false !== strpos( $key, 'fifth_' ) ) {

				$key = str_replace( 'fifth_', '', $key );

				$key = strval( intval( $key ) * 20 );

				$key = "0{$key}";

			} elseif ( false !== strpos( $key, 'tenth_' ) ) {

				$key = str_replace( 'tenth_', '', $key );

				$key = intval( $key ) * 10;

				if ( 50 == $key ) {

					$key = '6'; // Convert 50% to 12-col (i.e. 6/12).

				} else {

					$key = strval( $key );

					$key = "0{$key}";

				}
			}
		}
	}

	// Verify $key.
	if ( ! in_array( $key, $keys, true ) ) {

		$key = '12'; // Set to a default, if not verified.

	}

	// Verify $stack.
	if ( ! in_array( $stack, $stacks ) ) {

		$stack = 'sm'; // Set to a default, if not verified.

	}

	/**
	 * Filters the grid class generated for a
	 * column.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string     $class Column class.
	 * @param string|int $key   Grid key, see themeblvd_grid_class() docs.
	 * @param string     $stack Responsive stacking point, `xs`, `sm`, `md`, `lg` or `xl`.
	 */
	return apply_filters( 'themeblvd_grid_class', "col-{$stack}-{$key}", $key, $stack );

}

/**
 * Determine if a set of column widths configured
 * through framework option belongs to a 12-column
 * or 10-column grid system.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  string $widths Widths of columns combined with underscore, like `1/2_1/4_1/4`.
 * @return string         Grid system, `12` or `10`.
 */
function themeblvd_grid_type( $widths ) {

	$grid = '12';

	if ( $widths && is_string( $widths ) ) {

		$widths = explode( '-', $widths );

		foreach ( $widths as $width ) {

			$key = str_replace( 'col-xs-', '', themeblvd_grid_class( $width ) );

			$key = str_replace( 'col-sm-', '', $key );

			$key = str_replace( 'col-md-', '', $key );

			$key = str_replace( 'col-lg-', '', $key );

			$key = str_replace( 'col-xl-', '', $key );

			if ( in_array( $key, array( '010', '020', '030', '040', '060', '070', '080', '090' ) ) ) {

				$grid = '10';

				break;

			}
		}
	}

	return $grid;

}

/**
 * Get a fractional representation of a grid
 * class.
 *
 * For example, given `col-sm-3` would return
 * `1/4`.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  string $class    Grid class, like `col-sm-3`.
 * @return string $fraction Fraction, like `1/4`.
 */
function themeblvd_grid_fraction( $class ) {

	$denominator = 12;

	if ( false !== strpos( $class, 'grid_' ) ) {

		$class = themeblvd_grid_class( $class );

	}

	$numerator = str_replace( 'col-xs-', '', $class );

	$numerator = str_replace( 'col-sm-', '', $numerator );

	$numerator = str_replace( 'col-md-', '', $numerator );

	$numerator = str_replace( 'col-lg-', '', $numerator );

	$numerator = str_replace( 'col-xl-', '', $numerator );

	if ( 0 === strpos( $numerator, '0' ) ) {

		$numerator = str_replace( '0', '', $numerator );

		$denominator = 10;

	}

	$gcd = themeblvd_gcd( $numerator, $denominator );

	$numerator = $numerator / $gcd;

	$denominator = $denominator / $gcd;

	$fraction = strval( $numerator ) . '/' . strval( $denominator );

	if ( '1/1' == $fraction ) {

		$fraction = '1';

	}

	return $fraction;

}

/**
 * Get the greatest common denomimator between
 * two numbers.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  int $a First number.
 * @param  int $b Second number.
 * @return int    Greatest common dendenomimator between $a and $b.
 */
function themeblvd_gcd( $a, $b ) {

	$a = abs( $a );

	$b = abs( $b );

	if ( $a < $b ) {

		list( $b, $a ) = array( $a, $b );

	}

	if ( 0 == $b ) {

		return $a;

	}

	$r = $a % $b;

	while ( $r > 0 ) {

		$a = $b;

		$b = $r;

		$r = $a % $b;

	}

	return $b;

}

/**
 * Get the HTML to open a row of a grid.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args {
 *     Row arguments.
 *
 *     @type string $wrap  Optional. CSS class for a wrapping DIV.
 *     @type string $class CSS class for row, defaults to `row`.
 *     @type string $style Optional. Inline style for row.
 * }
 * @return string $output Final HTML output to open row.
 */
function themeblvd_get_open_row( $args = array() ) {

	$output = '';

	$args = wp_parse_args( $args, array(
		'wrap'  => '',
		'class' => 'row',
		'style' => '',
	) );

	if ( $args['wrap'] ) {

		$output .= sprintf( '<div class="%s">', esc_attr( $args['wrap'] ) );

	}

	$output .= sprintf( '<div class="%s row-inner"', esc_attr( $args['class'] ) );

	if ( $args['style'] ) {

		$output .= sprintf( ' style="%s"', esc_attr( $args['style'] ) );

	}

	$output .= '>';

	/**
	 * Filters the HTML to open a row of a grid.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output to open row.
	 * @param array  $args {
	 *     Row arguments.
	 *
	 *     @type string $wrap  Optional. CSS class for a wrapping DIV.
	 *     @type string $class CSS class for row, defaults to `row`.
	 *     @type string $style Optional. Inline style for row.
	 * }
	 */
	return apply_filters( 'themeblvd_open_row', $output, $args );

}

/**
 * Display the HTML to open a row of a grid.
 *
 * @since @@name-framework 2.0.0
 *
 * @param array $args Row arguments, see themeblvd_get_open_row() docs.
 */
function themeblvd_open_row( $args = array() ) {

	echo themeblvd_get_open_row( $args );

}

/**
 * Get the markup to close a row of a grid.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args {
 *     Row arguments.
 *
 *     @type string $wrap  Optional. CSS class for a wrapping DIV.
 *     @type string $class CSS class for row, defaults to `row`.
 *     @type string $style Optional. Inline style for row.
 * }
 * @return string $output Final HTML output to close row.
 */
function themeblvd_get_close_row( $args = array() ) {

	$args = wp_parse_args( $args, array(
		'wrap'  => false,
	) );

	$output = '</div><!-- .row (end) -->';

	if ( $args['wrap'] ) {

		$output .= '</div><!-- .' . esc_attr( $args['wrap'] ) . ' (end) -->';

	}

	/**
	 * Filters the HTML to close a row of a grid.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output to close row.
	 * @param array  $args {
	 *     Row arguments.
	 *
	 *     @type string $wrap  Optional. CSS class for a wrapping DIV.
	 *     @type string $class CSS class for row, defaults to `row`.
	 *     @type string $style Optional. Inline style for row.
	 * }
	 */
	return apply_filters( 'themeblvd_close_row', $output, $args );

}

/**
 * Display the HTML to close a row of a grid.
 *
 * @since @@name-framework 2.0.0
 *
 * @param array $args Row arguments, see themeblvd_get_close_row() docs.
 */
function themeblvd_close_row( $args = array() ) {

	echo themeblvd_get_close_row( $args );

}

/**
 * Get a guess for the maximum width of an
 * element.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array|string $args {
 *     Array of element max-width arguments, or single string representing context.
 *
 *     @type string $context Usage context, `blog`, `list`, `grid`, `element` or `block`.
 *     @type string $col     If in a column, the fraction width of the column.
 *     @type int    $cols    If $context == `grid`, the number of columns.
 * }
 * @return int                Description
 */
function themeblvd_get_max_width( $args = '' ) {

	/*
	 * If a single string is passed in, it is assumed
	 * to be the $context
	 */
	if ( ! is_array( $args ) ) {

		$args = array(
			'context' => $args,
		);

	}

	$args = wp_parse_args( $args, array(
		'context' => '',
		'col'     => '',
		'cols'    => 2,
	) );

	/*
	 * The framework uses WP's global `content_width` as
	 * the width of the main container.
	 */
	$max = $GLOBALS['content_width'];

	$container = $GLOBALS['content_width'];

	$n = 0;

	$d = 0;

	if ( 'block' === $args['context'] && $args['col'] ) {

		$fraction = explode( '/', $args['col'] );

		$n = $fraction[0];

		$d = $fraction[1];

		$max = ( $n / $d ) * $container;

	} else {

		$layouts = themeblvd_sidebar_layouts();

		$layout = themeblvd_config( 'sidebar_layout' );

		$n = $layouts[ $layout ]['columns']['content'];

		$n = str_replace( 'col-xs-', '', $n );

		$n = str_replace( 'col-sm-', '', $n );

		$n = str_replace( 'col-md-', '', $n );

		$n = str_replace( 'col-lg-', '', $n );

		$n = str_replace( 'col-xl-', '', $n );

		if ( strpos( $n, '0' ) === 0 ) { // 10-column grid system uses 010, 020, 030, etc.

			$n = str_replace( '0', '', $n );

			$d = 10;

		} else {

			$d = 12;

		}

		$n = intval( $n );

		if ( 'grid' === $args['context'] && $args['cols'] ) {

			$cols = intval( $args['cols'] );

			$max = ( 1 / $cols ) * ( $n / $d ) * $container;

		} else {

			$max = ( $n / $d ) * $container;

		}
	}

	return intval( round( $max ) );

}
