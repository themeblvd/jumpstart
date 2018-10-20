<?php
/**
 * The template file for any sidebars to appear right
 * of the main content.
 *
 * WARNING: This template file is a core part of the
 * Theme Blvd WordPress Framework. It is advised
 * that any edits to the way this file displays its
 * content be done with via actions, filters, and
 * template parts.
 *
 * @author     Jason Bobich <info@themeblvd.com>
 * @copyright  2009-2017 Theme Blvd
 * @package    Jump_Start
 * @subpackage Theme_Blvd
 * @since      Theme_Blvd 2.0.0
 */

/**
 * Fires for both the left and right sidebars, with
 * the context (right or left) passed in.
 *
 * @hooked themeblvd_fixed_sidebars - 10
 *
 * @param string Sidebar context, `right` or `left`.
 */
do_action( 'themeblvd_sidebars', 'right' );
