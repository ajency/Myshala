<?php
/*
Template Name: Home Page
*/

get_header(); ?>

	<?php get_template_part('includes/homepage-slider'); ?>
	
		<!-- Content -->
		<div class="homepage-blocks">
			
					<?php
					$arg=array (
						'post_type' => 'homepage',
						'orderby' => 'menu_order',
						'order' => 'ASC',
						'posts_per_page' => -1
					);
					
					$query = new WP_Query($arg);
					
					$size_counter=0;
					$same_height_opened=false;
					while ( $query->have_posts() ) : $query->the_post(); ?>
						<?php
							$size=get_post_meta($post->ID, OM_THEME_SHORT_PREFIX.'homepage_size', true);
							$size=intval($size);
							if(!$size)
								$size=9;

							if($size == 9 && $same_height_opened) {
								echo '</div></div><div class="clear anti-mar">&nbsp;</div>';
								$same_height_opened=false;
							}
							if($size < 9 && !$same_height_opened) {
								echo '<div class="blocks-same-height-wrapper"><div class="blocks-same-height">';
								$same_height_opened=true;
								$size_counter=0;
							}

							$size_counter+=$size;
							if($size_counter > 9 && $same_height_opened) {
								echo '</div></div><div class="clear anti-mar">&nbsp;</div><div class="blocks-same-height-wrapper"><div class="blocks-same-height">';
								$size_counter=$size;
							}
							
						
						?>
						<div class="block-<?php echo ($size==9?'full':$size) ?> bg-color-main bg-background">
							<div class="block-inner">
								<?php the_content(); ?>
							</div>
						</div>
						<?php if($size==9) echo '<div class="clear anti-mar">&nbsp;</div>'; ?>
						
					<?php endwhile; ?>
					
					<?php wp_reset_postdata(); ?>
					
					<?php if($same_height_opened) echo '</div></div><div class="clear anti-mar">&nbsp;</div>'; ?>
		
		</div>
		<!-- /Content -->
		
<?php get_footer(); ?>