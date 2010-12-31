<?php

/**
 * Get allt posts from the db
 */
function spp_get_all_posts() {
  global $wpdb;
  $query =
    "SELECT ID, post_title, post_content, post_date, post_status, guid, term_id
     FROM {$wpdb->posts}
     LEFT JOIN {$wpdb->term_relationships}
     ON object_id = ID
     LEFT JOIN {$wpdb->term_taxonomy}
     ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
     WHERE post_status = 'publish'
     AND post_type = 'post'
     GROUP BY ID
     ORDER BY post_date
     ;";
  $data = $wpdb->get_results($query);
  return $data;
}

/**
 * Selects the latest post from a category
 */
function spp_get_post($type, $selector) {
  global $wpdb;
  switch($type) {
    case 'category':
      $data = $wpdb->get_results(
        "SELECT ID, post_title, post_content, post_date, post_status, guid, term_id
         FROM {$wpdb->posts}
         LEFT JOIN {$wpdb->term_relationships}
         ON object_id = ID
         LEFT JOIN {$wpdb->term_taxonomy}
         ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
         WHERE term_id = $selector
         AND post_status = 'publish'
         ORDER BY post_date
         DESC LIMIT 1;"
      );
      break;

    case 'post':
      $data = $wpdb->get_results(
        "SELECT ID, post_title, post_content, post_date, post_status, guid
         FROM {$wpdb->posts}
         LEFT JOIN {$wpdb->term_relationships}
         ON object_id = ID
         WHERE ID = $selector
         AND post_status = 'publish'
         LIMIT 1;"
      );
      break;
  }
  return $data;
}

/**
 * Get all categories
 */
function spp_get_categories() {
  global $wpdb;
  $categories = $wpdb->get_results(
    "SELECT {$wpdb->terms}.term_id, name FROM {$wpdb->terms}
     LEFT JOIN {$wpdb->term_taxonomy}
     ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id
     WHERE {$wpdb->term_taxonomy}.taxonomy = 'category'
     AND {$wpdb->term_taxonomy}.count > 0;"
  );
  return $categories;
}

/*
 * Get all available thumbnail sizes
 * Retreived the data from the last uploaded picture.
 */
function spp_get_thumbnail_sizes() {
  global $wpdb;
  $data = $wpdb->get_results(
    "SELECT meta_value FROM {$wpdb->postmeta}
     WHERE meta_key = '_wp_attachment_metadata'
     AND post_id = (SELECT max(post_id) FROM {$wpdb->postmeta});"
  );
  $data = unserialize($data[0]->meta_value);
  foreach($data['sizes'] as $key => $values) {
    $options[$key] = $key . ' [H:'.$values['height'].'px W:'.$values['width'].'px]';
  }
  ksort($options);
  return $options;
}