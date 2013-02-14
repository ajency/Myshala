jQuery(document).ready(function() {
	
	jQuery('.input-browse-button').click(function() {
		var input_id=jQuery(this).attr('rel');
		window.send_to_editor = function(html) 
		{
			var wrapper=jQuery('<div />');
			wrapper.html(html);
			var objurl = wrapper.find('a').attr('href');
			jQuery('#'+input_id).val(objurl);
			tb_remove();
		}
	 
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
		
	});
	
});