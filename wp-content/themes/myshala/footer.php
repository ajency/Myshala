		<?php
			$footer_left = get_option( OM_THEME_PREFIX.'footer_text_left' );
			
			$icons=array('twitter', 'facebook', 'linkedin', 'behance', 'rss', 'blogger', 'deviantart', 'dribble', 'flickr', 'lastfm', 'google', 'myspace', 'pinterest', 'skype', 'vimeo', 'youtube');
			$color=get_option(OM_THEME_PREFIX.'social_icons_color');
			if(!$color)
				$color='light';
			$icons_html='';
			foreach($icons as $v) {
				if( $icon = get_option(OM_THEME_PREFIX.'social_'.$v) )
					$icons_html.= '<a href="'.$icon.'" class="social color-'.$color.' '.$v.'"></a>';
			}

			$is_footer_sidebars = ( is_active_sidebar('footer-column-left') || is_active_sidebar('footer-column-center') || is_active_sidebar('footer-column-right')|| is_active_sidebar('footer-right') );
			$is_sub_footer = ($footer_left || $icons_html);
			if($is_footer_sidebars || $is_sub_footer) {
		?>
		
			<!-- Footer -->
			
			<div class="blocks-same-height-wrapper">
				<div class="blocks-same-height">
					
					<?php if ($is_footer_sidebars) { ?>
						<div  class="block-siderbar block-2 bg-color-main col-1">
							<div class="block-inner widgets-area">
								<?php get_sidebar('footer-column-left'); ?>
							</div>
						</div>
						
						<div  class="block-siderbar block-2 bg-color-main col-2">
							<div class="block-inner widgets-area">
								<?php get_sidebar('footer-column-center'); ?>
							</div>
						</div>
						
						<div  class="block-siderbar block-2 bg-color-main col-3">
							<div class="block-inner widgets-area">
								<?php get_sidebar('footer-column-right'); ?>
							</div>
						</div>
						
						<div class="block-siderbar block-2 bg-color-main col-4">
							<div class="block-inner widgets-area">
								<?php get_sidebar('footer-right'); ?>
							</div>
						</div>
			
						<div class="clear"></div>
					<?php } ?>
		
					<?php if($is_footer_sidebars && $is_sub_footer) { ?>
						<div class="block-full"><div class="block-inner" style="padding-top:0;padding-bottom:0"><!--<div class="sub-footer-divider"></div>--></div></div>
					<?php } ?>
						<div class="clear"></div>
					
		
					
				</div>
			</div>
			<?php if($is_sub_footer) { ?>
						<!-- SubFooter -->
						<div class="block-full sub-footer">
							<div class="block-inner">
								<div class="one-half sub-footer-column-1"><?php echo $footer_left ?></div>
								<div class="one-half last sub-footer-column-2"><a href="http://www.ajency.in" style="color:#555;">Website Design</a>: <a href="http://www.ajency.in" style="color:#555;">Ajency.in</a><?php echo $icons_html ?></div>
								<div class="clear"></div>
							</div>
						</div>
						
						<!-- /SubFooter -->
					<?php } ?>
			<!-- /Footer -->

		<?php } ?>
		<div class="navleft" id="navi">
		<ul id="navigation" >
				<li><a href="#important-links" title="Important Links" style="margin-left: -135px; ">Important Links</a></li>
				<li><a href="#blog" title="Blog" style="margin-left: -135px; ">Blog</a></li>
				<li><a href="#events" title="Events" style="margin-left: -135px; ">Events</a></li>
				<li><a href="#gallery" title="Gallery" style="margin-left: -135px; ">Gallery</a></li>
				<li id="look-here">&nbsp;</li>
			</ul>
</div>
	</div>
	
	<?php wp_footer(); ?>
	<script type="text/javascript">
	jQuery(function() {
		jQuery('#navigation a').stop().animate({'marginLeft':'-135px'},1000);

		jQuery('#navigation > li').hover(
			function () {
				jQuery('a',jQuery(this)).stop().animate({'marginLeft':'-2px'},200);
			},
			function () {
				jQuery('a',jQuery(this)).stop().animate({'marginLeft':'-135px'},200);
			}
		);
	});
</script>
<script type="text/javascript">
jQuery(window).scroll(function () {

        if (jQuery(window).scrollTop() < 600) {
            jQuery('#navigation').fadeIn('fast');
        } else {
            jQuery('#navigation').fadeOut('slow');
        }

});

jQuery(window).scroll();
</script>
<script type="text/javascript">
function scrollToDiv(element,navheight){
    var offset = element.offset();
    var offsetTop = offset.top;
    var totalScroll = offsetTop-navheight;
     
    jQuery('body,html').animate({
            scrollTop: totalScroll
    }, 500);
}

//Click Function
jQuery('#navigation li a').click(function(){
    var el = jQuery(this).attr('href');
    var elWrapped = jQuery(el);
     
    scrollToDiv(elWrapped,20);
     
    return false;
});
</script>
<script src="<?php echo get_stylesheet_directory_uri();?>/js/vscroller.js" type="text/javascript"></script>
<script type="text/javascript">	
/****News Scroller Init****/
jQuery(document).ready(function () {
	jQuery('#vscroller').vscroller({ newsfeed: '<?php echo get_stylesheet_directory_uri();?>/newsfeed.xml.php' });
});
</script>
	
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
	<!-- mousewheel plugin -->
	<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.mousewheel.min.js"></script>
	<!-- custom scrollbars plugin -->
	<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.mCustomScrollbar.js"></script>
	<script>
		jQuery(document).ready(function(){
			jQuery("#content_1").mCustomScrollbar({
				scrollButtons:{
					enable:true
				}
			});
		});
	</script>
	

	<?php echo get_option( OM_THEME_PREFIX . 'code_before_body' ) ?>
</div>	
</body>
</html>