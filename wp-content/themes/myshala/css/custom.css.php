<?php
	$path_to_file = explode( 'wp-content', __FILE__ );
	$path_to_wp = $path_to_file[0];
	require_once( $path_to_wp . '/wp-load.php' );

	header("Content-type: text/css");

	$main_font=get_option(OM_THEME_PREFIX . 'base_font');
	$sec_font=get_option(OM_THEME_PREFIX . 'sec_font');

	$custom_main_font=get_option(OM_THEME_PREFIX . 'custom_base_font');
	if($custom_main_font)
		$main_font=$custom_main_font;
	$custom_sec_font=get_option(OM_THEME_PREFIX . 'custom_sec_font');
	if($custom_sec_font)
		$sec_font=$custom_sec_font;
		
	$chars_le=(get_option(OM_THEME_PREFIX . 'font_char_latin_ext')=='true'?true:false);
	$chars_ge=(get_option(OM_THEME_PREFIX . 'font_char_greek_ext')=='true'?true:false);
	$chars_g=(get_option(OM_THEME_PREFIX . 'font_char_greek')=='true'?true:false);
	$chars_ce=(get_option(OM_THEME_PREFIX . 'font_char_cyrillic_ext')=='true'?true:false);
	$chars_c=(get_option(OM_THEME_PREFIX . 'font_char_cyrillic')=='true'?true:false);
	
	$subset='';
	if($chars_le || $chars_ge || $chars_g || $chars_ce || $chars_c) {
		$subset='&subset=latin';
		
		if($chars_le)
			$subset.=',latin-ext';

		if($chars_ge)
			$subset.=',greek-ext';

		if($chars_g)
			$subset.=',greek';

		if($chars_ce)
			$subset.=',cyrillic-ext';

		if($chars_c)
			$subset.=',cyrillic';
	}
	
	echo '@import url(http://fonts.googleapis.com/css?family='.urlencode($main_font).':400,700,400italic,700italic'.$subset.');';
	echo '@import url(http://fonts.googleapis.com/css?family='.urlencode($sec_font).':400,700'.$subset.');';

?>

/************************
 * Fonts
 ************************/
 
body,
input,
textarea
{
	font-family:'<?php echo $main_font; ?>';
}

.big-slider-slide .text .title,
.logo-text,
h1,h2,h3,h4,h5,h6,
.widget-header,
.hover-add-pane .title,
.new-comment-caption,
.portfolio-small-preview .title,
.testimonials-block .item .name-name,
.biginfopane .text-block-title,
.pricing-column li.pricing-title,
.pricing-column li.pricing-price
{
	font-family:'<?php echo $sec_font; ?>';
}

/************************
 * Background
 ************************/

<?php

	$bg_color=get_option(OM_THEME_PREFIX . 'background_color');
	$bg_img=get_option(OM_THEME_PREFIX . 'background_img');
	$bg_img_custom=get_option(OM_THEME_PREFIX . 'background_img_custom');
	$bg_pos=get_option(OM_THEME_PREFIX . 'background_pos');
	$attach=get_option(OM_THEME_PREFIX . 'background_attach');
	$background_dots_overlay=get_option(OM_THEME_PREFIX . 'background_dots_overlay');

	$style=array();

	if($bg_pos == 'cover')
		$style[]='background-size: cover';
	if($bg_color)
		$style[]='background-color:'.$bg_color;

	if($bg_img_custom)
		$style[]='background-image:url('.$bg_img_custom.')';
	elseif($bg_img)
		$style[]='background-image:url('.TEMPLATE_DIR_URI.'/img/bg/'.$bg_img.')';
	
		
		
	if($bg_img_custom || $bg_img) {
		switch($bg_pos) {
			case 'repeat':
				$style[]='background-repeat:repeat';
			break;
			case 'repeat_x_top':
				$style[]='background-repeat:repeat-x';
				$style[]='background-position:left top';
			break;			
			case 'repeat_x_center':
				$style[]='background-repeat:repeat-x';
				$style[]='background-position:left center';
			break;			
			case 'repeat_x_bottom':
				$style[]='background-repeat:repeat-x';
				$style[]='background-position:left bottom';
			break;
			case 'repeat_y_left':
				$style[]='background-repeat:repeat-y';
				$style[]='background-position:left top';
			break;			
			case 'repeat_y_center':
				$style[]='background-repeat:repeat-y';
				$style[]='background-position:center top';
			break;			
			case 'repeat_y_right':
				$style[]='background-repeat:repeat-y';
				$style[]='background-position:right top';
			break;
			case 'no_repeat_left_top':
				$style[]='background-repeat:no-repeat';
				$style[]='background-position:left top';
			break;
			case 'no_repeat_top':
				$style[]='background-repeat:no-repeat';
				$style[]='background-position:center top';
			break;
			case 'no_repeat_right_top':
				$style[]='background-repeat:no-repeat';
				$style[]='background-position:right top';
			break;
			case 'no_repeat_right':
				$style[]='background-repeat:no-repeat';
				$style[]='background-position:right center';
			break;
			case 'no_repeat_right_bottom':
				$style[]='background-repeat:no-repeat';
				$style[]='background-position:right bottom';
			break;
			case 'no_repeat_bottom':
				$style[]='background-repeat:no-repeat';
				$style[]='background-position:center bottom';
			break;
			case 'no_repeat_left_bottom':
				$style[]='background-repeat:no-repeat';
				$style[]='background-position:left bottom';
			break;			
			case 'no_repeat_left':
				$style[]='background-repeat:no-repeat';
				$style[]='background-position:left center';
			break;
		}

		if($attach == 'fixed')
			$style[]='background-attachment:fixed';
		elseif($attach == 'scroll')
			$style[]='background-attachment:scroll';
	}

	if(!empty($style))
		$style=implode(';',$style);
	else
		$style='';
?>
body
{
	<?php echo $style; ?>
}

<?php
	if($background_dots_overlay == 'true')
		echo '.bg-overlay {background-image:url('.TEMPLATE_DIR_URI.'/img/bg-overlay.png);} ';
		
	$dimming=intval(trim(get_option(OM_THEME_PREFIX . 'background_dimming')));
	if($dimming) {
		if($dimming > 100)
			$dimming=100;
		$dimming/=100;
		$dimming_color=get_option(OM_THEME_PREFIX . 'background_dimming_color');
		if($dimming_color == 'white')
			echo '.bg-overlay {background-color:rgba(255,255,255,'.$dimming.');} ';
		elseif($dimming_color == 'black')
			echo '.bg-overlay {background-color:rgba(0,0,0,'.$dimming.');} ';
	}
	
?>

/************************
 * Colors
 ************************/
 
<?php

	$intro_text_color=get_option(OM_THEME_PREFIX. 'intro_text_color');
	
	$menu_text_color=get_option(OM_THEME_PREFIX."menu_text_color");
	$menu_bg_hover=get_option(OM_THEME_PREFIX."background_menu_color_hover");
	$menu_text_color_hover=get_option(OM_THEME_PREFIX."menu_text_color_hover");
	
	$tmp=str_replace('#','',$menu_text_color_hover);
	$tmp1=round(base_convert(substr($tmp,0,2),16,10));
	$tmp2=round(base_convert(substr($tmp,2,2),16,10));
	$tmp3=round(base_convert(substr($tmp,4,2),16,10));
	$menu_text_color_hover_a='rgba('.$tmp1.','.$tmp2.','.$tmp3.',0.4)';

	$slider_text_color=get_option(OM_THEME_PREFIX."slider_color_text");
	$slider_heading_color=get_option(OM_THEME_PREFIX."slider_color_heading");
	$slider_bg_hover=get_option(OM_THEME_PREFIX."background_slider_color_hover");
	$slider_text_color_hover=get_option(OM_THEME_PREFIX."slider_color_hover");
	
	$main_text_color=get_option(OM_THEME_PREFIX . 'main_text_color');
	$side_text_color=get_option(OM_THEME_PREFIX . 'side_text_color');
	$hightlight_color=get_option(OM_THEME_PREFIX . 'hightlight_color');

	$background_widget_color=get_option(OM_THEME_PREFIX. 'background_widget_color');
	$widget_text_color=get_option(OM_THEME_PREFIX . 'widget_text_color');

	$footer_main_text_color=get_option(OM_THEME_PREFIX . 'footer_main_text_color');
	$footer_side_text_color=get_option(OM_THEME_PREFIX . 'footer_side_text_color');
	$footer_hightlight_color=get_option(OM_THEME_PREFIX . 'footer_hightlight_color');
	
	$background_main_content_color=get_option(OM_THEME_PREFIX . 'background_main_content_color');
	$background_main_content_color_a='';
	$background_menu_color=get_option(OM_THEME_PREFIX . 'background_menu_color');
	$background_menu_color_a='';
	$background_slider_color=get_option(OM_THEME_PREFIX . 'background_slider_color');
	$background_slider_color_a='';
	$background_sidebar_color=get_option(OM_THEME_PREFIX . 'background_sidebar_color');
	$background_sidebar_color_a='';
	$background_footer_color=get_option(OM_THEME_PREFIX . 'background_footer_color');
	$background_footer_color_a='';
	
	$background_content_opacity=trim(get_option(OM_THEME_PREFIX . 'background_content_opacity'));
	
	if($background_content_opacity != '0') {
		$background_content_opacity=intval($background_content_opacity);
		if(!$background_content_opacity)
			$background_content_opacity=100;
		elseif($background_content_opacity > 100)
			$background_content_opacity=100;
	}
	
	if($background_content_opacity < 100) {
			$tmp=str_replace('#','',$background_main_content_color);
			$tmp1=round(base_convert(substr($tmp,0,2),16,10));
			$tmp2=round(base_convert(substr($tmp,2,2),16,10));
			$tmp3=round(base_convert(substr($tmp,4,2),16,10));
			$background_main_content_color_a='rgba('.$tmp1.','.$tmp2.','.$tmp3.','.((float)$background_content_opacity/100).')';

			$tmp=str_replace('#','',$background_menu_color);
			$tmp1=round(base_convert(substr($tmp,0,2),16,10));
			$tmp2=round(base_convert(substr($tmp,2,2),16,10));
			$tmp3=round(base_convert(substr($tmp,4,2),16,10));
			$background_menu_color_a='rgba('.$tmp1.','.$tmp2.','.$tmp3.','.((float)$background_content_opacity/100).')';

			$tmp=str_replace('#','',$background_slider_color);
			$tmp1=round(base_convert(substr($tmp,0,2),16,10));
			$tmp2=round(base_convert(substr($tmp,2,2),16,10));
			$tmp3=round(base_convert(substr($tmp,4,2),16,10));
			$background_slider_color_a='rgba('.$tmp1.','.$tmp2.','.$tmp3.','.((float)$background_content_opacity/100).')';

			$tmp=str_replace('#','',$background_sidebar_color);
			$tmp1=round(base_convert(substr($tmp,0,2),16,10));
			$tmp2=round(base_convert(substr($tmp,2,2),16,10));
			$tmp3=round(base_convert(substr($tmp,4,2),16,10));
			$background_sidebar_color_a='rgba('.$tmp1.','.$tmp2.','.$tmp3.','.((float)$background_content_opacity/100).')';

			$tmp=str_replace('#','',$background_footer_color);
			$tmp1=round(base_convert(substr($tmp,0,2),16,10));
			$tmp2=round(base_convert(substr($tmp,2,2),16,10));
			$tmp3=round(base_convert(substr($tmp,4,2),16,10));
			$background_footer_color_a='rgba('.$tmp1.','.$tmp2.','.$tmp3.','.((float)$background_content_opacity/100).')';
	}

?>

body
{
	color:<?php echo $main_text_color ?>;
}

.headline-text
{
	color:<?php echo $intro_text_color ?>;
}

/* Menu */

.bg-color-menu,
.primary-menu li
{
	background-color:<?php echo $background_menu_color ?>;
	<?php if($background_menu_color_a) { ?> background-color:<?php echo $background_menu_color_a ?>; <?php } ?>
}

.primary-menu a,
.logo-text
{
	color:<?php echo $menu_text_color ?>;
}

.primary-menu li a:hover,
.primary-menu li.active a,
.primary-menu li ul a
{
	color:<?php echo $menu_text_color_hover ?>;
}

.primary-menu li ul,
.primary-menu a:hover,
.primary-menu li.active a
{
	background-color:<?php echo $menu_bg_hover ?>;
}

.primary-menu li ul a
{
	border-bottom-color:<?php echo $menu_text_color_hover_a ?>;
}

.primary-menu li ul a:hover,
.primary-menu li ul li.active > a
{
	background-color:<?php echo $background_menu_color ?>;
	color:<?php echo $menu_text_color ?>;
}

.primary-menu > li > ul:after
{
	background-color:<?php echo $background_menu_color ?>;
}

/* Slider */

.bg-color-slider
{
	background-color:<?php echo $background_slider_color ?>;
	<?php if($background_slider_color_a) { ?> background-color:<?php echo $background_slider_color_a ?>; <?php } ?>
}

.big-slider
{
	color:<?php echo $slider_text_color ?>;
}

.big-slider-slide .text .title
{
	color:<?php echo $slider_heading_color ?>;
}

.big-slider-slide:hover .text,
.big-slider-control .control-left:hover,
.big-slider-control .control-right:hover,
.big-slider-control .control-seek:hover .control-seek-box-inner,
.big-slider-control .control-seek-box.pressed .control-seek-box-inner
{
	background-color:<?php echo $slider_bg_hover ?>;
}

.big-slider-slide:hover .text,
.big-slider-slide:hover .text .title
{
	color:<?php echo $slider_text_color_hover ?>;
}

/* Other */

a,
.sub-footer a:hover,
.post-categories a:hover,
.post-author a:hover,
.post-tags a:hover,
.post-comments a:hover,
.comment .info .name a:hover,
.portfolio-thumb .title,
.latest-tweets .tweet-status a:hover,
.headline-text a:hover,
.testimonials-block .item .qo,
.post-big .post-title a:hover,
.sort-menu li a.button.active .count
{
	color:<?php echo $hightlight_color ?>;
}

.footer a,
.footer .sub-footer a:hover,
.footer .latest-tweets .tweet-status a:hover,
.footer .testimonials-block .item .qo
{
	color:<?php echo $footer_hightlight_color ?>;
}

.bg-color-main,
.sort-menu li a.button .count
{
background:#fff;
}

.bg-color-sidebar
{
	background-color:<?php echo $background_sidebar_color ?>;
	<?php if($background_sidebar_color_a) { ?> background-color:<?php echo $background_sidebar_color_a ?>; <?php } ?>
}

.bg-color-footer
{
	background-color:<?php echo $background_footer_color ?>;
	<?php if($background_footer_color_a) { ?> background-color:<?php echo $background_footer_color_a ?>; <?php } ?>
}

.custom-gallery .controls,
.navigation-pages a:hover span.item,
.navigation-pages > span.item,
input[type=button],
input[type=submit],
input[type=reset]:hover,
.navigation-prev-next .navigation-prev a:before,
.navigation-prev-next .navigation-next a:after,
.navigation-prev-next .navigation-prev a:hover,
.navigation-prev-next .navigation-next a:hover,
.jp-volume-bar-value, .jp-play-bar,
.portfolio-thumb:hover .desc,
.button, a.button,
.dropcap.bgcolor-theme,
.marker,
.biginfopane,
.custom-table.style-3 th,
.custom-table-wrapper.style-3 table th,
.testimonials-block .controls a,
.pricing-column li.pricing-price,
.post-big .post-date,
.sort-menu li a.button.active
{
	background-color:<?php echo $hightlight_color ?>;
}

.footer input[type=button],
.footer input[type=submit],
.footer input[type=reset]:hover,
.footer .jp-volume-bar-value, .footer .jp-play-bar,
.footer .button, .footer a.button,
.footer .dropcap.bgcolor-theme,
.footer .marker,
.footer .biginfopane,
.footer .custom-table.style-3 th,
.footer .custom-table-wrapper.style-3 table th,
.footer .testimonials-block .controls a,
.footer .pricing-column li.pricing-price
{
	background-color:<?php echo $footer_hightlight_color ?>;
}

.tabs-control li a.active
{
	-webkit-box-shadow:inset 0 3px 0 0 <?php echo $hightlight_color ?>;
	-moz-box-shadow:inset 0 3px 0 0 <?php echo $hightlight_color ?>;
	box-shadow:inset 0 3px 0 0 <?php echo $hightlight_color ?>;
}

.footer .tabs-control li a.active
{
	-webkit-box-shadow:inset 0 3px 0 0 <?php echo $footer_hightlight_color ?>;
	-moz-box-shadow:inset 0 3px 0 0 <?php echo $footer_hightlight_color ?>;
	box-shadow:inset 0 3px 0 0 <?php echo $footer_hightlight_color ?>;
}

.pricing-column li.pricing-title,
.post-big .post-title-inner
{
	border-top-color:<?php echo $hightlight_color ?>;
}

.footer .pricing-column li.pricing-title
{
	border-top-color:<?php echo $footer_hightlight_color ?>;
}

.custom-gallery .controls .pager a:after
{
	border-right-color:<?php echo $hightlight_color ?>;
}

.post-widget-text .date,
.box-phone .days,
.breadcrumbs,
.post-categories,
.post-author,
.post-tags,
.post-full .post-date,
.post-small .post-date,
.post-comments,
.post-title-comment,
.post-title-link,
.comment .info .date,
.navigation-pages span.title,
.navigation-prev-next .navigation-prev a,
.navigation-prev-next .navigation-next a,
.content-block .content-title,
.latest-tweets .tweet-time a,
.portfolio-small-preview .tags,
.testimonials-block .item .name-desc,
.side-text,
.sort-menu li a.button .count
{
	color:<?php echo $side_text_color ?>;
}

.sort-menu li a.button
{
	background-color:<?php echo $side_text_color ?>;
}

.sidebar .widget-header
{
	background-color:<?php echo $background_widget_color ?>;
	color:<?php echo $widget_text_color ?>;
}

/* Footer */
.footer {
	color:<?php echo $footer_main_text_color ?>;
}

.sub-footer-divider {
	background-color:<?php echo $footer_main_text_color ?>;
}

.footer .post-widget-text .date,
.footer .box-phone .days,
.footer .content-block .content-title,
.footer .latest-tweets .tweet-time a,
.footer .portfolio-small-preview .tags,
.footer .testimonials-block .item .name-desc,
.sub-footer,
.footer .side-text
{
	color:<?php echo $footer_side_text_color ?>;
}