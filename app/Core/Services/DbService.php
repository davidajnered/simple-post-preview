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
    private $postTypes = array('post');

    /**
     * @var string
     */
    private $cachePath;

    /**
     * @var bool
     */
    private $errorHasBeenShown = false;

    /**
     * Constructor.
     */
    public function __construct(array $options = array())
    {
        global $wpdb;

        $this->postTypes = get_option('simple-post-preview_post_types');

        if (isset($options['post_types']) && is_array($options['post_types'])) {
            $this->postTypes = $options['post_types'];
        }

        if (isset($options['cache_path'])) {
            $this->cachePath = $options['cache_path'];
        }

        if (!file_exists($this->cachePath)) {
            add_action('admin_notices', array($this, 'showCachePathError'));
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

    /**
     * Save cache.
     *
     * @param string $key
     * @param mixed $data
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

        @file_put_contents($filename, serialize($data));
    }

    /**
     * Load cache.
     *
     * @param string $key
     * @return $cache
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
     * Delete specific cache file.
     *
     * @param string $key
     */
    public function clearCache($key)
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

    /**
     * Delete all cache files.
     */
    public function clearAllCache()
    {
        if (!$this->cachePath) {
            return;
        }

        $files = glob($this->cachePath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Show admin error if there's a problem with the cache path.
     */
    public function showCachePathError() {
        if (!$this->errorHasBeenShown) {
            $output = '
                <div class="error">
                    <p><b>Simple Post Preview</b>: cache file could not be created in <b>/wp-content/uploads/cache</b>.</p>
                </div>
            ';

            echo $output;

            $this->errorHasBeenShown = true;
        }
    }
}
