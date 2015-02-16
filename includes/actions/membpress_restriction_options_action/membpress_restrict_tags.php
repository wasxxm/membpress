<?php
/**
* This file handles the submit action of the 'MembPress -> Restriction Options -> Restrict Tags' section

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
// clear the membpress restrict tags level, Restrict Tags
foreach ($mp_all_membership_levels as $mp_level_name => $mp_level_val)
{
   // get current restrict tag option
   $mp_restrict_tags_level = (array)get_option('membpress_restrict_tags_level_'.$mp_level_val['level_no']);
   
   // clear the Restrict Tags option
   update_option('membpress_restrict_tags_level_'.$mp_level_val['level_no'], '');
}

// reverse the membership levels so we can retain the tag ID for higher levels and discard the lower levels
$mp_all_membership_levels = array_reverse($mp_all_membership_levels, true);
// array to hold the tag IDs already assigned to higher membership levels
$mp_higher_restricted_tags = array();

foreach ($mp_all_membership_levels as $mp_level_name => $mp_level_val)
{
   // explode it comma vise and turn it into array
   $mp_restrict_tags_level = explode(',', $_POST['membpress_restrict_tags_level_'.$mp_level_val['level_no']]);
   
   // make the tag IDs unique
   $mp_restrict_tags_level = array_unique($mp_restrict_tags_level);
   
   // remove all tag IDs already inlcuded in some higher membership level
   foreach ($mp_restrict_tags_level as $mp_restrict_tag_level_key => $mp_restrict_tag_level_val)
   {
	   if (in_array($mp_restrict_tag_level_val, $mp_higher_restricted_tags))
	   {
		   unset($mp_restrict_tags_level[$mp_restrict_tag_level_key]);   
	   }
	   else
	   {
	      // push the current higher tag IDs to the array
          array_push($mp_higher_restricted_tags, $mp_restrict_tag_level_val);
	   }
   }
   
   // iterate through all the tag IDs of the current level
   foreach ($mp_restrict_tags_level as $mp_restrict_tag_level_key => $mp_restrict_tag_level_val)
   { 
	  // check if the tag ID is valid
	  $mp_restrict_tag_level_val = trim($mp_restrict_tag_level_val);
	  
	  if (is_numeric($mp_restrict_tag_level_val) && $mp_restrict_tag_level_val > 0)
	  {
         //
	  }
	  else
	  {
		  // if not valid, then remove it from the array
		  unset($mp_restrict_tags_level[$mp_restrict_tag_level_key]);  
	  }
   }
   
   // check if the tag IDs are empty for the current level
   if (!is_array($mp_restrict_tags_level) && trim($mp_restrict_tags_level) == '') $mp_restrict_tags_level = '';
   
   // update the membpress restrict_tags_level_{level_no} option
   update_option('membpress_restrict_tags_level_'.$mp_level_val['level_no'], $mp_restrict_tags_level);
}

?>