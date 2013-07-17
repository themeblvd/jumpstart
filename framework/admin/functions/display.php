<?php
/**
 * Generates a table for a custom post type.
 *
 * @since 2.0.0
 *
 * @param $post_type string post type id
 * @param $columns array columns for table
 * @return $output string HTML output for table
 */
function themeblvd_post_table( $post_type, $columns ) {

	// Grab some details for post type
	$post_type_object = get_post_type_object($post_type);
	$name = $post_type_object->labels->name;
	$singular_name = $post_type_object->labels->singular_name;

	// Get posts
	$posts = get_posts( array( 'post_type' => $post_type, 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );

	// Get conflicts if this is a sidebar table
	if ( $post_type == 'tb_sidebar' ) {
		$conflicts = themeblvd_get_assignment_conflicts( $posts );
	}

	// Setup header/footer
	$header = '<tr>';
	$header .= '<th scope="col" id="cb" class="manage-column column-cb check-column"><input type="checkbox"></th>';
	foreach ( $columns as $column ) {
		$header .= '<th class="head-'.$column['type'].'">'.$column['name'].'</th>';
	}
	$header .= '</tr>';

	// Start main output
	$output  = '<table class="widefat">';
	$output .= '<div class="tablenav top">';
	$output .= '<div class="alignleft actions">';
	$output .= '<select name="action">';
	$output .= '<option value="-1" selected="selected">'.__( 'Bulk Actions', 'themeblvd' ).'</option>';
	$output .= '<option value="trash">'.__( 'Delete', 'themeblvd' ).' '.esc_attr($name).'</option>';
	$output .= '</select>';
	$output .= '<input type="submit" id="doaction" class="button-secondary action" value="'.__( 'Apply', 'themeblvd' ).'">';
	$output .= '</div>';
	$output .= '<div class="alignright tablenav-pages">';
	$output .= '<span class="displaying-num">'.sprintf( _n( '1 '.$singular_name, '%s '.$name, count($posts) ), number_format_i18n( count($posts) ) ).'</span>';
	$output .= '</div>';
	$output .= '<div class="clear"></div>';
	$output .= '</div>';

	// Table header
	$output .= '<thead>';
	$output .= $header;
	$output .= '</thead>';

	// Table footer
	$output .= '<tfoot>';
	$output .= $header;
	$output .= '</tfoot>';

	// Table body
	$output .= '<tbody>';
	if ( ! empty( $posts ) ) {
		foreach ( $posts as $post ) {
			$output .= '<tr id="row-'.$post->ID.'">';
			$output .= '<th scope="row" class="check-column"><input type="checkbox" name="posts[]" value="'.$post->ID.'"></th>';
			foreach ( $columns as $column ) {
				switch ( $column['type'] ) {

					case 'title' :
						$output .= '<td class="post-title page-title column-title">';
						$output .= '<strong><a href="#'.esc_attr($post->ID).'" class="title-link edit-'.$post_type.'" title="'.__( 'Edit', 'themeblvd' ).'">'.stripslashes(esc_html($post->post_title)).'</strong></a>';
						$output .= '<div class="row-actions">';
						$output .= '<span class="edit">';
						$output .= '<a href="#'.esc_attr($post->ID).'" class="edit-post edit-'.$post_type.'" title="'.__( 'Edit', 'themeblvd' ).'">'.__( 'Edit', 'themeblvd' ).'</a> | ';
						$output .= '</span>';
						$output .= '<span class="trash">';
						$output .= '<a title="'.__( 'Delete', 'themeblvd' ).'" href="#'.esc_attr($post->ID).'">'.__( 'Delete', 'themeblvd' ).'</a>';
						$output .= '</span>';
						$output .= '</div>';
						break;

					case 'id' :
						$output .= '<td class="post-id">';
						$output .= esc_html($post->ID);
						break;

					case 'slug' :
						$output .= '<td class="post-slug">';
						$output .= esc_html($post->post_name);
						break;

					case 'meta' :
						$output .= '<td class="post-meta-'.$column['config'].'">';
						$meta = get_post_meta( $post->ID, $column['config'], true );

						if ( isset( $column['inner'] ) ) {

							if ( isset( $meta[$column['inner']] ) ) {
								$output .= esc_html($meta[$column['inner']]);
							}

						} else {
							$output .= esc_html($meta);
						}
						break;

					case 'shortcode' :
						$output .= '<td class="post-shortcode-'.$column['config'].'">';
						$output .= '['.$column['config'].' id="'.$post->post_name.'"]';
						break;

					case 'assignments' :
						$output .= '<td class="post-assignments">';
						$location = get_post_meta( $post->ID, 'location', true );
						if ( $location && $location != 'floating' ) {

							$assignments = get_post_meta( $post->ID, 'assignments', true );

							if ( is_array( $assignments ) && ! empty( $assignments ) ) {

								$output .= '<ul>';

								foreach ( $assignments as $key => $assignment ) {

									$class = 'no-conflict';
									if ( in_array( $key, $conflicts[$location] ) ) {
										$class = 'conflict';
									}

									if ( $assignment['type'] == 'top' ) {
										$output .= '<li class="'.$class.'">'.esc_html( $assignment['name'] ).'</li>';
									} elseif ( $assignment['type'] == 'custom' ) {
										$output .= '<li class="'.$class.'">'.ucfirst( esc_html( $assignment['type'] ) ).': <code>'.esc_html( $assignment['name'] ).'</code></li>';
									} else {
										$output .= '<li class="'.$class.'">'.ucfirst( esc_html( $assignment['type'] ) ).': '.esc_html( $assignment['name'] ).'</li>';
									}
								}

								$output .= '</ul>';

							} else {
								$output .= '<span class="inactive">'.__( 'No Assignments', 'themeblvd' ).'</span>';
							}
						} else {
							$output .= '<span class="inactive">[floating]</span>';
						}
						break;

					case 'sidebar_location' :
						$output .= '<td class="sidebar-location">';
						$output .= themeblvd_get_sidebar_location_name( esc_html( get_post_meta( $post->ID, 'location', true ) ) );
						break;
				}
				$output .= '</td>';
			}
			$output .= '</tr>';
		}
	} else {
		$num = count( $columns ) + 1; // number of columns + the checkbox column
		$output .= '<tr><td colspan="'.$num.'">'.__('No items have been created yet. Click the Add tab above to get started.', 'themeblvd').'</td></tr>';
	}
	$output .= '</tbody>';
	$output .= '</table>';
	return $output;
}

/**
 * Generates option for configuring columns.
 *
 * This has been moved to a separate function
 * because it's a custom addition to the optionframework
 * module and it's pretty lengthy.
 *
 * @since 2.0.0
 *
 * @param $type string type of use, standard or element
 * @param $id string unique ID for option
 * @param $name string prefix for form name value
 * @param $val array currently saved data if exists
 * @return $output string HTML for option
 */
function themeblvd_columns_option( $type, $id, $name, $val ) {

	/*------------------------------------------------------*/
	/* Setup Internal Options
	/*------------------------------------------------------*/

	// Dropdown for number of columns selection
	$data_num = array (
		array(
			'name' 	=> __( 'Hide Columns', 'themeblvd' ),
			'value' => 0,
		),
		array(
			'name' 	=> '1 '.__( 'Column', 'themeblvd' ),
			'value' => 1,
		),
		array(
			'name' 	=> '2 '.__( 'Columns', 'themeblvd' ),
			'value' => 2,
		),
		array(
			'name' 	=> '3 '.__( 'Columns', 'themeblvd' ),
			'value' => 3,
		),
		array(
			'name' 	=> '4 '.__( 'Columns', 'themeblvd' ),
			'value' => 4,
		),
		array(
			'name' 	=> '5 '.__( 'Columns', 'themeblvd' ),
			'value' => 5,
		)
	);

	// Dropdowns for column width configuration
	$data_widths = themeblvd_column_widths();

	/*------------------------------------------------------*/
	/* Construct <select> Menus
	/*------------------------------------------------------*/

	// Number of columns
	if ( $type == 'element' ) {
		unset( $data_num[0] );
	}

	// Select number of columns
	$select_number  = '<div class="tb-fancy-select">';
	$select_number .= '<select class="column-num" name="'.esc_attr( $name.'['.$id.'][num]' ).'">';

	$current_value = '';
	if ( ! empty( $val ) && ! empty( $val['num'] ) ) {
		$current_value = $val['num'];
	}

	foreach ( $data_num as $num ) {
		$select_number .= '<option value="'.$num['value'].'" '.selected( $current_value, $num['value'], false ).'>'.$num['name'].'</option>';
	}

	$select_number .= '</select>';
	$select_number .= '<span class="trigger"></span>';
	$select_number .= '<span class="textbox"></span>';
	$select_number .= '</div><!-- .tb-fancy-select (end) -->';

	// Select column widths
	$i = 1;
	$select_widths = '<div class="column-width column-width-0"><p class="inactive">'.__( 'Columns will be hidden.', 'themeblvd' ).'</p></div>';
	foreach ( $data_widths as $widths ) {

		$select_widths .= '<div class="tb-fancy-select column-width column-width-'.$i.'">';
		$select_widths .= '<select name= "'.esc_attr( $name.'['.$id.'][width]['.$i.']' ).'">';

		$current_value = '';
		if ( ! empty( $val ) && ! empty( $val['width'][$i] ) ) {
			$current_value = $val['width'][$i];
		}

		foreach ( $widths as $width ) {
			$select_widths .= '<option value="'.$width['value'].'" '.selected( $current_value, $width['value'], false ).'>'.$width['name'].'</option>';
		}

		$select_widths .= '</select>';
		$select_widths .= '<span class="trigger"></span>';
		$select_widths .= '<span class="textbox"></span>';
		$select_widths .= '</div><!-- .tb-fancy-select (end) -->';
		$i++;
	}

	/*------------------------------------------------------*/
	/* Primary Output
	/*------------------------------------------------------*/

	$output  = sprintf( '<div class="select-wrap alignleft">%s</div>', $select_number );
	$output .= sprintf( '<div class="select-wrap alignleft">%s</div>', $select_widths );
	$output .= '<div class="clear"></div>';

	return $output;
}

/**
 * Generates option for configuring tabs.
 *
 * This has been moved to a separate function
 * because it's a custom addition to the optionframework
 * module and it's pretty lengthy.
 *
 * @since 2.0.0
 *
 * @param $id string unique ID for option
 * @param $name string prefix for form name value
 * @param $val array currently saved data if exists
 * @return $output string HTML for option
 */
function themeblvd_tabs_option( $id, $name, $val ) {

	/*------------------------------------------------------*/
	/* Build <select> for number of tabs
	/*------------------------------------------------------*/

	$numbers = array (
		array(
			'name' 	=> '2 '.__( 'Tabs', 'themeblvd' ),
			'value' => 2,
		),
		array(
			'name' 	=> '3 '.__( 'Tabs', 'themeblvd' ),
			'value' => 3,
		),
		array(
			'name' 	=> '4 '.__( 'Tabs', 'themeblvd' ),
			'value' => 4,
		),
		array(
			'name' 	=> '5 '.__( 'Tabs', 'themeblvd' ),
			'value' => 5,
		),
		array(
			'name' 	=> '6 '.__( 'Tabs', 'themeblvd' ),
			'value' => 6,
		),
		array(
			'name' 	=> '7 '.__( 'Tabs', 'themeblvd' ),
			'value' => 7,
		),
		array(
			'name' 	=> '8 '.__( 'Tabs', 'themeblvd' ),
			'value' => 8,
		),
		array(
			'name' 	=> '9 '.__( 'Tabs', 'themeblvd' ),
			'value' => 9,
		),
		array(
			'name' 	=> '10 '.__( 'Tabs', 'themeblvd' ),
			'value' => 10,
		),
		array(
			'name' 	=> '11 '.__( 'Tabs', 'themeblvd' ),
			'value' => 11,
		),
		array(
			'name' 	=> '12 '.__( 'Tabs', 'themeblvd' ),
			'value' => 12,
		)
	);
	$current_value = ! empty( $val ) && ! empty( $val['num'] ) ? $val['num'] : null;
	$select_number  = '<div class="tb-fancy-select">';
	$select_number .= '<select class="tabs-num" name="'.esc_attr( $name.'['.$id.'][num]' ).'">';
	foreach ( $numbers as $num ) {
		$select_number .= '<option value="'.$num['value'].'" '.selected( $current_value, $num['value'], false ).'>'.$num['name'].'</option>';
	}
	$select_number .= '</select>';
	$select_number .= '<span class="trigger"></span>';
	$select_number .= '<span class="textbox"></span>';
	$select_number .= '</div><!-- .tb-fancy-select (end) -->';

	/*------------------------------------------------------*/
	/* Build <select> for style of tabs
	/*------------------------------------------------------*/

	$current_value = ! empty( $val ) && ! empty( $val['style'] ) ? $val['style'] : null;
	$select_style  = '<div class="tb-fancy-select">';
	$select_style .= '<select class="tabs-style" name="'.esc_attr( $name.'['.$id.'][style]' ).'">';
	$select_style .= '<option value="open" '.selected( $current_value, 'open', false ).'>'.__( 'Open Style', 'themeblvd' ).'</option>';
	$select_style .= '<option value="framed" '.selected( $current_value, 'framed', false ).'>'.__( 'Framed Style', 'themeblvd' ).'</option>';
	$select_style .= '</select>';
	$select_style .= '<span class="trigger"></span>';
	$select_style .= '<span class="textbox"></span>';
	$select_style .= '</div><!-- .tb-fancy-select (end) -->';

	/*------------------------------------------------------*/
	/* Build <select> for nav of tabs
	/*------------------------------------------------------*/

	$current_value = ! empty( $val ) && ! empty( $val['nav'] ) ? $val['nav'] : null;
	$select_nav  = '<div class="tb-fancy-select">';
	$select_nav .= '<select class="tabs-nav" name="'.esc_attr( $name.'['.$id.'][nav]' ).'">';
	$select_nav .= '<option value="tabs_above" '.selected( $current_value, 'tabs_above', false ).'>'.__( 'Tabs on Top', 'themeblvd' ).'</option>';
	$select_nav .= '<option value="tabs_right" '.selected( $current_value, 'tabs_right', false ).'>'.__( 'Tabs on Right', 'themeblvd' ).'</option>';
	$select_nav .= '<option value="tabs_below" '.selected( $current_value, 'tabs_below', false ).'>'.__( 'Tabs on Bottom', 'themeblvd' ).'</option>';
	$select_nav .= '<option value="tabs_left" '.selected( $current_value, 'tabs_left', false ).'>'.__( 'Tabs on Left', 'themeblvd' ).'</option>';
	$select_nav .= '<option value="pills_above" '.selected( $current_value, 'pills_above', false ).'>'.__( 'Pills on Top', 'themeblvd' ).'</option>';
	$select_nav .= '<option value="pills_below" '.selected( $current_value, 'pills_below', false ).'>'.__( 'Pills on Bottom', 'themeblvd' ).'</option>';
	$select_nav .= '</select>';
	$select_nav .= '<span class="trigger"></span>';
	$select_nav .= '<span class="textbox"></span>';
	$select_nav .= '</div><!-- .tb-fancy-select (end) -->';

	/*------------------------------------------------------*/
	/* Add in text fields for names of tabs
	/*------------------------------------------------------*/

	// Add 1st tab
	array_unshift( $numbers, array( 'value' => 1 ) );
	$input_names = null;
	foreach ( $numbers as $number ) {
		// Default value
		$current_value = ! empty( $val ) && ! empty( $val['names']['tab_'.$number['value']] ) ? $val['names']['tab_'.$number['value']] : null;
		// Output
		$input_names .= '<div class="tab-name tab-name-'.$number['value'].'">';
		$input_names .= '<label for="tab-name-input='.$number['value'].'">'.sprintf( __('Tab #%d Name', 'themeblvd'), $number['value'] ).'</label>';
		$input_names .= '<input id="tab-name-input='.$number['value'].'" type="text" name="'.esc_attr( $name.'['.$id.'][names][tab_'.$number['value'].']' ).'" value ="'.stripslashes(esc_attr($current_value)).'" />';
		$input_names .= '<div class="clear"></div>';
		$input_names .= '</div>';
	}

	/*------------------------------------------------------*/
	/* Primary Output
	/*------------------------------------------------------*/

	$output = '<div class="select-tab-num">';
	$output .= $select_number;
	$output .= '</div>';
	$output .= '<div class="select-tab-style">';
	$output .= $select_style;
	$output .= '</div>';
	$output .= '<div class="select-tab-nav">';
	$output .= $select_nav;
	$output .= '</div>';
	$output .= '<div class="tab-names">';
	$output .= $input_names;
	$output .= '</div>';

	return $output;

}

/**
 * Generates option for an individual block of content.
 * This was mainly designed to be used within setting
 * up columns or tabs.
 *
 * This has been moved to a separate function
 * because it's a custom addition to the optionframework
 * module and it's pretty lengthy.
 *
 * @since 2.0.0
 *
 * @param $id string unique ID for option
 * @param $name string prefix for form name value
 * @param $val array currently saved data if exists
 * @param $options array content sources to choose from
 * @return $output string HTML for option
 */
function themeblvd_content_option( $id, $name, $val, $options ) {

	/*------------------------------------------------------*/
	/* Build <select> for type of content
	/*------------------------------------------------------*/

	// Setup content types to choose from
	$sources = array(
		'null' 		=> __( '- Select Content Type -', 'themeblvd' ),
	);

	if ( in_array ( 'widget', $options ) ) {
		$sources['widget'] = __( 'Floating Widget Area', 'themeblvd' );
	}

	if ( in_array ( 'current', $options ) ) {
		$sources['current'] = __( 'Content of Current Page', 'themeblvd' );
	}

	if ( in_array ( 'page', $options ) ) {
		$sources['page'] = __( 'Content of External Page', 'themeblvd' );
	}

	if ( in_array ( 'raw', $options ) ) {
		$sources['raw'] = __( 'Raw Content', 'themeblvd' );
	}

	// Set default value
	$current_value = '';
	if ( ! empty( $val ) && ! empty( $val['type'] ) ) {
		$current_value = $val['type'];
	}

	// Build <select>
	$select_type  = '<div class="tb-fancy-select">';
	$select_type .= '<select class="select-type" name= "'.esc_attr( $name.'['.$id.'][type]' ).'">';
	foreach ( $sources as $key => $value ) {
		$select_type .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $current_value, $key, false ), $value );
	}
	$select_type .= '</select>';
	$select_type .= '<span class="trigger"></span>';
	$select_type .= '<span class="textbox"></span>';
	$select_type .= '</div><!-- .tb-fancy-select (end) -->';

	/*------------------------------------------------------*/
	/* Build <select> for widget area
	/*------------------------------------------------------*/

	if ( in_array ( 'widget', $options ) ) {

		// The selection of a floating widget area is only
		// possible if the Widget Areas plugin is installed.
		if ( ! defined( 'TB_SIDEBARS_PLUGIN_VERSION' ) ) {

			// Message to get plugin
			$select_sidebar = '<p class="warning">'.sprintf(__( 'In order for you to use this feature you need to have the %s plugin activated.', 'themeblvd' ), '<a href="http://wordpress.org/extend/plugins/theme-blvd-widget-areas/" target="_blank">Theme Blvd Widget Areas</a>').'</p>';

		} else {

			$sidebars = array();

			// Set default value
			$current_value = ! empty( $val ) && ! empty( $val['sidebar'] ) ? $val['sidebar'] : null;

			// Get all custom sidebars from custom post type
			$sidebars = themeblvd_get_select( 'sidebars' );

			// Build <select>
			if ( ! empty( $sidebars ) ) {
				$select_sidebar  = '<div class="tb-fancy-select">';
				$select_sidebar .= '<select class="select-sidebar" name= "'.esc_attr( $name.'['.$id.'][sidebar]' ).'">';
				foreach ( $sidebars as $key => $value ) {
					$select_sidebar .= sprintf('<option value="%s" %s>%s</option>', $key, selected( $current_value, $key, false ), $value );
				}
				$select_sidebar .= '</select>';
				$select_sidebar .= '<span class="trigger"></span>';
				$select_sidebar .= '<span class="textbox"></span>';
				$select_sidebar .= '</div><!-- .tb-fancy-select (end) -->';
			} else {
				$select_sidebar = '<p class="warning">'.__( 'You haven\'t created any floating widget areas.', 'themeblvd' ).'</p>';
			}
		}

	}

	/*------------------------------------------------------*/
	/* Build <select> for external page
	/*------------------------------------------------------*/

	if ( in_array ( 'page', $options ) ) {

		// Set default value
		$current_value = '';
		if ( ! empty( $val ) && ! empty( $val['page'] ) ) {
			$current_value = $val['page'];
		}

		// Get all pages from WP database
		$pages = themeblvd_get_select( 'pages' );

		// Build <select>
		if ( ! empty( $pages ) ) {
			$select_page  = '<div class="tb-fancy-select">';
			$select_page .= '<select name= "'.esc_attr( $name.'['.$id.'][page]' ).'">';
			foreach ( $pages as $key => $value ) {
				$select_page .= sprintf('<option value="%s" %s>%s</option>', $key, selected( $current_value, $key, false ), $value );
			}
			$select_page .= '</select>';
			$select_page .= '<span class="trigger"></span>';
			$select_page .= '<span class="textbox"></span>';
			$select_page .= '</div><!-- .tb-fancy-select (end) -->';
		} else {
			$select_page = '<p class="warning">'.__( 'You haven\'t created any pages.', 'themeblvd' ).'</p>';
		}

	}

	/*------------------------------------------------------*/
	/* Build raw content input
	/*------------------------------------------------------*/

	if ( in_array ( 'raw', $options ) ) {

		// Set default value
		$current_value = '';
		if ( ! empty( $val ) && ! empty( $val['raw'] ) ) {
			$current_value = $val['raw'];
		}

		// Text area
		$raw_content = sprintf( '<textarea name="%s" class="of-input" cols="8" rows="8">%s</textarea>', esc_attr( $name.'['.$id.'][raw]' ), stripslashes( esc_textarea( $current_value ) ) );

		// Checkbox for the_content filter (added in v2.0.6)
		// Should be checked if selected OR option never existed.
		// This is for legacy purposes.
		$checked = 'checked';
		if ( isset( $val['raw_format'] ) && ! $val['raw_format'] ) {
			$checked = '';
		}

		$raw_content .= sprintf( '<input class="checkbox of-input" type="checkbox" name="%s" %s>', esc_attr( $name.'['.$id.'][raw_format]' ), $checked );
		$raw_content .= __( 'Apply WordPress automatic formatting.', 'themeblvd' );
	}

	/*------------------------------------------------------*/
	/* Primary Output
	/*------------------------------------------------------*/

	$output = '<div class="column-content-types">';
	$output .= $select_type;

	if ( in_array ( 'widget', $options ) ) {
		$output .= '<div class="column-content-type column-content-type-widget">';
		$output .= $select_sidebar;
		$output .= '<p class="note">'.__( 'Select from your floating custom widget areas. In order for a custom widget area to be "floating" you must have it configured this way in the Widget Area manager.', 'themeblvd' ).'</p>';
		$output .= '</div>';
	}

	if ( in_array ( 'page', $options ) ) {
		$output .= '<div class="column-content-type column-content-type-page">';
		$output .= $select_page;
		$output .= '<p class="note">'.__( 'Select an external page to pull content from.', 'themeblvd' ).'</p>';
		$output .= '</div>';
	}

	if ( in_array ( 'raw', $options ) ) {
		$output .= '<div class="column-content-type column-content-type-raw">';
		$output .= $raw_content;
		$output .= '<p class="note">'.__( 'You can use basic HTML here, and most shortcodes.', 'themeblvd' ).'</p>';
		$output .= '</div>';
	}

	$output .= '</div><!-- .column-content-types (end) -->';

	return $output;
}

/**
 * Create accordion panel for selecting conditional
 * assignments.
 *
 * @since 2.0.0
 *
 * @param $id string unique ID for option
 * @param $name string prefix for form name value
 * @param $val array currently saved data if exists
 * @return $output string HTML for option
 */
function themeblvd_conditionals_option( $id, $name, $val = null ) {

	// Create array of all assignments seperated
	// by type to check against when displaying them
	// back to the user.
	$assignments = array(
		'pages' 			=> array(),
		'posts' 			=> array(),
		'posts_in_category' => array(),
		'categories' 		=> array(),
		'tags' 				=> array(),
		'top' 				=> array(),
		'custom'			=> ''
	);
	if ( is_array( $val ) && ! empty( $val ) ) {
		foreach ( $val as $key => $group ) {
			$item_id = $group['id'];
			switch ( $group['type'] ) {
				case 'page' :
					$assignments['pages'][] = $item_id;
					break;
				case 'post' :
					$assignments['posts'][] = $item_id;
					break;
				case 'posts_in_category' :
					$assignments['posts_in_category'][] = $item_id;
					break;
				case 'category' :
					$assignments['categories'][] = $item_id;
					break;
				case 'tag' :
					$assignments['tags'][] = $item_id;
					break;
				case 'top' :
					$assignments['top'][] = $item_id;
					break;
				case 'custom' :
					$assignments['custom'] = $item_id;
					break;
			}
		}
	}

	// Grab all conditionals to choose from
	$conditionals = themeblvd_conditionals_config();

	// Start output
	$output = '<div class="accordion">';

	// Display each accordion element
	foreach ( $conditionals as $conditional ) {

		$output .= '<div class="element">';
		$output .= '<a href="#" class="element-trigger">'.$conditional['name'].'</a>';
		$output .= '<div class="element-content">';

		switch ( $conditional['id'] ) {

			// Pages
			case 'pages' :
				$pages = get_pages( array( 'hierarchical' => false ) );
				if ( ! empty( $pages ) ) {
					$output .= '<ul>';
					foreach ( $pages as $page ) {

						$checked = false;
						if ( in_array( $page->post_name, $assignments['pages'] ) ) {
							$checked = true;
						}

						$output .= sprintf( '<li><input type="checkbox" %s name="%s" value="%s" /> <span>%s</span></li>', checked( $checked, true, false ), esc_attr( $name.'['.$id.'][page][]' ), $page->post_name, $page->post_title );
						$checked = false;
					}
					$output .= '</ul>';
				} else {
					$output .= sprintf( '<p class="warning">%s</p>', $conditional['empty'] );
				}
				break;

			// Posts
			case 'posts' :
				$assignment_list = '';

				if ( ! empty( $assignments['posts'] ) ) {
					$assignment_list = implode( ', ', $assignments['posts'] );
				}

				$output .= sprintf( '<textarea name="%s">%s</textarea>', esc_attr( $name.'['.$id.'][post]' ), $assignment_list );
				$output .= sprintf( '<p class="note">%s</p>', __( 'Enter in a comma-separated list of the post slugs you\'d like to add to the assignments.', 'themeblvd' ) );
				$output .= sprintf( '<p class="note"><em>%s</em></p>', __( 'Example: post-1, post-2, post-3', 'themeblvd' ) );
				$output .= sprintf( '<p class="note"><em>%s</em></p>', __( 'Note: Any post slugs entered that don\'t exist won\'t be saved.', 'themeblvd' ) );
				break;

			// Posts in Category
			case 'posts_in_category' :

				if ( isset( $GLOBALS['sitepress'] ) ) {

					// WPML compat
					global $sitepress;
					remove_filter('terms_clauses', array( $sitepress, 'terms_clauses' ));
					$categories = get_categories( array( 'hide_empty' => false ) );
					add_filter('terms_clauses', array( $sitepress, 'terms_clauses' ));

				} else {
					$categories = get_categories( array( 'hide_empty' => false ) );
				}

		        if ( ! empty( $categories ) ) {
		        	$output .= '<ul>';
		        	foreach ( $categories as $category ) {

		        		$checked = false;
		        		if ( in_array( $category->slug, $assignments['posts_in_category'] ) ) {
		        			$checked = true;
		        		}

						$output .= sprintf( '<li><input type="checkbox" %s name="%s" value="%s" /> <span>%s</span></li>', checked( $checked, true, false ), esc_attr( $name.'['.$id.'][posts_in_category][]' ), $category->slug, $category->name );
						$checked = false;
					}
					$output .= '</ul>';
				} else {
					$output .= sprintf( '<p class="warning">%s</p>', $conditional['empty'] );
				}
				break;

			// Category Archives
			case 'categories' :

				if ( isset( $GLOBALS['sitepress'] ) ) {

					// WPML compat
					global $sitepress;
					remove_filter('terms_clauses', array( $sitepress, 'terms_clauses' ));
					$categories = get_categories( array( 'hide_empty' => false ) );
					add_filter('terms_clauses', array( $sitepress, 'terms_clauses' ));

				} else {
					$categories = get_categories( array( 'hide_empty' => false ) );
				}

		        if ( ! empty( $categories ) ) {
		        	$output .= '<ul>';
		        	foreach ( $categories as $category ) {

		        		$checked = false;
		        		if ( in_array( $category->slug, $assignments['categories'] ) ) {
		        			$checked = true;
		        		}

						$output .= sprintf( '<li><input type="checkbox" %s name="%s" value="%s" /> <span>%s</span></li>', checked( $checked, true, false ), esc_attr( $name.'['.$id.'][category][]' ), $category->slug, $category->name );
						$checked = false;
					}
					$output .= '</ul>';
				} else {
					$output .= sprintf( '<p class="warning">%s</p>', $conditional['empty'] );
				}
				break;

			// Tag Archives
			case 'tags' :
				$assignment_list = '';

				if ( ! empty( $assignments['tags'] ) ) {
					$assignment_list = implode( ', ', $assignments['tags'] );
				}

				$output .= sprintf( '<textarea name="%s">%s</textarea>', esc_attr( $name.'['.$id.'][tag]' ), $assignment_list );
				$output .= sprintf( '<p class="note">%s</p>', __( 'Enter in a comma-separated list of the tags you\'d like to add to the assignments.', 'themeblvd' ) );
				$output .= sprintf( '<p class="note"><em>%s</em></p>', __( 'Example: tag-1, tag-2, tag-3', 'themeblvd' ) );
				$output .= sprintf( '<p class="note"><em>%s</em></p>', __( 'Note: Any tags entered that don\'t exist won\'t be saved.', 'themeblvd' ) );
				break;

			// Hierarchy
			case 'top' :

				if ( ! empty( $conditional['items'] ) ) {
					foreach ( $conditional['items'] as $item_id => $item_name ) {

						$checked = false;
						if ( in_array( $item_id, $assignments['top'] ) ) {
							$checked = true;
						}

						$output .= sprintf( '<li><input type="checkbox" %s name="%s" value="%s" /> <span>%s</span></li>', checked( $checked, true, false ), esc_attr( $name.'['.$id.'][top][]' ), $item_id, $item_name );
						$checked = false;
					}
				}
				break;

			// Custom
			case 'custom' :

				// If someone feels unsafe having this option which uses eval(),
				// they can disable it here.
				// NOTE: The actual call to eval() happens in the Theme Blvd Widget
				// Areas plugin, and not in the theme framework.
				$disable = apply_filters( 'themeblvd_disable_sidebar_custom_conditional', false );

				if ( ! $disable ) {
					$output .= sprintf( '<input type="text" name="%s" value="%s" />', esc_attr( $name.'['.$id.'][custom]' ), $assignments['custom'] );
					$output .= sprintf( '<p class="note">%s</p>', __( 'Enter in a custom <a href="http://codex.wordpress.org/Conditional_Tags" target="_blank">conditional statement</a>.', 'themeblvd' ) );
					$output .= sprintf( '<p class="note"><em>%s</em><br /><code>is_home()</code><br /><code>is_home() || is_single()</code><br /><code>"book" == get_post_type() || is_tax("author")</code></p>', __( 'Examples:', 'themeblvd' ) );
					$output .= sprintf( '<p class="note"><em>%s</em></p>', __( 'Warning: Make sure you know what you\'re doing here. If you enter invalid conditional functions, you will most likely get PHP errors on the frontend of your website.', 'themeblvd' ) );
				}
				break;

		}
		$output .= '<ul>';
		$output .= '</div><!-- .element-content (end) -->';
		$output .= '</div><!-- .element (end) -->';
	}
	$output .= '</div><!-- .accordion (end) -->';

	return $output;
}

/**
 * Generates option to edit a logo.
 *
 * This has been moved to a separate function
 * because it's a custom addition to the optionframework
 * module and it's pretty lengthy.
 *
 * @since 2.0.0
 *
 * @param $id string unique ID for option
 * @param $name string prefix for form name value
 * @param $val array currently saved data if exists
 * @return $output string HTML for option
 */
function themeblvd_logo_option( $id, $name, $val ) {

	/*------------------------------------------------------*/
	/* Type of logo
	/*------------------------------------------------------*/

	$types = array(
		'title' 		=> __( 'Site Title', 'themeblvd' ),
		'title_tagline' => __( 'Site Title + Tagline', 'themeblvd' ),
		'custom' 		=> __( 'Custom Text', 'themeblvd' ),
		'image' 		=> __( 'Image', 'themeblvd' )
	);

	$current_value = '';
	if ( ! empty( $val ) && ! empty( $val['type'] ) ) {
		$current_value = $val['type'];
	}

	$select_type  = '<div class="tb-fancy-select">';
	$select_type .= '<select name="'.esc_attr( $name.'['.$id.'][type]' ).'">';

	foreach ( $types as $key => $type ) {
		$select_type .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $current_value, $key, false ), $type );
	}

	$select_type .= '</select>';
	$select_type .= '<span class="trigger"></span>';
	$select_type .= '<span class="textbox"></span>';
	$select_type .= '</div><!-- .tb-fancy-select (end) -->';

	/*------------------------------------------------------*/
	/* Site Title
	/*------------------------------------------------------*/

	$site_title  = '<p class="note">';
	$site_title .= __( 'Current Site Title', 'themeblvd' ).': <strong>';
	$site_title .= get_bloginfo( 'name' ).'</strong><br><br>';
	$site_title .= __( 'You can change your site title and tagline by going <a href="options-general.php" target="_blank">here</a>.', 'themeblvd' );
	$site_title .= '</p>';

	/*------------------------------------------------------*/
	/* Site Title + Tagline
	/*------------------------------------------------------*/

	$site_title_tagline  = '<p class="note">';
	$site_title_tagline .= __( 'Current Site Title', 'themeblvd' ).': <strong>';
	$site_title_tagline .= get_bloginfo( 'name' ).'</strong><br>';
	$site_title_tagline .= __( 'Current Tagline', 'themeblvd' ).': <strong>';
	$site_title_tagline .= get_bloginfo( 'description' ).'</strong><br><br>';
	$site_title_tagline .= __( 'You can change your site title by going <a href="options-general.php" target="_blank">here</a>.', 'themeblvd' );
	$site_title_tagline .= '</p>';

	/*------------------------------------------------------*/
	/* Custom Text
	/*------------------------------------------------------*/

	$current_value = '';
	if ( ! empty( $val ) && ! empty( $val['custom'] ) ) {
		$current_value = $val['custom'];
	}

	$current_tagline = '';
	if ( ! empty( $val ) && ! empty( $val['custom_tagline'] ) ) {
		$current_value = $val['custom_tagline'];
	}

	$custom_text  = sprintf( '<p><label class="inner-label"><strong>%s</strong></label>', __( 'Title', 'themeblvd' ) );
	$custom_text .= sprintf( '<input type="text" name="%s" value="%s" /></p>', esc_attr( $name.'['.$id.'][custom]' ), esc_attr( $current_value ) );
	$custom_text .= sprintf( '<p><label class="inner-label"><strong>%s</strong> (%s)</label>', __( 'Tagline', 'themeblvd' ), __( 'optional', 'themeblvd' ) );
	$custom_text .= sprintf( '<input type="text" name="%s" value="%s" /></p>', esc_attr( $name.'['.$id.'][custom_tagline]' ), esc_attr( $current_tagline ) );
	$custom_text .= sprintf( '<p class="note">%s</p>', __( 'Insert your custom text.', 'themeblvd' ) );

	/*------------------------------------------------------*/
	/* Image
	/*------------------------------------------------------*/

	if ( function_exists( 'wp_enqueue_media' ) ) { // WP 3.5+

		$current_value = array( 'url' => '', 'width' => '' );
		if ( is_array( $val ) && isset( $val['image'] ) ) {
			$current_value = array( 'url' => $val['image'], 'width' => $val['image_width'] );
		}

		$current_retina = array( 'url' => '' );
		if ( is_array( $val ) && isset( $val['image_2x'] ) ) {
			$current_retina = array( 'url' => $val['image_2x'] );
		}

		// Standard Image
		$image_upload  = '<div class="section-upload image-standard">';
		$image_upload .= '<label class="inner-label"><strong>'.__( 'Standard Image', 'themeblvd' ).'</strong></label>';
		$image_upload .= themeblvd_media_uploader( array( 'option_name' => $name, 'type' => 'logo', 'id' => $id, 'value' => $current_value['url'], 'value_width' => $current_value['width'], 'name' => 'image' ) );
		$image_upload .= '</div>';

		// Retina image (2x)
		$image_upload .= '<div class="section-upload image-2x">';
		$image_upload .= '<label class="inner-label"><strong>'.__( 'HiDPI-optimized Image (optional)', 'themeblvd' ).'</strong></label>';
		$image_upload .= themeblvd_media_uploader( array( 'option_name' => $name, 'type' => 'logo_2x', 'id' => $id, 'value' => $current_retina['url'], 'name' => 'image_2x' ) );
		$image_upload .= '</div>';

	} else {

		// Media uploader prior to WP 3.5 -- @deprecated

		$current_value = array( 'url' => '', 'width' => '', 'id' => '' );
		if ( is_array( $val ) && isset( $val['image'] ) ) {
			$current_value = array( 'url' => $val['image'], 'width' => $val['image_width'], 'id' => '' );
		}

		$current_retina = array( 'url' => '', 'id' => '' );
		if ( is_array( $val ) && isset( $val['image_2x'] ) ) {
			$current_retina = array( 'url' => $val['image_2x'], 'id' => '' );
		}

		// Standard Image
		$image_upload  = '<div class="section-upload image-standard">';
		$image_upload .= '<label class="inner-label"><strong>'.__( 'Standard Image', 'themeblvd' ).'</strong></label>';
		$image_upload .= optionsframework_medialibrary_uploader( $name, 'logo', $id, $current_value, null, null, 0, 'image' );
		$image_upload .= '</div>';

		// Retina image (2x)
		$image_upload .= '<div class="section-upload image-2x">';
		$image_upload .= '<label class="inner-label"><strong>'.__( 'HiDPI-optimized Image (optional)', 'themeblvd' ).'</strong></label>';
		$image_upload .= optionsframework_medialibrary_uploader( $name, 'logo_2x', $id, $current_retina, null, null, 0, 'image_2x' );
		$image_upload .= '</div>';
	}

	/*------------------------------------------------------*/
	/* Primary Output
	/*------------------------------------------------------*/

	$output  = sprintf( '<div class="select-type">%s</div>', $select_type );
	$output .= sprintf( '<div class="logo-item title">%s</div>', $site_title );
	$output .= sprintf( '<div class="logo-item title_tagline">%s</div>', $site_title_tagline );
	$output .= sprintf( '<div class="logo-item custom">%s</div>', $custom_text );
	$output .= sprintf( '<div class="logo-item image">%s</div>', $image_upload );

	return $output;
}

/**
 * Generates option to edit social media buttons.
 *
 * This has been moved to a separate function
 * because it's a custom addition to the optionframework
 * module and it's pretty lengthy.
 *
 * @since 2.0.0
 *
 * @param $id string unique ID for option
 * @param $name string prefix for form name value
 * @param $val array currently saved data if exists
 * @return $output string HTML for option
 */
function themeblvd_social_media_option( $id, $name, $val ) {

	$sources = themeblvd_get_social_media_buttons();

	$counter = 1;
	$divider = round( count($sources)/2 );

	$output = '<div class="column-1">';

	foreach ( $sources as $key => $source ) {

		// Setup
		$checked = false;
		if ( is_array( $val ) && array_key_exists( $key, $val ) ) {
			$checked = true;
		}

		if ( ! empty( $val ) && ! empty( $val[$key] ) ) {
			$value = $val[$key];
		} else {

			$value = 'http://';
			if ( $key == 'email' ) {
				$value = 'mailto:';
			}
		}

		// Add to output
		$output .= '<div class="item">';
		$output .= '<span>';
		$output .= sprintf( '<input class="checkbox of-input" value="%s" type="checkbox" %s name="%s" />', $key, checked( $checked, true, false ), esc_attr( $name.'['.$id.'][includes][]' ) );
		$output .= $source;
		$output .= '</span>';
		$output .= sprintf( '<input class="of-input social_media-input" value="%s" type="text" name="%s" />', esc_attr( $value ), esc_attr( $name.'['.$id.'][sources]['.$key.']' ) );
		$output .= '</div><!-- .item (end) -->';

		if ( $counter == $divider ) {
			// Separate options into two columns
			$output .= '</div><!-- .column-1 (end) -->';
			$output .= '<div class="column-2">';
		}

		$counter++;
	}
	$output .= '</div><!-- .column-2 (end) -->';

	return $output;
}

/**
 * Option for selecting a sidebar layout that gets
 * inserted into out Hi-jacked "Page Attributes"
 * meta box.
 *
 * @since 2.0.0
 *
 * @param $layout string current sidebar layout
 * @param $output string HTML to output
 */
function themeblvd_sidebar_layout_dropdown( $layout = null ) {

	$sidebar_layouts = themeblvd_sidebar_layouts();

	$output  = '<p><strong>'.__( 'Sidebar Layout', 'themeblvd' ).'</strong></p>';
	$output .= '<select name="_tb_sidebar_layout">';
	$output .= '<option value="default">'.__( 'Default Sidebar Layout', 'themeblvd' ).'</option>';

	foreach ( $sidebar_layouts as $sidebar_layout ) {
		$output .= sprintf( '<option value="%s" %s>%s</option>', $sidebar_layout['id'], selected( $sidebar_layout['id'], $layout, false ), $sidebar_layout['name'] );
	}

	$output .= '</select>';

	return $output;
}

/**
 * Option for selecting a custom layout that gets
 * inserted into out Hi-jacked "Page Attributes"
 * meta box.
 *
 * @since 2.0.0
 *
 * @param $layout string current custom layout
 * @param $output string HTML to output
 */
function themeblvd_custom_layout_dropdown( $layout = null ) {

	// Make sure layout builder plugin is installed
	if ( ! defined( 'TB_BUILDER_PLUGIN_VERSION' ) ) {
		$message = sprintf( __( 'In order to use the "Custom Layout" template, you must have the %s plugin installed.', 'themeblvd' ), '<a href="http://wordpress.org/extend/plugins/theme-blvd-layout-builder" target="_blank">Theme Blvd Layout Builder</a>' );
		return sprtinf( '<p class="tb_custom_layout"><em>%s</em></p>', $message );
	}

	$custom_layouts = get_posts('post_type=tb_layout&numberposts=-1');
	$output = '<p><strong>'.__( 'Custom Layout', 'themeblvd' ).'</strong></p>';

	if ( ! empty( $custom_layouts ) ) {

		$output .= '<select name="_tb_custom_layout">';
		foreach ( $custom_layouts as $custom_layout ) {
			$output .= sprintf( '<option value="%s" %s>%s</option>', $custom_layout->post_name, selected( $custom_layout->post_name, $layout, false ), $custom_layout->post_title );
		}
		$output .= '</select>';

	} else {
		$output .='<p class="tb_custom_layout"><em>'.__( 'You haven\'t created any custom layouts in the Layout builder yet.', 'themeblvd' ).'</em></p>';
	}

	return $output;
}

if ( !function_exists( 'themeblvd_options_footer_text_default' ) ) :
/**
 * Options footer text
 *
 * @since 2.2.0
 */
function themeblvd_options_footer_text_default() {

	// Theme info and text
	$theme_data = wp_get_theme( get_template() );
	$theme_title = $theme_data->get('Name');
	$theme_version = $theme_data->get('Version');

	// Changelog link
	$changelog = '';
	if ( defined( 'TB_THEME_ID' ) ) {
		$changelog = sprintf('( <a href="%s" target="_blank">%s</a> )', apply_filters( 'themeblvd_changelog_link', 'http://themeblvd.com/changelog/?theme='.TB_THEME_ID), __( 'Changelog', 'themeblvd' ) );
	}

	// Output
	printf('%s <strong>%s</strong> with Theme Blvd Framework <strong>%s</strong> %s', $theme_title, $theme_version, TB_FRAMEWORK_VERSION, $changelog );
}
endif;