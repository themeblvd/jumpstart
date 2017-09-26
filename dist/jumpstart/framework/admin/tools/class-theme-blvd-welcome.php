<?php
/**
 * Activation Welcome
 *
 * @author      Jason Bobich
 * @copyright   2009-2017 Theme Blvd
 * @link        http://themeblvd.com
 * @package     Jump_Start
 */

/**
 * Add Theme Blvd framework welcome message when the
 * theme is activated.
 *
 * @since Theme_Blvd 2.6.0
 */
class Theme_Blvd_Welcome {

	/**
	 * A single instance of this class.
	 *
	 * @since Theme_Blvd 2.6.0
	 */
	private static $instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since Theme_Blvd 2.6.0
	 *
	 * @return Theme_Blvd_Welcome A single instance of this class.
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
	 * @since 2.0.0
	 */
	private function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

		add_action( 'admin_notices', array( $this, 'show' ), 9 );

		add_action( 'admin_init', array( $this, 'disable' ) );

		add_action( 'switch_theme', array( $this, 'reset' ) );

	}

	/**
	 * Reset welcome message when user switches themes.
	 *
	 * @since Theme_Blvd 2.6.0
	 */
	public function reset() {

		delete_metadata( 'user', null, 'themeblvd-ignore-welcome', null, true );

	}

	/**
	 * Include scripts.
	 *
	 * @since Theme_Blvd 2.6.0
	 */
	public function assets() {

		global $current_user;

		if ( ! get_user_meta( $current_user->ID, 'themeblvd-ignore-welcome', true ) ) {

			$suffix = SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_media();

			wp_enqueue_style(
				'themeblvd_admin',
				esc_url( TB_FRAMEWORK_URI . "/admin/assets/css/admin-style{$suffix}.css" ),
				null,
				TB_FRAMEWORK_VERSION
			);

			wp_enqueue_script(
				'themeblvd_modal',
				esc_url( TB_FRAMEWORK_URI . "/admin/assets/js/modal{$suffix}.js" ),
				array( 'jquery' ),
				TB_FRAMEWORK_VERSION
			);

			wp_enqueue_script(
				'themeblvd_welcome',
				esc_url( TB_FRAMEWORK_URI . "/admin/assets/js/welcome{$suffix}.js" ),
				array( 'jquery' ),
				TB_FRAMEWORK_VERSION
			);

		}
	}

	/**
	 * Show welcome message.
	 *
	 * @since Theme_Blvd 2.6.0
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

		$theme = wp_get_theme( $template );

		$msg = sprintf(
			// translators: 1: name of current theme, 2: framework version
			'<p><strong>' . __( 'Thank you for using %1$s with Theme Blvd Framework %2$s.', 'jumpstart' ) . '</strong></p>',
			$theme->get( 'Name' ),
			TB_FRAMEWORK_VERSION
		);

		$msg .= sprintf(
			// translators: 1: link to documentation website
			'<p>' . __( 'Below are some resources to get started. You can find more videos and information at %s.', 'jumpstart' ) . '</p>',
			'<a href="http://docs.themeblvd.com" target="_blank">http://docs.themeblvd.com</a>'
		);

		/**
		 * Filters the arguments used to build the welcome
		 * message when the theme is activated.
		 *
		 * @since Theme_Blvd 2.6.0
		 *
		 * @param array {
		 *     @type string $msg   Message with HTML printed within box.
		 *     @type string $btn   Text of button leading to lightbox video.
		 *     @type string $title Text of button's title leading to lightbox video.
		 *     @type string $video ID of video on Vimeo to display in lightbox.
		  * }
		 */
		$args = apply_filters(
			'themeblvd_welcome_args', array(
				'msg'   => $msg,
				'btn'   => __( 'Getting Started', 'jumpstart' ),
				'title' => __( 'Getting Started', 'jumpstart' ),
				'video' => '124567552',
			), $theme
		);

		?>
		<div class="notice notice-warning">

			<?php echo themeblvd_kses( $args['msg'] ); ?>

			<p>
				<a href="#" id="themeblvd-welcome-video-link" class="button button-secondary" title="<?php echo esc_attr( $args['title'] ); ?>" data-video="<?php echo esc_attr( $args['video'] ); ?>">
					<span class="dashicons dashicons-video-alt3" style="line-height:26px;"></span>
					<?php echo esc_html( $args['btn'] ); ?>
				</a>

				<?php if ( themeblvd_supports( 'admin', 'base' ) ) : ?>
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=' . $template . '-base' ) ); ?>" class="button button-secondary">
						<span class="dashicons dashicons-admin-tools" style="line-height:26px;"></span>
						<?php esc_html_e( 'Select Theme Base', 'jumpstart' ); ?>
					</a>
				<?php endif; ?>

				<a href="<?php echo esc_url( admin_url( 'themes.php?page=' . themeblvd_get_option_name() ) ); ?>" class="button button-secondary">
					<span class="dashicons dashicons-admin-settings" style="line-height:26px;"></span>
					<?php esc_html_e( 'Setup Theme Options', 'jumpstart' ); ?>
				</a>
			</p>

			<p>
				<a href="<?php echo esc_url( $this->disable_url() ); ?>">
					<?php esc_html_e( 'Dismiss this notice', 'jumpstart' ); ?>
				</a>
			</p>

		</div>
		<?php
	}

	/**
	 * Disable welcome message.
	 *
	 * @since Theme_Blvd 2.6.0
	 */
	public function disable() {

		global $current_user;

		if ( ! isset( $_GET['nag-ignore'] ) ) {
			return;
		}

		if ( 'themeblvd-ignore-welcome' !== $_GET['nag-ignore'] ) {
			return;
		}

		if ( isset( $_GET['security'] ) && wp_verify_nonce( $_GET['security'], 'themeblvd-ignore-welcome' ) ) {
			add_user_meta( $current_user->ID, $_GET['nag-ignore'], 'true', true );
		}

	}

	/**
	 * Get URL to disable welcome message.
	 *
	 * @since Theme_Blvd 2.6.0
	 */
	private function disable_url() {

		global $pagenow;

		$url = admin_url( $pagenow );

		if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
			$url .= '?' . $_SERVER['QUERY_STRING'];
		}

		$url = add_query_arg(
			array(
				'nag-ignore' => 'themeblvd-ignore-welcome',
				'security'   => wp_create_nonce( 'themeblvd-ignore-welcome' ),
			), $url
		);

		return esc_url( $url );

	}

}
