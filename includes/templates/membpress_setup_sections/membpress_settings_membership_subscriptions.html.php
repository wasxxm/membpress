<?php
/**
* Contains the html for Membership Subscriptions in MembPress Basic Setup

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
if (isset($_GET['section']) && $_GET['section'] == 'membpress_settings_membership_subscriptions'):
   $this->mp_helper->membpress_show_update_notice((isset($_GET['notice'])) ? $_GET['notice'] : 1, (isset($_GET['error'])) ? 'error' : 'success', (isset($_GET['n_vars'])) ? $_GET['n_vars'] : '');
endif;
?>

<div id="membpress_settings_membership_subscriptions" class="postbox<?php if(!isset($_COOKIE['membpress_settings_membership_subscriptions']) || !$_COOKIE['membpress_settings_membership_subscriptions']): ?> closed<?php endif; ?>">
  <div class="handlediv" title="Click to toggle"><br>
  </div>
  <h3 class="hndle"><span><?php echo _x('Membership Subscription Rates', 'general', 'membpress'); ?></span></h3>
  <div class="inside">
    <p> <?php echo _x('Membership subscriptions section lets you setup rates for the membership levels. The changes made here will reflect on the membership options page where you display the membership plans (usually in a table).', 'membpress_setup', 'membpress'); ?> </p>
    <?php 
		     // iterate throuh all the available membership subscriptions
		     foreach($mp_levels as $mp_level):
		    ?>
    <p> <strong><?php echo _x('Subscription settings for level ', 'general', 'membpress'); echo ' ' . $mp_level['level_no'] ?> (<?php echo $mp_level['display_name']; ?>)</strong> </p>
    <p>
    Free for the first <input type="number" value="5"> <select><option>Hour(s)</option><option>Day(s)</option><option>Week(s)</option><option>Month(s)</option><option>Year(s)</option></select>
    </p>
        <p>
    then start charging $<input type="number" value="49"> for every <input type="number" value="1"> <select><option>Hour(s)</option><option>Day(s)</option><option>Week(s)</option><option selected>Month(s)</option><option>Year(s)</option></select>
    </p>
    <hr>
    <?php endforeach; ?>
    <input type="submit" value="<?php echo _x('Save Settings', 'general', 'membpress'); ?>" class="button button-primary" id="membpress_settings_submit" name="membpress_settings_submit-membpress_settings_membership_subscriptions">
  </div>
</div>
