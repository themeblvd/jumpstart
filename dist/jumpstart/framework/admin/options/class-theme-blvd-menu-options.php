<?php
/**
 * Add options to Appearance > Menus.
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     Jump_Start
 */
class Theme_Blvd_Menu_Options {

	/*--------------------------------------------*/
	/* Properties, private
	/*--------------------------------------------*/

	/**
	 * A single instance of this class.
	 *
	 * @since 2.3.0
	 */
	private static $instance = null;

	/*--------------------------------------------*/
	/* Constructor
	/*--------------------------------------------*/

	/**
     * Creates or returns an instance of this class.
     *
     * @since 2.3.0
     *
     * @return Theme_Blvd_Menu_Options A single instance of this class.
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
	public function __construct() {

		add_action( 'admin_enqueue_scripts', array($this, 'assets') );
		add_filter( 'wp_edit_nav_menu_walker', array($this, 'walker') );
		add_action( 'wp_update_nav_menu_item', array($this, 'save'), 10, 3 );

	}

	/*--------------------------------------------*/
	/* Methods
	/*--------------------------------------------*/

	/**
	 * Menus Admin page scripts and styles
	 *
	 * @since 2.5.0
	 */
	public function assets( $hook ) {
		if ( $hook == 'nav-menus.php' ) {
			wp_enqueue_style( 'themeblvd_menus', esc_url( TB_FRAMEWORK_URI . '/admin/assets/css/menu.css' ), null, TB_FRAMEWORK_VERSION );
			wp_enqueue_script( 'themeblvd_menus', esc_url( TB_FRAMEWORK_URI . '/admin/assets/js/menu.js' ), array('jquery'), TB_FRAMEWORK_VERSION );
		}
	}

	/**
	 * Include an extended version of WP's Walker_Nav_Menu_Edit
	 * and apply it.
	 *
	 * @since 2.5.0
	 */
	public function walker() {
		include_once( TB_FRAMEWORK_DIRECTORY . '/admin/options/class-theme-blvd-nav-menu-edit.php' );
		return 'Theme_Blvd_Nav_Menu_Edit';
	}

	/**
	 * Save the options we've added to the menu builder.
	 *
	 * @since 2.5.0
	 */
	public function save( $menu_id, $item_id, $args ) {

		global $_POST;

		if ( empty( $_POST['_tb_mega_menu'][$item_id] ) ) {
			update_post_meta( $item_id, '_tb_mega_menu', '0' );
		} else {
			update_post_meta( $item_id, '_tb_mega_menu', '1' );
		}

		if ( empty( $_POST['_tb_mega_menu_hide_headers'][$item_id] ) ) {
			update_post_meta( $item_id, '_tb_mega_menu_hide_headers', '0' );
		} else {
			update_post_meta( $item_id, '_tb_mega_menu_hide_headers', '1' );
		}

		if ( empty( $_POST['_tb_bold'][$item_id] ) ) {
			update_post_meta( $item_id, '_tb_bold', '0' );
		} else {
			update_post_meta( $item_id, '_tb_bold', '1' );
		}

		if ( empty( $_POST['_tb_deactivate_link'][$item_id] ) ) {
			update_post_meta( $item_id, '_tb_deactivate_link', '0' );
		} else {
			update_post_meta( $item_id, '_tb_deactivate_link', '1' );
		}

		if ( empty( $_POST['_tb_placeholder'][$item_id] ) ) {
			update_post_meta( $item_id, '_tb_placeholder', '0' );
		} else {
			update_post_meta( $item_id, '_tb_placeholder', '1' );
		}

	}
}
