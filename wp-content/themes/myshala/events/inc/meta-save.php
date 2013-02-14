<?php 
function agc_event_meta_box_desc_save( $event_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	if ( !wp_verify_nonce( $_POST['agc_event_meta_box_desc_nonce'],'event-invitees' ) )
		return;


	// Check permissions
	if ( 'page' == $_POST['post_type'] )
	{
		if ( !current_user_can( 'edit_page', $event_id ) )
			return;
	}
	else
	{
		if ( !current_user_can( 'edit_post', $event_id ) )
			return;
	}
	$event = get_post($event_id);
	$event_desc 	= $_POST['agc_event_desc'];
	$invitee_type 	= $_POST['event_invitees'];
	
	global $wpdb;
	
	$wpdb->update($wpdb->posts, array('post_content' => $event_desc), array('ID' => $event_id));
	
	switch ($invitee_type)
	{
		case 'invitees_only':
				update_post_meta($event_id,'agc_event_invitee_type','invitees_only');
				update_post_meta($event_id,'agc_event_invited_chapters',array(get_current_blog_id()));
				do_action('agc_event_meta_save_invitees_only',$event_id);
			break;
		
		case 'chapter':	
			default:
				update_post_meta($event_id,'agc_event_invitee_type','chapter');
				update_post_meta($event_id,'agc_event_invited_chapters',array(get_current_blog_id()));
				do_action('agc_event_meta_save_chapter',$event_id);
			break;
	}

}
add_action( 'save_post', 'agc_event_meta_box_desc_save' ,20);

add_action( 'save_post', 'agc_event_meta_box_datetime_save',10 );
function agc_event_meta_box_datetime_save( $event_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	if ( !wp_verify_nonce( $_POST['agc_event_meta_box_datetime_nonce'],'agc_event_meta_box_datetime' ) )
		return;

	// Check permissions
	if ( 'page' == $_POST['post_type'] )
	{
		if ( !current_user_can( 'edit_page', $event_id ) )
			return;
	}
	else
	{
		if ( !current_user_can( 'edit_post', $event_id ) )
			return;
	}
	$data = array(
			'from_date' => $_POST['agc_selected_from_date'],
			'to_date' 	=> $_POST['agc_selected_to_date'],
			'from_time' => $_POST['agc_selected_from_time'],
			'to_time' 	=> $_POST['agc_selected_to_time'],
			'venue' 	=> $_POST['agc_selected_venue'],
			);
	
	update_post_meta($event_id, 'agc_event_date_time', $data);
}
