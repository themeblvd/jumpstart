<?php
// Jump Start contains no mods to framework options, however does 
// include a starting point for the WordPress customizer...

/**
 * Setup theme for customizer.
 */
 
if( ! function_exists( 'jumpstart_customizer' ) ) {
	function jumpstart_customizer(){
		
		// Setup logo options
		$logo_options = array(
			'logo' => array( 
				'name' 		=> __( 'Logo', TB_GETTEXT_DOMAIN ),
				'id' 		=> 'logo',
				'type' 		=> 'logo',
				'transport'	=> 'postMessage'
			)
		);
		themeblvd_add_customizer_section( 'logo', __( 'Logo', TB_GETTEXT_DOMAIN ), $logo_options, 1 );
		
		// Setup custom styles option
		$custom_styles_options = array(
			'custom_styles' => array( 
				'name' 		=> __( 'Enter styles to preview their results.', TB_GETTEXT_DOMAIN ),
				'id' 		=> 'custom_styles',
				'type' 		=> 'textarea',
				'transport'	=> 'postMessage'
			)
		);
		themeblvd_add_customizer_section( 'custom_css', __( 'Custom CSS Preview', TB_GETTEXT_DOMAIN ), $custom_styles_options, 121 );
	}
}
add_action( 'after_setup_theme', 'jumpstart_customizer' );

/**
 * Add specific theme elements to customizer.
 */

if( ! function_exists( 'jumpstart_customizer_init' ) ) {
	function jumpstart_customizer_init( $wp_customize ){
		// Add real-time option edits
		if ( $wp_customize->is_preview() && ! is_admin() ){
			add_action( 'wp_footer', 'jumpstart_customizer_preview', 21 );
		}
	}
}
add_action( 'customize_register', 'jumpstart_customizer_init' );

if( ! function_exists( 'jumpstart_customizer_preview' ) ) {
	function jumpstart_customizer_preview(){
		// Begin output	
		?>
		<script type="text/javascript">
		window.onload = function(){ // window.onload for silly IE9 bug fix	
			(function($){
				
				// ---------------------------------------------------------
				// Logo
				// ---------------------------------------------------------
				
				<?php themeblvd_customizer_preview_logo(); ?>
				
				// ---------------------------------------------------------
				// Custom CSS
				// ---------------------------------------------------------
				
				<?php themeblvd_customizer_preview_styles(); ?>
				
			})(jQuery);
		} // End window.onload for silly IE9 bug
		</script>
		<?php
	}
}