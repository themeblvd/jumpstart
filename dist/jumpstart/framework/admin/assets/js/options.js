/**
 * Options System
 *
 * @package Jump_Start
 * @subpackage Theme_Blvd
 * @license GPL-2.0+
 */

/**
 * Add the options objects to the global
 * Theme Blvd admin object.
 *
 * @since Theme_Blvd 2.7.0
 *
 * @var {object}
 */
window.themeblvd.options = {};

/**
 * Options System: General Setup
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 * @param {object} l10n  Localized admin text strings.
 */
( function( $, admin, l10n ) {

	var $body = $( 'body' );

	/**
	 * Handles general option setup, called from the
	 * jQuery `themeblvd` namespace.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @param {object} element this
	 */
	admin.options.setup = function( element ) {

		var $element = $( element );

		/**
		 * Option subgroup, show/hide checkbox.
		 *
		 * Within a subgroup with class `show-hide` a checkbox
		 * with class `trigger` can toggle any options with
		 * class `receiver` to show or hide, depending on if that
		 * checkbox is checked.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.find( '.show-hide' ).each( function() {

			var $subgroup = $( this ),
				$checkbox = $subgroup.children( '.trigger' ).find( 'input' );

			if ( $checkbox.is( ':checked' ) ) {

				$subgroup.children( '.receiver' ).show();

			} else {

				$subgroup.find( '.receiver' ).each( function() {
					$( this ).find( 'input, textarea, select' ).prop( 'disabled', true );
				} );

				$subgroup.children( '.receiver' ).hide();

			}

		} );

		/**
		 * Option subgroup, show/hide <select> menu or radio
		 * button group.
		 *
		 * Within a subgroup with class `show-hide-toggle` a
		 * <select> menu or radio button group with class `trigger`
		 * can toggle any options with class `receiver-{value}`
		 * to show or hide, depending on selection.
		 *
		 * Note: All subgroup options that are NOT the trigger
		 * must have both class `receiver` and `receiver-{value}`.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.find( '.show-hide-toggle' ).each( function() {

			var $subgroup = $( this ),
				$trigger  = $subgroup.children( '.trigger' ),
				value     = '';

			if ( $trigger.hasClass( 'section-radio' ) ) {
				value = $trigger.find( '.of-radio:checked' ).val();
			} else {
				value = $trigger.find( '.of-input' ).val();
			}

			$subgroup.children( '.receiver' ).each( function() {
				$( this ).hide().find( 'input, textarea, select' ).prop( 'disabled', true );
			} );

			$subgroup.children( '.receiver-' + value).each( function() {
				$( this ).show().find( 'input, textarea, select' ).prop( 'disabled', false );
			} );

		});

		/**
		 * Option subgroup, show/hide descriptions.
		 *
		 * When multiple descriptions exist for options within
		 * a `.desc-toggle` subgroup, they can be toggled
		 * based on the selection of a `.trigger` option.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.find( '.desc-toggle' ).each( function() {

			var $subgroup = $( this ),
				value     = $subgroup.children( '.trigger' ).find( '.of-input' ).val();

			$subgroup.find( '.desc-receiver .explain' ).hide();

			$subgroup.find( '.desc-receiver .explain.' + value ).show();

		} );

		/**
		 * Sets up the type of content to populate a content
		 * area.
		 *
		 * Used by theme to setup the content of a footer column
		 * on theme options page.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.find( '.section-content' ).each( function() {

			var $section = $( this ),
				type     = $section.find( '.column-content-types select.select-type' ).val();

			$section.find( '.column-content-type' ).hide();

			$section.find( '.column-content-type-' + type ).show();

		} );

		/**
		 * When configuring a group of columns, determines
		 * the amount of columns that will show from the
		 * user selection.
		 *
		 * Also, the number selected determines whether to show
		 * the option to control columns widths.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.find( '.columns' ).each( function() {

			var $select = $( this ),
				num     = $select.find( '.select-col-num' ).val();

			if ( num > 1 ) {

				$select.find( '.select-wrap-grid' ).show();

				$select.find( '.section-column_widths' ).show();

				$select.closest( '.widget-content' ).find( '.column-height' ).show();

			} else {

				$select.find( '.select-wrap-grid' ).hide();

				$select.find( '.section-column_widths' ).hide();

				$select.closest( '.widget-content' ).find( '.column-height' ).hide();

			}

			$select.find( '.section-content' ).hide();

			for ( var i = 1; i <= num; i++) {
				$select.find( '.col_' + i ).show();
			}

		} );

		/**
		 * Set up a `logo` type option.
		 *
		 * This option is meant specifically for setting up the
		 * branding logo for a website and is used in the
		 * framework's default theme options for the main site
		 * logo.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.find( '.section-logo' ).each( function() {

			var $parent = $( this ),
				value   = $parent.find( '.select-type select' ).val();

			$parent.find( '.logo-item' ).hide();

			$parent.find( '.' + value ).show();

		} );

		/**
		 * Within `typography` type option, allow user to toggle
		 * open the Google and Typekit font inputs, based on the
		 * type of font they've selected.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.find( '.section-typography .of-typography-face' ).each( function() {

			var $select = $( this );

			$select.closest( '.section-typography' ).find( '.google-font' ).hide();

			$select.closest( '.section-typography' ).find( '.typekit-font' ).hide();

			$select.closest( '.section-typography' ).find( '.' + $select.val() + '-font' ).fadeIn( 'fast' );

		} );

		/**
		 * Handles the `images` type option.
		 *
		 * This is basically just a radio button group, using
		 * images to toggle the values of hidden radio inputs.
		 */
		$element.find( '.of-radio-img-label' ).hide();

		$element.find( '.of-radio-img-img' ).show();

		$element.find( '.of-radio-img-radio' ).hide();

		/**
		 * Handles background configuration specifically when
		 * the parallax option is supported and the
		 * `background-attachment` option is changed.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		$element.find( '.select-parallax' ).each( function() {

			var $select = $( this ),
				value   = $select.find( '.of-background-attachment' ).val();

			if ( 'parallax' === value ) {

				$select.find( '.tb-background-repeat' ).hide();

				$select.find( '.tb-background-position' ).hide();

				$select.find( '.tb-background-size' ).hide();

				$select.find( '.parallax' ).show();

			} else {

				$select.find( '.tb-background-repeat' ).show();

				$select.find( '.tb-background-position' ).show();

				$select.find( '.tb-background-size' ).show();

				$select.find( '.parallax' ).hide();

			}

		} );

		/**
		 * Handles general background configuration when the
		 * `background-repeat` option is changed.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.find( '.of-background-properties' ).each( function() {

			var $select        = $( this ),
				repeatValue    = $select.find( '.of-background-repeat' ).val(),
				scrollingValue = $select.find( '.of-background-attachment' ).val();

			if ( 'no-repeat' === repeatValue && 'parallax' !== scrollingValue ) {

				$select.find( '.of-background-size' ).show();

			} else {

				$select.find( '.of-background-size' ).hide();

			}

		} );

		/**
		 * Sets up a `slide` type option.
		 *
		 * This refers specifically to the jQuery UI slider.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		if ( $.isFunction( $.fn.slider ) ) {

			$element.find( '.jquery-ui-slider' ).each( function() {

				var $slider = $( this ),
					$input  = $slider.closest( '.jquery-ui-slider-wrap' ).find( '.slider-input' ),
					units   = $slider.data( 'units' );

				$slider.slider( {
					min: $slider.data( 'min' ),
					max: $slider.data( 'max' ),
					step: $slider.data( 'step' ),
					value: parseInt( $input.val() ),
					create: function( event, ui ) {

						$slider.find( '.ui-slider-handle' ).append( '<span class="display-value"><span class="display-value-text"></span><span class="display-value-arrow"></span></span>' );

						var $display = $slider.find( '.display-value' );

						$display.css( 'margin-left', '-' + ( $display.outerWidth() / 2 ) + 'px' );

						$display.find( '.display-value-text' ).text( $input.val() );

					},
					slide: function( event, ui ) {

						$input.val( ui.value + units );

						$slider.find( '.display-value-text' ).text( ui.value + units );

					}
				} );

			} );

		} // End check for $.fn.slider

		/**
		 * Sets up a `color` type option.
		 *
		 * This refers specifically to WordPress built-in color
		 * picker set up with $.fn.wpColorPicker.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		if ( $.isFunction( $.fn.wpColorPicker ) ) {

			$element.find( '.tb-color-picker' ).wpColorPicker( {
				change: function( event, ui ) {

					/*
					 * Trigger custom event `themeblvd-color-change` that we
					 * can bind to from options-bind.js.
					 */
					$( event.target ).trigger( 'themeblvd-color-change' );

				}
			} );

		} // End check for $.fn.wpColorPicker

		/**
		 * Sets up a `button` type option and WordPress color
		 * pickers within it.
		 *
		 * This refers specifically to WordPress built-in color
		 * picker set up with $.fn.wpColorPicker.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		if ( $.isFunction( $.fn.wpColorPicker ) ) {

			$element.find( '.section-button' ).each( function() {

				var $parent = $( this );

				$parent.find( '.color-picker' ).wpColorPicker();

				$parent.find( '.color.bg .wp-color-result-text' ).text( l10n.bg_color );

				$parent.find( '.color.bg_hover .wp-color-result-text' ).text( l10n.bg_color_hover );

				$parent.find( '.color.border .wp-color-result-text' ).text( l10n.border_color );

				$parent.find( '.color.text .wp-color-result-text' ).text( l10n.text_color );

				$parent.find( '.color.text_hover .wp-color-result-text' ).text( l10n.text_color_hover );

				// Show or hide the background color selection.
				if ( $parent.find( '.include.bg input' ).is( ':checked' ) ) {
					$parent.find( '.color.bg' ).show();
				}

				// Show or hide the border color selection.
				if ( $parent.find( '.include.border input' ).is( ':checked' ) ) {
					$parent.find( '.color.border' ).show();
				}

			} );

		} // End check for $.fn.wpColorPicker

		/**
		 * Sets up any form fields that require an icon
		 * browser, for FontAwesome icon selection.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		$element.find( '.tb-input-icon-link' ).themeblvd( 'modal', null, {
			build: false,
			padding: true,
			size: 'custom', // Something other than `large` to trigger auto height.
			onDisplay: function( self ) {

				var $input   = self.$element,
					$browser = self.$modalWindow,
					icon     = $input.closest( '.input-wrap' ).find( 'input' ).val();

				// Reset icon browser.
				$browser.find( '.media-frame-content' ).scrollTop( 0 );

				$browser.find( '.icon-search-input' ).val( '' );

				$browser.find( '.select-icon' ).removeClass( 'selected' ).show();

				$browser.find( '.media-toolbar-secondary' ).find( 'i, span, .svg-inline--fa' ).remove();

				// If valid icon exists in text input, apply the selection.
				if ( $browser.find( '[data-icon="' + icon + '"]' ).length > 0 ) {

					$browser.find( '[data-icon="' + icon + '"]' ).addClass( 'selected' );

					$browser.find( '.icon-selection' ).val( icon );

					$browser.find( '.media-toolbar-secondary' ).append( '<i class="' + icon + ' fa-2x fa-fw"></i><span>' + icon + '</span>' );

				}

			},
			onSave: function( self ) {

				var icon = self.$modalWindow.find( '.icon-selection' ).val();

				// Send icon name back to input.
				self.$element.closest( '.input-wrap' ).find( 'input' ).val( icon );

			}
		});

		/**
		 * Sets up form fields that require the post ID
		 * browser.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		$element.find( '.tb-input-post-id-link' ).themeblvd( 'modal', null, {
			build: false,
			padding: true,
			button: '',
			size: 'custom', // Something other than `large` to trigger auto height.
			onDisplay: function( self ) {

				var $input   = self.$element,
					$browser = self.$modalWindow;

				// Bind search ajax.
				$browser.find( '#search-submit' ).off( 'click.tb-search-posts' );

				$browser.find( '#search-submit' ).on( 'click.tb-search-posts', function( event ) {

					event.preventDefault();

					var $search = $( this ).closest( '.post-browser-head' ),
						data = {
							action: 'themeblvd_post_browser',
							data: $search.find( '#post-search-input' ).val()
						};

					$search.find( '.tb-loader' ).fadeIn( 200 );

					$.post( ajaxurl, data, function( response ) {

						$browser.find( '.ajax-mitt' ).html( '' ).append( response );

						$search.find( '.tb-loader' ).fadeOut( 200 );

					} );

				} );

				// Select a post and close modal.
				$browser.off( 'click.tb-select-post', '.select-post' );

				$browser.on( 'click.tb-select-post', '.select-post', function( event ) {

					event.preventDefault();

					$browser.find( '#post-search-input' ).val( '' );

					$browser.find( '.ajax-mitt' ).html( '' );

					$input.closest( '.input-wrap' ).find( '.of-input' ).val( $( this ).data( 'post-id' ) );

					self.close( self );

				} );

			},
			onCancel: function( self ) {

				$browser = self.$modalWindow;

				$browser.find( '#post-search-input' ).val( '' );

				$browser.find( '.ajax-mitt' ).html( '' );

			}
		} );

		/**
		 * Sets up form fields that require the texture browser,
		 * to select a framework texture.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		$element.find( '.tb-texture-browser-link' ).themeblvd( 'modal', null, {
			build: false,
			padding: true,
			size: 'custom', // Something other than `large` to trigger auto height.
			onDisplay: function( self ) {

				var $input   = self.$element,
					$browser = self.$modalWindow,
					texture  = $input.closest( '.controls' ).find( '.of-input' ).val();

				$browser.find( '.media-frame-content' ).scrollTop( 0 );

				$browser.find( '.select-texture' ).each( function() {

					$link = $( this );

					$link.removeClass( 'selected' );

					if ( texture === $link.data( 'texture' ) ) {

						$link.addClass( 'selected' );

						$browser.find( '.texture-selection' ).val( $link.data( 'texture' ) );

						$browser.find( '.current-texture' ).text( $link.data( 'texture-name' ) );

					}

				} );

			},
			onSave: function( self ) {

				var $select = self.$element.closest( '.controls' ).find( '.of-input' ),
					texture = self.$modalWindow.find( '.texture-selection' ).val();

				// Send texture back to original <select>.
				$select.val( texture );

			}
		} );

		/**
		 * Sets up footer sync option on theme options page.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		if ( $element.find( '#tb-footer-sync' ).is( ':checked' ) ) {

			$element.find( '.standard-footer-setup' ).hide();

			$element.find( '.footer-template-setup' ).show();

		} else {

			$element.find( '.standard-footer-setup' ).show();

			$element.find( '.footer-template-setup' ).hide();

		}

	};

} )( jQuery, window.themeblvd, themeblvdL10n );

/**
 * Options System: General Event-Binding
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 */
( function( $, admin ) {

	/**
	 * Handles general option binding, called from the
	 * jQuery `themeblvd` namespace.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @param {object} element this
	 */
	admin.options.bind = function( element ) {

		var $element = $( element ),
			$body    = $( 'body' );

		/**
		 * Bind modals by default to all links with `tb-modal-link`
		 * class.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		$element.find( '.tb-modal-link' ).themeblvd( 'modal' );

		/**
		 * Set up tooltips.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		if ( ! admin.isMobile( $body ) ) {

			/**
			 * Bind tooltips to all links with `tb-tooltip-link`
			 * class.
			 *
			 * @since Theme_Blvd 2.5.0
			 */
			$element.on( 'mouseenter', '.tb-tooltip-link', function() {

				var $link    = $( this ),
					position = $link.data( 'tooltip-position' ),
					x        = $link.offset().left,
					y        = $link.offset().top,
					text     = $link.data( 'tooltip-text' ),
					markup   = '<div class="themeblvd-tooltip %position%"> \
								   <div class="tooltip-inner"> \
									 %text% \
								   </div> \
								   <div class="tooltip-arrow"></div> \
								</div>';

				// Check for text toggle.
				if ( ! text && $link.data( 'tooltip-toggle' ) ) {
					text = $link.data( 'tooltip-text-' + $link.data( 'tooltip-toggle' ) );
				}

				// If no text found at data-tooltip-text, then pull from title.
				if ( ! text ) {
					text = $link.attr( 'title' );
				}

				// If no position found at data-tooltip-position, set to "top".
				if ( ! position ) {
					position = 'top';
				}

				// Setup markup
				markup = markup.replace( '%position%', position );
				markup = markup.replace( '%text%', text );

				// Append tooltip to page
				$body.append( markup );

				// Setup and display tooltip
				var $tooltip       = $( '.themeblvd-tooltip' ),
					tooltip_height = $tooltip.outerHeight(),
					tooltip_width  = $tooltip.outerWidth();

				// Position of tooltip relative to $link.
				switch ( position ) {

					case 'left' :
						x = x - tooltip_width - 5; // 5px for arrow.
						y = y + ( .5 * $link.outerHeight() );
						y = y - tooltip_height / 2;
						break;

					case 'right' :
						x = x + $link.outerWidth() + 5; // 5px for arrow.
						y = y + ( .5 * $link.outerHeight() );
						y = y - tooltip_height / 2;
						break;

					case 'bottom' :
						x = x + ( .5 * $link.outerWidth() );
						x = x - tooltip_width / 2;
						y = y + $link.outerHeight() + 2;
						break;

					case 'top' :
					default :
						x = x + ( .5 * $link.outerWidth() );
						x = x - tooltip_width / 2;
						y = y - tooltip_height - 2;
				}

				$tooltip.css( {
					'top': y + 'px',
					'left': x + 'px'
				} ).addClass( 'fade in' );
			} );

			$element.on( 'mouseleave', '.tb-tooltip-link', function() {
				$( '.themeblvd-tooltip' ).remove();
			} );

			/**
			 * Remove any active tooltips if a link is clicked.
			 *
			 * @since Theme_Blvd 2.5.0
			 */
			$element.find( '.tb-tooltip-link' ).on( 'click', function() {

				var $link  = $( this ),
					toggle = $link.data( 'tooltip-toggle' );

				$( '.themeblvd-tooltip' ).remove();

				if ( 2 == toggle ) {
					$link.data( 'tooltip-toggle', 1);
				} else {
					$link.data( 'tooltip-toggle', 2);
				}

			} );

		} // End check for isMobile().

		/**
		 * Option subgroup, show/hide checkbox.
		 *
		 * Within a subgroup with class `show-hide` a checkbox
		 * with class `trigger` can toggle any options with
		 * class `receiver` to show or hide, depending on if that
		 * checkbox is checked.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.on( 'click', '.show-hide > .trigger input', function() {

			var $checkbox = $( this );

			if ( $checkbox.is( ':checked' ) ) {

				$checkbox.closest( '.show-hide' ).find( '.receiver' ).each( function() {

					$( this ).find( 'input, textarea, select' ).prop( 'disabled', false );

				} );

				$checkbox.closest( '.show-hide' ).children( '.receiver' ).fadeIn( 'fast' );

			} else {

				$checkbox.closest( '.show-hide' ).find( '.receiver' ).each( function() {

					$( this ).find( 'input, textarea, select' ).prop( 'disabled', true );

				} );

				$checkbox.closest( '.show-hide' ).children( '.receiver' ).hide();

			}

		} );

		/**
		 * Option subgroup, show/hide <select> menu.
		 *
		 * Within a subgroup with class `show-hide-toggle` a
		 * <select> menu with class `trigger` can toggle any
		 * options with class `receiver-{value}` to show or hide,
		 * depending on selection.
		 *
		 * Note: All subgroup options that are NOT the trigger
		 * must have both class `receiver` and `receiver-{value}`.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.on( 'change', '.show-hide-toggle > .trigger select.of-input', function() {

			var $select    = $( this ),
				value      = $select.val(),
				$subgroup  = $select.closest( '.show-hide-toggle' );

			$subgroup.children( '.receiver' ).each( function() {

				$( this ).hide().find( 'input, textarea, select' ).prop( 'disabled', true );

			} );

			$subgroup.children( '.receiver-' + value ).each( function() {

				$( this ).show().find( 'input, textarea, select' ).prop( 'disabled', false );

			} );


		} );

		/**
		 * Option subgroup, show/hide radio button group.
		 *
		 * Within a subgroup with class `show-hide-toggle` a
		 * radio button set with class `trigger` can toggle any
		 * options with class `receiver-{value}` to show or hide,
		 * depending on selection.
		 *
		 * Note: All subgroup options that are NOT the trigger
		 * must have both class `receiver` and `receiver-{value}`.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.on( 'click', '.show-hide-toggle > .trigger .of-radio', function() {

			var $radio    = $( this ),
				value     = $radio.val(),
				$subgroup = $radio.closest( '.show-hide-toggle' );

			$subgroup.children( '.receiver' ).each( function() {

				$( this ).hide().find( 'input, textarea, select' ).prop( 'disabled', true );

			} );

			$subgroup.children( '.receiver-' + value ).each( function() {

				$( this ).show().find( 'input, textarea, select' ).prop( 'disabled', false );

			} );

		} );

		/**
		 * Option subgroup, show/hide descriptions.
		 *
		 * When multiple descriptions exist for options within
		 * a `.desc-toggle` subgroup, they can be toggled
		 * based on the selection of a `.trigger` option.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.on( 'change', '.desc-toggle > .trigger .of-input', function() {

			var $input    = $( this ),
				value     = $input.val(),
				$subgroup = $input.closest( '.desc-toggle' );

			$subgroup.find( '.desc-receiver .explain' ).hide();

			$subgroup.find( '.desc-receiver .explain.' + value ).show();

		} );

		/**
		 * Select the type of content to populate a content
		 * area.
		 *
		 * Used by theme to setup the content of a footer column
		 * on theme options page.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.on( 'change', '.column-content-types select.select-type', function() {

			var $select  = $( this ),
				$section = $select.closest( '.section-content' ),
				type     = $select.val();

			$section.find( '.column-content-type' ).hide();

			$section.find( '.column-content-type-' + type ).show();

		} );

		/**
		 * When configuring a group of columns, determines
		 * the amount of columns that will show from the
		 * user selection.
		 *
		 * Also, the number selected determines whether to show
		 * the option to control columns widths.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.on( 'change', '.select-col-num', function() {

			var $select    = $( this ),
				num        = $select.val(),
				$container = $select.closest( '.columns' );

			if ( num > 1 ) {

				$container.find( '.select-wrap-grid' ).show();

				$container.find( '.section-column_widths' ).show();

				$container.closest( '.widget-content' ).find( '.column-height' ).show();

			} else {

				$container.find( '.select-wrap-grid' ).hide();

				$container.find( '.section-column_widths' ).hide();

				$container.closest( '.widget-content' ).find( '.column-height' ).hide();

			}

			$container.find( '.section-content' ).hide();

			for ( var i = 1; i <= num; i++ ) {

				$container.find( '.col_' + i ).show();

			}

		} );

		/**
		 * Handles a `logo` type option.
		 *
		 * This option is meant specifically for setting up the
		 * branding logo for a website and is used in the
		 * framework's default theme options for the main site
		 * logo.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.on( 'change', '.section-logo .select-type select', function() {

			var $select = $( this ),
				$parent = $select.closest( '.section-logo' ),
				value   = $select.val();

			$parent.find( '.logo-item' ).hide();

			$parent.find( '.' + value ).show();

		} );

		/**
		 * Within `typography` type option, allow user to toggle
		 * open the Google and Typekit font inputs, based on the
		 * type of font they've selected.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.on( 'change', '.section-typography .of-typography-face', function() {

			var $select = $( this );

			$select.closest( '.section-typography' ).find( '.google-font' ).hide();

			$select.closest( '.section-typography' ).find( '.typekit-font' ).hide();

			$select.closest( '.section-typography' ).find( '.' + $select.val() + '-font' ).fadeIn( 'fast' );

		} );

		/**
		 * Handles the `images` type option.
		 *
		 * This is basically just a radio button group, using
		 * images to toggle the values of hidden radio inputs.
		 */
		$element.find( '.tb-radio-img-img, .of-radio-img-img' ).on( 'click', function( event ) {

			event.preventDefault();

			var $img = $( this );

			$img.parent().parent().find( '.of-radio-img-img' ).removeClass( 'tb-radio-img-selected of-radio-img-selected' );

			$img.addClass( 'tb-radio-img-selected of-radio-img-selected' );

		} );

		/**
		 * Handles checkbox group of categories.
		 *
		 * When a user is presented with a checkbox group of
		 * WordPress categories, they will have the selection
		 * of "All" or individual categories.
		 *
		 * This script ensures that when "All" is selected
		 * the other aren't, and vise versa.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.on( 'click', '.select-categories input', function() {

			var $current = $( this ),
				$option  = $current.closest( '.controls' );

			if ( $current.prop( 'checked' ) ) {

				if ( $current.hasClass( 'all' ) ) {

					$option.find( 'input' ).each( function() {

						var $checkbox = $( this );

						if ( ! $checkbox.hasClass( 'all' ) ) {

							$checkbox.prop( 'checked', false );

						}

					} );

				} else {

					$option.find( 'input.all' ).prop( 'checked', false );

				}
			}

		} );

		/**
		 * Handles background configuration specifically when
		 * the parallax option is supported and the
		 * `background-attachment` option is changed.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		$element.on( 'change', '.select-parallax .of-background-attachment', function() {

			var $select = $( this ),
				$option = $select.closest( '.select-parallax' );

			if ( 'parallax' == $select.val() ) {

				$option.find( '.tb-background-repeat' ).hide();

				$option.find( '.tb-background-position' ).hide();

				$option.find( '.tb-background-size' ).hide();

				$option.find( '.parallax' ).show();

			} else {

				$option.find( '.tb-background-repeat' ).show();

				$option.find( '.tb-background-position' ).show();

				$option.find( '.tb-background-size' ).show();

				$option.find( '.parallax' ).hide();

			}

		} );

		/**
		 * Handles general background configuration when the
		 * `background-repeat` option is changed.
		 *
		 * @since Theme_Blvd 2.2.0
		 */
		$element.on( 'change', '.of-background-properties .of-background-repeat', function() {

			var $select        = $( this ),
				$option        = $select.closest( '.of-background-properties' ),
				repeatValue    = $select.val(),
				scrollingValue = $option.find( '.of-background-attachment' ).val();

			if ( 'no-repeat' === repeatValue && 'parallax' !== scrollingValue ) {

				$option.find( '.tb-background-size' ).show();

			} else {

				$option.find( '.tb-background-size' ).hide();

			}

		} );

		/**
		 * Handles the `button` option type.
		 */
		$element.on( 'click', '.section-button .include input', function() {

			var $input = $( this ),
				type   = 'bg';

			if ( $input.closest( '.include' ).hasClass( 'border' ) ) {
				type = 'border';
			}

			if ( $input.is( ':checked' ) ) {
				$input.closest( '.section-button' ).find( '.color.' + type ).show();
			} else {
				$input.closest( '.section-button' ).find( '.color.' + type ).hide();
			}

		});

		/**
		 * Handles footer sync checkbox on theme options
		 * page.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		$element.find( '#tb-footer-sync' ).on( 'click', function() {

			if ( $( this ).is( ':checked' ) ) {

				$element.find( '.standard-footer-setup' ).hide();

				$element.find( '.footer-template-setup' ).show();

			} else {

				$element.find( '.standard-footer-setup' ).show();

				$element.find( '.footer-template-setup' ).hide();

			}

		} );

		/**
		 * Handles automated insertion of mape coordinates,
		 * utilizng the Google Maps API.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		if ( typeof google === 'object' && typeof google.maps === 'object' ) {

			$element.on( 'click', '.section-geo .geo-insert-lat-long', function( event ) {

				event.preventDefault();

				var $link     = $( this ),
					$overlay  = $link.closest( '.geo-generate' ).find( '.overlay' ),
					geocoder  = new google.maps.Geocoder(),
					address   = $link.prev( '.address' ).val(),
					latitude  = '0',
					longitude = '0';

				$overlay.fadeIn( 100 );

				geocoder.geocode( { 'address': address }, function( results, status ) {

					if ( status == google.maps.GeocoderStatus.OK ) {

						latitude = results[0].geometry.location.lat();

						longitude = results[0].geometry.location.lng();

					}

					setTimeout( function() {

						$link.closest( '.controls' ).find( '.geo-lat .geo-input' ).val(latitude);

						$link.closest( '.controls' ).find( '.geo-long .geo-input' ).val(longitude);

						if ( status != google.maps.GeocoderStatus.OK ) {
							admin.confirm( $link.data( 'oops' ), { 'textOk':'Ok' } );
						} else {
							$link.prev( '.address' ).val( '' );
						}

						$overlay.fadeOut( 100 );

					}, 1500 );

				} );

			} );

		} // End check for Google Maps API.

	};

} )( jQuery, window.themeblvd );

/**
 * Options System: Media Uploader
 *
 * @since Theme_Blvd 2.7.0
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
	 * @since Theme_Blvd 2.7.0
	 */
	admin.options.mediaUploader = {};

	/**
	 * Keeps track of any media frames created.
	 *
	 * @since Theme_Blvd 2.7.0
	 */
	admin.options.mediaUploader.frames = {};

	/**
	 * Handle initialization of `media-uploader`
	 * options component from the `themeblvd` jQuery
	 * namespace.
	 *
	 * @since Theme_Blvd 2.7.0
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
	 * @since Theme_Blvd 2.7.0
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
	 * @since Theme_Blvd 2.7.0
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
	 * @since Theme_Blvd 2.7.0
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

/**
 * Options System: Content Editor
 *
 * @since Theme_Blvd 2.7.0
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
	 * @since Theme_Blvd 2.7.0
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
		 * @since Theme_Blvd 2.7.0
		 */
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

/**
 * Options System: Column Widths
 *
 * This allows the user to use the jQuery ui slider
 * to adjust widths, for a set of columns.
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 * @param {object} l10n  Localized admin text strings.
 */
( function( $, admin, l10n ) {

	/**
	 * Sets up our column widths object.
	 *
	 * @since Theme_Blvd 2.7.0
	 */
	admin.options.columnWidths = {};

	/**
	 * Handle initialization of `column-widths`
	 * options component from the `themeblvd` jQuery
	 * namespace.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param {object} element this
	 */
	admin.options.columnWidths.init = function( element ) {

		$element = $( element );

		$element.find( '.section-column_widths' ).each( function() {

			admin.options.columnWidths.build( $( this ).closest( '.subgroup.columns' ) );

		} );

	};

	/**
	 * Builds each jQuery ui slider object to adjust column
	 * widths.
	 *
	 * @since Theme_Blvd 2.5.0
	 *
	 * @param {object} $section jQuery object that holds the option section.
	 */
	admin.options.columnWidths.build = function( $section ) {

		var id          = $section.find( '.slider' ).attr( 'id' ),
			grid        = 12, // 10 or 12
			columns     = 3, // 1-5
			total       = 0,
			fraction    = '',
			numerator   = 0,
			denominator = 0,
			current     = $section.find( '.column-width-input' ).val(),
			initValues  = [],
			defaults    = {};

		defaults = {
			10: {
				2: {
					values: [ 0, 5, 10 ],
					display: [ '1/2', '1/2' ]
				},
				3: {
					values: [ 0, 3, 7, 10 ],
					display: [ '3/10', '2/5', '3/10' ]
				},
				4: {
					values: [ 0, 2, 5, 8, 10 ],
					display: [ '1/5', '3/10', '3/10', '1/5' ]
				},
				5: {
					values: [ 0, 2, 4, 6, 8, 10 ],
					display: ['1/5', '1/5', '1/5', '1/5', '1/5' ]
				},
				6: {
					values: [ 0, 1, 3, 5, 7, 9, 10 ],
					display: [ '1/10', '1/5', '1/5', '1/5', '1/5', '1/10' ]
				}
			},
			12: {
				2: {
					values: [ 0, 6, 12 ],
					display: [ '1/2', '1/2' ]
				},
				3: {
					values: [ 0, 4, 8, 12 ],
					display: ['1/3', '1/3', '1/3' ]
				},
				4: {
					values: [ 0, 3, 6, 9, 12 ],
					display: [ '1/4', '1/4', '1/4', '1/4' ]
				},
				5: {
					values: [ 0, 2, 4, 8, 10, 12 ],
					display: [ '1/6', '1/6', '1/3', '1/6', '1/6' ]
				},
				6: {
					values: [ 0, 2, 4, 6, 8, 10, 12 ],
					display: [ '1/6', '1/6', '1/6', '1/6', '1/6', '1/6' ]
				}
			}
		};

		grid = $section.find( '.select-grid-system' ).val(); // 10 or 12

		columns = $section.find( '.select-col-num' ).val();

		$section.find( '.select-col-num' ).off( 'change.ui-slider' ); // Avoid duplicates.

		$section.find( '.select-col-num' ).on( 'change.ui-slider', admin.options.columnWidths.change );

		$section.find( '.select-grid-system' ).off( 'change.ui-slider' ); // Avoid duplicates.

		$section.find( '.select-grid-system' ).on( 'change.ui-slider', admin.options.columnWidths.change );

		// If one or no columns, don't run jQuery UI slider.
		if ( 0 == columns ) {

			return;

		} else if ( 1 == columns ) {

			$section.find( '.slider' ).append( '<div class="column-preview col-1" style="width:100%"><span class="text">100%</span></div>' );

			$section.find( '.column-width-input' ).val( '1/1' ).trigger( 'themeblvd-update-columns' );

			return;

		}

		if ( current ) {

			current = current.split( '-' );

			columns = current.length;

			for ( var i = 0; i <= columns; i++ ) {

				if ( 0 === i ) {

					initValues[ i ] = 0;

				} else if ( i == columns ) {

					initValues[ i ] = grid;

				} else {

					fraction = current[ i - 1 ].split( '/' );

					total += (grid * fraction[0]) / fraction[1];

					initValues[ i ] = total;

				}
			}
		} else {

			initValues = defaults[ grid ][ columns ]['values'];

			current = defaults[ grid ][ columns ]['display'];

			$section.find( '.column-width-input' ).val( current.join( '-' ) ).trigger( 'themeblvd-update-columns' );

		}

		// Setup jQuery UI slider instance
		$( '#' + id ).slider( {
			range: 'max',
			min: 0,
			max: grid,
			step: 1,
			values: initValues,
			create: function( event, ui ) {

				var i           = 0,
					left        = 0,
					width       = 0,
					gridDisplay = '';

				/*
				 * Setup display columns with visible fractions
				 * for the user.
				 */
				for ( i = 1; i <= columns; i++ ) {

					$section.find( '.slider' ).append( '<div class="column-preview col-' + i + '"><span class="text">' + current[ i - 1 ] + '</span></div>' );

					width = ( ( initValues[ i ] - initValues[ i - 1 ] ) / grid ) * 100;

					$section.find( '.col-' + i ).css( 'width', width + '%' );

					if ( i > 1 ) {
						$section.find( '.col-' + i ).css( 'left', left + '%' );
					}

					left += width;
				}

				// Add grid presentation.
				left = 0;

				gridDisplay = '<div class="grid-display grid-' + grid + '">';

				for ( i = 1; i <= grid; i++ ) {
					gridDisplay += '<div class="grid-column grid-col-' + i + '"></div>';
				}

				gridDisplay += '</div>';

				$section.find( '.slider' ).append( gridDisplay );

				for ( i = 1; i <= grid; i++ ) {

					left += ( ( 1 / grid ) * 100 );

					$section.find( '.grid-col-' + i ).css( 'left', left + '%' );

				}

			},
			slide: function( event, ui ) {

				var index  = $( ui.handle ).index(),
					values = ui.values,
					count  = values.length;

				// First and last can't be moved.
				if ( 1 == index || count == index ) {
					return false;
				}

				var $container      = $( ui.handle ).closest( '.column-widths-wrap' ),
					$option         = $container.find( '.column-width-input' ),
					currentVal      = ui.value,
					nextVal         = values[ index ],
					prevVal         = values[ index - 2 ],
					nextCol         = 0,
					prevCol         = 0,
					prevColFraction = '',
					nextColFraction = '',
					nextNumerator   = 0,
					prevNumerator   = 0,
					prevFinal       = '',
					finalVal        = '',
					finalFractions  = [],
					left            = 0,
					width           = 0;

				// Do not allow handles to pass or touch each other.
				if ( currentVal <= prevVal || currentVal >= nextVal ) {
					return false;
				}

				// Size columns before and after handle.
				prevNumerator = currentVal - prevVal;

				nextNumerator = nextVal - currentVal;

				prevCol = index - 1;

				nextCol = index;

				// Reduce previous column fraction.
				prevColFraction = admin.options.columnWidths.reduceFraction( prevNumerator, grid );

				prevColFraction = prevColFraction[0].toString() + '/' + prevColFraction[1].toString();

				// Reduce next column fraction
				nextColFraction = admin.options.columnWidths.reduceFraction( nextNumerator, grid );

				nextColFraction = nextColFraction[0].toString() + '/' + nextColFraction[1].toString();

				// Set hidden fraction placeholders for reference.
				$container.find( 'input[name="col[' + prevCol + ']"]' ).val( prevColFraction );

				$container.find( 'input[name="col[' + nextCol + ']"]' ).val( nextColFraction );

				// Update final option.
				prevFinal = $option.val();

				prevFinal = prevFinal.split( '-' );

				for ( var i = 1; i <= prevFinal.length; i++ ) {

					if ( i == prevCol ) {
						finalVal += prevColFraction;
					} else if ( i == nextCol ) {
						finalVal += nextColFraction;
					} else {
						finalVal += prevFinal[i-1];
					}

					if ( i != prevFinal.length ) {
						finalVal += '-';
					}

				}

				$option.val( finalVal ).trigger( 'themeblvd-update-columns' );

				/*
				 * Re-set display columns with visible fractions
				 * for the user.
				 */
				finalFractions = finalVal.split( '-' );

				for ( i = 1; i <= columns; i++ ) {

					width = ((values[i]-values[i-1])/grid)*100;

					$section.find( '.col-' + i ).css( 'width', width + '%' );

					$section.find( '.col-' + i + ' .text' ).text( finalFractions[ i - 1 ] );

					if ( i > 1 ) {
						$section.find( '.col-' + i ).css( 'left', left + '%' );
					}

					left += width;

				}
			}

		} );

	};

	/**
	 * Adjust the number of columns or grid system based
	 * on the <select> menu choice, and then re-build.
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	admin.options.columnWidths.change = function() {

		var $select = $( this ),
			$slider = $( '#' + $select.data( 'slider' ) );

		if ( $slider.data( 'uiSlider' ) ) {
			$slider.slider( 'destroy' );
		}

		$slider.html( '' ).closest( '.column-widths-wrap' ).find( '.column-width-input' ).val( '' );

		admin.options.columnWidths.build( $select.closest( '.subgroup.columns' ) );

	};

	/**
	 * Mathamatically reduce a fraction, by finding the greatest
	 * common denominator.
	 *
	 * @since Theme_Blvd 2.5.0
	 */
	admin.options.columnWidths.reduceFraction = function( numerator, denominator ) {

		var gcd = function gcd( a, b ) {
			return b ? gcd( b, a % b ) : a;
		};

		gcd = gcd( numerator, denominator );

		return [ numerator / gcd, denominator / gcd ];

	};

} )( jQuery, window.themeblvd, themeblvdL10n );

/**
 * Options System: Sortables
 *
 * @since Theme_Blvd 2.7.0
 *
 * @param {jQuery} $     jQuery object.
 * @param {object} admin Theme Blvd admin object.
 * @param {object} l10n  Localized admin text strings.
 */
( function( $, admin, l10n ) {

	/**
	 * Sets up our sortable object.
	 *
	 * @since Theme_Blvd 2.7.0
	 */
	admin.options.sortable = {};

	/**
	 * Handle initialization of `sortabler` options
	 * component from the `themeblvd` jQuery namespace.
	 *
	 * @since Theme_Blvd 2.7.0
	 *
	 * @param {object} element this
	 */
	admin.options.sortable.init = function( element ) {

		var $element = $( element );

		/**
		 * Sets up the actual sortable option type.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		$element.find( '.tb-sortable-option' ).each( function() {

			var $option  = $( this ),
				$section = $option.closest( '.section-sortable' ),
				max      = $option.data( 'max' );

			// Setup sortable items
			$section.find( '.item' ).each( function() {
				admin.options.sortable.setupItem( $( this ) );
			} );

			if ( $option.find( '.item-container .item' ).length ) {
				$option.find( '.delete-sortable-items' ).show();
			}

			$section.find( '.item-container' ).sortable( {
				handle: '.item-handle',
				stop: function( event, ui ) {
					ui.item.themeblvd( 'options', 'editor' );
				}
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

					window.setTimeout( function() {

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

				admin.confirm( $link.attr( 'title' ), { 'confirm': true }, function( response ) {

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
	 * @since Theme_Blvd 2.5.0
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

/**
 * Options System: Browser Modal Setup
 *
 * @param {jQuery} $ jQuery object.
 */
( function( $ ) {

	$( document ).ready( function( $ ) {

		/**
		 * Setup icon browser.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		var $iconBrowser = $( '.themeblvd-icon-browser' );

		$iconBrowser.themeblvd( 'options', 'setup' );

		$iconBrowser.themeblvd( 'options', 'bind' );

		$iconBrowser.find( '.select-icon' ).on( 'click', function( event ){

			event.preventDefault();

			var $btn     = $( this ),
				$browser = $btn.closest( '.themeblvd-icon-browser' ),
				icon     = $btn.data( 'icon' );

			$browser.find( '.select-icon' ).removeClass( 'selected' );

			$btn.addClass( 'selected' );

			$browser.find( '.icon-selection' ).val( icon );

			$browser.find( '.icon-selection-wrap' ).find( 'i, span, .svg-inline--fa' ).remove();

			$browser
				.find( '.icon-selection-wrap' )
				.append( '<i class="' + icon + ' fa-2x fa-fw"></i><span>' + icon + '</span>' );

		} );

		$iconBrowser.find( '.icon-search-input' ).on( 'keyup', function( event ) {

			var value   = $( this ).val(),
				results = [];

			if ( ! value ) {
				$iconBrowser.find( '.select-icon' ).show();
				return;
			}

			$iconBrowser.find( '.select-icon' ).hide();

			if ( 'undefined' !== typeof themeblvdIconSearchData ) {

				$.each( themeblvdIconSearchData, function( name, terms ) {

					var i, term;

					for ( i = 0; i < terms.length; i++ ) {

						term = terms[ i ];

						if ( 0 === term.indexOf( value ) ) {

							results.push( '.icon-' + name );

							i = terms.length; // End loop.

						}
					}

				} );

			}

			$iconBrowser.find( results.join() ).show();

		} );

		/**
		 * Setup texture browser.
		 *
		 * @since Theme_Blvd 2.5.0
		 */
		$( '.themeblvd-texture-browser' ).themeblvd( 'options', 'setup' );

		$( '.themeblvd-texture-browser' ).themeblvd( 'options', 'bind' );

		if ( $.isFunction( $.fn.wpColorPicker ) ) {
			$( '#texture-browser-perview-color' ).wpColorPicker( {
				change: function() {
					$( '.themeblvd-texture-browser .select-texture span' ).css( 'background-color', $( '#texture-browser-perview-color' ).val() );
				}
			} );
		}

		$( '.themeblvd-texture-browser .wp-color-result' ).attr( 'title', 'Temporary Preview Color' );

		$( '.themeblvd-texture-browser .select-texture span' ).css( 'background-color', $( '#texture-browser-perview-color' ).val() );

		$( '.themeblvd-texture-browser .select-texture' ).on( 'click', function( event ){

			event.preventDefault();

			var $btn = $( this );

			$btn.closest( '.themeblvd-texture-browser' ).find( '.select-texture' ).each( function() {
				$( this ).removeClass( 'selected' );
			} );

			$btn.addClass( 'selected' );

			$btn.closest( '.themeblvd-texture-browser' ).find( '.texture-selection' ).val( $btn.data( 'texture' ) );

			$btn.closest( '.themeblvd-texture-browser' ).find( '.current-texture' ).text( $btn.data( 'texture-name' ) );

		} );

	} );

} )( jQuery );
