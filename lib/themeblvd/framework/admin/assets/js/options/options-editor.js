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

		/**
		 * Build inline editors.
		 *
		 * @requires WordPress 4.8
		 * @since @@name-framework 2.7.0
		 */
		if ( 'undefined' !== typeof wp.editor ) {

			$( element ).find( '.tb-editor-input' ).each( function() {

				var $textarea = $( this ),
					editorID  = $textarea.attr( 'id' ),
					style     = $textarea.data( 'style' ),
					height    = 250,
					toolbar   = 'formatselect bold italic bullist numlist blockquote alignleft aligncenter alignright link';

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
					toolbar = 'formatselect bold italic bullist link';
				}

				// Initialize editor with QuickTags and TinyMCE.
				wp.editor.initialize( editorID, {
					tinymce: {
						wpautop: true,
						theme: 'modern',
						height : height,
						plugins : 'charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
						toolbar1: toolbar
					},
					quicktags: true,
					mediaButtons: true
				} );

			} );

		}

	};

} )( jQuery, window, window.themeblvd, themeblvdL10n );
