<?php
/**
* Contains the template for the MembPress Restriction Options Page

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

// level 0
$membpress_membership_name_level_0 = (get_option('membpress_membership_name_level_0') == '') ? MEMBPRESS_LEVEL_0 : get_option('membpress_membership_name_level_0');
// level 1
$membpress_membership_name_level_1 = (get_option('membpress_membership_name_level_1') == '') ? MEMBPRESS_LEVEL_1 : get_option('membpress_membership_name_level_1');
// level 2
$membpress_membership_name_level_2 = (get_option('membpress_membership_name_level_2') == '') ? MEMBPRESS_LEVEL_2 : get_option('membpress_membership_name_level_2');
// level 3
$membpress_membership_name_level_3 = (get_option('membpress_membership_name_level_3') == '') ? MEMBPRESS_LEVEL_3 : get_option('membpress_membership_name_level_3');
// level 4
$membpress_membership_name_level_4 = (get_option('membpress_membership_name_level_4') == '') ? MEMBPRESS_LEVEL_4 : get_option('membpress_membership_name_level_4'); 

// pages list, will be used many times
$pages = get_pages();
// Get only 30 posts
$args = array( 'posts_per_page' => MEMBPRESS_SETTINGS_MAX_POSTS, 'order'=> 'ASC', 'orderby' => 'title' );
$postslist = get_posts( $args );

// get total posts count
$posts_count = wp_count_posts();
$posts_count = $posts_count->publish;

?>

<div class="membpress">
  <div class="wrap" id="poststuff">
    <form method="post" action="<?php echo plugins_url(); ?>/membpress/includes/actions/membpress_setup.action.php" enctype="multipart/form-data">
      <h2 class="membpress_pull-left membpress_settings_heading"> <?php echo _x('MembPress Restriction Options', 'general', 'membpress'); ?></h2>
      <div class="membpress_settings_collapse_expand  membpress_pull-right">
        <div class="dashicons dashicons-arrow-down"></div>
        <a href="javascript:;" class="membpress_settings_expand"><?php echo _x('Expand All', 'general', 'membpress'); ?></a>
        <div class="dashicons dashicons-arrow-up"></div>
        <a href="javascript:;" class="membpress_settings_collapse"><?php echo _x('Collapse All', 'general', 'membpress'); ?></a> </div>
      <div class="membpress_clear"></div>
      <?php
if (isset($_GET['section']) && $_GET['section'] == 'all'):
   $this->mp_helper->membpress_show_update_notice((isset($_GET['notice'])) ? $_GET['notice'] : 1, (isset($_GET['error'])) ? 'error' : 'success', (isset($_GET['n_vars'])) ? $_GET['n_vars'] : '');
endif;
?>
      <div class="meta-box-sortables">
        <?php
if (isset($_GET['section']) && $_GET['section'] == 'membpress_settings_membership_options_page'):
   $this->mp_helper->membpress_show_update_notice((isset($_GET['notice'])) ? $_GET['notice'] : 1, (isset($_GET['error'])) ? 'error' : 'success', (isset($_GET['n_vars'])) ? $_GET['n_vars'] : '');
endif;
?>      
        <!-- Membership Restriction Options section starts below -->
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
        <!-- Membership Restriction Options section ends above -->  
      </div>
      <hr>
      <p>
        <input type="submit" value="<?php echo _x('Save Settings', 'general', 'membpress'); ?>" class="button button-primary" id="membpress_settings_submit" name="membpress_settings_submit">
      </p>
    </form>
  </div>
</div>
