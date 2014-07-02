<?php
/**
 *
 */

namespace SimplePostPreview;

class DbObject
{

    public function __contructor()
    {

    }

    /**
     * Get all posts or all posts from a category
     */
    function getAllPosts($category = null)
    {
        global $wpdb;
        $query = "
            SELECT ID, post_title, post_content, post_date, post_status, guid, term_id
            FROM {$wpdb->posts}
            LEFT JOIN {$wpdb->term_relationships}
            ON object_id = ID
            LEFT JOIN {$wpdb->term_taxonomy}
            ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
            WHERE post_status = 'publish'
        ";

        if ($category != null) {
            $query .= " AND {$wpdb->term_taxonomy}.term_id = " . $category;
        }

        // Todo: don't limit to just post
        // Add settings page so user can specify post types

        $query .= "
            AND post_type = 'post'
            GROUP BY ID
            ORDER BY post_date;
        ";

        $data = $wpdb->get_results($query);

        return $data;
    }



    /**
     * Select a specific post or the latest post from a category
     */
    function getPost($type, $selector = null)
    {
        global $wpdb;

        if ($selector == null) {
            $query = "
                SELECT ID, post_title, post_content, post_excerpt, post_date, post_status, guid
                FROM {$wpdb->posts}
                LEFT JOIN {$wpdb->term_relationships}
                ON object_id = ID
                WHERE ID = (SELECT max(ID) FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish')
                LIMIT 1;
            ";
            $data = $wpdb->get_results($query);
        } else {
            switch($type) {
                case 'category':
                    $query = "
                        SELECT ID, post_title, post_content, post_excerpt, post_date, post_status, guid, term_id
                        FROM {$wpdb->posts}
                        LEFT JOIN {$wpdb->term_relationships}
                        ON object_id = ID
                        LEFT JOIN {$wpdb->term_taxonomy}
                        ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
                        WHERE term_id = $selector
                        AND post_status = 'publish'
                        ORDER BY post_date
                        DESC LIMIT 1;
                    ";
                    $data = $wpdb->get_results($query);
                    break;

                case 'post':
                    $query = "
                        SELECT ID, post_title, post_content, post_excerpt, post_date, post_status, guid
                        FROM {$wpdb->posts}
                        LEFT JOIN {$wpdb->term_relationships}
                        ON object_id = ID
                        WHERE ID = $selector
                        AND post_status = 'publish'
                        LIMIT 1;
                    ";
                    $data = $wpdb->get_results($query);
                    break;
            }
        }
        return $data;
    }

    /**
     * Get all categories
     */
    function getCategories()
    {
        global $wpdb;

        $query = "
            SELECT {$wpdb->terms}.term_id, name FROM {$wpdb->terms}
            LEFT JOIN {$wpdb->term_taxonomy}
            ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id
            WHERE {$wpdb->term_taxonomy}.taxonomy = 'category'
            AND {$wpdb->term_taxonomy}.count > 0;
        ";

        $categories = $wpdb->get_results($query);

        return $categories;
    }

    /**
     * Get all available thumbnail sizes
     * Retreived the data from the last uploaded picture.
     */
    function getThumbnailSizes()
    {
        global $wpdb;

        $query = "
            SELECT meta_value
            FROM {$wpdb->postmeta} AS postmeta
            WHERE post_id = (SELECT max(post_id)
            FROM {$wpdb->postmeta} AS postmeta
            LEFT JOIN {$wpdb->posts} AS posts
            ON postmeta.post_id = posts.ID
            WHERE post_mime_type LIKE '%image%')
            AND meta_key = '_wp_attachment_metadata'
        ";
        $data = $wpdb->get_results($query);

        foreach ($data as $object) {
            $data_array = unserialize($object->meta_value);
            if ($data_array != false && is_array($data_array)) {
                foreach ($data_array['sizes'] as $key => $values) {
                    $options[$key] = $key . ' [H:'.$values['height'].'px W:'.$values['width'].'px]';
                }
                ksort($options);
            } else {
                $options = array();
            }
        }

        return $options;
    }

    // /**
    //  *
    //  */
    // function spp_get_dropdown()
    // {
    //     $categories = spp_get_categories();
    //     $i = 0;
    //     foreach ($categories as $category) {
    //         $posts = spp_get_all_posts($category->term_id);
    //         $select[$i]['category_name'] = $category->name;
    //         $select[$i]['category_id'] = $category->term_id;
    //         $j = 0;
    //         foreach ($posts as $post) {
    //             $select[$i]['children'][$j]['post_name'] = $post->post_title;
    //             $select[$i]['children'][$j]['post_id'] = $post->ID;
    //             $j++;
    //         }
    //         $i++;
    //     }
    //     return $select;
    // }
}
