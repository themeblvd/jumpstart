/**
 * Options System: Media Uploader
 *
 * @since @@name-framework 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 * @param {object} l10n  Localized admin text strings.
 */
( function( $, admin, l10n ) {

	if ( typeof wp === 'undefined' ) {
		return;
	}

	if ( typeof wp.media === 'undefined' ) {
		return;
	}

	/**
	 * Sets up our media uploader object.
	 *
	 * @since @@name-framework 2.7.0
	 */
	admin.options.mediaUploader = {};

	/**
	 * Keeps track of any media frames created.
	 *
	 * @since @@name-framework 2.7.0
	 */
	admin.options.mediaUploader.frames = {};

	/**
	 * Handle initialization of `media-uploader`
	 * options component from the `themeblvd` jQuery
	 * namespace.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param {object} element this
	 */
	admin.options.mediaUploader.init = function( element ) {

		var $element = $( element ),
			self     = admin.options.mediaUploader;

		$element.find( '.upload-button' ).off( 'click' );

		$element.find( '.remove-image, .remove-file' ).off( 'click' );

		$element.find( '.add-images' ).off( 'click' );

		$element.find( '.upload-button' ).on( 'click', function( event ) {

			event.preventDefault();

			self.addFile( $( this ).closest( '.section-upload, .section-media' ) );

		} );

		$element.find( '.remove-image, .remove-file' ).on( 'click', function( event ) {

			event.preventDefault();

			self.removeFile( $( this ).closest( '.section-upload, .section-media' ) );

		} );

		$element.find( '.add-images' ).on( 'click', function( event ) {

			event.preventDefault();

			var $btn = $( this );

			self.addImages( $btn, $btn.closest( '.section-sortable' ) );

		} );

	};

	/**
	 * Add a file info to an input, from an upload
	 * button.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param {object} $section jQuery object that holds the current media option section.
	 */
	admin.options.mediaUploader.addFile = function( $section ) {

		var self    = admin.options.mediaUploader,
			frameID = $section.find( 'input.upload' ).attr( 'id' );

		/*
		 * If we've already created the media frame, open it
		 * and get the heck out of here.
		 */
		if ( self.frames[ frameID ] ) {

			self.frames[ frameID ].open();

			return;

		}

		var newFrame   = null,
			$trigger   = $section.find( '.trigger' ),
			uploadType = $trigger.data( 'type' ),
			title      = $trigger.data( 'title' ),
			select     = $trigger.data( 'select' ),
			cssClass   = $trigger.data( 'class' ),
			sendBack   = $trigger.data( 'send-back' ),
			link       = $trigger.data( 'link' ),
			mediaType  = 'image',
			workflow   = 'select',
			multiple   = false, // @todo future feature of Quick Slider
			state      = 'library';

		if ( 'video' === uploadType ) {
			mediaType = 'video';
		}

		if ( 'media' === uploadType ) {
			mediaType = '';
			multiple = true;
		}

		if ( 'advanced' === uploadType ) {
			state = 'themeblvd_advanced';
		}

		// Create the media frame.
		newFrame = self.frames[ frameID ] = wp.media.frames.file_frame = wp.media( {
			frame: workflow,
			state: state,
			className: 'media-frame ' + cssClass, // Will break without "media-frame".
			title: title,
			library: {
				type: mediaType
			},
			button: {
				text: select
			},
			multiple: multiple
		} );

		// Setup advanced image selection.
		if ( 'advanced' === uploadType ) {

			// Create "themeblvd_advanced" state.
			newFrame.states.add([

				new wp.media.controller.Library( {
					id: 'themeblvd_advanced',
					title: title,
					priority: 20,
					toolbar: 'select',
					filterable: 'uploaded',
					library: wp.media.query( newFrame.options.library ),
					multiple: false,
					editable: true,
					displayUserSettings: false,
					displaySettings: true,
					allowLocalEdits: true
					// AttachmentView: media.view.Attachment.Library
				} )

			] );
		}

		// When media item is sent back to form field.
		newFrame.on( 'select', function() {

			// Grab the selected attachment.
			var attachment = newFrame.state().get( 'selection' ).first(),
				removeText = $section.find( '.trigger' ).data( 'remove' ),
				size,
				imageUrl,
				linkOption,
				linkUrl,
				helperText;

			/*
			 * Determine Image URL. If it's "advanced" will pull
			 * from crop size selection.
			 */
			if ( 'advanced' === uploadType ) {

				size = newFrame.$el.find( '.attachment-display-settings select[name="size"]' ).val();

				imageUrl = attachment.attributes.sizes[ size ].url;

			} else {

				imageUrl = attachment.attributes.url;

			}

			if ( 'id' === sendBack ) {

				$section.find( '.image-url' ).val( attachment.attributes.id );

			} else {

				$section.find( '.image-url' ).val( imageUrl );

			}

			if ( 'image' === attachment.attributes.type ) {

				$section.find( '.screenshot' ).empty().hide().append( '<img src="' + imageUrl + '"><a class="remove-image"></a>' ).slideDown( 'fast' );

			}

			if ( 'logo' === uploadType ) {

				$section.find( '.image-width' ).val( attachment.attributes.width );

				$section.find( '.image-height' ).val( attachment.attributes.height );

			}

			if ( 'video' === uploadType ) {

				$section.find( '.video-url' ).val( attachment.attributes.url );

			}

			if ( 'slider' === uploadType ) {

				$section.find( '.image-id' ).val( attachment.attributes.id );

				$section.find( '.image-title' ).val( attachment.attributes.title );

				helperText = $section.find( '.image-title' ).val();

				if ( helperText ) {

					$section.closest( '.widget' ).find( '.slide-summary' ).text( helperText ).fadeIn( 200 );

				}
			}

			if ( 'video' !== uploadType && 'media' !== uploadType ) {

				$section.find( '.upload-button' ).unbind().addClass( 'remove-file' ).removeClass( 'upload-button' ).val( removeText );

				$section.find( '.of-background-properties' ).slideDown();

				$section.find( '.remove-image, .remove-file' ).on( 'click', function( event ) {

					event.preventDefault();

					self.removeFile( $( this ).closest( '.section-upload' ) );

				} );
			}

			if ( 'advanced' === uploadType || 'background' === uploadType ) {

				// Send info back.
				$section.find( '.image-id' ).val( attachment.attributes.id );

				$section.find( '.image-title' ).val( attachment.attributes.title );

				$section.find( '.image-alt' ).val( attachment.attributes.alt );

				$section.find( '.image-caption' ).val( attachment.attributes.caption );

				if ( size ) {

					$section.find( '.image-crop' ).val( size );

				}

				// $section.find( '.image-width' ).val( attachment.attributes.sizes[size].width );

				// $section.find( '.image-height' ).val( attachment.attributes.sizes[size].height );

				// Send Link back.
				if ( link ) {

					linkOption = newFrame.$el.find( '.attachment-display-settings .link-to' ).val();

					if ( 'none' !== linkOption ) {

						linkUrl = newFrame.$el.find( '.attachment-display-settings .link-to-custom' ).val();

						$section.closest( '.advanced-image-upload' ).find( '.receive-link-url input' ).val( linkUrl );

					}
				}

			}

		} );

		// Finally, open the modal.
		newFrame.open();

		if ( 'advanced' === uploadType ) {

			newFrame.$el.addClass( 'hide-menu' );

			newFrame.$el.find( '.attachment-display-settings label:first-of-type' ).remove();

			if ( ! link ) {

				newFrame.$el.find( '.attachment-display-settings div.setting' ).remove();

			}
		}

	};

	/**
	 * Remove (or clear) file from input.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param {object} $section jQuery object that holds the current media option section.
	 */
	admin.options.mediaUploader.removeFile = function( $section ) {

		var self       = admin.options.mediaUploader,
			uploadText = $section.find( '.trigger' ).data( 'upload' ),
			uploadType = $section.find( '.trigger' ).data( 'type' );

		if ( 'slider' === uploadType ) {

			$section.closest( '.widget' ).find( '.slide-summary' ).removeClass( 'image video' ).hide().text( '' );

		}

		$section.find( '.remove-image' ).hide();

		$section.find( '.upload' ).val( '' );

		$section.find( '.of-background-properties' ).hide();

		$section.find( '.screenshot' ).slideUp();

		$section.find( '.remove-file' ).addClass( 'upload-button' ).removeClass( 'remove-file' ).val(uploadText);

		$section.find( '.upload-button' ).on( 'click', function( event ) {

			event.preventDefault();

			self.addFile( $( this ).closest( '.section-upload' ) );

		} );

	};

	/**
	 * Add multiple images to a sortable image scenario.
	 *
	 * Note: This is used for the `slider` advanced sortable
	 * option type.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param {object} $button  jQuery object that holds original button to open frame.
	 * @param {object} $section jQuery object that holds the current media option section.
	 */
	admin.options.mediaUploader.addImages = function( $button, $section ) {

		var self    = admin.options.mediaUploader,
			frameID = $button.attr( 'id' );

		/*
		 * If we've already created the media frame, open it,
		 * and get the heck out of here.
		 */
		if ( self.frames[ frameID ] ) {

			self.frames[ frameID ].open();

			return;

		}

		// Create the media frame.
		var newFrame = self.frames[ frameID ] = wp.media.frames.file_frame = wp.media( {
			frame: 'select',
			className: 'media-frame tb-modal-hide-settings', // Will break without "media-frame".
			title: $button.data( 'title' ),
			library: {
				type: 'image'
			},
			button: {
				text: $button.data( 'button' )
			},
			multiple: 'add'
		} );

		// Insert images.
		newFrame.on( 'select update insert', function( event ) {

			var selection,
				state       = newFrame.state(),
				i           = 0,
				images      = [],
				element,
				data,
				$option     = $section.find( '.tb-sortable-option' ),
				$newItems;

			if ( 'undefined' !== typeof event ) {
				selection = event; // Multiple items.
			} else {
				selection = state.get( 'selection' ); // Single item.
			}

			selection.map( function( attachment ) {

				element = attachment.toJSON();

				images[ i ] = {
					id: element.id,
					title: element.title
				}

				if ( element.sizes['tb_thumb'] ) {
					images[ i ]['preview'] = element.sizes['tb_thumb'].url;
				} else {
					images[ i ]['preview'] = element.sizes['full'].url;
				}

				i++;

			} );

			data = {
				action: 'themeblvd-add-' + $option.data( 'type' ) + '-item',
				security: $option.data( 'security' ),
				data: {
					option_name: $option.data( 'name' ),
					option_id: $option.data( 'id' ),
					items: images
				}
			};

			$.post( ajaxurl, data, function( response ) {

				// Append new item.
				$section.find( '.item-container' ).append( response );

				// Cache the items just added.
				var $newItems = $section.find( '.item-container .item' ).slice( -images.length );

				// Make it green for a bit to indicate it was just added.
				$newItems.addClass( 'add' );

				window.setTimeout(function() {

					$newItems.removeClass( 'add' );

				}, 500 );

				// Show "Delete All Items" button.
				$section.find( '.delete-sortable-items' ).fadeIn( 200 );

				// Bind "Delete Item" button.
				$newItems.find( '.delete-sortable-item' ).on( 'click', function( event ) {

					event.preventDefault();

					var $link   = $( this ),
						$option = $link.closest( '.section-sortable' ),
						$item   = $link.closest( '.item' );

					admin.confirm( $link.attr( 'title' ), { 'confirm': true }, function( response ) {

						if ( response ) {

							$item.addClass( 'delete' );

							window.setTimeout(function() {

								$item.remove();

								if ( ! $option.find( '.item-container .item' ).length ) {
									$option.find( '.delete-sortable-items' ).fadeOut( 200 );
								}

							}, 500 );

						}

					} );

				} );

				// Bind toggle for displaying options.
				$newItems.find( '.toggle' ).on( 'click', function( event ) {

					event.preventDefault();

					var $link = $( this );

					if ( $link.closest( '.item-handle' ).hasClass( 'closed' ) ) {

						$link.closest( '.item-handle' ).removeClass( 'closed' );

						$link.closest( '.item' ).find( '.item-content' ).show();

					} else {

						$link.closest( '.item-handle' ).addClass( 'closed' );

						$link.closest( '.item' ).find( '.item-content' ).hide();

					}

				} );

				$newItems.find( '.item-handle h3' ).each( function() {

					var $handle  = $( this ),
						$trigger = $handle.closest( '.item' ).find( '.handle-trigger' );

					if ( $trigger.is( 'select' ) ) {
						$handle.closest( '.item' ).find( '.item-handle h3' ).text( $trigger.find( 'option[value="' + $trigger.val() + '"]' ).text() );
					} else {
						$handle.closest( '.item' ).find( '.item-handle h3' ).text( $trigger.val() );
					}

				} );

				$newItems.find( '.handle-trigger' ).on( 'change', function() {

					var $trigger = $( this );

					if ( $trigger.is( 'select' ) ) {
						$trigger.closest( '.item' ).find( '.item-handle h3' ).text( $trigger.find( 'option[value="' + $trigger.val() + '"]' ).text() );
					} else {
						$trigger.closest( '.item' ).find( '.item-handle h3' ).text( $trigger.val() );
					}

				} );

				// Setup general scripts for options.
				$newItems.themeblvd( 'options', 'setup' );

			} );

		} );

		// Open the new modal.
		newFrame.open();

	};

} )( jQuery, window.themeblvd, themeblvdL10n );
