<?php
/**
 * Theme Blvd Twitter Widget
 * 
 * @package Theme Blvd WordPress Framework
 * @author Jason Bobich
 */

class TB_Widget_Twitter extends WP_Widget {
	
	/* Constructor */
	
	function __construct() {
		$widget_ops = array(
			'classname' => 'tb-twitter_widget', 
			'description' => 'Show recent tweets from a Twitter account.'
		);
        $this->WP_Widget( 'themeblvd_twitter_widget', 'Theme Blvd Twitter', $widget_ops );
	}
	
	/* Widget Options Form */
	
	function form($instance) {
		$defaults = array( 
			'title' => 'Latest Tweets', 
			'count' => '3',
			'username' => '',
			'exclude_replies' => 'no',
			'time' => 'yes'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = isset($instance['title']) ? strip_tags($instance['title']): "";
		$count = isset($instance['count']) ? strip_tags($instance['count']): "";
		$username = isset($instance['username']) ? strip_tags($instance['username']): "";
		$exclude_replies = isset($instance['exclude_replies']) ? strip_tags($instance['exclude_replies']): "";
		$time = isset($instance['time']) ? strip_tags($instance['time']): "";
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', TB_GETTEXT_DOMAIN ); ?> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e( 'Enter your twitter username:', TB_GETTEXT_DOMAIN ); ?> 
			<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo esc_attr($username); ?>" /></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'How many entries do you want to display:', TB_GETTEXT_DOMAIN ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>">
				<?php 
				$list = null;
				for ( $i = 1; $i <= 15; $i++ ) {
					$selected = "";
					if($count == $i) $selected = 'selected="selected"';
					$list .= "<option $selected value='$i'>$i</option>";
				}
				echo $list;
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('exclude_replies'); ?>"><?php _e( 'Exclude @replies:', TB_GETTEXT_DOMAIN ); ?> </label>
			<select class="widefat" id="<?php echo $this->get_field_id('exclude_replies'); ?>" name="<?php echo $this->get_field_name('exclude_replies'); ?>">
				<?php 
				$list = null;
				$answers = array( 'yes', 'no' );
				foreach ( $answers as $answer ) {
					$selected = "";
					if($answer == $exclude_replies) $selected = 'selected="selected"';
					$list .= "<option $selected value='$answer'>$answer</option>";
				}
				echo $list;
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('time'); ?>"><?php _e( 'Display time of tweet', TB_GETTEXT_DOMAIN ); ?> </label>
			<select class="widefat" id="<?php echo $this->get_field_id('time'); ?>" name="<?php echo $this->get_field_name('time'); ?>">
				<?php 
				$list = null;
				$answers = array( 'yes', 'no' );
				foreach ( $answers as $answer ) {
					$selected = "";
					if($answer == $time) $selected = 'selected="selected"';
					$list .= "<option $selected value='$answer'>$answer</option>";
				}
				echo $list;
				?>
			</select>
		</p>
		<?php	
	}
	
	/* Update Widget Settings */
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['count'] = $new_instance['count'];
        $instance['username'] = strip_tags($new_instance['username']);
        $instance['exclude_replies'] = $new_instance['exclude_replies'];
        $instance['time'] = $new_instance['time'];
        return $instance;
	}
	
	/* Display Widget */
	
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);		
		// Setup args
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$count = $instance['count'];
		$username = $instance['username'];
		$exclude_replies = $instance['exclude_replies'];
		$time = $instance['time'];		
		// Widget output
		echo $before_widget;
		if ( $title ) echo $before_title.$title.$after_title;
		themeblvd_twitter( $count, $username, $time, $exclude_replies );
		echo $after_widget;	
	}

}
register_widget('TB_Widget_Twitter');