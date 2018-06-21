<?php

namespace Magein\createForm\library\config;

use Magein\createForm\library\constant\FormConfigTypeConstant;

/**
 * Class RadioConfig
 * @package Magein\createForm\library\config
 *
 *  继承 SelectConfig 原因是：
 *      radio 和 select 从收集数据的结果来看是一样的， 都要求值在可选列表中只是渲染的方式不一样而已
 *      区别在于渲染的时候 可选值(即options参数) 如果较少选用radio ，如果 可选值 比较多使用 select
 *      移动端偏向于使用 select 多一点
 */
class RadioConfig extends SelectConfig
{
    /**
     * @var string
     */
    protected $type = FormConfigTypeConstant::TYPE_RADIO;

}