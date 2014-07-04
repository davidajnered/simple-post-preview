<?php
/**
 *
 */

namespace SimplePostPreview;

class WidgetInstance extends WidgetInstanceParent
{
    // /**
    //  *
    //  */
    // public function getType()
    // {
    //     // Find dropdown value
    //     if (strpos($item, 'p:') !== false) {
    //         $post = str_replace('p:', '', $item);
    //     } elseif (strpos($item, 'c:') !== false) {
    //         $category = str_replace('c:', '', $item);
    //     }

    //     return $type;
    // }

    // /**
    //  * Override default set method for item.
    //  * Extract post type and post id from value
    //  */
    // public function setItem($value)
    // {
    //     // error_log('setType');
    //     // error_log(var_export($value, true));
    // }

    /**
     *
     */
    public function getTypeId()
    {
        if (strpos($this->get('item'), ':') != false) {
            list($type, $id) = explode(':', $this->get('item'));
        }

        // Find dropdown value
        if (strpos($item, 'p:') !== false) {
            $id = str_replace('p:', '', $item);
        } elseif (strpos($item, 'c:') !== false) {
            $id = str_replace('c:', '', $item);
        }

        return $type;
    }

    /**
     * Return template data to be used with twig.
     */
    public function getTmplData()
    {
        $tmplData = array();

        $data = $this->getData();
        foreach ($data as $name => $value) {
            $tmplData[$name] = $value;
            $tmplData[$name . '_id'] = $this->getFieldId($name);
            $tmplData[$name . '_name'] = $this->getFieldName($name);
        }

        // Posts
        $posts = $this->dbObject->getPosts();
        $tmplData = array_merge($tmplData, $this->getPostsTmplData($posts));

        // Categories
        $categories = $this->dbObject->getCategories();
        $tmplData = array_merge($tmplData, $this->getCategoriesTmplData($categories));

        // Thumbnail sizes
        $thumbnailSizes = $this->dbObject->getThumbnailSizes();
        $tmplData = array_merge($tmplData, $this->getThumbnailSizesTmplData($thumbnailSizes));

        // Link options
        $tmplData = array_merge($tmplData, $this->getLinkOptionsTmplData());

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
     * Format data for template engine.
     *
     * @return array $options
     */
    private function getLinkOptionsTmplData()
    {
        $linkOptions = array();
        $linkOptions['link_options'][] = array('name' => 'Post', 'value' => 'post');
        $linkOptions['link_options'][] = array('name' => 'Category', 'value' => 'category');

        return $linkOptions;
    }
}
