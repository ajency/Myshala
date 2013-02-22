<?php 
/**
 * Class to store all data for a single event
 */
?>
<?php
 
class Agc_Event
{
	//variables
	private $post_id;
	private $user_id;
	private $event_meta;
	private $invited_chapters;
	private $event_details;
	public  $invitee_count;
	public  $loggedin_user_rsvp;
	public  $event_type;
	public  $user_has_access = false;
	public  $is_upcoming = false;
	public 	$is_plusone	 = false;
	
	public function __construct($_post_id)
	{
		$this->post_id   = $_post_id;
		$this->user_id    = bp_loggedin_user_id();
		
		$this->event_meta = get_post_meta($this->post_id);
		//set up event
		$this->set_event_type();
		$this->set_event_meta();
		$this->get_invitee_count();
		$this->get_loggedin_user_rsvp();
		
		$this->check_user_has_access();
		$this->check_is_upcoming();
		
		//Check plus one
		$this->agc_event_user_is_plusone();
		
	}
	
	/**
	 * check if event is upcoming or past event
	 */
	public function check_is_upcoming()
	{
		$event_date = (!empty($this->event_details['to_date']))?$this->event_details['to_date']:$this->event_details['from_date'];
		if(strtotime($event_date) >= strtotime(date('d-m-Y')))
		{
			$this->is_upcoming = true;
		}
		else
		{
			$this->is_upcoming = false;
		}
	}
	
	/**
	 * check if the loggedin user has permission to access the event
	 */
	function check_user_has_access()
	{
		//check event @author
		$event = get_post($this->post_id);
		
		if($this->user_id == $event->post_author)
		{
			$this->user_has_access = true;
			return;
		}
			
		
		switch($this->event_type)
		{
			case 'chapter':
				foreach($this->invited_chapters as $chapter)
				{
					$this->is_user_member_of($chapter);
				}
			break;

			case 'invitees_only':
				global $wpdb;
				$invited_table = $wpdb->base_prefix.'agc_event_invite_data';
				$user_invited = $wpdb->get_var($wpdb->prepare("SELECT COUNT(user_id) FROM $invited_table WHERE `user_id`='$this->user_id' AND `event_id`='$this->post_id'"));
				
				//check event author
				
				if($user_invited == 1)
					$this->user_has_access = true;
				else
					$this->user_has_access = false;
			break;
			
			default:
			break;
		}
		
	}
	
	private function is_user_member_of($blog_id)
	{
		$users  = get_users('blog_id='.$blog_id);
		foreach($users as $user)
		{
			if($user->ID == $this->user_id)
			{
				$this->user_has_access = true;
				break;
			}	
				
		}
	} 
	
	/**
	 * get logged in users RSVP status
	 */
	private function get_loggedin_user_rsvp()
	{
		global $wpdb;
		
		$response_table = $wpdb->base_prefix.'agc_event_response_data';
		
		$user_rsvp = $wpdb->get_var($wpdb->prepare("SELECT `response` FROM $response_table WHERE `event_id`='$this->post_id' AND `user_id`='$this->user_id';"));
		
		if($user_rsvp)
			$this->loggedin_user_rsvp = $user_rsvp;
		else 
			$this->loggedin_user_rsvp = null;
	}
	
	public function generate_rsvp_box()
	{
		$event_categories = $this->show_event_categories();
		$event_categories = explode(', ',$event_categories);
		
		//Based on event categories show additional data under RSVP
		//NOTE: The same conditions must be maintained in the javascript.
		
		if(in_array('Date Party',$event_categories))
		{
			$plus_one = $this->agc_event_get_plus_one($this->post_id);
			
			if($plus_one)
				$additional_data = $this->agc_event_invited_date($plus_one);
			else
				$additional_data = $this->agc_event_suggest_names_dialogue();
		}
		
			return "<p style='text-align:center;' id='agc-event-rsvp-box'>
						<a class='btn button text-dark size-small ". ($this->loggedin_user_rsvp == 'yes' ? 'btn-success' : '') ." rsvp-action'>Yes</a>&nbsp;
						<a class='btn button text-dark size-small ". ($this->loggedin_user_rsvp == 'no' ? 'btn-success' : '')  ." rsvp-action '>No</a>&nbsp;
						<a class='btn button text-dark size-small ". ($this->loggedin_user_rsvp == 'maybe' ? 'btn-success' : '') ." rsvp-action'>Maybe</a>
					</p><p style='text-align:center;margin-top:10px' id='agc-suggest-dates-box'>".$additional_data."</p>";	
	}
	
	private function set_event_type()
	{
		$this->event_type = $this->event_meta['agc_event_invitee_type'][0];
		
		$chapters = $this->event_meta['agc_event_invited_chapters'][0];
		
		if($chapters)
		foreach (unserialize($chapters) as $chapter):
			$this->invited_chapters[] = $chapter;
		endforeach;
		
	}
	
	private function set_event_meta()
	{
		$details = unserialize($this->event_meta['agc_event_date_time'][0]);
		
		$this->event_details = $details;
	}
	
	/**
	 *  get the event title
	 */
	public function get_event_title()
	{
		return get_the_title($this->post_id);
	}
	
	/**
	 * return evetn date
	 */
	public function show_event_date()
	{
		$from_date = $this->event_details['from_date'];
		$to_date = $this->event_details['to_date'];
		
		$to_date = (!empty($to_date))?$to_date:$from_date;
		
		if(strtotime($from_date) == strtotime($to_date))
			return date('l, dS F Y', strtotime($from_date));
		else
			return 'From ' . date('D, dS M Y', strtotime($from_date)) . ' to ' . date('D, dS M Y', strtotime($to_date));
		
		
		return '';
	}
	
	/**
	 * returns event time
	 */
	public function show_event_time()
	{
		$from_time = $this->event_details['from_time'];
		$to_time = $this->event_details['to_time'];
		
		$to_time = (!empty($to_time))?$to_time:$from_time;
		
		if($from_time == $to_time)
			return $from_time . ' onwards';
		else
			return 'From ' . $from_time.  ' to ' . $to_time;
		
		return '';
	}
	
	
	/**
	 * returns event venue
	 */
	public function show_event_venue()
	{
		return $this->event_details['venue'];
	}
	
	/**
	 * return event tags
	 */
	public function show_event_categories()
	{
		$categories = get_the_terms($this->post_id,'agc_event_category');
		
		if(!$categories)
		{
			return 'No categories added';
		}
		else
		{
			
			$html = '';
			foreach($categories as $category)
			{
				$html .= '' . $category->name . ', ';
			}
			return rtrim($html,', ');
		}	
	}
	
	/**
	 * returns the days left for the event
	 */
	public function count_days_left()
	{
		$now = time(); // or your date as well
    	$event_date = strtotime($this->event_details['from_date'].' '.$this->event_details['from_time']);
    	$datediff = $event_date - $now;
    	$days_remaining = $datediff/(60*60*24);
     	return ($days_remaining < 0)? 0 : round($days_remaining);
	}
	
	/**
	 * get the list of invitees
	 */
	public function get_invitee_list()
	{ 
		if($this->event_type == 'chapter')
		{
			$html = '<p>Entire School </p>'; 
			return $html;
		}
		else
		{
			global $wpdb;
			$invited_table = $wpdb->base_prefix.'agc_event_invite_data';
			$invited_users = $wpdb->get_results($wpdb->prepare("SELECT `user_id` FROM $invited_table WHERE `event_id`='$this->post_id'")); 
			if(count($invited_users) > 0 )
			{	
				$html = '<ul>';
				foreach($invited_users as $user):
				
					$html .= '<li>
								<a rel="tooltip" title="' . bp_core_get_user_displayname($user->user_id). '" href="'. bp_core_get_user_domain($user->user_id) .'">';
				
					$html .= get_avatar( $user->user_id, 35);
					$html .= '</a></li>';
				endforeach;
				$html .= '</ul>';
				
				if(count($invited_users) > 15)
					$html .= '<a href="#" title="View All" class="more">more &raquo;</a>';
				return $html;
			}
			else
			{
				return '<p>No users found<p><br />';
			}
		}
	}
	
	/**
	 * get the list of invitees
	 */
	public function get_rsvp_list($response)
	{
		$response_text = ($response == 'yes') ? 'Going' : 'May be'; 
		
		global $wpdb;
		$response_table = $wpdb->base_prefix . 'agc_event_response_data';
		$users = $wpdb->get_results($wpdb->prepare("SELECT `user_id` FROM $response_table WHERE `event_id`='$this->post_id'  AND `response`='$response';"));
		if(count($users) > 0)
		{
			$html = '<h4>'. $response_text .' <span class="meta">('. count($users) .')</span></h4>';
			$html .= '<ul>';
				foreach($users as $user):
				
				$plus_one 	= $this->agc_event_get_plus_one($this->post_id,$user->user_id);
				$date		= ($plus_one)?' going with '.bp_core_get_user_displayname($plus_one):'';
				
					$html .= '<li>
					<a rel="tooltip" title="' . bp_core_get_user_displayname($user->user_id) .$date. '" href="'. bp_core_get_user_domain($user->user_id) .'">';
					
					$html .= get_avatar( $user->user_id, 35);
					$html .= '</a></li>';
				endforeach;
			$html .= '</ul>';
			
			return $html ;
		}
		else
		{
			return '<h4>'. $response_text .' <span class="meta">(0)</span></h4>
					<p>No users found</p> <br />';
		}	

	}
	
	public function get_invitee_count()
	{
		if($this->event_type == 'multi_chapters' || $this->event_type == 'chapter')
		{
			foreach($this->invited_chapters as $chapter)
			{	
				$users = get_users('blog_id='.$chapter);
				$this->invitee_count += count($users);
			}
		}
		else
		{
			global $wpdb;
			$invited_table = $wpdb->base_prefix.'agc_event_invite_data';
			$invited_users = $wpdb->get_var($wpdb->prepare("SELECT COUNT(user_id) FROM $invited_table WHERE event_id=$this->post_id"));
			$this->invitee_count = $invited_users;			
		}	
	}
	
	/**
	 * Function to get the users response for a particular event.
	 * @param int $event_id
	 * @param int $user_id
	 * @return string
	 */
	public function agc_event_get_user_response($event_id = null,$user_id = null){
		
		if(!$user_id)
			$user_id = bp_loggedin_user_id();
	
		if(empty($event_id))
			$event_id = $this->post_id;
		
		global $wpdb;
		$response_table = $wpdb->base_prefix . "agc_event_response_data" ;
		$response		=  $wpdb->get_var($wpdb->prepare("SELECT `response` FROM $response_table WHERE `event_id` = %d AND `user_id` = %d",$event_id,$user_id));
		
		return $response;
	}
	
	/**
	 * Function to retrieve the plus one data for a user and event.
	 * @param int $event_id
	 * @param int $user_id
	 * @return boolean|string
	 */
	public function agc_event_get_plus_one($event_id = null,$user_id = null)
	{
		if(!$user_id)
			$user_id = bp_loggedin_user_id();
	
		if(empty($event_id))
			$event_id = $this->post_id;
	
		global $wpdb;
		$invitee_table 	= $wpdb->base_prefix . "agc_event_invite_data" ;
		$plus_one		= $wpdb->get_var($wpdb->prepare("SELECT `user_id` FROM $invitee_table WHERE `event_id` = %d AND `parent_id` = %d",$event_id,$user_id));
	
		if($plus_one)
			return $plus_one;
	
		return false;
	}
	
	public function agc_event_user_is_plusone($event_id = null,$user_id = null)
	{
		if(!$user_id)
			$user_id = bp_loggedin_user_id();
		
		if(empty($event_id))
			$event_id = $this->post_id;
		
		global $wpdb;
		$invitee_table 	= $wpdb->base_prefix . "agc_event_invite_data" ;
		$plus_one		= $wpdb->get_var($wpdb->prepare("SELECT `user_id` FROM $invitee_table WHERE `event_id` = %d AND `user_id` = %d",$event_id,$user_id));
		
		if($plus_one)
			$this->is_plusone = true;
		else
			$this->is_plusone = false;
		
		if($this->user_has_access === true)
			$this->is_plusone = false;
			
		return;
	}
	
	/**
	 * Function to show information div containing the person plus oned.
	 * @param int $user_id
	 * @return string
	 */
	public function agc_event_invited_date($user_id)
	{
		$ud = get_userdata( $user_id );
	
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
	
		return "<div class='alert alert-info fade in'>
            <button type='button' class='close' title='Remove +1' id='agc-event-remove-plusone' onClick='_agc_remove_plusone(this);'>&times;</button>
            <strong>Your date for the event:</strong><br>
			<img src='".bp_core_fetch_avatar( array( "item_id" => $user_id, "type" => "thumb",'html'=>false, "alt" => $ud->display_name ) )."' width='15' height='15' />
			<span class='suggested_name_name'>" . bp_core_get_user_displayname( $user_id ). $user_blog_name . "</span>
            </div>";
	
	}
	
	/**
	 * Function to delete the plus one for a particular user and event combo.
	 * @param int $event_id
	 * @param int $parent_id
	 */
	public function agc_event_cancel_plus_one($event_id,$parent_id)
	{
		global $wpdb;
		
		$invitee_table = $wpdb->base_prefix . "agc_event_invite_data" ;	
		$wpdb->query($wpdb->prepare("DELETE FROM $invitee_table WHERE `event_id` = %d AND `parent_id` = %d",$event_id, $parent_id));
		
		return;
	}
	/**
	 * Function to show suggest users dialog.
	 * @return string
	 */
	public function agc_event_suggest_names_dialogue()
	{
		$nonce 		= wp_create_nonce('invitee_date');
		$response 	= $this->agc_event_get_user_response();
		$html = '';
		if(!empty($response) && $response != 'no')
		{
			$html 	= "<label for='suggest_dates' class='suggest_dates_label'>Since this is a date party, enter the name of the date you are bringing along.</label>";
			$html  	.="<div class='suggest-input'><input value='' data-id='' class='suggest_dates_input' type='text' name='suggest_dates' id='suggest_dates' onkeyup='_agc_suggest_names(this)'/>";
			$html	.="<span class='loading-16' id='suggest_dates_loader' style='display:none;'></span></div>";
			$html	.="<button class='btn btn-primary btn-mini agc-bring-along' data-nonce='".$nonce."' onClick='_agc_submit_selected_name(this);'>Add +1</button>";
			$html	.="<span class='loading-16' id='submit_dates_loader' style='display:none;'></span>";
			$html	.="<div class='agc_suggest_scroll'><div class='agc_suggested_name'></div></div>";
		}
		return $html;
	}
	
	/**
	 * Function to add rsvp notices for an event.
	 * @return string
	 */
	public function agc_event_user_rsvp_notice()
	{
		$event_categories 	= $this->show_event_categories();
		$event_categories 	= explode(', ',$event_categories);
		
		if(in_array('Date Party',$event_categories))
		{
			$response 			= $this->agc_event_get_user_response();
			$plusone  			= $this->agc_event_get_plus_one();
			
			if(!empty($response) && ($response == 'yes' || $response == 'maybe'))
			{
				if(!$plusone)
				{
					$a 	= '<div id="agc_rsvp_notice_'.bp_loggedin_user_id().'" class="alert fade in" style="margin: 0 auto;">';
					$a .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
					$a .= '<strong>Seems like you are going alone!</strong> Add a date by clicking on RSVP.</div>';
				}
			}
		}
		//Add more conditions here.
		
		return $a;
	}
	
}

?>


