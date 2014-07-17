<?php
/**
* Contains the html for Restrict Post section in Restriction Options Page

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
<div id="membpress_restrict_posts" class="postbox<?php if(!isset($_COOKIE['membpress_restrict_posts']) || !$_COOKIE['membpress_restrict_posts']): ?> closed<?php endif; ?>">
          <div class="handlediv" title="Click to toggle"><br>
          </div>
          <h3 class="hndle"><span><?php echo _x('Restrict Posts', 'general', 'membpress'); ?></span></h3>
          <div class="inside">
            <p> <?php echo _x('MembPress lets you restrict any number of posts by binding them to different membership levels. You can enter the IDs of the posts (in a comma separated way like 12,10,5) you want to restrict against each membership level. MembPress will make those posts restricted and only the user with the required membership level will be able to access them. Any such attempt without required membership level will redirect the user to MemberShip Options Page (can be configured in \'Basic Setup -> Membership Options Page\').', 'membpress_restrict', 'membpress'); ?> </p>
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