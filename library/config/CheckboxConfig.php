<?php

namespace Magein\createForm\library\config;

use Magein\createForm\library\constant\FormConfigTypeConstant;
use Magein\createForm\library\constant\FormErrorConstant;

class CheckboxConfig extends SelectConfig
{
    /**
     * @var string
     */
    protected $type = FormConfigTypeConstant::TYPE_CHECKBOX;

    /**
     * @param string $value
     * @param bool $isInit
     * @return bool
     */
    public function setValue($value, $isInit = false)
    {

        /**
         * 这里不要执行父类的 setValue() 父类是集成 SelectConfig 他们验证的方式不一样，只需要重新调用一遍 验证必填，长度的方法即可
         */

        $this->value = $value;

        if (false === $this->checkRequired($value, $isInit) || false === $this->checkLength($value)) {
            return false;
        }

        if (false === $isInit) {

            /**
             * 值如果包含逗号等特殊符号，使用字符串分隔的方法会不准确，所以使用数组比较准确
             */
            if (!is_array($this->value)) {
                $this->setError(FormErrorConstant::FORM_DATA_IS_ARRAY, $this->title);
                return false;
            }

            $names = [];
            foreach ($this->options as $option) {
                $names[] = $option['name'];
            }

            foreach ($this->value as $value) {
                if (!in_array($value, $names)) {
                    $this->setError(FormErrorConstant::FORM_DATA_NOT_MATCH, $this->title);
                    return false;
                }
            }
        }

        return true;
    }

}