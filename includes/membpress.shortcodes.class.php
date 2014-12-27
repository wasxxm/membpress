<?php

// include the membpress helper class
include_once 'membpress.helper.class.php';

class Membpress_ShortCodes extends Membpress_Helper
{
	/**
	@ Function to parse membpress shortcodes
	@ Echoing the content will put the content on top of the content
	@ Return will ensure the content is placed at right place
	*/
	public function membpress_parse_shortcodes($atts, $content = null)
	{
		// parse the attrs to check what shortcode do they refer to
		// check if their is any attribute at all
		if (is_array($atts) && count($atts))
		{
			foreach($atts as $att_key => $att_value)
			{
				if ($att_key == $this->membpress_get_shortcode_attrs('restrict_by'))
				{
				   return $this->membpress_parse_restrict_by_level_shortcode($att_value, $content);   
				} 
			}
		}
		return $content;
	}
	
	/**
	@ Function to store an array of attributes for different shortcode parse
	*/
	public function membpress_get_shortcode_attrs($attr_key)
	{
	   $attrs = 
	   array(
	      'restrict_by' => 'restrict_by'       
	   );
	   
	   return $attrs[$attr_key];
	}
	
	/**
	@ Function to parse the restrict by level shortcode
	*/
	public function membpress_parse_restrict_by_level_shortcode($att_value, $content)
	{	
		if (is_user_logged_in())
	    {
		   	$att_value = (int)$att_value;
			if ($this->membpress_check_curr_user_level_meets($att_value))
			{
			   return $content;   	
			}
			
			return '';
		}	
		else
		{
			return '';
		}
	}	
};