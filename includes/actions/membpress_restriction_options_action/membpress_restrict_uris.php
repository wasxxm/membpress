<?php
/**
* This file handles the submit action of the 'MembPress -> Restriction Options -> Restrict URIs' section

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
// clear the membpress restrict uris level, Restrict URIs
foreach ($mp_all_membership_levels as $mp_level_name => $mp_level_val)
{
   // get current restrict uri option
   $mp_restrict_uris_level = (array)get_option('membpress_restrict_uris_level_'.$mp_level_val['level_no']);
   
   // clear the Restrict URIs option
   update_option('membpress_restrict_uris_level_'.$mp_level_val['level_no'], '');
}

// reverse the membership levels so we can retain the URI pattern for higher levels and discard the lower levels
$mp_all_membership_levels = array_reverse($mp_all_membership_levels);
// array to hold the URIs already assigned to higher membership levels
$mp_higher_restricted_uris = array();

foreach ($mp_all_membership_levels as $mp_level_name => $mp_level_val)
{
   // explode it comma vise and turn it into array
   $mp_restrict_uris_level = explode("\n", $_POST['membpress_restrict_uris_level_'.$mp_level_val['level_no']]);
   
   // make the URIs unique
   $mp_restrict_uris_level = array_unique($mp_restrict_uris_level);
   
   // remove all URIs already inlcuded in some higher membership level
   foreach ($mp_restrict_uris_level as $mp_restrict_uri_level_key => $mp_restrict_uri_level_val)
   {
	   if (in_array(trim($mp_restrict_uri_level_val), $mp_higher_restricted_uris))
	   {
		   unset($mp_restrict_uris_level[$mp_restrict_uri_level_key]);   
	   }
	   else if (trim($mp_restrict_uri_level_val) != '')
	   {
	      // push the current higher URIs to the array
		  array_push($mp_higher_restricted_uris, trim($mp_restrict_uri_level_val));
	   }
   }
   
   // iterate through all the URIs of the current level
   foreach ($mp_restrict_uris_level as $mp_restrict_uri_level_key => $mp_restrict_uri_level_val)
   { 
	  // check if the URI is valid
	  $mp_restrict_uri_level_val = trim($mp_restrict_uri_level_val);
	  
	  if ($mp_restrict_uri_level_val != '')
	  {
         $mp_restrict_uris_level[$mp_restrict_uri_level_key] = trim($mp_restrict_uri_level_val);
	  }
	  else
	  {
		  // if not valid, then remove it from the array
		  unset($mp_restrict_uris_level[$mp_restrict_uri_level_key]);  
	  }
   }
   
   // check if the URIs are empty for the current level
   if (!is_array($mp_restrict_uris_level) && trim($mp_restrict_uris_level) == '') $mp_restrict_uris_level = '';
   
   // update the membpress restrict_uris_level_{level_no} option
   update_option('membpress_restrict_uris_level_'.$mp_level_val['level_no'], $mp_restrict_uris_level);
}

?>