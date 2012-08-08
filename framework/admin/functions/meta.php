<?php
/*-----------------------------------------------------------------------------------*/
/* Admin Meta Boxes
/*-----------------------------------------------------------------------------------*/

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
			'title' 	=> __( 'Page Options', 'themeblvd' ),	// title to show for entire meta box
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
 * Display page meta box
 *
 * @since 2.0.1
 */

if( ! function_exists( 'display_themeblvd_page_meta' ) ) {
	function display_themeblvd_page_meta() {
		
		global $post;
    	$page_meta = setup_themeblvd_page_meta();
    	
    	// Make sure options framework exists so we can show 
    	// the options form.
    	if( ! function_exists( 'optionsframework_fields' ) ) {
    		echo 'Options framework not found.';
    		return;
    	}
    	
    	// Start content
    	echo '<div class="tb-meta-box">';
    	
    	// Gather any already saved settings or defaults for option types 
    	// that need a starting value
    	$settings = array();
    	foreach( $page_meta['options'] as $option ) {
    		$settings[$option['id']] = get_post_meta( $post->ID, $option['id'], true );
    		if( ! $settings[$option['id']] ) {
    			if( 'radio' == $option['type'] || 'images' == $option['type'] || 'select' == $option['type'] ) {
    				if( isset( $option['std'] ) )
    					$settings[$option['id']] = $option['std'];
    			}
    		}
    	}
    	
    	// Use options framework to display form elements
    	$form = optionsframework_fields( 'themeblvd_meta', $page_meta['options'], $settings, false );
    	echo $form[0];
    	
    	//  Finish content
    	if( isset( $page_meta['config']['desc'] ) ) echo '<p class="tb-meta-desc">'.$page_meta['config']['desc'].'</p>';
		echo '</div><!-- .tb-meta-box (end) -->';

	}
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
	foreach( $layouts as $layout )
		$sidebar_layouts[$layout['id']] = $imagepath.'layout-'.$layout['id'].'.png';

	
	$setup = array(
		'config' => array(
			'id' 		=> 'tb_post_options',						// make it unique	
			'title' 	=> __( 'Post Options', 'themeblvd' ),	// title to show for entire meta box
			'page'		=> array( 'post' ),							// can contain post, page, link, or custom post type's slug
			'context' 	=> 'normal',								// normal, advanced, or side
			'priority'	=> 'high'									// high, core, default, or low
		),
		'options' => array(
			array(
				'id' 		=> '_tb_meta',
				'name' 		=> __( 'Meta Information', 'themeblvd' ), /* Required by Framework */
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
				'name' 		=> __( 'Comments', 'themeblvd' ), /* Required by Framework */
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
				'name' 		=> __( 'Breadcrumbs', 'themeblvd' ),
				'desc'		=> __( 'Select whether you\'d like breadcrumbs to show on this post or not.', 'themeblvd' ),
				'type' 		=> 'radio',
				'options'	=> array(
					'default' => __( 'Use default setting.', 'themeblvd' ),
					'show' => __( 'Yes, show breadcrumbs.', 'themeblvd' ),
					'hide' => __( 'No, hide breadcrumbs.', 'themeblvd' )
				)
			),
			array(
				'id' 		=> '_tb_thumb',
				'name' 		=> __( 'Featured Image', 'themeblvd' ), /* Required by Framework */
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
				'name' 		=> __( 'Featured Image Link', 'themeblvd' ),
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
				'desc'		=> __( 'Enter the full URL to a video page on YouTube or Vimeo. You can also enter a link to a Quicktime video file.<br><br>Ex: http://www.youtube.com/watch?v=ginTCwWfGNY<br>Ex: http://vimeo.com/11178250', 'themeblvd' ),
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
				'id' 		=> '_tb_sidebar_layout',
				'name' 		=> __( 'Sidebar Layout', 'themeblvd' ),
				'desc' 		=> __( 'Choose the sidebar layout for this specific post. Keeping it set to "Website Default" will allow this post to continue using the sidebar layout selected on the Theme Options page.', 'themeblvd' ),
				'std' 		=> 'default',
				'type' 		=> 'images',
				'options' 	=> $sidebar_layouts
			)
		)
	);
	return apply_filters( 'themeblvd_post_meta', $setup );
}

/**
 * Display post meta box
 *
 * @since 2.0.1
 */

if( ! function_exists( 'display_themeblvd_post_meta' ) ) {
	function display_themeblvd_post_meta() {
		
		global $post;
    	$post_meta = setup_themeblvd_post_meta();
    	
    	// Make sure options framework exists so we can show 
    	// the options form.
    	if( ! function_exists( 'optionsframework_fields' ) ) {
    		echo 'Options framework not found.';
    		return;
    	}
    	
    	// Start content
    	echo '<div class="tb-meta-box">';
    	
    	// Gather any already saved settings or defaults for option types 
    	// that need a starting value
    	$settings = array();
    	foreach( $post_meta['options'] as $option ) {
    		$settings[$option['id']] = get_post_meta( $post->ID, $option['id'], true );
    		if( ! $settings[$option['id']] ) {
    			if( 'radio' == $option['type'] || 'images' == $option['type'] || 'select' == $option['type'] ) {
    				if( isset( $option['std'] ) )
    					$settings[$option['id']] = $option['std'];
    			}
    		}
    	}
    	
    	// Use options framework to display form elements
    	$form = optionsframework_fields( 'themeblvd_meta', $post_meta['options'], $settings, false );
    	echo $form[0];
    	
    	//  Finish content
    	if( isset( $post_meta['config']['desc'] ) ) echo '<p class="tb-meta-desc">'.$post_meta['config']['desc'].'</p>';
		echo '</div><!-- .tb-meta-box (end) -->';
		
	}
}

/**
 * Add page and post meta boxes.
 *
 * @since 2.0.1
 */

if( ! function_exists( 'themeblvd_add_meta_boxes' ) ) {
	function themeblvd_add_meta_boxes() {

		// Page meta box
		if( themeblvd_supports( 'meta', 'page_options' ) ) {
			$page_meta = setup_themeblvd_page_meta();
			foreach( $page_meta['config']['page'] as $page ) {
	    		add_meta_box( 
			        $page_meta['config']['id'],
					$page_meta['config']['title'],
					'display_themeblvd_page_meta',
					$page,
					$page_meta['config']['context'],
					$page_meta['config']['priority']
			    );
	    	}
	    }
	
		// Post meta box
		if( themeblvd_supports( 'meta', 'post_options' ) ) {
			$post_meta = setup_themeblvd_post_meta();
			foreach( $post_meta['config']['page'] as $page ) {
	    		add_meta_box( 
			        $post_meta['config']['id'],
					$post_meta['config']['title'],
					'display_themeblvd_post_meta',
					$page,
					$post_meta['config']['context'],
					$post_meta['config']['priority']
			    );
	    	}
	    }

	}
}

/**
 * Save page and post meta boxes.
 *
 * @since 2.0.1
 */

if( ! function_exists( 'themeblvd_save_meta_boxes' ) ) {
	function themeblvd_save_meta_boxes( $post_id ) {
		
		// Page meta boxes
		$page_meta = setup_themeblvd_page_meta();
		foreach( $page_meta['options'] as $option ) {
			if( isset( $_POST['themeblvd_meta'][$option['id']] ) ) {
				update_post_meta( $post_id, $option['id'], strip_tags( $_POST['themeblvd_meta'][$option['id']] ) );
			}	
		}
		
		// Post Meta boxes
		$post_meta = setup_themeblvd_post_meta();
		foreach( $post_meta['options'] as $option ) {
			if( isset( $_POST['themeblvd_meta'][$option['id']] ) ) {
				update_post_meta( $post_id, $option['id'], strip_tags( $_POST['themeblvd_meta'][$option['id']] ) );
			}	
		}
		
	}
}

/**
 * Hijack and modify default Page Attributes meta box.
 *
 * @since 2.0.0
 */
if( ! function_exists( 'themeblvd_page_attributes_meta_box' ) ) {
	function themeblvd_page_attributes_meta_box($post) {
		
		// Kill it if disabled
		if( ! themeblvd_supports( 'meta', 'hijack_atts' ) ) 
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
$custom_layout = get_post_meta( $post->ID, '_tb_custom_layout', true );
echo themeblvd_custom_layout_dropdown( $custom_layout );
/*-----------------------------------------------------------------------------------*/
/* ThemeBlvd Modifications (end)
/*-----------------------------------------------------------------------------------*/
?>
<p><strong><?php _e('Order', 'themeblvd') ?></strong></p>
<p><label class="screen-reader-text" for="menu_order"><?php _e('Order', 'themeblvd') ?></label><input name="menu_order" type="text" size="4" id="menu_order" value="<?php echo esc_attr($post->menu_order) ?>" /></p>
<p><?php if ( 'page' == $post->post_type ) _e( 'Need help? Use the Help tab in the upper right of your screen.', 'themeblvd' ); ?></p>
<?php
	}
}