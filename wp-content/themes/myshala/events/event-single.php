<?php 
/**
 * This is a single event view.
 */

get_header(); ?>

		<?php 
			//get_template_part( 'nav', 'above-single' );
			if ( have_posts() ) :
				
				while ( have_posts() ) : the_post();
					load_template(TEMPLATEPATH .'/events/event-template.php' ); 
					comments_template('', true); 
		 		endwhile;
			endif; ?>
		
		<?php //get_template_part( 'nav', 'below-single' ); ?>
		<script>
		<?php global $event;?>
			var jq = jQuery;
			var event_nonce 	= '<?php echo wp_create_nonce('invitee_date')?>';
			var remove_nonce 	= '<?php echo wp_create_nonce('remove_date')?>';
			var event_id		= '<?php global $post;echo $post->ID?>';
			var cs	= '<?php echo $event->show_event_categories();?>';
			var event_link		= '<?php echo get_permalink($post->ID)?>';
		</script>

<?php get_footer(); ?>