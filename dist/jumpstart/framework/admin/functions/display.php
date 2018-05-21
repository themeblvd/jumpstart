<?php
/**
 * Admin Display Components
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */

/**
 * Generates a table for a custom post type.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param  string $post_type Post type ID.
 * @param  array  $columns   Columns for table.
 * @return string $output    HTML output for table.
 */
function themeblvd_post_table( $post_type, $columns ) {

	$post_type_object = get_post_type_object( $post_type );
	$name = $post_type_object->labels->name;

	$posts = get_posts( array(
		'post_type'   => $post_type,
		'numberposts' => -1,
		'orderby'     => 'title',
		'order'       => 'ASC',
	) );

	if ( 'tb_sidebar' === $post_type ) {
		$conflicts = themeblvd_get_assignment_conflicts( $posts );
	}

	$header  = '<tr>';

	$header .= '<th scope="col" id="cb" class="manage-column column-cb check-column"><input type="checkbox"></th>';

	foreach ( $columns as $column ) {

		$header .= '<th class="head-' . $column['type'] . '">' . esc_html( $column['name'] ) . '</th>';

	}

	$header .= '</tr>';

	/*
	 * Start building main output.
	 */
	$output  = '<table class="widefat">';
	$output .= '<div class="tablenav top">';
	$output .= '<div class="alignleft actions">';

	$output .= '<select name="action">';
	$output .= '<option value="-1" selected="selected">' . esc_html__( 'Bulk Actions', 'jumpstart' ) . '</option>';
	$output .= '<option value="trash">' . esc_html__( 'Delete', 'jumpstart' ) . ' ' . esc_attr( $name ) . '</option>';
	$output .= '</select>';

	$output .= '<input type="submit" id="doaction" class="button-secondary action" value="' . esc_html__( 'Apply', 'jumpstart' ) . '">';
	$output .= '</div>';
	$output .= '<div class="alignright tablenav-pages">';
	// translators: 1: number of current posts in our custom post table output
	$output .= '<span class="displaying-num">' . esc_html( sprintf( _n( '%s item', '%s items', count( $posts ), 'jumpstart' ), number_format_i18n( count( $posts ) ) ) ) . '</span>';
	$output .= '</div>';
	$output .= '<div class="clear"></div>';
	$output .= '</div>';

	$output .= '<thead>';
	$output .= $header;
	$output .= '</thead>';

	$output .= '<tfoot>';
	$output .= $header;
	$output .= '</tfoot>';

	$output .= '<tbody>';

	if ( ! empty( $posts ) ) {

		foreach ( $posts as $post ) {

			$output .= '<tr id="row-' . $post->ID . '">';
			$output .= '<th scope="row" class="check-column"><input type="checkbox" name="posts[]" value="' . $post->ID . '"></th>';

			foreach ( $columns as $column ) {

				switch ( $column['type'] ) {

					case 'title':
						$output .= '<td class="post-title page-title column-title">';
						$output .= '<strong><a href="#' . esc_attr( $post->ID ) . '" class="title-link edit-' . $post_type . '" title="' . esc_attr__( 'Edit', 'jumpstart' ) . '">' . esc_html( $post->post_title ) . '</strong></a>';
						$output .= '<div class="row-actions">';
						$output .= '<span class="edit">';
						$output .= '<a href="#' . esc_attr( $post->ID ) . '" class="edit-post edit-' . $post_type . '" title="' . esc_attr__( 'Edit', 'jumpstart' ) . '">' . esc_attr__( 'Edit', 'jumpstart' ) . '</a> | ';
						$output .= '</span>';

						if ( 'tb_layout' === $post_type && defined( 'TB_BUILDER_PLUGIN_VERSION' ) && version_compare( TB_BUILDER_PLUGIN_VERSION, '2.0.0', '>=' ) ) {
							$output .= '<span class="export">';
							$output .= '<a href="' . esc_url( admin_url( 'admin.php?page=themeblvd_builder&themeblvd_export_layout=true&layout=' . esc_attr( $post->ID ) . '&security=' . wp_create_nonce( 'themeblvd_export_layout' ) ) ) . '" class="export-layout" title="' . esc_attr__( 'Export', 'jumpstart' ) . '">' . esc_attr__( 'Export', 'jumpstart' ) . '</a> | ';
							$output .= '</span>';
						}

						$output .= '<span class="trash">';
						$output .= '<a title="' . esc_attr__( 'Delete', 'jumpstart' ) . '" href="#' . esc_attr( $post->ID ) . '">' . esc_attr__( 'Delete', 'jumpstart' ) . '</a>';
						$output .= '</span>';
						$output .= '</div>';
						break;

					case 'id':
						$output .= '<td class="post-id">';
						$output .= esc_html( $post->ID );
						break;

					case 'slug':
						$output .= '<td class="post-slug">';
						$output .= esc_html( $post->post_name );
						break;

					case 'meta':
						$output .= '<td class="post-meta-' . $column['config'] . '">';
						$meta = get_post_meta( $post->ID, $column['config'], true );

						if ( isset( $column['inner'] ) ) {

							if ( isset( $meta[ $column['inner'] ] ) ) {
								$output .= esc_html( $meta[ $column['inner'] ] );
							}
						} else {
							$output .= esc_html( $meta );
						}
						break;

					case 'shortcode':
						$output .= '<td class="post-shortcode-' . $column['config'] . '">';
						$output .= '[' . $column['config'] . ' id="' . $post->post_name . '"]';
						break;

					case 'assignments':
						$output .= '<td class="post-assignments">';
						$location = get_post_meta( $post->ID, 'location', true );

						if ( $location && 'floating' !== $location ) {

							$assignments = get_post_meta( $post->ID, 'assignments', true );
							$conditionals = themeblvd_conditionals_config();

							if ( is_array( $assignments ) && ! empty( $assignments ) ) {

								$output .= '<ul>';

								foreach ( $assignments as $key => $assignment ) {

									$class = 'no-conflict';

									if ( in_array( $key, $conflicts[ $location ] ) ) {
										$class = 'conflict';
									}

									$label = '';

									/*
									 * Find label. -- Finding a label this way will ensure
									 * non-applicable ones are not included (like products
									 * when WooCommerce is disabled).
									 */
									foreach ( $conditionals as $conditional ) {

										if ( $conditional['field'] == $assignment['type'] ) {

											if ( in_array( $conditional['field'], array( 'posts_in_category', 'portfolio_items_in_portfolio', 'product_cat', 'product_tag', 'products_in_cat' ) ) ) {

												$label = $conditional['name'];
												$label = str_replace( ' Archives', '', $label );

											} else {

												$label = ucfirst( $conditional['field'] );
												$label = str_replace( '_', ' ', $label );

											}
										}
									}

									if ( $label ) {

										if ( 'top' === $assignment['type'] || strpos( $assignment['type'], '_top' ) !== false ) {

											$output .= '<li class="' . $class . '">' . esc_html( $assignment['name'] ) . '</li>';

										} elseif ( 'custom' === $assignment['type'] ) {

											$output .= '<li class="' . $class . '">' . $label . ': <code>' . esc_html( $assignment['name'] ) . '</code></li>';

										} else {

											$output .= '<li class="' . $class . '">' . $label . ': ' . esc_html( $assignment['name'] ) . '</li>';

										}
									}
								}

								$output .= '</ul>';

							} else {

								$output .= '<span class="inactive">' . esc_attr__( 'No Assignments', 'jumpstart' ) . '</span>';

							}
						} else {

							$output .= '<span class="inactive">[floating]</span>';

						}
						break;

					case 'sidebar_location':
						$output .= '<td class="sidebar-location">';
						$output .= themeblvd_get_sidebar_location_name( esc_html( get_post_meta( $post->ID, 'location', true ) ) );
						break;

				}

				$output .= '</td>';

			}

			$output .= '</tr>';

		}
	} else {

		/*
		 * Display empty table, with no results found.
		 */

		$num = count( $columns ) + 1; // Number of columns plus the checkbox column.

		$output .= '<tr><td colspan="' . $num . '">';
		$output .= esc_html__( 'No items have been created yet. Click the Add tab above to get started.', 'jumpstart' );
		$output .= '</td></tr>';

	}

	$output .= '</tbody>';
	$output .= '</table>';

	return $output;

}

/**
 * Generates option for configuring columns.
 *
 * This has been moved to a separate function
 * because it's a custom addition to the options
 * interface module and it's pretty lengthy.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param  string $type   Type of use, standard or element.
 * @param  string $id     Unique ID for option.
 * @param  string $name   Prefix for form name value.
 * @param  array  $val    Currently saved data if exists.
 * @return string $output HTML for option.
 */
function themeblvd_columns_option( $type, $id, $name, $val ) {

	$slider_id = uniqid( 'ui_slider_' . rand() );

	/*
	 * Setup internal options.
	 */

	// Dropdown for number of columns selection.
	$data_num = array();

	for ( $i = 0; $i <= 6; $i++ ) {

		switch ( $i ) {
			case 0:
				$message = esc_html__( 'Hide Columns', 'jumpstart' );
				break;

			case 1:
				$message = esc_html__( '1 Column', 'jumpstart' );
				break;

			default:
				$message = strval( $i ) . ' ' . esc_html__( 'Columns', 'jumpstart' );

		}

		$data_num[ $i ] = $message;

	}

	// Dropdowns for column width configuration.
	$data_grid = array(
		'10' => __( '10-Column Grid', 'jumpstart' ),
		'12' => __( '12-Column Grid', 'jumpstart' ),
	);

	/*
	 * Construct <select> menu outputs.
	 */

	if ( 'element' === $type ) {

		// Columns in Builder, 1-5 columns.
		unset( $data_num[0] );
		unset( $data_num[6] );

	} elseif ( 'shortcode' === $type ) {

		// Group of [column] shortcodes, 2-6 columns.
		unset( $data_num[0] );
		unset( $data_num[1] );

	} else {

		// Standard option, 0-5 columns (ex: Footer Columns)
		unset( $data_num[6] );

	}

	// Build <select> for number of columns.
	$select_number = '<select class="select-col-num" data-slider="' . $slider_id . '">';

	$count = 0;

	if ( $val && is_string( $val ) ) {
		$count = count( explode( '-', $val ) );
	}

	foreach ( $data_num as $key => $value ) {
		$select_number .= '<option value="' . $key . '" ' . selected( $count, $key, false ) . '>' . $value . '</option>';
	}

	$select_number .= '</select>';

	// Build <select> for grid system.
	$select_grid = '<select class="select-grid-system" data-slider="' . $slider_id . '">';

	$grid = '12';

	if ( $val ) {
		$grid = themeblvd_grid_type( $val );
	}

	foreach ( $data_grid as $key => $value ) {
		$select_grid .= '<option value="' . $key . '" ' . selected( $grid, $key, false ) . '>' . esc_attr( $value ) . '</option>';
	}

	$select_grid .= '</select>';

	// Build width option, using jQuery UI slider.
	$width_option  = sprintf(
		'<div id="%s" class="slider"></div>',
		$slider_id
	);

	$width_option .= sprintf(
		'<input id="%s" class="of-input column-width-input" name="%s" type="hidden" value="%s" />',
		esc_attr( $id ),
		esc_attr( $name . '[' . $id . ']' ),
		esc_attr( $val )
	);

	$width_option .= '<p class="explain">' . esc_html__( 'Click and drag the above column dividers left or right.', 'jumpstart' ) . '</p>';

	// Build primary output.
	$output  = sprintf(
		'<div class="select-wrap select-wrap-num alignleft">%s</div>',
		$select_number
	);

	$output .= sprintf(
		'<div class="select-wrap select-wrap-grid alignleft">%s</div>',
		$select_grid
	);

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
 * @since Theme_Blvd 2.0.0
 *
 * @param  string $id      Unique ID for option.
 * @param  string $name    Prefix for form name value.
 * @param  array  $val     Currently saved data if exists.
 * @param  array  $options Content sources to choose from.
 * @return string $output  HTML for option.
 */
function themeblvd_content_option( $id, $name, $val, $options ) {

	/*
	 * Build <select> for type of content
	 */

	// Setup content types to choose from.
	$sources = array(
		'null' => __( '- Select Content Type -', 'jumpstart' ),
	);

	if ( in_array( 'widget', $options ) ) {
		$sources['widget'] = __( 'Floating Widget Area', 'jumpstart' );
	}

	if ( in_array( 'current', $options ) ) {
		$sources['current'] = __( 'Content of Current Page', 'jumpstart' );
	}

	if ( in_array( 'page', $options ) ) {
		$sources['page'] = __( 'Content of External Page', 'jumpstart' );
	}

	if ( in_array( 'raw', $options ) ) {
		$sources['raw'] = __( 'Custom Content', 'jumpstart' );
	}

	// Set default value.
	$current_value = '';

	if ( ! empty( $val ) && ! empty( $val['type'] ) ) {
		$current_value = $val['type'];
	}

	// Build <select>.
	$select_type = sprintf(
		'<select class="select-type" name= "%s">',
		esc_attr( $name . '[' . $id . '][type]' )
	);

	foreach ( $sources as $key => $value ) {

		$select_type .= sprintf(
			'<option value="%s" %s>%s</option>',
			esc_attr( $key ),
			selected( $current_value, $key, false ),
			esc_attr( $value )
		);

	}

	$select_type .= '</select>';

	/*
	 * Build <select> for widget area.
	 */
	if ( in_array( 'widget', $options ) ) {

		if ( defined( 'TB_SIDEBARS_PLUGIN_VERSION' ) ) { // Theme Blvd Widget Areas plugin required.

			$sidebars = array();

			$current_value = ! empty( $val ) && ! empty( $val['sidebar'] ) ? $val['sidebar'] : null;

			$sidebars = themeblvd_get_select( 'sidebars' );

			if ( ! empty( $sidebars ) ) {

				$select_sidebar = sprintf(
					'<select class="select-sidebar" name= "%s">',
					esc_attr( $name . '[' . $id . '][sidebar]' )
				);

				foreach ( $sidebars as $key => $value ) {

					$select_sidebar .= sprintf(
						'<option value="%s" %s>%s</option>',
						$key,
						selected( $current_value, $key, false ),
						esc_attr( $value )
					);

				}

				$select_sidebar .= '</select>';

			} else {

				$select_sidebar = '<p class="warning">' . esc_html__( 'You haven\'t created any floating widget areas.', 'jumpstart' ) . '</p>';

			}
		} else {

			$select_sidebar  = '<p class="warning">';

			$select_sidebar .= sprintf(
				// translators: 1: link to Theme Blvd Widget Areas plugin
				esc_html__( 'In order for you to use this feature you need to have the %s plugin activated.', 'jumpstart' ),
				'<a href="http://wordpress.org/extend/plugins/theme-blvd-widget-areas/" target="_blank">Theme Blvd Widget Areas</a>'
			);

			$select_sidebar .= '</p>';

		}
	}

	/*
	 * Build <select> for external page.
	 */
	if ( in_array( 'page', $options ) ) {

		$current_value = '';

		if ( ! empty( $val ) && ! empty( $val['page'] ) ) {
			$current_value = $val['page'];
		}

		$pages = themeblvd_get_select( 'pages' );

		if ( ! empty( $pages ) ) {

			$select_page = sprintf(
				'<select name="%s">',
				esc_attr( $name . '[' . $id . '][page]' )
			);

			foreach ( $pages as $key => $value ) {

				$select_page .= sprintf(
					'<option value="%s" %s>%s</option>',
					$key,
					selected( $current_value, $key, false ),
					esc_attr( $value )
				);

			}

			$select_page .= '</select>';

		} else {

			$select_page = '<p class="warning">' . esc_html__( 'You haven\'t created any pages.', 'jumpstart' ) . '</p>';

		}
	}

	/*
	 * Build raw content input.
	 */
	if ( in_array( 'raw', $options ) ) {

		$current_value = '';

		if ( ! empty( $val ) && ! empty( $val['raw'] ) ) {
			$current_value = $val['raw'];
		}

		if ( themeblvd_do_rich_editing() ) {

			add_filter( 'the_editor_content', 'format_for_editor', 10, 2 );

			/** This filter is documented in wp-includes/class-wp-editor.php */
			$current_value = apply_filters( 'the_editor_content', $current_value, 'tinymce' );

			remove_filter( 'the_editor_content', 'format_for_editor' );

			/*
			 * Prevent premature closing of textarea in case
			 * format_for_editor() didn't apply or the_editor_content
			 * filter did a wrong thing.
			 */
			$current_value = preg_replace( '#</textarea#i', '&lt;/textarea', $current_value );

			$raw_content = sprintf(
				'<textarea id="%s" class="tb-editor-input" name="%s" data-style="mini">%s</textarea>',
				esc_attr( uniqid( 'tb-editor-' . $id ) ),
				esc_attr( $name . '[' . $id . '][raw]' ),
				$current_value
			);

		} else {

			/*
			 * When rich editing is disabled, display
			 * standard <textarea>.
			 */
			$raw_content = '<div class="textarea-wrap">';

			$raw_content .= sprintf(
				'<textarea class="of-input" name="%s" cols="8" rows="8">%s</textarea>',
				esc_attr( $name . '[' . $id . '][raw]' ),
				esc_textarea( $current_value )
			);

			$raw_content .= '</div><!-- .textarea-wrap (end) -->';

		}

		/*
		 * Checkbox for the_content filter (added in v2.0.6).
		 * This should be checked if selected OR if the option
		 * never existed, for backwards compat.
		 */
		$checked = 'checked';

		if ( isset( $val['raw_format'] ) && ! $val['raw_format'] ) {
			$checked = '';
		}

		$raw_content .= sprintf(
			'<input class="checkbox of-input" type="checkbox" name="%s" %s>',
			esc_attr( $name . '[' . $id . '][raw_format]' ),
			$checked
		);

		$raw_content .= esc_html__( 'Apply WordPress automatic formatting.', 'jumpstart' );

	}

	/*
	 * Build primary outputs.
	 */

	$output = '<div class="column-content-types">';

	$output .= $select_type;

	if ( in_array( 'widget', $options ) ) {

		$link = sprintf(
			'<a href="%s" target="_blank">%s</a>',
			esc_url( admin_url( 'themes.php?page=themeblvd_widget_areas' ) ),
			esc_html__( 'Appearance > Widget Areas', 'jumpstart' )
		);

		$output .= '<div class="column-content-type column-content-type-widget">';

		$output .= $select_sidebar;

		// translators: 1: link to Widget Areas admin screen, added by the Theme Blvd Widget Areas plugin
		$output .= '<p class="note">' . sprintf( esc_html__( 'Select from your floating custom widget areas. In order for a custom widget area to be "floating" you must have it configured this way at %s.', 'jumpstart' ), $link ) . '</p>';

		$output .= '</div>';

	}

	if ( in_array( 'page', $options ) ) {

		$output .= '<div class="column-content-type column-content-type-page">';

		$output .= $select_page;

		$output .= '<p class="note">' . esc_html__( 'Select an external page to pull content from.', 'jumpstart' ) . '</p>';

		$output .= '</div>';

	}

	if ( in_array( 'raw', $options ) ) {

		$output .= '<div class="column-content-type column-content-type-raw">';

		$output .= $raw_content;

		$output .= '<p class="note">' . esc_html__( 'You can use basic HTML here, and most shortcodes.', 'jumpstart' ) . '</p>';

		$output .= '</div>';

	}

	$output .= '</div><!-- .column-content-types (end) -->';

	return $output;

}

/**
 * Create accordion panel for selecting conditional
 * assignments.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param  string $id     Unique ID for option.
 * @param  string $name   Prefix for form name value.
 * @param  array  $val    Currently saved data if exists.
 * @return string $output HTML for option.
 */
function themeblvd_conditionals_option( $id, $name, $val = null ) {

	/*
	 * Create array of all assignments separated
	 * by type to check against when displaying them
	 * back to the user.
	 */
	$assignments = array(
		'pages'                        => array(),
		'posts'                        => array(),
		'posts_in_category'            => array(),
		'categories'                   => array(),
		'tags'                         => array(),
		'portfolio_items'              => array(),
		'portfolio_items_in_portfolio' => array(),
		'portfolios'                   => array(),
		'portfolio_tags'               => array(),
		'portfolio_top'                => array(),
		'product_cat'                  => array(),
		'product_tags'                 => array(),
		'products_in_cat'              => array(),
		'product_top'                  => array(),
		'forums'                       => array(),
		'forum_top'                    => array(),
		'top'                          => array(),
		'custom'                       => '',
	);

	if ( is_array( $val ) && ! empty( $val ) ) {
		foreach ( $val as $key => $group ) {

			$item_id = $group['id'];

			switch ( $group['type'] ) {

				case 'page':
					$assignments['pages'][] = $item_id;
					break;

				case 'post':
					$assignments['posts'][] = $item_id;
					break;

				case 'posts_in_category':
					$assignments['posts_in_category'][] = $item_id;
					break;

				case 'category':
					$assignments['categories'][] = $item_id;
					break;

				case 'tag':
					$assignments['tags'][] = $item_id;
					break;

				case 'portfolio_item':
					$assignments['portfolio_items'][] = $item_id;
					break;

				case 'portfolio_items_in_portfolio':
					$assignments['portfolio_items_in_portfolio'][] = $item_id;
					break;

				case 'portfolio':
					$assignments['portfolios'][] = $item_id;
					break;

				case 'portfolio_tag':
					$assignments['portfolio_tags'][] = $item_id;
					break;

				case 'portfolio_top':
					$assignments['portfolio_top'][] = $item_id;
					break;

				case 'product_cat':
					$assignments['product_cat'][] = $item_id;
					break;

				case 'product_tag':
					$assignments['product_tags'][] = $item_id;
					break;

				case 'products_in_cat':
					$assignments['products_in_cat'][] = $item_id;
					break;

				case 'product_top':
					$assignments['product_top'][] = $item_id;
					break;

				case 'forum':
					$assignments['forums'][] = $item_id;
					break;

				case 'forum_top':
					$assignments['forum_top'][] = $item_id;
					break;

				case 'top':
					$assignments['top'][] = $item_id;
					break;

				case 'custom':
					$assignments['custom'] = $item_id;
					break;

			}
		}
	}

	$conditionals = themeblvd_conditionals_config();

	/*
	 * For WPML compatibility, we need to remove some filters WPML
	 * is adding while we retrieve pages and terms; we'll put them
	 * back at the end.
	 */
	if ( themeblvd_installed( 'wpml' ) ) {

		remove_filter( 'get_pages', array( $GLOBALS['sitepress'], 'exclude_other_language_pages2' ) );

		remove_filter( 'get_terms_args', array( $GLOBALS['sitepress'], 'get_terms_args_filter' ) );

		remove_filter( 'terms_clauses', array( $GLOBALS['sitepress'], 'terms_clauses' ) );

	}

	/*
	 * Start building output.
	 */
	$output = '<div class="accordion">';

	foreach ( $conditionals as $conditional ) {

		$output .= '<div class="element">';

		$output .= '<a href="#" class="element-trigger">' . esc_html( $conditional['name'] ) . '</a>';

		$output .= '<div class="element-content">';

		switch ( $conditional['id'] ) {

			// Pages posts, and tags.
			case 'pages':
			case 'posts':
			case 'tags':
			case 'portfolio_items':
			case 'portfolio_tags':
			case 'product_tags':
				if ( 'pages' === $conditional['id'] ) {

					$single = __( 'page', 'jumpstart' );
					$multiple = __( 'pages', 'jumpstart' );
					$field = 'page';

				} elseif ( 'posts' === $conditional['id'] ) {

					$single = __( 'post', 'jumpstart' );
					$multiple = __( 'posts', 'jumpstart' );
					$field = 'post';

				} elseif ( 'portfolio_items' === $conditional['id'] ) {

					$single = __( 'portfolio-item', 'jumpstart' );
					$multiple = __( 'portfolio items', 'jumpstart' );
					$field = 'portfolio_item';

				} elseif ( 'portfolio_tags' === $conditional['id'] ) {

					$single = __( 'portfolio-tag', 'jumpstart' );
					$multiple = __( 'portfolio tags', 'jumpstart' );
					$field = 'portfolio_tag';

				} else {

					$single = __( 'tag', 'jumpstart' );
					$multiple = __( 'tags', 'jumpstart' );
					$field = 'product_tags' === $conditional['id'] ? 'product_tag' : 'tag';

				}

				$assignment_list = '';

				if ( ! empty( $assignments[ $conditional['id'] ] ) ) {
					$assignment_list = implode( ', ', $assignments[ $conditional['id'] ] );
				}

				$output .= sprintf(
					'<textarea name="%s">%s</textarea>',
					esc_attr( $name . '[' . $id . '][' . $field . ']' ),
					$assignment_list
				);

				$output .= sprintf(
					'<p class="note">%s</p>',
					// translators: 1: multiple instance term for the item to be listed by the end-user
					esc_html( sprintf( __( 'Enter in a comma-separated list of the %s you\'d like to add to the assignments.', 'jumpstart' ), $multiple ) )
				);

				$output .= sprintf(
					'<p class="note"><em>%1$s: %2$s-1, %2$s-2, %2$s-3</em></p>',
					esc_html__( 'Example', 'jumpstart' ),
					esc_html( $single )
				);

				$output .= sprintf(
					'<p class="note"><em>%s</em></p>',
					// translators: 1: multiple instance term for the item to be listed by the end-user
					esc_html( sprintf( __( 'Note: Any %s entered that don\'t exist won\'t be saved.', 'jumpstart' ), $multiple ) )
				);

				break;

			// Categories.
			case 'categories':
			case 'posts_in_category':
			case 'portfolios':
			case 'portfolio_items_in_portfolio':
			case 'product_cat':
			case 'products_in_cat':
				$tax = 'category';

				if ( 'portfolios' === $conditional['id'] || 'portfolio_items_in_portfolio' === $conditional['id'] ) {

					$tax = 'portfolio';

				} elseif ( 'product_cat' === $conditional['id'] || 'products_in_cat' === $conditional['id'] ) {

					$tax = 'product_cat';

				}

				$terms = get_terms( $tax, array(
					'hide_empty' => false,
				) );

				if ( ! empty( $terms ) ) {

					$output .= '<ul>';

					foreach ( $terms as $term ) {

						$checked = false;

						if ( in_array( $term->slug, $assignments[ $conditional['id'] ] ) ) {
							$checked = true;
						}

						$output .= sprintf(
							'<li><input type="checkbox" %s name="%s" value="%s" /> <span>%s</span></li>',
							checked( $checked, true, false ),
							esc_attr( $name . '[' . $id . '][' . $conditional['field'] . '][]' ),
							$term->slug,
							$term->name
						);

					}

					$output .= '</ul>';

				} else {

					$output .= sprintf(
						'<p class="warning">%s</p>',
						esc_html( $conditional['empty'] )
					);

				}
				break;

			// Forums
			case 'forums':
				$forums = get_posts( array(
					'post_type' => 'forum',
					'orderby' => 'title',
					'order' => 'DESC',
				) );

				if ( $forums ) {

					$output .= '<ul>';

					foreach ( $forums as $forum ) {

						$checked = false;

						if ( in_array( $forum->post_name, $assignments[ $conditional['id'] ] ) ) {
							$checked = true;
						}

						$output .= sprintf(
							'<li><input type="checkbox" %s name="%s" value="%s" /> <span>%s</span></li>',
							checked( $checked, true, false ),
							esc_attr( $name . '[' . $id . '][' . $conditional['field'] . '][]' ),
							$forum->post_name,
							$forum->post_title
						);

					}

					$output .= '</ul>';

				}

				break;

			// Hierarchy
			case 'portfolio_top':
			case 'product_top':
			case 'forum_top':
			case 'top':
				if ( ! empty( $conditional['items'] ) ) {

					$output .= '<ul>';

					foreach ( $conditional['items'] as $item_id => $item_name ) {

						$checked = false;

						if ( in_array( $item_id, $assignments[ $conditional['id'] ] ) ) {
							$checked = true;
						}

						$output .= sprintf(
							'<li><input type="checkbox" %s name="%s" value="%s" /> <span>%s</span></li>',
							checked( $checked, true, false ),
							esc_attr( $name . '[' . $id . '][' . $conditional['field'] . '][]' ),
							$item_id,
							esc_html( $item_name )
						);

						$checked = false;

					}

					$output .= '</ul>';

				}
				break;

			// Custom
			case 'custom':
				/**
				 * Filters whether the end-user option for inputting PHP
				 * code gets inserted.
				 *
				 * Note that the actual call eval() happens in the Theme
				 * Blvd Widget Areas plugin, and not in the theme framework.
				 * But disabling the option here can prevent the end-user
				 * from every being able to configure it in the first place.
				 *
				 * @since Theme_Blvd 2.0.0
				 *
				 * @param bool Whether eval() option gets inserted.
				 */
				if ( ! apply_filters( 'themeblvd_disable_sidebar_custom_conditional', false ) ) {

					$link = '<a href="http://codex.wordpress.org/Conditional_Tags" target="_blank">' . esc_html__( 'conditional statement', 'jumpstart' ) . '</a>';

					$output .= sprintf(
						'<input type="text" name="%s" value="%s" />',
						esc_attr( $name . '[' . $id . '][' . $conditional['field'] . ']' ),
						esc_attr( $assignments['custom'] )
					);

					$output .= sprintf(
						'<p class="note">%s</p>',
						// translators: 1: link to the Conditional Tags docs in WordPress codex, w/the translated string "conditional tag" string
						sprintf( esc_html__( 'Enter in a custom %s.', 'jumpstart' ), $link )
					);

					$output .= sprintf(
						'<p class="note"><em>%s</em><br /><code>is_home()</code><br /><code>is_home() || is_single()</code><br /><code>"book" == get_post_type() || is_tax("author")</code></p>',
						esc_html__( 'Examples:', 'jumpstart' )
					);

					$output .= sprintf(
						'<p class="note"><em>%s</em></p>',
						esc_html__( 'Warning: Make sure you know what you\'re doing here. If you enter invalid conditional functions, you will most likely get PHP errors on the frontend of your website.', 'jumpstart' )
					);

				}
				break;

		}

		$output .= '</div><!-- .element-content (end) -->';
		$output .= '</div><!-- .element (end) -->';
	}
	$output .= '</div><!-- .accordion (end) -->';

	// Put filters back for WPML.
	if ( themeblvd_installed( 'wpml' ) ) {

		add_filter( 'get_pages', array( $GLOBALS['sitepress'], 'exclude_other_language_pages2' ), 10, 2 );

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
 * @since Theme_Blvd 2.0.0
 *
 * @param  string $id     Unique ID for option.
 * @param  string $name   Prefix for form name value.
 * @param  array  $val    Currently saved data if exists.
 * @return string $output HTML for option.
 */
function themeblvd_logo_option( $id, $name, $val ) {

	// Establish types of logos, available to the user.
	$types = array(
		'default'       => __( 'Default Logo', 'jumpstart' ),
		'title'         => __( 'Site Title', 'jumpstart' ),
		'title_tagline' => __( 'Site Title + Tagline', 'jumpstart' ),
		'custom'        => __( 'Custom Text', 'jumpstart' ),
		'image'         => __( 'Image', 'jumpstart' ),
	);

	if ( 'logo' === $id ) {
		unset( $types['default'] );
	}

	if ( 'mobile_logo' === $id || 'sticky_logo' === $id ) {
		unset( $types['title_tagline'] );
	}

	$current_value = '';

	if ( ! empty( $val ) && ! empty( $val['type'] ) ) {
		$current_value = $val['type'];
	}

	$select_type = sprintf(
		'<select name="%s">',
		esc_attr( $name . '[' . $id . '][type]' )
	);

	foreach ( $types as $key => $type ) {

		$select_type .= sprintf(
			'<option value="%s" %s>%s</option>',
			$key,
			selected( $current_value, $key, false ),
			esc_attr( $type )
		);

	}

	$select_type .= '</select>';

	/*
	 * Logo Type: Site Title
	 */

	$link = '<a href="options-general.php" target="_blank">' . esc_html__( 'here', 'jumpstart' ) . '</a>';

	$site_title  = '<p class="note">';

	$site_title .= esc_html__( 'Current Site Title', 'jumpstart' ) . ': <strong>';

	$site_title .= get_bloginfo( 'name' ) . '</strong><br><br>';

	// translators: 1: link to WordPress general settings page, w/translated text string "here"
	$site_title .= sprintf( esc_html__( 'You can change your site title and tagline by going %s.', 'jumpstart' ), $link );

	$site_title .= '</p>';

	/*
	 * Logo Type: Site Title + Tagline
	 */

	$site_title_tagline  = '<p class="note">';

	$site_title_tagline .= esc_html__( 'Current Site Title', 'jumpstart' ) . ': <strong>';

	$site_title_tagline .= get_bloginfo( 'name' ) . '</strong><br>';

	$site_title_tagline .= esc_html__( 'Current Tagline', 'jumpstart' ) . ': <strong>';

	$site_title_tagline .= get_bloginfo( 'description' ) . '</strong><br><br>';

	// translators: 1: link to WordPress general settings page, w/translated text string "here"
	$site_title_tagline .= sprintf(
		esc_html__( 'You can change your site title by going %s.', 'jumpstart' ),
		$link
	);

	$site_title_tagline .= '</p>';

	/*
	 * Logo Type: Custom Text
	 */

	$current_value = '';

	if ( ! empty( $val ) && ! empty( $val['custom'] ) ) {
		$current_value = $val['custom'];
	}

	$current_tagline = '';

	if ( ! empty( $val ) && ! empty( $val['custom_tagline'] ) ) {
		$current_tagline = $val['custom_tagline'];
	}

	$custom_text = sprintf(
		'<p><label class="inner-label"><strong>%s</strong></label>',
		esc_html__( 'Title', 'jumpstart' )
	);

	$custom_text .= sprintf(
		'<input type="text" name="%s" value="%s" /></p>',
		esc_attr( $name . '[' . $id . '][custom]' ),
		esc_attr( $current_value )
	);

	if ( 'mobile_logo' !== $id && 'sticky_logo' !== $id ) {

		$custom_text .= sprintf(
			'<p><label class="inner-label"><strong>%s</strong> (%s)</label>',
			esc_html__( 'Tagline', 'jumpstart' ),
			esc_html__( 'optional', 'jumpstart' )
		);

		$custom_text .= sprintf(
			'<input type="text" name="%s" value="%s" /></p>',
			esc_attr( $name . '[' . $id . '][custom_tagline]' ),
			esc_attr( $current_tagline )
		);

	}

	$custom_text .= sprintf(
		'<p class="note">%s</p>',
		esc_html__( 'Insert your custom text.', 'jumpstart' )
	);

	/*
	 * Logo Type: Image
	 */

	$current_value = array(
		'url' => '',
		'width' => '',
		'height' => '',
	);

	if ( isset( $val['image'] ) ) {
		$current_value['url'] = $val['image'];
	}

	if ( isset( $val['image_width'] ) ) {
		$current_value['width'] = $val['image_width'];
	}

	if ( isset( $val['image_height'] ) ) {
		$current_value['height'] = $val['image_height'];
	}

	$current_retina = array(
		'url' => '',
	);

	if ( is_array( $val ) && isset( $val['image_2x'] ) ) {
		$current_retina = array(
			'url' => $val['image_2x'],
		);
	}

	// Add standard image upload option.
	$image_upload  = '<div class="section-upload image-standard">';

	$image_upload .= '<label class="inner-label"><strong>' . esc_html__( 'Standard Image', 'jumpstart' ) . '</strong></label>';

	$image_upload .= themeblvd_media_uploader( array(
		'option_name'  => $name,
		'type'         => 'logo',
		'id'           => $id,
		'value'        => $current_value['url'],
		'value_width'  => $current_value['width'],
		'value_height' => $current_value['height'],
		'name'         => 'image',
	) );

	$image_upload .= '</div>';

	// Add 2x HiDPI image upload option.
	$image_upload .= '<div class="section-upload image-2x">';

	$image_upload .= '<label class="inner-label"><strong>' . esc_html__( 'HiDPI-optimized Image (optional)', 'jumpstart' ) . '</strong></label>';

	$image_upload .= themeblvd_media_uploader( array(
		'option_name' => $name,
		'type'        => 'logo_2x',
		'id'          => $id,
		'value'       => $current_retina['url'],
		'name'        => 'image_2x',
	) );

	$image_upload .= '</div>';

	/*
	 * Piece together final output of all logo types.
	 */
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
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $id     Unique ID for option.
 * @param  string $name   Prefix for form name value.
 * @param  array  $val    Currently saved data if exists.
 * @return string $output HTML for option.
 */
function themeblvd_button_option( $id, $name, $val ) {

	$output = '';

	// Setup button background color.
	$bg = '#ffffff';

	$bg_def = '#ffffff';

	if ( ! empty( $val['bg'] ) ) {
		$bg = $val['bg'];
	}

	if ( ! empty( $value['std']['bg'] ) ) {
		$bg_def = $value['std']['bg'];
	}

	$output .= '<div class="color bg hide">';

	$output .= sprintf(
		'<input id="%s_bg" name="%s" type="text" value="%s" class="color-picker" data-default-color="%s" />',
		esc_attr( $id ),
		esc_attr( $name . '[' . $id . '][bg]' ),
		esc_attr( $bg ),
		esc_attr( $bg_def )
	);

	$output .= '</div><!-- .color.bg (end) -->';

	// Setup background hover color for button.
	$bg_hover = '#ebebeb';

	$bg_hover_def = '#ebebeb';

	if ( ! empty( $val['bg_hover'] ) ) {
		$bg_hover = $val['bg_hover'];
	}

	if ( ! empty( $value['std']['bg_hover'] ) ) {
		$bg_hover_def = $value['std']['bg_hover'];
	}

	$output .= '<div class="color bg_hover">';

	$output .= sprintf(
		'<input id="%s_bg_hover" name="%s" type="text" value="%s" class="color-picker" data-default-color="%s" />',
		esc_attr( $id ),
		esc_attr( $name . '[' . $id . '][bg_hover]' ),
		esc_attr( $bg_hover ),
		esc_attr( $bg_hover_def )
	);

	$output .= '</div><!-- .color.bg_hover (end) -->';

	// Setup border color for button.
	$border = '#cccccc';

	$border_def = '#cccccc';

	if ( ! empty( $val['border'] ) ) {
		$border = $val['border'];
	}

	if ( ! empty( $value['std']['border'] ) ) {
		$border_def = $value['std']['border'];
	}

	$output .= '<div class="color border hide">';

	$output .= sprintf(
		'<input id="%s_border" name="%s" type="text" value="%s" class="color-picker" data-default-color="%s" />',
		esc_attr( $id ),
		esc_attr( $name . '[' . $id . '][border]' ),
		esc_attr( $border ),
		esc_attr( $border_def )
	);

	$output .= '</div><!-- .color.border (end) -->';

	// Setup text color for button.
	$text = '#333333';

	$text_def = '#333333';

	if ( ! empty( $val['text'] ) ) {
		$text = $val['text'];
	}

	if ( ! empty( $value['std']['text'] ) ) {
		$text_def = $value['std']['text'];
	}

	$output .= '<div class="color text">';

	$output .= sprintf(
		'<input id="%s_text" name="%s" type="text" value="%s" class="color-picker" data-default-color="%s" />',
		esc_attr( $id ),
		esc_attr( $name . '[' . $id . '][text]' ),
		esc_attr( $text ),
		esc_attr( $text_def )
	);

	$output .= '</div><!-- .color.text (end) -->';

	// Setup text hover color for button.
	$text_hover = '#333333';

	$text_hover_def = '#333333';

	if ( ! empty( $val['text_hover'] ) ) {
		$text_hover = $val['text_hover'];
	}

	if ( ! empty( $value['std']['text_hover'] ) ) {
		$text_hover_def = $value['std']['text_hover'];
	}

	$output .= '<div class="color text_hover">';

	$output .= sprintf(
		'<input id="%s_text_hover" name="%s" type="text" value="%s" class="color-picker" data-default-color="%s" />',
		esc_attr( $id ),
		esc_attr( $name . '[' . $id . '][text_hover]' ),
		esc_attr( $text_hover ),
		esc_attr( $text_hover_def )
	);

	$output .= '</div><!-- .color.text_hover (end) -->';

	/*
	 * Setup checkbox for whether to include background color.
	 * When unchecked, background will be a transparent and
	 * option above to select a background color will be hidden.
	 */
	$include_bg = 1;

	if ( isset( $val['include_bg'] ) ) {
		$include_bg = $val['include_bg'];
	}

	$output .= '<div class="include bg clearfix">';

	$output .= sprintf(
		'<div class="include-controls"><input id="%s_include_bg" class="checkbox of-input" type="checkbox" name="%s" %s /></div>',
		esc_attr( $id ),
		esc_attr( $name . '[' . $id . '][include_bg]' ),
		checked( $include_bg, 1, false )
	);

	$output .= '<div class="include-explain">' . esc_html__( 'Button has background color', 'jumpstart' ) . '</div>';

	$output .= '</div><!-- .include (end) -->';

	/*
	 * Setup checkbox for whether to include border. When unchecked,
	 * button will have no border and option above to select a border
	 * color will be hidden.
	 */
	$include_border = 1;

	if ( isset( $val['include_border'] ) ) {
		$include_border = $val['include_border'];
	}

	$output .= '<div class="include border clearfix">';

	$output .= sprintf(
		'<div class="include-controls"><input id="%s_include_border" class="checkbox of-input" type="checkbox" name="%s" %s /></div>',
		esc_attr( $id ),
		esc_attr( $name . '[' . $id . '][include_border]' ),
		checked( $include_border, 1, false )
	);

	$output .= '<div class="include-explain">' . esc_html__( 'Button has border', 'jumpstart' ) . '</div>';

	$output .= '</div><!-- .include (end) -->';

	return $output;

}

/**
 * Option for selecting a sidebar layout that gets
 * inserted into the "Page Attributes" meta box
 * via the action "page_attributes_meta_box_template".
 *
 * This function was re-written in Theme_Blvd 2.7.0
 * to simply output a sidebar layout <select> menu,
 * and have the parameters passed in that are passed
 * from WordPress's "page_attributes_meta_box_template"
 * action hook.
 *
 * This function is hooked to:
 * 1. `page_attributes_meta_box_template` - 10
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param string  $template Current page template selected.
 * @param WP_Post $post     Post object for current post being edited.
 */
function themeblvd_sidebar_layout_dropdown( $template, $post ) {

	wp_nonce_field(
		'themeblvd-save-page-atts_' . $post->ID,
		'themeblvd-save-page-atts_' . $post->ID,
		false // No need for _wp_http_referer; it already exists on Edit Page screen.
	);

	$layouts = themeblvd_sidebar_layouts();

	$current_layout = get_post_meta( $post->ID, '_tb_sidebar_layout', true );

	$excluded_templates = array( 'template_builder.php', 'template_blank.php' );

	echo "\n<br>\n";

	if ( in_array( $template, $excluded_templates, true ) ) {

		echo "<select id=\"tb-sidebar-layout\" name=\"_tb_sidebar_layout\" style=\"display: none\">\n";

	} else {

		echo "<select id=\"tb-sidebar-layout\" name=\"_tb_sidebar_layout\">\n";

	}

	printf(
		"\t<option value='default'%s>%s</option>\n",
		selected( 'default', $current_layout, false ),
		__( 'Default Sidebar Layout', 'jumpstart' )
	);

	foreach ( $layouts as $layout ) {

		printf(
			"\t<option value='%s'%s>%s</option>\n",
			$layout['id'],
			selected( $layout['id'], $current_layout, false ),
			esc_html( $layout['name'] )
		);

	}

	echo "</select>\n";

}

/**
 * Option for selecting a custom layout that gets
 * inserted into out Hi-jacked "Page Attributes"
 * meta box.
 *
 * @since Theme_Blvd 2.0.0
 *
 * @param  string $layout Current custom layout.
 * @return string $output HTML to output.
 */
function themeblvd_custom_layout_dropdown( $layout = null ) {

	/*
	 * In order to build this dropdown, the Theme Blvd Layout
	 * Builder plugin must be active.
	 */
	if ( ! defined( 'TB_BUILDER_PLUGIN_VERSION' ) ) {

		$message = sprintf(
			// translators: 1: link to Theme Blvd Layout Builder plugin
			esc_html__( 'In order to use the "Custom Layout" template, you must have the %s plugin installed.', 'jumpstart' ),
			'<a href="http://wordpress.org/extend/plugins/theme-blvd-layout-builder" target="_blank">Theme Blvd Layout Builder</a>'
		);

		return sprintf(
			'<p class="tb_custom_layout"><em>%s</em></p>',
			$message
		);

	}

	$custom_layouts = get_posts( array(
		'post_type'   => 'tb_layout',
		'numberposts' => -1,
	) );

	$output = '<p><strong>' . esc_html__( 'Custom Layout', 'jumpstart' ) . '</strong></p>';

	if ( ! empty( $custom_layouts ) ) {

		$output .= '<select name="_tb_custom_layout">';

		foreach ( $custom_layouts as $custom_layout ) {

			$output .= sprintf(
				'<option value="%s" %s>%s</option>',
				$custom_layout->post_name,
				selected( $custom_layout->post_name, $layout, false ),
				$custom_layout->post_title
			);

		}

		$output .= '</select>';

	} else {

		$output .= '<p class="tb_custom_layout"><em>' . esc_html__( 'You haven\'t created any custom layouts in the Layout builder yet.', 'jumpstart' ) . '</em></p>';

	}

	return $output;

}

/**
 * Outputs icon browser in hidden modal window that can
 * be accessed by other options.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args Options for setting up icon browser.
 */
function themeblvd_icon_browser( $args = array() ) {

	$args = wp_parse_args( $args, array(
		'type' => 'vector', // Currently, only "vector" is a valid type.
	) );

	?>
	<div id="themeblvd-icon-browser-<?php echo $args['type']; ?>" class="themeblvd-modal-wrap themeblvd-icon-browser hide">
		<div class="themeblvd-modal medium-modal media-modal wp-core-ui tb-modal-with-icon-browser">

			<button type="button" class="media-modal-close">
				<span class="media-modal-icon">
					<span class="screen-reader-text">
						<?php esc_html_e( 'Close icon browser panel', 'jumpstart' ); ?>
					</span>
				</span>
			</button>

			<div class="media-modal-content">
				<div class="media-frame wp-core-ui hide-menu hide-router">

					<div class="media-frame-title">
						<h1><?php esc_html_e( 'Select an Icon', 'jumpstart' ); ?></h1>
					</div><!-- .media-frame-title (end) -->

					<div class="media-frame-content">
						<div class="media-frame-content-inner">
							<div class="content-mitt">
								<div class="icon-browser">

									<input class="icon-search-input" type="text" placeholder="<?php esc_html_e( 'Search for an icon...', 'jumpstart' ); ?>">

									<?php foreach ( themeblvd_get_icon_types() as $prefix => $type ) : ?>

										<h2><?php echo ucfirst( $type ); ?></h2>

										<?php $icons = themeblvd_get_icons( $type ); ?>

										<?php foreach ( $icons as $icon ) : ?>

											<?php
											/**
											 * Filters the value to be inserted for an icon in the
											 * icon browser.
											 *
											 * By default, this value will be structured with a Font
											 * Awesome style class and icon class, like `fas fa-user`.
											 *
											 * @since Theme_Blvd 2.7.4
											 *
											 * @param string $icon_value Icon value.
											 * @param string $icon       Icon name.
											 * @param string $prefix     Style class, like `fas`.
											 * @param string $type       Style type, like `solid`.
											 */
											$icon_value = apply_filters( 'themeblvd_icon_browser_value', $prefix . ' fa-' . $icon, $icon, $prefix, $type );
											?>

											<a href="#" class="select-icon icon-<?php echo esc_attr( $icon ); ?> select-vector-icon tb-tooltip-link" data-icon="<?php echo esc_attr( $icon_value ); ?>" data-tooltip-text="<?php echo esc_attr( $icon ); ?>">

												<?php
												/**
												 * Filters the HTML output for icons in the icon browser.
												 *
												 * @since Theme_Blvd 2.7.4
												 *
												 * @param string $icon       Icon HTML output.
												 * @param string $icon_value Icon value.
												 * @param string $icon       Icon name.
												 * @param string $prefix     Style class, like `fas`.
												 * @param string $type       Style type, like `solid`.
												 */
												$icon = apply_filters( 'themeblvd_icon_browser_icon', '', $icon_value, $icon, $prefix, $type );

												if ( ! $icon ) {

													$icon = sprintf( '<i class="%s fa-fw fa-2x"></i>', esc_attr( $icon_value ) );

												}

												echo $icon;
												?>

											</a>

										<?php endforeach; ?>

										<hr>

									<?php endforeach; ?>

								</div>
							</div>
						</div><!-- .media-frame-content-inner (end) -->
					</div><!-- .media-frame-content (end) -->

					<div class="media-frame-toolbar">

						<div class="media-toolbar">
							<div class="icon-selection-wrap">
								<input type="hidden" class="icon-selection" value="" />
								<span class="icon-preview"></span>
								<span class="icon-text-preview"></span>
							</div>
							<div class="media-toolbar-primary">
								<a href="#" class="button media-button button-primary button-large media-button-insert"><?php esc_html_e( 'Use Icon', 'jumpstart' ); ?></a>
							</div>
						</div><!-- .media-toolbar (end) -->

					</div><!-- .media-frame-toolbar (end) -->

				</div><!-- .media-frame (end) -->
			</div><!-- .media-modal-content (end) -->

		</div><!-- .media-modal (end) -->
	</div><!-- #themeblvd-icon-browser-{type}.themeblvd-modal-wrap.themeblvd-icon-browser (end) -->
	<?php

}

/**
 * Outputs post browser in hidden modal window that can
 * be accessed by other options.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args Options for setting up post browser (currently not being used for anything).
 */
function themeblvd_post_browser( $args = array() ) {
	?>
	<div id="themeblvd-post-browser" class="themeblvd-modal-wrap themeblvd-post-browser hide">
		<div class="themeblvd-modal medium-modal media-modal wp-core-ui tb-modal-with-icon-browser">

			<button type="button" class="media-modal-close">
				<span class="media-modal-icon">
					<span class="screen-reader-text">
						<?php esc_html_e( 'Close post browser panel', 'jumpstart' ); ?>
					</span>
				</span>
			</button>

			<div class="media-modal-content">
				<div class="media-frame wp-core-ui hide-menu hide-router">

					<div class="media-frame-title">
						<h1><?php esc_html_e( 'Find Post or Page ID', 'jumpstart' ); ?></h1>
					</div><!-- .media-frame-title (end) -->

					<div class="media-frame-content">
						<div class="media-frame-content-inner">
							<div class="tb-options-wrap content-mitt">
								<div class="post-browser">

									<div class="post-browser-head clearfix">
										<div class="search-box">
											<input type="search" id="post-search-input" name="s" value="">
											<input type="submit" name="" id="search-submit" class="button" value="<?php esc_attr_e( 'Search Posts and Pages', 'jumpstart' ); ?>">
										</div>
										<span class="tb-loader ajax-loading">
											<i class="tb-icon-spinner"></i>
										</span>
									  </div>

									<div class="search-results ajax-mitt"></div>

								</div><!-- .post-browser (end) -->
							</div><!-- .content-mitt (end) -->
						</div><!-- .media-frame-content-inner (end) -->
					</div><!-- .media-frame-content (end) -->

				</div><!-- .media-frame (end) -->
			</div><!-- .media-modal-content (end) -->

		</div><!-- .media-modal (end) -->
	</div><!-- #themeblvd-post-browser.themeblvd-modal-wrap.themeblvd-post-browser (end) -->
	<?php
}

/**
 * Ajax action function for searching posts and
 * displaying table of results.
 *
 * @since Theme_Blvd 2.5.0
 */
function themeblvd_ajax_post_browser() {

	$posts = get_posts( array(
		's'         => $_POST['data'],
		'post_type' => 'any',
	) );

	?>
	<table class="widefat">
		<thead>
			<tr>
				<th class="head-title">
					<?php esc_html_e( 'Title', 'jumpstart' ); ?>
				</th>
				<th class="head-slug">
					<?php esc_html_e( 'Slug', 'jumpstart' ); ?>
				</th>
				<th class="head-type">
					<?php esc_html_e( 'Type', 'jumpstart' ); ?>
				</th>
				<th class="head-id">
					<?php esc_html_e( 'ID', 'jumpstart' ); ?>
				</th>
				<th class="head-select">
					<?php esc_html_e( 'Select', 'jumpstart' ); ?>
				</th>
			</tr>
		</thead>

		<?php if ( $posts ) : ?>

			<?php
			foreach ( $posts as $post ) :
				$type = get_post_type_object( $post->post_type );
				?>

				<tr>
					<td><?php echo $post->post_title; ?></td>
					<td><?php echo $post->post_name; ?></td>
					<td><?php echo $type->labels->singular_name; ?></td>
					<td><?php echo $post->ID; ?></td>
					<td><a href="#" data-post-id="<?php echo $post->ID; ?>" class="select-post button-secondary"><?php esc_html_e( 'Use Post', 'jumpstart' ); ?></a></td>
				</tr>

			<?php endforeach; ?>

		<?php else : ?>

			<tr>
				<td colspan="5"><?php esc_html_e( 'No posts found.', 'jumpstart' ); ?></td>
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
 * @since Theme_Blvd 2.5.0
 *
 * @param array $args Options for setting up texture browser (currently not being used for anything).
 */
function themeblvd_texture_browser( $args = array() ) {

	// Get textures from framework.
	$textures = themeblvd_get_textures();

	?>
	<div id="themeblvd-texture-browser" class="themeblvd-modal-wrap themeblvd-texture-browser hide">
		<div class="themeblvd-modal medium-modal media-modal wp-core-ui tb-modal-with-icon-browser">

			<button type="button" class="media-modal-close">
				<span class="media-modal-icon">
					<span class="screen-reader-text">
						<?php esc_html_e( 'Close texture browser panel', 'jumpstart' ); ?>
					</span>
				</span>
			</button>

			<div class="media-modal-content">
				<div class="media-frame wp-core-ui hide-menu hide-router">

					<div class="media-frame-title">
						<h1><?php esc_html_e( 'Select a texture', 'jumpstart' ); ?></h1>
						<input id="texture-browser-perview-color" type="text" value="#00366d" data-default-color="#00366d" />
					</div><!-- .media-frame-title (end) -->

					<div class="media-frame-content">
						<div class="media-frame-content-inner">
							<div class="content-mitt">
								<div class="icon-browser">

									<div class="texture-header">
										<h2><?php esc_html_e( 'Dark Textures', 'jumpstart' ); ?></h2>
									</div>

									<?php
									foreach ( $textures as $id => $texture ) {

										if ( 'divider' === $id ) {

											echo '<div class="texture-header">';
											echo '<h2>' . esc_html__( 'Light Textures', 'jumpstart' ) . ' <span>(' . esc_html__( 'Above textures tweaked slightly to be overlaid on lighter background colors', 'jumpstart' ) . ')</span></h2>';
											echo '</div>';

											continue;

										}

										$size = '';

										if ( isset( $texture['size'] ) ) {
											$size = $texture['size'];
										}

										echo '<div class="select-texture-wrap">';

										printf(
											'<a href="#" class="select-texture texture-%1$s" data-texture="%1$s" data-texture-name="%2$s"><span style="background-image: url(%3$s); background-position: %4$s; background-repeat: %5$s; background-size: %6$s;"></span></a>',
											$id,
											esc_html( $texture['name'] ),
											esc_url( $texture['url'] ),
											$texture['position'],
											$texture['repeat'],
											$size
										);

										printf(
											'<p class="texture-label">%s</p>',
											str_replace( 'Light ', '', esc_html( $texture['name'] ) )
										);

										echo '</div><!-- .select-texture-wrap (end) -->';

									}
									?>
								</div><!-- .icon-browser (end) -->
							</div><!-- .content-mitt (end) -->
						</div><!-- .media-frame-content-inner (end) -->
					</div><!-- .media-frame-content (end) -->

					<div class="media-frame-toolbar">

						<div class="media-toolbar-secondary">
							<input type="hidden" class="texture-selection" value="" />
							<span class="current-texture"></span>
						</div><!-- .media-toolbar-secondary (end) -->

						<div class="media-toolbar">
							<div class="media-toolbar-primary">
								<a href="#" class="button media-button button-primary button-large media-button-insert"><?php esc_html_e( 'Use Texture', 'jumpstart' ); ?></a>
							</div>
						</div><!-- .media-toolbar (end) -->

					</div><!-- .media-frame-toolbar (end) -->

				</div><!-- .media-frame (end) -->
			</div><!-- .media-modal-content (end) -->

		</div><!-- .media-modal (end) -->
	</div><!-- #themeblvd-texture-browser.themeblvd-modal-wrap.themeblvd-texture-browser (end) -->
	<?php
}

/**
 * Dispay set of preset option values user can click
 * to populate part of the a options page.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array  $args         Options for displaying preset style selections.
 * @param  string $option_name  Prefix for all field name attributes (currently not used).
 * @return string $output       HTML output for preset style selection.
 */
function themeblvd_display_presets( $args, $option_name = '' ) {

	$args = wp_parse_args( $args, array(
		'id'    => '',
		'sets'  => array(),
		'level' => 1,
	) );

	$class = 'tb-presets level-' . $args['level'];

	foreach ( $args['sets'] as $set ) {
		if ( ! empty( $set['icon'] ) ) {
			$class .= ' has-images';
			break;
		}
	}

	$output = '<div class="' . $class . '">';

	if ( $args['sets'] ) {

		$output .= '<ul class="presets-menu">';

		foreach ( $args['sets'] as $id => $set ) {

			$output .= '<li>';

			if ( ! empty( $set['icon'] ) ) {

				$item = '<img src="' . esc_url( $set['icon'] ) . '" ';

				if ( ! empty( $set['icon_width'] ) ) {
					$item .= 'width="' . esc_attr( $set['icon_width'] ) . '" ';
				}

				if ( ! empty( $set['icon_height'] ) ) {
					$item .= 'height="' . esc_attr( $set['icon_height'] ) . '" ';
				}

				if ( ! empty( $set['icon_style'] ) ) {
					$item .= 'style="' . esc_attr( $set['icon_style'] ) . '" ';
				}

				$item .= '/>';

			} else {

				if ( ! empty( $set['name'] ) ) {
					$item = $set['name'];
				} else {
					$item = $id;
				}
			}

			$tooltip = esc_html__( 'Apply Preset', 'jumpstart' );

			if ( ! empty( $set['name'] ) ) {
				$tooltip .= ': ' . $set['name'];
			}

			$output .= sprintf( '<a href="#" class="tb-tooltip-link" data-set="%s" data-id="%s" data-tooltip-text="%s">%s</a>', $id, $args['id'], $tooltip, $item );

			$output .= '</li>';

		}

		$output .= '</ul>';

	}

	$output .= '</div><!-- .option-presets (end) -->';

	return $output;

}

/**
 * Dispay theme info at the base of option pages
 * created with the Theme_Blvd_Options_Page class.
 *
 * This function is hooked as the default callback
 * for the action themeblvd_options_footer_text.
 *
 * This function is hooked to:
 * 1. `themeblvd_options_footer_text` - 10
 *
 * @since Theme_Blvd 2.2.0
 */
function themeblvd_options_footer_text_default() {

	$theme = get_template();

	$theme_data = wp_get_theme( get_template() );

	/**
	 * Filter changelog URL.
	 *
	 * @since Theme_Blvd 2.0.0
	 *
	 * @param string URL to changelog.
	 * @param string Template slug retrieved from get_template().
	 */
	$url = apply_filters( 'themeblvd_changelog_link', 'http://themeblvd.com/changelog/?theme=' . $theme, $theme );

	$changelog = sprintf(
		'<a href="%s" target="_blank">%s</a>',
		$url,
		esc_html__( 'Changelog', 'jumpstart' )
	);

	printf(
		'<i class="tb-icon-logo-stroke wp-ui-text-highlight"></i> %s <strong>%s</strong> with Theme Blvd Framework <strong>%s</strong> &mdash; %s',
		$theme_data->get( 'Name' ),
		$theme_data->get( 'Version' ),
		TB_FRAMEWORK_VERSION,
		$changelog
	);
}
