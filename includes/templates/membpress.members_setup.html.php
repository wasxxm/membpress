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

// get membpress global login welcome redirect type. If not set, then default it to page
$membpress_settings_welcome_login_type = get_option('membpress_settings_welcome_login_type');
if ($membpress_settings_welcome_login_type == '')
   $membpress_settings_welcome_login_type = 'page';

// get global login welcome redirect page ID
$membpress_settings_welcome_login_page = get_option('membpress_settings_welcome_login_page');
// get global login welcome redirect post ID
$membpress_settings_welcome_login_post = get_option('membpress_settings_welcome_login_post');
// get global login welcome redirect URL
$membpress_settings_welcome_login_url = get_option('membpress_settings_welcome_login_url');
// get global login welcome redirect post/page restriction, either apply it or not
$membpress_settings_welcome_login_restrict = (bool)get_option('membpress_settings_welcome_login_restrict');
// get the membpress login welcome redirect type as global or for individual membership levels
$membpress_settings_welcome_login_individual = (bool)get_option('membpress_settings_welcome_login_individual');

// get membership options page ID
$membpress_settings_membership_option_page = get_option('membpress_settings_membership_option_page');

// get the flag for the login page customization
$membpress_settings_customize_login_page_flag = (bool)get_option('membpress_settings_customize_login_page_flag');

// get the flag for the login rewrite page
$membpress_settings_customize_login_rewrite_flag = (bool)get_option('membpress_settings_customize_login_rewrite_flag');

// get the flag for the login hide passwrd forgot
$membpress_settings_customize_login_hide_passforgot = (bool)get_option('membpress_settings_customize_login_hide_passforgot');

// get the flag for the hide blog link on login page
$membpress_settings_customize_login_hide_bloglink = (bool)get_option('membpress_settings_customize_login_hide_bloglink');

// get the membpress login page logo URL option
$membpress_settings_customize_login_logo_url = get_option('membpress_settings_customize_login_logo_url', plugins_url('membpress/resources/images/login_logo.png'));

// get the background color value for the login page
$membpress_settings_customize_login_page_bg = get_option('membpress_settings_customize_login_page_bg', MEMBPRESS_LOGIN_BG_COLOR);

// get the background color value for the login form page
$membpress_settings_customize_login_form_bg = get_option('membpress_settings_customize_login_form_bg', MEMBPRESS_LOGIN_FORM_BG_COLOR);

// get the background color value for the login button
$membpress_settings_customize_login_btn_bg = get_option('membpress_settings_customize_login_btn_bg', MEMBPRESS_LOGIN_BTN_BG_COLOR);

// get the background color value for the login button
$membpress_settings_customize_login_btn_border = get_option('membpress_settings_customize_login_btn_border', MEMBPRESS_LOGIN_BTN_BORDER_COLOR);

// get the backurl for logo on login
$membpress_settings_customize_login_backurl = get_option('membpress_settings_customize_login_backurl', MEMBPRESS_LOGIN_BACKURL);

// get the backurl title for logo on login
$membpress_settings_customize_login_backurl_title = get_option('membpress_settings_customize_login_backurl_title', MEMBPRESS_LOGIN_BACKURL_TITLE);

/**
// pages list, will be used many times
*/
$pages = get_pages();

/**
// posts list, max posts defined by MEMBPRESS_SETTINGS_MAX_POSTS
*/
$args = array( 'posts_per_page' => MEMBPRESS_SETTINGS_MAX_POSTS, 'order'=> 'ASC', 'orderby' => 'title' );
$postslist = get_posts( $args );

/**
// get total posts count
*/
$posts_count = wp_count_posts();
$posts_count = $posts_count->publish;

/**
// get all membpress membership levels
*/
$mp_levels = $this->membpress_get_all_membership_levels();

// membpress setup sections html include dir
$mp_setup_sections_dir = 'membpress_setup_sections';

// include the membpress header
include_once 'membpress.header.html.php';

?>
    <form method="post" action="<?php echo plugins_url(); ?>/membpress/includes/actions/membpress_setup.action.php" enctype="multipart/form-data">
    
      <?php
	     // create the nonce field for this page
		 wp_nonce_field( 'membpress_settings_page', 'membpress_settings_page_nonce' );
	  ?>
      
       <?php
	   // this is the header html like the expand/collapse links and the heading of this page
	   include_once $mp_setup_sections_dir . '/membpress_setup_header.html.php';
	   ?>
      
       <div class="meta-box-sortables">
       <!-- Welcome Page After Login section starts below -->
       <?php
       include_once $mp_setup_sections_dir . '/membpress_settings_welcome_page_login.html.php';
	   ?>
       <!-- Welcome Page After Login section ends above -->
       
       <!-- Membership Options Page section starts below -->
       <?php
	   include_once $mp_setup_sections_dir . '/membpress_settings_membership_options_page.html.php';
	   ?>
       <!-- Membership Options Page section ends above --> 
        
       
       <!-- Membership Levels section starts below -->
       <?php
	   include_once $mp_setup_sections_dir . '/membpress_settings_membership_levels.html.php';
	   ?>
        <!-- Membership levels section ends above --> 
        
        <!-- Membership Customize Login Page starts below -->
       <?php
	   include_once $mp_setup_sections_dir . '/membpress_settings_customize_login_page.html.php';
	   ?>
        <!-- Membership levels section ends above --> 
        
        
      </div>
      <p>
        <input type="submit" value="<?php echo _x('Save Settings', 'general', 'membpress'); ?>" class="button button-primary" id="membpress_settings_submit" name="membpress_settings_submit">
      </p>
    </form>
  </div>
</div>
