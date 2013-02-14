<?php

/**
 * Archive Template for Events
 *
 */

get_header(); ?>

		<div class="block-6 no-mar content-with-sidebar">
			
			<div class="block-6 bg-color-main">
				<div class="block-inner">
					<?php
	          if ( current_user_can( 'edit_post', $post->ID ) )
	      	    edit_post_link( __('edit', 'om_theme'), '<div class="edit-post-link">[', ']</div>' );
	    		?>
					<div class="tbl-bottom">
						<div class="tbl-td">
							<h1 class="page-h1"><?php single_cat_title('Events in '); ?></h1>
						</div>
						<?php if(get_option(OM_THEME_PREFIX . 'show_breadcrumbs') == 'true') { ?>
							<div class="tbl-td">
								<?php om_breadcrumbs(get_option(OM_THEME_PREFIX . 'breadcrumbs_caption')) ?>
							</div>
						<?php } ?>
					</div>
					<div class="clear page-h1-divider"></div>
	      		
	          <?php echo get_option(OM_THEME_PREFIX . 'code_after_page_h1'); ?>
	
						<ul class="events-list">
			
						<?php while ( have_posts() ) : the_post(); ?>
							
							<li>
								<div class="event-thumb"><?php echo get_the_post_thumbnail( $event->ID, array(50, 50) ); ?></div>
								<div class="event-info">
									<div class="event-title"><a href="<?php echo get_permalink(); ?>" class="eventTitle"><?php echo the_title(); ?></a></div>
									<div class="event-meta">
										<?php
											$evtime = get_post_meta(get_the_ID(),'agc_event_date_time',true);
											$e = new Agc_Event(get_the_ID());
										?>
										<em><?php echo $evtime['from_date']; ?></em>
										<small><?php echo $evtime['from_time']; ?></small>
										<span class="cats"><?php echo get_the_term_list($event->ID, 'agc_event_category', 'in ', ', '); ?></span>
									</div>
									<p class="eventDesc"><?php echo get_the_content(); ?></p>
								</div>
								<div class="days-left"><?php echo $e->count_days_left(); ?><span>days to go</span></div>
							</li>
						
						<?php endwhile; ?>
						
						</ul>
						
						<?php echo get_option(OM_THEME_PREFIX . 'code_after_page_content'); ?>
						
						<?php wp_link_pages(array('before' => '<div class="navigation-pages"><span class="title">'.__('Pages:', 'om_theme').'</span>', 'after' => '</div>', 'pagelink' => '<span class="item">%</span>', 'next_or_number' => 'number')); ?>
								
				</div>
			</div>

			<?php
				$fb_comments=false;
				if(function_exists('om_facebook_comments') && get_option(OM_THEME_PREFIX . 'fb_comments_pages') == 'true') {
						if(get_option(OM_THEME_PREFIX . 'fb_comments_pages') == 'after')
							$fb_comments='after';
						else
							$fb_comments='before';
				}
			?>
			
			<?php if($fb_comments == 'before') { om_facebook_comments();	} ?>
			
			<?php if(get_option(OM_THEME_PREFIX . 'hide_comments_pages') != 'true') : ?>
				<?php comments_template('',true); ?>
			<?php endif; ?>
			
			<?php if($fb_comments == 'after') { om_facebook_comments();	} ?>

		</div>


		<div class="block-3 no-mar sidebar">
			
			<div id="event-cats">
				<h3 class="widget-title">event types</h3>
				<ul>
				<?php
				$args = array( 'taxonomy' => 'agc_event_category' );

				$terms = get_terms('agc_event_category', $args);

				$count = count($terms); $i=0;
				if ($count > 0) {
					
					foreach ($terms as $term) {
						$i++;
						$term_list .= '<li><a href="' .get_bloginfo('url'). '/agc_event_category/' . $term->slug . '" title="' . sprintf(__('View all post filed under %s', 'my_localization_domain'), $term->name) . '">' . $term->name . '</a></li>';
						
					}
					echo $term_list;
				}
				?>
				</ul>
			</div><!-- /#event-cats -->
			
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