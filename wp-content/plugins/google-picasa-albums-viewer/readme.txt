=== Google Picasa Viewer ===

Contributors: nakunakifi
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZAZHU9ERY8W34
Tags: album, albums, CDN, google picasa, image, images gallery, image hosting, lightbox, picasa, picasa web, photo, photos, photo albums, showcase
Requires at least: 2.8
Tested up to: 3.4.1
Stable tag: 1.1.9

Provides simple drag & drop image gallery functionality to enable you to display Google Picasa Web albums in your WordPress installation.

== Description ==

Display your Picasa Web Albums in your WordPress installation simply with Google Picasa Viewer. 

* Display albums using the Google Picasa Albums Widget if your theme has widgetized
* Use Shortcodes to display your albums and album image gallery. 
* The album images are displayed using the Fancybox (fancybox.net) lightbox. 
* Admin settings to control size of thumbnail images and size of image in lightbox. 
* Just enter your Google Picasa username and password, drag in the Google Picasa Albums Widget and you are ready to go!


== Prerequisites ==

1. PHP5

== Installation ==

1. Unzip into your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Make your settings, Admin->Settings->Google Picasa
4. Drag Google Picasa Albums widget to the widgetized area of your theme alternatively use the 'Display Album' shortcode [nak_google_picasa_albums] on a page of your choice.
5. To display the album's images select the page from  the drop down menu in Admin->Settings->Google Picasa and make sure you place the shortcode, [nak_google_picasa_album_images] on the same page
6 . To display specific albums with the Display Album shortcode, pass  the album id in the shotcode e.g.
[nak_google_picasa_albums show_albums='5218473000700519489,5218507736478682657']
7. Change settings->permalinks to something other than the default.

== Screenshots ==

1. This is an example of the settings page.
2. This is an example of album widget settings.
3. This is the album widget in the sidebar.
4. This is the screen you will see after clicking an album thumnail to expose the images in the album.
5. This is an example of the lightbox displaying photo you clicked on.

== Shortcodes ==

*[nak_google_picasa_albums] Will display public albums. To show only specific albums add the album ids like so [nak_google_picasa_albums show_albums='5218473000700519489,5218507736478682657']
*[nak_google_picasa_album_images] This is a place holder to display the results of an album that has been clicked on, normally on a separate page, make sure you have chosen this page from the dropdown menu in the plug settings page.


== Credits ==

Google Picasa Viewer - Ian Kennerley, http://nakunakifi.com 
Follow me on Twitter (@nakunakifi) to be the first to hear about new releases

FancyBox - jQuery Plugin - Copyright (c) 2008 - 2010 Janis Skarnelis, http://fancybox.net

Zend GData - Copyright (c) 2005-2010, Zend Technologies USA, Inc.


== Changelog ==

== 1.1.8 ==
* Pagination helper fix applied. hat-tip @ ericmerl

== 1.1.7 ==
* Put single quotes around album name in pagination helper, class-PicasaAPI.php

== 1.1.6 ==
* Support for https

== 1.1.2 ==
* Added screenshots

== 1.1.1 ==
* General code tidy up
* Update to readme.txt

== 1.0.9 ==
* Checked compatability with WordPress 3.3.2
* Add meta box for Google Picasa Pro (http://www.cheshirewebsolutions.com/google-picasa-pro-plugin/) on settings page

= 1.0.8 =
* Added support to shortcode to display specific album by passing album id into shortcode, e.g.
[nak_google_picasa_albums show_albums='5218473000700519489,5218507736478682657']
* Improved pagination to not display when there is only one page of results

= 1.0.7 =
* Added support for sites not in root directory
* Added Album Widget form to customise title

= 1.0.6 =
* Added support for accented letters in page names
* Fixed: Pagination bug
* Added extra css margin above and below pagination links

= 1.0.5 =
* Added Zend GData check on settings page
* Added title attributes to albums and images
* Added Zend GData into plugin folder, no longer need to reference your location

= 1.0.4 =
* Fixed: Option settings not being read in some circumstances
* Added Tweet link to settings page

= 1.0.3 =
* Added album gallery name to album gallery page
* Changed how name appears in Settings menu to be more consistent with plugin name
* Fixed: Notice: Undefined variable: err_msg


