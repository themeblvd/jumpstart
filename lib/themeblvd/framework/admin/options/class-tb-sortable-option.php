<?php
/**
 * Theme Blvd Sortable Option.
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 * @since 		2.5.0
 */
abstract class Theme_Blvd_Sortable_Option {

	/**
	 * Force Extending class to define these methods
	 */
	abstract protected function get_options();
	abstract protected function get_labels();

	/*--------------------------------------------*/
	/* Properties, private
	/*--------------------------------------------*/

	/**
	 * Options for each sortable element
	 *
	 * @since 2.5.0
	 * @var array
	 */
	private $options = array();

	/**
	 * Trigger option. This option's value will
	 * get feed to the toggle's handle as its updated.
	 *
	 * @since 2.5.0
	 * @var string
	 */
	private $trigger = '';

	/*--------------------------------------------*/
	/* Properties, protected
	/*--------------------------------------------*/

	/**
	 * Text strings for managing items.
	 *
	 * @since 2.5.0
	 * @var array
	 */
	protected $labels = array();

	/**
	 * Current advanced option type. Set by child class.
	 *
	 * @since 2.5.0
	 * @var string
	 */
	protected $type = '';

	/**
	 * Optional maximum number of sortable items
	 *
	 * @since 2.5.0
	 * @var int
	 */
	protected $max = 0;

	/*--------------------------------------------*/
	/* Constructor
	/*--------------------------------------------*/

	/**
	 * Constructor.
	 *
	 * @since 2.5.0
	 */
	public function __construct( $ajax = true ) {

		// Setup labels
		$this->labels = array(
			'add' 					=> __('Add Item', '@@text-domain'),
			'delete'				=> __('Delete Item', '@@text-domain'),
			'delete_confirm'		=> __('Are you sure you want to delete this item?', '@@text-domain'),
			'delete_all' 			=> __('Delete All Items','@@text-domain'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all items?','@@text-domain')
		);

		// Set labels (inherited from child class)
		$this->labels = wp_parse_args( $this->get_labels(), $this->labels );

		// Set Options (inherited from child class)
		$this->options = $this->get_options();

		// Determine trigger option
		foreach ( $this->options as $option ) {
			if ( isset( $option['trigger'] ) && $option['trigger'] ) {
				$this->trigger = $option['id'];
			}
		}

		if ( $ajax ) {

			// Make sure there's no duplicate AJAX actions added
			remove_all_actions( 'wp_ajax_themeblvd_add_'.$this->type.'_item' );

			// Add item with AJAX - Use: themeblvd_add_{$type}_item
			add_action( 'wp_ajax_themeblvd_add_'.$this->type.'_item', array( $this, 'add_item' ) );
		}
	}

	/*--------------------------------------------*/
	/* Methods
	/*--------------------------------------------*/

	/**
	 * Display the option.
	 *
	 * @since 2.5.0
	 */
	public function get_display( $option_id, $option_name, $items ) {

		$ajax_nonce = wp_create_nonce( 'themeblvd_sortable_option' );

		$output  = sprintf('<div class="tb-sortable-option" data-security="%s" data-name="%s" data-id="%s" data-type="%s" data-max="%s">', $ajax_nonce, esc_html($option_name), esc_html($option_id), $this->type, $this->max );

		// Header (blank by default)
		$output .= $this->get_display_header( $option_id, $option_name, $items );

		// Output blank option for the sortable items. If the user doesn't
		// setup any sortable items and then they save, this will ensure that
		// at least a blank value get saved to the option.
		$output .= sprintf( '<input type="hidden" name="%s" />', esc_attr($option_name.'['.$option_id.']') );

		// Start sortable section
		$output .= '<div class="item-container">';

		if ( is_array( $items ) && count( $items ) > 0 ) {
			foreach ( $items as $item_id => $item ) {
				$output .= $this->get_item( $option_id, $item_id, $item, $option_name );
			}
		}

		$output .= '</div><!-- .item-container (end) -->';

		// Footer and button to add items
		$output .= $this->get_display_footer( $option_id, $option_name, $items );

		$output .= '</div><!-- .tb-sortable-option (end) -->';

		return $output;
	}

	/**
	 * Display the header.
	 *
	 * @since 2.5.0
	 */
	protected function get_display_header( $option_id, $option_name, $items ) {
		return '';
	}

	/**
	 * Display the footer.
	 *
	 * @since 2.5.0
	 */
	protected function get_display_footer( $option_id, $option_name, $items ) {

		$footer  = '<footer class="clearfix">';

		$disabled = '';

		if ( $this->max && count($items) >= $this->max ) {
			$disabled = 'disabled';
		}

		$footer .= sprintf( '<input type="button" class="add-item button-secondary" value="%s" %s />', esc_attr($this->labels['add']), $disabled );

		if ( $this->max ) {
			$footer .= sprintf('<div class="max">%s: %s</div>', esc_html__('Maximum', '@@text-domain'), $this->max);
		}

		$footer .= sprintf( '<a href="#" title="%s" class="tb-tooltip-link delete-sortable-items hide" data-tooltip-text="%s"><i class="tb-icon-cancel-circled"></i></a>', esc_attr($this->labels['delete_all_confirm']), esc_attr($this->labels['delete_all']) );
		$footer .= '</footer>';

		return $footer;
	}

	/**
	 * Individual sortable item.
	 *
	 * @since 2.5.0
	 */
	public function get_item( $option_id, $item_id, $item, $option_name ) {

		$item_output  = sprintf( '<div id="%s" class="widget item">', $item_id );

		$item_output .= $this->get_item_handle( $item );

		$item_output .= '<div class="item-content">';

		foreach ( $this->options as $option ) {

			// Wrap a some of the options in a DIV
			// to be utilized from the javascript.

			if ( $option['type'] == 'subgroup_start' ) {

				$class = 'subgroup';

				if ( isset( $option['class'] ) ) {
					$class .= ' '.$option['class'];
				}

				$item_output .= sprintf('<div class="%s">', $class);
				continue;
			}

			if ( $option['type'] == 'subgroup_end' ) {
				$item_output .= '</div><!-- .subgroup (end) -->';
				continue;
			}

			// Continue with normal form items
			$class = 'section-'.$option['type'];

			if ( isset( $option['class'] ) ) {
				$class .= ' '.$option['class'];
			}

			$item_output .= sprintf( '<div class="section %s">', $class );

			if ( ! empty( $option['name'] ) ) {
				$item_output .= sprintf( '<h4>%s</h4>', esc_html($option['name']) );
			}

			$item_output .= '<div class="option clearfix">';
			$item_output .= '<div class="controls">';

			$current = '';

			if ( isset($option['id']) && isset($item[$option['id']]) ) {
				$current = $item[$option['id']];
			}

			switch ( $option['type'] ) {

				/*---------------------------------------*/
				/* Hidden input
				/*---------------------------------------*/

				case 'hidden' :
					$class = 'of-input';
					if ( $this->trigger == $option['id'] ) {
						$class .= ' handle-trigger';
					}
					$item_output .= sprintf( '<input id="%s" class="%s" name="%s" type="hidden" value="%s" />', esc_attr( $option['id'] ), $class, esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ), esc_attr($current) );
					break;

				/*---------------------------------------*/
				/* Text input
				/*---------------------------------------*/

				case 'text':

					$place_holder = '';
					if ( ! empty( $option['pholder'] ) ) {
						$place_holder = ' placeholder="'.$option['pholder'].'"';
					}

					$item_output .= '<div class="input-wrap">';

					if ( isset( $option['icon'] ) && ( $option['icon'] == 'image' || $option['icon'] == 'vector' ) ) {
						$item_output .= '<a href="#" class="tb-input-icon-link tb-tooltip-link" data-target="themeblvd-icon-browser-'.$option['icon'].'" data-icon-type="'.$option['icon'].'" data-tooltip-text="'.esc_attr__('Browse Icons', '@@text-domain').'"><i class="tb-icon-picture"></i></a>';
					}

					$class = 'of-input';
					if ( $this->trigger == $option['id'] ) {
						$class .= ' handle-trigger';
					}

					$item_output .= sprintf( '<input id="%s" class="%s" name="%s" type="text" value="%s"%s />', esc_attr( $option['id'] ), $class, esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ), esc_attr($current), $place_holder );
					$item_output .= '</div><!-- .input-wrap (end) -->';
					break;

				/*---------------------------------------*/
				/* Textarea
				/*---------------------------------------*/

				case 'textarea':

					$place_holder = '';
					if ( ! empty( $option['pholder'] ) ) {
						$place_holder = ' placeholder="'.$option['pholder'].'"';
					}

					$cols = '8';
					if ( isset( $option['options'] ) && isset( $option['options']['cols'] ) ) {
						$cols = $option['options']['cols'];
					}

					if ( isset( $option['editor'] ) || isset( $option['code'] ) ) {

						$item_output .= '<div class="textarea-wrap with-editor-nav">';

						$item_output .= '<nav class="editor-nav">';

						if ( isset( $option['editor'] ) && $option['editor'] ) {
							$item_output .= '<a href="#" class="tb-textarea-editor-link tb-tooltip-link" data-tooltip-text="'.esc_attr__('Open in Editor', '@@text-domain').'" data-target="themeblvd-editor-modal"><i class="tb-icon-pencil"></i></a>';
						}

						if ( isset( $option['code'] ) && in_array( $option['code'], array( 'html', 'javascript', 'css' ) ) ) {
							$item_output .= '<a href="#" class="tb-textarea-code-link tb-tooltip-link" data-tooltip-text="'.esc_attr__('Open in Code Editor', '@@text-domain').'" data-target="'.esc_textarea( $option['id'] ).'" data-title="'.esc_attr($option['name']).'" data-code_lang="'.$option['code'].'"><i class="tb-icon-code"></i></a>';
						}

						$item_output .= '</nav>';

					} else {
						$item_output .= '<div class="textarea-wrap">';
					}

					$class = 'of-input';
					if ( $this->trigger == $option['id'] ) {
						$class .= ' handle-trigger';
					}

					$item_output .= sprintf( '<textarea id="%s" class="%s" name="%s" cols="%s" rows="8"%s>%s</textarea>', esc_textarea( $option['id'] ), $class, esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ), esc_attr($cols), $place_holder, esc_textarea($current) );
					$item_output .= '</div><!-- .textarea-wrap (end) -->';

					break;

				/*---------------------------------------*/
				/* <select> menu
				/*---------------------------------------*/

				case 'select':

					$item_output .= '<div class="tb-fancy-select">';

					$class = 'of-input';
					if ( $this->trigger == $option['id'] ) {
						$class .= ' handle-trigger';
					}

					$item_output .= sprintf( '<select class="%s" name="%s" id="%s">', $class, esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ), esc_attr($option['id']) );

					foreach ( $option['options'] as $key => $value ) {
						$item_output .= sprintf( '<option%s value="%s">%s</option>', selected( $key, $current, false ), esc_attr($key), esc_html($value) );
					}

					$item_output .= '</select>';
					$item_output .= '<span class="trigger"></span>';
					$item_output .= '<span class="textbox"></span>';
					$item_output .= '</div><!-- .tb-fancy-select (end) -->';

					break;

				/*---------------------------------------*/
				/* Checkbox
				/*---------------------------------------*/

				case 'checkbox':
					$item_output .= sprintf( '<input id="%s" class="of-input" name="%s" type="checkbox" %s />', esc_attr( $option['id'] ), esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ), checked( $current, 1, false ) );
					break;

				/*---------------------------------------*/
				/* Content option type
				/*---------------------------------------*/

				case 'content' :
					$item_output .= themeblvd_content_option( $option['id'], $option_name.'['.$option_id.']['.$item_id.']', $current, $option['options'] );
					break;

				/*---------------------------------------*/
				/* Color
				/*---------------------------------------*/

				case 'color' :
					$def_color = '';
					if ( ! empty( $option['std'] ) ) {
						$def_color = $option['std'];
					}
					$item_output .= sprintf( '<input id="%s" name="%s" type="text" value="%s" class="tb-color-picker" data-default-color="%s" />', esc_attr( $option['id'] ), esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ), esc_attr( $current ), esc_attr($def_color) );
					break;

				/*---------------------------------------*/
				/* Slide (jQuery UI slider)
				/*---------------------------------------*/

				case 'slide' :

					$item_output .= '<div class="jquery-ui-slider-wrap">';

					$slide_options = array(
						'min'	=> '1',
						'max'	=> '100',
						'step'	=> '1',
						'units'	=> '' // for display only
					);

					if ( isset( $option['options'] ) ) {
						$slide_options = wp_parse_args( $option['options'], $slide_options );
					}

					$item_output .= '<div class="jquery-ui-slider"';

					foreach ( $slide_options as $param_id => $param ) {
						$item_output .= sprintf( ' data-%s="%s"', $param_id, $param );
					}

					$item_output .= '></div>';

					if ( ! $current && $current !== '0' ) { // $current can't be empty or else the UI slider won't work
						$current = $slide_options['min'].$slide_options['units'];
					}

					$item_output .= sprintf( '<input id="%s" class="of-input slider-input" name="%s" type="hidden" value="%s" />', esc_attr( $option['id'] ), esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ), esc_attr($current) );
					$item_output .= '</div><!-- .jquery-ui-slider-wrap (end) -->';
					break;


				/*---------------------------------------*/
				/* Uploader
				/*---------------------------------------*/

				case 'upload' :

					$args = array(
						'option_name'	=> $option_name.'['.$option_id.']['.$item_id.']',
						'id'			=> $option['id']
					);

					if ( ! empty( $option['advanced'] ) ) {

						// Advanced type will allow for selecting
						// image crop size for URL.
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

						if ( isset( $current['width'] ) ) {
							$args['value_width'] = $current['width'];
						}

						if ( isset( $current['height'] ) ) {
							$args['value_height'] = $current['height'];
						}

					} else {

						$args['value'] = $current;
						$args['type'] = 'standard';

						if ( isset( $option['send_back'] ) ) {
							$args['send_back'] = $option['send_back'];
						} else {
							$args['send_back'] = 'url';
						}

						if ( ! empty( $option['video'] ) ) {
							$args['type'] = 'video';
						}
					}

					$item_output .= themeblvd_media_uploader( $args );

					break;

				/*---------------------------------------*/
				/* Button
				/*---------------------------------------*/

				case 'button' :
					$item_output .= themeblvd_button_option( $option['id'], $option_name.'['.$option_id.']['.$item_id.']', $current );
					break;

				/*---------------------------------------*/
				/* Geo (Latitude and Longitude)
				/*---------------------------------------*/

				case 'geo' :

					// Values
					$lat = '';
					if ( isset( $current['lat'] ) ) {
						$lat = $current['lat'];
					}

					$long = '';
					if ( isset( $current['long'] ) ) {
						$long = $current['long'];
					}

					$item_output .= '<div class="geo-wrap clearfix">';

					// Latitude
					$item_output .= '<div class="geo-lat">';
					$item_output .= sprintf( '<input id="%s_lat" class="of-input geo-input" name="%s" type="text" value="%s" />', esc_attr($option['id']), esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].'][lat]' ), esc_attr($lat) );
					$item_output .= '<span class="geo-label">'.esc_html__('Latitude', '@@text-domain').'</span>';
					$item_output .= '</div><!-- .geo-lat (end) -->';

					// Longitude
					$item_output .= '<div class="geo-long">';
					$item_output .= sprintf( '<input id="%s_long" class="of-input geo-input" name="%s" type="text" value="%s" />', esc_attr($option['id']), esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].'][long]' ), esc_attr($long) );
					$item_output .= '<span class="geo-label">'.esc_html__('Longitude', '@@text-domain').'</span>';
					$item_output .= '</div><!-- .geo-long (end) -->';

					$item_output .= '</div><!-- .geo-wrap (end) -->';

					// Generate lat and long
					$item_output .= '<div class="geo-generate">';
					$item_output .= '<h5>'.esc_html__('Generate Coordinates', '@@text-domain').'</h5>';
					$item_output .= '<div class="data clearfix">';
					$item_output .= '<span class="overlay"><span class="tb-loader ajax-loading"><i class="tb-icon-spinner"></i></span></span>';
					$item_output .= '<input type="text" value="" class="address" />';
					$item_output .= sprintf( '<a href="#" class="button-secondary geo-insert-lat-long" data-oops="%s">%s</a>', esc_html__('Oops! Sorry, we weren\'t able to get coordinates from that address. Try again.', '@@text-domain'), esc_html__('Generate', '@@text-domain') );
					$item_output .= '</div><!-- .data (end) -->';
					$item_output .= '<p class="note">';
					$item_output .= esc_html__('Enter an address, as you would do at maps.google.com.', '@@text-domain').'<br>';
					$item_output .= esc_html__('Example Address', '@@text-domain').': "123 Smith St, Chicago, USA"';
					$item_output .= '</p>';
					$item_output .= '</div><!-- .geo-generate (end) -->';

			}

			$item_output .= '</div><!-- .controls (end) -->';

			if ( ! empty( $option['desc'] ) ) {
				if ( is_array( $option['desc'] ) ) {
					foreach ( $option['desc'] as $desc_id => $desc ) {
						$item_output .= '<div class="explain hide '.$desc_id.'">';
						$item_output .= themeblvd_kses($desc);
						$item_output .= '</div>';
					}
				} else {
					$item_output .= '<div class="explain">';
					$item_output .= themeblvd_kses($option['desc']);
					$item_output .= '</div>';
				}
			}

			$item_output .= '</div><!-- .options (end) -->';

			$item_output .= '</div><!-- .section (end) -->';
		}

		// Delete item
		$item_output .= '<div class="section">';
		$item_output .= sprintf( '<a href="#%s" class="delete-sortable-item" title="%s">%s</a>', $item_id, esc_attr($this->labels['delete_confirm']), esc_attr($this->labels['delete']) );
		$item_output .= '</div>';

		$item_output .= '</div><!-- .item-content (end) -->';
		$item_output .= '</div>';

		return $item_output;
	}

	/**
	 * Get the handle for an item.
	 *
	 * @since 2.5.0
	 */
	protected function get_item_handle( $item ) {
		$handle  = '<div class="item-handle closed">';
		$handle .= '<h3>&nbsp;</h3>';
		$handle .= '<span class="tb-icon-sort"></span>';
		$handle .= '<a href="#" class="toggle"><span class="tb-icon-up-dir"></span></a>';
		$handle .= '</div>';
		return $handle;
	}

	/**
	 * Set default value for a new item.
	 *
	 * @since 2.5.0
	 */
	public function get_default() {

		$default = array();

		foreach ( $this->options as $option ) {
			if ( isset( $option['std'] ) ) {
				$default[$option['id']] = $option['std'];
			}
		}

		return $default;
	}

	/**
	 * Add item via Ajax.
	 *
	 * @since 2.5.0
	 */
	public function add_item() {
		check_ajax_referer( 'themeblvd_sortable_option', 'security' );
		echo $this->get_item( $_POST['data']['option_id'], uniqid( 'item_' . rand() ), $this->get_default(), $_POST['data']['option_name'] );
		die();
	}

}

/**
 * Progress Bars option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Bars_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'bars';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'label',
				'name'		=> __('Display Label', '@@text-domain'),
				'desc'		=> __('Enter a label for this display.', '@@text-domain').'<br>'.__('Ex: Graphic Design', '@@text-domain'),
				'type'		=> 'text',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'label_value',
				'name'		=> __('Value Display Label', '@@text-domain'),
				'desc'		=> __('Enter a label to display the value.', '@@text-domain').'<br>'.__('Ex: 80%', '@@text-domain'),
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'value',
				'name'		=> __('Value', '@@text-domain'),
				'desc'		=> __('Enter a number for the value.', '@@text-domain').'<br>'.__('Ex: 80', '@@text-domain'),
				'std'		=> '',
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'total',
				'name'		=> __('Total', '@@text-domain'),
				'desc'		=> __('Enter a number, which your above value should be divided into. If your above value is meant to represent a straight percantage, then this "total" number should be 100.', '@@text-domain'),
				'std'		=> '100',
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'color',
				'name'		=> __('Color', '@@text-domain'),
				'desc'		=> __('Select a color that represents this progress bar.', '@@text-domain'),
				'std'		=> '#cccccc',
				'type'		=> 'color'
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Progress Bar','@@text-domain'),
			'delete' 				=> __('Delete Progress Bar','@@text-domain'),
			'delete_confirm'		=> __('Are you sure you want to delete this progress bar?', '@@text-domain'),
			'delete_all' 			=> __('Delete All Progress Bars','@@text-domain'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all progress bars?','@@text-domain')
		);
		return $labels;
	}

}

/**
 * Buttons option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Buttons_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'buttons';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'show-hide-toggle'
		    ),
			array(
				'id' 		=> 'color',
				'name'		=> __('Button Color', '@@text-domain'),
				'desc'		=> __('Select what color you\'d like to use for this button.', '@@text-domain'),
				'type'		=> 'select',
				'class'		=> 'trigger',
				'options'	=> themeblvd_colors()
			),
			array(
				'id' 		=> 'custom',
				'name'		=> __('Custom Button Color', '@@text-domain'),
				'desc'		=> __('Configure a custom style for the button.', '@@text-domain'),
				'std'		=> array(
					'bg' 				=> '#ffffff',
					'bg_hover'			=> '#ebebeb',
					'border' 			=> '#cccccc',
					'text'				=> '#333333',
					'text_hover'		=> '#333333',
					'include_bg'		=> 1,
					'include_border'	=> 1
				),
				'type'		=> 'button',
				'class'		=> 'hide receiver receiver-custom'
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
			array(
				'id' 		=> 'text',
				'name'		=> __('Button Text', '@@text-domain'),
				'desc'		=> __('Enter the text for the button.', '@@text-domain'),
				'std'		=> 'Get Started Today!',
				'type'		=> 'text',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'size',
				'name'		=> __('Button Size', '@@text-domain'),
				'desc'		=> __('Select the size you\'d like used for this button.', '@@text-domain'),
				'type'		=> 'select',
				'std'		=> 'large',
				'options'	=> array(
					'mini' 		=> __('Mini', '@@text-domain'),
					'small' 	=> __('Small', '@@text-domain'),
					'default' 	=> __('Normal', '@@text-domain'),
					'large' 	=> __('Large', '@@text-domain'),
					'x-large' 	=> __('X-Large', '@@text-domain'),
					'xx-large' 	=> __('XX-Large', '@@text-domain'),
					'xxx-large' => __('XXX-Large', '@@text-domain')
				)
			),
			array(
				'id' 		=> 'url',
				'name'		=> __('Link URL', '@@text-domain'),
				'desc'		=> __('Enter the full URL where you want the button\'s link to go.', '@@text-domain'),
				'std'		=> 'http://www.your-site.com/your-landing-page',
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'target',
				'name'		=> __('Link Target', '@@text-domain'),
				'desc'		=> __('Select how you want the button to open the webpage.', '@@text-domain'),
				'type'		=> 'select',
				'options'	=> array(
			        '_self' 	=> __('Same Window', '@@text-domain'),
			        '_blank' 	=> __('New Window', '@@text-domain'),
			        'lightbox' 	=> __('Lightbox Popup', '@@text-domain')
				)
			),
			array(
				'id' 		=> 'icon_before',
				'name'		=> __('Icon Before Button Text (optional)', '@@text-domain'),
				'desc'		=> __('Icon before text of button. This can be any FontAwesome vector icon ID.', '@@text-domain'),
				'type'		=> 'text',
				'icon'		=> 'vector'
			),
			array(
				'id' 		=> 'icon_after',
				'name'		=> __('Icon After Button Text (optional)', '@@text-domain'),
				'desc'		=> __('Icon after text of button. This can be any FontAwesome vector icon ID.', '@@text-domain'),
				'type'		=> 'text',
				'icon'		=> 'vector'
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Button','@@text-domain'),
			'delete' 				=> __('Delete Button','@@text-domain'),
			'delete_confirm'		=> __('Are you sure you want to delete this button?', '@@text-domain'),
			'delete_all' 			=> __('Delete All Buttons','@@text-domain'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all buttons?','@@text-domain')
		);
		return $labels;
	}

}

/**
 * Datasets option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Datasets_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'datasets';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'label',
				'name'		=> __('Label', '@@text-domain'),
				'desc'		=> __('Enter a label for this dataset.', '@@text-domain'),
				'type'		=> 'text',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'values',
				'name'		=> __('Values', '@@text-domain'),
				'desc'		=> __('Enter a comma separated list of values for this data set.', '@@text-domain').'<br>'.__('Ex: 10, 20, 30, 40, 50, 60', '@@text-domain'),
				'std'		=> '',
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'color',
				'name'		=> __('Color', '@@text-domain'),
				'desc'		=> __('Select a color that represents this data set.', '@@text-domain'),
				'std'		=> '#cccccc',
				'type'		=> 'color'
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Data Set','@@text-domain'),
			'delete' 				=> __('Delete Data Set','@@text-domain'),
			'delete_confirm'		=> __('Are you sure you want to delete this data set?', '@@text-domain'),
			'delete_all' 			=> __('Delete All Data Sets','@@text-domain'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all data sets?','@@text-domain')
		);
		return $labels;
	}

}

/**
 * Markers option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Locations_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'locations';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'name',
				'name'		=> __('Location Name', '@@text-domain'),
				'desc'		=> __('Enter a name for this location.', '@@text-domain'),
				'type'		=> 'text',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'geo',
				'name'		=> __('Location Latitude and Longitude', '@@text-domain'),
				'desc'		=> __('For this marker to be displayed, there needs to be a latitude and longitude saved. You can use the tool below the text fields to generate the coordinates.', '@@text-domain'),
				'std'		=> array(
					'lat'	=> 0,
					'long'	=> 0
				),
				'type'		=> 'geo'
			),
			array(
				'id' 		=> 'info',
				'name'		=> __('Location Information', '@@text-domain'),
				'desc'		=> __('When the marker is clicked, this information will be shown. You can put basic HTML formatting in here, if you like; just don\'t get too carried away.', '@@text-domain'),
				'type'		=> 'textarea',
				'editor'	=> true,
				'code'		=> 'html'
			),
			array(
				'id' 		=> 'image',
				'name'		=> __('Custom Marker Image (optional)', '@@text-domain'),
				'desc'		=> __('If you\'d like a custom image to replace the default Google Map marker, you can insert it here.', '@@text-domain'),
				'std'		=> '',
				'type'		=> 'upload',
				'advanced'	=> true
			),
			array(
				'id' 		=> 'width',
				'name'		=> __('Custom Marker Image Width (optional)', '@@text-domain'),
				'desc'		=> __('If you\'d like to scale your custom marker image, input a width in pixels. Ex: 50', '@@text-domain'),
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'height',
				'name'		=> __('Custom Marker Image Height (optional)', '@@text-domain'),
				'desc'		=> __('If you\'d like to scale your custom marker image, input a height in pixels. Ex: 32', '@@text-domain'),
				'type'		=> 'text'
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Map Location','@@text-domain'),
			'delete' 				=> __('Delete Location','@@text-domain'),
			'delete_confirm'		=> __('Are you sure you want to delete this location?', '@@text-domain'),
			'delete_all' 			=> __('Delete All Locations','@@text-domain'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all map locations?','@@text-domain')
		);
		return $labels;
	}

}

/**
 * Sectors option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Sectors_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'sectors';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'label',
				'name'		=> __('Label', '@@text-domain'),
				'desc'		=> __('Enter a label for this sector.', '@@text-domain'),
				'type'		=> 'text',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'value',
				'name'		=> __('Value', '@@text-domain'),
				'desc'		=> __('Enter a numeric value for this sector.', '@@text-domain'),
				'std'		=> '0',
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'color',
				'name'		=> __('Color', '@@text-domain'),
				'desc'		=> __('Select a color that represents this sector.', '@@text-domain'),
				'std'		=> '#cccccc',
				'type'		=> 'color'
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Sector','@@text-domain'),
			'delete' 				=> __('Delete Sector','@@text-domain'),
			'delete_confirm'		=> __('Are you sure you want to delete this sector?', '@@text-domain'),
			'delete_all' 			=> __('Delete All Sectors','@@text-domain'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all sectors?','@@text-domain')
		);
		return $labels;
	}

}

/**
 * Slider option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Slider_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type -- Check to make sure type
		// is not set to allow child classes
		// of this child.
		if ( ! $this->type ) {
			$this->type = 'slider';
		}

		// Run parent
		parent::__construct();
	}

	/**
	 * Display the footer.
	 *
	 * @since 2.5.0
	 */
	protected function get_display_footer( $option_id, $option_name, $items ) {
		$footer  = '<footer>';
		$footer .= sprintf( '<a href="#" id="%s" class="add-images button-secondary" data-title="%s" data-button="%s">%s</a>', uniqid( 'slider_' . rand() ), esc_attr($this->labels['modal_title']), esc_attr($this->labels['modal_button']), esc_attr($this->labels['add']) );
		$footer .= sprintf( '<a href="#" title="%s" class="tb-tooltip-link delete-sortable-items hide" data-tooltip-text="%s"><i class="tb-icon-cancel-circled"></i></a>', esc_attr($this->labels['delete_all_confirm']), esc_attr($this->labels['delete_all']) );
		$footer .= '</footer>';
		return $footer;
	}

	/**
	 * Get the handle for an item.
	 *
	 * @since 2.5.0
	 */
	protected function get_item_handle( $item ) {

		$handle  = '<div class="item-handle closed">';

		if ( isset( $item['thumb'] ) ) {
			$handle .= sprintf( '<span class="preview"><img src="%s" /></span>', $item['thumb'] );
		}

		$handle .= '<h3>&nbsp;</h3>';
		$handle .= '<span class="tb-icon-sort"></span>';
		$handle .= '<a href="#" class="toggle"><span class="tb-icon-up-dir"></span></a>';
		$handle .= '</div>';

		return $handle;
	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'id',
				'type'		=> 'hidden',
				'std'		=> ''
			),
			array(
				'id' 		=> 'src',
				'type'		=> 'hidden',
				'std'		=> ''
			),
			array(
				'id' 		=> 'alt',
				'type'		=> 'hidden',
				'std'		=> '',
				'trigger'	=> true
			),
			/*
			array(
				'id' 		=> 'crop',
				'type'		=> 'hidden',
				'std'		=> 'slider-large',
				'class'		=> 'match' // Will match with image crop selection
			),
			*/
			array(
				'id' 		=> 'thumb',
				'type'		=> 'hidden',
				'std'		=> ''
			),
			array(
				'id' 		=> 'title',
				'name'		=> __('Title (optional)', '@@text-domain'),
				'desc'		=> __('If you\'d like a headline to show on the slide, you may enter it here.', '@@text-domain'),
				'type'		=> 'text',
				'std'		=> '',
				'class'		=> 'slide-title'
			),
			array(
				'id' 		=> 'desc',
				'name'		=> __('Description (optional)', '@@text-domain'),
				'desc'		=> __('If you\'d like a description to show on the slide, you may enter it here.', '@@text-domain'),
				'type'		=> 'textarea',
				'std'		=> '',
				'class'		=> 'slide-desc'
			),
			array(
				'type' 		=> 'subgroup_start',
				'class'		=> 'slide-link show-hide-toggle desc-toggle'
			),
			array(
				'id' 		=> 'link',
				'name'		=> __('Link', '@@text-domain'),
				'desc'		=> __('Select if and how this image should be linked.', '@@text-domain'),
				'type'		=> 'select',
				'options'	=> array(
			        'none'		=> __('No Link', '@@text-domain'),
			        '_self' 	=> __('Link to webpage in same window.', '@@text-domain'),
			        '_blank' 	=> __('Link to webpage in new window.', '@@text-domain'),
			        'image' 	=> __('Link to image in lightbox popup.', '@@text-domain'),
			        'video' 	=> __('Link to video in lightbox popup.', '@@text-domain')
				),
				'class'		=> 'trigger'
			),
			array(
				'id' 		=> 'link_url',
				'name'		=> __('Link URL', '@@text-domain'),
				'desc'		=> array(
			        '_self' 	=> __('Enter a URL to a webpage.<br />Ex: http://yoursite.com/example', '@@text-domain'),
			        '_blank' 	=> __('Enter a URL to a webpage.<br />Ex: http://google.com', '@@text-domain'),
			        'image' 	=> __('Enter a URL to an image file.<br />Ex: http://yoursite.com/uploads/image.jpg', '@@text-domain'),
			        'video' 	=> __('Enter a URL to a YouTube or Vimeo page.<br />Ex: http://vimeo.com/11178250', '@@text-domain').'<br>'.__('Ex: https://youtube.com/watch?v=ginTCwWfGNY', '@@text-domain')
				),
				'type'		=> 'text',
				'std'		=> '',
				'pholder'	=> 'http://',
				'class'		=> 'receiver receiver-_self receiver-_blank receiver-image receiver-video'
			),
			array(
				'type' 		=> 'subgroup_end'
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Images','@@text-domain'),
			'delete' 				=> __('Remove Image','@@text-domain'),
			'delete_all' 			=> __('Remove All Images','@@text-domain'),
			'delete_all_confirm' 	=> __('Are you sure you want to remove all images?','@@text-domain'),
			'modal_title'			=> __('Select Images','@@text-domain'),
			'modal_button'			=> __('Add Images','@@text-domain')
		);
		return $labels;
	}

	/**
	 * Add item via Ajax.
	 *
	 * @since 2.5.0
	 */
	public function add_item() {
		check_ajax_referer( 'themeblvd_sortable_option', 'security' );
		$items = $_POST['data']['items'];

		foreach ( $items as $item ) {
			$val = array(
				'id' 	=> $item['id'],
				'alt'	=> $item['title'],
				'thumb'	=> $item['preview']
			);
			echo $this->get_item( $_POST['data']['option_id'], uniqid( 'item_' . rand() ), $val, $_POST['data']['option_name'] );
		}
		die();
	}

}

/**
 * Logos option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Logos_Option extends Theme_Blvd_Slider_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'logos';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'id',
				'type'		=> 'hidden',
				'std'		=> ''
			),
			array(
				'id' 		=> 'src',
				'type'		=> 'hidden',
				'std'		=> ''
			),
			array(
				'id' 		=> 'alt',
				'type'		=> 'hidden',
				'std'		=> '',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'thumb',
				'type'		=> 'hidden',
				'std'		=> ''
			),
			array(
				'id' 		=> 'name',
				'name'		=> __('Partner Name', '@@text-domain'),
				'desc'		=> __('Enter a name that corresponds to this logo.', '@@text-domain'),
				'type'		=> 'text',
				'std'		=> ''
			),
			/*
			array(
				'id' 		=> 'desc',
				'name'		=> __('Partner Description (optional)', '@@text-domain'),
				'desc'		=> __('Enter very brief description that will display as a tooltip when the user hovers on the logo.', '@@text-domain'),
				'type'		=> 'textarea',
				'std'		=> ''
			),
			*/
			array(
				'id' 		=> 'link',
				'name'		=> __('Partner Link (optional)', '@@text-domain'),
				'desc'		=> __('Enter a URL you\'d like this logo to link to.', '@@text-domain').'<br>'.__('Ex: http://partersite.com', '@@text-domain'),
				'type'		=> 'text',
				'pholder'	=> 'http://'
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Logos','@@text-domain'),
			'delete' 				=> __('Remove Logo','@@text-domain'),
			'delete_all' 			=> __('Remove All Logos','@@text-domain'),
			'delete_all_confirm' 	=> __('Are you sure you want to remove all logos?','@@text-domain'),
			'modal_title'			=> __('Select Logos','@@text-domain'),
			'modal_button'			=> __('Add Logos','@@text-domain')
		);
		return $labels;
	}

}

/**
 * Pricing table column option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Price_Cols_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'price_cols';

		// Max number of items that can be added
		$this->max = 6;

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
		    array(
				'id' 		=> 'highlight',
				'name'		=> __('Highlight Color', '@@text-domain'),
				'desc'		=> __('If you wish, you can give the column a highlight color.', '@@text-domain'),
				'type'		=> 'select',
				'std'		=> 'none',
				'options'	=> themeblvd_colors(true, false) // Include bootstrap, don't include "Custom Color" option
			),
			array(
				'id' 		=> 'title',
				'name'		=> __('Title', '@@text-domain'),
				'desc'		=> __('Enter a title for this column.', '@@text-domain').'<br>'.__('Ex: Gold Package', '@@text-domain'),
				'type'		=> 'text',
				'std'		=> '',
				'trigger'	=> true
			),
			array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'show-hide'
		    ),
			array(
				'id' 		=> 'popout',
				'name'		=> null,
				'desc'		=> __('Pop out column so it stands out from the rest.', '@@text-domain'),
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			array(
				'id' 		=> 'title_subline',
				'name'		=> __('Popout Title Subline', '@@text-domain'),
				'desc'		=> __('Because the column is popped out, enter a very brief subline for the title.', '@@text-domain').'<br>'.__('Ex: Best Value', '@@text-domain'),
				'type'		=> 'text',
				'std'		=> 'Best Value',
				'class'		=> 'hide receiver'
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
			array(
				'id' 		=> 'price',
				'name'		=> __('Price', '@@text-domain'),
				'desc'		=> __('Enter a value for the price, without the currency symbol.', '@@text-domain').'<br>'.__('Ex: 50', '@@text-domain'),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'id' 		=> 'price_subline',
				'name'		=> __('Price Subline (optional)', '@@text-domain'),
				'desc'		=> __('Enter a very brief subline for the price to show what it represents.', '@@text-domain').'<br>'.__('Ex: per month', '@@text-domain'),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'id' 		=> 'features',
				'name'		=> __('Feature List', '@@text-domain'),
				'desc'		=> __('Enter each feature, seprated by a line break. If you like, spice it up with some icons.', '@@text-domain').'<br><br>[vector_icon icon="check" color="#00aa00"]<br>[vector_icon icon="times" color="#aa0000"]',
				'type'		=> 'textarea',
				'std'		=> "Feature 1\nFeature 2\nFeature 3",
				'html'		=> true,
				'editor'	=> true
			),
			array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'show-hide'
		    ),
			array(
		    	'id' 		=> 'button',
				'name'		=> __('Button', '@@text-domain'),
				'desc'		=> __('Show button below feature list?', '@@text-domain'),
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'hide receiver show-hide-toggle'
		    ),
			array(
				'id' 		=> 'button_color',
				'name'		=> __('Button Color', '@@text-domain'),
				'desc'		=> __('Select what color you\'d like to use for this button.', '@@text-domain'),
				'type'		=> 'select',
				'class'		=> 'trigger',
				'options'	=> themeblvd_colors()
			),
			array(
				'id' 		=> 'button_custom',
				'name'		=> __('Custom Button Color', '@@text-domain'),
				'desc'		=> __('Configure a custom style for the button.', '@@text-domain'),
				'std'		=> array(
					'bg' 				=> '#ffffff',
					'bg_hover'			=> '#ebebeb',
					'border' 			=> '#cccccc',
					'text'				=> '#333333',
					'text_hover'		=> '#333333',
					'include_bg'		=> 1,
					'include_border'	=> 1
				),
				'type'		=> 'button',
				'class'		=> 'hide receiver receiver-custom'
			),
			array(
				'type'		=> 'subgroup_end'
			),
			array(
				'id' 		=> 'button_text',
				'name'		=> __('Button Text', '@@text-domain'),
				'desc'		=> __('Enter the text for the button.', '@@text-domain'),
				'std'		=> 'Purchase Now',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'button_url',
				'name'		=> __('Link URL', '@@text-domain'),
				'desc'		=> __('Enter the full URL where you want the button\'s link to go.', '@@text-domain'),
				'std'		=> 'http://www.your-site.com/your-landing-page',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'button_size',
				'name'		=> __('Button Size', '@@text-domain'),
				'desc'		=> __('Select the size you\'d like used for this button.', '@@text-domain'),
				'type'		=> 'select',
				'std'		=> 'default',
				'class'		=> 'hide receiver',
				'options'	=> array(
					'mini' 		=> __('Mini', '@@text-domain'),
					'small' 	=> __('Small', '@@text-domain'),
					'default' 	=> __('Normal', '@@text-domain'),
					'large' 	=> __('Large', '@@text-domain'),
					'x-large' 	=> __('Extra Large', '@@text-domain')
				)
			),
			array(
				'id' 		=> 'button_icon_before',
				'name'		=> __('Icon Before Button Text (optional)', '@@text-domain'),
				'desc'		=> __('Icon before text of button. This can be any FontAwesome vector icon ID.', '@@text-domain'),
				'type'		=> 'text',
				'icon'		=> 'vector',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'button_icon_after',
				'name'		=> __('Icon After Button Text (optional)', '@@text-domain'),
				'desc'		=> __('Icon after text of button. This can be any FontAwesome vector icon ID.', '@@text-domain'),
				'type'		=> 'text',
				'icon'		=> 'vector',
				'class'		=> 'hide receiver'
			),
			array(
				'type'		=> 'subgroup_end'
			)

		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Pricing Table Column','@@text-domain'),
			'delete' 				=> __('Remove Column','@@text-domain'),
			'delete_confirm' 		=> __('Are you sure you want to delete this column?','@@text-domain'),
			'delete_all' 			=> __('Remove All Columns','@@text-domain'),
			'delete_all_confirm' 	=> __('Are you sure you want to remove all columns?','@@text-domain')
		);
		return $labels;
	}

}

/**
 * Social Media buttons option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Social_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'social_media';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'icon',
				'name'		=> __('Social Icon', '@@text-domain'),
				'type'		=> 'select',
				'std'		=> 'facebook',
				'options'	=> themeblvd_get_social_media_sources(),
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'url',
				'name'		=> __('Link URL', '@@text-domain'),
				'type'		=> 'text',
				'std'		=> 'http://'
			),
			array(
				'id' 		=> 'label',
				'name'		=> __('Label', '@@text-domain'),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'id' 		=> 'target',
				'name'		=> __('Link Target', '@@text-domain'),
				'type'		=> 'select',
				'std'		=> '_blank',
				'options'	=> array(
					'_blank'	=> __('New Window', '@@text-domain'),
					'_self' 	=> __('Same Window', '@@text-domain')
				)
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Icon','@@text-domain'),
			'delete' 				=> __('Delete Icon','@@text-domain'),
			'delete_confirm'		=> __('Are you sure you want to delete this icon?', '@@text-domain'),
			'delete_all' 			=> __('Delete All Icons','@@text-domain'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all icons?','@@text-domain')
		);
		return $labels;
	}

}

/**
 * Share buttons option type -- similar to
 * social_media, but simplified.
 *
 * @since 2.5.0
 */
class Theme_Blvd_Share_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'share';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'icon',
				'name'		=> __('Social Icon', '@@text-domain'),
				'type'		=> 'select',
				'std'		=> 'facebook',
				'options'	=> themeblvd_get_share_sources(),
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'label',
				'name'		=> __('Label', '@@text-domain'),
				'type'		=> 'text',
				'std'		=> ''
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Share Icon','@@text-domain'),
			'delete' 				=> __('Delete Share Icon','@@text-domain'),
			'delete_confirm'		=> __('Are you sure you want to delete this share icon?', '@@text-domain'),
			'delete_all' 			=> __('Delete All Share Icons','@@text-domain'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all share icons?','@@text-domain')
		);
		return $labels;
	}

}

/**
 * Tabs option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Tabs_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'tabs';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'title',
				'name'		=> __('Tab Title', '@@text-domain'),
				'desc'		=> __('Enter a short title to represent this tab.', '@@text-domain'),
				'type'		=> 'text',
				'std'		=> 'Tab Title',
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'content',
				'name'		=> __('Tab Content', '@@text-domain'),
				'desc'		=> __('Configure the content of the tab. Try not to make the content too complex, as it is possible that not all shortcodes and HTML will work as expected within a set of tabs.', '@@text-domain'),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Tab','@@text-domain'),
			'delete' 				=> __('Delete Tab','@@text-domain'),
			'delete_confirm'		=> __('Are you sure you want to delete this tab?', '@@text-domain'),
			'delete_all' 			=> __('Delete All Tabs','@@text-domain'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all tabs?','@@text-domain')
		);
		return $labels;
	}

}

/**
 * Toggles option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Testimonials_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'testimonials';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			'text' => array(
				'id' 		=> 'text',
				'name' 		=> __('Testimonial Text', '@@text-domain'),
				'desc'		=> __('Enter any text of the testimonial.', '@@text-domain'),
				'type'		=> 'textarea',
				'editor'	=> true,
				'code'		=> 'html'
		    ),
			'name' => array(
				'id' 		=> 'name',
				'name' 		=> __('Name', '@@text-domain'),
				'desc'		=> __('Enter the name of the person giving the testimonial.', '@@text-domain'),
				'type'		=> 'text',
				'trigger'	=> true // Triggers this option's value to be used in toggle
		    ),
		    'tagline' => array(
				'id' 		=> 'tagline',
				'name' 		=> __('Tagline (optional)', '@@text-domain'),
				'desc'		=> __('Enter a tagline for the person giving the testimonial.', '@@text-domain').'<br>'.__('Ex: Founder and CEO', '@@text-domain'),
				'type'		=> 'text'
		    ),
		    'company' => array(
				'id' 		=> 'company',
				'name' 		=> __('Company (optional)', '@@text-domain'),
				'desc'		=> __('Enter the company the person giving the testimonial belongs to.', '@@text-domain'),
				'type'		=> 'text'
		    ),
		    'company_url' => array(
				'id' 		=> 'company_url',
				'name' 		=> __('Company URL (optional)', '@@text-domain'),
				'desc'		=> __('Enter the website URL for the company or the person giving the testimonial.', '@@text-domain'),
				'type'		=> 'text',
				'pholder'	=> 'http://'
		    ),
		    'image' => array(
				'id' 		=> 'image',
				'name' 		=> __('Image (optional)', '@@text-domain'),
				'desc'		=> __('Select a small image for the person giving the testimonial.  This will look best if you select an image size that is square.', '@@text-domain'),
				'type'		=> 'upload',
				'advanced'	=> true
		    )
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Testimonial','@@text-domain'),
			'delete' 				=> __('Delete Testimonial','@@text-domain'),
			'delete_confirm'		=> __('Are you sure you want to delete this testimonial?', '@@text-domain'),
			'delete_all' 			=> __('Delete All Testimonials','@@text-domain'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all testimonials?','@@text-domain')
		);
		return $labels;
	}

}

/**
 * Toggles option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Toggles_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'toggles';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'title',
				'name'		=> __('Title', '@@text-domain'),
				'desc'		=> __('Enter a short title to represent this toggle.', '@@text-domain'),
				'type'		=> 'text',
				'std'		=> 'Toggle Title',
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'content',
				'name'		=> __('Content', '@@text-domain'),
				'desc'		=> __('Configure the content of the toggle. Try not to make the content too complex, as it is possible that not all shortcodes and HTML will work as expected within toggle which is initially hidden.', '@@text-domain'),
				'type'		=> 'textarea',
				'editor'	=> true,
				'code'		=> 'html'
			),
			array(
				'id' 		=> 'wpautop',
				'name'		=> __('Content Formatting', '@@text-domain'),
				'desc'		=> __('Apply WordPress automatic formatting.', '@@text-domain'),
				'type'		=> 'checkbox',
				'std'		=> '1'
			),
			array(
				'id' 		=> 'open',
				'name'		=> __('Initial State', '@@text-domain'),
				'desc'		=> __('Toggle is open when the page intially loads.', '@@text-domain'),
				'type'		=> 'checkbox',
				'std'		=> '0'
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Toggle','@@text-domain'),
			'delete' 				=> __('Delete Toggle','@@text-domain'),
			'delete_confirm'		=> __('Are you sure you want to delete this tab?', '@@text-domain'),
			'delete_all' 			=> __('Delete All Toggles','@@text-domain'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all toggles?','@@text-domain')
		);
		return $labels;
	}

}

/**
 * Text Blocks option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Text_Blocks_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'text_blocks';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
	 *
	 * @since 2.5.0
	 */
	public function get_options() {
		$options = array(
			array(
				'id' 		=> 'text',
				'name'		=> __('Text', '@@text-domain'),
				'desc'		=> __('Enter the text to display in this text block.', '@@text-domain'),
				'type'		=> 'textarea',
				'std'		=> '',
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'size',
				'name'		=> __('Text Size', '@@text-domain'),
				'desc'		=> __('Set the size of the text, relative to the site\'s main font size.', '@@text-domain'),
				'std'		=> '200%',
				'type'		=> 'slide',
				'options'	=> array(
					'units'	=> '%',
					'min'	=> '80',
					'max'	=> '1500',
					'step'	=> '10'
				)
			),
			array(
				'id' 		=> 'color',
				'name'		=> __('Text Color', '@@text-domain'),
				'desc'		=> __('Select the color of the text.', '@@text-domain'),
				'std'		=> '#333333',
				'type'		=> 'color'
			),
			array(
				'type'		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			array(
				'id' 		=> 'apply_bg_color',
				'name'		=> null,
				'desc'		=> __('Apply background color around text block.', '@@text-domain'),
				'std'		=> '0',
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			array(
				'id' 		=> 'bg_color',
				'name'		=> __('Background Color', '@@text-domain'),
				'desc'		=> __('Select the color of the background around the text.', '@@text-domain'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			array(
				'id'		=> 'bg_opacity',
				'name'		=> __('Background Color Opacity', '@@text-domain'),
				'desc'		=> __('Select the opacity of the background color. Selecting "100%" means that the background color is not transparent, at all.', '@@text-domain'),
				'std'		=> '1',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%',
					'1'		=> '100%'
				),
				'class'		=> 'hide receiver'
			),
			array(
				'type'		=> 'subgroup_end'
			),
			array(
				'id' 		=> 'bold',
				'name'		=> null,
				'desc'		=> __('Use header font.', '@@text-domain'),
				'type'		=> 'checkbox',
				'std'		=> '0'
			),
			array(
				'id' 		=> 'italic',
				'name'		=> null,
				'desc'		=> __('Italicize the text.', '@@text-domain'),
				'type'		=> 'checkbox',
				'std'		=> '0'
			),
			array(
				'id' 		=> 'caps',
				'name'		=> null,
				'desc'		=> __('Display text in all caps.', '@@text-domain'),
				'type'		=> 'checkbox',
				'std'		=> '0'
			),
			array(
				'id' 		=> 'suck_down',
				'name'		=> null,
				'desc'		=> __('Reduce space below text block.', '@@text-domain'),
				'type'		=> 'checkbox',
				'std'		=> '0'
			),
			array(
				'id' 		=> 'wpautop',
				'name'		=> null,
				'desc'		=> __('Apply WordPress automatic formatting.', '@@text-domain'),
				'type'		=> 'checkbox',
				'std'		=> '1'
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 *
	 * @since 2.5.0
	 */
	public function get_labels() {
		$labels = array(
			'add' 					=> __('Add Text Block','@@text-domain'),
			'delete' 				=> __('Delete Text Block','@@text-domain'),
			'delete_confirm'		=> __('Are you sure you want to delete this text block?', '@@text-domain'),
			'delete_all' 			=> __('Delete All Text Blocks','@@text-domain'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all text blocks?','@@text-domain')
		);
		return $labels;
	}

}
