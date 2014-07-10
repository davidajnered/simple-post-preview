<?php
/**
 * Contains instance declarations and helper functions.
 */
namespace SimplePostPreview\Core\Widget;

use SimplePostPreview\Core\Services\DbService;

class InstanceParent
{
    /**
     * var DbService
     */
    protected $dbService;

    /**
     * @var array
     */
    private $attributes = array();

    /**
     * @var string
     */
    private $idBase = 'simple_post_preview';

    /**
     * @var array
     */
    private $fields = array(
        'title',
        'item_id',
        'item_type',
        'content_type',
        'thumbnail_switch',
        'thumbnail_size',
        'length',
        'link_title',
        'show_categories',
        'template',
    );

    /**
     * Constructor.
     *
     * @param array widget variables
     */
    public function __construct(DbService $dbService, $id)
    {
        $this->dbService = $dbService;
        $this->attributes['id'] = $id;
    }

    /**
     * Imitation of wordpress get_field_name
     *
     * @param string $fieldName Field name
     * @return string Name attribute for $fieldName
     */
    public function getFieldName($fieldName)
    {
        return 'widget-' . $this->idBase . '[' . $this->attributes['id'] . '][' . $fieldName . ']';
    }

    /**
     * Imitation of wordpress get_field_id
     *
     * @param string $fieldName Field name
     * @return string ID attribute for $fieldName
     */
    public function getFieldId($fieldName)
    {
        return 'widget-' . $this->idBase . '-' . $this->attributes['id'] . '-' . $fieldName;
    }

    /**
     *
     */
    public function setAttribute($name, $value)
    {
        $function = 'set' . $this->getMethodNameFromAttribute($name);
        $this->$function($value);
    }

    /**
     *
     */
    public function setAttributes(array $attributes)
    {
        foreach ($this->fields as $name) {
            $value = isset($attributes[$name]) ? $attributes[$name] : null;
            $method = 'set' . $this->getMethodNameFromAttribute($name);
            $this->$method($value);
        }

        if (isset($attributes['item'])) {
            $this->setItem($attributes['item']);
        }
    }

    /**
     *
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Todo: ugly! Find better way
     */
    private function getMethodNameFromAttribute($name)
    {
        $parts = explode('_', $name);
        foreach ($parts as $key => $part) {
            $parts[$key] = ucfirst($part);
        }
        $method = implode('', $parts);

        return $method;
    }

    /**
     *
     */
    private function getAttributeFromMethodName($method)
    {
        return strtolower(preg_replace('/\B([A-Z])/', '_$1', substr($method, 3)));
    }

    /**
     * Proxy set and get calls.
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {
            call_user_func_array($method, $arguments);
        } else {
            $action = substr($method, 0, 3);
            $attributeName = $this->getAttributeFromMethodName($method);
            if ($action == 'set') {
                $this->attributes[$attributeName] = $arguments[0];
            } elseif ($action == 'get') {
                return $this->attributes[$attributeName];
            }
        }
    }
}
