<?php
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