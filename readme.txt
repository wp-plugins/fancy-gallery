=== Fancy Gallery ===
Contributors: dhoppe
Tags: image, images, picture, pictures, photo, photos, gallery, galleries, photo-albums, Post, admin, media, fancy, fancybox, thickbox, lightbox, jquery, 
Requires at least: 2.8.1
Tested up to: 2.9
Stable tag: trunk

Will bring your galleries as valid XHTML blocks on screen and associate linked images with Fancybox.

== Description ==

The Fancy Gallery Plugin overloads the default function behind the "[GALLERY]" Shortcode. Fancy Gallery generates fully valid XHTML and associates linked images with [Fancy Image Box](http://fancybox.net).

Of course you can use 'exclude' and 'include' parameters in your [gallery] shortcode. 

== Installation ==

Installation as usual.

1. Unzip and Upload Fancy Gallery to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Open a post or page which contains a gallery
1. You like what you see?

== Changelog ==

= 1.1.1 =
* Bug Fix: In some cases (No attributes) you got an error "array_merge(): Argument #2 is not an array [...]"

= 1.1 =
* Now you can use exclude="x,y,z" and include="a,b,c" attributes in your tag.

= 1.0 =
* Everything works fine.