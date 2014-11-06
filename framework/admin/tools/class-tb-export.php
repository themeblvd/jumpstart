<?php
/**
 * Setup the basic structure for various types of
 * exporting. This is an abstract parent class,
 * meant to be extended from a child.
 *
 * This class works by hooking to admin_init and
 * checking for the variable themeblvd_export_{$object_id}
 * to then trigger the export.
 *
 * So, from the page you're doing the export, you'll need
 * to have a button that links to something like this:
 *
 * http://yoursite.com/wp-admin/admin.php?page=whatever&themeblvd_export_{$object_id}=true&security=123456
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
abstract class Theme_Blvd_Export {

	/**
	 * Force Extending class to define these methods
	 */
	abstract protected function export();

	/**
	 * The unique ID for this exporter.
	 *
	 * @since 2.5.0
	 * @var string
	 */
	public $id = '';

	/**
	 * Any arguments for object.
	 *
	 * @since 2.5.0
	 * @var array
	 */
	protected $args = array();

	/**
	 * Whether to cancel the export.
	 *
	 * @since 2.5.0
	 * @var bool
	 */
	protected $do_cancel = false;

	/**
	 * Constructor.
	 *
	 * @since 2.5.0
	 *
	 * @param string $id A unique ID for this exporter
	 */
	public function __construct( $id, $args = array() ) {

		$this->id = $id;

		$defaults = array(
			'filename' 	=> $this->id.'-options-'.date('Y-m-d').'.xml',
			'filetype'	=> 'xml',
			'base_url'	=> admin_url(),
			'cancel'	=> __('The export could not be completed.', 'themeblvd')
		);
		$this->args = wp_parse_args( $args, $defaults );

		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'cancelled' ) );
	}

	/**
	 * Initiate the export process.
	 *
	 * @since 2.5.0
	 */
	public function init() {

		// Check if export is taking place
		if ( empty( $_GET['themeblvd_export_'.$this->id] ) ) {
			return;
		}

		// Check security nonce
		if ( ! wp_verify_nonce( $_GET['security'], 'themeblvd_export_'.$this->id ) ) {
			return;
		}

		// Allow the child class to break the export.
		$this->cancel();
		if ( $this->do_cancel ) {
			wp_redirect( $this->args['base_url'].'&settings-updated=themeblvd_export_fail' ); // Use "settings-updated" so WP will override if user saves options form on next page load
			exit;
		}

		// Setup headers
		$this->headers();

		// Output info to export to file; this should be
		// handled from child class.
		$this->export();

		die();
	}

	/**
	 * Set headers.
	 *
	 * @since 2.5.0
	 */
	protected function headers() {
		header( 'Content-Description: File Transfer' );
		header( 'Content-Disposition: attachment; filename='.$this->args['filename'] );
		header( 'Content-Type: text/xml; charset='.get_option( 'blog_charset' ), true );
	}

	/**
	 * Optional cancel; extend this from child, to check
	 * if there would be a reason to break the export.
	 *
	 * @since 2.5.0
	 */
	protected function cancel() {
		// $this->do_cancel = true;
	}

	/**
	 * Show confirmation that export was cancelled.
	 *
	 * @since 2.5.0
	 */
	public function cancelled() {
		if ( ! empty( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'themeblvd_export_fail' ) {
			add_settings_error( $this->id, 'export-fail', $this->args['cancel'], 'themeblvd-error error fade' );
		}
	}

}