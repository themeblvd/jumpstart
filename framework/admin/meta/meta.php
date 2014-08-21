<?php
/**
 * Add page and post meta boxes.
 *
 * @since 2.0.1
 */
function themeblvd_add_meta_boxes() {

	global $_themeblvd_page_meta_box;
	global $_themeblvd_post_template_meta_box;
	global $_themeblvd_post_meta_box;

	// General page meta box
	if ( themeblvd_supports( 'meta', 'page_options' ) ) {
		$meta = setup_themeblvd_page_meta();
		$_themeblvd_page_meta_box = new Theme_Blvd_Meta_Box( $meta['config']['id'], $meta['config'], $meta['options'] );
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
				'desc'		=> __( 'This option will be ignored if you\'ve applyed a custom layout.', 'themeblvd' ),
				'type' 		=> 'select',
				'options'	=> array(
					'show' 		=> __( 'Show page\'s title', 'themeblvd' ),
					'hide' 		=> __( 'Hide page\'s title', 'themeblvd' )
				)
			),
			'tb_breadcrumbs' => array(
				'id'		=> '_tb_breadcrumbs',
				'name' 		=> __( 'Breadcrumbs', 'themeblvd' ),
				'desc'		=> __( 'Select whether you\'d like breadcrumbs to show on this page or not. This option will be ignored if you\'ve applyed a custom layout.', 'themeblvd' ),
				'type' 		=> 'select',
				'options'	=> array(
					'default' 	=> __( 'Use default setting', 'themeblvd' ),
					'show' 		=> __( 'Yes, show breadcrumbs', 'themeblvd' ),
					'hide' 		=> __( 'No, hide breadcrumbs', 'themeblvd' )
				)
			),
			'tb_theme_layout' => array(
				'id'		=> '_tb_theme_layout',
				'name' 		=> __( 'Theme Layout', 'themeblvd' ),
				'desc'		=> __( 'Select if you\'d like to hide any theme elements that normally display across your entire website by default.', 'themeblvd' ),
				'type' 		=> 'multicheck',
				'options'	=> array(
					'hide_top'		=> __( 'Hide theme header on this page', 'themeblvd' ),
					'hide_bottom'	=> __( 'Hide theme footer on this page', 'themeblvd' )
				)
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
                'id'        => 'desc',
                'desc'      => __( 'Below are the custom fields you can use with the Blog, Post List, and Post Grid page templates. When working with these options, you can find a lot of helpful information by viewing WordPress\'s Codex page on the <a href="http://codex.wordpress.org/Class_Reference/WP_Query" target="_blank">WP Query</a>.', 'themeblvd_pto' ),
                'type'      => 'info'
            ),
            'cat' => array(
                'id'        => 'cat',
                'name'      => __( 'cat', 'themeblvd_pto' ),
                'desc'      => __( 'Category ID(s) to include/exclude.<br>Ex: 1<br>Ex: 1,2,3<br>Ex: -1,-2,-3', 'themeblvd_pto' ),
                'type'      => 'text'
            ),
            'category_name' => array(
                'id'        => 'category_name',
                'name'      => __( 'category_name', 'themeblvd_pto' ),
                'desc'      => __( 'Category slug(s) to include.<br>Ex: cat-1<br>Ex: cat-1,cat-2', 'themeblvd_pto' ),
                'type'      => 'text'
            ),
            'tag' => array(
                'id'        => 'tag',
                'name'      => __( 'tag', 'themeblvd_pto' ),
                'desc'      => __( 'Tag(s) to include.<br>Ex: tag-1<br>Ex: tag-1,tag-2', 'themeblvd_pto' ),
                'type'      => 'text'
            ),
            'posts_per_page' => array(
                'id'        => 'posts_per_page',
                'name'      => __( 'posts_per_page', 'themeblvd_pto' ),
                'desc'      => __( 'Number of posts per page. Only for Post List template; Post Grid uses rows*columns.', 'themeblvd_pto' ),
                'type'      => 'text'
            ),
            'orderby' => array(
                'id'        => 'orderby',
                'name'      => __( 'orderby', 'themeblvd_pto' ),
                'desc'      => __( 'What to order posts by -- date, title, rand, etc.<br>(<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">Learn More</a>)', 'themeblvd_pto' ),
                'type'      => 'text'
            ),
            'order' => array(
                'id'        => 'order',
                'name'      => __( 'order', 'themeblvd_pto' ),
                'desc'      => __( 'How to order posts -- ASC or DESC.<br>(<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">Learn More</a>)', 'themeblvd_pto' ),
                'type'      => 'text'
            ),
            'query' => array(
                'id'        => 'query',
                'name'      => __( 'query', 'themeblvd_pto' ),
                'desc'      => __( 'A custom query string. This will override other options.<br>Ex: tag=baking<br>Ex: post_type=my_type&my_tax=my_term', 'themeblvd_pto' ),
                'type'      => 'text'
            ),
            'columns' => array(
                'id'        => 'columns',
                'name'      => __( 'columns', 'themeblvd_pto' ),
                'desc'      => __( 'Number of columns for Post Grid template. When empty, this will default to 3.', 'themeblvd_pto' ),
                'type'      => 'text'
            ),
            'rows' => array(
                'id'        => 'rows',
                'name'      => __( 'rows', 'themeblvd_pto' ),
                'desc'      => __( 'Number of rows for Post Grid template. When empty, this will default to 4.', 'themeblvd_pto' ),
                'type'      => 'text'
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
	$sidebar_layouts = array( 'default' =>  $imagepath.'layout-default_2x.png' );
	$layouts = themeblvd_sidebar_layouts();
	foreach ( $layouts as $layout ) {
		$sidebar_layouts[$layout['id']] = $imagepath.'layout-'.$layout['id'].'_2x.png';
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
				'desc' 		=> __( 'Select if you\'d like to show more posts related to the one being viewed.', 'themeblvd' ),
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
				'desc' 		=> __( 'This will hide the presence of comments on this single post.<br><br><em>Note: To hide comments link in meta information, close the comments on the post\'s discussion settings.</em>', 'themeblvd' ),
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
			'tb_thumb_link' => array(
				'id'		=> '_tb_thumb_link',
				'name' 		=> __( 'Featured Image Link (everywhere)', 'themeblvd' ),
				'desc'		=> __( 'Here you can select how you\'d like this post\'s featured image to react when clicked. This <em>does</em> apply to both this single post page and when this post is used in a post list or post grid.', 'themeblvd' ),
				'type' 		=> 'radio',
				'std'		=> 'inactive',
				'class'		=> 'select-tb-thumb-link',
				'options'	=> array(
					'inactive'	=> __( 'Featured image is not a link', 'themeblvd' ),
					'post' 		=> __( 'It links to its post', 'themeblvd' ),
					'thumbnail' => __( 'It links to its enlarged lightbox version', 'themeblvd' ),
					'image' 	=> __( 'It links to a custom lightbox image', 'themeblvd' ),
					'video' 	=> __( 'It links to a lightbox video', 'themeblvd' ),
					'external' 	=> __( 'It links to a webpage', 'themeblvd' ),
				)
			),
			'tb_image_link' => array(
				'id'		=> '_tb_image_link',
				'name' 		=> __( 'Featured Image - Image Link', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL of enlarged image that the featured image will link to.<br><br>Ex: http://your-site.com/uploads/image.jpg', 'themeblvd' ),
				'class'		=> 'tb-thumb-link tb-thumb-link-image',
				'type' 		=> 'text'
			),
			'tb_video_link' => array(
				'id'		=> '_tb_video_link',
				'name' 		=> __( 'Featured Image - Video Link', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL to a video page supported by <a href="http://codex.wordpress.org/Embeds" target="_blank">WordPress\'s oEmbed</a>.<br><br>Ex: http://www.youtube.com/watch?v=ginTCwWfGNY<br>Ex: http://vimeo.com/11178250', 'themeblvd' ),
				'class'		=> 'tb-thumb-link tb-thumb-link-video',
				'type' 		=> 'text'
			),
			'tb_external_link' => array(
				'id'		=> '_tb_external_link',
				'name' 		=> __( 'Featured Image - External Link', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL of where the featured image will link.<br><br>Ex: http://google.com', 'themeblvd' ),
				'class'		=> 'tb-thumb-link tb-thumb-link-external',
				'type' 		=> 'text'
			),
			'tb_external_link_target' => array(
				'id'		=> '_tb_external_link_target',
				'name' 		=> __( 'Featured Image - External Link Target', 'themeblvd' ),
				'desc'		=> __( 'Select whether you\'d like the external link to open in a new window or not.', 'themeblvd' ),
				'class'		=> 'tb-thumb-link tb-thumb-link-external',
				'type' 		=> 'radio',
				'std'		=> '_blank',
				'options'	=> array(
					'_blank'	=> __( 'Open link in new window', 'themeblvd' ),
					'_self' 	=> __( 'Open link in same window', 'themeblvd' )
				)
			),
			'tb_thumb_link_single' => array(
				'id'		=> '_tb_thumb_link_single',
				'name' 		=> __( 'Featured Image Link (the single post)', 'themeblvd' ),
				'desc'		=> __( 'If you\'ve selected a featured image link above, select whether you\'d like the image link to be applied to the featured image on the single post page.', 'themeblvd' ),
				'class'		=> 'tb-thumb-link tb-thumb-link-single',
				'std' 		=> 'yes',
				'type' 		=> 'radio',
				'options'	=> array(
					'yes'		=> __( 'Yes, apply featured image link to single post', 'themeblvd' ),
					'no' 		=> __( 'No, don\'t apply featured image link to single post', 'themeblvd' ),
					'thumbnail'	=> __( 'Link it to its enlarged lightbox version', 'themeblvd' )
				)
			),
			'tb_sidebar_layout' => array(
				'id' 		=> '_tb_sidebar_layout',
				'name' 		=> __( 'Sidebar Layout', 'themeblvd' ),
				'desc' 		=> __( 'Choose the sidebar layout for this specific post. Keeping it set to "Website Default" will allow this post to continue using the sidebar layout selected on the Theme Options page.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'images',
				'options' 	=> $sidebar_layouts,
				'img_width'	=> '65' // HiDPI compatibility, 1/2 of images' natural widths
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