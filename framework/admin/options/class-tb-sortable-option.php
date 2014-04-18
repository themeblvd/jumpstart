<?php
/**
 * Theme Blvd Sortable Option.
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
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
	 * Text strings for managing items.
	 *
	 * @since 2.5.0
	 * @var array
	 */
	private $labels = array();

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
	 * Current advanced option type. Set by child class.
	 *
	 * @since 2.5.0
	 * @var string
	 */
	protected $type = '';

	/*--------------------------------------------*/
	/* Constructor
	/*--------------------------------------------*/

	/**
	 * Constructor.
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		// Setup labels
		$this->labels = array(
			'add' 				=> __('Add Item', 'themeblvd'),
			'delete'			=> __('Delete Item', 'themeblvd'),
			'delete_confirm'	=> __('Are you sure you want to delete this item?', 'themeblvd')
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

		// Make sure there's no duplicate AJAX actions added
		remove_all_actions( 'wp_ajax_themeblvd_add_'.$this->type.'_item' );

		// Add item with AJAX - Use: themeblvd_add_{$type}_item
		add_action( 'wp_ajax_themeblvd_add_'.$this->type.'_item', array( $this, 'add_item' ) );
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

		$output  = sprintf('<div class="tb-sortable-option" data-security="%s" data-name="%s" data-id="%s" data-type="%s">', $ajax_nonce, $option_name, $option_id, $this->type );

		// Start sortable section
		$output .= '<div class="item-container">';

		if ( is_array( $items ) && count( $items ) > 0 ) {
			foreach ( $items as $item_id => $item ) {
				$output .= $this->get_item( $option_id, $item_id, $item, $option_name );
			}
		}

		$output .= '</div><!-- .item-container (end) -->';

		// Footer and button to add items
		$output .= sprintf( '<footer><a href="#" class="add-item button-secondary">%s</a></footer>', $this->labels['add'] );

		$output .= '</div><!-- .tb-sortable-option (end) -->';

		return $output;
	}

	/**
	 * Individual sortable item.
	 *
	 * @since 2.5.0
	 */
	public function get_item( $option_id, $item_id, $item, $option_name ) {

		$item_output  = sprintf( '<div id="%s" class="widget item">', $item_id );

		// $item .= $this->get_handle();
		$item_output .= '<div class="item-handle closed">';
		$item_output .= '<h3>&nbsp;</h3>'; // ... @TODO What about image type? Make method like get_handle() that can be overriden from child class?
		$item_output .= '<span class="tb-icon-sort"></span>';
		$item_output .= '<a href="#" class="toggle"><span class="tb-icon-up-dir"></span></a>';
		$item_output .= '</div>';

		$item_output .= '<div class="item-content">';

		foreach ( $this->options as $option ) {

			$item_output .= sprintf( '<div class="section section-%s">', $option['type'] );

			$item_output .= sprintf( '<h4>%s</h4>', $option['name'] );

			$current = '';
			if ( isset( $item[$option['id']] ) ) {
				$current = $item[$option['id']];
			}

			switch ( $option['type'] ) {

				// Text input
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

				// Textarea
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

				case 'select':

					$item_output .= '<div class="tb-fancy-select">';

					$class = 'of-input';
					if ( $this->trigger == $option['id'] ) {
						$class .= ' handle-trigger';
					}

					$item_output .= sprintf( '<select class="%s" name="%s" id="%s">', $class, esc_attr( $option_name.'['.$option_id.']['.$item_id.']['.$option['id'].']' ), esc_attr($option['id']) );

					foreach ( $option['options'] as $key => $option ) {
						$item_output .= sprintf( '<option%s value="%s">%s</option>', selected( $key, $current, false ), esc_attr( $key ), esc_html($option) );
					}

					$item_output .= '</select>';
					$item_output .= '<span class="trigger"></span>';
					$item_output .= '<span class="textbox"></span>';
					$item_output .= '</div><!-- .tb-fancy-select (end) -->';

					break;
			}

			$item_output .= '</div><!-- .section (end) -->';
		}

		// Delete item
		$item_output .= '<div class="section">';
		$item_output .= sprintf( '<a href="#%s" class="delete-me" title="%s">%s</a>', $item_id, $this->labels['delete_confirm'], $this->labels['delete'] );
		$item_output .= '</div>';

		$item_output .= '</div><!-- .item-content (end) -->';
		$item_output .= '</div>';

		return $item_output;
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
 * Social Media buttons option type
 */
class Theme_Blvd_Social_Option extends Theme_Blvd_Sortable_Option {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Set type
		$this->type = 'social_media';

		// Run parent
		parent::__construct();

	}

	/**
	 * Get options
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
			)
		);
		return $options;
	}

	/**
	 * Get labels
	 */
	public function get_labels() {
		$labels = array(
			'add' 		=> __('Add Icon','themeblvd'),
			'delete' 	=> __('Delete Icon','themeblvd')
		);
		return $labels;
	}

}