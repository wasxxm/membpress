<?php
/**
* Contains the html for Welcome Page After Login in MembPress Basic Setup

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
<div id="membpress_settings_welcome_page_login" class="postbox<?php if(!isset($_COOKIE['membpress_settings_welcome_page_login']) || !$_COOKIE['membpress_settings_welcome_page_login']): ?> closed<?php endif; ?>">
          <div class="handlediv" title="Click to toggle"><br>
          </div>
          <h3 class="hndle"><span><?php echo _x('Welcome Page after Login', 'general', 'membpress'); ?></span></h3>
          <div class="inside">
            <p> <?php echo _x('Specify the welcome page after a successful login by the user. This can be any page or post of your choice. However, you cannot set a page or post which is always publicly visible as the welcome screen, like Home Page, Home Blog Post. MembPress will automatically set the welcome page/post as restricted (not publicly viewable) and will only be visible once a user is logged in (configurable). Any such restricted post/page will redirect to the', 'membpress_setup', 'membpress'); ?> <a href="javascript:;" rel="#membpress_settings_membership_options_page" class="membpress_goto_section"><?php echo _x('Membership Options Page', 'membpress_setup', 'membpress'); ?></a><?php echo _x(', if accessed publicly or the user does not have the required membership level.', 'membpress_setup', 'membpress'); ?>
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
                <p class="membpress_settings_welcome_login_restrict_checkbox <?php if($membpress_settings_welcome_login_type == 'url'): ?>membpress_hidden<?php endif; ?>">
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
	
	/*  
    $roles = get_editable_roles();
	
	// iterate through each role to assign the login welcome page/post/url
	foreach($roles as $mp_level['level_no'] => $role_val):
	
	// check if the role is defined by MembPress like membpress_level_1
	// include the subscriber role as the Membpress Level 0 /Free Level
	if (stristr($mp_level['level_no'], 'membpress_level_') === FALSE && $mp_level['level_no'] != 'subscriber')
	{
	   continue; //skip the rest of the code and move to next role in array
	}
	*/
	foreach($mp_levels as $mp_level):
	
	$membpress_settings_welcome_login_type = get_option('membpress_settings_welcome_login_type_' . $mp_level['level_no']);
    if ($membpress_settings_welcome_login_type == '')
    $membpress_settings_welcome_login_type = 'page';

    $membpress_settings_welcome_login_page = get_option('membpress_settings_welcome_login_page_' . $mp_level['level_no']);
    $membpress_settings_welcome_login_post = get_option('membpress_settings_welcome_login_post_' . $mp_level['level_no']);
    $membpress_settings_welcome_login_url = get_option('membpress_settings_welcome_login_url_' . $mp_level['level_no']);
	
	$membpress_settings_welcome_login_restrict = (bool)get_option('membpress_settings_welcome_login_restrict_' . $mp_level['level_no']);
	
	$role_name = $mp_level['display_name'] . ' ' . _x(sprintf('(MembPress Level %d)', $mp_level['level_no']), 'general', 'membpress');
	
	if ($mp_level['level_no'] == 0)
	    $role_name = $mp_level['display_name'] . ' ' . _x('(MembPress Level 0 - Subscriber)', 'general', 'membpress');
	
	?>
              <hr>
              <div class="membpress_welcome_login_group">
                <h4><?php echo $role_name; ?></h4>
                <p>
                  <input type="radio" name="membpress_settings_welcome_login_type_<?php echo $mp_level['level_no']; ?>" value="page" id="membpress_settings_welcome_login_type_page_<?php echo $mp_level['level_no']; ?>" class="membpress_settings_welcome_login_type" <?php if($membpress_settings_welcome_login_type == 'page'): ?>checked<?php endif; ?>>
                  <label for="membpress_settings_welcome_login_type_page_<?php echo $mp_level['level_no']; ?>"><span class="dashicons dashicons-admin-page"></span> <?php echo _x('Page', 'general', 'membpress'); ?> </label>
                  <input type="radio" name="membpress_settings_welcome_login_type_<?php echo $mp_level['level_no']; ?>" value="post" id="membpress_settings_welcome_login_type_post_<?php echo $mp_level['level_no']; ?>" <?php if($membpress_settings_welcome_login_type == 'post'): ?>checked<?php endif; ?> class="membpress_settings_welcome_login_type">
                  <label for="membpress_settings_welcome_login_type_post_<?php echo $mp_level['level_no']; ?>"><span class="dashicons dashicons-admin-post"></span> <?php echo _x('Post', 'general', 'membpress'); ?> </label>
                  <input type="radio" name="membpress_settings_welcome_login_type_<?php echo $mp_level['level_no']; ?>" value="url" id="membpress_settings_welcome_login_type_url_<?php echo $mp_level['level_no']; ?>" <?php if($membpress_settings_welcome_login_type == 'url'): ?>checked<?php endif; ?> class="membpress_settings_welcome_login_type">
                  <label for="membpress_settings_welcome_login_type_url_<?php echo $mp_level['level_no']; ?>"><span class="dashicons dashicons-admin-links"></span> <?php echo _x('URL', 'membpress', 'membpress'); ?> </label>
                </p>
                <p class="membpress_hidden" <?php if($membpress_settings_welcome_login_type == 'page'): ?>style="display:block"<?php endif; ?>>
                  <label for="membpress_settings_welcome_login_page_<?php echo $mp_level['level_no']; ?>"> <?php echo _x('Select Welcome Page:', 'general', 'membpress')?> </label>
                  <select name="membpress_settings_welcome_login_page_<?php echo $mp_level['level_no']; ?>" id="membpress_settings_welcome_login_page_<?php echo $mp_level['level_no']; ?>" class="membpress_settings_welcome_login_page">
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
                  <label for="membpress_settings_welcome_login_post_<?php echo $mp_level['level_no']; ?>"> <?php echo _x('Select Welcome Post:', 'membpress', 'membpress')?> </label>
                  <select name="membpress_settings_welcome_login_post_<?php echo $mp_level['level_no']; ?>" id="membpress_settings_welcome_login_post_<?php echo $mp_level['level_no']; ?>" class="membpress_settings_welcome_login_post">
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
                  <label for="membpress_settings_welcome_login_post_<?php echo $mp_level['level_no']; ?>"> <?php echo _x('Specify Welcome Post ID:', 'membpress', 'membpress')?> </label>
                   <input name="membpress_settings_welcome_login_post_<?php echo $mp_level['level_no']; ?>" id="membpress_settings_welcome_login_post_<?php echo $mp_level['level_no']; ?>" class="membpress_settings_welcome_login_post" type="text" value="<?php echo $membpress_settings_welcome_login_post; ?>">
                  <?php endif; ?>
                </p>
                <p class="membpress_hidden" <?php if($membpress_settings_welcome_login_type == 'url'): ?>style="display:block"<?php endif; ?>>
                  <label for="membpress_settings_welcome_login_url_<?php echo $mp_level['level_no']; ?>"> <?php echo _x('Select Welcome URL:', 'general', 'membpress')?> </label>
                  <input name="membpress_settings_welcome_login_url_<?php echo $mp_level['level_no']; ?>" id="membpress_settings_welcome_login_url_<?php echo $mp_level['level_no']; ?>" type="text" placeholder="http://" value="<?php echo $membpress_settings_welcome_login_url; ?>" class="membpress_settings_welcome_login_url">
                  <br>
                  <br>
                  <small> <?php echo _x('Must be a fully resolved URL like: http://www.membpress.com/welcome-page<br>Please make sure the URL is correct, else the users other than the administrators might not be able to login.', 'membpress_setup', 'membpress'); ?> </small> </p>
                <p class="membpress_settings_welcome_login_restrict_checkbox <?php if($membpress_settings_welcome_login_type == 'url'): ?>membpress_hidden<?php endif; ?>">
                  <input type="checkbox" name="membpress_settings_welcome_login_restrict_<?php echo $mp_level['level_no']; ?>" id="membpress_settings_welcome_login_restrict_<?php echo $mp_level['level_no']; ?>" <?php if($membpress_settings_welcome_login_restrict == 1): ?>checked<?php endif; ?> value="1">
                  <label for="membpress_settings_welcome_login_restrict_<?php echo $mp_level['level_no']; ?>"> <?php echo _x('Do not make the page/post restricted', 'membpress_setup', 'membpress'); ?> </label>
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