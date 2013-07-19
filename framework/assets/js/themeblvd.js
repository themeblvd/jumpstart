// ---------------------------------------------------------
// Responsive navigation menu.
// ---------------------------------------------------------

(function($) {
	"use strict";
    $.fn.toggleMenu = function(options) {

        var settings = $.extend({
			'viewport'		: 768,
			'openClass'		: 'mobile-open',
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

// ---------------------------------------------------------
// Frontend jQuery on DOM load
// ---------------------------------------------------------

jQuery(document).ready(function($) {

	"use strict";

	// ---------------------------------------------------------
	// Menus
	// ---------------------------------------------------------

	if(themeblvd.superfish)
	{
		// Activate Superfish
		$('ul.sf-menu').superfish({ speed: 200 }).addClass('sf-menu-with-fontawesome');

		// Adjust sub indicators to use fontawesome
		$('ul.sf-menu-with-fontawesome > li > a.sf-with-ul').append('<i class="sf-sub-indicator icon-caret-down"></i>');
		$('ul.sf-menu-with-fontawesome ul li a.sf-with-ul').append('<i class="sf-sub-indicator icon-caret-right"></i>');

	}

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

	// ---------------------------------------------------------
	// No-click dropdowns
	// ---------------------------------------------------------

	$('ul.sf-menu .no-click').find('a:first').click(function(){
		return false;
	});

	// ---------------------------------------------------------
	// Gallery Shortcode Integration
	// ---------------------------------------------------------

	// Since our gallery integration is specifically designed
	// to work with Magnific Popup, if it isn't included,
	// we'll halt it all together.
	if(themeblvd.magnific_popup)
	{

		$('.gallery').append('<div class="clear"></div>');

		$('.gallery').each(function(){

			var gallery = $(this);

			gallery.addClass('themeblvd-gallery');

			gallery.find('.gallery-item a').each(function(){

				// Add bootstrap thumbnail class
				$(this).find('img').addClass('thumbnail');

				// Append lightbox and hover effect if thumb links to an image
				if(this.href.match(/\.(jpe?g|png|bmp|gif|tiff?)$/i)) {
				   	$(this).addClass('lightbox-gallery-item mfp-image image-button');
				}

			});

		});
	}

	// ---------------------------------------------------------
	// Lightbox
	// ---------------------------------------------------------

	// Bind magnifico
	if(themeblvd.magnific_popup)
	{

		// Standard, non-gallery lightbox links
		$('.themeblvd-lightbox').magnificPopup({
			image: {
				cursor: null,
				tError: themeblvd.lightbox_error
			}
		});

		// Galleries
		$('.themeblvd-gallery').each(function() {
			$(this).magnificPopup({
				delegate: 'a.lightbox-gallery-item',
				gallery: { enabled: true },
				image: {
					cursor: null,
					tError: themeblvd.lightbox_error
				}
			});
		});

	}

	// Animations on lightbox thumbnails.
	if(themeblvd.thumb_animations)
	{

		$('.image-button').prepend('<span class="enlarge"></span>');

		$('.image-button').hover(
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
	}

	// ---------------------------------------------------------
	// Featured Image overlay links
	// ---------------------------------------------------------

	if(themeblvd.featured_animations || themeblvd.image_slide_animations)
	{
		var selector = "";
		if(themeblvd.featured_animations && themeblvd.image_slide_animations)
			selector = ".featured-image a, a.slide-thumbnail-link";
		else if(themeblvd.featured_animations)
			selector = ".featured-image a";
		else if(themeblvd.image_slide_animations)
			selector = "a.slide-thumbnail-link";

		$(selector).hover(
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
	}

	// ---------------------------------------------------------
	// Jump Menu
	// ---------------------------------------------------------

	$(".tb-jump-menu").change(function() {
		window.location.href = $(this).val();
	});

	// ---------------------------------------------------------
	// Logo w/retina display support
	// ---------------------------------------------------------

	if(themeblvd.retina_logo)
	{
		var image = $('.tb-image-logo img'),
			image_2x = image.attr('data-image-2x');

		// If a retina-otimized image was detected
		// and should be displayed
		if(window.devicePixelRatio >= 1.5 && image_2x)
		{
			// Display 2x image w/fixed original width
			image.attr({
				src: image_2x
			});
		}
	}

	// ---------------------------------------------------------
	// Bootstrap Integration
	// ---------------------------------------------------------

	if(themeblvd.bootstrap)
	{

		// Add standard table classes to calendar widget
		$('#calendar_wrap table').addClass('table table-bordered');

		// Tabs - Automatic fixed height
		// This allows the user to have the set of tabs
		// automatically stay the height of the tallest tab.
		$('.tabbable.fixed-height').each(function(){
			var tallest = 0;
			$(this).find('.tab-pane').each(function(){
				var currentHeight = $(this).height();
				if(currentHeight > tallest)
					tallest = currentHeight;
			});
			$(this).find('.tab-pane').height(tallest);
		});

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
	}

});