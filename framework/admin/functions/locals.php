<?php
/**
 * Return user read text strings. This function allows
 * to have all of the framework's common localized text 
 * strings in once place. Also, the following filters can 
 * be used to add/remove strings.
 *
 * themeblvd_locals_js
 *
 * @since 2.0.0
 *
 * @param string $type type of set, js or frontend
 * @return array $locals filtered array of localized text strings
 */
 
function themeblvd_get_admin_locals( $type ) {
	$locals = array();
	switch( $type ) {
		// General JS strings
		case ( 'js' ) :
			$locals = array ( 
				'clear'				=> __( 'By doing this, you will clear your database of this theme\'s options. In other words, you will lose any previously saved settings. Are you sure you want to continue?', TB_GETTEXT_DOMAIN ),
				'clear_title'		=> __( 'Clear Options', TB_GETTEXT_DOMAIN ),
				'edit_layout'		=> __( 'Edit Layout', TB_GETTEXT_DOMAIN ),
				'edit_sidebar'		=> __( 'Edit', TB_GETTEXT_DOMAIN ),
				'edit_slider'		=> __( 'Edit Slider', TB_GETTEXT_DOMAIN ),
				'delete_layout'		=> __( 'Are you sure you want to delete the layout(s)?', TB_GETTEXT_DOMAIN ),
				'delete_sidebar'	=> __( 'Are you sure you want to delete the widget area(s)?', TB_GETTEXT_DOMAIN ),
				'delete_slider'		=> __( 'Are you sure you want to delete the slider(s)?', TB_GETTEXT_DOMAIN ),
				'no_name'			=> __( 'Oops! You forgot to enter a name.', TB_GETTEXT_DOMAIN ),
				'invalid_name'		=> __( 'Oops! The name you entered is either taken or too close to another name you\'ve already used.', TB_GETTEXT_DOMAIN ),
				'invalid_slider'	=> __( 'Oops! Somehow, you\'ve entered an invalid slider type.', TB_GETTEXT_DOMAIN ),
				'layout_created'	=> __( 'Layout created!', TB_GETTEXT_DOMAIN ),
				'publish'			=> __( 'Publish', TB_GETTEXT_DOMAIN ),
				'sidebar_created'	=> __( 'Widget Area created!', TB_GETTEXT_DOMAIN ),
				'slider_created'	=> __( 'Slider created!', TB_GETTEXT_DOMAIN ),
				'primary_query'		=> __( 'Oops! You can only have one primary query element per layout. A paginated post list or paginated post grid would be examples of primary query elements.', TB_GETTEXT_DOMAIN ),
				'reset'				=> __( 'By doing this, all of your default theme settings will be saved, and you will lose any previously saved settings. Are you sure you want to continue?', TB_GETTEXT_DOMAIN ),
				'reset_title'		=> __( 'Restore Defaults', TB_GETTEXT_DOMAIN )
			);
			break;
		// Customizer JS strings
		case ( 'customizer_js' ) :
			$locals = array (
				'disclaimer'		=> __( 'Note: The customizer provides a simulated preview, and results may vary slightly when published and viewed on your live website.', TB_GETTEXT_DOMAIN )
			);
		// Could add more types other than JS here later.
	}
	return apply_filters( 'themeblvd_locals_'.$type, $locals );
}