<?php

namespace Magein\createform\library;

class FormConfig
{
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
    protected $length = [1, 255];

    /**
     * @var string|array
     */
    protected $value = '';

    /**
     * FormItemConfig constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        if ($data) {
            $this->init($data);
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    public function init(array $data)
    {
        if (empty($data)) {
            return false;
        }

        $properties = $this->getProperties(true);
        foreach ($properties as $property) {
            $this->$property = isset($data[$property]) ? $data[$property] : null;
        }

        if (empty($this->title)) {
            return false;
        }

        if (empty($this->name)) {
            return false;
        }

        if (empty($this->required)) {
            $this->required = 1;
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
        if (intval($this->required) && null === $value) {
            return false;
        }

        // 值不为空则验证长度
        if (null !== $value) {
            if (is_string($value) || is_int($value)) {
                $minLength = isset($this->length[0]) ? $this->length[0] : 1;
                $maxLength = isset($this->length[1]) ? $this->length[1] : 255;
                $length = mb_strlen($value);
                if ($minLength > $length || $maxLength < $length) {
                    return false;
                }
            }
        }

        $this->value = $value;

        return true;
    }

    /**
     * @param bool $toArray
     * @return \ReflectionProperty[]
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

        return $data;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $options
     * @return bool
     */
    protected function checkOptions($options)
    {
        if (empty($this->options)) {
            return false;
        }

        if (!is_array($options)) {
            return false;
        }

        foreach ($options as $option) {
            if (!is_string($option) && !is_int($option)) {
                return false;
            }

        }

        return true;
    }
}