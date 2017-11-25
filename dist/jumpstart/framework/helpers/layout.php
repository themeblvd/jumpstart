<?php
/**
 * Helpers: Theme Layout
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */

/**
 * Determine if header top bar displays any
 * content to display.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array $include  Elements to check for.
 * @return bool  $has_info Whether we've got any info to show in header.
 */
function themeblvd_has_header_info( $include = array() ) {

	if ( ! $include ) {

		/*
		 * If any of these elements are present, this
		 * function will return TRUE.
		 */
		$include = array(
			'header_text',
			'searchform',
			'social_media',
			'wpml',
			'cart',
			'side_panel',
		);

	}

	$has_info = false;

	if ( ! $has_info && in_array( 'header_text', $include ) ) {

		if ( themeblvd_get_option( 'header_text' ) ) {

			$has_info = true;

		}
	}

	if ( ! $has_info && in_array( 'searchform', $include ) ) {

		if ( 'show' === themeblvd_get_option( 'searchform' ) ) {

			$has_info = true;

		}
	}

	if ( ! $has_info && in_array( 'social_media', $include ) ) {

		if ( themeblvd_get_option( 'social_media' ) ) {

			$has_info = true;

		}
	}

	if ( ! $has_info && in_array( 'wpml', $include ) ) {

		if ( themeblvd_do_lang_selector() ) {

			$has_info = true;

		}
	}

	if ( ! $has_info && in_array( 'cart', $include ) ) {

		if ( themeblvd_do_cart() ) {

			$has_info = true;

		}
	}

	if ( ! $has_info && in_array( 'side_panel', $include ) ) {

		if ( themeblvd_do_side_panel() ) {

			$has_info = true;

		}
	}

	/**
	 * Filters if header top bar displays any
	 * content to display.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param bool  $has_info Whether we've got any info to show in header.
	 * @param array $include  Elements to check for.
	 */
	return apply_filters( 'themeblvd_has_header_info', $has_info, $include );

}

/**
 * Filter in helpers intended to be used with
 * copyright text.
 *
 * This function is filtered onto:
 * 1. `themeblvd_the_content` - 10
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string Copyright text.
 * @return string Copyright text with attributes replaced.
 */
function themeblvd_footer_copyright_helpers( $text ) {

	// Add the current year.
	$text = str_replace(
		'%year%',
		esc_attr( date( 'Y' ) ),
		$text
	);

	// Add the site title.
	$text = str_replace(
		'%site_title%',
		esc_html( get_bloginfo( 'site_title' ) ),
		$text
	);

	return $text;

}

/**
 * Whether to display floating shopping cart.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @return bool Whether to display floating shopping cart.
 */
function themeblvd_do_cart() {

	/**
	 * Filters whether to display floating
	 * shopping cart.
	 *
	 * By default this is FALSE; however, if the
	 * theme supports WooCommerce and WooCommerce
	 * is installed, this will be filtered to TRUE.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param bool Whether to display floating shopping cart.
	 */
	return apply_filters( 'themeblvd_do_cart', false );

}

/**
 * Whether to display theme language selector.
 *
 * @since Theme_Blvd 2.5.1
 */
function themeblvd_do_lang_selector() {

	/**
	 * Filters whether to display floating
	 * shopping cart.
	 *
	 * By default this is FALSE; however, if the
	 * theme supports WPML and WPML is installed,
	 * this will be filtered to TRUE.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param bool Whether to display theme language selector.
	 */
	return apply_filters( 'themeblvd_do_lang_selector', false );

}

/**
 * Whether to display floating search bar.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @return bool $do Whether to display floating search.
 */
function themeblvd_do_floating_search() {

	$do = false;

	if ( 'show' === themeblvd_get_option( 'searchform' ) ) {

		$do = true;

	}

	/**
	 * Filters whether to display floating search
	 * bar.
	 *
	 * Note: Before this filter is applied, there
	 * is an end-user option to determine this from
	 * the theme options.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param bool $do Whether to display floating search.
	 */
	return apply_filters( 'themeblvd_do_floating_search', $do );

}

/**
 * Whether to display the hidden side panel.
 *
 * @since Theme_Blvd 2.6.0
 *
 * @return bool $do Whether to display the hidden side panel.
 */
function themeblvd_do_side_panel() {

	$do = false;

	if ( themeblvd_supports( 'display', 'side_panel' ) ) {

		$primary = themeblvd_get_wp_nav_menu_args( 'side' );

		$secondary = themeblvd_get_wp_nav_menu_args( 'side_sub' );

		if ( has_nav_menu( $primary['theme_location'] ) || has_nav_menu( $secondary['theme_location'] ) ) {

			$do = true;

		}
	}

	/**
	 * Filters whether to display the hidden side
	 * panel.
	 *
	 * If you're looking to completely remove support
	 * for the side panel funcitonality, use the filter
	 * `themeblvd_global_config` instead.
	 *
	 * @link http://dev.themeblvd.com/tutorial/managing-framework-features/
	 *
	 * By default, the side panel is triggered by there
	 * being a navigation menu applied to one of the
	 * two side menu theme locations. So, you can use
	 * this filter to add other circumstances to
	 * trigger it.
	 *
	 * @since Theme_Blvd 2.6.0
	 *
	 * @return bool $do Whether to display the hidden side panel.
	 */
	return apply_filters( 'themeblvd_do_side_panel', $do );

}

/**
 * Whether to condense full-width content.
 *
 * When the sidebar layout is set to `full_width`
 * this condenses to the content to 800px and centers
 * it in the middle. A nice look for readable, open
 * blog posts, for example.
 *
 * @since Theme_Blvd 2.6.0
 *
 * @return bool $do Whether to apply condensed full-width content.
 */
function themeblvd_do_fw_narrow() {

	$do = false;

	if ( themeblvd_get_option( 'fw_narrow' ) ) {

		if ( 'full_width' === themeblvd_config( 'sidebar_layout' ) ) {

			$do = true;

		} elseif ( is_page_template( 'template_builder.php' ) && ! is_search() && ! is_archive() ) { // ! is_search() and ! is_archive() added to fix is_page_template() bug noticed in WordPress 4.7.

			$do = true;

		}

		if ( is_page_template( 'template_blank.php' ) || is_page_template( 'template_grid.php' ) || is_page_template( 'template_showcase.php' ) ) {

			$do = false;

		} elseif ( is_archive() && ( themeblvd_config( 'mode' ) == 'grid' || themeblvd_config( 'mode' ) == 'showcase' ) ) {

			$do = false;

		} elseif ( themeblvd_installed( 'woocommerce' ) && themeblvd_supports( 'plugins', 'woocommerce' ) ) {

			if ( is_shop() || is_cart() || is_checkout() ) {

				$do = false;

			}
		}
	}

	/**
	 * Filters whether to condense full-width content.
	 *
	 * When the sidebar layout is set to `full_width`
	 * this condenses to the content to 800px and centers
	 * it in the middle. A nice look for readable, open
	 * blog posts, for example.
	 *
	 * @since Theme_Blvd 2.6.0
	 *
	 * @return bool $do Whether to apply condensed full-width content.
	 */
	return apply_filters( 'themeblvd_do_fw_narrow', $do );

}

/**
 * Whether popout images wider than content,
 * when condensed content is enabled.
 *
 * @since Theme_Blvd 2.6.0
 *
 * @return bool $do Whether to popout images.
 */
function themeblvd_do_img_popout() {

	$do = false;

	if ( themeblvd_do_fw_narrow() && themeblvd_get_option( 'img_popout' ) ) {

		$do = true;

	}

	/**
	 * Filters whether popout images wider than content,
	 * when condensed content is enabled.
	 *
	 * @since Theme_Blvd 2.6.0
	 *
	 * @param bool $do Whether to popout images.
	 */
	return apply_filters( 'themeblvd_do_img_popout', $do );

}

/**
 * Get a responsive visisbility class(es).
 *
 * The returned value will be formatted as a
 * string including a combination of the following:
 * 1. `hidden-xs`
 * 2. `hidden-sm`
 * 3. `hidden-md`
 * 4. `hidden-lg`
 * 5. `hidden-xl`
 *
 * @since Theme_Blvd 2.1.0
 *
 * @param  array  $hide  Devices to be hidden on, like `array( 'xs', `sm` )`.
 * @return string $class CSS class(es) separated by spaces.
 */
function themeblvd_responsive_visibility_class( $hide ) {

	if ( ! $hide || ! is_array( $hide ) ) {
		return false; // option wasn't passed in correctly
	}

	$class = '';

	foreach ( $hide as $key => $val ) {

		if ( $val && in_array( $key, array( 'xs', 'sm', 'md', 'lg', 'xl' ) ) ) {

			$class .= " hidden-{$key}";

		}
	}

	/**
	 * Filters responsive visisbility class(es).
	 *
	 * @since Theme_Blvd 2.1.0
	 *
	 * @param string $class CSS class(es) separated by spaces.
	 * @param array  $hide  Devices to be hidden on, like `array( 'xs', `sm` )`.
	 */
	return apply_filters( 'themeblvd_responsive_visibility_class', trim( $class ), $hide );

}

/**
 * Forward password-protected pages using page
 * templates to page.php.
 *
 * Since custom page templates display generated
 * content outside of the_content(), this is helpful
 * to still hide that content.
 *
 * @since Theme_Blvd 2.2.1
 *
 * @param  string $template Current custom page template file location.
 * @return string $template Location of template file to be used.
 */
function themeblvd_private_page( $template ) {

	/*
	 * If the page isn't password-protected, just
	 * pass original template back.
	 */
	if ( ! post_password_required() ) {

		return $template;

	}

	/*
	 * If using the layout builder, always redirect to
	 * using page.php.
	 */
	if ( themeblvd_config( 'builder' ) ) {

		return locate_template( 'page.php' );

	}

	/**
	 * Filters the custom page templates, where
	 * the password-protected redirect is supported.
	 *
	 * Note: Custom layouts are handled separately
	 * above where this filter is applied; so
	 * template_builder.php is not included in this
	 * list.
	 *
	 * @since Theme_Blvd 2.2.1
	 *
	 * @param array Page templates.
	 */
	$page_templates = apply_filters( 'themeblvd_private_page_support', array(
		'template_blog.php',
		'template_grid.php',
		'template_list.php',
		'template_showcase.php',
		'template_archives.php',
		'template_sitemap.php',
	));

	foreach ( $page_templates as $page_template ) {

		if ( is_page_template( $page_template ) ) {

			$template = locate_template( 'page.php' );

		}
	}

	return $template;

}

/**
 * Get class used to determine width of column
 * in primary sidebar layout.
 *
 * Note: This function is meant to be used with
 * the main sidebar layout, not generally for
 * columns.
 *
 * @since Theme_Blvd 2.2.0
 *
 * @param  string $column       Which column to retrieve class for, `left`, `right`, or `content`.
 * @return string $column_class The class to be used in grid system.
 */
function themeblvd_get_column_class( $column ) {

	$class = '';

	if ( ! in_array( $column, array( 'content', 'left', 'right' ) ) ) {

		return $class;

	}

	$layouts = themeblvd_sidebar_layouts();

	$layout = themeblvd_config( 'sidebar_layout' );

	if ( isset( $layouts[ $layout ]['columns'][ $column ] ) ) {

		$class = $layouts[ $layout ]['columns'][ $column ];

		// If this layout has a left sidebar, it'll require some push/pull.
		if ( in_array( $layout, array( 'sidebar_left', 'double_sidebar', 'double_sidebar_left' ) ) ) {

			// What is the current stack?
			$stack = 'xs';

			if ( false !== strpos( $class, 'sm' ) ) {

				$stack = 'sm';

			} elseif ( false !== strpos( $class, 'md' ) ) {

				$stack = 'md';

			} elseif ( false !== strpos( $class, 'lg' ) ) {

				$stack = 'lg';

			}

			// Push/pull columns based on the layout.
			if ( 'sidebar_left' === $layout || 'double_sidebar' === $layout ) {

				if ( 'content' === $column ) {

					// Content push = left sidebar width.
					$class .= ' ' . str_replace( "col-{$stack}-", "col-{$stack}-push-", $layouts[ $layout ]['columns']['left'] );

				} elseif ( 'left' === $column ) {

					// Left sidebar pull = content width
					$class .= ' ' . str_replace( "col-{$stack}-", "col-{$stack}-pull-", $layouts[ $layout ]['columns']['content'] );

				}
			} elseif ( 'double_sidebar_left' === $layout ) {

				if ( 'content' === $column ) {

					// Content push = left sidebar width + right right sidebar width.
					$push_1 = str_replace( "col-{$stack}-", '', $layouts['double_sidebar_left']['columns']['left'] );

					$push_2 = str_replace( "col-{$stack}-", '', $layouts['double_sidebar_left']['columns']['right'] );

					$push = trim( intval( $push_1 ) + intval( $push_2 ) );

					if ( 0 === strpos( $push_1, '0' ) && 0 === strpos( $push_2, '0' ) ) {

						$push = '0' . $push;

					}

					$push = "col-{$stack}-push-{$push}";

					$class .= ' ' . $push;

				} else {

					// Left/Right sidebar pull = content width.
					$class .= ' ' . str_replace( "col-{$stack}-", "col-{$stack}-pull-", $layouts['double_sidebar_left']['columns']['content'] );

				}
			}
		}
	}

	/**
	 * Filters the CSS class used for a column
	 * in the main sidebar layout.
	 *
	 * @since Theme_Blvd 2.2.0
	 *
	 * @param string $class  CSS class.
	 * @param string $column Which column to retrieve class for, `left`, `right`, or `content`.
	 * @param string $layout Sidebar layout.
	 */
	return apply_filters( 'themeblvd_column_class', $class, $column, $layout );

}

/**
 * Correct any incompatibilities with child
 * theme devs filtering sidebar layout classes,
 * using older methods.
 *
 * This function is filtered onto:
 * 1. `themeblvd_column_class` - 20
 *
 * @since Theme_Blvd 2.4.0
 *
 * @param string $class Current sidebar layout column class
 */
function themeblvd_column_class_legacy( $class ) {

	/**
	 * Filters the responsive stacking point for
	 * columns of the sidebar layout.
	 *
	 * Stacking points: `xs`, `sm`, `md`, `lg` or `xl`
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param string Stacking point, `xs`, `sm`, `md`, `lg` or `xl`.
	 */
	$stack = apply_filters( 'themeblvd_sidebar_layout_stack', 'md' );

	return str_replace( 'span', "col-{$stack}-", $class );

}

/**
 * Get social media sources and their respective
 * names.
 *
 * @since Theme_Blvd 2.3.0
 *
 * @return array $sources All social media sources.
 */
function themeblvd_get_social_media_sources() {

	$sources = array(
		'chat'        => __( 'General: Chat', 'jumpstart' ), // fa: comments
		'cloud'       => __( 'General: Cloud', 'jumpstart' ),
		'anchor'      => __( 'General: Link', 'jumpstart' ), // fa: link
		'email'       => __( 'General: Mail', 'jumpstart' ), // fa: envelope
		'movie'       => __( 'General: Movie', 'jumpstart' ), // fa: film
		'music'       => __( 'General: Music', 'jumpstart' ),
		'portfolio'   => __( 'General: Portfolio', 'jumpstart' ), // fa: briefcase
		'rss'         => __( 'General: RSS', 'jumpstart' ),
		'store'       => __( 'General: Store', 'jumpstart' ), // fa: shopping-cart
		'write'       => __( 'General: Write', 'jumpstart' ), // fa: pencil
		'android'     => __( 'Android', 'jumpstart' ),
		'apple'       => __( 'Apple', 'jumpstart' ),
		'behance'     => __( 'Behance', 'jumpstart' ),
		'codepen'     => __( 'CodePen', 'jumpstart' ),
		'delicious'   => __( 'Delicious', 'jumpstart' ),
		'deviantart'  => __( 'DeviantArt', 'jumpstart' ),
		'digg'        => __( 'Digg', 'jumpstart' ),
		'dribbble'    => __( 'Dribbble', 'jumpstart' ),
		'dropbox'     => __( 'Dropbox', 'jumpstart' ),
		'facebook'    => __( 'Facebook', 'jumpstart' ),
		'flickr'      => __( 'Flickr', 'jumpstart' ),
		'foursquare'  => __( 'Foursquare', 'jumpstart' ),
		'github'      => __( 'GitHub', 'jumpstart' ),    // fa: github-alt
		'google'      => __( 'Google+', 'jumpstart' ),   // fa: google-plus
		'instagram'   => __( 'Instagram', 'jumpstart' ),
		'linkedin'    => __( 'LinkedIn', 'jumpstart' ),
		'paypal'      => __( 'PayPal', 'jumpstart' ),
		'pinterest'   => __( 'Pinterest', 'jumpstart' ), // fa: pinterest-p
		'reddit'      => __( 'Reddit', 'jumpstart' ),
		'slideshare'  => __( 'SlideShare', 'jumpstart' ),
		'soundcloud'  => __( 'SoundCloud', 'jumpstart' ),
		'stumbleupon' => __( 'StumbleUpon', 'jumpstart' ),
		'tumblr'      => __( 'Tumblr', 'jumpstart' ),
		'twitter'     => __( 'Twitter', 'jumpstart' ),
		'vimeo'       => __( 'Vimeo', 'jumpstart' ),     // fa: vimeo-square
		'vine'        => __( 'Vine', 'jumpstart' ),
		'windows'     => __( 'Windows', 'jumpstart' ),
		'wordpress'   => __( 'WordPress', 'jumpstart' ),
		'xing'        => __( 'XING', 'jumpstart' ),
		'yahoo'       => __( 'Yahoo', 'jumpstart' ),
		'youtube'     => __( 'YouTube', 'jumpstart' ),
	);

	/**
	 * Filters the available sources for setting
	 * up contact buttons.
	 *
	 * This filter is deprecated, use use
	 * `themeblvd_social_media_sources` instead!
	 *
	 * @since Theme_Blvd 2.0.0
	 * @deprecated Theme_Blvd 2.5.0
	 *
	 * @param array $sources All social media sources.
	 */
	$sources = apply_filters( 'themeblvd_social_media_buttons', $sources );

	/**
	 * Filters the available sources for setting
	 * up contact buttons.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param array $sources All social media sources.
	 */
	return apply_filters( 'themeblvd_social_media_sources', $sources );

}
