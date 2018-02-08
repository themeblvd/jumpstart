/**
 * Options System: Content Editor
 *
 * @since @@name-framework 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 * @param {object} l10n  Localized admin text strings.
 */
( function( $, window, admin, l10n ) {

	wp = window.wp || {};

	/**
	 * Handles setting up content editor, called from the
	 * jQuery `themeblvd` namespace.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param {object} element this
	 */
	admin.options.editor = function( element ) {

		if ( 'undefined' === typeof wp.editor ) {
			return;
		}

		/**
		 * Build inline editors.
		 *
		 * @requires WordPress 4.8
		 * @since @@name-framework 2.7.0
		 */
		$( element ).find( '.tb-editor-input' ).each( function() {

			var $textarea = $( this ),
				editorID  = $textarea.attr( 'id' ),
				style     = $textarea.data( 'style' ),
				height    = 250,
				plugins   = 'charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
				toolbar1  = 'formatselect bold italic bullist numlist blockquote alignleft aligncenter alignright link',
				toolbar2  = null,
				toolbar3  = null,
				toolbar4  = null;

			// The user has disabled TinyMCE.
			if ( 'undefined' === typeof window.tinymce ) {

				wp.editor.initialize( editorID, {
					tinymce: false,
					quicktags: true,
					mediaButtons: true
				} );

				return;

			}

			if ( tinymce.get( editorID ) ) {
				wp.editor.remove( editorID );
			}

			if ( 'mini' == style ) {
				height = 200;
				toolbar1 = 'formatselect bold italic bullist link';
			}

			if ( 'mini' != style ) {

				if ( $textarea.data( 'plugins' ) ) {
					plugins = $textarea.data( 'plugins' );
				}

				if ( $textarea.data( 'toolbar1' ) ) {
					toolbar1 = $textarea.data( 'toolbar1' );
				}

				if ( $textarea.data( 'toolbar2' ) ) {
					toolbar2 = $textarea.data( 'toolbar2' );
				}

				if ( $textarea.data( 'toolbar3' ) ) {
					toolbar3 = $textarea.data( 'toolbar3' );
				}

				if ( $textarea.data( 'toolbar4' ) ) {
					toolbar4 = $textarea.data( 'toolbar4' );
				}

			}

			// Initialize editor with QuickTags and TinyMCE.
			wp.editor.initialize( editorID, {
				tinymce: {
					wpautop: true,
					theme: 'modern',
					height : height,
					plugins : plugins,
					toolbar1: toolbar1,
					toolbar2: toolbar2,
					toolbar3: toolbar3,
					toolbar4: toolbar4
				},
				quicktags: true,
				mediaButtons: true
			} );

			if ( 'mini' != style && themeblvdL10n.add_shortcode ) {

				$shortcodeButton = $( '<a href="#" class="tb-insert-shortcode button" title="' + themeblvdL10n.add_shortcode + '"><span class="tb-icon"></span>' + themeblvdL10n.add_shortcode + '</a>' );

				$textarea.closest( '.wp-editor-tools' ).find( '.wp-media-buttons' ).append( $shortcodeButton );

				$shortcodeButton.on( 'click', function( event ) {

					event.preventDefault();

					$('#tb-shortcode-generator').show();

					$('body').addClass('themeblvd-stop-scroll themeblvd-shortcode-generator-on');

				} );

			}

		} );

	};

} )( jQuery, window, window.themeblvd, themeblvdL10n );
