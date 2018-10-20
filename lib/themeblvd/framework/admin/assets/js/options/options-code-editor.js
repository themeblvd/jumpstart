/**
 * Options System: Code Editor
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 * @param {object} l10n  Localized admin text strings.
 */
( function( $, window, admin, l10n ) {

	wp = window.wp || {};

	admin.options.codeEditorLangs = {};

	admin.options.codeEditors = {};

	/**
	 * Add a code editor's settings to our global
	 * cache.
	 *
	 * This gets called inline as we pass settings
	 * from PHP using `wp_enqueue_code_editor()`.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @param {string} lang     Coding language, like `css`, `javascript` or `html`.
	 * @param {object} settings Settings for codemirror.
	 */
	admin.options.addCodeEditorLang = function( lang, settings ) {

		admin.options.codeEditorLangs[ lang ] = settings;

	};

	/**
	 * Add a code editor's settings to our global
	 * cache.
	 *
	 * This gets called inline as we pass settings
	 * from PHP using `wp_enqueue_code_editor()`.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @param {object} element this
	 */
	admin.options.addCodeEditor = function( id, settings ) {

		if ( 'undefined' === typeof wp.codeEditor ) {
			return;
		}

		admin.options.codeEditors[ id ] = wp.codeEditor.initialize( id, settings );

	};

	/**
	 * Handles setting up content editor, called from the
	 * jQuery `themeblvd` namespace.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @param {object} element this
	 */
	admin.options.codeEditor = function( element ) {

		if ( 'undefined' === typeof wp.codeEditor ) {
			return;
		}

		var $element = $( element );

		/**
		 * Sets up a code editor directly within an
		 * option.
		 *
		 * In other words, the user doesn't need to open a
		 * modal window see the code editor.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		$element.find( '.section-code' ).each( function() {

			var editors     = window.themeblvd.options.codeEditors,
				editorLangs = window.themeblvd.options.codeEditorLangs,
				$textarea   = $( this ).find( 'textarea' ),
				editorID    = $textarea.attr( 'id' ),
				editorLang  = $textarea.data( 'code-lang' );

			// Editor already exists; refresh it.
			if ( 'undefined' != typeof editors[ editorID ] ) {

				editors[ editorID ].codemirror.refresh();

				return;

			}

			// Cooresponding settings exist, initialize editor.
			if ( 'undefined' != typeof editorLangs[ editorLang ] ) {

				admin.options.addCodeEditor( editorID, editorLangs[ editorLang ] );

			}

		} );

	};

} )( jQuery, window, window.themeblvd, themeblvdL10n );
