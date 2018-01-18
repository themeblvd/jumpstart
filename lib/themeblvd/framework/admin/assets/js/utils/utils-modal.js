/**
 * Admin Utilities: Modals
 *
 * @since @@name-framework 2.7.0
 *
 * Example usage:
 *
 * $( '.some-link' ).themeblvd( 'modal', null, settings );
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 * @param {object} l10n  Localized admin text strings.
 */
( function( $, admin, l10n ) {

	/**
	 * Add the modal objects to the global
	 * Theme Blvd admin object.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @var {object}
	 */
	admin.modal = {};

	/**
	 * Default options for the modal plugin.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @var {object}
	 */
	admin.modal.defaults = {
		title: 'Setup',
        button: 'Apply',
        buttonDelete: '',
        buttonSecondary: '',
        size: 'large',
        height: 'large',
        build: true,
        form: false,
        codeLang: 'html',
        vimeo: '',
        padding: false,
        sendBack: {},
        cssClass: '',
        onLoad: function( modal ){},
        onDisplay: function( modal ){},
        onCancel: function( modal ){},
        onSave: function( modal ){},
        onDelete: function( modal ){},
        onSecondary: function( modal ){}
	};

	/**
	 * Initialize the jQuery modal plugin.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param {object}   element                  this
	 * @param {object}   settings                 User settings to merge with defaults.
	 * @param {string}   settings.title           Title of modal window.
	 * @param {string}   settings.button          Button to text to close modal.
	 * @param {string}   settings.buttonDelete    Text for delete button in lower left - if blank, no button.
	 * @param {string}   settings.buttonSecondary Text for duplicate button in lower left - if blank, no button.
	 * @param {string}   settings.size            Determines width of modal - small, medium, large.
	 * @param {string}   settings.height          Determines height of modal - small, medium, large, or auto.
	 * @param {bool}     settings.build           Whether to build the HTML markup for the popup.
	 * @param {bool}     settings.form            Whether this modal is a set of options.
	 * @param {string}   settings.vimeo           If video modal, ID of Vimeo video.
	 * @param {bool}     settings.padding         Whether to add extra padding to modal content.
	 * @param {object}   settings.sendBack        An object of something you want send to info back to callback functions.
	 * @param {string}   settings.cssClass        Optional CSS class to add to modal.
	 * @param {function} settings.onLoad          Callback before modal window is displayed.
	 * @param {function} settings.onDisplay       Callback just after modal has been displayed.
	 * @param {function} settings.onCancel        Callback when close button is clicked, before modal is closed.
	 * @param {function} settings.onSave          Callback when save button is clicked, before modal is closed.
	 * @param {function} settings.onDelete        Callback when delete button is clicked, before modal is closed.
	 * @param {function} settings.onSecondary     Callback when secondary button is clicked, before modal is closed.
	 */
	admin.modal.init = function( element, settings ) {

		var self = Object.create( admin.modal );

		// var self = admin.modal;

		self.element   = element;
		self.$element  = $( element );
		self.secondary = false;

		self.settings = $.extend( {}, self.defaults, settings );

		// Allow data overrides from HTML element.
		for ( var index in self.settings ) {
			if ( self.$element.data( index ) ) {
				self.settings[ index ] = self.$element.data( index );
			}
		}

		// HTML element to show, or pull content from.
		self.target = self.$element.data( 'target' );

		self.$target = $( '#' + self.target );

		// Open modal.
		self.$element.off( 'click.themeblvd-modal' );

		self.$element.on( 'click.themeblvd-modal', function( event ) {

			event.preventDefault();

			// Setup popup.
			self.popup = '';

			// Is another modal already open?
			if ( $( 'body' ).hasClass( 'themeblvd-modal-on' ) ) {
				self.secondary = true;
			}

			// ID for modal.
			self.id = self.settings.vimeo ? 'video-' + self.settings.vimeo : self.target;

			// Build the popup if needed.
			if ( self.settings.build ) {
				self.id += '_build';
				self.build( self );
			}

			self.$modalWindow = $( '#' + self.id );

			self.$modalWindow.data( 'target', self.target );

			// Optional padding on content.
			if ( self.settings.padding ) {
				self.$modalWindow.find( '.media-frame-content-inner' ).css( 'padding', '20px' );
			}

			// onLoad() callback.
			self.settings.onLoad( self );

			// Bind close.
			self.$modalWindow.find( '.media-modal-close' ).off( 'click.themeblvd-modal-close' );

			self.$modalWindow.find( '.media-modal-close' ).on( 'click.themeblvd-modal-close', function( event ){

				event.preventDefault();

				$( this ).trigger( 'themeblvd-modal-close' );

				self.settings.onCancel( self );

				self.close( self );

			} );

			// Save.
			self.$modalWindow.find( '.media-button-insert' ).off( 'click.themeblvd-modal-insert' );

			self.$modalWindow.find( '.media-button-insert' ).on( 'click.themeblvd-modal-insert', function( event ){

				event.preventDefault();

				$( this ).trigger( 'themeblvd-modal-insert' );

				self.settings.onSave( self );

				self.save( self );

				self.close( self );

			} );

			// Secondary button.
			if ( self.settings.buttonSecondary ) {

				self.$modalWindow.find( '.media-button-secondary' ).off( 'click.themeblvd-modal-secondary' );

				self.$modalWindow.find( '.media-button-secondary' ).on( 'click.themeblvd-modal-secondary', function( event ){

					event.preventDefault();

					$( this ).trigger( 'themeblvd-modal-secondary' );

					self.settings.onSecondary( self );

					self.close( self );

				} );
			}

			// Delete.
			if ( self.settings.buttonDelete ) {

				var deleteMsg = self.$element.parent().is( '.block-nav' ) ? l10n.delete_block : l10n.delete_item;

				self.$modalWindow.find( '.media-button-delete' ).off( 'click.themeblvd-modal-delete' );

				self.$modalWindow.find( '.media-button-delete' ).on( 'click.themeblvd-modal-delete', function( event ){

					event.preventDefault();

					admin.confirm( deleteMsg, { 'confirm': true }, function( response ) {

						if ( response ) {

							$( this ).trigger( 'themeblvd-modal-delete' );

							self.settings.onDelete( self );

							self.close( self );

						}

					} );

				} );
			}

			// Display it.
			self.display( self );

			// onDisplay() callback.
			self.settings.onDisplay( self );

		} );

	};

	/**
	 * Build the modal markup.
	 *
	 * @since @@name-framework 2.7.0
	 */
	admin.modal.build = function( self ) {

		self.popup = '<div id="%id%" class="themeblvd-modal-wrap build" style="display:none;"> \
							<div class="themeblvd-modal %size%-modal %height%-height-modal media-modal wp-core-ui hide"> \
								<button type="button" class="media-modal-close"> \
									<span class="media-modal-icon"> \
										<span class="screen-reader-text">x</span> \
									</span> \
								</button> \
								<div class="media-modal-content"> \
									<div class="media-frame wp-core-ui hide-menu hide-router"> \
										<div class="media-frame-title"> \
											<h1>%title%</h1> \
										</div><!-- .media-frame-title (end) --> \
										<div class="media-frame-content"> \
											<div class="media-frame-content-inner"> \
												<div class="content-mitt"> \
													%content% \
												</div> \
											</div><!-- .media-frame-content-inner (end) --> \
										</div><!-- .media-frame-content (end) --> \
										<div class="media-frame-toolbar"> \
											<div class="media-toolbar"> \
												<div class="media-toolbar-primary"> \
													<a href="#" class="button media-button button-primary button-large media-button-insert">%button_text%</a> \
												</div> \
											</div><!-- .media-toolbar (end) --> \
										</div><!-- .media-frame-toolbar (end) --> \
									</div><!-- .media-frame (end) --> \
								</div><!-- .media-modal-content (end) --> \
							 </div><!-- .themeblvd-modal (end) --> \
						 </div>';

		self.popup = self.popup.replace( '%id%', self.id );
		self.popup = self.popup.replace( '%size%', self.settings.size );
		self.popup = self.popup.replace( '%height%', self.settings.height );
		self.popup = self.popup.replace( '%title%', self.settings.title );
		self.popup = self.popup.replace( '%button_text%', self.settings.button );

		if ( self.settings.form ) {
			self.popup = self.popup.replace( '%content%', '<div class="tb-options-wrap"></div>' );
		}

		if ( self.settings.vimeo ) {
			self.popup = self.popup.replace( self.settings.height + '-height-modal', 'modal-video' );
			self.popup = self.popup.replace( '%content%', '<iframe src="https://player.vimeo.com/video/' + self.settings.vimeo + '?autoplay=1" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>' );
		}

		if ( self.settings.custom ) {
			self.popup = self.popup.replace( '%content%', '' );
		}

		/*
		 * Add to bottom of <body>. Will be removed completely
		 * when modal is closed.
		 */
		$( 'body' ).append( self.popup );

		var $current = $( '#' + self.id );

		// Remove default button?
		if ( ! self.settings.button && ! self.settings.buttonSecondary && ! self.settings.buttonDelete ) {
			$current.find( '.media-frame-toolbar' ).remove();
			$current.find( '.themeblvd-modal' ).addClass( 'hide-toolbar' );
		}

		// Duplicate Button?
		if ( self.settings.buttonSecondary ) {
			$current.find( '.media-toolbar' ).prepend( '<div class="media-toolbar-secondary"><a href="#" class="button media-button button-secondary button-large media-button-secondary">' + self.settings.buttonSecondary + '</a></div>' );
		}

		// Delete button?
		if ( self.settings.buttonDelete ) {
			$current.find( '.media-toolbar' ).prepend( '<div class="media-toolbar-secondary"><a href="#" class="button media-button button-secondary button-large media-button-delete">' + self.settings.buttonDelete + '</a></div>' );
		}

		/*
		 * Set marker to send content back to when modal
		 * is closed.
		 */
		self.$target.after( '<div id="' + self.id + '_marker"></div>' );

		// Apend user content.
		if ( self.settings.form ) {
			$current.find( '.tb-options-wrap' ).append( self.$target );
		} else {
			$current.find( '.content-mitt' ).append( self.$target );
		}

	};

	/**
	 * Display the modal.
	 *
	 * @since @@name-framework 2.7.0
	 */
	admin.modal.display = function( self ) {

		var $body = $( 'body' ),
			height;

		if ( self.secondary ) {

			/*
			 * Another modal is already open, and this one is
			 * appearing above it.
			 */
			$body.addClass( 'themeblvd-secondary-modal-on' );

			self.$modalWindow.find( '.themeblvd-modal' ).removeClass( 'themeblvd-first-modal' ).addClass( 'themeblvd-second-modal' );

			$body.find( '.themeblvd-first-modal' ).prepend( '<div class="inline-media-modal-backdrop"></div>' );

		} else {

			$body.addClass( 'themeblvd-modal-on' );

			$body.addClass( 'themeblvd-stop-scroll' );

			$body.append( '<div id="themeblvd-modal-backdrop" class="media-modal-backdrop"></div>' );

			// Add class to denote original base modal.
			self.$modalWindow.find( '.themeblvd-modal' ).addClass( 'themeblvd-first-modal' );

			// Close all open modals.
			$( '#themeblvd-modal-backdrop' ).off( 'click.themeblvd-modal-backdrop' );

			$( '#themeblvd-modal-backdrop' ).on( 'click.themeblvd-modal-backdrop', function( event ) {

				event.preventDefault();

				self.settings.onCancel( self );

				self.closeAll( self );

			});
		}

		// Custom CSS class?
		if ( self.settings.cssClass ) {
			self.$modalWindow.find( '.themeblvd-modal' ).addClass( self.settings.cssClass );
		}

		// Show modal
		self.$modalWindow.show();

		// Adjust height if modal is not full size
		if ( self.settings.height == 'auto' ) {

			var viewport_height = $( window ).height();

			height = self.$modalWindow.find( '.media-frame-content-inner' ).outerHeight();

			height = 57 + height + 60; // 57 for title, 60 for footer

			height += 10;

			self.$modalWindow.find( '.themeblvd-modal' ).css({
				'max-height': height + 'px',
				'top': (viewport_height - height) / 2 + 'px'
			});

			$( window ).resize( function() {
				self.$modalWindow.find( '.themeblvd-modal' ).css( 'top', ( $( window ).height() - height) / 2 + 'px' );
			});
		}

		// Make sure any scripts for options run.
		if ( self.settings.form ) {

			self.$modalWindow.themeblvd( 'options', 'setup' );

			self.$modalWindow.themeblvd( 'options', 'bind' );

			self.$modalWindow.themeblvd( 'options', 'media-uploader' );

			self.$modalWindow.themeblvd( 'options', 'sortable' );

			self.$modalWindow.themeblvd( 'options', 'code-editor' );

		}

	};

	/**
	 * Close the modal.
	 *
	 * @since @@name-framework 2.7.0
	 */
	admin.modal.close = function( self ) {

		var $body = $( 'body' );

		// Put content from modal back to original location.
		if ( self.settings.build ) {
			$( '#' + self.id + '_marker' ).after( self.$target ).remove();
		}

		// Unbind links within modal.
		self.$modalWindow.find( '.media-button-secondary' ).off( 'click.themeblvd-modal-secondary' );

		self.$modalWindow.find( '.media-modal-close' ).off( 'click.themeblvd-modal-close' );

		self.$modalWindow.find( '.media-button-insert' ).off( 'click.themeblvd-modal-insert' );

		self.$modalWindow.find( '.media-button-delete' ).off( 'click.themeblvd-modal-delete' );

		// Hide or Remove modal
		if ( self.settings.build ) {
			self.$modalWindow.remove();
		} else {
			self.$modalWindow.hide();
		}

		// Put everything outside of the modal back
		if ( self.secondary ) {

			/*
			 * Closing a modal on top of another modal.
			 * Remove body class and extra backdrop.
			 */
			$body.removeClass( 'themeblvd-secondary-modal-on' );

			$body.find( '.themeblvd-modal-wrap .inline-media-modal-backdrop' ).remove();


		} else {

			$body.removeClass( 'themeblvd-modal-on' );

			$body.removeClass( 'themeblvd-stop-scroll' );

			$( '#themeblvd-modal-backdrop' ).remove();

		}

	};

	/**
	 * Close all open modals.
	 *
	 * @since @@name-framework 2.7.0
	 */
	admin.modal.closeAll = function( self ) {

		$( '.themeblvd-modal-wrap' ).each( function() {

			var $element = $( this ),
				id,
				target;

			if ( $element.hasClass( 'build' ) ) {

				id = $element.attr( 'id' );

				target = $element.data( 'target' );

				$( '#' + id + '_marker' ).after( $( '#' + target ) ).remove();

				$element.remove();

			} else {
				$element.hide();
			}

			$( '#themeblvd-modal-backdrop' ).remove();

			$( 'body' ).removeClass( 'themeblvd-modal-on themeblvd-secondary-modal-on themeblvd-stop-scroll' );

		} );

	};

	/**
	 * Save the modal contents.
	 *
	 * Note: This is just passing form data back and not
	 * actually saving to a database of any kind.
	 *
	 * @since @@name-framework 2.7.0
	 */
	admin.modal.save = function( self ) {

		// Do nothing currently.

	};

	/**
	 * Backwards compatibility for old $.fn.ThemeBlvdModal
	 * jQuery namespace.
	 *
	 * Current usage should look like:
	 *
	 * $( '.some-link' ).themeblvd( 'modal', null, settings );
	 *
	 * @since @@name-framework 2.5.0
	 */
	$.fn.ThemeBlvdModal = function( settings ) {
        return this.each( function() {
            admin.modal.init( this, settings );
        } );
    };


} )( jQuery, window.themeblvd, themeblvdL10n );
