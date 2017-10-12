/**
 * Options System: Content Editor
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
	admin.options.editor = function( element ) {

		/**
		 * Bind modal content editor instances to all links
		 * with class `tb-textarea-editor-link`.
		 *
		 * @since @@name-framework 2.5.0
		 */
		$( element ).find( '.tb-textarea-editor-link' ).themeblvd( 'modal', null, {
			build: false,
			padding: true,
			height: 'auto',
			onLoad: function( self ) {

				// Temporary override WP's active editor.
				wpActiveEditor = 'themeblvd_editor';

				var editor       = {},
					editorID     = 'themeblvd_editor',
					textarea     = {},
					fieldName    = '',
					content      = '',
					hasTinymce   = 'undefined' !== typeof tinymce,
					$modalWindow = self.$modalWindow;

				if ( self.$element.is( '.tb-block-editor-link' ) ) {

					fieldName = self.$element.closest( '.block' ).data( 'field-name' );

					textarea = self.$element.closest( '.block' ).find( 'textarea[name="' + fieldName + '[content]"]' );

				} else {

					textarea = self.$element.closest( '.textarea-wrap' ).find( 'textarea' );

				}

				// Get initial raw content.
				content = textarea.val();

				// Height of editor
				$modalWindow.find( '.wp-editor-area, iframe' ).height( 300 );

				if ( $modalWindow.find( '.wp-editor-wrap' ).is( '.tmce-active' ) ) {

					// Get the current editor by ID.
					if ( hasTinymce ) {
						editor = tinymce.get( editorID );
					}

					// To "Visual" editor.
					if ( editor ) {
						content = window.switchEditors.wpautop( content );
						editor.setContent( content, {format:'raw'} );
					}
				} else {

					// To "Text" editor.
					$modalWindow.find( 'textarea' ).val( content );

				}

				// Prevent textarea scroll to show over modal.
				textarea.css( 'overflow', 'hidden' );

			},
			onSave: function( self ) {

				// Put back WP's active editor
				wpActiveEditor = 'content';

				var editor       = {},
					editorID     = 'themeblvd_editor',
					content      = '',
					fieldName    = '',
					$textarea    = {},
					hasTinymce   = 'undefined' !== typeof tinymce,
					$modalWindow = self.$modalWindow;

				if ( self.$element.is( '.tb-block-editor-link' ) ) {

					fieldName = self.$element.closest( '.block' ).data( 'field-name' );

					$textarea = self.$element.closest( '.block' ).find( 'textarea[name="' + fieldName + '[content]"]' );

				} else {

					$textarea = self.$element.closest( '.textarea-wrap' ).find( 'textarea' );

				}

				if ( $modalWindow.find( '.wp-editor-wrap' ).is( '.tmce-active' ) ) {

					if ( hasTinymce ) {
						editor = tinymce.get( editorID );
					}

					// From "Visual" editor
					if ( editor ) {

						content = editor.getContent();

						content = window.switchEditors.pre_wpautop( content );

					}

				} else {

					// From "Text" editor.
					content = $modalWindow.find( '.wp-editor-area' ).val();

				}

				// Update options textara with new contnet from Editor.
				$textarea.val( content );

				// Put back textarea scrolling.
				$textarea.css( 'overflow', 'visible' );

			},
			onCancel: function( self ) {

				// Put back WP's active editor.
				wpActiveEditor = 'content';

				// Put back textarea scrolling.
				self.$element.closest( '.textarea-wrap' ).find( 'textarea' ).css( 'overflow', 'visible' );

			}
		} );

	};

} )( jQuery, window.themeblvd, themeblvdL10n );
