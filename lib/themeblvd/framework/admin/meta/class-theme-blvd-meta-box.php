<?php
/**
 * Meta Box Framework
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     @@name-package
 */

/**
 * Theme Blvd Meta Box
 *
 * This is a re-usable class for creating meta boxes.
 * It utilizes both the framework's option system and
 * WordPress's add_meta_box functionality.
 *
 * @since @@name-framework 2.3.0
 *
 * @see themeblvd_add_meta_boxes()
 *
 * @param string $id Unique ID for meta box.
 * @param array  $args {
 *     Setup array for meta box.
 *
 *     @type string $title      Title for meta box.
 *     @type array  $screen     Post type edit screens meta box will show on.
 *     @type string $context    Contex parameter passed to add_meta_box().
 *     @type string $priority   Priority parameter passed to add_meta_box().
 *     @type bool   $save_empty Optional. Whether to save empty values to database.
 * }
 * @param array  $options {
 *     Standard farmework array of options.
 *     @link http://dev.themeblvd.com/tutorial/formatting-options/
 * }
 */
class Theme_Blvd_Meta_Box {

	/**
	 * Arguments to pass to add_meta_box().
	 *
	 * @since @@name-framework 2.3.0
	 * @var array
	 */
	private $args;

	/**
	 * Options for meta box.
	 *
	 * @since @@name-framework 2.3.0
	 * @var array
	 */
	private $options;

	/**
	 * Constructor. Hook in meta box to start the process.
	 *
	 * @since @@name-framework 2.3.0
	 *
	 * @param string $id      Unique ID for meta box.
	 * @param array  $args    Setup array for meta box (see class docs).
	 * @param array  $options Options for meta box (see class docs).
	 */
	public function __construct( $id, $args, $options ) {

		$this->id = $id;

		$this->options = $options;

		if ( ! $this->options ) {
			return;
		}

		$this->args = wp_parse_args( $args, array(
			'screen'     => array( 'post' ),
			'context'    => 'normal',
			'priority'   => 'high',
			'save_empty' => true,
			'textures'   => false,
		));

		/*
		 * Backwards compat for older arguments.
		 *
		 * Prior to @@name-framework 2.7.0, $this->args['screen']
		 * was named $this->args['page']; so we'll just make sure
		 * that continues to work, if anyone's using it.
		 */
		if ( isset( $this->args['page'] ) ) {

			$this->args['screen'] = $this->args['page'];

			unset( $this->args['page'] );

		}

		/*
		 * Add any extra hidden items for the page that may
		 * be utilized for extra functionality. For example,
		 * the texture browser to select a texture.
		 */
		add_action( 'current_screen', array( $this, 'helpers' ) );

		/*
		 * Add actual calls to WordPress's add_meta_box().
		 * This is done with a loop through $this->args['screen'],
		 * calling add_meta_box() for each post type.
		 */
		add_action( 'add_meta_boxes', array( $this, 'add' ) );

		/*
		 * Add the save() method to `save_post_*` for each
		 * post type, so that it's not just generally hooked
		 * to `save_post` action.
		 */
		foreach ( $this->args['screen'] as $post_type ) {

			add_action( "save_post_{$post_type}", array( $this, 'save' ) );

		}

	}

	/**
	 * Add any helper items needed to be outputted for
	 * options being used in meta box.
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function helpers() {

		$screen = get_current_screen();

		if ( $this->args['textures'] && 'post' === $screen->base && in_array( $screen->post_type, $this->args['screen'] ) ) {

			add_action( 'in_admin_header', 'themeblvd_texture_browser' );

		}
	}

	/**
	 * Call WP's add_meta_box() for each post type.
	 *
	 * @since @@name-framework 2.3.0
	 */
	public function add() {

		foreach ( $this->args['screen'] as $screen ) {

			add_meta_box(
				$this->id,
				$this->args['title'],
				array( $this, 'display' ),
				$screen,
				$this->args['context'],
				$this->args['priority']
			);

		}

	}

	/**
	 * Callback to display meta box.
	 *
	 * @since @@name-framework 2.3.0
	 */
	public function display() {

		global $post;

		/*
		 * Make sure options framework exists so we can show
    	 * the options form.
		 */
		if ( ! function_exists( 'themeblvd_option_fields' ) ) {
			echo 'Options framework not found.';
			return;
		}

		// Begin building output.
		$class = 'tb-meta-box';

		if ( 'side' === $this->args['context'] ) {
			$class .= ' side';
		}

		echo '<div id="optionsframework" class="' . $class . '">';

		wp_nonce_field(
			'themeblvd-save-meta-box_' . $this->id,
			'themeblvd-save-meta-box_' . $this->id,
			false // No need for _wp_http_referer; it already exists on Edit Post screen.
		);

		/*
		 * Gather any already saved settings or defaults for
		 * option types that need a starting value.
		 */
		$settings = array();

		if ( ! empty( $this->args['group'] ) ) {

			$settings = get_post_meta( $post->ID, $this->args['group'], true );

		} else {

			foreach ( $this->options as $option ) {

				if ( empty( $option['id'] ) ) {
					continue;
				}

				$settings[ $option['id'] ] = get_post_meta( $post->ID, $option['id'], true );

				if ( ! $settings[ $option['id'] ] ) {

					if ( 'radio' == $option['type'] || 'images' == $option['type'] || 'select' == $option['type'] ) {

						if ( isset( $option['std'] ) ) {

							$settings[ $option['id'] ] = $option['std'];

						}
					}
				}
			}
		}

		/*
		 * When a post/page is saved that has our meta box,
		 * in the cases where no data passed, this hidden
		 * option will ensure that there is always at least
		 * one entry, and thus the array exists.
		 */
		$hidden = array(
			'placeholder' => array(
				'id'   => '_tb_placeholder',
				'std'  => 1,
				'type' => 'hidden',
			),
		);

		$options = array_merge( $hidden, $this->options );

		$form = themeblvd_option_fields(
			'themeblvd_meta[' . $this->id . ']',
			$options,
			$settings
		);

		echo $form[0];

		if ( ! empty( $this->args['desc'] ) ) {

			printf(
				'<p class="tb-meta-desc">%s</p>',
				themeblvd_kses( $this->args['desc'] )
			);

		}

		echo '</div><!-- .tb-meta-box (end) -->';

	}

	/**
	 * Save meta data sent from meta box.
	 *
	 * @since @@name-framework 2.3.0
	 *
	 * @param int $post_ID ID of post being saved.
	 */
	public function save( $post_id ) {

		/*
		 * If our meta box existed on the Edit screen, this
		 * should always be set because of the hidden
		 * _tb_placeholder option inserted into every meta box.
		 */
		if ( ! isset( $_POST['themeblvd_meta'][ $this->id ] ) ) {
			return;
		}

		check_admin_referer(
			'themeblvd-save-meta-box_' . $this->id,
			'themeblvd-save-meta-box_' . $this->id
		);

		$clean = array(); // Use for grouped options only.

		$input = $_POST['themeblvd_meta'][ $this->id ];

		if ( ! empty( $input ) ) {

			foreach ( $this->options as $option ) {

				if ( empty( $option['id'] ) ) {
					continue;
				}

				$id = $option['id'];

				/*
				 * Set checkbox to false if it wasn't sent in the $_POST.
				 */
				if ( 'checkbox' === $option['type'] && ! isset( $input[ $id ] ) ) {

					if ( ! empty( $option['inactive'] ) && 'true' === $option['inactive'] ) {

						$input[ $id ] = '1';

					} else {

						$input[ $id ] = '0';
					}
				}

				/*
				 * For button option type, set checkbox to false if it wasn't
				 * sent in the $_POST.
				 */
				if ( 'button' === $option['type'] ) {

					if ( ! isset( $input[ $id ]['include_bg'] ) ) {

						$input[ $id ]['include_bg'] = '0';

					}

					if ( ! isset( $input[ $id ]['include_border'] ) ) {

						$input[ $id ]['include_border'] = '0';

					}
				}

				/*
				 * Make sure at least an empty array gets sent to sanitization
				 * if no items were checked. Sanitization will result in a `0`
				 * value set for each unchecked box.
				 */
				if ( 'multicheck' === $option['type'] && ! isset( $input[ $id ] ) ) {

					$input[ $id ] = array();

				}

				if ( ! $this->args['save_empty'] && empty( $input[ $id ] ) ) {

					delete_post_meta( $post_id, $id );

				} elseif ( isset( $input[ $id ] ) ) {

					/** This filter is documented in framework/admin/options/class-theme-blvd-options-page.php */
					$input[ $id ] = apply_filters( 'themeblvd_sanitize_' . $option['type'], $input[ $id ], $option );

					if ( ! empty( $this->args['group'] ) ) {

						$clean[ $id ] = $input[ $id ];

					} else {

						update_post_meta( $post_id, $id, $input[ $id ] );

					}
				}
			}

			if ( ! empty( $this->args['group'] ) ) {

				update_post_meta( $post_id, $this->args['group'], $clean );

			}
		}
	}
}
