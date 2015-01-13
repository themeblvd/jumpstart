/**
 * Prints out the inline javascript that is shared between all
 * framework admin areas.
 */

(function($){

	// Setup methods for themeblvd namespace
	var themeblvd_shared = {

    	// All general binded events
    	init: function()
    	{
    		var $this = this;

    		// Toggle widgets
    		$this.off('click.tb-widget');
    		$this.on('click.tb-widget', '.widget-name-arrow, .block-widget-name-arrow', function() {

				var $button = $(this),
					type = 'widget',
					closed = false;

				if ( $button.hasClass('block-widget-name-arrow') ) {
					type = 'block-widget';
				}

				if ( $button.closest('.'+type+'-name').hasClass(type+'-name-closed') ) {
					closed = true;
				}

				if ( closed ) {

					// Show widget
					$button.closest('.'+type).find('.'+type+'-content').show();
					$button.closest('.'+type).find('.'+type+'-name').removeClass(type+'-name-closed');

					// Refresh any code editor options
					$button.closest('.'+type).find('.section-code').each(function(){

						var $editor = $(this).find('textarea').data('CodeMirrorInstance');

						if ( $editor ) {
							$editor.refresh();
						}
					});

				} else {

					// Close widget
					$button.closest('.'+type).find('.'+type+'-content').hide();
					$button.closest('.'+type).find('.'+type+'-name').addClass(type+'-name-closed');
				}

				return false;
			});

			// Help tooltips
			$this.on( 'click', '.tooltip-link', function() {
				var message = $(this).attr('title');
				tbc_confirm(message, {'textOk':'Ok'});
				return false;
			});

			// Delete item by ID passed through link's href
			$this.on( 'click', '.delete-me', function() {
				var item = $(this).attr('href');
				tbc_confirm($(this).attr('title'), {'confirm':true}, function(r) {
			    	if(r) {
			        	$(item).remove();
			        }
			    });
			    return false;
			});

			// Fancy Select
			$this.find('.tb-fancy-select').each(function(){
				var el = $(this),
					value = el.find('select').val(),
					text = el.find('option[value="'+value+'"]').text();
				el.find('.textbox').text(text);
			});
    	},

    	// Setup custom options
    	options: function( type )
    	{
    		return this.each(function(){

				var $this = $(this);

	    		// Apply all actions that need applying when an
	    		// option set is loaded. This will be called any
	    		// time a new options set is inserted.
	    		if(type == 'setup') {

	    			// Fancy Select
					$this.find('.tb-fancy-select').each(function(){
						var el = $(this), value = el.find('select').val(), text = el.find('option[value="'+value+'"]').text();
						el.find('.textbox').text(text);
					});

	    			// Custom content
	    			$this.find('.custom-content-types').each(function(){
	    				var el = $(this), value = el.find('select').val(), parent = el.closest('.subgroup');

	    				if(value == 'external')
	    					parent.find('.page-content').show();
	    				else if (value == 'raw')
	    					parent.find('.raw-content').show();
	    				else if (value == 'widget_area')
	    					parent.find('.widget_area-content').show();

	    			});

	    			// Tabs only
	    			$this.find('.tabs').each(function(){
	    				var el = $(this), i = 1, num = el.find('.tabs-num').val();
	    				el.find('.tab-names .tab-name').hide();
	    				el.find('.section-content').hide();
	    				while (i <= num)
						{
							el.find('.tab-names .tab-name-'+i).show();
							el.find('#section-tab_'+i).show();
							i++;
	    				}
	    			});

	    			// Columns AND Tabs
	    			$this.find('.section-content').each(function(){
    					var section = $(this), type = section.find('.column-content-types select.select-type').val();
    					section.find('.column-content-type').hide();
    					section.find('.column-content-type-'+type).show();
    				});

    				// Show correct number of columns (theme options), and
    				// whether to show column widths option
					$this.find('.columns').each(function(){
	    				var $el = $(this), i, num = $el.find('.select-col-num').val();
	    				if ( num > 1 ) {
	    					$el.find('.select-wrap-grid').show();
	    					$el.find('.section-column_widths').show();
	    					$el.closest('.widget-content').find('.column-height').show();
	    				} else {
	    					$el.find('.select-wrap-grid').hide();
	    					$el.find('.section-column_widths').hide();
	    					$el.closest('.widget-content').find('.column-height').hide();
	    				}
	    				$el.find('.section-content').hide();
	    				for ( i = 1; i <= num; i++) {
							$el.find('.col_'+i).show();
	    				}
	    			});

    				// Show/Hide groupings
    				$this.find('.show-hide').each(function(){
    					var $el = $(this), checkbox = $el.children('.trigger').find('input');
    					if ( checkbox.is(':checked') ) {
    						$el.children('.receiver').show();
    					} else {
    						$el.find('.receiver').each(function(){
    							$(this).find('input, textarea, select').prop('disabled', true);
    						});
    					}
    				});

    				// Show/Hide groupings
    				$this.find('.hide-show').each(function(){
    					var $el = $(this), checkbox = $el.children('.trigger').find('input');
    					if ( checkbox.is(':checked') ) {
    						$el.children('.receiver').hide();
    					} else {
    						$el.find('.receiver').each(function(){
    							$(this).find('input, textarea, select').prop('disabled', true);
    						});
    					}
    				});

    				// Show/Hide toggle grouping (triggered with <select> or
    				// radio group to target specific options)
    				$this.find('.show-hide-toggle').each(function(){

    					var $el = $(this),
    						$trigger = $el.children('.trigger'),
    						value = '';

    					if ( $trigger.hasClass('section-radio') ) {
    						value = $trigger.find('.of-radio:checked').val();
    					} else {
    						value = $trigger.find('.of-input').val();
    					}

    					$el.children('.receiver').each(function(){
    						$(this).hide().find('input, textarea, select').prop('disabled', true);
    					});

    					$el.children('.receiver-'+value).each(function(){
    						$(this).show().find('input, textarea, select').prop('disabled', false);
    					});

    				});

    				// Where one option's value determines which description displays on another option
    				$this.find('.desc-toggle').each(function(){
    					var $el = $(this), value = $el.children('.trigger').find('.of-input').val();
    					$el.find('.desc-receiver .explain').hide();
    					$el.find('.desc-receiver .explain.'+value).show();
    				});

    				// Configure logo
    				$this.find('.section-logo').each(function(){
    					var el = $(this), value = el.find('.select-type select').val();
						el.find('.logo-item').hide();
						el.find('.'+value).show();
    				});

    				// Google font selection
    				$this.find('.section-typography .of-typography-face').each(function(){
    					var el = $(this), value = el.val();
    					if( value == 'google' )
    						el.closest('.section-typography').find('.google-font').fadeIn('fast');
    					else
    						el.closest('.section-typography').find('.google-font').hide();
    				});

    				// Homepage Content
	    			$this.find('#section-homepage_content').each(function(){
    					var value = $(this).find('input:checked').val();
    					if( value != 'custom_layout' )
    						$this.find('#section-homepage_custom_layout').hide();
    				});

    				// Image Options
					$this.find('.of-radio-img-img').click(function(){
						$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
						$(this).addClass('of-radio-img-selected');
					});
					$this.find('.of-radio-img-label').hide();
					$this.find('.of-radio-img-img').show();
					$this.find('.of-radio-img-radio').hide();


					// Match options
					$this.find('.match-trigger .of-input').each(function(){
						var $el = $(this);
						$el.closest('.subgroup').find('.match .of-input').val($el.val());
					});

					// Background options
					$this.find('.select-parallax').each(function(){

						var $el = $(this),
							val = $el.find('.of-background-attachment').val();

						if ( val == 'parallax' ) {
							$el.find('.of-background-position').closest('.tb-fancy-select').hide();
							$el.find('.of-background-size').closest('.tb-fancy-select').hide();
							$el.find('.parallax').show();
						} else {
							$el.find('.of-background-position').closest('.tb-fancy-select').show();
							$el.find('.of-background-size').closest('.tb-fancy-select').show();
							$el.find('.parallax').hide();
						}
					});

					// jQuery UI slider
					if ( $.isFunction( $.fn.slider ) ) {
						$this.find('.jquery-ui-slider').each(function(){

							var $el = $(this),
								$input = $el.closest('.jquery-ui-slider-wrap').find('.slider-input'),
								units = $el.data('units');

							$el.slider({
								min: $el.data('min'),
								max: $el.data('max'),
								step: $el.data('step'),
								value: parseInt($input.val()),
								create: function( event, ui ) {

									$el.find('.ui-slider-handle').append('<span class="display-value"><span class="display-value-text"></span><span class="display-value-arrow"></span></span>');

									var $display = $el.find('.display-value');
									$display.css('margin-left', '-'+($display.outerWidth()/2)+'px');
									$display.find('.display-value-text').text( $input.val() );

								},
								slide: function( event, ui ) {
									$input.val( ui.value+units );
									$el.find('.display-value-text').text( ui.value+units );
								}
							});

						});
					}

					// WP Color Picker
					if ( $.isFunction( $.fn.wpColorPicker ) ) {
						$this.find('.tb-color-picker').wpColorPicker();
					}

					// Button option type
					if ( $.isFunction( $.fn.wpColorPicker ) ) {
						$this.find('.section-button').each(function(){

							var $el = $(this);

							// Color picker
							$el.find('.color-picker').wpColorPicker();
							$el.find('.color.bg .wp-color-result').attr('title', 'Background Color');
							$el.find('.color.bg_hover .wp-color-result').attr('title', 'Background Hover Color');
							$el.find('.color.border .wp-color-result').attr('title', 'Border Color');
							$el.find('.color.text .wp-color-result').attr('title', 'Text Color');
							$el.find('.color.text_hover .wp-color-result').attr('title', 'Text Hover Color');

							// Show/Hide BG color
							if ( $el.find('.include.bg input').is(':checked') ) {
								$el.find('.color.bg').show();
							}

							// Show/Hide border color
							if ( $el.find('.include.border input').is(':checked') ) {
								$el.find('.color.border').show();
							}

						});
					}

					// Remove tooltips if hovered link is clicked
					if ( ! $('body').hasClass('mobile') ) {
						$this.find('.tb-tooltip-link').click(function(){

	    					// Remove Tooltip
	    					$('.themeblvd-tooltip').remove();

	    					// Toggle text
	    					var link = $(this),
	    						toggle = link.data('tooltip-toggle');

	    					if ( toggle == 2 ) {
	    						link.data('tooltip-toggle', 1);
	    					} else {
	    						link.data('tooltip-toggle', 2);
	    					}

	    				});
					}

    				// Link to icon browsers
    				if ( $.isFunction( $.fn.ThemeBlvdModal ) ) {
						$this.find('.tb-input-icon-link').ThemeBlvdModal({
					        build: false,
					        padding: true,
					        size: 'custom', // Something other than "large" to trigger auto height
					    	on_display: function() {

					    		var self = this,
									$elem = self.$elem,
									$browser = self.$modal_window,
									icon = $elem.closest('.input-wrap').find('input').val();

								// Reset icon browser
								$browser.find('.media-frame-content').scrollTop(0);
								$browser.find('.select-icon').removeClass('selected');
								$browser.find('.media-toolbar-secondary').find('.fa, span, img').remove();

								// If valid icon exists in text input, apply the selection
								if ( $browser.find('[data-icon="'+icon+'"]').length > 0 ) {
									$browser.find('[data-icon="'+icon+'"]').addClass('selected');
									$browser.find('.icon-selection').val(icon);
									$browser.find('.media-toolbar-secondary').append('<i class="fa fa-'+icon+' fa-2x fa-fw"></i><span>'+icon+'</span>');
								}

					    	},
					    	on_save: function() {

					    		var self = this,
					    			icon = self.$modal_window.find('.icon-selection').val();

					    		// Send selection back to input
					    		self.$elem.closest('.input-wrap').find('input').val(icon);
					    	}
					    });
					}

					// Link to Post ID browser
					if ( $.isFunction( $.fn.ThemeBlvdModal ) ) {
						$this.find('.tb-input-post-id-link').ThemeBlvdModal({
					        build: false,
					        padding: true,
					        button: '',
					        size: 'custom', // Something other than "large" to trigger auto height
					    	on_display: function() {

					    		var self = this,
									$elem = self.$elem,
									$browser = self.$modal_window;

								// Bind search ajax
								$browser.find('#search-submit').off('click.tb-search-posts');
								$browser.find('#search-submit').on('click.tb-search-posts', function() {

									var $search = $(this).closest('.post-browser-head'),
										data = {
											action: 'themeblvd_post_browser',
											data: $search.find('#post-search-input').val()
										};

									$search.find('.tb-loader').fadeIn(200);

									$.post(ajaxurl, data, function(r) {
										$browser.find('.ajax-mitt').html('').append(r);
										$search.find('.tb-loader').fadeOut(200);
									});

									return false;
								});

								// Select a post and close modal
								$browser.off('click.tb-select-post', '.select-post');
								$browser.on('click.tb-select-post', '.select-post', function(){
									$browser.find('#post-search-input').val('');
									$browser.find('.ajax-mitt').html('');
									$elem.closest('.input-wrap').find('.of-input').val($(this).data('post-id'));
									self.close();
                    				return false;
								});

					    	},
					    	on_cancel: function() {
					    		$browser = this.$modal_window;
								$browser.find('#post-search-input').val('');
					    		$browser.find('.ajax-mitt').html('');
					    	}
					    });
					}

					// Link to texture browsers
    				if ( $.isFunction( $.fn.ThemeBlvdModal ) ) {
						$this.find('.tb-texture-browser-link').ThemeBlvdModal({
					        build: false,
					        padding: true,
					        size: 'custom', // Something other than "large" to trigger auto height
					    	on_display: function() {

					    		var self = this,
									$elem = self.$elem,
									$browser = self.$modal_window,
									texture = $elem.closest('.controls').find('.of-input').val();

								$browser.find('.media-frame-content').scrollTop(0);

								$browser.find('.select-texture').each(function(){

									$a = $(this);
									$a.removeClass('selected');

									if ( $a.data('texture') == texture ) {
										$a.addClass('selected');
										$browser.find('.texture-selection').val( $a.data('texture') );
										$browser.find('.current-texture').text( $a.data('texture-name') );
									}
								});

					    	},
					    	on_save: function() {

					    		var self = this,
					    			$select = self.$elem.closest('.controls').find('.of-input'),
					    			texture = self.$modal_window.find('.texture-selection').val();

					    		// Send selection back to select
					    		$select.val(texture);
					    		$select.closest('.tb-fancy-select').find('.textbox').text( $select.find('option[value="'+texture+'"]').text() );

					    	}
					    });
					}

					// Footer Sync
					if ( $this.find('#tb-footer-sync').is(':checked') ) {
						$this.find('.standard-footer-setup').hide();
						$this.find('.footer-template-setup').show();
					} else {
						$this.find('.standard-footer-setup').show();
						$this.find('.footer-template-setup').hide();
					}

					$this.find('#tb-footer-sync').on('click', function(){
						if ( $(this).is(':checked') ) {
							$this.find('.standard-footer-setup').hide();
							$this.find('.footer-template-setup').show();
						} else {
							$this.find('.standard-footer-setup').show();
							$this.find('.footer-template-setup').hide();
						}
					});

	    		}
	    		// Apply all binded actions. This will only need
	    		// to be called once on the original page load.
	    		else if(type == 'bind') {

	    			// Fancy Select
	    			$this.on( 'change', '.tb-fancy-select select', function() {
		    			var el = $(this), value = el.val(), text = el.find('option[value="'+value+'"]').text();
						el.closest('.tb-fancy-select').find('.textbox').text(text);
	    			});

	    			// Custom content
	    			$this.on( 'change', '.custom-content-types select', function() {
	    				var el = $(this), value = el.val(), parent = el.closest('.subgroup');
	    				if(value == 'current')
	    				{
	    					parent.find('.page-content').fadeOut('fast');
	    					parent.find('.raw-content').fadeOut('fast');
	    					parent.find('.widget_area-content').fadeOut('fast');
	    				}
	    				else if(value == 'external')
	    				{
	    					parent.find('.page-content').fadeIn('fast');
	    					parent.find('.raw-content').hide();
	    					parent.find('.widget_area-content').hide();
	    				}
	    				else if (value == 'raw')
	    				{
	    					parent.find('.raw-content').fadeIn('fast');
	    					parent.find('.page-content').hide();
	    					parent.find('.widget_area-content').hide();
	    				}
	    				else if (value == 'widget_area')
	    				{
	    					parent.find('.widget_area-content').fadeIn('fast');
	    					parent.find('.raw-content').hide();
	    					parent.find('.page-content').hide();
	    				}
	    			});

	    			// Tabs number
	    			$this.on( 'change', '.tabs .tabs-num', function() {
	    				var el = $(this), i = 1, num = el.val(), parent = el.closest('.tabs');
	    				parent.find('.tab-names .tab-name').hide();
	    				parent.find('.section-content').hide();
	    				while (i <= num)
						{
							parent.find('.tab-names .tab-name-'+i).show();
							parent.find('#section-tab_'+i).show();
							i++;
	    				}
	    			});

	    			// Column/Tab content types
	    			$this.on( 'change', '.column-content-types select.select-type', function() {
						var section = $(this).closest('.section-content'), type = $(this).val();
	    				section.find('.column-content-type').hide();
	    				section.find('.column-content-type-'+type).show();
	    			});

	    			// Show correct number of columns (theme options), and
    				// whether to show column widths option
	    			$this.on( 'change', '.select-col-num', function() {
	    				var $el = $(this), i, num = $el.val(), $container = $el.closest('.columns');
	    				if ( num > 1 ) {
	    					$container.find('.select-wrap-grid').show();
	    					$container.find('.section-column_widths').show();
	    					$container.closest('.widget-content').find('.column-height').show();
	    				} else {
	    					$container.find('.select-wrap-grid').hide();
	    					$container.find('.section-column_widths').hide();
	    					$container.closest('.widget-content').find('.column-height').hide();
	    				}
	    				$container.find('.section-content').hide();
	    				for ( i = 1; i <= num; i++) {
							$container.find('.col_'+i).show();
	    				}
	    			});

	    			// Show/Hide groupings
    				$this.on( 'click', '.show-hide > .trigger input', function() {

    					var checkbox = $(this);

    					if ( checkbox.is(':checked') ) {

    						checkbox.closest('.show-hide').find('.receiver').each(function(){
    							$(this).find('input, textarea, select').prop('disabled', false);
    						});

    						checkbox.closest('.show-hide').children('.receiver').fadeIn('fast');

    					} else {

    						checkbox.closest('.show-hide').find('.receiver').each(function(){
    							$(this).find('input, textarea, select').prop('disabled', true);
    						});

    						checkbox.closest('.show-hide').children('.receiver').hide();

    					}
    				});

    				// Hide/Show groupings
    				$this.on( 'click', '.hide-show > .trigger input', function() {

    					var checkbox = $(this);

    					if ( checkbox.is(':checked') ) {

    						checkbox.closest('.hide-show').find('.receiver').each(function(){
    							$(this).find('input, textarea, select').prop('disabled', true);
    						});

    						checkbox.closest('.hide-show').children('.receiver').hide();

    					} else {

    						checkbox.closest('.hide-show').find('.receiver').each(function(){
    							$(this).find('input, textarea, select').prop('disabled', false);
    						});

    						checkbox.closest('.hide-show').children('.receiver').fadeIn('fast');

    					}
    				});

    				// Show/Hide toggle grouping (triggered with <select> to target specific options)
    				$this.on( 'change', '.show-hide-toggle > .trigger select.of-input', function() {

    					var $el = $(this), value = $el.val(), $group = $el.closest('.show-hide-toggle');

    					$group.children('.receiver').each(function(){
    						$(this).hide().find('input, textarea, select').prop('disabled', true);
    					});

    					$group.children('.receiver-'+value).each(function(){
    						$(this).show().find('input, textarea, select').prop('disabled', false);
    					});


    				});

    				// Show/Hide toggle grouping (triggered with radio group to target specific options)
    				$this.on( 'click', '.show-hide-toggle > .trigger .of-radio', function() {

    					var $el = $(this), value = $el.val(), $group = $el.closest('.show-hide-toggle');

    					$group.children('.receiver').each(function(){
    						$(this).hide().find('input, textarea, select').prop('disabled', true);
    					});

    					$group.children('.receiver-'+value).each(function(){
    						$(this).show().find('input, textarea, select').prop('disabled', false);
    					});

    				});

    				// Where one option's value determines which description displays on another option
    				$this.on( 'change', '.desc-toggle > .trigger .of-input', function() {
    					var $el = $(this), value = $el.val(), $group = $el.closest('.desc-toggle');
    					$group.find('.desc-receiver .explain').hide();
    					$group.find('.desc-receiver .explain.'+value).show();
    				});

    				// Configure logo
    				$this.on( 'change', '.section-logo .select-type select', function() {
    					var el = $(this), parent = el.closest('.section-logo'), value = el.val();
						parent.find('.logo-item').hide();
						parent.find('.'+value).show();
    				});

    				// Google font selection
    				$this.on( 'change', '.section-typography .of-typography-face', function() {
    					var el = $(this), value = el.val();
    					if( value == 'google' )
    						el.closest('.section-typography').find('.google-font').fadeIn('fast');
    					else
    						el.closest('.section-typography').find('.google-font').hide();
    				});

    				// Homepage Content
	    			$this.on( 'change', '#section-homepage_content input:checked', function() {
    					if( $(this).val() == 'custom_layout' )
    						$this.find('#section-homepage_custom_layout').fadeIn('fast');
    					else
    						$this.find('#section-homepage_custom_layout').fadeOut('fast');
    				});

	    			// Match options
					$this.on( 'change', '.match-trigger .of-input', function(){
						var $el = $(this);
						$el.closest('.subgroup').find('.match .of-input').val($el.val());
					});

					// Categories multicheck
					$this.on( 'click', '.select-categories input', function(){

						var $current = $(this),
							$option = $current.closest('.controls');

						if ( $current.prop('checked') ) {
							if ( $current.hasClass('all') ) {
								$option.find('input').each(function(){
									if ( ! $(this).hasClass('all') ) {
										$(this).prop('checked', false);
									}
								});
							} else {
								$option.find('input.all').prop('checked', false);
							}
						}

					});

					// Background options
					$this.on( 'change', '.select-parallax .of-background-attachment', function(){

						var $el = $(this).closest('.select-parallax');

						if ( $(this).val() == 'parallax' ) {
							$el.find('.of-background-position').closest('.tb-fancy-select').hide();
							$el.find('.of-background-size').closest('.tb-fancy-select').hide();
							$el.find('.parallax').show();
						} else {
							$el.find('.of-background-position').closest('.tb-fancy-select').show();
							$el.find('.of-background-size').closest('.tb-fancy-select').show();
							$el.find('.parallax').hide();
						}
					});

	    			// Modals
	    			if ( $.isFunction( $.fn.ThemeBlvdModal ) ) {
	    				$this.find('.tb-modal-link').ThemeBlvdModal();
	    			}

    				// Tooltips
    				if ( ! $('body').hasClass('mobile') ) {

	    				$this.on( 'mouseenter', '.tb-tooltip-link', function() {

	    					var link = $(this);

	    					var	position = link.data('tooltip-position'),
	    						x = link.offset().left,
								y = link.offset().top,
								text = link.data('tooltip-text'),
								markup =  '<div class="themeblvd-tooltip %position%"> \
											   <div class="tooltip-inner"> \
											     %text% \
											   </div> \
											   <div class="tooltip-arrow"></div> \
											</div>';

							// Check for text toggle
							if ( ! text && link.data('tooltip-toggle') ) {
								text = link.data('tooltip-text-'+ link.data('tooltip-toggle') );
							}

							// If no text found at data-tooltip-text, then pull from title
							if ( ! text ) {
								text = link.attr('title');
							}

							// If no position found at data-tooltip-position, set to "top"
							if ( ! position ) {
								position = 'top';
							}

							// Setup markup
							markup = markup.replace('%position%', position);
							markup = markup.replace('%text%', text);

							// Append tooltip to page
							$('body').append( markup );

							// Setup and display tooltip
							var tooltip = $('.themeblvd-tooltip'),
								tooltip_height = tooltip.outerHeight(),
								tooltip_width = tooltip.outerWidth();

							// Position of tooltip relative to link
							switch ( position ) {

								case 'left' :
									x = x-tooltip_width-5; // 5px for arrow
									y = y+(.5*link.outerHeight());
									y = y-tooltip_height/2;
									break;

								case 'right' :
									x = x+link.outerWidth()+5; // 5px for arrow
									y = y+(.5*link.outerHeight());
									y = y-tooltip_height/2;
									break;

								case 'bottom' :
									x = x+(.5*link.outerWidth());
									x = x-tooltip_width/2;
									y = y+link.outerHeight()+2;
									break;

								case 'top' :
								default :
									x = x+(.5*link.outerWidth());
									x = x-tooltip_width/2;
									y = y-tooltip_height-2;
							}

							tooltip.css({
								'top' : y+'px',
								'left' : x+'px'
							}).addClass('fade in');
	    				});

	    				$this.on( 'mouseleave', '.tb-tooltip-link', function() {
	    					$('.themeblvd-tooltip').remove();
	    				});

	    			}

    				// Button option type
					$this.on( 'click', '.section-button .include input', function(){

						var $el = $(this),
							type = 'bg';

						if ( $el.closest('.include').hasClass('border') ) {
							type = 'border';
						}

						if ( $el.is(':checked') ) {
							$el.closest('.section-button').find('.color.'+type).show();
						} else {
							$el.closest('.section-button').find('.color.'+type).hide();
						}

					});

					// Google Maps latitude and longitude
					if ( typeof google === 'object' && typeof google.maps === 'object' ) {
						$this.on( 'click', '.section-geo .geo-insert-lat-long', function(){

							var $el = $(this),
								$overlay = $el.closest('.geo-generate').find('.overlay'),
								geocoder = new google.maps.Geocoder(),
								address = $el.prev('.address').val(),
								latitude = '0',
								longitude = '0';

							$overlay.fadeIn(100);

							geocoder.geocode( { 'address': address}, function(results, status) {

								if ( status == google.maps.GeocoderStatus.OK ) {
									latitude = results[0].geometry.location.lat();
									longitude = results[0].geometry.location.lng();
								}

								setTimeout(function() {

									$el.closest('.controls').find('.geo-lat .geo-input').val(latitude);
									$el.closest('.controls').find('.geo-long .geo-input').val(longitude);

									if ( status != google.maps.GeocoderStatus.OK ) {
										tbc_confirm( $el.data('oops'), {'textOk':'Ok'} );
									} else {
										$el.prev('.address').val('');
									}

									$overlay.fadeOut(100);

								}, 1500);
							});

							return false;
						});
					}

	    		}
	    		// Apply media uploader from themeblvd_media_uploader object.
	    		// This incorporates the Media Uploader in WP 3.5+
	    		else if(type == 'media-uploader') {
	    			// Check to make sure wp.media object exists.
	    			// If it doesn't ...
	    			// (1) We're using an older version of WP and the
	    			// legacy uploader.
	    			// (2) We're using a plugin that uses legacy uploader.
	    			// (3) the WP 3.5+'s media uploader JS files haven't been
	    			// enqueued properly.
	    			if ( typeof wp !== 'undefined' && typeof wp.media !== 'undefined' ) {
	    				themeblvd_media_uploader.init($this);
	    			}
	    		}
	    		// Incorporate WP Editors
	    		else if(type == 'editor') {

	    			// Modal Editor
					if ( $.isFunction( $.fn.ThemeBlvdModal ) ) {
						$this.find('.tb-textarea-editor-link').ThemeBlvdModal({
					        build: false,
					        padding: true,
					        height: 'auto',
					        on_load: function() {

					        	// Temporary override WP's active editor
					        	wpActiveEditor = 'themeblvd_editor';

					            var self = this,
					            	editor,
					            	editor_id = 'themeblvd_editor',
					            	textarea,
					            	field_name,
					            	content = '',
									has_tinymce = typeof tinymce !== 'undefined',
					            	modal_window = self.$modal_window;

					            if ( self.$elem.is('.tb-block-editor-link') ) {
					            	field_name = self.$elem.closest('.block').data('field-name');
					            	textarea = self.$elem.closest('.block').find('textarea[name="'+field_name+'[content]"]');
					            } else {
					            	textarea = self.$elem.closest('.textarea-wrap').find('textarea');
					            }

					            // Get initial raw content
					            content = textarea.val();

					            // Height of editor
					            modal_window.find('.wp-editor-area, iframe').height(300);

					            if ( modal_window.find('.wp-editor-wrap').is('.tmce-active') ) {

					            	// Get the current editor by ID
						            if ( has_tinymce ) {
						            	editor = tinymce.get( editor_id );
						            }

						            // To "Visual" editor
						            if ( editor ) {
										content = window.switchEditors.wpautop( content );
					            		editor.setContent( content, {format:'raw'} );
						            }

					            } else {

					            	// To "Text" editor
					            	modal_window.find('textarea').val( content );

					            }

					            // Prevent textarea scroll to show over modal
					            textarea.css('overflow', 'hidden');

					        },
					        on_save: function() {

					        	// Put back WP's active editor
					        	wpActiveEditor = 'content';

								var self = this,
									editor,
					            	editor_id = 'themeblvd_editor',
					            	content,
					            	field_name,
					            	textarea,
									has_tinymce = typeof tinymce !== 'undefined',
					            	modal_window = self.$modal_window;

					            if ( self.$elem.is('.tb-block-editor-link') ) {
					            	field_name = self.$elem.closest('.block').data('field-name');
					            	textarea = self.$elem.closest('.block').find('textarea[name="'+field_name+'[content]"]');
					            } else {
					            	textarea = self.$elem.closest('.textarea-wrap').find('textarea');
					            }

								if ( modal_window.find('.wp-editor-wrap').is('.tmce-active') ) {

									if ( has_tinymce ) {
					            		editor = tinymce.get( editor_id );
					            	}

									// From "Visual" editor
									if ( editor ) {
										content = editor.getContent();
										content = window.switchEditors.pre_wpautop(content);
									}

					            } else {

					            	// From "Text" editor
					            	content = modal_window.find('.wp-editor-area').val();

					            }

								// Update options textara with new contnet from Editor
								textarea.val( content );

								// Put back textarea scrolling
					            textarea.css('overflow', 'visible');

					        },
					        on_cancel: function() {

					        	// Put back WP's active editor
					        	wpActiveEditor = 'content';

								// Put back textarea scrolling
					        	this.$elem.closest('.textarea-wrap').find('textarea').css('overflow', 'visible');

					        }
					    });
					}

					/*
					TinyMCE Integration
					This all works, for the most part, but there
					are too many little odd quirks that happen.
					Current Issues:
					(1) Will not use cookie to show "Text" tab
					initially if it's supposed to.
    				(2) If cookie is set to "Text", visual Editor
    				comes up with "mce-flow-layout"
    				(3) Random page jumping when opening an element
    				with an editor. Only happens every once in awhile,
    				can't figur out why.
    				*/
    				/*
    				$this.find('.section-editor').each(function(){

						var section 	= $(this),
							has_tinymce	= typeof tinymce !== 'undefined',
							textarea 	= section.find('textarea.wp-editor-area'),
							el_id		= textarea.attr('id'),
							parent		= textarea.closest('.wp-editor-wrap'),
							switch_btn	= section.find('.wp-switch-editor').removeAttr("onclick"),
							q_settings	= {id: el_id, buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,spell,close"};

						if ( ! has_tinymce ) {
							return;
						}

						quicktags(q_settings);
						QTags._buttonsInit();

						switch_btn.on( 'click', function() {

							var button = $(this), content;

							if( button.is('.switch-tmce') ) {
								parent.removeClass('html-active').addClass('tmce-active');
								tinymce.execCommand('mceAddEditor', true, el_id);
								content = switchEditors.wpautop( textarea.val() );
								tinymce.get(el_id).setContent( content, {format:'raw'} );
							} else {
								parent.removeClass('tmce-active').addClass('html-active');
								tinymce.execCommand('mceRemoveEditor', true, el_id);
								content = textarea.val();
								content = window.switchEditors.pre_wpautop(content);
								textarea.val(content);
							}

							return false;

						}).trigger('click');

					});
					*/
	    		}
	    		// Incorporate code Editors
	    		else if(type == 'code-editor' && typeof CodeMirror !== 'undefined') {

	    			// Code editor from textarea to modal popup
    				if ( $.isFunction( $.fn.ThemeBlvdModal ) ) {
						$this.find('.tb-textarea-code-link').ThemeBlvdModal({
							code_editor: true,
							size: 'medium',
							height: 'auto'
						});
	    			}

	    			// Code editor directly in options panel
	    			$this.find('.section-code').each(function(){

	    				var section = $(this),
	    					textarea = section.find('textarea'),
	    					lang = textarea.data('code-lang'),
	    					mode,
	    					editor;

	    				// Look for existing instance of this editor
                		editor = textarea.data('CodeMirrorInstance');

                		// Editor doesn't exist, so let's create one
                		if ( ! editor ) {

		    				// Setup mode for CodeMirror
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
							editor = CodeMirror.fromTextArea(document.getElementById( textarea.attr('id') ), {
								mode: mode,
								lineNumbers: true,
			                    theme: 'themeblvd',
			                    indentUnit: 4,
			                    tabSize: 4,
			                    indentWithTabs: true
			                });

							// Make sure that code editor content
							// gets sent back to form's textarea
							editor.on('blur', function(){
							    textarea.val( editor.getValue() );
							});

							// Store CodeMirror instance with textarea
							// so we can access it again later.
							textarea.data('CodeMirrorInstance', editor);

						}
	    			});

	    		}
	    		// Column Widths
	    		else if(type == 'column-widths') {
	    			themeblvd_column_widths.init($this);
	    		}
	    		// Sortable option type
	    		else if (type == 'sortable') {

	    			// We'll store all of the setup for an item
	    			// within this variable so we can call it again
	    			// on AJAX success of adding new items.
	    			var sortable_item_setup = function( $item ) {

	    				// Bind "Delete Item" button
	    				$item.find('.delete-sortable-item').off('click');
						$item.find('.delete-sortable-item').on( 'click', function(){

							var $link = $(this),
								$option = $link.closest('.section-sortable'),
								$item = $link.closest('.item'),
								max = $link.closest('.tb-sortable-option').data('max');

							tbc_confirm($link.attr('title'), {'confirm':true}, function(r) {
						    	if(r) {
									$item.addClass('delete');
									window.setTimeout(function(){
										$item.remove();
										if ( ! $option.find('.item-container .item').length ) {
											$option.find('.delete-sortable-items').fadeOut(200);
										}
										if ( max > 0 && $option.find('.item-container > .item').length < max ) {
											$option.find('.add-item').prop('disabled', false);
										}
									}, 500);
						        }
						    });
						    return false;
						});

						// Bind toggle for items
						$item.find('.toggle').off('click');
						$item.find('.toggle').on('click', function(){

							var $el = $(this);

							if ( $el.closest('.item-handle').hasClass('closed') ) {
								$el.closest('.item-handle').removeClass('closed');
								$el.closest('.item').find('.item-content').show();
							} else {
								$el.closest('.item-handle').addClass('closed');
								$el.closest('.item').find('.item-content').hide();
							}

							return false;
						});

						$item.find('.item-handle h3').each(function(){

							var $el = $(this),
								$trigger = $el.closest('.item').find('.handle-trigger');

							if ( $trigger.is('select') ) {
								$el.closest('.item').find('.item-handle h3').text( $trigger.find('option[value="'+$trigger.val()+'"]').text() );
							} else {
								$el.closest('.item').find('.item-handle h3').text( $trigger.val() );
							}
						});

						$item.find('.handle-trigger').off('change');
						$item.find('.handle-trigger').on( 'change', function(){

							var $el = $(this);

							if ( $el.is('select') ) {
								$el.closest('.item').find('.item-handle h3').text( $el.find('option[value="'+$el.val()+'"]').text() );
							} else {
								$el.closest('.item').find('.item-handle h3').text( $el.val() );
							}
						});

	    			};

	    			// Sortable option type
					$this.find('.tb-sortable-option').each(function(){

						var $option = $(this),
							$section = $option.closest('.section-sortable'),
							max = $option.data('max');

						// Setup sortable items
						$section.find('.item').each(function() {
							sortable_item_setup( $(this) );
						});

						if ( $option.find('.item-container .item').length ) {
							$option.find('.delete-sortable-items').show();
						}

						// Setup sortables
						$section.find('.item-container').sortable({
							handle: '.item-handle'
						});

						// Bind "Add Item" button
						$section.find('.add-item').off('click'); // avoid duplicates
						$section.find('.add-item').on( 'click', function(){

							var $new_item,
								$button = $(this);

							var data = {
								action: 'themeblvd_add_'+$option.data('type')+'_item',
								security: $option.data('security'),
								data: {
									option_name: $option.data('name'),
									option_id: $option.data('id')
								}
							};

							$.post(ajaxurl, data, function( response ){

								// Append new item
								$section.find('.item-container').append( response );

								// Cache new item we just appended
								$new_item = $section.find('.item').last();

								// Make it green for a bit to indicate it was just added
								$new_item.addClass('add');
								window.setTimeout(function(){
									$new_item.removeClass('add');
								}, 500);

								// Show "Delete All Items" button
								$section.find('.delete-sortable-items').fadeIn(200);

								// Setup scripts within item
								sortable_item_setup( $new_item );

								// Setup general scripts for options
								$new_item.themeblvd('options', 'setup');
								$new_item.themeblvd('options', 'editor');
								$new_item.themeblvd('options', 'code-editor');
								$new_item.themeblvd('options', 'media-uploader');

								if ( max > 0 && $option.find('.item-container > .item').length >= max ) {
									$button.prop('disabled', true);
								}

							});

							return false;
						});

						// Bind "Delete All Items" button
						$section.find('.delete-sortable-items').off('click'); // avoid duplicates
						$section.find('.delete-sortable-items').on( 'click', function(){

							var $link = $(this),
								$option = $link.closest('.tb-sortable-option'),
								$items = $option.find('.item');

							tbc_confirm($link.attr('title'), {'confirm':true}, function(r) {
						    	if(r) {
									$items.addClass('delete');
									window.setTimeout(function(){
										$items.remove();
										$option.find('.delete-sortable-items').fadeOut(200);
										$option.find('.add-item').prop('disabled', false);
									}, 500);
						        }
						    });
						    return false;
						});

					});
	    		}
    		});
    	},

    	// Widgets
		widgets: function()
		{
			return this.each(function(){

				var $el = $(this);

				// Top level widgets
				$el.find('.widget-content').hide();
				$el.find('.widget-name').addClass('widget-name-closed');

				// Inner widgets (i.e. blocks)
				$el.find('.block-content').hide();
				$el.find('.block-handle').addClass('block-handle-closed');

			});
		},

		// Accordion
		accordion: function()
		{
			return this.each(function(){
				var el = $(this);

				// Set it up
				el.find('.element-content').hide();
				el.find('.element-content:first').show();
				el.find('.element-trigger:first').addClass('active');

				// The click
				el.find('.element-trigger').click(function(){
					var anchor = $(this);
					if( ! anchor.hasClass('active') )
					{
						el.find('.element-content').hide();
						el.find('.element-trigger').removeClass('active');
						anchor.addClass('active');
						anchor.next('.element-content').show();
					}
					return false;
				});
			});
		}

	};

	// Setup themeblvd namespace
	$.fn.themeblvd = function(method) {
		if( themeblvd_shared[method] )
			return themeblvd_shared[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
		else if( typeof method === 'object' || ! method )
			return themeblvd_shared.init.apply( this, arguments );
		else
			$.error( 'Method ' +  method + ' does not exist.' );
	};

})(jQuery);

/**
 * Media Uploader for WP 3.5+
 */
(function($) {
	themeblvd_media_uploader = {

		/**
		 * Manage any media modals that are created.
		 */
		media_frame: [],

		/**
		 * Apply click actions initially when loaded.
		 */
		init: function( $options ) {

			var self = this;

			// Avoid multiple bindings
			$options.find('.upload-button').off('click');
			$options.find('.remove-image, .remove-file').off('click');
			$options.find('.add-images').off('click');

			// Bind button actions
			$options.find('.upload-button').on( 'click', function(event){
				event.preventDefault();
				self.add_file( $(this).closest('.section-upload, .section-media') );
			});

			$options.find('.remove-image, .remove-file').on( 'click', function(event){
				event.preventDefault();
				self.remove_file( $(this).closest('.section-upload, .section-media') );
			});

			// Bind "Add Images" button for "slider" option type
			$options.find('.add-images').on( 'click', function(event){
				event.preventDefault();
				self.add_images( $(this), $(this).closest('.section-sortable') );
			});
		},

		/**
		 * Trigger media uploader modal to insert an image.
		 */
		add_file: function( $current_option ) {

			var self = this,
				frame_id = $current_option.find('input.upload').attr('id');

			// If we've already created the media frame, open it,
			// and get the heck out of here.
			if ( self.media_frame[frame_id] ) {
				self.media_frame[frame_id].open();
				return;
			}

			var new_frame,
				upload_type = $current_option.find('.trigger').data('type'),
				title = $current_option.find('.trigger').data('title'),
				select = $current_option.find('.trigger').data('select'),
				css_class = $current_option.find('.trigger').data('class'),
				send_back = $current_option.find('.trigger').data('send-back'),
				link = $current_option.find('.trigger').data('link'),
				media_type = 'image',
				workflow = 'select',
				multiple = false, // @todo future feature of Quick Slider
				state = 'library';

			if ( upload_type == 'video' ) {
				media_type = 'video';
			}

			if ( upload_type == 'media' ) {
				media_type = '';
				multiple = true;
			}

			if ( upload_type == 'advanced' ) {
				state = 'themeblvd_advanced';
			}

			// Create the media frame.
			new_frame = self.media_frame[frame_id] = wp.media.frames.file_frame = wp.media({
				frame: workflow,
				state: state,
				className: 'media-frame '+css_class, // Will break without "media-frame"
				title: title,
				library: {
					type: media_type
				},
				button: {
					text: select
				},
				multiple: multiple
			});

			// Setup advanced image selection
			if ( upload_type == 'advanced' ) {

				// Create "themeblvd_advanced" state
				new_frame.states.add([

					new wp.media.controller.Library({
						id: 'themeblvd_advanced',
						title: title,
						priority: 20,
						toolbar: 'select',
						filterable: 'uploaded',
						library: wp.media.query( new_frame.options.library ),
						multiple: false,
						editable: true,
						displayUserSettings: false,
						displaySettings: true,
						allowLocalEdits: true
						// AttachmentView: media.view.Attachment.Library
					})

				]);
			}

			// When media item is inserted
			new_frame.on( 'select', function() {

				// Grab the selected attachment.
				var attachment = new_frame.state().get('selection').first(),
					remove_text = $current_option.find('.trigger').data('remove'),
					size,
					image_url,
					link_option,
					link_url,
					helper_text;

				// Determine Image URL. If it "advanced" will pull from crop size selection
				if ( upload_type == 'advanced' ) {
					size = new_frame.$el.find('.attachment-display-settings select[name="size"]').val();
					image_url = attachment.attributes.sizes[size].url;
				} else {
					image_url = attachment.attributes.url;
				}

				if ( send_back == 'id' ) {
					$current_option.find('.image-url').val(attachment.attributes.id);
				} else {
					$current_option.find('.image-url').val(image_url);
				}

				if ( attachment.attributes.type == 'image' ) {
					$current_option.find('.screenshot').empty().hide().append('<img src="'+image_url+'"><a class="remove-image"></a>').slideDown('fast');
				}

				if ( upload_type == 'logo' ) {
					$current_option.find('.image-width').val(attachment.attributes.width);
					$current_option.find('.image-height').val(attachment.attributes.height);
				}

				if ( upload_type == 'video' ) {
					$current_option.find('.video-url').val(attachment.attributes.url);
				}

				if ( upload_type == 'slider' ) {

					$current_option.find('.image-id').val(attachment.attributes.id);
					$current_option.find('.image-title').val(attachment.attributes.title);

					helper_text = $current_option.find('.image-title').val();

					if ( helper_text ) {
						$current_option.closest('.widget').find('.slide-summary').text(helper_text).fadeIn(200);
					}
				}

				if ( upload_type != 'video' && upload_type != 'media' ) {
					$current_option.find('.upload-button').unbind().addClass('remove-file').removeClass('upload-button').val(remove_text);
					$current_option.find('.of-background-properties').slideDown();

					$current_option.find('.remove-image, .remove-file').click(function() {
						themeblvd_media_uploader.remove_file( $(this).closest('.section-upload') );
			        });
				}

				if ( upload_type == 'media' ) {
					// ...
				}

				if ( upload_type == 'advanced' ) {

					// Send info back
					$current_option.find('.image-id').val(attachment.attributes.id);
					$current_option.find('.image-title').val(attachment.attributes.title);
					$current_option.find('.image-crop').val(size);
					// $current_option.find('.image-width').val(attachment.attributes.sizes[size].width);
					// $current_option.find('.image-height').val(attachment.attributes.sizes[size].height);

					// Send Link back
					if ( link ){

						link_option = new_frame.$el.find('.attachment-display-settings .link-to').val();

						if ( link_option != 'none' ) {
							link_url = new_frame.$el.find('.attachment-display-settings .link-to-custom').val();
							console.log(link_url);
							$current_option.closest('.advanced-image-upload').find('.receive-link-url input').val(link_url);
						}
					}

				}

			});

			// Finally, open the modal.
			new_frame.open();

			if ( upload_type == 'advanced' ) {

				new_frame.$el.addClass('hide-menu');
				new_frame.$el.find('.attachment-display-settings label:first-of-type').remove();

				if ( ! link ) {
					console.log('hiding link options...');
					console.log(new_frame.$el.find('.attachment-display-settings div.setting'));
					new_frame.$el.find('.attachment-display-settings div.setting').remove();
				}
			}

		},

		/**
		 * Remove current image and put back "Upload" button.
		 */
		remove_file: function( $current_option ) {

			var self = this,
				upload_text = $current_option.find('.trigger').data('upload'),
				upload_type = $current_option.find('.trigger').data('type');

			if ( upload_type == 'slider' ) {
				$current_option.closest('.widget').find('.slide-summary').removeClass('image video').hide().text('');
			}

			$current_option.find('.remove-image').hide();
			$current_option.find('.upload').val('');
			$current_option.find('.of-background-properties').hide();
			$current_option.find('.screenshot').slideUp();
			$current_option.find('.remove-file').addClass('upload-button').removeClass('remove-file').val(upload_text);

			$current_option.find('.upload-button').click(function(event){
				event.preventDefault();
				self.add_file( $(this).closest('.section-upload') );
			});
		},

		/**
		 * Add images, used for "slider" advanced sortable option type
		 */
		add_images: function( $button, $current_option ) {

			var self = this,
				frame_id = $button.attr('id');

			// If we've already created the media frame, open it,
			// and get the heck out of here.
			if ( self.media_frame[frame_id] ) {
				self.media_frame[frame_id].open();
				return;
			}

			// Create the media frame.
			var new_frame = self.media_frame[frame_id] = wp.media.frames.file_frame = wp.media({
				frame: 'select',
				className: 'media-frame tb-modal-hide-settings', // Will break without "media-frame"
				title: $button.data('title'),
				library: {
					type: 'image'
				},
				button: {
					text: $button.data('button')
				},
				multiple: 'add'
			});

			// Insert images
			new_frame.on( 'select update insert', function(event) {

				var selection,
					state = new_frame.state(),
					i = 0,
					images = [],
					element,
					data,
					$option = $current_option.find('.tb-sortable-option'),
					$new_items;

				if ( typeof event !== 'undefined' ) {
	 				selection = event; // multiple items
	 			} else {
	 				selection = state.get('selection'); // single item
	 			}

				selection.map( function( attachment ){

					element = attachment.toJSON();

					images[i] = {
						id: element.id,
						title: element.title
					}

					if ( element.sizes['tb_thumb'] ) {
						images[i]['preview'] = element.sizes['tb_thumb'].url;
					} else {
						images[i]['preview'] = element.sizes['full'].url;
					}

					i++;
				});

				data = {
					action: 'themeblvd_add_'+$option.data('type')+'_item',
					security: $option.data('security'),
					data: {
						option_name: $option.data('name'),
						option_id: $option.data('id'),
						items: images
					}
				};

				$.post(ajaxurl, data, function( response ){

					// Append new item
					$current_option.find('.item-container').append( response );

					// Cache the items just added
					var $new_items = $current_option.find('.item-container .item').slice(-images.length);

					// Make it green for a bit to indicate it was just added
					$new_items.addClass('add');
					window.setTimeout(function(){
						$new_items.removeClass('add');
					}, 500);

					// Show "Delete All Items" button
					$current_option.find('.delete-sortable-items').fadeIn(200);

					// Bind "Delete Item" button
					$new_items.find('.delete-sortable-item').on( 'click', function(){

						var $link = $(this),
							$option = $link.closest('.section-sortable'),
							$item = $link.closest('.item');

						tbc_confirm($link.attr('title'), {'confirm':true}, function(r) {
					    	if(r) {
								$item.addClass('delete');
								window.setTimeout(function(){
									$item.remove();
									if ( ! $option.find('.item-container .item').length ) {
										$option.find('.delete-sortable-items').fadeOut(200);
									}
								}, 500);
					        }
					    });
					    return false;
					});

					// Bind toggle for displaying options
					$new_items.find('.toggle').on('click', function(){

						var $el = $(this);

						if ( $el.closest('.item-handle').hasClass('closed') ) {
							$el.closest('.item-handle').removeClass('closed');
							$el.closest('.item').find('.item-content').show();
						} else {
							$el.closest('.item-handle').addClass('closed');
							$el.closest('.item').find('.item-content').hide();
						}

						return false;
					});

					$new_items.find('.item-handle h3').each(function(){

						var $el = $(this),
							$trigger = $el.closest('.item').find('.handle-trigger');

						if ( $trigger.is('select') ) {
							$el.closest('.item').find('.item-handle h3').text( $trigger.find('option[value="'+$trigger.val()+'"]').text() );
						} else {
							$el.closest('.item').find('.item-handle h3').text( $trigger.val() );
						}
					});

					$new_items.find('.handle-trigger').on( 'change', function(){

						var $el = $(this);

						if ( $el.is('select') ) {
							$el.closest('.item').find('.item-handle h3').text( $el.find('option[value="'+$el.val()+'"]').text() );
						} else {
							$el.closest('.item').find('.item-handle h3').text( $el.val() );
						}

					});

					// Match options
					$new_items.closest('.subgroup').find('.match-trigger .of-input').each(function(){
						var $el = $(this);
						$el.closest('.subgroup').find('.match .of-input').val($el.val());
					});

					// Setup general scripts for options
					$new_items.themeblvd('options', 'setup');

				});

			});

			// Open the modal.
			new_frame.open();
		}
	};
})(jQuery);

/**
 * Use jQuery UI slider to setup an option
 * for the user to change column widths.
 */
(function($) {
	themeblvd_column_widths = {

		/**
		 * Apply click actions initially when loaded.
		 */
		init: function( $options ) {

			$options.find('.section-column_widths').each(function(){
				themeblvd_column_widths.run( $(this).closest('.subgroup.columns') );
			});

		},

		/**
		 * Create the slider object
		 */
		run: function( $section ) {

			var defaults = {
				10: {
					2: {
						values: [0, 5, 10],
						display: ['1/2', '1/2']
					},
					3: {
						values: [0, 3, 7, 10],
						display: ['3/10', '2/5', '3/10']
					},
					4: {
						values: [0, 2, 5, 8, 10],
						display: ['1/5', '3/10', '3/10', '1/5']
					},
					5: {
						values: [0, 2, 4, 6, 8, 10],
						display: ['1/5', '1/5', '1/5', '1/5', '1/5']
					},
					6: {
						values: [0, 1, 3, 5, 7, 9, 10],
						display: ['1/10', '1/5', '1/5', '1/5', '1/5', '1/10']
					}
				},
				12: {
					2: {
						values: [0, 6, 12],
						display: ['1/2', '1/2']
					},
					3: {
						values: [0, 4, 8, 12],
						display: ['1/3', '1/3', '1/3']
					},
					4: {
						values: [0, 3, 6, 9, 12],
						display: ['1/4', '1/4', '1/4', '1/4']
					},
					5: {
						values: [0, 2, 4, 8, 10, 12],
						display: ['1/6', '1/6', '1/3', '1/6', '1/6']
					},
					6: {
						values: [0, 2, 4, 6, 8, 10, 12],
						display: ['1/6', '1/6', '1/6', '1/6', '1/6', '1/6']
					}
				}
			};

			var id = $section.find('.slider').attr('id'),
				grid = 12, // 10 or 12
				columns = 3, // 1-5
				total = 0,
				fraction = '',
				numerator = 0,
				denominator = 0,
				current = $section.find('.column-width-input').val(),
				init_values = [];

			grid = $section.find('.select-grid-system').val(); // 10 or 12
			columns = $section.find('.select-col-num').val();

			// Bind changes
			$section.find('.select-col-num').off('change.ui-slider'); // Avoid duplicates
			$section.find('.select-col-num').on('change.ui-slider', themeblvd_column_widths.change );

			$section.find('.select-grid-system').off('change.ui-slider'); // Avoid duplicates
			$section.find('.select-grid-system').on('change.ui-slider', themeblvd_column_widths.change );

			// If one or no columns, don't run jQuery ui slider
			if ( columns == 0 ) {
				return;
			} else if ( columns == 1 ) {
				$section.find('.slider').append('<div class="column-preview col-1" style="width:100%"><span class="text">100%</span></div>');
				$section.find('.column-width-input').val('1/1').trigger('themeblvd_update_columns');
				return;
			}

			if ( current ) {

				current = current.split('-');
				columns = current.length;

				for ( var i = 0; i <= columns; i++ ) {
					if ( i === 0 ) {
						init_values[i] = 0;
					} else if ( i == columns ) {
						init_values[i] = grid;
					} else {
						fraction = current[i-1].split('/');
						total += (grid*fraction[0])/fraction[1];
						init_values[i] = total;
					}
				}
			} else {
				init_values = defaults[grid][columns]['values'];
				current = defaults[grid][columns]['display'];
				$section.find('.column-width-input').val(current.join('-')).trigger('themeblvd_update_columns');
			}

			// Setup jQuery UI slider instance
			$('#'+id).slider({
				range: 'max',
				min: 0,
				max: grid,
				step: 1,
				values: init_values,
				create: function( event, ui ) {

					var i,
						left = 0,
						width = 0,
						grid_display = '';

					// Setup display columns with visible
					// fractions for the user.
					for ( i = 1; i <= columns; i++ ) {

						$section.find('.slider').append('<div class="column-preview col-'+i+'"><span class="text">'+current[i-1]+'</span></div>');

						width = ((init_values[i]-init_values[i-1])/grid)*100;
						$section.find('.col-'+i).css('width', width+'%');

						if ( i > 1 ) {
							$section.find('.col-'+i).css('left', left+'%');
						}

						left += width;
					}

					// Add grid presentation
					left = 0;
					grid_display = '<div class="grid-display grid-'+grid+'">';

					for ( i = 1; i <= grid; i++ ) {
						grid_display += '<div class="grid-column grid-col-'+i+'"></div>';
					}

					grid_display += '</div>';

					$section.find('.slider').append(grid_display);

					for ( i = 1; i <= grid; i++ ) {
						left += ((1/grid)*100);
						$section.find('.grid-col-'+i).css('left', left+'%' );
					}

				},
				slide: function( event, ui ) {

					var index = $(ui.handle).index(),
						values = ui.values,
						count = values.length;

					// First and last can't be moved
					if ( index == 1 || index == count ) {
						return false;
					}

					var $container = $(ui.handle).closest('.column-widths-wrap'),
						$option = $container.find('.column-width-input'),
						current_val = ui.value,
						next_val = values[index],
						prev_val = values[index-2],
						next_col = 0,
						prev_col = 0,
						prev_col_fraction = '',
						next_col_fraction = '',
						next_numerator = 0,
						prev_numerator = 0,
						prev_final = '',
						final_val = '',
						final_fractions = [],
						left = 0,
						width = 0;

					// Do not allow handles to pass or touch each other
					if ( current_val <= prev_val || current_val >= next_val ) {
						return false;
					}

					// Size columns before and after handle
					prev_numerator = current_val-prev_val;
					next_numerator = next_val-current_val;
					prev_col = index-1;
					next_col = index;

					// Reduce previous column fraction
					prev_col_fraction = themeblvd_column_widths.reduce(prev_numerator, grid);
					prev_col_fraction = prev_col_fraction[0].toString()+'/'+prev_col_fraction[1].toString();

					// Reduce next column fraction
					next_col_fraction = themeblvd_column_widths.reduce(next_numerator, grid);
					next_col_fraction = next_col_fraction[0].toString()+'/'+next_col_fraction[1].toString();

					// Set hidden fraction placeholders for reference
					$container.find('input[name="col['+prev_col+']"]').val(prev_col_fraction);
					$container.find('input[name="col['+next_col+']"]').val(next_col_fraction);

					// Update final option
					prev_final = $option.val();
					prev_final = prev_final.split('-');

					for ( var i = 1; i <= prev_final.length; i++ ) {

						if ( i == prev_col ) {
							final_val += prev_col_fraction;
						} else if ( i == next_col ) {
							final_val += next_col_fraction;
						} else {
							final_val += prev_final[i-1];
						}

						if ( i != prev_final.length ) {
							final_val += '-';
						}

					}

					$option.val(final_val).trigger('themeblvd_update_columns');

					// Re-set display columns with visible
					// fractions for the user.
					final_fractions = final_val.split('-');

					for ( i = 1; i <= columns; i++ ) {

						width = ((values[i]-values[i-1])/grid)*100;
						$section.find('.col-'+i).css('width', width+'%');
						$section.find('.col-'+i+' .text').text(final_fractions[i-1]);

						if ( i > 1 ) {
							$section.find('.col-'+i).css('left', left+'%');
						}

						left += width;
					}
				}
			});

		},

		/**
		 * Adjust the number of columns or grid system, and re-run
		 */
		change: function (){

			var $el = $(this),
				$slider = $('#'+$el.data('slider'));

			if ( $slider.data('uiSlider') ) {
				$slider.slider('destroy');
			}

			$slider.html('').closest('.column-widths-wrap').find('.column-width-input').val('');
			themeblvd_column_widths.run( $el.closest('.subgroup.columns') );
		},

		/**
		 * Reduce fraction
		 */
		reduce: function (numerator, denominator){
			var gcd = function gcd(a,b){
				return b ? gcd(b, a%b) : a;
			};
			gcd = gcd(numerator,denominator);
			return [numerator/gcd, denominator/gcd];
		}
	};
})(jQuery);

/**
 * Show alert popup. Used for warnings and confirmations,
 * mostly intended to be used with AJAX actions.
 */
(function($) {
	tbc_alert = {
		init: function( alert_text, alert_class, selector ) {

		  	// Available classes:
			// success (green)
			// reset (red)
			// warning (yellow)

			// Initial HTML markup
			var alert_markup	= '	<div id="tb-alert"> \
										<div class="tb-alert-inner">  \
											<div class="tb-alert-pad">  \
												<div class="tb-alert-message"> \
													<p>Replace this with message text.</p> \
												</div> \
											</div><!-- .tb-alert-pad (end) --> \
										</div><!-- .tb-alert-inner (end) --> \
									 </div><!-- .tb-alert (end) -->';

			// Add initial markup to site
			if( ! selector )
				selector = 'body';

			$(selector).append(alert_markup);

			var	$this 			= $('#tb-alert'),
				window_height 	= $(window).height();

			// Insert dynamic elements into markup
			$this.addClass('tb-'+alert_class);
			$this.find('.tb-alert-message p').text(alert_text);

			// Position it, but only if it's being applied to the entire body.
			if( selector == 'body' )
			{
				$this.animate({'top' : ( window_height - ( window_height-75 ) ) + $(window).scrollTop() + "px"}, 100);
			}

			// Show it and fade it out 1.5 secs later
			$this.fadeIn(500, function(){
				setTimeout( function(){
					$this.fadeOut(500, function(){
						$this.remove();
					});

		      	}, 1500);
			});

		}
	};
})(jQuery);

/**
 * Confirmation
 */
(function($){
	tbc_confirm = function( string, args, callback ) {

		var $body = $('body'), $window = $(window), $outer, $inner, $buttons;

		var default_args = {
			'confirm'		:	false, 		// Ok and Cancel buttons
			'verify'		:	false,		// Yes and No buttons
			'input'			:	false, 		// Text input (can be true or string for default text)
			'input_desc'	:	'',			// Description for below input
			'textOk'		:	'Ok',		// Ok button default text
			'textCancel'	:	'Cancel',	// Cancel button default text
			'textYes'		:	'Yes',		// Yes button default text
			'textNo'		:	'No',		// No button default text
			'class'			:	''			// CSS class to add
		};

		if( args ) {
			for(var index in default_args) {
				if( typeof args[index] == "undefined" ) {
					args[index] = default_args[index];
				}
			}
		}

		// Append intial output
		$body.append('<div class="appriseOverlay" id="aOverlay"></div>');
		$body.append('<div class="appriseOuter"></div>');
		$outer = $('.appriseOuter');
		$outer.append('<div class="appriseInner"></div>');
		$inner = $('.appriseInner');
		$inner.append(string);
		$outer.css('left', ( $window.width() - $outer.width() ) / 2+$window.scrollLeft() + "px");
		$outer.css('top', '100px').fadeIn(200);

		// CSS class
		if ( args && args['class'] ) {
			$outer.addClass(args['class']);
		}

		// Append text input
		if ( args && args['input'] ) {

            if ( typeof(args['input']) == 'string') {
                $inner.append('<div class="aInput"><input type="text" class="aTextbox" t="aTextbox" value="' + args['input'] + '" /></div>');
            } else if ( typeof(args['input']) == 'object') {
                $inner.append($('<div class="aInput"></div>').append(args['input']));
            } else {
                $inner.append('<div class="aInput"><input type="text" class="aTextbox" t="aTextbox" /></div>');
            }

            if ( args['input_desc'] ) {
            	$outer.find('.aTextbox').after('<label>'+args['input_desc']+'</label>');
            }

            $outer.find('.aTextbox').focus();
		}

		// Append buttons
		$inner.append('<div class="aButtons"></div>');
		$buttons = $('.aButtons');

		if ( args ) {

			if ( args['confirm'] || args['input'] ) {
				$buttons.append('<button class="button-primary" value="ok">'+args['textOk']+'</button>');
				$buttons.append('<button class="button-secondary" value="cancel">'+args['textCancel']+'</button>');
			} else if ( args['verify'] ) {
				$buttons.append('<button class="button-primary" value="ok">'+args['textYes']+'</button>');
				$buttons.append('<button class="button-secondary" value="cancel">'+args['textNo']+'</button>');
			} else {
				$buttons.append('<button class="button-primary" value="ok">'+args['textOk']+'</button>');
			}

		} else {
			$buttons.append('<button class="button-primary" value="ok">Ok</button>');
		}

		$(document).keydown(function(e) {
			if ( $('.appriseOverlay').is(':visible')) {
				if ( e.keyCode == 13) {
					$('.aButtons > button[value="ok"]').click();
				}
				if ( e.keyCode == 27) {
					$('.aButtons > button[value="cancel"]').click();
				}
			}
		});

		var aText = $('.aTextbox').val();

		$('.aTextbox').keyup(function() {
			aText = $(this).val();
		});

		$buttons.find('button').on('click', function() {

			$('.appriseOverlay').remove();
			$outer.remove();

			if ( callback) {
				var wButton = $(this).attr("value");
				if ( wButton=='ok') {
					if ( args) {
						if ( args['input']) {
							callback(aText);
						} else {
							callback(true);
						}
					} else {
						callback(true);
					}
				} else if(wButton=='cancel') {
					callback(false);
				}
			}
		});
	}
})(jQuery);

/**
 * Any items that needs to executed
 * after DOM is loaded.
 */
jQuery(document).ready(function($) {

	// Icon Browser
	$('.themeblvd-icon-browser').themeblvd('options', 'setup');
	$('.themeblvd-icon-browser').themeblvd('options', 'bind');

	$('.themeblvd-icon-browser .select-icon').on( 'click', function(){

		var $elem = $(this),
			$browser = $elem.closest('.themeblvd-icon-browser'),
			icon = $elem.data('icon');

		$browser.find('.select-icon').removeClass('selected');
		$elem.addClass('selected');

		$browser.find('.icon-selection').val(icon);
		$browser.find('.media-toolbar-secondary').find('.fa, span, img').remove();

		if ( $elem.hasClass('select-image-icon') ) {
			$browser.find('.media-toolbar-secondary').append('<img src="'+$elem.find('img').attr('src')+'" /><span>'+icon+'</span>');
		} else {
			$browser.find('.media-toolbar-secondary').append('<i class="fa fa-'+icon+' fa-2x fa-fw"></i><span>'+icon+'</span>');
		}
		return false;
	});

	// Texture browser
	$('.themeblvd-texture-browser').themeblvd('options', 'setup');
	$('.themeblvd-texture-browser').themeblvd('options', 'bind');

	if ( $.isFunction( $.fn.wpColorPicker ) ) {
		$('#texture-browser-perview-color').wpColorPicker({
			change: function() {
				$('.themeblvd-texture-browser .select-texture span').css('background-color', $('#texture-browser-perview-color').val() );
			}
		});
	}

	$('.themeblvd-texture-browser .wp-color-result').attr('title', 'Temporary Preview Color');
	$('.themeblvd-texture-browser .select-texture span').css('background-color', $('#texture-browser-perview-color').val() );

	$('.themeblvd-texture-browser .select-texture').on( 'click', function(){

		var $el = $(this);

		$el.closest('.themeblvd-texture-browser').find('.select-texture').each(function(){
			$(this).removeClass('selected');
		});

		$el.addClass('selected');

		$el.closest('.themeblvd-texture-browser').find('.texture-selection').val( $el.data('texture') );
		$el.closest('.themeblvd-texture-browser').find('.current-texture').text( $el.data('texture-name') );

		return false;
	});

	// Theme Options reset handling
    $('#themeblvd_options_page .reset-button').click(function(){
		tbc_confirm('<h3>'+themeblvd.reset_title+'</h3>'+themeblvd.reset, {'confirm':true}, function(r) {
	    	if(r) {
	        	// Add in reset so our sanitizaiton callback reconizes.
	        	$('#themeblvd_options_page').append('<input type="hidden" name="reset" value="true" />');

	        	// Submit form
	        	$('#themeblvd_options_page').submit();
	        }
	    });
	    return false;
	});

	$('#themeblvd_options_page .clear-button').click(function(){
		tbc_confirm('<h3>'+themeblvd.clear_title+'</h3>'+themeblvd.clear, {'confirm':true}, function(r) {
	    	if(r) {

	        	var form = $('#themeblvd_options_page'),
	        		option_id = form.find('input[name="option_page"]').val();

	        	// Clear form's action so we don't go to options.php
	        	// and WP Settings API handling.
	        	form.attr('action', '');

	        	// Add in reset so our sanitizaiton callback reconizes.
	        	form.append('<input type="hidden" name="themeblvd_clear_options" value="'+option_id+'" />');

	        	// Submit form
	        	form.submit();

	        }
	    });
	    return false;
	});
});