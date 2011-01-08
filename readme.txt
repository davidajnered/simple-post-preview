=== Simple Post Preview ===
Contributors: davidajnered
Donate link: http://www.davidajnered.com/
Tags: simple, post, preview, category, push, widget, promote, excerpt, static, dynamic
Requires at least:
Tested up to: 3.0.1
Stable tag: 1.2.0

Simple Post Preview is a multi-instance widget that creates a push for the latest post in a category.

== Description ==

This widget lets you create a push for any post you like. It can create two kinds of pushes, both dynamic and static ones. A dynamic push is a push that shows the latest post from a category you select. It will automatically update it for you. To create a dymanic push simply select a category from the category drop down.To create a static push you select the post you want to show from the post drop down instead. This push will never change unless you reconfigure the widget.

For more updated information see http://davidajnered.dev/simple-post-preview/

What's in the future?
- javascript for admin UI

If you have an idea for improvement feel free to send me an email at davidajnered@gmail.com. Feedback is always appreciated. If you want to add some code yourself you can fork Simple Post Preview on github (https://github.com/davidajnered/simple-post-preview). Make your change and send me a pull request.

== Installation ==

1. Upload the simple-post-preview folder to to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the plugin by placing it in any defined widget area
4. Setup Simple Post Preview so that is displays what you want
4.1 If you want to use the features image in your push you'll have to enable it in your theme and add a thumbnail size 

There aren't many ways to set it up, but here is a small tutorial
Title: You can choose to write a header. If you don't, the title of the post will be used as the header instead
Select Category: Select one of your categories from which you want to display the latest post
Select Post: Select a specific post to create a static push
Thumbnail: Select this if you want to show the post thumbnail in the push
Thumbnail size: The name of the thumbnail size you want to use. It's specified in functions.php
Length of preview: The number of characters from the post shown in the preview. -1 will hide the content and 0 will show all.
Link: The name of the link that takes you to the full post
Link to: You can choose to link to either the post or the category

== Frequently Asked Questions ==

== Screenshots ==

1. The widgets GUI from the administration panel

== Changelog ==

= 1.2.0 =
* Added the ability to select a specific post
* Automatically finds all available thumbnail sizes
* Added a wrapper around the excerpt

= 1.1.0 =
* It's now possible to use the posts featured image in the preview
* Widget does no longer show the post title when you write a custom title 

= 1.0.1 =
* Fixed a bug that made the widget show a post from another category than the one selected
* It's no longer possible to select an empty category

= 1.0 =
* First version of Simple Post Preview

== Upgrade Notice ==

= 1.0.1 =
This upgrade fixes an incorrect database query that made the widget show posts from the wrong category