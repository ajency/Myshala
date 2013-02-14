<?php
/**
 * Shortcodes init
 * 
 * Init main shortcodes, and add a few others such as recent products.
 *
 * @package		WPPicasa
 * @category	Shortcode
 * @author		Nakunakifi
 */
include_once('shortcode-albums.php');
include_once('shortcode-album-images.php');

/**
 * Shortcode creation
 **/
add_shortcode( 'nak_google_picasa_albums', 'nak_gp_shortcode_albums' );
add_shortcode( 'nak_google_picasa_album_images', 'nak_gp_shortcode_album_images' );