<?php

/*************************************************************************************
 *	Add MetaBox to Portfolio edit page
 *************************************************************************************/

$om_portfolio_meta_box=array (
	'type' => array (
		'id' => 'om-portfolio-meta-box-type',
		'name' =>  __('Portfolio Details', 'om_theme'),
		'callback' => 'om_portfolio_meta_box_type',
		'fields' => array (
			array ( "name" => __('Portfolio type','om_theme'),
					"desc" => __('Choose the type of portfolio you wish to display.<br/>Don\'t forget to set a featured image that will be displayed on the main portfolio page.','om_theme'),
					"id" => OM_THEME_SHORT_PREFIX."portfolio_type",
					"type" => "select",
					"std" => 'image',
					'options' => array(
						'image' => __('Image', 'om_theme'),
						'slideshow' => __('Gallery', 'om_theme'),
						'slideshow-m' => __('Gallery (Masonry Layout)', 'om_theme'),
						'video' => __('Video', 'om_theme'),
						'audio' => __('Audio', 'om_theme')
					)
			),
		),
	),
	
	'imageblock' => array (
		'id' => 'om-portfolio-meta-box-images',
		'name' =>  __('Portfolio Images', 'om_theme'),
		'callback' => 'om_portfolio_meta_box_images',
		'fields' => array (
			array ( "name" => '',
					"desc" => __('Upload image (if "Image" type selected or image<b>s</b> if "Gallery" type selected) for this portfolio item (images should be at least 920px wide)','om_theme'),
					"id" => OM_THEME_SHORT_PREFIX."portfolio_images",
					"type" => "media_button",
					"std" => ''
			),
		),
	),

	'video' => array (
		'id' => 'om-portfolio-meta-box-video',
		'name' =>  __('Video Settings', 'om_theme'),
		'callback' => 'om_portfolio_meta_box_video',
		'fields' => array (
			array ( "name" => __('Embeded Code','om_theme'),
					"desc" => __('If you have embed code, please, insert it here (best width is 700px).<br/>If you have only link to M4V or OGV file, skip this field and fill the next ','om_theme'),
					"id" => OM_THEME_SHORT_PREFIX."video_embed",
					"type" => "textarea",
					"std" => ''
			),
			array ( "name" => __('M4V File URL','om_theme'),
					"desc" => __('The URL to the .m4v video file','om_theme'),
					"id" => OM_THEME_SHORT_PREFIX."video_m4v",
					"type" => "text_browse",
					"std" => ''
			),
			array ( "name" => __('OGV File URL','om_theme'),
					"desc" => __('The URL to the .ogv video file','om_theme'),
					"id" => OM_THEME_SHORT_PREFIX."video_ogv",
					"type" => "text_browse",
					"std" => ''
			),
			array ( "name" => __('Poster Image to M4V or OGV video ','om_theme'),
					"desc" => __('The preivew image.','om_theme'),
					"id" => OM_THEME_SHORT_PREFIX."video_poster",
					"type" => "text_browse",
					"std" => ''
			),
		),
	),
	
	'audio' => array (
		'id' => 'om-portfolio-meta-box-audio',
		'name' =>  __('Audio Settings', 'om_theme'),
		'callback' => 'om_portfolio_meta_box_audio',
		'fields' => array (
			array( "name" => __('MP3 File URL','om_theme'),
				"desc" => __('The URL to the .mp3 audio file','om_theme'),
				"id" => OM_THEME_SHORT_PREFIX."audio_mp3",
				"type" => "text_browse",
				"std" => ''
			),
			array( "name" => __('OGA File URL','om_theme'),
				"desc" => __('The URL to the .oga, .ogg audio file','om_theme'),
				"id" => OM_THEME_SHORT_PREFIX."audio_ogg",
				"type" => "text_browse",
				"std" => ''
			),
			array( 
				"name" => __('Audio Poster Image', 'om_theme'),
				"desc" => __('If you would like a poster image for the audio', 'om_theme'),
				"id" => OM_THEME_SHORT_PREFIX."audio_poster",
				"type" => "text_browse",
				"std" => ''
		  ),
		),
	),
	
	'size' => array (
		'id' => 'om-portfolio-meta-box-size',
		'name' =>  __('Preview Size', 'om_theme'),
		'callback' => 'om_portfolio_meta_box_size',
		'fields' => array (
			array ( "name" => __('Preview Size','om_theme'),
					"desc" => __('Choose the size of preview thumbnail for Mansory Portfolio Template','om_theme'),
					"id" => OM_THEME_SHORT_PREFIX."portfolio_size",
					"type" => "select",
					"std" => '1',
					'options' => array(
						'1' => __('1x&nbsp;&nbsp;&nbsp;', 'om_theme'),
						'2' => __('2x&nbsp;&nbsp;&nbsp;', 'om_theme'),
						'3' => __('3x&nbsp;&nbsp;&nbsp;', 'om_theme'),
					)
			),
		),
	),
);
 
function om_add_portfolio_meta_box() {
	global $om_portfolio_meta_box;
	
	foreach($om_portfolio_meta_box as $metabox) {
		add_meta_box(
			$metabox['id'],
			$metabox['name'],
			$metabox['callback'],
			'portfolio',
			'normal',
			'high'
		);
	}
 
}
add_action('add_meta_boxes', 'om_add_portfolio_meta_box');

/*************************************************************************************
 *	MetaBox Callbacks Functions
 *************************************************************************************/

function om_portfolio_meta_box_type() {
	global $om_portfolio_meta_box;

	echo om_generate_meta_box($om_portfolio_meta_box['type']['fields']);
}

function om_portfolio_meta_box_images() {
	global $om_portfolio_meta_box;

	echo om_generate_meta_box($om_portfolio_meta_box['imageblock']['fields']);
}

function om_portfolio_meta_box_video() {
	global $om_portfolio_meta_box;

	echo om_generate_meta_box($om_portfolio_meta_box['video']['fields']);
}

function om_portfolio_meta_box_audio() {
	global $om_portfolio_meta_box;

	echo om_generate_meta_box($om_portfolio_meta_box['audio']['fields']);
}

function om_portfolio_meta_box_size() {
	global $om_portfolio_meta_box;

	echo om_generate_meta_box($om_portfolio_meta_box['size']['fields']);
}

/*************************************************************************************
 *	Save MetaBox data
 *************************************************************************************/

function om_save_portfolio_metabox($post_id) {
	global $om_portfolio_meta_box;
 
	om_save_metabox($post_id, $om_portfolio_meta_box);

}
add_action('save_post', 'om_save_portfolio_metabox');


/*************************************************************************************
 *	Load JS Scripts and Styles
 *************************************************************************************/
 
function om_portfolio_meta_box_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('om-admin-browse-button', TEMPLATE_DIR_URI . '/admin/js/browse-button.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('om-admin-browse-button');
	wp_register_script('om-admin-portfolio-meta', TEMPLATE_DIR_URI . '/admin/js/portfolio-meta.js', array('jquery'));
	wp_enqueue_script('om-admin-portfolio-meta');

}
add_action('admin_print_scripts', 'om_portfolio_meta_box_scripts');

function om_portfolio_meta_box_styles() {
	wp_enqueue_style('thickbox');
}
add_action('admin_print_styles', 'om_portfolio_meta_box_styles');
