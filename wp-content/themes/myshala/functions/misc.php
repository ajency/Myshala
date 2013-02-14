<?php

/*************************************************************************************
 *	Favicon
 *************************************************************************************/

if ( !function_exists( 'om_favicon' ) ) {
	function om_favicon() {
		if ($tmp = get_option(OM_THEME_PREFIX . 'favicon')) {
			echo '<link rel="shortcut icon" href="'. $tmp .'"/>';
		}
	}
	add_action('wp_head', 'om_favicon');
}

/*************************************************************************************
 *	Audio Player
 *************************************************************************************/

if ( !function_exists( 'om_audio_player' ) ) {
	function om_audio_player($post_id, $trysize='456x300') {
	 
		$mp3 = get_post_meta($post_id, OM_THEME_SHORT_PREFIX.'audio_mp3', true);
		$ogg = get_post_meta($post_id, OM_THEME_SHORT_PREFIX.'audio_ogg', true);
		$poster = get_post_meta($post_id, OM_THEME_SHORT_PREFIX.'audio_poster', true);
		if($poster && $trysize) {
			$uploads = wp_upload_dir();
			
			if(strpos($poster,$uploads['baseurl']) === 0) {
				$name=basename($poster);
				$name_new=explode('.',$name);
				if(count($name_new > 1))
					$name_new[count($name_new)-2].='-'.$trysize;
				$name_new=implode('.',$name_new);
				$poster_new=str_replace($name,$name_new,$poster);
				if(file_exists(str_replace($uploads['baseurl'],$uploads['basedir'],$poster_new)))
					$poster=$poster_new;
			}
		} elseif(!$poster && $trysize=='456x300') {
			$poster=TEMPLATE_DIR_URI.'/img/audio.png';
		}
		
		$supplied=array();
		if($mp3)
			$supplied[]='mp3';
		if($ogg)
			$supplied[]='ogg';
		if(empty($supplied))
			return;
		$supplied=implode(',',$supplied);	
		
		$setmedia=array();
		if($poster)
			$setmedia[]='poster: "'.$poster.'"';
		if($mp3)
			$setmedia[]='mp3: "'.$mp3.'"';
		if($ogg)
			$setmedia[]='oga: "'.$ogg.'"';
		$setmedia=implode(',',$setmedia);
		
    ?>
		<script type="text/javascript">
			jQuery(document).ready(function(){

				if(jQuery().jPlayer) {
					jQuery("#jquery_jplayer_<?php echo $post_id; ?>").jPlayer({
						ready: function () {
							jQuery(this).jPlayer("setMedia", {
						    <?php echo $setmedia; ?>
							});
						},
						<?php if( !empty($poster) ) { ?>
							size: {
        				width: "auto",
        				height: "auto"
        			},
     				<?php } ?>
						swfPath: "<?php echo get_template_directory_uri(); ?>/js",
						cssSelectorAncestor: "#jp_container_<?php echo $post_id; ?>",
						supplied: "<?php echo $supplied ?>"
					});
			
				}
			});
		</script>

		<div id="jquery_jplayer_<?php echo $post_id; ?>" class="jp-jplayer"></div>

		<div id="jp_container_<?php echo $post_id; ?>" class="jp-container jp-audio">
			<div class="jp-type-single">
				<div class="jp-gui jp-interface">
					<div class="jp-controls">
						<a href="javascript:;" class="jp-play" tabindex="1" title="<?php _e('Play', 'om_theme') ?>"></a>
						<a href="javascript:;" class="jp-pause" tabindex="1" title="<?php _e('Pause', 'om_theme') ?>"></a>
						<div class="jp-play-pause-divider"></div>
						<div class="jp-mute-unmute-divider"></div>
						<a href="javascript:;" class="jp-mute" tabindex="1" title="<?php _e('Mute', 'om_theme') ?>"></a>
						<a href="javascript:;" class="jp-unmute" tabindex="1" title="<?php _e('Unmute', 'om_theme') ?>"></a>
					</div>
					<div class="jp-progress">
						<div class="jp-seek-bar">
							<div class="jp-play-bar"></div>
						</div>
					</div>
					<div class="jp-volume-bar">
						<div class="jp-volume-bar-value"></div>
					</div>
				</div>
				<div class="jp-no-solution">
					<span>Update Required</span>
					To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
				</div>
			</div>
		</div>

    <?php 
  }
}

/*************************************************************************************
 *	Video Player
 *************************************************************************************/

if ( !function_exists( 'om_video_player' ) ) {
	function om_video_player($post_id, $trysize='456x300') {
	 
		$m4v = get_post_meta($post_id, OM_THEME_SHORT_PREFIX.'video_m4v', true);
		$ogv = get_post_meta($post_id, OM_THEME_SHORT_PREFIX.'video_ogv', true);
		$poster = get_post_meta($post_id, OM_THEME_SHORT_PREFIX.'video_poster', true);

		if($poster && $trysize) {
			$uploads = wp_upload_dir();
			
			if(strpos($poster,$uploads['baseurl']) === 0) {
				$name=basename($poster);
				$name_new=explode('.',$name);
				if(count($name_new > 1))
					$name_new[count($name_new)-2].='-'.$trysize;
				$name_new=implode('.',$name_new);
				$poster_new=str_replace($name,$name_new,$poster);
				if(file_exists(str_replace($uploads['baseurl'],$uploads['basedir'],$poster_new)))
					$poster=$poster_new;
			}
		} elseif(!$poster && $trysize=='456x300') {
			$poster=TEMPLATE_DIR_URI.'/img/video.png';
		}
		
		$supplied=array();
		if($m4v)
			$supplied[]='m4v';
		if($ogv)
			$supplied[]='ogv';
		if(empty($supplied))
			return;
		$supplied=implode(',',$supplied);	
		
		$setmedia=array();
		if($poster)
			$setmedia[]='poster: "'.$poster.'"';
		if($m4v)
			$setmedia[]='m4v: "'.$m4v.'"';
		if($ogv)
			$setmedia[]='ogv: "'.$ogv.'"';
		$setmedia=implode(',',$setmedia);
		
    ?>
		<script type="text/javascript">
			jQuery(document).ready(function(){

				if(jQuery().jPlayer) {
					jQuery("#jquery_jplayer_<?php echo $post_id; ?>").jPlayer({
						ready: function () {
							jQuery(this).jPlayer("setMedia", {
						    <?php echo $setmedia; ?>
							});
						},
						<?php if( !empty($poster) ) { ?>
							size: {
        				width: "auto",
        				height: "auto"
        			},
     				<?php } ?>
						swfPath: "<?php echo get_template_directory_uri(); ?>/js",
						cssSelectorAncestor: "#jp_container_<?php echo $post_id; ?>",
						supplied: "<?php echo $supplied ?>"
					});
			
				}
			});
		</script>

		<div class="video-embed"><div id="jquery_jplayer_<?php echo $post_id; ?>" class="jp-jplayer"></div></div>

		<div id="jp_container_<?php echo $post_id; ?>" class="jp-container jp-video">
			<div class="jp-type-single">
				<div class="jp-gui jp-interface">
					<div class="jp-controls">
						<a href="javascript:;" class="jp-play" tabindex="1" title="<?php _e('Play', 'om_theme') ?>"></a>
						<a href="javascript:;" class="jp-pause" tabindex="1" title="<?php _e('Pause', 'om_theme') ?>"></a>
						<div class="jp-play-pause-divider"></div>
						<div class="jp-mute-unmute-divider"></div>
						<a href="javascript:;" class="jp-mute" tabindex="1" title="<?php _e('Mute', 'om_theme') ?>"></a>
						<a href="javascript:;" class="jp-unmute" tabindex="1" title="<?php _e('Unmute', 'om_theme') ?>"></a>
					</div>
					<div class="jp-progress">
						<div class="jp-seek-bar">
							<div class="jp-play-bar"></div>
						</div>
					</div>
					<div class="jp-volume-bar">
						<div class="jp-volume-bar-value"></div>
					</div>
				</div>
				<div class="jp-no-solution">
					<span>Update Required</span>
					To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
				</div>
			</div>
		</div>

    <?php 
  }
}

/*************************************************************************************
 * Slides Gallery
 *************************************************************************************/

function om_slides_gallery($post_id, $image_size = 'page-full-2') { 
	
	echo om_get_slides_gallery($post_id, $image_size);
	
}

function om_get_slides_gallery($post_id, $image_size = 'page-full-2') { 

	$attachments = get_posts(array(
		'orderby' => 'menu_order',
		'post_type' => 'attachment',
		'post_parent' => $post_id,
		'post_mime_type' => 'image',
		'post_status' => null,
		'numberposts' => -1
	));
	
	/*
	$thumbid = false;
	if( has_post_thumbnail($post_id) ) {
		$thumbid = get_post_thumbnail_id($post_id);
	}
	*/
	
	$out = '';
	
	if( !empty($attachments) ) {
		$out .= '<div class="custom-gallery"><div class="items">';
		foreach( $attachments as $attachment ) {
			//if( $attachment->ID == $thumbid )
			//	continue;
	    $src = wp_get_attachment_image_src( $attachment->ID, $image_size );
	    $src_full = wp_get_attachment_image_src( $attachment->ID, 'full' );
	    $alt = ( empty($attachment->post_content) ) ? $attachment->post_title : $attachment->post_content;
	    $out .= '<div class="item" rel="slide-'.$attachment->ID.'"><a href="'.$src_full[0].'" rel="prettyPhoto[postgal_'.$post_id.']"><img height="'.$src[2].'" width="'.$src[1].'" src="'.$src[0].'" alt="'.htmlspecialchars($alt).'" /></a></div>';
		}
		$out .= '</div></div>';
	}
	
	return $out;
}


/*************************************************************************************
 * Slides Gallery Masonry
 *************************************************************************************/

function om_slides_gallery_m($post_id, $image_size = 'portfolio-q-thumb') {
	
	echo om_get_slides_gallery_m($post_id, $image_size);
	
}

function om_get_slides_gallery_m($post_id, $image_size = 'portfolio-q-thumb') { 

	$attachments = get_posts(array(
		'orderby' => 'menu_order',
		'post_type' => 'attachment',
		'post_parent' => $post_id,
		'post_mime_type' => 'image',
		'post_status' => null,
		'numberposts' => -1
	));
	
	$out = '';
	
	if( !empty($attachments) ) {
		$out .= '<div class="thumbs-masonry isotope">';
		$sizes=array();
		$n=count($attachments);
		if($n <= 3) {
			for($i=0;$i<$n;$i++)
				$sizes[]=2;
		} elseif ($n >=4 && $n <= 6) {
			$sizes[]=2;
			$sizes[]=2;
			for($i=0;$i<$n-2;$i++)
				$sizes[]=1;
		} else {
			for($i=0;$i<$n;$i++)
				$sizes[]=(($i%3)==0?'2':'1');
		}
		$i=0;
		foreach( $attachments as $attachment ) {
	    $src = wp_get_attachment_image_src( $attachment->ID, $image_size );
	    $src_full = wp_get_attachment_image_src( $attachment->ID, 'full' );
	    $alt = ( empty($attachment->post_content) ) ? $attachment->post_title : $attachment->post_content;
	    $out .= '<div class="isotope-item block-'.$sizes[$i].' block-h-'.$sizes[$i].'"><a href="'.$src_full[0].'" rel="prettyPhoto[postgal_'.$post_id.']" class="show-hover-link"><img src="'.$src[0].'" alt="'.htmlspecialchars($alt).'"/><span class="before"></span><span class="after"></span></a></div>';
	    
	    $i++;
		}
		$out .= '</div>';
	}
	
	return $out;
}


/*************************************************************************************
 * Get Post Image
 *************************************************************************************/

if ( !function_exists( 'om_get_post_image' ) ) {
	function om_get_post_image($post_id, $image_size = 'thumbnail-post-single') { 
	
		$attachments = get_posts(array(
			'orderby' => 'menu_order',
			'post_type' => 'attachment',
			'post_parent' => $post_id,
			'post_mime_type' => 'image',
			'post_status' => null,
			'numberposts' => 1
		));
		
		/*
		$thumbid = false;
		if( has_post_thumbnail($post_id) ) {
			$thumbid = get_post_thumbnail_id($post_id);
		}
		*/
		
		if( !empty($attachments) ) {
			foreach( $attachments as $attachment ) {
				//if( $attachment->ID == $thumbid )
				//	continue;
		    $src = wp_get_attachment_image_src( $attachment->ID, $image_size );
		    $alt = ( empty($attachment->post_content) ) ? $attachment->post_title : $attachment->post_content;
		    return '<img height="'.$src[2].'" width="'.$src[1].'" src="'.$src[0].'" alt="'.htmlspecialchars($alt).'" />';
			}
		}
		
		return false;
	}
}

/*************************************************************************************
 * Select menu
 *************************************************************************************/
 
function om_select_menu($id, $select_id='primary-menu-select') {
	$out='';
	$out.='<select id="'.$select_id.'" onchange="if(this.value!=\'\'){document.location.href=this.value}"><option value="">'.__('Menu:','om_theme').'</option>';
	
 	if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $id ] ) ) {
 		
		$menu = wp_get_nav_menu_object( $locations[ $id ] );
	
		$sel_menu=wp_get_nav_menu_items($menu->term_id);

		if(is_array($sel_menu)) {
			
			$items=array();
		
			foreach($sel_menu as $item)
				$items[$item->ID]=array('parent'=>$item->menu_item_parent);
				
			foreach($items as $k=>$v) {
				$items[$k]['depth']=0;
				if($v['parent']) {
					$tmp=$v;
					while($tmp['parent']) {
						$items[$k]['depth']++;
						$tmp=$items[$tmp['parent']];
					}
				}
			}
			foreach($sel_menu as $item)
				$out.= '<option value="'.($item->url).'"'.((strcasecmp('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],$item->url)==0)?' selected="selected"':'').'>'.str_repeat('- ',$items[$item->ID]['depth']).($item->title).'</option>';
		}
	}
	
	$out.= '</select>';
	
	echo $out;
	
	return true;
}

/*************************************************************************************
 * Archive Page Title
 *************************************************************************************/
 
function om_get_archive_page_title() {
	
	$out='';
	
	if (is_category()) { 
		$out = sprintf(__('All posts in %s', 'om_theme'), single_cat_title('',false));
	} elseif( is_tag() ) {
		$out = sprintf(__('All posts tagged %s', 'om_theme'), single_tag_title('',false));
	} elseif (is_day()) { 
		$out = __('Archive for', 'om_theme'); $out .= ' '.get_the_time('F jS, Y'); 
	} elseif (is_month()) { 
		$out = __('Archive for', 'om_theme'); $out .= ' '.get_the_time('F, Y'); 
	} elseif (is_year()) { 
		$out = __('Archive for', 'om_theme'); $out .= ' '.get_the_time('Y');
	} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
		$out = __('Blog Archives', 'om_theme');
	} else { 
		$blog = get_post(get_option('page_for_posts'));
		$out = $blog->post_title;
	}
 	
 	return $out;
}