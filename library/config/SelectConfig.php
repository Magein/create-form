<?php

namespace Magein\createForm\library\config;

use Magein\createForm\library\constant\FormConfigTypeConstant;
use Magein\createForm\library\constant\FormErrorConstant;
use Magein\createForm\library\FormConfig;

/**
 * Class SelectConfig
 * @package Magein\createForm\library\config
 *
 * RadioConfig和CheckboxConfig都继承此类，三者的区别是
 * 1. $type属性方法不同
 * 2. RadioConfig、SelectConfig 的 setValue() 相同，跟 CheckboxConfig 的 setValue() 方法不同
 *
 */
class SelectConfig extends FormConfig
{
    /**
     * @var string
     */
    protected $type = FormConfigTypeConstant::TYPE_SELECT;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * 合法的表单项属性中增加options属性
     * @return array
     */
    public function getLegalProperties()
    {
        $properties = parent::getLegalProperties();

        $properties[] = 'options';

        return $properties;
    }

    /**
     * @param array $options
     * @return bool
     */
    public function setOptions($options)
    {
        if (empty($options) || !is_array($options)) {
            $this->setError(FormErrorConstant::FORM_CONFIG_OPTIONS_ERROR);
            return false;
        }

        $names = [];

        foreach ($options as $key => $option) {

            if (!isset($option['name']) || !isset($option['value'])) {
                $this->setError(FormErrorConstant::FORM_CONFIG_OPTIONS_FORMAT_ERROR);
                return false;
            }

            $option['name'] = trim($option['name']);
            $option['value'] = trim($option['value']);

            $options[$key] = $option;

            $names[] = $option['name'];
        }

        # 验证name值是否有重复的
        if (count(array_unique($names)) != count($names)) {
            $this->setError(FormErrorConstant::FORM_CONFIG_OPTIONS_NAME_NOT_UNIQUE);
            return false;
        }

        $this->options = $options;

        return true;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $value
     * @param bool $isInit
     * @return bool
     */
    public function setValue($value, $isInit = false)
    {
        /**
         * 父类已经验证是否必填，长度，要特别注意0值，这里只需要验证值是否在可选列表内即可
         */
        $result = parent::setValue($value, $isInit);

        if (false === $result) {
            return false;
        }

        if (false === $isInit) {
            if (!is_string($this->value) && !is_int($this->value)) {
                $this->setError(FormErrorConstant::FORM_DATA_IS_STRING, $this->title);
                return false;
            }

            $names = [];
            foreach ($this->options as $option) {
                $names[] = $option['name'];
            }

            if (!in_array($this->value, $names)) {
                $this->setError(FormErrorConstant::FORM_DATA_NOT_MATCH, $this->title);
                return false;
            }
        }
        
        return true;
    }
}