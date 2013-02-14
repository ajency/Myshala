<?php
/*
 Plugin Name: Ajency Google Auto Login
Plugin URI: http://ajency.in
Description: Used to allow users to auto login to wordpress site using google api and authentication.
Author: Nisheed Jagadish
Version: 0.1 Alpha
Author URI: http://ajency.in
*/

/* Only load code that needs BuddyPress to run once BP is loaded and initialized. */
function gal_plugin_init() {
	require( dirname( __FILE__ ) . '/gal_main.php' );
}
add_action( 'bp_include', 'gal_plugin_init' );