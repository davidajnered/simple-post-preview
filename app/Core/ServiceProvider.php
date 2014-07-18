<?php
/**
 * ServiceProvider acts a container/provider in a dependency injection design pattern.
 *
 * It stores commonly used objects that are returned when requested.
 */
namespace SimplePostPreview\Core;

use SimplePostPreview\Core\Services\DbService;
use SimplePostPreview\Core\Services\TwigService;

class ServiceProvider
{
    /**
     * @var array
     */
    private $services;

    /**
     * Constructor.
     */
    private function __construct()
    {
        $this->services['dbService'] = new DbService();
        $this->services['twigHelper'] = new TwigService();
    }

    /**
     * Singleton instantiation.
     */
    public static function getInstance()
    {
        static $instance = null;

        if (!$instance) {
            $instance = new ServiceProvider();
        }

        return $instance;
    }

    /**
     * Magic get method for services.
     *
     * @param string $name
     * @param mixed $arguments
     * @return Object the requested service
     */
    public function __call($name, $arguments)
    {
        if (strpos($name, 'get') === 0) {
            $service = lcfirst(substr($name, 3));
            return $this->services[$service];
        }
    }
}
