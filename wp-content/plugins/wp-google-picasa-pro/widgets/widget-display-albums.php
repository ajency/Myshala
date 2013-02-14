<?php
/**
 * Copyright (c) 2011, cheshirewebsolutions.com, Ian Kennerley (info@cheshirewebsolutions.com).
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/*************************************************
*
*	Set up 'Widget' to display albums
*	Drag and drop widget to a widgetized area 
*	of the theme
*
**************************************************/
	class Widget_DisplayAlbums extends WP_Widget {
	     
		function Widget_DisplayAlbums() {		
			parent::WP_Widget( false, $name = 'Google Picasa Albums' );		
		}


		function widget( $args, $instance ) { 
		 	
		 	global $key;
		 	
			extract( $args );
			
			$title          = apply_filters( 'widget_title', $instance['title'] );
			// $num_albums     = apply_filters( 'widget_title', $instance['num_albums'] );
			$show_album_ttl = apply_filters( 'widget_title', $instance['show_album_ttl'] );
			$show_albums    = apply_filters( 'widget_title', $instance['show_albums'] );			
			
			$show_albums_array = array();
						
			if( isset( $show_albums ) && $show_albums != '' ){
				$show_albums_array = explode( ',', $show_albums );			
			}		
			
			if( ! is_array( $show_albums_array ) || count( $show_albums_array ) < 1 ){
				$show_albums_array = null;
			}
						
			if ( !isset ( $title) ) {
				$title = "Google Picasa Albums";
			}

			echo $args['before_widget'];
			echo $args['before_title'] . "<span>$title</span>" . $args['after_title'];			
			
			// Create instance of PicasaAPI class
			if( $my_picasa = new CWS_GPPApi ) {

				$options 	= get_option( 'cws_gpp_options' );

				// Call user albums
				$user_albums = $my_picasa->get_album_display( 	$options['album_thumb_size'],																
																strtolower( $options['album_results_page'] ), 
																$show_albums_array, 
																$show_private = null, 
																$show_album_ttl,
																$show_slider_mrkrs = null );																
			}
			
			echo $user_albums;
			echo $args['after_widget'];
		
	     }			
		
		
		function update ( $new_instance, $old_instance ) {
	
			$instance = $old_instance;
			
			$instance['title'] 			= strip_tags( $new_instance['title'] );
			// $instance['num_albums'] 	= strip_tags( $new_instance['num_albums'] );
			$instance['show_album_ttl'] = strip_tags( $new_instance['show_album_ttl'] );			
			$instance['show_albums']    = strip_tags( $new_instance['show_albums'] );			
	
			return $instance;	     	
		}
		
		
		function form( $instance ) {
		
			$title          = esc_attr( $instance['title'] );
			// $num_albums     = esc_attr( $instance['num_albums'] );
			$show_album_ttl = esc_attr( $instance['show_album_ttl'] );			
			$show_albums    = esc_attr( $instance['show_albums'] );	
			
			 ?>
				<p>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'cws_gpp' ); ?></label> 
					<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( 'show_album_ttl' ); ?>"><?php _e( 'Show album titles:', 'cws_gpp' ); ?></label> 
					<input id="<?php echo $this->get_field_id( 'show_album_ttl' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'show_album_ttl' ); ?>"  value="1" <?php if ( $show_album_ttl == 1) { echo 'checked'; } ?> />						
				</p>				
				<p>
					<label for="<?php echo $this->get_field_id( 'show_albums' ); ?>"><?php _e( 'Show albums:', 'cws_gpp' ); ?></label> 
					<input id="<?php echo $this->get_field_id( 'show_albums' ); ?>" type="text" name="<?php echo $this->get_field_name( 'show_albums' ); ?>"  value="<?php echo $show_albums; ?>" />						
				</p>	

			<?php 
		}	
		
	}
