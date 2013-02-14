<?php
/*
Template Name: Lite blog (news)
*/

get_header(); ?>

		<div class="block-6 no-mar content-with-sidebar">
			
			<div class="block-6 bg-color-main">
				<div class="block-inner">
					<?php if(!is_front_page()) { ?>
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
					<?php } ?>
	      		
						<?php
							$wp_query_temp = $wp_query;
							$post_id_temp=$post->ID;
						
							$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
							$args=array(
								'posts_per_page' => get_option('posts_per_page'),
								'paged' => $paged,
								'cat' => 122
							);
							$wp_query = new WP_Query($args);
						?>	
			
						<?php if (have_posts()) : ?>

								<?php $i=0; ?>
								<?php while (have_posts()) : the_post(); ?>
									<?php if($i) echo '<hr />'; ?>
									<div <?php post_class('post-small'); ?> id="post-<?php the_ID(); ?>">
										<?php if(has_post_thumbnail()) { ?>
											<div class="post-pic block-1 zero-mar">
												<div class="block-inner inner move-left">
													<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
												</div>
											</div>
										<?php } ?>
										<div class="post-title">
											<h3><a href="<?php the_permalink(); ?>"><?php
												$format = get_post_format();
												if($format == 'quote')
													echo '&ldquo;'.get_post_meta($post->ID, OM_THEME_SHORT_PREFIX.'quote', true);
												else
													the_title();
											?></a></h3>
											<?php if($format == 'quote') { ?><p class="post-title-comment">&mdash; <?php the_title(); ?></p><?php } ?>
											<?php if($format == 'link') { $link=get_post_meta($post->ID, OM_THEME_SHORT_PREFIX.'link_url', true); ?><?php if($link) { ?><p class="post-title-link"><a href="<?php echo $link ?>" target="_blank"><?php echo str_replace('http://','',$link) ?></a></p><?php } ?><?php } ?>
										</div>
										<div class="post-meta">
											<div class="post-date"><?php the_time( get_option('date_format') ); ?></div>
											<?php /*if(get_option(OM_THEME_PREFIX . 'post_hide_categories') != 'true' && $categories = get_the_category_list(', ')) { ?>
												<div class="post-categories">
													<?php echo $categories ?>
												</div>
											<? } */ ?>
											<?php /*the_tags('<div class="post-tags">', ', ', '</div>' ) */ ?>
											<div class="post-comments">
												<?php comments_popup_link(); ?>
											</div>
										</div>
										<div class="post-text">

											<?php
												if(has_excerpt())
													om_custom_excerpt_more(get_the_excerpt());
												else {
													$tmp=explode('<!--more-->',$post->post_content);
													if(strpos( $post->post_content , "<!--more-->" ) !== false)
														om_custom_excerpt_more(apply_filters('the_content',$tmp[0]));
													else
														echo apply_filters('the_content',$tmp[0]);
												}
											?>
										</div>
										<div class="clear"></div>
									</div>
									    	
									<?php $i++ ?>
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
						<?php wp_reset_query(); $wp_query = $wp_query_temp; ?>					
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