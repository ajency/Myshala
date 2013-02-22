<?php 
//This file contains the various function to insert activity at various steps, to appear in the activity stream.


///////////////////////////////////WPMU ACTIVITY FUNCTIONS//////////////////////////////////////////

//Function to restrict activity to only particular wpmu blog
function agc_activity_blog_activity($sql,$select_sql, $from_sql, $where_sql, $sort)
{
	global $bp,$wpdb;
	
	if(get_current_blog_id() != 1) //put more blog related conditions here if necessary.
	{
		$select_sql .= ",am.meta_value";
		$from_sql	.= " LEFT JOIN {$bp->activity->table_name_meta} am ON a.id = am.activity_id";
		$where_sql	.=	" AND am.meta_key = 'blog_id_".get_current_blog_id()."' AND am.meta_value = ".get_current_blog_id();
		$pag_sql	 = substr($sql,strpos($sql,'LIMIT'));
		
		$blog_activity_sql = $wpdb->prepare( "{$select_sql} {$from_sql} {$where_sql} ORDER BY a.date_recorded {$sort} {$pag_sql}" );
		return $blog_activity_sql;
	}
	
	return $sql;
}
add_filter('bp_activity_get_user_join_filter', 'agc_activity_blog_activity',10,5);

//Function to get the count of the activities for a particular blog
function agc_activity_blog_activity_count($sql, $where_sql, $sort)
{
	
	global $bp,$wpdb;
	if(get_current_blog_id() != 1) //put more blog related conditions here if necessary.
	{
		$where_sql	.=	" AND am.meta_key = 'blog_id_".get_current_blog_id()."' AND am.meta_value = ".get_current_blog_id();
		$blog_activity_count = $wpdb->prepare( "SELECT count(a.id) FROM {$bp->activity->table_name} a LEFT JOIN {$bp->activity->table_name_meta} am ON a.id = am.activity_id {$where_sql} ORDER BY a.date_recorded {$sort}" );
		return $blog_activity_count;
	}
	return $sql;
}
add_filter('bp_activity_total_activities_sql','agc_activity_blog_activity_count',10,3);

function agc_activty_core_add_blog_to_meta($activity = null,$blog_id = null)
{	
	global $bp,$wpdb;

	if(empty($activity) || empty($blog_id))
		return false;
	
	if ( $row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$bp->activity->table_name_meta} WHERE activity_id = %d AND meta_key = %s", $activity->id , 'blog_id_'.$blog_id) ) )
		$q = $wpdb->prepare( "UPDATE {$bp->activity->table_name_meta} SET activity_id = %d, meta_key = %s, meta_value = %s WHERE id = %d",$activity->id,'blog_id_'.$blog_id,$blog_id,$row->id);
	else
		$q = $wpdb->prepare( "INSERT INTO {$bp->activity->table_name_meta} ( activity_id, meta_key, meta_value) VALUES ( %d, %s, %s)",$activity->id,'blog_id_'.$blog_id,$blog_id);
		
	if ( false === $wpdb->query( $q ) )
		return false;
	
	return true;
}

//Add current blog id to activity meta for future filtering
function agc_activty_add_blog_to_meta($activity)
{
	if($activity->id)
	{
		$user_data 	= get_userdata($activity->user_id);
		$blog_id	=  (isset($user_data->primary_blog))?$user_data->primary_blog:get_current_blog_id();
		
		agc_activty_core_add_blog_to_meta($activity,get_current_blog_id());
		
		if($blog_id != get_current_blog_id())
		{
			agc_activty_core_add_blog_to_meta($activity,$blog_id);
		}
		
		if($activity->type == 'new_event')
		{
			$event_id 		= $activity->item_id;
			$event_privacy 	= get_post_meta($event_id,'agc_event_invitee_type',true);
			switch ($event_privacy)
			{	  
				case 'multi_chapters':
					$invited_chapters = get_post_meta($event_id,'agc_event_invited_chapters',true);
					if($invited_chapters)
					{
						foreach ($invited_chapters as $chapter)
						{
							if($chapter != get_current_blog_id())
							{
								agc_activty_core_add_blog_to_meta($activity,$chapter);
							}
						}
					}
					break;
				default:
					break;	
			}
		}
	}
	return true;
}
add_action('bp_activity_after_save','agc_activty_add_blog_to_meta');

//Filter the single activity link for wpmu sites.
function agc_single_activity_permalink($link,$activity)
{

	$activity_obj = $activity;

	if ( isset( $activity_obj->current_comment ) ) {
		$activity_obj = $activity_obj->current_comment;
	}

	if ( 'new_blog_post' == $activity_obj->type || 'new_blog_comment' == $activity_obj->type || 'new_forum_topic' == $activity_obj->type || 'new_forum_post' == $activity_obj->type ) {
		$link = $activity_obj->primary_link;
	} else {
		if ( 'activity_comment' == $activity_obj->type ) {
			$link = bp_get_root_domain() . '/' . bp_get_activity_root_slug() . '/p/?a=' . $activity_obj->item_id . '/';
		} else {
			$link = bp_get_root_domain() . '/' . bp_get_activity_root_slug() . '/p/?a=' . $activity_obj->id . '/';
		}
	}

	return $link;
}
add_filter('bp_activity_get_permalink', 'agc_single_activity_permalink',10,2);

//Redirect to single activity page for wpmu sites.
function agc_activity_page_template( $page_template )
{
	if(bp_current_action() == 'p')
	{
		include(TEMPLATEPATH . '/members/single/activity/permalink.php');
		exit;
	}
}
add_action( 'template_redirect', 'agc_activity_page_template' );

/////////////////////////////////////////////////////ADD NEW ACTIVITY AND FILTERS////////////////////////////////

//Function add additional filters to the activity filter dropdown.
function agc_add_activity_filter_options()
{
	echo '	<option value="new_event">'.__( 'New Event', 'buddypress' ).'</option>';
	echo '	<option value="event_rsvp">'.__( 'Event RSVP\'s', 'buddypress' ).'</option>';
}
add_action('bp_activity_filter_options','agc_add_activity_filter_options');



//When new member is registerd prevent the activity from being created.
function agc_activity_no_register_activity($type,$activity)
{
	if($type == 'new_member')
	{
		$login_steps  = get_user_meta($activity->user_id, 'agc_login_steps_completed',true);
		
		if(!$login_steps || $login_steps != 'completed')
		{
			return false;
		}
	} 	
	return $type;
}
add_filter('bp_activity_type_before_save', 'agc_activity_no_register_activity',10,2);

//Insert new user registered activity after completion of login steps.
function agc_activity_create_new_user_activity($step_number,$current_step)
{
	if($step_number == 'completed')
	{
		$user_id	=  	bp_loggedin_user_id();
		$userlink 	= 	bp_core_get_userlink( $user_id);
		
		bp_activity_add( array(
		'user_id'   => $user_id,
		'action'    => apply_filters( 'agc_activity_registered_member_action', sprintf( __( '%s became a registered member', 'buddypress' ), $userlink ), $user_id ),
		'component' => 'xprofile',
		'type'      => 'new_member'
				) );
	}
	return ;
}
add_action('agc_after_login_step_saved','agc_activity_create_new_user_activity',10,2);


//Insert new event created activity on publishing an event.
function agc_activity_create_new_event_activity($post_ID, $post)
{
	$event = get_post($post_ID);
	if($event->post_type == 'agc_event' && $event->post_status == 'publish')
	{
		$user_id 	= $event->post_author;
		$userlink 	= bp_core_get_userlink( $user_id);
		$event_link		= '<a href="' . get_permalink($post_ID) . '" title="' . $event->post_title . '">'.__($event->post_title,'buddypress').'</a>';

		$action		= apply_filters( 'agc_activity_new_event_action', sprintf( __( '%s created a new event', 'buddypress' ), $userlink), $user_id,$post_ID ,$userlink);
		$content	= apply_filters( 'agc_activity_new_event_content', sprintf( __( '%s', 'buddypress' ), $event_link),$post_ID);

		$args =  array(
				'user_id'   => $user_id,
				'item_id'	=> $post_ID,
				'component' => 'xprofile',
				'type'      => 'new_event'
		);

		//If you want to delete previous instances on the event updates activity.
		//if($activity_id = bp_activity_get_activity_id( $args ))
		//{
		//	bp_activity_delete_by_activity_id($activity_id);
			
			$args['action'] 	=  $action;
			$args['content']	= $content;

			bp_activity_add( $args);
		//}
	}
	return;
}
add_action('wp_insert_post','agc_activity_create_new_event_activity',10,2);

//Event rsvp responses for activity content.
function agc_activity_event_rsvp_responses($response,$user_id,$event_id)
{
	if(!$response || !$user_id || !$event_id)
		return false;
	
	$userlink 	= bp_core_get_userlink( $user_id);
	$event		= get_post($event_id);
	$event_link		= '<a href="' . get_permalink($event_id) . '" title="' . $event->post_title . '">'.__($event->post_title,'buddypress').'</a>';
	
	switch ($response)
	{
		case 'yes':
			$reply = sprintf(__('%s is going to attend the event %s','buddypress'),$userlink,$event_link);
			break;
		case 'maybe':
			$reply = sprintf(__('%s may be attending the event %s','buddypress'),$userlink,$event_link);
			break;
		case 'no':
			$reply = sprintf(__('%s will not be attending the event %s','buddypress'),$userlink,$event_link);
			break;
		default:
			$reply = false;
			break;			
	}
	
	return apply_filters('agc_activity_event_rsvp_responses', $reply,$response,$user_id,$event_id,$event);
}

//Function to create an activity  when user rsvp's for the first time or updates his rsvp status.
function agc_activity_create_event_rsvp_activity($user_id, $event_id)
{
	global $wpdb;
	$response_table = $wpdb->base_prefix.'agc_event_response_data';
	
	$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$response_table} WHERE `user_id`= %d AND `event_id` = %d",$user_id,$event_id));
	
	if($row)
	{
		$userlink 	= bp_core_get_userlink( $user_id);
		
		$reply		= agc_activity_event_rsvp_responses($row->response,$user_id,$event_id);
		$action		= apply_filters( 'agc_activity_event_rsvp_action', sprintf( __( '%s rsvp\'d to an event', 'buddypress' ), $userlink), $user_id,$event_id);
		$content	= apply_filters( 'agc_activity_event_rsvp_content', sprintf( __( '%s', 'buddypress' ), $reply),$user_id,$event_id);
		
		$args =  array(
				'user_id'   => $user_id,
				'item_id'	=> $event_id,
				'component' => 'xprofile',
				'type'      => 'event_rsvp'
		);
		
		$new_args =  array(
				'user_id'   => $user_id,
				'item_id'	=> $event_id,
				'action'    => $action,
				'content'	=> $content,
				'component' => 'xprofile',
				'type'      => 'event_rsvp'
		);
		
		$activity_id = bp_activity_get_activity_id( $args );		
		if($activity_id)
		{
			bp_activity_delete_by_activity_id( $activity_id );
			bp_activity_add( $new_args);
		}
		else
			bp_activity_add( $new_args);
	}
}
add_action('agc_after_insert_event_rsvp','agc_activity_create_event_rsvp_activity',10,2);
add_action('agc_after_update_event_rsvp','agc_activity_create_event_rsvp_activity',10,2);

//If the user is taking a date along delete old activity and insert new activity to show the same.
function agc_activity_create_plusone_activity($event_id,$plusone,$user_id)
{
	global $wpdb;
	$response_table = $wpdb->base_prefix.'agc_event_response_data';	
	$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$response_table} WHERE `user_id`= %d AND `event_id` = %d",$user_id,$event_id));
	$userlink 	= bp_core_get_userlink( $user_id);
	
	$event_categories = wp_get_object_terms($event_id, 'agc_event_category',array('fields' => 'names'));
	
	if($event_categories)
	{
		if(in_array('Date Party',$event_categories))
		{
			$plusone_link 	= bp_core_get_userlink( $plusone);
			$reply			= agc_activity_event_rsvp_responses($row->response,$user_id,$event_id);			
			
			$args =  array(
					'user_id'   => $user_id,
					'item_id'	=> $event_id,
					'component' => 'xprofile',
					'type'      => 'event_rsvp'
			);
			
			if($activity_id = bp_activity_get_activity_id( $args ))
				bp_activity_delete_by_activity_id( $activity_id );
			
			$args['secondary_item_id'] = $plusone;
			
			$args['action']		= apply_filters( 'agc_activity_event_rsvp_plusone_action', sprintf( __( '%s rsvp\'d to an event', 'buddypress' ), $userlink), $user_id,$event_id);
			$args['content'] 	= apply_filters( 'agc_activity_event_rsvp_plusone_content', sprintf( __( '%s', 'buddypress' ), $reply),$user_id,$event_id);
				
			bp_activity_add( $args);
		}
	}
	return ;
}
add_action('agc_event_after_save_plus_one','agc_activity_create_plusone_activity',10,3);

//If the user changes the rsvp status but keeps the plusone the same add the plus one to the new activity.
function agc_activity_update_plusone_activity($reply,$response,$user_id,$event_id,$event)
{
	$event_categories = wp_get_object_terms($event_id, 'agc_event_category',array('fields' => 'names'));
	if($event_categories)
	{
		if(in_array('Date Party',$event_categories))
		{
			$e = new Agc_Event($event_id);
			$plusone		= $e->agc_event_get_plus_one($event_id,$user_id);
			$plusone_link 	= bp_core_get_userlink($plusone);
			
			if(($response== 'yes' || $response == 'maybe') && $plusone_link)
				$reply .= sprintf(__(" with %s",'buddypress'),$plusone_link);
		}
	}
	
	return $reply;
}
add_filter('agc_activity_event_rsvp_responses', 'agc_activity_update_plusone_activity',10,5);


//Notify users when a new comment is put on their activity stream item.
function agc_activity_notify_activity_comment($comment_id,$params)
{
	global $wpdb,$bp;
	
	extract( $params, EXTR_SKIP );
	
	if ( empty( $parent_id ) )
		$parent_id = $activity_id;
	
	$original_activity = new BP_Activity_Activity( $activity_id );
	
	$comment = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$bp->activity->table_name} WHERE `type` = %d AND `id` = %d",$type,$comment_id));
	
	$notify_users = array();

	if($comment && $comment->item_id != 0)
	{
		$absolute_parent = new BP_Activity_Activity($comment->item_id);
		
		if($absolute_parent->user_id != $user_id && 'no' != bp_get_user_meta( $absolute_parent->user_id, 'notification_activity_new_reply', true ))
			$notify_users[]  = $absolute_parent->user_id;
	}
	
	if($comment && $comment->secondary_item_id != 0)
		{
			$comment_parent = new BP_Activity_Activity($comment->secondary_item_id);
			
			if($comment_parent->user_id != $user_id && 'no' != bp_get_user_meta( $comment_parent->user_id, 'notification_activity_new_reply', true ))
				$notify_users[]  = $comment_parent->user_id;
		}
		
	if(count($notify_users) > 0)
	{
		$args = array(
				'user_id'  				=>  $notify_users,// The user id of the activity item
				'component_id'			=>  'bp_activity_comment',
				'send_message'			=> 	true,
				'send_email'			=>  true,
				'secondary_item_id' 	=> $comment_id,
				'secondary_item_type' 	=> 'activity_comment',
		);

		$comm = new AGC_COM($args);
		$comm->agc_send();
	
	}	
	return;
}
add_action( 'bp_activity_comment_posted','agc_activity_notify_activity_comment',10,2);


//Com module format message for activity comment.
function agc_activity_comment_comm_filter_msg($msg,$user_id,$id,$type,$comp_type)
{
	global $bp,$wpdb;
	
	$comment = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$bp->activity->table_name} WHERE `type` = %d AND `id` = %d",$type,$id));
	
	if($comment)
	{
		$commentter 	= get_userdata($comment->user_id);
		$user  			= get_userdata($user_id);
		
		switch($type)
		{
			case 'activity_comment':
				
				$comment_link 	= bp_activity_get_permalink($comment->id);
				
				$msg['subject'] = str_replace('[commenter]',$commentter->display_name,$msg['subject']);
				
				$msg['body'] 	= str_replace('[username]',$user->display_name,$msg['body']); //username
				$msg['body'] 	= str_replace('[commenter]',$commentter->display_name,$msg['body']); //commenter
				$msg['body'] 	= str_replace('[commentlink]',$comment_link,$msg['body']); //link

				break;
			default:
				break;
		}
	}
	return $msg;
}
add_filter('agc_format_message', 'agc_activity_comment_comm_filter_msg',10,5);

/**
 * Function to change the user link to reflect the user site.
 * @param string $domain
 * @param int $user_id
 * @param string $user_nicename
 * @param string $user_login
 * @return void|string
 */
function agc_bp_core_user_domain( $domain, $user_id, $user_nicename, $user_login)
{
	if ( empty( $user_id ) )
		return;

		$username = bp_core_get_username( $user_id, $user_nicename, $user_login );

		if ( bp_is_username_compatibility_mode() )
			$username = rawurlencode( $username );

		$after_domain = bp_core_enable_root_profiles() ? $username : bp_get_members_root_slug() . '/' . $username;
		
		$user_data 		= get_userdata($user_id);
		
		if(is_multisite())
		{
			$blog_id = (isset($user_data->primary_blog))?$user_data->primary_blog:1;	
			$blog_domain = get_blog_option($blog_id,'siteurl',bp_get_root_domain());	
		}
		else
			$blog_domain = bp_get_root_domain();
		
		$domain       = trailingslashit( $blog_domain . '/' . $after_domain );

	return $domain;
}
add_filter( 'bp_core_get_user_domain', 'agc_bp_core_user_domain',10,4);

/**
 * Function to change the content of the new/updated event acitvity
 * @param string $html
 * @param int $event_id
 * @return string
 */
function agc_event_new_event_content($html,$event_id)
{
	$event 			= get_post($event_id);
	$event_meta 	= get_post_meta($event_id,'agc_event_date_time',true);
	$event_image	= get_the_post_thumbnail($event_id,'medium',array('class' => 'agc-activity-event-image','title' => $event->post_title));
	
	$from_date 		= $event_meta['from_date']; 
	$to_date 		= $event_meta['to_date'];
	
	if(empty($to_date))
		$to_date = $from_date;
	
		if(strtotime($from_date) == strtotime($to_date))
			$event_date =  date('l, dS F Y', strtotime($from_date));
		else
			$event_date =  'From ' . date('D, dS M Y', strtotime($from_date)) . ' to ' . date('D, dS M Y', strtotime($to_date));
	
	$html 	 = '<a class="agc-activity-event-meta-title" href="'.get_permalink($event->ID).'">'.$event->post_title.'</a>';
	$html	.= '<span class="agc-activity-event-meta-date">'.$event_date.'</span>';
	$html	.= '<span class="agc-activity-event-meta-time">@'.$event_meta['from_time'].'</span>';
	$html	.= '<a href="'.get_permalink($event->ID).'">'.$event_image.'</a>';
	
	return $html;
}
add_filter('agc_activity_new_event_content','agc_event_new_event_content',10,2);

/**
 * Functiont to change the event activity action when even is updated.
 * @param string $html
 * @param int $user_id
 * @param int $event_id
 * @param string $user_link
 * @return string
 */
function agc_event_update_event_action($html,$user_id,$event_id,$user_link)
{
	$args =  array(
			'user_id'   => $user_id,
			'item_id'	=> $event_id,
			'component' => 'xprofile',
			'type'      => 'new_event'
	);
	
	if($activity_id = bp_activity_get_activity_id( $args ))
	{
		$html = sprintf( __( '%s updated an event', 'buddypress' ), $user_link);
	}
	return $html;
}
add_filter('agc_activity_new_event_action', 'agc_event_update_event_action',10,4);