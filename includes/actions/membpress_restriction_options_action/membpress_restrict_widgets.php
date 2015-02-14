<?php
/**
* This file handles the submit action of the 'MembPress -> Restriction Options -> Restrict Widgets' section

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

/**
@ Handle the action of Membpress Restrict Widgets section
*/

//var_dump($all_options); exit;

// reset all mp restrict widgets/sidebars options
foreach ($all_options as $option_key => $option_val)
{  
   foreach ($mp_all_membership_levels as $mp_level_name => $mp_level_val)
   {
	   $sidebar_key = 'membpress_restrict_sidebar_' . $mp_level_val['level_no'];
		  
	   if (stristr($option_key, $sidebar_key) !== FALSE)
	   {
		   update_option($option_key, 0);   
	   }
   }
}


// for each mp level, update the restrict widgets
foreach ($mp_all_membership_levels as $mp_level_name => $mp_level_val)
{ 
   $sidebar_key = 'membpress_restrict_sidebar_' . $mp_level_val['level_no'];
   
   // check if any sidebar is restricted for this level
   // iterate through the POSTed data
   foreach ($_POST as $key => $val)
   {
	   if (stristr($key, $sidebar_key) !== FALSE)
	   {
		   // the restrict sidebar/widget checkbox is checked
		   // so update the option to 1
		   update_option($key, 1);
	   }
   }
}

?>