<?php

function om_widget_tweets_init() {
	register_widget( 'om_widget_tweets' );
}
add_action( 'widgets_init', 'om_widget_tweets_init' );

function om_tweets_js_init() {
	if( !is_admin() ) {
		wp_register_script('om-widget-tweets', get_template_directory_uri() . '/widgets/tweets/js/tweets.js', array('jquery'));
		wp_enqueue_script('om-widget-tweets');
   	wp_enqueue_script('jquery'); 
	}
}
add_action('init', 'om_tweets_js_init');

/* Widget Class */

class om_widget_tweets extends WP_Widget {

	function om_widget_tweets() {
	
		$this->WP_Widget(
			'om_widget_tweets',
			__('Custom Latest Tweets','om_theme'),
			array(
				'classname' => 'om_widget_tweets',
				'description' => __('A widget that displays your latest tweets.', 'om_theme')
			)
		);
	}

	/* Front-end display of widget. */
	
	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );
		
		$instance['postcount']=intval($instance['postcount']);
		
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;
			
		$id = rand(1,9999);

		?>
			<ul id="twitter_update_list_<?php echo $id?>" class="latest-tweets"></ul>
			<script>om_twitter_latest_posts('<?php echo $instance['username']; ?>',<?php echo $instance['postcount']; ?>,'twitter_update_list_<?php echo $id?>');</script>
			<?php if($instance['follow_text']) { ?>
			<p class="twitter-follow">
				<a href="http://twitter.com/<?php echo $instance['username']; ?>" target="_blank" class="icon-twitter"><span><?php echo $instance['follow_text']; ?></span></a>
			</p>
			<?php } ?>
		<?php
		
		echo $after_widget;
	}

	/* Sanitize widget form values as they are saved. */

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['username'] = strip_tags( $new_instance['username'] );
		$instance['postcount'] = strip_tags( $new_instance['postcount'] );
		$instance['follow_text'] = strip_tags( $new_instance['follow_text'] );

		return $instance;
	}
	
	/* Back-end widget form. */
	
	function form( $instance ) {

		/* Default widget settings. */
		$defaults = array(
			'title' => 'Latest Tweets',
			'username' => 'mopc007',
			'postcount' => '2',
			'follow_text' => 'Follow @mopc007',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'om_theme') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<!-- Username: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e('Twitter username e.g. mopc007', 'om_theme') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $instance['username']; ?>" />
		</p>
		
		<!-- Postcount: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'postcount' ); ?>"><?php _e('Number of tweets', 'om_theme') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'postcount' ); ?>" name="<?php echo $this->get_field_name( 'postcount' ); ?>" value="<?php echo $instance['postcount']; ?>" />
		</p>
		
		<!-- Follow Text: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'follow_text' ); ?>"><?php _e('Follow Text e.g. Follow @mopc007', 'om_theme') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'follow_text' ); ?>" name="<?php echo $this->get_field_name( 'follow_text' ); ?>" value="<?php echo $instance['follow_text']; ?>" />
		</p>
		
	<?php
	}
}

?>