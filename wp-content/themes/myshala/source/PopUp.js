/*
 *	PopUp.js
 *
 *	Speedo PopUp v1.1.2
 *
 *	Speedo PopUp is a lightweight jQuery plugin
 *	with powerful customization settings.
 *
 *	http://www.speedoproducts.com
 *	http://www.artflow.ro
 *	http://www.agapastudio.com
 *
 *	Copyright (c) 2012-2013 By Artflow & Agapastudio.All rights reserved.
 *
 *	License:
 *		http://www.speedoproducts.com/speedo-popup/license.php
 */

(function ($)
{

/*
 *	browser_ie - If the browser is not IE (or IE version is less than 5) then this value will be null.
 *				 If the browser is IE and the version is higher than 5 the value will be the verison of
 *				 the browser.
 */
var browser_ie = (function ()
{
	var ver = 3;
	var div = document.createElement('div');
	var all = div.getElementsByTagName('i')

	while (div.innerHTML = '<!--[if gt IE ' + (++ver) + ']><i></i><![endif]-->', all[0])
		{};		// We don't want to do anything.

	return ver > 4 ? ver : null;
});

function PopUp(options)
{
	/* Private vaiables */
	var self = this;
	
	var left = 0;
	var top = 0;
	var width = 0;
	var height = 0;

	var groupIndex = options.groupIndex;
	var groupTimeout = 0;

	var container = null;
	var closeBtn = null;
	var loadingImage = null;
	var contentHolder = null;
	var draggable = null;
	var overlay = null;
	var playPauseButton = null;

	// groupGallery.
	var nextButton = null;
	var prevButton = null;
	var groupCaption = null;
	var groupCount = null;

	var embededObject = false;
	var imageList = /\.(jpg|jpeg|gif|png|bmp|tiff)(.*)?$/i;
	var videoList =
	{
		swf:
		{
			regex: /[^\.]\.(swf)\s*$/i
		},
		youtube: 
		{
			regex: /youtube\.com\/watch/i,
			url: "http://www.youtube.com/embed/{id}?autoplay=1&amp;fs=1&amp;rel=0",
			token: '=',
			iframe: 1,
			index: 1
		},
		google:
		{
			regex: /google\.com\/videoplay/i,
			url: "http://video.google.com/googleplayer.swf?autoplay=1&amp;hl=en&amp;docId={id}",
			token: '=',
			index: 1
		},
		vimeo:
		{
			regex: /vimeo\.com/i,
			url: "http://player.vimeo.com/video/{id}?hd=1&amp;autoplay=1&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1",
			token: '/',
			iframe: 1,
			index: 3
		},
		metacafe:
		{
			regex: /metacafe\.com\/watch/i,
			url: "http://www.metacafe.com/fplayer/{id}/.swf?playerVars=autoPlay=yes",
			token: '/',
			index: 4
		},
		dailymotion:
		{
			regex: /dailymotion\.com\/video/i,
			url: "http://www.dailymotion.com/swf/video/{id}?additionalInfos=0&amp;autoStart=1",
			token: '/',
			index: 4
        },
		wordpress:
		{
			regex: /v.wordpress.com/i,
			url: "http://s0.videopress.com/player.swf?guid={id}&amp;v=1.01",
			token: '/',
			index: 3
		}
	};

	/* Public variables */
	this.instanceName = 'instance_' + (Math.random() * 5233);
	
	/*
	 *	createPopup() - Create the html structure of the popup.
	 */
	this.createPopup = function ()
	{
		// Make sure the theme string is lowercase.
		options.theme = options.theme.toLowerCase();

		// Theme class.
		var themeClass = (options.theme && options.theme != 'default') ? ' speedo-theme-' + options.theme : '';


		$('body').addClass('speedo-popup-ready');
		// Load the css for the theme.
		//loadTheme(options.theme);

		// Create the popup container.
		container = $(document.createElement('div'));
		container.addClass('speedo-container'+themeClass);

		if (options.css3Effects && options.css3Effects != "none")
		{
			container.addClass("speedo-effect-"+options.css3Effects.toLowerCase());
		}
		
		var newWidth = (options.width) ? options.width : 'auto';
		var newheight = (options.height) ? options.height : 'auto';

		container.css(
		{
			display: 'none',
		    width: newWidth,
		    height: newheight,
			'min-width': 150,
			'min-height': 150,
		    left: (options.left == 'center') ? '50%' : options.left,
		    top: (options.top == 'center') ? '50%' : options.top
		});

		container.appendTo('body');

		// If the draggable option is true we need to be able to drag the window.
		if (options.draggable)
		{
			draggable = $(document.createElement('div'));

			draggable.addClass('speedo-popup-drag-area');
			draggable.bind('mousedown', onMouseDown);

			container.append(draggable);
		}

		// If the caption option is not empty then we create a container for the caption.
		if (options.caption && options.caption != '')
		{
			var caption = $(document.createElement('p'));

			caption.addClass('speedo-popup-caption');
			caption.html(options.caption);
			container.append(caption);
		}

		// Create the loadingImage container.
		loadingImage = $(document.createElement('div'));

		loadingImage.addClass('speedo-popup-loading');
		container.append(loadingImage);
		
		// Create the content holder.
		contentHolder = $(document.createElement('div'));
		contentHolder.addClass('speedo-content-holder');
		contentHolder.appendTo(container);

		// Create custom buttons.
		var buttons = createButtons(options.buttons);

		if (buttons !== false)
		{
			container.append(buttons);
		}

		var contentType = getContentType();

		self.setContent((contentType != "html") ? options.href : options.htmlContent, contentType);

		// If the groupGallery option is not empty and it's count is higher than 1 then we create play/pause, next, prev buttons and caption.
		if (options.groupGallery && options.groupGallery.length > 1)
		{
			// Play Pause button.
			playPauseButton = $(document.createElement('a'));

			playPauseButton.attr("href", "javascript: void(0);");
			playPauseButton.attr("title", "Play/Pause");
			playPauseButton.addClass("speedo-popup-playpause-button play");
			playPauseButton.click(function ()
			{
				self.playContent();
			});

			container.append(playPauseButton);

			// Prev button
			prevButton = $(document.createElement('a'));

			prevButton.attr("href", "javascript: void(0);");
			prevButton.attr("title", "Previous");
			prevButton.addClass("speedo-popup-previous-button");
			prevButton.click(function ()
			{
				self.prevContent();
			});

			container.append(prevButton);

			// Next button
			nextButton = $(document.createElement('a'));

			nextButton.attr("href", "javascript: void(0);");
			nextButton.attr("title", "Next");
			nextButton.addClass("speedo-popup-next-button");
			nextButton.click(function ()
			{
				self.nextContent();
			});

			container.append(nextButton);

			// Group caption.
			groupCaption = $(document.createElement("p"));

			groupCaption.addClass("speedo-popup-group-caption");

			container.append(groupCaption);

			$(window).bind("mousewheel DOMMouseScroll onmousewheel", function (ev)
			{
				var ev = ev.originalEvent;
				var delta = ev.wheelDelta || ev.detail;

				if (delta > 0)
				{
					self.nextContent();
				}
				else if (delta < 0)
				{
					self.prevContent();
				}
			});

			// Group count.
			groupCount = $(document.createElement("p"));

			groupCount.addClass("speedo-popup-group-count");
			groupCount.text((groupIndex + 1) + "/" + options.groupGallery.length);

			container.append(groupCount);
		}

		if (options.close)
		{
			closeBtn = $(document.createElement('a'));
			
			closeBtn.addClass('speedo-ui-close');
			closeBtn.attr('href', 'javascript: void(0);');
			closeBtn.click(function (ev) { options.onClose(ev); self.hidePopup(); self.playContent(false); });
			closeBtn.html(options.closeCaption);
			
			container.append(closeBtn);
		}
		
		// Add overlay div and posibility to close popUp if you click on it
		if (options.overlay)
		{
			overlay = $(document.createElement('div'));
			overlay.addClass('speedo-overlay'+themeClass);
			overlay.appendTo('body');

			// If the function is an object we expect some parameters like opacity, zindex etc.
			if (typeof(options.overlay) == 'object')
			{
				var overlayOptions = $.extend({
					opacity: .70,
					zindex: 10000
				}, options.overlay);

				//overlay.css({opacity: overlayOptions.opacity, 'z-index': overlayOptions.zindex});
			}
			
			if (options.overlayClose)
			{
				overlay.click(function(ev){ options.onClose(ev); self.hidePopup(); self.playContent(false);});
			}
		}

		// On before show.
		options.onBeforeShow(container.get(0));
		
		// Center the popup.
		self.centerPopup();

		$(window).resize(function ()
		{
			// Center the popup.
			self.centerPopup();
		});

		self.showPopup();

		// Autoplay the gallery.
		if (options.groupAutoPlay)
		{
			self.playContent(true);
		}

		setTimeout(function ()
		{
			self.centerPopup();
		}, 100);
	};
	
	/*
	 *	init() - Initialize events and popup.
	 */
	this.init = function ()
	{
		if (options.esc)
		{
			$(document).bind('keydown', onKeyDown);
		}
	};
	
	/*
	 *	showPopup() - Show the popup.
	 */
	this.showPopup = function ()
	{
		if (embededObject)
		{
			var type = getContentType();
			self.setContent(options.href, type);
		}

		if ($.speedoPopup.smartSkins[options.theme] != undefined)
		{
			$.speedoPopup.smartSkins[options.theme](overlay, container);
		}

		if (!handleEffects(options.effectIn, options.css3Effects, true))
		{
			container.show();
		
			if (overlay)
			{
				overlay.show();
			}

		}

		// Center the popup.
		self.centerPopup();

		//container.addClass('speedo-effect-fadespin');
		//container.addClass('play');

		/*container.css({
			"-webkit-animation-play-state": "running"
		});*/

		// Callback.
		options.onShow(container.get(0));

		if (options.autoClose)
		{
			setTimeout(function(){ self.hidePopup();}, options.autoClose);
		}
	};
	
	/*
	 *	hidePopup() - hide the popup.
	 */
	this.hidePopup = function ()
	{
		var effects = handleEffects(options.effectOut, options.css3Effects, false, false, function ()
		{
			// We need to remove the flash beacuse we don't want to have the movie/music playing in background.
			if (embededObject)
			{
				contentHolder.html(' ');
			}

			/*self.autoChangeContent(false);
			$(this).removeClass("pause").addClass("play");*/

			if (overlay)
			{
				overlay.hide();
			}

			container.hide();

			if (options.unload)
			{
				if (overlay)
				{
					overlay.remove();
				}
				container.remove();
			}
		});

		if (!effects)
		{
			container.hide();

			if(overlay)
			{
				overlay.hide();
			}

			// We need to remove the flash beacuse we don't want to have the movie/music playing in background.
			if (embededObject)
			{
				contentHolder.html(' ');
			}

			/*self.autoChangeContent(false);
			$(this).removeClass("pause").addClass("play");*/

			if (options.unload)
			{
				if (overlay)
				{
					overlay.remove();
				}
				container.remove();
			}
		}
		
		// On Hide.
		options.onHide(container.get(0));
	};

	/*
	 *	centerPopup() - Center the popup on the screen.
	 */
	this.centerPopup = function ()
	{
		if (width <= 0)
		{
			width = container.width();
			height = container.height();

			//alert('width '+width);

			//console.log("the definition of insanity. "+width+" the new definition "+contentHolder.width());

			if (width > 150)
			{
				container.css({'max-width': width, 'max-height': height});
			}
		}

		//container.css({margin: 40});
		//console.log('Second time '+width);

		if (options.responsive)
		{
			var windowWidth = $(window).width();
			var windowHeight = $(window).height();
			var containerOuterWidth = container.outerWidth();
			var containerOuterHeight = container.outerHeight();
			var paddingHor = parseInt(container.css('padding-left')) + parseInt(container.css('padding-right'));
			var paddingVer = parseInt(container.css('padding-top')) + parseInt(container.css('padding-bottom'));

			if (windowWidth < (width + paddingHor + left) || windowHeight < (height + paddingVer + top))
			{
				var marginHor = parseInt(container.css('margin-left')) + parseInt(container.css('margin-right'));
				var marginVer = parseInt(container.css('margin-top')) + parseInt(container.css('margin-bottom'));
				var containerWidth = container.width();
				var containerHeight = container.height();
				var additionalWidth = (containerOuterWidth + marginHor) - containerWidth;
				var additionalHeight = (containerOuterHeight + marginVer) - containerHeight;

				var newWidth = windowWidth - additionalWidth;
				var newHeight = windowHeight - additionalHeight;

				container.css({'width': newWidth, 'height': newHeight});
			}
		}

		left = (options.left == 'center') ? Math.floor($(window).width() / 2) - (container.outerWidth() / 2) : options.left;
		top = (options.top == 'center') ? Math.floor($(window).height() / 2) - (container.outerHeight() / 2) : options.top;

		container.css(
		{
			left: left,
			top: top
			/*'margin-left': (options.left == 'center') ? -(container.width() / 2) : 0,
			'margin-top': (options.top == 'center') ? -(container.height() / 2) : 0*/
		});

	};

	/*
	 *	animatePopup() - Animate the popup for showing or hiding.
	 *
	 *	PARAMETERS:
	 *		effect		- Specify the effect to use. You can use one of the following:
	 *						'fade'			- Fade in/out effect.
	 *						'slideLeft'		- Slide left effect.
	 *						'slideRight'	- Slide right effect.
	 *		speed		- Specify the effect speed.
	 *		show		- Specify if the animation is for showing the popup or hidding the popup.
	 *		callback	- Speicfy a callback to be called when the animation finished.
	 */
	this.animatePopup = function (effect, speed, show, callback)
	{
		var callback = ($.isFunction(callback)) ? callback : function () {};
		//var effect = effect + ((show) ? 'In' : 'Out');

		if (show)
		{
			container.hide();
			if (overlay)
			{
				overlay.hide();
			}
		}

		switch (effect)
		{
		case 'slideLeft':
				if (show)
				{
					container.css('left', -width);
					container.stop().animate({left: left, opacity: 'toggle'}, speed, callback);
				}
				else
				{
					container.stop().animate({left: -width, opacity: 'toggle'}, speed, callback);
				}
			break;

		case 'slideRight':
				if (show)
				{
					container.css('left', $(window).width() + width);
					container.stop().animate({left: left, opacity: 'toggle'}, speed, callback);
				}
				else
				{
					container.stop().animate({left: $(window).width() + width, opacity: 'toggle'}, speed, callback);
				}
			break;

		case 'slideTop':
				if (show)
				{
					container.css('top', -height);
					container.stop().animate({top: top, opacity: 'toggle'}, speed, callback);
				}
				else
				{
					//container.css('top', top);
					container.stop().animate({top: -height, opacity: 'toggle'}, speed, callback);
				}
			break;

		case 'slideBottom':
				if (show)
				{
					container.css('top', $(window).height() + height);
					container.stop().animate({top: top, opacity: 'toggle'}, speed, callback);
				}
				else
				{
					container.stop().animate({top: $(window).height() + height, opacity: 'toggle'}, speed, callback);
				}
			break;

		case 'slideZoom':
				if (show)
				{
					container.css('top', -height);
					container.css('left', left + (width / 2));

					container.stop().animate({width: 'toggle',  left: left, top: top, opacity: 'toggle'}, speed, callback);
				}
				else
				{
					container.stop().animate({top: -height,  left: left + (width / 2), width: 'toggle', opacity: 'toggle'}, speed, callback);
				}
			break;

		case 'growBox':
				container.stop().animate({width: 'toggle', height: 'toggle'}, callback);
			break;

		case 'incerto':
				if (show)
				{
					container.css('left', left + (width / 2));
					container.stop().animate({width: 'toggle',  left: left}, speed, callback);

					container.css('top', top + (height / 2));
					container.animate({top: top}, speed, callback);
				}
				else
				{
					container.stop().animate({top: top + (height / 2)}, speed, callback);

					container.animate({width: 'toggle',  left: left + (width / 2)}, speed, callback);
				}
			break;

		case 'fade':
		default:
			var funcEff = (show) ? 'fadeIn' : 'fadeOut';

			container.stop();

			container[funcEff](speed, callback);
			break;
		}

		if (overlay)
		{
			overlay.stop().animate({opacity: 'toggle'}, speed, function ()
			{
				if (browser_ie != null && browser_ie <= 8)
				{
					// We want to remove the filter attribute so we see the transparency.
					// Note: The css('filter', '') or get(0).style.filter = '' won't work.
					overlay.get(0).style.removeAttribute('filter', false);
				}
				//overlay.css({'filter': 'none', background: 'orange'});
			});
		}
	};

	/*
	 *	playContent() - Play/Pause the auto change content. If the content is playing then the content will be stoped.
	 *
	 *	PARAMETERS:
	 *		play	- Specify if it should play.
	 */
	this.playContent = function (play)
	{
		// Only start if there is a group gallery.
		if (options.groupGallery && options.groupGallery.length > 0)
		{
			if (play == true || (play != false && playPauseButton.hasClass("play")))
			{
				playPauseButton.removeClass("play").addClass("pause");
				// We don't want to go to the next content directly.
				groupTimeout = setTimeout(function ()
				{
					self.autoChangeContent(true);
				}, options.groupWait);
			}
			else if (playPauseButton.hasClass("pause") || play == false)
			{
				self.autoChangeContent(false);
				playPauseButton.removeClass("pause").addClass("play");
			}
		}
	};

	/*
	 *	autoChangeContent() - Start/Stop the auto change content.
	 *
	 *	PARAMETERS:
	 *		start	- Start the content if this is true, otherwise stop.
	 */
	this.autoChangeContent = function (start)
	{
		if (start)
		{
			function goToNext()
			{
				if (!self.nextContent(function ()
				{
					clearTimeout(groupTimeout);
					groupTimeout = setTimeout(goToNext, options.groupWait);
				}))
				{
					if (options.loop)
					{
						groupIndex = -1;
						self.nextContent();

						clearTimeout(groupTimeout);
						groupTimeout = setTimeout(goToNext, options.groupWait);
					}
				}
			}

			goToNext();

			//groupTimeout = setTimeout(goToNext, options.groupWait);
		}
		else
		{
			clearTimeout(groupTimeout);
		}
	};

	/*
	 *	prevContent() - Go to the previous content in the group.
	 *
	 *	RETURN VALUE:
	 *		If the function succeeds, the return value is true.
	 *		If the function fails, the return value is false.
	 */
	this.prevContent = function (callback)
	{
		if (options.groupGallery && options.groupGallery.length >= 1)
		{
			var index = groupIndex - 1;

			if (index >= 0 && index < options.groupGallery.length)
			{
				var content = options.groupGallery[index].url;
				var title = options.groupGallery[index].title;

				content = (content) ? content : "";
				title = (title) ? title : "";

				handleEffects(options.effectIn, options.css3Effects, false, true, function ()
				{
					self.setContent(content);
					groupCaption.text(title);
					groupCount.text((index + 1)+"/" + options.groupGallery.length);

					groupIndex = index;
					handleEffects(options.effectIn, options.css3Effects, true, true);

					if ($.isFunction(callback))
					{
						callback();
					}
				});

				return true;
			}
		}

		return false;
	};

	/*
	 *	nextContent() - Go to the next content in the group.
	 *
	 *	RETURN VALUE:
	 *		If the function succeeds, the return value is true.
	 *		If the function fails, the return value is false.
	 */
	this.nextContent = function (callback)
	{
		var index = groupIndex + 1;

		if (options.groupGallery && options.groupGallery.length > index && index >= 0)
		{
			var content = options.groupGallery[index].url;
			var title = options.groupGallery[index].title;

			content = (content) ? content : "";
			title = (title) ? title : "";

			handleEffects(options.effectIn, options.css3Effects, false, true, function ()
			{
				self.setContent(content);
				groupCaption.text(title);
				groupCount.text((index + 1)+"/" + options.groupGallery.length);

				groupIndex = index;

				handleEffects(options.effectIn, options.css3Effects, true, true);

				if ($.isFunction(callback))
				{
					callback();
				}

			});

			return true;
		}

		return false;
	};
	
	/*
	 *	setContent() - Set popup content.
	 *
	 *	PARAMETERS:
	 *		content		- The contentent.
	 *		type		- The content type.
	 *		complete	- Content loading complete.
	 *
	 *	NOTE:
	 *		If the type is not specified, the function will try to determine
	 *		the type based on the provided content.
	 */
	this.setContent = function (content, type, complete)
	{
		var contentType = (type) ? type : getContentType(content);
		var complete = (complete) ? complete : function () {};

		// Clear the old content.
		contentHolder.html('');

		// Reset the max width so the popup size will be automatically get from the size.
		container.css({'max-width': '', 'max-height': ''});

		if (contentType == "html")
		{
			contentHolder.html(content);

			options.onComplete(container.get(0), contentType);
			complete(container.get(0), contentType);
			loadingImage.hide();
			self.centerPopup();
		}
		else if (contentType == "image")
		{
			var image = new Image();

			image.src	= content;
			$(image).load(function (ev)
			{
				options.onComplete(container.get(0), contentType);
				complete(container.get(0), contentType);
				// Hide the loading image.
				loadingImage.hide();

				//self.width($(this).width());
				//self.height($(this).height());

				container.css({'max-width': $(this).width(), 'max-height': $(this).height()});
				self.centerPopup();
			});
			contentHolder.append(image);
			//contentHolder.html('<img src="'+content+'" />');
		}
		else if (contentType == "ajax")
		{
			// Use ajax to load the popup content.
			$.ajax({
				type: content.type,
				data: content.data,
				url: content.url,
				beforeSend: function ()
				{
				},
				success: function (data)
				{
					contentHolder.html(data);
					// On complete.
					options.onComplete(container.get(0), contentType);
					complete(container.get(0), contentType);
					loadingImage.hide();
					self.centerPopup();
				}
			});
		}
		else if (contentType == "iframe")
		{
			var iFrameContent = $(document.createElement('iframe'));

			iFrameContent.attr('border', 0);
			iFrameContent.attr('frameBorder', 0);
			iFrameContent.attr('marginwidth', 0);
			iFrameContent.attr('marginheight', 0);
			iFrameContent.css({width: options.width, height: options.height});
			iFrameContent.get(0).src = options.href;
			iFrameContent.load(function ()
			{
				// On complete.
				options.onComplete(container.get(0), contentType);
				complete(container.get(0), contentType);
				loadingImage.hide();
				container.css({'max-width': $(this).width(), 'max-height': $(this).height()});
				self.centerPopup();
			});

			contentHolder.append(iFrameContent);
		}
		else if (contentType == "flash")
		{
			var flashObject = buildFlashObject(content, options.width, options.height, options.flashvars);

			contentHolder.append(flashObject);

			setTimeout(function ()
			{
				// On complete.
				options.onComplete(container.get(0), contentType);
				complete(container.get(0), contentType);
				container.css({'max-width': (options.width != 'auto') ? options.width : flashObject.width(), 'max-height': (options.height != 'auto') ? options.height : flashObject.height()});
				self.centerPopup();
				loadingImage.hide();
			}, 80);

			//contentHolder.append(flashObject);
		}
		else	// Unkonown content type.
		{
			contentHolder.html(content);

			options.onComplete(container.get(0), contentType);
			complete(container.get(0), contentType);
			self.centerPopup();
			loadingImage.hide();
		}
	};

	/*
	 *	width() - Get or set the popup width.
	 *
	 *	PARAMETERS:
	 *		value	- The new width value.
	 *		animate	- Animate the resize. Default is true.
	 *
	 *	RETURN VALUE:
	 *		Returns the current width of the popup.
	 */
	this.width = function (value, animate)
	{
		var oldValue = width;
		var animate = (animate == undefined) ? true : animate;

		if (value)
		{
			width = value;

			if (animate)
			{
				container.animate({width: value, left: Math.floor(value / 2)}, "slow");
			}
			else
			{
				container.css('width', value);
			}
		}

		return oldValue;
	};

	/*
	 *	height() - Get or set the popup height.
	 *
	 *	PARAMETERS:
	 *		value	- The new height value.
	 *		animate	- Animate the resize. Default is true.
	 *
	 *	RETURN VALUE:
	 *		Returns the current height of the popup.
	 */
	this.height = function (value, animate)
	{
		var oldValue = height;
		var animate = (animate == undefined) ? true : animate;

		if (value)
		{
			height = value;

			if (animate)
			{
				container.animate({height: value, top: Math.floor(value / 2)}, "slow");
			}
			else
			{
				container.css('height', value);
			}
		}

		return oldValue;
	};
	
	/* Private functions */

	/*
	 *	handleEffects() - Handle in/out effects.
	 *
	 *	PARAMETERS:
	 *		effect			- Effect to use.
	 *		css3Effect		- CSS3 effect.
	 *		show			- Specify if the effect is for show or for hide.
	 *		contentChange	- Specify if the effect is for content change.
	 *		callback		- Called when the animation finished.
	 *
	 *	RETURN VALUE:
	 *		If the function succeds, the return value is true, otherwise is false.
	 */
	function handleEffects(effect, css3Effect, show, contentChange, callback)
	{
		if (css3Effect && css3Effect != 'none' && (!browser_ie || browser_ie > 9))
		{
			/*if ($.isFunction(callback))
			{
				container.bind('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', callback);
			}*/

			var animationEnd = function (ev)
			{
				if ($.isFunction(callback))
				{
					callback();
				}
				// We want to unbind this event after it has been executed so we don't brake something.
				container.unbind('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', animationEnd);
			};

			container.bind('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', animationEnd);

			// Reset the animation so we can play back;
			/*container.css({
				"-webkit-animation-name": "none",
				"-moz-animation-name": "none",
				"-o-animation-name": "none",
				"-ms-animation-name": "none",
				"animation-name": "none"
			});

			setTimeout(function ()
			{
				container.css({
					"-webkit-animation-name": "",
					"-moz-animation-name": "",
					"-o-animation-name": "",
					"-ms-animation-name": "",
					"animation-name": ""
				});
			}, 1);*/

			if (show)
			{
				container.show();

				if(overlay)
				{
					overlay.show();
				}

				$('body').addClass("speedo-effect-"+ css3Effect.toLowerCase() +"-active");
				container.removeClass("speedo-effect-"+ css3Effect.toLowerCase() +"-reverse");
				container.addClass("speedo-effect-"+ css3Effect.toLowerCase() +"-normal");

				/*container.css({
					"-webkit-animation-direction": "normal",
					"-moz-animation-direction": "normal",
					"-o-animation-direction": "normal",
					"-ms-animation-direction": "normal",
					"animation-direction": "normal"
				});*/
			}
			else
			{
				//container.hide();

				/*container.css({
					"-webkit-animation-direction": "reverse",
					"-mox-animation-direction": "reverse",
					"-o-animation-direction": "reverse",
					"-ms-animation-direction": "reverse",
					"animation-direction": "reverse"
				});*/

				$('body').removeClass("speedo-effect-"+ css3Effect.toLowerCase() +"-active");
				container.addClass("speedo-effect-"+ css3Effect.toLowerCase() +"-reverse");
				container.removeClass("speedo-effect-"+ css3Effect.toLowerCase() +"-normal");

				if(overlay && !contentChange)
				{
					overlay.hide();
				}
			}

			container.css({
				"-webkit-animation-play-state": "running",
				"-moz-animation-play-state": "running",
				"-o-animation-play-state": "running",
				"-ms-animation-play-state": "running",
				"animation-play-state": "running"
			});

			return true;
		}

		if (effect && effect != 'none')
		{
			// If this is a function then we call it because we assume the user will handle the showing.
			if ($.isFunction(effect))
			{
				if (options.overlay)
				{
					effect(container.get(0), overlay.get(0));
				}
				else
				{
					effect(container.get(0));
				}
			}
			else if ($.isArray(effect))	// If this is a array we assume that it contains the effect name and the speed.
			{
				self.animatePopup(effect[0], effect[1], show, callback);
			}
			else // We assume that what remains is the effect name so we pass it to the animate function .
			{
				self.animatePopup(effect, 'slow', show, callback);
			}

			return true;
		}

		return false;
	}

	/*
	 *	createButtons() - Create custom buttons.
	 *
	 *	PARAMETERS:
	 *		buttons	- An array with objects data for buttons.
	 *
	 *	RETURN VALUE:
	 *		If the function succeds, the return value is an html div object with all the buttons.
	 *		If the function fails, the return value is false.
	 */
	function createButtons(buttons)
	{
		if ($.isArray(buttons) && buttons.length > 0)
		{
			var reservedAttr = ['html', 'action'];
			var buttonsContainer = $(document.createElement('div'));

			buttonsContainer.addClass('speedo-popup-custom-buttons');

			for (var i = 0; i < buttons.length; i++)
			{
				var button = $(document.createElement('a'));

				button.attr('href', 'javascript: void(0);');
				if (buttons[i]['html'])
				{
					button.html(buttons[i]['html']);
				}

				for (var key in buttons[i])
				{
					if (reservedAttr.indexOf(key) == -1)
					{
						button.attr(key, buttons[i][key]);
					}
				}

				if ($.isFunction(buttons[i]['action']))
				{
					// Register callback.
					button.click(buttons[i]['action']);
				}

				buttonsContainer.append(button);
			}

			return buttonsContainer;
		}

		return false;
	}

	/*
	 *	getContentType() - Get the content type.
	 *
	 *	PARAMETERS:
	 *		content	- Content.
	 *
	 *	RETURN VALUE:
	 *		Returns the type of the content.
	 */
	function getContentType(content)
	{
		var content = (content) ? content : options.href;
		var videoId = '';

		// Reset the embededObject.
		embededObject = false;

		if ((content == null || content == '') && options.htmlContent)
		{
			return 'html';
		}

		if (content.match(imageList))	// Check if the link is a image.
		{
			return 'image';
		}

		var type = '';

		$.each(videoList, $.proxy(function (i, e)
		{
			if (content.split('?')[0].match(e.regex))
			{
				if (e.token)
				{
					
					if(i =='vimeo' && content.split('/')[3] == 'video')
					{
						e.index = 4;
					}
					var videoId = content.split(e.token)[e.index].split('?')[0].split('&')[0];

					content = e.url.replace('{id}', videoId);

					//options.href = content;
				}

				// Set the default values for the  embeded flash.
				options.width = (options.width) ? options.width : 640;
				options.height = (options.height) ? options.height : 360;

				options.href = content;

				embededObject = true;

				type = (e.iframe) ? 'iframe' : 'flash';
			}
		}, this));

		if (type == '')
		{
			// If we want to use iFrame.
			if (options.useFrame || content.indexOf('http') >= 0)
			{
				type = "iframe";
				embededObject = true;
				options.href = content;
			}
			else
			{
				type = 'ajax';
			}

			var idStart = content.indexOf('#');

			if (idStart != -1)
			{
				var object = content.substr(idStart);

				object = $(object);

				if (object.length > 0)
				{
					type = 'html';
					options.htmlContent = object.html();
				}
			}
		}

		return type;
	}

	/*
	 *	buildFlashObject() - Create the object tag for embeding flash file.
	 *
	 *	PARAMETERS:
	 *		href		- swf location.
	 *		width		- width of the swf.
	 *		height		- height of the swf.
	 *		flashvars	- flash vars.
	 *
	 *	RETURN VALUE:
	 *		Returns the html object.
	 */
	function buildFlashObject(href, width, height, flashvars)
	{
		var flashvars = (flashvars || flashvars == '') ? 'autostart=1&autoplay=1&fullscreenbutton=1' : flashvars;

		/*
		 *	Note: We build all the object and create it one time, for 2 reasons:
		 *		1. IE8 will not append any element to the object tag.
		 *		2. This way is faster than creating evrey element separately, but costs file size.
		 */
		var object = '<object width="'+width+'" height="'+height+'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">';

		object += '<param name="movie" value="'+href+'" />'+
				  '<param name="allowFullScreen" value="true" />'+
				  '<param name="allowscriptaccess" value="always" />'+
				  '<param name="wmode" value="transparent" />'+
				  '<param name="autostart" value="true" />'+
				  '<param name="autoplay" value="true" />'+
				  '<param name="flashvars" value="'+flashvars+'" />'+
				  '<param name="width" value="'+width+'" />'+
				  '<param name="height" value="'+height+'" />';

		object += '<embed src="'+href+'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true"'+
							' autostart="true" autoplay="true" flashvars="'+flashvars+'" wmode="transparent" width="'+width+'"'+
							' height="'+height+'" style="margin: 0; padding: 0" />';

		object += '</object>';

		// Create and return the object.
		return $(object);
	}
	
	/*
	 *	onKeyDown() - On key down event for the whole page.
	 */
	function onKeyDown(ev)
	{
		var keyCode = ev.keyCode || ev.charCode || ev.which;
		
		if (keyCode == 27)		// Escape code.
		{
			self.hidePopup();
		}
	}

	/*
	 *	onMouseDown() - When the mouse is down over the popup container we want to dragg the container.
	 */
	function onMouseDown(ev)
	{
		var offset = container.position();
		var startOffset = {startX: ev.clientX, startY: ev.clientY, offset: offset};

		// Register the mouse up event for ending the dragg and the mouse move for moving the window.
		$(window).bind('mousemove', startOffset, onMouseMove).bind('mouseup', onMouseUp);
	}

	/*
	 *	onMouseMove() - Move the container according to the mouse position.
	 */
	function onMouseMove(ev)
	{
		var offset = ev.data.offset;
		var xPos = ev.clientX - ev.data.startX + offset.left;
		var yPos = ev.clientY - ev.data.startY + offset.top;

		//if (ev.target === draggable.get(0))
		{
			container.css({left: xPos, top: yPos});
		}
	}

	/*
	 *	onMouseUp() - Ending the drag.
	 */
	function onMouseUp()
	{
		// Unregister the events for dragging.
		$(window).unbind('mousemove', onMouseMove).unbind('mouseup', onMouseUp);
	}
	
	return this;
}

/*
 *	speedoPopup() - This shows a better popup.
 */
$.fn.speedoPopup = function (options)
{
	var defaultOptions = {
		width: null,
		height: null,
		left: 'center',
		top: 'center',
		close: true,
		closeCaption: '',
		theme: 'default',
		htmlContent: '<p> Default content </p>',
		esc: true,
		overlay: {
			opacity: .75,
			zindex: 100000
		},
		caption: null,
		href: null,
		overlayClose: true,
		autoClose: false,
		autoShow: false,
		effectIn: 'none',
		effectOut: 'none',
		css3Effects: false,
		showOnEvent: 'click',
		useFrame: false,
		useAjax: false,
		loadingImage: false,
		unload: false,
		draggable: false,
		responsive: true,
		ajaxContent:
		{
			url: "",
			type: "POST",
			data: null
		},
		groupGallery: false,
		groupIndex: 0,	// The start index for the group.
		groupAutoPlay: true,
		groupWait: 5000,
		loop: true,
		buttons: null,

		// Callbacks
		onBeforeShow: function () {},		// Before the popup is showing.
		onShow: function () {},				// When the popup is showing.
		onComplete: function () {},			// After the popup content finished loading.
		onHide: function () {},				// When the popup is hiding.
		onClose: function () {}				// When the close button was clicked.
	};

	if (options.href && options.useFrame == null)
	{
		options.useFrame = true;
	}

	// Set CSS3 effects to a random effect.
	if (options.css3Effects == "random")
	{
		var randomEffects = ["none", "zoomIn", "zoomOut", "flip", "flipInHor", "flipInVer",
							"bounceIn", "pageTop", "flyIn", "fadeInScale", "scaleDown", "fadeSpin",
							"pulse", "leftSpeedIn", "rollIn", "rollOut", "pulseBody", "fadeSpinBody"];

		options.css3Effects = randomEffects[Math.floor(Math.random() * (randomEffects.length - 1))];
	}
	
	var options = $.extend(true, defaultOptions, options);
	
	var popupInstance = null;
	
	if (!this.data('unique-speedo-instance') || options.unload)
	{
		popupInstance = new PopUp(options);
		
		// Wait until the autoShow time passes and then create and show the popup.
		if (options.autoShow)
		{
			setTimeout(function(){ popupInstance.createPopup(); }, options.autoShow);
		}
		else
		{
			popupInstance.createPopup();
		}
		
		//popupInstance.hidePopup();
		popupInstance.init();
		
		this.data('unique-speedo-instance', popupInstance);
	}
	else
	{
		popupInstance = this.data('unique-speedo-instance');
		
		// Wait until the autoShow time passes and then show the popup.
		if (options.autoShow)
		{
			setTimeout(function(){ popupInstance.showPopup(); }, options.autoShow);
		}
		else
		{
			popupInstance.showPopup();
			popupInstance.centerPopup();
			//popupInstance.setContent(options.href);
			if (options.groupAutoPlay)
			{
				popupInstance.playContent(true);
			}
		}
		
	}
	
	/*this.each(function ()
	{
		console.log('InstanceName: '+popupInstance.instanceName+'  className '+this.className);
	/*	if (options.showOnEvent !== false)
		{
			$(this).bind(options.showOnEvent, function ()
			{
				popupInstance.showPopup();
			});
		}*/
	//});
	
	return popupInstance;
};

/*
 *	easing() - Extend the jQuery easing for new easing methods.
 */
/*$.extend($.easing,
{
    easeOutBack: function (x, t, b, c, d, s)
	{
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
    },
	easeInBounce: function (x, t, b, c, d)
	{
		return c - jQuery.easing.easeOutBounce (x, d-t, 0, c, d) + b;
	},
	easeOutBounce: function (x, t, b, c, d)
	{
		if ((t/=d) < (1/2.75))
		{
			return c*(7.5625*t*t) + b;
		}
		else if (t < (2/2.75))
		{
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		}
		else if (t < (2.5/2.75))
		{
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		}
		else
		{
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},
	easeInOutBounce: function (x, t, b, c, d)
	{
		if (t < d/2)
		{
			return jQuery.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
		}

		return jQuery.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
	}
});*/

$(function ()
{
	$.speedoPopup = {};
	$.speedoPopup.smartSkins = {};

	// Set the browser_ie utility variable.
	$.speedoPopup.browser_ie = browser_ie;

	/*
	 *	registerSmartSkin() - Register smart skin.
	 */
	$.speedoPopup.registerSmartSkin = function (name, func)
	{
		$.speedoPopup.smartSkins[name] = func;
	};

	function queryParameters(query)
	{
		var query = query.split("+").join(" ");

		query = query.split('?')[1];

		var params = {};
		var regex = /[?&]?([^=]+)=([^&]*)/g;
		var tokens;

		while (tokens = regex.exec(query))
		{
			params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
		}

		return params;
	}

	// Listen globally for the speedo-popup class to use the popup as a lightbox.
	$(document).on('click', '.speedo-popup', function (ev)
	{
		ev.preventDefault();
		//ev.stopPropagation();

		var $cliked = $(this);
		var href	= $cliked.attr('href');
		var groupGallery = [];
		var relAttribute = $cliked.attr('rel');
		var groupIndex = 0;

		if (relAttribute && relAttribute != '')
		{
			
			$('.speedo-popup[rel="'+relAttribute+'"]').each(function (index)
			{
				var $this = $(this);

				// If the element's are the same, set the start index to the current index.
				if ($this.get(0) == $cliked.get(0))
				{
					groupIndex = index;
				}

				groupGallery.push({url: $this.attr('href'), title: $this.attr('title')});
			});
		}

		var query = queryParameters(href);

		var options = $.extend({
			htmlContent: false,
			effectIn: 'fade',
			effectOut: 'fade'
		}, query);

		if (options.useAjax)
		{
			options.ajaxContent = {
				url: href,
				type: "GET",
				data: null
			}
		}

		if (groupGallery.length > 0)
		{
			options.groupGallery = groupGallery;
			options.groupIndex = groupIndex;
		}

		options.href = href;

		$(this).speedoPopup(options);
	});
});

})(jQuery);