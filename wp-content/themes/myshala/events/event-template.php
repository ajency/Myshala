<?php 
global $event;
?>

<div class="block-6 no-mar content-with-sidebar">
	
	<div class="block-6 bg-color-main">
		<div class="block-inner">
			<div class="event-container">
			<!--<ul class="breadcrumb">
			  	<li><a href="<?php echo bloginfo('url');?>">Home</a> <span class="divider">/</span></li>
			  	<li><a href="<?php echo agc_events_url();?>">Events</a> <span class="divider">/</span></li>
			  	<li class="active"><?php echo $event->get_event_title(); ?></li>
			</ul>-->
			<!-- hidden fields -->
			<input type="hidden" name="event_id" id="event_id" 	 value="<?php echo get_the_ID(); ?>" />
			<!--  end hidden fields -->
			<!-- alert message -->
			<div id="rsvp-response-div">
	           <?php echo $event->agc_event_user_rsvp_notice();?>
	        </div>
			<h2 class="page-title"><?php echo $event->get_event_title(); ?></h2>
			<div class="details">
				<div class="date">
					<span class="label" title="Date">&nbsp;</span> - <?php echo  $event->show_event_date() ;/* Friday, 15th October 2012 */ ?>
				</div><!-- /.date -->
				<div class="time">
					<span class="label" title="Time">&nbsp;</span> - <?php echo  $event->show_event_time() ; ?>
				</div><!-- /.time -->
				<div class="venue">
					<span class="label" title="Venue">&nbsp;</span> - <?php echo  $event->show_event_venue() ; ?>
				</div><!-- /.venue -->
				<div class="calendars">
					<span class="label" title="Calendars">&nbsp;</span> - <?php echo  $event->show_event_categories() ; ?>
				</div><!-- /.calendars -->
			</div><!-- /.details -->
			<div class="event-actions">
				<div class="count-down">
					<span><?php echo $event->count_days_left(); ?></span>days left
				</div><!-- /.count-down -->
				
				<?php //if(!$event->is_plusone && ( $event->count_days_left() > 0)):?>
				<div class="rsvp">
					<span class="button size-small" id="rsvp-pop">RSVP</span>
					<div class="btn-slide">
						<h3 class="popover-title">Are You Going?</h3>
						<?php echo $event->generate_rsvp_box(); ?>
					</div>
					<script>
					jQuery(document).ready(function() {
						jQuery('.btn-slide').hide();
						jQuery('#rsvp-pop').click(function() {
							jQuery('.btn-slide').slideToggle('slow');
							return false;
						});
					});
					</script>
				</div><!-- /.rsvp --> 
				<?php //endif;?>
		
			</div><!-- /.event-actions -->
			<div class="clearfix clear"></div>
			<div class="event-image">
			<?php if(has_post_thumbnail(get_the_ID())): ?>
				<?php echo get_the_post_thumbnail(get_the_ID(),array(250,175),array("class"=>"img-polaroid")); ?>
			<?php else: ?>	
					<!--  <img src="http://placehold.it/250x175" alt="" class="img-polaroid"> -->
			<?php endif; ?>		
			</div><!-- /.event-image -->
			<div class="event-description">
				<?php echo stripslashes(get_the_content()); ?>
			</div><!-- /.description -->
			<div class="clearfix clear"></div>
			<div class="member-responses">
				<?php echo $event->get_rsvp_list('yes'); ?>
			</div><!-- /.member-responses -->
			<div class="member-responses">
				<h4>Invited <span class="meta">( <?php echo $event->invitee_count; ?> )</span></h4>
				<?php echo $event->get_invitee_list(); ?>
			</div><!-- /.member-responses -->
			<div class="member-responses">
				<?php echo $event->get_rsvp_list('maybe'); ?>
			</div><!-- /.member-responses -->
		</div><!-- /.event-container -->
						
		</div>
	</div>

</div>


<div class="block-3 no-mar sidebar">
	
	<div id="event-cats" class="block-3 bg-color-sidebar">
		<div class="block-inner widgets-area">
			<div class="widget-header">Event Types</div>
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
</div>

<!-- /Content -->

<div class="clear anti-mar">&nbsp;</div>