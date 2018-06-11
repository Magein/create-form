<?php

namespace Magein\createForm\library;

use Magein\createForm\library\constant\FormErrorConstant;

trait FormError
{
    /**
     * @var array
     */
    private $error = [];

    /**
     * @var integer
     */
    private $code;

    public function initErrors()
    {
        $this->error = [
            FormErrorConstant::REGISTER_CLASS_ILLEGAL => '注册表单项处理类需要继承formConfig类',
            FormErrorConstant::REGISTER_CLASS_NOT_FOUND => '表单项处理类找不到',
            FormErrorConstant::FORM_CONFIG_DECODE_FAIL => '表单项解码失败，应该传递一个json类型的字符串',
            FormErrorConstant::FORM_CONFIG_FORMAT_ERROR => '表单项中存在不是对象的项或者项为空',
            FormErrorConstant::FORM_CONFIG_OPTIONS_ERROR => '表单项中OPTIONS是一个数组',
            FormErrorConstant::FORM_CONFIG_OPTIONS_FORMAT_ERROR => '表单项中OPTIONS需要包含name,value属性',
            FormErrorConstant::FORM_CONFIG_OPTIONS_NAME_NOT_UNIQUE => '表单项中OPTIONS中name值是唯一的',
            FormErrorConstant::FORM_CONFIG_LENGTH_PARAM_ERROR => '表单项的长度是一个长度为2的数组',
            FormErrorConstant::FORM_DATA_REQUIRED => '表单项的值必须填写',
            FormErrorConstant::FORM_DATA_LENGTH_ERROR => '表单项的值长度需要在规定的范围内',
            FormErrorConstant::FORM_DATA_IS_ARRAY => '表单项的值是一个数组',
            FormErrorConstant::FORM_DATA_IS_STRING => '表单项的值是一个字符串或者整型',
            FormErrorConstant::FORM_DATA_NOT_MATCH => '表单项的值需要在可选列表内'
        ];

    }

    /**
     * @param integer $code
     * @param string $message
     */
    public function setError($code, $message = '')
    {
        $this->code = $code;
        $this->error = $message;
    }

    /**
     * @param $code
     * @return string
     */
    public function getError($code = null)
    {
        if ($code == null) {
            $code = $this->code;
        }

        return isset($this->error[$code]) ? $this->error[$code] : $this->error;
    }

    public function getCode()
    {
        return $this->code;
    }
}