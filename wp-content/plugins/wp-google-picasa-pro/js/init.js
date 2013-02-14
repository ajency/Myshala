( function()
{	
	// Configure the slideshow options
	var options = 
	{
		// Element Handles
		slideshow: '#slideshow',
		classSlide: '.slide',
		classActive: 'active',
		classMarkers: '',
		
		buttons: 'ul.buttons',
		buttonNext: 'li.next a',
		buttonPrevious: 'li.previous a',
		
		
		// Adjust Timings
		delay: 2500,
		slideTime: 5000,
		slideTimeFade: 600,			
	}
	
	// Let's get the party started.
	var slideshow = new CWS.Slideshow( options );
	slideshow.init();
}
)();