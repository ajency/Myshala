<?php
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Copyright (c) 2011, cheshirewebsolutions.com, Ian Kennerley (info@cheshirewebsolutions.com).
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * WPPicasa Admin
 * 
 * Main admin file which loads all settings panels and sets up admin menu.
 *
 * @author 		Nakunakifi
 * @category 	Admin
 * @package 	WPPicasaPro
 */

function cws_gpp_admin_scripts() 
{
	wp_enqueue_script( 'cws-gpp-plugin-admin', WPPICASA_PLUGIN_URL . 'js/admin.js' );
	wp_localize_script( 'cws-gpp-plugin-admin', 'cws_gpp_', array( 	
																	'siteurl' 		=> get_option( 'siteurl' ),
																	'pluginurl' 	=> WPPICASA_PLUGIN_URL,
																	'cacheconfirm'	=> __( 'Are you sure you want to delete the cache?', 'cws_gpp' ),
																) );
}

/**
 * Admin Menus
 * 
 * Sets up the admin menus in wordpress.
 */
add_action( 'admin_menu', 'cws_gpp_add_page' );

function cws_gpp_add_page() {

	// Register options page
	$page = add_options_page(	
								__( 'Google Picasa Pro Options', 'cws_gpp' ),
								__( 'Google Picasa Pro', 'cws_gpp' ),
								'manage_options', 
								'cws_gpp', 
								'cws_gpp_options_page' );
								
   // Using registered $page handle to hook stylesheet loading 
   add_action( 'admin_print_styles-' . $page, 'cws_gpp_admin_scripts' );								
}

/**
 *
 * Draw the options page
 *
 */	
function cws_gpp_options_page() {

	// global $get_page;
	
	$hook = 'cws_gpp';
	

	if ( ! current_user_can( 'manage_options') ){ wp_die( __( 'You do not have sufficient permissions to access this page.' ) ); }
	
	?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>Google Picasa Pro Settings Page</h2>
			<?php
				$options = get_option( 'cws_gpp_options' );		

 				$WPPicasaPro = new CWS_WPPicasaPro();

				// Check if we need to authenticate before displaying options form...
				$cws_GPPAdmin = new CWS_GPPAdmin( $WPPicasaPro->preflight_errors );
	

			if( ! $WPPicasaPro->preflight_errors ) {								
				if( isset( $_REQUEST[ 'cws_oauth_return'] ) ) {
					if( $cws_GPPAdmin->debug ) error_log( 'Returned from callback' );
					
					if ( ! isset( $_REQUEST['page'] ) || $_REQUEST['page'] != $hook ) return; // Make sure we play nicely with other OAUth peeps
					
					// Save access token and run $cws_GPPAdmin->is_authenticated() again...
					try{	
						if( $cws_GPPAdmin->debug ) error_log( 'Storing Access Token' );
						$access_token = serialize( $cws_GPPAdmin->consumer->getAccessToken( $_GET, unserialize( $cws_GPPAdmin->request_token ) ) );
					
						add_option( 'CWS-GA-ACCESS_TOKEN', $access_token ); 
						delete_option( 'CWS_GA_REQUEST_TOKEN' );	// no longer need this token so delete it.
	
						header( "Location: " . cws_get_admin_url( '/options-general.php' ) . "?page=cws_gpp" );
					}
					catch (Zend_Oauth_Exception $ex) {
						// header( "Location: " . cws_get_admin_url( '/options-general.php' ) . "?page=cws_gpp" );
						
						// Nuke request token...
						delete_option('CWS_GA_REQUEST_TOKEN');						
						error_log( 'ERROR: ' . $ex );
						header( "Location: " . cws_get_admin_url( '/options-general.php' ) . "?page=cws_gpp" );
						die();	
					}						
				}
				else {
					// If user is authenticated display options form
					if( $cws_GPPAdmin->is_authenticated() )
					{
						?>							
						<form method="post" action="options.php">
							<?php	
							if( function_exists( 'settings_fields' ) ) {
								settings_fields( 'cws_gpp_options' );
							}
							
							// 
							if( function_exists( 'do_settings_sections' ) )	{
								do_settings_sections( 'cws_gpp' );
							}
							
							cws_gpp_setting_input();		// Grab the form					
							cws_gpp_meta_box_feedback();	// Grab the meta boxes
							// cws_gpp_meta_box_links();		// Grab the links meta boxes	
							?>
						</form>								
						<?php
					}
					else {
					?>
					<p>
						<?php _e( 'This is the preferred method of authenticating your Google Picasa account.', 'cws_gpp' ); ?> <br/>
						<?php _e( "All authentication is taken place on Google's secure servers.", 'cws_gpp' ); ?><br/>
					</p>
					<p>
                		<?php _e( 'Clicking the "Start the Login Process" link will redirect you to a login page at Google.com.', 'cws_gpp' ); ?><br/>
                		<?php _e( 'After accepting the login there you will be returned here.', 'cws_gpp' ); ?>
                	</p>
					<?php
						echo $cws_GPPAdmin->get_grant_link();
					}
				}
		}
		else {
			// echo 'Display Error MEssage!';			
			$WPPicasaPro->showAdminMessages( 'Param' );
		}
?>		
		</div>
<?php
}

/**
 *
 * Register and define the settings
 *
 */	
add_action( 'admin_init', 'cws_gpp_admin_init' );
function cws_gpp_admin_init() {
	register_setting( 'cws_gpp_options', 'cws_gpp_options', 'cws_gpp_validate_options' );	// settings_fields
}


/**
 *
 * Display theme suggestion links
 *
 */	
function cws_gpp_meta_box_links() {
?>
	<div class="widget-liquid-right">
		<div id="widgets-right">
			<div style="width:20%;" class="postbox-container side">
				<div class="metabox-holder">
					<div class="postbox" id="feedback">
						<h3><span>Awesome Themes</span></h3>
							<div class="inside">
								<p>Showcase your Photos with an awesome theme:</p>
								<ul>
									<li><a href="">Link 1</a></li>
									<li><a href="">Link 2</a></li>
									<li><a href="">Link 3</a></li>
									<li><a href="">Link 4</a></li>
									<li><a href="">Link 5</a></li>									
								</ul>
								
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	
}

/**
 *
 * Display feedback links
 *
 */	
function cws_gpp_meta_box_feedback() {
?>
	<div class="widget-liquid-right">
		<div id="widgets-right">
			<div style="width:20%;" class="postbox-container side">
				<div class="metabox-holder">
					<div class="postbox" id="feedback">
						<h3><span><?php _e( 'We want your feedback!', 'cws_gpp' ); ?></span></h3>
							<div class="inside">
								<p><?php _e( 'If you have found a bug please email us', 'cws_gpp' ); ?> <a href="mailto:info@cheshirewebsolutions.com?subject=Feedback%20Google%20Picasa%20Viewer">info@cheshirewebsolutions.com</a></p>								
								<?php
									// Prepare  Tweet URL for localization
									$tweet_url = 'http://twitter.com/share?url=http://bit.ly/q4nqNA&text=';
									$tweet_url .= __( "Check out this awesome WordPress Plugin I'm using - Google Picasa Viewer", 'cws_gpp' );
								?>
								<p>&raquo; <?php _e( 'Share it with your friends', 'cws_gpp' ); ?> <a href="<?php echo $tweet_url; ?>"><?php _e( 'Tweet It', 'cws_gpp' ); ?></a></p>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	
}

/**
 *
 * Display and fill the form field
 *
 */	
function cws_gpp_setting_input() {
	
	// Get optios from the database
	$options = get_option( 'cws_gpp_options' );		
?>
	
	<div class="widget-liquid-left">
		<div id="widgets-left">		
			<div class="postbox-container">
				<div class="metabox-holder">				
					<div class="postbox" id="settings">
			
						<table class="form-table">				
<!--
							<tr>
								<th scope="row">Number of albums results to show in total</th>
								<td>
									<input type="number" name="cws_gpp_options[max_album_results]" value="<?php // echo $options['max_album_results']; ?>" />
								</td>
							</tr>
-->					
							<tr>
								<th scope="row"><?php _e( 'Album thumbnail size (px)', 'cws_gpp'); ?></th>
								<td>
									<input type="number" name="cws_gpp_options[album_thumb_size]" value="<?php echo $options['album_thumb_size']; ?>" />
								</td>
							</tr>
							<tr>
								<th scope="row"><?php _e( 'Number of image results per page', 'cws_gpp'); ?></th>
								<td>
									<input type="number" name="cws_gpp_options[max_results]" value="<?php echo $options['max_results']; ?>" />
								</td>
							</tr>
							<tr>
								<th scope="row"><?php _e( 'Thumbnail size (px)', 'cws_gpp'); ?></th>
								<td>
									<input type="number" name="cws_gpp_options[thumb_size]" value="<?php echo $options['thumb_size']; ?>" />
								</td>
							</tr>
							<tr>
								<th scope="row"><?php _e( 'Lightbox image size (px)', 'cws_gpp'); ?></th>
								<td>
									<input type="number" name="cws_gpp_options[max_image_size]" value="<?php echo $options['max_image_size']; ?>" />
								</td>
							</tr>
							<tr>
								<th><?php _e( 'Page to show album results on', 'cws_gpp'); ?></th>
								<td>
									<?php
									$pages = cws_list_pages();
									?>
									<select style="width:240px;" name="cws_gpp_options[album_results_page]" id="">
										<option value=''><?php _e( 'Select a page:', 'cws_gpp'); ?></option>
										<?php							
										for( $i = 0, $num_pages = count( $pages ) - 1 ; $i <= $num_pages; $i++ ){
											?>
											<option value="<?php echo $pages[$i]['post_path']; ?>" <?php if ( $options['album_results_page'] == $pages[$i]['post_path'] ) { echo 'selected="selected"'; } ?>><?php echo $pages[$i]['post_title']; ?></option>								
											<?php
										}																					
										?>
									</select>							
									<p><small><?php _e( 'This is the page to place the shortcode, [cws_gpp_album_images]', 'cws_gpp'); ?></small></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php _e( 'Show Private galleries', 'cws_gpp'); ?></th>
								<td>
									<input type="checkbox" name="cws_gpp_options[inc_private]" value="1" <?php if ( $options['inc_private'] == 1) { echo 'checked'; } ?> />
									<small><?php _e( 'Delete cache after changing this setting!', 'cws_gpp'); ?></small>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php _e( 'Show album titles', 'cws_gpp'); ?></th>
								<td>
									<input type="checkbox" name="cws_gpp_options[show_album_ttl]"  value="1" <?php if ( $options['show_album_ttl'] == 1) { echo 'checked'; } ?> />
								</td>
							</tr>						
							<tr>
								<th scope="row"><?php _e( 'Show slider markers', 'cws_gpp'); ?></th>
								<td>
									<input type="checkbox" name="cws_gpp_options[show_slider_mrkrs]"  value="1" <?php if ( $options['show_slider_mrkrs'] == 1) { echo 'checked'; } ?> />
								</td>
							</tr>
							<tr>
								<th scope="row"><?php _e( 'Delete cache', 'cws_gpp'); ?></th>
								<td>
									<input id="delete_cache" type="button" name="cws_gpp_options[delete_cache]" value="<?php _e( 'Delete cache', 'cws_gpp'); ?>" />
								</td>
							</tr>
							<tr>
								<th scope="row"><?php _e( 'Enable FancyBox', 'cws_gpp'); ?></th>
								<td>
									<input type="checkbox" name="cws_gpp_options[enable_fancybox]" value="1" <?php if ( $options['enable_fancybox'] == 1) { echo 'checked'; } ?> />
									<p>
										<small><?php _e( 'Sometimes your theme will already include this FancyBox. If so you can disable FancyBox from being included by this plugin.', 'cws_gpp'); ?></p>
								</td>
							</tr>				
						</table>
						
					</div>
					
					<input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes', 'cws_gpp') ?>" />
				</div>				
			</div>
		</div>				
	</div>	
	<?php
}

/**
 *
 * Validate user input
 *
 */	
function cws_gpp_validate_options( $input ) {

	$errors = array();
	
	// $valid['max_album_results']     = esc_attr( $input['max_album_results'] );
	$valid['album_thumb_size']      = esc_attr( $input['album_thumb_size'] );
	$valid['max_results']           = esc_attr( $input['max_results'] );
	$valid['thumb_size']            = esc_attr( $input['thumb_size'] );
	$valid['max_image_size']        = esc_attr( $input['max_image_size'] );
	$valid['album_results_page']    = esc_attr( $input['album_results_page'] );		
	// $valid['inc_private']           =  $input['inc_private'] ;
	// $valid['show_album_ttl']        =  $input['show_album_ttl'] ;
	// $valid['show_slider_mrkrs']     =  $input['show_slider_mrkrs'] ;
	// $valid['enable_fancybox']     	=  $input['enable_fancybox'] ;
	
	// Correct validation of checkboxes
	$valid[inc_private] 		= ( isset( $input[inc_private] ) && true == $input[inc_private] ? true : false );
	$valid[show_album_ttl] 		= ( isset( $input[show_album_ttl] ) && true == $input[show_album_ttl] ? true : false );
	$valid[show_slider_mrkrs] 	= ( isset( $input[show_slider_mrkrs] ) && true == $input[show_slider_mrkrs] ? true : false );
	$valid[enable_fancybox] 	= ( isset( $input[enable_fancybox] ) && true == $input[enable_fancybox] ? true : false );
		
	// Validate numbers
	// Make sure Max Album Results is numeric
/*
	if( !is_numeric( $valid['max_album_results'] ) ) {
		$errors['max_album_results'] = 'Please enter a number for the number of albums to show on a page.';
	}
*/
			
	if( !is_numeric( $valid['album_thumb_size'] ) ) {
		$errors['album_thumb_size'] = 'Please enter a number in pixels for the album thumbnail size.';
	}	

	if( !is_numeric( $valid['max_results'] ) ) {
		$errors['max_results'] = 'Please enter a number for the number of results to show on a page.';
	}	

	if( !is_numeric( $valid['thumb_size'] ) ) {
		$errors['thumb_size'] = 'Please enter a number in pixels for the image thumbnail size.';
	}	

	if( !is_numeric( $valid['max_image_size'] ) ) {
		$errors['max_image_size'] = 'Please enter a number in pixels for image size in the lightbox (e.g. 600).';
	}			
	
	// Display all errors together
	// TODO: check this out
	if( count( $errors ) > 0 ) {
			
		$err_msg = '';
			
		// Display errors
		foreach( $errors as $err ) {
			$err_msg .= "$err<br><br>"; 
		}

		add_settings_error(
			'nap_gp_text_string',
			'cws_gpp_texterror',
			$err_msg,
			'error'
		);
	}
	return $valid;
}