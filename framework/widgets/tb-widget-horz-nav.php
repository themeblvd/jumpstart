<?php
/**
 * Theme Blvd Video Widget
 * 
 * @package Theme Blvd WordPress Framework
 * @author Jason Bobich
 */

class TB_Horz_Menu extends WP_Widget {
	
	/* Constructor */
	
	function __construct() {
		$widget_ops = array(
			'classname' => 'tb-horz_menu_widget', 
			'description' => 'Display a custom menu in a collapsible widget area that displays horizontally such as the "Ads Above Content" widget area.'
		);
        $this->WP_Widget( 'themeblvd_horz_menu_widget', 'Theme Blvd Horizontal Menu', $widget_ops );
	}
	
	/* Widget Options Form */
	
	function form( $instance ) {
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

		// Get menus
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

		// If no menus exists, direct the user to go and create some.
		if ( !$menus ) {
			echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') ) .'</p>';
			return;
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:'); ?></label>
			<select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
		<?php
			foreach ( $menus as $menu ) {
				$selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
				echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
			}
		?>
			</select>
		</p>
		<?php
	}
	
	/* Update Widget Settings */
	
	function update( $new_instance, $old_instance ) {
		$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		return $instance;
	}
	
	/* Display Widget */
	
	function widget($args, $instance) {
		extract( $args );
		
		// Get menu
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

		if ( !$nav_menu )
			return;
		
		echo $before_widget;
		wp_nav_menu( array( 'fallback_cb' => '', 'container_class' => 'subnav', 'menu_class' => 'sf-menu nav nav-pills', 'menu' => $nav_menu ) );
		echo $after_widget;		
	}

}
register_widget('TB_Horz_Menu');