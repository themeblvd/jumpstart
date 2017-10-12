/**
 * Options System: Code Editor
 *
 * @since @@name-framework 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 * @param {object} l10n  Localized admin text strings.
 */
( function( $, admin, l10n ) {

	/**
	 * Handles setting up content editor, called from the
	 * jQuery `themeblvd` namespace.
	 *
	 * @since @@name-framework 2.7.0
	 *
	 * @param {object} element this
	 */
	admin.options.codeEditor = function( element ) {

		var $element = $( element );

		/**
		 * Bind modal code editor instances to all links
		 * with class `tb-textarea-code-link`.
		 *
		 * @since @@name-framework 2.5.0
		 */
		$element.find( '.tb-textarea-code-link' ).themeblvd( 'modal', null, {
			codeEditor: true,
			size: 'medium',
			height: 'auto'
		} );

		/**
		 * Sets up a code editor directly within an
		 * option.
		 *
		 * In other words, the user doesn't need to open a
		 * modal window see the code editor.
		 *
		 * @since @@name-framework 2.5.0
		 */
		$element.find( '.section-code' ).each( function() {

			var $section  = $( this ),
				$textarea = $section.find( 'textarea' ),
				lang      = $textarea.data( 'code-lang' ),
				mode      = '',
				editor    = {};

			// Look for existing instance of this editor.
			editor = $textarea.data( 'CodeMirrorInstance' );

			// Editor doesn't exist, so let's create one.
			if ( ! editor ) {

				// Setup mode for CodeMirror.
				if ( lang == 'html' ) {
					mode = {
						name: "htmlmixed",
						scriptTypes: [{matches: /\/x-handlebars-template|\/x-mustache/i,
									   mode: null},
									  {matches: /(text|application)\/(x-)?vb(a|script)/i,
									   mode: "vbscript"}]
					};
				} else {
					mode = lang;
				}

				// Setup CodeMirror instance
				editor = CodeMirror.fromTextArea( document.getElementById( $textarea.attr( 'id' ) ), {
					mode: mode,
					lineNumbers: true,
					theme: 'themeblvd',
					indentUnit: 4,
					tabSize: 4,
					indentWithTabs: true
				});

				/*
				 * Make sure that code editor content gets sent back
				 * to form's textarea
				 */
				editor.on( 'blur', function() {

					$textarea.val( editor.getValue() );

				} );

				/*
				 * Store CodeMirror instance with textarea so we can
				 * access it again later.
				 */
				$textarea.data( 'CodeMirrorInstance', editor );

			}
		});


	};

} )( jQuery, window.themeblvd, themeblvdL10n );
