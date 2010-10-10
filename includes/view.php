<?php
  $output = $args['before_widget'].$args['before_title'];
  if($title != NULL) {
    $output .= $title;
  } else {
    $output .= $data->post_title;
  }
  $output .= $args['after_title'];

  if($thumbnail == TRUE) {
    $output .= get_the_post_thumbnail($data->ID, $thumbnail_size);
  }

  $content = strip_tags($data->post_content);
  if(strlen($content) > $length) {
    if($length > 0) {
      $content = substr($content, 0, $length).'&hellip; ';
    } else {
      $content = '';
    }
  }
  $output .= $content.$html_link.$args['after_widget'];
  echo $output;