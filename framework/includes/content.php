<?php
if ( !function_exists( 'themeblvd_blocks' ) ) :
/**
 * Get column of content blocks.
 *
 * @since 2.5.0
 *
 * @param array $blocks A set of content blocks
 */
function themeblvd_blocks( $blocks ) {

	if ( $blocks ) {

		foreach ( $blocks as $id => $block ) {

			$type = '';
			if ( isset( $block['type'] ) ) {
				$type = $block['type'];
			}

			$options = array();
			if ( isset( $block['options'] ) ) {
				$options = $block['options'];
			}

			themeblvd_block( $id, $type, $options );
		}
	}

}
endif;

if ( !function_exists( 'themeblvd_block' ) ) :
/**
 * Display individual column block;
 *
 * @since 2.5.0
 *
 * @param array $blocks A set of content blocks
 */
function themeblvd_block( $id, $type, $options ) {

	$class = sprintf( 'content-block content-block-%s', $type );

	echo '<div class="'.$class.'">';
	echo '<div class="content-block-inner">';
	echo '<div class="content-block-inner-wrap">';

	switch ( $type ) {

		case 'content' :
			themeblvd_content_block( $options );
			break;

		case 'alert' :
			themeblvd_alert( $options );
			break;

		case 'chart_bar' :
			themeblvd_chart( 'bar', $options );
			break;

		case 'chart_line' :
			themeblvd_chart( 'line', $options );
			break;

		case 'chart_pie' :
			themeblvd_chart( 'pie', $options );
			break;

		case 'contact' :
			themeblvd_contact_bar( $options['buttons'], $options );
			break;

		case 'current' :
			themeblvd_page_content();
			break;

		case 'divider' :
			echo themeblvd_divider( $options );
			break;

		case 'html' :
			echo stripslashes( $options['html'] );
			break;

		case 'icon_box' :
			themeblvd_icon_box( $options );
			break;

		case 'image' :
			themeblvd_image( $options );
			break;

		case 'jumbotron' :
			themeblvd_jumbotron( $options );
			break;

		case 'milestone' :
			themeblvd_milestone( $options );
			break;

		case 'milestone_ring' :
			themeblvd_milestone_ring( $options );
			break;

		case 'page' :
			themeblvd_page_content( $options['page'] );
			break;

		case 'panel' :
			themeblvd_panel( $options );
			break;

		case 'partners' :
			themeblvd_logos( $options );
			break;

		case 'post_grid' :
			themeblvd_posts( $options, 'grid' );
			break;

		case 'post_grid_paginated' :
			themeblvd_posts_paginated( $options, 'grid' );
			break;

		case 'post_grid_slider' :
			themeblvd_post_slider( $id, $options, 'grid' );
			break;

		case 'post_list' :
			themeblvd_posts( $options, 'list' );
			break;

		case 'post_list_paginated' :
			themeblvd_posts_paginated( $options, 'list' );
			break;

		case 'post_list_slider' :
			themeblvd_post_slider( $id, $options, 'grid' );
			break;

		case 'progress_bars' :
			themeblvd_progress_bars( $options );
			break;

		case 'quote' :
			themeblvd_blockquote( $options );
			break;

		case 'raw' :
			if ( $options['raw_format'] ) {
				themeblvd_content( $options['raw'] );
			} else {
				echo do_shortcode( stripslashes( $options['raw'] ) );
			}
			break;

		case 'simple_slider' :
			themeblvd_simple_slider( $options );
			break;

		case 'slogan' :
			echo themeblvd_slogan( $options );
			break;

		case 'tabs' :
			echo themeblvd_tabs( $id, $options );
			break;

		case 'team_member' :
			echo themeblvd_team_member( $options );
			break;

		case 'testimonial' :
			echo themeblvd_testimonial( $options );
			break;

		case 'testimonial_slider' :
			echo themeblvd_testimonial_slider( $options );
			break;

		case 'toggles' :
			echo themeblvd_toggles( $id, $options );
			break;

		case 'video' :
			themeblvd_video( $options['video'] );
			break;

		case 'widget' :
			echo '<div class="widget-area">';
			dynamic_sidebar( $options['sidebar'] );
			echo '</div><!-- .widget-area (end) -->';
			break;

	}

	// Allow to add on custom content block that's
	// not in the framework
	do_action( 'themeblvd_block_'.$type, $id, $options );

	echo '</div><!-- .content-block-inner-wrap (end) -->';
	echo '</div><!-- .content-block-inner (end) -->';
	echo '</div><!-- .content-block (end) -->';

}
endif;

if ( !function_exists( 'themeblvd_columns' ) ) :
/**
 * Dislay set of columns.
 *
 * @since 2.5.0
 *
 * @param array $args
 * @param array Optionally force-feed column data
 */
function themeblvd_columns( $args, $columns = null ) {

	$defaults = array(
		'layout_id'		=> 0,
		'element_id'	=> 'element_',
		'num'			=> 1,
		'widths'		=> 'grid_12',
		'height'		=> 0,
		'align'			=> 'top'
	);
	$args = wp_parse_args( $args, $defaults );

	// Number of columns
	$num = intval( $args['num'] );

	// Bootstrap stack point
	$stack = apply_filters('themeblvd_columns_stack', 'sm');

	// Kill it if number of columns doesn't match the
	// number of widths exploded from the string.
	$widths = explode( '-', $args['widths'] );
	if ( $num != count( $widths ) ) {
		return;
	}

	// Column margins
	$margin_left = '-15px';
	$margin_right = '-15px';

	for ( $i = 1; $i <= $num; $i++ ) {

		// If first or last
		if ( $i == 1 || $i == $num ) {

			$column = get_post_meta( $args['layout_id'], $args['element_id'].'_col_'.strval($i), true );

			if ( ! empty( $column['display']['bg_type'] ) ) {
				if ( in_array( $column['display']['bg_type'], array( 'color', 'image', 'texture' ) ) ) {

					if ( $i == 1 ) {
						$margin_left = '0';
					} else if ( $i == $num ) {
						$margin_right = '0';
					}

				}
			}
		}
	}

	$margin = sprintf( 'margin: 0 %s 0 %s;', $margin_right, $margin_left );

	// Open column row
	if ( $args['height'] && $args['layout_id'] != 0 && ! $columns ) {
		printf( '<div class="container-%s-height">', $stack );
		themeblvd_open_row("row row-{$stack}-height", $margin);
	} else {
		themeblvd_open_row('row', $margin);
	}

	// Display columns
	for ( $i = 1; $i <= $num; $i++ ) {

		$grid_class = themeblvd_grid_class( $widths[$i-1], $stack );

		if ( $args['layout_id'] == 0 && $columns ) {

			echo '<div class="col '.$grid_class.'">';

			if ( isset( $columns[$i] ) ) {

				// Display individual content block for
				// the column of passed in data.
				themeblvd_block( uniqid('block_'), $columns[$i]['type'], $columns[$i] );

			}

			echo '</div><!-- .'.$grid_class.' (end) -->';

		} else {

			$blocks = array();
			$display = array();
			$column = get_post_meta( $args['layout_id'], $args['element_id'].'_col_'.strval($i), true );

			// Display options
			if ( ! empty( $column['display'] ) ) {
				$display = $column['display'];
			}

			// Equal height columns?
			if ( $args['height'] ) {

				$grid_class .= " col-{$stack}-height";

				if ( in_array( $args['align'], array( 'top', 'middle', 'bottom' ) ) ) {
					$grid_class .= ' col-'.$args['align'];
				}
			}

			// Start column
			printf('<div class="col %s %s" style="%s" data-parallax="%s">', $grid_class, themeblvd_get_display_class($display), themeblvd_get_display_inline_style($display), themeblvd_get_parallax_intensity($display) );

			// Content blocks
			if ( ! empty( $column['blocks'] ) ) {
				$blocks = $column['blocks'];
			}

			themeblvd_blocks( $blocks );

			echo '</div><!-- .'.$grid_class.' (end) -->';

		}

	}

	themeblvd_close_row();

	if ( $args['height'] ) {
		echo '</div><!-- .container-{$stack}-height (end) -->';
	}
}
endif;

if ( !function_exists( 'themeblvd_page_content' ) ) :
/**
 * Display content of current page, or inputted page slug or ID.
 *
 * @since 2.5.0
 *
 * @param int|string $page Page ID or slug to pull content from
 */
function themeblvd_page_content( $page = 0 ) {

	$page_id = 0;
	$current_page = false;

	// If no ID, get current page's ID
	if ( ! $page ) {
		$current_page = true;
		$page_id = themeblvd_config('id');
	}

	// Page slug?
	if ( is_string( $page ) ) {
		$page_id = themeblvd_post_id_by_name( $page, 'page' );
	}

	$get_page = get_post( $page_id );

	if ( $get_page ) {
		if ( $current_page ) {
			echo apply_filters( 'the_content', $get_page->post_content );
		} else {
			themeblvd_content( $get_page->post_content );
		}
	}

}
endif;

/**
 * Take in some content and return it with formatting.
 *
 * @since 2.5.0
 *
 * @param array $content Content to display
 * @return string Formatted content
 */
function themeblvd_get_content( $content ) {
	return apply_filters( 'themeblvd_the_content', stripslashes( $content ) );
}

/**
 * Take in some content and display it with formatting.
 *
 * @since 2.5.0
 *
 * @param array $content Content to display
 * @return string Formatted content
 */
function themeblvd_content( $content ) {
	echo themeblvd_get_content( $content );
}