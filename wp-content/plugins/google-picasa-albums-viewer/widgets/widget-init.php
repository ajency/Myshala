<?php
/**
 * Widgets init
 * 
 * Init the widgets.
 *
 * @package		WPPicasa
 * @category	Widgets
 * @author		Nakunakifi
 */
include_once( 'widget-display-albums.php' );

function nak_gp_register_widgets() {
	register_widget( 'Nak_gp_Widget_DisplayAlbums' );
	// error_log('registering');
}

add_action( 'widgets_init', 'nak_gp_register_widgets' );