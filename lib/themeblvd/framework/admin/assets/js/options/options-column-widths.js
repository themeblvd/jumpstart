/**
 * Options System: Column Widths
 *
 * This allows the user to use the jQuery ui slider
 * to adjust widths, for a set of columns.
 *
 * @since @@name-framework 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 * @param {object} l10n  Localized admin text strings.
 */
( function( $, admin, l10n ) {

	/**
	 * Sets up our column widths object.
	 *
	 * @since @@name-framework 2.7.0
	 */
	admin.options.columnWidths = {};

	/**
	 * Handle initialization of `column-widths`
	 * options component from the `themeblvd` jQuery
	 * namespace.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param {object} element this
	 */
	admin.options.columnWidths.init = function( element ) {

		$element = $( element );

		$element.find( '.section-column_widths' ).each( function() {

			admin.options.columnWidths.build( $( this ).closest( '.subgroup.columns' ) );

		} );

	};

	/**
	 * Builds each jQuery ui slider object to adjust column
	 * widths.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param {object} $section jQuery object that holds the option section.
	 */
	admin.options.columnWidths.build = function( $section ) {

		var id          = $section.find( '.slider' ).attr( 'id' ),
			grid        = 12, // 10 or 12
			columns     = 3, // 1-5
			total       = 0,
			fraction    = '',
			numerator   = 0,
			denominator = 0,
			current     = $section.find( '.column-width-input' ).val(),
			initValues  = [],
			defaults    = {};

		defaults = {
			10: {
				2: {
					values: [ 0, 5, 10 ],
					display: [ '1/2', '1/2' ]
				},
				3: {
					values: [ 0, 3, 7, 10 ],
					display: [ '3/10', '2/5', '3/10' ]
				},
				4: {
					values: [ 0, 2, 5, 8, 10 ],
					display: [ '1/5', '3/10', '3/10', '1/5' ]
				},
				5: {
					values: [ 0, 2, 4, 6, 8, 10 ],
					display: ['1/5', '1/5', '1/5', '1/5', '1/5' ]
				},
				6: {
					values: [ 0, 1, 3, 5, 7, 9, 10 ],
					display: [ '1/10', '1/5', '1/5', '1/5', '1/5', '1/10' ]
				}
			},
			12: {
				2: {
					values: [ 0, 6, 12 ],
					display: [ '1/2', '1/2' ]
				},
				3: {
					values: [ 0, 4, 8, 12 ],
					display: ['1/3', '1/3', '1/3' ]
				},
				4: {
					values: [ 0, 3, 6, 9, 12 ],
					display: [ '1/4', '1/4', '1/4', '1/4' ]
				},
				5: {
					values: [ 0, 2, 4, 8, 10, 12 ],
					display: [ '1/6', '1/6', '1/3', '1/6', '1/6' ]
				},
				6: {
					values: [ 0, 2, 4, 6, 8, 10, 12 ],
					display: [ '1/6', '1/6', '1/6', '1/6', '1/6', '1/6' ]
				}
			}
		};

		grid = $section.find( '.select-grid-system' ).val(); // 10 or 12

		columns = $section.find( '.select-col-num' ).val();

		$section.find( '.select-col-num' ).off( 'change.ui-slider' ); // Avoid duplicates.

		$section.find( '.select-col-num' ).on( 'change.ui-slider', admin.options.columnWidths.change );

		$section.find( '.select-grid-system' ).off( 'change.ui-slider' ); // Avoid duplicates.

		$section.find( '.select-grid-system' ).on( 'change.ui-slider', admin.options.columnWidths.change );

		// If one or no columns, don't run jQuery UI slider.
		if ( 0 == columns ) {

			return;

		} else if ( 1 == columns ) {

			$section.find( '.slider' ).append( '<div class="column-preview col-1" style="width:100%"><span class="text">100%</span></div>' );

			$section.find( '.column-width-input' ).val( '1/1' ).trigger( 'themeblvd-update-columns' );

			return;

		}

		if ( current ) {

			current = current.split( '-' );

			columns = current.length;

			for ( var i = 0; i <= columns; i++ ) {

				if ( 0 === i ) {

					initValues[ i ] = 0;

				} else if ( i == columns ) {

					initValues[ i ] = grid;

				} else {

					fraction = current[ i - 1 ].split( '/' );

					total += (grid * fraction[0]) / fraction[1];

					initValues[ i ] = total;

				}
			}
		} else {

			initValues = defaults[ grid ][ columns ]['values'];

			current = defaults[ grid ][ columns ]['display'];

			$section.find( '.column-width-input' ).val( current.join( '-' ) ).trigger( 'themeblvd-update-columns' );

		}

		// Setup jQuery UI slider instance
		$( '#' + id ).slider( {
			range: 'max',
			min: 0,
			max: grid,
			step: 1,
			values: initValues,
			create: function( event, ui ) {

				var i           = 0,
					left        = 0,
					width       = 0,
					gridDisplay = '';

				/*
				 * Setup display columns with visible fractions
				 * for the user.
				 */
				for ( i = 1; i <= columns; i++ ) {

					$section.find( '.slider' ).append( '<div class="column-preview col-' + i + '"><span class="text">' + current[ i - 1 ] + '</span></div>' );

					width = ( ( initValues[ i ] - initValues[ i - 1 ] ) / grid ) * 100;

					$section.find( '.col-' + i ).css( 'width', width + '%' );

					if ( i > 1 ) {
						$section.find( '.col-'+i).css( 'left', left + '%' );
					}

					left += width;
				}

				// Add grid presentation.
				left = 0;

				gridDisplay = '<div class="grid-display grid-' + grid + '">';

				for ( i = 1; i <= grid; i++ ) {
					gridDisplay += '<div class="grid-column grid-col-' + i + '"></div>';
				}

				gridDisplay += '</div>';

				$section.find( '.slider' ).append( gridDisplay );

				for ( i = 1; i <= grid; i++ ) {

					left += ( ( 1 / grid ) * 100 );

					$section.find( '.grid-col-'+i).css( 'left', left + '%' );

				}

			},
			slide: function( event, ui ) {

				var index  = $( ui.handle ).index(),
					values = ui.values,
					count  = values.length;

				// First and last can't be moved.
				if ( 1 == index || count == index ) {
					return false;
				}

				var $container      = $( ui.handle ).closest( '.column-widths-wrap' ),
					$option         = $container.find( '.column-width-input' ),
					currentVal      = ui.value,
					nextVal         = values[ index ],
					prevVal         = values[ index - 2 ],
					nextCol         = 0,
					prevCol         = 0,
					prevColFraction = '',
					nextColFraction = '',
					nextNumerator   = 0,
					prevNumerator   = 0,
					prevFinal       = '',
					finalVal        = '',
					finalFractions  = [],
					left            = 0,
					width           = 0;

				// Do not allow handles to pass or touch each other.
				if ( currentVal <= prevVal || currentVal >= nextVal ) {
					return false;
				}

				// Size columns before and after handle.
				prevNumerator = currentVal - prevVal;

				nextNumerator = nextVal - currentVal;

				prevCol = index - 1;

				nextCol = index;

				// Reduce previous column fraction.
				prevColFraction = admin.options.columnWidths.reduceFraction( prevNumerator, grid );

				prevColFraction = prevColFraction[0].toString() + '/' + prevColFraction[1].toString();

				// Reduce next column fraction
				nextColFraction = admin.options.columnWidths.reduceFraction( nextNumerator, grid );

				nextColFraction = nextColFraction[0].toString() + '/' + nextColFraction[1].toString();

				// Set hidden fraction placeholders for reference.
				$container.find( 'input[name="col[' + prevCol + ']"]' ).val( prevColFraction );

				$container.find( 'input[name="col[' + nextCol + ']"]' ).val( nextColFraction );

				// Update final option.
				prevFinal = $option.val();

				prevFinal = prevFinal.split( '-' );

				for ( var i = 1; i <= prevFinal.length; i++ ) {

					if ( i == prevCol ) {
						finalVal += prevColFraction;
					} else if ( i == nextCol ) {
						finalVal += nextColFraction;
					} else {
						finalVal += prevFinal[i-1];
					}

					if ( i != prevFinal.length ) {
						finalVal += '-';
					}

				}

				$option.val( finalVal ).trigger( 'themeblvd-update-columns' );

				/*
				 * Re-set display columns with visible fractions
				 * for the user.
				 */
				finalFractions = finalVal.split( '-' );

				for ( i = 1; i <= columns; i++ ) {

					width = ((values[i]-values[i-1])/grid)*100;

					$section.find( '.col-' + i ).css( 'width', width + '%' );

					$section.find( '.col-' + i + ' .text' ).text( finalFractions[ i - 1 ] );

					if ( i > 1 ) {
						$section.find( '.col-' + i ).css( 'left', left + '%' );
					}

					left += width;

				}
			}

		} );

	};

	/**
	 * Adjust the number of columns or grid system based
	 * on the <select> menu choice, and then re-build.
	 *
	 * @since @@name-framework 2.5.0
	 */
	admin.options.columnWidths.change = function() {

		var $select = $( this ),
			$slider = $( '#' + $select.data( 'slider' ) );

		if ( $slider.data( 'uiSlider' ) ) {
			$slider.slider( 'destroy' );
		}

		$slider.html( '' ).closest( '.column-widths-wrap' ).find( '.column-width-input' ).val( '' );

		admin.options.columnWidths.build( $select.closest( '.subgroup.columns' ) );

	};

	/**
	 * Mathamatically reduce a fraction, by finding the greatest
	 * common denominator.
	 *
	 * @since @@name-framework 2.5.0
	 */
	admin.options.columnWidths.reduceFraction = function( numerator, denominator ) {

		var gcd = function gcd( a, b ) {
			return b ? gcd( b, a % b ) : a;
		};

		gcd = gcd( numerator, denominator );

		return [ numerator / gcd, denominator / gcd ];

	};

} )( jQuery, window.themeblvd, themeblvdL10n );
