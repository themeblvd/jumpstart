<?php
/**
 * Frontend Menu Functions
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.5.0
 */

/**
 * Creates a new walker for WordPress's wp_nav_menu()
 * function.
 *
 * This walker adds framework functionality to main
 * navigation for things like mega menus and item
 * icons.
 *
 * @since Theme_Blvd 2.5.0
 */
class ThemeBlvd_Main_Menu_Walker extends Walker_Nav_Menu {

	private $doing_mega = false;

	private $count = 0;

	private $show_headers = false;

	private $current_header = null;

	/**
	 * Starts the list before the elements are added.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {

		if ( $this->doing_mega ) {

			if ( 0 == $depth ) {

				$output .= "<div class=\"sf-mega\">\n";

			} elseif ( 1 == $depth ) {

				$output .= "<div class=\"sf-mega-section\">\n";

			} else {

				return; // Additional sub levels not allowed past 1 for mega menus.

			}
		}

		if ( ! $this->doing_mega ) {

			$output .= '<ul class="sub-menu non-mega-sub-menu">';

		} elseif ( 0 != $depth ) {

			$output .= '<ul class="sub-menu mega-sub-menu level-1">';

		}

		if ( $this->doing_mega && 1 == $depth ) {

			/*
			 * Putting the 2nd level menu item as the first,
			 * prominent item in the 3rd level.
			 */
			if ( $this->current_header ) {

				if ( get_post_meta( $this->current_header->ID, '_tb_deactivate_link', true ) || get_post_meta( $this->current_header->ID, '_tb_placeholder', true ) ) {

					$class = 'menu-item-has-children';

					if ( get_post_meta( $this->current_header->ID, '_tb_placeholder', true ) ) {

						$class .= ' placeholder';

					}

					$output .= sprintf(
						'<li class="%s"><span class="mega-section-header">%s</span></li>',
						$class,
						/** Default WordPress filter. */
						apply_filters( 'the_title', $this->current_header->title, $this->current_header->ID )
					);

				} else {

					$args->before = '<span class="mega-section-header">';

					$args->after = '</span>';

					parent::start_el( $output, $this->current_header, 2, $args );

					parent::end_el( $output, $this->current_header, 2, $args );

					$args->before = $args->after = '';

				}

				$output = trim( $output );

				// Replace last </li> with opening <ul>.
				$output = substr_replace(
					$output,
					"<ul class=\"sub-menu mega-sub-menu level-2\">\n",
					-5
				);

			}
		}

	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {

		if ( $this->doing_mega ) {

			if ( 0 == $depth ) {

				$output .= "</div><!-- .sf-mega (end) -->\n";

			} elseif ( 1 == $depth ) {

				if ( $this->show_headers ) {

					$output .= "</ul><!-- .mega-sub-menu.level-2 (end) -->\n";

					$output .= "</li><!-- .menu-item-has-children (end) -->\n";

				}

				$output .= "</ul><!-- .mega-sub-menu.level-1 (end) -->\n";

				$output .= "</div><!-- .sf-mega-section (end) -->\n";

				$this->count++;

			} else {

				return; // Additional sub levels not allowed past 1 for mega menus.

			}
		} else {

			$output .= '</ul>';

		}

		$output = str_replace( "\n", '', $output );

	}

	/**
	 * Starts the element output.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		// Activate mega menu, if enabled.
		if ( 0 == $depth ) {

			$this->doing_mega = false;

			$this->show_headers = false;

			$this->current_header = null;

			$this->count = 0;

			if ( $args->theme_location == apply_filters( 'themeblvd_primary_menu_location', 'primary' ) && get_post_meta( $item->ID, '_tb_mega_menu', true ) ) {

				$this->doing_mega = true;

				if ( ! get_post_meta( $item->ID, '_tb_mega_menu_hide_headers', true ) ) {

					$this->show_headers = true;

				}
			}
		}

		/*
		 * If level 2 header of mega menu, skip and store it to
		 * be displayed as a title for level 3.
		 */
		if ( $this->doing_mega && 1 == $depth ) {

			if ( $this->show_headers ) {

				$this->current_header = $item;

			}

			return;

		}

		// Add sub indicator icons, if necessary.
		if ( in_array( 'menu-item-has-children', $item->classes ) && ( $depth == 0 || ! $this->doing_mega ) ) {

			$direction = 'down';

			if ( $depth > 0 ) {

				if ( is_rtl() ) {

					$direction = 'left';

				} else {

					$direction = 'right';

				}
			}

			/**
			 * Filters the sub indicator menu icon name.
			 *
			 * @since Theme_Blvd 2.7.4
			 *
			 * @param string            Icon name.
			 * @param string $direction Direction indicator arrow should point to, like `down` or `right`.
			 */
			$icon_name = apply_filters( 'themeblvd_sub_indicator_icon_name', 'angle-' . $direction, $direction );

			/**
			 * Filters the sub indicator menu icon HTML.
			 *
			 * @since Theme_Blvd 2.2.0
			 *
			 * @param string            HTML output for icon. Default uses FontAwesome.
			 * @param string $direction Direction indicator arrow should point to.
			 */
			$args->link_after = apply_filters(
				'themeblvd_menu_sub_indicator',
				themeblvd_get_icon( themeblvd_get_icon_class( $icon_name, array( 'sub-indicator', 'sf-sub-indicator' ) ) ),
				$direction
			);

		}

		// Deactivate link, if enabled.
		if ( $depth > 0 && ( get_post_meta( $item->ID, '_tb_deactivate_link', true ) || get_post_meta( $item->ID, '_tb_placeholder', true ) ) ) {

			$class = 'menu-item menu-item-' . $item->ID;

			if ( get_post_meta( $item->ID, '_tb_placeholder', true ) ) {

				$class .= ' placeholder';

			}

			if ( get_post_meta( $item->ID, '_tb_bold', true ) ) {

				$class .= ' bold';

			}

			$output .= sprintf(
				'<li id="menu-item-%s" class="%s"><span class="menu-btn">%s</span></li>',
				$item->ID,
				$class,
				/** Default WordPress Filter */
				apply_filters( 'the_title', $item->title, $item->ID )
			);

			return;
		}

		if ( get_post_meta( $item->ID, '_tb_bold', true ) ) {

			$item->classes[] = 'bold';

		};

		parent::start_el( $output, $item, $depth, $args, $id );

		$args->link_after = '';

	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Page data object. Not used.
	 * @param int      $depth  Depth of page. Not Used.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	function end_el( &$output, $item, $depth = 0, $args = array() ) {

		// Add `has-mega-menu` class to list item holding mega menu.
		if ( $this->doing_mega && $depth == 0 ) {

			$output = str_replace(
				sprintf(
					'<li id="menu-item-%s" class="',
					$item->ID
				),
				sprintf(
					'<li id="menu-item-%s" class="has-mega-menu mega-col-%s ',
					$item->ID,
					$this->count
				),
				$output
			);

		}

		if ( ! $this->doing_mega || 1 != $depth ) {

			$output .= "</li>\n";

		}
	}

}

/**
 * Filter framework items into wp_nav_menu() output.
 *
 * This function is filtered onto:
 * 1. `walker_nav_menu_start_el` - 10
 *
 * @since Theme_Blvd 2.4.3
 *
 * @param string  $item_output Initial menu item, like `<a href="{URL}">{Title}</a>`.
 * @param string  $item        Object for menu item post.
 * @param int     $depth       Depth of the menu item, like `0` for top level, `1` for second level, etc.
 * @param array   $args        Arguments for call to wp_nav_menu(), NOT individiaul menu item.
 * @return string $item_output Modified menu item.
 */
function themeblvd_nav_menu_start_el( $item_output, $item, $depth, $args ) {

	$primary = themeblvd_get_wp_nav_menu_args( 'primary' );

	$primary = $primary['theme_location'];

	$side = themeblvd_get_wp_nav_menu_args( 'side' );

	$side = $side['theme_location'];

	/*
	 * Add "menu-btn" to all menu items in main
	 * navigation.
	 *
	 * Note: If menu item's link was disabled in the
	 * walker, the item will already be setup as
	 * <span class="menu-btn">Title</span>, which allows
	 * styling to match all anchor links with `.menu-btn`.
	 */
	if ( is_a( $args->walker, 'ThemeBlvd_Main_Menu_Walker' ) ) {

		$item_output = str_replace( '<a', '<a class="menu-btn"', $item_output );

	}

	// Add `bold` class.
	if ( get_post_meta( $item->ID, '_tb_bold', true ) ) {

		if ( strpos( $item_output, 'menu-btn' ) !== false ) {

			$item_output = str_replace( 'menu-btn', 'menu-btn bold', $item_output );

		} else {

			$item_output = str_replace( '<a', '<a class="bold"', $item_output );

		}
	}

	// Dropdown toggles for side menu.
	if ( $args->theme_location == $side && in_array( 'menu-item-has-children', $item->classes ) && $depth < 1 ) {

		/**
		 * Filters the link used to toggle menu items
		 * in the desktop side menu.
		 *
		 * @since Theme_Blvd 2.5.0
		 *
		 * @param string Link HTML.
		 */
		$toggle = apply_filters(
			'themeblvd_submenu_toggle',
			'<a href="#" class="submenu-toggle">+</a>'
		);

		$item_output = str_replace( '</a>', '</a>' . $toggle, $item_output );

	}

	/*
	 * Allow `nav-header` class in menu items.
	 *
	 * Note: For primary navigation will only work on levels 2-3.
	 *
	 * 1. `.sf-menu li li.nav-header` Primary nav dropdowns.
	 * 2. `.menu li.nav-header`       Standard custom menu widget.
	 * 3. `.subnav li.nav-header`     Theme Blvd Horizontal Menu widget.
	 */
	if ( in_array( 'nav-header', $item->classes ) ) {

		$header = sprintf(
			'<span>%s</span>',
			/** Default WordPress Filter */
			apply_filters( 'the_title', $item->title, $item->ID )
		);

		if ( $primary == $args->theme_location ) {

			if ( $depth > 0 ) {

				$item_output = $header;

			}
		} else {

			$item_output = $header;

		}
	}

	/*
	 * Add `divider` class in menu items.
	 *
	 * Note: For primary navigation will only work on
	 * levels 2-3.
	 */
	if ( in_array( 'divider', $item->classes ) ) {

		if ( $primary == $args->theme_location ) {

			if ( $depth > 0 ) {

				$item_output = '';

			}
		} else {

			$item_output = '';

		}
	}

	// Add FontAwesome icons in menu items.
	$icon = '';

	foreach ( $item->classes as $class ) {

		if ( false !== strpos( $class, 'menu-icon-' ) ) {

			$icon = str_replace( 'menu-icon-', '', $class );

		}
	}

	if ( $icon ) {

		/** Default WordPress Filter. */
		$text = apply_filters( 'the_title', $item->title, $item->ID );

		$icon_output = themeblvd_get_icon( themeblvd_get_icon_class( $icon, array( 'fa-fw' ) ) );

		if ( $primary == $args->theme_location && $depth == 0 ) {

			$icon_output = str_replace( ' fa-fw', '', $icon_output );

		}

		/**
		 * Filters the HTML for a menu icon.
		 *
		 * @since Theme_Blvd 2.2.0
		 *
		 * @param string $icon_output Final HTML for icon.
		 * @param string $icon        Icon name extracted from `menu-icon-{name}`.
		 */
		$icon_output = apply_filters( 'themeblvd_menu_icon', $icon_output, $icon );

		if ( ! $args->theme_location ) {

			// Random custom menu, probably sidebar widget, insert icon outside <a>.
			$item_output = $icon_output . $item_output;

		} else {

			// Theme location, insert icon within <a>.
			$item_output = str_replace( $text, $icon_output . $text, $item_output );

		}
	}

	return $item_output;

}

/**
 * Add CSS classes to main menu list items.
 *
 * This function is hooked to:
 * 1. `nav_menu_css_class` - 10
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  array    $classes The CSS classes that are applied to the menu item's `<li>` element.
 * @param  WP_Post  $item    The current menu item.
 * @param  stdClass $args    An object of wp_nav_menu() arguments.
 * @param  int      $depth   Depth of menu item. Used for padding.
 * @return array    $classes Modified CSS classes.
 */
function themeblvd_nav_menu_css_class( $classes, $item, $args = array(), $depth = 0 ) {

	$classes[] = sprintf( 'level-%s', strval( $depth + 1 ) );

	return $classes;

}

/**
 * Display <select> menu, to replace main
 * navigation. Intended for mobile.
 *
 * @since Theme_Blvd 2.0.0
 * @deprecated Theme_Blvd 2.7.0
 *
 * @param  string $location Location of wp nav menu to grab
 * @return string           Blank output.
 */
function themeblvd_nav_menu_select( $location ) {

	themeblvd_deprecated_function(
		__FUNCTION__,
		'2.7.0',
		null,
		__( 'The &lt;select&gt; menu to replaces the main navigation on mobile is no longer supported.' , 'jumpstart' )
	);

	return '';

}

/**
 * Get filtered arguments for wp_nav_menu(),
 * for different theme menu locations.
 *
 * @since Theme_Blvd 2.5.0
 *
 * @param  string $location Menu location.
 * @return array  $args     Arguments to pass to wp_nav_menu().
 */
function themeblvd_get_wp_nav_menu_args( $location = 'primary' ) {

	$args = array();

	switch ( $location ) {

		// Set arguments for "Primary Navigation" location.
		case 'primary':
			$args = array(
				'walker'      => new ThemeBlvd_Main_Menu_Walker(),
				'menu_class'  => 'tb-primary-menu tb-to-mobile-menu sf-menu sf-menu-with-fontawesome clearfix',
				'fallback_cb' => false,
				'container'   => '',
			);

			/**
			 * Filters the theme's menu location used
			 * for the primary navigation output.
			 *
			 * Here's a good example of a user for this
			 * filter:
			 *
			 * @link https://dev.themeblvd.com/tutorial/creating-a-custom-menu-for-mobile
			 *
			 * @since Theme_Blvd 2.5.0
			 *
			 * @param string Menu location.
			 */
			$args['theme_location'] = apply_filters( 'themeblvd_primary_menu_location', 'primary' );

			break;

		// Set arguments for "Footer Navigation" location.
		case 'footer':
			$args = array(
				'menu_class'  => 'list-inline',
				'container'   => '',
				'fallback_cb' => false,
				'depth'       => 1,
			);

			/**
			 * Filters the theme's menu location used
			 * for the footer navigation output.
			 *
			 * @since Theme_Blvd 2.5.0
			 *
			 * @param string Menu location.
			 */
			$args['theme_location'] = apply_filters( 'themeblvd_footer_menu_location', 'footer' );

			break;

		// Set arguments for "Primary Side Navigation" location.
		case 'side':
			$args = array(
				'walker'      => new ThemeBlvd_Main_Menu_Walker(),
				'menu_class'  => 'menu',
				'container'   => '',
				'fallback_cb' => false,
				'depth'       => 2,
			);

			/**
			 * Filters the theme's menu location used
			 * for the main side navigation output.
			 *
			 * Note: Within the desktop hidden side
			 * panel, this is meant to display MORE
			 * prominently of the two side menu locations.
			 *
			 * @since Theme_Blvd 2.5.0
			 *
			 * @param string Menu location.
			 */
			$args['theme_location'] = apply_filters( 'themeblvd_side_menu_location', 'side' );

			break;

		// Set arguments for "Secondary Side Navigation" location.
		case 'side_sub':
			$args = array(
				'menu_class'  => 'secondary-menu',
				'container'   => '',
				'fallback_cb' => false,
				'depth'       => 1,
			);

			/**
			 * Filters the theme's menu location used
			 * for the main side navigation output.
			 *
			 * Note: Within the desktop hidden side
			 * panel, this is meant to display LESS
			 * prominently of the two side menu locations.
			 *
			 * @since Theme_Blvd 2.5.0
			 *
			 * @param string Menu location.
			 */
			$args['theme_location'] = apply_filters( 'themeblvd_side_sub_menu_location', 'side_sub' );

	}

	/**
	 * Filters the arguments passed to wp_nav_menu(),
	 * for a given theme menu location.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param array $args Arguments to pass to wp_nav_menu().
	 */
	return apply_filters( "themeblvd_{$location}_menu_args", $args );

}
