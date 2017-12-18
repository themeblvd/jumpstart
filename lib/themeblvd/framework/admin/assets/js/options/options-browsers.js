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
		var $iconBrowser = $( '.themeblvd-icon-browser' );

		$iconBrowser.themeblvd( 'options', 'setup' );

		$iconBrowser.themeblvd( 'options', 'bind' );

		$iconBrowser.find( '.select-icon' ).on( 'click', function( event ){

			event.preventDefault();

			var $btn     = $( this ),
				$browser = $btn.closest( '.themeblvd-icon-browser' ),
				icon     = $btn.data( 'icon' );

			$browser.find( '.select-icon' ).removeClass( 'selected' );

			$btn.addClass( 'selected' );

			$browser.find( '.icon-selection' ).val( icon );

			$browser.find( '.icon-selection-wrap' ).find( 'i, span, .svg-inline--fa' ).remove();

			$browser
				.find( '.icon-selection-wrap' )
				.append( '<i class="' + icon + ' fa-2x fa-fw"></i><span>' + icon + '</span>' );

		} );

		$iconBrowser.find( '.icon-search-input' ).on( 'keyup', function( event ) {

			var value   = $( this ).val(),
				results = [];

			console.log( 'VALUE: ' + value );

			if ( ! value ) {
				$iconBrowser.find( '.select-icon' ).show();
				return;
			}

			$iconBrowser.find( '.select-icon' ).hide();

			if ( 'undefined' !== typeof themeblvdIconSearchData ) {

				$.each( themeblvdIconSearchData, function( name, terms ) {

					var i, term;

					for ( i = 0; i < terms.length; i++ ) {

						term = terms[ i ];

						if ( 0 === term.indexOf( value ) ) {

							results.push( '.icon-' + name );

							i = terms.length; // End loop.

						}
					}

				} );

			}

			$iconBrowser.find( results.join() ).show();

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
