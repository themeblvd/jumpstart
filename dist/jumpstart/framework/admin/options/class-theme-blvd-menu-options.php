<?php
/**
 * Custom Menu Options
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.3.0
 */

/**
 * Add options to Appearance > Menus.
 *
 * @since Theme_Blvd 2.3.0
 */
class Theme_Blvd_Menu_Options {

	/**
	 * A single instance of this class.
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var Theme_Blvd_Menu_Options
	 */
	private static $instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @return Theme_Blvd_Menu_Options A single instance of this class.
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
	public function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'walker' ) );

		add_action( 'wp_update_nav_menu_item', array( $this, 'save' ), 10, 3 );

	}

	/**
	 * Menus admin page scripts and styles.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string $hook Admin page hook passed from WP's `admin_enqueue_scripts`.
	 */
	public function assets( $hook ) {

		if ( 'nav-menus.php' === $hook ) {

			wp_enqueue_style(
				'themeblvd_menus', esc_url( TB_FRAMEWORK_URI . '/admin/assets/css/menu.css' ),
				null,
				TB_FRAMEWORK_VERSION
			);

			wp_enqueue_script(
				'themeblvd_menus',
				esc_url( TB_FRAMEWORK_URI . '/admin/assets/js/menu.js' ),
				array( 'jquery' ),
				TB_FRAMEWORK_VERSION
			);

		}

	}

	/**
	 * Include an extended version of WP's Walker_Nav_Menu_Edit
	 * and apply it.
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	public function walker() {

		/**
		 * Include our child class to extend WordPress's
		 * Walker_Nav_Menu_Edit class, after it's been included
		 * by WordPress core.
		 */
		include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/class-theme-blvd-nav-menu-edit.php' );

		/*
		 * Point the WordPress menu builder at Appearance > Menus
		 * to use our child class.
		 */
		return 'Theme_Blvd_Nav_Menu_Edit';

	}

	/**
	 * Save the options we've added to the menu builder.
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	public function save( $menu_id, $item_id, $args ) {

		global $_POST;

		if ( empty( $_POST['_tb_mega_menu'][ $item_id ] ) ) {
			update_post_meta( $item_id, '_tb_mega_menu', '0' );
		} else {
			update_post_meta( $item_id, '_tb_mega_menu', '1' );
		}

		if ( empty( $_POST['_tb_mega_menu_hide_headers'][ $item_id ] ) ) {
			update_post_meta( $item_id, '_tb_mega_menu_hide_headers', '0' );
		} else {
			update_post_meta( $item_id, '_tb_mega_menu_hide_headers', '1' );
		}

		if ( empty( $_POST['_tb_bold'][ $item_id ] ) ) {
			update_post_meta( $item_id, '_tb_bold', '0' );
		} else {
			update_post_meta( $item_id, '_tb_bold', '1' );
		}

		if ( empty( $_POST['_tb_deactivate_link'][ $item_id ] ) ) {
			update_post_meta( $item_id, '_tb_deactivate_link', '0' );
		} else {
			update_post_meta( $item_id, '_tb_deactivate_link', '1' );
		}

		if ( empty( $_POST['_tb_placeholder'][ $item_id ] ) ) {
			update_post_meta( $item_id, '_tb_placeholder', '0' );
		} else {
			update_post_meta( $item_id, '_tb_placeholder', '1' );
		}

	}
}
