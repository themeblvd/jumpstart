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
			$this.find('.widget-name-arrow').live('click', function(){
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
			$this.find('.tooltip-link').live('click', function(){
				var message = $(this).attr('title');
				tbc_confirm(message, {'textOk':'Ok'});
				return false;						
			});
			
			// Delete item by ID passed through link's href
			$this.find('.delete-me').live('click', function(){
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
					text = el.find('option[value='+value+']').text();
				el.find('.textbox').text(text);
			});
    	},
    	
    	// Setup custom option combos
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
						var el = $(this), value = el.find('select').val(), text = el.find('option[value='+value+']').text();
						el.find('.textbox').text(text);
					});
			
	    			// Custom content
	    			$this.find('.custom-content-types').each(function(){
	    				var el = $(this), value = el.find('select').val(), parent = el.closest('.subgroup');
	    				
	    				if(value == 'external')
	    				{
	    					parent.find('.page-content').show();
	    				}
	    				else if (value == 'raw')
	    				{
	    					parent.find('.raw-content').show();
	    				}
	    				else if (value == 'widget_area')
	    				{
	    					parent.find('.widget_area-content').show();
	    				}
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
    					{
    						el.find('.receiver').show();
    					}    					
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
	    					{
	    						checkbox.closest('.item').addClass('active').find('.social_media-input').show();
	    					}
	    					else
	    					{
	    						checkbox.closest('.item').removeClass('active').find('.social_media-input').hide();
	    					}
						});					
    				});
    				
    				// Google font selection
    				$this.find('.section-typography .of-typography-face').each(function(){
    					var el = $(this), value = el.val();
    					if( value == 'google' )
    					{
    						el.closest('.section-typography').find('.google-font').fadeIn('fast');
    					}
    					else
    					{
    						el.closest('.section-typography').find('.google-font').hide();
    					}
    				});
    				
    				// Homepage Content
	    			$this.find('#section-homepage_content').each(function(){
    					var value = $(this).find('input:checked').val();
    					if( value != 'custom_layout' )
    					{
    						$this.find('#section-homepage_custom_layout').hide();
    					}				
    				});
	    				
	    		}
	    		// Apply all binded actions. This will only need
	    		// to be called once on the original page load.
	    		else if(type == 'bind')
	    		{
	    			
	    			// Fancy Select
	    			$this.find('.tb-fancy-select select').live('change', function(){
		    			var el = $(this), value = el.val(), text = el.find('option[value='+value+']').text();
						el.closest('.tb-fancy-select').find('.textbox').text(text);
	    			});
									
	    			// Custom content
	    			$this.find('.custom-content-types select').live('change', function(){
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
	    			$this.find('.columns .column-num').live('change', function(){
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
	    			$this.find('.tabs .tabs-num').live('change', function(){
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
	    			$this.find('.column-content-types select.select-type').live('change', function(){
						var section = $(this).closest('.section-content'), type = $(this).val();
	    				section.find('.column-content-type').hide();
	    				section.find('.column-content-type-'+type).show();
	    			});
	    			
	    			// Show/Hide groupings
    				$this.find('.show-hide .trigger input').live('click', function(){
    					var checkbox = $(this);
    					if( checkbox.is(':checked') )
    					{
    						checkbox.closest('.show-hide').find('.receiver').fadeIn('fast');
    					}
    					else
    					{
    						checkbox.closest('.show-hide').find('.receiver').hide();
    					}    					
    				});
    				
    				// Configure logo
    				$this.find('.section-logo .select-type select').live('change', function(){
    					var el = $(this), parent = el.closest('.section-logo'), value = el.val();
						parent.find('.logo-item').hide();
						parent.find('.'+value).show();					
    				});
    				
    				// Configure social media buttons
    				$this.find('.section-social_media .checkbox').live('click', function(){
    					var checkbox = $(this);
						if( checkbox.is(':checked') )
    					{
    						checkbox.closest('.item').addClass('active').find('.social_media-input').fadeIn('fast');
    					}
    					else
    					{
    						checkbox.closest('.item').removeClass('active').find('.social_media-input').hide();
    					}
    				});
    				
    				// Google font selection
    				$this.find('.section-typography .of-typography-face').live('change', function(){
    					var el = $(this), value = el.val();
    					if( value == 'google' )
    					{
    						el.closest('.section-typography').find('.google-font').fadeIn('fast');
    					}
    					else
    					{
    						el.closest('.section-typography').find('.google-font').hide();
    					}
    				});

    				// Homepage Content
	    			$this.find('#section-homepage_content input:checked').live('change', function() {  					
    					if( $(this).val() == 'custom_layout' )
    					{
    						$this.find('#section-homepage_custom_layout').fadeIn('fast');
    					}
    					else
    					{
    						$this.find('#section-homepage_custom_layout').fadeOut('fast');
    					}			
    				});
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
		{
			return themeblvd_shared[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
		}
		else if( typeof method === 'object' || ! method )
		{
			return themeblvd_shared.init.apply( this, arguments );
		}
		else
		{
			$.error( 'Method ' +  method + ' does not exist.' );
		}    
	
	};

})(jQuery);

/**
 * Show alert popup. Used for warnings and confirmations,
 * mostly intended to be used with AJAX actions.
 */
 
(function ($) {
	tbc_alert = {
		init: function(alert_text, alert_class)
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
			$('body').append(alert_markup);
			
			var	$this 			= $('#tb-alert'),
				window_height 	= $(window).height();
			
			// Insert dynamic elements into markup
			$this.addClass('tb-'+alert_class);
			$this.find('.tb-alert-message p').text(alert_text);
			
			// Position it
			$this.animate({'top' : ( window_height - ( window_height-75 ) ) + $(window).scrollTop() + "px"}, 100);
			
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
    $('#themeblvd_theme_options .reset-button').click(function(){
		tbc_confirm('<h3>'+themeblvd.reset_title+'</h3>'+themeblvd.reset, {'confirm':true}, function(r)
		{
	    	if(r)
	        {
	        	// Add in reset so our sanitizaiton callback reconizes.
	        	$('#themeblvd_theme_options').append('<input type="hidden" name="reset" value="true" />');
	        	
	        	// Submit form
	        	$('#themeblvd_theme_options').submit();
	        }
	    });
	    return false;
	});
	
	$('#themeblvd_theme_options .clear-button').click(function(){
		tbc_confirm('<h3>'+themeblvd.clear_title+'</h3>'+themeblvd.clear, {'confirm':true}, function(r)
		{
	    	if(r)
	        {
	        	// Add in reset so our sanitizaiton callback reconizes.
	        	$('#themeblvd_theme_options').append('<input type="hidden" name="clear" value="true" />');
	        	
	        	// Submit form
	        	$('#themeblvd_theme_options').submit();
	        }
	    });
	    return false;
	});
});