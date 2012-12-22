<?php
/**
 * Setup all core theme options of framework, which can 
 * then be altered at the theme level.
 *
 * @uses $_themeblvd_options 
 * @since 2.1.0
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
 * Configuration
 *	- Analytics
 *		- analytics
 */

if( ! function_exists( 'themeblvd_get_core_options' ) ) {
	function themeblvd_get_core_options() {
		
		/*-------------------------------------------------------*/
		/* Setup Helper Items
		/*-------------------------------------------------------*/
		
		// If using image radio buttons, define a directory path
		$imagepath =  get_template_directory_uri() . '/framework/admin/assets/images/';
	
		// Generate sidebar layout options
		$sidebar_layouts = array();
		if( is_admin() ) {
			$layouts = themeblvd_sidebar_layouts();
			foreach( $layouts as $layout )
				$sidebar_layouts[$layout['id']] = $imagepath.'layout-'.$layout['id'].'.png';
		}
		
		// Generate sliders options
		$custom_sliders = array();
		if( is_admin() ) {
			$sliders = get_posts('post_type=tb_slider&numberposts=-1');
			if( ! empty( $sliders ) ) {
				foreach( $sliders as $slider )
					$custom_sliders[$slider->post_name] = $slider->post_title;
			} else {
				$custom_sliders['null'] = __( 'You haven\'t created any custom sliders yet.', 'themeblvd' );
			}
		}		
		
		// Pull all the categories into an array
		$options_categories = array();  
		if( is_admin() ) {
			$options_categories_obj = get_categories();
			foreach ($options_categories_obj as $category) {
		    	$options_categories[$category->cat_ID] = $category->cat_name;
			}
		}
	
		/*-------------------------------------------------------*/
		/* Layout
		/*-------------------------------------------------------*/
		
		$layout_options = array(
			// Section: Header
			'header' => array(
				'name' => __( 'Header', 'themeblvd' ),
				'options' => array(	
					'logo' => array( 
						'name' 		=> __( 'Logo', 'themeblvd' ),
						'desc' 		=> __( 'Configure the primary branding logo for the header of your site.', 'themeblvd' ),
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
						'options' 	=> $sidebar_layouts
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
						'std' 		=> '(c) '.date('Y').' '.get_bloginfo('site_title').' - Web Design by <a href="http://www.jasonbobich.com" target="_blank">Jason Bobich</a>',
						'type' 		=> 'text'
					)	
				) // End footer options
			)
		);
		
		/*-------------------------------------------------------*/
		/* Content
		/*-------------------------------------------------------*/
		
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
						'desc' 		=> __( 'Choose whether you want to show full content or post excerpts only.<br><br><em>Note: Because this theme uses post formats, this option will not apply to all post formats.</em>', 'themeblvd' ),
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
			)
		);
		
		/*-------------------------------------------------------*/
		/* Configuration
		/*-------------------------------------------------------*/
		
		$config_options = array(
			// Section: Analytics
			'analytics' => array(
				'name' => __( 'Analytics', 'themeblvd' ),
				'options' => array(		
					'analytics' => array( 
						'name' 		=> __( 'Analytics Code', 'themeblvd' ),	
						'desc' 		=> __( 'Paste in the code provided by your Analytics service.<br><br>If you\'re looking for a free analytics service, definitely check out <a href="http://www.google.com/analytics/">Google Analytics</a>.', 'themeblvd' ),
						'id' 		=> 'analytics',
						'type' 		=> 'textarea'
					)
				) // End analytics options
			)
		);
	
		/*-------------------------------------------------------*/
		/* Finalize
		/*-------------------------------------------------------*/
		
		$options = array(
			'layout' 	=> array( 
				'name' 		=> __( 'Layout', 'themeblvd' ),
				'sections' 	=> $layout_options
			),
			'content' 	=> array( 
				'name' 		=> __( 'Content', 'themeblvd' ),
				'sections' 	=> $content_options
			),
			'config' 	=> array( 
				'name' 		=> __( 'Configuration', 'themeblvd' ),
				'sections' 	=> $config_options
			)
		);
		return apply_filters( 'themeblvd_core_options', $options );
	}
}

/**
 * This sets up the global theme options after 
 * the theme level has had a chance to make 
 * modifications, as well as formatting properly 
 * to go into the Options Framework. It gets hooked 
 * in at  after_theme_setup but w/priority 1000.
 *
 * @uses $_themeblvd_options
 * @uses $_themeblvd_core_options 
 * @since 2.1.0
 */

if( ! function_exists( 'themeblvd_format_options' ) ) {
	function themeblvd_format_options() {
		global $_themeblvd_core_options;
		global $_themeblvd_options;
		$_themeblvd_options = array();
		// Tab Level
		foreach( $_themeblvd_core_options as $tab_id => $tab ) {	
			// Insert Tab Heading
			$_themeblvd_options['tab_'.$tab_id] = array(
				'id' 	=> $tab_id,
				'name' 	=> $tab['name'],
				'type' 	=> 'heading'
			);
			// Section Level
			if( $tab['sections'] ) {
				foreach( $tab['sections'] as $section_id => $section ) {
					// Start section
					$_themeblvd_options['start_section_'.$section_id] = array( 
						'name' => $section['name'],		
						'type' => 'section_start'
					);
					if( isset( $section['desc'] ) ) {
						$_themeblvd_options['start_section_'.$section_id]['desc'] = $section['desc'];
					}
					// Options Level
					if( $section['options'] ) {
						foreach( $section['options'] as $option_id => $option ) {
							$_themeblvd_options[$option_id] = $option;
						}
					}
					// End section
					$_themeblvd_options['end_section_'.$section_id] = array( 	
						'type' => 'section_end'
					);
				}
			}
		}
		// Apply filters
		$_themeblvd_options = apply_filters( 'themeblvd_formatted_options', $_themeblvd_options );
	}
}

/**
 * This retrieves the theme options based on the 
 * global $_themeblvd_options array created in 
 * themeblvd_format_options()
 *
 * @uses $_themeblvd_options 
 * @since 2.1.0
 */

if( ! function_exists( 'themeblvd_get_formatted_options' ) ) {
	function themeblvd_get_formatted_options() {
		global $_themeblvd_options;
		return apply_filters( 'themeblvd_formatted_options', $_themeblvd_options );
	}
}

/**
 * Get theme option
 *
 * @since 2.0.0
 * @uses $_themeblvd_theme_settings
 *
 * @param string $primary The primary ID of the option
 * @param string $secondary This would be the option ID only if we're grabbing it from a multi-dimensional array
 */

if( ! function_exists( 'themeblvd_get_option' ) ) {
	function themeblvd_get_option( $primary, $seconday = null ) {
		global $_themeblvd_theme_settings; // We pull from a global array, so we're not using WordPress's get_option every single time.
		$options = $_themeblvd_theme_settings;
		$option = null;
		if( isset( $options[$primary] ) ) {
			if( $seconday ) {
				if( is_array( $options[$primary] ) && isset( $options[$primary][$seconday] ) )
					$option = $options[$primary][$seconday];
			} else {
				$option = $options[$primary];
			}
		}
		return $option;
	}
}

/**
 * Add theme option tab.
 *
 * @since 2.1.0
 *
 * @param string $tab_id ID of tab to add
 * @param string $tab_name Name of the tab to add
 * @param boolean $top Whether the tab should be added to the start or not
 */

if( ! function_exists( 'themeblvd_add_option_tab' ) ) {
	function themeblvd_add_option_tab( $tab_id, $tab_name, $top = false ) {
		global $_themeblvd_core_options;
		
		if( $top ) {
			// Add tab to the top of array 
			$new_options = array();
			$new_options[$tab_id] = array( 
				'name' 		=> $tab_name,
				'sections' 	=> array()
			);
			$_themeblvd_core_options = array_merge( $new_options, $_themeblvd_core_options );			
		} else {
			// Add tab to the end of global array
			$_themeblvd_core_options[$tab_id] = array( 
				'name' 		=> $tab_name,
				'sections' 	=> array()
			);
		}
	}
}

/**
 * Remove theme option tab.
 *
 * @since 2.1.0
 *
 * @param string $tab_id ID of tab to add
 */

if( ! function_exists( 'themeblvd_remove_option_tab' ) ) {
	function themeblvd_remove_option_tab( $tab_id ) {
		global $_themeblvd_core_options;
		unset( $_themeblvd_core_options[$tab_id] );
	}
}

/**
 * Add theme option section.
 *
 * @since 2.1.0
 *
 * @param string $tab_id ID of tab section will be located in
 * @param string $section_id ID of new section
 * @param string $section_name Name of new section
 * @param string $section_desc Description of new section
 * @param array $options Options array formatted for Options Framework
 * @param boolean $top Whether the option should be added to the top or not
 */

if( ! function_exists( 'themeblvd_add_option_section' ) ) {
	function themeblvd_add_option_section( $tab_id, $section_id, $section_name, $section_desc = null, $options = null, $top = false ) {
		global $_themeblvd_core_options;
		
		// Make sure tab exists
		if( ! isset( $_themeblvd_core_options[$tab_id] ) )
			return;
		
		// Format options array
		$new_options = array();
		if( $options ) {
			foreach( $options as $option ) {
				if( isset( $option['id'] ) ) {
					$new_options[$option['id']] = $option;
				}
			}
		}
		
		// Does the options section already exist?
		if( isset( $_themeblvd_core_options[$tab_id]['sections'][$section_id] ) ) {
			$_themeblvd_core_options[$tab_id]['sections'][$section_id]['options'] = array_merge( $_themeblvd_core_options[$tab_id]['sections'][$section_id]['options'], $new_options );
			return;
		}
		
		// Add new section to top or bottom
		if( $top ) {
			$previous_sections = $_themeblvd_core_options[$tab_id]['sections'];
			$_themeblvd_core_options[$tab_id]['sections'] = array(
				$section_id => array(
					'name' => $section_name,
					'desc' => $section_desc,
					'options' => $new_options
				)
			);
			$_themeblvd_core_options[$tab_id]['sections'] = array_merge( $_themeblvd_core_options[$tab_id]['sections'], $previous_sections );
		} else {
			$_themeblvd_core_options[$tab_id]['sections'][$section_id] = array(
				'name' => $section_name,
				'desc' => $section_desc,
				'options' => $new_options
			);
		}
	}
}

/**
 * Remove theme option section.
 *
 * @since 2.1.0
 *
 * @param string $tab_id ID of tab that section to remove belongs to
 * @param string $section_id ID of section to remove
 */

if( ! function_exists( 'themeblvd_remove_option_section' ) ) {
	function themeblvd_remove_option_section( $tab_id, $section_id ) {
		global $_themeblvd_core_options;
		unset( $_themeblvd_core_options[$tab_id]['sections'][$section_id] );
	}
}

/**
 * Add theme option.
 *
 * @since 2.1.0
 *
 * @param string $tab_id ID of tab to add option to
 * @param string $section_id ID of section to add to
 * @param array $option attributes for option, formatted for Options Framework
 * @param string $option_id ID of of your option, note that this id must also be present in $option array
 */

if( ! function_exists( 'themeblvd_add_option' ) ) {
	function themeblvd_add_option( $tab_id, $section_id, $option_id, $option ) {
		global $_themeblvd_core_options;
		if( isset( $_themeblvd_core_options[$tab_id] ) )
			if( isset( $_themeblvd_core_options[$tab_id]['sections'][$section_id] ) )
				$_themeblvd_core_options[$tab_id]['sections'][$section_id]['options'][$option_id] = $option;
	}
}

/**
 * Remove theme option.
 *
 * @since 2.1.0
 *
 * @param string $tab_id ID of tab to add option to
 * @param string $section_id ID of section to add to
 * @param string $option_id ID of of your option
 */
 
if( ! function_exists( 'themeblvd_remove_option' ) ) {
	function themeblvd_remove_option( $tab_id, $section_id, $option_id ) {
		global $_themeblvd_core_options;
		if( isset( $_themeblvd_core_options[$tab_id] ) ) {
			if( isset( $_themeblvd_core_options[$tab_id]['sections'][$section_id] ) ) {
				if( isset( $_themeblvd_core_options[$tab_id]['sections'][$section_id]['options'][$option_id] ) ) {
					// If option has element's ID as key, we can find and 
					// remove it faster.
					unset( $_themeblvd_core_options[$tab_id]['sections'][$section_id]['options'][$option_id] );
				} else {
					// If this is an option added by a child theme or plugin, 
					// and it doesn't have the element's ID as the key, this 
					// is how we can remove it.
					foreach( $_themeblvd_core_options[$tab_id]['sections'][$section_id]['options'] as $key => $value ) {
						if( $value['id'] == $option_id ) {
							unset( $_themeblvd_core_options[$tab_id]['sections'][$section_id]['options'][$key] );
						}
					}
				}
			}
		}
	}
}

/**
 * Remove theme option.
 *
 * @since 2.1.0
 *
 * @param string $tab_id ID of tab to add option to
 * @param string $section_id ID of section to add to
 * @param string $option_id ID of of your option
 * @param string $att Attribute of option to change
 * @param string $value New value for attribute
 */
 
if( ! function_exists( 'themeblvd_edit_option' ) ) {
	function themeblvd_edit_option( $tab_id, $section_id, $option_id, $att, $value ) {
		global $_themeblvd_core_options;
		if( isset( $_themeblvd_core_options[$tab_id] ) )
			if( isset( $_themeblvd_core_options[$tab_id]['sections'][$section_id] ) )
				if( isset( $_themeblvd_core_options[$tab_id]['sections'][$section_id]['options'][$option_id] ) )
					$_themeblvd_core_options[$tab_id]['sections'][$section_id]['options'][$option_id][$att] = $value;
	}
}

/**
 * For each theme, we use a unique identifier to store 
 * the theme's options in the database based on the current 
 * name of the theme. This is can be filtered with 
 * "themeblvd_option_id".
 *
 * @since 2.1.0
 */

if( ! function_exists( 'themeblvd_get_option_name' ) ) {
	function themeblvd_get_option_name() {
	
		// This gets the theme name from the stylesheet (lowercase and without spaces)
		$theme_data = wp_get_theme( get_stylesheet() );
		$themename = preg_replace('/\W/', '', strtolower( $theme_data->get('Name') ) );
		
		// This is what ID the options will be saved under in the database. 
		// By default, it's generated from the current installed theme. 
		// So that means if you activate a child theme, you'll then need 
		// re-configure theme options.
		return apply_filters( 'themeblvd_option_id', $themename );
	}
}

/**
 * Get default values for set of options
 *
 * @since 2.2.0
 *
 * @param array $options Options formatted for internal options framework
 * @return array $defaults Default values from options
 */

if( ! function_exists( 'themeblvd_get_option_defaults' ) ) {
	function themeblvd_get_option_defaults( $options ) {
		$defaults = array();
		foreach( (array) $options as $option ) {
			// Skip if any vital items are not set.
			if( ! isset( $option['id'] ) )
				continue;
			if( ! isset( $option['std'] ) )
				continue;
			if( ! isset( $option['type'] ) )
				continue;
			// Continue with adding the option in.
			if ( has_filter( 'themeblvd_sanitize_' . $option['type'] ) )
				$defaults[$option['id']] = apply_filters( 'themeblvd_sanitize_' . $option['type'], $option['std'], $option );
		}
		return $defaults;
	}
}