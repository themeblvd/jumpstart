<?php
/**
 * Display alert formatted correctly for Bootstrap
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for alert
 * @param string $content content for alert, optional
 * @return string $output Output for alert
 */
function themeblvd_get_alert( $args, $content = '' ) {

	$defaults = array(
        'style'         => 'default',   // Style of panel - primary, success, info, warning, danger
        'class'         => '',          // Any additional CSS classes
        'wpautop'       => 'true'       // Whether to apply wpautop on content
    );
    $args = wp_parse_args( $args, $defaults );

    // CSS classes
    $class = sprintf( 'tb-alert alert alert-%s entry-content', $args['style'] );

    if ( $args['class'] ) {
        $class .= ' '.$args['class'];
    }

    // How are we getting the content?
    if ( ! $content && ! empty( $args['content'] ) ) {
    	$content = $args['content'];
    }

    // WP auto?
    if ( $args['wpautop'] == 'true' || $args['wpautop'] == '1' ) {
        $content = themeblvd_get_content( $content );
    } else {
    	$content = do_shortcode( $content );
    }

    // Construct alert
    $output = sprintf( '<div class="%s">%s</div><!-- .panel (end) -->', $class, $content );

    return apply_filters( 'themeblvd_alert', $output, $args, $content );
}

/**
 * Display alert formatted correctly for Bootstrap
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for alert
 * @param string $content content for alert, optional
 */
function themeblvd_alert( $args, $content = '' ) {
	echo themeblvd_get_alert( $args, $content );
}

/**
 * Get divider.
 *
 * @since 2.5.0
 *
 * @param string $type Style of divider
 * @return string $output HTML output for divider
 */
function themeblvd_get_divider( $args = array() ) {

    // Setup and extract $args
    $defaults = array(
        'type'      => 'shadow',    // Style of divider - dashed, shadow, solid, double-solid, double-dashed
        'width'     => '',          // A width for the divider in pixels
        'placement' => 'equal'      // Where the divider sits between the content - equal, above (closer to content above), below (closer to content below)
    );
    $args = wp_parse_args( $args, $defaults );

    $class = sprintf( 'tb-divider %s', $args['type'] );

    if ( $args['placement'] == 'up' || $args['placement'] == 'down' ) {
        $class .= ' suck-'.$args['placement'];
    }

    $style = '';

    if ( $args['width'] ) {
        $style .= sprintf( 'max-width: %spx;', $args['width'] );
    }

    $output = sprintf( '<div class="%s" style="%s"></div>', $class, $style );

    return apply_filters( 'themeblvd_divider', $output, $args['type'] );
}

/**
 * Display divider.
 *
 * @since 2.0.0
 *
 * @param string $type Style of divider
 * @return string $output HTML output for divider
 */
function themeblvd_divider( $args = array() ) {
    echo themeblvd_get_divider( $args );
}

/**
 * Get Google Map
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for map
 * @return string $output Final content to output
 */
function themeblvd_get_map( $args ) {

    wp_enqueue_script( 'google_maps' );

    $defaults = array(
        'id'            => uniqid('map_'),  // Unique ID for map
        'markers'       => array(),         // Location markers for map
        'height'        => '400',           // CSS height of map
        'center_type'   => 'default',       // If default, will be first location - default or custom
        'center'        => array(),         // If above is custom, this will be the center point of map
        'zoom'          => '15',            // Zoom level of initial map- [1, 20]
        'lightness'     => '0',             // Map brightness - [-100, 100]
        'saturation'    => '0',             // Map color saturation - [-100, 100]
        'has_hue'       => '0',             // Whether map has overlay color
        'hue'           => '',              // Overlay color for map (i.e. hue)
        'zoom_control'  => '1',             // Whether user has zoom control
        'pan_control'   => '1',             // Whether user has pan control
        'draggable'     => '1'              // Whether user can drag map around
    );
    $args = wp_parse_args( $args, $defaults );

    $hue = '0';
    if ( $args['has_hue'] && $args['hue'] ) {
        $hue = $args['hue'];
    }

    // Start map with config options
    $output = sprintf( '<div class="tb-map" data-zoom="%s" data-lightness="%s" data-saturation="%s" data-hue="%s" data-zoom_control="%s" data-pan_control="%s" data-draggable="%s">', $args['zoom'], $args['lightness'], $args['saturation'], $hue, $args['zoom_control'], $args['pan_control'], $args['draggable'] );

    // Map gets inserted into this DIV
    $output .= sprintf( '<div id="%s" class="map-canvas" style="height: %spx;"></div>', $args['id'], $args['height'] );

    // Map center point
    $center_lat = '0';
    $center_long = '0';

    if ( $args['center_type'] == 'custom' ) {

        // Custom center point
        if ( isset( $args['center']['lat'] ) ) {
            $center_lat = $args['center']['lat'];
        }
        if ( isset( $args['center']['long'] ) ) {
            $center_long = $args['center']['long'];
        }

    } else {

        // Default: Use first marker as center point
        if ( $args['markers'] ) {
            foreach ( $args['markers'] as $marker ) {
                if ( isset( $marker['geo']['lat'] ) ) {
                    $center_lat = $marker['geo']['lat'];
                }
                if ( isset( $marker['geo']['long'] ) ) {
                    $center_long = $marker['geo']['long'];
                }
                break;
            }
        }

    }

    $output .= sprintf('<div class="map-center" data-lat="%s" data-long="%s"></div>', $center_lat, $center_long );

    // Map markers
    if ( $args['markers'] ) {

        $output .= '<div class="map-markers hide">';

        foreach ( $args['markers'] as $marker ) {

            $name = '';
            if ( ! empty( $marker['name'] ) ) {
                $name = $marker['name'];
            }

            $lat = '0';
            if ( ! empty( $marker['geo']['lat'] ) ) {
                $lat = $marker['geo']['lat'];
            }

            $long = '0';
            if ( ! empty( $marker['geo']['long'] ) ) {
                $long = $marker['geo']['long'];
            }

            $info = '';
            if ( ! empty( $marker['info'] ) ) {
                $info = $marker['info'];
            }

            $image = '';
            if ( ! empty( $marker['image']['src'] ) ) {
                $image = $marker['image']['src'];
            }

            $output .= sprintf('<div class="map-marker" data-name="%s" data-lat="%s" data-long="%s" data-image="%s">', $name, $lat, $long, $image );
            $output .= sprintf( '<div class="map-marker-info">%s</div>', themeblvd_get_content($info) );
            $output .= '</div><!-- .map-marker (end) -->';
        }

        $output .= '</div><!-- .map-markers (end) -->';
    }

    $output .= '</div><!-- .tb-map (end) -->';

    return apply_filters( 'themeblvd_map', $output, $args );
}

/**
 * Display Google map
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for Google Map.
 */
function themeblvd_map( $args ) {
    echo themeblvd_get_map( $args );
}

/**
 * Get icon box.
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for alert
 * @return string $output Output for alert
 */
function themeblvd_get_icon_box( $args ) {

    $defaults = array(
        'icon'          => '',          // FontAwesome icon ID
        'size'          => '20px',      // Font size of font icon
        'location'      => 'above',     // Location of icon
        'color'         => '#666666',   // Color of the icon
        'circle'        => '0',         // Whether to circle the icon
        'circle_color'  => '#cccccc',   // BG color of the circle
        'title'         => '',          // Title of the block
        'text'          => ''           // Content of the block
    );
    $args = wp_parse_args( $args, $defaults );

    // Class for icon box
    $class = sprintf( 'tb-icon-box icon-%s', $args['location'] );

    if ( $args['circle'] ) {
        $class .= ' icon-circled';
    }

    // Icon
    $icon_style = sprintf( 'color: %s; font-size: %s;', $args['color'], $args['size'] );

    if ( $args['circle'] ) {
        $icon_style .= sprintf( ' background-color: %s;', $args['circle_color'] );
    }

    $icon = sprintf( '<div class="icon" style="%s"><i class="fa fa-%s" style="width:%s;"></i></div>', $icon_style, $args['icon'], $args['size'] );

    // Content style
    $content_style = '';

    if ( $args['location'] == 'side' ) {

        $padding = intval( str_replace( 'px', '', $args['size'] ) );

        if ( $args['circle'] ) {
            $padding = $padding + 30; // Account for 15px of padding both sides of circled icon
        }

        $padding = $padding + 10;

        if ( is_rtl() ) {
            $content_style = sprintf( 'padding-right: %spx;', $padding );
        } else {
            $content_style = sprintf( 'padding-left: %spx;', $padding );
        }
    }

    // Final output
    $output  = '<div class="'.$class.'">';

    $output .= $icon;

    if ( $args['title'] || $args['text'] ) {
        $output .= '<div class="entry-content" style="'.$content_style.'">';
        $output .= '<h3>'.$args['title'].'</h3>';
        $output .= themeblvd_get_content( $args['text'] );
        $output .= '</div><!-- .content (end) -->';
    }

    $output .= '</div><!-- .tb-icon-box (end) -->';

    return apply_filters( 'themeblvd_icon_box', $output, $args, $icon );
}

/**
 * Display icon box.
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for icon box
 */
function themeblvd_icon_box( $args ) {
    echo themeblvd_get_icon_box( $args );
}

/**
 * Get Bootstrap Jumbotron
 *
 * @since 2.4.2
 *
 * @param array $args Arguments for jumbotron
 * @param string $args Content within jumbotron
 * @return string $output Final content to output
 */
function themeblvd_get_jumbotron( $args, $content ) {

    $output = '';

    $defaults = array(
        'title'         => '',      // Title of unit
        'bg_color'      => '',      // Background color - Ex: #000000 (no option in Builder)
        'bg_opacity'    => '1',     // BG color opacity for rgba()
        'text_color'    => '',      // Text color - Ex: #000000
        'text_align'    => 'left',  // How to align text - left, right, center
        'align'         => '',      // How to align jumbotron - left, right, center, blank for no alignment
        'max_width'     => '',      // Meant to be used with align left/right/center - 300px, 50%, etc
        'class'         => '',      // Any additional CSS classes
        'wpautop'       => true     // Whether to apply wpautop on content
    );
    $args = wp_parse_args( $args, $defaults );

    // WP auto?
    if ( $args['wpautop'] ) {
        $content = wpautop( $content );
    }

    // CSS classes
    $class = sprintf( 'tb-jumbotron jumbotron entry-content text-%s', $args['text_align'] );

    // Setup inline styles
    $style = '';

    if ( $args['bg_color'] ) {
        $style .= sprintf( 'background-color:%s;', $args['bg_color'] ); // Fallback for older browsers
        $style .= sprintf( 'background-color:%s;', themeblvd_get_rgb( $args['bg_color'], $args['bg_opacity'] ) );
        $class .= ' has-bg';
    }

    if ( $args['text_color'] ) {
        $style .= sprintf( 'color:%s;', $args['text_color'] );
    }

    // Custom CSS classes
    if ( $args['class'] ) {
        $class .= ' '.$args['class'];
    }

    // Construct initial jumbotron
    if ( $args['title'] ) {
        $title = sprintf( '<h2>%s</h2>', $args['title'] );
        $content = $title.$content;
    }

    $jumbotron = sprintf('<div class="%s" style="%s">%s</div>', $class, $style, do_shortcode( $content ) );

    // Wrap the unit
    $wrap_class = 'jumbotron-wrap';

    // Align jumbotron right or left?
    if ( $args['align'] == 'left' ) {
        $wrap_class .= ' pull-left';
    } else if ( $args['align'] == 'right' ) {
        $wrap_class .= ' pull-right';
    }

    // Inline styles
    $style = '';

    // Align jumbotron center?
    if ( $args['align'] == 'center' ) {
        $style .= 'margin-left: auto; margin-right: auto; ';
    }

    // Max width?
    if ( $args['max_width'] ) {
        $style .= sprintf( 'max-width: %s;', $args['max_width'] );
    }

    // Final output
    $output = sprintf( '<div class="%s" style="%s">%s</div>', $wrap_class, $style, $jumbotron );

    return apply_filters( 'themeblvd_jumbotron', $output, $args, $content, $jumbotron );
}

/**
 * Display Bootstrap Jumbotron
 *
 * @since 2.4.2
 *
 * @param array $args Arguments for Jumbotron.
 */
function themeblvd_jumbotron( $args ) {

    $defaults = array(
        // Rest of $args are verified in themeblvd_get_jumbotron() ...
        'buttons'       => array(), // Any buttons to include
        'buttons_stack' => '0',     // Whether buttons appear stacked
        'buttons_block' => '0'      // Whether buttons are displayed as block-level
    );
    $args = wp_parse_args( $args, $defaults );

    // Setup content
    $content = '';

    if ( ! empty( $args['content'] ) ) {
        $content = $args['content'];
    }

    if ( $args['buttons'] ) {

        if ( $args['buttons_block'] ) {
            foreach ( $args['buttons'] as $key => $value ) {
                $args['buttons'][$key]['block'] = true;
            }
        }

        $content .= "\n\n".themeblvd_get_buttons( $args['buttons'], array( 'stack' => $args['buttons_stack'] ) );

    }

    echo themeblvd_get_jumbotron( $args, $content );

}

/**
 * Get Partner Logos
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for partners unit
 * @return string $output Final content to output
 */
function themeblvd_get_logos( $args ) {

    $defaults = array(
        'logos'     => array(),     // Logos to display
        'title'     => '',          // Title for unit
        'display'   => 'slider',    // How to display logos, grid or slider
        'slide'     => '4',         // If slider, number of logos per slide
        'nav'       => '1',         // If slider, whether to display nav
        'timeout'   => '3',         // If slider, seconds in between auto rotation
        'grid'      => '4',         // If grid, number of logos per row
        'height'    => '100',       // Height across all logo blocks
        'boxed'     => '1',         // Whether to dispay box arond logo
        'greyscale' => '1'          // Whether to display logos as black and white
    );
    $args = wp_parse_args( $args, $defaults );

    $class = 'tb-logos '.$args['display'];

    if ( $args['boxed'] ) {
        $class .= ' has-boxed';
    }

    if ( $args['title'] ) {
        $class .= ' has-title';
    }

    if ( $args['display'] == 'slider' ) {

        $class .= ' tb-block-slider flexslider';

        if ( $args['nav'] ) {
            $class .= ' has-nav';
        }
    }

    $output = sprintf( '<div class="%s" data-timeout="%s" data-nav="%s">', $class, $args['timeout'], $args['nav'] );

    if ( $args['title'] ) {
        $output .= sprintf( '<h3 class="title">%s</h3>', $args['title'] );
    }

    $output .= '<div class="tb-logos-inner tb-block-slider-inner">';

    $output .= themeblvd_get_loader();

    if ( $args['display'] == 'slider' && $args['nav'] ) {
        $output .= themeblvd_get_slider_controls();
    }

    if ( $args['logos'] ) {

        $img_class = '';

        if ( $args['greyscale'] ) {
            $img_class .= 'greyscale';
        }

        $wrap_class = 'tb-logo';

        if ( $args['boxed'] ) {
            $wrap_class .= ' boxed';
        }

        $wrap_style = '';

        if ( $args['height'] ) {
            $wrap_style .= sprintf( 'height: %spx;', $args['height'] );
        }

        if ( $args['display'] == 'slider' ) {
            $num_per = intval($args['slide']);
        } else {
            $num_per = intval($args['grid']);
        }

        $grid_class = themeblvd_grid_class($num_per);

        if ( $args['display'] == 'slider' ) {
            $output .= '<ul class="slides">';
            $output .= '<li class="slide">';
        }

        $output .= themeblvd_get_open_row();

        $total = count($args['logos']);
        $i = 1;

        foreach ( $args['logos'] as $logo ) {

            $img = sprintf( '<img src="%s" alt="%s" class="%s" />', $logo['src'], $logo['alt'], $img_class );

            if ( $logo['link'] ) {
                $img = sprintf( '<a href="%s" title="%s" class="%s" style="%s" target="_blank">%s</a>', $logo['link'], $logo['name'], $wrap_class, $wrap_style, $img );
            } else {
                $img = sprintf( '<div class="%s" style="%s">%s</div>', $wrap_class, $wrap_style, $img );
            }

            $output .= sprintf( '<div class="col %s">%s</div>', $grid_class, $img );

            if ( $i % $num_per == 0 && $i < $total ) {

                $output .= themeblvd_get_close_row();

                if ( $args['display'] == 'slider' ) {
                    $output .= '</li>';
                    $output .= '<li class="slide">';
                }

                $output .= themeblvd_get_open_row();

            }

            $i++;

        }

        $output .= themeblvd_get_close_row();

        if ( $args['display'] == 'slider' ) {
            $output .= '</li>';
            $output .= '</ul>';
        }
    }

    $output .= '</div><!-- .tb-logos-inner (end) -->';
    $output .= '</div><!-- .tb-logos (end) -->';

    return apply_filters( 'themeblvd_logos', $output, $args );
}

/**
 * Display partner logos
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for Google Map.
 */
function themeblvd_logos( $args ) {
    echo themeblvd_get_logos( $args );
}

/**
 * Get panel formatted correctly for Bootstrap
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for panel
 * @param string $content content for panel, optional
 * @return string $output Output for panel
 */
function themeblvd_get_panel( $args, $content = '' ) {

    $defaults = array(
        'style'         => 'default',   // Style of panel - primary, success, info, warning, danger
        'title'         => '',          // Header for panel
        'footer'        => '',          // Footer for panel
        'text_align'    => 'left',      // How to align text - left, right, center
        'align'         => '',          // How to align panel - left, right
        'max_width'     => '',          // Meant to be used with align left/right - 300px, 50%, etc
        'class'         => '',          // Any additional CSS classes
        'wpautop'       => 'true'       // Whether to apply wpautop on content
    );
    $args = wp_parse_args( $args, $defaults );

    // CSS classes
    $class = sprintf( 'tb-panel panel panel-%s text-%s', $args['style'], $args['text_align'] );

    if ( $args['class'] ) {
        $class .= ' '.$args['class'];
    }

    // How are we getting the content?
    if ( ! $content && ! empty( $args['content'] ) ) {
        $content = $args['content'];
    }

    // WP auto?
    if ( $args['wpautop'] == 'true' || $args['wpautop'] == '1' ) {
        $content = themeblvd_get_content( $content );
    } else {
        $content = do_shortcode( $content );
    }

    // Construct intial panel
    $output = sprintf( '<div class="%s">', $class );

    if ( $args['title'] ) {
        $output .= sprintf( '<div class="panel-heading"><h3 class="panel-title">%s</h3></div>', $args['title'] );
    }

    $output .= sprintf( '<div class="panel-body entry-content">%s</div>', $content );

    if ( $args['footer'] ) {
        $output .= sprintf( '<div class="panel-footer">%s</div>', $args['footer'] );
    }

    $output .= '</div><!-- .panel (end) -->';

    return apply_filters( 'themeblvd_panel', $output, $args );
}

/**
 * Display panel formatted correctly for Bootstrap
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for panel
 * @param string $content content for panel, optional
 */
function themeblvd_panel( $args, $content = '' ) {
    echo themeblvd_get_panel( $args, $content );
}

/**
 * Get slogan (i.e. Promo Box)
 *
 * @since 2.5.0
 *
 * @param array $args All options for slogan
 * @return string $output HTML output for slogan
 */
function themeblvd_get_slogan( $args ) {

    // Setup and extract $args
    $defaults = array(
        'slogan'                => '',                      // Text for slogan (used by element)
        'content'               => '',                      // Text for slogan (used by block)
        'wpautop'               => 1,                       // Whether to apply wpautop on content
        'text_size'             => 'large',                 // Size of text - small, normal, medium, large
        'style'                 => 'none',                  // Custom styling class
        'bg_color'              => '',                      // Background color - Ex: #000000
        'bg_opacity'            => '1',                     // BG color opacity for rgba()
        'text_color'            => '',                      // Text color - Ex: #000000
        'button'                => 1,                       // Show button - true, false
        'button_color'          => 'default',               // Color of button - Use themeblvd_colors() to generate list
        'button_custom'         => array(
            'bg'                => '#ffffff',
            'bg_hover'          => '#ebebeb',
            'border'            => '#cccccc',
            'text'              => '#333333',
            'text_hover'        => '#333333',
            'include_bg'        => 1,
            'include_border'    => 1
        ),
        'button_text'           => 'Get Started Today!',    // Text for button
        'button_size'           => 'large',                 // Size of button - mini, small, default, large
        'button_url'            => '',                      // URL button goes to
        'button_target'         => '_self',                 // Button target - _self, _blank
        'button_icon_before'    => '',                      // FontAwesome Icon before button text
        'button_icon_after'     => ''                       // FontAwesome Icon afters button text
    );
    $args = wp_parse_args( $args, $defaults );

    // CSS classes
    $class = sprintf( 'tb-slogan clearfix text-%s', $args['text_size'] );

    if ( $args['button'] ) {
        $class .= ' has-button';
    } else {
        $class .= ' text-only';
    }

    // Inline styles
    $style = '';

    if ( $args['style'] == 'custom' ) {

        if ( $args['bg_color'] ) {
            $style .= sprintf( 'background-color:%s;', $args['bg_color'] ); // Fallback for older browsers
            $style .= sprintf( 'background-color:%s;', themeblvd_get_rgb( $args['bg_color'], $args['bg_opacity'] ) );
            $class .= ' has-bg';
        }

        if ( $args['text_color'] ) {
            $style .= sprintf( 'color:%s;', $args['text_color'] );
        }

    }

    // Custom CSS classes
    if ( $args['style'] && $args['style'] != 'custom' && $args['style'] != 'none'  ) {
        $class .= ' '.$args['style'];
    }

    // Output
    $output = sprintf( '<div class="%s" style="%s">', $class, $style );

    // Button
    if ( $args['button'] ) {

        // Custom button styling
        $addon = '';

        if ( $args['button_color'] == 'custom' ) {

            if ( $args['button_custom']['include_bg'] ) {
                $bg = $args['button_custom']['bg'];
            } else {
                $bg = 'transparent';
            }

            if ( $args['button_custom']['include_border'] ) {
                $border = $args['button_custom']['border'];
            } else {
                $border = 'transparent';
            }

            $addon = sprintf( 'style="background-color: %1$s; border-color: %2$s; color: %3$s;" data-bg="%1$s" data-bg-hover="%4$s" data-text="%3$s" data-text-hover="%5$s"', $bg, $border, $args['button_custom']['text'], $args['button_custom']['bg_hover'], $args['button_custom']['text_hover'] );

        }

        $output .= themeblvd_button( stripslashes($args['button_text']), $args['button_url'], $args['button_color'], $args['button_target'], $args['button_size'], null, null, $args['button_icon_before'], $args['button_icon_after'], $addon );
    }

    // Content
    $content = stripslashes($args['slogan']);

    if ( ! $content ) {
        $content = stripslashes($args['content']);
    }

    // WP auto?
    if ( $args['wpautop'] ) {
        $content = wpautop( $content );
    }

    $output .= sprintf( '<span class="slogan-text entry-content">%s</span>', do_shortcode($content) );
    $output .= '</div><!-- .slogan (end) -->';

    return apply_filters( 'themeblvd_slogan', $output, $args );
}

/**
 * Get slogan (i.e. Promo Box)
 *
 * @since 2.5.0
 *
 * @param array $args All options for slogan
 * @return string $output HTML output for slogan
 */
function themeblvd_slogan( $args ) {
    echo themeblvd_get_slogan( $args );
}

/**
 * Get set of tabs.
 *
 * @since 2.0.0
 *
 * @param array $id unique ID for tab set
 * @param array $options all options for tabs
 * @return string $output HTML output for tabs
 */
function themeblvd_get_tabs( $id, $args ) {

    $defaults = array(
        'tabs'      => array(),     // Tabs from sortable option type
        'nav'       => 'tabs',      // Style of nav, tabs or pills
        'style'     => 'framed',    // Style of unit, framed or open - Theme can filter on style choice with "themeblvd_tab_styles"
        'height'    => '0'          // Whether forced shared height of all columns
    );
    $args = wp_parse_args( $args, $defaults );

    $navigation = '';
    $content = '';
    $output = '';

    // Tabs or pills?
    $nav_type = $args['nav'];

    if ( $nav_type != 'tabs' && $nav_type != 'pills' ) {
        $nav_type = 'tabs';
    }

    // Container classes
    $classes = 'tb-tabs tabbable';

    if ( ! empty( $args['height'] ) ) {
        $classes .= ' fixed-height';
    }

    $classes .= ' tb-tabs-'.$args['style'];

    if ( $nav_type == 'pills' ) {
        $classes .= ' tb-tabs-pills';
    }

    // Allow deep linking directly to individual tabs?
    $deep = apply_filters( 'themeblvd_tabs_deep_linking', false );

    // Navigation
    $i = 1;
    $navigation .= '<ul class="nav nav-'.$nav_type.'">';
    if ( $args['tabs'] && is_array($args['tabs']) ) {
        foreach ( $args['tabs'] as $tab_id => $tab ) {

            $class = '';

            if ( $i == 1 ) {
                $class = 'active';
            }

            $name = $tab['title'];

            if ( $deep ) {
                $tab_id = str_replace( ' ', '_', $name );
                $tab_id = preg_replace('/\W/', '', strtolower($tab_id) );
            } else {
                $tab_id = $id.'-'.$tab_id;
            }

            $navigation .= sprintf('<li class="%s"><a href="#%s" data-toggle="%s" title="%s">%s</a></li>', $class, $tab_id, str_replace('s', '', $nav_type), stripslashes($name), stripslashes($name) );

            $i++;
        }
    }
    $navigation .= '</ul>';

    // Tab content
    $i = 1;
    $content_class = 'tab-content';

    if ( $args['style'] == 'framed' ) {
        $content_class .= ' '.apply_filters( 'themeblvd_toggle_body_text', 'dark' );
    }

    $content = sprintf( '<div class="%s">', $content_class );
    $content .= "\n";

    if ( $args['tabs'] && is_array($args['tabs']) ) {
        foreach ( $args['tabs'] as $tab_id => $tab ) {

            $class = 'tab-pane entry-content fade in clearfix';
            if ( $i == 1 ) {
                $class .= ' active';
            }

            if ( $deep ) {
                $tab_id = str_replace( ' ', '_', $name );
                $tab_id = preg_replace('/\W/', '', strtolower($tab_id) );
            } else {
                $tab_id = $id.'-'.$tab_id;
            }

            $content .= sprintf( '<div id="%s" class="%s">', $tab_id, $class );

            switch ( $tab['content']['type'] ) {

                case 'widget' :
                    $content .= themeblvd_get_widgets( $tab['content']['sidebar'], 'tabs' );
                    break;

                case 'page' :
                    $content .= themeblvd_get_post_content( $tab['content']['page'], 'page' );
                    break;

                case 'raw' :
                    if ( ! empty( $tab['raw_format'] ) ) {
                        $content .= themeblvd_get_content( $tab['content']['raw'] );
                    } else {
                        $content .= do_shortcode( $tab['content']['raw'] );
                    }
                    break;

            }

            $content .= '</div><!-- .tab-pane (end) -->';
            $i++;
        }
    }
    $content .= '</div><!-- .tab-content (end) -->';

    // Construct final output
    $output  = '<div class="'.$classes.'">';
    $output .= $navigation;
    $output .= $content;
    $output .= '</div><!-- .tabbable (end) -->';

    return apply_filters('themeblvd_tabs', $output);
}

/**
 * Display set of tabs.
 *
 * @since 2.0.0
 *
 * @param array $id unique ID for tab set
 * @param array $args all options for tabs
 * @return string $output HTML output for tabs
 */
function themeblvd_tabs( $id, $args ) {
    echo themeblvd_get_tabs( $id, $args );
}

/**
 * Get team member.
 *
 * @since 2.5.0
 *
 * @param string $args Options for content block
 * @return string $output Content output
 */
function themeblvd_get_team_member( $args ){

    $defaults = array(
        'image'         => array(), // Image of person
        'name'          => '',      // Name of person
        'tagline'       => '',      // Tagline for person, Ex: Founder and CEO
        'icons'         => array(), // Social icons for themeblvd_contact_bar()
        'icons_style'   => 'grey',  // Style of social icons - grey, light, dark, or color
        'text'          => ''       // Description for person
    );
    $args = wp_parse_args( $args, $defaults );

    $output = '<div class="tb-team-member">';

    if ( ! empty( $args['image']['src'] ) ) {
        $output .= sprintf( '<div class="member-image"><img src="%s" alt="%s" /></div>', $args['image']['src'], $args['image']['title'] );
    }

    $output .= '<div class="member-info clearfix">';

    $output .= '<div class="member-identity">';

    if ( $args['name'] ) {
        $output .= sprintf( '<span class="member-name">%s</span>', $args['name'] );
    }

    if ( $args['tagline'] ) {
        $output .= sprintf( '<span class="member-tagline">%s</span>', $args['tagline'] );
    }

    $output .= '</div><!-- .member-identity (end) -->';

    if ( $args['icons'] ) {
        $icon_args = array(
            'style' => $args['icons_style'],
            'class' => 'member-contact'
        );
        $output .= themeblvd_get_contact_bar( $args['icons'], $icon_args );
    }

    $output .= '</div><!-- .member-info (end) -->';

    if ( $args['text'] ) {
        $output .= sprintf('<div class="member-text entry-content">%s</div><!-- .member-text (end) -->', themeblvd_get_content($args['text']) );
    }

    $output .= '</div><!-- .tb-team-member (end) -->';

    return apply_filters( 'themeblvd_team_member', $output, $args );
}

/**
 * Display team member.
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for panel
 */
function themeblvd_team_member( $args ) {
    echo themeblvd_get_team_member( $args );
}

/**
 * Get testimonial.
 *
 * @since 2.5.0
 *
 * @param string $args Options for content block
 * @return string $output Content output
 */
function themeblvd_get_testimonial( $args ){

    $defaults = array(
        'text'          => '',          // Text for testimonial
        'name'          => '',          // Name of person giving testimonial
        'tagline'       => '',          // Tagline or position of person giving testimonial
        'company'       => '',          // Company of person giving testimonial
        'company_url'   => '',          // Company URL of person giving testimonial
        'image'         => array(),     // Image of person giving testimonial,
        'display'       => 'standard'   // How to display the testimonial, showcase or standard
    );
    $args = wp_parse_args( $args, $defaults );

    $class = sprintf( 'tb-testimonial %s', $args['display'] );

    if ( ! empty( $args['image']['src'] ) ) {
        $class .= ' has-image';
    }

    if ( $args['name'] && ( $args['tagline'] || $args['company'] ) ) {
        $class .= ' tag-two-line';
    } else if ( $args['name'] || $args['tagline'] || $args['company'] ) {
        $class .= ' tag-one-line';
    }

    $output = '<div class="'.$class.'">';

    $output .= sprintf( '<div class="testimonial-text entry-content"><span class="arrow"></span>%s</div>', themeblvd_get_content($args['text']) );

    if ( $args['name'] ) {

        if ( $args['display'] == 'showcase' ) {
            $output .= themeblvd_get_divider();
        }

        $output .= '<div class="author">';

        if ( ! empty( $args['image']['src'] ) ) {
            $output .= sprintf( '<span class="author-image"><img src="%s" alt="%s" /></span>', $args['image']['src'], $args['image']['title'] );
        }

        $output .= sprintf( '<span class="author-name">%s</span>', $args['name'] );

        if ( $args['tagline'] || $args['company'] ) {

            $tagline = '';

            if ( $args['tagline'] ) {
                $tagline .= $args['tagline'];
            }

            if ( $args['company'] ) {

                $company = $args['company'];

                if ( $args['company_url'] ) {
                    $company = sprintf( '<a href="%1$s" title="%2$s" target="_blank">%2$s</a>', $args['company_url'], $company );
                }

                if ( $tagline ) {
                    $tagline .= ', '.$company;
                } else {
                    $tagline .= $company;
                }

            }

            $output .= sprintf( '<span class="author-tagline">%s</span>', $tagline );

        }

        $output .= '</div><!-- .author (end) -->';
    }

    $output .= '</div><!-- .tb-testimonial (end) -->';

    return apply_filters( 'themeblvd_testiomnial', $output, $args );
}


/**
 * Display testimonial.
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for panel
 */
function themeblvd_testimonial( $args ) {
    echo themeblvd_get_testimonial( $args );
}

/**
 * Get testimonial slider.
 *
 * @since 2.5.0
 *
 * @param string $args Options for content block
 * @return string $output Content output
 */
function themeblvd_get_testimonial_slider( $args ) {

    $defaults = array(
        'testimonials'  => array(),     // The testimonials, each formatted for themeblvd_get_testimonial
        'title'         => '',          // Title for unit
        'fx'            => '',          // Slide transition effect (slide or fade)
        'timeout'       => '3',         // Secods in between transitions, can be 0 for no auto rotation
        'nav'           => '1',         // Whether to show slider navigation
        'display'       => 'standard'   // How to display the slider, showcase or standard
    );
    $args = wp_parse_args( $args, $defaults );

    if ( ! $args['fx'] ) {
        if ( $args['display'] == 'showcase' ) {
            $args['fx'] = 'slide';
        } else {
            $args['fx'] = 'fade';
        }
    }

    $class = sprintf( 'tb-testimonial-slider tb-block-slider %s flexslider', $args['display'] );

    if ( $args['nav'] ) {
        $class .= ' has-nav';
    }

    if ( $args['title'] && $args['display'] == 'standard' ) {
        $class .= ' has-title';
    }

    $output = sprintf('<div class="%s" data-timeout="%s" data-nav="%s" data-fx="%s">', $class, $args['timeout'], $args['nav'], $args['fx'] );

    if ( $args['title'] && $args['display'] == 'standard' ) {
        $output .= sprintf( '<h3 class="title">%s</h3>', $args['title'] );
    }

    $output .= '<div class="tb-testimonial-slider tb-block-slider-inner">';

    $output .= themeblvd_get_loader();

    if ( $args['nav'] ) {
        $output .= themeblvd_get_slider_controls();
    }

    if ( $args['testimonials'] ) {

        $output .= '<ul class="slides">';

        foreach ( $args['testimonials'] as $testimonial ) {

            $testimonial['display'] = $args['display'];

            $output .= sprintf( '<li class="slide">%s</li>', themeblvd_get_testimonial($testimonial) );

        }

        $output .= '</ul>';

    }

    $output .= '</div><!-- .tb-testimonial-slider-inner (end) -->';
    $output .= '</div><!-- .tb-testimonial-slider (end) -->';

    return apply_filters( 'themeblvd_testiomnial_slider', $output, $args );
}

/**
 * Display testimonial slider.
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for panel
 */
function themeblvd_testimonial_slider( $args ) {
    echo themeblvd_get_testimonial_slider( $args );
}

/**
 * Get toggle formatted correctly for Bootstrap,
 * using the panel.
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for alert
 * @param string $content content for alert, optional
 * @return string $output Output for alert
 */
function themeblvd_get_toggle( $args ) {

    $defaults = array(
        'title'         => '',      // Title of toggle
        'content'       => '',      // Hidden content of toggle
        'wpautop'       => 'true',  // Whether to apply wpautop on content
        'open'          => 'false', // Whether toggle is initially open
        'class'         => '',      // Any additional CSS classes
        'last'          => false    // Whether this is the last toggle of a group; this only applies if it's not part of an accordion
    );
    $args = wp_parse_args( $args, $defaults );

    // Bootstrap color
    $color = apply_filters( 'themeblvd_toggle_color', 'default' );

    // CSS classes
    $class = sprintf( 'tb-toggle panel panel-%s', $color );

    if ( $args['class'] ) {
        $class .= ' '.$args['class'];
    }

    if ( $args['last'] ) {
        $class .= '  panel-last';
    }

    // WP auto?
    if ( $args['wpautop'] == 'true' || $args['wpautop'] == '1' ) {
        $content = themeblvd_get_content( $args['content'] );
    } else {
        $content = do_shortcode( $args['content'] );
    }

    // Is toggle open?
    $state = 'panel-collapse collapse';
    $icon = 'plus-circle';
    if( $args['open'] == 'true' || $args['open'] == '1' ) {
        $state .= ' in';
        $icon = 'minus-circle';
    }

    // Individual toggle ID (NOT the Accordion ID)
    $toggle_id = uniqid( 'toggle_'.rand() );

    // Bootstrap 3 output
    $output = '
        <div class="'.$class.'">
            <div class="panel-heading">
                <a class="panel-title" data-toggle="collapse" data-parent="" href="#'.$toggle_id.'">
                    <i class="fa fa-'.$icon.' switch-me"></i> '.$args['title'].'
                </a>
            </div><!-- .panel-heading (end) -->
            <div id="'.$toggle_id.'" class="'.$state.'">
                <div class="panel-body entry-content">
                    '.$content.'
                </div><!-- .panel-body (end) -->
            </div><!-- .panel-collapse (end) -->
        </div><!-- .panel (end) -->';

    return apply_filters( 'themeblvd_toggle', $output, $args );
}

/**
 * Display toggle formatted correctly for Bootstrap,
 * using the panel.
 *
 * @since 2.5.0
 *
 * @param array $args Arguments for panel
 * @param string $content content for panel, optional
 */
function themeblvd_toggle( $args, $content = '' ) {
    echo themeblvd_get_toggle( $args, $content );
}

/**
 * Get set of toggles.
 *
 * @since 2.5.0
 *
 * @param array $id unique ID for toggle set
 * @param array $options all options for toggles
 * @return string $output HTML output for toggles
 */
function themeblvd_get_toggles( $id, $options ) {

    $accordion = false;

    if ( isset($options['accordion']) ) {
        if( $options['accordion'] == 'true' || $options['accordion'] == '1' || $options['accordion'] === 1 ) {
            $accordion = true;
        }
    }

    $counter = 1;
    $total = count($options['toggles']);
    $output = '';

    if ( $options['toggles'] && is_array($options['toggles']) ) {
        foreach ( $options['toggles'] as $toggle ) {

            if ( ! $accordion && $counter == $total ) {
                $toggle['last'] = true;
            }

            $output .= themeblvd_get_toggle( $toggle );

            $counter++;
        }
    }

    if ( $accordion ) {
        $output = sprintf( '<div id="%s" class="tb-accordion panel-group">%s</div>', $id, $output );
    }

    return apply_filters('themeblvd_toggles', $output);
}

/**
 * Display set of toggles.
 *
 * @since 2.5.0
 *
 * @param array $id unique ID for toggle set
 * @param array $options all options for toggles
 */
function themeblvd_toggles( $id, $options ) {
    echo themeblvd_get_toggles( $id, $options );
}