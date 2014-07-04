<?php
/**
 *
 */

namespace SimplePostPreview;

class SimplePostPreview extends \WP_Widget
{
    /**
     * @var object
     */
    private $twig;

    /**
     * @var object ServiceProvider
     */
    private $serviceProvider;

    /**
     * @var string
     */
    private $relPath = 'wp-content/plugins/simple-post-preview';

    /**
     * Init method
     */
    public function __construct()
    {
        parent::__construct(
            'simple_post_preview',
            'Simple Post Preview',
            array(
                'description' => 'Creates pushes for your posts'
            )
        );

        $this->serviceProvider = new ServiceProvider($this->relPath);
        $this->twig = $this->serviceProvider->getTwigHelper()->getTwig();

        // Hook into wordpress
        $this->addWordpressActions();
    }

    /**
     * Get unique id number assigned by wordpress.
     *
     * @return int
     */
    private function getId()
    {
        return $this->number;
    }

    /**
     * Add
     */
    private function addWordpressActions()
    {
        // Register widget
        add_action('widgets_init', function () {
            register_widget('SimplePostPreview\SimplePostPreview');
        });

        // Add scripts and css
        $relPath = $this->relPath;
        add_action('admin_head', function () use ($relPath) {
            wp_enqueue_style('chosen', '/' . $relPath . '/lib/chosen/chosen.css');
            wp_enqueue_script('chosen', '/' . $relPath . '/lib/chosen/chosen.jquery.min.js', array('jquery'));

            wp_enqueue_style('simple_post_preview', '/' . $relPath . '/css/style.css');
            wp_enqueue_script('simple_post_preview', '/' . $relPath . '/js/script.js', array('jquery'));
        });
    }

    /**
     * Displays the widget
     *
     * @param $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        $widget = new WidgetInstance($instance, $this->getId());
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

        // $this->template->loadTemplate('widget.html');
        // echo $this->template->render(array('name' => 'David'));
    }

    /**
     * Saves the widget settings
     */
    public function update($newInstance, $oldInstance)
    {
        $widgetInstance = new WidgetInstance($this->serviceProvider->getDbObject(), $this->getId());
        $widgetInstance->setData($newInstance);

        return $widgetInstance->getData();
    }

    /**
     * GUI for backend
     */
    public function form($instance)
    {
        $widgetInstance = new WidgetInstance($this->serviceProvider->getDbObject(), $this->getId());
        $widgetInstance->setData($instance);

        echo $this->twig->render('admin.html', $widgetInstance->getTmplData());
    }
}