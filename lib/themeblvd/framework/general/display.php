<?php
/**
 * Default Display Functions
 *
 * All display functions include which actions
 * they're hooked to at the framework level, but
 * keep in mind that these may get adjusted at
 * the theme level.
 *
 * @link http://dev.themeblvd.com/tutorial/primary-framework-action-hooks/#finding-actions
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.2.0
 */

if ( ! function_exists( 'themeblvd_viewport_default' ) ) {

	/**
	 * Add viewport meta, if responsive design is
	 * enabled in framework.
	 *
	 * This function is hooked to:
	 * 1. `wp_head` - 2
	 *
	 * @since @@name-framework 2.2.0
	 */
	function themeblvd_viewport_default() {

		if ( themeblvd_supports( 'display', 'responsive' ) ) {

			echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";

		}

	}
}

if ( ! function_exists( 'themeblvd_widgets_above_header' ) ) {

	/**
	 * Display the "Above Header" collapsible widget
	 * area above the main site container.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_before` - 20
	 *
	 * @since @@name-framework 2.7.0
	 */
	function themeblvd_widgets_above_header() {

		themeblvd_display_sidebar( 'ad_above_header' );

	}
}

if ( ! function_exists( 'themeblvd_header_top_default' ) ) {

	/**
	 * Display the header top bar.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_header_top` - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	function themeblvd_header_top_default() {

		if ( ! themeblvd_has_header_info() ) {
			return;
		}

		$do_icons = themeblvd_get_option( 'social_header' );

		$icons = themeblvd_get_option( 'social_media' );

		?>
		<div <?php themeblvd_header_top_class(); ?>>

			<div class="wrap clearfix">

				<?php themeblvd_header_text(); ?>

				<?php if ( themeblvd_do_side_panel() || 'show' === themeblvd_get_option( 'searchform' ) || themeblvd_do_cart() || ( $icons && $do_icons ) || themeblvd_do_lang_selector() ) : ?>

					<ul class="header-top-nav list-unstyled">

						<?php if ( themeblvd_do_side_panel() ) : ?>

							<li class="top-side-panel">
								<?php themeblvd_side_trigger(); ?>
							</li>

						<?php endif; ?>

						<?php if ( themeblvd_get_option( 'searchform' ) == 'show' ) : ?>

							<li class="top-search">
								<?php themeblvd_floating_search_trigger(); ?>
							</li>

						<?php endif; ?>

						<?php if ( themeblvd_do_cart() ) : ?>

							<li class="top-cart">
								<?php themeblvd_cart_popup_trigger(); ?>
							</li>

						<?php endif; ?>

						<?php if ( $icons && $do_icons ) : ?>

							<li class="top-icons">
								<?php
								themeblvd_contact_bar( $icons, array(
									'class' => 'to-mobile',
								));
								?>
							</li>

						<?php endif; ?>

						<?php if ( themeblvd_do_lang_selector() ) : ?>

							<li class="top-wpml">
								<?php do_action( 'icl_language_selector' ); ?>
							</li>

						<?php endif; ?>

					</ul>

				<?php endif; ?>

			</div><!-- .wrap (end) -->

		</div><!-- .header-top (end) -->
		<?php

	}
}

if ( ! function_exists( 'themeblvd_responsive_menu_toggle' ) ) {

	/**
	 * Display the mobile navigation buttons.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_header_addon` - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	function themeblvd_responsive_menu_toggle() {

		?>
		<ul class="mobile-nav list-unstyled">

			<li>
				<a href="#" class="btn-navbar tb-nav-trigger">
					<?php
					/**
					 * Filters the HTML output for the "hamburger"
					 * mobile menu toggle button.
					 *
					 * @since @@name-framework 2.0.0
					 *
					 * @param array Final HTML output.
					 */
					echo apply_filters(
						'themeblvd_btn_navbar_text',
						'<span class="hamburger"><span class="top"></span><span class="middle"></span><span class="bottom"></span></span>'
					);
					?>
				</a>
			</li>

			<?php if ( themeblvd_do_cart() ) : ?>

				<li><?php themeblvd_mobile_cart_link(); ?></li>

			<?php endif; ?>

		</ul>
		<?php

	}
}

if ( ! function_exists( 'themeblvd_header_content_default' ) ) {

	/**
	 * Display the main content of the header.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_header_content` - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	function themeblvd_header_content_default() {

		$class = 'header-content';

		if ( themeblvd_do_floating_search() ) {

			$class .= ' has-floating-search';

		}

		if ( themeblvd_do_cart() ) {

			$class .= ' has-cart-button';

		}

		?>
		<div class="<?php echo $class; ?>" role="banner">

			<div class="wrap clearfix">

				<?php
				/**
				 * Fires first within the main content of
				 * the header.
				 *
				 * @hooked themeblvd_header_logo_default - 10
				 * @hooked themeblvd_header_logo_mobile - 20
				 *
				 * @since @@name-framework 2.0.0
				 */
				do_action( 'themeblvd_header_logo' );

				/**
				 * Fires last within the main content of
				 * the header.
				 *
				 * @hooked themeblvd_responsive_menu_toggle - 10
				 *
				 * @since @@name-framework 2.0.0
				 */
				do_action( 'themeblvd_header_addon' );
				?>

			</div><!-- .wrap (end) -->

		</div><!-- .header-content (end) -->
		<?php

	}
}

if ( ! function_exists( 'themeblvd_header_logo_default' ) ) {

	/**
	 * Display the header logo.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_header_logo` - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	function themeblvd_header_logo_default() {

		$logo = themeblvd_get_option( 'logo' );

		if ( $logo ) {

			$trans = themeblvd_get_option( 'trans_logo' );

			if ( themeblvd_config( 'suck_up' ) && ! empty( $trans['type'] ) ) {

				$logo['class'] = 'logo-standard';

				echo themeblvd_get_logo( $logo );

				if ( $trans['type'] == 'default' ) {

					$trans = $logo;

				}

				$trans['class'] = 'logo-trans';

				echo themeblvd_get_logo( $trans );

			} else {

				$logo['class'] = 'logo-standard';

				echo themeblvd_get_logo( $logo );

			}
		}
	}
}

if ( ! function_exists( 'themeblvd_header_logo_mobile' ) ) {

	/**
	 * Display the mobile logo.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_header_logo` - 20
	 *
	 * @since @@name-framework 2.6.0
	 */
	function themeblvd_header_logo_mobile() {

		$logo = themeblvd_get_option( 'mobile_logo' );

		if ( ! $logo || ( ! empty( $logo['type'] ) && 'default' === $logo['type'] ) ) {

			$logo = themeblvd_get_option( 'logo' );

		}

		if ( $logo ) {

			$logo['class'] = 'logo-mobile';

			echo themeblvd_get_logo( $logo );

		}
	}
}

if ( ! function_exists( 'themeblvd_header_menu_default' ) ) {

	/**
	 * Display the primary navigation.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_header_main_menu` - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	function themeblvd_header_menu_default() {

		$args = themeblvd_get_wp_nav_menu_args( 'primary' );

		/**
		 * Fires before the main menu, before the
		 * wrapping <nav>.
		 *
		 * @since @@name-framework 2.0.0
		 */
		do_action( 'themeblvd_header_menu_before' );

		if ( has_nav_menu( $args['theme_location'] ) ) {

			?>
			<nav id="access" class="header-nav">

				<div class="wrap clearfix">

					<?php wp_nav_menu( $args ); ?>

					<?php
					/**
					 * Fires just after the main menu, within
					 * the main menu's <nav> wrapper.
					 *
					 * Note: By default, this only fires of a
					 * menu has been applied to the "Primary
					 * Navigation" location.
					 *
					 * @since @@name-framework 2.0.0
					 */
					do_action( 'themeblvd_header_menu_addon' );
					?>

				</div><!-- .wrap (end) -->

			</nav><!-- #access (end) -->
			<?php

		}

		/**
		 * Fires after the main menu, after the
		 * wrapping <nav>.
		 *
		 * @since @@name-framework 2.0.0
		 */
		do_action( 'themeblvd_header_menu_after' );

	}
}

if ( ! function_exists( 'themeblvd_epic_thumb' ) ) {

	/**
	 * Display the "epic thumbnail."
	 *
	 * The epic thumbnail refers to when the
	 * featured image is set to display full-width,
	 * above the content.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_header_after` - 10
	 *
	 * @since @@name-framework 2.6.0
	 */
	function themeblvd_epic_thumb() {

		if ( ! themeblvd_get_att( 'epic_thumb' ) && ! themeblvd_get_att( 'epic_banner' ) ) {

			return;

		}

		$class = array( 'epic-thumb', themeblvd_get_att( 'thumbs' ) );

		if ( themeblvd_installed( 'woocommerce' ) && is_woocommerce() ) {

			$class[] = 'epic-banner';

			$class = apply_filters( 'themeblvd_epic_thumb_class', $class );

			$class = themeblvd_set_att( 'epic_class', implode( ' ',  $class ) );

			themeblvd_get_template_part( 'featured-wc' );

		} elseif ( is_category() || is_tag() || is_author() || is_date() ) {

			$class[] = 'epic-banner';

			if ( 'fs' === themeblvd_get_att( 'thumbs' ) ) {

				$class[] = 'tb-parallax';

			}

			$class = apply_filters( 'themeblvd_epic_thumb_class', $class );

			$class = themeblvd_set_att( 'epic_class', implode( ' ',  $class ) );

			themeblvd_get_template_part( 'featured-archives' );

		} else {

			if ( have_posts() ) {

				while ( have_posts() ) {

					the_post();

					if ( ! has_post_format( 'gallery' ) && 'fs' === themeblvd_get_att( 'thumbs' ) ) {

						$class[] = 'tb-parallax';

					}

					if ( get_post_format() ) {

						$class[] = get_post_format();

					}

					if ( is_page() ) {

						if ( 'hide' === get_post_meta( get_the_ID(), '_tb_title', true ) ) {

							$class[] = 'no-text';

						}
					}

					$class = apply_filters( 'themeblvd_epic_thumb_class', $class );

					$class = themeblvd_set_att( 'epic_class', implode( ' ',  $class ) );

					themeblvd_get_template_part( 'featured' );

				}
			}

			rewind_posts();

		}
	}

}

if ( ! function_exists( 'themeblvd_main_start_default' ) ) {

	/**
	 * Display the opening HTML tags for the
	 * main content wrapper.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_main_start` - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	function themeblvd_main_start_default() {

		?>
		<!-- MAIN (start) -->

		<div id="main" <?php themeblvd_main_class(); ?>>

			<div class="wrap clearfix">

		<?php
	}
}

if ( ! function_exists( 'themeblvd_main_end_default' ) ) {

	/**
	 * Display the closing HTML tags for the
	 * main content wrapper.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_main_start` - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	function themeblvd_main_end_default() {

		?>
			</div><!-- .wrap (end) -->

		</div><!-- #main (end) -->

		<!-- MAIN (end) -->
		<?php

	}
}

if ( ! function_exists( 'themeblvd_widgets_above_content' ) ) {

	/**
	 * Display the "Above Content" collapsible widget
	 * area just above the content.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_main_top` - 10
	 *
	 * @since @@name-framework 2.7.0
	 */
	function themeblvd_widgets_above_content() {

		themeblvd_display_sidebar( 'ad_above_content' );

	}
}

if ( ! function_exists( 'themeblvd_widgets_below_content' ) ) {

	/**
	 * Display the "Below Content" collapsible widget
	 * area just below the content.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_main_bottom` - 10
	 *
	 * @since @@name-framework 2.7.0
	 */
	function themeblvd_widgets_below_content() {

		themeblvd_display_sidebar( 'ad_below_content' );

	}
}

if ( ! function_exists( 'themeblvd_breadcrumbs_default' ) ) {

	/**
	 * Display website breadcrumbs.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_breadcrumbs` - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	function themeblvd_breadcrumbs_default() {

		if ( themeblvd_show_breadcrumbs() ) {

			themeblvd_the_breadcrumbs();

		}
	}
}

if ( ! function_exists( 'themeblvd_fixed_sidebars' ) ) {

	/**
	 * Display fixed sidebar(s).
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_sidebars` - 10
	 *
	 * @since @@name-framework 2.0.0
	 *
	 * @param string $position position of sidebar on page, left or right
	 */
	function themeblvd_fixed_sidebars( $position ) {

		$layout = themeblvd_config( 'sidebar_layout' );

		// Layouts: Sidebar Left, Sidebar Right, Double Sidebars
		if ( 'sidebar_' . $position === $layout || 'double_sidebar' === $layout ) {

			/**
			 * Fires before a fixed sidebar.
			 *
			 * @hooked themeblvd_fixed_sidebar_before_default - 10
			 *
			 * @since @@name-framework 2.0.0
			 *
			 * @param string $position Sidebar position, `left` or `right`.
			 */
			do_action( 'themeblvd_fixed_sidebar_before', $position );

			// Display sidebar widgets.
			themeblvd_display_sidebar( 'sidebar_' . $position );

			/**
			 * Fires after a fixed sidebar.
			 *
			 * @hooked themeblvd_fixed_sidebar_after_default - 10
			 *
			 * @since @@name-framework 2.0.0
			 *
			 * @param string $position Sidebar position, `left` or `right`.
			 */
			do_action( 'themeblvd_fixed_sidebar_after', $position );

		}

		// Layouts: Double Left Sidebars
		if ( 'double_sidebar_left' === $layout && 'left' === $position ) {

			// Display left sidebar.

			/**
			 * Fires before a fixed sidebar.
			 *
			 * @hooked themeblvd_fixed_sidebar_before_default - 10
			 *
			 * @since @@name-framework 2.0.0
			 *
			 * @param string $position Sidebar position, `left` or `right`.
			 */
			do_action( 'themeblvd_fixed_sidebar_before', 'left' );

			// Display sidebar widgets.
			themeblvd_display_sidebar( 'sidebar_left' );

			/**
			 * Fires after a fixed sidebar.
			 *
			 * @hooked themeblvd_fixed_sidebar_after_default - 10
			 *
			 * @since @@name-framework 2.0.0
			 *
			 * @param string $position Sidebar position, `left` or `right`.
			 */
			do_action( 'themeblvd_fixed_sidebar_after', 'left' );

			// Display right sidebar.

			/**
			 * Fires before a fixed sidebar.
			 *
			 * @hooked themeblvd_fixed_sidebar_before_default - 10
			 *
			 * @since @@name-framework 2.0.0
			 *
			 * @param string $position Sidebar position, `left` or `right`.
			 */
			do_action( 'themeblvd_fixed_sidebar_before', 'right' );

			// Display sidebar widgets.
			themeblvd_display_sidebar( 'sidebar_right' );

			/**
			 * Fires after a fixed sidebar.
			 *
			 * @hooked themeblvd_fixed_sidebar_after_default - 10
			 *
			 * @since @@name-framework 2.0.0
			 *
			 * @param string $position Sidebar position, `left` or `right`.
			 */
			do_action( 'themeblvd_fixed_sidebar_after', 'right' );

		}

		// Layouts: Double Right Sidebars
		if ( 'double_sidebar_right' === $layout && 'right' === $position ) {

			// Display left sidebar.

			/**
			 * Fires before a fixed sidebar.
			 *
			 * @hooked themeblvd_fixed_sidebar_before_default - 10
			 *
			 * @since @@name-framework 2.0.0
			 *
			 * @param string $position Sidebar position, `left` or `right`.
			 */
			do_action( 'themeblvd_fixed_sidebar_before', 'left' );

			// Display sidebar widgets.
			themeblvd_display_sidebar( 'sidebar_left' );

			/**
			 * Fires after a fixed sidebar.
			 *
			 * @hooked themeblvd_fixed_sidebar_after_default - 10
			 *
			 * @since @@name-framework 2.0.0
			 *
			 * @param string $position Sidebar position, `left` or `right`.
			 */
			do_action( 'themeblvd_fixed_sidebar_after', 'left' );

			// Display right sidebar.

			/**
			 * Fires before a fixed sidebar.
			 *
			 * @hooked themeblvd_fixed_sidebar_before_default - 10
			 *
			 * @since @@name-framework 2.0.0
			 *
			 * @param string $position Sidebar position, `left` or `right`.
			 */
			do_action( 'themeblvd_fixed_sidebar_before', 'right' );

			// Display sidebar widgets.
			themeblvd_display_sidebar( 'sidebar_right' );

			/**
			 * Fires after a fixed sidebar.
			 *
			 * @hooked themeblvd_fixed_sidebar_after_default - 10
			 *
			 * @since @@name-framework 2.0.0
			 *
			 * @param string $position Sidebar position, `left` or `right`.
			 */
			do_action( 'themeblvd_fixed_sidebar_after', 'right' );

		}
	}
}

if ( ! function_exists( 'themeblvd_fixed_sidebar_before_default' ) ) {

	/**
	 * Display the opening HTML markup for a
	 * fixed sidebar.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_fixed_sidebar_before` - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	function themeblvd_fixed_sidebar_before_default( $side ) {

		echo '<div class="fixed-sidebar ' . $side . '-sidebar ' . esc_attr( themeblvd_get_column_class( $side ) ) . '">';

		echo '<div class="fixed-sidebar-inner">';

	}
}

if ( ! function_exists( 'themeblvd_fixed_sidebar_after_default' ) ) {

	/**
	 * Display the closing HTML markup for a
	 * fixed sidebar.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_fixed_sidebar_after` - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	function themeblvd_fixed_sidebar_after_default() {

		echo '</div><!-- .fixed-sidebar-inner (end) -->';

		echo '</div><!-- .fixed-sidebar (end) -->';

	}
}

if ( ! function_exists( 'themeblvd_footer_content_default' ) ) {

	/**
	 * Display the main footer content, consisting of
	 * columns configured from the theme options by
	 * the end-user.
	 *
	 * This function is hooked to: themeblvd_footer_content
	 *
	 * @see themeblvd_columns()
	 *
	 * @since @@name-framework 2.0.0
	 */
	function themeblvd_footer_content_default() {

		$footer_setup = themeblvd_get_option( 'footer_setup' );

		if ( $footer_setup ) {

			$args = array();

			$args['num'] = count( explode( '-', $footer_setup ) );

			$args['widths'] = $footer_setup;

			/**
			 * Filters the arguments to setup the footer
			 * columns, which are passed to themeblvd_columns().
			 *
			 * @see themeblvd_columns()
			 *
			 * @since @@name-framework 2.5.0
			 *
			 * @param array $args Arguments for themeblvd_columns().
			 */
			$args = apply_filters( 'themeblvd_footer_columns_args', $args );

			$i = 1;

			$columns = array();

			while ( $i <= $args['num'] ) {

				$columns[ $i ] = themeblvd_get_option( 'footer_col_' . $i );

				$i++;

			}
			?>
			<div class="footer-content">

				<div class="wrap clearfix">

					<?php themeblvd_columns( $args, $columns ); ?>

				</div><!-- .wrap (end) -->

			</div><!-- .footer-content (end) -->
			<?php

		}
	}
}

if ( ! function_exists( 'themeblvd_footer_sub_content_default' ) ) {

	/**
	 * Display the footer sub content, below the
	 * main footer content.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_footer_sub_content` - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	function themeblvd_footer_sub_content_default() {

		$menu = themeblvd_get_wp_nav_menu_args( 'footer' );

		?>
		<div <?php themeblvd_copyright_class(); ?>>

			<div class="wrap clearfix">

				<?php if ( themeblvd_get_option( 'social_footer' ) ) : ?>

					<?php
					/**
					 * Filters the arguments used for the footer
					 * copyright contact bar.
					 *
					 * @since @@name-framework 2.7.0
					 *
					 * @param array Arguments passed to themeblvd_get_contact_bar().
					 */
					$args = apply_filters(
						'themeblvd_copyright_contact_bar_args',
						array(
							'tooltip' => 'top',
							'style'   => 'dark',
						)
					);
					?>

					<?php echo themeblvd_get_contact_bar( null, $args ); ?>

				<?php endif; ?>

				<div class="copyright">

					<?php
					/**
					 * Filters the footer copyright text.
					 *
					 * @since @@name-framework 2.0.0
					 *
					 * @param string $output Final HTML output.
					 */
					echo apply_filters(
						'themeblvd_footer_copyright',
						themeblvd_get_content( themeblvd_get_option( 'footer_copyright' ) )
					);
					?>

				</div><!-- .copyright (end) -->

				<?php if ( has_nav_menu( $menu['theme_location'] ) ) : ?>

					<div class="footer-nav">

						<?php wp_nav_menu( $menu ); ?>

					</div><!-- .footer-nav (end) -->

				<?php endif; ?>

			</div><!-- .wrap (end) -->

		</div><!-- .site-copyright (end) -->
		<?php

	}
}

if ( ! function_exists( 'themeblvd_widgets_below_footer' ) ) {

	/**
	 * Display the "Below Footer" collapsible widget
	 * area after the main site container.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_after` - 5
	 *
	 * @since @@name-framework 2.7.0
	 */
	function themeblvd_widgets_below_footer() {

		themeblvd_display_sidebar( 'ad_below_footer' );

	}
}

if ( ! function_exists( 'themeblvd_side_panel' ) ) {

	/**
	 * Display the hidden side panel.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_after` - 10
	 *
	 * @since @@name-framework 2.6.0
	 */
	function themeblvd_side_panel() {

		if ( themeblvd_do_side_panel() ) {

			themeblvd_get_template_part( 'panel' );

		}

	}
}

if ( ! function_exists( 'themeblvd_side_panel_menu' ) ) {

	/**
	 * Display the "Primary Side Navigation"
	 * menu location.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_side_panel` - 10
	 *
	 * @since @@name-framework 2.6.0
	 */
	function themeblvd_side_panel_menu() {

		wp_nav_menu( themeblvd_get_wp_nav_menu_args( 'side' ) );

	}
}

if ( ! function_exists( 'themeblvd_side_panel_sub_menu' ) ) {

	/**
	 * Display the "Secondary Side Navigation"
	 * menu location.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_side_panel` - 20
	 *
	 * @since @@name-framework 2.6.0
	 */
	function themeblvd_side_panel_sub_menu() {

		wp_nav_menu( themeblvd_get_wp_nav_menu_args( 'side_sub' ) );

	}
}

if ( ! function_exists( 'themeblvd_side_panel_contact' ) ) {

	/**
	 * Display the contact icons in the side panel,
	 * when enabled from the theme options.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_side_panel` - 30
	 *
	 * @since @@name-framework 2.7.0
	 */
	function themeblvd_side_panel_contact() {

		if ( themeblvd_get_option( 'social_panel' ) ) {

			/**
			 * Filters the arguments used for the side
			 * panel contact bar.
			 *
			 * @since @@name-framework 2.7.0
			 *
			 * @param array Arguments passed to themeblvd_get_contact_bar().
			 */
			$args = apply_filters(
				'themeblvd_panel_contact_bar_args',
				array(
					'tooltip' => false,
					'style'   => 'light',
				)
			);

			echo themeblvd_get_contact_bar( null, $args );

		}

	}
}

if ( ! function_exists( 'themeblvd_archive_info' ) ) {

	/**
	 * Display info boxes at the top of archives.
	 *
	 * Note: Whether archive info boxes have been
	 * disabled by the user or not, this function
	 * also still serves to fire the
	 * `themeblvd_archive_info` action hook.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_content_top` - 10
	 *
	 * @since @@name-framework 2.5.0
	 */
	function themeblvd_archive_info() {

		if ( is_archive() ) {

			if ( is_category() || is_tag() ) {

				$setting = '';

				if ( is_category() ) {

					$setting = themeblvd_get_tax_meta( 'category', get_query_var( 'category_name' ), 'info', 'default' );

					if ( ! $setting || 'default' === $setting ) {

						$setting = themeblvd_get_option( 'category_info', null, 'hide' );

					}

					if ( 'show' === $setting ) {

						$tax = 'category';

						$term = get_query_var( 'category_name' );

					}
				} elseif ( is_tag() ) {

					$setting = themeblvd_get_tax_meta( 'post_tag', get_query_var( 'tag' ), 'info', 'default' );

					if ( ! $setting || 'default' === $setting ) {

						$setting = themeblvd_get_option( 'tag_info', null, 'hide' );

					}

					if ( 'show' === $setting ) {

						$tax = 'post_tag';

						$term = get_query_var( 'tag' );

					}
				}

				if ( 'show' === $setting ) {

					themeblvd_tax_info();

				}
			} elseif ( is_author() ) {

				if ( get_query_var( 'author_name' ) ) {

					$user = get_user_by( 'slug', get_query_var( 'author_name' ) );

				} elseif ( get_query_var( 'author' ) ) {

					$user = get_user_by( 'id', get_query_var( 'author' ) );

				}

				if ( ! empty( $user ) ) {

					$setting = get_user_meta( $user->ID, '_tb_box_archive', true );

					if ( '1' === $setting ) {

						themeblvd_author_info( $user, 'archive' );

					}
				}
			}

			/**
			 * Fires as the top of post archives.
			 *
			 * If you wanted to use themeblvd_tax_info() for a
			 * taxonomy other than category or post_tag, this
			 * would be a good place to do it.
			 *
			 * This action hook is also useful if you want to
			 * add a traditional archive title instead of the
			 * theme's info boxes.
			 *
			 * @since @@name-framework 2.5.0
			 */
			do_action( 'themeblvd_archive_info' );

		} // End if is_archive().
	}
}

if ( ! function_exists( 'themeblvd_single_footer_default' ) ) {

	/**
	 * Display the footer for single posts.
	 *
	 * Note: This function must be within the loop.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_single_footer` - 10
	 *
	 * @since @@name-framework 2.5.0
	 */
	function themeblvd_single_footer_default() {

		// Display author box.
		$user = get_user_by( 'id', get_the_author_meta( 'ID' ) );

		$setting = get_post_meta( get_the_ID(), '_tb_author_box', true );

		if ( ! $setting || 'default' === $setting ) {

			$setting = get_user_meta( $user->ID, '_tb_box_single', true );

		}

		if ( '1' === $setting ) {

			themeblvd_author_info( $user, 'single' );

		}

		// Display related posts.
		$setting = get_post_meta( get_the_ID(), '_tb_related_posts', true );

		if ( ! $setting || 'default' === $setting ) {

			$setting = themeblvd_get_option( 'single_related_posts', null, 'tag' );

		}

		if ( 'hide' !== $setting ) {

			themeblvd_related_posts( array(
				'related_by' => $setting,
			));

		}

	}
}

if ( ! function_exists( 'themeblvd_blog_meta_default' ) ) {

	/**
	 * Display the post meta for the `blog` post
	 * display type.
	 *
	 * Note: This function must be within the loop.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_meta` - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	function themeblvd_blog_meta_default() {

		/*
		// A simpler, more modernized post meta display.
		$args = array(
			'include' => array( 'time', 'author' ),
			'icons'   => array( 'time' ),
			'time'    => 'ago',
			'sep'     => ' ' . themeblvd_get_local('by') . ' '
		);
		*/

		/**
		 * Filters the arguments used for the post
		 * meta display of the `blog` post display.
		 *
		 * @see themeblvd_get_meta()
		 *
		 * @since @@name-framework 2.3.0
		 *
		 * @param array Arguments used for themeblvd_get_meta().
		 */
		$args = apply_filters( 'themeblvd_blog_meta_args', array(
			'include' => array( 'format', 'time', 'author', 'category', 'comments' ),
		));

		echo themeblvd_get_meta( $args );

	}
}

if ( ! function_exists( 'themeblvd_grid_meta_default' ) ) {

	/**
	 * Display the post meta for the `grid` post
	 * display type.
	 *
	 * Note: This function must be within the loop.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_grid_meta` - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	function themeblvd_grid_meta_default() {

		/**
		 * Filters the arguments used for the post
		 * meta display of the `grid` post display.
		 *
		 * @see themeblvd_get_meta()
		 *
		 * @since @@name-framework 2.3.0
		 *
		 * @param array Arguments used for themeblvd_get_meta().
		 */
		$args = apply_filters( 'themeblvd_grid_meta_args', array(
			'include'  => array( 'time', 'author', 'comments' ),
			'comments' => 'mini',
			//'time'   => 'ago'
		));

		echo themeblvd_get_meta( $args );

	}
}

if ( ! function_exists( 'themeblvd_search_meta_default' ) ) {

	/**
	 * Display the post meta for the posts in a
	 * search results list.
	 *
	 * Note: This function must be within the loop.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_search_meta` - 10
	 *
	 * @since @@name-framework 2.0.0
	 */
	function themeblvd_search_meta_default() {

		$args = array(
			'include' => array( 'time' ),
			'time'    => 'ago',
		);

		if ( 'post' === get_post_type() ) {

			$args['include'][] = 'author';

			$args['include'][] = 'comments';

		}

		/**
		 * Filters the arguments used for the post
		 * meta for the posts in a search results
		 * list.
		 *
		 * @see themeblvd_get_meta()
		 *
		 * @since @@name-framework 2.3.0
		 *
		 * @param array Arguments used for themeblvd_get_meta().
		 */
		$args = apply_filters( 'themeblvd_search_meta_args', $args );

		echo themeblvd_get_meta( $args );

	}
}

if ( ! function_exists( 'themeblvd_blog_sub_meta_default' ) ) {

	/**
	 * Display the sub meta below the post content
	 * in the `blog` post display.
	 *
	 * Note: This function must be within the loop.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_blog_sub_meta` - 10
	 *
	 * @since @@name-framework 2.5.0
	 */
	function themeblvd_blog_sub_meta_default() {

		?>
		<div class="sub-meta-wrapper clearfix">

			<div class="share">

				<?php themeblvd_blog_share(); ?>

			</div>

			<div class="info">

				<?php themeblvd_blog_tags(); ?>

			</div>

		</div><!-- .sub-meta-wrapper (end) -->
		<?php

	}
}

if ( ! function_exists( 'themeblvd_the_post_thumbnail_default' ) ) {

	/**
	 * Display the featured image in a blogroll.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_the_post_thumbnail` - 10
	 *
	 * @see themeblvd_get_post_thumbnail()
	 *
	 * @since @@name-framework 2.0.0
	 *
	 * @param string $size Image crop size.
	 * @param array  $args Arguments for featured image, see themeblvd_get_post_thumbnail().
	 */
	function themeblvd_the_post_thumbnail_default( $size = '', $args = array() ) {

		echo themeblvd_get_post_thumbnail( $size, $args );

	}
}

if ( ! function_exists( 'themeblvd_blog_content_default' ) ) {

	/**
	 * Display the content within a `blog` post
	 * display.
	 *
	 * This is helpful for determining whether
	 * `the_content()` or `the_excerpt()` is
	 * used and setting those two scenarios up.
	 *
	 * Note: This function must be within the loop.
	 *
	 * This function is hooked to:
	 * 1. `themeblvd_blog_content` - 10
	 *
	 * @since @@name-framework 2.0.0
	 *
	 * @param string $type Type of content -- content or excerpt
	 */
	function themeblvd_blog_content_default( $type ) {

		if ( 'content' === $type ) {

			/**
			 * Filters the "More Text" used when the_content()
			 * is called in the `blog` post display.
			 *
			 * @since @@name-framework 2.0.0
			 *
			 * @param string Read more text, like `Read More`.
			 */
			$more = apply_filters( 'themeblvd_the_content_more_text', themeblvd_get_local( 'read_more' ) );

			the_content( $more );

		} else {

			/**
			 * Filters the arguments used to display the
			 * excerpt and read more button in the `blog`
			 * post display type.
			 *
			 * @since @@name-framework 2.0.0
			 *
			 * @param array {
			 *     Arguments for excerpt and read more button.
			 *
			 *     @type string $text        Text for button, like `Read More`.
			 *     @type string $url         URL for button, defaults to `get_permalink()`.
			 *     @type string $color       Color of button.
			 *     @type string $target      Target for button, like `_self` or `_blank`.
			 *     @type string $size        Button size, `small`, `medium`, `default`, `large`, `x-large`, `xx-large` or `xxx-large`.
			 *     @type string $classes     CSS classes to add to button.
			 *     @type string $title       Title attributes for button.
			 *     @type string $icon_before Name of icon to display at start of button.
			 *     @type string $icon_after  Name of icon to display at end of button.
			 *     @type string $addon       Addon for button <a> tag, like `data-foo="bar"`.
			 *     @type bool   $p           Whether to wrap the button in a <p> tag or not.
			 * }
			 */
			$args = apply_filters( 'themeblvd_the_excerpt_more_args', array(
				'text'        => themeblvd_get_local( 'read_more' ),
				'url'         => get_permalink(),
				'color'       => 'default',
				'target'      => '_self',
				'size'        => null,
				'classes'     => null,
				'title'       => null,
				'icon_before' => null,
				'icon_after'  => null,
				'addon'       => null,
				'p'           => true,
			));

			the_excerpt();

			$button = themeblvd_button(
				$args['text'],
				$args['url'],
				$args['color'],
				$args['target'],
				$args['size'],
				$args['classes'],
				$args['title'],
				$args['icon_before'],
				$args['icon_after'],
				$args['addon']
			);

			if ( $args['p'] ) {

				$button = '<p>' . $button . '</p>';

			}

			echo $button;

		}
	}
}

if ( ! function_exists( 'themeblvd_before_signup_form' ) ) {

	/**
	 * Start wrapping HTML before WordPress
	 * multi-site sign-up form.
	 *
	 * This function is hooked to:
	 * 1. `before_signup_form` - 10
	 *
	 * @since @@name-framework 2.1.0
	 */
	function themeblvd_before_signup_form() {
		?>
		<div id="sidebar_layout" class="clearfix">

			<div class="sidebar_layout-inner">

				<div class="row grid-protection">

					<!-- CONTENT (start) -->

					<div id="content" class="col-md-12 clearfix" role="main">

						<div class="inner">

							<?php themeblvd_content_top(); ?>

		<?php
	}
}

if ( ! function_exists( 'themeblvd_after_signup_form' ) ) {

	/**
	 * End wrapping HTML before WordPress
	 * multi-site sign-up form.
	 *
	 * This function is hooked to:
	 * 1. `after_signup_form` - 10
	 *
	 * @since @@name-framework 2.1.0
	 */
	function themeblvd_after_signup_form() {

		?>
							<?php themeblvd_content_bottom(); ?>

						</div><!-- .inner (end) -->

					</div><!-- #content (end) -->

					<!-- CONTENT (end) -->

					<!-- SIDEBARS (start) -->

					<?php get_sidebar( 'left' ); ?>

					<?php get_sidebar( 'right' ); ?>

					<!-- SIDEBARS (end) -->

				</div><!-- .grid-protection (end) -->

			</div><!-- .sidebar_layout-inner (end) -->

		</div><!-- .#sidebar_layout (end) -->
		<?php

	}
}
