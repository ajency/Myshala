<?php
/**
 * Display Albums when using shortcode
 *
 *
 *
 */
function nak_gp_shortcode_albums( $atts ) {
	
	global $wpPicasa;
	
	// Get any specific album ids if shortcode has passed any
	// e.g. [nak_google_picasa_albums show_albums='5218473000700519489,  5218507736478682657 ']
	if ( isset( $atts['show_albums'] ) )
	{				
		$show_albums = explode( ',' , $atts['show_albums'] );				// return array split string on commas
		$show_albums = array_map( 'trim', $show_albums );					// remove any white space				
	}
			
	$options = get_option( 'nak_gp_options' );								// Get the options stored for this install
			
	extract( shortcode_atts( array(									
		'user' 				 => $options['username'],
		'pass' 				 => $options['password'],
		'max_album_results'  => $options['max_album_results'],
		'max_results' 		 => $options['max_results'],
		'album_thumb_size' 	 => $options['album_thumb_size'],
		'album_results_page' => $options['album_results_page'],
	), $atts) );
	
	$my_picasa = new PicasaAPI( $options['username'], $options['password'] ); // Create instance of PicasaAPI class
	
	// Call user albums
	return $user_albums = $my_picasa->get_album_display(	$max_album_results, 
															$album_thumb_size, 
															strtolower( $options['album_results_page'] ), 
															$show_albums );
}