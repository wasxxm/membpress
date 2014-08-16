<?php
/**
* This file handles the submit action of the 'MembPress -> Restriction Options -> Restrict Pages' section

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
@ Handle the action of Membpress Levels section
*/

// iterate through all the levels
$mp_all_membership_levels = $membpress->mp_helper->membpress_get_all_membership_levels();

// before updating with new values, clear the old values
// clear the membpress restrict pages level, Restrict Pages
foreach ($mp_all_membership_levels as $mp_level_name => $mp_level_val)
{
   // get current restrict page option
   $mp_restrict_pages_level = (array)get_option('membpress_restrict_pages_level_'.$mp_level_val['level_no']);
   
   // clear the page restrict meta for each ID
   foreach ($mp_restrict_pages_level as $mp_restrict_page_level_key => $mp_restrict_page_level_val)
   {
	   update_page_meta($mp_restrict_page_level_val, 'membpress_page_restricted_by_level', '');   
   }
   
   // clear the Restrict Posts option
   update_option('membpress_restrict_pages_level_'.$mp_level_val['level_no'], '');
}

foreach ($mp_all_membership_levels as $mp_level_name => $mp_level_val)
{
   // explode it comma vise and turn it into array
   $mp_restrict_pages_level = explode(',', $_POST['membpress_restrict_pages_level_'.$mp_level_val['level_no']]);
   
   // make the page IDs unique
   $mp_restrict_pages_level = array_unique($mp_restrict_pages_level);
   
   // we need to update the relevant meta data of the page
   // iterate through all the page IDs of the current level
   foreach ($mp_restrict_pages_level as $mp_restrict_page_level_key => $mp_restrict_page_level_val)
   { 
	  // check if the page ID is valid
	  if (trim($mp_restrict_page_level_val) != "" && $mp_restrict_page_level_val > 0)
	  {
		 update_page_meta($mp_restrict_page_level_val, 'membpress_page_restricted_by_level', $mp_level_name);
	  }
	  else
	  {
		  // if not valid, then remove it from the array
		  unset($mp_restrict_pages_level[$mp_restrict_page_level_key]);  
	  }
   }
   
   // check if the page IDs are empty for the current level
   if (!is_array($mp_restrict_pages_level) && trim($mp_restrict_pages_level) == '') $mp_restrict_pages_level = '';
   
   // update the membpress restrict_pages_level_{level_no} option
   update_option('membpress_restrict_pages_level_'.$mp_level_val['level_no'], $mp_restrict_pages_level);
}

?>