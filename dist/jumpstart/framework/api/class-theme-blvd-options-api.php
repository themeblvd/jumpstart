<?php
/**
 * Theme Options API
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.3.0
 */

/**
 * Setup theme options.
 *
 * This class establishes all of the Theme Blvd
 * framework's theme options, and sets up the API
 * system to allow these options to be modified
 * from the client-side.
 *
 * Also, this class provides access to the saved
 * settings corresponding to the these theme options.
 *
 * Note: This class does not setup the options system;
 * it sets up the default framework options which then
 * later get sent to the options system.
 *
 * @since Theme_Blvd 2.3.0
 */
class Theme_Blvd_Options_API {

	/**
	 * A single instance of this class.
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var Theme_Blvd_Options_API
	 */
	private static $instance = null;

	/**
	 * The options name associated with the the theme
	 * options and settings. i.e. get_option( $option_id )
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var string
	 */
	private $option_id = '';

	/**
	 * Raw options modified along the way by client.
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var array
	 */
	private $raw_options = array();

	/**
	 * Formatted options after client modifications.
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var array
	 */
	private $formatted_options = array();

	/**
	 * Settings saved in the DB for the current site.
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var array
	 */
	private $settings = array();

	/**
	 * The arguments for the Theme Options page that'll
	 * get passed through to Theme_Blvd_Options_Page class.
	 *
	 * @since Theme_Blvd 2.3.0
	 * @var array
	 */
	private $args = array();

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @return Theme_Blvd_Options_API A single instance of this class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {

			self::$instance = new self;

		}

		return self::$instance;

	}

	/**
	 * Constructor. Hook everything in and setup API.
	 *
	 * @since Theme_Blvd 2.3.0
	 */
	private function __construct() {

		$this->set_raw_options();

		$this->set_option_id();

		add_action( 'after_setup_theme', array( $this, 'set_formatted_options' ), 1000 );

		add_action( 'after_setup_theme', array( $this, 'set_settings' ), 1000 );

		add_action( 'after_setup_theme', array( $this, 'set_args' ), 1000 );

	}

	/**
	 * Set option name that options and settings will
	 * be associated with.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param string $id Optional current ID to be applied.
	 */
	public function set_option_id( $id = '' ) {

		if ( $id ) {

			$this->option_id = $id;

		} else {

			$theme_data = wp_get_theme( get_stylesheet() );

			$this->option_id = preg_replace( '/\W/', '', strtolower( $theme_data->get( 'Name' ) ) ); // Lowercase, without spaces.

		}

		/**
		 * Filters the option ID one time, at the time
		 * it's set.
		 *
		 * The option ID is used to retrieve the theme's
		 * options like `get_option( $option_id )`.
		 *
		 * This is an alternative to using the filter
		 * `themeblvd_option_id` which gets applied every
		 * time an option ID is retrieved.
		 *
		 * @since Theme_Blvd 2.3.0
		 *
		 * @param string $option_id ID options are saved to in the database.
		 */
		$this->option_id = apply_filters( 'themeblvd_set_option_id', $this->option_id );

	}

	/**
	 * Setup raw options array for the start of the
	 * API process.
	 *
	 * Note: The framework used to reference these as
	 * "core options" before this class existed.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * Layout
	 *  - Header
	 *      - logo
	 *      - header_text
	 *      - social_media
	 *      - social_media_style
	 *      - searchform
	 *  - Mobile Header
	 *      - mobile_logo
	 *  - Transparent Header
	 *      - trans_logo
	 *      - trans_social_media_style
	 *  - Footer
	 *      - footer_setup
	 *      - footer_col_1
	 *      - footer_col_2
	 *      - footer_col_3
	 *      - footer_col_4
	 *      - footer_col_5
	 *      - footer_copyright
	 *  - Sidebar Layouts
	 *      - sidebar_layout
	 *      - single_sidebar_layout
	 *      - page_sidebar_layout
	 *      - archive_sidebar_layout
	 *  - Extras
	 *      - breadcrumbs
	 *      - scroll_to_top
	 * Content
	 *  - General
	 *      - fw_narrow
	 *      - img_popout
	 *      - gallery_carousel
	 *      - scroll_effects
	 *  - Single Posts
	 *      - single_meta
	 *      - single_sub_meta
	 *      - share
	 *      - single_thumbs
	 *      - single_related_posts
	 *      - single_comments
	 *  - Pages
	 *      - page_thumbs
	 *  - Blog Homepage
	 *      - home_mode
	 *  - Archives
	 *      - archive_mode
	 *      - category_info
	 *      - tag_info
	 *  - Blog
	 *      - blog_thumbs
	 *      - blog_meta
	 *      - blog_sub_meta
	 *      - blog_content
	 *      - blog_categories
	 *  - Post Lists
	 *      - list_thumbs
	 *      - list_meta
	 *      - list_sub_group_start
	 *      - list_more
	 *      - list_more_text
	 *      - list_sub_group_end
	 *      - list_posts_per_page
	 *  - Post Grids
	 *      - grid_sub_group_start_1
	 *      - grid_thumbs
	 *      - grid_crop
	 *      - grid_sub_group_end_1
	 *      - grid_meta
	 *      - grid_excerpt
	 *      - grid_sub_group_start_2
	 *      - grid_more
	 *      - grid_more_text
	 *      - grid_sub_group_end_2
	 *      - grid_sub_group_start_3
	 *      - grid_display
	 *      - grid_columns
	 *      - grid_rows
	 *      - grid_posts_per_page
	 *      - grid_sub_group_end_3
	 *  - Post Showcase
	 *      - showcase_crop
	 *      - showcase_titles
	 *      - showcase_excerpt
	 *      - showcase_gutters
	 *      - showcase_sub_group_start_1
	 *      - showcase_display
	 *      - showcase_columns
	 *      - showcase_rows
	 *      - showcase_posts_per_page
	 *      - showcase_sub_group_end_1
	 *  - Lightbox
	 *      - lightbox_animation
	 *      - lightbox_mobile
	 *      - lightbox_mobile_iframe
	 *      - lightbox_mobile_gallery
	 * Configuration
	 *  - Google Maps
	 *      - gmap_api_key
	 * Plugins
	 *  - bbPress
	 *      - bbp_styles
	 *      - bbp_naked_page
	 *      - bbp_sidebar_layout
	 *      - bbp_topic_sidebar_layout
	 *      - bbp_user_sidebar_layout
	 *  - Gravity Forms
	 *      - gforms_styles
	 *  - WooCommerce
	 *      - woo_styles
	 *      - woo_shop_view
	 *      - woo_shop_columns
	 *      - woo_shop_per_page
	 *      - woo_archive_view
	 *      - woo_archive_columns
	 *      - woo_archive_per_page
	 *      - woo_shop_sidebar_layout
	 *      - woo_archive_sidebar_layout
	 *      - woo_product_sidebar_layout
	 *      - woo_cross_sell
	 *      - woo_view_toggle
	 *      - woo_product_zoom
	 *  - WPML
	 *      - wpml_show_lang_switcher
	 */
	private function set_raw_options() {

		// If using image radio buttons, define a directory path.
		$imagepath = get_template_directory_uri() . '/framework/admin/assets/img/';

		// Generate sidebar layout options.
		$sidebar_layouts = array();

		if ( is_admin() ) {

			$layouts = themeblvd_sidebar_layouts();

			foreach ( $layouts as $layout ) {

				$sidebar_layouts[ $layout['id'] ] = $imagepath . 'layout-' . $layout['id'] . '.png';

			}
		}

		// Pull all the categories into an array.
		$options_categories = array();

		if ( is_admin() ) {

			$options_categories_obj = get_categories( array(
				'hide_empty' => false,
			));

			foreach ( $options_categories_obj as $category ) {

				$options_categories[ $category->cat_ID ] = $category->cat_name;

			}
		}

		/*--------------------------------*/
		/* Tab #1: Layout
		/*--------------------------------*/

		$layout_options = array(

			// Section: Header
			'header' => array(
				'name'    => __( 'Header', 'jumpstart' ),
				'options' => array(
					'logo' => array(
						'name'    => __( 'Logo', 'jumpstart' ),
						'desc'    => __( 'Configure the primary branding logo for the header of your site.', 'jumpstart' ) . '<br /><br /><em>' . __( 'Note: If you\'re inputting a "HiDPI-optimized" image, it needs to be twice as large as you intend it to be displayed, and have the same aspect ratio as the standard image. Feel free to leave the HiDPI image field blank if you\'d like it to simply not have any effect.', 'jumpstart' ) . '</em>',
						'id'      => 'logo',
						'std'     => array(
							'type'         => 'image',
							'image'        => get_template_directory_uri() . '/assets/img/logo.png',
							'image_width'  => '250',
							'image_height' => '75',
							'image_2x'     => get_template_directory_uri() . '/assets/img/logo_2x.png',
						),
						'type'    => 'logo',
					),
					'header_text' => array(
						'name'    => __( 'Header Text', 'jumpstart' ),
						// translators: 1: link to FontAwesome, 2: formatting for option input
						'desc'    => sprintf( __( 'Enter a brief piece of text you\'d like to show. You can use basic HTML here, or any %1$s ID formatted like %2$s.', 'jumpstart' ), '<a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">FontAwesome</a>', '<code>%name%</code>' ),
						'id'      => 'header_text',
						'std'     => '%phone% 1-800-555-5555 %envelope% admin@yoursite.com',
						'type'    => 'text',
					),
					'social_media' => array(
						'name'    => __( 'Social Media Buttons', 'jumpstart' ),
						'desc'    => __( 'Configure the social media buttons you\'d like to show.', 'jumpstart' ),
						'id'      => 'social_media',
						'std'     => array(
							'item_1' => array(
								'icon'   => 'facebook',
								'url'    => 'http://facebook.com/jasonbobich',
								'label'  => 'Facebook',
								'target' => '_blank',
							),
							'item_2' => array(
								'icon'   => 'google',
								'url'    => 'https://plus.google.com/116531311472104544767/posts',
								'label'  => 'Google+',
								'target' => '_blank',
							),
							'item_3' => array(
								'icon'   => 'twitter',
								'url'    => 'http://twitter.com/jasonbobich',
								'label'  => 'Twitter',
								'target' => '_blank',
							),
							'item_4' => array(
								'icon'   => 'rss',
								'url'    => get_feed_link(),
								'label'  => 'RSS Feed',
								'target' => '_blank',
							),
						),
						'type'    => 'social_media',
					),
					'social_media_style' => array(
						'name'    => __( 'Social Media Style', 'jumpstart' ),
						'desc'    => __( 'Select the color you\'d like applied to the social icons.', 'jumpstart' ),
						'id'      => 'social_media_style',
						'std'     => 'grey',
						'type'    => 'select',
						'options' => array(
							'flat'  => __( 'Flat Color', 'jumpstart' ),
							'dark'  => __( 'Flat Dark', 'jumpstart' ),
							'grey'  => __( 'Flat Grey', 'jumpstart' ),
							'light' => __( 'Flat Light', 'jumpstart' ),
							'color' => __( 'Color', 'jumpstart' ),
						),
					),
					'searchform' => array(
						'name'    => __( 'Search Form', 'jumpstart' ),
						'desc'    => __( 'Select whether you\'d like to show a search form.', 'jumpstart' ),
						'id'      => 'searchform',
						'std'     => 'show',
						'type'    => 'select',
						'options' => array(
							'show' => __( 'Show search form', 'jumpstart' ),
							'hide' => __( 'Hide search form', 'jumpstart' ),
						),
					),
				), // End header options.
			),

			// Section: Mobile Header
			'header_mobile' => array(
				'name'    => __( 'Mobile Header', 'jumpstart' ),
				'options' => array(
					'mobile_logo' => array(
						'name'     => __( 'Mobile Logo', 'jumpstart' ),
						'desc'     => __( 'Configure the primary branding logo for the mobile header of your site. The mobile header is 64px tall; so you\'ll want to use an image shorter than that.', 'jumpstart' ) . '<br /><br /><em>' . __( 'Note: If you\'re inputting a "HiDPI-optimized" image, it needs to be twice as large as you intend it to be displayed, and have the same aspect ratio as the standard image. Feel free to leave the HiDPI image field blank if you\'d like it to simply not have any effect.', 'jumpstart' ) . '</em>',
						'id'       => 'mobile_logo',
						'std'      => array(
							'type'         => 'image',
							'image'        => get_template_directory_uri() . '/assets/img/logo-smaller-light.png',
							'image_width'  => '85',
							'image_height' => '25',
							'image_2x'     => get_template_directory_uri() . '/assets/img/logo-smaller-light_2x.png',
						),
						'type'     => 'logo',
					),
				), // End mobile header options.
			),

			// Section: Transparent Header
			'header_trans' => array(
				'name'    => __( 'Transparent Header', 'jumpstart' ),
				'desc'    => __( 'When you\'re configuring a page, if you select "Transparent Header" in the Theme Layout box, here you can setup special options for how the header displays over your content, which has been sucked up beneath. This feature will work best with a full-width or full-screen featured image, or a when custom layout is applied to the page.', 'jumpstart' ),
				'options' => array(
					'trans_logo' => array(
						'name'    => __( 'Logo', 'jumpstart' ),
						'desc'    => __( 'Configure the primary branding logo for the header of your site.', 'jumpstart' ) . '<br /><br /><em>' . __( 'Note: If you\'re inputting a "HiDPI-optimized" image, it needs to be twice as large as you intend it to be displayed, and have the same aspect ratio as the standard image. Feel free to leave the HiDPI image field blank if you\'d like it to simply not have any effect.', 'jumpstart' ) . '</em>',
						'id'      => 'trans_logo',
						'std'     => array(
							'type'         => 'image',
							'image'        => get_template_directory_uri() . '/assets/img/logo-light.png',
							'image_width'  => '250',
							'image_height' => '75',
							'image_2x'     => get_template_directory_uri() . '/assets/img/logo-light_2x.png',
						),
						'type'    => 'logo',
					),
					'trans_social_media_style' => array(
						'name'    => __( 'Social Media Style', 'jumpstart' ),
						'desc'    => __( 'Select the color you\'d like applied to the social icons.', 'jumpstart' ),
						'id'      => 'trans_social_media_style',
						'std'     => 'light',
						'type'    => 'select',
						'options' => array(
							'flat'  => __( 'Flat Color', 'jumpstart' ),
							'dark'  => __( 'Flat Dark', 'jumpstart' ),
							'grey'  => __( 'Flat Grey', 'jumpstart' ),
							'light' => __( 'Flat Light', 'jumpstart' ),
							'color' => __( 'Color', 'jumpstart' ),
						),
					),
				), // End trans header options.
			),

			// Section: Footer
			'footer' => array(
				'name'    => __( 'Footer', 'jumpstart' ),
				'options' => array(
					'start_footer_cols' => array(
						'type'    => 'subgroup_start',
						'class'   => 'columns standard-footer-setup',
					),
					'footer_setup' => array(
						'name'    => __( 'Setup Columns', 'jumpstart' ),
						'desc'    => null,
						'id'      => 'footer_setup',
						'std'     => '1/4-1/4-1/4-1/4',
						'std'     => '',
						'type'    => 'columns',
						'options' => 'standard',
					),
					'footer_col_1' => array(
						'name'    => __( 'Footer Column #1', 'jumpstart' ),
						'desc'    => __( 'Configure the content for the first column.', 'jumpstart' ),
						'id'      => 'footer_col_1',
						'std'     => array(
							'type'       => 'raw',
							'raw'        => "<h3 class=\"widget-title\">About Us</h3>\n\nHey there! Edit me from WP Admin > Appearance > Theme Options > Layout > Footer.\n\n%phone% 907.555.1234\n%envelope% you@youremail.com",
							'raw_format' => 1,
						),
						'type'    => 'content',
						'class'   => 'col_1',
						'options' => array( 'widget', 'page', 'raw' ),
					),
					'footer_col_2' => array(
						'name'    => __( 'Footer Column #2', 'jumpstart' ),
						'desc'    => __( 'Configure the content for the second column.', 'jumpstart' ),
						'id'      => 'footer_col_2',
						'type'    => 'content',
						'class'   => 'col_2',
						'options' => array( 'widget', 'page', 'raw' ),
					),
					'footer_col_3' => array(
						'name'    => __( 'Footer Column #3', 'jumpstart' ),
						'desc'    => __( 'Configure the content for the third column.', 'jumpstart' ),
						'id'      => 'footer_col_3',
						'type'    => 'content',
						'class'   => 'col_3',
						'options' => array( 'widget', 'page', 'raw' ),
					),
					'footer_col_4' => array(
						'name'    => __( 'Footer Column #4', 'jumpstart' ),
						'desc'    => __( 'Configure the content for the fourth column.', 'jumpstart' ),
						'id'      => 'footer_col_4',
						'type'    => 'content',
						'class'   => 'col_4',
						'options' => array( 'widget', 'page', 'raw' ),
					),
					'footer_col_5' => array(
						'name'    => __( 'Footer Column #5', 'jumpstart' ),
						'desc'    => __( 'Configure the content for the fifth column.', 'jumpstart' ),
						'id'      => 'footer_col_5',
						'type'    => 'content',
						'class'   => 'col_5',
						'options' => array( 'widget', 'page', 'raw' ),
					),
					'end_footer_cols' => array(
						'type'    => 'subgroup_end',
					),
					'footer_copyright' => array(
						'name'    => __( 'Footer Copyright Text', 'jumpstart' ),
						'desc'    => __( 'Enter the copyright text you\'d like to show in the footer of your site.', 'jumpstart' ) . '<br><br><em>%year%</em> &mdash; ' . __( 'Show current year.', 'jumpstart' ) . '<br><em>%site_title%</em> &mdash; ' . __( 'Show your site title.', 'jumpstart' ),
						'id'      => 'footer_copyright',
						'std'     => '(c) %year% %site_title% - Powered by <a href="http://wordpress.org" title="WordPress" target="_blank">WordPress</a>, Designed by <a href="http://themeblvd.com" title="Theme Blvd" target="_blank">Theme Blvd</a>',
						'type'    => 'editor',
						'class'   => 'standard-footer-setup',
					),
				), // End footer options.
			),

			// Section: Sidebar Layouts
			'sidebar_layouts' => array(
				'name'    => __( 'Sidebar Layout', 'jumpstart' ),
				// 'desc' => __( 'These settings apply when you\'re viewing posts specific to a category, tag, date, author, etc.', 'jumpstart' ),
				'options' => array(
					'sidebar_layout' => array(
						'name'      => __( 'Default', 'jumpstart' ),
						'desc'      => __( 'Choose the default sidebar layout for the main content area of your site.', 'jumpstart' ),
						'id'        => 'sidebar_layout',
						'std'       => 'sidebar_right',
						'type'      => 'images',
						'options'   => $sidebar_layouts,
						'img_width' => '45',
					),
					'single_sidebar_layout' => array(
						'name'      => __( 'Single Posts', 'jumpstart' ),
						'desc'      => __( 'When viewing a single post, what do you want to use for the sidebar layout?', 'jumpstart' ),
						'id'        => 'single_sidebar_layout',
						'std'       => 'default',
						'type'      => 'images',
						'options'   => array_merge(
							array(
								'default' => $imagepath . 'layout-default.png',
							), $sidebar_layouts
						),
						'img_width' => '45',
					),
					'page_sidebar_layout' => array(
						'name'      => __( 'Pages', 'jumpstart' ),
						'desc'      => __( 'When viewing a standard page, what do you want to use for the sidebar layout?', 'jumpstart' ),
						'id'        => 'page_sidebar_layout',
						'std'       => 'default',
						'type'      => 'images',
						'options'   => array_merge(
							array(
								'default' => $imagepath . 'layout-default.png',
							), $sidebar_layouts
						),
						'img_width' => '45',
					),
					'archive_sidebar_layout' => array(
						'name'      => __( 'Archives', 'jumpstart' ),
						'desc'      => __( 'When viewing a general archive of posts, what do you want to use for the sidebar layout?', 'jumpstart' ),
						'id'        => 'archive_sidebar_layout',
						'std'       => 'default',
						'type'      => 'images',
						'options'   => array_merge(
							array(
								'default' => $imagepath . 'layout-default.png',
							), $sidebar_layouts
						),
						'img_width' => '45',
					),
				),
			),

			// Section: Extras
			'extras' => array(
				'name'    => __( 'Extras', 'jumpstart' ),
				'options' => array(
					'sticky' => array(
						'name'    => __( 'Sticky Header', 'jumpstart' ),
						'desc'    => __( 'If enabled, this will display compact version of the site header, fixed to the top of the browser, as the user scrolls down the page.', 'jumpstart' ),
						'id'      => 'sticky',
						'std'     => 'show',
						'type'    => 'select',
						'options' => array(
							'show'  => __( 'Yes, show sticky header', 'jumpstart' ),
							'hide'  => __( 'No, don\'t show it', 'jumpstart' ),
						),
					),
					'breadcrumbs' => array(
						'name'    => __( 'Breadcrumbs', 'jumpstart' ),
						'desc'    => __( 'Select whether you\'d like breadcrumbs to show throughout the site or not.', 'jumpstart' ),
						'id'      => 'breadcrumbs',
						'std'     => 'show',
						'type'    => 'select',
						'options' => array(
							'show' => __( 'Yes, show breadcrumbs', 'jumpstart' ),
							'hide' => __( 'No, hide breadcrumbs', 'jumpstart' ),
						),
					),
					'scroll_to_top' => array(
						'name'    => __( 'Scroll-to-Top Button', 'jumpstart' ),
						'desc'    => __( 'If enabled, this will display a button that appears on the screen, which allows the user to quickly scroll back to the top of the website.', 'jumpstart' ),
						'id'      => 'scroll_to_top',
						'std'     => 'show',
						'type'    => 'select',
						'options' => array(
							'show'  => __( 'Yes, show button', 'jumpstart' ),
							'hide'  => __( 'No, don\'t show it', 'jumpstart' ),
						),
					),
				),
			),
		);

		/*--------------------------------*/
		/* Tab #2: Content
		/*--------------------------------*/

		$content_options = array(

			// Section: General
			'general' => array(
				'name'    => __( 'General', 'jumpstart' ),
				'options' => array(
					'general_sub_group_start' => array(
						'type'   => 'subgroup_start',
						'class'  => 'show-hide',
					),
					'fw_narrow' => array(
						'name'  => null,
						'desc'  => __( 'Condense full-width content.', 'jumpstart' ),
						'id'    => 'fw_narrow',
						'std'   => '0',
						'type'  => 'checkbox',
						'class' => 'trigger',
					),
					'img_popout' => array(
						'name'  => null,
						'desc'  => __( 'Popout large images within full-width content.', 'jumpstart' ),
						'id'    => 'img_popout',
						'std'   => '0',
						'type'  => 'checkbox',
						'class' => 'receiver hide',
					),
					'general_sub_group_end' => array(
						'type'  => 'subgroup_end',
					),
					'gallery_carousel' => array(
						'name'  => null,
						'desc'  => __( 'Use variable width image carousel for gallery sliders.', 'jumpstart' ),
						'id'    => 'gallery_carousel',
						'std'   => '1',
						'type'  => 'checkbox',
					),
					'scroll_effects' => array(
						'name'  => null,
						'desc'  => __( 'Use scroll effects, where supported.', 'jumpstart' ),
						'id'    => 'scroll_effects',
						'std'   => '1',
						'type'  => 'checkbox',
					),
				),
			),

			// Section: Single Posts
			'single' => array(
				'name'    => __( 'Single Posts', 'jumpstart' ),
				'desc'    => __( 'These settings will only apply to vewing single posts. Additionally, most of these settings can be overridden in the Post Options meta box when editing individual posts.', 'jumpstart' ),
				'options' => array(
					'single_meta' => array(
						'name'    => __( 'Meta Information', 'jumpstart' ),
						'desc'    => __( 'Select if you\'d like the meta information (like date posted, author, etc) to show on the single post. If you\'re going for a non-blog type of setup, you may want to hide the meta info.', 'jumpstart' ),
						'id'      => 'single_meta',
						'std'     => 'show',
						'type'    => 'select',
						'options' => array(
							'show'      => __( 'Show meta info', 'jumpstart' ),
							'hide'      => __( 'Hide meta info', 'jumpstart' ),
						),
					),
					'single_sub_meta' => array(
						'name'    => __( 'Sub Meta Information', 'jumpstart' ),
						'desc'    => __( 'Select if you\'d like the sub meta information (like tags, categories, etc) to show on the single post.', 'jumpstart' ),
						'id'      => 'single_sub_meta',
						'std'     => 'show',
						'type'    => 'select',
						'options' => array(
							'show'      => __( 'Show sub meta info', 'jumpstart' ),
							'hide'      => __( 'Hide sub meta info', 'jumpstart' ),
						),
					),
					'share' => array( // generic name "share" so it can be thoertically moved to another option section, if applied to more than just signle post
						'name'    => __( 'Share Icons', 'jumpstart' ),
						'desc'    => __( 'Configure any share icons you\'d like displayed within the Sub Meta of the single post.', 'jumpstart' ),
						'id'      => 'share',
						'std'     => array(
							'item_1' => array(
								'icon'  => 'facebook',
								'label' => 'Share this on Facebook',
							),
							'item_2' => array(
								'icon'  => 'google',
								'label' => 'Share this on Google+',
							),
							'item_3' => array(
								'icon'  => 'twitter',
								'label' => 'Share this on Twitter',
							),
							'item_4' => array(
								'icon'  => 'email',
								'label' => 'Share this via Email',
							),
						),
						'type'    => 'share',
					),
					'single_thumbs' => array(
						'name'    => __( 'Featured Images &amp; Galleries', 'jumpstart' ),
						'desc'    => __( 'Choose how you want your featured images to show on the single post, by default. This option can be useful if you\'ve set featured images strictly for use in a blog, post grid, portfolio, etc, but you don\'t want those fetured images to show on the single posts.', 'jumpstart' ),
						'id'      => 'single_thumbs',
						'std'     => 'full',
						'type'    => 'select',
						'options' => array(
							'fw'   => __( 'Full width, above content', 'jumpstart' ),
							'fs'   => __( 'Full screen parallax, above content', 'jumpstart' ),
							'full' => __( 'Standard, with content', 'jumpstart' ),
							'hide' => __( 'Hide featured images', 'jumpstart' ),
						),
					),
					'single_related_posts' => array(
						'name'     => __( 'Related Posts', 'jumpstart' ),
						'desc'     => __( 'Select if you\'d like to show more posts related to the one being viewed.', 'jumpstart' ) . '<br><br><em>' . __( 'Note: This only applies to standard posts.', 'jumpstart' ) . '</em>',
						'id'       => 'single_related_posts',
						'std'      => 'tag',
						'type'     => 'select',
						'options'  => array(
							'tag'      => __( 'Show related posts by tag', 'jumpstart' ),
							'category' => __( 'Show related posts by category', 'jumpstart' ),
							'hide'     => __( 'Hide related posts', 'jumpstart' ),
						),
					),
					'single_related_posts_style' => array(
						'name'     => __( 'Related Posts Style', 'jumpstart' ),
						'desc'     => __( 'When showing, select how you\'d like to display the related posts.', 'jumpstart' ),
						'id'       => 'single_related_posts_style',
						'std'      => 'list',
						'type'     => 'select',
						'options'  => array(
							'list' => __( 'List', 'jumpstart' ),
							'grid' => __( 'Grid', 'jumpstart' ),
						),
					),
					'single_comments' => array(
						'name'    => __( 'Comments', 'jumpstart' ),
						'desc'    => __( 'This will hide the presence of comments on the single post.', 'jumpstart' ),
						'id'      => 'single_comments',
						'std'     => 'show',
						'type'    => 'select',
						'options' => array(
							'show' => __( 'Show comments', 'jumpstart' ),
							'hide' => __( 'Hide comments', 'jumpstart' ),
						),
					),
				), // End single options.
			),

			// Section: Pages
			'pages' => array(
				'name'    => __( 'Pages', 'jumpstart' ),
				'options' => array(
					'page_thumbs' => array(
						'name'    => __( 'Featured Images', 'jumpstart' ),
						'desc'    => __( 'Choose how you want your featured images to show on pages, by default.', 'jumpstart' ),
						'id'      => 'page_thumbs',
						'std'     => 'full',
						'type'    => 'select',
						'options' => array(
							'fw'   => __( 'Full width, above content', 'jumpstart' ),
							'fs'   => __( 'Full screen parallax, above content', 'jumpstart' ),
							'full' => __( 'Standard, with content', 'jumpstart' ),
							'hide' => __( 'Hide featured images', 'jumpstart' ),
						),
					),
				),
			),

			// Section: Blog Homepage
			'home' => array(
				'name'    => __( 'Blog Homepage', 'jumpstart' ),
				'desc'    => __( 'These settings apply when you\'re viewing your blog homepage or "posts page" set at Settings > Reading > Frontpage displays.', 'jumpstart' ),
				'options' => array(
					'home_mode' => array(
						'name'    => __( 'Post Display', 'jumpstart' ),
						'desc'    => __( 'When viewing your blog homepage, how do you want the posts displayed by default?', 'jumpstart' ),
						'id'      => 'home_mode',
						'std'     => 'blog',
						'type'    => 'select',
						'options' => themeblvd_get_modes(),
					),
				), // End blog homepage options.
			),

			// Section: Archives
			'archives' => array(
				'name'    => __( 'Archives', 'jumpstart' ),
				// 'desc' => __( 'These settings apply when you\'re viewing posts specific to a category, tag, date, author, etc.', 'jumpstart' ),
				'options' => array(
					'archive_mode' => array(
						'name'    => __( 'Post Display', 'jumpstart' ),
						'desc'    => __( 'When viewing an archive of posts, how do you want them displayed by default?', 'jumpstart' ),
						'id'      => 'archive_mode',
						'std'     => 'blog',
						'type'    => 'select',
						'options' => themeblvd_get_modes(),
					),
					'category_info' => array(
						'name'    => __( 'Category Info Boxes', 'jumpstart' ),
						'desc'    => __( 'When viewing a category archive, would you like to show an info box at the top that contains the title and description of the current category?', 'jumpstart' ),
						'id'      => 'category_info',
						'std'     => 'hide',
						'type'    => 'select',
						'options' => array(
							'show' => __( 'Yes, show info boxes', 'jumpstart' ),
							'hide' => __( 'No, hide info boxes', 'jumpstart' ),
						),
					),
					'tag_info' => array(
						'name'    => __( 'Tag Info Boxes', 'jumpstart' ),
						'desc'    => __( 'When viewing a tag archive, would you like to show an info box at the top that contains the title and description of the current tag?', 'jumpstart' ),
						'id'      => 'tag_info',
						'std'     => 'hide',
						'type'    => 'select',
						'options' => array(
							'show' => __( 'Yes, show info boxes', 'jumpstart' ),
							'hide' => __( 'No, hide info boxes', 'jumpstart' ),
						),
					),
				), // End archives options.
			),

			// Section: Primary Posts Display
			'blog' => array(
				'name'    => __( 'Post Display: Blog', 'jumpstart' ),
				'desc'    => __( 'These settings allow you to setup the default configuration for using the blog post display. These settings will be applied automatically to the "Blog" page template and any posts you\'ve set to display in the blog format.<br><br>For more control over a specific blog, you can apply the "Blog" element of the Layout Builder or use the [blog] shortcode in a page, which will allow you to override these options for that instance.', 'jumpstart' ),
				'options' => array(
					'blog_thumbs' => array(
						'name'    => __( 'Featured Images', 'jumpstart' ),
						'desc'    => __( 'Select the size of the blog\'s post thumbnail or whether you\'d like to hide them all together when posts are listed.', 'jumpstart' ),
						'id'      => 'blog_thumbs',
						'std'     => 'full',
						'type'    => 'select',
						'options' => array(
							'full' => __( 'Show featured images', 'jumpstart' ),
							'hide' => __( 'Hide featured images', 'jumpstart' ),
						),
					),
					'blog_meta' => array(
						'name'    => __( 'Meta Information', 'jumpstart' ),
						'desc'    => __( 'Select if you\'d like the meta information (like date posted, author, etc) to show for each post.', 'jumpstart' ),
						'id'      => 'blog_meta',
						'std'     => 'show',
						'type'    => 'select',
						'options' => array(
							'show' => __( 'Show meta info', 'jumpstart' ),
							'hide' => __( 'Hide meta info', 'jumpstart' ),
						),
					),
					'blog_sub_meta' => array(
						'name'    => __( 'Sub Meta Information', 'jumpstart' ),
						'desc'    => __( 'Select if you\'d like the sub meta information (like tags, categories, etc) to below each post.', 'jumpstart' ),
						'id'      => 'blog_sub_meta',
						'std'     => 'show',
						'type'    => 'select',
						'options' => array(
							'show' => __( 'Show sub meta info', 'jumpstart' ),
							'hide' => __( 'Hide sub meta info', 'jumpstart' ),
						),
					),
					'blog_content' => array(
						'name'    => __( 'Excerpts of Full Content', 'jumpstart' ),
						'desc'    => __( 'Choose whether you want to show full content or post excerpts only.', 'jumpstart' ),
						'id'      => 'blog_content',
						'std'     => 'excerpt',
						'type'    => 'select',
						'options' => array(
							'content' => __( 'Show full content', 'jumpstart' ),
							'excerpt' => __( 'Show excerpt only', 'jumpstart' ),
						),
					),
					'blog_categories' => array(
						'name'    => __( 'Exclude Categories', 'jumpstart' ),
						'desc'    => __( 'Select any categories you\'d like to be excluded from your blog.', 'jumpstart' ) . '<br><br><em>' . __( 'Note: This only applies to the main index blog or your "posts page" if you\'ve set one.</em>', 'jumpstart' ),
						'id'      => 'blog_categories',
						'type'    => 'multicheck',
						'options' => $options_categories,
					),
				), // End blog options.
			),

			// Section: Post Lists
			'list' => array(
				'name'    => __( 'Post Display: List', 'jumpstart' ),
				'desc'    => __( 'These settings allow you to setup the default configuration for using post lists. These settings will be applied automatically to the "Post List" page template and any posts you\'ve set to display in the post list format.<br><br>For more control over a specific post list, you can apply the "Post List" element of the Layout Builder or use the [post_list] shortcode in a page, which will allow you to override these options for that instance.', 'jumpstart' ),
				'options' => array(
					'list_thumbs' => array(
						'name'    => __( 'Featured Images', 'jumpstart' ),
						'desc'    => __( 'Choose whether or not you want featured images to show for each post.', 'jumpstart' ),
						'id'      => 'list_thumbs',
						'std'     => 'full',
						'type'    => 'select',
						'options' => array(
							'full' => __( 'Show featured images', 'jumpstart' ),
							'date' => __( 'Show date block', 'jumpstart' ),
							'hide' => __( 'Hide featured images', 'jumpstart' ),
						),
					),
					'list_meta' => array(
						'name'    => __( 'Meta Information', 'jumpstart' ),
						'desc'    => __( 'Select if you\'d like the meta information (like date posted, author, etc) to show for each post.', 'jumpstart' ),
						'id'      => 'list_meta',
						'std'     => 'show',
						'type'    => 'select',
						'options' => array(
							'show' => __( 'Show meta info', 'jumpstart' ),
							'hide' => __( 'Hide meta info', 'jumpstart' ),
						),
					),
					'list_sub_group_start' => array(
						'type'    => 'subgroup_start',
						'class'   => 'show-hide-toggle',
					),
					'list_more' => array(
						'name'    => __( 'Read More', 'jumpstart' ),
						'desc'    => __( 'What would you like to show for each post to lead the reader to the full post?', 'jumpstart' ),
						'id'      => 'list_more',
						'std'     => 'text',
						'type'    => 'select',
						'options' => array(
							'text'   => __( 'Show text link', 'jumpstart' ),
							'button' => __( 'Show button', 'jumpstart' ),
							'none'   => __( 'Show no button or text link', 'jumpstart' ),
						),
						'class'   => 'trigger',
					),
					'list_more_text' => array(
						'name'    => __( 'Read More Text', 'jumpstart' ),
						'desc'    => __( 'Enter the text you\'d like to use to lead the reader to the full post.', 'jumpstart' ),
						'id'      => 'list_more_text',
						'std'     => 'Read More <i class="fa fa-long-arrow-right"></i>',
						'type'    => 'text',
						'class'   => 'hide receiver receiver-text receiver-button',
					),
					'list_sub_group_end' => array(
						'type'    => 'subgroup_end',
					),
					'list_posts_per_page' => array(
						'name'    => __( 'Posts Per Page', 'jumpstart' ),
						'desc'    => __( 'When viewing a default post list, what is the maximum number of posts to display on each page?', 'jumpstart' ),
						'id'      => 'list_posts_per_page',
						'std'     => '10',
						'type'    => 'text',
					),
				), // End post list options.
			),

			// Section: Post Grids
			'grid' => array(
				'name'    => __( 'Post Display: Grid', 'jumpstart' ),
				'desc'    => __( 'These settings allow you to setup the default configuration for using post grids. These settings will be applied automatically to the "Post Grid" page template and any posts you\'ve set to display in the post grid format.<br><br>For more control over a specific post grid, you can apply the "Post Grid" element of the Layout Builder or use the [post_grid] shortcode in a page, which will allow you to override these options for that instance.', 'jumpstart' ),
				'options' => array(
					'grid_sub_group_start_1' => array(
						'type'    => 'subgroup_start',
						'class'   => 'show-hide-toggle',
					),
					'grid_thumbs' => array(
						'name'    => __( 'Featured Images', 'jumpstart' ),
						'desc'    => __( 'Choose whether or not you want featured images to show for each post.', 'jumpstart' ),
						'id'      => 'grid_thumbs',
						'std'     => 'full',
						'type'    => 'select',
						'options' => array(
							'full' => __( 'Show featured images', 'jumpstart' ),
							'hide' => __( 'Hide featured images', 'jumpstart' ),
						),
						'class'   => 'trigger',
					),
					'grid_crop' => array(
						'id'      => 'grid_crop',
						'name'    => __( 'Featured Image Crop Size', 'jumpstart' ),
						'desc'    => __( 'Select a custom crop size to be used for the images. If you select a crop size that doesn\'t have a consistent height, then you may want to enable "Masonry" display.', 'temeblvd' ) . '<br><br><em>' . __( 'Note: Images are scaled proportionally to fit within their current containers.', 'jumpstart' ) . '</em>',
						'type'    => 'select',
						'select'  => 'crop',
						'std'     => 'tb_grid',
						'class'   => 'hide receiver receiver-full',
					),
					'grid_sub_group_end_1' => array(
						'type'     => 'subgroup_end',
					),
					'grid_meta' => array(
						'name'    => __( 'Meta Information', 'jumpstart' ),
						'desc'    => __( 'Select if you\'d like the meta information (like date posted, author, etc) to show for each post.', 'jumpstart' ),
						'id'      => 'grid_meta',
						'std'     => 'show',
						'type'    => 'select',
						'options' => array(
							'show' => __( 'Show meta info', 'jumpstart' ),
							'hide' => __( 'Hide meta info', 'jumpstart' ),
						),
					),
					'grid_excerpt' => array(
						'name'    => __( 'Excerpts', 'jumpstart' ),
						'desc'    => __( 'Select if you\'d like to show the excerpt or not for each post.', 'jumpstart' ),
						'id'      => 'grid_excerpt',
						'std'     => 'show',
						'type'    => 'select',
						'options' => array(
							'show' => __( 'Show excerpts', 'jumpstart' ),
							'hide' => __( 'Hide excerpts', 'jumpstart' ),
						),
					),
					'grid_sub_group_start_2' => array(
						'type'    => 'subgroup_start',
						'class'   => 'show-hide-toggle',
					),
					'grid_more' => array(
						'name'    => __( 'Read More', 'jumpstart' ),
						'desc'    => __( 'What would you like to show for each post to lead the reader to the full post?', 'jumpstart' ),
						'id'      => 'grid_more',
						'std'     => 'button',
						'type'    => 'select',
						'options' => array(
							'text'   => __( 'Show text link', 'jumpstart' ),
							'button' => __( 'Show button', 'jumpstart' ),
							'none'   => __( 'Show no button or text link', 'jumpstart' ),
						),
						'class'   => 'trigger',
					),
					'grid_more_text' => array(
						'name'    => __( 'Read More Text', 'jumpstart' ),
						'desc'    => __( 'Enter the text you\'d like to use to lead the reader to the full post.', 'jumpstart' ),
						'id'      => 'grid_more_text',
						'std'     => 'Read More',
						'type'    => 'text',
						'class'   => 'hide receiver receiver-text receiver-button',
					),
					'grid_sub_group_end_2' => array(
						'type'    => 'subgroup_end',
					),
					'grid_sub_group_start_3' => array(
						'type'    => 'subgroup_start',
						'class'   => 'show-hide-toggle',
					),
					'grid_display' => array(
						'name'    => __( 'Display', 'jumpstart' ),
						'desc'    => __( 'When viewing a default post grid, how should they be displayed?', 'jumpstart' ),
						'id'      => 'grid_display',
						'std'     => 'paginated',
						'type'    => 'select',
						'options' => array(
							'paginated'         => __( 'Standard Grid', 'jumpstart' ),
							'masonry_paginated' => __( 'Masonry Grid', 'jumpstart' ),
						),
						'class'   => 'trigger',
					),
					'grid_columns' => array(
						'name'    => __( 'Columns', 'jumpstart' ),
						'desc'    => __( 'When viewing a default post grid, how many columns should the posts be separated into?', 'jumpstart' ),
						'id'      => 'grid_columns',
						'std'     => '3',
						'type'    => 'select',
						'options' => array(
							'2' => __( '2 Columns', 'jumpstart' ),
							'3' => __( '3 Columns', 'jumpstart' ),
							'4' => __( '4 Columns', 'jumpstart' ),
							'5' => __( '5 Columns', 'jumpstart' ),
						),
					),
					'grid_rows' => array(
						'name'    => __( 'Rows', 'jumpstart' ),
						'desc'    => __( 'When viewing a default post grid, what is the maximum number of rows that should be displayed on each page?', 'jumpstart' ) . '<br><br><em>' . __( 'Note: The total posts on the page will be the number of rows times the number of columns.', 'jumpstart' ) . '</em>',
						'id'      => 'grid_rows',
						'std'     => '3',
						'type'    => 'text',
						'class'   => 'hide receiver receiver-paginated',
					),
					'grid_posts_per_page' => array(
						'name'    => __( 'Posts Per Page', 'jumpstart' ),
						'desc'    => __( 'When viewing a default masonry post grid, what is the maximum number of posts that should be displayed on each page?', 'jumpstart' ),
						'id'      => 'grid_posts_per_page',
						'std'     => '12',
						'type'    => 'text',
						'class'   => 'hide receiver receiver-masonry_paginated',
					),
					'grid_sub_group_end_3' => array(
						'type'    => 'subgroup_end',
					),
				), // End post grid options.
			),

			// Section: Showcase
			'showcase' => array(
				'name'    => __( 'Post Display: Showcase', 'jumpstart' ),
				'desc'    => __( 'These settings allow you to setup the default configuration for using the post showcase. These settings will be applied automatically to the "Post Showcase" page template and any posts you\'ve set to display in the post showcase format.<br><br>For more control over a specific post showcase, you can apply the "Post Showcase" element of the Layout Builder or use the [post_showcase] shortcode in a page, which will allow you to override these options for that instance.', 'jumpstart' ),
				'options' => array(
					'showcase_crop' => array(
						'id'      => 'showcase_crop',
						'name'    => __( 'Featured Image Crop Size', 'jumpstart' ),
						'desc'    => __( 'Select a custom crop size to be used for the images. If you select a crop size that doesn\'t have a consistent height, then you may want to enable "Masonry" display.', 'jumpstart' ) . '<br><br><em>' . __( 'Note: Images are scaled proportionally to fit within their current containers.', 'jumpstart' ) . '</em>',
						'type'    => 'select',
						'select'  => 'crop',
						'std'     => 'tb_grid',
					),
					'showcase_titles' => array(
						'name'    => __( 'Titles', 'jumpstart' ),
						'desc'    => __( 'Select if you\'d like to show the title or not for each post.', 'jumpstart' ),
						'id'      => 'showcase_titles',
						'std'     => 'show',
						'type'    => 'select',
						'options' => array(
							'show' => __( 'Show titles', 'jumpstart' ),
							'hide' => __( 'Hide titles', 'jumpstart' ),
						),
					),
					'showcase_excerpt' => array(
						'name'    => __( 'Excerpts', 'jumpstart' ),
						'desc'    => __( 'Select if you\'d like to show the excerpt or not for each post.', 'jumpstart' ),
						'id'      => 'showcase_excerpt',
						'std'     => 'hide',
						'type'    => 'select',
						'options' => array(
							'show' => __( 'Show excerpts', 'jumpstart' ),
							'hide' => __( 'Hide excerpts', 'jumpstart' ),
						),
					),
					'showcase_gutters' => array(
						'name'    => __( 'Gutters', 'jumpstart' ),
						'desc'    => __( 'Select if you\'d like to show spacing in between the showcase items.', 'jumpstart' ) . '<br><br><em>' . __( 'Note: Hiding the gutters works best if you\'re using a consistent image crop size, or the masonry display.', 'jumpstart' ) . '</em>',
						'id'      => 'showcase_gutters',
						'std'     => 'show',
						'type'    => 'select',
						'options' => array(
							'show' => __( 'Show gutters', 'jumpstart' ),
							'hide' => __( 'Hide gutters', 'jumpstart' ),
						),
					),
					'showcase_sub_group_start_1' => array(
						'type'    => 'subgroup_start',
						'class'   => 'show-hide-toggle',
					),
					'showcase_display' => array(
						'name'    => __( 'Display', 'jumpstart' ),
						'desc'    => __( 'When viewing a default post showcase, how should they be displayed?', 'jumpstart' ),
						'id'      => 'showcase_display',
						'std'     => 'paginated',
						'type'    => 'select',
						'options' => array(
							'paginated'         => __( 'Standard Showcase Grid', 'jumpstart' ),
							'masonry_paginated' => __( 'Masonry Showcase', 'jumpstart' ),
						),
						'class'   => 'trigger',
					),
					'showcase_columns' => array(
						'name'    => __( 'Columns', 'jumpstart' ),
						'desc'    => __( 'When viewing a default post showcase, how many columns should the posts be separated into?', 'jumpstart' ),
						'id'      => 'showcase_columns',
						'std'     => '3',
						'type'    => 'select',
						'options' => array(
							'2' => __( '2 Columns', 'jumpstart' ),
							'3' => __( '3 Columns', 'jumpstart' ),
							'4' => __( '4 Columns', 'jumpstart' ),
							'5' => __( '5 Columns', 'jumpstart' ),
						),
					),
					'showcase_rows' => array(
						'name'    => __( 'Rows', 'jumpstart' ),
						'desc'    => __( 'When viewing a default post showcase, what is the maximum number of rows that should be displayed on each page?', 'jumpstart' ) . '<br><br><em>' . __( 'Note: The total posts on the page will be the number of rows times the number of columns.', 'jumpstart' ) . '</em>',
						'id'      => 'showcase_rows',
						'std'     => '3',
						'type'    => 'text',
						'class'   => 'hide receiver receiver-paginated',
					),
					'showcase_posts_per_page' => array(
						'name'    => __( 'Posts Per Page', 'jumpstart' ),
						'desc'    => __( 'When viewing a default masonry post showcase, what is the maximum number of posts that should be displayed on each page?', 'jumpstart' ),
						'id'      => 'showcase_posts_per_page',
						'std'     => '12',
						'type'    => 'text',
						'class'   => 'hide receiver receiver-masonry_paginated',
					),
					'showcase_sub_group_end_1' => array(
						'type'    => 'subgroup_end',
					),
				), // End post showcase options.
			),

			// Section: Lightbox
			'lightbox' => array(
				'name'    => __( 'Lightbox', 'jumpstart' ),
				// translators: 1: link to Magnific Popup website.
				'desc'    => sprintf( __( 'These settings apply to the built-in lightbox functionality, which utilizes the %s script.', 'jumpstart' ), '<a href="http://dimsemenov.com/plugins/magnific-popup/" target="_blank">Magnific Popup</a>' ),
				'options' => array(
					'lightbox_animation' => array(
						'name'    => __( 'Animate lightboxes?', 'jumpstart' ),
						'desc'    => __( 'Select if you\'d like lightboxes to animate as they open and close.', 'jumpstart' ),
						'id'      => 'lightbox_animation',
						'std'     => 'fade',
						'type'    => 'select',
						'options' => array(
							'none' => __( 'No animation', 'jumpstart' ),
							'fade' => __( 'Fade animation', 'jumpstart' ),
						),
					),
					'lightbox_mobile' => array(
						'name'    => __( 'Disable standard lightboxes for mobile?', 'jumpstart' ),
						'desc'    => __( 'Select if you\'d like the lightbox to be disabled for mobile users viewing a standard lightbox instance.', 'jumpstart' ),
						'id'      => 'lightbox_mobile',
						'std'     => 'no',
						'type'    => 'select',
						'options' => array(
							'yes' => __( 'Yes, disable for mobile.', 'jumpstart' ),
							'no'  => __( 'No, do not disable for mobile.', 'jumpstart' ),
						),
					),
					'lightbox_mobile_iframe' => array(
						'name'    => __( 'Disable iframe lightboxes for mobile?', 'jumpstart' ),
						'desc'    => __( 'Select if you\'d like the lightbox to be disabled for mobile users viewing an iframe lightbox instance. This includes linking to YouTube videos, Vimeo videos, and Google Maps in a lightbox popup.', 'jumpstart' ),
						'id'      => 'lightbox_mobile_iframe',
						'std'     => 'yes',
						'type'    => 'select',
						'options' => array(
							'yes' => __( 'Yes, disable for mobile.', 'jumpstart' ),
							'no'  => __( 'No, do not disable for mobile.', 'jumpstart' ),
						),
					),
					'lightbox_mobile_gallery' => array(
						'name'    => __( 'Disable gallery lightboxes for mobile?', 'jumpstart' ),
						'desc'    => __( 'Select if you\'d like the lightbox to be disabled for mobile users when viewing a gallery.', 'jumpstart' ),
						'id'      => 'lightbox_mobile_gallery',
						'std'     => 'no',
						'type'    => 'select',
						'options' => array(
							'yes' => __( 'Yes, disable for mobile.', 'jumpstart' ),
							'no'  => __( 'No, do not disable for mobile.', 'jumpstart' ),
						),
					),
				),
			), // End archives options.
		);

		/*--------------------------------*/
		/* Tab #3: Configuration
		/*--------------------------------*/

		$config_options = array(

			// Section: Google Maps
			'gmap' => array(
				'name'    => __( 'Google Maps', 'jumpstart' ),
				'options' => array(
					'gmap_info' => array(
						'id'   => 'gmap_info',
						'desc' => __( 'You must have a Google Maps API key setup to use the "Google Maps" element of our layout builder.', 'jumpstart' ) . ' <a href="http://docs.themeblvd.com/article/56-google-maps-api" target="_blank">' . __( 'Learn More', 'jumpstart' ) . '</a>',
						'type' => 'info',
					),
					'gmap_api_key' => array(
						'name' => __( 'Google Maps API Key', 'jumpstart' ),
						// translators: 1: link to generate a Google Maps API key
						'desc' => sprintf( __( 'You can generate a Google Map API key %s.', 'jumpstart' ), '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key" target="_blank">' . __( 'here', 'jumpstart' ) . '</a>' ),
						'id'   => 'gmap_api_key',
						'std'  => '',
						'type' => 'text',
					),
				),
			),

		);
		/*--------------------------------*/
		/* Tab #4: Plugins
		/*--------------------------------*/

		$plugin_options = array(

			// Section: bbPress
			'bbpress' => array(
				'name'    => __( 'bbPress', 'jumpstart' ),
				'options' => array(
					'bbp_styles' => array(
						'name'     => __( 'Custom Styles', 'jumpstart' ),
						'desc'     => __( 'Add theme\'s custom styling for bbPress.', 'jumpstart' ) . '<br><br><em>' . __( 'Note: By disabling the theme\'s custom bbPress styling, all of the plugin\'s default features may not be fully supported.', 'jumpstart' ) . '</em>',
						'id'       => 'bbp_styles',
						'std'      => '1',
						'type'     => 'checkbox',
					),
					'bbp_naked_page' => array(
						'name'     => __( 'Content Background', 'jumpstart' ),
						'desc'     => __( 'When viewing bbPress pages and using a closed content style, remove standard page background design from wrapping forums and topics.', 'jumpstart' ),
						'id'       => 'bbp_naked_page',
						'std'      => '1',
						'type'     => 'checkbox',
					),
					'bbp_sidebar_layout' => array(
						'name'     => __( 'Forum Sidebar Layout', 'jumpstart' ),
						'desc'     => __( 'Select the sidebar layout used for viewing forum pages generated by bbPress.', 'jumpstart' ),
						'id'       => 'bbp_sidebar_layout',
						'std'      => 'default',
						'type'     => 'images',
						'options'  => array_merge(
							array(
								'default' => $imagepath . 'layout-default.png',
							),
							$sidebar_layouts
						),
						'img_width' => '45',
					),
					'bbp_topic_sidebar_layout' => array(
						'name'     => __( 'Topic Sidebar Layout', 'jumpstart' ),
						'desc'     => __( 'Select the sidebar layout used for viewing individual topics.', 'jumpstart' ),
						'id'       => 'bbp_topic_sidebar_layout',
						'std'      => 'default',
						'type'     => 'images',
						'options'  => array_merge(
							array(
								'default' => $imagepath . 'layout-default.png',
							),
							$sidebar_layouts
						),
						'img_width' => '45',
					),
					'bbp_user_sidebar_layout' => array(
						'name'     => __( 'Profile Sidebar Layout', 'jumpstart' ),
						'desc'     => __( 'Select the sidebar layout used for viewing user profiles.', 'jumpstart' ),
						'id'       => 'bbp_user_sidebar_layout',
						'std'      => 'default',
						'type'     => 'images',
						'options'  => array_merge(
							array(
							'default' => $imagepath . 'layout-default.png',
							), $sidebar_layouts
						),
						'img_width' => '45',
					),
				),
			), // End bbpress options.

			// Section: Gravity Forms
			'gravityforms' => array(
				'name'    => __( 'Gravity Forms', 'jumpstart' ),
				'options' => array(
					'gforms_styles' => array(
						'name'     => __( 'Custom Styles', 'jumpstart' ),
						'desc'     => __( 'Add theme\'s custom styling for Gravity Forms.', 'jumpstart' ) . '<br><br><em>' . __( 'Note: By disabling the theme\'s custom Gravity Forms styling, all of the plugin\'s default features may not be fully supported.', 'jumpstart' ) . '</em>',
						'id'       => 'gforms_styles',
						'std'      => '1',
						'type'     => 'checkbox',
					),
				),
			),

			// Section: WooCommerce
			'woocommerce' => array(
				'name'    => __( 'WooCommerce', 'jumpstart' ),
				'options' => array(
					'woo_styles' => array(
						'name'     => __( 'Custom Styles', 'jumpstart' ),
						'desc'     => __( 'Add theme\'s custom styling for WooCommerce.', 'jumpstart' ) . '<br><br><em>' . __( 'Note: By disabling the theme\'s custom WooCommerce styling, all of the plugin\'s default features may not be fully supported. Also, several of the options below may not work exactly as expected.', 'jumpstart' ) . '</em>',
						'id'       => 'woo_styles',
						'std'      => '1',
						'type'     => 'checkbox',
					),
					'woo_floating_cart' => array(
						'name'     => __( 'Floating Shopping Cart', 'jumpstart' ),
						'desc'     => __( 'Select whether you\'d like the floating shopping cart to display in the header of your website.', 'jumpstart' ),
						'id'       => 'woo_floating_cart',
						'std'      => 'yes',
						'type'     => 'select',
						'options'  => array(
							'yes' => __( 'Yes, show floating cart', 'jumpstart' ),
							'no'  => __( 'No, don\'t show floating cart', 'jumpstart' ),
						),
					),
					'woo_shop_view' => array(
						'name'     => __( 'Shop View', 'jumpstart' ),
						'desc'     => __( 'Select the default product display style for your main shop page.', 'jumpstart' ),
						'id'       => 'woo_shop_view',
						'std'      => 'grid',
						'type'     => 'select',
						'options'  => array(
							'grid'      => __( 'Grid', 'jumpstart' ),
							'list'      => __( 'List', 'jumpstart' ),
							'catalog'   => __( 'Catalog', 'jumpstart' ),
						),
					),
					'woo_shop_columns' => array(
						'name'     => __( 'Shop Columns', 'jumpstart' ),
						'desc'     => __( 'Select the number of columns to display the products on your main shop page, when viewed as a grid.', 'jumpstart' ),
						'id'       => 'woo_shop_columns',
						'std'      => '4',
						'type'     => 'select',
						'options'  => array(
							'2'     => __( '2 columns', 'jumpstart' ),
							'3'     => __( '3 columns', 'jumpstart' ),
							'4'     => __( '4 columns', 'jumpstart' ),
							'5'     => __( '5 columns', 'jumpstart' ),
						),
					),
					'woo_shop_per_page' => array(
						'name'     => __( 'Shop Products Per Page', 'jumpstart' ),
						'desc'     => __( 'Select the number products to display per page on your main shop page.', 'jumpstart' ),
						'id'       => 'woo_shop_per_page',
						'std'      => '12',
						'type'     => 'text',
					),
					'woo_archive_view' => array(
						'name'     => __( 'Archive View', 'jumpstart' ),
						'desc'     => __( 'Select the default product display style for your product archives. This is when products are displayed by category or tag.', 'jumpstart' ),
						'id'       => 'woo_archive_view',
						'std'      => 'grid',
						'type'     => 'select',
						'options'  => array(
							'grid'      => __( 'Grid', 'jumpstart' ),
							'list'      => __( 'List', 'jumpstart' ),
							'catalog'   => __( 'Catalog', 'jumpstart' ),
						),
					),
					'woo_archive_columns' => array(
						'name'     => __( 'Archive Columns', 'jumpstart' ),
						'desc'     => __( 'Select the number of columns to display the products in your product archives, when viewed as a grid.', 'jumpstart' ),
						'id'       => 'woo_archive_columns',
						'std'      => '3',
						'type'     => 'select',
						'options'  => array(
							'2'     => __( '2 columns', 'jumpstart' ),
							'3'     => __( '3 columns', 'jumpstart' ),
							'4'     => __( '4 columns', 'jumpstart' ),
							'5'     => __( '5 columns', 'jumpstart' ),
						),
					),
					'woo_archive_per_page' => array(
						'name'     => __( 'Archives Products Per Page', 'jumpstart' ),
						'desc'     => __( 'Select the number products to display per page in your product archives.', 'jumpstart' ),
						'id'       => 'woo_archive_per_page',
						'std'      => '12',
						'type'     => 'text',
					),
					'woo_shop_sidebar_layout' => array(
						'name'     => __( 'Shop Sidebar Layout', 'jumpstart' ),
						'desc'     => __( 'Select the sidebar layout when viewing your main shop.', 'jumpstart' ),
						'id'       => 'woo_shop_sidebar_layout',
						'std'      => 'full_width',
						'type'     => 'images',
						'options'  => array_merge(
							array(
								'default' => $imagepath . 'layout-default.png',
							),
							$sidebar_layouts
						),
						'img_width' => '45',
					),
					'woo_archive_sidebar_layout' => array(
						'name'     => __( 'Archive Sidebar Layout', 'jumpstart' ),
						'desc'     => __( 'Select the sidebar layout when viewing your product archives. This is when products are displayed by category or tag.', 'jumpstart' ),
						'id'       => 'woo_archive_sidebar_layout',
						'std'      => 'sidebar_left',
						'type'     => 'images',
						'options'  => array_merge(
							array(
								'default' => $imagepath . 'layout-default.png',
							),
							$sidebar_layouts
						),
						'img_width' => '45',
					),
					'woo_product_sidebar_layout' => array(
						'name'     => __( 'Product Sidebar Layout', 'jumpstart' ),
						'desc'     => __( 'Select the sidebar layout when viewing a single product.', 'jumpstart' ),
						'id'       => 'woo_product_sidebar_layout',
						'std'      => 'sidebar_left',
						'type'     => 'images',
						'options'  => array_merge(
							array(
								'default' => $imagepath . 'layout-default.png',
							),
							$sidebar_layouts
						),
						'img_width' => '45',
					),
					'woo_cross_sell' => array(
						'name'     => __( 'Shopping Cart Cross Sells', 'jumpstart' ),
						'desc'     => __( 'For the shopping cart page, select if you\'d like to display products customers may be interested in, based on what\'s currently in their cart.', 'jumpstart' ),
						'id'       => 'woo_cross_sell',
						'std'      => 'no',
						'type'     => 'select',
						'options'  => array(
							'yes' => __( 'Yes, show cross sells', 'jumpstart' ),
							'no'  => __( 'No, don\'t show cross sells', 'jumpstart' ),
						),
					),
					'woo_view_toggle' => array(
						'name'     => __( 'Product View Toggle', 'jumpstart' ),
						'desc'     => __( 'Select if you\'d like to display buttons on your product pages that allow the user to toggle between list, grid and catalog view.', 'jumpstart' ),
						'id'       => 'woo_view_toggle',
						'std'      => 'yes',
						'type'     => 'select',
						'options'  => array(
							'yes'   => __( 'Yes, show buttons', 'jumpstart' ),
							'no'    => __( 'No, don\'t show buttons', 'jumpstart' ),
						),
					),
					'woo_product_zoom' => array(
						'name'     => __( 'Product Gallery Zoom', 'jumpstart' ),
						'desc'     => __( 'When viewing a single product, select whether you\'d like the WooCommerce zooming feature enabled on product galleries.', 'jumpstart' ),
						'id'       => 'woo_product_zoom',
						'std'      => 'yes',
						'type'     => 'select',
						'options'  => array(
							'yes' => __( 'Yes, enable gallery zooming', 'jumpstart' ),
							'no'  => __( 'No, disable gallery zooming', 'jumpstart' ),
						),
					),
				),
			), // End WooCommerce options.

			// Section: WPML
			'wpml' => array(
				'name'    => __( 'WPML', 'jumpstart' ),
				'options' => array(
					'wpml_show_lang_switcher' => array(
						'name'    => __( 'Language Switcher', 'jumpstart' ),
						'desc'    => __( 'Select if you\'d like to show the theme\'s built-in language switcher for WPML. You can hide this if you\'re using other features in WPML to display a language switcher.', 'jumpstart' ),
						'id'      => 'wpml_show_lang_switcher',
						'std'     => 'yes',
						'type'    => 'select',
						'options' => array(
							'yes' => __( 'Yes, show theme\'s language switcher', 'jumpstart' ),
							'no'  => __( 'No, don\'t show it', 'jumpstart' ),
						),
					),
				),
			), // End WPML options.
		);

		/*--------------------------------*/
		/* Finalize and extend
		/*--------------------------------*/

		$this->raw_options = array(
			'layout' => array(
				'name'     => __( 'Layout', 'jumpstart' ),
				'sections' => $layout_options,
			),
			'content' => array(
				'name'     => __( 'Content', 'jumpstart' ),
				'sections' => $content_options,
			),
			'config' => array(
				'name'     => __( 'Configuration', 'jumpstart' ),
				'sections' => $config_options,
			),
			'plugins' => array(
				'name'     => __( 'Plugins', 'jumpstart' ),
				'sections' => $plugin_options,
			),
		);

		/**
		 * Filters the core framework options before any client
		 * options are added through API and before any formatting
		 * has been done.
		 *
		 * The following filter probably won't be used often, but if
		 * there's something that can't be accomplished through the
		 * client mutator API methods, then this provides a way to
		 * modify these raw options.
		 *
		 * @since Theme_Blvd 2.3.0
		 *
		 * @param array $raw_options All core framework options before formatting.
		 */
		$this->raw_options = apply_filters( 'themeblvd_core_options', $this->raw_options );

	}

	/**
	 * Format raw options after client has had a chance to
	 * modifty options.
	 *
	 * This works because our set_formatted_options()
	 * mutator is hooked in to the WP loading process at
	 * after_setup_theme.
	 *
	 * @since Theme_Blvd 2.3.0
	 */
	public function set_formatted_options() {

		// Hidden options
		$this->formatted_options = array(
			'framework_version' => array(
				'id'   => 'framework_version',
				'type' => 'hidden',
			),
			'theme_version' => array(
				'id'   => 'theme_version',
				'type' => 'hidden',
			),
		);

		if ( themeblvd_supports( 'admin', 'base' ) ) {
			$this->formatted_options['theme_base'] = array(
				'id'   => 'theme_base',
				'type' => 'hidden',
			);
		}

		// Remove any options for unsupported features.

		if ( ! themeblvd_supports( 'display', 'suck_up' ) ) {

			if ( isset( $this->raw_options['layout']['sections']['header_trans'] ) ) {

				unset( $this->raw_options['layout']['sections']['header_trans'] );

			}
		}

		if ( ! themeblvd_supports( 'display', 'sticky' ) ) {

			if ( isset( $this->raw_options['layout']['sections']['extras']['options']['sticky'] ) ) {

				unset( $this->raw_options['layout']['sections']['extras']['options']['sticky'] );

			}
		}

		if ( ! themeblvd_supports( 'display', 'scroll_effects' ) ) {

			if ( isset( $this->raw_options['content']['sections']['general']['options']['scroll_effects'] ) ) {

				unset( $this->raw_options['content']['sections']['general']['options']['scroll_effects'] );

			}
		}

		/** This filter is documented in framework/compat/wpml/class-theme-blvd-compat-wpml.php */
		if ( ! apply_filters( 'themeblvd_wpml_has_switcher', true ) ) {

			if ( isset( $this->raw_options['plugins']['sections']['wpml'] ) ) {

				unset( $this->raw_options['plugins']['sections']['wpml'] );

			}
		}

		/**
		 * Filters the core options before formatting, a second time
		 * after some options have been removed (see just above).
		 *
		 * @since Theme_Blvd 2.3.0
		 *
		 * @param array $raw_options All framework options before formatting.
		 */
		$this->raw_options = apply_filters( 'themeblvd_pre_format_options', $this->raw_options );

		// Tab Level.
		foreach ( $this->raw_options as $tab_id => $tab ) {

			// Insert Tab Heading.
			$this->formatted_options[ 'tab_' . $tab_id ] = array(
				'id'    => $tab_id,
				'name'  => $tab['name'],
				'type'  => 'heading',
			);

			if ( isset( $tab['preset'] ) ) {
				$this->formatted_options[ 'tab_' . $tab_id ]['preset'] = $tab['preset'];
			}

			// Section Level.
			if ( $tab['sections'] ) {
				foreach ( $tab['sections'] as $section_id => $section ) {

					// Start section.
					$this->formatted_options[ 'start_section_' . $section_id ] = array(
						'name' => $section['name'],
						'type' => 'section_start',
					);

					if ( isset( $section['preset'] ) ) {
						$this->formatted_options[ 'start_section_' . $section_id ]['preset'] = $section['preset'];
					}

					if ( isset( $section['desc'] ) ) {
						$this->formatted_options[ 'start_section_' . $section_id ]['desc'] = $section['desc'];
					}

					// Options Level.
					if ( $section['options'] ) {
						foreach ( $section['options'] as $option_id => $option ) {
							$this->formatted_options[ $option_id ] = $option;
						}
					}

					// End section.
					$this->formatted_options[ 'end_section_' . $section_id ] = array(
						'type' => 'section_end',
					);

				}
			}
		}

		// Adjust some option descriptions if post formats are enabled.
		if ( current_theme_supports( 'post-formats' ) ) {

			/**
			 * Filters a list of options that get the special message
			 * added to their descriptions when post formats are enabled.
			 *
			 * Basically, if you have an option that will be effected
			 * from enabling post formats, you can add it to the list.
			 *
			 * @since Theme_Blvd 2.3.0
			 *
			 * @param array $options An array of option ID strings that are effected by post formats.
			 */
			$options = apply_filters( 'themeblvd_apply_post_format_warning', array( 'single_thumbs', 'blog_thumbs' ) );

			foreach ( $options as $option_id ) {
				$this->formatted_options[ $option_id ]['desc'] .= '<br><br><em>' . __( 'Note: The result of this option may vary with posts that are not the "standard" post format.', 'jumpstart' ) . '</em>';
			}
		}

		/**
		 * Filters all theme options after formatting has occured, and
		 * all options have been merged with options added through the
		 * client API.
		 *
		 * @since Theme_Blvd 2.3.0
		 *
		 * @param array $formatted_options All formatted options.
		 */
		$this->formatted_options = apply_filters( 'themeblvd_formatted_options', $this->formatted_options );

	}

	/**
	 * Set currently stored theme settings based on options.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param array $settings Optional current settings to be applied.
	 */
	public function set_settings( $settings = null ) {

		// Apply settings passed into function.
		if ( $settings && is_array( $settings ) ) {

			$this->settings = $settings;

			return;

		}

		// Or pull settings from DB if nothing was passed.
		$this->settings = get_option( $this->get_option_id() );

		/*
		 * Do settings exist? If not, grab default values.
		 * Only do this for the frontend.
		 */
		if ( ! $this->settings ) {

			if ( ! is_admin() ) {

				// Because frontend, we need to add sanitiziation.
				themeblvd_add_sanitization();

				/*
				 * Construct array of default values pulled from
				 * formatted options.
				 */
				$defaults = themeblvd_get_option_defaults( $this->formatted_options );
				$this->settings = $defaults;
				add_option( $this->get_option_id(), $defaults );

			}
		}

		// Verify data is saved properly.
		if ( $this->settings ) {

			$this->settings = $this->verify( $this->settings );

		}

		/**
		 * Filters settings on frontend to be accessed from the single
		 * array of all settings.
		 *
		 * @since Theme_Blvd 2.3.0
		 * @deprecated Theme_Blvd 2.7.0
		 *
		 * @param array $settings All theme settings for frontend.
		 */
		$this->settings = apply_filters( 'themeblvd_frontend_options', $this->settings ); // Backwards compat.

		/**
		 * Filters settings on frontend to be accessed from the single
		 * array of all settings.
		 *
		 * @since Theme_Blvd 2.7.0
		 *
		 * @param array $settings All theme settings for frontend.
		 */
		$this->settings = apply_filters( 'themeblvd_frontend_settings', $this->settings );

	}

	/**
	 * Set $args to be used for Theme_Blvd_Options_Page
	 * class instance for our main theme options page.
	 *
	 * @since Theme_Blvd 2.3.0
	 */
	public function set_args() {

		$this->args = array(
			'page_title' => __( 'Theme Options', 'jumpstart' ),
			'menu_title' => __( 'Theme Options', 'jumpstart' ),
			'cap'        => themeblvd_admin_module_cap( 'options' ),
			'menu_slug'  => $this->get_option_id(),
			'icon'       => '',
			'export'     => true,
			'import'     => true,
		);

		/**
		 * Filters the arguments used to create the theme
		 * options page.
		 *
		 * @see Theme_Blvd_Options_Page
		 *
		 * @since Theme_Blvd 2.3.0
		 *
		 * @param array $settings All theme settings for frontend.
		 */
		$this->args = apply_filters( 'themeblvd_theme_options_args', $this->args );

	}

	/**
	 * Verify theme options have been saved properly, and
	 * make any updates needed.
	 *
	 * This method will expanded over time as the framework
	 * changes. The idea here is that if we make any modification
	 * in how data is saved, we can handle it once here, and
	 * not worry aboutt it throughout the framework.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param  array $settings All currently saved settings to check.
	 * @return array $settings All saved settings, with any mods that were made.
	 */
	public function verify( $settings ) {

		/*
		 * Whether to update the options in the database,
		 * which we, of course, want to avoid.
		 */
		$update = false;

		/*
		 * Get framework version the options page was last
		 * saved with. Before v2.5.0, this option will be blank.
		 */
		$version = '0';

		if ( ! empty( $settings['framework_version'] ) ) {
			$version = $settings['framework_version'];
		}

		/*
		 * If options were last saved with current version of
		 * the framework, we know we don't need to do anything.
		 */
		if ( version_compare( TB_FRAMEWORK_VERSION, $version, '==' ) ) {

			return $settings;

		} else {

			$update = true;

			$theme = wp_get_theme( get_template() );

			$settings['theme_version'] = $theme->get( 'Version' );

			$settings['framework_version'] = TB_FRAMEWORK_VERSION;

		}

		/*
		 * 2.5.0 -- The structure of the `columns` option type
		 * has changed. Default framework option ID is `footer_setup`
		 * which utilizes this option type.
		 */
		if ( ! empty( $settings['footer_setup'] ) && is_array( $settings['footer_setup'] ) ) {

			$val = $settings['footer_setup'];

			if ( ! empty( $val['width'] ) && ! empty( $val['num'] ) ) {

				$widths = $val['width'][ $val['num'] ];

				$widths = explode( '-', $widths );

				foreach ( $widths as $key => $value ) {
					$widths[ $key ] = themeblvd_grid_fraction( $value );
				}

				$settings['footer_setup'] = implode( '-', $widths );

			}
		}

		/*
		 * 2.5.0 -- The structure of the `social_media` option type
		 * has changed. No framework default option with this type,
		 * but many themes use an option with id `social_media`.
		 */
		if ( ! empty( $settings['social_media'] ) ) {

			// Has it been saved with framework 2.5+?
			if ( ! is_array( current( $settings['social_media'] ) ) ) {

				$i = 1;

				$val = array();

				foreach ( $settings['social_media'] as $icon => $url ) {

					$val[ 'item_' . $i ] = array(
						'icon'   => $icon,
						'url'    => $url,
						'label'  => ucfirst( $icon ),
						'target' => '_blank',
					);

					$i++;

				}

				$settings['social_media'] = $val;
			}
		}

		// If update flag was set, update in the database.
		if ( $update ) {
			update_option( $this->get_option_id(), $settings );
		}

		return get_option( $this->get_option_id() );

	}

	/**
	 * Add options panel tab.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param string $tab_id   ID of tab to add.
	 * @param string $tab_name Name of the tab to add.
	 * @param bool   $top      Whether the tab should be added to the start or not.
	 */
	public function add_tab( $tab_id, $tab_name, $top = false ) {

		/*
		 * Can't create a tab that already exists.
		 * Must use remove_tab() first to modify.
		 */
		if ( isset( $this->raw_options[ $tab_id ] ) ) {
			return;
		}

		if ( $top ) {

			// Add tab to the top of array.
			$new_options = array();

			$new_options[ $tab_id ] = array(
				'name'     => $tab_name,
				'sections' => array(),
			);

			$this->raw_options = array_merge( $new_options, $this->raw_options );

		} else {

			// Add tab to the end of global array.
			$this->raw_options[ $tab_id ] = array(
				'name'     => $tab_name,
				'sections' => array(),
			);

		}
	}

	/**
	 * Remove options panel tab.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param string $tab_id ID of tab to add.
	 */
	public function remove_tab( $tab_id ) {

		unset( $this->raw_options[ $tab_id ] );

	}

	/**
	 * Add section to an options panel tab.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param string $tab_id       ID of tab section will be located in.
	 * @param string $section_id   ID of new section.
	 * @param string $section_name Name of new section.
	 * @param string $section_desc Description of new section.
	 * @param array  $options      Options array formatted for options system.
	 * @param bool   $top          Whether the option should be added to the top or not.
	 */
	public function add_section( $tab_id, $section_id, $section_name, $section_desc = '', $options = array(), $top = false ) {

		// Make sure tab exists.
		if ( ! isset( $this->raw_options[ $tab_id ] ) ) {
			return;
		}

		// Format options array.
		$new_options = array();

		if ( $options ) {
			foreach ( $options as $option ) {
				if ( isset( $option['id'] ) ) {
					$new_options[ $option['id'] ] = $option;
				}
			}
		}

		// Does the options section already exist?
		if ( isset( $this->raw_options[ $tab_id ]['sections'][ $section_id ] ) ) {

			$this->raw_options[ $tab_id ]['sections'][ $section_id ]['options'] = array_merge(
				$this->raw_options[ $tab_id ]['sections'][ $section_id ]['options'],
				$new_options
			);

			return;

		}

		// Add new section to top or bottom.
		if ( $top ) {

			$previous_sections = $this->raw_options[ $tab_id ]['sections'];

			$this->raw_options[ $tab_id ]['sections'] = array(
				$section_id => array(
					'name'    => $section_name,
					'desc'    => $section_desc,
					'options' => $new_options,
				),
			);

			$this->raw_options[ $tab_id ]['sections'] = array_merge(
				$this->raw_options[ $tab_id ]['sections'],
				$previous_sections
			);

		} else {

			$this->raw_options[ $tab_id ]['sections'][ $section_id ] = array(
				'name'    => $section_name,
				'desc'    => $section_desc,
				'options' => $new_options,
			);

		}

	}

	/**
	 * Remove section from an options panel tab.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param string $tab_id     ID of tab that section to remove belongs to.
	 * @param string $section_id ID of section to remove.
	 */
	public function remove_section( $tab_id, $section_id ) {

		unset( $this->raw_options[ $tab_id ]['sections'][ $section_id ] );

	}

	/**
	 * Add option.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param string $tab_id     ID of tab to add option to.
	 * @param string $section_id ID of section to add to.
	 * @param array  $option     Attributes for option, formatted for options system.
	 * @param string $option_id  ID of of your option, note that this id must also be present in $option array.
	 */
	public function add_option( $tab_id, $section_id, $option_id, $option ) {

		if ( ! isset( $this->raw_options[ $tab_id ] ) ) {
			return;
		}

		if ( ! isset( $this->raw_options[ $tab_id ]['sections'][ $section_id ] ) ) {
			return;
		}

		$this->raw_options[ $tab_id ]['sections'][ $section_id ]['options'][ $option_id ] = $option;

	}

	/**
	 * Remove option.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param string $tab_id     ID of tab to add option to.
	 * @param string $section_id ID of section to add to.
	 * @param string $option_id  ID of of your option.
	 */
	public function remove_option( $tab_id, $section_id, $option_id ) {

		if ( ! isset( $this->raw_options[ $tab_id ] ) || ! isset( $this->raw_options[ $tab_id ]['sections'][ $section_id ] ) ) {
			return;
		}

		if ( isset( $this->raw_options[ $tab_id ]['sections'][ $section_id ]['options'][ $option_id ] ) ) {

			/*
			 * If option has element's ID as key, we can find and
			 * remove it easier.
			 */
			unset( $this->raw_options[ $tab_id ]['sections'][ $section_id ]['options'][ $option_id ] );

		} else {

			/*
			 * If this is an option added by a child theme or plugin,
			 * and it doesn't have the element's ID as the key, we'll
			 * need to loop through to find it in order to remove it.
			 */
			foreach ( $this->raw_options[ $tab_id ]['sections'][ $section_id ]['options'] as $key => $value ) {

				if ( $value['id'] == $option_id ) {

					unset( $this->raw_options[ $tab_id ]['sections'][ $section_id ]['options'][ $key ] );

				}
			}
		}
	}

	/**
	 * Edit option.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param string $tab_id     ID of tab to add option to.
	 * @param string $section_id ID of section to add to.
	 * @param string $option_id  ID of of your option.
	 * @param string $att        Attribute of option to change.
	 * @param string $value      New value for attribute.
	 */
	public function edit_option( $tab_id, $section_id, $option_id, $att, $value ) {

		if ( ! isset( $this->raw_options[ $tab_id ] ) ) {
			return;
		}

		if ( ! isset( $this->raw_options[ $tab_id ]['sections'][ $section_id ] ) ) {
			return;
		}

		if ( ! isset( $this->raw_options[ $tab_id ]['sections'][ $section_id ]['options'][ $option_id ] ) ) {
			return;
		}

		$this->raw_options[ $tab_id ]['sections'][ $section_id ]['options'][ $option_id ][ $att ] = $value;

	}

	/**
	 * Add set of preset option values user can populate
	 * portion of form with.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param array $args {
	 *     @type string $id      ID of presets section.
	 *     @type string $tab     ID of tab preset section will be added to the top of.
	 *     @type string $section ID given to section added for presets.
	 *     @type array  $sets    Multiple arrays of option values, organized by preset ID (see start of function).
	 * }
	 */
	public function add_presets( $args ) {

		/* Structure for sets:
		array(
			'set_1' => array(
				'id'			=> 'set_1',
				'name'			=> '',		// Optional: Used for tooltip and for link, if no thumb specified
				'icon'			=> '',		// Full URL to image file
				'icon_width'	=> '80',	// Display width for image, if empty, assumed using Text
				'icon_height'	=> '',		// Optional force height of image
				'icon_style'	=> '',		// Optional inline style for icon
				'settings'		=> array(
					'foo' 	=> 'bar',
					'foo2' 	=> 'bar2'
				)
			)
			// And so on...
		)
		*/

		$defaults = array(
			'id'      => '',
			'tab'     => '',
			'section' => '',
			'sets'    => array(),
		);

		$args = wp_parse_args( $args, $defaults );

		if ( ! $args['tab'] ) {
			return;
		}

		if ( $args['section'] ) {
			$level = 2;
		} else {
			$level = 1;
		}

		$preset = $args;

		$preset['id'] = 'preset_' . $preset['id'];

		$preset['level'] = $level;

		unset( $preset['tab'], $preset['section'] );

		if ( 2 == $level ) {

			$this->raw_options[ $args['tab'] ]['sections'][ $args['section'] ]['preset'] = $preset;

		} elseif ( 1 == $level ) {

			$this->raw_options[ $args['tab'] ]['preset'] = $preset;

		}

	}

	/**
	 * Set option name that options and settings will
	 * be associated with.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @return string $option_id Option ID theme settings are stored to the database with.
	 */
	public function get_option_id() {

		/**
		 * Filters the option ID.
		 *
		 * The option ID is used to retrieve the theme's
		 * options like `get_option( $option_id )`.
		 *
		 * @since Theme_Blvd 2.3.0
		 *
		 * @param string $option_id ID options are saved to in the database.
		 */
		return apply_filters( 'themeblvd_option_id', $this->option_id );

	}

	/**
	 * Get core options.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @return array $raw_options All options before formatting.
	 */
	public function get_raw_options() {

		return $this->raw_options;

	}

	/**
	 * Get formatted options.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @return array $formatted_options All options after formatting.
	 */
	public function get_formatted_options() {

		return $this->formatted_options;

	}

	/**
	 * Get settings, or drill down and retrieve indiviual
	 * settings.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @param  string       $primary   Optional primary ID of the option.
	 * @param  string       $secondary Optional $secondary ID to traverse deeper into arrays.
	 * @param  string       $default   Optional default value to be applied if value is empty.
	 * @return array|string $settings  Entire settings array or individual setting string.
	 */
	public function get_setting( $primary = '', $secondary = '', $default = null ) {

		if ( ! $primary ) {
			return $this->settings;
		}

		if ( $secondary ) {

			if ( ! isset( $this->settings[ $primary ][ $secondary ] ) ) {

				if ( null !== $default ) {

					return $default;

				} elseif ( isset( $this->formatted_options[ $primary ]['std'][ $secondary ] ) ) {

					return $this->formatted_options[ $primary ]['std'][ $secondary ];

				} else {

					return null;

				}
			}

			return $this->settings[ $primary ][ $secondary ];

		}

		if ( ! isset( $this->settings[ $primary ] ) ) {

			if ( null !== $default ) {

				return $default;

			} elseif ( isset( $this->formatted_options[ $primary ]['std'] ) ) {

				return $this->formatted_options[ $primary ]['std'];

			} else {

				return null;

			}
		}

		return $this->settings[ $primary ];

	}

	/**
	 * Get $args to be used for Theme_Blvd_Options_Page
	 * class instance for our main theme options page.
	 *
	 * @since Theme_Blvd 2.3.0
	 *
	 * @return array $args Arguments used to build theme options page.
	 */
	public function get_args() {

		return $this->args;

	}

} // End class Theme_Blvd_Options_API.
