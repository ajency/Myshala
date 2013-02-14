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

jQuery(function(){

	/* Apply fancybox to multiple items */
	jQuery("a[rel=fancybox]").fancybox({
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'titlePosition' 	: 'over',
		'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
			return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
		}
	});
	
	/* Apply opacity change to non moused over items */
	jQuery(".grid").delegate("img", "mouseover mouseout", function(e) 
	{
		if (e.type == 'mouseover') 
		{
			jQuery(".grid img").not(this).dequeue().animate({opacity: "0.7"}, 300);
    	} 
    	else 
    	{
			jQuery(".grid img").not(this).dequeue().animate({opacity: "1"}, 300);
   		}
	});
	
/*
				
    $("#fancyopen a").fancybox({
         'hideOnContentClick': true,
         'overlayShow': true
    });
*/

});
