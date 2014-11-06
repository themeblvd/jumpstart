jQuery(document).ready(function($) {

	$('.tb-field-link-mega-hide-headers').each(function(){

		var $el = $(this),
			$item = $el.closest('.menu-item');

		if ( $item.find('.tb-field-link-mega input').is(':checked') ) {
			$el.find('label').show();
		} else {
			$el.find('label').hide();
		}

	});

	$('.tb-field-link-mega input').on('click', function(){

		var $item = $(this).closest('.menu-item');

		if ( $item.find('.tb-field-link-mega input').is(':checked') ) {
			$item.find('.tb-field-link-mega-hide-headers label').show();
		} else {
			$item.find('.tb-field-link-mega-hide-headers label').hide();
		}

	});
});