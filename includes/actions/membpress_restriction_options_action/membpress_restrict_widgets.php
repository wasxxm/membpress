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

//var_dump($_POST); exit;

/**
@ Handle the action of Membpress Restrict Widgets section

@ $val is boolean true or false for a sidebar
@ $val is an array in case of widget and contains the following info
@ $val[0] is boolean true or false indicating if this is checked or not
@ $val[1] is the sidebar ID of the widget
@ $val[2] is the membership level of the widget
@ $val[3] is the ID of the widget
*/

// get the sidebar keys
$membpress_restrict_sidebar_keys = array_unique($_POST['membpress_restrict_sidebar_keys']);

// get the array holding the widgets/sidebars restrictions
$mp_restrict_sidebars_widgets_option = get_option('membpress_restrict_sidebars_widgets_option');

//var_dump($_POST); exit;

// reset all mp restrict widgets/sidebars options
foreach ($mp_restrict_sidebars_widgets_option as $option_key => $option_val)
{  
   foreach ($mp_all_membership_levels as $mp_level_name => $mp_level_val)
   {
	   $sidebar_key = 'membpress_restrict_sidebar_' . $mp_level_val['level_no'];
	   
	   $mp_restrict_sidebars_widgets_option[$option_key] = array(0, (in_array($key, $membpress_restrict_sidebar_keys)) ? true :  false, @$option_val[1], @$option_val[3], @$option_val[2]);    
   }
}

// reverse the order of the membpress levels
// this will ensure we only assign restrictions to the top level
// and discard the lower ones
$mp_all_membership_levels = array_reverse($mp_all_membership_levels, true);

// keep a record of the widgets/sidebars already restricted in some higher level
// after reversal the levels are in descending order
$mp_sidebars_widgets_restricted = array();

// keep a record of the sidebars whose values should not be checked
// because of any of the widget not being checked
$mp_sidebars_to_uncheck = array();


// for each mp level, update the restrict widgets
foreach ($mp_all_membership_levels as $mp_level_name => $mp_level_val)
{ 
   $sidebar_key = 'membpress_restrict_sidebar_' . $mp_level_val['level_no'];
   
   // check if any sidebar is restricted for this level
   // iterate through the POSTed data
   foreach ($_POST as $key => $val)
   {
	   if (stristr($key, $sidebar_key) === FALSE) continue;
	   
	   // get the sidebar/widget ID part only from the key
	   $sidebar_key_id = explode($sidebar_key, $key);
	   // remove the zero index
	   unset($sidebar_key_id[0]);
	   $sidebar_key_id = implode("", (array)$sidebar_key_id);
	   
	   // if the ID is already assigned to some higher level, then unassign
	   if (in_array($sidebar_key_id, $mp_sidebars_widgets_restricted))
	   {
		  $mp_restrict_sidebars_widgets_option[$key] = array(0, (in_array($key, $membpress_restrict_sidebar_keys)) ? true :  false, @$val[1], @$val[3], @$val[2]); 
		  
		  // check if this is a widget or sidebar
		  if (!in_array($key, $membpress_restrict_sidebar_keys))
		  {
			 // this is a widget
			 //check if this widget check is made for not
			 if (is_array($val) && count($val) > 3)
			 {
				 // we need to uncheck the sidebars in all lower levels containing the current widget
				 // we are here because this widget was already included some higher level
				 $sidebar_key_to_uncheck = $sidebar_key . '_sidebar_' . $val[1];
				 $mp_restrict_sidebars_widgets_option[$sidebar_key_to_uncheck] = array(0, true, @$val[1], @$val[3], @$val[2]);
				 // add the sidebar to the list of sidebars which should not be checked
				 $mp_sidebars_to_uncheck[] = $sidebar_key_to_uncheck; 
			 }
		  }   
	   }
	   // the restriction is not yet assigned
	   // assign it to this highest level
	   else
	   {
		 if (((bool)$val[0] && count($val) > 3) || (!is_array($val) && (bool)$val))
		 {
		    //echo $key . '<br>';
			// check the restrict sidebar/widget checkbox is checked
		    // if so, then update the option to 1
			
			// check if this is a sidebar and is included in the list of sidebars not to be checked
			if (!is_array($val) && in_array($key, $mp_sidebars_to_uncheck))
			{
			   	// skip this iteration
				continue;
			}
			
		    $mp_restrict_sidebars_widgets_option[$key] = array(1, (in_array($key, $membpress_restrict_sidebar_keys)) ? true :  false, @$val[1], @$val[3], @$val[2]);   
		 
		    $mp_sidebars_widgets_restricted[] = $sidebar_key_id;
		 }
	   }
   }
}


//var_dump($mp_restrict_sidebars_widgets_option);

// update the restrict sidebars/widgets option
update_option('membpress_restrict_sidebars_widgets_option', $mp_restrict_sidebars_widgets_option);

?>