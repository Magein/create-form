<?php


namespace Magein\createForm\library\constant;


class FormErrorConstant extends Constant
{
    /**
     * 表单配置错误代码
     */
    const FORM_CONFIG_CLASS_ERROR = 10100;

    const FORM_CONFIG_CLASS_NOT_FOUND = 10101;

    const FORM_CONFIG_NAME_NOT_UNIQUE = 10102;

    const FORM_CONFIG_DECODE_FAIL = 10103;

    const FORM_CONFIG_FORMAT_ERROR = 10104;

    /**
     * 表单配置项错误代码
     */
    const FORM_CONFIG_TITLE_ERROR = 10201;

    const FORM_CONFIG_NAME_ERROR = 10202;

    const FORM_CONFIG_REQUIRED_ERROR = 10203;

    const FORM_CONFIG_OPTIONS_ERROR = 10204;

    const FORM_CONFIG_OPTIONS_FORMAT_ERROR = 10205;

    const FORM_CONFIG_OPTIONS_NAME_NOT_UNIQUE = 10206;

    const FORM_CONFIG_LENGTH_PARAM_ERROR = 10207;

    /**
     * 表单收集的数据错误代码
     */
    const FORM_DATA_REQUIRED = 10301;

    const FORM_DATA_LENGTH_ERROR = 10302;

    const FORM_DATA_IS_ARRAY = 10303;

    const FORM_DATA_IS_STRING = 10304;

    const FORM_DATA_NOT_MATCH = 10305;
}