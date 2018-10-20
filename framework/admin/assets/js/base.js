/**
 * Theme Base
 *
 * @package Jump_Start
 * @subpackage Theme_Blvd
 * @license GPL-2.0+
 */

/**
 * Sets up all functionality specific to theme
 * base management page.
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 */
( function( $, admin ) {

	$( document ).ready( function( $ ) {

		/**
		 * When a theme base thumbnail link is clicked,
		 * apply that theme base.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		$( '#themeblvd-base-admin .select-base' ).on( 'click', function( event ) {

			event.preventDefault();

			var $link = $( this );

			admin.confirm( $link.data( 'confirm' ), { 'confirm': true }, function( response ) {

				if ( response ) {
					window.location.href = $link.attr( 'href' );
				}

			} );

		} );

	} );

} )( jQuery, window.themeblvd );
