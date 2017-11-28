<?php
/**
 * Frontend Blocks: Statistical Elements
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.5.0
 */

/**
 * Get a chart block, using chart.js script.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $type Type of chart, `pie`, `line` or `graph`.
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string      $id       Unique ID for this chart.
 *     @type array       $data     Data for charts from layout builder; this data is formatted differently for chart types.
 *     @type string|int  $width    Width of chart, like `200`.
 *     @type string|int  $height   Height of chart, like `200`.
 *     @type string      $labels   If $type == `line` || $type == `bar`, x-axis labels.
 *     @type string|bool $tooltips Whether to display labels when hovered chart elements are hovered on.
 *     @type string|bool $legend   Whether to display chart legend.
 *     @type string|bool $doughnut If $type == `pie`, whether to display as doughnut.
 *     @type string|bool $curve    If $type == `line`, whether to curve lines.
 *     @type string|bool $fill     If $type == `line`, whether to fill datasets with color.
 *     @type string|bool $dot      If $type == `line`, whether to display dots for each data point.
 *     @type string|bool $zero     If $type == `pie` || $type == `bar`, whether to start the y-axis scale at 0.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_chart( $type, $args ) {

	wp_enqueue_script( 'charts' );

	$args = wp_parse_args( $args, array(
		'id'       => uniqid( 'chart_' . rand() ),
		'data'     => array(),
		'width'    => '200',
		'height'   => '200',
		'labels'   => '',
		'tooltips' => '1',
		'legend'   => '0',
		'doughnut' => '0',
		'curve'    => '1',
		'fill'     => '1',
		'dot'      => '1',
		'zero'     => '1',
	));

	$class = 'tb-chart';

	if ( 'line' === $type || 'bar' === $type ) {

		$class .= ' ' . $type;

	} elseif ( 'pie' === $type ) {

		if ( $args['doughnut'] ) {

			$class .= ' doughnut';

		} else {

			$class .= ' pie';

		}
	}

	if ( $args['legend'] ) {

		$class .= ' has-legend';

	}

	$options = array(
		'legend' => $args['legend'],
	);

	switch ( $type ) {
		case 'bar':
			$options['labels'] = str_replace( ' ', '', $args['labels'] );

			$options['zero'] = $args['zero'];

			$options['tooltips'] = $args['tooltips'];

			break;

		case 'line':
			$options['labels'] = str_replace( ' ', '', $args['labels'] );

			$options['zero'] = $args['zero'];

			$options['curve'] = $args['curve'];

			$options['fill'] = $args['fill'];

			$options['dot'] = $args['dot'];

			$options['tooltips'] = $args['tooltips'];

			break;

		case 'pie':
			$options['tooltips'] = $args['tooltips'];

	}

	$output = sprintf( '<div class="%s"', $class );

	foreach ( $options as $key => $value ) {

		$output .= sprintf(
			' data-%s="%s"',
			esc_attr( $key ),
			esc_attr( $value )
		);

	}

	$output .= '>';

	if ( $args['data'] ) {

		foreach ( $args['data'] as $data ) {

			switch ( $type ) {

				case 'bar':
					$output .= sprintf(
						'<div class="data" data-label="%s" data-values="%s" data-fill="%s"></div>',
						esc_attr( $data['label'] ),
						esc_attr( str_replace( ' ', '', $data['values'] ) ),
						esc_attr( $data['color'] )
					);

					break;

				case 'line':
					$output .= sprintf(
						'<div class="data" data-label="%s" data-values="%s" data-fill="%s" data-stroke="%s" data-point="%s"></div>',
						esc_attr( $data['label'] ),
						esc_attr( str_replace( ' ', '', $data['values'] ) ),
						esc_attr( themeblvd_get_rgb( $data['color'], '0.2' ) ),
						esc_attr( $data['color'] ),
						esc_attr( $data['color'] )
					);

					break;

				case 'pie':
					$output .= sprintf(
						'<div class="data" data-label="%s" data-value="%s" data-color="%s" data-hightlight="%s"></div>',
						esc_attr( $data['label'] ),
						esc_attr( $data['value'] ),
						esc_attr( $data['color'] ),
						themeblvd_adjust_color( $data['color'], 20, 'lighten' )
					);

			}
		}
	}

	$output .= '<div class="chart-wrap">';

	$output .= sprintf(
		'<canvas id="%s" width="%s" height="%s"></canvas>',
		esc_attr( $args['id'] ),
		esc_attr( $args['width'] ),
		esc_attr( $args['height'] )
	);

	$output .= '</div><!-- .chart-wrap (end) -->';

	$output .= '</div><!-- .tb-chart (end) -->';

	/**
	 * Filters the final HTML output for a chart
	 * block, using chart.js script.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param string $type Type of chart, `pie`, `line` or `graph`.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string      $id       Unique ID for this chart.
	 *     @type array       $data     Data for charts from layout builder; this data is formatted differently for chart types.
	 *     @type string|int  $width    Width of chart, like `200`.
	 *     @type string|int  $height   Height of chart, like `200`.
	 *     @type string      $labels   If $type == `line` || $type == `bar`, x-axis labels.
	 *     @type string|bool $tooltips Whether to display labels when hovered chart elements are hovered on.
	 *     @type string|bool $legend   Whether to display chart legend.
	 *     @type string|bool $doughnut If $type == `pie`, whether to display as doughnut.
	 *     @type string|bool $curve    If $type == `line`, whether to curve lines.
	 *     @type string|bool $fill     If $type == `line`, whether to fill datasets with color.
	 *     @type string|bool $dot      If $type == `line`, whether to display dots for each data point.
	 *     @type string|bool $zero     If $type == `pie` || $type == `bar`, whether to start the y-axis scale at 0.
	 * }
	 */
	return apply_filters( 'themeblvd_chart', $output, $type, $args );

}

/**
 * Display a chart block, using chart.js script.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param string $type Type of chart, `pie`, `line` or `graph`.
 * @param array  $args Block arguments, see themeblvd_get_chart() docs.
 */
function themeblvd_chart( $type, $args ) {

	echo themeblvd_get_chart( $type, $args );

}

/**
 * Get a milestone block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string      $milestone Number for the milestone.
 *     @type string      $color     Color of text for milestone number.
 *     @type string      $text      Brief text to describe milestone.
 *     @type string|bool $boxed     Whether to wrap milestone in bordered box.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_milestone( $args ) {

	$args = wp_parse_args( $args, array(
		'milestone' => '100',
		'color'     => '#0c9df0',
		'text'      => '',
		'boxed'     => '0',
	));

	$class = 'tb-milestone';

	if ( $args['boxed'] ) {

		$class .= ' boxed';

	}

	$output = sprintf( '<div class="%s">', $class );

	$num = filter_var( $args['milestone'], FILTER_SANITIZE_NUMBER_INT );

	$num = str_replace( '-', '', $num );

	$num = str_replace( '+', '', $num );

	$milestone = str_replace(
		$num,
		'<span class="num">' . $num . '</span>',
		themeblvd_kses( $args['milestone'] )
	);

	if ( themeblvd_do_scroll_effects() ) {

		$milestone = str_replace( $num, '0', $milestone );

	}

	$output .= sprintf(
		'<span class="milestone" style="color: %s;" data-num="%s">%s</span>',
		esc_attr( $args['color'] ),
		esc_attr( $num ),
		$milestone
	);

	if ( $args['text'] ) {

		$output .= sprintf(
			'<h5 class="text">%s</h5>',
			themeblvd_kses( $args['text'] )
		);

	}

	$output .= '</div><!-- .tb-milestone (end) -->';

	/**
	 * Filters the final HTML output for a foo
	 * block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string      $milestone Number for the milestone.
	 *     @type string      $color     Color of text for milestone number.
	 *     @type string      $text      Brief text to describe milestone.
	 *     @type string|bool $boxed     Whether to wrap milestone in bordered box.
	 * }
	 */
	return apply_filters( 'themeblvd_milestone', $output, $args );

}

/**
 * Display a milestone block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_milestone() docs.
 */
function themeblvd_milestone( $args ) {
	echo themeblvd_get_milestone( $args );
}

/**
 * Get a milestone percentage block, as a
 * pie chart.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string|int  $percent     Percentage for pie chart, like `75`.
 *     @type string      $color       Color of the printed milestone percentage, like `#000`.
 *     @type string      $track_color Color of track the ring sits on, like `#000`.
 *     @type string      $display     Text in the middle of the pie chart.
 *     @type string      $title       Title below pie chart.
 *     @type string      $text        Description below title.
 *     @type string      $text_align  Text alignment, `left`, `right` or `center`.
 *     @type string|bool $boxed       Whether to wrap milestone in bordered box.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_milestone_ring( $args ) {

	$args = wp_parse_args( $args, array(
		'percent'     => '75',
		'color'       => '#0c9df0',
		'track_color' => '#eeeeee',
		'display'     => '',
		'title'       => '',
		'text'        => '',
		'text_align'  => 'center',
		'boxed'       => '0',
	));

	$class = 'tb-milestone-percent text-' . $args['text_align'];

	if ( $args['title'] || $args['text'] ) {

		$class .= ' has-text';

	}

	if ( $args['boxed'] ) {

		$class .= ' boxed';

	}

	$output = sprintf( '<div class="%s">', esc_attr( $class ) );

	$output .= sprintf(
		'<div class="milestone chart" data-percent="%s" data-color="%s" data-track-color="%s">',
		esc_attr( $args['percent'] ),
		esc_attr( $args['color'] ),
		esc_attr( $args['track_color'] )
	);

	if ( $args['display'] ) {

		if ( false !== strpos( $args['display'], 'fa' ) ) {

			$output .= '<span class="display">' . themeblvd_kses( $args['display'] ) . '</span>';

		} else {

			$output .= '<h5 class="display">' . themeblvd_kses( $args['display'] ) . '</h5>';

		}
	}

	$output .= '</div><!-- .milestone (end) -->';

	if ( $args['title'] || $args['text'] ) {

		$output .= '<div class="content">';

		if ( $args['title'] ) {

			$output .= '<h5>' . themeblvd_kses( $args['title'] ) . '</h5>';

		}

		if ( $args['text'] ) {

			$output .= themeblvd_get_content( $args['text'] );

		}

		$output .= '</div><!-- .content (end) -->';

	}

	$output .= '</div><!-- .tb-milestone (end) -->';

	/**
	 * Filters the final HTML output for a foo
	 * block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string|int  $percent     Percentage for pie chart, like `75`.
	 *     @type string      $color       Color of the printed milestone percentage, like `#000`.
	 *     @type string      $track_color Color of track the ring sits on, like `#000`.
	 *     @type string      $display     Text in the middle of the pie chart.
	 *     @type string      $title       Title below pie chart.
	 *     @type string      $text        Description below title.
	 *     @type string      $text_align  Text alignment, `left`, `right` or `center`.
	 *     @type string|bool $boxed       Whether to wrap milestone in bordered box.
	 * }
	 */
	return apply_filters( 'themeblvd_milestone_ring', $output, $args );

}

/**
 * Display a milestone percentage block, as a
 * pie chart.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_milestone_ring() docs.
 */
function themeblvd_milestone_ring( $args ) {

	echo themeblvd_get_milestone_ring( $args );

}

/**
 * Get an individual progress bar block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $args {
 *     Block arguments.
 *
 *     @type string     $label       Label of what is represented, like `Graphic Design`.
 *     @type string     $label_value Label for value, like `80%`.
 *     @type string|int $value       Actual numeric value, like `50`.
 *     @type string|int $total       Number that $value should be devided into, to find percentage, like `100`.
 *     @type string     $color       Color of the progress bar value, like `#000`.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_progress_bar( $args ) {

	$args = wp_parse_args( $args, array(
		'label'       => '',
		'label_value' => '',
		'value'       => '50',
		'total'       => '100',
		'color'       => '#428bca',
	));

	$class = 'tb-progress';

	if ( $args['label'] || $args['label_value'] ) {

		$class .= ' has-label';

	}

	$output = sprintf( '<div class="%s">', $class );

	if ( $args['label'] ) {

		$output .= sprintf(
			'<h5 class="label text">%s</h5>',
			esc_attr( $args['label'] )
		);

	}

	if ( $args['label_value'] ) {

		$output .= sprintf(
			'<span class="label value">%s</span>',
			esc_attr( $args['label_value'] )
		);

	}

	$percent = ( intval( $args['value'] ) / intval( $args['total'] ) ) * 100;

	$display = '0';

	if ( ! themeblvd_do_scroll_effects() ) {

		$display = $percent;

	}

	$style = sprintf( 'width: %s%%;', $display );

	$class = 'progress';

	$output .= sprintf( '<div class="%s">', $class );

	$class = 'progress-bar';

	if ( strpos( $args['color'], '#' ) === 0 ) {

		$style .= sprintf( ' background-color: %s;', $args['color'] );

	} else {

		$class .= ' progress-bar-' . $args['color'];

	}

	$output .= sprintf(
		'<div class="%s" role="progressbar" aria-valuenow="%s" aria-valuemin="0" aria-valuemax="%s" data-percent="%s" style="%s"></div>',
		esc_attr( $class ),
		$percent,
		esc_attr( $args['total'] ),
		$percent,
		esc_attr( $style )
	);

	$output .= '</div><!-- .progress (end) -->';

	$output .= '</div><!-- .tb-progress (end) -->';

	/**
	 * Filters the final HTML output for an
	 * individual progress bar block.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type string     $label       Label of what is represented, like `Graphic Design`.
	 *     @type string     $label_value Label for value, like `80%`.
	 *     @type string|int $value       Actual numeric value, like `50`.
	 *     @type string|int $total       Number that $value should be devided into, to find percentage, like `100`.
	 *     @type string     $color       Color of the progress bar value, like `#000`.
	 * }
	 */
	return apply_filters( 'themeblvd_progress_bar', $output, $args );

}

/**
 * Display an individual progress bar block.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args Block arguments, see themeblvd_get_progress_bar() docs.
 */
function themeblvd_progress_bar( $args ) {

	echo themeblvd_get_progress_bar( $args );

}

/**
 * Get a set of progress bars.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args {
 *     Block arguments.
 *
 *     @type array $bars Array of progress bars formatted for themeblvd_get_progress_bar().
 * }
 */
function themeblvd_get_progress_bars( $args ) {

	$args = wp_parse_args( $args, array(
		'bars' => array(),
	));

	$output = '<div class="tb-progress-bars">';

	if ( $args['bars'] ) {

		foreach ( $args['bars'] as $bar ) {

			$output .= themeblvd_progress_bar( $bar );

		}
	}

	$output .= '</div><!-- .tb-progress-bars (end) -->';

	/**
	 * Filters the final HTML output for a set
	 * of progress bars.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Block arguments.
	 *
	 *     @type array $bars Array of progress bars formatted for themeblvd_get_progress_bar().
	 * }
	 */
	return apply_filters( 'themeblvd_progress_bars', $output, $args );

}

/**
 * Display a set of progress bars.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args Arguments for progress bars
 */
function themeblvd_progress_bars( $args ) {

	echo themeblvd_get_progress_bars( $args );

}
