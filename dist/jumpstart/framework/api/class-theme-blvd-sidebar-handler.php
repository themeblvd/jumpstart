<?php
/**
 * Widget Area Handler
 *
 * The framework is made up widget area "locations"
 * and there is a default sidebar registered with
 * WordPress for each location.
 *
 * This opens up a chance for the Widget Area plugin
 * to filter in and replace locations with custom
 * sidebars created by the end-user for specific pages
 * of the website.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.3.0
 */

/**
 * Sets up widget areas.
 *
 * This handler controls setting up these locations and
 * registering the default location sidebars.
 *
 * Also, there are some handler methods to all locations
 * to be added and removed.
 *
 * @since Theme_Blvd 2.3.0
 */
class Theme_Blvd_Sidebar_Handler {

	/**
	 * A single instance of this class.
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var Theme_Blvd_Sidebar_Handler
	 */
	private static $instance = null;

	/**
	 * Core framework sidebar locations.
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var array
	 */
	private $core_locations = array();

	/**
	 * Sidebar locations added through client
	 * mutators.
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var array
	 */
	private $client_locations = array();

	/**
	 * Sidebar locations to remove.
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var array
	 */
	private $remove_locations = array();

	/**
	 * Final array of sidebar locations. This combines
	 * $core_elements and $client_elements. WP-Admin only.
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var array
	 */
	private $locations = array();

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @return Theme_Blvd_Sidebar_Handler A single instance of this class.
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
	 * @since Theme_Blvd 2.3.0
	 */
	private function __construct() {

		$this->set_core_locations();

		/*
		 * Finalize locations after client handler has had
		 * a chance to modify them.
		 */
		add_action( 'after_setup_theme', array( $this, 'set_locations' ), 1001 );

		/*
		 * Register sidebars for default locations.
		 *
		 * Note: Theme Blvd Widget Areas plugin hooks in at priority
		 * 11, just after this.
		 */
		add_action( 'widgets_init', array( $this, 'register' ) );

	}

	/**
	 * Set core framework sidebar locations.
	 *
	 * @since Theme_Blvd 2.3.0
	 */
	private function set_core_locations() {

		$this->core_locations = array();

		// Default Left Sidebar
		$this->core_locations['sidebar_left'] = array(
			'type' => 'fixed',
			'location' => array(
				'name' => __( 'Left Sidebar', 'jumpstart' ),
				'id'   => 'sidebar_left',
			),
			'assignments' => array(
				'default' => array(
					'type'       => 'default',
					'id'         => null,
					'name'       => 'Everything',
					'sidebar_id' => 'sidebar_left',
				),
			),
			'args' => array(
				'name'        => __( 'Location: Left Sidebar', 'jumpstart' ),
				'description' => __( 'This is default placeholder for the "Left Sidebar" location.', 'jumpstart' ),
				'id'          => 'sidebar_left',
			),
		);

		// Default Right Sidebar
		$this->core_locations['sidebar_right'] = array(
			'type' => 'fixed',
			'location' => array(
				'name' => __( 'Right Sidebar', 'jumpstart' ),
				'id'   => 'sidebar_right',
			),
			'assignments' => array(
				'default' => array(
					'type'       => 'default',
					'id'         => null,
					'name'       => 'Everything',
					'sidebar_id' => 'sidebar_right',
				),
			),
			'args' => array(
				'name'        => __( 'Location: Right Sidebar', 'jumpstart' ),
				'description' => __( 'This is default placeholder for the "Right Sidebar" location.', 'jumpstart' ),
				'id'          => 'sidebar_right',
			),
		);

		// Default Ad Space - Above Header
		$this->core_locations['ad_above_header'] = array(
			'type' => 'collapsible',
			'location' => array(
				'name' => __( 'Ads Above Header', 'jumpstart' ),
				'id'   => 'ad_above_header',
			),
			'assignments' => array(
				'default' => array(
					'type'       => 'default',
					'id'         => null,
					'name'       => 'Everything',
					'sidebar_id' => 'ad_above_header',
				),
			),
			'args' => array(
				'name'        => __( 'Location: Ads Above Header', 'jumpstart' ),
				'description' => __( 'This is default placeholder for the "Ads Above Header" location, which is designed for banner ads, and so not all widgets will appear as expected.', 'jumpstart' ),
				'id'          => 'ad_above_header',
			),
		);

		// Default Ad Space - Above Content
		$this->core_locations['ad_above_content'] = array(
			'type' => 'collapsible',
			'location' => array(
				'name' => __( 'Ads Above Content', 'jumpstart' ),
				'id'   => 'ad_above_content',
			),
			'assignments' => array(
				'default' => array(
					'type'       => 'default',
					'id'         => null,
					'name'       => 'Everything',
					'sidebar_id' => 'ad_above_content',
				),
			),
			'args' => array(
				'name'        => __( 'Location: Ads Above Content', 'jumpstart' ),
				'description' => __( 'This is default placeholder for the "Ads Above Content" location, which is designed for banner ads, and so not all widgets will appear as expected.', 'jumpstart' ),
				'id'          => 'ad_above_content',
			),
		);

		// Default Ad Space - Below Content
		$this->core_locations['ad_below_content'] = array(
			'type' => 'collapsible',
			'location' => array(
				'name' => __( 'Ads Below Content', 'jumpstart' ),
				'id'   => 'ad_below_content',
			),
			'assignments' => array(
				'default' => array(
					'type'       => 'default',
					'id'         => null,
					'name'       => 'Everything',
					'sidebar_id' => 'ad_below_content',
				),
			),
			'args' => array(
				'name'        => __( 'Location: Ads Below Content', 'jumpstart' ),
				'description' => __( 'This is default placeholder for the "Ads Below Content" location, which is designed for banner ads, and so not all widgets will appear as expected.', 'jumpstart' ),
				'id'          => 'ad_below_content',
			),
		);

		// Default Ad Space - Below Footer
		$this->core_locations['ad_below_footer'] = array(
			'type' => 'collapsible',
			'location' => array(
				'name' => __( 'Ads Below Footer', 'jumpstart' ),
				'id'   => 'ad_below_footer',
			),
			'assignments' => array(
				'default' => array(
					'type'       => 'default',
					'id'         => null,
					'name'       => 'Everything',
					'sidebar_id' => 'ad_below_footer',
				),
			),
			'args' => array(
				'name'        => __( 'Location: Ads Below Footer', 'jumpstart' ),
				'description' => __( 'This is default placeholder for the "Ads Below Footer" location, which is designed for banner ads, and so not all widgets will appear as expected.', 'jumpstart' ),
				'id'          => 'ad_below_footer',
			),
		);

		// Add shared data to core widget areas.
		$shared_args = array(
			'before_widget' => '<aside id="%1$s" class="widget %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		);

		foreach ( $this->core_locations as $id => $location ) {

			foreach ( $shared_args as $key => $value ) {

				$this->core_locations[ $id ]['args'][ $key ] = $value;

			}
		}

		/**
		 * Filters all data used to register the core location
		 * widget areas.
		 *
		 * @since Theme_Blvd 2.3.0
		 *
		 * @param array $core_locations Shared arguments for register_sidebar().
		 */
		$this->core_locations = apply_filters( 'themeblvd_core_sidebar_locations', $this->core_locations );

	}

	/**
	 * Set final sidebar locations. This sets the merged result
	 * of core locations and client added locations.
	 *
	 * @since Theme_Blvd 2.3.0
	 */
	public function set_locations() {

		// Merge core locations with client added locations.
		$this->locations = array_merge( $this->core_locations, $this->client_locations );

		// Remove locations.
		if ( $this->remove_locations ) {

			foreach ( $this->remove_locations as $location ) {

				unset( $this->locations[ $location ] );

			}
		}

		/**
		 * Filters the final sidebar locations, after API has ran
		 * and client sidebar locations have been merged with
		 * framework defaults.
		 *
		 * @since Theme_Blvd 2.3.0
		 *
		 * @param array $locations All widget area locations.
		 */
		$this->locations = apply_filters( 'themeblvd_sidebar_locations', $this->locations );

	}

	/**
	 * Add sidebar location.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param string $id   ID of location.
	 * @param string $name Name of location.
	 * @param string $type Type of location, `fixed` or `collapsible`.
	 * @param string $desc Description or widget area.
	 */
	public function add_location( $id, $name, $type, $desc = '' ) {

		if ( ! $desc ) {

			$desc = sprintf(
				// translators: 1: name of sidebar location
				__( 'This is default placeholder for the "%s" location.', 'jumpstart' ),
				$name
			);

		}

		$this->client_locations[ $id ] = array(
			'type' => $type,
			'location' => array(
				'name' => $name,
				'id'   => $id,
			),
			'assignments' => array(
				'default' => array(
					'type'       => 'default',
					'id'         => null,
					'name'       => 'Everything',
					'sidebar_id' => $id,
				),
			),
			'args' => array(
				// translators: 1: name of sidebar location
				'name'          => sprintf( __( 'Location: %s', 'jumpstart' ), $name ),
				'description'   => $desc,
				'id'            => $id,
				'before_widget' => '<aside id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			),
		);

	}

	/**
	 * Remove sidebar location.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param string $id ID of location to remove.
	 */
	public function remove_location( $id ) {

		$this->remove_locations[] = $id;

	}

	/**
	 * Get core framework sidebar locations.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @return array $registered_elements
	 */
	public function get_core_locations() {

		return $this->core_locations;

	}

	/**
	 * Get added locations from client handler mutators.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @return array $registered_elements
	 */
	public function get_client_locations() {

		return $this->client_locations;

	}

	/**
	 * Get locations to be removed by client handler mutators.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @return array $registered_elements
	 */
	public function get_remove_locations() {

		return $this->remove_locations;

	}

	/**
	 * Get final sidebar locations. This is the merged result
	 * of core locations and client added locations. This
	 * is available after WP's "after_setup_theme" hook.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param  string $location_id  Optional ID of specific location to pull.
	 * @return array  $locations    All locations or specific location.
	 */
	public function get_locations( $location_id = '' ) {

		if ( ! $location_id ) {

			return $this->locations;

		}

		if ( isset( $this->locations[ $location_id ] ) ) {

			return $this->locations[ $location_id ];

		}

		return array();
	}

	/**
	 * Register sidebars with WordPress.
	 *
	 * Hooked to "after_setup_theme" at priority 1001.
	 *
	 * @since Theme_Blvd 2.3.0
	 */
	public function register() {

		/*
		 * Loop through locations and register a default
		 * placeholder sidebar for each location.
		 */
		foreach ( $this->locations as $sidebar ) {

			/**
			 * Filters the arguments used in registering
			 * the default "location" widget areas.
			 *
			 * @since Theme_Blvd 2.3.0
			 *
			 * @param array  $args     Arguments passed to register_sidebar().
			 * @param array  $sidebar  Sidebar information from framework.
			 * @param string $location ID of widget area being registered.
			 */
			$args = apply_filters( 'themeblvd_default_sidebar_args', $sidebar['args'], $sidebar, $sidebar['location']['id'] );

			register_sidebar( $args );

		}

	}

	/**
	 * Display sidebar.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param string $location Location ID for the sidebar to display.
	 */
	public function display( $location ) {

		if ( ! isset( $this->locations[ $location ]['type'] ) ) {
			return;
		}

		$type = $this->locations[ $location ]['type'];

		$sidebar = themeblvd_config( 'sidebars', $location );

		if ( ! $sidebar ) {
			return;
		}

		/*
		 * If this is a collapsible default sidebar with no errors,
		 * we'll want to just kill it if it has no widgets.
		 */
		if ( 'collapsible' === $type && ! $sidebar['error'] && ! is_active_sidebar( $sidebar['id'] ) ) {
			return;
		}

		/**
		 * Fires before a widget area's output is started.
		 *
		 * @hooked null
		 *
		 * @since Theme_Blvd 2.3.0
		 */
		do_action( 'themeblvd_sidebar_' . $type . '_before' );

		/**
		 * Fires before a widget area's output is started.
		 *
		 * @hooked null
		 *
		 * @since Theme_Blvd 2.3.0
		 */
		do_action( 'themeblvd_sidebar_' . $location . '_before' );

		echo '<div class="widget-area widget-area-' . $type . '">';

		/**
		 * Fires before just inside a widget area.
		 *
		 * @hooked null
		 *
		 * @since Theme_Blvd 2.3.0
		 */
		do_action( 'themeblvd_widgets_' . $location . '_before' );

		if ( $sidebar['error'] ) {

			if ( is_user_logged_in() ) {

				switch ( $type ) {

					case 'collapsible':
						$message = sprintf(
							// translators: 1: ID of widget area
							__( 'This is a collapsible widget area with ID, %s, but you haven\'t put any widgets in it yet. Normally this wouldn\'t show at all when empty, but since you have assigned a custom widget area here and didn\'t put any widgets in it, you are seeing this message.', 'jumpstart' ),
							$sidebar['id']
						);
						break;

					case 'fixed':
						$message = sprintf(
							// translators: 1: ID of widget area
							__( 'This is a fixed sidebar with ID, %s, but you haven\'t put any widgets in it yet.', 'jumpstart' ),
							$sidebar['id']
						);
						break;
				}

				echo '<div class="alert alert-warning">';
				echo '	<p>' . esc_html( $message ) . '</p>';
				echo '</div><!-- .tb-warning (end) -->';

			}
		} else {

			/*
			 * Sidebar ID exists and there are no errors; so
			 * let's display the darn thing.
			 */
			dynamic_sidebar( $sidebar['id'] );

		}

		/**
		 * Fires inside a widget area, just before its closed.
		 *
		 * @hooked null
		 *
		 * @since Theme_Blvd 2.3.0
		 */
		do_action( 'themeblvd_widgets_' . $location . '_after' );

		echo '</div><!-- .widget_area (end) -->';

		/**
		 * Fires just outside a widget area, after its closed.
		 *
		 * @hooked null
		 *
		 * @since Theme_Blvd 2.3.0
		 */
		do_action( 'themeblvd_sidebar_' . $location . '_after' );

		/**
		 * Fires just outside a widget area, after its closed.
		 *
		 * @hooked null
		 *
		 * @since Theme_Blvd 2.3.0
		 */
		do_action( 'themeblvd_sidebar_' . $type . '_after' );

	}

} // End class Theme_Blvd_Sidebar_Handler.
