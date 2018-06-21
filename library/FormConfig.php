<?php

namespace Magein\createForm\library;

use Magein\createForm\library\constant\FormErrorConstant;

class FormConfig
{
    use FormError;

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
     * @var string
     */
    protected $description = '';

    /**
     * 验证不通过的错误信息
     * @var string
     */
    protected $message = '';

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
     * 合法的属性
     * @var array
     */
    private $legalProperties = [
        'class',
        'title',
        'type',
        'name',
        'required',
        'placeholder',
        'description',
        'message',
        'length',
        'value',
        'disabled'
    ];

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
     * @param string $title
     * @return bool
     */
    public function setTitle($title)
    {
        $title = $this->filterString($title);

        if (empty($title)) {
            $this->setError(FormErrorConstant::FORM_CONFIG_TITLE_ERROR);
            return false;
        }

        $this->title = $title;

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
     * @param string $type
     */
    public function setType($type)
    {
        $type = $this->filterString($type);

        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function setName($name)
    {
        if (empty($name) || !preg_match('/^[\w]+$/', $name)) {
            $this->setError(FormErrorConstant::FORM_CONFIG_NAME_ERROR);
            return false;
        }

        $this->name = $name;

        return true;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $required
     * @return bool
     */
    public function setRequired($required)
    {
        if ($required === '' || false === in_array($required, [0, 1])) {

            $this->setError(FormErrorConstant::FORM_CONFIG_REQUIRED_ERROR);

            return false;
        }

        $this->required = $required;

        return true;
    }

    /**
     * @return int
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @param string $placeholder
     * @return bool
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return true;
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param string $description
     * @return bool
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return true;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $message
     * @return bool
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return true;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param bool $disabled
     * @return bool
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return true;
    }

    /**
     * @return bool
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param array $length
     * @return bool
     */
    public function setLength($length)
    {
        if (empty($length)) {
            return true;
        }

        if (false === is_array($length) || count($length) != 2) {
            $this->setError(FormErrorConstant::FORM_CONFIG_LENGTH_PARAM_ERROR);
            return false;
        }

        $length = array_reduce($length, function ($length, $item) {
            $length[] = intval($item);
            return $length;
        });

        $this->length = $length;

        return true;
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
     * @param bool $isInit
     * @return bool
     */
    public function setValue($value, $isInit = false)
    {
        if (is_string($value)) {
            $value = $this->filterString($value);
        }

        if (false === $this->checkRequired($value, $isInit) || false === $this->checkLength($value)) {
            return false;
        }

        $this->value = $value;

        return true;
    }

    /**
     * 初始化的时候可以设置默认值，所以要绕过必填的验证
     * 如果是必填，则验证值是否为空，注意0值是不为空的，所以只验证 null 和 '' 即可
     * 1. 设置了长度限定的值
     * 2. 传递了值（值不为 null 或者 '' ）
     * 3. 值的类型为非数组类型
     * @param $value
     * @param bool $isInit
     * @return bool
     */
    protected function checkRequired($value, $isInit = false)
    {
        if (false === $isInit && intval($this->required) && ($value === null || $value === '')) {
            $this->setError(FormErrorConstant::FORM_DATA_REQUIRED, $this->title);
            return false;
        }

        return true;
    }

    /**
     * 如果限定长度的值不为空 并且 value 有值，则验证长度
     * 这里跟必填验证不冲突，非必填项填写了值，并且有长度要求也是需要验证，所以有验证长度只需要满足两个条件
     * @param $value
     * @return bool
     */
    protected function checkLength($value)
    {
        if (array_filter($this->length) && null !== $value && $value !== '' && false === is_array($value)) {

            $minLength = $this->length[0];
            $maxLength = $this->length[1];

            $length = mb_strlen($value);
            if ($minLength > $length || $maxLength < $length) {
                $this->setError(FormErrorConstant::FORM_DATA_LENGTH_ERROR, $this->title);
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $legalProperties
     * @return bool
     */
    public function setLegalProperties(array $legalProperties)
    {
        $this->legalProperties = $legalProperties;

        return true;
    }

    /**
     * 获取合法的表单属性
     * @return array
     */
    public function getLegalProperties()
    {
        return $this->legalProperties;
    }

    /**
     * 表单配置项的类属性初始化,需要具备以下条件
     *  1. 是在合法的属性范围内
     *  2. 在类中设置了set{Property}方法
     * @param array $data
     * @return bool
     */
    public function init(array $data)
    {
        if (empty($data['name'])) {
            $data['name'] = 'e' . rand(100, 999) . 'n' . rand(100, 999);
        }

        $properties = $this->getLegalProperties();

        if ($properties) {

            foreach ($properties as $property) {

                if (false === isset($data[$property])) {
                    continue;
                }

                $param = $data[$property];

                $setPropertyMethod = 'set' . ucfirst($property);

                /**
                 * 如果没有找到 set属性的方法，则跳过
                 */
                if (false === method_exists($this, $setPropertyMethod)) {
                    continue;
                }

                if ($property == 'value') {
                    $result = $this->$setPropertyMethod($param, true);
                } else {
                    $result = $this->$setPropertyMethod($param);
                }

                if (false === $result) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * 把 配置项的转化为数组  键是属性，值是属性的值
     * @return array
     */
    public function toArray()
    {
        $properties = $this->getLegalProperties();

        $data = [];

        if ($properties) {

            foreach ($properties as $property) {

                $data[$property] = $this->$property;

            }

        }

        return $data;
    }

    /**
     *
     * 把配置项转转化为json字符串
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * 过滤标签
     * @param string $string
     * @return string
     */
    protected function filterString($string)
    {
        return strip_tags(trim($string));
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return $this->getLegalProperties();
    }
}