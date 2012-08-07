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
 
if( ! function_exists( 'themeblvd_add_customizer_section' ) ) {
	function themeblvd_add_customizer_section( $section_id, $section_name, $options, $priority = null ){
		global $_themeblvd_customizer_sections;
		$_themeblvd_customizer_sections[$section_id] = array(
			'id' 		=> $section_id,
			'name' 		=> $section_name,
			'options' 	=> $options,
			'priority'	=> $priority
		);
	}
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
 
if( ! function_exists( 'themeblvd_registered_customizer_options' ) ) {
	function themeblvd_registered_customizer_options( $sections ){
		$registered_options = array();
		if( $sections ){
			foreach( $sections as $section ){
				if( $section['options'] ){
					foreach( $section['options'] as $option ){
						$registered_options[$option['id']] = $option;
					}
				}
			}
		}
		return $registered_options;
	}
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

if( ! function_exists( 'themeblvd_customizer_init' ) ) {
	function themeblvd_customizer_init( $wp_customize ){

		global $_themeblvd_customizer_sections;
	
		// Determine options name
		$option_name = themeblvd_get_option_name();
	
		// Alter current theme options and only modify our select 
		// options controled with the customizer.
		$theme_options = get_option( $option_name );
		
		// If theme options don't exist, yet set defaults for customizer.
		$theme_option_defaults = of_get_default_values();
		
		// Register sections of options
		if( $_themeblvd_customizer_sections ) {
			foreach( $_themeblvd_customizer_sections as $section ){
				
				// Add section
				$wp_customize->add_section( $section['id'], array(
					'title'    => $section['name'],
					'priority' => $section['priority'],
				) );
				
				$font_counter = 1;
				
				// Add Options
				if( $section['options'] ){
					foreach( $section['options'] as $option ) {
						
						if( $option['type'] == 'logo' ){
							
							// LOGO
							
							// Setup defaults
							$defaults = array(
								'type' 				=> '',
								'custom' 			=> '',
								'custom_tagline' 	=> '',
								'image' 			=> ''
							);
							if( isset( $theme_options[$option['id']] ) ){
								foreach( $defaults as $key => $value ){
									if( isset( $theme_options[$option['id']][$key] ) ){
										$defaults[$key] = $theme_options[$option['id']][$key];
									}
								}
							} else if( isset( $theme_option_defaults[$option['id']] ) ){
								foreach( $defaults as $key => $value ){
									if( isset( $theme_option_defaults[$option['id']][$key] ) ){
										$defaults[$key] = $theme_option_defaults[$option['id']][$key];
									}
								}
							}
							
							// Transport
							$transport = '';
							if( isset( $option['transport'] ) )
								$transport = $option['transport'];
							
							// Logo Type
							$wp_customize->add_setting( $option_name.'['.$option['id'].'][type]', array(
								'default'    	=> esc_attr( $defaults['type'] ),
								'type'       	=> 'option',
								'capability' 	=> themeblvd_admin_module_cap( 'options' ),
								'transport'		=> $transport
							) );
							$wp_customize->add_control( $option['id'].'_type', array(
								'priority'		=> 1,
								'settings'		=> $option_name.'['.$option['id'].'][type]',
								'label'   		=> $option['name'].' '.__( 'Type', TB_GETTEXT_DOMAIN ),
								'section'    	=> $section['id'],
								'type'       	=> 'select',
								'choices'    	=> array(
									'title' 		=> __( 'Site Title', TB_GETTEXT_DOMAIN ),
									'title_tagline'	=> __( 'Site Title + Tagline', TB_GETTEXT_DOMAIN ),
									'custom' 		=> __( 'Custom Text', TB_GETTEXT_DOMAIN ),
									'image' 		=> __( 'Image', TB_GETTEXT_DOMAIN )
								)
							) );
							
							// Custom Title
							$wp_customize->add_setting( $option_name.'['.$option['id'].'][custom]', array(
								'default'    	=> esc_attr( $defaults['custom'] ),
								'type'       	=> 'option',
								'capability' 	=> themeblvd_admin_module_cap( 'options' ),
								'transport'		=> $transport
							) );
							$wp_customize->add_control( $option['id'].'_custom', array(
								'priority'		=> 2,
								'settings'		=> $option_name.'['.$option['id'].'][custom]',
								'label'      	=> __( 'Custom Title', TB_GETTEXT_DOMAIN ),
								'section'    	=> $section['id']
							) );
							
							// Custom Tagline
							$wp_customize->add_setting( $option_name.'['.$option['id'].'][custom_tagline]', array(
								'default'    	=> esc_attr( $defaults['custom_tagline'] ),
								'type'       	=> 'option',
								'capability' 	=> themeblvd_admin_module_cap( 'options' ),
								'transport'		=> $transport
							) );
							$wp_customize->add_control( $option['id'].'_custom_tagline', array(
								'priority'		=> 3,
								'settings'		=> $option_name.'['.$option['id'].'][custom_tagline]',
								'label'      	=> __( 'Custom Tagline', TB_GETTEXT_DOMAIN ),
								'section'    	=> $section['id']
							) );
							
							// Logo Image
							$wp_customize->add_setting( $option_name.'['.$option['id'].'][image]', array(
								'default'    	=> esc_attr( $defaults['image'] ),
								'type'       	=> 'option',
								'capability' 	=> themeblvd_admin_module_cap( 'options' ),
								'transport'		=> $transport
							) );
							$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $option['id'].'_image', array(
								'priority'		=> 4,
								'settings'		=> $option_name.'['.$option['id'].'][image]',
								'label'   => $option['name'].' '.__( 'Image', TB_GETTEXT_DOMAIN ),
								'section' => $section['id'],
							) ) );
							
						} else if( $option['type'] == 'typography' ){
							
							// TYPOGRAPHY
							
							// Setup defaults
							$defaults = array(
								'size' 		=> '',
								'face' 		=> '',
								'style' 	=> '',
								'color' 	=> '',
								'google' 	=> ''
							);
							if( isset( $theme_options[$option['id']] ) )
								$defaults = $theme_options[$option['id']];
							
							// Transport
							$transport = '';
							if( isset( $option['transport'] ) )
								$transport = $option['transport'];
							
							// Loop through included attributes
							foreach( $option['atts'] as $attribute ){
								
								// Register options
								$wp_customize->add_setting( $option_name.'['.$option['id'].']['.$attribute.']', array(
									'default'    	=> esc_attr( $defaults[$attribute] ),
									'type'       	=> 'option',
									'capability' 	=> themeblvd_admin_module_cap( 'options' ),
									'transport'		=> $transport
								) );
	
								switch( $attribute ){
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
											'choices'    	=> of_recognized_font_faces()
										) ) );
										$font_counter++;
										$wp_customize->add_setting( $option_name.'['.$option['id'].'][google]', array(
											'default'    	=> esc_attr( $defaults['google'] ),
											'type'       	=> 'option',
											'capability' 	=> themeblvd_admin_module_cap( 'options' ),
											'transport'		=> $transport
										) );
										$wp_customize->add_control( new WP_Customize_ThemeBlvd_Google_Font( $wp_customize, $option['id'].'_'.$attribute.'_google', array(
											'priority'		=> $font_counter,
											'settings'		=> $option_name.'['.$option['id'].'][google]',
											'label'   		=> __( 'Google Font Name', TB_GETTEXT_DOMAIN ),
											'section' 		=> $section['id']
										) ) );
										$font_counter++;
										break;
									case 'style' :
										// Currently not supported in Theme Blvd options 
										// $counter++;
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
									'type'       	=> 'option',
									'capability' 	=> themeblvd_admin_module_cap( 'options' ),
									'transport'		=> $transport
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
							if( isset( $theme_option_defaults[$option['id']] ) )
								$default = $theme_option_defaults[$option['id']];
							
							// Transport
							$transport = '';
							if( isset( $option['transport'] ) )
								$transport = $option['transport'];
							
							$priority = '';
							if( isset( $option['priority'] ) )
								$priority = $option['priority'];
							
							// Register option
							$wp_customize->add_setting( $option_name.'['.$option['id'].']', array(
								'default'    	=> esc_attr( $default ),
								'type'       	=> 'option',
								'capability' 	=> themeblvd_admin_module_cap( 'options' ),
								'transport'		=> $transport
							) );
						
							// Add controls
							switch( $option['type'] ) {
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
									$wp_customize->add_control( $option['id'], array(
										'priority'		=> $priority,
										'settings'		=> $option_name.'['.$option['id'].']',
										'label'			=> $option['name'],
										'section'		=> $section['id'],
										'type'			=> 'select',
										'choices'		=> $option['options']
									) );
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
							}
						
						}
					}
				}
			}
		}
		
		// Remove irrelevant sections
		$remove_sections = apply_filters( 'themeblvd_customizer_remove_sections', array( 'title_tagline' ) );
		if( is_array( $remove_sections ) && $remove_sections )
			foreach( $remove_sections as $section )
				$wp_customize->remove_section( $section );
		
		// Modify sections
		$modify_sections = apply_filters( 'themeblvd_customizer_modify_sections', array( 'static_front_page' ) );
		if( is_array( $modify_sections ) && $modify_sections ){
			foreach( $modify_sections as $section ){
				// Currently only one section set to be modified. I'm doing this 
				// loop to make it so you can stop items from being modified and 
				// I can may add to this in the future.
				switch( $section ) {
					case 'static_front_page' :
						// Modify section's title
						$wp_customize->add_section( 'static_front_page', array(
							'title'          => __( 'Homepage', TB_GETTEXT_DOMAIN ),
							'priority'       => 120,
							'description'    => __( 'Your theme supports a static front page.' ),
						) );
						// Add custom homepage option
						$wp_customize->add_setting( $option_name.'[homepage_content]', array(
							'default'    	=> '',
							'type'       	=> 'option',
							'capability' 	=> themeblvd_admin_module_cap( 'options' )
						) );
						$wp_customize->add_control( 'homepage_content', array(
							'settings'		=> $option_name.'[homepage_content]',
							'label'			=> __( 'Homepage Content', TB_GETTEXT_DOMAIN ),
							'section'		=> 'static_front_page',
							'type'			=> 'radio',
							'choices'		=> array(
								'posts'			=> __( 'WordPress Default', TB_GETTEXT_DOMAIN ),
								'custom_layout' => __( 'Custom Layout', TB_GETTEXT_DOMAIN )
							)
						) );
						// Add custom layout selection
						// Custom Layouts
						$custom_layouts = array();
						$custom_layout_posts = get_posts('post_type=tb_layout&numberposts=-1');
						if( ! empty( $custom_layout_posts ) ) {
							foreach( $custom_layout_posts as $layout )
								$custom_layouts[$layout->post_name] = $layout->post_title;
						} else {
							$custom_layouts['null'] = __( 'You haven\'t created any custom layouts yet.', TB_GETTEXT_DOMAIN );
						}				
						$wp_customize->add_setting( $option_name.'[homepage_custom_layout]', array(
							'default'    	=> '',
							'type'       	=> 'option',
							'capability' 	=> themeblvd_admin_module_cap( 'options' )
						) );
						$wp_customize->add_control( 'homepage_custom_layout', array(
							'settings'		=> $option_name.'[homepage_custom_layout]',
							'label'			=> __( 'Homepage Custom Layout', TB_GETTEXT_DOMAIN ),
							'section'		=> 'static_front_page',
							'type'			=> 'select',
							'choices'		=> $custom_layouts
						) );
						break;
				}	
			}
		}
	}
}

/**
 * Styles for WordPress cusomizer.
 *
 * @since 2.1.0
 */ 

if( ! function_exists( 'themeblvd_customizer_styles' ) ) {
	function themeblvd_customizer_styles(){
		wp_register_style( 'themeblvd_customizer', get_template_directory_uri().'/framework/admin/assets/css/customizer.css', false, TB_FRAMEWORK_VERSION );
		wp_enqueue_style( 'themeblvd_customizer' );
	}
}

/**
 * Scripts for WordPress cusomizer.
 *
 * @since 2.1.0
 */ 

if( ! function_exists( 'themeblvd_customizer_scripts' ) ) {
	function themeblvd_customizer_scripts(){
		wp_register_script( 'themeblvd_customizer', get_template_directory_uri().'/framework/admin/assets/js/customizer.js', array('jquery'), TB_FRAMEWORK_VERSION );
	    wp_enqueue_script( 'themeblvd_customizer' );
	    wp_localize_script( 'themeblvd_customizer', 'themeblvd', themeblvd_get_admin_locals( 'customizer_js' ) );	
	}
}

/**
 * Customizer control extensions. 
 */

if( class_exists( 'WP_Customize_Control' ) ) {
	
	/**
	 * Add control for textarea. 
	 */
	
	class WP_Customize_ThemeBlvd_Textarea extends WP_Customize_Control {
		
		public $type = 'textarea';
		public $statuses;
	
		public function __construct( $manager, $id, $args = array() ) {
			$this->statuses = array( '' => __('Default', TB_GETTEXT_DOMAIN ) );
			parent::__construct( $manager, $id, $args );
		}
		
		public function to_json() {
			parent::to_json();
			$this->json['statuses'] = $this->statuses;
		}
		
		public function render_content() {
			?>		
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<textarea <?php $this->link(); ?>><?php echo esc_attr( $this->value() ); ?></textarea>
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
			$this->statuses = array( '' => __( 'Default', TB_GETTEXT_DOMAIN ) );
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
		
		public function render_content() {		
			if ( empty( $this->choices ) )
				return;
			?>
			<label class="themeblvd-font-face">
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<select <?php $this->link(); ?>>
					<?php
					foreach ( $this->choices as $value => $label )
						echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . $label . '</option>';
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
			$this->statuses = array( '' => __('Default', TB_GETTEXT_DOMAIN ) );
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
		
		public function render_content() {
			?>		
			<label class="themeblvd-google-font">
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
				<p><?php _e( 'Example', TB_GETTEXT_DOMAIN ); ?>: Pontano Sans</p>
				<p><a href="http://www.google.com/webfonts" target="_blank"><?php _e( 'Browse Google Fonts', TB_GETTEXT_DOMAIN ); ?></a></p>
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
			$this->statuses = array( '' => __('Default', TB_GETTEXT_DOMAIN ) );
			parent::__construct( $manager, $id, $args );
		}
		
		public function render_content() {
			?>		
			<div class="themeblvd-divider"></div>
			<?php
		}
	
	} 

} // End if class_exists('WP_Customize_Control')

/**
 * Logo Customizer Preview
 *
 * Since the Javascript for the logo will get repeated in 
 * many themes, its being placed here so it can be easily 
 * placed in each theme that requires it.
 *
 * @since 2.1.0
 */

if( ! function_exists( 'themeblvd_customizer_preview_logo' ) ) {
	function themeblvd_customizer_preview_logo(){
		
		// Global option name
		$option_name = themeblvd_get_option_name();
		
		// Setup for logo
		$logo_options = themeblvd_get_option('logo');
		$logo_atts = array(
			'type' 				=> '',
			'site_url'			=> home_url(),
			'title'				=> get_bloginfo('name'),
			'tagline'			=> get_bloginfo('description'),
			'custom' 			=> '',
			'custom_tagline' 	=> '',
			'image' 			=> '',
		);
		foreach( $logo_atts as $key => $value ){
			if( isset($logo_options[$key]) )
				$logo_atts[$key] = $logo_options[$key];
		}
		
		// Begin output	
		?>	
		// Logo object
		Logo = new Object();
		<?php foreach( $logo_atts as $key => $value ) : ?>
		Logo.<?php echo $key; ?> = '<?php echo $value; ?>';
		<?php endforeach; ?>
		
		/* Logo - Type */
		wp.customize('<?php echo $option_name; ?>[logo][type]',function( value ) {
			value.bind(function(value) {
				// Set global marker. This allows us to 
				// know the currently selected logo type 
				// from any other option.
				Logo.type = value;
				
				// Remove classes specific to type so we 
				// can add tehm again depending on new type.
				$('#branding .header_logo').removeClass('header_logo_title header_logo_title_tagline header_logo_custom header_logo_image header_logo_has_tagline');
				
				// Display markup depending on type of 
				// logo selected.
				if( value == 'title' )
				{
					$('#branding .header_logo').addClass('header_logo_title');
					$('#branding .header_logo').html('<h1 class="tb-text-logo"><a href="'+Logo.site_url+'" title="'+Logo.title+'">'+Logo.title+'</a></h1>');
				}
				else if( value == 'title_tagline' )
				{
					$('#branding .header_logo').addClass('header_logo_title_tagline');
					$('#branding .header_logo').addClass('header_logo_has_tagline');
					$('#branding .header_logo').html('<h1 class="tb-text-logo"><a href="'+Logo.site_url+'" title="'+Logo.title+'">'+Logo.title+'</a></h1><span class="tagline">'+Logo.tagline+'</span>');
				}
				else if( value == 'custom' )
				{
					var html = '<h1 class="tb-text-logo"><a href="'+Logo.site_url+'" title="'+Logo.custom+'">'+Logo.custom+'</a></h1>';
					if(Logo.custom_tagline)
					{
						$('#branding .header_logo').addClass('header_logo_has_tagline');
						html = html+'<span class="tagline">'+Logo.custom_tagline+'</span>';
					}
					$('#branding .header_logo').addClass('header_logo_custom');
					$('#branding .header_logo').html(html);
				}
				else if( value == 'image' )
				{
					var html;
					if(Logo.image)
					{
						html = '<a href="'+Logo.site_url+'" title="'+Logo.title+'" class="tb-image-logo"><img src="'+Logo.image+'" alt="'+Logo.title+'" /></a>';
					}
					else
					{
						html = '<strong>Oops! You still need to upload an image.</strong>';
					}
					$('#branding .header_logo').addClass('header_logo_image');
					$('#branding .header_logo').html(html);
				}
			});
		});
		
		/* Logo - Custom Title */
		wp.customize('<?php echo $option_name; ?>[logo][custom]',function( value ) {
			value.bind(function(value) {
				// Set global marker
				Logo.custom = value;
				
				// Only do if anything if the proper logo 
				// type is currently selected.
				if( Logo.type == 'custom' ){
					$('#branding .header_logo h1 a').text(value);
				}
			});
		});
		
		/* Logo - Custom Tagline */
		wp.customize('<?php echo $option_name; ?>[logo][custom_tagline]',function( value ) {
			value.bind(function(value) {
				// Set global marker
				Logo.custom_tagline = value;
				
				// Remove previous tagline if needed.
				$('#branding .header_logo').removeClass('header_logo_has_tagline');
				$('#branding .header_logo .tagline').remove();
				
				// Only do if anything if the proper logo 
				// type is currently selected.
				if( Logo.type == 'custom' ){
					if(value)
					{
						$('#branding .header_logo').addClass('header_logo_has_tagline');
						$('#branding .header_logo').append('<span class="tagline">'+value+'</span>');
					}
				}
			});
		});
		
		/* Logo - Image */
		wp.customize('<?php echo $option_name; ?>[logo][image]',function( value ) {
			value.bind(function(value) {
				// Set global marker
				Logo.image = value;
				
				// Only do if anything if the proper logo 
				// type is currently selected.
				if( Logo.type == 'image' ){
					var html;
					if(value)
					{
						html = '<a href="'+Logo.site_url+'" title="'+Logo.title+'" class="tb-image-logo"><img src="'+Logo.image+'" alt="'+Logo.title+'" /></a>';
					}
					else
					{
						html = '<strong>Oops! You still need to upload an image.</strong>';
					}
					$('#branding .header_logo').addClass('header_logo_image');
					$('#branding .header_logo').html(html);
				}
			});
		});
		<?php 
	}
}

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

if( ! function_exists( 'themeblvd_customizer_preview_font_prep' ) ) {
	function themeblvd_customizer_preview_font_prep(){
		
		// Global option name
		$option_name = themeblvd_get_option_name();
		
		// Setup font stacks
		$font_stacks = themeblvd_font_stacks();
		unset( $font_stacks['google'] );
		
		// Determine current google fonts with fake 
		// booleans to be used in printed JS object.
		$google_fonts = array(
			'body' => array(
				'name' => '',
				'toggle' => 'false'
			),
			'header' => array(
				'name' => '',
				'toggle' => 'false'
			),
			'special' => array(
				'name' => '',
				'toggle' => 'false'
			)
		);
		foreach( $google_fonts as $key => $value ){
			$font = themeblvd_get_option('typography_'.$key);
			if( isset( $font['face']) && $font['face'] == 'google' )
				$google_fonts[$key]['toggle'] = 'true';
			if( isset( $font['google'] ) && $font['google'] )
				$google_fonts[$key]['name'] = $font['google'];
		}
		?>
		// Font stacks
		fontStacks = new Object();
		<?php foreach( $font_stacks as $key => $value ) : ?>
		fontStacks.<?php echo $key; ?> = '<?php echo $value; ?>';
		<?php endforeach; ?>
		
		// Google font toggles
		googleFonts = new Object();
		<?php foreach( $google_fonts as $key => $value ) : ?>
		googleFonts.<?php echo $key; ?>Name = "<?php echo $value['name']; ?>";
		googleFonts.<?php echo $key; ?>Toggle = <?php echo $value['toggle']; ?>;
		<?php endforeach; ?>
		<?php
	}
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

if( ! function_exists( 'themeblvd_customizer_preview_primary_font' ) ) {
	function themeblvd_customizer_preview_primary_font(){
		
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
				$('.preview_body_font').remove();
				$('head').append('<style class="preview_body_font">body{ font-size: '+size+';}</style>');	
			});
		});
		
		/* Body Typography - Face */
		wp.customize('<?php echo $option_name; ?>[typography_body][face]',function( value ) {
			value.bind(function(face) {
				var header_font_face = $('h1, h2, h3, h4, h5, h6').css('font-family');
				if( face == 'google' ){
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
				if(googleFonts.bodyToggle)
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

if( ! function_exists( 'themeblvd_customizer_preview_header_font' ) ) {
	function themeblvd_customizer_preview_header_font(){
	
		// Global option name
		$option_name = themeblvd_get_option_name();
	
		// Begin Output
		?>
		// ---------------------------------------------------------
		// Header Typography
		// ---------------------------------------------------------
		
		/* Header Typography - Face */
		wp.customize('<?php echo $option_name; ?>[typography_header][face]',function( value ) {
			value.bind(function(face) {
				if( face == 'google' ){
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
				if(googleFonts.headerToggle)
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

if( ! function_exists( 'themeblvd_customizer_preview_styles' ) ) {
	function themeblvd_customizer_preview_styles(){
		
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
}