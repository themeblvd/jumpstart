/**
 * Menus Page
 *
 * @package Jump_Start
 * @subpackage Theme_Blvd
 * @license GPL-2.0+
 */

/**
 * Sets up all functionality needed for the
 * Appearance > Menus page.
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $ jQuery object.
 */
( function( $, admin, l10n ) {

	$( document ).ready( function() {

		/**
		 * Handles only showing the mega header checkbox,
		 * if the top-level menu item has been set to be
		 * a mega menu.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		$( '.tb-field-link-mega-hide-headers' ).each( function() {

			var $field = $( this ),
				$item  = $field.closest( '.menu-item' );

			if ( $item.find( '.tb-field-link-mega input' ).is( ':checked' ) ) {

				$field.find( 'label' ).show();

			} else {

				$field.find( 'label' ).hide();

			}

		} );

		/**
		 * When a top-level menu item is selected to be a
		 * mega menu, this handles showing the relevent
		 * mega menu options on sub menu items.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		$( '.tb-field-link-mega input' ).on( 'click', function() {

			var $item = $( this ).closest( '.menu-item' );

			if ( $item.find( '.tb-field-link-mega input' ).is( ':checked' ) ) {

				$item.find( '.tb-field-link-mega-hide-headers label' ).show();

			} else {

				$item.find( '.tb-field-link-mega-hide-headers label' ).hide();

			}

		} );

	} );

} )( jQuery );
