jQuery(document).ready(function($){

	// ---------------------------------------------------------
	// Logo
	// ---------------------------------------------------------

	// Initial page load
	var logo_type = $('#customize-control-logo_type').find('select').val();

	if( logo_type == 'custom' )
	{
		$('#customize-control-logo_image').hide();
	}
	else if( logo_type == 'image' )
	{
		$('#customize-control-logo_custom').hide();
		$('#customize-control-logo_custom_tagline').hide();
	}
	else
	{
		$('#customize-control-logo_custom').hide();
		$('#customize-control-logo_custom_tagline').hide();
		$('#customize-control-logo_image').hide();
	}

	$('#customize-control-logo_type').find('select').change(function(){
		logo_type = $(this).val();
		if( logo_type == 'custom' )
		{
			$('#customize-control-logo_custom').show();
			$('#customize-control-logo_custom_tagline').show();
			$('#customize-control-logo_image').hide();
		}
		else if( logo_type == 'image' )
		{
			$('#customize-control-logo_custom').hide();
			$('#customize-control-logo_custom_tagline').hide();
			$('#customize-control-logo_image').show();
		}
		else
		{
			$('#customize-control-logo_custom').hide();
			$('#customize-control-logo_custom_tagline').hide();
			$('#customize-control-logo_image').hide();
		}
	});

	// ---------------------------------------------------------
	// Font Selections
	// ---------------------------------------------------------

	// Initial page load.
	$('.customize-control-font_face').each(function(){

		var el = $(this),
			font_type = el.find('select').val();

		// If google isn't selected...
		if( font_type != 'google' ){
			// Then, hide the the google font name input.
			el.next().hide();
		}

	});

	// On change of font face select box.
	$('.customize-control-font_face select').change(function(){

		var el = $(this),
			parent = el.closest('.customize-control-font_face'),
			font_type = el.val();

		// Determine if google should be now shown or hidden.
		if( font_type == 'google' ){
			// Show google input
			parent.next().show();
		}
		else {
			// Hide google input
			parent.next().hide();
		}
	});

	// ---------------------------------------------------------
	// Homepage
	// ---------------------------------------------------------

	// Initial page load.
	var show_on_front = $('#customize-control-show_on_front input[name=_customize-radio-show_on_front]:checked').val(),
		homepage_content = $('#customize-control-homepage_content input[name=_customize-radio-homepage_content]:checked').val();

	if(show_on_front == 'page')
	{
		$('#customize-control-homepage_content').hide();
		$('#customize-control-homepage_custom_layout').hide();
	}
	else if(show_on_front == 'posts')
	{
		if(homepage_content == 'posts')
		{
			$('#customize-control-homepage_custom_layout').hide();
		}
	}

	// On change of font face select box.
	$('#customize-control-show_on_front input[name=_customize-radio-show_on_front]').change(function(){

		show_on_front = $(this).val();

		if(show_on_front == 'page')
		{
			$('#customize-control-homepage_content').hide();
			$('#customize-control-homepage_custom_layout').hide();
		}
		else if(show_on_front == 'posts')
		{

			// Show content type no matter what
			$('#customize-control-homepage_content').show();

			// Only show custom layouts if that's the content type for the homepage
			if(homepage_content == 'posts')
			{
				$('#customize-control-homepage_custom_layout').hide();
			}
			else if(homepage_content == 'custom_layout')
			{
				$('#customize-control-homepage_custom_layout').show();
			}
		}
	});

	$('#customize-control-homepage_content input[name=_customize-radio-homepage_content]').change(function(){

		homepage_content = $(this).val();

		// Toggle custom layout select box
		if(homepage_content == 'posts')
		{
			$('#customize-control-homepage_custom_layout').hide();
		}
		else if(homepage_content == 'custom_layout')
		{
			$('#customize-control-homepage_custom_layout').show();
		}

	});
});
