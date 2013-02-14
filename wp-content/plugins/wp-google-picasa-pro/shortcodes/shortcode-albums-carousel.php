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
function cws_gpp_shortcode_albums_carousel( $atts ) {
		
	$show_slider_mrkrs = 0;
	
	// Get any specific album ids if shortcode has passed any
	// e.g. [cws_gpp_albums show_albums='5218473000700519489,  5218507736478682657 ']
	if ( isset( $atts['show_albums'] ) ) {				
		$show_albums = explode( ',' , $atts['show_albums'] );	// Return array split string on commas
		$show_albums = array_map( 'trim', $show_albums );		// Remove any white space
	}
	
	// Show/hide slider markers
	if ( isset( $atts['show_slider_mrkrs'] ) ) {
		
		$show_slider_mrkrs = $atts['show_slider_mrkrs'];
	}

	$options = get_option( 'cws_gpp_options' );
	
	// Extract the attributes into variables
	extract( shortcode_atts( array(
		'max_results' 		 => $options['max_results'],
		'album_thumb_size' 	 => $options['album_thumb_size'],
		'album_results_page' => $options['album_results_page'],
		'inc_private'        => $options['inc_private'],
		'show_album_ttl'     => $options['show_album_ttl'],
		'show_slider_mrkrs'  => $options['show_slider_mrkrs'],
	), $atts ) );
	
	// Create instance of PicasaAPI class
	$my_picasa = new CWS_GPPApi( );

	// Call user albums carousel
	return $user_albums_carousel = $my_picasa->get_album_carousel_display( 	$album_thumb_size, 
										strtolower( $album_results_page ), 
										$show_albums, 
										$inc_private, 
										$show_album_ttl, 
										$show_slider_mrkrs );
					}