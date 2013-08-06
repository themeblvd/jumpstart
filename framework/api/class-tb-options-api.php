<?php
/**
 * Theme Blvd Options API
 *
 * This class establishes all of the Theme Blvd
 * framework's theme options, and sets up the API
 * system to allow these options to be modified
 * from the client-side.
 *
 * Also, this class provides access to the saved
 * settings cooresponding to the these theme options.
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
class Theme_Blvd_Options_API {

	/*--------------------------------------------*/
	/* Properties, private
	/*--------------------------------------------*/

	/**
	 * A single instance of this class.
	 *
	 * @since 2.3.0
	 */
	private static $instance = null;

	/**
	 * The options name associated with the the theme
	 * options and settings. i.e. get_option({name})
	 *
	 * @since 2.3.0
	 */
	private $option_id = '';

	/**
	 * Raw options modified along the way by client.
	 *
	 * @since 2.3.0
	 */
	private $raw_options = array();

	/**
	 * Formatted options after client modifications.
	 *
	 * @since 2.3.0
	 */
	private $formatted_options = array();

	/**
	 * Settings saved in the DB for the current site.
	 *
	 * @since 2.3.0
	 */
	private $settings = array();

	/**
	 * The arguments for the Theme Options page that'll
	 * get passed through to Theme_Blvd_Options_Page class.
	 *
	 * @since 2.3.0
	 */
	private $args = array();

	/*--------------------------------------------*/
	/* Constructor
	/*--------------------------------------------*/

	/**
     * Creates or returns an instance of this class.
     *
     * @since 2.3.0
     *
     * @return Theme_Blvd_Options_API A single instance of this class.
     */
	public static function get_instance() {

		if ( self::$instance == null ) {
            self::$instance = new self;
        }

        return self::$instance;
	}

	/**
	 * Constructor. Hook everything in and setup API.
	 *
	 * @since 2.3.0
	 */
	private function __construct() {

		// Setup options
		$this->set_raw_options();
		$this->set_option_id();

		// Format options, and store saved settings
		add_action( 'after_setup_theme', array( $this, 'set_formatted_options' ), 1000 );
		add_action( 'after_setup_theme', array( $this, 'set_settings' ), 1000 );
		add_action( 'after_setup_theme', array( $this, 'set_args' ), 1000 );

	}

	/*--------------------------------------------*/
	/* Methods, general mutators
	/*--------------------------------------------*/

	/**
	 * Set option name that options and settings will
	 * be associated with.
	 *
	 * @since 2.3.0
	 *
	 * @param string $id Optional current ID to be applied.
	 */
	public function set_option_id( $id = '' ) {

		if ( $id ) {
			$this->option_id = apply_filters( 'themeblvd_set_option_id', $id );
			return;
		}

		// This gets the theme name from the stylesheet (lowercase and without spaces)
		$theme_data = wp_get_theme( get_stylesheet() );
		$themename = preg_replace('/\W/', '', strtolower( $theme_data->get('Name') ) );

		$this->option_id = apply_filters( 'themeblvd_set_option_id', $themename );
	}

	/**
	 * Setup raw options array for the start of the
	 * API process.
	 *
	 * Note: The framework used to reference these as
	 * "core options" before this class existed.
	 *
	 * @since 2.3.0
	 *
	 * Layout
	 *	- Header
	 *		- logo
	 *	- Main
	 *		- breadcrumbs
	 *		- sidebar_layout
	 *	- Footer
	 *		- start_footer_cols
	 *		- footer_setup
	 *		- footer_col_1
	 *		- footer_col_2
	 *		- footer_col_3
	 *		- footer_col_4
	 *		- footer_col_5
	 *		- end_footer_cols
	 *		- footer_copyright
	 * Content
	 *	- Single Posts
	 *		- single_meta
	 *		- single_thumbs
	 *		- single_comments
	 *	- Primary Posts Display
	 *		- blog_thumbs
	 *		- blog_content
	 *		- blog_categories
	 *		- start_featured
	 *		- blog_featured
	 *		- blog_slider
	 *		- end_featured
	 *	- Archives
	 *		- archive_title
	 *		- archive_thumbs
	 *		- archive_content
	 *	- Lightbox
	 *		- lightbox_animation
	 *		- lightbox_mobile
	 *		- lightbox_mobile_iframe
	 *		- lightbox_mobile_gallery
	 */
	private function set_raw_options() {

		/*--------------------------------*/
		/* Option helpers
		/*--------------------------------*/

		// If using image radio buttons, define a directory path
		$imagepath =  get_template_directory_uri() . '/framework/admin/assets/images/';

		// Generate sidebar layout options
		$sidebar_layouts = array();
		if ( is_admin() ) {
			$layouts = themeblvd_sidebar_layouts();
			foreach ( $layouts as $layout )
				$sidebar_layouts[$layout['id']] = $imagepath.'layout-'.$layout['id'].'_2x.png';
		}

		// Pull all the categories into an array
		$options_categories = array();
		if ( is_admin() ) {
			$options_categories_obj = get_categories( array( 'hide_empty' => false ) );
			foreach ( $options_categories_obj as $category ) {
		    	$options_categories[$category->cat_ID] = $category->cat_name;
			}
		}

		/*--------------------------------*/
		/* Tab #1: Layout
		/*--------------------------------*/

		$layout_options = array(
			// Section: Header
			'header' => array(
				'name' => __( 'Header', 'themeblvd' ),
				'options' => array(
					'logo' => array(
						'name' 		=> __( 'Logo', 'themeblvd' ),
						'desc' 		=> __( 'Configure the primary branding logo for the header of your site.<br /><br />Use the "Upload" button to either upload an image or select an image from your media library. When inserting an image with the "Upload" button, the URL and width will be inserted for you automatically. You can also type in the URL to an image in the text field along with a manually-entered width.<br /><br />If you\'re inputting a "HiDPI-optimized" image, it needs to be twice as large as you intend it to be displayed. Feel free to leave the HiDPI image field blank if you\'d like it to simply not have any effect.', 'themeblvd' ),
						'id' 		=> 'logo',
						'std' 		=> array( 'type' => 'image', 'image' => get_template_directory_uri().'/assets/images/logo.png', 'image_width' => '220', 'image_2x' => get_template_directory_uri().'/assets/images/logo_2x.png' ),
						'type' 		=> 'logo'
					)
				) // End header options
			),
			// Section: Main
			'main' => array(
				'name' => __( 'Main', 'themeblvd' ),
				'options' => array(
					'breadcrumbs' => array(
						'name' 		=> __( 'Breadcrumbs', 'themeblvd' ),
						'desc'		=> __( 'Select whether you\'d like breadcrumbs to show throughout the site or not.', 'themeblvd' ),
						'id'		=> 'breadcrumbs',
						'std'		=> 'show',
						'type' 		=> 'select',
						'options'	=> array(
							'show' => __( 'Yes, show breadcrumbs.', 'themeblvd' ),
							'hide' => __( 'No, hide breadcrumbs.', 'themeblvd' )
						)
					),
					'sidebar_layout' => array(
						'name' 		=> __( 'Default Sidebar Layout', 'themeblvd' ),
						'desc' 		=> __( 'Choose the default sidebar layout for the main content area of your site.<br><br><em>Note: This will be the default sidebar layout throughout your site, but you can be override this setting for any specific page or custom layout.</em>', 'themeblvd' ),
						'id' 		=> 'sidebar_layout',
						'std' 		=> 'sidebar_right',
						'type' 		=> 'images',
						'options' 	=> $sidebar_layouts,
						'img_width'	=> '65' // HiDPI compatibility, 1/2 of images' natural widths
					)
				) // End main options
			),
			// Section: Footer
			'footer' => array(
				'name' => __( 'Footer', 'themeblvd' ),
				'options' => array(
					'start_footer_cols' => array(
						'type'		=> 'subgroup_start',
						'class'		=> 'columns'
					),
					'footer_setup' => array(
						'name'		=> __( 'Setup Columns', 'themeblvd' ),
						'desc'		=> __( 'Choose the number of columns along with the corresponding width configurations.', 'themeblvd' ),
						'id' 		=> 'footer_setup',
						'type'		=> 'columns',
						'options'	=> 'standard'
					),
					'footer_col_1' => array(
						'name'		=> __( 'Footer Column #1', 'themeblvd' ),
						'desc'		=> __( 'Configure the content for the first column.', 'themeblvd' ),
						'id' 		=> 'footer_col_1',
						'type'		=> 'content',
						'class'		=> 'col_1',
						'options'	=> array( 'widget', 'page', 'raw' )
					),
					'footer_col_2' => array(
						'name'		=> __( 'Footer Column #2', 'themeblvd' ),
						'desc'		=> __( 'Configure the content for the second column.', 'themeblvd' ),
						'id' 		=> 'footer_col_2',
						'type'		=> 'content',
						'class'		=> 'col_2',
						'options'	=> array( 'widget', 'page', 'raw' )
					),
					'footer_col_3' => array(
						'name'		=> __( 'Footer Column #3', 'themeblvd' ),
						'desc'		=> __( 'Configure the content for the third column.', 'themeblvd' ),
						'id' 		=> 'footer_col_3',
						'type'		=> 'content',
						'class'		=> 'col_3',
						'options'	=> array( 'widget', 'page', 'raw' )
					),
					'footer_col_4' => array(
						'name'		=> __( 'Footer Column #4', 'themeblvd' ),
						'desc'		=> __( 'Configure the content for the fourth column.', 'themeblvd' ),
						'id' 		=> 'footer_col_4',
						'type'		=> 'content',
						'class'		=> 'col_4',
						'options'	=> array( 'widget', 'page', 'raw' )
					),
					'footer_col_5' => array(
						'name'		=> __( 'Footer Column #5', 'themeblvd' ),
						'desc'		=> __( 'Configure the content for the fifth column.', 'themeblvd' ),
						'id' 		=> 'footer_col_5',
						'type'		=> 'content',
						'class'		=> 'col_5',
						'options'	=> array( 'widget', 'page', 'raw' )
					),
					'end_footer_cols' => array(
						'type'		=> 'subgroup_end'
					),
					'footer_copyright' => array(
						'name' 		=> __( 'Footer Copyright Text', 'themeblvd' ),
						'desc' 		=> __( 'Enter the copyright text you\'d like to show in the footer of your site.', 'themeblvd' ),
						'id' 		=> 'footer_copyright',
						'std' 		=> '(c) '.date('Y').' '.get_bloginfo('site_title').' - Powered by <a href="http://wordpress.org" title="WordPress" target="_blank">WordPress</a>, Designed by <a href="http://themeblvd.com" title="Theme Blvd" target="_blank">Theme Blvd</a>',
						'type' 		=> 'text'
					)
				) // End footer options
			)
		);

		/*--------------------------------*/
		/* Tab #2: Content
		/*--------------------------------*/

		$content_options = array(
			// Section: Single Posts
			'single' => array(
				'name' => __( 'Single Posts', 'themeblvd' ),
				'desc' => __( 'These settings will only apply to vewing single posts. This means that any settings you set here will <strong>not</strong> effect any posts that appear in a post list or post grid.', 'themeblvd' ),
				'options' => array(
					'single_meta' => array(
						'name' 		=> __( 'Show meta information at top of posts?', 'themeblvd' ),
						'desc' 		=> __( 'Select if you\'d like the meta information (date posted, author, etc) to show at the top of the post. If you\'re going for a portfolio-type setup, you may want to hide the meta info.', 'themeblvd' ),
						'id' 		=> 'single_meta',
						'std' 		=> 'show',
						'type' 		=> 'radio',
						'options' 	=> array(
							'show'		=> __( 'Show meta info.', 'themeblvd' ),
							'hide' 		=> __( 'Hide meta info.', 'themeblvd' )
						)
					),
					'single_thumbs' => array(
						'name' 		=> __( 'Show featured images at top of posts?', 'themeblvd' ),
						'desc' 		=> __( 'Choose how you want your featured images to show at the top of the posts. It can be useful to turn this off if you want to have featured images over on your blogroll or post grid sections, but you don\'t want them to show on the actual posts themeselves.', 'themeblvd' ),
						'id' 		=> 'single_thumbs',
						'std' 		=> 'small',
						'type' 		=> 'radio',
						'options' 	=> array(
							'small'		=> __( 'Show small thumbnails.', 'themeblvd' ),
							'full' 		=> __( 'Show full-width thumbnails.', 'themeblvd' ),
							'hide' 		=> __( 'Hide thumbnails.', 'themeblvd' )
						)
					),
					'single_comments' => array(
						'name' 		=> __( 'Show comments below posts?', 'themeblvd' ),
						'desc' 		=> __( 'Select if you\'d like to completely hide comments or not below the post.', 'themeblvd' ),
						'id' 		=> 'single_comments',
						'std' 		=> 'show',
						'type' 		=> 'radio',
						'options' 	=> array(
							'show'		=> __( 'Show comments.', 'themeblvd' ),
							'hide' 		=> __( 'Hide comments.', 'themeblvd' )
						)
					)
				) // End single options
			),
			// Section: Primary Posts Display
			'blog' => array(
				'name' => __( 'Primary Posts Display', 'themeblvd' ),
				'desc' => __( 'These settings apply to your primary posts page that you\'ve selected under Settings > Reading and <strong>all</strong> instances of the "Post List" page template. Note that if you want to use the post list page template for multiple pages with different categories on each, you can accomplish this on each specific page with custom fields - <a href="http://vimeo.com/32754998">Learn More</a>.', 'themeblvd' ),
				'options' => array(
					'blog_thumbs' => array(
						'name' 		=> __( 'Featured Images', 'themeblvd' ),
						'desc' 		=> __( 'Select the size of the blog\'s post thumbnail or whether you\'d like to hide them all together when posts are listed.', 'themeblvd' ),
						'id' 		=> 'blog_thumbs',
						'std' 		=> 'small',
						'type' 		=> 'radio',
						'options' 	=> array(
							'small'		=> __( 'Show small thumbnails.', 'themeblvd' ),
							'full' 		=> __( 'Show full-width thumbnails.', 'themeblvd' ),
							'hide' 		=> __( 'Hide thumbnails.', 'themeblvd' )
						)
					),
					'blog_content' => array(
						'name' 		=> __( 'Show excerpts or full content?', 'themeblvd' ),
						'desc' 		=> __( 'Choose whether you want to show full content or post excerpts only.', 'themeblvd' ),
						'id' 		=> 'blog_content',
						'std' 		=> 'content',
						'type' 		=> 'radio',
						'options' 	=> array(
							'content'	=> __( 'Show full content.', 'themeblvd' ),
							'excerpt' 	=> __( 'Show excerpt only.', 'themeblvd' )
						)
					),
					'blog_categories' => array(
						'name' 		=> __( 'Exclude Categories', 'themeblvd' ),
						'desc' 		=> __( 'Select any categories you\'d like to be excluded from your blog.', 'themeblvd' ),
						'id' 		=> 'blog_categories',
						'type' 		=> 'multicheck',
						'options' 	=> $options_categories
					)
				) // End blog options
			),
			// Section: Archives
			'archives' => array(
				'name' => __( 'Archives', 'themeblvd' ),
				'desc' => __( 'These settings apply any time you\'re viewing search results or posts specific to a category, tag, date, author, etc.', 'themeblvd' ),
				'options' => array(
					'archive_title' => array(
						'name' 		=> __( 'Show title on archive pages?', 'themeblvd' ),
						'desc' 		=> __( 'Choose whether or not you want the title to show on tag archives, category archives, date archives, author archives and search result pages.', 'themeblvd' ),
						'id' 		=> 'archive_title',
						'std' 		=> 'false',
						'type' 		=> 'radio',
						'options' 	=> array(
							'true'	=> __( 'Yes, show main title at the top of archive pages.', 'themeblvd' ),
							'false' => __( 'No, hide the title.', 'themeblvd' )
						)
					),
					'archive_thumbs' => array(
						'name' 		=> __( 'Show featured images on archive pages?', 'themeblvd' ),
						'desc' 		=> __( 'Choose whether or not you want featured images to show on tag archives, category archives, date archives, author archives and search result pages.', 'themeblvd' ),
						'id' 		=> 'archive_thumbs',
						'std' 		=> 'small',
						'type' 		=> 'radio',
						'options' 	=> array(
							'small'		=> __( 'Show small thumbnails.', 'themeblvd' ),
							'full' 		=> __( 'Show full-width thumbnails.', 'themeblvd' ),
							'hide' 		=> __( 'Hide thumbnails.', 'themeblvd' )
						)
					),
					'archive_content' => array(
						'name' 		=> __( 'Show excerpts or full content?', 'themeblvd' ),
						'desc' 		=> __( 'Choose whether you want to show full content or post excerpts only.', 'themeblvd' ),
						'id' 		=> 'archive_content',
						'std' 		=> 'excerpt',
						'type' 		=> 'radio',
						'options' 	=> array(
							'content'	=> __( 'Show full content.', 'themeblvd' ),
							'excerpt' 	=> __( 'Show excerpt only.', 'themeblvd' )
						)
					)
				) // End archives options
			),
			'lightbox' => array(
				'name' => __( 'Lightbox', 'themeblvd' ),
				'desc' => __( 'These settings apply to the built-in lightbox functionality, which utilizes the <a href="http://dimsemenov.com/plugins/magnific-popup/" target="_blank">Magnific Popup</a> script.', 'themeblvd' ),
				'options' => array(
					'lightbox_animation' => array(
						'name' 		=> __( 'Animate lightboxes?', 'themeblvd' ),
						'desc' 		=> __( 'Select if you\'d like lightboxes to animate as they open and close.', 'themeblvd' ),
						'id' 		=> 'lightbox_animation',
						'std' 		=> 'fade',
						'type' 		=> 'radio',
						'options' 	=> array(
							'none'		=> __( 'No animation', 'themeblvd' ),
							'fade' 		=> __( 'Fade animation', 'themeblvd' )
						)
					),
					'lightbox_mobile' => array(
						'name' 		=> __( 'Disable standard lightboxes for mobile?', 'themeblvd' ),
						'desc' 		=> __( 'Select if you\'d like the lightbox to be disabled for mobile users viewing a standard lightbox instance.', 'themeblvd' ),
						'id' 		=> 'lightbox_mobile',
						'std' 		=> 'no',
						'type' 		=> 'radio',
						'options' 	=> array(
							'yes'		=> __( 'Yes, disable for mobile.', 'themeblvd' ),
							'no' 		=> __( 'No, do not disable for mobile.', 'themeblvd' )
						)
					),
					'lightbox_mobile_iframe' => array(
						'name' 		=> __( 'Disable iframe lightboxes for mobile?', 'themeblvd' ),
						'desc' 		=> __( 'Select if you\'d like the lightbox to be disabled for mobile users viewing an iframe lightbox instance. This includes linking to YouTube videos, Vimeo videos, and Google Maps in a lightbox popup.', 'themeblvd' ),
						'id' 		=> 'lightbox_mobile_iframe',
						'std' 		=> 'yes',
						'type' 		=> 'radio',
						'options' 	=> array(
							'yes'		=> __( 'Yes, disable for mobile.', 'themeblvd' ),
							'no' 		=> __( 'No, do not disable for mobile.', 'themeblvd' )
						)
					),
					'lightbox_mobile_gallery' => array(
						'name' 		=> __( 'Disable gallery lightboxes for mobile?', 'themeblvd' ),
						'desc' 		=> __( 'Select if you\'d like the lightbox to be disabled for mobile users when viewing a gallery.', 'themeblvd' ),
						'id' 		=> 'lightbox_mobile_gallery',
						'std' 		=> 'no',
						'type' 		=> 'radio',
						'options' 	=> array(
							'yes'		=> __( 'Yes, disable for mobile.', 'themeblvd' ),
							'no' 		=> __( 'No, do not disable for mobile.', 'themeblvd' )
						)
					)
				)
			) // End archives options
		);

		/*--------------------------------*/
		/* Finalize and extend
		/*--------------------------------*/

		$this->raw_options = array(
			'layout' 	=> array(
				'name' 		=> __( 'Layout', 'themeblvd' ),
				'sections' 	=> $layout_options
			),
			'content' 	=> array(
				'name' 		=> __( 'Content', 'themeblvd' ),
				'sections' 	=> $content_options
			)
		);

		// The following filter probably won't be used often,
		// but if there's something that can't be accomplished
		// through the client mutator API methods, then this
		// provides a way to modify these raw options.
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
	 * @since 2.3.0
	 */
	public function set_formatted_options() {

		$this->formatted_options = array();

		// Tab Level
		foreach ( $this->raw_options as $tab_id => $tab ) {

			// Insert Tab Heading
			$this->formatted_options['tab_'.$tab_id] = array(
				'id' 	=> $tab_id,
				'name' 	=> $tab['name'],
				'type' 	=> 'heading'
			);

			// Section Level
			if ( $tab['sections'] ) {
				foreach ( $tab['sections'] as $section_id => $section ) {

					// Start section
					$this->formatted_options['start_section_'.$section_id] = array(
						'name' => $section['name'],
						'type' => 'section_start'
					);

					if ( isset( $section['desc'] ) ) {
						$this->formatted_options['start_section_'.$section_id]['desc'] = $section['desc'];
					}

					// Options Level
					if ( $section['options'] ) {
						foreach ( $section['options'] as $option_id => $option ) {
							$this->formatted_options[$option_id] = $option;
						}
					}

					// End section
					$this->formatted_options['end_section_'.$section_id] = array(
						'type' => 'section_end'
					);
				}
			}
		}

		// Apply filters
		$this->formatted_options = apply_filters( 'themeblvd_formatted_options', $this->formatted_options );

	}

	/**
	 * Set currently stored theme settings based on options.
	 *
	 * @since 2.3.0
	 *
	 * @param array $settings Optional current settings to be applied.
	 */
	public function set_settings( $settings = null ) {

		// Apply settings passed into function
		if ( $settings && is_array( $settings ) ) {
			$this->settings = $settings;
			return;
		}

		// Or pull settings from DB
		$this->settings = get_option( $this->get_option_id() );

		// Do settings exist? If not, grab default values.
		// Only do this for the frontend.
		if ( ! $this->settings ) {

			// Because frontend, we need to add sanitiziation
			if ( ! is_admin() ) {
				themeblvd_add_sanitization();
			}

			// Construct array of default values pulled from
			// formatted options.
			$defaults = themeblvd_get_option_defaults( $this->formatted_options );
			$this->settings = $defaults;
			add_option( $this->get_option_id(), $defaults );

		}

		$this->settings = apply_filters( 'themeblvd_frontend_options', $this->settings );
	}

	/**
	 * Set $args to be used for Theme_Blvd_Options_Page
	 * class instance for our main theme options page.
	 *
	 * @since 2.3.0
	 */
	public function set_args() {
		$this->args = array(
			'parent'		=> 'themes.php',
			'page_title' 	=> __( 'Theme Options', 'themeblvd' ),
			'menu_title' 	=> __( 'Theme Options', 'themeblvd' ),
			'cap'			=> themeblvd_admin_module_cap( 'options' ),
			'menu_slug'		=> $this->get_option_id(),
			'icon'			=> '',
			'closer'		=> true // Needs to be false if option page has no tabs
		);
		$this->args = apply_filters( 'themeblvd_theme_options_args', $this->args );
	}

	/*--------------------------------------------*/
	/* Methods, client API mutators
	/*--------------------------------------------*/

	/**
	 * Add options panel tab.
	 *
	 * @since 2.3.0
	 *
	 * @param string $tab_id ID of tab to add
	 * @param string $tab_name Name of the tab to add
	 * @param bool $top Whether the tab should be added to the start or not
	 */
	public function add_tab( $tab_id, $tab_name, $top = false ) {

		// Can't create a tab that already exists. 
		// Must use remove_tab() first to modify.
		if ( isset( $this->raw_options[$tab_id] ) ) {
			return;
		}

		if ( $top ) {

			// Add tab to the top of array
			$new_options = array();
			$new_options[$tab_id] = array(
				'name' 		=> $tab_name,
				'sections' 	=> array()
			);
			$this->raw_options = array_merge( $new_options, $this->raw_options );

		} else {

			// Add tab to the end of global array
			$this->raw_options[$tab_id] = array(
				'name' 		=> $tab_name,
				'sections' 	=> array()
			);

		}
	}

	/**
	 * Remove options panel tab.
	 *
	 * @since 2.3.0
	 *
	 * @param string $tab_id ID of tab to add
	 */
	public function remove_tab( $tab_id ) {
		unset( $this->raw_options[$tab_id] );
	}

	/**
	 * Add section to an options panel tab.
	 *
	 * @since 2.3.0
	 *
	 * @param string $tab_id ID of tab section will be located in
	 * @param string $section_id ID of new section
	 * @param string $section_name Name of new section
	 * @param string $section_desc Description of new section
	 * @param array $options Options array formatted for Options Framework
	 * @param bool $top Whether the option should be added to the top or not
	 */
	public function add_section( $tab_id, $section_id, $section_name, $section_desc = '', $options = array(), $top = false ) {

		// Make sure tab exists
		if ( ! isset( $this->raw_options[$tab_id] ) )
			return;

		// Format options array
		$new_options = array();
		if ( $options ) {
			foreach ( $options as $option ) {
				if ( isset( $option['id'] ) ) {
					$new_options[$option['id']] = $option;
				}
			}
		}

		// Does the options section already exist?
		if ( isset( $this->raw_options[$tab_id]['sections'][$section_id] ) ) {
			$this->raw_options[$tab_id]['sections'][$section_id]['options'] = array_merge( $this->raw_options[$tab_id]['sections'][$section_id]['options'], $new_options );
			return;
		}

		// Add new section to top or bottom
		if ( $top ) {

			$previous_sections = $this->raw_options[$tab_id]['sections'];

			$this->raw_options[$tab_id]['sections'] = array(
				$section_id => array(
					'name' 		=> $section_name,
					'desc' 		=> $section_desc,
					'options' 	=> $new_options
				)
			);

			$this->raw_options[$tab_id]['sections'] = array_merge( $this->raw_options[$tab_id]['sections'], $previous_sections );

		} else {

			$this->raw_options[$tab_id]['sections'][$section_id] = array(
				'name'		=> $section_name,
				'desc'		=> $section_desc,
				'options'	=> $new_options
			);

		}

	}

	/**
	 * Remove section from an options panel tab.
	 *
	 * @since 2.3.0
	 *
	 * @param string $tab_id ID of tab that section to remove belongs to
	 * @param string $section_id ID of section to remove
	 */
	public function remove_section( $tab_id, $section_id ) {
		unset( $this->raw_options[$tab_id]['sections'][$section_id] );
	}

	/**
	 * Add option.
	 *
	 * @since 2.3.0
	 *
	 * @param string $tab_id ID of tab to add option to
	 * @param string $section_id ID of section to add to
	 * @param array $option attributes for option, formatted for Options Framework
	 * @param string $option_id ID of of your option, note that this id must also be present in $option array
	 */
	public function add_option( $tab_id, $section_id, $option_id, $option ) {

		if ( ! isset( $this->raw_options[$tab_id] ) ) {
			return;
		}

		if ( ! isset( $this->raw_options[$tab_id]['sections'][$section_id] ) ) {
			return;
		}

		$this->raw_options[$tab_id]['sections'][$section_id]['options'][$option_id] = $option;
	}

	/**
	 * Remove option.
	 *
	 * @since 2.3.0
	 *
	 * @param string $tab_id ID of tab to add option to
	 * @param string $section_id ID of section to add to
	 * @param string $option_id ID of of your option
	 */
	public function remove_option( $tab_id, $section_id, $option_id ) {

		if ( ! isset( $this->raw_options[$tab_id] ) || ! isset( $this->raw_options[$tab_id]['sections'][$section_id] ) ) {
			return;
		}

		if ( isset( $this->raw_options[$tab_id]['sections'][$section_id]['options'][$option_id] ) ) {

			// If option has element's ID as key, we can find and
			// remove it easier.
			unset( $this->raw_options[$tab_id]['sections'][$section_id]['options'][$option_id] );

		} else {

			// If this is an option added by a child theme or plugin,
			// and it doesn't have the element's ID as the key, we'll
			// need to loop through to find it in order to remove it.
			foreach ( $this->raw_options[$tab_id]['sections'][$section_id]['options'] as $key => $value ) {
				if ( $value['id'] == $option_id ) {
					unset( $this->raw_options[$tab_id]['sections'][$section_id]['options'][$key] );
				}
			}

		}
	}

	/**
	 * Edit option.
	 *
	 * @since 2.3.0
	 *
	 * @param string $tab_id ID of tab to add option to
	 * @param string $section_id ID of section to add to
	 * @param string $option_id ID of of your option
	 * @param string $att Attribute of option to change
	 * @param string $value New value for attribute
	 */
	public function edit_option( $tab_id, $section_id, $option_id, $att, $value ) {

		if ( ! isset( $this->raw_options[$tab_id] ) ) {
			return;
		}

		if ( ! isset( $this->raw_options[$tab_id]['sections'][$section_id] ) ) {
			return;
		}

		if ( ! isset( $this->raw_options[$tab_id]['sections'][$section_id]['options'][$option_id] ) ) {
			return;
		}

		$this->raw_options[$tab_id]['sections'][$section_id]['options'][$option_id][$att] = $value;
	}

	/*--------------------------------------------*/
	/* Methods, accessors
	/*--------------------------------------------*/

	/**
	 * Set option name that options and settings will
	 * be associated with.
	 *
	 * @since 2.3.0
	 *
	 * @return string $option_id
	 */
	public function get_option_id() {
		return apply_filters( 'themeblvd_option_id', $this->option_id );
	}

	/**
	 * Get core options.
	 *
	 * @since 2.3.0
	 *
	 * @return array $raw_options
	 */
	public function get_raw_options() {
		return $this->raw_options;
	}

	/**
	 * Get formatted options
	 *
	 * @since 2.3.0
	 *
	 * @return array $formatted_options
	 */
	public function get_formatted_options() {
		return $this->formatted_options;
	}

	/**
	 * Get settings, or drill down and retrieve indiviual settings.
	 *
	 * @since 2.3.0
	 *
	 * @param string $primary Optional primary ID of the option
	 * @param string $secondary Optional $secondary ID to traverse deeper into arrays
	 * @return array|string $settings Entire settings array or individual setting string
	 */
	public function get_setting( $primary = '', $seconday = '' ) {

		if ( ! $primary ) {
			return $this->settings;
		}

		if ( ! isset( $this->settings[$primary] ) ) {
			return null;
		}

		if ( $seconday ) {
			if ( isset( $this->settings[$primary][$seconday] ) ) {
				return $this->settings[$primary][$seconday];
			} else {
				return null;
			}
		}

		return $this->settings[$primary];
	}

	/**
	 * Get $args to be used for Theme_Blvd_Options_Page
	 * class instance for our main theme options page.
	 *
	 * @since 2.3.0
	 */
	public function get_args() {
		return $this->args;
	}

} // End class Theme_Blvd_Options_API