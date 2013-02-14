<?php

/**
 *	Display Album's Images when using shortcode
 */
function nak_gp_shortcode_album_images( $atts , $show_albums ) {

	$page_no = 1;
	
	if(isset($_GET['q']))
	{
		$page_no = $_GET['q'];
		if(empty($page_no) || !is_numeric($page_no))
			$page_no = 1;
	}
	else	
	{
		$page_no = 1;
	}

	
	$options = get_option( 'nak_gp_options' );									// Get user credentials and prefs from database
	$my_picasa = new PicasaAPI( $options['username'], $options['password'] );	// Create instance of GoogleAPI class
				
	if( function_exists( 'output_album_id' ) ){ $album_id = output_album_id(); }// Get album ID	
	
	if( ! isset( $album_id ) ) {
		return false;
		exit;
	}
	
	// Extract the attributes into variables
	extract( shortcode_atts( array(
		'user' 				=> $options['username'],
		'pass' 				=> $options['password'],
		'max_results' 		=> $options['max_results'],
		'thumb_size' 		=> $options['thumb_size'],
		'album_thumb_size'	=> $options['album_thumb_size'], 
		'max_results' 		=> $options['max_results'], 
		'max_image_size' 	=> $options['max_image_size'], 
	), $atts ) );
	
	return $my_picasa->get_album_images_display(	$album_id, 
													$thumb_size, 
													$max_image_size, 
													$max_results, 
													intval($page_no));
}
