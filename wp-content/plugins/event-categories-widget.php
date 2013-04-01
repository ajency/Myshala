<?php
/*
Plugin Name: Event Categories Widget
Plugin URI: http://ajency.in/
Description: Displays a list of Event Categories on your sidebar
Author: Ajency
Version: 1
Author URI: http://ajency.in/
*/
 
 
class EventCatsWidget extends WP_Widget
{
  function EventCatsWidget()
  {
    $widget_ops = array('classname' => 'EventCatsWidget', 'description' => 'Displays a list of Event Categories' );
    $this->WP_Widget('EventCatsWidget', 'Event Categories Widget', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
	<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
	
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    // WIDGET CODE GOES HERE
	
	
	// Start Code	
	echo '<div class="block-3  sidebar bg-color-sidebar">';
	foreach ( $users as $user ) 
		{
			?>
				<div id="event-cats" >
					<div class="block-inner widgets-area">
						<div class=" widget-header"><?php echo $title; ?></div>
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
				</div>
			<?php
		}
	echo '</div>';

  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("EventCatsWidget");') );?>