<?php
/**
 * Plugin Name: Simple Post Preview
 * Version: 1.2.6
 * Plugin URI: http://www.davidajnered.com/
 * Description: Simple Post Preview is a widget that creates pushes for posts.
 * Author: David Ajnered
 */

namespace SimplePostPreview;

class SimplePostPreview extends \WP_Widget
{
    /**
     * @var object
     */
    private $template;

    /**
     * @var object
     */
    private $dbObjet;

    /**
     * Init method
     */
    public function __construct()
    {
        parent::__construct(
            'simple_post_preview',
            'Simple Post Preview',
            array('description' => 'Creates pushes for your posts')
        );

        $this->db = new db();

        $this->initTemplateEngine();
    }

    /**
     * Instanciate temlate engine.
     */
    private function initTemplateEngine()
    {
        require_once ABSPATH . '/wp-content/plugins/simple-post-preview/lib/Twig/Autoloader.php';
        \Twig_Autoloader::register();

        $loader = new \Twig_Loader_Filesystem(ABSPATH . '/wp-content/plugins/simple-post-preview/templates');

        $args = array(
            'cache' => ABSPATH . '/wp-content/plugins/simple-post-preview/templates/cache',
        );

        $this->template = new \Twig_Environment($loader, $args);
    }

    /**
     * Displays the widget
     *
     * @param $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        $this->template->loadTemplate('widget.html');

        $widget = new Widget($instance);
        $type = $widget->getType();

        if (!empty($instance)) {
            include_once('includes/db_queries.php');
            if ($category != 0) {
                $data = spp_get_post('category', $category);
                $data = $data[0];
            } elseif ($post != 0) {
                $data = spp_get_post('post', $post);
                $data = $data[0];
            } else {
                // If no post or category is selected, use the most recent post.
                $data = spp_get_post('post');
                $data = $data[0];
                if (!$data) {
                    $title = "Simple Post Preview";
                    $length = 100;
                    $data = (object) array(
                        'post_title' => 'Error!',
                        'post_content' => 'This widget needs configuration',
                    );
                }
            }
        }

        if ($data != null) {
            // Set link url, post is default
            $url = get_bloginfo('url');
            $url .= ($link_to == 'Category') ? '?cat='.$data->term_id : '?p='.$data->ID;
            $html_link = '<a href="';
            $html_link .= $url;
            $html_link .= '">'.$link.'</a>';
        }

        // Print to view
        include('includes/view.php');

        // echo $this->template->render(array('name' => 'David'));
    }

    /**
     * Saves the widget settings
     */
    public function update($new_instance, $old_instance)
    {
        $thumb = strip_tags(stripslashes($new_instance['thumbnail']));
        $instance = $old_instance;
        $instance['title'] = strip_tags(stripslashes($new_instance['title']));
        $instance['item'] = strip_tags(stripslashes($new_instance['item']));
        $instance['thumbnail'] = $thumb != 'checked' ? false : true;
        $instance['thumbnail_size'] = strip_tags(stripslashes($new_instance['thumbnail_size']));
        $instance['data_to_use'] = strip_tags(stripslashes($new_instance['data_to_use']));
        $instance['length'] = strip_tags(stripslashes($new_instance['length']));
        $instance['link'] = strip_tags(stripslashes($new_instance['link']));
        $instance['link_to'] = strip_tags(stripslashes($new_instance['link_to']));

        return $instance;
    }

    /**
     * GUI for backend
     */
    public function form($instance)
    {
        $title = isset($instance['title']) ? htmlspecialchars($instance['title']) : '';
        $item = isset($instance['item']) ? htmlspecialchars($instance['item']) : '';
        $thumbnail = isset($instance['thumbnail']) ? htmlspecialchars($instance['thumbnail']) : '';
        $thumbnail_size = isset($instance['thumbnail_size']) ? htmlspecialchars($instance['thumbnail_size']) : '';
        $data_to_use = isset($instance['data_to_use']) ? htmlspecialchars($instance['data_to_use']) : '';
        $length = isset($instance['length']) ? htmlspecialchars($instance['length']) : '';
        $link = isset($instance['link']) ? htmlspecialchars($instance['link']) : '';
        $link_to = isset($instance['link_to']) ? htmlspecialchars($instance['link_to']) : '';

        /* Print interface */
        include('includes/interface.php');
    }
}
/* End of class */

/**
 * Register Widget
 */
function simple_post_preview_init()
{
    register_widget('SimplePostPreview\SimplePostPreview');
}
add_action('widgets_init', 'SimplePostPreview\simple_post_preview_init');

/**
 * Add css and js to head
 */
function simple_post_preview_css_and_scripts()
{
    wp_enqueue_style('simple_post_preview', '/wp-content/plugins/simple-post-preview/css/style.css');
    wp_enqueue_script('simple_post_preview', '/wp-content/plugins/simple-post-preview/js/script.js', array('jquery'));
}
add_action('admin_head', 'SimplePostPreview\simple_post_preview_css_and_scripts');
