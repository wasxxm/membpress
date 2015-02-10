<?php
/**
* This is the main membpress class. It contains methods for the core
* functions of the membpress plugin

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

// include the membpress helper class
include_once 'membpress.helper.class.php';

// include the membpress metaboxes class
include_once 'membpress.metaboxes.class.php';

// include the membpress menus class
include_once 'membpress.menus.class.php';

// include the membpress shortcodes class
include_once 'membpress.shortcodes.class.php';

// include the membpress customcolumns class
include_once 'membpress.customcolumns.class.php';

// include the membpress extrafields class
include_once 'membpress.extrafields.class.php';

// include the membpress adminnotices class
include_once 'membpress.adminnotices.class.php';

// include the memberess adminpointers class
include_once 'membpress.adminpointers.class.php';

// include the memberess loginpage class
include_once 'membpress.loginpage.class.php';

class MembPress_Main
{
	// instance of membpress helper class
	public $mp_helper;
	// instance of membpress metaboxes class
	public $mp_metaboxes;
	// instance of membpress menus class
	public $mp_menus;
	// instance of membpress shortcodes class
	public $mp_shortcodes;
	// instance of membpress customcolumns class
	public $mp_customcolumns;
	// instance of membpress extrafields class
	public $mp_extrafields;
	// instance of membpress adminnotices class
	public $mp_adminnotices;
	// instance of membpress admin pointers class
	public $mp_adminpointers;
    // instance of membpress loginpage class
	public $mp_loginpage;
	
	/*
	@Contructor function loads basic hooks
	*/
	public function MembPress_Main()
	{ 	   
	   // initialize membpress helper class object
	   $this->mp_helper = new Membpress_Helper();
	   // initialize membpress metaboxes class object
	   $this->mp_metaboxes = new Membpress_MetaBoxes();
	   // initialize membpress metaboxes class object
	   $this->mp_menus = new Membpress_Menus();
	   // initialize membpress shortcodes class object
	   $this->mp_shortcodes = new Membpress_ShortCodes();
	   // initialize membpress customcolumns class object
	   $this->mp_customcolumns = new Membpress_CustomColumns();
	   // initialize membpress extrafields class object
	   $this->mp_extrafields = new Membpress_ExtraFields();
	   // initialize membpress adminnotices class object
	   $this->mp_adminnotices = new Membpress_AdminNotices();
	   // initialize membpress adminpointers class object
	   $this->mp_adminpointers = new Membpress_AdminPointers();
	   // initialize membpress loginpage class object
	   $this->mp_loginpage = new Membpress_LoginPage();
	   
	   
	   // add admin menu hook
	   add_action('admin_menu', array($this->mp_menus, 'membpress_action_admin_menu'));
	   // add admin bar menu hook
	   add_action( 'admin_bar_menu', array($this->mp_menus, 'membpress_action_admin_bar_menu'), 999 );
	   // add init hook
	   add_action('init', array($this, 'membpress_action_init'));
	   // add admin init hook
	   add_action('admin_init', array($this, 'membpress_admin_init'));
	   // add admin notices hook
	   add_action('admin_notices', array($this->mp_adminnotices, 'membpress_admin_notice'));
	   // add the hook for adding metaboxes
	   add_action('add_meta_boxes', array($this->mp_metaboxes, 'membpress_add_meta_box'));
	   // enqueue scripts to admin
	   add_action( 'admin_enqueue_scripts', array($this, 'mp_admin_enqueue_scripts'), 1000 );
	   
	   // hook for plugin activation
	   register_activation_hook(
	      // root directory relative to the dir of current file
		  str_replace('includes', '', dirname(__FILE__)) . 'membpress.php', 
	      array($this, 'membpress_register_activation_hook')
	   );
	   
	   // pre get post gook
	   // will be used to filter the main query
	   add_action( 'pre_get_posts', array($this, 'membpress_pre_get_posts') );
	   
	   // add action on save post 
	   add_action( 'save_post', array($this->mp_metaboxes, 'membpress_save_meta_box'));
	   
	   
	   // add extra fields to category edit form hook
	   add_action ( 'edit_category_form_fields', array($this->mp_extrafields, 'membpress_extra_category_fields'));
	   // save extra fields in category hook
	   add_action ( 'edited_category', array($this->mp_extrafields, 'membpress_save_extra_category_fields'));
	   
	   // add extra fields to tag edit form hook
	   add_action ( 'edit_tag_form_fields', array($this->mp_extrafields, 'membpress_extra_tag_fields'));
	   // save extra fields in tag hook
	   add_action ( 'edited_term', array($this->mp_extrafields, 'membpress_save_extra_tag_fields'));
	   
	   /*
	   Filter for modifying the columns in posts/pages/categories/tags screen
	   */
	   
	   // filter for the manage edit posts columns
	   add_filter('manage_posts_columns', array($this->mp_customcolumns, 'membpress_manage_posts_columns'), 10, 2);
	   // filter used in conjunction with manage_posts_columns filter
	   add_action('manage_posts_custom_column', array($this->mp_customcolumns, 'membpress_manage_posts_custom_column'), 10, 2);
	   // filter for sorting the custom columns in edit posts screen
	   add_filter('manage_edit-post_sortable_columns', array($this->mp_customcolumns, 'membpress_sortable_columns'));
	   
	   // filter for manage edit page columns
	   add_filter('manage_pages_columns', array($this->mp_customcolumns, 'membpress_manage_pages_columns'), 10, 2);
	   // filter used in conjunction with manage_pages_columns filter
	   add_action('manage_pages_custom_column', array($this->mp_customcolumns, 'membpress_manage_pages_custom_column'), 10, 2);
	   // filter for sorting the custom columns in edit pages screen
	   add_filter('manage_edit-page_sortable_columns', array($this->mp_customcolumns, 'membpress_sortable_columns'));
	   
	   // filter for manage edit category columns
	   add_filter('manage_edit-category_columns', array($this->mp_customcolumns, 'membpress_manage_categories_columns'), 10, 2);
	   // filter used in conjunction with manage_edit-category_columns filter
       add_filter('manage_category_custom_column', array($this->mp_customcolumns, 'membpress_manage_categories_custom_column'), 10, 3);
	   // filter for sorting the custom columns in edit categories screen
	   add_filter( 'manage_edit-category_sortable_columns', array($this->mp_customcolumns, 'membpress_sortable_columns'));
	   
	   // filter for manage edit tags columns
	   add_filter('manage_edit-post_tag_columns', array($this->mp_customcolumns, 'membpress_manage_tags_columns'), 10, 2);
	   // filter used in conjunction with manage_edit-category_columns filter
       add_filter('manage_post_tag_custom_column', array($this->mp_customcolumns, 'membpress_manage_tags_custom_column'), 10, 3);
	   // filter for sorting the custom columns in edit tags screen
	   add_filter( 'manage_edit-post_tag_sortable_columns', array($this->mp_customcolumns, 'membpress_sortable_columns'));
	   
	   /*
	   Add all the shortcodes with their callbacks here
	   */
	   add_shortcode('membpress', array($this->mp_shortcodes, 'membpress_parse_shortcodes'));
	   
	   $membpress_levels =  (array)get_option('membpress_levels');
	}
	
	/*
	@ Function hooked to the init action
	*/
	public function membpress_action_init()
	{
	   // set the text domain for the translation files
	   load_plugin_textdomain('membpress', false, basename( dirname( __FILE__ ) ) . '/languages' );
	   
	   // call membpress stylesheets addition hook
	   $this->membpress_register_plugin_styles();
	   // call membpress scripts addition hook
	   $this->membpress_register_plugin_scripts();
	   
	   // call the membpress login page welcome routine
	   $this->mp_helper->membpress_login_welcome();
	   
	   // customize login page
	   $this->mp_loginpage->membpress_customize_login_page();	
	}
	
    /*
	@ Function hooked to the admin_init action
	*/
	public function membpress_admin_init()
	{
		// remove all other admin notices during membpress admin screens
		// of course do not remove membpress own notices
		$this->mp_adminnotices->membpress_remove_admin_notices();
		
		// render the install messages as admin pointers
		$this->mp_adminpointers->mp_render_install_pointers();
	}
	
	/*
	@ Function called when the plugin is activated
	*/
	public function membpress_register_activation_hook()
	{
	   // add the default membpress roles
	   $this->mp_helper->membpress_add_default_roles();	
	}
	
    /*
	@   function to register stylesheets
	*/
	public function membpress_register_plugin_styles()
	{
		// register and enqueue main membpress stylesheet
		wp_register_style( 'membpress-style-sheet', plugins_url( 'membpress/resources/css/style.css' ) );
		wp_enqueue_style( 'membpress-style-sheet' );	
	}
	
	/*
	@   function to register javascript files
	*/
	public function membpress_register_plugin_scripts()
	{
		// include these scripts only in admin
		if (is_admin())
		{
		   // register and enqueue main javascript file
		   wp_register_script( 'membpress-script', plugins_url( 'membpress/resources/js/main.js' ) );
		   wp_enqueue_script( 'membpress-script' );
		
		   // register and enqueue the jquery cookie script
		   wp_register_script( 'membpress-script-cookie', plugins_url( 'membpress/resources/js/jquery.cookie.js' ) );
		   wp_enqueue_script( 'membpress-script-cookie' );
		}
	}
	
	/*
	@  Function called as hook to the pre get posts
	@  Place code to modify the main query
	@  param ($query): is the query object
	*/
	public function membpress_pre_get_posts($query)
	{
	    // call the function to manage the login welcome access of posts/pages/external url
		$this->mp_helper->membpress_manage_login_welcome_access($query);	
		
		// call the function to manage the restriction imposed on posts/pages/categories/tags/content/uris etc
		// based on membership levels
		$this->mp_helper->membpress_manage_restricted_access($query);
		
		// function to sort the MembPress info column on posts/pages/categories/tags etc
		$this->mp_customcolumns->membpress_sortable_info_columns($query);
	}
	
	
	/**
	Admin Enqueue Scripts 
	*/
	public function mp_admin_enqueue_scripts($hook_suffix)
	{
	    // enqueue the pointers function
		$this->mp_adminpointers->mp_pointers_load($hook_suffix);
	}
};

?>