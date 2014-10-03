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
// iterate throuh all the available membership subscriptions
foreach($mp_levels as $mp_level):
?>
<?php
if (isset($_GET['section']) && $_GET['section'] == 'membpress_settings_membership_subscriptions_'.$mp_level['level_no']):
   $this->mp_helper->membpress_show_update_notice((isset($_GET['notice'])) ? $_GET['notice'] : 1, (isset($_GET['error'])) ? 'error' : 'success', (isset($_GET['n_vars'])) ? $_GET['n_vars'] : '');
endif;
?>

<div id="membpress_settings_membership_subscriptions_<?php echo $mp_level['level_no']; ?>" class="postbox<?php if(!isset($_COOKIE['membpress_settings_membership_subscriptions_'.$mp_level['level_no']]) || !$_COOKIE['membpress_settings_membership_subscriptions_'.$mp_level['level_no']]): ?> closed<?php endif; ?>">
  <div class="handlediv" title="Click to toggle"><br>
  </div>
  <h3 class="hndle"><span><?php echo _x('Subscription Rates for Level', 'general', 'membpress'); ?> <?php echo $mp_level['level_no']; ?> (<?php echo $mp_level['display_name']; ?>)</span></h3>
  <div class="inside">
    <p> <?php echo _x('Create as many subscription rates for this level as you need. Membpress lets you create three types of subscriptions for a level, "Recurring", "One Time" and "Life Time". Recurring subscriptions are billed repeatedly based on the interval you set. A One Time subscription will automatically end after an interval (you set) without further billing. Life Time is the ultimate subscription where permanent access to a membership level is given.', 'general', 'membpress'); ?> </p>
    <p> <?php echo _x('Charging for the first few days/weeks etc acts as a trial period during which a member can cancel the membership without incurring any charges.', 'membpress', 'general'); ?> </p>
    <hr>
    <p> <strong><?php echo _x('Subscription #1 for Free Member', 'general', 'membpress');?></strong> </p>
    <hr>
    <span class="membpress_subs_rate_wrapper">
    <p> <strong><?php echo _x('Create a new subscription rate for this level', 'general', 'membpress');?></strong> </p>
    <p> Subscription Type:
      <select class="membpress_subs_type">
        <option value="recurring">Recurring</option>
        <option value="one_time">One Time</option>
        <option value="life_time">Life Time</option>
      </select>
    </p>
    <p> Charge $
      <input type="number" value="0.00" class="membpress_span2" step="0.01" min="0.00">
      for the first
      <input type="number" value="0" class="membpress_span2" min="0" max="9999">
      <select>
        <option>Hour(s)</option>
        <option>Day(s)</option>
        <option>Week(s)</option>
        <option>Month(s)</option>
        <option>Year(s)</option>
      </select>
    </p>
    <p> <span class="membpress_subs_recurring_txt">then start charging</span><span class="membpress_subs_onetime_txt">then charge</span><span class="membpress_subs_lifetime_txt">then charge</span> $
      <input type="number" value="0.99" class="membpress_span2" step="0.01" min="0.00">
      <span class="membpress_subs_recurring_for">for every</span><span class="membpress_subs_onetime_for">for</span><span class="membpress_subs_lifetime_for">for life-time access</span> <span class="membpress_subs_duration">
      <input type="number" value="1" class="membpress_span2" min="0" max="9999">
      <select>
        <option>Hour(s)</option>
        <option>Day(s)</option>
        <option>Week(s)</option>
        <option selected>Month(s)</option>
        <option>Year(s)</option>
      </select>
      </span> </p>
    <p>
      <label><?php echo _x('Description for this subscription rate plan', 'general', 'membpress'); ?></label>
    </p>
    <p>
      <input type="text" value="Subscription #2 for <?php echo $mp_level['display_name']; ?>" class="membress_span-full">
    </p>
    <p>
      <input type="submit" value="<?php echo _x('Create a new subscription rate for this level', 'general', 'membpress'); ?>" class="button button-primary" name="membpress_settings_submit-membpress_settings_membership_save_subscription">
    </p>
    </span> </div>
</div>
<?php endforeach; ?>
