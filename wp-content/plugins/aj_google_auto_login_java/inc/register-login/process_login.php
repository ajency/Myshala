<?php 
function gal_ajax_register_login_user()
{
	global $opt_gal_client_id,$opt_gal_redirect_uri,$opt_gal_domain,$opt_gal_client_secret;
	
	// response output
	header( "Content-Type: application/json" );
	
	$nonce 	= $_POST['_wpnonce'];
	$user	= $_POST['user'];
	
	// check to see if the submitted nonce matches with the
	// generated nonce we created earlier
	if ( ! wp_verify_nonce( $nonce, 'gal_user_login_register' ) )
		die ( 'Busted!');

	session_start();
	
	//Include this to use the functions email_exists and user_name exits.
	require_once(ABSPATH . WPINC . '/registration.php');
	

		if ( email_exists($user['email']) || username_exists( $user['email'] ) )
		{
			//Login the user and redirect him.
			if(email_exists($user['email']))
				$user_data = get_user_by('email', $user['email']);

			$user_login_id = $user_data->ID;
			$response = json_encode( array( 'success' => true, 'code'=> 0 ,'msg' => 'User exits and will be auto logged in.'));
		}
		else
		{
			$email_name = explode('@',$user["email"]);
				
			//Initialize the user array.
			$user_data = array();
			$user_data['user_login']    = $user["email"];
			$user_data['user_pass']     = wp_generate_password();
			$user_data['user_nicename'] = sanitize_title($email_name[0]);
			$user_data['first_name']    = (isset($user['given_name']))?$user['given_name']:'';
			$user_data['last_name']     = (isset($user['family_name']))?$user['family_name']:'';
			$user_data['display_name']  = (isset($user['name']))?$user['name']:'';
			$user_data['user_url']      = (isset($user["link"]))?$user["link"]:'';
			$user_data['user_email']    = (isset($user["email"]))?$user["email"]:'';

			$user_data = apply_filters('gal_insert_user', $user_data, $user );

			//Insert a new user to our database and make sure it worked
			$user_login_id   = wp_insert_user($user_data);

			//Check if there was any error
			if( is_wp_error($user_login_id) )
			{
				$response = json_encode( array( 'success' => false, 'code'=> 1 ,'msg' => 'Failed to register. Please try again.'));
				echo $response;
				exit;
			}


			if(isset($_SESSION['token'])) unset($_SESSION['token']);

			$_SESSION['token'] = $_POST['token'];
			$response = json_encode( array( 'success' => true ,'code'=> 2 ,'msg' => 'User has been successfully registered.') );

		}


		if(isset($user["picture"]))
			update_user_meta($user_login_id, 'gal_user_avatar', $user["picture"]);

		wp_set_auth_cookie( $user_login_id );
	
	echo $response;
	exit;
}
add_action('wp_ajax_nopriv_gal_register_login_user', 'gal_ajax_register_login_user');
