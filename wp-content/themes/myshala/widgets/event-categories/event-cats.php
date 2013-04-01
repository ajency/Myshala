<?php

function om_widget_event_cats_init() {
	register_widget( 'om_widget_event_cats' );
}
add_action( 'widgets_init', 'om_widget_event_cats_init' );

/* Widget Class */

class om_widget_event_cats extends WP_Widget {

	function om_widget_event_cats() {
	
		$this->WP_Widget(
			'om_widget_event_cats',
			__('Event Categories','om_theme'),
			array(
				'classname' => 'om_widget_event_cats',
				'description' => __('Event Categories List.', 'om_theme')
			),
			array (
				'width' => 320,
				'height' => 380
			)
		);
	}

	/* Front-end display of widget. */
		
	function widget( $args, $instance ) {
		extract( $args );
		
		echo $before_widget;
		
		?>
			<div id="event-cats" >
				<div class="block-inner widgets-area">
					<div class=" widget-header">event types</div>
					<ul class="menu">
					<?php
					$args = array( 'taxonomy' => 'agc_event_category' );

					$terms = get_terms('agc_event_category', $args);

					$count = count($terms); $i=0;
					if ($count > 0) {
						
						foreach ($terms as $term) {
							$i++;
							$term_link = get_term_link( $term->slug , 'agc_event_category' ); 
							$term_list .= '<li><a href="' .$term_link. '" title="' . sprintf(__('View all post filed under %s', 'buddypress'), $term->name) . '">' . $term->name . '</a></li>';
							
						}
						echo $term_list;
					}
					?>
					</ul>
				</div>
			</div><!-- /#event-cats -->
			
		<?php
		
		echo $after_widget;
	}


	/* Sanitize widget form values as they are saved. */
		
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
	
		
	
		return $instance;
	}


	/* Back-end widget form. */
		 
	function form( $instance ) {
	
		// Set up some default widget settings
		
			
	<?php
	}
}
?>