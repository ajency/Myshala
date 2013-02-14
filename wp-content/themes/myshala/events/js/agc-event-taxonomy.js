var jq = jQuery;
jq(document).ready(function(){
	
	jq('form#addtag input.button').click(function(){
		var l = jq('#ajax_event_taxonomy_loading');
		var d = {
					action:'agc_event_ajax_taxonomy_add',
				};
		l.css('visibility','visible');
		jq.post(ajaxurl,d,function(r){
			if(r.result === false)
				{
					alert(r.msg);
				}
			l.css('visibility','hidden');
		});
	});
});