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
};