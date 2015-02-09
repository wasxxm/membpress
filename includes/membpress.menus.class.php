<?php

// include the membpress helper class
include_once 'membpress.helper.class.php';

// include the membpress helper class
include_once 'membpress.shortcodes.class.php';

class Membpress_Menus extends Membpress_Helper
{
	// instance of membpress shortcodes class
	public $mp_shortcodes;
	
	// constructor
	public function Membpress_Menus()
	{
		$this->mp_shortcodes = new MembPress_ShortCodes();
	}
	
	/*
	@ Function hooked to the admin_menu action
	*/
	public function membpress_action_admin_menu()
	{
	   // we need to make sure that the current user has the
	   // capability to update options
	   if (current_user_can('manage_options'))
	   {
		   // call membpress register menu page function
		   $this->register_membpress_menu_page();
		   // call membpress register sub-menu pages
		   $this->register_membpress_submenu_pages();
		   // call membpress menu manage function
		   // for rearranging, renaming menus etc
		   $this->membpress_admin_menu_manage();
	   }
	}
	
	
    /*
	@ Function hooked to the admin_bar_menu action
	*/
	public function membpress_action_admin_bar_menu($wp_admin_bar)
	{
	   // we need to make sure that the current user has the
	   // capability to update options
	   if (current_user_can('manage_options'))
	   {
		   // call membpress register admin menu bar function
		   $this->register_membpress_admin_bar_links($wp_admin_bar);
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
		 3.1251221851919 // 1251221851919 = membpress
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
	   
	   // add membpress subscription rates page menu
	   $hook2 = add_submenu_page(
		 'membpress_page_quick_start',
		 _x('Membpress Subscription Rates', 'general', 'membpress'),
		 _x('Subscription Rates', 'general', 'membpress'),
		 'manage_options',
		 'membpress_subscription_rates_page',
		 array($this, 'membpress_subscription_rates_page')
	   ); 
	   
	   // add membpress restriction options page menu
	   $hook3 = add_submenu_page(
		 'membpress_page_quick_start',
		 _x('Membpress Restriction Options', 'general', 'membpress'),
		 _x('Restriction Options', 'general', 'membpress'),
		 'manage_options',
		 'membpress_restrict_options_page',
		 array($this, 'membpress_restrict_options_page')
	   );
	}
	
	
	/*
	Contains all admin menu bar links
	*/
	public function register_membpress_admin_bar_links($wp_admin_bar)
	{
		$args = array(
			  'id'    => 'membpress_admin_bar',
			  'title' => _x('MembPress', 'general', 'membpress'),
			  'href'  => admin_url('admin.php?page=membpress_page_quick_start'),
			  'meta'  => array( 'class' => 'menupop' )
		  );
		  $wp_admin_bar->add_node( $args );
		  
		  $args = array(
			  'id'    => 'membpress_admin_bar_link_1',
			  'title' => _x('Quick Start', 'general', 'membpress'),
			  'href'  => admin_url('admin.php?page=membpress_page_quick_start'),
			  'parent'=> 'membpress_admin_bar'
		  );
		  $wp_admin_bar->add_node( $args );
		  
		  $args = array(
			  'id'    => 'membpress_admin_bar_link_2',
			  'title' => _x('Basic Setup', 'general', 'membpress'),
			  'href'  => admin_url('admin.php?page=membpress_setup_page'),
			  'parent'=> 'membpress_admin_bar'
		  );
		  $wp_admin_bar->add_node( $args );
		  
		  $args = array(
			  'id'    => 'membpress_admin_bar_link_3',
			  'title' => _x('Subscription Rates', 'general', 'membpress'),
			  'href'  => admin_url('admin.php?page=membpress_subscription_rates_page'),
			  'parent'=> 'membpress_admin_bar'
		  );
		  $wp_admin_bar->add_node( $args );
		  
		  $args = array(
			  'id'    => 'membpress_admin_bar_link_4',
			  'title' => _x('Restriction Options', 'general', 'membpress'),
			  'href'  => admin_url('admin.php?page=membpress_restrict_options_page'),
			  'parent'=> 'membpress_admin_bar'
		  );
		  $wp_admin_bar->add_node( $args );	
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
	@ Template for the membpress restriction options page
	*/
	public function membpress_restrict_options_page()
	{
	   // include the template file for membpress restriction options page
	   include_once 'templates/membpress.members_restriction_options.html.php';	
	}
	
	/*
	@ Template for the membpress subscription rates page
	*/
	public function membpress_subscription_rates_page()
	{
	   // include the template file for membpress subscription rates page
	   include_once 'templates/membpress.members_subscription_rates.html.php';	
	}
	
	
	/*
	@ Function membpress_admin_menu_manage() is used to manage the membpress
	@ admin menu links. It will re-order and rename the links as needed
	*/
	public function membpress_admin_menu_manage()
	{
	   global $submenu;
	   
	   $submenu['membpress_page_quick_start'][0][0] = 'Quick Start';
	   
	   foreach($submenu['membpress_page_quick_start'] as $key => $value)
	   {
		  if ($key == 1)
		  {
		     $submenu['membpress_page_quick_start'][1] = 
			 array (
				0 =>  '<span class="membpress-admin-menu-sep"></span>',
				1 =>  'manage_options',
				2 =>  '',
				3 =>  ''
				);	    
			 }
			 
			 $submenu['membpress_page_quick_start'][$key + 1] = $value;
	   }
	}  	
};