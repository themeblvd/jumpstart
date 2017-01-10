<?php
/**
 * Add extended WPML compatibility
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_Compat_WPML {

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
     * @return Theme_Blvd_Compat_bbPress A single instance of this class.
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

		global $sitepress;
		global $icl_language_switcher;

		add_action( 'wp_enqueue_scripts', array($this, 'assets'), 15 );
		add_filter( 'themeblvd_framework_stylesheets', array($this, 'add_style') );

		if ( apply_filters('themeblvd_wpml_has_switcher', true) ) {

			// Remove WPML default lang switcher and create our own.
			// remove_all_actions('icl_language_selector');
			remove_all_actions( 'icl_language_selector' );
			add_action( 'icl_language_selector', array( $this, 'language_selector' ) );

			// Set theme tag themeblvd_do_lang_selector() to true, if necessary
			add_filter( 'themeblvd_do_lang_selector', array($this, 'do_lang_selector') );

		}

	}

	/**
	 * Add CSS
	 *
	 * @since 2.5.0
	 */
	public function assets( $type ) {

		$handler = Theme_Blvd_Stylesheet_Handler::get_instance();
		$deps = $handler->get_framework_deps();

		wp_enqueue_style( 'themeblvd-wpml', esc_url( TB_FRAMEWORK_URI . '/compat/wpml/wpml.css' ), $deps, TB_FRAMEWORK_VERSION );

	}

	/**
	 * Add our stylesheet to framework $deps. This will make
	 * sure our wpml.css file comes between framework
	 * styles and child theme's style.css
	 *
	 * @since 2.5.0
	 */
	public function add_style( $deps ) {

		$deps[] = 'wpml';

		return $deps;
	}

	/**
	 * Display custom language switcher
	 *
	 * @since 2.5.0
	 */
	public function get_language_selector() {

		$output = '';
		$langs = icl_get_languages('skip_missing=1');

		if ( $langs ) {

			$active = array();

			foreach ( $langs as $key => $lang ) {
				if ( isset($lang['active']) && $lang['active'] == 1 ) {
					$active = $lang;
					unset($langs[$key]);
					break;
				}
			}

			if ( $active ) {

				$output .= "\n<div class=\"tb-wpml-switcher\">\n";
				$output .= "\t<ul>\n";
				$output .= "\t\t<li>\n";

				if ( count($langs) ) {

					$output .= sprintf("\t\t\t<a href=\"%1\$s\" class=\"lang-%2\$s active\" title=\"%3\$s\">%3\$s<i class=\"fa fa-caret-down\"></i></a>", $active['url'], $active['language_code'], $active['translated_name']);
					$output .= "\t\t\t<ul class=\"lang-sub-menu\">\n";

					foreach ( $langs as $lang ) {
						$output .= sprintf("\t\t\t\t<li class=\"lang-%1\$s\"><a href=\"%2\$s\" title=\"%3\$s\">%3\$s</a></li>\n", $lang['language_code'], $lang['url'], $lang['translated_name']);
					}

					$output .= "\t\t\t</ul>\n";

				} else {

					$output .= sprintf("\t\t\t<span class=\"active\">%s</span>\n", $active['translated_name']);

				}

				$output .= "\t\t</li><!-- .active (end) -->\n";
				$output .= "\t</ul>\n";
				$output .= "</div> <!-- .tb-wpml-switcher -->";
			}

		}

		return apply_filters( 'themeblvd_wpml_switcher', $output );

	}

	/**
	 * Display custom language switcher
	 *
	 * @since 2.5.0
	 */
	public function language_selector() {

		echo $this->get_language_selector();

	}

	/**
	 * Whether to display custom language switcher
	 *
	 * @since 2.5.1
	 */
	public function do_lang_selector() {

		if ( 'no' !== themeblvd_get_option( 'wpml_show_lang_switcher' ) ) {

			return true;

		}

		return false;
	}

}
