/**
 * Prints out the inline javascript needed for managing layouts. 
 * This is an extension of what was already started in the
 * options-custom.js file.
 */

jQuery(document).ready(function($) {
	
	/*-----------------------------------------------------------------------------------*/
	/* Static Methods
	/*-----------------------------------------------------------------------------------*/
	
	var builder_blvd = {
		
		// Update Manage Layouts page's table
    	manager : function( table )
    	{
    		if(table)
			{
				// We already have the table, so just throw it in.
				$('#builder_blvd #manage_layouts .ajax-mitt').html(table);
			}
			else
			{
				// We don't have the table yet, so let's grab it.
				$.ajax({
					type: "POST",
					url: ajaxurl,
					data:
					{
						action: 'builder_blvd_update_table'
					},
					success: function(response)
					{	
						$('#builder_blvd #manage_layouts .ajax-mitt').html(response);
					}
				});
			}
    	},
    	
    	// Delete Layout
    	delete_layout : function( ids, action, location )
    	{
    		var nonce  = $('#manage_builder').find('input[name="_wpnonce"]').val();
			tbc_confirm( themeblvd.delete_layout, {'confirm':true}, function(r)
			{
		    	if(r)
		        {
		        	$.ajax({
						type: "POST",
						url: ajaxurl,
						data:
						{
							action: 'builder_blvd_delete_layout',
							security: nonce,
							data: ids
						},
						success: function(response)
						{	

							// Prepare response
							response = response.split('[(=>)]');
							
							// Insert update message, fade it in, and then remove it 
							// after a few seconds.
							$('#builder_blvd #manage_layout').prepend(response[1]);
							$('#builder_blvd #manage_layout .ajax-update').fadeIn(500, function(){
								setTimeout( function(){
									$('#builder_blvd #manage_layout .ajax-update').fadeOut(500, function(){
										$('#builder_blvd #manage_layout .ajax-update').remove();
									});
						      	}, 1500);
							
							});
							
							// Change number of layouts
							$('#builder_blvd .displaying-num').text(response[0]);
							
							// Update table
							if(action == 'submit')
							{
								$('#manage_builder').find('input[name="posts[]"]').each(function(){
									if( $(this).is(':checked') )
									{
										var id = $(this).val();
										if( $('#edit_layout-tab').hasClass(id+'-edit') )
										{
											$('#edit_layout-tab').hide();
										}
										$(this).closest('tr').remove();
									}
								});
							}
							else if(action == 'click')
							{
								var id = ids.replace('posts%5B%5D=', '');
								if( $('#edit_layout-tab').hasClass(id+'-edit') )
								{
									$('#edit_layout-tab').hide();
								}
								$('#row-'+id).remove();
							}
							
							// Uncheck all checkboxes
							$('#manage_builder option').removeAttr('checked'); 
							
							// Forward back to manage layouts page if 
							// we're deleting this layout from the Edit 
							// Layout page.
							if(location == 'edit_page')
							{
								$('#builder_blvd .group').hide();
								$('#builder_blvd .group:first').fadeIn();
								$('#builder_blvd .nav-tab-wrapper a:first').addClass('nav-tab-active');
							}
						}
					});
		        }
		    });
    	},
		
		// Manage add new layout form elements
		add_layout : function( object )
    	{
    		var value = object.val(), parent = object.closest('.controls');
		
			// Always remove the warning.
			$('#section-layout_sidebar .controls .warning').remove();
			
			// Finish it up depending on if the user selected to 
			// start from scratch or a sample layout.
			if(value != '0')
			{
				$('#section-layout_sidebar select').hide();
				$('#section-layout_sidebar .controls').prepend('<p class="warning">The starting sample layout you\'ve chosen already has a sidebar layout.</p>');
				parent.find('.sample-layouts div').hide();
				parent.find('#sample-'+value).show();
			}
			else
			{
				$('#section-layout_sidebar select').fadeIn('fast');
				parent.find('.sample-layouts div').hide();
			}
    	},
    	
    	// Enter into editing a layout
    	edit : function ( name, page )
    	{
    		// Get the ID from the beginning
			var page = page.split('[(=>)]');
			
			// Prepare the edit tab
			$('#builder_blvd .nav-tab-wrapper a.nav-edit-builder').text(themeblvd.edit_layout+': '+name).addClass(page[0]+'-edit');
			$('#builder_blvd #edit_layout .ajax-mitt').html(page[1]);
			
			// Setup hints
			$('.sortable:not(:has(div))').addClass('empty');
			$('.sortable:has(div)').removeClass('empty');
			
			// Setup sortables
			$('.sortable').sortable({
				handle: '.widget-name',
				connectWith: '.sortable'
			});
			
			// Sortable binded events
			$('.sortable').bind( 'sortreceive', function(event, ui) {
				$('.sortable:not(:has(div))').addClass('empty');
				$('.sortable:has(div)').removeClass('empty');
			});
			
			// Setup widgets
			$('#builder_blvd .widget').themeblvd('widgets');
			
			// Setup options
			$('#builder_blvd').themeblvd('options', 'setup');
			
			// Take us to the tab
			$('#builder_blvd .nav-tab-wrapper a').removeClass('nav-tab-active');
			$('#builder_blvd .nav-tab-wrapper a.nav-edit-builder').show().addClass('nav-tab-active');
			$('#builder_blvd .group').hide();
			$('#builder_blvd .group:last').fadeIn();
			
    	}
		
	};
	
	/*-----------------------------------------------------------------------------------*/
	/* General setup
	/*-----------------------------------------------------------------------------------*/
	
	// Hide secret tab when page loads
	$('#builder_blvd .nav-tab-wrapper a.nav-edit-builder').hide();
	
	// If the active tab is on edit layout page, we'll 
	// need to override the default functionality of 
	// the Options Framework JS, because we don't want 
	// to show a blank page.
	if (typeof(localStorage) != 'undefined' )
	{
		if( localStorage.getItem('activetab') == '#edit_layout')
		{
			$('#builder_blvd .group').hide();
			$('#builder_blvd .group:first').fadeIn();
			$('#builder_blvd .nav-tab-wrapper a:first').addClass('nav-tab-active');
		}
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* Manage Layouts Page
	/*-----------------------------------------------------------------------------------*/
	
	// Edit layout (via Edit Link on manage page)
	$('#builder_blvd #manage_layouts .edit-tb_layout').live( 'click', function(){
		var name = $(this).closest('tr').find('.post-title .title-link').text(),
			id = $(this).attr('href'), 
			id = id.replace('#', '');
		
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data:
			{
				action: 'builder_blvd_edit_layout',
				data: id
			},
			success: function(response)
			{	
				builder_blvd.edit( name, response );
			}
		});
		return false;
	});
	
	// Delete layout (via Delete Link on manage page)
	$('#builder_blvd .row-actions .trash a').live( 'click', function(){
		var href = $(this).attr('href'), id = href.replace('#', ''), ids = 'posts%5B%5D='+id;
		builder_blvd.delete_layout( ids, 'click' );
		return false;
	});
	
	// Delete layouts via bulk action
	$('#manage_builder').live( 'submit', function(){
		var value = $(this).find('select[name="action"]').val(), ids = $(this).serialize();
		if(value == 'trash')
		{
			builder_blvd.delete_layout( ids, 'submit' );
		}
		return false;
	});
	
	/*-----------------------------------------------------------------------------------*/
	/* Add New Layout Page
	/*-----------------------------------------------------------------------------------*/
	
	$('#layout_start').each( function(){
		builder_blvd.add_layout( $(this) );
	});
	
	$('#layout_start').change(function(){ 
		builder_blvd.add_layout( $(this) );
	});
	
	// Add new layout
	$('#optionsframework #add_new_builder').submit(function(){		
		var el = $(this),
			data = el.serialize(),
			load = el.find('.ajax-loading'),
			name = el.find('input[name="options[layout_name]"]').val(),
			nonce = el.find('input[name="_wpnonce"]').val();
		
		// Tell user they forgot a name
		if(!name)
		{
			tbc_confirm(themeblvd.no_name, {'textOk':'Ok'});
		    return false;
		}
			
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: 
			{
				action: 'builder_blvd_add_layout',
				security: nonce,
				data: data
			},
			beforeSend: function()
			{
				load.fadeIn('fast');
			},
			success: function(response)
			{	
				// Scroll to top of page
				$('body').animate( { scrollTop: 0 }, 100, function(){						
					// Everything is good to go. So, forward 
					// on to the edit layout page.					
					builder_blvd.edit( name, response );
					tbc_alert.init(themeblvd.layout_created, 'success');
					el.find('input[name="options[layout_name]"]').val('');
				});
				
				// Update builder management table in background
				builder_blvd.manager();

				// Hide loader no matter what.												
				load.hide();
			}
		});
		return false;
	});
	
	/*-----------------------------------------------------------------------------------*/
	/* Edit Layout Page
	/*-----------------------------------------------------------------------------------*/
	
	// Add new element
	$('#optionsframework #add_new_element').live( 'click', function(){
		var el = $(this),
			id,
			trim_front,
			trim_back,
			element_id,
			primary_query = false,
			overlay = el.parent().find('.ajax-overlay'),
			load = el.parent().find('.ajax-loading');
			values = el.parent().find('select').val(),
			values = values.split('=>'),
			type = values[0],
			query = values[1];
			
		// Make sure the user doesn't have more than one "primary" 
		// query element. This just means that they can't add 
		// two elements that both use WordPress's primary loop. 
		// Examples would be anything that's paginated. Most other 
		// elements that require posts to be pulled are done with 
		// get_posts() in order to have multiple on a single page. 
		// This can't be done, really, with anything paginated. 
		if(query == 'primary')
		{
			// Run a check for other primary query items.
			$('#builder_blvd #builder .element-query').each(function(){
				if( $(this).val() == 'primary' )
				{
					primary_query = true;
				}
			});
			
			// Check if primary_query was set to true
			if(primary_query)
			{
				// Say, what? We found a second primary? Halt everything!
				tbc_confirm(themeblvd.primary_query, {'textOk':'Ok'});
				return false;
			}
		}
		
		// User doesn't have more than one "primary" query item, 
		// so let's proceed with the ajax.
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data:
			{
				action: 'builder_blvd_add_element',
				data: type
			},
			beforeSend: function()
			{
				overlay.fadeIn('fast');
				load.fadeIn('fast');
			},
			success: function(response)
			{	
				trim_front = response.split('<div id="');
				trim_back = trim_front[1].split('" class="widget element-options"');
				element_id = trim_back[0];
				$('#builder_blvd #edit_layout #primary .sortable').append(response);
				$('#builder_blvd #edit_layout #primary .sortable').removeClass('empty');
				$('#'+element_id).themeblvd('widgets');
				$('#'+element_id).themeblvd('options', 'setup');
				$('#'+element_id).fadeIn();									
				load.fadeOut('fast');
				overlay.fadeOut('fast');
				
			}
		});
		return false;
	});
	
	// Save Layout
	$('#optionsframework #edit_builder').live('submit', function(){
		var el = $(this),
			data = el.serialize(),
			load = el.find('.publishing-action .ajax-loading'),
			nonce = el.find('input[name="_wpnonce"]').val();
			
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data:
			{
				action: 'builder_blvd_save_layout',
				security: nonce,
				data: data
			},
			beforeSend: function()
			{
				load.fadeIn('fast');
			},
			success: function(response)
			{	
				// Insert update message, fade it in, and then remove it 
				// after a few seconds.
				$('#builder_blvd #edit_layout').prepend(response);
				
				// Scroll to top of page
				$('body').animate( { scrollTop: 0 }, 50, function(){						
					// Fade in the update message
					$('#builder_blvd #edit_layout .ajax-update').fadeIn(500, function(){
						setTimeout( function(){
							$('#builder_blvd #edit_layout .ajax-update').fadeOut(500, function(){
								$('#builder_blvd #edit_layout .ajax-update').remove();
							});
				      	}, 1500);
					
					});
				});	
				load.fadeOut('fast');
			}
		});
		return false;
	});
	
	// Delete layout (via Delete Link on edit layout page)
	$('#builder_blvd #edit_layout .delete_layout').live( 'click', function(){
		var href = $(this).attr('href'), id = href.replace('#', ''), ids = 'posts%5B%5D='+id;
		builder_blvd.delete_layout( ids, 'click', 'edit_page' );
		return false;
	});
	
});