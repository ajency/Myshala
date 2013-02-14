<?php

function om_widget_testimonials_init() {
	register_widget( 'om_widget_testimonials' );
}
add_action( 'widgets_init', 'om_widget_testimonials_init' );

/* Widget Class */

class om_widget_testimonials extends WP_Widget {

	function om_widget_testimonials() {
	
		$this->WP_Widget(
			'om_widget_testimonials',
			__('Testimonials','om_theme'),
			array(
				'classname' => 'om_widget_testimonials',
				'description' => __('Use this widget to display testimonials', 'om_theme')
			)
		);
	}

	/* Front-end display of widget. */
		
	function widget( $args, $instance ) {
		extract( $args );
		
		$title = apply_filters('widget_title', $instance['title'] );
		$instance['postcount'] = intval($instance['postcount']);
	
		echo $before_widget;
	
		if ( $title )
			echo $before_title . $title . $after_title;
		
		echo do_shortcode('[testimonials]');
		
		echo $after_widget;
	}


	/* Sanitize widget form values as they are saved. */
		
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
			
		return $instance;
	}


	/* Back-end widget form. */
		 
	function form( $instance ) {

		$defaults = array(
			'title' => __('Testimonials','om_theme'),
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
	
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'om_theme') ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>	
		
		<?php
	}
}
?>