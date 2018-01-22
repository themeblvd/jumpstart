<?php
/**
 * Jump Start Theme Updater
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.7.1
 */

/**
 * Updates the Jump Start theme with WordPress.
 *
 * This class was adapted from EDD_SL_Theme_Updater
 * by Easy Digital Downloads.
 *
 * @since Theme_Blvd 2.7.1
 */
class Jump_Start_Updater {

	private $remote_api_url;

	private $request_data;

	private $response_key;

	private $theme_slug;

	private $license_key;

	private $version;

	private $author;

	private $changelog_url;

	/**
	 * Constructor.
	 *
	 * @since Theme_Blvd 2.7.1
	 *
	 * @param array $args {
	 *     Updater arguments.
	 *
	 *     @type string $remote_api_url Remote URL to EDD store.
	 *     @type array  $request_data   Update data.
	 *     @type string $theme_slug     Theme slug.
	 *     @type string $item_name      Theme Name.
	 *     @type string $license        User license key.
	 *     @type string $version        Installed version.
	 *     @type string $author         Theme author.
	 *     @type string $changelog_url  URL to changelog.
	 * }
	 */
	function __construct( $args = array() ) {

		$args = wp_parse_args( $args, array(
			'remote_api_url' => 'http://wpjumpstart.com',
			'request_data'   => array(),
			'theme_slug'     => get_template(),
			'item_name'      => '',
			'license'        => '',
			'version'        => '',
			'author'         => '',
			'changelog_url'  => ''
		) );

		$this->license = $args['license'];

		$this->item_name = $args['item_name'];

		$this->version = $args['version'];

		$this->theme_slug = sanitize_key( $args['theme_slug'] );

		$this->author = $args['author'];

		$this->remote_api_url = $args['remote_api_url'];

		$this->response_key = $this->theme_slug . '-update-response';

		$this->changelog_url = $args['changelog_url'];

		add_filter( 'site_transient_update_themes', array( $this, 'theme_update_transient' ) );

		add_filter( 'delete_site_transient_update_themes', array( $this, 'delete_theme_update_transient' ) );

		add_action( 'load-update-core.php', array( $this, 'delete_theme_update_transient' ) );

		add_action( 'load-update-core.php', array( $this, 'load_update_nag' ) );

		add_action( 'load-themes.php', array( $this, 'delete_theme_update_transient' ) );

		add_action( 'load-themes.php', array( $this, 'load_update_nag' ) );

		add_filter( 'wp_prepare_themes_for_js', array( $this, 'update_message' ) );

	}

	/**
	 * Hooks in the "major upgrade" update nag at the
	 * top of the WordPress admin.
	 *
	 * @since Theme_Blvd 2.7.1
	 */
	public function load_update_nag() {

		/**
		 * Filters the major upgrade notice.
		 *
		 * When it is deemed that the update performed
		 * will be a major upgrade, there is a notice
		 * that stays on the screen. This filter will
		 * get rid of that.
		 *
		 * @since Theme_Blvd 2.7.1
		 *
		 * @param bool Whether the nag displays.
		 */
		if ( apply_filters( 'themeblvd_update_nag', true ) ) {

			add_action( 'admin_notices', array( $this, 'update_nag' ) );

		}

	}

	/**
	 * Displays the "major upgrade" update nag at the
	 * top of the WordPress admin.
	 *
	 * @since Theme_Blvd 2.7.1
	 */
	public function update_nag() {

		$api_response = get_transient( $this->response_key );

		if ( false === $api_response ) {

			return;

		}

		$theme = wp_get_theme( $this->theme_slug );

		$current_version = $theme->get( 'Version' );

		$new_version = $api_response->new_version;

		$major_upgrade = $this->is_major_upgrade( $current_version, $new_version );

		if ( $major_upgrade ) {

			$message = sprintf(
				// translators: 1. theme name, 2 theme version, 3. "view the changelog" link, 4. "update responsibly" link
				__( '<strong>%1$s %2$s</strong> is available. This is a major upgrade. Please %3$s for important details on this release and keep in mind that major upgrades may affect child theme customization. So use caution and %4$s.', 'jumpstart' ),
				$theme->get( 'Name' ),
				$new_version,
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( $this->changelog_url ) . '#' . $major_upgrade,
					__( 'view the changelog', 'jumpstart' )
				),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					'http://themeblvd.com/links/update-responsibly',
					__( 'update responsibly', 'jumpstart' )
				)
			);

			printf( '<div class="notice notice-warning"><p>%s</p></div>', $message );

		}

	}

	/**
	 * Update the theme update transient.
	 *
	 * @since Theme_Blvd 2.7.1
	 */
	function theme_update_transient( $value ) {

		$update_data = $this->check_for_update();

		if ( $update_data ) {

			$value->response[ $this->theme_slug ] = $update_data;

		}

		return $value;

	}

	/**
	 * Delete the theme update transient.
	 *
	 * @since Theme_Blvd 2.7.1
	 */
	function delete_theme_update_transient() {

		delete_transient( $this->response_key );

	}

	/**
	 * Check if a theme update is available.
	 *
	 * @since Theme_Blvd 2.7.1
	 */
	function check_for_update() {

		$theme = wp_get_theme( $this->theme_slug );

		$update_data = get_transient( $this->response_key );

		if ( false === $update_data ) {

			$failed = false;

			$api_params = array(
				'edd_action' => 'get_version',
				'license'    => $this->license,
				'name'       => $this->item_name,
				'slug'       => $this->theme_slug,
				'author'     => $this->author,
			);

			$response = wp_remote_post( $this->remote_api_url, array(
				'timeout' => 15,
				'body'    => $api_params,
			) );

			// Make sure the response was successful.
			if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {

				$failed = true;

			}

			$update_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ! is_object( $update_data ) ) {

				$failed = true;

			}

			// if the response failed, try again in 30 minutes.
			if ( $failed ) {

				$data = new stdClass;

				$data->new_version = $theme->get( 'Version' );

				if ( $this->changelog_url ) {

					$data->url = esc_url( $this->changelog_url );

				}

				set_transient( $this->response_key, $data, strtotime( '+30 minutes' ) );

				return false;

			}

			// If the status is 'ok', return the update arguments.
			if ( ! $failed ) {

				if ( $this->changelog_url ) {

					$update_data->url = esc_url( $this->changelog_url );

				}

				$update_data->sections = maybe_unserialize( $update_data->sections );

				set_transient( $this->response_key, $update_data, strtotime( '+12 hours' ) );

			}
		}

		if ( version_compare( $theme->get( 'Version' ), $update_data->new_version, '>=' ) ) {

			return false;

		}

		return (array) $update_data;

	}

	/**
	 * Adds additional information to the core update
	 * message when viewing details of a theme.
	 *
	 * This information only gets added if this is
	 * deemed to be a "major upgrade".
	 *
	 * @since Theme_Blvd 2.7.1
	 *
	 * @param array  $themes Array of themes.
	 * @return array $themes Maybe modified array of themes.
	 */
	public function update_message( $themes ) {

		$theme = get_template();

		$api_response = get_transient( $this->response_key );

		if ( false === $api_response ) {

			return $themes;

		}

		$new_version = $api_response->new_version;

		if ( ! $this->is_major_upgrade( $themes[ $theme ]['version'], $new_version ) ) {

			return $themes;

		}

		// Build the message to add.
		$message = sprintf(
			// translators: 1. "update responsibly" link
			__( 'This is a major upgrade that may affect child theme customization. Use caution and %s.', 'jumpstart' ),
			sprintf(
				'<a href="%s" target="_blank">%s</a>',
				'http://themeblvd.com/links/update-responsibly',
				__( 'update responsibly', 'jumpstart' )
			)
		);

		// Insert the message at the end of the paragraph.
		$themes[ $theme ]['update'] = str_replace(
			'</strong></p>',
			' ' . $message . '</strong></p>',
			$themes[ $theme ]['update']
		);

		return $themes;

	}

	/**
	 * Determine if the current update is considered
	 * a "major upgrade".
	 *
	 * @since Theme_Blvd 2.7.1
	 *
	 * @param  string      $current_version Current installed version.
	 * @param  string      $new_version     New version available.
	 * @return bool|string                  Major release version or FALSE if not a major upgrade.
	 */
	private function is_major_upgrade( $current_version, $new_version ) {

		$major = false;

		$a = explode( '.', $current_version );

		$b = explode( '.', $new_version );

		if ( $a[0] < $b[0] ) {

			$major = true;

		}

		if ( $a[0] == $b[0] ) {

			if ( isset( $a[1] ) && isset( $b[1] ) ) {

				if ( $a[1] < $b[1] ) {

					$major = true;

				}
			}
		}

		if ( $major ) {

			return sprintf( '%s.%s.0', $b[0], $b[1] );

		}


		return false;

	}
}
