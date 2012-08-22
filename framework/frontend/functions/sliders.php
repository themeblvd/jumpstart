<?php
/*-----------------------------------------------------------------------------------*/
/* Slider Javascript
/*-----------------------------------------------------------------------------------*/

/**
 * Print out the JS for setting up a standard slider.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_standard_slider_js' ) ) {
	function themeblvd_standard_slider_js( $id, $options ) {
		?>
		<script>
		jQuery(document).ready(function($) {
			$(window).load(function() {
				
				// Initiate flexslider for this slider.
				$('#tb-slider-<?php echo $id; ?> .flexslider').flexslider({
					useCSS: false, // Avoid CSS3 glitches
					video: true, // Avoid CSS3 glitches
					smoothHeight: true,
					prevText: '<i class="icon-circle-arrow-left"></i>',
					nextText: '<i class="icon-circle-arrow-right"></i>',
					animation: "<?php echo $options['fx']; ?>",
					// pauseOnHover: true - This was replaced with a custom solution to work with other controls, see below with "pause_on_hover" option.
					<?php if( $options['timeout'] ) : ?>
					slideshowSpeed: <?php echo $options['timeout']; ?>000,
					<?php else : ?>
					slideshow: false,
					<?php endif; ?>
					<?php if( ! $options['nav_arrows'] ) echo 'directionNav: false,'; ?>
					<?php if( ! $options['nav_standard'] ) echo 'controlNav: false,'; ?>
					controlsContainer: ".slides-wrapper-<?php echo $id; ?>",
					start: function(slider) {
        				<?php if( $options['pause_play'] && $options['timeout'] != '0' ) : ?>
		    				$('#tb-slider-<?php echo $id; ?> .flex-direction-nav li:first-child').after('<li><a class="flex-pause" href="#"><i class="icon-pause"></i></a></li><li><a class="flex-play" href="#" style="display:none"><i class="icon-play"></i></a></li>');
		    				$('#tb-slider-<?php echo $id; ?> .flex-pause').click(function(){
								slider.pause();
								$(this).hide();
								$('#tb-slider-<?php echo $id; ?> .flex-play').show();
								return false;
							});
							$('#tb-slider-<?php echo $id; ?> .flex-play').click(function(){
								// slider.resume(); currently has a bug with FlexSlider 2.0, so will do the next line instead.
								$('#tb-slider-<?php echo $id; ?> .flexslider').flexslider('play');
								$(this).hide();
								$('#tb-slider-<?php echo $id; ?> .flex-pause').show();
								return false;
							});
							$('#tb-slider-<?php echo $id; ?> .flex-control-nav li, #tb-slider-<?php echo $id; ?> .flex-direction-nav li').click(function(){
								$('#tb-slider-<?php echo $id; ?> .flex-pause').hide();
								$('#tb-slider-<?php echo $id; ?> .flex-play').show();
							});
						<?php endif; ?>
        				$('#tb-slider-<?php echo $id; ?> .image-link').click(function(){
        					$('#tb-slider-<?php echo $id; ?> .flex-pause').hide();
        					$('#tb-slider-<?php echo $id; ?> .flex-play').show();
        					slider.pause();
        				});
        			}
				}).parent().find('.tb-loader').fadeOut();
				
				<?php if( isset( $options['pause_on_hover'] ) ) : ?>
					<?php if( $options['pause_on_hover'] == 'pause_on' || $options['pause_on_hover'] == 'pause_on_off' ) : ?>
					// Custom pause on hover funtionality
					$('#tb-slider-<?php echo $id; ?>').hover(
						function() {
							$('#tb-slider-<?php echo $id; ?> .flex-pause').hide();
							$('#tb-slider-<?php echo $id; ?> .flex-play').show();
							$('#tb-slider-<?php echo $id; ?> .flexslider').flexslider('pause');
						}, 
						function() {
							<?php if( $options['pause_on_hover'] == 'pause_on_off' ) : ?>
							$('#tb-slider-<?php echo $id; ?> .flex-play').hide();
							$('#tb-slider-<?php echo $id; ?> .flex-pause').show();
							$('#tb-slider-<?php echo $id; ?> .flexslider').flexslider('play');
							<?php endif; ?>
						}
					);
					<?php endif; ?>
				<?php endif; ?>
				
			});
		});
		</script>
		<?php
	}
}

/**
 * Print out the JS for setting up a carrousel slider.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_carrousel_slider_js' ) ) {
	function themeblvd_carrousel_slider_js( $id, $options ) {
		?>
		<script>
		jQuery(document).ready(function($) {
			$(window).load(function() {
				$('#tb-slider-<?php echo $id; ?> .tb-loader').fadeOut('fast');
				$('#tb-slider-<?php echo $id; ?> .slider-inner').fadeIn('fast');
				$('#tb-slider-<?php echo $id; ?> .carrousel-slider').roundabout({
					// minOpacity: '1',
					responsive: true,
					<?php if( $options['nav_arrows'] ) : ?>
					btnNext: '#tb-slider-<?php echo $id; ?> .next',
         			btnPrev: '#tb-slider-<?php echo $id; ?> .prev'
         			<?php endif; ?>
				});
			});
		});
		</script>
		<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* Slider Default Actions
/*-----------------------------------------------------------------------------------*/

/**
 * Standard Slider - default action for themeblvd_standard_slider
 *
 * @since 2.0.0
 *
 * @param var $slider ID of current slider
 * @param array $settings Current settings for slider
 * @param array $slides Current slides for slider
 */

if( ! function_exists( 'themeblvd_standard_slider_default' ) ) {
	function themeblvd_standard_slider_default( $slider, $settings, $slides ) {
		
		// Configure additional CSS classes
		$classes = themeblvd_get_classes( 'slider_standard', true );
		$settings['nav_standard'] == '1' ? $classes .= ' show-nav_standard' : $classes .= ' hide-nav_standard';
		$settings['nav_arrows'] == '1' ? $classes .= ' show-nav_arrows' : $classes .= ' hide-nav_arrows';
		$settings['pause_play'] == '1' ? $classes .= ' show-pause_play' : $classes .= ' hide-pause_play';
		if( $settings['nav_standard'] == '0' && $settings['nav_arrows'] == '0' )
			$classes .= ' hide-full_nav';
		
		// Hide on mobile?
		$hide = '';
		if( isset( $settings['mobile_fallback'] ) )
			if( $settings['mobile_fallback'] == 'full_list' || $settings['mobile_fallback'] == 'first_slide' )
				$hide = true;
			
		// Start output
		themeblvd_standard_slider_js( $slider, $settings );
		?>
		<div id="tb-slider-<?php echo $slider; ?>" class="slider-wrapper standard-slider-wrapper<?php if($hide) echo ' slider_has_mobile_fallback';?>">
			<div class="slider-inner<?php echo $classes; ?>">	
				<div class="slides-wrapper slides-wrapper-<?php echo $slider; ?>">
					<div class="slides-inner">
						<div class="slider standard-slider flexslider">
							<div class="tb-loader"></div>
							<ul class="slides">
								<?php if( ! empty( $slides ) ) : ?>
									<?php foreach( $slides as $slide ) : ?>
										<?php
										if( ! isset( $slide['custom'] ) ) {
											// Setup CSS classes									
											$classes = 'media-'.$slide['position'].' '.$slide['slide_type'].'-slide';									
											if( $slide['position'] == 'full' && $slide['slide_type'] == 'image' )
												$classes .= ' full-image';
											// Image setup
											if( $slide['slide_type'] == 'image' ) {
												// Image Size
												if( $slide['position'] == 'full' )
													$image_size = 'slider-large';
												else
													$image_size = 'slider-staged';
												// Image URL
												$image_url = null;
												$image_title = null;
												if( isset( $slide['image'][$image_size] ) && $slide['image'][$image_size] )
													$image_url = $slide['image'][$image_size]; // We do a strict check here so no errors will be thrown with old versions of the framework.
												if( isset( $slide['image']['id'] ) ) {
													$attachment = get_post( $slide['image']['id'], OBJECT );
													$image_title = $attachment->post_title;
												}
												if( ! $image_url ) {
													// This should only get used if user updates to v2.1.0 and 
													// didn't re-save their slider. 
													$attachment = wp_get_attachment_image_src( $slide['image']['id'], $image_size );
													$image_url = $attachment[0];
												}
											}
											// Video Setup
											if( $slide['slide_type'] == 'video' && $slide['position'] == 'full' ) {
												$slide['elements']['headline'] = null; // Backup in case user did soemthing funky
												$slide['elements']['description'] = null; // Backup in case user did soemthing funky
												$slide['elements']['button']['url'] = null; // Backup in case user did soemthing funky
											}
											if( $slide['slide_type'] == 'video' ) {	
												// Attributes
												if( $slide['position'] == 'full' )
													$atts = array( 'height' => '350' );
												else
													$atts = array( 'width' => '564' );
												// Get HTML
												$video = wp_oembed_get( $slide['video'], $atts );
												// Set error message
												if( ! $video )
													$video = '<p>'.themeblvd_get_local( 'no_video' ).'</p>';
											}
											// Elements
											$elements = array();
											if( isset( $slide['elements']['include'] ) && is_array( $slide['elements']['include'] ) )
												$elements = $slide['elements']['include'];
											if( $slide['slide_type'] == 'video' && $slide['position'] == 'full' )
												$elements = array(); // Full width video slide can't have elements.
										}
										?>
										<li class="slide tight <?php echo $classes; ?>">
											<div class="slide-body">
												<div class="grid-protection">
													<?php // Custom Slides ?>
													<?php if( isset( $slide['custom'] ) ) : ?>
														<?php echo $slide['custom']; ?>
													<?php // Video and Image Slides ?>
													<?php else : ?>
														<?php if( in_array( 'headline', $elements ) || in_array( 'description', $elements ) || in_array( 'button', $elements ) ) : ?>
															<div class="content<?php if($slide['position'] != 'full') echo ' grid_fifth_2'; ?>">
																<div class="content-inner">	
																	<?php if( in_array( 'headline', $elements ) && $slide['elements']['headline'] ) : ?>
																		<div class="slide-title"><span><?php echo stripslashes( $slide['elements']['headline'] ); ?></span></div>
																	<?php endif; ?>
																	<?php if( in_array( 'description', $elements ) || in_array( 'button', $elements ) ) : ?>
																		<div class="slide-description">
																			<span>
																				<?php if( in_array( 'description', $elements ) ) : ?>
																					<p class="slide-description-text"><?php echo do_shortcode( stripslashes( $slide['elements']['description'] ) ); ?></p>
																				<?php endif; ?>
																				<?php if( in_array( 'button', $elements ) && $slide['elements']['button']['text'] ) : ?>
																					<p class="slide-description-button"><?php echo themeblvd_button( stripslashes( $slide['elements']['button']['text'] ), $slide['elements']['button']['url'], 'default', $slide['elements']['button']['target'], 'medium' ); ?></p>
																				<?php endif; ?>
																			</span>
																		</div><!-- .description (end) -->
																	<?php endif; ?>
																</div><!-- .content-inner (end) -->
															</div><!-- .content (end) -->
														<?php endif; ?>
														<div class="media <?php echo $slide['slide_type']; if($slide['position'] != 'full') echo ' grid_fifth_3'; ?>">
															<div class="media-inner">
																<?php if( $slide['slide_type'] == 'image' ) : ?>
																	<?php if( in_array( 'image_link', $elements ) && $slide['elements']['image_link']['url'] ) : ?>
																		<?php if( $slide['elements']['image_link']['target'] == 'lightbox' ) : ?>
																			<a href="<?php echo $slide['elements']['image_link']['url']; ?>" class="image-link enlarge" rel="themeblvd_lightbox" title=""><span>Image Link</span></a>
																		<?php else : ?>
																			<a href="<?php echo $slide['elements']['image_link']['url']; ?>" target="<?php echo $slide['elements']['image_link']['target']; ?>" class="image-link external"><span>Image Link</span></a>
																		<?php endif; ?>
																	<?php endif; ?>
																	<img src="<?php echo $image_url; ?>" alt="<?php echo $image_title; ?>" />
																<?php else : ?>
																	<?php echo $video; ?>
																<?php endif; ?>
															</div><!-- .media-inner (end) -->
														</div><!-- .media (end) -->
													<?php endif; ?>
												</div><!-- .grid-protection (end) -->
											</div><!-- .slide-body (end) -->
										</li>
									<?php endforeach; ?>
								<?php endif; ?>								
							</ul>
						</div><!-- .slider (end) -->
					</div><!-- .slides-inner (end) -->					
				</div><!-- .slides-wrapper (end) -->
			</div><!-- .slider-inner (end) -->
			<div class="design-1"></div>
			<div class="design-2"></div>
			<div class="design-3"></div>
			<div class="design-4"></div>					
		</div><!-- .slider-wrapper (end) -->
		<?php
		// Display fallback if necessary
		if( isset( $settings['mobile_fallback'] ) )
			if( $settings['mobile_fallback'] == 'full_list' || $settings['mobile_fallback'] == 'first_slide' )
				themeblvd_slider_fallback( $slider, $slides, $settings['mobile_fallback'] );
	}
}

/**
 * Carrousel Slider - default action for themeblvd_carrousel_slider
 *
 * @since 2.0.0
 *
 * @param var $slider ID of current slider
 * @param array $settings Current settings for slider
 * @param array $slides Current slides for slider
 */
 
if( ! function_exists( 'themeblvd_carrousel_slider_default' ) ) {
	function themeblvd_carrousel_slider_default( $slider, $settings, $slides ) {
		themeblvd_carrousel_slider_js( $slider, $settings );
		$classes = themeblvd_get_classes( 'slider_carrousel', true );
		
		// Hide on mobile?
		if( isset( $settings['mobile_fallback'] ) )
			if( $settings['mobile_fallback'] == 'full_list' || $settings['mobile_fallback'] == 'first_slide' ) 
				$classes .= ' slider_has_mobile_fallback';
		?>
		<div id="tb-slider-<?php echo $slider; ?>" class="slider-wrapper carrousel-slider-wrapper<?php echo $classes; ?>">
			<div class="tb-loader"></div>
			<div class="slider-inner">
				<?php if( $settings['nav_arrows'] ) : ?>
				<div class="roundabout-nav">
					<a href="#" title="Previous" class="prev"><i class="icon-circle-arrow-left"></i></a>
					<a href="#" title="Next" class="next"><i class="icon-circle-arrow-right"></i></a>
				</div><!-- .roundabout-nav (end) -->
				<?php endif; ?>
				<ul class="carrousel-slider">
					<?php if( $slides ) : ?>
						<?php foreach( $slides as $slide ) : ?>
							<li class="slide">
								<div class="slide-body">
									<div class="grid-protection">
										<?php
										// Image
										$crop = apply_filters( 'themeblvd_carrousel_image_size', 'grid_4' );
										$image_url = null;
										$image_title = null;
										if( isset( $slide['image'][$crop] ) && $slide['image'][$crop] )
											$image_url = $slide['image'][$crop];
										if( isset( $slide['image']['id'] ) ) {
											$attachment = get_post( $slide['image']['id'], OBJECT );
											$image_title = $attachment->post_title;
										}
										if( ! $image_url ) {
											$attachment = wp_get_attachment_image_src( $slide['image']['id'], $crop );
											$image_url = $attachment[0];
										}
										// Elements
										$elements = array();
										if( isset( $slide['elements']['include'] ) && is_array( $slide['elements']['include'] ) )
											$elements = $slide['elements']['include'];
										?>
										<?php if( in_array( 'image_link', $elements ) ) : ?>
											<?php if( $slide['elements']['image_link']['target'] == 'lightbox' ) : ?>
												<a href="<?php echo $slide['elements']['image_link']['url']; ?>" class="image-link enlarge" rel="themeblvd_lightbox" title=""><span><i class="icon-plus"></i></span></a>
											<?php else : ?>
												<a href="<?php echo $slide['elements']['image_link']['url']; ?>" target="<?php echo $slide['elements']['image_link']['target']; ?>" class="image-link external"><span><i class="icon-external-link"></i></span></a>
											<?php endif; ?>
										<?php endif; ?>
										<img src="<?php echo $image_url; ?>" alt="<?php echo $image_title; ?>" />
									</div><!-- .grid-protection (end) -->
								</div><!-- .slide-body (end) -->
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</div><!-- .slider-inner (end) -->
		</div><!-- .slider-wrapper (end) -->
		<?php
		// Display fallback if necessary
		if( isset( $settings['mobile_fallback'] ) )
			if( $settings['mobile_fallback'] == 'full_list' || $settings['mobile_fallback'] == 'first_slide' )
				themeblvd_slider_fallback( $slider, $slides, $settings['mobile_fallback'] );
	}
}

/**
 * Slidebar mobile fallback
 *
 * @since 2.1.0
 *
 * @param var $slider ID of current slider
 * @param array $slides Current slides for slider
 * @param var $fallback Type of fallback, full_list or first_slide
 */

if( ! function_exists( 'themeblvd_slider_fallback' ) ) {
	function themeblvd_slider_fallback( $slider, $slides, $fallback ) {
		
		// DEBUG
		// echo '<pre>'; print_r($slides); echo '</pre>';

		echo '<div class="slider-fallback">';
		echo '<div class="slider-fallback-inner '.$fallback.'">';
		echo '<ul class="slider-fallback-list">';
		foreach( $slides as $slide ) {
			if( ! isset( $slide['custom'] ) ) {
				// Image Slides
				if( $slide['slide_type'] == 'image' ) {
					// Image URL
					$image_size = '';
					$slide['position'] == 'full' ? $image_size = 'slider-large' : $image_size = 'slider-staged'; // Use crop size to match standard slider display, depending on image position				
					$image_size = apply_filters( 'themeblvd_slider_fallback_img_size', $image_size, $fallback, $slide['position'] ); // Apply optional filter and pass in fallback type & image position
					$image_url = null;
					$image_title = null;
					if( isset( $slide['image'][$image_size] ) && $slide['image'][$image_size] )
						$image_url = $slide['image'][$image_size]; // We do a strict check here so no errors will be thrown with old versions of the framework.
					if( isset( $slide['image']['id'] ) ) {
						$attachment = get_post( $slide['image']['id'], OBJECT );
						$image_title = $attachment->post_title;
					}
					if( ! $image_url ) {
						// This should only get used if user updates to v2.1.0 and 
						// didn't re-save their slider. 
						$attachment = wp_get_attachment_image_src( $slide['image']['id'], $image_size );
						$image_url = $attachment[0];
					}
				}
				// Video Slides
				if( $slide['slide_type'] == 'video' ) {	
					// Get HTML
					$video = wp_oembed_get( $slide['video'] );
					// Set error message
					if( ! $video )
						$video = '<p>'.themeblvd_get_local( 'no_video' ).'</p>';
				}
				// Elements
				$elements = array();
				if( isset( $slide['elements']['include'] ) && is_array( $slide['elements']['include'] ) )
				$elements = $slide['elements']['include'];
			}
			echo '<li class="slider-fallback-slide">';
			echo '<div class="slider-fallback-slide-body">';
				if( isset( $slide['custom'] ) ) {
					// Custom Slide
					echo $slide['custom'];
				} else {
					// Slide Headline
					if( in_array( 'headline', $elements ) && isset( $slide['elements']['headline'] ) && $slide['elements']['headline'] )
						echo '<h2>'.stripslashes($slide['elements']['headline']).'</h2>';
					// Image Slides
					if( $slide['slide_type'] == 'image' ) {
						if( in_array( 'image_link', $elements ) ) {
							if( $slide['elements']['image_link']['target'] == 'lightbox' )
								echo '<a href="'.$slide['elements']['image_link']['url'].'" class="image-link enlarge" rel="themeblvd_lightbox">';
							else
								echo '<a href="'.$slide['elements']['image_link']['url'].'" target="'.$slide['elements']['image_link']['target'].'" class="image-link external">';
						}
						echo '<img src="'.$image_url.'" alt="'.$image_title.'" />';	
						if( in_array( 'image_link', $elements ) )
							echo '</a>';
					}
					// Video Slides
					if( $slide['slide_type'] == 'video' )
						echo $video;
					// Description
					if( in_array( 'description', $elements ) && isset( $slide['elements']['description'] ) && $slide['elements']['description'] )
						echo '<p class="slide-description-text">'.do_shortcode(stripslashes($slide['elements']['description'])).'</p>';
					// Button
					if( in_array( 'button', $elements ) && isset( $slide['elements']['button']['text'] ) && $slide['elements']['button']['text'] )
						echo '<p class="slide-description-button">'.themeblvd_button( stripslashes( $slide['elements']['button']['text'] ), $slide['elements']['button']['url'], 'default', $slide['elements']['button']['target'], 'medium' ).'</p>';
				}
			echo '</div><!-- .slider-fallback-slide-body (end) -->';
			echo '</li>';
			
			// End the loop after first slide if we're only showing the first slide.
			if( $fallback == 'first_slide' )
				break;
		}
		echo '</ul>';
		echo '</div><!-- .slider-fallback-inner (end) -->';
		echo '</div><!-- .slider-fallback(end) -->';
	}

}