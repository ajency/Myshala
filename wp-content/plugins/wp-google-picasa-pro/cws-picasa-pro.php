<?php
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Plugin Name: Google Picasa Viewer Pro
 * Plugin URI: http://cheshirewebsolutions.com
 * Description: Add Google Picasa albums to your blog posts, pages and sidebar. With Carousel goodness and muchus more...
 * Version: 1.3.0
 * Author: Ian Kennerley - <a href='http://twitter.com/CheshireWebSol'>@CheshireWebSol</a> on twitter
 * Author URI: http://cheshirewebsolutions.com
 * Author Email: hello@cheshirewebsolutions.com
 * License: GPLv2
 * 
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

/** Add Zend Library to include path ****************************************/
set_include_path ( realpath( dirname( __FILE__ )) . '/zend/library' );


if ( ! class_exists( 'CWS_WPPicasaPro' ) ) {

class CWS_WPPicasaPro {

	/** Debug ***************************************************************/
	public $debug = TRUE;

	// var $zend_loader_present_flag 	= FALSE;
	var $preflight_errors 			= array();

	/** Version *************************************************************/	

	var $plugin_version;
		
	/** URLS ****************************************************************/
	
    var $plugin_path;
    var $plugin_url;
    
	/**
	 * CWS_WPPicasaPro Constructor
	 *
	 * Let's get this party started!
	 */
    function __construct() 
    {	
		if( $this->debug ) error_log( 'Inside: CWS_WPPicasaPro::__construct()' );

        define( 'WPPICASA_PLUGIN_PATH', plugin_dir_path(__FILE__) );
		define( 'WPPICASA_PLUGIN_URL', 	WP_PLUGIN_URL.'/'.str_replace( basename( __FILE__ ),"",plugin_basename( __FILE__ ) ) );

		// Pre Flight Check...
		$this->check_zend_loader();
		$this->is_cache_writable();

        // Set up activation hooks
        // register_activation_hook( __FILE__, array( &$this, 'activate' ) );
        // register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );
        
        // Set up locale
        load_plugin_textdomain( 'cws_gpp', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );        
        
        // Add your own hooks/filters
        // add_action( 'init', array( &$this, 'init' ) );
        
		// Include required files
		$this->includes();
        
		// Add shortcode support for widgets
		if ( ! is_admin() ){ add_filter( 'widget_text', 'do_shortcode', 11 ); }
		
		add_action( 'wp_print_scripts', array( &$this,'add_header_scripts') );
		add_action( 'wp_print_styles', array( &$this,'add_header_styles') );

		// Add ajax hooks for pulling in next page of images			
		add_action( 'wp_ajax_getImages', array( &$this, 'cws_gpp_process_ajax_images' ) );
		add_action( 'wp_ajax_nopriv_getImages', array( &$this, 'cws_gpp_process_ajax_images' ) );
		
		// Add ajax hooks for albums
		add_action( 'wp_ajax_getAlbumImages', array( &$this, 'cws_gpp_process_ajax_albums' ) );
		add_action( 'wp_ajax_nopriv_getAlbumImages', array( &$this, 'cws_gpp_process_ajax_albums' ) );			
		
		// Add ajax hook for deleting cache
		add_action( 'wp_ajax_deleteCache', array( &$this, 'cws_gpp_delete_cache' ) );
		add_action( 'wp_ajax_nopriv_deleteCache', array( &$this, 'cws_gpp_delete_cache' ) );				
				
		register_activation_hook( __FILE__, array(  &$this, 'cws_gpp_activate' ) );        
    }
    
	/**
	 *
	 *  Check Zend Loader is available.
	 *
	 */	    
    function check_zend_loader() {    
    
    	if( $this->zend_loader_present = @fopen( 'Zend/Loader.php', 'r', true ) ) {
    		return TRUE;
    	}
    	else {
    		$this->preflight_errors[] = 'Exception thrown trying to ' .
				'access Zend/Loader.php using \'use_include_path\' = true ' .
				'Make sure you include the Zend Framework in your ' .
				'include_path which currently contains: "' .
				ini_get('include_path') . '"';    		
    		
    		return FALSE;
    	}
    }
    
	/**
	 *
	 *  Check 'cache' is writable (attempt to make? )
	 *	Used for user feedback in settings page
	 *
	 */		
	public function is_cache_writable() {
	
		if( ! is_writable( WPPICASA_PLUGIN_PATH . '/cache/' ) ) {
			if( $this->debug ) error_log( 'Inside: CWS_WPPicasaPro::is_cache_writable()  Cache folder is NOT writable.'  . WPPICASA_PLUGIN_PATH . '/cache/' );
			$this->preflight_errors[] = 'Cache folder is NOT writable.' . $this->plugin_path . '/cache/';
			return FALSE;
		}
		else {
			if( $this->debug ) error_log( 'Inside: CWS_WPPicasaPro::is_cache_writable()  Cache folder is writable.' );	
			return TRUE;				
		}		
	} 	    
    
/*
    function init()
    {
          
    }
 
    function activate( $network_wide ) 
    {
        
    }
    
    
    function deactivate( $network_wide ) 
    {
        
    }
*/

	/**
	 *
	 * Create Default Settings
	 *
	 */	 
	function cws_gpp_activate() {
		if( $this->debug ) error_log( 'Inside: CWS_WPPicasaPro::cws_gpp_activate()' );

        $cws_gpp_options = array(
        							'max_results' => 7,
        							'max_album_results' => 3,
        							'album_thumb_size' => 150,
        							'thumb_size' => 150,
        							'max_image_size' => 600,
        							'enable_fancybox' => 1
        						);
        						
		update_option( 'cws_gpp_options', $cws_gpp_options );            						
    }

	/**
	 *
	 * Include required core files
	 *
	 */
	function includes() {
		if( $this->debug ) error_log( 'Inside: CWS_WPPicasaPro::includes()' );
		
		if ( is_admin() ) $this->admin_includes();
		
		include_once( 'classes/cws-gpp-api.php' );
		include_once( 'cws-gpp-functions.php' );										// TODO: split file out in admin and non-admin functions
		include_once( 'shortcodes/shortcode-init.php' );								// Init the shortcodes
		include_once( 'widgets/widget-init.php' );										// Widget classes		
	}

	/**
	 *
	 * Include required Admin files
	 *
	 */	 
	function admin_includes() {
		if( $this->debug ) error_log( 'Inside: CWS_WPPicasaPro::admin_includes()' );

		include_once( 'admin/cws-gpp-admin-init.php' );									// Admin section
		include_once( 'cws-gpp-functions.php' );										// TODO: split file out in admin and non-admin functions
		include_once( 'classes/cws-gpp-admin.php' ); 									// does this need including here?
	}

	/**
     *
     * Enqueue front-end scripts
     *
     */	
	public function add_header_scripts() {
		if( $this->debug ) error_log( 'Inside: CWS_WPPicasaPro::add_header_scripts()' );
				
		if ( ! is_admin() ) {	
		
			if( function_exists( 'wp_register_script' ) ) {
				
				$options = get_option( 'cws_gpp_options' );			
								
				// Load FancyBox
				if( $options['enable_fancybox'] == 1 )  {
					wp_register_script( 'cws_gpp_albums_fb', WPPICASA_PLUGIN_URL . 'fancybox/jquery.fancybox-1.3.4.js', array('jquery'),1, true );
				}
								
				wp_register_script( 'cws_gpp_albums_js', WPPICASA_PLUGIN_URL . 'js/base.js', array('jquery'),1, true );											// Load main js file
				wp_localize_script( 'cws_gpp_albums_js', 'cws_gpp_', array( 'siteurl' => get_option( 'siteurl' ), 'pluginurl' => WPPICASA_PLUGIN_URL ));		// Allows plugin location to be referenced in js files
				wp_register_script( 'cws_gpp_albums_infcar', WPPICASA_PLUGIN_URL . 'js/infiniteCarousel.js', array('jquery'),"1.6.1", true );					// Load Inifinite carousel jQuery Plugin
				wp_register_script( 'cws_gpp_albums_infcarsetup', WPPICASA_PLUGIN_URL . 'js/ic_setup.js', array('jquery', 'cws_gpp_albums_infcar'),1, true);	// Load Infinite Carousel setup file
				wp_register_script( 'cws_gpp_albums_jqBlock', WPPICASA_PLUGIN_URL . 'js/jquery.blockUI.js', array('jquery'),1, true);							// Load jquery.blockUI.js								
				
				//
				wp_register_script( 'cws_gpp_albums_slideshow', WPPICASA_PLUGIN_URL . 'js/slideshow.js', array('jquery'),1, true);
				wp_register_script( 'cws_gpp_slideshow_init', WPPICASA_PLUGIN_URL . 'js/init.js', array('jquery'),1, true);
				
				if( function_exists( 'wp_enqueue_script' ) ) {
				
					if( $options['enable_fancybox'] == 1 ) {
						wp_enqueue_script( 'cws_gpp_albums_fb' );
					}
					
					wp_enqueue_script( 'cws_gpp_albums_js' );						
					wp_enqueue_script( 'cws_gpp_albums_infcar' );
					wp_enqueue_script( 'cws_gpp_albums_infcarsetup' );
					wp_enqueue_script( 'cws_gpp_albums_jqBlock' );
					
					wp_enqueue_script( 'cws_gpp_albums_slideshow' );
					wp_enqueue_script( 'cws_gpp_slideshow_init' );												
				}
			}
		}
	}
		
	/**
     *
     * Enqueue front-end styles
     *
     */	
	public function add_header_styles() {
		if( $this->debug ) error_log( 'Inside: CWS_WPPicasaPro::add_header_styles()' );

		if ( ! is_admin() ) {	
			
			if( function_exists( 'wp_register_style' ) ) {
				
				$options = get_option( 'cws_gpp_options' );
				
				wp_register_style( 'cws_gpp_albums_fbcss', plugins_url( 'fancybox/jquery.fancybox-1.3.4.css', __FILE__ ), '', $this->plugin_version );
				wp_register_style( 'cws_gpp_albums_stylecss', plugins_url( 'css/style.css',__FILE__ ) , '', $this->plugin_version );

				if ( function_exists( 'wp_enqueue_style' ) ) {
				
					// Load FancyBox
					if( $options['enable_fancybox'] == 1 ) {
						wp_enqueue_style( 'cws_gpp_albums_fbcss' );
					}
					
					wp_enqueue_style( 'cws_gpp_albums_stylecss' );
				}
			}
		}
	}

	/**
	 *
	 *	Display images in album using ajax
	 *
	 */
	public function album_images_ajax($AlbumId) {
		if( $this->debug ) error_log( 'Inside: CWS_WPPicasaPro::album_images_ajax()' );	

		// Get prefs from database
		$options = get_option( 'cws_gpp_options' );

		// Create instance of GoogleAPI class
		$my_picasa = new CWS_GPPApi( );

		$atts='';			
			
		// Extract the attributes into variables
		extract( shortcode_atts( array(
			'max_results' 		=> $options['max_results'],
			'thumb_size' 		=> $options['thumb_size'],
			'album_thumb_size'	=> $options['album_thumb_size'], 
			'max_results' 		=> $options['max_results'], 
			'max_image_size' 	=> $options['max_image_size'], 
		), $atts ) );
																
		return $my_picasa->get_images_display_ajax( $AlbumId, $thumb_size, $max_image_size, $max_results, $page = 1 );
	}
		
	/**
	 *
	 *	Called by ajax via hook
	 *
	 */	
	function cws_gpp_process_ajax_images( ) {
		if( $this->debug ) error_log( 'Inside: CWS_WPPicasaPro::cws_gpp_process_ajax_images()' );	
		
		// Get the data from ajax() call  
		$album_id 	= $_POST['AlbumId']; 
		$page 		= $_POST['page'];
		
		$images 	= $this->images_ajax( $album_id, $page );
				
		// Return the String  
		die( $images ); 
	}
	
	/**
	 *
	 *	Display images using ajax
	 *
	 */
	public function images_ajax( $AlbumId, $page ) {
		if( $this->debug ) error_log( 'Inside: CWS_WPPicasaPro::images_ajax()' );	
		
		// Get prefs from database
		$options = get_option( 'cws_gpp_options' );

		// Create instance of GoogleAPI class
		$my_picasa = new CWS_GPPApi( );
			
		$atts='';			
			
		// Extract the attributes into variables
		extract( shortcode_atts( array(
			'max_results' 		=> $options['max_results'],
			'thumb_size' 		=> $options['thumb_size'],
			'album_thumb_size'	=> $options['album_thumb_size'], 
			'max_results' 		=> $options['max_results'], 
			'max_image_size' 	=> $options['max_image_size'], 
		), $atts ) );
																								
		return $my_picasa->get_images_display_ajax( $AlbumId, $thumb_size, $max_image_size, $max_results, $page );
	}

	/**
	 *
	 *	Called by ajax via hook
	 *
	 */		
	function cws_gpp_process_ajax_albums() {
		if( $this->debug ) error_log( 'Inside: CWS_WPPicasaPro::cws_gpp_process_ajax_albums()' );	

		// Get the data from ajax() call  
		$album_id = $_POST['AlbumId']; 
		
		$images = $this->album_images_ajax( $album_id, $page = 1 );		
		
		// Return the String  
		die( $images ); 
	}
	
	/**
	 *
	 * Delete cache
	 *
	 */
	function cws_gpp_delete_cache( )
	{
		if( $this->debug ) error_log( 'Inside: CWS_WPPicasaPro::cws_gpp_delete_cache()' );	
		
		$dirname = WPPICASA_PLUGIN_PATH . 'cache';
		
		if( $this->debug ) error_log( 'Delete ALL files in: ' . $dirname );
		
		$dir = opendir( $dirname );
		
		while( false !== $entry = readdir( $dir ) ) {
			if( $entry == '.' || $entry == '..' ) continue;
			if( is_file( "$dirname/$entry" ) ) unlink( "$dirname/$entry" );
		}
		
		closedir( $dir );
	}

}

}

$wpPicasaPro = new CWS_WPPicasaPro();
$wpPicasaPro->plugin_version = '1.3.0';
?>