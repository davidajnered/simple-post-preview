<?php echo $args['before_widget']; ?>
<?php if($header != null) : ?>
  <?php echo $args['before_title']; ?><?php echo $header; ?><?php echo $args['after_title']; ?>
  <p class="sub-header"><?php echo $data->post_title; ?></p>
<?php else : ?>
  <?php echo $args['before_title']; ?><?php echo $data->post_title; ?><?php echo $args['after_title']; ?>
<?php endif; ?>

<p>
  <?php if($thumbnail == TRUE) {
    echo get_the_post_thumbnail($data->ID, $thumbnail_size);
  } ?>

  <?php $content = strip_tags($data->post_content);
  if(strlen($content) > $length) {
    if($length > 0) {
      $content = substr($content, 0, $length).'&hellip; ';
    } else {
      $content = '';
    }
  }
  print $content; ?>
</p>
<p><?php echo $html_link ?></p>
<?php echo $args['after_widget']; ?>
