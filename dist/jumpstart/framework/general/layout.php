<?php
/**
 * Frontend layout functions.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.5.0
 */

/**
 * Display set of elements
 *
 * @since 2.5.0
 *
 * @param string $section_id ID of current section holding elements
 * @param array $elements All elements to loop through and display
 * @param string $context Context for elements, element or block (within a column)
 * @param int $max Maximum width of container
 */
function themeblvd_elements( $section_id, $elements, $context = 'element', $max = 0 ) {

	if ( $elements ) {

		// Maximum width of container for elements. If the
		// context is "block" this shouldn't be empty to start.
		if ( ! $max ) {
			$max = themeblvd_get_max_width('element');
		}

		$i = 1;
		$total = count($elements);

		foreach ( $elements as $id => $element ) {

			$args = array(
				'section'	=> $section_id,
				'id'		=> $id,
				'num'		=> $i,
				'total'		=> $total,
				'context'	=> $context,
				'max_width'	=> $max
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
 * Display individual element
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for display
 */
function themeblvd_element( $args ) {

	$defaults = array(
		'section'	=> '',			// Unique ID for the section
		'id'		=> '',			// Unique ID for the element
		'type'		=> '',			// The type of element to display
		'label'		=> '',			// Element label
		'options'	=> array(),		// Settings for this element
		'display'	=> array(),		// Display settings for this element
		'num'		=> 1,			// Current count for the element being displayed
		'total'		=> 1,			// Total number of elements in parent section
		'context'	=> 'element',	// Context for elements, element or block (within a column)
		'max_width'	=> ''
	);
	$args = wp_parse_args( $args, $defaults );

	// Allow any elements to utilize the container
	// max width
	$args['options']['max_width'] = $args['max_width'];

	// Element class
	$class = implode( ' ', themeblvd_get_element_class( $args ) );

	// Open element
	do_action( 'themeblvd_element_'.$args['type'].'_before', esc_attr($args['id']), $args['options'], $args['section'], $args['display'], $args['context'] ); // Before element: themeblvd_element_{type}_before
	printf( '<div id="%s" class="%s" style="%s">', esc_attr($args['id']), esc_attr($class), themeblvd_get_display_inline_style($args['display']) );
	do_action( 'themeblvd_element_'.$args['type'].'_top', esc_attr($args['id']), $args['options'], $args['section'], $args['display'], $args['context'] ); // Top of element: themeblvd_element_{type}_top

	// Display element
	switch ( $args['type'] ) {

		/*------------------------------------------------------*/
		/* Layout (layout.php)
		/*------------------------------------------------------*/

		case 'columns' :
		case 'jumbotron_slider' :

			if ( themeblvd_get_att('footer_sync') ) {
				$layout_id = themeblvd_config('bottom_builder_post_id');
			} else {
				$layout_id = themeblvd_config('builder_post_id');
			}

			if ( $args['type'] == 'jumbotron_slider' ) {

				$hero_args = array(
					'section'		=> $args['section'],
					'layout_id'		=> $layout_id,
					'element_id' 	=> $args['id'],
				);

				themeblvd_jumbotron_slider( array_merge( $hero_args, $args['options'] ) );

			} else {

				$num = 1;

				if ( isset($args['options']['setup']) && is_string( $args['options']['setup'] ) ) {
					$num = count( explode( '-', $args['options']['setup'] ) );
				}

				$col_args = array(
					'section'		=> $args['section'],
					'layout_id'		=> $layout_id,
					'element_id' 	=> $args['id'],
					'num'			=> $num,
					'widths'		=> $args['options']['setup'],
					'stack' 		=> $args['options']['stack'],
					'height'		=> $args['options']['height'],
					'align'			=> $args['options']['align']
				);

				themeblvd_columns( $col_args );

			}
			break;

		/*------------------------------------------------------*/
		/* Content (content.php)
		/*------------------------------------------------------*/

		// Content (direct input)
		case 'content' :
			themeblvd_content_block( $args['options'] );
			break;

		// Current (from post content)
		case 'current' :
			themeblvd_post_content();
			break;

		// Custom Field
		case 'custom_field' :

			$value = get_post_meta( themeblvd_config('id'), $args['options']['key'], true );

			if ( $args['options']['wpautop'] ) {
				echo themeblvd_get_content($value);
			} else {
				echo do_shortcode( themeblvd_kses($value) );
			}
			break;

		// External Page/Post content
		case 'external' :
			themeblvd_post_content( intval($args['options']['post_id']) );
			break;

		// Headline
		case 'headline' :
			themeblvd_headline( $args['options'] );
			break;

		// HTML
		case 'html' :
			printf( '<div class="entry-content">%s</div>', themeblvd_kses($args['options']['html']) );
			break;

		// Quote
		case 'quote' :
			themeblvd_blockquote( $args['options'] );
			break;

		// Widget area
		case 'widget' :
			themeblvd_widgets( $args['options']['sidebar'], $args['context'] );
			break;

		/*------------------------------------------------------*/
		/* Components (components.php)
		/*------------------------------------------------------*/

		// Alert
		case 'alert' :
			themeblvd_alert( $args['options'] );
			break;

		// Divider
		case 'divider' :
			themeblvd_divider( $args['options'] );
			break;

		// Google Map
		case 'map' :
			themeblvd_map( $args['options'] );
			break;

		// Icon Box
		case 'icon_box' :
			themeblvd_icon_box( $args['options'] );
			break;

		// Hero Unit
		case 'jumbotron' :
			themeblvd_jumbotron( $args['options'] );
			break;

		// Panel
		case 'panel' :
			themeblvd_panel( $args['options'] );
			break;

		// Partner Logos
		case 'partners' :
			themeblvd_logos( $args['options'] );
			break;

		// Pricing Table
		case 'pricing_table' :
			themeblvd_pricing_table( $args['options']['columns'], $args['options'] );
			break;

		// Promo Box
		case 'slogan' :
			themeblvd_slogan( $args['options'] );
			break;

		// Tabs
		case 'tabs' :
			themeblvd_tabs( $args['id'], $args['options'] );
			break;

		// Team Member
		case 'team_member' :
			themeblvd_team_member( $args['options'] );
			break;

		// Testimonial
		case 'testimonial' :
			themeblvd_testimonial( $args['options'] );
			break;

		// Testimonial Slider
		case 'testimonial_slider' :
			themeblvd_testimonial_slider( $args['options'] );
			break;

		// Toggles
		case 'toggles' :
			themeblvd_toggles( $args['id'], $args['options'] );
			break;

		/*------------------------------------------------------*/
		/* Posts (loop.php)
		/*------------------------------------------------------*/

		// Blog
		case 'blog' :
			$args['options']['context'] = 'blog';
			$args['options']['element'] = true;
			themeblvd_loop( $args['options'] );
			break;

		// Post Grid
		case 'post_grid' :
			$args['options']['context'] = 'grid';
			$args['options']['element'] = true;
			themeblvd_loop( $args['options'] );
			break;

		// Post List
		case 'post_list' :
			$args['options']['context'] = 'list';
			$args['options']['element'] = true;
			themeblvd_loop( $args['options'] );
			break;

		// Post Showcase
		case 'post_showcase' :
			$args['options']['context'] = 'showcase';
			$args['options']['element'] = true;
			themeblvd_loop( $args['options'] );
			break;

		// Post Slider
		case 'post_slider' :
		case 'post_slider_popout' :
			$args['options']['element'] = true;
			themeblvd_post_slider( $args['options'] );
			break;

		// Mini Post List
		case 'mini_post_list' :
			$args['options']['element'] = true;
			themeblvd_mini_post_list( $args['options'], $args['options']['thumbs'], $args['options']['meta'] );
			break;

		// Mini Post Grid
		case 'mini_post_grid' :

			$gallery = '';

			if ( $args['options']['source'] == 'gallery' ) {

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
		case 'featured_image' :
			wp_reset_query();
			themeblvd_the_post_thumbnail( $args['options']['crop'] );
			break;

		// Image
		case 'image' :
			themeblvd_image( $args['options']['image'], $args['options'] );
			break;

		// Revolution Slider (requires Revolution Sliders plugin)
		case 'revslider' :
			themeblvd_content( sprintf('[rev_slider %s]', $args['options']['id']) );
			break;

		// Simple Slider (standard and popout)
		case 'simple_slider' :
		case 'simple_slider_popout' :
			$images = $args['options']['images'];
			unset( $args['options']['images'] );
			themeblvd_simple_slider( $images, $args['options'] );
			break;

		// Video
		case 'video' :
			themeblvd_video( $args['options']['video'], $args['options'] );
			break;

		/*------------------------------------------------------*/
		/* Parts (parts.php)
		/*------------------------------------------------------*/

		// Author Box
		case 'author_box' :
			themeblvd_author_info( get_user_by('slug', $args['options']['user']) );
			break;

		// Breacrumbs
		case 'breadcrumbs' :
			themeblvd_the_breadcrumbs();
			break;

		// Contact Buttons
		case 'contact' :
			echo themeblvd_get_contact_bar( $args['options']['buttons'], $args['options'] );
			break;

		/*------------------------------------------------------*/
		/* Charts and Graphs (stats.php)
		/*------------------------------------------------------*/

		// Chart (bar)
		case 'chart_bar' :
			themeblvd_chart( 'bar', $args['options'] );
			break;

		// Chart (line)
		case 'chart_line' :
			themeblvd_chart( 'line', $args['options'] );
			break;

		// Chart (pie)
		case 'chart_pie' :
			themeblvd_chart( 'pie', $args['options'] );
			break;

		// Milestone
		case 'milestone' :
			themeblvd_milestone( $args['options'] );
			break;

		// Milestone Ring (represents percentage)
		case 'milestone_ring' :
			themeblvd_milestone_ring( $args['options'] );
			break;

		// Progress Bars
		case 'progress_bars' :
			themeblvd_progress_bars( $args['options'] );

	}

	// Custom Element
	do_action( 'themeblvd_'.$args['type'], $args['id'], $args['options'] );

	// Close element
	do_action( 'themeblvd_element_'.esc_attr($args['type']).'_bottom', esc_attr($args['id']), $args['options'], $args['section'], $args['display'], $args['context'] ); // Before element: themeblvd_element_{type}_bottom
	printf( '</div><!-- #%s (end) -->', esc_attr($args['id']) );
	do_action( 'themeblvd_element_'.esc_attr($args['type']).'_after', esc_attr($args['id']), $args['options'], $args['section'], $args['display'], $args['context']  ); // Top of element: themeblvd_element_{type}_after

}

/**
 * Get CSS classes needed for individual element
 *
 * @since 2.5.0
 *
 * @param array $args Arguments from themeblvd_element()
 * @return string $class CSS class to return
 */
function themeblvd_get_element_class( $args ) {

	// Ensure that $args is setup right and matches
	// what should be coming from themeblvd_element()
	$defaults = array(
		'id'		=> '',			// Unique ID for the element
		'type'		=> '',			// The type of element to display
		'label'		=> '',			// Element label
		'options'	=> array(),		// Settings for this element
		'display'	=> array(),		// Display settings for this element
		'num'		=> 1,			// Current count for the element being displayed
		'total'		=> 1,			// Total number of elements in parent section
		'context'	=> 'element'	// Context for elements, element or block (within a column)
	);
	$args = wp_parse_args( $args, $defaults );

	// Start class
	$class = array( 'element', 'element-'.$args['num'], 'element-'.$args['type'] );

	// Is the element being display within the "Columns" element?
	if ( $args['context'] == 'block' ) {
		$class[] = 'element-block';
	}

	// First and last elements
	if ( $args['num'] == 1 ) {
		$class[] = 'first';
	}
	if ( $args['total'] == $args['num'] ) {
		$class[] = 'last';
	}

	// Label
	if ( $args['label'] && $args['label'] != '...' ) {
		$label = str_replace( ' ', '-', $args['label'] );
		$label = preg_replace( '/[^\w-]/', '', $label );
		$class[] = strtolower($label);
	}

	// Display classes
	if ( $args['display'] ) {

		// Is the element popped out?
		if ( ! empty( $args['display']['apply_popout'] ) ) {
			$class[] = 'popout';
		} else {
			$class[] = 'no-popout';
		}

		// Does the element have the default content BG applied?
		if ( ! empty( $args['display']['bg_content'] ) ) {

			$class[] = 'bg-content';

			if ( themeblvd_supports('display', 'dark') ) {
				$class[] = 'text-light';
			} else {
				$class[] = 'text-dark';
			}
		}

		// Is the element sucked up?
		if ( ! empty( $args['display']['suck_up'] ) ) {
			$class[] = 'suck-up';
		}

		// Or maybe sucked down?
		if ( ! empty( $args['display']['suck_down'] ) ) {
			$class[] = 'suck-down';
		}

		// Responsive visibility
		if ( ! empty( $args['display']['hide'] ) && $visibility = themeblvd_responsive_visibility_class( $args['display']['hide'] ) ) {
			$class[] = $visibility;
		}

		// User-added CSS classes
		if ( ! empty( $args['display']['classes'] ) ) {
			$class[] = $args['display']['classes'];
		}

	}

	// For columns, add a class for when they collapse/stack
	if ( $args['type'] == 'columns' ) {
		$class[] = 'stack-'.$args['options']['stack'];
	}

	// For paginated post list/grid we want to output the shared
	// class that Post List/Grid page templates, and main index.php
	// are using.
	if ( $args['type'] == 'post_list' || $args['type'] == 'post_grid' ) {
		if ( isset( $args['options']['display'] ) && $args['options']['display'] == 'paginated' ) {
			$class[] = sprintf( 'paginated_%s', $args['type'] );
		}
	}

	// Post grid galleries
	if ( in_array( $args['type'], apply_filters( 'themeblvd_gallery_elements', array( 'post_grid', 'portfolio' ) ) ) ) {
		$class[] = 'themeblvd-gallery';
	}

	// Allow thumb link images within Image Element to be centered
	if ( $args['type'] == 'image' && ! empty( $args['options']['align'] ) && $args['options']['align'] == 'center' ) {
		$class[] = 'text-center';
	}

	// For CSS fixes, apply no-width class on image elements with
	// the display width option left blank
	if ( $args['type'] == 'image' && empty( $args['options']['width'] ) ) {
		$class[] = 'no-width';
	}

	// Any elements that have heights to match viewport
	// Note: For hero unit, if user is "popping out", they
	// should be applying the background to the hero unit
	// element, not section.
	if ( ! empty( $args['options']['height_100vh'] ) && ! in_array('popout', $class) ) {
		$class[] = 'height-100vh';
	}

	// Clear fix
	$class[] = 'clearfix';

	/**
	 * If you want to filter element classes, use the following
	 * "themeblvd_element_class" and check for $type.
	 */
	return apply_filters( 'themeblvd_element_class', $class, $args['type'], $args );
}

/**
 * Get CSS classes needed for individual section
 *
 * @since 2.5.0
 *
 * @param array $args Data saved for section
 * @param int $count Number of elements in the current section
 * @return string $class CSS class to return
 */
function themeblvd_get_section_class( $id, $data, $count ) {

	$class = array();

	if ( strpos($id, 'section_') === false ) {
		$class[] = 'section_'.$id;
	} else {
		$class[] = $id;
	}

	$class[] = 'element-section';
	$class[] = 'element-count-'.$count;

	// Label
	if ( $data['label'] && $data['label'] != '...' ) {
		$label = str_replace( ' ', '-', $data['label'] );
		$label = preg_replace( '/[^\w-]/', '', $label );
		$class[] = strtolower($label);
	}

	// Display classes
	if ( ! empty( $data['display'] ) ) {
		$class = array_merge( $class, themeblvd_get_display_class( $data['display'] ) );
	}

	return apply_filters( 'themeblvd_section_class', $class, $data, $count );
}

/**
 * Get class for a set of display options. Used for
 * sections and columns, NOT elements.
 *
 * @since 2.5.0
 *
 * @param array $display Display options
 * @return string $style Inline style line to be used
 */
function themeblvd_get_display_class( $display ) {

	$class = array();

	if ( ! empty( $display['bg_type'] ) ) {

		$bg_type = $display['bg_type'];

		if ( $bg_type == 'none' ) {

			$class[] = 'standard';

		} else {

			$class[] = 'has-bg';
			$class[] = $bg_type;

			if ( in_array( $bg_type, array('color', 'image', 'texture', 'slideshow', 'video') ) ) {
				if ( ! empty( $display['text_color'] ) && $display['text_color'] != 'none' ) {
					$class[] = 'text-'.$display['text_color'];
				}
			}

			if ( ( $bg_type == 'image' || $bg_type == 'slideshow' || $bg_type == 'video' ) && ! empty( $display['apply_bg_shade'] ) ) {
				$class[] = 'has-bg-shade';
			}

		}

		if ( $bg_type == 'texture' ) {

			if ( ! empty( $display['apply_bg_texture_parallax'] ) ) {
				$class[] = 'tb-parallax';
			}

			if ( ! empty( $display['bg_texture'] ) ) {
				$texture = themeblvd_get_texture( $display['bg_texture'] );
				$class[] = $texture['repeat'];
			}

		} else if ( $bg_type == 'image' ) {

			if ( ! empty( $display['bg_image']['attachment'] ) && $display['bg_image']['attachment'] == 'parallax' ) {
				$class[] = 'tb-parallax';
			}

			if ( ! empty( $display['bg_image']['repeat'] ) ) {
				$class[] = $display['bg_image']['repeat'];
			}

		} else if ( $bg_type == 'slideshow' ) {

			$class[] = 'has-bg-slideshow';

		}  else if ( $bg_type == 'video' ) {

			$class[] = 'has-bg-video';

		}

		// Custom padding?
		if ( ! empty( $display['apply_padding'] ) ) {
			$class[] = 'has-custom-padding';
		}

		// Responsive visibility
		if ( ! empty( $display['hide'] ) ) {
			$class[] = themeblvd_responsive_visibility_class( $display['hide'] );
		}

		// User-added CSS classes
		if ( ! empty( $display['classes'] ) ) {
			$class[] = $display['classes'];
		}

	}

	return apply_filters( 'themeblvd_display_class', $class, $display );
}

/**
 * Get inline styles for a set of display options.
 *
 * @since 2.5.0
 *
 * @param array $display Display options
 * @param string $print How to return the styles, use "inline" or "external"
 * @return string $style Inline style line to be used
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

	if ( $bg_type == 'image' && ! empty( $display['bg_image']['attachment'] ) && $display['bg_image']['attachment'] == 'parallax' ) {
		$parallax = true;
	}

	if ( $bg_type == 'texture' && ! empty($display['apply_bg_texture_parallax']) ) {
		$parallax = true;
	}

	if ( in_array( $bg_type, array('color', 'texture', 'image', 'video', 'none') ) ) {

		if ( ( $bg_type == 'none' && empty($display['bg_content']) ) || $parallax ) {

			$params['background-color'] = 'transparent';

		} else if ( ! empty( $display['bg_color'] ) ) {

			$bg_color = $display['bg_color'];

			$params['background-color'] = $bg_color; // non-rgba, for old browsers

			if ( ! empty( $display['bg_color_opacity'] ) ) {
				$bg_color = themeblvd_get_rgb( $bg_color, $display['bg_color_opacity'] );
			}

			$params['background-color-2'] = $bg_color;

		}

		if ( $bg_type == 'texture' && ! $parallax ) {

			$textures = themeblvd_get_textures();

			if ( ! empty( $display['bg_texture'] ) && ! empty( $textures[$display['bg_texture']] ) ) {

				$texture = $textures[$display['bg_texture']];

				$params['background-image'] = sprintf('url(%s)', esc_url($texture['url']));
				$params['background-position'] = $texture['position'];
				$params['background-repeat'] = $texture['repeat'];
				$params['background-size'] = $texture['size'];

			}

		} else if ( $bg_type == 'image' && ! $parallax ) {

			$repeat = false;

			if ( ! empty( $display['bg_image']['image'] ) ) {
				$params['background-image'] = sprintf('url(%s)', esc_url($display['bg_image']['image']));
			}

			if ( ! empty( $display['bg_image']['repeat'] ) ) {

				if ( $display['bg_image']['repeat'] != 'no-repeat' ) {
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

		} else if ( $bg_type == 'video' ) {

			if ( ! empty( $display['bg_video']['fallback'] ) ) {
				$params['background-image'] = sprintf('url(%s)', esc_url($display['bg_video']['fallback']));
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

			if ( ! empty($display['apply_popout']) ) {
				$params['padding-right'] .= ' !important'; // override popout
			}
		}

		if ( ! empty( $display['padding_left'] ) ) {

			$params['padding-left'] = $display['padding_left'];

			if ( ! empty($display['apply_popout']) ) {
				$params['padding-left'] .= ' !important'; // override popout
			}
		}

	}

	if ( $print == 'external' ) {

		$params = array(
			'general'	=> $params,
			'desktop'	=> array(),
			'tablet'	=> array(),
			'mobile'	=> array()
		);

		foreach ( $params as $key => $value ) {

			if ( $key == 'general' ) {
				continue;
			}

			if ( ! empty( $display['apply_padding_'.$key] ) ) {

				if ( ! empty( $display['padding_top_'.$key] ) ) {
					$params[$key]['padding-top'] = $display['padding_top_'.$key];
				}

				if ( ! empty( $display['padding_bottom_'.$key] ) ) {
					$params[$key]['padding-bottom'] = $display['padding_bottom_'.$key];
				}

				if ( ! empty( $display['padding_right_'.$key] ) ) {
					$params[$key]['padding-right'] = $display['padding_right_'.$key];
				}

				if ( ! empty( $display['padding_left_'.$key] ) ) {
					$params[$key]['padding-left'] = $display['padding_left_'.$key];
				}

			}

		}

	}

	$params = apply_filters( 'themeblvd_display_inline_style', $params, $display );

	if ( $print == 'inline' ) {

		foreach ( $params as $key => $value ) {
			$key = str_replace('-2', '', $key);
			$style .= sprintf( '%s: %s; ', $key, $value );
		}

		return trim( esc_attr($style) );
	}

	return $params;
}

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
		'section'		=> '',
		'layout_id'		=> 0,
		'element_id'	=> 'element_',
		'num'			=> 1,
		'widths'		=> 'grid_12',
		'stack'			=> 'md',
		'height'		=> 0,
		'align'			=> 'top'
	);
	$args = wp_parse_args( $args, $defaults );

	// Number of columns
	$num = intval( $args['num'] );

	// Bootstrap stack point
	$stack = $args['stack'];

	if ( themeblvd_is_ie( array('8') ) ) {
		$stack = 'xs';
	}

	// Kill it if number of columns doesn't match the
	// number of widths exploded from the string.
	$widths = explode( '-', $args['widths'] );

	if ( $num != count( $widths ) ) {
		return;
	}

	// Column Margins
	//
	// Problem: By default with Bootstrap, a row of columns
	// has -15px margin on the sides. However, when a background is
	// applied to a column, we need to eliminate that so the background
	// of the column doesn't over hang outside of the container.
	// Note: Using Bootstrap's "container-fluid" class will not work
	// in this case, because we're doing this per individual side.
	//
	// Solution: If it's the first column and has a BG, change row
	// left margin to 0, and if the last column has a BG, change row
	// right margin to 0.
	$margin_left = '-15px';
	$margin_right = '-15px';

	for ( $i = 1; $i <= $num; $i++ ) {

		// If first or last
		if ( $i == 1 || $i == $num ) {

			$column = get_post_meta( $args['layout_id'], '_tb_builder_'.$args['element_id'].'_col_'.strval($i), true ); // Ex: _tb_builder_element_123_col_1

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
		$open_row = array(
			'wrap'	=> "container-{$stack}-height",
			'class'	=> "row stack-{$stack} row-{$stack}-height",
			'style'	=> $margin
		);
	} else {
		$open_row = array(
			'class'	=> "row stack-{$stack}",
			'style'	=> $margin
		);
	}

	themeblvd_open_row($open_row);

	// Display columns
	for ( $i = 1; $i <= $num; $i++ ) {

		$grid_class = themeblvd_grid_class( $widths[$i-1], $stack );

		// Equal height columns?
		if ( $args['height'] ) {

			$grid_class .= " col-{$stack}-height";

			if ( in_array( $args['align'], array( 'top', 'middle', 'bottom' ) ) ) {
				$grid_class .= ' col-'.$args['align'];
			}
		}

		if ( $args['layout_id'] == 0 && $columns ) {

			echo '<div class="col entry-content '.esc_attr($grid_class).'">'; // "entry-content" class only because not using elements

			if ( isset( $columns[$i] ) ) {

				switch ( $columns[$i]['type'] ) {

	                case 'widget' :
						themeblvd_widgets( $columns[$i]['sidebar'], 'tabs' );
	                    break;

	                case 'page' :
	                    themeblvd_post_content( $columns[$i]['page'], 'page' );
	                    break;

	                case 'raw' :
	                    if ( ! empty( $columns[$i]['raw_format'] ) ) {
	                        themeblvd_content( $columns[$i]['raw'] );
	                    } else {
	                        echo do_shortcode( themeblvd_kses($columns[$i]['raw']) );
	                    }
	                    break;

	            }

			}

			echo '</div><!-- .'.esc_attr($grid_class).' (end) -->';

		} else {

			$blocks = array();
			$display = array();
			$column = get_post_meta( $args['layout_id'], '_tb_builder_'.$args['element_id'].'_col_'.strval($i), true ); // Ex: _tb_builder_element_123_col_1

			// Display options
			if ( ! empty( $column['display'] ) ) {
				$display = $column['display'];
			}

			// Start column
			$display_class = implode( ' ', themeblvd_get_display_class($display) );
			printf('<div class="col %s %s" style="%s">', esc_attr($grid_class), esc_attr($display_class), themeblvd_get_display_inline_style($display) );

			// Add background shade
			if ( themeblvd_do_bg_shade( $display ) ) {
				themeblvd_bg_shade( $display );
			}

			// Add parallax effect
			if ( themeblvd_do_parallax( $display ) ) {
				themeblvd_bg_parallax( $display );
			}

			// Content blocks
			if ( ! empty( $column['elements'] ) ) {
				$blocks = $column['elements'];
			}

			// Max container width for elements
			$width_atts = array(
				'context'	=> 'block',
				'col'		=> $widths[$i-1]
			);

			// Display elements
			themeblvd_elements( $args['section'], $blocks, 'block', themeblvd_get_max_width($width_atts) );

			echo '</div><!-- .'.$grid_class.' (end) -->';

		}

	}

	if ( $args['height'] ) {
		themeblvd_close_row( array('wrap' => true) );
	} else {
		themeblvd_close_row();
	}
}

/**
 * Get hero unit slider
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for hero unit.
 * @return string $output Output for hero unit slider
 */
function themeblvd_get_jumbotron_slider( $args ) {

    $defaults = array(
		'section'		=> '',					// Section ID (not really used for anything)
        'layout_id'		=> 0,					// Current ID of custom layout or page (to pull hero units from meta)
		'element_id'	=> 'element_',			// Current ID of element (also to pull hero units from meta)
		'fx'            => 'fade',          	// Slide transition effect (slide or fade) -- option from builder currently disabled, will always be fade
        'timeout'       => '3',         		// Secods in between transitions, can be 0 for no auto rotation
        'nav'           => '1'         			// Whether to show slider navigation
    );
    $args = wp_parse_args( $args, $defaults );

	$output = '';

	// Get data with hero units, saved outside of initial layout elements
	$data = get_post_meta( $args['layout_id'], '_tb_builder_'.$args['element_id'].'_col_1', true ); // Ex: _tb_builder_element_123_col_1

	if ( $data && ! empty($data['elements']) ) {

		// Create ID for slider
		$slider_id = str_replace('element_', 'jumbotron_slider_', $args['element_id']);

		// Wrapping CSS classes
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
			$class .= ' fs'; // Added if all slides are full viewport height
		}

		// Slider auto rotate speed
		$interval = esc_attr($args['timeout']);

		if ( wp_is_mobile() ) {
			$interval = '0'; // disable auto rotation for true mobile devices
		}

		if ( $interval && intval($interval) < 100 ) {
			$interval .= '000'; // User has inputted seconds, so we convert to milliseconds
		}

		// Start output
		$output .= sprintf( '<div id="%s" class="%s" data-ride="carousel" data-interval="%s" data-pause="hover">', $slider_id, $class, $interval );
		$output .= themeblvd_get_loader();
		$output .= '<div class="carousel-control-wrap">';
		$output .= '<div class="carousel-inner">';

		$count = 1;

		foreach ( $data['elements'] as $elem ) {

			$class = 'item';

			if ( $count == 1 ) {
				$class .= ' active';
			}

			$output .= sprintf('<div class="%s">%s</div>', $class, themeblvd_get_jumbotron( $elem['options'] ));

			$count++;
		}

		$output .= '</div><!-- .carousel-inner (end) -->';

		// Navigation dots
		if ( $args['nav'] ) {
			if ( $data && ! empty($data['elements']) ) {

				$output .= '<ol class="carousel-indicators">';
				$count = 0;

				foreach ( $data['elements'] as $elem ) {

					$class = '';

					if ( $count == 0 ) {
						$class = 'active';
					}

					$output .= sprintf( '<li data-target="#%s" data-slide-to="%s" class="%s"></li>', $slider_id, $count, $class );

					$count++;
				}

				$output .= '</ol>';
			}
	    }

		// Directional nav
		$output .= themeblvd_get_slider_controls( array('carousel' => $slider_id, 'color' => 'trans') );

		$output .= '</div><!-- .carousel-control-wrap (end) -->';
		$output .= '</div><!-- .tb-jumbotron-slider (end) -->';

	}
    return apply_filters( 'themeblvd_jumbotron_slider', $output, $args );
}

/**
 * Display hero unit
 *
 * @since 2.4.2
 *
 * @param array $args Arguments for hero unit.
 */
function themeblvd_jumbotron_slider( $args ) {
    echo themeblvd_get_jumbotron_slider( $args );
}

/**
 * If a page with custom layout has a featured image above
 * content applied, bump the section start count from
 * 1 to 2. This way, the featured image essentially becomes
 * the first section of the layout.
 *
 * @since 2.5.2
 *
 * @param int $count Number to start section count at, equal to 1 by default
 */
function themeblvd_builder_section_start_count( $count ) {

	if ( themeblvd_get_att('epic_thumb') ) {
		$count++;
	}

	return $count;
}
