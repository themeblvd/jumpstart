<?php
/**
 * Theme Blvd framework welcome message
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_Welcome {

	/**
	 * A single instance of this class.
	 *
	 * @since 2.6.0
	 */
	private static $instance = null;

	/**
     * Creates or returns an instance of this class.
     *
     * @since 2.6.0
     *
     * @return Theme_Blvd_Welcome A single instance of this class.
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
	 * @since 2.0.0
	 */
	private function __construct() {

        add_action( 'admin_enqueue_scripts', array($this, 'assets') );
		add_action( 'admin_notices', array($this, 'show'), 9 );
		add_action( 'admin_init', array($this, 'disable') );
        add_action( 'switch_theme', array( $this, 'reset' ) );

	}

    /**
	 * Reset welcome message when user switches themes.
	 *
	 * @since 2.6.0
	 */
	public function reset() {
        delete_metadata( 'user', null, 'themeblvd-ignore-welcome', null, true );
    }

    /**
	 * Include scripts
	 *
	 * @since 2.6.0
	 */
	public function assets() {

        global $current_user;

        if ( ! get_user_meta( $current_user->ID, 'themeblvd-ignore-welcome', true ) ) {
            wp_enqueue_media();
            wp_enqueue_style( 'themeblvd_admin', esc_url( TB_FRAMEWORK_URI . '/admin/assets/css/admin-style.min.css' ), null, TB_FRAMEWORK_VERSION );
            wp_enqueue_script( 'themeblvd_modal', esc_url( TB_FRAMEWORK_URI . '/admin/assets/js/modal.min.js' ), array('jquery'), TB_FRAMEWORK_VERSION );
            wp_enqueue_script( 'themeblvd_welcome', esc_url( TB_FRAMEWORK_URI . '/admin/assets/js/welcome.min.js' ), array('jquery'), TB_FRAMEWORK_VERSION );
        }
    }

    /**
	 * Show welcome message
	 *
	 * @since 2.6.0
	 */
	public function show() {

        global $current_user;

        if ( get_user_meta( $current_user->ID, 'themeblvd-ignore-welcome', true ) ) {
            return;
        }

        if ( ! current_user_can( 'edit_theme_options' ) ) {
            return;
        }

        $template = get_template();
        $theme = wp_get_theme($template);

        $args = apply_filters('themeblvd_welcome_args', array(
            'msg'   => sprintf( '<p><strong>'.__('Thank you for using %s with Theme Blvd Framework %s.', 'themeblvd').'</strong></p><p>'.__('Below are some resources to get started. You can find more videos and information in your "All files and documentation" download.', 'themeblvd').'</p>', $theme->get('Name'), TB_FRAMEWORK_VERSION ),
            'btn'   => __('Getting Started', 'themeblvd'),
            'title' => __('Getting Started', 'themeblvd'),
            'video' => '124567552'
        ), $theme);

        ?>
        <div class="notice notice-warning">

            <?php echo themeblvd_kses( $args['msg'] ); ?>

            <p>
                <a href="#" id="themeblvd-welcome-video-link" class="button button-secondary" title="<?php echo esc_attr( $args['title'] ); ?>" data-video="<?php echo esc_attr( $args['video'] ); ?>">
                    <span class="dashicons dashicons-video-alt3" style="line-height:26px;"></span>
                    <?php echo esc_html( $args['btn'] ); ?>
                </a>

                <?php if ( themeblvd_supports('admin', 'base') ) : ?>
                    <a href="<?php echo esc_url( admin_url('themes.php?page='.$template.'-base') ) ?>" class="button button-secondary">
                        <span class="dashicons dashicons-admin-tools" style="line-height:26px;"></span>
                        <?php esc_html_e('Select Theme Base', 'themeblvd'); ?>
                    </a>
                <?php endif; ?>

                <a href="<?php echo esc_url( admin_url( 'themes.php?page='.themeblvd_get_option_name() ) ) ?>" class="button button-secondary">
                    <span class="dashicons dashicons-admin-settings" style="line-height:26px;"></span>
                    <?php esc_html_e('Setup Theme Options', 'themeblvd'); ?>
                </a>
            </p>

            <p>
                <a href="<?php echo esc_url( $this->disable_url() ); ?>">
                    <?php esc_html_e('Dismiss this notice', 'themeblvd'); ?>
                </a>
            </p>

        </div>
        <?php
    }

    /**
	 * Disable welcome message
	 *
	 * @since 2.6.0
	 */
	public function disable() {

        global $current_user;

		if ( ! isset($_GET['nag-ignore']) ) {
			return;
		}

		if ( $_GET['nag-ignore'] != 'themeblvd-ignore-welcome' ) {
			return;
		}

		if ( isset($_GET['security']) && wp_verify_nonce( $_GET['security'], 'themeblvd-ignore-welcome' ) ) {
			add_user_meta( $current_user->ID, $_GET['nag-ignore'], 'true', true );
		}

    }

    /**
	 * URL to disable welcome message.
	 *
	 * @since 2.6.0
	 */
	private function disable_url() {

		global $pagenow;

		$url = admin_url( $pagenow );

        if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
            $url .= '?'.$_SERVER['QUERY_STRING'];
        }

        return esc_url( add_query_arg( array(
            'nag-ignore'    => 'themeblvd-ignore-welcome',
            'security'      => wp_create_nonce('themeblvd-ignore-welcome')
        ), $url ));

	}

}
