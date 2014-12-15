<?php
/**
 * Add option section for theme customizer
 * added in WP 3.4
 *
 * @since 2.1.0
 *
 * @param string $section_id ID for section
 * @param string $section_name Name for section
 * @param array $options Options to register for WP theme customizer
 * @param int $priority Priority for section
 */
function themeblvd_add_customizer_section( $section_id, $section_name, $options, $priority = null ) {

	global $_themeblvd_customizer_sections;

	$_themeblvd_customizer_sections[$section_id] = array(
		'id' 		=> $section_id,
		'name' 		=> $section_name,
		'options' 	=> $options,
		'priority'	=> $priority
	);

}

/**
 * Format options for customizer into array organized
 * with all sections together.
 *
 * This is needed because WordPress's customizer groups
 * all options together when saving and doesn't take
 * sections into account. So, we need to organize all
 * options into an array that we can check against when
 * saving our options in our custom action added to the
 * WordPress customizer saving process.
 *
 * The structure of the array returned with this function
 * is formatted in a specific way to work with the function
 * "themeblvd_save_customizer"
 *
 * @since 2.1.0
 *
 * @param array $options Options to format
 */
function themeblvd_registered_customizer_options( $sections ) {
	$registered_options = array();
	if ( $sections ) {
		foreach ( $sections as $section ) {
			if ( $section['options'] ) {
				foreach ( $section['options'] as $option ) {
					$registered_options[$option['id']] = $option;
				}
			}
		}
	}
	return $registered_options;
}

/**
 * Setup everything we need for WordPress customizer.
 *
 * All custom controls used in this function can be
 * found in the following file:
 *
 * /framework/admin/classes/customizer.php
 *
 * @since 2.1.0
 */
function themeblvd_customizer_init( $wp_customize ) {

	global $_themeblvd_customizer_sections;

	// Get current theme settings.
	$options_api = Theme_Blvd_Options_API::get_instance();
	$option_name = $options_api->get_option_id();
	$theme_settings = $options_api->get_setting();

	// Register sections of options
	if ( $_themeblvd_customizer_sections ) {
		foreach ( $_themeblvd_customizer_sections as $section ) {

			// Add section
			$wp_customize->add_section( $section['id'], array(
				'title'    => $section['name'],
				'priority' => $section['priority'],
			) );

			$font_counter = 1;

			// Add Options
			if ( $section['options'] ) {
				foreach ( $section['options'] as $option ) {

					if ( $option['type'] == 'typography' ) {

						// TYPOGRAPHY

						// Setup defaults
						$defaults = array(
							'size' 		=> '',
							'style'		=> '',
							'face' 		=> '',
							'style' 	=> '',
							'color' 	=> '',
							'google' 	=> ''
						);
						if ( isset( $theme_settings[$option['id']] ) ) {
							$defaults = $theme_settings[$option['id']];
						}

						// Transport
						$transport = '';
						if ( isset( $option['transport'] ) ) {
							$transport = $option['transport'];
						}

						// Loop through included attributes
						foreach ( $option['atts'] as $attribute ) {

							// Register options
							$wp_customize->add_setting( $option_name.'['.$option['id'].']['.$attribute.']', array(
								'default'    		=> esc_attr( $defaults[$attribute] ),
								'type'       		=> 'option',
								'capability' 		=> themeblvd_admin_module_cap( 'options' ),
								'transport'			=> $transport,
								'sanitize_callback'	=> 'themeblvd_sanitize_typography'
							) );

							switch ( $attribute ) {

								case 'size' :
									$size_options = array();
									for($i = 9; $i < 71; $i++) {
										$size = $i . 'px';
										$size_options[$size] = $size;
									}
									$wp_customize->add_control( $option['id'].'_'.$attribute, array(
										'priority'		=> $font_counter,
										'settings'		=> $option_name.'['.$option['id'].']['.$attribute.']',
										'label'   		=> $option['name'].' '.ucfirst($attribute),
										'section'    	=> $section['id'],
										'type'       	=> 'select',
										'choices'    	=> $size_options
									) );
									$font_counter++;
									break;

								case 'face' :
									$wp_customize->add_control( new WP_Customize_ThemeBlvd_Font_Face( $wp_customize, $option['id'].'_'.$attribute, array(
										'priority'		=> $font_counter,
										'settings'		=> $option_name.'['.$option['id'].']['.$attribute.']',
										'label'   		=> $option['name'].' '.ucfirst($attribute),
										'section' 		=> $section['id'],
										'choices'    	=> themeblvd_recognized_font_faces()
									) ) );
									$font_counter++;
									$wp_customize->add_setting( $option_name.'['.$option['id'].'][google]', array(
										'default'    		=> esc_attr( $defaults['google'] ),
										'type'       		=> 'option',
										'capability' 		=> themeblvd_admin_module_cap( 'options' ),
										'transport'			=> $transport,
										'sanitize_callback'	=> 'themeblvd_sanitize_text'
									) );
									$wp_customize->add_control( new WP_Customize_ThemeBlvd_Google_Font( $wp_customize, $option['id'].'_'.$attribute.'_google', array(
										'priority'		=> $font_counter,
										'settings'		=> $option_name.'['.$option['id'].'][google]',
										'label'   		=> __( 'Google Font Name', 'themeblvd' ),
										'section' 		=> $section['id']
									) ) );
									$font_counter++;
									break;

								case 'style' :
									$wp_customize->add_control( $option['id'].'_'.$attribute, array(
										'priority'		=> $font_counter,
										'settings'		=> $option_name.'['.$option['id'].']['.$attribute.']',
										'label'   		=> $option['name'].' '.ucfirst($attribute),
										'section'    	=> $section['id'],
										'type'       	=> 'select',
										'choices'    	=> themeblvd_recognized_font_styles()
									) );
									$font_counter++;
									break;

								case 'color' :
									$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $option['id'].'_'.$attribute, array(
										'priority'		=> $font_counter,
										'settings'		=> $option_name.'['.$option['id'].']['.$attribute.']',
										'label'   		=> $option['name'].' '.ucfirst($attribute),
										'section' 		=> $section['id']
									) ) );
									$font_counter++;
									break;
							}

							// Divider line below each font
							$wp_customize->add_setting( $option_name.'['.$option['id'].'][divider]', array(
								'type'       		=> 'option',
								'capability' 		=> themeblvd_admin_module_cap( 'options' ),
								'transport'			=> $transport,
								'sanitize_callback'	=> 'themeblvd_sanitize_text' // no data actually passed
							) );
							$wp_customize->add_control( new WP_Customize_ThemeBlvd_Divider( $wp_customize, $option['id'].'_divider', array(
								'priority'		=> $font_counter,
								'settings'		=> $option_name.'['.$option['id'].'][divider]',
								'section'		=> $section['id']
							) ) );
							$font_counter++;

						}

					} else {

						// ALL OTHER OPTIONS

						// Default
						$default = '';

						if ( isset( $theme_settings[$option['id']] ) ) {
							$default = $theme_settings[$option['id']];
						} else if ( isset( $option['std'] ) ) {
							$default = $option['std'];
						}

						// CSS classes
						$class = '';

						if ( isset( $option['class'] ) ) {
							$class = $option['class'];
						}

						// Transport
						$transport = '';

						if ( isset( $option['transport'] ) ) {
							$transport = $option['transport'];
						}

						$priority = '';

						if ( isset( $option['priority'] ) ) {
							$priority = $option['priority'];
						}

						// Register option
						$wp_customize->add_setting( $option_name.'['.$option['id'].']', array(
							'default'    		=> esc_attr( $default ),
							'type'       		=> 'option',
							'capability' 		=> themeblvd_admin_module_cap( 'options' ),
							'transport'			=> $transport,
							'sanitize_callback'	=> 'themeblvd_sanitize_'.$option['type']
						) );

						// Add controls
						switch ( $option['type'] ) {

							// Standard text option
							case 'text' :
								$wp_customize->add_control( $option['id'], array(
									'priority'		=> $priority,
									'settings'		=> $option_name.'['.$option['id'].']',
									'label'			=> $option['name'],
									'section'		=> $section['id']
								) );
								break;

							// Textarea
							case 'textarea' :
								$wp_customize->add_control( new WP_Customize_ThemeBlvd_Textarea( $wp_customize, $option['id'], array(
									'priority'		=> $priority,
									'settings'		=> $option_name.'['.$option['id'].']',
									'label'   		=> $option['name'],
									'section' 		=> $section['id']
								) ) );
								break;

							// Select box
							case 'select' :

								if ( ! $default ) {
									$default = $option['std']; // select option can't be empty
								}

								$choices = array();

								if ( isset( $option['select'] ) ) {

									switch ( $option['select'] ) {
										case 'textures' :
											$textures = themeblvd_get_select('textures');

											foreach ( $textures as $group ) {
												foreach ( $group['options'] as $key => $val ) {
													$choices[$key] = $val;
												}
											}

										// @TODO Maybe add more later
									}

								} else if (isset($option['options']) ) {

									$choices = $option['options'];

								}

								$wp_customize->add_control( new WP_Customize_ThemeBlvd_Select( $wp_customize, $option['id'], array(
									'priority'		=> $priority,
									'settings'		=> $option_name.'['.$option['id'].']',
									'label'   		=> $option['name'],
									'section' 		=> $section['id'],
									'type'			=> 'select',
									'default'		=> $default,
									'choices'		=> $choices,
									'class'			=> $class
								) ) );
								break;

							// Radio set
							case 'radio' :
								$wp_customize->add_control( $option['id'], array(
									'priority'		=> $priority,
									'settings'		=> $option_name.'['.$option['id'].']',
									'label'			=> $option['name'],
									'section'		=> $section['id'],
									'type'			=> 'radio',
									'choices'		=> $option['options']
								) );
								break;

							// Color
							case 'color' :
								$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $option['id'], array(
									'priority'		=> $priority,
									'settings'		=> $option_name.'['.$option['id'].']',
									'label'			=> $option['name'],
									'section'		=> $section['id']
								) ) );
								break;

							// Start inner section
							case 'subgroup_start' :
								/* // ... @TODO
								$wp_customize->add_setting( $option_name.'['.$option['id'].']', array(
									'type'       		=> 'option',
									'capability' 		=> themeblvd_admin_module_cap( 'options' ),
									'sanitize_callback'	=> 'themeblvd_sanitize_text' // no data actually passed
								) );
								$wp_customize->add_control( new WP_Customize_ThemeBlvd_Subgroup_Start( $wp_customize, $option['id'], array(
									'priority'		=> $priority,
									'settings'		=> $option_name.'['.$option['id'].']',
									'section'		=> $section['id'],
									'class'			=> $class
								) ) );
								*/
								break;

							// Start inner section
							case 'subgroup_end' :
								/* // ... @TODO
								$wp_customize->add_setting( $option_name.'['.$option['id'].']', array(
									'type'       		=> 'option',
									'capability' 		=> themeblvd_admin_module_cap( 'options' ),
									'sanitize_callback'	=> 'themeblvd_sanitize_text' // no data actually passed
								) );
								$wp_customize->add_control( new WP_Customize_ThemeBlvd_Subgroup_End( $wp_customize, $option['id'], array(
									'priority'		=> $priority,
									'settings'		=> $option_name.'['.$option['id'].']',
									'section'		=> $section['id']
								) ) );
								*/

						}

					}
				}
			}
		}
	}

	// Remove irrelevant sections
	$remove_sections = apply_filters( 'themeblvd_customizer_remove_sections', array( 'title_tagline' ) );

	if ( is_array( $remove_sections ) && $remove_sections ) {
		foreach ( $remove_sections as $section ) {
			$wp_customize->remove_section( $section );
		}
	}
}

/**
 * Styles for WordPress cusomizer.
 *
 * @since 2.1.0
 */
function themeblvd_customizer_styles() {
	wp_register_style( 'themeblvd_customizer', get_template_directory_uri().'/framework/admin/assets/css/customizer.min.css', false, TB_FRAMEWORK_VERSION );
	wp_enqueue_style( 'themeblvd_customizer' );
}

/**
 * Scripts for WordPress cusomizer.
 *
 * @since 2.1.0
 */
function themeblvd_customizer_scripts() {
	wp_register_script( 'themeblvd_customizer', get_template_directory_uri().'/framework/admin/assets/js/customizer.min.js', array('jquery'), TB_FRAMEWORK_VERSION );
    wp_enqueue_script( 'themeblvd_customizer' );
    wp_localize_script( 'themeblvd_customizer', 'themeblvd', themeblvd_get_admin_locals( 'customizer_js' ) );
}

/**
 * Customizer control extensions.
 */
if ( class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * Add control for textarea.
	 */
	class WP_Customize_ThemeBlvd_Textarea extends WP_Customize_Control {

		public $type = 'textarea';
		public $statuses;

		public function __construct( $manager, $id, $args = array() ) {
			$this->statuses = array( '' => __('Default', 'themeblvd' ) );
			parent::__construct( $manager, $id, $args );
		}

		public function to_json() {
			parent::to_json();
			$this->json['statuses'] = $this->statuses;
		}

		protected function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<textarea <?php $this->link(); ?>><?php echo esc_attr( $this->value() ); ?></textarea>
			</label>
			<?php
		}

	}

	/**
	 * Add control for select.
	 */
	class WP_Customize_ThemeBlvd_Select extends WP_Customize_Control {

		public $type = 'select';
		public $default = '';
		public $class = '';
		public $statuses;

		public function __construct( $manager, $id, $args = array() ) {

			if ( isset( $args['default'] ) ) {
				$this->default = $args['default'];
			}

			if ( isset( $args['class'] ) ) {
				$this->class = $args['class'];
			}

			$this->statuses = array( '' => __('Default', 'themeblvd' ) );

			parent::__construct( $manager, $id, $args );
		}

		public function to_json() {
			parent::to_json();
			$this->json['statuses'] = $this->statuses;
		}

		protected function render() {
			$id    = 'customize-control-' . str_replace( '[', '-', str_replace( ']', '', $this->id ) );
			$class = 'customize-control customize-control-' . $this->type . ' ' . $this->class;

			?><li id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>">
				<?php $this->render_content(); ?>
			</li><?php
		}

		protected function render_content() {

			if ( empty( $this->choices ) ) {
				return;
			}

			$current = $this->value();

			if ( ! $current ) {
				$current = $this->default;
			}
			?>
			<label>
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif;
				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo $this->description; ?></span>
				<?php endif; ?>

				<select <?php $this->link(); ?>>
					<?php
					foreach ( $this->choices as $value => $label )
						echo '<option value="' . esc_attr( $value ) . '"' . selected( $current, $value, false ) . '>' . $label . '</option>';
					?>
				</select>
			</label>
			<?php
		}

	}

	/**
	 * Add control to select font face.
	 *
	 * This is very similar to default select option but with our
	 * added class that allows us to use it as reference for the
	 * Google font input.
	 */
	class WP_Customize_ThemeBlvd_Font_Face extends WP_Customize_Control {

		public $type = 'font_face';
		public $statuses;

		public function __construct( $manager, $id, $args = array() ) {
			$this->statuses = array( '' => __( 'Default', 'themeblvd' ) );
			parent::__construct( $manager, $id, $args );
		}

		public function enqueue() {
			wp_enqueue_script( 'themeblvd_customizer' );
			wp_enqueue_style( 'themeblvd_customizer' );
		}

		public function to_json() {
			parent::to_json();
			$this->json['statuses'] = $this->statuses;
		}

		protected function render_content() {
			if ( empty( $this->choices ) ) {
				return;
			}
			?>
			<label class="themeblvd-font-face">
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<select <?php $this->link(); ?>>
					<?php
					foreach ( $this->choices as $value => $label ) {
						printf( '<option value="%s"%s>%s</option>', esc_attr($value), selected( $this->value(), $value, false ), $label );
					}
					?>
				</select>
			</label>
			<?php
		}

	}

	/**
	 * Add control to input Google font name.
	 */
	class WP_Customize_ThemeBlvd_Google_Font extends WP_Customize_Control {

		public $type = 'google_font';
		public $statuses;

		public function __construct( $manager, $id, $args = array() ) {
			$this->statuses = array( '' => __('Default', 'themeblvd' ) );
			parent::__construct( $manager, $id, $args );
		}

		public function enqueue() {
			wp_enqueue_script( 'themeblvd_customizer' );
			wp_enqueue_style( 'themeblvd_customizer' );
		}

		public function to_json() {
			parent::to_json();
			$this->json['statuses'] = $this->statuses;
		}

		protected function render_content() {
			?>
			<label class="themeblvd-google-font">
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
				<p><?php _e( 'Example', 'themeblvd' ); ?>: Pontano Sans</p>
				<p><a href="http://www.google.com/webfonts" target="_blank"><?php _e( 'Browse Google Fonts', 'themeblvd' ); ?></a></p>
			</label>
			<?php
		}

	}

	/**
	 * Add control for divider.
	 */
	class WP_Customize_ThemeBlvd_Divider extends WP_Customize_Control {

		public $type = 'divider';
		public $statuses;

		public function __construct( $manager, $id, $args = array() ) {
			$this->statuses = array( '' => __('Default', 'themeblvd' ) );
			parent::__construct( $manager, $id, $args );
		}

		protected function render_content() {
			?>
			<div class="themeblvd-divider"></div>
			<?php
		}

	}

	/**
	 * Start section (for javascript show/hide options)
	 * // ... @TODO
	 */
	class WP_Customize_ThemeBlvd_Subgroup_Start extends WP_Customize_Control {

		public $type = 'subgroup_start';
		public $class = '';
		public $statuses;

		public function __construct( $manager, $id, $args = array() ) {

			if ( isset( $args['class'] ) ) {
				$this->class = $args['class'];
			}

			$this->statuses = array( '' => __('Default', 'themeblvd' ) );

			parent::__construct( $manager, $id, $args );
		}

		protected function render_content() {
			?>
			<div class="<?php echo $this->class; ?>">
				<ul>
			<?php
		}

	}

	/**
	 * End section (for javascript show/hide options)
	 * // ... @TODO
	 */
	class WP_Customize_ThemeBlvd_Subgroup_End extends WP_Customize_Control {

		public $type = 'subgroup_end';
		public $statuses;

		public function __construct( $manager, $id, $args = array() ) {
			$this->statuses = array( '' => __('Default', 'themeblvd' ) );
			parent::__construct( $manager, $id, $args );
		}

		protected function render_content() {
			?>
				</ul>
			</div>
			<?php
		}

	}

} // End if class_exists('WP_Customize_Control')

/**
 * Font Prep for customizer preview
 *
 * Since the Javascript for fonts will get repeated in
 * many themes, its being placed here so it can be easily
 * placed in each theme that requires it.
 *
 * This function sets up some objects we can use throughout
 * all of our font options.
 *
 * @since 2.1.0
 */
function themeblvd_customizer_preview_font_prep() {

	// Global option name
	$option_name = themeblvd_get_option_name();

	// Setup font stacks
	$font_stacks = themeblvd_font_stacks();
	unset( $font_stacks['google'] );

	// Determine current google fonts with fake
	// booleans to be used in printed JS object.
	$types = array('body', 'header', 'special');
	$google_fonts = array();
	foreach ( $types as $type ) {
		$font = themeblvd_get_option('typography_'.$type);
		$google_fonts[$type.'Name'] = !empty($font['google']) && $font['google'] ? $font['google'] : '';
		$google_fonts[$type.'Toggle'] = !empty($font['face']) && $font['face'] == 'google' ? 'true' : 'false';
	}
	?>
	// Font stacks
	fontStacks = <?php echo json_encode($font_stacks); ?>;

	// Google font toggles
	googleFonts = <?php echo json_encode($google_fonts); ?>;
	<?php
}

/**
 * Primary (Body) Font Customizer Preview
 *
 * Since the Javascript for the fonts will get repeated in
 * many themes, its being placed here so it can be easily
 * placed in each theme that requires it.
 *
 * @since 2.1.0
 */
function themeblvd_customizer_preview_primary_font() {

	// Global option name
	$option_name = themeblvd_get_option_name();

	// Begin output
	?>
	// ---------------------------------------------------------
	// Body Typography
	// ---------------------------------------------------------

	/* Body Typography - Size */
	wp.customize('<?php echo $option_name; ?>[typography_body][size]',function( value ) {
		value.bind(function(size) {
			// We're doing this odd-ball way so jQuery
			// doesn't apply body font to other elements.
			$('.preview_body_font_size').remove();
			$('head').append('<style class="preview_body_font_size">body{ font-size: '+size+'; }</style>');
		});
	});

	/* Body Typography - Style */
	wp.customize('<?php echo $option_name; ?>[typography_body][style]',function( value ) {
		value.bind(function(style) {

			// We're doing this odd-ball way so jQuery
			// doesn't apply body font to other elements.
			$('.preview_body_font_style').remove();

			// Possible choices: normal, bold, italic, bold-italic
			var body_css_props;
			if ( style == 'normal' )
				body_css_props = 'font-weight: normal; font-style: normal;';
			else if ( style == 'bold' )
				body_css_props = 'font-weight: bold; font-style: normal;';
			else if ( style == 'italic' )
				body_css_props = 'font-weight: normal; font-style: italic;';
			else if ( style == 'bold-italic' )
				body_css_props = 'font-weight: bold; font-style: italic;';

			$('head').append('<style class="preview_body_font_style">body{'+body_css_props+'}</style>');

		});
	});

	/* Body Typography - Face */
	wp.customize('<?php echo $option_name; ?>[typography_body][face]',function( value ) {
		value.bind(function(face) {
			var header_font_face = $('h1, h2, h3, h4, h5, h6').css('font-family');
			if ( face == 'google' ) {
				googleFonts.bodyToggle = true;
				var google_font = googleFonts.bodyName.split(":"),
					google_font = google_font[0];
				$('body').css('font-family', google_font);
				$('h1, h2, h3, h4, h5, h6').css('font-family', header_font_face); // Maintain header font when body font switches
			}
			else
			{
				googleFonts.bodyToggle = false;
				$('body').css('font-family', fontStacks[face]);
				$('h1, h2, h3, h4, h5, h6').css('font-family', header_font_face); // Maintain header font when body font switches
			}
		});
	});

	/* Body Typography - Google */
	wp.customize('<?php echo $option_name; ?>[typography_body][google]',function( value ) {
		value.bind(function(google_font) {
			// Only proceed if user has actually selected for
			// a google font to show in previous option.
			if (googleFonts.bodyToggle)
			{
				// Set global google font for reference in
				// other options.
				googleFonts.bodyName = google_font;

				// Determine current header font so we don't
				// override it with our new body font.
				var header_font_face = $('h1, h2, h3, h4, h5, h6').css('font-family');

				// Remove previous google font to avoid clutter.
				$('.preview_google_body_font').remove();

				// Format font name for inclusion
				var include_google_font = google_font.replace(/ /g,'+');

				// Include font
				$('head').append('<link href="http://fonts.googleapis.com/css?family='+include_google_font+'" rel="stylesheet" type="text/css" class="preview_google_body_font" />');

				// Format for CSS
				google_font = google_font.split(":");
				google_font = google_font[0];

				// Apply font in CSS
				$('body').css('font-family', google_font);
				$('h1, h2, h3, h4, h5, h6').css('font-family', header_font_face); // Maintain header font when body font switches
			}
		});
	});
	<?php
}

/**
 * Header (h1, h2, h3, h4, h5) Font Customizer Preview
 *
 * Since the Javascript for the fonts will get repeated in
 * many themes, its being placed here so it can be easily
 * placed in each theme that requires it.
 *
 * @since 2.1.0
 */
function themeblvd_customizer_preview_header_font() {

	// Global option name
	$option_name = themeblvd_get_option_name();

	// Begin Output
	?>
	// ---------------------------------------------------------
	// Header Typography
	// ---------------------------------------------------------

	/* Header Typography - Style */
	wp.customize('<?php echo $option_name; ?>[typography_header][style]',function( value ) {
		value.bind(function(style) {
			// Possible choices: normal, bold, italic, bold-italic
			if ( style == 'normal' ) {
				$('h1, h2, h3, h4, h5, h6').css('font-weight', 'normal');
				$('h1, h2, h3, h4, h5, h6').css('font-style', 'normal');
			} else if ( style == 'bold' ) {
				$('h1, h2, h3, h4, h5, h6').css('font-weight', 'bold');
				$('h1, h2, h3, h4, h5, h6').css('font-style', 'normal');
			} else if ( style == 'italic' ) {
				$('h1, h2, h3, h4, h5, h6').css('font-weight', 'normal');
				$('h1, h2, h3, h4, h5, h6').css('font-style', 'italic');
			} else if ( style == 'bold-italic' ) {
				$('h1, h2, h3, h4, h5, h6').css('font-weight', 'bold');
				$('h1, h2, h3, h4, h5, h6').css('font-style', 'italic');
			}
		});
	});

	/* Header Typography - Face */
	wp.customize('<?php echo $option_name; ?>[typography_header][face]',function( value ) {
		value.bind(function(face) {
			if ( face == 'google' ) {
				googleFonts.headerToggle = true;
				var google_font = googleFonts.headerName.split(":"),
					google_font = google_font[0];
				$('h1, h2, h3, h4, h5, h6').css('font-family', google_font);
			}
			else
			{
				googleFonts.headerToggle = false;
				$('h1, h2, h3, h4, h5, h6').css('font-family', fontStacks[face]);
			}
		});
	});

	/* Header Typography - Google */
	wp.customize('<?php echo $option_name; ?>[typography_header][google]',function( value ) {
		value.bind(function(google_font) {
			// Only proceed if user has actually selected for
			// a google font to show in previous option.
			if (googleFonts.headerToggle)
			{
				// Set global google font for reference in
				// other options.
				googleFonts.headerName = google_font;

				// Remove previous google font to avoid clutter.
				$('.preview_google_header_font').remove();

				// Format font name for inclusion
				var include_google_font = google_font.replace(/ /g,'+');

				// Include font
				$('head').append('<link href="http://fonts.googleapis.com/css?family='+include_google_font+'" rel="stylesheet" type="text/css" class="preview_google_header_font" />');

				// Format for CSS
				google_font = google_font.split(":");
				google_font = google_font[0];

				// Apply font in CSS
				$('h1, h2, h3, h4, h5, h6').css('font-family', google_font);
			}
		});
	});
	<?php
}

/**
 * Custom CSS Customizer Preview
 *
 * Since the Javascript for the fonts will get repeated in
 * many themes, its being placed here so it can be easily
 * placed in each theme that requires it.
 *
 * @since 2.1.0
 */
function themeblvd_customizer_preview_styles() {

	// Global option name
	$option_name = themeblvd_get_option_name();

	// Output
	?>
	/* Custom CSS */
	wp.customize('<?php echo $option_name; ?>[custom_styles]',function( value ) {
		value.bind(function(css) {
			$('.preview_custom_styles').remove();
			$('head').append('<style class="preview_custom_styles">'+css+'</style>');
		});
	});
	<?php
}

/**
 * Allow "refresh" transport type settings to
 * work right in the Customizer.
 *
 * Note: Hooked to "wp_loaded".
 *
 * @since 2.3.0
 */
function themeblvd_customizer_preview() {

	global $wp_customize;

	// Check if customizer is running.
	if ( ! is_a( $wp_customize, 'WP_Customize_Manager' ) ) {
		return;
	}

	// Reset themeblvd settings after Customizer
	// has applied filters.
	if ( $wp_customize->is_preview() ) {
		$api = Theme_Blvd_Options_API::get_instance();
		$api->set_settings();
	}

}