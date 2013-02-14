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

class CWS_GPPApi {

	var $debug = TRUE;

    // var $plugin_path;	// Pulled these out as CONSTANTS defined in cws-picasa-pro.php
    // var $plugin_url;
	var $client;
	var $request_token;
	var $access_token;
	var $consumer;
	var $consumer_key;
	var $consumer_secret;
	var $return_to;
	var $scopes;
	var $oauth_options;
	var $approval_url;
	var $zend_loader_present = false;
	var $errors = array();
	
	function __construct()
	{
		if( $this->debug ) error_log( 'Inside: CWS_GPPApi::__construct()' );

		require_once 'Zend/Loader.php';
		
		Zend_Loader::loadClass( 'Zend_Gdata_HttpClient' );
		Zend_Loader::loadClass( 'Zend_Gdata_Photos' );
		Zend_Loader::loadClass( 'Zend_Oauth_Consumer' );
		Zend_Loader::loadClass( 'Zend_Http_Client' );
		Zend_Loader::loadClass( 'Zend_Gdata_Gbase' );
		Zend_Loader::loadClass( 'Zend_Cache' );
		Zend_Loader::loadClass( 'Zend_Gdata_Photos_UserQuery' );
		
        // Get tokens from db
        $this->request_token 	= get_option( 'CWS_GA_REQUEST_TOKEN' );
        $this->access_token		= get_option( 'CWS-GA-ACCESS_TOKEN' );        
		$this->consumer_key 	= 'anonymous';										// Pass anonymous because we want to grab an anonymous token.
		$this->consumer_secret  = 'anonymous';
		$this->return_to 		= cws_get_admin_url('/options-general.php') . '?page=cws_gpp&cws_oauth_return=true' ;
		$this->scopes 			= array( 'https://picasaweb.google.com/data/',);	// Define areas of Google Account you require access to.

		// Prepare array
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
		
		$this->consumer 			= new Zend_Oauth_Consumer( $this->oauth_options );	
	}

	/**
	 *
	 *	Generate List of Albums
	 *
	 */	
	public function get_album_list( $album_thumb_size, $show_private = FALSE, $client ) {	
		if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_album_list()' );

		try	{				
			// Setup Zend Cache
			$frontendOptions = array( 'lifetime' => 60, 'automatic_serialization' => true ); 
			$backendOptions  = array( 'cache_dir' => WPPICASA_PLUGIN_PATH . 'cache/' ); 
			$cache = Zend_Cache::factory( 'Core', 'File', $frontendOptions, $backendOptions );
			
			// If we don't have cached version, grab em from Google
			if( ( $result = $cache->load( 'albums' ) ) === false ) {
				if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_album_list() - This one is from google servers.' );
								
				$this->service = new Zend_Gdata_Photos( $client );								
								
				/*
				// Retrieve public only albums
				if( $show_private != TRUE ) {
					$this->service = new Zend_Gdata_Photos( $client );
					if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_album_list() - Do not show private albums.' );
				}
				// Retrieve Private albums and Public
				else {
					$this->service = new Zend_Gdata_Photos( $client );
					if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_album_list() - Show private albums.' );
				}
				*/
			}
			// Grab it from cache
			else {
				if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_album_list() - This one is from cache.' );
			
				$cache->save( $result, 'albums' );
				return $result;					 
			}

		} 
		catch( Zend_Gdata_App_Exception $ex ) {
			
			$this->errors[] =  $ex->getMessage();
			$this->get_errors( $this->errors );
		}			
		
		try {
		
			$query = new Zend_Gdata_Photos_UserQuery();
		
		} catch( Zend_Gdata_App_Exception $ex ) {
			
			$this->errors[] =  $ex->getMessage();
			$this->get_errors( $this->errors );
		}
		
		
		/**
		 *
		 * Construct the query
		 *
		 */
		$query->setUser( 'default' );
	    $query->setThumbSize( $album_thumb_size . 'c' ); // append 'c' to crop image
	    
	    // Private / public	    
	    if( $show_private != TRUE ) $query->setAccess('public');
	    
	    // $query->setMaxResults( $max_album_results );
	    		
		try	{
			$userFeed = $this->service->getUserFeed( null, $query );
		} 
		catch( Zend_Gdata_App_Exception $ex ) {
			$this->errors[] =  $ex->getMessage();
			$this->get_errors( $this->errors );
		}
		
		if( count( $this->errors ) > 0 ) {
			// Display any errors in error log.
			$this->get_errors( $this->errors );
			return false;			
		} 
		else { 
			// Cache: save result in cache
			$cache->save( $userFeed, 'albums' );		
			return $userFeed;	
		}
	}

	/**
	 *
	 *	Format Display List of Albums
	 *	TODO: Pass in an an options array...
	 *
	 */		
	public function get_album_display( 	$album_thumb_size, 
										$resultsPage, 
										$show_albums = null, 
										$show_private = FALSE, 
										$show_album_ttl = null, 
										$show_slider_mrkrs = null  
									) 
	{			
		
		if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_album_display()' );
		
		// Bit of toughening
		if( ! is_numeric( $album_thumb_size ) )  $album_thumb_size 		= $this->album_thumb_size;

		// Grab Google OAuth tokens
		$this->access_token 	= unserialize( $this->access_token );
		$this->client 			= $this->access_token->getHttpClient( $this->oauth_options );
		
		// Prepend slash to results page
		if( $resultsPage != '' ? $resultsPage = '/' . $resultsPage : $resultsPage );				
		
		$get_album_list 	= $this->get_album_list( $album_thumb_size, $show_private, $this->client );						
						
		// TODO: comment this
		if( $get_album_list ) {
		
			try	{
				$numResults = $get_album_list->getTotalResults()->text;
			}
			catch( Zend_Gdata_App_Exception $ex ) {
				
				$this->errors[] =  $ex->getMessage();
				$this->get_errors( $this->errors );
			}
		
			// $get_album_list->getTitle();
		
			$html[] = '<div id="nak-gpv" class="grid-container">';
			$html[] = '<div class="grid">';

			$html[] = '<ul>';
			
			$bloginfo = get_bloginfo('url' ) ;
			$resultsPage = $bloginfo . $resultsPage;							
						
			// TODO: comment this						
			foreach( $get_album_list as $album ) {
			
				if ( $album instanceof Zend_Gdata_Photos_AlbumEntry ) {

					$thumbnail = $album->getMediaGroup()->getThumbnail();
					$title = $album->getMediaGroup()->getTitle();

					if ( count ( $show_albums ) ) {
					
						/*
						echo '<pre>';
						echo($album->getGphotoId());
						echo '</pre>';	
						*/					
						// If album id in results has been specified in the shortcode
						// by the user, formulate display code, just for those albums
						// include Title if $show_album_ttl is true
						if ( in_array( $album->getGphotoId(), $show_albums) ) {
						
							if( $show_album_ttl ){
							
								$html[] = '<li id="album-' . $album->getGphotoId()  
								. '"><a title="' . $title . '" href="' . $resultsPage . '?album_id=' . $album->getGphotoId()  
								. '"><img alt="' . $title . '" src="' . $thumbnail[0]->getUrl() . '"></a><br />' . $title . '</li>';								
						
							}
							else{
								$html[] = '<li id="album-' . $album->getGphotoId()  
								. '"><a title="' . $title . '" href="' . $resultsPage . '?album_id=' . $album->getGphotoId()  
								. '"><img alt="' . $title . '" src="' . $thumbnail[0]->getUrl() . '"></a></li>';						
							}
					
						}
					
					}
					// Formulate display code for all albums
					// include Title if $show_album_ttl is true								
					else {
					
						if($show_album_ttl){	
									
							$html[] = '<li id="album-' . $album->getGphotoId()  
							. '"><a title="' . $title . '" href="' . $resultsPage . '?album_id=' . $album->getGphotoId()  
							. '"><img alt="' . $title . '" src="' . $thumbnail[0]->getUrl() . '"></a><br /><span>' . $title . '</span></li>';
																									
						}
						else{
							$html[] = '<li id="album-' . $album->getGphotoId()  
							. '"><a title="' . $title . '" href="' . $resultsPage . '?album_id=' . $album->getGphotoId()  
							. '"><img alt="' . $title . '" src="' . $thumbnail[0]->getUrl() . '"></a></li>';								
						}
					
					}

				}
			}
			$html[] = '</ul>';
			$html[] = '</div>';
			$html[] = '</div>';
								
			return implode("\n", $html);
		}
	}
			
	/**
	 *
	 * Format Display List of Albums Carousel
	 * TODO: Pass in an an options array...	 
	 *
	 */
	public function get_album_carousel_display(	$album_thumb_size, 
												$resultsPage, 
												$show_albums = null, 
												$show_private = null, 
												$show_album_ttl = null, 
												$show_slider_mrkrs = null ) 
	{			
		if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_album_carousel_display()' );		
		
		if( ! is_numeric( $album_thumb_size ) )  $album_thumb_size 	= $this->album_thumb_size;

		// Grab Google OAuth tokens
		$this->access_token 	= unserialize( $this->access_token );
		$this->client 		= $this->access_token->getHttpClient( $this->oauth_options );
		
		// Prepend slash to results page
		if( $resultsPage != '' ? $resultsPage = '/' . $resultsPage : $resultsPage );
		
		// Get the album feed
		$get_album_list 	= $this->get_album_list( $album_thumb_size, $show_private, $this->client );
										
		if( $get_album_list ) {
			try	{
				$numResults = $get_album_list->getTotalResults()->text;
			}
			catch( Zend_Gdata_App_Exception $ex ) {
				
				$this->errors[] =  $ex->getMessage();
				$this->get_errors( $this->errors );
			}				
		
			$html[] = '<div class="nak-gpv carouselWrapper">';
			
			if( $show_slider_mrkrs == 1 ){
				$html[] = '<div class="slider_navigation"></div>';
			}
							
			$html[] = '<div id="nak-gpv" class="grid-container infiniteCarousel">';
			$html[] = '<div class="grid wrapper">';
			
			$bloginfo = get_bloginfo( 'url' ) ;
			$resultsPage = $bloginfo . $resultsPage;
			
			$html[] = '<ul>';
						
			foreach( $get_album_list as $album ) {
			
				if ( $album instanceof Zend_Gdata_Photos_AlbumEntry ) {

					$thumbnail = $album->getMediaGroup()->getThumbnail();
					$title = $album->getMediaGroup()->getTitle();
														
					if ( count ( $show_albums ) ) {
					
						// If album id in results has been specified in the shortcode
						// by the user, formulate display code, just for those albums
						// include Title if $show_album_ttl is true						
						if ( in_array( $album->getGphotoId(), $show_albums) ) {

							if( $show_album_ttl ) {
							
							$html[] = '<li id="album-' . $album->getGphotoId()  
								. '"><a title="' . $title . '" href="' . $resultsPage . '?album_id=' . $album->getGphotoId()  
								. '" id="albumThumb"><img alt="' . $title . '" src="' . $thumbnail[0]->getUrl() . '"></a>' . $title . '</li>';									
							}
							else {
							$html[] = '<li id="album-' . $album->getGphotoId()  
								. '"><a title="' . $title . '" href="' . $resultsPage . '?album_id=' . $album->getGphotoId()  
								. '" id="albumThumb"><img alt="' . $title . '" src="' . $thumbnail[0]->getUrl() . '"></a></li>';									
							}
						}
					}
					// Formulate display code for all albums
					// include Title if $show_album_ttl is true								
					else {
						if( $show_album_ttl ) {
						
						$html[] = '<li id="album-' . $album->getGphotoId()  
							. '"><a title="' . $title . '" href="' . $resultsPage . '?album_id=' . $album->getGphotoId()  
							. '" id="albumThumb"><img alt="' . $title . '" src="' . $thumbnail[0]->getUrl() . '"></a>' . $title . '</li>';									
						}
						else {
						$html[] = '<li id="album-' . $album->getGphotoId()  
							. '"><a title="' . $title . '" href="' . $resultsPage . '?album_id=' . $album->getGphotoId()  
							. '" id="albumThumb"><img alt="' . $title . '" src="' . $thumbnail[0]->getUrl() . '"></a></li>';									
						}
					}										
				}
			}
			
			$html[] = '</ul>';
			$html[] = '</div>';	// End grid-container							
			$html[] = '</div>'; // End grid wrapper
			$html[] = '<div class="images"></div>';
			$html[] = '</div>'; // end carouselWrapper

			return implode("\n", $html);				
		}
	}			

	/**
	 * 
	 * Generate image display ready for loading via ajax
	 *
	 * $album_id, int
	 * $thumb_size, int
	 * $max_image_size, int
	 * $max_results, int
	 * $page,int
	 * 
	 * return str
	 */
	public function get_images_display_ajax( $album_id, $thumb_size, $max_image_size, $max_results, $page = 1  ) {	
		if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_images_display_ajax()' );		
		
		if( ! is_numeric( $max_image_size ) ) 	$max_image_size = $this->max_image_size;
		if( ! is_numeric( $thumb_size ) )  		$thumb_size = $this->thumb_size;
		if( ! is_numeric( $max_results ) )		$max_results = $this->max_results;
					
		// Check we have an album id
		if( isset( $album_id ) ) 
		{
			if( $album_feed = $this->get_album_image_list( $album_id, $thumb_size, $max_image_size, $max_results, $page ) ) {
					
				// If not set then use default value
				if( $max_results != '' ? $max_results: $max_results = $this->max_results );
									
				$album_name = $album_feed->getTitle();
									
				// For pagination	
				$numResults = $album_feed->getTotalResults()->text;
				$num_pages 	= ceil( $numResults / $max_results );
			
				$html[] = '<div id="nak-gpv" class="grid-container">';
			    $html[] = "<h2>$album_name</h2>";
				$html[] = '<div class="grid">';
				
				foreach( $album_feed as $photo_entry ) {
			    
			        if( $photo_entry->getMediaGroup()->getContent() != null ) {			        
						$media_content_array = $photo_entry->getMediaGroup()->getContent();
						$content_url 		 = $media_content_array[0]->getUrl();
			        }
			
			        if( $photo_entry->getMediaGroup()->getThumbnail() != null ) {
						$media_thumbnail_array = $photo_entry->getMediaGroup()->getThumbnail();			
						$title = $photo_entry->getSummary();
						
						try	{
							$thumbnail_url = $media_thumbnail_array[0]->getUrl();							
						} 
						catch( Zend_Gdata_App_Exception $ex ) {						
							$this->errors[] = $ex->getMessage();
							$this->get_errors( $this->errors );															    
						}
				        
				        $html[] = '<a class="grouped_elements" title="' . $title . '" rel="' . $album_id . '" href="' . $content_url . '"><img width="'.$thumb_size.'" height="'.$thumb_size.'" class="displayed" src="' . $thumbnail_url . '" /></a>';
			        }		    	
			    }	    
			    
			    $html[] = '</div>';
			    				    
				if ( $num_pages > 1 ) {
					$html[] = $this->get_pagination( $num_pages, $page, $album_name, $album_id );
				}
			    
			    $html[] = '</div>';
			    				    
				if( count( $this->errors ) > 0 ) {					
					// Display any errors in error log.
					$this->get_errors( $this->errors );
					return false;
					
				} 
				else {				
					return implode( "\n", $html );						
				}
			} 
			else {
				error_log( 'We had a problem, maybe not a valid image id for this album.' );
			}
		}
	}		
	
	/**
	 *
	 *	Format Display List of Images in Specific Album
	 *
	 * $album_id, int
	 * $thumb_size, int
	 * $max_image_size, int
	 * $max_results, int
	 * $page,int
	 * 
	 * return string		 
	*/
	public function get_album_images_display( $album_id, $thumb_size, $max_image_size, $max_results, $page ) {	
		if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_album_images_display()' );		
															
		if( ! is_numeric( $max_image_size ) )	$max_image_size = $this->max_image_size;
		if( ! is_numeric( $thumb_size ) ) 		$thumb_size 	= $this->thumb_size;
		if( ! is_numeric( $max_results ) ) 		$max_results 	= $this->max_results;
	
		if( $album_feed = $this->get_album_image_list( $album_id, $thumb_size, $max_image_size, $max_results, $page ) ) {			
			// If not set then use default value
			if( $max_results != '' ? $max_results: $max_results = $this->max_results );
								
			$album_name = $album_feed->getTitle();
								
			// For pagination	
			$numResults = $album_feed->getTotalResults()->text;
			$num_pages 	= ceil( $numResults / $max_results );
		
			// TODO: Rename	carouselWrapper to be more semantic now it is used to display images as well as in the carousel!!!
			$html[] = '<div class="carouselWrapper">';
			$html[] = '<div id="nak-gpv" class="grid-container images">';
		    $html[] = "<h2>$album_name</h2>";
			$html[] = '<div class="grid">';
			$html[] = '<ul>';
			
			foreach( $album_feed as $photo_entry ) {
		    
		        if( $photo_entry->getMediaGroup()->getContent() != null ) {
					$media_content_array = $photo_entry->getMediaGroup()->getContent();
					$content_url 		 = $media_content_array[0]->getUrl();
		        }
		
		        if( $photo_entry->getMediaGroup()->getThumbnail() != null ) {
					$media_thumbnail_array = $photo_entry->getMediaGroup()->getThumbnail();			
					$title = $photo_entry->getSummary();
					
					try	{
						$thumbnail_url = $media_thumbnail_array[0]->getUrl();
						
					} 
					catch( Zend_Gdata_App_Exception $ex ) {
						$this->errors[] = $ex->getMessage();
						$this->get_errors( $this->errors );
					}
			        
			        $html[] = '<li><a class="grouped_elements" title="' . $title . '" rel="' . $album_id . '" href="' . $content_url . '"><img class="displayed" src="' . $thumbnail_url . '" /></a></li>';
		        }		    	
		    }	    
		    				    
		    $html[] = '</ul>';				    				    				    
		    $html[] = '</div>';
		    				    
		    $html[] = $this->get_pagination( $num_pages, $page, $album_name, $album_id );
		    
		    $html[] = '</div>';				    
			$html[] = '</div>'; // end carouselWrapper
		    
		    
			if( count( $this->errors ) > 0 ) {
				// Display any errors in error log.
				$this->get_errors( $this->errors );
				return false;
			} 
			else {
				return implode( "\n", $html );						
			}
		} 
		else {
			error_log( 'We had a problem, maybe not a valid image id for this album.' );
		}
	}

	/**
	 *
	 *	Generate List of Images in a Specific Album on Album ID
	 *
	 */
	// public function get_album_image_list( $album_id, $thumb_size, $max_image_size, $max_results, $page ) {
	public function get_album_image_list( $album_id, $thumb_size, $max_image_size, $max_results, $page, $is_slideshow = FALSE ) {
	
		if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_album_image_list()' );		

			// TODO:
			// Why should I need to add this?...
			$this->access_token		= get_option( 'CWS-GA-ACCESS_TOKEN' );        

			// Grab Google OAuth tokens
			$this->access_token = unserialize( $this->access_token );
			$this->client 		= $this->access_token->getHttpClient( $this->oauth_options );
		
			// Set config for Zend cache
			$frontendOptions = array( 'lifetime' => 60, 'automatic_serialization' => true ); 
			$backendOptions  = array( 'cache_dir' => WPPICASA_PLUGIN_PATH . 'cache/' ); 
			$cache = Zend_Cache::factory( 'Core', 'File', $frontendOptions, $backendOptions );				
		
			// If not set then use default value
			if( isset( $max_results) ? $max_results: $max_results = $this->max_results );

			try	{
				$this->photos = new Zend_Gdata_Photos( $this->client );
			} 
			catch( Zend_Gdata_App_Exception $ex ) {				
				$this->errors[] =  $ex->getMessage();
				$this->get_errors( $this->errors );
			}
			
			// Construct the query						
			$query = $this->photos->newAlbumQuery();
	        	$query->setUser( "default" );
	        	$query->setAlbumId( $album_id );
	     
	     		$query->setImgMax( $max_image_size );
	     		$query->setMaxResults( $max_results );
	     
	        	$query->setThumbSize( "$thumb_size".'c' ); // 'c' to crop thumbnail
	        
	        	if ( isset ( $page ) ) {
        			$query->setStartIndex( ( ( $page - 1 ) * $max_results ) + 1 );
    			}
	        	
	        	// Need to allow for slideshows at various sizes being cached...	        		        
	        	try {
				
				if( ! $is_slideshow ) {
				
					if( ( $album_feed = $cache->load( 'images_' . $album_id . $page ) ) === false ) {
						if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_album_image_list() - This one is from google servers.' );		
											
						$album_feed = $this->photos->getAlbumFeed( $query );
					}
					else {
						if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_album_image_list() - This one is from cache.' );		
					}
				}
				else {
					if( ( $album_feed = $cache->load( 'images_' . $album_id . $page . $max_image_size ) ) === false ) {
						if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_album_image_list() - This one is from google servers.' );		
											
						$album_feed = $this->photos->getAlbumFeed( $query );
					}
					else {
						if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_album_image_list() - This one is from cache.' );		
					}				
				}
							        
		        if( $album_feed ) {
				
				if( ! $is_slideshow ) {
					// cache: save result in cache
					$cache->save( $album_feed, 'images_' . $album_id . $page );
				}
				else {
					$cache->save( $album_feed, 'images_' . $album_id . $page . $max_image_size );
				}
				
		        	return $album_feed;
		        } 
		        else {
		        	return false;
		        }
	        } 
	        catch( Zend_Gdata_App_Exception $ex ) {
				$this->errors[] =  $ex->getMessage();
				$this->get_errors( $this->errors );
			} 
	        
			if( count( $this->errors ) > 0 ) {
				// Display any errors in error log.
				$this->get_errors( $this->errors );
				return false;
			} 
			else {
				return $userFeed;			
			}   
	}
	
	/*
	 *
	 * Pagination Helper
	 *
	 * $num_pages, int
	 * $current_page, int
	 * $album_name, string
	 * $album_id, int
	 *
	 *	return string
	 */		
	public function get_pagination( $num_pages, $current_page, $album_name, $album_id ) {
		if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_pagination()' );		
		
		if( ! isset( $current_page ) ){ $current_page = 1; } // TODO: Do we need this check?
		
		// Create page links
		$html[] = "<ul class=\"page-nav\">\n";
		
		$previous 	= $current_page - 1;
		$next 		= $current_page + 1;
		
		// Previous link
		if( $previous > 0 )	{
			$html[] = "<li><a href=\"?album_id=".$album_id."&amp;albumName='".$album_name."'&amp;page=".$previous."\" id='prev_page'>Previous</a></li>";
		}
		
		for( $i=1 ; $i <= $num_pages ; $i++ ) {
		
			$class = "";
		
			// Add class to current page
			if( $i == $current_page) {
				$class = " class='selected'";
			}
	
			$html[] = "<li".$class.">";
			// $html[] = "<a href='?album_id=".$album_id."&amp;albumName='".$album_name."'&amp;page=".$i."' id='pages'>".$i."</a></li>\n";
			$html[] = "<a href=\"?album_id=".$album_id."&amp;albumName='".$album_name."'&amp;page=".$i."\" id='pages'>".$i."</a></li>\n";

		}
		
		// Next link
		if( $next <= $num_pages ) {
			$html[] = "<li><a href=\"?album_id=".$album_id."&amp;albumName='".$album_name."'&amp;page=".$next."\" id='next_page'>Next</a></li>";
		}
		
		$html[] = "</ul>\n";
		
		return implode( "\n", $html );
	}

	/**
	 *
	 * Format Display List of Images in a Specified album in a Carousel
	 * TODO: Pass in an an options array...	 
	 *
	 */
	public function get_album_images_carousel_display(
														$thumb_size, 
														$album_results_page, 
														$show_albums, 
														$show_private, 
														$show_album_ttl, 
														$show_slider_mrkrs,
														$album_id,
														$max_image_size
														) 
	{			
		if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_album_images_carousel_display()' );		

		//if( ! is_numeric( $max_album_results ) ) $max_album_results = $this->max_album_results;
		if( ! is_numeric( $thumb_size ) )  $thumb_size 	= $this->thumb_size;

		// Grab Google OAuth tokens
		$this->access_token 	= unserialize( $this->access_token );
		$this->client 		= $this->access_token->getHttpClient( $this->oauth_options );
		
		// Prepend slash to results page
		if( $resultsPage != '' ? $resultsPage = '/' . $resultsPage : $resultsPage );
		
		// Get image feed for album specified by album_id
		$get_album_list 	= $this->get_album_image_list( $album_id, $thumb_size, $max_image_size, $max_results, $page=1 );
		
		if( $get_album_list ) {
			try	{
				$numResults = $get_album_list->getTotalResults()->text;
			}
			catch( Zend_Gdata_App_Exception $ex ) {
				
				$this->errors[] =  $ex->getMessage();
				$this->get_errors( $this->errors );
			}				
		
			$html[] = '<div class="nak-gpv carouselWrapper">';
			
			if( $show_slider_mrkrs == 1 ){
				$html[] = '<div class="slider_navigation"></div>';
			}
							
			$html[] = '<div id="nak-gpv" class="grid-container infiniteCarousel">';
			$html[] = '<div class="grid wrapper">';
			
			$bloginfo = get_bloginfo('url' ) ;
			$resultsPage = $bloginfo . $resultsPage;
			
			$html[] = '<ul>';
			
			foreach( $get_album_list as $photo_entry ) {
		    
		        if( $photo_entry->getMediaGroup()->getContent() != null ) {
					$media_content_array = $photo_entry->getMediaGroup()->getContent();
					$content_url 		 = $media_content_array[0]->getUrl();
		        }
		
		        if( $photo_entry->getMediaGroup()->getThumbnail() != null ) {
					$media_thumbnail_array = $photo_entry->getMediaGroup()->getThumbnail();			
					$title = $photo_entry->getSummary();
					
					try	{
						$thumbnail_url = $media_thumbnail_array[0]->getUrl();
						
					} 
					catch( Zend_Gdata_App_Exception $ex ) {
						$this->errors[] = $ex->getMessage();
						$this->get_errors( $this->errors );
					}
			        
			        $html[] = '<li><a class="grouped_elements" title="' . $title . '" rel="' . $album_id . '" href="' . $content_url . '"><img class="displayed" src="' . $thumbnail_url . '" /></a></li>';
		        }		    	
		    }	 			
			
			$html[] = '</ul>';
			$html[] = '</div>';	// End grid-container							
			$html[] = '</div>'; // End grid wrapper
			$html[] = '<div class="images"></div>';
			$html[] = '</div>'; // end carouselWrapper

			return implode("\n", $html);				
		}
	}			
	
	
	/**
	 *
	 *	Format Display Slideshow of Images in Specific Album
	 *
	 * $album_id, int
	 * $thumb_size, int
	 * $max_image_size, int
	 * $max_results, int
	 * $page,int
	 * 
	 * return string		 
	*/
	public function get_album_slideshow_display( $album_id, $thumb_size, $max_image_size, $max_results, $page ) {	
		
		if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_album_slideshow_display()' );		
															
		if( ! is_numeric( $max_image_size ) )		$max_image_size = $this->max_image_size;
		if( ! is_numeric( $thumb_size ) ) 		$thumb_size 	= $this->thumb_size;
		if( ! is_numeric( $max_results ) ) 		$max_results 	= $this->max_results;
	
	
		if( $album_feed = $this->get_album_image_list( $album_id, $thumb_size, $max_image_size, $max_results, $page, $is_slideshow = TRUE ) ) {			
			
			// If not set then use default value
			if( $max_results != '' ? $max_results: $max_results = $this->max_results );
								
			$album_name = $album_feed->getTitle();
											
			$html[] = '<div id="slideshow" style="width:' . $max_image_size . 'px;">';
			$html[] = '<div class="slides">';
		    	$html[] = "<h2>$album_name</h2>";
						
			foreach( $album_feed as $photo_entry ) {
		    
			        if( $photo_entry->getMediaGroup()->getContent() != null ) {
						$media_content_array 	= $photo_entry->getMediaGroup()->getContent();
						$content_url		= $media_content_array[0]->getUrl();
						
						$width = $media_content_array[0]->getWidth();
						$height = $media_content_array[0]->getHeight();
			        }
		
			        if( $photo_entry->getMediaGroup()->getThumbnail() != null ) {
						$media_thumbnail_array 	= $photo_entry->getMediaGroup()->getThumbnail();			
						$title 			= $photo_entry->getSummary();
						
						try {
							$thumbnail_url = $media_thumbnail_array[0]->getUrl();
							
						} 
						catch( Zend_Gdata_App_Exception $ex ) {
							$this->errors[] = $ex->getMessage();
							$this->get_errors( $this->errors );
						}
				        				        
				        // $html[] = '<div class="slide"><img width="' . $width . '" height="' . $height . '" class="displayed" src="' . $content_url . '" /></div>';
				        $html[] = '<div class="slide"><img width="' . $max_image_size . ' class="displayed" src="' . $content_url . '" /></div>';
			        }
			        
		    	}	    
		    				    		    				    
		    	$html[] = '</div>';				    
			$html[] = '</div>'; // end carouselWrapper
		    
		    
			if( count( $this->errors ) > 0 ) {
				// Display any errors in error log.
				$this->get_errors( $this->errors );
				return false;
			} 
			else {
				return implode( "\n", $html );						
			}
		} 
		else {
			error_log( 'We had a problem, maybe not a valid image id for this album.' );
		}
	}
	
	
	
	
	
	
	/**
	 *
	 * Write errors to error log.
	 *
	 */		
	private function get_errors( $errors ) {
		if( $this->debug ) error_log( 'Inside: CWS_GPPApi::get_errors()' );
			
		foreach( $errors as $err ) {
			error_log( $err );
		}
	}	

}