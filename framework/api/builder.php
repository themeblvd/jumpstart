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
		if( ! $sidebars ) $sidebars['null'] = __( 'You haven\'t created any floating widget areas yet.', TB_GETTEXT_DOMAIN );
			
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
				'name'		=> __( 'Setup Columns', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Choose the number of columns along with the corresponding width configurations.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'columns',
				'options'	=> 'element'
			),
			array(
				'id' 		=> 'col_1',
				'name'		=> __( 'Column #1', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the first column.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'class'		=> 'col_1',
				'options'	=> array( 'widget', 'page', 'raw' )
			),
			array(
				'id' 		=> 'col_2',
				'name'		=> __( 'Column #2', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the second column.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'class'		=> 'col_2',
				'options'	=> array( 'widget', 'page', 'raw' )
			),
			array(
				'id' 		=> 'col_3',
				'name'		=> __( 'Column #3', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the third column.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'class'		=> 'col_3',
				'options'	=> array( 'widget', 'page', 'raw' )
			),
			array(
				'id' 		=> 'col_4',
				'name'		=> __( 'Column #4', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the fourth column.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'class'		=> 'col_4',
				'options'	=> array( 'widget', 'page', 'raw' )
			),
			array(
				'id' 		=> 'col_5',
				'name'		=> __( 'Column #5', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the fifth column.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'class'		=> 'col_5',
				'options'	=> array( 'widget', 'page', 'raw' )
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', TB_GETTEXT_DOMAIN ),
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
				'name'		=> __( 'Content Source', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Choose where you\'d like to have content feed from. The content can either be from the current page you\'re applying this layout to or an external page.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'options'	=> array(
					'current' 		=> __( 'Content from current page', TB_GETTEXT_DOMAIN ),
			        'external' 		=> __( 'Content from external page', TB_GETTEXT_DOMAIN ),
			        'raw'			=> __( 'Raw content', TB_GETTEXT_DOMAIN ),
			        'widget_area'	=> __( 'Floating Widget Area', TB_GETTEXT_DOMAIN )
				),
				'class'		=> 'custom-content-types'
			),
			array(
		    	'id' 		=> 'page_id',
				'name'		=> __( 'External Page', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Choose the external page you\'d like to pull content from.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'options'	=> $pages_select,
				'class'		=> 'hide page-content'
			),
			array(
		    	'id' 		=> 'raw_content',
				'name'		=> __( 'Raw Content', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter in the content you\'d like to show. You may use basic HTML, and most shortcodes.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'textarea',
				'class'		=> 'hide raw-content'
			),
			array(
		    	'id' 		=> 'raw_format',
				'name'		=> __( 'Raw Content Formatting', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Apply WordPress automatic formatting.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'checkbox',
				'std'		=> '1',
				'class'		=> 'hide raw-content'
			),
			array(
		    	'id' 		=> 'widget_area',
				'name'		=> __( 'Floating Widget Area', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select from your floating custom widget areas. In order for a custom widget area to be "floating" you must have it configured this way in the Widget Area manager.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'options'	=> $sidebars,
				'class'		=> 'hide widget_area-content'
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', TB_GETTEXT_DOMAIN ),
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
				'name'		=> __( 'Divider Type', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select which style of divider you\'d like to use here.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'options'		=> array(
			        'dashed' 	=> __( 'Dashed Line', TB_GETTEXT_DOMAIN ),
			        'shadow' 	=> __( 'Shadow Line', TB_GETTEXT_DOMAIN ),
					'solid' 	=> __( 'Solid Line', TB_GETTEXT_DOMAIN )
				) 
			),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', TB_GETTEXT_DOMAIN ),
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
				'name'		=> __( 'Headline Text', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter in the text you\'d like to use for your headline. It is better if you use plain text here and not try and use HTML tags.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'textarea',
			),
			array(
		    	'id' 		=> 'tagline',
				'name'		=> __( 'Tagline', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter any text you\'d like to display below the headline as a tagline. Feel free to leave this blank. It is better if you use plain text here and not try and use HTML tags.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'textarea'
			),
		    array(
		    	'id' 		=> 'tag',
				'name'		=> __( 'Headline Tag', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select the type of header tag you\'d like to wrap this headline.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'options'	=> array(
					'h1' => __( '&lt;h1&gt;Your Headline&lt;/h1&gt;', TB_GETTEXT_DOMAIN ),
					'h2' => __( '&lt;h2&gt;Your Headline&lt;/h2&gt;', TB_GETTEXT_DOMAIN ),
					'h3' => __( '&lt;h3&gt;Your Headline&lt;/h3&gt;', TB_GETTEXT_DOMAIN ),
					'h4' => __( '&lt;h4&gt;Your Headline&lt;/h4&gt;', TB_GETTEXT_DOMAIN ),
					'h5' => __( '&lt;h5&gt;Your Headline&lt;/h5&gt;', TB_GETTEXT_DOMAIN ),
					'h6' => __( '&lt;h6&gt;Your Headline&lt;/h6&gt;', TB_GETTEXT_DOMAIN )
				)   
			),
			array(
				'id' 		=> 'align',
				'name'		=> __( 'Headline Alignment', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select how you\'d like the text in this title to align.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'options'		=> array(
			        'left' 		=> __( 'Align Left', TB_GETTEXT_DOMAIN ),
			        'center' 	=> __( 'Center', TB_GETTEXT_DOMAIN ),
					'right' 	=> __( 'Align Right', TB_GETTEXT_DOMAIN )
				) 
			),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', TB_GETTEXT_DOMAIN ),
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
				'name'		=> __( 'Categories', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select the categories you\'d like to pull posts from. Note that selecting "All Categories" will override any other selections.', TB_GETTEXT_DOMAIN ),
				'std'		=> array( 'all' => 1 ),
				'type'		=> 'multicheck',
				'options'	=> $categories_multicheck
			),
			array(
		    	'id' 		=> 'columns',
				'name'		=> __( 'Columns', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select how many posts per row you\'d like displayed.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> '3',
				'options'	=> array(
			        '2' 	=> __( '2 Columns', TB_GETTEXT_DOMAIN ),
			        '3' 	=> __( '3 Columns', TB_GETTEXT_DOMAIN ),
			        '4' 	=> __( '4 Columns', TB_GETTEXT_DOMAIN ),
			        '5' 	=> __( '5 Columns', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'rows',
				'name'		=> __( 'Rows', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter in the number of rows you\'d like to show. The number you enter here will be multiplied by the amount of columns you selected in the previous option to figure out how many posts should be showed. You can leave this option blank if you\'d like to show all posts from the categories you\'ve selected.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> '3'
			),
			array(
		    	'id' 		=> 'orderby',
				'name'		=> __( 'Order By', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select what attribute you\'d like the posts ordered by.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> '3',
				'options'	=> array(
			        'post_date' 	=> __( 'Publish Date', TB_GETTEXT_DOMAIN ),
			        'title' 		=> __( 'Post Title', TB_GETTEXT_DOMAIN ),
			        'comment_count' => __( 'Number of Comments', TB_GETTEXT_DOMAIN ),
			        'rand' 			=> __( 'Random', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'order',
				'name'		=> __( 'Order', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select the order in which you\'d like the posts displayed based on the previous orderby parameter.<br><br><em>Note that a traditional WordPress setup would have posts ordered by <strong>Publish Date</strong> and be ordered <strong>Descending</strong>.</em>', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> 'DESC',
				'options'	=> array(
			        'DESC' 	=> __( 'Descending (highest to lowest)', TB_GETTEXT_DOMAIN ),
			        'ASC' 	=> __( 'Ascending (lowest to highest)', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'offset',
				'name'		=> __( 'Offset', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter the number of posts you\'d like to offset the query by. In most cases, you will just leave this at <em>0</em>. Utilizing this option could be useful, for example, if you wanted to have the first post in an element above this one, and then you could offset this set by <em>1</em> so the posts start after that post in the previous element. If that makes no sense, just ignore this option and leave it at <em>0</em>!', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> '0'
			),
			array(
		    	'id' 		=> 'query',
				'name'		=> __( 'Custom Query String (optional)', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter in a <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Parameters">custom query string</a>. This will override any other query-related options.<br><br>Ex: tag=cooking<br>Ex: post_type=XYZ&numberposts=10<br><br><em>Note: Putting anything in this option will cause the following options to not have any effect: Categories, Orderby, Order, and Offset</em>', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'show-hide'
		    ),
			array(
		    	'id' 		=> 'link',
				'name'		=> __( 'Link', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Show link after posts to direct visitors somewhere?', TB_GETTEXT_DOMAIN ),
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			array(
				'id' 		=> 'link_text',
				'name'		=> __( 'Link Text', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter the text for the link.', TB_GETTEXT_DOMAIN ),
				'std'		=> 'View All Posts',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'link_url',
				'name'		=> __( 'Link URL', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter the full URL where you want this link to go to.', TB_GETTEXT_DOMAIN ),
				'std'		=> 'http://www.your-site.com/your-blog-page',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'link_target',
				'name'		=> __( 'Link Target', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select how you want the link to open.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'class'		=> 'hide receiver',
				'options'		=> array(
			        '_self' 	=> __( 'Same Window', TB_GETTEXT_DOMAIN ),
			        '_blank' 	=> __( 'New Window', TB_GETTEXT_DOMAIN )
				) 
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', TB_GETTEXT_DOMAIN ),
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
				'name'		=> __( 'Categories', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select the categories you\'d like to pull posts from. Note that selecting "All Categories" will override any other selections.', TB_GETTEXT_DOMAIN ),
				'std'		=> array( 'all' => 1 ),
				'type'		=> 'multicheck',
				'options'	=> $categories_multicheck
			),
			array(
		    	'id' 		=> 'columns',
				'name'		=> __( 'Columns', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select how many posts per row you\'d like displayed.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> '3',
				'options'	=> array(
			        '2' 	=> __( '2 Columns', TB_GETTEXT_DOMAIN ),
			        '3' 	=> __( '3 Columns', TB_GETTEXT_DOMAIN ),
			        '4' 	=> __( '4 Columns', TB_GETTEXT_DOMAIN ),
			        '5' 	=> __( '5 Columns', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'rows',
				'name'		=> __( 'Rows per page', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter in the number of rows <strong>per page</strong> you\'d like to show. The number you enter here will be multiplied by the amount of columns you selected in the previous option to figure out how many posts should be showed on each page. You can leave this option blank if you\'d like to show all posts from the categories you\'ve selected on a single page.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> '3'
			),
			array(
		    	'id' 		=> 'orderby',
				'name'		=> __( 'Order By', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select what attribute you\'d like the posts ordered by.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> '3',
				'options'	=> array(
			        'post_date' 	=> __( 'Publish Date', TB_GETTEXT_DOMAIN ),
			        'title' 		=> __( 'Post Title', TB_GETTEXT_DOMAIN ),
			        'comment_count' => __( 'Number of Comments', TB_GETTEXT_DOMAIN ),
			        'rand' 			=> __( 'Random', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'order',
				'name'		=> __( 'Order', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select the order in which you\'d like the posts displayed based on the previous orderby parameter.<br><br><em>Note that a traditional WordPress setup would have posts ordered by <strong>Publish Date</strong> and be ordered <strong>Descending</strong>.</em>', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> 'DESC',
				'options'	=> array(
			        'DESC' 	=> __( 'Descending (highest to lowest)', TB_GETTEXT_DOMAIN ),
			        'ASC' 	=> __( 'Ascending (lowest to highest)', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'query',
				'name'		=> __( 'Custom Query String (optional)', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter in a <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Parameters">custom query string</a>. This will override any other query-related options.<br><br>Ex: tag=cooking<br>Ex: post_type=XYZ<br><br><em>Note: Putting anything in this option will cause the following options to not have any effect: Categories, Orderby, and Order. Additionally, you can\'t use "posts_per_page" as this is generated in a grid based on the rows and columns.</em>', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> ''
			),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', TB_GETTEXT_DOMAIN ),
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
				'name'		=> __( 'Transition Effect', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select the effect you\'d like used to transition from one slide to the next.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> 'slide',
				'options'	=> array(
			        'fade' 	=> 'Fade',
					'slide'	=> 'Slide'
				)
			),
			array(
		    	'id' 		=> 'timeout',
				'name'		=> __( 'Speed', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter the number of seconds you\'d like in between trasitions. You may use <em>0</em> to disable the slider from auto advancing.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> '0'
			),
			array(
				'id'		=> 'nav_standard',
				'name'		=> __( 'Show standard slideshow navigation?', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'The standard navigation are the little dots that appear below the slider.' , TB_GETTEXT_DOMAIN ),
				'std'		=> '1',
				'type'		=> 'select',
				'options'		=> array(
		            '1'	=> __( 'Yes, show navigation.', TB_GETTEXT_DOMAIN ),
		            '0'	=> __( 'No, don\'t show it.', TB_GETTEXT_DOMAIN )
				)
			),
			array(
				'id'		=> 'nav_arrows',
				'name'		=> __( 'Show next/prev slideshow arrows?', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'These arrows allow the user to navigation from one slide to the next.' , TB_GETTEXT_DOMAIN ),
				'std'		=> '1',
				'type'		=> 'select',
				'options'		=> array(
		            '1'	=> __( 'Yes, show arrows.', TB_GETTEXT_DOMAIN ),
		            '0'	=> __( 'No, don\'t show them.', TB_GETTEXT_DOMAIN )
				)
			),
			array(
				'id'		=> 'pause_play',
				'name'		=> __( 'Show pause/play button?', TB_GETTEXT_DOMAIN ),
				'desc'		=> __('Note that if you have the speed set to 0, this option will be ignored. ', TB_GETTEXT_DOMAIN ),
				'std'		=> '1',
				'type'		=> 'select',
				'options'		=> array(
		            '1'	=> __( 'Yes, show pause/play button.', TB_GETTEXT_DOMAIN ),
		            '0'	=> __( 'No, don\'t show it.', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'categories',
				'name'		=> __( 'Categories', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select the categories you\'d like to pull posts from. Note that selecting "All Categories" will override any other selections.', TB_GETTEXT_DOMAIN ),
				'std'		=> array( 'all' => 1 ),
				'type'		=> 'multicheck',
				'options'	=> $categories_multicheck
			),
			array(
		    	'id' 		=> 'columns',
				'name'		=> __( 'Columns', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select how many posts per row you\'d like displayed.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> '3',
				'options'	=> array(
			        '2' 	=> __( '2 Columns', TB_GETTEXT_DOMAIN ),
			        '3' 	=> __( '3 Columns', TB_GETTEXT_DOMAIN ),
			        '4' 	=> __( '4 Columns', TB_GETTEXT_DOMAIN ),
			        '5' 	=> __( '5 Columns', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'rows',
				'name'		=> __( 'Rows per slide', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter in the number of rows <strong>per slide</strong> you\'d like to show. The number you enter here will be multiplied by the amount of columns you selected in the previous option to figure out how many posts should be showed on each slide.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> '3'
			),
			array(
		    	'id' 		=> 'numberposts',
				'name'		=> __( 'Total Number of Posts', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter the maximum number of posts you\'d like to show from the categories selected. You can use <em>-1</em> to show all posts from the selected categories.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> '-1'
			),
			array(
		    	'id' 		=> 'orderby',
				'name'		=> __( 'Order By', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select what attribute you\'d like the posts ordered by.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> 'post_date',
				'options'	=> array(
			        'post_date' 	=> __( 'Publish Date', TB_GETTEXT_DOMAIN ),
			        'title' 		=> __( 'Post Title', TB_GETTEXT_DOMAIN ),
			        'comment_count' => __( 'Number of Comments', TB_GETTEXT_DOMAIN ),
			        'rand' 			=> __( 'Random', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'order',
				'name'		=> __( 'Order', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select the order in which you\'d like the posts displayed based on the previous orderby parameter.<br><br><em>Note that a traditional WordPress setup would have posts ordered by <strong>Publish Date</strong> and be ordered <strong>Descending</strong>.</em>', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> 'DESC',
				'options'	=> array(
			        'DESC' 	=> __( 'Descending (highest to lowest)', TB_GETTEXT_DOMAIN ),
			        'ASC' 	=> __( 'Ascending (lowest to highest)', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'offset',
				'name'		=> __( 'Offset', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter the number of posts you\'d like to offset the query by. In most cases, you will just leave this at <em>0</em>. Utilizing this option could be useful, for example, if you wanted to have the first post in an element above this one, and then you could offset this set by <em>1</em> so the posts start after that post in the previous element. If that makes no sense, just ignore this option and leave it at <em>0</em>!', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> '0'
			),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', TB_GETTEXT_DOMAIN ),
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
				'name'		=> __( 'Categories', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select the categories you\'d like to pull posts from. Note that selecting "All Categories" will override any other selections.', TB_GETTEXT_DOMAIN ),
				'std'		=> array( 'all' => 1 ),
				'type'		=> 'multicheck',
				'options'	=> $categories_multicheck
			),
			array(
				'id' 		=> 'thumbs',
				'name' 		=> __( 'Featured Images', TB_GETTEXT_DOMAIN ), /* Required by Framework */
				'desc' 		=> __( 'Select the size of the post list\'s thumbnails or whether you\'d like to hide them all together when posts are listed.', TB_GETTEXT_DOMAIN ),
				'std' 		=> 'default',
				'type' 		=> 'select',
				'options' 	=> array(
					'default'	=> __( 'Use default blog setting.', TB_GETTEXT_DOMAIN ),
					'small'		=> __( 'Show small thumbnails.', TB_GETTEXT_DOMAIN ),
					'full' 		=> __( 'Show full-width thumbnails.', TB_GETTEXT_DOMAIN ),
					'hide' 		=> __( 'Hide thumbnails.', TB_GETTEXT_DOMAIN )
				)
			),
			array( 
				'id' 		=> 'content',
				'name' 		=> __( 'Show excerpts of full content?', TB_GETTEXT_DOMAIN ), /* Required by Framework */
				'desc' 		=> __( 'Choose whether you want to show full content or post excerpts only.', TB_GETTEXT_DOMAIN ),
				'std' 		=> 'default',
				'type' 		=> 'select',
				'options' 	=> array(
					'default'	=> __( 'Use default blog setting.', TB_GETTEXT_DOMAIN ),
					'content'	=> __( 'Show full content.', TB_GETTEXT_DOMAIN ),
					'excerpt' 	=> __( 'Show excerpt only.', TB_GETTEXT_DOMAIN )
				) 
			),
			array(
		    	'id' 		=> 'numberposts',
				'name'		=> __( 'Number of Posts', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter in the <strong>total number</strong> of posts you\'d like to show. You can enter <em>-1</em> if you\'d like to show all posts from the categories you\'ve selected.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> '6'
			),
			array(
		    	'id' 		=> 'orderby',
				'name'		=> __( 'Order By', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select what attribute you\'d like the posts ordered by.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> 'post_date',
				'options'	=> array(
			        'post_date' 	=> __( 'Publish Date', TB_GETTEXT_DOMAIN ),
			        'title' 		=> __( 'Post Title', TB_GETTEXT_DOMAIN ),
			        'comment_count' => __( 'Number of Comments', TB_GETTEXT_DOMAIN ),
			        'rand' 			=> __( 'Random', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'order',
				'name'		=> __( 'Order', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select the order in which you\'d like the posts displayed based on the previous orderby parameter.<br><br><em>Note that a traditional WordPress setup would have posts ordered by <strong>Publish Date</strong> and be ordered <strong>Descending</strong>.</em>', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> 'DESC',
				'options'	=> array(
			        'DESC' 	=> __( 'Descending (highest to lowest)', TB_GETTEXT_DOMAIN ),
			        'ASC' 	=> __( 'Ascending (lowest to highest)', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'offset',
				'name'		=> __( 'Offset', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter the number of posts you\'d like to offset the query by. In most cases, you will just leave this at <em>0</em>. Utilizing this option could be useful, for example, if you wanted to have the first post in an element above this one, and then you could offset this set by <em>1</em> so the posts start after that post in the previous element. If that makes no sense, just ignore this option and leave it at <em>0</em>!', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> '0'
			),
			array(
		    	'id' 		=> 'query',
				'name'		=> __( 'Custom Query String (optional)', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter in a <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Parameters">custom query string</a>. This will override any other query-related options.<br><br>Ex: tag=cooking<br>Ex: post_type=XYZ&numberposts=10<br><br><em>Note: Putting anything in this option will cause the following options to not have any effect: Categories, Number of Posts, Orderby, Order, and Offset</em>', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'show-hide'
		    ),
			array(
		    	'id' 		=> 'link',
				'name'		=> __( 'Link', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Show link after posts to direct visitors somewhere?', TB_GETTEXT_DOMAIN ),
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			array(
				'id' 		=> 'link_text',
				'name'		=> __( 'Link Text', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter the text for the link.', TB_GETTEXT_DOMAIN ),
				'std'		=> 'View All Posts',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'link_url',
				'name'		=> __( 'Link URL', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter the full URL where you want this link to go to.', TB_GETTEXT_DOMAIN ),
				'std'		=> 'http://www.your-site.com/your-blog-page',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'link_target',
				'name'		=> __( 'Link Target', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select how you want the link to open.', TB_GETTEXT_DOMAIN ),
				'std'		=> '_self',
				'type'		=> 'select',
				'class'		=> 'hide receiver',
				'options'		=> array(
			        '_self' 	=> __( 'Same Window', TB_GETTEXT_DOMAIN ),
			        '_blank' 	=> __( 'New Window', TB_GETTEXT_DOMAIN )
				) 
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', TB_GETTEXT_DOMAIN ),
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
				'name'		=> __( 'Categories', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select the categories you\'d like to pull posts from. Note that selecting "All Categories" will override any other selections.', TB_GETTEXT_DOMAIN ),
				'std'		=> array( 'all' => 1 ),
				'type'		=> 'multicheck',
				'options'	=> $categories_multicheck
			),
			array(
				'id' 		=> 'thumbs',
				'name' 		=> __( 'Featured Images', TB_GETTEXT_DOMAIN ),
				'desc' 		=> __( 'Select the size of the post list\'s thumbnails or whether you\'d like to hide them all together when posts are listed.', TB_GETTEXT_DOMAIN ),
				'std' 		=> 'default',
				'type' 		=> 'select',
				'options' 	=> array(
					'default'	=> __( 'Use default blog setting.', TB_GETTEXT_DOMAIN ),
					'small'		=> __( 'Show small thumbnails.', TB_GETTEXT_DOMAIN ),
					'full' 		=> __( 'Show full-width thumbnails.', TB_GETTEXT_DOMAIN ),
					'hide' 		=> __( 'Hide thumbnails.', TB_GETTEXT_DOMAIN )
				)
			),
			array( 
				'id' 		=> 'content',
				'name' 		=> __( 'Show excerpts of full content?', TB_GETTEXT_DOMAIN ), /* Required by Framework */
				'desc' 		=> __( 'Choose whether you want to show full content or post excerpts only.', TB_GETTEXT_DOMAIN ),
				'std' 		=> 'default',
				'type' 		=> 'select',
				'options' 	=> array(
					'default'	=> __( 'Use default blog setting.', TB_GETTEXT_DOMAIN ),
					'content'	=> __( 'Show full content.', TB_GETTEXT_DOMAIN ),
					'excerpt' 	=> __( 'Show excerpt only.', TB_GETTEXT_DOMAIN )
				) 
			),
			array(
		    	'id' 		=> 'posts_per_page',
				'name'		=> __( 'Posts per page', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter in the number of posts <strong>per page</strong> you\'d like to show. You can enter <em>-1</em> if you\'d like to show all posts from the categories you\'ve selected on a single page.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> '6'
			),
			array(
		    	'id' 		=> 'orderby',
				'name'		=> __( 'Order By', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select what attribute you\'d like the posts ordered by.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> '3',
				'options'	=> array(
			        'post_date' 	=> __( 'Publish Date', TB_GETTEXT_DOMAIN ),
			        'title' 		=> __( 'Post Title', TB_GETTEXT_DOMAIN ),
			        'comment_count' => __( 'Number of Comments', TB_GETTEXT_DOMAIN ),
			        'rand' 			=> __( 'Random', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'order',
				'name'		=> __( 'Order', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select the order in which you\'d like the posts displayed based on the previous orderby parameter.<br><br><em>Note that a traditional WordPress setup would have posts ordered by <strong>Publish Date</strong> and be ordered <strong>Descending</strong>.</em>', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> 'DESC',
				'options'	=> array(
			        'DESC' 	=> __( 'Descending (highest to lowest)', TB_GETTEXT_DOMAIN ),
			        'ASC' 	=> __( 'Ascending (lowest to highest)', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'query',
				'name'		=> __( 'Custom Query String (optional)', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter in a <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Parameters">custom query string</a>. This will override any other query-related options.<br><br>Ex: tag=cooking<br>Ex: post_type=XYZ&posts_per_page=10<br><br><em>Note: Putting anything in this option will cause the following options to not have any effect: Categories, Posts per page, Orderby, and Order</em>', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> ''
			),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', TB_GETTEXT_DOMAIN ),
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
				'name'		=> __( 'Transition Effect', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select the effect you\'d like used to transition from one slide to the next.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> 'slide',
				'options'	=> array(
			        'fade' 	=> 'Fade',
					'slide'	=> 'Slide'
				)
			),
			array(
		    	'id' 		=> 'timeout',
				'name'		=> __( 'Speed', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter the number of seconds you\'d like in between trasitions. You may use <em>0</em> to disable the slider from auto advancing.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> '0'
			),
			array(
				'id'		=> 'nav_standard',
				'name'		=> __( 'Show standard slideshow navigation?', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'The standard navigation are the little dots that appear below the slider.' , TB_GETTEXT_DOMAIN ),
				'std'		=> '1',
				'type'		=> 'select',
				'options'		=> array(
		            '1'	=> __( 'Yes, show navigation.', TB_GETTEXT_DOMAIN ),
		            '0'	=> __( 'No, don\'t show it.', TB_GETTEXT_DOMAIN )
				)
			),
			array(
				'id'		=> 'nav_arrows',
				'name'		=> __( 'Show next/prev slideshow arrows?', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'These arrows allow the user to navigation from one slide to the next.' , TB_GETTEXT_DOMAIN ),
				'std'		=> '1',
				'type'		=> 'select',
				'options'		=> array(
		            '1'	=> __( 'Yes, show arrows.', TB_GETTEXT_DOMAIN ),
		            '0'	=> __( 'No, don\'t show them.', TB_GETTEXT_DOMAIN )
				)
			),
			array(
				'id'		=> 'pause_play',
				'name'		=> __( 'Show pause/play button?', TB_GETTEXT_DOMAIN ),
				'desc'		=> __('Note that if you have the speed set to 0, this option will be ignored. ', TB_GETTEXT_DOMAIN ),
				'std'		=> '1',
				'type'		=> 'select',
				'options'		=> array(
		            '1'	=> __( 'Yes, show pause/play button.', TB_GETTEXT_DOMAIN ),
		            '0'	=> __( 'No, don\'t show it.', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'categories',
				'name'		=> __( 'Categories', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select the categories you\'d like to pull posts from. Note that selecting "All Categories" will override any other selections.', TB_GETTEXT_DOMAIN ),
				'std'		=> array( 'all' => 1 ),
				'type'		=> 'multicheck',
				'options'	=> $categories_multicheck
			),
			array(
				'id' 		=> 'thumbs',
				'name' 		=> __( 'Featured Images', TB_GETTEXT_DOMAIN ), /* Required by Framework */
				'desc' 		=> __( 'Select the size of the post list\'s thumbnails or whether you\'d like to hide them all together when posts are listed.', TB_GETTEXT_DOMAIN ),
				'std' 		=> 'default',
				'type' 		=> 'select',
				'options' 	=> array(
					'default'	=> __( 'Use default blog setting.', TB_GETTEXT_DOMAIN ),
					'small'		=> __( 'Show small thumbnails.', TB_GETTEXT_DOMAIN ),
					'full' 		=> __( 'Show full-width thumbnails.', TB_GETTEXT_DOMAIN ),
					'hide' 		=> __( 'Hide thumbnails.', TB_GETTEXT_DOMAIN )
				)
			),
			array( 
				'id' 		=> 'content',
				'name' 		=> __( 'Show excerpts of full content?', TB_GETTEXT_DOMAIN ), /* Required by Framework */
				'desc' 		=> __( 'Choose whether you want to show full content or post excerpts only.', TB_GETTEXT_DOMAIN ),
				'std' 		=> 'default',
				'type' 		=> 'select',
				'options' 	=> array(
					'default'	=> __( 'Use default blog setting.', TB_GETTEXT_DOMAIN ),
					'content'	=> __( 'Show full content.', TB_GETTEXT_DOMAIN ),
					'excerpt' 	=> __( 'Show excerpt only.', TB_GETTEXT_DOMAIN )
				) 
			),
			array(
		    	'id' 		=> 'posts_per_slide',
				'name'		=> __( 'Posts per slide', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter in the number of posts <strong>per slide</strong> you\'d like to show.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> '3'
			),
			array(
		    	'id' 		=> 'numberposts',
				'name'		=> __( 'Total Number of Posts', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter the maximum number of posts you\'d like to show from the categories selected. You can use <em>-1</em> to show all posts from the selected categories.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> '-1'
			),
			array(
		    	'id' 		=> 'orderby',
				'name'		=> __( 'Order By', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select what attribute you\'d like the posts ordered by.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> '3',
				'options'	=> array(
			        'post_date' 	=> __( 'Publish Date', TB_GETTEXT_DOMAIN ),
			        'title' 		=> __( 'Post Title', TB_GETTEXT_DOMAIN ),
			        'comment_count' => __( 'Number of Comments', TB_GETTEXT_DOMAIN ),
			        'rand' 			=> __( 'Random', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'order',
				'name'		=> __( 'Order', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select the order in which you\'d like the posts displayed based on the previous orderby parameter.<br><br><em>Note that a traditional WordPress setup would have posts ordered by <strong>Publish Date</strong> and be ordered <strong>Descending</strong>.</em>', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> 'DESC',
				'options'	=> array(
			        'DESC' 	=> __( 'Descending (highest to lowest)', TB_GETTEXT_DOMAIN ),
			        'ASC' 	=> __( 'Ascending (lowest to highest)', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'offset',
				'name'		=> __( 'Offset', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter the number of posts you\'d like to offset the query by. In most cases, you will just leave this at <em>0</em>. Utilizing this option could be useful, for example, if you wanted to have the first post in an element above this one, and then you could offset this set by <em>1</em> so the posts start after that post in the previous element. If that makes no sense, just ignore this option and leave it at <em>0</em>!', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> '0'
			),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', TB_GETTEXT_DOMAIN ),
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
				'name'		=> __( 'Choose Slider', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Choose from the sliders you\'ve created. You can edit these sliders at any time under the \'Sliders\' tab above.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'options'	=> $sliders_select
			),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', TB_GETTEXT_DOMAIN ),
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
				'name' 		=> __( 'Setup Slogan', TB_GETTEXT_DOMAIN),
				'desc'		=> __( 'Enter the text you\'d like to show.', TB_GETTEXT_DOMAIN),
				'type'		=> 'textarea'
		    ),
		    array(
		    	'type'		=> 'subgroup_start',
		    	'class'		=> 'show-hide'
		    ),
			array(
		    	'id' 		=> 'button',
				'name'		=> __( 'Button', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Show call-to-action button next to slogan?', TB_GETTEXT_DOMAIN ),
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			array(
				'id' 		=> 'button_text',
				'name'		=> __( 'Button Text', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter the text for the button.', TB_GETTEXT_DOMAIN ),
				'std'		=> 'Get Started Today!',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'button_color',
				'name'		=> __( 'Button Color', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select what color you\'d like to use for this button.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'class'		=> 'hide receiver',
				'options'	=> themeblvd_colors()
			),
			array(
				'id' 		=> 'button_url',
				'name'		=> __( 'Link URL', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter the full URL where you want the button\'s link to go.', TB_GETTEXT_DOMAIN ),
				'std'		=> 'http://www.your-site.com/your-landing-page',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
			),
			array(
				'id' 		=> 'button_target',
				'name'		=> __( 'Link Target', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select how you want the button to open the webpage.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'class'		=> 'hide receiver',
				'options'		=> array(
			        '_self' 	=> __( 'Same Window', TB_GETTEXT_DOMAIN ),
			        '_blank' 	=> __( 'New Window', TB_GETTEXT_DOMAIN )
				) 
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', TB_GETTEXT_DOMAIN ),
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
				'name'		=> __( 'Setup Tabs', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Choose the number of tabs along with inputting a name for each one. These names are what will appear on the actual tab buttons across the top of the tab set.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'tabs'
			),
			array(
				'id' 		=> 'height',
				'name'		=> __( 'Fixed Height', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter in a number of pixels for a fixed height if you\'d like to do so. Ex: 400<br><br>This can help with "page jumping" in the case that not all tabs have equal amount of content. It can also help in the case when you\'re getting unwanted scrollbars on the inner content areas of tabs. This is optional.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text' 
			),
			array(
				'id' 		=> 'tab_1',
				'name'		=> __( 'Tab #1 Content', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the first tab.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_2',
				'name'		=> __( 'Tab #2 Content', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the second tab.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_3',
				'name'		=> __( 'Tab #3 Content', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the third tab.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_4',
				'name'		=> __( 'Tab #4 Content', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the fourth tab.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_5',
				'name'		=> __( 'Tab #5 Content', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the fifth tab.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_6',
				'name'		=> __( 'Tab #6 Content', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the sixth tab.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_7',
				'name'		=> __( 'Tab #7 Content', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the seventh tab.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_8',
				'name'		=> __( 'Tab #8 Content', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the eighth tab.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_9',
				'name'		=> __( 'Tab #9 Content', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the ninth tab.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_10',
				'name'		=> __( 'Tab #10 Content', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the tenth tab.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_11',
				'name'		=> __( 'Tab #11 Content', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the eleventh tab.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
				'id' 		=> 'tab_12',
				'name'		=> __( 'Tab #12 Content', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Configure the content for the twelfth tab.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'content',
				'options'	=> array( 'page', 'raw', 'widget' )
			),
			array(
		    	'type'		=> 'subgroup_end'
		    ),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', TB_GETTEXT_DOMAIN ),
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
				'name'		=> __( 'Twitter Account', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter the Twitter username you\'d like to pull the most recent tweet from.', TB_GETTEXT_DOMAIN ),
				'std'		=> 'themeblvd',
				'type'		=> 'text'
			),
			array(
		    	'id' 		=> 'icon',
				'name'		=> __( 'Icon', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Enter any Font Awesome icon ID; this icon will then display next to the Tweet. Set this option blank to not use any icon. Examples: twitter, pencil, warning-sign, etc. ', TB_GETTEXT_DOMAIN ),
				'type'		=> 'text',
				'std'		=> 'twitter'
			),
			array(
		    	'id' 		=> 'meta',
				'name'		=> __( 'Tweet Meta Info', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select whether you\'d like information about the current tweet displayed below it.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> 'show',
				'options'	=> array(
			        'show' 	=> __( 'Show meta info below tweet.', TB_GETTEXT_DOMAIN ),
			        'hide' 	=> __( 'Hide meta info below tweet.', TB_GETTEXT_DOMAIN )
				)
			),
			array(
		    	'id' 		=> 'replies',
				'name'		=> __( 'Exclude @replies?', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select whether or not you\'d like to exclude @replies for the current tweet.', TB_GETTEXT_DOMAIN ),
				'type'		=> 'select',
				'std'		=> 'no',
				'options'	=> array(
			        'yes' 	=> __( 'Yes, exclude @replies.', TB_GETTEXT_DOMAIN ),
			        'no' 	=> __( 'No, do not exclude @replies.', TB_GETTEXT_DOMAIN )
				)
			),
		    array(
		    	'id' 		=> 'visibility',
				'name'		=> __( 'Responsive Visibility ', TB_GETTEXT_DOMAIN ),
				'desc'		=> __( 'Select any resolutions you\'d like to <em>hide</em> this element on. This optional, but can be utilized to deliver different content to different devices.<br><br><em>Example: Hide an element on tablets and mobile devices & then create a second element that\'s hidden only on standard screen resolutions to take its place.</em>', TB_GETTEXT_DOMAIN ),
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
					'desc' 	=> __( 'Row of columns with custom content', TB_GETTEXT_DOMAIN )
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
					'desc' 	=> __( 'Content from external page or current page', TB_GETTEXT_DOMAIN )
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
					'desc' 	=> __( 'Simple divider line to break up content', TB_GETTEXT_DOMAIN )
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
					'desc' 	=> __( 'Simple <H> header title', TB_GETTEXT_DOMAIN )
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
					'desc' 	=> __( 'Full paginated grid of posts', TB_GETTEXT_DOMAIN )
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
					'desc' 	=> __( 'Grid of posts followed by optional link', TB_GETTEXT_DOMAIN )
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
					'desc' 	=> __( 'Slider of posts in a grid', TB_GETTEXT_DOMAIN )
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
					'desc' 	=> __( 'Full paginated list of posts', TB_GETTEXT_DOMAIN )
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
					'desc' 	=> __( 'List of posts followed by optional link', TB_GETTEXT_DOMAIN )
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
					'desc' 	=> __( 'Slider of posts listed out', TB_GETTEXT_DOMAIN )
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
					'desc' 	=> __( 'User-built slideshow', TB_GETTEXT_DOMAIN )
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
					'desc' 	=> __( 'Slogan with optional button', TB_GETTEXT_DOMAIN )
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
					'desc' 	=> __( 'Set of tabbed content', TB_GETTEXT_DOMAIN )
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
					'desc' 	=> __( 'Shows the most recent tweet from a Twitter account', TB_GETTEXT_DOMAIN )
				),
				'options' => $element_tweet
			)
		);
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