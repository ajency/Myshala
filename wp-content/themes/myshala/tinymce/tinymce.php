<?php

define('OM_TINYMCE_PATH', get_template_directory().'/tinymce');
define('OM_TINYMCE_URI', TEMPLATE_DIR_URI.'/tinymce');

function om_tmce_admin_head() {
	echo '<script>var OM_TEMPLATE_DIR_URI="'.TEMPLATE_DIR_URI.'";</script>';
}
add_action('admin_head', 'om_tmce_admin_head');

function om_tmce_admin_init() {

	wp_enqueue_style( 'om-tmce-shortcodes', OM_TINYMCE_URI . '/css/style.css' );
	wp_enqueue_script( 'om-tmce-shortcodes-js', OM_TINYMCE_URI . '/js/popup.js', array('jquery') );

	wp_enqueue_style('color-picker', TEMPLATE_DIR_URI.'/admin/css/colorpicker.css');
	wp_enqueue_script('color-picker', TEMPLATE_DIR_URI.'/admin/js/colorpicker.js', array('jquery'));
		
}
add_action('admin_init', 'om_tmce_admin_init');


function om_tmce_init()
{
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		return;

	if ( get_user_option('rich_editing') == 'true' )
	{
		add_filter( 'mce_external_plugins', 'om_tmce_plugins'  );
		add_filter( 'mce_buttons', 'om_tmce_buttons' );
	}
}
add_action('init', 'om_tmce_init' );

function om_tmce_plugins( $plugin_array )
{
	$plugin_array['om_shortcode_plugin'] = OM_TINYMCE_URI . '/js/plugin.js';
	return $plugin_array;
}

function om_tmce_buttons( $buttons )
{
	array_push( $buttons, "|", 'om_shortcode_button' );
	return $buttons;
}

