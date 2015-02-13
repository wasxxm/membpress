<?php

/**
class: Membpress_LoginPage
This class customizes the default login page of the wordpress
*/

// include the membpress helper class
include_once 'membpress.helper.class.php';

class Membpress_LoginPage extends Membpress_Helper
{
	/**
	Function called from the action init
	*/
	public function membpress_customize_login_page_init()
	{
	   	   // let users access wp-login.php also via /login/
	   // check if the apprpriate flag is checked
	   // but first check if a change is pending or not
	   if ((bool)get_option('membpress_settings_customize_login_rewrite_pending_flag'))
	   {
		   if ((bool)get_option('membpress_settings_customize_login_rewrite_flag'))
		   {		  
			   $this->membpress_login_rewrite();
		   }
		   // if not set then flush rewrite rules, to revert the changes
		   else
		   {
			   $this->membpress_login_rewrite_undo();
		   }
		   // clear the pending flag
		   update_option('membpress_settings_customize_login_rewrite_pending_flag', 0);
	   }
	   
	   // check if the forgot password link on the login page is checked
	   if ((bool)get_option('membpress_settings_customize_login_hide_passforgot'))
	   {
		   $this->hide_forgot_pass_link();   
	   }
	   
	   // check if the forgot password link on the login page is checked
	   if ((bool)get_option('membpress_settings_customize_login_hide_bloglink'))
	   {
		   $this->hide_back_blog_link();   
	   }	
	}
	
	/**
	Functions to customize the login page
	*/
	public function membpress_customize_login_page()
	{
	    // change wordpress login logo
		add_action('login_head', array($this, 'membpress_login_page_logo'));
		
		// change wordpress login URL
		add_filter( 'login_headerurl', array($this, 'membpress_login_page_logo_url'));
		
		// change wordpress login URL Title
		add_filter( 'login_headertitle', array($this, 'membpress_login_page_logo_title')); 
		
		// let the users authenticate using email address too
	    add_action( 'wp_authenticate', array($this, 'membpress_allow_email_login'));
	}
	
	public function membpress_login_page_logo() {
       echo '<style type="text/css">
        .login h1 a { 
		   background-image:url('. plugins_url('membpress/resources/images/login_logo.png') .') !important; 
		   background-size: 380px auto !important; 
		   width: 380px !important; 
		}
		#login {
          width: 420px !important;
		  padding:6% 0 0 !important;
       }
	   .wp-core-ui .button-primary {
		   background-color: #045580 !important; 
		   border-color: #045580 !important;
		   height: 35px !important;
           line-height: 32px !important;
           padding: 0 23px 2px !important;
		   font-size: 1.1em !important;  
	   }
	   
	   .wp-core-ui .button-primary:hover {
		   background-color: #096a9e !important; 
		   border-color: #096a9e !important;
	   }
	   
	   html, body {
		   background-color: #FFF !important;   
	   }
	   
	   .login form {
		   background-color: #f5f8fb !important;
		   box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
	   }
	   
	   .login .message, #login_error {
		  background-color: #f5f8fb !important;
		  margin-bottom:5px;
	   }
	   
	   /*
	   Make login form responsive
	   */
	   @media all and (max-width: 460px) {
		   #login {
			   width: 90% !important;
		   }
		   
		   .login h1 a { 
		      background-size: 90% auto !important; 
		      width: 100% !important; 
		   }
	   }
	   
	   @media all and (max-width: 350px) {
		   .login h1 a {
			   height: 65px !important;   
		   }
	   }
	   
       </style>';
    }
	
	/**
	hide forgot password link
	*/
	public function hide_forgot_pass_link()
	{
	   	// change wordpress login logo
		add_action('login_head', array($this, 'hide_forgot_pass_link_action'));
	}
	
	public function hide_forgot_pass_link_action ()
	{
	   echo '<style type="text/css">
	   .login #nav {
		   display: none !important;   
	   }
	   </style>';	
	}
	
    /**
	hide back to blog link
	*/
	public function hide_back_blog_link()
	{
	   	// change wordpress login logo
		add_action('login_head', array($this, 'hide_back_blog_link_action'));
	}
	
	public function hide_back_blog_link_action()
	{
	   echo '<style type="text/css">
	   .login #backtoblog {
		   display: none !important;   
	   }
	   </style>';	
	}
	
	/**
	Change login logo URL target to web site address
	*/
	public function membpress_login_page_logo_url()
	{
       return get_bloginfo( 'url' );
    }

    /**
	Change login logo title
	*/
    public function membpress_login_page_logo_title()
	{
       return _x('MembPress - Ultimate membership system for WordPress', 'general', 'membpress');
	}

    /**
	Let the users login via their email addresses too
	*/
    public function membpress_allow_email_login( &$username, &$password = '')
    {
		$user = get_user_by( 'email', $username );
	
		if( !empty( $user->user_login ) )
		{
			$username = $user->user_login;
		}
    }	
	
	/**
	Function to add login rewrite so access for wp-login.php can be reached
	via login/ as well
	*/
	public function membpress_login_rewrite()
	{
       add_rewrite_rule( 'login/?$', 'wp-login.php', 'top' );
	   flush_rewrite_rules(true); 

    }
	
	/**
	Function to remove login rewrite
	*/
	public function membpress_login_rewrite_undo()
	{	   
	   flush_rewrite_rules(true); 
	}
};