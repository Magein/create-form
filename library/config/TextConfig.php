<?php

namespace Magein\createForm\library\config;

use Magein\createForm\library\constant\FormConfigTypeConstant;
use Magein\createForm\library\FormConfig;

class TextConfig extends FormConfig
{
    /**
     * @var string
     */
    protected $type = FormConfigTypeConstant::TYPE_TEXT;
    
}