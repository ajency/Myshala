<?php
/**
 * WPPicasa Admin
 * 
 * Main admin file which loads all settings panels and sets up admin menu.
 *
 * @author 		Nakunakifi
 * @category 	Admin
 * @package 	WPPicasa
 */

/**
 * Admin Menus
 * 
 * Sets up the admin menus in wordpress.
 */
add_action( 'admin_menu', 'nak_gp_add_page' );

function nak_gp_add_page() {
	
	// Register options page
	$page = add_options_page(	'Google Picasa Options', 
								'Google Picasa Viewer', 
								'manage_options', 
								'nak_gp', 
								'nak_gp_options_page' );

   // Using registered $page handle to hook stylesheet loading 
   ///add_action( 'admin_print_styles-' . $page, 'nak_gp_admin_styles' );
}

// Draw the options page
function nak_gp_options_page() {

	global $get_page;

	if ( !current_user_can( 'manage_options') ){ wp_die( __( 'You do not have sufficient permissions to access this page.' ) ); }
	
	?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>Google Picasa Albums Viewer Settings Page</h2>
			<form method="post" action="options.php">
				<?php
					if( function_exists('settings_fields') ) {
						settings_fields( 'nak_gp_options' ); // needs to match register_settings()
					}
					
					// 
					if( function_exists( 'do_settings_sections' ) )	{
						do_settings_sections( 'nak_gp' );
					}
										
					nak_gp_setting_input();				// Grab the form					
					nak_gp_meta_box_pro();				// Grab the meta boxes
					// nak_gp_meta_box_donate();
					nak_gp_meta_box_feedback();
				?>
			</form>
		</div>
<?php
}



	// Register and define the settings
	add_action( 'admin_init', 'nak_gp_admin_init' );
	
	function nak_gp_admin_init() {
	
		// Register style sheet
		wp_enqueue_style( 'nak_google_picasa_albums_activation', plugins_url( 'css/activation.css', __FILE__ ) );
		
		register_setting(
			'privacy', 
			'nak_gp_options',
			'nak_gp_validate_options' 
		);
			
/*
		add_settings_field(
			'nak_gp_text_stringcunt',
			'Enter text here',
			'nak_gp_setting_input',
			'privacy',
			'default'
		);
*/	
		
		register_setting( 'nak_gp_options', 'nak_gp_options', 'nak_gp_validate_options' ); // settings_fields
	}


	// Display a Picasa Pro Promo Box
	function nak_gp_meta_box_pro() {
	?>
	<div class="widget-liquid-right">
<div id="widgets-right">

	<div style="width:20%;" class="postbox-container side">
		<div class="metabox-holder">
			<div class="postbox" id="donate">
				<h3><span>Get Google Picasa Pro!</span></h3>
					<div class="inside">
						<p>Grab yourself the <a href="http://www.cheshirewebsolutions.com/?utm_source=wp_gp_viewer&utm_medium=wp_plugin&utm_content=meta_box_pro&utm_campaign=plugin_upgrade">Pro</a> version of the plugin. 						<a href="http://www.cheshirewebsolutions.com/?utm_source=wp_gp_viewer&utm_medium=wp_plugin&utm_content=meta_box_download_it_here&utm_campaign=plugin_upgrade">Download it here</a></p>
						<p>
							Reasons to UPGRADE!
						</p>
						<ol>
							<li>AWESOME Slideshow</li>
							<li>Infinite Image Caraousels</li>
							<li>Enhanced Security</li>
							<li>Results are cached for speed</li>
							<li>Show Public/Private Albums</li>
							<li>Display Albums Names</li>
						</ol>

					</div>
			</div>
		</div>
	</div>


</div>
</div>
	
	<?php
		
	}

	// Display a donate button
	function nak_gp_meta_box_donate() {
	?>
		<div class="widget-liquid-right">
			<div id="widgets-right">	
				<div style="width:20%;" class="postbox-container side">
					<div class="metabox-holder">
						<div class="postbox" id="donate">
							<h3><span>Buy me a coffee!</span></h3>
								<div class="inside">
									<p>I spend hours staring at my screen drinking a range of caffinated beverages.</p>
									<p>If you have found this plugin useful why not help keep me going and buy me a <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZAZHU9ERY8W34">coffee?</a></p>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
	<?php
		
	}


	// Display a feedback links
	function nak_gp_meta_box_feedback() {
	?>

		<div class="widget-liquid-right">
			<div id="widgets-right">	
				<div style="width:20%;" class="postbox-container side">
					<div class="metabox-holder">
						<div class="postbox" id="feedback">
							<h3><span>I want your feedback!</span></h3>
							<div class="inside">							
								<p>If you have found a bug please email me <a href="mailto:info@nakunakifi.com?subject=Feedback%20Google%20Picasa%20Viewer">info@nakunakifi.com</a></p>
								<p>Tell other people it works by giving it a <a href="http://wordpress.org/extend/plugins/google-picasa-albums-viewer/">good rating</a></p>
								<p>&raquo; Share it with your friends <a href="<?php echo "http://twitter.com/share?url=http://bit.ly/q4nqNA&text=Check out this awesome WordPress Plugin I'm using - Google Picasa Viewer" ?>">Tweet It</a></p>
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>	
		
	<?php
		
	}


	// Display and fill the form field
	function nak_gp_setting_input() {
	
		global $get_page;
		
		// get option 'text_string' value from the database
		$options = get_option( 'nak_gp_options' );
		
		?>
		
		
<div class="widget-liquid-left">
<div id="widgets-left">		
		
		<div class="postbox-container">
		
			<div class="metabox-holder">
			
				<div class="postbox" id="settings">
		
					<table class="form-table">
						<tr valign="top">
							<th scope="row">Username / Email</th>
							<td>
								<input type="text" name="nak_gp_options[username]" value="<?php echo $options['username']; ?>" />
						</tr>
						<tr>
							<th scope="row">Password</th>
							<td>
								<input type="password" name="nak_gp_options[password]" value="<?php echo $options['password']; ?>" />
						</tr>
						
						<tr>
							<th scope="row">Number of album results per page</th>
							<td>
								<input type="number" name="nak_gp_options[max_album_results]" value="<?php echo $options['max_album_results']; ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">Album thumbnail size (px)</th>
							<td>
								<input type="number" name="nak_gp_options[album_thumb_size]" value="<?php echo $options['album_thumb_size']; ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">Number of image results per page</th>
							<td>
								<input type="number" name="nak_gp_options[max_results]" value="<?php echo $options['max_results']; ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">Thumbnail size (px)</th>
							<td>
								<input type="number" name="nak_gp_options[thumb_size]" value="<?php echo $options['thumb_size']; ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">Lightbox image size (px)</th>
							<td>
								<input type="number" name="nak_gp_options[max_image_size]" value="<?php echo $options['max_image_size']; ?>" />
							</td>
						</tr>
						<tr>
							<th>Page to show album results on </th>
							<td>
							<select style="width:240px;" name="nak_gp_options[album_results_page]" id="">
							<?php 
								foreach(list_pages() as $key => $page)
								{ 								
									?><option value="<?php echo $key; ?>" <?php if ( $options['album_results_page'] == $key) { echo 'selected="selected"'; } ?>><?php echo $page; ?></option>
							<?php } ?>
							</select>
							<p>
								<small>This is the page to place the shortcode, [nak_google_picasa_album_images]</small>
							</p>
							</td>
						</tr>

						
						
					</table>
					
				</div>
				
				<input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />

			</div>
			
		</div>
		
		
</div>
</div>		
		
		
		
		<?php
	}




// Validate user input
function nak_gp_validate_options( $input ) {

	$errors = array();
	
	$valid['username']        = esc_attr( $input['username'] );
	/* $valid['password']        = md5( esc_attr( $input['password'] ) ); */
	$valid['password']        = esc_attr( $input['password'] );
	$valid['max_album_results'] = esc_attr( $input['max_album_results'] );
	$valid['album_thumb_size']  = esc_attr( $input['album_thumb_size'] );
	$valid['max_results']      = esc_attr( $input['max_results'] );
	$valid['thumb_size']       = esc_attr( $input['thumb_size'] );
	$valid['max_image_size']    = esc_attr( $input['max_image_size'] );
	$valid['album_results_page']    = esc_attr( $input['album_results_page'] );


	// Make sure user name is not empty
	if( $valid['username'] == '' ) {
		$errors['username'] = 'Please enter your Google Picasa username.';		
	}

	if( $valid['password'] == '') {
		$errors['password'] = 'Please enter your Google Picasa password.';		
	}
	
	// Validate numbers
	// Make sure Max Album Results is numeric
	if( !is_numeric( $valid['max_album_results'] ) ) {
		$errors['max_album_results'] = 'Please enter a number for the number of albums to show on a page.';
	}	

	if( !is_numeric( $valid['album_thumb_size'] ) ) {
		$errors['album_thumb_size'] = 'Please enter a number in pixels for the album thumbnail size.';
	}	

	if( !is_numeric( $valid['max_results'] ) ) {
		$errors['max_results'] = 'Please enter a number for the number of results to show on a page.';
	}	

	if( !is_numeric( $valid['thumb_size'] ) ) {
		$errors['thumb_size'] = 'Please enter a number in pixels for the image thumbnail size.';
	}	

	if( !is_numeric($valid['max_image_size'] ) ) {
		$errors['max_image_size'] = 'Please enter a number in pixels for image size in the lightbox (e.g. 600).';
	}	


	// Check Zend Framework is available
	if( $my_picasa = new PicasaAPI( $options['username'], $options['password'] ) ) {	
	
		// Extra check to test for things like Zend Framework
		if ( $my_picasa->preflight_check() == false ) {
		
			$zf_notice = 'Error trying' .
                    ' to access Zend/Loader.php using \'use_include_path\' =' .
                    ' true. Make sure you include Zend Framework in your' .
                    ' include_path which currently contains: ' .
                    ini_get( 'include_path' ) . '. ';
                    
            $zf_notice .= 'It can be <a href="http://framework.zend.com/download/webservices" target="_blank">downloaded here</a>.';
		
			$errors['preflight_check'] = "$zf_notice" ;
		}					
	}


	// Display all errors together
	if( count( $errors ) > 0 ) {
			
		$err_msg = '';
			
		// Display errors
		foreach( $errors as $err ) {
			$err_msg .= "$err<br><br>"; 
		}

		add_settings_error(
			'nap_gp_text_string',
			'nak_gp_texterror',
			$err_msg,
			'error'
		);
	}

	return $valid;
}




	// Dispay upgrade notice
	function nak_gp_admin_installed_notice() {
		
		global $pagenow, $current_user;
		
		$user_id = $current_user->ID;
		
		// Check if user has dismissed notice previously
		if ( ! get_user_meta( $user_id, 'nak_gp_ignore_upgrade' ) ) {
	
			// Only show upgrade notice if on this page
			//if ( $pagenow == 'plugins.php' ) 
			//if($pagenow == 'options-general.php')
			{
			?>
			<div id="message" class="updated nak-gpp-message">
				<div class="squeezer">
					<h4><?php _e( '<strong>Google Picasa Viewer has been installed &#8211; Get the Pro version</strong>', 'woocommerce' ); ?></h4>
					<p class="submit">
						<a href="http://www.cheshirewebsolutions.com/?utm_source=wp_gp_viewer&utm_medium=wp_plugin&utm_content=upgrade_notice_message&utm_campaign=plugin_upgrade" class="button-primary"><?php _e( 'Visit Site', 'nak_gp_' ); ?></a>
						<a href="<?php echo admin_url('admin.php?page=nak_gp'); ?>" class="button-primary"><?php _e( 'Settings', 'woocommerce' ); ?></a>
						<a href="?nak_gp_ignore_upgrade=0" class="secondary-button">Hide Notice</a>
					</p>
				</div>
			</div>
			<?php
			}
		
		}
		// Set installed option
		update_option('nak_gp_installed', 0);
	}

	
	// If installed display upgrade notice
	function nak_gp_admin_notices_styles() {
	
		// Installed notices
		if ( get_option( 'nak_gp_installed' ) == 1 ) {
		
			add_action( 'admin_notices', 'nak_gp_admin_installed_notice' );		
		}
	}
		
	// Allow user to dismiss upgrade notice :)
	add_action( 'admin_init', 'nak_gp_ignore_upgrade' );	
	function nak_gp_ignore_upgrade() {
	
	    global $current_user;
	
	    $user_id = $current_user->ID;
	
	        /* If user clicks to ignore the notice, add that to their user meta */
	        if ( isset( $_GET['nak_gp_ignore_upgrade'] ) && '0' == $_GET['nak_gp_ignore_upgrade'] ) {
	             add_user_meta($user_id, 'nak_gp_ignore_upgrade', 'true', true);
	    	}
	}	
//