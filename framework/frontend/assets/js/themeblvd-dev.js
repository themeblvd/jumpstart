/**
 * Responsive navigation menu.
 */

(function($) {
    $.fn.toggleMenu = function(options) {
        
        var settings = $.extend({
	      'viewport' 	: 768,
	      'openClass'	: 'mobile-open',
	      'closedClass'	: 'mobile-closed'
	    }, options);
        
        // Run it
        this.each(function(){

        	var el = $(this),
        		target = $(el.attr('href')),
        		currentViewport,
        		timeout = false;
		    
		    // Toggle on click
		    el.click(function(){
			    if( target.hasClass(settings.openClass) )
				    target.slideUp().removeClass(settings.openClass).addClass(settings.closedClass);
			    else
			    	target.slideDown().removeClass(settings.closedClass).addClass(settings.openClass);

			    return false;
		    });
		    
		    // Window re-sizing  - For those people screwing with the 
		    // browser window and are not actually on a mobile device.
		    $( window ).resize( function() {
				if ( false !== timeout )
					clearTimeout( timeout );
		
				timeout = setTimeout( function() {
					currentViewport = $(window).width();
					if( currentViewport > settings.viewport )
					{
						// Add class "expanded" so we can keep track of 
						// whether this re-sizing is occuring on a mobile 
						// device or not. If we're on mobile, the "forced_open" 
						// class should never get added.
						target.show().removeClass(settings.openClass+' '+settings.closedClass).addClass('expanded');
					}
					else
					{
						// Make sure this wasn't triggered by re-sizing on mobile
						if( target.hasClass('expanded') )
							target.hide().removeClass('expanded');
					}
						
				}, 100 );
			});
        });
    };
})(jQuery);

/**
 * Prints out the inline javascript needed for the frontend framework. 
 */

jQuery(document).ready(function($) {
	
	// ---------------------------------------------------------
	// Menus
	// ---------------------------------------------------------
	
	// Activate Superfish
	$('ul.sf-menu').superfish().addClass('sf-menu-with-fontawesome'); 
	
	// Adjust sub indicators to use fontawesome
	$('ul.sf-menu-with-fontawesome > li > a .sf-sub-indicator').replaceWith('<i class="sf-sub-indicator icon-caret-down"></i>');
	$('ul.sf-menu-with-fontawesome ul li a .sf-sub-indicator').replaceWith('<i class="sf-sub-indicator icon-caret-right"></i>');
	
	// Allow bootstrap "nav-header" class in menu items.
	// Note: For primary navigation will only work on levels 2-3
	// (1) ".sf-menu li li.nav-header" 	=> Primary nav dropdowns
	// (2) ".menu li.nav-header" 		=> Standard custom menu widget
	// (3) ".subnav li.nav-header" 		=> Theme Blvd Horizontal Menu widget
	var menu_text;
	$('.sf-menu li li.nav-header, .menu li.nav-header, .subnav li.nav-header').each(function(){	
		menu_text = $(this).find('> a').text();
		$(this).prepend('<span>'+menu_text+'</span>').find('> a').remove();
	});
	
	// Allow bootstrap "divider" class in menu items.
	// Note: For primary navigation will only work on levels 2-3
	$('.sf-menu li li.divider, .menu li.divider').html('');
	
	// Fontawesome icons in menu items
	var classes, name;
	$('[class^="menu-icon-"]').each(function(){
		// For this to work, "menu-icon-whatever" must be the first 
		// class inputted on the menu item.
		classes = $(this).attr('class');
		name = classes.substr(classes.indexOf('menu-icon') + 10).split(' ')[0];
		$(this).find('> a, > span').prepend('<i class="icon-'+name+'"></i>');
	});
	
	// Create responsive navigation toggle similar to Bootstrap.
	// Why not use bootstrap default functionality? Because it 
	// works by adjusting the height of the navigation menu, 
	// which does not account for the menu having any drop downs 
	// within, and this won't work for us, unfortuantely.
	/*
	var target, collapse;
	$('.btn-navbar').click(function(){
		target = $(this).attr('data-target');
		if( $(target).hasClass('open') )
		{
			// Toggle is open, so let's close it.
			$(target).slideUp('fast');
			$(target).removeClass('open');
			$(target).addClass('closed');
		}
		else
		{
			// Toggle is closed, so let's open it.
			$(target).slideDown('fast');
			$(target).removeClass('closed');
			$(target).addClass('open');
		}
		return false;
	});
	*/
		
	// ---------------------------------------------------------
	// No-click dropdowns
	// ---------------------------------------------------------
	
	$('ul.sf-menu .no-click').find('a:first').click(function(){
		return false;
	});
	
	// ---------------------------------------------------------
	// Gallery Shortcode Integration
	// ---------------------------------------------------------

	$('.gallery').append('<div class="clear"></div>');

	$('.gallery').each(function(){

		var current_gallery = $(this),
			gallery_id = current_gallery.attr('id');
		
		current_gallery.find('.gallery-item a').each(function(){
			// Add bootstrap thumbnail class
			$(this).find('img').addClass('thumbnail');
			// Append lightbox if it's an image
			if(this.href.match(/\.(jpe?g|png|bmp|gif|tiff?)$/i)){
			    $(this).attr('rel','themeblvd_lightbox['+gallery_id+']');
			    $(this).addClass('image-button');
			}
		});
	
	});
	
	// ---------------------------------------------------------
	// Lightbox
	// ---------------------------------------------------------
		
	$('a[rel^="themeblvd_lightbox"], a[rel^="featured_themeblvd_lightbox"]').prettyPhoto({
		social_tools: false, // Share icons are not compatible with IE9
		deeplinking: false,
		overlay_gallery: false,
		show_title: false
	});
	
	$('a[rel^="themeblvd_lightbox"]').prepend('<span class="enlarge"></span>');
	
	$('a[rel^="themeblvd_lightbox"]').hover(
		function () {
			var el = $(this);
			el.find('.enlarge').stop(true, true).animate({
				opacity: 1
			}, 100 );
			el.find('img').stop(true, true).animate({
				opacity: 0.6
			}, 100 );
		}, 
		function () {
			var el = $(this);
			el.find('.enlarge').stop(true, true).animate({
				opacity: 0
			}, 100 );
			el.find('img').stop(true, true).animate({
				opacity: 1
			}, 100 );
		}
	);
	
	// ---------------------------------------------------------
	// Featured Image overlay links
	// ---------------------------------------------------------
	
	$('.featured-image a').hover(
		function () {
			var el = $(this);
			el.find('.image-overlay-bg').stop(true, true).animate({
				opacity: 0.2
			}, 300 );
			el.find('.image-overlay-icon').stop(true, true).animate({
				opacity: 1
			}, 300 );
		}, 
		function () {
			var el = $(this);
			el.find('.image-overlay-bg').stop(true, true).animate({
				opacity: 0
			}, 300 );
			el.find('.image-overlay-icon').stop(true, true).animate({
				opacity: 0
			}, 300 );
		}
	);
	
	// ---------------------------------------------------------
	// Tabs
	// ---------------------------------------------------------
	
	$('.tb-tabs').each(function(){
		var el = $(this);
		el.find('.tab-content').hide();
		el.find('.tab-content:first').show();
	});
    $('.tb-tabs .tab-nav a').click(function() {
		var el = $(this), parent = el.closest('.tb-tabs'), activetab = el.attr('href');
		parent.find('.tab-nav li').removeClass('active');
		el.closest('li').addClass('active');
		parent.find('.tab-content').hide();
		parent.find(activetab).show(); // Use show instead of nicer looking fade to avoid jumping
        return false;
    });
    
    // ---------------------------------------------------------
	// Toggle
	// ---------------------------------------------------------
	
	$('.tb-toggle').each(function(){
		$(this).find('.toggle-content').hide();
	});
	$('.tb-toggle a.toggle-trigger').click(function(){
		var el = $(this), parent = el.closest('.tb-toggle');
		
		if( el.hasClass('active') )
		{
			parent.find('.toggle-content').hide();
			el.removeClass('active');
		}
		else
		{
			parent.find('.toggle-content').show();
			el.addClass('active');
		}
		return false;
	});
	
	// ---------------------------------------------------------
	// Jump Menu
	// ---------------------------------------------------------
	
	$(".tb-jump-menu").change(function(e) {
		window.location.href = $(this).val();
	})
	
	// ---------------------------------------------------------
	// Logo w/retina display support
	// ---------------------------------------------------------
	
	var width, 
		height,
		image = $('.tb-image-logo img'),
		image_2x = image.attr('data-image-2x');
	
	// If a retina-otimized image was detected
	if(image_2x)
	{
		// Display 2x image w/fixed original width
		image.attr({
			width: image.width(),
			src: image_2x
		});
	}
	
	// ---------------------------------------------------------
	// Bootstrap Integration
	// ---------------------------------------------------------
	
	// Add standard table classes to calendar widget
	$('#calendar_wrap table').addClass('table table-bordered');
	
	// Collapsables expanded
	// This basically just toggles the Plus/Minus fontawesome 
	// icon we've incorporated into the triggers for the toggles.
	$('.collapse').on('show', function() {
		// Toggle is opening, add "active-trigger" class and 
		// change icon to a minus sign.
		$(this).closest('.accordion-group').find('.accordion-toggle').addClass('active-trigger').find('.switch-me').removeClass('icon-plus-sign').addClass('icon-minus-sign');
	});
	$('.collapse').on('hide', function() {
		// Toggle is closing, remove "active-trigger" class and 
		// change icon to a plus sign.
		$(this).closest('.accordion-group').find('.accordion-toggle').removeClass('active-trigger').find('.switch-me').removeClass('icon-minus-sign').addClass('icon-plus-sign');
	});
	// And now if the user has wrapped a set of "toggles" into an 
	// accordian, this will attach them all.
	var accordion_id;
	$('.tb-accordion').each(function(){
		accordion_id = $(this).attr('id');
		$(this).find('.accordion-toggle').each(function(){
			$(this).attr('data-parent', '#'+accordion_id);
		});
	});
	
});