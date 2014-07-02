<?php
/**
 *
 */

namespace SimplePostPreview;

class Widget
{
    /**
     * @var array
     */
    private $data = array();

    /**
     * var DbObject
     */
    private $dbObject;

    /**
     * Constructor.
     *
     * @param array widget variables
     */
    public function __construct(array $widget_variables, DbObject $dbObject)
    {
        if ($widget_variables) {
            foreach ($widget_variables as $name => $value) {
                $this->set($name, $value);
            }
        }

        $this->dbObject = $dbObject;
    }

    /**
     *
     */
    public function getType()
    {
        // Find dropdown value
        if (strpos($item, 'p:') !== false) {
            $post = str_replace('p:', '', $item);
        } elseif (strpos($item, 'c:') !== false) {
            $category = str_replace('c:', '', $item);
        }

        return $type;
    }

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
        return $data;
    }

    /**
     * Magic method to set object properties.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Magic method to get object properties.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->data[$name];
    }
}
