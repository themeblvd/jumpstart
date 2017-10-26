/**
 * Admin Utilities
 *
 * This is file is required before running any
 * other framework administration JavaScript files.
 *
 * @package Jump_Start
 * @subpackage Theme_Blvd
 * @license GPL-2.0+
 */

/**
 * Global Theme Blvd admin object.
 *
 * @since Theme_Blvd 2.7.0
 *
 * @var {object}
 */
window.themeblvd = {};

/**
 * Admin Utilities: Tools
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 */
( function( $, admin ) {

	/**
	 * Check if we're on a mobile device or not.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @return {bool} Whether it's a mobile device or not.
	 */
	admin.isMobile = function( $body ) {

		if ( ! $body ) {
			$body = $( 'body' );
		}

		if ( $body.hasClass( 'mobile' ) ) {
			return true;
		}

		var agentCheck = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test( navigator.userAgent );

		if ( agentCheck ) {
			return true;
		}

		return false;

	};

} )( jQuery, window.themeblvd );

/**
 * Admin Utilities: Confirmation Prompt
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 * @param {object} l10n  Localized admin text strings.
 */
( function( $, admin, l10n ) {

	/**
	 * Sets up confirmation prompt.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @param {string}      message         Message to user in confirmation prompt.
	 * @param {object}      args            Options for confirmation popup.
	 * @param {bool}        args.confirm    Whether to include Ok and Cancel buttons.
	 * @param {bool}        args.verify     Whether to include Yes and No buttons.
	 * @param {bool|string} args.input      Text input (can be true or string for default input value).
	 * @param {string}      args.inputDesc  Description for below input.
	 * @param {string}      args.textOk     Ok button text.
	 * @param {string}      args.textCancel Cancel button text.
	 * @param {string}      args.textYes    Yes button text.
	 * @param {string}      args.textNo     No button text.
	 * @param {string}      args.class      Optional CSS class to add to prompt.
	 * @param {function}    callback        Callback function once prompt is clicked.
	 */
	admin.confirm = function( message, args, callback ) {

		var $body   = $( 'body' ),
			$window = $( window ),
			$outer,
			$inner,
			$buttons;

		args = $.extend( {
			confirm:    false,
			verify:     false,
			input:      false,
			inputDesc:	'',
			textOk:     l10n.ok,
			textCancel: l10n.cancel,
			textYes:    l10n.yes,
			textNo:     l10n.no,
			className:  ''
		}, args );

		if ( args.input_desc ) { // Backwards compatibility.
			args.inputDesc = args.input_desc;
		}

		// Append initial output.

		$body.append( '<div class="themeblvd-confirm-overlay"></div>' );

		$body.append( '<div class="themeblvd-confirm-outer"></div>' );

		$outer = $( '.themeblvd-confirm-outer' );

		$outer.append( '<div class="themeblvd-confirm-inner"></div>' );

		$inner = $( '.themeblvd-confirm-inner' );

		$inner.append( message );

		$outer.css( 'left', ( $window.width() - $outer.width() ) / 2 + $window.scrollLeft() + 'px' );

		$outer.css( 'top', '100px' ).fadeIn( 200 );

		// Add optional CSS class.

		if ( args.className ) {
			$outer.addClass( args.className );
		}

		// Append text input.

		if ( args.input ) {

            if ( 'string' == typeof( args.input ) ) {

				$inner.append( '<div class="themeblvd-confirm-input"><input type="text" class="themeblvd-confirm-text-box" t="themeblvd-confirm-text-box" value="' + args.input + '" /></div>' );

			} else if ( 'object' == typeof( args.input ) ) {

			    $inner.append( $( '<div class="themeblvd-confirm-input"></div>' ).append( args.input ) );

			} else {

			    $inner.append( '<div class="themeblvd-confirm-input"><input type="text" class="themeblvd-confirm-text-box" /></div>' );

			}

            if ( args.inputDesc ) {

            	$outer.find( '.themeblvd-confirm-text-box' ).after( '<label>' + args.inputDesc + '</label>' );

			}

            $outer.find( '.themeblvd-confirm-text-box' ).focus();

		}

		// Append buttons.

		$inner.append( '<div class="themeblvd-confirm-buttons"></div>' );

		$buttons = $( '.themeblvd-confirm-buttons' );

		if ( args.confirm || args.input ) {

			$buttons.append( '<button class="button-primary" value="ok">' + args.textOk + '</button>' );

			$buttons.append( '<button class="button-secondary" value="cancel">' + args.textCancel + '</button>' );

		} else if ( args.verify ) {

			$buttons.append( '<button class="button-primary" value="ok">' + args.textYes + '</button>' );

			$buttons.append( '<button class="button-secondary" value="cancel">' + args.textNo + '</button>' );

		} else {

			$buttons.append( '<button class="button-primary" value="ok">' + args.textOk + '</button>' );

		}

		$( document ).on( 'keydown', function( event ) {

			if ( $( '.themeblvd-confirm-overlay' ).is( ':visible' ) ) {

				if ( 13 == event.keyCode ) {
					$( '.themeblvd-confirm-buttons > button[value="ok"]' ).trigger( 'click' );
				}

				if ( 27 == event.keyCode ) {
					$( '.themeblvd-confirm-buttons > button[value="cancel"]' ).trigger( 'click' );
				}
			}

		} );

		var inputText = $( '.themeblvd-confirm-text-box' ).val();

		$( '.themeblvd-confirm-text-box' ).on( 'keyup', function() {
			inputText = $( this ).val();
		} );

		$buttons.find( 'button' ).on( 'click', function() {

			$( '.themeblvd-confirm-overlay' ).remove();

			$outer.remove();

			if ( callback ) {

				var buttonType = $( this ).attr( 'value' );

				if ( 'ok' == buttonType ) {

					if ( args.input) {

						callback( inputText );

					} else {

						callback( true );

					}
				} else if ( 'cancel' == buttonType ) {

					callback( false );

				}
			}

		} );

	};

} )( jQuery, window.themeblvd, themeblvdL10n );

/**
 * Confirmation prompt backwards compatibility.
 *
 * We're phasing out tbc_confirm() function in the
 * process of adding all WP admin functionality to
 * window.themeblvd.
 *
 * But since other plugins still may be using tbc_confirm(),
 * we'll keep it as a wrapper for now.
 *
 * @since Theme_Blvd 2.0.0
 * @deprecated Theme_Blvd 2.7.0
 */
function tbc_confirm( message, args, callback ) {

	window.themeblvd.confirm( message, args, callback );

}

/**
 * Admin Utilities: jQuery Namespace
 *
 * Sets up the `themeblvd` jQuery namespace.
 *
 * @since Theme_Blvd 2.7.0
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

/**
 * Admin Utilities: Modals
 *
 * @since Theme_Blvd 2.7.0
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
	 * @since Theme_Blvd 2.7.0
	 *
	 * @var {object}
	 */
	admin.modal = {};

	/**
	 * Default options for the modal plugin.
	 *
	 * @since Theme_Blvd 2.7.0
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
        codeEditor: false,
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
	 * @since Theme_Blvd 2.7.0
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
	 * @param {bool}     settings.codeEditor      Whether this modal is a code editor.
	 * @param {string}   settings.codeLang        If code editor, the code language - html, css, or javascript.
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

					admin.comfirm( deleteMsg, { 'confirm': true }, function( response ) {

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
	 * @since Theme_Blvd 2.7.0
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

		if ( self.settings.codeEditor ) {
			self.popup = self.popup.replace( '%content%', '<form><textarea id="' + self.id + '_editor" name="code"></textarea></form>' );
		}

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

		if ( self.settings.codeEditor ) {

			var content = '';

			if ( self.$element.is( '.tb-block-code-link' ) ) {

				var field_name = self.$element.closest( '.block' ).data( 'field-name' );

				var content = self.$element.closest( '.block' ).find( 'textarea[name="' + field_name + '[html]"]' ).val();

			} else {

				var content = self.$element.closest( '.textarea-wrap' ).find( 'textarea' ).val();

			}

			$( '#' + self.id + '_editor' ).val( content );

		} else {

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

		}

	};

	/**
	 * Display the modal.
	 *
	 * @since Theme_Blvd 2.7.0
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

		// Setup code editor with CodeMirror
		if ( self.settings.codeEditor && typeof CodeMirror !== 'undefined'  ) {

			var editor, mode;

			// Look for existing instance of this editor.
			editor = $( '#' + self.id + '_editor' ).data( 'CodeMirrorInstance' );

			if ( ! editor ) {

				if ( self.settings.codeLang == 'css' || self.settings.codeLang == 'javascript' ) {

					mode = self.settings.codeLang;

				} else {

					mode = {
						name: "htmlmixed",
						scriptTypes: [{matches: /\/x-handlebars-template|\/x-mustache/i,
									   mode: null},
									  {matches: /(text|application)\/(x-)?vb(a|script)/i,
									   mode: "vbscript"}]
					};

				}

				editor = CodeMirror.fromTextArea( document.getElementById( self.id + '_editor' ), {
					mode: mode,
					lineNumbers: true,
					theme: 'themeblvd',
					indentUnit: 4,
					tabSize: 4,
					indentWithTabs: true,
					autofocus: true
				} );

				// If editor was created successfully, store it for access later.
				if ( editor ) {
					$( '#' + self.id + '_editor' ).data( 'CodeMirrorInstance', editor );
				}
			}

		}

		// Adjust height if modal is not full size
		if ( self.settings.height == 'auto' ) {

			var viewport_height = $( window ).height();

			height = self.$modalWindow.find( '.media-frame-content-inner' ).outerHeight();
			height = 57 + height + 60; // 57 for title, 60 for footer

			if ( self.settings.codeEditor ) {
				height += 2;
			} else {
				height += 10;
			}

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

		}

	};

	/**
	 * Close the modal.
	 *
	 * @since Theme_Blvd 2.7.0
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
	 * @since Theme_Blvd 2.7.0
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
	 * @since Theme_Blvd 2.7.0
	 */
	admin.modal.save = function( self ) {

		/*
		 * If code editor, on save we can trasfer the code
		 * from modal back to the textarea in the options.
		 */
		if ( self.settings.codeEditor ) {

			var editor  = $( '#' + self.id + '_editor' ).data( 'CodeMirrorInstance' ),
				content = editor.getValue(),
				textarea;

			if ( self.$element.is( '.tb-block-code-link' ) ) {

				var field_name = self.$element.closest( '.block' ).data( 'field-name' );

				textarea = self.$element.closest( '.block' ).find( 'textarea[name="' + field_name + '[html]"]' );

			} else {

				textarea = self.$element.closest( '.textarea-wrap' ).find( 'textarea' );

			}

			textarea.val( content );

		}

	};

	/**
	 * Backwards compatibility for old $.fn.ThemeBlvdModal
	 * jQuery namespace.
	 *
	 * Current usage should look like:
	 *
	 * $( '.some-link' ).themeblvd( 'modal', null, settings );
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	$.fn.ThemeBlvdModal = function( settings ) {
        return this.each( function() {
            admin.modal.init( this, settings );
        } );
    };


} )( jQuery, window.themeblvd, themeblvdL10n );

/**
 * Admin Utilities: General UI Components
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 */
( function( $, admin ) {

	/**
	 * Sets up the general UI components called from
	 * the jQuery `themeblvd` namespace.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @param {object} element this
	 */
	admin.setup = function( element ) {

		var $element = $( element );

		/**
		 * Toggle admin UI sidebar widgets.
		 *
		 * @since Theme_Blvd 2.0.0
		 */
		$element.off( 'click.tb-widget' );

		$element.on( 'click.tb-widget', '.widget-name-arrow, .block-widget-name-arrow', function( event ) {

			event.preventDefault();

			var $button = $( this ),
				type    = 'widget',
				closed  = false;

			if ( $button.hasClass( 'block-widget-name-arrow' ) ) {
				type = 'block-widget';
			}

			if ( $button.closest( '.' + type + '-name' ).hasClass( type + '-name-closed' ) ) {
				closed = true;
			}

			if ( closed ) {

				// Show widget.

				$button
					.closest( '.' + type )
					.find( '.' + type + '-content' )
					.show();

				$button
					.closest( '.' + type).find('.' + type + '-name' )
					.removeClass( type + '-name-closed' );

				// Refresh any code editor options.
				$button.closest( '.' + type ).find( '.section-code' ).each( function() {

					var $editor = $( this ).find( 'textarea' ).data( 'CodeMirrorInstance' );

					if ( $editor ) {
						$editor.refresh();
					}

				} );

			} else {

				// Close widget.

				$button.closest('.'+type).find('.'+type+'-content').hide();

				$button.closest('.'+type).find('.'+type+'-name').addClass(type+'-name-closed');

			}

		} );

		/**
		 * Setup help tooltips.
		 *
		 * @since Theme_Blvd 2.0.0
		 */
		$element.on( 'click', '.tooltip-link', function( event ) {

			event.preventDefault();

			admin.confirm( $( this ).attr( 'title' ), { 'textOk': 'Ok' } );

		} );

		/**
		 * Delete item by HTML ID passed through
		 * link's href.
		 *
		 * @since Theme_Blvd 2.0.0
		 */
		$element.on( 'click', '.delete-me', function( event ) {

			var $link = $( this ),
				item  = $link.attr( 'href' );

			admin.confirm( $link.attr( 'title' ), { 'confirm': true }, function( response ) {

				if ( response ) {
					$( item ).remove();
				}

			} );

		} );

		/**
		 * Setup fancy selects.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.find( '.tb-fancy-select' ).each( function(){

			var $select = $( this ),
				value   = $select.find( 'select' ).val(),
				text    = $select.find( 'option[value="' + value + '"]' ).text();

			$select.find( '.textbox' ).text( text );

		} );

	};

} )( jQuery, window.themeblvd );

/**
 * Admin Utilities: Accordions
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 */
( function( $, admin ) {

	/**
	 * Sets up an admin accordion.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @param {object} element this
	 */
	admin.accordion = function( element ) {

		var $element = $( element );

		$element.find( '.element-content' ).hide();

		$element.find( '.element-content:first' ).show();

		$element.find( '.element-trigger:first' ).addClass( 'active' );

		$element.find( '.element-trigger' ).on( 'click', function( event ) {

			event.preventDefault();

			var $anchor = $( this );

			if ( ! $anchor.hasClass( 'active' ) ) {

				$element.find( '.element-content' ).hide();

				$element.find( '.element-trigger' ).removeClass( 'active' );

				$anchor.addClass( 'active' );

				$anchor.next( '.element-content' ).show();

			}

		});

	};

} )( jQuery, window.themeblvd );

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
