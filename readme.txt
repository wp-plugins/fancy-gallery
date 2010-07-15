=== Fancy Gallery ===
Contributors: dhoppe
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=1220480
Tags: image, images, picture, pictures, photo, photos, gallery, galleries, photo-albums, Post, admin, media, fancy, fancybox, thickbox, lightbox, jquery, 
Requires at least: 2.8
Tested up to: 3.0
Stable tag: trunk

Fancy Gallery associates linked images and galleries with the jQuery Fancybox. [This Plugin has been granted the Famous Software Award!](http://download.famouswhy.com/fancy_gallery/)

== Description ==

= Latest news =
* Fancy Gallery has been granted the Famous Software Award! [To the post &raquo;](http://download.famouswhy.com/fancy_gallery/)


= Description =
Fancy Gallery integrates the [Fancy Image Box](http://fancybox.net) in your WordPress. All links pointing to an image will automatically opened in the FancyBox. If you use the [Gallery] Short-code the images will get a navigation and the gallery itself will converted into a valid XHTML block.

Of course you can use 'exclude' and 'include' parameters in your [GALLERY] short-code.

= Requirements =
* **Fancy Gallery requires PHP5!**
* WordPress 2.8 or higher

= Settings =
You can find the settings page in WP Admin Panel &raquo; Settings &raquo; Fancy Gallery.

= Questions =
If you have any questions you can leave a comment in my blog. But please think about this: I will not add features, write customizations or write tutorials for free. Please think about a donation. I'm a human and to write code is hard work.


= In the Press =
* Fancy Gallery has been granted the Famous Software Award. [To the post &raquo;](http://download.famouswhy.com/fancy_gallery/)
* 17 Most Used WordPress Jquery Plugins @ [Skyje.com](http://skyje.com/). [To the post &raquo;](http://skyje.com/2010/04/wordpress-jquery-plugins/)
* "Awesome and easy to use Wordpress gallery plugin" [To the post &raquo;](http://topsy.com/trackback?utm_source=pingback&utm_campaign=L2&url=http://dennishoppe.de/wordpress-plugins/fancy-gallery)

= Language =
* This Plug-in is available in English.
* Diese Erweiterung ist in Deutsch verfügbar. ([Dennis Hoppe](http://dennishoppe.de/))
* Este plugin está disponível em Português do Brasil. ([Rafael Sirotheau](http://rsirotheau.wordpress.com/))
* Cette extension est traduite en français. ([Charles Alain](http://www.domarin-tt.com/))

If you have translated this plug-in in your language feel free to send me the language file (.po file) via E-Mail with your name and this translated sentence: "This plug-in is available in %YOUR_LANGUAGE_NAME%." So i can add it to the plug-in.

You can find the *Translation.pot* file in the *language/* folder in the plug-in directory.

* Copy it.
* Rename it (to your language code).
* Translate everything.
* Send it via E-Mail to mail@DennisHoppe.de.
* Thats it. Thank you! =)


== Installation ==

Installation as usual.

1. Unzip and Upload all files to a sub directory in "/wp-content/plugins/".
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. View a post or page which contains a gallery.
1. You like what you see?

== Changelog ==

= 1.3.12 =
* Fancy Gallery can detect PHP4 now.

= 1.3.11 =
* Added the "hide close button" option
* renamed the "load_setting()" function to "get_option()"

= 1.3.10 =
* added index files which create 403s to void directory listing
* added hideable colorpicker
* fixed german translation

= 1.3.9 =
* Fixed the farbtastic incompatibility bug
* Added transparency fix for the navigation buttons in IE6
* Fixed: Remove senseless settings from gallery insert box
* tidied up the code

= 1.3.8 =
* Added French translation by [Charles Alain](http://www.domarin-tt.com/).


= 1.3.7 = 
* Added Portuguese (Brasil) translation by [Rafael Sirotheau](http://rsirotheau.wordpress.com/).


= 1.3.6 =
* Fixed German translation.
* Added .pot file to language folder


= 1.3.5 =
* fixed a problem in the fancy-js.php does not need parameters anymore.
* added a workaround for themes that show images as block elements.


= 1.3.4 =
* fancy-js.php does not define Functions or Classes anymore
* Fixed the exclude bug


= 1.3.3 =
* optimized settings handling in fancy-js.php


= 1.3.2 =
* New version of the donation plugin


= 1.3.1 =
* Some small translation, CSS, JS, XHTML fixes


= 1.3 =
* Upon now FancyGallery runs only with PHP5 and higher!
* There is a new admin panel in Settings > Fancy Gallery
* Added my new "Please donate" Plugin ;) 


= 1.2.4 =
* You can use the "id" parameter to sepcify the gallery that should be shown.


= 1.2.3 =
* Modified FancyBoy CSS. Should now also work in IE6 and IE7.


= 1.2.2 =
* Chage BaseUrl function. Now the plugin should even work if the wp folder is diffrent from your blog url. 


= 1.2.1 =
* Added necessary javascript files (Sorry)


= 1.2 =
* updated the fancy gallery to 1.3.1


= 1.1.2 =
* added the url to the plugin page
* Bug Fix: FancyGallery didn't work if you have the blog installed in a different directory.


= 1.1.1 =
* Bug Fix: In some cases (No attributes) you got an error "array_merge(): Argument #2 is not an array [...]"


= 1.1 =
* Now you can use exclude="x,y,z" and include="a,b,c" attributes in your tag.


= 1.0 =
* Everything works fine.