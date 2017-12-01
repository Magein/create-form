<?php

namespace Magein\createForm\library;

use Magein\createForm\library\filter\Filter;

class FormConfig
{
    protected $class;
    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $type = '';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var integer
     */
    protected $required = 1;

    /**
     * @var string
     */
    protected $placeholder = '';

    /**
     * @var array
     */
    protected $length = [0, 0];

    /**
     * @var string|array
     */
    protected $value = '';

    /**
     * @var bool
     */
    protected $disabled = false;

    /**
     * FormItemConfig constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        if ($data) {
            $this->init($data);
        }

        $this->setClass();
    }

    /**
     * @return bool
     */
    public function setClass()
    {
        $class = preg_replace('/\\\/', '/', get_class($this));
        $class = explode('/', $class);
        $this->class = end($class);
        return true;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param array $data
     * @param Filter|null $filter
     * @return bool
     */
    public function init(array $data, Filter $filter = null)
    {
        if (empty($data)) {
            return false;
        }

        if (empty($data['name'])) {
            $data['name'] = 'e' . rand(100, 999) . 'n' . rand(100, 999);
        }

        $properties = $this->getProperties(true);

        if ($filter) {
            foreach ($properties as $property) {

                $param = isset($data[$property]) ? $data[$property] : null;
                if ($param) {

                    if (method_exists($filter, $property)) {
                        if (!call_user_func([$filter, $property], $param)) {
                            return false;
                        }
                    }

                    $this->$property = $param;
                }
            }
        } else {
            foreach ($properties as $property) {
                $this->$property = isset($data[$property]) ? $data[$property] : null;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @return array
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return array|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function setValue($value)
    {
        // 去除前后空格以及过滤html标签
        if (is_string($value)) {
            $value = strip_tags(trim($value));
        }

        // 请注意0值
        if (intval($this->required) && ($value === null || $value === '')) {
            return false;
        }

        // 值不为空则验证长度
        if (is_string($value) || is_int($value)) {
            if (null !== $value && $value !== '') {
                if (is_string($value) || is_int($value)) {
                    $minLength = isset($this->length[0]) ? $this->length[0] : 0;
                    $maxLength = isset($this->length[1]) ? $this->length[1] : 0;
                    if ($minLength && $maxLength) {
                        $length = mb_strlen($value);
                        if ($minLength > $length || $maxLength < $length) {
                            return false;
                        }
                    }
                }
            }
        }

        $this->value = $value;

        return true;
    }

    /**
     * @param bool $toArray
     * @return array
     */
    public function getProperties($toArray = false)
    {
        $refection = new \ReflectionClass($this);

        $properties = $refection->getProperties();

        if ($toArray) {
            foreach ($properties as $key => $item) {
                $properties[$key] = $item->name;
            }
        }

        return $properties;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $properties = $this->getProperties();

        $data = [];

        foreach ($properties as $property) {

            $name = $property->name;

            $data[$name] = $this->$name;
        }

        $this->setClass();

        return $data;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}