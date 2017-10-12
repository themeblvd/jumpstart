/**
 * Options Page
 *
 * @package @@name-package
 * @subpackage @@name-framework
 * @license GPL-2.0+
 */

/**
 * Sets up all functionality specific to options
 * pages.
 *
 * @since @@name-framework 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 * @param {object} l10n  Localized admin text strings.
 */
( function( $, admin, l10n ) {

	$( document ).ready( function( $ ) {

		var $page    = $( '.tb-options-page' ),
			$options = $( '.tb-options-js' );

		/**
		 * Determine the active tab and show it.
		 *
		 * If the user was previously on a tab before the
		 * page was re-loaded, that tab should display again.
		 *
		 * If no tab is saved in storage, then the first tab
		 * is shown.
		 *
		 * @since @@name-framework 2.2.0
		 */
		var activeTab = '';

		if ( 'undefined' !== typeof localStorage ) {
			activeTab = localStorage.getItem( 'tb-active-tab' );
		}

		var $activePane = $page.find( activeTab );

		if ( $activePane.length ) {
			$activePane.fadeIn();
		} else {
			$page.find( '.group:first' ).fadeIn();
		}

		var $activeTab = $page.find( activeTab + '-tab' );

		if ( $activeTab.length ) {
			$activeTab.addClass( 'nav-tab-active' );
		} else {
			$page.find( '.nav-tab-wrapper a:first' ).addClass( 'nav-tab-active' );
		}

		/**
		 * Handles swapping between option panes from
		 * clicking main navigation tabs.
		 *
		 * @since @@name-framework 2.2.0
		 */
		$page.find( '.nav-tab-wrapper a' ).on( 'click', function( event ) {

			event.preventDefault();

			var $tab  = $( this ),
				$pane = $( $tab.attr( 'href' ) );

			$( '.nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );

			$tab.addClass( 'nav-tab-active' ).trigger( 'blur' );

			if ( 'undefined' !== typeof localStorage ) {
				localStorage.setItem( 'tb-active-tab', $tab.attr( 'href' ) );
			}

			$page.find( '.group' ).hide();

			$pane.fadeIn();

			// Refresh any code editors in this tab.
			$pane.find( '.section-code' ).each( function() {

					var editor = $( this ).find( 'textarea' ).data( 'CodeMirrorInstance' );

					if ( editor ) {
						editor.refresh();
					}
				}
			);

		} );

		/**
		 * Handles displaying option sections that were
		 * found in local storage.
		 *
		 * In other words, if a user had an option section
		 * already open before refreshing the page, it will
		 * open back up again.
		 *
		 * @since @@name-framework 2.2.0
		 */
		$page.find( '.postbox' ).each( function() {

			var $postbox = $( this );

			if ( 'undefined' !== typeof localStorage && localStorage.getItem( 'tb-section-' + $postbox.attr( 'id' ) ) ) {

				$postbox.removeClass( 'closed' ).find( '.inner-section-content' ).show();

				// Refresh any code editor options
				$postbox.find( '.section-code' ).each( function() {

						var editor = $( this ).find( 'textarea' ).data( 'CodeMirrorInstance' );

						if ( editor ) {
							editor.refresh();
						}
					}
				);
			}

		} );

		/**
		 * Handles toggling the display of option
		 * sections.
		 *
		 * @since @@name-framework 2.2.0
		 */
		$page.find( '.postbox > .section-toggle' ).on( 'click', function() {

			var $toggle  = $( this ),
				$postbox = $toggle.closest( '.postbox' );

			if ( $postbox.hasClass( 'closed' ) ) {

				$postbox.removeClass( 'closed' ).find( '.inner-section-content' ).show();

				if ( 'undefined' !== typeof localStorage ) {
					localStorage.setItem( 'tb-section-' + $postbox.attr( 'id' ), true );
				}

				// Refresh any code editor options.
				$postbox.find( '.section-code' ).each( function() {

						var editor = $( this ).find( 'textarea' ).data( 'CodeMirrorInstance' );

						if ( editor ) {
							editor.refresh();
						}
					}
				);

			} else {

				$postbox.addClass( 'closed' ).find( '.inner-section-content' ).hide();

				if ( 'undefined' !== typeof localStorage ) {
					localStorage.removeItem( 'tb-section-' + $postbox.attr( 'id' ) );
				}

			}

		} );

		/**
		 * Handles applying a preset style to the theme
		 * options page.
		 *
		 * @since @@name-framework 2.6.1
		 */
		$page.find( '.tb-presets a' ).on( 'click', function() {

			var $link = $( this );
				$form = $link.closest( 'form' );

			admin.confirm( l10n.preset, { 'confirm': true }, function( response ) {

				if ( response ) {

					$form.append( '<input name="_tb_set_preset" value="' + $link.data( 'set' ) + '" type="hidden" />' ).submit();

				}

			} );

		} );

		/**
		 * Handles clearing all options from the database, for
		 * the given options page.
		 *
		 * @since @@name-framework 2.2.0
		 */
		$page.find( '.clear-button' ).on( 'click', function( event ){

			event.preventDefault();

			admin.confirm( '<h3>' + l10n.clear_title + '</h3>' + l10n.clear, { 'confirm': true }, function( response ) {

				if ( response ) {

		        	var $form    = $page.find( '#tb-options-page-form' ),
		        		optionID = $form.find( 'input[name="option_page"]' ).val();

		        	/*
					 * Clear form's action so we don't go to options.php
		        	 * and WP Settings API handling.
					 */
		        	$form.attr( 'action', '' );

		        	// Add in reset so our sanitization callback recognizes.
		        	$form.append( '<input type="hidden" name="themeblvd_clear_options" value="' + optionID + '" />' );

		        	$form.submit();

		        }

		    } );

		} );

		// Setup options with $.fn.themeblvd namspace.

		$options.themeblvd( 'init' );

		$options.themeblvd( 'options', 'bind' );

		$options.themeblvd( 'options', 'setup' );

		$options.themeblvd( 'options', 'media-uploader' );

		$options.themeblvd( 'options', 'editor' );

		$options.themeblvd( 'options', 'code-editor' );

		$options.themeblvd( 'options', 'column-widths' );

		$options.themeblvd( 'options', 'sortable' );

	} );

} )( jQuery, window.themeblvd, themeblvdL10n );
