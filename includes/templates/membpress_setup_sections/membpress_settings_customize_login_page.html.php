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
if (isset($_GET['section']) && $_GET['section'] == 'membpress_settings_customize_login_page'):
   $this->membpress_show_update_notice((isset($_GET['notice'])) ? $_GET['notice'] : 1, (isset($_GET['error'])) ? 'error' : 'success', (isset($_GET['n_vars'])) ? $_GET['n_vars'] : '');
endif;
?>

<div id="membpress_settings_customize_login_page" class="postbox<?php if(!isset($_COOKIE['membpress_settings_customize_login_page']) || !$_COOKIE['membpress_settings_customize_login_page']): ?> closed<?php endif; ?>">
  <div class="handlediv" title="Click to toggle"><br>
  </div>
  <h3 class="hndle"><span><?php echo _x('Customize Login Page', 'general', 'membpress'); ?></span></h3>
  <div class="inside">
    <p> <?php echo _x('MembPress lets you customize the login page of your wordpress web site to match it more closely with your theme. Below you can find several configurations which let you customize the login screen exactly the way you want it to be.', 'membpress_setup', 'membpress'); ?> </p>
    <p>
      <input type="checkbox" value="1" name="membpress_settings_customize_login_rewrite_flag" id="membpress_settings_customize_login_rewrite_flag" class="membpress_settings_welcome_login_type" <?php if($membpress_settings_customize_login_rewrite_flag): ?>checked<?php endif; ?>>
      <label for="membpress_settings_customize_login_rewrite_flag"> <?php echo _x('Let the users access the login page [your_site]/wp-login.php by using [your_site]/login as the URL. Uncheck this option if you are already using /login/ for some other page/post to avoid conflicts.', 'general', 'membpress'); ?> </label>
    </p>
    <p>
      <input type="checkbox" value="1" name="membpress_settings_customize_login_hide_passforgot" id="membpress_settings_customize_login_hide_passforgot" class="membpress_settings_welcome_login_type" <?php if($membpress_settings_customize_login_hide_passforgot): ?>checked<?php endif; ?>>
      <label for="membpress_settings_customize_login_hide_passforgot"> <?php echo _x('Hide the lost your password link from the login page.', 'general', 'membpress'); ?> </label>
    </p>
    <p>
      <input type="checkbox" value="1" name="membpress_settings_customize_login_hide_bloglink" id="membpress_settings_customize_login_hide_bloglink" class="membpress_settings_welcome_login_type" <?php if($membpress_settings_customize_login_hide_bloglink): ?>checked<?php endif; ?>>
      <label for="membpress_settings_customize_login_hide_bloglink"> <?php echo _x('Hide the back to your wordpress web site link on the login page.', 'general', 'membpress'); ?> </label>
    </p>
    <p>
      <input type="checkbox" value="1" name="membpress_settings_customize_login_page_flag" id="membpress_settings_customize_login_page_flag" class="membpress_settings_welcome_login_type" <?php if($membpress_settings_customize_login_page_flag): ?>checked<?php endif; ?>>
      <label for="membpress_settings_customize_login_page_flag"> <?php echo _x('Enable customization of the login page', 'general', 'membpress'); ?> </label>
    </p>
    <hr>
    <input type="submit" value="<?php echo _x('Save Settings', 'general', 'membpress'); ?>" class="button button-primary" id="membpress_settings_submit" name="membpress_settings_submit-membpress_settings_customize_login_page">
  </div>
</div>