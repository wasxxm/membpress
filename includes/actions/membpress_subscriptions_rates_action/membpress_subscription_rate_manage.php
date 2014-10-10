<?php
/**
* This file handles the submit action of the 'MembPress -> Subscription Rates -> Subscription Rates for Level %level_no%' section

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
@ Handle the action of Membpress Subscription Rates for the level triggered
@ Save the subscription rate data for the current level
*/

/**
Save the POSTed data and validate it
*/

// (string) subscription type like 'recurring, one_time, life_time'
$membpress_subs_rate_type = $_POST['membpress_subs_rate_type'];

/**
For trial period
*/

// (float) charge for the trial period. This is in dollars
$membpress_subs_trial_charge = (float)$_POST['membpress_subs_trial_charge'];

// (integer) trial duration integer, to be used with hours, days etc
$membpress_subs_trial_duration = (int)$_POST['membpress_subs_trial_duration'];

// (string) trial duration type like 'hour, day, week, month' etc
$membpress_subs_trial_duration_type = $_POST['membpress_subs_trial_duration_type'];

/**
For normal period
*/

// (float) charge for the normal period. This is in dollars
$membpress_subs_charge = (float)$_POST['membpress_subs_charge'];

// (integer) normal duration integer, to be used with hours, days etc
$membpress_subs_duration = (int)$_POST['membpress_subs_duration'];

// (string) normal duration type like 'hour, day, week, month' etc
$membpress_subs_duration_type = $_POST['membpress_subs_duration_type'];

// (string) Subscription Rate name
$membpress_subs_rate_name = sanitize_text_field($_POST['membpress_subs_rate_name']);

/**
Get the membership levels global option array
*/
$membpress_levels =  (array)get_option('membpress_levels');

//$membpress_subscription_rate_level holds the target level for which the request was made
// get the target level array
$membpress_target_level = $membpress_levels['membpress_level_'.$membpress_subscription_rate_level];

//temporarily create an array to store subscription rate settings/options
$temp_subs_rate_arr = array();

$temp_subs_rate_arr['type'] = $membpress_subs_rate_type;

$temp_subs_rate_arr['trial_charge'] = $membpress_subs_trial_charge;
$temp_subs_rate_arr['trial_charge_duration'] = $membpress_subs_trial_duration;
$temp_subs_rate_arr['trial_charge_duration_type'] = $membpress_subs_trial_duration_type;

$temp_subs_rate_arr['normal_charge'] = $membpress_subs_charge;
$temp_subs_rate_arr['normal_charge_duration'] = $membpress_subs_duration;
$temp_subs_rate_arr['normal_charge_duration_type'] = $membpress_subs_duration_type;

$temp_subs_rate_arr['subscription_name'] = $membpress_subs_rate_name;

$membpress_target_level_subs = array();

if (isset($membpress_target_level['subscription_rates']) && is_array($membpress_target_level['subscription_rates']))
{
    $membpress_target_level_subs = $membpress_target_level['subscription_rates'];
    /**
	Check if the request was for new subscription rate creation
	*/
	if (isset($_POST['membpress_create_new_subscription_rate']))
    {
	   $membpress_target_level_subs[] = $temp_subs_rate_arr;
	}
}
else
{
   $membpress_target_level_subs[] = $temp_subs_rate_arr;  		
}

// save the new array of the subscription rates
$membpress_target_level['subscription_rates'] = $membpress_target_level_subs;

// assign the new subscription array to the corresponding level
$membpress_levels['membpress_level_'.$membpress_subscription_rate_level] = $membpress_target_level;

// update membpress levels array option
update_option('membpress_levels', $membpress_levels);


?>