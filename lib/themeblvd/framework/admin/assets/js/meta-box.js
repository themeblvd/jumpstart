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

	/**
	 * Toggle sidebar layout selection added to Page
	 * Attributes meta box.
	 *
	 * When the "Custom Layout" or "Blank Page" page template
	 * are selected, we want to hide the selection for a
	 * sidebar layout, to make it obvious these template do
	 * not use a sidebar layout.
	 *
	 * @since @@name-framework 2.2.0
	 */
	$( '#page_template' ).on( 'change', function() {

		var value = $( this ).val();

		if ( 'template_builder.php' === value || 'template_blank.php' === value ) {

			$( '#tb-sidebar-layout' ).hide();

		} else {

			$( '#tb-sidebar-layout' ).show();

		}

	} );

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

} )( jQuery );
