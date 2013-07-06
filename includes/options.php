<?php
// Jump Start contains no mods to framework options, however does
// include a starting point for the WordPress customizer.

/**
 * Setup theme for customizer.
 *
 * @since 1.0.0
 */
function jumpstart_customizer() {

	// Setup logo options
	$logo_options = array(
		'logo' => array(
			'name' 		=> __( 'Logo', 'themeblvd' ),
			'id' 		=> 'logo',
			'type' 		=> 'logo',
			'transport'	=> 'postMessage'
		)
	);
	themeblvd_add_customizer_section( 'logo', __( 'Logo', 'themeblvd' ), $logo_options, 1 );

	// Setup custom styles option
	$custom_styles_options = array(
		'custom_styles' => array(
			'name' 		=> __( 'Enter styles to preview their results.', 'themeblvd' ),
			'id' 		=> 'custom_styles',
			'type' 		=> 'textarea',
			'transport'	=> 'postMessage'
		)
	);
	themeblvd_add_customizer_section( 'custom_css', __( 'Custom CSS Preview', 'themeblvd' ), $custom_styles_options, 121 );
}
add_action( 'after_setup_theme', 'jumpstart_customizer' );

/**
 * Add specific theme elements to customizer.
 *
 * @since 1.0.0
 */
function jumpstart_customizer_init( $wp_customize ) {

	// Add real-time option edits
	if ( $wp_customize->is_preview() && ! is_admin() )
		add_action( 'wp_footer', 'jumpstart_customizer_preview', 21 );

}
add_action( 'customize_register', 'jumpstart_customizer_init' );

/**
 * Setup javascript needed for customizer to link up
 * to real-time edit options.
 *
 * @since 1.0.0
 */
function jumpstart_customizer_preview() {
	// Begin output
	?>
	<script type="text/javascript">
	window.onload = function() { // window.onload for silly IE9 bug fix
		(function($) {

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