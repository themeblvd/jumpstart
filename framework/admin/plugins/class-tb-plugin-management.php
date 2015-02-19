<?php
/**
 * Setup notice system for so the user is reminded
 * of which plugins to update to get the best results
 * with the current version of their theme.
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_Plugin_Management {

	/*--------------------------------------------*/
	/* Properties, private
	/*--------------------------------------------*/

	private static $instance = null;
	private $theme;
	private $key = '';
	private $plugins = array();
	private $updates = array();

	/*--------------------------------------------*/
	/* Constructor
	/*--------------------------------------------*/

	/**
     * Creates or returns an instance of this class.
     *
     * @since 2.3.0
     *
     * @return Theme_Blvd_Plugin_Management A single instance of this class.
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

		global $current_user;

		$this->theme = wp_get_theme( get_template() );
		$this->key = sprintf( '%s_%s_plugin_check', get_template(), $this->theme->get('Version') );

		// DEBUG:
		// delete_user_meta( $current_user->ID, $this->key );

		// Setup the plugins and check to make sure user
		// is using recommended versions.
		$this->set_plugins();
		$this->check();

		if ( $this->updates ) {
			add_action( 'admin_init', array( $this, 'disable_notice' ) );
			add_action( 'admin_notices', array( $this, 'show_notice' ) );
		}
	}

	/*--------------------------------------------*/
	/* Methods
	/*--------------------------------------------*/

	/**
	 * Setup plugins and their requested versions
	 *
	 * @since 2.5.0
	 */
	public function set_plugins() {

		$this->plugins = array(
			'portfolios' => array(
				'name'		=> 'Portfolios',
				'slug'		=> 'portfolios',
				'suggested'	=> '1.1.0',
				'current'	=> '',
				'constant'	=> 'TB_PORTFOLIOS_PLUGIN_VERSION'
			),
			'builder' => array(
				'name'		=> 'Theme Blvd Layout Builder',
				'slug'		=> 'theme-blvd-layout-builder',
				'suggested'	=> '2.0.0',
				'current'	=> '',
				'constant'	=> 'TB_BUILDER_PLUGIN_VERSION'
			),
			'shortcodes' => array(
				'name'		=> 'Theme Blvd Shortcodes',
				'slug'		=> 'theme-blvd-shortcodes',
				'suggested'	=> '1.5.0',
				'current'	=> '',
				'constant'	=> 'TB_SHORTCODES_PLUGIN_VERSION'
			),
			'sliders' => array(
				'name'		=> 'Theme Blvd Sliders',
				'slug'		=> 'theme-blvd-sliders',
				'suggested'	=> '1.2.2',
				'current'	=> '',
				'constant'	=> 'TB_SLIDERS_PLUGIN_VERSION'
			),
			'sidebars' => array(
				'name'		=> 'Theme Blvd Widget Areas',
				'slug'		=> 'theme-blvd-widget-areas',
				'suggested'	=> '1.2.0',
				'current'	=> '',
				'constant'	=> 'TB_SIDEBARS_PLUGIN_VERSION'
			),
			'widgets' => array(
				'name'		=> 'Theme Blvd Widget Pack',
				'slug'		=> 'theme-blvd-widget-pack',
				'suggested'	=> '1.0.3',
				'current'	=> '',
				'constant'	=> 'TB_WIDGET_PACK_PLUGIN_VERSION'
			),
			'tweeple' => array(
				'name'		=> 'Tweeple',
				'slug'		=> 'tweeple',
				'suggested'	=> '0.5.0',
				'current'	=> '',
				'constant'	=> 'TWEEPLE_PLUGIN_VERSION'
			)
		);

		foreach ( $this->plugins as $key => $plugin ) {
			if ( defined( $plugin['constant'] ) ) {
				$this->plugins[$key]['current'] = constant($plugin['constant']);
			}
		}

		$this->plugins = apply_filters( 'themeblvd_manage_plugins', $this->plugins );

	}

	/**
	 * Check currently installed plugins against
	 * the theme's suggested versions.
	 *
	 * @since 2.5.0
	 */
	public function check() {

		$this->update = array();

		foreach ( $this->plugins as $id => $plugin ) {
			if ( $plugin['current'] ) {
				if ( version_compare( $plugin['current'], $plugin['suggested'], '<' ) ) {
					$this->updates[] = $id;
				}
			}
		}

	}

	/**
	 * Disable notice
	 *
	 * @since 2.5.0
	 */
	public function disable_notice() {

		global $current_user;

		if ( isset($_GET['nag-ignore']) ) {

			if ( strpos($_GET['nag-ignore'], 'tb-nag-') !== 0 ) { // meta key must start with "tb-nag-"
				return;
			}

			if ( isset($_GET['security']) && wp_verify_nonce( $_GET['security'], 'themeblvd-plugin-nag' ) ) {
				add_user_meta( $current_user->ID, $_GET['nag-ignore'], 'true', true );
			}
		}
	}

	/**
	 * Show notice
	 *
	 * @since 2.5.0
	 */
	public function show_notice() {

		global $pagenow;
		global $current_user;

		// Handle dismiss
		if ( get_user_meta( $current_user->ID, 'tb-nag-'.$this->key ) ) {
			return;
		}

		$url = admin_url( $pagenow );

		if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
			$url .= sprintf( '?%s&nag-ignore=%s', $_SERVER['QUERY_STRING'], 'tb-nag-'.$this->key );
		} else {
			$url .= sprintf( '?nag-ignore=%s', 'tb-nag-'.$this->key );
		}

		$url .= sprintf( '&security=%s', wp_create_nonce('themeblvd-plugin-nag') );

		echo '<div class="error">';
		echo '<p>'.sprintf(__('For everything to work properly with your current version of the %s theme, you must update the following plugins.', 'themeblvd'), '<em>'.$this->theme->get('Name').'</em>').'</p>';
		echo '<ol>';

		foreach ( $this->updates as $update ) {
			printf( '<li>%s</li>', $this->plugins[$update]['name'] );
		}

		echo '</ol>';
		echo '<p class="row-actions visible"><a href="'.admin_url('plugins.php').'">'.__('Go to Plugins Page', 'themeblvd').'</a> | <a href="'.$url.'">'.__('Dismiss this notice', 'themeblvd').'</a> | <a href="http://www.themeblvd.com" target="_blank">'.__('Visit ThemeBlvd.com', 'themeblvd').'</a></p>';
		echo '</div>';
	}

}
