<?php

define( 'GAL_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'GAL_PLUGIN_URI', plugins_url('/aj_google_auto_login_java/') );

global $opt_gal_client_id,$opt_gal_redirect_uri,$opt_gal_domain;
	
$opt_gal_client_id 		= '317464765252.apps.googleusercontent.com';
$opt_gal_client_secret	= 'WL9PLAgK7oiS8BM9RShZr61j';
$opt_gal_redirect_uri 	= 'http://myshala.com';
$opt_gal_domain 		= 'ajency.in';


require_once (GAL_PLUGIN_PATH.'/inc/widget/gal_login_widget.php');
require_once (GAL_PLUGIN_PATH.'/inc/register-login/process_login.php');

function gal_set_auth_defaults()
{
	global $opt_gal_client_id,$opt_gal_redirect_uri,$opt_gal_domain;
	
	$oauthurl 	= 'https://accounts.google.com/o/oauth2/auth?';
	
	$validurl	= 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=';
	
	$logouturl	= 'http://accounts.google.com/Logout';
	
	$scope		= 'scope='.urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email');
	$scope		.= apply_filters('gal_auth_scope', ''); //add additional scope parameters.
	
	$clientid	= '&client_id='.urlencode($opt_gal_client_id);
	
	$redirect	= '&redirect_uri='.	urlencode($opt_gal_redirect_uri);
	
	$type		= '&response_type='.urlencode('token');
	
	$approval_prompt = '&approval_prompt=auto';
	
	$domain		= ($opt_gal_domain)?'&hd='.urlencode($opt_gal_domain):'';
	
	$authurl	= $oauthurl.$scope.$clientid.$redirect.$type.$approval_prompt.$domain;
	?>
	  <script type="text/javascript">
	  	var WPSITEURL	= 	'<?php echo site_url();?>';
      	var VALIDURL    =   '<?php echo $validurl;?>';
      	var REDIRECT    =   '<?php echo $opt_gal_redirect_uri;?>';
        var _url        =  	'<?php echo $authurl;?>';
    	var acToken;
        var tokenType;
        var expiresIn;
        var user;
        var loggedIn    =   false;


        function galLogin() {

            var win     =   window.open(_url, "agcgoogleauth", 'width=800, height=600'); 
            
            var pollTimer   =   window.setInterval(function() { 
                console.log(win);
                console.log(win.document);
                console.log(win.document.URL);

                if (win.document.URL.indexOf(REDIRECT) != -1) {
                    window.clearInterval(pollTimer);
                    var url =   win.document.URL;
                    acToken =   gup(url, 'access_token');
                    tokenType = gup(url, 'token_type');
                    expiresIn = gup(url, 'expires_in');

                    win.close();
					jQuery('.gal-login-big-google').hide();
					jQuery('.gal-login-google').hide();
					jQuery('.gal-loader').show();
                    galValidateToken(acToken);
               }
            }, 500);
        }
        
      </script>  
	<?php 
}
add_action('wp_footer', 'gal_set_auth_defaults');//On Every Page
add_action('login_footer','gal_set_auth_defaults');//On Login page

/**
 * Function to enqueue the required scripts and styles for the the plugin to work.
 */
function gal_enqueue_scripts()
{
	//styles
	wp_enqueue_style('gal-login-style',GAL_PLUGIN_URI.'inc/css/gal_auto_login.css');
	
	//scripts
	wp_enqueue_script('gal-login-script',GAL_PLUGIN_URI.'inc/js/gal_login.js',array('jquery'),'',true);
	
	//Enqueue ajax url for wp login page
	wp_localize_script( 'gal-login-script', 'gal_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('wp_enqueue_scripts', 'gal_enqueue_scripts');//On Every page
add_action('login_enqueue_scripts', 'gal_enqueue_scripts');//On Login page

/**
 * Function to display the login button big
 */
function gal_login_button_big()
{
	$html 	 = '<div class="gal-login-big-google">';
	$html	.= '<a href="javascript:void(0);" onClick="galLogin();"></a>';
	$html	.=	'</div>';
	
	$nonce   = wp_create_nonce('gal_user_login_register'); 
	$html	.= '<input type="hidden" name="galnonce" id="galnonce" value="'.$nonce.'"/>';
	return $html;
}

//Extend the wp login form to include the google login button.
function gal_wp_login_form_ext()
{
	echo '<div class="gal-loader" style="display:none"><span class="gal-loader-text">Loggin In...Please Wait..</span></div>';
	echo gal_login_button_big();
}
add_action('login_form','gal_wp_login_form_ext');

/**
 * Function to override the default bp avatar with google avatar.
 */
function gal_bp_avatar($avatar, $params='',$item_id, $avatar_dir, $css_id, $html_width, $html_height, $avatar_folder_url, $avatar_folder_dir)
{
	//Default plugin avatar uri
	$default_avatar_uri =  GAL_PLUGIN_URI.'/inc/img/profilepic.png';
	$default_avatar		= '<img alt="' . esc_attr($params['alt']) . '" src="' . $default_avatar_uri . '" class="avatar gal-rounded_cr" '. $html_width . $html_height .'/>';

	//First, get the userid	
	global $comment;
	if (is_object($comment))	                        $user_id = $comment->user_id;
	if (is_object($params)) 	                        $user_id = $params->user_id;
	if (is_array($params) && $params['object']=='user') $user_id = $params['item_id'];
	if (!$user_id)                                      return $default_avatar;
	
    //Now that we have a userID, let's see if we have their facebook profile pic stored in usermeta.  If not, fallback on the default.
	if( $params['type'] == 'full' ) $gal_img = get_user_meta($user_id, 'gal_user_avatar', true);
	if( !$gal_img )                 $gal_img = get_user_meta($user_id, 'gal_user_avatar', true);
	if( !$gal_img )                 return $default_avatar;

	//If the usermeta doesn't contain an absolute path, prefix it with the path to the uploads dir
	if( strpos($gal_img, "http") === FALSE )
	{
	    $uploads_url = wp_upload_dir();
	    $uploads_url = $uploads_url['baseurl'];
	    $gal_img = trailingslashit($uploads_url) . $gal_img;
	}
	
    //And return the Facebook avatar (rather than the default WP one)
    return '<img alt="' . esc_attr($params['alt']) . '" src="' . $gal_img . '" class="avatar gal-rounded_cr" '. $html_width . $html_height .'/>';
}
add_filter( 'bp_core_fetch_avatar', 'gal_bp_avatar', 10, 9 );
