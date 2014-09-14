=== Fancy Gallery ===
Contributors: dhoppe
Tags: gallery, galleries, image, images, picture, pictures, photo, photos, photo-album, photo-albums, fancybox, thickbox, lightbox, jquery, javascript, widget, cms, free, flickr				widget,Post,plugin,admin,posts,sidebar,comments,google,images,page,image,links
Requires at least: 3.6
Tested up to: 4.0
Stable tag: trunk
Donate link: http://dennishoppe.de/en/wordpress-plugins/fancy-gallery
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Awesome gallery management and touch-enabled, responsive, mobile-friendly image lightbox tool to manage and present image galleries within WordPress


== Description ==
[Fancy Gallery](http://dennishoppe.de/en/wordpress-plugins/fancy-gallery) is *the* state of the art **[WordPress Gallery Management tool](http://dennishoppe.de/en/wordpress-plugins/fancy-gallery)** which enables you to organize image galleries easily in your WordPress backend. Embed these galleries in your posts or pages and present them to your website visitors.

Furthermore this [gallery plugin](http://dennishoppe.de/en/wordpress-plugins/fancy-gallery) adds a beautiful awesome **javascript lightbox effect** (touch-enabled, responsive, optimized for both mobile and desktop web browsers) to all links, pointing to an image anywhere on your website. This includes images in your posts, galleries, pages, sidebar widgets and anywhere else on your website. In detail this means all links - regardless if text or image - pointing to an image will automatically opened in a responsive lightbox. When using the [gallery] shortcode the images will get a "previous", "next" and a slideshow button. The gallery itself will be converted to a **valid HTML5 section** - even if your theme does not support HTML5 galleries yet.


= Facts &amp; Features =
* Organize image galleries separated from posts or pages
* **Centralized gallery management**. Enjoy a single location where you can see and manage all your galleries
* Javascript lightbox support for all linked images on your website
* Automatically generated **index page with all galleries**
* Every gallery has its own page with unique URL
* **SEO conform** URL structure for all kind of pages
* **Tags and categories**¹ to classify your galleries (both tags and categories are disjunct from your post tags and post categories)
* More taxonomies to classify your galleries: Categories, Tags, Events, Places, Dates, Persons, Photographers. (Need more? Ask us!)
* Supports **WPML** flawless and enables you to create a multilingual image website
* Supports the WordPress theme template hierarchy and the parent-child-theme paradigm
* Supports **user defined HTML templates**
* Supports gallery **comments**²
* Supports **featured images** as gallery thumbnails²
* Supports **excerpts** for your uploaded galleries (the same way you already know from regular posts)
* Excerpts can contain text description and a random set of preview images
* Supports WordPress **user rights** and capabilities¹
* Supports the **WordPress menus** and enables you to add all components of your encyclopedia to any menu
* Supports **RSS feeds** for the gallery index and for the comments of each gallery
* Custom **thumbnail sizes** and **color effects**¹
* **Fully compatible** with all existing themes with archive template
* Template engine to display your galleries in different styles
* Widget to display random images from your galleries in the sidebar¹
* Widget to display the gallery taxonomies as list or cloud in the sidebar¹
* Converts all galleries in **valid HTML5 blocks**
* **Completely translatable** - .pot file is included
* Includes a **bunch of filters** to give you the control of the behavior of this piece of code
* **Import and export** directly via the official "[WordPress Importer](https://wordpress.org/plugins/wordpress-importer/)" by Automattic Inc.
* **Clean and intuitive** user interface
* Works great with **WordPress Multisite**
* Personal **one-on-one real-time support** by the developer¹
* No ads or branding anywhere - perfect white label solution¹

¹ Available in the [premium version of Fancy Gallery](http://dennishoppe.de/en/wordpress-plugins/fancy-gallery).<br>
² Your theme needs to support this too.

= Gallery Shortcode =
Of course you can use "exclude" and "include" parameters in your [gallery] shortcode like you already know from the traditional gallery code.

= Settings =
You can find the settings page in your Dashboard -> Settings -> [Fancy Gallery](http://dennishoppe.de/en/wordpress-plugins/fancy-gallery).

= Gallery Templates =
To create your own gallery template you only need elementary HTML and PHP knowledge. Just start by creating a new HTML file with the following example header with the template details.

<code>
/*
Fancy Gallery Template: Template Name
Description: Your template description here.
Version: 1.0
Author: John Doe
Author URI: http://example.com
*/
</code>

You can place the template in these folders:

1. If it is for your own website put it in /wp-content/fancy-gallery-templates.
1. If you are a theme author just put in your theme (max one level deep).
1. If you are a plugin developer use the "fancy_gallery_template_files" to add template files from anywhere.
1. Absolutely not recommended: in the "templates/" folder of the plugin itself.


= Questions / Support requests =
Please use the support forum on WordPress.org for this version of the plugin. For the [Premium Version](http://dennishoppe.de/en/wordpress-plugins/fancy-gallery) there is a separate support package [available](http://dennishoppe.de/en/wordpress-plugins/fancy-gallery). Of course you can hire me for consulting, support, programming and customizations at any time.


= Language =
* This Plugin is available in English.
* Diese Erweiterung ist in Deutsch verfügbar. ([Ulrike Seddig](http://UlrikeSeddig.de/))
* Plugin disponible en Español. ([Guillermo Gozalbes](http://www.versus.es/))
* Este plugin está disponível em português - Brasil. (Ramiro Modica)
* Ce plugin est disponible en français. (Thomas Schlesser)


= Translate this plugin =
If you have translated this plugin in your language feel free to send me the language file (.po file) via E-Mail with your name and this translated sentence: "This plug-in is available in %YOUR_LANGUAGE_NAME%." So i can add it to the plug-in.

You can find the *Translation.pot* file in the *language/* folder in the plugin directory.

* Copy it.
* Rename it (to your language code).
* Translate everything.
* Send it via E-Mail to &lt;Mail [@t] [DennisHoppe](http://DennisHoppe.de) [dot] de&gt;.
* Thats it. Thank you! =)

= Limitations of the lite version =
There are no real limitations in this version except the number of galleries you can organize within the gallery management tool. The maximal number of galleries is limited to three but of course you can use the traditional galleries in your posts and pages *without* any limitations!


== Installation ==

= Minimum Requirements =

* WordPress 3.6 or greater
* PHP version 5.3 or greater
* MySQL version 5.0 or greater

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you do not need to leave your web browser. To do an automatic install of Fancy Gallery, log in to your WordPress dashboard, navigate to the plugins menu and click "Add New".

In the search field type "Fancy Gallery" and click "Search plugins". Once you have found my gallery plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now".

= Manual installation =

The manual installation method involves downloading my gallery plugin and uploading it to your web server via your favorite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.


== Screenshots ==
01. Gallery single view
02. Gallery lightbox
03. Add a new gallery
04. Manage gallery categories
05. Edit a category
06. Edit an image
07. Integrate your galleries to your navigation menus
08. Change details of several images
09. The lightbox settings

== Upgrade Notice ==
If you update the plugin to version 1.5.x or higher please reactivate the plugin after the update and re-save your options through clicking the "Save changes" button at the end of the options page.


== Premium Version ==

Fancy Gallery is available as [Premium Plugin](http://dennishoppe.de/en/wordpress-plugins/fancy-gallery) too. In the Premium Version you can use all options and features which are restricted in the lite version.

Possibly even more important, buying the Premium edition gives you access to me and my support team. You can email us your questions about usage of the plugin or your problems in setting it up and we will assist you in no time!


== Changelog ==

= 1.5.9 =
* Added PHP warning if version is lower than 5.3

= 1.5.8 =
* Added WPML Workaround for translated post type slugs

= 1.5.7.1 =
* Added padding style attribute to collage template

= 1.5.7 =
* Fixed: Undefined post object in the save meta box function

= 1.5.6.1 =
* Fixed: Made meta box hover texts translatable

= 1.5.6 =
* Fixed: Gallery items can contain images from other posts.

= 1.5.5 =
* Added: Classify images within the same post content

= 1.5.4 =
* Fixed: input name of the meta box fields do not contain backslashes anymore
* Change: Does not use the image caption as description anymore if the description is not set
* Patched: permalink for taxonomies were rewritten on plugin activation

= 1.5.3 =
* Patched HTML5-gallery style sheet to work with older themes
* Patched Collage gallery template
* Fixed German translation strings

= 1.5.2 =
* Removed deprecated jQuery "live" function call

= 1.5 =
* Replaced lightbox with a new responsive one
* Replaced admin menu icon
* Completely re-factored PHP code

= 1.4.4 =
* Fixed the edit image section
* Made the lightbox library optional

= 1.4.3 =
* The plugin loads the stylesheets asynchronous now

= 1.4.2 =
* Added /wp-content/fancy-gallery-templates to the list of template include paths

= 1.4.1 =
* Updated jQuery Mousewheel to 1.3.11
* Updated jQuery Livequery to 1.3.6
* Fixed PHP notice on import tab for images without parent

= 1.4 =
* Merged jQuery Lightbox repository from 1.3.1 to 1.3.35

= 1.3 =
* Updated the whole backend and added more valuable features to the lite version

= 1.2.8 =
* Fixed Warnings on options page

= 1.2.6 =
* Moved the Register Widget function to the plugin itself

= 1.2.5 =
* Fixed: gallery shortcode without attributes shows the images of the current post now

= 1.2.4 =
* Fixed the responsive design of the edit-screen

= 1.2.3 =
* Patched the Fancy-Fancy template

= 1.2.2 =
* Made the FancyBox 1.3.4 the default lightbox
* Changed the structure so you can easily add more other lightboxes

= 1.2.1 =
* Patched the options page / meta boxes with the old options

= 1.2 =
* Complete roll back to FancyBox 1.3.4
* Optional FancyBox 2.x from external CDN

= 1.1.7 =
* Updated Fancybox JS library to the latest Git Hub state
* Added Adminbar fix for top control buttons of the lightbox

= 1.1.6 =
* Updated Fancybox to version 2.1.5:
* Fixed: Broken slideshow
* Fixed: Parent option
* Retina graphics and retina display support
* Improved "lock" feature

= 1.1.5 =
* Made options page responsive

= 1.1.4 =
* Added French translation.
* Merged all styles via SASS to fancybox.css
* Patched z-index bug for TwentenEleven theme
* Patched default template styles
* Improved Gallery generate code and parameters

= 1.1.3 =
* Fixed "Image Order" problem in manually created galleries.

= 1.1.2 =
* Improved the "Import to gallery" tab
* removed some deprecated files
* Fixed image size bug in Thumbnails-Share-Button template

= 1.1.1 =
* Added overflow style to gallery wrappers

= 1.1 =
* Fancybox updated, completely rewritten JS

= 1.0.29 =
* removed clear element from "Thumbnails only" template

= 1.0.28 =
* removed clear element from default template

= 1.0.27 =
* Fixed Strip_Tags bug in share-button-template

= 1.0.26 =
* Reordered Template files
* New Template with share buttons inside the image title

= 1.0.25 =
* Changed the right image margin of the default gallery template
* Converted the shortcode in the gallery backend from a <code> element to an <input> field

= 1.0.24 =
* Made taxonomy slugs translatable.

= 1.0.23 =
* Added a management column for each taxonomy to the gallery management page

= 1.0.22 =
* Added some CSS to the default template

= 1.0.21 =
* Removed column field from the gallery editor
* Added support for "orderby" field in manually created galleries

= 1.0.20 =
* Added html trim function

= 1.0.19 =
* Splitted templates in Code and Style
* improved template output cleaning

= 1.0.18 =
* Allow URL Slug translation via WPML now

= 1.0.17 =
* Added WP 3.5 support

= 1.0.16 =
* Fixed the (Widget) translation bug

= 1.0.15 =
* Eliminated Backslashes in generated URLs (should work with Windows now)

= 1.0.14 =
* Changed z-index values for all fancy objects
* renamed library and stylesheet files
* fixed IE6/7/8 Bugs

= 1.0.13 =
* Changed fade-in time of the overlay
* fixed an IE bug which let the fancy box pop on non-image links

= 1.0.12 =
* Added Brazilian-Portuguese translation

= 1.0.11 =
* Updated mouse wheel library

= 1.0 =
* Everything works fine.
