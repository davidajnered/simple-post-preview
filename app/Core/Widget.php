<?php
/**
 * This is the main widget class that extends wordpress widget class.
 */
namespace SimplePostPreview\Core;

use SimplePostPreview\Core\Widget\Instance;

class Widget extends \WP_Widget
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

        $this->serviceProvider = ServiceProvider::getInstance();
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
            register_widget('SimplePostPreview\Core\Widget');
        });

        // Add scripts and css
        add_action('admin_head', function () {
            wp_enqueue_style('select2', '/' . SPP_RELPATH . '/libs/select2-3.5.0/select2.css');
            wp_enqueue_script('select2', '/' . SPP_RELPATH . '/libs/select2-3.5.0/select2.min.js', array('jquery'));
            wp_enqueue_style('simple_post_preview', '/' . SPP_RELPATH . '/public/css/style.css');
            wp_enqueue_script('simple_post_preview', '/' . SPP_RELPATH . '/public/js/script.js', array('jquery'));
        });

        add_action('wp_ajax_spp_search_posts', array($this, 'searchPosts'));
    }

    /**
     * Ajax callback to retreive search results for chosen select.
     */
    public function searchPosts()
    {
        $dbService = $this->serviceProvider->getDbService();

        $search = isset($_GET['search']) ? $_GET['search'] : false;
        $selected = isset($_GET['selected']) ? $_GET['selected'] : false;
        $options = array(
            'search' => $search,
            'selected' => $selected,
        );

        $posts = $dbService->getPosts($options);

        $jsonData = array();
        foreach ($posts as $post) {
            $jsonData[] = array(
                'id' => $post->post_type . ':' . $post->ID,
                'text' => $post->post_title . ' (' . $post->post_type . ')',
            );
        }

        wp_send_json($jsonData);
        die();
    }

    /**
     * Displays the widget
     *
     * @param $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        $widgetInstance = new Instance($this->serviceProvider->getDbService(), $this->getId());
        $widgetInstance->setAttributes($instance);
        $tmplData = array_merge($args, $widgetInstance->getTmplData());

        // Check if user has own template
        $templateFile = '/wp-content/plugins/simple-post-preview/templates/widget.html';
        $userTemplateFile = $widgetInstance->getTemplate();
        if ($userTemplateFile) {
            if (file_exists(get_template_directory() . '/' . $userTemplateFile)) {
                $templateFile = str_replace(ABSPATH, '', get_template_directory()) . '/' . $userTemplateFile;
            }
        }

        echo $this->twig->render($templateFile, $tmplData);
    }

    /**
     * Saves the widget settings
     */
    public function update($newInstance, $oldInstance)
    {
        $widgetInstance = new Instance($this->serviceProvider->getDbService(), $this->getId());
        $widgetInstance->setAttributes($newInstance);

        return $widgetInstance->getAttributes();
    }

    /**
     * GUI for backend
     */
    public function form($instance)
    {
        error_log(var_export($instance, true));
        $widgetInstance = new Instance($this->serviceProvider->getDbService(), $this->getId());
        $widgetInstance->setAttributes($instance);

        $templateFile = '/wp-content/plugins/simple-post-preview/templates/admin.html';
        echo $this->twig->render($templateFile, $widgetInstance->getAdminTmplData());
    }
}
