<?php
/**
 * Shortcodes init
 * 
 * Init main shortcodes
 *
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
include_once('shortcode-albums.php');
include_once('shortcode-album-images.php');
include_once('shortcode-albums-carousel.php');
include_once('shortcode-images-in-album.php');		// Display images in a specified album
include_once('shortcode-carousel.php'); 			// Display carousel of images in a specified album

include_once('shortcode-slideshow.php');

/**
 * Shortcode creation
 **/
add_shortcode( 'cws_gpp_albums', 'cws_gpp_shortcode_albums' );
add_shortcode( 'cws_gpp_album_images', 'cws_gpp_shortcode_album_images' );
add_shortcode( 'cws_gpp_albums_carousel', 'cws_gpp_shortcode_albums_carousel' );
add_shortcode( 'cws_gpp_images_in_album', 'cws_gpp_shortcode_images_in_album' ); 	// New shortcode to display all images in a specified album, album id
add_shortcode( 'cws_gpp_carousel', 'cws_gpp_shortcode_carousel' ); 					// New shortcode to display carousel of images in a specified album, album id 

add_shortcode( 'cws_gpp_slideshow', 'cws_gpp_shortcode_slideshow' );
