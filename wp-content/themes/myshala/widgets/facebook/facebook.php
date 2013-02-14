<?php

function om_widget_facebook_init() {
	register_widget( 'om_widget_facebook' );
}
add_action( 'widgets_init', 'om_widget_facebook_init' );

/* Widget Class */

class om_widget_facebook extends WP_Widget {

	function om_widget_facebook() {
	
		$this->WP_Widget(
			'om_widget_facebook',
			__('Facebook Like Box','om_theme'),
			array(
				'classname' => 'om_widget_facebook',
				'description' => __('Widget that enables Facebook Page owners to attract and gain Likes from your own website.', 'om_theme')
			)
		);
	}

	/* Front-end display of widget. */
		
	function widget( $args, $instance ) {
		extract( $args );
	
		$title = apply_filters('widget_title', $instance['title'] );
	
		echo $before_widget;
	
		if ( $title && $instance['no_margins'] != 'true' )
			echo $before_title . $title . $after_title;
			
		if($instance['no_margins'] == 'true')
			echo '<div class="widget-eat-margins eat-margins">';
	
			$lang='en_US';
			if(WPLANG)
				$lang=WPLANG;
		?>
			<div id="fb-root"></div>
			<script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/<?php echo $lang?>/all.js#xfbml=1";  fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script>
			<div class="fb-like-box" data-href="<?php echo $instance['url'] ?>" data-colorscheme="<?php echo $instance['color'] ?>"  data-width="292" data-show-faces="<?php echo ($instance['show_faces']=='true'?'true':'false') ?>" data-stream="<?php echo ($instance['show_stream']=='true'?'true':'false') ?>" data-header="<?php echo ($instance['show_header']=='true'?'true':'false') ?>" data-border-color="#eeeeee"></div>
		<?php
	
		if($instance['no_margins'] == 'true')
			echo '</div>';

		echo $after_widget;
	
	}


	/* Sanitize widget form values as they are saved. */
		
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags( $new_instance['title'] );
	
		$instance['url'] = $new_instance['url'] ;
		$instance['color'] = $new_instance['color'];
		$instance['show_faces'] = $new_instance['show_faces'];
		$instance['show_stream'] = $new_instance['show_stream'];
		$instance['show_header'] = $new_instance['show_header'];
		$instance['no_margins'] = $new_instance['no_margins'];
		
	
		return $instance;
	}


	/* Back-end widget form. */
		 
	function form( $instance ) {
	
		// Set up some default widget settings
		$defaults = array(
			'title' => '',
			'url' => 'http://www.facebook.com/platform',
			'color' => 'light',
			'show_faces' => 'true',
			'show_stream' => 'true',
			'show_header' => 'true',
			'no_margins' => '',
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'om_theme') ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>	
			
		<!-- Widget URL: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e('Facebook Page URL:', 'om_theme') ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" value="<?php echo $instance['url']; ?>" />
		</p>

		<!-- Color: Select Box -->
		<p>
			<label for="<?php echo $this->get_field_id( 'color' ); ?>"><?php _e('Color Scheme:', 'om_theme') ?></label>
			<select id="<?php echo $this->get_field_id( 'color' ); ?>" name="<?php echo $this->get_field_name( 'color' ); ?>" class="widefat">
				<option <?php if ( 'light' == $instance['color'] ) echo 'selected="selected"'; ?>>light</option>
				<option <?php if ( 'dark' == $instance['color'] ) echo 'selected="selected"'; ?>>dark</option>
			</select>
		</p>
		
		<!-- Show Faces: Check Box -->
		<p>
			<label for="<?php echo $this->get_field_id( 'show_faces' ); ?>"><?php _e('Show Faces', 'om_theme') ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_faces' ); ?>" name="<?php echo $this->get_field_name( 'show_faces' ); ?>" value="true" <?php if( $instance['show_faces'] == 'true') echo 'checked="checked"'; ?> />
		</p>
	
		<!-- Stream: Check Box -->
		<p>
			<label for="<?php echo $this->get_field_id( 'show_stream' ); ?>"><?php _e('Show Stream', 'om_theme') ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_stream' ); ?>" name="<?php echo $this->get_field_name( 'show_stream' ); ?>" value="true" <?php if( $instance['show_stream'] == 'true') echo 'checked="checked"'; ?> />
		</p>
		
		<!-- Header: Check Box -->
		<p>
			<label for="<?php echo $this->get_field_id( 'show_header' ); ?>"><?php _e('Show Header ("Find us" bar)', 'om_theme') ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_header' ); ?>" name="<?php echo $this->get_field_name( 'show_header' ); ?>" value="true" <?php if( $instance['show_header'] == 'true') echo 'checked="checked"'; ?> />
		</p>
		
		<!-- Margins: Check Box -->
		<p>
			<label for="<?php echo $this->get_field_id( 'no_margins' ); ?>"><?php _e('No margins (only for sidebar widget areas)', 'om_theme') ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'no_margins' ); ?>" name="<?php echo $this->get_field_name( 'no_margins' ); ?>" value="true" <?php if( $instance['no_margins'] == 'true') echo 'checked="checked"'; ?> />
		</p>
			
	<?php
	}
}
?>