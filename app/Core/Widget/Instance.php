<?php
/**
 *
 */
namespace SimplePostPreview\Core\Widget;

class Instance extends InstanceParent
{
    /**
     * Item isn't really a fields, it's a combined field of item_type and item_id.
     *
     * @param string $value
     */
    public function setItem($value)
    {
        if (strpos($value, ':') != false) {
            list($item_type, $item_id) = explode(':', $value);
            $this->setAttribute('item_type', $item_type);
            $this->setAttribute('item_id', $item_id);
        }
    }

    /**
     * Get front end template data to be used with Twig.
     *
     * @return array $tmplData
     */
    public function getTmplData()
    {
        $tmplData = array();
        $itemId = $this->getItemId();
        $itemType = $this->getItemType();

        if (empty($itemId) || empty($itemType)) {
            return array();
        }

        if ($itemType == 'post') {
            $post = get_post($itemId);
            $tmplData = (array) $post;
        } elseif ($itemType == 'category') {
            $posts = get_posts(array(
                'posts_per_page' => 1,
                'category' => $itemId,
                'orderby' => 'post_date',
                'order' => 'DESC'
            ));
            $tmplData = (array) $posts[0];
        }

        // Add widget data (might be good for if statements and such)
        $tmplData = array_merge($this->getAttributes(), $tmplData);

        // Widget selected content
        $content = '';
        if ($this->getLength() != -1) {
            $content = ($this->getContentType() == 'excerpt') ? $tmplData['post_excerpt'] : $tmplData['post_content'];
        }

        if ($this->getLength() != -1 || $this->getLength() != 0) {
            $content = $this->getShortenedContent($content, $this->getLength());
        }
        $tmplData['content'] = $content;

        // Fill tmplData array with wordpress data
        if (isset($tmplData['ID'])) {

            // Permalink
            $tmplData['permalink'] = get_permalink($tmplData['ID']);

            // Categories
            $categoryIds = wp_get_post_categories($tmplData['ID']);
            foreach ($categoryIds as $categoryId) {
                $categoryData = (array) get_category($categoryId);
                $categoryData['url'] = get_category_link($categoryId);
                $categories[] = $categoryData;
            }

            $tmplData['categories'] = !empty($categories) ? $categories : array();

            // Featured image
            $attachmentId = get_post_thumbnail_id($tmplData['ID']);
            $tmplData['featured_image']['attachment_id'] = $attachmentId;
            $featuredImage = wp_get_attachment_metadata($attachmentId);
            $tmplData['featured_image'] = !empty($featuredImage) ? $featuredImage : array();
            $tmplData['show_image'] = !empty($tmplData['thumbnail_switch']) ? true : false;

            // Add url for easy access
            foreach (get_intermediate_image_sizes() as $size) {
                $imageSrc = wp_get_attachment_image_src($attachmentId, $size);
                $tmplData['featured_image'][$size . '_url'] = $imageSrc[0]; // url

                if ($size == $this->getThumbnailSize()) {
                    $tmplData['image_url'] = $imageSrc[0];
                }
            }
        }

        $tmplData['excerpt_more'] = '';

        // Trigger action
        $customTmplData = do_action('simple_post_preview_tmpl_data', $tmplData);

        $tmplData = (!empty($customTmplData) && is_array($customTmplData)) ? $customTmplData : $tmplData;

        return $tmplData;
    }

    /**
     * Return admin template data to be used with Twig.
     *
     * @return array $tmplData
     */
    public function getAdminTmplData()
    {
        $tmplData = array();

        foreach ($this->getAttributes() as $name => $value) {
            $tmplData[$name] = $value;
            $tmplData[$name . '_form_id'] = $this->getFieldId($name);
            $tmplData[$name . '_form_name'] = $this->getFieldName($name);
        }

        // Item isn't really a field and we need it
        $tmplData['item_form_id'] = $this->getFieldId('item');
        $tmplData['item_form_name'] = $this->getFieldName('item');

        // Thumbnail sizes
        $thumbnailSizes = $this->dbService->getThumbnailSizes();
        $tmplData = array_merge($tmplData, $this->getThumbnailSizesTmplData($thumbnailSizes));

        // Categories
        $categories = $this->dbService->getCategories();
        $tmplData = array_merge($tmplData, $this->getCategoriesTmplData($categories));

        // Posts
        $posts = $this->dbService->getPosts();
        $tmplData = array_merge($tmplData, $this->getPostsTmplData($posts));

        return $tmplData;
    }

    /**
     * Format data for template engine.
     *
     * @param array $posts
     * @return array $tmplData
     */
    private function getPostsTmplData(array $posts)
    {
        $tmplData = array();
        if ($posts) {
            foreach ($posts as $post) {
                $tmplData['posts'][] = array(
                    'id' => $post->ID,
                    'name' => $post->post_title,
                    'type' => $post->post_type
                );
            }
        }

        return $tmplData;
    }

    /**
     * Format data for template engine.
     *
     * @param array $categories
     * @return array $tmplData
     */
    private function getCategoriesTmplData(array $categories)
    {
        $tmplData = array();
        if ($categories) {
            foreach ($categories as $category) {
                $tmplData['categories'][] = array(
                    'id' => $category->term_id,
                    'name' => $category->name
                );
            }
        }

        return $tmplData;
    }

    /**
     * Format data for template engine.
     *
     * @param array $thumbnailSizez
     * @return array $tmplData
     */
    private function getThumbnailSizesTmplData(array $thumbnailSizes)
    {
        $tmplData = array();
        if ($thumbnailSizes) {
            foreach ($thumbnailSizes as $size => $description) {
                $tmplData['thumbnail_sizes'][] = array(
                    'name' => $size,
                    'description' => $description
                );
            }
        }

        return $tmplData;
    }

    /**
     *
     */
    private function getShortenedContent($text, $max_length, $offset = 0)
    {
        $text = strip_tags($text);
        $searching = true;

        while ($searching == true) {
            $offset = strpos($text, '.', $offset) + 1;
            if ($offset > $max_length) {
                $excerpt = substr($text, 0, $offset);
                $searching = false;
            }

            if (strlen($text) < $max_length) {
                $excerpt = $text;
                $searching = false;
            }
        }

        return $excerpt;
    }
}
