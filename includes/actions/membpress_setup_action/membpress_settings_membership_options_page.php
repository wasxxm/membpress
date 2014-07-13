<?php
/**
* This file handles the submit action of the 'MembPress Basic Setup -> Membership Options Page' section

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
	
// only update the membpress options page, if it does not match any of the login redirect page
if (!$mp_membership_option_page_flag)
{
   // update membpress options page
   update_option('membpress_settings_membership_option_page', sanitize_text_field($_POST['membpress_settings_membership_option_page']));
}

?>