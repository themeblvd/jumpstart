/**
 * Prints out the inline javascript that is shared between all
 * framework admin areas.
 */

(function($){

	// Setup methods for themeblvd namespace
	var themeblvd_shared = {

    	// All general binded events
    	init : function()
    	{
    		var $this = this;

    		// Toggle widgets
			$this.on( 'click', '.widget-name-arrow', function() {
				var el = $(this), closed = el.closest('.widget-name').hasClass('widget-name-closed');
				if(closed)
				{
					el.closest('.widget').find('.widget-content').show();
					el.closest('.widget').find('.widget-name').removeClass('widget-name-closed');
				}
				else
				{
					el.closest('.widget').find('.widget-content').hide();
					el.closest('.widget').find('.widget-name').addClass('widget-name-closed');
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
				tbc_confirm($(this).attr('title'), {'confirm':true}, function(r)
				{
			    	if(r)
			        {
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
    	options : function( type )
    	{
    		return this.each(function(){

				var $this = $(this);

	    		// Apply all actions that need applying when an
	    		// option set is loaded. This will be called any
	    		// time a new options set is inserted.
	    		if(type == 'setup')
	    		{

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

	    			// Columns only
	    			$this.find('.columns').each(function(){
	    				var el = $(this), i = 1, num = el.find('.column-num').val();
	    				el.find('.column-width').hide();
	    				el.find('.column-width-'+num).show();
	    				el.find('.section-content').hide();
	    				while (i <= num)
						{
							el.find('.col_'+i).show();
							i++;
	    				}
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

    				// Show/Hide groupings
    				$this.find('.show-hide').each(function(){
    					var el = $(this), checkbox = el.find('.trigger input');
    					if( checkbox.is(':checked') )
    						el.find('.receiver').show();
    				});

    				// Show/Hide toggle grouping (triggered with <select> to target specific options)
    				$this.find('.show-hide-toggle').each(function(){
    					var el = $(this), value = el.find('.trigger select').val();
    					el.find('.receiver').hide();
    					el.find('.receiver-'+value).show();
    				});

    				// Configure logo
    				$this.find('.section-logo').each(function(){
    					var el = $(this), value = el.find('.select-type select').val();
						el.find('.logo-item').hide();
						el.find('.'+value).show();
    				});

    				// Configure social media buttons
    				$this.find('.section-social_media').each(function(){
    					var el = $(this);
						el.find('.social_media-input').hide();
						el.find('.checkbox').each(function(){
							var checkbox = $(this);
							if( checkbox.is(':checked') )
	    						checkbox.closest('.item').addClass('active').find('.social_media-input').show();
	    					else
	    						checkbox.closest('.item').removeClass('active').find('.social_media-input').hide();
						});
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

    				// Color Picker
					$this.find('.colorSelector').each(function(){
						var Othis = this; //cache a copy of the this variable for use inside nested function
						var initialColor = $(Othis).next('input').attr('value');
						$(this).ColorPicker({
							color: initialColor,
							onShow: function (colpkr) {
								$(colpkr).fadeIn(500);
								return false;
							},
							onHide: function (colpkr) {
								$(colpkr).fadeOut(500);
								return false;
							},
							onChange: function (hsb, hex, rgb) {
								$(Othis).children('div').css('backgroundColor', '#' + hex);
								$(Othis).next('input').attr('value','#' + hex);
							}
						});
					});

    				// Image Options
					$this.find('.of-radio-img-img').click(function(){
						$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
						$(this).addClass('of-radio-img-selected');
					});
					$this.find('.of-radio-img-label').hide();
					$this.find('.of-radio-img-img').show();
					$this.find('.of-radio-img-radio').hide();

	    		}
	    		// Apply all binded actions. This will only need
	    		// to be called once on the original page load.
	    		else if(type == 'bind')
	    		{

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

	    			// Column widths and number
	    			$this.on( 'change', '.columns .column-num', function() {
	    				var el = $(this), i = 1, num = el.val(), parent = el.closest('.columns');
	    				parent.find('.column-width').hide();
	    				parent.find('.column-width-'+num).fadeIn('fast');
	    				parent.find('.section-content').hide();
	    				while (i <= num)
						{
							parent.find('.col_'+i).show();
							i++;
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

	    			// Show/Hide groupings
    				$this.on( 'click', '.show-hide .trigger input', function() {
    					var checkbox = $(this);
    					if( checkbox.is(':checked') )
    						checkbox.closest('.show-hide').find('.receiver').fadeIn('fast');
    					else
    						checkbox.closest('.show-hide').find('.receiver').hide();
    				});

    				// Show/Hide toggle grouping (triggered with <select> to target specific options)
    				$this.on( 'change', '.show-hide-toggle .trigger select', function() {
    					var el = $(this), value = el.val(), group = el.closest('.show-hide-toggle');
    					group.find('.receiver').hide();
    					group.find('.receiver-'+value).show();
    				});

    				// Configure logo
    				$this.on( 'change', '.section-logo .select-type select', function() {
    					var el = $(this), parent = el.closest('.section-logo'), value = el.val();
						parent.find('.logo-item').hide();
						parent.find('.'+value).show();
    				});

    				// Configure social media buttons
    				$this.on( 'click', '.section-social_media .checkbox', function() {
    					var checkbox = $(this);
						if( checkbox.is(':checked') )
    						checkbox.closest('.item').addClass('active').find('.social_media-input').fadeIn('fast');
    					else
    						checkbox.closest('.item').removeClass('active').find('.social_media-input').hide();
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
	    		}
	    		// Apply media uploader from themeblvd_media_uploader object.
	    		// This incorporates the Media Uploader in WP 3.5+
	    		else if(type == 'media-uploader')
	    		{
	    			// Check to make sure wp.media object exists.
	    			// If it doesn't ...
	    			// (1) We're using an older version of WP and the
	    			// legacy uploader.
	    			// (2) We're using a plugin that uses legacy uploader.
	    			// (3) the WP 3.5+'s media uploader JS files haven't been
	    			// enqueued properly.
	    			if(typeof wp !== 'undefined' && typeof wp.media !== 'undefined')
	    				themeblvd_media_uploader.init($this);
	    		}

    		});
    	},

    	// Widgets
		widgets : function()
		{
			return this.each(function(){
				var el = $(this);
				el.find('.widget-content').hide();
				el.find('.widget-name').addClass('widget-name-closed');
			});
		},

		// Accordion
		accordion : function()
		{
			return this.each(function(){
				var el = $(this);

				// Set it up
				el.find('.element-content').hide();
				el.find('.element-content:first').show();

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
		 * Apply click actions initially when loaded.
		 */
		init : function(options)
		{
			options.find('.upload-button').click(function(event){
				themeblvd_media_uploader.add_file( $(this).closest('.section-upload') );
			});

			options.find('.remove-image, .remove-file').click(function(){
				themeblvd_media_uploader.remove_file( $(this).closest('.section-upload') );
			});
		},

		/**
		 * Trigger media uploader modal to insert an image.
		 */
		add_file : function(current_option)
		{
			var file_frame,
				upload_type = current_option.find('.trigger').data('type'),
				title = current_option.find('.trigger').data('title'),
				select = current_option.find('.trigger').data('select'),
				css_class = current_option.find('.trigger').data('class'),
				media_type = upload_type == 'standard' ? '' : 'image',
				multiple = upload_type == 'quick_slider' ? true : false, // @todo future feature of Quick Slider
				workflow = upload_type == 'quick_slider' ? 'post' : 'select'; // @todo future feature of Quick Slider

			if( upload_type == 'video' )
				media_type = 'video';

			// event.preventDefault();

			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				frame: workflow,
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

			// Image selected and inserted
			file_frame.on( 'select', function() {

				// Grab the selected attachment.
				var attachment = file_frame.state().get('selection').first(),
					remove_text = current_option.find('.trigger').data('remove'),
					helper_text;

				current_option.find('.image-url').val(attachment.attributes.url);
				if( attachment.attributes.type == 'image' )
					current_option.find('.screenshot').empty().hide().append('<img src="' + attachment.attributes.url + '"><a class="remove-image">Remove</a>').slideDown('fast');

				if(upload_type == 'logo')
					current_option.find('.image-width').val(attachment.attributes.width);

				if(upload_type == 'video')
					current_option.find('.video-url').val(attachment.attributes.url);

				if(upload_type == 'slider')
				{
					current_option.find('.image-id').val(attachment.attributes.id);
					current_option.find('.image-title').val(attachment.attributes.title);

					helper_text = current_option.find('.image-title').val();
					if( helper_text )
						current_option.closest('.widget').find('.slide-summary').text(helper_text).fadeIn(200);
				}

				if( upload_type != 'video' )
				{
					current_option.find('.upload-button').unbind().addClass('remove-file').removeClass('upload-button').val(remove_text);
					current_option.find('.of-background-properties').slideDown();

					current_option.find('.remove-image, .remove-file').click(function() {
						themeblvd_media_uploader.remove_file( $(this).closest('.section-upload') );
			        });
				}
			});

			// Modal window closed w/no insertion of an image. So, we need to
			// reset the upload button to avoid weird results.
			file_frame.on( 'close', function() {
				current_option.find('.upload-button').unbind('click');
				current_option.find('.upload-button').click(function(){
					themeblvd_media_uploader.add_file( $(this).closest('.section-upload') );
				});
			});

			// Finally, open the modal.
			file_frame.open();
		},

		/**
		 * Remove current image and put back "Upload" button.
		 */
		remove_file : function(current_option)
		{
			var upload_text = current_option.find('.trigger').data('upload'),
				upload_type = current_option.find('.trigger').data('type');

			if( upload_type == 'slider' )
				current_option.closest('.widget').find('.slide-summary').removeClass('image video').hide().text('');

			current_option.find('.remove-image').hide();
			current_option.find('.upload').val('');
			current_option.find('.of-background-properties').hide();
			current_option.find('.screenshot').slideUp();
			current_option.find('.remove-file').addClass('upload-button').removeClass('remove-file').val(upload_text);
			current_option.find('.upload-button').click(function(){
				themeblvd_media_uploader.add_file( $(this).closest('.section-upload') );
			});
		}
	};
})(jQuery);

/**
 * Show alert popup. Used for warnings and confirmations,
 * mostly intended to be used with AJAX actions.
 */

(function($) {
	tbc_alert = {
		init : function(alert_text, alert_class, selector)
		{

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
	tbc_confirm = function(string, args, callback)
	{
		var default_args =
			{
			'confirm'		:	false, 		// Ok and Cancel buttons
			'verify'		:	false,		// Yes and No buttons
			'input'			:	false, 		// Text input (can be true or string for default text)
			'animate'		:	false,		// Groovy animation (can true or number, default is 400)
			'textOk'		:	'Ok',		// Ok button default text
			'textCancel'	:	'Cancel',	// Cancel button default text
			'textYes'		:	'Yes',		// Yes button default text
			'textNo'		:	'No'		// No button default text
			}

		if(args)
		{
			for(var index in default_args)
			{
				if(typeof args[index] == "undefined") args[index] = default_args[index];
			}
		}

		var aHeight = $(document).height();
		var aWidth = $(document).width();
		$('body').append('<div class="appriseOverlay" id="aOverlay"></div>');
		$('.appriseOverlay').css('height', aHeight).css('width', aWidth).fadeIn(100);
		$('body').append('<div class="appriseOuter"></div>');
		$('.appriseOuter').append('<div class="appriseInner"></div>');
		$('.appriseInner').append(string);
		$('.appriseOuter').css("left", ( $(window).width() - $('.appriseOuter').width() ) / 2+$(window).scrollLeft() + "px");

		if(args)
		{
			if(args['animate'])
			{
				var aniSpeed = args['animate'];
				if(isNaN(aniSpeed)) { aniSpeed = 400; }
				$('.appriseOuter').css('top', '-200px').show().animate({top:"100px"}, aniSpeed);
			}
			else
			{
				$('.appriseOuter').css('top', '100px').fadeIn(200);
			}
		}
		else
		{
			$('.appriseOuter').css('top', '100px').fadeIn(200);
		}

		if(args)
		{
			if(args['input'])
			{
				if(typeof(args['input'])=='string')
				{
					$('.appriseInner').append('<div class="aInput"><input type="text" class="aTextbox" t="aTextbox" value="'+args['input']+'" /></div>');
				}
				else
				{
					$('.appriseInner').append('<div class="aInput"><input type="text" class="aTextbox" t="aTextbox" /></div>');
				}
				$('.aTextbox').focus();
			}
		}

		$('.appriseInner').append('<div class="aButtons"></div>');
		if(args)
		{
			if(args['confirm'] || args['input'])
			{
				$('.aButtons').append('<button value="ok">'+args['textOk']+'</button>');
				$('.aButtons').append('<button value="cancel">'+args['textCancel']+'</button>');
			}
			else if(args['verify'])
			{
				$('.aButtons').append('<button value="ok">'+args['textYes']+'</button>');
				$('.aButtons').append('<button value="cancel">'+args['textNo']+'</button>');
			}
			else
			{
				$('.aButtons').append('<button value="ok">'+args['textOk']+'</button>');
			}
		}
		else
		{
			$('.aButtons').append('<button value="ok">Ok</button>');
		}

		$(document).keydown(function(e)
		{
			if($('.appriseOverlay').is(':visible'))
			{
				if(e.keyCode == 13)
				{
					$('.aButtons > button[value="ok"]').click();
				}
				if(e.keyCode == 27)
				{
					$('.aButtons > button[value="cancel"]').click();
				}
			}
		});

		var aText = $('.aTextbox').val();
		if(!aText) { aText = false; }
		$('.aTextbox').keyup(function()
		{
			aText = $(this).val();
		});

		$('.aButtons > button').click(function()
		{
			$('.appriseOverlay').remove();
			$('.appriseOuter').remove();
			if(callback)
			{
				var wButton = $(this).attr("value");
				if(wButton=='ok')
				{
					if(args)
					{
						if(args['input'])
						{
							callback(aText);
						}
						else
						{
							callback(true);
						}
					}
					else
					{
						callback(true);
					}
				}
				else if(wButton=='cancel')
				{
					callback(false);
				}
			}
		});
	}
})(jQuery);

/**
 * Theme Options reset handling
 */

jQuery(document).ready(function($) {
    $('#themeblvd_options_page .reset-button').click(function(){
		tbc_confirm('<h3>'+themeblvd.reset_title+'</h3>'+themeblvd.reset, {'confirm':true}, function(r)
		{
	    	if(r)
	        {
	        	// Add in reset so our sanitizaiton callback reconizes.
	        	$('#themeblvd_options_page').append('<input type="hidden" name="reset" value="true" />');

	        	// Submit form
	        	$('#themeblvd_options_page').submit();
	        }
	    });
	    return false;
	});

	$('#themeblvd_options_page .clear-button').click(function(){
		tbc_confirm('<h3>'+themeblvd.clear_title+'</h3>'+themeblvd.clear, {'confirm':true}, function(r)
		{
	    	if(r)
	        {

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