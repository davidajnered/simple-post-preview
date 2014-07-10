<?php
/**
 * Load Twig Template Engine
 */
namespace SimplePostPreview\Core\Services;

class TwigService
{
    /**
     * @var Twig
     */
    private $twig;

    /**
     * Constructor.
     */
    public function __construct()
    {
        require_once SPP_ABSPATH . '/libs/Twig/Autoloader.php';
        \Twig_Autoloader::register();
        $loader = new \Twig_Loader_Filesystem(ABSPATH);
        $args = array('autoescape' => false);
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
