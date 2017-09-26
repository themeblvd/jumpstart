/**
 * Theme Base admin page
 */
jQuery(document).ready(function($) {

	$('#themeblvd-base-admin .select-base').on('click', function(){

		var $el = $(this);

		tbc_confirm( $el.data('confirm'), {'confirm':true}, function(r) {
			if(r) {
				window.location.href = $el.attr('href');
			}
		});

		return false;
	});

});
