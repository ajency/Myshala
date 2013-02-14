
var CWS = CWS || {};


CWS.Slideshow = function( options )
{
	'use strict';
	
	var debug = false;
	
	var self = this;
	var $slides;			// Collection of slides
	var _loopInterval;		// Time between slides
	
	//self.slideNumber = 1;		// The current slide number
	var markers;
	var markerLinks;
	
	self.slideNumber = 1;
	

	self.init = function()
	{	
		self.$slideshow = jQuery( options.slideshow );

		// Make sure we have a slideshow
		if( self.$slideshow.length )
		{			
			// Find the slides
			self.$slides = self.$slideshow.find( options.classSlide );
			
			// If we have slides
			if( self.$slides )
			{			
				// Make sure we have more than 1 slide
				if( self.$slides.length > 1 )
				{
					// Grab first slide					
					//self.slide 	= self.$slides.eq( 0 );	
					self.slide 	= self.$slides.eq( self.slideNumber - 1 );
					
					// Start the party!
					self.start();
										
					// Grab the buttons
					self.$buttons 		= self.$slideshow.find( options.buttons );
					self.$buttonNext 	= self.$buttons.find( options.buttonNext );
					self.$buttonPrevious 	= self.$buttons.find( options.buttonPrevious );
					
					// Initialise the events
					self.initEvents();
					// self.initMarkers();
					
					//self.updateMarkers();
						
				}
			}
		}
	}
	
	// Start looping slides
	self.start = function()
	{
		_loopInterval = setInterval( self.nextSlide, 2500 );
	};

	// Stop looping slides
	self.stop = function()
	{
		clearInterval( _loopInterval );
		// clearTimeout(timeoutSlide);
	};

	
	// Get next slide
	self.nextSlide = function()
	{
		var nextSlide;	
				
		nextSlide = self.slide.next( options.classSlide ); //<-- if this returns 0 then call :first
		
		self.slideNumber = nextSlide.index();	
		
		if( nextSlide.length == 0 )
		{
			// Grab first slide
			nextSlide = self.$slides.eq( 0 );
			self.slideNumber = nextSlide.index(0);							
		}

		// console.log( 'x '+self.slideNumber );
		// self.updateMarkers();
		if( debug ) console.log( nextSlide );					
		self.display( nextSlide );
	}
	
	
	// Get previous slide
	self.prevSlide = function()
	{
		var prevSlide;
		
		prevSlide = self.slide.prev( options.classSlide );
		
		self.slideNumber = prevSlide.index();; // self.slideNumber = self.slideNumber - 1;			
				
		
		 // If this returns 0 then grab last slide
		if( prevSlide.length == 0 )
		{
			prevSlide = self.$slides.eq( self.$slides.length -1 );			
		}
		
		self.updateMarkers();
		if( debug ) console.log( prevSlide );
		self.display( prevSlide );
	}
	

	// Display the next slide
	self.display = function( nextSlide, override )
	{				
		// Goto a specific slide
		if ( typeof override !== 'undefined' )
		{
			nextSlide = self.$slides.eq( override );
			self.slideNumber = override ;
		}

		// Show the next slide
		nextSlide.css( 'z-index', 1 ).fadeTo('slow', 1);

		// Hide the old slide
		self.slide.css( 'z-index', 0 ).fadeOut('slow', function()
		{
			// Update active class
			self.$slides.removeClass( options.classActive );

self.$slides.css('float', 'none');
self.$slides.css('position', 'absolute');

			nextSlide.addClass( options.classActive );
			
nextSlide.css('float','left');
nextSlide.css('position','relative');

			// Update current slide
			self.slide = nextSlide;

			// console.log('y '+self.slideNumber);
			// self.updateMarkers();
		});
	}
	
	// Create slider markers
/*
	self.initMarkers = function()
	{
		// Return if we have no marker option set
		if ( ! options.classMarkers ) { return; }
		
		// Add the markers
		markers = jQuery( '<ul />' ).addClass( options.classMarkers );
	
		// Create marker links
		for ( var i = 0, j = self.$slides.length; i < j; i++ )
		{
			markers.append( jQuery( '<li><a href="#">' + ( i + 1 ) + '</a></li>' ) );
		}
	
		// Grab the links
		markerLinks = markers.find('a');
	
		// Add markers
		self.$slideshow.append( markers );
	
		// Update markers
		self.slideNumber = self.slideNumber - 1;
		self.updateMarkers();
	
		// Wire up and show the markers
		markerLinks.click( self.updateMarkers );
		markers.show();
	};
*/


	// 
/*
	self.updateMarkers = function( event )
	{	
		// If we have markers
		if ( markers )
		{
			var marker = self.slideNumber - 1 ;
		
			// There was a click so update
			if ( event )
			{				
				marker = markerLinks.index( jQuery( this ) );
	
				// Change to the right slide
				self.gotoSlide( event, marker );
			}
			else
			{
				var marker = self.slideNumber ;
				// Highlight the right marker		
				markerLinks.removeAttr( 'class' ).eq( marker ).addClass( options.classActive );
			}
		}	
		
	}
*/

	// 
/*
	self.gotoSlide = function( event, marker )
	{
		
		// stop white slide if click on same link as slide active
		// Skip is same slide has been request
		// if (self.slide.is(marker)) { return; }
		
		if(self.slide.index() === marker) { return; }		

		// alert(self.slide.index());
		// alert(marker);
		
		// console.log( 'gotoSlide...' );
		// console.log(  marker );
		
		self.display( '' , marker );
		// self.updateMarkers();
	}
*/


	self.initEvents = function()
	{
		// Listen for mouse movement
		self.$slideshow.bind( 'mouseenter', self.stop );
		self.$slideshow.bind( 'mouseleave', self.start );
		
		// Listen for back / forward
		self.$buttonNext.bind( 'click', function( event ) { self.nextSlide(); });
		self.$buttonPrevious.bind( 'click', function( event ) { self.prevSlide(); });		
	};	
	
	
}