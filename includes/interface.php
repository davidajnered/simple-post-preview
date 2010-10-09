  <div class="simple-post-preview">
    <p>
      <label for="<?php echo $this->get_field_name('header'); ?>"><?php echo __('Header:') ?></label><br>
      <input id="<?php echo $this->get_field_id('header') ?>" name="<?php echo $this->get_field_name('header'); ?>" type="text" value="<?php echo $header; ?>"/>
    </p>

    <p>
      <label for="<?php echo $this->get_field_name('category'); ?>"><?php echo __('Select category:'); ?></label><br>
      <select name="<?php echo $this->get_field_name('category'); ?>" id="<?php echo $this->get_field_id('category'); ?>">
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
      <input id="<?php echo $this->get_field_id('thumbnail') ?>" name="<?php echo $this->get_field_name('thumbnail'); ?>" type="checkbox" value="checked" <?php echo $thumbnail ? 'checked': ''; ?>>
      Show thumbnail in preview
    </p>

    <p>
      Thumbnail size:
      <input id="<?php echo $this->get_field_id('thumbnail_size') ?>" name="<?php echo $this->get_field_name('thumbnail_size'); ?>" type="text" value="<?php echo $thumbnail_size; ?>"/>
    </p>

    <p>
      <label for="<?php echo $this->get_field_name('length'); ?>"><?php echo __('Length of preview:'); ?></label><br>
      <input id="<?php echo $this->get_field_id('length'); ?>" name="<?php echo $this->get_field_name('length'); ?>" type="text" value="<?php echo $length; ?>" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_name('ellipsis'); ?>"><?php echo __('Ellipsis:'); ?></label><br>
      <input id="<?php echo $this->get_field_id('ellipsis'); ?>" name="<?php echo $this->get_field_name('ellipsis'); ?>" type="text" value="<?php echo $ellipsis; ?>" />
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
