<?php
/**
 * Plugin Compatibility: WPML
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.5.0
 */

/**
 * Add extended WPML compatibility.
 *
 * This class follows the singleton pattern,
 * meaning it can only be instantiated in
 * one instance.
 *
 * @since @@name-framework 2.5.0
 */
class Theme_Blvd_Compat_WPML {

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
	 * @return Theme_Blvd_Compat_WPML A single instance of this class.
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

		// Add custom stylesheet for WPML switcher(s).
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ), 15 );

		// Add the theme's WPML stylesheet as a framework dependency.
		add_filter( 'themeblvd_framework_stylesheets', array( $this, 'add_style' ) );

		/**
		 * Fitlers whether the theme's custom language
		 * switcher gets added.
		 *
		 * @since @@name-framework 2.5.0
		 *
		 * @param bool Whether to implement the theme's custom language switcher.
		 */
		if ( apply_filters( 'themeblvd_wpml_has_switcher', true ) ) {

			// Remove WPML default language switcher.
			remove_all_actions( 'icl_language_selector' );

			// Add custom language switcher.
			add_action( 'icl_language_selector', array( $this, 'language_selector' ) );

			/*
			 * Let framework know that a language switcher of some
			 * kind is enabled.
			 */
			add_filter( 'themeblvd_do_lang_selector', array( $this, 'do_lang_selector' ) );

		}

		/*
		 * Translate custom layouts manually, and avoid using
		 * wpml-config.xml for this.
		 */
		add_action( 'wp_insert_post', array( $this, 'translate_layout' ), 10, 3 );

	}

	/**
	 * Add theme's custom stylesheet for WPML.
	 *
	 * This method is hooked to:
	 * 1. `wp_enqueue_scripts` - 15
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function assets() {

		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style(
			'themeblvd-wpml',
			esc_url( TB_FRAMEWORK_URI . "/compat/assets/css/wpml{$suffix}.css" ),
			array( 'themeblvd' ),
			TB_FRAMEWORK_VERSION
		);

	}

	/**
	 * Add theme's WooCommerce stylesheet to framework
	 * dependencies.
	 *
	 * This method is filtered onto:
	 * 1. `themeblvd_framework_stylesheets` - 10
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param  array $deps Stylesheets the theme framework depends on.
	 * @return array $deps Modified stylesheets the theme framework depends on.
	 */
	public function add_style( $deps ) {

		$deps[] = 'wpml';

		return $deps;
	}

	/**
	 * Get custom language switcher block.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @return string $output Final HTML output for block.
	 */
	public function get_language_selector() {

		$output = '';

		$langs = icl_get_languages( 'skip_missing=1' );

		if ( $langs ) {

			$active = array();

			foreach ( $langs as $key => $lang ) {

				if ( isset( $lang['active'] ) && 1 == $lang['active'] ) {

					$active = $lang;

					unset( $langs[ $key ] );

					break;

				}
			}

			if ( $active ) {

				$output .= "\n<div class=\"tb-wpml-switcher\">\n";

				$output .= "\t<ul>\n";

				$output .= "\t\t<li>\n";

				if ( count( $langs ) ) {

					$output .= sprintf(
						"\t\t\t<a href=\"%1\$s\" class=\"lang-%2\$s active\" title=\"%3\$s\">%3\$s<i class=\"%4\$s sf-sub-indicator\"></i></a>",
						$active['url'],
						$active['language_code'],
						$active['translated_name'],
						themeblvd_get_icon_class( 'angle-down' )
					);

					$output .= "\t\t\t<ul class=\"lang-sub-menu\">\n";

					foreach ( $langs as $lang ) {

						$output .= sprintf(
							"\t\t\t\t<li class=\"lang-%1\$s\"><a href=\"%2\$s\" title=\"%3\$s\">%3\$s</a></li>\n",
							$lang['language_code'],
							$lang['url'],
							$lang['translated_name']
						);

					}

					$output .= "\t\t\t</ul>\n";

				} else {

					$output .= sprintf(
						"\t\t\t<span class=\"active\">%s</span>\n",
						$active['translated_name']
					);

				}

				$output .= "\t\t</li><!-- .active (end) -->\n";

				$output .= "\t</ul>\n";

				$output .= '</div> <!-- .tb-wpml-switcher -->';

			}
		}

		/**
		 * Filters the final output for the custom language
		 * switcher the theme adds.
		 *
		 * Note: This funcionality can also be completely disabled
		 * by filtering to `themeblvd_wpml_has_switcher` FALSE.
		 *
		 * @since @@name-framework 2.5.0
		 *
		 * @param string $output Final HTML for language switcher block.
		 */
		return apply_filters( 'themeblvd_wpml_switcher', $output );

	}

	/**
	 * Display custom language switcher block.
	 *
	 * This method is hooked to:
	 * 1. `icl_language_selector` - 10
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function language_selector() {

		echo $this->get_language_selector();

	}

	/**
	 * This tells the framework whether a custom
	 * language switcher (from any plugin) should
	 * be displayed.
	 *
	 * This method is filtered onto:
	 * 1. `themeblvd_do_lang_selector` - 10
	 *
	 * @since @@name-framework 2.5.1
	 *
	 * @return bool Whether to display language switcher.
	 */
	public function do_lang_selector() {

		if ( 'no' !== themeblvd_get_option( 'wpml_show_lang_switcher' ) ) {

			return true;

		}

		return false;

	}

	/**
	 * Translate a custom layout from the layout builder,
	 * when a translated page is created.
	 *
	 * This is a workaround for the following:
	 *
	 * 1. The buggyness of WPML's wpml-config.xml and
	 * making future changes to it.
	 * 2. Copying the builder elements that have uniquely
	 * generated meta keys for a given post.
	 *
	 * This method is hooked to:
	 * 1. `wp_insert_post` - 10
	 *
	 * @since @@name-framework 2.6.3
	 *
	 * @param int     $post_ID Post ID.
	 * @param WP_Post $post    Post object.
	 * @param bool    $update  Whether this is an existing post being updated or not.
	 */
	public function translate_layout( $post_id, $post, $update ) {

		// Is this actually a new post?
		if ( wp_is_post_revision( $post_id ) || $update ) {

			return;

		}

		// Is this a WPML translation?
		if ( ! isset( $_GET['trid'] ) ) {

			return;

		}

		$fields = array(
			'_tb_custom_layout',
			'_tb_builder_plugin_version_created',
			'_tb_builder_plugin_version_saved',
			'_tb_builder_framework_version_created',
			'_tb_builder_framework_version_saved',
			'_tb_builder_elements',
			'_tb_builder_sections',
			'_tb_builder_styles',
		);

		foreach ( $fields as $field ) {

			$val = get_post_meta( $_GET['trid'], $field, true );

			if ( $val ) {

				update_post_meta( $post_id, $field, $val );

			}
		}

		$meta = get_post_meta( $_GET['trid'] );

		if ( $meta ) {

			foreach ( $meta as $key => $val ) {

				if ( false !== strpos( $key, '_tb_builder_element_' ) ) {

					$val = get_post_meta( $_GET['trid'], $key, true );

					update_post_meta( $post_id, $key, $val );

				}
			}
		}

	}

}
