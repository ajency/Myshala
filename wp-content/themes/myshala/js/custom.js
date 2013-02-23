jQuery(document).ready(function(){
	
	if(!jQuery.browser)
		jQuery.browser=browser_detect();
	
	if(jQuery.browser.msie && jQuery.browser.version < 8)
		return;
	
	if(jQuery.browser.webkit || jQuery.browser.chrome || jQuery.browser.safari)
		jQuery('body').addClass('webkit');
	else if(jQuery.browser.msie)
		jQuery('body').addClass('msie');
	else if(jQuery.browser.mozilla)
		jQuery('body').addClass('mozilla');
		
	if(!!('ontouchstart' in window))
		jQuery('body').addClass('touch');
	else
		jQuery('body').addClass('no-touch');

	jQuery('.portfolio-small-preview .pic a').prepend('<span class="before" />');
		
	jQuery('.big-slider-slide, .flickr_badge_image a').append('<span class="after" />');
	
	responsiveListener_init();

	menu_init();
	
	gallery_init();
		
	slider_init();
	
	slider_auto_scroll();
	
	logos_init();
	
	testimonials_init();
	
	comments_init();
	
	isotope_init();
	
	lightbox_init();
	
	thumbs_masonry_init();
	
	tooltips_init();
	
	toggle_init();
	
	tabs_init();

	contact_form_init();
	
	sort_menu_init();
	
	//sidebar_slide_init();

	fix_placeholders();	
});

/***********************************/

function fix_placeholders() {
	
	var input = document.createElement("input");
  if(('placeholder' in input)==false) { 
		jQuery('[placeholder]').focus(function() {
			var i = jQuery(this);
			if(i.val() == i.attr('placeholder')) {
				i.val('').removeClass('placeholder');
				if(i.hasClass('password')) {
					i.removeClass('password');
					this.type='password';
				}			
			}
		}).blur(function() {
			var i = jQuery(this);	
			if(i.val() == '' || i.val() == i.attr('placeholder')) {
				if(this.type=='password') {
					i.addClass('password');
					this.type='text';
				}
				i.addClass('placeholder').val(i.attr('placeholder'));
			}
		}).blur().parents('form').submit(function() {
			jQuery(this).find('[placeholder]').each(function() {
				var i = jQuery(this);
				if(i.val() == i.attr('placeholder'))
					i.val('');
			})
		});
	}
}

/***********************************/

function browser_detect() {
	
	var matched, browser;

	ua = navigator.userAgent.toLowerCase();

	var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
		/(webkit)[ \/]([\w.]+)/.exec( ua ) ||
		/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
		/(msie) ([\w.]+)/.exec( ua ) ||
		ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
		[];

	matched = {
		browser: match[ 1 ] || "",
		version: match[ 2 ] || "0"
	};

	browser = {};

	if ( matched.browser ) {
		browser[ matched.browser ] = true;
		browser.version = matched.version;
	}

	if ( browser.webkit ) {
		browser.safari = true;
	}

	return browser;
}

/***********************************/

function responsiveListener_init(){
	var lastWindowSize=jQuery(window).width();
	jQuery(window).data('mobile-view',(lastWindowSize<768));
	
	jQuery(window).resize(function(){
		var w=jQuery(this).width();
		if(
			(w>=1420 && lastWindowSize < 1420) ||
			(w>=1270 && lastWindowSize < 1270) ||
			(w>=980 && lastWindowSize < 980) ||
			(w>=768 && lastWindowSize < 768) ||
			(w>=480 && lastWindowSize < 480) ||
			
			(w<=1419 && lastWindowSize > 1419) ||
			(w<=1269 && lastWindowSize > 1269) ||
			(w<=979 && lastWindowSize > 979) ||
			(w<=767 && lastWindowSize > 767) ||
			(w<=479 && lastWindowSize > 479)		
		){
			jQuery(window).data('mobile-view',(w<768));
			responsiveEvent();
		}
		lastWindowSize=w;
	});
	
}

function responsiveEvent(){
	
	sliderRewind();
	sliderCheckControl();
	isotopeCheck();
	thumbs_masonry_refresh();
	jQuery(window).scroll();
}

/*************************************/

function slider_init(){
	
	sliderCheckControl();
	
	var jQuerybox=jQuery('#big-slider-control .control-seek-box');
	var jQueryslidesInner=jQuery('#big-slider .big-slider-inner');
	var initialPos=0;
	var initialOffset=0;
	var seekWidth=0;
	var boxWidth=0;
	var lastDirection=0;
	var lastPageX=0;
	
	var slidesWidth=0;
	var slidesPaneWidth=0;
	
	var movehandler=function(e){
		var left=initialOffset+(e.pageX-initialPos);
		if(left < 0)
			left=0;
		if(left > seekWidth-boxWidth)
			left = seekWidth-boxWidth;
		
		var percent=left/(seekWidth-boxWidth);
			
		jQuerybox.css('left',left+'px');
		var offset=(slidesPaneWidth-slidesWidth)*percent;
		jQueryslidesInner.css('margin-left',offset+'px');
		
		lastDirection=lastPageX-e.pageX;
		lastPageX=e.pageX;
	}
	
	
	jQuerybox.mousedown(function(e){
		e.preventDefault();
		initialPos=e.pageX;
		initialOffset=parseInt(jQuerybox.css('left'));
		boxWidth=jQuerybox.width();
		seekWidth=jQuery('#big-slider-control .control-seek').width();
		
		slidesWidth=jQuery('#big-slider .big-slider-uber-inner').width();
		slidesPaneWidth=jQuery('#big-slider').width();
		
		jQuery(this).addClass('pressed');

		jQuery(document).bind('mousemove',movehandler);
	});

	jQuery(document).mouseup(function(){
		if(jQuerybox.hasClass('pressed')){
			jQuerybox.removeClass('pressed');
			jQuery(document).unbind('mousemove',movehandler);

			var jQueryfs=jQuery('#big-slider .big-slider-slide:first');
			var sw=jQueryfs.outerWidth()+parseInt(jQueryfs.css('margin-left'))+parseInt(jQueryfs.css('margin-right'));
			var ml=parseInt(jQueryslidesInner.css('margin-left'));
			if(lastDirection > 0) {
				ml=Math.ceil(ml/sw)*sw;
				if(ml > 0)
					ml=0;
			} else {
				ml=Math.floor(ml/sw)*sw;
				if(ml < slidesPaneWidth-slidesWidth)
					ml=slidesPaneWidth-slidesWidth;
			}
			jQueryslidesInner.stop(true).animate({marginLeft: ml+'px'}, 300);
			fitBox(ml);
		}
	});
	
	/***/
	
	function fitBox(newMarginLeft){
		jQuerybox.stop(true);
		
		var percent=newMarginLeft/(slidesPaneWidth-slidesWidth);

		boxWidth=jQuerybox.width();
		seekWidth=jQuery('#big-slider-control .control-seek').width();

		var left=(seekWidth-boxWidth)*percent;
		jQuerybox.animate({left:left+'px'},300);
	}
	
	
	jQuery('#big-slider-control .control-left').click(function(e){
		
		e.preventDefault();
		
		jQueryslidesInner.stop(true,true);
		
		var ml=parseInt(jQueryslidesInner.css('margin-left'));
		if(ml < 0)
		{
			var jQueryfs=jQuery('#big-slider .big-slider-slide:first');
			var sw=jQueryfs.outerWidth()+parseInt(jQueryfs.css('margin-left'))+parseInt(jQueryfs.css('margin-right'));
			ml+=sw;
			ml=Math.round(ml/sw)*sw;
			jQueryslidesInner.animate({marginLeft: ml+'px'}, 300);
			fitBox(ml);
			
			slider_clicks--;
			if(slider_clicks == 0)
				forward_go = true;
		}
		
		
	});
	

	jQuery('#big-slider-control .control-right').click(function(e){
		
		e.preventDefault();
		
		jQueryslidesInner.stop(true,true);
		
		slidesWidth=jQuery('#big-slider .big-slider-uber-inner').width();
		slidesPaneWidth=jQuery('#big-slider').width();
		var ml=parseInt(jQueryslidesInner.css('margin-left'));
		if(slidesWidth+ml > (slidesPaneWidth + 20))
		{
			var jQueryfs=jQuery('#big-slider .big-slider-slide:first');
			var sw=jQueryfs.outerWidth()+parseInt(jQueryfs.css('margin-left'))+parseInt(jQueryfs.css('margin-right'));
			ml-=sw;
			ml=Math.round(ml/sw)*sw;
			jQueryslidesInner.animate({marginLeft: ml+'px'}, 300);
			fitBox(ml);
			
			slider_clicks++;
			if(slider_clicks == 4)
				forward_go = false;
		}
		
	});
	
	/***/
	
	var touchStartPos=-1;
	var sliderInnerOffset=0;
	
	jQuery('#big-slider').bind('touchstart',function(e){
		
		var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
		touchStartPos=touch.pageX;
		
		slidesWidth=jQuery('#big-slider .big-slider-uber-inner').width();
		slidesPaneWidth=jQuery('#big-slider').width();
		sliderInnerOffset=parseInt(jQuery('#big-slider .big-slider-inner').css('margin-left'));
	});
	
	jQuery('#big-slider').bind('touchmove',function(e){
		if(touchStartPos>=0) {
			//e.preventDefault();
			
			var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
			
			var ml=sliderInnerOffset+touch.pageX-touchStartPos;
			if(ml > 0)
				ml=0;
			if(ml < slidesPaneWidth-slidesWidth)
				ml=slidesPaneWidth-slidesWidth;
			
			jQuery('#big-slider .big-slider-inner').css('margin-left',ml+'px');
			
			lastDirection=lastPageX-touch.pageX;
			lastPageX=touch.pageX;
		}
	});
	
	jQuery(document).bind('touchend', function(){
		touchStartPos=-1;
		
		var jQueryfs=jQuery('#big-slider .big-slider-slide:first');
		var sw=jQueryfs.outerWidth()+parseInt(jQueryfs.css('margin-left'))+parseInt(jQueryfs.css('margin-right'));
		var ml=parseInt(jQueryslidesInner.css('margin-left'));
		if(lastDirection < 0)
			ml=Math.ceil(ml/sw)*sw;
		else
			ml=Math.floor(ml/sw)*sw;
		jQueryslidesInner.stop(true).animate({marginLeft: ml+'px'}, 300);

	});
	
	/***/
	
	jQuery('#big-slider .big-slider-slide').mouseenter(function(){
		
		jQuery(this).find('.text-inner').stop(true,true).animate({top: '-120px'},150, function(){
			var jQuerytext=jQuery(this).find('.text-text');
			jQuerytext.stop(true,true);
			jQuery(this).css('top','120px');
			jQuerytext.css('top','30px');
			jQuery(this).animate({top: 0},150);
			jQuerytext.animate({top: 0},350);
		});
		
	});
}

function sliderRewind() {
	var jQuerybox=jQuery('#big-slider-control .control-seek-box');
	var jQueryslidesInner=jQuery('#big-slider .big-slider-inner');

	jQuerybox.css('left',0);
	jQueryslidesInner.css('margin-left',0);
	
}

function sliderCheckControl() {

	var sn=jQuery('#big-slider .big-slider-slide').length;
	var w=jQuery(window).width();

	if((sn < 4 && w >=768) || (sn == 1 && w < 768)) {
		jQuery('#big-slider-control').hide();
	} else {
		jQuery('#big-slider-control').show();
	}

}

/******************************************/

function menu_init(){

	if(!!('ontouchstart' in window)) {
		jQuery('.primary-menu li ul').each(function(){
			jQuery(this).parent().addClass('touch-childs').children('a').bind('touchstart',function(e){
				if(jQuery(this).parent().hasClass('active')) {
					menu_close(jQuery(this).parent().get(0));
				} else {				
					e.preventDefault();
					e.stopPropagation();

					jQuery(this).parent().parents('li.menu-item').addClass('thouch-not-to-close');
					jQuery('.primary-menu li.touch-childs').each(function(){
						if(!jQuery(this).hasClass('thouch-not-to-close'))
							menu_close(this);
					});
					jQuery('.primary-menu li.thouch-not-to-close').removeClass('thouch-not-to-close');
					
					menu_open(jQuery(this).parent().get(0));
				}
			}).mouseleave(function(){
				menu_close(this);
			});
		});
	} else {
		jQuery('.primary-menu li ul').each(function(){
			jQuery(this).parent().mouseenter(function(){
				menu_open(this);
			}).mouseleave(function(){
				menu_close(this);
			});
		});
	}
	
}

function menu_open(obj) {
	
	var jQueryul=jQuery(obj).addClass('active').children('ul');
	jQueryul.children('li').stop(true).css('opacity',0);
	jQueryul.stop(true,true).delay(150).slideDown(200,function(){
		var i=0;
		jQuery(this).children('li').each(function(){
			jQuery(this).fadeTo(100+100*i,1);
			i++;
		});
	});
}

function menu_close(obj) {
	jQuery(obj).removeClass('active');
	jQuery(obj).children('ul').stop(true,true).fadeOut(300);
}

/****************************/

function testimonials_init()
{
	jQuery('.testimonials-block').filter(':not(.no-scroll)').each(function(){
		
		var jQueryitems=jQuery(this).find('.items');
		if(jQueryitems.find('.item').length > 1) {

			jQuery(this).addClass('multi-items');

			jQueryitems.omSlider({
				speed: 200,
				next: jQuery(this).find('.controls .next'),
				prev: jQuery(this).find('.controls .prev'),
				fadePrev: true
			});
		
		} else {
			jQuery(this).find('.controls').remove();
		}
		
	});
}

/****************************/

function gallery_init()
{
	if(jQuery().omSlider) {
		jQuery('.custom-gallery').each(function(){
			var jQueryitems=jQuery(this).find('.items');
			if(jQueryitems.find('.item').length > 1) {
				
				var active=0;
				var hash=document.location.hash.replace('#','');
				if(hash != '') {
					var jQueryactive=jQueryitems.find('.item[rel='+hash+']');
					if(jQueryactive.length)
						active=jQueryactive.index();
				}
				jQuery(this).append('<div class="controls"><a href="#" class="next"></a><div class="pager"></div></div>');
				jQueryitems.omSlider({
					speed: 400,
					pager: jQuery(this).find('.controls .pager'),
					next: jQuery(this).find('.controls .next'),
					active: active
				});
			}
		});
	}
}


/****************************/

function comments_init()
{
	if(jQuery().validate) {
		jQuery("#commentform").validate({
			errorPlacement: function(error, element) {
			},
			wrapper: 'div'
		});
	}
}

/****************************/

function isotope_init()
{
	if(jQuery().isotope)
	{
		var jQuerycontainer=jQuery('#portfolio-wrapper');
		if(jQuerycontainer.length)
		{
	    var args={ 
		    itemSelector: '.isotope-item',
		    layoutMode: 'fitRows',
		    animationEngine: 'best-available'
		  };
		  
	    if(jQuerycontainer.hasClass('isotope-masonry')) {
	    	args.layoutMode='masonry';
	    	var jQuerytmp=jQuery('<div class="block-1" style="height:0"></div>').appendTo('body');
	    	args.masonry={columnWidth: (jQuerytmp.outerWidth() + parseInt(jQuerytmp.css('margin-left')) + parseInt(jQuerytmp.css('margin-right'))) };
	    	jQuerytmp.remove();
	    	args.resizable=false;
	    }
	
			var jQuerylinks=jQuery('.isotope-sort-menu').find('a');
      jQuerylinks.click(function(){
      	if(jQuery(this).hasClass('active'))
      		return false;
        jQuerylinks.removeClass('active');
        jQuery(this).addClass('active');

        var selector = jQuery(this).attr('href').split('#');
        selector=selector[1];

				args.filter='.'+selector;
        
        jQuerycontainer.isotope(args);
        
        return false;
      });

			jQuerycontainer.isotope(args);

    }
	}
}

function isotopeCheck()
{
	if(jQuery().isotope) {
		var jQuerycontainer=jQuery('#portfolio-wrapper.isotope-masonry');
		if(jQuerycontainer.length) {

    	var args={};			
    	var jQuerytmp=jQuery('<div class="block-1" style="height:0"></div>').appendTo('body');
    	args.masonry={columnWidth: (jQuerytmp.outerWidth() + parseInt(jQuerytmp.css('margin-left')) + parseInt(jQuerytmp.css('margin-right'))) };
    	jQuerytmp.remove();

			jQuerycontainer.isotope('option', args);
			jQuerycontainer.isotope('reLayout');
		}
	}
}

/*************************************/

function lightbox_init()
{
	//prettyPhoto
	if(jQuery().prettyPhoto) {
		jQuery('a[rel^=prettyPhoto]').addClass('pp_worked_up').prettyPhoto();
		var jQuerytmp=jQuery('a[hrefjQuery=".jpg"], a[hrefjQuery=".png"], a[hrefjQuery=".gif"], a[hrefjQuery=".jpeg"], a[hrefjQuery=".bmp"]').not('.pp_worked_up');
		jQuerytmp.each(function(){
			if(typeof(jQuery(this).attr('title')) == 'undefined')
				jQuery(this).attr('title',''); 
		});
		jQuerytmp.prettyPhoto();
	}
}

/*************************************/

function thumbs_masonry_init()
{
	if(jQuery().isotope)
	{
		var jQuerycontainer=jQuery('.thumbs-masonry');
		if(jQuerycontainer.length)
		{
	    var args={ 
		    itemSelector: '.isotope-item',
		    layoutMode: 'masonry',
		    animationEngine: 'best-available',
		    resisable: false
		  };
		  
    	var jQuerytmp=jQuery('<div class="block-1" style="height:0"></div>').appendTo('body');
    	args.masonry={columnWidth: (jQuerytmp.outerWidth() + parseInt(jQuerytmp.css('margin-left')) + parseInt(jQuerytmp.css('margin-right'))) };
    	jQuerytmp.remove();

			jQuerycontainer.isotope(args);

    }
	}
}

function thumbs_masonry_refresh()
{
	if(jQuery().isotope)
	{
		var jQuerycontainer=jQuery('.thumbs-masonry');
		if(jQuerycontainer.length) {
   		var args={};			
    	var jQuerytmp=jQuery('<div class="block-1" style="height:0"></div>').appendTo('body');
    	args.masonry={columnWidth: (jQuerytmp.outerWidth() + parseInt(jQuerytmp.css('margin-left')) + parseInt(jQuerytmp.css('margin-right'))) };
    	jQuerytmp.remove();

			jQuerycontainer.isotope('option', args);
			jQuerycontainer.isotope('reLayout');
		}
	}
}

/*********************************/

function tooltips_init()
{
	var tt_id=1;
	jQuery('.add-tooltip').each(function(){
		var title=jQuery(this).attr('title');
		if(typeof(title) == 'undefined')
			return;
		jQuery(this).data('tooltip_id',tt_id);
		
		jQuery(this).mouseenter(function(){
			jQuery(this).attr('title','');
			var id=jQuery(this).data('tooltip_id');
			var jQuerytt=jQuery('#tooltip_'+id).stop();
			if(!jQuerytt.length)
			{
				var pos=jQuery(this).offset();
				jQuerytt=jQuery('<div class="tooltip" id="tooltip_'+id+'">'+title+'</div>');
				jQuerytt.appendTo('body');
				jQuerytt.css('left',pos.left + Math.round(jQuery(this).outerWidth()/2));
				jQuerytt.css('top',pos.top - jQuerytt.outerHeight());
			}
			jQuerytt.show();
			jQuerytt.animate({opacity:1, marginTop: '-6px'}, 200);
		});

		jQuery(this).mouseleave(function(){
			jQuery(this).attr('title',title);
			var id=jQuery(this).data('tooltip_id');
			jQuery('#tooltip_'+id).stop().animate({opacity:0, marginTop: '-15px'}, 200, function(){
				jQuery(this).remove();
			});
		});

		tt_id++;
	});
}

/**********************************/

function toggle_init()
{

	jQuery('.accordion .toggle-title').addClass('in-accordion').click(function(){
		if(jQuery(this).hasClass('expanded'))
			return false;

		var jQueryacc=jQuery(this).parents('.accordion');
		jQueryacc.find('.toggle-title').removeClass('expanded');
		jQueryacc.find('.toggle-inner').slideUp(300);
		
		jQuery(this).parent().find('.toggle-inner').slideDown(300);
		jQuery(this).addClass('expanded');
		
	});
	
	jQuery('.toggle-title').not('.in-accordion').click(function(){
		var jQueryinner=jQuery(this).parent().find('.toggle-inner');
		if(!jQueryinner.length)
			return false;
		if(jQueryinner.is(':animated'))
			return false;
		
		jQuery(this).toggleClass('expanded');
		jQueryinner.slideToggle(300);
		
		return false;
	});
}

function tabs_init()
{
	
	jQuery('.tabs').each(function(){
		jQuery(this).find('.tabs-control a:first').addClass('active');
		jQuery(this).find('.tabs-tabs .tabs-tab:first').addClass('active').show();
	});
	
	jQuery('.tabs .tabs-control a').click(function(){
		var jQuerytabs=jQuery(this).parents('.tabs');
		if(!jQuerytabs.length)
			return false;
			
		var tabname=jQuery(this).attr('href').replace('#','');
		
		jQuerytabs.find('.tabs-control a').removeClass('active');
		jQuery(this).addClass('active');
		
		var jQuerynewtab=jQuerytabs.find('.tabs-tabs .tabs-tab.'+tabname);
		
		jQuerytabs.stop(true);
		var cur_h=jQuerytabs.height();
		var new_h=jQuerynewtab.outerHeight() + jQuerytabs.find('.tabs-control').outerHeight();
		new_h++; // only for current template
		if(Math.abs(cur_h - new_h) > 4) {
			jQuerytabs.css('height',cur_h+'px');
			jQuerytabs.animate({height: new_h + 'px'}, 300, function(){
				jQuery(this).css('height','auto');
			});
		}
		
		jQuerytabs.find('.tabs-tabs .tabs-tab.active').hide().removeClass('active');
		jQuerynewtab.addClass('active').fadeIn(300);
		
		
		return false;
	});

}

/**********************************/

function contact_form_init() {
	
	if( jQuery().validate && jQuery().ajaxSubmit ) {
		
	  var options = {
			success: contact_form_success,
			beforeSubmit: contact_form_before
		}; 	
		
		jQuery("#contact-form").validate({
			submitHandler: function(form) {
				jQuery(form).ajaxSubmit(options);
			},
			errorPlacement: function(error, element) {
				if(jQuery(element).attr('type') == 'checkbox')
					error.insertAfter(element);
			},
			errorClass: 'error'
		});
	}
}

function contact_form_before()
{
	var jQueryobj=jQuery('#contact-form');
	jQueryobj.fadeTo(300,0.5);
	jQueryobj.before('<div id="contact-form-blocker" style="position:absolute;width:'+jQueryobj.outerWidth()+'px;height:'+jQueryobj.outerHeight()+'px;z-index:9999;"></div>');
}

function contact_form_success(resp)
{
	jQuery('#contact-form-blocker').remove();
	if(resp == '0')
	{
		jQuery('#contact-form').fadeOut(300,function(){
			jQuery('#contact-form').remove();
			jQuery('#contact-form-success').fadeIn(200);
		});
	}
	else
	{
		jQuery('#contact-form').fadeOut(300,function(){
			jQuery('#contact-form').remove();
			jQuery('#contact-form-error').fadeIn(200);
		});		
	}
}

/************************************/

function logos_init()
{
	jQuery('.logos img').addClass('to_process');
	jQuery('.logos a').wrap('<div class="item" />').find('img').removeClass('to_process');
	jQuery('.logos img.to_process').removeClass('to_process').wrap('<div class="item" />');
}

/************************************/

function sidebar_slide_init()
{
	var jQuerycontent=jQuery('.content-with-sidebar:first');
	var jQuerysidebar=jQuery('.sidebar:first');
	
	jQuerysidebar.mouseenter(function(){
		if(jQuery(this).is(':animated'))
			jQuery(this).stop(true);
	});
	
	if(jQuerycontent.length && jQuerysidebar.length)
	{
		var sidebar_timer=false;
		var ie8=jQuery.browser.msie && (jQuery.browser.version == 8);
		
		jQuery(window).scroll(function(){

			if(sidebar_timer)
				clearTimeout(sidebar_timer);
				
			if(jQuery(window).data('mobile-view'))
			{
				jQuerysidebar.stop(true).css({marginTop: 0});
				return;
			}

			sidebar_timer=setTimeout(function(){
				jQuerysidebar.stop(true);
				
				var sh=jQuerysidebar.height();
				var ch=jQuerycontent.height();
				
				if(ch > sh)
				{
					var top=jQuerysidebar.offset();
					var ws=jQuery(window).scrollTop();

					var cur_mar=parseInt(jQuerysidebar.css('margin-top'));
					var max=ch-sh;
					var new_mar=ws-(top.top-cur_mar)+6;
					if(new_mar > max)
						new_mar = max;
					if(new_mar < 0)
						new_mar = 0;
					
					var hover=false;
					if(!ie8)
						hover = jQuerysidebar.is(':hover');
						
					if(new_mar != cur_mar && !hover) 
						jQuerysidebar.stop(true).animate({marginTop: new_mar+'px'}, 800, 'easeInOutExpo');
				}
				
			}, 1290);
			
		});
	}
}

function sort_menu_init()
{
	
	jQuery('.sort-menu li a .count').wrapInner('<span />');
	jQuery('.sort-menu li a').mouseenter(function(){
		var jQuerycount=jQuery(this).find('.count span');
		if(jQuerycount.is(':animated'))
			return;
		jQuerycount.stop(true).animate({top: '24px'}, 150, function(){
			jQuery(this).css('top','-24px').animate({top: 0}, 200);
		})
	});
}

	/***/
	var slider_clicks = 0;
	var forward_go = true;
	
	function slider_auto_scroll()
	{
		var timer = setInterval(function(){
			if(forward_go && slider_clicks < 4 )
			{
				jQuery('#big-slider-control a.control-right').trigger('click');
			}
			else 
			{
				jQuery('#big-slider-control a.control-left').trigger('click');
			}
		},5000);	
	}
	
//////////EVENTS RELATED CUSTOM SCRIPT
	
	/**
	 *  
	 */
	jQuery(document).ready(function(jQuery){
		 
		
		jQuery('#send_message_to_classified_author').live('click',function(ev){
			var _this = jQuery(this);
			
			ev.preventDefault();
			if(jQuery('#contact_author').valid())
			{
				jQuery('#send_message_form').find('div.alert').remove();
				jQuery('#send_message_form').prepend('<div class="alert"> <button type="button" class="close" data-dismiss="alert">x</button><strong>Sending message....</strong></div>');	
					
				var data = {
					action: 'send_message_to_author',
					message_to: jQuery('#message_to').val(),
					message_subject: jQuery('#message_subject').val(),
					message_body : jQuery('#message_body').val()
				};

				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				jQuery.post(ajaxurl, data, function(response) {
					console.log(response);
					if(response == 0)
					{
						jQuery('#send_message_form').find('div.alert').remove();
						jQuery('#send_message_form').prepend('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">x</button><strong>Failed to send message. Please try again</strong></div>');
					}
					else
					{
						jQuery('#contact_author').fadeOut(500,function(){
							jQuery('#send_message_form').html('<div class="alert alert-success"><strong>Message successfully sent. Thank you!!</strong></div>');
							_this.hide();
						});
					}
				});
			}	
		});
		
		jQuery('.delete_review').click(function(){
			var c = confirm("Are you sure you want to delete?");
			jQuery('#content').find('div.alert').remove();
			if(c == true)
			{
				jQuery(this).html('Deleting. . .');
				var _this = jQuery(this);	
				var data = {
					action: 'delete_bookreview',
					bookreview_id : jQuery(this).attr('bookreview_id') 
				};
			
				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				jQuery.post(ajaxurl, data, function(response) {
					if(response == 1)
					{
						window.location.href = jQuery('input[name="bookreview_link"]').val();
					}	
					else
					{
						jQuery('#content').prepend('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">x</button><strong>Failed to delete book review. Please try again</strong></div><div class="clear"></div>');
						jQuery(_this).html('Delete');
					}
				});
			}	
		});	
		
		jQuery('#classifieds_post').validate({
			rules : {
				classified_title : 'required'
			}
		});
		
	});

	//SINGLE EVENT SCRIPTS
	jQuery(document).ready(function(jQuery) {
		jQuery(window).load(function(){
			jQuery("#feed-scroll").mCustomScrollbar({
				scrollButtons:{
					enable:false
				}
			});
		});

		

		jQuery('a.rsvp-action').live('click',function(ev){
			ev.preventDefault();
			jQuery('a.rsvp-action').removeClass('btn-success');
			jQuery(this).addClass('btn-success').attr('disabled',true);

			var _response = jQuery(this).html();
			
			// send data to server
			jQuery.post(ajaxurl,
					{
						action   : 'agc_send_user_event_rsvp',
						event_id : jQuery('#event_id').val(),
						response : _response
					},
					function(response)
					{
 
						jQuery('a.rsvp-action').removeAttr('disabled');
						jQuery('#rsvp-pop').attr('data-content',response.html);
						//jQuery('#myModal').modal('show');
						var msg = '';
						switch(response.code)
						{
							case "0": 	
								msg = _agc_show_alert("rsvp-res",'error',"Failed: ",response.response);
								break;
							case "1":
								msg = _agc_show_alert("rsvp-res",'success',"Successfully saved: ",response.response);
								break;
							case "2":
								msg = _agc_show_alert("rsvp-res",'success',"Successfully updated: ",response.response);
								break;
							default:
								break;			
						}	
						jQuery('#rsvp-response-div').empty().hide().html(msg).slideToggle();		
						var event_categories = cs.split(", ");
						//if not date party then refresh page.
						console.log(_response);
						if((jQuery.inArray('Date Party',event_categories) == -1) || (_response == 'No'))
						{
							window.location.href = event_link;	
						}
						//else show the select date box.
						else
						{
							_agc_suggest_dates_box(_response,response.date);
						}
						
					},
					'json');	
		});
		
		//jQuery('.full-width').horizontalNav();
		//ToolTip Init
		//jQuery('a[rel="tooltip"]').tooltip();

		//jQuery(".alert").alert();
		
		//jQuery('#rsvp-pop').popover('toggle');
		
		
		/** NO ACCESS LOGIN LOGIC**/
		var jq 			= jQuery;
		jq('#agc-no-access-login-submit').live('click',function(){
			var b = jq(this);
			var u = jq('#agc-no-access-uname').val();
			var p = jq('#agc-no-access-pwd').val();
			var n = b.attr('data-nonce');
			var l = jq('#agc-no-access-login-loader');
			var d = {
						action		:	'agc_no_access_login',
						'_wpnonce'	: 	n,
						'uname'		:	u,
						'pass'		:	p,
					};
			l.show();
			jq.post(ajaxurl,d,function(r){
					if(r.result == 'success')
					{
						var a = _agc_show_alert('agc-login-no-access-alert',r.result,'Success!',r.msg);
						jq('#agc-no-access-login #agc-no-access-alerts').empty().append(a);
						window.location.href = redirect_to;
					}
					else
					{
						var a = _agc_show_alert('agc-login-no-access-alert',r.result,'Oops!',r.msg);
						jq('#agc-no-access-login #agc-no-access-alerts').empty().append(a);
					}
					l.hide();
				});
		});
		jq('#agc-no-access-login').bind('hidden',function(){
				jq('#agc-no-access-login #agc-no-access-alerts').empty();
			});
		/**END NO ACCESS LOGIN LOGIC**/

	});


	function _agc_show_alert(id,type,title,msg)
	{
		var a = '<div id="'+ id +'" class="alert alert-'+ type +' fade in" style="margin: 0 auto;">';
		a += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
		a += '<strong>'+ title +'</strong> '+ msg +'</div>';
		return a;
	}


	function _agc_suggest_dates_box(response,date)
	{
		
		//GET EVENT CATEGORIES.	
		var c 		= cs.split(", ");
		
		if(jq.inArray('Date Party',c) != -1)
		{	
			var i = '<label for="suggest_dates" class="suggest_dates_label">Since this is a date party, enter the name of the date you are bringing along.</label>'+
					'<div class="suggest-input"><input value="" data-id="" class="suggest_dates_input" type="text" name="suggest_dates" id="suggest_dates" onkeyup="_agc_suggest_names(this)"/>'+
					'<span class="loading-16" id="suggest_dates_loader" style="display:none;"></span></div>'+
					'<button class="btn btn-primary btn-mini agc-bring-along" data-nonce="'+ event_nonce +'" onClick="_agc_submit_selected_name(this);">Add +1</button>'+
					'<span class="loading-16" id="submit_dates_loader" style="display:none;"></span>'+
					'<div class="agc_suggest_scroll"><div class="agc_suggested_name"></div></div>';
		}
		else
		{
			var i ='';
		}
		if(response == 'Yes' || response == 'Maybe')
		{
			if(date == false){
				jq('#agc-suggest-dates-box').empty().append(i);
				jq('#agc-suggest-dates-box').next('div').remove();
			}
		}
		else
		{
			jq('#agc-suggest-dates-box').empty();
			jq('#agc-suggest-dates-box').next('div').remove();
		}
	}

	//Auto suggest names based on user input.
	function _agc_suggest_names(input)
	{
		var v 	= jq(input).val();
		var l 	= jq('#suggest_dates_loader');
		var dc 	= jq('.agc_suggested_name');

		jq('input.suggest_dates_input').attr('data-id',''); //reset the id
		
		if(v.length != 0)
		{
			var d = {
						action:'agc_event_suggest_names',
						q:v,
					};
			l.show();
			jq.get(ajaxurl,d,function(r){
				if(r.trim() == ''){
					dc.empty().append('No Users Found.');
				}
				else{
					dc.empty().append(r);
					dc.focus();
				}
				l.hide();
				jq(".agc_suggest_scroll").mCustomScrollbar({
					advanced:{ updateOnContentResize: true, updateOnBrowserResize: true }
				});
			});
		}
		else
		{
			dc.empty();
		}
	}
	//On name selected
	function _agc_select_suggested_name(selected)
	{
			var n 	= jq(selected).parent().attr('data-name');
			var id	= jq(selected).parent().attr('data-id'); 
			var dc 	= jq('.agc_suggested_name');
		
			jq('input.suggest_dates_input').val(n);
			jq('input.suggest_dates_input').attr('data-id',id);	
			dc.empty();
			jq("div.agc_suggest_scroll").remove();
	}

	//On name submit
	function _agc_submit_selected_name(button)
	{
		var l 	= jq('#submit_dates_loader');
		var id 	= jq('input.suggest_dates_input').attr('data-id');
		var i 	= jq('input.suggest_dates_input');
		var b 	= jq(button);
		var n	= b.attr('data-nonce');
		
		if(id == '')
		{
			alert('Please choose a name from the suggested list.');
		}
		else
		{
			l.show();
			i.prop('disabled', true);
			b.prop('disabled', true);
			var d={
						action		:'agc_event_save_plus_one',
						'_wpnonce'	:n,
						'id'		:id,
						'eid'		:event_id,
					};
			jq.post(ajaxurl,d,function(r){

				jq('#agc-suggest-dates-box').empty().after(r.result);
				
				l.hide();
				i.prop('disabled', false);
				b.prop('disabled', false);
			});

		}
		
	}

	function _agc_remove_plusone(button)
	{
		var b 	= jq(button);
		var w	= b.parent().before('<p>Please Wait ...</p>');
		b.parent().fadeOut('slow');

		var d = {
					action		:'agc_ajax_event_cancel_plus_one',
					'_wpnonce'	: remove_nonce,
					'eid'		: event_id,
				};

		console.log(d);
		jq.post(ajaxurl,d,function(r){
				console.log(r);
				window.location.href = event_link;
			});
	}


	/****Dropdown Menu Basic Function****/
	jQuery.fn.nav_dropdown = function(options) {

	 var defaults = {};
	 var opts = jQuery.extend(defaults, options);

	 // Apply on those items with children
	 this.each(function() {
	   jQuery(this).find('li').each(function() {
	     if(jQuery(this).find("ul").length > 0) {
	       jQuery(this).addClass("hasChildren");
	       jQuery(this).find('> a').addClass('arrow');
	     }
	   });
	 });

	 // Apply on all list items
	 jQuery(this).find("nav ul li").hover(function() {
	   jQuery(this).addClass('hover');
	 }, function() {
	   jQuery(this).removeClass('hover');
	 });

	 jQuery('nav ul li').has('ul').hover(function(){
	   jQuery(this).children('ul').show();
	 }, function() {
	   jQuery(this).children('ul').hide();
	 });
	};