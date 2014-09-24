// ---------------------------------------------------------
// Frontend jQuery on DOM load
// ---------------------------------------------------------

jQuery(document).ready(function($) {

	"use strict";

	var $window = $(window),
		window_width = $window.width(),
		$body = $('body'),
		$header = $('#branding'),
		$primary_menu = $('.tb-primary-menu');

	// ---------------------------------------------------------
	// Dynamic Body Classes
	// ---------------------------------------------------------

	// Add "mobile-on" and "tablet-on" classes to body,
	// only depending on viewport size.
	//
	// NOTE: These are different than the default "mobile"
	// and "desktop" classes filtered onto WP's body_class(),
	// which denote if user is on a true mobile device
	if ( window_width <= 767 ) {

		$body.addClass('mobile-on');
		$body.removeClass('tablet-on');

		if ( $header.hasClass('transparent') ) {
			$header.removeClass('transparent');
			$header.addClass('transparent-off');
		}

	} else if ( window_width <= 992 ) {
		$body.addClass('tablet-on');
		$body.removeClass('mobile-on');
	}

	$window.resize(function(){

		var window_width = $window.width();

		if ( window_width <= 767 ) {

			$body.addClass('mobile-on');
			$body.removeClass('tablet-on');

			if ( $header.hasClass('transparent') ) {
				$header.removeClass('transparent');
				$header.addClass('transparent-off');
			}

		} else if ( window_width <= 992 ) {

			$body.addClass('tablet-on');
			$body.removeClass('mobile-on');

			if ( $header.hasClass('transparent-off') ) {
				$header.removeClass('transparent-off');
				$header.addClass('transparent');
			}

		} else {

			$body.removeClass('tablet-on');
			$body.removeClass('mobile-on');

			if ( $header.hasClass('transparent-off') ) {
				$header.removeClass('transparent-off');
				$header.addClass('transparent');
			}
		}
	});

	// ---------------------------------------------------------
	// Menus
	// ---------------------------------------------------------

	// Responsive side menu
	if ( themeblvd.mobile_side_menu == 'true' && $primary_menu.hasClass('tb-to-side-menu') ) {

		// Add initial class that denotes the menu is hidden on
		// page load. The menu will be hidden on its own, but
		// this allows for CSS3 transitions.
		$body.addClass('side-menu-off');

		// Create empty wrapper for the side menu
		$('#wrapper').after('<div id="tb-side-menu-wrapper"><div class="wrap"></div></div>');

		// Generate content for side menu
		var $side_holder = $('#tb-side-menu-wrapper > .wrap'),
			$toggle_open = $('#primary-menu-open'),
			$toggle_close = $('#primary-menu-close'),
			$extras = $('.tb-to-side-menu'), // Any items that you want to be moved in the side menu location, add class "tb-to-side-menu"
			max = parseInt(themeblvd.mobile_menu_viewport_max);

		$header.find('.tb-search').clone().addClass('mini').appendTo( $side_holder );
		$primary_menu.clone().appendTo( $side_holder );
		$header.find('.tb-social-icons:first-child').clone().appendTo( $side_holder );

		// Adjust menu classes
		$side_holder.find('.tb-primary-menu').removeClass('sf-menu tb-primary-menu').addClass('tb-side-menu');

		// Adjust social media icon color
		if ( themeblvd.mobile_side_menu_icon_color ) {
			$side_holder.find('.tb-social-icons').removeClass('grey dark light flat color').addClass(themeblvd.mobile_side_menu_icon_color);
		}

		// Show menu
		$toggle_open.on('click', function(){
			$body.removeClass('side-menu-off').addClass('side-menu-on');
			$toggle_open.hide();
			$toggle_close.show();
		});

		// Close menu
		$toggle_close.on('click', function(){
			$body.removeClass('side-menu-on').addClass('side-menu-off');
			$toggle_close.hide();
			$toggle_open.show();
		});

		$('#wrapper').on('click', function(){
			$body.removeClass('side-menu-on').addClass('side-menu-off');
			$toggle_close.hide();
			$toggle_open.show();
		});

		$window.resize(function(){
			if ( $window.width() > max ) {
				$body.removeClass('side-menu-on').addClass('side-menu-off');
				$toggle_close.hide();
				$toggle_open.show();
			}
		});

	}

	// All side menus
	$('.tb-side-menu').on('click', '.tb-side-menu-toggle', function() {

		var $el = $(this);

		if ( $el.hasClass('open') ) {
			$el.next('.sub-menu, .sf-mega').slideUp(100);
			$el.removeClass('open fa-'+$el.data('close'));
			$el.addClass('fa-'+$el.data('open'));
		} else {
			$el.next('.sub-menu,  .sf-mega').slideDown(100);
			$el.removeClass('fa-'+$el.data('open'));
			$el.addClass('open fa-'+$el.data('close'));
		}
	});

	if ( themeblvd.sticky !== 'false' ) {

		// Build sticky menu
		var $sticky_spy = $(themeblvd.sticky),
			$sticky = $('<div id="sticky-menu" class="tb-sticky-menu"><div class="wrap sticky-wrap clearfix"></div></div>').appendTo( $sticky_spy );

		$header.find('.header-logo:first-child').clone().appendTo( $sticky.find('.sticky-wrap') );
		$header.find('.tb-search-popup').clone().appendTo( $sticky.find('.sticky-wrap') );
		$primary_menu.clone().appendTo( $sticky.find('.sticky-wrap') );

		// Sticky menu, make selector dynamic
		$sticky_spy.viewportChecker({
			classToAdd: 'visible',
			repeat: true,
			offset: parseInt(themeblvd.sticky_offset)
		});

	}

	// Activate Superfish
	if ( themeblvd.superfish == 'true' ) {
		$('ul.sf-menu').superfish({
			speed: 200,
			popUpSelector: '.non-mega-sub-menu,.sf-mega'
		});
	}

	// ---------------------------------------------------------
	// Sorting/Masonry
	// ---------------------------------------------------------

	$window.load(function() {
		$('.tb-isotope').each(function(){

			var $container = $(this),
				mode = 'fitRows';

			if ( $container.hasClass('tb-masonry') ) {
				mode = 'masonry';
			}

			$container.find('.post-wrap > .row').fadeIn(400, function(){

				var $iso = $container.find('.post-wrap > .row').isotope({
					layoutMode: mode,
					itemSelector: '.iso-item',
					filter: '*'
				});

				$container.find('.tb-loader').fadeOut(100);

				if ( $container.hasClass('tb-filter') ) {

					$container.find('.tb-filter-nav').fadeIn(400);

					$container.find('.filter-menu > li > a').on('click', function(){
						var $link = $(this);
						$container.find('.filter-menu > li').removeClass('active');
						$link.closest('li').addClass('active');
						$iso.isotope({ filter: $(this).data('filter') });
						return false;
					});
				}

			});

		});
	});

	// ---------------------------------------------------------
	// Floater popup
	// ---------------------------------------------------------

	$('.tb-floater .floater-trigger').on('click', function(){

		var $el = $(this),
			$popup = $el.closest('.tb-floater').find('.floater-popup');

		if ( $el.hasClass('open') ) {
			$el.stop().removeClass('open').html('<i class="fa fa-'+$el.data('open')+'"></i>');
			$popup.stop().fadeOut(200);
		} else {
			$el.stop().addClass('open').html('<i class="fa fa-'+$el.data('close')+'"></i>');
			$popup.stop().fadeIn(200);
		}

		return false;
	});

	// ---------------------------------------------------------
	// No-click dropdowns
	// ---------------------------------------------------------

	$('ul.sf-menu .no-click').find('a:first').click(function(){
		return false;
	});

	// ---------------------------------------------------------
	// Scroll-to-Top Button
	// ---------------------------------------------------------

	if ( themeblvd.scroll_to_top == 'true' ) {

		var $scroll_to_top = $('.tb-scroll-to-top');

		$window.scroll(function(){
			if ( $(this).scrollTop() > 400 ) {
				$scroll_to_top.fadeIn();
			} else {
				$scroll_to_top.fadeOut();
			}
		});

		$scroll_to_top.on( 'click', function(){
			$('html, body').animate({scrollTop : 0}, 400);
			return false;
		});

	}

	// ---------------------------------------------------------
	// Social Share Buttons
	// ---------------------------------------------------------

	$('.tb-share-button.popup').on('click', function(){
		if ( ! window.open( $(this).attr('href'), '', 'height=320, width=640, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no' ) ) {
			document.location.href = $(this).attr('href');
		}
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
	// Jump Menu
	// ---------------------------------------------------------

	$(".tb-jump-menu").change(function() {
		window.location.href = $(this).val();
	});

	// ---------------------------------------------------------
	// Logo w/retina display support
	// ---------------------------------------------------------

	if ( themeblvd.retina_logo == 'true' ) {

		// If a retina-otimized image was detected
		// and should be displayed
		if ( window.devicePixelRatio >= 1.5 ) {
			$('.tb-image-logo').each(function(){

				var $logo = $(this).find('img'),
					img_2x = $logo.data('image-2x');

				if (img_2x) {
					$logo.attr('src', img_2x);
				}

			});
		}

	}

	// ---------------------------------------------------------
	// Block sliders
	// ---------------------------------------------------------

	$('.tb-block-slider').each(function(){

		var $slider_wrap = $(this),
			$slider = $slider_wrap.find('.tb-block-slider-inner'),
			fx = 'slide',
			speed = $slider_wrap.data('timeout'),
			slideshow = true,
			nav = $slider_wrap.data('nav'),
			pause = false;

		if ( $slider.data('fx') ) {
			fx = $slider.data('fx');
		}

		if ( speed && speed !== '0' ) {
			speed = speed+'000';
		} else {
			slideshow = false;
		}

		if ( nav && nav !== '0' ) {
			nav = true;
		} else {
			nav = false;
		}

		if ( ! nav ) {
			pause = true;
		}

		$window.load(function() {
			$slider.flexslider({
				// smoothHeight: true,
				animation: fx,
				easing: 'swing',
				slideshowSpeed: speed,
				animationSpeed: '1000',
				slideshow: slideshow,
				directionNav: false,	// Using custom slider controls outputted with slider markup
				controlNav: false,
				pauseOnHover: pause,	// If nav exists, replace with manual action below
				pauseOnAction: false, 	// Replaced with manual action below
				start: function(){
					$slider_wrap.find('.tb-slider-arrows').fadeIn(100);
					$slider_wrap.find('.tb-loader').fadeOut(100);
				}
			});
		});

		if ( nav ) {

			// Manual pause on hover, will not continue when hovered off
			$slider.on('mouseover', function(){
				$slider.data('flexslider').flexslider('pause');
			});

			// Custom slider controls
			$slider_wrap.find('.tb-slider-arrows a').on('click', function(){

				if ( $(this).hasClass('next') ) {
					$slider.data('flexslider').flexslider('next');
				} else {
					$slider.data('flexslider').flexslider('prev');
				}

				$slider.data('flexslider').flexslider('pause');

				return false;
			});
		}
	});

	// ---------------------------------------------------------
	// Parallax background effect
	// ---------------------------------------------------------

	$('.desktop .tb-parallax').each(function(){

		var $el = $(this),
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
	// Google Maps
	// ---------------------------------------------------------

	if ( typeof google === 'object' && typeof google.maps === 'object' ) {
		$('.tb-map').each(function(){

			var $map = $(this);

			var init = function() {

		        var map_id = $map.find('.map-canvas').attr('id'),
		        	map_options,
		            map;

		        map_options = {
		            center: new google.maps.LatLng( $map.find('.map-center').data('lat'), $map.find('.map-center').data('long') ),
		            zoom: $map.data('zoom'),
		            draggable: $map.data('draggable'),
		            panControl: $map.data('pan_control'),
		            zoomControl: $map.data('zoom_control'),
		            mapMaker: false,
		            mapTypeControl: false,
		            mapTypeId: google.maps.MapTypeId.ROADMAP,
		            backgroundColor: 'transparent',
		            streetViewControl: false,
		            scrollwheel: false
		        };

		        map = new google.maps.Map( document.getElementById(map_id), map_options );

		        // Markers
		        $map.find('.map-marker').each(function(){

		        	var $marker = $(this);

			        var marker = new google.maps.Marker({
			            position: new google.maps.LatLng( $marker.data('lat'), $marker.data('long') ),
			            map: map,
			            title: $marker.data('name'),
			            icon: $marker.data('image'),
			            animation: google.maps.Animation.DROP
			        });

			        var infowindow = new google.maps.InfoWindow({
			            content: '<div class="map-marker-info">'+$marker.find('.map-marker-info').html()+'</div>'
			        });

			        google.maps.event.addListener(marker, 'click', function() {
			            infowindow.open(map,marker);
			        });

			    });


		        // Style map
		        var stylers = [], style = [], map_type;

		        stylers.push({saturation: $map.data('saturation')});
		        stylers.push({lightness: $map.data('lightness')});

		        if ( $map.data('hue') ) {
		        	stylers.push({hue: $map.data('hue')});
		        }

		        if ( stylers.length ) {

		            style = [{
		                featureType: "all",
		                elementType: "all",
		                stylers: stylers
		            }];

		            map_type = new google.maps.StyledMapType(style, {name:'tb_map_style'});
		            map.mapTypes.set('tb_map_style', map_type);
		            map.setMapTypeId('tb_map_style');
		        }

		    };

		    google.maps.event.addDomListener(window, 'load', init);

		});
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
		$('.tb-simple-slider .carousel-thumb-nav li').click(function(){
			var $el = $(this);
			$el.closest('.carousel-thumb-nav').find('li').removeClass('active');
			$el.addClass('active');
		});

		$('.tb-simple-slider').on('slid.bs.carousel', function () {

			var $el = $(this),
				data = $el.data('bs.carousel'),
				current = data.getActiveIndex()+1;

			$el.find('.carousel-thumb-nav li').removeClass('active');
			$el.find('.carousel-thumb-nav li:nth-child('+current+')').addClass('active');

		});

		// Carousel indicator dots navigation -- This fix allows
		// us to integrate links on the slides better by reducing
		// the space around navigation indicator dots.
		$('.tb-simple-slider .carousel-indicators').each(function(){

			var $el = $(this),
				width = ( 14 * $el.find('li').length );

			$el.css({
				'width': width + 'px',
				'margin-left': '-'+(width/2)+'px'
			});
		});

		// When user interacts with carousel controls or slide links,
		// stop auto rotate.
		$('.tb-simple-slider').each(function(){

			var $el = $(this);

			$el.find('.tb-thumb-link, .carousel-indicators li, .carousel-control').on('click', function(){
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
		$('.desktop .tb-tooltip').tooltip();

	}


	// ---------------------------------------------------------
	// Charts
	// ---------------------------------------------------------

	// Milestone percent
	if ( $.fn.easyPieChart != 'undefined' ) {
		if ( ! $body.hasClass('tb-scroll-effects') || $body.hasClass('mobile') ) {
			$('.tb-milestone-percent').each(function(){

				var $chart = $(this).find('.chart');

				$chart.easyPieChart({
					lineWidth: 10,
					size: 140,
					animate: 1000,
					barColor: $chart.data('color'),
					trackColor: $chart.data('track-color'),
					scaleColor: false,
					lineCap: 'square',
					easing: 'easeOutBounce'
				});
			});
		}
	}

	// Bar, Doughnut, Line, and Bar charts
	if ( typeof Chart !== 'undefined' ) {

		// Global settings
		// Chart.defaults.global.responsive = true;

		// Pie/Doughnut charts
		$('.tb-chart.pie, .tb-chart.doughnut').each(function(){

			var $el = $(this),
				type = 'pie',
				context = $el.find('canvas').get(0).getContext('2d'),
				data = [],
				chart;

			if ( $el.hasClass('doughnut') ) {
				type = 'doughnut';
			}

			$el.find('.data').each(function(){

				var $data = $(this);

				data.push({
					value: $data.data('value'),
					color: $data.data('color'),
					highlight: $data.data('highlight'),
					label: $data.data('label')
				});
			});

			if ( type == 'doughnut' ) {

				chart = new Chart(context).Doughnut(data, {
					showTooltips: $el.data('tooltips')
				});

			} else {

				chart = new Chart(context).Pie(data, {
					showTooltips: $el.data('tooltips')
				});

			}

			// Legend
			if ( $el.data('legend') ) {
				$el.append( '<div class="legend">'+chart.generateLegend()+'</div>' );
			}

			// Scroll effects
			if ( $body.hasClass('tb-scroll-effects') && $body.hasClass('desktop') ) {
				$el.find('.chart-wrap').css('opacity', '0').viewportChecker({
					classToAdd: 'visible',
					offset: 0,
					callbackFunction: function($elem, action){
						$elem.css('opacity', '1');
						chart.render();
					}
				});
			}
		});

		// Bar/Line charts
		$('.tb-chart.bar, .tb-chart.line').each(function(){

			var $el = $(this),
				type = 'line',
				context = $el.find('canvas').get(0).getContext('2d'),
				data = [],
				datasets = [],
				chart;

			if ( $el.hasClass('bar') ) {
				type = 'bar';
			}

			$el.find('.data').each(function(){

				var $data = $(this);

				datasets.push({
					label: $data.data('label'),
					fillColor: $data.data('fill'),
					strokeColor: $data.data('stroke'),
					pointColor: $data.data('point'),
					data: $data.data('values').split(',')
				});

			});

			data = {
				labels: $el.data('labels').split(','),
				datasets: datasets
			};

			if ( type == 'bar' ) {

				chart = new Chart(context).Bar(data, {
					barShowStroke: false,
					scaleBeginAtZero: $el.data('start'),
					showTooltips: $el.data('tooltips')
				});

			} else {

				chart = new Chart(context).Line(data, {
					bezierCurve: $el.data('curve'),
					datasetFill: $el.data('fill'),
					pointDot: $el.data('dot'),
					scaleBeginAtZero: $el.data('start'),
					showTooltips: $el.data('tooltips')
				});

			}

			// Legend
			if ( $el.data('legend') ) {
				$el.append( '<div class="legend">'+chart.generateLegend()+'</div>' );
			}

			// Scroll effects
			if ( $body.hasClass('tb-scroll-effects') && $body.hasClass('desktop') ) {
				$el.find('.chart-wrap').css('opacity', '0').viewportChecker({
					classToAdd: 'visible',
					offset: 0,
					callbackFunction: function($elem, action){
						$elem.css('opacity', '1');
						chart.render();
					}
				});
			}

		});
	}


	// ---------------------------------------------------------
	// Scroll effects (separate)
	// ---------------------------------------------------------

	// Milestone standard
	$('.desktop.tb-scroll-effects .tb-milestone .milestone').each(function() {

		var $el = $(this),
			num = parseInt($el.data('num'));

		$el.viewportChecker({
			classToAdd: 'visible',
			offset: 0,
			callbackFunction: function($elem, action){
				$elem.find('.num').countTo({
					from: 0,
					to: num,
					speed: 900,
					refreshInterval: 30
				});
			}
		});

	});

	// Milestone percent
	if( $.fn.easyPieChart != 'undefined' ) {
		$('.desktop.tb-scroll-effects .tb-milestone-percent').each(function(){

			var $chart = $(this).find('.chart');

			$chart.css('opacity', '0').viewportChecker({
				classToAdd: 'visible',
				offset: 0,
				callbackFunction: function($elem, action){
					$elem.easyPieChart({
						lineWidth: 10,
						size: 140,
						animate: 1000,
						barColor: $chart.data('color'),
						trackColor: $chart.data('track-color'),
						scaleColor: false,
						lineCap: 'square',
						easing: 'easeOutBounce',
						onStart: function() {
							$chart.css('opacity', '1');
						}
					});
				}
			});

		});
	}


	// Milestone standard
	$('.desktop.tb-scroll-effects .tb-progress .progress-bar').each(function(){

		$(this).viewportChecker({
			classToAdd: 'visible',
			offset: 0,
			callbackFunction: function($elem, action){
				$elem.animate({width:$elem.data('percent')+'%'}, 400);
			}
		});

	});

});

// ---------------------------------------------------------
// Allow adding a class when item comes into viewport,
// and performing a callback.
// ---------------------------------------------------------

/*
    Version 1.4.2
    The MIT License (MIT)

    Copyright (c) 2014 Dirk Groenen

    Permission is hereby granted, free of charge, to any person obtaining a copy of
    this software and associated documentation files (the "Software"), to deal in
    the Software without restriction, including without limitation the rights to
    use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
    the Software, and to permit persons to whom the Software is furnished to do so,
    subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all
    copies or substantial portions of the Software.
*/

(function($){
    $.fn.viewportChecker = function(useroptions){
        // Define options and extend with user
        var options = {
            classToAdd: 'visible',
            offset: 100,
            repeat: false,
            callbackFunction: function(elem, action){}
        };
        $.extend(options, useroptions);

        // Cache the given element and height of the browser
        var $elem = this,
            windowHeight = $(window).height(),
            scrollElem = ((navigator.userAgent.toLowerCase().indexOf('webkit') != -1) ? 'body' : 'html');

        this.checkElements = function(){

            // Set some vars to check with
            var viewportTop = $(scrollElem).scrollTop(),
                viewportBottom = (viewportTop + windowHeight);

            $elem.each(function(){
                var $obj = $(this);
                // If class already exists; quit
                if ($obj.hasClass(options.classToAdd) && !options.repeat){
                    return;
                }

                // define the top position of the element and include the offset which makes is appear earlier or later
                var elemTop = Math.round( $obj.offset().top ) + options.offset,
                    elemBottom = elemTop + ($obj.height());

                // Add class if in viewport
                if ((elemTop < viewportBottom) && (elemBottom > viewportTop)){
                    $obj.addClass(options.classToAdd);

                    // Do the callback function. Callback wil send the jQuery object as parameter
                    options.callbackFunction($obj, "add");

                // Remove class if not in viewport and repeat is true
                } else if ($obj.hasClass(options.classToAdd) && (options.repeat)){
                    $obj.removeClass(options.classToAdd);

                    // Do the callback function.
                    options.callbackFunction($obj, "remove");
                }
            });

        };

        // Run checkelements on load and scroll
        $(window).bind("load scroll touchmove", this.checkElements);

        // On resize change the height var
        $(window).resize(function(e){
            windowHeight = e.currentTarget.innerHeight;
        });

        return this;
    };
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

			var $el = $(this),
				$target,
				currentViewport,
				timeout = false;

			if ( $el.data('toggle') ) {
				$target = $($el.data('toggle'));
			} else {
				$target = $($el.attr('href'));
			}

			// Toggle on click
			$el.click(function(){
				if ( $target.hasClass(settings.openClass) ) {
					$target.slideUp().removeClass(settings.openClass).addClass(settings.closedClass);
				} else {
					$target.slideDown().removeClass(settings.closedClass).addClass(settings.openClass);
				}
				return false;
			});

			// Window re-sizing  - For those people screwing with the
			// browser window and are not actually on a mobile device.
		    $(window).resize( function() {

				if ( false !== timeout ) {
					clearTimeout( timeout );
				}

				timeout = setTimeout( function() {
					currentViewport = $(window).width();
					if (  currentViewport > settings.viewport ) {

						// Add class "expanded" so we can keep track of
						// whether this re-sizing is occuring on a mobile
						// device or not. If we're on mobile, the "forced_open"
						// class should never get added.
						$target.show().removeClass(settings.openClass+' '+settings.closedClass).addClass('expanded');

					} else {

						// Make sure this wasn't triggered by re-sizing on mobile
						if ( $target.hasClass('expanded') ) {
							$target.hide().removeClass('expanded');
						}
					}

				}, 100 );
			});
        });
    };
})(jQuery);