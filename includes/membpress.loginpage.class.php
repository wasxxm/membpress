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
	    // check if the login page customization is set in 'MembPress Basic Setup -> Customize Login Page'
	   if (!$this->check_if_login_customize())
	      return;
		
		// change wordpress login logo
		add_action('login_head', array($this, 'membpress_login_page_logo'));
		
		// change wordpress login URL
		add_filter( 'login_headerurl', array($this, 'membpress_login_page_logo_url'));
		
		// change wordpress login URL Title
		add_filter( 'login_headertitle', array($this, 'membpress_login_page_logo_title')); 
		
		// let the users authenticate using email address too
	    add_action( 'wp_authenticate', array($this, 'membpress_allow_email_login'));
	}
	
	public function membpress_login_page_logo()
	{
	   
	   echo '<style type="text/css">
        .login h1 a { 
		   background-image:url('. $this->get_login_logo_url() .') !important; 
		   background-size: 380px auto !important; 
		   width: 380px !important; 
		}
		#login {
          width: 420px !important;
		  padding:6% 0 0 !important;
       }
	   .wp-core-ui .button-primary {
		   background-color: '.get_option('membpress_settings_customize_login_btn_bg', MEMBPRESS_LOGIN_BTN_BG_COLOR).' !important; 
		   border-color: '.get_option('membpress_settings_customize_login_btn_border', MEMBPRESS_LOGIN_BTN_BORDER_COLOR).' !important;
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
		   background-color: '.get_option('membpress_settings_customize_login_page_bg', MEMBPRESS_LOGIN_BG_COLOR).' !important;   
	   }
	   
	   .login form {
		   background-color: '.get_option('membpress_settings_customize_login_form_bg', MEMBPRESS_LOGIN_FORM_BG_COLOR).' !important;
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
	Function to enqueue media uploader scripts for the wp login logo change function to work
	This function is called by membpress_register_plugin_scripts() from the main membpress class
	*/
	public function enqueue_media_uploader_scripts()
	{		  
		// make sure the scripts are enqueued only for the membpress_setup_page page
		if ( 'membpress_setup_page' == $this->get_admin_page_slug() )
		{
		   wp_enqueue_script('thickbox');
           wp_enqueue_style('thickbox');
 
           wp_enqueue_script('media-upload');
		}
	}
	
	/**
	Function to render script for the change login logo media uploader
	*/
	public function render_media_uploader_login_logo()
	{  
	   // only output for basic setup page
	   if ( 'membpress_setup_page' == $this->get_admin_page_slug() )
	   {
		   
		   echo "<script type=\"text/javascript\">jQuery(document).ready(function($)
		   {
			  $('#membpress_settings_customize_login_logo_upload_btn').click(function()
			  {
				  tb_show('"._x('Upload login logo', 'general', 'membpress')."', 'media-upload.php?referer=membpress-login-logo&type=image&TB_iframe=true&post_id=0', false);
				  return false;
			  });
			  window.send_to_editor = function(html)
			  {
				  var image_url = $('img',html).attr('src');
				  if (image_url != '')
				  {
				     $('#membpress_login_logo_holder').attr('src', image_url);
				     $('#membpress_settings_customize_login_logo_url').val(image_url);
				  }
				  tb_remove();
		      }
			  $('#membpress_settings_customize_login_logo_reset_btn').click(function()
			  {
				 $('#membpress_login_logo_holder').attr('src', '".$this->get_default_login_url()."'); 
				 $('#membpress_settings_customize_login_logo_url').val('".$this->get_default_login_url()."'); 
			  });
		   });</script>";
	   }
	}
	
	/**
	Customize text for the Insert into Post button on login logo media uploader
	*/
	public function customize_logo_insert_btn_text($text)
	{
	    // check if the login page customization is set in 'MembPress Basic Setup -> Customize Login Page'
	    if (!$this->check_if_login_customize())
	      return false;
		  
	    global $pagenow;
 
        if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) 
	    {
		  if ('insert into post' == strtolower($text))
		  {
			 $referer = strpos( wp_get_referer(), 'membpress-login-logo' );
			 if ( $referer != '' )
			 {
				return _x('Set this as logo on login page', 'general', 'membpress');
			 }
		  }
		}
		
		return false;
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
       return get_option('membpress_settings_customize_login_backurl', MEMBPRESS_LOGIN_BACKURL);
    }

    /**
	Change login logo title
	*/
    public function membpress_login_page_logo_title()
	{
       return get_option('membpress_settings_customize_login_backurl_title', MEMBPRESS_LOGIN_BACKURL_TITLE);
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
	
	/**
	Function to check if login customization is enabled 
	*/
	public function check_if_login_customize()
	{	    
	   // check if the login page customization is set in 'MembPress Basic Setup -> Customize Login Page'
	   if ((bool)get_option('membpress_settings_customize_login_page_flag'))
	      return true;
		  
		return false;
	}
	
	/**
	Function to get the login logo URL
	*/
	public function get_login_logo_url()
	{
	   	return get_option('membpress_settings_customize_login_logo_url', $this->get_default_login_url());	
	}
	
	/**
	Function to get the default login logo URL
	*/
	public function get_default_login_url()
	{
	    return plugins_url('membpress/resources/images/login_logo.png');	   	
	}
};