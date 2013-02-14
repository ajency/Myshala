<?php
	/*************************************************
	*
	*	Set up 'Widget' to display albums
	*	Drag and drop widget to a widgetized area 
	*	of the theme
	*
	**************************************************/
	
	// use widgets_init action hook to execute custom function and register widget
	add_action('widgets_init', create_function('', 'return register_widget("Nak_gp_Widget_DisplayAlbums");'));

	class Nak_gp_Widget_DisplayAlbums extends WP_Widget {

	     
		function Nak_gp_Widget_DisplayAlbums() {
		
			parent::WP_Widget(false, $name = 'Google Picasa Albums');
		
		}


		function widget( $args, $instance ) { 

			extract($args);
			$title = apply_filters('widget_title', $instance['title']);
		
			// $wpPicasa 	= new WPPicasa();
			$wpPicasa = $GLOBALS['wpPicasa'];
			
			$options 	= get_option( 'nak_gp_options' );
			
			if ( !isset ( $title) ) {
				$title = "Google Picasa Albums";
			}

			echo $args['before_widget'];
			echo $args['before_title'] . "<span>$title</span>" . $args['after_title'];
			
			// Create instance of PicasaAPI class
			if( $my_picasa = new PicasaAPI( $options['username'], $options['password'] ) ) {
				
				// Call user albums
				$user_albums = $my_picasa->get_album_display( $options['max_album_results'], 
							$options['album_thumb_size'], strtolower($options['album_results_page'] ) );
			}
			
			echo $user_albums;
			echo $args['after_widget'];
		
	     }			
		
		
		function update ( $new_instance, $old_instance) {
	
			$instance = $old_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );
	
			return $instance;	     	
		}
		
		
		function form($instance) {
		
			$title = esc_attr($instance['title']);
			 ?>
				<p>
					<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
					<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
				</p>
			<?php 
		}	
		
	}
?>