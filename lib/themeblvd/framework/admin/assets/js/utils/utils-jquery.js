/**
 * Admin Utilities: jQuery Namespace
 *
 * Sets up the `themeblvd` jQuery namespace.
 *
 * @since @@name-framework 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 */
( function( $, admin ) {

	/**
	 * Adds all components as one item to the jQuery
	 * namespace.
	 *
	 * @since 1.0.0
	 *
	 * @param {string} component Component ID like `init` or `options`.
	 * @param {string} part      Sub component part, like `media-uploader`.
	 * @param {object} settings  Settings, if relevent.
	 */
	$.fn.themeblvd = function( component, part, settings ) {

		var componentName = '',
			partName      = '';

		if ( 'init' === component ) {
			component = 'setup';
		}

		/*
		 * To get object name, convert component name to
		 * camel case, i.e. `my-string` to `myString`.
		 */
		if ( component ) {
			componentName = component.replace( /-([a-z])/g, function( g ) {
				return g[1].toUpperCase();
			} );
		}

		if ( part ) {
			partName = part.replace( /-([a-z])/g, function( g ) {
				return g[1].toUpperCase();
			} );
		}

		return this.each( function() {

			if ( 'undefined' !== typeof admin[ componentName ] ) {

				if ( part ) {

					if ( 'undefined' !== typeof admin[ componentName ][ partName ] ) {

						if ( 'undefined' !== typeof admin[ componentName ][ partName ]['init'] ) {

							if ( settings ) {
								admin[ componentName ][ partName ]['init']( this, settings );
							} else {
								admin[ componentName ][ partName ]['init']( this );
							}
						} else {

							if ( settings ) {
								admin[ componentName ][ partName ]( this, settings );
							} else {
								admin[ componentName ][ partName ]( this );
							}
						}
					} else {

						console.log( 'Theme Blvd admin component "' + component + '" is missing part "' + part + '."' );

					}
				} else {

					if ( 'undefined' !== typeof admin[ componentName ]['init'] ) {

						if ( settings ) {
							admin[ componentName ]['init']( this, settings );
						} else {
							admin[ componentName ]['init']( this );
						}
					} else {

						if ( settings ) {
							admin[ componentName ]( this, settings );
						} else {
							admin[ componentName ]( this );
						}
					}
				}
			} else {

				console.log( 'Theme Blvd admin component "' + component + '" is missing.' );

			}

		} );

	};

} )( jQuery, window.themeblvd );
