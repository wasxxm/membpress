<?php
/**
* Contains the html for Membership Options Page in MembPress Basic Setup

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
if (isset($_GET['section']) && $_GET['section'] == 'membpress_settings_membership_options_page'):
   $this->mp_helper->membpress_show_update_notice((isset($_GET['notice'])) ? $_GET['notice'] : 1, (isset($_GET['error'])) ? 'error' : 'success', (isset($_GET['n_vars'])) ? $_GET['n_vars'] : '');
endif;
?>

<div id="membpress_settings_membership_options_page" class="postbox<?php if(!isset($_COOKIE['membpress_settings_membership_options_page']) || !$_COOKIE['membpress_settings_membership_options_page']): ?> closed<?php endif; ?>">
          <div class="handlediv" title="Click to toggle"><br>
          </div>
          <h3 class="hndle"><span><?php echo _x('Membership Options Page', 'general', 'membpress'); ?></span></h3>
          <div class="inside">
            <p> <?php echo _x('Membship Options page is the page where you list your MembPress Membership Levels with payment buttons for subscription. Please create a page with a title like: "Membership Signup" and include all the membership levels available for subscription with payment methods. This will be the page where users will get redirected to, if they access any restricted page like "Welcome Page after Login" or any other restricted page/post/section (of course the users with the relevant membership level will not be redirected).', 'membpress_setup', 'membpress'); ?> </p>
            <p>
              <label for="membpress_settings_membership_option_page"><?php echo _x('Select Membership Options Page:', 'membpress_setup', 'membpress'); ?></label>
              <select name="membpress_settings_membership_option_page" id="membpress_settings_membership_option_page" class="membpress_settings_membership_option_page">
                <option value="">-- <?php echo _x('Select a Page', 'general', 'membpress'); ?> --</option>
                <?php  
      foreach ( $pages as $page )
	  {
		$option = '<option value="' . $page->ID . '"';
		if ($membpress_settings_membership_option_page == $page->ID)
		{
		   $option .= ' selected ';	
		}
		$option .= '>';
        $option .= $page->post_title;
        $option .= '</option>';
        echo $option;
      }
     ?>
              </select>
            </p>
            <hr>
            <input type="submit" value="<?php echo _x('Save Settings', 'general', 'membpress'); ?>" class="button button-primary" id="membpress_settings_submit" name="membpress_settings_submit-membpress_settings_membership_options_page">
          </div>
        </div>