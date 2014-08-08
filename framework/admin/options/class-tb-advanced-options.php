<?php
/**
 * Theme Blvd Advanced Options. For advanced
 * option types, this class acts as a factory
 * to create these objects, as needed, and
 * store their instances.
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_Advanced_Options {

	/*--------------------------------------------*/
	/* Properties, private
	/*--------------------------------------------*/

	/**
	 * A single instance of this class.
	 *
	 * @since 2.5.0
	 */
	private static $instance = null;

	/**
	 * Reference of all advanced option types.
	 * These are available types, not instantiated
	 * objects.
	 *
	 * @since 2.5.0
	 * @var array
	 */
	private $reference = array();

	/**
	 * An array of all option type objects.
	 *
	 * @since 2.5.0
	 */
	private $types = array();

	/*--------------------------------------------*/
	/* Constructor
	/*--------------------------------------------*/

	/**
     * Creates or returns an instance of this class.
     *
     * @since 2.5.0
     *
     * @return Theme_Blvd_Options_API A single instance of this class.
     */
	public static function get_instance() {

		if ( self::$instance == null ) {
            self::$instance = new self;
        }

        return self::$instance;
	}

	/**
	 * Constructor. Hook everything in and setup API.
	 *
	 * @since 2.5.0
	 */
	private function __construct() {
		$this->set_reference();
	}

	/*--------------------------------------------*/
	/* Methods, mutators
	/*--------------------------------------------*/

	/**
	 * Set reference of all available option types.
	 *
	 * @since 2.5.0
	 */
	public function set_reference() {
		$this->reference = array(
			'bars',
			'buttons',
			'datasets',
			'locations',
			'logos',
			'sectors',
			'share',
			'slider',
			'social_media',
			'tabs',
			'testimonials',
			'toggles'
		);
	}

	/**
	 * Create an advanced option type instance and store it.
	 *
	 * @since 2.5.0
	 */
	public function create( $type ) {

		// Do not allow duplicates
		if ( isset( $this->types[$type] ) ) {
			return;
		}

		// Check if valid type
		if ( ! $this->is_type( $type ) ) {
			return;
		}

		switch ( $type ) {

			case 'bars':
				$this->types[$type] = new Theme_Blvd_Bars_Option();
				break;

			case 'buttons':
				$this->types[$type] = new Theme_Blvd_Buttons_Option();
				break;

			case 'datasets':
				$this->types[$type] = new Theme_Blvd_Datasets_Option();
				break;

			case 'locations':
				$this->types[$type] = new Theme_Blvd_Locations_Option();
				break;

			case 'logos':
				$this->types[$type] = new Theme_Blvd_Logos_Option();
				break;

			case 'sectors':
				$this->types[$type] = new Theme_Blvd_Sectors_Option();
				break;

			case 'share':
				$this->types[$type] = new Theme_Blvd_Share_Option();
				break;

			case 'slider':
				$this->types[$type] = new Theme_Blvd_Slider_Option();
				break;

			case 'social_media':
				$this->types[$type] = new Theme_Blvd_Social_Option();
				break;

			case 'tabs':
				$this->types[$type] = new Theme_Blvd_Tabs_Option();
				break;

			case 'testimonials':
				$this->types[$type] = new Theme_Blvd_Testimonials_Option();
				break;

			case 'toggles':
				$this->types[$type] = new Theme_Blvd_Toggles_Option();
				break;
		}

	}

	/*--------------------------------------------*/
	/* Methods, accessors
	/*--------------------------------------------*/

	/**
	 * Get a stored instance of an option type object.
	 *
	 * @since 2.5.0
	 */
	public function get( $type ) {

		if ( ! isset( $this->types[$type] ) ) {
			return null;
		}

		return $this->types[$type];
	}

	/**
	 * Check if an option type exists.
	 *
	 * @since 2.5.0
	 */
	public function is_type( $type ) {
		return in_array( $type, $this->reference );
	}

	/**
	 * Check if an options type is one of our sortable ones.
	 *
	 * @since 2.5.0
	 */
	public function is_sortable( $type ) {
		return in_array( $type, array( 'bars', 'buttons', 'datasets', 'locations', 'logos', 'sectors', 'share', 'slider', 'social_media', 'tabs', 'testimonials', 'toggles' ) );
	}

}