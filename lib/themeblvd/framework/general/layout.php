<?php
/**
 * Frontend Layout
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.5.0
 */

/**
 * Display set of elements from a custom layout.
 *
 * Note: When the $context of an element set is
 * `block` it means it's a set of elements
 * within a column.
 *
 * @since @@name-framework 2.5.0
 *
 * @param string $section_id Section ID, where all of these elements exist.
 * @param array  $elements   All elements to disolay within section.
 * @param string $context    Context for elements, `element` or `block`.
 * @param int    $max        Maximum width of container.
 */
function themeblvd_elements( $section_id, $elements, $context = 'element', $max = 0 ) {

	if ( $elements ) {

		/*
		 * Maximum width of container for elements. If the
		 * context is `block` this shouldn't be empty to start.
		 */
		if ( ! $max ) {

			$max = themeblvd_get_max_width( 'element' );

		}

		$i = 1;

		$total = count( $elements );

		foreach ( $elements as $id => $element ) {

			$args = array(
				'section'   => $section_id,
				'id'        => $id,
				'num'       => $i,
				'total'     => $total,
				'context'   => $context,
				'max_width' => $max,
			);

			if ( isset( $element['type'] ) ) {

				$args['type'] = $element['type'];

			}

			if ( isset( $element['label'] ) ) {

				$args['label'] = $element['label'];

			}

			if ( isset( $element['options'] ) ) {

				$args['options'] = $element['options'];

			}

			if ( isset( $element['display'] ) ) {

				$args['display'] = $element['display'];

			}

			themeblvd_element( $args );

			$i++;

		}
	}
}

/**
 * Display an individual element.
 *
 * @see themeblvd_elements()
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args {
 *     Element arguments.
 *
 *     @type string $section   Section ID, containing this element.
 *     @type string $id        Element ID.
 *     @type string $type      Element type.
 *     @type string $label     Element label.
 *     @type string $options   Element settings, different for each element type.
 *     @type string $display   Element display settings.
 *     @type int    $num       Element's sequential count, among the other element its displayed with.
 *     @type int    $total     Total number of elements this element is displayed with.
 *     @type string $context   Context for elements, `element` or `block`.
 *     @type string $max_width Maximum width of container.
 * }
 */
function themeblvd_element( $args ) {

	$args = wp_parse_args( $args, array(
		'section'   => '',
		'id'        => '',
		'type'      => '',
		'label'     => '',
		'options'   => array(),
		'display'   => array(),
		'num'       => 1,
		'total'     => 1,
		'context'   => 'element',
		'max_width' => '',
	));

	$args['options']['max_width'] = $args['max_width'];

	/**
	 * Filters the options passed for an individual
	 * element from the layout builder.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param array $options Element options.
	 */
	$args['options'] = apply_filters(
		'themeblvd_element_' . $args['type'] . '_options',
		$args['options']
	);

	/**
	 * Filters the display options passed for
	 * an individual element from the layout
	 * builder.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param array $display Element display options.
	 */
	$args['display'] = apply_filters(
		'themeblvd_element_' . $args['type'] . '_display',
		$args['display']
	);

	/**
	 * Fires before the start of any custom layout
	 * element's HTML output.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $id      Element ID.
	 * @param string $options Element settings, different for each element type.
	 * @param string $section Section ID, containing this element.
	 * @param string $display Element display settings.
	 * @param string $context Context for elements, `element` or `block`.
	 */
	do_action(
		'themeblvd_element_' . $args['type'] . '_before',
		$args['id'],
		$args['options'],
		$args['section'],
		$args['display'],
		$args['context']
	);

	$class = implode( ' ', themeblvd_get_element_class( $args ) );

	// Open element.
	printf(
		'<div id="%s" class="%s" style="%s">',
		esc_attr( $args['id'] ),
		esc_attr( $class ),
		themeblvd_get_display_inline_style( $args['display'] )
	);

	/**
	 * Fires just within the start of any custom
	 * layout element's HTML output.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $id      Element ID.
	 * @param string $options Element settings, different for each element type.
	 * @param string $section Section ID, containing this element.
	 * @param string $display Element display settings.
	 * @param string $context Context for elements, `element` or `block`.
	 */
	do_action(
		'themeblvd_element_' . $args['type'] . '_top',
		$args['id'],
		$args['options'],
		$args['section'],
		$args['display'],
		$args['context']
	);

	// Display element.
	switch ( $args['type'] ) {

		/*------------------------------------------------------*/
		/* Layout (layout.php)
		/*------------------------------------------------------*/

		case 'columns':
		case 'jumbotron_slider':
			if ( themeblvd_get_att( 'footer_sync' ) ) {

				$layout_id = themeblvd_config( 'bottom_builder_post_id' );

			} else {

				$layout_id = themeblvd_config( 'builder_post_id' );

			}

			if ( 'jumbotron_slider' === $args['type'] ) {

				$hero_args = array(
					'section'    => $args['section'],
					'layout_id'  => $layout_id,
					'element_id' => $args['id'],
				);

				themeblvd_jumbotron_slider( array_merge( $hero_args, $args['options'] ) );

			} else {

				$num = 1;

				if ( isset( $args['options']['setup'] ) && is_string( $args['options']['setup'] ) ) {

					$num = count( explode( '-', $args['options']['setup'] ) );

				}

				$col_args = array(
					'section'    => $args['section'],
					'layout_id'  => $layout_id,
					'element_id' => $args['id'],
					'num'        => $num,
					'widths'     => $args['options']['setup'],
					'stack'      => $args['options']['stack'],
					'height'     => $args['options']['height'],
					'align'      => $args['options']['align'],
				);

				themeblvd_columns( $col_args );

			}
			break;

		/*------------------------------------------------------*/
		/* Content (content.php)
		/*------------------------------------------------------*/

		// Content (direct input)
		case 'content':
			themeblvd_content_block( $args['options'] );
			break;

		// Current (from post content)
		case 'current':
			themeblvd_post_content();
			break;

		// Custom Field
		case 'custom_field':
			$value = get_post_meta( themeblvd_config( 'id' ), $args['options']['key'], true );

			if ( $args['options']['wpautop'] ) {

				echo themeblvd_get_content( $value );

			} else {

				echo do_shortcode( themeblvd_kses( $value ) );

			}
			break;

		// External Page/Post content
		case 'external':
			themeblvd_post_content( intval( $args['options']['post_id'] ) );
			break;

		// Headline
		case 'headline':
			themeblvd_headline( $args['options'] );
			break;

		// HTML
		case 'html':
			printf(
				'<div class="entry-content">%s</div>',
				themeblvd_kses( $args['options']['html'] )
			);
			break;

		// Quote
		case 'quote':
			themeblvd_blockquote( $args['options'] );
			break;

		// Widget area
		case 'widget':
			themeblvd_widgets( $args['options']['sidebar'], $args['context'] );
			break;

		/*------------------------------------------------------*/
		/* Components (components.php)
		/*------------------------------------------------------*/

		// Alert
		case 'alert':
			themeblvd_alert( $args['options'] );
			break;

		// Divider
		case 'divider':
			themeblvd_divider( $args['options'] );
			break;

		// Google Map
		case 'map':
			themeblvd_map( $args['options'] );
			break;

		// Icon Box
		case 'icon_box':
			themeblvd_icon_box( $args['options'] );
			break;

		// Hero Unit
		case 'jumbotron':
			themeblvd_jumbotron( $args['options'] );
			break;

		// Panel
		case 'panel':
			themeblvd_panel( $args['options'] );
			break;

		// Partner Logos
		case 'partners':
			themeblvd_logos( $args['options'] );
			break;

		// Pricing Table
		case 'pricing_table':
			themeblvd_pricing_table( $args['options']['columns'], $args['options'] );
			break;

		// Promo Box
		case 'slogan':
			themeblvd_slogan( $args['options'] );
			break;

		// Tabs
		case 'tabs':
			themeblvd_tabs( $args['id'], $args['options'] );
			break;

		// Team Member
		case 'team_member':
			themeblvd_team_member( $args['options'] );
			break;

		// Testimonial
		case 'testimonial':
			themeblvd_testimonial( $args['options'] );
			break;

		// Testimonial Slider
		case 'testimonial_slider':
			themeblvd_testimonial_slider( $args['options'] );
			break;

		// Toggles
		case 'toggles':
			themeblvd_toggles( $args['id'], $args['options'] );
			break;

		/*------------------------------------------------------*/
		/* Posts (loop.php)
		/*------------------------------------------------------*/

		// Blog
		case 'blog':
			$args['options']['context'] = 'blog';

			$args['options']['element'] = true;

			themeblvd_loop( $args['options'] );

			break;

		// Post Grid
		case 'post_grid':
			$args['options']['context'] = 'grid';

			$args['options']['element'] = true;

			themeblvd_loop( $args['options'] );

			break;

		// Post List
		case 'post_list':
			$args['options']['context'] = 'list';

			$args['options']['element'] = true;

			themeblvd_loop( $args['options'] );

			break;

		// Post Showcase
		case 'post_showcase':
			$args['options']['context'] = 'showcase';

			$args['options']['element'] = true;

			themeblvd_loop( $args['options'] );

			break;

		// Post Slider
		case 'post_slider':
		case 'post_slider_popout':
			$args['options']['element'] = true;

			themeblvd_post_slider( $args['options'] );

			break;

		// Mini Post List
		case 'mini_post_list':
			$args['options']['element'] = true;

			themeblvd_mini_post_list( $args['options'], $args['options']['thumbs'], $args['options']['meta'] );

			break;

		// Mini Post Grid
		case 'mini_post_grid':
			$gallery = '';

			if ( 'gallery' === $args['options']['source'] ) {

				$gallery = $args['options']['gallery'];

				if ( ! $gallery ) {

					$gallery = 'error';

				}
			}

			$args['options']['element'] = true;

			themeblvd_mini_post_grid( $args['options'], $args['options']['align'], $args['options']['thumbs'], $gallery );

			break;

		/*------------------------------------------------------*/
		/* Media (media.php)
		/*------------------------------------------------------*/

		// Featured Image
		case 'featured_image':
			wp_reset_query();

			themeblvd_the_post_thumbnail( $args['options']['crop'] );

			break;

		// Image
		case 'image':
			themeblvd_image( $args['options']['image'], $args['options'] );
			break;

		// Revolution Slider (requires Revolution Sliders plugin)
		case 'revslider':
			themeblvd_content( sprintf( '[rev_slider %s]', $args['options']['id'] ) );
			break;

		// Simple Slider (standard and popout)
		case 'simple_slider':
		case 'simple_slider_popout':
			$images = $args['options']['images'];

			unset( $args['options']['images'] );

			themeblvd_simple_slider( $images, $args['options'] );

			break;

		// Video
		case 'video':
			themeblvd_video( $args['options']['video'], $args['options'] );
			break;

		/*------------------------------------------------------*/
		/* Parts (parts.php)
		/*------------------------------------------------------*/

		// Author Box
		case 'author_box':
			themeblvd_author_info( get_user_by( 'slug', $args['options']['user'] ) );
			break;

		// Breacrumbs
		case 'breadcrumbs':
			themeblvd_the_breadcrumbs();
			break;

		// Contact Buttons
		case 'contact':
			echo themeblvd_get_contact_bar( $args['options']['buttons'], $args['options'] );
			break;

		/*------------------------------------------------------*/
		/* Charts and Graphs (stats.php)
		/*------------------------------------------------------*/

		// Chart (bar)
		case 'chart_bar':
			themeblvd_chart( 'bar', $args['options'] );
			break;

		// Chart (line)
		case 'chart_line':
			themeblvd_chart( 'line', $args['options'] );
			break;

		// Chart (pie)
		case 'chart_pie':
			themeblvd_chart( 'pie', $args['options'] );
			break;

		// Milestone
		case 'milestone':
			themeblvd_milestone( $args['options'] );
			break;

		// Milestone Ring (represents percentage)
		case 'milestone_ring':
			themeblvd_milestone_ring( $args['options'] );
			break;

		// Progress Bars
		case 'progress_bars':
			themeblvd_progress_bars( $args['options'] );

	}

	/**
	 * Fires for the output for custom layout
	 * elements.
	 *
	 * @TODO Issue #322. Rename to themeblvd_element_{$type}.
	 * Also needs to chnage in layout builder plugin so custom
	 * elements get hooked correctly.
	 *
	 * @since @@name-framework 2.2.0
	 *
	 * @param string $id      Element ID.
	 * @param array  $options Element settings.
	 * @param array  $args    All arguments originally passed for element, see themeblvd_element() docs.
	 */
	do_action( 'themeblvd_' . $args['type'], $args['id'], $args['options'], $args );

	/**
	 * Fires just before the end of any custom
	 * layout element's HTML output.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $id      Element ID.
	 * @param string $options Element settings, different for each element type.
	 * @param string $section Section ID, containing this element.
	 * @param string $display Element display settings.
	 * @param string $context Context for elements, `element` or `block`.
	 */
	do_action(
		'themeblvd_element_' . $args['type'] . '_bottom',
		$args['id'],
		$args['options'],
		$args['section'],
		$args['display'],
		$args['context']
	);

	// Close element.
	printf( '</div><!-- #%s (end) -->', esc_attr( $args['id'] ) );

	/**
	 * Fires after the end of any custom layout
	 * element's HTML output.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $id      Element ID.
	 * @param string $options Element settings, different for each element type.
	 * @param string $section Section ID, containing this element.
	 * @param string $display Element display settings.
	 * @param string $context Context for elements, `element` or `block`.
	 */
	do_action(
		'themeblvd_element_' . $args['type'] . '_after',
		$args['id'],
		$args['options'],
		$args['section'],
		$args['display'],
		$args['context']
	);

}

/**
 * Get CSS classes for an indvidual element.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args  Element arguments. See docs for themeblvd_element().
 * @return string $class Element CSS class.
 */
function themeblvd_get_element_class( $args ) {

	/*
	 * Ensure that $args is set up right and matches
	 * what should be coming from themeblvd_element().
	 */
	$args = wp_parse_args( $args, array(
		'id'        => '',
		'type'      => '',
		'label'     => '',
		'options'   => array(),
		'display'   => array(),
		'num'       => 1,
		'total'     => 1,
		'context'   => 'element',
	));

	$class = array(
		'element',
		'element-' . $args['num'],
		'element-' . $args['type'],
	);

	// Is the element being display within the "Columns" element?
	if ( 'block' === $args['context'] ) {

		$class[] = 'element-block';

	}

	// First and last elements.
	if ( 1 == $args['num'] ) {

		$class[] = 'first';

	}

	if ( $args['total'] == $args['num'] ) {

		$class[] = 'last';

	}

	// Add class for label.
	if ( $args['label'] && '...' !== $args['label'] ) {

		$label = str_replace( ' ', '-', $args['label'] );

		$label = preg_replace( '/[^\w-]/', '', $label );

		$class[] = strtolower( $label );

	}

	// Add display option classes.
	if ( $args['display'] ) {

		// Is the element popped out?
		if ( ! empty( $args['display']['apply_popout'] ) ) {

			$class[] = 'popout';

		} else {

			$class[] = 'no-popout';

		}

		// Does the element have the default content background applied?
		if ( ! empty( $args['display']['bg_content'] ) ) {

			$class[] = 'bg-content';

			$class[] = 'text-dark';

		}

		// Is the element sucked up, closer to the elment above it?
		if ( ! empty( $args['display']['suck_up'] ) ) {

			$class[] = 'suck-up';

		}

		// Or sucked down, closer to the elment below it?
		if ( ! empty( $args['display']['suck_down'] ) ) {

			$class[] = 'suck-down';

		}

		// Add responsive visibility class.
		if ( ! empty( $args['display']['hide'] ) ) {

			$visibility = themeblvd_responsive_visibility_class( $args['display']['hide'] );

			if ( $visibility ) {

				$class[] = $visibility;

			}
		}

		// Add any end-user custom-added classes.
		if ( ! empty( $args['display']['classes'] ) ) {

			$add = explode( ' ', $args['display']['classes'] );

			$class = array_merge( $class, $add );

		}
	}

	// For columns, add a class for when they collapse.
	if ( 'columns' === $args['type'] ) {

		$class[] = 'stack-' . $args['options']['stack'];

	}

	/*
	 * For paginated post list/grid we want to output the
	 * shared class that Post List/Grid page templates, and
	 * main index.php are using.
	 */
	if ( 'post_list' === $args['type'] || 'post_grid' === $args['type'] ) {

		if ( isset( $args['options']['display'] ) && 'paginated' === $args['options']['display'] ) {

			$class[] = sprintf( 'paginated_%s', $args['type'] );

		}
	}

	// Group any lightbox elements within a Post Grid element.
	if ( 'post_grid' === $args['type'] ) {

		$class[] = 'themeblvd-gallery';

	}

	// Allow thumb link images within Image element to be centered.
	if ( 'image' === $args['type'] ) {

		if ( ! empty( $args['options']['align'] ) && 'center' === $args['options']['align'] ) {

			$class[] = 'text-center';

		}
	}

	/*
	 * For CSS fixes, apply no-width class on image
	 * elements with the display width option left blank.
	 */
	if ( 'image' === $args['type'] && empty( $args['options']['width'] ) ) {

		$class[] = 'no-width';

	}

	/*
	 * Setup any elements that have heights to match
	 * viewport.
	 *
	 * Note: For a Hero Unit, if user is using the
	 * `popout` option, they should be applying the
	 * background to the hero unit element, not the
	 * section.
	 */
	if ( ! empty( $args['options']['height_100vh'] ) && ! in_array( 'popout', $class ) ) {

		$class[] = 'height-100vh';

	}

	// Add clear fix.
	$class[] = 'clearfix';

	/**
	 * Filters the CSS classes for a custom layout
	 * element.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $class Element CSS classes.
	 * @param string $type  Element type.
	 * @param array  $args  Element arguments, see themeblvd_element() docs.
	 */
	return apply_filters( 'themeblvd_element_class', $class, $args['type'], $args );

}

/**
 * Get CSS classes for an section of elements.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  string $id    Section ID.
 * @param  array  $args  Section data.
 * @param  int    $count Number of elements in the current section.
 * @return string $class Section CSS class.
 */
function themeblvd_get_section_class( $id, $data, $count ) {

	$class = array();

	if ( false === strpos( $id, 'section_' ) ) {

		$class[] = 'section_' . $id;

	} else {

		$class[] = $id;

	}

	$class[] = 'element-section';

	$class[] = 'element-count-' . $count;

	if ( $data['label'] && '...' !== $data['label'] ) {

		$label = str_replace( ' ', '-', $data['label'] );

		$label = preg_replace( '/[^\w-]/', '', $label );

		$class[] = strtolower( $label );

	}

	if ( ! empty( $data['display'] ) ) {

		$class = array_merge( $class, themeblvd_get_display_class( $data['display'] ) );

	}

	/**
	 * Filters the CSS classes for a custom layout
	 * section of elements.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $class Section CSS class.
	 * @param array  $args  Section data.
	 * @param int    $count Number of elements in the current section.
	 */
	return apply_filters( 'themeblvd_section_class', $class, $data, $count );

}

/**
 * Get display classes for sections and columns.
 *
 * Note: This is NOT used for indvidual elements,
 * other than a `Columns` element.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $display Display settings.
 * @return string $class   Display CSS classes.
 */
function themeblvd_get_display_class( $display ) {

	$class = array();

	if ( ! empty( $display['bg_type'] ) ) {

		$bg_type = $display['bg_type'];

		if ( 'none' === $bg_type ) {

			$class[] = 'standard';

		} else {

			$class[] = 'has-bg';

			$class[] = $bg_type;

			if ( in_array( $bg_type, array( 'color', 'image', 'texture', 'slideshow', 'video' ) ) ) {

				if ( ! empty( $display['text_color'] ) && 'none' !== $display['text_color'] ) {

					$class[] = 'text-' . $display['text_color'];

				}
			}

			if ( 'image' === $bg_type || 'slideshow' === $bg_type || 'video' === $bg_type ) {

				if ( ! empty( $display['apply_bg_shade'] ) ) {

					$class[] = 'has-bg-shade';

				}
			}
		}

		if ( 'texture' === $bg_type ) {

			if ( ! empty( $display['apply_bg_texture_parallax'] ) ) {

				$class[] = 'tb-parallax';

			}

			if ( ! empty( $display['bg_texture'] ) ) {

				$texture = themeblvd_get_texture( $display['bg_texture'] );

				$class[] = $texture['repeat'];

			}
		} elseif ( 'image' === $bg_type ) {

			if ( ! empty( $display['bg_image']['attachment'] ) && 'parallax' === $display['bg_image']['attachment'] ) {

				$class[] = 'tb-parallax';

			}

			if ( ! empty( $display['bg_image']['repeat'] ) ) {

				$class[] = $display['bg_image']['repeat'];

			}
		} elseif ( 'slideshow' === $bg_type ) {

			$class[] = 'has-bg-slideshow';

		} elseif ( 'video' === $bg_type ) {

			$class[] = 'has-bg-video';

		}

		if ( ! empty( $display['apply_padding'] ) ) {

			$class[] = 'has-custom-padding';

		}

		if ( ! empty( $display['hide'] ) ) {

			$class[] = themeblvd_responsive_visibility_class( $display['hide'] );

		}

		if ( ! empty( $display['classes'] ) ) {

			$add = explode( ' ', $display['classes'] );

			$class = array_merge( $class, $add );

		}
	}

	/**
	 * Filters the display classes for sections
	 * and columns.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $class   Display CSS classes.
	 * @param array  $display Display settings.
	 */
	return apply_filters( 'themeblvd_display_class', $class, $display );

}

/**
 * Get inline styles for a set of display
 * settings.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array        $display Display settings.
 * @param  string       $print   How to return the styles, use `inline` or `external`.
 * @return string|array          If $print == `inline`, inline style HTML, otherwise array of style parameters are returned.
 */
function themeblvd_get_display_inline_style( $display, $print = 'inline' ) {

	$bg_type = '';

	$style = '';

	$params = array();

	if ( empty( $display['bg_type'] ) ) {

		$bg_type = 'none';

	} else {

		$bg_type = $display['bg_type'];

	}

	$parallax = false;

	if ( 'image' === $bg_type ) {

		if ( ! empty( $display['bg_image']['attachment'] ) && 'parallax' === $display['bg_image']['attachment'] ) {

			$parallax = true;

		}
	} elseif ( 'texture' === $bg_type ) {

		if ( ! empty( $display['apply_bg_texture_parallax'] ) ) {

			$parallax = true;

		}
	}

	if ( in_array( $bg_type, array( 'color', 'texture', 'image', 'video', 'none' ) ) ) {

		if ( ( 'none' === $bg_type && empty( $display['bg_content'] ) ) || $parallax ) {

			$params['background-color'] = 'transparent';

		} elseif ( ! empty( $display['bg_color'] ) ) {

			$bg_color = $display['bg_color'];

			if ( ! empty( $display['bg_color_opacity'] ) ) {

				$bg_color = themeblvd_get_rgb( $bg_color, $display['bg_color_opacity'] );

			}

			$params['background-color'] = $bg_color;

		}

		if ( 'texture' === $bg_type && ! $parallax ) {

			$textures = themeblvd_get_textures();

			if ( ! empty( $display['bg_texture'] ) && ! empty( $textures[ $display['bg_texture'] ] ) ) {

				$texture = $textures[ $display['bg_texture'] ];

				$params['background-image'] = sprintf( 'url(%s)', esc_url( $texture['url'] ) );

				$params['background-position'] = $texture['position'];

				$params['background-repeat'] = $texture['repeat'];

				$params['background-size'] = $texture['size'];

			}
		} elseif ( 'image' === $bg_type && ! $parallax ) {

			$repeat = false;

			if ( ! empty( $display['bg_image']['image'] ) ) {

				$params['background-image'] = sprintf( 'url(%s)', esc_url( $display['bg_image']['image'] ) );

			}

			if ( ! empty( $display['bg_image']['repeat'] ) ) {

				if ( 'no-repeat' !== $display['bg_image']['repeat'] ) {

					$repeat = true;

				}

				$params['background-repeat'] = $display['bg_image']['repeat'];

			}

			if ( ! $repeat && ! empty( $display['bg_image']['size'] ) ) {

				$params['background-size'] = $display['bg_image']['size'];

			}

			if ( ! wp_is_mobile() && ! empty( $display['bg_image']['attachment'] ) ) {

				$params['background-attachment'] = $display['bg_image']['attachment'];

			}

			if ( ! empty( $display['bg_image']['position'] ) ) {

				$params['background-position'] = $display['bg_image']['position'];

			}
		} elseif ( 'video' === $bg_type ) {

			if ( ! empty( $display['bg_video']['fallback'] ) ) {

				$params['background-image'] = sprintf( 'url(%s)', esc_url( $display['bg_video']['fallback'] ) );

			}
		}
	}

	if ( ! empty( $display['apply_border_top'] ) ) {

		$params['border-top-style'] = 'solid';

		if ( ! empty( $display['border_top_width'] ) ) {

			$params['border-top-width'] = $display['border_top_width'];

		}

		if ( ! empty( $display['border_top_color'] ) ) {

			$params['border-top-color'] = $display['border_top_color'];

		}
	}

	if ( ! empty( $display['apply_border_bottom'] ) ) {

		$params['border-bottom-style'] = 'solid';

		if ( ! empty( $display['border_bottom_width'] ) ) {

			$params['border-bottom-width'] = $display['border_bottom_width'];

		}

		if ( ! empty( $display['border_bottom_color'] ) ) {

			$params['border-bottom-color'] = $display['border_bottom_color'];

		}
	}

	if ( ! empty( $display['apply_padding'] ) ) {

		if ( ! empty( $display['padding_top'] ) ) {

			$params['padding-top'] = $display['padding_top'];

		}

		if ( ! empty( $display['padding_bottom'] ) ) {

			$params['padding-bottom'] = $display['padding_bottom'];

		}

		if ( ! empty( $display['padding_right'] ) ) {

			$params['padding-right'] = $display['padding_right'];

			if ( ! empty( $display['apply_popout'] ) ) {

				$params['padding-right'] .= ' !important'; // Override popout.

			}
		}

		if ( ! empty( $display['padding_left'] ) ) {

			$params['padding-left'] = $display['padding_left'];

			if ( ! empty( $display['apply_popout'] ) ) {
				$params['padding-left'] .= ' !important'; // Override popout.
			}
		}
	}

	if ( 'external' === $print ) {

		$params = array(
			'general'   => $params,
			'desktop'   => array(),
			'tablet'    => array(),
			'mobile'    => array(),
		);

		foreach ( $params as $key => $value ) {

			if ( 'general' === $key ) {

				continue;

			}

			if ( ! empty( $display[ 'apply_padding_' . $key ] ) ) {

				if ( ! empty( $display[ 'padding_top_' . $key ] ) ) {

					$params[ $key ]['padding-top'] = $display[ 'padding_top_' . $key ];

				}

				if ( ! empty( $display[ 'padding_bottom_' . $key ] ) ) {

					$params[ $key ]['padding-bottom'] = $display[ 'padding_bottom_' . $key ];

				}

				if ( ! empty( $display[ 'padding_right_' . $key ] ) ) {

					$params[ $key ]['padding-right'] = $display[ 'padding_right_' . $key ];

				}

				if ( ! empty( $display[ 'padding_left_' . $key ] ) ) {

					$params[ $key ]['padding-left'] = $display[ 'padding_left_' . $key ];

				}
			}
		}
	}

	/**
	 * Filters the inline style parameters generated
	 * for a section or column.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array $params  Inline style parameters.
	 * @param array $display Display settings.
	 */
	$params = apply_filters( 'themeblvd_display_inline_style', $params, $display );

	if ( 'inline' === $print ) {

		foreach ( $params as $key => $value ) {

			// Handles the second instance of any duplicate parameters.
			$key = str_replace( '-2', '', $key );

			$style .= sprintf( '%s: %s; ', $key, $value );

		}

		return trim( esc_attr( $style ) );

	}

	return $params;

}

/**
 * Dislay set of columns.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array $args {
 *     Arguments for column layout.
 *
 *     @type string $section    Section containing columnss.
 *     @type int    $layout_id  Custom layout ID, if included within a custom layout.
 *     @type string $element_id Element ID, if included within a custom layout.
 *     @type int    $num        Number of columns.
 *     @type string $widths     Column widths.
 *     @type string $stack      Viewport slug columns collapse at, like `sm`, `md`, etc.
 *     @type bool   $height     Whether all columns should match the tallest column in height.
 *     @type string $align      How column content is aligned, `top`, `middle` or `bottom`.
 * }
 * @param array $columns Manually feed column data.
 */
function themeblvd_columns( $args, $columns = null ) {

	$args = wp_parse_args( $args, array(
		'section'    => '',
		'layout_id'  => 0,
		'element_id' => 'element_',
		'num'        => 1,
		'widths'     => 'grid_12',
		'stack'      => 'md',
		'height'     => 0,
		'align'      => 'top',
	));

	$num = intval( $args['num'] );

	$stack = $args['stack'];

	/*
	 * Kill it if number of columns doesn't match the
	 * number of widths exploded from the string.
	 */
	$widths = explode( '-', $args['widths'] );

	if ( count( $widths ) !== $num ) {

		return;

	}

	/*
	 * Column Margins
	 *
	 * Problem: By default with Bootstrap, a row of columns
	 * has -15px margin on the sides. However, when a background is
	 * applied to a column, we need to eliminate that so the background
	 * of the column doesn't over hang outside of the container.
	 * Note: Using Bootstrap's "container-fluid" class will not work
	 * in this case, because we're doing this per individual side.
	 *
	 * Solution: If it's the first column and has a BG, change row
	 * left margin to 0, and if the last column has a BG, change row
	 * right margin to 0.
	 */
	$margin_left = '-15px';

	$margin_right = '-15px';

	for ( $i = 1; $i <= $num; $i++ ) {

		// If first or last.
		if ( 1 === $i || $num === $i ) {

			$column = get_post_meta(
				$args['layout_id'],
				'_tb_builder_' . $args['element_id'] . '_col_' . strval( $i ),
				true
			); // Example: `_tb_builder_element_123_col_1`

			if ( ! empty( $column['display']['bg_type'] ) ) {

				if ( in_array( $column['display']['bg_type'], array( 'color', 'image', 'texture' ) ) ) {

					if ( 1 === $i ) { // First column.

						$margin_left = '0';

					} elseif ( $num === $i ) { // Last column.

						$margin_right = '0';

					}
				}
			}
		}
	}

	$margin = sprintf( 'margin: 0 %s 0 %s;', $margin_right, $margin_left );

	// Open column row.
	if ( $args['height'] && 0 != $args['layout_id'] && ! $columns ) {

		$open_row = array(
			'wrap'  => "container-{$stack}-height",
			'class' => "row stack-{$stack} row-{$stack}-height",
			'style' => $margin,
		);

	} else {

		$open_row = array(
			'class' => "row stack-{$stack}",
			'style' => $margin,
		);

	}

	themeblvd_open_row( $open_row );

	// Display columns.
	for ( $i = 1; $i <= $num; $i++ ) {

		$grid_class = themeblvd_grid_class( $widths[ $i - 1 ], $stack );

		// Equal height columns?
		if ( $args['height'] ) {

			$grid_class .= " col-{$stack}-height";

			if ( in_array( $args['align'], array( 'top', 'middle', 'bottom' ) ) ) {

				$grid_class .= ' col-' . $args['align'];

			}
		}

		if ( 0 == $args['layout_id'] && $columns ) {

			echo '<div class="col entry-content ' . esc_attr( $grid_class ) . '">'; // Use "entry-content" class only because not using elements.

			if ( isset( $columns[ $i ] ) ) {

				switch ( $columns[ $i ]['type'] ) {

					case 'widget':
						themeblvd_widgets( $columns[ $i ]['sidebar'], 'tabs' );

						break;

					case 'page':
						themeblvd_post_content( $columns[ $i ]['page'], 'page' );

						break;

					case 'raw':
						if ( ! empty( $columns[ $i ]['raw_format'] ) ) {

							themeblvd_content( $columns[ $i ]['raw'] );

						} else {

							echo do_shortcode( themeblvd_kses( $columns[ $i ]['raw'] ) );

						}

						break;

				}
			}

			echo '</div><!-- .' . esc_attr( $grid_class ) . ' (end) -->';

		} else {

			$blocks = array();

			$display = array();

			$column = get_post_meta(
				$args['layout_id'],
				'_tb_builder_' . $args['element_id'] . '_col_' . strval( $i ),
				true
			); // Example: `_tb_builder_element_123_col_1`

			if ( ! empty( $column['display'] ) ) {

				$display = $column['display'];

			}

			// Start column.
			$display_class = implode( ' ', themeblvd_get_display_class( $display ) );

			printf(
				'<div class="col %s %s" style="%s">',
				esc_attr( $grid_class ),
				esc_attr( $display_class ),
				themeblvd_get_display_inline_style( $display )
			);

			if ( themeblvd_do_bg_shade( $display ) ) {

				themeblvd_bg_shade( $display );

			}

			if ( themeblvd_do_parallax( $display ) ) {

				themeblvd_bg_parallax( $display );

			}

			if ( ! empty( $column['elements'] ) ) {

				$blocks = $column['elements'];

			}

			$width_atts = array(
				'context' => 'block',
				'col'     => $widths[ $i - 1 ],
			);

			// Display elements.
			themeblvd_elements(
				$args['section'],
				$blocks, 'block',
				themeblvd_get_max_width( $width_atts )
			);

			echo '</div><!-- .' . $grid_class . ' (end) -->';

		}
	}

	if ( $args['height'] ) {

		themeblvd_close_row( array(
			'wrap' => true,
		));

	} else {

		themeblvd_close_row();

	}
}

/**
 * Get a hero unit slider.
 *
 * @since @@name-framework 2.5.0
 *
 * @param  array  $args {
 *     Hero unit arguments.
 *
 *     @type string      $section    Section ID containing hero unit slider.
 *     @type string|int  $layout_id  Custom layout ID containing hero unit slider.
 *     @type string      $element_id Hero unit slider element ID.
 *     @type string      $fx         Transition effect, `slide` or `fade`; for now, this is disabled to the user and will always be `fade`.
 *     @type string      $timeout    Seconds between transitions, use `0` for no auto-rotation.
 *     @type string|bool $nav        Whether to show slider navigation.
 * }
 * @return string $output Final HTML output for block.
 */
function themeblvd_get_jumbotron_slider( $args ) {

	$args = wp_parse_args( $args, array(
		'section'    => '',
		'layout_id'  => 0,
		'element_id' => 'element_',
		'fx'         => 'fade',
		'timeout'    => '3',
		'nav'        => '1',
	));

	$output = '';

	// Get data with hero units, saved outside of initial layout elements.
	$data = get_post_meta(
		$args['layout_id'],
		'_tb_builder_' . $args['element_id'] . '_col_1',
		true
	); // Example: `_tb_builder_element_123_col_1`

	if ( $data && ! empty( $data['elements'] ) ) {

		// Create ID for slider.
		$slider_id = str_replace(
			'element_',
			'jumbotron_slider_',
			$args['element_id']
		);

		$class = 'tb-jumbotron-slider carousel';

		if ( $args['nav'] ) {

			$class .= ' has-nav';

		}

		$fs = true;

		foreach ( $data['elements'] as $elem ) {

			if ( empty( $elem['options']['height_100vh'] ) ) {

				$fs = false;

				break;

			}
		}

		if ( $fs ) {

			$class .= ' fs'; // Added if all slides are full viewport height.

		}

		$interval = esc_attr( $args['timeout'] );

		if ( wp_is_mobile() ) {

			$interval = '0'; // Disable auto rotation for true mobile devices.

		}

		if ( $interval && intval( $interval ) < 100 ) {

			$interval .= '000'; // User has probably inputted seconds; convert it to milliseconds.

		}

		// Start output.
		$output .= sprintf(
			'<div id="%s" class="%s" data-ride="carousel" data-interval="%s" data-pause="hover">',
			$slider_id,
			$class,
			$interval
		);

		$output .= themeblvd_get_loader();

		$output .= '<div class="carousel-control-wrap">';

		$output .= '<div class="carousel-inner">';

		$count = 1;

		foreach ( $data['elements'] as $elem ) {

			$class = 'item';

			if ( 1 === $count ) {

				$class .= ' active';

			}

			$output .= sprintf(
				'<div class="%s">%s</div>',
				$class,
				themeblvd_get_jumbotron( $elem['options'] )
			);

			$count++;

		}

		$output .= '</div><!-- .carousel-inner (end) -->';

		// Add navigation dots.
		if ( $args['nav'] ) {

			if ( $data && ! empty( $data['elements'] ) ) {

				$output .= '<ol class="carousel-indicators">';

				$count = 0;

				foreach ( $data['elements'] as $elem ) {

					$class = '';

					if ( 0 === $count ) {

						$class = 'active';

					}

					$output .= sprintf(
						'<li data-target="#%s" data-slide-to="%s" class="%s"></li>',
						$slider_id,
						$count,
						$class
					);

					$count++;

				}

				$output .= '</ol>';

			}
		}

		// Add directional nav.
		$output .= themeblvd_get_slider_controls( array(
			'carousel' => $slider_id,
			'color'    => 'trans',
		));

		$output .= '</div><!-- .carousel-control-wrap (end) -->';

		$output .= '</div><!-- .tb-jumbotron-slider (end) -->';

	}

	/**
	 * Filters the HTML output for a hero unit
	 * slider.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $output Final HTML output.
	 * @param array  $args {
	 *     Hero unit arguments.
	 *
	 *     @type string      $section    Section ID containing hero unit slider.
	 *     @type string|int  $layout_id  Custom layout ID containing hero unit slider.
	 *     @type string      $element_id Hero unit slider element ID.
	 *     @type string      $fx         Transition effect, `slide` or `fade`; for now, this is disabled to the user and will always be `fade`.
	 *     @type string      $timeout    Seconds between transitions, use `0` for no auto-rotation.
	 *     @type string|bool $nav        Whether to show slider navigation.
	 * }
	 */
	return apply_filters( 'themeblvd_jumbotron_slider', $output, $args );

}

/**
 * Display a hero unit slider.
 *
 * @since @@name-framework 2.4.2
 *
 * @param array $args Block arguments, see themeblvd_get_jumbotron_slider() docs.
 */
function themeblvd_jumbotron_slider( $args ) {

	echo themeblvd_get_jumbotron_slider( $args );

}

/**
 * Bump the starting count for a section by one.
 *
 * If a page with custom layout has a featured image above
 * content applied, bump the section start count from
 * 1 to 2.
 *
 * This way, the featured image essentially becomes
 * the first section of the layout.
 *
 * This function is filtered onto:
 * 1. `themeblvd_builder_section_start_count` - 10
 *
 * @since @@name-framework 2.5.2
 *
 * @param  int $count Number to start section count at.
 * @return int $count Modified number to start section count at.
 */
function themeblvd_builder_section_start_count( $count ) {

	if ( themeblvd_get_att( 'epic_thumb' ) ) {

		$count++;

	}

	return $count;

}
