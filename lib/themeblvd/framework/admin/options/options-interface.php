<?php
/**
 * Functions to create sets of end-user options.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.2.0
 */

/**
 * Generates the options fields that are used in
 * forms for internal options framework.
 *
 * @since @@name-framework 2.2.0
 *
 * @param  string  $option_name Prefix for all field name attributes.
 * @param  array   $options     All options to show in form.
 * @param  array   $settings    Any current settings for all form fields.
 * @param  bool    $close       Whether to add closing </div>. @deprecated Now figured out automatically.
 * @return array {
 *     Final options form.
 *
 *     @type string HTML output for optins page.
 *     @type string HTML output for tabbed navigation, if exists.
 * }
 */
function themeblvd_option_fields( $option_name, $options, $settings, $close = true ) {

	$counter = 0;

	$menu = '';

	$output = '';

	$advanced = Theme_Blvd_Advanced_Options::get_instance();

	$option_name_orig = esc_attr( $option_name );

	foreach ( $options as $option_key => $option ) {

		$counter++;

		$current = '';

		$select_value = '';

		$checked = '';

		$class = '';

		$option_name = $option_name_orig;

		/*
		 * Footer sync option is just a placeholder; so we
		 * can skip it in our display.
		 */
		if ( 'footer_sync' === $option_key ) {
			continue;
		}

		/*
		 * Start or end an option subgroup.
		 *
		 * This allows for a wrapping div around groups of
		 * elements. The primary reason for this is to help
		 * link certain options together in order to apply
		 * custom javascript for certain common groups.
		 */
		if ( 'subgroup_start' === $option['type'] ) {

			if ( isset( $option['class'] ) ) {
				$class = ' ' . $option['class'];
			}

			$output .= '<div class="subgroup' . $class . '">' . "\n";

			continue;

		}

		if ( 'subgroup_end' === $option['type'] ) {

			$output .= '</div><!-- .subgroup (end) -->' . "\n";

			continue;

		}

		/*
		 * Configure option name grouping.
		 *
		 * This allows certain options to be grouped together
		 * in the final saved options array by adding a common
		 * prefix to their name form attributes.
		 */
		if ( isset( $option['group'] ) ) {

			$option_name .= '[' . $option['group'] . ']';

		}

		/*
		 * Start and end an option section.
		 *
		 * This allows for a wrapping div around certain sections.
		 * This is meant to create visual dividing styles between
		 * sections, opposed to sub groups, which are used to
		 * section off the code for hidden purposes.
		 */
		if ( 'section_start' === $option['type'] ) {

			$name = '';

			if ( ! empty( $option['name'] ) ) {

				$name = esc_html( $option['name'] );

			}

			if ( isset( $option['class'] ) ) {

				$class = ' ' . $option['class'];

			}

			if ( ! $name ) {

				$class .= ' no-name';

			}

			$id = str_replace(
				array( 'start_section_', 'section_start_' ),
				'',
				$option_key
			);

			$output .= sprintf(
				'<div id="%s" class="postbox inner-section%s closed">',
				$id,
				esc_attr( $class )
			);

			$output .= '<a href="#" class="section-toggle"><i class="tb-icon-up-dir"></i></a>';

			if ( $name ) {

				$output .= '<h3 class="hndle">' . $name . '</h3>';

			}

			if ( 'start_section_footer' === $option_key && isset( $options['footer_sync'] ) ) {

				$current = 0;

				if ( ! empty( $settings['footer_sync'] ) ) {

					$current = 1;

				}

				$output .= '<div class="footer-sync-wrap">' . "\n";

				$output .= sprintf(
					'<input id="tb-footer-sync" class="checkbox of-input" type="checkbox" name="%s" %s />',
					esc_attr( $option_name . '[footer_sync]' ),
					checked( $current, 1, false )
				);

				$output .= sprintf(
					'<label for="footer_sync">%s</label>',
					esc_html__( 'Template Sync', '@@text-domain' )
				);

				$output .= '</div><!-- .footer-sync-wrap (end) -->' . "\n";

			}

			$output .= '<div class="inner-section-content hide">' . "\n";

			if ( ! empty( $option['desc'] ) ) {

				$output .= sprintf(
					'<div class="section-description">%s</div>',
					$option['desc']
				);

				$output .= "\n";

			}

			if ( ! empty( $option['preset'] ) ) {

				$output .= sprintf(
					'<div class="section section-presets">%s</div>',
					themeblvd_display_presets( $option['preset'], $option_name )
				);

				$output .= "\n";

			}

			continue;

		}

		if ( 'section_end' === $option['type'] ) {

			$output .= '<div class="section save clearfix">';

			$output .= "\n";

			$output .= sprintf(
				'<input type="submit" class="button-primary" name="update" value="%s" />',
				esc_attr__( 'Save Options', '@@text-domain' )
			);

			$output .= '</div>';

			$output .= '</div><!-- .inner-section-content (end) -->' . "\n";

			$output .= '</div><!-- .inner-section (end) -->' . "\n";

			continue;

		}

		// Set current value.
		if ( isset( $option['std'] ) ) {
			$current = $option['std'];
		}

		if ( 'heading' !== $option['type'] && 'info' !== $option['type'] ) {

			if ( isset( $option['group'] ) ) {

				// Set grouped value.
				if ( isset( $settings[ ($option['group']) ][ ($option['id']) ] ) ) {

					$current = $settings[ ($option['group']) ][ ($option['id']) ];

				}
			} else {

				// Set non-grouped value.
				if ( isset( $settings[ ($option['id']) ] ) ) {

					$current = $settings[ ($option['id']) ];

				}
			}
		}

		// Add hidden options to display.
		if ( 'hidden' === $option['type'] ) {

			$class = 'section section-hidden hide';

			if ( ! empty( $option['class'] ) ) {

				$class .= ' ' . $option['class'];

			}

			$output .= sprintf( '<div class="%s">', esc_attr( $class ) );

			$output .= "\n";

			$output .= sprintf(
				'<input id="%s" class="of-input" name="%s" type="text" value="%s" />',
				esc_attr( $option['id'] ),
				esc_attr( $option_name . '[' . $option['id'] . ']' ),
				esc_attr( $current )
			);

			$output .= '</div><!-- .section.section-hidden (end) -->' . "\n";

			continue;

		}

		/*
		 * Wrap all options.
		 *
		 * At this point, any options that haven't been
		 * displayed will follow the basic template of
		 * being wrapped by the same markup with heading
		 * and description.
		 */
		if ( 'heading' !== $option['type'] && 'info' !== $option['type'] ) {

			$option['id'] = preg_replace( '/\W/', '', strtolower( $option['id'] ) );

			$id = 'section-' . $option['id'];

			$class = 'section ';

			if ( isset( $option['type'] ) ) {

				$class .= ' section-' . $option['type'];

				if ( $advanced->is_sortable( $option['type'] ) ) {

					$class .= ' section-sortable';

				}

				/**
				 * Filters which option types will be displayed as
				 * full-width in an options set.
				 *
				 * Specifically, this refers to the layout of an option
				 * where you have the option controls to the left and
				 * the description to the right. With these, option
				 * controls will be stretched full-width of the options
				 * panel, with the description falling below.
				 *
				 * @since @@name-framework 2.7.0
				 *
				 * @param array All options to display as full-width.
				 */
				$full_width = apply_filters( 'themeblvd_full_width_options', array(
					'bars',
					'buttons',
					'code',
					'editor',
					'datasets',
					'locations',
					'price_cols',
					'sectors',
					'tabs',
					'testimonials',
					'text_blocks',
					'toggles',
				));

				if ( in_array( $option['type'], $full_width ) ) {

					$class .= ' full-width';

				}

				if ( 'logo' === $option['type'] || 'background' === $option['type'] ) {

					$class .= ' section-upload';

				}
			}

			if ( ! empty( $option['class'] ) ) {

				$class .= ' ' . $option['class'];

			}

			$output .= sprintf(
				'<div id="%s" class="%s">',
				esc_attr( $id ),
				esc_attr( $class )
			);

			$output .= "\n";

			if ( ! empty( $option['name'] ) ) { // Heading for option isn't required.

				$output .= sprintf(
					'<h4 class="heading">%s</h4>',
					esc_html( $option['name'] )
				);

				$output .= "\n";

			}

			$output .= '<div class="option">' . "\n";

			$output .= '<div class="controls">' . "\n";

		}

		// Display individual options, by type.
		switch ( $option['type'] ) {

			/*
			 * Display option type, `heading`.
			 *
			 * This is not actually an option, but lets you break
			 * your option sets into top-level tabs.
			 *
			 * @param array $option {
			 *     @type string $id     Unique ID for option.
			 *     @type string $name   Title for tab.
			 *     @type string $type   Type of option, should be `heading`.
			 *     @type array  $preset Optional. Presets at top of tab.
			 * }
			 */
			case 'heading':
				// If this isn't the first tab, close the previous.
				if ( $menu ) {

					$output .= '</div><!-- .group (end) -->' . "\n";

				}

				$id = $option['name'];

				if ( ! empty( $option['id'] ) ) {

					$id = $option['id'];

				}

				$click_hook = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower( $id ) );

				$click_hook = 'tb-options-page-' . $click_hook;

				$menu .= sprintf(
					'<a id="%s-tab" class="nav-tab" title="%s" href="%s">%s</a>',
					esc_attr( $click_hook ),
					esc_attr( $option['name'] ),
					esc_attr( '#' . $click_hook ),
					esc_html( $option['name'] )
				);

				$output .= sprintf(
					'<div class="group hide" id="%s">',
					$click_hook
				);

				if ( ! empty( $option['preset'] ) ) {
					$output .= themeblvd_display_presets( $option['preset'], $option_name );
				}

				break;

			/*
			 * Display option type, `info`.
			 *
			 * This is not actually an option, but gives you a
			 * chance to add a block of information at any point
			 * in a set of options, which will take up its own row.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Optional. Title for information block.
			 *     @type string $desc Description for information block.
			 *     @type string $type Type of option, should be `info`.
			 * }
			 */
			case 'info':
				$class = 'section section-info';

				if ( isset( $option['class'] ) ) {

					$class .= ' ' . $option['class'];

				}

				$output .= '<div class="' . esc_attr( $class ) . '">' . "\n";

				if ( isset( $option['name'] ) ) {

					$output .= sprintf(
						'<h4 class="heading">%s</h4>',
						esc_html( $option['name'] )
					);

					$output .= "\n";

				}

				if ( isset( $option['desc'] ) ) {
					$output .= $option['desc'] . "\n";
				}

				$output .= '<div class="clear"></div>';

				$output .= '</div><!-- .section (end) -->' . "\n";

				break;

			/*
			 * Display option type, `text`.
			 *
			 * The `text` option type is generally just a
			 * standard <input type="text" /> option.
			 *
			 * One exception is that you can configure a `text`
			 * option to link to a browser by passing in
			 * $option['icon'] as `vector` or `post_id`, which
			 * allows the user to find a value to send back to
			 * the input field.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type string $std  Default value.
			 *     @type string $type Type of option, should be `text`.
			 *     @type string $icon Type of browser, if including browser link, `vector` or `post_id`.
		 	 * }
			 */
			case 'text':
				$pholder = '';

				if ( ! empty( $option['pholder'] ) ) {

					$pholder = ' placeholder="' . esc_attr( $option['pholder'] ) . '"';

				}

				$output .= '<div class="input-wrap">';

				/*
				 * If $option['icon'] is passed through, transform to
				 * specialized text field, linking to browser, which
				 * will let the user find a value to send back to
				 * text field.
				 */
				if ( isset( $option['icon'] ) ) {

					if ( 'vector' === $option['icon'] ) {

						$output .= sprintf(
							'<a href="#" class="tb-input-icon-link tb-tooltip-link" data-target="themeblvd-icon-browser-vector" data-icon-type="vector" data-tooltip-text="%s"><i class="tb-icon-picture"></i></a>',
							esc_attr__( 'Browse Icons', '@@text-domain' )
						);

					} elseif ( 'post_id' === $option['icon'] ) {

						$output .= sprintf(
							'<a href="#" class="tb-input-post-id-link tb-tooltip-link" data-target="themeblvd-post-browser" data-icon-type="post_id" data-tooltip-text="%s"><i class="tb-icon-barcode"></i></a>',
							esc_attr__( 'Find Post or Page ID', '@@text-domain' )
						);

					}
				}

				$output .= sprintf(
					'<input id="%s" class="of-input" name="%s" type="text" value="%s"%s />',
					esc_attr( $option['id'] ),
					esc_attr( $option_name . '[' . $option['id'] . ']' ),
					esc_attr( $current ),
					$pholder
				);

				$output .= '</div><!-- .input-wrap (end) -->';

				break;

			/*
			 * Display option type, `textarea`.
			 *
			 * The `textarea` option tpe is generally just
			 * a standard <textarea> option.
			 *
			 * This option type can be extended by adding
			 * links to edit the contents of the textarea in
			 * a WP visual editor or code editor modal window.
			 * This is done by passing in $option['editor']
			 * or $option['code'].
			 *
			 * @param array $option {
			 *     @type string $id     Unique ID for option.
			 *     @type string $name   Title for option.
			 *     @type string $desc   Description for option.
			 *     @type string $std    Default value.
			 *     @type string $type   Type of option, should be `textarea`.
			 *     @type bool   $editor Whether to include WP visual editor link.
			 *     @type string $code   Type of coding language, if including code editor link, `html`, `javascript`, or `css`.
		 	 * }
			 */
			case 'textarea':
				$pholder = '';

				if ( ! empty( $option['pholder'] ) ) {

					$pholder = ' placeholder="' . esc_attr( $option['pholder'] ) . '"';

				}

				$cols = '8';

				if ( isset( $option['options'] ) && isset( $option['options']['cols'] ) ) {

					$cols = $option['options']['cols'];

				}

				$output .= '<div class="textarea-wrap">';

				$output .= sprintf(
					'<textarea id="%s" class="of-input" name="%s" cols="%s" rows="8"%s>%s</textarea>',
					esc_attr( $option['id'] ),
					esc_attr( $option_name . '[' . $option['id'] . ']' ),
					esc_attr( $cols ),
					$pholder,
					esc_textarea( $current )
				);

				$output .= '</div><!-- .textarea-wrap (end) -->';

				break;

			/*
			 * Display option type, `select`.
			 *
			 * The `select` option type is generally just a
			 * standard <select> menu meant to let the user
			 * select one selection from a group.
			 *
			 * For a standard select menu, an array of items
			 * is passed in through $option['options'].
			 *
			 * Alternately, a dynamic set of options can be
			 * generated by passing in $option['select'] as
			 * one of the following values:
			 *
			 * `pages`        List of pages.
			 * `categories`   List of post categories.
			 * `sidebars`     List of custom widget areas.
			 * `sidebars_all` List of all widget areas, except collapsible.
			 * `crop`         List of registered crop sizes.
			 * `textures`     List of framework textures.
			 * `templates`    List of templates built with layout builder.
			 * `authors`      List of site's users w/Contributer role or higher.
			 *
			 * @param array $option {
			 *     @type string $id      Unique ID for option.
			 *     @type string $name    Title for option.
			 *     @type string $desc    Description for option.
			 *     @type string $std     Default value.
			 *     @type string $type    Type of option, should be `select`.
			 *     @type array  $options Associative array of selections.
			 *     @type string $select  Optional. Type of dynamic select to generate (see above).
		 	 * }
			 */
			case 'select':
				$error = '';

				$textures = false;

				// Generate dynamic select types.
				if ( ! isset( $option['options'] ) && isset( $option['select'] ) ) {

					$option['options'] = array();

					switch ( $option['select'] ) {

						case 'pages':
							$option['options'] = themeblvd_get_select( 'pages' );

							if ( count( $option['options'] ) < 1 ) {

								$error = __( 'No pages were found.', '@@text-domain' );

							}

							break;

						case 'categories':
							$option['options'] = themeblvd_get_select( 'categories' );

							if ( count( $option['options'] ) < 1 ) {

								$error = __( 'No categories sidebars were found.', '@@text-domain' );

							}

							break;

						case 'sidebars':
							if ( ! defined( 'TB_SIDEBARS_PLUGIN_VERSION' ) ) {

								$error = __( 'You must install the Theme Blvd Widget Areas plugin in order to insert a floating widget area.', '@@text-domain' );

							}

							$option['options'] = themeblvd_get_select( 'sidebars' );

							if ( count( $option['options'] ) < 1 ) {

								$error = __( 'No floating widget areas were found.', '@@text-domain' );

							}

							break;

						case 'sidebars_all':
							$option['options'] = themeblvd_get_select( 'sidebars_all' );

							if ( count( $option['options'] ) < 1 ) {

								$error = __( 'No registered sidebars were found.', '@@text-domain' );

							}

							break;

						case 'crop':
							$option['options'] = themeblvd_get_select( 'crop' );

							if ( count( $option['options'] ) < 1 ) {

								$error = __( 'No registered crop sizes were found.', '@@text-domain' );

							}

							break;

						case 'textures':
							$textures = true;

							$option['options'] = themeblvd_get_select( 'textures' );

							if ( count( $option['options'] ) < 1 ) {

								$error = __( 'No textures were found.', '@@text-domain' );

							}

							break;

						case 'templates':
							$option['options'] = themeblvd_get_select( 'templates' );

							if ( count( $option['options'] ) < 1 ) {

								$error = __( 'You haven\'t created any custom templates yet.', '@@text-domain' );

							}

							break;

						case 'authors':
							$option['options'] = themeblvd_get_select( 'authors' );

							if ( count( $option['options'] ) < 1 ) {

								$error = __( 'Couldn\'t find any authors.', '@@text-domain' );

							}

							break;

					}
				}

				/*
				 * If any dynamic selects caused errors (or no items
				 * were found), don't display a select menu.
				 */
				if ( $error ) {

					$output .= sprintf(
						'<p class="warning">%s</p>',
						esc_html( $error )
					);

					break;

				}

				// Start output for <select>.
				$output .= sprintf(
					'<select class="of-input" name="%s" id="%s">',
					esc_attr( $option_name . '[' . $option['id'] . ']' ),
					esc_attr( $option['id'] )
				);

				$first = reset( $option['options'] );

				if ( is_array( $first ) ) {

					// Option groups.
					foreach ( $option['options'] as $optgroup_id => $optgroup ) {

						$output .= sprintf( '<optgroup label="%s">', $optgroup['label'] );

						foreach ( $optgroup['options'] as $key => $selection ) {

							$output .= sprintf(
								'<option%s value="%s">%s</option>',
								selected( $key, $current, false ),
								esc_attr( $key ),
								esc_html( $selection )
							);

						}

						$output .= '</optgroup>';

					}
				} else {

					// Standard <select>.
					foreach ( $option['options'] as $key => $selection ) {

						$output .= sprintf(
							'<option%s value="%s">%s</option>',
							selected( $key, $current, false ),
							esc_attr( $key ),
							esc_html( $selection )
						);

					}
				}

				$output .= '</select>';

				if ( $textures ) {

					$output .= sprintf(
						'<a href="#" class="tb-texture-browser-link" data-target="themeblvd-texture-browser">%s</a>',
						esc_attr__( 'Browse Textures', '@@text-domain' )
					);

				}

				// If this is a builder sample select, show preview images.
				if ( isset( $option['class'] ) && false !== strpos( $option['class'], 'builder-samples' ) ) {

					if ( function_exists( 'themeblvd_builder_sample_previews' ) ) {

						$output .= themeblvd_builder_sample_previews();

					}
				}

				break;

			/*
			 * Display option type, `radio`.
			 *
			 * This option is a basic set of radio inputs, meant
			 * to let the user select one selection from a group.
			 *
			 * @param array $option {
			 *     @type string $id      Unique ID for option.
			 *     @type string $name    Title for option.
			 *     @type string $desc    Description for option.
			 *     @type string $std     Default value.
			 *     @type string $type    Type of option, should be `radio`.
			 *     @type array  $options Associative array of selections.
			 * }
			 */
			case 'radio':
				$name = $option_name . '[' . $option['id'] . ']';

				foreach ( $option['options'] as $key => $input ) {

					$id = sprintf( '%s-%s-%s', $option_name, $option['id'], $key );

					$output .= '<div class="radio-input clearfix">';

					$output .= sprintf(
						'<input class="tb-radio of-input of-radio" type="radio" name="%s" id="%s" value="%s" %s />',
						esc_attr( $name ),
						esc_attr( $id ),
						esc_attr( $key ),
						checked( $current, $key, false )
					);

					$output .= sprintf(
						'<label for="%s">%s</label>',
						esc_attr( $id ),
						$input
					);

					$output .= '</div><!-- .radio-input (end) -->';

				}

				break;

			/*
			 * Display option type, `images`.
			 *
			 * This option is a basic set of radio inputs, meant
			 * to let the user select one selection from a group.
			 * But each input selection is represented by an image.
			 *
			 * @param array $option {
			 *     @type string $id      Unique ID for option.
			 *     @type string $name    Title for option.
			 *     @type string $desc    Description for option.
			 *     @type string $std     Default value.
			 *     @type string $type    Type of option, should be `images`.
			 *     @type array  $options Associative array of selections.
			 * }
			 */
			case 'images':
				$name = $option_name . '[' . $option['id'] . ']';

				$width = '';

				if ( isset( $option['img_width'] ) ) {

					$width = $option['img_width'];

				}

				foreach ( $option['options'] as $key => $img ) {

					$checked = checked( $current, $key, false );

					$selected = '';

					if ( $checked ) {

						$selected = ' tb-radio-img-selected of-radio-img-selected';

					}

					$output .= sprintf(
						'<input type="radio" id="%s" class="tb-radio-img-radio of-radio-img-radio" value="%s" name="%s" %s />',
						esc_attr( $option['id'] . '_' . $key ),
						esc_attr( $key ),
						esc_attr( $name ),
						$checked
					);

					$output .= sprintf(
						'<div class="tb-radio-img-label of-radio-img-label">%s</div>',
						esc_html( $key )
					);

					$output .= sprintf(
						'<img src="%s" alt="%s" class="tb-radio-img-img of-radio-img-img%s" width="%s" onclick="document.getElementById(\'%s\').checked=true;" />',
						esc_url( $img ),
						esc_url( $img ),
						$selected,
						esc_attr( $width ),
						esc_attr( $option['id'] . '_' . $key )
					);

				}

				break;

			/*
			 * Display option type, `checkbox`.
			 *
			 * This option type is just a single checkbox.
			 *
			 * @param array $option {
			 *     @type string $id       Unique ID for option.
			 *     @type string $name     Title for option.
			 *     @type string $desc     Description for option.
			 *     @type string $std      Default value, `0` or `1`.
			 *     @type string $type     Type of option, should be `checkbox`.
			 *     @type string $inactive Optional. A value to force the on the checkbox, if disabling.
			 * }
			 */
			case 'checkbox':
				if ( ! empty( $option['inactive'] ) ) {

					if ( '1' === $option['inactive'] || 'true' === $option['inactive'] ) {

						$current = '1';

					} elseif ( '0' === $option['inactive'] || 'false' === $option['inactive'] ) {

						$current = '0';

					}
				}

				$name = $option_name . '[' . $option['id'] . ']';

				$checkbox = sprintf(
					'<input id="%s" class="checkbox of-input" type="checkbox" name="%s" %s />',
					esc_attr( $option['id'] ),
					esc_attr( $name ),
					checked( $current, '1', false )
				);

				if ( ! empty( $option['inactive'] ) ) {

					$checkbox = str_replace( '/>', 'disabled="disabled" />', $checkbox );

				}

				$output .= $checkbox;

				break;

			/*
			 * Display option type, `multicheck`.
			 *
			 * This option is a basic set of checkboxes, meant
			 * to let the user select multiple selection from
			 * a group.
			 *
			 * @param array $option {
			 *     @type string $id      Unique ID for option.
			 *     @type string $name    Title for option.
			 *     @type string $desc    Description for option.
			 *     @type string $std     Default value.
			 *     @type string $type    Type of option, should be `multicheck`.
			 *     @type array  $options Associative array of selections.
			 * }
			 */
			case 'multicheck':
				foreach ( $option['options'] as $key => $checkbox ) {

					$checked = '';

					if ( isset( $current[ $key ] ) ) {
						$checked = checked( $current[ $key ], 1, false );
					}

					$label = $checkbox;

					$checkbox = preg_replace( '/\W/', '', strtolower( $key ) );

					$id = $option_name . '-' . $option['id'] . '-' . $checkbox;

					$name = $option_name . '[' . $option['id'] . '][' . $key . ']';

					$class = 'checkbox of-input';

					if ( 'all' === $key ) {

						$class .= ' all';

					}

					$output .= sprintf(
						'<input id="%s" class="%s" type="checkbox" name="%s" %s /><label for="%s">%s</label>',
						esc_attr( $id ),
						$class,
						esc_attr( $name ),
						$checked,
						esc_attr( $id ),
						$label
					);

				}

				break;

			/*
			 * Display option type, `color`.
			 *
			 * This option uses WordPress's built-in color
			 * picker to let the user select a color hex code.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type string $std  Default value.
			 *     @type string $type Type of option, should be `color`.
			 * }
			 */
			case 'color':
				$output .= sprintf(
					'<input id="%s" name="%s" type="text" value="%s" class="tb-color-picker" data-default-color="%s" />',
					esc_attr( $option['id'] ),
					esc_attr( $option_name . '[' . $option['id'] . ']' ),
					esc_attr( $current ),
					esc_attr( $option['std'] )
				);

				break;

			/*
			 * Display option type, `upload`.
			 *
			 * This option lets the user upload media, using
			 * WordPress's media modal.
			 *
			 * @param array $option {
			 *     @type string       $id        Unique ID for option.
			 *     @type string       $name      Title for option.
			 *     @type string       $desc      Description for option.
			 *     @type string|array $std       Default value.
			 *     @type string|array $type      Type of option, should be `upload`.
			 *     @type string       $send_back On standard upload type, what to send to input field, `url` or `id`.
			 *     @type bool         $advanced  Whether this is an `advanced` upload type.
			 *     @type bool         $video     Whether this is a `video` upload type.
			 *     @type bool         $media     Whether this is a `media` upload type, to select from any form of media to send back to a <textarea>.
			 * }
			 */
			case 'upload':
				$args = array(
					'option_name' => $option_name,
					'id'          => $option['id'],
				);

				if ( ! empty( $option['advanced'] ) ) {

					/*
					 * Advanced type will allow for selecting
					 * image crop size for URL.
					 */
					$args['type'] = 'advanced';

					if ( isset( $current['src'] ) ) {

						$args['value_src'] = $current['src'];

					}

					if ( isset( $current['id'] ) ) {

						$args['value_id'] = $current['id'];

					}

					if ( isset( $current['title'] ) ) {

						$args['value_title'] = $current['title'];

					}

					if ( isset( $current['crop'] ) ) {

						$args['value_crop'] = $current['crop'];

					}
				} else {

					$args['value'] = $current;

					$args['type'] = 'standard';

					if ( isset( $option['send_back'] ) ) {

						$args['send_back'] = $option['send_back'];

					} else {

						$args['send_back'] = 'url'; // Default.

					}

					if ( ! empty( $option['video'] ) ) {

						$args['type'] = 'video';

					}

					if ( ! empty( $option['media'] ) ) { // @TODO Framework javascript currently doesn't support this

						$args['type'] = 'media';

					}
				}

				$output .= themeblvd_media_uploader( $args );

				break;

			/*
			 * Display option type, `typography`.
			 *
			 * This option lets the user configure a font, with
			 * support included for the Google Font Directory and
			 * Typekit.
			 *
			 * You can control how many attributes about the font
			 * are configurable by passing in the $atts param.
			 * `array( 'size', 'style', 'weight', 'face', 'color' )`
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `typography`.
			 *     @type array  $atts List of attributes to include from `size`, `style`, `weight`, `face`, and `color`.
			 * }
			 */
			case 'typography':
				$current = wp_parse_args( $current, array(
					'size'        => '0px',
					'style'       => '',
					'weight'      => '400', // @since @@name-framework 2.5.0
					'face'        => '',
					'color'       => '',    // @since @@name-framework 2.5.0
					'google'      => '',
					'typekit'     => '',    // @since @@name-framework 2.6.0
					'typekit_kit' => '',    // @since @@name-framework 2.6.0
					'custom'      => '',    // @since @@name-framework 2.7.0
				));

				// Add font-size selection to output.
				if ( in_array( 'size', $option['atts'] ) ) {

					$output .= '<div class="jquery-ui-slider-wrap">';

					if ( ! empty( $option['sizes'] ) ) {

						$sizes = $option['sizes'];

					} else {

						$sizes = themeblvd_recognized_font_sizes();

					}

					$slide_options = array(
						'min'   => intval( $sizes[0] ),
						'max'   => intval( end( $sizes ) ),
						'step'  => intval( $sizes[1] ) - intval( $sizes[0] ),
						'units' => 'px',
					);

					$output .= '<div class="jquery-ui-slider"';

					foreach ( $slide_options as $param_id => $param ) {

						$output .= sprintf(
							' data-%s="%s"',
							esc_attr( $param_id ),
							esc_attr( $param )
						);

					}

					$output .= '></div>';

					$output .= sprintf(
						'<input id="%s" class="of-input slider-input" name="%s" type="hidden" value="%s" />',
						esc_attr( $option['id'] . '_size' ),
						esc_attr( $option_name . '[' . $option['id'] . '][size]' ),
						esc_attr( $current['size'] )
					);

					$output .= '</div><!-- .jquery-ui-slider-wrap (end) -->';

				}

				// Add font-style selection to output.
				if ( in_array( 'style', $option['atts'] ) ) {

					$output .= sprintf(
						'<select class="of-typography of-typography-style" name="%s" id="%s">',
						esc_attr( $option_name . '[' . $option['id'] . '][style]' ),
						esc_attr( $option['id'] . '_style' )
					);

					$styles = themeblvd_recognized_font_styles();

					foreach ( $styles as $key => $style ) {

						$output .= sprintf(
							'<option value="%s" %s>%s</option>',
							esc_attr( $key ),
							selected( $current['style'], $key, false ),
							esc_html( $style )
						);

					}

					$output .= '</select>';

				}

				// Add font-weight selection to output.
				if ( in_array( 'weight', $option['atts'] ) ) {

					$output .= sprintf(
						'<select class="of-typography of-typography-weight" name="%s" id="%s">',
						esc_attr( $option_name . '[' . $option['id'] . '][weight]' ),
						esc_attr( $option['id'] . '_weight' )
					);

					$weights = themeblvd_recognized_font_weights();

					foreach ( $weights as $key => $weight ) {

						$output .= sprintf(
							'<option value="%s" %s>%s</option>',
							esc_attr( $key ),
							selected( $current['weight'], $key, false ),
							esc_attr( $weight )
						);

					}

					$output .= '</select>';

				}

				// Add font-family selection to output.
				if ( in_array( 'face', $option['atts'] ) ) {

					$output .= sprintf(
						'<select class="of-typography of-typography-face" name="%s" id="%s">',
						esc_attr( $option_name . '[' . $option['id'] . '][face]' ),
						esc_attr( $option['id'] . '_face' )
					);

					$faces = themeblvd_recognized_font_faces();

					foreach ( $faces as $key => $face ) {

						$output .= sprintf(
							'<option value="%s" %s>%s</option>',
							esc_attr( $key ),
							selected( $current['face'], $key, false ),
							esc_attr( $face )
						);

					}

					$output .= '</select>';

				}

				// Add color selection to output.
				if ( in_array( 'color', $option['atts'] ) ) {

					$default = '#666666';

					if ( ! empty( $option['std']['color'] ) ) {
						$default = $option['std']['color'];
					}

					$output .= sprintf(
						'<input id="%s-color" name="%s" type="text" value="%s" class="tb-color-picker" data-default-color="%s" />',
						esc_attr( $option['id'] ),
						esc_attr( $option_name . '[' . $option['id'] . '][color]' ),
						esc_attr( $current['color'] ),
						esc_attr( $default )
					);

				}

				$output .= '<div class="clear"></div>';

				if ( in_array( 'face', $option['atts'] ) ) {

					// Add Google Font field to output.

					$output .= '<div class="google-font hide">';

					$output .= sprintf(
						// translators: 1: link to Google Font Directory
						'<h5>' . esc_html__( 'Enter the name of a font from the %s.', '@@text-domain' ) . '</h5>',
						'<a href="http://www.google.com/webfonts" target="_blank">' . esc_attr__( 'Google Font Directory', '@@text-domain' ) . '</a>'
					);

					$output .= sprintf(
						'<input type="text" name="%s" value="%s" />',
						esc_attr( $option_name . '[' . $option['id'] . '][google]' ),
						esc_attr( $current['google'] )
					);

					$output .= sprintf(
						'<p class="note"><strong>%s</strong> Open Sans<br />',
						esc_html__( 'Example:', '@@text-domain' )
					);

					$output .= sprintf(
						'<strong>%s</strong> Open Sans:300</p>',
						esc_html__( 'Example with custom weight:', '@@text-domain' )
					);

					$output .= '</div>';

					// Add Typekit field to output.

					$output .= '<div class="typekit-font hide">';

					$output .= sprintf(
						'<h5>%s</h5>',
						esc_attr__( 'Typekit Font Family', '@@text-domain' )
					);

					$output .= sprintf(
						'<input type="text" name="%s" value="%s" />',
						esc_attr( $option_name . '[' . $option['id'] . '][typekit]' ),
						esc_attr( $current['typekit'] )
					);

					$output .= sprintf(
						'<h5>%s</h5>',
						esc_attr__( 'Paste your kit\'s embed code below.', '@@text-domain' )
					);

					$output .= sprintf(
						'<textarea name="%s">%s</textarea>',
						esc_attr( $option_name . '[' . $option['id'] . '][typekit_kit]' ),
						themeblvd_kses( $current['typekit_kit'] )
					);

					$output .= '</div>';

					// Add custom font field to output.

					$output .= '<div class="custom-font hide">';

					$output .= sprintf(
						'<h5>%s</h5>',
						esc_attr__( 'Enter the name of your custom font.', '@@text-domain' )
					);

					$output .= sprintf(
						'<input type="text" name="%s" value="%s" />',
						esc_attr( $option_name . '[' . $option['id'] . '][custom]' ),
						esc_attr( $current['custom'] )
					);

					$output .= sprintf(
						'<p class="note">%s</p>',
						esc_html__( 'Use a font name formatted like "My Font" that corresponds to a web font you\'ve included through customization or a third-party plugin.', '@@text-domain' )
					);

					$output .= '</div>';

				}

				break;

			/*
			 * Display option type, `background`.
			 *
			 * This option lets the user configure a background
			 * image for a section.
			 *
			 * @param array $option {
			 *     @type string $id       Unique ID for option.
			 *     @type string $name     Title for option.
			 *     @type string $desc     Description for option.
			 *     @type array  $std      Default value.
			 *     @type string $type     Type of option, should be `background`.
			 *     @type bool   $color    Whether to include background-color selection.
			 *     @type bool   $parallax Whether to let the user select parallax effect.
			 * }
			 */
			case 'background':
				$background = array();

				if ( $current ) {

					$background = $current;

				}

				$color = true;

				if ( isset( $option['color'] ) ) {

					$color = $option['color'];

				}

				// Add background-color picker to output.
				if ( $color ) {

					$current_color = '';

					if ( ! empty( $background['color'] ) ) {

						$current_color = $background['color'];

					}

					$output .= sprintf(
						'<input id="%s_color" name="%s" type="text" value="%s" class="tb-color-picker" data-default-color="%s" />',
						esc_attr( $option['id'] ),
						esc_attr( $option_name . '[' . $option['id'] . '][color]' ),
						esc_attr( $current_color ),
						esc_attr( $current_color )
					);

					$output .= '<br />';

				}

				// Add background-image upload to output.
				if ( ! isset( $background['image'] ) ) {

					$background['image'] = '';

				}

				$current_bg_url = '';

				if ( ! empty( $background['image'] ) ) {

					$current_bg_url = $background['image'];

				}

				$current_bg_image = array(
					'url' => $current_bg_url,
					'id'  => '',
				);

				$output .= themeblvd_media_uploader( array(
					'option_name' => $option_name,
					'type'        => 'background',
					'id'          => $option['id'],
					'value'       => $current_bg_url,
					'name'        => 'image',
				));

				$class = 'tb-background-properties of-background-properties';

				if ( empty( $background['image'] ) ) {

					$class .= ' hide';

				}

				$output .= '<div class="' . esc_attr( $class ) . '">';

				// Add background-repeat selection to output.
				$current_repeat = '';

				if ( ! empty( $background['repeat'] ) ) {
					$current_repeat = $background['repeat'];
				}

				$output .= sprintf(
					'<select class="tb-background tb-background-repeat of-background of-background-repeat" name="%s" id="%s">',
					esc_attr( $option_name . '[' . $option['id'] . '][repeat]' ),
					esc_attr( $option['id'] . '_repeat' )
				);

				$repeats = themeblvd_recognized_background_repeat();

				foreach ( $repeats as $key => $repeat ) {

					$output .= sprintf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $key ),
						selected( $current_repeat, $key, false ),
						esc_html( $repeat )
					);

				}

				$output .= '</select>';

				// Add background-attachment selection to output.
				$current_attachment = '';

				if ( ! empty( $background['attachment'] ) ) {

					$current_attachment = $background['attachment'];

				}

				$output .= sprintf(
					'<select class="tb-background tb-background-attachment of-background of-background-attachment" name="%s" id="%s">',
					esc_attr( $option_name . '[' . $option['id'] . '][attachment]' ),
					esc_attr( $option['id'] . '_attachment' )
				);

				$attachments = themeblvd_recognized_background_attachment();

				$parallax = false;

				if ( isset( $option['parallax'] ) ) {

					$parallax = $option['parallax'];

				}

				if ( ! $parallax ) {

					unset( $attachments['parallax'] );

				}

				foreach ( $attachments as $key => $attachment ) {

					$output .= sprintf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $key ),
						selected( $current_attachment, $key, false ),
						esc_attr( $attachment )
					);

				}

				$output .= '</select>';

				// Add background-position selection to output.
				$current_position = '';

				if ( ! empty( $background['position'] ) ) {

					$current_position = $background['position'];

				}

				$output .= '<select class="tb-background tb-background-position of-background of-background-position" name="' . esc_attr( $option_name . '[' . $option['id'] . '][position]' ) . '" id="' . esc_attr( $option['id'] . '_position' ) . '">';

				$positions = themeblvd_recognized_background_position();

				foreach ( $positions as $key => $position ) {

					$output .= sprintf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $key ),
						selected( $current_position, $key, false ),
						esc_attr( $position )
					);

				}

				$output .= '</select>';

				// Add background-size selection to output.
				$current_size = '';

				if ( ! empty( $background['size'] ) ) {

					$current_size = $background['size'];

				}

				$output .= sprintf(
					'<select class="tb-background tb-background-size of-background of-background-size" name="%s" id="%s">',
					esc_attr( $option_name . '[' . $option['id'] . '][size]' ),
					esc_attr( $option['id'] . '_size' )
				);

				$sizes = themeblvd_recognized_background_size();

				foreach ( $sizes as $key => $size ) {

					$output .= sprintf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $key ),
						selected( $current_size, $key, false ),
						esc_attr( $size )
					);

				}

				$output .= '</select>';

				$output .= '</div>';

				break;

			/*
			 * Display option type, `background_video`.
			 *
			 * This option lets the user configure a background
			 * video for a section.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `background_video`.
			 * }
			 */
			case 'background_video':
				// Add video upload field to output.
				$output .= '<div class="section-upload clearfix">';

				$output .= '<div class="controls">';

				$output .= sprintf(
					'<p><strong>%s</strong></p>',
					esc_html__( 'Video URL', '@@text-domain' )
				);

				$video_url = '';

				if ( ! empty( $current['mp4'] ) ) {

					$video_url = $current['mp4'];

				}

				$output .= themeblvd_media_uploader( array(
					'option_name' => $option_name,
					'type'        => 'video',
					'id'          => $option['id'],
					'value'       => $video_url,
					'name'        => 'mp4',
				));

				$output .= '</div><!-- .controls (end) -->';

				$output .= '</div><!-- .section-upload (end) -->';

				// Add fallback image upload field to output.
				$output .= '<div class="section-upload clearfix">';

				$output .= '<div class="controls">';

				$output .= sprintf(
					'<p><strong>%s</strong></p>',
					esc_html__( 'Video Fallback Image', '@@text-domain' )
				);

				$img_url = '';

				if ( ! empty( $current['fallback'] ) ) {

					$img_url = $current['fallback'];

				}

				$output .= themeblvd_media_uploader( array(
					'option_name' => $option_name,
					'type'        => 'background',
					'id'          => $option['id'],
					'value'       => $img_url,
					'name'        => 'fallback',
				));

				$output .= '</div><!-- .controls (end) -->';

				$output .= '</div><!-- .section-upload (end) -->';

				// Add aspect ratior field to output.
				$output .= sprintf(
					'<p><strong>%s</strong></p>',
					esc_html__( 'Video Aspect Ratio', '@@text-domain' )
				);

				$ratio = '16:9';

				if ( ! empty( $current['ratio'] ) ) {

					$ratio = $current['ratio'];

				}

				$output .= sprintf(
					'<input id="%s_ratio" name="%s" type="text" value="%s" class="of-input" />',
					esc_attr( $option['id'] ),
					esc_attr( $option_name . '[' . $option['id'] . '][ratio]' ),
					esc_attr( $ratio )
				);

				break;

			/*
			 * Display option type, `gradient`.
			 *
			 * This option let's the user setup a gradient
			 * background by selecting a top and bottom color,
			 * to blend together.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `gradient`.
			 * }
			 */
			case 'gradient':
				$start = '';

				$start_def = '#000000';

				$end = '';

				$end_def = '#000000';

				if ( ! empty( $current['start'] ) ) {

					$start = $current['start'];

				}

				if ( ! empty( $current['end'] ) ) {

					$end = $current['end'];

				}

				if ( ! empty( $option['std']['start'] ) ) {

					$start_def = $option['std']['start'];

				}

				if ( ! empty( $option['std']['end'] ) ) {

					$end_def = $option['std']['end'];

				}

				$output .= '<div class="gradient-wrap">';

				// Add color picker for start color to output.
				$output .= '<div class="color-start">';

				$output .= sprintf(
					'<input id="%s_start" name="%s" type="text" value="%s" class="tb-color-picker" data-default-color="%s" />',
					esc_attr( $option['id'] ),
					esc_attr( $option_name . '[' . $option['id'] . '][start]' ),
					esc_attr( $start ),
					esc_attr( $start_def )
				);

				$output .= sprintf(
					'<span class="color-label">%s</span>',
					esc_attr__( 'Top Color', '@@text-domain' )
				);

				$output .= '</div><!-- .color-start (end) -->';

				// Add color picker for end color to output.
				$output .= '<div class="color-end">';

				$output .= sprintf(
					'<input id="%s_end" name="%s" type="text" value="%s" class="tb-color-picker" data-default-color="%s" />',
					esc_attr( $option['id'] ),
					esc_attr( $option_name . '[' . $option['id'] . '][end]' ),
					esc_attr( $end ),
					esc_attr( $end_def )
				);

				$output .= sprintf(
					'<span class="color-label">%s</span>',
					esc_attr__( 'Bottom Color', '@@text-domain' )
				);

				$output .= '</div><!-- .color-end (end) -->';

				$output .= '</div><!-- .gradient-wrap (end) -->';

				break;

			/*
			 * Display option type, `button`.
			 *
			 * This option lets the user configure the colors
			 * and settings for a custom button.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `button`.
			 * }
			 */
			case 'button':
				$output .= themeblvd_button_option(
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `geo`.
			 *
			 * This option type is used to set the coordinates,
			 * latitude and longitude for a Google Map marker.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `geo`.
			 * }
			 */
			case 'geo':
				$lat = '';

				if ( isset( $current['lat'] ) ) {

					$lat = $current['lat'];

				}

				$long = '';

				if ( isset( $current['long'] ) ) {

					$long = $current['long'];

				}

				$output .= '<div class="geo-wrap clearfix">';

				// Add latitude field to output.
				$output .= '<div class="geo-lat">';

				$output .= sprintf(
					'<input id="%s_lat" class="of-input geo-input" name="%s" type="text" value="%s" />',
					esc_attr( $option['id'] ),
					esc_attr( $option_name . '[' . $option['id'] . '][lat]' ),
					esc_attr( $lat )
				);

				$output .= '<span class="geo-label">' . esc_html__( 'Latitude', '@@text-domain' ) . '</span>';

				$output .= '</div><!-- .geo-lat (end) -->';

				// Add longitude field to output.
				$output .= '<div class="geo-long">';

				$output .= sprintf(
					'<input id="%s_long" class="of-input geo-input" name="%s" type="text" value="%s" />',
					esc_attr( $option['id'] ),
					esc_attr( $option_name . '[' . $option['id'] . '][long]' ),
					esc_attr( $long )
				);

				$output .= '<span class="geo-label">' . esc_html__( 'Longitude', '@@text-domain' ) . '</span>';

				$output .= '</div><!-- .geo-long (end) -->';

				$output .= '</div><!-- .geo-wrap (end) -->';

				/*
				 * Add helper fields to let the user look up a set
				 * of coordinates through the Google Maps API, by
				 * searching an address.
				 */
				$output .= '<div class="geo-generate">';

				$output .= '<h5>' . esc_html__( 'Generate Coordinates', '@@text-domain' ) . '</h5>';

				$output .= '<div class="data clearfix">';

				$output .= '<span class="overlay"><span class="tb-loader ajax-loading"><i class="tb-icon-spinner"></i></span></span>';

				$output .= '<input type="text" value="" class="address" />';

				$output .= sprintf(
					'<a href="#" class="button-secondary geo-insert-lat-long" data-oops="%s">%s</a>',
					esc_html__( 'Oops! Sorry, we weren\'t able to get coordinates from that address. Try again.', '@@text-domain' ),
					esc_html__( 'Generate', '@@text-domain' )
				);

				$output .= '</div><!-- .data (end) -->';

				$output .= '<p class="note">';

				$output .= esc_html__( 'Enter an address, as you would do at maps.google.com.', '@@text-domain' ) . '<br>';

				$output .= esc_html__( 'Example Address', '@@text-domain' ) . ': "123 Smith St, Chicago, USA"';

				$output .= '</p>';

				$output .= '</div><!-- .geo-generate (end) -->';

				break;

			/*
			 * Display option type, `columns`.
			 *
			 * This option type lets the user setup the widths
			 * of a set of columns.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type string $std  Default value.
			 *     @type string $type Type of option, should be `columns`.
			 * }
			 */
			case 'columns':
				$output .= themeblvd_columns_option(
					$option['options'],
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `content`.
			 *
			 * This option gives the user a choice to populate some
			 * sort of content area with either a widget area,
			 * content from a post, content from the current post,
			 * or raw input.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `content`.
			 * }
			 */
			case 'content':
				$output .= themeblvd_content_option(
					$option['id'],
					$option_name,
					$current,
					$option['options']
				);

				break;

			/*
			 * Display option type, `conditionals`.
			 *
			 * This option was built for the Theme Blvd Widget
			 * Areas plugin, giving the user a way to select
			 * what pages on the website a custom sidebar
			 * should show.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `conditionals`.
			 * }
			 */
			case 'conditionals':
				$output .= themeblvd_conditionals_option(
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `logo`.
			 *
			 * This option is meant to let the user setup
			 * the website branding logo.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `logo`.
			 * }
			 */
			case 'logo':
				$output .= themeblvd_logo_option(
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `slide`.
			 *
			 * This option is just a simple number slider,
			 * utilizing the slider component of jQuery UI.
			 * It's meant to let the user pick a number
			 * from a given a range.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `slide`.
			 * }
			 */
			case 'slide':
				$output .= '<div class="jquery-ui-slider-wrap">';

				$slide_options = array(
					'min'   => '1',
					'max'   => '100',
					'step'  => '1',
					'units' => '', // For display purpses only.
				);

				if ( isset( $option['options'] ) ) {

					$slide_options = wp_parse_args( $option['options'], $slide_options );

				}

				$output .= '<div class="jquery-ui-slider"';

				foreach ( $slide_options as $param_id => $param ) {

					$output .= sprintf(
						' data-%s="%s"',
						esc_attr( $param_id ),
						esc_attr( $param )
					);

				}

				$output .= '></div>';

				// $current can't be empty or else the UI slider won't work.
				if ( ! $current && '0' !== $current ) {

					$current = $slide_options['min'] . $slide_options['units'];

				}

				$output .= sprintf(
					'<input id="%s" class="of-input slider-input" name="%s" type="hidden" value="%s" />',
					esc_attr( $option['id'] ),
					esc_attr( $option_name . '[' . $option['id'] . ']' ),
					esc_attr( $current )
				);

				$output .= '</div><!-- .jquery-ui-slider-wrap (end) -->';

				break;

			/*
			 * Display option type, `editor`.
			 *
			 * Adds a WordPress vidual editor for the user,
			 * using wp_editor().
			 *
			 * @param array $option {
			 *     @type string $id            Unique ID for option.
			 *     @type string $name          Title for option.
			 *     @type string $desc          Description for option.
			 *     @type string $std           Default value.
			 *     @type string $type          Type of option, should be `editor`.
			 *     @type string $desc_location Location of option description, `before` or `after`.
			 *     @type array  $settings      Any setting overrides to pass to wp_editor().
			 * }
			 */
			case 'editor':
				if ( user_can_richedit() ) {

					add_filter( 'the_editor_content', 'format_for_editor', 10, 2 );

					$default_editor = 'tinymce';

				} else {

					$default_editor = 'html';

				}

				/** This filter is documented in wp-includes/class-wp-editor.php */
				$current = apply_filters( 'the_editor_content', $current, $default_editor );

				// Reset filter addition.
				if ( user_can_richedit() ) {

					remove_filter( 'the_editor_content', 'format_for_editor' );

				}

				/*
				 * Prevent premature closing of textarea in case
				 * format_for_editor() didn't apply or the_editor_content
				 * filter did a wrong thing.
				 */
				$current = preg_replace( '#</textarea#i', '&lt;/textarea', $current );

				$output .= sprintf(
					'<textarea id="%s" class="tb-editor-input" name="%s">%s</textarea>',
					esc_attr( uniqid( 'tb-editor-' . $option['id'] ) ),
					esc_attr( $option_name . '[' . $option['id'] . ']' ),
					$current
				);

				break;

			/*
			 * Display option type, `code`.
			 *
			 * This option adds an inline code editor with syntax
			 * highlighting, which can be setup for HTML, JavaScript
			 * or CSS.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type string $std  Default value.
			 *     @type string $type Type of option, should be `code`.
			 *     @type string $lang Coding language, `html`, `javascript` or `css`.
			 * }
			 */
			case 'code':
				$lang = 'html'; // Default, $option['lang'] not passed through.

				if ( isset( $option['lang'] ) && in_array( $option['lang'], array( 'html', 'javascript', 'css' ) ) ) {

					$lang = $option['lang'];

				}

				$output .= '<div class="textarea-wrap">';

				$output .= sprintf(
					'<textarea id="%s" data-code-lang="%s" name="%s" rows="8">%s</textarea>',
					esc_attr( uniqid( 'code_editor_' . rand() ) ),
					esc_attr( $lang ),
					esc_attr( $option_name . '[' . $option['id'] . ']' ),
					esc_textarea( $current )
				);

				$output .= '</div><!-- .textarea-wrap (end) -->';

				break;

			/*
			 * Display option type, `bars`.
			 *
			 * Sortable option type to configure a group of
			 * progress bars.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `bars`.
			 * }
			 */
			case 'bars':
				$bars = $advanced->get( 'bars' );

				$output .= $bars->get_display(
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `buttons`.
			 *
			 * Sortable option type to configure a group of
			 * buttons.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `buttons`.
			 * }
			 */
			case 'buttons':
				$buttons = $advanced->get( 'buttons' );

				$output .= $buttons->get_display(
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `datasets`.
			 *
			 * Sortable option type to configure the values
			 * for a line or bar graph.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `datasets`.
			 * }
			 */
			case 'datasets':
				$datasets = $advanced->get( 'datasets' );

				$output .= $datasets->get_display(
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `locations`.
			 *
			 * Sortable option type to configure the location
			 * markers of Google Map.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `locations`.
			 * }
			 */
			case 'locations':
				$locations = $advanced->get( 'locations' );

				$output .= $locations->get_display(
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `price_cols`.
			 *
			 * Sortable option type to configure columns
			 * of a pricing table.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `price_cols`.
			 * }
			 */
			case 'price_cols':
				$price_cols = $advanced->get( 'price_cols' );

				$output .= $price_cols->get_display(
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `sectors`.
			 *
			 * Sortable option type to configure sectors
			 * of a pie chart.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `sectors`.
			 * }
			 */
			case 'sectors':
				$sectors = $advanced->get( 'sectors' );

				$output .= $sectors->get_display(
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `share`.
			 *
			 * Sortable option type to configure group of
			 * share buttons for a post.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `share`.
			 * }
			 */
			case 'share':
				$share = $advanced->get( 'share' );

				$output .= $share->get_display(
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `social_media`.
			 *
			 * Sortable option type to configure social
			 * media buttons of a contact bar.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `social_media`.
			 * }
			 */
			case 'social_media':
				$social_media = $advanced->get( 'social_media' );

				$output .= $social_media->get_display(
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `slider`.
			 *
			 * Sortable option type to configure images
			 * of a simple slider.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `slider`.
			 * }
			 */
			case 'slider':
				$slider = $advanced->get( 'slider' );

				$output .= $slider->get_display(
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `logos`.
			 *
			 * Sortable option type to configure a group of
			 * logos, originally designed for the "Partner Logos"
			 * element of the layout builder.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `logos`.
			 * }
			 */
			case 'logos':
				$logos = $advanced->get( 'logos' );

				$output .= $logos->get_display(
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `tabs`.
			 *
			 * Sortable option type to configure group
			 * of tabs in tabs element.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `tabs`.
			 * }
			 */
			case 'tabs':
				$tabs = $advanced->get( 'tabs' );

				$output .= $tabs->get_display(
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `testimonials`.
			 *
			 * Sortable option type to configure group of
			 * testimonials in a testimonial slider.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `testimonials`.
			 * }
			 */
			case 'testimonials':
				$testimonials = $advanced->get( 'testimonials' );

				$output .= $testimonials->get_display(
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `text_blocks`.
			 *
			 * Sortable option type to configure text blocks
			 * of a hero unit element.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `text_blocks`.
			 * }
			 */
			case 'text_blocks':
				$text_blocks = $advanced->get( 'text_blocks' );

				$output .= $text_blocks->get_display(
					$option['id'],
					$option_name,
					$current
				);

				break;

			/*
			 * Display option type, `toggles`.
			 *
			 * Sortable option type to configure a group of
			 * toggled content in a toggles element.
			 *
			 * @param array $option {
			 *     @type string $id   Unique ID for option.
			 *     @type string $name Title for option.
			 *     @type string $desc Description for option.
			 *     @type array  $std  Default value.
			 *     @type string $type Type of option, should be `toggles`.
			 * }
			 */
			case 'toggles':
				$toggles = $advanced->get( 'toggles' );

				$output .= $toggles->get_display(
					$option['id'],
					$option_name,
					$current
				);

				break;

		} // end switch ( $option['type'] )

		/**
		 * Filters the output of the options page, during the
		 * looping of each option. Use to add custom option type.
		 *
		 * With a unique $type that's not used anywhere above,
		 * you can intercept things here and append to the $output,
		 * if your $option['type'] is in play.
		 *
		 * Also remember that in order for you option to save to
		 * the database, you must filter on a sanitization function
		 * to `themeblvd_sanitize_{type}`.
		 *
		 * @since @@name-framework 2.2.0
		 *
		 * @param string $output      All HTML for options page, up to this point.
		 * @param array  $option      Data for current option in loop.
		 * @param string $option_name Constructed name attribute for option, use like `name={$option_name[ $option['id'] ]}`.
		 * @param array  $current     Current saved value, or default value if not saved yet.
		 */
		$output = apply_filters( 'themeblvd_option_type', $output, $option, $option_name, $current );

		// Finish off standard options and add description.
		if ( 'heading' !== $option['type'] && 'info' !== $option['type'] ) {

			$output .= '</div><!-- .controls (end) -->' . "\n";;

			if ( 'editor' !== $option['type'] ) { // The `editor` type handles its own description.

				if ( ! empty( $option['desc'] ) ) {

					if ( is_array( $option['desc'] ) ) {

						foreach ( $option['desc'] as $desc_id => $desc ) {

							$output .= sprintf(
								'<div class="explain hide %s">%s</div>',
								esc_attr( $desc_id ),
								themeblvd_kses( $desc )
							);

						}
					} else {

						$output .= sprintf(
							'<div class="explain">%s</div>',
							themeblvd_kses( $option['desc'] )
						);

					}

					$output .= "\n";

				}
			}

			$output .= '<div class="clear"></div>' . "\n";;

			$output .= '</div><!-- .option (end) -->' . "\n";

			$output .= '</div><!-- .section (end) -->' . "\n\n";;

		}
	}

	/*
	 * If a tabbed navigation was added with any options
	 * with type `heading`, close the last tab.
	 */
	if ( $menu ) {

		$output .= '</div><!-- .group (end) -->';

	}

	return array(
		$output, // The actual options form, split into sections and tabs.
		$menu,   // Navigation tabs, if there were any `heading` option types.
	);

}
