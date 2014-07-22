<?php
/**
* This file handles the submit action of the 'MembPress -> Restriction Options -> Restrict Posts' section

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
// clear the membpress restrict posts level, Restrict Posts
foreach ($mp_all_membership_levels as $mp_level_name => $mp_level_val)
{
   // get current restrict post option
   $mp_restrict_posts_level = (array)get_option('membpress_restrict_posts_level_'.$mp_level_val['level_no']);
   
   // clear the post restrict meta for each ID
   foreach ($mp_restrict_posts_level as $mp_restrict_post_level_key => $mp_restrict_post_level_val)
   {
	   update_post_meta($mp_restrict_post_level_val, 'membpress_post_restricted_by_level', '');   
   }
   
   // clear the Restrict Posts option
   update_option('membpress_restrict_posts_level_'.$mp_level_val['level_no'], '');
}

foreach ($mp_all_membership_levels as $mp_level_name => $mp_level_val)
{
   // explode it comma vise and turn it into array
   $mp_restrict_posts_level = explode(',', $_POST['membpress_restrict_posts_level_'.$mp_level_val['level_no']]);
   
   // we need to update the relevant meta data of the post
   // iterate through all the post IDs of the current level
   foreach ($mp_restrict_posts_level as $mp_restrict_post_level_key => $mp_restrict_post_level_val)
   { 
	  // check if the post ID is valid
	  if (trim($mp_restrict_post_level_val) != "" && $mp_restrict_post_level_val > 0)
	  {
		 update_post_meta($mp_restrict_post_level_val, 'membpress_post_restricted_by_level', $mp_level_name);
	  }
	  else
	  {
		  // if not valid, then remove it from the array
		  unset($mp_restrict_posts_level[$mp_restrict_post_level_key]);  
	  }
   }
   
   // check if the post IDs are empty for the current level
   if (!is_array($mp_restrict_posts_level) && trim($mp_restrict_posts_level) == '') $mp_restrict_posts_level = '';
   
   // update the membpress restrict_posts_level_{level_no} option
   update_option('membpress_restrict_posts_level_'.$mp_level_val['level_no'], $mp_restrict_posts_level);
}

?>