<?php

add_action('init','om_options');

if (!function_exists('om_options')) {
	
	function om_options() {

		// Set the Options Array
		$options = array();


		$options[] = array( "name" => __('General settings','om_theme'),
		                    "type" => "heading");


		$options[] = array( 'name' => __('Site logo type', 'om_theme'),
		                    'desc' => __('Choose what do you want to use as site logo: image or plain text.', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'site_logo_type',
		                    'std' => 'text',
		                    'options'=>array(
		                    	'text'=>__('Plain text', 'om_theme'),
		                    	'image'=>__('Image', 'om_theme')
		                    ),
		                    'type' => 'radio');
		                    
		$options[] = array( "name" => __('Site logo text','om_theme'),
							"desc" => __('Specify logo text, if "Plain text" Logo used.','om_theme'),
							"id" => OM_THEME_PREFIX."site_logo_text",
							"std" => "Metro Style",
							"type" => "text");

		$options[] = array( "name" => __('Site logo image','om_theme'),
							"desc" => __('Upload a logo for your theme, or specify the image address of your online logo (http://example.com/logo.png).','om_theme'),
							"id" => OM_THEME_PREFIX."site_logo_image",
							"std" => "",
							"type" => "upload");

		$options[] = array( "name" => __('Site intro text','om_theme'),
					"desc" => __('Intro text on the top of the page','om_theme'),
					"id" => OM_THEME_PREFIX."intro_text",
					"std" => "",
					"type" => "textarea");
					
		$options[] = array( "name" => __('Site favicon','om_theme'),
					"desc" => __('Upload an *.ico file or 16px x 16px Png/Gif image that will for your website\'s favicon.','om_theme'),
					"id" => OM_THEME_PREFIX."favicon",
					"std" => "",
					"type" => "upload");

		$options[] = array( "name" => __('FeedBurner URL','om_theme'),
					"desc" => __('Enter your full FeedBurner URL (or any other preferred feed URL) if you wish to use FeedBurner over the standard WordPress Feed e.g. http://feeds.feedburner.com/yoururlhere','om_theme'),
					"id" => OM_THEME_PREFIX."feedburner",
					"std" => "",
					"type" => "text");
					
		$options[] = array( "name" => __('Sub-footer text line','om_theme'),
					"desc" => '',
					"id" => OM_THEME_PREFIX."footer_text_left",
					"std" => "",
					"type" => "text");

		$options[] = array( 'name' => __('Activate responsive mode', 'om_theme'),
		                    'desc' => __('Check if you want your site to be fitted by width on mobile devices', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'responsive',
		                    'std' => 'true',
		                    'type' => 'checkbox');
		                    
		$options[] = array( 'name' => __('Hide comments block on pages', 'om_theme'),
		                    'desc' => __('Check if you want to hide comments block on single pages. To hide comments on post pages and portfolio - see sections "Post options" and "Portfolio options"', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'hide_comments_pages',
		                    'std' => '',
		                    'type' => 'checkbox');
		                    
		                    
		////////////////////////////////////////////////////////////
		
		$options[] = array( "name" => __('Styling','om_theme'),
		                    "type" => "heading");

		$options[] = array( "name" => "",
							"message" => '<b style="font-size:130%">'.__('Style Presets:','om_theme').'</b>',
							"type" => "intro");

		                    
		$options[] = array( 'name' => '',
		                    'desc' => __('Choose one of the preseted styles or create your own one', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'styling_presets',
		                    'std' => unserialize('a:25:{s:9:"Rosy Dark";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-19.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#ababab";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#FFABC4";s:24:"om_metro_menu_text_color";s:7:"#6E4966";s:36:"om_metro_background_menu_color_hover";s:7:"#6E4966";s:30:"om_metro_menu_text_color_hover";s:7:"#FEFFF2";s:32:"om_metro_background_slider_color";s:7:"#FFABC4";s:26:"om_metro_slider_color_text";s:7:"#6E4966";s:29:"om_metro_slider_color_heading";s:7:"#6E4966";s:38:"om_metro_background_slider_color_hover";s:7:"#6E4966";s:27:"om_metro_slider_color_hover";s:7:"#FEFFF2";s:24:"om_metro_main_text_color";s:7:"#000000";s:24:"om_metro_side_text_color";s:7:"#c9c9c9";s:25:"om_metro_hightlight_color";s:7:"#6E4966";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#6E4966";s:35:"om_metro_background_content_opacity";s:2:"95";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"white";s:32:"om_metro_background_widget_color";s:7:"#6E4966";s:26:"om_metro_widget_text_color";s:7:"#ffffff";s:31:"om_metro_footer_main_text_color";s:7:"#ffffff";s:31:"om_metro_footer_side_text_color";s:7:"#ada0ad";s:32:"om_metro_footer_hightlight_color";s:7:"#FFABC4";}s:9:"Blue Dark";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-30.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#b0b0b0";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#aadfff";s:24:"om_metro_menu_text_color";s:7:"#405469";s:36:"om_metro_background_menu_color_hover";s:7:"#73B5DC";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#aadfff";s:26:"om_metro_slider_color_text";s:7:"#405469";s:29:"om_metro_slider_color_heading";s:7:"#405469";s:38:"om_metro_background_slider_color_hover";s:7:"#73B5DC";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#8a8a8a";s:25:"om_metro_hightlight_color";s:7:"#73B5DC";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#405469";s:35:"om_metro_background_content_opacity";s:3:"100";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"white";s:32:"om_metro_background_widget_color";s:7:"#73B5DC";s:26:"om_metro_widget_text_color";s:7:"#ffffff";s:31:"om_metro_footer_main_text_color";s:7:"#ffffff";s:31:"om_metro_footer_side_text_color";s:7:"#a0a6b0";s:32:"om_metro_footer_hightlight_color";s:7:"#73B5DC";}s:15:"Yellow on Black";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:8:"bg-5.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#707070";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#e9a20a";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#f5b62d";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#e9a20a";s:26:"om_metro_slider_color_text";s:7:"#ffffff";s:29:"om_metro_slider_color_heading";s:7:"#ffffff";s:38:"om_metro_background_slider_color_hover";s:7:"#f5b62d";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#8c8c8c";s:25:"om_metro_hightlight_color";s:7:"#e9a20a";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#e9a20a";s:35:"om_metro_background_content_opacity";s:3:"100";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:7:"#e9a20a";s:26:"om_metro_widget_text_color";s:7:"#ffffff";s:31:"om_metro_footer_main_text_color";s:7:"#ffffff";s:31:"om_metro_footer_side_text_color";s:7:"#f2e1b4";s:32:"om_metro_footer_hightlight_color";s:7:"#805d03";}s:13:"Orange Bright";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-10.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#c5c5c5";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#ff6e22";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#898989";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#f5f4f5";s:26:"om_metro_slider_color_text";s:7:"#c5c5c5";s:29:"om_metro_slider_color_heading";s:7:"#898989";s:38:"om_metro_background_slider_color_hover";s:7:"#898989";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#c5c5c5";s:25:"om_metro_hightlight_color";s:7:"#ff6e22";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#333333";s:35:"om_metro_background_content_opacity";s:2:"95";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:12:""Transparent";s:26:"om_metro_widget_text_color";s:7:"#1a1a1a";s:31:"om_metro_footer_main_text_color";s:7:"#ffffff";s:31:"om_metro_footer_side_text_color";s:7:"#898989";s:32:"om_metro_footer_hightlight_color";s:7:"#ff6e22";}s:7:"Apricot";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-14.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#c2c2c0";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#ff6e22";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#898989";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#fafafa";s:26:"om_metro_slider_color_text";s:7:"#a5a5a5";s:29:"om_metro_slider_color_heading";s:7:"#898989";s:38:"om_metro_background_slider_color_hover";s:7:"#898989";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#c5c5c5";s:25:"om_metro_hightlight_color";s:7:"#ff6e22";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#333333";s:35:"om_metro_background_content_opacity";s:3:"100";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:11:"Transparent";s:26:"om_metro_widget_text_color";s:7:"#1a1a1a";s:31:"om_metro_footer_main_text_color";s:7:"#dbdbdb";s:31:"om_metro_footer_side_text_color";s:7:"#898989";s:32:"om_metro_footer_hightlight_color";s:7:"#ff6e22";}s:10:"Royal Blue";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-14.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#c2c2c0";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#4788ff";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#898989";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#f0f0f0";s:26:"om_metro_slider_color_text";s:7:"#a5a5a5";s:29:"om_metro_slider_color_heading";s:7:"#898989";s:38:"om_metro_background_slider_color_hover";s:7:"#898989";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#c5c5c5";s:25:"om_metro_hightlight_color";s:7:"#4788ff";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#4a4a4a";s:35:"om_metro_background_content_opacity";s:3:"100";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:11:"Transparent";s:26:"om_metro_widget_text_color";s:7:"#1a1a1a";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#898989";s:32:"om_metro_footer_hightlight_color";s:7:"#5086ea";}s:11:"Fresh Apple";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-14.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#c2c2c0";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#80c408";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#898989";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#f0f0f0";s:26:"om_metro_slider_color_text";s:7:"#a5a5a5";s:29:"om_metro_slider_color_heading";s:7:"#898989";s:38:"om_metro_background_slider_color_hover";s:7:"#898989";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#c5c5c5";s:25:"om_metro_hightlight_color";s:7:"#80c408";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#444444";s:35:"om_metro_background_content_opacity";s:3:"100";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:11:"Transparent";s:26:"om_metro_widget_text_color";s:7:"#1a1a1a";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#898989";s:32:"om_metro_footer_hightlight_color";s:7:"#80c408";}s:9:"Coral Red";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-14.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#c2c2c0";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#e52a49";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#898989";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#f0f0f0";s:26:"om_metro_slider_color_text";s:7:"#a5a5a5";s:29:"om_metro_slider_color_heading";s:7:"#898989";s:38:"om_metro_background_slider_color_hover";s:7:"#898989";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#c5c5c5";s:25:"om_metro_hightlight_color";s:7:"#e52a49";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#444444";s:35:"om_metro_background_content_opacity";s:3:"100";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:11:"Transparent";s:26:"om_metro_widget_text_color";s:7:"#1a1a1a";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#898989";s:32:"om_metro_footer_hightlight_color";s:7:"#e52a49";}s:11:"Flat Violet";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-14.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#c2c2c0";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#9041d3";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#898989";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#f0f0f0";s:26:"om_metro_slider_color_text";s:7:"#a5a5a5";s:29:"om_metro_slider_color_heading";s:7:"#898989";s:38:"om_metro_background_slider_color_hover";s:7:"#898989";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#c5c5c5";s:25:"om_metro_hightlight_color";s:7:"#9041d3";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#444444";s:35:"om_metro_background_content_opacity";s:3:"100";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:11:"Transparent";s:26:"om_metro_widget_text_color";s:7:"#1a1a1a";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#898989";s:32:"om_metro_footer_hightlight_color";s:7:"#9041d3";}s:14:"Turquoise Glow";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-14.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#c2c2c0";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#2abfd5";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#898989";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#f0f0f0";s:26:"om_metro_slider_color_text";s:7:"#a5a5a5";s:29:"om_metro_slider_color_heading";s:7:"#898989";s:38:"om_metro_background_slider_color_hover";s:7:"#898989";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#c5c5c5";s:25:"om_metro_hightlight_color";s:7:"#2abfd5";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#444444";s:35:"om_metro_background_content_opacity";s:3:"100";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:7:"#2abfd5";s:26:"om_metro_widget_text_color";s:7:"#ffffff";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#898989";s:32:"om_metro_footer_hightlight_color";s:7:"#2abfd5";}s:10:"Yellow Sun";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-14.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#c2c2c0";s:27:"om_metro_social_icons_color";s:4:"dark";s:30:"om_metro_background_menu_color";s:7:"#f4cb0a";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#444444";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#f0f0f0";s:26:"om_metro_slider_color_text";s:7:"#a5a5a5";s:29:"om_metro_slider_color_heading";s:7:"#898989";s:38:"om_metro_background_slider_color_hover";s:7:"#898989";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#c5c5c5";s:25:"om_metro_hightlight_color";s:7:"#f4cb0a";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#444444";s:35:"om_metro_background_content_opacity";s:3:"100";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:12:""Transparent";s:26:"om_metro_widget_text_color";s:7:"#1a1a1a";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#898989";s:32:"om_metro_footer_hightlight_color";s:7:"#f4cb0a";}s:11:"Grass Green";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-14.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#c2c2c0";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#88a026";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#898989";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#f0f0f0";s:26:"om_metro_slider_color_text";s:7:"#a5a5a5";s:29:"om_metro_slider_color_heading";s:7:"#898989";s:38:"om_metro_background_slider_color_hover";s:7:"#898989";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#c5c5c5";s:25:"om_metro_hightlight_color";s:7:"#88a026";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#444444";s:35:"om_metro_background_content_opacity";s:3:"100";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:11:"Transparent";s:26:"om_metro_widget_text_color";s:8:"#1a1a1sa";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#898989";s:32:"om_metro_footer_hightlight_color";s:7:"#88a026";}s:10:"Brown Bear";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-14.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#c2c2c0";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#ae5907";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#898989";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#f0f0f0";s:26:"om_metro_slider_color_text";s:7:"#a5a5a5";s:29:"om_metro_slider_color_heading";s:7:"#898989";s:38:"om_metro_background_slider_color_hover";s:7:"#898989";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#c5c5c5";s:25:"om_metro_hightlight_color";s:7:"#ae5907";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#444444";s:35:"om_metro_background_content_opacity";s:3:"100";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:7:"#ae5907";s:26:"om_metro_widget_text_color";s:7:"#ffffff";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#898989";s:32:"om_metro_footer_hightlight_color";s:7:"#ae5907";}s:12:"Dark Asphalt";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-14.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#c2c2c0";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#474747";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#898989";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#f0f0f0";s:26:"om_metro_slider_color_text";s:7:"#a5a5a5";s:29:"om_metro_slider_color_heading";s:7:"#898989";s:38:"om_metro_background_slider_color_hover";s:7:"#898989";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#c5c5c5";s:25:"om_metro_hightlight_color";s:7:"#474747";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#474747";s:35:"om_metro_background_content_opacity";s:3:"100";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:7:"#474747";s:26:"om_metro_widget_text_color";s:7:"#ffffff";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#898989";s:32:"om_metro_footer_hightlight_color";s:7:"#ffffff";}s:11:"Light Smoke";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-14.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#c2c2c0";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#898989";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#898989";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#f0f0f0";s:26:"om_metro_slider_color_text";s:7:"#a5a5a5";s:29:"om_metro_slider_color_heading";s:7:"#898989";s:38:"om_metro_background_slider_color_hover";s:7:"#898989";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#c5c5c5";s:25:"om_metro_hightlight_color";s:7:"#8f8f8f";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#898989";s:35:"om_metro_background_content_opacity";s:3:"100";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:7:"#898989";s:26:"om_metro_widget_text_color";s:7:"#ffffff";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#bababa";s:32:"om_metro_footer_hightlight_color";s:7:"#ffffff";}s:17:"Strong Red & Gray";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:8:"bg-3.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#454545";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#dc271c";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#bb0000";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#dc271c";s:26:"om_metro_slider_color_text";s:7:"#ffffff";s:29:"om_metro_slider_color_heading";s:7:"#ffffff";s:38:"om_metro_background_slider_color_hover";s:7:"#bb0000";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#000000";s:24:"om_metro_side_text_color";s:7:"#c9c9c9";s:25:"om_metro_hightlight_color";s:7:"#dc271c";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#444444";s:35:"om_metro_background_content_opacity";s:2:"90";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:2:"50";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:7:"#dc271c";s:26:"om_metro_widget_text_color";s:7:"#ffffff";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#9b9b9b";s:32:"om_metro_footer_hightlight_color";s:7:"#ffffff";}s:18:"Blue & Light Smoke";a:36:{s:25:"om_metro_background_color";s:7:"#6E4966";s:23:"om_metro_background_img";s:9:"bg-30.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#707070";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#f0f0f0";s:24:"om_metro_menu_text_color";s:7:"#405469";s:36:"om_metro_background_menu_color_hover";s:7:"#73B5DC";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#aadfff";s:26:"om_metro_slider_color_text";s:7:"#405469";s:29:"om_metro_slider_color_heading";s:7:"#405469";s:38:"om_metro_background_slider_color_hover";s:7:"#73B5DC";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#8a8a8a";s:25:"om_metro_hightlight_color";s:7:"#73B5DC";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#444444";s:35:"om_metro_background_content_opacity";s:2:"80";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:2:"70";s:33:"om_metro_background_dimming_color";s:5:"white";s:32:"om_metro_background_widget_color";s:12:""Transparent";s:26:"om_metro_widget_text_color";s:7:"#405469";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#9b9b9b";s:32:"om_metro_footer_hightlight_color";s:7:"#ffffff";}s:19:"Sunny Green & Black";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-11.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#b5b5b5";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#bddb69";s:24:"om_metro_menu_text_color";s:7:"#637225";s:36:"om_metro_background_menu_color_hover";s:7:"#7c9335";s:30:"om_metro_menu_text_color_hover";s:7:"#eaf9af";s:32:"om_metro_background_slider_color";s:7:"#e7f4b7";s:26:"om_metro_slider_color_text";s:7:"#7c9335";s:29:"om_metro_slider_color_heading";s:7:"#7c9335";s:38:"om_metro_background_slider_color_hover";s:7:"#7c9335";s:27:"om_metro_slider_color_hover";s:7:"#eaf9af";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#c9c9c9";s:25:"om_metro_hightlight_color";s:7:"#7c9335";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#444444";s:35:"om_metro_background_content_opacity";s:3:"100";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:2:"80";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:7:"#7c9335";s:26:"om_metro_widget_text_color";s:7:"#ffffff";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#9b9b9b";s:32:"om_metro_footer_hightlight_color";s:7:"#ffffff";}s:17:"Blue & Photo Back";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-30.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:22:"../img/bg/bg-pic-1.jpg";s:23:"om_metro_background_pos";s:5:"cover";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";s:4:"true";s:25:"om_metro_intro_text_color";s:7:"#d1d1d1";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#aadfff";s:24:"om_metro_menu_text_color";s:7:"#405469";s:36:"om_metro_background_menu_color_hover";s:7:"#73B5DC";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#aadfff";s:26:"om_metro_slider_color_text";s:7:"#405469";s:29:"om_metro_slider_color_heading";s:7:"#405469";s:38:"om_metro_background_slider_color_hover";s:7:"#73B5DC";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#8a8a8a";s:25:"om_metro_hightlight_color";s:7:"#73B5DC";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#444444";s:35:"om_metro_background_content_opacity";s:2:"85";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:2:"20";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:7:"#73B5DC";s:26:"om_metro_widget_text_color";s:7:"#ffffff";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#9b9b9b";s:32:"om_metro_footer_hightlight_color";s:7:"#73B5DC";}s:17:"Blue & Gauss Back";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-11.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:22:"../img/bg/bg-pic-2.jpg";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#b5b5b5";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#73B5DC";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#73B5DC";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#ffffff";s:26:"om_metro_slider_color_text";s:7:"#5e7380";s:29:"om_metro_slider_color_heading";s:7:"#5e7380";s:38:"om_metro_background_slider_color_hover";s:7:"#73B5DC";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#c9c9c9";s:25:"om_metro_hightlight_color";s:7:"#73B5DC";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#444444";s:35:"om_metro_background_content_opacity";s:2:"70";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"white";s:32:"om_metro_background_widget_color";s:11:"Transparent";s:26:"om_metro_widget_text_color";s:7:"#1a1a1a";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#9b9b9b";s:32:"om_metro_footer_hightlight_color";s:7:"#73B5DC";}s:21:"Dark Sea & Gauss Back";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-11.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:22:"../img/bg/bg-pic-2.jpg";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#707070";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#1E3444";s:24:"om_metro_menu_text_color";s:7:"#8393b3";s:36:"om_metro_background_menu_color_hover";s:7:"#73B5DC";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#1E3444";s:26:"om_metro_slider_color_text";s:7:"#8393b3";s:29:"om_metro_slider_color_heading";s:7:"#8393b3";s:38:"om_metro_background_slider_color_hover";s:7:"#73B5DC";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#8c8c8c";s:25:"om_metro_hightlight_color";s:7:"#73B5DC";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#1E3444";s:35:"om_metro_background_content_opacity";s:2:"75";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:2:"50";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:7:"#1E3444";s:26:"om_metro_widget_text_color";s:7:"#8393b3";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#9b9b9b";s:32:"om_metro_footer_hightlight_color";s:7:"#73B5DC";}s:14:"Yellow & Black";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:8:"bg-5.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#707070";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#e9a20a";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#f5b62d";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#e9a20a";s:26:"om_metro_slider_color_text";s:7:"#ffffff";s:29:"om_metro_slider_color_heading";s:7:"#ffffff";s:38:"om_metro_background_slider_color_hover";s:7:"#f5b62d";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#8c8c8c";s:25:"om_metro_hightlight_color";s:7:"#e9a20a";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#444444";s:35:"om_metro_background_content_opacity";s:3:"100";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:7:"#f5b62d";s:26:"om_metro_widget_text_color";s:7:"#ffffff";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#9b9b9b";s:32:"om_metro_footer_hightlight_color";s:7:"#f5b62d";}s:20:"Orange Bright & Dark";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-29.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#a8a8a8";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#ff6e22";s:24:"om_metro_menu_text_color";s:7:"#ffffff";s:36:"om_metro_background_menu_color_hover";s:7:"#898989";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#f5f4f5";s:26:"om_metro_slider_color_text";s:7:"#c5c5c5";s:29:"om_metro_slider_color_heading";s:7:"#898989";s:38:"om_metro_background_slider_color_hover";s:7:"#898989";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#c5c5c5";s:25:"om_metro_hightlight_color";s:7:"#ff6e22";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#444444";s:35:"om_metro_background_content_opacity";s:2:"95";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:7:"#898989";s:26:"om_metro_widget_text_color";s:7:"#ffffff";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#9b9b9b";s:32:"om_metro_footer_hightlight_color";s:7:"#ff6e22";}s:16:"Eurquoise Breeze";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:9:"bg-20.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#787878";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#97d5cd";s:24:"om_metro_menu_text_color";s:7:"#556270";s:36:"om_metro_background_menu_color_hover";s:7:"#556270";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#a1e5dc";s:26:"om_metro_slider_color_text";s:7:"#556270";s:29:"om_metro_slider_color_heading";s:7:"#556270";s:38:"om_metro_background_slider_color_hover";s:7:"#556270";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#a6a6a6";s:25:"om_metro_hightlight_color";s:7:"#4ECDC4";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#556270";s:35:"om_metro_background_content_opacity";s:2:"90";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:2:"30";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:11:"transparent";s:26:"om_metro_widget_text_color";s:7:"#1a1a1a";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#9b9b9b";s:32:"om_metro_footer_hightlight_color";s:7:"#4ECDC4";}s:24:"Eurquoise Breeze & Black";a:36:{s:25:"om_metro_background_color";s:7:"#ffffff";s:23:"om_metro_background_img";s:8:"bg-5.png";s:26:"om_metro_background_custom";N;s:30:"om_metro_background_img_custom";s:0:"";s:23:"om_metro_background_pos";s:6:"repeat";s:26:"om_metro_background_attach";s:5:"fixed";s:32:"om_metro_background_dots_overlay";N;s:25:"om_metro_intro_text_color";s:7:"#949494";s:27:"om_metro_social_icons_color";s:5:"light";s:30:"om_metro_background_menu_color";s:7:"#97d5cd";s:24:"om_metro_menu_text_color";s:7:"#556270";s:36:"om_metro_background_menu_color_hover";s:7:"#556270";s:30:"om_metro_menu_text_color_hover";s:7:"#ffffff";s:32:"om_metro_background_slider_color";s:7:"#ffffff";s:26:"om_metro_slider_color_text";s:7:"#556270";s:29:"om_metro_slider_color_heading";s:7:"#556270";s:38:"om_metro_background_slider_color_hover";s:7:"#556270";s:27:"om_metro_slider_color_hover";s:7:"#ffffff";s:24:"om_metro_main_text_color";s:7:"#1a1a1a";s:24:"om_metro_side_text_color";s:7:"#808080";s:25:"om_metro_hightlight_color";s:7:"#4ECDC4";s:38:"om_metro_background_main_content_color";s:7:"#ffffff";s:33:"om_metro_background_sidebar_color";s:7:"#ffffff";s:32:"om_metro_background_footer_color";s:7:"#444444";s:35:"om_metro_background_content_opacity";s:2:"95";s:18:"om_metro_base_font";s:9:"Open Sans";s:25:"om_metro_custom_base_font";s:0:"";s:17:"om_metro_sec_font";s:9:"Ropa Sans";s:24:"om_metro_custom_sec_font";s:4:"Lato";s:27:"om_metro_background_dimming";s:1:"0";s:33:"om_metro_background_dimming_color";s:5:"black";s:32:"om_metro_background_widget_color";s:11:"Transparent";s:26:"om_metro_widget_text_color";s:7:"#1a1a1a";s:31:"om_metro_footer_main_text_color";s:7:"#eeeeee";s:31:"om_metro_footer_side_text_color";s:7:"#9b9b9b";s:32:"om_metro_footer_hightlight_color";s:7:"#4ECDC4";}}'),
		                    'options' => array(
		                    	OM_THEME_PREFIX."background_color",
		                    	OM_THEME_PREFIX."background_img",
		                    	OM_THEME_PREFIX."background_custom",
													OM_THEME_PREFIX . 'background_img_custom',
													OM_THEME_PREFIX . 'background_pos',
													OM_THEME_PREFIX . 'background_attach',
													OM_THEME_PREFIX . 'background_dots_overlay',
													OM_THEME_PREFIX."intro_text_color",
													OM_THEME_PREFIX . 'social_icons_color',
													OM_THEME_PREFIX."background_menu_color",
													OM_THEME_PREFIX."menu_text_color",
													OM_THEME_PREFIX."background_menu_color_hover",
													OM_THEME_PREFIX."menu_text_color_hover",
													OM_THEME_PREFIX."background_slider_color",
													OM_THEME_PREFIX."slider_color_text",
													OM_THEME_PREFIX."slider_color_heading",
													OM_THEME_PREFIX."background_slider_color_hover",
													OM_THEME_PREFIX."slider_color_hover",
													OM_THEME_PREFIX."main_text_color",
													OM_THEME_PREFIX."side_text_color",
													OM_THEME_PREFIX."hightlight_color",
													OM_THEME_PREFIX."background_main_content_color",
													OM_THEME_PREFIX."background_sidebar_color",
													OM_THEME_PREFIX."background_footer_color",
													OM_THEME_PREFIX . 'background_content_opacity',
													OM_THEME_PREFIX . 'base_font',
													OM_THEME_PREFIX . 'custom_base_font',
													OM_THEME_PREFIX . 'sec_font',
													OM_THEME_PREFIX . 'custom_sec_font',
													OM_THEME_PREFIX . 'background_dimming',
													OM_THEME_PREFIX . 'background_dimming_color',
													OM_THEME_PREFIX."background_widget_color",
													OM_THEME_PREFIX."widget_text_color",
													OM_THEME_PREFIX."footer_main_text_color",
													OM_THEME_PREFIX."footer_side_text_color",
													OM_THEME_PREFIX."footer_hightlight_color",
		                    ),
		                    'type' => 'styling_presets');		
		                    		                    
		$options[] = array( "name" => "",
							"message" => '<b style="font-size:130%">'.__('Overall Background:','om_theme').'</b>',
							"type" => "intro");
							
		$options[] = array( "name" => __('Background color', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."background_color",
		                    "std" => "#ffffff",
		                    "type" => "color");
		                    
		$options[] = array( 'name' => __('Background pattern', 'om_theme'),
		                    'desc' => __('Choose one of the builded-in background', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'background_img',
		                    'std' => 'bg-14.png',
		                    'options'=>array(
		                    	'' => 'none',
		                    	'bg-1.png' => 'Pattern #1',
		                    	'bg-2.jpg' => 'Pattern #2',
		                    	'bg-3.png' => 'Pattern #3',
		                    	'bg-4.png' => 'Pattern #4',
		                    	'bg-5.png' => 'Pattern #5',
		                    	'bg-6.png' => 'Pattern #6',
		                    	'bg-7.png' => 'Pattern #7',
		                    	'bg-8.png' => 'Pattern #8',
		                    	'bg-9.png' => 'Pattern #9',
		                    	'bg-10.png' => 'Pattern #10',
		                    	'bg-11.png' => 'Pattern #11',
		                    	'bg-12.png' => 'Pattern #12',
		                    	'bg-13.png' => 'Pattern #13',
		                    	'bg-14.png' => 'Pattern #14',
		                    	'bg-15.png' => 'Pattern #15',
		                    	'bg-16.png' => 'Pattern #16',
		                    	'bg-17.png' => 'Pattern #17',
		                    	'bg-18.png' => 'Pattern #18',
		                    	'bg-19.png' => 'Pattern #19',
		                    	'bg-20.png' => 'Pattern #20',
		                    	'bg-21.png' => 'Pattern #21',
		                    	'bg-22.png' => 'Pattern #22',
		                    	'bg-23.png' => 'Pattern #23',
		                    	'bg-24.png' => 'Pattern #24',
		                    	'bg-25.png' => 'Pattern #25',
		                    	'bg-26.png' => 'Pattern #26',
		                    	'bg-27.png' => 'Pattern #27',
		                    	'bg-28.png' => 'Pattern #28',
		                    	'bg-29.png' => 'Pattern #29',
		                    	'bg-30.png' => 'Pattern #30',
		                    ),
		                    'type' => 'select2');
		$mess='<a href="#" onclick="jQuery(\'#om_patterns_preview\').slideToggle();return false"><b>'.__('Background pattern preview','om_theme').' (+)</b></a><div id="om_patterns_preview" style="display:none">';
		for($i=1;$i<=30;$i++) {
			$mess.='<div style="width:70px;height:70px;background:url('.TEMPLATE_DIR_URI.'/img/bg/bg-'.$i.'.png);display:inline-block;margin:0 10px 10px 0;padding:5px;">Pattern #'.$i.'</div>';
		}
		$mess.='</div>';
		$options[] = array( "name" => "",
							"message" => $mess,
							"type" => "intro");
		                    
		$options[] = array( 'name' => __('Custom Background image', 'om_theme'),
		                    'desc' => __('Upload your background, or leave this field empty to choose one of the above dropdown', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'background_img_custom',
		                    'std' => '',
		                    'type' => 'upload');
		                    
		$options[] = array( 'name' => __('Custom Background position', 'om_theme'),
		                    'desc' => '',
		                    'id' =>  OM_THEME_PREFIX . 'background_pos',
		                    'std' => 'repeat',
		                    'options'=>array(
		                    	'repeat' => 'Repeat image',
		                    	'repeat_x_top' => 'Repeat-x image top',
		                    	'repeat_x_center' => 'Repeat-x image center',
		                    	'repeat_x_bottom' => 'Repeat-x image bottom',
		                    	'repeat_y_left' => 'Repeat-y image left',
		                    	'repeat_y_center' => 'Repeat-y image center',
		                    	'repeat_y_right' => 'Repeat-y image right',
		                    	'cover' => 'Cover (Full Screen)',
		                    	'no_repeat_left_top' => 'No-Repeat Left Top',
		                    	'no_repeat_top' => 'No-Repeat Top',
		                    	'no_repeat_right_top' => 'No-Repeat Right Top',
		                    	'no_repeat_right' => 'No-Repeat Right',
		                    	'no_repeat_right_bottom' => 'No-Repeat Right Bottom',
		                    	'no_repeat_bottom' => 'No-Repeat Bottom',
		                    	'no_repeat_left_bottom' => 'No-Repeat Left Bottom',
		                    	'no_repeat_left' => 'No-Repeat Left',
		                    ),
		                    'type' => 'select2');
		                    
		$options[] = array( 'name' => __('Background attachment', 'om_theme'),
		                    'desc' => '',
		                    'id' =>  OM_THEME_PREFIX . 'background_attach',
		                    'std' => 'fixed',
		                    'options'=>array(
		                    	'fixed' => 'Fixed',
		                    	'scroll' => 'Scroll',
		                    ),
		                    'type' => 'select2');
		                    
		$options[] = array( 'name' => __('Enable additional dots overlay layer', 'om_theme'),
		                    'desc' => __('Useful for full screen background images', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'background_dots_overlay',
		                    'std' => '',
		                    'type' => 'checkbox');
		                    
		$options[] = array( 'name' => __('Background image dimmer (percent)', 'om_theme'),
		                    'desc' => __('Value between 0 and 100 to dim background image. 0 - no dim, 100 - maximum dim', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'background_dimming',
		                    'std' => '0',
		                    'type' => 'text');

		$options[] = array( 'name' => __('Background image dimmer color', 'om_theme'),
		                    'desc' => __('Choose the dimming color', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'background_dimming_color',
		                    'std' => 'black',
		                    'options' => array(
		                    	'black'=>'Black',
		                    	'white'=>'White',
		                    ),
		                    'type' => 'select2');
		                    
		$options[] = array( "name" => "",
							"message" => '<b style="font-size:130%">'.__('Header:','om_theme').'</b>',
							"type" => "intro");
							
		$options[] = array( "name" => __('Site intro text color', 'om_theme'),
		                    "desc" => __('At the top of the site', 'om_theme'),
		                    "id" => OM_THEME_PREFIX."intro_text_color",
		                    "std" => "#c2c2c0",
		                    "type" => "color");
		                    
		$options[] = array( "name" => "",
							"message" => '<b style="font-size:130%">'.__('Primary Menu:','om_theme').'</b>',
							"type" => "intro");
							
		$options[] = array( "name" => __('Menu panes background color', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."background_menu_color",
		                    "std" => "#ff6e22",
		                    "type" => "color");

		$options[] = array( "name" => __('Menu text color', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."menu_text_color",
		                    "std" => "#ffffff",
		                    "type" => "color");

		$options[] = array( "name" => __('Menu panes background color by hover', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."background_menu_color_hover",
		                    "std" => "#898989",
		                    "type" => "color");
		                    
		$options[] = array( "name" => __('Menu text color by hover', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."menu_text_color_hover",
		                    "std" => "#ffffff",
		                    "type" => "color");
		                    
		$options[] = array( "name" => "",
							"message" => '<b style="font-size:130%">'.__('Homepage Slider:','om_theme').'</b>',
							"type" => "intro");

		$options[] = array( "name" => __('Slider panes background color', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."background_slider_color",
		                    "std" => "#fafafa",
		                    "type" => "color");

		$options[] = array( "name" => __('Slider text color', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."slider_color_text",
		                    "std" => "#a5a5a5",
		                    "type" => "color");

		$options[] = array( "name" => __('Slider headings color', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."slider_color_heading",
		                    "std" => "#898989",
		                    "type" => "color");
		                    
		$options[] = array( "name" => __('Slider panes background color by hover', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."background_slider_color_hover",
		                    "std" => "#898989",
		                    "type" => "color");
		                    
		$options[] = array( "name" => __('Slider text and headings color by hover', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."slider_color_hover",
		                    "std" => "#ffffff",
		                    "type" => "color");


		$options[] = array( "name" => "",
							"message" => '<b style="font-size:130%">'.__('Main Content/Sidebar:','om_theme').'</b>',
							"type" => "intro");

		$options[] = array( "name" => __('Main text color', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."main_text_color",
		                    "std" => "#1a1a1a",
		                    "type" => "color");
		                    
		$options[] = array( "name" => __('Side text color', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."side_text_color",
		                    "std" => "#c5c5c5",
		                    "type" => "color");

		$options[] = array( "name" => __('Hightlight color', 'om_theme'),
		                    "desc" => 'For highlighted text, links, controls',
		                    "id" => OM_THEME_PREFIX."hightlight_color",
		                    "std" => "#ff6e22",
		                    "type" => "color");
							
		$options[] = array( "name" => __('Main content panes background color', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."background_main_content_color",
		                    "std" => "#ffffff",
		                    "type" => "color");

		$options[] = array( "name" => __('Sidebar panes background color', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."background_sidebar_color",
		                    "std" => "#ffffff",
		                    "type" => "color");

		$options[] = array( "name" => __('Sidebar Widget Title Background Color', 'om_theme'),
		                    "desc" => __('You can set value "Transparent" if there is no need in special color', 'om_theme'),
		                    "id" => OM_THEME_PREFIX. 'background_widget_color',
		                    "std" => "transparent",
		                    "type" => "color");
		                    
		$options[] = array( "name" => __('Sidebar Widget Title Text Color', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX. 'widget_text_color',
		                    "std" => "#1a1a1a",
		                    "type" => "color");
		                    
		$options[] = array( "name" => "",
							"message" => '<b style="font-size:130%">'.__('Footer:','om_theme').'</b>',
							"type" => "intro");

		$options[] = array( "name" => __('Footer panes background color', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."background_footer_color",
		                    "std" => "#333333",
		                    "type" => "color");

		$options[] = array( "name" => __('Footer text color', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."footer_main_text_color",
		                    "std" => "#dbdbdb",
		                    "type" => "color");
		                    
		$options[] = array( "name" => __('Footer side text color', 'om_theme'),
		                    "desc" => '',
		                    "id" => OM_THEME_PREFIX."footer_side_text_color",
		                    "std" => "#898989",
		                    "type" => "color");
		                    
		$options[] = array( "name" => __('Footer hightlight color', 'om_theme'),
		                    "desc" => 'For highlighted text, links, controls',
		                    "id" => OM_THEME_PREFIX."footer_hightlight_color",
		                    "std" => "#ff6e22",
		                    "type" => "color");


		$options[] = array( 'name' => __('Social Icons color', 'om_theme'),
		                    'desc' => __('If footer background is light - use dark icons, if background is dark - use light icons', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_icons_color',
		                    'std' => 'dark',
		                    'options'=>array(
		                    	'dark' => 'Dark',
		                    	'light' => 'Light',
		                    ),
		                    'type' => 'select2');

		                    
		$options[] = array( "name" => "",
							"message" => '<b style="font-size:130%">'.__('All panes:','om_theme').'</b>',
							"type" => "intro");

                    		                    		                    
		$options[] = array( 'name' => __('Menu/Content/Sidebar/Footer panes background opacity', 'om_theme'),
		                    'desc' => __('Value between 0 and 100. 0 - transparent, 100 - opaque', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'background_content_opacity',
		                    'std' => '100',
		                    'type' => 'text');
		                    
		$options[] = array( "name" => "",
							"message" => '<b style="font-size:130%">'.__('Fonts:','om_theme').'</b>',
							"type" => "intro");
									                    
		$options[] = array( 'name' => __('Base font', 'om_theme'),
		                    'desc' => '',
		                    'id' =>  OM_THEME_PREFIX . 'base_font',
		                    'std' => 'Open Sans',
		                    'options'=>array(
		                    	'Arial' => 'Arial',
		                    	'Times New Roman' => 'Times New Roman',
		                    	'Verdana' => 'Verdana',
		                    	'Tahoma' => 'Tahoma',
		                    	'Courier' => 'Courier',
		                    	'Courier New' => 'Courier New',
		                    	'Georgia' => 'Georgia',
		                    	'Impact' => 'Impact',
		                    	'Lucida Console' => 'Lucida Console',
		                    	'Trebuchet MS' => 'Trebuchet MS',
		                    	'Arvo' => 'Arvo',
		                    	'Open Sans' => 'Open Sans',
		                    ),
		                    'type' => 'select2');
		                    
		$options[] = array( 'name' => __('Custom Google.Fonts base font', 'om_theme'),
		                    'desc' => __('Choose the the font from <a href="http://www.google.com/webfonts" target="_blank">http://www.google.com/webfonts</a> and enter the name.<br/>If you want to choose the font from the list above, leave this field empty', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'custom_base_font',
		                    'std' => '',
		                    'type' => 'text');

		$options[] = array( 'name' => __('Highlight font', 'om_theme'),
		                    'desc' => __('Alternative font for some blocks', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'sec_font',
		                    'std' => 'Lato',
		                    'options'=>array(
		                    	'Arial' => 'Arial',
		                    	'Times New Roman' => 'Times New Roman',
		                    	'Verdana' => 'Verdana',
		                    	'Tahoma' => 'Tahoma',
		                    	'Courier' => 'Courier',
		                    	'Courier New' => 'Courier New',
		                    	'Georgia' => 'Georgia',
		                    	'Impact' => 'Impact',
		                    	'Lucida Console' => 'Lucida Console',
		                    	'Trebuchet MS' => 'Trebuchet MS',
		                    	'Arvo' => 'Arvo',
		                    	'Open Sans' => 'Open Sans',
		                    	'Oxygen' => 'Oxygen',
		                    	'Ropa Sans' => 'Ropa Sans',
		                    	'Lato' => 'Lato'
		                    ),
		                    'type' => 'select2');
		                    
		$options[] = array( 'name' => __('Custom Google.Fonts highlight font', 'om_theme'),
		                    'desc' => __('Choose the the font from <a href="http://www.google.com/webfonts" target="_blank">http://www.google.com/webfonts</a> and enter the name.<br/>If you want to choose the font from the list above, leave this field empty', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'custom_sec_font',
		                    'std' => '',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Unclude character sets for fonts (Latin charset by default)', 'om_theme'),
		                    'desc' => __('Latin Extended', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'font_char_latin_ext',
		                    'std' => '',
		                    'type' => 'checkbox');
		                    
		$options[] = array( 'name' => '',
		                    'desc' => __('Greek Extended', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'font_char_greek_ext',
		                    'std' => '',
		                    'type' => 'checkbox');
		                    
		$options[] = array( 'name' => '',
		                    'desc' => __('Greek', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'font_char_greek',
		                    'std' => '',
		                    'type' => 'checkbox');

		$options[] = array( 'name' => '',
		                    'desc' => __('Cyrillic Extended', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'font_char_cyrillic_ext',
		                    'std' => '',
		                    'type' => 'checkbox');
		                    
		$options[] = array( 'name' => '',
		                    'desc' => __('Cyrillic', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'font_char_cyrillic',
		                    'std' => '',
		                    'type' => 'checkbox');


                    

		                    
		////////////////////////////////////////////////////////////

		$options[] = array( "name" => __('Homepage slider','om_theme'),
		                    "type" => "heading");
		                    
		$options[] = array( 'name' => __('Show slider on the homepage', 'om_theme'),
		                    'desc' => __('Check to show slider on the homepage', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'show_homepage_slider',
		                    'std' => '',
		                    'type' => 'checkbox');

		$options[] = array( 'name' => __('Use checkerboard layout', 'om_theme'),
		                    'desc' => __('Check to alternate picture position and the descrition in slides', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'homepage_slider_checkerboard',
		                    'std' => 'true',
		                    'type' => 'checkbox');
		                    
 		                    
		$options[] = array( 'name' => __('Slider content', 'om_theme'),
		                    'desc' => '',
		                    'id' =>  OM_THEME_PREFIX . 'homepage_slider',
		                    'std' => array(),
		                    'type' => 'slider');
		                    
		////////////////////////////////////////////////////////////
		
		$options[] = array( "name" => __('Post options','om_theme'),
		                    "type" => "heading");
		                    
		$options[] = array( 'name' => __('Show post author', 'om_theme'),
		                    'desc' => __('If checked, author will be displayed on the Post Page', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'post_show_author',
		                    'std' => '',
		                    'type' => 'checkbox');

		$options[] = array( 'name' => __('Hide post categories', 'om_theme'),
		                    'desc' => __('Check, if you want to hide categories for posts', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'post_hide_categories',
		                    'std' => '',
		                    'type' => 'checkbox');

		$options[] = array( 'name' => __('Show featured image on the post page', 'om_theme'),
		                    'desc' => __('Check to show the featured image at the beginning of the post on the single post page', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'post_single_show_thumb',
		                    'std' => '',
		                    'type' => 'checkbox');
		                    
		$options[] = array( 'name' => __('Hide comments block on the post pages', 'om_theme'),
		                    'desc' => __('Check if you want to hide comments block on the post pages.', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'hide_comments_post',
		                    'std' => '',
		                    'type' => 'checkbox');
		                    
		////////////////////////////////////////////////////////////
		
		$options[] = array( "name" => __('Portfolio options','om_theme'),
		                    "type" => "heading");

		$options[] = array( 'name' => __('Portfolio sort type', 'om_theme'),
		                    'desc' => __('If custom sort chosen, porfolio items should be sorted in "Portfolio Sort" page', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'portfolio_sort',
		                    'std' => 'custom',
		                    'options'=>array(
		                    	'custom' => 'custom',
		                    	'date_desc' => 'by Date newer on the top',
		                    	'date_asc' => 'by Date older on the top'
		                    ),
		                    'type' => 'select2');
		                    
		$options[] = array( 'name' => '',
		                    'desc' => __('Show random portfolio items on single portfolio page', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'portfolio_single_show_random',
		                    'std' => '',
		                    'type' => 'checkbox');
		                    
		$options[] = array( 'name' => __('Hide comments block on the portfolio pages', 'om_theme'),
		                    'desc' => __('Check if you want to hide comments block on the portfolio pages.', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'hide_comments_portfolio',
		                    'std' => '',
		                    'type' => 'checkbox');

	                    
		////////////////////////////////////////////////////////////

		$options[] = array( "name" => __('Breadcrumbs','om_theme'),
		                    "type" => "heading");
		                    
		$options[] = array( 'name' => __('Show breadcrumbs', 'om_theme'),
		                    'desc' => __('Check to show navigation chain', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'show_breadcrumbs',
		                    'std' => 'true',
		                    'type' => 'checkbox');

		$options[] = array( 'name' => __('Breadcrumbs caption', 'om_theme'),
		                    'desc' => '',
		                    'id' =>  OM_THEME_PREFIX . 'breadcrumbs_caption',
		                    'std' => __('You are here: ','om_theme'),
		                    'type' => 'text');

		$options[] = array( 'name' => __('Current page title', 'om_theme'),
		                    'desc' => __('Check to include current page title to breadcrumbs', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'breadcrumbs_show_current',
		                    'std' => 'true',
		                    'type' => 'checkbox');
		                    
		////////////////////////////////////////////////////////////

		$options[] = array( "name" => __('Facebook comments','om_theme'),
		                    "type" => "heading");
		                    
		$options[] = array( 'name' => __('Moderator Facebook user ID', 'om_theme'),
		                    'desc' => __('The easiest way to moderate comments - insert Facebook user ID who can moderate comments. To add multiple moderators, separate the uids by comma without spaces.', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'fb_comments_admin_id',
		                    'std' => '',
		                    'type' => 'text');

		$options[] = array( 'name' => __('Your Facebook application ID', 'om_theme'),
		                    'desc' => __('Second way to moderate comments - insert Facebook application ID. You will be able to moderate comments with Facebook Comment Moderation Tool <a href="http://developers.facebook.com/tools/comments" target="_blank">http://developers.facebook.com/tools/comments</a>', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'fb_comments_app_id',
		                    'std' => '',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Number of posts to display by default', 'om_theme'),
		                    'desc' => '',
		                    'id' =>  OM_THEME_PREFIX . 'fb_comments_count',
		                    'std' => '2',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Facebook comments color scheme', 'om_theme'),
		                    'desc' => '',
		                    'id' =>  OM_THEME_PREFIX . 'fb_comments_color',
		                    'std' => '',
		                    'options'=>array(
		                    	'' => 'Light',
		                    	'dark' => 'Dark',
		                    ),
		                    'type' => 'select2');

		$options[] = array( 'name' => __('Show Facebook comments on pages', 'om_theme'),
		                    'desc' => __('Check to show Facebook comments block on single pages', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'fb_comments_pages',
		                    'std' => '',
		                    'type' => 'checkbox');

		$options[] = array( 'name' => __('Show Facebook comments on post pages', 'om_theme'),
		                    'desc' => __('Check to show Facebook comments block on single post pages', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'fb_comments_posts',
		                    'std' => '',
		                    'type' => 'checkbox');

		$options[] = array( 'name' => __('Show Facebook comments on portfolio pages', 'om_theme'),
		                    'desc' => __('Check to show Facebook comments block on single portfolio pages', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'fb_comments_portfolio',
		                    'std' => '',
		                    'type' => 'checkbox');
		                    
		$options[] = array( 'name' => __('Position of Facebook comments', 'om_theme'),
		                    'desc' => '',
		                    'id' =>  OM_THEME_PREFIX . 'fb_comments_position',
		                    'std' => '',
		                    'options'=>array(
		                    	'' => 'Before Wordrpess Comments',
		                    	'after' => 'After Wordpress Comments',
		                    ),
		                    'type' => 'select2');
		                    
	
		////////////////////////////////////////////////////////////
		
		
		$options[] = array( "name" => __('Contact form','om_theme'),
		                    "type" => "heading");

		$options[] = array( "name" => '',
												'message'=> __('Set up form and include it at any page by inserting shortcode <b>[contact_form]</b>','om_theme'),
		                    "type" => "intro");

		$options[] = array( 'name' => __('Email to send the form data', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'form_email',
		                    'std' => '',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Success message', 'om_theme'),
												'desc' => __('Message will be shown after success form submission', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'form_success',
		                    'std' => 'Success!',
		                    'type' => 'textarea');
		                    
		$options[] = array( 'name' => __('Submit button title', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'form_button_title',
		                    'std' => 'Submit',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Form fields', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'form_fields',
		                    'std' => '',
		                    'type' => 'form_fields');
		
		
		////////////////////////////////////////////////////////////
		

		$options[] = array( "name" => __('Sidebars','om_theme'),
		                    "type" => "heading");

		$options[] = array( "name" => __('Sidebar position','om_theme'),
							"desc" => __('Select sidebar alignment.','om_theme'),
							"id" => OM_THEME_PREFIX."sidebar_position",
							"std" => "right",
							"type" => "images",
							"options" => array(
								'right' => TEMPLATE_DIR_URI . '/admin/images/2cr.png',
								'left' => TEMPLATE_DIR_URI . '/admin/images/2cl.png'
							)
						);
						
		$options[] = array( "name" => __('Sidebar sliding','om_theme'),
							"desc" => __('Check to enable sidebar sliding up and down when it\'s height less than page content height.','om_theme'),
							"id" => OM_THEME_PREFIX."sidebar_sliding",
							"std" => "true",
							"type" => "checkbox",
						);
		                    
		$options[] = array( "name" => "",
							"message" => __('You can set the number of available alternative sidebars, set them up at the "Appearance > Widgets" section and choose for every page one of them at the page settings.','om_theme'),
							"type" => "intro");
							
		$options[] = array( "name" => __('Number of alternative sidebars','om_theme'),
					"desc" => '',
					"id" => OM_THEME_PREFIX."sidebars_num",
					"std" => "3",
					"type" => "text");
					
		////////////////////////////////////////////////////////////

		$options[] = array( "name" => __('Social icons','om_theme'),
		                    "type" => "heading");
		                    
		$options[] = array( "name" => '',
												"message" => __('Specify necessary links and icons will be shown in the footer','om_theme'),
		                    "type" => "intro");
		                    
		$options[] = array( 'name' => __('Facebook link', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_facebook',
		                    'std' => '',
		                    'type' => 'text');

		$options[] = array( 'name' => __('LinekdIn link', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_linkedin',
		                    'std' => '',
		                    'type' => 'text');

		$options[] = array( 'name' => __('Twitter link', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_twitter',
		                    'std' => '',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Last.fm link', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_lastfm',
		                    'std' => '',
		                    'type' => 'text');
	
		$options[] = array( 'name' => __('Behance link', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_behance',
		                    'std' => '',
		                    'type' => 'text');

		$options[] = array( 'name' => __('RSS link', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_rss',
		                    'std' => '',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Blogger link', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_blogger',
		                    'std' => '',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Deviantart link', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_deviantart',
		                    'std' => '',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Dribble link', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_dribble',
		                    'std' => '',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Flickr link', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_flickr',
		                    'std' => '',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Google link', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_google',
		                    'std' => '',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Myspace link', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_myspace',
		                    'std' => '',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Pinterest link', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_pinterest',
		                    'std' => '',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Skype link', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_skype',
		                    'std' => '',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Vimeo link', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_vimeo',
		                    'std' => '',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Youtube link', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'social_youtube',
		                    'std' => '',
		                    'type' => 'text');					
					
		////////////////////////////////////////////////////////////

		                    
		$options[] = array( "name" => __('Extra code blocks, counters','om_theme'),
		                    "type" => "heading");
		                    
		$options[] = array( 'name' => __('Code block for custom CSS', 'om_theme'),
		                    'desc' => __('Here you can add you custom CSS classes', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'code_custom_css',
		                    'std' => '',
		                    'type' => 'textarea');
		                    
		$options[] = array( 'name' => __('Code block before &lt;/head&gt;', 'om_theme'),
		                    'desc' => __('Here you can add Google.Analytics code', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'code_before_head',
		                    'std' => '',
		                    'type' => 'textarea');
		                    
		$options[] = array( 'name' => __('Code block before &lt;/body&gt;', 'om_theme'),
		                    'desc' => '',
		                    'id' =>  OM_THEME_PREFIX . 'code_before_body',
		                    'std' => '',
		                    'type' => 'textarea');
		                    
		$options[] = array( 'name' => __('Code block after page header (&lt;/H1&gt;)', 'om_theme'),
		                    'desc' => '',
		                    'id' =>  OM_THEME_PREFIX . 'code_after_page_h1',
		                    'std' => '',
		                    'type' => 'textarea');

		$options[] = array( 'name' => __('Code block after page content', 'om_theme'),
		                    'desc' => '',
		                    'id' =>  OM_THEME_PREFIX . 'code_after_page_content',
		                    'std' => '',
		                    'type' => 'textarea');
		                    
		$options[] = array( 'name' => __('Code block after post header (&lt;/H1&gt;) on the single page', 'om_theme'),
		                    'desc' => '',
		                    'id' =>  OM_THEME_PREFIX . 'code_after_post_h1',
		                    'std' => '',
		                    'type' => 'textarea');

		$options[] = array( 'name' => __('Code block after post content on the single page', 'om_theme'),
		                    'desc' => '',
		                    'id' =>  OM_THEME_PREFIX . 'code_after_post_content',
		                    'std' => '',
		                    'type' => 'textarea');
		                    
		$options[] = array( 'name' => __('Code block after portfolio header (&lt;/H1&gt;) on the single page', 'om_theme'),
		                    'desc' => '',
		                    'id' =>  OM_THEME_PREFIX . 'code_after_portfolio_h1',
		                    'std' => '',
		                    'type' => 'textarea');

		$options[] = array( 'name' => __('Code block after portfolio content on the single page', 'om_theme'),
		                    'desc' => '',
		                    'id' =>  OM_THEME_PREFIX . 'code_after_portfolio_content',
		                    'std' => '',
		                    'type' => 'textarea');
		                    
/*
		                    


										
		$options[] = array( "name" => __('Homepage slider','om_theme'),
		                    "type" => "heading");
		                    
		$options[] = array( 'name' => __('Show slider on the homepage', 'om_theme'),
		                    'desc' => __('Check to show slider on the homepage', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'show_homepage_slider',
		                    'std' => '',
		                    'type' => 'checkbox');
		                    
		$options[] = array( 'name' => __('Autoslide', 'om_theme'),
		                    'desc' => __('Autoslide interval in milliseconds, enter 0 or leave empty to disable autoslide', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'homepage_slider_autoslide',
		                    'std' => '6000',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Animation speed', 'om_theme'),
		                    'desc' => __('Animation speed in milliseconds', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'homepage_slider_animation_speed',
		                    'std' => '800',
		                    'type' => 'text');

		$options[] = array( 'name' => __('Animation effect', 'om_theme'),
		                    'desc' => __('See demo on <a href="http://malsup.com/jquery/cycle/browser.html" target="_blank">http://malsup.com/jquery/cycle/browser.html</a>', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'homepage_slider_animation_effect',
		                    'std' => 'custom',
		                    'type' => 'select2',
		                    "options" => array(
    'custom'=>'Custom Predefined',
		'blindX'=>'blindX',
    'blindY'=>'blindY',
    'blindZ'=>'blindZ',
    'cover'=>'cover',
    'curtainX'=>'curtainX',
    'curtainY'=>'curtainY',
    'fade'=>'fade',
    'fadeZoom'=>'fadeZoom',
    'growX'=>'growX',
    'growY'=>'growY',
    'none'=>'none',
    'scrollUp'=>'scrollUp',
    'scrollDown'=>'scrollDown',
    'scrollLeft'=>'scrollLeft',
    'scrollRight'=>'scrollRight',
    'scrollHorz'=>'scrollHorz',
    'scrollVert'=>'scrollVert',
    'shuffle'=>'shuffle',
    'slideX'=>'slideX',
    'slideY'=>'slideY',
    'toss'=>'toss',
    'turnUp'=>'turnUp',
    'turnDown'=>'turnDown',
    'turnLeft'=>'turnLeft',
    'turnRight'=>'turnRight',
    'uncover'=>'uncover',
    'wipe'=>'wipe',
    'zoom'=>'zoom',
												));
		                    		                    
		$options[] = array( "name" => "",
							"message" => __('Homepage slider consists of slides, grouped to sections.<br/>You can use tag <i>&lt;span class="colored"&gt;this is colored text&lt;/span&gt;</i> in slide title to color the text', 'om_theme'),
							"type" => "intro");
							
		$options[] = array( 'name' => __('Slider content', 'om_theme'),
		                    'desc' => '',
		                    'id' =>  OM_THEME_PREFIX . 'homepage_slider',
		                    'std' => array(),
		                    'type' => 'slider_w_sections');
	  
		                    		                    
		                    
		$options[] = array( "name" => __('Testimonials','om_theme'),
		                    "type" => "heading");
		                    
		$options[] = array( "name" => "",
							"message" => __('List of testimonials with the thumbnails can be displayed on the main page','om_theme'),
							"type" => "intro");
							
		$options[] = array( 'name' => '',
		                    'desc' => __('Show testimonials block on the main page', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'show_testimonials',
		                    'std' => '',
		                    'type' => 'checkbox');

		$options[] = array( "name" => __('Block name','om_theme'),
					"desc" => __('The name of the block at the homepage', 'om_theme'),
					"id" => OM_THEME_PREFIX."testimonials_block_name",
					"std" => "Our customers",
					"type" => "text");
		                    
		$options[] = array( "name" => __('Block name comment','om_theme'),
					"desc" => __('The text line under the block name at the homepage', 'om_theme'),
					"id" => OM_THEME_PREFIX."testimonials_block_name_comment",
					"std" => "A list of partners, clients",
					"type" => "text");
					

		   
		   */
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		                    
		/*
		$options[] = array( "name" => __('Slider','om_theme'),
		                    "type" => "heading");
		                    
		$options[] = array( "name" => "",
							"message" => __('Control and configure the general setup of your theme. Upload your preferred logo, setup your feeds and insert your analytics tracking code.','om_theme'),
							"type" => "intro");
							
		$options[] = array( 'name' => __('Slider', 'om_theme'),
		                    'desc' => __('Slider', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'slider_exampl',
		                    'std' => array(),
		                    'type' => 'slider_w_sections');
		                    
		$options[] = array( "name" => __('General Settings','om_theme'),
		                    "type" => "heading");

		$options[] = array( 'name' => __('Site Tagline', 'om_theme'),
		                    'desc' => __('Add a tagline to display on your site. If you do not want one, please leave this field blank', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'string_exampl',
		                    'std' => '',
		                    'type' => 'text');
		                    
		$options[] = array( 'name' => __('Select Example', 'om_theme'),
		                    'desc' => __('Add a select', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'sel_exampl',
		                    'std' => '',
		                    'options'=>array(
		                    	'Option 1',
		                    	'Option 2',
		                    	'Option 3'
		                    ),
		                    'type' => 'select');
		                    
		$options[] = array( 'name' => __('Select Cat Example', 'om_theme'),
		                    'desc' => __('Add a select Cat', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'sel_cat_exampl',
		                    'std' => '',
		                    'type' => 'select-cat');
		                    
		$options[] = array( 'name' => __('Select Page Example', 'om_theme'),
		                    'desc' => __('Add a select Page', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'sel_page_exampl',
		                    'std' => '',
		                    'type' => 'select-page');
		                    
		$options[] = array( 'name' => __('Select2 Example', 'om_theme'),
		                    'desc' => __('Add a select 2 ', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'sel2_exampl',
		                    'std' => '',
		                    'options'=>array(
		                    	'opt-1'=>'Option 1',
		                    	'opt-2'=>'Option 2',
		                    	'opt-3'=>'Option 3'
		                    ),
		                    'type' => 'select2');
		                    
		$options[] = array( 'name' => __('Site Textarea', 'om_theme'),
		                    'desc' => __('Add a tagline to display on your site. If you do not want one, please leave this field blank', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'textarea_exampl',
		                    'std' => '',
		                    'type' => 'textarea');	
		                    
		$options[] = array( 'name' => __('Select  Radio', 'om_theme'),
		                    'desc' => __('Add ', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'radio_exampl',
		                    'std' => 'opt-2',
		                    'options'=>array(
		                    	'opt-1'=>'Option 1',
		                    	'opt-2'=>'Option 2',
		                    	'opt-3'=>'Option 3'
		                    ),
		                    'type' => 'radio');     
		                    
		$options[] = array( 'name' => __('Site checkbox', 'om_theme'),
		                    'desc' => __('Add a tagline to display on your site. If you do not want one, please leave this field blank', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'check_exampl',
		                    'std' => '',
		                    'type' => 'checkbox');	  

		$options[] = array( "name" => __('Secondary','om_theme'),
		                    "type" => "heading");
		                    
		$options[] = array( 'name' => __('Select Multicheck', 'om_theme'),
		                    'desc' => __('Add ', 'om_theme'),
		                    'id' =>  OM_THEME_PREFIX . 'multicheck_exampl',
		                    'std' => array(
		                    	'opt-2'=>'true'
		                    ),
		                    'options'=>array(
		                    	'opt-1'=>'Option 1',
		                    	'opt-2'=>'Option 2',
		                    	'opt-3'=>'Option 3'
		                    ),
		                    'type' => 'multicheck');                

		$options[] = array( "name" => __('Upload exmp','om_theme'),
							"desc" => __('Upload a logo for your theme, or specify the image address of your online logo. (http://example.com/logo.png)','om_theme'),
							"id" => OM_THEME_PREFIX."upload_exampl",
							"std" => "",
							"type" => "upload");
							
		$options[] = array( "name" => __('Upload MIN exmp','om_theme'),
							"desc" => __('Upload a logo for your theme, or specify the image address of your online logo. (http://example.com/logo.png)','om_theme'),
							"id" => OM_THEME_PREFIX."upload_min_exampl",
							"std" => "",
							"type" => "upload_min");
							
		$options[] = array( "name"=>'',
							"message" => __('Some Message','om_theme'),
							"type" => "note");
							
		$options[] = array( "name" => __('Color', 'om_theme'),
		                    "desc" => __('Select a color to be used as the highlight color within the theme', 'om_theme'),
		                    "id" => OM_THEME_PREFIX."color_exampl",
		                    "std" => "",
		                    "type" => "color");
									                    		 
		$options[] = array( "name" => __('Typography', 'om_theme'),
		                    "desc" => __('Select a color to be used as the highlight color within the theme', 'om_theme'),
		                    "id" => OM_THEME_PREFIX."typograph_exampl",
		                    "std" => "",
		                    "type" => "typography");
		                    
		$options[] = array( "name" => __('Border', 'om_theme'),
		                    "desc" => __('Select a color to be used as the highlight color within the theme', 'om_theme'),
		                    "id" => OM_THEME_PREFIX."border_exampl",
		                    "std" => "",
		                    "type" => "border");
		                    
		$url = TEMPLATE_DIR_URI . '/admin/images/';
		$options[] = array( "name" => __('Main Layout','om_theme'),
							"desc" => __('Select main content and sidebar alignment.','om_theme'),
							"id" => OM_THEME_PREFIX."images_exmapl",
							"std" => "layout-2cr",
							"type" => "images",
							"options" => array(
								'layout-2cr' => $url . '2cr.png',
								'layout-2cl' => $url . '2cl.png')
							);
		*/					
		
		
		update_option(OM_THEME_PREFIX.'options_template',$options); 					  

	}
}