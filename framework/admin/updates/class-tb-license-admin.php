<?php
/**
 * Setup admin page to manage license key for
 * automatic updates from Theme Blvd servers.
 * This only applies to Theme Blvd themes sold
 * from Theme Blvd-hosted websites.
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

		if ( isset( $_POST['themeblvd_license_activate'] ) ) {

		 	if ( ! check_admin_referer( 'themeblvd_license_nonce', 'themeblvd_license_nonce' ) )
				return; // get out if we didn't click the Activate button

			global $wp_version;

			$license = trim( get_option( 'themeblvd_license_key' ) );

			$api_params = array(
				'edd_action' 	=> 'activate_license',
				'license' 		=> $license,
				'item_name' 	=> urlencode( $this->item_name )
			);

			$response = wp_remote_get( add_query_arg( $api_params, $this->remote_api_url ) );

			if ( is_wp_error( $response ) )
				return false;

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "active" or "inactive"

			if ( isset( $license_data->license ) )
				update_option( 'themeblvd_license_key_status', $license_data->license );

		}
	}

	/**
	 * Add admin page.
	 */
	function add_page() {
		if ( themeblvd_supports('admin', 'updates') && current_user_can( themeblvd_admin_module_cap( 'updates' ) ) )
			add_theme_page( __( 'Theme License', 'themeblvd' ), __( 'Theme License', 'themeblvd' ), 'manage_options', 'themeblvd-license', array( $this, 'admin_page' ) );
	}

	/**
	 * Display Admin page.
	 */
	function admin_page() {
		$license = get_option( 'themeblvd_license_key' );
		$status = get_option( 'themeblvd_license_key_status' );
		?>
		<div class="wrap">
			<h2><?php _e('Theme License', 'themeblvd'); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields('themeblvd_license'); ?>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('License Key', 'themeblvd'); ?>
							</th>
							<td>
								<input id="themeblvd_license_key" name="themeblvd_license_key" type="text" class="regular-text" value="<?php esc_attr( $license ); ?>" />
								<label class="description" for="themeblvd_license_key"><?php _e('Enter your license key', 'themeblvd'); ?></label>
							</td>
						</tr>
						<?php if ( $license ) : ?>
							<tr valign="top">
								<th scope="row" valign="top">
									<?php _e( 'Activate License', 'themeblvd' ); ?>
								</th>
								<td>
									<?php if ( $status !== false && $status == 'valid' ) : ?>
										<span style="color:green;"><?php _e( 'active', 'themeblvd' ); ?></span>
									<?php else : ?>
										<?php wp_nonce_field( 'themeblvd_license_nonce', 'themeblvd_license_nonce' ); ?>
										<input type="submit" class="button-secondary" name="themeblvd_license_activate" value="<?php _e( 'Activate License', 'themeblvd' ); ?>"/>
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
