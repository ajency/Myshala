// Utility Functions

/**
 *	Parse href to obtain album_id and page values
 *
 */
function parseHref( href ) {

	if ( href.substring( 0, 1 ) == '?' ) { 
  		href = href.substring( 1 );
	}

	// New object
	var params = {},
		href = href;
	
	if( href === '' ) return prams;
	
	var list = href.split( "&" );
	
	for( var i = 0; i < list.length; i++ ) {
		
		var param = list[i],
	        p     = param.indexOf( "=" ),
		    name  = param.substring( 0, p ),
		    value = param.substring( p+1 );
		
		params[name] = value;
		
	}
	
	return params;
}


/**
 *  Update page with new content 
 *
 */
function updatePage( data, div ){
	// console.log( 'updatePage called...' );
	// console.log( div );
	
    window.setTimeout( function(){
        jQuery( div ).html( data ).fadeIn( 400 );
    }, 150 )
}


/**
 *	Show busy overlay	
 *
 */
function showBusy(){
	// TODO: Namespace this        
	// jQuery.blockUI( {message: '<img src="/wp-content/plugins/wp-google-picasa-pro/img/ajax-loader.gif" />'} );
	jQuery.blockUI( {message: '<img src="' + cws_gpp_.pluginurl +  '/img/ajax-loader.gif" />'} );
}


/**
 *	Remove busy overlay	
 *
 */
function removeBusy() {

	setTimeout( jQuery.unblockUI, 2000 ); 
	jQuery( '.blockUI' ).fadeOut( 'slower' );   
}


/**
 *	Main jQuery Gumpf
 *
 */
jQuery( document ).ready( function( $ ) {
	
	$("a.grouped_elements").fancybox( {
	
		'transitionIn'		: 'elastic',
		'transitionOut'		: 'elastic',
		'easingIn'		    : 'swing',
		'easingOut'		    : 'swing',		
		'speedIn'           : 600,
		'speedOut'          : 200,
		'titlePosition' 	: 'over',
		'titleFormat'		: function( title, currentArray, currentIndex, currentOpts ) {
			return '<span id="fancybox-title-over">Image ' + ( currentIndex + 1 ) + ' / ' + currentArray.length + ( title.length ? ' &nbsp; ' + title : '' ) + '</span>';
		}
	} );
	
	
	// Apply opacity change to non moused over items
	$( ".grid" ).delegate( "img", "mouseover mouseout", function( e ) {
		if( e.type == 'mouseover' ) {
			$( ".grid img" ).not( this ).dequeue().animate( { opacity: "0.7" }, 200 );
		} 
		else {
			$( ".grid img" ).not( this ).dequeue().animate( { opacity: "1" }, 200 );
		}
	});
	
			
	// Page through images
	$( "a#pages, a#next_page, a#prev_page" ).live( 'click', function( e ) {
	
		e.preventDefault(); 

		var clean_href = $( this ).attr( 'href' );
						
		var $carouselWrapper = $(this).parents('.carouselWrapper'),
			$images = $carouselWrapper.find('> .images', $carouselWrapper);
				
		// Parse href of the link clicked, using utility function
		var href     = parseHref( clean_href ),		
			album_id = href['album_id'],
			page     = href['page'];
	
			$.ajax({
  					type: 'POST',
  					url: cws_gpp_.siteurl + '/wp-admin/admin-ajax.php',
  					data: {
  							action: 'getImages',
  							AlbumId: album_id,
  							page: page
  						  },
  						  
                    beforeSend: function() {
                        showBusy(); 
                    },
  					
  					success: function( data, textStatus, XMLHttpRequest ){
  						
						$carousel = jQuery( '.infiniteCarousel' );

						// Fade out before loading in new images
						$images.fadeOut( 100 );

						updatePage( data , $images );
						
						// Re-initialize FancyBox
						setTimeout(
							function() {
								console.log('re-init fancy box');
									$("a.grouped_elements").fancybox( {
										'transitionIn'		: 'elastic',
										'transitionOut'		: 'elastic',
										'easingIn'		    : 'swing',
										'easingOut'		    : 'swing',
										'speedIn'           : 600,
										'speedOut'          : 200,
										'titlePosition' 	: 'over',
										'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
											return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
										}
									});
							},
							600
						);
				  		
				  		removeBusy();
					  },
					  
					error: function( MLHttpRequest, textStatus, errorThrown ){
						alert( 'err'+errorThrown );
					}
  			});
  			
	});	
		
		
	/**
	 *	Load An Album
	 *
	 */
	$( 'a#albumThumb' ).click( function( e ){
	
		e.preventDefault();

		var $AlbumId = $( this ).parent().attr( 'id' ).replace( 'album-', '' ),
			$carouselWrapper = $( this ).parents( '.carouselWrapper' ),
			$images = $carouselWrapper.find( '> .images' );
					
			$.ajax({
			
				type: 'POST',
				url: cws_gpp_.siteurl + '/wp-admin/admin-ajax.php',
				data: {
						action: 'getAlbumImages',
						AlbumId: $AlbumId,
				},
				
				beforeSend: function() {
					showBusy(); 
				},
				
				success: function( data, textStatus, XMLHttpRequest ){
				
					removeBusy();
					
					$carousel = jQuery( '.infiniteCarousel' );						
					var album_images = jQuery( $images );
					
					album_images.html( '' );
					album_images.append( data );		
								
					// Re-initialize FancyBox
					setTimeout(
						function() {
								$( "a.grouped_elements" ).fancybox( {
									'transitionIn'		: 'elastic',
									'transitionOut'		: 'elastic',
										'easingIn'		    : 'swing',
										'easingOut'		    : 'swing',									
									'speedIn'           : 600,
									'speedOut'          : 200,
									'titlePosition' 	: 'over',
									'titleFormat'		: function( title, currentArray, currentIndex, currentOpts ) {
										return '<span id="fancybox-title-over">Image ' + ( currentIndex + 1 ) + ' / ' + currentArray.length + ( title.length ? ' &nbsp; ' + title : '' ) + '</span>';
									}
								});
						},
						600
					);		  
				
				},
				
				error: function( MLHttpRequest, textStatus, errorThrown ){
					alert( 'err'+errorThrown );
				}
			});
	
		} );

});