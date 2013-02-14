<?php
/*
Template Name: Portfolio Masonry
*/

get_header(); ?>

<?php

	$arg=array (
		'post_type' => 'portfolio',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'posts_per_page' => -1
	);
	
	$sort=get_option(OM_THEME_PREFIX . 'portfolio_sort');
	if($sort == 'date_asc') {
		$arg['orderby'] = 'date';
		$arg['order'] = 'ASC';
	} elseif($sort == 'date_desc') {
		$arg['orderby'] = 'date';
		$arg['order'] = 'DESC';
	}
	
	$query = new WP_Query($arg);
	
?>

		<div class="block-full bg-color-main">
			<div class="block-inner">
				<?php
          if ( current_user_can( 'edit_post', $post->ID ) )
      	    edit_post_link( __('edit', 'om_theme'), '<div class="edit-post-link">[', ']</div>' );
    		?>
				<div class="tbl-bottom">
					<div class="tbl-td">
						<h1 class="page-h1"><?php the_title(); ?></h1>
					</div>
					<?php if(get_option(OM_THEME_PREFIX . 'show_breadcrumbs') == 'true') { ?>
						<div class="tbl-td">
							<?php om_breadcrumbs(get_option(OM_THEME_PREFIX . 'breadcrumbs_caption')) ?>
						</div>
					<?php } ?>
				</div>
				<div class="clear page-h1-divider"></div>

				<?php
				$categories=get_categories( array (
					'type' => 'portfolio',
					'taxonomy' => 'portfolio-type'
				));
				if(!empty($categories)) {
				?>
					<!-- Categories -->
					<ul class="sort-menu isotope-sort-menu">
						<li><a href="#portfolio-thumb" class="button single-color active"><?php _e('All', 'om_theme'); ?><span class="count"><?php echo $query->post_count ?></span></a></li>
						<?php
							foreach($categories as $category) {
								if(!$category->count)
									continue;
								echo '<li><a href="'.get_term_link($category, 'portfolio-type').'#'.$category->slug.'" class="button single-color">'.$category->name.'<span class="count">'.$category->count.'</span></a></li>';
							}
						?>
					</ul>
					<div class="clear"></div>
					<!-- /Categories -->
				<?php } ?>
			</div>
		</div>
		
		<div class="clear anti-mar">&nbsp;</div>

		<div class="portfolio-wrapper isotope isotope-masonry" id="portfolio-wrapper">
			<!-- Portfolio items -->
			
			<?php
			
			while ( $query->have_posts() ) : $query->the_post(); ?>

				<?php
					$size = get_post_meta($post->ID, OM_THEME_SHORT_PREFIX.'portfolio_size', true);
					$size=intval($size);
					if(!$size)
						$size=1;
					elseif($size > 3)
						$size=3;
				?>

				<?php
				$terms =  get_the_terms( $post->ID, 'portfolio-type' ); 
				$term_list = array();
				if( is_array($terms) ) {
					foreach( $terms as $term ) {
						$term_list[]=urldecode($term->slug);
					}
				}
				$term_list=implode(' ',$term_list);
				?>
				
				<div <?php post_class('portfolio-thumb bg-color-alt isotope-item block-'.$size.' '.$term_list); ?> id="post-<?php the_ID(); ?>">
					<div class="pic block-h-<?php echo $size ?>">
						<?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) { ?>
						<?php the_post_thumbnail('portfolio-q-thumb'); ?>
						<?php } else { echo '&nbsp'; } ?>
					</div>
					<a href="<?php the_permalink(); ?>" class="link show-hover-link"><span class="after"></span></a>
				</div>
			<?php endwhile; ?>
			
			<?php wp_reset_postdata(); ?>

			<!-- /Portfolio items -->
			<div class="clear"></div>
		</div>									

		<div class="clear anti-mar">&nbsp;</div>
				
<?php get_footer(); ?>