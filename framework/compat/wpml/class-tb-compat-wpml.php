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

		if ( has_filter('wp_nav_menu_items', array($icl_language_switcher, 'wp_nav_menu_items_filter') ) ) {
			remove_filter( 'wp_nav_menu_items', array($icl_language_switcher, 'wp_nav_menu_items_filter') );
			add_filter( 'wp_nav_menu_items', array($this, 'menu_switcher'), 10, 2);
		}

		if ( apply_filters('themeblvd_wpml_has_switcher', true) ) {
			add_action( 'icl_language_switcher_options', array($this, 'switcher_options') );
			add_action( 'icl_save_settings', array($this, 'save_switcher_options') );
		}

	}

	/**
	 * Add CSS
	 *
	 * @since 2.5.0
	 */
	public function assets( $type ) {

		$api = Theme_Blvd_Stylesheets_API::get_instance();

		$deps = $api->get_framework_deps();

		wp_enqueue_style( 'themeblvd-wpml', TB_FRAMEWORK_URI.'/compat/wpml/wpml.css', $deps, TB_FRAMEWORK_VERSION );
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
	 * If user has selected for WPML to insert lang switcher into
	 * main menu, let's make sure it will fit into our framework
	 * menu styling.
	 *
	 * This function was copied from WPML and following changes were made:
	 * 1. Add class "menu-item-has-children" to top level <li>
	 * 2. Add sub indicator to top level <li>
	 * 3. Add "menu-btn" class to all links
	 * 4. Add "non-mega-sub-menu" class to submenu
	 *
	 * @since 2.5.0
	 * @see SitePressLanguageSwitcher::wp_nav_menu_items_filter()
	 */
	public function menu_switcher($items, $args){

        global $sitepress_settings, $sitepress;

		$current_language = $sitepress->get_current_language();
		$default_language = $sitepress->get_default_language();

        // menu can be passed as integer or object
        if( isset($args->menu->term_id) ) {
			$args->menu = $args->menu->term_id;
		}

		$abs_menu_id = icl_object_id( $args->menu, 'nav_menu', false, $default_language );

        if ( $abs_menu_id == $sitepress_settings['menu_for_ls'] ) {

            $languages = $sitepress->get_ls_languages();

            $items .= '<li class="menu-item menu-item-language menu-item-language-current menu-item-has-children">';

            if(isset($args->before)){
                $items .= $args->before;
            }

            $items .= '<a href="#" onclick="return false" class="menu-btn">';

            if( isset($args->link_before) ) {
                $items .= $args->link_before;
            }

			$language_name = '';

			if ( $sitepress_settings['icl_lso_native_lang'] ) {
				$language_name .= $languages[ $current_language ][ 'native_name' ];
			}

			if ( $sitepress_settings['icl_lso_display_lang'] && $sitepress_settings[ 'icl_lso_native_lang' ] ) {
				$language_name .= ' (';
			}

			if ( $sitepress_settings['icl_lso_display_lang'] ) {
				$language_name .= $languages[ $current_language ][ 'translated_name' ];
			}

			if ( $sitepress_settings['icl_lso_display_lang'] && $sitepress_settings[ 'icl_lso_native_lang' ] ) {
				$language_name .= ')';
			}

			$alt_title_lang = esc_attr($language_name);

            if ( $sitepress_settings['icl_lso_flags'] ) {
				$items .= '<img class="iclflag" src="' . $languages[ $current_language ][ 'country_flag_url' ] . '" width="18" height="12" alt="' . $alt_title_lang . '" title="' . esc_attr( $language_name ) . '" />';
			}

			$items .= $language_name;

			if ( ! empty($languages) && count($languages) > 1 ) {
				$items .= '<i class="sf-sub-indicator fa fa-caret-down"></i>';
			}

			if ( isset($args->link_after) ) {
                $items .= $args->link_after;
            }

            $items .= '</a>';

            if( isset($args->after) ) {
                $items .= $args->after;
            }

            if ( ! empty($languages) && count($languages) > 1 ) {
            	$icon_open = apply_filters( 'themeblvd_side_menu_icon_open', 'plus' );
				$icon_close = apply_filters( 'themeblvd_side_menu_icon_close', 'minus' );
				$items .= apply_filters( 'themeblvd_side_menu_icon', sprintf( '<i class="tb-side-menu-toggle fa fa-%1$s" data-open="%1$s" data-close="%2$s"></i>', $icon_open, $icon_close ) );
            }

            unset($languages[ $current_language ]);

			$sub_items = false;
			$menu_is_vertical = !isset($sitepress_settings['icl_lang_sel_orientation']) || $sitepress_settings['icl_lang_sel_orientation'] == 'vertical';
			$menu_is_vertical = true; // ThemeBlvd override above line, always vertical

            if( ! empty($languages) ) {
                foreach( $languages as $lang ){

					$sub_items .= '<li class="menu-item menu-item-language menu-item-language-current">';
                    $sub_items .= '<a href="'.$lang['url'].'" class="menu-btn">';

					$language_name = '';

					if ( $sitepress_settings[ 'icl_lso_native_lang' ] ) {
						$language_name .= $lang[ 'native_name' ];
					}

					if ( $sitepress_settings[ 'icl_lso_display_lang' ] && $sitepress_settings[ 'icl_lso_native_lang' ] ) {
						$language_name .= ' (';
					}

					if ( $sitepress_settings[ 'icl_lso_display_lang' ] ) {
						$language_name .= $lang[ 'translated_name' ];
					}

					if ( $sitepress_settings[ 'icl_lso_display_lang' ] && $sitepress_settings[ 'icl_lso_native_lang' ] ) {
						$language_name .= ')';
					}
					$alt_title_lang = esc_attr($language_name);

                    if( $sitepress_settings['icl_lso_flags'] ){
                        $sub_items .= '<img class="iclflag" src="'.$lang['country_flag_url'].'" width="18" height="12" alt="'.$alt_title_lang.'" title="' . $alt_title_lang . '" />';
                    }

					$sub_items .= $language_name;

                    $sub_items .= '</a>';
                    $sub_items .= '</li>';

                }
				if ( $sub_items && $menu_is_vertical ) {
					$sub_items = '<ul class="sub-menu submenu-languages non-mega-sub-menu">' . $sub_items . '</ul>';
				}
            }
			if ( $menu_is_vertical ) {
				$items .= $sub_items;
            	$items .= '</li>';
			} else {
				$items .= '</li>';
				$items .= $sub_items;
			}

        }

        return $items;
    }

    /**
	 * Add option to display theme's language switcher.
	 *
	 * @since 2.5.0
	 */
	public function switcher_options() {
		$theme = wp_get_theme( get_template() );
		$current = get_option('tb_wpml_show_lang_switcher', '1');
		?>
		<div class="wpml-section-content-inner">
			<h4><?php printf(__('%s by %s', 'themeblvd'), $theme->get('Name'), '<a href="http://themeblvd.com" target="_blank">Theme Blvd</a>' ); ?></h4>
			<label for="tb_wpml_show_lang_switcher">
				<input type="checkbox" id="tb_wpml_show_lang_switcher" name="tb_wpml_show_lang_switcher" value="1" <?php checked('1', $current); ?> />
				<?php printf(__('Display %s\'s default language switcher.', 'themeblvd'), $theme->get('Name')); ?>
			</label>
		</div>
		<?php
	}

	/**
	 * Save option to display theme's language switcher.
	 *
	 * @since 2.5.0
	 */
	public function save_switcher_options() {

		if ( isset($_POST['icl_ajx_action']) && $_POST['icl_ajx_action'] == 'icl_save_language_switcher_options' ) {

			// Save user's selection
			if ( empty( $_POST['tb_wpml_show_lang_switcher'] ) ) {
				$val = '0';
			} else {
				$val = '1';
			}

			update_option('tb_wpml_show_lang_switcher', $val);

		} else if ( ! empty($_GET['restore_ls_settings']) ) {

			// If user resets option, set to true
			update_option('tb_wpml_show_lang_switcher', '1');

		}

	}

}
