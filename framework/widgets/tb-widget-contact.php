<?php
/**
 * Theme Blvd Simple Contact Widget
 * 
 * @package Theme Blvd WordPress Framework
 * @author Jason Bobich
 */

class TB_Widget_Contact extends WP_Widget {
	
	/* Constructor */
	
	function __construct() {
		$widget_ops = array(
			'classname' => 'tb-contact_widget', 
			'description' => 'Display some basic contact information.'
		);
		$control_ops = array(
			'width' => 400, 
			'height' => 350
		);
        $this->WP_Widget( 'themeblvd_contact_widget', 'Theme Blvd Simple Contact', $widget_ops, $control_ops );
	}
	
	/* Widget Options Form */
	
	function form($instance) {
		$defaults = array(
			'title' => '',
            'phone_1' => '',
            'phone_2' => '',
            'email_1' => '',
            'email_2' => '',
            'contact' => '',
            'skype' => '',
            'link_1_icon' => '',
            'link_1_url' => '',
            'link_2_icon' => '',
            'link_2_url' => '',
            'link_3_icon' => '',
            'link_3_url' => '',
            'link_4_icon' => '',
            'link_4_url' => '',
            'link_5_icon' => '',
            'link_5_url' => '',
            'link_6_icon' => '',
            'link_6_url' => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $icons = array(
			'none' 			=> 'None',
			'amazon' 		=> 'Amazon',
			'delicious' 	=> 'del.icio.us',
			'deviantart' 	=> 'Deviant Art',
			'digg' 			=> 'Digg',
			'dribbble' 		=> 'Dribbble',
			'ebay' 			=> 'Ebay',
			'email' 		=> 'Email',
			'facebook' 		=> 'Facebook',
			'feedburner' 	=> 'Feedburner',
			'flickr' 		=> 'Flickr',
			'forrst' 		=> 'Forrst',
			'foursquare' 	=> 'Foursquare',
			'github' 		=> 'Github',
			'google' 		=> 'Google+',
			'instagram' 	=> 'Instagram',
			'linkedin' 		=> 'Linkedin',
			'myspace' 		=> 'MySpace',
			'paypal' 		=> 'PayPal',
			'picasa' 		=> 'Picasa',
			'pinterest' 	=> 'Pinterest',
			'reddit' 		=> 'Reddit',
			'scribd' 		=> 'Sribd',
			'squidoo' 		=> 'Squidoo',
			'technorati' 	=> 'Technorati',
			'tumblr' 		=> 'Tumblr',
			'twitter' 		=> 'Twitter',
			'vimeo' 		=> 'Vimeo',
			'xbox' 			=> 'Xbox',
			'yahoo' 		=> 'Yahoo',
			'youtube' 		=> 'YouTube',
			'rss' 			=> 'RSS'
		);
		$icons = apply_filters( 'themeblvd_simple_contact_icons', $icons );
        ?>
		<p class="description"><?php _e( 'All fields are optional. Only fill in the items you want to show.', 'themeblvd' ); ?></p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'themeblvd'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('phone_1'); ?>"><?php _e('Phone #1:', 'themeblvd'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('phone_1'); ?>" name="<?php echo $this->get_field_name('phone_1'); ?>" type="text" value="<?php echo esc_attr($instance['phone_1']); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('phone_2'); ?>"><?php _e('Phone #2:', 'themeblvd'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('phone_2'); ?>" name="<?php echo $this->get_field_name('phone_2'); ?>" type="text" value="<?php echo esc_attr($instance['phone_2']); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('email_1'); ?>"><?php _e('Email Address #1:', 'themeblvd'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('email_1'); ?>" name="<?php echo $this->get_field_name('email_1'); ?>" type="text" value="<?php echo esc_attr($instance['email_1']); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('email_2'); ?>"><?php _e('Email Address #2:', 'themeblvd'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('email_2'); ?>" name="<?php echo $this->get_field_name('email_2'); ?>" type="text" value="<?php echo esc_attr($instance['email_2']); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('contact'); ?>"><?php _e('URL to Contact Page:', 'themeblvd'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('contact'); ?>" name="<?php echo $this->get_field_name('contact'); ?>" type="text" value="<?php echo esc_attr($instance['contact']); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('skype'); ?>"><?php _e('Skype Username:', 'themeblvd'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('skype'); ?>" name="<?php echo $this->get_field_name('skype'); ?>" type="text" value="<?php echo esc_attr($instance['skype']); ?>" />
		</p>
		
		<h4><?php _e( 'Icon Links', 'themeblvd' ); ?></h4>
		<div style="border: 1px solid #cccccc; margin: 0 0 5px 0; padding: 8px;">
			<p>
				<label for="<?php echo $this->get_field_id('link_1_icon'); ?>"><?php _e('Link #1 Icon:', 'themeblvd'); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('link_1_icon'); ?>" name="<?php echo $this->get_field_name('link_1_icon'); ?>">
					<?php foreach( $icons as $icon => $name ) : ?>
						<option value="<?php echo $icon; ?>" <?php selected( $icon, esc_attr($instance['link_1_icon']), true ); ?>'><?php echo $name; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>	
				<label for="<?php echo $this->get_field_id('link_1_url'); ?>"><?php _e('Link #1 URL:', 'themeblvd'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('link_1_url'); ?>" name="<?php echo $this->get_field_name('link_1_url'); ?>" type="text" value="<?php echo esc_attr($instance['link_1_url']); ?>" />
			</p>
		</div>
		<div style="border: 1px solid #cccccc; margin: 0 0 5px 0; padding: 8px;">
			<p>
				<label for="<?php echo $this->get_field_id('link_2_icon'); ?>"><?php _e('Link #2 Icon:', 'themeblvd'); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('link_2_icon'); ?>" name="<?php echo $this->get_field_name('link_2_icon'); ?>">
					<?php foreach( $icons as $icon => $name ) : ?>
						<option value="<?php echo $icon; ?>" <?php selected( $icon, esc_attr($instance['link_2_icon']), true ); ?>'><?php echo $name; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>	
				<label for="<?php echo $this->get_field_id('link_2_url'); ?>"><?php _e('Link #2 URL:', 'themeblvd'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('link_2_url'); ?>" name="<?php echo $this->get_field_name('link_2_url'); ?>" type="text" value="<?php echo esc_attr($instance['link_2_url']); ?>" />
			</p>
		</div>
		<div style="border: 1px solid #cccccc; margin: 0 0 5px 0; padding: 8px;">
			<p>
				<label for="<?php echo $this->get_field_id('link_3_icon'); ?>"><?php _e('Link #3 Icon:', 'themeblvd'); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('link_3_icon'); ?>" name="<?php echo $this->get_field_name('link_3_icon'); ?>">
					<?php foreach( $icons as $icon => $name ) : ?>
						<option value="<?php echo $icon; ?>" <?php selected( $icon, esc_attr($instance['link_3_icon']), true ); ?>'><?php echo $name; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>	
				<label for="<?php echo $this->get_field_id('link_3_url'); ?>"><?php _e('Link #3 URL:', 'themeblvd'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('link_3_url'); ?>" name="<?php echo $this->get_field_name('link_3_url'); ?>" type="text" value="<?php echo esc_attr($instance['link_3_url']); ?>" />
			</p>
		</div>
		<div style="border: 1px solid #cccccc; margin: 0 0 5px 0; padding: 8px;">
			<p>
				<label for="<?php echo $this->get_field_id('link_4_icon'); ?>"><?php _e('Link #4 Icon:', 'themeblvd'); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('link_4_icon'); ?>" name="<?php echo $this->get_field_name('link_4_icon'); ?>">
					<?php foreach( $icons as $icon => $name ) : ?>
						<option value="<?php echo $icon; ?>" <?php selected( $icon, esc_attr($instance['link_4_icon']), true ); ?>'><?php echo $name; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>	
				<label for="<?php echo $this->get_field_id('link_4_url'); ?>"><?php _e('Link #4 URL:', 'themeblvd'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('link_4_url'); ?>" name="<?php echo $this->get_field_name('link_4_url'); ?>" type="text" value="<?php echo esc_attr($instance['link_4_url']); ?>" />
			</p>
		</div>
		<div style="border: 1px solid #cccccc; margin: 0 0 5px 0; padding: 8px;">
			<p>
				<label for="<?php echo $this->get_field_id('link_5_icon'); ?>"><?php _e('Link #5 Icon:', 'themeblvd'); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('link_5_icon'); ?>" name="<?php echo $this->get_field_name('link_5_icon'); ?>">
					<?php foreach( $icons as $icon => $name ) : ?>
						<option value="<?php echo $icon; ?>" <?php selected( $icon, esc_attr($instance['link_5_icon']), true ); ?>'><?php echo $name; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>	
				<label for="<?php echo $this->get_field_id('link_5_url'); ?>"><?php _e('Link #5 URL:', 'themeblvd'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('link_5_url'); ?>" name="<?php echo $this->get_field_name('link_5_url'); ?>" type="text" value="<?php echo esc_attr($instance['link_5_url']); ?>" />
			</p>
		</div>
		<div style="border: 1px solid #cccccc; margin: 0 0 5px 0; padding: 8px;">
			<p>
				<label for="<?php echo $this->get_field_id('link_6_icon'); ?>"><?php _e('Link #6 Icon:', 'themeblvd'); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('link_6_icon'); ?>" name="<?php echo $this->get_field_name('link_6_icon'); ?>">
					<?php foreach( $icons as $icon => $name ) : ?>
						<option value="<?php echo $icon; ?>" <?php selected( $icon, esc_attr($instance['link_6_icon']), true ); ?>'><?php echo $name; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>	
				<label for="<?php echo $this->get_field_id('link_6_url'); ?>"><?php _e('Link #6 URL:', 'themeblvd'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('link_6_url'); ?>" name="<?php echo $this->get_field_name('link_6_url'); ?>" type="text" value="<?php echo esc_attr($instance['link_6_url']); ?>" />
			</p>
		</div>
        <?php
	}
	
	/* Update Widget Settings */
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['phone_1'] = strip_tags($new_instance['phone_1']);
        $instance['phone_2'] = strip_tags($new_instance['phone_2']);
        $instance['email_1'] = strip_tags($new_instance['email_1']);
        $instance['email_2'] = strip_tags($new_instance['email_2']);
        $instance['contact'] = strip_tags($new_instance['contact']);
        $instance['skype'] = strip_tags($new_instance['skype']);
        $instance['link_1_icon'] = strip_tags($new_instance['link_1_icon']);
        $instance['link_1_url'] = strip_tags($new_instance['link_1_url']);
        $instance['link_2_icon'] = strip_tags($new_instance['link_2_icon']);
        $instance['link_2_url'] = strip_tags($new_instance['link_2_url']);
        $instance['link_3_icon'] = strip_tags($new_instance['link_3_icon']);
        $instance['link_3_url'] = strip_tags($new_instance['link_3_url']);
        $instance['link_4_icon'] = strip_tags($new_instance['link_4_icon']);
        $instance['link_4_url'] = strip_tags($new_instance['link_4_url']);
        $instance['link_5_icon'] = strip_tags($new_instance['link_5_icon']);
        $instance['link_5_url'] = strip_tags($new_instance['link_5_url']);
        $instance['link_6_icon'] = strip_tags($new_instance['link_6_icon']);
        $instance['link_6_url'] = strip_tags($new_instance['link_6_url']);
        return $instance;
	}
	
	/* Display Widget */
	
	function widget($args, $instance) {
		extract( $args );
		echo $before_widget;
		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( $title )
			echo $before_title . $title . $after_title;
		echo themeblvd_get_simple_contact( $instance );
		echo $after_widget;		
	}

}
register_widget('TB_Widget_Contact');