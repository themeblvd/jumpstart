<?php
/**
 * Generates the the interface to manage layouts.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'builder_blvd_manage' ) ) {
	function builder_blvd_manage() {
		
		// Setup columns for management table
		$columns = array(
			array(
				'name' 		=> __( 'Layout Title', 'themeblvd' ),
				'type' 		=> 'title',
			),
			array(
				'name' 		=> __( 'Layout ID', 'themeblvd' ),
				'type' 		=> 'slug',
			)
			/* Hiding the true post ID from user to avoid confusion.
			array(
				'name' 		=> __( 'Layout ID', 'themeblvd' ),
				'type' 		=> 'id',
			)
			*/
		);
		$columns = apply_filters( 'themeblvd_manage_layouts', $columns );
		
		// Display it all
		echo '<div class="metabox-holder">';
		echo themeblvd_post_table( 'tb_layout', $columns );
		echo '</div><!-- .metabox-holder (end) -->';
	}
}

/**
 * Generates the the interface to add a new layout.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'builder_blvd_add' ) ) {
	function builder_blvd_add() {
		
		// Setup sidebar layouts
		$layouts = themeblvd_sidebar_layouts();
		$sidebar_layouts = array( 'default' => __( 'Default Sidebar Layout', 'themeblvd' ) );
		foreach( $layouts as $layout )
			$sidebar_layouts[$layout['id']] = $layout['name'];
		
		// Setup sample layouts
		$samples = builder_blvd_samples();
		$sample_layouts = array();
		if( $samples ) {
			$sample_layouts = array( false => '- Start From Scratch -' );
			foreach( $samples as $sample )
				$sample_layouts[$sample['id']] = $sample['name'];
		}
			
		// Setup options array to display form
		$options = array();
		
		// Layout Name
		$options[] = array( 
			'name' 		=> __( 'Layout Name', 'themeblvd' ),
			'desc' 		=> __( 'Enter a user-friendly name for your layout. You will not be able to change this after you\'ve created the layout.<br><br><em>Example: My Layout</em>', 'themeblvd' ),
			'id' 		=> 'layout_name',
			'type' 		=> 'text'
		);
		
		// Sample Layouts (only show if there are sample layouts)
		if( $sample_layouts ) {
			$options[] = array( 
				'name' 		=> __( 'Starting Point', 'themeblvd' ),
				'desc' 		=> __( 'Select if you\'d like to start building your layout from scratch or from a pre-built sample layout.', 'themeblvd' ),
				'id' 		=> 'layout_start',
				'type' 		=> 'select',
				'options' 	=> $sample_layouts,
				'class'		=> 'builder_samples'
			);
		}
		
		// Sidebar Layout
		$options[] = array( 
			'name' 		=> __( 'Sidebar Layout', 'themeblvd' ),
			'desc' 		=> __( 'Select your sidebar layout for this page.<br><br><em>Note: You can change this later when editing your layout.</em>', 'themeblvd' ),
			'id' 		=> 'layout_sidebar',
			'type' 		=> 'select',
			'options' 	=> $sidebar_layouts
		);
		
		$options = apply_filters( 'themeblvd_add_layout', $options );
		
		// Build form
		$form = optionsframework_fields( 'options', $options, null, false );
		?>
		<div class="metabox-holder">
			<div class="postbox">
				<h3><?php _e( 'Add New Layout', 'themeblvd' ); ?></h3>
				<form id="add_new_slider">
					<div class="inner-group">
						<?php echo $form[0]; ?>
					</div><!-- .group (end) -->
					<div id="optionsframework-submit">
						<input type="submit" class="button-primary" name="update" value="<?php _e( 'Add New Layout', 'themeblvd' ); ?>">
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
 * Generates the an indivdual panel to edit an element. 
 * This has been broken into a separate function because 
 * not only does it show each element when loading the 
 * Edit Layout screen, but it's used to insert a new 
 * element when called upon with AJAX.
 *
 * @since 2.0.0
 *
 * @param string $element_type type of element
 * @param string $element_id ID for individual slide
 * @param array $element_settings any current options for current element
 */

if( ! function_exists( 'builder_blvd_edit_element' ) ) {
	function builder_blvd_edit_element( $element_type, $element_id, $element_settings = null, $visibility = null ) {
		$elements = themeblvd_get_elements();
		$form = optionsframework_fields( 'elements['.$element_id.'][options]', $elements[$element_type]['options'], $element_settings, false );
		?>
		<div id="<?php echo $element_id; ?>" class="widget element-options"<?php if( $visibility == 'hide' ) echo ' style="display:none"'; ?>>					
			<div class="widget-name">
				<a href="#" class="widget-name-arrow">Toggle</a>
				<h3><?php echo $elements[$element_type]['info']['name']; ?></h3>
			</div><!-- .element-name (end) -->
			<div class="widget-content">
				<input type="hidden" class="element-type" name="elements[<?php echo $element_id; ?>][type]" value="<?php echo $element_type; ?>" />
				<input type="hidden" class="element-query" name="elements[<?php echo $element_id; ?>][query_type]" value="<?php echo $elements[$element_type]['info']['query']; ?>" />
				<?php echo $form[0]; ?>
				<div class="submitbox widget-footer">
					<a href="#<?php echo $element_id; ?>" class="submitdelete delete-me" title="<?php _e( 'Are you sure you want to delete this element?', 'themeblvd' ); ?>"><?php _e( 'Delete Element', 'themeblvd' ); ?></a>
					<div class="clear"></div>
				</div><!-- .widget-footer (end) -->
			</div><!-- .element-content (end) -->
		</div><!-- .element-options(end) -->
		<?php
	}
}

/**
 * Generates the the interface to edit the layout.
 *
 * @since 2.0.0
 *
 * @param $id string ID of layout to edit
 */

if( ! function_exists( 'builder_blvd_edit' ) ) {
	function builder_blvd_edit( $id ) {
		
		$elements = themeblvd_get_elements();
		$layout = get_post($id);
		$layout_elements = get_post_meta( $id, 'elements', true );
		$layout_settings = get_post_meta( $id, 'settings', true );
		?>
		<input type="hidden" name="layout_id" value="<?php echo $id; ?>" />
		<div id="poststuff" class="metabox-holder full-width has-right-sidebar">
			<div class="inner-sidebar">
				<div class="postbox postbox-publish">
					<h3 class="hndle"><?php _e( 'Publish', 'themeblvd' ); ?> <?php echo stripslashes($layout->post_title); ?></h3>
					<div class="submitbox">
						<div id="major-publishing-actions">
							<div id="delete-action">
								<a class="submitdelete delete_layout" href="#<?php echo $id; ?>"><?php _e( 'Delete', 'themeblvd' ); ?></a>
							</div>
							<div id="publishing-action">
								<input class="button-primary" value="<?php _e( 'Update Layout', 'themeblvd' ); ?>" type="submit" />
								<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-loading" />
							</div>
							<div class="clear"></div>
						</div>
					</div><!-- .submitbox (end) -->
				</div><!-- .post-box (end) -->
				<div class="postbox postbox-layout-info">
					<h3 class="hndle"><?php _e('Layout Information', 'themeblvd' ); ?></h3>
					<?php
					// Current settings
					$info_settings = array(
						'post_title' 	=> $layout->post_title,
						'post_name'		=> $layout->post_name
					);
					
					// Setup attribute options
					$info_options = array( 
						array( 
							'name'		=> __('Layout Name', 'themeblvd' ),
							'id' 		=> 'post_title',
							'desc'		=> __('This title is just for you. It\'ll never be used outside of your WordPress admin panel.', 'themeblvd'),
							'type' 		=> 'text'
						),
						array( 
							'name' 		=> __('Layout ID', 'themeblvd' ),
							'id' 		=> 'post_name',
							'desc'		=> __( 'Custom layouts are assigned based on this ID. So if you change this at any point, make sure to also update any pages or options in which you\'ve assigned this specific layout.', 'themeblvd' ),
							'type' 		=> 'text'
						)
					);
	
					// Display form element
					$form = optionsframework_fields( 'info', $info_options, $info_settings, false );
					echo $form[0]; 
					?>
				</div><!-- .post-box (end) -->
				<div class="postbox postbox-sidebar-layout">
					<h3 class="hndle"><?php _e('Sidebar Layout', 'themeblvd' ); ?></h3>
					<?php
					// Setup sidebar layouts
					$layouts = themeblvd_sidebar_layouts();
					$sidebar_layouts = array( 'default' => __( 'Default Sidebar Layout', 'themeblvd' ) );
					foreach( $layouts as $layout )
						$sidebar_layouts[$layout['id']] = $layout['name'];
					
					$options = array( 
						array( 
							'id' 		=> 'sidebar_layout',
							'desc'		=> __( 'Select how you\'d like the sidebar(s) arranged in this layout. Your site-wide default sidebar layout can be set from your Theme Options page.<br><br><strong>Note: The sidebar layout is only applied to the "Primary Area" of the custom layout.</strong>', 'themeblvd' ),
							'type' 		=> 'select',
							'options' 	=> $sidebar_layouts
						)
					);
	
					// Display form element
					$form = optionsframework_fields( 'options', $options, $layout_settings, false );
					echo $form[0]; 
					?>
				</div><!-- .post-box (end) -->
			</div><!-- .inner-sidebar (end) -->
			<div id="post-body">
				<div id="post-body-content">
					<div id="titlediv">
						<div class="ajax-overlay"></div>
						<h2><?php _e( 'Manage Elements', 'themeblvd' ); ?></h2>
						<select>
						<?php
						foreach( $elements as $element )
							echo '<option value="'.$element['info']['id'].'=>'.$element['info']['query'].'">'.$element['info']['name'].'</option>';
						?>
						</select>
						<a href="#" id="add_new_element" class="button-secondary"><?php _e( 'Add New Element', 'themeblvd' ); ?></a>
						<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-loading" id="ajax-loading">
						<div class="clear"></div>
					</div><!-- #titlediv (end) -->
					<div id="builder">
						<div id="featured">
							<span class="label"><?php _e( 'Featured Above', 'themeblvd' ); ?></span>
							<div class="sortable">
								<?php
								if( ! empty( $layout_elements['featured'] ) ) {
									foreach( $layout_elements['featured'] as $id => $element ) {
										if( themeblvd_is_element( $element['type'] ) ) {
											builder_blvd_edit_element( $element['type'], $id, $element['options'] );
										}
									}
								}
								?>
							</div><!-- .sortable (end) -->
						</div><!-- #featured (end) -->
						<div id="primary">
							<input type="hidden" name="elements[divider]" value="" />
							<span class="label"><?php _e( 'Primary Area', 'themeblvd' ); ?></span>
							<div class="sortable">
								<?php
								if( ! empty( $layout_elements['primary'] ) ) {
									foreach( $layout_elements['primary'] as $id => $element ) {
										if( themeblvd_is_element( $element['type'] ) ) {
											builder_blvd_edit_element( $element['type'], $id, $element['options'] );
										}
									}
								}
								?>
							</div><!-- .sortable (end) -->
						</div><!-- #primary (end) -->
						<div id="featured_below">
							<input type="hidden" name="elements[divider_2]" value="" />
							<span class="label"><?php _e( 'Featured Below', 'themeblvd' ); ?></span>
							<div class="sortable">
								<?php
								if( ! empty( $layout_elements['featured_below'] ) ) {
									foreach( $layout_elements['featured_below'] as $id => $element ) {
										if( themeblvd_is_element( $element['type'] ) ) {
											builder_blvd_edit_element( $element['type'], $id, $element['options'] );
										}
									}
								}
								?>
							</div><!-- .sortable (end) -->
						</div><!-- #primary (end) -->
					</div><!-- #builder (end) -->
				</div><!-- .post-body-content (end) -->
			</div><!-- #post-body (end) -->
		</div><!-- #poststuff (end) -->
		<?php
	}
}