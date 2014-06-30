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
			'add' 					=> __('Add Item', 'themeblvd'),
			'delete'				=> __('Delete Item', 'themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this item?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Items','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all items?','themeblvd')
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

		$output  = sprintf('<div class="tb-sortable-option" data-security="%s" data-name="%s" data-id="%s" data-type="%s" data-max="%s">', $ajax_nonce, $option_name, $option_id, $this->type, $this->max );

		// Header (blank by default)
		$output .= $this->get_display_header( $option_id, $option_name, $items );

		// Output blank option for the sortable items. If the user doesn't
		// setup any sortable items and then they save, this will ensure that
		// at least a blank value get saved to the option.
		$output .= sprintf( '<input type="hidden" name="%s" />', $option_name.'['.$option_id.']' );

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

		$footer .= sprintf( '<input type="button" class="add-item button-secondary" value="%s" %s />', $this->labels['add'], $disabled );

		if ( $this->max ) {
			$footer .= sprintf('<div class="max">%s: %s</div>', __('Maximum', 'themeblvd'), $this->max);
		}

		$footer .= sprintf( '<a href="#" title="%s" class="tb-tooltip-link delete-sortable-items hide" data-tooltip-text="%s"><i class="tb-icon-cancel-circled"></i></a>', $this->labels['delete_all_confirm'], $this->labels['delete_all'] );
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

			if ( isset( $option['name'] ) && $option['name'] ) {
				$item_output .= sprintf( '<h4>%s</h4>', $option['name'] );
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
					$item_output .= sprintf( '<input id="%s" class="%s" name="%s" type="hidden" value="%s" />', esc_attr( $option['id'] ), $class, esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ), stripslashes( esc_attr( $current ) ) );
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
						$item_output .= '<a href="#" class="tb-input-icon-link tb-tooltip-link" data-target="themeblvd-icon-browser-'.$option['icon'].'" data-icon-type="'.$option['icon'].'" data-tooltip-text="'.__('Browse Icons', 'themeblvd').'"><i class="tb-icon-picture"></i></a>';
					}

					$class = 'of-input';
					if ( $this->trigger == $option['id'] ) {
						$class .= ' handle-trigger';
					}

					$item_output .= sprintf( '<input id="%s" class="%s" name="%s" type="text" value="%s"%s />', esc_attr( $option['id'] ), $class, esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ), stripslashes( esc_attr( $current ) ), $place_holder );
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
							$item_output .= '<a href="#" class="tb-textarea-editor-link tb-tooltip-link" data-tooltip-text="'.__('Open in Editor', 'themeblvd').'" data-target="themeblvd-editor-modal"><i class="tb-icon-pencil"></i></a>';
						}

						if ( isset( $option['code'] ) && in_array( $option['code'], array( 'html', 'javascript', 'css' ) ) ) {
							$item_output .= '<a href="#" class="tb-textarea-code-link tb-tooltip-link" data-tooltip-text="'.__('Open in Code Editor', 'themeblvd').'" data-target="'.esc_textarea( $option['id'] ).'" data-title="'.$option['name'].'" data-code_lang="'.$option['code'].'"><i class="tb-icon-code"></i></a>';
						}

						$item_output .= '</nav>';

					} else {
						$item_output .= '<div class="textarea-wrap">';
					}

					$class = 'of-input';
					if ( $this->trigger == $option['id'] ) {
						$class .= ' handle-trigger';
					}

					$item_output .= sprintf( '<textarea id="%s" class="%s" name="%s" cols="%s" rows="8"%s>%s</textarea>', esc_textarea( $option['id'] ), $class, stripslashes( esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ) ), esc_attr( $cols ), $place_holder, esc_textarea( $current ) );
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
						$item_output .= sprintf( '<option%s value="%s">%s</option>', selected( $key, $current, false ), esc_attr( $key ), esc_html( $value ) );
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
					$item_output .= sprintf( '<input id="%s" name="%s" type="text" value="%s" class="tb-color-picker" data-default-color="%s" />', esc_attr( $option['id'] ), esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ), esc_attr( $current ), $def_color );
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
			}

			$item_output .= '</div><!-- .controls (end) -->';

			if ( ! empty( $option['desc'] ) ) {
				if ( is_array( $option['desc'] ) ) {
					foreach ( $option['desc'] as $desc_id => $desc ) {
						$item_output .= '<div class="explain hide '.$desc_id.'">';
						$item_output .= wp_kses( $desc, themeblvd_allowed_tags() );
						$item_output .= '</div>';
					}
				} else {
					$item_output .= '<div class="explain">';
					$item_output .= wp_kses( $option['desc'], themeblvd_allowed_tags() );
					$item_output .= '</div>';
				}
			}

			$item_output .= '</div><!-- .options (end) -->';

			$item_output .= '</div><!-- .section (end) -->';
		}

		// Delete item
		$item_output .= '<div class="section">';
		$item_output .= sprintf( '<a href="#%s" class="delete-sortable-item" title="%s">%s</a>', $item_id, $this->labels['delete_confirm'], $this->labels['delete'] );
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
		echo $this->get_item( $_POST['data']['option_id'], uniqid( 'item_'.rand() ), $this->get_default(), $_POST['data']['option_name'] );
		die();
	}

}

/**
 * Milestones option type
 *
 * @since 2.5.0
 */
class Theme_Blvd_Milestones_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Set type
		$this->type = 'milestones';

		// Set max items
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
				'id' 		=> 'milestone',
				'name'		=> __('Milestone Number', 'themeblvd'),
				'desc'		=> __('Enter a number for the milestone. Ex: 500', 'themeblvd'),
				'type'		=> 'text'
			),

			array(
				'id' 		=> 'before',
				'name'		=> __('Milestone Symbol/Unit (before)', 'themeblvd'),
				'desc'		=> __('Optional symbol or unit before the milestone number. Ex: $, +, etc.', 'themeblvd'),
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'after',
				'name'		=> __('Milestone Symbol/Unit (after)', 'themeblvd'),
				'desc'		=> __('Optional symbol or unit after the milestone number. Ex: +, k, etc.', 'themeblvd'),
				'type'		=> 'text'
			),
			array(
				'id' 		=> 'color',
				'name'		=> __('Milestone Color', 'themeblvd'),
				'desc'		=> __('Text color for the milestone number.', 'themeblvd'),
				'std'		=> '#0c9df0',
				'type'		=> 'color'
			),
			array(
				'id' 		=> 'text',
				'name'		=> __('Description', 'themeblvd'),
				'desc'		=> __('Enter a very simple description for the milestone number.', 'themeblvd'),
				'type'		=> 'text',
				'trigger'	=> true // Triggers this option's value to be used in toggle
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
			'add' 					=> __('Add Milestone','themeblvd'),
			'delete' 				=> __('Delete Milestone','themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this milestone?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Milestones','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all milestone?','themeblvd')
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

		// Set type
		$this->type = 'slider';

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
		$footer .= sprintf( '<a href="#" id="%s" class="add-images button-secondary" data-title="%s" data-button="%s">%s</a>', uniqid('slider_'), $this->labels['modal_title'], $this->labels['modal_button'], $this->labels['add'] );
		$footer .= sprintf( '<a href="#" title="%s" class="tb-tooltip-link delete-sortable-items hide" data-tooltip-text="%s"><i class="tb-icon-cancel-circled"></i></a>', $this->labels['delete_all_confirm'], $this->labels['delete_all'] );
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
				'id' 		=> 'alt',
				'type'		=> 'hidden',
				'std'		=> '',
				'trigger'	=> true
			),
			array(
				'id' 		=> 'crop',
				'type'		=> 'hidden',
				'std'		=> 'slider-large',
				'class'		=> 'match' // Will match with image crop selection
			),
			array(
				'id' 		=> 'thumb',
				'type'		=> 'hidden',
				'std'		=> ''
			),
			array(
				'id' 		=> 'title',
				'name'		=> __('Title (optional)', 'themeblvd'),
				'desc'		=> __('If you\'d like a headline to show on the slide, you may enter it here.', 'themeblvd'),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'id' 		=> 'desc',
				'name'		=> __('Description (optional)', 'themeblvd'),
				'desc'		=> __('If you\'d like a description to show on the slide, you may enter it here.', 'themeblvd'),
				'type'		=> 'textarea',
				'std'		=> ''
			),
			array(
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle desc-toggle'
			),
			array(
				'id' 		=> 'link',
				'name'		=> __( 'Link', 'themeblvd_builder' ),
				'desc'		=> __( 'Select if and how this image should be linked.', 'themeblvd_builder' ),
				'type'		=> 'select',
				'options'	=> array(
			        'none'		=> __( 'No Link', 'themeblvd' ),
			        '_self' 	=> __( 'Link to webpage in same window.', 'themeblvd_builder' ),
			        '_blank' 	=> __( 'Link to webpage in new window.', 'themeblvd_builder' ),
			        'image' 	=> __( 'Link to image in lightbox popup.', 'themeblvd_builder' ),
			        'video' 	=> __( 'Link to video in lightbox popup.', 'themeblvd_builder' )
				),
				'class'		=> 'trigger'
			),
			array(
				'id' 		=> 'link_url',
				'name'		=> __( 'Link URL', 'themeblvd_builder' ),
				'desc'		=> array(
			        '_self' 	=> __( 'Enter a URL to a webpage.<br />Ex: http://yoursite.com/example', 'themeblvd_builder' ),
			        '_blank' 	=> __( 'Enter a URL to a webpage.<br />Ex: http://google.com', 'themeblvd_builder' ),
			        'image' 	=> __( 'Enter a URL to an image file.<br />Ex: http://yoursite.com/uploads/image.jpg', 'themeblvd_builder' ),
			        'video' 	=> __( 'Enter a URL to a YouTube or Vimeo page.<br />Ex: http://vimeo.com/11178250‎</br />Ex: https://youtube.com/watch?v=ginTCwWfGNY', 'themeblvd_builder' )
				),
				'type'		=> 'text',
				'std'		=> '',
				'pholder'	=> 'http://',
				'class'		=> 'receiver receiver-_self receiver-_blank receiver-image receiver-video'
			),
			array(
				'type' 		=> 'subgroup_end'
			),
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
			'add' 					=> __('Add Images','themeblvd'),
			'delete' 				=> __('Remove Image','themeblvd'),
			'delete_all' 			=> __('Remove All Images','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to remove all images?','themeblvd'),
			'modal_title'			=> __('Select Images','themeblvd'),
			'modal_button'			=> __('Add Images','themeblvd')
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
			echo $this->get_item( $_POST['data']['option_id'], uniqid( 'item_'.rand() ), $val, $_POST['data']['option_name'] );
		}
		die();
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
				'name'		=> __('Social Icon', 'themeblvd'),
				'type'		=> 'select',
				'std'		=> 'facebook',
				'options'	=> themeblvd_get_social_media_sources(),
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'url',
				'name'		=> __('Link URL', 'themeblvd'),
				'type'		=> 'text',
				'std'		=> 'http://'
			),
			array(
				'id' 		=> 'label',
				'name'		=> __('Label', 'themeblvd'),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'id' 		=> 'target',
				'name'		=> __('Link Target', 'themeblvd'),
				'type'		=> 'select',
				'std'		=> '_blank',
				'options'	=> array(
					'_blank'	=> __('New Window', 'themeblvd'),
					'_self' 	=> __('Same Window', 'themeblvd')
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
			'add' 					=> __('Add Icon','themeblvd'),
			'delete' 				=> __('Delete Icon','themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this icon?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Icons','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all icons?','themeblvd')
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
				'name'		=> __('Tab Title', 'themeblvd'),
				'desc'		=> __('Enter a short title to represent this tab.', 'themeblvd'),
				'type'		=> 'text',
				'std'		=> 'Tab Title',
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'content',
				'name'		=> __('Tab Content', 'themeblvd'),
				'desc'		=> __('Configure the content of the tab. Try not to make the content too complex, as it is possible that not all shortcodes and HTML will work as expected within a set of tabs.', 'themeblvd'),
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
			'add' 					=> __('Add Tab','themeblvd'),
			'delete' 				=> __('Delete Tab','themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this tab?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Tabs','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all tabs?','themeblvd')
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
				'name' 		=> __( 'Testimonial Text', 'themeblvd_builder'),
				'desc'		=> __( 'Enter any text of the testimonial.', 'themeblvd_builder'),
				'type'		=> 'textarea',
				'editor'	=> true,
				'code'		=> 'html'
		    ),
			'name' => array(
				'id' 		=> 'name',
				'name' 		=> __( 'Name', 'themeblvd_builder'),
				'desc'		=> __( 'Enter the name of the person giving the testimonial.', 'themeblvd_builder'),
				'type'		=> 'text',
				'trigger'	=> true // Triggers this option's value to be used in toggle
		    ),
		    'tagline' => array(
				'id' 		=> 'tagline',
				'name' 		=> __( 'Tagline (optional)', 'themeblvd_builder'),
				'desc'		=> __( 'Enter a tagline for the person giving the testimonial.<br>Ex: Founder and CEO', 'themeblvd_builder'),
				'type'		=> 'text'
		    ),
		    'company' => array(
				'id' 		=> 'company',
				'name' 		=> __( 'Company (optional)', 'themeblvd_builder'),
				'desc'		=> __( 'Enter the company the person giving the testimonial belongs to.', 'themeblvd_builder'),
				'type'		=> 'text'
		    ),
		    'company_url' => array(
				'id' 		=> 'company_url',
				'name' 		=> __( 'Company URL (optional)', 'themeblvd_builder'),
				'desc'		=> __( 'Enter the website URL for the company or the person giving the testimonial.', 'themeblvd_builder'),
				'type'		=> 'text',
				'pholder'	=> 'http://'
		    ),
		    'image' => array(
				'id' 		=> 'image',
				'name' 		=> __( 'Image (optional)', 'themeblvd_builder'),
				'desc'		=> __( 'Select a small image for the person giving the testimonial.', 'themeblvd_builder'),
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
			'add' 					=> __('Add Testimonial','themeblvd'),
			'delete' 				=> __('Delete Testimonial','themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this testimonial?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Testimonials','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all testimonials?','themeblvd')
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
				'name'		=> __('Title', 'themeblvd'),
				'desc'		=> __('Enter a short title to represent this toggle.', 'themeblvd'),
				'type'		=> 'text',
				'std'		=> 'Toggle Title',
				'trigger'	=> true // Triggers this option's value to be used in toggle
			),
			array(
				'id' 		=> 'content',
				'name'		=> __('Content', 'themeblvd'),
				'desc'		=> __('Configure the content of the toggle. Try not to make the content too complex, as it is possible that not all shortcodes and HTML will work as expected within toggle which is initially hidden.', 'themeblvd'),
				'type'		=> 'textarea',
				'editor'	=> true,
				'code'		=> 'html'
			),
			array(
				'id' 		=> 'wpautop',
				'name'		=> __('Content Formatting', 'themeblvd'),
				'desc'		=> __('Apply WordPress automatic formatting.', 'themeblvd'),
				'type'		=> 'checkbox',
				'std'		=> '1'
			),
			array(
				'id' 		=> 'open',
				'name'		=> __('Initial State', 'themeblvd'),
				'desc'		=> __('Toggle is open when the page intially loads.', 'themeblvd'),
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
			'add' 					=> __('Add Toggle','themeblvd'),
			'delete' 				=> __('Delete Toggle','themeblvd'),
			'delete_confirm'		=> __('Are you sure you want to delete this tab?', 'themeblvd'),
			'delete_all' 			=> __('Delete All Toggles','themeblvd'),
			'delete_all_confirm' 	=> __('Are you sure you want to delete all toggles?','themeblvd')
		);
		return $labels;
	}

}