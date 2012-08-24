<?php
/**
 * Setup all core theme options of framework, which can 
 * then be altered at the theme level.
 *
 * @uses $_themeblvd_core_elements 
 * @since 2.1.0
 *
 * - columns
 * - content
 * - divider
 * - headline
 * - post_grid_paginated
 * - post_grid
 * - post_grid_slider
 * - post_list_paginated
 * - post_list
 * - post_list_slider
 * - slider
 * - slogan
 * - tabs
 * - tweet
 */

if( ! function_exists( 'themeblvd_get_core_elements' ) ) {
	function themeblvd_get_core_elements() {
		
		global $_themeblvd_core_elements;

		/*------------------------------------------------------*/
		/* Initial Setup
		/*------------------------------------------------------*/
		
		// Setup array for pages select
		$pages_select = themeblvd_get_select( 'pages' );
		
		// Setup array for custom sliders select
		$sliders_select = themeblvd_get_select( 'sliders' );
		
		// Setup array for floating sidebars
		$sidebars = themeblvd_get_select( 'sidebars' );
		if( ! $sidebars ) 
			$sidebars['null'] = __( 'You haven\'t created any floating widget areas yet.', 'themeblvd' );
		if( ! defined( 'TB_SIDEBARS_PLUGIN_VERSION' ) )
			$sidebars['null'] = __( 'You need to have the Theme Blvd Widget Areas plugin installed for this feature.', 'themeblvd' );
			
		// Setup array for categories select
		$categories_select = themeblvd_get_select( 'categories' );
		
		// Setup array for categories group of checkboxes
		$categories_multicheck = $categories_select;
		unset( $categories_multicheck['null'] );
		
		/*------------------------------------------------------*/
		/* Element Options
		/*------------------------------------------------------*/
		
		// Columns
		$element_columns = array(
		   	array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'columns'
		    ),
		   	array(
				'id' 		=> 'setup',
				'name'		=> __( 'Setup Columns', 'themeblvd' ),
				'desc'		=> __( 'Choose the number of columns along with the corresponding width configurations.', 'themeblvd' ),
				'type'		=> 'columns',
				'options'	=> 'element'
			),
			array(
				'id' 		=> 'col_1',
				'name'		=> __( 'Column #1', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the first column.', 'themeblvd' ),
				'type'		=> 'content',
				'class'		=> 'col_1',
				'options'	=> array( 'widget', 'page', 'raw' )
			),
			array(
				'id' 		=> 'col_2',
				'name'		=> __( 'Column #2', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the second column.', 'themeblvd' ),
				'type'		=> 'content',
				'class'		=> 'col_2',
				'options'	=> array( 'widget', 'page', 'raw' )
			),
			array(
				'id' 		=> 'col_3',
				'name'		=> __( 'Column #3', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the third column.', 'themeblvd' ),
				'type'		=> 'content',
				'class'		=> 'col_3',
				'options'	=> array( 'widget', 'page', 'raw' )
			),
			array(
				'id' 		=> 'col_4',
				'name'		=> __( 'Column #4', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the fourth column.', 'themeblvd' ),
				'type'		=> 'content',
				'class'		=> 'col_4',
				'options'	=> array( 'widget', 'page', 'raw' )
			),
			array(
				'id' 		=> 'col_5',
				'name'		=> __( 'Column #5', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the fifth column.', 'themeblvd' ),
				'type'		=> 'content',
				'class'		=> 'col_5',
				'options'	=> array( 'widget', 'page', 'raw' )
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', 'themeblvd' ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This is optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', 'themeblvd' ),
				'type'		=> 'multicheck',
				'options'	=> array(
					'hide_on_standard' 	=> 'Hide on Standard Resolutions',
					'hide_on_tablet' 	=> 'Hide on Tablets',
					'hide_on_mobile' 	=> 'Hide on Mobile Devices',
				)
			)
		);
		
		// Content
		$element_content = array(
		    array(
		    	'type'		=> 'subgroup_start'
		    ),
		    array(
		    	'id' 		=> 'source',
				'name'		=> __( 'Content Source', 'themeblvd' ),
				'desc'		=> __( 'Choose where you\'d like to have content feed from. The content can either be from the current page you\'re applying this layout to or an external page.', 'themeblvd' ),
				'type'		=> 'select',
				'options'	=> array(
					'current' 		=> __( 'Content from current page', 'themeblvd' ),
			        'external' 		=> __( 'Content from external page', 'themeblvd' ),
			        'raw'			=> __( 'Raw content', 'themeblvd' ),
			        'widget_area'	=> __( 'Floating Widget Area', 'themeblvd' )
				),
				'class'		=> 'custom-content-types'
			),
			array(
		    	'id' 		=> 'page_id',
				'name'		=> __( 'External Page', 'themeblvd' ),
				'desc'		=> __( 'Choose the external page you\'d like to pull content from.', 'themeblvd' ),
				'type'		=> 'select',
				'options'	=> $pages_select,
				'class'		=> 'hide page-content'
			),
			array(
		    	'id' 		=> 'raw_content',
				'name'		=> __( 'Raw Content', 'themeblvd' ),
				'desc'		=> __( 'Enter in the content you\'d like to show. You may use basic HTML, and most shortcodes.', 'themeblvd' ),
				'type'		=> 'textarea',
				'class'		=> 'hide raw-content'
			),
			array(
		    	'id' 		=> 'raw_format',
				'name'		=> __( 'Raw Content Formatting', 'themeblvd' ),
				'desc'		=> __( 'Apply WordPress automatic formatting.', 'themeblvd' ),
				'type'		=> 'checkbox',
				'std'		=> '1',
				'class'		=> 'hide raw-content'
			),
			array(
		    	'id' 		=> 'widget_area',
				'name'		=> __( 'Floating Widget Area', 'themeblvd' ),
				'desc'		=> __( 'Select from your floating custom widget areas. In order for a custom widget area to be "floating" you must have it configured this way in the Widget Area manager.', 'themeblvd' ),
				'type'		=> 'select',
				'options'	=> $sidebars,
				'class'		=> 'hide widget_area-content'
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', 'themeblvd' ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This is optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', 'themeblvd' ),
				'type'		=> 'multicheck',
				'options'	=> array(
					'hide_on_standard' 	=> 'Hide on Standard Resolutions',
					'hide_on_tablet' 	=> 'Hide on Tablets',
					'hide_on_mobile' 	=> 'Hide on Mobile Devices',
				)
			)
		);
		
		// Divider
		$element_divider = array(
		    array(
		    	'id' 		=> 'type',
				'name'		=> __( 'Divider Type', 'themeblvd' ),
				'desc'		=> __( 'Select which style of divider you\'d like to use here.', 'themeblvd' ),
				'type'		=> 'select',
				'options'		=> array(
			        'dashed' 	=> __( 'Dashed Line', 'themeblvd' ),
			        'shadow' 	=> __( 'Shadow Line', 'themeblvd' ),
					'solid' 	=> __( 'Solid Line', 'themeblvd' )
				) 
			),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', 'themeblvd' ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This is optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', 'themeblvd' ),
				'type'		=> 'multicheck',
				'options'	=> array(
					'hide_on_standard' 	=> 'Hide on Standard Resolutions',
					'hide_on_tablet' 	=> 'Hide on Tablets',
					'hide_on_mobile' 	=> 'Hide on Mobile Devices',
				)
			)
		);
			
		// Headline
		$element_headline = array(
			array(
				'id' 		=> 'text',
				'name'		=> __( 'Headline Text', 'themeblvd' ),
				'desc'		=> __( 'Enter in the text you\'d like to use for your headline. It is better if you use plain text here and not try and use HTML tags.', 'themeblvd' ),
				'type'		=> 'textarea',
			),
			array(
		    	'id' 		=> 'tagline',
				'name'		=> __( 'Tagline', 'themeblvd' ),
				'desc'		=> __( 'Enter any text you\'d like to display below the headline as a tagline. Feel free to leave this blank. It is better if you use plain text here and not try and use HTML tags.', 'themeblvd' ),
				'type'		=> 'textarea'
			),
		    array(
		    	'id' 		=> 'tag',
				'name'		=> __( 'Headline Tag', 'themeblvd' ),
				'desc'		=> __( 'Select the type of header tag you\'d like to wrap this headline.', 'themeblvd' ),
				'type'		=> 'select',
				'options'	=> array(
					'h1' => __( '&lt;h1&gt;Your Headline&lt;/h1&gt;', 'themeblvd' ),
					'h2' => __( '&lt;h2&gt;Your Headline&lt;/h2&gt;', 'themeblvd' ),
					'h3' => __( '&lt;h3&gt;Your Headline&lt;/h3&gt;', 'themeblvd' ),
					'h4' => __( '&lt;h4&gt;Your Headline&lt;/h4&gt;', 'themeblvd' ),
					'h5' => __( '&lt;h5&gt;Your Headline&lt;/h5&gt;', 'themeblvd' ),
					'h6' => __( '&lt;h6&gt;Your Headline&lt;/h6&gt;', 'themeblvd' )
				)   
			),
			array(
				'id' 		=> 'align',
				'name'		=> __( 'Headline Alignment', 'themeblvd' ),
				'desc'		=> __( 'Select how you\'d like the text in this title to align.', 'themeblvd' ),
				'type'		=> 'select',
				'options'		=> array(
			        'left' 		=> __( 'Align Left', 'themeblvd' ),
			        'center' 	=> __( 'Center', 'themeblvd' ),
					'right' 	=> __( 'Align Right', 'themeblvd' )
				) 
			),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', 'themeblvd' ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This is optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', 'themeblvd' ),
				'type'		=> 'multicheck',
				'options'	=> array(
					'hide_on_standard' 	=> 'Hide on Standard Resolutions',
					'hide_on_tablet' 	=> 'Hide on Tablets',
					'hide_on_mobile' 	=> 'Hide on Mobile Devices',
				)
			)
		);
		
		// Post Grid (lead)
		$element_post_grid = array(
		    array(
		    	'id' 		=> 'categories',
				'name'		=> __( 'Categories', 'themeblvd' ),
				'desc'		=> __( 'Select the categories you\'d like to pull posts from. Note that selecting "All Categories" will override any other selections.', 'themeblvd' ),
				'std'		=> array( 'all' => 1 ),
				'type'		=> 'multicheck',
				'options'	=> $categories_multicheck
			),
			array(
		    	'id' 		=> 'columns',
				'name'		=> __( 'Columns', 'themeblvd' ),
				'desc'		=> __( 'Select how many posts per row you\'d like displayed.', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> '3',
				'options'	=> array(
			        '2' 	=> __( '2 Columns', 'themeblvd' ),
			        '3' 	=> __( '3 Columns', 'themeblvd' ),
			        '4' 	=> __( '4 Columns', 'themeblvd' ),
			        '5' 	=> __( '5 Columns', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'rows',
				'name'		=> __( 'Rows', 'themeblvd' ),
				'desc'		=> __( 'Enter in the number of rows you\'d like to show. The number you enter here will be multiplied by the amount of columns you selected in the previous option to figure out how many posts should be showed. You can leave this option blank if you\'d like to show all posts from the categories you\'ve selected.', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> '3'
			),
			array(
		    	'id' 		=> 'orderby',
				'name'		=> __( 'Order By', 'themeblvd' ),
				'desc'		=> __( 'Select what attribute you\'d like the posts ordered by.', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> '3',
				'options'	=> array(
			        'post_date' 	=> __( 'Publish Date', 'themeblvd' ),
			        'title' 		=> __( 'Post Title', 'themeblvd' ),
			        'comment_count' => __( 'Number of Comments', 'themeblvd' ),
			        'rand' 			=> __( 'Random', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'order',
				'name'		=> __( 'Order', 'themeblvd' ),
				'desc'		=> __( 'Select the order in which you\'d like the posts displayed based on the previous orderby parameter.<br><br><em>Note that a traditional WordPress setup would have posts ordered by <strong>Publish Date</strong> and be ordered <strong>Descending</strong>.</em>', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> 'DESC',
				'options'	=> array(
			        'DESC' 	=> __( 'Descending (highest to lowest)', 'themeblvd' ),
			        'ASC' 	=> __( 'Ascending (lowest to highest)', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'offset',
				'name'		=> __( 'Offset', 'themeblvd' ),
				'desc'		=> __( 'Enter the number of posts you\'d like to offset the query by. In most cases, you will just leave this at <em>0</em>. Utilizing this option could be useful, for example, if you wanted to have the first post in an element above this one, and then you could offset this set by <em>1</em> so the posts start after that post in the previous element. If that makes no sense, just ignore this option and leave it at <em>0</em>!', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> '0'
			),
			array(
		    	'id' 		=> 'query',
				'name'		=> __( 'Custom Query String (optional)', 'themeblvd' ),
				'desc'		=> __( 'Enter in a <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Parameters">custom query string</a>. This will override any other query-related options.<br><br>Ex: tag=cooking<br>Ex: post_type=XYZ&numberposts=10<br><br><em>Note: Putting anything in this option will cause the following options to not have any effect: Categories, Orderby, Order, and Offset</em>', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
		    	'id' 		=> 'crop',
				'name'		=> __( 'Custom Image Crop Size (optional)', 'themeblvd' ),
				'desc'		=> __( 'Enter in a custom image crop size. Always leave this blank unless you know what you\'re doing here; when left blank, the theme will generate this crop size for you depending on the amount of columns in your post grid.', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'show-hide'
		    ),
			array(
		    	'id' 		=> 'link',
				'name'		=> __( 'Link', 'themeblvd' ),
				'desc'		=> __( 'Show link after posts to direct visitors somewhere?', 'themeblvd' ),
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			array(
				'id' 		=> 'link_text',
				'name'		=> __( 'Link Text', 'themeblvd' ),
				'desc'		=> __( 'Enter the text for the link.', 'themeblvd' ),
				'std'		=> 'View All Posts',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'link_url',
				'name'		=> __( 'Link URL', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL where you want this link to go to.', 'themeblvd' ),
				'std'		=> 'http://www.your-site.com/your-blog-page',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'link_target',
				'name'		=> __( 'Link Target', 'themeblvd' ),
				'desc'		=> __( 'Select how you want the link to open.', 'themeblvd' ),
				'type'		=> 'select',
				'class'		=> 'hide receiver',
				'options'		=> array(
			        '_self' 	=> __( 'Same Window', 'themeblvd' ),
			        '_blank' 	=> __( 'New Window', 'themeblvd' )
				) 
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', 'themeblvd' ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This is optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', 'themeblvd' ),
				'type'		=> 'multicheck',
				'options'	=> array(
					'hide_on_standard' 	=> 'Hide on Standard Resolutions',
					'hide_on_tablet' 	=> 'Hide on Tablets',
					'hide_on_mobile' 	=> 'Hide on Mobile Devices',
				)
			)
		);
		
		// Post Grid (paginated)
		$element_post_grid_paginated = array(
			array(
		    	'id' 		=> 'categories',
				'name'		=> __( 'Categories', 'themeblvd' ),
				'desc'		=> __( 'Select the categories you\'d like to pull posts from. Note that selecting "All Categories" will override any other selections.', 'themeblvd' ),
				'std'		=> array( 'all' => 1 ),
				'type'		=> 'multicheck',
				'options'	=> $categories_multicheck
			),
			array(
		    	'id' 		=> 'columns',
				'name'		=> __( 'Columns', 'themeblvd' ),
				'desc'		=> __( 'Select how many posts per row you\'d like displayed.', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> '3',
				'options'	=> array(
			        '2' 	=> __( '2 Columns', 'themeblvd' ),
			        '3' 	=> __( '3 Columns', 'themeblvd' ),
			        '4' 	=> __( '4 Columns', 'themeblvd' ),
			        '5' 	=> __( '5 Columns', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'rows',
				'name'		=> __( 'Rows per page', 'themeblvd' ),
				'desc'		=> __( 'Enter in the number of rows <strong>per page</strong> you\'d like to show. The number you enter here will be multiplied by the amount of columns you selected in the previous option to figure out how many posts should be showed on each page. You can leave this option blank if you\'d like to show all posts from the categories you\'ve selected on a single page.', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> '3'
			),
			array(
		    	'id' 		=> 'orderby',
				'name'		=> __( 'Order By', 'themeblvd' ),
				'desc'		=> __( 'Select what attribute you\'d like the posts ordered by.', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> '3',
				'options'	=> array(
			        'post_date' 	=> __( 'Publish Date', 'themeblvd' ),
			        'title' 		=> __( 'Post Title', 'themeblvd' ),
			        'comment_count' => __( 'Number of Comments', 'themeblvd' ),
			        'rand' 			=> __( 'Random', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'order',
				'name'		=> __( 'Order', 'themeblvd' ),
				'desc'		=> __( 'Select the order in which you\'d like the posts displayed based on the previous orderby parameter.<br><br><em>Note that a traditional WordPress setup would have posts ordered by <strong>Publish Date</strong> and be ordered <strong>Descending</strong>.</em>', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> 'DESC',
				'options'	=> array(
			        'DESC' 	=> __( 'Descending (highest to lowest)', 'themeblvd' ),
			        'ASC' 	=> __( 'Ascending (lowest to highest)', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'query',
				'name'		=> __( 'Custom Query String (optional)', 'themeblvd' ),
				'desc'		=> __( 'Enter in a <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Parameters">custom query string</a>. This will override any other query-related options.<br><br>Ex: tag=cooking<br>Ex: post_type=XYZ<br><br><em>Note: Putting anything in this option will cause the following options to not have any effect: Categories, Orderby, and Order. Additionally, you can\'t use "posts_per_page" as this is generated in a grid based on the rows and columns.</em>', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
		    	'id' 		=> 'crop',
				'name'		=> __( 'Custom Image Crop Size (optional)', 'themeblvd' ),
				'desc'		=> __( 'Enter in a custom image crop size. Always leave this blank unless you know what you\'re doing here; when left blank, the theme will generate this crop size for you depending on the amount of columns in your post grid.', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> ''
			),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', 'themeblvd' ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This is optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', 'themeblvd' ),
				'type'		=> 'multicheck',
				'options'	=> array(
					'hide_on_standard' 	=> 'Hide on Standard Resolutions',
					'hide_on_tablet' 	=> 'Hide on Tablets',
					'hide_on_mobile' 	=> 'Hide on Mobile Devices',
				)
			)	
		);
		
		// Post Grid Slider
		$element_post_grid_slider = array( 
			array(
		    	'id' 		=> 'fx',
				'name'		=> __( 'Transition Effect', 'themeblvd' ),
				'desc'		=> __( 'Select the effect you\'d like used to transition from one slide to the next.', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> 'slide',
				'options'	=> array(
			        'fade' 	=> 'Fade',
					'slide'	=> 'Slide'
				)
			),
			array(
		    	'id' 		=> 'timeout',
				'name'		=> __( 'Speed', 'themeblvd' ),
				'desc'		=> __( 'Enter the number of seconds you\'d like in between trasitions. You may use <em>0</em> to disable the slider from auto advancing.', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> '0'
			),
			array(
				'id'		=> 'nav_standard',
				'name'		=> __( 'Show standard slideshow navigation?', 'themeblvd' ),
				'desc'		=> __( 'The standard navigation are the little dots that appear below the slider.' , 'themeblvd' ),
				'std'		=> '1',
				'type'		=> 'select',
				'options'		=> array(
		            '1'	=> __( 'Yes, show navigation.', 'themeblvd' ),
		            '0'	=> __( 'No, don\'t show it.', 'themeblvd' )
				)
			),
			array(
				'id'		=> 'nav_arrows',
				'name'		=> __( 'Show next/prev slideshow arrows?', 'themeblvd' ),
				'desc'		=> __( 'These arrows allow the user to navigation from one slide to the next.' , 'themeblvd' ),
				'std'		=> '1',
				'type'		=> 'select',
				'options'		=> array(
		            '1'	=> __( 'Yes, show arrows.', 'themeblvd' ),
		            '0'	=> __( 'No, don\'t show them.', 'themeblvd' )
				)
			),
			array(
				'id'		=> 'pause_play',
				'name'		=> __( 'Show pause/play button?', 'themeblvd' ),
				'desc'		=> __('Note that if you have the speed set to 0, this option will be ignored. ', 'themeblvd' ),
				'std'		=> '1',
				'type'		=> 'select',
				'options'		=> array(
		            '1'	=> __( 'Yes, show pause/play button.', 'themeblvd' ),
		            '0'	=> __( 'No, don\'t show it.', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'categories',
				'name'		=> __( 'Categories', 'themeblvd' ),
				'desc'		=> __( 'Select the categories you\'d like to pull posts from. Note that selecting "All Categories" will override any other selections.', 'themeblvd' ),
				'std'		=> array( 'all' => 1 ),
				'type'		=> 'multicheck',
				'options'	=> $categories_multicheck
			),
			array(
		    	'id' 		=> 'columns',
				'name'		=> __( 'Columns', 'themeblvd' ),
				'desc'		=> __( 'Select how many posts per row you\'d like displayed.', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> '3',
				'options'	=> array(
			        '2' 	=> __( '2 Columns', 'themeblvd' ),
			        '3' 	=> __( '3 Columns', 'themeblvd' ),
			        '4' 	=> __( '4 Columns', 'themeblvd' ),
			        '5' 	=> __( '5 Columns', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'rows',
				'name'		=> __( 'Rows per slide', 'themeblvd' ),
				'desc'		=> __( 'Enter in the number of rows <strong>per slide</strong> you\'d like to show. The number you enter here will be multiplied by the amount of columns you selected in the previous option to figure out how many posts should be showed on each slide.', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> '3'
			),
			array(
		    	'id' 		=> 'numberposts',
				'name'		=> __( 'Total Number of Posts', 'themeblvd' ),
				'desc'		=> __( 'Enter the maximum number of posts you\'d like to show from the categories selected. You can use <em>-1</em> to show all posts from the selected categories.', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> '-1'
			),
			array(
		    	'id' 		=> 'orderby',
				'name'		=> __( 'Order By', 'themeblvd' ),
				'desc'		=> __( 'Select what attribute you\'d like the posts ordered by.', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> 'post_date',
				'options'	=> array(
			        'post_date' 	=> __( 'Publish Date', 'themeblvd' ),
			        'title' 		=> __( 'Post Title', 'themeblvd' ),
			        'comment_count' => __( 'Number of Comments', 'themeblvd' ),
			        'rand' 			=> __( 'Random', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'order',
				'name'		=> __( 'Order', 'themeblvd' ),
				'desc'		=> __( 'Select the order in which you\'d like the posts displayed based on the previous orderby parameter.<br><br><em>Note that a traditional WordPress setup would have posts ordered by <strong>Publish Date</strong> and be ordered <strong>Descending</strong>.</em>', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> 'DESC',
				'options'	=> array(
			        'DESC' 	=> __( 'Descending (highest to lowest)', 'themeblvd' ),
			        'ASC' 	=> __( 'Ascending (lowest to highest)', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'offset',
				'name'		=> __( 'Offset', 'themeblvd' ),
				'desc'		=> __( 'Enter the number of posts you\'d like to offset the query by. In most cases, you will just leave this at <em>0</em>. Utilizing this option could be useful, for example, if you wanted to have the first post in an element above this one, and then you could offset this set by <em>1</em> so the posts start after that post in the previous element. If that makes no sense, just ignore this option and leave it at <em>0</em>!', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> '0'
			),
			array(
		    	'id' 		=> 'crop',
				'name'		=> __( 'Custom Image Crop Size (optional)', 'themeblvd' ),
				'desc'		=> __( 'Enter in a custom image crop size. Always leave this blank unless you know what you\'re doing here; when left blank, the theme will generate this crop size for you depending on the amount of columns in your post grid.', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> ''
			),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', 'themeblvd' ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This is optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', 'themeblvd' ),
				'type'		=> 'multicheck',
				'options'	=> array(
					'hide_on_standard' 	=> 'Hide on Standard Resolutions',
					'hide_on_tablet' 	=> 'Hide on Tablets',
					'hide_on_mobile' 	=> 'Hide on Mobile Devices',
				)
			)
		);
		
		// Post List (lead)
		$element_post_list = array(
		    array(
		    	'id' 		=> 'categories',
				'name'		=> __( 'Categories', 'themeblvd' ),
				'desc'		=> __( 'Select the categories you\'d like to pull posts from. Note that selecting "All Categories" will override any other selections.', 'themeblvd' ),
				'std'		=> array( 'all' => 1 ),
				'type'		=> 'multicheck',
				'options'	=> $categories_multicheck
			),
			array(
				'id' 		=> 'thumbs',
				'name' 		=> __( 'Featured Images', 'themeblvd' ), /* Required by Framework */
				'desc' 		=> __( 'Select the size of the post list\'s thumbnails or whether you\'d like to hide them all together when posts are listed.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'select',
				'options' 	=> array(
					'default'	=> __( 'Use default blog setting.', 'themeblvd' ),
					'small'		=> __( 'Show small thumbnails.', 'themeblvd' ),
					'full' 		=> __( 'Show full-width thumbnails.', 'themeblvd' ),
					'hide' 		=> __( 'Hide thumbnails.', 'themeblvd' )
				)
			),
			array( 
				'id' 		=> 'content',
				'name' 		=> __( 'Show excerpts of full content?', 'themeblvd' ), /* Required by Framework */
				'desc' 		=> __( 'Choose whether you want to show full content or post excerpts only.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'select',
				'options' 	=> array(
					'default'	=> __( 'Use default blog setting.', 'themeblvd' ),
					'content'	=> __( 'Show full content.', 'themeblvd' ),
					'excerpt' 	=> __( 'Show excerpt only.', 'themeblvd' )
				) 
			),
			array(
		    	'id' 		=> 'numberposts',
				'name'		=> __( 'Number of Posts', 'themeblvd' ),
				'desc'		=> __( 'Enter in the <strong>total number</strong> of posts you\'d like to show. You can enter <em>-1</em> if you\'d like to show all posts from the categories you\'ve selected.', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> '6'
			),
			array(
		    	'id' 		=> 'orderby',
				'name'		=> __( 'Order By', 'themeblvd' ),
				'desc'		=> __( 'Select what attribute you\'d like the posts ordered by.', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> 'post_date',
				'options'	=> array(
			        'post_date' 	=> __( 'Publish Date', 'themeblvd' ),
			        'title' 		=> __( 'Post Title', 'themeblvd' ),
			        'comment_count' => __( 'Number of Comments', 'themeblvd' ),
			        'rand' 			=> __( 'Random', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'order',
				'name'		=> __( 'Order', 'themeblvd' ),
				'desc'		=> __( 'Select the order in which you\'d like the posts displayed based on the previous orderby parameter.<br><br><em>Note that a traditional WordPress setup would have posts ordered by <strong>Publish Date</strong> and be ordered <strong>Descending</strong>.</em>', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> 'DESC',
				'options'	=> array(
			        'DESC' 	=> __( 'Descending (highest to lowest)', 'themeblvd' ),
			        'ASC' 	=> __( 'Ascending (lowest to highest)', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'offset',
				'name'		=> __( 'Offset', 'themeblvd' ),
				'desc'		=> __( 'Enter the number of posts you\'d like to offset the query by. In most cases, you will just leave this at <em>0</em>. Utilizing this option could be useful, for example, if you wanted to have the first post in an element above this one, and then you could offset this set by <em>1</em> so the posts start after that post in the previous element. If that makes no sense, just ignore this option and leave it at <em>0</em>!', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> '0'
			),
			array(
		    	'id' 		=> 'query',
				'name'		=> __( 'Custom Query String (optional)', 'themeblvd' ),
				'desc'		=> __( 'Enter in a <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Parameters">custom query string</a>. This will override any other query-related options.<br><br>Ex: tag=cooking<br>Ex: post_type=XYZ&numberposts=10<br><br><em>Note: Putting anything in this option will cause the following options to not have any effect: Categories, Number of Posts, Orderby, Order, and Offset</em>', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'show-hide'
		    ),
			array(
		    	'id' 		=> 'link',
				'name'		=> __( 'Link', 'themeblvd' ),
				'desc'		=> __( 'Show link after posts to direct visitors somewhere?', 'themeblvd' ),
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			array(
				'id' 		=> 'link_text',
				'name'		=> __( 'Link Text', 'themeblvd' ),
				'desc'		=> __( 'Enter the text for the link.', 'themeblvd' ),
				'std'		=> 'View All Posts',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'link_url',
				'name'		=> __( 'Link URL', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL where you want this link to go to.', 'themeblvd' ),
				'std'		=> 'http://www.your-site.com/your-blog-page',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'link_target',
				'name'		=> __( 'Link Target', 'themeblvd' ),
				'desc'		=> __( 'Select how you want the link to open.', 'themeblvd' ),
				'std'		=> '_self',
				'type'		=> 'select',
				'class'		=> 'hide receiver',
				'options'		=> array(
			        '_self' 	=> __( 'Same Window', 'themeblvd' ),
			        '_blank' 	=> __( 'New Window', 'themeblvd' )
				) 
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', 'themeblvd' ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This is optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', 'themeblvd' ),
				'type'		=> 'multicheck',
				'options'	=> array(
					'hide_on_standard' 	=> 'Hide on Standard Resolutions',
					'hide_on_tablet' 	=> 'Hide on Tablets',
					'hide_on_mobile' 	=> 'Hide on Mobile Devices',
				)
			)
		);
		
		// Post List (paginated)
		$element_post_list_paginated = array(
		   	array(
		    	'id' 		=> 'categories',
				'name'		=> __( 'Categories', 'themeblvd' ),
				'desc'		=> __( 'Select the categories you\'d like to pull posts from. Note that selecting "All Categories" will override any other selections.', 'themeblvd' ),
				'std'		=> array( 'all' => 1 ),
				'type'		=> 'multicheck',
				'options'	=> $categories_multicheck
			),
			array(
				'id' 		=> 'thumbs',
				'name' 		=> __( 'Featured Images', 'themeblvd' ),
				'desc' 		=> __( 'Select the size of the post list\'s thumbnails or whether you\'d like to hide them all together when posts are listed.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'select',
				'options' 	=> array(
					'default'	=> __( 'Use default blog setting.', 'themeblvd' ),
					'small'		=> __( 'Show small thumbnails.', 'themeblvd' ),
					'full' 		=> __( 'Show full-width thumbnails.', 'themeblvd' ),
					'hide' 		=> __( 'Hide thumbnails.', 'themeblvd' )
				)
			),
			array( 
				'id' 		=> 'content',
				'name' 		=> __( 'Show excerpts of full content?', 'themeblvd' ), /* Required by Framework */
				'desc' 		=> __( 'Choose whether you want to show full content or post excerpts only.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'select',
				'options' 	=> array(
					'default'	=> __( 'Use default blog setting.', 'themeblvd' ),
					'content'	=> __( 'Show full content.', 'themeblvd' ),
					'excerpt' 	=> __( 'Show excerpt only.', 'themeblvd' )
				) 
			),
			array(
		    	'id' 		=> 'posts_per_page',
				'name'		=> __( 'Posts per page', 'themeblvd' ),
				'desc'		=> __( 'Enter in the number of posts <strong>per page</strong> you\'d like to show. You can enter <em>-1</em> if you\'d like to show all posts from the categories you\'ve selected on a single page.', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> '6'
			),
			array(
		    	'id' 		=> 'orderby',
				'name'		=> __( 'Order By', 'themeblvd' ),
				'desc'		=> __( 'Select what attribute you\'d like the posts ordered by.', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> '3',
				'options'	=> array(
			        'post_date' 	=> __( 'Publish Date', 'themeblvd' ),
			        'title' 		=> __( 'Post Title', 'themeblvd' ),
			        'comment_count' => __( 'Number of Comments', 'themeblvd' ),
			        'rand' 			=> __( 'Random', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'order',
				'name'		=> __( 'Order', 'themeblvd' ),
				'desc'		=> __( 'Select the order in which you\'d like the posts displayed based on the previous orderby parameter.<br><br><em>Note that a traditional WordPress setup would have posts ordered by <strong>Publish Date</strong> and be ordered <strong>Descending</strong>.</em>', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> 'DESC',
				'options'	=> array(
			        'DESC' 	=> __( 'Descending (highest to lowest)', 'themeblvd' ),
			        'ASC' 	=> __( 'Ascending (lowest to highest)', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'query',
				'name'		=> __( 'Custom Query String (optional)', 'themeblvd' ),
				'desc'		=> __( 'Enter in a <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Parameters">custom query string</a>. This will override any other query-related options.<br><br>Ex: tag=cooking<br>Ex: post_type=XYZ&posts_per_page=10<br><br><em>Note: Putting anything in this option will cause the following options to not have any effect: Categories, Posts per page, Orderby, and Order</em>', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> ''
			),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', 'themeblvd' ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This is optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', 'themeblvd' ),
				'type'		=> 'multicheck',
				'options'	=> array(
					'hide_on_standard' 	=> 'Hide on Standard Resolutions',
					'hide_on_tablet' 	=> 'Hide on Tablets',
					'hide_on_mobile' 	=> 'Hide on Mobile Devices',
				)
			)
		);
		
		// Post List Slider
		$element_post_list_slider = array(
			array(
		    	'id' 		=> 'fx',
				'name'		=> __( 'Transition Effect', 'themeblvd' ),
				'desc'		=> __( 'Select the effect you\'d like used to transition from one slide to the next.', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> 'slide',
				'options'	=> array(
			        'fade' 	=> 'Fade',
					'slide'	=> 'Slide'
				)
			),
			array(
		    	'id' 		=> 'timeout',
				'name'		=> __( 'Speed', 'themeblvd' ),
				'desc'		=> __( 'Enter the number of seconds you\'d like in between trasitions. You may use <em>0</em> to disable the slider from auto advancing.', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> '0'
			),
			array(
				'id'		=> 'nav_standard',
				'name'		=> __( 'Show standard slideshow navigation?', 'themeblvd' ),
				'desc'		=> __( 'The standard navigation are the little dots that appear below the slider.' , 'themeblvd' ),
				'std'		=> '1',
				'type'		=> 'select',
				'options'		=> array(
		            '1'	=> __( 'Yes, show navigation.', 'themeblvd' ),
		            '0'	=> __( 'No, don\'t show it.', 'themeblvd' )
				)
			),
			array(
				'id'		=> 'nav_arrows',
				'name'		=> __( 'Show next/prev slideshow arrows?', 'themeblvd' ),
				'desc'		=> __( 'These arrows allow the user to navigation from one slide to the next.' , 'themeblvd' ),
				'std'		=> '1',
				'type'		=> 'select',
				'options'		=> array(
		            '1'	=> __( 'Yes, show arrows.', 'themeblvd' ),
		            '0'	=> __( 'No, don\'t show them.', 'themeblvd' )
				)
			),
			array(
				'id'		=> 'pause_play',
				'name'		=> __( 'Show pause/play button?', 'themeblvd' ),
				'desc'		=> __('Note that if you have the speed set to 0, this option will be ignored. ', 'themeblvd' ),
				'std'		=> '1',
				'type'		=> 'select',
				'options'		=> array(
		            '1'	=> __( 'Yes, show pause/play button.', 'themeblvd' ),
		            '0'	=> __( 'No, don\'t show it.', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'categories',
				'name'		=> __( 'Categories', 'themeblvd' ),
				'desc'		=> __( 'Select the categories you\'d like to pull posts from. Note that selecting "All Categories" will override any other selections.', 'themeblvd' ),
				'std'		=> array( 'all' => 1 ),
				'type'		=> 'multicheck',
				'options'	=> $categories_multicheck
			),
			array(
				'id' 		=> 'thumbs',
				'name' 		=> __( 'Featured Images', 'themeblvd' ), /* Required by Framework */
				'desc' 		=> __( 'Select the size of the post list\'s thumbnails or whether you\'d like to hide them all together when posts are listed.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'select',
				'options' 	=> array(
					'default'	=> __( 'Use default blog setting.', 'themeblvd' ),
					'small'		=> __( 'Show small thumbnails.', 'themeblvd' ),
					'full' 		=> __( 'Show full-width thumbnails.', 'themeblvd' ),
					'hide' 		=> __( 'Hide thumbnails.', 'themeblvd' )
				)
			),
			array( 
				'id' 		=> 'content',
				'name' 		=> __( 'Show excerpts of full content?', 'themeblvd' ), /* Required by Framework */
				'desc' 		=> __( 'Choose whether you want to show full content or post excerpts only.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'select',
				'options' 	=> array(
					'default'	=> __( 'Use default blog setting.', 'themeblvd' ),
					'content'	=> __( 'Show full content.', 'themeblvd' ),
					'excerpt' 	=> __( 'Show excerpt only.', 'themeblvd' )
				) 
			),
			array(
		    	'id' 		=> 'posts_per_slide',
				'name'		=> __( 'Posts per slide', 'themeblvd' ),
				'desc'		=> __( 'Enter in the number of posts <strong>per slide</strong> you\'d like to show.', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> '3'
			),
			array(
		    	'id' 		=> 'numberposts',
				'name'		=> __( 'Total Number of Posts', 'themeblvd' ),
				'desc'		=> __( 'Enter the maximum number of posts you\'d like to show from the categories selected. You can use <em>-1</em> to show all posts from the selected categories.', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> '-1'
			),
			array(
		    	'id' 		=> 'orderby',
				'name'		=> __( 'Order By', 'themeblvd' ),
				'desc'		=> __( 'Select what attribute you\'d like the posts ordered by.', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> '3',
				'options'	=> array(
			        'post_date' 	=> __( 'Publish Date', 'themeblvd' ),
			        'title' 		=> __( 'Post Title', 'themeblvd' ),
			        'comment_count' => __( 'Number of Comments', 'themeblvd' ),
			        'rand' 			=> __( 'Random', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'order',
				'name'		=> __( 'Order', 'themeblvd' ),
				'desc'		=> __( 'Select the order in which you\'d like the posts displayed based on the previous orderby parameter.<br><br><em>Note that a traditional WordPress setup would have posts ordered by <strong>Publish Date</strong> and be ordered <strong>Descending</strong>.</em>', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> 'DESC',
				'options'	=> array(
			        'DESC' 	=> __( 'Descending (highest to lowest)', 'themeblvd' ),
			        'ASC' 	=> __( 'Ascending (lowest to highest)', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'offset',
				'name'		=> __( 'Offset', 'themeblvd' ),
				'desc'		=> __( 'Enter the number of posts you\'d like to offset the query by. In most cases, you will just leave this at <em>0</em>. Utilizing this option could be useful, for example, if you wanted to have the first post in an element above this one, and then you could offset this set by <em>1</em> so the posts start after that post in the previous element. If that makes no sense, just ignore this option and leave it at <em>0</em>!', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> '0'
			),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', 'themeblvd' ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This is optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', 'themeblvd' ),
				'type'		=> 'multicheck',
				'options'	=> array(
					'hide_on_standard' 	=> 'Hide on Standard Resolutions',
					'hide_on_tablet' 	=> 'Hide on Tablets',
					'hide_on_mobile' 	=> 'Hide on Mobile Devices',
				)
			)	
		);
		
		// Slider
		$element_slider = array(
		    array(
		    	'id' 		=> 'slider_id',
				'name'		=> __( 'Choose Slider', 'themeblvd' ),
				'desc'		=> __( 'Choose from the sliders you\'ve created. You can edit these sliders at any time under the \'Sliders\' tab above.', 'themeblvd' ),
				'type'		=> 'select',
				'options'	=> $sliders_select
			),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', 'themeblvd' ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This is optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', 'themeblvd' ),
				'type'		=> 'multicheck',
				'options'	=> array(
					'hide_on_standard' 	=> 'Hide on Standard Resolutions',
					'hide_on_tablet' 	=> 'Hide on Tablets',
					'hide_on_mobile' 	=> 'Hide on Mobile Devices',
				)
			)
		);
		
		// Slogan
		$element_slogan = array(
			array(
				'id' 		=> 'slogan',
				'name' 		=> __( 'Setup Slogan', 'themeblvd'),
				'desc'		=> __( 'Enter the text you\'d like to show.', 'themeblvd'),
				'type'		=> 'textarea'
		    ),
		    array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'show-hide'
		    ),
			array(
		    	'id' 		=> 'button',
				'name'		=> __( 'Button', 'themeblvd' ),
				'desc'		=> __( 'Show call-to-action button next to slogan?', 'themeblvd' ),
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			array(
				'id' 		=> 'button_text',
				'name'		=> __( 'Button Text', 'themeblvd' ),
				'desc'		=> __( 'Enter the text for the button.', 'themeblvd' ),
				'std'		=> 'Get Started Today!',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'button_color',
				'name'		=> __( 'Button Color', 'themeblvd' ),
				'desc'		=> __( 'Select what color you\'d like to use for this button.', 'themeblvd' ),
				'type'		=> 'select',
				'class'		=> 'hide receiver',
				'options'	=> themeblvd_colors()
			),
			array(
				'id' 		=> 'button_url',
				'name'		=> __( 'Link URL', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL where you want the button\'s link to go.', 'themeblvd' ),
				'std'		=> 'http://www.your-site.com/your-landing-page',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'button_target',
				'name'		=> __( 'Link Target', 'themeblvd' ),
				'desc'		=> __( 'Select how you want the button to open the webpage.', 'themeblvd' ),
				'type'		=> 'select',
				'class'		=> 'hide receiver',
				'options'		=> array(
			        '_self' 	=> __( 'Same Window', 'themeblvd' ),
			        '_blank' 	=> __( 'New Window', 'themeblvd' )
				) 
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', 'themeblvd' ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This is optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', 'themeblvd' ),
				'type'		=> 'multicheck',
				'options'	=> array(
					'hide_on_standard' 	=> 'Hide on Standard Resolutions',
					'hide_on_tablet' 	=> 'Hide on Tablets',
					'hide_on_mobile' 	=> 'Hide on Mobile Devices',
				)
			)
		);
		
		$element_tabs = array(
			array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'tabs'
		    ),
		   	array(
				'id' 		=> 'setup',
				'name'		=> __( 'Setup Tabs', 'themeblvd' ),
				'desc'		=> __( 'Choose the number of tabs along with inputting a name for each one. These names are what will appear on the actual tab buttons across the top of the tab set.', 'themeblvd' ),
				'type'		=> 'tabs'
			),
			array(
				'id' 		=> 'height',
				'name'		=> __( 'Fixed Height', 'themeblvd' ),
				'desc'		=> __( 'Enter in a number of pixels for a fixed height if you\'d like to do so. Ex: 400<br><br>This can help with "page jumping" in the case that not all tabs have equal amount of content. It can also help in the case when you\'re getting unwanted scrollbars on the inner content areas of tabs. This is optional.', 'themeblvd' ),
				'type'		=> 'text' 
			),
			array(
				'id' 		=> 'tab_1',
				'name'		=> __( 'Tab #1 Content', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the first tab.', 'themeblvd' ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_2',
				'name'		=> __( 'Tab #2 Content', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the second tab.', 'themeblvd' ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_3',
				'name'		=> __( 'Tab #3 Content', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the third tab.', 'themeblvd' ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_4',
				'name'		=> __( 'Tab #4 Content', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the fourth tab.', 'themeblvd' ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_5',
				'name'		=> __( 'Tab #5 Content', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the fifth tab.', 'themeblvd' ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_6',
				'name'		=> __( 'Tab #6 Content', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the sixth tab.', 'themeblvd' ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_7',
				'name'		=> __( 'Tab #7 Content', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the seventh tab.', 'themeblvd' ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_8',
				'name'		=> __( 'Tab #8 Content', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the eighth tab.', 'themeblvd' ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_9',
				'name'		=> __( 'Tab #9 Content', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the ninth tab.', 'themeblvd' ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_10',
				'name'		=> __( 'Tab #10 Content', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the tenth tab.', 'themeblvd' ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_11',
				'name'		=> __( 'Tab #11 Content', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the eleventh tab.', 'themeblvd' ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_12',
				'name'		=> __( 'Tab #12 Content', 'themeblvd' ),
				'desc'		=> __( 'Configure the content for the twelfth tab.', 'themeblvd' ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', 'themeblvd' ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This is optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', 'themeblvd' ),
				'type'		=> 'multicheck',
				'options'	=> array(
					'hide_on_standard' 	=> 'Hide on Standard Resolutions',
					'hide_on_tablet' 	=> 'Hide on Tablets',
					'hide_on_mobile' 	=> 'Hide on Mobile Devices',
				)
			)
		);
		
		// Tweet
		$element_tweet = array(
		    array(
		    	'id' 		=> 'account',
				'name'		=> __( 'Twitter Account', 'themeblvd' ),
				'desc'		=> __( 'Enter the Twitter username you\'d like to pull the most recent tweet from.', 'themeblvd' ),
				'std'		=> 'themeblvd',
				'type'		=> 'text'
			),
			array(
		    	'id' 		=> 'icon',
				'name'		=> __( 'Icon', 'themeblvd' ),
				'desc'		=> __( 'Enter any Font Awesome icon ID; this icon will then display next to the Tweet. Set this option blank to not use any icon. Examples: twitter, pencil, warning-sign, etc. ', 'themeblvd' ),
				'type'		=> 'text',
				'std'		=> 'twitter'
			),
			array(
		    	'id' 		=> 'meta',
				'name'		=> __( 'Tweet Meta Info', 'themeblvd' ),
				'desc'		=> __( 'Select whether you\'d like information about the current tweet displayed below it.', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> 'show',
				'options'	=> array(
			        'show' 	=> __( 'Show meta info below tweet.', 'themeblvd' ),
			        'hide' 	=> __( 'Hide meta info below tweet.', 'themeblvd' )
				)
			),
			array(
		    	'id' 		=> 'replies',
				'name'		=> __( 'Exclude @replies?', 'themeblvd' ),
				'desc'		=> __( 'Select whether or not you\'d like to exclude @replies for the current tweet.', 'themeblvd' ),
				'type'		=> 'select',
				'std'		=> 'no',
				'options'	=> array(
			        'yes' 	=> __( 'Yes, exclude @replies.', 'themeblvd' ),
			        'no' 	=> __( 'No, do not exclude @replies.', 'themeblvd' )
				)
			),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', 'themeblvd' ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This is optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', 'themeblvd' ),
				'type'		=> 'multicheck',
				'options'	=> array(
					'hide_on_standard' 	=> 'Hide on Standard Resolutions',
					'hide_on_tablet' 	=> 'Hide on Tablets',
					'hide_on_mobile' 	=> 'Hide on Mobile Devices',
				)
			)
		);
		
		/*------------------------------------------------------*/
		/* Final Filterable Elements
		/*------------------------------------------------------*/
		
		$_themeblvd_core_elements = array(
			'columns' => array(
				'info' => array(
					'name' 	=> 'Columns',
					'id'	=> 'columns',
					'query'	=> 'none',
					'hook'	=> 'themeblvd_columns',
					'shortcode'	=> false,
					'desc' 	=> __( 'Row of columns with custom content', 'themeblvd' )
				),
				'options' => $element_columns
			),
			'content' => array(
				'info' => array(
					'name' 	=> 'Content',
					'id'	=> 'content',
					'query'	=> 'none',
					'hook'	=> null,
					'shortcode'	=> false,
					'desc' 	=> __( 'Content from external page or current page', 'themeblvd' )
				),
				'options' => $element_content
			),
			'divider' => array(
				'info' => array(
					'name' 	=> 'Divider',
					'id'	=> 'divider',
					'query'	=> 'none',
					'hook'	=> 'themeblvd_divider',
					'shortcode'	=> '[divider]',
					'desc' 	=> __( 'Simple divider line to break up content', 'themeblvd' )
				),
				'options' => $element_divider
			),
			'headline' => array(
				'info' => array(
					'name' 	=> 'Headline',
					'id'	=> 'headline',
					'query'	=> 'none',
					'hook'	=> 'themeblvd_headline',
					'shortcode'	=> false,
					'desc' 	=> __( 'Simple <H> header title', 'themeblvd' )
				),
				'options' => $element_headline
			),
			'post_grid_paginated' => array(
				'info' => array(
					'name' 	=> 'Post Grid (paginated)',
					'id'	=> 'post_grid_paginated',
					'query'	=> 'primary',
					'hook'	=> 'themeblvd_post_grid_paginated',
					'shortcode'	=> '[post_grid]',
					'desc' 	=> __( 'Full paginated grid of posts', 'themeblvd' )
				),
				'options' => $element_post_grid_paginated
			),
			'post_grid' => array(
				'info' => array(
					'name' 	=> 'Post Grid',
					'id'	=> 'post_grid',
					'query'	=> 'secondary',
					'hook'	=> 'themeblvd_post_grid',
					'shortcode'	=> '[post_grid]',
					'desc' 	=> __( 'Grid of posts followed by optional link', 'themeblvd' )
				),
				'options' => $element_post_grid
			),
			'post_grid_slider' => array(
				'info' => array(
					'name' 	=> 'Post Grid Slider',
					'id'	=> 'post_grid_slider',
					'query'	=> 'secondary',
					'hook'	=> 'themeblvd_post_grid_slider',
					'shortcode'	=> '[post_grid_slider]',
					'desc' 	=> __( 'Slider of posts in a grid', 'themeblvd' )
				),
				'options' => $element_post_grid_slider
			),
			'post_list_paginated' => array(
				'info' => array(
					'name' 	=> 'Post List (paginated)',
					'id'	=> 'post_list_paginated',
					'query'	=> 'primary',
					'hook'	=> 'themeblvd_post_list_paginated',
					'shortcode'	=> '[post_list]',
					'desc' 	=> __( 'Full paginated list of posts', 'themeblvd' )
				),
				'options' => $element_post_list_paginated
			),
			'post_list' => array(
				'info' => array(
					'name' 	=> 'Post List',
					'id'	=> 'post_list',
					'query'	=> 'secondary',
					'hook'	=> 'themeblvd_post_list',
					'shortcode'	=> '[post_list]',
					'desc' 	=> __( 'List of posts followed by optional link', 'themeblvd' )
				),
				'options' => $element_post_list
			),
			'post_list_slider' => array(
				'info' => array(
					'name' 	=> 'Post List Slider',
					'id'	=> 'post_list_slider',
					'query'	=> 'secondary',
					'hook'	=> 'themeblvd_post_list_slider',
					'shortcode'	=> '[post_list_slider]',
					'desc' 	=> __( 'Slider of posts listed out', 'themeblvd' )
				),
				'options' => $element_post_list_slider
			),
			'slider' => array(
				'info' => array(
					'name' 	=> 'Slider',
					'id'	=> 'slider',
					'query'	=> 'secondary',
					'hook'	=> 'themeblvd_slider',
					'shortcode'	=> '[slider]',
					'desc' 	=> __( 'User-built slideshow', 'themeblvd' )
				),
				'options' => $element_slider
			),
			'slogan' => array(
				'info' => array(
					'name' 	=> 'Slogan',
					'id'	=> 'slogan',
					'query'	=> 'none',
					'hook'	=> 'themeblvd_slogan',
					'shortcode'	=> '[slogan]',
					'desc' 	=> __( 'Slogan with optional button', 'themeblvd' )
				),
				'options' => $element_slogan
			),
			'tabs' => array(
				'info' => array(
					'name' 	=> 'Tabs',
					'id'	=> 'tabs',
					'query'	=> 'none',
					'hook'	=> 'themeblvd_tabs',
					'shortcode'	=> '[tabs]',
					'desc' 	=> __( 'Set of tabbed content', 'themeblvd' )
				),
				'options' => $element_tabs
			),
			'tweet' => array(
				'info' => array(
					'name' 	=> 'Tweet',
					'id'	=> 'tweet',
					'query'	=> 'none',
					'hook'	=> 'themeblvd_tweet',
					'shortcode'	=> null,
					'desc' 	=> __( 'Shows the most recent tweet from a Twitter account', 'themeblvd' )
				),
				'options' => $element_tweet
			)
		);
		
		// Remove slider element if plugin isn't installed
		if( ! defined( 'TB_SLIDERS_PLUGIN_VERSION' ) )
			unset( $_themeblvd_core_elements['slider'] );
		
		return apply_filters( 'themeblvd_core_elements', $_themeblvd_core_elements );
	}
}

/**
 * Get layout builder's elements after new elements 
 * have been given a chance to be added at the theme-level.
 *
 * @uses $_themeblvd_core_elements 
 * @since 2.1.0
 */

if( ! function_exists( 'themeblvd_get_elements' ) ) {
	function themeblvd_get_elements() {
		global $_themeblvd_core_elements;
		$elements = array();
		if( $_themeblvd_core_elements )
			$elements = $_themeblvd_core_elements;
		return apply_filters( 'themeblvd_elements', $elements );
	}
}

/**
 * Check if element is currently registered.
 *
 * @uses $_themeblvd_registered_elements 
 * @since 2.1.0
 *
 * @param string $element_id ID of element type to check for
 * @return boolean $exists If element exists or not
 */

if( ! function_exists( 'themeblvd_is_element' ) ) {
	function themeblvd_is_element( $element_id ) {
		global $_themeblvd_registered_elements;
		$exists = false;
		if( is_array( $_themeblvd_registered_elements ) )
			if( in_array( $element_id, $_themeblvd_registered_elements ) )
				$exists = true;
		return $exists;
		
	}
}

/**
 * Add element to layout builder.
 *
 * @since 2.1.0
 *
 * @param string $element_id ID of element to add
 * @param string $element_name Name of element to add
 * @param string $query_type Type of query if any - none, secondary, or primary
 * @param array $options Options formatted for Options Framework
 * @param string $function_to_display Function to display element on frontend
 */

if( ! function_exists( 'themeblvd_add_builder_element' ) ) { 
	function themeblvd_add_builder_element( $element_id, $element_name, $query_type, $options, $function_to_display ) {
		
		global $_themeblvd_core_elements;
		global $_themeblvd_registered_elements;
		
		// Register element
		$_themeblvd_registered_elements[] = $element_id;
		
		// Add in element
		$_themeblvd_core_elements[$element_id] = array(
			'info' => array(
				'name' 		=> $element_name,
				'id'		=> $element_id,
				'query'		=> $query_type,
				'hook'		=> 'themeblvd_'.$element_id,
				'shortcode'	=> null,
				'desc' 		=> null
			),
			'options' => $options
		);
		
		// Hook in display function on frontend
		add_action( 'themeblvd_'.$element_id, $function_to_display, 10, 3 );
	}
}

/**
 * Remove element from layout builder.
 *
 * @since 2.1.0
 *
 * @param string $element_id ID of element to remove
 */

if( ! function_exists( 'themeblvd_remove_builder_element' ) ) { 
	function themeblvd_remove_builder_element( $element_id ) {
		
		global $_themeblvd_core_elements;
		global $_themeblvd_registered_elements;
		
		// Remove Element
		unset( $_themeblvd_core_elements[$element_id] );
		
		// De-register Element
		foreach( $_themeblvd_registered_elements as $key => $value )
			if( $value == $element_id )
				unset( $_themeblvd_registered_elements[$key] );
	}
}

/**
 * Add sample layout to layout builder.
 *
 * @since 2.1.0
 *
 * @param string $element_id ID of element to add
 * @param string $element_name Name of element to add
 * @param string $query_type Type of query if any - none, secondary, or primary
 * @param array $options Options formatted for Options Framework
 * @param string $function_to_display Function to display element on frontend
 */

if( ! function_exists( 'themeblvd_add_sample_layout' ) ) { 
	function themeblvd_add_sample_layout( $layout_id, $layout_name, $preview, $sidebar_layout, $elements ) {
		
		global $_themeblvd_user_sample_layouts;
		
		/* $elements should look something like this.
		$elements = array(
			array(
				'type' => 'slider',
				'location' => 'featured',
				'defaults' => array()
			),
			array(
				'type' => 'slogan',
				'location' => 'featured',
				'defaults' => array()
			)
		);
		*/

		// get all elements and fill in items based on info there instead of making user put it into this function.
		$framework_elements = themeblvd_get_elements();
		
		// Start layout
		$new_layout = array(
			'name' 				=> $layout_name,
			'id' 				=> $layout_id,
			'preview' 			=> $preview,
			'sidebar_layout'	=> $sidebar_layout,
			'featured'			=> array(),
			'primary' 			=> array(),
			'featured_below' 	=> array()
		);
		
		// Add on elements
		$i = 1;
		if( $elements ) {
			foreach( $elements as $element ) {
				if( themeblvd_is_element( $element['type'] ) ) {	
					// Addon element
					$new_layout[$element['location']]['element_'.$i] = array(
						'type' 			=> $element['type'],
						'query_type' 	=> $framework_elements[$element['type']]['info']['query'],
						'options'		=> array()
					);
					// Configure any default options
					if( isset( $element['defaults'] ) ) {
						foreach ( $framework_elements[$element['type']]['options'] as $option ) {
							$default_value = null;
							// Did the user put in a default value for this element?
							if( $element['defaults'] )
								foreach( $element['defaults'] as $key => $value )
									if( isset( $option['id'] ) )	
										if( $key == $option['id'] )
											$default_value = $value;
							// Is there a default value for the element in the builder?
							if( $default_value === null )
								if( isset( $option['std'] ) )
									$default_value = $option['std'];
							// Apply value if this an actual option with ID
							if( isset( $option['id'] ) )
								$new_layout[$element['location']]['element_'.$i]['options'][$option['id']] = $default_value;
						}
					}
					$i++;
				}
			}
		}
		
		// Add new layout to global user layouts
		$_themeblvd_user_sample_layouts[$layout_id] = $new_layout;
	}
}

/**
 * Remove sample layout from layout builder.
 *
 * @since 2.1.0
 *
 * @param string $element_id ID of element to remove
 */

if( ! function_exists( 'themeblvd_remove_sample_layout' ) ) { 
	function themeblvd_remove_sample_layout( $layout_id ) {
		global $_themeblvd_remove_sample_layouts;
		$_themeblvd_remove_sample_layouts[] = $layout_id;
	}
}