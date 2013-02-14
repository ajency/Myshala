<?php

/*************************************************************************************
 *	Add MetaBox to Page edit page
 *************************************************************************************/

$om_page_meta_box=array (
	'sidebar' => array (
		'id' => 'om-post-meta-box-sidebar',
		'name' => __('Sidebar', 'om_theme'),
		'callback' => 'om_page_meta_box_sidebar',
		'fields' => array (
			array (
				'name' => __('Choose the sidebar','om_theme'),
				'desc' => '',
				'id' => OM_THEME_SHORT_PREFIX.'sidebar',
				'type' => 'sidebar',
				'std' => ''
			),
			
			array ( "name" => __('Page Individual Sidebar Position','om_theme'),
					"desc" => __('Normally sidebar position for all pages can be specified under "Appearance > Theme Options > Sidebars", but you can set sidebar position for current page manually.','om_theme'),
					"id" => OM_THEME_SHORT_PREFIX."sidebar_custom_pos",
					"type" => "select",
					"std" => '',
					'options' => array(
						'' => __('Default (As in "Theme Options")', 'om_theme'),
						'left' => __('Left Side', 'om_theme'),
						'right' => __('Right Side', 'om_theme'),
					)
			),
		),
	),
	
);
 
function om_add_page_meta_box() {
	global $om_page_meta_box;
	
	foreach($om_page_meta_box as $metabox) {
		add_meta_box(
			$metabox['id'],
			$metabox['name'],
			$metabox['callback'],
			'page',
			'normal',
			'high'
		);
	}
 
}
add_action('add_meta_boxes', 'om_add_page_meta_box');

/*************************************************************************************
 *	MetaBox Callbacks Functions
 *************************************************************************************/

function om_page_meta_box_sidebar() {
	global $om_page_meta_box;

	echo om_generate_meta_box($om_page_meta_box['sidebar']['fields']);
}

/*************************************************************************************
 *	Save MetaBox data
 *************************************************************************************/

function om_save_page_metabox($post_id) {
	global $om_page_meta_box;
 
	om_save_metabox($post_id, $om_page_meta_box);

}
add_action('save_post', 'om_save_page_metabox');

