<?php

// include the membpress helper class
include_once 'membpress.helper.class.php';

class Membpress_ExtraFields extends Membpress_Helper
{
	/**
	@ Function as a hook to add extra fields to category edit form
	*/
	public function membpress_extra_category_fields($tag)
	{
	   $tag_id = $tag->term_id;
	   
	   // get all the membpress membership levels
	   $mp_get_membership_levels = $this->membpress_get_all_membership_levels();
	   
	   $mp_category_restricted_by_level = $this->membpress_check_category_restricted_by_level($tag_id);
	
	   echo '<tr class="form-field">
			<th scope="row" valign="top"><label for="membpress_restrict_category_level">'._x('Restrict this category', 'general', 'membpress').'</label></th>
			<td>
			<select name="membpress_restrict_category_level" class="postform" id="membpress_restrict_category_level"><option value="">-- '._x('No Restriction', 'general', 'membpress').' --</option>';
			
	   // list all the levels with the assigned as selected
	   foreach($mp_get_membership_levels as $mp_get_membership_level_key => $mp_get_membership_level_val)
	   {
		   $selected = '';
		   // check if this level is the same level to which the post is assigned restricted
		   if ($mp_category_restricted_by_level && $mp_category_restricted_by_level['level_no'] == $mp_get_membership_level_val['level_no'])
		   {
			   $selected = 'selected="selected"';  // select the current level  
		   }
		   echo '<option '.$selected.' value="'.$mp_get_membership_level_key.'">' . $mp_get_membership_level_val['display_name'] . '</option>';      
	   }	
			
		echo '</select>
						<p class="description">'._x('Please select the required Membership Level to access the posts under this category, in case you want to restrict them from public viewing.', 'general', 'membpress').'</p>
					</td>
			</tr>';	
	}
	
	/**
	@ Function to save extra fields in edit category
	*/
	public function membpress_save_extra_category_fields($term_id)
	{
		// see if the restrict option was set
		if (isset($_POST['membpress_restrict_category_level']))
		{		   
		   /**
		   Update the list of posts in the membpress restrict_categories_level_{level_no} array option
		   It can be configured also at: Membpress -> Restriction Options -> Retrict Categories
		   */
		   
		   // clear any previous assignment of the current category to any membership level
		   // get the current category resitricted level, this is before saving the new one
		   $mp_category_prev_level = $this->membpress_check_category_restricted_by_level($term_id);
		   $mp_category_prev_level = $mp_category_prev_level['level_no'];
		   
		   // the category ID can be in more than one membership level, so remove it from all levels
		   // first get all membership levels
		   $mp_levels = $this->membpress_get_all_membership_levels();
		   
		   // iterate through each level
		   foreach($mp_levels as $mp_level)
		   {
			   // get the mempress restrict category level option
			   $mp_restrict_categories_by_curr_level = (array)get_option('membpress_restrict_categories_level_' . $mp_level['level_no']);
			   
			   // remove the current category from this level
			   // there can be many category IDs, so iterate
			   foreach($mp_restrict_categories_by_curr_level as $mp_restrict_category_by_curr_level_key => $mp_restrict_category_by_curr_level)
			   {
				   if ($mp_restrict_category_by_curr_level == $term_id)
				   {
					   unset($mp_restrict_categories_by_curr_level[$mp_restrict_category_by_curr_level_key]);   
				   }
			   }
			   
			   update_option('membpress_restrict_categories_level_' . $mp_level['level_no'], $mp_restrict_categories_by_curr_level);
		   }
		   	
		   /*
		   Now the current value of category is removed from the restrict categories level, we can continue further update
		   */
		   
		   // get the category level number only currently submitted
		   $mp_level_no = explode('_', $_POST['membpress_restrict_category_level']);
		   $mp_level_no = $mp_level_no[count($mp_level_no) - 1];
		   
		   if ($mp_level_no == 'subscriber') $mp_level_no = 0;
		   
		   // new level for this category
		   $mp_restrict_categories_by_new_level = (array)get_option('membpress_restrict_categories_level_' . $mp_level_no);
		 
		   // check if the value of restrict level is  empty
		   if (trim($_POST['membpress_restrict_category_level']) == '')
		   {
			   //   
		   }
		   else // means the membership value is set, restriction is applied
		   {
			   array_push($mp_restrict_categories_by_new_level, $term_id); 
		   }
		   
		   // make the array unqiue to remove any duplicate values
		   $mp_restrict_categories_by_new_level = array_unique($mp_restrict_categories_by_new_level);
		   
		   // remove empty IDs
		   foreach($mp_restrict_categories_by_new_level as $mp_restrict_category_by_new_level_key => $mp_restrict_category_by_new_level)
		   {
			  if (trim($mp_restrict_category_by_new_level) == '')
			  {
				  unset($mp_restrict_categories_by_new_level[$mp_restrict_category_by_new_level_key]);  
			  }
		   }
		  
		   // update the restrict categories option
		   update_option('membpress_restrict_categories_level_' . $mp_level_no, $mp_restrict_categories_by_new_level);
		}
	}
	
	
	/**
	@ Function as a hook to add extra fields to tag edit form
	*/
	public function membpress_extra_tag_fields($tag)
	{
	   $tag_id = $tag->term_id;
	   
	   // get all the membpress membership levels
	   $mp_get_membership_levels = $this->membpress_get_all_membership_levels();
	   
	   $mp_tag_restricted_by_level = $this->membpress_check_tag_restricted_by_level($tag_id);
	
	   echo '<tr class="form-field">
			<th scope="row" valign="top"><label for="membpress_restrict_tag_level">'._x('Restrict this tag', 'general', 'membpress').'</label></th>
			<td>
			<select name="membpress_restrict_tag_level" class="postform" id="membpress_restrict_tag_level"><option value="">-- '._x('No Restriction', 'general', 'membpress').' --</option>';
			
	   // list all the levels with the assigned as selected
	   foreach($mp_get_membership_levels as $mp_get_membership_level_key => $mp_get_membership_level_val)
	   {
		   $selected = '';
		   // check if this level is the same level to which the post is assigned restricted
		   if ($mp_tag_restricted_by_level && $mp_tag_restricted_by_level['level_no'] == $mp_get_membership_level_val['level_no'])
		   {
			   $selected = 'selected="selected"';  // select the current level  
		   }
		   echo '<option '.$selected.' value="'.$mp_get_membership_level_key.'">' . $mp_get_membership_level_val['display_name'] . '</option>';      
	   }	
			
		echo '</select>
						<p class="description">'._x('Please select the required Membership Level to access posts assigned to this tag, in case you want to restrict them from public viewing.', 'general', 'membpress').'</p>
					</td>
			</tr>';	
	}
	
	/**
	@ Function to save extra fields in edit tag
	*/
	public function membpress_save_extra_tag_fields($term_id)
	{
		// see if the restrict option was set
		if (isset($_POST['membpress_restrict_tag_level']))
		{		   
		   /**
		   Update the list of posts in the membpress restrict_tags_level_{level_no} array option
		   It can be configured also at: Membpress -> Restriction Options -> Retrict Tags
		   */
		   
		   // clear any previous assignment of the current tag to any membership level
		   // get the current tag resitricted level, this is before saving the new one
		   $mp_tag_prev_level = $this->membpress_check_tag_restricted_by_level($term_id);
		   $mp_tag_prev_level = $mp_tag_prev_level['level_no'];
		   
		   // the tag ID can be in more than one membership level, so remove it from all levels
		   // first get all membership levels
		   $mp_levels = $this->membpress_get_all_membership_levels();
		   
		   // iterate through each level
		   foreach($mp_levels as $mp_level)
		   {
			   // get the mempress restrict tag level option
			   $mp_restrict_tags_by_curr_level = (array)get_option('membpress_restrict_tags_level_' . $mp_level['level_no']);
			   
			   // remove the current tag from this level
			   // there can be many tag IDs, so iterate
			   foreach($mp_restrict_tags_by_curr_level as $mp_restrict_tag_by_curr_level_key => $mp_restrict_tag_by_curr_level)
			   {
				   if ($mp_restrict_tag_by_curr_level == $term_id)
				   {
					   unset($mp_restrict_tags_by_curr_level[$mp_restrict_tag_by_curr_level_key]);   
				   }
			   }
			   
			   update_option('membpress_restrict_tags_level_' . $mp_level['level_no'], $mp_restrict_tags_by_curr_level);
		   }
		   	
		   /*
		   Now the current value of tag is removed from the restrict tags level, we can continue further update
		   */
		   
		   // get the tag level number only currently submitted
		   $mp_level_no = explode('_', $_POST['membpress_restrict_tag_level']);
		   $mp_level_no = $mp_level_no[count($mp_level_no) - 1];
		   
		   if ($mp_level_no == 'subscriber') $mp_level_no = 0;
		   
		   // new level for this tag
		   $mp_restrict_tags_by_new_level = (array)get_option('membpress_restrict_tags_level_' . $mp_level_no);
		 
		   // check if the value of restrict level is  empty
		   if (trim($_POST['membpress_restrict_tag_level']) == '')
		   {
			   //   
		   }
		   else // means the membership value is set, restriction is applied
		   {
			   array_push($mp_restrict_tags_by_new_level, $term_id); 
		   }
		   
		   // make the array unqiue to remove any duplicate values
		   $mp_restrict_tags_by_new_level = array_unique($mp_restrict_tags_by_new_level);
		   
		   // remove empty IDs
		   foreach($mp_restrict_tags_by_new_level as $mp_restrict_tag_by_new_level_key => $mp_restrict_tag_by_new_level)
		   {
			  if (trim($mp_restrict_tag_by_new_level) == '')
			  {
				  unset($mp_restrict_tags_by_new_level[$mp_restrict_tag_by_new_level_key]);  
			  }
		   }
		  
		   // update the restrict tags option
		   update_option('membpress_restrict_tags_level_' . $mp_level_no, $mp_restrict_tags_by_new_level);
		}
	}	
};