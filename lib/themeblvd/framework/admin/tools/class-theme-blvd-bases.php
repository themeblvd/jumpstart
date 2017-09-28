<?php
/**
 * Theme Base Management
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.5.0
 */

/**
 * Setup the admin page for theme bases at
 * Appearance > Theme Base.
 *
 * @since @@name-framework 2.5.0
 *
 * @param array  $bases   All theme bases to select from.
 * @param string $default Default base when none exists in the database.
 */
class Theme_Blvd_Bases {

	/**
	 * All theme bases to select from.
	 *
	 * @since @@name-framework 2.5.0
	 * @var array
	 */
	private $bases = array();

	/**
	 * Default base when none exists in the database.
	 *
	 * @since @@name-framework 2.5.0
	 * @var string
	 */
	private $default = 'dev';

	/**
	 * Constructor. Hook to `admin_menu` to start process
	 * of adding admin page.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array  $bases   All theme bases to select from.
	 * @param string $default Default base when none exists in the database.
	 */
	public function __construct( $bases, $default ) {

		$this->bases = $bases;

		$this->default = $default;

		add_action( 'admin_menu', array( $this, 'add_page' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

	}

	/**
	 * Add the menu page.
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function add_page() {

		$admin_page = add_theme_page(
			__( 'Theme Base', '@@text-domain' ),
			__( 'Theme Base', '@@text-domain' ),
			'edit_theme_options',
			get_template() . '-base',
			array( $this, 'admin_page' )
		);

	}

	/**
	 * Add CSS and JS for admin page.
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function assets() {

		$page = get_current_screen();

		if ( 'appearance_page_' . get_template() . '-base' === $page->base ) {

			$suffix = SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style(
				'themeblvd_admin',
				esc_url( TB_FRAMEWORK_URI . "/admin/assets/css/admin-style{$suffix}.css" ),
				null,
				TB_FRAMEWORK_VERSION
			);

			wp_enqueue_style(
				'themeblvd-admin-base',
				esc_url( TB_FRAMEWORK_URI . "/admin/assets/css/base{$suffix}.css" ),
				null,
				TB_FRAMEWORK_VERSION
			);

			wp_enqueue_script( 'themeblvd_admin',
				esc_url( TB_FRAMEWORK_URI . "/admin/assets/js/shared{$suffix}.js" ),
				array( 'jquery' ),
				TB_FRAMEWORK_VERSION
			);

			wp_enqueue_script(
				'themeblvd-admin-base',
				esc_url( TB_FRAMEWORK_URI . "/admin/assets/js/base{$suffix}.js" ),
				array( 'jquery' ),
				TB_FRAMEWORK_VERSION
			);

		}

	}

	/**
	 * Display admin page.
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function admin_page() {

		$template = get_template();

		// Update theme base.
		if ( ! empty( $_GET['select-base'] ) ) {

			if ( ! empty( $_GET['security'] ) && wp_verify_nonce( $_GET['security'], 'themeblvd-select-base' ) ) {

				if ( array_key_exists( $_GET['select-base'], $this->bases ) ) {

					update_option( $template . '_base', $_GET['select-base'] );

					printf(
						'<div class="updated"><p><strong>%s</strong></p></div>',
						esc_html__( 'Theme base updated successfully.', '@@text-domain' )
					);

				}
			} else {

				printf(
					'<div class="error"><p><strong>%s</strong></p></div>',
					esc_html__( 'Security check failed. Couldn\'t update theme base.', '@@text-domain' )
				);

			}
		}

		// Check to make sure saved theme options match theme base.
		$settings = get_option( themeblvd_get_option_name() );

		$base = get_option( $template . '_base' );

		if ( $base && ( empty( $settings['theme_base'] ) || $settings['theme_base'] !== $base ) ) {

			printf(
				'<div class="error"><p><strong>%s</strong></p></div>',
				esc_html__( 'Your saved options do not currently match the theme base you\'ve selected. Please configure and save your theme options page.', '@@text-domain' )
			);

		}

		// Begin display of page content.
		$theme = wp_get_theme( $template );

		$current = get_option( $template . '_base' );

		$confirm = sprintf(
			'<h4>%1$s</h4><p>%2$s</p>',
			esc_html__( 'Are you sure you want to change your theme base?', '@@text-domain' ),
			esc_html__( 'This will effect your theme options, and you must re-save your theme options page after changing theme bases.', '@@text-domain' )
		);

		if ( ! $current ) {
			$current = $this->default;
		}
		?>
		<div id="themeblvd-base-admin" class="wrap">

			<h2><?php esc_html_e( 'Theme Base', '@@text-domain' ); ?></h2>

			<p class="title-tagline">
				<?php
				printf(
					// translators: 1: name of current theme
					esc_html__( 'Select the %s theme base that\'s right for you.', '@@text-domain' ),
					$theme->get( 'Name' )
				);
				?>
			</p>

			<div class="theme-bases">

				<?php if ( $this->bases ) : ?>

					<?php foreach ( $this->bases as $id => $info ) : ?>

						<?php
						$class = 'theme-base';

						$desc_class = 'theme-base-info';

						if ( $current === $id ) {

							$class .= ' active';

							$desc_class .= ' wp-ui-highlight';

						}

						$url = admin_url(
							add_query_arg(
								array(
									'page'        => $template . '-base',
									'select-base' => $id,
									'security'    => wp_create_nonce( 'themeblvd-select-base' ),
								),
								'themes.php'
							)
						);
						?>

						<div class="theme-base-col">

							<div class="<?php echo $class; ?>">

								<div class="theme-base-screenshot">

									<?php if ( $current !== $id ) : ?>
										<a href="<?php echo esc_url( $url ); ?>" class="select-base" data-confirm="<?php echo $confirm; ?>">
											<span><?php esc_html_e( 'Select Theme Base', '@@text-domain' ); ?></span>
										</a>
									<?php endif; ?>

									<img src="<?php echo esc_url( themeblvd_get_base_uri( $id ) . '/preview.png' ); ?>" />

								</div>

								<div class="<?php echo $desc_class; ?>">

									<h3><?php echo esc_html( $info['name'] ); ?></h3>

									<p><?php echo esc_html( $info['desc'] ); ?></p>

								</div>

							</div><!-- .theme-base -->

						</div><!-- .theme-base-col -->

					<?php endforeach; ?>

				<?php endif; ?>

			</div><!-- .theme-bases -->

		</div><!-- #themeblvd-base-admin -->
		<?php
	}
}
