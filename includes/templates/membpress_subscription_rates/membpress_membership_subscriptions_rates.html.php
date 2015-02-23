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
   $this->membpress_show_update_notice((isset($_GET['notice'])) ? $_GET['notice'] : 1, (isset($_GET['error'])) ? 'error' : 'success', (isset($_GET['n_vars'])) ? $_GET['n_vars'] : '');
endif;
?>

<div id="membpress_settings_membership_subscriptions_<?php echo $mp_level['level_no']; ?>" class="postbox<?php if(!isset($_COOKIE['membpress_settings_membership_subscriptions_'.$mp_level['level_no']]) || !$_COOKIE['membpress_settings_membership_subscriptions_'.$mp_level['level_no']]): ?> closed<?php endif; ?>">
  <div class="handlediv" title="Click to toggle"><br>
  </div>
  <h3 class="hndle"><span><?php echo _x('Subscription Rates for Level', 'general', 'membpress'); ?> <?php echo $mp_level['level_no']; ?> (<?php echo $mp_level['display_name']; ?>)</span></h3>
  <div class="inside">
    <?php if ($mp_level['level_no'] == 0): ?>
    <h3><?php echo _x('This is the free subscriber level and will always be FREE. There has to be some level for the memberships to fallback after a paid membership expires.', 'general', 'membpress'); ?></h3>
    <?php else: ?>
    <p> <?php echo _x('Create as many subscription rates for this level as you need. Membpress lets you create three types of subscriptions for a level, "Recurring", "One Time" and "Life Time". Recurring subscriptions are billed repeatedly based on the interval you set. A One Time subscription will automatically end after an interval (you set) without further billing. Life Time is the ultimate subscription where permanent access to a membership level is given.', 'general', 'membpress'); ?> </p>
    <p> <?php echo _x('Charging for the first few days/weeks etc acts as a trial period during which a member can cancel the membership without incurring any charges. Of course, you can also offer a free trial for a limited time, after which the membership will only continue if a payment is made for the subscription.', 'membpress', 'general'); ?> </p>
    <?php
	     
		 // default subscription rate number
		 $subscript_rate_no = 1;
		 
		 // get all the subscription rates for this level
		 $mp_curr_level_subs_rates = $this->membpress_get_subscription_rates_by_level_no($mp_level['level_no']);
		 
		 //var_dump($mp_curr_level_subs_rates);
		 
		 // only continue if the subscription rates are there to display
		 if ($mp_curr_level_subs_rates):
		    
			// set the subscription rate number = total subscription rates + 1
		    $subscript_rate_no = count($mp_curr_level_subs_rates) + 1;
	 ?>
    <?php	
			foreach ($mp_curr_level_subs_rates as $mp_curr_level_subs_rate_key => $mp_curr_level_subs_rate):
	  ?>
    <hr>
    <div class="membpress_subs_rates">
      <p><strong> <?php echo $mp_curr_level_subs_rate['subscription_name']; ?> </strong>
        <button type="button" class="button button-small mp_subs_edit_btn"><?php echo _x('Edit', 'general', 'membpress'); ?></button>
        <button type="button" class="button button-small mp_subs_canceledit_btn" style="display:none"><?php echo _x('Cancel editing', 'general', 'membpress'); ?></button>
        <button type="button" class="button button-small mp_subs_delete_btn"><?php echo _x('Delete', 'general', 'membpress'); ?></button>
      </p>
      <p> - <?php echo _x('Subscription Type is', 'subscriptions', 'membpress'); ?> <strong><?php echo $this->membpress_get_subscription_rates_string($mp_curr_level_subs_rate['type']); ?></strong> </p>
      <p>
        <?php if ($mp_curr_level_subs_rate['trial_charge_duration'] <= 0): // trial period is set to 0 ?>
        - <?php echo _x('No trial/free period is set for this subscription', 'subscriptions', 'membpress'); ?>
        <?php else: // trial period is more than 0 ?>
        <?php if ($mp_curr_level_subs_rate['trial_charge'] <= 0): // trial period charge is 0 ?>
        - <?php echo _x('Free trial is set for', 'subscriptions', 'membpress') ?> <strong><?php echo $mp_curr_level_subs_rate['trial_charge_duration']; ?> <?php echo $this->membpress_get_subscription_rates_string($mp_curr_level_subs_rate['trial_charge_duration_type']); ?></strong>
        <?php else: // trial period charge is more than 0 ?>
        - <?php echo _x(sprintf('Trial charge of %s is set for %s %s', '<strong>$'.$mp_curr_level_subs_rate['trial_charge'].'</strong>', '<strong>'.$mp_curr_level_subs_rate['trial_charge_duration'].'</strong>', '<strong>'.$this->membpress_get_subscription_rates_string($mp_curr_level_subs_rate['trial_charge_duration_type']).'</strong>'), 'subscriptions', 'membpress'); ?>
        <?php endif; ?>
        <?php endif; ?>
        <?php if ($mp_curr_level_subs_rate['normal_charge_duration'] > 0 && $mp_curr_level_subs_rate['normal_charge'] > 0): 
	  // normal rate charge and duration are both greater than 0 ?>
        <?php if ($mp_curr_level_subs_rate['type'] == 'recurring'): ?>
      <p> - <?php echo _x(sprintf('Normal charge of %s is set per %s %s', '<strong>$'.$mp_curr_level_subs_rate['normal_charge'].'</strong>', '<strong>'.$mp_curr_level_subs_rate['normal_charge_duration'].'</strong>', '<strong>'.$this->membpress_get_subscription_rates_string($mp_curr_level_subs_rate['normal_charge_duration_type']).'</strong>'), 'subscriptions', 'membpress'); ?> </p>
      <?php elseif ($mp_curr_level_subs_rate['type'] == 'one_time'): ?>
      <p> - <?php echo _x(sprintf('One Time charge of %s is set for %s %s', '<strong>$'.$mp_curr_level_subs_rate['normal_charge'].'</strong>', '<strong>'.$mp_curr_level_subs_rate['normal_charge_duration'].'</strong>', '<strong>'.$this->membpress_get_subscription_rates_string($mp_curr_level_subs_rate['normal_charge_duration_type']).'</strong>'), 'subscriptions', 'membpress'); ?> </p>
      <?php elseif ($mp_curr_level_subs_rate['type'] == 'life_time'): ?>
      <p> - <?php echo _x(sprintf('Life Time charge of %s is set', '<strong>$'.$mp_curr_level_subs_rate['normal_charge'].'</strong>'), 'subscriptions', 'membpress'); ?> </p>
      <?php endif; ?>
      <?php endif; ?>
      </p>
      <p> - <?php echo _x('No. of subscribers are', 'subscriptions', 'membpress'); ?> <strong><?php echo $total_subs = (int)($mp_curr_level_subs_rate['users_subscribed_active'] + $temp_subs_rate_arr['users_subscribed_expired']); ?></strong> (<?php echo _x('Active', 'subscriptions', 'membpress'); ?>: <strong><?php echo (int)$mp_curr_level_subs_rate['users_subscribed_active']; ?></strong>, <?php echo _x('Expired', 'subscriptions', 'membpress'); ?>: <strong><?php echo (int)$mp_curr_level_subs_rate['users_subscribed_expired']; ?></strong>) </p>
      <!-- Edit form for the current subscription starts -->
      <form method="post" action="<?php echo plugins_url(); ?>/membpress/includes/actions/membpress_subscription_rates.action.php" enctype="multipart/form-data">
        <?php
       // create the nonce field for this page
       wp_nonce_field( 'membpress_subscription_rates_page', 'membpress_subscription_rates_page_nonce' );
      ?>
        <span class="membpress_subs_rate_wrapper membpress_hidden">
        <?php if ($total_subs > 0): ?>
        <p class="membpress_subs_active_warning">
        <?php echo _x('You have users subscribed to this subscription rate. Modifying any of the following settings may affect their subscription for the coming cycle of payment.', 'subscriptions', 'membpress'); ?>
        </p>
        <?php endif; ?>
        <p> <?php echo _x('Subscription Type:', 'subscriptions', 'membpress'); ?>
          <select class="membpress_subs_type" name="membpress_subs_rate_type">
          <?php if ($mp_curr_level_subs_rate['type'] == 'recurring'): ?><option value="recurring" selected><?php echo _x('Recurring', 'subscriptions', 'membpress'); ?></option><?php endif; ?>
          <?php if ($mp_curr_level_subs_rate['type'] == 'one_time'): ?><option value="one_time" selected><?php echo _x('One Time', 'subscriptions', 'membpress'); ?></option><?php endif; ?>
          <?php if ($mp_curr_level_subs_rate['type'] == 'life_time'): ?><option value="life_time" selected><?php echo _x('Life Time', 'subscriptions', 'membpress'); ?></option><?php endif; ?>
          </select>
        </p>
        <p> <?php echo _x('Charge $', 'subscriptions', 'membpress'); ?>
          <input type="number" value="<?php echo $mp_curr_level_subs_rate['trial_charge']; ?>" class="membpress_span2" step="0.01" min="0.00" name="membpress_subs_trial_charge">
          <?php echo _x('for the first', 'subscriptions', 'membpress'); ?>
          <input type="number" value="<?php echo $mp_curr_level_subs_rate['trial_charge_duration']; ?>" class="membpress_span2" min="0" max="9999" step="1" name="membpress_subs_trial_duration">
          <select name="membpress_subs_trial_duration_type">
            <option value="hour" <?php if ($mp_curr_level_subs_rate['trial_charge_duration_type'] == 'hour'): ?>selected<?php endif; ?>><?php echo _x('Hour(s)', 'subscriptions', 'membpress'); ?></option>
            <option value="day" <?php if ($mp_curr_level_subs_rate['trial_charge_duration_type'] == 'day'): ?>selected<?php endif; ?>><?php echo _x('Day(s)', 'subscriptions', 'membpress'); ?></option>
            <option value="week" <?php if ($mp_curr_level_subs_rate['trial_charge_duration_type'] == 'week'): ?>selected<?php endif; ?>><?php echo _x('Week(s)', 'subscriptions', 'membpress'); ?></option>
            <option value="month" <?php if ($mp_curr_level_subs_rate['trial_charge_duration_type'] == 'month'): ?>selected<?php endif; ?>><?php echo _x('Month(s)', 'subscriptions', 'membpress'); ?></option>
            <option value="year" <?php if ($mp_curr_level_subs_rate['trial_charge_duration_type'] == 'year'): ?>selected<?php endif; ?>><?php echo _x('Year(s)', 'subscriptions', 'membpress'); ?></option>
          </select>
        </p>
        <p> <span class="membpress_subs_recurring_txt"><?php echo _x('then start charging', 'subscriptions', 'membpress'); ?></span><span class="membpress_subs_onetime_txt"><?php echo _x('then charge', 'subscriptions', 'membpress'); ?></span><span class="membpress_subs_lifetime_txt"><?php echo _x('then charge', 'subscriptions', 'membpress'); ?></span> $
          <input type="number" value="<?php echo $mp_curr_level_subs_rate['normal_charge']; ?>" class="membpress_span2" step="0.01" min="0.00" name="membpress_subs_charge">
          <span class="membpress_subs_recurring_for"><?php echo _x('for every', 'subscriptions', 'membpress'); ?></span><span class="membpress_subs_onetime_for"><?php echo _x('for', 'subscriptions', 'membpress'); ?></span><span class="membpress_subs_lifetime_for"><?php echo _x('for life-time access', 'subscriptions', 'membpress'); ?></span> <span class="membpress_subs_duration">
          <input type="number" value="<?php echo $mp_curr_level_subs_rate['normal_charge_duration']; ?>" class="membpress_span2" min="0" max="9999" step="1" name="membpress_subs_duration">
          <select name="membpress_subs_duration_type">
            <option value="hour" <?php if($mp_curr_level_subs_rate['normal_charge_duration_type'] == 'hour'): ?>selected<?php endif; ?>><?php echo _x('Hour(s)', 'subscriptions', 'membpress'); ?></option>
            <option value="day" <?php if($mp_curr_level_subs_rate['normal_charge_duration_type'] == 'day'): ?>selected<?php endif; ?>><?php echo _x('Day(s)', 'subscriptions', 'membpress'); ?></option>
            <option value="week" <?php if($mp_curr_level_subs_rate['normal_charge_duration_type'] == 'week'): ?>selected<?php endif; ?>><?php echo _x('Week(s)', 'subscriptions', 'membpress'); ?></option>
            <option value="month" <?php if($mp_curr_level_subs_rate['normal_charge_duration_type'] == 'month'): ?>selected<?php endif; ?>><?php echo _x('Month(s)', 'subscriptions', 'membpress'); ?></option>
            <option value="year" <?php if($mp_curr_level_subs_rate['normal_charge_duration_type'] == 'year'): ?>selected<?php endif; ?>><?php echo _x('Year(s)', 'subscriptions', 'membpress'); ?></option>
          </select>
          </span> </p>
        <p>
          <label><?php echo _x('Name this subscription rate plan', 'general', 'membpress'); ?></label>
        </p>
        <p>
          <input type="text" value="<?php echo $mp_curr_level_subs_rate['subscription_name']; ?>" class="membpress_span-full" name="membpress_subs_rate_name">
        </p>
        <p>
          <input type="hidden" name="membpress_subscription_rate_level" value="<?php echo $mp_level['level_no']; ?>">
          <input type="hidden" name="membpress_subscription_rate_key" value="<?php echo $mp_curr_level_subs_rate_key; ?>">
          <input type="submit" value="<?php echo _x('Save changes', 'general', 'membpress'); ?>" class="button button-primary" name="membpress_edit_subscription_rate">
          <input type="button" value="<?php echo _x('Cancel', 'general', 'membpress'); ?>" class="button button-secondary membpress_subs_cancel">
        </p>
        </span>
      </form>
    </div>
    <!-- end edit form for current subscription -->
    <?php
	        endforeach;
         endif; 
	  ?>
    <hr>
    <form method="post" action="<?php echo plugins_url(); ?>/membpress/includes/actions/membpress_subscription_rates.action.php" enctype="multipart/form-data">
      <?php
       // create the nonce field for this page
       wp_nonce_field( 'membpress_subscription_rates_page', 'membpress_subscription_rates_page_nonce' );
      ?>
      <span class="membpress_subs_rate_wrapper">
      <p> <strong><?php echo _x('Create a new subscription rate for this level', 'general', 'membpress');?></strong> </p>
      <p> <?php echo _x('Subscription Type:', 'subscriptions', 'membpress'); ?>
        <select class="membpress_subs_type" name="membpress_subs_rate_type">
          <option value="recurring"><?php echo _x('Recurring', 'subscriptions', 'membpress'); ?></option>
          <option value="one_time"><?php echo _x('One Time', 'subscriptions', 'membpress'); ?></option>
          <option value="life_time"><?php echo _x('Life Time', 'subscriptions', 'membpress'); ?></option>
        </select>
      </p>
      <p> <?php echo _x('Charge $', 'subscriptions', 'membpress'); ?>
        <input type="number" value="0.00" class="membpress_span2" step="0.01" min="0.00" name="membpress_subs_trial_charge">
        <?php echo _x('for the first', 'subscriptions', 'membpress'); ?>
        <input type="number" value="0" class="membpress_span2" min="0" max="9999" step="1" name="membpress_subs_trial_duration">
        <select name="membpress_subs_trial_duration_type">
          <option value="hour"><?php echo _x('Hour(s)', 'subscriptions', 'membpress'); ?></option>
          <option value="day" selected><?php echo _x('Day(s)', 'subscriptions', 'membpress'); ?></option>
          <option value="week"><?php echo _x('Week(s)', 'subscriptions', 'membpress'); ?></option>
          <option value="month"><?php echo _x('Month(s)', 'subscriptions', 'membpress'); ?></option>
          <option value="year"><?php echo _x('Year(s)', 'subscriptions', 'membpress'); ?></option>
        </select>
      </p>
      <p> <span class="membpress_subs_recurring_txt"><?php echo _x('then start charging', 'subscriptions', 'membpress'); ?></span><span class="membpress_subs_onetime_txt"><?php echo _x('then charge', 'subscriptions', 'membpress'); ?></span><span class="membpress_subs_lifetime_txt"><?php echo _x('then charge', 'subscriptions', 'membpress'); ?></span> $
        <input type="number" value="0.99" class="membpress_span2" step="0.01" min="0.00" name="membpress_subs_charge">
        <span class="membpress_subs_recurring_for"><?php echo _x('for every', 'subscriptions', 'membpress'); ?></span><span class="membpress_subs_onetime_for"><?php echo _x('for', 'subscriptions', 'membpress'); ?></span><span class="membpress_subs_lifetime_for"><?php echo _x('for life-time access', 'subscriptions', 'membpress'); ?></span> <span class="membpress_subs_duration">
        <input type="number" value="1" class="membpress_span2" min="0" max="9999" step="1" name="membpress_subs_duration">
        <select name="membpress_subs_duration_type">
          <option value="hour"><?php echo _x('Hour(s)', 'subscriptions', 'membpress'); ?></option>
          <option value="day"><?php echo _x('Day(s)', 'subscriptions', 'membpress'); ?></option>
          <option value="week"><?php echo _x('Week(s)', 'subscriptions', 'membpress'); ?></option>
          <option value="month" selected><?php echo _x('Month(s)', 'subscriptions', 'membpress'); ?></option>
          <option value="year"><?php echo _x('Year(s)', 'subscriptions', 'membpress'); ?></option>
        </select>
        </span> </p>
      <p>
        <label><?php echo _x('Name this subscription rate plan', 'general', 'membpress'); ?></label>
      </p>
      <p>
        <input type="text" value="<?php printf(_x('Subscription Rate #%d for', 'general', 'membpress'), $subscript_rate_no); ?> <?php echo $mp_level['display_name']; ?>" class="membpress_span-full" name="membpress_subs_rate_name">
      </p>
      <p>
        <input type="hidden" name="membpress_subscription_rate_level" value="<?php echo $mp_level['level_no']; ?>">
        <input type="submit" value="<?php echo _x('Create a new subscription rate', 'general', 'membpress'); ?>" class="button button-primary" name="membpress_create_new_subscription_rate">
      </p>
      </span>
    </form>
    <?php endif; ?>
  </div>
</div>
<?php endforeach; ?>
