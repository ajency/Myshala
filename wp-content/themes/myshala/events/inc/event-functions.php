<?php 
/////////////////////////Greekconnect Custom Functions/////////////////////////////

/**
 * Function to return the url for the events archive page.
 * @return string
 */
function agc_events_url()
{
	$link = get_post_type_archive_link( 'agc_event' );
	
	return apply_filters('agc_events_archive_url', $link);
}

/**
 * redirect to the template file inside events folder
 * @param unknown_type $page_template
 */
function agc_events_page_template( $page_template )
{
	if(is_singular('agc_event'))
	{
		global $event;
		//prepare the global event object
		global $post; 
		$event = new Agc_Event($post->ID);
		
		//Show network user the event even if it has passed.
		if(current_user_can('manage_options'))
		{
			include(get_stylesheet_directory()  . '/events/event-single.php');
			exit;
		}
		
		//Always show the event page even after the event has passed.
		include(get_stylesheet_directory()  . '/events/event-single.php');
		exit;
	}
	else 
		if(is_post_type_archive( 'agc_event' ))
		{
			include(get_stylesheet_directory() . '/events/event-page.php');
			exit;
		}
}
add_action( 'template_redirect', 'agc_events_page_template' );

function agc_event_enqueue_calendar_js()
{
	if(is_post_type_archive( 'agc_event' ) || is_page('Events'))
	{
		wp_enqueue_style('agc-event-calendar-style',get_stylesheet_directory_uri().'/events/css/eventCalendar.css');
		wp_enqueue_style('agc-event-calendar-responsive',get_stylesheet_directory_uri().'/events/css/eventCalendar_theme_responsive.css');	
		wp_enqueue_script('agc-event-calendar',get_stylesheet_directory_uri().'/events/js/jquery.eventCalendar.js',array('jquery'),false,true);
	}
}
add_action('wp_enqueue_scripts', 'agc_event_enqueue_calendar_js');

/**
 * Function to get the members belonging to a particular blog 
 * @param number $blog_id
 * @param number $page
 * @param number $per_page
 * @return array $members
 */
function agc_event_get_members($blog_id=1,$page = 1,$event_id=1,$per_page = 20)
{
	if(is_multisite())
		$bp_user_query = 'type=alphabetical&per_page='.$per_page.'&page='.$page.'&meta_key=primary_blog&meta_value='.$blog_id;
	else
		$bp_user_query = 'type=alphabetical&per_page='.$per_page.'&page='.$page;
		
	if ( bp_has_members($bp_user_query) ) :
		
		global $members_template;
		$total = ceil( (int) $members_template->total_member_count / (int) $members_template->pag_num );

		
		while ( bp_members() ) : bp_the_member();
			$checked = (agc_event_member_invited(bp_get_member_user_id(),$event_id))?'checked="checked"':'';
			$html .= '<label for="agc_chapter_member'.bp_get_member_user_id().'"><input type="checkbox" '.$checked.' value="'.bp_get_member_user_id().'" name="agc_chapter_member[]" class="agc_chapter_member" id="agc_chapter_member'.bp_get_member_user_id().'">&nbsp'.bp_get_member_name().'</label><br>';
		endwhile;
		
		if(!($page >= $total))
		{
			$nonce = wp_create_nonce('event-loadmore-members');
			$html .= '<a class="button-secondary invitees_loadmore" id="invitees_loadmore_ajax" rel="'.$page.'" data-nonce="'.$nonce.'" href="javascript:void(0);" title="Load More">Load More</a>';
		}
		return $html;
	else:
		return 'No members were found';
	endif;
}

/**
 * Function to get the blogs in a checkbox format.
 * @return string
 */
function agc_event_get_blogs($event_id = 0)
{
	$blogs = agc_get_subdomain_ids();
	if(count($blogs) > 0)
	{
		foreach ($blogs as $blog)
		{
			$blog_name = get_blog_option($blog,'blogname','');
			$invited_blogs = get_post_meta($event_id,'agc_event_invited_chapters',true);
			
			$invited_blogs = ($invited_blogs)?$invited_blogs:array();
			
			$checked = (in_array($blog,$invited_blogs))?'checked="checked"':'';
			$html .= '<label for="agc_chapter_'.$blog.'"><input type="checkbox" '.$checked.' name="agc_chapters[]" id="agc_chapter_'.$blog.'" class="agc_chapters" value="'.$blog.'">&nbsp'.$blog_name.'</label><br>';
		}
	}
	else
	{
		$html = 'No Blogs Found';
	}
	
	return $html;
}

/**
 * Function to populate the invitee list based on the invitee type.
 */
function agc_event_invitees_ajax()
{
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;
	
	$nonce = $_POST['_wpnonce'];
	if (! wp_verify_nonce($nonce, 'event-invitees') )
		return;
	
	$invitee_type = $_POST['type'];
	$event_id	  = $_POST['eid'];
	switch($invitee_type)
	{
		case 'invitees_only':
				$html = agc_event_get_members(get_current_blog_id(),1,$event_id);
			break;
		default:
			break;	
	}
	
	echo $html;
	exit;
}
add_action('wp_ajax_event_invitees','agc_event_invitees_ajax');

/**
 * Function to load more members into the invitees list
 */
function agc_event_invitees_loadmore_ajax()
{
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;
	
	$nonce = $_POST['_wpnonce'];
	if (! wp_verify_nonce($nonce, 'event-loadmore-members') )
		return;
	
	$page_num = $_POST['page_num'];
	$page_num = (int)$page_num + 1;
	$event_id	  = $_POST['eid'];
	$html = agc_event_get_members(get_current_blog_id(),$page_num,$event_id);
	
	echo $html;
	exit;
}
add_action('wp_ajax_invitees_loadmore_ajax','agc_event_invitees_loadmore_ajax');


function agc_event_ajax_send_invites()
{
	header( "Content-Type: application/json" );
	
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;
	
	$nonce = $_POST['_wpnonce'];
	if (! wp_verify_nonce($nonce, 'event-invitees') )
		return;
	
	$invitee_type = $_POST['type'];
	$selected	  = $_POST['selected'];
	$channels	  = $_POST['channels'];
	$event_id	  = $_POST['eid'];

	$stored_type = get_post_meta($event_id,'agc_event_invitee_type',true);
	
	if($stored_type != $invitee_type)
	{
		$response = json_encode( array('result' => 'failed','msg' => 'Please save the event and try sending the invites again.' ));	
		echo $response;
		exit;
	}
	
	switch ($invitee_type)
	{
		case 'invitees_only':
			agc_event_notify_members($selected,$channels,$event_id);
			acg_event_log_invitees_only($selected,$event_id);
			break;
			
		case 'chapter':
		default:
			agc_event_notify_chapters(array(get_current_blog_id()), $channels, $event_id);
			break;
	}
	
	$response = json_encode( array('result' => 'success','msg' => 'Members have been successfully notified.' ));

	echo $response;
	exit;
}
add_action('wp_ajax_send_invites','agc_event_ajax_send_invites');

function agc_event_notify_members($selected,$channels,$event_id)
{
	$args = array(
			'user_id'  				=> $selected,
			'component_id'			=> 'bp_event',
			'send_message'			=> (in_array('message',$channels))? true :false,
			'send_notification' 	=> (in_array('notification',$channels))? true :false,
			'send_email'			=> (in_array('email',$channels))? true :false,
			'send_sms'				=> (in_array('sms',$channels))? true :false,
			'secondary_item_id' 	=> $event_id,
			'secondary_item_type' 	=> 'event',
	);
	
	$comm = new AGC_COM($args);	
	$comm->agc_send();
}

function agc_event_notify_memebers_email($returned,$component_id,$user_id,$secondary_item_id,$secondary_item_type)
{
	if($returned === false)
	{
		if($secondary_item_type == 'event')
		{
			$msg 		= array();
			$event 		= get_post($secondary_item_id);
			$event_link = get_permalink($secondary_item_id);
			$user  		= get_user_by('id',$user_id);
			$blogname	= get_option('blogname','MyShala');
			//Line spacing is required to maintain the same in the internal messaging.
			$msg['body'] 		= "Hi {$user->display_name},
			
			This is to notify you that you have been invited to the event {$event->post_title}.
			
			";
			$msg['extrabody'] 	= "To learn the details about the event please click <a href=\"{$event_link}\" >here</a>";
			$msg['subject']		= "[".$blogname."] New Event Invitation";
			return $msg;
		}
	}
	
	return $returned;
}
add_filter('agc_get_message_components','agc_event_notify_memebers_email',10,5);

/**
 * Function to check is a member has already been invited to an event.
 * @param int $member_id
 * @param int $event_id
 * @return boolean
 */
function agc_event_member_invited($member_id,$event_id)
{
	global $wpdb;
	$events_table = $wpdb->base_prefix . "agc_event_invite_data" ;
	$member = $wpdb->get_var($wpdb->prepare("SELECT `event_id` FROM $events_table WHERE `event_id`=%d AND `user_id`=%d",$event_id,$member_id));
	if($member)
		return true;
	
	return false;
}

/**
 * Function to enter initial member invite.
 * @param int $member_id
 * @param int $event_id
 */
function agc_event_add_to_table($member_id,$event_id)
{
	global $wpdb;
	$events_table 	= $wpdb->base_prefix . "agc_event_invite_data" ;
	$member 	  	= get_userdata($member_id);
	$args = array(
			'event_id' 		=> $event_id,
			'user_id'  		=> $member_id,
			'parent_id'		=> 0,
			);
	$wpdb->insert($events_table, $args);
}
/**
 * Function to insert invitees only into invitees table
 * @param array $members
 */
function acg_event_log_invitees_only($members,$event_id)
{
	if($members)
	{
		foreach ($members as $member)
		{
			if(!agc_event_member_invited($member,$event_id))
			{
				agc_event_add_to_table($member,$event_id);
			}
		}
	}
}

/**
 * Function to return the ID's of users of a blog.
 * @param int $blog_id
 * @return Ambigous <multitype:, array>
 */
function agc_event_get_blog_members($blog_id)
{
	return get_users('blog_id='.$blog_id.'&fields=ID');
}

/**
 * Function to invite all the members of selected blog.
 * @param array $selected The blog ids
 * @param array $channels The communication channels
 * @param int $event_id	  The event id.
 */
function agc_event_notify_chapters($selected, $channels, $event_id)
{
	if($selected)
	{
		foreach($selected as $blog_id)
		{
			$members =  agc_event_get_blog_members($blog_id);
			agc_event_notify_members($members,$channels,$event_id);
		}
	}
}


/**
 * Function to get the names of users via ajax for auto complete.
 */
function agc_event_suggest_names() {
	global $bp;

	$pag_page = 1;
	$limit    = 10;

	// Get the user ids based on the search terms
		$users = BP_Core_User::search_users( $_GET['q'], $limit, $pag_page );

		if ( ! empty( $users['users'] ) ) {
			// Build an array with the correct format
			$user_ids = array();
			foreach( $users['users'] as $user ) {
				if ( $user->id != bp_loggedin_user_id() )
					$user_ids[] = $user->id;
			}

			$user_ids = apply_filters( 'bp_core_autocomplete_ids', $user_ids, $_GET['q'], $limit );
		}


	if ( ! empty( $user_ids ) ) {
		echo '<ul class="suggested_name_list">';
		foreach ( $user_ids as $user_id ) {
			$ud = get_userdata( $user_id );
			if ( ! $ud )
				continue;

			if ( bp_is_username_compatibility_mode() )
				$username = $ud->user_login;
			else
				$username = $ud->user_nicename;
			
			$user_blog_id = get_user_meta($ud->ID,'primary_blog',true);
			if(!empty($user_blog_id))
			{
				$user_blog_name = get_blog_option($user_blog_id, 'blogname');
				$user_blog_name = '&nbsp;('.$user_blog_name.')';
			}

			// Note that the final line break acts as a delimiter for the
			// autocomplete javascript and thus should not be removed
			echo '<li class="suggested_name_item" data-id="'.$user_id.'" data-name="' . bp_core_get_user_displayname( $user_id ) . '"><a onClick="_agc_select_suggested_name(this);" href="javascript:void(0);">'.bp_core_fetch_avatar( array( 'item_id' => $user_id, 'type' => 'thumb', 'width' => 15, 'height' => 15, 'alt' => $ud->display_name ) ).'<span class="suggested_name_name">' . bp_core_get_user_displayname( $user_id ). $user_blog_name . '</span></a></li>';
			
		}
		echo '</ul>';
	}

	exit;
}
add_action('wp_ajax_agc_event_suggest_names', 'agc_event_suggest_names');

/**
 * Function to save the plus one data for and event
 */
function agc_event_save_plus_one()
{
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;
	
	$nonce = $_POST['_wpnonce'];
	if (! wp_verify_nonce($nonce, 'invitee_date') )
		return;
	
	$user_id 	= $_POST['id'];
	$event_id 	= $_POST['eid'];
	
	header( "Content-Type: application/json" );
	
	
	
	global $wpdb;
	$events_table = $wpdb->base_prefix . "agc_event_invite_data" ;
	$row_id = $wpdb->insert($events_table,array('event_id' => $event_id ,'user_id' => $user_id, 'parent_id' => bp_loggedin_user_id()));
	
	do_action('agc_event_after_save_plus_one',$event_id,$user_id,bp_loggedin_user_id());
	
	$e = new Agc_Event($event_id);
	echo json_encode(array('result' => $e->agc_event_invited_date($user_id)));
	
	exit;
}
add_action('wp_ajax_agc_event_save_plus_one', 'agc_event_save_plus_one');

function agc_ajax_event_cancel_plus_one()
{
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;
	
	$nonce = $_POST['_wpnonce'];
	if (! wp_verify_nonce($nonce, 'remove_date') )
		return;
		
	$event_id 	= $_POST['eid'];
		
	header( "Content-Type: application/json" );
	
	$e = new Agc_Event($event_id);
	$test = $e->agc_event_cancel_plus_one($event_id, bp_loggedin_user_id());
	
	do_action('agc_event_after_cancel_plus_one',$event_id,bp_loggedin_user_id());
	
	echo json_encode(array('result' => 'success'));
	exit;
}
add_action('wp_ajax_agc_ajax_event_cancel_plus_one', 'agc_ajax_event_cancel_plus_one');



/**
 * save the user event rsvp response
 */
function agc_send_user_event_rsvp()
{
	global $wpdb;
	$response_table = $wpdb->base_prefix.'agc_event_response_data';





	//post data
	$event_id = $_POST['event_id'];
	$response = strtolower($_POST['response']);
	$user_id  = bp_loggedin_user_id();

	//invitee_data if available
	$e = new Agc_Event($event_id);

	//Delete invitee data if response is no
	if($response == 'no')
		$e->agc_event_cancel_plus_one($event_id,$user_id);

	$plus_one = $e->agc_event_get_plus_one($event_id);
	$plus_one = ($plus_one)? true : $plus_one;

	//check if user has already replied
	$is_rsvp = $wpdb->get_var($wpdb->prepare("SELECT COUNT(response) FROM $response_table WHERE `user_id`='$user_id' AND `event_id`='$event_id'"));

	if($is_rsvp == 1)
	{
		//user has already replied to event. Update the data
		$wpdb->update(
				$response_table,
				array(
						'response' => $response,	// string
				),
				array(
						'user_id'  => $user_id,
						'event_id' => $event_id
				)
		);
		
		do_action('agc_after_update_event_rsvp',$user_id, $event_id);
		
		echo json_encode(array(
				"code"     => "2",
				"response" => "Successfully updated your response",
				"html" => "<p style='text-align:center;'>
												<a class='btn ". ($response == 'yes' ? 'btn-success' : '') ." rsvp-action'>Yes</a>&nbsp;
												<a class='btn ". ($response == 'no' ? 'btn-success' : '')  ." rsvp-action'>No</a>&nbsp;
												<a class='btn ". ($response == 'maybe' ? 'btn-success' : '') ." rsvp-action'>Maybe</a>
											</p>",
				"date"	=> $plus_one,
		));
	}
	else
	{
		$user_data 		= get_userdata($user_id);
		$primary_blog	= (isset($user_data->primary_blog))?$user_data->primary_blog:1;
		if(is_multisite())
			$blog_name 		= get_blog_option($primary_blog, 'blogname','Blog name Here');
		else
			$blog_name 		= get_option('blogname','Blog name Here');
		
		$wpdb->insert(
				$response_table,
				array(
						'event_id' => $event_id,
						'user_id'  => $user_id,
						'user_name'=> bp_core_get_username($user_id),
						'blog_id'  => $primary_blog,
						'blog_name'=> $blog_name,
						'response' => strtolower($response),
						'status'   => 1
				)
		);
			
		if($wpdb->insert_id)
		{
			do_action('agc_after_insert_event_rsvp',$user_id, $event_id);
			
			echo json_encode(array(
					"code"    => "1",
					"response" => "Your response is saved successfully",
					"html" => "<p style='text-align:center;'>
													<a class='btn ". ($response == 'yes' ? 'btn-success' : '') ." rsvp-action'>Yes</a>&nbsp;
													<a class='btn ". ($response == 'no' ? 'btn-success' : '')  ." rsvp-action'>No</a>&nbsp;
													<a class='btn ". ($response == 'maybe' ? 'btn-success' : '') ." rsvp-action'>Maybe</a>
												</p>",
					"date"	=> $plus_one,
			));
		}
		else
		{
			echo json_encode(array(
					"code"	   => "0",
					"response" => "Failed to save your response. Please try again",
					"html" => "<p style='text-align:center;'>
									<a class='btn rsvp-action'>Yes</a>&nbsp;
									<a class='btn rsvp-action'>No</a>&nbsp;
									<a class='btn rsvp-action'>Maybe</a>
								</p>",
					"date"	=> $plus_one,
			));
		}
	}
	die();
}
add_action('wp_ajax_agc_send_user_event_rsvp','agc_send_user_event_rsvp');

///////////////////////////////////////////////////EVENT AGGREGATE VIEW FUNCTIONS/////////////////////////////////

/**
 * Function to display the actual main calender.
 */
function agc_event_main_calender()
{
	$event_categories = agc_event_get_categories(); 
	
	$html  = '<div id="event-cal" class="tabs">';
	$html .= '<ul id="event-tabs" class="tabs-control">';
	$html .= '<li class="active"><a href="#events" data-toggle="tab">Events</a></li>/';
	
	if($event_categories)
	{
		foreach ($event_categories as $category)
		{
			$html .= '<li><a href="#'.$category->slug.'" data-toggle="tab">'.$category->name.'</a></li>/';
		}
	}
	
	$html .= '</ul>';
	$html .= '<div class="tab-content tabs-tabs">';
	
	$html .= '<div class="tab-pane tabs-tab active events" id="events">';
	$html .= agc_event_get_cal_events();
	$html .= '</div>';

	if($event_categories)
	{
		foreach ($event_categories as $category)
		{
			$html .= '<div class="tab-pane tabs-tab '.$category->slug.'" id="'.$category->slug.'">';
			$html .= agc_event_get_cal_events($category->slug);
			$html .= '</div>';
		}
	}
	
	$html .= '</div><!-- /#tab-content-->';
	$html .= '</div><!-- /#event-cal -->';
	return $html;
}
add_shortcode( 'agc_events_calender', 'agc_event_main_calender' );
/**
 * Function to return the event categories.
 * @return boolean|Ambigous <multitype:, WP_Error, array>
 */
function agc_event_get_categories()
{
	$args 		= 'orderby=name&hide_empty=1';
	$event_cats = get_terms('agc_event_category', $args ) ; //returns an array or WP Error
	
	if(is_wp_error($event_cats))
		return false;
	
	$ecats_with_upcoming = array();
	
	if(count($event_cats) > 0)
	{
		foreach ($event_cats as $cat)
		{
			$events = agc_event_get_events($cat->slug);
			if(count($events) > 0 )
			{
				$ecats_with_upcoming[] = $cat;
			}
		}
		return apply_filters('agc_event_calendar_cats', $ecats_with_upcoming);
	}
	
	return apply_filters('agc_event_calendar_cats',false);
}

/**
 * Function to return the posts of the type agc_event optionally specifying the event category.
 * @param string $category
 * @return array
 */
function agc_event_get_events($category = false)
{
	 $args = array(
    'numberposts'     => -1,
    'orderby'         => 'post_date',		
    'post_type'       => 'agc_event', 		
    'post_status'     => 'publish',
    'suppress_filters' => true );
	 
	 if(isset($category))
	 	$args['agc_event_category'] = $category; //the category slug
	 	
	 $events = get_posts($args);
	 
	 $ecaldata = array();
	 
	 foreach ($events as $event)
	 {
	 	$event_meta = get_post_meta($event->ID,'agc_event_date_time',true);
	 	//if(isset($event_meta['to_date']))
	 	//{
	 		$event_date		= (!empty($event_meta['to_date']))?strtotime($event_meta['to_date']):strtotime($event_meta['from_date']);
	 		$current_date 	= strtotime(date('d-m-Y'));
	 		
	 		if($event_date >= $current_date)
	 		{
	 			$event_date_frm = strtotime($event_meta['from_date']);
	 			
	 			$event_month 		= date("F",$event_date_frm);
	 			$event_year			= date("Y",$event_date_frm);
	 			$event_day_week	 	= date("l",$event_date_frm);
	 			$event_day_month	= date("dS",$event_date_frm);
	 			$event_day_sort		= date("d",$event_date_frm);
	 			$event_time			= $event_meta['from_time'];
	 			$event_title		= $event->post_title;
	 			$event_venue		= $event_meta['venue'];
	 			$event_id			= $event->ID;
	 			
	 			$event_args			= array(
			 							'id' 		=> $event_id,
			 							'weekday' 	=> $event_day_week,
			 							'monthday'	=> $event_day_month,
			 							'time'		=> $event_time,
			 							'title'		=> $event_title,
			 							'venue'		=> $event_venue,
	 									'sdate'		=> $event_day_sort,
			 							);
	 			
	 			$ecaldata[$event_year][$event_month][] = $event_args;
	 		}
	 	//}
	 }
	 
	 $ecaldata = apply_filters('agc_event_calendar_data', $ecaldata,$category);
	 
	 //Sort the returned data based on day of month
	 $ecalsortdata = array();
	 
	 foreach ($ecaldata as $year => $months)
	 {
	 	foreach ($months as $month => $events)
	 	{
	 		$sorted_events =  aasort ($events, 'sdate'); 					 
	 		foreach($sorted_events as $event)
	 		{	
	 			$ecalsortdata[$year][$month][] = $event;
	 		}
	 	}
	 }
	 
	 return $ecalsortdata;
}
/**
 * Functiont to render the events in a ul li structure.
 * @param string $category
 * @return string
 */
function agc_event_get_cal_events($category = false)
{
	$all_events = agc_event_get_events($category);
	
	if(count($all_events) > 0 )
	{
		$html = '<ul class="grid-cal">';
		
		$licount = 0;
		
		foreach ($all_events as $year => $months)
		{
			foreach ($months as $month => $events)
			{
				if($licount < 11)
				{
						
					$html .= '<li class="month"><div class="t-a-r">'.$year.'<span>'.$month.'</span></div></li>';

					foreach ($events as $event)
					{
						if($licount < 11)
						{
							$link = (isset($event['link']))?$event['link']:get_permalink($event['id']);
								
							$html .= 	'<li class="date"><div>';
							$html .=	'<a href="'.$link.'">';
							$html .=	'<span class="dayname">'.$event['weekday'].'</span>';
							$html .=	'<span class="time">'.$event['time'].'</span>';
							$html .=	'<span>'.$event['monthday'].'</span>';
							$html .=	'<span class="title">'.$event['title'].'</span>';
							$html .=	'<span class="venue">@ '.$event['venue'].'</span>';
							$html .=	'</a></div></li>';
								
							$licount += 1;
						}
					}

					$licount += 1;
				}
			}
		}
		
		//View more link
		if($licount >= 11)
			$html .= '<li class="month more"><div class="t-a-r"><a class="r-m-a" href="'.agc_events_url().'">view more</a></div></li>';
		else
			$html .= '<li class="month more"><div class="t-a-r"><a class="r-m-a" href="'.agc_events_url().'">all events</a></div></li>';
		
		$html .= '</ul>';
	}
	else
	{
		$html = '<ul class="grid-cal">';
		$html .= '<li class="no-events"><div><h4>Oops!</h4><p>There are no upcoming events to show here!</p></div></li>';
		$html .= '<li class="month more"><div class="t-a-r"><a class="r-m-a" href="'.agc_events_url().'">all events</a></div></li>';
		$html .= '</ul>';
	}

	return $html;
}
/**
 * Function to sort a multidimentional array based on a key.
 * @param array $array
 * @param string $key
 * @return sorted array
 */
function aasort (&$array, $key) {
	$sorter=array();
	$ret=array();
	reset($array);
	foreach ($array as $ii => $va) {
		$sorter[$ii]=$va[$key];
	}
	asort($sorter);
	foreach ($sorter as $ii => $va) {
		$ret[$ii]=$array[$ii];
	}
	$array=$ret;
	
	return $array;
}

/**
 * Function to get the event categories.
 * @param int $id
 * @return multitype:array
 */
function agc_event_get_event_categories($id)
{
	$event_terms 	= wp_get_object_terms($id, 'agc_event_category');
	$event_cats		= array(); 
	
	if(!empty($event_terms)){
		if(!is_wp_error( $event_terms )){
			foreach($event_terms as $term){
				$event_cats[$term->slug] = $term->name;
			}
		}
	}
	
	return $event_cats;
}