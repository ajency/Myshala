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
	
	var $box=jQuery('#big-slider-control .control-seek-box');
	var $slidesInner=jQuery('#big-slider .big-slider-inner');
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
			
		$box.css('left',left+'px');
		var offset=(slidesPaneWidth-slidesWidth)*percent;
		$slidesInner.css('margin-left',offset+'px');
		
		lastDirection=lastPageX-e.pageX;
		lastPageX=e.pageX;
	}
	
	
	$box.mousedown(function(e){
		e.preventDefault();
		initialPos=e.pageX;
		initialOffset=parseInt($box.css('left'));
		boxWidth=$box.width();
		seekWidth=jQuery('#big-slider-control .control-seek').width();
		
		slidesWidth=jQuery('#big-slider .big-slider-uber-inner').width();
		slidesPaneWidth=jQuery('#big-slider').width();
		
		jQuery(this).addClass('pressed');

		jQuery(document).bind('mousemove',movehandler);
	});

	jQuery(document).mouseup(function(){
		if($box.hasClass('pressed')){
			$box.removeClass('pressed');
			jQuery(document).unbind('mousemove',movehandler);

			var $fs=jQuery('#big-slider .big-slider-slide:first');
			var sw=$fs.outerWidth()+parseInt($fs.css('margin-left'))+parseInt($fs.css('margin-right'));
			var ml=parseInt($slidesInner.css('margin-left'));
			if(lastDirection > 0) {
				ml=Math.ceil(ml/sw)*sw;
				if(ml > 0)
					ml=0;
			} else {
				ml=Math.floor(ml/sw)*sw;
				if(ml < slidesPaneWidth-slidesWidth)
					ml=slidesPaneWidth-slidesWidth;
			}
			$slidesInner.stop(true).animate({marginLeft: ml+'px'}, 300);
			fitBox(ml);
		}
	});
	
	/***/
	
	function fitBox(newMarginLeft){
		$box.stop(true);
		
		var percent=newMarginLeft/(slidesPaneWidth-slidesWidth);

		boxWidth=$box.width();
		seekWidth=jQuery('#big-slider-control .control-seek').width();

		var left=(seekWidth-boxWidth)*percent;
		$box.animate({left:left+'px'},300);
	}
	
	
	jQuery('#big-slider-control .control-left').click(function(e){
		
		e.preventDefault();
		
		$slidesInner.stop(true,true);
		
		var ml=parseInt($slidesInner.css('margin-left'));
		if(ml < 0)
		{
			var $fs=jQuery('#big-slider .big-slider-slide:first');
			var sw=$fs.outerWidth()+parseInt($fs.css('margin-left'))+parseInt($fs.css('margin-right'));
			ml+=sw;
			ml=Math.round(ml/sw)*sw;
			$slidesInner.animate({marginLeft: ml+'px'}, 300);
			fitBox(ml);
			
			slider_clicks--;
			if(slider_clicks == 0)
				forward_go = true;
		}
		
		
	});
	

	jQuery('#big-slider-control .control-right').click(function(e){
		
		e.preventDefault();
		
		$slidesInner.stop(true,true);
		
		slidesWidth=jQuery('#big-slider .big-slider-uber-inner').width();
		slidesPaneWidth=jQuery('#big-slider').width();
		var ml=parseInt($slidesInner.css('margin-left'));
		if(slidesWidth+ml > (slidesPaneWidth + 20))
		{
			var $fs=jQuery('#big-slider .big-slider-slide:first');
			var sw=$fs.outerWidth()+parseInt($fs.css('margin-left'))+parseInt($fs.css('margin-right'));
			ml-=sw;
			ml=Math.round(ml/sw)*sw;
			$slidesInner.animate({marginLeft: ml+'px'}, 300);
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
		
		var $fs=jQuery('#big-slider .big-slider-slide:first');
		var sw=$fs.outerWidth()+parseInt($fs.css('margin-left'))+parseInt($fs.css('margin-right'));
		var ml=parseInt($slidesInner.css('margin-left'));
		if(lastDirection < 0)
			ml=Math.ceil(ml/sw)*sw;
		else
			ml=Math.floor(ml/sw)*sw;
		$slidesInner.stop(true).animate({marginLeft: ml+'px'}, 300);

	});
	
	/***/
	
	jQuery('#big-slider .big-slider-slide').mouseenter(function(){
		
		jQuery(this).find('.text-inner').stop(true,true).animate({top: '-120px'},150, function(){
			var $text=jQuery(this).find('.text-text');
			$text.stop(true,true);
			jQuery(this).css('top','120px');
			$text.css('top','30px');
			jQuery(this).animate({top: 0},150);
			$text.animate({top: 0},350);
		});
		
	});
}

function sliderRewind() {
	var $box=jQuery('#big-slider-control .control-seek-box');
	var $slidesInner=jQuery('#big-slider .big-slider-inner');

	$box.css('left',0);
	$slidesInner.css('margin-left',0);
	
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
	
	var $ul=jQuery(obj).addClass('active').children('ul');
	$ul.children('li').stop(true).css('opacity',0);
	$ul.stop(true,true).delay(150).slideDown(200,function(){
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
		
		var $items=jQuery(this).find('.items');
		if($items.find('.item').length > 1) {

			jQuery(this).addClass('multi-items');

			$items.omSlider({
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
			var $items=jQuery(this).find('.items');
			if($items.find('.item').length > 1) {
				
				var active=0;
				var hash=document.location.hash.replace('#','');
				if(hash != '') {
					var $active=$items.find('.item[rel='+hash+']');
					if($active.length)
						active=$active.index();
				}
				jQuery(this).append('<div class="controls"><a href="#" class="next"></a><div class="pager"></div></div>');
				$items.omSlider({
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
		var $container=jQuery('#portfolio-wrapper');
		if($container.length)
		{
	    var args={ 
		    itemSelector: '.isotope-item',
		    layoutMode: 'fitRows',
		    animationEngine: 'best-available'
		  };
		  
	    if($container.hasClass('isotope-masonry')) {
	    	args.layoutMode='masonry';
	    	var $tmp=jQuery('<div class="block-1" style="height:0"></div>').appendTo('body');
	    	args.masonry={columnWidth: ($tmp.outerWidth() + parseInt($tmp.css('margin-left')) + parseInt($tmp.css('margin-right'))) };
	    	$tmp.remove();
	    	args.resizable=false;
	    }
	
			var $links=jQuery('.isotope-sort-menu').find('a');
      $links.click(function(){
      	if(jQuery(this).hasClass('active'))
      		return false;
        $links.removeClass('active');
        jQuery(this).addClass('active');

        var selector = jQuery(this).attr('href').split('#');
        selector=selector[1];

				args.filter='.'+selector;
        
        $container.isotope(args);
        
        return false;
      });

			$container.isotope(args);

    }
	}
}

function isotopeCheck()
{
	if(jQuery().isotope) {
		var $container=jQuery('#portfolio-wrapper.isotope-masonry');
		if($container.length) {

    	var args={};			
    	var $tmp=jQuery('<div class="block-1" style="height:0"></div>').appendTo('body');
    	args.masonry={columnWidth: ($tmp.outerWidth() + parseInt($tmp.css('margin-left')) + parseInt($tmp.css('margin-right'))) };
    	$tmp.remove();

			$container.isotope('option', args);
			$container.isotope('reLayout');
		}
	}
}

/*************************************/

function lightbox_init()
{
	//prettyPhoto
	if(jQuery().prettyPhoto) {
		jQuery('a[rel^=prettyPhoto]').addClass('pp_worked_up').prettyPhoto();
		var $tmp=jQuery('a[href$=".jpg"], a[href$=".png"], a[href$=".gif"], a[href$=".jpeg"], a[href$=".bmp"]').not('.pp_worked_up');
		$tmp.each(function(){
			if(typeof(jQuery(this).attr('title')) == 'undefined')
				jQuery(this).attr('title',''); 
		});
		$tmp.prettyPhoto();
	}
}

/*************************************/

function thumbs_masonry_init()
{
	if(jQuery().isotope)
	{
		var $container=jQuery('.thumbs-masonry');
		if($container.length)
		{
	    var args={ 
		    itemSelector: '.isotope-item',
		    layoutMode: 'masonry',
		    animationEngine: 'best-available',
		    resisable: false
		  };
		  
    	var $tmp=jQuery('<div class="block-1" style="height:0"></div>').appendTo('body');
    	args.masonry={columnWidth: ($tmp.outerWidth() + parseInt($tmp.css('margin-left')) + parseInt($tmp.css('margin-right'))) };
    	$tmp.remove();

			$container.isotope(args);

    }
	}
}

function thumbs_masonry_refresh()
{
	if(jQuery().isotope)
	{
		var $container=jQuery('.thumbs-masonry');
		if($container.length) {
   		var args={};			
    	var $tmp=jQuery('<div class="block-1" style="height:0"></div>').appendTo('body');
    	args.masonry={columnWidth: ($tmp.outerWidth() + parseInt($tmp.css('margin-left')) + parseInt($tmp.css('margin-right'))) };
    	$tmp.remove();

			$container.isotope('option', args);
			$container.isotope('reLayout');
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
			var $tt=jQuery('#tooltip_'+id).stop();
			if(!$tt.length)
			{
				var pos=jQuery(this).offset();
				$tt=jQuery('<div class="tooltip" id="tooltip_'+id+'">'+title+'</div>');
				$tt.appendTo('body');
				$tt.css('left',pos.left + Math.round(jQuery(this).outerWidth()/2));
				$tt.css('top',pos.top - $tt.outerHeight());
			}
			$tt.show();
			$tt.animate({opacity:1, marginTop: '-6px'}, 200);
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

		var $acc=jQuery(this).parents('.accordion');
		$acc.find('.toggle-title').removeClass('expanded');
		$acc.find('.toggle-inner').slideUp(300);
		
		jQuery(this).parent().find('.toggle-inner').slideDown(300);
		jQuery(this).addClass('expanded');
		
	});
	
	jQuery('.toggle-title').not('.in-accordion').click(function(){
		var $inner=jQuery(this).parent().find('.toggle-inner');
		if(!$inner.length)
			return false;
		if($inner.is(':animated'))
			return false;
		
		jQuery(this).toggleClass('expanded');
		$inner.slideToggle(300);
		
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
		var $tabs=jQuery(this).parents('.tabs');
		if(!$tabs.length)
			return false;
			
		var tabname=jQuery(this).attr('href').replace('#','');
		
		$tabs.find('.tabs-control a').removeClass('active');
		jQuery(this).addClass('active');
		
		var $newtab=$tabs.find('.tabs-tabs .tabs-tab.'+tabname);
		
		$tabs.stop(true);
		var cur_h=$tabs.height();
		var new_h=$newtab.outerHeight() + $tabs.find('.tabs-control').outerHeight();
		new_h++; // only for current template
		if(Math.abs(cur_h - new_h) > 4) {
			$tabs.css('height',cur_h+'px');
			$tabs.animate({height: new_h + 'px'}, 300, function(){
				jQuery(this).css('height','auto');
			});
		}
		
		$tabs.find('.tabs-tabs .tabs-tab.active').hide().removeClass('active');
		$newtab.addClass('active').fadeIn(300);
		
		
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
	var $obj=jQuery('#contact-form');
	$obj.fadeTo(300,0.5);
	$obj.before('<div id="contact-form-blocker" style="position:absolute;width:'+$obj.outerWidth()+'px;height:'+$obj.outerHeight()+'px;z-index:9999;"></div>');
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
	var $content=jQuery('.content-with-sidebar:first');
	var $sidebar=jQuery('.sidebar:first');
	
	$sidebar.mouseenter(function(){
		if(jQuery(this).is(':animated'))
			jQuery(this).stop(true);
	});
	
	if($content.length && $sidebar.length)
	{
		var sidebar_timer=false;
		var ie8=jQuery.browser.msie && (jQuery.browser.version == 8);
		
		jQuery(window).scroll(function(){

			if(sidebar_timer)
				clearTimeout(sidebar_timer);
				
			if(jQuery(window).data('mobile-view'))
			{
				$sidebar.stop(true).css({marginTop: 0});
				return;
			}

			sidebar_timer=setTimeout(function(){
				$sidebar.stop(true);
				
				var sh=$sidebar.height();
				var ch=$content.height();
				
				if(ch > sh)
				{
					var top=$sidebar.offset();
					var ws=jQuery(window).scrollTop();

					var cur_mar=parseInt($sidebar.css('margin-top'));
					var max=ch-sh;
					var new_mar=ws-(top.top-cur_mar)+6;
					if(new_mar > max)
						new_mar = max;
					if(new_mar < 0)
						new_mar = 0;
					
					var hover=false;
					if(!ie8)
						hover = $sidebar.is(':hover');
						
					if(new_mar != cur_mar && !hover) 
						$sidebar.stop(true).animate({marginTop: new_mar+'px'}, 800, 'easeInOutExpo');
				}
				
			}, 1290);
			
		});
	}
}

function sort_menu_init()
{
	
	jQuery('.sort-menu li a .count').wrapInner('<span />');
	jQuery('.sort-menu li a').mouseenter(function(){
		var $count=jQuery(this).find('.count span');
		if($count.is(':animated'))
			return;
		$count.stop(true).animate({top: '24px'}, 150, function(){
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