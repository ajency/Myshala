<?php

get_header();

 ?>

		<div class="block-full bg-color-main">
			<div class="block-inner">

				<div class="tbl-bottom">
					<div class="tbl-td">
						<h1 class="page-h1"><?php echo $wp_query->queried_object->name ?></h1>
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
					<ul class="sort-menu">
						<?php
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
								$portfolio_count=wp_count_posts( 'portfolio');
								?><li><a href="<?php echo get_permalink($portfolio_page[0]->ID)?>" class="button single-color"><?php _e('All', 'om_theme'); ?><span class="count"><?php echo $portfolio_count->publish ?></span></a></li><?php
							}
						?>
						<?php
							foreach($categories as $category) {
								if(!$category->count)
									continue;
								echo '<li><a href="'.get_term_link($category, 'portfolio-type').'" class="button single-color'.($wp_query->queried_object->term_taxonomy_id==$category->term_taxonomy_id?' active':'').'">'.$category->name.'<span class="count">'.$category->count.'</span></a></li>';
							}
						?>
					</ul>
					<div class="clear"></div>
					<!-- /Categories -->
				<?php } ?>
			</div>
		</div>
		
		<div class="clear anti-mar">&nbsp;</div>
		
		<div class="portfolio-wrapper">
			<!-- Portfolio items -->
			
				<?php
				$arg=array (
					'post_type' => 'portfolio',
					'orderby' => 'menu_order',
					'order' => 'ASC',
					'posts_per_page' => -1,
					'portfolio-type' => $wp_query->queried_object->slug
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
				
				while ( $query->have_posts() ) : $query->the_post(); ?>
				
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
				
					<div <?php post_class('portfolio-thumb bg-color-main isotope-item block-3 show-hover-link '.$term_list); ?> id="post-<?php the_ID(); ?>">
						<div class="pic block-h-2">
							<?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) { ?>
							<?php the_post_thumbnail('portfolio-thumb'); ?>
							<?php } else { echo '&nbsp'; } ?>
						</div>
						<div class="desc block-h-1">
							<div class="title"><?php the_title(); ?></div>
							<div class="tags"><?php the_terms($post->ID, 'portfolio-type', '', ', ', ''); ?></div>
						</div>
						<a href="<?php the_permalink(); ?>" class="link"><span class="after"></span></a>
					</div>
				<?php endwhile; ?>
				
				<?php wp_reset_postdata(); ?>

			<!-- /Portfolio items -->
			<div class="clear"></div>
		</div>									

		<div class="clear anti-mar">&nbsp;</div>
		
		
<?php get_footer(); ?>