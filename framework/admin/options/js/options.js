/**
 * Prints out the inline javascript needed for the colorpicker and choosing
 * the tabs in the panel.
 */

jQuery(document).ready(function($) {
	
	// Fade out the save message
	$('.fade').delay(1000).fadeOut(1000);
	
	// Switches option sections
	$('.tb-options-js .group').hide();
	var activetab = '';
	
	if( typeof(localStorage) != 'undefined' )
		activetab = localStorage.getItem("activetab");
	
	if( activetab != '' && $(activetab).length )
		$(activetab).fadeIn();
	else
		$('.tb-options-js .group:first').fadeIn();

	$('.tb-options-js .group .collapsed').each(function(){
		$(this).find('input:checked').parent().parent().parent().nextAll().each( 
			function(){
				if($(this).hasClass('last'))
				{
					$(this).removeClass('hidden');
					return false;
				}
				$(this).filter('.hidden').removeClass('hidden');
			});
	});
	
	if( activetab != '' && $(activetab + '-tab').length )
		$(activetab + '-tab').addClass('nav-tab-active');
	else
		$('.nav-tab-wrapper a:first').addClass('nav-tab-active');

	$('.nav-tab-wrapper a').click(function(evt) {
		$('.nav-tab-wrapper a').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active').blur();
		var clicked_group = $(this).attr('href');
		if( typeof(localStorage) != 'undefined' )
			localStorage.setItem("activetab", $(this).attr('href'));
		$('.tb-options-js .group').hide();
		$(clicked_group).fadeIn();
		evt.preventDefault();
	});
           					
	$('.tb-options-js .group .collapsed input:checkbox').click(unhideHidden);
				
	function unhideHidden(){
		if($(this).attr('checked'))
		{
			$(this).parent().parent().parent().nextAll().removeClass('hidden');
		}
		else
		{
			$(this).parent().parent().parent().nextAll().each( 
			function(){
				if($(this).filter('.last').length)
				{
					$(this).addClass('hidden');
					return false;		
				}
				$(this).addClass('hidden');
			});					
		}
	}
	
	// ThemeBlvd namespace
	$('.tb-options-js').themeblvd('init');
	$('.tb-options-js').themeblvd('options', 'bind');
	$('.tb-options-js').themeblvd('options', 'setup');
	$('.tb-options-js').themeblvd('options', 'media-uploader');		
});	