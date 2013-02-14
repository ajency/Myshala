<?php

	// If uninstall not called from WordPress exit
	if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		exit();
	}
	
	// Delete option from the options table
	// delete_option( 'nak_gp_options' );
	
	global $wpdb;
	
	// Delete options
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'nak_gp_%';");
	$wpdb->query("DELETE FROM $wpdb->usermeta WHERE meta_key LIKE 'nak_gp_%';");
	
?>	