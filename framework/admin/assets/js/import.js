/**
 * Add our HTML markup to WordPress's Importer.
 */
jQuery(document).ready(function($) {

	var markup = '';

	// Intro title and text
	markup += '<h3>'+themeblvd_import.header+'</h3>';

	// Hidden field containing file name
	if ( themeblvd_import.file_name ) {
		markup += '<input type="hidden" value="'+themeblvd_import.file_name+'" name="themeblvd_file_name">';
	}

	// Select to import theme settings
	if ( themeblvd_import.theme_settings ) {
		markup += '<p>';
		markup += '<input type="checkbox" value="1" name="themeblvd_import_theme_settings" checked>';
		markup += '<label for="themeblvd_import_theme_settings">'+themeblvd_import.theme_settings+'</label>';
		markup += '</p>';
	}

	// Select to import site settings
	if ( themeblvd_import.site_settings ) {
		markup += '<p>';
		markup += '<input type="checkbox" value="1" name="themeblvd_import_site_settings" checked>';
		markup += '<label for="themeblvd_import_site_settings">'+themeblvd_import.site_settings+'</label>';
		markup += '</p>';
	}

	// Select to import site settings
	if ( themeblvd_import.site_widgets ) {
		markup += '<p>';
		markup += '<input type="checkbox" value="1" name="themeblvd_import_site_widgets" checked>';
		markup += '<label for="themeblvd_import_site_widgets">'+themeblvd_import.site_widgets+'</label>';
		markup += '</p>';
	}

	$('p.submit').before(markup);

	// Check import media by default
	$('#import-attachments').prop('checked', true);

});