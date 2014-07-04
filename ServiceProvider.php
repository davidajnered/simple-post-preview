<?php
/**
 *
 */

namespace SimplePostPreview;

class ServiceProvider
{
    /**
     * @var array
     */
    private $services;

    /**
     * Constructor.
     */
    public function __construct($relpath)
    {
        $this->services['dbObject'] = new DbObject(array('cache_path' => ABSPATH . $relpath . '/cache'));
        $this->services['twigHelper'] = new TwigHelper($relpath);
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
