<?php 
/**
 * Generates the the interface to manage sidebars.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'sidebar_blvd_manage' ) ) {
	function sidebar_blvd_manage() {
		
		// Setup columns for management table
		$columns = array(
			array(
				'name' 		=> __( 'Widget Area Title', TB_GETTEXT_DOMAIN ),
				'type' 		=> 'title',
			),
			array(
				'name' 		=> __( 'ID', TB_GETTEXT_DOMAIN ),
				'type' 		=> 'slug',
			),
			/* Hiding the true post ID from user to avoid confusion.
			array(
				'name' 		=> __( 'ID', TB_GETTEXT_DOMAIN ),
				'type' 		=> 'id',
			),
			*/
			array(
				'name' 		=> __( 'Location', TB_GETTEXT_DOMAIN ),
				'type' 		=> 'sidebar_location'
			),
			array(
				'name' 		=> __( 'Assignments', TB_GETTEXT_DOMAIN ),
				'type' 		=> 'assignments',
			)
		);
		$columns = apply_filters( 'themeblvd_manage_sidebars', $columns );
		
		// Display it all
		echo '<div class="metabox-holder">';
		echo themeblvd_post_table( 'tb_sidebar', $columns );
		echo '</div><!-- .metabox-holder (end) -->';
	}
}

/**
 * Generates the the interface to add a new slider.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'sidebar_blvd_add' ) ) {
	function sidebar_blvd_add() {
		
		// Setup sidebar layouts
		$sidebars = themeblvd_get_sidebar_locations();
		$sidebar_locations = array( 'floating' => __( 'No Location (Floating Widget Area)', TB_GETTEXT_DOMAIN ) );
		foreach( $sidebars as $sidebar )
			$sidebar_locations[$sidebar['location']['id']] = $sidebar['location']['name'];
			
		// Setup options array to display form
		$options = array(
			array( 
				'name' 		=> __( 'Widget Area Name', TB_GETTEXT_DOMAIN ),
				'desc' 		=> __( 'Enter a user-friendly name for your widget area.<br><br><em>Example: My Sidebar</em>', TB_GETTEXT_DOMAIN ),
				'id' 		=> 'sidebar_name',
				'type' 		=> 'text'
			),
			array( 
				'name' 		=> __( 'Widget Area Location', TB_GETTEXT_DOMAIN ),
				'desc' 		=> __( 'Select which location on the site this widget area will be among the theme\'s currently supported widget area locations.<br><br><em>Note: A "Floating Widget Area" can be used in dynamic elements like setting up columns in the layout builder, for example.</em>', TB_GETTEXT_DOMAIN ),
				'id' 		=> 'sidebar_location',
				'type' 		=> 'select',
				'options' 	=> $sidebar_locations,
			),
			array( 
				'name' 		=> __( 'Widget Area Assignments', TB_GETTEXT_DOMAIN ),
				'desc' 		=> __( 'Select the places on your site you\'d like this custom widget area to show in the location you picked previously.<br><br><em>Note: You can edit the location you selected previously and these assignments later if you change your mind.</em><br><br><em>Note: Assignments will be ignored on "Floating Widget Areas" but since you can always come back and change the location for a custom widget area, assignments still will always be stored.</em>', TB_GETTEXT_DOMAIN ),
				'id' 		=> 'sidebar_assignments',
				'type' 		=> 'conditionals'
			)
		);
		$options = apply_filters( 'themeblvd_add_sidebar', $options );
		
		// Build form
		$form = optionsframework_fields( 'options', $options, null, false );
		?>
		<div class="metabox-holder">
			<div class="postbox">
				<h3><?php _e( 'Add New Widget Area', TB_GETTEXT_DOMAIN ); ?></h3>
				<form id="add_new_sidebar">
					<div class="inner-group">
						<?php echo $form[0]; ?>
					</div><!-- .group (end) -->
					<div id="optionsframework-submit">
						<input type="submit" class="button-primary" name="update" value="<?php _e( 'Add New Widget Area', TB_GETTEXT_DOMAIN ); ?>">
						<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-loading" id="ajax-loading">
			            <div class="clear"></div>
					</div>
				</form><!-- #add_new_slider (end) -->
			</div><!-- .postbox (end) -->
		</div><!-- .metabox-holder (end) -->
		<?php
	}
}

/**
 * Generates the the interface to edit the sidebar.
 *
 * @since 2.0.0
 *
 * @param $id string ID of sidebar to edit
 */

if( ! function_exists( 'sidebar_blvd_edit' ) ) {
	function sidebar_blvd_edit( $id ) {
		
		$post = get_post( $id );
		
		// Setup sidebar layouts
		$sidebars = themeblvd_get_sidebar_locations();
		$sidebar_locations = array( 'floating' => __( 'No Location (Floating Widget Area)', TB_GETTEXT_DOMAIN ) );
		foreach( $sidebars as $sidebar )
			$sidebar_locations[$sidebar['location']['id']] = $sidebar['location']['name'];
			
		// Setup options array to display form
		$options = array(
			array( 
				'name' 		=> __( 'Widget Area Location', TB_GETTEXT_DOMAIN ),
				'desc' 		=> __( 'Select which location on the site this widget area will be among the theme\'s currently supported widget area locations.<br><br><em>Note: A "Floating Widget Area" can be used in dynamic elements like setting up columns in the layout builder, for example.</em>', TB_GETTEXT_DOMAIN ),
				'id' 		=> 'sidebar_location',
				'type' 		=> 'select',
				'options' 	=> $sidebar_locations,
			),
			array( 
				'name' 		=> __( 'Widget Area Assignments', TB_GETTEXT_DOMAIN ),
				'desc' 		=> __( 'Select the places on your site you\'d like this custom widget area to show in the location you picked previously.<br><br><em>Note: Assignments will be ignored on "Floating Widget Areas" but since you can always come back and change the location for a custom widget area, assignments still will always be stored.</em>', TB_GETTEXT_DOMAIN ),
				'id' 		=> 'sidebar_assignments',
				'type' 		=> 'conditionals'
			)
		);
		
		// Settup current settings
		$settings = array(
			'sidebar_location' => get_post_meta( $id, 'location', true ),
			'sidebar_assignments' => get_post_meta( $id, 'assignments', true )
		);
		
		// Build form
		$form = optionsframework_fields( 'options', $options, $settings, false );
		?>
		<div class="metabox-holder">
			<div class="postbox">
				<h3><?php echo $post->post_title; ?></h3>
				<div class="inner-group">
					<input type="hidden" name="sidebar_id" value="<?php echo $id; ?>" />
					<?php echo $form[0]; ?>
				</div><!-- .group (end) -->
				<div id="optionsframework-submit">
					<input type="submit" class="button-primary" name="update" value="<?php _e( 'Save Widget Area', TB_GETTEXT_DOMAIN ); ?>">
					<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-loading" id="ajax-loading">
		            <div class="clear"></div>
				</div>
			</div><!-- .postbox (end) -->
		</div><!-- .metabox-holder (end) -->
		<?php
	}
}