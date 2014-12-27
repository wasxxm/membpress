<?php
/**
* Contains the html for MembPress Setup Page heading area

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
?>
      <h2 class="membpress_pull-left membpress_settings_heading"> <?php echo _x('MembPress Basic Setup/Settings', 'general', 'membpress'); ?></h2>
      <div class="membpress_settings_collapse_expand  membpress_pull-right">
        <div class="dashicons dashicons-arrow-down"></div>
        <a href="javascript:;" class="membpress_settings_expand"><?php echo _x('Expand All', 'general', 'membpress'); ?></a>
        <div class="dashicons dashicons-arrow-up"></div>
        <a href="javascript:;" class="membpress_settings_collapse"><?php echo _x('Collapse All', 'general', 'membpress'); ?></a> </div>
      <div class="membpress_clear"></div>
      <?php
if (isset($_GET['section']) && $_GET['section'] == 'all'):
   $this->membpress_show_update_notice((isset($_GET['notice'])) ? $_GET['notice'] : 1, (isset($_GET['error'])) ? 'error' : 'success', (isset($_GET['n_vars'])) ? $_GET['n_vars'] : '');
endif;
?>