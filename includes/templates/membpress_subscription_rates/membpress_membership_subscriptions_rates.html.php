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
    <?php if ($mp_level['level_no'] == 0): ?>
    <h3><?php echo _x('This is the free subscriber level and will always be FREE. There has to be some level for the memberships to fallback after a paid membership expires.', 'general', 'membpress'); ?></h3>
    <?php else: ?>
    <form method="post" action="<?php echo plugins_url(); ?>/membpress/includes/actions/membpress_subscription_rates.action.php" enctype="multipart/form-data">
      <?php
       // create the nonce field for this page
       wp_nonce_field( 'membpress_subscription_rates_page', 'membpress_subscription_rates_page_nonce' );
      ?>
      <p> <?php echo _x('Create as many subscription rates for this level as you need. Membpress lets you create three types of subscriptions for a level, "Recurring", "One Time" and "Life Time". Recurring subscriptions are billed repeatedly based on the interval you set. A One Time subscription will automatically end after an interval (you set) without further billing. Life Time is the ultimate subscription where permanent access to a membership level is given.', 'general', 'membpress'); ?> </p>
      <p> <?php echo _x('Charging for the first few days/weeks etc acts as a trial period during which a member can cancel the membership without incurring any charges. Of course, you can also offer a free trial for a limited time, after which the membership will only continue if a payment is made for the subscription.', 'membpress', 'general'); ?> </p>
      <?php
	     
		 // default subscription rate number
		 $subscript_rate_no = 1;
		 
		 // get all the subscription rates for this level
		 $mp_curr_level_subs_rates = $this->mp_helper->membpress_get_subscription_rates_by_level_no($mp_level['level_no']);
		 
		 //var_dump($mp_curr_level_subs_rates);
		 
		 // only continue if the subscription rates are there to display
		 if ($mp_curr_level_subs_rates):
		    
			// set the subscription rate number = total subscription rates + 1
		    $subscript_rate_no = count($mp_curr_level_subs_rates) + 1;
	 ?>
      <?php if (count($mp_curr_level_subs_rates)) :?>
      <p><strong><?php echo _x('Subscription Rates for this level.', 'general', 'membpress'); ?></strong></p>
      <?php endif; ?>
      <?php	
			foreach ($mp_curr_level_subs_rates as $mp_curr_level_subs_rate):
	  ?>
      <hr>
      <p> <a href="javascript:;"><strong> <?php echo $mp_curr_level_subs_rate['subscription_name']; ?> </strong></a></p>
      <p> - Subscription Type is <strong><?php echo $this->mp_helper->membpress_get_subscription_rates_string($mp_curr_level_subs_rate['type']); ?></strong> </p>
      <p>
        <?php if ($mp_curr_level_subs_rate['trial_charge_duration'] <= 0): // trial period is set to 0 ?>
        - No trial/free period is set for this subscription
        <?php else: // trial period is more than 0 ?>
        <?php if ($mp_curr_level_subs_rate['trial_charge'] <= 0): // trial period charge is 0 ?>
        - Free trial is set for <strong><?php echo $mp_curr_level_subs_rate['trial_charge_duration']; ?> <?php echo $this->mp_helper->membpress_get_subscription_rates_string($mp_curr_level_subs_rate['trial_charge_duration_type']); ?></strong>
        <?php else: // trial period charge is more than 0 ?>
        - Trial charge of <strong>$<?php echo $mp_curr_level_subs_rate['trial_charge']; ?></strong> is set for <strong><?php echo $mp_curr_level_subs_rate['trial_charge_duration']; ?> <?php echo $this->mp_helper->membpress_get_subscription_rates_string($mp_curr_level_subs_rate['trial_charge_duration_type']); ?></strong>
        <?php endif; ?>
        <?php endif; ?>
      
	  <?php if ($mp_curr_level_subs_rate['normal_charge_duration'] > 0 && $mp_curr_level_subs_rate['normal_charge'] > 0): 
	  // normal rate charge and duration are both greater than 0 ?>
         <?php if ($mp_curr_level_subs_rate['type'] == 'recurring'): ?>
      <p> - Normal charge of <strong>$<?php echo $mp_curr_level_subs_rate['normal_charge']; ?></strong> is set per <strong><?php echo $mp_curr_level_subs_rate['normal_charge_duration']; ?> <?php echo $this->mp_helper->membpress_get_subscription_rates_string($mp_curr_level_subs_rate['normal_charge_duration_type']); ?></strong> </p>
            <?php elseif ($mp_curr_level_subs_rate['type'] == 'one_time'): ?>
      <p> - One Time charge of <strong>$<?php echo $mp_curr_level_subs_rate['normal_charge']; ?></strong> is set for <strong><?php echo $mp_curr_level_subs_rate['normal_charge_duration']; ?> <?php echo $this->mp_helper->membpress_get_subscription_rates_string($mp_curr_level_subs_rate['normal_charge_duration_type']); ?></strong> </p>  
            <?php elseif ($mp_curr_level_subs_rate['type'] == 'life_time'): ?>
       <p> - Life Time charge of <strong>$<?php echo $mp_curr_level_subs_rate['normal_charge']; ?></strong> is set</p>   
         <?php endif; ?>
	  <?php endif; ?>
      </p>
      <?php
	        endforeach;
         endif; 
	  ?>
      <hr>
      <span class="membpress_subs_rate_wrapper">
      <p> <strong><?php echo _x('Create a new subscription rate for this level', 'general', 'membpress');?></strong> </p>
      <p> Subscription Type:
        <select class="membpress_subs_type" name="membpress_subs_rate_type">
          <option value="recurring">Recurring</option>
          <option value="one_time">One Time</option>
          <option value="life_time">Life Time</option>
        </select>
      </p>
      <p> Charge $
        <input type="number" value="0.00" class="membpress_span2" step="0.01" min="0.00" name="membpress_subs_trial_charge">
        for the first
        <input type="number" value="0" class="membpress_span2" min="0" max="9999" step="1" name="membpress_subs_trial_duration">
        <select name="membpress_subs_trial_duration_type">
          <option value="hour">Hour(s)</option>
          <option value="day">Day(s)</option>
          <option value="week">Week(s)</option>
          <option value="month">Month(s)</option>
          <option value="year">Year(s)</option>
        </select>
      </p>
      <p> <span class="membpress_subs_recurring_txt">then start charging</span><span class="membpress_subs_onetime_txt">then charge</span><span class="membpress_subs_lifetime_txt">then charge</span> $
        <input type="number" value="0.99" class="membpress_span2" step="0.01" min="0.00" name="membpress_subs_charge">
        <span class="membpress_subs_recurring_for">for every</span><span class="membpress_subs_onetime_for">for</span><span class="membpress_subs_lifetime_for">for life-time access</span> <span class="membpress_subs_duration">
        <input type="number" value="1" class="membpress_span2" min="0" max="9999" step="1" name="membpress_subs_duration">
        <select name="membpress_subs_duration_type">
          <option value="hour">Hour(s)</option>
          <option value="day">Day(s)</option>
          <option value="week">Week(s)</option>
          <option value="month" selected>Month(s)</option>
          <option value="year">Year(s)</option>
        </select>
        </span> </p>
      <p>
        <label><?php echo _x('Name this subscription rate plan', 'general', 'membpress'); ?></label>
      </p>
      <p>
        <input type="text" value="<?php printf(_x('Subscription Rate #%d for', 'general', 'membpress'), $subscript_rate_no); ?> <?php echo $mp_level['display_name']; ?>" class="membress_span-full" name="membpress_subs_rate_name">
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
