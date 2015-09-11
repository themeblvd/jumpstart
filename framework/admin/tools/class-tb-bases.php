<?php
/**
 * Theme Blvd theme bases admin
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_Bases {

	private $bases = array();
	private $default = 'dev';

	/**
	 * Constructor.
	 *
	 * @since 2.5.0
	 *
	 * @param array $bases Theme bases to select from
	 */
	public function __construct( $bases, $default ) {

		$this->bases = $bases;
		$this->default = $default;

		add_action( 'admin_menu', array( $this, 'add_page' ) );
	}

	/**
	 * Add the menu page.
	 *
	 * @since 2.5.0
	 */
	public function add_page() {
		$admin_page = add_theme_page( __('Theme Base', 'themeblvd'), __('Theme Base', 'themeblvd'), 'edit_theme_options', get_template().'-base', array($this, 'admin_page') );
		add_action( 'admin_print_styles-'.$admin_page, array( $this, 'assets' ) );
		add_action( 'admin_print_scripts-'.$admin_page, array( $this, 'assets' ) );
	}

	/**
	 * Add CSS and JS for admin page.
	 *
	 * @since 2.5.0
	 */
	public function assets() {
		wp_enqueue_style( 'themeblvd_admin', TB_FRAMEWORK_URI . '/admin/assets/css/admin-style.min.css', null, TB_FRAMEWORK_VERSION );
		wp_enqueue_style( 'themeblvd-admin-base', TB_FRAMEWORK_URI . '/admin/assets/css/base.css', null, TB_FRAMEWORK_VERSION );
		wp_enqueue_script( 'themeblvd_admin', TB_FRAMEWORK_URI . '/admin/assets/js/shared.min.js', array('jquery'), TB_FRAMEWORK_VERSION );
		wp_enqueue_script( 'hemeblvd-admin-base', TB_FRAMEWORK_URI . '/admin/assets/js/base.js', array('jquery'), TB_FRAMEWORK_VERSION );
	}

	/**
	 * Display admin page.
	 *
	 * @since 2.5.0
	 */
	public function admin_page() {

		// Update theme base
		if ( ! empty($_GET['select-base']) ) {
			if ( ! empty($_GET['security']) && wp_verify_nonce($_GET['security'], 'select-base') ) {
				if ( array_key_exists($_GET['select-base'], $this->bases) ) {
					update_option( get_template().'_base', $_GET['select-base'] );
					echo '<div class="updated"><p><strong>'.esc_html__('Theme base updated successfully.', 'themeblvd').'</strong></p></div>';
				}
			} else {
				echo '<div class="error"><p><strong>'.esc_html__('Security check failed. Couldn\'t update theme base.', 'themeblvd').'</strong></p></div>';
			}
		}

		// Check to make sure saved theme options match theme base
		$settings = get_option( themeblvd_get_option_name() );
		$base = get_option( get_template().'_base' );

		if ( $base && ( empty($settings['theme_base']) || $settings['theme_base'] != $base ) ) {
			echo '<div class="error"><p><strong>'.esc_html__('Your saved options do not currently match the theme base you\'ve selected. Please configure and save your theme options page.', 'themeblvd').'</strong></p></div>';
		}

		$theme = wp_get_theme(get_template());
		$current = get_option(get_template().'_base');
		$security = wp_create_nonce('select-base');

		if ( ! $current ) {
			$current = $this->default;
		}
		?>
		<div id="themeblvd-base-admin" class="wrap">

			<h2><?php esc_html_e('Theme Base', 'themeblvd'); ?></h2>
			<p class="title-tagline"><em><?php esc_html_e('Who are you?', 'themeblvd'); ?></em> &ndash; <?php printf(esc_html__('Select the %s theme base that\'s right for you.', 'themeblvd'), $theme->get('Name')); ?></p>

			<div class="theme-bases">

				<?php if ( $this->bases ) : ?>
					<?php foreach ( $this->bases as $id => $info ) : ?>

						<?php
						$class = 'theme-base';

						if ( $current == $id ) {
							$class .= ' active';
						}

						$url = sprintf('themes.php?page=%s-base&select-base=%s&security=%s', get_template(), $id, $security );
						?>

						<div class="theme-base-col">
							<div class="<?php echo $class; ?>">
								<div class="theme-base-screenshot">
									<?php if ( $current != $id ) : ?>
										<a href="<?php echo esc_url(admin_url($url)); ?>" class="select-base" data-confirm="<h4><?php esc_html_e('Are you sure you want to change your theme base?', 'themeblvd'); ?></h4><?php esc_html_e('This will effect your Theme Options page, and you must re-save your theme options page after changing theme bases.', 'themeblvd'); ?>">
											<span><?php esc_html_e('Select Theme Base', 'themeblvd'); ?></span>
										</a>
									<?php endif; ?>
									<img src="<?php echo themeblvd_get_base_uri($id); ?>/preview.png" />
								</div>
								<div class="theme-base-info<?php if ( $current == $id ) echo ' wp-ui-highlight'; ?>">
									<h3><?php echo $info['name']; ?></h3>
									<p><?php echo $info['desc']; ?></p>
								</div>
							</div><!-- .theme-base (end) -->
						</div><!-- .theme-base-col (end) -->

					<?php endforeach; ?>
				<?php endif; ?>

			</div><!-- .theme-bases (end) -->

		</div>
		<?php
	}
}
