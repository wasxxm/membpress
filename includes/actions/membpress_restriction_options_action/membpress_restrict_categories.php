<?php
/**
* This file handles the submit action of the 'MembPress -> Restriction Options -> Restrict Categories' section

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

// before updating with new values, clear the old values
// clear the membpress restrict categories level, Restrict Categories
foreach ($mp_all_membership_levels as $mp_level_name => $mp_level_val)
{
   // get current restrict category option
   $mp_restrict_categories_level = (array)get_option('membpress_restrict_categories_level_'.$mp_level_val['level_no']);
   
   // clear the Restrict Categories option
   update_option('membpress_restrict_categories_level_'.$mp_level_val['level_no'], '');
}

// reverse the membership levels so we can retain the category ID for higher levels and discard the lower levels
$mp_all_membership_levels = array_reverse($mp_all_membership_levels, true);
// array to hold the category IDs already assigned to higher membership levels
$mp_higher_restricted_categories = array();

foreach ($mp_all_membership_levels as $mp_level_name => $mp_level_val)
{
   // explode it comma vise and turn it into array
   $mp_restrict_categories_level = explode(',', $_POST['membpress_restrict_categories_level_'.$mp_level_val['level_no']]);
   
   // make the category IDs unique
   $mp_restrict_categories_level = array_unique($mp_restrict_categories_level);
   
   // remove all category IDs already inlcuded in some higher membership level
   foreach ($mp_restrict_categories_level as $mp_restrict_category_level_key => $mp_restrict_category_level_val)
   {
	   if (in_array($mp_restrict_category_level_val, $mp_higher_restricted_categories))
	   {
		   unset($mp_restrict_categories_level[$mp_restrict_category_level_key]);   
	   }
	   else
	   {
	      // push the current higher category IDs to the array
          array_push($mp_higher_restricted_categories, $mp_restrict_category_level_val);
	   }
   }
   
   // iterate through all the category IDs of the current level
   foreach ($mp_restrict_categories_level as $mp_restrict_category_level_key => $mp_restrict_category_level_val)
   { 
	  // check if the category ID is valid
	  $mp_restrict_category_level_val = trim($mp_restrict_category_level_val);
	  
	  if (is_numeric($mp_restrict_category_level_val) && $mp_restrict_category_level_val > 0)
	  {
         //
	  }
	  else
	  {
		  // if not valid, then remove it from the array
		  unset($mp_restrict_categories_level[$mp_restrict_category_level_key]);  
	  }
   }
   
   // check if the category IDs are empty for the current level
   if (!is_array($mp_restrict_categories_level) && trim($mp_restrict_categories_level) == '') $mp_restrict_categories_level = '';
   
   // update the membpress restrict_categories_level_{level_no} option
   update_option('membpress_restrict_categories_level_'.$mp_level_val['level_no'], $mp_restrict_categories_level);
}

?>