<?php
/*
 * DdObject is the database layer for simple post preview
 */
namespace SimplePostPreview\Core\Services;

class DbService
{
    /**
     * Todo: Get from settings page
     * @var array
     */
    private $postTypes;

    /**
     * Constructor.
     */
    public function __construct(array $options = array())
    {
        global $wpdb;

        $this->postTypes = get_option('simple-post-preview_post_types');

        if (isset($options['post_types']) && is_array($options['post_types'])) {
            $this->postTypes = $options['post_types'];
        } else {
            $this->postTypes = array('post');
        }
    }

    /**
     * Get all posts or all posts from a category
     */
    public function getPosts($options = array())
    {
        global $wpdb;

        $performSearch = (isset($options['search']) && $options['search']) ? true : false;
        $includeSelected = (isset($options['selected']) && $options['selected'] && is_numeric($options['selected'])) ? true : false;

        // Build query
        $query = "
            SELECT ID, post_title, post_type
            FROM {$wpdb->posts}
            WHERE post_status = 'publish'
            AND post_type IN ('" . implode("','", $this->postTypes) ."')
        ";

        // Search
        if ($performSearch) {
            $query .= " AND post_title LIKE '%" . $options['search'] . "%'";
        } elseif ($includeSelected) {
            $query .= " AND ID = " . $options['selected'];
        }

        $query .= " ORDER BY post_date DESC";

        // Show all results when search is performed
        if (!$performSearch) {
            $query .= " LIMIT 10;";
        }

        $posts = $wpdb->get_results($query);

        return $posts;
    }

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
}
