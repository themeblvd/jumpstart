/*-----------------------------------------------------------------------------------*/
/* WooFramework Media Library-driven AJAX File Uploader Module
/* JavaScript Functions (2010-11-05)
/*
/* The code below is designed to work as a part of the WooFramework Media Library-driven
/* AJAX File Uploader Module. It is included only on screens where this module is used.
/*
/* Used with (very) slight modifications for Options Framework.
/*-----------------------------------------------------------------------------------*/

(function ($) {

  optionsframeworkMLU = {
  
/*-----------------------------------------------------------------------------------*/
/* Remove file when the "remove" button is clicked.
/*-----------------------------------------------------------------------------------*/
  
    removeFile: function () {
     
     $('.mlu_remove').live('click', function(event) { 
        var el = $(this);
        el.hide();
        el.closest('.section-upload').find('.upload').attr('value', ''); // Mod'd to find ALL .upload (by ThemeBlvd)
        el.closest('.section-upload').find('.screenshot').slideUp();
        el.closest('.section-upload').find('.screenshot').siblings('.of-background-properties').hide(); //remove background properties
        return false;
      });
      
      // Hide the delete button on the first row 
      $('a.delete-inline', "#option-1").hide();
      
    }, // End removeFile
    
/*-----------------------------------------------------------------------------------*/
/* Replace the default file upload field with a customised version.
/*-----------------------------------------------------------------------------------*/

    recreateFileField: function () {
    
      $('input.file').each(function(){
        var uploadbutton = '<input class="upload_file_button" type="button" value="Upload" />';
        $(this).wrap('<div class="file_wrap" />');
        $(this).addClass('file').css('opacity', 0); //set to invisible
        $(this).parent().append($('<div class="fake_file" />').append($('<input type="text" class="upload" />').attr('id',$(this).attr('id')+'_file')).val( $(this).val() ).append(uploadbutton));
 
        $(this).bind('change', function() {
          $('#'+$(this).attr('id')+'_file').val($(this).val());
        });
        $(this).bind('mouseout', function() {
          $('#'+$(this).attr('id')+'_file').val($(this).val());
        });
      });
      
    }, // End recreateFileField

/*-----------------------------------------------------------------------------------*/
/* Use a custom function when working with the Media Uploads popup.
/* Requires jQuery, Media Upload and Thickbox JavaScripts.
/*-----------------------------------------------------------------------------------*/

	mediaUpload: function () {
	
	jQuery.noConflict();
	
	$( 'input.upload_button' ).removeAttr('style');
	
	var formfield,
		formID,
		btnContent = true,
		tbframe_interval;
	// On Click
	$('input.upload_button').live("click", function () {
        formfield = $(this).closest('.section-upload').find('input.image-url').attr('id'); // Mod'd to allow more variations of HTML markup (by ThemeBlvd)
        formID = $(this).attr('rel'),
        upload_type = $(this).attr('data-upload-type'); // Added by ThemeBlvd
        
		// Change "insert into post" to "Use this Button" // ... Altered for different upload types (Mod by ThemeBlvd)
		if( upload_type == 'slider' )
		{
			tbframe_interval = setInterval(function()
			{
				jQuery('#TB_iframeContent').contents().find('.savesend .button').val('Insert into Slider');
				jQuery('#TB_iframeContent').contents().find('.slidetoggle tr.image_alt, .slidetoggle tr.post_excerpt, .slidetoggle tr.post_content, .slidetoggle tr.url, .slidetoggle tr.align, .slidetoggle tr.image-size').hide();
			}, 2000);
        }
        else if( upload_type == 'logo' || upload_type == 'logo_2x' )
        {
        	tbframe_interval = setInterval(function() {jQuery('#TB_iframeContent').contents().find('.savesend .button').val('Use as Logo');}, 2000);
        }
        else
        {
        	tbframe_interval = setInterval(function() {jQuery('#TB_iframeContent').contents().find('.savesend .button').val('Use This Image');}, 2000);
        }
        
        // Display a custom title for each Thickbox popup.
        var woo_title = '';
        
		if ( $(this).parents('.section').find('.heading') ) { woo_title = $(this).parents('.section').find('.heading').text(); } // End IF Statement
        
		tb_show( woo_title, 'media-upload.php?post_id='+formID+'&themeblvd_upload_type='+upload_type+'&TB_iframe=1' ); // Modded to pass in upload_type by ThemeBlvd
		return false;
	});
            
	window.original_send_to_editor = window.send_to_editor;
	window.send_to_editor = function(html) {

		if (formfield) {

			// Add media item's ID in the mix if this is for a slider (Mod by ThemeBlvd)
			if( upload_type == 'slider' )
			{
				var id = $(html).html(html).find('img').attr('class'), id = id.split('wp-image-'), id = id[1];
				$('#' + formfield + '_id').val(id);	
			}
			
			//clear interval for "Use this Button" so button text resets
			clearInterval(tbframe_interval);
        	
			// itemurl = $(html).attr('href'); // Use the URL to the main image.
          
			if ( $(html).html(html).find('img').length > 0 ) {
				
				if( upload_type == 'logo' )
				{
					var itemwidth = $(html).html(html).find('img').attr('width');	
				}
				var itemurl = $(html).html(html).find('img').attr('src'); // Use the URL to the size selected.
				
			} else {
			
				// It's not an image. Get the URL to the file instead.
				
				var htmlBits = html.split("'"); // jQuery seems to strip out XHTML when assigning the string to an object. Use alternate method.
				itemurl = htmlBits[1]; // Use the URL to the file.
				
				var itemtitle = htmlBits[2];
				
				itemtitle = itemtitle.replace( '>', '' );
				itemtitle = itemtitle.replace( '</a>', '' );
			
			} // End IF Statement
                   
			var image = /(^.*\.jpg|jpeg|png|gif|ico*)/gi;
			var document = /(^.*\.pdf|doc|docx|ppt|pptx|odt*)/gi;
			var audio = /(^.*\.mp3|m4a|ogg|wav*)/gi;
			var video = /(^.*\.mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2*)/gi;
			
			if (itemurl.match(image)) {
			
				btnContent = '<img src="'+itemurl+'" alt="" /><a href="#" class="mlu_remove button">Remove Image</a>';
			
			} else {
				
				// No output preview if it's not an image.
				// btnContent = '';
				// Standard generic output if it's not an image.
			
				html = '<a href="'+itemurl+'" target="_blank" rel="external">View File</a>';
				btnContent = '<div class="no_image"><span class="file_link">'+html+'</span><a href="#" class="mlu_remove button">Remove</a></div>';
			}
			
			$('#' + formfield).val(itemurl);
			if( upload_type == 'logo' )
			{
				// Insert width if this is the standard size logo image.
				$('#' + formfield).next().val(itemwidth);
			}
			// $('#' + formfield).next().next('div').slideDown().html(btnContent);
			$('#' + formfield).siblings('.screenshot').slideDown().html(btnContent);
			$('#' + formfield).siblings('.of-background-properties').show(); //show background properties
			tb_remove();
          
		} else {
		  window.original_send_to_editor(html);
		}
        
        // Clear the formfield value so the other media library popups can work as they are meant to. - 2010-11-11.
        formfield = '';
      }
      
    } // End mediaUpload
   
  }; // End optionsframeworkMLU Object // Don't remove this, or the sky will fall on your head.

/*-----------------------------------------------------------------------------------*/
/* Execute the above methods in the optionsframeworkMLU object.
/*-----------------------------------------------------------------------------------*/
  
	$(document).ready(function () {

		optionsframeworkMLU.removeFile();
		optionsframeworkMLU.recreateFileField();
		optionsframeworkMLU.mediaUpload();
	
	});
  
})(jQuery);