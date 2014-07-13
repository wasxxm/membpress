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

/*
@ Handle the action of Membpress Levels section
*/
$_POST['membpress_membership_name_level_0'] = (trim($_POST['membpress_membership_name_level_0']) == '') ? MEMBPRESS_LEVEL_0 : trim($_POST['membpress_membership_name_level_0']);

$_POST['membpress_membership_name_level_1'] = (trim($_POST['membpress_membership_name_level_1']) == '') ? MEMBPRESS_LEVEL_1 : trim($_POST['membpress_membership_name_level_1']); 

$_POST['membpress_membership_name_level_2'] = (trim($_POST['membpress_membership_name_level_2']) == '') ? MEMBPRESS_LEVEL_2 : trim($_POST['membpress_membership_name_level_2']); 

$_POST['membpress_membership_name_level_3'] = (trim($_POST['membpress_membership_name_level_3']) == '') ? MEMBPRESS_LEVEL_3 : trim($_POST['membpress_membership_name_level_3']); 

$_POST['membpress_membership_name_level_4'] = (trim($_POST['membpress_membership_name_level_4']) == '') ? MEMBPRESS_LEVEL_4 : trim($_POST['membpress_membership_name_level_4']);  

// update the core membpress membership levels
update_option('membpress_membership_name_level_0', sanitize_text_field($_POST['membpress_membership_name_level_0']));
update_option('membpress_membership_name_level_1', sanitize_text_field($_POST['membpress_membership_name_level_1']));
update_option('membpress_membership_name_level_2', sanitize_text_field($_POST['membpress_membership_name_level_2']));
update_option('membpress_membership_name_level_3', sanitize_text_field($_POST['membpress_membership_name_level_3']));
update_option('membpress_membership_name_level_4', sanitize_text_field($_POST['membpress_membership_name_level_4']));
// also update the WP User Roles option
$membpress->mp_helper->membpress_update_role_display_name($wp_roles, 'subscriber', sanitize_text_field($_POST['membpress_membership_name_level_0']));
$membpress->mp_helper->membpress_update_role_display_name($wp_roles, 'membpress_level_1', sanitize_text_field($_POST['membpress_membership_name_level_1']));
$membpress->mp_helper->membpress_update_role_display_name($wp_roles, 'membpress_level_2', sanitize_text_field($_POST['membpress_membership_name_level_2']));
$membpress->mp_helper->membpress_update_role_display_name($wp_roles, 'membpress_level_3', sanitize_text_field($_POST['membpress_membership_name_level_3']));
$membpress->mp_helper->membpress_update_role_display_name($wp_roles, 'membpress_level_4', sanitize_text_field($_POST['membpress_membership_name_level_4']));


// if the membership levels are more than four, then
for($i = 5; $i <= MEMBPRESS_LEVEL_COUNT; $i++)
{
	$_POST['membpress_membership_name_level_'.$i] = (trim($_POST['membpress_membership_name_level_'.$i]) == '') ? _x('Membership Level', 'general', 'membpress') . " $i" : trim($_POST['membpress_membership_name_level_'.$i]);
	update_option('membpress_membership_name_level_'.$i, sanitize_text_field($_POST['membpress_membership_name_level_'.$i]));
	
	$membpress->mp_helper->membpress_update_role_display_name($wp_roles, 'membpress_level_' . $i, sanitize_text_field($_POST['membpress_membership_name_level_'.$i]));   	
}

?>