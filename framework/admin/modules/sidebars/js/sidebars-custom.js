jQuery(document).ready(function($) {
	
	/*-----------------------------------------------------------------------------------*/
	/* Static Methods
	/*-----------------------------------------------------------------------------------*/
	
	var sidebar_blvd = {
		
		// Delete Sidebar
    	delete_sidebar : function( ids, action )
    	{
    		var nonce  = $('#manage_current_sidebars').find('input[name="_wpnonce"]').val();
			tbc_confirm( themeblvd.delete_sidebar, {'confirm':true}, function(r)
			{
		    	if(r)
		        {
		        	$.ajax({
						type: "POST",
						url: ajaxurl,
						data:
						{
							action: 'sidebar_blvd_delete_sidebar',
							security: nonce,
							data: ids
						},
						success: function(response)
						{	
							// Prepare response
							response = response.split('[(=>)]');
							
							// Scroll to top of page
							$('body').animate( { scrollTop: 0 }, 100, function(){						
								
								// Insert update message, fade it in, and then remove it 
								// after a few seconds.
								$('#sidebar_blvd #manage_sidebars').prepend(response[0]);
								$('#sidebar_blvd #manage_sidebars .ajax-update').fadeIn(500, function(){
									setTimeout( function(){
										$('#sidebar_blvd #manage_sidebars .ajax-update').fadeOut(500, function(){
											$('#sidebar_blvd #manage_sidebars .ajax-update').remove();
										});
							      	}, 1500);
								
								});
								
								// Update table
								$('#sidebar_blvd #manage_sidebars .ajax-mitt').hide().html(response[1]).fadeIn('fast');
							});
						}
					});
		        }
		    });
    	}

	};
	
	/*-----------------------------------------------------------------------------------*/
	/* General setup
	/*-----------------------------------------------------------------------------------*/
	
	// Items from themeblvd namespace
	$('#sidebar_blvd .accordion').themeblvd('accordion');
	
	// Hide secret tab when page loads
	$('#sidebar_blvd .nav-tab-wrapper a.nav-edit-sidebar').hide();
	
	// If the active tab is on edit layout page, we'll 
	// need to override the default functionality of 
	// the Options Framework JS, because we don't want 
	// to show a blank page.
	if (typeof(localStorage) != 'undefined' )
	{
		if( localStorage.getItem('activetab') == '#edit_sidebar')
		{
			$('#sidebar_blvd .group').hide();
			$('#sidebar_blvd .group:first').fadeIn();
			$('#sidebar_blvd .nav-tab-wrapper a:first').addClass('nav-tab-active');
		}
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* Manage Widget Areas Page
	/*-----------------------------------------------------------------------------------*/
	
	// Edit slider (via Edit Link on manage page)
	$('#sidebar_blvd #manage_sidebars .edit-tb_sidebar').live( 'click', function(){
		var name = $(this).closest('tr').find('.post-title .title-link').text(),
			id = $(this).attr('href'), 
			id = id.replace('#', '');
		
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data:
			{
				action: 'sidebar_blvd_edit_sidebar',
				data: id
			},
			success: function(response)
			{	
				// Get the ID from the beginning
				var page = response.split('[(=>)]');
				
				// Prepare the edit tab
				$('#sidebar_blvd .nav-tab-wrapper a.nav-edit-sidebar').text(themeblvd.edit_sidebar+': '+name).addClass('edit-'+page[0]);
				$('#sidebar_blvd #edit_sidebar .ajax-mitt').html(page[1]);
				
				// Setup accordion
				$('#sidebar_blvd #edit_sidebar .accordion').themeblvd('accordion');
				
				// Setup options
				$('#sidebar_blvd #edit_sidebar').themeblvd('options', 'setup');
				
				// Take us to the tab
				$('#sidebar_blvd .nav-tab-wrapper a').removeClass('nav-tab-active');
				$('#sidebar_blvd .nav-tab-wrapper a.nav-edit-sidebar').show().addClass('nav-tab-active');
				$('#sidebar_blvd .group').hide();
				$('#sidebar_blvd .group:last').fadeIn();
			}
		});
		return false;
	});
	
	// Delete sidebar (via Delete Link on manage page)
	$('#sidebar_blvd .row-actions .trash a').live( 'click', function(){
		var href = $(this).attr('href'), id = href.replace('#', ''), ids = 'posts%5B%5D='+id;
		sidebar_blvd.delete_sidebar( ids, 'click' );
		return false;
	});
	
	// Delete sidebars via bulk action
	$('#manage_current_sidebars').live( 'submit', function(){		
		var value = $(this).find('select[name="action"]').val(), ids = $(this).serialize();
		if(value == 'trash')
		{
			sidebar_blvd.delete_sidebar( ids, 'submit' );
		}
		return false;
	});
	
	/*-----------------------------------------------------------------------------------*/
	/* Add New Widget Area Page
	/*-----------------------------------------------------------------------------------*/
	
	// Add new layout
	$('#optionsframework #add_new_sidebar').submit(function(){		
		var el = $(this),
			data = el.serialize(),
			load = el.find('.ajax-loading'),
			name = el.find('input[name="options[sidebar_name]"]').val(),
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
				action: 'sidebar_blvd_add_sidebar',
				security: nonce,
				data: data
			},
			beforeSend: function()
			{
				load.fadeIn('fast');
			},
			success: function(response)
			{	
				// Update management table
				$('#sidebar_blvd #manage_sidebars .ajax-mitt').html(response);
				
				// Scroll to top of page
				$('body').animate( { scrollTop: 0 }, 100, function(){						
					// Take us back to the management tab
					$('#sidebar_blvd .nav-tab-wrapper a').removeClass('nav-tab-active');
					$('#sidebar_blvd .nav-tab-wrapper a:first').addClass('nav-tab-active');
					$('#sidebar_blvd .group').hide();
					$('#sidebar_blvd .group:first').fadeIn();
					tbc_alert.init(themeblvd.sidebar_created, 'success');
				});
				
				// Clear form
				$('#sidebar_blvd #add_new_sidebar #sidebar_name').val('');
				$('#sidebar_blvd #add_new_sidebar input').removeAttr('checked');
								
				// Hide loader no matter what.												
				load.hide();
			}
		});
		return false;
	});
	
	/*-----------------------------------------------------------------------------------*/
	/* Edit Widget Area Page
	/*-----------------------------------------------------------------------------------*/
	
	// Save Widget Area
	$('#optionsframework #edit_current_sidebar').live('submit', function(){
		var el = $(this),
			data = el.serialize(),
			load = el.find('.ajax-loading'),
			nonce = el.find('input[name="_wpnonce"]').val();

		$.ajax({
			type: "POST",
			url: ajaxurl,
			data:
			{
				action: 'sidebar_blvd_save_sidebar',
				security: nonce,
				data: data
			},
			beforeSend: function()
			{
				load.fadeIn('fast');
			},
			success: function(response)
			{	
			
				response = response.split('[(=>)]');
				
				// Scroll to top of page
				$('body').animate( { scrollTop: 0 }, 100, function(){						
					// Insert update message, fade it in, and then remove it 
					// after a few seconds.
					$('#sidebar_blvd #edit_sidebar').prepend(response[0]);
					$('#sidebar_blvd #edit_sidebar .ajax-update').fadeIn(500, function(){
						setTimeout( function(){
							$('#sidebar_blvd #edit_sidebar .ajax-update').fadeOut(500, function(){
								$('#sidebar_blvd #edit_sidebar .ajax-update').remove();
							});
				      	}, 1500);
					
					});
				});
			
				// Update management table in background
				$('#sidebar_blvd #manage_sidebars .ajax-mitt').html(response[1]);
				
				load.fadeOut('fast');
			}
		});
		return false;
	});
	
});