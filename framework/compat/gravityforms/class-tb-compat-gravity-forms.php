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

		add_action( 'wp_enqueue_scripts', array($this, 'assets'), 15 );
		add_filter( 'themeblvd_framework_stylesheets', array($this, 'add_style') );

		add_filter( 'gform_validation_message', array($this, 'error') );
		add_filter( 'gform_confirmation', array($this, 'confirm') );

	}

	/**
	 * Add CSS
	 *
	 * @since 2.5.0
	 */
	public function assets( $type ) {

		$api = Theme_Blvd_Stylesheets_API::get_instance();
		$deps = $api->get_framework_deps();

		wp_enqueue_style( 'themeblvd-gravityforms', TB_FRAMEWORK_URI.'/compat/gravityforms/gravityforms.min.css', $deps, TB_FRAMEWORK_VERSION );
	}

	/**
	 * Add our stylesheet to framework $deps. This will make
	 * sure our wpml.css file comes between framework
	 * styles and child theme's style.css
	 *
	 * @since 2.5.0
	 */
	public function add_style( $deps ) {
		$deps[] = 'gforms_formsmain_css';
		$deps[] = 'gforms_ready_class_css';
		return $deps;
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
