<?php
/**
* Contains the basic configurations for the membpress engine
* These settings/configs will be available globally
*
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

//////////////////////////////////////////////////////////////////////////////
// Core MembPress Configs
//////////////////////////////////////////////////////////////////////////////

// this constant will be used to check the membpress engine is included
define('MEMBPRESS_LOADED', true);

// define the maximum number of posts to show on the settings page
define('MEMBPRESS_SETTINGS_MAX_POSTS', 3);

// define membership level counts, cannot be less than 4
// This DOES NOT include the level 0
define('MEMBPRESS_LEVEL_COUNT', 4);

// define Membpress Membership level names
// if you have more than level 4 membership plans, you can add the names here
// these names can however be changed in MembPress -> Basic Setup -> Membership Levels
define('MEMBPRESS_LEVEL_0', _x('Free Member', 'general', 'membpress'));
define('MEMBPRESS_LEVEL_1', _x('Basic Member', 'general', 'membpress'));
define('MEMBPRESS_LEVEL_2', _x('Plus Member', 'general', 'membpress'));
define('MEMBPRESS_LEVEL_3', _x('Standard Member', 'general', 'membpress'));
define('MEMBPRESS_LEVEL_4', _x('Premium Member', 'general', 'membpress'));
define('MEMBPRESS_LEVEL_5', _x('Ultra Premium Member', 'general', 'membpress'));

// minimum php version to run membpress
define('MEMBPRESS_MIN_PHP_VERSION', '5.2');
// minimum wordpress version required for membpress
define('MEMBPRESS_MIN_WP_VERSION', '3.5');

//////////////////////////////////////////////////////////////////////////////
// Advanced Configs
//////////////////////////////////////////////////////////////////////////////
define('MEMBPRESS_LOGIN_BG_COLOR', '#ffffff');
define('MEMBPRESS_LOGIN_FORM_BG_COLOR', '#f5f8fb');
define('MEMBPRESS_LOGIN_BTN_BG_COLOR', '#045580');
define('MEMBPRESS_LOGIN_BTN_BORDER_COLOR', '#045580');
define('MEMBPRESS_LOGIN_BACKURL', get_home_url());
define('MEMBPRESS_LOGIN_BACKURL_TITLE', get_bloginfo('name'));

?>