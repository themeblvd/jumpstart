<?php
/**
 * Plugin Compatibility: WooCommerce
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.5.0
 */

/**
 * Add extended WooCommerce compatibility.
 *
 * This class follows the singleton pattern,
 * meaning it can only be instantiated in
 * one instance.
 *
 * @since @@name-framework 2.5.0
 */
class Theme_Blvd_Compat_WooCommerce {

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
	 * @return Theme_Blvd_Compat_WooCommerce A single instance of this class.
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

		/*
		 * Determine if we're adding custom styles.
		 *
		 * Because this constructor is running early in the
		 * loading process, we can't use `themeblvd_get_option()`.
		 */
		$settings = get_option( themeblvd_get_option_name() );

		$do_custom_styles = true;

		if ( isset( $settings['woo_styles'] ) ) {

			$do_custom_styles = (bool) $settings['woo_styles'];

		}

		// Add any WooCommerce theme support features.
		add_action( 'init', array( $this, 'add_theme_support' ), 5 );

		if ( $do_custom_styles ) {

			/*
			 * Add body class, that can optionally be used for styling
			 * that comes from the theme outside of our custum WooCommerce
			 * stylesheet.
			 */
			add_filter( 'body_class', array( $this, 'body_class' ) );

			// Remove all WooCommerce stylesheets.
			add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

			// Add the theme's WooCommerce stylesheet as a framework dependency.
			add_filter( 'themeblvd_framework_stylesheets', array( $this, 'add_style' ) );

			// Add theme's custom WooCommerce stylesheets and scripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'assets' ), 15 );

		}

		/*
		 * Add custom product view template files.
		 *
		 * When displaying multiple products in a group, the
		 * theme splits them into three views, `grid`, `list`
		 * and `catalog`.
		 *
		 * This filter will replace WooCommerce's content-product.php
		 * with the correct template file in the theme for the
		 * current product view.
		 *
		 * Note: These product loop views DO require the theme's
		 * custom WooCommerce styling.
		 */
		if ( $do_custom_styles ) {

			add_filter( 'wc_get_template_part', array( $this, 'product_template' ), 10, 3 );

		}

		// Add any other custom template overrides.
		add_filter( 'woocommerce_locate_template', array( $this, 'templates' ), 10, 3 );

		/**
		 * Filters whether the theme adds custom WooCommerce
		 * thumbnail sizes.
		 *
		 * @since @@name-framework 2.5.0
		 *
		 * @param bool Whether to let theme modify WooCommerce images sizes.
		 */
		if ( apply_filters( 'themeblvd_woocommerce_images', true ) ) {

			add_image_size( 'shop_thumbnail', '200', '200', true );

			add_image_size( 'shop_catalog', '800', '800', true );

			add_image_size( 'shop_single', '800', '800', true );

		}

		/*
		 * Add support for "epic thumbnails".
		 *
		 * This refers to the big featured images, the
		 * theme displays prominently above the content.
		 */
		add_action( 'wp', array( $this, 'thumb_epic' ), 11 ); // After framework's set_atts().

		// Modify WooCommerce settings page.
		add_filter( 'woocommerce_product_settings', array( $this, 'remove_options' ) );

		// Add HTML before a single product.
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'product_open' ), 5 );

		// Add HTML after a single product.
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'product_close' ), 1 );

		// Add frontend framework contextual classes.
		add_filter( 'woocommerce_sale_flash', array( $this, 'sale_flash' ) );

		add_filter( 'woocommerce_sale_price_html', array( $this, 'sale_price' ) );

		add_filter( 'woocommerce_get_availability', array( $this, 'availability' ) );

		// Add `clearfix` class to comments on product pages.
		add_filter( 'comment_class', array( $this, 'comment_class' ), 10, 3 );

		/*
		 * Set framework config of post ID to original shop page
		 * ID, so theme settings for the page can get applied.
		 */
		add_filter( 'themeblvd_frontend_config_post_id', array( $this, 'set_shop_id' ) );

		// Set global shop attribues.
		add_action( 'wp', array( $this, 'set_atts' ) );

		// Start wrapping HTML before product loop.
		add_action( 'woocommerce_before_shop_loop', array( $this, 'loop_open' ) );

		// Start product loop header.
		add_action( 'woocommerce_before_shop_loop', array( $this, 'loop_header_start' ), 15 );

		// End product loop header.
		add_action( 'woocommerce_before_shop_loop', array( $this, 'loop_header_end' ), 40 );

		// Finish wrapping HTML after product loop.
		add_action( 'woocommerce_after_shop_loop', array( $this, 'loop_close' ) );

		/*
		 * Remove default WooCommerce page title.
		 *
		 * Since we're displaying it from `woocommerce_before_shop_loop`,
		 * we'll remove it here.
		 */
		add_filter( 'woocommerce_show_page_title', '__return_false' );

		// Start wrapping HTML for individual product in loop.
		add_filter( 'woocommerce_before_shop_loop_item', array( $this, 'loop_product_open' ), 5 );

		// End wrapping HTML for individual product in loop.
		add_filter( 'woocommerce_after_shop_loop_item', array( $this, 'loop_product_close' ) );

		/*
		 * Handle custom image display for loop products, only if
		 * custom styles are enabled.
		 */
		if ( $do_custom_styles ) {

			// Remove default display for product image in product loop.
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );

			// Add Custom display for product image in product loop.
			add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'show_product_image' ), 20 );

			// Remove the default rating display, becuase we're adding it to the product image.
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

		}

		// Add to theme's breadcrumbs.
		add_filter( 'themeblvd_pre_breadcrumb_parts', array( $this, 'add_breadcrumb' ), 10, 2 );

		// Modify the products to display per page, in a product loop.
		add_filter( 'loop_shop_per_page', array( $this, 'per_page' ) );

		/*
		 * Adjust up sell and cross sell displays.
		 *
		 * Note: This requires the theme's custom WooCommerce
		 * styling.
		 */
		if ( $do_custom_styles ) {

			// Remove default display for upsells.
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

			// Add custom display for upsells.
			add_action( 'woocommerce_after_single_product_summary', array( $this, 'up_sell' ), 15 );

			// Remove default display for cross sells.
			remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

			// Add custom display for cross sells.
			add_action( 'themeblvd_content_bottom', array( $this, 'cross_sell' ) );

		}

		/*
		 * Set global attributes used in shortcodes
		 * that display product loops.
		 *
		 * Make sure they display the correct number of columns
		 * and accept our "view" attribute.
		 */
		add_filter( 'shortcode_atts_recent_products', array( $this, 'shortcode_set_view' ), 10, 3 );

		add_filter( 'shortcode_atts_featured_products', array( $this, 'shortcode_set_view' ), 10, 3 );

		add_filter( 'shortcode_atts_products', array( $this, 'shortcode_set_view' ), 10, 3 );

		add_filter( 'shortcode_atts_product_category', array( $this, 'shortcode_set_view' ), 10, 3 );

		add_filter( 'shortcode_atts_sale_products', array( $this, 'shortcode_set_view' ), 10, 3 );

		add_filter( 'shortcode_atts_best_selling_products', array( $this, 'shortcode_set_view' ), 10, 3 );

		add_filter( 'shortcode_atts_related_products', array( $this, 'shortcode_set_view' ), 10, 3 );

		add_filter( 'shortcode_atts_top_rated_products', array( $this, 'shortcode_set_view' ), 10, 3 );

		/*
		 * Modify attributes passed to WooCommerce
		 * `[product_categories]` shortcode.
		 *
		 * Allow category thumbnail display to have correct
		 * number of columns inputted by the user.
		 */
		add_filter( 'shortcode_atts_product_categories', array( $this, 'shortcode_categories_set_view' ), 10, 3 );

		// Modify WooCommerce search form.
		add_filter( 'get_product_search_form', array( $this, 'search_form' ) );

		// Modify button classes for notices.
		add_filter( 'woocommerce_add_message', array( $this, 'notice_message' ) );

		// Set sidebar layout for WooCommerce pages.
		add_filter( 'themeblvd_sidebar_layout', array( $this, 'sidebar_layout' ) );

		// Set number of columns for a product loop.
		add_filter( 'loop_shop_columns', array( $this, 'loop_columns' ) );

		// Tell the theme to display a floating shopping cart.
		add_filter( 'themeblvd_do_cart', array( $this, 'do_cart' ) );

		// Add WooCommerce-specific output to theme's floating shopping cart.
		add_action( 'themeblvd_floating_cart', array( $this, 'cart' ) );

		// Add the floating shopping cart to the website's output.
		add_action( 'themeblvd_after', array( $this, 'add_cart' ) );

		// Add Ajax capability to floating cart link.
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_link_fragment' ) );

	}

	/**
	 * Get current WooCommerce version.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @return int Current WooCommerce version, like `2.0.0`.
	 */
	public function get_version() {

		if ( defined( 'WC_VERSION' ) ) {

			return WC_VERSION;

		}

		return 0;

	}

	/**
	 * Add any WooCommerce theme support.
	 *
	 * This method is hooked to:
	 * 1. `add_theme_support` - 5
	 *
	 * @since @@name-framework 2.6.5
	 */
	public function add_theme_support() {

		add_theme_support( 'wc-product-gallery-lightbox' );

		add_theme_support( 'wc-product-gallery-slider' );

		if ( 'no' !== themeblvd_get_option( 'woo_product_zoom' ) ) {

			add_theme_support( 'wc-product-gallery-zoom' );

		}

	}

	/**
	 * Add body class, that can optionally be used
	 * for styling that comes from the theme outside
	 * of our custum WooCommerce stylesheet.
	 *
	 * This method is filtered onto:
	 * 1. `body_class` - 10
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param  array $class Current classes for <body>.
	 * @return array $class Modified classes for <body>.
	 */
	public function body_class( $class ) {

		$class[] = 'tb-wc-styles';

		return $class;

	}

	/**
	 * Add custom stylesheets and scripts to
	 * modify WooCommerce.
	 *
	 * This method is hooked to:
	 * 1. `wp_enqueue_scripts` - 15
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function assets() {

		$handler = Theme_Blvd_Stylesheet_Handler::get_instance();

		$deps = $handler->get_framework_deps();

		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style(
			'themeblvd-woocommerce',
			esc_url( TB_FRAMEWORK_URI . "/compat/assets/css/woocommerce{$suffix}.css" ),
			$deps,
			TB_FRAMEWORK_VERSION
		);

		/*
		 * Remove increment button styling, if using WooCommerce
		 * Quantity Increment plugin.
		 */
		wp_dequeue_style( 'wcqi-css' );

	}

	/**
	 * Add theme's WooCommerce stylesheet to framework
	 * dependencies.
	 *
	 * This will make sure our woocommerce.css file
	 * comes between framework styles and child
	 * theme's style.css.
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

		$deps[] = 'woocommerce';

		return $deps;

	}

	/**
	 * Override content-product.php template file
	 * with custom template files for different
	 * product views the theme adds.
	 *
	 * Possible product views can be `grid`, `list`
	 * or `catalog`.
	 *
	 * Note: WooCommerce formats file names like
	 * "{$slug}-{$name}.php".
	 *
	 * This method is filtered onto:
	 * 1. `wc_get_template_part` - 10
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param  string $template File location for template file.
	 * @param  string $slug     Slug of the filename from "{$slug}-{$name}.php".
	 * @param  string $name     Name of the filename from "{$slug}-{$name}.php".
	 * @return string $template Modified file location for template file.
	 */
	public function product_template( $template, $slug, $name ) {

		if ( 'content' === $slug && 'product' === $name ) {

			$view = $this->loop_view(); // Possible views: `grid`, `list` or `catalog`

			if ( in_array( $view, array( 'list', 'catalog' ) ) ) {

				/**
				 * Filters the WooCommerce template file used,
				 * for the specified custom Theme Blvd product
				 * view.
				 *
				 * Possible filter names used here with $view:
				 * 1. `themeblvd_woocommerce_grid_template`
				 * 2. `themeblvd_woocommerce_list_template`
				 * 3. `themeblvd_woocommerce_catalog_template`
				 *
				 * @since @@name-framework 2.5.0
				 *
				 * @param string Location of templat file.
				 */
				$template = apply_filters(
					"themeblvd_woocommerce_{$view}_template",
					trailingslashit( TB_FRAMEWORK_DIRECTORY ) . 'compat/templates/woocommerce/content-product-' . $view . '.php'
				);

			}
		}

		return $template;

	}

	/**
	 * Override template parts.
	 *
	 * Note: Any template files we override here can't
	 * be overriden in the typical way of being copied
	 * to a child theme. So, we're doing this ONLY to
	 * very select template files.
	 *
	 * Doing it this way is less user-friendly, but
	 * requires less server resources, as we're not adding
	 * an additional directory check for EVERY template
	 * file of WooCommerce, and only taking over the few
	 * we need to override.
	 *
	 * This method is filtered onto:
	 * 1. `woocommerce_locate_template` - 10
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param  string $template      File location for template file.
	 * @param  string $template_name Name of template, like `content-foo.php`.
	 * @param  string $template_path Path to template, like `/directory/to/woocommerce/templates`.
	 * @return string $template      Modified file location for template file.
	 */
	public function templates( $template, $template_name, $template_path ) {

		// Setup page templates to override from WooCommerce.
		$override = array(
			'loop/pagination.php' => 'loop/pagination.php',
			'loop/loop-start.php' => 'loop/loop-start.php',
			'loop/loop-end.php'   => 'loop/loop-end.php',
			'loop/orderby.php'    => 'loop/orderby.php',
		);

		// Remove templates overrides requiring custom styles.
		if ( ! themeblvd_get_option( 'woo_styles' ) ) {

			unset( $override['loop/orderby.php'] );

		}

		/**
		 * Filters the template files the theme overrides.
		 *
		 * If you need any custom template files put back to
		 * the default WooCommerce files, or want to be able
		 * to override them from your child theme in the normal
		 * way, you can unset() them from this array.
		 *
		 * @since @@name-framework 2.5.0
		 *
		 * @param array $override WooCommerce template files the theme overrides.
		 */
		$override = apply_filters( 'themeblvd_woocommerce_template_overrides', $override );

		if ( in_array( $template_name, $override ) ) {

			$file = explode( '/', $template_name );

			$template = trailingslashit( TB_FRAMEWORK_DIRECTORY ) . 'compat/templates/woocommerce/' . end( $file );

		}

		return $template;

	}

	/**
	 * Add epic thumbnail to shop pages, if applied
	 * through theme's page options.
	 *
	 * This method is hooked to:
	 * 1. `wp` - 11
	 *
	 * @since @@name-framework 2.6.0
	 */
	public function thumb_epic() {

		if ( is_shop() && function_exists( 'themeblvd_set_att' ) ) {

			$shop_id = get_option( 'woocommerce_shop_page_id' );

			if ( ! has_post_thumbnail( $shop_id ) ) {

				return;

			}

			$val = get_post_meta( $shop_id, '_tb_thumb', true );

			if ( ! $val || 'default' === $val ) {

				$val = themeblvd_get_option( 'single_thumbs', null, 'fw' );

			}

			if ( 'fs' === $val || 'fw' === $val ) {

				themeblvd_set_att( 'thumbs', $val );

				themeblvd_set_att( 'epic_thumb', true );

			}
		} elseif ( is_product_category() ) {

			// ... @TODO

		}
	}

	/**
	 * Remove options from WooCommerce settings
	 * page.
	 *
	 * This method is filtered onto:
	 * 1. `woocommerce_product_settings` - 10
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param  array $options Options for WooCommerce settings page.
	 * @return array $options Modified options for WooCommerce settings page.
	 */
	public function remove_options( $options ) {

		$remove = array();

		/**
		 * Filters whether the theme adds custom WooCommerce
		 * thumbnail sizes.
		 *
		 * @since @@name-framework 2.5.0
		 *
		 * @param bool Whether to let theme modify WooCommerce images sizes.
		 */
		if ( apply_filters( 'themeblvd_woocommerce_images', true ) ) {

			$remove[] = 'image_options';

			$remove[] = 'shop_catalog_image_size';

			$remove[] = 'shop_single_image_size';

			$remove[] = 'shop_thumbnail_image_size';

		}

		/**
		 * Filters the options removed by the theme
		 * from the WooCommerce settings page.
		 *
		 * @since @@name-framework 2.5.0
		 *
		 * @param array $remove Basic indexed array of option keys to be removed.
		 */
		$remove = apply_filters( 'themeblvd_woocommerce_remove_options', $remove );

		if ( $remove ) {

			foreach ( $options as $key => $value ) {

				if ( ! empty( $value['id'] ) && in_array( $value['id'], $remove ) ) {

					unset( $options[ $key ] );

				}
			}
		}

		return $options;

	}

	/**
	 * Add HTML to the start of displaying a
	 * single product.
	 *
	 * This method is hooked to:
	 * 1. `woocommerce_before_single_product_summary` - 5
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function product_open() {

		echo '<div class="tb-product-wrap themeblvd-gallery bg-content">';

		echo '<div class="product-wrap-inner">';

	}

	/**
	 * Add HTML to the end of displaying a
	 * single product.
	 *
	 * This method is hooked to:
	 * 1. `woocommerce_after_single_product_summary` - 1
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function product_close() {

		echo '</div><!-- .product-wrap-inner (end) -->';

		echo '</div><!-- .tb-product-wrap (end) -->';

	}

	/**
	 * Add contextual classes to onsale notice.
	 *
	 * This method is filtered onto:
	 * 1. `woocommerce_sale_flash` - 10
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param  $input  Original HTML for notice.
	 * @return $output Modified HTML for notice.
	 */
	public function sale_flash( $input ) {

		$output = str_replace( 'onsale', 'onsale bg-success', $input );

		return $output;

	}

	/**
	 * Add contextual classes to sale price
	 * display.
	 *
	 * This method is filtered onto:
	 * 1. `woocommerce_sale_price_html` - 10
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param  $input  Original HTML for notice.
	 * @return $output Modified HTML for notice.
	 */
	public function sale_price( $input ) {

		$output = str_replace(
			'<del><span class="amount">',
			'<del><span class="amount text-muted">',
			$input
		);

		return $output;

	}

	/**
	 * Add contextual classes to availability
	 * notice.
	 *
	 * This method is filtered onto:
	 * 1. `woocommerce_get_availability` - 10
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param array $args Arguments WooCommerce uses to build availability notice.
	 */
	public function availability( $args ) {

		if ( 'in-stock' === $args['class'] ) {

			$args['class'] .= ' text-success';

		} elseif ( 'out-of-stock' === $args['class'] ) {

			$args['class'] .= ' text-danger';

		} else {

			$args['class'] .= ' text-muted';

		}

		return $args;

	}

	/**
	 * Add `clearfix` to comments.
	 *
	 * This method is filtered onto:
	 * 1. `comment_class` - 10
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param  array  $classes    An array of comment classes.
	 * @param  string $class      A comma-separated list of additional classes added to the list.
	 * @param  int    $comment_id The comment id.
	 * @return array  $classes    Modified array of comment classes.
	 */
	public function comment_class( $classes, $class, $comment_id ) {

		if ( 'product' === get_post_type( $comment_id ) ) {

			$classes[] = 'clearfix';

		}

		return $classes;

	}

	/**
	 * Set framework config of post ID to original
	 * shop page ID, so theme settings for the page
	 * can get applied.
	 *
	 * This method is filtered onto:
	 * 1. `themeblvd_frontend_config_post_id` - 10
	 *
	 * @since @@name-framework 2.5.1
	 *
	 * @param  int $post_id ID of current post.
	 * @return int $post_id ID of current post.
	 */
	public function set_shop_id( $post_id ) {

		if ( is_shop() ) {

			$post_id = (int) get_option( 'woocommerce_shop_page_id' );

		}

		return $post_id;

	}

	/**
	 * Set any framework global attributes we can
	 * use in the shop.
	 *
	 * This method is hoooked to:
	 * 1. `wp` - 10
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function set_atts() {

		global $_GET;

		if ( is_admin() ) {

			return;

		}

		// Set product display view.
		$view = 'grid'; // Default view.

		$view = array( 'grid', 'list', 'catalog' );

		if ( ! empty( $_GET['view'] ) && in_array( $_GET['view'], $views ) ) {

			$view = $_GET['view'];

		} elseif ( is_product_category() || is_product_tag() || ( is_woocommerce() && is_search() ) ) {

			$view = themeblvd_get_option( 'woo_archive_view', null, $view );

		} elseif ( is_shop() ) {

			$view = themeblvd_get_option( 'woo_shop_view', null, $view );

		}

		themeblvd_set_att( 'woo_product_view', $view );

	}

	/**
	 * Start wrapping HTML before product loop.
	 *
	 * This method is hooked to:
	 * 1. `woocommerce_before_shop_loop` - 10
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function loop_open() {

		printf(
			'<div class="tb-product-loop-wrap shop-columns-%s %s-view bg-content clearfix">',
			$this->loop_columns(),
			$this->loop_view()
		);

	}

	/**
	 * Start product loop header.
	 *
	 * This method is hooked to:
	 * 1. `woocommerce_before_shop_loop` - 15
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function loop_header_start() {

		printf(
			'<header class="entry-header"><h1 class="entry-title">%s</h1>',
			woocommerce_page_title( false )
		);

	}

	/**
	 * End product loop header.
	 *
	 * This method is hooked to:
	 * 1. `woocommerce_before_shop_loop` - 40
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function loop_header_end() {

		echo '</header><!-- .entry-header -->';

	}

	/**
	 * Finish wrapping HTML after product loop.
	 *
	 * This method is hooked to:
	 * 1. `woocommerce_after_shop_loop` - 10
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function loop_close() {

		echo '</div><!-- .tb-product-loop-wrap (end) -->';

	}

	/**
	 * Start wrapping HTML for individual product
	 * in loop.
	 *
	 * This method is hooked to:
	 * 1. `woocommerce_before_shop_loop_item` - 5
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function loop_product_open() {

		echo '<div class="tb-product">';

	}

	/**
	 * End wrapping HTML for individual product
	 * in loop.
	 *
	 * This method is hooked to:
	 * 1. `woocommerce_after_shop_loop_item` - 10
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function loop_product_close() {

		echo '</div><!-- .tb-product (end) -->';

	}

	/**
	 * Custom display for product image in
	 * product loop.
	 *
	 * This method is hooked to:
	 * 1. `woocommerce_before_shop_loop_item_title` - 20
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function show_product_image() {

		global $product;

		$post_id = get_the_ID();

		$size = 'shop_catalog';

		/**
		 * Filters whether the theme adds custom WooCommerce
		 * thumbnail sizes.
		 *
		 * @since @@name-framework 2.5.0
		 *
		 * @param bool Whether to let theme modify WooCommerce images sizes.
		 */
		if ( apply_filters( 'themeblvd_woocommerce_images', true ) ) {

			$size = apply_filters( 'themeblvd_woocommerce_thumb_product', 'tb_square_medium' );

		}

		$class = "attachment-{$size}";

		echo '<span class="product-thumb">';

		// Float the rating over the thumbnail, when hovered on.
		$rating = wc_get_rating_html( $product->get_average_rating() );

		if ( $rating ) {

			$rating = str_replace( 'div', 'span', $rating );

			printf( '<span class="rating-wrap">%s</span>', $rating );

		}

		// Output first image from product gallery, to show on hover.
		$gallery = get_post_meta( $post_id, '_product_image_gallery', true );

		if ( $gallery ) {

			$gallery = explode( ',',$gallery );

			$image_id = $gallery[0];

			echo wp_get_attachment_image( $image_id, $size, false, array(
				'class' => $class . ' hover',
			));

		}

		// Output standard product image.
		the_post_thumbnail( $size, array(
			'class' => $class,
		));

		echo '</span><!-- .product-thumb (end) -->';

	}

	/**
	 * Add WooCommerce products to theme's
	 * breadcrumbs.
	 *
	 * This method is filtered onto:
	 * 1. `themeblvd_pre_breadcrumb_parts` - 10
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @see themeblvd_get_breadcrumb_parts()
	 *
	 * @param array $parts Current breadcrumbs parts.
	 * @param array $args {
	 *     Breadcrumb arguments.
	 *
	 *     @type string $delimiter HTML between breadcrumb pieces.
	 *     @type string $home      Home link text.
	 *     @type string $home_link Home link URL.
	 *     @type string $before    HTML before current breadcrumb item.
	 *     @type string $after     HTML after current breadcrumb item.
	 * }
	 * @return array $parts Modified breadcrumb parts.
	 */
	public function add_breadcrumb( $parts, $args ) {

		global $wp_query;

		if ( is_woocommerce() ) {

			$parts = array();

			if ( is_search() ) {

				$parts[] = array(
					'link' => '',
					'text' => themeblvd_get_local( 'crumb_search' ) . ' "' . get_search_query() . '"',
					'type' => 'search',
				);

			} elseif ( is_shop() ) { // Also TRUE, when is_search().

				$parts[] = array(
					'link' => '',
					'text' => get_the_title( wc_get_page_id( 'shop' ) ),
					'type' => 'page',
				);

			} elseif ( is_product_category() ) {

				// Parent categories.
				$cat_obj = $wp_query->get_queried_object();

				$current_cat = $cat_obj->term_id;

				$current_cat = get_term( $current_cat, 'product_cat' );

				if ( $current_cat->parent && ( $current_cat->parent != $current_cat->term_id ) ) {

					$parents = themeblvd_get_term_parents( $current_cat->parent, 'product_cat' );

					$parts = array_merge( $parts, $parents );

				}

				// Add current category.
				$parts[] = array(
					'link' => '',
					'text' => $current_cat->name,
					'type' => 'category',
				);

			} elseif ( is_product_tag() ) {

				$tag_obj = $wp_query->get_queried_object();

				$parts[] = array(
					'link' => '',
					'text' => sprintf( '%s "%s"', themeblvd_get_local( 'crumb_tag_products' ), $tag_obj->name ),
					'type' => 'tag',
				);

			} elseif ( is_product() ) {

				// Product category taxonomy tree.
				$cat = get_the_terms( get_the_id(), 'product_cat' );

				if ( $cat ) {

					$cat = reset( $cat );

					$parents = themeblvd_get_term_parents( $cat->term_id, 'product_cat' );

					$parts = array_merge( $parts, $parents );

				}

				$parts[] = array(
					'link' => '',
					'text' => get_the_title(),
					'type' => 'single',
				);

			}

			/*
			 * Add "Shop" page from WooCommerce settings to start
			 * of breadcrumbs trail.
			 */
			if ( ! is_shop() || ( is_shop() && is_search() ) ) {

				/**
				 * Filters whether the WooCommerce "Shop" page gets
				 * added to the start of the breadcrumbs being added
				 * throughout WooCommerce pages.
				 *
				 * @since @@name-framework 2.5.0
				 *
				 * @param bool Whether to add "Shop" page to breadcrumbs.
				 */
				if ( apply_filters( 'themeblvd_woocommerce_add_shop_to_breadcrumbs', true ) ) {

					$shop_id = wc_get_page_id( 'shop' );

					if ( $shop_id && -1 != $shop_id ) {

						$add = array(
							array(
								'link' => get_permalink( wc_get_page_id( 'shop' ) ),
								'text' => get_the_title( wc_get_page_id( 'shop' ) ),
								'type' => 'page',
							),
						);

						$parts = array_merge( $add, $parts );

					}
				}
			}
		}

		return $parts;

	}

	/**
	 * Customize number of products per page,
	 * when displaying a product loop.
	 *
	 * This method is filtered onto:
	 * 1. `loop_shop_per_page` - 10
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param  string|int $num Number of products per page.
	 * @return int        $num Modified number of products per page.
	 */
	public function per_page( $num ) {

		if ( ! empty( $_GET['per_page'] ) ) {

			$num = $_GET['per_page'];

		} elseif ( is_product_category() || is_product_tag() || is_search() ) {

			$num = themeblvd_get_option( 'woo_archive_per_page', null, '12' );

		} elseif ( is_shop() ) {

			$num = themeblvd_get_option( 'woo_shop_per_page', null, '12' );

		}

		return intval( $num );

	}

	/**
	 * Add custom upsell display.
	 *
	 * This custom display override WooCommerce's
	 * `woocommerce_upsell_display()`.
	 *
	 * This method is hooked to:
	 * 1. `woocommerce_after_single_product_summary` - 15
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function up_sell() {

		/**
		 * Filters the arguments used for the theme's
		 * custom display of product upsells.
		 *
		 * @since @@name-framework 2.5.0
		 *
		 * @param array {
		 *     Upsell arguments.
		 *
		 *     @type string|int $posts_per_page Number of posts per page.
		 *     @type string     $orderby        How to order products, like `rand`, `name`, etc.
		 *     @type string     $order          Direction to order, `ASC` or `DESC`.
		 *     @type string|int $columns        Number of columns.
		 * }
		 */
		$args = apply_filters( 'themeblvd_woocommerce_up_sell_args', array(
			'posts_per_page' => '-1',
			'orderby'        => apply_filters( 'woocommerce_upsells_orderby', 'rand' ), // WooCoomerce default filter; let's keep it for compat.
			'order'          => 'desc',
			'columns'        => $this->loop_columns(),
		));

		woocommerce_upsell_display(
			$args['posts_per_page'],
			$args['columns'],
			$args['orderby'],
			$args['order']
		);

	}

	/**
	 * Add custom cross sell display.
	 *
	 * This custom display override WooCommerce's
	 * `woocommerce_cross_sell_display()`.
	 *
	 * This method is hooked to:
	 * 1. `themeblvd_content_bottom` - 10
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function cross_sell() {

		if ( is_cart() && 'yes' === themeblvd_get_option( 'woo_cross_sell' ) ) {

			/**
			 * Filters the arguments used for the theme's
			 * custom display of product cross sells.
			 *
			 * @since @@name-framework 2.5.0
			 *
			 * @param array {
			 *     Upsell arguments.
			 *
			 *     @type string|int $posts_per_page Number of posts per page.
			 *     @type string     $orderby        How to order products, like `rand`, `name`, etc.
			 *     @type string     $order          Direction to order, `ASC` or `DESC`.
			 *     @type string|int $columns        Number of columns.
			 * }
			 */
			$args = apply_filters( 'themeblvd_woocommerce_cross_sell_args', array(
				'posts_per_page' => '-1',
				'order'          => 'desc',
				'orderby'        => 'rand',
				'columns'        => $this->loop_columns(),
			));

			woocommerce_cross_sell_display(
				$args['posts_per_page'],
				$args['columns'],
				$args['orderby'],
				$args['order']
			);

		}

	}

	/**
	 * Set global attributes used in shortcodes
	 * that display product loops.
	 *
	 * Make sure they display the correct number
	 * of columns and accept our "view" attribute.
	 *
	 * Note: We're filtering attributes passed to
	 * various shortcodes, but really we're just
	 * utilizing it as an action hook and passing
	 * the attributes back through, unmodified.
	 *
	 * This method is filtered onto:
	 * 1. `shortcode_atts_recent_products` - 10
	 * 2. `shortcode_atts_featured_products` - 10
	 * 3. `shortcode_atts_products` - 10
	 * 4. `shortcode_atts_product_category` - 10
	 * 5. `shortcode_atts_sale_products` - 10
	 * 6. `shortcode_atts_best_selling_products` - 10
	 * 7. `shortcode_atts_related_products` - 10
	 * 8. `shortcode_atts_top_rated_products` - 10
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param  array $out   The output array of shortcode attributes.
	 * @param  array $pairs The supported attributes and their defaults.
	 * @param  array $atts  The user defined shortcode attributes.
	 * @return array $out   The output array of shortcode attributes.
	 */
	public function shortcode_set_view( $out, $pairs, $atts ) {

		if ( ! function_exists( 'themeblvd_set_att' ) ) {

			return $atts;

		}

		if ( ! empty( $atts['columns'] ) ) {

			themeblvd_set_att( 'woo_product_columns', $atts['columns'] );

		}

		if ( isset( $atts['view'] ) && in_array( $atts['view'], array( 'list', 'grid', 'catalog' ) ) ) {

			themeblvd_set_att( 'woo_product_view_reset', themeblvd_get_att( 'woo_product_view' ) );

			themeblvd_set_att( 'woo_product_view', $atts['view'] );

		}

		return $atts; // Pass $atts back through, unmodified.

	}

	/**
	 * Set the number of columns when using WooCommerce
	 * [product_categories] shortcode.
	 *
	 * This method is filtered onto:
	 * 1. `shortcode_atts_product_categories` - 10
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param  array $out   The output array of shortcode attributes.
	 * @param  array $pairs The supported attributes and their defaults.
	 * @param  array $atts  The user defined shortcode attributes.
	 * @return array $out   The output array of shortcode attributes.
	 */
	public function shortcode_categories_set_view( $out, $pairs, $atts ) {

		if ( ! function_exists( 'themeblvd_set_att' ) ) {

			return $out;

		}

		if ( ! empty( $atts['columns'] ) ) {

			themeblvd_set_att( 'woo_product_columns', $atts['columns'] );

		}

		themeblvd_set_att( 'woo_product_view_reset', themeblvd_get_att( 'woo_product_view' ) );

		themeblvd_set_att( 'woo_product_view', 'grid' ); // Categories always displayed in a grid.

		return $out; // Pass $atts back through, unmodified.

	}

	/**
	 * Reset global template variables.
	 *
	 * After the current loop, check for what we did
	 * in `shortcode_set_view()`, and reset everything
	 * back. Function is called in loop-end.php.
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function shortcode_reset_view() {

		if ( ! function_exists( 'themeblvd_set_att' ) ) {

			return;

		}

		if ( themeblvd_get_att( 'woo_product_columns' ) ) {

			themeblvd_set_att( 'woo_product_columns', null );

		}

		$reset = themeblvd_get_att( 'woo_product_view_reset' );

		if ( $reset ) {

			themeblvd_set_att( 'woo_product_view', $reset );

			themeblvd_set_att( 'woo_product_view_reset', null );

		}
	}

	/**
	 * Modify WooCommerce search form.
	 *
	 * This method is filtered onto:
	 * 1. `get_product_search_form` - 10
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param string $html HTML for search form.
	 * @param string       Modified HTML for search form.
	 */
	public function search_form( $html ) {

		$html = get_search_form( false );

		$add = '<input type="hidden" name="post_type" value="product" />';

		$find = '<button class="search-submit btn-primary" type="submit">';

		return str_replace( $find, $add . "\n\t\t\t" . $find, $html );

	}

	/**
	 * Modify button classes for notices.
	 *
	 * This method is filtered onto:
	 * 1. `woocommerce_add_message` - 10
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param  string $message Notice message.
	 * @return string $message Modified notice message.
	 */
	function notice_message( $message ) {

		return str_replace(
			'button wc-forward',
			'wc-forward btn btn-primary btn-xs',
			$message
		);

	}

	/**
	 * Set sidebar layout for WooCommerce pages.
	 *
	 * This method is filtered onto:
	 * 1. `themeblvd_sidebar_layout` - 10
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param  string $layout Current sidebar layout.
	 * @return string $layout Modified sidebar layout.
	 */
	public function sidebar_layout( $layout ) {

		if ( is_product() ) {

			$layout = themeblvd_get_option( 'woo_product_sidebar_layout' );

		} elseif ( is_shop() && ! is_search() ) {

			$layout = themeblvd_get_option( 'woo_shop_sidebar_layout' );

		} elseif ( is_woocommerce() ) {

			$layout = themeblvd_get_option( 'woo_archive_sidebar_layout' );

		}

		return $layout;

	}

	/**
	 * Set number of columns for a product loop.
	 *
	 * This method is filtered onto:
	 * 1. `loop_shop_columns` - 10
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param  int $cols Current number of columns.
	 * @return int $cols Modified number of columns.
	 */
	public function loop_columns( $cols = 4 ) {

		if ( themeblvd_get_att( 'woo_product_columns' ) ) {

			$cols = themeblvd_get_att( 'woo_product_columns' );

		} elseif ( is_product_category() || is_product_tag() || is_search() ) {

			$cols = themeblvd_get_option( 'woo_archive_columns' );

		} elseif ( is_shop() ) {

			$cols = themeblvd_get_option( 'woo_shop_columns' );

		}

		return intval( $cols );

	}

	/**
	 * Get current shop view.
	 *
	 * The shop view can be `grid`, `list` or `catalog`.
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function loop_view() {

		$view = themeblvd_get_att( 'woo_product_view' );

		if ( ! $view ) {

			$view = 'grid';

		}

		/**
		 * Filters the theme's custom view value used
		 * to display a product loop.
		 *
		 * By default the view can be `grid`, `list`
		 * or `catalog`.
		 *
		 * @param string $view Current shop view.
		 */
		return apply_filters( 'themeblvd_product_view', $view );

	}

	/**
	 * Tell the theme to display a floating
	 * shopping cart.
	 *
	 * This method is filtered onto:
	 * 1. `themeblvd_do_cart` - 10
	 *
	 * @since @@name-framework 2.6.1
	 *
	 * @return bool Whether to display the floating shopping cart.
	 */
	public function do_cart() {

		if ( 'no' !== themeblvd_get_option( 'woo_floating_cart' ) ) {

			return true;

		}

		return false;

	}

	/**
	 * Add WooCommerce-specific output to theme's
	 * floating shopping cart.
	 *
	 * This method is hooked to:
	 * 1. `themeblvd_floating_cart` - 10
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function cart() {

		the_widget( 'WC_Widget_Cart', 'title=' );

	}

	/**
	 * Add the hidden floating shopping cart to
	 * the website's output.
	 *
	 * This method is hooked to:
	 * 1. `themeblvd_after` - 10
	 *
	 * @since @@name-framework 2.5.0
	 */
	public function add_cart() {

		if ( ! is_cart() && ! is_checkout() ) {

			themeblvd_cart_popup();

		}

	}

	/**
	 * Add Ajax capability to floating cart link.
	 *
	 * Specifically, allows the cart item count on
	 * the floating shopping cart link to update
	 * without a page refresh, as items are added
	 * to the cart.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param  array $fragments Fragements used for WooCommerce cart Ajax.
	 * @return array $fragments Modified fragements used for WooCommerce cart Ajax.
	 */
	public function cart_link_fragment( $fragments ) {

		$fragments['.tb-woocommerce-cart-popup-link'] = themeblvd_get_cart_popup_trigger();

		$fragments['.tb-woocommerce-cart-page-link'] = themeblvd_get_cart_popup_trigger( array(
			'target' => null,
		));

		$fragments['#mobile-to-cart'] = themeblvd_get_mobile_cart_link();

		return $fragments;

	}

}
