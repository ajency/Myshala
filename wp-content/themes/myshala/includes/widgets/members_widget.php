<?php 
//Widget to display active and recent members.

class AGC_MEMBERS_WIDGET extends WP_Widget {

	
	var $tabs;
	
	public function __construct() {
		
		$this->tabs = array('active-mem' =>__('Active Members','buddypress'),'newest' => __('Recent Members','buddypress'));
		
		parent::__construct(
				'agc_member_widget', // Base ID
				'AGC Member Widget', // Name
				array( 'description' => __( 'This widget is used to display most recent and active members.', 'buddypress' ), )
		);
	}

	public function form( $instance ) {		
				
		$tabs = $this->tabs;
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'member_count' ); ?>"><?php _e( 'No. of members to display:' ); ?></label> 
			<select name="<?php echo $this->get_field_name('member_count');?>" id="<?php echo $this->get_field_id( 'member_count' ); ?>">
				<?php for($i=3 ; $i<= 12 ; $i += 3):?>
					<option value="<?php echo $i;?>" <?php selected($instance['member_count'], $i);?>><?php echo $i;?></option>
				<?php endfor;?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'active_on_diplay' ); ?>"><?php _e( 'Active tab on display:' ); ?></label> 
			<select name="<?php echo $this->get_field_name('active_on_diplay');?>" id="<?php echo $this->get_field_id( 'active_on_diplay' ); ?>" style="width:100%">
				<?php foreach ($tabs as $value => $label):?>
					<option value="<?php echo $value;?>" <?php selected($instance['active_on_diplay'], $value);?>><?php echo $label;?></option>
				<?php endforeach;?>
			</select>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['active_on_diplay'] 	= strip_tags( $new_instance['active_on_diplay'] );
		$instance['member_count'] 		= strip_tags( $new_instance['member_count'] );
		return $instance;
	}

	public function widget( $args, $instance ) {
		
		$tabs = $this->tabs		
	?>
	<div id="members-widget" class="block-3 bg-color-sidebar">
		<div class="block-inner widgets-area">
			<div class="tabs">
				
				<ul id="member-tabs" class="tabs-control">
					<?php foreach ($tabs as $value => $name):?>
						<?php $selected_cls = ($value == $instance['active_on_diplay'])?'active-mem':'';?>
						<li class="<?php echo $selected_cls;?>"><a href="<?php echo '#'.$value; ?>"><span><?php echo $name;?></span></a></li>
					<?php endforeach;?>
				</ul>
				<div class="tab-content tabs-tabs">
				<?php foreach($tabs as $value => $name):?>
					
				<?php $selected_cls = ($value == $instance['active_on_diplay'])?'tab-pane active-mem':'tab-pane';?>
					
					<div class="tabs-tab <?php echo $selected_cls;?> <?php echo $value;?>" id="<?php //echo $value;?>">
						<ul class="member-pane">
							<?php if ( bp_has_members('type='.$value.'&max='.$instance['member_count']) ) : ?>
							
							<?php while ( bp_members() ) : bp_the_member(); ?>
									<li class="recent-style">
										<div class="user-thumb">
											<a href="<?php bp_member_permalink(); ?>"><?php echo  msh_display_avatar(bp_get_member_user_id()); ?></a>
										</div>
										<div class="user-activity">
											<a href="<?php bp_member_permalink(); ?>"><span><?php bp_member_name()?></span></a>
											<div class="meta"><?php  bp_member_last_active(); ?></div>
										</div>
										<div class="clear anti-mar"></div>
									</li>		
							<?php endwhile; ?>
							
							<?php else: ?>
								<li class="recent-style">
									<p><?php _e( "Sorry, no members were found.", 'buddypress' ) ?></p>
								</li>		 
							<?php endif; ?>	
						</ul>
					</div>
				
				<?php endforeach;?>
				</div>
			</div>	
		</div>
	</div>	
	<?php
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget( "AGC_MEMBERS_WIDGET" );' ) );
?>