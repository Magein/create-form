<?php

namespace Magein\createform;

use Magein\createform\library\constant\FormConfigTypeConstant;
use Magein\createform\library\item\FormItemConfig;

/**
 * 自定义渲染类型
 * Class UserConfig
 * @package Magein\createform
 */
class UserConfig extends FormItemConfig
{
    public function __construct()
    {
        parent::__construct();

        $this->setType(FormConfigTypeConstant::TYPE_TEXT);
        $this->setTitle('姓名');
        $this->setName('username');
        $this->setValue('小马哥');
        $this->setPlaceholder('请输入姓名');
        $this->setRequired(1);
    }

    public function setValue($value)
    {
        parent::setValue('{修改姓名，修改姓名，修改姓名}');
    }

}