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
				if (  target.hasClass(settings.openClass) )
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
					if (  currentViewport > settings.viewport ) {

						// Add class "expanded" so we can keep track of
						// whether this re-sizing is occuring on a mobile
						// device or not. If we're on mobile, the "forced_open"
						// class should never get added.
						target.show().removeClass(settings.openClass+' '+settings.closedClass).addClass('expanded');

					} else {

						// Make sure this wasn't triggered by re-sizing on mobile
						if (  target.hasClass('expanded') )
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

	var window_width = $(window).width();

	// ---------------------------------------------------------
	// Menus
	// ---------------------------------------------------------

	if ( themeblvd.superfish == 'true' ) {

		// Activate Superfish
		$('ul.sf-menu').superfish({ speed: 200 }).addClass('sf-menu-with-fontawesome');

	}

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
	if ( themeblvd.magnific_popup == 'true' ) {

		$('.gallery').append('<div class="clear"></div>');

		$('.gallery').each(function(){

			var gallery = $(this);

			gallery.addClass('themeblvd-gallery');

			gallery.find('.gallery-item a').each(function(){

				// Add bootstrap thumbnail class
				$(this).find('img').addClass('thumbnail');

				// Account for any parameters being added to
				// URL, like with W3 Total Cache, for example.
				var url = this.href.split('?');
					url = url[0];

				// Append lightbox and hover effect if thumb links to an image
				if ( url.match(/\.(jpe?g|png|bmp|gif|tiff?)$/i ) ) {
				   	$(this).addClass('lightbox-gallery-item mfp-image image-button');
				}

			});

		});
	}

	// ---------------------------------------------------------
	// Lightbox
	// ---------------------------------------------------------

	// Bind magnifico
	if ( themeblvd.magnific_popup == 'true' ) {

		var remove_delay = 0, main_class = '';

		if ( themeblvd.lightbox_animation != 'none' ) {
			remove_delay = 160;
			main_class = 'themeblvd-mfp-'+themeblvd.lightbox_animation;
		}

		// Galleries
		$('.themeblvd-gallery').each(function() {

			$(this).find('.themeblvd-lightbox').each(function(){
				$(this).removeClass('themeblvd-lightbox').addClass('lightbox-gallery-item');
			});

			$(this).magnificPopup({
				disableOn: themeblvd.lightbox_mobile_gallery,
				delegate: 'a.lightbox-gallery-item',
				gallery: { enabled: true },
				image: {
					cursor: null,
				},
				iframe: {
					// Add bottom bar for iframes in gallery
					markup: '<div class="mfp-iframe-scaler">'+
					            '<div class="mfp-close"></div>'+
					            '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
					            '<div class="mfp-bottom-bar">'+
					              '<div class="mfp-title"></div>'+
					              '<div class="mfp-counter"></div>'+
					            '</div>'+
					          '</div>'
				},
				removalDelay: remove_delay,
				mainClass: main_class
			});

		});

		// Standard, non-gallery lightbox links
		$('.themeblvd-lightbox').magnificPopup({
			disableOn: themeblvd.lightbox_mobile,
			removalDelay: remove_delay,
			mainClass: main_class,
			image: {
				cursor: null,
			}
		});

		// Specific lightbox for iframe (videos, google maps)
		// so we can designate a separate option to disable
		// on mobile. -- Videos seem to be harder to view in
		// the lightbox than images on mobile.
		$('.themeblvd-lightbox.lightbox-iframe').magnificPopup({
			disableOn: themeblvd.lightbox_mobile_iframe,
			type: 'iframe',
			removalDelay: remove_delay,
			mainClass: main_class
		});

		// Localize
		$.extend( true, $.magnificPopup.defaults, {
			tClose: themeblvd.lightbox_close,
			tLoading: themeblvd.lightbox_loading,
			gallery: {
				tPrev: themeblvd.lightbox_previous,
				tNext: themeblvd.lightbox_next,
				tCounter: themeblvd.lightbox_counter
			},
			image: {
				tError: themeblvd.lightbox_error
			},
			ajax: {
				tError: themeblvd.lightbox_error
			}
		});

	}

	// Animations on lightbox thumbnails.
	if ( themeblvd.thumb_animations == 'true' && window_width >= 768 ) {

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

	if ( themeblvd.featured_animations == 'true' || themeblvd.image_slide_animations == 'true' ) {

		var selector = "";

		if ( themeblvd.featured_animations == 'true' && themeblvd.image_slide_animations == 'true' ) {
			selector = ".featured-image a, a.slide-thumbnail-link";
		} else if ( themeblvd.featured_animations == 'true' ) {
			selector = ".featured-image a";
		} else if ( themeblvd.image_slide_animations == 'true' ) {
			selector = "a.slide-thumbnail-link";
		}

		if ( window_width >= 768 ) {

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

	if ( themeblvd.retina_logo == 'true' ) {
		var image = $('.tb-image-logo img'),
			image_2x = image.attr('data-image-2x');

		// If a retina-otimized image was detected
		// and should be displayed
		if ( window.devicePixelRatio >= 1.5 && image_2x ) {
			// Display 2x image w/fixed original width
			image.attr({
				src: image_2x
			});
		}
	}

	// ---------------------------------------------------------
	// Bootstrap Integration
	// ---------------------------------------------------------

	if ( themeblvd.bootstrap == 'true' ) {

		// Add standard table classes to calendar widget
		$('#calendar_wrap table').addClass('table table-bordered');

		// Tabs - Automatic fixed height
		// This allows the user to have the set of tabs
		// automatically stay the height of the tallest tab.
		$('.tabbable.fixed-height').each(function(){

			var tallest = 0;

			$(this).find('.tab-pane').each(function(){

				var currentHeight = $(this).height();

				if ( currentHeight > tallest )
					tallest = currentHeight;

			});

			$(this).find('.tab-pane').height(tallest);
		});

		// Collapsables expanded
		// This basically just toggles the Plus/Minus fontawesome
		// icon we've incorporated into the triggers for the toggles.
		$('.collapse').on('show.bs.collapse', function() {

			// Toggle is opening, add "active-trigger" class and
			// change icon to a minus sign.
			$(this).closest('.panel').find('.panel-heading a').addClass('active-trigger').find('.switch-me').removeClass('fa-plus-circle').addClass('fa-minus-circle');

		});

		$('.collapse').on('hide.bs.collapse', function() {

			// Toggle is closing, remove "active-trigger" class and
			// change icon to a plus sign.
			$(this).closest('.panel').find('.panel-heading a').removeClass('active-trigger').find('.switch-me').removeClass('fa-minus-circle').addClass('fa-plus-circle');

		});

		// And now if the user has wrapped a set of "toggles" into an
		// accordian, this will attach them all.
		var accordion_id;
		$('.tb-accordion').each(function(){
			accordion_id = $(this).attr('id');
			$(this).find('.panel-heading a').each(function(){
				$(this).attr('data-parent', '#'+accordion_id);
			});
		});

		// Carousel thumbnail navigation
		$('.carousel .carousel-thumb-nav li').click(function(){
			var el = $(this);
			el.closest('.carousel-thumb-nav').find('li').removeClass('active');
			el.addClass('active');
		});

		$('.carousel').on('slid.bs.carousel', function () {

			var el = $(this),
				data = el.data('bs.carousel'),
				current = data.getActiveIndex()+1;

			el.find('.carousel-thumb-nav li').removeClass('active');
			el.find('.carousel-thumb-nav li:nth-child('+current+')').addClass('active');

		});

		// Deep linking Bootstrap tabs
		if ( themeblvd.tabs_deep_linking == 'true' ) {

			// Note: To deep link to a tab, you need to prefix
			// the ID of the tab with "tab_" like this:
			// http://your-site.com/page-with-tabs/#tab_id_of_tab

			var hash = document.location.hash;

			if ( hash && hash.indexOf('tab_') != -1 ) {
				$('.element-tabs a[href="'+hash.replace('tab_', '')+'"]').tab('show');
			}
		}

	}

});