$(function ()
{
	// Register Smart skin.
	$.speedoPopup.registerSmartSkin('clouds', function (overlay, container)
	{
		// We don't want to brake anything if there is no overlay.
		if (!overlay)
		{
			return ;
		}

		if ($.speedoPopup.browser_ie && $.speedoPopup.browser_ie< 9)
		{
			return;
		}

		var clouds = '<div class="cloud x1"></div><div class="cloud x2"></div><div class="cloud x3"></div><div class="cloud x4"></div><div class="cloud x5"></div>';

		overlay.append(clouds);
	});
});