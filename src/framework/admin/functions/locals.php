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
	switch ( $type ) {

		// General JS strings
		case 'js' :
			$locals = array (
				'clear'					=> __('By doing this, you will clear your database of this option set. In other words, you will lose any previously saved settings. Are you sure you want to continue?', 'jumpstart'),
				'clear_title'			=> __('Clear Options', 'jumpstart'),
				'delete_item'			=> __('Are you sure you want to delete this item?', 'jumpstart'),
				'no_name'				=> __('Oops! You forgot to enter a name.', 'jumpstart'),
				'invalid_name'			=> __('Oops! The name you entered is either taken or too close to another name you\'ve already used.', 'jumpstart'),
				'publish'				=> __('Publish', 'jumpstart'),
				'preset'				=> __('Are you sure you want to apply this group of preset options? This will override some of your current settings.', 'jumpstart'),
				'primary_query'			=> __('Oops! You\'ve already got another element displaying paginated posts. When you have more than one paginated set of posts on a page, you\'re going to get funky results.', 'jumpstart'),
				'reset'					=> __('By doing this, all of your default theme settings will be saved, and you will lose any previously saved settings. Are you sure you want to continue?', 'jumpstart'),
				'reset_title'			=> __('Restore Defaults', 'jumpstart')
			);
			break;

		// Customizer JS strings
		case 'customizer_js' :
			$locals = array (
				// ...
			);

	}
	return apply_filters( 'themeblvd_locals_'.$type, $locals );
}
