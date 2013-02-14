=== Google Picasa Pro ===

Contributors: nakunakifi
Tags: google, picasa, images gallery, carousel, showcase, photos, photo albums
Requires at least: 3
Tested up to: 3.4.2
Stable tag: 1.3.0

Provides simple drag & drop image gallery functionality to enable you to display Google Picasa Web albums in your WordPress installation.


== Description ==

Display your Picasa Web Albums in your WordPress installation simply with Google Picasa Pro. Display albums using the Google Picasa Albums Widget if your theme has widgetized areas otherwise use Shortcodes to display your albums and album image gallery. The album images are displayed in the Fancybox (fancybox.net) lightbox. Admin settings to control size of thumbnail images and size of image in lightbox. Just enter your Google Picasa username and password, drag in the Google Picasa Albums Widget and you are ready to go!


== Prerequisites ==

1. PHP5
2. Apache Web Server
3. Google Account


== Installation ==

1. Unzip into your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Make your settings, Admin->Settings->Google Picasa Pro
4. Drag Google Picasa Albums widget to the widgetized area of your them alternatively use the 'Display Album' shortcode [cws_gpp_albums] on a page of your choice.
5. To display the album's images select the page from  the drop down menu in Admin->Settings->Google Picasa and make sure you place the shortcode, [cws_gpp_album_images] on the same page
6. To display the albums in a carousel use shortcode [cws_gpp_albums_carousel]
7. Change settings->permalinks to something other than the default.

== Shortcodes ==

*[cws_gpp_albums] Will display public/private albums. To show only specific albums add the album ids like so [cws_gpp_albums show_albums='5218473000700519489,5218507736478682657']
*[cws_gpp_album_images] This is a place holder to display the results of an album that has been clicked on, normally on a separate page, make sure you have chosen this page from the dropdown menu in the plug settings page.
*[cws_gpp_albums_carousel] Will display an infinite carousel of public/private albums.
*[cws_gpp_carousel album_id='5218473000700519489' show_slider_mrkrs='1'] Will display an infinite carousel of specified album. To hide slider markers use show_slider_mrkrs='1'.
*[cws_gpp_images_in_album album_id='5218473000700519489'] Will display the images of specified album.


== Credits ==

Google Picasa Viewer - Ian Kennerley, http://cheshirewebsolutions.com

FancyBox - jQuery Plugin - Copyright (c) 2008 - 2010 Janis Skarnelis, http://fancybox.net

Zend GData - Copyright (c) 2005-2010, Zend Technologies USA, Inc.

jQuery blockUI plugin, M. Alsup, http://malsup.com/jquery/block/

== Changelog ==

= 1.2 =
* Add shortcode [cws_gpp_carousel album_id='5218473000700519489'] Ð will display images in album specified by album id in a carousel
* Add shortcode [cws_gpp_images_in_album album_id='5218473000700519489'] Ð will display the images of specified album with pagination.
* Add localization support.

= 1.1 =
* Enhanced security of user authentication with OAUth

= 1.0.2 =
* Fix Bug where problems existed displaying public/private albums

= 1.0.1 =
* Fix Bug where problems existed when under certain circumstances if WordPress was installed in a sub-folder
* Fix Bug where JavaScript problems existed when under certain circumstances if WordPress was installed in a sub-folder

= 1.0.0 =
* Initial Release


