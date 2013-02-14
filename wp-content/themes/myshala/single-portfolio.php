<?php get_header(); ?>

		<div class="block-full bg-color-main content-without-sidebar">
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
				
						<?php echo get_option(OM_THEME_PREFIX . 'code_after_portfolio_h1'); ?>
						
						<?php if( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						
							<!-- Portfolio Item -->
							<div class="portfolio-item">
								<div class="desc">
									<?php the_content(); ?>
	
									<?php the_terms($post->ID, 'portfolio-type', '<p><b>'.__('Categories:','om_theme').'</b> ', ', ', '</p>'); ?>
								</div>
								<div class="pic block-6 zero-mar">
									<div class="pic-inner move-right">
										<?php
											$type = get_post_meta($post->ID, OM_THEME_SHORT_PREFIX.'portfolio_type', true);
											if($type == 'image') {
												echo om_get_post_image($post->ID, 'page-full-2');
											} elseif($type == 'slideshow-m') {
												om_slides_gallery_m($post->ID);
											} elseif($type == 'slideshow') {
												om_slides_gallery($post->ID, 'page-full-2');
											} elseif($type == 'audio') {
												om_audio_player($post->ID, false);
											} elseif($type == 'video') {
												if($embed = get_post_meta($post->ID, OM_THEME_SHORT_PREFIX.'video_embed', true))
													echo '<div class="video-embed">'.$embed.'</div>';
												else
													om_video_player($post->ID, false);
											}
										?>
									</div>
								</div>
								<div class="clear"></div>
							</div>
							<!-- /Portfolio Item -->
							
						<?php endwhile; endif; ?>
						
						<?php echo get_option(OM_THEME_PREFIX . 'code_after_portfolio_content'); ?>
						
						<?php if( get_previous_post() || get_next_post() ) : ?>
						<div class="navigation-prev-next">
							<div class="navigation-prev"><?php previous_post_link('%link') ?></div>
							<div class="navigation-next"><?php next_post_link('%link') ?></div>
							<div class="clear"></div>
						</div>
						<?php endif; ?>

						<!-- /Content -->

							
			</div>
		</div>

		<?php
			$fb_comments=false;
			if(function_exists('om_facebook_comments') && get_option(OM_THEME_PREFIX . 'fb_comments_portfolio') == 'true') {
					if(get_option(OM_THEME_PREFIX . 'fb_comments_pages') == 'after')
						$fb_comments='after';
					else
						$fb_comments='before';
			}
		?>
		
		<?php if($fb_comments == 'before') { om_facebook_comments();	} ?>
				
		<?php if(get_option(OM_THEME_PREFIX . 'hide_comments_portfolio') != 'true') : ?>
			<?php comments_template('',true); ?>
		<?php endif; ?>
		
		<?php if($fb_comments == 'after') { om_facebook_comments();	} ?>
		
		<!-- /Content -->
		
		<div class="clear anti-mar">&nbsp;</div>

	<?php if(get_option(OM_THEME_PREFIX . 'portfolio_single_show_random') == 'true') { ?>
	
		<!-- Related portfolio items -->
		<div class="block-full bg-color-main">
			<div class="block-inner">
						
						<div class="widget-header"><?php _e('Random Items','om_theme') ?></div>
			</div>
		</div>
		
		<div class="clear anti-mar">&nbsp;</div>
		
		<div class="portfolio-wrapper">
			<?php 
			$query = new WP_Query( array (
				'post_type' => 'portfolio',
				'orderby' => 'rand',
				'posts_per_page' => 3
			));
			
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
			
			<div class="clear"></div>
		</div>
		<!-- /Related portfolio items -->
		
		<div class="clear anti-mar">&nbsp;</div>
				
	<?php } ?>
<?php get_footer(); ?>