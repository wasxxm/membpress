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
      <input type="checkbox" <?php if (!get_option('permalink_structure')): $membpress_settings_customize_login_rewrite_flag = false; ?>disabled<?php endif; ?> value="1" name="membpress_settings_customize_login_rewrite_flag" id="membpress_settings_customize_login_rewrite_flag" class="membpress_settings_welcome_login_type" <?php if($membpress_settings_customize_login_rewrite_flag): ?>checked<?php endif; ?>>
      <label for="membpress_settings_customize_login_rewrite_flag"> <?php echo _x('Let the users access the login page [your_site]/wp-login.php by using [your_site]/login as the URL. Uncheck this option if you are already using /login/ for some other page/post to avoid conflicts.', 'general', 'membpress'); ?>
        <?php if (!get_option('permalink_structure')): echo _x('(Please enable the permalinks in Settings -> Permalinks to get this feature working.)', 'general', 'membpress'); endif; ?>
      </label>
    </p>
    <p>
      <input type="checkbox" value="1" name="membpress_settings_customize_login_hide_passforgot" id="membpress_settings_customize_login_hide_passforgot" class="membpress_settings_welcome_login_type" <?php if($membpress_settings_customize_login_hide_passforgot): ?>checked<?php endif; ?>>
      <label for="membpress_settings_customize_login_hide_passforgot"> <?php echo _x('Hide the lost your password link from the login page.', 'general', 'membpress'); ?> </label>
    </p>
    <p>
      <input type="checkbox" value="1" name="membpress_settings_customize_login_hide_bloglink" id="membpress_settings_customize_login_hide_bloglink" class="membpress_settings_welcome_login_type" <?php if($membpress_settings_customize_login_hide_bloglink): ?>checked<?php endif; ?>>
      <label for="membpress_settings_customize_login_hide_bloglink"> <?php echo _x('Hide the back to your wordpress web site link on the login page.', 'general', 'membpress'); ?> </label>
    </p>
    <hr>
    <p>
      <input type="checkbox" value="1" name="membpress_settings_customize_login_page_flag" id="membpress_settings_customize_login_page_flag" class="membpress_settings_welcome_login_type" <?php if($membpress_settings_customize_login_page_flag): ?>checked<?php endif; ?>>
      <label for="membpress_settings_customize_login_page_flag"> <?php echo _x('Enable customization of the login page. Checking this option will change the default theme of your wp-login page. You can customize each bit of styling and elements.', 'general', 'membpress'); ?> </label>
    </p>
    <div class="membpress_login_customize_options <?php if(!$membpress_settings_customize_login_page_flag): ?>membpress_hidden<?php endif; ?>">
      <p> <?php echo _x('Change the default membpress logo with your own company/brand logo. The recommended dimensions are 800 * 220 pixels to ensure good rendering at high definition and large displays.', 'general', 'membpress'); ?> </p>
      <p><img src="<?php echo $membpress_settings_customize_login_logo_url; ?>" width="250px" class="membpress_pull-left" id="membpress_login_logo_holder" />
        <input type="hidden" id="membpress_settings_customize_login_logo_url" name="membpress_settings_customize_login_logo_url" value="<?php echo $membpress_settings_customize_login_logo_url; ?>">
      <div class="membpress_logo_btns">
        <button class="button button-secondary" type="button" id="membpress_settings_customize_login_logo_upload_btn"><?php echo _x('Change logo', 'general', 'membpress'); ?></button>
        <button class="button button-secondary" type="button" id="membpress_settings_customize_login_logo_reset_btn"><?php echo _x('Reset to default', 'general', 'membpress'); ?></button>
      </div>
      </p>
      <div class="membpress_clear"></div>
      <p>
        <label for="membpress_settings_customize_login_page_bg"> <?php echo _x('Customize the background color of the login page:', 'general', 'membpress'); ?> </label>
        <br>
        <input type="text" value="<?php echo $membpress_settings_customize_login_page_bg; ?>" name="membpress_settings_customize_login_page_bg" id="membpress_settings_customize_login_page_bg" class="membpress_settings_welcome_login_type color-field">
        <button type="button" class="button button-small reset-login-color" data-color-value="<?php echo MEMBPRESS_LOGIN_BG_COLOR; ?>" data-element-id="membpress_settings_customize_login_page_bg"><?php echo _x('Reset to default', 'general', 'membpress'); ?></button>
      </p>
      <div class="membpress_clear"></div>
      <p>
        <label for="membpress_settings_customize_login_form_bg"> <?php echo _x('Customize the background color of the login form:', 'general', 'membpress'); ?> </label>
        <br>
        <input type="text" value="<?php echo $membpress_settings_customize_login_form_bg; ?>" name="membpress_settings_customize_login_form_bg" id="membpress_settings_customize_login_form_bg" class="membpress_settings_welcome_login_type color-field">
        <button type="button" class="button button-small reset-login-color" data-color-value="<?php echo MEMBPRESS_LOGIN_FORM_BG_COLOR; ?>" data-element-id="membpress_settings_customize_login_form_bg"><?php echo _x('Reset to default', 'general', 'membpress'); ?></button>
      </p>
      <div class="membpress_clear"></div>
      <p>
        <label for="membpress_settings_customize_login_btn_bg"> <?php echo _x('Customize the background color of the login button:', 'general', 'membpress'); ?> </label>
        <br>
        <input type="text" value="<?php echo $membpress_settings_customize_login_btn_bg; ?>" name="membpress_settings_customize_login_btn_bg" id="membpress_settings_customize_login_btn_bg" class="membpress_settings_welcome_login_type color-field">
        <button type="button" class="button button-small reset-login-color" data-color-value="<?php echo MEMBPRESS_LOGIN_BTN_BG_COLOR; ?>" data-element-id="membpress_settings_customize_login_btn_bg"><?php echo _x('Reset to default', 'general', 'membpress'); ?></button>
      </p>
      <div class="membpress_clear"></div>
      <p>
        <label for="membpress_settings_customize_login_btn_bg"> <?php echo _x('Customize the border color of the login button:', 'general', 'membpress'); ?> </label>
        <br>
        <input type="text" value="<?php echo $membpress_settings_customize_login_btn_border; ?>" name="membpress_settings_customize_login_btn_border" id="membpress_settings_customize_login_btn_border" class="membpress_settings_welcome_login_type color-field">
        <button type="button" class="button button-small reset-login-color" data-color-value="<?php echo MEMBPRESS_LOGIN_BTN_BORDER_COLOR; ?>" data-element-id="membpress_settings_customize_login_btn_border"><?php echo _x('Reset to default', 'general', 'membpress'); ?></button>
      </p>
      <div class="membpress_clear"></div>
      <p>
        <label for="membpress_settings_customize_login_backurl"> <?php echo _x('Specify the target URL for the login screen logo:', 'general', 'membpress'); ?> </label>
        <br>
        <input type="url" name="membpress_settings_customize_login_backurl" id="membpress_settings_customize_login_backurl" value="<?php echo get_option('membpress_settings_customize_login_backurl', MEMBPRESS_LOGIN_BACKURL); ?>" class="membpress_span8">
      </p>
      <p>
        <label for="membpress_settings_customize_login_backurl_title"> <?php echo _x('Specify the title for the login screen logo link:', 'general', 'membpress'); ?> </label>
        <br>
        <input type="text" name="membpress_settings_customize_login_backurl_title" id="membpress_settings_customize_login_backurl_title" value="<?php echo get_option('membpress_settings_customize_login_backurl_title', MEMBPRESS_LOGIN_BACKURL_TITLE); ?>" class="membpress_span8">
      </p>
    </div>
    <div class="membpress_clear"></div>
    <hr>
    <input type="submit" value="<?php echo _x('Save Settings', 'general', 'membpress'); ?>" class="button button-primary" id="membpress_settings_submit" name="membpress_settings_submit-membpress_settings_customize_login_page">
  </div>
</div>
