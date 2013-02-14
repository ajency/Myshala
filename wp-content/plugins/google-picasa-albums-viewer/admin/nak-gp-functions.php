<?php
/**
 * Get Pages into a drop-down list
 */
function list_pages() {

	$get_page;
	$pages_list = get_pages();
	$get_page   = array();
			
	foreach( $pages_list as $apage ) {
		$get_page[$apage->post_name] = $apage->post_title;
	}
	
	array_unshift( $get_page, "Select a page:" );
	
	return $get_page;
}

/**
 * Setup custom URL parameters for use 
 * when displaying gallery images
 */
/*
function add_query_var( $qvars ) {

     $qvars[] = 'album_id';     
     return $qvars;
}

add_filter('query_vars', 'add_query_var');				// Add a new URL parameter

function output_album_id() {							// Retrieve and display the URL parameter

     global $wp_query;
   
	
	// echo '<pre>';
    // print_r($wp_query->query_vars);
    // echo '</pre>'; 
	

     if( isset( $wp_query->query_vars['album_id'] ) ) {
          return $wp_query->query_vars['album_id'];
     }
}
*/