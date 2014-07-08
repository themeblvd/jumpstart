<?php
/**
 * Get chart using chart.js
 *
 * @since 2.5.0
 *
 * @param string $type Type of chart - pie, line, graph
 * @param array $args Options for from "Milestone" block
 */
function themeblvd_get_chart( $type, $args ) {

    wp_enqueue_script( 'charts' );

    $defaults = array(
        'id'        => uniqid('chart_'),    // Unique ID for this chart
        'data'      => array(),             // Data for charts - warning: formatted differently for different kinds of charts
        'width'     => '200',               // Width of chart
        'height'    => '200',               // Height of chart
        'labels'    => '',                  // X-axis labels for line and bar graphs
        'tooltips'  => '1',                 // Whether to display labels when hovered on
        'legend'    => '0',                 // Whether to display chart legend
        'doughnut'  => '0',                 // For pie chart, whether to display as doughnut
        'curve'     => '1',                 // For line chart, whether to curve lines
        'fill'      => '1',                 // For line chart, whether to fill datasets with color
        'dot'       => '1',                 // For line chart, whether to display dots for each data point
        'zero'      => '1'                  // For line/bar chart, whether to start the scale (y-axis) at 0
    );
    $args = wp_parse_args( $args, $defaults );

    $class = 'tb-chart';

    if ( $type == 'line' || $type == 'bar' ) {
        $class .= ' '.$type;
    } else if ( $type == 'pie' ) {
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
        'legend' => $args['legend']
    );

    switch ( $type ) {
        case 'bar' :
            $options['labels'] = str_replace(' ', '', $args['labels']);
            $options['zero'] = $args['zero'];
            $options['tooltips'] = $args['tooltips'];
            break;
        case 'line' :
            $options['labels'] = str_replace(' ', '', $args['labels']);
            $options['zero'] = $args['zero'];
            $options['curve'] = $args['curve'];
            $options['fill'] = $args['fill'];
            $options['dot'] = $args['dot'];
            $options['tooltips'] = $args['tooltips'];
            break;
        case 'pie' :
            $options['tooltips'] = $args['tooltips'];
            break;
    }

    $output = sprintf( '<div class="%s"', $class );

    foreach ( $options as $key => $value ) {
        $output .= sprintf( ' data-%s="%s"', $key, $value );
    }

    $output .= '>';

    if ( $args['data'] ) {
        foreach ( $args['data'] as $data ) {
            switch ( $type ) {
                case 'bar' :
                    $output .= sprintf( '<div class="data" data-label="%s" data-values="%s" data-fill="%s"></div>', $data['label'], str_replace(' ', '', $data['values']), $data['color'] );
                    break;
                case 'line' :
                    $output .= sprintf( '<div class="data" data-label="%s" data-values="%s" data-fill="%s" data-stroke="%s" data-point="%s"></div>', $data['label'], str_replace(' ', '', $data['values']), themeblvd_get_rgb($data['color'], '0.2'), $data['color'], $data['color'] );
                    break;
                case 'pie' :
                    $output .= sprintf( '<div class="data" data-label="%s" data-value="%s" data-color="%s" data-hightlight="%s"></div>', $data['label'], $data['value'], $data['color'], themeblvd_adjust_color( $data['color'], 20, 'lighten' ) );
            }
        }
    }

    $output .= '<div class="chart-wrap">';
    $output .= sprintf( '<canvas id="%s" width="%s" height="%s"></canvas>', $args['id'], $args['width'], $args['height'] );
    $output .= '</div><!-- .chart-wrap (end) -->';

    $output .= '</div><!-- .tb-chart (end) -->';

    return apply_filters( 'themeblvd_chart', $output, $type, $args );
}

/**
 * Display chart using chart.js
 *
 * @since 2.5.0
 *
 * @param string $type Type of chart - pie, line, graph
 * @param array $args Arguments for milestone block.
 */
function themeblvd_chart( $type, $args ) {
    echo themeblvd_get_chart( $type, $args );
}

/**
 * Get milestone
 *
 * @since 2.5.0
 *
 * @param array $args Options for from "Milestone" block
 */
function themeblvd_get_milestone( $args ) {

	$defaults = array(
		'milestone'		=> '100',		// The number for the milestone
		'before'		=> '',			// Symbol before milestone number
		'after'			=> '',			// Symbol after milestone number
		'color'			=> '#0c9df0',	// Color of text for milestone number
		'text'			=> '',			// Brief text to describe milestone
		'boxed'			=> '0'			// Whether to wrap milestone in borered box
    );
    $args = wp_parse_args( $args, $defaults );

    $class = 'tb-milestone';

    if ( $args['boxed'] ) {
    	$class .= ' boxed';
    }

    $output = sprintf( '<div class="%s">', $class );

    $output .= sprintf( '<span class="milestone" style="color: %s;">%s<span class="num">%s</span>%s</span>', $args['color'], $args['before'], $args['milestone'], $args['after'] );

    if ( $args['text'] ) {
    	$output .= themeblvd_divider( array('type' => 'solid', 'width' => '50') );
    	$output .= sprintf( '<span class="text">%s</span>', $args['text'] );
    }

    $output .= '</div><!-- .tb-milestone (end) -->';

    return apply_filters( 'themeblvd_milestone', $output, $args );
}

/**
 * Display milestone
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for milestone block.
 */
function themeblvd_milestone( $args ) {
	echo themeblvd_get_milestone( $args );
}

/**
 * Get milestone percentage as pie chart
 *
 * @since 2.5.0
 *
 * @param array $args Options for from "Milestone" block
 */
function themeblvd_get_milestone_percent( $args ) {

	$defaults = array(
		'percent'		=> '75',		// Percentage for pie chart
		'color'			=> '#0c9df0',	// Color of the milestone percentage
		'track_color'	=> '#eeeeee',	// Color track containing milestone ring
		'display'		=> '',			// Text in the middle of the pie chart
		'title'			=> '',			// Title below pie chart
		'text'			=> '',			// Description below title
		'text_align'	=> 'center',	// Text alignment - left, right, or center
		'boxed'			=> '0'			// Whether to wrap milestone in borered box
    );
    $args = wp_parse_args( $args, $defaults );

    $class = 'tb-milestone-percent text-'.$args['text_align'];

    if ( $args['title'] || $args['text'] ) {
    	$class .= ' has-text';
    }

    if ( $args['boxed'] ) {
    	$class .= ' boxed';
    }

    $output = sprintf( '<div class="%s">', $class );

    $output .= sprintf( '<div class="milestone chart" data-percent="%s" data-color="%s" data-track-color="%s">', $args['percent'], $args['color'], $args['track_color'] );

    if ( $args['display'] ) {
    	$output .= '<span class="display">'.$args['display'].'</span>';
    }

	$output .= '</div><!-- .milestone (end) -->';

	if ( $args['title'] || $args['text'] ) {

		$output .= '<div class="content">';

		if ( $args['title'] ) {
			$output .= '<h3>'.$args['title'].'</h3>';
		}

		if ( $args['title'] ) {
			$output .= themeblvd_get_content( $args['text'] );
		}

		$output .= '</div><!-- .content (end) -->';
	}

    $output .= '</div><!-- .tb-milestone (end) -->';

    return apply_filters( 'themeblvd_milestone_percent', $output, $args );
}

/**
 * Display milestone percentage as pie chart
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for milestone block.
 */
function themeblvd_milestone_percent( $args ) {
	echo themeblvd_get_milestone_percent( $args );
}