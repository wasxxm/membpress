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
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_restrict_checkbox')).show();
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_post').parent('p')).hide();
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_url').parent('p')).hide();	
		}
		else if (membpress_settings_welcome_login_type == 'post')
		{
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_post').parent('p')).show();
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_restrict_checkbox')).show();
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_page').parent('p')).hide();
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_url').parent('p')).hide();	
		}
	    else if (membpress_settings_welcome_login_type == 'url')
		{
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_url').parent('p')).show();
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_post').parent('p')).hide();
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_page').parent('p')).hide();
		  $($(membpress_settings_welcome_group).find('.membpress_settings_welcome_login_restrict_checkbox')).hide();	
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
	
	/**
	Handle the Membpress Subscription rates, subscription types field
	*/
	$('.membpress_subs_type').on('change', function()
	{
	    var parent = $(this).parents('.membpress_subs_rate_wrapper');
		membpress_manage_subscription_types_fields(parent);
	});
	
	$('.membpress_subs_rate_wrapper').each(function(index, element) {
        membpress_manage_subscription_types_fields(element);    
    });
	
	function membpress_manage_subscription_types_fields(parent)
	{		
		var el = $(parent).find('.membpress_subs_type');
		
		if ($(el).val() == 'recurring')
		{
		   $(parent).find('.membpress_subs_recurring_txt').fadeIn(100);
		   $(parent).find('.membpress_subs_onetime_txt').hide();
		   $(parent).find('.membpress_subs_lifetime_txt').hide();
		   
		   $(parent).find('.membpress_subs_recurring_for').fadeIn(100);
		   $(parent).find('.membpress_subs_onetime_for').hide();
		   $(parent).find('.membpress_subs_lifetime_for').hide();
		   
		   $(parent).find('.membpress_subs_duration').fadeIn(100);	
		}
	    else if ($(el).val() == 'one_time')
		{
		   $(parent).find('.membpress_subs_recurring_txt').hide();
		   $(parent).find('.membpress_subs_onetime_txt').fadeIn(100);
		   $(parent).find('.membpress_subs_lifetime_txt').hide();
		   
		   $(parent).find('.membpress_subs_recurring_for').hide();
		   $(parent).find('.membpress_subs_onetime_for').fadeIn(100);
		   $(parent).find('.membpress_subs_lifetime_for').hide();
		   
		   $(parent).find('.membpress_subs_duration').fadeIn(100);	
		}
		else if ($(el).val() == 'life_time')
		{
		   $(parent).find('.membpress_subs_recurring_txt').hide();
		   $(parent).find('.membpress_subs_onetime_txt').hide();
		   $(parent).find('.membpress_subs_lifetime_txt').fadeIn(100);
		   
		   $(parent).find('.membpress_subs_recurring_for').hide();
		   $(parent).find('.membpress_subs_onetime_for').hide();
		   $(parent).find('.membpress_subs_lifetime_for').fadeIn(100);
		   
		   $(parent).find('.membpress_subs_duration').hide();	
		}	
	}
	
	/**
	Input/textarea readonly click select all
	*/
	$('.membpress input[readonly], .membpress textarea[readonly]').on('click', function()
	{
	   $(this).select();
	}
	);
	
	
	/**
	Check all widgets if a sidebar is checked.
	Also uncheck the sidebar if any of the widget is unchecked
	*/
	$('.membpress_restrict_widgets_wrapper').each(function(index, element) {
		var if_all_checked = true;
		$(element).find('li').each(function(index, element) {
           if (index > 0)
		   {
			   if (!$(element).find('input[type=checkbox]:checked'))
			   {
				   if_all_checked = false;   
			   }
			   else
			   {
			   }
				   
		   }
		   return;
        });
		
		if (!if_all_checked)
		{
		   $(element).find('li:first input[type=checkbox]').removeAttr('checked');	
		}
    });
	
	$('.membpress_restrict_widgets_wrapper input[type=checkbox]').change(function(e) {
		var widget_li = $(e.target).parents('li');
		var widget_ul = $(e.target).parents('ul');
		var widget_li_index = $(widget_li).index();
		
		if (widget_li_index == 0 && $(e.target).attr('checked'))
		{
		   $(widget_ul).find('li').each(function(index, element) {
              if (index > 0)
			  {
				 $(element).find('input[type=checkbox]').attr('checked', true);  
			  }
           }); 
		   return;  	
		}
		
		if (widget_li_index > 0 && !$(e.target).attr('checked'))
		{
		   	$(widget_ul).find('li:first input[type=checkbox]').removeAttr('checked');
		}
		
		// also check if all the widgets are checked
		// if yes then check the sidebar
		// only do if it is not sidebar itself
		if (widget_li_index > 0)
		{
			if ($(widget_ul).find('li:nth-child(n + 2) input[type=checkbox]:checked').length == ($(widget_ul).find('li').length - 1))
			{
				$(widget_ul).find('li:first input[type=checkbox]').attr('checked', true);
			}
		}
		else
		{
		   $(widget_ul).find('li').each(function(index, element) {
              if (index > 0)
			  {
				 $(element).find('input[type=checkbox]').removeAttr('checked');  
			  }
           });    	
		}
		
    });
	
	/**
	Hide/Display login page customization options
	*/
	$('#membpress_settings_customize_login_page_flag').change(function(e) {
        if (this.checked)
		{
		   $('.membpress_login_customize_options').fadeIn(200);	
		}
		else
		{
		   $('.membpress_login_customize_options').fadeOut(200);
		}
    });
	
	// Add Color Picker to all inputs that have 'color-field' class
    $(function() {
        $('.color-field').wpColorPicker();
    });
	
	// reset colors in login customize section
	$('.reset-login-color').click(function(e)
	{
		var el = $('#' + $(this).data('element-id'));	
		var col_val = $(this).data('color-value');
	
        $(el).wpColorPicker('color', col_val);    
    });
	
	
	
	/**
	Toggle the display of the edit subscription form on the Membpress Subscription page
	*/
	$('.mp_subs_edit_btn').click(function(e) {
        var this_parent = $(this).parents('.membpress_subs_rates');
		$(this_parent).find('.membpress_subs_rate_wrapper').show();
		$(this_parent).find('.mp_subs_canceledit_btn').show();
		$(this).hide();
    });
	$('.mp_subs_canceledit_btn').click(function(e) {
        $(this).parents('.membpress_subs_rates').find('.membpress_subs_rate_wrapper').hide();
		$(this).parents('.membpress_subs_rates').find('.mp_subs_edit_btn').show();
		$(this).hide();
    });
	$('.membpress_subs_cancel').click(function(e) {
        $(this).parents('.membpress_subs_rates').find('.membpress_subs_rate_wrapper').hide();
		$(this).parents('.membpress_subs_rates').find('.mp_subs_edit_btn').show();
		$(this).parents('.membpress_subs_rates').find('.mp_subs_canceledit_btn').hide(); 
    });
});

/**
Customize the display of Widgets/Sidebars titles to show the restriction imposed by some level
Keeping it global so it can be accessed globally
*/
function membpress_restrict_title_widget(sidebar_id, widget_id, level_name)
{ 
   jQuery('#' + sidebar_id +  ' .widget[id^=widget-][id$=' + widget_id + '] .widget-title').append('<span class="membpress-in-widget-title">' + level_name + '</span>');
   jQuery('#accordion-section-sidebar-widgets-' + sidebar_id + ' .widget[id^=widget-][id$=' + widget_id + '] .widget-title').append('<span class="membpress-in-widget-title">' + level_name + '</span>');	
}