<?php
/**
 * Theme Blvd Widget Areas API.
 *
 * The framework is made up widget area "locations"
 * and there is a default sidebar registered with
 * WordPress for each location. This opens up a chance
 * for the Widget Area plugin to filter in and replace
 * locations with custom sidebars created by the end-user
 * for specific pages of the website.
 *
 * This API controls setting up these locations and
 * registering the default location sidebars. Also, there
 * are some API methods to all locations to be added and
 * removed.
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_Sidebars_API {

	/*--------------------------------------------*/
	/* Properties, private
	/*--------------------------------------------*/

	/**
	 * A single instance of this class.
	 *
	 * @since 2.3.0
	 */
	private static $instance = null;

	/**
	 * Core framework sidebar locations.
	 *
	 * @since 2.3.0
	 */
	private $core_locations = array();

	/**
	 * Sidebar locations added through client API
	 * mutators.
	 *
	 * @since 2.3.0
	 */
	private $client_locations = array();

	/**
	 * Sidebar locations to remove.
	 *
	 * @since 2.3.0
	 */
	private $remove_locations = array();

	/**
	 * Final array of sidebar locations. This combines
	 * $core_elements and $client_elements. WP-Admin only.
	 *
	 * @since 2.3.0
	 */
	private $locations = array();

	/*--------------------------------------------*/
	/* Constructor
	/*--------------------------------------------*/

	/**
     * Creates or returns an instance of this class.
     *
     * @since 2.3.0
     *
     * @return Theme_Blvd_Sidebars_API A single instance of this class.
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
	 * @since 2.3.0
	 */
	private function __construct() {

		// Set core framework locations
		$this->set_core_locations();

		// Finalize locations after client API has had a chance to modify them.
		add_action( 'after_setup_theme', array( $this, 'set_locations' ), 1001 );

		// Regiser sidebars from locations.
		add_action( 'after_setup_theme', array( $this, 'register' ), 1001 );

	}

	/*--------------------------------------------*/
	/* Methods, mutators
	/*--------------------------------------------*/

	/**
	 * Set core framework sidebar locations.
	 *
	 * @since 2.3.0
	 */
	private function set_core_locations() {

		$this->core_locations = array();

		// Default Left Sidebar
		$this->core_locations['sidebar_left'] = array(
			'type' => 'fixed',
			'location'	=> array(
				'name' 	=> __( 'Left Sidebar', 'themeblvd' ),
				'id' 	=> 'sidebar_left'
			),
			'assignments' => array(
				'default' => array(
					'type' 			=> 'default',
					'id' 			=> null,
					'name' 			=> 'Everything',
					'sidebar_id' 	=> 'sidebar_left'
				)
			),
			'args' => array(
			    'name' 			=> __( 'Location: Left Sidebar', 'themeblvd' ),
			    'description' 	=> __( 'This is default placeholder for the "Left Sidebar" location.', 'themeblvd' ),
			    'id' 			=> 'sidebar_left'
			)
		);

		// Default Right Sidebar
		$this->core_locations['sidebar_right'] = array(
			'type' => 'fixed',
			'location' => array(
				'name' 	=> __( 'Right Sidebar', 'themeblvd' ),
				'id'	=> 'sidebar_right'
			),
			'assignments' => array(
				'default' => array(
					'type' 			=> 'default',
					'id' 			=> null,
					'name' 			=> 'Everything',
					'sidebar_id' 	=> 'sidebar_right'
				)
			),
			'args' => array(
			    'name' 			=> __( 'Location: Right Sidebar', 'themeblvd' ),
			    'description' 	=> __( 'This is default placeholder for the "Right Sidebar" location.', 'themeblvd' ),
			    'id' 			=> 'sidebar_right'
			)
		);

		// Default Ad Space - Above Header
		$this->core_locations['ad_above_header'] = array(
			'type' => 'collapsible',
			'location' => array(
				'name' 	=> __( 'Ads Above Header', 'themeblvd' ),
				'id'	=> 'ad_above_header'
			),
			'assignments' => array(
				'default' => array(
					'type' 			=> 'default',
					'id' 			=> null,
					'name' 			=> 'Everything',
					'sidebar_id' 	=> 'ad_above_header'
				)
			),
			'args' => array(
			    'name' 			=> __( 'Location: Ads Above Header', 'themeblvd' ),
			    'description' 	=> __( 'This is default placeholder for the "Ads Above Header" location, which is designed for banner ads, and so not all widgets will appear as expected.', 'themeblvd' ),
			    'id' 			=> 'ad_above_header'
			)
		);

		// Default Ad Space - Above Content
		$this->core_locations['ad_above_content'] = array(
			'type' => 'collapsible',
			'location' => array(
				'name' 	=> __( 'Ads Above Content', 'themeblvd' ),
				'id'	=> 'ad_above_content'
			),
			'assignments' => array(
				'default' => array(
					'type' 			=> 'default',
					'id' 			=> null,
					'name' 			=> 'Everything',
					'sidebar_id' 	=> 'ad_above_content'
				)
			),
			'args' => array(
			    'name' 			=> __( 'Location: Ads Above Content', 'themeblvd' ),
			    'id' 			=> 'ad_above_content'
			)
		);

		// Default Ad Space - Below Content
		$this->core_locations['ad_below_content'] = array(
			'type' => 'collapsible',
			'location' => array(
				'name' 	=> __( 'Ads Below Content', 'themeblvd' ),
				'id'	=> 'ad_below_content'
			),
			'assignments' => array(
				'default' => array(
					'type' 			=> 'default',
					'id' 			=> null,
					'name' 			=> 'Everything',
					'sidebar_id' 	=> 'ad_below_content'
				)
			),
			'args' => array(
			    'name' 			=> __( 'Location: Ads Below Content', 'themeblvd' ),
			    'description' 	=> __( 'This is default placeholder for the "Ads Below Content" location, which is designed for banner ads, and so not all widgets will appear as expected.', 'themeblvd' ),
			    'id' 			=> 'ad_below_content'
			)
		);

		// Default Ad Space - Below Footer
		$this->core_locations['ad_below_footer'] = array(
			'type' => 'collapsible',
			'location' => array(
				'name' 	=> __( 'Ads Below Footer', 'themeblvd' ),
				'id'	=> 'ad_below_footer'
			),
			'assignments' => array(
				'default' => array(
					'type' 			=> 'default',
					'id' 			=> null,
					'name' 			=> 'Everything',
					'sidebar_id' 	=> 'ad_below_footer'
				)
			),
			'args' => array(
			    'name' 			=> __( 'Location: Ads Below Footer', 'themeblvd' ),
			    'description' 	=> __( 'This is default placeholder for the "Ads Below Footer" location, which is designed for banner ads, and so not all widgets will appear as expected.', 'themeblvd' ),
			    'id' 			=> 'ad_below_footer'
			)
		);

		// Add in shared arguments
		foreach ( $this->core_locations as $id => $location ) {
			$this->core_locations[$id]['args']['before_widget'] = '<aside id="%1$s" class="widget %2$s"><div class="widget-inner">';
			$this->core_locations[$id]['args']['after_widget'] 	= '</div></aside>';
			$this->core_locations[$id]['args']['before_title'] 	= '<h3 class="widget-title">';
			$this->core_locations[$id]['args']['after_title'] 	= '</h3>';
		}

		// Extend
		$this->core_locations = apply_filters( 'themeblvd_core_sidebar_locations', $this->core_locations );

	}

	/**
	 * Set final sidebar locations. This sets the merged result
	 * of core locations and client API-added locations.
	 *
	 * @since 2.3.0
	 */
	public function set_locations() {

		// Merge core locations with client API-added locations.
		$this->locations = array_merge( $this->core_locations, $this->client_locations );

		// Remove locations
		if ( $this->remove_locations ) {
			foreach ( $this->remove_locations as $location ) {
				unset( $this->locations[$location] );
			}
		}

		// Extend
		$this->locations = apply_filters( 'themeblvd_sidebar_locations', $this->locations );

	}

	/*--------------------------------------------*/
	/* Methods, client API mutators
	/*--------------------------------------------*/

	/**
	 * Add sidebar location.
	 *
	 * @since 2.3.0
	 *
	 * @param string $id ID of location
	 * @param string $name Name of location
	 * @param string $type Type of location - fixed or collapsible
	 * @param string $desc Description or widget area
	 */
	public function add_location( $id, $name, $type, $desc = '' ) {

		// Description
		if ( ! $desc ) {
			$desc = sprintf( __( 'This is default placeholder for the "%s" location.', 'themeblvd'), $name );
		}

		// Add Sidebar location
		$this->client_locations[$id] = array(
			'type' => $type,
			'location' => array(
				'name' 	=> $name,
				'id'	=> $id
			),
			'assignments' => array(
				'default' => array(
					'type' 			=> 'default',
					'id' 			=> null,
					'name' 			=> 'Everything',
					'sidebar_id'	=> $id
				)
			),
			'args' => array(
			    'name'			=> sprintf( __( 'Location: %s', 'themeblvd' ), $name ),
			    'description' 	=> $desc,
			    'id' 			=> $id,
			    'before_widget' => '<aside id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget' 	=> '</div></aside>',
				'before_title' 	=> '<h3 class="widget-title">',
				'after_title' 	=> '</h3>'
			)
		);

	}

	/**
	 * Remove sidebar location.
	 *
	 * @since 2.3.0
	 *
	 * @param string $id ID of location to remove
	 */
	public function remove_location( $id ) {
		$this->remove_locations[] = $id;
	}

	/*--------------------------------------------*/
	/* Methods, accessors
	/*--------------------------------------------*/

	/**
	 * Get core framework sidebar locations.
	 *
	 * @since 2.3.0
	 *
	 * @return array $registered_elements
	 */
	public function get_core_locations() {
		return $this->core_locations;
	}

	/**
	 * Get added locations from client API mutators.
	 *
	 * @since 2.3.0
	 *
	 * @return array $registered_elements
	 */
	public function get_client_locations() {
		return $this->client_locations;
	}

	/**
	 * Get locations to be removed by client API mutators.
	 *
	 * @since 2.3.0
	 *
	 * @return array $registered_elements
	 */
	public function get_remove_locations() {
		return $this->remove_locations;
	}

	/**
	 * Get final sidebar locations. This is the merged result
	 * of core locations and client API-added locations. This
	 * is available after WP's "after_setup_theme" hook.
	 *
	 * @since 2.3.0
	 *
	 * @param string $location_id Optional ID of specific location to pull.
	 * @return array $locations All locations or specific location.
	 */
	public function get_locations( $location_id = '' ) {

		if ( ! $location_id ) {
			return $this->locations;
		}

		if ( isset( $this->locations[$location_id] ) ) {
			return $this->locations[$location_id];
		}

		return array();
	}

	/*--------------------------------------------*/
	/* Methods, helpers
	/*--------------------------------------------*/

	/**
	 * Register sidebars with WordPress. Hooked to "after_setup_theme"
	 * at priority 1001.
	 *
	 * @since 2.3.0
	 */
	public function register() {

		// Loop through locations and register a default
		// placeholder sidebar for each location.
		foreach ( $this->locations as $sidebar ) {

			// Filter args for each of default sidebar
			$args = apply_filters( 'themeblvd_default_sidebar_args', $sidebar['args'], $sidebar, $sidebar['location']['id'] );

			// Register sidebar with WordPress
			register_sidebar( $args );

		}

	}

	/**
	 * Display Sidebar
	 *
	 * @since 2.3.0
	 *
	 * @param string $location Location ID for the sidebar to display
	 */
	public function display( $location ) {

		// Setup type
		if ( ! isset( $this->locations[$location]['type'] ) ) {
			return;
		}

		$type = $this->locations[$location]['type'];

		// Current configuration for sidebar
		$sidebar = themeblvd_config( 'sidebars', $location );

		// If sidebar is set to false or sidebar doesn't
		// exist, kill it.
		if ( ! $sidebar ) {
			return;
		}

		// If this is a collapsible default sidebar with
		// no errors, we'll want to just kill it if it
		// has no widgets.
		if ( $type == 'collapsible' && ! $sidebar['error'] && ! is_active_sidebar( $sidebar['id'] ) ) {
			return;
		}

		// Start display.
		do_action( 'themeblvd_sidebar_'.$type.'_before' ); // Framework does not hook anything here by default
		do_action( 'themeblvd_sidebar_'.$location.'_before' ); // Framework does not hook anything here by default
		echo '<div class="widget-area widget-area-'.$type.'">';

		// Proceed, but check for error
		if ( $sidebar['error'] ) {

			// Only show error message if user is logged in.
			if ( is_user_logged_in() ) {

				// Set message
				switch ( $type ) {
					case 'collapsible' :
						$message = sprintf( __( 'This is a collapsible widget area with ID, <strong>%s</strong>, but you haven\'t put any widgets in it yet. Normally this wouldn\'t show at all when empty, but since you have assigned a custom widget area here and didn\'t put any widgets in it, you are seeing this message.', 'themeblvd' ), $sidebar['id'] );
						break;

					case 'fixed' :
						$message = sprintf( __( 'This is a fixed sidebar with ID, <strong>%s</strong>, but you haven\'t put any widgets in it yet.', 'themeblvd' ), $sidebar['id'] );
						break;
				}

				// Ouput message
				echo '<div class="alert alert-warning">';
				echo '	<p>'.$message.'</p>';
				echo '</div><!-- .tb-warning (end) -->';

			}

		} else {

			// Sidebar ID exists and there are no errors.
			// So, let's display the darn thing.
			dynamic_sidebar( $sidebar['id'] );

		}

		// End display
		echo '</div><!-- .widget_area (end) -->';
		do_action( 'themeblvd_sidebar_'.$location.'_after' ); // Framework does not hook anything here by default
		do_action( 'themeblvd_sidebar_'.$type.'_after' ); // Framework does not hook anything here by default

	}

} // End class Theme_Blvd_Sidebars_API