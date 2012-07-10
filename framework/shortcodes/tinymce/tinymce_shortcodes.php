<?php
/**
 *
 * ThemeBlvd WordPress Theme Framework
 * TinyMCE Shortcode Integration
 *
 * @author  Jason Bobich
 * @credit	Based on the work of the Shortcode Ninja plugin by VisualShortcodes.com
 *
 */

class ThemeBlvd_TinyMCE_Shortcodes {
	
	##############################################################
	# Constructor
	##############################################################
	
	function ThemeBlvd_TinyMCE_Shortcodes() {
	
		//admin_init
		add_action( 'admin_init', array( &$this, 'init' ) );
		
		//Only use wp_ajax if user is logged in
		add_action( 'wp_ajax_themeblvd_check_url_action', array( &$this, 'ajax_action_check_url' ) );
	
	}

	##############################################################
	# Get everything started
	##############################################################

	function init() {
	
		if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) && get_user_option('rich_editing') == 'true' )  {
		
		  	// TinyMCE plugin stuff
			add_filter( 'mce_buttons', array( &$this, 'filter_mce_buttons' ) );
			add_filter( 'mce_external_plugins', array( &$this, 'filter_mce_external_plugins' ) );
			
			// TinyMCE shortcode plugin CSS
			wp_register_style( 'themeblvd-tinymce-shortcodes', get_template_directory_uri().'/framework/shortcodes/tinymce/layout/css/tinymce_shortcodes.css' );
			wp_enqueue_style( 'themeblvd-tinymce-shortcodes' );
			
		}
	
	}
	
	##############################################################
	# Filter mce buttons
	##############################################################
	
	function filter_mce_buttons( $buttons ) {
		array_push( $buttons, '|', 'themeblvd_shortcodes_button' );
		return $buttons;
	}
	
	##############################################################
	# Actually add tinyMCE plugin attachment
	##############################################################
	
	function filter_mce_external_plugins( $plugins ) {
        $plugins['ThemeBlvdShortcodes'] = get_template_directory_uri().'/framework/shortcodes/tinymce/editor_plugin.php';
        return $plugins;
	}

	##############################################################
	# Ajax Check
	##############################################################
	
	function ajax_action_check_url() {
		$hadError = true;
		$url = isset( $_REQUEST['url'] ) ? $_REQUEST['url'] : '';
		if ( strlen( $url ) > 0  && function_exists( 'get_headers' ) ) {
			$file_headers = @get_headers( $url );
			$exists       = $file_headers && $file_headers[0] != 'HTTP/1.1 404 Not Found';
			$hadError     = false;
		}
		echo '{ "exists": '. ($exists ? '1' : '0') . ($hadError ? ', "error" : 1 ' : '') . ' }';
		die();
	}


##################################################################
} # end ThemeBlvd_TinyMCE_Shortcodes class
##################################################################

$themeblvd_shortcode_tinymce = new ThemeBlvd_TinyMCE_Shortcodes();
?>