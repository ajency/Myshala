<?php 
/**
 * Function to register the meta boxes.
 */
function agc_events_meta_boxes()
{
	add_meta_box('agc_event_data_desc',__( 'Event Description', 'buddypress' ),'agc_event_meta_box_desc','agc_event');
	add_meta_box('agc_event_data_datetime',__( 'Event Date & Time', 'buddypress' ),'agc_event_meta_box_datetime','agc_event','side','core');
}
add_action( 'add_meta_boxes', 'agc_events_meta_boxes' );

/**
 * Add event description meta box.
 */
function agc_event_meta_box_desc($event)
{	
	$nonce = wp_create_nonce('event-invitees');
	
	$invitee_type = get_post_meta($event->ID,'agc_event_invitee_type',true);
	
	?>
	
	<input type="hidden" id="agc_event_meta_box_desc_nonce" name="agc_event_meta_box_desc_nonce" value="<?php echo $nonce;?>">
	
	<textarea name="agc_event_desc" id="agc-event-desc" class="agc-event-textarea"><?php echo $event->post_content;?></textarea><br>
	
	<h3 class="event_invitees_header">Event Privacy
		<div class="event_invitees_wrapper">
			<img src="<?php echo admin_url();?>/images/wpspin_light.gif" class="ajax-loading" id="ajax_event_invitees_loading">
			<label for="event_invite_chapter" class="event_invitees">
				<input type="radio" <?php checked( 'chapter', $invitee_type);?> data-label="Chapter Only" name="event_invitees" id="event_invite_chapter" data-nonce="<?php echo $nonce?>" value="chapter">
				&nbsp;Entire School
			</label>
			<label for="event_invite_invitees_only" class="event_invitees">
				<input type="radio" <?php checked( 'invitees_only', $invitee_type);?> data-label="Invitees Only"  name="event_invitees" id="event_invite_invitees_only" data-nonce="<?php echo $nonce?>" value="invitees_only">
				&nbsp;Invitees Only
			</label>
		</div>
	</h3>
	
	<div class="event_invitees_list">
	</div>
	
	<?php if($invitee_type):?>
		<div class="agc-event-send-invites-wrapper">
			<a class="button-secondary agc-event-send-invites thickbox" id="agc-event-send-invited" href="#TB_inline?&inlineId=agcSendInviteChannels">Send Invites</a>
			<div id="agcSendInviteChannels" style="display:none;position:relative;">
				
				<h3><?php echo __('Communication Channels');?></h3>
				
				<span class="description"><?php echo __('Please select the various communication channels by which the event notification needs to be sent.');?></span>
				
				<hr>
				<br>
				<label for="agc_invite_channels_internal_msg">
					<input type="checkbox" name="agc_invite_channels[]" id="agc_invite_channels_internal_msg" class="agc_invite_channels" value="message" />
					<?php echo __('Internal Buddypress Messaging')?>
				</label>
				
				<br>
				<label for="agc_invite_channels_email">
					<input type="checkbox" name="agc_invite_channels[]" id="agc_invite_channels_email" class="agc_invite_channels" value="email" />
					<?php echo __('Email')?>
				</label>
				
				<div class="agcSendInvitesConfirm">
					<h3></h3>
					<span class="description"></span>
					<hr>
					<a class="button-secondary" href="#" id="agcSendInvitesConfirmButton" data-type="" data-nonce="<?php echo $nonce;?>">Send Invites</a>
					<img src="<?php echo admin_url();?>/images/wpspin_light.gif" class="ajax-loading" id="ajax_send_invites_loading">
				</div>
			</div>
		</div>
	<?php endif;?>
<?php
}
/**
 * Add event date time meta box.
 */
function agc_event_meta_box_datetime($event)
{	
	$nonce 		= wp_create_nonce('agc_event_meta_box_datetime');
	$date_time 	= get_post_meta($event->ID, 'agc_event_date_time', true);
	?>
	<input type="hidden" id="agc_event_meta_box_datetime_nonce" name="agc_event_meta_box_datetime_nonce" value="<?php echo $nonce;?>">
	<div class="agc-datetime-picker-wrap">
	
		<table>
			<tbody>
				<tr>
					<td class="agc-time-date-label">
						<span class="agc-time-date-title"><?php echo __('From Date :','buddypress')?></span>
					</td>
					<td> 
						<input onClick="agcShowDatePicker(this);" type="text" id="agc_selected_from_date" name="agc_selected_from_date" class="agc_selected_date"   value="<?php echo $date_time['from_date']?>" />
						<span class="cleardate" title="Clear From Date">&times;</span><br>
					</td>
				</tr>
				<tr>
					<td class="agc-time-date-label">	
						<span class="agc-time-date-title"><?php echo __('  To Date :','buddypress')?></span> 
					</td>
					<td>
						<input onClick="agcShowDatePicker(this);" type="text" id="agc_selected_to_date" name="agc_selected_to_date" class="agc_selected_date"  value="<?php echo $date_time['to_date']?>" />
						<span class="cleardate" title="Clear To Date">&times;</span><br>
					</td>
				</tr>
				<tr>
					<td class="agc-time-date-label">	
						<span class="agc-time-date-title"><?php echo __('From Time :','buddypress')?></span> 
					</td>
					<td>
						<input onClick="agcShowTimePicker(this);" type="text" id="agc_selected_from_time" name="agc_selected_from_time" class="agc_selected_date"  value="<?php echo $date_time['from_time']?>"/>
						<span class="cleardate" title="Clear From Time">&times;</span><br>
					</td>
				</tr>
				<tr>
					<td class="agc-time-date-label">	
						<span class="agc-time-date-title"><?php echo __('To Time :','buddypress')?></span> 
					</td>
					<td>
						<input onClick="agcShowTimePicker(this);" type="text" id="agc_selected_to_time" name="agc_selected_to_time" class="agc_selected_date"  value="<?php echo $date_time['to_time']?>"/>
						<span class="cleardate" title="Clear To Time">&times;</span><br>
					</td>
				</tr>
				<tr>
					<td class="agc-time-date-label">	
						<span class="agc-time-date-title"><?php echo __('Venue :','buddypress')?></span> 
					</td>
					<td>
						<input type="text" id="agc_selected_venue" name="agc_selected_venue" class="agc_selected_date"  value="<?php echo $date_time['venue']?>"/>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<script type="text/javascript">
		var btn_calender_image = '<?php echo get_template_directory_uri().'/events/css/img/event-calendar-icon.png'?>';
		var btn_time_image = '<?php echo get_template_directory_uri().'/events/css/img/time.png'?>';
		var agc_event_edit_page = '<?php echo admin_url('/post.php?post='.$event->ID.'&action=edit')?>';
	</script>
<?php 
}

