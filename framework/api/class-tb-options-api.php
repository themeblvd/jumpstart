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
	 *		- header_text
	 *		- social_media
	 *		- social_media_style
	 *		- searchform
	 *	- Main
	 *		- breadcrumbs
	 *		- sidebar_layout
	 *	- Footer
	 *		- footer_setup
	 *		- footer_col_1
	 *		- footer_col_2
	 *		- footer_col_3
	 *		- footer_col_4
	 *		- footer_col_5
	 *		- footer_copyright
	 *	- Extras
	 *		- scroll_to_top
	 * Content
	 *	- Single Posts
	 *		- single_meta
	 *		- single_thumbs
	 *		- single_comments
	 *	- Primary Posts Display
	 *		- blog_thumbs
	 *		- blog_content
	 *		- blog_categories
	 *	- Archives
	 *		- archive_title
	 *		- archive_thumbs
	 *		- archive_content
	 *	- Post Lists
	 *		- list_thumbs
	 *		- list_meta
	 *		- list_more
	 *		- list_more_text
	 *	- Post Grids
	 *		- grid_thumbs
	 *		- grid_meta
	 *		- grid_excerpt
	 *		- grid_more
	 *		- grid_more_text
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
			foreach ( $layouts as $layout ) {
				$sidebar_layouts[$layout['id']] = $imagepath.'layout-'.$layout['id'].'_2x.png';
			}
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
						'desc' 		=> __( 'Configure the primary branding logo for the header of your site.<br /><br /><em>Note: If you\'re inputting a "HiDPI-optimized" image, it needs to be twice as large as you intend it to be displayed. Feel free to leave the HiDPI image field blank if you\'d like it to simply not have any effect.</em>', 'themeblvd' ),
						'id' 		=> 'logo',
						'std' 		=> array( 'type' => 'image', 'image' => get_template_directory_uri().'/assets/images/logo.png', 'image_width' => '250', 'image_2x' => get_template_directory_uri().'/assets/images/logo_2x.png' ),
						'type' 		=> 'logo'
					),
					'header_text' => array(
						'name' 		=> __( 'Header Text', 'themeblvd' ),
						'desc'		=> __( 'Enter a very brief piece of text you\'d like to show You can use basic HTML here..', 'themeblvd' ),
						'id'		=> 'header_text',
						'std'		=> 'Welcome to our website!',
						'type' 		=> 'text'
					),
					'social_media' => array(
						'name' 		=> __( 'Social Media Buttons', 'themeblvd' ),
						'desc' 		=> __( 'Configure the social media buttons you\'d like to show.', 'themeblvd' ),
						'id' 		=> 'social_media',
						'std' 		=> array(
							'item_1' => array(
								'icon'	=> 'facebook',
								'url'	=> 'http://facebook.com/jasonbobich',
								'label'	=> 'Facebook'
							),
							'item_2' => array(
								'icon'	=> 'google',
								'url'	=> 'https://plus.google.com/116531311472104544767/posts',
								'label'	=> 'Google+'
							),
							'item_3' => array(
								'icon'	=> 'twitter',
								'url'	=> 'http://twitter.com/jasonbobich',
								'label'	=> 'Twitter'
							),
							'item_4' => array(
								'icon'	=> 'rss',
								'url'	=> get_feed_link(),
								'label'	=> 'RSS Feed'
							)
						),
						'type' 		=> 'social_media'
					),
					'social_media_style' => array(
						'name' 		=> __( 'Social Media Style', 'themeblvd' ),
						'desc'		=> __( 'Select the color you\'d like applied to the social icons.', 'themeblvd' ),
						'id'		=> 'social_media_style',
						'std'		=> 'flat',
						'type' 		=> 'select',
						'options'	=> array(
							'flat'			=> __( 'Flat Color', 'themeblvd' ),
							'dark' 			=> __( 'Flat Dark', 'themeblvd' ),
							'grey' 			=> __( 'Flat Grey', 'themeblvd' ),
							'light' 		=> __( 'Flat Light', 'themeblvd' ),
							'color'			=> __( 'Color', 'themeblvd' )
						)
					),
					'searchform' => array(
						'name' 		=> __( 'Search Form', 'themeblvd' ),
						'desc'		=> __( 'Select whether you\'d like to show a search form.', 'themeblvd' ),
						'id'		=> 'searchform',
						'std'		=> 'show',
						'type' 		=> 'select',
						'options'	=> array(
							'show'			=> __( 'Show search form', 'themeblvd' ),
							'hide' 			=> __( 'Hide search form', 'themeblvd' )
						)
					),
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
							'show' => __( 'Yes, show breadcrumbs', 'themeblvd' ),
							'hide' => __( 'No, hide breadcrumbs', 'themeblvd' )
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
						'class'		=> 'columns standard-footer-setup'
					),
					'footer_setup' => array(
						'name'		=> __( 'Setup Columns', 'themeblvd' ),
						'desc'		=> null,
						'id' 		=> 'footer_setup',
						//'std'		=> '1/3-1/3-1/3',
						'std'		=> '',
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
						'desc' 		=> __( '<p>Enter the copyright text you\'d like to show in the footer of your site.</p><p><em>%year%</em> &mdash; Show current year.<br /><em>%site_title%</em> &mdash; Show your site title.</p>', 'themeblvd' ),
						'id' 		=> 'footer_copyright',
						'std' 		=> '(c) %year% %site_title% - Powered by <a href="http://wordpress.org" title="WordPress" target="_blank">WordPress</a>, Designed by <a href="http://themeblvd.com" title="Theme Blvd" target="_blank">Theme Blvd</a>',
						'type' 		=> 'textarea',
						'editor'	=> true,
						'code'		=> 'html',
						'class'		=> 'standard-footer-setup'
					)
				) // End footer options
			),
			// Section: Extras
			'extras' => array(
				'name' => __( 'Extras', 'themeblvd' ),
				'options' => array(
					'scroll_to_top' => array(
						'name' 		=> __( 'Scroll-to-Top Button', 'themeblvd' ),
						'desc' 		=> __( 'If enabled, this will display a button that appears on the screen, which allows the user to quickly scroll back to the top of the website.', 'themeblvd' ),
						'id' 		=> 'scroll_to_top',
						'std' 		=> 'show',
						'type' 		=> 'radio',
						'options'	=> array(
							'show'	=> __('Yes, show button', 'themeblvd'),
							'hide'	=> __('No, don\'t show it', 'themeblvd'),
						)
					)
				)
			)
		);

		/*--------------------------------*/
		/* Tab #2: Content
		/*--------------------------------*/

		$content_options = array(

			// Section: Single Posts
			'single' => array(
				'name' => __( 'Single Posts', 'themeblvd' ),
				'desc' => __( 'These settings will only apply to vewing single posts. This means that any settings you set here will <strong>not</strong> effect any posts that appear in a post list or post grid. Additionally, most of these settings can be overridden in the Post Options section when editing individual posts.', 'themeblvd' ),
				'options' => array(
					'single_meta' => array(
						'name' 		=> __( 'Meta Information', 'themeblvd' ),
						'desc' 		=> __( 'Select if you\'d like the meta information (like date posted, author, etc) to show on the single post. If you\'re going for a non-blog type of setup, you may want to hide the meta info.', 'themeblvd' ),
						'id' 		=> 'single_meta',
						'std' 		=> 'show',
						'type' 		=> 'radio',
						'options' 	=> array(
							'show'		=> __( 'Show meta info', 'themeblvd' ),
							'hide' 		=> __( 'Hide meta info', 'themeblvd' )
						)
					),
					'single_sub_meta' => array(
						'name' 		=> __( 'Sub Meta Information', 'themeblvd' ),
						'desc' 		=> __( 'Select if you\'d like the sub meta information (like tags, categories, etc) to show on the single post.', 'themeblvd' ),
						'id' 		=> 'single_sub_meta',
						'std' 		=> 'show',
						'type' 		=> 'radio',
						'options' 	=> array(
							'show'		=> __( 'Show sub meta info', 'themeblvd' ),
							'hide' 		=> __( 'Hide sub meta info', 'themeblvd' )
						)
					),
					'share' => array( // generic name "share" so it can be thoertically moved to another option section, if applied to more than just signle post
						'name' 		=> __( 'Share Icons', 'themeblvd' ),
						'desc' 		=> __( 'Configure any share icons you\'d like displayed within the Sub Meta of the single post.', 'themeblvd' ),
						'id' 		=> 'share',
						'std' 		=> array(
							'item_1' => array(
								'icon'	=> 'facebook',
								'label'	=> 'Share this on Facebook'
							),
							'item_2' => array(
								'icon'	=> 'google',
								'label'	=> 'Share this on Google+'
							),
							'item_3' => array(
								'icon'	=> 'twitter',
								'label'	=> 'Share this on Twitter'
							),
							'item_4' => array(
								'icon'	=> 'email',
								'label'	=> 'Share this via Email'
							)
						),
						'type' 		=> 'share'
					),
					'share_style' => array(
						'name' 		=> __( 'Share Icon Style', 'themeblvd' ),
						'desc'		=> __( 'Select the color you\'d like applied to the share icons on the single post.', 'themeblvd' ),
						'id'		=> 'share_style',
						'std'		=> 'flat',
						'type' 		=> 'select',
						'options'	=> array(
							'flat'			=> __( 'Flat Color', 'themeblvd' ),
							'dark' 			=> __( 'Flat Dark', 'themeblvd' ),
							'grey' 			=> __( 'Flat Grey', 'themeblvd' ),
							'light' 		=> __( 'Flat Light', 'themeblvd' ),
							'color'			=> __( 'Color', 'themeblvd' )
						)
					),
					'single_thumbs' => array(
						'name' 		=> __( 'Featured Images', 'themeblvd' ),
						'desc' 		=> __( 'Choose how you want your featured images to show on the single post. This option can be useful if you\'ve set featured images strictly for use in a blog, post grid, portfolio, etc, but you don\'t want those fetured images to show on the single posts.', 'themeblvd' ),
						'id' 		=> 'single_thumbs',
						'std' 		=> 'full',
						'type' 		=> 'radio',
						'options' 	=> array(
							'full' 		=> __( 'Show featured images', 'themeblvd' ),
							'hide' 		=> __( 'Hide featured images', 'themeblvd' )
						)
					),
					'single_comments' => array(
						'name' 		=> __( 'Comments', 'themeblvd' ),
						'desc' 		=> __( 'This will hide the presence of comments on the single post.<br><br><em>Note: To hide comments link in meta information, close the comments on the post\'s discussion settings.</em>', 'themeblvd' ),
						'id' 		=> 'single_comments',
						'std' 		=> 'show',
						'type' 		=> 'radio',
						'options' 	=> array(
							'show'		=> __( 'Show comments', 'themeblvd' ),
							'hide' 		=> __( 'Hide comments', 'themeblvd' )
						)
					)
				) // End single options
			),

			// Section: Primary Posts Display
			'blog' => array(
				'name' => __( 'Blog Display', 'themeblvd' ),
				'desc' => __( 'These settings apply to your main theme index page, "posts page" that you\'ve selected under Settings > Reading, and <strong>all</strong> instances of the "Blog" page template. .', 'themeblvd' ),
				'options' => array(
					'blog_thumbs' => array(
						'name' 		=> __( 'Featured Images', 'themeblvd' ),
						'desc' 		=> __( 'Select the size of the blog\'s post thumbnail or whether you\'d like to hide them all together when posts are listed.', 'themeblvd' ),
						'id' 		=> 'blog_thumbs',
						'std' 		=> 'full',
						'type' 		=> 'radio',
						'options' 	=> array(
							'full' 		=> __( 'Show featured images', 'themeblvd' ),
							'hide' 		=> __( 'Hide featured images', 'themeblvd' )
						)
					),
					'blog_content' => array(
						'name' 		=> __( 'Excerpts of Full Content', 'themeblvd' ),
						'desc' 		=> __( 'Choose whether you want to show full content or post excerpts only.', 'themeblvd' ),
						'id' 		=> 'blog_content',
						'std' 		=> 'excerpt',
						'type' 		=> 'radio',
						'options' 	=> array(
							'content'	=> __( 'Show full content', 'themeblvd' ),
							'excerpt' 	=> __( 'Show excerpt only', 'themeblvd' )
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
				'desc' => __( 'These settings apply when you\'re viewing posts specific to a category, tag, date, author, etc.', 'themeblvd' ),
				'options' => array(
					'archive_title' => array(
						'name' 		=> __( 'Archive Page Titles', 'themeblvd' ),
						'desc' 		=> __( 'Choose whether or not you want the title to show on tag archives, category archives, date archives, author archives and search result pages.', 'themeblvd' ),
						'id' 		=> 'archive_title',
						'std' 		=> 'false',
						'type' 		=> 'radio',
						'options' 	=> array(
							'true'	=> __( 'Yes, show main title at the top of archive pages', 'themeblvd' ),
							'false' => __( 'No, hide the title', 'themeblvd' )
						)
					),
					'archive_thumbs' => array(
						'name' 		=> __( 'Featured Images', 'themeblvd' ),
						'desc' 		=> __( 'Choose whether or not you want featured images to show on tag archives, category archives, date archives, author archives and search result pages.', 'themeblvd' ),
						'id' 		=> 'archive_thumbs',
						'std' 		=> 'full',
						'type' 		=> 'radio',
						'options' 	=> array(
							'full' 		=> __( 'Show featured images', 'themeblvd' ),
							'hide' 		=> __( 'Hide featured images', 'themeblvd' )
						)
					),
					'archive_content' => array(
						'name' 		=> __( 'Excerpts of Full Content', 'themeblvd' ),
						'desc' 		=> __( 'Choose whether you want to show full content or post excerpts only.', 'themeblvd' ),
						'id' 		=> 'archive_content',
						'std' 		=> 'excerpt',
						'type' 		=> 'radio',
						'options' 	=> array(
							'content'	=> __( 'Show full content', 'themeblvd' ),
							'excerpt' 	=> __( 'Show excerpt only', 'themeblvd' )
						)
					),
					'archive_posts_per_page' => array(
						'name' 		=> __( 'Posts Per Page', 'themeblvd' ),
						'desc' 		=> __( 'Enter how many posts you\'d like shown per page in your archives.', 'themeblvd' ),
						'id' 		=> 'archive_posts_per_page',
						'std' 		=> '10',
						'type' 		=> 'text'
					)
				) // End archives options
			),

			// Section: Post Lists
			'list' => array(
				'name' => __( 'Post Lists', 'themeblvd' ),
				'desc' => __( 'These settings allow you to setup the default configuration for using post lists.', 'themeblvd' ),
				'options' => array(
					'list_thumbs' => array(
						'name' 		=> __( 'Featured Images', 'themeblvd' ),
						'desc' 		=> __( 'Choose whether or not you want featured images to show for each post.', 'themeblvd' ),
						'id' 		=> 'list_thumbs',
						'std' 		=> 'full',
						'type' 		=> 'radio',
						'options' => array(
							'full'		=> __( 'Show featured images', 'themeblvd_builder' ),
							'hide' 		=> __( 'Hide featured images', 'themeblvd_builder' )
						)
					),
					'list_meta' => array(
						'name' 		=> __( 'Meta Information', 'themeblvd' ),
						'desc' 		=> __( 'Select if you\'d like the meta information (like date posted, author, etc) to show for each post.', 'themeblvd' ),
						'id' 		=> 'list_meta',
						'std' 		=> 'show',
						'type' 		=> 'radio',
						'options' 	=> array(
							'show'		=> __( 'Show meta info', 'themeblvd' ),
							'hide' 		=> __( 'Hide meta info', 'themeblvd' )
						)
					),
					'list_sub_group_start' => array(
						'type' 		=> 'subgroup_start',
						'class'		=> 'show-hide-toggle'
					),
					'list_more' => array(
						'name' 		=> __( 'Read More', 'themeblvd' ),
						'desc' 		=> __( 'What would you like to show for each post to lead the reader to the full post?', 'themeblvd' ),
						'id' 		=> 'list_more',
						'std' 		=> 'text',
						'type' 		=> 'radio',
						'options' 	=> array(
							'text' 		=> __( 'Show text link', 'themeblvd' ),
							'button'	=> __( 'Show button', 'themeblvd' ),
							'none'		=> __( 'Show no button or text link', 'themeblvd' )
						),
						'class'		=> 'trigger'
					),
					'list_more_text' => array(
						'name' 		=> __( 'Read More Text', 'themeblvd' ),
						'desc' 		=> __( 'Enter the text you\'d like to use to lead the reader to the full post.', 'themeblvd' ),
						'id' 		=> 'list_more_text',
						'std' 		=> 'Read More <i class="fa fa-long-arrow-right"></i>',
						'type' 		=> 'text',
						'class'		=> 'hide receiver receiver-text receiver-button'
					),
					'list_sub_group_end' => array(
						'type' 		=> 'subgroup_end'
					)
				) // End post list options
			),

			// Section: Post Grids
			'grid' => array(
				'name' => __( 'Post Grids', 'themeblvd' ),
				'desc' => __( 'These settings allow you to setup the default configuration for using post grids.', 'themeblvd' ),
				'options' => array(
					'grid_thumbs' => array(
						'name' 		=> __( 'Featured Images', 'themeblvd' ),
						'desc' 		=> __( 'Choose whether or not you want featured images to show for each post.', 'themeblvd' ),
						'id' 		=> 'grid_thumbs',
						'std' 		=> 'full',
						'type' 		=> 'radio',
						'options' => array(
							'full'		=> __( 'Show featured images', 'themeblvd_builder' ),
							'hide' 		=> __( 'Hide featured images', 'themeblvd_builder' )
						)
					),
					'grid_meta' => array(
						'name' 		=> __( 'Meta Information', 'themeblvd' ),
						'desc' 		=> __( 'Select if you\'d like the meta information (like date posted, author, etc) to show for each post.', 'themeblvd' ),
						'id' 		=> 'grid_meta',
						'std' 		=> 'show',
						'type' 		=> 'radio',
						'options' 	=> array(
							'show'		=> __( 'Show meta info', 'themeblvd' ),
							'hide' 		=> __( 'Hide meta info', 'themeblvd' )
						)
					),
					'grid_excerpt' => array(
						'name' 		=> __( 'Excerpt', 'themeblvd' ),
						'desc' 		=> __( 'Select if you\'d like to show the excerpt or not for each post.', 'themeblvd' ),
						'id' 		=> 'grid_excerpt',
						'std' 		=> 'show',
						'type' 		=> 'radio',
						'options' 	=> array(
							'show'		=> __( 'Show excerpts', 'themeblvd' ),
							'hide' 		=> __( 'Hide excerpts', 'themeblvd' )
						)
					),
					'grid_sub_group_start' => array(
						'type' 		=> 'subgroup_start',
						'class'		=> 'show-hide-toggle'
					),
					'grid_more' => array(
						'name' 		=> __( 'Read More', 'themeblvd' ),
						'desc' 		=> __( 'What would you like to show for each post to lead the reader to the full post?', 'themeblvd' ),
						'id' 		=> 'grid_more',
						'std' 		=> 'button',
						'type' 		=> 'radio',
						'options' 	=> array(
							'text' 		=> __( 'Show text link', 'themeblvd' ),
							'button'	=> __( 'Show button', 'themeblvd' ),
							'none'		=> __( 'Show no button or text link', 'themeblvd' )
						),
						'class'		=> 'trigger'
					),
					'grid_more_text' => array(
						'name' 		=> __( 'Read More Text', 'themeblvd' ),
						'desc' 		=> __( 'Enter the text you\'d like to use to lead the reader to the full post.', 'themeblvd' ),
						'id' 		=> 'grid_more_text',
						'std' 		=> 'Read More',
						'type' 		=> 'text',
						'class'		=> 'hide receiver receiver-text receiver-button'
					),
					'grid_sub_group_end' => array(
						'type' 		=> 'subgroup_end'
					)
				) // End post grid options
			),

			// Section: Lightbox
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

		// Hidden options
		$this->formatted_options = array(
			'framework_version' => array(
				'id' 	=> 'framework_version',
				'type'	=> 'hidden'
			),
			'theme_version' => array(
				'id' 	=> 'theme_version',
				'type'	=> 'hidden'
			)
		);

		// Tab Level
		foreach ( $this->raw_options as $tab_id => $tab ) {

			// Insert Tab Heading
			$this->formatted_options['tab_'.$tab_id] = array(
				'id' 	=> $tab_id,
				'name' 	=> $tab['name'],
				'type' 	=> 'heading'
			);

			if ( isset( $tab['preset'] ) ) {
				$this->formatted_options['tab_'.$tab_id]['preset'] = $tab['preset'];
			}

			// Section Level
			if ( $tab['sections'] ) {
				foreach ( $tab['sections'] as $section_id => $section ) {

					// Start section
					$this->formatted_options['start_section_'.$section_id] = array(
						'name' => $section['name'],
						'type' => 'section_start'
					);

					if ( isset( $section['preset'] ) ) {
						$this->formatted_options['start_section_'.$section_id]['preset'] = $section['preset'];
					}

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

		// Adjust some option descriptions if post formats are enabled.
		if ( current_theme_supports('post-formats') ) {

			$options = apply_filters('themeblvd_apply_post_format_warning', array('single_thumbs', 'blog_thumbs', 'archive_thumbs'));

			foreach ( $options as $option_id ) {
				$this->formatted_options[$option_id]['desc'] .= '<br><br><em>Note: The result of this option may vary with posts that are not the "standard" post format.</em>';
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

			if ( ! is_admin() ) {

				// Because frontend, we need to add sanitiziation
				themeblvd_add_sanitization();

				// Construct array of default values pulled from
				// formatted options.
				$defaults = themeblvd_get_option_defaults( $this->formatted_options );
				$this->settings = $defaults;
				add_option( $this->get_option_id(), $defaults );

			}

		}

		// Verify data is saved properly
		if ( $this->settings ) {
			$this->settings = $this->verify( $this->settings );
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
			'closer'		=> true, // Needs to be false if option page has no tabs
			'export'		=> true,
			'import'		=> true
		);
		$this->args = apply_filters( 'themeblvd_theme_options_args', $this->args );
	}

	/**
	 * Verify theme options have been saved properly, and
	 * make any updates needed. This method will expanded
	 * over time as the framework changes. The idea here is
	 * that if we make any modification in how data is saved,
	 * we can handle it once here, and not worry aboutt it
	 * throughout the framework.
	 *
	 * @since 2.5.0
	 */
	public function verify( $settings ) {

		// Whether to update the options in the database,
		// which we, of course, want to avoid.
		$update = false;

		// Get framework version the options page was last
		// saved with. Before v2.5.0, this option will be blank.
		$version = '0';
		if ( ! empty( $settings['framework_version'] ) ) {
			$version = $settings['framework_version'];
		}

		// If options were last saved with current version of
		// the framework, we know we don't need to do anything.
		if ( version_compare( TB_FRAMEWORK_VERSION, $version, '==' ) ) {
			return $settings;
		} else {
			$update = true;
			$theme = wp_get_theme( get_template() );
			$settings['theme_version'] = $theme->get('Version');
			$settings['framework_version'] = TB_FRAMEWORK_VERSION;
		}

		// 2.5.0 -- The structure of the "columns" option type
		// has changed. Default framework option ID is "footer_setup"
		// which utilizes this option type.
		if ( ! empty( $settings['footer_setup'] ) && is_array( $settings['footer_setup'] ) ) {

			$val = $settings['footer_setup'];

			if ( ! empty( $val['width'] ) && ! empty( $val['num'] ) ) {

				$widths = $val['width'][$val['num']];
				$widths = explode('-', $widths);

				foreach ( $widths as $key => $value ) {
					$widths[$key] = themeblvd_grid_fraction($value);
				}

				$settings['footer_setup'] = implode('-', $widths);

			}
		}

		// 2.5.0 -- The structure of the "social_media" option type
		// has changed. No framework default option with this type,
		// but many themes use an option with id "social_media".
		if ( ! empty( $settings['social_media'] ) ) {

			// Has it been saved with framework 2.5+?
			if ( ! is_array( current( $settings['social_media'] ) ) ) {

				$i = 1;
				$val = array();

				foreach ( $settings['social_media'] as $icon => $url ) {
					$val['item_'.$i] = array(
						'icon'		=> $icon,
						'url'		=> $url,
						'label'		=> ucfirst($icon),
						'target'	=> '_blank'
					);
					$i++;
				}

				$settings['social_media'] = $val;
			}
		}

		// If update flag was set, make the DB call.
		if ( $update ) {
			update_option( $this->get_option_id(), $settings );
		}

		return get_option( $this->get_option_id() );
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

	/**
	 * Add set of preset option values user can populate
	 * portion of form with.
	 *
	 * @since 2.5.0
	 */
	public function add_presets( $args ) {

		$defaults = array(
			'id'		=> '',			// Unique ID for preset group (required)
			'tab' 		=> '',			// Tab to display at (required)
			'section'	=> '',			// Section within that tab (optional)
			'options'	=> array(
				'set_1' => 'img.jpg',
				'set_2'	=> 'img2.jpg'
			),
			'sets'		=> array(
				'set_1' => array(
					'foo' 	=> 'bar',
					'foo2' 	=> 'bar2'
				),
				'set_2' => array(
					'foo' 	=> 'bar3',
					'foo2' 	=> 'bar4'
				)
			),
			'icon_width'	=> ''			// Display width for image options, if empty, assumed using Text
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
		$preset['id'] = 'preset_'.$preset['id'];
		$preset['level'] = $level;
		unset( $preset['tab'], $preset['section'] );

		if ( $level == 2 ) {
			$this->raw_options[$args['tab']]['sections'][$args['section']]['preset'] = $preset;
		} else if ( $level == 1 ) {
			$this->raw_options[$args['tab']]['preset'] = $preset;
		}

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