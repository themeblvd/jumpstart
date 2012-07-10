<?php
/**
 * Generates the the interface to manage sliders.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'slider_blvd_manage' ) ) {
	function slider_blvd_manage() {
		
		// Setup columns for management table
		$columns = array(
			array(
				'name' 		=> __( 'Slider Title', TB_GETTEXT_DOMAIN ),
				'type' 		=> 'title',
			),
			array(
				'name' 		=> __( 'Slider ID', TB_GETTEXT_DOMAIN ),
				'type' 		=> 'slug',
			),
			/* Hiding the true post ID from user to avoid confusion.
			array(
				'name' 		=> __( 'Slider ID', TB_GETTEXT_DOMAIN ),
				'type' 		=> 'id',
			),
			*/
			array(
				'name' 		=> __( 'Slider Type', TB_GETTEXT_DOMAIN ),
				'type' 		=> 'meta',
				'config' 	=> 'type' // Meta key to use to get value
			),
			array(
				'name' 		=> __( 'Shortcode Usage', TB_GETTEXT_DOMAIN ),
				'type' 		=> 'shortcode',
				'config' 	=> 'slider' // Shortcode key
			),
		);
		$columns = apply_filters( 'themeblvd_manage_sliders', $columns );
		
		// Display it all
		echo '<div class="metabox-holder">';
		echo themeblvd_post_table( 'tb_slider', $columns );
		echo '</div><!-- .metabox-holder (end) -->';
	}
}

/**
 * Generates the the interface to add a new slider.
 *
 * @since 2.0.0
 *
 * @param $types array All default sliders
 */

if( ! function_exists( 'slider_blvd_add' ) ) {
	function slider_blvd_add( $types ) {
		
		// Setup slider types for options array
		$slider_types = array();
		foreach( $types as $type ) {
			$slider_types[$type['id']] = $type['name'];
		}
		
		// Setup options array to display form
		$options = array(
			array( 
				'name' 		=> __( 'Slider Name', TB_GETTEXT_DOMAIN ),
				'desc' 		=> __( 'Enter a user-friendly name for your slider.<br>Example: My Slider', TB_GETTEXT_DOMAIN ),
				'id' 		=> 'slider_name',
				'type' 		=> 'text'
			),
			array( 
				'name' 		=> __( 'Slider Type', TB_GETTEXT_DOMAIN ),
				'desc' 		=> __( 'Select which type of slider among the theme\'s currently supported slider types.', TB_GETTEXT_DOMAIN ),
				'id' 		=> 'slider_type',
				'type' 		=> 'select',
				'options' 	=> $slider_types
			)
		);
		$options = apply_filters( 'themeblvd_add_slider', $options );
		
		// Build form
		$form = optionsframework_fields( 'options', $options, null, false );
		?>
		<div class="metabox-holder">
			<div class="postbox">
				<h3><?php _e( 'Add New Slider', TB_GETTEXT_DOMAIN ); ?></h3>
				<form id="add_new_slider">
					<div class="inner-group">
						<?php echo $form[0]; ?>
					</div><!-- .group (end) -->
					<div id="optionsframework-submit">
						<input type="submit" class="button-primary" name="update" value="<?php _e( 'Add New Slider', TB_GETTEXT_DOMAIN ); ?>">
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
 * Generates the an indivdual panel to edit a slide. 
 * This has been broken into a separate function because 
 * not only does it show each slide when loading the 
 * Edit Slider screen, but it's used to insert a new 
 * slide when called with AJAX.
 *
 * @since 2.0.0
 *
 * @param string $slider_type type of slider
 * @param string $slide_id ID for individual slide
 * @param array $slide_options any current options for current slide
 */

if( ! function_exists( 'slider_blvd_edit_slide' ) ) {
	function slider_blvd_edit_slide( $slider_id, $slider_type, $slide_id, $slide_options = null, $visibility = null ) {
		$slider_types = slider_blvd_recognized_sliders();
		?>
		<div id="<?php echo $slide_id; ?>" class="widget slide-options"<?php if( $visibility == 'hide' ) echo ' style="display:none"'; ?>>					
			<div class="widget-name">
				<a href="#" class="widget-name-arrow">Toggle</a>
				<h3><?php _e( 'Slide', TB_GETTEXT_DOMAIN ); ?> (<?php echo $slider_type; ?>)</h3>
			</div><!-- .element-name (end) -->
			<div class="widget-content">
				<div class="slide-set-type">
					<strong><?php _e( 'Image Slide', TB_GETTEXT_DOMAIN ); ?></strong>
					<select name="slides[<?php echo $slide_id; ?>][slide_type]">
						<?php
						$slide_type = slider_blvd_slide_value( $slide_options, 'slide_type' );
						foreach( $slider_types[$slider_type]['types'] as $key => $value ) {						
	        				echo '<option '.selected( $key, $slide_type, false ).' value="'.$key.'">'.$value['name'].'</option>';
	        			}
	        			?>
					</select>
				</div><!-- .slide-set-type (end) -->
				<div class="pad">
					<div class="slide-media controls grid-wrap">
						<div class="slide-set-media">
							<?php 
							foreach( $slider_types[$slider_type]['types'] as $type => $config ) {
								switch ( $type ) {
									case 'image' :
										?>
										<div class="slide-set-image">
											<h3><?php echo $config['main_title']; ?></h3>
											<div class="field section-upload">
												<?php
												$current_image = slider_blvd_slide_value( $slide_options, 'image' );
												echo optionsframework_medialibrary_uploader( 'slides['.$slide_id.']', 'slider', $slide_id.'image', $current_image, null, null, $slider_id, null, __( 'Get Image', TB_GETTEXT_DOMAIN ) );
												?>
											</div><!-- .field (end) -->
										</div><!-- .slide-set-image (end) -->
										<?php
										break;
									case 'video' :
										?>
										<div class="slide-set-video">
											<h3><?php echo $config['main_title']; ?></h3>
											<div class="field video-link">
												<?php
												$current_video = slider_blvd_slide_value( $slide_options, 'video' );
												echo '<input type="text" name="slides['.$slide_id.'][video]" value="'.$current_video.'" />';
												?>
												<p class="explain"><?php _e( 'Enter in a video URL compatible with <a href="http://codex.wordpress.org/Embeds">WordPress\'s oEmbed</a>.<br><br>Ex: http://youtube.com/watch?v=HPPj6viIBmU<br>Ex: http://vimeo.com/11178250<br>Ex: http://wordpress.tv/2011/08/14/name-of-video', TB_GETTEXT_DOMAIN ); ?>
											</div><!-- .field (end) -->
										</div><!-- .slide-set-video (end) -->
										<?php
										break;
								}
							}
							?>
						</div><!-- .slide-set-media (end) -->
						<div class="slide-include-elements">
							<div class="slide-section">
								<?php if( $slider_types[$slider_type]['positions'] ) : ?>
									<h4><?php _e( 'How would you like to display the media?', TB_GETTEXT_DOMAIN ); ?></h4>
									<select class="slide-position" name="slides[<?php echo $slide_id; ?>][position]">
										<?php
										$position = slider_blvd_slide_value($slide_options, 'position');
										foreach( $slider_types[$slider_type]['positions'] as $key => $value ) {
					        				echo '<option '.selected( $key, $position, false ).' value="'.$key.'">'.$value.'</option>';
					        			}
					        			?>
									</select>
								<?php endif; ?>
							</div><!-- .slide-section (end) -->
							<?php if( ! empty( $slider_types[$slider_type]['elements'] ) ) : ?>
								<div class="slide-section">
									<h4><?php _e( 'Would you like to include additional elements?', TB_GETTEXT_DOMAIN ); ?></h4>
									<table class="widefat slide-elements">
										<tbody>
										<?php
										foreach( $slider_types[$slider_type]['elements'] as $element ) {
											switch( $element ) {
												
												case 'image_link' : 
													if( $key != 'video' ) {	// A video would never be wrapped in a link
														?>
														<tr class="element-image_link slide-element-header">
															<td class="slide-element-check"><input value="image_link" type="checkbox" name="slides[<?php echo $slide_id; ?>][elements][include][]"<?php echo slider_blvd_slide_value($slide_options, 'include', 'image_link'); ?> /></td>
															<td class="slide-element-name"><?php _e( 'Image Link', TB_GETTEXT_DOMAIN ); ?></td>
															<td class="slide-element-help"><a href="#" class="help-icon tooltip-link" title="<?php _e( 'This will allow you to apply a link to the image of this slide. You can configure it to open a webpage or a lightbox popup of different media types.', TB_GETTEXT_DOMAIN ); ?>">Help</a></td>
														</tr>
														<tr class="element-image_link slide-element-options">
															<td colspan="3">
																<div class="field">
																	<h5><?php _e( 'Where should the link open?', TB_GETTEXT_DOMAIN ); ?></h5>
																	<?php $target = slider_blvd_slide_value($slide_options, 'image_link', 'target'); ?>
																	<select name="slides[<?php echo $slide_id; ?>][elements][image_link][target]">
																		<option value="_self" <?php selected( $target, '_self' ); ?>><?php _e( 'Same Window', TB_GETTEXT_DOMAIN ); ?></option>
																		<option value="_blank" <?php selected( $target, '_blank' ); ?>><?php _e( 'New Window', TB_GETTEXT_DOMAIN ); ?></option>
																		<option value="lightbox" <?php selected( $target, 'lightbox' ); ?>><?php _e( 'Lightbox Popup', TB_GETTEXT_DOMAIN ); ?></option>
																	</select>
																</div><!-- .field (end) -->
																<div class="field">
																	<h5><?php _e( 'Where should the link go?', TB_GETTEXT_DOMAIN ); ?></h5>
																	<input name="slides[<?php echo $slide_id; ?>][elements][image_link][url]" type="text" value="<?php echo slider_blvd_slide_value($slide_options, 'image_link', 'url'); ?>" class="input" />
																	</div><!-- .class="more-info (end) -->
																</div><!-- .field (end) -->
															</td>
														</tr>
														<?php
													}
													break;
													
												case 'headline' : 
													
													?>
													<tr class="element-headline slide-element-header">
														<td class="slide-element-check"><input value="headline" type="checkbox" name="slides[<?php echo $slide_id; ?>][elements][include][]"<?php echo slider_blvd_slide_value($slide_options, 'include', 'headline'); ?> /></td>
														<td class="slide-element-name"><?php _e( 'Headline', TB_GETTEXT_DOMAIN ) ?></td>
														<td class="slide-element-help"><a href="#" class="help-icon tooltip-link" title="<?php _e( 'This will allow you to insert a simple headline on your slide. The location and style of this headline will vary depending on the design of the current theme.', TB_GETTEXT_DOMAIN ); ?>">Help</a></td>
													</tr>
													<tr class="element-headline slide-element-options">
														<td colspan="3">
															<div class="field">
																<h5><?php _e( 'What should the headline say?', TB_GETTEXT_DOMAIN ); ?></h5>
																<textarea name="slides[<?php echo $slide_id; ?>][elements][headline]"><?php echo slider_blvd_slide_value($slide_options, 'headline'); ?></textarea>
															</div><!-- .field (end) -->
														</td>
													</tr>
													<?php
													
													break;
													
												case 'description' : 
													
													?>
													<tr class="element-description slide-element-header">
														<td class="slide-element-check"><input value="description" type="checkbox" name="slides[<?php echo $slide_id; ?>][elements][include][]"<?php echo slider_blvd_slide_value($slide_options, 'include', 'description'); ?> /></td>
														<td class="slide-element-name"><?php _e( 'Description', TB_GETTEXT_DOMAIN ); ?></td>
														<td class="slide-element-help"><a href="#" class="help-icon tooltip-link" title="<?php _e( 'This will allow you to insert a simple description on your slide. The location and style of this description will vary depending on the design of the current theme.', TB_GETTEXT_DOMAIN ); ?>">Help</a></td>
													</tr>
													<tr class="element-description slide-element-options">
														<td colspan="3">
															<div class="field">
																<h5><?php _e( 'What should the description say?', TB_GETTEXT_DOMAIN ); ?></h5>
																<textarea name="slides[<?php echo $slide_id; ?>][elements][description]"><?php echo slider_blvd_slide_value($slide_options, 'description'); ?></textarea>
															</div><!-- .field (end) -->
														</td>
													</tr>
													<?php
													
													break;
													
												case 'button' : 
													
													?>
													<tr class="element-button slide-element-header">
														<td class="slide-element-check"><input value="button" type="checkbox" name="slides[<?php echo $slide_id; ?>][elements][include][]"<?php echo slider_blvd_slide_value($slide_options, 'include', 'button'); ?> /></td>
														<td class="slide-element-name"><?php _e( 'Button', TB_GETTEXT_DOMAIN ); ?></td>
														<td class="slide-element-help"><a href="#" class="help-icon tooltip-link" title="<?php _e( 'This will allow you to include a button on your slide. You can configure it to open a webpage or a lightbox popup of different media types.', TB_GETTEXT_DOMAIN ); ?>">Help</a></td>
													</tr>
													<tr class="element-button slide-element-options">
														<td colspan="3">
															<div class="field">
																<h5><?php _e( 'What should the button say?', TB_GETTEXT_DOMAIN ); ?></h5>
																<input name="slides[<?php echo $slide_id; ?>][elements][button][text]" type="text" value="<?php echo slider_blvd_slide_value($slide_options, 'button', 'text'); ?>" class="input" />
															</div><!-- .field (end) -->
															<div class="field">
																<h5><?php _e( 'Where should the link open?', TB_GETTEXT_DOMAIN ); ?></h5>
																<?php $target = slider_blvd_slide_value($slide_options, 'button', 'target'); ?>
																<select name="slides[<?php echo $slide_id; ?>][elements][button][target]">
																	<option value="_self" <?php selected( $target, '_self' ); ?>><?php _e( 'Same Window', TB_GETTEXT_DOMAIN ); ?></option>
																	<option value="_blank" <?php selected( $target, '_blank' ); ?>><?php _e( 'New Window', TB_GETTEXT_DOMAIN ); ?></option>
																	<option value="lightbox" <?php selected( $target, 'lightbox' ); ?>><?php _e( 'Lightbox Popup', TB_GETTEXT_DOMAIN ); ?></option>
																</select>
															</div><!-- .field (end) -->
															<div class="field">
																<h5><?php _e( 'Where should the link go?', TB_GETTEXT_DOMAIN ); ?></h5>
																<input name="slides[<?php echo $slide_id; ?>][elements][button][url]" type="text" value="<?php echo slider_blvd_slide_value($slide_options, 'button', 'url'); ?>" class="input" />
															</div><!-- .field (end) -->
														</td>
													</tr>
													<?php
													
													break;
											}
										}
										?>
										</tbody>
									</table>
									<p class="warning slide-elements-warning"><?php _e( 'You cannot have any elements on top of full-size video. If you\'d like to include elements, align the video to the right or left.', TB_GETTEXT_DOMAIN ); ?></p>
								</div><!-- .slide-section (end) -->
							<?php endif; ?>
						</div><!-- .slide-include-elements (end) -->
						<div class="clear"></div>
					</div><!-- .grid-wrap (end) -->
					<?php if( array_key_exists( 'custom', $slider_types[$slider_type]['types'] ) ) : ?>
					<div class="controls slide-custom">
						<h3><?php echo $slider_types[$slider_type]['types']['custom']['main_title']; ?></h3>
						<?php $custom = slider_blvd_slide_value( $slide_options, 'custom' ); ?>
						<textarea name="slides[<?php echo $slide_id; ?>][custom]"><?php echo $custom; ?></textarea>
					</div><!-- .slide-custom (end) -->
					<?php endif; ?>
				</div><!-- .pad (end) -->
				<div class="submitbox widget-footer">
					<a href="#<?php echo $slide_id; ?>" class="submitdelete delete-me" title="<?php _e( 'Are you sure you want to delete this slide?', TB_GETTEXT_DOMAIN ); ?>"><?php _e( 'Delete Slide', TB_GETTEXT_DOMAIN ); ?></a>
					<div class="clear"></div>
				</div><!-- .widget-footer (end) -->
			</div><!-- .element-content (end) -->
		</div><!-- .slide-options(end) -->
		<?php
	}
}

/**
 * Generates the the interface to edit slider.
 *
 * @since 2.0.0
 *
 * @param $id string ID of slider to edit
 * @param $types array all default slider info
 */

if( ! function_exists( 'slider_blvd_edit' ) ) {
	function slider_blvd_edit( $id, $types ) {
		
		// Get slider custom post
		$slider = get_post($id);
		$post_id = $slider->ID;
		
		if( $slider ) {
			$current_slides = get_post_meta( $post_id, 'slides', true );
			$type = get_post_meta( $post_id, 'type', true );
			$options = $types[$type]['options'];
			$settings = get_post_meta( $post_id, 'settings', true );
			?>
			<input type="hidden" name="slider_id" value="<?php echo $post_id; ?>" />
			<div id="poststuff" class="metabox-holder full-width has-right-sidebar">
				<div class="inner-sidebar">
					<div class="postbox">
						<h3 class="hndle"><?php _e( 'Publish', TB_GETTEXT_DOMAIN ); ?> <?php echo stripslashes($slider->post_title); ?></h3>
						<div class="submitbox">
							<div id="major-publishing-actions">
								<div id="delete-action">
									<a class="submitdelete delete_slider" href="#<?php echo $post_id; ?>"><?php _e( 'Delete', TB_GETTEXT_DOMAIN ); ?></a>
								</div>
								<div id="publishing-action">
									<input class="button-primary" value="<?php _e( 'Update Slider', TB_GETTEXT_DOMAIN ); ?>" type="submit" />
									<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-loading" />
								</div>
								<div class="clear"></div>
							</div>
						</div><!-- .submitbox (end) -->
					</div><!-- .post-box (end) -->
					<?php if( $options ) : ?>
						<div class="postbox">
							<h3 class="hndle"><?php echo $types[$type]['name'].' '.__( 'Options', TB_GETTEXT_DOMAIN ); ?></h3>
							<?php 
							// Slider Options
							$form = optionsframework_fields( 'options', $options, $settings, false );
							echo $form[0];
							?>
						</div><!-- .post-box (end) -->
					<?php endif; ?>
				</div><!-- .inner-sidebar (end) -->
				<div id="post-body">
					<div id="post-body-content">
						<div id="titlediv">
							<div class="ajax-overlay"></div>
							<h2><?php _e( 'Manage Slides', TB_GETTEXT_DOMAIN ); ?></h2>
							<a href="#<?php echo $post_id; ?>=><?php echo $type; ?>" id="add_new_slide" class="button-secondary"><?php _e( 'Add New Slide', TB_GETTEXT_DOMAIN ); ?></a>
							<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-loading" id="ajax-loading">
							<div class="clear"></div>
						</div><!-- #titlediv (end) -->
						<div id="sortable">
							<?php
							if( ! empty( $current_slides ) ) {
								foreach( $current_slides as $slide_id => $slide ) {
									slider_blvd_edit_slide( $post_id, $type, $slide_id, $slide );
								}
							} else {
								echo '<p class="warning no-item-yet">'.__( 'You haven\'t added any slides yet. Get started by clicking "Add New Slide" above.', TB_GETTEXT_DOMAIN ).'</p>';
							}
	    					?>
						</div><!-- .sortable-slides (end) -->
					</div><!-- #post-body-content (end) -->
				</div><!-- #post-body (end) -->
			</div><!-- .metabox-holder (end) -->
			<?php
		} else {
			echo '<p>'.__( 'Error: The slider you\'re trying to edit doesn\'t exist.', TB_GETTEXT_DOMAIN ).'</p>';
		}
	}
}