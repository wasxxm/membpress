<?php
/**
* This is the main membpress class. It contains methods for the core
* functions of the membpress plugin

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

// include the membpress helper class
include_once 'membpress.helper.class.php';

class MembPress_Main
{
	// instance of membpress helper class
	public $mp_helper;
	
	/*
	@Contructor function loads basic hooks
	*/
	public function MembPress_Main()
	{
	   // add admin menu hook
	   add_action('admin_menu', array($this, 'membpress_action_admin_menu'));
	   // add init hook
	   add_action('init', array($this, 'membpress_action_init'));
	   // add admin notices hook
	   add_action('admin_notices', array($this, 'membpress_admin_notice'));
	   // add the hook for adding metaboxes
	   add_action('add_meta_boxes', array($this, 'membpress_add_meta_box'));
	   
	   // hook for plugin activation
	   register_activation_hook(
	      // root directory relative to the dir of current file
		  str_replace('includes', '', dirname(__FILE__)) . 'membpress.php', 
	      array($this, 'membpress_register_activation_hook')
	   );
	   
	   // pre get post gook
	   // will be used to filter the main query
	   add_action( 'pre_get_posts', array($this, 'membpress_pre_get_posts') );
	   
	   // add action on save post 
	   add_action( 'save_post', array($this, 'membpress_save_meta_box'));
	   
	   // initialize membpress helper class object
	   $this->mp_helper = new Membpress_Helper();
	}
	
	/*
	@ Function hooked to the init action
	*/
	public function membpress_action_init()
	{
	   // set the text domain for the translation files
	   load_plugin_textdomain('membpress', false, basename( dirname( __FILE__ ) ) . '/languages' );
	   
	   // call membpress stylesheets addition hook
	   $this->membpress_register_plugin_styles();
	   // call membpress scripts addition hook
	   $this->membpress_register_plugin_scripts();
	   
	   // call the membpress login page welcome routine
	   $this->mp_helper->membpress_login_welcome();	
	}
	
	
	/*
	@ Function called when the plugin is activated
	*/
	public function membpress_register_activation_hook()
	{
	   // add the default membpress roles
	   $this->mp_helper->membpress_add_default_roles();	
	}
	
	/*
	@ Function hooked to the admin_menu action
	*/
	public function membpress_action_admin_menu()
	{
	   // call membpress register menu page function
	   $this->register_membpress_menu_page();
	   // call membpress register sub-menu pages
	   $this->register_membpress_submenu_pages();
	   // call membpress menu manage function
	   // for rearranging, renaming menus ect
	   $this->membpress_admin_menu_manage();	
	}
	
	/*
	@ Function hooked to the admin notices function
	* Can be used to generate WP Admin notices
	*/
	public function membpress_admin_notice()
	{
		global $pagenow; // global page file name like admin.php
		
		if (isset($_GET['page']))
		{
		   $page = $_GET['page']; // current page passed as ?page=page_name
		}
		
		// generate admin notices if required.
	}
	
	/*
	@ Function hooked to the add_meta_boxes hook
	*/
	public function membpress_add_meta_box()
	{
	   // add a metabox to a post/page being used a login welcome page
	   // on right side above Publish section
	   
	   // MembPress Side High metabox for the post
	   add_meta_box( 
		  'membpress_login_welcome_metabox',
		  __( 'MembPress', 'membpress' ),
		  array($this, 'membpress_metabox_callback'),
		  'post',
		  'side',
		  'high' // set it high on the top side
	   );
	   
	   // MembPress Side High metabox for the page
	   add_meta_box(
		  'membpress_login_welcome_metabox',
		  __( 'MembPress', 'membpress' ),
		  array($this, 'membpress_metabox_callback'),
		  'page',
		  'side',
		  'high' // set it high on the top
	   );	
	}
	
	
	/*
	@ Function called as hook for the save_post
	@ $post_id is the current post ID
	*/
	public function membpress_save_meta_box($post_id)
	{
	    // call the function to save metabox restirct box
		// this will work for both post and page
		$this->membpress_save_restrict_metabox($post_id);   	
	}
	
	
	/*
	@ Function used to save the restrict option coming from the metabox
	@ in post and page.
	*/
	function membpress_save_restrict_metabox($post_id)
	{
		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */
	
		// Check if our nonce is set.
		if ( ! isset( $_POST['membpress_meta_box_nonce'] ) )
		{
			return;
		}
	
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['membpress_meta_box_nonce'], 'membpress_meta_box' ) )
		{
			return;
		}
	
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		{
			return;
		}
	
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] )
		{
	
			if ( ! current_user_can( 'edit_page', $post_id ) )
			{
				return;
			}
	
		}
		else
		{
	
			if ( ! current_user_can( 'edit_post', $post_id ) )
			{
				return;
			}
		}
	
		/* OK, it's safe for us to save the data now. */
		
		// see if the restrict option was for post
		if (isset($_POST['membpress_restrict_post_level']))
		{
		   /**
		   Only continue with the restrict option, if the post is not set as
		   login welcome redirect. There is no point of checking for membership options page, since post
		   cannot be set as membership options page
		   */
		   // check if the current post is set as login welcome redirect
		   $mp_login_welcome_check = $this->mp_helper->membpress_check_if_login_welcome_redirect($post_id, 'post');
		   if ($mp_login_welcome_check)// this means, the current post is indeed set as login welcome redirect
		   {
			   // return the function without continuing
			   return;     
		   }
		   
		   /**
		   Update the list of posts in the membpress restrict_posts_level_{level_no} array option
		   It can be configured also at: Membpress -> Restriction Options -> Retrict Posts
		   */
		   
		   // clear any previous assignment of the current post to any membership level
		   // get the current post resitricted level, this is before saving the new one
		   $mp_post_prev_level = get_post_meta($post_id, 'membpress_post_restricted_by_level', true);
		   // get the number the above level
		   $mp_post_prev_level = explode('_', $mp_post_prev_level);
		   $mp_post_prev_level = $mp_post_prev_level[count($mp_post_prev_level) - 1];
		   
		   if ($mp_post_prev_level == 'subscriber') $mp_post_prev_level = 0;
		   
		   // get the mempress restrict posts level option
		   $mp_restrict_posts_by_curr_level = (array)get_option('membpress_restrict_posts_level_' . $mp_post_prev_level);
		   // remove the current post from this level
		   // there can be many post IDs, so iterate
		   foreach($mp_restrict_posts_by_curr_level as $mp_restrict_post_by_curr_level_key => $mp_restrict_post_by_curr_level)
		   {
			   if ($mp_restrict_post_by_curr_level == $post_id)
			   {
				   unset($mp_restrict_posts_by_curr_level[$mp_restrict_post_by_curr_level_key]);   
			   }
		   }

		   update_option('membpress_restrict_posts_level_' . $mp_post_prev_level, $mp_restrict_posts_by_curr_level);
		   	
		   /*
		   Now the current value of post is removed from the restrict posts level, we can continue further update
		   */
		   
		   // get the post level number only currently submitted
		   $mp_level_no = explode('_', $_POST['membpress_restrict_post_level']);
		   $mp_level_no = $mp_level_no[count($mp_level_no) - 1];
		   
		   if ($mp_level_no == 'subscriber') $mp_level_no = 0;
		   
		   // new level for this post
		   $mp_restrict_posts_by_new_level = (array)get_option('membpress_restrict_posts_level_' . $mp_level_no);
		 
		   // check if the value of restrict level is  empty
		   if (trim($_POST['membpress_restrict_post_level']) == '')
		   {
			   update_post_meta($post_id, 'membpress_post_restricted_by_level', '');   
		   }
		   else // means the membership value is set, restriction is applied
		   {
			   update_post_meta($post_id, 'membpress_post_restricted_by_level', $_POST['membpress_restrict_post_level']);
			   array_push($mp_restrict_posts_by_new_level, $post_id); 
		   }
		   
		   // make the array unqiue to remove any duplicate values
		   $mp_restrict_posts_by_new_level = array_unique($mp_restrict_posts_by_new_level);
		   
		   // remove empty IDs
		   foreach($mp_restrict_posts_by_new_level as $mp_restrict_post_by_new_level_key => $mp_restrict_post_by_new_level)
		   {
			  if (trim($mp_restrict_post_by_new_level) == '')
			  {
				  unset($mp_restrict_posts_by_new_level[$mp_restrict_post_by_new_level_key]);  
			  }
		   }
		  
		   // update the restrict posts option
		   update_option('membpress_restrict_posts_level_' . $mp_level_no, $mp_restrict_posts_by_new_level);
		}
		// else check if it is set for page
		else if(isset($_POST['membpress_restrict_page_level']))
		{
		   /**
		   Only continue with the restrict option, if the page is not set as
		   login welcome redirect. Also check for membership options page
		   */
		   // check if the current page is set as login welcome redirect
		   if ($this->mp_helper->membpress_check_if_login_welcome_redirect($post_id, 'page'))
		   // this means, the current page is indeed set as login welcome redirect
		   {
			   // return the function without continuing
			   return;     
		   }
		   // check if current page is set as membership options page
		   if ($this->mp_helper->membpress_check_if_membership_options_page($post_id))
		   {
			   // page is set as membership options page, do not continue
			   return;   
		   }
		   
		   // check if the value of restrict level is  empty
		   if (trim($_POST['membpress_restrict_page_level']) == '')
		   {
			   update_post_meta($post_id, 'membpress_page_restricted_by_level', '');    
		   }
		   else // means the membership value is set, restriction is applied
		   {
			   update_post_meta($post_id, 'membpress_page_restricted_by_level', $_POST['membpress_restrict_page_level']);      
		   }	
		}
    }
	
	
    /*
	@   function to register stylesheets
	*/
	public function membpress_register_plugin_styles()
	{
		// register and enqueue main membpress stylesheet
		wp_register_style( 'membpress-style-sheet', plugins_url( 'membpress/resources/css/style.css' ) );
		wp_enqueue_style( 'membpress-style-sheet' );	
	}
	
	/*
	@   function to register javascript files
	*/
	public function membpress_register_plugin_scripts()
	{
		// include these scripts only in admin
		if (is_admin())
		{
		   // register and enqueue main javascript file
		   wp_register_script( 'membpress-script', plugins_url( 'membpress/resources/js/main.js' ) );
		   wp_enqueue_script( 'membpress-script' );
		
		   // register and enqueue the jquery cookie script
		   wp_register_script( 'membpress-script-cookie', plugins_url( 'membpress/resources/js/jquery.cookie.js' ) );
		   wp_enqueue_script( 'membpress-script-cookie' );
		}
	}
	
	/*
	@  Function called as hook to the pre get posts
	@  Place code to modify the main query
	@  param ($query): is the query object
	*/
	public function membpress_pre_get_posts($query)
	{
	    $this->mp_helper->membpress_manage_login_welcome_access($query);	
	}
	
	
	
	/**
	@ Function used as a callback for post/page right side high metabox
	*/
	public function membpress_metabox_callback($post)
	{
	   	// Add a nonce field so we can check for it later.
	    wp_nonce_field( 'membpress_meta_box', 'membpress_meta_box_nonce' );
	   
	   // call the function to add the login welcome redirect box, if applicable
	   // if the function returns false, it means the current post/page is not assigned as login welcome redirect
	   
	   if (!$this->membpress_metabox_login_welcome_redirect($post))
	   {
		   // check if the current page (if any) is not set as the membpress options page
		   if (!$this->membpress_metabox_membership_options_page($post))
		   {
		      // the current post/page is not set as login redirect, nor as the membpress membership options page
			  $this->membpress_metabox_restrict_options($post);
		   }
	   }
	}
	
	
	/*
	@ The function called as a callback for membpress login welcome metabox
	* This callback will print the HTML for the post/page being set as the
	* login welcome redirect in MembPress Basic Setup
	* It returns true if the current post/page is assigned to some membership level(s) else false
	*/
	public function membpress_metabox_login_welcome_redirect($post)
	{
		// this flag will determine if the current post/page is assigned as a welcome login redirect for
		// either global or local membership levels or not
		$membpress_welcome_login_set = false;
		
		// we need to check if the redirect is set globally or individually on each membership levels
		$mp_settings_welcome_login_individual = (bool)get_option('membpress_settings_welcome_login_individual');
		
		if ($mp_settings_welcome_login_individual) // individually set for each membership level
		{
		   // we need to get all the membpress membership levels
		   // and map the page/post to the membership level set
		   global $wp_roles; // global varaible holding wp roles object

		   if (! isset( $wp_roles ))
		   {
			   $wp_roles = new WP_Roles(); // initiate an object of WP_Roles, if not set already
		   }
		   
		   $membpress_roles_names = $wp_roles->role_names; // get all the role names
		   
		   // these are the flags to control the output of multiple posts/pages
		   // assigned to different membership levels
		   $mp_individual_post_flag = array();
		   $mp_individual_page_flag = array();
		   
		   // iterate through all the membpress membership levels and the subscription level 0
		   foreach ($membpress_roles_names as $mp_role_key => $mp_role_val)
		   {
			   // check if the role is defined by MembPress like membpress_level_1
			   // include the subscriber role as the Membpress Level 0 /Free Level
			   if (stristr($mp_role_key, 'membpress_level_') !== FALSE || $mp_role_key == 'subscriber')
			   {
				  // get the current membpress level redirect type
				  $mp_login_type = get_option('membpress_settings_welcome_login_type_'.$mp_role_key);
				  // if redirect is set to a post and the current post ID(if post) matches the login redirect
				  // post assigned in the membpress setup
				  if ($mp_login_type == 'post' && get_option('membpress_settings_welcome_login_post_'.$mp_role_key) == $post->ID)
				  {
					  // set the login welcome redirect show flag
					  $membpress_welcome_login_set = true;
					  
					  // print the html for the post being assigned as login redirect for current level
					  // see if login heading has been printed for this page by checking the flag array
					  if (!in_array($post->ID, $mp_individual_post_flag))
					  {
					     echo '<p><strong>' . _x('Login Welcome Post', 'membpress_login_redirect', 'membpress') . '</strong> <small>';
						 echo _x('for membership:', 'membpress_login_redirect', 'membpress');
						 echo '</small></p>';
					  }
					  
					  // rename the value if set to subscriber
					  if ($mp_role_key == 'subscriber')
					     $mp_role_val = _x('Subscriber (MembPress Level 0 - Free)', 'general','membpress');
					  
					  echo'<p><em>' . $mp_role_val . '</em>';
					  echo '</p>';
					  
					  // push the page id and membership name to the flag array
					  $mp_individual_post_flag[] = $post->ID;
				  }
				  
				  // if redirect is set to a page and the current page ID(if page) matches the login redirect
				  // page assigned in the membpress setup
				  if ($mp_login_type == 'page' && get_option('membpress_settings_welcome_login_page_'.$mp_role_key) == $post->ID)
				  {
					  // set the login welcome redirect show flag
					  $membpress_welcome_login_set = true;
					  
					  // print the html for the page being assigned as login redirect for current level
					  // see if login heading has been printed for this page by checking the flag array
					  if (!in_array($post->ID, $mp_individual_page_flag))
					  {
					     echo '<p><strong>' . _x('Login Welcome Page', 'membpress_login_redirect', 'membpress') . '</strong> <small>';
						 echo _x('for membership:', 'membpress_login_redirect', 'membpress');
						 echo '</small></p>';
					  }
					  
					  // rename the value if set to subscriber
					  if ($mp_role_key == 'subscriber')
					     $mp_role_val = _x('Subscriber (MembPress Level 0 - Free)', 'general','membpress');
						 
					  echo'<p><em>' . $mp_role_val . '</em>';
					  echo '</p>';
					  
					  // push the page id and membership name to the flag array
					  $mp_individual_page_flag[] = $post->ID;
				  }
			   }
		   } // endforeach
		}
		else // means the login redirect is set globally
		{
		  $membpress_settings_welcome_login_type = get_option('membpress_settings_welcome_login_type');
		  if ($membpress_settings_welcome_login_type == 'post' && get_option('membpress_settings_welcome_login_post') == $post->ID)
		  {
			  // set the login welcome redirect show flag
			  $membpress_welcome_login_set = true;
			  
			  // print the html for the post being assigned as login redirect
			  echo '<p>';
			  printf( 
				  _x('This post is set as the global %s', 'membpress_login_redirect', 'membpress'), 
				  '<br><strong>' . _x('Login Welcome Post', 'membpress_login_redirect', 'membpress') . '</strong>'
			  );
			  echo '</p>';
		  }
		  
		  if ($membpress_settings_welcome_login_type == 'page' && get_option('membpress_settings_welcome_login_page') == $post->ID)
		  {
			  // set the login welcome redirect show flag
			  $membpress_welcome_login_set = true;
			 
			  // print the html for the page being assigned as login redirect
			  echo '<p>';
			  printf( 
				  _x('This page is set as the global %s', 'membpress_login_redirect', 'membpress'), 
				  '<br><strong>' . _x('Login Welcome Page', 'membpress_login_redirect', 'membpress') . '</strong>'
			  );
			  echo '</p>';
		  }
		}
		
		// only print the rest of the html if the login redirect show flag is set
		if ($membpress_welcome_login_set)
		{
		  echo '<p><small>';
		  echo _x('This setting can be changed in', 'membpress_login_redirect', 'membpress');
		  echo ' <em>';
		  echo _x('MembPress -> Basic Setup -> ', 'membpress_login_redirect', 'membpress');
		  echo '<a href="'.admin_url('admin.php?page=membpress_setup_page#section=#membpress_settings_welcome_page_login').'" target="_blank">';
		  echo _x('Welcome Page after Login', 'membpress_login_redirect', 'membpress');
		  echo '</a>';
		  echo '</em>';
		  echo '</small></p>';
		  
		  // return true
		  return true;
		}
		
		// else return false
		return false;
	}
	
	
	/*
	@ This function is used as a callback for displaying the membership options
	@ metabox on a page assigned as the membership options page
	@ It will return true, if this is a page and is set as the membership options page, else false
	*/
	public function membpress_metabox_membership_options_page($post)
	{
	   // only continue if this is a page
	   if ($post->post_type == 'page')
	   {
		   // get membership options page ID set in MembPress Basic Setup
		   $mp_options_page_id = get_option('membpress_settings_membership_option_page');
		   
		   // if current page ID matches mp options page ID
		   if ($mp_options_page_id > 0 && $mp_options_page_id == $post->ID)
		   {
			   echo '<p>';
			   echo _x('This page is set as the <br><strong>Membership Options Page</strong>', 'membpress_membership_options_page', 'membpress');
			   echo '</p>';
			   echo '<p>';
			   echo '<small>';
			   echo _x('You can change this setting in', 'membpress_membership_options_page', 'membpress');
			   echo ' <em>';
			   echo _x('MembPress -> Basic Setup ->', 'membpress_membership_options_page', 'membpress');
			   echo ' <a href="'.admin_url('admin.php?page=membpress_setup_page#section=#membpress_settings_membership_options_page').'" target="_blank">';
			   echo _x('Membership Options Page', 'membpress_membership_options_page', 'membpress');
			   echo '</a>';
			   echo '</em>';
			   echo '</small>';
			   echo '</p>';
			   return true;   
		   }
		   else
		   {
			   // current page is not set as membership options page
			   return false;   
		   }
	   }
	   else
	   {
		   // this is not a page, return false
		   return false;   
	   }
	}
	
	
	/**
	@ Function which will show the restriction options for current page/post in high sidebar
	*/
	public function membpress_metabox_restrict_options($post)
	{
	   // if the current edit screen is for page
	   if ($post->post_type == 'page')
	   {
		   echo '<p>';
		   echo '<strong>';
		   echo _x('Restrict this Page:', 'general', 'membpress');
		   echo '</strong><br>';
		   echo _x('Please select the required Membership Level to access this Page, in case you want to restrict it from public viewing.', 'general', 'membpress');
		   echo '</p>';	
		   
		   // get all the membpress membership levels
		   $mp_get_membership_levels = $this->mp_helper->membpress_get_all_membership_levels();
		   
		   // get the current post restriction
		   $mp_page_restricted_by_level = get_post_meta($post->ID, 'membpress_page_restricted_by_level', true);
		   
		   echo '<p>';
		   echo '<select name="membpress_restrict_page_level">';
		   echo '<option value="">-- ' . _x('No Restriction', 'general', 'membpress') . ' --</option>';
		   // list all the levels with the assigned as selected
		   foreach($mp_get_membership_levels as $mp_get_membership_level_key => $mp_get_membership_level_val)
		   {
			   $selected = '';
			   // check if this level is the same level to which the post is assigned restricted
			   if ($mp_page_restricted_by_level == $mp_get_membership_level_key)
			   {
				   $selected = 'selected';  // select the current level  
			   }
			   echo '<option '.$selected.' value="'.$mp_get_membership_level_key.'">' . $mp_get_membership_level_val['display_name'] . '</option>';      
		   }
		   echo '</select></p>';          
	   }
	   // if the edit screen is not page, means it is the standard post or anyother post
	   else
	   {
		   echo '<p>';
		   echo '<strong>';
		   echo _x('Restrict this post:', 'general', 'membpress');
		   echo '</strong><br>';
		   echo _x('Please select the required Membership Level to access this post, in case you want to restrict it from public viewing.', 'general', 'membpress');
		   echo '</p>';	
		   
		   // get all the membpress membership levels
		   $mp_get_membership_levels = $this->mp_helper->membpress_get_all_membership_levels();
		   
		   // get the current post restriction
		   $mp_post_restricted_by_level = get_post_meta($post->ID, 'membpress_post_restricted_by_level', true);
		   
		   echo '<p>';
		   echo '<select name="membpress_restrict_post_level">';
		   echo '<option value="">-- ' . _x('No Restriction', 'general', 'membpress') . ' --</option>';
		   // list all the levels with the assigned as selected
		   foreach($mp_get_membership_levels as $mp_get_membership_level_key => $mp_get_membership_level_val)
		   {
			   $selected = '';
			   // check if this level is the same level to which the post is assigned restricted
			   if ($mp_post_restricted_by_level == $mp_get_membership_level_key)
			   {
				   $selected = 'selected';  // select the current level  
			   }
			   echo '<option '.$selected.' value="'.$mp_get_membership_level_key.'">' . $mp_get_membership_level_val['display_name'] . '</option>';      
		   }
		   echo '</select></p>';
	   }
	}
	
	
	
	
	
	/*
	@ Function for registering the membpress menu links in WP Admin
	*/
    public function register_membpress_menu_page()
	{
       // add main membpress admin link
	   $hook = add_menu_page(
		 'MembPress Quick-Start Guide',
		 'MembPress',
		 'manage_options',
		 'membpress_page_quick_start',
		 array($this, 'membpress_page_quick_start'),
		 plugins_url( 'membpress/resources/images/icon.png' ),
		 99.1251221851919 // 1251221851919 = membpress
	   );
    }
    
	/*
    @ loads the quick start membpress page
	*/
    public function membpress_page_quick_start()
	{
	   // load the quick-start page template file
       include_once 'templates/membpress.quick_start.html.php';	
    }
	
	/*
	@ Contains all the sub-menu pages hooks
	*/
    public function register_membpress_submenu_pages()
	{  
	   // add membpress setup page menu
	   $hook = add_submenu_page(
		 'membpress_page_quick_start',
		 _x('Membpress Basic Setup/Settings', 'general', 'membpress'),
		 _x('Basic Setup', 'general', 'membpress'),
		 'manage_options',
		 'membpress_setup_page',
		 array($this, 'membpress_setup_page')
	   );
	   
	   // add membpress restriction options page menu
	   $hook2 = add_submenu_page(
		 'membpress_page_quick_start',
		 _x('Membpress Restriction Options', 'general', 'membpress'),
		 _x('Restriction Options', 'general', 'membpress'),
		 'manage_options',
		 'membpress_restrict_options_page',
		 array($this, 'membpress_restrict_options_page')
	   ); 
	}
	
	
	/*
	@ Template for the membpress setup/settings page
	*/
	public function membpress_setup_page()
	{
	   // include the template file for membpress setup page
	   include_once 'templates/membpress.members_setup.html.php';	
	}
	
	/*
	@ Template for the membpress restrictio options page
	*/
	public function membpress_restrict_options_page()
	{
	   // include the template file for membpress restriction options page
	   include_once 'templates/membpress.members_restriction_options.html.php';	
	}
	
	
	/*
	@ Function membpress_admin_menu_manage() is used to manage the membpress
	@ admin menu links. It will re-order and rename the links as needed
	*/
	public function membpress_admin_menu_manage()
	{
	   global $submenu;
	   
	   $submenu['membpress_page_quick_start'][0][0] = 'Quick Start';
	}
};

?>