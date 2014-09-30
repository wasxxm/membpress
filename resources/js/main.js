jQuery(document).ready(function( $ ) {
	
    // remove the hand cursor over the separator in menu
	$('.wp-submenu li a .membpress-admin-menu-sep').parent('a').css('cursor', 'default');
	
	// options for the login welcome page select box
	$('.membpress_settings_welcome_login_type').change(function(e)
	{
		var membpress_settings_welcome_group = $(e.target).parents('.membpress_welcome_login_group');
		var membpress_settings_welcome_login_type = $(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_type:checked').val();
		
		if (membpress_settings_welcome_login_type == 'page')
		{
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_page').parent('p')).show();
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_post').parent('p')).hide();
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_url').parent('p')).hide();	
		}
		else if (membpress_settings_welcome_login_type == 'post')
		{
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_post').parent('p')).show();
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_page').parent('p')).hide();
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_url').parent('p')).hide();	
		}
	    else if (membpress_settings_welcome_login_type == 'url')
		{
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_url').parent('p')).show();
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_post').parent('p')).hide();
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_page').parent('p')).hide();	
		}
    });
	
	$('#membpress_settings_welcome_login_individual').change(function(e) {
        if ($(this).prop('checked'))
		{
			$('#membpress_welcome_login_individual').fadeIn(300);
			$('#mempress_welcome_login_group_all').fadeOut(0);
		}
		else
		{
			$('#membpress_welcome_login_individual').fadeOut(0);
			$('#mempress_welcome_login_group_all').fadeIn(300);	
		}
    });
	
	// make the update message fade away after x seconds
	window.setTimeout(function()
	{
	   //uncomment the line below to enable
	   // $('.membpress .updated').fadeOut(600);
	}, 5000);
	
	/*
	Handle the collapse/expand of the boxes
	*/
	
	function membpress_set_expand_cookie(postbox)
	{
	   $.cookie($(postbox).attr('id'), 1, { expires : 1000 });	
	}
	
    function membpress_set_collapse_cookie(postbox)
	{
	   $.cookie($(postbox).attr('id'), 0, { expires : 1000 });	
	}
	
	$('.membpress .hndle, .membpress .handlediv').click(function(e) {
		
		var postbox = $(this).parent('.postbox');
		
        $(postbox).toggleClass('closed');
		
		if ($(postbox).hasClass('closed'))
		{
			membpress_set_collapse_cookie(postbox);
		}
		else
		{
			membpress_set_expand_cookie(postbox);
		}
    });
	
	/*
	Expand All metaboxes membpress
	*/
	$('.membpress_settings_expand').click(function(e) {
        $('.postbox').each(function(index, element) {
            $(element).removeClass('closed');
			membpress_set_expand_cookie(element);
        });
    });
	
	/*
	Collapse All metaboxes membpress
	*/
    $('.membpress_settings_collapse').click(function(e) {
        $('.postbox').each(function(index, element) {
            $(element).addClass('closed');
			membpress_set_collapse_cookie(element);
        });
    });
	
	/*
	Close update message bar
	*/
	$('.membpress_updated_close').click(function(e) {
        $(this).parents('.updated').fadeOut(300);
		$(this).parents('.error').fadeOut(300);
    });
	
	/*
	Jump to a section in membpress admin panel and open it
	It works when a link is clicked
	*/
	$('.membpress_goto_section').click(function(e) {
        var target_section = $(this).attr('rel');
		// expand the target section
		$(target_section).removeClass('closed');
		// set expand cookie for the target section
		membpress_set_expand_cookie($(target_section));
		// scroll to the target section
		$('html, body').animate({
           scrollTop: $(target_section).offset().top - 40
        }, 1000);
    });
	
	/*
	Jump to a section in membpress admin panel
	It works with the hastag in URL
	*/
	function membpress_goto_section_hash()
	{
	   var membpress_section_hash = window.location.hash;
	   var membpress_target_section = membpress_section_hash.split('section=');
	   membpress_target_section = membpress_target_section[1];
	   
	   if ($(membpress_target_section).length <= 0)
	   return;
	   
	   var offset_scroll = $(membpress_target_section).find('.hndle').height() + 40;
	   
	   var membpress_section_query = (window.location).toString();
	   var membpress_target_section_query = membpress_section_query.split('&section=');
	   
	   if ($(membpress_target_section_query).length > 1)
	   {
		   offset_scroll = offset_scroll + $('.below-h2').outerHeight();     
	   }
	   
	   if (window.outerWidth <= 616)
	   {
		   offset_scroll = offset_scroll - 40;   
	   }
	   
		// expand the target section
		$(membpress_target_section).removeClass('closed');
		// set expand cookie for the target section
		membpress_set_expand_cookie($(membpress_target_section));
		// scroll to the target section
		try
		{
		   $('html, body').animate({
              scrollTop: $(membpress_target_section).offset().top - offset_scroll
           }, 1000);
		}
		catch(e)
		{}
	}
	membpress_goto_section_hash();
	
})