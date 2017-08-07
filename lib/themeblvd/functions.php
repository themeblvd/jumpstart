<?php
/**
 * Include Theme Blvd wordPress framework.
 *
 * Below is the file needed to load the parent theme and theme framework.
 * It's included with require_once() so that it can be included via a child
 * theme without having to worry about duplicate inclusion.
 *
 * So, if you're creating a child theme, this line needs to be at the top of your
 * child theme's functions.php. By doing this you're overriding the file being
 * included here.
 *
 * @author		Jason Bobich
 * @copyright	2009-2017 Theme Blvd
 * @link		http://themeblvd.com
 * @package 	@@name-package
 */

require_once( get_template_directory() . '/framework/themeblvd.php' );
