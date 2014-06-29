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
	// Parallax background effect
	// ---------------------------------------------------------

	$('.desktop .tb-parallax').each(function(){

		var $el = $(this),
			$window = $(window),
			intensity = $el.data('parallax'),
			y = 0,
			y_pos = 0,
			diff = 0,
			bg_repeat = $el.css('background-repeat'),
			img_url = $el.css('background-image'),
			img_url = img_url.match(/^url\("?(.+?)"?\)$/),
			img;

		// Setup parallax intensity
		switch ( intensity ) {
			case 1: // Least intensity
				y_pos = 10;
				break;
			case 2:
				y_pos = 9;
				break;
			case 3:
				y_pos = 8;
				break;
			case 4:
				y_pos = 7;
				break;
			case 5:
				y_pos = 6;
				break;
			case 6:
				y_pos = 5;
				break;
			case 7:
				y_pos = 4;
				break;
			case 8:
				y_pos = 3;
				break;
			case 9:
				y_pos = 2;
				break;
			case 10: // Highest intensity
				y_pos = 1;
				break;
		}

		if ( img_url[1] ) {

		    img_url = img_url[1];
		    img = new Image();

		    // just in case it is not already loaded
		    $(img).load(function () {

		    	var scrollTop     = $window.scrollTop(),
				    elementOffset = $el.offset().top,
				    distance      = (elementOffset - scrollTop);

				y = - ( distance / y_pos );

				$el.css({ 'background-position': 'center '+ y + 'px' });

		    	// Parallax effect
		    	$window.scroll(function() {

		        	// Disable for tablet/mobile viewport size
		        	if ( $window.width() <= 992 ) {
		        		return;
		        	}

					scrollTop = $window.scrollTop(),
				    elementOffset = $el.offset().top,
				    distance = (elementOffset - scrollTop);

					y = - ( distance / y_pos );

		            $el.css({ 'background-position': 'center '+ y + 'px' });

		        });

		    	// Apply background cover, only when container
			    // is wider than background image.
		    	if ( $el.hasClass('tb-bg-cover') && bg_repeat == 'no-repeat' ) {

			        if ( $el.outerWidth() > img.width ) {
						$el.css('background-size', 'cover');
					} else {
						$el.css('background-size', 'auto');
					}

					$window.resize(function(){
						if ( $el.outerWidth() > img.width ) {
							$el.css('background-size', 'cover');
						} else {
							$el.css('background-size', 'auto');
						}
					});
				}

		    }); // end image on load

		    img.src = img_url;

		}
	});

	// ---------------------------------------------------------
	// Custom Buttons
	// ---------------------------------------------------------

	if ( themeblvd.custom_buttons == 'true' ) {
		$('.tb-custom-button').hover(
			function() {
				var $el = $(this);
				$el.css({
					'background-color': $el.data('bg-hover'),
					'color': $el.data('text-hover')
				});
			}, function() {
				var $el = $(this);
				$el.css({
					'background-color': $el.data('bg'),
					'color': $el.data('text')
				});
			}
		);
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

		// Carousel indicator dots navigation -- This fix allows
		// us to integrate links on the slides better by reducing
		// the space around navigation indicator dots.
		$('.carousel-indicators').each(function(){

			var $el = $(this),
				width = ( 14 * $el.find('li').length );

			$el.css({
				'width': width + 'px',
				'margin-left': '-'+(width/2)+'px'
			});
		});

		// When user interacts with carousel controls or slide links,
		// stop auto rotate.
		$('.carousel').each(function(){

			var $el = $(this);

			$el.find('.slide-link, .carousel-indicators li, .carousel-control').on('click', function(){
				$el.carousel('pause');
			});
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

		// Tooltips
		$('.tb-tooltip').tooltip();

	}

	// ---------------------------------------------------------
	// Milestones
	// ---------------------------------------------------------

	$('.desktop .tb-milestones .milestone').each(function() {
		$(this).appear(function() {

			var $el = $(this),
				num = parseInt($el.find('.num').text());

			$el.find('.num').countTo({
				from: 0,
				to: num,
				speed: 1500,
				refreshInterval: 30
			});

		},{accX: 0, accY: 0});
	});

});

// ---------------------------------------------------------
// Allow animation when items appear w/scroll
// script by Michael Hixson and Alexander Brovikov
// ---------------------------------------------------------

(function($) {
    $.fn.appear = function(fn, options) {

        var settings = $.extend({

            //arbitrary data to pass to fn
            data: undefined,

            //call fn only on the first appear?
            one: true,

            // X & Y accuracy
            accX: 0,
            accY: 0

        }, options);

        return this.each(function() {

            var t = $(this);

            //whether the element is currently visible
            t.appeared = false;

            if (!fn) {

                //trigger the custom event
                t.trigger('appear', settings.data);
                return;
            }

            var w = $(window);

            //fires the appear event when appropriate
            var check = function() {

                //is the element hidden?
                if (!t.is(':visible')) {

                    //it became hidden
                    t.appeared = false;
                    return;
                }

                //is the element inside the visible window?
                var a = w.scrollLeft();
                var b = w.scrollTop();
                var o = t.offset();
                var x = o.left;
                var y = o.top;

                var ax = settings.accX;
                var ay = settings.accY;
                var th = t.height();
                var wh = w.height();
                var tw = t.width();
                var ww = w.width();

                if (y + th + ay >= b &&
                    y <= b + wh + ay &&
                    x + tw + ax >= a &&
                    x <= a + ww + ax) {

                    //trigger the custom event
                    if (!t.appeared) t.trigger('appear', settings.data);

                } else {

                    //it scrolled out of view
                    t.appeared = false;
                }
            };

            //create a modified fn with some additional logic
            var modifiedFn = function() {

                //mark the element as visible
                t.appeared = true;

                //is this supposed to happen only once?
                if (settings.one) {

                    //remove the check
                    w.unbind('scroll', check);
                    var i = $.inArray(check, $.fn.appear.checks);
                    if (i >= 0) $.fn.appear.checks.splice(i, 1);
                }

                //trigger the original fn
                fn.apply(this, arguments);
            };

            //bind the modified fn to the element
            if (settings.one) t.one('appear', settings.data, modifiedFn);
            else t.bind('appear', settings.data, modifiedFn);

            //check whenever the window scrolls
            w.scroll(check);

            //check whenever the dom changes
            $.fn.appear.checks.push(check);

            //check now
            (check)();
        });
    };

    //keep a queue of appearance checks
    $.extend($.fn.appear, {

        checks: [],
        timeout: null,

        //process the queue
        checkAll: function() {
            var length = $.fn.appear.checks.length;
            if (length > 0) while (length--) ($.fn.appear.checks[length])();
        },

        //check the queue asynchronously
        run: function() {
            if ($.fn.appear.timeout) clearTimeout($.fn.appear.timeout);
            $.fn.appear.timeout = setTimeout($.fn.appear.checkAll, 20);
        }
    });

    //run checks when these methods are called
    $.each(['append', 'prepend', 'after', 'before', 'attr',
        'removeAttr', 'addClass', 'removeClass', 'toggleClass',
        'remove', 'css', 'show', 'hide'], function(i, n) {
        var old = $.fn[n];
        if (old) {
            $.fn[n] = function() {
                var r = old.apply(this, arguments);
                $.fn.appear.run();
                return r;
            }
        }
    });

})(jQuery);

// ---------------------------------------------------------
// Animation for number counting
// script by Matt Huggins
// ---------------------------------------------------------

(function ($) {
	$.fn.countTo = function (options) {
		options = options || {};

		return $(this).each(function () {
			// set options for current element
			var settings = $.extend({}, $.fn.countTo.defaults, {
				from:            $(this).data('from'),
				to:              $(this).data('to'),
				speed:           $(this).data('speed'),
				refreshInterval: $(this).data('refresh-interval'),
				decimals:        $(this).data('decimals')
			}, options);

			// how many times to update the value, and how much to increment the value on each update
			var loops = Math.ceil(settings.speed / settings.refreshInterval),
				increment = (settings.to - settings.from) / loops;

			// references & variables that will change with each update
			var self = this,
				$self = $(this),
				loopCount = 0,
				value = settings.from,
				data = $self.data('countTo') || {};

			$self.data('countTo', data);

			// if an existing interval can be found, clear it first
			if (data.interval) {
				clearInterval(data.interval);
			}
			data.interval = setInterval(updateTimer, settings.refreshInterval);

			// initialize the element with the starting value
			render(value);

			function updateTimer() {
				value += increment;
				loopCount++;

				render(value);

				if (typeof(settings.onUpdate) == 'function') {
					settings.onUpdate.call(self, value);
				}

				if (loopCount >= loops) {
					// remove the interval
					$self.removeData('countTo');
					clearInterval(data.interval);
					value = settings.to;

					if (typeof(settings.onComplete) == 'function') {
						settings.onComplete.call(self, value);
					}
				}
			}

			function render(value) {
				var formattedValue = settings.formatter.call(self, value, settings);
				$self.text(formattedValue);
			}
		});
	};

	$.fn.countTo.defaults = {
		from: 0,               // the number the element should start at
		to: 0,                 // the number the element should end at
		speed: 1000,           // how long it should take to count between the target numbers
		refreshInterval: 100,  // how often the element should be updated
		decimals: 0,           // the number of decimal places to show
		formatter: formatter,  // handler for formatting the value before rendering
		onUpdate: null,        // callback method for every time the element is updated
		onComplete: null       // callback method for when the element finishes updating
	};

	function formatter(value, settings) {
		return value.toFixed(settings.decimals);
	}
}(jQuery));

// ---------------------------------------------------------
// Responsive navigation menu.
// script by Jason Bobich
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