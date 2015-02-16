<?php
/**
* Helper Membpress class. This class contains functions which help
* the membpress framework to invoke different WP built-in functions

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

// load the Wordpress file
require_once ABSPATH . 'wp-admin/includes/file.php';
// load the wordpress misc file
require_once ABSPATH .  'wp-admin/includes/misc.php';

class MembPress_Helper
{
	/*
	@ Class contructor
	*/
 
	
	public function MembPress_Helper()
	{
		//
	}
	
	
	/*
	@ Notice IDs
	*/
	public function membpress_get_notices($notice_id = 0, $str = array())
	{
	   // initialize the notice ID array
	   $notice_ids = array
	   (
	      1 => _x('Membpress Setup/Settings successfully updated', 'membpress_notices', 'membpress'),
		  2 => _x('Membpress Membership Options page cannot be set to a page used as the login welcome redirection.<br>Please specify a page other than the login welcome redirect page. The login welcome redirect feature won\'t work until you fix it.', 'membpress_notices', 'membpress'),
		  3 => _x('Please provide a valid Post ID to be used as the login welcome redirect.', 'membpress_notices', 'membpress'),
		  4 => sprintf(_x('The Post ID: %s is not valid. Please provide a valid post ID to be used as the login welcome redirect for membpress level: %s', 'membpress_notices', 'membpress'), @$str[0], @$str[1]),
		  5 => _x('Please provide a valid Page ID to be used as the login welcome redirect.', 'membpress_notices', 'membpress'),
		  6 => _x('Membpress Restriction Options successfully updated', 'membpress_notices', 'membpress'),
		  7 => sprintf(_x('New Subscription Rate has been added to the membership level: %s', 'membpress_notices', 'membpress'), @$str[0]),
		  8 => sprintf(_x('There is already a subscription rate (%s) having the same settings. Duplicate subscription rates are not allowed.', 'membpress_notices', 'membpress'), $this->membpress_get_subscription_rate_name(@$str[2], @$str[1]))	
       );
	   
	   if ($notice_id > 0 && isset($notice_ids[$notice_id]))
	   {
		   return $notice_ids[$notice_id];   
	   }
	   else
	   {
		   return false;   
	   }
	   	
	}
    
	/**
	*  login_redirect filter for membpress login welcome redirect
	*/
	public function membpress_login_welcome()
	{
	   add_filter( 'login_redirect', array($this, 'membpress_login_welcome_redirect'), 10, 3 );	
	}
	
	/**
	* Redirect user after successful login to the welcome page
	* set in the 'Welcome Page after Login' section
	*
	* @param string $redirect_to URL to redirect to.
	* @param string $request URL the user is coming from.
	* @param object $user Logged user's data.
	* @return string
	*/
	
	public function membpress_login_welcome_redirect( $redirect_to, $request, $user )
	{
		//is there a user to check?
		global $user;
		
		if ( isset( $user->roles ) && is_array( $user->roles ) )
		{
			//check for admins
			if ( in_array( 'administrator', $user->roles ) )
			{
				// redirect administrators to the default place
				return $redirect_to;
			}
			else 
			{
				// get the membpress membership options page
				$mp_membpership_options_page = get_option('membpress_settings_membership_option_page');
				
				// we are ready to check for the login welcome redirections
				// check if the login redirect is set for individual membpress levels
				$mp_login_individual = get_option('membpress_settings_welcome_login_individual');
				
				if ($mp_login_individual)
				{
				   
				   global $wp_roles; // global varaible holding wp roles object

				   if ( ! isset( $wp_roles ) )
				   {
					   $wp_roles = new WP_Roles();
				   }
				   
				   $membpress_roles_names = $wp_roles->role_names;
				   
				   // redirect for individual membpress levels is set in settings
				   // first get membpress levels, including subscription level 0
				   foreach ($membpress_roles_names as $mp_role_key => $mp_role_val)
				   {
					   	// check if the role is defined by MembPress like membpress_level_1
						// include the subscriber role as the Membpress Level 0 /Free Level
						if (stristr($mp_role_key, 'membpress_level_') !== FALSE || $mp_role_key == 'subscriber')
						{
						   // check the level of the current user
						   if ( in_array( $mp_role_key , $user->roles ) )
						   {
							  // redirect the user to the level redirect settings
							  $mp_login_type = get_option('membpress_settings_welcome_login_type_'.$mp_role_key);
							  
							  if ($mp_login_type == 'page') // if the redirect is set to a page
							  {
								 // get the redirect page id for the current level
								 $mp_login_page_id = get_option('membpress_settings_welcome_login_page_'.$mp_role_key);
								 
								 // proceed only if the page id is valid
								 if (isset($mp_login_page_id) && $mp_login_page_id != '')
								 {
									 // check if the page ID matches the membership options page
									 if ($mp_membpership_options_page == $mp_login_page_id)
									 {
										 // return the default login redirection url
										 return $redirect_to;	 
									 }
									 
									 // get permalink of the page
									 $mp_login_page_permalink = get_permalink($mp_login_page_id);
									 if ($mp_login_page_permalink)
									 {
										 // return the page permalink
										 // user will redirect to this link
										 return $mp_login_page_permalink;   
									 }
									 else
									 {
										 // permalink not found, redirect to default link
										 return $redirect_to;   
									 }
								 }
								 else
								 {
									 // page id is invalid, redirect to default
									 return $redirect_to;   
								 }
							  }
							  else if ($mp_login_type == 'post') // if redirect type is set to a post 
							  {
								 // get the id of the post ID set for the current membpress membership level
								 $mp_login_post_id = get_option('membpress_settings_welcome_login_post_'.$mp_role_key);
								 // only process if the post id is valid
								 if (isset($mp_login_post_id) && $mp_login_post_id != '')
								 {
									 // get permalink of the post id
									 $mp_login_post_permalink = get_permalink($mp_login_post_id);
									 if ($mp_login_post_permalink)
									 {
										 // redirect user to the assigned post permalink
										 return $mp_login_post_permalink;   
									 }
									 else
									 {
										 // permalink not found, redirect to default url
										 return $redirect_to;   
									 }
								 }
								 else
								 {
									 // post id is invalid, redirect to default return url
									 return $redirect_to;   
								 }
							  }
							  else if ($mp_login_type == 'url') // if redirect set to an absolute url
							  {
								 // get the external url set for the current member level of the user
								 $mp_login_url = get_option('membpress_settings_welcome_login_url_'.$mp_role_key);
								 // check if the url is a valid url
								 if (isset($mp_login_url) && $mp_login_url != '' && filter_var($mp_login_url, FILTER_VALIDATE_URL))
								 {
									 // do a wp redirect to the absolute url, and exit
									 wp_redirect($mp_login_url);
									 exit; 
								 }
								 else
								 {
									 // url is invalid, redirect to default url
									 return $redirect_to;   
								 }
							  } 
						   }
						}   
				   }
				}
				
				// if a return did not occur above, it means, the membpress login redirect is not
				// individually for each member level. Proceed with the global redirect type
				
				$mp_login_type = get_option('membpress_settings_welcome_login_type'); // get the redirect type option
				if ($mp_login_type == 'page') // if set to page
				{
				   // get the page id for the current member level
				   $mp_login_page_id = get_option('membpress_settings_welcome_login_page');
				   // only proceed if the page id is valid
				   if (isset($mp_login_page_id) && $mp_login_page_id != '')
				   {
					    // check if the page ID matches the membership options page
					   if ($mp_membpership_options_page == $mp_login_page_id)
					   {
						   // return the default login redirection url
						   return $redirect_to;	 
					   }
					   
					   // get permalink of the page id
					   $mp_login_page_permalink = get_permalink($mp_login_page_id);
					   if ($mp_login_page_permalink)
					   {
						   // return the permalink, user will redirect to this page permalink
						   return $mp_login_page_permalink;   
					   }
					   else
					   {
						   // page permalink was not found, redirect to the default gateway
						   return $redirect_to;   
					   }
				   }
				   else
				   {
					   // page id is not set or invalid, redirect to default url
					   return $redirect_to;   
				   }
				}
				else if ($mp_login_type == 'post') // if login redirect is set to post
				{
				   // get the post id of the current member level
				   $mp_login_post_id = get_option('membpress_settings_welcome_login_post');
				   // proceed only if the post id returned, is valid
				   if (isset($mp_login_post_id) && $mp_login_post_id != '')
				   {
					   // get the permalink of the post id
					   $mp_login_post_permalink = get_permalink($mp_login_post_id);
					   if ($mp_login_post_permalink)
					   {
						   // permalink found, return the post permalink
						   // user will redirect to this post permalink
						   return $mp_login_post_permalink;   
					   }
					   else
					   {
						   // no permalink found for post id, return the default url for redirection
						   return $redirect_to;   
					   }
				   }
				   else
				   {
					   // post id is invalid. Redirect default
					   return $redirect_to;   
				   }
				}
				else if ($mp_login_type == 'url') // if login welcome redirect is set to an absolute URL
				{
				   // get the url
				   $mp_login_url = get_option('membpress_settings_welcome_login_url');
				   // check if the url is valid (syntax wise)
				   if (isset($mp_login_url) && $mp_login_url != '' && filter_var($mp_login_url, FILTER_VALIDATE_URL))
				   {
					   // do a wp redirect of the absolute url and exit to prevent any further processing
					   wp_redirect($mp_login_url);
					   exit; 
				   }
				   else
				   {
					   // url is invalid, redirect to default url of WP
					   return $redirect_to;   
				   }
				}
			}
		}
		// user isn't assigned any role or the user is not valid or not logged in
		else
		{
			// return to default wp login redirect url
			return $redirect_to;
		}
	}
	
	
	/*
	@ function membpress_add_default_roles
	@ This function adds the default 4 roles to the wordpress database
	*/
	
	public function membpress_add_default_roles()
	{
       // by default, membpress will add 4 membership levels
	   // WP built-in 'subscriber' role will be treated as the free level, MembPress Level 0
	   // each level will only have the read capability
	   
	   // but also check if the MEMBPRESS_LEVEL_COUNT is more than 4 or not. If yes, create additional roles
	   
	   // Also update the array of option holding the membership levels, names and other params like subscription charges
	   
	   if (defined('MEMBPRESS_LEVEL_COUNT') && MEMBPRESS_LEVEL_COUNT > 4)
	   {
		   for($i = MEMBPRESS_LEVEL_COUNT; $i > 4; $i--)
		   {
			   if (@defined('MEMBPRESS_LEVEL_'.$i) && @trim(constant(MEMBPRESS_LEVEL_.$i)) != '')
			   {
			      add_role( 'membpress_level_'.$i, @trim(constant(MEMBPRESS_LEVEL_.$i)), array( 'read' => true, 'delete_posts' => false, 'edit_posts'   => false) );
				  // update the levels array
				  $this->membpress_update_level_by_name('membpress_level_'.$i, @trim(constant(MEMBPRESS_LEVEL_.$i)));
			   }
			   else
			   {
				  add_role( 'membpress_level_'.$i, 'Membership Level '.$i, array( 'read' => true, 'delete_posts' => false, 'edit_posts'   => false) );
				  // update the levels array
				  $this->membpress_update_level_by_name('membpress_level_'.$i, 'Membership Level '.$i);   
			   }
		   }
	   }

	   add_role( 'membpress_level_4', MEMBPRESS_LEVEL_4, array( 'read' => true, 'delete_posts' => false, 'edit_posts'   => false) );
	   add_role( 'membpress_level_3', MEMBPRESS_LEVEL_3, array( 'read' => true, 'delete_posts' => false, 'edit_posts'   => false) );
	   add_role( 'membpress_level_2', MEMBPRESS_LEVEL_2, array( 'read' => true, 'delete_posts' => false, 'edit_posts'   => false) );
	   add_role( 'membpress_level_1', MEMBPRESS_LEVEL_1, array( 'read' => true, 'delete_posts' => false, 'edit_posts'   => false) );
	   
	   // update the levels array
	   $this->membpress_update_level_by_name('membpress_level_0', MEMBPRESS_LEVEL_0);
	   $this->membpress_update_level_by_name('membpress_level_1', MEMBPRESS_LEVEL_1);
	   $this->membpress_update_level_by_name('membpress_level_2', MEMBPRESS_LEVEL_2);
	   $this->membpress_update_level_by_name('membpress_level_3', MEMBPRESS_LEVEL_3);
	   $this->membpress_update_level_by_name('membpress_level_4', MEMBPRESS_LEVEL_4);

	   
	   // get the WP roles object
	   global $wp_roles;
	   if ( ! isset( $wp_roles ) )
	   {
		  $wp_roles = new WP_Roles();
	   }
	   
	   // rename the subscriber level to display name set in the membpress.config.php file
	   $this->membpress_update_role_display_name($wp_roles, 'subscriber', MEMBPRESS_LEVEL_0);
   }
   
   /**
   @ Function to update list of membership levels
   @ $level_name is the index for membership levels array set in membpress_levels
   @ $level_display_name is the name like 'Free Member' for the level in first param
   */
   public function membpress_update_level_by_name($level_name, $level_display_name)
   {
	  // first get the membpress_levels array 
	  $membpress_levels =  (array)get_option('membpress_levels');
	  
	  // update the level name and index (if not present already)
	  if (trim($level_name) != '' && isset($membpress_levels[$level_name]) && is_array($membpress_levels[$level_name]))
	  {
	      // if already present, save and then update the old value
		  $membpress_level_prev = $membpress_levels[$level_name];
		  $membpress_level_prev['display_name'] = $level_display_name;
		  
		  $membpress_levels[$level_name] = $membpress_level_prev;	  
	  }
	  else if (trim($level_display_name) != '' && trim($level_name) != '')
	  {
	     $membpress_levels[$level_name] = array('display_name' => $level_display_name);
	  }
	  
	  // update the membpress levels array
	  // check if levels are valid indeed
	  foreach($membpress_levels as $membpress_level_key => $membpress_level_value)
	  {
	      if (!isset($membpress_level_value['display_name']) || trim($membpress_level_value['display_name']) == '')
		  {
			  unset($membpress_levels[$membpress_level_key]);  
		  }
	  }
	  
	  update_option('membpress_levels', $membpress_levels); 
   }
   
   /*
   @ Function used to check which page/post is set as the login welcome redirect
   @ it returns the login redirect scope (global or individual)
   @ and the redirect login type(s) with the page ID(s)/post ID(s)
   @ It also returns the login redirect individual membership levels, if the scope is not set to global
   */
   public function membpress_get_login_redirect_setting_vars()
   {
	  // get the login welcome redirection mapping, global or individual
	  $login_redirect_scope = (bool)get_option('membpress_settings_welcome_login_individual');
	  
	  // if the login redirect is set for each membership level then,
	  if ($login_redirect_scope)
	  {
		  $login_redirect_scope = 'individual';
		  $login_redirect_levels = array();
		  $login_redirect_type = array();
		  $login_redirect_id = array();
		  $login_redirect_restrict_flags = array();

		   global $wp_roles; // global varaible holding wp roles object
  
		   if ( ! isset( $wp_roles ) )
		   {
			   $wp_roles = new WP_Roles();
		   }
		   
		   $membpress_roles_names = $wp_roles->role_names;
		   
		   // redirect for individual membpress levels is set in settings
		   // first get membpress levels, including subscription level 0
		   foreach ($membpress_roles_names as $mp_role_key => $mp_role_val)
		   {
			  // check if the role is defined by MembPress like membpress_level_1
			  // include the subscriber role as the Membpress Level 0 /Free Level
			  if (stristr($mp_role_key, 'membpress_level_') !== FALSE || $mp_role_key == 'subscriber')
			  {
				  // redirect the user to the level redirect settings
				  $mp_login_type = get_option('membpress_settings_welcome_login_type_'.$mp_role_key);
				  
				  $mp_login_restrict = get_option('membpress_settings_welcome_login_restrict_'.$mp_role_key);
				  
				  $login_redirect_type[] = $mp_login_type;
				  $login_redirect_levels[] = $mp_role_key;
				  $login_redirect_restrict_flags[] = $mp_login_restrict; 
				  
				  if ($mp_login_type == 'page') // if the redirect is set to a page
				  {
					 // get the redirect page id for the current level
					 $mp_login_page_id = get_option('membpress_settings_welcome_login_page_'.$mp_role_key);
					 // proceed only if the page id is valid
					 if (isset($mp_login_page_id) && $mp_login_page_id != '')
					 {
						 $login_redirect_id[] = $mp_login_page_id;
					 }
					 else
					 {
						$login_redirect_id[] = ''; 
					 }
				  }
				  else if ($mp_login_type == 'post') // if redirect type is set to a post 
				  {
					 // get the id of the post ID set for the current membpress membership level
					 $mp_login_post_id = get_option('membpress_settings_welcome_login_post_'.$mp_role_key);
					 // only process if the post id is valid
					 if (isset($mp_login_post_id) && $mp_login_post_id != '')
					 {
						 $login_redirect_id[] = $mp_login_post_id;
					 }
					 else
					 {
						$login_redirect_id[] = ''; 
					 }
				  }
				  else if ($mp_login_type == 'url') // if redirect set to an absolute url
				  {
					 // get the external url set for the current member level of the user
					 $mp_login_url = get_option('membpress_settings_welcome_login_url_'.$mp_role_key);
					 // check if the url is a valid url
					 if (isset($mp_login_url) && $mp_login_url != '' && filter_var($mp_login_url, FILTER_VALIDATE_URL))
					 {
						 $login_redirect_id[] = $mp_login_url;
					 }
				  } 
			  }   
		   }
		  
	  }
	  // login redirect is set for all users, except the administrator
	  else
	  {
		 $login_redirect_scope = 'global';
		 $login_redirect_levels = 'all';
		 
		 $login_redirect_restrict_flags = get_option('membpress_settings_welcome_login_restrict');
		 
		 // get the global login redirect type
		 $login_redirect_type = get_option('membpress_settings_welcome_login_type');
		 // if login redirect is set to page
		 if ($login_redirect_type == 'page')
		 {
			 // get the login redirect page ID
			 $login_redirect_id = get_option('membpress_settings_welcome_login_page');
		 }
		 else if ($login_redirect_type == 'post')
		 {
			 // get the login redirect post ID
			 $login_redirect_id = get_option('membpress_settings_welcome_login_post');
		 }
		 else if ($login_redirect_type == 'url')
		 {
		   	 $login_redirect_id = get_option('membpress_settings_welcome_login_url');	 
		 }
	  } 
	  
	  return array('login_redirect_scope' => $login_redirect_scope, 'login_redirect_type' => $login_redirect_type, 'login_redirect_id' => $login_redirect_id, 'login_redirect_levels' => $login_redirect_levels, 'login_redirect_restrict_flags' => $login_redirect_restrict_flags);  
   }
   
   
   
   /**
   @ Function to check if a page ID is set as login welcome redirect for any/all membership level(s)
   @ If the Page is set as login welcome redirect, it returns the level number, else it returns false
   @ page_id is the page ID passed to be check
   */
   public function membpress_check_page_login_redirect_exists($page_id)
   {
	   // if page ID is not valid, return false
	   if (!isset($page_id) || $page_id <= 0) return false;
	   
	   // get the login welcome redirect settings, set in MembPress -> Basic Setup -> Login Welcome Page
	   $redirect_vars = $this->membpress_get_login_redirect_setting_vars(); 
	   
	   // if login scope is global, login redirect type is page and login redirect ID is same as the passed page ID, return the level all
	   if ($redirect_vars['login_redirect_scope'] == 'global' && $redirect_vars['login_redirect_type'] == 'page' && $redirect_vars['login_redirect_id'] == $page_id)
	   {
		   return array('level_name' => 'all', 'level_no' => '');   
	   }
	   
	   // Page ID was not found in the global login redirect at least
	   // now check if the individual redirection is set
	   
	   // array to hold the membership level numbers and names for which the page is set as login welcome redirect
	   $login_redirect_level_numbers = array();
	   $login_redirect_level_names = array();
	   
	   // if login welcome redirect is set individually
	   if ($redirect_vars['login_redirect_scope'] == 'individual')
	   {
	      // get the login redirect vars to be used in for loop
		  $login_redirect_levels = $redirect_vars['login_redirect_levels'];
		  $login_redirect_type = $redirect_vars['login_redirect_type'];
		  $login_redirect_id = $redirect_vars['login_redirect_id'];
		  
		  for ($i = 0; $i < count($login_redirect_levels); $i++)
		  {
		      // if the login redirect type is page and the passed Page ID matches the login redirect ID in loop
			  if ($login_redirect_type[$i] == 'page' && $login_redirect_id[$i] == $page_id)
			  {
			      // extract the level number, like 7 from membpress_level_7
		          $level_no = $this->membpress_extract_level_no($login_redirect_levels[$i]);
		          // get the level name
		          $level_name = $this->membpress_get_membership_level_name($level_no);
				  
				  $login_redirect_level_numbers[] = $level_no;
				  $login_redirect_level_names[] = $level_name;   
			  }
		  }
	   }
	   
	   // only proceed if the Page ID was set for any level
	   if (count($login_redirect_level_numbers) > 0)
	   {
		   // return the level names and numbers array
		   return array('level_name' => $login_redirect_level_names, 'level_no' => $login_redirect_level_numbers);  
	   }
	   
	   // the Page ID is not set as login welcome redirect for any level, for any scope
	   // so return false
	   return false;  
   }
   
   
   
   /**
   @ Function to check if a post ID is set as login welcome redirect for any/all membership level(s)
   @ If the Post is set as login welcome redirect, it returns the level numbers, else it returns false
   @ post_id is the post ID passed to be check
   */
   public function membpress_check_post_login_redirect_exists($post_id)
   {
	   // if post ID is not valid, return false
	   if (!isset($post_id) || $post_id <= 0) return false;
	   
	   // get the login welcome redirect settings, set in MembPress -> Basic Setup -> Login Welcome Post
	   $redirect_vars = $this->membpress_get_login_redirect_setting_vars(); 
	   
	   // if login scope is global, login redirect type is post and login redirect ID is same as the passed post ID, return the level all
	   if ($redirect_vars['login_redirect_scope'] == 'global' && $redirect_vars['login_redirect_type'] == 'post' && $redirect_vars['login_redirect_id'] == $post_id)
	   {
		   return array('level_name' => 'all', 'level_no' => '');   
	   }
	   
	   // Post ID was not found in the global login redirect at least
	   // now check if the individual redirection is set
	   
	   // array to hold the membership level numbers and names for which the post is set as login welcome redirect
	   $login_redirect_level_numbers = array();
	   $login_redirect_level_names = array();
	   
	   // if login welcome redirect is set individually
	   if ($redirect_vars['login_redirect_scope'] == 'individual')
	   {
	      // get the login redirect vars to be used in for loop
		  $login_redirect_levels = $redirect_vars['login_redirect_levels'];
		  $login_redirect_type = $redirect_vars['login_redirect_type'];
		  $login_redirect_id = $redirect_vars['login_redirect_id'];
		  
		  for ($i = 0; $i < count($login_redirect_levels); $i++)
		  {
		      // if the login redirect type is post and the passed Post ID matches the login redirect ID in loop
			  if ($login_redirect_type[$i] == 'post' && $login_redirect_id[$i] == $post_id)
			  {
			      // extract the level number, like 7 from membpress_level_7
		          $level_no = $this->membpress_extract_level_no($login_redirect_levels[$i]);
		          // get the level name
		          $level_name = $this->membpress_get_membership_level_name($level_no);
				  
				  $login_redirect_level_numbers[] = $level_no;
				  $login_redirect_level_names[] = $level_name;   
			  }
		  }
	   }
	   
	   // only proceed if the Post ID was set for any level
	   if (count($login_redirect_level_numbers) > 0)
	   {
		   // return the level names and numbers array
		   return array('level_name' => $login_redirect_level_names, 'level_no' => $login_redirect_level_numbers);  
	   }
	   
	   // the Post ID is not set as login welcome redirect for any level, for any scope
	   // so return false
	   return false;  
   }
   
   
   
   /**
   @ Function to check if the Page is restricted by any membership level
   @ $page_id is the Page ID to be checked
   @ It will return the highest membership level by which the Page is restricted
   */
   public function membpress_check_page_restricted_by_level($page_id)
   {
	   // make sure the $page_id is valid
	   if (!isset($page_id) || $page_id <= 0) return false;
	   
	   // first, get all membership levels
	   $mp_levels = $this->membpress_get_all_membership_levels();
	   
	   // flag to hold the highest membership level
	   $mp_restrict_by_level = -1;
	   
	   foreach ($mp_levels as $mp_level)
	   {
	       // get the list of pages restricted by the current membership level
		   $mp_restrict_pages_by_curr_level = (array)get_option('membpress_restrict_pages_level_' . $mp_level['level_no']);
		   // if the page ID is present in the list of restricted pages for current level, then store it
		   if (in_array($page_id, $mp_restrict_pages_by_curr_level))
		   {
			   $mp_restrict_by_level = $mp_level['level_no'];   
		   }
	   }
	   
	   // check if the page is restricted by any level
	   if ($mp_restrict_by_level >= 0)
	   {
		   return array('level_name' => $this->membpress_get_membership_level_name($mp_restrict_by_level), 'level_no' => $mp_restrict_by_level);   
	   }
	   
	   // the page is not restricted by any level
	   // return false
	   return false;
   }
   
   
   /**
   @ Function to check if the Post is restricted by any membership level
   @ $post_id is the Post ID to be checked
   @ It will return the highest membership level by which the Post is restricted
   */
   public function membpress_check_post_restricted_by_level($post_id)
   {
	   // make sure the $post_id is valid
	   if (!isset($post_id) || $post_id <= 0) return false;
	   
	   // first, get all membership levels
	   $mp_levels = $this->membpress_get_all_membership_levels();
	   
	   // flag to hold the highest membership level
	   $mp_restrict_by_level = -1;
	   
	   foreach ($mp_levels as $mp_level)
	   {
	       // get the list of posts restricted by the current membership level
		   $mp_restrict_posts_by_curr_level = (array)get_option('membpress_restrict_posts_level_' . $mp_level['level_no']);
		   // if the post ID is present in the list of restricted posts for current level, then store it
		   if (in_array($post_id, $mp_restrict_posts_by_curr_level))
		   {
			   $mp_restrict_by_level = $mp_level['level_no'];   
		   }
	   }
	   
	   // check if the post is restricted by any level
	   if ($mp_restrict_by_level >= 0)
	   {
		   return array('level_name' => $this->membpress_get_membership_level_name($mp_restrict_by_level), 'level_no' => $mp_restrict_by_level);   
	   }
	   
	   // the post is not restricted by any level
	   // return false
	   return false;
   }
   
   
   
   /**
   @ Function to check if the Category is restricted by any membership level
   @ $category_id is the Category ID to be checked
   @ It will return the highest membership level by which the Category is restricted
   */
   public function membpress_check_category_restricted_by_level($category_id)
   {
	   // make sure the $category_id is valid
	   if (!isset($category_id) || $category_id <= 0) return false;
	   
	   // first, get all membership levels
	   $mp_levels = $this->membpress_get_all_membership_levels();
	   
	   // flag to hold the highest membership level
	   $mp_restrict_by_level = -1;
	   
	   foreach ($mp_levels as $mp_level)
	   {
	       // get the list of categories restricted by the current membership level
		   $mp_restrict_categories_by_curr_level = (array)get_option('membpress_restrict_categories_level_' . $mp_level['level_no']);
		   // if the category ID is present in the list of restricted categories for current level, then store it
		   if (in_array($category_id, $mp_restrict_categories_by_curr_level))
		   {
			   $mp_restrict_by_level = $mp_level['level_no'];   
		   }
	   }
	   
	   // check if the category is restricted by any level
	   if ($mp_restrict_by_level >= 0)
	   {
		   return array('level_name' => $this->membpress_get_membership_level_name($mp_restrict_by_level), 'level_no' => $mp_restrict_by_level);   
	   }
	   
	   // the category is not restricted by any level
	   // return false
	   return false;
   }
   
   
   
   /**
   @ Function to check if the tag is restricted by any membership level
   @ $tag_id is the tag ID to be checked
   @ It will return the highest membership level by which the tag is restricted
   */
   public function membpress_check_tag_restricted_by_level($tag_id)
   {
	   // make sure the $tag_id is valid
	   if (!isset($tag_id) || $tag_id <= 0) return false;
	   
	   // first, get all membership levels
	   $mp_levels = $this->membpress_get_all_membership_levels();
	   
	   // flag to hold the highest membership level
	   $mp_restrict_by_level = -1;
	   
	   foreach ($mp_levels as $mp_level)
	   {
	       // get the list of tags restricted by the current membership level
		   $mp_restrict_tags_by_curr_level = (array)get_option('membpress_restrict_tags_level_' . $mp_level['level_no']);
		   
		   // if the tag ID is present in the list of restricted tags for current level, then store it
		   if (in_array($tag_id, $mp_restrict_tags_by_curr_level))
		   {
			   $mp_restrict_by_level = $mp_level['level_no'];   
		   }
	   }
	   
	   // check if the tag is restricted by any level
	   if ($mp_restrict_by_level >= 0)
	   {
		   return array('level_name' => $this->membpress_get_membership_level_name($mp_restrict_by_level), 'level_no' => $mp_restrict_by_level);   
	   }
	   
	   // the tag is not restricted by any level
	   // return false
	   return false;
   }
   
   
   
   /**
   Function to check for the sidebar widget if they are restricted by some
   membpress membership level
   
   @param $widget_sidebar_key is the key of the restricted widgets/sidebar options
   @param $widget_sidebar_val is the value of the restricted widgets/sidebar options
   @param $sidebars_widgets is the global array of widgets and sidebars
   
   @Returns the modified $sidebars_widgets variable
   */
   public function membpress_check_sidebar_widget_restricted_by_level($widget_sidebar_key, $widget_sidebar_val, $sidebars_widgets)
   {   
	   // check if the key is valid
	   if (!is_array($widget_sidebar_val) || count($widget_sidebar_val) < 4)
	      return $sidebars_widgets;
	   
	   // check if this is a widget or a sidebar
	   if ($widget_sidebar_val[1] === false)
	   {
		  // this is a widget
		  //echo $widget_sidebar_key . '<br>';
		  
		  // get the sidebar ID of this widget
		  $sidebar_id = $widget_sidebar_val[2];
		  
		  // get the widget ID
		  $widget_id = $widget_sidebar_val[3];
		  
		  //echo $sidebar_id . ' ' . $widget_id . '<br>';
		  
		  // unset its index in its sidebar in the global sidebar_widgets variable
		  // a sidebar can have many widgets, so iterate and unset this current widget
		  if (isset($sidebars_widgets[$sidebar_id]) && is_array($sidebars_widgets[$sidebar_id]) && trim($widget_sidebar_val[2]))
		  {
			  foreach ($sidebars_widgets[$sidebar_id] as $widget_key => $widget_val)
			  {
				 if ($widget_val == $widget_id)
				 {
					unset($sidebars_widgets[$sidebar_id][$widget_key]); 
				 }
			  }
		  }
	   }
	   else
	   {
		 // this is a sidebar
		 // get the sidebar ID
		 $sidebar_id = explode('_', $widget_sidebar_key);
		 unset($sidebar_id[0], $sidebar_id[1], $sidebar_id[2], $sidebar_id[3], $sidebar_id[4]);
		 $sidebar_id = implode("", (array)$sidebar_id[5]);

         // unset the whole sidebar
		 unset($sidebars_widgets[$sidebar_id]);  
	   }
	   
	   return $sidebars_widgets;
   }
   
   
   /**
   Function to restrict the sidebar widgets restricted by some level, for being
   displayed on wordpress web site
   */
   public function membpress_restrict_sidebar_widgets_on_site($sidebars_widgets)
   {
	    //var_dump($sidebars_widgets); exit;

		// do not hide/remove sidebar in admin
		// only do this for front-end
		if (!is_admin())
		{
			/*
			// check if user is logged in 
			if (is_user_logged_in())
			{
			   // check if the current user has the required membership level
			   // greater or equal to required category_highest_restricted_by_level
			   if (!$this->membpress_check_curr_user_level_meets($category_highest_restricted_by_level))
			   {
					// required level is not found, redirect to membership options page
					wp_redirect($mp_redirect_to);
					exit;   
			   }  
			}
			else
			{
				// user is not logged in, go directly to membership options page
				wp_redirect($mp_redirect_to);
				exit;   
			}
			*/
		   // get the array holding the widgets/sidebars restrictions
           $mp_restrict_sidebars_widgets_option = get_option('membpress_restrict_sidebars_widgets_option');
		   
		   //var_dump($mp_restrict_sidebars_widgets_option);
		   //membpress_get_current_user_membership_level()
		   
		   // iterate through all the widgets/sidebars restrictions
		   foreach ($mp_restrict_sidebars_widgets_option as $widget_sidebar_key => $widget_sidebar_val)
		   {
			   // check if the user is logged in
			   if (is_user_logged_in())
			   {
				   // user if logged in
				   // get current used membpress level
				   $curr_user_mp_level = $this->membpress_get_current_user_membership_level();
				   
				   // only restrict for membpress levels
				   if ($curr_user_mp_level > -1)
				   {
					  // get the membpress level for the restricted widget/sidebar
					  $mp_level_for_widget_sidebar = explode('_', $widget_sidebar_key);
					  $mp_level_for_widget_sidebar = $mp_level_for_widget_sidebar[3];
					  
					  // if the current user level is less than the level restricted for widget/sidebar
					  // then restrict the sidebar/widget
					  if ($curr_user_mp_level < $mp_level_for_widget_sidebar)
					  {
					      $sidebars_widgets = $this->membpress_check_sidebar_widget_restricted_by_level($widget_sidebar_key, $widget_sidebar_val, $sidebars_widgets);  
					  }
				   }
				   
			   }
			   else
			   {
				  // user is not logged in
				  // no need to check for restriction levels
				  // just hide the restricted sidebar or widget
				  $sidebars_widgets = $this->membpress_check_sidebar_widget_restricted_by_level($widget_sidebar_key, $widget_sidebar_val, $sidebars_widgets);   
			   }
		   }
		}

		
		return $sidebars_widgets;   
   }
   
   
   /**
   Function to customize the display of sidebar widgets restricted by some level
   in the WP admin
   */
   public function membpress_customize_restricted_widgets_admin()
   {
      // get the array holding the widgets/sidebars restrictions
      $mp_restrict_sidebars_widgets_option = get_option('membpress_restrict_sidebars_widgets_option');
	  
	  // get all membpress membership levels
	  $mp_all_membership_levels = $this->membpress_get_all_membership_levels();
	  
	  $script_to_output = '
	  <script type="text/javascript" src="'.plugins_url('membpress/resources/js/restrict_widgets_sidebars.js').'"></script>
	  <script type="text/javascript">jQuery(document).ready(function(){ ';
	  
	  // iterate through all options checking for sidebar/widgets restrictions
	  foreach ($mp_restrict_sidebars_widgets_option as $option_key => $option_val)
	  {  
		 foreach ($mp_all_membership_levels as $mp_level_name => $mp_level_val)
		 {
			 $sidebar_key = 'membpress_restrict_sidebar_' . $mp_level_val['level_no'];
			 // get the sidebar/widget ID part only from the key
			 $sidebar_key_id = explode($sidebar_key, $option_key);
			 $sidebar_key_id = $sidebar_key_id[1];
			 
			 // get the sidebar ID
			 $sidebar_id = trim($option_val[2]);
			 
			 if ($sidebar_id == '') continue;
             
			 // check if the widget/sidebar restriction is set
			 if ((bool)$option_val[0])
			 {
				
				// check if this is a sidebar
			    if ($option_val[1] == true)
			    {
			       //$script_to_output .= 	 
			    }
				// this is some widget in a sidebar
				else
				{						
				    // get the widget ID
				    $widget_id = explode($sidebar_id, $sidebar_key_id);
					$widget_id = trim($widget_id[1]);
					
					if ($widget_id != '')
					{
					  $script_to_output .= 'membpress_restrict_title_widget("'.$sidebar_id.'", "'.$widget_id.'", "'.$mp_level_val['display_name'].'");';
					}
				}
			 }
		 }
	  }
	  
	  $script_to_output .= '});</script>';
	  
	  echo $script_to_output;
   }
   
   
   
   /*
   @ Function to check the $query object for the query variables and return the post/page with their IDs
   @ The $query object returns different query object, based on whether the permalink is enabled or not
   @ $query is the query object passed for checking
   */
   public function membpress_query_object_check($query = false)
   {
      if (!$query) return false;
	  
	  // get the query object from main query
	  $q = $query->query;
	  
	  // check if permalink is enabled
	  if (trim(get_option('permalink_structure')) != '') 
	  {
		  // check if this is a page
		  if ($query->is_page)
		  {
			 // get the post ID using the slug
			 $page_id = query_posts('name='.$q['pagename'] . '&post_type=page');
			 $page_id = $page_id[0]; 
			 return array('page_id' => $page_id->ID);  
		  }
		  // not a page, may be post or custom post
		  elseif($query->is_single)
		  {
			 // get the post ID using the slug
			 $post_id = query_posts('name='.$q['name']);
			 $post_id = $post_id[0]; 
			 return array('post_id' => $post_id->ID);
		  }
		  // may be a category
		  elseif($query->is_category)
		  {
			 // get the category ID using the slug
			 $cat_id = get_category_by_slug($q['category_name']);
			 return array('cat_id' => $cat_id->term_id);    
		  }
		  elseif($query->is_tag)
		  {
			 // get the tag ID using the slug
			 $tag_id = get_term_by('slug', $q['tag'], 'post_tag');
			 return array('tag_id' => $tag_id->term_id);    
		  }
	  }
	  // if the permalink is not defined
	  else
	  {
		 if (isset($q['page_id'])) // if page ID is set
		 {
			return array('page_id' => $q['page_id']);
		 }
		 else if (isset($q['p'])) // if post ID is set
		 {
			return array('post_id' => $q['p']); 
		 }
		 else if (isset($q['cat'])) // if cat ID is set
		 {
			return array('cat_id' => $q['cat']); 
		 }
		 else if (isset($q['tag'])) // if tag ID is set
		 {
			 // get the tag ID using the slug
			 $tag_id = get_term_by('slug', $q['tag'], 'post_tag');
			 return array('tag_id' => $tag_id->term_id);
		 }
	  }
	  
	  return false;
   }
   
   
   
   
   /*
   @ Function used to redirect login welcome page/post if accessed with out the required member level
   @ The redirection will be towards Membership Options Page (if set). In case membership options page is not
   @ set, the redirection will be towards login page (if not logged in). If user is logged in but no membership
   @ options page is set, redirection will be towards home page or profile page.
   @ param ($query): is the query object for the current query
   */
   public function membpress_manage_login_welcome_access($query)
   {   
       // do not continue if this is in the admin area
	   if ($query->is_admin) return;
   
	   // see if it is the main query requested by the user
	   if ($query->is_main_query())
	   {
		  
		  $q = $this->membpress_query_object_check($query);
		  
		  // get the membpress login redirect settings vars
		  $mp_login_redirect_vars = $this->membpress_get_login_redirect_setting_vars();
		  // membpress membership options page set in the membpress settings
		  $mp_membership_option_page = get_option('membpress_settings_membership_option_page');
		  // get permalink of the page ID set in membpress options page section
		  $mp_membership_option_page_permalink = get_permalink($mp_membership_option_page);
		  
		  if ($mp_login_redirect_vars['login_redirect_scope'] == 'global')
		  {
		      $login_redirect_id = $mp_login_redirect_vars['login_redirect_id'];
			  // check if the user is logged in
			  if (is_user_logged_in())
			  {
				 // let the page/post display  
			  }
			  else // user is not logged in
			  {
			     // get the login redirect restriction
				 $login_redirect_restrict_flags = $mp_login_redirect_vars['login_redirect_restrict_flags'];
				 if ((bool)$login_redirect_restrict_flags)
				 {
					return; // return if there is no restriction 
				 }
				 // check the redirect type
				 if ($mp_login_redirect_vars['login_redirect_type'] == 'page') // global redirect type is page
				 {
					// check if current page ID matches global login redirect page ID
					if (isset($q['page_id']) && $q['page_id'] == $login_redirect_id)
					{
					   // check if membership options page permalink is valid
					   if ($mp_membership_option_page_permalink && $mp_membership_option_page_permalink != '')
					   {
						  // we need to check if the membership options page and the login redirect page are not same
						  // if same, then return
						  if ($mp_membership_option_page == $login_redirect_id)
						  {
							  return;  
						  }
						  wp_redirect($mp_membership_option_page_permalink); exit;
					   }
					} // end current page ID check with login redirect
				 }
				 else if ($mp_login_redirect_vars['login_redirect_type'] == 'post')
				 {
				    // check if current post ID matches global login redirect post ID
					if (isset($q['post_id']) && $q['post_id'] == $login_redirect_id)
					{
					   // check if membership options post permalink is valid
					   if ($mp_membership_option_page_permalink && $mp_membership_option_page_permalink != '')
					   {
						  wp_redirect($mp_membership_option_page_permalink); exit;
					   }
					} // end current post ID check with login redirect 
				 }
			  }
		  }
		  else if ($mp_login_redirect_vars['login_redirect_scope'] == 'individual') // login redirect is to individual membership levels
		  {  
			  // iterate through the array of membership levels
			  $login_redirect_types = $mp_login_redirect_vars['login_redirect_type'];
			  $login_redirect_ids = $mp_login_redirect_vars['login_redirect_id'];
			  $login_redirect_levels = $mp_login_redirect_vars['login_redirect_levels'];
			  $login_redirect_restrict_flags = $mp_login_redirect_vars['login_redirect_restrict_flags']; 
			  
			  for($i = 0; $i < count($login_redirect_types); $i++)
			  {  
				 // if login redirect is set to a page
				 if ($login_redirect_types[$i] == 'page') 
				 {
					// check if current page ID matches global login redirect page ID
					if (isset($q['page_id']) && $q['page_id'] == $login_redirect_ids[$i])
					{
					   // if there is no restriction for current page, return
					   if ((bool)$login_redirect_restrict_flags[$i])
					   {
						   return;   
					   }
					   
					   // check if membership options page permalink is valid
					   if ($mp_membership_option_page_permalink && $mp_membership_option_page_permalink != '')
					   {
						   // we need to check if the membership options page and the login redirect page are not same
						  // if same, then return
						  if ($mp_membership_option_page == $login_redirect_ids[$i])
						  {
							  return;  
						  }
						  
						  if (!is_user_logged_in()) // if user is not logged in, redirect without checking membership level
						  {
						     wp_redirect($mp_membership_option_page_permalink); exit;
						  }
						  else
						  {
							 // if user is logged in, we need to check membership level
							 // if the current membership level of user is higher or equal to the level required by the 
							 // current login welcome restricted page, then show it. Else, redirect user to the membship options page
							 $current_user = wp_get_current_user();
							 
							  if ($current_user instanceof WP_User && is_array($current_user->roles))
							  {
								  $roles = $current_user->roles;  //$roles is an array
								  $requred_role = $login_redirect_levels[$i]; // required membpress level
								  
								  // check if current user role is greater than or equal to the required membpress login redirect level
								  // a user can have different roles, so iterate
								  foreach($roles as $role)
								  {
									 // check if the membpress level is assigned to current user
									 if(stristr($role, 'membpress_level_') !== FALSE)
									 {
										 if ($requred_role == 'subscriber')
										 {
										     // if the required role is subscriber
											 // let the user see the page, since any membpress level
											 // is greater than subscriber (Free Level)
											 return;	 
										 }
										 else
										 {
											 // get only the level number(int) part
											 $requred_role = explode('membpress_level_', $requred_role);
											 $requred_role = $requred_role[1];
											 
											 
											 echo $requred_role; exit;
											 
											 $role = explode('membpress_level_', $role);
											 $role = $role[1];
											 
											 // current user membership level is less than the required
											 // membpress login redirect membership level
											 if ($role < $requred_role)
											 {
												 // redirect the user to membership options page and exit
												 wp_redirect($mp_membership_option_page_permalink); exit;	 
											 }
											 else
											 {
												// if user level is greater or equal than the required membership level
												// then let the user see this page 
											 }
										 }
									 }
									 else if ($role == 'subscriber') // if user is a subscriber
									 {
									    if ($requred_role == 'subscriber')
										{
										   // if required role is a subscriber, than let the user see this page	
										}
										else
										{
										    // redirect the user to membership options page and exit
											// required level is greater than the subscriber level
										    wp_redirect($mp_membership_option_page_permalink); exit;	
										}
									 }
								  }
							  }
						  }
					   }
					} // end current page ID check with login redirect 
				 }
				 else if ($login_redirect_types[$i] == 'post')
				 {
					// check if current post ID matches global login redirect post ID
					if (isset($q['post_id']) && $q['post_id'] == $login_redirect_ids[$i])
					{
					   // if there is no restriction for current post, return
					   if ((bool)$login_redirect_restrict_flags[$i])
					   {
						   return;   
					   }
					   
					   // check if membership options page permalink is valid
					   if ($mp_membership_option_page_permalink && $mp_membership_option_page_permalink != '')
					   {
						  if (!is_user_logged_in()) // if user is not logged in, redirect without checking membership level
						  {
						     wp_redirect($mp_membership_option_page_permalink); exit;
						  }
						  else
						  {
							 // if user is logged in, we need to check membership level
							 // if the current membership level of user is higher or equal to the level required by the 
							 // current login welcome restricted post, then show it. Else, redirect user to the membship options page
							 $current_user = wp_get_current_user();
							 
							  if ($current_user instanceof WP_User && is_array($current_user->roles))
							  {
								  $roles = $current_user->roles;  //$roles is an array
								  $requred_role = $login_redirect_levels[$i]; // required membpress level
								  
								  // check if current user role is greater than or equal to the required membpress login redirect level
								  // a user can have different roles, so iterate
								  foreach($roles as $role)
								  {
									 // check if the membpress level is assigned to current user
									 if(stristr($role, 'membpress_level_') !== FALSE)
									 {
										 if ($requred_role == 'subscriber')
										 {
										     // if the required role is subscriber
											 // let the user see the page, since any membpress level
											 // is greater than subscriber (Free Level)	 
										 }
										 else
										 {
											 // get only the level number(int) part
											 $requred_role = explode('membpress_level_', $requred_role);
											 $requred_role = $requred_role[1];
											 
											 $role = explode('membpress_level_', $role);
											 $role = $role[1];
											 
											 // current user membership level is less than the required
											 // membpress login redirect membership level
											 if ($role < $requred_role)
											 {
												 // redirect the user to membership options page and exit
												 wp_redirect($mp_membership_option_page_permalink); exit;	 
											 }
											 else
											 {
												// if user level is greater or equal than the required membership level
												// then let the user see this page 
											 }
										 }
									 }
									 else if ($role == 'subscriber') // if user is a subscriber
									 {
									    if ($requred_role == 'subscriber')
										{
										   // if required role is a subscriber, than let the user see this page	
										}
										else
										{
										    // redirect the user to membership options page and exit
											// required level is greater than the subscriber level
										    wp_redirect($mp_membership_option_page_permalink); exit;	
										}
									 }
								  }
							  }
						  }
					   }
					} // end current page ID check with login redirect  
				 }
			  }
		  }
       }   
   }
   
   
   /*
   Function to get the current URI
   */
   public function get_current_uri()
   {
	   $curr_url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	   
	   $base_url = str_replace('http://', '', home_url());
	   $base_url = str_replace('https://', '', $base_url);  
	   
	   $curr_uri = str_replace($base_url, '', $curr_url);
	   
	   return $curr_uri;  
   }
   
   /*
   Function to parse the regular expression enabled string
   The regex in the string is assumed to be in the following format
     This is a string containing {{ regex_expression }}
   The anything between {{ }} double curly braces is treated as regex
   
   $string_with_regex = the string containing the regex and other normal characters
   $check_string = the string to check against
   
   Example:
   $string_with_regex = "/membpress/?age={{ [0-9]+ }}&id={{ [0-9]+ }}&name={{ [a-z]+ }}";
   $check_string = "/membpress/?age=27&id=2332&name=waseem";
   
   The function will return the matched pattern if matched, else false
   */
   public function check_regex_string($string_with_regex, $check_string)
   {
	  preg_match_all ("^\{\{(.*?)\}\}^", $string_with_regex, $string_with_regex_arr);

	  $string_with_regex_arr = $string_with_regex_arr[1];
	  
	  $string_with_regex_rep = preg_quote(str_replace($string_with_regex_arr, '', $string_with_regex));
	  
	  $string_with_regex_rep = str_replace('/', '\/', $string_with_regex_rep);
	  
	  $string_with_regex_rep = explode('\{\{\}\}', $string_with_regex_rep);
	  
	  $regex_str = '';
	  
	  foreach($string_with_regex_rep as $key => $value)
	  {
		 $regex_str .= $value . trim((isset($string_with_regex_arr[$key]) ? $string_with_regex_arr[$key] : ''));	
	  }
	  
	  preg_match_all ("/$regex_str/", $check_string, $matched_arr);
	  
	  if (is_array($matched_arr) && count($matched_arr))
	  {
	      return $matched_arr[0][0];
	  }
	  
	  return false;
   }
   
   
   /**
   Function to restrict pages/post/categories/content etc by a membership level.
   Any post/page accessed without the required membership level
   will redirect the user to the membership options page
   */
   public function membpress_manage_restricted_access($query)
   { 
       // do not continue if this is in the admin area
	   if ($query->is_admin) return;
   
	   // see if it is the main query requested by the user
	   if ($query->is_main_query())
	   {  
		  // get all the membership levels
		  $mp_levels = $this->membpress_get_all_membership_levels();
		  // reverse the order of memberships from high to low
		  // this will ensure higher level memberships are checked first
		  // since higher membership levels have precedence over lower ones
		  $mp_levels = array_reverse($mp_levels);
		  
		  // membpress membership options page set in the membpress settings
		  $mp_membership_option_page = get_option('membpress_settings_membership_option_page');
		  // get permalink of the page ID set in membpress options page section
		  $mp_membership_option_page_permalink = get_permalink($mp_membership_option_page);
		  
		  // get the main query object
		  $q = $this->membpress_query_object_check($query);
		  
		  // we first need to check if the current url is restricted by
		  // by any membership level, as configured in Restrictions-> Restrict URIs
		  // we will then check for the other restrictions
		  // call the function to manage URIs restriction access
		  $this->membpress_manage_restricted_uri_access($this->get_current_uri(), $mp_levels, $mp_membership_option_page_permalink);
		  
		  // check if it is the front page
		  // the reason we are checking it after the check of restricted URI access is that
		  // we want the users to be able to restrict their frontpage too with URI pattern
		  // for example: www.example.com/?name=waseem
		  if (is_front_page()) return;
		  
		  // if it is a post, call the post restriction function
		  if (isset($q['post_id']) && $q['post_id'] > 0)
		  {
		     // call the function to manage post restriction access
			 $this->membpress_manage_restricted_post_access($q['post_id'], $mp_levels, $mp_membership_option_page_permalink);
		  }
		  else if (isset($q['page_id']) && $q['page_id'] > 0)
		  {
			 // call the function to manage page restriction access
			 $this->membpress_manage_restricted_page_access($q['page_id'], $mp_levels, $mp_membership_option_page_permalink);  
		  }
		  else if (isset($q['cat_id']) && $q['cat_id'] > 0)
		  {
			 // call the function to manage category restriction access
			 $this->membpress_manage_restricted_category_access($q['cat_id'], $mp_levels, $mp_membership_option_page_permalink);  
		  }
		  else if (isset($q['tag_id']) && $q['tag_id'] > 0)
		  {
			 // call the function to manage tag restriction access
			 $this->membpress_manage_restricted_tag_access($q['tag_id'], $mp_levels, $mp_membership_option_page_permalink);  
		  }
		  
	   }
   }
   
   
   
   /**
   Function to manage restriction of posts by membership levels
   $post_id is the post ID to be checked
   $mp_levels is an array containing all membership levels
   $mp_redirect_to is the permalink where the user will be redirected in case of unauthorized access
   
   This function will also look for posts restricted by some category, tag etc
   Anything to which a post is related to
   */
   
   public function membpress_manage_restricted_post_access($post_id, $mp_levels, $mp_redirect_to)
   {
	   /**
		Code to check if the post is restricted directly by any means
	   */
	   
	   // check if this post is set as login welcome for any/all membership level(s)
	   if ($this->membpress_check_post_login_redirect_exists($post_id))
	   {
		   // if yes, then do not continue however the restriction is applied by 
		   // Restriction Options -> Restrict Posts
		   return false;  
	   }
	   
	   // this post is not set as login welcome redirect for any level
	   // now see if it is restricted by any level
	   
	   // iterate through each level
	   foreach ($mp_levels as $mp_level)
	   {
		   // append post ID and membership level info to the redirection url
	       $mp_redirect_to = add_query_arg(array('mp_r_level' => $mp_level['level_no'], 'mp_post_id' => $post_id), $mp_redirect_to);
		   
		   // get the posts restricted by this level
		   $posts_restricted_curr_level = get_option('membpress_restrict_posts_level_' . $mp_level['level_no']);
		   
		   // check if current post is present in the restricted posts of the current level
		   if (in_array($post_id, $posts_restricted_curr_level))
		   {
			   // check if the user is logged in
			   if (is_user_logged_in())
			   {
				   // check if the current user has the required membership level
				   // greater or equal to required mp level number
				   if (!$this->membpress_check_curr_user_level_meets($mp_level['level_no']))
				   {
						// required level is not found, redirect to membership options page
						wp_redirect($mp_redirect_to);
						exit;   
				   }
			   }
			   else
			   // user is not logged in
			   {
				  // since the user is not logged in we only need to check if this post is restricted by current membership level
				  // this level is the highest level by which the post is restricted
				 if (in_array($post_id, $posts_restricted_curr_level))
				 {
					 // post is restricted by this level, go to membership options sign-up page
					 wp_redirect($mp_redirect_to);
					 exit;  
				 }
			   }
		   }
	   }
		
		/**
		Code to check if the post is restricted by any category
		*/
		
		// current post is not restricted by any level.
		// now check if the category of this post is restricted by any level
		$category_highest_restricted_by_level = $this->membpress_get_post_category_restricted_level($post_id);
		$category_highest_restricted_by_level = $category_highest_restricted_by_level['level_no'];
		
		// if there was any restricted category assigned to this post, we should have it now
		if ($category_highest_restricted_by_level > -1)
		{
			// check if user is logged in 
			if (is_user_logged_in())
			{
			   // check if the current user has the required membership level
			   // greater or equal to required category_highest_restricted_by_level
			   if (!$this->membpress_check_curr_user_level_meets($category_highest_restricted_by_level))
			   {
					// required level is not found, redirect to membership options page
					wp_redirect($mp_redirect_to);
					exit;   
			   }  
			}
			else
			{
				// user is not logged in, go directly to membership options page
				wp_redirect($mp_redirect_to);
				exit;   
			}
		}
		
		/**
		Code to check if the post is restricted by some tag(s)
		*/
		
		// post is not restricted directly, nor by any category
		// so check if it is restricted by any tag
	    $tag_highest_restricted_by_level = $this->membpress_get_post_tag_restricted_level($post_id);
		$tag_highest_restricted_by_level = $tag_highest_restricted_by_level['level_no'];
		
	    // if there was any restricted tag assigned to this post, we should have it now
		if ($tag_highest_restricted_by_level > -1)
		{
			// check if user is logged in 
			if (is_user_logged_in())
			{
			   // check if the current user has the required membership level
			   // greater or equal to required category_highest_restricted_by_level
			   if (!$this->membpress_check_curr_user_level_meets($tag_highest_restricted_by_level))
			   {
					// required level is not found, redirect to membership options page
					wp_redirect($mp_redirect_to);
					exit;   
			   }  
			}
			else
			{
				// user is not logged in, go directly to membership options page
				wp_redirect($mp_redirect_to);
				exit;   
			}
		}
		
		// this post is not restricted by any means
		// let it display publicly
		
   }
   
   /**
   @ Function to get the highest membership level of any category assigned to
   @ the post ID, defined by param $post_id
   @ It will return an array containing the level_no and the category ID
   */
   
   public function membpress_get_post_category_restricted_level($post_id)
   {
	  // get post categories
	  $post_category_ids = wp_get_post_categories($post_id);
	  // keep track of the highest level restriction
	  $category_highest_restricted_by_level = -1;
	  // keep track of the highest level category
	  $category_highest_restricted_by = -1;
	  // since a post can be assigned to multiple categories, we need to iterate through
	  // the categories of this post
	  foreach($post_category_ids as $post_category_id)
	  {
		  // check if this category is restricted by any level
		  $category_restricted_by_level = $this->membpress_check_category_restricted_by_level($post_category_id);
		  if ($category_restricted_by_level)
		  {
			  //this category is restricted by a level
			  // compare with previous high level, and assign if this is greater
			  if ($category_restricted_by_level['level_no'] > $category_highest_restricted_by_level)
			  {
				 $category_highest_restricted_by_level = $category_restricted_by_level['level_no'];
				 $category_highest_restricted_by = $post_category_id;
			  }    
		  }
		  else
		  {  
			  // this category is not restricted explicitly
			  // check if it is restricted implicitly by a parent
			  // there can be muliple parents (ancestors)
			  
			  $parent_category = $post_category_id;
			  while ($parent_category)
			  {
				 $parent_category = get_category($parent_category);
				 $parent_category = $parent_category->parent;
				
				 if (!$parent_category) break 1;
				 
				 $parent_restricted_by_level = $this->membpress_check_category_restricted_by_level($parent_category);
				 
				 if ($parent_restricted_by_level)
				 {
					 // this parent is restricted by a level
					 // so compare it to the highest recorded level and assign if greater
					if ($parent_restricted_by_level['level_no'] > $category_highest_restricted_by_level)
					{
						 $category_highest_restricted_by_level = $parent_restricted_by_level['level_no'];
						 $category_highest_restricted_by = $parent_category;
						 break 1;
					}  
				 }
			  }
		  }
	  }
	  
	  if ($category_highest_restricted_by_level < 0)
	  {
		   return false;  
	  }
	  
	  $category_name_highest_restricted_by = get_cat_name($category_highest_restricted_by);
	  
	  if (!$category_name_highest_restricted_by) return false;
	  
	  return array('level_no' => $category_highest_restricted_by_level, 'category_id' => $category_highest_restricted_by, 'category_name' => $category_name_highest_restricted_by);   
   }
   
   
   
   /**
   @ Function to get the highest membership level of any tag assigned to
   @ the post ID, defined by param $post_id
   @ It will return an array containing the level_no and the tag ID
   */
   
   public function membpress_get_post_tag_restricted_level($post_id)
   {
	  // get post tags
	  // See http://codex.wordpress.org/Function_Reference/wp_get_post_tags
	  // for more info the below function
	  $post_tags_ids = wp_get_post_tags($post_id);
	  // keep track of the highest level restriction
	  $tag_highest_restricted_by_level = -1;
	  // keep track of the highest level category
	  $tag_highest_restricted_by = -1;
	  // since a post can be assigned to multiple tags, we need to iterate through
	  // the tags of this post
	  foreach($post_tags_ids as $post_tag_id)
	  {
		  $post_tag_id = $post_tag_id->term_id;
		  
		  // check if this tag is restricted by any level
		  $tag_restricted_by_level = $this->membpress_check_tag_restricted_by_level($post_tag_id);
		  if ($tag_restricted_by_level)
		  {
			  // this tag is restricted by a level
			  // compare with previous high level, and assign if this is greater
			  if ($tag_restricted_by_level['level_no'] > $tag_highest_restricted_by_level)
			  {
				 $tag_highest_restricted_by_level = $tag_restricted_by_level['level_no'];
				 $tag_highest_restricted_by = $post_tag_id;
			  }    
		  }
	  }
	  
	  if ($tag_highest_restricted_by_level < 0)
	  {
		   return false;  
	  }
	  
	  $tag_name_highest_restricted_by = get_tag($tag_highest_restricted_by);
	  
	  if (!$tag_name_highest_restricted_by)
	  return false;
	  
	  $tag_name_highest_restricted_by = $tag_name_highest_restricted_by->name;
	  
	  return array('level_no' => $tag_highest_restricted_by_level, 'tag_id' => $tag_highest_restricted_by, 'tag_name' => $tag_name_highest_restricted_by);   
   }
   
   
   
   
   /**
   Function to manage restriction of pages by membership levels
   $page_id is the page ID to be checked
   $mp_levels is an array containing all membership levels
   $mp_redirect_to is the permalink where the user will be redirected in case of unauthorized access
   */
   
   public function membpress_manage_restricted_page_access($page_id, $mp_levels, $mp_redirect_to)
   {
	   // check if this page is set as the membership options page
	   if ($this->membpress_check_if_membership_options_page($page_id))
	   {
		   // page is set as membership options page
		   return false;   
	   }
	   
	   
	   // check if this page is set as login welcome for any/all membership level(s)
	   if ($this->membpress_check_page_login_redirect_exists($page_id))
	   {
		   // if yes, then do not continue however the restriction is applied by 
		   // Restriction Options -> Restrict Pages
		   return false;  
	   }
	   
	   
	   // this page is not set as login welcome redirect for any level
	   // now see if it is restricted by any level
	   
	   // iterate through each level
	   foreach ($mp_levels as $mp_level)
	   {
		   // append page ID and membership level info to the redirection url
	       $mp_redirect_to = add_query_arg(array('mp_r_level' => $mp_level['level_no'], 'mp_page_id' => $page_id), $mp_redirect_to);
		   
		   // get the pages restricted by this level
		   $pages_restricted_curr_level = get_option('membpress_restrict_pages_level_' . $mp_level['level_no']);
		   
		   // check if current page is present in the restricted pages of the current level
		   if (in_array($page_id, $pages_restricted_curr_level))
		   {
			   
			   // check if the user is logged in
			   if (is_user_logged_in())
			   {
				   // check if the current user has the required membership level
				   // greater or equal to required mp level number
				   if (!$this->membpress_check_curr_user_level_meets($mp_level['level_no']))
				   {
						// required level is not found, redirect to membership options page
						wp_redirect($mp_redirect_to);
						exit;   
				   }
			   }
			   else
			   // user is not logged in
			   {
				  // since the user is not logged in we only need to check if this page is restricted by current membership level
				  // this level is the highest level by which the page is restricted
				 if (in_array($page_id, $pages_restricted_curr_level))
				 {
					 // page is restricted by this level, go to membership options sign-up page
					 wp_redirect($mp_redirect_to);
					 exit;  
				 }
			   }
		   }
	   }
   }
   
   
   
   /**
   Function to manage restriction of category by membership levels
   $cat_id is the category ID to be checked
   $mp_levels is an array containing all membership levels
   $mp_redirect_to is the permalink where the user will be redirected in case of unauthorized access
   */
   
   public function membpress_manage_restricted_category_access($cat_id, $mp_levels, $mp_redirect_to)
   {      
	   // see if this category is restricted by any level
	   
	   // iterate through each level
	   foreach ($mp_levels as $mp_level)
	   {
		   // append category ID and membership level info to the redirection url
		   // this will be appended to the query on the membership options page and
		   // will help us keep track of what restricted category brought the user to that page
	       $mp_redirect_to = add_query_arg(array('mp_r_level' => $mp_level['level_no'], 'mp_cat_id' => $cat_id), $mp_redirect_to);
		   
		   // get the categories restricted by this level
		   $cats_restricted_curr_level = get_option('membpress_restrict_categories_level_' . $mp_level['level_no']);
		   
		   // check if current category is present in the restricted categories of the current level
		   if (in_array($cat_id, $cats_restricted_curr_level))
		   {
			   
			   // check if the user is logged in
			   if (is_user_logged_in())
			   {
				   // check if the current user has the required membership level
				   // greater or equal to required mp level number
				   if (!$this->membpress_check_curr_user_level_meets($mp_level['level_no']))
				   {
						// required level is not found, redirect to membership options page
						wp_redirect($mp_redirect_to);
						exit;   
				   }
			   }
			   else
			   // user is not logged in
			   {
				  // since the user is not logged in we only need to check if this category
				  // is restricted by current membership level
				  // this level is the highest level by which the category is restricted
				 if (in_array($cat_id, $cats_restricted_curr_level))
				 {
					 // category is restricted by this level, go to membership options sign-up page
					 wp_redirect($mp_redirect_to);
					 exit;  
				 }
			   }
		   }
	   }
   }
   
   
   
   /**
   Function to manage restriction of tag by membership levels
   $tag_id is the tag ID to be checked
   $mp_levels is an array containing all membership levels
   $mp_redirect_to is the permalink where the user will be redirected in case of unauthorized access
   */
   
   public function membpress_manage_restricted_tag_access($tag_id, $mp_levels, $mp_redirect_to)
   {      
	   // see if this tag is restricted by any level
	   
	   // iterate through each level
	   foreach ($mp_levels as $mp_level)
	   {
		   // append tag ID and membership level info to the redirection url
		   // this will be appended to the query on the membership options page and
		   // will help us keep track of what restricted tag brought the user to that page
	       $mp_redirect_to = add_query_arg(array('mp_r_level' => $mp_level['level_no'], 'mp_tag_id' => $tag_id), $mp_redirect_to);
		   
		   // get the tags restricted by this level
		   $tags_restricted_curr_level = get_option('membpress_restrict_tags_level_' . $mp_level['level_no']);
		   
		   // check if current tag is present in the restricted tags of the current level
		   if (in_array($tag_id, $tags_restricted_curr_level))
		   {
			   
			   // check if the user is logged in
			   if (is_user_logged_in())
			   {
				   // check if the current user has the required membership level
				   // greater or equal to required mp level number
				   if (!$this->membpress_check_curr_user_level_meets($mp_level['level_no']))
				   {
						// required level is not found, redirect to membership options page
						wp_redirect($mp_redirect_to);
						exit;   
				   }
			   }
			   else
			   // user is not logged in
			   {
				  // since the user is not logged in we only need to check if this tag
				  // is restricted by current membership level
				  // this level is the highest level by which the tag is restricted
				 if (in_array($tag_id, $tags_restricted_curr_level))
				 {
					 // tag is restricted by this level, go to membership options sign-up page
					 wp_redirect($mp_redirect_to);
					 exit;  
				 }
			   }
		   }
	   }
   }
   
   
   /**
   Function to check if a string is present in an array of regex enabled strings
   */
   public function in_array_regex($string_to_check, $regex_strings, $strict_match = true)
   {
	   foreach ((array)$regex_strings as $regex_string)
	   {
		   $regex_string = rtrim($regex_string, "/");
		   $regex_string = ltrim($regex_string, "/");
		   $regex_string = '/' . $regex_string;
		   
		   $regex_string1 = $regex_string;
		   
		   $regex_string2 = $regex_string . '/';
		   
		   if ($strict_match)
		   {
			 // append '$' at the end of the URI so for patterns like /members/
			 // only the /members/ URI is restricted and not /members/sub_dirs
			 $regex_string1 = '{{^}}' . $regex_string1 . '{{$}}';
			 $regex_string2 = '{{^}}' . $regex_string2 . '{{$}}';
		   }
		   
		   if ($return_str = $this->check_regex_string($regex_string1, $string_to_check)) 
		   {
			   return $return_str;	  
		   }
		   // if the match is not found, try once again by adding a trailing slash
		   elseif ($return_str = $this->check_regex_string($regex_string2, $string_to_check))
		   {
			   return $return_str;     
		   }
	   } 
	   
	   return false;  
   }
   
   
   
   /**
   Function to manage restriction of URIs by membership levels
   $curr_uri is the current URI which is to be checked against the restricted URIs
   $mp_levels is an array containing all membership levels
   $mp_redirect_to is the permalink where the user will be redirected in case of unauthorized access
   */
   
   public function membpress_manage_restricted_uri_access($curr_uri, $mp_levels, $mp_redirect_to)
   {      	  
	   // see if this URI is restricted by any level
	   
	   // iterate through each level
	   foreach ($mp_levels as $mp_level)
	   {
		   // append curr URI and membership level info to the redirection url
		   // this will be appended to the query on the membership options page and
		   // will help us keep track of what restricted URI brought the user to that page
	       $mp_redirect_to = add_query_arg(array('mp_r_level' => $mp_level['level_no'], 'mp_uri' => urlencode($curr_uri)), $mp_redirect_to);
		   
		   // get the uris restricted by this level
		   $uris_restricted_curr_level = get_option('membpress_restrict_uris_level_' . $mp_level['level_no']);
		   
		   // check if current uri is present in the restricted uris of the current level
		   if ($this->in_array_regex($curr_uri, $uris_restricted_curr_level))
		   {
			   // check if the user is logged in
			   if (is_user_logged_in())
			   {
				   // check if the current user has the required membership level
				   // greater or equal to required mp level number
				   if (!$this->membpress_check_curr_user_level_meets($mp_level['level_no']))
				   {
						// required level is not found, redirect to membership options page
						wp_redirect($mp_redirect_to);
						exit;   
				   }
			   }
			   else
			   // user is not logged in
			   {
				  // since the user is not logged in we only need to check if this URI
				  // is restricted by current membership level
				  // this level is the highest level by which the URI is restricted
				 if ($this->in_array_regex($curr_uri, $uris_restricted_curr_level))
				 {
					 // URI is restricted by this level, go to membership options sign-up page
					 wp_redirect($mp_redirect_to);
					 exit;  
				 }
			   }
		   }
	   }
   }
   
   
   
   /**
   Function to get current user role
   */
   public function membpress_get_current_user_roles()
   {
      // get current user object
	  $current_user = wp_get_current_user();
	 
	  // check if the current user object an instance of WP_User class
	  if ($current_user instanceof WP_User && is_array($current_user->roles))
	  {
		  // return user roles object
		  return $current_user->roles;
	  }
	  
	  return false;
   }
   
   /**
   Function to get current user highest membership level
   @ Return: integer value of the membpress membership level
   -1 indicates that the current user has no role
   -2 indicates that the current user has some non membpress level like administrator etc
   All other values indicate membpress levels
   */
   public function membpress_get_current_user_membership_level()
   {
      // get current user object
	  $current_user = wp_get_current_user();
	  
	  // array of current user mp levels
	  $cur_user_mp_levels = array();
	 
	  // check if the current user object an instance of WP_User class
	  if ($current_user instanceof WP_User && is_array($current_user->roles))
	  {
		  // if there is no role assigned, it is a guest user
		  if (count($current_user->roles) <= 0) return -1;
		  
		  foreach($current_user->roles as $role)
		  {
		     if ($level = $this->membpress_extract_level_no($role))
			 {
				$cur_user_mp_levels[] = $level; 
			 }
		  }
		  
		  if (count($cur_user_mp_levels) > 0) return max($cur_user_mp_levels );
	  }
	  
	  // user has some non membpress level
	  return -2;
   }
   
   
   /**
   Function to check if the user meets the required membership level
   */
   public function membpress_check_curr_user_level_meets($required_level_no = 0)
   {
	  
	  $roles = $this->membpress_get_current_user_roles();
	  
	  if (!$roles) return false;  
	  
	  foreach ($roles as $role)
	  {
		 // if the current user has role something like membpress_level_2
		 if(stristr($role, 'membpress_level_') !== FALSE) 
		 {
		     // get the number of this membership level
			 $curr_user_level_no = explode("_", $role);
			 $curr_user_level_no = $curr_user_level_no[count($curr_user_level_no) - 1]; 
			 
			 // check if the current membership level is greater than or equal to required level number
			 if ($curr_user_level_no >= $required_level_no)
			 {
				return true; // return true 
			 }
			 else
			 {
				// user does not have the required level 
				return false; 
			 }
		 }
		 // check if the user is a free member => subscriber
		 else if ($role == 'subscriber')
		 {
			 // check if the require level is 0
			 if ($required_level_no <= 0)
			 {
			    return true;
			 }
		 }
		 else
		 {
		    // the user is not a subscriber, nor a membpress member.
			// this means the user is administrator, editor etc
			// return true so that the user meets the level
			return true; 
		 }
	  }
	  
	  return false;
   }
   
   
   
   /*
   @ Function to update a role display name
   */
   public function membpress_update_role_display_name($wp_roles, $role_name, $role_display_name)
   {   
	   $membpress_wp_roles = $wp_roles->role_names;
	   
	   // check if the role is not defined, then add it
	   if (!isset($wp_roles->roles[$role_name]))
	   {
		   add_role( $role_name, $role_display_name, array( 'read' => true, 'delete_posts' => false, 'edit_posts'   => false) );
		   // exit from function since, the role is already updated to the required display name
		   return;     
	   }
	   
		$wp_roles->roles[$role_name] = array(
		   'name' => $role_display_name,
		   'capabilities' => $wp_roles->roles[$role_name]['capabilities']
	    );
		
		$wp_roles->role_names[$role_name] = $role_display_name;
		update_option($wp_roles->role_key, $wp_roles->roles);   
   }
   
   /**
   @Function to check if the post/page is set as login welcome redirect, either globally or individual
   @ Param ($ID) is the ID of the post/page to be checked
   @ Param ($type) is the type, either post or page to be checked
   
   The function return the 'scope' and 'level' of the post/page assigned as login welcome redirect, if any
   Sample returns: array('scope' => 'global', 'level' => 'all'), array('scope' => 'individual', 'level' => 'membpress_level_1')
   If, not found, returns false
   */
   public function membpress_check_if_login_welcome_redirect($ID, $type = 'post')
   {
	   // check if types are set correctly, else return
	   if ($type != 'post' && $type != 'page')
	   {
		   return false;   
	   }
	   
	   // get the settings for login welcome redirect
	   $redirect_vars = $this->membpress_get_login_redirect_setting_vars();
	   
	   // if the login redirect is set globally
	   if ($redirect_vars['login_redirect_scope'] == 'global')
	   {
		   // if the passed ID and type matches the one set globally for login welcome redirect
		   if ($redirect_vars['login_redirect_type'] == $type && $redirect_vars['login_redirect_id'] == $ID)
		   {
			   return array('scope' => 'global', 'level' => 'all');   
		   }
	   }
	   // if login welcome redirect is set individually
	   else
	   {
		   // get login welcome redirect IDs, types, levels
		   $login_redirect_ids = $redirect_vars['login_redirect_id'];
		   $login_redirect_types = $redirect_vars['login_redirect_type'];
		   $login_redirect_levels = $redirect_vars['login_redirect_levels'];
		   
		   // iterate through the IDs set
		   for($i = 0; $i < count($login_redirect_ids); $i++)
		   {
		      // if the current level ID and type matches, return them
			  if ($login_redirect_types[$i] == $type && $login_redirect_ids[$i] == $ID)
			  {
				  return array('scope' => 'individual', 'level' => $login_redirect_levels[$i]);  
			  }
		   }
	   }
	   
	   return false;     
   }
   
   
   
   /**
   @ This function will determine if the page ID passed as parameter, is set as
    the membership options page or not.
   @ Param ($page_id) is the page ID passed as parameter to be checked
   
   It returns boolean true if found else returns false
   */
   public function membpress_check_if_membership_options_page($page_id = 0)
   {
	   // make sure $page_id is valid
	   if ($page_id <= 0) return false;
	   
	   // get membership options page ID
	   $mp_membership_option_page = get_option('membpress_settings_membership_option_page');
	   
	   // check if membership options page matches the passed page ID
	   if ($mp_membership_option_page > 0 && $mp_membership_option_page == $page_id)
	   {
		   return true;   
	   }
	   
	   // does not match, return false
	   return false;
   }
   
   
   /*
   @ Function to show different update notices based on the section updated
   */
   public function membpress_show_update_notice($notice_id = 1, $notice_type = 'success', $notice_vars = '')
   {
	   // proceed only if updated variable is set
	   if (isset($_GET['updated']) && (bool)$_GET['updated'])
	   {
	      if ($notice_vars != '')
		  {
			 $notice_vars = explode(',', $notice_vars);  
		  }
		  $this->membpress_show_update_notice_text($notice_id, $notice_type, $notice_vars); 
	   } 
   }
   
   /*
   @ Function which will show the text for the update notice
   @ param ($notice_id) will be used to determine the notice text
   @ param ($notice_type) will be used to see if it is success or error or any other
   */
   public function membpress_show_update_notice_text($notice_id = 1, $notice_type = 'success', $notice_vars = array())
   {
	   
	   if ($notice_type == 'success')
	   {
	      echo '<div class="updated below-h2">';
	   }
	   else if ($notice_type == 'error')
	   {
		   echo '<div class="error below-h2">';  
	   }
	   echo '<p>';
	   echo _x($this->membpress_get_notices($notice_id, $notice_vars), 'general', 'membpress');
	   echo '<a class="membpress_updated_close" href="javascript:;">';
	   echo _x('Close', 'general', 'membpress');
	   echo '</a></p>';
	   echo '</div>';   
   }
   
   
   
   
   /**
   Function to exit script and show a message when a restricted script is access directly from browser
   */
   public function membpress_permission_denied_notice()
   {
	   header('Status: 403 Forbidden');
	   header('HTTP/1.1 403 Forbidden');
	   echo '<h2>' . _x('You do not have permission to view this page / perform this action.', 'general', 'membpress') . '</h2>';
	   exit();	   
   }
   
   
   
   
   /*
   @ Function get the core membpress membership levels, as well as the free subscriber level
   */
   public function membpress_get_core_membership_levels()
   {
	   	 // define an array containg the roles, to be returned
		 $mp_roles = array();
		 
		 global $wp_roles; // global varaible holding wp roles object
  
		 if ( ! isset( $wp_roles ) )
		 {
			 $wp_roles = new WP_Roles();
		 }
		 
		 // include the subscriber level and the 4 core membpress level
		 $mp_roles['subscriber'] = array('display_name' => $wp_roles->role_names['subscriber'], 'level_no' => 0);
		 $mp_roles['membpress_level_1'] = array('display_name' => $wp_roles->role_names['membpress_level_1'], 'level_no' => 1);
		 $mp_roles['membpress_level_2'] = array('display_name' => $wp_roles->role_names['membpress_level_2'], 'level_no' => 2);
		 $mp_roles['membpress_level_3'] = array('display_name' => $wp_roles->role_names['membpress_level_3'], 'level_no' => 3);
		 $mp_roles['membpress_level_4'] = array('display_name' => $wp_roles->role_names['membpress_level_4'], 'level_no' => 4);
		 
		 return $mp_roles;   
   }
   
   /*
   @ Function to get all the membership levels, including the subscriber level, the core levels
   @ and the user defined membpress level
   */
   public function membpress_get_all_membership_levels()
   {
	   /*
	   // Old code retained here just for reference/comparison purposes
	   // get the core levels
	   $mp_roles = $this->membpress_get_core_membership_levels();
	   
	   // start from 5, so if MEMBPRESS_LEVEL_COUNT is set to 4 or less than that,
	   // it won't continue
	   for($i = 5; $i <= MEMBPRESS_LEVEL_COUNT; $i++)
	   {
	      // check if the membership level name is empty, if yes
		  // then check if the config file has the level name defined
		  // if no then put 'Membership level #' as value
		  
		  if (trim(get_option('membpress_membership_name_level_'.$i)) == '')
		  {
			  if (@defined('MEMBPRESS_LEVEL_'.$i) && @trim(constant(MEMBPRESS_LEVEL_.$i)) != '')
			  {
				  $membership_level_name = @trim(constant(MEMBPRESS_LEVEL_.$i));   
			  }
			  else
			  {
				 $membership_level_name = _x('Membership Level', 'general', 'membpress'); 
			  }
		  }
		  else
		  {
		      $membership_level_name = trim(get_option('membpress_membership_name_level_'.$i));	  
		  }
		  
		  $mp_roles['membpress_level_'.$i] = array('display_name' => $membership_level_name, 'level_no' => $i);
	   }
	   
	   return $mp_roles;
	   */
	   
	   // first get the membpress_levels array 
	   // membership levels array should be defined by now
	   $membpress_levels =  (array)get_option('membpress_levels');
	   
	   // iterate through the array and return the values in the required format
	   $mp_roles = array();
	   foreach($membpress_levels as $membpress_level_key => $membpress_level_val)
	   {
		   $level_no = explode('_', $membpress_level_key);
		   $level_no = $level_no[count($level_no) - 1];
		   
		   $mp_roles[$membpress_level_key] = array('display_name' => $membpress_level_val['display_name'], 'level_no' => $level_no);     
	   }

       usort($mp_roles, array($this, 'sort_by_level_nos'));
	   
	   // return the roles
	   return $mp_roles;
   }
   
   // sort the mp_roles array by the level_no of the membership
   // starting from 0 onwards
   private function sort_by_level_nos($a, $b)
   {
	  return $a['level_no'] - $b['level_no'];
   }
   
   /**
   @ Function to get the name of the membership level no
   @ mp_level_no is the level no passed
   */
   public function membpress_get_membership_level_name($mp_level_no)
   {
	   // make sure level no is valid
	   if ($mp_level_no < 0) return '';
	   
	   // get all membership levels
	   $mp_levels = $this->membpress_get_all_membership_levels();
	   
	   // itereate through all levels and return the matched level name
	   foreach ($mp_levels as $mp_level)
	   {
		   if ($mp_level['level_no'] == $mp_level_no)
		   {
			   return $mp_level['display_name'];   
		   }
	   }
	   
	   return '';
   }
   
   /**
   @ Function to extract level number from a membership level like membpress_level_4
   @ mp_level_no is the level number is the above format
   */
   public function membpress_extract_level_no($mp_level_no)
   {
	   $mp_level_no = strtolower($mp_level_no);
	   
	   // if level is a subscriber, it is 0 level
	   if ($mp_level_no == 'subscriber') return 0;
	      
	   $mp_level_no = explode("membpress_level_", $mp_level_no);
	   $mp_level_no = $mp_level_no[1];
	   return $mp_level_no;     
   }
   
   
   /**
   @ This function returns the subscription rates for a membership level
   @ $mp_level_no is the input param for which the subscription rates will be returned
   */
   public function membpress_get_subscription_rates_by_level_no($mp_level_no)
   {
	   // get membership levels array
	   $membpress_levels =  (array)get_option('membpress_levels'); 
	   
	   if (@is_array($membpress_levels['membpress_level_'.$mp_level_no]['subscription_rates']) && count($membpress_levels['membpress_level_'.$mp_level_no]['subscription_rates']))
	   {
	      return $membpress_levels['membpress_level_'.$mp_level_no]['subscription_rates'];
	   }
	   
	   return false;
   }
   
   /**
   @ This function returns the subscription rate name for a given membership level and subscription rate index
   @ $mp_level_no is the input membership level
   @ $mp_subs_rate_index is the index of the subscription rate array
   */
   public function membpress_get_subscription_rate_name($mp_level_no, $mp_subs_rate_index)
   {
	   // get membership levels array
	   $membpress_levels =  (array)get_option('membpress_levels'); 
	   
	   if (@is_array($membpress_levels['membpress_level_'.$mp_level_no]['subscription_rates']) && count($membpress_levels['membpress_level_'.$mp_level_no]['subscription_rates']))
	   {
	       if (@is_array($membpress_levels['membpress_level_'.$mp_level_no]['subscription_rates'][$mp_subs_rate_index]))
	       {
			   return $membpress_levels['membpress_level_'.$mp_level_no]['subscription_rates'][$mp_subs_rate_index]['subscription_name'];   
		   }
	   }
	   
	   return false;
   }
   
   
   /**
   @ The function maps the subscription rate settings like type, duration, charge to their strings
   */
   public function membpress_get_subscription_rates_string($setting_string)
   {
	   $setting_strings = array
	   (
	      'recurring' => _x('Recurring', 'general', 'membpress'),
		  'one_time' => _x('One Time', 'general', 'membpress'),
		  'life_time' => _x('Life Time', 'general', 'membpress'),
		  'hour' => _x('Hour(s)', 'general', 'membpress'),
		  'day' => _x('Day(s)', 'general', 'membpress'),
		  'week' => _x('Week(s)', 'general', 'membpress'),
		  'month' => _x('Month(s)', 'general', 'membpress'),
		  'year' => _x('Year(s)', 'general', 'membpress')
	   );
	   
	   if (isset($setting_strings[$setting_string]))
	   {
		   return $setting_strings[$setting_string];   
	   }
	   
	   return $setting_string;
   }
   
   /**
   MembPress own .htaccess mod_rewrite function to add rules
   */
   public function membpress_add_rewrite_rule($rule_string)
   {   
	   if (!got_mod_rewrite ()) return false;
	   
	   // htaccess path
	   $htaccess = ABSPATH . '.htaccess';
	   
	   // open for reading only
	   $f = fopen($htaccess, 'r'); 
	   
	   $rules = fread($f, filesize($htaccess));
	   
	   $mp_start = '#MembPress Begin';
	   $mp_end = '#MembPress End';
	   
	   $if_module_start = "<IfModule mod_rewrite.c>";
	   $if_module_end = "</IfModule>";
	   
	   $rewrite_engine_on = "RewriteEngine On";
	   $rewrite_base = "RewriteBase " . $this->get_rel_root_path();
	   
	   // get current membpress rules, if any
	   $curr_mp_rules_org = trim($this->get_string_between($rules, $mp_start, $mp_end));
	   
	   $curr_mp_rules = trim($this->get_string_between($curr_mp_rules_org, $if_module_start, $if_module_end));
	   
	   $updated_mp_rules = trim($curr_mp_rules . "\n" . $rule_string);
	   
	   $updated_mp_rules = trim(str_replace(array($rewrite_engine_on, $rewrite_base), '', $updated_mp_rules));
	   
	   $updated_mp_rules = $mp_start . "\n" . $if_module_start . "\n" . $rewrite_engine_on . "\n" . $rewrite_base . "\n" . $updated_mp_rules . "\n" . $if_module_end . "\n" . $mp_end;
	   
	   $updated_mp_rules = $this->replace_string_between($rules, $mp_start, $mp_end, $updated_mp_rules);
	  
	   // close reading
	   fclose($f);
	   
	   // open for write
	   $f = fopen($htaccess, 'w'); 
	   
	   // write the updated rules to .htaccess
	   fwrite($f, $updated_mp_rules);
	   fclose($f);
   }
   
   /**
   Function to get content between two strings
   */
   public function get_string_between($string, $start, $end)
   {
      $string = " ".$string;
      $ini = strpos($string,$start);
	  
	  // if no match found, return empty
      if ($ini == 0) return "";
	  
      $ini += strlen($start);
      $len = strpos($string,$end,$ini) - $ini;
	  
	  // return the string found
      return substr($string,$ini,$len);
    }
	
	/**
	Function to replace content between two strings
	*/
	public function replace_string_between($string, $start, $end, $replace)
	{
       return preg_replace('#('.preg_quote($start, '#').')(.*)('.preg_quote($end, '#').')#si', $replace, $string); 
	}
	
	/**
	Function to get the relative root path of WP
	*/
	public function get_rel_root_path()
	{
	   return str_replace($_SERVER['DOCUMENT_ROOT'], '', get_home_path());	
	}
   
};
?>