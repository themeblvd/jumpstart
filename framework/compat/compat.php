<?php
/**
 * Include compatibility fixes for select plugins.
 *
 * @since 2.5.0
 */
function themeblvd_plugin_compat() {

	// bbPress by Automattic
	if ( themeblvd_supports('plugins', 'bbpress') ) {

		include_once( TB_FRAMEWORK_DIRECTORY . '/compat/bbpress/class-tb-compat-bbpress.php' );

		$bbpress = Theme_Blvd_Compat_bbPress::get_instance();

	}

	// ... @TODO more plugins

}