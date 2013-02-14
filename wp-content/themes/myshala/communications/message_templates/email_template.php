<?php 
/**
 * Function to specify the templates for the various emails that go out.
 * @param string $type
 * @return array
 */
function agc_get_message_templates($type)
{
	switch($type)
	{
		//Sent when the user signs up.
		case 'bp_comm_email':
			$template = array(
			'hhtml'		=> agc_message_template_generic_header(),
			'bhtml'		=> agc_message_template_generic_body(),
			'fhtml'		=> agc_message_template_generic_footer(),
			'header'  	=> array('From: Support <support@myshala.com>'),
			);
			break;
		default:
			$template = array(
			'hhtml'		=> agc_message_template_generic_header(),
			'bhtml'		=> agc_message_template_generic_body(),
			'fhtml'		=> agc_message_template_generic_footer(),
			'subject'	=> "",	
			'message' 	=> "",
			'header'  	=> array('From: Support <support@myshala.com>'),
			);
			break;
	}
	return $template;
}
////////////////////////////////////////////////EMAIL TEMPLATE HTML/////////////////////////////////////////////////////
//Put html images in the template img folder and to access them use the following in inline css
//get_template_directory_uri().'/img/<filename>'

function agc_message_template_generic_header()
{
	//Email header html goes here.
	$html = '<!-- Email header ---><!--End of Header -->';
	
	return apply_filters('agc_message_template_generic_header', $html);
}

function agc_message_template_generic_body()
{
	//Email message html goes here.
	$html = '<!--Email Message -->%CONTENT% %EXTRACONTENT%<!--End Of Message -->';
	
	return apply_filters('agc_message_template_generic_body', $html);
	
}

function agc_message_template_generic_footer()
{
	//Email Footer html goes here.
	$html = '<!-- Email Footer --><!--End of footer -->';

	return apply_filters('agc_message_template_generic_footer', $html);
}

?>