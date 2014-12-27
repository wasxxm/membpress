<?php
/**
* Contains the html for Membership Levels in MembPress Basic Setup

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
<?php
if (isset($_GET['section']) && $_GET['section'] == 'membpress_settings_membership_levels'):
   $this->membpress_show_update_notice((isset($_GET['notice'])) ? $_GET['notice'] : 1, (isset($_GET['error'])) ? 'error' : 'success', (isset($_GET['n_vars'])) ? $_GET['n_vars'] : '');
endif;
?>
<div id="membpress_settings_membership_levels" class="postbox<?php if(!isset($_COOKIE['membpress_settings_membership_levels']) || !$_COOKIE['membpress_settings_membership_levels']): ?> closed<?php endif; ?>">
          <div class="handlediv" title="Click to toggle"><br>
          </div>
          <h3 class="hndle"><span><?php echo _x('Membership Levels', 'general', 'membpress'); ?></span></h3>
          <div class="inside">
            <p> <?php echo _x('Membpress gives you four membership levels to start with. These levels should be enough for most of the cases. The membpress levels range from Level 1 to Level 4. Level 0 is the Wordpress built-in \'Subscriber\' level. In this section you can rename all the membership levels to suit your membership plans.', 'membpress_setup', 'membpress'); ?> </p>
            
            <?php 
		     // iterate throuh all the available membership levels
		     foreach($mp_levels as $mp_level):
		    ?>
            <p>
              <label><?php echo _x('Membership Level', 'general', 'membpress'); echo ' ' . $mp_level['level_no'] ?>: </label>
              <input type="text" name="membpress_membership_name_level_<?php echo $mp_level['level_no']; ?>" value="<?php echo $mp_level['display_name'] ?>"> <?php if ($mp_level['level_no'] == 0): ?><small>(<?php echo _x('Wordpress built-in Subscriber', 'general', 'membpress'); ?>)</small><?php endif; ?>
            </p>
            <?php endforeach; ?>
            <hr>
            <input type="submit" value="<?php echo _x('Save Settings', 'general', 'membpress'); ?>" class="button button-primary" id="membpress_settings_submit" name="membpress_settings_submit-membpress_settings_membership_levels">
          </div>
        </div>