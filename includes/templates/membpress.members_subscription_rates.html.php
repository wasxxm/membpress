<?php
/**
* Contains the template for membpress Subscription Rates page

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
$mp_subscription_rates_sections_dir = 'membpress_subscription_rates';

// include the membpress header
include_once 'membpress.header.html.php';
?>
       <?php
	   // this is the header html like the expand/collapse links and the heading of this page
	   include_once $mp_subscription_rates_sections_dir . '/membpress_subscription_rates_header.html.php';
	   ?>
      
       <div class="meta-box-sortables">
        
       <!-- Membership Subscriptions section starts below -->
       <?php
	   include_once $mp_subscription_rates_sections_dir . '/membpress_membership_subscriptions_rates.html.php';
	   ?>
        <!-- Membership Subscriptions section ends above --> 
      </div>
  </div>
</div>
