<?php
/**
 * Add options to WP edit user profile
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_User_Options {

	/**
	 * A single instance of this class.
	 *
	 * @since 2.5.0
	 */
	private static $instance = null;

	/**
     * Creates or returns an instance of this class.
     *
     * @since 2.5.0
     *
     * @return Theme_Blvd_User_Options A single instance of this class.
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
	 * @since 2.5.0
	 */
	public function __construct() {

		if ( is_admin() ) {

			// Contact options
			add_filter( 'user_contactmethods', array($this, 'contact') );

			// Display options on user profile edit
			add_action( 'show_user_profile', array($this, 'display') );
			add_action( 'edit_user_profile', array($this, 'display') );

			// Save author meta data for user profiles
			add_action( 'personal_options_update', array($this, 'save') );
			add_action( 'edit_user_profile_update', array($this, 'save') );

		}

	}

	/**
	 * Get icons
	 *
	 * @since 2.5.0
	 */
	public static function get_icons() {
		return apply_filters('themeblvd_contact_methods', array(
			/*
			'email'	=> array(
				'label'		=> themeblvd_get_local('email'),
				'option'	=> null,
				'key'		=> 'email'
			),
			*/
			'website'	=> array(
				'label'		=> '[url]',
				'option'	=> null,
				'key'		=> 'url'
			),
			'facebook'	=> array(
				'label'		=> themeblvd_get_local('contact_facebook'),
				'option'	=> __('Facebook URL', 'themeblvd'),
				'key'		=> '_tb_contact_facebook'
			),
			'google'	=> array(
				'label'		=> themeblvd_get_local('contact_gplus'),
				'option'	=> __('Google+ URL', 'themeblvd'),
				'key'		=> '_tb_contact_google'
			),
			'linkedin'	=> array(
				'label'		=> themeblvd_get_local('contact_linkedin'),
				'option'	=> __('LinkedIn URL', 'themeblvd'),
				'key'		=> '_tb_contact_linkedin'
			),
			'pinterest'	=> array(
				'label'		=> themeblvd_get_local('contact_pinterest'),
				'option'	=> __('Pinterest URL', 'themeblvd'),
				'key'		=> '_tb_contact_pinterest'
			),
			'twitter'	=> array(
				'label'		=> themeblvd_get_local('contact_twitter'),
				'option'	=> __('Twitter URL', 'themeblvd'),
				'key'		=> '_tb_contact_twitter'
			)
		));
	}

	/**
	 * Display options on user profile edit
	 *
	 * @since 2.5.0
	 */
	public function contact( $methods ) {

		$icons = self::get_icons();

		$add = array();

		if ( $icons ) {
			foreach ( $icons as $icon ) {
				if ( $icon['option'] ) {
					$add[$icon['key']] = $icon['option'];
				}
			}
		}

		return array_merge( $methods, $add );
	}

	/**
	 * Display options on user profile edit
	 *
	 * @since 2.5.0
	 */
	public function display( $user ) {

		// Sidebar Layouts
		$layouts = themeblvd_sidebar_layouts();
		$select_layouts = array('default' => __('Default', 'themeblvd'));

		foreach ( $layouts as $layout ) {
			$select_layouts[$layout['id']] = $layout['name'];
		}

		// Post Display modes
		$select_modes = array_merge( array('default' =>__('Default', 'themeblvd')), themeblvd_get_modes() );

		// Values
		$box_single = get_user_meta($user->ID, '_tb_box_single', true);

		if ( $box_single !== '1' && $box_single !== '0' ) {
			$box_single = '0'; // default
		}

		$box_archive = get_user_meta($user->ID, '_tb_box_archive', true);

		if ( $box_archive !== '1' && $box_archive !== '0' ) {
			$box_archive = '0'; // default
		}

		$box_email = get_user_meta($user->ID, '_tb_box_email', true);

		if ( $box_email !== '1' && $box_email !== '0' ) {
			$box_email = '0'; // default
		}

		$box_archive_link = get_user_meta($user->ID, '_tb_box_archive_link', true);

		if ( $box_archive_link !== '1' && $box_archive_link !== '0' ) {
			$box_archive_link = '0'; // default
		}

		$box_icons = get_user_meta($user->ID, '_tb_box_icons', true);
		$archive_layout = get_user_meta($user->ID, '_tb_sidebar_layout', true);
		$archive_mode = get_user_meta($user->ID, '_tb_archive_mode', true);
		?>
		<h3><?php esc_html_e('Author Box', 'themeblvd'); ?></h3>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row" valign="top"><?php esc_html_e('Display', 'themeblvd'); ?></th>
					<td>
						<label for="_tb_box_single">
							<input id="_tb_box_single" name="_tb_box_single" type="checkbox" value="1" <?php checked($box_single, '1'); ?> />
							<?php esc_html_e('Display Author Box on user\'s posts', 'themeblvd'); ?>
						</label><br>
						<label for="_tb_box_archive">
							<input id="_tb_box_archive" name="_tb_box_archive" type="checkbox" value="1" <?php checked($box_archive, '1'); ?> />
							<?php esc_html_e('Display Author Box on user\'s archive', 'themeblvd'); ?>
						</label><br>
						<label for="_tb_box_email">
							<input id="_tb_box_archive" name="_tb_box_email" type="checkbox" value="1" <?php checked($box_email, '1'); ?> />
							<?php esc_html_e('Display email icon in Author Box', 'themeblvd'); ?>
						</label><br>
						<label for="_tb_box_archive_link">
							<input id="_tb_box_archive_link" name="_tb_box_archive_link" type="checkbox" value="1" <?php checked($box_archive_link, '1'); ?> />
							<?php esc_html_e('Display link to user\'s archive in Author Box (on single posts)', 'themeblvd'); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th><label for="_tb_box_icons"><?php esc_html_e('Icon Color', 'themeblvd'); ?></label></th>
					<td>
						<select name="_tb_box_icons" id="_tb_box_icons">
							<option value="flat" <?php selected($box_icons, 'flat'); ?>><?php esc_html_e('Flat Color', 'themeblvd'); ?></option>
							<option value="dark" <?php selected($box_icons, 'dark'); ?>><?php esc_html_e('Flat Dark', 'themeblvd'); ?></option>
							<option value="grey" <?php selected($box_icons, 'grey'); ?>><?php esc_html_e('Flat Grey', 'themeblvd'); ?></option>
							<option value="light" <?php selected($box_icons, 'light'); ?>><?php esc_html_e('Flat Light', 'themeblvd'); ?></option>
							<option value="color" <?php selected($box_icons, 'color'); ?>><?php esc_html_e('Color', 'themeblvd'); ?></option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>

		<h3><?php esc_html_e('Author Archives', 'themeblvd'); ?></h3>

		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="_tb_sidebar_layout"><?php esc_html_e('Sidebar Layout', 'themeblvd'); ?></label></th>
					<td>
						<select name="_tb_sidebar_layout" id="_tb_sidebar_layout">
							<?php foreach ( $select_layouts as $key => $value ) : ?>
								<option value="<?php echo $key; ?>" <?php selected($archive_layout, $key); ?>><?php echo esc_html($value); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="_tb_archive_mode"><?php esc_html_e('Post Display', 'themeblvd'); ?></label></th>
					<td>
						<select name="_tb_archive_mode" id="_tb_archive_mode">
							<?php foreach ( $select_modes as $key => $value ) : ?>
								<option value="<?php echo $key; ?>" <?php selected($archive_mode, $key); ?>><?php echo esc_html($value); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Save author meta data for user profiles
	 *
	 * @since 2.5.0
	 */
	public function save( $user_id ) {

		global $_POST;

		// Checboxes, need to take action if empty
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

		// <select> options
		if ( ! empty( $_POST['_tb_box_icons'] ) && in_array( $_POST['_tb_box_icons'], array('flat', 'dark', 'grey', 'light', 'color') ) ) {
			update_user_meta( $user_id, '_tb_box_icons', $_POST['_tb_box_icons'] );
		}

		if ( ! empty( $_POST['_tb_sidebar_layout'] ) ) {
			update_user_meta( $user_id, '_tb_sidebar_layout', wp_kses($_POST['_tb_sidebar_layout'], array()) );
		}

		if ( ! empty( $_POST['_tb_archive_mode'] ) ) {
			update_user_meta( $user_id, '_tb_archive_mode', wp_kses($_POST['_tb_archive_mode'], array()) );
		}

	}

}
