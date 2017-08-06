<?php
/**
 * Theme Blvd Meta Box. Adds meta boxes through
 * WP's built-in add_meta_box functionality.
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_Meta_Box {

	/**
	 * Arguments to pass to add_meta_box().
	 *
	 * @since 2.3.0
	 * @var array
	 */
	private $args;

	/**
	 * Options for meta box.
	 *
	 * @since 2.3.0
	 * @var array
	 */
	private $options;

	/**
	 * Constructor. Hook in meta box to start the process.
	 *
	 * @since 2.3.0
	 *
	 * @param array $args Setup array for meta box
	 * @param array $options Settings for meta box
	 */
	public function __construct( $id, $args, $options ) {

		$this->id = $id;
		$this->options = $options;

		if ( ! $this->options ) {
			return;
		}

		$defaults = array(
			'page'			=> array('post'),		// can contain post, page, link, or custom post type's slug
			'context'		=> 'normal',			// normal, advanced, or side
			'priority'		=> 'high',
			'save_empty'	=> true, 				// Save empty custom fields?
			'textures'		=> false				// Include texture browser?
		);
		$this->args = wp_parse_args( $args, $defaults );

		add_action( 'current_screen', array( $this, 'helpers' ) );
		add_action( 'add_meta_boxes', array( $this, 'add' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Add any helper items needed to be outputted for
	 * options being used in meta box.
	 *
	 * @since 2.5.0
	 */
	public function helpers() {

		$page = get_current_screen();

		if ( $this->args['textures'] && $page->base == 'post' && in_array($page->post_type, $this->args['page']) ) {
			add_action( 'in_admin_header', 'themeblvd_texture_browser' );
		}
	}

	/**
	 * Call WP's add_meta_box() for each post type.
	 *
	 * @since 2.3.0
	 */
	public function add() {
		foreach ( $this->args['page'] as $page ) {
    		add_meta_box(
		        $this->id,
				$this->args['title'],
				array( $this, 'display' ),
				$page,
				$this->args['context'],
				$this->args['priority']
		    );
    	}
	}

	/**
	 * Callback to display meta box.
	 *
	 * @since 2.3.0
	 */
	public function display() {

		global $post;

    	// Make sure options framework exists so we can show
    	// the options form.
    	if ( ! function_exists( 'themeblvd_option_fields' ) ) {
    		echo 'Options framework not found.';
    		return;
    	}

    	$class = 'tb-meta-box';

    	if ( $this->args['context'] == 'side' ) {
    		$class .= ' side';
    	}

    	// Start content
    	echo '<div id="optionsframework" class="'.$class.'">';

    	// Gather any already saved settings or defaults for option types
    	// that need a starting value
    	$settings = array();

    	if ( ! empty($this->args['group']) ) {

    		$settings = get_post_meta( $post->ID, $this->args['group'], true );

    	} else {

	    	foreach ( $this->options as $option ) {

	    		if ( empty($option['id']) ) {
	    			continue;
	    		}

	    		$settings[$option['id']] = get_post_meta( $post->ID, $option['id'], true );

	    		if ( ! $settings[$option['id']] ) {
	    			if ( 'radio' == $option['type'] || 'images' == $option['type'] || 'select' == $option['type'] ) {
	    				if ( isset( $option['std'] ) ) {
	    					$settings[$option['id']] = $option['std'];
	    				}
	    			}
	    		}
	    	}

	    }

    	// Add hidden form trigger for save()
    	$hidden = array(
			'placeholder' => array(
				'id' 	=> '_tb_placeholder',
				'std'	=> 1,
				'type'	=> 'hidden'
			)
		);

		$options = array_merge( $hidden, $this->options );

    	// Use options framework to display form elements
    	$form = themeblvd_option_fields( 'themeblvd_meta['.$this->id.']', $options, $settings, false );
    	echo $form[0];

    	//  Finish content
    	if ( ! empty( $this->args['desc'] ) ) {
    		printf( '<p class="tb-meta-desc">%s</p>', themeblvd_kses($this->args['desc']) );
    	}

		echo '</div><!-- .tb-meta-box (end) -->';
	}

	/**
	 * Save meta data sent from meta box.
	 *
	 * @since 2.3.0
	 */
	public function save( $post_id ) {

		$clean = array(); // Use for grouped options only

		if ( isset( $_POST['themeblvd_meta'][$this->id] ) ) {
			$input = $_POST['themeblvd_meta'][$this->id];
		}

		if ( ! empty( $input ) ) {
			foreach ( $this->options as $option ) {

				if ( empty( $option['id'] ) ) {
					continue;
				}

				$id = $option['id'];

				// Set checkbox to false if it wasn't sent in the $_POST
				if ( $option['type'] == 'checkbox' && ! isset( $input[$id] ) ) {
					if ( ! empty( $option['inactive'] ) && $option['inactive'] === 'true' ) {
						$input[$id] = '1';
					} else {
						$input[$id] = '0';
					}
				}

				// For button option type, set checkbox to false if it wasn't
				// sent in the $_POST
				if ( $option['type'] == 'button' ) {
					if ( ! isset( $input[$id]['include_bg'] ) ) {
						$input[$id]['include_bg'] = '0';
					}
					if ( ! isset( $input[$id]['include_border'] ) ) {
						$input[$id]['include_border'] = '0';
					}
				}

				// Set each item in the multicheck to false if it wasn't sent in the $_POST
				if ( $option['type'] == 'multicheck' && ! isset($input[$id]) && ! empty($option['options']) ) {
					foreach ( $option['options'] as $key => $value ) {
						$input[$id][$key] = '0';
					}
				}

				if ( ! $this->args['save_empty'] && empty($input[$id]) ) {

					delete_post_meta( $post_id, $id );

				} else if ( isset($input[$id]) ) {

					$input[$id] = apply_filters( 'themeblvd_sanitize_'.$option['type'], $input[$id], $option );

					if ( ! empty($this->args['group']) ) {
						$clean[$id] = $input[$id];
					} else {
						update_post_meta( $post_id, $id, $input[$id] );
					}

				}

			}

			if ( ! empty($this->args['group']) ) {
				update_post_meta( $post_id, $this->args['group'], $clean );
			}
		}
	}
}
