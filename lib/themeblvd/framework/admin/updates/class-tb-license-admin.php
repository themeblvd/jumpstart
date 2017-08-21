<?php
/**
 * Setup admin page to manage license key for
 * automatic updates from Theme Blvd servers.
 * This only applies to Theme Blvd themes sold
 * from Theme Blvd-hosted websites.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     @@name-package
 */
class Theme_Blvd_License_Admin {

	private $remote_api_url;
	private $item_name;

	/**
	 * Constructor
	 */
	function __construct( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'remote_api_url' 	=> 'http://wpjumpstart.com',
			'item_name'			=> ''
		) );

		$this->remote_api_url = $args['remote_api_url'];
		$this->item_name = $args['item_name'];

		add_action('admin_menu', array( $this, 'add_page' ) );
		add_action('admin_init', array( $this, 'register_settings' ) );
		add_action('admin_init', array( $this, 'activate_license') );
	}

	/**
	 * Register settings.
	 */
	function register_settings() {
		register_setting( 'themeblvd_license', 'themeblvd_license_key', array( $this, 'sanitize_license' ) );
	}

	/**
	 * License sanitization.
	 */
	function sanitize_license( $new ) {
		$old = get_option( 'themeblvd_license_key' );
		if ( $old && $old != $new ) {
			delete_option( 'themeblvd_license_key_status' ); // new license has been entered, so must reactivate
		}
		return $new;
	}

	/**
	 * Activate license.
	 */
	function activate_license() {

		/*
		 * @TODO Later, when this is all redone -- When remotely activating
		 * license key, we'll need to add the following at wpjumpstart.com:
		 * define( 'EDD_BYPASS_NAME_CHECK', true );
		 * ... so we can activate licenses for multiple products, w/out
		 * having to update the theme with the product names.
		 */

		if ( isset( $_POST['themeblvd_activate_license'] ) ) {

			check_admin_referer( 'themeblvd_license-options' );

			global $wp_version;

			$license = trim( get_option( 'themeblvd_license_key' ) );

			$api_params = array(
				'edd_action'	=> 'activate_license',
				'license'		=> $license,
				'item_name'		=> urlencode( $this->item_name )
			);

			$remote_post_args = apply_filters( 'themeblvd_update_remote_post_args', array(
				'timeout'		=> 20,
				'sslverify'		=> false,
				'body'			=> $api_params
			));

			$response = wp_remote_post( $this->remote_api_url, $remote_post_args );

			if ( is_wp_error( $response ) ) {
				return false;
			}

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "active" or "inactive"

			if ( isset( $license_data->license ) ) {
				update_option( 'themeblvd_license_key_status', $license_data->license );
			}

		}
	}

	/**
	 * Add admin page.
	 */
	function add_page() {
		if ( themeblvd_supports('admin', 'updates') && current_user_can( themeblvd_admin_module_cap( 'updates' ) ) ) {
			add_theme_page( __('Theme License', '@@text-domain'), __('Theme License', '@@text-domain'), 'manage_options', 'themeblvd-license', array( $this, 'admin_page' ) );
		}
	}

	/**
	 * Display Admin page.
	 */
	function admin_page() {
		$license = get_option( 'themeblvd_license_key' );
		$status = get_option( 'themeblvd_license_key_status' );
		?>
		<div class="wrap">
			<h2><?php esc_html_e('Theme License', '@@text-domain'); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields('themeblvd_license'); ?>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php esc_html_e('License Key', '@@text-domain'); ?>
							</th>
							<td>
								<input id="themeblvd_license_key" name="themeblvd_license_key" type="text" class="regular-text" value="<?php echo esc_attr( $license ); ?>" />
								<label class="description" for="themeblvd_license_key"><?php esc_html_e('Enter your license key', '@@text-domain'); ?></label>
							</td>
						</tr>
						<?php if ( $license ) : ?>
							<tr valign="top">
								<th scope="row" valign="top">
									<?php esc_html_e( 'Activate License', '@@text-domain' ); ?>
								</th>
								<td>
									<?php if ( $status !== false && $status == 'valid' ) : ?>
										<span style="color:green;"><?php esc_html_e( 'active', '@@text-domain' ); ?></span>
									<?php else : ?>
										<input type="submit" class="button-secondary" name="themeblvd_activate_license" value="<?php esc_html_e( 'Activate License', '@@text-domain' ); ?>"/>
									<?php endif; ?>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
				<?php submit_button(); ?>
			</form>
		<?php
	}
}
