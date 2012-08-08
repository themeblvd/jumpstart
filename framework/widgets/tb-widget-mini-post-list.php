<?php
/**
 * Theme Blvd Mini Post List Widget
 * 
 * @package Theme Blvd WordPress Framework
 * @author Jason Bobich
 */

class TB_Widget_Mini_Post_List extends WP_Widget {
	
	/* Constructor */
	
	function __construct() {
		$widget_ops = array(
			'classname' => 'tb-mini_post_list_widget', 
			'description' => 'Show list of posts.'
		);
		$control_ops = array(
			'width' => 400, 
			'height' => 350
		);
        $this->WP_Widget( 'themeblvd_mini_post_list_widget', 'Theme Blvd Mini Post List', $widget_ops, $control_ops );
	}
	
	/* Widget Options Form */
	
	function form($instance) {
		$defaults = array( 
			'title' => 'Recent Posts', 
			'thumb'	=> 'smaller',
			'meta' => 'show',
			'category' => '',
			'numberposts' => 4,
			'query' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = isset($instance['title']) ? strip_tags($instance['title']): "";
		$thumb = isset($instance['thumb']) ? strip_tags($instance['thumb']): "";
		$meta = isset($instance['meta']) ? strip_tags($instance['meta']): "";
		$category = isset($instance['category']) ? strip_tags($instance['category']): "";
		$numberposts = isset($instance['numberposts']) ? strip_tags($instance['numberposts']): "";
		$query = isset($instance['query']) ? strip_tags($instance['query']): "";
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'themeblvd' ); ?> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('thumb'); ?>"><?php _e( 'Thumbnail Sizes:', 'themeblvd' ); ?> </label>
			<select class="widefat" id="<?php echo $this->get_field_id('thumb'); ?>" name="<?php echo $this->get_field_name('thumb'); ?>">
				<?php 
				$list = null;
				$answers = array( 'small', 'smaller', 'smallest', 'hide' );
				foreach ( $answers as $answer ) {
					$selected = "";
					if($answer == $thumb) $selected = 'selected="selected"';
					$list .= "<option $selected value='$answer'>$answer</option>";
				}
				echo $list;
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('meta'); ?>"><?php _e( 'Post Dates:', 'themeblvd' ); ?> </label>
			<select class="widefat" id="<?php echo $this->get_field_id('meta'); ?>" name="<?php echo $this->get_field_name('meta'); ?>">
				<?php 
				$list = null;
				$answers = array( 'show', 'hide' );
				foreach ( $answers as $answer ) {
					$selected = "";
					if($answer == $meta) $selected = 'selected="selected"';
					$list .= "<option $selected value='$answer'>$answer</option>";
				}
				echo $list;
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e( 'Category:', 'themeblvd' ); ?> </label>
			<select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
				<?php 
				$list = null;
				$answers = array( 'all' => __( 'All Categories', 'themeblvd' ) );
				$categories = get_categories();
				foreach( $categories as $current_category ) {
					$answers[$current_category->slug] = $current_category->name;
				}
				foreach ( $answers as $key => $value ) {
					$selected = "";
					if($key == $category) $selected = 'selected="selected"';
					$list .= "<option $selected value='$key'>$value</option>";
				}
				echo $list;
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numberposts'); ?>"><?php _e( 'Number of Posts:', 'themeblvd' ); ?> 
			<input class="widefat" id="<?php echo $this->get_field_id('numberposts'); ?>" name="<?php echo $this->get_field_name('numberposts'); ?>" type="text" value="<?php echo esc_attr($numberposts); ?>" /></label>
		</p>		
		<div style="border: 1px solid #cccccc; margin: 0 0 5px 0; padding: 8px;">
			<p>
				<label for="<?php echo $this->get_field_id('query'); ?>"><strong><?php _e( 'Custom query string (optional)', 'themeblvd' ); ?></strong>
				<input class="widefat" id="<?php echo $this->get_field_id('query'); ?>" name="<?php echo $this->get_field_name('query'); ?>" type="text" value="<?php echo esc_attr($query); ?>" /></label>
			</p>
			<p><?php _e( 'Here you can enter in a custom query string formatted for WordPress\'s <a href="http://codex.wordpress.org/Template_Tags/get_posts" target="_blank">get_posts</a>.', 'themeblvd' ); ?></p>
			<p><?php _e( 'If you enter anything here, your category selection and number of posts selection above will be ignored.', 'themeblvd' ); ?></p>
			<p>
				Example: "tag=whatever"<br>
				Example: "tag=whatever&category_name=portfolio"<br>
				Example: "tag=whatever&numberposts=5&offset=1"
			</p>
		</div>
		<?php	
	}
	
	/* Update Widget Settings */
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['thumb'] = strip_tags($new_instance['thumb']);
        $instance['meta'] = strip_tags($new_instance['meta']);
        $instance['category'] = strip_tags($new_instance['category']);
        $instance['numberposts'] = strip_tags($new_instance['numberposts']);
        $instance['query'] = strip_tags($new_instance['query']);
        return $instance;
	}
	
	/* Display Widget */
	
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);		
		// Title
		$title = $instance['title'];
		// Thumb
		$thumb = $instance['thumb'];
		if( $thumb == 'hide' ) $thumb = false;
		// Meta
		$instance['meta'] == 'hide' ? $meta = false : $meta = true;
		// Build query
		$query = $instance['query'];
		if( ! $query ) {
			$instance['category'] == 'all' ? $category = '' : $category = $instance['category'];
			$query  = 'category_name='.$category;
			$query .= '&numberposts='.$instance['numberposts'];
		}
		// Widget output
		echo $before_widget;
		if ( $title ) echo $before_title.$title.$after_title;
		echo themeblvd_get_mini_post_list( $query, $thumb, $meta );
		echo $after_widget;	
	}

}
register_widget('TB_Widget_Mini_Post_List');