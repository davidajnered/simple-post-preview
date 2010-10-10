<div class="simple-post-preview">
  <p>
    <label for="<?php echo $this->get_field_name('title'); ?>"><?php echo __('Title:') ?></label><br>
    <input id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
    <small>If empty the posts title will be used</small>
  </p>

  <p>
    <label for="<?php echo $this->get_field_name('category'); ?>"><?php echo __('Select a category:'); ?></label><br>
    <select name="<?php echo $this->get_field_name('category'); ?>" id="<?php echo $this->get_field_id('category'); ?>">
    <?php include_once('db_queries.php');
      foreach(spp_get_categories() as $category) : ?>
        <option <?php echo ($category->term_id == $instance['category']) ? 'selected' : '' ?> value="<?php echo $category->term_id; ?>">
          <?php echo $category->name; ?>
        </option>
      <?php endforeach; ?>
    </select>
  </p>

  <p>
    <label for="<?php echo $this->get_field_name('thumbnail'); ?>"><?php echo __('Thumbnail:'); ?></label><br>
    <input id="<?php echo $this->get_field_id('thumbnail') ?>" name="<?php echo $this->get_field_name('thumbnail'); ?>" type="checkbox" value="checked" <?php echo $thumbnail ? 'checked': ''; ?>>
    Show thumbnail in preview
  </p>

  <p>
    Thumbnail size:
    <input id="<?php echo $this->get_field_id('thumbnail_size') ?>" name="<?php echo $this->get_field_name('thumbnail_size'); ?>" type="text" value="<?php echo $thumbnail_size; ?>"/>
    <small>The name of the thumbnail size specified in functions.php</small>
  </p>

  <p>
    <label for="<?php echo $this->get_field_name('length'); ?>"><?php echo __('Length of preview:'); ?></label><br>
    <input id="<?php echo $this->get_field_id('length'); ?>" name="<?php echo $this->get_field_name('length'); ?>" type="text" value="<?php echo $length; ?>" />
  </p>

  <p>
    <label for="<?php echo $this->get_field_name('link'); ?>"><?php echo __('Link title:'); ?></label><br>
    <input id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" />
  </p>

  <p>
    <label for="<?php echo $this->get_field_name('link_to'); ?>"><?php echo __('Link to:'); ?></label><br>
    <select name="<?php echo $this->get_field_name('link_to'); ?>" id="<?php echo $this->get_field_id('link_to'); ?>">
    <?php $options = array('Post', 'Category');
      foreach($options as $option) : ?>
        <option value="<?php echo $option; ?>" <?php echo $option == $instance['link_to'] ? 'selected' : '' ?>><?php echo $option; ?></option>
      <?php endforeach; ?>
    </select>
  </p>
</div>
