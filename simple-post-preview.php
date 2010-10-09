<?php
/*
 * Plugin Name: Simple Post Preview
 * Version: 1.0.1
 * Plugin URI: http://www.davidajnered.com/
 * Description: Simple Post Preview is a widget that displays (a part of) the latest post from a category.
 * Author: David Ajnered
 */

class simple_post_preview extends WP_Widget
{

  function simple_post_preview(){
    $widget_ops = array('classname' => 'simple_post_preview',
                        'description' => __("Displays (a part of) the latest post from a category"));

    $control_ops = array('width' => 100, 'height' => 100);
    $this->WP_Widget('simple_post_preview', __('Simple Post Preview'), $widget_ops, $control_ops);
  }

 /*
  * Displays the widget
  */
  function widget($args, $instance) {
    $data;
    $header;

    if(!empty($instance)) {
      /* Variables */
      global $wpdb;
      $header = $instance['header'];
      $length = (int)$instance['length'];
      $category = (int)$instance['category'];
      $thumbnail = $instance['thumbnail'];
      $thumbnail_size = $instance['thumbnail_size'];
      $ellipsis = $instance['ellipsis'];
      $link_to = $instance['link_to'];

      $data = $wpdb->get_results(
        "SELECT ID, post_title, post_content, post_date, post_status, guid, term_id
         FROM {$wpdb->posts}
         LEFT JOIN {$wpdb->term_relationships} ON object_id = ID
         LEFT JOIN {$wpdb->term_taxonomy} ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
         WHERE term_id = $category
         AND post_status = 'publish'
         ORDER BY post_date
         DESC LIMIT 0,1"
      );
    }

    /* If database returns nothing, set default values */
    if($data == null) {
      $header = "Error!";
      $data = array(
        (object) array(
          'post_title' => 'Error!',
          'post_content' => 'This widget needs configuration'
        )
      );
    }

    /* Set link url, post is default */
    $data = $data[0];
    if($link_to == 'Category') {
      $html_link = '<a href="'.get_bloginfo('url').'?cat='.$data->term_taxonomy_id.'">'.$ellipsis.'</a>';
    } else {
      $html_link = '<a href="'.get_bloginfo('url').'?p='.$data->ID.'">'.$ellipsis.'</a>';
    }
    /* Print to view */
    require_once('includes/view.php');
  }

 /**
  * Saves the widget settings
  */
  function update($new_instance, $old_instance){
    $instance = $old_instance;
    $instance['header'] = strip_tags(stripslashes($new_instance['header']));
    $instance['category'] = strip_tags(stripslashes($new_instance['category']));
    $thumb = strip_tags(stripslashes($new_instance['thumbnail']));
    $instance['thumbnail'] = $thumb != 'checked' ? FALSE : TRUE;
    $instance['thumbnail_size'] = strip_tags(stripslashes($new_instance['thumbnail_size']));
    $instance['length'] = strip_tags(stripslashes($new_instance['length']));
    $instance['ellipsis'] = strip_tags(stripslashes($new_instance['ellipsis']));
    $instance['link_to'] = strip_tags(stripslashes($new_instance['link_to']));
    return $instance;
  }

 /**
  * Form for admin
  */
  function form($instance) {
    global $wpdb;

    $header = htmlspecialchars($instance['header']);
    $category = htmlspecialchars($instance['category']);
    $thumbnail = htmlspecialchars($instance['thumbnail']);
    $thumbnail_size = htmlspecialchars($instance['thumbnail_size']);
    $length = htmlspecialchars($instance['length']);
    $ellipsis = htmlspecialchars($instance['ellipsis']);
    $link_to = htmlspecialchars($instance['link_to']);

    require_once('includes/interface.php');
  }
} /* End of class */

 /**
  * Register Widget
  */
function simple_post_preview_init() {
  register_widget('simple_post_preview');
}

function add_css() {
  print '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('url').'/wp-content/plugins/simple-post-preview/css/simple-post-preview.css" />';
}

add_action('admin_head', 'add_css');
add_action('widgets_init', 'simple_post_preview_init');

?>
