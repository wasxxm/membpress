<?php

// include the membpress helper class
include_once 'membpress.helper.class.php';

class Membpress_CustomColumns extends Membpress_Helper
{
	/**
	@ Function used as a callback for the Wordpress Manage Posts Columns filter
	*/
	public function membpress_manage_posts_columns($posts_columns, $post_type)
	{
	   // add post ID column to the edit post screen, after the checkbbox and before title
	   $posts_columns = $this->membpress_add_post_ID_column($posts_columns, $post_type);
	   
	   // add post info column to the edit post screen, after the title
	   $posts_columns = $this->membpress_add_post_info_column($posts_columns, $post_type);
	   
	   return $posts_columns;	
	}
	
    /**
	@ Filter function called for managing the custom columns added to edit post screen
	*/
	public function membpress_manage_posts_custom_column($column_name, $post_id)
	{
        // call the function to manage the post ID column content
		$this->membpress_manage_post_ID_column($column_name, $post_id);
		
		// call the function to manage the post info column content
		$this->membpress_manage_post_info_column($column_name, $post_id);
	}
	
	/**
	@ Function for callback to manage page columns edit
	*/
	public function membpress_manage_pages_columns($page_columns)
	{
	   // add page ID column to the edit page screen, after the checkbbox and before title
	   $page_columns = $this->membpress_add_page_ID_column($page_columns);
	   
	   // add page info column to the edit page screen, after the title
	   $page_columns = $this->membpress_add_page_info_column($page_columns);
	   
	   return $page_columns;  	
	}
	
	/**
	@ Filter function called for managing the custom columns added to edit page screen
	*/
	public function membpress_manage_pages_custom_column($column_name, $page_id)
	{
        // call the function to manage the page ID column content
		$this->membpress_manage_page_ID_column($column_name, $page_id);
		
		// call function to add membpress info column
		$this->membpress_manage_page_info_column($column_name, $page_id);
	}
	
	
    /**
	@ Function for callback to manage category columns edit
	*/
	public function membpress_manage_categories_columns($category_columns)
	{
	   // add category ID column to the edit category screen, after the checkbbox and before title
	   $category_columns = $this->membpress_add_category_ID_column($category_columns);
	   
	   // add category info column to the edit category screen, after the title
	   $category_columns = $this->membpress_add_category_info_column($category_columns);
	   
	   return $category_columns;  	
	}
	
	/**
	@ Function for callback to manage tag columns edit
	*/
	public function membpress_manage_tags_columns($tag_columns)
	{	   
	   // add tag ID column to the edit tag screen, after the checkbbox and before title
	   $tag_columns = $this->membpress_add_tag_ID_column($tag_columns);
	   
	   // add tag info column to the edit tag screen, after the title
	   $tag_columns = $this->membpress_add_tag_info_column($tag_columns);
	   
	   return $tag_columns;  	
	}
	
	/**
	@ Filter function called for managing the custom columns added to edit category screen
	*/
	public function membpress_manage_categories_custom_column($c, $column_name, $category_id)
	{
        // call the function to manage the category ID column content
		$this->membpress_manage_category_ID_column($c, $column_name, $category_id);
		
	    // call the function to manage the category info column content
		$this->membpress_manage_category_info_column($c, $column_name, $category_id);
	}
	
    /**
	@ Filter function called for managing the custom columns added to edit tag screen
	*/
	public function membpress_manage_tags_custom_column($c, $column_name, $tag_id)
	{
        // call the function to manage the tag ID column content
		$this->membpress_manage_tag_ID_column($c, $column_name, $tag_id);
		
	    // call the function to manage the tag info column content
		$this->membpress_manage_tag_info_column($c, $column_name, $tag_id);
	}
	
	
	/**
	@ Function to add post ID to the edit post screen
	*/
	public function membpress_add_post_ID_column($posts_columns, $post_type)
	{
	   // we want to add the post ID before the Title column and after the checkbox
       $posts_columns = array_slice($posts_columns, 0, 1, true) + array("post_ID" => "ID") + array_slice($posts_columns, 1, count($posts_columns) - 1, true);
	   
	   return $posts_columns;	
	}
	
	/**
	@ Function to add post info to the edit post screen
	*/
	public function membpress_add_post_info_column($posts_columns, $post_type)
	{
	   // we want to add the post info after the title
       $posts_columns = array_slice($posts_columns, 0, 3, true) + array("post_info" => "MembPress") + array_slice($posts_columns, 1, count($posts_columns) - 1, true);
	   
	   return $posts_columns;	
	}
	
    /**
	@ Function to add page ID to the edit page screen
	*/
	public function membpress_add_page_ID_column($page_columns)
	{
	   // we want to add the page ID before the Title column and after the checkbox
       $page_columns = array_slice($page_columns, 0, 1, true) + array("page_ID" => "ID") + array_slice($page_columns, 1, count($page_columns) - 1, true);
	   
	   return $page_columns;	
	}
	
	/**
	@ Function to add restriction info to the edit page screen
	*/
	public function membpress_add_page_info_column($page_columns)
	{
	   // we want to add the page info column after the title
       $page_columns = array_slice($page_columns, 0, 3, true) + array("page_info" => "MembPress") + array_slice($page_columns, 1, count($page_columns) - 1, true);
	   
	   return $page_columns;	
	}
	
	/**
	@ Function to add category ID to the edit category screen
	*/
	public function membpress_add_category_ID_column($category_columns)
	{
	   // we want to add the page ID before the Title column and after the checkbox
       $category_columns = array_slice($category_columns, 0, 1, true) + array("category_ID" => "ID") + array_slice($category_columns, 1, count($category_columns) - 1, true);
	   
	   return $category_columns;	
	}
	
	/**
	@ Function to add tag ID to the edit tag screen
	*/
	public function membpress_add_tag_ID_column($tag_columns)
	{
	   // we want to add the page ID before the Title column and after the checkbox
       $tag_columns = array_slice($tag_columns, 0, 1, true) + array("tag_ID" => "ID") + array_slice($tag_columns, 1, count($tag_columns) - 1, true);
	   
	   return $tag_columns;	
	}
	
	
    /**
	@ Function to add restriction info to the edit category screen
	*/
	public function membpress_add_category_info_column($category_columns)
	{
	   // we want to add the category info column after the title
       $category_columns = array_slice($category_columns, 0, 3, true) + array("category_info" => "MembPress") + array_slice($category_columns, 1, count($category_columns) - 1, true);
	   
	   return $category_columns;	
	}
	
	/**
	@ Function to add restriction info to the edit tag screen
	*/
	public function membpress_add_tag_info_column($tag_columns)
	{
	   // we want to add the tag info column after the title
       $tag_columns = array_slice($tag_columns, 0, 3, true) + array("tag_info" => "MembPress") + array_slice($tag_columns, 1, count($tag_columns) - 1, true);
	   
	   return $tag_columns;	
	}
	
	
	/**
	@ Function to manage the contents of the post ID column
	*/
	public function membpress_manage_post_ID_column($column_name, $post_id)
	{
	  if ('post_ID' == $column_name)
	  {
		  echo "<strong>$post_id</strong>";
	  }	
	}
	
	/**
	@ Function to manage the contents of the page ID column
	*/
	public function membpress_manage_page_ID_column($column_name, $page_id)
	{
	  if ('page_ID' == $column_name)
	  {
		  echo "<strong>$page_id</strong>";
	  }	
	}
	
    /**
	@ Function to manage the contents of the category ID column
	*/
	public function membpress_manage_category_ID_column($c, $column_name, $category_id)
	{
	  if ('category_ID' == $column_name)
	  {
		  echo "<strong>$category_id</strong>";
	  }	
	}
	
	/**
	@ Function to manage the contents of the tag ID column
	*/
	public function membpress_manage_tag_ID_column($c, $column_name, $tag_id)
	{
	  if ('tag_ID' == $column_name)
	  {
		  echo "<strong>$tag_id</strong>";
	  }	
	}
	
	/**
	@ Function to manage the filter for sorting the customs columns
	@ added in posts, pages, categories, tags
	*/
	public function membpress_sortable_columns($columns)
	{	
	    $columns['post_ID'] = 'id';
	    $columns['page_ID'] = 'id';
		$columns['category_ID'] = 'id';
		$columns['tag_ID'] = 'id';
		
	    $columns['post_info'] = 'post_info';
	    $columns['page_info'] = 'page_info';
		$columns['category_info'] = 'category_info';
		$columns['tag_info'] = 'tag_info';
		
		return $columns;	
	}
	
	/**
	@ Function to add sortable filter for the MembPress info column
	@ on posts/pages/categories/tags screens
	*/
	
	public function membpress_sortable_info_columns($query)
	{
		// only continue if it is admin
		if( ! is_admin() )
			return;
	 
	    // get the orderby query parameter
		$orderby = $query->get( 'orderby');
	  
	    // if this is a post, order by post info like 'Restricted by Free Member' etc
		if('post_info' == $orderby)
		{
			$query->set('meta_key', 'membpress_post_info');
			$query->set('orderby', 'meta_value');
		}
		
	    // if this is a page, order by page info like 'Restricted by Free Member' etc
		if('page_info' == $orderby )
		{
			$query->set('meta_key', 'membpress_page_info');
			$query->set('orderby', 'meta_value');
		}
		
		if ('category_info' == $orderby)
		{
		   // do something to sort by category info
		}
		
		if ('tag_info' == $orderby)
		{
		 // do something to sort by tag info	
		}
	}
	
	/**
	@ Function to manage the contents of the page info column
	*/
	public function membpress_manage_page_info_column($column_name, $page_id)
	{
	  if ('page_info' == $column_name)
	  {
		  /**
		  Check if the current page is set as membership options page
		  */
		  if ($this->membpress_check_if_membership_options_page($page_id))
		  {
		      $ret = "<strong>"._x("Membership Options Page", 'general', 'membpress')."</strong>";
			  
			  // update page membpress info to be used in MembPress column sort
			  update_post_meta($page_id, 'membpress_page_info', $ret);
			  
			  echo $ret;
			  
			  return; // return do not continue further  
		  }
		  
		  /** this page is not set as membership options page, so check if it is set as login welcome */
		  // redirect page for any level or for all levels (global)
		  $login_redirect_level = $this->membpress_check_page_login_redirect_exists($page_id);
		  
		  // page is set for global welcome login
		  if ($login_redirect_level['level_name'] == 'all')
		  {
		      $ret = "<strong>". _x("Global Login Welcome Page", 'general', 'membpress')."</strong>"; 
			  
			  // update page membpress info to be used in MembPress column sort
			  update_post_meta($page_id, 'membpress_page_info', $ret);
			  
			  echo $ret;
			  
			  return; // do not continue
		  }
		  else if (is_array($login_redirect_level) && count($login_redirect_level['level_no']))
		  {
			  $ret = "<strong>"._x("Login Welcome Page for", 'general', 'membpress') . '<br>';
			  
			  for ($i = 0; $i < count($login_redirect_level['level_no']); $i++)
			  {
			     $level_name = $login_redirect_level['level_name'];
				 // page is set for some specific membership levels
			     $ret .= " <em>".$level_name[$i]."</em><br>";
			  }
			  
			  $ret .= "</strong>";
			  
			  // update page membpress info to be used in MembPress column sort
			  update_post_meta($page_id, 'membpress_page_info', $ret);
			  
			  echo $ret;
			  
			  return;  // do not continue 
		  }
		  
		  /**
		  This page is not set as membership options page, nor as login welcome redirect page
		  Now check if it is restricted by any membership level
		  */
		  
		  $page_restricted = $this->membpress_check_page_restricted_by_level($page_id);
		  
		  if ($page_restricted) // page is restricted by some level
		  {
		     $ret = _x('Restricted by', 'general', 'membpress')." <strong><em>".$page_restricted['level_name']."<em></strong>";
			 
			 // update page membpress info to be used in MembPress column sort
			 update_post_meta($page_id, 'membpress_page_info', $ret);
			  
			 echo $ret;
			 
			 return;  
		  }
		  
		  // if no link to membpress is found, echo something to fill space
		  echo '- - -';
		  
		  // update page membpress info to be used in MembPress column sort
		  update_post_meta($page_id, 'membpress_page_info', '');
		  
	  }	
	}
	
	
	
	/**
	@ Function to manage the contents of the post info column
	*/
	public function membpress_manage_post_info_column($column_name, $post_id)
	{ 
	  if ('post_info' == $column_name)
	  {  
		  /** check if it is set as login welcome */
		  // redirect post for any level or for all levels (global)
		  $login_redirect_level = $this->membpress_check_post_login_redirect_exists($post_id);
		  
		  // post is set for global welcome login
		  if ($login_redirect_level['level_name'] == 'all')
		  {
		      $ret =  "<strong>". _x("Global Login Welcome Post", 'general', 'membpress')."</strong>";
			  // update post membpress info to be used in MembPress column sort
			  update_post_meta($post_id, 'membpress_post_info', $ret);
			  
			  echo $ret; 
			  return; // do not continue
		  }
		  else if (is_array($login_redirect_level) && count($login_redirect_level['level_no']))
		  {
			  $ret = "<strong>"._x("Login Welcome Post for", 'general', 'membpress') . '<br>';
			  
			  for ($i = 0; $i < count($login_redirect_level['level_no']); $i++)
			  {
			     $level_name = $login_redirect_level['level_name'];
				 // post is set for some specific membership levels
			     $ret .= " <em>".$level_name[$i]."</em><br>";
			  }
			  
			  $ret .= "</strong>";
			  
			  // update post membpress info to be used in MembPress column sort
			  update_post_meta($post_id, 'membpress_post_info', $ret);
			  
			  echo $ret;
			  
			  return;  // do not continue 
		  }
		  
		  /**
		  This post is not set as login welcome redirect post
		  Now check if it is restricted by any membership level
		  */
		  
		  $post_restricted = $this->membpress_check_post_restricted_by_level($post_id);
		  
		  if ($post_restricted) // post is restricted by some level
		  {
		      			 
			  $ret = ""._x('Restricted by', 'general', 'membpress')." <strong><em>".$post_restricted['level_name']."<em></strong>";
			  
			  // update post membpress info to be used in MembPress column sort
			  update_post_meta($post_id, 'membpress_post_info', $ret);
			  
			  echo $ret;
			  return; 
		  }
		  
		  // check if the post is retricted by some membership level
		  // using the all posts restrict option
		  if (get_option('membpress_restrict_allposts_level') != '')
		  {
			  $mp_allposts_restrict_by = (int)get_option('membpress_restrict_allposts_level');
			  
			  $ret = ""._x('Restricted by', 'general', 'membpress')." <strong><em>".$this->membpress_get_membership_level_name($mp_allposts_restrict_by)."<em></strong>";
			  
			  // update post membpress info to be used in MembPress column sort
			  update_post_meta($post_id, 'membpress_post_info', $ret);
			  
			  echo $ret;
			  return;      
		  }
		  
		  // post is not restricted directly by any means
		  // now check if it is restricted by any category
		  $post_restricted_by_cat = $this->membpress_get_post_category_restricted_level($post_id);
		  
		  if ($post_restricted_by_cat)
		  {
			  // post is restricted by some category
			  $ret = _x('Restricted by category', 'general', 'membpress')." <br><strong><em>".$post_restricted_by_cat['category_name']."<em></strong>";
			  // update post membpress info to be used in MembPress column sort
			  update_post_meta($post_id, 'membpress_post_info', $ret);
			  
			  echo $ret;
			  return; 
		  }
		  
		  update_post_meta($post_id, 'membpress_post_info', '');
		  
		  // if no link to membpress is found, echo something to fill space
		  echo '- - -';
		  
	  }	
	}
	
	
	/**
	@ Function to manage the contents of the category info column
	*/
	public function membpress_manage_category_info_column($c, $column_name, $category_id)
	{
	  if ('category_info' == $column_name)
	  {  
		  /**
		  Check if it is restricted by any membership level
		  */
		  $category_restricted = $this->membpress_check_category_restricted_by_level($category_id);
		  
		  if ($category_restricted) // category is restricted by some level
		  {
		     echo ""._x('Restricted by', 'general', 'membpress')." <br><strong><em>".$category_restricted['level_name']."<em></strong>";
			 
			 return; 
		  }
		  
		  // current category is not restricted by any level
		  // check if current category is a child of any category
		  // if yes, then check if the parent category is restricted by any level
		  $parent_category = $category_id;
		  while ($parent_category)
		  {
		     $parent_category = get_category($parent_category);
		     $parent_category = $parent_category->parent;
			
			 if (!$parent_category) break;
			 
			 $parent_restricted_by_level = $this->membpress_check_category_restricted_by_level($parent_category);
			 
			 if ($parent_restricted_by_level)
			 {
				echo ""._x('Implicitly Restricted by', 'general', 'membpress')." <br><strong><em>".$parent_restricted_by_level['level_name']."<em></strong>";
				return; 
			 }
		  }

		  // if no link to membpress is found, echo something to fill space
		  echo '- - -';
		  
	  }	
	}
	
	
	/**
	@ Function to manage the contents of the tag info column
	*/
	
	public function membpress_manage_tag_info_column($c, $column_name, $tag_id)
	{
	  if ('tag_info' == $column_name)
	  {  
		  /**
		  Check if it is restricted by any membership level
		  */
		  $tag_restricted = $this->membpress_check_tag_restricted_by_level($tag_id);
		  
		  if ($tag_restricted) // tag is restricted by some level
		  {
		     echo ""._x('Restricted by', 'general', 'membpress')." <br><strong><em>".$tag_restricted['level_name']."<em></strong>";
			 
			 return; 
		  }

		  // if no link to membpress is found, echo something to fill space
		  echo '- - -';
		  
	  }	
	}	
};