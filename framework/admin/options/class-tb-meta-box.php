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
	 * @param string $args Setup array for meta box
	 */
	public function __construct( $args, $options ) {
		$this->args = $args;
		$this->options = $options;
		add_action( 'add_meta_boxes', array( $this, 'add' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Call WP's add_meta_box() for each post type.
	 *
	 * @since 2.3.0
	 */
	public function add() {
		foreach ( $this->args['page'] as $page ) {
    		add_meta_box(
		        $this->args['id'],
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

    	// Start content
    	echo '<div class="tb-meta-box">';

    	// Gather any already saved settings or defaults for option types
    	// that need a starting value
    	$settings = array();
    	foreach ( $this->options as $option ) {
    		$settings[$option['id']] = get_post_meta( $post->ID, $option['id'], true );
    		if ( ! $settings[$option['id']] ) {
    			if ( 'radio' == $option['type'] || 'images' == $option['type'] || 'select' == $option['type'] ) {
    				if ( isset( $option['std'] ) )
    					$settings[$option['id']] = $option['std'];
    			}
    		}
    	}

    	// Use options framework to display form elements
    	$form = themeblvd_option_fields( 'themeblvd_meta', $this->options, $settings, false );
    	echo $form[0];

    	//  Finish content
    	if ( ! empty( $this->args['desc'] ) )
    		printf( '<p class="tb-meta-desc">%s</p>', $this->args['desc'] );

		echo '</div><!-- .tb-meta-box (end) -->';
	}

	/**
	 * Save meta data sent from meta box.
	 *
	 * @since 2.3.0
	 */
	public function save( $post_id ) {
		foreach ( $this->options as $option ) {
			if ( isset( $_POST['themeblvd_meta'][$option['id']] ) ) {
				update_post_meta( $post_id, $option['id'], strip_tags( $_POST['themeblvd_meta'][$option['id']] ) );
			}
		}
	}
}