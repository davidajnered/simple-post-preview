<?php
/**
 *
 */

namespace SimplePostPreview;

class DbObject
{
    /**
     * @var array
     */
    private $postTypes = array('post', 'articles');

    /**
     *
     */
    private $cachePath;

    /**
     *
     */
    public function __construct(array $options = array())
    {
        global $wpdb;

        if (isset($options['post_types']) && is_array($options['post_types'])) {
            $this->postTypes = $options['post_types'];
        }

        if (isset($options['cache_path'])) {
            $this->cachePath = $options['cache_path'];
        }
    }

    /**
     * Get all posts or all posts from a category
     */
    public function getPosts($category = null)
    {
        if (!($posts = $this->loadCache('posts'))) {
            global $wpdb;
            $query = "SELECT ID, post_title, post_type FROM {$wpdb->posts} WHERE post_status = 'publish';";
            $posts = $wpdb->get_results($query);
            $this->setCache('posts', $posts);
        }

        return $posts;
    }

    // /**
    //  * Select a specific post or the latest post from a category
    //  */
    // public function getPost($type, $selector = null)
    // {
    //     if ($selector == null) {
    //         $query = "
    //             SELECT ID, post_title, post_content, post_excerpt, post_date, post_status, guid, post_type
    //             FROM {$this->db->posts}
    //             LEFT JOIN {$this->db->term_relationships}
    //             ON object_id = ID
    //             WHERE ID = (SELECT max(ID) FROM {$this->db->posts} WHERE post_type = 'post' AND post_status = 'publish')
    //             LIMIT 1;
    //         ";
    //     } else {
    //         if ($type == 'category') {
    //             $query = "
    //                 SELECT ID, post_title, post_content, post_excerpt, post_date, post_status, guid, term_id
    //                 FROM {$this->db->posts}
    //                 LEFT JOIN {$this->db->term_relationships}
    //                 ON object_id = ID
    //                 LEFT JOIN {$this->db->term_taxonomy}
    //                 ON {$this->db->term_relationships}.term_taxonomy_id = {$this->db->term_taxonomy}.term_taxonomy_id
    //                 WHERE term_id = $selector
    //                 AND post_status = 'publish'
    //                 ORDER BY post_date
    //                 DESC LIMIT 1;
    //             ";
    //         } elseif ($type == 'post') {
    //             $query = "
    //                 SELECT ID, post_title, post_content, post_excerpt, post_date, post_status, guid
    //                 FROM {$this->db->posts}
    //                 LEFT JOIN {$this->db->term_relationships}
    //                 ON object_id = ID
    //                 WHERE ID = $selector
    //                 AND post_status = 'publish'
    //                 LIMIT 1;
    //             ";
    //         }
    //     }

    //     if ($query) {
    //         return $this->db->get_results($query);
    //     }
    // }

    /**
     * Get all categories
     */
    public function getCategories()
    {
        $categories = get_categories(array('type' => $this->postTypes));

        return $categories;
    }

    /**
     * Get all available thumbnail sizes
     * Retreived the data from the last uploaded picture.
     */
    public function getThumbnailSizes()
    {
        $sizes = array();
        foreach (get_intermediate_image_sizes() as $size) {
            if (isset($_wp_additional_image_sizes[$size])) {
                $width = intval($_wp_additional_image_sizes[$size]['width']);
                $height = intval($_wp_additional_image_sizes[$size]['height']);
            } else {
                $width = get_option($size . '_size_w');
                $height = get_option($size . '_size_h');
            }

            $sizes[$size] = $size . ' [H:' . $height . 'px W:' . $width . 'px]';
        }

        return $sizes;
    }

    /**
     *
     */
    private function setCache($key, $data)
    {
        // Abort if cache path is not set
        if (!$this->cachePath) {
            return;
        }

        $filename = $this->cachePath . '/cache-' . $key;
        if (file_exists($filename)) {
            $this->clearCache($key);
        }

        $res = file_put_contents($filename, serialize($data));

        if ($res == false) {
            error_log('cache file ' . $filename . ' could not be created');
        }
    }

    /**
     *
     */
    private function loadCache($key)
    {
        // Abort if cache path is not set
        if (!$this->cachePath) {
            return;
        }

        $filename = $this->cachePath . '/cache-' . $key;
        if (file_exists($filename)) {
            $cache = unserialize(file_get_contents($filename));
            return $cache;
        }
    }

    /**
     *
     */
    private function clearCache($key)
    {
        // Abort if cache path is not set
        if (!$this->cachePath) {
            return;
        }

        $filename = $this->cachePath . '/cache-' . $key;
        if (file_exists($filename)) {
            unlink($filename);
        }
    }
}
