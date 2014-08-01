<?php
/**
 * Generates the options fields that are used in forms for
 * internal options framework.
 *
 * Total props to Devin Price for originally creating this function
 * for his "Options Framework" -- This function has since been adapted
 * over time to be utilized throughout many parts of the Theme Blvd
 * theme framework.
 * Devin Price's website: http://wptheming.com
 *
 * @since 2.2.0
 *
 * @param string $option_name Prefix for all field name attributes
 * @param array $options All options to show in form
 * @param array $settings Any current settings for all form fields
 * @param boolean $close Whether to add closing </div>
 * @return array $form Final options form
 */
function themeblvd_option_fields( $option_name, $options, $settings, $close = true ) {

    $counter = 0;
	$menu = '';
	$output = '';
	$advanced = Theme_Blvd_Advanced_Options::get_instance();

	foreach ( $options as $value ) {

		$counter++;
		$val = '';
		$select_value = '';
		$checked = '';
		$class = '';

		// Sub Groups --
		// This allows for a wrapping div around groups of elements.
		// The primary reason for this is to help link certain options
		// together in order to apply custom javascript for certain
		// common groups.
	   	if ( $value['type'] == 'subgroup_start' ) {
	   		if ( isset( $value['class'] ) ) {
	   			$class = ' '.$value['class'];
	   		}
	   		$output .= '<div class="subgroup'.$class.'">';
	   		continue;
	   	}

	   	if ( $value['type'] == 'subgroup_end' ) {
	   		$output .= '</div><!-- .subgroup (end) -->';
	   		continue;
	   	}

	   	// Name Grouping --
	   	// This allows certain options to be grouped together in the
	   	// final saved options array by adding a common prefix to their
	   	// name form attributes.
	   	if ( isset( $value['group'] ) ) {
	   		$option_name .= '['.$value['group'].']';
	   	}

	   	// Sections --
		// This allows for a wrapping div around certain sections. This
		// is meant to create visual dividing styles between sections,
		// opposed to sub groups, which are used to section off the code
		// for hidden purposes.
	   	if ( $value['type'] == 'section_start' ) {

	   		$name = ! empty( $value['name'] ) ? esc_html( $value['name'] ) : '';

	   		if ( isset( $value['class'] ) ) {
	   			$class = ' '.$value['class'];
	   		}

	   		if ( ! $name ) {
	   			$class .= ' no-name';
	   		}

	   		$output .= '<div class="postbox inner-section'.$class.'">';

	   		if ( $name ) {
	   			$output .= '<h3>'.$name.'</h3>';
	   		}

	   		if ( ! empty( $value['desc'] ) ) {
	   			$output .= '<div class="section-description">'.$value['desc'].'</div>';
	   		}

	   		continue;
	   	}
	   	if ( $value['type'] == 'section_end' ) {
	   		$output .= '</div><!-- .inner-section (end) -->';
	   		continue;
	   	}

		// Set default value to $val
		if ( isset( $value['std'] ) ) {
			$val = $value['std'];
		}

		// If the option is already saved, override $val
		if ( $value['type'] != 'heading' && $value['type'] != 'info' ) {
			if ( isset( $value['group'] ) ) {

				// Set grouped value
				if ( isset( $settings[($value['group'])][($value['id'])] ) ) {

					$val = $settings[($value['group'])][($value['id'])];

					// Striping slashes of non-array options
					if ( ! is_array( $val ) ) {
						$val = stripslashes( $val );
					}
				}
			} else {

				// Set non-grouped value
				if ( isset($settings[($value['id'])]) ) {

					$val = $settings[($value['id'])];

					// Striping slashes of non-array options
					if ( ! is_array( $val ) ) {
						$val = stripslashes( $val );
					}
				}
			}
		}

		// Hidden options
		if ( $value['type'] == 'hidden' ) {

			$class = 'section section-hidden hide';
			if ( ! empty( $value['class'] ) ) {
				$class .= ' '.$value['class'];
			}

			$output .= sprintf('<div class="%s">', $class);
			$output .= sprintf( '<input id="%s" class="of-input" name="%s" type="text" value="%s" />', esc_attr( $value['id'] ), esc_attr( $option_name.'['.$value['id'].']' ), stripslashes( esc_attr( $val ) ) );
			$output .= '</div>';
			continue;
		}

		// Wrap all options
		if ( $value['type'] != 'heading' && $value['type'] != 'info' ) {

			// Keep all ids lowercase with no spaces
			$value['id'] = preg_replace('/\W/', '', strtolower($value['id']) );

			// Determine CSS classes
			$id = 'section-'.$value['id'];
			$class = 'section ';

			if ( isset( $value['type'] ) ) {

				$class .= ' section-'.$value['type'];

				if ( $advanced->is_sortable( $value['type'] ) ) {
					$class .= ' section-sortable';
				}

				if ( $value['type'] == 'logo' || $value['type'] == 'background' ) {
					$class .= ' section-upload';
				}
			}

			if ( ! empty( $value['class'] ) ) {
				$class .= ' '.$value['class'];
			}

			// Start Output
			$output .= '<div id="'.esc_attr( $id ) .'" class="'.esc_attr( $class ).'">'."\n";

			if ( ! empty( $value['name'] ) ) { // Name not required
				$output .= '<h4 class="heading">'.esc_html( $value['name'] ).'</h4>'."\n";
			}

			$output .= '<div class="option">'."\n".'<div class="controls">'."\n";
		}

        // Add each option to output based on type.
		switch ( $value['type'] ) {

			/*---------------------------------------*/
			/* Basic Text Input
			/*---------------------------------------*/

			case 'text':

				$place_holder = '';
				if ( ! empty( $value['pholder'] ) ) {
					$place_holder = ' placeholder="'.$value['pholder'].'"';
				}

				$output .= '<div class="input-wrap">';

				if ( isset($value['icon']) ) {
					if ( $value['icon'] == 'image' || $value['icon'] == 'vector' ) {
						$output .= '<a href="#" class="tb-input-icon-link tb-tooltip-link" data-target="themeblvd-icon-browser-'.$value['icon'].'" data-icon-type="'.$value['icon'].'" data-tooltip-text="'.__('Browse Icons', 'themeblvd').'"><i class="tb-icon-picture"></i></a>';
					} else if ( $value['icon'] == 'post_id' ) {
						$output .= '<a href="#" class="tb-input-post-id-link tb-tooltip-link" data-target="themeblvd-post-browser" data-icon-type="post_id" data-tooltip-text="'.__('Find Post or Page ID', 'themeblvd').'"><i class="tb-icon-barcode"></i></a>';
					}
				}

				$output .= sprintf( '<input id="%s" class="of-input" name="%s" type="text" value="%s"%s />', esc_attr( $value['id'] ), esc_attr( $option_name.'['.$value['id'].']' ), stripslashes( esc_attr( $val ) ), $place_holder );
				$output .= '</div><!-- .input-wrap (end) -->';
				break;

			/*---------------------------------------*/
			/* Text Area
			/*---------------------------------------*/

			case 'textarea' :

				$place_holder = '';
				if ( ! empty( $value['pholder'] ) ) {
					$place_holder = ' placeholder="'.$value['pholder'].'"';
				}

				$cols = '8';
				if ( isset( $value['options'] ) && isset( $value['options']['cols'] ) ) {
					$cols = $value['options']['cols'];
				}

				if ( ! empty( $value['editor'] ) ) {

					$output .= '<div class="textarea-wrap with-editor-nav">';

					$output .= '<nav class="editor-nav">';

					if ( ! empty( $value['editor'] ) ) {
						$output .= '<a href="#" class="tb-textarea-editor-link tb-tooltip-link" data-tooltip-text="'.__('Open in Editor', 'themeblvd').'" data-target="themeblvd-editor-modal"><i class="tb-icon-pencil"></i></a>';
					}

					if ( isset( $value['code'] ) && in_array( $value['code'], array( 'html', 'javascript', 'css' ) ) ) {
						$output .= '<a href="#" class="tb-textarea-code-link tb-tooltip-link" data-tooltip-text="'.__('Open in Code Editor', 'themeblvd').'" data-target="'.esc_textarea( $value['id'] ).'" data-title="'.$value['name'].'" data-code_lang="'.$value['code'].'"><i class="tb-icon-code"></i></a>';
					}

					$output .= '</nav>';

				} else {
					$output .= '<div class="textarea-wrap">';
				}

				$output .= sprintf( '<textarea id="%s" class="of-input" name="%s" cols="%s" rows="8"%s>%s</textarea>', esc_textarea( $value['id'] ), stripslashes( esc_attr( $option_name.'['.$value['id'].']') ), esc_attr( $cols ), $place_holder, esc_textarea( $val ) );
				$output .= '</div><!-- .textarea-wrap (end) -->';
				break;

			/*---------------------------------------*/
			/* Select
			/*---------------------------------------*/

			case 'select' :

				$error = '';
				$textures = false;

				// Dynamic select types
				if ( ! isset( $value['options'] ) && isset( $value['select'] ) ) {

					$value['options'] = array();

					switch ( $value['select'] ) {

						case 'pages' :

							$value['options'] = themeblvd_get_select( 'pages' );

							if ( count( $value['options'] ) < 1 ) {
								$error = __('No pages were found.', 'themeblvd');
							}
							break;

						case 'categories' :

							$value['options'] = themeblvd_get_select( 'categories' );

							if ( count( $value['options'] ) < 1 ) {
								$error = __('No categories sidebars were found.', 'themeblvd');
							}
							break;

						case 'sidebars' :

							if ( ! defined( 'TB_SIDEBARS_PLUGIN_VERSION' ) ) {
								$error = __('You must install the Theme Blvd Widget Areas plugin in order to insert a floating widget area.', 'themeblvd');
							}

							$value['options'] = themeblvd_get_select( 'sidebars' );

							if ( count( $value['options'] ) < 1 ) {
								$error = __('No floating widget areas were found.', 'themeblvd');
							}
							break;

						case 'sidebars_all' :

							$value['options'] = themeblvd_get_select( 'sidebars_all' );

							if ( count( $value['options'] ) < 1 ) {
								$error = __('No registered sidebars were found.', 'themeblvd');
							}
							break;

						case 'crop' :
							$value['options'] = themeblvd_get_select( 'crop' );

							if ( count( $value['options'] ) < 1 ) {
								$error = __('No registered crop sizes were found.', 'themeblvd');
							}
							break;

						case 'textures' :
							$textures = true;

							$value['options'] = themeblvd_get_select( 'textures' );

							if ( count( $value['options'] ) < 1 ) {
								$error = __('No textures were found.', 'themeblvd');
							}
							break;
					}

				}

				// If any dynamic selects caused errors,
				// don't display a select menu.
				if ( $error ) {
					$output .= sprintf('<p class="warning">%s</p>', $error);
					break;
				}

				// Start output for <select>
				$output .= '<div class="tb-fancy-select">';
				$output .= sprintf( '<select class="of-input" name="%s" id="%s">', esc_attr( $option_name.'['.$value['id'].']' ), esc_attr($value['id']) );

				$first = reset($value['options']);

				if ( is_array( $first ) ) {

					// Option groups
					foreach ( $value['options'] as $optgroup_id => $optgroup ) {

						$output .= sprintf('<optgroup label="%s">', $optgroup['label']);

						foreach ( $optgroup['options'] as $key => $option ) {
							$output .= sprintf( '<option%s value="%s">%s</option>', selected( $key, $val, false ), esc_attr( $key ), esc_html($option) );
						}

						$output .= '</optgroup>';
					}

				} else {

					// Standard
					foreach ( $value['options'] as $key => $option ) {
						$output .= sprintf( '<option%s value="%s">%s</option>', selected( $key, $val, false ), esc_attr( $key ), esc_html($option) );
					}

				}

				$output .= '</select>';
				$output .= '<span class="trigger"></span>';
				$output .= '<span class="textbox"></span>';
				$output .= '</div><!-- .tb-fancy-select (end) -->';

				if ( $textures ) {
					$output .= '<a href="#" class="tb-texture-browser-link" data-target="themeblvd-texture-browser">'.__('Browse Textures', 'themeblvd').'</a>';
				}

				// If this is a builder sample select, show preview images
				if ( isset( $value['class'] ) && strpos($value['class'], 'builder-samples') !== false ) {
					if ( function_exists( 'themeblvd_builder_sample_previews' ) ) {
						$output .= themeblvd_builder_sample_previews();
					}
				}
				break;

			/*---------------------------------------*/
			/* Radio
			/*---------------------------------------*/

			case 'radio' :

				$name = sprintf( '%s[%s]', $option_name, $value['id'] );

				foreach ( $value['options'] as $key => $option ) {
					$id = sprintf( '%s-%s-%s', $option_name, $value['id'], $key );
					$output .= '<div class="radio-input clearfix">';
					$output .= sprintf( '<input class="of-input of-radio" type="radio" name="%s" id="%s" value="%s" %s />', esc_attr($name), esc_attr($id), esc_attr($key), checked( $val, $key, false ) );
					$output .= sprintf( '<label for="%s">%s</label>', esc_attr($id), esc_html($option) );
					$output .= '</div><!-- .radio-input (end) -->';
				}
				break;

			/*---------------------------------------*/
			/* Image Selectors
			/*---------------------------------------*/

			case 'images' :

				$name = sprintf( '%s[%s]', $option_name, $value['id'] );

				$width = '';
				if ( isset( $value['img_width'] ) ) {
					$width = $value['img_width'];
				}

				foreach ( $value['options'] as $key => $option ) {

					$selected = '';
					$checked = checked( $val, $key, false );
					$selected = $checked ? ' of-radio-img-selected' : '';

					$output .= sprintf( '<input type="radio" id="%s" class="of-radio-img-radio" value="%s" name="%s" %s />', esc_attr($value['id'].'_'.$key), esc_attr($key), esc_attr($name), $checked );
					$output .= sprintf( '<div class="of-radio-img-label">%s</div>', esc_html($key) );
					$output .= sprintf( '<img src="%s" alt="%s" class="of-radio-img-img%s" width="%s" onclick="document.getElementById(\'%s\').checked=true;" />', esc_url($option), $option, $selected, $width, esc_attr($value['id'].'_'.$key) );

				}
				break;

			/*---------------------------------------*/
			/* Checkbox
			/*---------------------------------------*/

			case 'checkbox' :

				if ( ! empty( $value['inactive'] ) ) {
					if ( $value['inactive'] === 'true' ) {
						$val = 1;
					} else if( $value['inactive'] === 'false' ) {
						$val = 0;
					}
				}

				$name = sprintf( '%s[%s]', $option_name, $value['id'] );
				$checkbox = sprintf( '<input id="%s" class="checkbox of-input" type="checkbox" name="%s" %s />', esc_attr($value['id']), esc_attr($name), checked( $val, 1, false ) );

				if ( ! empty( $value['inactive'] ) ) {
					$checkbox = str_replace( '/>', 'disabled="disabled" />', $checkbox );
				}

				$output .= $checkbox;

				break;

			/*---------------------------------------*/
			/* Multicheck
			/*---------------------------------------*/

			case 'multicheck' :
				foreach ( $value['options'] as $key => $option ) {

					$checked = isset( $val[$key] ) ? checked( $val[$key], 1, false ) : '';
					$label = $option;
					$option = preg_replace( '/\W/', '', strtolower( $key ) );

					$id = sprintf( '%s-%s-%s', $option_name, $value['id'], $option );
					$name = sprintf( '%s[%s][%s]', $option_name, $value['id'], $key );

					$class = 'checkbox of-input';

					if ( $key == 'all' ) {
						$class .= ' all';
					}

					$output .= sprintf( '<input id="%s" class="%s" type="checkbox" name="%s" %s /><label for="%s">%s</label>', esc_attr($id), $class, esc_attr($name), $checked, esc_attr($id), $label );
				}
				break;

			/*---------------------------------------*/
			/* Color picker
			/*---------------------------------------*/

			case 'color' :
				$output .= sprintf( '<input id="%s" name="%s" type="text" value="%s" class="tb-color-picker" data-default-color="%s" />', esc_attr( $value['id'] ), esc_attr( $option_name.'['.$value['id'].']' ), esc_attr( $val ), esc_attr( $value['std'] ) );
				break;

			/*---------------------------------------*/
			/* Uploader
			/*---------------------------------------*/

			case 'upload' :

				// Media uploader WP 3.5+
				$args = array(
					'option_name'	=> $option_name,
					'id'			=> $value['id']
				);

				if ( ! empty( $value['advanced'] ) ) {

					// Advanced type will allow for selecting
					// image crop size for URL.
					$args['type'] = 'advanced';

					if ( isset( $val['src'] ) ) {
						$args['value_src'] = $val['src'];
					}

					if ( isset( $val['id'] ) ) {
						$args['value_id'] = $val['id'];
					}

					if ( isset( $val['title'] ) ) {
						$args['value_title'] = $val['title'];
					}

					if ( isset( $val['crop'] ) ) {
						$args['value_crop'] = $val['crop'];
					}

					if ( isset( $val['width'] ) ) {
						$args['value_width'] = $val['width'];
					}

					if ( isset( $val['height'] ) ) {
						$args['value_height'] = $val['height'];
					}

				} else {

					$args['value'] = $val;
					$args['type'] = 'standard';

					if ( isset( $value['send_back'] ) ) {
						$args['send_back'] = $value['send_back'];
					} else {
						$args['send_back'] = 'url';
					}

					if ( ! empty( $value['video'] ) ) {
						$args['type'] = 'video';
					}
				}

				$output .= themeblvd_media_uploader( $args );

				break;

			/*---------------------------------------*/
			/* Typography
			/*---------------------------------------*/

			case 'typography' :

				$typography_stored = $val;

				// Font Size
				if ( in_array( 'size', $value['atts'] ) ) {

					$output .= '<div class="jquery-ui-slider-wrap">';

					$sizes = themeblvd_recognized_font_sizes();
					$options = array();
					$options['min'] = intval($sizes[0]);
					$options['max']	= intval(end($sizes));
					$options['step'] = intval($sizes[1])-intval($sizes[0]);
					$options['units'] = 'px';

					$output .= '<div class="jquery-ui-slider"';

					foreach ( $options as $param_id => $param ) {
						$output .= sprintf( ' data-%s="%s"', $param_id, $param );
					}

					$output .= '></div>';

					$output .= sprintf( '<input id="%s" class="of-input slider-input" name="%s" type="hidden" value="%s" />', esc_attr( $value['id'].'_size' ), esc_attr( $option_name.'['.$value['id'].'][size]' ), $typography_stored['size'] );
					$output .= '</div><!-- .jquery-ui-slider-wrap (end) -->';

				}

				// Font Style
				if ( in_array( 'style', $value['atts'] ) ) {

					$output .= '<div class="tb-fancy-select">';
					$output .= '<select class="of-typography of-typography-style" name="'.esc_attr( $option_name.'['.$value['id'].'][style]' ).'" id="'.esc_attr( $value['id'].'_style' ).'">';

					$styles = themeblvd_recognized_font_styles();
					foreach ( $styles as $key => $style ) {
						$output .= '<option value="'.esc_attr( $key ).'" '.selected( $typography_stored['style'], $key, false ).'>'.esc_html( $style ).'</option>';
					}

					$output .= '</select>';
					$output .= '<span class="trigger"></span>';
					$output .= '<span class="textbox"></span>';
					$output .= '</div><!-- .tb-fancy-select (end) -->';
				}

				// Font Face
				if ( in_array( 'face', $value['atts'] ) ) {

					$output .= '<div class="tb-fancy-select">';
					$output .= '<select class="of-typography of-typography-face" name="'.esc_attr( $option_name.'['.$value['id'].'][face]' ).'" id="'.esc_attr( $value['id'].'_face' ).'">';

					$faces = themeblvd_recognized_font_faces();
					foreach ( $faces as $key => $face ) {
						$output .= '<option value="'.esc_attr( $key ).'" '.selected( $typography_stored['face'], $key, false ).'>'.esc_html( $face ).'</option>';
					}

					$output .= '</select>';
					$output .= '<span class="trigger"></span>';
					$output .= '<span class="textbox"></span>';
					$output .= '</div><!-- .tb-fancy-select (end) -->';
				}

				// Font Color
				if ( in_array( 'color', $value['atts'] ) ) {
					$output .= '<div id="'.esc_attr( $value['id'] ).'_color_picker" class="colorSelector"><div style="'.esc_attr( 'background-color:'.$typography_stored['color'] ).'"></div></div>';
					$output .= '<input class="of-color of-typography of-typography-color" name="'.esc_attr( $option_name.'['.$value['id'].'][color]' ).'" id="'.esc_attr( $value['id'].'_color' ).'" type="text" value="'.esc_attr( $typography_stored['color'] ).'" />';
				}

				$output .= '<div class="clear"></div>';

				// Google Font support
				if ( in_array( 'face', $value['atts'] ) ) {
					$output .= '<div class="google-font hide">';
					$output .= '<h5>'.__( 'Enter the name of a font from the <a href="http://www.google.com/webfonts" target="_blank">Google Font Directory</a>.', 'themeblvd' ).'</h5>';
					$output .= '<input type="text" name="'.esc_attr( $option_name.'['.$value['id'].'][google]' ).'" value="'.esc_attr( $typography_stored['google'] ).'" />';
					$output .= '<p class="note">Example Font Name: "Hammersmith One"</p>';
					$output .= '</div>';
				}

				break;

			/*---------------------------------------*/
			/* Background
			/*---------------------------------------*/

			case 'background':

				$background = $val;

				// Show background color?
				$color = true;
				if ( isset( $value['color'] ) ) {
					$color = $value['color'];
				}

				// Background Color
				if ( $color ) {

					$current_color = '';
					if ( ! empty( $background['color'] ) ) {
						$current_color = $background['color'];
					}

					$output .= sprintf( '<input id="%s_color" name="%s" type="text" value="%s" class="tb-color-picker" data-default-color="%s" />', esc_attr( $value['id'] ), esc_attr( $option_name.'['.$value['id'].'][color]' ), esc_attr( $current_color ), esc_attr( $current_color ));
					$output .= '<br />';

				}

				// Background Image
				if ( ! isset( $background['image'] ) ) {
					$background['image'] = '';
				}

				// Currrent BG formatted correctly
				$current_bg_url = '';
				if ( ! empty( $background['image'] ) ) {
					$current_bg_url = $background['image'];
				}

				$current_bg_image = array(
					'url'	=> $current_bg_url,
					'id'	=> ''
				);

				// Start output

				// Uploader
				$output .= themeblvd_media_uploader( array( 'option_name' => $option_name, 'type' => 'background', 'id' => $value['id'], 'value' => $current_bg_url, 'name' => 'image' ) );

				$class = 'of-background-properties';
				if ( empty( $background['image'] ) ) {
					$class .= ' hide';
				}

				$output .= '<div class="'.esc_attr( $class ).'">';

				// Background Repeat
				$current_repeat = !empty($background['repeat']) ? $background['repeat'] : '';
				$output .= '<div class="tb-fancy-select condensed">';
				$output .= '<select class="of-background of-background-repeat" name="'.esc_attr( $option_name.'['.$value['id'].'][repeat]'  ).'" id="'.esc_attr( $value['id'].'_repeat' ).'">';
				$repeats = themeblvd_recognized_background_repeat();

				foreach ( $repeats as $key => $repeat ) {
					$output .= '<option value="'.esc_attr( $key ).'" '.selected( $current_repeat, $key, false ).'>'. esc_html( $repeat ).'</option>';
				}

				$output .= '</select>';
				$output .= '<span class="trigger"></span>';
				$output .= '<span class="textbox"></span>';
				$output .= '</div><!-- .tb-fancy-select (end) -->';

				// Background Attachment
				$current_attachment = !empty($background['attachment']) ? $background['attachment'] : '';
				$output .= '<div class="tb-fancy-select condensed">';
				$output .= '<select class="of-background of-background-attachment" name="'.esc_attr( $option_name.'['.$value['id'].'][attachment]' ).'" id="'.esc_attr( $value['id'].'_attachment' ).'">';
				$attachments = themeblvd_recognized_background_attachment();

				// Parallax scrolling
				$parallax = false;
				if ( isset( $value['parallax'] ) ) {
					$parallax = $value['parallax'];
				}

				if ( ! $parallax ) {
					unset( $attachments['parallax'] );
				}

				foreach ( $attachments as $key => $attachment ) {
					$output .= '<option value="'.esc_attr( $key ).'" '.selected( $current_attachment, $key, false ).'>'.esc_html( $attachment ).'</option>';
				}

				$output .= '</select>';
				$output .= '<span class="trigger"></span>';
				$output .= '<span class="textbox"></span>';
				$output .= '</div><!-- .tb-fancy-select (end) -->';

				// Background Position
				$current_position = !empty($background['position']) ? $background['position'] : '';
				$output .= '<div class="tb-fancy-select condensed">';
				$output .= '<select class="of-background of-background-position" name="'.esc_attr( $option_name.'['.$value['id'].'][position]' ).'" id="'.esc_attr( $value['id'].'_position' ).'">';
				$positions = themeblvd_recognized_background_position();

				foreach ( $positions as $key => $position ) {
					$output .= '<option value="'.esc_attr( $key ).'" '.selected( $current_position, $key, false ).'>'. esc_html( $position ).'</option>';
				}

				$output .= '</select>';
				$output .= '<span class="trigger"></span>';
				$output .= '<span class="textbox"></span>';
				$output .= '</div><!-- .tb-fancy-select (end) -->';

				// Background Size
				$current_size = !empty($background['size']) ? $background['size'] : '';
				$output .= '<div class="tb-fancy-select condensed">';
				$output .= '<select class="of-background of-background-size" name="'.esc_attr( $option_name.'['.$value['id'].'][size]' ).'" id="'.esc_attr( $value['id'].'_size' ).'">';

				$sizes = themeblvd_recognized_background_size();

				foreach ( $sizes as $key => $size ) {
					$output .= '<option value="'.esc_attr( $key ).'" '.selected( $current_size, $key, false ).'>'. esc_html( $size ).'</option>';
				}

				$output .= '</select>';
				$output .= '<span class="trigger"></span>';
				$output .= '<span class="textbox"></span>';
				$output .= '</div><!-- .tb-fancy-select (end) -->';

				$output .= '</div>';

				break;

			/*---------------------------------------*/
			/* Gradient
			/*---------------------------------------*/

			case 'gradient':

				$start = '';
				$start_def = '#000000';
				$end = '';
				$end_def = '#000000';

				if ( ! empty( $val['start'] ) ) {
					$start = $val['start'];
				}

				if ( ! empty( $val['end'] ) ) {
					$end = $val['end'];
				}

				if ( ! empty( $value['std']['start'] ) ) {
					$start_def = $value['std']['start'];
				}

				if ( ! empty( $value['std']['end'] ) ) {
					$end_def = $value['std']['end'];
				}

				$output .= '<div class="gradient-wrap">';

				// Start color
				$output .= '<div class="color-start">';
				$output .= sprintf( '<input id="%s_start" name="%s" type="text" value="%s" class="tb-color-picker" data-default-color="%s" />', esc_attr($value['id']), esc_attr($option_name.'['.$value['id'].'][start]'), esc_attr($start), esc_attr($start_def) );
				$output .= '<span class="color-label">'.__('Top Color', 'themeblvd').'</span>';
				$output .= '</div><!-- .color-start (end) -->';

				// End color
				$output .= '<div class="color-end">';
				$output .= sprintf( '<input id="%s_end" name="%s" type="text" value="%s" class="tb-color-picker" data-default-color="%s" />', esc_attr($value['id']), esc_attr($option_name.'['.$value['id'].'][end]'), esc_attr($end), esc_attr($end_def) );
				$output .= '<span class="color-label">'.__('Bottom Color', 'themeblvd').'</span>';
				$output .= '</div><!-- .color-end (end) -->';

				$output .= '</div><!-- .gradient-wrap (end) -->';
				break;

			/*---------------------------------------*/
			/* Button
			/*---------------------------------------*/

			case 'button':
				$output .= themeblvd_button_option( $value['id'], $option_name, $val );
				break;

			/*---------------------------------------*/
			/* Geo (Latitude and Longitude)
			/*---------------------------------------*/

			case 'geo' :

				// Values
				$lat = '';
				if ( isset( $val['lat'] ) ) {
					$lat = $val['lat'];
				}

				$long = '';
				if ( isset( $val['long'] ) ) {
					$long = $val['long'];
				}

				$output .= '<div class="geo-wrap clearfix">';

				// Latitude
				$output .= '<div class="geo-lat">';
				$output .= sprintf( '<input id="%s_lat" class="of-input geo-input" name="%s" type="text" value="%s" />', esc_attr($value['id']), esc_attr( $option_name.'['.$value['id'].'][lat]' ), esc_attr($lat) );
				$output .= '<span class="geo-label">'.__('Latitude', 'themeblvd').'</span>';
				$output .= '</div><!-- .geo-lat (end) -->';

				// Longitude
				$output .= '<div class="geo-long">';
				$output .= sprintf( '<input id="%s_long" class="of-input geo-input" name="%s" type="text" value="%s" />', esc_attr($value['id']), esc_attr( $option_name.'['.$value['id'].'][long]' ), esc_attr($long) );
				$output .= '<span class="geo-label">'.__('Longitude', 'themeblvd').'</span>';
				$output .= '</div><!-- .geo-long (end) -->';

				$output .= '</div><!-- .geo-wrap (end) -->';

				// Generate lat and long
				$output .= '<div class="geo-generate">';
				$output .= '<h5>'.__('Generate Coordinates', 'themeblvd').'</h5>';
				$output .= '<div class="data clearfix">';
				$output .= '<span class="overlay"><span class="tb-loader ajax-loading"><i class="tb-icon-spinner"></i></span></span>';
				$output .= '<input type="text" value="" class="address" />';
				$output .= sprintf( '<a href="#" class="button-secondary geo-insert-lat-long" data-oops="%s">%s</a>', __('Oops! Sorry, we weren\'t able to get coordinates from that address. Try again.', 'themeblvd'), __('Generate', 'themeblvd') );
				$output .= '</div><!-- .data (end) -->';
				$output .= '<p class="note">';
				$output .= __('Enter an address, as you would do at maps.google.com.', 'themeblvd').'<br>';
				$output .= __('Example Address', 'themeblvd').': "123 Smith St, Chicago, USA"';
				$output .= '</p>';
				$output .= '</div><!-- .geo-generate (end) -->';

				break;

			/*---------------------------------------*/
			/* Info
			/*---------------------------------------*/

			case 'info' :

				// Classes
				$class = 'section';
				if ( isset( $value['type'] ) ) {
					$class .= ' section-'.$value['type'];
				}
				if ( isset( $value['class'] ) ) {
					$class .= ' '.$value['class'];
				}

				// Start output
				$output .= '<div class="'.esc_attr( $class ).'">'."\n";

				if ( isset($value['name']) ) {
					$output .= '<h4 class="heading">'.esc_html( $value['name'] ).'</h4>'."\n";
				}

				if ( isset( $value['desc'] ) ) {
					$output .= $value['desc']."\n";
				}

				$output .= '<div class="clear"></div></div>'."\n";
				break;

			/*---------------------------------------*/
			/* Columns Setup
			/*---------------------------------------*/

			case 'columns' :
				$output .= themeblvd_columns_option( $value['options'], $value['id'], $option_name, $val );
				break;

			/*---------------------------------------*/
			/* Content --
			/* Originally designed to work in conjunction
			/* with setting up columns and tabs.
			/*---------------------------------------*/

			case 'content' :
				$output .= themeblvd_content_option( $value['id'], $option_name, $val, $value['options'] );
				break;

			/*---------------------------------------*/
			/* Conditionals --
			/* Originally designed to allow users to
			/* assign custom sidebars to certain pages.
			/*---------------------------------------*/

			case 'conditionals' :
				$output .= themeblvd_conditionals_option( $value['id'], $option_name, $val );
				break;

			/*---------------------------------------*/
			/* Logo
			/*---------------------------------------*/

			case 'logo' :
				$output .= themeblvd_logo_option( $value['id'], $option_name, $val );
				break;


			/*---------------------------------------*/
			/* Slide (jQuery UI slider)
			/*---------------------------------------*/

			case 'slide' :

				$output .= '<div class="jquery-ui-slider-wrap">';

				$options = array(
					'min'	=> '1',
					'max'	=> '100',
					'step'	=> '1',
					'units'	=> '' // for display only
				);

				if ( isset( $value['options'] ) ) {
					$options = wp_parse_args( $value['options'], $options );
				}

				$output .= '<div class="jquery-ui-slider"';

				foreach ( $options as $param_id => $param ) {
					$output .= sprintf( ' data-%s="%s"', $param_id, $param );
				}

				$output .= '></div>';

				if ( ! $val && $val !== '0' ) { // $val can't be empty or else the UI slider won't work
					$val = $options['min'].$options['units'];
				}

				$output .= sprintf( '<input id="%s" class="of-input slider-input" name="%s" type="hidden" value="%s" />', esc_attr( $value['id'] ), esc_attr( $option_name.'['.$value['id'].']' ), stripslashes( esc_attr( $val ) ) );
				$output .= '</div><!-- .jquery-ui-slider-wrap (end) -->';
				break;

			/*---------------------------------------*/
			/* Progress Bars
			/*---------------------------------------*/

			case 'bars' :
				$bars = $advanced->get('bars');
				$output .= $bars->get_display( $value['id'], $option_name, $val );
				break;

			/*---------------------------------------*/
			/* Buttons
			/*---------------------------------------*/

			case 'buttons' :
				$buttons = $advanced->get('buttons');
				$output .= $buttons->get_display( $value['id'], $option_name, $val );
				break;

			/*---------------------------------------*/
			/* Data Sets
			/*---------------------------------------*/

			case 'datasets' :
				$datasets = $advanced->get('datasets');
				$output .= $datasets->get_display( $value['id'], $option_name, $val );
				break;

			/*---------------------------------------*/
			/* Locations
			/*---------------------------------------*/

			case 'locations' :
				$locations = $advanced->get('locations');
				$output .= $locations->get_display( $value['id'], $option_name, $val );
				break;

			/*---------------------------------------*/
			/* Logos
			/*---------------------------------------*/

			case 'logos' :
				$logos = $advanced->get('logos');
				$output .= $logos->get_display( $value['id'], $option_name, $val );
				break;

			/*---------------------------------------*/
			/* Sectors
			/*---------------------------------------*/

			case 'sectors' :
				$sectors = $advanced->get('sectors');
				$output .= $sectors->get_display( $value['id'], $option_name, $val );
				break;

			/*---------------------------------------*/
			/* Slider
			/*---------------------------------------*/

			case 'slider' :
				$slider = $advanced->get('slider');
				$output .= $slider->get_display( $value['id'], $option_name, $val );
				break;

			/*---------------------------------------*/
			/* Social Media
			/*---------------------------------------*/

			case 'social_media' :
				$social_media = $advanced->get('social_media');
				$output .= $social_media->get_display( $value['id'], $option_name, $val );
				break;

			/*---------------------------------------*/
			/* Testimonials
			/*---------------------------------------*/

			case 'testimonials' :
				$testimonials = $advanced->get('testimonials');
				$output .= $testimonials->get_display( $value['id'], $option_name, $val );
				break;

			/*---------------------------------------*/
			/* Tabs
			/*---------------------------------------*/

			case 'tabs' :
				$tabs = $advanced->get('tabs');
				$output .= $tabs->get_display( $value['id'], $option_name, $val );
				break;

			/*---------------------------------------*/
			/* Toggles
			/*---------------------------------------*/

			case 'toggles' :
				$toggles = $advanced->get('toggles');
				$output .= $toggles->get_display( $value['id'], $option_name, $val );
				break;

			/*---------------------------------------*/
			/* Editor
			/*---------------------------------------*/

			case 'editor':

				// Settings
				$editor_settings = array(
					'wpautop' 			=> true,
					'text_area_name' 	=> esc_attr( $option_name.'['.$value['id'].']' ),
					'media_buttons'		=> true,
					'tinymce' 			=> array( 'plugins' => 'wordpress' ),
					'height'			=> 'small' // small, medium, large (Not part of WP's TinyMCE settings
				);

				// @todo -- Add TB shortcode generator button.
				// This will work however currently there is a quirk that won't allow for
				// more than one editor on a page. Shortcodes will get inserted in whichever
				// the last editor the cursor was in.
				/*
				if ( defined('TB_SHORTCODES_PLUGIN_VERSION') && get_option('themeblvd_shortcode_generator') != 'no' )
					$editor_settings['tinymce']['plugins'] .= ',ThemeBlvdShortcodes';
				*/

				if ( ! empty( $value['settings'] ) ) {
					$editor_settings = wp_parse_args( $value['settings'], $editor_settings );
				}

				// Setup description
				if ( ! empty( $value['desc_location'] ) && $value['desc_location'] == 'before' ) {
					$desc_location = 'before';
				} else {
					$desc_location = 'after';
				}

				$explain_value = '';
				$has_description = '';
				if ( ! empty( $value['desc'] ) ) {
					$explain_value = $value['desc'];
					$has_description = ' has-desc';
				}

				// Output description and editor
				$output .= '<div class="tb-wp-editor desc-'.$desc_location.$has_description.' height-'.$editor_settings['height'].'">';

				if ( $desc_location == 'before' ) {
					$output .= '<div class="explain">'.$explain_value.'</div>'."\n";
				}

				ob_start();
				wp_editor( $val, uniqid( $value['id'].'_'.rand() ), $editor_settings );
				$output .= ob_get_clean();

				if ( $desc_location == 'after' ) {
					$output .= '<div class="explain">'.$explain_value.'</div>'."\n";
				}

				$output .= '</div><!-- .tb-wp-editor (end) -->';

				break;

			/*---------------------------------------*/
			/* Editor that opens up in a modal
			/* (Use this editor type for any AJAX
			/* requsted options)
			/*---------------------------------------*/

			case 'editor_modal' :
				$output .= sprintf( '<textarea id="%s" class="editor-modal-content" name="%s" cols="8" rows="8">%s</textarea>', esc_textarea( $value['id'] ), stripslashes( esc_attr( $option_name.'['.$value['id'].']') ), esc_textarea( $val ) );
				break;

			/*---------------------------------------*/
			/* Code Editor
			/*---------------------------------------*/

			case 'code' :

				$id = uniqid('code_editor_');

				$lang = 'html';
				if ( isset( $value['lang'] ) && in_array( $value['lang'], array( 'javascript', 'html', 'css' )) ) {
					$lang = $value['lang'];
				}

				$output .= '<div class="textarea-wrap">';
				$output .= sprintf( '<textarea id="%s" data-code-lang="%s" name="%s" rows="8">%s</textarea>', $id, $lang, stripslashes( esc_attr( $option_name.'['.$value['id'].']') ), esc_textarea( $val ) );
				$output .= '</div><!-- .textarea-wrap (end) -->';

				break;

			/*---------------------------------------*/
			/* Heading for Navigation
			/*---------------------------------------*/

			case 'heading' :

				if ( $menu ) {
				   $output .= '</div>'."\n";
				}

				$id = $value['name'];
				if ( ! empty( $value['id'] ) ) {
					$id = $value['id'];
				}

				$jquery_click_hook = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($id) );
				$jquery_click_hook = esc_attr( "of-option-".$jquery_click_hook );

				$menu .= sprintf( '<a id="%s-tab" class="nav-tab" title="%s" href="%s">%s</a>', $jquery_click_hook, esc_attr($value['name']), esc_attr('#'.$jquery_click_hook), esc_html($value['name']) );
				$output .= sprintf( '<div class="group" id="%s">', $jquery_click_hook );

				break;

		} // end switch ( $value['type'] )

		// Here's your chance to add in your own custom
		// option type while we're looping through each
		// option. If you come up with a unique $type,
		// you can intercept things here and append
		// to the $output.
		$output = apply_filters( 'themeblvd_option_type', $output, $value, $option_name, $val );

		// Finish off standard options and add description
		if ( $value['type'] != 'heading' && $value['type'] != 'info' ) {

			$output .= '</div>';

			if ( $value['type'] != 'editor' ) { // Editor displays description above it
				if ( ! empty( $value['desc'] ) ) {
					if ( is_array( $value['desc'] ) ) {
						foreach ( $value['desc'] as $desc_id => $desc ) {
							$output .= '<div class="explain hide '.$desc_id.'">'.wp_kses( $desc, themeblvd_allowed_tags() ).'</div>'."\n";
						}
					} else {
						$output .= '<div class="explain">'.wp_kses( $value['desc'], themeblvd_allowed_tags() ).'</div>'."\n";
					}
				}
			}

			$output .= '<div class="clear"></div></div></div>'."\n";
		}
	}

	// Optional closing div
    if ( $close ) {
    	$output .= '</div>';
    }

    // Construct final return
    $form = array(
    	$output,	// The actual options form
    	$menu		// Navigation, will not always be needed
    );

    return $form;
}