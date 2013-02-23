<?php get_header(); ?>

<div id="content" class="block-6 no-mar content-with-sidebar">
	<div class="padder block-full bg-color-main">
		<div class="block-inner">
			<div class="single-bookreview">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

			?>
			 <?php 
			 	if(get_the_author_ID() == bp_loggedin_user_id()):
			 	
			 ?>		
				<div class="single-bookreview-actions">
					<a href="<?php echo bp_loggedin_user_link(); ?>/book-reviews/" class="edit-bookreview box_tag">Go to my book reviews</a>
					<a href="<?php echo  get_bloginfo('url'); ?>/edit-review/?id=<?php echo get_the_ID(); ?>" class="edit-bookreview box_tag">Edit this Review</a>
					<a class="delete_review box_tag button size-mini" bookreview_id="<?php echo get_the_ID();?>">Delete</a>
				</div>
				<div class="clearfix clear"></div>
			<?php endif; ?>	
			<input type="hidden" name="bookreview_link" value="<?php echo bp_loggedin_user_link(); ?>/book-reviews/" />
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
	      		
	          <?php echo get_option(OM_THEME_PREFIX . 'code_after_page_h1'); ?>
				
				<div class="bookreview-image">
				<?php 
				if(has_post_thumbnail(get_the_ID())):
					echo get_the_post_thumbnail(get_the_ID(), large); 
				endif;
				?>
				</div>
							
				<div class="bookreview-meta">
					Reviewed by <a href="<?php echo bp_core_get_user_domain(get_the_author_ID()); ?>"><?php echo bp_core_get_user_displayname(get_the_author_ID());  ?></a>
					
					<?php 
						//create proper date format
						$time = strtotime(get_the_date());
						$date = date('M d, Y',$time);
					?>
				</div>
				<div class="post-date">
					Posted on <?php echo $date; ?>
				</div>
				
				<div class="bookreview-desc">
					<?php echo get_the_content(); ?>
				</div>
				
				<div class="bookreview-cats">
					<?php echo get_the_term_list($event->ID, 'bookreview_category', 'Posted in ', '&nbsp;'); ?>
				</div>
				<div class="clearfix clear"></div>
			</div>
		<?php endwhile;
		else: ?>
		<div class="alert alert-info"><?php _e('Sorry, no posts matched your criteria.'); ?></div>
		<?php endif; ?>
		
		</div>
	
	</div><!-- /.main-area -->
</div>
<div class="block-3 no-mar sidebar">
	
	<div id="event-cats" class="block-3 bg-color-sidebar">
		<div class="block-inner widgets-area">
			<div class="widget-header">Book Review Categories</div>
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