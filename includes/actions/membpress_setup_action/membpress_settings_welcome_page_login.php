<?php
/**
* This file handles the submit action of the 'MembPress Basic Setup -> Welcome Page After Login' section

* Copyright: © 2014
* {@link http://www.membpress.com, MembPress Inc.}
* {@author Waseem Khan}
*
* Released under the terms of the GNU General Public License.
* See the directory /license/
*
* @package membpress
* @since 1.0
*/

// check if the file is being called by the membpress engine
if (!defined('MEMBPRESS_LOADED'))
{
   exit;	
}

// membpress options page ID
$mp_membership_option_page = sanitize_text_field($_POST['membpress_settings_membership_option_page']);
// flag to check if the membpress options page is equal to (any) login redirect page
$mp_membership_option_page_flag = false;

// update welcome login type for global redirect
update_option('membpress_settings_welcome_login_type', sanitize_text_field($_POST['membpress_settings_welcome_login_type']));
// update welcome login page ID, global
// make sure, page ID is valid
$mp_welcome_login_page = get_post(sanitize_text_field($_POST['membpress_settings_welcome_login_page']));
if ($mp_welcome_login_page)
{
	if ($mp_welcome_login_page->post_type == 'page' && $mp_welcome_login_page->post_status == 'publish')
	{
	   update_option('membpress_settings_welcome_login_page', sanitize_text_field($_POST['membpress_settings_welcome_login_page']));
	}
	else if(sanitize_text_field($_POST['membpress_settings_welcome_login_type']) == 'page')
	{
	   $membpress_error_flag = true;
	   $membpress_error_section = 'all';
	   $membpress_error_id = 5;		
	}
}
else
{
   update_option('membpress_settings_welcome_login_page', sanitize_text_field($_POST['membpress_settings_welcome_login_page']));	
}
// update welcome login post ID, global
// we need to make sure, the post ID is valid
$mp_welcome_login_post = get_post(sanitize_text_field($_POST['membpress_settings_welcome_login_post']));
if ($mp_welcome_login_post)
{
	if ($mp_welcome_login_post->post_type != 'page' && $mp_welcome_login_post->post_status == 'publish')
	{
	   update_option('membpress_settings_welcome_login_post', sanitize_text_field($_POST['membpress_settings_welcome_login_post']));
	}
	else if (sanitize_text_field($_POST['membpress_settings_welcome_login_type']) == 'post')
	{
	   $membpress_error_flag = true;
	   $membpress_error_section = 'all';
	   $membpress_error_id = 3;	
	}
}
else
{
   update_option('membpress_settings_welcome_login_post', sanitize_text_field($_POST['membpress_settings_welcome_login_post']));	
}
// update welcome login URL, global
update_option('membpress_settings_welcome_login_url', sanitize_text_field($_POST['membpress_settings_welcome_login_url']));
// update the login redirect page/post restriction option
update_option('membpress_settings_welcome_login_restrict', isset($_POST['membpress_settings_welcome_login_restrict']) ? 1 : 0);
// update the scope of login welcome redirect as global or individual
update_option('membpress_settings_welcome_login_individual', isset($_POST['membpress_settings_welcome_login_individual']) ? 1 : 0);

// if scope is set to individual
if (isset($_POST['membpress_settings_welcome_login_individual']))
{
   // iterate all the membpress login redirect levels
   foreach($_POST as $key => $val)
   {
	  // proceed only if the current post data is for login welcome page only
	  if (stristr($key, 'membpress_settings_welcome_login') === FALSE) continue;
	  
	  // check if the membpress login redirect page/post/url is set for this level
	  if (str_replace(array('membpress_settings_welcome_login_type_', 'membpress_settings_welcome_login_page_', 'membpress_settings_welcome_login_post_', 'membpress_settings_welcome_login_url_'), '', $key) != '')
	  {
		  update_option($key, sanitize_text_field($val)); 
	  }
	  
	  // check if the membpress login type is set for this level
	  if (stristr($key, 'membpress_settings_welcome_login_type_'))
	  { 
		 // get the login redirect type
		 $membpress_role_name = explode('membpress_settings_welcome_login_type_', $key);
		 $membpress_role_name = $membpress_role_name[1];
		 
		 // update the restriction option
		 update_option('membpress_settings_welcome_login_restrict_'.$membpress_role_name, isset($_POST['membpress_settings_welcome_login_restrict_'.$membpress_role_name]) ? 1 : 0);
	  }
   }
   
   $mp_login_redirect_vars = $membpress->mp_helper->membpress_get_login_redirect_setting_vars();
   $mp_login_redirect_types = $mp_login_redirect_vars['login_redirect_type'];
   $mp_login_redirect_ids = $mp_login_redirect_vars['login_redirect_id'];
   $mp_login_redirect_levels = $mp_login_redirect_vars['login_redirect_levels'];
   
   for($i = 0; $i < count($mp_login_redirect_types); $i++)
   {
	   // check if the login redirect post/page ID exists or not
	   // if not then skip the rest of the code in the current loop
	   if (!isset($mp_login_redirect_ids[$i]))
	   {
		   continue;   
	   }
	   
	   // check if the login redirect post ID set is valid
	   if ($mp_login_redirect_types[$i] == 'post')
	   {
		   $mp_welcome_login_post = get_post($mp_login_redirect_ids[$i]);
		   if ($mp_welcome_login_post->post_type != 'page')
		   {
		   }
		   else if ($mp_welcome_login_post->post_status == 'publish')
		   {
			  $membpress_error_flag = true;
			  $membpress_error_section = 'all';
			  $membpress_error_id = 4;
			  $notice_vars = $mp_login_redirect_ids[$i] . ',' . $membpress_wp_roles[$mp_login_redirect_levels[$i]];     
		   }
	   }
	   
	   // check if the login redirect welcome page set for any membership level
	   // matches the membership options page
	   if ($mp_login_redirect_types[$i] == 'page' && $mp_login_redirect_ids[$i] == $mp_membership_option_page && $mp_membership_option_page > 0)
	   {
		   $mp_membership_option_page_flag = true;
		   $membpress_error_flag = true;
		   $membpress_error_section = 'membpress_settings_membership_options_page';
		   $membpress_error_id = 2;
		   break;     
	   }
   }
}
else // if scope is set to global
{
   if (sanitize_text_field($_POST['membpress_settings_welcome_login_type']) == 'page' && sanitize_text_field($_POST['membpress_settings_welcome_login_page']) == $mp_membership_option_page && $mp_membership_option_page > 0)
   {
	  $mp_membership_option_page_flag = true;
	  $membpress_error_flag = true;
	  $membpress_error_section = 'membpress_settings_membership_options_page';
	  $membpress_error_id = 2;
   }
}


?>