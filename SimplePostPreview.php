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
        $widgetInstance = new WidgetInstance($this->serviceProvider->getDbObject(), $this->getId());
        $widgetInstance->setAttributes($instance);

        $tmplData = array_merge($args, $widgetInstance->getTmplData());
        echo $this->twig->render('widget.html', $tmplData);
    }

    /**
     * Saves the widget settings
     */
    public function update($newInstance, $oldInstance)
    {
        $widgetInstance = new WidgetInstance($this->serviceProvider->getDbObject(), $this->getId());
        $widgetInstance->setAttributes($newInstance);

        return $widgetInstance->getAttributes();
    }

    /**
     * GUI for backend
     */
    public function form($instance)
    {
        $widgetInstance = new WidgetInstance($this->serviceProvider->getDbObject(), $this->getId());
        $widgetInstance->setAttributes($instance);

        echo $this->twig->render('admin.html', $widgetInstance->getAdminTmplData());
    }
}
