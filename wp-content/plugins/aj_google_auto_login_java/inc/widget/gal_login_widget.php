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
	<div class="gal-sidebar_widget">
		
	<?php if(is_user_logged_in()): $id = bp_loggedin_user_id(); ?>
	<?php $args = array('item_id'=> $id, 'object'=> 'user', 'width'=> '60','height'=> '60');?>	
		
		<div class="gal-left_div">
			<a href="<?php echo bp_core_get_user_domain($id);?>">
				<?php echo bp_core_fetch_avatar($args);?>
			</a>
		</div>
		<div class="gal-right_div">
			<b>
				<span class="name" title="<?php echo bp_core_get_user_displayname($id);?>"><?php echo bp_core_get_user_displayname($id);?></span>
			</b>
			<br>
			<span class="email" title="<?php echo bp_core_get_user_email($id);?>"><?php echo bp_core_get_user_email($id);?></span>
			<br>
			<a href="<?php echo bp_core_get_user_domain($id);?>" class="gal-links gal-profile_txt">Profile</a>&nbsp;&nbsp;&nbsp;
			<a href="<?php echo wp_logout_url( bp_get_root_domain() );?>" class="gal-links gal-logout_txt">Log Out</a>
		</div>
		<div style="clear:both"></div>
	<?php else: $nonce = wp_create_nonce('gal_user_login_register'); ?>
		<div class="gal-loader" style="display:none"><span class="gal-loader-text">Loggin In...Please Wait..</span></div>
		<div class="gal-login-google">
			<a class="gal-links" href="javascript:void(0);" onClick="galLogin();"></a>
			<input type="hidden" name="galnonce" id="galnonce" value="<?php echo $nonce ?>"/>
		</div>	
	<?php endif; ?>
	
	</div>
	<?php
	}

}

function gal_register_login_widget()
{
	register_widget( 'GAL_LOGIN_WIDGET' );
}
add_action('widgets_init','gal_register_login_widget');