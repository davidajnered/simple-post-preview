<?php
/**
 *
 */

namespace SimplePostPreview;

class TwigHelper
{
    /**
     * @var Twig
     */
    private $twig;

    /**
     * Constructor.
     */
    public function __construct($relpath)
    {
        require_once ABSPATH . $relpath . '/lib/Twig/Autoloader.php';
        \Twig_Autoloader::register();

        $loader = new \Twig_Loader_Filesystem(ABSPATH . $relpath . '/templates');

        $args = array(
            'autoescape' => false,
            // 'cache' => ABSPATH . $relpath . '/cache',
        );

        $this->twig = new \Twig_Environment($loader, $args);
    }

    /**
     * @return Twig
     */
    public function getTwig()
    {
        return $this->twig;
    }
}
