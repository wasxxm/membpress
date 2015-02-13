<?php
/**
* This file handles the submit action of the 'MembPress Basic Setup -> Customize Login Page' section

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


// update the customize login page check flag
update_option('membpress_settings_customize_login_page_flag', isset($_POST['membpress_settings_customize_login_page_flag']) ? 1 : 0);

// update the customize login page hide password link flag
update_option('membpress_settings_customize_login_hide_passforgot', isset($_POST['membpress_settings_customize_login_hide_passforgot']) ? 1 : 0);

// update the hide back to web site on login page
update_option('membpress_settings_customize_login_hide_bloglink', isset($_POST['membpress_settings_customize_login_hide_bloglink']) ? 1 : 0);

$new_rewrite_flag = isset($_POST['membpress_settings_customize_login_rewrite_flag']) ? 1 : 0;

$old_rewrite_flag = (bool)get_option('membpress_settings_customize_login_rewrite_flag');


if (($old_rewrite_flag == 1 && $new_rewrite_flag == 0) || ($old_rewrite_flag == 0 && $new_rewrite_flag == 1))
{
	// update the  login rewrite pending flag
	// if there is a change in the setting of login url rewrite
	update_option('membpress_settings_customize_login_rewrite_pending_flag', 1);
}

// update the login page rewrite flag
update_option('membpress_settings_customize_login_rewrite_flag', $new_rewrite_flag);


?>