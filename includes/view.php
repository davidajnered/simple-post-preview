<?php
$output = $args['before_widget'].$args['before_title'];

// Use custom title or post title
if($title != NULL) {
  $output .= $title;
} else {
  $output .= $data->post_title;
}

$output .= $args['after_title'];

// Show thumbnail
if($thumbnail == TRUE) {
  $output .= '<a href="' . $url . '">';
  $output .= get_the_post_thumbnail($data->ID, $thumbnail_size);
  $output .= '</a>';
}

// Use post content or post excerpt
if($data_to_use == 'excerpt') {
  $content = $data->post_excerpt;
} else {
  $content = $data->post_content;
}

// Show the specified length of the content
if($length <= -1) {
  $content = '';
} else if (strlen($content) > $length) {
  if($length > 0) {
    $content = substr($content, 0, $length) . '&hellip; ';
  }
}

// Link to post of category
$output .= $content . ' ' . $html_link . $args['after_widget'];

// Print
echo $output;