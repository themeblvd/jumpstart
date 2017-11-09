<?php
/**
 * Plugin Compatibility: Gravity Forms
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.5.0
 */

/**
 * Add extended Gravity Forms compatibility.
 *
 * This class follows the singleton pattern,
 * meaning it can only be instantiated in
 * one instance.
 *
 * @since Theme_Blvd 2.5.0
 */
class Theme_Blvd_Compat_Gravity_Forms {

	/**
	 * A single instance of this class.
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	private static $instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @return Theme_Blvd_Compat_Gravity_Forms A single instance of this class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {

			self::$instance = new self;

		}

		return self::$instance;

	}

	/**
	 * Constructor. Hook everything in.
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ), 15 ); // Gravity Forms is at priority 11.

		add_filter( 'body_class', array( $this, 'body_class' ) );

		add_filter( 'gform_validation_message', array( $this, 'error' ) );

		add_filter( 'gform_confirmation', array( $this, 'confirm' ) );

	}

	/**
	 * Add custom styling for Gravity Forms.
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	public function assets( $type ) {

		if ( themeblvd_get_option( 'gforms_styles' ) ) {

			$handler = Theme_Blvd_Stylesheet_Handler::get_instance();

			$deps = $handler->get_framework_deps();

			$suffix = SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style(
				'themeblvd-gravityforms',
				esc_url( TB_FRAMEWORK_URI . "/compat/assets/css/gravityforms{$suffix}.css" ),
				$deps,
				TB_FRAMEWORK_VERSION
			);

		}

	}

	/**
	 * Add `tb-gforms-compat` class to <body>, to
	 * help with custom styling when Gravity Form's
	 * stylesheet preceed our's.
	 *
	 * If a form is present within the standard content
	 * of a page, Gravity Froms will generally output
	 * all stylesheets properly in the <head>.
	 *
	 * However, when forms are present outside of the
	 * loop, Gravity Forms may print stylesheets in the
	 * footer of the website. In these cases, our body
	 * class can help us to write overriding styles.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param  array $class Current CSS classes on <body>.
	 * @return array $class Modified CSS classes on <body>.
	 */
	public function body_class( $class ) {

		if ( themeblvd_get_option( 'gforms_styles' ) ) {

			$class[] = 'tb-gforms-compat';

		}

		return $class;

	}

	/**
	 * Filter validation error message to use our
	 * framework's alert styling.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param  string $message Original validation message.
	 * @return string $message Modified validation message.
	 */
	public function error( $message ) {

		$message = str_replace(
			'validation_error',
			'tb-alert alert alert-danger',
			$message
		);

		return $message;

	}

	/**
	 * Filter confirmation message to use our
	 * framework's alert styling.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param  string $message Original validation message.
	 * @return string $message Modified validation message.
	 */
	public function confirm( $msg ) {

		$message = str_replace(
			'gform_confirmation_message',
			'tb-alert alert alert-success',
			$msg
		);

		return $message;

	}

}
