<?php
/**
 * Create new walker for WP's wp_nav_menu function.
 * Takes into account mega menus and icons.
 *
 * @since 2.5.0
 */
class ThemeBlvd_Main_Menu_Walker extends Walker_Nav_Menu {

	private $doing_mega = false;
	private $count = 0;
	private $show_headers = false;
	private $current_header = null;

	/**
	 * Start level
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {

		if ( $this->doing_mega ) {
			if ( $depth == 0 ) {
				$output .= "<div class=\"sf-mega\">\n";
			} else if ( $depth == 1 ) {
				$output .= "<div class=\"sf-mega-section\">\n";
			} else {
				return; // Additional sub levels not allowed past 1 for mega menus
			}
		}

		if ( ! $this->doing_mega ) {
			$output .= '<ul class="sub-menu non-mega-sub-menu">';
		} else if ( $depth != 0 ) {
			$output .= '<ul class="sub-menu mega-sub-menu level-1">';
		}

		if ( $this->doing_mega && $depth == 1 ) {

			// Putting the 2nd level menu item as the first,
			// prominent item in the 3rd level.
			if ( $this->current_header ) {

				if ( get_post_meta($this->current_header->ID, '_tb_deactivate_link', true) ) {
					$output .= sprintf('<li class="menu-item-has-children"><span class="mega-section-header">%s</span></li>', apply_filters( 'the_title', $this->current_header->title, $this->current_header->ID ) );
				} else {
					$args->before = '<span class="mega-section-header">';
					$args->after = '</span>';
					parent::start_el( $output, $this->current_header, 2, $args );
					parent::end_el( $output, $this->current_header, 2, $args );
					$args->before = $args->after = '';
				}

				$output = substr_replace($output, "<ul class=\"sub-menu mega-sub-menu level-2\">\n", -5); // Replace last </li> with opening <ul>
			}
		}

	}

	/**
	 * End level
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {

		if ( $this->doing_mega ) {
			if ( $depth == 0 ) {
				$output .= "</div><!-- .sf-mega (end) -->\n";
			} else if ( $depth == 1 ) {

				if ( $this->show_headers ) {
					$output .= "</ul><!-- .mega-sub-menu.level-2 (end) -->\n";
				}

				$output .= "</div><!-- .sf-mega-section (end) -->\n";
				$this->count++;

			} else {
				return; // Additional sub levels not allowed past 1 for mega menus
			}
		} else {
			$output .= '</ul>';
		}

	}

	/**
	 * Start nav element
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		// Activate mega menu, if enabled
		if ( $depth == 0 ) {

			$this->doing_mega = false;
			$this->show_headers = false;
			$this->current_header = null;
			$this->count = 0;

			if ( get_post_meta($item->ID, '_tb_mega_menu', true) ) {

				$this->doing_mega = true;

				if ( ! get_post_meta($item->ID, '_tb_mega_menu_hide_headers', true) ) {
					$this->show_headers = true;
				}
			}
		}

		// If level 2 header of mega menu, skip and store it to
		// be displayed as a title for level 3.
		if ( $this->doing_mega && $depth == 1 ) {
			if ( $this->show_headers ) {
				$this->current_header = $item;
			}
			return;
		}

		// Add sub indicator icons, if necessary
		if ( in_array('menu-item-has-children', $item->classes) && ( $depth == 0 || ! $this->doing_mega ) ) {

			$direction = 'down';

			if ( $depth > 0 ) {
				if ( is_rtl() ) {
					$direction = 'left';
				} else {
					$direction = 'right';
				}
			}

			$args->link_after = sprintf( '<i class="sf-sub-indicator fa fa-caret-%s"></i>', $direction );

		}

		// Deactivate link, if enabled
		if ( $depth > 0 && get_post_meta($item->ID, '_tb_deactivate_link', true) ) {

			$class = 'menu-btn';

			if ( get_post_meta($item->ID, '_tb_bold', true) ) {
				$class .= ' bold';
			}

			$output .= sprintf('<span class="%s">%s</span>', $class, apply_filters( 'the_title', $item->title, $item->ID ) );
			return;
		}

		// Add "has-mega-menu" class to list item holding mega menu
		if ( $this->doing_mega && $depth == 0 ) {
			$output = str_replace( sprintf('<li id="menu-item-%s" class="', $item->ID), sprintf('<li id="menu-item-%s" class="has-mega-menu mega-col-%s ', $item->ID, $this->count), $output);
		}

		parent::start_el( $output, $item, $depth, $args, $id );

		$args->link_after = '';

	}

	/**
	 * End nav element
	 */
	function end_el( &$output, $item, $depth = 0, $args = array() ) {

		// Add "has-mega-menu" class to list item holding mega menu
		if ( $this->doing_mega && $depth == 0 ) {
			$output = str_replace( sprintf('<li id="menu-item-%s" class="', $item->ID), sprintf('<li id="menu-item-%s" class="has-mega-menu mega-col-%s ', $item->ID, $this->count), $output);
		}

		if ( ! $this->doing_mega || $depth != 1 ) {
			$output .= "</li>\n";
		}
	}

}

/**
 * Filter framework items into wp_nav_menu() output.
 *
 * @since 2.4.3
 *
 * @param string $item_output Initial menu item like <a href="URL">Title</a>
 * @param string $item Object for menu item post
 * @param int $depth Depth of the menu item, i.e 0 for top level, 1 for second level, etc
 * @param array $args Arguments for call to wp_nav_menu, NOT individiaul menu item
 * @return string $item_output Modified menu item
 */
function themeblvd_nav_menu_start_el( $item_output, $item, $depth, $args ) {

	// Add "menu-btn" to all menu items in main navigation.
	// Note: If menu item's link was disabled in the walker, the
	// item will already be setup as <span class="menu-btn">Title</span>,
	// which allows styling to match all anchor links with .menu-btn
	if ( is_a($args->walker, 'ThemeBlvd_Main_Menu_Walker') ) {
		$item_output = str_replace( '<a', '<a class="menu-btn"', $item_output );
	}

	// Add "bold" class
	if ( get_post_meta($item->ID, '_tb_bold', true) ) {
		if ( strpos($item_output, 'menu-btn') !== false ) {
			$item_output = str_replace( 'menu-btn', 'menu-btn bold', $item_output );
		} else {
			$item_output = str_replace( '<a', '<a class="bold"', $item_output );
		}
	}

	// Indicators for top-level toggle menus
	if ( in_array( 'menu-item-has-children', $item->classes ) && $depth < 1 ) {
		if ( strpos($args->menu_class, 'tb-side-menu') !== false || ( $args->menu_id == 'primary-menu' && themeblvd_supports('display', 'responsive') && themeblvd_supports('display', 'mobile_side_menu') ) ) {
			$icon_open = apply_filters( 'themeblvd_side_menu_icon_open', 'plus' );
			$icon_close = apply_filters( 'themeblvd_side_menu_icon_close', 'minus' );
			$icon = apply_filters( 'themeblvd_side_menu_icon', sprintf( '<i class="tb-side-menu-toggle fa fa-%1$s" data-open="%1$s" data-close="%2$s"></i>', $icon_open, $icon_close ) );
			$item_output = str_replace( '</a>', '</a>'.$icon, $item_output );
		}

	}

	// Allow bootstrap "nav-header" class in menu items.
	// Note: For primary navigation will only work on levels 2-3
	// (1) ".sf-menu li li.nav-header" 	=> Primary nav dropdowns
	// (2) ".menu li.nav-header" 		=> Standard custom menu widget
	// (3) ".subnav li.nav-header" 		=> Theme Blvd Horizontal Menu widget
	if ( in_array( 'nav-header', $item->classes )  ) {

		$header = sprintf( '<span>%s</span>', apply_filters( 'the_title', $item->title, $item->ID ) );

		if ( strpos( $args->menu_class, 'sf-menu' ) !== false ) {
			// Primary Navigation
			if ( $depth > 0 ) {
				$item_output = $header;
			}
		} else {
			$item_output = $header;
		}
	}

	// Allow bootstrap "divider" class in menu items.
	// Note: For primary navigation will only work on levels 2-3
	if ( in_array( 'divider', $item->classes )  ) {

		if ( strpos( $args->menu_class, 'sf-menu' ) !== false ) {
			// Primary Navigation
			if ( $depth > 0 ) {
				$item_output = '';
			}
		} else {
			$item_output = '';
		}
	}

	// Fontawesome icons in menu items
	$icon = '';
	foreach ( $item->classes as $class ) {
		if ( strpos( $class, 'menu-icon-' ) !== false ) {
			$icon = str_replace( 'menu-icon-', '', $class );
		}
	}

	if ( $icon ) {
		$text = apply_filters( 'the_title', $item->title, $item->ID );
		$icon_output = sprintf( '<i class="fa fa-%s"></i>', $icon );
		$icon_output = apply_filters( 'themeblvd_menu_icon', $icon_output, $icon );
		$item_output = str_replace( $text, $icon_output.$text, $item_output );
	}

	return $item_output;
}

/**
 * Create new walker for WP's wp_nav_menu function.
 * Each menu item is an <option> with the $depth being
 * taken into account in its display.
 *
 * We're using this with themeblvd_nav_menu_select
 * function.
 *
 * @since 2.2.1
 */
class ThemeBlvd_Select_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * Start level
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		// do nothing ...
	}

	/**
	 * End level
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		// do nothing ...
	}

	/**
	 * Start nav element
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = '';

		for( $i = 0; $i < $depth; $i++ ) {
			$indent .= '-';
		}

		if ( $indent ) {
			$indent .= ' ';
		}

		$output .= '<option value="'.$item->url.'">'.$indent.$item->title;
	}

	/**
	 * End nav element
	 */
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= "</option>\n";
	}

}

/**
 * Responsive wp nav menu
 *
 * @since 2.0.0
 *
 * @param string $location Location of wp nav menu to grab
 * @return string $select_menu Select menu version of wp nav menu
 */
function themeblvd_nav_menu_select( $location ) {
	$select_menu = wp_nav_menu( apply_filters( 'themeblvd_nav_menu_select_args', array(
		'theme_location'	=> $location,
		'container'			=> false,
		'items_wrap'		=> '<form class="responsive-nav"><select class="tb-jump-menu form-control"><option value="">'.themeblvd_get_local('navigation').'</option>%3$s</select></form>',
		'echo' 				=> false,
		'walker' 			=> new ThemeBlvd_Select_Menu_Walker
	)));
	return apply_filters('themeblvd_nav_menu_select', $select_menu, $location );
}

if ( !function_exists( 'themeblvd_primary_menu_fallback' ) ) :
/**
 * List pages as a main navigation menu when user
 * has not set one under Apperance > Menus in the
 * WordPress admin panel.
 *
 * @since 2.0.0
 */
function themeblvd_primary_menu_fallback() {
	$home_text = themeblvd_get_local('home');
	$args = apply_filters( 'themeblvd_primary_menu_args', array( 'menu_id' => 'primary-menu', 'menu_class' => 'tb-primary-menu tb-to-side-menu sf-menu', 'container' => '', 'theme_location' => 'primary', 'fallback_cb' => 'themeblvd_primary_menu_fallback' ) );
	echo '<ul id="'.$args['menu_id'].'" class="'.$args['menu_class'].'">';
	echo '<li class="home"><a href="'.home_url().'" title="'.$home_text.'">'.$home_text.'</a></li>';
	wp_list_pages('title_li=');
	echo '</ul>';
}
endif;