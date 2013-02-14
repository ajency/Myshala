var jq = jQuery;
var df = "mm/dd/yy";

jq(document).ready(function(){

      jq( "#agc_selected_from_date" ).datepicker(
        		{
        			showOn: "button",
                    buttonImage: btn_calender_image,
                    buttonImageOnly: true,
                    buttonText:'From Date',
                    dateFormat:df,
        		});
        jq( "#agc_selected_to_date" ).datepicker(
        		{
        			showOn: "button",
                    buttonImage: btn_calender_image,
                    buttonImageOnly: true,
                    buttonText:'To Date',
                    dateFormat:df,
        		});
        jq(".cleardate").click(function(e){
        	jq(this).parent().find('input').each(function(){
        		jq(this).val('');
        	});
        });
        
        jq("#agc_selected_from_time").timepicker({
        	showOn: "button",
            buttonImage: btn_time_image,
            buttonImageOnly: true,
            buttonText:'From Time',
            timeFormat: 'hh:mm tt',
            stepMinute:15,
        });
        
        jq("#agc_selected_to_time").timepicker({
        	showOn: "button",
            buttonImage: btn_time_image,
            buttonImageOnly: true,
            buttonText:'From Time',
            timeFormat: 'hh:mm tt',
            stepMinute:15,
        });
        
        
        //EVENT INVITEES RADIO BUTTON CHANGE FUNCTION
        jq("input[name='event_invitees']").change(function(){
        	
        	var l = jq('#ajax_event_invitees_loading');
        	var s = jq('.agc-event-send-invites-wrapper');
        	var target = '.event_invitees_list';
        	var i = jq(this);
        	var n = i.attr('data-nonce');
        	var t = i.val();
        	var a = i.attr('name');
        	var e = jq('#post_ID').val();
        	var d = {
        				action		:a,
        				'_wpnonce'	:n,
        				'type'		:t,
        				'eid'		:e,
        	};
        	l.css('visibility','visible');
        	s.hide();
        	jq.post(ajaxurl,d,function(r){
        		if(r.trim() == '')
        		{
        			jq(target).fadeOut('100');
        		}
        		else
        		{
        			jq(target).fadeOut( 100, function() {
        				jq(this).html(r);
        				jq(this).fadeIn(100);
        			});
        		}
        		_agc_event_privacy(i);
        		s.show();
        		l.css('visibility','hidden');
        	});
        });
        
        //LOAD MORE INVITEES FUNCTION
        jq('.invitees_loadmore').live('click',function(e){
        	e.preventDefault();
        	var l = jq('#ajax_event_invitees_loading');
        	var target = '.event_invitees_list';
        	var a = jq(this);
        	var n = a.attr('data-nonce');
        	var p = a.attr('rel');
        	var i = a.attr('id');
        	var e = jq('#post_ID').val();
        	var d = {
        		action		:i,
        		'_wpnonce'	:n,
        		'page_num'	:p,
        		'eid'		:e,
        	};
        	l.css('visibility','visible');
        	a.fadeOut(100);
        	jq.post(ajaxurl,d,function(r){
        		l.css('visibility','hidden');
        		jq(target).append(r);
        	});
        });
        
        //Trigger the event if radio button found checked.
        jq("input[name='event_invitees']:checked").trigger('change');
        
        //Perform validation of the date time input.
        jq('#post').validate({
        	
        	errorClass: "error-block",
			errorElement: "span",
			errorPlacement: function(error, element) {
			     element.parent('td').find('br').after(error);
			   },
        	rules:{
        		agc_selected_from_date: {
     		       required: true,
     		       date: true,
     			},
        		/*agc_selected_to_date: {
        		       required: true,
        		       date: true,
        		},*/
        		agc_selected_from_time: {
      		       required: true,
      		       time: true,
      			},
         		/*agc_selected_to_time: {
         		       required: true,
         		       time: true,
         		     }*/
         		}
			
        });
        
        //Time validator.
        jq.validator.addMethod('time',function(value,element)
        		{
		        			
		            var result = false, m;
		            var re = /^((0?[1-9]|1[012])(:[0-5]\d){0,2}(\ [ap]m))$|^([01]\d|2[0-3])(:[0-5]\d){0,2}$/;
		            if ((m = value.match(re))) {
		                result = (m[1].length == 2 ? "" : "0") + m[1] + ":" + m[2];
		            }
		            return result;
        		},"Please enter valid time.");
    
     
        //SEND INVITES BUTTON CLICK
       jq('#agcSendInvitesConfirmButton').live('click',function(e){
    	  e.preventDefault();
    	  var a = jq(this);
    	  var t = a.attr('data-type');
    	  var c = _agc_checked_array(".agc_invite_channels:checked");
	  	  var d = null;
	  	  var l = jq('#ajax_send_invites_loading');
	  	  var e = jq('#post_ID').val();
    	  switch(t)
    	  {
    	  	case 'invitees_only':
    	  		 d = _agc_checked_array(".agc_chapter_member:checked");
    	  		break;
    	  	case 'multi_chapters':
    	  		 d = _agc_checked_array(".agc_chapters:checked");
    	  		break;
    	  	default:
    	  		break;
    	  }
    	  var data = {
    		action		:'send_invites',
    		'type'		: t,
    		'selected'	: d,
    		'channels'	: c,
    		'_wpnonce'	: a.attr('data-nonce'),
    		'eid'		: e,
    	  };
    	  if(c.length > 0)
    	  {
    		  l.css('visibility','visible');
    		  jq.post(ajaxurl,data,function(response){
    			  l.css('visibility','hidden');
    			  if(response.result == 'success')
    				  {
    				  	alert(response.msg);
    				  	window.location.href = agc_event_edit_page;
    				  }
    			  else
    				  {
    				  	alert(response.msg);
    				  }
    		  });
    	  }
    	  else
    		  alert('You must choose at least one communication channel.');
       });
       
       //Prevent user from adding categories from the event edti page.
       jq('#agc_event_category-adder').remove();
});


//THICKBOX FUNCTIONS.
function _agc_event_privacy(input){
	var i 		= input;
	var t 		= i.val();
	var l 		= i.attr('data-label');
	var info 	='';
	switch(t)
	{
    	case 'invitees_only':
    		info = 'The invite will be sent to only those members who have been selected.';	
    		break;
    	case 'multi_chapters':
    		 info = 'The invite will be sent to all the members of the selected chapters.';
    		break;
    	default:
    		info = 'The invite will be sent to all the members in your chapter.';
    		break;	
	};
	var tb = jq('div.agcSendInvitesConfirm');
	tb.find('h3').html(l);
	tb.find('span.description').html(info);
	tb.find('a').attr('data-type',t);
};

function _agc_checked_array(selector)
{	
	var arr = new Array();
	jq(selector).each(function(){
		arr.push(jq(this).val());
	});
	return arr;
}

function agcShowDatePicker(t)
{
	jq(t).datepicker('show');
}
function agcShowTimePicker(t)
{
	jq(t).timepicker('show');
}