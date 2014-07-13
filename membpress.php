<?php
/**
* This is the main plugin file for membpress plugin

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
/*
--- Wordpress Parsing Data Starts Here ---
------------------------------------------
Version: 1.0
Stable tag: 1.0

Tested up to: 3.9.1
Requires at least: 3.3

Copyright: © 2014 MembPress, Inc.
License: GNU General Public License
Contributors: Waseem Khan

Author: Waseem Khan
Author URI: http://www.wazeem.com/

Text Domain: membpress

Plugin Name: MembPress Membership Plugin
Forum URI: http://www.membpress.com/forum/
Plugin URI: http://www.membpress.com/
Privacy URI: http://www.membpress.com/privacy/
Video Tutorials: http://www.membpress.com/videos/

Description: MembPress is a free, all-in-one membership system for wordpress. Using custom membership levels, you can restrict access to any page/post and have the users pay to access them.
Tags: membpress, membership, subscription, paid, wordpress, levels, users, restrict, paypal

----------------------------------------
--- Wordpress Parsing Data Ends Here ---
*/

//avoid direct calls to this file where wp core files not present
if (!function_exists ('add_action')) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		echo _x('You do not have permission to view this page / perform this action.', 'general', 'membpress');
		exit();
}

// include membpress configuration file
require_once 'membpress.config.php';

// server compatibility check, minimum php version etc
// only proceed if checks go through
if(version_compare(PHP_VERSION, MEMBPRESS_MIN_PHP_VERSION, ">=") && version_compare(get_bloginfo("version"), MEMBPRESS_MIN_WP_VERSION, ">="))
{
   // add the main membpress class. This class handles the basic structure of the plugin
   require_once 'includes/membpress.class.php';
   // run the membpress main framework
   $membpress = new MembPress_Main();	
}
else
{
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	echo sprintf(_x('Membpress cannot be installed/run. Please make sure you have at least PHP version %s and Wordpress version %s or later installed.', 'general', 'membpress'), MEMBPRESS_MIN_PHP_VERSION, MEMBPRESS_MIN_WP_VERSION);
	exit();	
}
	
?>