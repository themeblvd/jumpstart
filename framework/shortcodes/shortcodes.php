<?php
/**
 * Theme Blvd Shortcodes
 *
 * (1) Columns
 *		- one_sixth 		=> @since 2.0.0
 *		- one_fourth		=> @since 2.0.0
 *		- one_third			=> @since 2.0.0
 *		- one_half			=> @since 2.0.0
 *		- two_third			=> @since 2.0.0
 *		- three_fourth 		=> @since 2.0.0
 *		- one_fifth			=> @since 2.0.0
 *		- two_fifth			=> @since 2.0.0
 *		- three_fifth		=> @since 2.0.0
 *		- four_fifth		=> @since 2.0.0
 *		- three_tenth		=> @since 2.0.0
 *		- seven_tenth		=> @since 2.0.0
 * (2) Components
 *		- button 			=> @since 2.0.0
 *		- box				=> @since 2.0.0
 *		- alert				=> @since 2.2.0
 *		- icon_list			=> @since 2.0.0
 *		- divider			=> @since 2.0.0
 * 		- progess_bar		=> @since 2.2.0
 *		- popup				=> @since 2.2.0
 * (3) Inline Elements
 *		- icon				=> @since 2.0.0
 *		- icon_link 		=> @since 2.0.0
 *		- highlight			=> @since 2.0.0
 *		- dropcap			=> @since 2.0.0
 *		- label				=> @since 2.2.0
 *		- vector_icon		=> @since 2.2.0
 * (3) Tabs, Accordion, & Toggles
 *		- tabs				=> @since 2.0.0
 *		- accordion			=> @since 2.2.0
 *		- toggle			=> @since 2.0.0
 * (5) Sliders
 * 		- slider			=> @since 2.0.0
 *		- post_grid_slider	=> @since 2.0.0
 *		- post_list_slider	=> @since 2.0.0
 * (6) Display Posts
 *		- post_grid			=> @since 2.0.0
 *		- post_list			=> @since 2.0.0
 *		- mini_post_grid	=> @since 2.1.0
 *		- mini_post_list	=> @since 2.1.0
 */


/*-----------------------------------------------------------------------------------*/
/* WP Auto Formatting Fix w/Raw shortocde
/*-----------------------------------------------------------------------------------*/

/**
 * Content formatter.
 *
 * @since 2.0.0
 *
 * @param sting $content Content
 */

if( ! function_exists( 'themeblvd_content_formatter' ) ) {
	function themeblvd_content_formatter( $content ) {
		$new_content = '';
		$pattern_full = '{(\[raw\].*?\[/raw\])}is';
		$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
		$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
		foreach ($pieces as $piece) {
			if (preg_match($pattern_contents, $piece, $matches)) {
				$new_content .= $matches[1];
			} else {
				$new_content .= shortcode_unautop( wptexturize( wpautop( $piece ) ) );
			}
		}
		return $new_content;
	}
}
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_content', 'wptexturize' );
remove_filter( 'the_content', 'shortcode_unautop' );
add_filter( 'the_content', 'themeblvd_content_formatter', 9 );

/*-----------------------------------------------------------------------------------*/
/* Columns
/*-----------------------------------------------------------------------------------*/

/**
 * Columns
 *
 * @since 2.0.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @param string $content The enclosed content
 * @param string $tag Current shortcode tag
 */

if( ! function_exists( 'themeblvd_shortcode_column' ) ) {
	function themeblvd_shortcode_column( $atts, $content = null, $tag = '' ) {
		// Determine if column is last in row
		$last = '';
		if ( isset( $atts[0] ) && trim( $atts[0] ) == 'last')
			$last = ' last';
		// Determine width of column
		$class = 'column ';
		if( 'one_sixth' == $tag || 'one-sixth' == $tag )
			$class .= 'grid_2';
		else if( 'one_fourth' == $tag || 'one-fourth' == $tag )
			$class .= 'grid_3';
		else if( 'one_third' == $tag || 'one-third' == $tag )
			$class .= 'grid_4';
		else if( 'one_half' == $tag || 'one-half' == $tag )
			$class .= 'grid_6';
		else if( 'two_third' == $tag || 'two-third' == $tag )
			$class .= 'grid_8';
		else if( 'three_fourth' == $tag || 'three-fourth' == $tag )
			$class .= 'grid_9';
		else if( 'one_fifth' == $tag || 'one-fifth' == $tag )
			$class .= 'grid_fifth_1';
		else if( 'two_fifth' == $tag || 'two-fifth' == $tag )
			$class .= 'grid_fifth_2';
		else if( 'three_fifth' == $tag || 'three-fifth' == $tag )
			$class .= 'grid_fifth_3';
		else if( 'four_fifth' == $tag || 'four-fifth' == $tag )
			$class .= 'grid_fifth_4';
		else if( 'three_tenth' == $tag || 'three-tenth' == $tag )
			$class .= 'grid_tenth_3';
		else if( 'seven_tenth' == $tag || 'seven-tenth' == $tag )
			$class .= 'grid_tenth_7';
		// Return column
		$content = '<div class="'.$class.$last.'">'.$content.'</div><!-- .column (end) -->';
		return do_shortcode( $content );
	}
}
add_shortcode( 'one_sixth', 'themeblvd_shortcode_column' ); 		// 1/6
add_shortcode( 'one-sixth', 'themeblvd_shortcode_column' );			// 1/6 (depricated)
add_shortcode( 'one_fourth', 'themeblvd_shortcode_column' ); 		// 1/4
add_shortcode( 'one-fourth', 'themeblvd_shortcode_column' );		// 1/4 (depricated)
add_shortcode( 'one_third', 'themeblvd_shortcode_column' );			// 1/3
add_shortcode( 'one-third', 'themeblvd_shortcode_column' );			// 1/3 (depricated)
add_shortcode( 'one_half', 'themeblvd_shortcode_column' );			// 1/2
add_shortcode( 'one-half', 'themeblvd_shortcode_column' );			// 1/2 (depricated)
add_shortcode( 'two_third', 'themeblvd_shortcode_column' );			// 2/3
add_shortcode( 'two-third', 'themeblvd_shortcode_column' );			// 2/3 (depricated)
add_shortcode( 'three_fourth', 'themeblvd_shortcode_column' );		// 3/4
add_shortcode( 'three-fourth', 'themeblvd_shortcode_column' );		// 3/4 (depricated)
add_shortcode( 'one_fifth', 'themeblvd_shortcode_column' );			// 1/5
add_shortcode( 'one-fifth', 'themeblvd_shortcode_column' );			// 1/5 (depricated)
add_shortcode( 'two_fifth', 'themeblvd_shortcode_column' );			// 2/5
add_shortcode( 'two-fifth', 'themeblvd_shortcode_column' );			// 2/5 (depricated)
add_shortcode( 'three_fifth', 'themeblvd_shortcode_column' );		// 3/5
add_shortcode( 'three-fifth', 'themeblvd_shortcode_column' );		// 3/5 (depricated)
add_shortcode( 'four_fifth', 'themeblvd_shortcode_column' );		// 4/5
add_shortcode( 'four-fifth', 'themeblvd_shortcode_column' );		// 4/5 (depricated)
add_shortcode( 'three_tenth', 'themeblvd_shortcode_column' );		// 3/10
add_shortcode( 'three-tenth', 'themeblvd_shortcode_column' );		// 3/10 (depricated)
add_shortcode( 'seven_tenth', 'themeblvd_shortcode_column' );		// 7/10
add_shortcode( 'seven-tenth', 'themeblvd_shortcode_column' );		// 7/10 (depricated)

/**
 * Clear Row
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_shortcode_clear' ) ) {
	function themeblvd_shortcode_clear() {
		return '<div class="clear"></div>';
	}
}
add_shortcode( 'clear', 'themeblvd_shortcode_clear' );

/*-----------------------------------------------------------------------------------*/
/* Components
/*-----------------------------------------------------------------------------------*/

/**
 * Icon List
 *
 * @since 2.0.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @param string $content The enclosed content
 * @return string $output Content to output for shortcode
 */

if( ! function_exists( 'themeblvd_shortcode_icon_list' ) ) {
	function themeblvd_shortcode_icon_list( $atts, $content = null ) {
	    $default = array(
			'style' => '',					// (deprecated) check, crank, delete, doc, plus, star, star2, warning, write
			'icon'	=> 'caret-right',		// Any fontawesome icon
			'color'	=> ''					// Optional color hex for icon - ex: #000000
	    );
	    extract( shortcode_atts( $default, $atts ) );
	    
	    
	    // For those using old "style" method, this will 
	    // match the old style choices to a fontawesome icon 
	    // and add in a relevant color.
	    if( $style ){
		    switch( $style ){
			    case 'check' :
			    	$icon = 'ok-sign';
			    	$color = '#59f059';
			    	break;
			    case 'crank' :
			    	$icon = 'cog';
			    	$color = '#d7d7d7';
			    	break;
			    case 'delete' :
			    	$icon = 'remove-sign';
			    	$color = '#ff7352';
			    	break;
			    case 'doc' :
			    	$icon = 'file';
			    	$color = '#b8b8b8';
			    	break;
			    case 'plus' :
			    	$icon = 'plus-sign';
			    	$color = '#59f059';
			    	break;
			    case 'star' :
			    	$icon = 'star';
			    	$color = '#eec627';
			    	break;
			    case 'star2' :
			    	$icon = 'star';
			    	$color = '#a7a7a7';
			    	break;
			    case 'warning' :
			    	$icon = 'warning-sign';
			    	$color = '#ffd014';
			    	break;
			    case 'write' :
			    	$icon = 'pencil';
			    	$color = '#ffd014';
			    	break;
		    }
	    }
	    
	    // Color
	    $color_css = '';
	    if( $color )
	    	$color_css = ' style="color:'.$color.';"';
	    
	    // Add in fontawesome icon
	    $content = str_replace('<ul>', '<ul class="tb-icon-list">', $content );
	    $content = str_replace('<li>', '<li><i class="icon-'.$icon.'"'.$color_css.'></i> ', $content );
	    
	    // Output
	    $output = do_shortcode($content);
	    return $output;
	}
}
add_shortcode( 'icon_list', 'themeblvd_shortcode_icon_list' );

/**
 * Button
 *
 * @since 2.0.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @param string $content The enclosed content
 * @return string $output Content to output for shortcode
 */

if( ! function_exists( 'themeblvd_shortcode_button' ) ) {
	function themeblvd_shortcode_button( $atts, $content = null ) {
		$output = '';
		$default = array(
            'link' => 'http://www.google.com',
            'color' => 'default',
            'target' => '_self',
            'size' => 'small',
            'class' => '',
            'title' => '',
            'icon_before' => '',
            'icon_after' => ''
	    );
	    extract( shortcode_atts( $default, $atts ) );
	    $output = themeblvd_button( $content, $link, $color, $target, $size, $class, $title, $icon_before, $icon_after );
	    return $output;
	}
}
add_shortcode( 'button', 'themeblvd_shortcode_button' );

/**
 * Info Boxes
 *
 * @since 2.0.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @param string $content The enclosed content
 * @return string $output Content to output for shortcode
 */

if( ! function_exists( 'themeblvd_shortcode_box' ) ) {
	function themeblvd_shortcode_box( $atts, $content = null ) {
		$output = '';
		$has_icon = '';
		$default = array(
            'style' => 'blue', // blue, green, grey, orange, purple, red, teal, yellow
            'icon' => ''
	    );
	    extract( shortcode_atts( $default, $atts ) );
	    // Classes
	    $classes = 'info-box info-box-'.$style;	    
	    // Add icon
	    if( $icon ) {
	    	$classes .= ' info-box-has-icon';
	    	$content = '<i class="icon icon-'.$icon.'"></i>'.$content;
	    }
	    $output = '<div class="'.$classes.'">'.apply_filters('themeblvd_the_content', $content).'</div>';
	    return $output;
	}
}
add_shortcode( 'box', 'themeblvd_shortcode_box' );

/**
 * Alerts (from Bootstrap)
 *
 * @since 2.2.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @param string $content The enclosed content
 * @return string $output Content to output for shortcode
 */

if( ! function_exists( 'themeblvd_shortcode_alert' ) ) {
	function themeblvd_shortcode_alert( $atts, $content = null ) {
		$output = '';
		$classes = 'alert';
		$default = array(
            'style' => 'blue', // info, success, danger, 'message'
            'close' => 'false' // true, false
	    );
	    extract( shortcode_atts( $default, $atts ) );
	    // CSS classes
	    if( in_array( $style, array( 'info', 'success', 'danger', 'message' ) ) )
	    	$classes .= ' alert-'.$style;
	    if( $close == 'true' ) 
	    	$classes .= ' fade in';
	    // Start output
	    $output = '<div class="'.$classes.'">';
	    // Add a close button?
	    if( $close == 'true' )
	    	$output .= '<button type="button" class="close" data-dismiss="alert">×</button>';
	    // Finish output
	    $output .= $content.'</div><!-- .alert (end) -->';
	    return $output;
	}
}
add_shortcode( 'alert', 'themeblvd_shortcode_alert' );

/**
 * Divider
 *
 * @since 2.0.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @param string $content The enclosed content
 */

if( ! function_exists( 'themeblvd_shortcode_divider' ) ) {
	function themeblvd_shortcode_divider( $atts, $content = null ) {
	    $default = array(
            'style' => 'solid' // dashed, shadow, solid
	    );
	    extract( shortcode_atts( $default, $atts ) );
	    return themeblvd_divider( $style );
	}
}
add_shortcode( 'divider', 'themeblvd_shortcode_divider' );

/**
 * Progress Bar (from Bootstrap)
 *
 * @since 2.2.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @return string $output Content to output for shortcode
 */

if( ! function_exists( 'themeblvd_shortcode_progress_bar' ) ) {
	function themeblvd_shortcode_progress_bar( $atts ) {
	    $default = array(
            'color' 	=> '',		// default, danger, success, info, warning
            'percent' 	=> '100',	// Percent of bar - 30, 60, 80, etc.
            'striped' 	=> 'false',	// true, false	
            'animate' 	=> 'false'	// true, false
	    );
	    extract( shortcode_atts( $default, $atts ) );
	    
	    $classes = 'progress';
	    
	    // Color
	    if( $color && $color != 'default' )
	    	$classes .= ' progress-'.$color;
	    
	    // Striped?
	    if( $striped == 'true' )
	    	$classes .= ' progress-striped';
	    
	    // Animated?
	    if( $animate == 'true' )
	    	$classes .= ' active';
	    
	    // Output
	    $output = '<div class="'.$classes.'"><div class="bar" style="width: '.$percent.'%;"></div></div>';
	    
	    return $output;
	}
}
add_shortcode( 'progress_bar', 'themeblvd_shortcode_progress_bar' );

/**
 * Popup (from Bootstrap)
 *
 * @since 2.2.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @param string $content Content in shortcode
 * @return string $output Content to output for shortcode
 */

if( ! function_exists( 'themeblvd_shortcode_popup' ) ) {
	function themeblvd_shortcode_popup( $atts, $content = null ) {
	    $default = array(
	    	'text' 			=> 'Link Text', // Text for link or button leading to popup
			'title' 		=> '', 			// Title for anchor, will default to "text" option
			'color' 		=> '', 			// Color of button, only applies if button style is selected
			'icon_before'	=> '', 			// Icon before button or link's text
			'icon_after' 	=> '', 			// Icon after button or link's text
			'header' 		=> '', 			// Header text for popup
			'animate' 		=> 'false'		// Whether popup slides in or not - true, false 
	    );
	    extract( shortcode_atts( $default, $atts ) );
	    
	    // ID for popup
	    $popup_id = uniqid( 'popup_'.rand() );
	    
	    // Button/Link
	    $link = '';
	    if( $title )
	    	$title = $text;
	    $link = themeblvd_button( $text, '#'.$popup_id, $color, null, $size, null, $title, $icon_before, $icon_after, 'data-toggle="modal"' );
		$link = apply_filters('themeblvd_the_content', $link);
	    
	    // Classes for popup
	    $classes = 'modal hide';
	    if( $animate == 'true' )
	    	$classes .= ' fade';
	    
	    // Header
	    $header_html = '';
	    if( $header ){
	    	$header_html .= '<div class="modal-header">';
	    	$header_html .= '<button type="button" class="close" data-dismiss="modal">×</button>';
	    	$header_html .= '<h3>'.$header.'</h3>';
	    	$header_html .= '</div><!-- modal-header (end) -->';
	    }
	    
	    // Output
	    $output  = $link;
	    $output .= '<div class="'.$classes.'" id="'.$popup_id.'">';
	    $output .= $header_html;
	    $output .= '<div class="modal-body">';
	    $output .= apply_filters('themeblvd_the_content', $content);
	    $output .= '</div><!-- .modal-body (end) -->';
	    $output .= '<div class="modal-footer">';
	    $output .= '<a href="#" class="btn" data-dismiss="modal">'.themeblvd_get_local('close').'</a>';
	    $output .= '</div><!-- .modal-footer (end) -->';
	    $output .= '</div><!-- .modal (end) -->';
	    return $output;
	}
}
add_shortcode( 'popup', 'themeblvd_shortcode_popup' );

/*-----------------------------------------------------------------------------------*/
/* Inline Elements
/*-----------------------------------------------------------------------------------*/

/**
 * 48px Icon (NOT Font Awesome icons)
 *
 * @since 2.0.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @param string $content The enclosed content
 * @return string $output Content to output for shortcode
 */

if( ! function_exists( 'themeblvd_shortcode_icon' ) ) {
	function themeblvd_shortcode_icon( $atts, $content = null ) {
		$output = '';
		$default = array(
            'image' => 'accepted',
            'align' => 'left' // left, right, center, none
	    );
	    extract( shortcode_atts( $default, $atts ) );
	    $align != 'none' ? $align = ' class="align'.$align.'"' : $align = null;
	    $output = '<img src="'.get_template_directory_uri().'/framework/frontend/assets/images/shortcodes/icons/'.$image.'.png"'.$align.' />';
		return $output;
	}
}
add_shortcode( 'icon', 'themeblvd_shortcode_icon' );

/**
 * Icon Link
 *
 * @since 2.0.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @param string $content The enclosed content
 * @return string $output Content to output for shortcode
 */

if( ! function_exists( 'themeblvd_shortcode_icon_link' ) ) {
	function themeblvd_shortcode_icon_link( $atts, $content = null ) {	    
	    $default = array(
            'icon' => '', // alert, approved, camera, cart, doc, download, media, note, notice, quote, warning
            'link' => 'http://www.google.com',
            'target' => '_self',
            'class' => '',
            'title' => ''
	    );
	    extract( shortcode_atts( $default, $atts ) );
	    
	    // Convert icons used with older framework versions to fontawesome
	    // alert, approved, camera, cart, doc, download, media, note, notice, quote, warning
	    // Note: "camera" and "download" work so can be excluded below.
	    switch( $icon ) {
		    case 'alert' :
		    	$icon = 'exclamation-sign';
		    	break;
		    case 'approved' :
				$icon = 'check';		    
		    	break;
		    case 'cart' :
		    	$icon = 'shopping-cart';	
		    	break;
		    case 'doc' :
		    	$icon = 'file';	
		    	break;
		    case 'media' :
		    	$icon = 'hdd'; // Kind of ironic... The CD icon gets replaced with the harddrive icon in the update for the "media" icon.
		    	break;
		    case 'note' :
		    	$icon = 'pencil';	
		    	break;
		    case 'notice' :
		    	$icon = 'exclamation-sign'; // Was always the same as "alert"	
		    	break;
		    case 'quote' :
		    	$icon = 'comment';	
		    	break;
		    case 'warning' :
		    	$icon = 'warning-sign';	
		    	break;
	    }
	    
	    if( ! $title ) $title = $content;
	    if( $class ) $class = ' '.$class;
	    $output  = '<span class="tb-icon-link'.$class.'">'; // Can't use class starting in "icon-" or it will conflict with Bootstrap
	    $output .= '<i class="icon-'.$icon.'"></i>';
	    $output .= '<a href="'.$link.'" title="'.$title.'" class="icon-link-'.$icon.'" target="'.$target.'">'.$content.'</a>';
	    $output .= '</span>';
	    return $output;
	}
}
add_shortcode( 'icon_link', 'themeblvd_shortcode_icon_link' );

/**
 * Text Highlight
 *
 * @since 2.0.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @param string $content The enclosed content
 */

if( ! function_exists( 'themeblvd_shortcode_highlight' ) ) {
	function themeblvd_shortcode_highlight( $atts, $content = null ) {
	    return '<span class="text-highlight">'.do_shortcode($content).'</span><!-- .text-highlight (end) -->';
	}
}
add_shortcode( 'highlight', 'themeblvd_shortcode_highlight' );

/**
 * Dropcaps
 *
 * @since 2.0.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @param string $content The enclosed content
 */

if( ! function_exists( 'themeblvd_shortcode_dropcap' ) ) {
	function themeblvd_shortcode_dropcap( $atts, $content = null ) {
	    return '<span class="dropcap">'.do_shortcode($content).'</span><!-- .dropcap (end) -->';
	}
}
add_shortcode( 'dropcap', 'themeblvd_shortcode_dropcap' );

/**
 * Labels (straight from Bootstrap)
 * 
 * <span class="label label-success">Success</span>
 *
 * @since 2.2.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @param string $content The enclosed content
 */

if( ! function_exists( 'themeblvd_shortcode_label' ) ) {
	function themeblvd_shortcode_label( $atts, $content = null ) {
	   	$default = array(
            'style' => '', // default, success, warning, important, info, inverse
            'icon'	=> ''
	    );
	    extract( shortcode_atts( $default, $atts ) );
	   	$class = 'label'; 
	    if( $style && $style != 'default' )
	    	$class .= ' label-'.$style;
	    if( $icon )
	    	$content = '<i class="icon-'.$icon.'"></i> '.$content;
	    return '<span class="'.$class.'">'.do_shortcode($content).'</span><!-- .label (end) -->';
	}
}
add_shortcode( 'label', 'themeblvd_shortcode_label' );

/**
 * Vector Icon (from Bootstrap and Font Awesome)
 * 
 * <i class="icon-{whatever}"></i>
 *
 * @since 2.2.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @param string $content The enclosed content
 */

if( ! function_exists( 'themeblvd_shortcode_vector_icon' ) ) {
	function themeblvd_shortcode_vector_icon( $atts ) {
	   	$default = array(
            'icon' => 'pencil',
            'size'	=> ''
	    );
	    extract( shortcode_atts( $default, $atts ) );
	   	$size_style = '';
	   	if( $size )
	   		$size_style = ' style="font-size:'.$size.';"';
	    return '<i class="icon-'.$icon.'"'.$size_style.'></i>';
	}
}
add_shortcode( 'vector_icon', 'themeblvd_shortcode_vector_icon' );

/*-----------------------------------------------------------------------------------*/
/* Tabs, Accordion, & Toggles
/*-----------------------------------------------------------------------------------*/

/**
 * Tabs
 *
 * @since 2.0.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @param string $content The enclosed content
 * @return string $output Content to output for shortcode
 */

if( ! function_exists( 'themeblvd_shortcode_tabs' ) ) {
	function themeblvd_shortcode_tabs( $atts, $content = null ) {
	    $default = array(
            'style' 		=> 'framed', 		// framed, open
            'nav'			=> 'tabs_above',	// tabs_above, tabs_right, tabs_below, tabs_left, pills_above, pills_below
            'height' 		=> '' 				// Optional fixed height for inside of tabs
	    );
	    extract( shortcode_atts( $default, $atts ) );
	    if( isset( $atts['style'] ) ) unset( $atts['style'] );
	    if( isset( $atts['nav'] ) ) unset( $atts['nav'] );
	    if( isset( $atts['height'] ) ) unset( $atts['height'] );
	    $id = uniqid( 'tabs_'.rand() );
	    $num = count( $atts ) - 1;
		$i = 1;
		$options = array(
	    	'setup' => array(
	    		'num' 	=> $num,
	    		'style' => $style,
	    		'nav' 	=> $nav,
	    		'names' => array()
	    	),
	    	'height' => $height
	    );
	    if( is_array( $atts ) && ! empty( $atts ) ) {
			foreach( $atts as $key => $tab ) {
				$options['setup']['names']['tab_'.$i] = $tab;
				$tab_content = explode( '[/'.$key.']', $content );
				$tab_content = explode( '['.$key.']', $tab_content[0] );
				$options['tab_'.$i] = array(
					'type' => 'raw',
					'raw' => $tab_content[1],
				);
				$i++;
			}
			$output = '<div class="element element-tabs'.themeblvd_get_classes( 'element_tabs', true ).'">'.themeblvd_tabs( $id, $options ).'</div><!-- .element (end) -->';
		} else {
			$output = '<p class="tb-warning">'.__( 'No tabs found', TB_GETTEXT_DOMAIN ).'</p>';
		}
	    return $output;
	}
}
add_shortcode( 'tabs', 'themeblvd_shortcode_tabs' );

/**
 * Accordion
 *
 * @since 2.2.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @param string $content The enclosed content
 * @return string $output Content to output for shortcode
 */

if( ! function_exists( 'themeblvd_shortcode_accordion' ) ) {
	function themeblvd_shortcode_accordion( $atts, $content = null ) {
		$accordion_id = uniqid( 'accordion_'.rand() );
		return '<div id="'.$accordion_id.'" class="tb-accordion">'.do_shortcode($content).'</div>';
	}
}
add_shortcode( 'accordion', 'themeblvd_shortcode_accordion' );

/**
 * Toggles
 *
 * @since 2.0.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @param string $content The enclosed content
 * @return string $output Content to output for shortcode
 */

if( ! function_exists( 'themeblvd_shortcode_toggle' ) ) {
	function themeblvd_shortcode_toggle( $atts, $content = null ) {		
		if( isset( $atts[0] ) ) $last = ' accordion-group-last';
		$default = array(
	        'title' => ''
	    );
		extract( shortcode_atts( $default, $atts ) );
		// Individual toggle ID (NOT the Accordion ID)
		$toggle_id = uniqid( 'toggle_'.rand() );
		// Start output
		$output  = '<div class="accordion-group'.$last.'">';
		$output .= '<div class="accordion-heading">';               
		$output .= '<a class="accordion-toggle" data-toggle="collapse" href="#'.$toggle_id.'"><i class="icon-plus-sign switch-me"></i> '.$title.'</a>';
		$output .= '</div><!-- .accordion-heading (end) -->';
		$output .= '<div id="'.$toggle_id.'" class="accordion-body collapse">';
		$output .= '<div class="accordion-inner">';
		$output .= apply_filters( 'themeblvd_the_content', $content );
		$output .= '</div><!-- .accordion-inner (end) -->';
		$output .= '</div><!-- .accordion-body (end) -->';
		$output .= '</div><!-- .accordion-group (end) -->';
	    return $output;
	}
}
add_shortcode( 'toggle', 'themeblvd_shortcode_toggle' );

/*-----------------------------------------------------------------------------------*/
/* Sliders
/*-----------------------------------------------------------------------------------*/

/**
 * Custom slider
 *
 * @since 2.0.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 */

if( ! function_exists( 'themeblvd_shortcode_slider' ) ) {
	function themeblvd_shortcode_slider( $atts ) {
		$default = array(
            'id' => ''
	    );
	    extract( shortcode_atts( $default, $atts ) );
		// CSS classes for element
		$slider_id = themeblvd_post_id_by_name( $id, 'tb_slider' );
		$type = get_post_meta( $slider_id, 'type', true );
		$classes = 'element element-slider element-slider-'.$type.themeblvd_get_classes( 'element_slider', true );
		// Output
		ob_start();
		echo '<div class="'.$classes.'">';
		echo '<div class="element-inner">';
		echo '<div class="element-inner-wrap">';
		echo '<div class="grid-protection">';
		themeblvd_slider( $id );
		echo '</div><!-- .grid-protection (end) -->';
		echo '</div><!-- .element-inner-wrap (end) -->';
		echo '</div><!-- .element-inner (end) -->';
		echo '</div><!-- .element (end) -->';
		return ob_get_clean();
	}
}
add_shortcode( 'slider', 'themeblvd_shortcode_slider' );

/**
 * Grid List
 *
 * @since 2.0.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 */

if( ! function_exists( 'themeblvd_shortcode_post_grid_slider' ) ) {
	function themeblvd_shortcode_post_grid_slider( $atts ) {
		$default = array(
            'fx' => 'slide', 			// fx: Transition of slider - fade, slide
            'timeout' => 0, 			// timeout: Seconds in between transitions, 0 for no auto-advancing
            'nav_standard' => 1, 		// nav_standard: Show standard nav dots to control slider - true or false
            'nav_arrows' => 1, 			// nav_arrows: Show directional arrows to control slider - true or false
            'pause_play' => 1, 			// pause_play: Show pause/play button - true or false
            'categories' => '',			// categories: Categories to include, category slugs separated by commas, or blank for all categories
            'columns' => 3,				// columns: Number of posts per row
            'rows' => 3,				// rows: Number of rows per slide
            'numberposts' => -1,		// numberposts: Total number of posts, -1 for all posts
            'orderby' => 'post_date',	// orderby: post_date, title, comment_count, rand
            'order' => 'DESC',			// order: DESC, ASC
            'offset' => 0				// offset: Number of posts to offset off the start, defaults to 0
	    ); 
	    extract( shortcode_atts( $default, $atts ) );
	    // Generate unique ID
		$id = uniqid( 'grid_'.rand() );
	    // Build $options array compatible to element's function
	    $options = array(
	    	'fx' => $fx,
	    	'timeout' => $timeout,
            'categories' => array('all' => 0),
            'columns' => $columns,
            'rows' => $rows,
            'numberposts' => $numberposts,
            'orderby' => $orderby,
            'order' => $order,
            'offset' => $offset
	    );
	    
	    // Add in the booleans
	    if( $nav_standard === 'true' )
	    	$options['nav_standard'] = 1;
	    else if( $nav_standard === 'false' )
	    	$options['nav_standard'] = 0;
	    else
	    	$options['nav_standard'] = $default['nav_standard'];

	    if( $nav_arrows === 'true' )
	    	$options['nav_arrows'] = 1;
	    else if( $nav_arrows === 'false' )
	    	$options['nav_arrows'] = 0;
	    else
	    	$options['nav_arrows'] = $default['nav_arrows'];
	    
	    if( $pause_play === 'true' )
	    	$options['pause_play'] = 1;
	    else if( $pause_play === 'false' )
	    	$options['pause_play'] = 0;
	    else
	    	$options['pause_play'] = $default['pause_play'];
	    
	    // Build categories array
	    if( $categories ) {
	    	$formatted_categories = explode( ',', $categories );
	    	foreach( $formatted_categories as $category ) {
	    		$options['categories'][$category] = 1;
	    	}
	    } else {
	    	$options['categories']['all'] = 1;
	    }
	    
		// Output
		ob_start();
		echo '<div class="element element-post_grid_slider'.themeblvd_get_classes( 'element_post_grid_slider', true ).'">';
		echo '<div class="element-inner">';
		echo '<div class="element-inner-wrap">';
		echo '<div class="grid-protection">';
		themeblvd_post_slider( $id, $options, 'grid', 'primary' );
		echo '</div><!-- .grid-protection (end) -->';
		echo '</div><!-- .element-inner-wrap (end) -->';
		echo '</div><!-- .element-inner (end) -->';
		echo '</div><!-- .element (end) -->';
		return ob_get_clean();
	}
}
add_shortcode( 'post_grid_slider', 'themeblvd_shortcode_post_grid_slider' );

/**
 * Post Slider
 *
 * @since 2.0.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 */

if( ! function_exists( 'themeblvd_shortcode_post_list_slider' ) ) {
	function themeblvd_shortcode_post_list_slider( $atts ) {
		$default = array(
            'fx' => 'slide', 				// fx: Transition of slider - fade, slide
            'timeout' => 0, 				// timeout: Seconds in between transitions, 0 for no auto-advancing
            'nav_standard' => 1, 			// nav_standard: Show standard nav dots to control slider - true or false
            'nav_arrows' => 1, 				// nav_arrows: Show directional arrows to control slider - true or false
            'pause_play' => 1, 				// pause_play: Show pause/play button - true or false
            'categories' => '',				// categories: Categories to include, category slugs separated by commas, or blank for all categories
            'thumbs' => 'default',			// thumbs: Size of post thumbnails - default, small, full, hide
            'post_content' => 'default',	// content: Show excerpts or full content - default, content, excerpt
            'posts_per_slide' => 3,			// posts_per_slide: Number of posts per slide.
            'numberposts' => -1,			// numberposts: Total number of posts, -1 for all posts
            'orderby' => 'post_date',		// orderby: post_date, title, comment_count, rand
            'order' => 'DESC',				// order: DESC, ASC
            'offset' => 0					// offset: Number of posts to offset off the start, defaults to 0
	    ); 
	    extract( shortcode_atts( $default, $atts ) );	    
	    // Generate unique ID
		$id = uniqid( 'list_'.rand() );
	    // Build $options array compatible to element's function
	    $options = array(
	    	'fx' => $fx,
	    	'timeout' => $timeout,
            'categories' => array('all' => 0),
			'thumbs' => $thumbs,	
			'content' => $post_content,
			'posts_per_slide' => $posts_per_slide,
            'numberposts' => $numberposts,
            'orderby' => $orderby,
            'order' => $order,
            'offset' => $offset
	    );
	    
	    // Add in the booleans
	    if( $nav_standard === 'true' )
	    	$options['nav_standard'] = 1;
	    else if( $nav_standard === 'false' )
	    	$options['nav_standard'] = 0;
	    else
	    	$options['nav_standard'] = $default['nav_standard'];

	    if( $nav_arrows === 'true' )
	    	$options['nav_arrows'] = 1;
	    else if( $nav_arrows === 'false' )
	    	$options['nav_arrows'] = 0;
	    else
	    	$options['nav_arrows'] = $default['nav_arrows'];
	    
	    if( $pause_play === 'true' )
	    	$options['pause_play'] = 1;
	    else if( $pause_play === 'false' )
	    	$options['pause_play'] = 0;
	    else
	    	$options['pause_play'] = $default['pause_play'];
	    
	    // Build categories array
	    if( $categories ) {
	    	$formatted_categories = explode( ',', $categories );
	    	foreach( $formatted_categories as $category ) {
	    		$options['categories'][$category] = 1;
	    	}
	    } else {
	    	$options['categories']['all'] = 1;
	    }
	    
		// Output
		ob_start();
		echo '<div class="element element-post_list_slider'.themeblvd_get_classes( 'element_post_list_slider', true ).'">';
		echo '<div class="element-inner">';
		echo '<div class="element-inner-wrap">';
		echo '<div class="grid-protection">';
		themeblvd_post_slider( $id, $options, 'list', 'primary' );
		echo '</div><!-- .grid-protection (end) -->';
		echo '</div><!-- .element-inner-wrap (end) -->';
		echo '</div><!-- .element-inner (end) -->';
		echo '</div><!-- .element (end) -->';
		return ob_get_clean();
	}
}
add_shortcode( 'post_list_slider', 'themeblvd_shortcode_post_list_slider' );

/*-----------------------------------------------------------------------------------*/
/* Display Posts
/*-----------------------------------------------------------------------------------*/

/**
 * Post Grid
 *
 * @since 2.0.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 */

if( ! function_exists( 'themeblvd_shortcode_post_grid' ) ) {
	function themeblvd_shortcode_post_grid( $atts ) {
		$default = array(
            'categories' => '',					// categories: Categories to include, category slugs separated by commas, or blank for all categories
            'columns' => 3,						// columns: Number of posts per row
            'rows' => 3,						// rows: Number of rows per slide
            'orderby' => 'post_date',			// orderby: post_date, title, comment_count, rand
            'order' => 'DESC',					// order: DESC, ASC
            'offset' => 0,						// offset: Number of posts to offset off the start, defaults to 0
            'link' => 0,						// link: Show link after posts, true or false
            'link_text' => 'View All Posts', 	// link_text: Text for the link
            'link_url' => 'http://google.com',	// link_url: URL where link should go
            'link_target' => '_self', 			// link_target: Where link opens - _self, _blank
            'query' => '' 						// custom query string
	    ); 
	    extract( shortcode_atts( $default, $atts ) );
	    
	    // Build $options array compatible to element's function
	    $options = array(
            'categories' => array('all' => 0),
            'columns' => $columns,
            'rows' => $rows,
            'orderby' => $orderby,
            'order' => $order,
            'offset' => $offset,
            'query' => $query,
            'link_text' => $link_text,
            'link_url' => $link_url,
            'link_target' => $link_target
	    );
	    
	    // Add in the booleans
	    if( $link === 'true' )
	    	$options['link'] = 1;
	    else if( $link === 'false' )
	    	$options['link'] = 0;
	    else
	    	$options['link'] = $default['link'];
	    
	    // Build categories array
	    if( $categories ) {
	    	$formatted_categories = explode( ',', $categories );
	    	foreach( $formatted_categories as $category ) {
	    		$options['categories'][$category] = 1;
	    	}
	    } else {
	    	$options['categories']['all'] = 1;
	    }
	    
		// Output
		ob_start();
		echo '<div class="element element-post_grid'.themeblvd_get_classes( 'element_post_grid', true ).'">';
		echo '<div class="element-inner">';
		echo '<div class="element-inner-wrap">';
		echo '<div class="grid-protection">';
		themeblvd_posts( $options, 'grid', 'primary' );
		echo '</div><!-- .grid-protection (end) -->';
		echo '</div><!-- .element-inner-wrap (end) -->';
		echo '</div><!-- .element-inner (end) -->';
		echo '</div><!-- .element (end) -->';
		return ob_get_clean();
	}
}
add_shortcode( 'post_grid', 'themeblvd_shortcode_post_grid' );

/**
 * Post List
 *
 * @since 2.0.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 */

if( ! function_exists( 'themeblvd_shortcode_post_list' ) ) {
	function themeblvd_shortcode_post_list( $atts ) {
		$default = array(
            'categories' => '',					// categories: Categories to include, category slugs separated by commas, or blank for all categories
			'thumbs' => 'default',				// thumbs: Size of post thumbnails - default, small, full, hide
			'post_content' => 'default',		// content: Show excerpts or full content - default, content, excerpt
			'numberposts' => 3,					// numberposts: Total number of posts, -1 for all posts            
            'orderby' => 'post_date',			// orderby: post_date, title, comment_count, rand
            'order' => 'DESC',					// order: DESC, ASC
            'offset' => 0,						// offset: Number of posts to offset off the start, defaults to 0
            'link' => 0,						// link: Show link after posts, true or false
            'link_text' => 'View All Posts', 	// link_text: Text for the link
            'link_url' => 'http://google.com',	// link_url: URL where link should go
            'link_target' => '_self', 			// link_target: Where link opens - _self, _blank
            'query' => '' 						// custom query string
	    ); 
	    extract( shortcode_atts( $default, $atts ) );
	    
	    // Build $options array compatible to element's function
	    $options = array(
            'categories' => array('all' => 0),
            'thumbs' => $thumbs,
			'content' => $post_content,
			'numberposts' => $numberposts,
            'orderby' => $orderby,
            'order' => $order,
            'offset' => $offset,
            'query' => $query,
            'link_text' => $link_text,
            'link_url' => $link_url,
            'link_target' => $link_target
	    );
	    
	    // Add in the booleans
	    if( $link === 'true' )
	    	$options['link'] = 1;
	    else if( $link === 'false' )
	    	$options['link'] = 0;
	    else
	    	$options['link'] = $default['link'];
	    
	    // Build categories array
	    if( $categories ) {
	    	$formatted_categories = explode( ',', $categories );
	    	foreach( $formatted_categories as $category ) {
	    		$options['categories'][$category] = 1;
	    	}
	    } else {
	    	$options['categories']['all'] = 1;
	    }
	    
		// Output
		ob_start();
		echo '<div class="element element-post_list'.themeblvd_get_classes( 'element_post_list', true ).'">';
		echo '<div class="element-inner">';
		echo '<div class="element-inner-wrap">';
		echo '<div class="grid-protection">';
		themeblvd_posts( $options, 'list', 'primary' );
		echo '</div><!-- .grid-protection (end) -->';
		echo '</div><!-- .element-inner-wrap (end) -->';
		echo '</div><!-- .element-inner (end) -->';
		echo '</div><!-- .element (end) -->';
		return ob_get_clean();
	}
}
add_shortcode( 'post_list', 'themeblvd_shortcode_post_list' );

/**
 * Mini Post Grid
 *
 * @since 2.1.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @return string $output Content to output for shortcode
 */

if( ! function_exists( 'themeblvd_shortcode_mini_post_grid' ) ) {
	function themeblvd_shortcode_mini_post_grid( $atts ) {
		// Default shortcode atts
		$default = array(
		    'categories' => '',					// categories: Categories to include, category slugs separated by commas, or blank for all categories
			'numberposts' => 4,					// numberposts: Total number of posts, -1 for all posts         
		    'orderby' => 'post_date',			// orderby: post_date, title, comment_count, rand
		    'order' => 'DESC',					// order: DESC, ASC
		    'offset' => 0,						// offset: Number of posts to offset off the start, defaults to 0
		    'query' => '',						// custom query string
		    'thumb' => 'smaller',				// thumbnail size - small, smaller, or smallest
		    'align' => 'left',					// alignment of grid - left, right, or center
		    'gallery' => ''						// post ID to pull gallery attachments from, only used if not blank
		); 
		extract( shortcode_atts( $default, $atts ) );
		// Build query
		if( ! $query ) {
			$query  = 'category_name='.$categories;
			$query .= '&numberposts='.$numberposts;
			$query .= '&orderby='.$orderby;
			$query .= '&order='.$order;
			$query .= '&offset='.$offset;
		}
		// Output
		$output = themeblvd_get_mini_post_grid( $query, $align, $thumb, $gallery );
		return $output;
	   
	}
}
add_shortcode( 'mini_post_grid', 'themeblvd_shortcode_mini_post_grid' );

/**
 * Mini Post List
 *
 * @since 2.1.0
 *
 * @param array $atts Standard WordPress shortcode attributes
 * @return string $output Content to output for shortcode
 */

if( ! function_exists( 'themeblvd_shortcode_mini_post_list' ) ) {
	function themeblvd_shortcode_mini_post_list( $atts ) {
		// Default shortcode atts
		$default = array(
		    'categories' => '',					// categories: Categories to include, category slugs separated by commas, or blank for all categories
			'numberposts' => 4,					// numberposts: Total number of posts, -1 for all posts         
		    'orderby' => 'post_date',			// orderby: post_date, title, comment_count, rand
		    'order' => 'DESC',					// order: DESC, ASC
		    'offset' => 0,						// offset: Number of posts to offset off the start, defaults to 0
		    'query' => '',						// custom query string
		    'thumb' => 'smaller',				// thumbnail size - small, smaller, smallest, or hide
		    'meta' => 'show'					// show meta or not - show or hide
		); 
		extract( shortcode_atts( $default, $atts ) );
		// Build query
		if( ! $query ) {
			$query  = 'category_name='.$categories;
			$query .= '&numberposts='.$numberposts;
			$query .= '&orderby='.$orderby;
			$query .= '&order='.$order;
			$query .= '&offset='.$offset;
		}
		// Format thumbnail size
		if( $thumb == 'hide' ) 
			$thumb = false;
		// Format meta
		$meta == 'show' ? $meta = true : $meta = false;
		// Output
		$output = themeblvd_get_mini_post_list( $query, $thumb, $meta );
		return $output;
	}
}
add_shortcode( 'mini_post_list', 'themeblvd_shortcode_mini_post_list' );