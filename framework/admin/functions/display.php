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
		$header .= '<th class="head-'.$column['type'].'">'.esc_html($column['name']).'</th>';
	}
	$header .= '</tr>';

	// Start main output
	$output  = '<table class="widefat">';
	$output .= '<div class="tablenav top">';
	$output .= '<div class="alignleft actions">';

	//$output .= '<div class="tb-fancy-select condensed">';
	$output .= '<select name="action">';
	$output .= '<option value="-1" selected="selected">'.esc_html__('Bulk Actions', 'themeblvd').'</option>';
	$output .= '<option value="trash">'.esc_html__('Delete', 'themeblvd').' '.esc_attr($name).'</option>';
	$output .= '</select>';
	//$output .= '<span class="trigger"></span>';
	//$output .= '<span class="textbox"></span>';
	//$output .= '</div>';

	$output .= '<input type="submit" id="doaction" class="button-secondary action" value="'.esc_html__('Apply', 'themeblvd').'">';
	$output .= '</div>';
	$output .= '<div class="alignright tablenav-pages">';
	$output .= '<span class="displaying-num">'.esc_html( sprintf( _n( '1 '.$singular_name, '%s '.$name, count($posts), 'themeblvd'), number_format_i18n( count($posts) ) ) ).'</span>';
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
						$output .= '<strong><a href="#'.esc_attr($post->ID).'" class="title-link edit-'.$post_type.'" title="'.esc_attr__('Edit', 'themeblvd').'">'.esc_html($post->post_title).'</strong></a>';
						$output .= '<div class="row-actions">';
						$output .= '<span class="edit">';
						$output .= '<a href="#'.esc_attr($post->ID).'" class="edit-post edit-'.$post_type.'" title="'.esc_attr__('Edit', 'themeblvd').'">'.esc_attr__('Edit', 'themeblvd').'</a> | ';
						$output .= '</span>';

						if ( $post_type == 'tb_layout' && defined('TB_BUILDER_PLUGIN_VERSION') && version_compare(TB_BUILDER_PLUGIN_VERSION, '2.0.0', '>=') ) {
							$output .= '<span class="export">';
							$output .= '<a href="'.admin_url('admin.php?page=themeblvd_builder&themeblvd_export_layout=true&layout='.esc_attr($post->ID).'&security='.wp_create_nonce('themeblvd_export_layout')).'" class="export-layout" title="'.esc_attr__('Export', 'themeblvd').'">'.esc_attr__('Export', 'themeblvd').'</a> | ';
							$output .= '</span>';
						}

						$output .= '<span class="trash">';
						$output .= '<a title="'.esc_attr__('Delete', 'themeblvd').'" href="#'.esc_attr($post->ID).'">'.esc_attr__('Delete', 'themeblvd').'</a>';
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
							$conditionals = themeblvd_conditionals_config();

							if ( is_array( $assignments ) && ! empty( $assignments ) ) {

								$output .= '<ul>';

								foreach ( $assignments as $key => $assignment ) {

									$class = 'no-conflict';

									if ( in_array( $key, $conflicts[$location] ) ) {
										$class = 'conflict';
									}

									$label = '';

									foreach ( $conditionals as $conditional ) { // Finding a label this way will ensure non-applicable ones are not included (like products when WooCommerce is disabled)
										if ( $conditional['field'] == $assignment['type'] ) {
											if ( in_array( $conditional['field'], array('posts_in_category', 'portfolio_items_in_portfolio', 'product_cat', 'product_tag', 'products_in_cat') ) ) {
												$label = $conditional['name'];
												$label = str_replace(' Archives', '', $label);
											} else {
												$label = ucfirst($conditional['field']);
												$label = str_replace('_', ' ', $label);
											}
										}
									}

									if ( $label ) {
										if ( $assignment['type'] == 'top' || strpos($assignment['type'], '_top') !== false  ) {
											$output .= '<li class="'.$class.'">'.esc_html( $assignment['name'] ).'</li>';
										} elseif ( $assignment['type'] == 'custom' ) {
											$output .= '<li class="'.$class.'">'.$label.': <code>'.esc_html( $assignment['name'] ).'</code></li>';
										} else {
											$output .= '<li class="'.$class.'">'.$label.': '.esc_html( $assignment['name'] ).'</li>';
										}
									}
								}

								$output .= '</ul>';

							} else {
								$output .= '<span class="inactive">'.esc_attr__('No Assignments', 'themeblvd').'</span>';
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
		$output .= '<tr><td colspan="'.$num.'">'.esc_html__('No items have been created yet. Click the Add tab above to get started.', 'themeblvd').'</td></tr>';
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

	$slider_id = uniqid('ui_slider_');

	/*------------------------------------------------------*/
	/* Setup Internal Options
	/*------------------------------------------------------*/

	// Dropdown for number of columns selection
	$data_num = array();

	for ( $i = 0; $i <= 6; $i++  ) {
		switch( $i ) {
			case 0:
				$message = esc_html__('Hide Columns', 'themeblvd');
				break;
			case 1:
				$message = esc_html__('1 Column', 'themeblvd');
				break;
			default:
				$message = strval($i).' '.esc_html__('Columns', 'themeblvd');
		}
		$data_num[$i] = $message;
	}

	// Dropdowns for column width configuration
	$data_grid = array(
		'10' => __('10-Column Grid', 'themeblvd'),
		'12' => __('12-Column Grid', 'themeblvd')
	);

	/*------------------------------------------------------*/
	/* Construct <select> Menus
	/*------------------------------------------------------*/

	// Number of columns
	if ( $type == 'element' ) {
		// Columns in Builder, 1-5 columns
		unset( $data_num[0] );
		unset( $data_num[6] );
	} else if( $type == 'shortcode' ) {
		// Group of [column] shortcodes, 2-6 columns
		unset( $data_num[0] );
		unset( $data_num[1] );
	} else {
		// Standard option, 0-5 columns (ex: Footer Columns)
		unset( $data_num[6] );
	}

	// Select number of columns
	$select_number  = '<div class="tb-fancy-select">';
	$select_number .= '<select class="select-col-num" data-slider="'.$slider_id.'">';

	$count = 0;

	if ( $val && is_string($val) ) {
		$count = count( explode('-', $val) );
	}

	foreach ( $data_num as $key => $value ) {
		$select_number .= '<option value="'.$key.'" '.selected( $count, $key, false ).'>'.$value.'</option>';
	}

	$select_number .= '</select>';
	$select_number .= '<span class="trigger"></span>';
	$select_number .= '<span class="textbox"></span>';
	$select_number .= '</div><!-- .tb-fancy-select (end) -->';

	// Select grid system
	$select_grid  = '<div class="tb-fancy-select">';
	$select_grid .= '<select class="select-grid-system" data-slider="'.$slider_id.'">';

	$grid = '12';
	if ( $val ) {
		$grid = themeblvd_grid_type($val);
	}

	foreach ( $data_grid as $key => $value ) {
		$select_grid .= '<option value="'.$key.'" '.selected( $grid, $key, false ).'>'.esc_attr($value).'</option>';
	}

	$select_grid .= '</select>';
	$select_grid .= '<span class="trigger"></span>';
	$select_grid .= '<span class="textbox"></span>';
	$select_grid .= '</div><!-- .tb-fancy-select (end) -->';

	/*------------------------------------------------------*/
	/* Construct width option, using jQuery UI slider
	/*------------------------------------------------------*/

	$width_option  = sprintf( '<div id="%s" class="slider"></div>', $slider_id );
	$width_option .= sprintf( '<input id="%s" class="of-input column-width-input" name="%s" type="hidden" value="%s" />', esc_attr($id), esc_attr($name.'['.$id.']'), esc_attr($val) );
	$width_option .= '<p class="explain">'.esc_html__('Click and drag the above column dividers left or right.', 'themeblvd').'</p>';

	/*------------------------------------------------------*/
	/* Primary Output
	/*------------------------------------------------------*/

	$output  = sprintf( '<div class="select-wrap select-wrap-num alignleft">%s</div>', $select_number );
	$output .= sprintf( '<div class="select-wrap select-wrap-grid alignleft">%s</div>', $select_grid );

	$output .= '<div class="clear"></div>';
	$output .= '</div><!-- .controls (end) -->';
	$output .= '</div><!-- .option (end) -->';
	$output .= '</div><!-- .section (end) -->';

	$output .= '<div class="section section-column_widths">';
	$output .= '<div class="option">';
	$output .= '<div class="controls">';

	$output .= sprintf( '<div class="column-widths-wrap">%s</div>', $width_option );

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
		'null' 		=> __('- Select Content Type -', 'themeblvd'),
	);

	if ( in_array ( 'widget', $options ) ) {
		$sources['widget'] = __('Floating Widget Area', 'themeblvd');
	}

	if ( in_array ( 'current', $options ) ) {
		$sources['current'] = __('Content of Current Page', 'themeblvd');
	}

	if ( in_array ( 'page', $options ) ) {
		$sources['page'] = __('Content of External Page', 'themeblvd');
	}

	if ( in_array ( 'raw', $options ) ) {
		$sources['raw'] = __('Raw Content', 'themeblvd');
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
		$select_type .= sprintf( '<option value="%s" %s>%s</option>', $key, selected($current_value, $key, false), esc_attr($value) );
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
			$select_sidebar = '<p class="warning">'.sprintf(esc_html__('In order for you to use this feature you need to have the %s plugin activated.', 'themeblvd'), '<a href="http://wordpress.org/extend/plugins/theme-blvd-widget-areas/" target="_blank">Theme Blvd Widget Areas</a>').'</p>';

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
					$select_sidebar .= sprintf('<option value="%s" %s>%s</option>', $key, selected( $current_value, $key, false ), esc_attr($value) );
				}

				$select_sidebar .= '</select>';
				$select_sidebar .= '<span class="trigger"></span>';
				$select_sidebar .= '<span class="textbox"></span>';
				$select_sidebar .= '</div><!-- .tb-fancy-select (end) -->';

			} else {

				$select_sidebar = '<p class="warning">'.esc_html__('You haven\'t created any floating widget areas.', 'themeblvd').'</p>';

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
		$pages = themeblvd_get_select('pages');

		// Build <select>
		if ( ! empty( $pages ) ) {

			$select_page  = '<div class="tb-fancy-select">';
			$select_page .= '<select name= "'.esc_attr( $name.'['.$id.'][page]' ).'">';

			foreach ( $pages as $key => $value ) {
				$select_page .= sprintf('<option value="%s" %s>%s</option>', $key, selected( $current_value, $key, false ), esc_attr($value) );
			}

			$select_page .= '</select>';
			$select_page .= '<span class="trigger"></span>';
			$select_page .= '<span class="textbox"></span>';
			$select_page .= '</div><!-- .tb-fancy-select (end) -->';

		} else {

			$select_page = '<p class="warning">'.esc_html__('You haven\'t created any pages.', 'themeblvd').'</p>';

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
		$raw_content  = '<div class="textarea-wrap with-editor-nav">';
		$raw_content .= '<nav class="editor-nav clearfix">';
		$raw_content .= '<a href="#" class="tb-textarea-editor-link tb-tooltip-link" data-tooltip-text="'.esc_attr__('Open in Editor', 'themeblvd').'" data-target="themeblvd-editor-modal"><i class="tb-icon-pencil"></i></a>';
		$raw_content .= '<a href="#" class="tb-textarea-code-link tb-tooltip-link" data-tooltip-text="'.esc_attr__('Open in Code Editor', 'themeblvd').'" data-target="'.esc_textarea( $id.'_raw' ).'" data-title="'.esc_attr__('Edit HTML', 'themeblvd').'" data-code_lang="html"><i class="tb-icon-code"></i></a>';
		$raw_content .= '</nav>';
		$raw_content .= sprintf( '<textarea id="%s" name="%s" class="of-input" cols="8" rows="8">%s</textarea>', esc_textarea( $id.'_raw' ), esc_attr( $name.'['.$id.'][raw]' ), esc_textarea($current_value) );
		$raw_content .= '</div><!-- .textarea-wrap (end) -->';

		// Checkbox for the_content filter (added in v2.0.6)
		// Should be checked if selected OR option never existed.
		// This is for legacy purposes.
		$checked = 'checked';

		if ( isset( $val['raw_format'] ) && ! $val['raw_format'] ) {
			$checked = '';
		}

		$raw_content .= sprintf( '<input class="checkbox of-input" type="checkbox" name="%s" %s>', esc_attr( $name.'['.$id.'][raw_format]' ), $checked );
		$raw_content .= esc_html__('Apply WordPress automatic formatting.', 'themeblvd');
	}

	/*------------------------------------------------------*/
	/* Primary Output
	/*------------------------------------------------------*/

	$output = '<div class="column-content-types">';
	$output .= $select_type;

	if ( in_array ( 'widget', $options ) ) {
		$output .= '<div class="column-content-type column-content-type-widget">';
		$output .= $select_sidebar;
		$output .= '<p class="note">'.esc_html__('Select from your floating custom widget areas. In order for a custom widget area to be "floating" you must have it configured this way in the Widget Area manager.', 'themeblvd').'</p>';
		$output .= '</div>';
	}

	if ( in_array ( 'page', $options ) ) {
		$output .= '<div class="column-content-type column-content-type-page">';
		$output .= $select_page;
		$output .= '<p class="note">'.esc_html__('Select an external page to pull content from.', 'themeblvd').'</p>';
		$output .= '</div>';
	}

	if ( in_array ( 'raw', $options ) ) {
		$output .= '<div class="column-content-type column-content-type-raw">';
		$output .= $raw_content;
		$output .= '<p class="note">'.esc_html__('You can use basic HTML here, and most shortcodes.', 'themeblvd').'</p>';
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
		'pages' 						=> array(),
		'posts' 						=> array(),
		'posts_in_category' 			=> array(),
		'categories' 					=> array(),
		'tags' 							=> array(),
		'portfolio_items'				=> array(),
		'portfolio_items_in_portfolio'	=> array(),
		'portfolios'					=> array(),
		'portfolio_tags'				=> array(),
		'portfolio_top'					=> array(),
		'product_cat'					=> array(),
		'product_tags' 					=> array(),
		'products_in_cat' 				=> array(),
		'product_top' 					=> array(),
		'forums' 						=> array(),
		'forum_top' 					=> array(),
		'top' 							=> array(),
		'custom'						=> ''
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

				case 'portfolio_item' :
					$assignments['portfolio_items'][] = $item_id;
					break;

				case 'portfolio_items_in_portfolio' :
					$assignments['portfolio_items_in_portfolio'][] = $item_id;
					break;

				case 'portfolio' :
					$assignments['portfolios'][] = $item_id;
					break;

				case 'portfolio_tag' :
					$assignments['portfolio_tags'][] = $item_id;
					break;

				case 'portfolio_top' :
					$assignments['portfolio_top'][] = $item_id;
					break;

				case 'product_cat' :
					$assignments['product_cat'][] = $item_id;
					break;

				case 'product_tag' :
					$assignments['product_tags'][] = $item_id;
					break;

				case 'products_in_cat' :
					$assignments['products_in_cat'][] = $item_id;
					break;

				case 'product_top' :
					$assignments['product_top'][] = $item_id;
					break;

				case 'forum' :
					$assignments['forums'][] = $item_id;
					break;

				case 'forum_top' :
					$assignments['forum_top'][] = $item_id;
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

	// WPML compat
	if ( themeblvd_installed('wpml') ) {
		remove_filter( 'get_pages', array( $GLOBALS['sitepress'], 'exclude_other_language_pages2' ) );
		remove_filter( 'get_terms_args', array( $GLOBALS['sitepress'], 'get_terms_args_filter' ) );
		remove_filter( 'terms_clauses', array( $GLOBALS['sitepress'], 'terms_clauses' ) );
	}

	// Start output
	$output = '<div class="accordion">';

	// Display each accordion element
	foreach ( $conditionals as $conditional ) {

		$output .= '<div class="element">';
		$output .= '<a href="#" class="element-trigger">'.esc_html($conditional['name']).'</a>';
		$output .= '<div class="element-content">';

		switch ( $conditional['id'] ) {

			// Pages posts, and tags
			case 'pages' :
			case 'posts' :
			case 'tags' :
			case 'portfolio_items' :
			case 'portfolio_tags' :
			case 'product_tags' :

				if ( $conditional['id'] == 'pages' ) {
					$single = __('page', 'themeblvd');
					$multiple = __('pages', 'themeblvd');
					$field = 'page';
				} else if ( $conditional['id'] == 'posts' ) {
					$single = __('post', 'themeblvd');
					$multiple = __('posts', 'themeblvd');
					$field = 'post';
				} else if ( $conditional['id'] == 'portfolio_items' ) {
					$single = __('portfolio-item', 'themeblvd');
					$multiple = __('portfolio items', 'themeblvd');
					$field = 'portfolio_item';
				} else if ( $conditional['id'] == 'portfolio_tags' ) {
					$single = __('portfolio-tag', 'themeblvd');
					$multiple = __('portfolio tags', 'themeblvd');
					$field = 'portfolio_tag';
				} else {
					$single = __('tag', 'themeblvd');
					$multiple = __('tags', 'themeblvd');
					$field = $conditional['id'] == 'product_tags' ? 'product_tag' : 'tag';
				}

				$assignment_list = '';

				if ( ! empty( $assignments[$conditional['id']] ) ) {
					$assignment_list = implode( ', ', $assignments[$conditional['id']] );
				}

				$output .= sprintf( '<textarea name="%s">%s</textarea>', esc_attr( $name.'['.$id.']['.$field.']' ), $assignment_list );
				$output .= sprintf( '<p class="note">%s</p>', esc_html(sprintf(__('Enter in a comma-separated list of the %s you\'d like to add to the assignments.', 'themeblvd'), $multiple) ) );
				$output .= sprintf( '<p class="note"><em>%1$s: %2$s-1, %2$s-2, %2$s-3</em></p>', esc_html__('Example', 'themeblvd'), esc_html($single) );
				$output .= sprintf( '<p class="note"><em>%s</em></p>', esc_html( sprintf(__('Note: Any %s entered that don\'t exist won\'t be saved.', 'themeblvd'), $multiple) ) );
				break;

			// Categories
			case 'categories' :
			case 'posts_in_category' :
			case 'portfolios' :
			case 'portfolio_items_in_portfolio' :
			case 'product_cat' :
			case 'products_in_cat' :

				$tax = 'category';

				if ( $conditional['id'] == 'portfolios' || $conditional['id'] == 'portfolio_items_in_portfolio' ) {
					$tax = 'portfolio';
				} else if ( $conditional['id'] == 'product_cat' || $conditional['id'] == 'products_in_cat' ) {
					$tax = 'product_cat';
				}

				$terms = get_terms( $tax, array('hide_empty' => false) );

		        if ( ! empty( $terms ) ) {

		        	$output .= '<ul>';

		        	foreach ( $terms as $term ) {

		        		$checked = false;

		        		if ( in_array( $term->slug, $assignments[$conditional['id']] ) ) {
		        			$checked = true;
		        		}

						$output .= sprintf( '<li><input type="checkbox" %s name="%s" value="%s" /> <span>%s</span></li>', checked( $checked, true, false ), esc_attr( $name.'['.$id.']['.$conditional['field'].'][]' ), $term->slug, $term->name );

					}

					$output .= '</ul>';

				} else {
					$output .= sprintf( '<p class="warning">%s</p>', esc_html($conditional['empty']) );
				}
				break;

			// Forums
			case 'forums' :

				$forums = get_posts( array('post_type' => 'forum', 'orderby' => 'title', 'order' => 'DESC') );

				if ( $forums ) {

					$output .= '<ul>';

					foreach ( $forums as $forum ) {

		        		$checked = false;

		        		if ( in_array( $forum->post_name, $assignments[$conditional['id']] ) ) {
		        			$checked = true;
		        		}

						$output .= sprintf( '<li><input type="checkbox" %s name="%s" value="%s" /> <span>%s</span></li>', checked( $checked, true, false ), esc_attr( $name.'['.$id.']['.$conditional['field'].'][]' ), $forum->post_name, $forum->post_title );

					}

					$output .= '</ul>';

				}

				break;

			// Hierarchy
			case 'portfolio_top' :
			case 'product_top' :
			case 'forum_top' :
			case 'top' :

				if ( ! empty( $conditional['items'] ) ) {

					$output .= '<ul>';

					foreach ( $conditional['items'] as $item_id => $item_name ) {

						$checked = false;

						if ( in_array( $item_id, $assignments[$conditional['id']] ) ) {
							$checked = true;
						}

						$output .= sprintf( '<li><input type="checkbox" %s name="%s" value="%s" /> <span>%s</span></li>', checked( $checked, true, false ), esc_attr( $name.'['.$id.']['.$conditional['field'].'][]' ), $item_id, esc_html($item_name) );
						$checked = false;
					}

					$output .= '</ul>';

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
					$link = '<a href="http://codex.wordpress.org/Conditional_Tags" target="_blank">'.esc_html__('conditional statement', 'themeblvd').'</a>';
					$output .= sprintf( '<input type="text" name="%s" value="%s" />', esc_attr( $name.'['.$id.']['.$conditional['field'].']' ), esc_attr($assignments['custom']) );
					$output .= sprintf( '<p class="note">%s</p>', sprintf(esc_html__('Enter in a custom %s.', 'themeblvd'), $link) );
					$output .= sprintf( '<p class="note"><em>%s</em><br /><code>is_home()</code><br /><code>is_home() || is_single()</code><br /><code>"book" == get_post_type() || is_tax("author")</code></p>', esc_html__('Examples:', 'themeblvd') );
					$output .= sprintf( '<p class="note"><em>%s</em></p>', esc_html__('Warning: Make sure you know what you\'re doing here. If you enter invalid conditional functions, you will most likely get PHP errors on the frontend of your website.', 'themeblvd') );
				}
				break;

		}

		$output .= '</div><!-- .element-content (end) -->';
		$output .= '</div><!-- .element (end) -->';
	}
	$output .= '</div><!-- .accordion (end) -->';

	// Put WPML filters back
	if ( themeblvd_installed('wpml') ) {
		add_filter( 'get_pages', array( $GLOBALS['sitepress'], 'exclude_other_language_pages2' ) );
		add_filter( 'get_terms_args', array( $GLOBALS['sitepress'], 'get_terms_args_filter' ), 10, 2 );
		add_filter( 'terms_clauses', array( $GLOBALS['sitepress'], 'terms_clauses' ), 10, 4 );
	}

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
		'default'		=> __('Default Logo', 'themeblvd'),
		'title' 		=> __('Site Title', 'themeblvd'),
		'title_tagline' => __('Site Title + Tagline', 'themeblvd'),
		'custom' 		=> __('Custom Text', 'themeblvd'),
		'image' 		=> __('Image', 'themeblvd')
	);

	if ( $id != 'trans_logo' ) {
		unset( $types['default'] );
	}

	$current_value = '';

	if ( ! empty( $val ) && ! empty( $val['type'] ) ) {
		$current_value = $val['type'];
	}

	$select_type  = '<div class="tb-fancy-select">';
	$select_type .= '<select name="'.esc_attr( $name.'['.$id.'][type]' ).'">';

	foreach ( $types as $key => $type ) {
		$select_type .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $current_value, $key, false ), esc_attr($type) );
	}

	$select_type .= '</select>';
	$select_type .= '<span class="trigger"></span>';
	$select_type .= '<span class="textbox"></span>';
	$select_type .= '</div><!-- .tb-fancy-select (end) -->';

	/*------------------------------------------------------*/
	/* Site Title
	/*------------------------------------------------------*/

	$link = '<a href="options-general.php" target="_blank">'.esc_html__('here', 'themeblvd').'</a>';
	$site_title  = '<p class="note">';
	$site_title .= esc_html__('Current Site Title', 'themeblvd').': <strong>';
	$site_title .= get_bloginfo('name').'</strong><br><br>';
	$site_title .= sprintf(esc_html__('You can change your site title and tagline by going %s.', 'themeblvd'), $link);
	$site_title .= '</p>';

	/*------------------------------------------------------*/
	/* Site Title + Tagline
	/*------------------------------------------------------*/

	$site_title_tagline  = '<p class="note">';
	$site_title_tagline .= esc_html__('Current Site Title', 'themeblvd').': <strong>';
	$site_title_tagline .= get_bloginfo('name').'</strong><br>';
	$site_title_tagline .= esc_html__('Current Tagline', 'themeblvd').': <strong>';
	$site_title_tagline .= get_bloginfo('description').'</strong><br><br>';
	$site_title_tagline .= sprintf(esc_html__('You can change your site title by going %s.', 'themeblvd'), $link);
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
		$current_tagline = $val['custom_tagline'];
	}

	$custom_text  = sprintf( '<p><label class="inner-label"><strong>%s</strong></label>', esc_html__('Title', 'themeblvd') );
	$custom_text .= sprintf( '<input type="text" name="%s" value="%s" /></p>', esc_attr( $name.'['.$id.'][custom]' ), esc_attr($current_value) );
	$custom_text .= sprintf( '<p><label class="inner-label"><strong>%s</strong> (%s)</label>', esc_html__('Tagline', 'themeblvd'), esc_html__('optional', 'themeblvd') );
	$custom_text .= sprintf( '<input type="text" name="%s" value="%s" /></p>', esc_attr( $name.'['.$id.'][custom_tagline]' ), esc_attr($current_tagline) );
	$custom_text .= sprintf( '<p class="note">%s</p>', esc_html__('Insert your custom text.', 'themeblvd') );

	/*------------------------------------------------------*/
	/* Image
	/*------------------------------------------------------*/

	$current_value = array( 'url' => '', 'width' => '', 'height' => '' );

	if ( isset($val['image']) ) {
		$current_value['url'] = $val['image'];
	}

	if ( isset($val['image_width']) ) {
		$current_value['width'] = $val['image_width'];
	}

	if ( isset($val['image_height']) ) {
		$current_value['height'] = $val['image_height'];
	}

	$current_retina = array( 'url' => '' );

	if ( is_array( $val ) && isset( $val['image_2x'] ) ) {
		$current_retina = array( 'url' => $val['image_2x'] );
	}

	// Standard Image
	$image_upload  = '<div class="section-upload image-standard">';
	$image_upload .= '<label class="inner-label"><strong>'.esc_html__('Standard Image', 'themeblvd').'</strong></label>';
	$image_upload .= themeblvd_media_uploader( array( 'option_name' => $name, 'type' => 'logo', 'id' => $id, 'value' => $current_value['url'], 'value_width' => $current_value['width'], 'value_height' => $current_value['height'], 'name' => 'image' ) );
	$image_upload .= '</div>';

	// Retina image (2x)
	$image_upload .= '<div class="section-upload image-2x">';
	$image_upload .= '<label class="inner-label"><strong>'.esc_html__('HiDPI-optimized Image (optional)', 'themeblvd').'</strong></label>';
	$image_upload .= themeblvd_media_uploader( array( 'option_name' => $name, 'type' => 'logo_2x', 'id' => $id, 'value' => $current_retina['url'], 'name' => 'image_2x' ) );
	$image_upload .= '</div>';

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
 * Generates option for configuring a button.
 *
 * @since 2.5.0
 *
 * @param $id string unique ID for option
 * @param $name string prefix for form name value
 * @param $val array currently saved data if exists
 * @return $output string HTML for option
 */
function themeblvd_button_option( $id, $name, $val ) {

	$output = '';

	// BG color
	$bg = '#ffffff';
	$bg_def = '#ffffff';

	if ( ! empty( $val['bg'] ) ) {
		$bg = $val['bg'];
	}

	if ( ! empty( $value['std']['bg'] ) ) {
		$bg_def = $value['std']['bg'];
	}

	$output .= '<div class="color bg hide">';
	$output .= sprintf( '<input id="%s_bg" name="%s" type="text" value="%s" class="color-picker" data-default-color="%s" />', esc_attr($id), esc_attr($name.'['.$id.'][bg]'), esc_attr($bg), esc_attr($bg_def) );
	$output .= '</div><!-- .color.bg (end) -->';

	// Setup BG hover color
	$bg_hover = '#ebebeb';
	$bg_hover_def = '#ebebeb';

	if ( ! empty( $val['bg_hover'] ) ) {
		$bg_hover = $val['bg_hover'];
	}

	if ( ! empty( $value['std']['bg_hover'] ) ) {
		$bg_hover_def = $value['std']['bg_hover'];
	}

	$output .= '<div class="color bg_hover">';
	$output .= sprintf( '<input id="%s_bg_hover" name="%s" type="text" value="%s" class="color-picker" data-default-color="%s" />', esc_attr($id), esc_attr($name.'['.$id.'][bg_hover]'), esc_attr($bg_hover), esc_attr($bg_hover_def) );
	$output .= '</div><!-- .color.bg_hover (end) -->';

	// Setup border color
	$border = '#cccccc';
	$border_def = '#cccccc';

	if ( ! empty( $val['border'] ) ) {
		$border = $val['border'];
	}

	if ( ! empty( $value['std']['border'] ) ) {
		$border_def = $value['std']['border'];
	}

	$output .= '<div class="color border hide">';
	$output .= sprintf( '<input id="%s_border" name="%s" type="text" value="%s" class="color-picker" data-default-color="%s" />', esc_attr($id), esc_attr($name.'['.$id.'][border]'), esc_attr($border), esc_attr($border_def) );
	$output .= '</div><!-- .color.border (end) -->';

	// Setup text color
	$text = '#333333';
	$text_def = '#333333';

	if ( ! empty( $val['text'] ) ) {
		$text = $val['text'];
	}

	if ( ! empty( $value['std']['text'] ) ) {
		$text_def = $value['std']['text'];
	}

	$output .= '<div class="color text">';
	$output .= sprintf( '<input id="%s_text" name="%s" type="text" value="%s" class="color-picker" data-default-color="%s" />', esc_attr($id), esc_attr($name.'['.$id.'][text]'), esc_attr($text), esc_attr($text_def) );
	$output .= '</div><!-- .color.text (end) -->';

	// Setup text hover color
	$text_hover = '#333333';
	$text_hover_def = '#333333';

	if ( ! empty( $val['text_hover'] ) ) {
		$text_hover = $val['text_hover'];
	}

	if ( ! empty( $value['std']['text_hover'] ) ) {
		$text_hover_def = $value['std']['text_hover'];
	}

	$output .= '<div class="color text_hover">';
	$output .= sprintf( '<input id="%s_text_hover" name="%s" type="text" value="%s" class="color-picker" data-default-color="%s" />', esc_attr($id), esc_attr($name.'['.$id.'][text_hover]'), esc_attr($text_hover), esc_attr($text_hover_def) );
	$output .= '</div><!-- .color.text_hover (end) -->';

	// Include BG
	$include_bg = 1;

	if ( isset( $val['include_bg'] ) ) {
		$include_bg = $val['include_bg'];
	}

	$output .= '<div class="include bg clearfix">';
	$output .= sprintf( '<div class="include-controls"><input id="%s_include_bg" class="checkbox of-input" type="checkbox" name="%s" %s /></div>', esc_attr($id), esc_attr($name.'['.$id.'][include_bg]'), checked( $include_bg, 1, false ) );
	$output .= '<div class="include-explain">'.esc_html__('Button has background color', 'themeblvd').'</div>';
	$output .= '</div><!-- .include (end) -->';

	// Include border
	$include_border = 1;

	if ( isset( $val['include_border'] ) ) {
		$include_border = $val['include_border'];
	}

	$output .= '<div class="include border clearfix">';
	$output .= sprintf( '<div class="include-controls"><input id="%s_include_border" class="checkbox of-input" type="checkbox" name="%s" %s /></div>', esc_attr($id), esc_attr($name.'['.$id.'][include_border]'), checked( $include_border, 1, false ) );
	$output .= '<div class="include-explain">'.esc_html__('Button has border', 'themeblvd').'</div>';
	$output .= '</div><!-- .include (end) -->';

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

	$output  = '<p><strong>'.esc_html__('Sidebar Layout', 'themeblvd').'</strong></p>';
	$output .= '<select name="_tb_sidebar_layout">';
	$output .= '<option value="default">'.esc_html__('Default Sidebar Layout', 'themeblvd').'</option>';

	foreach ( $sidebar_layouts as $sidebar_layout ) {
		$output .= sprintf( '<option value="%s" %s>%s</option>', $sidebar_layout['id'], selected( $sidebar_layout['id'], $layout, false ), esc_html($sidebar_layout['name']) );
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
		$message = sprintf( esc_html__('In order to use the "Custom Layout" template, you must have the %s plugin installed.', 'themeblvd'), '<a href="http://wordpress.org/extend/plugins/theme-blvd-layout-builder" target="_blank">Theme Blvd Layout Builder</a>' );
		return sprtinf( '<p class="tb_custom_layout"><em>%s</em></p>', $message );
	}

	$custom_layouts = get_posts('post_type=tb_layout&numberposts=-1');
	$output = '<p><strong>'.esc_html__('Custom Layout', 'themeblvd').'</strong></p>';

	if ( ! empty( $custom_layouts ) ) {

		$output .= '<select name="_tb_custom_layout">';

		foreach ( $custom_layouts as $custom_layout ) {
			$output .= sprintf( '<option value="%s" %s>%s</option>', $custom_layout->post_name, selected( $custom_layout->post_name, $layout, false ), $custom_layout->post_title );
		}

		$output .= '</select>';

	} else {
		$output .='<p class="tb_custom_layout"><em>'.esc_html__('You haven\'t created any custom layouts in the Layout builder yet.', 'themeblvd').'</em></p>';
	}

	return $output;
}

/**
 * Outputs Editor in hidden modal window that can
 * be accessed by other options.
 *
 * @since 2.5.0
 */
function themeblvd_editor( $args = array() ) {

	$defaults = array(
		'delete' 	=> false,
		'duplicate'	=> false
	);
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	wp_editor( '', 'themeblvd_editor' );
	$editor = ob_get_clean();
	?>
	<div id="themeblvd-editor-modal" class="themeblvd-modal-wrap hide">
		<div class="themeblvd-modal medium-modal media-modal wp-core-ui tb-modal-with-editor">

			<button type="button" class="button-link media-modal-close">
				<span class="media-modal-icon">
					<span class="screen-reader-text">x</span>
				</span>
			</button>

			<div class="media-modal-content">
				<div class="media-frame wp-core-ui hide-menu hide-router">

					<div class="media-frame-title">
						<h1><?php esc_html_e('Edit Content', 'themeblvd'); ?></h1>
					</div><!-- .media-frame-title (end) -->

					<div class="media-frame-content">
						<div class="media-frame-content-inner">
							<div class="content-mitt">
								<?php echo $editor; ?>
							</div>
						</div><!-- .media-frame-content-inner (end) -->
					</div><!-- .media-frame-content (end) -->

					<div class="media-frame-toolbar">
						<div class="media-toolbar">
							<?php if ( $args['delete'] || $args['duplicate'] ) : ?>
								<div class="media-toolbar-secondary">
									<?php if ( $args['delete'] ) : ?>
										<a href="#" class="button media-button button-secondary button-large media-button-delete"><?php esc_html_e('Delete', 'themeblvd'); ?></a>
									<?php endif; ?>
									<?php if ( $args['duplicate'] ) : ?>
										<a href="#" class="button media-button button-secondary button-large media-button-secondary"><?php esc_html_e('Duplicate', 'themeblvd'); ?></a>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<div class="media-toolbar-primary">
								<a href="#" class="button media-button button-primary button-large media-button-insert"><?php esc_html_e('Save', 'themeblvd'); ?></a>
							</div>
						</div><!-- .media-toolbar (end) -->
					</div><!-- .media-frame-toolbar (end) -->

				</div><!-- .media-frame (end) -->
			</div><!-- .media-modal-content (end) -->

		</div><!-- .media-modal (end) -->
	</div>
	<?php
}

/**
 * Outputs icon browser in hidden modal window that can
 * be accessed by other options.
 *
 * @since 2.5.0
 */
function themeblvd_icon_browser( $args = array() ) {

	$defaults = array(
		'type' => 'vector' // image, vector
	);
	$args = wp_parse_args( $args, $defaults );

	// Get icons from framework (cached for 24 hours)
	$icons = themeblvd_get_icons( $args['type'] );
	?>
	<div id="themeblvd-icon-browser-<?php echo $args['type']; ?>" class="themeblvd-modal-wrap themeblvd-icon-browser hide">
		<div class="themeblvd-modal medium-modal media-modal wp-core-ui tb-modal-with-icon-browser">

			<button type="button" class="button-link media-modal-close">
				<span class="media-modal-icon">
					<span class="screen-reader-text">x</span>
				</span>
			</button>

			<div class="media-modal-content">
				<div class="media-frame wp-core-ui hide-menu hide-router">

					<div class="media-frame-title">
						<h1><?php esc_html_e('Select an Icon', 'themeblvd'); ?></h1>
					</div><!-- .media-frame-title (end) -->

					<div class="media-frame-content">
						<div class="media-frame-content-inner">
							<div class="content-mitt">
								<div class="icon-browser">
									<?php
									if ( $args['type'] == 'vector' ) {

										foreach ( $icons as $icon ) {
											printf( '<a href="#" class="select-icon select-vector-icon tb-tooltip-link" data-icon="%1$s" data-tooltip-text="%1$s"><i class="fa fa-%1$s fa-fw fa-2x"></i></a>', $icon );
										}

									} else if ( $args['type'] == 'image' ) {

										foreach ( $icons as $icon_id => $icon_url ) {
											printf( '<a href="#" class="select-icon select-image-icon tb-tooltip-link" data-icon="%1$s" data-tooltip-text="%1$s"><img src="%2$s" /></a>', $icon_id, $icon_url );
										}

									}
									?>
								</div>
							</div>
						</div><!-- .media-frame-content-inner (end) -->
					</div><!-- .media-frame-content (end) -->

					<div class="media-frame-toolbar">
						<div class="media-toolbar-secondary">
							<input type="hidden" class="icon-selection" value="" />
						</div><!-- .media-toolbar-secondary (end) -->
						<div class="media-toolbar">
							<div class="media-toolbar-primary">
								<a href="#" class="button media-button button-primary button-large media-button-insert"><?php esc_html_e('Use Icon', 'themeblvd'); ?></a>
							</div>
						</div><!-- .media-toolbar (end) -->
					</div><!-- .media-frame-toolbar (end) -->

				</div><!-- .media-frame (end) -->
			</div><!-- .media-modal-content (end) -->

		</div><!-- .media-modal (end) -->
	</div>
	<?php

}

/**
 * Outputs post browser in hidden modal window that can
 * be accessed by other options.
 *
 * @since 2.5.0
 */
function themeblvd_post_browser( $args = array() ) {
	?>
	<div id="themeblvd-post-browser" class="themeblvd-modal-wrap themeblvd-post-browser hide">
		<div class="themeblvd-modal medium-modal media-modal wp-core-ui tb-modal-with-icon-browser">

			<button type="button" class="button-link media-modal-close">
				<span class="media-modal-icon">
					<span class="screen-reader-text">x</span>
				</span>
			</button>

			<div class="media-modal-content">
				<div class="media-frame wp-core-ui hide-menu hide-router">

					<div class="media-frame-title">
						<h1><?php esc_html_e('Find Post or Page ID', 'themeblvd'); ?></h1>
					</div><!-- .media-frame-title (end) -->

					<div class="media-frame-content">
						<div class="media-frame-content-inner">
							<div id="optionsframework" class="content-mitt">
								<div class="post-browser">
									<div class="post-browser-head clearfix">
										<div class="search-box">
											<input type="search" id="post-search-input" name="s" value="">
											<input type="submit" name="" id="search-submit" class="button" value="<?php esc_attr_e('Search Posts & Pages', 'themeblvd'); ?>">
										</div>
										<span class="tb-loader ajax-loading">
	  										<i class="tb-icon-spinner"></i>
	  									</span>
	  								</div>
									<div class="search-results ajax-mitt">
									</div>
								</div>
							</div>
						</div><!-- .media-frame-content-inner (end) -->
					</div><!-- .media-frame-content (end) -->

				</div><!-- .media-frame (end) -->
			</div><!-- .media-modal-content (end) -->

		</div><!-- .media-modal (end) -->
	</div>
	<?php
}

/**
 * Ajax action function for searching posts and
 * displaying table of results.
 *
 * @since 2.5.0
 */
function themeblvd_ajax_post_browser(){

	$query = array(
		's' => $_POST['data'],
		'post_type' => 'any'
	);

	$posts = get_posts($query);
	?>
	<table class="widefat">
		<thead>
			<tr>
				<th class="head-title"><?php esc_html_e('Title', 'themeblvd'); ?></th>
				<th class="head-slug"><?php esc_html_e('Slug', 'themeblvd'); ?></th>
				<th class="head-type"><?php esc_html_e('Type', 'themeblvd'); ?></th>
				<th class="head-id"><?php esc_html_e('ID', 'themeblvd'); ?></th>
				<th class="head-select"><?php esc_html_e('Select', 'themeblvd'); ?></th>
			</tr>
		</thead>
		<?php if ( $posts ) : ?>
			<?php foreach ( $posts as $post ) :
				$type = get_post_type_object($post->post_type);
				?>
				<tr>
					<td><?php echo $post->post_title; ?></td>
					<td><?php echo $post->post_name; ?></td>
					<td><?php echo $type->labels->singular_name; ?></td>
					<td><?php echo $post->ID; ?></td>
					<td><a href="#" data-post-id="<?php echo $post->ID; ?>" class="select-post button-secondary"><?php esc_html_e('Use Post', 'themeblvd'); ?></a></td>
				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td colspan="5"><?php esc_html_e('No posts found.', 'themeblvd'); ?></td>
			</tr>
		<?php endif; ?>
	</table>
	<?php
	die();
}
add_action( 'wp_ajax_themeblvd_post_browser', 'themeblvd_ajax_post_browser' );

/**
 * Outputs texture browser in hidden modal window that can
 * be accessed by other options.
 *
 * @since 2.5.0
 */
function themeblvd_texture_browser( $args = array() ) {

	// Get textures from framework
	$textures = themeblvd_get_textures();
	?>
	<div id="themeblvd-texture-browser" class="themeblvd-modal-wrap themeblvd-texture-browser hide">
		<div class="themeblvd-modal medium-modal media-modal wp-core-ui tb-modal-with-icon-browser">

			<button type="button" class="button-link media-modal-close">
				<span class="media-modal-icon">
					<span class="screen-reader-text">x</span>
				</span>
			</button>

			<div class="media-modal-content">
				<div class="media-frame wp-core-ui hide-menu hide-router">

					<div class="media-frame-title">
						<h1><?php esc_html_e('Select a texture', 'themeblvd'); ?></h1>
						<input id="texture-browser-perview-color" type="text" value="#00366d" data-default-color="#00366d" />
					</div><!-- .media-frame-title (end) -->

					<div class="media-frame-content">
						<div class="media-frame-content-inner">
							<div class="content-mitt">
								<div class="icon-browser">
									<?php
									echo '<div class="texture-header">';
									echo '<h2>'.esc_html__('Dark Textures', 'themeblvd').'</h2>';
									echo '</div>';

									foreach ( $textures as $id => $texture ) {

										if ( $id == 'divider' ) {
											echo '<div class="texture-header">';
											echo '<h2>'.esc_html__('Light Textures <span>(Above textures tweaked slightly to be overlaid on lighter background colors)</span>', 'themeblvd').'</h2>';
											echo '</div>';
											continue;
										}

										$size = '';
										if ( isset( $texture['size'] ) ) {
											$size = $texture['size'];
										}

										echo '<div class="select-texture-wrap">';
										printf( '<a href="#" class="select-texture texture-%s" data-texture="%s" data-texture-name="%s"><span style="background-image: url(%s); background-position: %s; background-repeat: %s; background-size: %s;"></span></a>', $id, $id, esc_html($texture['name']), $texture['url'], $texture['position'], $texture['repeat'], $size );
										printf( '<p class="texture-label">%s</p>', str_replace('Light ', '', esc_html($texture['name'])) );
										echo '</div><!-- .select-texture-wrap (end) -->';
									}
									?>
								</div>
							</div>
						</div><!-- .media-frame-content-inner (end) -->
					</div><!-- .media-frame-content (end) -->

					<div class="media-frame-toolbar">
						<div class="media-toolbar-secondary">
							<input type="hidden" class="texture-selection" value="" />
							<span class="current-texture"></span>
						</div><!-- .media-toolbar-secondary (end) -->
						<div class="media-toolbar">
							<div class="media-toolbar-primary">
								<a href="#" class="button media-button button-primary button-large media-button-insert"><?php esc_html_e('Use Texture', 'themeblvd'); ?></a>
							</div>
						</div><!-- .media-toolbar (end) -->
					</div><!-- .media-frame-toolbar (end) -->

				</div><!-- .media-frame (end) -->
			</div><!-- .media-modal-content (end) -->

		</div><!-- .media-modal (end) -->
	</div>
	<?php
}

/**
 * Dispay set of preset option values user can click
 * to populate part of the options page.
 *
 * @since 2.5.0
 *
 * @param array $preset
 */
function themeblvd_display_presets( $args, $option_name = '' ) {

	$defaults = array(
		'id'			=> '',
		'options'		=> array(),
		'sets'			=> array(),
		'icon_width'	=> '',
		'level'			=> 1
	);
	$args = wp_parse_args( $args, $defaults );

	$class = 'tb-presets level-'.$args['level'];

	if ( $args['icon_width'] ) {
		$class .= ' has-images';
	}

	$output = '<div class="'.$class.'">';

	if ( $args['options'] ) {

		$output .= '<ul class="presets-menu">';

		foreach ( $args['options'] as $key => $value ) {

			if ( ! empty( $args['sets'][$key] ) ) {

				$output .= '<li>';

				if ( $args['icon_width'] ) {
					$item = sprintf('<img src="%s" width="%s" />', $value, $args['icon_width']);
				} else {
					$item = $value;
				}

				$output .= sprintf('<a href="#" class="tb-tooltip-link" data-set="%s" data-id="%s" data-tooltip-text="%s">%s</a>', $key, $args['id'], esc_html__('Apply Preset', 'themeblvd'), $item );

				$output .= '</li>';

			}

		}

		$output .= '</ul>';

	}

	if ( $args['sets'] ) {

		$output .= "\n<script type=\"text/javascript\">\n";
		$output .= "/* <![CDATA[ */\n";
		$output .= "themeblvd_presets['".$args['id']."'] = {\n";

		$last = end($args['sets']);

		foreach ( $args['sets'] as $key => $value ) {

			if ( $option_name ) {
				$old_value = $value;
				$value = array();
				$value[$option_name] = $old_value;
			}

			$output .= sprintf('%s: %s', $key, json_encode($value));

			if ( $value != $last ) {
				$output .= ',';
			}

			$output .= "\n";
		}

		$output .= "};\n";
		$output .= "/* ]]> */\n";
		$output .= "</script>\n";

	}

	$output .= '</div><!-- .option-presets (end) -->';

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
		$changelog = sprintf('( <a href="%s" target="_blank">%s</a> )', apply_filters( 'themeblvd_changelog_link', 'http://themeblvd.com/changelog/?theme='.TB_THEME_ID), esc_html__('Changelog', 'themeblvd') );
	}

	// Output
	printf( '<i class="tb-icon-logo-stroke wp-ui-text-highlight"></i> %s <strong>%s</strong> with Theme Blvd Framework <strong>%s</strong> %s', $theme_title, $theme_version, TB_FRAMEWORK_VERSION, $changelog );
}
endif;
