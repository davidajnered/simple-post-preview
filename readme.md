# Simple Post Preview

Simple Post Preview is a widget that creates pushes for your posts.

## Description

Simple Post Preview can create two kinds of pushes. The first is a static push for any post or page. The second one is for categories where the widget automatically shows the latest post in a category.

# Set it up
Not really a lot to do here except select the content types you want the plugin to work with on the Simple Post Preview settings page.

## Customization

The GUI of this plugin is pretty straight forward so I just right to the more interesting parts. As of version 2.0 I've implemented Twig as template engine and added the feature for designers and developers to use their own templates with Simple Post Preview. In every widget you have a field to add then name of your html template file in the root of your theme folder. Below is an array of all the variables available in the template. If you're not sure how to use Twig, have a look at [this introduction page](http://twig.sensiolabs.org/doc/templates.html).

```
$tmplData = array (
    'name' => 'Primary Sidebar',
    'id' => 13,
    'description' => 'Main sidebar that appears on the left.',
    'class' => '',
    'before_widget' => '<aside id="simple_post_preview-13" class="widget widget_simple_post_preview">',
    'after_widget' => '</aside>',
    'before_title' => '<h1 class="widget-title">',
    'after_title' => '</h1>',
    'widget_id' => 'simple_post_preview-13',
    'widget_name' => 'Simple Post Preview',
    'title' => 'It\'s simple!',
    'item_id' => '5919',
    'item_type' => 'post',
    'content_type' => 'body',
    'thumbnail_switch' => 'on',
    'thumbnail_size' => 'medium',
    'length' => '200',
    'link_title' => 'Read more',
    'show_categories' => 'on',
    'template' => 'my-custom-widget-template.html',
    'ID' => 5919,
    'post_author' => '1',
    'post_date' => '2014-07-15 08:06:56',
    'post_date_gmt' => '2014-07-15 08:06:56',
    'post_content' => 'Lorem ipsum dolor sit amet...',
    'post_title' => 'This is simple post preview',
    'post_excerpt' => 'Lorem ipsum...',
    'post_status' => 'publish',
    'comment_status' => 'open',
    'ping_status' => 'open',
    'post_password' => '',
    'post_name' => 'this-is-simple-post-preview',
    'to_ping' => '',
    'pinged' => '',
    'post_modified' => '2014-07-15 08:17:49',
    'post_modified_gmt' => '2014-07-15 08:17:49',
    'post_content_filtered' => '',
    'post_parent' => 0,
    'guid' => 'http://your-site.dev/?p=5919',
    'menu_order' => 0,
    'post_type' => 'post',
    'post_mime_type' => '',
    'comment_count' => '0',
    'filter' => 'raw',
    'content' => 'Lorem ipsum dolor sit amet...',
    'permalink' => 'http://your-site.dev/this-is-simple-post-preview/',
    'categories' =>
    array (
        0 => array (
            'term_id' => 38,
            'name' => 'Plugins',
            'slug' => 'plugins',
            'term_group' => 0,
            'term_taxonomy_id' => 38,
            'taxonomy' => 'category',
            'description' => '',
            'parent' => 0,
            'count' => 1,
            'filter' => 'raw',
            'cat_ID' => 38,
            'category_count' => 1,
            'category_description' => '',
            'cat_name' => 'Plugins',
            'category_nicename' => 'plugins',
            'category_parent' => 0,
            'url' => 'http://your-site.dev/category/plugins/',
        ),
    ),
    'featured_image' => array (
        'width' => 327,
        'height' => 683,
        'file' => '2014/07/screenshot-1.png',
        'sizes' => array (
            'thumbnail' => array (
                'file' => 'screenshot-1-80x80.png',
                'width' => 80,
                'height' => 80,
                'mime-type' => 'image/png',
            ),
            'medium' => array (
                'file' => 'screenshot-1-143x300.png',
                'width' => 143,
                'height' => 300,
                'mime-type' => 'image/png',
            ),
            'post-thumbnail' => array (
                'file' => 'screenshot-1-327x372.png',
                'width' => 327,
                'height' => 372,
                'mime-type' => 'image/png',
            ),
            'twentyfourteen-full-width' => array (
                'file' => 'screenshot-1-327x576.png',
                'width' => 327,
                'height' => 576,
                'mime-type' => 'image/png',
            ),
        ),
        'image_meta' => array (
            'aperture' => 0,
            'credit' => '',
            'camera' => '',
            'caption' => '',
            'created_timestamp' => 0,
            'copyright' => '',
            'focal_length' => 0,
            'iso' => 0,
            'shutter_speed' => 0,
            'title' => '',
        ),
        'thumbnail_url' => 'http://your-site.dev/wp-content/uploads/2014/07/screenshot-1-80x80.png',
        'medium_url' => 'http://your-site.dev/wp-content/uploads/2014/07/screenshot-1-143x300.png',
        'large_url' => 'http://your-site.dev/wp-content/uploads/2014/07/screenshot-1.png',
        'post-thumbnail_url' => 'http://your-site.dev/wp-content/uploads/2014/07/screenshot-1-327x372.png',
        'twentyfourteen-full-width_url' => 'http://your-site.dev/wp-content/uploads/2014/07/screenshot-1-327x576.png',
    ),
    'show_image' => true,
    'image_url' => 'http://your-site.dev/wp-content/uploads/2014/07/screenshot-1-143x300.png',
    'excerpt_more' => ''
);
```
I've added a hook that allows you to customize the template data.
```
    add_filter('simple_post_preview_tmpl_data', function ($tmplData) {
        // Modify the $tmplData array here
        return $tmplData;
    });
```

Feel free to ask questions or help out, both on [wordpress.org](https://wordpress.org/plugins/simple-post-preview/) and [github](https://github.com/davidajnered/simple-post-preview).

Cheers / David

## Installation

1. Upload the simple-post-preview folder to to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the widget to your widget area
4. Select a post or a category (and maybe change some of the other self explaing settings).

## Changelog

### 2.0
* **Important: 2.0 is not backward compatible!**
* Rewritten from scratch
* Plugin is now OOP
* Use your own custom templates
* Implemented Twig as template engine
* Post and category select is searchable to enhance performance on big sites
* PSR code standard

### 1.2.6
* Making sure everything is still working.

### 1.2.5
* Improved thumbnail query to not select anything that's not an image

### 1.2.4
* Removed strip_tags function call and custom wrapping p-tag from content

### 1.2.3
* Improved UI for admins
* Links on thumbnails
* You can now use both post content and excerpt

### 1.2.0
* Added the ability to select a specific post
* Automatically finds all available thumbnail sizes
* Added a wrapper around the excerpt

### 1.1.0
* It's now possible to use the posts featured image in the preview
* Widget does no longer show the post title when you write a custom title

### 1.0.1
* Fixed a bug that made the widget show a post from another category than the one selected
* It's no longer possible to select an empty category

### 1.0
* First version of Simple Post Preview

## Upgrade Notice

### 1.0.1
This upgrade fixes an incorrect database query that made the widget show posts from the wrong category