<?php

namespace Magein\createForm\library\filter;

use Magein\createForm\library\constant\FormConfigTypeConstant;

class FormConfigFilter extends Filter
{

    /**
     * @param string $type
     * @return null
     */
    public function type($type)
    {
        $enum = FormConfigTypeConstant::getTypeConstant();

        if (!in_array($type, $enum)) {
            $this->setError('属性不在可选范围内，可选值：' . implode(' ', $enum));
            return null;
        }

        return $type;
    }

    /**
     * @param string $title
     * @return null|string
     */
    public function title($title)
    {
        $title = $this->toString($title);

        if (empty($title)) {
            $this->setError('标题为空');
            return null;
        }

        return $title;
    }

    /**
     * @param string $name
     * @return null|string
     */
    public function name($name)
    {
        $name = $this->toString($name);

        if (!preg_match('/^[a-zA-Z][\w]{1,30}$/', $name)) {
            $this->setError('name值为数字字母下划线，且字母开头，长度1-30个字符');
            $name = null;
        }

        return $name;
    }

    /**
     * @param int $required
     * @return int
     */
    public function required($required)
    {
        $required = intval($required) == 1 ? 1 : 0;

        return $required;
    }

    /**
     * @param array|string $value
     * @return array|string
     */
    public function value($value)
    {
        if (is_array($value)) {
            return array_values($value);
        }

        $value = $this->toString($value);

        return $value;
    }

    /**
     * @param string $placeHolder
     * @return string
     */
    public function placeHolder($placeHolder)
    {
        $placeHolder = $this->toString($placeHolder);

        return $placeHolder;
    }

    /**
     * @param array $options
     * @return string
     */
    public function options($options)
    {
        if (!is_array($options)) {
            $this->setError('可选值属性的值为数组类型');
            return null;
        }

        if (count($options) > 20) {
            return null;
        }

        return array_values($options);
    }

    /**
     * @param string $length
     * @return null
     */
    public function length($length)
    {
        if (!is_array($length)) {
            $length=json_decode($length,true);
        }

        if (count($length) > 2){
            $this->setError('长度属性的值为数组类型');
            return null;
        }

        return $length;
    }
}