/**
 * Options System: Browser Modal Setup
 *
 * @param {jQuery} $ jQuery object.
 */
( function( $ ) {

	$( document ).ready( function( $ ) {

		/**
		 * Setup icon browser.
		 *
		 * @since @@name-framework 2.5.0
		 */
		$( '.themeblvd-icon-browser' ).themeblvd( 'options', 'setup' );

		$( '.themeblvd-icon-browser' ).themeblvd( 'options', 'bind' );

		$( '.themeblvd-icon-browser .select-icon' ).on( 'click', function( event ){

			event.preventDefault();

			var $btn     = $( this ),
				$browser = $btn.closest( '.themeblvd-icon-browser' ),
				icon     = $btn.data( 'icon' );

			$browser.find( '.select-icon' ).removeClass( 'selected' );

			$btn.addClass( 'selected' );

			$browser.find( '.icon-selection' ).val( icon );

			$browser.find( '.media-toolbar-secondary' ).find( '.fa, span, img' ).remove();

			if ( $btn.hasClass( 'select-image-icon' ) ) {

				$browser
					.find( '.media-toolbar-secondary' )
					.append( '<img src="' + $btn.find( 'img' ).attr( 'src' ) + '" /><span>' + icon + '</span>' );

			} else {

				$browser
					.find( '.media-toolbar-secondary' )
					.append( '<i class="fa fa-' + icon + ' fa-2x fa-fw"></i><span>' + icon + '</span>' );

			}

		} );

		/**
		 * Setup texture browser.
		 *
		 * @since @@name-framework 2.5.0
		 */
		$( '.themeblvd-texture-browser' ).themeblvd( 'options', 'setup' );

		$( '.themeblvd-texture-browser' ).themeblvd( 'options', 'bind' );

		if ( $.isFunction( $.fn.wpColorPicker ) ) {
			$( '#texture-browser-perview-color' ).wpColorPicker( {
				change: function() {
					$( '.themeblvd-texture-browser .select-texture span' ).css( 'background-color', $( '#texture-browser-perview-color' ).val() );
				}
			} );
		}

		$( '.themeblvd-texture-browser .wp-color-result' ).attr( 'title', 'Temporary Preview Color' );

		$( '.themeblvd-texture-browser .select-texture span' ).css( 'background-color', $( '#texture-browser-perview-color' ).val() );

		$( '.themeblvd-texture-browser .select-texture' ).on( 'click', function( event ){

			event.preventDefault();

			var $btn = $( this );

			$btn.closest( '.themeblvd-texture-browser' ).find( '.select-texture' ).each( function() {
				$( this ).removeClass( 'selected' );
			} );

			$btn.addClass( 'selected' );

			$btn.closest( '.themeblvd-texture-browser' ).find( '.texture-selection' ).val( $btn.data( 'texture' ) );

			$btn.closest( '.themeblvd-texture-browser' ).find( '.current-texture' ).text( $btn.data( 'texture-name' ) );

		} );

	} );

} )( jQuery );
