/**
 * Options System: Sortables
 *
 * @since @@name-framework 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 * @param {object} l10n  Localized admin text strings.
 */
( function( $, admin, l10n ) {

	/**
	 * Sets up our sortable object.
	 *
	 * @since @@name-framework 2.7.0
	 */
	admin.options.sortable = {};

	/**
	 * Handle initialization of `sortabler` options
	 * component from the `themeblvd` jQuery namespace.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param {object} element this
	 */
	admin.options.sortable.init = function( element ) {

		var $element = $( element );

		/**
		 * Sets up the actual sortable option type.
		 *
		 * @since @@name-framework 2.5.0
		 */
		$element.find( '.tb-sortable-option' ).each( function() {

			var $option  = $( this ),
				$section = $option.closest( '.section-sortable' ),
				max      = $option.data( 'max' );

			// Setup sortable items
			$section.find( '.item' ).each(function() {
				admin.options.sortable.setupItem( $( this ) );
			} );

			if ( $option.find( '.item-container .item' ).length ) {
				$option.find( '.delete-sortable-items' ).show();
			}

			$section.find( '.item-container' ).sortable( {
				handle: '.item-handle'
			} );

			// Bind "Add Item" button.
			$section.find( '.add-item' ).off( 'click' ); // Avoid duplicates.

			$section.find( '.add-item' ).on( 'click', function( event ) {

				event.preventDefault();

				var $button  = $( this ),
					$newItem = null,
					data     = {};

				data = {
					action: 'themeblvd-add-' + $option.data( 'type' ) + '-item',
					security: $option.data( 'security' ),
					data: {
						option_name: $option.data( 'name' ),
						option_id: $option.data( 'id' )
					}
				};

				$.post( ajaxurl, data, function( response ) {

					$section.find( '.item-container' ).append( response );

					$newItem = $section.find( '.item' ).last();

					// Make it green for a bit to indicate it was just added.
					$newItem.addClass( 'add' );

					window.setTimeout(function() {

						$newItem.removeClass( 'add' );

					}, 500 );

					$section.find( '.delete-sortable-items' ).fadeIn( 200 );

					admin.options.sortable.setupItem( $newItem );

					$newItem.themeblvd( 'options', 'setup' );

					$newItem.themeblvd( 'options', 'editor' );

					$newItem.themeblvd( 'options', 'code-editor' );

					$newItem.themeblvd( 'options', 'media-uploader' );

					if ( max > 0 && $option.find( '.item-container > .item' ).length >= max ) {
						$button.prop( 'disabled', true);
					}

				} );

			} );

			// Bind "Delete All Items" button.
			$section.find( '.delete-sortable-items' ).off( 'click' ); // Avoid duplicates.

			$section.find( '.delete-sortable-items' ).on( 'click', function( event ) {

				event.preventDefault();

				var $link   = $( this ),
					$option = $link.closest( '.tb-sortable-option' ),
					$items  = $option.find( '.item' );

				admin.comfirm( $link.attr( 'title' ), { 'confirm': true }, function( response ) {

					if ( response ) {

						$items.addClass( 'delete' );

						window.setTimeout( function() {

							$items.remove();

							$option.find( '.delete-sortable-items' ).fadeOut( 200 );

							$option.find( '.add-item' ).prop( 'disabled', false );

						}, 500 );

					}

				} );

			} );

		} );

	};

	/**
	 * Setup an individual sortable item.
	 *
	 * @since @@name-framework 2.5.0
	 *
	 * @param {object} $item jQuery object containing sortable dom element.
	 */
	admin.options.sortable.setupItem = function( $item ) {

		$item.find( '.delete-sortable-item' ).off( 'click' ); // Avoid duplicates.

		$item.find( '.delete-sortable-item' ).on( 'click', function( event ) {

			event.preventDefault();

			var $link   = $( this ),
				$option = $link.closest( '.section-sortable' ),
				$item   = $link.closest( '.item' ),
				max     = $link.closest( '.tb-sortable-option' ).data( 'max' );

			admin.confirm( $link.attr( 'title' ), { 'confirm': true }, function( response ) {

				if ( response ) {

					$item.addClass( 'delete' );

					window.setTimeout( function() {

						$item.remove();

						if ( ! $option.find( '.item-container .item' ).length ) {
							$option.find( '.delete-sortable-items' ).fadeOut(200);
						}

						if ( max > 0 && $option.find( '.item-container > .item' ).length < max ) {
							$option.find( '.add-item' ).prop( 'disabled', false);
						}

					}, 500 );
				}

			} );

		} );

		$item.find( '.toggle' ).off( 'click' );  // Avoid duplicates.

		$item.find( '.toggle' ).on( 'click', function( event ) {

			event.preventDefault();

			var $toggle = $( this );

			if ( $toggle.closest( '.item-handle' ).hasClass( 'closed' ) ) {

				$toggle.closest( '.item-handle' ).removeClass( 'closed' );

				$toggle.closest( '.item' ).find( '.item-content' ).show();

			} else {

				$toggle.closest( '.item-handle' ).addClass( 'closed' );

				$toggle.closest( '.item' ).find( '.item-content' ).hide();

			}

		} );

		$item.find( '.item-handle h3' ).each( function() {

			var $handle  = $( this ),
				$trigger = $handle.closest( '.item' ).find( '.handle-trigger' );

			if ( $trigger.is( 'select' ) ) {
				$handle.closest( '.item' ).find( '.item-handle h3' ).text( $trigger.find( 'option[value="' + $trigger.val() + '"]' ).text() );
			} else {
				$handle.closest( '.item' ).find( '.item-handle h3' ).text( $trigger.val() );
			}

		} );

		$item.find( '.handle-trigger' ).off( 'change' ); // Avoid duplicates.

		$item.find( '.handle-trigger' ).on( 'change', function() {

			var $trigger = $( this );

			if ( $trigger.is( 'select' ) ) {
				$trigger.closest( '.item' ).find( '.item-handle h3' ).text( $trigger.find( 'option[value="'+$trigger.val()+'"]' ).text() );
			} else {
				$trigger.closest( '.item' ).find( '.item-handle h3' ).text( $trigger.val() );
			}

		} );

	};

} )( jQuery, window.themeblvd, themeblvdL10n );
