<?php get_header(); ?>

<?php 

$args = array(
		'numberposts'     => -1,
		'orderby'         => 'post_date',
		'post_type'       => 'agc_event',
		'post_status'     => 'publish',
		'suppress_filters' => true );

$events = get_posts($args);
$ejson 	= array();
foreach ($events as $event)
{
	$event_meta = get_post_meta($event->ID,'agc_event_date_time',true);
	$e = new Agc_Event($event->ID);
	$desc = substr($event->post_content,0, 130);
	
	$ejson[] = array(
			'date' 			=> (string)((strtotime($event_meta['from_date'].' '.$event_meta['from_time'])) * 1000 ),
			'type' 			=> '',
			'title'			=> $event->post_title,
			'description'	=> $desc,
			'url'			=> get_permalink($event->ID),
			'thumb'			=> get_the_post_thumbnail( $event->ID, array(50, 50) ),
			'days_left'		=> $e->count_days_left(),
			'cats'			=> get_the_term_list($event->ID, 'agc_event_category', 'in ', ', '),
	);
}
?>
		<div class="block-6 no-mar content-with-sidebar">
			
			<div class="block-6 bg-color-main">
				<div class="block-inner">
					
					<div id="events-container">
						<div id="event-calendar-plugin"></div>
					</div><!-- /#events-container -->
								
				</div>
			</div>

		</div>


		<div>
			
			
			
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
		
		<script>
			jQuery(document).ready(function() {
			
				var eventsInline = <?php echo json_encode($ejson);?>;
				// events calendar
				jQuery("#event-calendar-plugin").eventCalendar({
					jsonData: eventsInline,
					eventsLimit: 100,
					showDescription: true, // also it can be false
					txt_NextEvents: "Upcoming Events",
					txt_SpecificEvents_prev: "Events - ",
					txt_SpecificEvents_after: "",
					moveOpacity: 0.5,
					moveSpeed: 200,
				});
			});
		</script>

<?php get_footer(); ?>