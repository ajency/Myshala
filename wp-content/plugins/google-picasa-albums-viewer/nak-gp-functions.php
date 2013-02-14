<?php
/**
 * Setup custom URL parameters for use 
 * when displaying gallery images
 */

function add_query_var( $qvars ) {
     $qvars[] = 'album_id';     
     return $qvars;
}

add_filter('query_vars', 'add_query_var');				// Add a new URL parameter

// Retrieve and display the URL parameter
function output_album_id() {

     global $wp_query;

     if( isset( $wp_query->query_vars['album_id'] ) ) {
          return $wp_query->query_vars['album_id'];
     }
}

/*
wp_deregister_script('jquery');
wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"), false, '1.3.2');
wp_enqueue_script('jquery');
*/

