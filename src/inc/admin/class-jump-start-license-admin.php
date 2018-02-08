<?php
/**
 * Jump Start License Admin
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @since      @@name-package 2.2.1
 */

/**
 * Adds an admin interface to manage license keys for
 * Jump Start and its extensions, which ultimately enable
 * in-dashboard updates.
 *
 * @since @@name-package 2.2.1
 */
class Jump_Start_License_Admin {

	/**
	 * EDD store URL where EDD Software Licensing
	 * is installed.
	 *
	 * @since @@name-package 2.2.2
	 * @var string
	 */
	private $remote_api_url;

	/**
	 * Theme short name or slug.
	 *
	 * @since @@name-package 2.2.2
	 * @var string
	 */
	private $item_shortname;

	/**
	 * Theme name.
	 *
	 * @since @@name-package 2.2.2
	 * @var string
	 */
	private $item_name;

	/**
	 * Represents the menu_slug registered for the
	 * admin page and the option ID used to save
	 * the license keys.
	 *
	 * @since @@name-package 2.2.2
	 * @var string
	 */
	private $screen_id;

	/**
	 * The hook suffix for the admin screen, regsitered
	 * to WordPress.
	 *
	 * @since @@name-package 2.2.2
	 * @var string
	 */
	private $admin_screen_base;

	/**
	 * Retrieved data from database, for admin page.
	 *
	 * @since @@name-package 2.2.2
	 * @var array
	 */
	private $data;

	/**
	 * Registered extensions.
	 *
	 * @since @@name-package 2.2.2
	 * @var array
	 */
	private $extensions;

	/**
	 * Constructor.
	 *
	 * @since @@name-package 2.2.2
	 *
	 * @param array $args {
	 *     Admin arguments.
	 *
	 *     @type string $remote_api_url Remote URL to EDD store.
	 *     @type string $item_name      Theme Name.
	 * }
	 */
	function __construct( $args = array() ) {

		$args = wp_parse_args( $args, array(
			'remote_api_url' => 'https://wpjumpstart.com',
			'item_shortname' => get_template(),
			'item_name'      => 'Jump Start',
			'screen_id'      => 'jumpstart-licenses',
		) );

		$this->remote_api_url = $args['remote_api_url'];

		$this->item_shortname = $args['item_shortname'];

		$this->item_name = $args['item_name'];

		$this->screen_id = $args['screen_id'];

		$this->data = array();

		add_action( 'admin_init', array( $this, 'register_extensions' ) );

		add_action( 'admin_init', array( $this, 'register_settings' ) );

		add_action( 'admin_menu', array( $this, 'add_page' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

	}

	/**
	 * Registered extensions.
	 *
	 * @since @@name-package 2.2.2
	 */
	public function register_extensions() {

		/**
		 * Filters the installed extensions for Jump Start.
		 *
		 * These registered extensions will display on the
		 * license admin page so that in-dashboard updates
		 * can be set up for them.
		 *
		 * Extensions plugins can use class Jump_Start_Extension
		 * class to register itself to work with this sytem.
		 *
		 * @since @@name-package 2.2.2
		 */
		$this->extensions = apply_filters( 'jump_start_installed_extensions', array() );

	}

	/**
	 * Register settings.
	 *
	 * @since @@name-package 2.2.2
	 */
	public function register_settings() {

		register_setting(
			$this->screen_id,
			$this->screen_id,
			array( $this, 'save' )
		);

	}

	/**
	 * Check all saved licenses, once per day, when
	 * license admin screen is loaded.
	 *
	 * @since @@name-package 2.2.2
	 *
	 * @param bool $force Whether to bypass cache and force the license check.
	 */
	public function check_licenses( $force = false ) {

		// If a license check was performed in the last day, skip and bail out.
		if ( ! $force && get_transient( 'has_checked_' . $this->item_shortname . '_licenses' ) ) {

			return;

		}

		$settings = array();

		$items = get_option( $this->screen_id );

		if ( $items ) {

			foreach ( $items as $shortname => $item ) {

				if ( $this->is_active( $item ) ) {

					/*
					 * If the license is currently active, we want to do
					 * a remote check on its status, to give the user
					 * feedback on its status.
					 */
					$settings[ $shortname ] = $this->check_license( $item );

				} else {

					/*
					 * If the license isn't currently saved as active,
					 * just pass the data back through without any
					 * remote checks.
   				 	 */
					$settings[ $shortname ] = $item;

				}
			}
		}

		update_option( $this->screen_id, $settings );

		set_transient(
			'has_checked_' . $this->item_shortname . '_licenses',
			true,
			DAY_IN_SECONDS
		);

	}

	/**
	 * Sanitize data and activate licenses.
	 *
	 * @since @@name-package 2.2.2
	 *
	 * @param  array $fields   Submitted form data.
	 * @return array $settings Data to save.
	 */
	public function save( $items ) {

		$settings = array();

		// Are we deactivating a license?
		$deactivate = false;

		foreach ( $items as $key => $value ) {

			if ( false !== strpos( $key, 'deactivate_license_' ) ) {

				$deactivate = str_replace( 'deactivate_license_', '', $key );

				break;

			}
		}

		if ( $deactivate ) {

			$settings = get_option( $this->screen_id );

			if ( ! empty( $settings[ $deactivate ] ) ) {

				$data = $this->deactivate_license( $settings[ $deactivate ] );

				unset( $settings[ $deactivate ] );

				return $settings; // Saving all data from db with deactivated license removed. Ignoring other data submitted through form.

			}
		}

		// If not deactivating a license, save and activate all submitted.
		foreach ( $items as $shortname => $item ) {

			if ( ! empty( $item['key'] ) ) {

				$new_item = array(
					'item_name' => ! empty( $item['item_name'] ) ? esc_html( $item['item_name'] ) : '',
					'key'       => ! empty( $item['key'] ) ? esc_attr( $item['key'] ) : '',
				);

				$data = $this->activate_license( $item );

				if ( $data ) {

					$new_item = array_merge( $data, $new_item );

				}

				$settings[ $shortname ] = $new_item;

			}
		}

		return $settings;

	}

	/**
	 * Activate license.
	 *
	 * @since @@name-package 2.2.2
	 *
	 * @param array $license {
	 *     @type string $item_name Product name at EDD store.
	 *     @type string $key       License key.
	 * }
	 * @param array|bool API response or `false`.
	 */
	private function activate_license( $license ) {

		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license['key'],
			'item_name'  => urlencode( $license['item_name'] ),
			'url'        => home_url(),
		);

		$remote_post_args = array(
			'timeout'   => 20,
			'sslverify' => false,
			'body'      => $api_params,
		);

		$response = wp_remote_post( $this->remote_api_url, $remote_post_args );

		if ( is_wp_error( $response ) ) {

			return false;

		}

		return (array) json_decode( wp_remote_retrieve_body( $response ) );

	}

	/**
	 * Deactivate a license.
	 *
	 * @since @@name-package 2.2.2
	 *
	 * @param array $license {
	 *     @type string $item_name Product name at EDD store.
	 *     @type string $key       License key.
	 * }
	 * @param array|bool API response or `false`.
	 */
	function deactivate_license( $license ) {

		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license['key'],
			'item_name'  => urlencode( $license['item_name'] ),
			'url'        => home_url(),
		);

		$remote_post_args = array(
			'timeout'   => 20,
			'sslverify' => false,
			'body'      => $api_params,
		);

		$response = wp_remote_post( $this->remote_api_url, $remote_post_args );

		if ( is_wp_error( $response ) ) {

			return false;

		}

		return (array) json_decode( wp_remote_retrieve_body( $response ) );

	}

	/**
	 * Check an individual licence.
	 *
	 * @since @@name-package 2.2.2
	 *
	 * @param array $item {
	 *    @type string $item_name Item name.
	 *    @type string $key       License key.
	 * }
	 * @return array $new_item Updated license information to save.
	 */
	public function check_license( $item ) {

		$new_item = array(
			'item_name' => ! empty( $item['item_name'] ) ? esc_html( $item['item_name'] ) : '',
			'key'       => ! empty( $item['key'] ) ? esc_attr( $item['key'] ) : '',
		);

		$api_params = array(
			'edd_action' => 'check_license',
			'license'    => ! empty( $item['key'] ) ? esc_attr( $item['key'] ) : '',
			'item_name'  => ! empty( $item['item_name'] ) ? urlencode( $item['item_name'] ) : '',
			'url'        => home_url(),
		);

		$remote_post_args = array(
			'timeout'   => 20,
			'sslverify' => false,
			'body'      => $api_params,
		);

		$response = wp_remote_post( $this->remote_api_url, $remote_post_args );

		$data = array();

		if ( ! is_wp_error( $response ) ) {

			$data = (array) json_decode( wp_remote_retrieve_body( $response ) );

		}

		if ( $data ) {

			$new_item = array_merge( $data, $new_item );

		}

		return $new_item;

	}

	/**
	 * Add admin page, and hook in specific admin
	 * screen actions.
	 *
	 * @since @@name-package 2.2.2
	 */
	function add_page() {

		if ( themeblvd_supports( 'admin', 'updates' ) && current_user_can( themeblvd_admin_module_cap( 'updates' ) ) ) {

			$this->admin_screen_base = add_theme_page(
				__( 'Theme License', '@@text-domain' ),
				__( 'Theme License', '@@text-domain' ),
				'manage_options',
				$this->screen_id,
				array( $this, 'admin_page' )
			);

			add_action( 'load-' . $this->admin_screen_base, array( $this, 'set_data' ) );

		}
	}

	/**
	 * Display the licence admin.
	 *
	 * @since @@name-package 2.2.2
	 */
	function admin_page() {

		include_once( get_template_directory() . '/inc/admin/partials/licenses.php' );

	}

	/**
	 * Display an individual license input within the
	 * license management admin page.
	 *
	 * @since @@name-package 2.2.2
	 *
	 * @param array $args {
	 *     @type string $item_name      Item name.
	 *     @type string $item_shortname Item slug.
	 * }
	 */
	private function license_row( $args ) {

		$item = $this->get_item( $args['item_shortname'] );

		// Start output.
		echo "<tr>\n";

		printf( "\t<th scope=\"row\">%s</th>\n", esc_html( $args['item_name'] ) );

		echo "\t<td>\n";

		printf(
			"\t\t<input type=\"text\" class=\"regular-text\" name=\"%s[%s][key]\" value=\"%s\" %s>\n",
			esc_attr( $this->screen_id ),
			esc_attr( $args['item_shortname'] ),
			esc_attr( $item['key'] ),
			$this->is_active( $item ) ? 'readonly' : ''
		);

		printf(
			"\t\t<input type=\"hidden\" name=\"%s[%s][item_name]\" value=\"%s\">\n",
			esc_attr( $this->screen_id ),
			esc_attr( $args['item_shortname'] ),
			esc_attr( $args['item_name'] )
		);

		echo "\t\t<label>";

		if ( $this->is_active( $item ) ) {

			printf(
				'<input type="submit" class="button" name="%s" value="%s">',
				esc_attr( $this->screen_id . '[deactivate_license_' . $args['item_shortname'] . ']' ),
				__( 'Deactivate License', '@@text-domain' )
			);

		}

		echo "</label>\n";

		$status = $this->license_status( $item );

		printf( "\t\t<div class=\"license-data %s\">", esc_attr( $status['class'] ) );

		echo "\t\t\t<p>";

		echo themeblvd_kses( $status['message'] );

		echo "</p>\n";

		echo "\t\t</div><!-- .license-data -->\n";

		echo "\t</td>\n";

		echo "</tr>\n";

	}

	/**
	 * Get license info.
	 *
	 * @since @@name-package 2.2.2
	 *
	 * @param  string $item   Product and license info.
	 * @return array  $status {
	 *     License status.
	 *
	 *     @type string $class   CSS class for message.
	 *     @type string $message Text for message.
	 * }
	 */
	private function license_status( $item ) {

		$status = array(
			'class'   => 'license-empty',
			'message' => __( 'The data has not been saved properly.', '@@text-domain' ),
		);

		if ( empty( $item['key'] ) ) {

			$status['class'] = 'license-empty';

			$status['message'] = sprintf(
				// translators: 1. Item name
				__( 'To receive updates, please enter your valid %s license key', '@@text-domain' ),
				$item['item_name']
			);

			return $status;

		}

		if ( ! isset( $item['success'] ) ) {

			return $status;

		}

		if ( false === $item['success'] ) {

			// Handle unsuccessful license activation attempts.
			if ( ! empty( $item['error'] ) ) {

				switch ( $item['error'] ) {

					case 'expired':
						$status['class'] = 'license-expired';

						$status['message'] = sprintf(
							// translators: 1. expiration date, 2. URL to renew license
							__( 'Your license key expired on %1$s. Please <a href="%2$s" target="_blank">renew your license key</a>.', '@@text-domain' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license->expires, current_time( 'timestamp' ) ) ),
							'https://wpjumpstart.com/checkout/?edd_license_key=' . $item['key'] . '&utm_campaign=admin&utm_source=licenses&utm_medium=expired'
						);

						break;

					case 'revoked':
						$status['class'] = 'license-error';

						$status['message'] = sprintf(
							// translators: 1. URL to item support
							__( 'Your license key has been disabled. Please <a href="%s" target="_blank">contact support</a> for more information.', '@@text-domain' ),
							'https://wpjumpstart.com/support'
						);

						break;

					case 'missing':
						$status['class'] = 'license-error';

						$status['message'] = sprintf(
							// translators: 1. URL to account login
							__( 'Invalid license. Please <a href="%s" target="_blank">visit your account page</a> and verify it.', '@@text-domain' ),
							'https://wpjumpstart.com/customer-dashboard/?utm_campaign=admin&utm_source=licenses&utm_medium=missing'
						);

						break;

					case 'invalid':
					case 'site_inactive':
						$status['class'] = 'license-error';

						$status['message'] = sprintf(
							// translators: 1. item name, 2. URL to account login
							__( 'Your %1$s is not active for this URL. Please <a href="%2$s" target="_blank">visit your account page</a> to manage your license key URLs.', '@@text-domain' ),
							esc_html( $item['item_name'] ),
							'https://wpjumpstart.com/customer-dashboard?utm_campaign=admin&utm_source=licenses&utm_medium=invalid'
						);

						break;

					case 'item_name_mismatch':
						$status['class'] = 'license-error';

						$status['message'] = sprintf(
							// translators: 1. item name
							__( 'This appears to be an invalid license key for %s.', '@@text-domain' ),
							esc_html( $item['item_name'] )
						);

						break;

					case 'no_activations_left':
						$status['class'] = 'license-error';

						$status['message'] = sprintf(
							// translators: 1. URL to customer dashboard
							__( 'Your license key has reached its activation limit. <a href="%s">View possible upgrades</a> now.', '@@text-domain' ),
							'https://wpjumpstart.com/customer-dashboard?utm_campaign=admin&utm_source=licenses&utm_medium=no_activations_left'
						);

						break;

					case 'license_not_activable':
						$status['class'] = 'license-error';

						$status['message'] = __( 'The key you entered belongs to a bundle, please use the product specific license key.', '@@text-domain' );

						break;

					default:
						$status['class'] = 'license-error';

						$error = ! empty( $license->error ) ? $license->error : __( 'unknown_error', '@@text-domain' );

						$status['message'] = sprintf(
							// translators: 1. license error, 2. link to item support
							__( 'There was an error with this license key: %1$s. Please <a href="%2$s">contact our support team</a>.', '@@text-domain' ),
							$error,
							'https://wpjumpstart.com/support?utm_campaign=admin&utm_source=licenses&utm_medium=' . $error
						);

						break;

				}
			}
		} else {

			// Handle successful license activation.
			if ( ! empty( $item['license'] ) && 'valid' == $item['license'] && ! empty( $item['expires'] ) ) {

				$status['class'] = 'license-valid';

				if ( 'lifetime' === $item['expires'] ) {

					$status['message'] = __( 'License key never expires.', '@@text-domain' );

				} else {

					$now = current_time( 'timestamp' );

					$expiration = strtotime( $item['expires'], $now );

					if ( $expiration > $now && $expiration - $now < ( DAY_IN_SECONDS * 30 ) ) {

						$status['message'] = sprintf(
							// translators: 1. license expiration date, 2. url to renew license
							__( 'Your license key expires soon! It expires on %1$s. <a href="%2$s" target="_blank">Renew your license key</a>.', '@@text-domain' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license->expires, current_time( 'timestamp' ) ) ),
							'https://wpjumpstart.com/checkout/?edd_license_key=' . $item['key'] . '&utm_campaign=admin&utm_source=licenses&utm_medium=renew'
						);

					} else {

						$status['message'] = sprintf(
							// translators: 1. license expiration date
							__( 'Your license key expires on %s.', '@@text-domain' ),
							date_i18n( get_option( 'date_format' ), $expiration )
						);

					}
				}
			}
		}

		return $status;

	}

	/**
	 * Retrieve stored data for admin page.
	 *
	 * @since @@name-package 2.2.2
	 */
	public function set_data() {

		$this->check_licenses();

		$this->data = get_option( $this->screen_id );

	}

	/**
	 * Enqueue assets for admin page.
	 *
	 * @since @@name-package 2.2.2
	 */
	public function assets() {

		if ( $this->is_admin_screen() ) {

			$suffix = themeblvd_script_debug() ? '' : '.min';

			wp_enqueue_style(
				$this->screen_id,
				esc_url( get_template_directory_uri() . "/inc/admin/assets/css/license-admin{$suffix}.css" )
			);

		}

	}

	/**
	 * Get saved data for an individual license item.
	 *
	 * @since @@name-package 2.2.2
	 *
	 * @param  string $shortname Item shortname.
	 * @return array  $item      Saved item data.
	 */
	private function get_item( $shortname ) {

		$item = array(
			'item_name' => '',
			'key'       => '',
		);

		if ( ! empty( $this->data[ $shortname ] ) ) {

			$item = wp_parse_args(
				$this->data[ $shortname ],
				$item
			);

		}

		return $item;

	}

	/**
	 * Checks if this is currently our license
	 * management admin screen.
	 *
	 * @since @@name-package 2.2.2
	 *
	 * @return bool Whether it's our admin screen.
	 */
	private function is_admin_screen() {

		$screen = get_current_screen();

		if ( $this->admin_screen_base == $screen->base ) {

			return true;

		}

		return false;

	}

	/**
	 * Checks if a license is active, based on
	 * saved data. No remote request here.
	 *
	 * @since @@name-package 2.2.2
	 *
	 * @param  array $item Saved license info and data.
	 * @return bool        Whether it's our admin screen.
	 */
	private function is_active( $item ) {

		if ( ! empty( $item['license'] ) && 'valid' == $item['license'] ) {

			return true;

		}

		return false;

	}

	/**
	 * Get ID you to save to WordPress options table.
	 *
	 * @since @@name-package 2.2.2
	 *
	 * @return string Settings ID.
	 */
	public function get_settings_id() {

		return $this->screen_id;

	}

	/**
	 * Get item name.
	 *
	 * @since @@name-package 2.2.2
	 *
	 * @return string Item name.
	 */
	public function get_item_name() {

		return $this->item_name;

	}

	/**
	 * Get item shortname.
	 *
	 * @since @@name-package 2.2.2
	 *
	 * @return string Item shortname.
	 */
	public function get_item_shortname() {

		return $this->item_shortname;

	}

}
