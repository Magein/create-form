<?php

namespace Magein\createForm\library;

use Magein\createForm\library\constant\FormErrorConstant;

trait FormError
{

    /**
     * 错误代码
     * @var integer
     */
    private $code;

    /**
     * 错误信息
     * @var string
     */
    private $error;

    /**
     * 错误的配置项
     * @var string
     */
    private $errorConfig;

    /**
     * 错误代码说明
     * @return array
     */
    public function initErrors()
    {
        return [
            FormErrorConstant::FORM_CONFIG_CLASS_ERROR => '注册表单项处理类需要继承 FormConfig 类',
            FormErrorConstant::FORM_CONFIG_CLASS_NOT_FOUND => '表单项处理类找不到，需要传递一个 class 值',
            FormErrorConstant::FORM_CONFIG_DECODE_FAIL => '表单项解码失败，需要传递一个 json 类型的字符串',
            FormErrorConstant::FORM_CONFIG_FORMAT_ERROR => '表单项中存在不是对象的项或者项为空',
            FormErrorConstant::FORM_CONFIG_TITLE_ERROR => '表单项中 title 是必须要声明的',
            FormErrorConstant::FORM_CONFIG_NAME_ERROR => '表单项中 name 值只能是数字，字母，下划线组合，可以为空',
            FormErrorConstant::FORM_CONFIG_REQUIRED_ERROR => '表单项中 required 可选值为0和1',
            FormErrorConstant::FORM_CONFIG_OPTIONS_ERROR => '表单项中 options 需要一个不为空的数组',
            FormErrorConstant::FORM_CONFIG_OPTIONS_FORMAT_ERROR => '表单项中 options 需要包含 name , value 属性',
            FormErrorConstant::FORM_CONFIG_OPTIONS_NAME_NOT_UNIQUE => '表单项中 options 中 name 值必须是唯一的',
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
     * @param string $errorConfig 出错的配置项,可以是 title name 或者 第一项，可以更准确的定位到错误信息
     * @param string $error
     */
    public function setError($code, $errorConfig = null, $error = null)
    {
        $this->code = $code;
        $this->errorConfig = $errorConfig;
        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getError()
    {
        $initErrors = $this->initErrors();

        return isset($initErrors[$this->code]) ? $initErrors[$this->code] : $this->error;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getErrorConfig()
    {
        return $this->errorConfig;
    }
}