<?php
/*
 * Plugin Name: Simple Post Preview
 * Version: 1.2.0
 * Plugin URI: http://www.davidajnered.com/
 * Description: Simple Post Preview is a widget that creates pushes for posts.
 * Author: David Ajnered
 */

class simple_post_preview extends WP_Widget {

  function simple_post_preview(){
    $widget_ops = array('classname' => 'simple_post_preview',
                        'description' => __("Creates pushes for your posts"));

    $control_ops = array('width' => 100, 'height' => 100);
    $this->WP_Widget('simple_post_preview', __('Simple Post Preview'), $widget_ops, $control_ops);
  }

 /*
  * Displays the widget
  */
  function widget($args, $instance) {
    if(!empty($instance)) {
      // Variables
      $title = $instance['title'];
      $length = (int)$instance['length'];
      $category = (int)$instance['category'];
      $post = (int)$instance['post'];
      $thumbnail = $instance['thumbnail'];
      $thumbnail_size = $instance['thumbnail_size'];
      $link = $instance['link'];
      $link_to = $instance['link_to'];

      include_once('includes/db_queries.php');
      if($category != 0) {
        $data = spp_get_post('category', $category);
        $data = $data[0];
      } else if($post != 0) {
        $data = spp_get_post('post', $post);
        $data = $data[0];
      } else {
        // If database returns nothing, set default values
        $title = "Simple Post Preview";
        $length = 100;
        $data = (object)array(
          'post_title' => 'Error!',
          'post_content' => 'This widget needs configuration',
        );
      }
    }

    if($data != NULL) {
      // Set link url, post is default
      $html_link = '<a href="'.get_bloginfo('url');
      $html_link .= ($link_to == 'Category') ? '?cat='.$data->term_id : '?p='.$data->ID;
      $html_link .= '">'.$link.'</a>';
    }

    //Print to view
    include_once('includes/view.php');
  }

 /**
  * Saves the widget settings
  */
  function update($new_instance, $old_instance){
    $thumb = strip_tags(stripslashes($new_instance['thumbnail']));
    $instance = $old_instance;
    $instance['title'] = strip_tags(stripslashes($new_instance['title']));
    $instance['category'] = strip_tags(stripslashes($new_instance['category']));
    $instance['post'] = strip_tags(stripslashes($new_instance['post']));
    $instance['thumbnail'] = $thumb != 'checked' ? FALSE : TRUE;
    $instance['thumbnail_size'] = strip_tags(stripslashes($new_instance['thumbnail_size']));
    $instance['length'] = strip_tags(stripslashes($new_instance['length']));
    $instance['link'] = strip_tags(stripslashes($new_instance['link']));
    $instance['link_to'] = strip_tags(stripslashes($new_instance['link_to']));

    return $instance;
  }

 /**
  * Form for admin
  */
  function form($instance) {
    $title = htmlspecialchars($instance['title']);
    $category = htmlspecialchars($instance['category']);
    $post = htmlspecialchars($instance['post']);
    $thumbnail = htmlspecialchars($instance['thumbnail']);
    $thumbnail_size = htmlspecialchars($instance['thumbnail_size']);
    $length = htmlspecialchars($instance['length']);
    $link = htmlspecialchars($instance['link']);
    $link_to = htmlspecialchars($instance['link_to']);

    /* Print interface */
    include('includes/interface.php');
  }

} /* End of class */

 /**
  * Register Widget
  */
function simple_post_preview_init() {
  register_widget('simple_post_preview');
}
add_action('widgets_init', 'simple_post_preview_init');

function simple_post_preview_head() {
  echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('url').'/wp-content/plugins/simple-post-preview/css/simple-post-preview.css" />';
  echo '<script type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/simple-post-preview/js/simple-post-preview.js"></script>';
}
add_action('admin_head', 'simple_post_preview_head');

?>