<?php
/**
* This file handles the submit action of the MembPress Settings/Setup Page

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

// load the Wordpress Main Load File
require_once '../../../../../wp-load.php';

// proceed only if the user is logged in and have necessary capability to manage options
if (!is_user_logged_in() || !current_user_can( 'manage_options' ))
{
	// show the no permission notice
	$membpress->mp_helper->membpress_permission_denied_notice();
}

// Check if our nonce is set.
if ( isset( $_POST['membpress_settings_page_nonce']) && wp_verify_nonce( $_POST['membpress_settings_page_nonce'], 'membpress_settings_page' ))
{	
	// variable that will keep track of which section triggered the error
	$membpress_error_section = 'all';
	// varaible that will flag an error
	$membpress_error_flag = false;
	// this will save the relevant error notice ID
	$membpress_error_id = 1;
	
	$section = 'all'; // section all means that the update message should appear at top
	$notice = 1; // default notice ID
	$error = false; // error is set to false by default
	$notice_vars = '';
	
	/*
	**************************************************
	@ Include the Welcome Login Page section, submit
	**************************************************
	*/
	include_once 'membpress_setup_action/membpress_settings_welcome_page_login.php';
	
	/*
	**************************************************
	@ Include the Membership Options Page section, submit
	**************************************************
	*/
	include_once 'membpress_setup_action/membpress_settings_membership_options_page.php';
	
	
	/*
	**************************************************
	@ Include the Membership Levels section, submit
	**************************************************
	*/
	include_once 'membpress_setup_action/membpress_settings_membership_levels.php';
	
	
	
	/*
	**************************************************
	@ Include the Membership Customize Login Page, submit
	**************************************************
	*/
	include_once 'membpress_setup_action/membpress_settings_customize_login_page.php';
	
	
	
	/*
	**************************************************
	@ Check which section triggered the action and act accordingly
	*/
	
	// if login welcome section
	if (isset($_POST['membpress_settings_submit-membpress_settings_welcome_page_login']))
	{
	   $section = 'all';
	}
	// if membership options page section
	else if(isset($_POST['membpress_settings_submit-membpress_settings_membership_options_page']))
	{
	   $section = 'membpress_settings_membership_options_page';
	   	
	}
	// if membership levels section
	else if(isset($_POST['membpress_settings_submit-membpress_settings_membership_levels']))
	{
	   $section = 'membpress_settings_membership_levels';
	   	
	}
	// if customize login page section
	else if(isset($_POST['membpress_settings_submit-membpress_settings_customize_login_page']))
	{
	   $section = 'membpress_settings_customize_login_page';
	   	
	}
	
	// see if there was an error
	if ($membpress_error_flag)
	{
	   // set error as true
	   $error = true;
	   // ok, set the section to the one which triggered the error
	   $section = $membpress_error_section;
	   // set the notice ID to the error ID	
	   $notice = $membpress_error_id;
	}
	
	// build the notice and error query
	$notice_error_q = '&notice=' . $notice;
	if ($error)
	{
	   $notice_error_q .= '&error=1';
	}
	
	wp_redirect(admin_url() . 'admin.php?page=membpress_setup_page&updated=1&section=' . urlencode($section) . $notice_error_q . '&n_vars=' . urlencode($notice_vars) . '#section=#'.urlencode($section));
}
else
{
  // request is not valid
  // show the no permission notice
  $membpress->mp_helper->membpress_permission_denied_notice();	
}

?>