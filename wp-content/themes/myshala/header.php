<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>


	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?php
    if (!defined('WPSEO_VERSION')) {
        wp_title('|', true, 'right');
        bloginfo('name');
    }
    else {
        //IF WordPress SEO by Yoast is activated
        wp_title('');
    }?></title>


	<!-- Pingbacks -->
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	
	<!-- Google Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Schoolbell' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/jquery.mCustomScrollbar.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/custom.css.php" type="text/css" />
	<?php if(get_option(OM_THEME_PREFIX . 'responsive') == 'true') : ?>
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/responsive.css" type="text/css" />
	<?php endif; ?>
	<!--[if IE 8]>
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie8.css" type="text/css" />
	<![endif]-->
	<!--[if lt IE 8]>
		<style>body{background:#fff;font:18px/24px Arial} .bg-overlay{display:none} .chromeframe {margin:40px;text-align:center} .chromeframe a{color:#0c5800;text-decoration:underline}
		
		</style>
	<![endif]-->
	
	
	
	
	<?php
		$custom_css=get_option(OM_THEME_PREFIX . 'code_custom_css');
		if($custom_css)
			echo '<style>'.$custom_css.'</style>';
	?>
	
	<?php echo get_option( OM_THEME_PREFIX . 'code_before_head' ) ?>
	
	<?php wp_head(); ?>
	<?php if (is_page('eligibility') ) { ?>
<!--home page custom JS-->
	
	
    <script type="text/javascript">
<!--
  function validateR(){
	var acadyr=(comboForm.acadyr.value);
	var day=(comboForm.day.value);
	var mth=(comboForm.month.value);
	var yr=(comboForm.year.value);
	if (acadyr=="" || day=="" || mth=="" || yr=="") {
		alert("Please check entered values");
		return false;
	}
	//alert("day:" + day + " month: " + mth + " year: " + yr);
	dt = new Date(yr, mth, day);
	//alert("dt: "+dt);
	var nurS;	var nurE;
	var jrS;	var jrE;
	var srS;	var srE;
	var firstS;	var firstE;
	var secondS;	var secondE;
	var thirdS;	var thirdE;
	var fourthS;	var fourthE;
	var fifthS;	var fifthE;
	var sixthS;	var sixthE;
	var seventhS;	var seventhE;
	var eighthS;	var eighthE;
	var ninthS;	var ninthE;
	var tenthS;	var tenthE;


	switch(acadyr)
	{
		case "2013-14":
			nurS= new Date(2010,0,1);	nurE= new Date(2010,11,31);
			jrS= new Date(2009,0,1);	jrE= new Date(2009,11,31);
			srS= new Date(2008,0,1);	srE= new Date(2008,11,31);
			firstS= new Date(2007,0,1);	firstE= new Date(2007,11,31);
			secondS= new Date(2006,0,1);	
			break;
		
		case "2014-15":
			nurS= new Date(2011,0,1);	nurE= new Date(2011,11,31);
			jrS= new Date(2010,0,1);	jrE= new Date(2010,11,31);
			srS= new Date(2009,0,1);	srE= new Date(2009,11,31);
			firstS= new Date(2008,0,1);	firstE= new Date(2008,11,31);
			secondS= new Date(2007,0,1);	
			break;

		default:
			alert("default");
	}
	//alert("dt: "+dt +" nurS: "+nurS+" nurE: "+nurE+" jrS: "+jrS+" jrE: "+jrE+" srS: "+srS+" srE: "+srE+" firstS: "+firstS+" firstE: "+firstE+" secondS: "+secondS);
	if (dt>=nurS && dt <=nurE)
	{
		alert("Nursery");
	}
	else if (dt>=jrS && dt <=jrE)
	{
		alert("Junior KG");
	}
	else if (dt>=srS && dt <=srE)
	{
		alert("Senior KG");
	}
	else if (dt>=firstS && dt <=firstE)
	{
		alert("First");
	}
	else if (dt>nurE)
	{
		//yrs=dt.getFullYear() - nurE.getFullYear();
		alert("Your child seems to be underage! Enjoy this time with your child - school will come later!");
	}

	return false;
  }
--></script>
<?php } ?>
	
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/source/PopUp.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/source/skins/default/default.css"></link>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/source/skins/metro/metro.css"></link>
	
	<script type="text/javascript">
	/*$(functtion () {
	       $(this).speedoPopup({htmlContent: "<p> I'm a simple content </p>"});
	    });*/
</script>
	
	
	
</head>
<?php
	$body_class='';
	if(get_option(OM_THEME_PREFIX.'sidebar_position')=='left')
		$body_class='flip-sidebar';
	if(@$post) {
		$sidebar_post=get_post_meta($post->ID, OM_THEME_SHORT_PREFIX.'sidebar_custom_pos', true);
		if($sidebar_post == 'left')
			$body_class='flip-sidebar';
		elseif($sidebar_post == 'right')
			$body_class='';
	}
?>
<body <?php body_class( $body_class ) ?>>
<!--[if lt IE 8]><p class="chromeframe"><?php _e('Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.','om_theme'); ?></p><![endif]-->
<div class="bg-overlay">

	<div class="container">
		
		<!-- Headline -->
		<div class="headline block-full">
			<div class="headline-text">
				<?php echo get_option(OM_THEME_PREFIX . 'intro_text') ?>
			</div>
		</div>
		<!-- /Headline -->
	
		<!-- Logo & Menu -->
		
		<div class="logo-back logo-pane block-3 block-h-1 bg-color-menu" style="background:none;">
			<div class="logo-pane-inner">

				<?php
				if(get_option(OM_THEME_PREFIX . 'site_logo_type') == 'text') {
					echo '<div class="logo-text"><a href="' . home_url() .'">'. get_option(OM_THEME_PREFIX . 'site_logo_text') .'</a></div>';
				} else {
					if( $tmp=get_option(OM_THEME_PREFIX . 'site_logo_image') )
						echo '<div class="logo-image"><a href="' . home_url() .'"><img src="'.$tmp.'" alt="'.htmlspecialchars( get_bloginfo( 'name' ) ).'" /></a></div>';
				}
				?>
			</div>
		</div>
		
		<?php
			if ( has_nav_menu( 'primary-menu' ) ) {

				function om_nav_menu_classes ($items) {

					function hasSub ($menu_item_id, &$items) {
		        foreach ($items as $item) {
	            if ($item->menu_item_parent && $item->menu_item_parent==$menu_item_id) {
	              return true;
	            }
		        }
		        return false;
					};					
					
					$menu_root_num=0;
					foreach($items as $item) {
						if(!$item->menu_item_parent)
							$menu_root_num++;
							
						if (hasSub($item->ID, $items)) {
							$item->classes[] = 'menu-parent-item';
						}
					}
					if($menu_root_num < 7)
						$size_class='block-h-1';
					else
						$size_class='block-h-half';
					
					$custom_colors = array('menu-1','menu-2','menu-3','menu-4','menu-5','menu-6');		
					$count_colors = count($custom_colors);
					$i = 0;	
					foreach ($items as &$item) {
						if($item->menu_item_parent)
							continue;
						if($i > $count_colors)
							$i = 0;
						$item->classes[] = 'block-1';
						$item->classes[] = $size_class;
						$item->classes[] = $custom_colors[$i];
						
						$i++;
					}
					return $items;    
				}
				add_filter('wp_nav_menu_objects', 'om_nav_menu_classes');	

				$menu = wp_nav_menu( array(
					'theme_location' => 'primary-menu',
					'container' => false,
					'echo' => false,
					'link_before'=>'<span>',
					'link_after'=>'</span>',
					'items_wrap' => '%3$s'
				) );
				
				remove_filter('wp_nav_menu_objects', 'om_nav_menu_classes');	
				
				$root_num=preg_match_all('/class="[^"]*block-1[^"]*"/', $menu, $m);
				echo '<ul class="primary-menu block-6 no-mar">'.$menu;
				$blank_num=0;
				$blank_str='';
				if($root_num < 7) {
					$blank_num=6-$root_num;
					$blank_str='<li class="block-1 block-h-1 blank">&nbsp;</li>';
				} elseif($root_num < 13) {
					$blank_num=12-$root_num;
					$blank_str='<li class="block-1 block-h-half blank">&nbsp;</li>';
				}
				echo str_repeat($blank_str,$blank_num);
				echo '</ul>';

		
				echo '<div class="primary-menu-select bg-color-menu">';
				om_select_menu( 'primary-menu' );
				echo '</div>';
			}
		?>
		<div class="clear"></div>
		
		<!-- /Logo & Menu -->
