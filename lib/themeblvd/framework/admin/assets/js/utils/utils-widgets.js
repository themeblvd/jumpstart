/**
 * Admin Utilities: Widgets
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 */
( function( $, admin ) {

	/**
	 * Sets up an admin widget.
	 *
	 * These are mainly used for the elements of
	 * layout builder, to allow them to toggle
	 * open and closed.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @param {object} element this
	 */
	admin.widgets = function( element ) {

		var $element = $( element );

		// Top-level widgets.
		$element.find( '.widget-content' ).hide();

		$element.find( '.widget-name' ).addClass( 'widget-name-closed' );

		// Inner widgets - i.e. blocks or elements within columns.
		$element.find( '.block-content' ).hide();

		$element.find( '.block-handle' ).addClass( 'block-handle-closed' );

	};

} )( jQuery, window.themeblvd );
