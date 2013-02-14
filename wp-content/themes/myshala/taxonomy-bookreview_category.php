<?php

/**
 * Archive Template for Events
 *
 */

get_header(); ?>

<div id="content" class="block-6 no-mar content-with-sidebar">
	<div class="padder block-6 bg-color-main">
		<div class="block-inner review-archive">
			<h2 class="page-title"><?php single_cat_title('Book Reviews in '); ?></h2>
			
			<div id="bookreview-box" class="reviews-box-class">
				<ul class="clearfix">
			
				<?php while ( have_posts() ) : the_post(); 
					
					//create proper date format
					$time = strtotime(get_the_date());
					$date = date('d M y',$time);
										
					//strip post content if extra
					$post_content = (strlen(get_the_content()) > 200) ? substr(get_the_content(),0,197) . '...' : get_the_content();
					//get_post($post->ID);
					
					//trim post title
					$post_title = (strlen(get_the_title()) > 25) ? substr(get_the_title(),0,21) . '&raquo;' : get_the_title();
					?>
					
					<li>
						<div class="review-thumb">
						<?php 
							if(has_post_thumbnail(get_the_ID())):
								echo get_the_post_thumbnail(get_the_ID(),array(193,193)); 
							else:
								echo '<img src="'. get_template_directory_uri() . '/images/nobookimage.png" width="193" height="193" alt="' . get_the_title() . '"/>';	
							endif;	
						 ?>
						<span class="date"><?php echo $date; ?></span>
						</div>
						<h4><a title="<?php echo get_the_title(); ?>" href="<?php echo get_permalink( get_the_ID() ); ?>"> 
							<?php //echo $post_title; ?><?php echo get_the_title(); ?>
						</a></h4>
						<div class="review-cats">
							<?php echo get_the_term_list($event->ID, 'bookreview_category', 'Posted in ', '&nbsp;'); ?>
						</div>
						<div class="review-meta">
							Reviewed by <a href="<?php echo bp_core_get_user_domain(get_the_author_ID()); ?>"><?php echo bp_core_get_user_displayname(get_the_author_ID()); ?></a>
						</div>
						<div class="review-desc">
							<?php echo $post_content;?>
							<a href="<?php echo get_permalink( get_the_ID() ); ?>" class="">Read More</a>
						</div>
						
						<div class="review-bar clear">
							
							<div class="review-actions">
								<?php if($view_page == 'MY_REVIEWS' && is_user_logged_in() && (get_the_author_ID() == bp_displayed_user_id())): ?>
									<div class="edit-options">
										 <a href="<?php echo get_bloginfo('url') . '/edit-review/?id=' . get_the_ID()?>" class="edit-classified box_tag button size-mini">Edit</a>
										 <span class="delete_bookreview box_tag button size-mini btn-danger" bookreview_id="<?php echo get_the_ID();?>">Delete</span>
									</div> 
								<?php else: endif; ?>
							</div>
						</div>
					</li>
				
				<?php endwhile; ?>
				
				<?php
					$nav_prev=get_previous_posts_link(__('Newer Reviews', 'om_theme'));
					$nav_next=get_next_posts_link(__('Older Reviews', 'om_theme'));
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
				
				</ul>
			</div>
			
		</div>
	</div><!-- /.main-area -->
</div>
<div class="block-3 no-mar sidebar">
	
	<div id="event-cats" class="block-3 bg-color-sidebar">
		<div class="block-inner widgets-area">
			<h3 class="widget-title">Book Review Categories</h3>
			<ul>
			<?php
			$args = array( 'taxonomy' => 'bookreview_category' );

			$terms = get_terms('bookreview_category', $args);

			$count = count($terms); $i=0;
			if ($count > 0) {
				
				foreach ($terms as $term) {
					$i++;
					$term_list .= '<li><a href="' .get_bloginfo('url'). '/bookreview-categories/' . $term->slug . '" title="' . sprintf(__('View all post filed under %s', 'my_localization_domain'), $term->name) . '">' . $term->name . '</a></li>';
					
				}
				echo $term_list;
			}
			?>
			</ul>
		</div>
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
	
</div><!-- sidebar -->
<div class="clearfix clear anti-mar"></div>
	
<?php get_footer(); ?>