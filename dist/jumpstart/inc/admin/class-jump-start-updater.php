<?php
/**
 * Jump Start Theme Updater
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @since      Jump_Start 2.2.1
 */

/**
 * Updates the Jump Start theme with WordPress.
 *
 * This class was adapted from EDD_SL_Theme_Updater
 * by Easy Digital Downloads.
 *
 * @since Jump_Start 2.2.1
 */
class Jump_Start_Updater {

	private $items;

	private $remote_api_url;

	private $request_data;

	private $response_key;

	private $item_shortname;

	private $license_key;

	private $version;

	private $author;

	private $changelog_url;

	private $extensions;

	private $extension_licenses;

	/**
	 * Constructor.
	 *
	 * @since Jump_Start 2.2.1
	 *
	 * @param array $items Saved license items.
	 * @param array $args {
	 *     Updater arguments.
	 *
	 *     @type string $remote_api_url Remote URL to EDD store.
	 *     @type array  $request_data   Update data.
	 *     @type string $item_shortname Theme slug.
	 *     @type string $item_name      Theme Name.
	 *     @type string $license        User license key.
	 *     @type string $author         Theme author.
	 *     @type string $changelog_url  URL to changelog.
	 * }
	 */
	function __construct( $items, $args = array() ) {

		$this->items = $items;

		$args = wp_parse_args( $args, array(
			'remote_api_url' => 'https://wpjumpstart.com',
			'request_data'   => array(),
			'item_shortname' => get_template(),
			'item_name'      => 'Jump Start', // Must match EDD store item name.
			'author'         => 'Theme Blvd',
			'changelog_url'  => '',
			'license'        => '',
		) );

		$this->item_name = $args['item_name'];

		$this->item_shortname = sanitize_key( $args['item_shortname'] );

		$this->author = $args['author'];

		$this->remote_api_url = $args['remote_api_url'];

		$this->response_key = $this->item_shortname . '-update-response';

		$this->changelog_url = $args['changelog_url'];

		$this->set_license( $items, $args['license'] );

		// Add to core theme updates for the Jump Start theme.

		add_filter( 'site_transient_update_themes', array( $this, 'theme_update_transient' ) );

		add_filter( 'delete_site_transient_update_themes', array( $this, 'delete_theme_update_transient' ) );

		add_action( 'load-update-core.php', array( $this, 'delete_theme_update_transient' ) );

		add_action( 'load-themes.php', array( $this, 'delete_theme_update_transient' ) );

		add_action( 'load-themes.php', array( $this, 'load_theme_update_nag' ) );

		add_filter( 'wp_prepare_themes_for_js', array( $this, 'theme_update_message' ) );

		// Add to core plugin updates for any Jump Start extensions.

		$this->set_extensions( $items );

		if ( $this->extensions ) {

			add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_plugin_update' ) );

			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );

			add_action( 'load-plugins.php', array( $this, 'load_plugin_update_nag' ), 30 ); // WP hooks to this at priority 20.

		}

	}

	/**
	 * Set license key for Jump Start theme.
	 *
	 * @since Jump_Start 2.2.1
	 *
	 * @param array $items   Saved items from license management page.
	 * @param array $license Manually feed license key.
	 */
	private function set_license( $items = array(), $license = null ) {

		$this->license = $license;

		if ( ! $this->license ) {

			if ( ! empty( $items[ $this->item_shortname ] ) ) {

				if ( ! empty( $items[ $this->item_shortname ]['key'] ) ) {

					$this->license = $items[ $this->item_shortname ]['key'];

				}
			}
		}

	}

	/**
	 * Set extensions.
	 *
	 * @since Jump_Start 2.2.1
	 *
	 * @param array $items Saved items from license management page.
	 */
	private function set_extensions( $items ) {

		/** This filter is documented in inc/admin/class-jump-start-license-admin.php */
		$extensions = apply_filters( 'jump_start_installed_extensions', array() );

		if ( is_array( $items ) && ! empty( $items[ $this->item_shortname ] ) ) {

			unset( $items[ $this->item_shortname ] ); // Remove theme from extensions.

		}

		$this->extensions = array();

		if ( $items ) {

			foreach ( $items as $shortname => $item ) {

				if ( ! empty( $item['license'] ) && 'valid' == $item['license'] ) {

					$item = wp_parse_args( $item, $extensions[ $shortname ] );

					$cache_key = '';

					if ( ! empty( $item['key'] ) ) {

						$cache_key = 'jumpstart_license_' . md5( serialize( $shortname . $item['key'] ) );

					}

					$item['cache_key'] = $cache_key;

					$this->extensions[ $shortname ] = $item;

				}

			}
		}

	}

	/**
	 * Hooks in the "major upgrade" update nag at the
	 * top of the WordPress admin.
	 *
	 * @since Jump_Start 2.2.1
	 */
	public function load_theme_update_nag() {

		/**
		 * Filters the major upgrade notice.
		 *
		 * When it is deemed that the update performed
		 * will be a major upgrade, there is a notice
		 * that stays on the screen. This filter will
		 * get rid of that.
		 *
		 * @since Jump_Start 2.2.2
		 *
		 * @param bool Whether the nag displays.
		 */
		if ( apply_filters( 'jumpstart_major_upgrade_nag', true ) ) {

			add_action( 'admin_notices', array( $this, 'theme_update_nag' ) );

		}

	}

	/**
	 * Displays the "major upgrade" update nag at the
	 * top of the WordPress admin.
	 *
	 * @since Jump_Start 2.2.1
	 */
	public function theme_update_nag() {

		$api_response = get_transient( $this->response_key );

		if ( false === $api_response ) {

			return;

		}

		$theme = wp_get_theme( $this->item_shortname );

		$current_version = $theme->get( 'Version' );

		$new_version = $api_response->new_version;

		$major_upgrade = $this->is_major_theme_upgrade( $current_version, $new_version );

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
	 * @since Jump_Start 2.2.1
	 */
	function theme_update_transient( $value ) {

		$update_data = $this->check_theme_update();

		if ( $update_data ) {

			$value->response[ $this->item_shortname ] = $update_data;

		}

		return $value;

	}

	/**
	 * Delete the theme update transient.
	 *
	 * @since Jump_Start 2.2.1
	 */
	function delete_theme_update_transient() {

		delete_transient( $this->response_key );

	}

	/**
	 * Check if a theme update is available.
	 *
	 * @since Jump_Start 2.2.1
	 */
	function check_theme_update() {

		$theme = wp_get_theme( $this->item_shortname );

		$update_data = get_transient( $this->response_key );

		if ( false === $update_data ) {

			$failed = false;

			$update_data = $this->api_request();

			if ( ! $update_data ) {

				// if the response failed, try again in 30 minutes.

				$data = new stdClass;

				$data->new_version = $theme->get( 'Version' );

				if ( $this->changelog_url ) {

					$data->url = esc_url( $this->changelog_url );

				}

				set_transient( $this->response_key, $data, 30 * MINUTE_IN_SECONDS );

				return false;

			} else {

				if ( $this->changelog_url ) {

					$update_data->url = esc_url( $this->changelog_url );

				}

				$update_data->sections = maybe_unserialize( $update_data->sections );

				set_transient( $this->response_key, $update_data, 12 * HOUR_IN_SECONDS );

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
	 * @since Jump_Start 2.2.1
	 *
	 * @param  array $themes Array of themes.
	 * @return array $themes Maybe modified array of themes.
	 */
	public function theme_update_message( $themes ) {

		$theme = get_template();

		$api_response = get_transient( $this->response_key );

		if ( false === $api_response ) {

			return $themes;

		}

		$new_version = $api_response->new_version;

		if ( ! $this->is_major_theme_upgrade( $themes[ $theme ]['version'], $new_version ) ) {

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
	 * @since Jump_Start 2.2.1
	 *
	 * @param  string      $current_version Current installed version.
	 * @param  string      $new_version     New version available.
	 * @return bool|string                  Major release version or FALSE if not a major upgrade.
	 */
	private function is_major_theme_upgrade( $current_version, $new_version ) {

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

	/**
	 * Check for plugin updates.
	 *
	 * @since Jump_Start 2.2.2
	 *
	 * @uses api_request()
	 *
	 * @param  array $_transient_data Update array build by WordPress.
	 * @return array $_transient_data Modified update array with custom plugin data.
	 */
	public function check_plugin_update( $_transient_data ) {

		global $pagenow;

		if ( ! is_object( $_transient_data ) ) {

			$_transient_data = new stdClass;

		}

		if ( 'plugins.php' == $pagenow && is_multisite() ) {

			return $_transient_data;

		}

		$checked = true;

		if ( ! empty( $_transient_data->response ) ) {

			foreach ( $this->extensions as $shortname => $license ) {

				$plugin = '';

				if ( ! empty( $this->extensions[ $shortname ] ) ) {

					if ( ! empty( $this->extensions[ $shortname ]['file'] ) ) {

						$plugin = $this->extensions[ $shortname ]['file'];

					}
				}

				if ( $plugin && empty( $_transient_data->response[ $plugin ] ) ) {

					$checked = false;

					break;

				}
			}
		}

		if ( $checked ) {

			return $_transient_data;

		}

		foreach ( $this->extensions as $extension ) {

			$update_data = $this->get_cached_plugin_version( $extension['item_shortname'] );

			if ( ! $update_data ) {

				$update_data = $this->api_request( array(
					'license' => $extension['key'],
					'name'    => $extension['item_name'],
					'slug'    => $extension['item_shortname'],
					'author'  => $extension['author'],
				) );

				$this->set_cached_plugin_version( $extension['item_shortname'], $update_data );

			}

			if ( $update_data && isset( $update_data->new_version ) ) {

				if ( version_compare( $extension['version'], $update_data->new_version, '<' ) ) {

					$_transient_data->response[ $extension['file'] ] = $update_data;

				}

				$_transient_data->last_checked = current_time( 'timestamp' );

			}
		}

		return $_transient_data;

	}

	/**
	 * Remove "View Details" link from Plugin sceen, for
	 * our extensions.
	 *
	 * A more common approach would be to filter
	 * "plugins_api" to get the modal display correct
	 * information. But instead, we'll just remove the
	 * links all together to make plugin management easier.
	 *
	 * @since Jump_Start 2.2.2
	 *
	 * @param  array  $plugin_meta An array of the plugin's metadata, including the version, author,
	 *                             author URI, and plugin URI.
	 * @param  string $plugin_file Path to the plugin file, relative to the plugins directory.
	 * @param  array  $plugin_data An array of plugin data.
	 * @param  string $status      Status of the plugin. Defaults are 'All', 'Active',
	 *                            'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
	 *                            'Drop-ins', 'Search'.
	 * @return array  $plugin_meta Maybe modified plugin row meta.
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {

		if ( ! isset( $plugin_data['slug'] ) || ! array_key_exists( $plugin_data['slug'], $this->extensions ) ) {

			return $plugin_meta;

		}

		foreach ( $plugin_meta as $key => $value ) {

			// Remove any links that open to the WordPress plugin modal.
			if ( false !== strpos( $value, 'open-plugin-details-modal' ) ) {

				unset( $plugin_meta[ $key ] );

			}
		}

		return $plugin_meta;

	}

	/**
	 * Swaps in the default plugin update row with our
	 * custom ones.
	 *
	 * @since Jump_Start 2.2.2
	 */
	public function load_plugin_update_nag() {

		foreach ( $this->extensions as $extension ) {

			remove_action( 'after_plugin_row_' . $extension['file'], 'wp_plugin_update_row' );

			add_action( 'after_plugin_row_' . $extension['file'], array( $this, 'plugin_update_notification' ), 10, 2 );

		}

	}

	/**
	 * Show update nofication row -- needed for multisite subsites,
	 * because WP won't tell you otherwise!
	 *
	 * @since Jump_Start 2.2.2
	 *
	 * @param  string $shortname Internal shortname used for plugin.
	 * @return object            Cached version data.
	 */
	public function plugin_update_notification( $file, $plugin ) {

		$current = get_site_transient( 'update_plugins' );

		if ( ! isset( $current->response[ $file ] ) ) {

			return false;

		}

		$response = $current->response[ $file ];

		$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );

		if ( is_network_admin() ) {

			$active_class = is_plugin_active_for_network( $file ) ? ' active' : '';

		} else {

			$active_class = is_plugin_active( $file ) ? ' active' : '';

		}

		echo '<tr class="plugin-update-tr' . $active_class . '" id="' . esc_attr( $response->slug . '-update' ) . '" data-slug="' . esc_attr( $response->slug ) . '" data-plugin="' . esc_attr( $file ) . '">';

		echo '<td colspan="' . esc_attr( $wp_list_table->get_column_count() ) . '" class="plugin-update colspanchange">';

		echo '<div class="update-message notice inline notice-warning notice-alt">';

		echo '<p>';

		if ( ! current_user_can( 'update_plugins' ) ) {

			printf(
				__( 'There is a new version of %s available. View <a href="%s" target="_blank">version %s details</a>.', 'jumpstart' ),
				$response->name,
				$response->url,
				$response->new_version
			);

		} else {

			printf(
				__( 'There is a new version of %s available. View <a href="%s" target="_blank">version %s details</a> or <a href="%s" %s>update now</a>.', 'jumpstart' ),
				$response->name,
				$response->url,
				$response->new_version,
				wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file, 'upgrade-plugin_' . $file ),
				sprintf(
					'class="update-link" aria-label="%s"',
					/* translators: 1. plugin name */
					esc_attr( sprintf( __( 'Update %s now' ), $response->name ) )
				)
			);

		}

		echo '</p>';

		echo '</div>';

		echo '</td>';

		echo '</tr>';

	}

	/**
	 * Get the cached version information for a plugin.
	 *
	 * @since Jump_Start 2.2.2
	 *
	 * @param  string $shortname Internal shortname used for plugin.
	 * @return object            Cached version data.
	 */
	private function get_cached_plugin_version( $shortname ) {

		$cache_key = '';

		if ( ! empty( $this->extensions[ $shortname ] ) ) {

			if ( ! empty( $this->extensions[ $shortname ]['cache_key'] ) ) {

				$cache_key = $this->extensions[ $shortname ]['cache_key'];

			}
		}

		return get_transient( $cache_key );

	}

	/**
	 * Set the cached version information for a plugin.
	 *
	 * @since Jump_Start 2.2.2
	 *
	 * @param string $shortname Internal shortname used for plugin.
	 * @param string $value     JSON response to be cached.
	 */
	private function set_cached_plugin_version( $shortname, $value ) {

		$cache_key = '';

		if ( ! empty( $this->extensions[ $shortname ] ) ) {

			if ( ! empty( $this->extensions[ $shortname ]['cache_key'] ) ) {

				$cache_key = $this->extensions[ $shortname ]['cache_key'];

			}
		}

		if ( $cache_key && $value ) {

			set_transient( $cache_key, $value, 3 * HOUR_IN_SECONDS ); // 3 hours.

		}

	}

	/**
	 * Calls the API and, if successfull, returns the object
	 * delivered by the API.
	 *
	 * @param  array        $params Parameters for the API action.
	 * @return false|object
	 */
	private function api_request( $params = array() ) {

		$update_data = false;

		$api_params = array(
			'edd_action' => 'get_version',
			'license'    => ! empty( $params['license'] ) ? $params['license'] : $this->license,
			'name'       => ! empty( $params['name'] ) ? $params['name'] : $this->item_name,
			'slug'       => ! empty( $params['slug'] ) ? $params['slug'] : $this->item_shortname,
			'author'     => ! empty( $params['author'] ) ? $params['author'] : $this->author,
		);

		$response = wp_remote_post( $this->remote_api_url, array(
			'timeout' => 15,
			'body'    => $api_params,
		) );

		if ( ! is_wp_error( $response ) && 200 == wp_remote_retrieve_response_code( $response ) ) {

			$update_data = json_decode( wp_remote_retrieve_body( $response ) );

		}

		if ( ! $update_data || ! is_object( $update_data ) ) {

			return false;

		}

		// What type of item is this? Theme or plugin?
		$type = 'plugin';

		if ( $this->item_shortname == $api_params['slug'] ) {

			$type = 'theme';

		}

		if ( isset( $update_data->url ) ) {

			$update_data->url = sprintf(
				'http://themeblvd.com/changelog/?%s=%s',
				$type,
				$api_params['slug']
			);

		}

		if ( 'plugin' == $type ) {

			if ( isset( $update_data->banners ) ) {

				$update_data->banners = maybe_unserialize( $update_data->banners );

			}

			if ( isset( $update_data->sections ) ) {

				$update_data->sections = maybe_unserialize( $update_data->sections );

				if ( isset( $update_data->sections['description'] ) ) {

					unset( $update_data->sections['description'] );

				}
			}

			if ( ! empty( $update_data->sections ) ) {

				foreach( $update_data->sections as $key => $section ) {

					$update_data->$key = (array) $section;

				}
			}
		}

		return $update_data;

	}
}
