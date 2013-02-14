<?php
/**
 * Copyright (c) 2011, cheshirewebsolutions.com, Ian Kennerley (info@cheshirewebsolutions.com).
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
function cws_gpp_shortcode_album_images( $atts ) {
		
	// Get user credentials and prefs from database
	$options = get_option( 'cws_gpp_options' );

	// Create instance of GoogleAPI class
	$my_picasa = new CWS_GPPApi( );
	
	// Get album ID
	if( function_exists( 'cws_gpp_output_album_id' ) )	$album_id = cws_gpp_output_album_id();
	
	if( ! isset( $album_id ) ) {
		return false;
		exit;
	}
				
	// Extract the attributes into variables
	extract( shortcode_atts( array(
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
													$GLOBALS['wpPicasa']->page );			
}
