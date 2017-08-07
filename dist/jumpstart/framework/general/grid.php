<?php
/**
 * Frontend grid functions.
 *
 * @author		Jason Bobich
 * @copyright	2009-2017 Theme Blvd
 * @link		http://themeblvd.com
 * @package 	Jump_Start
 */

/**
 * Get class for a column based on our framework grid keys.
 * See: http://dev.themeblvd.com/tutorial/grid-system/
 *
 * Accepted inputs for $key:
 * 1: A fraction (as string) equivalent to 1/12 - 11/12
 * 2: A fraction (as string) equivalent to a percent divisable by 10
 * 3: A classic grid key for 12-col - grid_{1-12}
 * 4: A classic grid key for fifths - grid_fifth_{1-5}
 * 5: A classic grid key for tenths - grid_tenth_{1-10}
 * 6: An integer from 1-6, representing the number of equal-width columns
 *
 * @since 2.0.0
 *
 * @param string|int $key Grid key - grid_3, grid_4, etc.
 * @return string $class CSS class to use for column
 */
function themeblvd_grid_class( $key, $stack = 'sm' ) {

	// Accepted Keys. By the end, we want
	// the key to match one of these.
	$keys = array( '1', '010', '2', '020', '3', '030', '4', '040', '5', '6', '7', '060', '8', '070', '9', '080', '10', '090', '11', '12' );

	// Accepted $stack values
	$stacks = array( 'xs', 'sm', 'md', 'lg' );

	if ( ! in_array( $key, $keys, true ) ) {

		if ( is_int($key) && ( ( $key >= 2 && $key <= 6 ) || $key == 10 || $key == 12 ) ) {
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

		} else if ( strpos( $key, '/' ) !== false ) {

			$fraction = explode( '/', $key );
			$numerator = intval( $fraction[0] );
			$denominator = intval( $fraction[1] );

			// Is this a fraction of 12?
			$x = ($numerator*12)/$denominator;

			if ( $x == floor($x) ) {

				$key = strval($x);

			} else {

				// Or is this a supported fifth or tenth?
				$percent = ($numerator/$denominator)*100;

				if ( $percent/10 == floor($percent/10) ) {
					$key = strval($percent);
					$key = "0{$key}";
				}

			}

		} else if ( strpos( $key, 'grid_' ) !== false ) {

			$key = str_replace( 'grid_', '', $key );

			if ( strpos( $key, 'fifth_' ) !== false ) {

				$key = str_replace( 'fifth_', '', $key );
				$key = strval( intval($key)*20 );
				$key = "0{$key}";

			} elseif ( strpos( $key, 'tenth_' ) !== false ) {

				$key = str_replace( 'tenth_', '', $key );
				$key = intval($key)*10;

				if ( $key == 50 ) {
					$key = '6'; // Convert 50% to 12-col (i.e. 6/12)
				} else {
					$key = strval($key);
					$key = "0{$key}";
				}

			}
		}

	}

	// Verify $key
	if ( ! in_array( $key, $keys, true ) ) {
		$key = '12'; // Default
	}

	// Verify $stack
	if ( ! in_array( $stack, $stacks ) ) {
		$stack = 'sm'; // Default
	}

	if ( themeblvd_is_ie( array('8') ) ) {
		$stack = 'xs';
	}

	return apply_filters( 'themeblvd_grid_class', "col-{$stack}-{$key}", $key, $stack );
}

/**
 * Determine if a set of column widths belongs to a
 * 12-column or 10-column grid system.
 *
 * @since 2.5.0
 *
 * @param string $widths Widths of columns combined with underscore, Ex: 1/2_1/4_1/4
 * @return string Grid system, 12 or 10
 */
function themeblvd_grid_type( $widths ) {

	$grid = '12';

	if ( $widths && is_string($widths) ) {

		$widths = explode( '-', $widths );

		foreach ( $widths as $width ) {

			$key = str_replace('col-xs-', '', themeblvd_grid_class($width));
			$key = str_replace('col-sm-', '', $key);
			$key = str_replace('col-md-', '', $key);
			$key = str_replace('col-lg-', '', $key);

			if ( in_array( $key, array( '010', '020', '030', '040', '060', '070', '080','090' ) ) ) {
				$grid = '10';
				break;
			}
		}
	}

	return $grid;
}

/**
 * Take a grid class and return a visual fractional
 * representation as a string. For example, "col-sm-3"
 * would be 3/12, which returned as 1/4.
 *
 * @since 2.5.0
 *
 * @param string $class Grid class - col-sm-3, col-sm-040, or old grid key like grid_3, grid_fifth_1
 * @return string Grid system, 12 or 10
 */
function themeblvd_grid_fraction( $class ) {

	$denominator = 12;

	if ( strpos( $class, 'grid_' ) !== false ) {
		$class = themeblvd_grid_class($class);
	}

	$numerator = str_replace('col-xs-', '', $class);
	$numerator = str_replace('col-sm-', '', $numerator);
	$numerator = str_replace('col-md-', '', $numerator);
	$numerator = str_replace('col-lg-', '', $numerator);

	if ( strpos( $numerator, '0' ) === 0 ) {
		$numerator = str_replace( '0', '', $numerator );
		$denominator = 10;
	}

	$gcd = themeblvd_gcd( $numerator, $denominator );
	$numerator = $numerator / $gcd;
	$denominator =  $denominator / $gcd;

	$fraction = strval($numerator).'/'.strval($denominator);

	if ( $fraction == '1/1' ) {
		$fraction = '1';
	}

	return $fraction;
}

/**
 * Return greatest common denonimator between two numbers.
 *
 * @since 2.5.0
 */
function themeblvd_gcd( $a, $b ) {

    $a = abs($a);
    $b = abs($b);

    if ( $a < $b ) {
    	list($b, $a) = array($a, $b);
    }

    if ( $b == 0) {
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
 * Get the markup to open a row of a grid.
 *
 * @since 2.5.0
 */
function themeblvd_get_open_row( $args = array() ) {

	$output = '';

	$defaults = array(
		'wrap'	=> '',
		'class'	=> 'row',
		'style'	=> ''
	);
	$args = wp_parse_args( $args, $defaults );

	if ( $args['wrap'] ) {
		$output .= sprintf( '<div class="%s">', $args['wrap'] );
	}

	$output .= sprintf( '<div class="%s row-inner"', $args['class'] );

	if ( $args['style'] ) {
		$output .= sprintf(' style="%s"', $args['style']);
	}

	$output .= '>';

	return apply_filters( 'themeblvd_open_row', $output, $args );
}

/**
 * Output the markup to open a row of a grid.
 *
 * @since 2.0.0
 */
function themeblvd_open_row( $args = array() ) {
	echo themeblvd_get_open_row( $args );
}

/**
 * Get the markup to close a row of a grid.
 *
 * @since 2.5.0
 */
function themeblvd_get_close_row( $args = array() ) {

	$defaults = array(
		'wrap'	=> false
	);
	$args = wp_parse_args( $args, $defaults );

	$output = '</div><!-- .row (end) -->';

	if ( $args['wrap'] ) {
		$output .= '</div><!-- Row-Wrapping DIV (end) -->';
	}

	return apply_filters( 'themeblvd_close_row', $output, $args );
}

/**
 * Output the markup to close a row of a grid.
 *
 * @since 2.0.0
 */
function themeblvd_close_row( $args = array() ) {
	echo themeblvd_get_close_row( $args );
}

/**
 * Attempt to estimate the maximum width of a container
 * depending on the context of the situation.
 *
 * @since 2.5.0
 *
 * @param string $var Description
 * @return string $var Description
 */
function themeblvd_get_max_width( $args = '' ) {

	// If a single string is passed in, it is assumed
	// to be the $context
	if ( ! is_array($args) ) {
		$args = array(
			'context' => $args
		);
	}

	$defaults = array(
		'context'	=> '',		// blog, list, grid, element, block
		'col'		=> '',		// If in a column, the fraction width of the column
		'cols'		=> 2 		// If "grid" the number of columns
	);
	$args = wp_parse_args( $args, $defaults );

	// The framework uses WP's global "content_width" as the
	// width of the main container
	$max = $container = $GLOBALS['content_width'];

	$n = 0;
	$d = 0;

	if ( $args['context'] == 'block' && $args['col'] ) {

		$fraction = explode('/', $args['col']);
		$n = $fraction[0];
		$d = $fraction[1];

		$max = ($n/$d) * $container;

	} else {

		$layouts = themeblvd_sidebar_layouts();
		$layout = themeblvd_config('sidebar_layout');
		$n = $layouts[$layout]['columns']['content'];

		$n = str_replace('col-xs-', '', $n);
		$n = str_replace('col-sm-', '', $n);
		$n = str_replace('col-md-', '', $n);
		$n = str_replace('col-lg-', '', $n);

		if ( strpos($n, '0') === 0 ) { // 10-col grid system uses 010, 020, 030, etc
			$n = str_replace('0', '', $n);
			$d = 10;
		} else {
			$d = 12;
		}

		$n = intval($n);

		if ( $args['context'] == 'grid' && $args['cols'] ) {
			$cols = intval($args['cols']);
			$max = (1/$cols) * ($n/$d) * $container;
		} else {
			$max = ($n/$d) * $container;
		}
	}

	return intval(round($max));
}
