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

$WPPicasaPro = new CWS_WPPicasaPro(); // Can this be passedin [globals] ?? is that why console is diplaying repeated logs?!?!

/**
 *
 *  Deals with Admin side of things. OAuth
 *
 */	
class CWS_GPPAdmin {

	var $debug = TRUE;
	var $errors = array();
	
	var $request_token;
	var $access_token;
	var $consumer;

	var $consumer_key;
	var $consumer_secret;
	var $return_to;
	var $scopes;
	var $oauth_options;
	var $approval_url;
	
	var $zend_loader_present = FALSE;
	

    function __construct( $preflight_check ) 
    {	
		if( $this->debug ) error_log( 'Inside: CWS_GPPAdmin::__construct()' );

        if( count( $preflight_check) )  {
        	// Moved this fn to cws-gpp-functions.php
        	showAdminMessages( $preflight_check );
        	return;
        }
        
        include_once 'Zend/Loader.php';

		Zend_Loader::loadClass( 'Zend_Gdata_HttpClient' );
		Zend_Loader::loadClass( 'Zend_Gdata_Photos' );
		Zend_Loader::loadClass( 'Zend_Oauth_Consumer' );
		Zend_Loader::loadClass( 'Zend_Http_Client' );
		Zend_Loader::loadClass( 'Zend_Gdata_Gbase' );
        
        // Get tokens from db
        $this->request_token 	= get_option( 'CWS_GA_REQUEST_TOKEN' );
        $this->access_token		= get_option( 'CWS-GA-ACCESS_TOKEN' );        
		$this->consumer_key 	= 'anonymous';							// Pass anonymous because we want to grab an anonymous token.
		$this->consumer_secret  = 'anonymous';
		$this->return_to 		= cws_get_admin_url('/options-general.php') . '?page=cws_gpp&cws_oauth_return=true' ;
		$this->scopes = array( 'https://picasaweb.google.com/data/',);	// Define areas of Google Account you require access to.

		// Prepare array for OAuth request
		try{
			$this->oauth_options = array(
										  'requestScheme'        => Zend_Oauth::REQUEST_SCHEME_HEADER,
										  'version'              => '1.0',
										  'consumerKey'          => $this->consumer_key,
										  'consumerSecret'       => $this->consumer_secret,
										  'signatureMethod'      => 'HMAC-SHA1',
										  'callbackUrl'          => $this->return_to,
										  'requestTokenUrl'      => 'https://www.google.com/accounts/OAuthGetRequestToken',
										  'userAuthorizationUrl' => 'https://www.google.com/accounts/OAuthAuthorizeToken',
										  'accessTokenUrl'       => 'https://www.google.com/accounts/OAuthGetAccessToken'
										  );		
		}
		catch ( Zend_Gdata_App_Exception $ex ) {
			$this->errors[] =  $ex->getMessage();
			error_log( 'Error: ' . $ex );
			
			return;
		}

		try{
			$this->consumer = new Zend_Oauth_Consumer( $this->oauth_options );
		}
		catch ( Zend_Gdata_App_Exception $ex ) {
			$this->errors[] =  $ex->getMessage();		
			error_log( 'Error: ' . $ex );
		}
    }
 
	/**
	 *
	 * Check if we are already authenticated.
	 *
	 */
    function is_authenticated() {
    	if( $this->debug ) error_log( 'Inside: CWS_GPPAdmin::is_authenticated()' );
    	
    	// If not empty assume we are authenticated.
    	$this->access_token = $this->get_access_token();
    	
    	if( ! empty( $this->access_token ) ) return TRUE;
    	
    	// Create request token
    	// TODO: put hook check here to make sure we are on out plugin page so we play nicely with other plugins using OAuth (Yoast, Backup to Dropbox etc)
    	if( empty( $request_token ) ) {
    		if( $this->debug ) error_log( 'Inside: CWS_GPPAdmin::is_authenticated() - $request_token is empty.' );
			
			$request_token = $this->generate_request_token();
			
			if( ! empty( $request_token ) ) {
				// Save in database
				// add_option( 'CWS_GA_REQUEST_TOKEN', $request_token );	
				update_option( 'CWS_GA_REQUEST_TOKEN', $request_token );
			}    	
    	}
    	return FALSE;
    }

	/**
	 *
	 * Return serialized array.
	 *
	 */	
    function get_request_token() {
    	return $this->request_token;
    }

	/**
	 *
	 * Return serialized array.
	 *
	 */	    
    function get_access_token() {
    	return $this->access_token;
    }
 
 	/**
	 *
	 * Return serialized request token.
	 *
	 */	 
	function generate_request_token() {
		if( $this->debug ) error_log( 'Inside: CWS_GPPAdmin::generate_request_token' );
				
		return serialize( $this->consumer->getRequestToken( array( 'scope' => implode(' ', $this->scopes) ) ) );
	}    

	/**
	 *
	 * Return Grant Link. TODO: make better link, with para explaining what's about to happen.
	 *
	 */    
    function get_grant_link() {
		if( $this->debug ) error_log( 'Inside: CWS_GPPAdmin::get_grant_link' );

		$this->approval_url = $this->consumer->getRedirectUrl( array( 'hd' => 'default' ) );
		$login_label = __( 'Start the Login Process', 'cws_gpp');
		return "<a href=\"$this->approval_url\">$login_label</a>";
    }  
}