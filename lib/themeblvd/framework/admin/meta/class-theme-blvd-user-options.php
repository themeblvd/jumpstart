<?php
/**
 * Profile Options
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     @@name-package
 */

/**
 * Adds options to the WordPress user profile edit
 * screen, mainly for the purpse of configuring the
 * author box and how author archives display.
 *
 * This is a singleton class, to be used once.
 *
 * @since @@name-framework 2.5.0
 *
 * @see themeblvd_admin_init()
 */
class Theme_Blvd_User_Options {

	/**
	 * A single instance of this class.
	 *
	 * @since @@name-framework 2.5.0
	 */
	private static $instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @return Theme_Blvd_User_Options A single instance of this class.
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
	 * @since @@name-framework 2.5.0
	 */
	public function __construct() {

		if ( is_admin() ) {

			// Contact options.
			add_filter( 'user_contactmethods', array( $this, 'contact' ) );

			// Display options on user profile edit.
			add_action( 'show_user_profile', array( $this, 'display' ) );
			add_action( 'edit_user_profile', array( $this, 'display' ) );

			// Save author meta data for user profiles.
			add_action( 'personal_options_update', array( $this, 'save' ) );
			add_action( 'edit_user_profile_update', array( $this, 'save' ) );

		}

	}

	/**
	 * Get social sharing icon options that we're adding
	 * to user profile editing.
	 *
	 * @since @@name-framework 2.5.0
	 */
	public static function get_icons() {

		/**
		 * Filters the contact fields added to the
		 * Edit Profile screen of the WordPress admin.
		 *
		 * @since @@name-framework 2.5.0
		 *
		 * @param array Contact fields to be added.
		 */
		return apply_filters( 'themeblvd_contact_methods', array(
			/*
			'email' => array(
				'label'     => themeblvd_get_local('email'),
				'option'    => null,
				'key'       => 'email'
			),
			*/
			'website' => array(
				'label'  => '[url]',
				'option' => null,
				'key'    => 'url',
			),
			'facebook' => array(
				'label'  => themeblvd_get_local( 'contact_facebook' ),
				'option' => __( 'Facebook URL', '@@text-domain' ),
				'key'    => '_tb_contact_facebook',
			),
			'google' => array(
				'label'  => themeblvd_get_local( 'contact_gplus' ),
				'option' => __( 'Google+ URL', '@@text-domain' ),
				'key'    => '_tb_contact_google',
			),
			'linkedin' => array(
				'label'  => themeblvd_get_local( 'contact_linkedin' ),
				'option' => __( 'LinkedIn URL', '@@text-domain' ),
				'key'    => '_tb_contact_linkedin',
			),
			'pinterest' => array(
				'label'  => themeblvd_get_local( 'contact_pinterest' ),
				'option' => __( 'Pinterest URL', '@@text-domain' ),
				'key'    => '_tb_contact_pinterest',
			),
			'twitter' => array(
				'label'  => themeblvd_get_local( 'contact_twitter' ),
				'option' => __( 'Twitter URL', '@@text-domain' ),
				'key'    => '_tb_contact_twitter',
			),
		));

	}

	/**
	 * Display options on user profile edit.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param  array $methods Current contact options (methods) WordPress has on Edit Profile page.
	 * @return array          Modified contact options, with our's added.
	 */
	public function contact( $methods ) {

		$icons = self::get_icons();

		$add = array();

		if ( $icons ) {

			foreach ( $icons as $icon ) {

				if ( $icon['option'] ) {

					$add[ $icon['key'] ] = $icon['option'];

				}
			}
		}

		return array_merge( $methods, $add );

	}

	/**
	 * Display options on user profile edit.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param WP_User $user Current user profile is being edited for.
	 */
	public function display( $user ) {

		/*
		 * Get helper data for options array.
		 */

		$layouts = themeblvd_sidebar_layouts();

		$select_layouts = array(
			'default' => __( 'Default', '@@text-domain' ),
		);

		foreach ( $layouts as $layout ) {
			$select_layouts[ $layout['id'] ] = $layout['name'];
		}

		$select_modes = array_merge(
			array(
				'default' => __( 'Default', '@@text-domain' ),
			),
			themeblvd_get_modes()
		);

		/*
		 * Determine values to use in form fields. And for each
		 * variable, we'll assign a default value if the current
		 * setting isn't saved.
		 */

		$box_single = get_user_meta( $user->ID, '_tb_box_single', true );

		if ( '1' !== $box_single && '0' !== $box_single ) {

			$box_single = '0'; // Default value.

		}

		$box_archive = get_user_meta( $user->ID, '_tb_box_archive', true );

		if ( '1' !== $box_archive && '0' !== $box_archive ) {

			$box_archive = '0'; // Default value.

		}

		$box_email = get_user_meta( $user->ID, '_tb_box_email', true );

		if ( '1' !== $box_email && '0' !== $box_email ) {

			$box_email = '0'; // Default value.

		}

		$box_archive_link = get_user_meta( $user->ID, '_tb_box_archive_link', true );

		if ( '1' !== $box_archive_link && '0' !== $box_archive_link ) {

			$box_archive_link = '0'; // Default value.

		}

		$box_icons = get_user_meta( $user->ID, '_tb_box_icons', true );

		$archive_layout = get_user_meta( $user->ID, '_tb_sidebar_layout', true );

		$archive_mode = get_user_meta( $user->ID, '_tb_archive_mode', true );

		?>
		<h3><?php esc_html_e( 'Author Box', '@@text-domain' ); ?></h3>

		<table class="form-table">
			<tbody>

				<tr>

					<th scope="row" valign="top">
						<?php esc_html_e( 'Display', '@@text-domain' ); ?>
					</th>

					<td>
						<label for="_tb_box_single">
							<input id="_tb_box_single" name="_tb_box_single" type="checkbox" value="1" <?php checked( $box_single, '1' ); ?> />
							<?php esc_html_e( 'Display Author Box on user\'s posts', '@@text-domain' ); ?>
						</label><br>
						<label for="_tb_box_archive">
							<input id="_tb_box_archive" name="_tb_box_archive" type="checkbox" value="1" <?php checked( $box_archive, '1' ); ?> />
							<?php esc_html_e( 'Display Author Box on user\'s archive', '@@text-domain' ); ?>
						</label><br>
						<label for="_tb_box_email">
							<input id="_tb_box_archive" name="_tb_box_email" type="checkbox" value="1" <?php checked( $box_email, '1' ); ?> />
							<?php esc_html_e( 'Display email icon in Author Box', '@@text-domain' ); ?>
						</label><br>
						<label for="_tb_box_archive_link">
							<input id="_tb_box_archive_link" name="_tb_box_archive_link" type="checkbox" value="1" <?php checked( $box_archive_link, '1' ); ?> />
							<?php esc_html_e( 'Display link to user\'s archive in Author Box (on single posts)', '@@text-domain' ); ?>
						</label>
					</td>

				</tr>

				<tr>

					<th>
						<label for="_tb_box_icons">
							<?php esc_html_e( 'Icon Color', '@@text-domain' ); ?>
						</label>
					</th>

					<td>
						<select name="_tb_box_icons" id="_tb_box_icons">
							<option value="grey" <?php selected( $box_icons, 'grey' ); ?>><?php esc_html_e( 'Flat Grey', '@@text-domain' ); ?></option>
							<option value="dark" <?php selected( $box_icons, 'dark' ); ?>><?php esc_html_e( 'Flat Dark', '@@text-domain' ); ?></option>
							<option value="light" <?php selected( $box_icons, 'light' ); ?>><?php esc_html_e( 'Flat Light', '@@text-domain' ); ?></option>
							<option value="flat" <?php selected( $box_icons, 'flat' ); ?>><?php esc_html_e( 'Flat Color', '@@text-domain' ); ?></option>
							<option value="color" <?php selected( $box_icons, 'color' ); ?>><?php esc_html_e( 'Color', '@@text-domain' ); ?></option>
						</select>
					</td>

				</tr>

			</tbody>
		</table>

		<h3><?php esc_html_e( 'Author Archives', '@@text-domain' ); ?></h3>

		<table class="form-table">
			<tbody>

				<tr>

					<th>
						<label for="_tb_sidebar_layout">
							<?php esc_html_e( 'Sidebar Layout', '@@text-domain' ); ?>
						</label>
					</th>

					<td>
						<select name="_tb_sidebar_layout" id="_tb_sidebar_layout">
							<?php foreach ( $select_layouts as $key => $value ) : ?>
								<option value="<?php echo $key; ?>" <?php selected( $archive_layout, $key ); ?>><?php echo esc_html( $value ); ?></option>
							<?php endforeach; ?>
						</select>
					</td>

				</tr>

				<tr>

					<th><label for="_tb_archive_mode"><?php esc_html_e( 'Post Display', '@@text-domain' ); ?></label></th>

					<td>
						<select name="_tb_archive_mode" id="_tb_archive_mode">
							<?php foreach ( $select_modes as $key => $value ) : ?>
								<option value="<?php echo $key; ?>" <?php selected( $archive_mode, $key ); ?>><?php echo esc_html( $value ); ?></option>
							<?php endforeach; ?>
						</select>
					</td>

				</tr>

			</tbody>
		</table>
		<?php
	}

	/**
	 * Save author meta data for user profiles.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param int $user_id User ID of user we're saving meta data for.
	 */
	public function save( $user_id ) {

		global $_POST;

		/*
		 * For all checkboxes, we'll still save a value of
		 * string '0', if the checkbox wasn't checked, and
		 * if it was we'll save a string '1'.
		 *
		 * This is meant to emulate how the options system in
		 * the framework handles checkbpxes, for a consistent
		 * experience on the frontend, when retrieving the values.
		 */
		if ( empty( $_POST['_tb_box_single'] ) ) {
			update_user_meta( $user_id, '_tb_box_single', '0' );
		} else {
			update_user_meta( $user_id, '_tb_box_single', '1' );
		}

		if ( empty( $_POST['_tb_box_archive'] ) ) {
			update_user_meta( $user_id, '_tb_box_archive', '0' );
		} else {
			update_user_meta( $user_id, '_tb_box_archive', '1' );
		}

		if ( empty( $_POST['_tb_box_email'] ) ) {
			update_user_meta( $user_id, '_tb_box_email', '0' );
		} else {
			update_user_meta( $user_id, '_tb_box_email', '1' );
		}

		if ( empty( $_POST['_tb_box_archive_link'] ) ) {
			update_user_meta( $user_id, '_tb_box_archive_link', '0' );
		} else {
			update_user_meta( $user_id, '_tb_box_archive_link', '1' );
		}

		/*
		 * Save all <select> menu settings.
		 */
		if ( ! empty( $_POST['_tb_box_icons'] ) && in_array( $_POST['_tb_box_icons'], array( 'flat', 'dark', 'grey', 'light', 'color' ) ) ) {
			update_user_meta( $user_id, '_tb_box_icons', $_POST['_tb_box_icons'] );
		}

		if ( ! empty( $_POST['_tb_sidebar_layout'] ) ) {
			update_user_meta( $user_id, '_tb_sidebar_layout', wp_kses( $_POST['_tb_sidebar_layout'], array() ) );
		}

		if ( ! empty( $_POST['_tb_archive_mode'] ) ) {
			update_user_meta( $user_id, '_tb_archive_mode', wp_kses( $_POST['_tb_archive_mode'], array() ) );
		}

	}

}
