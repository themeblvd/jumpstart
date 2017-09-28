<?php
/**
 * Admin JavaScript Localized Text Strings
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    @@name-package
 * @subpackage @@name-framework
 * @since      @@name-framework 2.0.0
 */

/**
 * Return user read text strings to be attached
 * to admin JavaScript.
 *
 * This function allows to have all of the framework's
 * common admin localized text strings in once place.
 *
 * @since @@name-framework 2.0.0
 *
 * @param  string $type   Type of set, js or customizer_js.
 * @return array  $locals Filtered array of localized text strings
 */
function themeblvd_get_admin_locals( $type ) {

	$locals = array();

	switch ( $type ) {

		case 'js':
			$locals = array(
				'clear'          => __( 'By doing this, you will clear your database of this option set. In other words, you will lose any previously saved settings. Are you sure you want to continue?', '@@text-domain' ),
				'clear_title'    => __( 'Clear Options', '@@text-domain' ),
				'delete_item'    => __( 'Are you sure you want to delete this item?', '@@text-domain' ),
				'no_name'        => __( 'Oops! You forgot to enter a name.', '@@text-domain' ),
				'invalid_name'   => __( 'Oops! The name you entered is either taken or too close to another name you\'ve already used.', '@@text-domain' ),
				'publish'        => __( 'Publish', '@@text-domain' ),
				'preset'         => __( 'Are you sure you want to apply this group of preset options? This will override some of your current settings.', '@@text-domain' ),
				'primary_query'  => __( 'Oops! You\'ve already got another element displaying paginated posts. When you have more than one paginated set of posts on a page, you\'re going to get funky results.', '@@text-domain' ),
				'reset'          => __( 'By doing this, all of your default theme settings will be saved, and you will lose any previously saved settings. Are you sure you want to continue?', '@@text-domain' ),
				'reset_title'    => __( 'Restore Defaults', '@@text-domain' ),
			);
			break;

		case 'customizer_js':
			$locals = array(
				// @todo
			);

	}

	/**
	 * Filter localization strings printed to
	 * admin JavaScript.
	 *
	 * @since @@name-framework 2.0.0
	 *
	 * @param array $locals Array of text strings.
	 */
	return apply_filters( "themeblvd_locals_{$type}", $locals );

}
