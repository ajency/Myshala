(function($) {

$.fn.infiniteCarousel = function () {

    function repeat(str, num) {
        return new Array( num + 1 ).join( str );
    }
  
    return this.each( function ( index ) {
        
    	var $slider_navigation = $( '.slider_navigation' );
    	    
        var $wrapper = $( '> div', this ).css( 'overflow', 'hidden' ),
            $slider  = $wrapper.find( '> ul' ),
            $items   = $slider.find( '> li' ),
            $single  = $items.filter( ':first' ),
            
            singleWidth = $single.outerWidth(), 
            visible     = Math.ceil( $wrapper.innerWidth() / singleWidth ), // Note: doesn't include padding or border
            currentPage = 1,
            pages       = Math.ceil( $items.length / visible );
                        
		 // Define globals          
		 $carouselWrapper = $wrapper.parent().parent();
		 $slider_navigation = $carouselWrapper.find('.slider_navigation');           


        // 1. Pad so that 'visible' number will always be seen, otherwise create empty items
        if ( ( $items.length % visible ) != 0 ) {
            $slider.append( repeat( '<li class="empty" />', visible - ( $items.length % visible ) ) );
            $items = $slider.find( '> li' );
        }


        // 2. Top and tail the list with 'visible' number of items, top has the last section, and tail has the first
        $items.filter( ':first' ).before( $items.slice( - visible ).clone().addClass( 'cloned' ) );
        $items.filter( ':last' ).after( $items.slice( 0, visible ).clone().addClass( 'cloned' ) );
        $items = $slider.find( '> li' ); // reselect
        
        
        // 3. Set the left position to the first 'real' item
        $wrapper.scrollLeft( singleWidth * visible );
        
        
        // 4.a album nav function
        function albumNavi( pages, index ) {
        
        	// console.log( 'albumNavi index: '+index );
        
        	var slider_navigation = '';
        	slider_navigation = '<ul class="markers">';
        	
        	for( var i = 1; i<=pages; i++ ) {
        		console.log('make navi for page: '+i);
        		
        		slider_navigation += '<li><a>'+ i +'</a></li>'; 
        	}
        	
        	slider_navigation += '</ul>';
            
            return slider_navigation;	
        }
        
        
        // 4a Update markers
        function updateMarkers( event , page ) {
                
	        // console.log('updateMarkers');        
	        // console.log(event);

        	event.filter( function( index ) {

			jQuery( this ).removeClass( 'current' );
				
				if( jQuery( this ).text() == page ){
				   jQuery( this ).addClass( 'current' );				
				}
			
			});        	
        }
        

        // 4. Paging function
        function gotoPage( page ) {
        
        page == '' ? 1 : page;
                
            var dir = page < currentPage ? -1 : 1,
                n = Math.abs( currentPage - page ),
                left = singleWidth * dir * visible * n;
            
            $wrapper.filter( ':not(:animated)' ).animate({
                scrollLeft : '+=' + left
            }, 500, function () {
            
	            // console.log(this);            
            
                if ( page == 0 ) {
                    $wrapper.scrollLeft( singleWidth * visible * pages );
                    page = pages;
                } else if ( page > pages ) {
                    $wrapper.scrollLeft( singleWidth * visible );
                    // reset back to start position
                    page = 1;
                } 

                currentPage = page;

                // Update current marker, when click next/back         
                var markers = jQuery( 'ul.markers a', $slider_navigation );
                
                updateMarkers( markers, page );
            });                
            
            return false;
        }
        
        $wrapper.after( '<a class="arrow back">&lt;</a><a class="arrow forward">&gt;</a>' );
        
        // Render slider markers
		$slider_navigation.html( albumNavi( pages ) );
		console.log($slider_navigation);


        // 5. Bind to the forward and back buttons
        $( 'a.back', this ).click( function () {
        console.log( this );
            return gotoPage( currentPage - 1 );                
        });
        
        $( 'a.forward', this ).click( function () {
            return gotoPage( currentPage + 1 );
        });
        
        // Create a public interface to move to a specific page
        // this can be called 
        // jQuery('.infiniteCarousel').trigger('goto', [1]);
        $( this ).bind( 'goto', function ( event, page ) {
        	console.log( 'inside goto' );
            gotoPage( page );
        });
        
        
        // 6. Album paging function
        var markers = jQuery('ul.markers a', $slider_navigation );
                
        //$('ul.markers a').click( function(){
        markers.click( function(){
			
			var page = $(this).text();
			console.log(page + ', '+index);
        	
        	return gotoPage(parseInt(page));
        	
        } );      
     
        var markers = jQuery( 'ul.markers a' );
        // Update markers
        updateMarkers( markers, 1 ); 

    });  
};

})(jQuery);