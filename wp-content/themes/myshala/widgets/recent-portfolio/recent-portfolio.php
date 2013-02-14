<?php

function om_widget_recent_portfolio_init() {
	register_widget( 'om_widget_recent_portfolio' );
}
add_action( 'widgets_init', 'om_widget_recent_portfolio_init' );

/* Widget Class */

class om_widget_recent_portfolio extends WP_Widget {

	function om_widget_recent_portfolio() {
	
		$this->WP_Widget(
			'om_widget_recent_portfolio',
			__('Custom Recent Portfolio','om_theme'),
			array(
				'classname' => 'om_widget_recent_portfolio',
				'description' => __('The most recent portfolio items', 'om_theme')
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

		$arg=array (
			'post_type' => 'portfolio',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'posts_per_page' => $instance['postcount']
		);
		
		$sort=get_option(OM_THEME_PREFIX . 'portfolio_sort');
		if($sort == 'date_asc') {
			$arg['orderby'] = 'date';
			$arg['order'] = 'ASC';
		} elseif($sort == 'date_desc') {
			$arg['orderby'] = 'date';
			$arg['order'] = 'DESC';
		}
			
		$query = new WP_Query($arg);
		
    if ($query->have_posts()) {
    	?><div class="portfolio-small-preview"><?php
    	while ($query->have_posts()) {
    		$query->the_post();
    		?>
				<div class="portfolio-small-preview">
					<?php if(has_post_thumbnail()) { ?>
						<div class="pic"><a href="<?php the_permalink(); ?>" class="show-hover-link"><?php the_post_thumbnail('portfolio-thumb'); ?><span class="after"></span></a></div>
					<?php } ?>
					<div class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
					<div class="tags fs-s"><?php the_terms(get_the_ID(), 'portfolio-type', '', ', ', ''); ?></div>
				</div>
				<?php
      }
      ?></div><?php
    }
	
		wp_reset_query();
	
		echo $after_widget;
	
	}


	/* Sanitize widget form values as they are saved. */
		
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
	
		$instance['title'] = strip_tags( $new_instance['title'] );
	
		$instance['postcount'] = $new_instance['postcount'];
			
		return $instance;
	}


	/* Back-end widget form. */
		 
	function form( $instance ) {
	
		// Set up some default widget settings
		$defaults = array(
			'title' => 'Recent Works',
			'postcount' => '2',
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
	
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'om_theme') ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
	
		<!-- Postcount: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'postcount' ); ?>"><?php _e('Number of posts', 'om_theme') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'postcount' ); ?>" name="<?php echo $this->get_field_name( 'postcount' ); ?>" value="<?php echo $instance['postcount']; ?>" />
		</p>

					
	<?php
	}
}
?>