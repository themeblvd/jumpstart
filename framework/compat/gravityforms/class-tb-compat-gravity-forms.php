<?php
/**
 * Add extended Gravity Forms compatibility
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_Compat_Gravity_Forms {

	/**
	 * A single instance of this class.
	 *
	 * @since 2.5.0
	 */
	private static $instance = null;

	/**
     * Creates or returns an instance of this class.
     *
     * @since 2.5.0
     *
     * @return Theme_Blvd_Compat_bbPress A single instance of this class.
     */
	public static function get_instance() {

		if ( self::$instance == null ) {
            self::$instance = new self;
        }

        return self::$instance;
	}

	/**
	 * Constructor. Hook everything in.
	 *
	 * @since 2.5.0
	 */
	public function __construct() {

		add_action( 'wp_enqueue_scripts', array($this, 'assets'), 15 ); // Gravity Forms is at priority 11
		add_filter( 'body_class', array($this, 'body_class') );

		add_filter( 'gform_validation_message', array($this, 'error') );
		add_filter( 'gform_confirmation', array($this, 'confirm') );

	}

	/**
	 * Add CSS
	 *
	 * @since 2.5.0
	 */
	public function assets( $type ) {

		$handler = Theme_Blvd_Stylesheet_Handler::get_instance();
		$deps = $handler->get_framework_deps();

		wp_enqueue_style( 'themeblvd-gravityforms', esc_url( TB_FRAMEWORK_URI . '/compat/gravityforms/gravityforms.min.css' ), $deps, TB_FRAMEWORK_VERSION );
	}

	/**
	 * Add "tb-gforms-compat" class to <body>
	 *
	 * It's unpredicatable where Gravity Forms is going to output
	 * the CSS files, as they do it dynamically depending on if a form
	 * exists on the page, and then inserts to the wp_footer().
	 * This body class allows us to style everything one more class
	 * specifically to make sure we always override.
	 *
	 * @since 2.5.0
	 */
	public function body_class( $class ) {
		$class[] = 'tb-gforms-compat';
		return $class;
	}

	/**
	 * Filter validation error message to use our framework's
	 * alert styling.
	 *
	 * @since 2.5.0
	 */
	public function error( $msg ) {
		return str_replace('validation_error', 'tb-alert alert alert-danger', $msg);
	}

	/**
	 * Filter confirmation message to use our framework's
	 * alert styling.
	 *
	 * @since 2.5.0
	 */
	public function confirm( $msg ) {
		return str_replace('gform_confirmation_message', 'tb-alert alert alert-success', $msg);
	}

}
