<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class AGC_COM{
	
	var $user_id;
	var $component_id;
	var $send_message;
	var $send_notification;
	var $send_email;
	var $send_sms;
	var $secondary_item_id;
	var $secondary_item_type;
	var $user_array;
	
	function __construct( $args = '' ) {
	
		// Default arguments
		$defaults = array(
				'user_id'  				=>  bp_loggedin_user_id(),
				'component_id'			=>  'bp_com',
				'send_message'			=> 	true,
				'send_notification' 	=> false,
				'send_email'			=> false,
				'send_sms'				=> false,
				'secondary_item_id' 	=> null,
				'secondary_item_type' 	=> null,
		);
	
		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );
		
		$this->user_id 				= $user_id;
		$this->component_id         = $component_id;
		$this->send_message 		= (bool)$send_message;
		$this->send_notification    = (bool)$send_notification;
		$this->send_email           = (bool)$send_email;
		$this->send_sms           	= (bool)$send_sms;
		$this->secondary_item_id	= $secondary_item_id;
		$this->secondary_item_type	= $secondary_item_type;
		
		// $user_id and $component_id are required
		if ( empty( $user_id ) || empty( $component_id ) )
			return false;
		
		if(!empty($user_id) && (is_numeric($user_id) || is_string($user_id)))
			$this->user_array = false;
		
		if(!empty($user_id) && is_array($user_id))
			$this->user_array = true;
	}
	
	//GET THE HEADER SUBJECT AND BODY OF A MESSAGE BASED ON COMPONENT ID OPTIONALLY FORMATTING IT BASED ON 
	//SECONDARY ITEM ID AND ITEM TYPE.
	function agc_get_message_components($component_id,$user_id,$secondary_item_id = null,$secondary_item_type = null)
	{
		if( empty( $component_id ))
			return false;
		
		global $wpdb;
		$message_table = $wpdb->base_prefix.AGC_MESSAGE_TABLE;
		$message_component = $wpdb->get_row($wpdb->prepare("SELECT * FROM $message_table WHERE `message_type`= %s",$component_id));
		
		if($message_component)
		{
				$msg_comp = array();
				$msg_comp['header'] 	= $message_component->header;
				$msg_comp['subject'] 	= $message_component->subject;
				$msg_comp['body'] 		= $message_component->message;

				$msg_comp = $this->agc_format_message($secondary_item_id,$secondary_item_type,$user_id,$msg_comp,$component_id);
			
			return $msg_comp;	
		}
		
		return apply_filters('agc_get_message_components', false,$component_id,$user_id,$secondary_item_id,$secondary_item_type);
	}
	
	//FORMAT THE RETURNED MESSAGE BASED ON SECONDARY ITEM ID AND TYPE
	function agc_format_message($id=null,$type=null,$user_id,$msg,$component_id)
	{
		return apply_filters('agc_format_message',$msg,$user_id,$id,$type,$component_id);
	}
	
	//LOG THE COMMUNICATION INFORMATION.
	function agc_log_com($com_type,$user_id)
	{
		if( empty( $com_type ) || empty( $user_id ) )
			return false;	
		
		global $wpdb;
		$wpdb->insert($wpdb->base_prefix.AGC_LOG_TABLE, array(
				'user_id' 		=> $user_id,
				'message_type' 	=> $com_type,
				'secondary_id'	=> $this->secondary_item_id,
				'status'		=> 0,
				));
		
	
	}
	//SEND MESSAGE.
	function agc_send_message($user_id)
	{
		if($this->send_message)
		{
			$message = $this->agc_get_message_components($this->component_id.'_message',$user_id,$this->secondary_item_id,$this->secondary_item_type);
			if($message)
			{
				$args = array(
						'sender_id'  => bp_loggedin_user_id(),
						'recipients' => array($user_id),
						'subject'    => $message['subject'],
						'content'    => $message['body'].$message['extrabody'],
						);
				
				$thread_id = messages_new_message($args);
				
				if($thread_id)
					$this->agc_log_com($this->component_id .'_message',$user_id);
			}
		}
	}
	
	//SEND EMAIL.
	function agc_send_email($user_id)
	{
		if($this->send_email)
		{
			$message = $this->agc_get_message_components($this->component_id.'_email',$user_id,$this->secondary_item_id,$this->secondary_item_type);
			
			if($message)
			{
				
				$user = get_userdata($user_id);
				
				$msg = agc_get_message_templates('bp_comm_email');
				
				$content 	= $msg['hhtml'];
				$content   .= $msg['bhtml'];
				$content  	= str_replace("%CONTENT%",$message['body'],$content);
				$content  	= str_replace("%EXTRACONTENT%",$message['extrabody'],$content);
				$content   .= $msg['fhtml'];

				add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
				if(wp_mail($user->user_email, $message['subject'], $content,$msg['header']))
					$this->agc_log_com($this->component_id .'_email',$user_id);
			}
		}
	}
	
	//SMS AND BP NOTIFICATIONS NEED TO BE HERE.
	function agc_send_sms($user_id)
	{
		$message = $this->agc_get_message_components($this->component_id.'_sms',$user_id,$this->secondary_item_id,$this->secondary_item_type);
		$component_id 			= $this->component_id.'_sms';
		$secondary_item_id 		= $this->secondary_item_id;
		$secondary_item_type 	= $this->secondary_item_type;
		
		do_action('agc_send_sms',$message,$user_id,$component_id,$secondary_item_id,$secondary_item_type);
	}
	
	
	//SEND GROUP OR SINGULAR MESSAGE
	function agc_send()
	{
		if($this->user_array === true)
		{
			foreach($this->user_id as $user_id)
			{
				$this->agc_send_functions($user_id);
			}
		}
		else
			$this->agc_send_functions($this->user_id);

	}
	
	//Queue the send functions here.
  	function agc_send_functions($user_id)
	{
		$this->agc_send_message($user_id);
		$this->agc_send_email($user_id);
		$this->agc_send_sms($user_id);
		
	}
}

function agc_comm_filter_msg($msg,$user_id,$id,$type,$comp_type)
{
	switch($type)
	{
		case 'event':
			
			$event = get_post($id);
			$user  = get_userdata($user_id);	
			
			$msg['subject'] = str_replace('[eventname]',$event->post_title,$msg['subject']);
			$msg['body'] 	= str_replace('[username]',$user->display_name,$msg['body']); //username
			$msg['body'] 	= str_replace('[eventname]',$event->post_title,$msg['body']); //title
			$msg['body'] 	= str_replace('[eventlink]',get_permalink($event->ID),$msg['body']); //link
	
			break;
		default:
			break;
	}
	
	return $msg;
}
add_filter('agc_format_message', 'agc_comm_filter_msg',10,5);

//The communication message details table and log table creation
function agc_comm_create_table()
{
	global $wpdb;
	$table_name = $wpdb->base_prefix . "agc_comm_messages";
	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name )
	{
		$agc_comm_messages = "CREATE TABLE $table_name (
		ID mediumint(9) NOT NULL AUTO_INCREMENT,
		message_type varchar(255) NOT NULL,
		header varchar(255),
		subject varchar(255),
		message varchar(255) NOT NULL,
		UNIQUE KEY ID (ID)
		);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($agc_comm_messages);
	}
	
	$table_name = $wpdb->base_prefix . "agc_comm_logs";
	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name )
	{
		$agc_comm_logs = "CREATE TABLE $table_name (
		ID mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id	mediumint(9) NOT NULL,	
		message_type varchar(255) NOT NULL,
		secondary_id mediumint(9),
		status	mediumint(9) NOT NULL,
		UNIQUE KEY ID (ID)
		);";
	
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($agc_comm_logs);
	}
}
add_action( 'init', 'agc_comm_create_table');

define("AGC_MESSAGE_TABLE","agc_comm_messages",TRUE);
define("AGC_LOG_TABLE","agc_comm_logs",TRUE);

require_once ('mails.php');
require_once ('message_templates/email_template.php');
?>