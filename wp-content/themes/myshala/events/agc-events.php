<?php 
/**
 * This file contains the definition of the events plugin as well as 
 * other includes and classes that might be used by the events modules.
 */


//add the  events class
require_once 'Agc_Event.php';

/**
 * Function to register the events post type
 */
function agc_register_events_post_type() {
	
	$labels = array(
			'name' 				=> _x('Events', 'post type general name', 'buddypress'),
			'singular_name' 	=> _x('Event', 'post type singular name', 'buddypress'),
			'add_new' 			=> _x('Add New', 'event', 'buddypress'),
			'add_new_item' 		=> __('Add New Event', 'buddypress'),
			'edit_item' 		=> __('Edit Event', 'buddypress'),
			'new_item' 			=> __('New Event', 'buddypress'),
			'all_items' 		=> __('All Events', 'buddypress'),
			'view_item' 		=> __('View Event', 'buddypress'),
			'search_items' 		=> __('Search Events', 'buddypress'),
			'not_found' 		=>  __('No events found', 'buddypress'),
			'not_found_in_trash' 	=> __('No events found in Trash', 'buddypress'),
			'parent_item_colon' 	=> '',
			'menu_name' 			=> __('Events', 'buddypress')

	);
	$args = array(
			'labels' 				=> $labels,
			'public' 				=> true,
			'publicly_queryable' 	=> true,
			'show_ui' 				=> true,
			'show_in_menu' 			=> true,
			'show_in_nav_menus' 	=> true,
			'query_var' 			=> true,
			'rewrite' 				=> array( 'slug' =>  'myshala-events' ),
			'capability_type' 		=> 'post',
			'has_archive' 			=> true,
			'hierarchical' 			=> false,
			'menu_position' 		=> 10,
			'supports' 				=> array( 'title', 'thumbnail')
	);
	register_post_type('agc_event', $args);
	
	
	//Register custom event taxonomy category.
	$labels = array(
			'name' 				=> _x( 'Event Categories', 'taxonomy general name' ),
			'singular_name' 	=> _x( 'Event Category', 'taxonomy singular name' ),
			'search_items' 		=> __( 'Search Event Categories' ),
			'all_items' 		=> __( 'All Event Categories' ),
			'parent_item' 		=> __( 'Parent Event Category' ),
			'parent_item_colon' => __( 'Parent Event Category:' ),
			'edit_item' 		=> __( 'Edit Event Category' ),
			'update_item' 		=> __( 'Update Event Category' ),
			'add_new_item' 		=> __( 'Add New Event Category' ),
			'new_item_name' 	=> __( 'New Event Category Name' ),
			'menu_name' 		=> __( 'Event Category' ),
	);
	
	register_taxonomy('agc_event_category',array('agc_event'), array(
		'hierarchical' => true,
		'labels' => $labels,	
		'show_ui' => true, // Change this to true to show the edit event category page.
		'query_var' => true,
		'rewrite' => array( 'slug' => 'myshala-event-category' ),
	));
	
	do_action('agc_after_register_event_taxonomy');
	
	 flush_rewrite_rules();
}
add_action( 'init', 'agc_register_events_post_type');

function agc_event_create_table()
{
	global $wpdb;
	$table_name = $wpdb->base_prefix . "agc_event_response_data";
	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name )
	{
		$create_agc_event_response_data = "CREATE TABLE $table_name (
		ID mediumint(9) NOT NULL AUTO_INCREMENT,
		event_id bigint(255) NOT NULL,
		user_id bigint(255) NOT NULL,
		user_name varchar(255) NOT NULL,
		blog_id bigint(255) NOT NULL,
		blog_name varchar(255) NOT NULL,
		response varchar(255),
		date_recorded varchar(255),
		status mediumint(9) NOT NULL,
		UNIQUE KEY ID (ID)
		);";
	
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($create_agc_event_response_data);
	}
	
	$table_name = $wpdb->base_prefix . "agc_event_invite_data";
	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name )
	{
		$create_agc_event_invite_data = "CREATE TABLE $table_name (
		ID mediumint(9) NOT NULL AUTO_INCREMENT,
		event_id bigint(255) NOT NULL,
		user_id bigint(255) NOT NULL,
		parent_id bigint(255) NOT NULL,
		UNIQUE KEY ID (ID)
		);";
	
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($create_agc_event_invite_data);
	}
}
add_action('agc_after_register_event_taxonomy', 'agc_event_create_table');

////////////////////////////////////////CSS AND JS///////////////////////////////////
function agc_event_admin_css_js($hook)
{
	global $post;
	if($post->post_type == 'agc_event')
	{
		wp_dequeue_script( 'autosave' );
		
		wp_enqueue_style('agc-event-style',get_template_directory_uri().'/events/css/agc-events-styles.css');
		wp_enqueue_style('agc-event-datepicker-style','http://code.jquery.com/ui/1.9.1/themes/smoothness/jquery-ui.css');
		wp_enqueue_style('agc-event-jqui-style',get_template_directory_uri().'/events/css/jquery-ui-timepicker-addon.css');
	
		
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-slider');
		
		wp_enqueue_script('agc-event-timepicker-script',get_template_directory_uri().'/events/js/jquery-ui-timepicker-addon.js',array(),'',true);
		wp_enqueue_script('agc-event-slider-addon',get_template_directory_uri().'/events/js/jquery-ui-sliderAccess.js',array(),'',true);
		wp_enqueue_script('agc-event-validate',get_template_directory_uri().'/events/js/jquery.validate.min.js',array(),'',true);
		wp_enqueue_script('agc-admin-script',get_template_directory_uri().'/events/js/agc-events-scripts.js',array(),'',true);
	}
}
add_action( 'admin_enqueue_scripts', 'agc_event_admin_css_js' );
//////////////////////////////////////META BOX///////////////////////////////////////
require_once ('inc/meta-box.php');
require_once ('inc/meta-save.php');

////////////////////////////////////EVENT FUNCTIONS/////////////////////////////////
require_once ('inc/event-functions.php');
