<?php

// include the membpress helper class
include_once 'membpress.helper.class.php';

class Membpress_AdminPointers extends Membpress_Helper
{
	
	public function mp_pointers_load( $hook_suffix ) {
	 
		// Don't run on WP < 3.3
		if ( get_bloginfo( 'version' ) < '3.3' )
			return;
	 
		$screen = get_current_screen();
		$screen_id = $screen->id;
	 
		// Get pointers for this screen
		$pointers = apply_filters( 'mp_admin_pointers-' . $screen_id, array() );
	 
		if ( ! $pointers || ! is_array( $pointers ) )
			return;
	 
		// Get dismissed pointers
		$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
		$valid_pointers = array();
	 
		// Check pointers and remove dismissed ones.
		foreach ( $pointers as $pointer_id => $pointer ) {
	 
			// Sanity check
			if ( in_array( $pointer_id, $dismissed ) || empty( $pointer )  || empty( $pointer_id ) || empty( $pointer['target'] ) || empty( $pointer['options'] ) )
				continue; 
	 
			$pointer['pointer_id'] = $pointer_id;
	 
			// Add the pointer to $valid_pointers array
			$valid_pointers['pointers'][] =  $pointer;
		}
	 
		// No valid pointers? Stop here.
		if ( empty( $valid_pointers ) )
			return;
	
	   // Add pointers style to queue.
       wp_enqueue_style( 'wp-pointer' );
	 
       // Add pointers script to queue. Add custom script.
       wp_enqueue_script( 'mp-pointer', plugin_dir_url( '/' ) . 'membpress/resources/js/mp_pointers.js', array( 'wp-pointer' ) );
 
       // Add pointer options to script.
       wp_localize_script( 'mp-pointer', 'mpPointer', $valid_pointers );
	   
	}
	
	public function mp_render_install_pointers()
	{
	   	add_filter( 'mp_admin_pointers-plugins', array($this, 'mp_register_install_pointer_sidebar') );
		
		add_filter( 'mp_admin_pointers-plugins', array($this, 'mp_register_install_pointer_top') );	
	}
	
	public function mp_register_install_pointer_sidebar( $p ) {
		$p['membpress_install_sidebar_pointer'] = array(
			'target' => '#toplevel_page_membpress_page_quick_start',
			'options' => array(
				'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
					_x( 'Get started with MembPress' ,'general', 'membpress'),
					_x( 'Thanks for choosing MembPress. You can get started with Membpress by following the quick start guide in the dropdown.','general', 'membpress')
				),
				'position' => array( 'edge' => 'top', 'align' => 'left' )
			)
		);
		return $p;
	}
	
	public function mp_register_install_pointer_top( $p ) {
		$p['membpress_install_top_pointer'] = array(
			'target' => '#wp-admin-bar-membpress_admin_bar',
			'options' => array(
				'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
					_x( 'MembPress is here too' ,'general', 'membpress'),
					_x( 'You can also manage your MembPress using the dropdown links above.','general', 'membpress')
				),
				'position' => array( 'edge' => 'top', 'align' => 'left' )
			)
		);
		return $p;
	}
};

?>