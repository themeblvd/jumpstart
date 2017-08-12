<?php
/**
 * Theme Blvd Sortable Option.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     Jump_Start
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
			'add' 					=> __('Add Item', 'jumpstart'),
			'delete'				=> __('Delete Item', 'jumpstart'),
			'delete_confirm'		=> __('Are you sure you want to delete this item?', 'jumpstart'),
			'delete_all' 			=> __('Delete All Items','jumpstart'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all items?','jumpstart')
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
			$footer .= sprintf('<div class="max">%s: %s</div>', esc_html__('Maximum', 'jumpstart'), $this->max);
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
						$item_output .= '<a href="#" class="tb-input-icon-link tb-tooltip-link" data-target="themeblvd-icon-browser-'.$option['icon'].'" data-icon-type="'.$option['icon'].'" data-tooltip-text="'.esc_attr__('Browse Icons', 'jumpstart').'"><i class="tb-icon-picture"></i></a>';
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
							$item_output .= '<a href="#" class="tb-textarea-editor-link tb-tooltip-link" data-tooltip-text="'.esc_attr__('Open in Editor', 'jumpstart').'" data-target="themeblvd-editor-modal"><i class="tb-icon-pencil"></i></a>';
						}

						if ( isset( $option['code'] ) && in_array( $option['code'], array( 'html', 'javascript', 'css' ) ) ) {
							$item_output .= '<a href="#" class="tb-textarea-code-link tb-tooltip-link" data-tooltip-text="'.esc_attr__('Open in Code Editor', 'jumpstart').'" data-target="'.esc_textarea( $option['id'] ).'" data-title="'.esc_attr($option['name']).'" data-code_lang="'.$option['code'].'"><i class="tb-icon-code"></i></a>';
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
					$item_output .= '<span class="geo-label">'.esc_html__('Latitude', 'jumpstart').'</span>';
					$item_output .= '</div><!-- .geo-lat (end) -->';

					// Longitude
					$item_output .= '<div class="geo-long">';
					$item_output .= sprintf( '<input id="%s_long" class="of-input geo-input" name="%s" type="text" value="%s" />', esc_attr($option['id']), esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].'][long]' ), esc_attr($long) );
					$item_output .= '<span class="geo-label">'.esc_html__('Longitude', 'jumpstart').'</span>';
					$item_output .= '</div><!-- .geo-long (end) -->';

					$item_output .= '</div><!-- .geo-wrap (end) -->';

					// Generate lat and long
					$item_output .= '<div class="geo-generate">';
					$item_output .= '<h5>'.esc_html__('Generate Coordinates', 'jumpstart').'</h5>';
					$item_output .= '<div class="data clearfix">';
					$item_output .= '<span class="overlay"><span class="tb-loader ajax-loading"><i class="tb-icon-spinner"></i></span></span>';
					$item_output .= '<input type="text" value="" class="address" />';
					$item_output .= sprintf( '<a href="#" class="button-secondary geo-insert-lat-long" data-oops="%s">%s</a>', esc_html__('Oops! Sorry, we weren\'t able to get coordinates from that address. Try again.', 'jumpstart'), esc_html__('Generate', 'jumpstart') );
					$item_output .= '</div><!-- .data (end) -->';
					$item_output .= '<p class="note">';
					$item_output .= esc_html__('Enter an address, as you would do at maps.google.com.', 'jumpstart').'<br>';
					$item_output .= esc_html__('Example Address', 'jumpstart').': "123 Smith St, Chicago, USA"';
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
				'name'		=> __('Display Label', 'jumpstart'),
				'desc'		=> __('Enter a label for this display.', 'jumpstart').'<br>'.__('Ex: Graphic Design', 'jumpstart'),
				'type'		=> 'text',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'label_value',
				'name'		=> __('Value Display Label', 'jumpstart'),
				'desc'		=> __('Enter a label to display the value.', 'jumpstart').'<br>'.__('Ex: 80%', 'jumpstart'),
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'value',
				'name'		=> __('Value', 'jumpstart'),
				'desc'		=> __('Enter a number for the value.', 'jumpstart').'<br>'.__('Ex: 80', 'jumpstart'),
				'std'		=> '',
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'total',
				'name'		=> __('Total', 'jumpstart'),
				'desc'		=> __('Enter a number, which your above value should be divided into. If your above value is meant to represent a straight percantage, then this "total" number should be 100.', 'jumpstart'),
				'std'		=> '100',
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'color',
				'name'		=> __('Color', 'jumpstart'),
				'desc'		=> __('Select a color that represents this progress bar.', 'jumpstart'),
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
			'add' 					=> __('Add Progress Bar','jumpstart'),
			'delete' 				=> __('Delete Progress Bar','jumpstart'),
			'delete_confirm'		=> __('Are you sure you want to delete this progress bar?', 'jumpstart'),
			'delete_all' 			=> __('Delete All Progress Bars','jumpstart'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all progress bars?','jumpstart')
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
				'name'		=> __('Button Color', 'jumpstart'),
				'desc'		=> __('Select what color you\'d like to use for this button.', 'jumpstart'),
				'type'		=> 'select',
				'class'		=> 'trigger',
				'options'	=> themeblvd_colors()
			),
			array(
				'id' 		=> 'custom',
				'name'		=> __('Custom Button Color', 'jumpstart'),
				'desc'		=> __('Configure a custom style for the button.', 'jumpstart'),
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
				'name'		=> __('Button Text', 'jumpstart'),
				'desc'		=> __('Enter the text for the button.', 'jumpstart'),
				'std'		=> 'Get Started Today!',
				'type'		=> 'text',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'size',
				'name'		=> __('Button Size', 'jumpstart'),
				'desc'		=> __('Select the size you\'d like used for this button.', 'jumpstart'),
				'type'		=> 'select',
				'std'		=> 'large',
				'options'	=> array(
					'mini' 		=> __('Mini', 'jumpstart'),
					'small' 	=> __('Small', 'jumpstart'),
					'default' 	=> __('Normal', 'jumpstart'),
					'large' 	=> __('Large', 'jumpstart'),
					'x-large' 	=> __('X-Large', 'jumpstart'),
					'xx-large' 	=> __('XX-Large', 'jumpstart'),
					'xxx-large' => __('XXX-Large', 'jumpstart')
				)
			),
			array(
				'id' 		=> 'url',
				'name'		=> __('Link URL', 'jumpstart'),
				'desc'		=> __('Enter the full URL where you want the button\'s link to go.', 'jumpstart'),
				'std'		=> 'http://www.your-site.com/your-landing-page',
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'target',
				'name'		=> __('Link Target', 'jumpstart'),
				'desc'		=> __('Select how you want the button to open the webpage.', 'jumpstart'),
				'type'		=> 'select',
				'options'	=> array(
			        '_self' 	=> __('Same Window', 'jumpstart'),
			        '_blank' 	=> __('New Window', 'jumpstart'),
			        'lightbox' 	=> __('Lightbox Popup', 'jumpstart')
				)
			),
			array(
				'id' 		=> 'icon_before',
				'name'		=> __('Icon Before Button Text (optional)', 'jumpstart'),
				'desc'		=> __('Icon before text of button. This can be any FontAwesome vector icon ID.', 'jumpstart'),
				'type'		=> 'text',
				'icon'		=> 'vector'
			),
			array(
				'id' 		=> 'icon_after',
				'name'		=> __('Icon After Button Text (optional)', 'jumpstart'),
				'desc'		=> __('Icon after text of button. This can be any FontAwesome vector icon ID.', 'jumpstart'),
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
			'add' 					=> __('Add Button','jumpstart'),
			'delete' 				=> __('Delete Button','jumpstart'),
			'delete_confirm'		=> __('Are you sure you want to delete this button?', 'jumpstart'),
			'delete_all' 			=> __('Delete All Buttons','jumpstart'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all buttons?','jumpstart')
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
				'name'		=> __('Label', 'jumpstart'),
				'desc'		=> __('Enter a label for this dataset.', 'jumpstart'),
				'type'		=> 'text',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'values',
				'name'		=> __('Values', 'jumpstart'),
				'desc'		=> __('Enter a comma separated list of values for this data set.', 'jumpstart').'<br>'.__('Ex: 10, 20, 30, 40, 50, 60', 'jumpstart'),
				'std'		=> '',
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'color',
				'name'		=> __('Color', 'jumpstart'),
				'desc'		=> __('Select a color that represents this data set.', 'jumpstart'),
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
			'add' 					=> __('Add Data Set','jumpstart'),
			'delete' 				=> __('Delete Data Set','jumpstart'),
			'delete_confirm'		=> __('Are you sure you want to delete this data set?', 'jumpstart'),
			'delete_all' 			=> __('Delete All Data Sets','jumpstart'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all data sets?','jumpstart')
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
				'name'		=> __('Location Name', 'jumpstart'),
				'desc'		=> __('Enter a name for this location.', 'jumpstart'),
				'type'		=> 'text',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'geo',
				'name'		=> __('Location Latitude and Longitude', 'jumpstart'),
				'desc'		=> __('For this marker to be displayed, there needs to be a latitude and longitude saved. You can use the tool below the text fields to generate the coordinates.', 'jumpstart'),
				'std'		=> array(
					'lat'	=> 0,
					'long'	=> 0
				),
				'type'		=> 'geo'
			),
			array(
				'id' 		=> 'info',
				'name'		=> __('Location Information', 'jumpstart'),
				'desc'		=> __('When the marker is clicked, this information will be shown. You can put basic HTML formatting in here, if you like; just don\'t get too carried away.', 'jumpstart'),
				'type'		=> 'textarea',
				'editor'	=> true,
				'code'		=> 'html'
			),
			array(
				'id' 		=> 'image',
				'name'		=> __('Custom Marker Image (optional)', 'jumpstart'),
				'desc'		=> __('If you\'d like a custom image to replace the default Google Map marker, you can insert it here.', 'jumpstart'),
				'std'		=> '',
				'type'		=> 'upload',
				'advanced'	=> true
			),
			array(
				'id' 		=> 'width',
				'name'		=> __('Custom Marker Image Width (optional)', 'jumpstart'),
				'desc'		=> __('If you\'d like to scale your custom marker image, input a width in pixels. Ex: 50', 'jumpstart'),
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'height',
				'name'		=> __('Custom Marker Image Height (optional)', 'jumpstart'),
				'desc'		=> __('If you\'d like to scale your custom marker image, input a height in pixels. Ex: 32', 'jumpstart'),
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
			'add' 					=> __('Add Map Location','jumpstart'),
			'delete' 				=> __('Delete Location','jumpstart'),
			'delete_confirm'		=> __('Are you sure you want to delete this location?', 'jumpstart'),
			'delete_all' 			=> __('Delete All Locations','jumpstart'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all map locations?','jumpstart')
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
				'name'		=> __('Label', 'jumpstart'),
				'desc'		=> __('Enter a label for this sector.', 'jumpstart'),
				'type'		=> 'text',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'value',
				'name'		=> __('Value', 'jumpstart'),
				'desc'		=> __('Enter a numeric value for this sector.', 'jumpstart'),
				'std'		=> '0',
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'color',
				'name'		=> __('Color', 'jumpstart'),
				'desc'		=> __('Select a color that represents this sector.', 'jumpstart'),
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
			'add' 					=> __('Add Sector','jumpstart'),
			'delete' 				=> __('Delete Sector','jumpstart'),
			'delete_confirm'		=> __('Are you sure you want to delete this sector?', 'jumpstart'),
			'delete_all' 			=> __('Delete All Sectors','jumpstart'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all sectors?','jumpstart')
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
				'name'		=> __('Title (optional)', 'jumpstart'),
				'desc'		=> __('If you\'d like a headline to show on the slide, you may enter it here.', 'jumpstart'),
				'type'		=> 'text',
				'std'		=> '',
				'class'		=> 'slide-title'
			),
			array(
				'id' 		=> 'desc',
				'name'		=> __('Description (optional)', 'jumpstart'),
				'desc'		=> __('If you\'d like a description to show on the slide, you may enter it here.', 'jumpstart'),
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
				'name'		=> __('Link', 'jumpstart'),
				'desc'		=> __('Select if and how this image should be linked.', 'jumpstart'),
				'type'		=> 'select',
				'options'	=> array(
			        'none'		=> __('No Link', 'jumpstart'),
			        '_self' 	=> __('Link to webpage in same window.', 'jumpstart'),
			        '_blank' 	=> __('Link to webpage in new window.', 'jumpstart'),
			        'image' 	=> __('Link to image in lightbox popup.', 'jumpstart'),
			        'video' 	=> __('Link to video in lightbox popup.', 'jumpstart')
				),
				'class'		=> 'trigger'
			),
			array(
				'id' 		=> 'link_url',
				'name'		=> __('Link URL', 'jumpstart'),
				'desc'		=> array(
			        '_self' 	=> __('Enter a URL to a webpage.<br />Ex: http://yoursite.com/example', 'jumpstart'),
			        '_blank' 	=> __('Enter a URL to a webpage.<br />Ex: http://google.com', 'jumpstart'),
			        'image' 	=> __('Enter a URL to an image file.<br />Ex: http://yoursite.com/uploads/image.jpg', 'jumpstart'),
			        'video' 	=> __('Enter a URL to a YouTube or Vimeo page.<br />Ex: http://vimeo.com/11178250', 'jumpstart').'<br>'.__('Ex: https://youtube.com/watch?v=ginTCwWfGNY', 'jumpstart')
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
			'add' 					=> __('Add Images','jumpstart'),
			'delete' 				=> __('Remove Image','jumpstart'),
			'delete_all' 			=> __('Remove All Images','jumpstart'),
			'delete_all_confirm' 	=> __('Are you sure you want to remove all images?','jumpstart'),
			'modal_title'			=> __('Select Images','jumpstart'),
			'modal_button'			=> __('Add Images','jumpstart')
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
				'name'		=> __('Partner Name', 'jumpstart'),
				'desc'		=> __('Enter a name that corresponds to this logo.', 'jumpstart'),
				'type'		=> 'text',
				'std'		=> ''
			),
			/*
			array(
				'id' 		=> 'desc',
				'name'		=> __('Partner Description (optional)', 'jumpstart'),
				'desc'		=> __('Enter very brief description that will display as a tooltip when the user hovers on the logo.', 'jumpstart'),
				'type'		=> 'textarea',
				'std'		=> ''
			),
			*/
			array(
				'id' 		=> 'link',
				'name'		=> __('Partner Link (optional)', 'jumpstart'),
				'desc'		=> __('Enter a URL you\'d like this logo to link to.', 'jumpstart').'<br>'.__('Ex: http://partersite.com', 'jumpstart'),
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
			'add' 					=> __('Add Logos','jumpstart'),
			'delete' 				=> __('Remove Logo','jumpstart'),
			'delete_all' 			=> __('Remove All Logos','jumpstart'),
			'delete_all_confirm' 	=> __('Are you sure you want to remove all logos?','jumpstart'),
			'modal_title'			=> __('Select Logos','jumpstart'),
			'modal_button'			=> __('Add Logos','jumpstart')
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
				'name'		=> __('Highlight Color', 'jumpstart'),
				'desc'		=> __('If you wish, you can give the column a highlight color.', 'jumpstart'),
				'type'		=> 'select',
				'std'		=> 'none',
				'options'	=> themeblvd_colors(true, false) // Include bootstrap, don't include "Custom Color" option
			),
			array(
				'id' 		=> 'title',
				'name'		=> __('Title', 'jumpstart'),
				'desc'		=> __('Enter a title for this column.', 'jumpstart').'<br>'.__('Ex: Gold Package', 'jumpstart'),
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
				'desc'		=> __('Pop out column so it stands out from the rest.', 'jumpstart'),
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			array(
				'id' 		=> 'title_subline',
				'name'		=> __('Popout Title Subline', 'jumpstart'),
				'desc'		=> __('Because the column is popped out, enter a very brief subline for the title.', 'jumpstart').'<br>'.__('Ex: Best Value', 'jumpstart'),
				'type'		=> 'text',
				'std'		=> 'Best Value',
				'class'		=> 'hide receiver'
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
			array(
				'id' 		=> 'price',
				'name'		=> __('Price', 'jumpstart'),
				'desc'		=> __('Enter a value for the price, without the currency symbol.', 'jumpstart').'<br>'.__('Ex: 50', 'jumpstart'),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'id' 		=> 'price_subline',
				'name'		=> __('Price Subline (optional)', 'jumpstart'),
				'desc'		=> __('Enter a very brief subline for the price to show what it represents.', 'jumpstart').'<br>'.__('Ex: per month', 'jumpstart'),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'id' 		=> 'features',
				'name'		=> __('Feature List', 'jumpstart'),
				'desc'		=> __('Enter each feature, seprated by a line break. If you like, spice it up with some icons.', 'jumpstart').'<br><br>[vector_icon icon="check" color="#00aa00"]<br>[vector_icon icon="times" color="#aa0000"]',
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
				'name'		=> __('Button', 'jumpstart'),
				'desc'		=> __('Show button below feature list?', 'jumpstart'),
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'hide receiver show-hide-toggle'
		    ),
			array(
				'id' 		=> 'button_color',
				'name'		=> __('Button Color', 'jumpstart'),
				'desc'		=> __('Select what color you\'d like to use for this button.', 'jumpstart'),
				'type'		=> 'select',
				'class'		=> 'trigger',
				'options'	=> themeblvd_colors()
			),
			array(
				'id' 		=> 'button_custom',
				'name'		=> __('Custom Button Color', 'jumpstart'),
				'desc'		=> __('Configure a custom style for the button.', 'jumpstart'),
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
				'name'		=> __('Button Text', 'jumpstart'),
				'desc'		=> __('Enter the text for the button.', 'jumpstart'),
				'std'		=> 'Purchase Now',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'button_url',
				'name'		=> __('Link URL', 'jumpstart'),
				'desc'		=> __('Enter the full URL where you want the button\'s link to go.', 'jumpstart'),
				'std'		=> 'http://www.your-site.com/your-landing-page',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'button_size',
				'name'		=> __('Button Size', 'jumpstart'),
				'desc'		=> __('Select the size you\'d like used for this button.', 'jumpstart'),
				'type'		=> 'select',
				'std'		=> 'default',
				'class'		=> 'hide receiver',
				'options'	=> array(
					'mini' 		=> __('Mini', 'jumpstart'),
					'small' 	=> __('Small', 'jumpstart'),
					'default' 	=> __('Normal', 'jumpstart'),
					'large' 	=> __('Large', 'jumpstart'),
					'x-large' 	=> __('Extra Large', 'jumpstart')
				)
			),
			array(
				'id' 		=> 'button_icon_before',
				'name'		=> __('Icon Before Button Text (optional)', 'jumpstart'),
				'desc'		=> __('Icon before text of button. This can be any FontAwesome vector icon ID.', 'jumpstart'),
				'type'		=> 'text',
				'icon'		=> 'vector',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'button_icon_after',
				'name'		=> __('Icon After Button Text (optional)', 'jumpstart'),
				'desc'		=> __('Icon after text of button. This can be any FontAwesome vector icon ID.', 'jumpstart'),
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
			'add' 					=> __('Add Pricing Table Column','jumpstart'),
			'delete' 				=> __('Remove Column','jumpstart'),
			'delete_confirm' 		=> __('Are you sure you want to delete this column?','jumpstart'),
			'delete_all' 			=> __('Remove All Columns','jumpstart'),
			'delete_all_confirm' 	=> __('Are you sure you want to remove all columns?','jumpstart')
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
				'name'		=> __('Social Icon', 'jumpstart'),
				'type'		=> 'select',
				'std'		=> 'facebook',
				'options'	=> themeblvd_get_social_media_sources(),
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'url',
				'name'		=> __('Link URL', 'jumpstart'),
				'type'		=> 'text',
				'std'		=> 'http://'
			),
			array(
				'id' 		=> 'label',
				'name'		=> __('Label', 'jumpstart'),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'id' 		=> 'target',
				'name'		=> __('Link Target', 'jumpstart'),
				'type'		=> 'select',
				'std'		=> '_blank',
				'options'	=> array(
					'_blank'	=> __('New Window', 'jumpstart'),
					'_self' 	=> __('Same Window', 'jumpstart')
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
			'add' 					=> __('Add Icon','jumpstart'),
			'delete' 				=> __('Delete Icon','jumpstart'),
			'delete_confirm'		=> __('Are you sure you want to delete this icon?', 'jumpstart'),
			'delete_all' 			=> __('Delete All Icons','jumpstart'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all icons?','jumpstart')
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
				'name'		=> __('Social Icon', 'jumpstart'),
				'type'		=> 'select',
				'std'		=> 'facebook',
				'options'	=> themeblvd_get_share_sources(),
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'label',
				'name'		=> __('Label', 'jumpstart'),
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
			'add' 					=> __('Add Share Icon','jumpstart'),
			'delete' 				=> __('Delete Share Icon','jumpstart'),
			'delete_confirm'		=> __('Are you sure you want to delete this share icon?', 'jumpstart'),
			'delete_all' 			=> __('Delete All Share Icons','jumpstart'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all share icons?','jumpstart')
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
				'name'		=> __('Tab Title', 'jumpstart'),
				'desc'		=> __('Enter a short title to represent this tab.', 'jumpstart'),
				'type'		=> 'text',
				'std'		=> 'Tab Title',
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'content',
				'name'		=> __('Tab Content', 'jumpstart'),
				'desc'		=> __('Configure the content of the tab. Try not to make the content too complex, as it is possible that not all shortcodes and HTML will work as expected within a set of tabs.', 'jumpstart'),
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
			'add' 					=> __('Add Tab','jumpstart'),
			'delete' 				=> __('Delete Tab','jumpstart'),
			'delete_confirm'		=> __('Are you sure you want to delete this tab?', 'jumpstart'),
			'delete_all' 			=> __('Delete All Tabs','jumpstart'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all tabs?','jumpstart')
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
				'name' 		=> __('Testimonial Text', 'jumpstart'),
				'desc'		=> __('Enter any text of the testimonial.', 'jumpstart'),
				'type'		=> 'textarea',
				'editor'	=> true,
				'code'		=> 'html'
		    ),
			'name' => array(
				'id' 		=> 'name',
				'name' 		=> __('Name', 'jumpstart'),
				'desc'		=> __('Enter the name of the person giving the testimonial.', 'jumpstart'),
				'type'		=> 'text',
				'trigger'	=> true // Triggers this option's value to be used in toggle
		    ),
		    'tagline' => array(
				'id' 		=> 'tagline',
				'name' 		=> __('Tagline (optional)', 'jumpstart'),
				'desc'		=> __('Enter a tagline for the person giving the testimonial.', 'jumpstart').'<br>'.__('Ex: Founder and CEO', 'jumpstart'),
				'type'		=> 'text'
		    ),
		    'company' => array(
				'id' 		=> 'company',
				'name' 		=> __('Company (optional)', 'jumpstart'),
				'desc'		=> __('Enter the company the person giving the testimonial belongs to.', 'jumpstart'),
				'type'		=> 'text'
		    ),
		    'company_url' => array(
				'id' 		=> 'company_url',
				'name' 		=> __('Company URL (optional)', 'jumpstart'),
				'desc'		=> __('Enter the website URL for the company or the person giving the testimonial.', 'jumpstart'),
				'type'		=> 'text',
				'pholder'	=> 'http://'
		    ),
		    'image' => array(
				'id' 		=> 'image',
				'name' 		=> __('Image (optional)', 'jumpstart'),
				'desc'		=> __('Select a small image for the person giving the testimonial.  This will look best if you select an image size that is square.', 'jumpstart'),
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
			'add' 					=> __('Add Testimonial','jumpstart'),
			'delete' 				=> __('Delete Testimonial','jumpstart'),
			'delete_confirm'		=> __('Are you sure you want to delete this testimonial?', 'jumpstart'),
			'delete_all' 			=> __('Delete All Testimonials','jumpstart'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all testimonials?','jumpstart')
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
				'name'		=> __('Title', 'jumpstart'),
				'desc'		=> __('Enter a short title to represent this toggle.', 'jumpstart'),
				'type'		=> 'text',
				'std'		=> 'Toggle Title',
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'content',
				'name'		=> __('Content', 'jumpstart'),
				'desc'		=> __('Configure the content of the toggle. Try not to make the content too complex, as it is possible that not all shortcodes and HTML will work as expected within toggle which is initially hidden.', 'jumpstart'),
				'type'		=> 'textarea',
				'editor'	=> true,
				'code'		=> 'html'
			),
			array(
				'id' 		=> 'wpautop',
				'name'		=> __('Content Formatting', 'jumpstart'),
				'desc'		=> __('Apply WordPress automatic formatting.', 'jumpstart'),
				'type'		=> 'checkbox',
				'std'		=> '1'
			),
			array(
				'id' 		=> 'open',
				'name'		=> __('Initial State', 'jumpstart'),
				'desc'		=> __('Toggle is open when the page intially loads.', 'jumpstart'),
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
			'add' 					=> __('Add Toggle','jumpstart'),
			'delete' 				=> __('Delete Toggle','jumpstart'),
			'delete_confirm'		=> __('Are you sure you want to delete this tab?', 'jumpstart'),
			'delete_all' 			=> __('Delete All Toggles','jumpstart'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all toggles?','jumpstart')
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
				'name'		=> __('Text', 'jumpstart'),
				'desc'		=> __('Enter the text to display in this text block.', 'jumpstart'),
				'type'		=> 'textarea',
				'std'		=> '',
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'size',
				'name'		=> __('Text Size', 'jumpstart'),
				'desc'		=> __('Set the size of the text, relative to the site\'s main font size.', 'jumpstart'),
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
				'name'		=> __('Text Color', 'jumpstart'),
				'desc'		=> __('Select the color of the text.', 'jumpstart'),
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
				'desc'		=> __('Apply background color around text block.', 'jumpstart'),
				'std'		=> '0',
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			array(
				'id' 		=> 'bg_color',
				'name'		=> __('Background Color', 'jumpstart'),
				'desc'		=> __('Select the color of the background around the text.', 'jumpstart'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			array(
				'id'		=> 'bg_opacity',
				'name'		=> __('Background Color Opacity', 'jumpstart'),
				'desc'		=> __('Select the opacity of the background color. Selecting "100%" means that the background color is not transparent, at all.', 'jumpstart'),
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
				'desc'		=> __('Use header font.', 'jumpstart'),
				'type'		=> 'checkbox',
				'std'		=> '0'
			),
			array(
				'id' 		=> 'italic',
				'name'		=> null,
				'desc'		=> __('Italicize the text.', 'jumpstart'),
				'type'		=> 'checkbox',
				'std'		=> '0'
			),
			array(
				'id' 		=> 'caps',
				'name'		=> null,
				'desc'		=> __('Display text in all caps.', 'jumpstart'),
				'type'		=> 'checkbox',
				'std'		=> '0'
			),
			array(
				'id' 		=> 'suck_down',
				'name'		=> null,
				'desc'		=> __('Reduce space below text block.', 'jumpstart'),
				'type'		=> 'checkbox',
				'std'		=> '0'
			),
			array(
				'id' 		=> 'wpautop',
				'name'		=> null,
				'desc'		=> __('Apply WordPress automatic formatting.', 'jumpstart'),
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
			'add' 					=> __('Add Text Block','jumpstart'),
			'delete' 				=> __('Delete Text Block','jumpstart'),
			'delete_confirm'		=> __('Are you sure you want to delete this text block?', 'jumpstart'),
			'delete_all' 			=> __('Delete All Text Blocks','jumpstart'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all text blocks?','jumpstart')
		);
		return $labels;
	}

}
