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
    ?>
    <?php echo $args['before_widget']; ?>
    <?php if($header != null) : ?>
      <?php echo $args['before_title']; ?><?php echo $header; ?><?php echo $args['after_title']; ?>
      <p class="sub-header"><?php echo $data->post_title; ?></p>
    <?php else : ?>
      <?php echo $args['before_title']; ?><?php echo $data->post_title; ?><?php echo $args['after_title']; ?>
    <?php endif; ?>

    <p>
      <?php $content = strip_tags($data->post_content);
      echo (strlen($content) > $length) ? (substr($content, 0, $length).'&hellip; ') : $content; ?>
    </p>
    <p><?php echo $html_link ?></p>
    <?php echo $args['after_widget']; ?>

    <?php
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
    ?>

      <p>
      <label for="<?php echo $this->get_field_name('header'); ?>"><?php echo __('Header:') ?></label><br>
      <input id="<?php echo $this->get_field_id('header') ?>"
             name="<?php echo $this->get_field_name('header'); ?>"
             type="text"
             value="<?php echo $header; ?>"/>
      </p>

      <p><label for="<?php echo $this->get_field_name('category'); ?>"><?php echo __('Select category:'); ?></label><br>
      <select name="<?php echo $this->get_field_name('category'); ?>"
              id="<?php echo $this->get_field_id('category'); ?>"
              style="width:170px">
      <?php $result = $wpdb->get_results(
        "SELECT {$wpdb->terms}.term_id, name FROM {$wpdb->terms}
         LEFT JOIN {$wpdb->term_taxonomy}
         ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id
         WHERE {$wpdb->term_taxonomy}.taxonomy = 'category'
         AND {$wpdb->term_taxonomy}.count > 0;"
      );
      foreach($result as $category) : ?>
        <option <?php echo ($category->term_id == $instance['category']) ? 'selected' : '' ?> value="<?php echo $category->term_id; ?>">
          <?php echo $category->name; ?>
        </option>
      <?php endforeach; ?>
      </select></p>

      <p>
      <label for="<?php echo $this->get_field_name('thumbnail'); ?>"><?php echo __('Thumbnail:'); ?></label><br>
      <input id="<?php echo $this->get_field_id('thumbnail') ?>"
             name="<?php echo $this->get_field_name('thumbnail'); ?>"
             type="checkbox"
             value="checked"
             <?php echo $thumbnail ? 'checked': ''; ?>>
      Show thumbnail in preview
      </p>

      <p>
        Thumbnail size:
      <input id="<?php echo $this->get_field_id('thumbnail_size') ?>"
             name="<?php echo $this->get_field_name('thumbnail_size'); ?>"
             type="text"
             value="<?php echo $thumbnail_size; ?>"/>
      </p>

      <p>
      <label for="<?php echo $this->get_field_name('length'); ?>"><?php echo __('Length of preview:'); ?></label><br>
      <input id="<?php echo $this->get_field_id('length'); ?>"
             name="<?php echo $this->get_field_name('length'); ?>"
             type="text"
             value="<?php echo $length; ?>" />
      </p>

      <p>
      <label for="<?php echo $this->get_field_name('ellipsis'); ?>"><?php echo __('Ellipsis:'); ?></label><br>
      <input id="<?php echo $this->get_field_id('ellipsis'); ?>"
             name="<?php echo $this->get_field_name('ellipsis'); ?>"
             type="text"
             value="<?php echo $ellipsis; ?>" />
      </p>

      <p><label for="<?php echo $this->get_field_name('link_to'); ?>"><?php echo __('Link to:'); ?></label><br>
      <select name="<?php echo $this->get_field_name('link_to'); ?>"
              id="<?php echo $this->get_field_id('link_to'); ?>"
              style="width:170px">
        <?php $options = array('Post', 'Category');
        foreach($options as $option) : ?>
        <option value="<?php echo $option; ?>" <?php echo $option == $instance['link_to'] ? 'selected' : '' ?>><?php echo $option; ?></option>
        <?php endforeach; ?>
      </select></p>

  <?php
  }
} /* End of class */

 /**
  * Register Widget
  */
function simple_post_preview_init() {
  register_widget('simple_post_preview');
}

function add_css() {
  print '<link rel="stylesheet" type="text/css" href="/css/simple-post-preview.css" />';
}

add_action('wp_head', 'add_css');
add_action('widgets_init', 'simple_post_preview_init');

?>
