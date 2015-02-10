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

?>