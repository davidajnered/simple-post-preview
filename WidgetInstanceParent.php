<?php
/**
 *
 */

namespace SimplePostPreview;

class WidgetInstanceParent
{
    /**
     * var DbObject
     */
    protected $dbObject;

    /**
     * @var array
     */
    private $data = array();

    /**
     * @var string
     */
    private $idBase = 'simple_post_preview';

    /**
     * Constructor.
     *
     * @param array widget variables
     */
    public function __construct(DbObject $dbObject, $id)
    {
        $this->dbObject = $dbObject;
        $this->data['id'] = $id;
    }

    /**
     * Imitation of wordpress get_field_name
     *
     * @param string $fieldName Field name
     * @return string Name attribute for $fieldName
     */
    function getFieldName($fieldName) {
        return 'widget-' . $this->idBase . '[' . $this->data['id'] . '][' . $fieldName . ']';
    }

    /**
     * Imitation of wordpress get_field_id
     *
     * @param string $fieldName Field name
     * @return string ID attribute for $fieldName
     */
    function getFieldId($fieldName) {
        return 'widget-' . $this->idBase . '-' . $this->data['id'] . '-' . $fieldName;
    }

    /**
     *
     */
    public function setData(array $data)
    {
        foreach ($data as $name => $value) {
            $function = 'set' . ucfirst($name);
            $this->$function($value);
        }
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * Proxy set and get calls.
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            call_user_func_array($name, $arguments);
        } else {
            $action = substr($name, 0, 3);
            $name = lcfirst(substr($name, 3));
            if ($action == 'set') {
                $this->data[$name] = $arguments[0];
            } elseif ($action == 'get') {
                return $this->data[$name];
            }
        }
    }
}
