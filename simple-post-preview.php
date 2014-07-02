<?php
/**
 * Plugin Name: Simple Post Preview
 * Version: 1.2.6
 * Plugin URI: http://www.davidajnered.com/
 * Description: Simple Post Preview is a widget that creates pushes for posts.
 * Author: David Ajnered
 */
namespace SimplePostPreview;

class SimplePostPreview extends \WP_Widget
{

    /**
     * Init method
     */
    public function __construct()
    {
        $widget_ops = array(
          'classname' => 'simple_post_preview',
          'description' => __("Creates pushes for your posts")
        );

        $control_ops = array('width' => 100, 'height' => 100);
        $this->WP_Widget('simple_post_preview', __('Simple Post Preview'), $widget_ops, $control_ops);
    }

   /**
    * Displays the widget
    *
    * @param $args
    * @param array $instance
    */
    public function widget($args, $instance)
    {
        // Todo: create object instead

        if (!empty($instance)) {
          // Variables
            $title = $instance['title'];
            $length = (int)$instance['length'];
            $item = $instance['item'];
            $thumbnail = $instance['thumbnail'];
            $thumbnail_size = $instance['thumbnail_size'];
            $data_to_use = $instance['data_to_use'];
            $link = $instance['link'];
            $link_to = $instance['link_to'];

            // Find dropdown value
            if (strpos($item, 'p:') !== false) {
                $post = str_replace('p:', '', $item);
            } elseif (strpos($item, 'c:') !== false) {
                $category = str_replace('c:', '', $item);
            }

            include_once('includes/db_queries.php');
            if ($category != 0) {
                $data = spp_get_post('category', $category);
                $data = $data[0];
            } elseif ($post != 0) {
                $data = spp_get_post('post', $post);
                $data = $data[0];
            } else {
                // If no post or category is selected, use the most recent post.
                $data = spp_get_post('post');
                $data = $data[0];
                if (!$data) {
                    $title = "Simple Post Preview";
                    $length = 100;
                    $data = (object) array(
                        'post_title' => 'Error!',
                        'post_content' => 'This widget needs configuration',
                    );
                }
            }
        }

        if ($data != null) {
            // Set link url, post is default
            $url = get_bloginfo('url');
            $url .= ($link_to == 'Category') ? '?cat='.$data->term_id : '?p='.$data->ID;
            $html_link = '<a href="';
            $html_link .= $url;
            $html_link .= '">'.$link.'</a>';
        }

        // Print to view
        include('includes/view.php');
    }

    /**
     * Saves the widget settings
     */
    public function update($new_instance, $old_instance)
    {
        $thumb = strip_tags(stripslashes($new_instance['thumbnail']));
        $instance = $old_instance;
        $instance['title'] = strip_tags(stripslashes($new_instance['title']));
        $instance['item'] = strip_tags(stripslashes($new_instance['item']));
        $instance['thumbnail'] = $thumb != 'checked' ? false : true;
        $instance['thumbnail_size'] = strip_tags(stripslashes($new_instance['thumbnail_size']));
        $instance['data_to_use'] = strip_tags(stripslashes($new_instance['data_to_use']));
        $instance['length'] = strip_tags(stripslashes($new_instance['length']));
        $instance['link'] = strip_tags(stripslashes($new_instance['link']));
        $instance['link_to'] = strip_tags(stripslashes($new_instance['link_to']));

        return $instance;
    }

    /**
     * GUI for backend
     */
    public function form($instance)
    {
        $title = isset($instance['title']) ? htmlspecialchars($instance['title']) : '';
        $item = isset($instance['item']) ? htmlspecialchars($instance['item']) : '';
        $thumbnail = isset($instance['thumbnail']) ? htmlspecialchars($instance['thumbnail']) : '';
        $thumbnail_size = isset($instance['thumbnail_size']) ? htmlspecialchars($instance['thumbnail_size']) : '';
        $data_to_use = isset($instance['data_to_use']) ? htmlspecialchars($instance['data_to_use']) : '';
        $length = isset($instance['length']) ? htmlspecialchars($instance['length']) : '';
        $link = isset($instance['link']) ? htmlspecialchars($instance['link']) : '';
        $link_to = isset($instance['link_to']) ? htmlspecialchars($instance['link_to']) : '';

        /* Print interface */
        include('includes/interface.php');
    }
}
/* End of class */

/**
 * Register Widget
 */
function simple_post_preview_init()
{
    register_widget('SimplePostPreview\SimplePostPreview');
}
add_action('widgets_init', 'SimplePostPreview\simple_post_preview_init');

/**
 * Add CSS and JS to head
 */
function simple_post_preview_head()
{
    // Todo: register script the correct way
    $plug_path = WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
    echo '<link rel="stylesheet" type="text/css" href="' . $plug_path . '/css/simple-post-preview.css" />';
    echo '<script type="text/javascript" src="' . $plug_path . '/js/simple-post-preview.js"></script>';
}
add_action('admin_head', 'SimplePostPreview\simple_post_preview_head');
