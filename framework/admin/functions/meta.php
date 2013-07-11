<?php
if ( !function_exists( 'themeblvd_add_meta_boxes' ) ) :
/**
 * Add page and post meta boxes.
 *
 * @since 2.0.1
 */
function themeblvd_add_meta_boxes() {

	global $_themeblvd_page_meta_box;
	global $_themeblvd_post_meta_box;

	// Page meta box
	if ( themeblvd_supports( 'meta', 'page_options' ) ) {
		$page_meta = setup_themeblvd_page_meta();
		$_themeblvd_page_meta_box = new Theme_Blvd_Meta_Box( $page_meta['config']['id'], $page_meta['config'], $page_meta['options'] );
	}

	// Post meta box
	if ( themeblvd_supports( 'meta', 'post_options' ) ) {
		$post_meta = setup_themeblvd_post_meta();
		$_themeblvd_post_meta_box = new Theme_Blvd_Meta_Box( $post_meta['config']['id'], $post_meta['config'], $post_meta['options'] );
	}

}
endif;

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
			array(
				'id'		=> '_tb_title',
				'name' 		=> __( 'Page Title', 'themeblvd' ),
				'desc'		=> __( 'This option will be ignored if you\'re using this page with a custom layout built with the <a href="admin.php?page=builder_blvd">Layout Builder</a>.', 'themeblvd' ),
				'type' 		=> 'select',
				'options'	=> array(
					'show' => __( 'Show page\'s title.', 'themeblvd' ),
					'hide' => __( 'Hide page\'s title.', 'themeblvd' )
				)
			),
			array(
				'id'		=> '_tb_breadcrumbs',
				'name' 		=> __( 'Breadcrumbs', 'themeblvd' ),
				'desc'		=> __( 'Select whether you\'d like breadcrumbs to show on this page or not.', 'themeblvd' ),
				'type' 		=> 'select',
				'options'	=> array(
					'default' => __( 'Use default setting.', 'themeblvd' ),
					'show' => __( 'Yes, show breadcrumbs.', 'themeblvd' ),
					'hide' => __( 'No, hide breadcrumbs.', 'themeblvd' )
				)
			)
		)
	);
	return apply_filters( 'themeblvd_page_meta', $setup );
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
			array(
				'id' 		=> '_tb_meta',
				'name' 		=> __( 'Meta Information (the single post)', 'themeblvd' ), /* Required by Framework */
				'desc' 		=> __( 'Select if you\'d like the meta information (date posted, author, etc) to show at the top of the post. If you\'re going for a portfolio-type setup, you may want to hide the meta info. This does not apply to when this post is listed in a post list or post grid format. This option only refers to this single post.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'radio',
				'options' 	=> array(
					'default'	=> __( 'Use default post setting.', 'themeblvd' ),
					'show'		=> __( 'Show meta info.', 'themeblvd' ),
					'hide' 		=> __( 'Hide meta info.', 'themeblvd' )
				)
			),
			array(
				'id' 		=> '_tb_comments',
				'name' 		=> __( 'Comments (the single post)', 'themeblvd' ), /* Required by Framework */
				'desc' 		=> __( 'Select if you\'d like to completely hide comments or not below the post. This does not apply to when this post is listed in a post list or post grid format. This option only refers to this single post.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'radio',
				'options' 	=> array(
					'default'	=> __( 'Use default post setting.', 'themeblvd' ),
					'show'		=> __( 'Show comments.', 'themeblvd' ),
					'hide' 		=> __( 'Hide comments.', 'themeblvd' )
				)
			),
			array(
				'id'		=> '_tb_breadcrumbs',
				'name' 		=> __( 'Breadcrumbs (the single post)', 'themeblvd' ),
				'desc'		=> __( 'Select whether you\'d like breadcrumbs to show on this post or not.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'radio',
				'options'	=> array(
					'default' 	=> __( 'Use default setting.', 'themeblvd' ),
					'show' 		=> __( 'Yes, show breadcrumbs.', 'themeblvd' ),
					'hide' 		=> __( 'No, hide breadcrumbs.', 'themeblvd' )
				)
			),
			array(
				'id' 		=> '_tb_thumb',
				'name' 		=> __( 'Featured Image Display (the single post)', 'themeblvd' ), /* Required by Framework */
				'desc' 		=> __( 'Select how you\'d like the featured image to show at the top of the post. This does <em>not</em> apply to when this post is listed in a post list or post grid. This option only refers to this single post.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'radio',
				'options' 	=> array(
					'default'	=> __( 'Use default post setting.', 'themeblvd' ),
					'small'		=> __( 'Show small thumbnail.', 'themeblvd' ),
					'full' 		=> __( 'Show full-width thumbnail.', 'themeblvd' ),
					'hide' 		=> __( 'Hide featured image.', 'themeblvd' )
				)
			),
			array(
				'id'		=> '_tb_thumb_link',
				'name' 		=> __( 'Featured Image Link (everywhere)', 'themeblvd' ),
				'desc'		=> __( 'Here you can select how you\'d like this post\'s featured image to react when clicked. This <em>does</em> apply to both this single post page and when this post is used in a post list or post grid.', 'themeblvd' ),
				'type' 		=> 'radio',
				'std'		=> 'inactive',
				'class'		=> 'select-tb-thumb-link',
				'options'	=> array(
					'inactive'	=> __( 'Featured image is not a link.', 'themeblvd' ),
					'post' 		=> __( 'It links to its post.', 'themeblvd' ),
					'thumbnail' => __( 'It links to its enlarged lightbox version.', 'themeblvd' ),
					'image' 	=> __( 'It links to a custom lightbox image.', 'themeblvd' ),
					'video' 	=> __( 'It links to a lightbox video.', 'themeblvd' ),
					'external' 	=> __( 'It links to a webpage.', 'themeblvd' ),
				)
			),
			array(
				'id'		=> '_tb_image_link',
				'name' 		=> __( 'Featured Image - Image Link', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL of enlarged image that the featured image will link to.<br><br>Ex: http://your-site.com/uploads/image.jpg', 'themeblvd' ),
				'class'		=> 'tb-thumb-link tb-thumb-link-image',
				'type' 		=> 'text'
			),
			array(
				'id'		=> '_tb_video_link',
				'name' 		=> __( 'Featured Image - Video Link', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL to a video page supported by <a href="http://codex.wordpress.org/Embeds" target="_blank">WordPress\'s oEmbed</a>.<br><br>Ex: http://www.youtube.com/watch?v=ginTCwWfGNY<br>Ex: http://vimeo.com/11178250', 'themeblvd' ),
				'class'		=> 'tb-thumb-link tb-thumb-link-video',
				'type' 		=> 'text'
			),
			array(
				'id'		=> '_tb_external_link',
				'name' 		=> __( 'Featured Image - External Link', 'themeblvd' ),
				'desc'		=> __( 'Enter the full URL of where the featured image will link.<br><br>Ex: http://google.com', 'themeblvd' ),
				'class'		=> 'tb-thumb-link tb-thumb-link-external',
				'type' 		=> 'text'
			),
			array(
				'id'		=> '_tb_external_link_target',
				'name' 		=> __( 'Featured Image - External Link Target', 'themeblvd' ),
				'desc'		=> __( 'Select whether you\'d like the external link to open in a new window or not.', 'themeblvd' ),
				'class'		=> 'tb-thumb-link tb-thumb-link-external',
				'type' 		=> 'radio',
				'std'		=> '_blank',
				'options'	=> array(
					'_blank'	=> __( 'Open link in new window.', 'themeblvd' ),
					'_self' 	=> __( 'Open link in same window.', 'themeblvd' )
				)
			),
			array(
				'id'		=> '_tb_thumb_link_single',
				'name' 		=> __( 'Featured Image Link (the single post)', 'themeblvd' ),
				'desc'		=> __( 'If you\'ve selected a featured image link above, select whether you\'d like the image link to be applied to the featured image on the single post page or not.', 'themeblvd' ),
				'class'		=> 'tb-thumb-link tb-thumb-link-single',
				'std' 		=> 'yes',
				'type' 		=> 'radio',
				'options'	=> array(
					'yes'		=> __( 'Yes, apply featured image link to single post.', 'themeblvd' ),
					'no' 		=> __( 'No, don\'t apply featured image link to single post.', 'themeblvd' )
				)
			),
			array(
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
	if ( ! themeblvd_supports( 'meta', 'hijack_atts' ) )
		return false;

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