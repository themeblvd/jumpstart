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
 * (2) HTML
 *		- button 			=> @since 2.0.0
 *		- icon				=> @since 2.0.0
 *		- box				=> @since 2.0.0
 *		- icon_list			=> @since 2.0.0
 *		- icon_link 		=> @since 2.0.0
 *		- highlight			=> @since 2.0.0
 *		- dropcap			=> @since 2.0.0
 *		- divider			=> @since 2.0.0
 * (3) Tabs
 *		- tabs				=> @since 2.0.0
 * (4) Toggles
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
/* Basic HTML Shortcodes
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
			'style' => 'check' // check, crank, delete, doc, plus, star, star2, warning, write
	    );
	    extract( shortcode_atts( $default, $atts ) );
	    $output = '<div class="icon-list icon-'.$style.'">'.do_shortcode($content).'</div><!-- .icon-list (end) -->';
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
            'title' => ''
	    );
	    extract( shortcode_atts( $default, $atts ) );
	    $output = themeblvd_button( $content, $link, $color, $target, $size, $class, $title );
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
		$default = array(
            'style' => 'alert' // alert, approved, camera, cart, doc, download, media, note, notice, quote, warning
	    );
	    extract( shortcode_atts( $default, $atts ) );
	    $output = '<div class="info-box info-box-'.$style.'"><div class="icon">'.do_shortcode( $content ).'</div></div>';
	    return $output;
	}
}
add_shortcode( 'box', 'themeblvd_shortcode_box' );

/**
 * 48px Icon
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
	    if( ! $title ) $title = $content;
	    $output =	'<span class="icon-link">';
	    $output .=	'<a href="'.$link.'" title="'.$title.'" class="icon-link-'.$icon.'" target="'.$target.'">'.$content.'</a>';
	    $output .= 	'</span>';
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
	    return '<span class="text-highlight">'.do_shortcode( $content ).'</span><!-- .text-highlight (end) -->';
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
	    return '<span class="dropcap">'.do_shortcode( $content ).'</span><!-- .dropcap (end) -->';
	}
}
add_shortcode( 'dropcap', 'themeblvd_shortcode_dropcap' );

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

/*-----------------------------------------------------------------------------------*/
/* Tabs
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
            'style' => 'framed', // framed, open
            'height' => '' // Optional fixed height for inside of tabs
	    );
	    extract( shortcode_atts( $default, $atts ) );
	    if( isset( $atts['style'] ) ) unset( $atts['style'] );
	    if( isset( $atts['height'] ) ) unset( $atts['height'] );
	    $id = uniqid( 'tabs_'.rand() );
	    $num = count( $atts ) - 1;
		$i = 1;
		$options = array(
	    	'setup' => array(
	    		'num' => $num,
	    		'style' => $style,
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

/*-----------------------------------------------------------------------------------*/
/* Toggles
/*-----------------------------------------------------------------------------------*/

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
		$last = '';
		if ( isset( $atts[0] ) && trim( $atts[0] ) == 'last') $last = ' tb-toggle-last';
		$default = array(
	        'title' => ''
	    );
	    extract( shortcode_atts( $default, $atts ) );
	    $content = wpautop( do_shortcode( stripslashes( $content ) ) );
		$output  = '<div class="tb-toggle'.$last.'">';
		$output .= '<a href="#" title="'.$title.'" class="toggle-trigger"><span></span>'.$title.'</a>';
		$output .= '<div class="toggle-content">'.$content.'</div>';
		$output .= '</div>';
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
