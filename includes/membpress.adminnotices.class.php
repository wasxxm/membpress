<?php

// include the membpress helper class
include_once 'membpress.helper.class.php';

class Membpress_AdminNotices extends Membpress_Helper
{
	/*
	@ Function hooked to the admin notices function
	* Can be used to generate WP Admin notices
	*/
	public function membpress_admin_notice()
	{
		
		global $pagenow; // global page file name like admin.php
		
		if (isset($_GET['page']))
		{
		   $page = $_GET['page']; // current page passed as ?page=page_name
		}
		
		// generate admin notices if required.
	}
	
	/**
	Function to remove all the admin notices
	*/
	public function membpress_remove_admin_notices()
	{
		// only remove admin notices while in membpress plugin
		global $pagenow;
		
	    if ($pagenow == 'admin.php' && isset($_GET['page']) && stristr($_GET['page'], 'membpress'))
		{
			// we are in membpress plugin, remove all other notices except membpress own notices
			
			global $wp_filter;
			
			foreach( array('admin_notices', 'all_admin_notices', 'network_admin_notices', 'user_admin_notices' ) as $hook_name )
			{
				$this->membpress_remove_admin_notices_hook( $hook_name );
			} 
		}
	}
	
	/**
	 * Searches $wp_filter for all possible notices
	 * and removes them
	 * @global $wp_filter Global array of Actions/Filters.
	 * @param $hook_name string Hook name to begin search.
	 * @return void
	 */
	public function membpress_remove_admin_notices_hook( $hook_name )
	{
		global $wp_filter;
		
		foreach( $wp_filter[ $hook_name ] as $priority => $actions )
		{
			foreach( $actions as $key => $data )
			{
				unset( $wp_filter[ $hook_name ][ $priority ][ $key ] );
			}
		}  
	}
};