<?php
/**
* This file handles the submit action of the MembPress Restriction Options Page

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
if ( isset( $_POST['membpress_restriction_options_page_nonce']) && wp_verify_nonce( $_POST['membpress_restriction_options_page_nonce'], 'membpress_restriction_options_page' ))
{	
	// variable that will keep tract of which section triggered the error
	$membpress_error_section = 'all';
	// varaible that will flag an error
	$membpress_error_flag = false;
	// this will save the relevant error notice ID
	$membpress_error_id = 1;
	
	$section = 'all'; // section all means that the update message should appear at top
	$notice = 6; // default notice ID
	$error = false; // error is set to false by default
	$notice_vars = '';
	
    // get all roles with names, to be used below
	global $wp_roles; // global varaible holding wp roles object
	if ( ! isset( $wp_roles ) )
	{
		$wp_roles = new WP_Roles();
	}
	$membpress_wp_roles = $wp_roles->role_names;
	
	/*
	**************************************************
	@ Include the Restrict Posts section action, submit
	**************************************************
	*/
	include_once 'membpress_restriction_options_action/membpress_restrict_posts.php';
	
	
	/*
	**************************************************
	@ Include the Restrict Pages section action, submit
	**************************************************
	*/
	include_once 'membpress_restriction_options_action/membpress_restrict_pages.php';
	
	

    /*
	**************************************************
	@ Include the Restrict Categories section action, submit
	**************************************************
	*/
	include_once 'membpress_restriction_options_action/membpress_restrict_categories.php';
	
	
	/*
	**************************************************
	@ Check which section triggered the action and act accordingly
	*/
	
	// if Restrict Post section
	if (isset($_POST['membpress_restrict_submit-membpress_restrict_post_section']))
	{
	   $section = 'all';
	}
	
	if (isset($_POST['membpress_restrict_submit-membpress_restrict_page_section']))
	{
	   $section = 'membpress_restrict_pages';	
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
	
	wp_redirect(admin_url() . 'admin.php?page=membpress_restrict_options_page&updated=1&section=' . urlencode($section) . $notice_error_q . '&n_vars=' . urlencode($notice_vars) . '#section=#'.urlencode($section));
}
else
{
  // request is not valid
  // show the no permission notice
  $membpress->mp_helper->membpress_permission_denied_notice();	
}

?>