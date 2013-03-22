<?php
class GAL_LOGIN_WIDGET extends WP_Widget {

	public function __construct() {
			
		parent::__construct(
	 		'gal_login_widget', // Base ID
			'Google Auto Login Widget', // Name
			array( 'description' => __( 'Login/Logout and member profile links', 'gal' ), ) // Args
		);
	}

	public function form( $instance ) {
		// outputs the options form on admin
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}

	public function widget( $args, $instance ) {
		// outputs the content of the widget
		
	?>
	<div class="block-3 bg-color-sidebar">
		<div class="block-inner widgets-area">
			<div class="gal-sidebar_widget">
				<?php if(is_user_logged_in()): $id = bp_loggedin_user_id(); ?>
				<?php $args = array('item_id'=> $id, 'object'=> 'user', 'width'=> '60','height'=> '60');?>	
					
					<div class="gal-left_div">
						<a href="<?php echo bp_core_get_user_domain($id);?>">
							<?php echo  msh_display_avatar($id);?>
						</a>
					</div>
					<div class="gal-right_div">
						<span class="name" title="<?php echo bp_core_get_user_displayname($id);?>"><?php echo bp_core_get_user_displayname($id);?></span>
						
						<span class="email" title="<?php echo bp_core_get_user_email($id);?>"><?php echo bp_core_get_user_email($id);?></span>
						
						<a href="<?php echo bp_core_get_user_domain($id);?>" class="gal-links gal-profile_txt button size-mini">View Profile</a>
						<a href="<?php echo wp_logout_url( bp_get_root_domain() );?>" class="gal-links gal-logout_txt">Log Out</a>
						
					</div>
					<div class="clear anti-mar"></div>
				<?php else: $nonce = wp_create_nonce('gal_user_login_register'); ?>
					<div class="gal-loader" style="display:none"><span class="gal-loader-text">Logging In... Please Wait...</span></div>
					<div class="gal-login-google">
						<span>Got a Google account? Just click this button to login!</span>
						<a class="gal-links" href="javascript:void(0);" onClick="galLogin();"></a>
						<input type="hidden" name="galnonce" id="galnonce" value="<?php echo $nonce ?>"/>
					</div>	
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
	}

}

function gal_register_login_widget()
{
	register_widget( 'GAL_LOGIN_WIDGET' );
}
add_action('widgets_init','gal_register_login_widget');