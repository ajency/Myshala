<?php

function om_facebook_ids_add() {

	$admin_id=get_option(OM_THEME_PREFIX . 'fb_comments_admin_id');
	$app_id=get_option(OM_THEME_PREFIX . 'fb_comments_admin_id');


	if($admin_id)
		echo '<meta property="fb:admins" content="'.$admin_id.'"/>';

	if($app_id)
		echo '<meta property="fb:app_id" content="'.$app_id.'"/>';

}

add_action('wp_head', 'om_facebook_ids_add');


function om_facebook_comments() {
	
	$count=get_option( OM_THEME_PREFIX . 'fb_comments_count' );
	$color=get_option( OM_THEME_PREFIX . 'fb_comments_color' );

	$count=intval($count);
	if(!$count)
		$count=2;
		
	if($color == 'dark')
		$color=' data-colorscheme="dark"';
	else
		$color='';
		
?>
			<div class="clear anti-mar">&nbsp;</div>
			
			<!-- FB Comments -->
			<div class="block-full bg-color-main">
				<div class="block-inner">
					<div class="widget-header"><?php _e('Comments', 'om_theme') ?></div>

					<div id="fb-root"></div>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));</script>

					<div class="fb-comments" data-href="<?php the_permalink() ?>" data-num-posts="<?php echo $count; ?>" data-width="300"<?php echo $color; ?>></div>
					
				</div>
			</div>			
			<!-- /FB Comments -->		
<?php	
}