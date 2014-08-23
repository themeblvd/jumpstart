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
	/* Options Framework imports for meta boxes
	/*-----------------------------------------------------------------------------------*/

	// Image Options
	$('.of-radio-img-img').click(function(){
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).addClass('of-radio-img-selected');
	});

	$('.of-radio-img-label').hide();
	$('.of-radio-img-img').show();
	$('.of-radio-img-radio').hide();

	/*-----------------------------------------------------------------------------------*/
	/* Edit Post Meta Box
	/*-----------------------------------------------------------------------------------*/

	// Setup featured image link
	var value, meta_box = $('.tb-meta-box');
	meta_box.find('.tb-thumb-link').hide();
	meta_box.find('.select-tb-thumb-link input:radio:checked').each(function() {
		value = $(this).val();
		meta_box.find('.tb-thumb-link-'+value).show();
		if( value != 'inactive' )
			meta_box.find('.tb-thumb-link-single').show();
	});
	meta_box.find('.select-tb-thumb-link input:radio').change(function(){
		value = $(this).val();
		meta_box.find('.tb-thumb-link').hide();
		meta_box.find('.tb-thumb-link-'+value).show();
		if( value != 'inactive' )
			meta_box.find('.tb-thumb-link-single').show();
	});
});