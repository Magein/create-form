<?php

namespace Magein\createForm;

use Magein\createForm\library\constant\FormConfigTypeConstant;
use Magein\createForm\library\FormConfig;

/**
 * 自定义渲染类型
 * Class UserConfig
 * @package Magein\createForm
 */
class UserConfig extends FormConfig
{
    protected $type = FormConfigTypeConstant::TYPE_TEXT;

    protected $title = '';

    protected $name = '';

    protected $placeholder = '请输入姓名';

    public function __construct()
    {
        parent::__construct();
        
    }

    public function setValue($value)
    {
        parent::setValue('{修改姓名，修改姓名，修改姓名}');
    }

}