<?php
/**
 * Add page and post meta boxes.
 *
 * @since 2.0.1
 */
function themeblvd_add_meta_boxes() {

	global $_themeblvd_page_meta_box;
	global $_themeblvd_layout_meta_box;
	global $_themeblvd_post_template_meta_box;
	global $_themeblvd_post_meta_box;

	// General page meta box
	if ( themeblvd_supports( 'meta', 'page_options' ) ) {
		$meta = setup_themeblvd_page_meta();
		$_themeblvd_page_meta_box = new Theme_Blvd_Meta_Box( $meta['config']['id'], $meta['config'], $meta['options'] );
	}

	// Theme Layout meta box (posts and pages)
	if ( themeblvd_supports( 'meta', 'layout' ) ) {
		$meta = setup_themeblvd_layout_meta();
		$_themeblvd_layout_meta_box = new Theme_Blvd_Meta_Box( $meta['config']['id'], $meta['config'], $meta['options'] );
	}

	// Banner meta box (posts and pages)
	if ( themeblvd_supports( 'meta', 'banner' ) && themeblvd_supports( 'display', 'banner' ) ) {
		$meta = setup_themeblvd_banner_meta();
		$_themeblvd_banner_meta_box = new Theme_Blvd_Meta_Box( $meta['config']['id'], $meta['config'], $meta['options'] );
	}

	// "Post Grid" and "Post List" page template meta box
	if ( themeblvd_supports( 'meta', 'pto' ) ) {
		$meta = setup_themeblvd_pto_meta();
		$_themeblvd_post_template_meta_box = new Theme_Blvd_Meta_Box( $meta['config']['id'], $meta['config'], $meta['options'] );
	}

	// General post meta box
	if ( themeblvd_supports( 'meta', 'post_options' ) ) {
		$meta = setup_themeblvd_post_meta();
		$_themeblvd_post_meta_box = new Theme_Blvd_Meta_Box( $meta['config']['id'], $meta['config'], $meta['options'] );
	}

}

/**
 * Get settings for the Page Options meta box.
 *
 * @since 2.0.0
 *
 * @return $setup filterable options for metabox
 */
function setup_themeblvd_page_meta() {

	$setup = array(
		'config' => array(
			'id' 		=> 'tb_page_options',						// make it unique
			'title' 	=> __( 'Page Options', 'themeblvd' ),		// title to show for entire meta box
			'page'		=> array( 'page' ),							// can contain post, page, link, or custom post type's slug
			'context' 	=> 'normal',								// normal, advanced, or side
			'priority'	=> 'high'									// high, core, default, or low
		),
		'options' => array(
			'tb_title' => array(
				'id'		=> '_tb_title',
				'name' 		=> __( 'Page Title', 'themeblvd' ),
				'desc'		=> __( 'This option will be ignored if you\'ve applied the "Custom Layout" template.', 'themeblvd' ),
				'type' 		=> 'select',
				'options'	=> array(
					'show' 		=> __( 'Show page\'s title above content', 'themeblvd' ),
					'hide' 		=> __( 'Hide page\'s title above content', 'themeblvd' )
				)
			),
			'tb_breadcrumbs' => array(
				'id'		=> '_tb_breadcrumbs',
				'name' 		=> __( 'Breadcrumbs', 'themeblvd' ),
				'desc'		=> __( 'Select whether you\'d like breadcrumbs to show on this page or not. This option will be ignored if you\'ve applied the "Custom Layout" or "Blank Page" templates.', 'themeblvd' ),
				'type' 		=> 'select',
				'options'	=> array(
					'default' 	=> __( 'Use default setting', 'themeblvd' ),
					'show' 		=> __( 'Yes, show breadcrumbs', 'themeblvd' ),
					'hide' 		=> __( 'No, hide breadcrumbs', 'themeblvd' )
				)
			),
			'section_start' => array(
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'tb_thumb_link' => array(
				'id'		=> '_tb_thumb_link',
				'name' 		=> __( 'Featured Image Link', 'themeblvd' ),
				'desc'		=> __( 'Here you can select how you\'d like this page\'s featured image to react when clicked, if you\'ve set one.', 'themeblvd' ),
				'type' 		=> 'radio',
				'std'		=> 'inactive',
				'options'	=> array(
					'inactive'	=> __( 'Featured image is not a link', 'themeblvd' ),
					'thumbnail' => __( 'It links to its enlarged lightbox version', 'themeblvd' ),
					'image' 	=> __( 'It links to a custom lightbox image', 'themeblvd' ),
					'video' 	=> __( 'It links to a lightbox video', 'themeblvd' ),
					'external' 	=> __( 'It links to a webpage', 'themeblvd' ),
				),
				'class'		=> 'trigger'
			),
			'tb_image_link' => array(
				'id'		=> '_tb_image_link',
				'name' 		=> __( 'Featured Image - Image Link', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL of enlarged image that the featured image will link to.<br><br>Ex: http://your-site.com/uploads/image.jpg', 'themeblvd' ),
				'type' 		=> 'text',
				'class'		=> 'hide receiver receiver-image'
			),
			'tb_video_link' => array(
				'id'		=> '_tb_video_link',
				'name' 		=> __( 'Featured Image - Video Link', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL to a video page supported by <a href="http://codex.wordpress.org/Embeds" target="_blank">WordPress\'s oEmbed</a>.<br><br>Ex: http://www.youtube.com/watch?v=ginTCwWfGNY<br>Ex: http://vimeo.com/11178250', 'themeblvd' ),
				'type' 		=> 'text',
				'class'		=> 'hide receiver receiver-video'
			),
			'tb_external_link' => array(
				'id'		=> '_tb_external_link',
				'name' 		=> __( 'Featured Image - External Link', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL of where the featured image will link.<br><br>Ex: http://google.com', 'themeblvd' ),
				'type' 		=> 'text',
				'class'		=> 'hide receiver receiver-external'
			),
			'tb_external_link_target' => array(
				'id'		=> '_tb_external_link_target',
				'name' 		=> __( 'Featured Image - External Link Target', 'themeblvd' ),
				'desc'		=> __( 'Select whether you\'d like the external link to open in a new window or not.', 'themeblvd' ),
				'type' 		=> 'radio',
				'std'		=> '_blank',
				'options'	=> array(
					'_blank'	=> __( 'Open link in new window', 'themeblvd' ),
					'_self' 	=> __( 'Open link in same window', 'themeblvd' )
				),
				'class'		=> 'hide receiver receiver-external'
			),
			'section_end' => array(
				'type' 		=> 'subgroup_end'
			)
		)
	);

	if ( ! themeblvd_supports('display', 'hide_top') && ! themeblvd_supports('display', 'hide_bottom') ) {
		unset( $setup['options']['tb_theme_layout'] );
	} else if ( ! themeblvd_supports('display', 'hide_top') ) {
		unset( $setup['options']['tb_theme_layout']['options']['hide_top'] );
	} else if ( ! themeblvd_supports('display', 'hide_bottom') ) {
		unset( $setup['options']['tb_theme_layout']['options']['hide_bottom'] );
	}

	return apply_filters( 'themeblvd_page_meta', $setup );
}

/**
 * Get settings for the "Layout" meta box.
 *
 * @since 2.5.0
 *
 * @return $setup filterable options for metabox
 */
function setup_themeblvd_layout_meta() {

	$setup = array(
		'config' => array(
			'id' 		=> 'tb_layout_options',						// make it unique
			'title' 	=> __( 'Theme Layout', 'themeblvd' ),		// title to show for entire meta box
			'page'		=> array( 'page', 'post' ),					// can contain post, page, link, or custom post type's slug
			'context' 	=> 'side',									// normal, advanced, or side
			'priority'	=> 'core'									// high, core, default, or low
		),
		'options' => array(
			'layout_header' => array(
				'id'		=> '_tb_layout_header',
				'name' 		=> __( 'Header', 'themeblvd' ),
				'desc'		=> __( 'Note: The transparent header option will work better when a banner or custom layout is applied to the page.', 'themeblvd' ).' <a href="https://vimeo.com/118959469" target="_blank">'.__('Learn More', 'themeblvd').'</a>',
				'type' 		=> 'select',
				'options'	=> array(
					'default'		=> __( 'Standard Header', 'themeblvd' ),
					'suck_up'		=> __( 'Transparent Header', 'themeblvd' ),
					'hide'			=> __( 'Hide Header', 'themeblvd' )
				)
			),
			'layout_footer' => array(
				'id'		=> '_tb_layout_footer',
				'name' 		=> __( 'Footer', 'themeblvd' ),
				'type' 		=> 'select',
				'options'	=> array(
					'default'		=> __( 'Standard Footer', 'themeblvd' ),
					'hide'			=> __( 'Hide Footer', 'themeblvd' )
				)
			)
		)
	);

	if ( ! themeblvd_supports('display', 'suck_up') && ! themeblvd_supports('display', 'hide_top') ) {
		unset($setup['options']['tb_layout_header']);
	} else if ( ! themeblvd_supports('display', 'suck_up') ) {
		unset($setup['options']['tb_layout_header']['options']['suck_up']);
		unset($setup['options']['tb_layout_header']['desc']);
	} else if ( ! themeblvd_supports('display', 'hide_top') ) {
		unset($setup['options']['tb_layout_header']['options']['hide']);
	}

	if ( ! themeblvd_supports('display', 'hide_bottom') ) {
		unset($setup['options']['tb_layout_footer']);
	}

	return apply_filters( 'themeblvd_layout_meta', $setup );
}

/**
 * Get settings for the "Banner" meta box.
 *
 * @since 2.5.0
 *
 * @return $setup filterable options for metabox
 */
function setup_themeblvd_banner_meta() {

	$setup = array(
		'config' => array(
			'id' 		=> 'tb_banner_options',						// make it unique
			'title' 	=> __( 'Banner', 'themeblvd' ),				// title to show for entire meta box
			'page'		=> array( 'page', 'post' ),					// can contain post, page, link, or custom post type's slug
			'context' 	=> 'normal',								// normal, advanced, or side
			'priority'	=> 'core',									// high, core, default, or low
			'group'		=> '_tb_banner',							// save all option to single meta entry "_tb_banner"
			'textures'	=> true										// uses texture browser in options
		),
		'options' => array(
			'subgroup_start_1' => array(
				'type'		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'bg_type' => array(
				'id'		=> 'bg_type',
				'name'		=> __('Apply Banner', 'themeblvd'),
				'desc'		=> __('Select if you\'d like to apply a custom banner and how you want to set it up.', 'themeblvd'),
				'std'		=> 'none',
				'type'		=> 'select',
				'options'	=> themeblvd_get_bg_types('banner'),
				'class'		=> 'trigger'
			),
			'bg_color' => array(
				'id'		=> 'bg_color',
				'name'		=> __('Background Color', 'themeblvd'),
				'desc'		=> __('Select a background color.', 'themeblvd'),
				'std'		=> '#202020',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-color receiver-texture receiver-image'
			),
			'bg_texture' => array(
				'id'		=> 'bg_texture',
				'name'		=> __('Background Texture', 'themeblvd'),
				'desc'		=> __('Select a background texture.', 'themeblvd'),
				'type'		=> 'select',
				'select'	=> 'textures',
				'class'		=> 'hide receiver receiver-texture'
			),
			'apply_bg_texture_parallax' => array(
				'id'		=> 'apply_bg_texture_parallax',
				'name'		=> null,
				'desc'		=> __('Apply parallax scroll effect to background texture.', 'themeblvd'),
				'type'		=> 'checkbox',
				'class'		=> 'hide receiver receiver-texture'
			),
			'subgroup_start_2' => array(
				'type'		=> 'subgroup_start',
				'class'		=> 'select-parallax hide receiver receiver-image'
			),
			'bg_image' => array(
				'id'		=> 'bg_image',
				'name'		=> __('Background Image', 'themeblvd'),
				'desc'		=> __('Select a background image.', 'themeblvd'),
				'type'		=> 'background',
				'std'		=> array(
					'color'			=> '',
					'image'			=> '',
					'repeat'		=> 'no-repeat',
					'position'		=> 'center center',
					'attachment'	=> 'scroll',
					'size'			=> '100% auto'
				),
				'color'		=> false,
				'parallax'	=> true
			),
			'subgroup_end_2' => array(
				'type'		=> 'subgroup_end'
			),
			'bg_video' => array(
				'id'		=> 'bg_video',
				'name'		=> __('Background Video', 'themeblvd'),
				'desc'		=> __('Setup a background video. For best results, make sure to use all three fields. The <em>.webm</em> file will display in Google Chrome, while the <em>.mp4</em> will display in most other modnern browsers. Your fallback image will display on mobile and in browsers that don\'t support HTML5 video.', 'themeblvd'),
				'type'		=> 'background_video',
				'class'		=> 'hide receiver receiver-video'
			),
			'subgroup_start_3' => array(
				'type'		=> 'subgroup_start',
				'class'		=> 'show-hide hide receiver receiver-image receiver-slideshow receiver-video'
			),
			'apply_bg_shade' => array(
				'id'		=> 'apply_bg_shade',
				'name'		=> null,
				'desc'		=> __('Shade background with transparent color.', 'theme-blvd-layout-builder'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'bg_shade_color' => array(
				'id'		=> 'bg_shade_color',
				'name'		=> __('Shade Color', 'theme-blvd-layout-builder'),
				'desc'		=> __('Select the color you want overlaid on your background.', 'theme-blvd-layout-builder'),
				'std'		=> '#000000',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'bg_shade_opacity' => array(
				'id'		=> 'bg_shade_opacity',
				'name'		=> __('Shade Opacity', 'theme-blvd-layout-builder'),
				'desc'		=> __('Select the opacity of the shade color overlaid on your background.', 'theme-blvd-layout-builder'),
				'std'		=> '0.5',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%'
				),
				'class'		=> 'hide receiver'
			),
			'subgroup_end_3' => array(
				'type'		=> 'subgroup_end'
			),
			'subgroup_start_4' => array(
				'type'		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle hide receiver receiver-color receiver-texture receiver-image receiver-video'
			),
			'headline' => array(
				'id'		=> 'headline',
				'name'		=> __('Banner Headline (optional)', 'themeblvd'),
				'desc'		=> __('Select if you\'d like the banner to contain a headline.', 'themeblvd'),
				'std'		=> 'none',
				'type'		=> 'select',
				'options'	=> array(
					'none'		=> __('No headline', 'themeblvd'),
					'title'		=> __('Display current title', 'themeblvd'),
					'custom'	=> __('Display custom text', 'themeblvd'),
				),
				'class'		=> 'trigger'
			),
			'headline_custom' => array(
				'id'		=> 'headline_custom',
				'name'		=> __('Custom Headline', 'themeblvd'),
				'desc'		=> __('Enter the text for the headline.', 'themeblvd'),
				'std'		=> '',
				'type'		=> 'text',
				'class'		=> 'hide receiver receiver-custom'
			),
			'tagline' => array(
				'id'		=> 'tagline',
				'name'		=> __('Banner Tagline (optional)', 'themeblvd'),
				'desc'		=> __('If you want a brief tagline to appear below the headline, enter it here.', 'themeblvd'),
				'std'		=> '',
				'type'		=> 'text',
				'class'		=> 'hide receiver receiver-title receiver-custom'
			),
			'text_color' => array(
				'id'		=> 'text_color',
				'name'		=> __('Text Color', 'themeblvd'),
				'desc'		=> __('If you\'re using a dark background color, select to show light text, and vice versa.', 'themeblvd_builder'),
				'std'		=> 'light',
				'type'		=> 'select',
				'options'	=> array(
					'dark'		=> __('Dark Text', 'themeblvd'),
					'light'		=> __('Light Text', 'themeblvd')
				),
				'class'		=> 'hide receiver receiver-title receiver-custom'
			),
			'text_align' => array(
				'id'		=> 'text_align',
				'name'		=> __('Text Align', 'themeblvd'),
				'desc'		=> __('Select how to align the text of the headline and tagline.', 'themeblvd'),
				'std'		=> 'left',
				'type'		=> 'select',
				'options'	=> array(
					'left'		=> __('Left', 'themeblvd'),
					'center'	=> __('Center', 'themeblvd'),
					'right'		=> __('Right', 'themeblvd')
				),
				'class'		=> 'hide receiver receiver-title receiver-custom'
			),
			'subgroup_end_4' => array(
				'type' 		=> 'subgroup_end'
			),
			'subgroup_start_5' => array(
				'type'		=> 'subgroup_start',
				'class'		=> 'show-hide hide receiver receiver-color receiver-texture receiver-image receiver-video'
			),
			'height' => array(
				'id'		=> 'height',
				'name' 		=> null,
				'desc' 		=> __( 'Apply custom banner height.', 'themeblvd' ),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
		    ),
			'height_desktop' => array(
				'id'		=> 'height_desktop',
				'name' 		=> __( 'Desktop Height', 'themeblvd' ),
				'desc' 		=> __( 'Banner height (in pixels) when displayed at the standard desktop viewport range.', 'themeblvd' ),
				'std'		=> '200',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
		    ),
		    'height_tablet' => array(
				'id'		=> 'height_tablet',
				'name' 		=> __( 'Tablet Height', 'themeblvd' ),
				'desc' 		=> __( 'Banner height (in pixels) when displayed at the tablet viewport range.', 'themeblvd' ),
				'std'		=> '120',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
		    ),
		    'height_mobile' => array(
				'id'		=> 'height_mobile',
				'name' 		=> __( 'Mobile Height', 'themeblvd' ),
				'desc' 		=> __( 'Banner height (in pixels) when displayed at the mobile viewport range.', 'themeblvd' ),
				'std'		=> '100',
				'type'		=> 'text',
				'class'		=> 'hide receiver'
		    ),
			'subgroup_end_5' => array(
				'type'		=> 'subgroup_end'
			),
			'subgroup_end_1' => array(
				'type'		=> 'subgroup_end'
			)
		)
	);

	return apply_filters( 'themeblvd_banner_meta', $setup );
}

/**
 * Get settings for the Post Grid Template Options meta box.
 *
 * @since 2.5.0
 *
 * @return $setup filterable options for metabox
 */
function setup_themeblvd_pto_meta() {

	$setup = array(
		'config' => array(
			'id' 			=> 'pto',										// make it unique
			'title' 		=> __( 'Post Template Options', 'themeblvd' ),	// title to show for entire meta box
			'page'			=> array('page'),								// can contain post, page, link, or custom post type's slug
			'context' 		=> 'normal',									// normal, advanced, or side
			'priority'  	=> 'low',										// high, core, default, or low
            'save_empty'	=> false										// Whether to save empty values to custom fields
		),
		'options' => array(
			'desc' => array(
                'desc'      => __( 'Below are the custom fields you can use with the Blog, Post List, Post Grid, and Post Showcase page templates. When working with these options, you can find a lot of helpful information by viewing WordPress\'s Codex page on the <a href="http://codex.wordpress.org/Class_Reference/WP_Query" target="_blank">WP Query</a>.', 'themeblvd' ),
                'type'      => 'info'
            ),
            'cat' => array(
                'id'        => 'cat',
                'name'      => __( 'cat', 'themeblvd' ),
                'desc'      => __( 'Category ID(s) to include/exclude.<br>Ex: 1<br>Ex: 1,2,3<br>Ex: -1,-2,-3', 'themeblvd' ),
                'type'      => 'text'
            ),
            'category_name' => array(
                'id'        => 'category_name',
                'name'      => __( 'category_name', 'themeblvd' ),
                'desc'      => __( 'Category slug(s) to include.<br>Ex: cat-1<br>Ex: cat-1,cat-2', 'themeblvd' ),
                'type'      => 'text'
            ),
            'tag' => array(
                'id'        => 'tag',
                'name'      => __( 'tag', 'themeblvd' ),
                'desc'      => __( 'Tag(s) to include.<br>Ex: tag-1<br>Ex: tag-1,tag-2', 'themeblvd' ),
                'type'      => 'text'
            ),
            'posts_per_page' => array(
                'id'        => 'posts_per_page',
                'name'      => __( 'posts_per_page', 'themeblvd' ),
                'desc'      => __( 'Number of posts per page. This option gets used for the Blog template, Post List template, and when using "masonry" style display for the Post Grid and Post Showcase template. Standard grid view for Post Grid and Post Showcase templates use rows*columns.', 'themeblvd' ),
                'type'      => 'text'
            ),
            'orderby' => array(
                'id'        => 'orderby',
                'name'      => __( 'orderby', 'themeblvd' ),
                'desc'      => __( 'What to order posts by -- date, title, rand, etc.<br>(<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">Learn More</a>)', 'themeblvd' ),
                'type'      => 'text'
            ),
            'order' => array(
                'id'        => 'order',
                'name'      => __( 'order', 'themeblvd' ),
                'desc'      => __( 'How to order posts -- ASC or DESC.<br>(<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">Learn More</a>)', 'themeblvd' ),
                'type'      => 'text'
            ),
            'query' => array(
                'id'        => 'query',
                'name'      => __( 'query', 'themeblvd' ),
                'desc'      => __( 'A custom query string. This will override other options.<br>Ex: <small>tag=baking</small><br>Ex: <small>post_type=my_type&my_tax=my_term</small>', 'themeblvd' ),
                'type'      => 'text'
            ),
            'columns' => array(
                'id'        => 'columns',
                'name'      => __( 'columns', 'themeblvd' ),
                'desc'      => __( 'Number of columns for Post Grid or Post Showcase template, which can be 2-5. When empty, this will default to 3.', 'themeblvd' ),
                'type'      => 'text'
            ),
            'rows' => array(
                'id'        => 'rows',
                'name'      => __( 'rows', 'themeblvd' ),
                'desc'      => __( 'Number of rows for Post Grid and Post Showcase templates. When empty, this will default to 4.<br><br><em>Note: This option does not apply when using masonry.</em>', 'themeblvd' ),
                'type'      => 'text'
            ),
            'tb_display' => array(
                'id'        => 'tb_display',
                'name'      => __( 'tb_display', 'themeblvd' ),
                'desc'      => __( 'When using Post Grid and Post Showcase template, this custom field allows you to override the default display option.', 'themeblvd' ),
                'type'    	=> 'select',
                'options'	=> array(
                	'0'					=> __( 'Use default setting', 'themeblvd' ),
                	'paginated' 		=> __( 'Standard', 'themeblvd' ),
					'masonry_paginated' => __( 'Masonry', 'themeblvd' )
                )
            )
		)
	);

	return apply_filters( 'themeblvd_pto_meta', $setup );
}

/**
 * Get settings for the Post Options meta box.
 *
 * @since 2.0.0
 *
 * @return $setup filterable options for metabox
 */
function setup_themeblvd_post_meta() {

	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . '/framework/admin/assets/images/';

	// Generate sidebar layout options
	$sidebar_layouts = array( 'default' =>  $imagepath.'layout-default.png' );
	$layouts = themeblvd_sidebar_layouts();
	foreach ( $layouts as $layout ) {
		$sidebar_layouts[$layout['id']] = $imagepath.'layout-'.$layout['id'].'.png';
	}

	$setup = array(
		'config' => array(
			'id' 		=> 'tb_post_options',						// make it unique
			'title' 	=> __( 'Post Options', 'themeblvd' ),		// title to show for entire meta box
			'page'		=> array( 'post' ),							// can contain post, page, link, or custom post type's slug
			'context' 	=> 'normal',								// normal, advanced, or side
			'priority'	=> 'high'									// high, core, default, or low
		),
		'options' => array(
			'tb_meta' => array(
				'id' 		=> '_tb_meta',
				'name' 		=> __( 'Meta Information (the single post)', 'themeblvd' ),
				'desc' 		=> __( 'Select if you\'d like the meta information (like date posted, author, etc) to show on this single post. If you\'re going for a non-blog type of setup, you may want to hide the meta info.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'radio',
				'options' 	=> array(
					'default'	=> __( 'Use default setting', 'themeblvd' ),
					'show'		=> __( 'Show meta info', 'themeblvd' ),
					'hide' 		=> __( 'Hide meta info', 'themeblvd' )
				)
			),
			'tb_sub_meta' => array(
				'id' 		=> '_tb_sub_meta',
				'name' 		=> __( 'Sub Meta Information (the single post)', 'themeblvd' ),
				'desc' 		=> __( 'Select if you\'d like the sub meta information (like tags, categories, etc) to show on this single post.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'radio',
				'options' 	=> array(
					'default'	=> __( 'Use default setting', 'themeblvd' ),
					'show'		=> __( 'Show sub meta info', 'themeblvd' ),
					'hide' 		=> __( 'Hide sub meta info', 'themeblvd' )
				)
			),
			'tb_author_box' => array(
				'id' 		=> '_tb_author_box',
				'name' 		=> __( 'Author Box (the single post)', 'themeblvd' ),
				'desc' 		=> __( 'Select if you\'d like to display a box with information about the post\'s author.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'radio',
				'options' 	=> array(
					'default'	=> __( 'Use WordPress user\'s default setting', 'themeblvd' ),
					'1'			=> __( 'Show author box', 'themeblvd' ), // Use "1" to match default user checkbox option
					'hide' 		=> __( 'Hide author box', 'themeblvd' )
				)
			),
			'tb_related_posts' => array(
				'id' 		=> '_tb_related_posts',
				'name' 		=> __( 'Related Posts (the single post)', 'themeblvd' ),
				'desc' 		=> __( 'Select if you\'d like to show more posts related to the one being viewed.', 'themeblvd' ).'<br><br><em>'.__('Note: This only applies to standard posts.', 'themeblvd').'</em>',
				'std' 		=> 'default',
				'type' 		=> 'radio',
				'options' 	=> array(
					'default'	=> __( 'Use default setting', 'themeblvd' ),
					'tag'		=> __( 'Show related posts by tag', 'themeblvd' ),
					'category'	=> __( 'Show related posts by category', 'themeblvd' ),
					'hide' 		=> __( 'Hide related posts', 'themeblvd' )
				)
			),
			'tb_comments' => array(
				'id' 		=> '_tb_comments',
				'name' 		=> __( 'Comments (the single post)', 'themeblvd' ),
				'desc' 		=> __( 'This will hide the presence of comments on this single post.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'radio',
				'options' 	=> array(
					'default'	=> __( 'Use default setting', 'themeblvd' ),
					'show'		=> __( 'Show comments', 'themeblvd' ),
					'hide' 		=> __( 'Hide comments', 'themeblvd' )
				)
			),
			'tb_breadcrumbs' => array(
				'id'		=> '_tb_breadcrumbs',
				'name' 		=> __( 'Breadcrumbs (the single post)', 'themeblvd' ),
				'desc'		=> __( 'Select whether you\'d like breadcrumbs to show on this post or not.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'radio',
				'options'	=> array(
					'default' 	=> __( 'Use default setting', 'themeblvd' ),
					'show' 		=> __( 'Yes, show breadcrumbs', 'themeblvd' ),
					'hide' 		=> __( 'No, hide breadcrumbs', 'themeblvd' )
				)
			),
			'tb_thumb' => array(
				'id' 		=> '_tb_thumb',
				'name' 		=> __( 'Featured Image Display (the single post)', 'themeblvd' ),
				'desc' 		=> __( 'Select how you\'d like the featured image to show at the top of the post. This does <em>not</em> apply to when this post is listed in a post list or post grid. This option only refers to this single post.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'radio',
				'options' 	=> array(
					'default'	=> __( 'Use default setting', 'themeblvd' ),
					//'small'		=> __( 'Show small thumbnail', 'themeblvd' ),
					'full' 		=> __( 'Show featured image', 'themeblvd' ),
					'hide' 		=> __( 'Hide featured image', 'themeblvd' )
				)
			),
			'section_start' => array(
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'tb_thumb_link' => array(
				'id'		=> '_tb_thumb_link',
				'name' 		=> __( 'Featured Image Link (everywhere)', 'themeblvd' ),
				'desc'		=> __( 'Here you can select how you\'d like this post\'s featured image to react when clicked. This <em>does</em> apply to both this single post page and when this post is used in a post list or post grid.', 'themeblvd' ),
				'type' 		=> 'radio',
				'std'		=> 'inactive',
				'options'	=> array(
					'inactive'	=> __( 'Featured image is not a link', 'themeblvd' ),
					'post' 		=> __( 'It links to its post', 'themeblvd' ),
					'thumbnail' => __( 'It links to its enlarged lightbox version', 'themeblvd' ),
					'image' 	=> __( 'It links to a custom lightbox image', 'themeblvd' ),
					'video' 	=> __( 'It links to a lightbox video', 'themeblvd' ),
					'external' 	=> __( 'It links to a webpage', 'themeblvd' ),
				),
				'class'		=> 'trigger'
			),
			'tb_image_link' => array(
				'id'		=> '_tb_image_link',
				'name' 		=> __( 'Featured Image - Image Link', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL of enlarged image that the featured image will link to.<br><br>Ex: http://your-site.com/uploads/image.jpg', 'themeblvd' ),
				'type' 		=> 'text',
				'class'		=> 'hide receiver receiver-image'
			),
			'tb_video_link' => array(
				'id'		=> '_tb_video_link',
				'name' 		=> __( 'Featured Image - Video Link', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL to a video page supported by <a href="http://codex.wordpress.org/Embeds" target="_blank">WordPress\'s oEmbed</a>.<br><br>Ex: http://www.youtube.com/watch?v=ginTCwWfGNY<br>Ex: http://vimeo.com/11178250', 'themeblvd' ),
				'type' 		=> 'text',
				'class'		=> 'hide receiver receiver-video'
			),
			'tb_external_link' => array(
				'id'		=> '_tb_external_link',
				'name' 		=> __( 'Featured Image - External Link', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL of where the featured image will link.<br><br>Ex: http://google.com', 'themeblvd' ),
				'type' 		=> 'text',
				'class'		=> 'hide receiver receiver-external'
			),
			'tb_external_link_target' => array(
				'id'		=> '_tb_external_link_target',
				'name' 		=> __( 'Featured Image - External Link Target', 'themeblvd' ),
				'desc'		=> __( 'Select whether you\'d like the external link to open in a new window or not.', 'themeblvd' ),
				'type' 		=> 'radio',
				'std'		=> '_blank',
				'options'	=> array(
					'_blank'	=> __( 'Open link in new window', 'themeblvd' ),
					'_self' 	=> __( 'Open link in same window', 'themeblvd' )
				),
				'class'		=> 'hide receiver receiver-external'
			),
			'tb_thumb_link_single' => array(
				'id'		=> '_tb_thumb_link_single',
				'name' 		=> __( 'Featured Image Link (the single post)', 'themeblvd' ),
				'desc'		=> __( 'If you\'ve selected a featured image link above, select whether you\'d like the image link to be applied to the featured image on the single post page.', 'themeblvd' ),
				'std' 		=> 'yes',
				'type' 		=> 'radio',
				'options'	=> array(
					'yes'		=> __( 'Yes, apply featured image link to single post', 'themeblvd' ),
					'no' 		=> __( 'No, don\'t apply featured image link to single post', 'themeblvd' ),
					'thumbnail'	=> __( 'Link it to its enlarged lightbox version', 'themeblvd' )
				),
				'class'		=> 'hide receiver receiver-post receiver-thumbnail receiver-image receiver-video receiver-external'
			),
			'section_end' => array(
				'type' 		=> 'subgroup_end'
			),
			'tb_sidebar_layout' => array(
				'id' 		=> '_tb_sidebar_layout',
				'name' 		=> __( 'Sidebar Layout', 'themeblvd' ),
				'desc' 		=> __( 'Choose the sidebar layout for this specific post. Keeping it set to "Website Default" will allow this post to continue using the sidebar layout selected on the Theme Options page.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'images',
				'options' 	=> $sidebar_layouts,
				'img_width'	=> '45'
			)
		)
	);
	return apply_filters( 'themeblvd_post_meta', $setup );
}

if ( ! function_exists( 'themeblvd_page_attributes_meta_box' ) ) :
/**
 * Hijack and modify default Page Attributes meta box.
 *
 * @since 2.0.0
 */
function themeblvd_page_attributes_meta_box($post) {

	// Kill it if disabled
	if ( ! themeblvd_supports( 'meta', 'hijack_atts' ) ) {
		return false;
	}

	// Continue on with everything copied from WordPress core

	$post_type_object = get_post_type_object($post->post_type);
	if ( $post_type_object->hierarchical ) {
		$pages = wp_dropdown_pages(array('post_type' => $post->post_type, 'exclude_tree' => $post->ID, 'selected' => $post->post_parent, 'name' => 'parent_id', 'show_option_none' => __('(no parent)', 'themeblvd'), 'sort_column'=> 'menu_order, post_title', 'echo' => 0));
		if ( ! empty($pages) ) {
?>
<p><strong><?php _e('Parent', 'themeblvd') ?></strong></p>
<label class="screen-reader-text" for="parent_id"><?php _e('Parent', 'themeblvd') ?></label>
<?php echo $pages; ?>
<?php
		} // end empty pages check
	} // end hierarchical check.
	if ( 'page' == $post->post_type && 0 != count( get_page_templates() ) ) {
		$template = !empty($post->page_template) ? $post->page_template : false;
		?>
<p><strong><?php _e('Template', 'themeblvd') ?></strong></p>
<label class="screen-reader-text" for="page_template"><?php _e('Page Template', 'themeblvd') ?></label><select name="page_template" id="page_template">
<option value='default'><?php _e('Default Template', 'themeblvd'); ?></option>
<?php page_template_dropdown($template); ?>
</select>
<?php
	} ?>
<?php
/*-----------------------------------------------------------------------------------*/
/* ThemeBlvd Modifications (start)
/*-----------------------------------------------------------------------------------*/
$sidebar_layout = get_post_meta( $post->ID, '_tb_sidebar_layout', true );
echo themeblvd_sidebar_layout_dropdown( $sidebar_layout );
// Custom layout selection removed as of Layout Builder v1.1, as now it's selected from a custom metabox.
// $custom_layout = get_post_meta( $post->ID, '_tb_custom_layout', true );
// echo themeblvd_custom_layout_dropdown( $custom_layout );
/*-----------------------------------------------------------------------------------*/
/* ThemeBlvd Modifications (end)
/*-----------------------------------------------------------------------------------*/
?>
<p><strong><?php _e('Order', 'themeblvd') ?></strong></p>
<p><label class="screen-reader-text" for="menu_order"><?php _e('Order', 'themeblvd') ?></label><input name="menu_order" type="text" size="4" id="menu_order" value="<?php echo esc_attr($post->menu_order) ?>" /></p>
<p><?php if ( 'page' == $post->post_type ) _e( 'Need help? Use the Help tab in the upper right of your screen.', 'themeblvd' ); ?></p>
<?php
}
endif;
