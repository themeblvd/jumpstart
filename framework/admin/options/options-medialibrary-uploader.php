<?php
/**
 * WooThemes Media Library-driven AJAX File Uploader Module (2010-11-05)
 *
 * Slightly modified for use in the Options Framework.
 */

if( is_admin() ) {
	// Load additional css and js for image uploads on the Options Framework page
	$of_page = 'appearance_page_options-framework';
	add_action( "admin_print_styles-$of_page", 'optionsframework_mlu_css', 0 );
	add_action( "admin_print_scripts-$of_page", 'optionsframework_mlu_js', 0 );	
}

/**
 * Sets up a custom post type to attach image to.  This allows us to have
 * individual galleries for different uploaders.
 */

if( ! function_exists( 'optionsframework_mlu_init' ) ) {
	function optionsframework_mlu_init () {
		register_post_type( 'optionsframework', array(
			'labels' => array(
				'name' => __( 'Options Framework Internal Container', 'themeblvd' ),
			),
			'public' => true,
			'show_ui' => false,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => false,
			'supports' => array( 'title', 'editor' ), 
			'query_var' => false,
			'can_export' => true,
			'show_in_nav_menus' => false
		) );
	}
}

/**
 * Adds the Thickbox CSS file and specific loading and button images to the header
 * on the pages where this function is called.
 */

if( ! function_exists( 'optionsframework_mlu_css' ) ) {
	function optionsframework_mlu_css () {
		$_html = '';
		$_html .= '<link rel="stylesheet" href="' . get_option('siteurl') . '/' . WPINC . '/js/thickbox/thickbox.css" type="text/css" media="screen" />' . "\n";
		$_html .= '<script type="text/javascript">
		var tb_pathToImage = "' . get_option('siteurl') . '/' . WPINC . '/js/thickbox/loadingAnimation.gif";
	    var tb_closeImage = "' . get_option('siteurl') . '/' . WPINC . '/js/thickbox/tb-close.png";
	    </script>' . "\n";
	    echo $_html;
	}
}

/**
 * Registers and enqueues (loads) the necessary JavaScript file for working with the
 * Media Library-driven AJAX File Uploader Module.
 */

if( ! function_exists( 'optionsframework_mlu_js' ) ) {
	function optionsframework_mlu_js () {
		// Registers custom scripts for the Media Library AJAX uploader.
		wp_register_script( 'of-medialibrary-uploader', TB_FRAMEWORK_URI .'/admin/options/js/of-medialibrary-uploader.min.js', array( 'jquery', 'thickbox' ) );
		wp_enqueue_script( 'of-medialibrary-uploader' );
		wp_enqueue_script( 'media-upload' );
	}
}

/**
 * Media Uploader Using the WordPress Media Library.
 *
 * Parameters:
 * - string $option_name - Required prefix for form name attributes (Added by ThemeBlvd) 
 * - string $type - Required type of media uploader, standard or slider (Added by ThemeBlvd) 
 * - string $_id - A token to identify this field (the name).
 * - string $_value - The value of the field, if present.
 * - string $_mode - The display mode of the field.
 * - string $_desc - An optional description of the field.
 * - int $_postid - An optional post id (used in the meta boxes).
 * - string $_name - An optional name attribute (Added by Devin)
 * - string $_upload_text - An optional text string for the "Upload" button. (Added by ThemeBlvd)
 *
 * Dependencies:
 * - optionsframework_mlu_get_silentpost()
 */

if( ! function_exists( 'optionsframework_medialibrary_uploader' ) ) {
	function optionsframework_medialibrary_uploader( $option_name, $type, $_id, $_value, $_mode = 'full', $_desc = null, $_postid = 0, $_name = null, $_upload_text = null) {
	
		$output = '';
		$id = '';
		$class = '';
		$int = '';
		$value = array( 'url' => '', 'id' => '' );
		$upload_text = __( 'Upload', 'themeblvd' );
		
		if( $type == 'slider' )
			$name = $option_name.'[image]';
		else
			$name = $option_name.'['.$_id.']';

		$id = strip_tags( strtolower( $_id ) );
		
		// If a value is passed and we don't have a stored value, use the value that's passed through.
		if( $_value )
			$value = $_value;
		
		// If passed name, set it.
		if( $_name ) {
			$name = $name.'['.$_name.']';
			$id = $id.'_'.$_name;
		}

		// Set the ID for the post.
		if( $_postid )
			// Set ID to the one passed in.
			$int = $_postid;
		else
			// Change for each field, using a "silent" post. If no post is present, one will be created.
			$int = optionsframework_mlu_get_silentpost( $id );
		
		// If passed upload button text, set it.
		if( $_upload_text )
			$upload_text = $_upload_text;
		
		if( $value['url'] ) { $class = ' has-file'; }

		if( $type == 'slider' ) {
			// Slider image ID input w/hidden image URL input for user admin display
			$output .= '<span class="locked"><span></span><input id="' . $id . '_id" class="locked upload' . $class . '" type="text" name="'.$name.'[id]" value="' . $value['id'] . '" /></span>' . "\n";
			$output .= '<input id="' . $id . '" class="image-url upload' . $class . '" type="hidden" name="'.$name.'[url]" value="' . $value['url'] . '" />' . "\n";
			$output .= '<p class="explain">'.__( 'You must use the "Get Image" button to modify the image ID for this slide. This is what the locked icon represents.', 'themeblvd' ).'</p>';
		} elseif( $type == 'logo' ){
			$width_name = str_replace( '[image]', '[image_width]', $name );
			$output .= '<input id="' . $id . '" class="image-url upload' . $class . '" type="text" name="'.$name.'" value="' . $value['url'] . '" placeholder="'.__('Image URL', 'themeblvd').'" />' . "\n";
			$output .= '<input id="' . $id . '_width" class="image-width upload' . $class . '" type="text" name="'.$width_name.'" value="' . $value['width'] . '" placeholder="'.__('Width', 'themeblvd').'" />' . "\n";
		} elseif( $type == 'logo_2x' ){
			$output .= '<input id="' . $id . '" class="image-url upload' . $class . '" type="text" name="'.$name.'" value="' . $value['url'] . '" placeholder="'.__('Image URL twice the size of standard image', 'themeblvd').'" />' . "\n";
		} else {
			// Standard image input
			$output .= '<input id="' . $id . '" class="image-url upload' . $class . '" type="text" name="'.$name.'" value="' . $value['url'] . '" placeholder="'.__('Image URL', 'themeblvd').'" />' . "\n";
		}
		$output .= '<input id="upload_' . $id . '" class="upload_button button" type="button" value="' . $upload_text . '" data-upload-type="'. $type .'" rel="' . $int . '" />' . "\n";
		if( $_desc != '' ) {
			$output .= '<span class="of_metabox_desc">' . $_desc . '</span>' . "\n";
		}
		$output .= '<div class="screenshot" id="' . $id . '_image">' . "\n";
		if( $value['url'] != '' ) { 
			$remove = '<a href="javascript:(void);" class="mlu_remove button">Remove</a>';
			$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value['url'] );
			if( $image ) {
				$output .= '<img src="' . $value['url'] . '" alt="" />'.$remove.'';
			} else {
				$parts = explode( "/", $value['url'] );
				for( $i = 0; $i < sizeof( $parts ); ++$i ) {
					$title = $parts[$i];
				}

				// No output preview if it's not an image.			
				$output .= '';
			
				// Standard generic output if it's not an image.	
				$title = __( 'View File', 'themeblvd' );
				$output .= '<div class="no_image"><span class="file_link"><a href="' . $value['url'] . '" target="_blank" rel="external">'.$title.'</a></span>' . $remove . '</div>';
			}	
		}
		$output .= '</div>' . "\n";
		return $output;
	}	
}

/**
 * Uses "silent" posts in the database to store relationships for images.
 * This also creates the facility to collect galleries of, for example, logo images.
 * 
 * Return: $_postid.
 *
 * If no "silent" post is present, one will be created with the type "optionsframework"
 * and the post_name of "of-$_token".
 *
 * Example Usage:
 * optionsframework_mlu_get_silentpost ( 'of_logo' );
 */

if( ! function_exists( 'optionsframework_mlu_get_silentpost' ) ) {
	function optionsframework_mlu_get_silentpost ( $_token ) {
	
		global $wpdb;
		$_id = 0;
	
		// Check if the token is valid against a whitelist.
		// $_whitelist = array( 'of_logo', 'of_custom_favicon', 'of_ad_top_image' );
		// Sanitise the token.
		
		$_token = strtolower( str_replace( ' ', '_', $_token ) );
		
		// if( in_array( $_token, $_whitelist ) ) {
		if( $_token ) {
			
			// Tell the function what to look for in a post.
			
			$_args = array( 'post_type' => 'optionsframework', 'post_name' => 'of-' . $_token, 'post_status' => 'draft', 'comment_status' => 'closed', 'ping_status' => 'closed' );
			
			// Look in the database for a "silent" post that meets our criteria.
			$query = 'SELECT ID FROM ' . $wpdb->posts . ' WHERE post_parent = 0';
			foreach ( $_args as $k => $v ) {
				$query .= ' AND ' . $k . ' = "' . $v . '"';
			} // End FOREACH Loop
			
			$query .= ' LIMIT 1';
			$_posts = $wpdb->get_row( $query );
			
			// If we've got a post, loop through and get it's ID.
			if( count( $_posts ) ) {
				$_id = $_posts->ID;
			} else {
			
				// If no post is present, insert one.
				// Prepare some additional data to go with the post insertion.
				$_words = explode( '_', $_token );
				$_title = join( ' ', $_words );
				$_title = ucwords( $_title );
				$_post_data = array( 'post_title' => $_title );
				$_post_data = array_merge( $_post_data, $_args );
				$_id = wp_insert_post( $_post_data );
			}	
		}
		return $_id;
	}
}

/**
 * Trigger code inside the Media Library popup.
 */

if( ! function_exists( 'optionsframework_mlu_insidepopup' ) ) {
	function optionsframework_mlu_insidepopup () {
		if( isset( $_REQUEST['is_optionsframework'] ) && $_REQUEST['is_optionsframework'] == 'yes' ) {
			add_action( 'admin_head', 'optionsframework_mlu_js_popup' );
			add_filter( 'media_upload_tabs', 'optionsframework_mlu_modify_tabs' );
		}
	}
}

if( ! function_exists( 'optionsframework_mlu_js_popup' ) ) {
	function optionsframework_mlu_js_popup () {
		$_of_title = $_REQUEST['of_title'];
		if( ! $_of_title ) { $_of_title = 'file'; } // End IF Statement
		?>
		<script type="text/javascript">
		<!--
		jQuery(function($) {
			
			jQuery.noConflict();
			
			// Change the title of each tab to use the custom title text instead of "Media File".
			$( 'h3.media-title' ).each ( function () {
				var current_title = $( this ).html();
				var new_title = current_title.replace( 'media file', '<?php echo $_of_title; ?>' );
				$( this ).html( new_title );
			
			} );
			
			// Change the text of the "Insert into Post" buttons to read "Use this File".
			$( '.savesend input.button[value*="Insert into Post"], .media-item #go_button' ).attr( 'value', 'Use this File' );
			
			// Hide the "Insert Gallery" settings box on the "Gallery" tab.
			$( 'div#gallery-settings' ).hide();
			
			// Preserve the "is_optionsframework" parameter on the "delete" confirmation button.
			$( '.savesend a.del-link' ).click ( function () {
			
				var continueButton = $( this ).next( '.del-attachment' ).children( 'a.button[id*="del"]' );
				var continueHref = continueButton.attr( 'href' );
				continueHref = continueHref + '&is_optionsframework=yes';
				continueButton.attr( 'href', continueHref );
			
			} );
			
		});
		-->
		</script>
		<?php
	}
}

/**
 * Triggered inside the Media Library popup to modify the title of the "Gallery" tab.
 */
if( ! function_exists( 'optionsframework_mlu_modify_tabs' ) ) {
	function optionsframework_mlu_modify_tabs ( $tabs ) {
		$tabs['gallery'] = str_replace( __( 'Gallery', 'themeblvd' ), __( 'Previously Uploaded', 'themeblvd' ), $tabs['gallery'] );
		return $tabs;
	}
}