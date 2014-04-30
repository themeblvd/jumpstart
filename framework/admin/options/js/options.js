/**
 * Prints out the inline javascript needed for the colorpicker and choosing
 * the tabs in the panel.
 */
jQuery(document).ready(function($) {

	// Fade out the save message
	$('.fade').delay(2000).fadeOut(1000);

	// Switches option sections
	$('.tb-options-js .group').hide();

	var activetab = '';

	if( typeof(localStorage) != 'undefined' ) {
		activetab = localStorage.getItem("activetab");
	}

	if( activetab != '' && $(activetab).length ) {
		$(activetab).fadeIn();
	} else {
		$('.tb-options-js .group:first').fadeIn();
	}

	$('.tb-options-js .group .collapsed').each(function(){
		$(this).find('input:checked').parent().parent().parent().nextAll().each( function(){
			if( $(this).hasClass('last') ) {
				$(this).removeClass('hidden');
				return false;
			}
			$(this).filter('.hidden').removeClass('hidden');
		});
	});

	if( activetab != '' && $(activetab + '-tab').length ) {
		$(activetab + '-tab').addClass('nav-tab-active');
	} else {
		$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
	}

	$('.nav-tab-wrapper a').click(function(evt) {

		var el = $(this),
			clicked_group = $(el.attr('href'));

		$('.nav-tab-wrapper a').removeClass('nav-tab-active');

		el.addClass('nav-tab-active').blur();

		if( typeof(localStorage) != 'undefined' ) {
			localStorage.setItem("activetab", el.attr('href'));
		}

		$('.tb-options-js .group').hide();

		clicked_group.fadeIn();

		evt.preventDefault();

		// Refresh any code editors in this tab
		clicked_group.find('.section-code').each(function(){

			var code_option = $(this),
				editor = code_option.find('textarea').data('CodeMirrorInstance');

			if ( editor ) {
				editor.refresh();
			}
		});

	});

	$('.tb-options-js .group .collapsed input:checkbox').click(unhideHidden);

	function unhideHidden(){
		var el = $(this);

		if( el.attr('checked') ) {
			el.parent().parent().parent().nextAll().removeClass('hidden');
		} else {
			el.parent().parent().parent().nextAll().each(function(){
				if( el.filter('.last').length ) {
					el.addClass('hidden');
					return false;
				}
				el.addClass('hidden');
			});
		}
	}

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