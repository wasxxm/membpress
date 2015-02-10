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

// get membership options page ID
$membpress_settings_membership_option_page = get_option('membpress_settings_membership_option_page');

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
$mp_restriction_options_sections_dir = 'membpress_restriction_options_sections';

// include the membpress header
include_once 'membpress.header.html.php';

?>
    <form method="post" action="<?php echo plugins_url(); ?>/membpress/includes/actions/membpress_restriction_options.action.php" enctype="multipart/form-data">
    
      <?php
	     // create the nonce field for this page
		 wp_nonce_field( 'membpress_restriction_options_page', 'membpress_restriction_options_page_nonce' );
	  ?>
	   
	   <?php
	   // this is the header html like the expand/collapse links and the heading of this page
	   include_once $mp_restriction_options_sections_dir . '/membpress_restriction_options_header.html.php';
	   ?>
       
      <div class="meta-box-sortables">     
       <!-- Membership Post Restriction Options section starts below -->
       <?php
	   // include the restrict posts section
	   include_once $mp_restriction_options_sections_dir . '/membpress_restrict_posts.html.php';
	   ?>
       <!-- Membership Post Restriction Options section ends above -->
       
       
       <!-- Membership Page Restriction Options section starts below -->
       <?php
	   // include the restrict pages section
	   include_once $mp_restriction_options_sections_dir . '/membpress_restrict_pages.html.php';
	   ?>
       <!-- Membership Page Restriction Options section ends above -->
       
       
       <!-- Membership Category Restriction Options section starts below -->
       <?php
	   // include the restrict categories section
	   include_once $mp_restriction_options_sections_dir . '/membpress_restrict_categories.html.php';
	   ?>
       <!-- Membership Category Restriction Options section ends above -->
       
       
        
       <!-- Membership Tags Restriction Options section starts below -->
       <?php
	   // include the restrict tags section
	   include_once $mp_restriction_options_sections_dir . '/membpress_restrict_tags.html.php';
	   ?>
       <!-- Membership Tags Restriction Options section ends above -->
       
       
       
       <!-- Membership HTML Restriction Options section starts below -->
       <?php
	   // include the restrict categories section
	   include_once $mp_restriction_options_sections_dir . '/membpress_restrict_section.html.php';
	   ?>
       <!-- Membership HTML Restriction Options section ends above --> 
       
       
       <!-- Membership URI Restriction Options section starts below -->
       <?php
	   // include the restrict categories section
	   include_once $mp_restriction_options_sections_dir . '/membpress_restrict_uri.html.php';
	   ?>
       <!-- Membership URI Restriction Options section ends above --> 
       
       
      </div>
      <p>
        <input type="submit" value="<?php echo _x('Save Settings', 'general', 'membpress'); ?>" class="button button-primary" id="membpress_restriction_options_submit" name="membpress_restriction_options_submit">
      </p>
    </form>
  </div>
</div>
