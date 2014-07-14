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
	   
	   add_role( 'membpress_level_1', MEMBPRESS_LEVEL_1, array( 'read' => true, 'delete_posts' => false, 'edit_posts'   => false) );
	   add_role( 'membpress_level_2', MEMBPRESS_LEVEL_2, array( 'read' => true, 'delete_posts' => false, 'edit_posts'   => false) );
	   add_role( 'membpress_level_4', MEMBPRESS_LEVEL_4, array( 'read' => true, 'delete_posts' => false, 'edit_posts'   => false) );
	   add_role( 'membpress_level_3', MEMBPRESS_LEVEL_3, array( 'read' => true, 'delete_posts' => false, 'edit_posts'   => false) );

	   
	   // get the WP roles object
	   global $wp_roles;
	   if ( ! isset( $wp_roles ) )
	   {
		  $wp_roles = new WP_Roles();
	   }
	   
	   // rename the subscriber level to display name set in the membpress.config.php file
	   $this->membpress_update_role_display_name($wp_roles, 'subscriber', MEMBPRESS_LEVEL_0);
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
   
   
   
   /*
   @ Function to check the $query object for the query variables and return the post/page with their IDs
   @ The $query object returns different query object, based on whether the permalink is enabled or not
   @ $query is the query object passed for checking
   */
   public function membpress_query_object_check($query = false)
   {
      if (!$query) return;
	  
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
		  // not a post, may be post or custom post
		  else
		  {
			 // get the post ID using the slug
			 $post_id = query_posts('name='.$q['name']);
			 $post_id = $post_id[0]; 
			 return array('p' => $post_id->ID);
		  }
	  }
	  // if the permalink is not defined
	  else
	  {
		 if (isset($q['page_id'])) // if page ID is set
		 {
			return array('page_id' => $q['page_id']);
		 }
		 else if (isset($q['p']))
		 {
			return array('p' => $q['p']); // else if post ID is set 
		 }
	  }
	  
	  return;
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
					if (isset($q['p']) && $q['p'] == $login_redirect_id)
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
					// check if current page ID matches global login redirect page ID
					if (isset($q['p']) && $q['p'] == $login_redirect_ids[$i])
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
   
   
   /*
   @ Function get all the membpress membership levels, as well as the free subscriber level
   */
   public function membpress_get_membership_levels()
   {
	   	 // define an array containg the roles, to be returned
		 $mp_roles = array();
		 
		 global $wp_roles; // global varaible holding wp roles object
  
		 if ( ! isset( $wp_roles ) )
		 {
			 $wp_roles = new WP_Roles();
		 }
		 
		 // include the subscriber level and the 4 core membpress level
		 $mp_roles['subscriber'] = $wp_roles->role_names['subscriber'];
		 $mp_roles['membpress_level_1'] = $wp_roles->role_names['membpress_level_1'];
		 $mp_roles['membpress_level_2'] = $wp_roles->role_names['membpress_level_2'];
		 $mp_roles['membpress_level_3'] = $wp_roles->role_names['membpress_level_3'];
		 $mp_roles['membpress_level_4'] = $wp_roles->role_names['membpress_level_4'];
		 
		 return $mp_roles;   
   }
};
?>