<?php
/**
 * Jump Start Extensions
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @since      Jump_Start 2.2.2
 */

/**
 * Registers a new Jump Start extension.
 *
 * This class can be used by Jump Start extension
 * plugins to integrate into the theme's licence
 * admin page.
 *
 * @since Jump_Start 2.2.2
 */
class Jump_Start_Extension {

	/**
	 * Extension arguments.
	 *
	 * @var array
	 */
	private $args;

	/**
	 * Constructor.
	 *
	 * @since Jump_Start 2.2.2
	 *
	 * @param array $args {
	 *     Extension arguments.
	 *
	 *     @type string $file           Filepath to plugin's main file.
	 *     @type string $item_name      Plugin name, as registered from EDD store.
	 *     @type string $version        Installed plugin version.
	 *     @type string $author         Plugin author.
	 *     @type string $remote_api_url Remote URL to EDD store.
	 * }
	 */
	public function __construct( $args = array() ) {

		$args = wp_parse_args( $args, array(
			'file'           => '',
			'item_name'      => '',
			'item_shortname' => '',
			'version'        => '',
			'author'         => 'Theme Blvd',
			'remote_api_url' => 'https://wpjumpstart.com',
		) );

		if ( ! $args['file'] || ! $args['item_name'] || ! $args['version'] ) {

			return;

		}

		$this->args = $args;

		add_filter( 'jump_start_installed_extensions', array( $this, 'register' ) );

	}

	/**
	 * Registers the extension with Jump Start.
	 *
	 * @since Jump_Start 2.2.2
	 *
	 * @param  array $extensions Current extentsions.
	 * @return array             Modified extensions.
	 */
	public function register( $extensions ) {

		$file = explode( 'plugins/', $this->args['file'] );

		if ( ! empty( $file[1] ) ) {

			$file = $file[1];

		} else {

			$file = $file[0];

		}

		$this->args['file'] = $file;

		$extensions[ $this->args['item_shortname'] ] = $this->args;

		return $extensions;

	}
}
