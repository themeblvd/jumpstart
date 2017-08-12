/**
 * All scripts for metaboxes.
 */
jQuery(document).ready(function($) {

	/**
	 * Sidebar layout selection added to Page Attributes.
	 *
	 * When the "Custom Layout" or "Blank Page" page template
	 * are selected, we want to hide the selection for a
	 * sidebar layout, to make it obvious these template do
	 * not use a sidebar layout.
	 */
	$('#page_template').on( 'change', function() {

		var value = $(this).val();

		console.log(value);

		if ( value == 'template_builder.php' || value == 'template_blank.php' ) {
			$('#tb-sidebar-layout').hide();
		} else {
			$('#tb-sidebar-layout').show();
		}

	});

	/**
	 * Apply framework scripts to our meta boxes.
	 */
	$('.tb-meta-box').themeblvd('init');
	$('.tb-meta-box').themeblvd('options', 'bind');
	$('.tb-meta-box').themeblvd('options', 'setup');
	$('.tb-meta-box').themeblvd('options', 'media-uploader');
	$('.tb-meta-box').themeblvd('options', 'editor');
	$('.tb-meta-box').themeblvd('options', 'code-editor');
	$('.tb-meta-box').themeblvd('options', 'column-widths');
	$('.tb-meta-box').themeblvd('options', 'sortable');

});
