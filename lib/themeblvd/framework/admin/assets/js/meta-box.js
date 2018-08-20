/**
 * Meta Boxes
 *
 * @package @@name-package
 * @subpackage @@name-framework
 * @license GPL-2.0+
 */
/**
 * Sets up all functionality specific to options
 * pages.
 *
 * @since @@name-framework 2.7.0
 *
 * @param {jQuery} $ jQuery object.
 */
( function( $ ) {

	$( document ).ready( function( $ ) {

		// Setup options with $.fn.themeblvd namspace.

		var $metaBoxes = $( '.tb-meta-box' );

		$metaBoxes.themeblvd( 'init' );

		$metaBoxes.themeblvd( 'options', 'bind' );

		$metaBoxes.themeblvd( 'options', 'setup' );

		$metaBoxes.themeblvd( 'options', 'media-uploader' );

		$metaBoxes.themeblvd( 'options', 'editor' );

		$metaBoxes.themeblvd( 'options', 'code-editor' );

		$metaBoxes.themeblvd( 'options', 'column-widths' );

		$metaBoxes.themeblvd( 'options', 'sortable' );

	} );

} )( jQuery );
