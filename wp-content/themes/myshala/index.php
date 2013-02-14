<?php

$blog_page_id=get_option('page_for_posts');
if($blog_page_id)
	$blog = get_post($blog_page_id);
else
	$blog=false;

if($blog) {
	$template_name = get_post_meta( $blog->ID, '_wp_page_template', true );
	if($template_name == 'template-blog-lite.php') {
		get_template_part('template-blog-lite');
		return;
	}
}

get_header();
?>


		<div class="block-6 no-mar content-with-sidebar">
			
			<div class="block-6 bg-color-main">
				<div class="block-inner">
					<?php if($blog) { ?>
						<div class="tbl-bottom">
							<div class="tbl-td">
								<h1 class="page-h1"><?php echo $blog->post_title; ?></h1>
							</div>
							<?php if(get_option(OM_THEME_PREFIX . 'show_breadcrumbs') == 'true') { ?>
								<div class="tbl-td">
									<?php om_breadcrumbs(get_option(OM_THEME_PREFIX . 'breadcrumbs_caption')) ?>
								</div>
							<?php } ?>
						</div>
						<div class="clear page-h1-divider"></div>
					<?php } ?>
	      		
	      		<?php //$i=0; ?>
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							
						    <?php 
									//if( $i > 0 )
									//	echo '<hr />';

									$format = get_post_format(); 
									if( false === $format )
										$format = 'standard';
									get_template_part( 'includes/post-type-' . $format );
									
									$i++;
						    ?>
	
							
							<?php endwhile; ?>
		
							<?php
								$nav_prev=get_previous_posts_link(__('Newer Entries', 'om_theme'));
								$nav_next=get_next_posts_link(__('Older Entries', 'om_theme'));
								if( $nav_prev || $nav_next ) {
									?>
									<div class="navigation-prev-next">
										<?php if($nav_prev){?><div class="navigation-prev"><?php echo $nav_prev; ?></div><?php } ?>
										<?php if($nav_next){?><div class="navigation-next"><?php echo $nav_next; ?></div><?php } ?>
										<div class="clear"></div>
									</div>
									<?php
								}		
							?>
			
						<?php else : ?>
			
							<h2><?php _e('Error 404 - Not Found', 'om_theme') ?></h2>
						
							<p><?php _e('Sorry, but you are looking for something that isn\'t here.', 'om_theme') ?></p>
		
						<?php endif; ?>								
				</div>
			</div>

		</div>


		<div class="block-3 no-mar sidebar">
			<?php
				// alternative sidebar
				$alt_sidebar=intval(get_post_meta($post->ID, OM_THEME_SHORT_PREFIX.'sidebar', true));
				if($alt_sidebar && $alt_sidebar <= intval(get_option(OM_THEME_PREFIX."sidebars_num")) ) {
					if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'alt-sidebar-'.$alt_sidebar ) ) ;
				} else {
					get_sidebar();
				}
				?>
		</div>
		
		<!-- /Content -->
		
		<div class="clear anti-mar">&nbsp;</div>
		
		
<?php get_footer(); ?>