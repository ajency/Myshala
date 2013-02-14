<?php
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WPPicasa Admin Functions
 * 
 * Loads main functions used by admin menu and front-end.
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

/**
 *
 *  Allow redirection, even if my theme starts to send output to the browser
 *
 */	
add_action( 'init', 'cws_do_output_buffer' );
function cws_do_output_buffer() {
	ob_start();
}

/**
 *
 *  Display Messsage.
 *  If Zend Loader is not found.
 *  If Cache directory is not writable.
 *
 */	
function showMessage( $preflight_check, $errormsg = false )
{
	if ( $errormsg ) {
		echo '<div id="message" class="error">';
		
		foreach($preflight_check as $message){
			echo "<p><strong>$message</strong></p>";
		}
		
		echo "</div>";
		
	}
	else {
		echo '<div id="message" class="updated fade">';
	}

	//echo "<p><strong>$message</strong></p></div>";
}    

function showAdminMessages( $preflight_check )
{
    // Shows as an error message. You could add a link to the right page if you wanted.
    showMessage($preflight_check, true);

    //$this->showMessage($msg, true);

	// Only show to admins
    // if ( current_user_can( 'manage_options' ) ) {
    //    $this->showMessage( "Hello admins!" );
    // }
}

/**
 *
 *  Get admin URL
 *
 */	
function cws_get_admin_url( $path = '' )
{
	global $wp_version;
	
	if ( version_compare( $wp_version, '3.0', '>=') ) {
		return get_admin_url( null, $path );
	}
	else {
		return get_bloginfo( 'wpurl' ) . '/wp-admin' . $path;
	}
}

/**
 *
 * Get Pages into a drop-down list
 *
 */
function cws_list_pages() {

	$get_page   = array();
	$pages_list = get_pages();
							
	// Loop through pages
	// Calling get_slug_by_id() and passing...
	$i = 0;
	
	foreach( $pages_list as $apage ) {
		// echo "Getting Ancestors for page_title = " . $apage->post_title   . "[ ". $apage->ID ." ]. Has parent_id of " . $apage->post_parent  .' path: ' . get_slug_by_id( $apage->ID) . "<br>";		
				
		// Start assembling the data in array...
		$path= get_slug_by_id( $apage->ID);

		$get_page[$i] = array(	'page_id' 		=> $apage->ID,
								'post_title'	=> "$apage->post_title",
								'post_path'		=> "$path",
								);
		$i++;
	}
			
	return $get_page;
}

/**
 *
 * Get all the pages
 *
 */
$pages_list = get_pages();

/**
 *
 * Get path to page. Takes into consideration parents and grandparents 
 * TODO: prefix function name with cws_
 *
 * $post_by_id, int
 * $_post_by_id_slug, string
 *
 * return string
 */
function get_slug_by_id( $post_by_id, $_post_by_id_slug = '' )
{
    $post_by_id_data   = get_page( $post_by_id, OBJECT );
    $post_by_id_parent = $post_by_id_data->post_parent;
    $post_by_id_slug   = $post_by_id_data->post_name;

    // If there are no more parents
    // return path/to/page
    if ( 0 == $post_by_id_parent ) {
    	      	       
      $_path .= $post_by_id_slug . '/'. $_post_by_id_slug ;	       	       
      
      return "$_path";	      
    } 
    else {
       	return get_slug_by_id( $post_by_id_parent, $post_by_id_slug );
    }		
}
	
/* -----------------------------------------------------------------------
 *	Setup custom URL parameters for use when displaying gallery images	
/*------------------------------------------------------------------------*/
function cws_gpp_add_query_var( $qvars ) {
	$qvars[] = 'album_id';
	return $qvars;
}	

// Add a new URL parameter
add_filter( 'query_vars', 'cws_gpp_add_query_var' );

// Retrieve and display the URL parameter
function cws_gpp_output_album_id() {
	global $wp_query;
	
	if( isset( $wp_query->query_vars['album_id'] ) ) {
		return $wp_query->query_vars['album_id'];
	}
}