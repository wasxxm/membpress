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

/*
@ Handle the action of Membpress Levels section
*/
$mp_restrict_post_level = explode(',', $_POST['membpress_restrict_posts_level_0']);
update_option('membpress_restrict_posts_level_0', $mp_restrict_post_level);

?>