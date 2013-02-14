<?php

function om_breadcrumbs($caption='', $before='<div class="breadcrumbs">', $after='</div>', $separator=' / ') {
	global $post, $wp_query;
	
	$show_last=(get_option(OM_THEME_PREFIX . 'breadcrumbs_show_current') == 'true');
	
	$out=array();
	
	if( is_home() ) {
		
		if(is_front_page()) {
			
			// do nothing
			return;
			
		} else {
			$blog_page_id=get_option('page_for_posts');
			if($blog_page_id) {
				$blog = get_post($blog_page_id);
				if($show_last)
					$out[]=$blog->post_title;
				om_breadcrumbs_add_parents($out,$blog);
			}
		}
		
	}	elseif ( is_attachment() ) {
		
		if($show_last)
			$out[]=$post->post_title;
		om_breadcrumbs_add_parents($out,$post);
		
	} elseif( is_page() ) {

		if($show_last)
			$out[]=$post->post_title;
		om_breadcrumbs_add_parents($out,$post);

	} elseif( is_single() ) {

		if( $post->post_type == 'portfolio' ) {

			if($show_last)
				$out[]=$post->post_title;

			$portfolio_page = get_pages(array(
			    'meta_key' => '_wp_page_template',
			    'meta_value' => 'template-portfolio.php',
			    'number' => 1
			));
			if(empty($portfolio_page)) {
				$portfolio_page = get_pages(array(
				    'meta_key' => '_wp_page_template',
				    'meta_value' => 'template-portfolio-m.php',
				    'number' => 1
				));
			}
			if(!empty($portfolio_page)) {
				$out[]='<a href="'. get_permalink($portfolio_page[0]->ID) .'">'.$portfolio_page[0]->post_title.'</a>';
				om_breadcrumbs_add_parents($out,$portfolio_page[0]);
			}	
			
		} elseif( $post->post_type == 'testimonials' ) {

			if($show_last)
				$out[]=$post->post_title;

		} else {
			if($show_last)
				$out[]=$post->post_title;
	
			$blog_page_id=get_option('page_for_posts');
			if($blog_page_id) {
				$blog = get_post($blog_page_id);
				$out[]='<a href="'. get_permalink($blog->ID) .'">'.$blog->post_title.'</a>';
				om_breadcrumbs_add_parents($out,$blog);
			}
		}

	}	elseif( is_category() ||  is_tag() || is_day() || is_month() || is_year()) {

		if($show_last)
			$out[]=om_get_archive_page_title();

		$blog_page_id=get_option('page_for_posts');
		if($blog_page_id) {
			$blog = get_post($blog_page_id);
			$out[]='<a href="'. get_permalink($blog->ID) .'">'.$blog->post_title.'</a>';
			om_breadcrumbs_add_parents($out,$blog);
		}
		
	}	elseif( is_tax('portfolio-type') ) {
		
		if($show_last)
			$out[]=$wp_query->queried_object->name;

		$portfolio_page = get_pages(array(
		    'meta_key' => '_wp_page_template',
		    'meta_value' => 'template-portfolio.php',
		    'number' => 1
		));
		if(empty($portfolio_page)) {
			$portfolio_page = get_pages(array(
			    'meta_key' => '_wp_page_template',
			    'meta_value' => 'template-portfolio-m.php',
			    'number' => 1
			));
		}
		if(!empty($portfolio_page)) {
			$out[]='<a href="'. get_permalink($portfolio_page[0]->ID) .'">'.$portfolio_page[0]->post_title.'</a>';
			om_breadcrumbs_add_parents($out,$portfolio_page[0]);
		}		
	}
	
	//if(!empty($out)) {
		$out[]='<a href="'. home_url() .'">Home</a>';
		echo $before . $caption . implode( $separator, array_reverse($out) ) . (!$show_last ? $separator.'' : '') . $after;
	//}
}


function om_breadcrumbs_add_parents(&$out,$post) {

	if($post->post_parent) {
		$parent=$post->post_parent;
		while($parent) {
			$tmp=get_post($parent);
			if($tmp) {
				$out[]='<a href="'. get_permalink($tmp->ID) .'">'.$tmp->post_title.'</a>';
				$parent=$tmp->post_parent;
			} else {
				break;
			}
		}
	}

}