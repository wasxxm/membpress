<?php
/**
* Contains the template for membpress basic setup/settings

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

$membpress_settings_welcome_login_type = get_option('membpress_settings_welcome_login_type');
if ($membpress_settings_welcome_login_type == '')
   $membpress_settings_welcome_login_type = 'page';

$membpress_settings_welcome_login_page = get_option('membpress_settings_welcome_login_page');
$membpress_settings_welcome_login_post = get_option('membpress_settings_welcome_login_post');
$membpress_settings_welcome_login_url = get_option('membpress_settings_welcome_login_url');
$membpress_settings_welcome_login_restrict = (bool)get_option('membpress_settings_welcome_login_restrict');
$membpress_settings_welcome_login_individual = (bool)get_option('membpress_settings_welcome_login_individual');

$membpress_settings_membership_option_page = get_option('membpress_settings_membership_option_page');

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
      <h2 class="membpress_pull-left membpress_settings_heading"> <?php echo _x('MembPress Basic Setup/Settings', 'general', 'membpress'); ?></h2>
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
        <div id="membpress_settings_welcome_page_login" class="postbox<?php if(!isset($_COOKIE['membpress_settings_welcome_page_login']) || !$_COOKIE['membpress_settings_welcome_page_login']): ?> closed<?php endif; ?>">
          <div class="handlediv" title="Click to toggle"><br>
          </div>
          <h3 class="hndle"><span><?php echo _x('Welcome Page after Login', 'general', 'membpress'); ?></span></h3>
          <div class="inside">
            <p> <?php echo _x('Specify the welcome page after a successful login by the user. This can be any page or post of your choice. However, you cannot set a page or post which is always publicly visible as the welcome screen, like Home Page, Home Blog Post. MembPress will automatically set the welcome page/post as restricted (not publicly viewable) and will only be visible once a user is logged in (configurable). Any such restricted post/page will redirect to the', 'membpress_setup', 'membpress'); ?> <a href="javascript:;" rel="#membpress_settings_membership_options_page" class="membpress_goto_section"><?php echo _x('Membership Options Page', 'membpress_setup', 'membpress'); ?></a><?php echo _x(', if accessed publicly or the user does not have the required membership level.', 'membpress_setup', 'membpress'); ?>
              <?php wp_nonce_field( 'membpress_settings_page', 'membpress_settings_page_nonce' ); ?>
            </p>
            <div id="mempress_welcome_login_group_all" <?php if($membpress_settings_welcome_login_individual == 1): ?>class="membpress_hidden"<?php endif; ?>>
              <p> <strong><?php echo _x('The setting below applies to all user roles/levels, including all the MembPress membership levels. Only exception is Administrator.', 'membpress_setup', 'membpress'); ?></strong> </p>
              <div class="membpress_welcome_login_group">
                <p>
                  <input type="radio" name="membpress_settings_welcome_login_type" value="page" id="membpress_settings_welcome_login_type_page" class="membpress_settings_welcome_login_type" <?php if($membpress_settings_welcome_login_type == 'page'): ?>checked<?php endif; ?>>
                  <label for="membpress_settings_welcome_login_type_page"><span class="dashicons dashicons-admin-page"></span> <?php echo _x('Page', 'general', 'membpress'); ?> </label>
                  <input type="radio" name="membpress_settings_welcome_login_type" value="post" id="membpress_settings_welcome_login_type_post" <?php if($membpress_settings_welcome_login_type == 'post'): ?>checked<?php endif; ?> class="membpress_settings_welcome_login_type">
                  <label for="membpress_settings_welcome_login_type_post"><span class="dashicons dashicons-admin-post"></span> <?php echo _x('Post', 'general', 'membpress'); ?> </label>
                  <input type="radio" name="membpress_settings_welcome_login_type" value="url" id="membpress_settings_welcome_login_type_url" <?php if($membpress_settings_welcome_login_type == 'url'): ?>checked<?php endif; ?> class="membpress_settings_welcome_login_type">
                  <label for="membpress_settings_welcome_login_type_url"><span class="dashicons dashicons-admin-links"></span> <?php echo _x('URL', 'general', 'membpress'); ?> </label>
                </p>
                <p class="membpress_hidden" <?php if($membpress_settings_welcome_login_type == 'page'): ?>style="display:block"<?php endif; ?>>
                  <label for="membpress_settings_welcome_login_page"> <?php echo _x('Select Welcome Page:', 'general', 'membpress')?> </label>
                  <select name="membpress_settings_welcome_login_page" id="membpress_settings_welcome_login_page" class="membpress_settings_welcome_login_page">
                    <option value="">-- <?php echo _x('Select a Page', 'general', 'membpress'); ?> --</option>
                    <?php  
      foreach ( $pages as $page )
	  {
		$option = '<option value="' . $page->ID . '"';
		if ($membpress_settings_welcome_login_page == $page->ID)
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
                <p class="membpress_hidden" <?php if($membpress_settings_welcome_login_type == 'post'): ?>style="display:block"<?php endif; ?>>
                <?php if($posts_count <= MEMBPRESS_SETTINGS_MAX_POSTS): ?>
                  <label for="membpress_settings_welcome_login_post"> <?php echo _x('Select Welcome Post:', 'general', 'membpress')?> </label>
                  <select name="membpress_settings_welcome_login_post" id="membpress_settings_welcome_login_post" class="membpress_settings_welcome_login_post">
                    <option value="">-- <?php echo _x('Select a Post', 'general', 'membpress'); ?> --</option>
                    <?php
    foreach ( $postslist as $post ) :
      setup_postdata( $post ); ?>
                    <option value="<?php echo $post->ID; ?>" <?php if($membpress_settings_welcome_login_post == $post->ID): ?>selected<?php endif; ?>> <?php echo $post->post_title; ?> </option>
                    <?php
    endforeach; 
    wp_reset_postdata();
    ?>
                  </select>
                  <?php else: ?>
                  <label><?php echo _x('Specify Welcome Post ID:', 'general', 'membpress')?></label>
                  <input name="membpress_settings_welcome_login_post" id="membpress_settings_welcome_login_post" value="<?php echo $membpress_settings_welcome_login_post; ?>" class="membpress_settings_welcome_login_post" type="text">
                  <?php endif; ?>
                </p>
                <p class="membpress_hidden" <?php if($membpress_settings_welcome_login_type == 'url'): ?>style="display:block"<?php endif; ?>>
                  <label for="membpress_settings_welcome_login_url"> <?php echo _x('Select Welcome URL:', 'general', 'membpress')?> </label>
                  <input name="membpress_settings_welcome_login_url" id="membpress_settings_welcome_login_url" type="text" placeholder="http://" value="<?php echo $membpress_settings_welcome_login_url; ?>" class="membpress_settings_welcome_login_url">
                  <br>
                  <br>
                  <small> <?php echo _x('Must be a fully resolved URL like: http://www.membpress.com/welcome-page<br>Please make sure the URL is correct, else the users other than the administrators might not be able to login.', 'membpress_setup', 'membpress'); ?> </small> </p>
                <p>
                  <input type="checkbox" name="membpress_settings_welcome_login_restrict" id="membpress_settings_welcome_login_restrict" <?php if($membpress_settings_welcome_login_restrict == 1): ?>checked<?php endif; ?> value="1">
                  <label for="membpress_settings_welcome_login_restrict"> <?php echo _x('Do not make the page/post restricted', 'membpress_setup', 'membpress'); ?> </label>
                </p>
              </div>
            </div>
            <p>
              <input type="checkbox" name="membpress_settings_welcome_login_individual" id="membpress_settings_welcome_login_individual" <?php if($membpress_settings_welcome_login_individual == 1): ?>checked<?php endif; ?> value="1">
              <label for="membpress_settings_welcome_login_individual"> <strong><?php echo _x('Specify the login welcome redirects for individual MembPress levels', 'membpress_setup', 'membpress'); ?></strong> </label>
            </p>
            <div <?php if($membpress_settings_welcome_login_individual == 0): ?>class="membpress_hidden"<?php endif; ?> id="membpress_welcome_login_individual">
    <p>
    <?php echo _x('Please note that a higher membership level will take precedence over all the lower membership levels regarding the login redirect settings. So if a user has membership level 4, the user will be able to access the login welcome restricted pages of level 3, 2, 1 and 0. But a user with level 3 won\'t be able to access a page/post exclusively assigned to level 4, doing so will redirect the user to membership options page.', 'membpress_setup', 'membpress'); ?>
    </p>
	
	<?php
	  
    $roles = get_editable_roles();
	
	// iterate through each role to assign the login welcome page/post/url
	foreach($roles as $role_key => $role_val):
	
	// check if the role is defined by MembPress like membpress_level_1
	// include the subscriber role as the Membpress Level 0 /Free Level
	if (stristr($role_key, 'membpress_level_') === FALSE && $role_key != 'subscriber')
	{
	   continue; //skip the rest of the code and move to next role in array
	}
	
	$membpress_settings_welcome_login_type = get_option('membpress_settings_welcome_login_type_' . $role_key);
    if ($membpress_settings_welcome_login_type == '')
    $membpress_settings_welcome_login_type = 'page';

    $membpress_settings_welcome_login_page = get_option('membpress_settings_welcome_login_page_' . $role_key);
    $membpress_settings_welcome_login_post = get_option('membpress_settings_welcome_login_post_' . $role_key);
    $membpress_settings_welcome_login_url = get_option('membpress_settings_welcome_login_url_' . $role_key);
	
	$membpress_settings_welcome_login_restrict = (bool)get_option('membpress_settings_welcome_login_restrict_' . $role_key);
	
	$role_name = $role_val['name'];
	
	if ($role_key == 'subscriber')
	    $role_name = _x('Subscriber (MembPress Level 0 - Free)', 'general', 'membpress');
	
	?>
              <hr>
              <div class="membpress_welcome_login_group">
                <h4><?php echo $role_name; ?></h4>
                <p>
                  <input type="radio" name="membpress_settings_welcome_login_type_<?php echo $role_key; ?>" value="page" id="membpress_settings_welcome_login_type_page_<?php echo $role_key; ?>" class="membpress_settings_welcome_login_type" <?php if($membpress_settings_welcome_login_type == 'page'): ?>checked<?php endif; ?>>
                  <label for="membpress_settings_welcome_login_type_page_<?php echo $role_key; ?>"><span class="dashicons dashicons-admin-page"></span> <?php echo _x('Page', 'general', 'membpress'); ?> </label>
                  <input type="radio" name="membpress_settings_welcome_login_type_<?php echo $role_key; ?>" value="post" id="membpress_settings_welcome_login_type_post_<?php echo $role_key; ?>" <?php if($membpress_settings_welcome_login_type == 'post'): ?>checked<?php endif; ?> class="membpress_settings_welcome_login_type">
                  <label for="membpress_settings_welcome_login_type_post_<?php echo $role_key; ?>"><span class="dashicons dashicons-admin-post"></span> <?php echo _x('Post', 'general', 'membpress'); ?> </label>
                  <input type="radio" name="membpress_settings_welcome_login_type_<?php echo $role_key; ?>" value="url" id="membpress_settings_welcome_login_type_url_<?php echo $role_key; ?>" <?php if($membpress_settings_welcome_login_type == 'url'): ?>checked<?php endif; ?> class="membpress_settings_welcome_login_type">
                  <label for="membpress_settings_welcome_login_type_url_<?php echo $role_key; ?>"><span class="dashicons dashicons-admin-links"></span> <?php echo _x('URL', 'membpress', 'membpress'); ?> </label>
                </p>
                <p class="membpress_hidden" <?php if($membpress_settings_welcome_login_type == 'page'): ?>style="display:block"<?php endif; ?>>
                  <label for="membpress_settings_welcome_login_page_<?php echo $role_key; ?>"> <?php echo _x('Select Welcome Page:', 'general', 'membpress')?> </label>
                  <select name="membpress_settings_welcome_login_page_<?php echo $role_key; ?>" id="membpress_settings_welcome_login_page_<?php echo $role_key; ?>" class="membpress_settings_welcome_login_page">
                    <option value="">-- <?php echo _x('Select a Page', 'general', 'membpress'); ?> --</option>
                    <?php 
      foreach ( $pages as $page )
	  {
		$option = '<option value="' . $page->ID . '"';
		if ($membpress_settings_welcome_login_page == $page->ID)
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
                <p class="membpress_hidden" <?php if($membpress_settings_welcome_login_type == 'post'): ?>style="display:block"<?php endif; ?>>
                <?php if($posts_count <= MEMBPRESS_SETTINGS_MAX_POSTS): ?>
                  <label for="membpress_settings_welcome_login_post_<?php echo $role_key; ?>"> <?php echo _x('Select Welcome Post:', 'membpress', 'membpress')?> </label>
                  <select name="membpress_settings_welcome_login_post_<?php echo $role_key; ?>" id="membpress_settings_welcome_login_post_<?php echo $role_key; ?>" class="membpress_settings_welcome_login_post">
                    <option value="">-- <?php echo _x('Select a Post', 'membpress', 'membpress'); ?> --</option>
                    <?php
    foreach ( $postslist as $post ) :
      setup_postdata( $post ); ?>
                    <option value="<?php echo $post->ID; ?>" <?php if($membpress_settings_welcome_login_post == $post->ID): ?>selected<?php endif; ?>> <?php echo $post->post_title; ?> </option>
                    <?php
    endforeach; 
    wp_reset_postdata();
    ?>
                  </select>
                  <?php else: ?>
                  <label for="membpress_settings_welcome_login_post_<?php echo $role_key; ?>"> <?php echo _x('Specify Welcome Post ID:', 'membpress', 'membpress')?> </label>
                   <input name="membpress_settings_welcome_login_post_<?php echo $role_key; ?>" id="membpress_settings_welcome_login_post_<?php echo $role_key; ?>" class="membpress_settings_welcome_login_post" type="text" value="<?php echo $membpress_settings_welcome_login_post; ?>">
                  <?php endif; ?>
                </p>
                <p class="membpress_hidden" <?php if($membpress_settings_welcome_login_type == 'url'): ?>style="display:block"<?php endif; ?>>
                  <label for="membpress_settings_welcome_login_url_<?php echo $role_key; ?>"> <?php echo _x('Select Welcome URL:', 'general', 'membpress')?> </label>
                  <input name="membpress_settings_welcome_login_url_<?php echo $role_key; ?>" id="membpress_settings_welcome_login_url_<?php echo $role_key; ?>" type="text" placeholder="http://" value="<?php echo $membpress_settings_welcome_login_url; ?>" class="membpress_settings_welcome_login_url">
                  <br>
                  <br>
                  <small> <?php echo _x('Must be a fully resolved URL like: http://www.membpress.com/welcome-page<br>Please make sure the URL is correct, else the users other than the administrators might not be able to login.', 'membpress_setup', 'membpress'); ?> </small> </p>
                <p>
                  <input type="checkbox" name="membpress_settings_welcome_login_restrict_<?php echo $role_key; ?>" id="membpress_settings_welcome_login_restrict_<?php echo $role_key; ?>" <?php if($membpress_settings_welcome_login_restrict == 1): ?>checked<?php endif; ?> value="1">
                  <label for="membpress_settings_welcome_login_restrict_<?php echo $role_key; ?>"> <?php echo _x('Do not make the page/post restricted', 'membpress_setup', 'membpress'); ?> </label>
                </p>
              </div>
              <!-- End .membpress_welcome_login_group -->
              <?php endforeach; ?>
            </div>
            <!-- End #membpress_welcome_login_individual -->
            <hr>
            <p>
              <input type="submit" value="<?php echo _x('Save Settings', 'general', 'membpress'); ?>" class="button button-primary" id="membpress_settings_submit" name="membpress_settings_submit-membpress_settings_welcome_page_login">
            </p>
          </div>
        </div>
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
        <!-- Membership Options Page section ends above --> 
        
        <!-- Membership Levels section starts below -->
 <?php
if (isset($_GET['section']) && $_GET['section'] == 'membpress_settings_membership_levels'):
   $this->mp_helper->membpress_show_update_notice((isset($_GET['notice'])) ? $_GET['notice'] : 1, (isset($_GET['error'])) ? 'error' : 'success', (isset($_GET['n_vars'])) ? $_GET['n_vars'] : '');
endif;
?>
        <div id="membpress_settings_membership_levels" class="postbox<?php if(!isset($_COOKIE['membpress_settings_membership_levels']) || !$_COOKIE['membpress_settings_membership_levels']): ?> closed<?php endif; ?>">
          <div class="handlediv" title="Click to toggle"><br>
          </div>
          <h3 class="hndle"><span><?php echo _x('Membership Levels', 'general', 'membpress'); ?></span></h3>
          <div class="inside">
            <p> <?php echo _x('Membpress gives you four membership levels to start with. These levels should be enough for most of the cases. The membpress levels range from Level 1 to Level 4. Level 0 is the Wordpress built-in \'Subscriber\' level. In this section you can rename all the membership levels to suit your membership plans.', 'membpress_setup', 'membpress'); ?> </p>
            <p>
              <label><?php echo _x('Membership Level 0', 'general', 'membpress'); ?>: </label>
              <input type="text" name="membpress_membership_name_level_0" value="<?php echo $membpress_membership_name_level_0; ?>"> <small>(<?php echo _x('Wordpress built-in Subscriber', 'general', 'membpress'); ?>)</small>
            </p>
            <p>
              <label><?php echo _x('Membership Level 1', 'general', 'membpress'); ?>: </label>
              <input type="text" name="membpress_membership_name_level_1" value="<?php echo $membpress_membership_name_level_1; ?>">
            </p>
            <p>
              <label><?php echo _x('Membership Level 2', 'general', 'membpress'); ?>: </label>
              <input type="text" name="membpress_membership_name_level_2" value="<?php echo $membpress_membership_name_level_2; ?>">
            </p>
            <p>
              <label><?php echo _x('Membership Level 3', 'general', 'membpress'); ?>: </label>
              <input type="text" name="membpress_membership_name_level_3" value="<?php echo $membpress_membership_name_level_3; ?>">
            </p>
            <p>
              <label><?php echo _x('Membership Level 4', 'general', 'membpress'); ?>: </label>
              <input type="text" name="membpress_membership_name_level_4" value="<?php echo $membpress_membership_name_level_4; ?>">
            </p>
            
            <?php 
			// iterate through the maximum membership levels, defined in membpress.config.php
			// only if the levels are greater than 4
			for($i = 5; $i <= MEMBPRESS_LEVEL_COUNT; $i++):
            
			// check if the membership level name is empty, if yes then put 'Membership level #' as value
			$membership_level_name = (trim(get_option('membpress_membership_name_level_'.$i)) == '' ? _x('Membership Level', 'general', 'membpress'). " $i" : trim(get_option('membpress_membership_name_level_'.$i)));
			
			?>
            <p>
              <label><?php echo _x('Membership Level', 'general', 'membpress'); ?> <?php echo $i; ?>: </label>
              <input type="text" name="membpress_membership_name_level_<?php echo $i; ?>" value="<?php echo $membership_level_name; ?>">
            </p>
            <?php endfor; ?>
            <hr>
            <input type="submit" value="<?php echo _x('Save Settings', 'general', 'membpress'); ?>" class="button button-primary" id="membpress_settings_submit" name="membpress_settings_submit-membpress_settings_membership_levels">
          </div>
        </div>
        <!-- Membership levels section ends above --> 
      </div>
      <hr>
      <p>
        <input type="submit" value="<?php echo _x('Save Settings', 'general', 'membpress'); ?>" class="button button-primary" id="membpress_settings_submit" name="membpress_settings_submit">
      </p>
    </form>
  </div>
</div>
