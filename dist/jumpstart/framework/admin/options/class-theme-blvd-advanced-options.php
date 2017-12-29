<?php
/**
 * Advanced Option Types
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.5.0
 */

/**
 * Manage advanced option types, that are not easily created
 * from directly within the framework's option system.
 *
 * This class loosely follows a basic factory design pattern,
 * to manage all of the advanced option types, and instantiate
 * their objects.
 *
 * For that purpose, it also takes on singleton pattern, to
 * only be instantiated once.
 *
 * Currently, advanced option types only conist of various
 * types of sortable options. See the Theme_Blvd_Sortable_Option
 * abstract for more documentation on sortable options.
 *
 * @since Theme_Blvd 2.5.0
 */
class Theme_Blvd_Advanced_Options {

	/**
	 * A single instance of this class.
	 *
	 * @since Theme_Blvd 2.5.0
	 * @var Theme_Blvd_Advanced_Options
	 */
	private static $instance = null;

	/**
	 * Reference of all advanced option types.
	 * These are available types, not instantiated
	 * objects.
	 *
	 * @since Theme_Blvd 2.5.0
	 * @var array
	 */
	private $reference = array();

	/**
	 * An array of all option type objects.
	 *
	 * @since Theme_Blvd 2.5.0
	 * @var array
	 */
	private $types = array();

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @return Theme_Blvd_Advanced_Options A single instance of this class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {

			self::$instance = new self;

		}

		return self::$instance;

	}

	/**
	 * Class constructor. Stores library of option
	 * types to instance.
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	private function __construct() {

		$this->reference = array(
			'bars',
			'buttons',
			'datasets',
			'locations',
			'logos',
			'price_cols',
			'sectors',
			'share',
			'slider',
			'social_media',
			'tabs',
			'text_blocks',
			'testimonials',
			'toggles',
		);

	}

	/**
	 * Create an advanced option type instance and
	 * store it.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $type Type of option.
	 */
	public function create( $type ) {

		// Do not allow duplicates.
		if ( isset( $this->types[ $type ] ) ) {
			return;
		}

		// Make sure it's a valid type.
		if ( ! $this->is_type( $type ) ) {
			return;
		}

		switch ( $type ) {

			case 'bars':
				$this->types[ $type ] = new Theme_Blvd_Bars_Option();
				break;

			case 'buttons':
				$this->types[ $type ] = new Theme_Blvd_Buttons_Option();
				break;

			case 'datasets':
				$this->types[ $type ] = new Theme_Blvd_Datasets_Option();
				break;

			case 'locations':
				$this->types[ $type ] = new Theme_Blvd_Locations_Option();
				break;

			case 'logos':
				$this->types[ $type ] = new Theme_Blvd_Logos_Option();
				break;

			case 'price_cols':
				$this->types[ $type ] = new Theme_Blvd_Price_Cols_Option();
				break;

			case 'sectors':
				$this->types[ $type ] = new Theme_Blvd_Sectors_Option();
				break;

			case 'share':
				$this->types[ $type ] = new Theme_Blvd_Share_Option();
				break;

			case 'slider':
				$this->types[ $type ] = new Theme_Blvd_Slider_Option();
				break;

			case 'social_media':
				$this->types[ $type ] = new Theme_Blvd_Social_Option();
				break;

			case 'tabs':
				$this->types[ $type ] = new Theme_Blvd_Tabs_Option();
				break;

			case 'testimonials':
				$this->types[ $type ] = new Theme_Blvd_Testimonials_Option();
				break;

			case 'text_blocks':
				$this->types[ $type ] = new Theme_Blvd_Text_Blocks_Option();
				break;

			case 'toggles':
				$this->types[ $type ] = new Theme_Blvd_Toggles_Option();
				break;

		}

	}

	/**
	 * Get a stored instance of an option type object.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param  string       $type Type of option.
	 * @return Theme_Blvd_*       Instantiated object for type.
	 */
	public function get( $type ) {

		if ( ! isset( $this->types[ $type ] ) ) {
			return null;
		}

		return $this->types[ $type ];

	}

	/**
	 * Check if an option type exists.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param  string $type Type of option.
	 * @return bool         Whether option type exists.
	 */
	public function is_type( $type ) {

		return in_array( $type, $this->reference );

	}

	/**
	 * Check if an options type is one of our sortable
	 * ones.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param  string $type Type of option.
	 * @return bool         Whether option is sortable.
	 */
	public function is_sortable( $type ) {

		$sortable = array(
			'bars',
			'buttons',
			'datasets',
			'locations',
			'logos',
			'price_cols',
			'sectors',
			'share',
			'slider',
			'social_media',
			'tabs',
			'testimonials',
			'text_blocks',
			'toggles',
		);

		return in_array( $type, $sortable );

	}

}
