/**
 * All scripts for metaboxes.
 */

jQuery(document).ready(function($) {

	/*-----------------------------------------------------------------------------------*/
	/* Hi-jacked Page Attributes meta box
	/*-----------------------------------------------------------------------------------*/

	// Show the proper option on page load
	var page_atts = $('#themeblvd_pageparentdiv'),
		template = page_atts.find('select[name="page_template"]').val();

	if( template == 'template_builder.php' || template == 'template_blank.php' ) {
		page_atts.find('select[name="_tb_sidebar_layout"]').hide().prev('p').hide();
	} else {
		page_atts.find('p.tb_custom_layout').hide().prev('p').hide();
	}

	// Show the proper option when user changes <select>
	page_atts.find('select[name="page_template"]').change(function(){
		var template = $(this).val();
		if( template == 'template_builder.php' || template == 'template_blank.php' ) {
			page_atts.find('select[name="_tb_sidebar_layout"]').hide().prev('p').hide();
			page_atts.find('p.tb_custom_layout').show().prev('p').show();
		} else {
			page_atts.find('p.tb_custom_layout').hide().prev('p').hide();
			page_atts.find('select[name="_tb_sidebar_layout"]').show().prev('p').show();
		}
	});

	/*-----------------------------------------------------------------------------------*/
	/* Apply framework scripts to our meta boxes
	/*-----------------------------------------------------------------------------------*/

	$('.tb-meta-box').themeblvd('init');
	$('.tb-meta-box').themeblvd('options', 'bind');
	$('.tb-meta-box').themeblvd('options', 'setup');
	$('.tb-meta-box').themeblvd('options', 'media-uploader');
	$('.tb-meta-box').themeblvd('options', 'editor');
	$('.tb-meta-box').themeblvd('options', 'code-editor');
	$('.tb-meta-box').themeblvd('options', 'column-widths');
	$('.tb-meta-box').themeblvd('options', 'sortable');

});