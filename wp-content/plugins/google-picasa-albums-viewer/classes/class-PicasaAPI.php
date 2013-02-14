<?php
/*
Copyright (c) 2011, nakunakifi.com, Ian Kennerley.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Include Zend Gdata
set_include_path ( realpath( dirname( __FILE__ ) ) . '/../zend/library');

/*
		Main PicasaAPI class
*/
	class PicasaAPI {
	
		// Define some variables
		private $client;
		private $errors = array();
		private $zend_loader_present = false;
		private $service;
		private $user;
		private $pass;
		
		/*
			Check Zend Library is present and load required classes
		*/
		public function __construct( $user, $pass ) {
				
			try
			{	
				if( $this->zend_loader_present = @fopen( 'Zend/Loader.php', 'r', true ) ) {
				
					if( $this->zend_loader_present ) {
					
						@fclose( $this->zend_loader_present );
						
						require_once 'Zend/Loader.php';
						require_once 'Zend/Version.php';
						
						Zend_Loader::loadClass( 'Zend_Gdata' );
						Zend_Loader::loadClass( 'Zend_Gdata_ClientLogin' );
						Zend_Loader::loadClass( 'Zend_Gdata_Photos' );
						Zend_Loader::loadClass( 'Zend_Gdata_Photos_UserQuery' );
					}
				
						// Parameters for ClientAuth authentication
						$this->user = $user;
						$this->pass = $pass;

					try	{				
						$this->service = Zend_Gdata_Photos::AUTH_SERVICE_NAME;				
					}
					catch( Zend_Gdata_App_Exception $ex ) {

						$this->errors[] = $ex->getMessage();
						$this->get_errors( $this->errors );				    
					}
											
					try	{				
						// Create an authenticated HTTP client
						$this->client  = Zend_Gdata_ClientLogin::getHttpClient( $user, $pass, $this->service );				
					}
					catch( Zend_Gdata_App_Exception $ex ) {
						
						$this->errors[] =  $ex->getMessage();
						$this->get_errors( $this->errors );
					}
					
				}
				else {
					
					error_log( 'Error trying' .
                    ' to access Zend/Loader.php using \'use_include_path\' =' .
                    ' true. Make sure you include Zend Framework in your' .
                    ' include_path which currently contains: ' .
                    ini_get( 'include_path' ) );
				}
			}
			catch( Exception $e ) {
				$this->errors[] = 'Error checking if Zend Library is present.';			
			}


        	// Display any errors in error log.
			if( count( $this->errors ) > 0 ) {
				$this->get_errors( $this->errors );
			}
		}
		
		
		/*
			Check for Zend Framework
			Used for user feedback in settings page
		*/
		public function preflight_check() {
		
			if( $this->zend_loader_present = @fopen( 'Zend/Loader.php', 'r', true ) ) {
				if( !$this->zend_loader_present ) {
					// ZF Not Found!
					return false;
				} else {
					return true;
				}
			}
		}

		
		/*
			Check user is authenticated
		*/
		private function check_auth( $var ) {
			
			if( $var != null ) {				
				return true;
			}
			else {
				return false;
			}
		}


		/*
			Generate List of Albums
		*/
		public function get_album_list( $max_album_results, $album_thumb_size ) {				
	
			try	{
				// Create an instance of the service
				$this->service = new Zend_Gdata_Photos();
			
			} catch( Zend_Gdata_App_Exception $ex ) {
				
				$this->errors[] =  $ex->getMessage();
				$this->get_errors( $this->errors );
			}			
			
			try {
			
				$query = new Zend_Gdata_Photos_UserQuery();
			
			} catch( Zend_Gdata_App_Exception $ex ) {
				
				$this->errors[] =  $ex->getMessage();
				$this->get_errors( $this->errors );
			}
			
			// Construct the query	
			$query->setUser( $this->user );
		    $query->setThumbSize( $album_thumb_size . 'c' );
		    $query->setMaxResults( $max_album_results );
		    		
			try	{
				$userFeed = $this->service->getUserFeed( null, $query );
			} catch( Zend_Gdata_App_Exception $ex ) {
				
				$this->errors[] =  $ex->getMessage();
				$this->get_errors( $this->errors );
			}
			
			if( count( $this->errors ) > 0 ) {
				// Display any errors in error log.
				$this->get_errors( $this->errors );
				return false;			
			} else { 
				return $userFeed;	
			}
		
		}
		
		
		/*
			Format Display List of Albums
		*/
		public function get_album_display( $max_album_results, $album_thumb_size, $resultsPage, $show_albums = null) {			

			if( !is_numeric( $max_album_results ) ) { $max_album_results = $this->max_album_results; }
			if( !is_numeric( $album_thumb_size ) )  { $album_thumb_size = $this->album_thumb_size; }

			// Check we are authenticated 
			if( $this->check_auth( $this->client ) ) {
			
				if( $resultsPage != '' ? $resultsPage = '/' . $resultsPage : $resultsPage );
				
				$get_album_list 	= $this->get_album_list( $max_album_results, $album_thumb_size );
				
				if( $get_album_list ) {
					try	{
						$numResults = $get_album_list->getTotalResults()->text;
					}
					catch( Zend_Gdata_App_Exception $ex ) {
						
						$this->errors[] =  $ex->getMessage();
						$this->get_errors( $this->errors );
					}
				
					//$get_album_list->getTitle();
				
					$html[] = '<div id="nak-gpv" class="grid-container">';
					$html[] = '<div class="grid grid_gallery" >';
				
					$bloginfo = get_bloginfo('url' ) ;
					$resultsPage = $bloginfo . $resultsPage;
					
				
					foreach( $get_album_list as $album ) {
					
						if ( $album instanceof Zend_Gdata_Photos_AlbumEntry ) {

							$thumbnail = $album->getMediaGroup()->getThumbnail();
							$title = $album->getMediaGroup()->getTitle();
							// error_log($bloginfo . $resultsPage . '?album_id=' . $album->getGphotoId());
							
							//
							// error_log('album id = ' . $album->getGphotoId());
							//$show_albums = array('5193636718112667841', '5218507736478682657');
							// var_dump( $show_albums ); die();
							//
							
							if ( count ( $show_albums ) )
							{
						
								if ( in_array( $album->getGphotoId(), $show_albums) )
								{

									$html[] = '<a title="' . $title . '" href="' . $resultsPage . '?album_id=' . $album->getGphotoId()  . '"><img alt="' . $title . '" src="' . $thumbnail[0]->getUrl() . '"></a>';
								}
							}
							else
							{
								$html[] = '<div style="width:100px;height:220px;text-align:center;float:left;margin-right:20px;margin-left:20px;"><a title="' . $title . '" href="' . $resultsPage . '?album_id=' . $album->getGphotoId()  . '"><img alt="' . $title . '" src="' . $thumbnail[0]->getUrl() . '"><br /><b style="padding:5px">'.$title.'</b></a></div>';
							}
					}
					}
					 
					$html[] = '</div><div style="clear:both"></div>';
					$html[] = '</div>';
					
					return implode("\n", $html);
				
				}
			}
			
		}
		
		
		/*
			Generate List of Images in a Specific Album
		*/
		public function get_album_image_list( $album_id, $thumb_size, $max_image_size, $max_results, $page ) {		
			
			if( !$this->zend_loader_present ) {
				return;
				
			} else {					
				// If not set then use  default value
				if( isset( $max_results) ? $max_results: $max_results = $this->max_results );

				try	{
					$this->photos = new Zend_Gdata_Photos($this->client);
				} catch( Zend_Gdata_App_Exception $ex ) {
					
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
		        		        		        
		        try {
			        
			        if( $album_feed = $this->photos->getAlbumFeed( $query ) ) {
			        
			        	return $album_feed;
			     
			        } else {
			        	return false;
			        }
			        
		        } catch( Zend_Gdata_App_Exception $ex ) {
					$this->errors[] =  $ex->getMessage();
					$this->get_errors( $this->errors );
				} 
		        
				if( count( $this->errors ) > 0 ) {
					// Display any errors in error log.
					$this->get_errors( $this->errors );
					return false;
				} else {
					return $userFeed;			
				}   
			
			}
	        
		}
		
		
		/*
			Format Display List of Images in Specific Album
		*/
		public function get_album_images_display($album_id, $thumb_size, $max_image_size, $max_results, $page) {	
			
			if( !is_numeric( $max_image_size ) ) { $max_image_size = $this->max_image_size; }
			if( !is_numeric( $thumb_size ) ) { $thumb_size = $this->thumb_size; }
			if( !is_numeric( $max_results ) ) { $max_results = $this->max_results; }
						
			// Check we are authenticated and we have an album id
			if( $this->check_auth( $this->client ) && isset( $album_id) ) {
			
				if( $album_feed = $this->get_album_image_list( $album_id, $thumb_size, $max_image_size, $max_results, $page ) ) {			
					// If not set then use  default value
					if( $max_results != '' ? $max_results: $max_results = $this->max_results );
										
					$album_name = $album_feed->getTitle();
										
					// For pagination	
					$numResults = $album_feed->getTotalResults()->text;
					$num_pages 	= ceil( $numResults / $max_results );
				
					$html[] = '<div id="nak-gpv" class="grid-container">';
				    $html[] = "<h2>$album_name</h2>";
					$html[] = '<div class="grid">';
					if ( $num_pages > 1 )
					{
						$html[] = $this->get_pagination( $num_pages, $page, $album_name, $album_id );
					}
					
					foreach( $album_feed as $photo_entry ) {
				    
				        if( $photo_entry->getMediaGroup()->getContent() != null ) {
				        
							$media_content_array = $photo_entry->getMediaGroup()->getContent();
							$content_url 		 = $media_content_array[0]->getUrl();
				    
				        }
				
				        if($photo_entry->getMediaGroup()->getThumbnail() != null) {
					
							$media_thumbnail_array = $photo_entry->getMediaGroup()->getThumbnail();			
							$title = $photo_entry->getSummary();
							
							try	{
							
								$thumbnail_url = $media_thumbnail_array[0]->getUrl();
								
							} catch( Zend_Gdata_App_Exception $ex ) {
							
								$this->errors[] = $ex->getMessage();
								$this->get_errors( $this->errors );
															    
							}
					        
					        $html[] = '<a title="' . $title . '" rel="fancybox" href="' . $content_url . '"><img class="flickr_badge_image" src="' . $thumbnail_url . '"  /></a>';
				        }		    	
				    }	    
				    				    
				    $html[] = $this->get_pagination( $num_pages, $page, $album_name, $album_id );
				    $html[] = '</div>';
				    $html[] = '</div>';
				    
				    
					if( count( $this->errors ) > 0 ) {
						
						// Display any errors in error log.
						$this->get_errors( $this->errors );
						
						return false;
						
					} else {
					
						return implode( "\n", $html );	
					
					}
				    
				} else {
					// error_log( 'We had a problem, maybe not a valid image id for this album.' );
				}
			}
			else
			{
				echo 'Authentication Failed';
			}
		}
		

		/*
			Pagination Helper
		*/		
		public function get_pagination( $num_pages, $current_page, $album_name, $album_id ) {

			if( ! isset( $current_page ) ){ $current_page = 1; } // TODO: Do we need this check?
			
			// Create page links
			$html[] = "<ul class=\"page-nav\">\n";
			
			$previous 	= $current_page - 1;
			$next 		= $current_page + 1;
			
			// Previou link
			if( $previous > 0 )	{
				$html[] = "<li><a href=\"?q=".$previous."&album_id=".$album_id."&amp;albumName='".$album_name."'&amp;page=".$previous."\">Previous</a></li>";
			}
			
			for( $i=1 ; $i <= $num_pages ; $i++ ) {
				$class = "";
				// Add class to current page
				if( $i == $current_page) {
					$class = " class='selected'";
				}
		
				$html[] = "<li".$class.">";
				$html[] = "<a href=\"?q=".$i."&album_id=".$album_id."&amp;albumName='".$album_name."'&amp;page=".$i."\">".$i."</a></li>\n";
			}
			
			// Next link
			if( $next <= $num_pages ) {
				$html[] = "<li><a href=\"?q=".$next."&album_id=".$album_id."&amp;albumName='".$album_name."'&amp;page=".$next."\">Next</a></li>";
			}
			
			$html[] = "</ul>\n";
			
			return implode( "\n", $html );
			
		}

		
		/*
			Write errors to error log.
		*/		
		private function get_errors( $errors ) {
				
			foreach( $errors as $err ) {
				
				error_log( $err );
			
			}
						
			//return false;
		}
		
	}
	