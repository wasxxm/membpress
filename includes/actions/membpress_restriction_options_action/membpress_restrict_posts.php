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

// also clear the Restrict all posts option
update_option('membpress_restrict_allposts_level', '');

// reverse the membership levels so we can retain the post ID for higher levels and discard the lower levels
$mp_all_membership_levels = array_reverse($mp_all_membership_levels, true);
// array to hold the post IDs already assigned to higher membership levels
$mp_higher_restricted_posts = array();
// get the highest restrict all posts level
$mp_highest_restrict_allposts_level = $_POST['membpress_restrict_allposts_level'];
if (is_array($mp_highest_restrict_allposts_level) && count($mp_highest_restrict_allposts_level) > 0)
{
   $mp_highest_restrict_allposts_level = max($mp_highest_restrict_allposts_level);	
}
else
{
   $mp_highest_restrict_allposts_level = '';	
}

foreach ($mp_all_membership_levels as $mp_level_name => $mp_level_val)
{
	  // first check if the restrict all posts by this level checkbox is checked or not
	 if ($mp_highest_restrict_allposts_level != '' && $mp_highest_restrict_allposts_level >= $mp_level_val['level_no'])
	 {
		  if ($mp_highest_restrict_allposts_level == $mp_level_val['level_no'])
		  {
		     // update the restrict all posts option by level
		     update_option('membpress_restrict_allposts_level', $mp_level_val['level_no']);
			
		     $mp_restrict_posts_level = array();
		  }
	 }
	 else
	 {
		 // explode it comma vise and turn it into array
	     $mp_restrict_posts_level = explode(',', $_POST['membpress_restrict_posts_level_'.$mp_level_val['level_no']]);
 
	     // make the post IDs unique
	     $mp_restrict_posts_level = array_unique($mp_restrict_posts_level);  
	 }  
	 
	 // remove all post IDs already inlcuded in some higher membership level
	 foreach ((array)$mp_restrict_posts_level as $mp_restrict_post_level_key => $mp_restrict_post_level_val)
	 {
		 if (in_array($mp_restrict_post_level_val, $mp_higher_restricted_posts))
		 {
			 unset($mp_restrict_posts_level[$mp_restrict_post_level_key]);   
		 }
		 else
		 {
			// push the current higher post IDs to the array
			array_push($mp_higher_restricted_posts, $mp_restrict_post_level_val);
		 }
	 }
	 
	 // we need to update the relevant meta data of the post
	 // iterate through all the post IDs of the current level
	 foreach ((array)$mp_restrict_posts_level as $mp_restrict_post_level_key => $mp_restrict_post_level_val)
	 { 
		// check if the post ID is valid
		$mp_restrict_post_level_val = trim($mp_restrict_post_level_val);
		
		if (is_numeric($mp_restrict_post_level_val) && $mp_restrict_post_level_val > 0)
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