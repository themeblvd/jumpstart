<?php
if ( !function_exists( 'themeblvd_content_blocks' ) ) :
/**
 * Get column of content blocks.
 *
 * @since 2.5.0
 *
 * @param array $blocks A set of content blocks
 */
function themeblvd_content_blocks( $layout_id, $element_id, $col = 1 ) {

	$blocks = get_post_meta( $layout_id, $element_id.'_col_'.strval($col), true );

	if ( $blocks && is_array($blocks) && count($blocks) >= 1 ) {
		foreach ( $blocks as $id => $block ) {

			$type = '';
			if ( isset( $block['type'] ) ) {
				$type = $block['type'];
			}

			$options = array();
			if ( isset( $block['options'] ) ) {
				$options = $block['options'];
			}

			themeblvd_content_block( $id, $type, $options );
		}
	}

}
endif;

if ( !function_exists( 'themeblvd_content_block' ) ) :
/**
 * Display individual column block;
 *
 * @since 2.5.0
 *
 * @param array $blocks A set of content blocks
 */
function themeblvd_content_block( $id, $type, $options ) {

	$class = sprintf( 'content-block content-block-%s', $type );

	echo '<div class="'.$class.'">';
	echo '<div class="content-block-inner">';
	echo '<div class="content-block-inner-wrap">';

	switch ( $type ) {

		case 'content' :
			themeblvd_content( $options['content'] );
			break;

		case 'current' :
			themeblvd_page_content();
			break;

		case 'html' :
			echo stripslashes( $options['html'] );
			break;

		case 'image' :
			themeblvd_image( $options );
			break;

		case 'page' :
			themeblvd_page_content( $options['page'] );
			break;

		case 'panel' :
			themeblvd_panel( $options );
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

		case 'video' :
			themeblvd_video( $options['video'] );
			break;

		case 'widget' :
			echo '<div class="widget-area">';
			dynamic_sidebar( $options['sidebar'] );
			echo '</div><!-- .widget-area (end) -->';
			break;

	}

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
 */
function themeblvd_columns( $args, $columns = null ) {

	$defaults = array(
		'layout_id'		=> 0,
		'element_id'	=> 'element_',
		'num'			=> 1,
		'widths'		=> 'grid_12'
	);
	$args = wp_parse_args( $args, $defaults );

	// Number of columns
	$num = intval( $args['num'] );

	// Kill it if number of columns doesn't match the
	// number of widths exploded from the string.
	$widths = explode( '-', $args['widths'] );
	if ( $num != count( $widths ) ) {
		return;
	}

	echo '<div class="row">';

	for ( $i = 1; $i <= $num; $i++ ) {

		$class = themeblvd_get_grid_class( $widths[$i-1] );

		echo '<div class="'.$class.'">';

		if ( $args['layout_id'] == 0 && $columns ) {

			if ( isset( $columns[$i] ) ) {

				// Display individual content block for
				// the column of passed in data.
				themeblvd_content_block( uniqid('block_'), $columns[$i]['type'], $columns[$i] );

			}

		} else {

			// Get content blocks from stored meta data
			// for individual column of multiple blocks.
			themeblvd_content_blocks( $args['layout_id'], $args['element_id'], $i );

		}

		echo '</div><!-- .column (end) -->';

	}

	echo '</div><!-- .row (end) -->';
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
 * Take in some content and display it with formatting.
 *
 * @since 2.0.0
 *
 * @param array $content Content to display
 * @return string Formatted content
 */
function themeblvd_get_content( $content ) {
	return apply_filters( 'themeblvd_the_content', stripslashes( $content ) );
}
function themeblvd_content( $content ) {
	echo themeblvd_get_content( $content );
}
