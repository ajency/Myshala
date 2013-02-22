<?php 
//Widget to display active and recent members.

class AGC_FORUM_TOPICS_WIDGET extends WP_Widget {



	public function __construct() {

		parent::__construct(
				'agc_forum_topics_widget', // Base ID
				'AGC Forum Topics Widget', // Name
				array( 'description' => __( 'This widget is used to display most recent forum topics.', 'buddypress' ), )
		);
	}

	public function form( $instance ) {
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'topic_count' ); ?>"><?php _e( 'No. of topics to display:' ); ?></label> 
			<select name="<?php echo $this->get_field_name('topic_count');?>" id="<?php echo $this->get_field_id( 'topic_count' ); ?>">
				<?php for($i=3 ; $i<= 12 ; $i += 1):?>
					<option value="<?php echo $i;?>" <?php selected($instance['topic_count'], $i);?>><?php echo $i;?></option>
				<?php endfor;?>
			</select>
		</p>
		<p>
		<label for="parent_id"><?php _e( 'Forum', 'bbpress' ); ?></label>
		<?php
			bbp_dropdown( array(
				'selected'           => $instance['parent_id'],
				'show_none'          => __( '(No Parent)', 'bbpress' ),
				'select_id'          => $this->get_field_name('parent_id'),
				'disable_categories' => false
			) );
		?>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['topic_count'] 		= strip_tags( $new_instance['topic_count'] );
		$instance['parent_id'] 		  	= strip_tags( $new_instance['parent_id'] );
		return $instance;
	}

	public function widget( $args, $instance ) {

	global $wpdb;
	//Get form with title General
	$forum_id = $wpdb->get_var($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE `post_title` = %s AND `post_type` = %s",'General','forum'));
	
	?>

	<div id="recent-topics" class="block-3 bg-color-sidebar">
		<div class="block-inner widgets-area">
			<div class="widget-header">Recent Topics</div>
			<ul>
			<?php //Use $instance['parent_id'] if filtering specific post_parent?>
			<?php if ( bbp_has_topics(array('posts_per_page' => $instance['topic_count'],'post_parent' => $forum_id  )) ) : ?>
			<?php ?>
			<?php while ( bbp_topics() ) : bbp_the_topic();?>

			<li>
				<a href="<?php bbp_topic_permalink();?>"><?php bbp_topic_title(); ?></a>
				<div class="freshness"><?php bbp_topic_last_active_time();?></div>
			</li>

			<?php endwhile;?>

			<?php else:?>
			
			<li class="no-topics">
				<div class="alert alert-block alert-info">
				  <h4>No topics yet!</h4>
				  <?php if(!agc_is_alumni()): //Alumni Check?>
					Why dont you go ahead and <a href="<?php bloginfo('url'); ?>/discussions/" class="btn btn-info">Add a New Topic</a>
				  <?php endif;?>	
				</div>
			</li>
			
			<?php endif; ?>
			
			</ul>
			<div class="view-more">
				<a class="r-m-a" href="<?php echo  bbp_get_forums_url();?>">View All Topics</a>
			</div>
		</div>
	</div>
	<!-- /#recent-topics -->
<?php }

}
if(function_exists('bbp_has_topics'))
{
	add_action( 'widgets_init', create_function( '', 'register_widget( "AGC_FORUM_TOPICS_WIDGET" );' ) );
}