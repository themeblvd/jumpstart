/**
 * Prints out the inline javascript needed for the outer
 * functionality of top-level options pages.
 */
jQuery(document).ready(function($) {

	// Fade out the save message
	$('.fade').delay(2000).fadeOut(1000);

	// Switches option sections
	$('.tb-options-js .group').hide();

	var activetab = '';

	if ( typeof(localStorage) != 'undefined' ) {
		activetab = localStorage.getItem("tb-activetab");
	}

	if ( activetab != '' && $(activetab).length ) {
		$(activetab).fadeIn();
	} else {
		$('.tb-options-js .group:first').fadeIn();
	}

	$('.tb-options-js .group .collapsed').each(function(){
		$(this).find('input:checked').parent().parent().parent().nextAll().each( function(){
			if ( $(this).hasClass('last') ) {
				$(this).removeClass('hidden');
				return false;
			}
			$(this).filter('.hidden').removeClass('hidden');
		});
	});

	if ( activetab != '' && $(activetab + '-tab').length ) {
		$(activetab + '-tab').addClass('nav-tab-active');
	} else {
		$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
	}

	$('.nav-tab-wrapper a').click(function(evt) {

		var $el = $(this),
			$clicked_group = $($el.attr('href'));

		$('.nav-tab-wrapper a').removeClass('nav-tab-active');

		$el.addClass('nav-tab-active').blur();

		if ( typeof(localStorage) != 'undefined' ) {
			localStorage.setItem("tb-activetab", $el.attr('href'));
		}

		$('.tb-options-js .group').hide();

		$clicked_group.fadeIn();

		evt.preventDefault();

		// Refresh any code editors in this tab
		$clicked_group.find('.section-code').each(function(){

			var $editor = $(this).find('textarea').data('CodeMirrorInstance');

			if ( $editor ) {
				$editor.refresh();
			}
		});

	});

	$('.postbox > .section-toggle').on('click', function(){

		$toggle = $(this),
		$postbox = $toggle.closest('.postbox');

		if ( $postbox.hasClass('closed') ) {

			// Show content
			$postbox.removeClass('closed').find('.inner-section-content').show();

			// Store user data
			if ( typeof(localStorage) != 'undefined' ) {
				localStorage.setItem('tb-section-'+$postbox.attr('id'), true);
			}

			// Refresh any code editor options
			$postbox.find('.section-code').each(function(){

				var $editor = $(this).find('textarea').data('CodeMirrorInstance');

				if ( $editor ) {
					$editor.refresh();
				}
			});

		} else {

			// Hide content
			$postbox.addClass('closed').find('.inner-section-content').hide();

			// Store user data
			if ( typeof(localStorage) != 'undefined' ) {
				localStorage.removeItem('tb-section-'+$postbox.attr('id'));
			}

		}

		return false;
	});

	$('.postbox').each(function(){

		var $postbox = $(this);

		if ( typeof(localStorage) != 'undefined' && localStorage.getItem('tb-section-'+$postbox.attr('id')) ) {

			// Show content
			$postbox.removeClass('closed').find('.inner-section-content').show();

			// Refresh any code editor options
			$postbox.find('.section-code').each(function(){

				var $editor = $(this).find('textarea').data('CodeMirrorInstance');

				if ( $editor ) {
					$editor.refresh();
				}
			});
		}
	});

	$('.tb-options-js .group .collapsed input:checkbox').click(unhideHidden);

	function unhideHidden(){

		var el = $(this);

		if ( el.attr('checked') ) {
			el.parent().parent().parent().nextAll().removeClass('hidden');
		} else {
			el.parent().parent().parent().nextAll().each(function(){
				if ( el.filter('.last').length ) {
					el.addClass('hidden');
					return false;
				}
				el.addClass('hidden');
			});
		}
	}

	$('.tb-presets a').on('click', function(){

		var $a = $(this);
			$form = $a.closest('form');

		tbc_confirm( themeblvd.preset, {'confirm':true}, function(r) {
	    	if (r) {
				$form.append('<input name="_tb_set_preset" value="' + $a.data('set') + '" type="hidden" />').submit();
	        }
	    });

		return false;
	});

	// ThemeBlvd namespace
	$('.tb-options-js').themeblvd('init');
	$('.tb-options-js').themeblvd('options', 'bind');
	$('.tb-options-js').themeblvd('options', 'setup');
	$('.tb-options-js').themeblvd('options', 'media-uploader');
	$('.tb-options-js').themeblvd('options', 'editor');
	$('.tb-options-js').themeblvd('options', 'code-editor');
	$('.tb-options-js').themeblvd('options', 'column-widths');
	$('.tb-options-js').themeblvd('options', 'sortable');

});
